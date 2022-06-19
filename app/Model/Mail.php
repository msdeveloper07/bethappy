<?php
/**
 * Mail Model
 *
 * Handles Mail Data Source Actions
 *
 * @package    Mails.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
class Mail extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'Mail';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var $useTable bool
     */
    public $useTable = false;

    /**
     * List of validation rules.
     *
     * @var $validate array
     */
	 /* validation bug fix */    	
	 /*
    public $validate = array(
        'email' => array(
            'rule' => 'email',
            'message' => 'Please enter valid email address'
        ),
    	
        'subject' => array(
            'rule' => array('maxLength', '10'),
            'allowEmpty' => false,
            'message' => 'Please enter subject shorter than 10 length'
        ),
        'content' => array(
            'rule' => array('minLength', '1'),
            'allowEmpty' => false,
            'message' => 'Please enter message'
        )
    );
*/
    /**
     * Returns tabs
     *
     * @param $params
     * @return array
     */
    public function getTabs($params)
    {
        $tabs = array();

        $tabs[] = $this->__makeTab(__('Send Mail', true), 'index', 'mails', NULL, false);
        $tabs[] = $this->__makeTab(__('Send to All', true), 'all', 'mails', NULL, false);

        if ($params['action'] == 'admin_index') {
            $tabs[0]['active'] = true;
        }

        if ($params['action'] == 'admin_all') {
            $tabs[1]['active'] = true;
        }

        return $tabs;
    }
}