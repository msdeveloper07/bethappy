<?php

/**
 * Front IntCategories Controller
 * Handles IntCategories Actions
 * 
 */
App::uses('AppController', 'Controller');

class IntBrandsController extends IntGamesAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'IntBrands';
    public $components = array(0 => 'RequestHandler');

    /**
     * Additional models
     * @var array
     */
    public $uses = array('IntGames.IntBrand');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('admin_index', 'editMethod', 'getBrandBySlug', 'admin_toggleActive');
    }

//    public function admin_index() {
////        var_dump($this->IntBrand->getBrands());
//        $this->set('brands', $this->IntBrand->getBrands());
//    }

    public function editMethod() {
        $this->autoRender = false;
        try {
            $data = $this->IntBrand->getItem($this->request->query['id']);

            //var_dump($this->request->query);
            $data['IntBrand']['order'] = $this->request->query['order'];
            $data['IntBrand']['active'] = $this->request->query['active'];
//      $data['IntBrand']['image'] = $this->request->query['image'];

            if ($this->IntBrand->save($data)) {
                return json_encode(array('status' => 'success', 'msg' => __('Done')));
            } else {
                throw new Exception('Could not save game provider.');
            }
        } catch (Exception $e) {
            $this->__setError($e->getMessage());
        }
    }

    
        public function getBrandBySlug($brand_slug) {
        $this->autoRender = false;

        $brand_id = $this->IntBrand->getBySlug($brand_slug);
        
        $response = array('status' => 'success', 'brand_id' => $brand_id);
        
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }
    
    
        public function admin_toggleActive($brand_id) {
        $this->autoRender = false;

        $brand = $this->IntBrand->find('first', array('conditions' => array('id' => $brand_id)));
        $brand['IntBrand']['active'] = !$brand['IntBrand']['active'];
        $this->IntBrand->save($brand);

    }
}
