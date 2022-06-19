<?php

//

App::uses('AppController', 'Controller');

class RaventrackController extends RaventrackAppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'Raventrack';

    /**
     * Paginate
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     * @var array
     */
    public $uses = array('Raventrack.Raventrack', 'TransactionLog', 'BonusLog', 'User');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $this->Auth->allow('getPlayerRegistrations', 'getStatisticsReport');
        parent::beforeFilter();
    }

    //Retreives the player registrations in a given date interval
    //returns brand_name, registration_date, btag, player_id, player_country
    //btag:
    //Example: a_13271b_103610789c_63765d_123456
    //We will parse this string and will work on the assumption that it contains this data:
    //a_ = Affiliate Profile ID
    //b_ = Affiliate Profile Site ID (can also be empty)
    //c_ = Campaign Resource ID (can also beempty)
    //d_ = RavenTrack click ID
    //https://bethappy.com/Raventrack/Raventrack/getPlayerRegistrations/2021-05-01/2021-12-31
    public function getPlayerRegistrations($player_registration_start_date = '2020-01-01', $player_registration_end_date = '2021-12-31') {
        try {
            $data = array();
            $users = $this->User->find('all', array('conditions' => array('registration_date BETWEEN ? AND ?' => array(date('Y-m-d 00:00:00', strtotime($player_registration_start_date)), date('Y-m-d 23:59:59', strtotime($player_registration_end_date))))));
            foreach ($users as $user) {
                $data[] = array(
                    'brand_name' => 'BetHappy',
                    'registration_date' => date('Y-m-d', strtotime($user['User']['registration_date'])),
                    'btag' => 'a_' . $user['User']['affiliate_id'] . 'b_c_d_123456',
                    'player_id' => $user['User']['id'],
                    'player_country' => $user['Country']['alpha2_code']
                );
            }

            $response = array('status' => 'success', 'response' => $data);
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'response' => $ex->getMessage());
        }

        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

    //Retrieves the player statistics for all currencies, in a given date interval
    //For each product separately
    //https://bethappy.com/Raventrack/Raventrack/getStatisticsReport/2020-01-01/2021-12-31
    public function getStatisticsReport($date) {
        try {
            $date_parts = explode('-', $date);

            if (checkdate($date_parts[1], $date_parts[2], $date_parts[0]) && strlen($date_parts[0]) === 4) {
                //date is valid
                $now = time();
                if (strtotime($date) < $now) {
                    $start_date = date("Y-m-d 00:00:00", strtotime($date));
                    $end_date = date("Y-m-d 23:59:59", strtotime($date));

                    $blueocean = $this->Raventrack->getBlueOceanReport($start_date, $end_date);
                    //var_dump($blueocean);
                    $data = array();
                    foreach ($blueocean['stats'] as $currency => $stats) {
                        foreach ($stats as $player_id => $player) {
                            //var_dump($player_id);

                            $data[$currency][$player_id]['player_id'] = $player_id;
                            $player_deposits = $this->Raventrack->getPlayerDailyDeposits($player_id, $start_date, $end_date);

                            if (!empty($player_deposits[$currency]['user_id'])) {
                                if ((int) $player_deposits[$currency]['user_id'] === $player_id) {
                                    $data[$currency][$player_id]['deposits'] = $player_deposits[$currency]['amount'];
                                }
                            } else {
                                $data[$currency][$player_id]['deposits'] = sprintf('%.2f', 0);
                            }
                            $data[$currency][$player_id]['BlueOcean']['real_bets'] = $player['real_bets'];
                            $data[$currency][$player_id]['BlueOcean']['real_wins'] = $player['real_wins'];
                            $data[$currency][$player_id]['BlueOcean']['real_rollbacks'] = $player['real_rollbacks'];
                            $data[$currency][$player_id]['BlueOcean']['real_refunds'] = $player['real_refunds'];
                            $data[$currency][$player_id]['BlueOcean']['real_net'] = $player['real_net'];
                            $data[$currency][$player_id]['BlueOcean']['bonus_bets'] = $player['bonus_bets'];
                            $data[$currency][$player_id]['BlueOcean']['bonus_wins'] = $player['bonus_wins'];
                            $data[$currency][$player_id]['BlueOcean']['bonus_rollbacks'] = $player['bonus_rollbacks'];
                            $data[$currency][$player_id]['BlueOcean']['bonus_refunds'] = $player['bonus_refunds'];
                            $data[$currency][$player_id]['BlueOcean']['bonus_net'] = $player['bonus_net'];


                            $data[$currency][$player_id]['date'] = $date;
                        }
                    }
                    //var_dump($data);
                    $response = array('error' => 0, 'response' => $data);
                } else {
                    // then it is in the future
                    $response = array('error' => 1, 'response' => 'Date is not in valid range.');
                }
            } else {
                //date is invalid
                $response = array('error' => 1, 'response' => 'Invalid date format.');
            }
        } catch (Exception $ex) {
            $response = array('error' => 1, 'response' => $ex->getMessage());
        }

        $this->response->type('json');
        $this->response->body(json_encode($response));
    }

}
