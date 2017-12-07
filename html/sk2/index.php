<!DOCTYPE html>
<?php
  include("../inc/info/statsInfo.php");
  include("../inc/libraries/db.class.php");
  date_default_timezone_set('America/Chicago');
  $db = new db();
  $event_id=$_REQUEST['event_id'];
  $sql = "SELECT * FROM event_players WHERE event_id = $event_id ORDER BY sequence; SHOW ERRORS;";
  $db->query($sql);
  $event_players = $db->resultset();

  // Get the most recent quarter from the floor stats
  $sql = "SELECT * FROM Floor_Stats WHERE event_id = $event_id ORDER BY act_time DESC LIMIT 1; SHOW ERRORS;";
  $db->query($sql);
  $floor_stats = $db->resultset();
  if (count($floor_stats) == 0) {
    $qtr = 1;
  } else {
      $qtr = $floor_stats[0]['quarter'];
  }

  //get the team id from the events table
  $sql = "SELECT * FROM events WHERE id = $event_id";
  $db->query($sql);
  $events = $db->resultset();
//    echo "<pre>";
//    print_r($events);
//    echo "</pre>";
  $team_id = $events[0]['team_id'];
  $team_name = $events[0]['team_name'];
  $team_score = $events[0]['team_score'];
  $opp_id = $events[0]['opponent_id'];
  $opp_pid = $opp_id + 10000;
  $opp_name = $events[0]['opp_name'];
  $opp_score = $events[0]['opp_score'];
  $gdt = $events[0]['event_date_time'];
  $home_or_road = $events[0]['home_or_road'];
  // add the opposing player to the array string
  $array_string = "";
  $array_string .= "players.push({pid : $opp_pid, name : '$opp_name', uni : $opp_pid, sequence : 0, team_id : $opp_id, team_name : '$opp_name'});\n";

  // Now loop through the players and do the same thing.
  foreach($event_players as $player) {
    $pid = $player['player_id'];
    $p_name = $player['last_first_init'];
    $p_uni = $player['uni'];
    $p_seq = $player['sequence'];
    $array_string.="players.push({pid : $pid, name : '$p_name', uni : $p_uni, sequence : $p_seq, team_id : $team_id, team_name : '$team_name'});\n";
  }
?>
<html>
<head>
  <title>StatKeeper</title>
  <meta http-equiv=”Pragma” content=”no-cache”>
  <meta http-equiv=”Expires” content=”-1″>
  <meta http-equiv=”CACHE-CONTROL” content=”NO-CACHE”>
  <link rel = "stylesheet" href = "../inc/css/sk2.css?n=<?php echo time(); ?>" >
  <script src = "../inc/js/sk2.js?n=<?php echo time(); ?>"></script>
  <script src = "../inc/js/swipe_object2.js?n=<?php echo time(); ?>"></script>
  <?php
    echo "<script>";
    echo "  players = [];";
    echo "\n";
    echo "  event_id = $event_id;\n";
    echo "  team_id = $team_id;\n";
    echo "  team_name = '$team_name';\n";
    echo "  opp_name = '$opp_name';\n";
    echo "  opp_id = '$opp_pid';\n";
    echo "  gdt = '$gdt';\n";
    echo "  home_or_road = '$home_or_road';\n";
    echo "  qtr = 1;\n";
    echo "  $array_string\n";
    echo "</script>";
  ?>  
</head>
  <body onload='init();'>
  <div id='hist_0' class='hist' style="opacity:.2">--</div>
  <div id='hist_1' class='hist' style="opacity:.3">--</div>
  <div id='hist_2' class='hist' style="opacity:.4">--</div>
  <div id='hist_3' class='hist' style="opacity:.5">--</div>
  <div id='hist_4' class='hist' style="opacity:.6">--</div>
  <div id='hist_5' class='hist' style="opacity:.7">--</div>
  <div id='hist_6' class='hist' style="color:red">--</div>
  <iframe id="helper" name="helper" style="background-color:white;position:absolute;width:400px;left:5px;top:0;display:none;z-index:100" ></iframe>
  <iframe id="scoreboard" name="scoreboard" scrolling=no style="position:fixed;width:300px;left:470px;top:2px;height:125px;background-color:#63666b;border-radius:20px;" ></iframe>
  <div id='quarters' class='quarters'>
    <input type=button id=qtrbtn_1 class=qtrbtn value='Q1' onclick='q_click(1);' style='left:0px;'>
    <input type=button id=qtrbtn_2 class=qtrbtn value='Q2' onclick='q_click(2);' style='left:70px;'>
    <input type=button id=qtrbtn_3 class=qtrbtn value='Q3' onclick='q_click(3);' style='left:140px;'>
    <input type=button id=qtrbtn_4 class=qtrbtn value='Q4' onclick='q_click(4);' style='left:210px;'>
    <input type=button id=qtrbtn_5 class=qtrbtn value='OT1' onclick='q_click(5);' style='left:280px;'>
    <input type=button id=qtrbtn_6 class=qtrbtn value='OT2' onclick='q_click(6);' style='left:350px;'>
    <input type=button id=qtrbtn_7 class=qtrbtn value='OT3' onclick='q_click(7);' style='left:420px;'>
  </div>
  <div id='grid' class='grid'>
    <button type=button id=which_court class=which_court onclick="swap_opp_direction(this);" value="Right">Right >></button>
  </div>
  <div id=court_basket_left class='court_basket_left'>
    <div id='basket_left' class='basket_left' style='display:inline-block;'>&nbsp;</div> 
    <div id='ft_spot_left' class='ft_spot_left' style='display:inline-block;'>&nbsp;</div> 
  </div>
  <div id=court_basket_right class='court_basket_right'>
    <div id='basket_right' class='basket_right' style='display:inline-block;'>&nbsp;</div> 
    <div id='ft_spot_right' class='ft_spot_right' style='display:inline-block;'>&nbsp;</div> 
  </div>
  </body>
</html>