<?php
class analytics {
    public static $name = "analytics";
    public static $version = "1.0";
    public static $author = "Darrell Oller";
    public static $version_date = "10/26/2017";

//    public $analytics_date;

    private $empty_stat_array;
    private $empty_scoring_array;
    private $empty_quarter_array;
    private $shotchart_basket_x_y;
//    private $error;
//    private $stmt; 
    private $db;

    public function __construct(){
        $this->db = new db();
        $this->empty_stat_array = array('FGmade' => 0,'FGmissed' => 0, '3made' => 0,
            '3missed' => 0, 'FTmade' => 0, 'FTmissed' => 0, 'TO' => 0,
            'OReb' => 0, 'ORebFG' => 0, 'OReb3' => 0, 'ORebFT' => 0,
            'DReb' => 0, 'DRebFG' => 0, 'DReb3' => 0, 'DRebFT' => 0,
            'Steal' => 0, 'Assist' => 0, 'Block' => 0, 'Foul' => 0, 'TotPts' => 0
        );

       $this->empty_scoring_array = array('person_id' =>'', 'uni' => 0, 'person_name' => '', 
            'q0' => array(
                '2att' => 0, '2made' => 0, '2missed' => 0, '2%' => 0, '2line' => '', 
                '3att' => 0, '3made' => 0, '3missed' => 0, '3%' => 0, '3line' => '',
                'FTatt' => 0, 'FTmade' => 0, 'FTmissed' => 0, 'FT%' => 0, 'FTline' => '', 
                'FGatt' => 0, 'FGmade' => 0, 'FGmissed' => 0, 'FG%' => 0, 'FGline' => '', 
                'TotPts' => 0,  
                'OReb' => 0, 'OReb2' => 0, 'OReb3' => 0, 'ORebFT' => 0,
                'DReb' => 0, 'DReb2' => 0, 'DReb3' => 0, 'DRebFT' => 0,
                'TotReb' => 0, 
                'Assist' =>0, 'Block' => 0, 'Foul' => 0, 'TO' => 0, 'Steal' => 0
            ),
            'q1' => array(
                '2att' => 0, '2made' => 0, '2missed' => 0, '2%' => 0, '2line' => '', 
                '3att' => 0, '3made' => 0, '3missed' => 0, '3%' => 0, '3line' => '',
                'FTatt' => 0, 'FTmade' => 0, 'FTmissed' => 0, 'FT%' => 0, 'FTline' => '', 
                'FGatt' => 0, 'FGmade' => 0, 'FGmissed' => 0, 'FG%' => 0, 'FGline' => '', 
                'TotPts' => 0,  
                'OReb' => 0, 'OReb2' => 0, 'OReb3' => 0, 'ORebFT' => 0,
                'DReb' => 0, 'DReb2' => 0, 'DReb3' => 0, 'DRebFT' => 0,
                'TotReb' => 0, 
                'Assist' =>0, 'Block' => 0, 'Foul' => 0, 'TO' => 0, 'Steal' => 0
            ),
            'q2' => array(
                '2att' => 0, '2made' => 0, '2missed' => 0, '2%' => 0, '2line' => '',
                '3att' => 0, '3made' => 0, '3missed' => 0, '3%' => 0, '3line' => '',
                'FTatt' => 0, 'FTmade' => 0, 'FTmissed' => 0, 'FT%' => 0, 'FTline' => '', 
                'FGatt' => 0, 'FGmade' => 0, 'FGmissed' => 0, 'FG%' => 0, 'FGline' => '', 
                'TotPts' => 0,  
                'OReb' => 0, 'OReb2' => 0, 'OReb3' => 0, 'ORebFT' => 0,
                'DReb' => 0, 'DReb2' => 0, 'DReb3' => 0, 'DRebFT' => 0,
                'TotReb' => 0, 
                'Assist' =>0, 'Block' => 0, 'Foul' => 0, 'TO' => 0, 'Steal' => 0
            ),
            'q3' => array(
                '2att' => 0, '2made' => 0, '2missed' => 0, '2%' => 0, '2line' => '',
                '3att' => 0, '3made' => 0, '3missed' => 0, '3%' => 0, '3line' => '',
                'FTatt' => 0, 'FTmade' => 0, 'FTmissed' => 0, 'FT%' => 0, 'FTline' => '', 
                'FGatt' => 0, 'FGmade' => 0, 'FGmissed' => 0, 'FG%' => 0, 'FGline' => '', 
                'TotPts' => 0,  
                'OReb' => 0, 'OReb2' => 0, 'OReb3' => 0, 'ORebFT' => 0,
                'DReb' => 0, 'DReb2' => 0, 'DReb3' => 0, 'DRebFT' => 0,
                'TotReb' => 0, 
                'Assist' =>0, 'Block' => 0, 'Foul' => 0, 'TO' => 0, 'Steal' => 0
            ),
            'q4' => array(
                '2att' => 0, '2made' => 0, '2missed' => 0, '2%' => 0, '2line' => '',
                '3att' => 0, '3made' => 0, '3missed' => 0, '3%' => 0, '3line' => '',
                'FTatt' => 0, 'FTmade' => 0, 'FTmissed' => 0, 'FT%' => 0, 'FTline' => '', 
                'FGatt' => 0, 'FGmade' => 0, 'FGmissed' => 0, 'FG%' => 0, 'FGline' => '', 
                'TotPts' => 0,  
                'OReb' => 0, 'OReb2' => 0, 'OReb3' => 0, 'ORebFT' => 0,
                'DReb' => 0, 'DReb2' => 0, 'DReb3' => 0, 'DRebFT' => 0,
                'TotReb' => 0, 
                'Assist' =>0, 'Block' => 0, 'Foul' => 0, 'TO' => 0, 'Steal' => 0
            ),
            'q5' => array(
                '2att' => 0, '2made' => 0, '2missed' => 0, '2%' => 0, '2line' => '',
                '3att' => 0, '3made' => 0, '3missed' => 0, '3%' => 0, '3line' => '',
                'FTatt' => 0, 'FTmade' => 0, 'FTmissed' => 0, 'FT%' => 0, 'FTline' => '', 
                'FGatt' => 0, 'FGmade' => 0, 'FGmissed' => 0, 'FG%' => 0, 'FGline' => '', 
                'TotPts' => 0,  
                'OReb' => 0, 'OReb2' => 0, 'OReb3' => 0, 'ORebFT' => 0,
                'DReb' => 0, 'DReb2' => 0, 'DReb3' => 0, 'DRebFT' => 0,
                'TotReb' => 0, 
                'Assist' =>0, 'Block' => 0, 'Foul' => 0, 'TO' => 0, 'Steal' => 0
            ),
            'q6' => array(
                '2att' => 0, '2made' => 0, '2missed' => 0, '2%' => 0, '2line' => '',
                '3att' => 0, '3made' => 0, '3missed' => 0, '3%' => 0, '3line' => '',
                'FTatt' => 0, 'FTmade' => 0, 'FTmissed' => 0, 'FT%' => 0, 'FTline' => '', 
                'FGatt' => 0, 'FGmade' => 0, 'FGmissed' => 0, 'FG%' => 0, 'FGline' => '', 
                'TotPts' => 0,  
                'OReb' => 0, 'OReb2' => 0, 'OReb3' => 0, 'ORebFT' => 0,
                'DReb' => 0, 'DReb2' => 0, 'DReb3' => 0, 'DRebFT' => 0,
                'TotReb' => 0, 
                'Assist' =>0, 'Block' => 0, 'Foul' => 0, 'TO' => 0, 'Steal' => 0
            ),
            'q7' => array(
                '2att' => 0, '2made' => 0, '2missed' => 0, '2%' => 0, '2line' => '',
                '3att' => 0, '3made' => 0, '3missed' => 0, '3%' => 0, '3line' => '',
                'FTatt' => 0, 'FTmade' => 0, 'FTmissed' => 0, 'FT%' => 0, 'FTline' => '', 
                'FGatt' => 0, 'FGmade' => 0, 'FGmissed' => 0, 'FG%' => 0, 'FGline' => '', 
                'TotPts' => 0,  
                'OReb' => 0, 'OReb2' => 0, 'OReb3' => 0, 'ORebFT' => 0,
                'DReb' => 0, 'DReb2' => 0, 'DReb3' => 0, 'DRebFT' => 0,
                'TotReb' => 0, 
                'Assist' =>0, 'Block' => 0, 'Foul' => 0, 'TO' => 0, 'Steal' => 0
            )
        );

        //set up the array for quarter scoring
        $this->empty_quarter_array = array('2made' => 0, '2missed' => 0,'3made' => 0,'3missed' => 0,'FTmade' => 0,'FTmissed' => 0,'TO' => 0,'OReb' => 0,'DReb' => 0,'Steal' => 0,'Assist' => 0,'Block' => 0,'Foul' => 0,'TotPts' => 0);
        $this->shotchart_basket_x_y = array(
            'full_game' => array('x' => 0, 'y' => 0),
            'q1' => array('x' => 0, 'y' => 0),
            'q2' => array('x' => 0, 'y' => 0),
            'q3' => array('x' => 0, 'y' => 0),
            'q4' => array('x' => 0, 'y' => 0),
            'q5' => array('x' => 0, 'y' => 0),
            'q6' => array('x' => 0, 'y' => 0),
            'q7' => array('x' => 0, 'y' => 0)
        );

    }
    private function finish_shooting_stats($ar) {
          $ar['box_score_row'] = $this->getBoxRow();
          $ar['box_score_row'] = str_replace("^uni^", $ar['uni'], $ar['box_score_row']);
          $ar['box_score_row'] = str_replace("^pname^", $ar['person_name'], $ar['box_score_row']);
          $ar['box_score_row'] = str_replace("^madeFG^", $ar['FGmade'], $ar['box_score_row']);
          $ar['box_score_row'] = str_replace("^attFG^", $ar['FGatt'], $ar['box_score_row']);
          $ar['box_score_row'] = str_replace("^pctFG^", $ar['FG%'], $ar['box_score_row']);
          $ar['box_score_row'] = str_replace("^made3^", $ar['3made'], $ar['box_score_row']);
          $ar['box_score_row'] = str_replace("^att3^", $ar['3att'], $ar['box_score_row']);
          $ar['box_score_row'] = str_replace("^pct3^", $ar['3%'], $ar['box_score_row']);
          $ar['box_score_row'] = str_replace("^FTmade^", $ar['FTmade'], $ar['box_score_row']);
          $ar['box_score_row'] = str_replace("^FTatt^", $ar['FTatt'], $ar['box_score_row']);
          $ar['box_score_row'] = str_replace("^FTpct^", $ar['FT%'], $ar['box_score_row']);
          $ar['box_score_row'] = str_replace("^madeFG^", $ar['FGmade'], $ar['box_score_row']);
          $ar['box_score_row'] = str_replace("^attFG^", $ar['FGatt'], $ar['box_score_row']);
          $ar['box_score_row'] = str_replace("^pctFG^", $ar['FG%'], $ar['box_score_row']);
          $ar['OReb'] = $ar['OReb2'] + $ar['OReb3'] + $ar['ORebFT'];
          $ar['box_score_row'] = str_replace("^OReb^", $ar['OReb'], $ar['box_score_row']);
          $ar['DReb'] = $ar['DReb2'] + $ar['DReb3'] + $ar['DRebFT'];
          $ar['box_score_row'] = str_replace("^DReb^", $ar['DReb'], $ar['box_score_row']);
          $ar['TotReb'] = $ar['OReb'] + $ar['DReb'];
          $ar['box_score_row'] = str_replace("^TotReb^", $ar['TotReb'], $ar['box_score_row']);
          $ar['box_score_row'] = str_replace("^Assists^", $ar['Assist'], $ar['box_score_row']);
          $ar['box_score_row'] = str_replace("^Block^", $ar['Block'], $ar['box_score_row']);
          $ar['box_score_row'] = str_replace("^Fouls^", $ar['Foul'], $ar['box_score_row']);
          $ar['box_score_row'] = str_replace("^TO^", $ar['TO'], $ar['box_score_row']);
          $ar['box_score_row'] = str_replace("^Steal^", $ar['Steal'], $ar['box_score_row']);
          $ar['box_score_row'] = str_replace("^TotPoints^", $ar['TotPts'], $ar['box_score_row']);

        return $ar;
    }

