<?php

App::uses('AppHelper', 'View/Helper');

class BethHelper extends AppHelper {

    /**
     * Helper name
     * @var string
     */
    public $name = 'Beth';

    /**
     * Helpers list
     * @var array
     */
    public $helpers = array(0 => 'Ajax',  1 => 'Session', 2 => 'Time');

    /**
     * Themes list
     * @return array
     */
    function getThemesList() {
        return array(
            'Black'     => 'Black',
            'LightBlue' => 'Light blue',
            'Grey'      => 'Grey',
            'DarkRed'   => 'Dark red',
            'Red'       => 'Red',
            'Orange'    => 'Orange',
            'Green'     => 'Green',
            'Brown'     => 'Brown',
            'Blue'      => 'Blue',
            'Pink'      => 'Pink'
        );
    }

    function convertTime($time) {
        if ($this->Session->read('Auth.User.time_zone')) {
            $timeZone = $this->Session->read('Auth.User.time_zone');
        } else if ($this->Session->read('time_zone')) {
            $timeZone = $this->Session->read('time_zone');
        } else {
            $timeZone = 0;
        }
        //$format = Configure::read('Settings.eventDateFormat');
        return $this->Time->format('H:i', $time, null, $this->calculateOffSet($timeZone));
    }
    
    function convertTimeSchedules($time, $timeZone) {
        $format = 'H:i';
        return $this->Time->format($format, $time, null, $this->calculateOffSet($timeZone));
    }
    
    function calculateOffSet($timeZone) {
        //Calculate The Offset to UTC
        $date_London = new DateTime("now", new DateTimeZone('Europe/London'));
        $offset_in_hours=($date_London->getOffset())/3600;

        //Recalculate the User's Time based on Offset
        return ($timeZone+$offset_in_hours);
    }
    
    public function convertDate($time) {
        if ($this->Session->read('Auth.User.time_zone')) {
            $timeZone = $this->Session->read('Auth.User.time_zone');
        } else if ($this->Session->read('time_zone')) {
            $timeZone = $this->Session->read('time_zone');
        } else {
            $timeZone = 0;
        }
        return $this->Time->format('H:i Y\/m\/d', $time, null, $this->calculateOffSet($timeZone));
    }

    function convertDateTime($time) {
        if ($this->Session->read('Auth.User.time_zone')) {
            $timeZone = $this->Session->read('Auth.User.time_zone');            
        } else if ($this->Session->read('time_zone')) {
            $timeZone = $this->Session->read('time_zone');
        } else {
            $timeZone = 0;
        }
        //$format = Configure::read('Settings.eventDateFormat');
        return $this->Time->format('Y\/m\/d H:i', $time, null, $this->calculateOffSet($timeZone));
    }
    
    function convertDateSimple($time) {
        if ($this->Session->read('Auth.User.time_zone')) {
            $timeZone = $this->Session->read('Auth.User.time_zone');
        } else if ($this->Session->read('time_zone')) {
            $timeZone = $this->Session->read('time_zone');
        } else {
            $timeZone = 0;
        }
        //$format = Configure::read('Settings.eventDateFormat');
        return $this->Time->format('Y-m-d', $time, null, $this->calculateOffSet($timeZone));
    }

    /**
     * Returns time diff
     * @param $time
     * @param bool $suffix
     * @return string
     */
    public function getRemainingTime($time, $suffix = true) {
        $difference = strtotime($time) - strtotime(date("Y-m-d H:i:s"));
        
        $sDays = $sHours = $sMins = $sSecs = '';
        $rDays = date('j', $difference) - 1;
        
        if ($rDays > 0) {
            $sDays = $rDays;

            if($suffix) $sDays .= ' ' . $this->count($rDays, __('day'), __('days')) . ' ';
        }

        $rHours = date('G', $difference);

        if ($rHours > 0) {
            $sHours = $rHours;

            if($suffix) $sHours .= ' ' . $this->count($rHours, __('h'), __('h')) . ' ';
        }

        $rMins = (int) date('i', $difference);

        if ($rMins > 0) {
            $sMins = $rMins;

            if($suffix) $sMins .= ' ' . $this->count($rMins, __('min'), __('min')). ' ';
        }
        
        if ($rDays == 0 && $rHours == 0 && $rMins <= 5) {
            return __("Shortly");
        } else {  
           return $sDays . $sHours . $sMins;
        }
    }

    function count($number, $singular, $plural) {
        if ($number > 1) return $plural;
        return $singular;
    }

    function convertCurrency($amount) {
        return sprintf("%01.2f", round($amount, 2));
    }

