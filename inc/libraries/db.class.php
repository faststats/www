<?php
class db 
	{
    private $host      = DB_HOST;
    private $user      = DB_USER;
    private $pass      = DB_PASS;
    private $dbname    = DB_NAME;
 
    private $dbh;
    private $error;
	private $stmt; 
    public $affected_rows;
    public function __construct(){
		//echo "host=".$this->host."|user=".$this->user."|pass=".$this->pass."|dbname=".$this->dbname."<BR>";
        // Set DSN
				$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        // Set options
			$options = array(
            	PDO::ATTR_PERSISTENT    => true,
            	PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
        	);
        // Create a new PDO instanace
        try{
        	$this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        	}
        // Catch any errors
        catch(PDOException $e){
                echo  "PDO:Database Connection Error: See Log<br>";
                $error_time = date('l jS \of F Y h:i:s A');
                $error_string = $this->clean_string($this->getErrorInfo());
                $error_entry = "PDO Database Connection Error @ $error_time\n$sql:$error_string\n=================\n";
                error_log($error_entry, 3, "c:/wamp/logs/stat-errors.log");
                $retVal=$this->error;
        		}
    		}

		public function getDescription() {
			$myDesc="This is the db class.<BR>";
			$myDesc.="This class handles the interaction between the Array Manager class<BR>";
			$myDesc.="and the mySQL database using the PHP PDO protocol.<BR>";
			$myDesc.="The developer will interact with the ArrayManager class which will get<BR>";
			$myDesc.="data structure specifics from the DataDefs class <BR>";
			$myDesc.="in order to do its work, then when it is time to interact with the database<BR>";
			$myDesc.="the ArrayManager class will call db->methods to handle that interaction.<BR>";
			$myDesc.="The developer will never have to mess with the database again.<BR>";
			$myDesc.="Version 0.5 5.20.2017  Darrell Oller<BR><BR>";
			return($myDesc);
			}
		
		public function query($query,$show=0){
			if($show==1){
				echo "query=$query<br>";
        	}
	        // Prepare the PDO query
	        try{
				$this->stmt = $this->dbh->prepare($query);
			}
    	    // Catch any errors
	        catch(PDOException $e){
                echo  "Query Error: See Log<br>";
                $clean_sql = $this->clean_string($query);
                echo  "Query Error: See Log<br>";
                $error_time = date('l jS \of F Y h:i:s A');
                echo  "Query Error: See Log<br>";
                $error_string = $this->clean_string($this->getErrorInfo());
                echo  "Query Error: See Log<br>";
                $error_entry = "PDO Query Statement Prep Error @ $error_time\n$clean_sql:$error_string\n=================\n";
                echo  "Query Error: See Log<br>";
                error_log($error_entry, 3, "c:/wamp/logs/stat-errors.log");
                echo  "Query Error: See Log<br>";
                $retVal=$this->error;
        	}
    	}
			

		public function bind($param, $value, $type = null, $showParam=0){
	    	if (is_null($type)) {
    		    switch (true) {
          			case is_int($value):
            			$type = PDO::PARAM_INT;
            			break;
          			case is_bool($value):
            			$type = PDO::PARAM_BOOL;
            			break;
          			case is_null($value):
            			$type = PDO::PARAM_NULL;
            			break;
          			default:
            		$type = PDO::PARAM_STR;
        		}
        	}
	        // Bind the value to the parameter
	        try{
	    		$this->stmt->bindValue($param, $value, $type);
	        }
	        // Catch any errors
	        catch(PDOException $e){
                echo  "PDO:Binding Error: See Log<br>";
                $error_time = date('l jS \of F Y h:i:s A');
                $error_string = $this->clean_string($this->getErrorInfo());
                $error_entry = "PDO Binding Error @ $error_time\n$sql:$error_string\n=================\n";
                error_log($error_entry, 3, "c:/wamp/logs/stat-errors.log");
                $retVal=$this->error;
	        }

			if($showParam==1) {
				echo "Binding Parameter: $param=$value  type=$type<br>";
			}
		}

	public function execute($sql = ""){
   		// Bind the value to the parameter
	    try{
			$retVal=$this->stmt->execute();
            $this->affected_rows = $this->stmt->rowCount();
		}	        
	    // Catch any errors
	    catch(PDOException $e){
            echo  "PDO:Query Error: See Log | $sql<br>";
            $error_time = date('l jS \of F Y h:i:s A');
            $error_string = $this->clean_string($this->getErrorInfo());
            $error_entry = "PDO Execution Error @ $error_time\n$sql:$error_string\n=================\n";
            error_log($error_entry, 3, "c:/wamp/logs/stat-errors.log");
			$retVal=$this->error;
	    }
		return $retVal;
	}

	public function getErrorInfo() {
		$arr = $this->stmt->errorInfo();
		if($arr[0]=="00000") {
			return "<b>NO ERRORS DETECTED</b><br>";
		}else{
			return "\nERRORS DETECTED\nError Code: ".$arr[0]."\nDriver Specific Code: ".$arr[1]."\nDriver Specific Err Msg: ".$arr[2]."<BR>";
		}
	}

	public function resultset($sql = ""){
	    $this->execute();
    	$ar=$this->stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->affected_rows = $this->stmt->rowCount();
        return $ar;
		}
    public function closeCursor() {
        $this->stmt->closeCursor();
    }

	public function single(){
        $this->execute();
        $ar = $this->stmt->fetch(PDO::FETCH_ASSOC);
        return $ar;
	}

	public function rowCount(){
        return $this->stmt->rowCount();
	}
		
	public function lastInsertId(){
        return $this->dbh->lastInsertId();
	}

	public function beginTransaction(){
    	return $this->dbh->beginTransaction();
	}

	public function endTransaction(){
        return $this->dbh->commit();
	}
	
	public function cancelTransaction(){
        return $this->dbh->rollBack();
	}

	public function debugDumpParams(){
        return $this->stmt->debugDumpParams();
	}

	public function GetVersion() {
		return "1.0";
	}

    public function clean_string($mystring) {
        $clean = str_replace('"' ,"^" , $mystring);
        $clean = str_replace("'" ,"~" , $clean);
        $clean = str_replace(';' ,"|" , $clean);
        return $clean;
    }


} //End Of Class 				

	
?>
