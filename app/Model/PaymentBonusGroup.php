<?php
/**
 * PaymentBonusGroup Model
 *
 * Handles PaymentBonusGroup Data Source Actions
 *
 * @package    PaymentBonusesGroup.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */

class PaymentBonusGroup extends AppModel{

    /**
     * Model name
     *
     * @var string
     */
    public $name = 'PaymentBonusGroup';

    /**
     * Model schema
     *
     * @var array
     */
    protected $_schema = array(
        'id'        => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'name'     => array(
            'type'      => 'string',
            'length'    => 80,
            'null'      => false
        ),
		'textbonus'     => array(
            'type'      => 'string',
            'length'    => null,
            'null'      => true
        )
    );

	
    /**
     * Detailed list of hasMany associations.
     *
     * @var $hasMany array
     */
	public $hasMany= array('PaymentBonus');

	
	
	
	/**
     * List of behaviors to load when the model object is initialized.
     *
     * @var $actsAs array
     */

	 public $actsAs = array(
        'Translate' => array(
            'name' => 'translations',
			'textbonus'
        )
    );
	
	
    /**
     * Custom display field name.
     * Display fields are used by Scaffold, in SELECT boxes' OPTION elements.
     *
     * @var string
     */
    public $displayField = 'name';

    /**
     * Returns admin actions
     *
     * @return array
     */
    public function getActions(){
		$actions = parent::getActions(); 
		
		$show_codes = array(
				'name' => 'Edit codes',
				'action' => 'index',
				'controller' => 'payment_bonuses'
		);
		
		array_unshift($actions, $show_codes);
		
		return $actions;
	}
	
	
	 public function getbonuses(){
		$this->locale = Configure::read('Config.language');
		$options['recursive'] = 1;
		$data = $this->find('all',$options);
		return $data;
	}
	
	
	
	
	
}