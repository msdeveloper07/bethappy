<?php

class BetApiComponent extends Component {

    public $name = 'BetApi';
    public $components = array('Session');

    function getStatus($status) {
        $str = '';
        switch (intval($status)) {
            case 1:
                $str = __('Win', true);
                break;
            case 0:
                $str = __('Pending', true);
                break;
            case -1:
                $str = __('Lost', true);
                break;
            case -2:
                $str = __('Canceled', true);
                break;
            case -3:
                $str = __('Rejected', true);
                break;
            case -4:
                $str = __('Pending Validation', true);
                break;
        }
        return $str;
    }

    /**
     * FIXME: looks dead
     * @param unknown_type $type
     * @return number
     */
    function typeToInt($type) {
        switch ($type) {
            case 'Single':
                return 1;
                break;
            case 'Multiple':
                return 2;
                break;
            default:
                return 0;
                break;
        }
    }

    //TODO implement
    function typeToString($type) {
        switch (intval($type)) {
            case 1:
                return 'Single';
                break;
            case 2:
                return 'Multiple';
                break;
            default:
                return 'none';
                break;
        }
    }

    //date time function

    function getSqlDate() {
        return gmdate('Y-m-d H:i:s');
    }
    
    function getGMTTime() {
        return strtotime(gmdate('Y-m-d H:i:s'));
    }
    
    function getLocalTime() {
        $time = $this->GMTToLocal($this->getGMTTime());        
        return $time;
    }

    function localToGMT($localTime) {
        $timeZone = $this->getTimeZone();        
        return $this->convert($localTime, -$timeZone);        
    }

    function GMTToLocal($localTime) {
        $timeZone = $this->getTimeZone();        
        return $this->convert($localTime, $timeZone);        
    }

    function getTimeZone() {
        if ($this->Session->check('Auth.User.time_zone')) {
            $timeZone = $this->Session->read('Auth.User.time_zone');
        } else if ($this->Session->check('time_zone')) {
            $timeZone = $this->Session->read('time_zone');
        } else {
            $timeZone = 0;
        }
        return $timeZone;
    }
    
    function convert($time, $offset) {
        return date('Y-m-d H:i', $time + $offset * 60 * 60);
    }

}