<?php

/**
 * Front Logs Controller
 *
 * Handles Logs Actions
 *
 * @package    Logs
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
class LogsController extends AppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Logs';
    public $uses = array('Log');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('affiliate_view'));
    }

    /**
     * Admin index
     * @return mixed|void
     */
    public function admin_index() {
        if ($this->request->data)
            $options['conditions']['Log.created BETWEEN ? AND ?'] = array($this->request->data['Report']['from'], $this->request->data['Report']['to']);
        $this->paginate = $this->Log->getPagination($options);
        $this->set('data', $this->paginate());
    }

    public function admin_view($id) {
        if ($this->request->data)
            $options['conditions']['Log.user_id'] = CakeSession::read('Auth.User.id');
        $this->paginate = $this->Log->getPagination($options);
        $this->set('data', $this->paginate());
    }

    public function affiliate_view() {
        $this->layout = 'affiliate';

        $options['conditions']['Log.user_id'] = CakeSession::read('Auth.Affiliate.user_id');
        $this->paginate = $this->Log->getPagination($options);
        $this->set('data', $this->paginate());
    }

}
