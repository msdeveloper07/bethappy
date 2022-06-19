<?php
/**
 * Group Model
 *
 * Handles Group Data Source Actions
 *
 * @package    Groups.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */

class Group extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'Group';

    /**
     * List of behaviors to load when the model object is initialized.
     *
     * @var $actsAs array
     */
    public $actsAs = array('Containable', 'Acl' => array('type' => 'requester'));

    /**
     * Detailed list of hasMany associations.
     *
     * @var $hasMany array
     */
    public $hasMany = 'User';

    /**
     * User group cont value
     */
    const USER_GROUP = 1;

    /**
     * Admin group const value
     */
    const ADMINISTRATOR_GROUP = 2;

    /**
     * Operator group const value
     */
    const OPERATOR_GROUP = 6;

    /**
     * Cashier group cont value
     */
    const CASHIER_GROUP = 7;
    
    /**
     * Terminal Operator
     */
    const TERMINAL_GROUP = 12;

    public function parentNode() {
        return null;
    }

    /**
     * Returns add fields
     *
     * @return array
     */
    public function getAdd() {
        return array('Group.name');
    }

    /**
     * Returns groups
     *
     * @return mixed
     */
    public function getGroups() {
        $groups = array();
        $data = $this->find('all');

        foreach ($data as &$group) {
            $groups[$group['Group']['id']] = $group['Group']['name'];
        }
        return $groups;
    }

    /**
     * Returns admin groups
     *
     * @return mixed
     */
    public function getAdminGroups() {
        $groups = $this->getGroups();
        unset($groups['1']);
        return $groups;
    }
    
    /**
     * Returns a list of all available categories 
     * with id's as indexes
     *
     * @return array
     */
    public function list_groups() {
        return $this->find('list', array(
           'recursive' => -1,
           'fields'     => array('id', 'name')
        ));
    }
}