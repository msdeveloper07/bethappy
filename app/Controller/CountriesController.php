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
class CountriesController extends AppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Countries';
    public $uses = array('Country');

    /**
     * Called before the controller action.
     * @return void
     */
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('getCountriesJson', 'admin_toggleActive'));
    }

    public function getCountriesJson() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $countries = array();

        foreach ($this->Country->find('all') as $country) {
            if ($country['Country']['active'] == 1) {
                $countries[] = array(
                    'id' => $country['Country']['id'],
                    'name' => $country['Country']['name'],
                    'alpha2_code' => $country['Country']['alpha2_code'],
                    'alpha3_code' => $country['Country']['alpha3_code'],
                    'iso31662_code' => $country['Country']['iso31662_code'],
                    'numeric_code' => $country['Country']['numeric_code'],
                    'active' => $country['Country']['active'],
                );
            }
        }
//        var_dump($countries);

        $this->response->type('json');
        $this->response->body(json_encode($countries));
//          var_dump(json_last_error_msg());
    }

//
    public function admin_index() {
        $this->set('data', $this->paginate());
//        var_dump($this->Country->getActions());
        $this->set('actions', $this->Country->getActions());
    }

    public function admin_toggleActive($country_id) {
        $this->autoRender = false;
//        $this->layout = 'ajax';

        $country = $this->Country->find('first', array('conditions' => array('id' => $country_id)));
        $country['Country']['active'] = !$country['Country']['active'];
        $this->Country->save($country);
        //var_dump($country['Country']);
//        if ($country['Country']['active'] == 1) {
//            $this->__setMessage(__('Item activated.', true));
//        } else {
//            $this->__setMessage(__('Item deactivated.', true));
//        }
//        $this->redirect(array('action' => 'index'));
//        $this->Session->setFlash(__('Changes saved.'));
//        $this->__setMessage(__('Changes saved.', true));
//        $this->redirect($this->referer());
//        $this->response->type('json');
//        $this->response->body(json_encode($country));
    }

}
