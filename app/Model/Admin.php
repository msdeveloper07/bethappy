<?php
/**
 * Admin Model
 *
 * Handles Admin Data Source Actions
 *
 * @package    Admin.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */

class Admin extends AppModel
{

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var $useTable bool
     */  
    public $useTable = false;

    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'Admin';
}