<?php

/**
 * Front Staffs Controller
 * Handles Staffs Actions
 * @package    Staffs
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
class StaffsController extends AppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Staffs';

    /**
     * An array containing the class names of models this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Staff', 'Log');

    public function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow(array('affiliate_view'));
    }

    /**
     * Admin edit
     * @param null $id
     */
    public function admin_edit($id = NULL) {
        if (!empty($this->request->data)) {
            if (empty($this->request->data['Staff']['password'])) {
                $staff = $this->Staff->getItem($id);
                $this->request->data['Staff']['password'] = $staff['Staff']['password'];
            } else {
                $this->request->data['Staff']['password'] = $this->Auth->password($this->request->data['Staff']['password']);
            }
        }
        parent::admin_edit($id);
        $this->request->data['Staff']['password'] = '';
    }

    public function admin_add($id = NULL) {
        if (!empty($this->request->data)) {
            $this->request->data['Staff']['password'] = $this->Auth->password($this->request->data['Staff']['password_raw']);
            $this->request->data['Staff']['status'] = 1;
        }
        parent::admin_add($id);
        $this->request->data['Staff']['password'] = '';
    }

    public function admin_deposit_bonus_history($id) {
        //FIXME: someday in traint (php 5.4)
        $this->view = "admin_index";
        $conditions['user_id'] = $id;
        return parent::admin_index($conditions, 'PaymentBonusUsage');
    }

    public function affiliate_view($id) {
        $this->layout = 'affiliate';
        $data = $this->Staff->find('first', array('conditions' => array('Staff.id' => $id),  
        'contain' => array(
            'Group',
            'Currency',
        ),'fields'=>array('Staff.username', 'Staff.first_name', 'Staff.last_name', 'Staff.email',  
            'Staff.address1', 'Staff.city', 'Staff.zip_code', 'Staff.country', 
            'Staff.date_of_birth', 'Staff.mobile_number', 'Staff.last_visit_ip', 'Staff.registration_date', 'Currency.name')
            
            ));
        //var_dump($data);
        $this->set('fields', $data);
    }

}
