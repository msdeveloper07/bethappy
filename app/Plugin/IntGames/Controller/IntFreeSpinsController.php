<?php

/**
 * Front IntGames Controller
 * Handles IntGames Actions
 * 
 */
App::uses('AppController', 'Controller');

class IntFreeSpinsController extends IntGamesAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'IntFreeSpins';
    public $components = array(0 => 'RequestHandler', 1 => 'Paginator');

    /**
     * Additional models
     * @var array
     */
    public $uses = array(
        'IntGames.IntGame',
        'IntGames.IntGameActivity',
        'IntGames.IntCategory',
        'IntGames.IntBrand',
        'IntGames.IntFavorite',
        'IntGames.IntFreeSpin',
        //'IntGames.IntPlugin',
        'TransactionLog',
        'User',
    );
    public $helpers = array(0 => 'Paginator');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->layout = 'admin';
        $this->Auth->allow();
    }

//    public function admin_index() {
//        $this->layout = 'admin';
//        //$data = array();
//
//
//        $this->paginate = $this->IntFreeSpin->getIndex();
//        var_dump($this->paginate);
//        //exit;
//        $this->paginate['recursive'] = 0;
//        //$this->paginate['order'] = array('IntFreeSpin.order' => 'DESC');
//        //$this->paginate['contain'] = array('IntGame', 'User');
//
//        $data = $this->paginate();
//
//
////        foreach ($data as &$game) {
////
////            $category = $this->IntCategory->getItem($game['IntGame']['category_id']);
////            $game['IntCategory'] = $category['IntCategory'];
////            $brand = $this->IntBrand->getItem($game['IntGame']['brand_id']);
////            $game['IntBrand'] = $brand['IntBrand'];
////        }
//        var_dump($data);
//        $this->set('data', $data);
//        //$this->set('actions', $this->IntGame->getActions());
//        parent::admin_index();
//    }

    public function admin_add() {
        $this->layout = 'admin';
        $games = $this->IntGame->getFreeSpinsGames();
        $users = $this->User->find('all', array('conditions' => array('User.group_id' => 1, 'User.status' => 1)));

        $this->IntPlugin = ClassRegistry::init('int_plugins');
        $platforms = $this->IntPlugin->find('all', array('conditions' => array('active' => 1), 'recursive' => -1));
        //$platforms = $this->IntPlugin->getActive();
        //var_dump($platforms);
        $this->set('tabs', $this->IntFreeSpin->getTabs($this->request->data));
        $this->set('platforms', $platforms);
        $this->set('games', $games);
        $this->set('users', $users);
//        try {
//            $this->log('FREE SPINS');
//            $this->log($this->request);
//
//
//            if (!empty($this->request->data)) {
//                $this->log($this->request);
//                var_dump($this->request);
//                exit;
//                //var_dump($users);
//            }
//        } catch (Exeption $e) {
//            $this->__setError(__($e->getMessage(), true));
//        }

        parent::admin_add();
    }

//    public function admin_view($id) {
//        parent::admin_view(array('IntGame.id' => $id), 'IntGame');
//
//        $options['conditions'] = array('IntGame.id' => $id);
//        $options['recursive'] = 1;
//        $data = $this->IntGame->find('first', $options);
//        //var_dump($data);
//        $this->set('model', 'IntGame');
//        $this->set('fields', $data);
//    }
}
