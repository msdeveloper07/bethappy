<?php

/**
 * User Model
 *
 * Handles User Data Source Actions
 *
 * @package    Users.UserLog
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
class UserLog extends AppModel {

    /**
     * Model name
     *
     * @var string
     */
    public $name = 'UserLog';
    public $useTable = 'user_logs';

    /**
     * Model schema
     *
     * @var $_schema array
     */
    protected $_schema = array(
        'id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'user_id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'action' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'date' => array(
            'type' => 'datetime',
            'length' => null,
            'null' => false
        ),
        'ip' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        )
    );

    /**
     * List of behaviors to load when the model object is initialized.
     * @var array
     */
    public $actsAs = array('Containable');

    /**
     * Detailed list of belongsTo associations.
     * @var array
     */
    public $belongsTo = array('User');

    public function create_log($data) {
        $this->create();
        $this->save($data);
    }

    public function getUserlogs($dates = array(), $user_id = null, $map = false) {
        $options['recursive'] = -1;

        if ($user_id != null)
            $options['conditions']['user_id'] = $user_id;

        if ($dates['from'] && $dates['to']) {
            $options['conditions']['date BETWEEN ? AND ?'] = array($dates['from'], $dates['to']);
        }

        $options['order'] = array('date DESC');

        return $this->find('all', $options);
    }

    /**
     * Gets pagination
     * @param string $options
     * @return array
     */
    public function getPagination($options = array()) {
        //var_dump($options);
        $pagination = array(
            'limit' => Configure::read('Settings.itemsPerPage'),
            'fields' => array(
                'UserLog.id',
                'UserLog.date',
                'UserLog.action',
                'UserLog.ip'
            ),
            'order' => array('UserLog.date' => 'DESC')
        );
        
         if (!empty($options)) {
            $pagination['conditions'] = $options['conditions'];
        }
        //var_dump($pagination);
        return $pagination;
    }

    public function userLogin($user_id, $user_ip) {
        $dd['UserLog'] = array(
            'user_id' => $user_id,
            'action' => 'login',
            'date' => $this->__getSqlDate(),
            'ip' => $user_ip
        );

        $this->create_log($dd);
    }
    
       public function getSearch() {
        $fields = array(
//            'UserLog.id' => $this->getFieldHtmlConfig('number', array('label' => __('ID'))),
//            'UserLog.action' => $this->getFieldHtmlConfig('text', array('label' => __('Action'))),
            'UserLog.ip' => $this->getFieldHtmlConfig('text', array('label' => __('IP'))),
            'UserLog.date' => $this->getFieldHtmlConfig('date', array('label' => __('Date'))),
        );
        return $fields;
    }

}