    public function getDescription() {
        $myDesc="This is the analytics class.<BR>";
        $myDesc .=  "Name:".self::$name."  Version:".self::$version."<br>";
        $myDesc .=  "Author:".self::$author."  Version:".self::$version_date."<br>";
        $myDesc.="This class creates arrays for reporting and charting.<BR>";
        $myDesc.=".<BR>";
        return($myDesc);
    }
    public function getScoringStats ($event_id) {
        // Will include opponent stats as element[opp_id], Team Stats as element[$team_id] and individual
        // player stats as element[$person_id]
        // array details will be included in element['detail'] with entries for ['tot_elements'],[team_id],
        // [team_name]['team_points'],[opp_id],[opp_name]['opp_points']
        $players = $this->getPlayerInfo($event_id);
        $scoring = array();

        //get event info 
        $event_info = $this->getEventTeamInfo($event_id);

        //add a record for the opponent, we want it to be the first element
        $opp_id = $event_info[0]['opp_id'];
        $opp_name = $event_info[0]['opp_name'];
        $team_id = $event_info[0]['team_id'];
        $team_name = $event_info[0]['team_name'];
        $opp_pid = $event_info[0]['opp_id'] + 10000;
        $scoring[$opp_pid] = $this->empty_scoring_array;
        $scoring[$opp_pid]['uni'] = 'Team';
        $scoring[$opp_pid]['person_id'] = $opp_pid;
        $scoring[$opp_pid]['person_name'] = $opp_name;

        foreach ($players as $player) {
            $scoring[$player['person_id']] = $this->empty_scoring_array;
            $scoring[$player['person_id']]['person_id'] = $player['person_id'];
            $scoring[$player['person_id']]['person_name'] = $player['name'];
            $scoring[$player['person_id']]['uni'] = $player['uni'];
        }

       //add a record for the McGivney team, we want it to be the last element
        $scoring[99999] = $this->empty_scoring_array;
        $scoring[99999]['uni'] = 'Team';
        $scoring[99999]['person_id'] = 99999;
        $scoring[99999]['person_name'] = $team_name;

        //ok, the players are set up to receive the scoring stats now.
        $ar = $this->getRawStatArray($event_id);
        foreach ($ar as $ele) {
            $pid = $ele['person_id'];
            $act = $ele['act'];
            $q = 'q'.$ele['quarter'];
            $scoring[$pid]['q0'][$act] = $scoring[$pid]['q0'][$act] + 1; //q0 is the whole game
            $scoring[$pid][$q][$act] = $scoring[$pid][$q][$act] + 1;
            // add the stat to the team stats if not the visitor player
            if ($pid != $opp_pid ) { //It is not an opposition player
                $scoring[99999]['q0'][$act] = $scoring[99999]['q0'][$act] + 1;
                $scoring[99999][$q][$act] = $scoring[99999][$q][$act] + 1;        
            } else {
            }
        }
        //acts have now been entered into the array,
        //figure out the stats
        foreach ($scoring as $line) {
            $pid = $line['person_id'];
            for ($i = 0; $i < 8; $i++) {
                $q = "q".$i;
                $scoring[$pid][$q]['FGatt'] = $line[$q]['2made'] + $line[$q]['2missed'] + $line[$q]['3made'] + $line[$q]['3missed'];
                $scoring[$pid][$q]['FGmade'] = $line[$q]['2made'] + $line[$q]['3made'];
                $scoring[$pid][$q]['FGmissed'] = $scoring[$pid][$q]['2missed'] + $scoring[$pid][$q]['3missed'];
                if($scoring[$pid][$q]['FGatt'] == 0 ) {
                    $scoring[$pid][$q]['FG%'] = 0;
                } else {
                    $scoring[$pid][$q]['FG%'] = round($scoring[$pid][$q]['FGmade'] / $scoring[$pid][$q]['FGatt'],3) * 100;
                }
                $scoring[$pid][$q]['FGline'] = $scoring[$pid][$q]['FGmade']."/".$scoring[$pid][$q]['FGatt']." ".($scoring[$pid][$q]['FGatt'] ? $scoring[$pid][$q]['FG%']."%" : '');
                //-------
                $scoring[$pid][$q]['2att'] = $line[$q]['2made'] + $line[$q]['2missed'];
                if($scoring[$pid][$q]['2att'] != 0 ) {
                    $scoring[$pid][$q]['2%'] = round($line[$q]['2made'] / $scoring[$pid][$q]['2att'],3) * 100;
                } else {
                    $scoring[$pid][$q]['2%'] = 0;
                }
                $scoring[$pid][$q]['2line'] = $scoring[$pid][$q]['2made']."/".$scoring[$pid][$q]['2att']." ".($scoring[$pid][$q]['2att'] ? $scoring[$pid][$q]['2%']."%" : '');
                //-------
                $scoring[$pid][$q]['3att'] = $line[$q]['3made'] + $line[$q]['3missed'];
                if($scoring[$pid][$q]['3att'] != 0 ) {
                    $scoring[$pid][$q]['3%'] = round($line[$q]['3made'] / $scoring[$pid][$q]['3att'],3) * 100;
                } else {
                    $scoring[$pid][$q]['3%'] = 0;
                }
                $scoring[$pid][$q]['3line'] = $scoring[$pid][$q]['3made']."/".$scoring[$pid][$q]['3att']." ".($scoring[$pid][$q]['3att'] ? $scoring[$pid][$q]['3%']."%" : '');
                //-------
                $scoring[$pid][$q]['FTatt'] = $line[$q]['FTmade'] + $line[$q]['FTmissed'];
                if($scoring[$pid][$q]['FTatt'] != 0 ) {
                    $scoring[$pid][$q]['FT%'] = round($line[$q]['FTmade'] / $scoring[$pid][$q]['FTatt'],3) * 100;
                } else {
                    $scoring[$pid][$q]['FT%'] = 0;
                }
                $scoring[$pid][$q]['FTline'] = $scoring[$pid][$q]['FTmade']."/".$scoring[$pid][$q]['FTatt']." ".($scoring[$pid][$q]['FTatt'] ? $scoring[$pid][$q]['FT%']."%" : '');
 
                $scoring[$pid][$q]['TotPts'] = $scoring[$pid][$q]['FTmade'] + ($scoring[$pid][$q]['2made'] * 2) + ($scoring[$pid][$q]['3made'] * 3);

                $scoring[$pid][$q]['OReb'] = $line[$q]['OReb2'] + $line[$q]['OReb3'] + $line[$q]['ORebFT'];
                $scoring[$pid][$q]['DReb'] = $line[$q]['DReb2'] + $line[$q]['DReb3'] + $line[$q]['DRebFT'];
                $scoring[$pid][$q]['TotReb'] = $scoring[$pid][$q]['OReb'] + $scoring[$pid][$q]['DReb'];
            }
        }
        return $scoring;
    }