    /**
     * @param $odd
     * @return float|string
     */
    public function convertOdd($odd) {
        
        if ($odd==null) return "-";
        
        if ($this->Session->read('Auth.User.odds_type')) {
            $type = $this->Session->read('Auth.User.odds_type');
        } else if ($this->Session->read('odds_type')) {
            $type = $this->Session->read('odds_type');
        } else {
            //TODO default odds type
            $type = 'default';
        }

        switch ($type) {
            case 2:
                return $this->convertToFractional($odd);
                break;
            case 3:
                return $this->convertToAmerican($odd);  
                break;
            case 4:
                return $this->convertToHongKong($odd);  
                break;
            case 5:
                return $this->convertToIndonesian($odd);  
                break;
            case 6:
                return sprintf("%01.2f",$this->convertToMalay($odd));  
                break;
            default:
                return sprintf("%01.2f", round($odd, 2));
                break;
        }
    }

    function convertToFractional($odd) {
        //TODO simplify
        $numerator = ($odd - 1) * 100;
        $denominator = 100;
        return $numerator . '/' . $denominator;
    }

    function convertToAmerican($odd) {
        if ($odd >= 2) {
            return '+' . 100 * ($odd - 1);
        } else {
            return round(-100 / ($odd - 1));
        }
    }
    
    function convertToHongKong($odd) {
            return $odd - 1;
    }
    
    function convertToIndonesian($odd) {
        if ($odd >= 2) {
            $oddd =  '+' . 100 * ($odd - 1);
        } else {
            $oddd = round(-100 / ($odd - 1));
        }
        return $oddd/100;
    }
    
    function convertToMalay($odd) {
        //TODO simplify
        $numerator = ($odd - 1) * 100;
        $denominator = 100;
        $malayodd = $denominator/$numerator;
        if ($odd>2){
            return $malayodd*-1;
        }else{
            return $malayodd;
        }
    }

    function getUserStatus($st) {
        $str = '';
        switch ($st) {
            case -4:
                $str = __('Banned');
                break;
            case -3:
                $str = __('Self Deleted');
                break;
            case -2:
                $str = __('Self Excluded');
                break;
            case -1:
                $str = __('Locked Out');
                break;
            case 0:
                $str = __('Unconfirmed');
                break;
            case 1:
                $str = 'Active';
                break;
        }
        return $str;
    }

    function getDepositStatus($status) {
        $str = '';
        switch ($status) {
            case 'completed':
                $str = '<span class="deposit-success">' . __('Successful', true) . '</span>';
                break;
            case 'canceled':
                $str = '<span class="deposit-failed">' . __('Failed', true) . '</span>';
                break;
            case 'pending':
                $str = '<span class="deposit-pending">' . __('Pending', true) . '</span>';
                break;
        }
        return $str;
    }
    
    function getWithdrawStatus($status) {
        $str = '';
        switch (intval($status)) {
            case 'completed':
                $str = '<span class="deposit-success">' . __('Completed', true) . '</span>';
                break;
            case 'pending':
                $str = '<span class="deposit-failed">' . __('Pending', true) . '</span>';
                break;
        }
        return $str;
    }

    public function getSlotGames($gameId) {
        switch ($gameId) {
            case 8:
                $name = __("Dragon King");
                break;
            case 9:
                $name = __("Wild Dolphin");
                break;
            case 12:
                $name = __("Venetia");
                break;
            case 13:
                $name = __("Lady Luck");
                break;
            case 14:
                $name = __("Wolf Quest");
                break;
            case 18:
                $name = __("Explosive Reels");
                break;
            case 19:
                $name = __("Gold Of Ra");
                break;
            case 20:
                $name = __("Dancing Lion");
                break;
            case 21:
                $name = __("Phoenix Princess");
                break;
            case 22:
                $name = __("Fortune Panda");
                break;
            case 25:
                $name = __("Magic Unicorn");
                break;
            case 26:
                $name = __("Ancient Gong");
                break;
            case 27:
                $name = __("Power Dragon");
                break;
            case 71:
                $name = __("JumpinPot");
                break;
            case 73:
                $name = __("African Sunset");
                break;
            case 77:
                $name = __("Kitty Twins");
                break;
            case 81:
                $name = __("Tesla");
                break;
            case 82:
                $name = __("DaVinci Codex");
                break;
        }
        return $name;
    }
    
    public function humanizeActive($val) {
        switch ($val) {
            case 1:
                $active = __('Yes');
                break;
            default:
                $active = __('No');
        }
        return $active;
    }
}

?>
