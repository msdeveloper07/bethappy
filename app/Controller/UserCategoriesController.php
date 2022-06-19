<?php

/**
 * Handles Dashboard
 *
 * Handles Dashboard Actions
 *
 * @package    Dashboard
 * @author     Deividas Petraitis <deividas@laiskai.lt>
 * @copyright  2013 The ChalkPro Betting Scripts
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://www.chalkpro.com/
 */
class UserCategoriesController extends AppController {

    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'UserCategories';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('UserCategory');

    /**
     * Called before the controller action.
     */
    function beforeFilter() {
        parent::beforeFilter();
    }

    public function admin_index() {
        $this->set('data', $this->paginate());
        $this->set('tabs', $this->UserCategory->getTabs($this->request->params));
        $this->set('actions', $this->UserCategory->getActions());
    }

}
