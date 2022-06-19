<?php

/**
 * Handles Currencies
 *
 * Handles Currencies Actions
 *
 * @package    Currencies
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
class CurrenciesController extends AppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Currencies';
    public $uses = array('Currency');

    /**
     * Called before the controller action.
     * @return void
     */
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('getCurrenciesJson', 'setCurrency', 'getAll', 'admin_toggleActive'));
    }

    public function getCurrenciesJson() {
        $this->autoRender = false;
        $this->layout = false;

        $currencies = array();
        $currency = $this->Currency->getByCode(Configure::Read('Settings.currency'));

        foreach ($this->Currency->getActive() as $curr) {
            $currencies[] = array(
                'id' => $curr['Currency']['id'],
                'name' => $curr['Currency']['name'],
                'code' => $curr['Currency']['code'],
                'selected' => ($curr['Currency']['id'] == $currency['Currency']['id'])
            );
        }
//        var_dump(json_encode($currencies));
//        var_dump(json_last_error_msg());


        $this->response->type('json');
        $this->response->body(json_encode($currencies));
    }

    public function setCurrency($id, $first_time = false) {
        $this->autoRender = false;

        $currency = $this->Currency->getCode($id);
        if (isset($currency))
            Configure::Write('Settings.currency', $currency);

        if ($first_time == true) {
            return json_encode(array('status' => 'success', 'msg' => array('controller' => 'pages', 'action' => 'index')));
        } else {
            return json_encode(array('status' => 'success', 'msg' => $this->referer()));
        }
    }

    public function admin_index() {
        $this->set('data', $this->paginate());
//        var_dump($this->Country->getActions());
        $this->set('actions', $this->Currency->getActions());
    }

    public function admin_toggleActive($currency_id) {
        $this->autoRender = false;
//        $this->layout = 'ajax';

        $currency = $this->Currency->find('first', array('conditions' => array('id' => $currency_id)));
        $currency['Currency']['active'] = !$currency['Currency']['active'];
        $this->Currency->save($currency);

//        $this->Session->setFlash(__('Changes saved.'));
//        $this->__setMessage(__('Changes saved.', true));
//        $this->redirect($this->referer());
//        $this->response->type('json');
//        $this->response->body(json_encode($country));
    }

}
