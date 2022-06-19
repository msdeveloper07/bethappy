<?php

App::uses('HttpSocket', 'Network/Http');

class IntGame extends IntGamesAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'IntGame';
    protected $_schema = array(
        'id' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'name' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'category_id' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'brand_id' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'pay_lines' => array(
            'type' => 'int',
            'null' => true,
            'length' => 11
        ),
        'reels' => array(
            'type' => 'int',
            'null' => true,
            'length' => 11
        ),
        'rtp' => array(
            'type' => 'int',
            'null' => true,
            'length' => 11
        ),
        'volatility' => array(
            'type' => 'string',
            'null' => true,
            'length' => 50
        ),
        'free_spins' => array(
            'type' => 'int',
            'null' => true,
            'length' => 11
        ),
        'fun_play' => array(
            'type' => 'int',
            'null' => true,
            'length' => 11
        ),
        'source' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'source_id' => array(
            'type' => 'string',
            'null' => false,
            'length' => 255
        ),
        'order' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'image' => array(
            'type' => 'string',
            'null' => true,
            'length' => 255
        ),
        'new' => array(
            'type' => 'int',
            'null' => false,
            'length' => 4
        ),
        'jackpot' => array(
            'type' => 'int',
            'null' => false,
            'length' => 4
        ),
        'featured' => array(
            'type' => 'int',
            'null' => false,
            'length' => 4
        ),
        'mobile' => array(
            'type' => 'int',
            'null' => false,
            'length' => 4
        ),
        'desktop' => array(
            'type' => 'int',
            'null' => false,
            'length' => 4
        ),
        'open_stats' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'active' => array(
            'type' => 'int',
            'null' => false,
            'length' => 4
        ),
    );

    /**
     * @var type 
     */
    public $config = array();
