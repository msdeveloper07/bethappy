<?php

/**
 * Short description for class
 *
 * Long description for class (if any)...
 *
 * @package    Payments
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('AppController', 'Controller');

class PaymentsAppController extends AppController {

    /**
     * Controller name
     * @var $name string
     */
    public $name = 'PaymentsApp';

    /**
     * Paginate
     * @var array
     */
    public $paginate = array();

    /**
     * Models
     * @var array
     */
    public $uses = array();

    /**
     * The name of the layout file to render the view inside of
     * @var string
     */

    /**
     * Payment config
     * @var array
     */
    protected $config = array();

    //define transaction statuses

    public function beforeFilter() {
        parent::beforeFilter();
        //$this->Auth->allow('success', 'failed');
    }

    const PAYMENT_TYPE_DEPOSIT = 'Deposit';
    const PAYMENT_TYPE_WITHDRAW = 'Withdraw';
    const PAYMENT_TYPE_REFUND = 'Refund';
    const DEBUG_MODE = true;

    public function get_controller($slug) {
        switch ($slug) {
            case 'cashlib':
                return 'Cashlib';
            case 'epro-visa':
                return 'Epro';
            case 'neteller':
                return 'Neteller';
            case 'skrill':
            case 'paysafe':
            case 'rapid':
                return 'Skrill';
            case 'radiant-pay':
                return 'RadiantPay';
            case 'forum-pay':
                return 'ForumPay';
            case 'bridgerpay-cc':
            case 'bridgerpay-astropay':
            case 'bridgerpay-gigadat':
                return 'BridgerPay';
            case 'b2crypto':
                return 'B2crypto';
            case 'quaife-visa':
            case 'quaife-mastercard':
                return 'Quaife';
            case 'bank-transfer':
                return 'BankTransfer';
            case 'card-transfer':
                return 'CardTransfer';
            case 'aninda-havale':
            case 'aninda-papara':
            case 'aninda-ccw':
            case 'aninda-ccd':
            case 'aninda-mefete':
            case 'aninda-btc':
            case 'aninda-qr':
                return 'Aninda';
	        case 'vippass':
                return 'Vippass';
            case 'astropay':
                return 'Astropay';
            case 'dixon':
                return 'Dixonpay';
            case 'wonderland-pay':
                return 'WonderlandPay';
            case 'uqualify':
                return 'Uqualify';
            default:
                break;
        }
    }

    public function get_method($slug) {
        switch ($slug) {
            case 'skrill':
                return 'WLT';
            case 'paysafe':
                return 'PSC';
            case 'rapid':
                return 'OBT, NGP';
            case 'card-transfer':
                return 'CT';
            case 'bank-transfer':
                return 'BT';
            case 'radiant-pay':
                return 'RP';
            case 'aninda-havale':
                return 'AH';
            case 'aninda-papara':
                return 'AP';
            case 'aninda-ccw':
                return 'ACCW';
            case 'aninda-ccd':
                return 'ACCD';
            case 'aninda-mefete':
                return 'AM';
            case 'aninda-btc':
                return 'ABTC';
            case 'aninda-qr':
                return 'AQR';
            case 'bridgerpay-cc':
                return 'BPCC';
            case 'bridgerpay-astropay':
                return 'BPAP';
            case 'bridgerpay-gigadat':
                return 'BPGD';
        }
    }

//for Skrill and Bridgerpay
    public function get_payment($code) {
        switch ($code) {
            case 'WLT':
                return 'Wallet';
            case 'NTL':
                return 'Neteller';
            case 'PSC':
                return 'PaysafeCard';
            case 'RSB':
                return 'Resurs';
            case 'ACC':
                return 'All card';
            case 'VSA':
                return 'Visa';
            case 'MSC':
                return 'MasterCard';
            case 'VSE':
                return 'Visa Electron';
            case 'MAE':
                return 'Maestro';
            case 'AMX':
                return 'American Express';
            case 'DIN':
                return 'Diners';
            case 'JCB':
                return 'JCB';
            case 'GCB':
                return 'Carte Bleue'; //France
            case 'DNK':
                return 'Dankort'; //Denmark
            case 'PSP':
                return 'PostePay'; //Italy
            case 'CSI':
                return 'CartaSi'; //Italy
            case 'OBT':
            case 'NGP':
                return 'RAPID Transfer';
            case 'GIR':
                return 'giropay'; //Germany
            case 'DID':
                return 'Direct Debit/SEPA'; //Germany
            case 'SFT':
                return 'Klarna (was Sofort)';
            case 'EBT':
                return 'Nordea Solo'; //Sweden
            case 'IDL':
            case 'GCI':
                return 'iDEAL';
            case 'NPY':
                return 'EPS (Netpay)'; //Austria
            case 'PLI':
                return 'POLi'; //Australia
            case 'PWY':
                return 'Przelewy24'; //Poland
            case 'EPY':
                return "ePay.bg"; //Bulgaria
            case 'GLU':
                return 'Trustly';
            case 'ALI':
                return 'Alipay'; //China
            case 'ADB':
                return 'Astropay - Online'; //Argentina, Brazil
            case 'AOB':
                return 'Astropay - Offline'; //Brazil, Chile, China, Colombia
            case 'ACI':
                return 'Astropay - Cash (Invoice)'; // Argentina, Brazil, Chile, China, Colombia, Mexico, Peru, Uruguay
            case 'AUP':
                return 'Unionpay (via Astropay)'; //China
            case 'BPAP'://Bridgerpay
                return 'astro_pay';
            case 'BPGD'://Bridgerpay
                return 'gigadat';
            default :
                return;
        }
    }

    /*
     * Anında Mefete Deposit Rule

      1. A player made a deposit from any system.
      2. There is 1 approved deposit. It doesn't matter which system it is. It may or may not be our system.
      3. This player can see the x option.
      4. Those who do not obey these rules cannot see.

     */

    public function aninda_deposit_mefete_rule($user_id) {
        $this->Payment = ClassRegistry::init('Payments.Payment');
        $payments = $this->Payment->find('count', array('conditions' => array('Payment.user_id' => $user_id, 'Payment.type' => 'Deposit', 'Payment.status' => 'Completed', 'Payment.provider !=' => 'Manual')));
        if ($payments >= 1)
            return true;

        return false;
    }

    /*
     * Anında Kredi Kartı Deposit Rule

      1. A player has deposited money from any system.
      2. There are 5 approved deposit and 5 approved withdrawal transactions, it doesn't matter which systems it is. It may or may not be our system.
      3. This player can see the Anında Kredi Kartı option.
      4. Those who do not follow these rules cannot see.
     */

    public function aninda_deposit_credit_card_rule($user_id) {
        $this->Payment = ClassRegistry::init('Payments.Payment');
        $payments = $this->Payment->find('count', array('conditions' => array('Payment.user_id' => $user_id, 'Payment.type' => 'Deposit', 'Payment.status' => 'Completed', 'Payment.provider !=' => 'Manual')));
        if ($payments >= 5)
            return true;

        return false;
    }

    public function aninda_withdraw_credit_card_rule($user_id) {
        $this->Payment = ClassRegistry::init('Payments.Payment');
        $payments = $this->Payment->find('count', array('conditions' => array('Payment.user_id' => $user_id, 'Payment.type' => 'Withdraw', 'Payment.status' => 'Completed', 'Payment.provider !=' => 'Manual')));
        if ($payments >= 5)
            return true;

        return false;
    }

}
