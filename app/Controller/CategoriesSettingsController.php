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

class CategoriesSettingsController extends AppController{
    
    /**
     * Controller name
     * @var string
     */
    public $name = 'CategoriesSettings';

    /**
     * An array containing the class names of models this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array(
        0  =>  'CategoriesSetting',
        1  =>  'User',
        2  =>  'Setting',
        3  =>  'Risk',
        4  =>  'UserCategory'
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

    //check
//    public function admin_index($id) {
//        parent::admin_index(array('CategoriesSetting.category_id'=>$id));     
//    }
    
     public function admin_add($id) {
        if (!empty($this->request->data)){
             
            if ($this->CategoriesSetting->save($this->request->data)){
                $this->__setMessage(__('Changes saved', true));
                $this->redirect(array('controller' => 'CategoriesSettings', 'action' =>'index',$this->request->data['CategoriesSetting']['category_id']));
            }
        }
         
        $fields = $this->CategoriesSetting->getAdd();
        
        $allsettings = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.value')));
        unset($allsettings['ticket_limit_by_amount']);
        unset($allsettings['ticket_limit_by_country']);
        unset($allsettings['ticket_tax_by_country']);
        
        foreach ($allsettings as $key=>$values){
            $allsettings[$key]=$key."(Default value:".$values.")";
        }
        
        $fields[$fields[1]]=array(
            'type' => 'select',
            'options' => $allsettings
        );
        
        unset($fields[1]);

        $fields['CategoriesSetting.category_id']['default']=$id;
        $this->set('fields', $fields);
    }
    
    public function admin_edit($user_id){
        parent::admin_edit($user_id);        
    }
    
    public function admin_risk($category_id) {
         if (!empty($this->request->data)) {
            if ($this->CategoriesSetting->saveSettings($this->request->data, 'riskSettings',$category_id)) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('Can\'t save settings.', true));
            }
        }
        $settings = $this->CategoriesSetting->getRiskSettings($category_id);
        
        $this->set('userid', $category_id); 
        $this->set('settings', $settings);
        $this->set('singularName', 'User Setting');
        $this->set('pluralName', 'User Settings');
    }
}
