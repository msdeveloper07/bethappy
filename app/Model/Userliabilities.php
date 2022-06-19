<?php
/**
 * User Model
 *
 * Handles User Data Source Actions
 *
 * @package    Users.Userlog
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */

class Userliabilities extends AppModel {

     /**
     * Model name
     * @var string
     */
    public $name = 'Userliabilities';
        
    public $useTable = 'liabilities';
    
    /**
     * Model schema
     * @var $_schema array
     */
    protected $_schema = array(
        'id'                => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'month'       => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        ),
        'Debit'    => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        ),
        'Credit'       => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        ),
        'Net'       => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        )
    );
	
    public function createlog($data) {
        $this->create();
        $this->save($data, false);
    }
	
    public function getLiabilities($month){
	if ($month!=Null){
            $options['conditions']= array('Userliabilities.month' =>  $month);
            return $this->find('first',$options);
        } else {
            return null;
        }
    }
}