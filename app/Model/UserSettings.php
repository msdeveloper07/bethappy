<?php
/**
 * Setting Model
 *
 * Handles Setting Data Source Actions
 *
 * @package    Settings.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */

class UserSettings extends AppModel {
    
    /**
     * Model name
     * @var $name string
     */
    public $name = 'UserSettings';
    public $useTable = 'user_settings'; // This model does not use a database table

    /**
     * Model schema
     * @var $_schema array
     */
    protected $_schema = array(
        'id'        => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'user_id'        => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'key'       => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        ),
        'value'     => array(
            'type'      => 'string',
            'length'    => 3200,
            'null'      => false
        )
    );

   public $belongsTo = array('User'=> array('className' => 'User','foreignKey' => 'user_id'));

    /**
     * Save settings
     * @param $settings
     * @param $settingsType
     * @param $userid
     * @return bool
     */
    public function saveSettings($settings, $settingsType, $userid) {
        switch ($settingsType) {
            case "riskSettings":
                $db_settings = $this->getRiskSettings($userid);
                
                $settings = array_map(function($setting) USE ($settings) {
                if(isset($settings['UserSettings'][$setting['id']])) {
                    return array('id' => $setting['id'], 'value' => $settings['UserSettings'][$setting['id']]);
                }
                return array();
                }, $db_settings);

                //DO TO BETTER
                unset($settings["stop_bet"],
                    $settings["ticket_limit_by_country"], 
                    $settings["ticket_limit_by_amount"], 
                    $settings["ticket_tax_by_country"]
                );

                foreach($settings as $key=>$setting) {
                    $out = $this->updateField($key,$setting['value'],$userid);
                    if ($out === false) return false;
                }
                return true;
            break;
            case "depositriskSettings":
                $db_settings = $this->getDepositRiskSettings($userid);
                
                $settings = array_map(function($setting) USE ($settings) {
                if(isset($settings['UserSettings'][$setting['id']])) {
                    return array('id' => $setting['id'], 'value' => $settings['UserSettings'][$setting['id']]);
                }
                return array();
                }, $db_settings);

                foreach($settings as $key=>$setting) {
                    $out = $this->updateField($key,$setting['value'],$userid);
                    if ($out === false) return false;
                }
                return true;
            break;
            
        }//switch
    }

    /**
     * Updates setting field
     * @param $field
     * @param $value
     */
    function updateField($field, $value, $userid) {
        $options['conditions'] = array('UserSettings.key' => $field, 'UserSettings.user_id' => $userid);
        $options['recursive'] = -1;
        $datatosave = $this->find('first', $options);
        
        if (empty($data)) {
            $this->create();
            $datatosave['UserSettings']['key'] = $field;
        }

        $datatosave['UserSettings']['value'] = $value;
        $datatosave['UserSettings']['user_id'] = $userid;
        
        if ($this->save($datatosave)){
            return true;
        } else {
            return false;
        }
    }

    /**
    * Returns risks settings
    * @return mixed
    */
    public function getRiskSettings($userid) {
        $fields = array('minBet', 'maxBet', 'maxBetsCount', 'minBetsCount', 'maxWin', 'daily_wager_limit', 'daily_win_limit');
        $options['recursive']=-1;
        $options['conditions'] = array('UserSettings.key' => $fields, 'UserSettings.user_id' => $userid);

        $settings = $this->find('all', $options);
        $Settingmodel = ClassRegistry::init('Setting'); 
        
        if (empty($settings)) {                                                  //If there are not risk setting for the user use default
            $settings = $Settingmodel->getRiskSettings();
            return $settings;
        } else {
            $list = array();
            foreach ($settings as $setting) {
                $list[$setting['UserSettings']['key']] = $setting['UserSettings'];
            }
            
            foreach($fields as $keys) {
                if (!array_key_exists($keys, $list)) {
                    $settings = $Settingmodel->getRiskSettings();
                    $list[$keys] = $settings[$keys];
                }
            }
            return $list;
        }
    }
    
    /**
    * Returns risks settings
    * @return mixed
    */
    public function getDepositRiskSettings($userid) {
        $fields = array( 'deposits', 'D_Manual', 'minDeposit', 'maxDeposit');
        $options['recursive']=-1;
        $options['conditions'] = array('UserSettings.key' => $fields, 'UserSettings.user_id' => $userid);

        $settings = $this->find('all', $options);
        $Settingmodel = ClassRegistry::init('Setting'); 
        
        if (empty($settings)) {                                                  //If there are not risk setting for the user use default
            $settings = $Settingmodel->getDepositsRisksSettings();
            return $settings;
        } else {
            $list = array();
            foreach ($settings as $setting) {
                $list[$setting['UserSettings']['key']] = $setting['UserSettings'];
            }
            
            foreach($fields as $keys) {
                if (!array_key_exists($keys, $list)) {
                    $settings = $Settingmodel->getRiskSettings();
                    $list[$keys] = $settings[$keys];
                }
            }
            return $list;
        }
    }

    /**
     * Returns risks settings
     * @return mixed
     */
    public function getticketlimit($userid){
        $options['recursive'] = -1;
        $options['conditions'] = array('UserSettings.key' => 'ticket_limit_by_amount', 'UserSettings.user_id' => $userid);

        $settings = $this->find('all', $options);
        if (empty($settings)) {                                                 //If there are not risk setting for the user use default
            $Settingmodel = ClassRegistry::init('Setting');
            $settings = $Settingmodel->getRiskSettings();
            return $settings;
        } else {
            $list = array();
            foreach ($settings as $setting) {
                $list[$setting['UserSettings']['key']] = $setting['UserSettings'];
            }
            return $list;
        }
    }
    
    /**
     * Returns risks settings
     * @return bool
     */
    public function hasSettings($userid){
        $options['recursive']=-1;
        $options['conditions'] = array('UserSettings.user_id' => $userid);
        $settings = $this->find('list', $options);
        if (empty($settings)){
            return false;
        } else {
            return true;
        }
    }
    
    public function getSetting($userId, $key) {
        return $this->find('first', array(
            'recursive' => -1,
            'conditions' => array(
                'UserSettings.key' => 'SessionBets',
                'UserSettings.user_id' => $userId
            )
        ));
    }
    
    public function createSetting($userId, $key, $value) {
        $settings = $this->getSetting($userId, $key);
        if (empty($settings)) {
            $this->create();
            $settings['UserSettings']['user_id'] =   $userId;
            $settings['UserSettings']['key']     =   $key;
            $settings['UserSettings']['value']   =   $value;
        } else {
            $settings['UserSettings']['value']   =   $value;
        }
        $this->save($settings);
    }

}