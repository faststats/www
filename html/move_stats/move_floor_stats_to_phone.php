<!DOCTYPE html>
<?php
  include("../inc/info/statsInfo.php");
  include("../inc/libraries/db.class.php");
  date_default_timezone_set('America/Chicago');
  $db = new db();
  $event_id=$_REQUEST['event_id'];

  $sql = "SELECT * FROM floor_stats where event_id = $event_id";
  $db->query($sql,1);
  $stats = $db->resultset();
  $stat_records = count($stats);

//empty out the temp_floor_stats table on the phone
  $phone_db = new PDO("mysql:host=192.168.1.202;dbname=".DB_NAME, DB_USER , DB_PASS);  
  $query="TRUNCATE temp_floor_stats";
  $phone_stmt = $phone_db->prepare($query);
  $phone_stmt->execute();
echo "started at:".microtime()."<br>";

  $query="INSERT INTO temp_floor_stats VALUES(?,?,?,?,?,?,?,?,?,?,?)";
  $phone_stmt = $phone_db->prepare($query);
  foreach ($stats as $res) {
    $phone_stmt->bindParam(1, $res['act_time']); 
    $phone_stmt->bindParam(2, $res['event_id']); 
    $phone_stmt->bindParam(3, $res['quarter']); 
    $phone_stmt->bindParam(4, $res['act']); 
    $phone_stmt->bindParam(5, $res['feet']); 
    $phone_stmt->bindParam(6, $res['sine']); 
    $phone_stmt->bindParam(7, $res['person_id']); 
    $phone_stmt->bindParam(8, $res['person_name']); 
    $phone_stmt->bindParam(9, $res['team_id']); 
    $phone_stmt->bindParam(10, $res['team_name']); 
    $phone_stmt->bindParam(11, $res['layup']);
    $phone_stmt->execute();
  }
echo "ended at:".microtime()."<br>";

  $query="SELECT count(*) as phone_recs FROM temp_floor_stats";
  $phone_stmt = $phone_db->prepare($query);
  $result = $phone_stmt->execute();
  $phone_recs = $phone_stmt->fetch(PDO::FETCH_ASSOC);
print_r($phone_recs);
echo "<br>";
  $phone_records = $phone_recs['phone_recs'];
  echo "Phone Records after processing:$phone_records<br>";
  if($phone_records != $stat_records) {
    echo "there is some problem.<br>";
  } else {
    echo "All records copied successfully.<br>";
  }
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