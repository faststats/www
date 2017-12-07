<!DOCTYPE html>

<html>
<?php
	echo "save_stat.php<br>=======================<br>";
	echo "including statsInfo.php<br>";
	include("../inc/info/statsInfo.php");
	echo  "including db.class.php<br>";
	include("../inc/libraries/db.class.php");
	echo "Setting the TimeZone<br>";
  	date_default_timezone_set('America/Chicago');
  	echo "Instantiating the db object<br>";
  	$db = new db();
  	echo "Ready to Rock<BR><BR>";

	$q_string=urldecode($_SERVER['QUERY_STRING']);
	parse_str($q_string,$fields);
	$sql="INSERT INTO Floor_Stats (act_time, quarter, event_id, act, feet, sine, person_id,person_name, team_id,team_name,layup)";
	$sql.=" VALUES(:v_act_time, :v_quarter, :v_event_id, :v_act, :v_feet, :v_sine, :v_person_id,:v_person_name, :v_team_id, :v_team_name, :v_layup)";
	print_r($fields);
	echo "<br><br>";
	$db->beginTransaction();
	$db->query($sql,1);
	$db->bind(':v_act_time', $fields['act_time'],1); 
	$db->bind(':v_quarter', $fields['quarter'],1); 
	$db->bind(':v_event_id', $fields['event_id'],1); 
	$db->bind(':v_act', $fields['act'],1); 
	$db->bind(':v_feet', $fields['feet'],1); 
	$db->bind(':v_sine', $fields['sine'],1); 
	$db->bind(':v_person_id', $fields['person_id'],1); 
	$db->bind(':v_person_name', $fields['person_name'],1); 
	if ($fields['person_id'] >= 10000) {
		$fields['team_id'] = $fields['person_id'];
	}
	$db->bind(':v_team_id', $fields['team_id'],1); 
	$db->bind(':v_team_name', $fields['team_name'],1); 
	$db->bind(':v_layup', $fields['layup'],1); 
	$execResult=$db->execute();
	if($execResult==1){
		$execResult="Success";
	}else{
		$execResult="Failed";		
	}
	$x=$db->endTransaction();
	echo "<BR>save_action.php - execute returns:$execResult<br>endTransaction returns:$x<br>";

	//adust the score in the events table
	$event_id = $fields['event_id']; 
	$team_id = $fields['team_id'];
	$act = $fields['act'];
	
	$sql = "SELECT * FROM events WHERE id = $event_id;";
    $db->query($sql);
    $events = $db->resultset();
   $shotVal = 0;
    switch($act) {
    	case "2made":
    		$shotVal = 2;
    		break;
    	case "3made":
    		$shotVal = 3;
    		break;
    	case "FTmade":
    		$shotVal = 1;
    		break;
    	default:
    		$shotVal = 0;
    }
    if($shotVal > 0 and $team_id <= 1000) { //this will handle the team score
    	$newScore = $events[0]['team_score'] + $shotVal;
    	$sql = "UPDATE events set team_score = $newScore where id = $event_id;";
    }
    if($shotVal > 0 and $team_id >=10000) { //this will handle the opponent score
    	$newScore = $events[0]['opp_score'] + $shotVal;
    	$sql = "UPDATE events set opp_score = $newScore where id = $event_id;";
    }
    if($shotVal > 0) {  //process the sql statement
		echo "<br><br>incrementing the score (+$shotVal) for team_id: $team_id in the events table.";
		$db->beginTransaction();
		$db->query($sql,1);
		$execResult=$db->execute();
		if($execResult==1){
			$execResult="Score increment was a Success";
		}else{
			$execResult="Score increment Failed";		
		}
		$x=$db->endTransaction();
		echo "<BR>save_action.php (event table score increment) - execute returns:$execResult<br>";
		echo "endTransaction returns:$x<br>";
    }

//=====================
	if($fields['act'] == 'Steal' and $fields['person_id'] <=999) {
		echo "<br><br>Adding an opponent TO if we got a steal<br>";
		$db->beginTransaction();
		$sql="INSERT INTO Floor_Stats (act_time, quarter, event_id, act, feet, sine, person_id,person_name, team_id,team_name,layup)";
		$sql.=" VALUES(:v_act_time, :v_quarter, :v_event_id, :v_act, :v_feet, :v_sine, :v_person_id,:v_person_name, :v_team_id, :v_team_name, :v_layup)";
		$db->query($sql,1);
		$db->bind(':v_act_time', $fields['act_time'] + 100, 1); 
		$db->bind(':v_quarter', $fields['quarter'], 1); 
		$db->bind(':v_event_id', $fields['event_id'] ,1); 
		$db->bind(':v_act', 'TO', 1); 
		$db->bind(':v_feet', 0, 1); 
		$db->bind(':v_sine', 0, 1); 
		$db->bind(':v_person_id', $fields['opp_id'], 1); 
		$db->bind(':v_person_name', $fields['opp_name'], 1); 
		$db->bind(':v_team_id', $fields['team_id'], 1); 
		$db->bind(':v_team_name', $fields['opp_name'], 1); 
		$db->bind(':v_layup', $fields['layup'], 1); 
		$execResult=$db->execute();
		if($execResult==1){
			$execResult="Success adding TO";
		}else{
			$execResult="Failed Adding TO";		
		}
		$x=$db->endTransaction();
		echo "<BR>save_action.php (adding opponent TO upon our steal) - execute returns:$execResult<br>endTransaction returns:$x<br>";
		echo "5<br>";
	}

?>
	<head>
		<title>Save Act</title>
		<meta http-equiv=”Pragma” content=”no-cache”>
		<meta http-equiv=”Expires” content=”-1″>
		<meta http-equiv=”CACHE-CONTROL” content=”NO-CACHE”>

	</head>
	<body>
	</body>
</html>
