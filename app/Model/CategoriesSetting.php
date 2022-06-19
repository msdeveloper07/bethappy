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

class CategoriesSetting extends AppModel {
    
    /**
     * Model name
     * @var $name string
     */
    public $name = 'CategoriesSetting';
    
    public $useTable = 'category_settings'; // This model does not use a database table
    

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
        'category_id'        => array(
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


   public $belongsTo = array('User'=> array('className' => 'User','foreignKey' => 'category_id'));
   
  /**
     * Save settings
     * @param $settings
     * @param $settingsType
     * @param $categoryid
     * @return bool
     */
    public function saveSettings($settings, $settingsType, $categoryid){
        switch ($settingsType) {
            case "riskSettings":
                $db_settings = $this->getRiskSettings($categoryid);
                
                $settings = array_map(function($setting) USE ($settings) {
                if(isset($settings['CategoriesSetting'][$setting['id']])) {
                    return array(
                        'id'    =>  $setting['id'],
                        'value' =>  $settings['CategoriesSetting'][$setting['id']]
                        );
                 }
                return array();
                }, $db_settings);  
                unset($settings["stop_bet"]);                             //DO TO BETTER
                unset($settings["ticket_limit_by_country"]);                            //DO TO BETTER
                unset($settings["ticket_limit_by_amount"]);                             //DO TO BETTER
                unset($settings["ticket_limit_by_country"]);                            //DO TO BETTER

                foreach($settings as $key=>$setting){
                    
                    $out=$this->updateField($key,$setting['value'],$categoryid);
      
                    if ($out===false){
                        return false;
                    }
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
    function updateField($field, $value, $categoryid) {
        $options['conditions'] = array(
            'CategoriesSetting.key' => $field,
            'CategoriesSetting.category_id' => $categoryid,
        );
        $options['recursive'] = -1;
        $datatosave = $this->find('first', $options);
        
        if (empty($data)) {
            $this->create();
            $datatosave['CategoriesSetting']['key'] = $field;
        }
        $datatosave['CategoriesSetting']['value'] = $value;
        $datatosave['CategoriesSetting']['category_id'] = $categoryid;
        
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
    public function getRiskSettings($categoryid){
        $options['recursive']=-1;
        $options['conditions'] = array( 
            'CategoriesSetting.key' => array('minBet', 'maxBet', 'maxBetsCount', 'minBetsCount', 'maxWin', 'daily_wager_limit', 'daily_win_limit'),
            'CategoriesSetting.category_id' => $categoryid
        );

        $settings = $this->find('all', $options);

        //If there are not risk setting for the user use default
        if (empty($settings)) {
            $Settingmodel = ClassRegistry::init('Setting');
            $settings = $Settingmodel->getRiskSettings();
            
            return $settings;
        } else {
            $list = array();
            
            foreach ($settings as $setting) {
                $list[$setting['CategoriesSetting']['key']] = $setting['CategoriesSetting'];
            }
            return $list;
        }
    }
     
    /**
     * Returns risks settings
     * @return mixed
     */
    public function getticketlimit($categoryid){
        $options['recursive']=-1;
        $options['conditions'] = array('CategoriesSetting.key' => 'ticket_limit_by_amount', 'CategoriesSetting.category_id' => $categoryid);

        $settings = $this->find('all', $options);
        
         //If there are not risk setting for the user use default
        if (empty($settings)){
            
            $Settingmodel = ClassRegistry::init('Setting'); 
            
            $settings = $Settingmodel->getRiskSettings();
            
            return $settings;  
        } else {
            $list = array();
            
            foreach ($settings as $setting) {
                $list[$setting['CategoriesSetting']['key']] = $setting['CategoriesSetting'];
            }
            
            return $list;
        }
    }
    
    /**
     * Returns risks settings
     * @return bool
     */
    public function hasSettings($categoryid){
        $options['recursive']=-1;
        $options['conditions'] = array( 
            'CategoriesSetting.category_id' => $categoryid
        );   
        $settings = $this->find('list', $options);
        if (empty($settings)){
            return false;
        }else{
            return true;
        }
    }
    
    /**
     * Admin edit fields
     * @return mixed
     */
    public function getEdit() {
        $usercatModel = ClassRegistry::init('UserCategory');
        $categories = $usercatModel->find('list');
        return array(
            'CategoriesSetting.category_id' => $this->getFieldHtmlConfig('select', array('label' => __('Category'), 'options' => $categories)),
            'CategoriesSetting.key'         => $this->getFieldHtmlConfig('text',array('label' => __('Key'))),
            'CategoriesSetting.value'       => $this->getFieldHtmlConfig('text',array('label' => __('Value')))
        );
    }
    
    /**
     * Admin add fields
     * @return mixed
     */
    public function getAdd() {
        $usercatModel = ClassRegistry::init('UserCategory');
        $categories = $usercatModel->find('list');
        return array(
            'CategoriesSetting.category_id' => $this->getFieldHtmlConfig('select', array('label' => __('Category'), 'options' => $categories)),
            'CategoriesSetting.key'         => $this->getFieldHtmlConfig('text',array('label' => __('Key'))),
            //'CategoriesSetting.value'       => $this->getFieldHtmlConfig('text',array('label' => __('Value')))
        );
    }
}