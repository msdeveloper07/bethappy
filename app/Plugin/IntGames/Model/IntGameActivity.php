<?php

class IntGameActivity extends IntGamesAppModel {

    public $name = 'IntGameActivity';
    protected $_schema = array(
        'user_id' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'int_game_id' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'fun' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'ismobile' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
        'date' => array(
            'type' => 'int',
            'null' => false,
            'length' => 11
        ),
    );

    /**
     * Detailed list of belongsTo associations.
     * @var array 
     */
    public $belongsTo = array(
        'User',
        'IntGames' => array(
            'className' => 'IntGames',
            'foreignKey' => 'int_game_id',
        )
    );

    const ItemsPerPage = 10;

    /**
     * Returns admin search fields
     * @return array
     */
    public function getSearch() {

        $fields = array(
            'IntGameActivity.ismobile' => $this->getFieldHtmlConfig('select', array('options' => array(-1 => 'None', 0 => 'No', 1 => 'Yes'), 'label' => __('Mobile'))),
            'IntGameActivity.fun' => $this->getFieldHtmlConfig('select', array('options' => array(-1 => 'None', 0 => 'No', 1 => 'Yes'), 'label' => __('Fun'))),
            'IntGameActivity.from' => $this->getFieldHtmlConfig('date', array('label' => 'Registration Date From')),
            'IntGameActivity.to' => $this->getFieldHtmlConfig('date', array('label' => 'Registration Date To')),
        );
//        $fields = array(
//            'User.id'                       =>  array('type' => 'number'),
//            'User.username'                 =>  array('type' => 'text'),
//            'User.email'                    =>  array('type' => 'text'),
//            'User.first_name'               =>  array('type' => 'text'),
//            'User.last_name'                =>  array('type' => 'text'),
//            'User.registration_date'        =>  array('type' => 'hidden'),
//            'User.registration_date_from'   =>  $this->getFieldHtmlConfig('date', array('label' => 'Registration Date From')),
//            'User.registration_date_to'     =>  $this->getFieldHtmlConfig('date', array('label' => 'Registration Date To')),
//            'User.last_visit'               =>  array('type' => 'hidden'),
//            'User.last_visit_from'          =>  $this->getFieldHtmlConfig('date', array('label' => 'Last Visit From')),
//            'User.last_visit_to'            =>  $this->getFieldHtmlConfig('date', array('label' => 'Last Visit To')),
//            'User.date_of_birth'            =>  $this->getFieldHtmlConfig('date', array('label' => __('Date of Birth'))),
//            'User.country'                  =>  $this->getFieldHtmlConfig('select', array('options' => $this->getCountriesList(), 'label' => __('Country'))),
//            'User.kyc_status'               =>  $this->getFieldHtmlConfig('select', array('options' => $this->getuserkyc_type(), 'label' => __('KYC Status'))),
//            'User.ip'                       =>  array('type' => 'text', 'label' => 'Registration IP'),
//            'User.last_visit_ip'            =>  array('type' => 'text', 'label' => 'Login IP'),
//            'User.category'                 =>  $this->getFieldHtmlConfig('select', array('options' => $UserCategories->list_categories(), 'label' => __('Category'))),
//        );
        return $fields;
    }

    public function saveActivity($user_id, $game_id, $fun = 0, $is_mobile = 0) {
        if (!$user_id)
            return;
        $data = array(
            'IntGameActivity' => array(
                'user_id' => $user_id,
                'int_game_id' => $game_id,
                'fun' => $fun,
                'ismobile' => $is_mobile,
                'date' => $this->__getSqlDate()
        ));
//        $this->log($data);

        $this->create();
        $this->save($data);
    }

    public function getGameLogs($user_id, $page) {
        $this->autoRender = false;
        try {

            $sql_games = "SELECT count(DISTINCT int_game_id) as total "
                    . "FROM `int_game_activities` "
                    . "INNER JOIN`int_games`ON int_game_activities.int_game_id = int_games.`id` "
                    . "WHERE int_game_activities.user_id =" . $user_id;
            $games = $this->query($sql_games);

            $sql_total = "SELECT count(*) as total "
                    . "FROM `int_game_activities` "
                    . "INNER JOIN`int_games`ON int_game_activities.int_game_id = int_games.`id` "
                    . "WHERE int_game_activities.user_id =" . $user_id;

            $total = $this->query($sql_total);
            $sql = "SELECT *  "
                    . "FROM `int_game_activities`  "
                    . "INNER JOIN `int_games` ON int_game_activities.int_game_id = int_games.`id` "
                    . "INNER JOIN `int_brands` ON int_games.brand_id = int_brands.`id` "
                    . "WHERE int_game_activities.user_id =" . $user_id . " "
                    . "ORDER BY int_game_activities.date DESC "
                    . "LIMIT "
                    . self::ItemsPerPage . " OFFSET " . (($page - 1) * self::ItemsPerPage);

            $logs = $this->query($sql);

            return array('data' => $logs, 'games_played' => $games[0][0]['total'], 'total' => $total[0][0]['total'], 'items_per_page' => self::ItemsPerPage);
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function gameActivitybyUser($user_id, $from, $to, $ismobile = -1, $fun = -1) {

        if (!$from) {
            $from = date("Y-m-d H:i:s", strtotime("-1 week"));
        }

        if (!$to) {
            $to = date("Y-m-d H:i:s", strtotime("now"));
        }

        $opt = array('conditions' => array('IntGameActivity.user_id' => $user_id, 'IntGameActivity.date BETWEEN ? AND ?' => array($from, $to)));



//        if ($ismobile != -1 || $ismobile != NULL) {
//            $opt['conditions']['IntGameActivity.ismobile'] = $ismobile;
//        }
//
//        if ($fun != -1 || $fun != NULL) {
//            $opt['conditions']['IntGameActivity.fun'] = $fun;
//        }
        $opt['order'] = array('IntGameActivity.date DESC');


        return $this->find('all', $opt);
        
    }

    public function apiGameActivity() {
        //foreach model
        //download data in a spesific format calling function "getactivitylog" from each log model of plugin
    }

}
