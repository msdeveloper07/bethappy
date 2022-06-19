<?php

/**
 * Front IntGames Controller
 * Handles IntGames Actions
 * 
 */
App::uses('AppController', 'Controller');

class IntGamesController extends IntGamesAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'IntGames';
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
        'TransactionLog',
        'User',
    );
    public $helpers = array(0 => 'Paginator');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        parent::beforeFilter();
        // $this->layout = 'admin';
        $this->Auth->allow('admin_ordered_games_desktop', 'admin_ordered_games_mobile', 'admin_add_free_spins', 'admin_toggleActive', 'index', 'game', 'getGameUrl', 'adminInsertGames', 'admin_add_game', 'getGames', 'getGamesByCategoryId', 'getGamesByBrandId', 'getGamesBySource');
    }

    public function index() {
        $this->autoRender = false;

        $active = 1;
        $order = 'desc';
        $categorySlug = ($this->request->query['category'] ? $this->request->query['category'] : null);
        $brandSlug = ($this->request->query['brand'] ? $this->request->query['brand'] : null);

        $data = $this->IntGame->getGames($active, $order, $categorySlug, $brandSlug, $this->isMobile());

        $this->response->type('json');
        $this->response->body(json_encode($data, JSON_NUMERIC_CHECK));
    }

    public function getGames() {
        $this->autoRender = false;

        $data = $this->IntGame->getGames();

        $this->response->type('json');
        $this->response->body(json_encode($data, JSON_NUMERIC_CHECK));
    }

    public function getGamesByCategoryId($category_id) {
        $this->autoRender = false;

        $data = $this->IntGame->getGamesByCategoryId($category_id);
        //find('all', array('conditions' => array('IntGames.active' => 1, 'IntGames.jackpot' => 0, 'IntGames.order' => 'DESC', 'IntGames.category_id' => $category_id)));

        $this->response->type('json');
        $this->response->body(json_encode($data, JSON_NUMERIC_CHECK));
    }

    public function getGamesByBrandId($brand_id) {
        $this->autoRender = false;

        $data = $this->IntGame->getGamesByBrandId($brand_id);
        //find('all', array('conditions' => array('IntGames.active' => 1, 'IntGames.jackpot' => 0, 'IntGames.order' => 'DESC', 'IntGames.brand_id' => $brand_id)));

        $this->response->type('json');
        $this->response->body(json_encode($data, JSON_NUMERIC_CHECK));
    }

    public function getGamesBySource($source) {
        $this->autoRender = false;

        $data = $this->IntGame->getGamesBySource($source);

        $this->response->type('json');
        $this->response->body(json_encode($data, JSON_NUMERIC_CHECK));
    }

    public function game($game_id, $fun_play = false, $just_url = false) {

        $this->log($game_id, 'debug');
        $this->autoRender = false;

//        var_dump($game_id, $fun_play);
        if ($fun_play == "true") {
            $fun_play = true;
        } else {
            $fun_play = false;
        }

        if ($just_url == "true") {
            $just_url = true;
        } else {
            $just_url = false;
        }

        if ($game_id) {
            $getURL = $this->IntGame->getUrl($game_id, $fun_play);
            //var_dump($getURL);
            //exit;
            $game = $this->IntGame->getGame($game_id);
            $favorite = $this->IntFavorite->getFavorite($this->Auth->user("id"), $game_id);

            if ($getURL['URL'] && $just_url) {
                $this->redirect($getURL['URL']);
            } elseif ($getURL['content']) {

                $this->IntGame->addOpenStats($game_id);
                $this->IntGameActivity->saveActivity($this->Auth->user("id"), $game_id, (int) $fun_play, (int) $this->isMobile());

                $response = array('status' => 'success', 'content' => $getURL['content'], 'URL' => $getURL['URL'], 'game' => $game, 'favorite' => $favorite);
            } else {
                $response = array('status' => 'error', 'msg' => $getURL['message']);
            }
        } else {
            $response = array('status' => 'error', 'msg' => __('Please provide a game id.'));
        }
        if (!$just_url) {
            $this->response->type('json');
            $this->response->body(json_encode($response));
        } else {
            echo $response['msg'];
            echo $response['content'];
        }
    }

    public function admin_opengame($parentid, $source) {
        $this->autoRender = false;

        $game = $this->IntGame->getSourceGame($parentid, $source);
        $headers = $this->IntGame->getHeaders($source);

        $data = array('headers' => $headers, 'game' => $game);
        $this->response->type('json');
        $this->response->body(json_encode($data));
    }

    public function admin_set_bulk() {
        $this->autoRender = false;

        $request = $this->request->query;
        $games = explode(",", $request['games']);

        if (!empty($request['value'])) {
            $value = $request['value'];
            if ($request['action'] == $this->IntGame->config['BulkActions']['enable'])
                $request['action'] = $this->IntGame->config['BulkActions']['disable'];
        } else {
            $value = null;
        }

        $response = array('status' => 'success');
        if (count($games) > 0 && $request['action']) {
            switch ($request['action']) {
                case $this->IntGame->config['BulkActions']['fun_play']:
                    $this->IntGame->setGamesforFun($games, $value);
                    break;
                case $this->IntGame->config['BulkActions']['enable']:
                    $this->IntGame->enableGames($games);
                    break;
                case $this->IntGame->config['BulkActions']['disable']:
                    $this->IntGame->disableGames($games);
                    break;
                case $this->IntGame->config['BulkActions']['category']:
                    if ($request['setid']) {
                        $this->IntGame->categorizeGames($games, $request['setid']);
                    }
                    break;
                case $this->IntGame->config['BulkActions']['brand']:
                    if ($request['setid']) {
                        $this->IntGame->setbrandGames($games, $request['setid']);
                    }
                    break;
                case $this->IntGame->config['BulkActions']['new']:
                    $this->IntGame->setnewGames($games, $value);
                    break;
                case $this->IntGame->config['BulkActions']['mobile']:
                    $this->IntGame->setmobileGames($games, $value);
                    break;
                case $this->IntGame->config['BulkActions']['desktop']:
                    $this->IntGame->setdesktopGames($games, $value);
                    break;
                case 'image':
                    if (!empty($request['source']) && !empty($this->request->data['image'])) {
                        $imgpath = $this->IntGame->getIntImagePath($request['source']);
                        $imagesUrls = $this->__uploadFiles($imgpath, array($this->request->data['image']));
                        if (array_key_exists('urls', $imagesUrls)) {
                            $path = $imgpath . $imagesUrls['urls'][0];
                            $this->IntGame->setGameImage($games, $path);
                            $response = array('status' => 'success', 'path' => $path);
                        } else {
                            $this->__setError($imagesUrls['errors'][0]);
                            $this->request->data['image'] = '';
                            $response = array('status' => 'error');
                        }
                    } else {
                        $response = array('status' => 'error', 'message' => __('No image found.'));
                    }
                    break;
                case 'order':
                    $order = $this->request->data['intorder'];
                    $this->log($this->request->data['intorder'], 'IntOrder');
                    if (!empty($order)) {
                        $this->IntGame->setGameOrder($games, $order);
                        $response = array('status' => 'success', 'order' => $order);
                    } else {
                        $response = array('status' => 'error', 'message' => __('Invalid or empty order value.'));
                    }
                    break;
            }
        }
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    public function admin_ordered_games($mobile) {

        $this->paginate = $this->IntGame->getGamesByDevice(1, 'desc', null, null, $mobile);
        $this->set('data', $this->paginate());
    }

    public function admin_ordered_games_desktop() {

        $this->paginate = $this->IntGame->getGamesByDevice(1, 'desc', null, null, false);
        $this->set('data', $this->paginate());
    }

    public function admin_ordered_games_mobile() {

        $this->paginate = $this->IntGame->getGamesByDevice(1, 'desc', null, null, true);
        $this->set('data', $this->paginate());
    }

    public function admin_reorder($id) {
        $this->autoRender = false;

        $posted_data = $this->request->data;
        if (!empty($posted_data)) {
            $i = count($posted_data);
            foreach ($posted_data as $keystring => $listdatastring) {
                $id = explode(":", $keystring)[1];
                $order = explode(":", $listdatastring[0])[1];

                if ($i != $order) {
                    echo "order Changed:" . $id . " went from " . $order . " to " . $i . ".\n";
                    $records_rearrange[$i] = $this->IntGame->getItem($id);
                }
                $i--;
            }

            foreach ($records_rearrange as $new_arrange => $record) {
                $record['IntGames']['order'] = $new_arrange;
                $this->IntGame->save($record);
            }

            $output['status'] = '1';
            echo json_encode($output);
        }
    }

    public function admin_transactions() {
        if (!empty($this->request->data['Transaction']['type'])) {
            $types = $this->request->data['Transaction']['type'];
            $this->set('selected_type', $this->request->data['Transaction']['type']);
        } else {
            $types = array('Bet', 'Win', 'Refund');
        }

        $this->Paginator->settings = array(
            'limit' => Configure::read('Settings.itemsPerPage'),
            'order' => 'transactionlog.date DESC',
            'conditions' => array('transactionlog.transaction_type' => $types)
        );

        if (!empty($this->request->data['Transaction']['user_id'])) {
            $selecteduser = $this->User->getItem($this->request->data['Transaction']['user_id']);
            $this->set('selected_username', $selecteduser['User']['username']);

            $this->Paginator->settings['conditions']['transactionlog.user_id'] = $this->request->data['Transaction']['user_id'];
        }

        if (!empty($this->request->data['Transaction']['from'])) {
            $this->Paginator->settings['conditions']['transactionlog.date >='] = $this->request->data['Transaction']['from'];
        }

        if (!empty($this->request->data['Transaction']['to'])) {
            $this->Paginator->settings['conditions']['transactionlog.date <='] = $this->request->data['Transaction']['to'];
        }

        if (!empty($this->request->data['Transaction']['amount'])) {
            $this->Paginator->settings['conditions']['abs(transactionlog.amount)'] = $this->request->data['Transaction']['amount'];
        }

        if (!empty($this->request->data['Transaction']['amount_from'])) {
            $this->Paginator->settings['conditions']['abs(transactionlog.amount) >='] = $this->request->data['Transaction']['amount_from'];
        }

        if (!empty($this->request->data['Transaction']['amount_to'])) {
            $this->Paginator->settings['conditions']['abs(transactionlog.amount) <='] = $this->request->data['Transaction']['amount_to'];
        }


        if (empty($this->request->data)) {
            if (empty($this->request->params['named']))
                $this->Session->write(__CLASS__ . '.' . __FUNCTION__ . '.' . 'SearchConditions', "");
            $conditions = $this->Session->read(__CLASS__ . '.' . __FUNCTION__ . '.' . 'SearchConditions');
            $this->Paginator->settings['conditions'] = $conditions;
        }

        $all = $this->Paginator->paginate('transactionlog');
        $this->Session->write(__CLASS__ . '.' . __FUNCTION__ . '.' . 'SearchConditions', $this->Paginator->settings['conditions']);

        $data = array();
        $countdata = array('bets' => 0, 'wins' => 0, 'refunds' => 0);
        if (!empty($all)) {
            foreach ($all as $row) {
                if (!$selecteduser)
                    $user = $this->User->getItem($row['transactionlog']['user_id']);
                $row['transactionlog']['username'] = $user['User']['username'];
                if ($row['transactionlog']['transaction_type'] == 'Bet') {
                    $countdata['bets'] ++;
                } else if ($row['transactionlog']['transaction_type'] == 'Win') {
                    $countdata['wins'] ++;
                } else if ($row['transactionlog']['transaction_type'] == 'Refund') {
                    $countdata['refunds'] ++;
                }
                $data[] = $row['transactionlog'];
            }
        }

        $this->set('users', true);
        $this->set('amounts', true);
        $this->set('types', array('Bet' => 'Bet', 'Win' => 'Win', 'Refund' => 'Refund'));
        $this->set(compact('data', 'countdata'));
    }

    public function admin_getgameactivity($user_id) {

        if ($this->request->data) {
//            $from = $this->request->data['IntGameActivity']['from'];
//            $to = $this->request->data['IntGameActivity']['to'];
//            $ismobile = $this->request->data['IntGameActivity']['ismobile'];
//            $fun = $this->request->data['IntGameActivity']['fun'];

            $from = $this->request->data['IntGame']['from'];
            $to = $this->request->data['IntGame']['to'];
            $ismobile = $this->request->data['IntGame']['ismobile'];
            $fun = $this->request->data['IntGame']['fun'];
        } else {
            $ismobile = -1;
            $fun = -1;
        }
        $this->set('user_id', $user_id);
        $this->set('model', 'IntGame');
        //$this->set('search_fields', $this->IntGameActivity->getSearch());
        $this->set('data', $this->IntGameActivity->gameActivitybyUser($user_id, $from, $to, $ismobile, $fun));
    }

//    public function generate_img_name($string) {
//        $replace = array('Android', 'Mobile', 'Windows Phone'); //specific for Betsoft
//        return strtolower(preg_replace('/^[\W_]+|[\W_]+$/', '', str_replace(" ", "_", preg_replace('/  +/', ' ', preg_replace("/[^a-zA-Z0-9\s]/", " ", str_replace($replace, "", str_replace("'", "", $string)))))));
//    }
//
//    public function add_provider_game($request) {
//
//        $image_folder = '/plugins/' . strtolower($request['provider']) . '/img/';
//        $image_name = $this->generate_img_name($request['name']);
//        $image_path = $image_folder . $image_name . '.jpg';
//        $table = $request['provider'] . 'Games';
//
//        $request['free_spins'] = $request['free_spins'] == 1 ? $request['free_spins'] : 0;
//        $request['branded'] = $request['branded'] == 1 ? $request['branded'] : 0;
//        $request['jackpot'] = $request['jackpot'] == 1 ? $request['jackpot'] : 0;
//        $request['desktop'] = $request['desktop'] == 1 ? $request['desktop'] : 0;
//        $request['mobile'] = $request['mobile'] == 1 ? $request['mobile'] : 0;
//        $request['fun_play'] = $request['fun_play'] == 1 ? $request['fun_play'] : 0;
//
//        $request['pay_lines'] = $request['pay_lines'] != 0 ? $request['pay)lines'] : 0;
//        $request['reels'] = $request['reels'] != 0 ? $request['reels'] : 0;
//        $request['game_key'] = $request['game_key'] ? $request['game_key'] : 0;
//
//        $sql = "INSERT INTO `" . $table . "` "
//                . "(`game_id`, `game_key`, `name`, `category_id`, `type`, "
//                . "`pay_lines`, `reels`, `free_spins`, `image`, "
//                . "`branded`, `jackpot`, `desktop`, `mobile`, `fun_play`, `new`, `active`) "
//                . "VALUES "
//                . "('" . $request['game_id'] . "', '" . $request['game_key'] . "', '" . $request['name'] . "'," . $request['category_id'] . ", 'Flash/HTML', "
//                . $request['pay_lines'] . "," . $request['reels'] . "," . $request['free_spins'] . ", '" . $image_path . "', "
//                . $request['branded'] . ", " . $request['jackpot'] . ", " . $request['desktop'] . "," . $request['mobile'] . "," . $request['fun_play'] . ", 1, 1)";
//
//        var_dump($sql);
//
//        //$this->query($sql);
//    }
//    public function get_max_order() {
//        $sql = "SELECT MAX(`order`) FROM `int_games`";
//        return $this->query($sql);
//    }
    public function admin_add_game() {

        $this->layout = 'admin';
        try {

            $this->loadModel('IntGames.IntGame');
            $this->set('game_providers', $this->IntBrand->getActiveBrands());
            $this->set('categories', $this->IntCategory->getCategories());
            $this->set('platforms', $this->IntGame->config['Platforms']);

//            var_dump($this->request->data);
            if ($this->request->data) {
                $request = $this->request->data;
                $this->IntGame->add_game($request);
            }
        } catch (Exeption $e) {
            $this->__setError(__($e->getMessage(), true));
        }
    }

    public function admin_add_free_spins() {
        //$this->autoRender = false;

        $this->layout = 'admin';
        try {

            $games = $this->IntGame->getFreeSpinsGames();
            $users = $this->User->find('all', array('conditions' => array('User.group_id' => 1, 'User.status' => 1)));


            //var_dump($users);

            $this->set('games', $games);
            $this->set('users', $users);
        } catch (Exeption $e) {
            $this->__setError(__($e->getMessage(), true));
        }
    }

    public function admin_index() {
        $this->layout = 'admin';
        $data = array();
        $categories = $this->IntCategory->getCategories();
        $brands = $this->IntBrand->getActive();



        if (!empty($this->request->data)) {
            foreach ($this->request->data['IntGame'] as $key => $value) {
                //var_dump($key);
                //var_dump($value);
                if (empty($value))
                    continue;
                if ($key == 'category_id') {
                    $conditions[] = array('IntGame.category_id' => $value);
                    continue;
                }
                if ($key == 'brand_id') {
                    $conditions[] = array('IntGame.brand_id' => $value);
                    continue;
                }
                if ($key == 'new') {
                    $conditions[] = array('IntGame.new' => $value);
                    continue;
                }
                if ($key == 'mobile') {
                    $conditions[] = array('IntGame.mobile' => $value);
                    continue;
                }
                if ($key == 'desktop') {
                    $conditions[] = array('IntGame.fun_play' => $value);
                    continue;
                }
                if ($key == 'featured') {
                    $conditions[] = array('IntGame.featured' => $value);
                    continue;
                }
                if ($key == 'jackpot') {
                    $conditions[] = array('IntGame.jackpot' => $value);
                    continue;
                }
                if ($key == 'fun_play') {
                    $conditions[] = array('IntGame.fun_play' => $value);
                    continue;
                }
                if ($key == 'free_spins') {
                    $conditions[] = array('IntGame.free_spins' => $value);
                    continue;
                }


                //search words by using first letters and then *
                if ($key == 'name' && strpos($value, "*") !== FALSE) {

                    $value = str_replace("*", "%", $value);
                    $value = "%" . $value;
                    $conditions = array('IntGame.' . $key . ' LIKE' => $value);
                } else {
                    $conditions['IntGame.' . $key] = $value;
                }
            }
        }
        //var_dump($conditions);

        if (!empty($conditions))
            $this->paginate['conditions'] = $conditions;

        $this->paginate = $this->IntGame->getIndex();
        $this->paginate['recursive'] = 1;
        $this->paginate['order'] = array('IntGame.order' => 'DESC');
        $this->paginate['contain'] = array('IntGames.IntBrand', 'IntGames.IntCategory');

        $data = $this->paginate();


        foreach ($data as &$game) {

            $category = $this->IntCategory->getItem($game['IntGame']['category_id']);
            $game['IntCategory'] = $category['IntCategory'];
            $brand = $this->IntBrand->getItem($game['IntGame']['brand_id']);
            $game['IntBrand'] = $brand['IntBrand'];
        }
        //var_dump($data);
        $this->set('data', $data);
        $this->set('categories', $categories);
        $this->set('brands', $brands);
        $this->set('actions', $this->IntGame->getActions());
    }

    public function admin_view($id) {
        parent::admin_view(array('IntGame.id' => $id), 'IntGame');

        $options['conditions'] = array('IntGame.id' => $id);
        $options['recursive'] = 1;
        $data = $this->IntGame->find('first', $options);
        //var_dump($data);
        $this->set('model', 'IntGame');
        $this->set('fields', $data);
    }

    //list of games for the admin panel
    public function admin_index_old() {
        $this->layout = 'admin';
        $data = array();

        //$this->loadModel('IntGames.IntGame');
        //$categories = $this->IntCategory->find('list');
        //$brands = $this->IntBrand->find('list');
        //var_dump($categories);
        //$categoriesRec = $this->IntCategory->find('all', array('recursive' => -1, 'fields' => array('id', 'name')));


        $categoriesFilter = $this->IntGame->getAllCategories();
        $undefined = array(array('IntCategory' => array('id' => 'undefined', 'name' => 'Undefined')));
        $data = array_merge($categoriesFilter, $undefined);

        $categories = $this->IntGame->getAllCategories();
        $brands = $this->IntGame->getAllBrands();

        $plugins = $this->IntGame->getAllPlugins();

        $aggregators = array();
        foreach ($plugins as $row) {
            $aggregators[] = array(
                'name' => $row['IntPlugin']['model'],
                'model' => $row['IntPlugin']['model'] . '.' . $row['IntPlugin']['games_model'],
                'image_start_path' => $row['IntPlugin']['image_start_path'],
                'style' => $row['IntPlugin']['style']
            );
        }
        $this->set('configactions', $this->IntGame->config['BulkActions']);
        $this->set((compact('data', 'categories', 'brands', 'aggregators')));
    }

    //used in int_games.js for getting the games from a category
    public function admin_get_games($category_id) {
//        var_dump($category_id);
        $data = $this->IntGame->find('all', array('conditions' => array('category_id' => (($category_id != 'undefined') ? $category_id : "")), 'recursive' => 1));
        $this->log($category_id);
//        $data = $this->IntGame->find('all');
        $this->log($data);

        //$this->set('allints', $this->IntGame->getIntStyles());
        //$this->set('brands', $this->IntBrand->find('list'));
        $this->set('configactions', $this->IntGame->config['BulkActions']);
        $this->set(compact('data', 'category_id'));
    }

    public function admin_force_gamelists() {
        $this->autoRender = false;
        $type = $this->request->query['type'];

        $games = $this->IntGame->getGamesList($type);

        $query = "INSERT IGNORE INTO int_games (name, `pay_lines`, reels, free_spins, source, source_id, image, mobile, desktop) VALUES ";

        $countkeys = 0;
        foreach ($games as $key => $game) {
            $countkeys++;
            $countgame = 0;
            foreach ($game as $row) {
                /* this has to be checked  - may needs improvement */
                $row['image'] = $this->IntGame->getIntImagePath($row['source']) . $row['image'];
                $countgame++;

                if (empty($row['image']))
                    $row['image'] = null;
                $query .= "(\"" . $row['name'] . "\", '" . $row['pay_lines'] . "', '" . $row['reels'] . "', '" . $row['free_spins'] . "', '" . $row['source'] . "', '" . $row['source_id'] . "', '" . $row['image'] . "', '" . $row['mobile'] . "', '" . $row['desktop'] . "')";
                if ($countgame < count($game))
                    $query .= ", ";
            }
            if ($countkeys < count($games))
                $query .= ", ";
        }
        $data = array();
        $this->IntGame->query($query);

        $this->__setMessage(($type ? explode('.', $type)[0] : __('All')) . ' ' . __('Games were updated successfully.'));

        $this->response->type('json');
        $this->response->body(json_encode(array('status' => 'success')));
    }

    public function admin_toggleActive($game_id) {
        $this->autoRender = false;

        $game = $this->IntGame->find('first', array('conditions' => array('IntGame.id' => $game_id)));
        $game['IntGame']['active'] = !$game['IntGame']['active'];
        $this->IntGame->save($game);
    }

}