    public function getPlayerInfo ($event_id) {
        // unable to get this down to unique players with sql so I will do it manually in php
        $sql = "SELECT player_id as person_id, team_id, uni, last_first_init FROM event_players WHERE event_id=$event_id ORDER BY last_first_init;";
        $this->db->query($sql);
        $results = $this->db->resultset();
        $return_array = array();

        //Get rid of the duplicate player records
        foreach ($results as $result) {
            if (!isset($return_array[$result['person_id']])) {
                $return_array[$result['person_id']] = array(
                    'person_id' => $result['person_id'],
                    'team_id' => $result['team_id'],
                    'uni' => $result['uni'],
                    'name' => $result['last_first_init'],
                    'has_shooting_stats' => 0
                );
            }
        }
        return $return_array;
    }
    public function getRawStatArray ($event_id) {
        $sql = "SELECT * FROM Floor_Stats WHERE event_id=$event_id ORDER BY act_time;";
        $clean_sql = $this->db->clean_string($sql);
        $this->db->query($sql);
        $result = $this->db->resultset($clean_sql);
        return $result;
    }

    public function getEventDetails ($event_id) {
        $sql = "SELECT * FROM events WHERE id=$event_id ORDER BY id;";
        $clean_sql = $this->db->clean_string($sql);
        $this->db->query($sql);
        $result = $this->db->resultset($clean_sql);
        return $result;
        }
    public function getEventTeamInfo ($event_id) {
        $sql = "SELECT events.id, ";
        $sql .= "events.event_date_time, ";
        $sql .= "events.home_or_road as venue, ";
        $sql .= "teams.short_name as team_name, ";
        $sql .= "teams.id as team_id, ";
        $sql .= "opponents.short_name as opp_name, ";
        $sql .= "opponents.id as opp_id ";
        $sql .= "from events ";
        $sql .= "INNER JOIN teams on teams.id = events.team_id ";
        $sql .= "INNER JOIN opponents on opponents.id = events.opponent_id ";
        $sql .= "WHERE events.id = $event_id";
        $clean_sql = $this->db->clean_string($sql);
        $this->db->query($sql);
        $result = $this->db->resultset($clean_sql);
        $result[0]['team_pid'] = 99999;
        $result[0]['opp_pid'] = $result[0]['opp_id'] + 10000;       
        return $result;
    }

