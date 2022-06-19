<?php
/**
 * BonusCode Model
 *
 * Handles BonusCode Data Source Actions
 *
 * @package    BonusCodes.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */

class BonusCode extends AppModel
{
    /**
     * Model name
     * @var string
     */
    public $name = 'BonusCode';

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
        'code'     => array(
            'type'      => 'string',
            'length'    => 50,
            'null'      => false
        ),
        'amount'       => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'times'     => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'expires'    => array(
            'type'      => 'datetime',
            'length'    => null,
            'null'      => false
        )
    );

	
	
	/**
     * Returns actions
     *
     * @return array
     */
    public function getActions()
    {
        return array(
			0   =>  array(
                'name'          =>  __('View', true),
                'action'        =>  'view',
                'controller'    =>  NULL,
                'class'         =>  'btn btn-mini'
            ),
			
            1   =>  array(
                'name'          =>  __('Edit', true),
                'action'        =>  'edit',
                'controller'    =>  NULL,
                'class'         =>  'btn btn-mini btn-primary'
            ),

            2   =>  array(
                'name'          =>  __('Users', true),
                'action'        =>  'admin_bonus_to_user',
                'controller'    =>  NULL,
                'class'         =>  'btn btn-mini btn-success'
            )
        );
    }
	
	
	
    /**
     * Detailed list of hasMany associations.
     *
     * @var $hasMany array
     */
    public $hasMany = array('BonusCodesUser');

    /**
     * Find bonus code
     *
     * @param $code
     * @return array
     */
    public function findBonusCode($code)
    {
        $options['conditions'] = array(
            'BonusCode.code' => $code,
            'BonusCode.times >' => 0,
            'BonusCode.expires >' => $this->getSqlDate()
        );
        $options['recursive'] = -1;
        $bonusCode = $this->find('first', $options);
        return $bonusCode;
    }

	
	/**
     * Find bonus code
     *
     * @param $code
     * @return array
     */
    public function findBonusamount($id)
    {
        $options['conditions'] = array(
            'BonusCode.id' => $id,
        );
        $options['recursive'] = -1;
		$options['fields'] = array('BonusCode.amount');
		
        $bonusCode = $this->find('first', $options);
        return $bonusCode;
    }
	
	
	
	
    /**
     * Use bonus code
     *
     * @param $id
     */
    public function useCode($id)
    {
        $options['conditions'] = array(
            'BonusCode.id' => $id
        );
        $options['recursive'] = -1;
        $bonusCode = $this->find('first', $options);
        $bonusCode['BonusCode']['times'] = $bonusCode['BonusCode']['times'] - 1;
        $this->save($bonusCode);
    }

    /**
     * Edit entry fields
     *
     * @return array
     */
    public function getAdd()
    {
        return array(
            'BonusCode.code'    =>  array(
                'type'  =>  'text'
            ),

            'BonusCode.amount'  =>  array(
                'type'   =>  'number'
            ),

            'BonusCode.times'  =>  array(
                'type'  =>  'number'
            ),

            'BonusCode.expires'  =>  $this->getFieldHtmlConfig('date')
        );
    }
    /**
     * Edit entry fields
     *
     * @return array
     */
    public function getEdit()
    {
        return array(
            'BonusCode.code'    =>  array(
                'type'  =>  'text'
            ),

            'BonusCode.amount'  =>  array(
                'type'  =>  'number'
            ),

            'BonusCode.times'  =>  array(
                'type'  =>  'number'
            ),

            'BonusCode.expires'  =>  $this->getFieldHtmlConfig('date')
        );
    }
}