<?php
class clock {
    public static $name = "clock";
    public static $version = "1.0";
    public static $author = "Darrell Oller";
    public static $version_date = "11/13/2017";

//    public $analytics_date;

    private $empty_minutes_array;
    private $empty_game_array;
    private $empty_quarter_array;
    private $empty_segment_array;
    private $segment_array;
    private $clock_array;

    private $db;

    public function __construct() {
        $this->db = new db();
        $this->empty_minutes_array = array(
            'person_id' => 0,
            'name' => '',
            'tot_mins' => 0,
            'tot_segments' => 0,
            'avg_min_per_segment' => 0,
            'games' =>  array());
        $this->empty_game_array = array(
            'event_id' => 0,
            'tot_mins' => 0,
            'tot_segments' => 0,
            'avg_min_per_segment' => 0,
            'quarters' => array());
        $this->empty_quarter_array = array('quarter' => 0, 
            'tot_mins' => 0, 
            'tot_segments' => 0,
            'segments' => array()); 
        $this->empty_segment_array = array(
            'in' => '',
            'out' => '',
            'seconds' => 0,
            'start' => 0);
        $this->segment_array = array(
            'id' => array(
                'start_id' => 0,
                'start_vt' => '',
                'end_id' => 0, 
                'end_vt' => '',
                'seconds' => 0)
        );
        $this->clock_array = array(
            'game' => array('start_id' => 0, 'start_vt' => '', 'end_id' => 0, 'end_vt' => ''),
            'Q1' => array('start_id' => 0, 'start_vt' => '', 'end_id' => 0, 'end_vt' => ''),
            'Q2' => array('start_id' => 0, 'start_vt' => '', 'end_id' => 0, 'end_vt' => ''),
            'Q3' => array('start_id' => 0, 'start_vt' => '', 'end_id' => 0, 'end_vt' => ''),
            'Q4' => array('start_id' => 0, 'start_vt' => '', 'end_id' => 0, 'end_vt' => ''),
            'Q5' => array('start_id' => 0, 'start_vt' => '', 'end_id' => 0, 'end_vt' => ''),
            'Q6' => array('start_id' => 0, 'start_vt' => '', 'end_id' => 0, 'end_vt' => ''),
            'Q7' => array('start_id' => 0, 'start_vt' => '', 'end_id' => 0, 'end_vt' => ''),
        );
    } //end of construct method
    public function getMinutesInfo($players, $min_recs) {

        //set up an array ele for each player
        $results = array();
        foreach ($players as $player) {
$pid = $player['person_id'];
echo "pid instead of person_id:$pid<br>";
            $results[$pid] = $this->empty_minutes_array;
            $results[$player['person_id']]['person_id'] = $player['person_id'];
            $results[$player['person_id']]['name'] = $player['name'];
        }
        // first, we populate what we can in the games part of the array
        $event_id_str = "";
        foreach($minutes as $minute) {
            // collect up a list of all of the event_ids
            if(strpos($events,$minutes['event_id']) === false) {
                $event_id_str .= $minutes['event_id']."~";
            }
        }
        $events = explode('~', $event_id_str);
        foreach ($events as $event) {
            foreach ($players as $player) {
                $results[$player['person_id']]['games'][$event] = $this->empty_game_array;
                $results[$player['person_id']]['games'][$event]['event_id'] = $event;
                $results[$player['person_id']]['games']['event_id'][$event]['quarters'][1] = $this->empty_quarter_array;
                $results[$player['person_id']]['games']['event_id'][$event]['quarters'][2] = $this->empty_quarter_array;
                $results[$player['person_id']]['games']['event_id'][$event]['quarters'][3] = $this->empty_quarter_array;
                $results[$player['person_id']]['games']['event_id'][$event]['quarters'][4] = $this->empty_quarter_array;
                $results[$player['person_id']]['games']['event_id'][$event]['quarters'][5] = $this->empty_quarter_array;
                $results[$player['person_id']]['games']['event_id'][$event]['quarters'][6] = $this->empty_quarter_array;
                $results[$player['person_id']]['games']['event_id'][$event]['quarters'][7] = $this->empty_quarter_array;
            }
$this->showFormattedArray ("results", $results, $exit = 1);
        }

$this->showFormattedArray ("results", $results,1);

        //ok, we have the results array started and data records, lets figure out the minutes
//        $i=0;
//       foreach($minutes as $minute) {
//            if($minute['action'] == 'In') {
//                $results[$minute]['person_id']] = [$i]['qtr'] = $minute['quarter'];
//                $segments[$i]['In'] = $minute['clock_time'];
//                $i++;
//            }
//        }
        $i=0;
        foreach($minutes as $minute) {
            if($minute['action'] == 'Out') {
                $segments[$i]['qtr'] = $minute['quarter'];
                $segments[$i]['Out'] = $minute['clock_time'];
                $i++;
            }
        }
        for($i = 0; $i < count($segments); $i++) {
            $start = explode(':',$segments[$i]['In']);
            $start_secs = ($start[0] * 60) + $start[1];
            $end = explode(':',$segments[$i]['Out']);
            $end_secs = ($end[0] * 60) + $end[1];
            $segments[$i]['seconds'] = $start_secs - $end_secs;
            $str_secs = $segments[$i]['seconds'] % 60;
            $str_secs = ($str_secs <= 9) ? "0$str_secs" : $str_secs;
            $segments[$i]['minutes'] = intval($segments[$i]['seconds'] / 60);
            $segments[$i]['minutes'] = $segments[$i]['minutes'].":".$str_secs;
        }

    }
    public function getMinuteRecords ($event_id, $pid = -1, $getQuarters = 1, $show = 0) {
        //Use pid = -1 to get all players, use pid = player_id to get individual Players
        // use pid = 0 to get just the quarter timing
        // getQuarters=0 means to exclude the game timing
        // getQuarters=1 means include the game timing
        if($show ==1) {
            echo "<br>SHOW is ON:<br>   event_id:$event_id<br>   pid:$pid<br>   getQuarters:$getQuarters<br>==========<br>";
        }
        
        if($pid == -1 AND $getQuarters == 1) {
            $where_string = "";
        } elseif ($pid <> -1 and $getQuarters == 1) {
            echo "Yo<BR>";
            $where_string = " AND (  person_id = 0 OR person_id = $pid )";
        } elseif($pid <> -1 and $getQuarters == 0) {
            $where_string = " AND ( person_id = $pid )";
        } else {
            $where_string = " AND THERE SHOULD NOT BE AN ELSE "; 
        }
        $sql = "SELECT * FROM clock WHERE (event_id=$event_id $where_string) ORDER BY video_time;";
        $clean_sql = $this->db->clean_string($sql);
        $this->db->query($sql, $show);
        $results = $this->db->resultset($clean_sql);
        
        $timing = array('clock' => array(), 'player_io');
        foreach ($results as $result) {
            $event_id = $result['event_id'];
            $event_key="event_$event_id";
            if($result['person_id'] == 0) { //manage clock timing
                if(!isset($timing['clock'][$event_key])) {
                    $timing['clock'][$event_key] = array();  
                }
                $periods = explode('_',$result['action']);
                $period = $periods[0];
                if(strpos($result['action'], 'Start')) {
                    $ar = array('start_id' => 0, 'start_vt' => '', 'end_id' => 0, 'end_vt' => '');
                    $ar['start_id'] = $result['id'];
                    $ar['start_vt'] = $result['video_time'];
                    $timing['clock'][$event_key][$period] = $ar;
                } else {  //This is an ending marker.  The array already is in place
                    $timing['clock'][$event_key][$period]['end_id'] = $result['id'];
                    $timing['clock'][$event_key][$period]['end_vt'] = $result['video_time'];
                }
            } else { //manage player movement
                $id = $result['id'];
                $pid = $result['person_id'];
                $pid_key = "pid_$pid";
                $qtr = $result['quarter'];
                if(!isset($timing['player_io'][$event_key])) {
                    $timing['player_io'][$event_key] = array('Q1' => array(), 'Q2' => array(),
                        'Q3' => array(), 'Q4' => array(), 'Q5' => array(), 'Q6' => array(),
                        'Q7' => array()
                    );
                }
                $Q = "Q$qtr"; //This is the quarter identifier like Q1, Q2 etc for the array
                if(!isset($timing['player_io'][$event_key][$Q][$pid_key])) {
                    $timing['player_io'][$event_key][$Q][$pid_key] = array();
                }                
                if($result['action']== 'In') {
                    $ar = array('start_id' => 0, 'start_vt' => '', 'start_ct' => '', 'end_id' => 0, 'end_vt' => '', 'end_ct' => '', 'seconds' => 0);
                    $ar['start_id'] = $result['id'];
                    $ar['start_vt'] = $result['video_time'];
                    $ar['start_ct'] = $result['clock_time'];
                    //(count())) should be the next segment I am working on
                    $seg_count = count($timing['player_io'][$event_key][$Q][$pid_key]);
//echo "<br>Start: seg_count = $seg_count<br>";
                    $timing['player_io'][$event_key][$Q][$pid_key][$seg_count] = $ar;
//echo "<br> After Segment start<br>";
//$this->showFormattedArray('timing',$timing,0);

                } else {  //This is an ending marker.  The array already is in place
                    //(count - 1) should be the last active segment I am working on
                    $seg_count = count($timing['player_io'][$event_key][$Q][$pid_key]) - 1; 
//echo "<br>End: seg_count = $seg_count<br>";
                    $timing['player_io'][$event_key][$Q][$pid_key][$seg_count]['end_id'] = $result['id'];
                    $timing['player_io'][$event_key][$Q][$pid_key][$seg_count]['end_vt'] = $result['video_time'];
                    $timing['player_io'][$event_key][$Q][$pid_key][$seg_count]['end_ct'] = $result['clock_time'];
                    $timing['player_io'][$event_key][$Q][$pid_key][$seg_count]['seconds'] = $this->getSeconds($timing['player_io'][$event_key][$Q][$pid_key][$seg_count]);
//echo "<br>timing after end -------<br>";
//$this->showFormattedArray('timing',$timing,0);
                }
            }
        }



        return $timing;
    }
    public function getSeconds($ar) {
//echo "<BR>   in getSeconds<br>";
//$this->showFormattedArray('ar',$ar,0);
        $start = $ar['start_ct'];
        $start_secs = ($start[0] * 60) + $start[1];
        $end = $ar['end_ct'];
        $end_secs = ($end[0] * 60) + $end[1];
        $seconds = $start_secs - $end_secs;
        return $seconds;
    }
    public function convertSecondsToMinutes($seconds) {
        $str_secs = $seconds % 60;
        $str_secs = ($str_secs <= 9) ? "0$str_secs" : $str_secs;
        $minutes = intval($seconds / 60);
        return "$minutes:$str_secs";
    }
    public function showFormattedArray ($name, $ar, $exit = 0) {
        echo "<BR>========== $name =============<br><pre>\n";
        print_r($ar);
        echo "<BR>========== End of $name display =============<br><pre>\n";
        if ($exit == 1) {
            exit;
        }
    }

} // end of clock class

