<?php
/**
 * Front Users Controller
 *
 * Handles Users Actions
 *
 * @package    Events
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */

class UsersSettingsController extends AppController {
    /**
     * Controller name
     * @var string
     */
    public $name = 'UsersSettings';

    /**
     * An array containing the class names of models this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array(
        0  =>  'UserSettings',
        1  =>  'User',
        2  =>  'Setting',
        3  =>  'Risk',
        4  =>  'Country',
        5  =>  'League',
        6  =>  'Sport'
    );

    /**
     * Called before the controller action.
     * @return void
     */
    function beforeFilter() {
        parent::beforeFilter();
    }

    function afterFilter() {
        parent::afterFilter();
    }

    public function admin_index($user_id) {
        $this->set('data', $this->UserSettings->find('all', array('conditions' => array('user_id' => $user_id))));
         $this->set('user_id', $user_id); 
    }
    
    public function admin_add($id) {
        if (!empty($this->request->data)) {
            if ($this->UserSettings->save($this->request->data)){
                $this->__setMessage(__('Changes saved', true));
                $this->redirect(array('controller' => 'UsersSettings', 'action' =>'index',$this->request->data['UserSettings']['user_id']));
            }
        }
         
        $fields = $this->UserSettings->getAdd();
        
        $allsettings=$this->Setting->find('list',array('fields'=>array('Setting.key','Setting.value')));

        unset($allsettings['stop_bet']);
        unset($allsettings['ticket_limit_by_country']);
        unset($allsettings['ticket_limit_by_amount']);
        unset($allsettings['ticket_tax_by_country']);
        
        foreach ($allsettings as $key=>$values){
            $allsettings[$key]=$key."(Default value:".$values.")";
        }
        
        $fields[$fields[1]]=array('type' => 'select', 'options' => $allsettings);
        
        unset($fields[1]);

        $fields['UserSettings.user_id']['default']=$id;
        $this->set('fields', $fields);
    }

    public function admin_edit($userid){
        parent::admin_edit($userid);
    }

    public function admin_risk($user_id) {
        if (!empty($this->request->data)) {
            if ($this->UserSettings->UserSettings($this->request->data, 'riskSettings', $user_id)) {
                $this->__setMessage(__('Settings saved.', true));
                $this->redirect(array('controller' => 'UserSettings', 'action' =>'index',$user_id));
            } else {
                $this->__setError(__('Can\'t save settings.', true));
            }
        }
        $settings = $this->UserSettings->getRiskSettings($user_id);
        
        $this->set('user_id', $user_id); 
        $this->set('settings', $settings);
        $this->set('singularName', 'User Setting');
        $this->set('pluralName', 'User Settings');
    }
    
    public function admin_depositrisk($user_id) {
        if (!empty($this->request->data)) {
            if ($this->UserSettings->saveSettings($this->request->data, 'depositriskSettings', $user_id)) {
                $this->__setMessage(__('Settings saved.', true));
                $this->redirect(array('controller' => 'UserSettings', 'action' =>'index',$user_id));
            } else {
                $this->__setError(__('Can\'t save settings.', true));
            }
        }
        $settings = $this->UserSettings->getDepositRiskSettings($user_id);
        
        $this->set('user_id', $user_id); 
        $this->set('settings', $settings);
        $this->set('singularName', 'User Setting');
        $this->set('pluralName', 'User Settings');
    }
    
    public function admin_delete($id, $userId) {
        $this->autoRender = false;
        $usersetting = $this->Userssetting->getItem($id);
        if (!empty($usersetting)) {
            if ($this->Userssetting->delete($id)) {
                $this->__setMessage(__('Settings for id %s deleted succesffuly', $id));
            } else {
                $this->__setError(__('Could not delete settings for id %s', $id));
            }
        } else {
            $this->__setError(__('Could not delete settings for id %s', $id));
        }
        $this->redirect(array('controller' => 'UsersSettings', 'action' => 'admin_index', $userId));
    }
}
