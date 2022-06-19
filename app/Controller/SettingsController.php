<?php

/**
 * Handles Settings
 *
 * Handles Script Settings
 *
 * @package    Settings
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
App::uses('TimeZoneHelper', 'View/Helper');

class SettingsController extends AppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Settings';

    /**
     * An array containing the class names of models this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Setting', 'Currency', 'Language', 'Payment');

    /**
     * Admin index
     * @return mixed|void
     */
    public function admin_index() {
        if (!empty($this->request->data)) {
            if ($this->Setting->saveSettings($this->request->data, 'generalSettings')) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('An error occurred. Can\'t save settings.', true));
            }
        }

        $data = $this->Setting->getGeneralSettings();

        $this->request->data = $data;

        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->params));

//        $this->loadModel('Currency');
//        $this->loadModel('Languages');

        $currencies = $this->Currency->getList();
        $locales = $this->Language->getIdLangueageList();

        $TZHelper = new TimeZoneHelper(new View());
        $time_zones = $TZHelper->getTimeZones();

        $this->set('currencies', $currencies);
        $this->set('locales', $locales);
        $this->set('time_zones', $time_zones);
    }

    public function admin_seo() {
        if (!empty($this->request->data)) {
            if ($this->Setting->saveSettings($this->request->data, 'seoSettings')) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('An error occurred. Can\'t save settings.', true));
            }
        }
        $data = $this->Setting->getSeoSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->params));
    }

    public function admin_warnings() {
        if (!empty($this->request->data)) {
            if ($this->Setting->saveSettings($this->request->data, 'warningsSettings')) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }
        $data = $this->Setting->getWarningsSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->params));
    }

    function admin_extragames() {
        if (!empty($this->request->data)) {
            if ($this->Setting->saveSettings($this->request->data, 'extragamesSettings')) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }
        $data = $this->Setting->getExtragamesSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->params));
    }

    function admin_deposits() {
        if (!empty($this->request->data)) {
            if ($this->Setting->saveSettings($this->request->data, 'depositsSettings')) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }
        $data = $this->Setting->getDepositsSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->params));
    }

    function admin_depositsRisks() {
        if (!empty($this->request->data)) {
            if ($this->Setting->saveSettings($this->request->data, 'depositsRisksSettings')) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }
        $data = $this->Setting->getDepositsRisksSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->params));
    }

    function admin_withdraws() {
        if (!empty($this->request->data)) {
            if ($this->Setting->saveSettings($this->request->data, 'withdrawsSettings')) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }
        $data = $this->Setting->getWithdrawsSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->params));
    }

    public function admin_withdrawsRisks() {
        if (!empty($this->request->data)) {
            if ($this->Setting->saveSettings($this->request->data, 'withdrawsRisksSettings')) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }
        $data = $this->Setting->getWithdrawsRisksSettings();

        $this->request->data = $data;

        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->params));
    }

    public function admin_referral() {
        if (!empty($this->request->data)) {
            if ($this->Setting->saveSettings($this->request->data, 'referralSettings')) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }
        $data = $this->Setting->getReferralSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->params));
    }

    public function admin_promo() {
        if (!empty($this->request->data)) {
            if ($this->Setting->saveSettings($this->request->data, 'promoSettings')) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }

        $data = $this->Setting->getPromoSettings();

        $this->request->data = $data;

        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->params));
    }

    function admin_deposit() {
        if (!empty($this->request->data)) {
            if ($this->Setting->saveSettings($this->request->data, 'depositSettings')) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }
        $data = $this->Setting->getDepositsSettings();
        $this->request->data = $data;
        $this->set('data', $data);
        $this->set('tabs', $this->Setting->getTabs($this->params));
    }

    function admin_ip() {
        $filename = APP . 'banned_ips';

        if (!empty($this->request->data)) {
            $fh = fopen($filename, 'w');
            if (fwrite($fh, $this->request->data['Setting']['ips']) === false)
                $this->__setError(__('Can\'t save settings.', true));
            fclose($fh);
        }

        $this->set('contents', file_get_contents($filename));
    }

    function admin_tickettax() {
        if (!empty($this->request->data)) {
            if ($this->Setting->updateField('ticket_tax_by_country', serialize($this->request->data['Setting']))) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }

        //load countries from user model
        $available_countries = $this->User->getCountriesList();

        //load settings
        $all_settings = $this->Setting->getRiskSettings();
        $ticket_tax_by_country = $all_settings['ticket_tax_by_country']['value'];

        //set the limit array into database
        if ($ticket_tax_by_country == "") {
            $countries = $available_countries;
            foreach ($countries as &$country) {
                $country = "";
            }
            $value = serialize($countries);
            $this->Setting->updateField('ticket_tax_by_country', $value);
        }
        $this->set('all_countries', $available_countries);
        $this->set('countries', unserialize($ticket_tax_by_country));
        $this->set('singularName', 'Setting');
        $this->set('pluralName', 'Settings');
    }

}
