<?php
	include("../inc/info/statsInfo.php");
	include("../inc/libraries/db.class.php");
  	date_default_timezone_set('America/Chicago');
  	$db = new db();
	
	echo "delete_stat.PHP<BR>";
	$event_id = $_REQUEST['event_id'];
	echo "event_id=$event_id<br>";
	$person_id = $_REQUEST['person_id'];
	echo "person_id=$person_id<br>";
	$act = $_REQUEST['act'];
	echo "act=$act<br>";


//	$q_string=urldecode($_SERVER['QUERY_STRING']);
//	echo "<br>q_string=".$q_string."<BR>";
//	parse_str($q_string,$fields);	
//	print_r($fields);
	echo "<BR>";
	if(strpos("*3made*3missed*2made*2missed",$act) >= 0) {
		echo "in the shooting delete area<br>";
		$act_string = substr($act,0,1);
		$act_string.="%";
		echo "act_string=$act_string<br>";
		$sql="DELETE FROM Floor_Stats WHERE ";
		$sql.="event_id = $event_id AND person_id = $person_id AND ";
		$sql .= "act LIKE '$act_string' ORDER BY act_time DESC LIMIT 1";
		echo "sql=$sql<BR>";
		$db->beginTransaction();
		$db->query($sql,1);
		$execResult=$db->execute();
		if($execResult==1){
			$execResult="Success";
		}else{
			$execResult="Failed";		
		}
		$x=$db->endTransaction();
		echo "DeleteAct.php (shot)- execute returns:$execResult<br>endTransaction returns:$x<br>";
	} else {
		echo "in the stat delete area<br>";
		$sql="DELETE FROM Floor_Stats WHERE event_id = $event_id and act = '$act' and person_id = $person_id ORDER BY act_time DESC Limit 1";
		echo "sql=$sql<BR>";
		$db->beginTransaction();
		$db->query($sql,1);
		$execResult=$db->execute();
		if($execResult==1){
			$execResult="Success";
		}else{
			$execResult="Failed";		
		}
		$x=$db->endTransaction();
		echo "delete_stat.php (regular stat)- execute returns:$execResult<br>endTransaction returns:$x<br>";
	}	
?>