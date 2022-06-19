<?php
/**
 * User Model
 *
 * Handles User Data Source Actions
 *
 * @package    Users.Userlog
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */

class Utilities extends AppModel {
    /**
     * Model name
     *
     * @var string
     */
    public $name     = 'Utilities',
           $useTable = false;
	
const LIVE_CEIL = "def";
const PREMATCH_CEIL = "3cdef";
    
    /**
     * Create a string containing the number of available pages
     * 
     * @param {int}     $page
     * @param {int}     $max
     * @param {array}   $route
     */
    public function get_pages($data, $limit, $max, $route, $num) {
        // parameter format
        $page = $data['page']; 
        $from = $data['from']; 
        $to   = $data['to'];
        $id   = $data['id'];
        
        $max = $max/$limit;
        $max_pages = round($max, 0, PHP_ROUND_HALF_UP);
                
        if($max > $max_pages) {
            $max_pages++;
        }
        if($max_pages <= 1) {
            $pages =  "";
        }
        else {
            // prev btn
            if($page > 1) {
                $pages = '<ul class="pagination"><li class="btn-prev"><a href="' . Router::url(array('controller' => $route['controller'], 'action' => $route['action'], $id, ($page - 1), $from, $to)) . '/' .(!empty($num) ? $num : "") . '">&laquo;</a></li>';
            } else {
                $pages = '<ul class="pagination"><li class="disabled btn-prev"><a href="#">&laquo;</a></li>';
            }

            for($i= ($page - 4); $i <= ($page + 4); $i++) {
                if(!($i <= 0 || $i > $max_pages)) {
                    if($i == $page) {
                        $pages .= '<li class="active"><a href="#">'.$i.'</a></li>';
                    } else {
                        $pages .= '<li><a href="' . Router::url(array('controller' => $route['controller'], 'action' => $route['action'], $id, $i, $from, $to)) . '/' .(!empty($num) ? $num : "") . '">'.$i.'</a></li>'; 
                    }
               } 
            }

            // next btn
            if($page < $max_pages) {
                $pages .= '<li class="btn-next"><a href="' . Router::url(array('controller' => $route['controller'], 'action' => $route['action'], $id, ($page + 1), $from, $to)) . '/' .(!empty($num) ? $num : "") . '">&raquo;</a></li>';
            } else {
                $pages .= '<li class="disabled btn-next"><a href="#">&raquo;</a></li></ul>';
            }
        }
        return array(
            'pages' => $pages,
            'curr'  => $page,
            'max'   => $max_pages,
            'start' => ($page - 1) * $limit + 1,
            'end'   => $page * $limit,
            'limit' => $limit
        );
    }
    
    
    /**
     * Returns time diff
     *
     * @param $time
     * @param bool $suffix
     * @return string
     */
    public function get_remaining_time($time, $suffix = true) {
        $difference = strtotime($time) - strtotime(gmdate("M d Y H:i:s"));
        $sDays = $sHours = $sMins = $sSecs = '';
        $rDays = date('j', $difference) - 1;

        if ($rDays > 0) {
            $sDays = $rDays;

            if($suffix) {
                $sDays .= ' ' . $this->count($rDays, __('day'), __('days')) . ' ';
            }
        }

        $rHours = date('G', $difference);

        if ($rHours > 0) {
            $sHours = $rHours;

            if($suffix) {
                $sHours .= ' ' . $this->count($rHours, __('h'), __('h')) . ' ';
            }
        }

        $rMins = (int) date('i', $difference);

        if ($rMins > 0) {
            $sMins = $rMins;

            if($suffix) {
                $sMins .= ' ' . $this->count($rMins, __('min'), __('min')). ' ';
            }
        }
        
        if ($rDays == 0 && $rHours == 0 && $rMins <= 5) {
            return __("Shortly");
        }
        else {  
           return $sDays . $sHours . $sMins;
        }
    }
        
    function count($number, $singular, $plural) {
        if ($number > 1) {
            return $plural;
        }
        return $singular;
    }
     
    function generate_custom_id($live, $date) {
        if($live) {
            // split year into days (live matches have shorted id range)
            $pos = date('z', strtotime($date)); 
            $val = date('z', strtotime('now')) - 2;
            $filename  = APP . 'tmp' . DS . 'custom_ids' . DS. 'live'; 
        } else {        
            // split year into weeks, each week starts from one tuesday till the next 
            $pos = date('N', strtotime($date)) > 2?date('W', strtotime($date)) + 1:date('W', strtotime('now'));             
            $val = date('W', strtotime('now')) - 2;
            $filename  = APP . 'tmp' . DS . 'custom_ids' . DS. 'prematch';    
        }
            
        // in case of old entry dont create new id
        if($val > $pos) return false;
        
        $content = file_get_contents($filename);

        if(!empty($content)) {
            $data = unserialize($content); 
           
            if(!empty($data[$pos])) {
                // generate next custom id from the last one created for the particular week
                // and replace week data with new values
                $data[$pos]['id'] = $this->generate_event_id($live, $data[$pos]['id']);
                $data[$pos]['order']++;
            } else  {                
                // create array pos from start
                $data[$pos]['id'] = $this->generate_event_id($live);
                $data[$pos]['order'] = 1;
            }
            
            // remove outdated entries 
            /*$data = array_filter($data, function($i) use($val) {
                return $i > $val?true:false;
            }, ARRAY_FILTER_USE_KEY);   */
        // no weeks have been entered
        } else {
            // create array data from start
            $data[$pos]['id'] = $this->generate_event_id($live);
            $data[$pos]['order'] = 1;
        }
              
        file_put_contents($filename, serialize($data)); 
        
        return $data[$pos];
    }
    
    function generate_event_id($live, $prev_id = null) {  
        if($live && ($prev_id === null || $prev_id == self::LIVE_CEIL)) $hex = dechex(1);
        else if(!$live && ($prev_id === null || $prev_id == self::PREMATCH_CEIL)) $hex = dechex(intval(hexdec(LIVE_CEIL)) + 1);
        else $hex = dechex(intval(hexdec($prev_id)) + 1);    

        $letters = str_split($hex);

        for($i = 1; $i < count($letters); $i++) {
            if($letters[$i] <= $letters[$i - 1]) return $this->generate_event_id($live, $hex);
        }

        if(in_array('0', $letters)) return $this->generate_event_id($live, $hex);
 
        if(count($letters) > 1) {
            $c = array_count_values($letters);

            foreach($c as $counter) {
                if($counter > 1) return $this->generate_event_id($live, $hex);
            }
        }

        return $hex;
    }    
}