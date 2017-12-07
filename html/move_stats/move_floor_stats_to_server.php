<!DOCTYPE html>
<?php
  date_default_timezone_set('America/Chicago');
  $event_id=$_REQUEST['event_id'];
  echo "ready to start<br>";

  try {
//75.134.205.60
    $options = array(
      PDO::ATTR_PERSISTENT    => true,
      PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
    );
    $remote_db = new PDO("mysql:192.168.1.201:3306;", 'StatDataBot' , 'Gr1ffB0t@Werk', $options);  
    $remote_db->select_db('stats');
    echo "remote_db connection created.<br>";  
  } catch (Exception $e) {
  echo "1<br>";
    echo 'Caught exception: ',  $e->getMessage(), "\n";
  }
  //prevent duplication of floor stats by clearing the old set out of the remote table 
  //before I move this set in.
  $query="DELETE FROM floor_stats WHERE event_id = $event_id";
  try {
    $remote_stmt = $remote_db->prepare($query);
    $remote_stmt->execute();
    echo "existing remote records for event_id:$event_id removed.<br>";  
  } catch (Exception $e) {
  echo "2<br>";
    echo 'Caught exception: ',  $e->getMessage(), "\n";
  }


  // Prepare the statement to insert new records
  $query="INSERT INTO floor_stats VALUES(?,?,?,?,?,?,?,?,?,?,?)";
  try {
    $remote_stmt = $remote_db->prepare($query);
  } catch (Exception $e) {
  echo "3<br>";
    echo 'Caught exception: ',  $e->getMessage(), "\n";
  }
  echo "remote PDO statement prepared.<br>";  

  //get the records to insert from the local database
  $local_db = new PDO('mysql:host=192.168.1.200;dbname=stats', 'StatDataBot' , 'Gr1ffB0t@Werk', $options);  
  echo "local PDO connection created.<br>";  
  $query="SELECT * FROM floor_stats WHERE event_id = $event_id";
  try {
    $local_stmt = $local_db->prepare($query);
    echo "debugDumpParams:";
    echo $local_stmt->debugDumpParams()."<br>";
    
    if ($local_stmt->execute())
    {
      echo " success<br>";
    }
    else
    {
      echo " failure<br>";
      echo 'Caught exception: ',  $e->getMessage(), "\n";
    }    
  } catch (Exception $e) {
  echo "4<br>";
    echo 'Caught exception: ',  $e->getMessage(), "\n";
  }
  echo "local statement executed, selecting records for event_id $event_id.<br>";  

  //load the floor_stats into the remote database
  while ($res = $local_stmt->fetch(PDO::FETCH_ASSOC)){
    echo $res['act']."<br>"; 
    try {
      $remote_stmt->bindParam(1, $res['act_time']); 
      $remote_stmt->bindParam(2, $res['event_id']); 
      $remote_stmt->bindParam(3, $res['quarter']); 
      $remote_stmt->bindParam(4, $res['act']); 
      $remote_stmt->bindParam(5, $res['feet']); 
      $remote_stmt->bindParam(6, $res['sine']); 
      $remote_stmt->bindParam(7, $res['person_id']); 
      $remote_stmt->bindParam(8, $res['person_name']); 
      $remote_stmt->bindParam(9, $res['team_id']); 
      $remote_stmt->bindParam(10, $res['team_name']); 
      $remote_stmt->bindParam(11, $res['layup']);
      $remote_stmt->execute();
    } catch (Exception $e) {
  echo "5<br>";
      echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
  }

  //get a count of the records copied into the remote table.
  $query="SELECT act_time FROM floor_stats WHERE event_id = $event_id";
  $remote_stmt = $remote_db->prepare($query);
  $remote_stmt->execute();
  $remote_records = $remote_stmt->fetchAll(PDO::FETCH_ASSOC);

  echo "Procedure complete. ".count($remote_records)." records INSERTED.<br>";  

?>
<html>
    <head>
        <title>Header</title>
        <meta http-equiv=”Pragma” content=”no-cache”>
        <meta http-equiv=”Expires” content=”-1″>
        <meta http-equiv=”CACHE-CONTROL” content=”NO-CACHE”>
    </head>
    <body>   
    </body>
</html>