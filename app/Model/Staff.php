<?php

/**
 * Staff Model
 *
 * Handles Staff Data Source Actions
 *
 * @package    Staffs.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
class Staff extends AppModel {

    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'Staff';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var $useTable string
     */
    public $useTable = 'users';

    /**
     * List of behaviors to load when the model object is initialized.
     *
     * @var $actsAs array
     */
    public $actsAs = array('Containable');

    /**
     * Detailed list of belongsTo associations.
     *
     * @var $belongsTo array
     */
    public $belongsTo = array('Group', 'Currency', 'Country');

    /**
     * List of validation rules.
     *
     * @var array
     */
    public $validate = array(
        'username' => array(
            'alphaNumeric' => array(
                'rule' => 'alphaNumeric',
                'allowEmpty' => false,
                'message' => 'Alphabets and numbers only'
            ),
            'between' => array(
                'rule' => array('between', 5, 15),
                'message' => 'Between 5 to 15 characters'
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This username has already been taken.'
            )
        ),
        'password_raw' => array(
            'rule' => array('minLength', '5'),
            'message' => 'Mimimum 5 characters long'
        )
    );

    /**
     * Returns index fields
     *
     * @return array
     */
    public function getIndex() {
        return array(
            'fields' => array(
                'Staff.id',
                'Staff.username',
                'Staff.balance',
                'Staff.group_id'
            ),
            'conditions' => array(
                'Staff.group_id <>' => 1
            )
        );
    }

    /**
     * Returns view
     *
     * @param $id
     * @return array
     */
    public function getView($id) {
        return $this->find('first', array(
                    'fields' => array(
                        'Staff.id',
                        'Staff.username',
                        'Staff.email',
                        'Staff.group_id',
                        'Staff.first_name',
                        'Staff.last_name'
                    ),
                    'recursive' => -1,
                    'conditions' => array(
                        'Staff.id' => $id,
                        'Staff.group_id <>' => 1
                    )
        ));
    }

    /**
     * Returns add fields
     *
     * @return array|mixed
     */
    public function getAdd() {
        $group_id_field = array('type' => 'select', 'options' => $this->Group->getAdminGroups());
        $fields = array(
            'Staff.username',
            'Staff.password_raw' => array('type' => 'password', 'label' => __('Password')),
            'Staff.email',
            'Staff.group_id' => $group_id_field,
            'Staff.first_name',
            'Staff.last_name',
            'Staff.has_member_card',
            'Staff.member_card_no'
        );
        return $fields;
    }

    /**
     * Returns edit fields
     *
     * @return array|mixed
     */
    public function getEdit() {
        $group_id_field = array('type' => 'select', 'options' => $this->Group->getAdminGroups());
        $fields = array(
            'Staff.id',
            'Staff.username',
            'Staff.password',
            'Staff.email',
            'Staff.group_id' => $group_id_field,
            'Staff.first_name',
            'Staff.last_name',
            'Staff.has_member_card',
            'Staff.member_card_no'
        );
        return $fields;
    }

    /**
     * Gets search fields
     *
     * @return array
     */
    public function getSearch() {
        $fields = array(
            'Staff.id' => array('type' => 'number'),
            'Staff.username' => array('type' => 'text'),
            'Staff.username' => array('type' => 'email'),
            'Staff.group_id' => array('type' => 'select', 'options' => $this->Group->getAdminGroups()),
            'Staff.first_name',
            'Staff.last_name'
        );
        return $fields;
    }

    /**
     * List actions
     *
     * @return array
     */
    public function getActions() {
        return array(
            0 => array(
                'name' => __('Edit', true),
                'action' => 'edit',
                'controller' => NULL,
                'class' => 'btn btn-sm btn-warning'
            ),
            1 => array(
                'name' => __('Delete', true),
                'action' => 'delete',
                'controller' => NULL,
                'class' => 'btn btn-sm btn-danger'
            ),
//            2 => array(
//                'name' => __('Fund staff', true),
//                'action' => 'addBalance',
//                'controller' => 'Paymentmanual',
//                'class' => 'btn btn-mini btn-info'
//            ),
//            3 => array(
//                'name' => __('Charge staff', true),
//                'controller' => 'Paymentmanual',
//                'action' => 'chargeBalance',
//                'class' => 'btn btn-mini btn-info'
//            ),
            4 => array(
                'name' => __('Login/Logout', true),
                'action' => 'viewlog',
                'controller' => 'Userlog',
                'class' => 'btn btn-sm btn-dark'
            )
        );
    }

}