    public function getOpponentDetails ($opp_id) {
        $sql = "SELECT * FROM opponents WHERE id = $opp_id ORDER BY id;";
        $clean_sql = $this->db->clean_string($sql);
        $this->db->query($sql);
        $result = $this->db->resultset($clean_sql);
        return $result;
        }

    public function getTeamDetails ($team_id) {
        $sql = "SELECT * FROM teams WHERE id = $team_id ORDER BY id;";
        $clean_sql = $this->db->clean_string($sql);
        $this->db->query($sql);
        $result = $this->db->resultset($clean_sql);
        return $result;
        }

    public function getEventsForTeam ($team_id = 0, $result_as = "array") {


    }

    public function getBoxScoreHeader($team_name) {
        $box_score_header = "<table class='stat_table' style='font-size:16px; text-align:center;'>\n";
        $box_score_header .= "<caption style='font-size:22px;font-weight:bold;'>~team~</caption>\n";
        $box_score_header .= "<tr style='vertical-align:bottom;'>\n";
        $box_score_header .= "<th width='15px' rowspan='2'><center>#</th>\n";
        $box_score_header .= "<th width='150px' rowspan='2'>Player</th>\n";
        $box_score_header .= "<th width='30px' rowspan='2'><abbr title='Minutes played'><center>Min</abbr></th>\n";
        $box_score_header .= "<th colspan='2'><abbr title='2-point field goals'><center>2FG</abbr></th>\n";
        $box_score_header .= "<th colspan='2'><abbr title='3-point field goals'><center>3FG</abbr></th>\n";
        $box_score_header .= "<th colspan='2'><abbr title='Free throws'><center>FT</abbr></th>\n";
        $box_score_header .= "<th colspan='3'><abbr title='Rebounds'><center>REB</abbr></th>\n";
        $box_score_header .= "<th width='30px' rowspan='2'><abbr title='Assists'><center><center>AST</abbr></th>\n";
        $box_score_header .= "<th width='30px' rowspan='2'><abbr title='Personal fouls'><center>PF</abbr></th>\n";
        $box_score_header .= "<th width='30px' rowspan='2'><abbr title='Turnovers'><center>TO</abbr></th>\n";
        $box_score_header .= "<th width='30px' rowspan='2'><abbr title='Steals'><center><center>STL</abbr></th>\n";
        $box_score_header .= "<th width='30px' rowspan='2'><abbr title='Blocked shots'><center>BS</abbr></th>\n";
        $box_score_header .= "<th width='30px' rowspan='2'><abbr title='Points'><center>Pts</abbr></th>\n";
        $box_score_header .= "</tr>\n";
        $box_score_header .= "<tr>\n";
        $box_score_header .= "<th width='35px'><abbr title='Made/Attempts'><center>M/A</abbr></th>\n";
        $box_score_header .= "<th width='35px'><abbr title='2-point\nfield goal %'><center>%</abbr></th>\n";
        $box_score_header .= "<th width='35px'><abbr title='Made/Attempts'><center>M/A</abbr></th>\n";
        $box_score_header .= "<th width='35px'><abbr title='3-point\nfield goal %'><center>%</abbr></th>\n";
        $box_score_header .= "<th width='35px'><abbr title='Made/Attempts'><center>M/A</abbr></th>\n";
        $box_score_header .= "<th width='35px'><abbr title='Free Throw %'><center>%</abbr></th>\n";
        $box_score_header .= "<th width='30px'><abbr title='Offensive rebounds'><center>OFF</abbr></th>\n";
        $box_score_header .= "<th width='30px'><abbr title='Defensive rebounds'><center>DEF</abbr></th>\n";
        $box_score_header .= "<th width='30px'><abbr title='Total rebounds'><center>TOT</abbr></th>\n";
        $box_score_header .= "</tr>\n";
        $box_score_header .= "</table>\n";
        $box_score_header = str_replace("~team~", $team_name, $box_score_header);
        return $box_score_header;
    }
    public function getBoxRow() {

        $box_score_row = "<tr style='vertical-align:bottom;'>\n";
        $box_score_row .= "<td width='15px'><center>^uni^</td>\n";
        $box_score_row .= "<td width='150px'>^pname^</td>\n";
        $box_score_row .= "<td width='30px'><center>^min^</td>\n";
        $box_score_row .= "<td width='35px'><center>^madeFG^&nbsp;/&nbsp;^attFG^</td>\n";
        $box_score_row .= "<td width='35px'><center>^pctFG^%</td>\n";
        $box_score_row .= "<td width='35px'><center>^made3^&nbsp;/&nbsp;^att3^</td>\n";
        $box_score_row .= "<td width='35px'><center>^pct3^%</td>\n";
        $box_score_row .= "<td width='35px'><center>^FTmade^&nbsp;/&nbsp;^FTatt^</td>\n";
        $box_score_row .= "<td width='35px'><center>^FTpct^%</td>\n";
        $box_score_row .= "<td width='30px'><center>^OReb^</td>\n";
        $box_score_row .= "<td width='30px'><center>^DReb^</td>\n";
        $box_score_row .= "<td width='30px'><center>^TotReb^</td>\n";
        $box_score_row .= "<td width='30px'><center>^Assists^</td>\n";
        $box_score_row .= "<td width='30px'><center>^Fouls^</td>\n";
        $box_score_row .= "<td width='30px'><center>^TO^</td>\n";
        $box_score_row .= "<td width='30px'><center>^Steal^</td>\n";
        $box_score_row .= "<td width='30px'><center>^Block^</td>\n";
        $box_score_row .= "<td width='30px'><center>^TotPoints^</td>\n";
        $box_score_row .= "</tr>\n";
        return $box_score_row;
    }    
    public function showFormattedArray ($name, $ar, $exit = 0) {
        echo "<BR>========== $name =============<br><pre>\n";
        print_r($ar);
        echo "<BR>========== End of $name display =============<br><pre>\n";
        if ($exit == 1) {
            exit;
        }
    }


} //End of analytics class definition