//    const BlueOcean = 'blueocean';
//    const Playson = 'playson';
//    const Tomhorn = 'tomhorn';
//    const MrSlotty = 'mrslotty';
//    const Ezugi = 'ezugi';
//    const games_Netent = 'Blueocean.NetentGames';
//    const games_Microgaming = 'Blueocean.MicrogamingGames';
//    const games_Playson = 'Playson.PlaysonGameList';
//    const games_Tomhorn = 'Tomhorn.TomhornGameList';
//    const games_MrSlotty = 'MrSlotty.SlotGames';
//    const games_Ezugi = 'Livecasino.EzugiGamelobby';

    public static $bulkActions = array(
        'enable' => 'Enable',
        'disable' => 'Disable',
        'category' => 'Set Category',
        'brand' => 'Set Brand',
        'new' => 'Set New',
        'mobile' => 'Enable for mobile',
        'desktop' => 'Enable for desktop'
    );

    /**
     * db table name
     * @var type 
     */
    public $useTable = 'int_games';
    public $belongsTo = array(
        'IntCategory' => array('className' => 'IntCategory', 'foreignKey' => 'category_id'),
        'IntBrand' => array('className' => 'IntBrand', 'foreignKey' => 'brand_id')
    );

    function __construct() {
        parent::__construct($id, $table, $ds);
        Configure::load('IntGames.' . $this->name);

        if (Configure::read($this->name . '.Config') == 0)
            throw new Exception('Config not found', 500);
        $this->config = Configure::read($this->name . '.Config');

        $this->IntBrand = ClassRegistry::init('IntGames.IntBrand');
        $this->IntCategory = ClassRegistry::init('IntGames.IntCategory');
        $this->IntGame = ClassRegistry::init('IntGames.IntGame');
        $this->IntPlugin = ClassRegistry::init('IntGames.IntPlugin');
        $this->User = ClassRegistry::init('User');

        //$this->BlueOceanGames = ClassRegistry::init('Games.BlueOceanGames');
    }

    //for front-end used
    function getGames() {
        $games = $this->query("SELECT * FROM `int_games` as `IntGame`
INNER JOIN `int_brands` as `IntBrand` ON `IntGame`.`brand_id` = `IntBrand`.`id`
INNER JOIN `int_categories` as `IntCategory` ON `IntGame`.`category_id` = `IntCategory`.`id`
WHERE 
`IntGame`.`active` = 1 ORDER BY `IntGame`.`order` DESC");
        return $games;
    }

    function getGamesByCategoryId($category_id) {
        $games = $this->query("SELECT * FROM `int_games` as `IntGame`
INNER JOIN `int_brands` as `IntBrand` ON `IntGame`.`brand_id` = `IntBrand`.`id`
INNER JOIN `int_categories` as `IntCategory` ON `IntGame`.`category_id` = `IntCategory`.`id`
WHERE `IntGame`.`category_id` = " . $category_id . " AND
`IntGame`.`active` = 1 ORDER BY `IntGame`.`order` DESC");

        return $games;
    }

    function getGamesByBrandId($brand_id) {
        $games = $this->query("SELECT * FROM `int_games` as `IntGame`
INNER JOIN `int_brands` as `IntBrand` ON `IntGame`.`brand_id` = `IntBrand`.`id`
INNER JOIN `int_categories` as `IntCategory` ON `IntGame`.`category_id` = `IntCategory`.`id`
WHERE `IntGame`.`brand_id` = " . $brand_id . " AND
`IntGame`.`active` = 1 ORDER BY `IntGame`.`order` DESC");

        return $games;
    }

    function getFreeSpinsGames() {
        $games = $this->query("SELECT * FROM `int_games` as `IntGame`
INNER JOIN `int_brands` as `IntBrand` ON `IntGame`.`brand_id` = `IntBrand`.`id`
INNER JOIN `int_categories` as `IntCategory` ON `IntGame`.`category_id` = `IntCategory`.`id`
WHERE `IntGame`.`free_spins` = 1 AND
`IntGame`.`active` = 1 ORDER BY `IntGame`.`order` DESC");

        return $games;
    }

    function getGamesBySource($source) {
        $games = $this->query("SELECT * FROM `int_games` as `IntGame`
INNER JOIN `int_brands` as `IntBrand` ON `IntGame`.`brand_id` = `IntBrand`.`id`
INNER JOIN `int_categories` as `IntCategory` ON `IntGame`.`category_id` = `IntCategory`.`id`
WHERE `IntGame`.`source` = '" . $source . "' AND
`IntGame`.`active` = 1 ORDER BY `IntGame`.`order` DESC");

        return $games;
    }

    public function getPagination($options = array()) {

        $options['recursive'] = 1;
        $pagination = array(
            'limit' => Configure::read('Settings.itemsPerPage'),
            'order' => array('IntGame.order' => 'DESC'),
            'recursive' => 1
        );

        if (!empty($options)) {
            $pagination['conditions'] = $options['conditions'];
        }

        return $pagination;
    }

    public function getIndex() {

//        $options['order'] = array('IntGame.order' => 'DESC');
//        $options['limit'] = Configure::read('Settings.itemsPerPage');
//        $options['recursive'] = 1;
//        $options['fields'] = 
        return array(
            'IntGame.name',
            'IntGame.active' => $this->getFieldHtmlConfig('switch', array('label' => __('Active'))),
            'IntGame.desktop' => $this->getFieldHtmlConfig('switch', array('label' => __('Desktop'))),
            'IntGame.mobile' => $this->getFieldHtmlConfig('switch', array('label' => __('Mobile'))),
            'IntGame.jackpot' => $this->getFieldHtmlConfig('switch', array('label' => __('Jackpot'))),
            'IntGame.fun_play' => $this->getFieldHtmlConfig('switch', array('label' => __('Fun Play'))),
            'IntGame.free_spins' => $this->getFieldHtmlConfig('switch', array('label' => __('Free Spins'))),
            'IntGame.featured' => $this->getFieldHtmlConfig('switch', array('label' => __('Featured'))),
            'IntGame.new' => $this->getFieldHtmlConfig('switch', array('label' => __('New'))),
            'IntGame.game_hash',
            'IntGame.source_id',
        );
        //return $options;
    }

    public function getView() {
        //$Categories = ClassRegistry::init('IntGames.IntCategory');
        return array(
            'IntGame.name',
            //'IntGame.image' => array('type' => 'file'),
            'IntGame.brand_id' => $this->getFieldHtmlConfig('select', array('options' => $this->IntBrand->getActive())),
            'IntGame.category_id' => $this->getFieldHtmlConfig('select', array('options' => $this->IntCategory->getCategories())),
            'IntGame.pay_lines',
            'IntGame.reels',
            'IntGame.rtp',
            'IntGame.volatility',
            'IntGame.game_hash',
            'IntGame.source_id',
            'IntGame.order',
            'IntGame.active' => $this->getFieldHtmlConfig('switch', array('label' => __('Active'))),
            'IntGame.desktop' => $this->getFieldHtmlConfig('switch', array('label' => __('Desktop'))),
            'IntGame.mobile' => $this->getFieldHtmlConfig('switch', array('label' => __('Mobile'))),
            'IntGame.jackpot' => $this->getFieldHtmlConfig('switch', array('label' => __('Jackpot'))),
            'IntGame.fun_play' => $this->getFieldHtmlConfig('switch', array('label' => __('Fun Play'))),
            'IntGame.free_spins' => $this->getFieldHtmlConfig('switch', array('label' => __('Free Spins'))),
            'IntGame.featured' => $this->getFieldHtmlConfig('switch', array('label' => __('Featured'))),
            'IntGame.new' => $this->getFieldHtmlConfig('switch', array('label' => __('New'))),
        );
    }

    public function getEdit() {
//        $Brands = ClassRegistry::init('IntGames.IntBrand');
        //$Categories = ClassRegistry::init('IntGames.IntCategory');
        //var_dump($this->IntCategory);
        return array(
            'IntGame.name',
            'IntGame.image' => array('type' => 'file'),
            'IntGame.brand_id' => $this->getFieldHtmlConfig('select', array('options' => $this->IntBrand->getActive())),
            'IntGame.category_id' => $this->getFieldHtmlConfig('select', array('options' => $this->IntCategory->getCategories())),
            'IntGame.pay_lines' => array('type' => 'number'),
            'IntGame.reels' => array('type' => 'number'),
            'IntGame.rtp' => array('type' => 'number'),
            'IntGame.volatility' => array('type' => 'text'),
            'IntGame.game_hash' => array('type' => 'text'),
            'IntGame.source_id' => array('type' => 'text'),
            'IntGame.order' => array('type' => 'number'),
            'IntGame.desktop' => $this->getFieldHtmlConfig('switch', array('label' => __('Desktop'))),
            'IntGame.mobile' => $this->getFieldHtmlConfig('switch', array('label' => __('Mobile'))),
            'IntGame.jackpot' => $this->getFieldHtmlConfig('switch', array('label' => __('Jackpot'))),
            'IntGame.fun_play' => $this->getFieldHtmlConfig('switch', array('label' => __('Fun Play'))),
            'IntGame.free_spins' => $this->getFieldHtmlConfig('switch', array('label' => __('Free Spins'))),
            'IntGame.featured' => $this->getFieldHtmlConfig('switch', array('label' => __('Featured'))),
            'IntGame.new' => $this->getFieldHtmlConfig('switch', array('label' => __('New'))),
            'IntGame.active' => $this->getFieldHtmlConfig('switch', array('label' => __('Active'))),
        );
    }

    public function getAdd() {
        //        $Brands = ClassRegistry::init('IntGames.IntBrand');
        //$Categories = ClassRegistry::init('IntGames.IntCategory');
        //var_dump($this->IntCategory);
        return array(
            'IntGame.name',
            'IntGame.image' => array('type' => 'file'),
            'IntGame.brand_id' => $this->getFieldHtmlConfig('select', array('options' => $this->IntBrand->getActive())),
            'IntGame.category_id' => $this->getFieldHtmlConfig('select', array('options' => $this->IntCategory->getCategories())),
            'IntGame.pay_lines' => array('type' => 'number'),
            'IntGame.reels' => array('type' => 'number'),
            'IntGame.rtp' => array('type' => 'number'),
            'IntGame.volatility' => array('type' => 'text'),
            'IntGame.game_hash' => array('type' => 'text'),
            'IntGame.source_id' => array('type' => 'text'),
            'IntGame.order' => array('type' => 'number'),
            'IntGame.desktop' => $this->getFieldHtmlConfig('switch', array('label' => __('Desktop'))),
            'IntGame.mobile' => $this->getFieldHtmlConfig('switch', array('label' => __('Mobile'))),
            'IntGame.jackpot' => $this->getFieldHtmlConfig('switch', array('label' => __('Jackpot'))),
            'IntGame.fun_play' => $this->getFieldHtmlConfig('switch', array('label' => __('Fun Play'))),
            'IntGame.free_spins' => $this->getFieldHtmlConfig('switch', array('label' => __('Free Spins'))),
            'IntGame.featured' => $this->getFieldHtmlConfig('switch', array('label' => __('Featured'))),
            'IntGame.new' => $this->getFieldHtmlConfig('switch', array('label' => __('New'))),
            'IntGame.active' => $this->getFieldHtmlConfig('switch', array('label' => __('Active'))),
        );
    }

//getAllInts

    function getAllPlugins() {
        $plugins = $this->query("select * from int_plugins as IntPlugin");
        return $plugins;
        //return $this->query("select * from int_plugins as AllInts");
    }

    function getAllCategories() {
        $categories = $this->query("select * from int_categories as IntCategory");
        return $categories;
        //return $this->query("select * from int_plugins as AllInts");
    }

    function getAllBrands() {
        $brands = $this->query("select * from int_brands as IntBrand");
        return $brands;
        //return $this->query("select * from int_plugins as AllInts");
    }

    function getIntImagePath($model) {
        $model = explode(".", $model);
        return $this->query('select image_start_path from int_plugins as AllInts where model = "' . $model[0] . '" and games_model = "' . $model[1] . '"')[0]['AllInts']['image_start_path'];
    }

    function getIntStyles() {
        $data = $this->getAllInts();
        var_dump($data);
        $styles = array();
        foreach ($data as $row) {
            $styles[$row['AllInts']['model'] . '.' . $row['AllInts']['games_model']] = $row['AllInts']['style'];
        }
        return $styles;
    }

    /**
     * if player country is is the restricted list do not show the games from that provider
     * @return type array
     */
    public function restrictGames($user_id) {
        $restricted = $this->config['Restricted'];
        $user = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
        $providers = array();
        foreach ($restricted as $key => $value) {
            if (in_array($user['User']['country'], $restricted[$key])) {
                $provider = $this->IntBrand->getBySlug($key);
                $providers[] = $provider;
            }
        }
        return array(
            'NOT' => array(
                array('IntGame.brand_id' => $providers)
            )
        );
    }

//was getGames
    function getGamesByDevice($active, $order = 'desc', $categoryslug = null, $brandslug = null, $is_mobile = false) {
        //$user_id = CakeSession::read('Auth.User.id');
//        if ($user_id)
//            $options['conditions'] = $this->restrictGames($user_id);

        if ($active) {
            $options['conditions']['IntGame.active'] = $active;
        }
        if ($categoryslug)
            $options['conditions']['IntCategory.slug'] = $categoryslug;
        if ($brandslug)
            $options['conditions']['IntBrand.slug'] = $brandslug;

        if ($is_mobile) {
            $options['conditions']['IntGame.mobile'] = true;
        } else {
            $options['conditions']['IntGame.desktop'] = true;
        }
        if ($order == 'asc') {
            $options['IntGame.order'] = array('order ASC');
        } else {
            $options['IntGame.order'] = array('order DESC');
        }

        $options['conditions']['IntBrand.active'] = 1; //this will remove inactive providers without activating/deactivating individual games

        $options['recursive'] = 1;

        $options['limit'] = Configure::read('Settings.itemsPerPage');

        return $this->find('all', $options);
    }

    function getSourceGame($parentid, $source) {
        $model = ClassRegistry::init($source);
        $data = $model->getItem($parentid);
        return $data[$model->name];
    }

    function getHeaders($source) {
        $model = ClassRegistry::init($source);
        return $model->getHeaders();
    }

    function getGamesList($gametype) {
        $games = array();
        if ($gametype) {
            $model = ClassRegistry::init($gametype);
            $games[$model->name] = $model->getIntGames();
        } else {
            foreach ($this->getAllInts() as $int) {
                $model = ClassRegistry::init($int['AllInts']['model'] . '.' . $int['AllInts']['games_model']);
                $games[$model->name] = $model->getIntGames();
            }
        }
        return $games;
    }

    function setGamesforFun($games, $value) {
        if (count($games) > 0) {
            if ($value) {
                $value = 0;
            } else {
                $value = 1;
            }

            $query = "UPDATE int_games SET fun_play = {$value} WHERE " . ((count($games) == 1) ? "id={$games[0]}" : "id in (" . implode(',', $games) . ")");
            return $this->query($query);
        }
        return false;
    }

    function enableGames($games) {
        if (count($games) > 0) {
            $query = "UPDATE int_games SET active = 1 WHERE " . ((count($games) == 1) ? "id={$games[0]}" : "id in (" . implode(',', $games) . ")");
            return $this->query($query);
        }
        return false;
    }

    function disableGames($games) {
        if (count($games) > 0) {
            $query = "UPDATE int_games SET active = 0 WHERE " . ((count($games) == 1) ? "id={$games[0]}" : "id in (" . implode(',', $games) . ")");
            return $this->query($query);
        }
        return false;
    }

    function categorizeGames($games, $catId) {
        if (count($games) > 0 && $catId) {
            $query = "UPDATE int_games SET category_id = {$catId} WHERE " . ((count($games) == 1) ? "id={$games[0]}" : "id in (" . implode(',', $games) . ")");
            return $this->query($query);
        }
        return false;
    }

    function setbrandGames($games, $brandId) {
        if (count($games) > 0 && $brandId) {
            $query = "UPDATE int_games SET brand_id = {$brandId} WHERE " . ((count($games) == 1) ? "id={$games[0]}" : "id in (" . implode(',', $games) . ")");
            return $this->query($query);
        }
        return false;
    }

    function setnewGames($games, $value) {
        if (count($games) > 0) {
            if ($value) {
                $value = 0;
            } else {
                $value = 1;
            }

            $query = "UPDATE int_games SET new = {$value} WHERE " . ((count($games) == 1) ? "id={$games[0]}" : "id in (" . implode(',', $games) . ")");
            return $this->query($query);
        }
        return false;
    }

    function setmobileGames($games, $value) {
        if (count($games) > 0) {
            if ($value) {
                $value = 0;
            } else {
                $value = 1;
            }

            $query = "UPDATE int_games SET mobile = {$value} WHERE " . ((count($games) == 1) ? "id={$games[0]}" : "id in (" . implode(',', $games) . ")");
            return $this->query($query);
        }
        return false;
    }

    function setdesktopGames($games, $value) {
        if (count($games) > 0) {
            if ($value) {
                $value = 0;
            } else {
                $value = 1;
            }

            $query = "UPDATE int_games SET desktop = {$value} WHERE " . ((count($games) == 1) ? "id={$games[0]}" : "id in (" . implode(',', $games) . ")");
            return $this->query($query);
        }
        return false;
    }

    function setGameImage($games, $imgpath) {
        $query = "UPDATE int_games SET image = '{$imgpath}' WHERE " . ((count($games) == 1) ? "id={$games[0]}" : "id in (" . implode(',', $games) . ")");
        return $this->query($query);
    }

    public function setGameOrder($games, $order) {
        $query = "UPDATE `int_games` SET `order` = {$order} WHERE " . ((count($games) == 1) ? "`id`={$games[0]}" : "`id` in (" . implode(',', $games) . ")");
        $this->log($query, 'IntOrder');
        return $this->query($query);
    }

    function addOpenStats($id) {
        $game = $this->getItem($id);
        $game['IntGame']['open_stats'] ++;
        $this->save($game);
    }

    //manipulating provider games functions


    public function getSourceHeaders() {
        return array(
            'id' => __('ID'),
            'game_id' => __('Game ID'),
            'game_key' => __('Game Key'),
            'name' => __('Name'),
            'category' => __('Category'),
            'type' => __('Type'),
            'pay_lines' => __('Paylines'),
            'reels' => __('Reels'),
            'free_spins' => __('Free spins'),
            'image' => __('Image'),
            'branded' => __('Branded'),
            'mobile' => __('Mobile'),
            'desktop' => __('Desktop'),
            'fun_play' => __('Play for fun'),
            'new' => __('New'),
            'active' => __('Active')
        );
    }

//get all data for a launched game
    function getGame($game_id) {
        return $this->find('first', array('conditions' => array('IntGame.id' => $game_id), 'recursive' => 1));
    }

    public function generate_img_name($string) {
        $replace = array('Android', 'Mobile', 'Windows Phone'); //specific for Betsoft
        return strtolower(preg_replace('/^[\W_]+|[\W_]+$/', '', str_replace(" ", "_", preg_replace('/  +/', ' ', preg_replace("/[^a-zA-Z0-9\s]/", " ", str_replace($replace, "", str_replace("'", "", $string)))))));
    }

    public function add_game($request) {

        $image_folder = '/plugins/' . strtolower($request['provider']) . '/img/';
        $image_name = $this->generate_img_name($request['name']);
        $image_path = $image_folder . $image_name . '.jpg';
        $table = $request['provider'] . 'Games';


        $request['free_spins'] = (int) $request['free_spins'] == 1 ? $request['free_spins'] : 0;
        $request['branded'] = (int) $request['branded'] == 1 ? $request['branded'] : 0;
        $request['jackpot'] = (int) $request['jackpot'] == 1 ? $request['jackpot'] : 0;
        $request['desktop'] = (int) $request['desktop'] == 1 ? $request['desktop'] : 0;
        $request['mobile'] = (int) $request['mobile'] == 1 ? $request['mobile'] : 0;
        $request['fun_play'] = (int) $request['fun_play'] == 1 ? $request['fun_play'] : 0;
        $request['pay_lines'] = (int) $request['pay_lines'] != 0 ? $request['pay_lines'] : 0;
        $request['reels'] = (int) $request['reels'] != 0 ? $request['reels'] : 0;
        $request['game_key'] = $request['game_key'] ? $request['game_key'] : 0;

        $request['rtp'] = $request['rtp'];
        $request['volatility'] = $request['volatility'];

        $sql = "INSERT INTO `" . $table . "` "
                . "(`game_id`, `game_key`, `name`, `category_id`, `type`, "
                . "`rtp`, `volatility`, `pay_lines`, `reels`, `free_spins`, `image`, "
                . "`branded`, `jackpot`, `desktop`, `mobile`, `fun_play`, `new`, `active`) "
                . "VALUES "
                . "('" . $request['game_id'] . "', '" . $request['game_key'] . "', '" . $request['name'] . "', " . $request['category_id'] . ", 'Flash/HTML', "
                . $request['rtp'] . ", " . $request['volatility'] . ", " . $request['pay_lines'] . ", " . $request['reels'] . "," . $request['free_spins'] . ", '" . $image_path . "', "
                . $request['branded'] . ", " . $request['jackpot'] . ", " . $request['desktop'] . ", " . $request['mobile'] . ", " . $request['fun_play'] . ", 1, 1)";

//        var_dump($sql);
        //$this->query($sql);
    }

    function get_max_order() {
        $sql = "SELECT MAX(`order`) as max_order FROM `int_games`";
        $max_order = $this->query($sql);
        return (int) $max_order[0][0]['max_order'];
    }

    public function disableSourceGames($source) {
        try {
            $sql = "UPDATE `int_games` SET `active` = 0 WHERE `source` = '" . $source . "'";
            $this->query($sql);
            return true;
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    public function enableSourceGame($source_id) {
        if (!empty($source_id)) {
            $options['conditions'] = array('IntGame.source_id' => $source_id);
            $game = $this->find('first', $options);
            if (!empty($game)) {
                $game['IntGame']['active'] = 1;
                if ($this->save($game))
                    return true;

                return false;
            }
            return false;
        }
        return false;
    }

    public function gameSourceExists($source, $source_id, $name) {
        try {
            $sql = "SELECT * FROM `int_games` WHERE source = '" . $source . "' "
                    . "AND `source_id` ='" . $source_id . "' "
                    . "AND `name` ='" . addslashes($name) . "';"; //need to avoid ' errors

            $game = $this->query($sql);
//            var_dump($game);
            if (!empty($game))
                return true;

            return false;
        } catch (Exception $e) {
            echo 'Error: ', $e->getMessage();
        }
    }

    public function addSourceGame($game, $source) {
        $game = json_decode($game);

        $data['source'] = $source;
        $data['source_id'] = (string) $game->game_id;
        $data['game_hash'] = $game->game_hash;
        $data['name'] = $game->name;
        $data['category_id'] = $game->category_id;
        $data['brand_id'] = $game->brand_id;
        $data['rtp'] = $game->rtp;
        $data['volatility'] = $game->volatility;
        $data['pay_lines'] = $game->pay_lines;
        $data['reels'] = $game->reels;
        $data['jackpot'] = $game->jackpot == true ? 1 : 0;
        $data['free_spins'] = $game->free_spins == true ? 1 : 0;
        $data['image'] = $game->image;
        $data['mobile'] = $game->mobile == true ? 1 : 0;
        $data['desktop'] = $game->desktop == true ? 1 : 0;
        $data['fun_play'] = $game->fun_play == true ? 1 : 0;
        $data['new'] = $game->new == true ? 1 : 0;
        $data['active'] = $game->active == true ? 1 : 0;
        $data['created'] = $this->__getSqlDate();
        if (empty($game->order)) {
            $data['order'] = $this->get_max_order() + 1;
        } else {
            $data['order'] = $game->order;
        }
//
//
//        $target = '';
//        if ($game->mobile && !$game->desktop) {
//            $target = 'mobile';
//        } elseif (!$game->mobile && $game->desktop) {
//            $target = 'desktop';
//        } else {
//            $target = 'desktop and mobile';
//        }
        //var_dump($data);

        $this->create();
        $this->save($data);
        //$this->Alert = ClassRegistry::init('Alert');
//        createAlert($user_id, $source, $model = null, $text, $date)
        //$this->Alert->createAlert(555, 'IntGames', $source, 'New ' . $target . ' game has been added. Provider: ' . $source . '. Game name: ' . $game->name . '.', $this->__getSqlDate());
    }

    //do not delete, original
//    function getUrl($game_id, $fun_play = false) {
//        
//        if ($game = $this->getItem($game_id)) {
//            if ($game['IntGames']['active'] == 0)
//                return array('message' => __("You are not allowed to play this game."));
//            if ($game['IntGames']['fun_play'] == 0 && $fun)
//                return array('message' => __("You can't play this game for fun."));
//
//            $gameModel = ClassRegistry::init('Games.' . $game['IntGames']['source']);
//        
//     
//            $getURl = $gameModel->getGameUrl($game['IntGames']['source_id'], $fun_play);
//
//
//            if ($getURl['response'] == 1) {
//                if ($getURl['content'])
//                    return $getURl;
//            } else {
//                return array('message' => $getURl['message']);
//            }
//        }
//        return array('status' => false);
//    }


    function getUrl($game_id, $fun_play) {

        if ($game = $this->getItem($game_id)) {
            $user_id = CakeSession::read('Auth.User.id');

            if ($game['IntGame']['active'] == 0)
                return array('message' => __("You are not allowed to play this game."));

            if ($game['IntGame']['fun_play'] == 0 && $fun)
                return array('message' => __("You can't play this game for fun."));

            if (!$user_id && !$fun_play)
                return array('message' => __("Please login first."));

            if ($fun_play == false || !$fun_play) {
                $fun_play = 'false';
            } else {
                $fun_play = 'true';
            }

            // if ($game['IntGame']['source'] == 'BlueOceanGames') {
            //     $this->BlueOceanGames = ClassRegistry::init('Games.BlueOceanGames');
            //     $url = $this->BlueOceanGames->getGameUrl($game['IntGame']['source_id'], $fun_play);
            // }

            $gameModel = ClassRegistry::init("Games." . $game['IntGame']['source']);
            $url = $gameModel->getGameUrl($game['IntGame']['source_id'], $fun_play);

            if ($url['status'] == 'success') {
                if ($url['content'])
                    return $url;
            } else {
                return array('message' => $url['message']);
            }
        }
        return array('status' => false);
    }

}
