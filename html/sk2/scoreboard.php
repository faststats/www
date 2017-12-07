<!DOCTYPE html>
<?php
    include("../inc/info/statsInfo.php");
    include("../inc/libraries/db.class.php");
    date_default_timezone_set('America/Chicago');
    $db = new db();

    sleep(1);
    $debug = $_REQUEST['debug'];
    $event_id = $_REQUEST['event_id'];

    $sql = "SELECT * FROM events WHERE id = $event_id; SHOW ERRORS;";
    $db->query($sql,$debug);
    $events = $db->resultset();
    $team_name = $events[0]['team_name'];
    $team_score = $events[0]['team_score'];
    $opp_name = $events[0]['opp_name'];
    $opp_score = $events[0]['opp_score'];
    $db->closeCursor();

    $scoreboard = "<table width=100% height=100% >";
    $scoreboard .= "<thead>";
    $scoreboard .= "<tr>";
    $scoreboard .= "<td width=50% style='font-size:20px;color:orange;'>";
    $scoreboard .= "<b><center>$team_name</center></b>";
    $scoreboard .= "</td>";
    $scoreboard .= "<td width=50% style='font-size:20px;color:orange;'>";
    $scoreboard .= "<b><center>$opp_name</center></b>";
    $scoreboard .= "</td>";
    $scoreboard .= "</tr>";
    $scoreboard .= "</thead>";
    $scoreboard .= "<tr>";
    $scoreboard .= "<td width=50% style='font-size:60px;color:orange;font-color:#dd9c0f;'>";
    $scoreboard .= "<b><center>$team_score</center></b>";
    $scoreboard .= "</td>";
    $scoreboard .= "<td width=50% style='font-size:60px;color:orange;'>";
    $scoreboard .= "<b><center>$opp_score</center></b>";
    $scoreboard .= "</td>";
    $scoreboard .= "</tr>";
    $scoreboard .= "</table>";
?>
<html>
    <head>
        <script>
        </script>
    </head>
    <body>
        <?php echo $scoreboard; ?>
    </body>
</html>