<?php
/**
 * API Model
 *
 * Handles API Data Source Actions
 *
 * @package    API.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */

class Job extends AppModel {
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'Job';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var bool
     */
    public $useTable = 'jobs';
        
    /**
     * Model schema
     *
     * @var array
     */
    protected $_schema = array(
        'id'            => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'description'   => array(
            'type'      => 'int',
            'length'    => null,
            'null'      => false
        ),
        'date'          => array(
            'type'      => 'datetime',
            'length'    => null,
            'null'      => true
        ),
        'frequency'     => array(
            'type'      => 'string',
            'length'    => null,
            'null'      => true
        ),
        'access_group'     => array(
            'type'      => 'int',
            'length'    => null,
            'null'      => true
        ),
        'name'          => array(
            'type'      => 'string',
            'length'    => null,
            'null'      => true
        )
    );
    
    
    /**
     * Detailed list of belongsTo associations.
     *
     * @var array
     */
    public $belongsTo = array(
        'Group' => array(
            'className'     => 'Group',
            'foreignKey'    => 'access_group',
        )
    );
        
    /**
     * Returns edit fields
     *
     * @return array|mixed
     */
    public function getEdit() {
        $Group = ClassRegistry::init('Group');
        
        return array(
            'description',
            'access_group'  =>  $this->getFieldHtmlConfig('select', array('options' => $Group->list_groups())),
            'frequency',
            'date',
            'name',
        );
    } 
    
    /**
     * Returns admin index fields
     *
     * @return array
     */
    public function getIndex() {
        $options['fields'] = array(
            'Job.id',
            'Job.description',
            'Job.frequency',
            'Job.access_group',
            'Job.date',
        );
        
        if(CakeSession::read('Auth.User.group_id') != Group::ADMINISTRATOR_GROUP) {  
            $options['conditions'] = array(
                'Job.access_group' => CakeSession::read('Auth.User.group_id'),
            );         
        }
        
        return $options;
    }
    
    /**
     * Returns scaffold actions list
     *
     * @return array
     */
    public function getActions() {         
        $actions[] = array(
            'name'          => __('Edit', true),
            'controller'    => 'jobs',
            'action'        => 'edit',
            'class'         => 'btn btn-mini btn-primary'
        );
        
        $actions[] = array(
            'name'          => __('Execute', true),
            'controller'    => 'jobs',
            'action'        => 'execute',
            'class'         => 'btn btn-mini btn-warning'
        );
        
        return $actions;
    }
    
    /**
     * Returns admin tabs
     *
     * @param $params
     * @return array
     */
    public function getTabs($params) {
        $tabs = parent::getTabs($params);
        unset($tabs['jobsadmin_view']);
        
        return $tabs;
    }
    
    public function update($id) {
        $this->create();
        $this->save(array(
            'Job' => array(
                'id'    => $id,
                'date'  => $this->__getSqlDate()
            )
        ));
    }
}