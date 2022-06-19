<?php
/**
 * Fraud Model
 *
 * Handles Fraud Data Source Actions
 *
 * @package    Fraud.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */

class Fraud extends AppModel {

    /**
     * Model name
     *
     * @var string
     */
    public $name = 'Fraud';
	
	public $useTable = 'Fraud';

    /**
     * Model schema
     *
     * @var $_schema array
    
    protected $_schema = array(
        'id'                => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'user_id'          => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'kyc_type'		=> array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        ),
        'kyc_data_url'  => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        ),
        'date'           => array(
            'type'      => 'datetime',
            'length'    => null,
            'null'      => true
        )
    );
 */


   

    /**
     * Detailed list of belongsTo associations.
     *
     * @var array
     */
    public $belongsTo = array('User');


	
	
	
		
		
		
		
}