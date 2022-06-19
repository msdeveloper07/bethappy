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
App::uses('AppController', 'Controller');

App::import('Vendor', 'Dompdf\Dompdf', array('file' => 'dompdf/autoload.inc.php')); // OR require_once('/var/www/clients/client1/web1/web/app/Vendor/'); 

use Dompdf\Dompdf;

class ReportsController extends PaymentsAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Reports';
    public $uses = array('Payments.Report', 'Payments.Payment', 'Payments.PaymentMethod');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('affiliate_players_deposits', 'affiliate_player_deposits', 'admin_user_payments'));
    }

    public function admin_deposits() {
        try {
            $this->set('payment_providers', $this->PaymentMethod->getPaymentMethods('deposit'));
            if ($this->request->data) {
                $request = $this->request->data['Report'];
                if ($request['from']) {
                    $from = date("Y-m-d 00:00:00", strtotime($request['from']));
                } else {
                    $from = date("Y-m-d 00:00:00", strtotime("first day of this month"));
                }
                if ($request['to']) {
                    $to = date("Y-m-d 23:59:59", strtotime($request['to']));
                } else {
                    $to = date("Y-m-d 23:59:59", strtotime("last day of this month"));
                }

                $selected_provider = $request['payment_provider'];
                $selected_status = $request['status'];
                $this->set('selected_provider', $selected_provider);
                $this->set('selected_status', $selected_status);
                $this->set('from', $from);
                $this->set('to', $to);


                $sql = "SELECT Payment.id, Payment.provider, Payment.created,  Payment.amount, Payment.status, User.first_name, User.last_name, User.username,Currency.name"
                        . " FROM payments as Payment"
                        . " INNER JOIN users AS User ON Payment.user_id = User.id"
                        . " INNER JOIN currencies AS Currency ON User.currency_id = Currency.id"
                        . " WHERE 1"
                        . " AND Payment.type = 'Deposit'"
                        . (!empty($selected_provider) ? " AND Payment.provider  = '" . $selected_provider . "'" : "")
                        . (!empty($selected_status) ? " AND Payment.status  = '" . ucfirst(strtolower($selected_status)) . "'" : "")
//                        . " AND Payment.status = 'Completed'"
                        . " AND Payment.created BETWEEN '{$from}' AND '{$to}'"
                        . " ORDER BY Currency.name, Payment.created";

                //var_dump($sql);

                $transactions = $this->Payment->query($sql);

                $data = array();
                foreach ($transactions as $transaction) {
                    $data[$transaction['Currency']['name']][$transaction['Payment']['id']] = $transaction['Payment'];
                    $data[$transaction['Currency']['name']][$transaction['Payment']['id']]['depositor_name'] = $transaction['User']['first_name'] . ' ' . $transaction['User']['last_name'];
                    $data[$transaction['Currency']['name']][$transaction['Payment']['id']]['username'] = $transaction['User']['username'];
                    $data[$transaction['Currency']['name']][$transaction['Payment']['id']]['currency'] = $transaction['Currency']['name'];
                    $data[$transaction['Currency']['name']][$transaction['Payment']['id']]['status'] = $transaction['Payment']['status'];
                    $data[$transaction['Currency']['name']][$transaction['Payment']['id']]['provider'] = $transaction['Payment']['provider'];
                }
                $this->set('data', $data);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    //Withdraws report by provider, by currency
    //Select the provider and date interval, and report will print all withdraws made, summed by currency
    public function admin_withdraws() {
        try {

            $this->set('payment_providers', $this->PaymentMethod->getPaymentMethods('withdraw'));

            if ($this->request->data) {
                $request = $this->request->data['Report'];
                $this->log($request);
                if ($request['from']) {
                    $from = date("Y-m-d 00:00:00", strtotime($request['from']));
                } else {
                    $from = date("Y-m-d 00:00:00", strtotime("first day of this month"));
                }
                if ($request['to']) {
                    $to = date("Y-m-d 23:59:59", strtotime($request['to']));
                } else {
                    $to = date("Y-m-d 23:59:59", strtotime("last day of this month"));
                }
                $selected_provider = $request['payment_provider'];
                $selected_status = $request['status'];
                $this->set('selected_provider', $selected_provider);
                $this->set('selected_status', $selected_status);
                $this->set('from', $from);
                $this->set('to', $to);
                $sql = "SELECT Payment.id, Payment.provider, Payment.amount, Payment.created, Payment.status, User.first_name, User.last_name, Currency.name"
                        . " FROM payments as Payment"
                        . " INNER JOIN users AS User ON Payment.user_id = User.id"
                        . " INNER JOIN currencies AS Currency ON User.currency_id = Currency.id"
                        . " WHERE 1"
                        . " AND Payment.type = 'Withdraw'"
                        . (!empty($selected_provider) ? " AND Payment.provider  = '" . $selected_provider . "'" : "")
                        . (!empty($selected_status) ? " AND Payment.status  = '" . ucfirst(strtolower($selected_status)) . "'" : "")
//                        . " AND Payment.status = 'Completed'"
                        . " AND Payment.created BETWEEN '{$from}' AND '{$to}'"
                        . " ORDER BY Currency.name, Payment.created";

                //var_dump($sql);

                $transactions = $this->Payment->query($sql);
                $data = array();
                foreach ($transactions as $transaction) {
                    $data[$transaction['Currency']['name']][$transaction['Payment']['id']] = $transaction['Payment'];
                    $data[$transaction['Currency']['name']][$transaction['Payment']['id']]['withdrawer_name'] = $transaction['User']['first_name'] . ' ' . $transaction['User']['last_name'];
                    $data[$transaction['Currency']['name']][$transaction['Payment']['id']]['currency'] = $transaction['Currency']['name'];
                    $data[$transaction['Currency']['name']][$transaction['Payment']['id']]['status'] = $transaction['Payment']['status'];
                    $data[$transaction['Currency']['name']][$transaction['Payment']['id']]['provider'] = $transaction['Payment']['provider'];
                }

                $this->set('data', $data);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function admin_user_payments($user_id) {
        try {
            $options = array();
            $options['conditions']['Payment.user_id'] = $user_id;
            $this->paginate = $this->Payment->getPagination($options);



            $this->set('data', $this->paginate($this->Payment));
            $this->set('user_id', $user_id);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function affiliate_player_deposits($player_id) {
        $this->layout = 'affiliate';

        try {
            $this->set('payment_providers', $this->PaymentMethod->getPaymentMethods('deposit'));
            if ($this->request->data) {
                $request = $this->request->data['Report'];
                if ($request['from']) {
                    $from = date("Y-m-d 00:00:00", strtotime($request['from']));
                } else {
                    $from = date("Y-m-d 00:00:00", strtotime("first day of this month"));
                }
                if ($request['to']) {
                    $to = date("Y-m-d 23:59:59", strtotime($request['to']));
                } else {
                    $to = date("Y-m-d 23:59:59", strtotime("last day of this month"));
                }

                $selected_provider = $request['payment_provider'];
//                $selected_status = $request['status'];
                $this->set('selected_provider', $selected_provider);
//                $this->set('selected_status', $selected_status);
                $this->set('from', $from);
                $this->set('to', $to);


                $sql = "SELECT Payment.id, Payment.provider, Payment.created,  Payment.amount, Payment.status, User.first_name, User.last_name, User.username,Currency.name"
                        . " FROM payments as Payment"
                        . " INNER JOIN users AS User ON Payment.user_id = User.id"
                        . " INNER JOIN currencies AS Currency ON User.currency_id = Currency.id"
                        . " WHERE 1"
                        . " AND Payment.type = 'Deposit'"
                        . " AND User.id = " . $player_id
                        . (!empty($selected_provider) ? " AND Payment.provider  = '" . $selected_provider . "'" : "")
                        . " AND Payment.status = 'Completed'"
                        . " AND Payment.created BETWEEN '{$from}' AND '{$to}'"
                        . " ORDER BY Payment.created";

                //var_dump($sql);

                $transactions = $this->Payment->query($sql);

                $data = array();
                foreach ($transactions as $transaction) {
                    $data[$transaction['Currency']['name']][$transaction['Payment']['id']] = $transaction['Payment'];
                    $data[$transaction['Currency']['name']][$transaction['Payment']['id']]['depositor_name'] = $transaction['User']['first_name'] . ' ' . $transaction['User']['last_name'];
                    $data[$transaction['Currency']['name']][$transaction['Payment']['id']]['username'] = $transaction['User']['username'];
                    $data[$transaction['Currency']['name']][$transaction['Payment']['id']]['currency'] = $transaction['Currency']['name'];
                    $data[$transaction['Currency']['name']][$transaction['Payment']['id']]['status'] = $transaction['Payment']['status'];
                    $data[$transaction['Currency']['name']][$transaction['Payment']['id']]['provider'] = $transaction['Payment']['provider'];
                }
                $this->set('data', $data);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function admin_printPDF($type = "") {
        $this->autoRender = false;
        //$id = $this->Session->read('Auth.User.id');
        //var_dump($this->request);
//        var_dump($this->Report->getCssData());
//        var_dump($this->request->data['Report']);
//        var_dump($this->request->data['htmldata']);
        //exit;
        switch ($type) {
            case 'collections':
                //to do if needed
                break;
            case 'report':
                $this->log($this->request->data, 'printPDF');
                if (!empty($this->request->data['htmldata'])) {
                    $html = '<body>';
                    $html .= $this->Report->getCssData();

                    if (!empty($this->request->data['Report'])) {
                        $html .= '<h3>' . $this->request->data['Report']['header'] . ' (' . __('From:') . ' ' . $this->request->data['Report']['from'] . ' ' . __('To:') . ' ' . $this->request->data['Report']['to'] . ')' . '</h3>';
                    }
                    $html .= $this->request->data['htmldata'];
                    $html .= '</body>';

                    $dompdf = new Dompdf();
                    $dompdf->set_option('defaultFont', 'sans-serif');
                    $dompdf->setPaper('A4', 'landscape');
                    $dompdf->loadHtml($html);

                    $dompdf->render();
                    $dompdf->stream($this->request->data['Report']['title'] . '(' . $this->request->data['Report']['from'] . '_' . $this->request->data['Report']['to'] . ')');

                    $this->redirect($this->referer());
                } else {
                    $this->__setError(__("No data found."));
                    $this->redirect($this->referer());
                }
                break;
            default:
                $this->__setError(__("You don't have permissions to use this page."));
                break;
        }
    }

}
