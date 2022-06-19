<?php
/**
 * Setting Model
 *
 * Handles Setting Data Source Actions
 *
 * @package    Settings.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */

class Setting extends AppModel {
    
    /**
     * Model name
     * @var $name string
     */
    public $name = 'Setting';

    /**
     * Model schema
     * @var $_schema array
     */
    protected $_schema = array(
        'id'        => array(
            'type'      => 'int',
            'length'    => 11,
            'null'      => false
        ),
        'value'     => array(
            'type'      => 'string',
            'length'    => 3200,
            'null'      => false
        ),
        'key'       => array(
            'type'      => 'string',
            'length'    => 255,
            'null'      => false
        )
    );

    /**
     * Save settings
     * @param $settings
     * @param $settingsType
     * @return bool|mixed
     */
    public function saveSettings($settings, $settingsType) {
        switch($settingsType) {
            case 'generalSettings':
                $db_settings = $this->getGeneralSettings();
                break;
            case 'riskSettings':
                $db_settings = $this->getRiskSettings();
                break;
            case 'seoSettings':
                $db_settings = $this->getSeoSettings();
                break;
            case 'warningsSettings':
                $db_settings = $this->getWarningsSettings();
                break;
            case 'depositsSettings':
                $db_settings = $this->getDepositsSettings();
                break;
            case 'depositsRisksSettings':
                $db_settings = $this->getDepositsRisksSettings();
                break;
            case 'withdrawsSettings':
                $db_settings = $this->getWithdrawsSettings();
                break;
            case 'withdrawsRisksSettings':
                $db_settings = $this->getWithdrawsRisksSettings();
                break;
            case 'referralSettings':
                $db_settings = $this->getReferralSettings();
                break;
            case 'promoSettings':
                $db_settings = $this->getPromoSettings();
                break;
            case 'depositSettings':
                $db_settings = $this->getDepositSettings();
                break;
            case 'extragamesSettings':
                $db_settings = $this->getExtragamesSettings();
                break;

            default:
                return false;
        }
        $settings = array_map(function($setting) USE ($settings) {
            if(isset($settings['Setting'][$setting['id']])) {
                return array(
                    'id'    =>  $setting['id'],
                    'value' =>  $settings['Setting'][$setting['id']]
                );
            }
            return array();
        }, $db_settings);

        return $this->saveAll(array_filter(array_values($settings)));
    }

    /**
     * Updates setting field
     * @param $field
     * @param $value
     */
    function updateField($field, $value) {
        $options['conditions'] = array('Setting.key' => $field);

        $data = $this->find('first', $options);
        $data['Setting']['value'] = $value;
        if ($this->save($data)){
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns general settings
     * @return array
     */
    public function getGeneralSettings() {
        $list = array();
        $options['conditions'] = array(
            'Setting.key' => array(
                'timeFormat',
                'eventDateFormat',
                'websiteName',
                'registration',
                'contactMail',
                'defaultCurrency',
                'defaultTimezone',
                'defaultLanguage',
                'printing',
                'itemsPerPage',
                'defaultTheme',
                'websiteEmail',
                'copyright',
                'referals',
                'login',
                'passwordReset',
                'testDeposit',
                'allowMultiSingleBets',
                'charset',
                'under_maintanance',
                'game_slider',
                'kyc_file_formats'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }
        return $list;
    }

    /**
     * Returns SEO settings
     * @return array
     */
    public function getSeoSettings() {
        $list = array();
        $options['conditions'] = array(
            'Setting.key' => array(
                'metaDescription',
                'metaKeywords',
                'metaAuthor',
                'metaReplayTo',
                'metaCopyright',
                'metaRevisitTime',
                'metaIdentifierUrl',
                'websiteTitle'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }
        return $list;
    }

    /**
     * Returns warnings settings
     * @return array
     */
    public function getWarningsSettings() {
        $list = array();
        $options['conditions'] = array(
            'Setting.key' => array(
                'bigDeposit',
                'bigWithdraw',
                'bigStake',
                'bigOdd',
                'bigWinning'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }
        return $list;
    }

    /**
     * Returns Extra Games settings
     * @return array
     */
    public function getExtragamesSettings() {
        $list = array();
        $options['conditions'] = array(
            'Setting.key' => array(
                'roulette',
                'blackjack',
                'baccarat',
                'virtual_soccer',
                'soccer_roulette',
                'game_not_available',
                'partner_search'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }
        return $list;
    }

    /**
     * Returns deposit settings
     * @return array
     */
    public function getDepositsSettings() {
        $list = array();

        $options['conditions'] = array('Setting.key' => array('deposits', 'eTranzactStatus', 'ApcoStatus', 'epgStatus','TFMStatus'));
        $settings = $this->find('all', $options);

        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }
        return $list;
    }

    /**
     * Returns deposit risks settings
     * @return array
     */
    public function getDepositsRisksSettings() {
        $list = array();

        $options['conditions'] = array(
            'Setting.key' => array(
                'deposits',
                'D_Manual',
                'minDeposit',
                'maxDeposit'
            )
        );

        $settings = $this->find('all', $options);

        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }

        return $list;
    }

    /**
     * Returns withdraws settings
     * @return array
     */
    public function getWithdrawsSettings() {
        $list = array();
        $options['conditions'] = array('Setting.key' => array('withdraws'));
        
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }
        return $list;
    }

    /**
     * Returns withdraws risks settings
     * @return array
     */
    public function getWithdrawsRisksSettings() {
        $list = array();
        $options['conditions'] = array(
            'Setting.key' => array(
                'withdraws',
                'minWithdraw',
                'maxWithdraw',
                'kyc_limit_wthdraw'
            )
        );
        $settings = $this->find('all', $options);
        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }
        return $list;
    }


    /**
     * Returns risks settings
     * @return mixed
     */
    public function getRiskSettings() {
        $options['conditions'] = array( 
            'Setting.key' => array(
                'minBet',
                'maxBet',
                'maxBetsCount',
                'minBetsCount',
                'maxWin',
                'ticket_limit_by_amount',
                'ticket_limit_by_country',
                'ticket_tax_by_country',
                'daily_wager_limit',
                'daily_win_limit',
                'stop_bet'
            )
        );

        $list = array();
        $settings = $this->find('all', $options);

        foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
        }
        return $list;
    }

    /**
     * Returns deposits settings
     * @return array
     */
    public function getDepositSettings() {
    	$options['conditions'] = array('Setting.key' => array('daily_win_limit'));
    	$settings = $this->find('all', $options);
    	$list = array();
    	foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
    	}    	
    	return $list;
    }
 
    /**
     * Returns referral settings
     * @return array
     */
    public function getReferralSettings() {
    	$options['conditions'] = array('Setting.key' => array('referral_deposit_percentage'));
    	$settings = $this->find('all', $options);
    	$list = array();
    	foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
    	}
    	return $list;
    }

    /**
     * Returns promo settings
     * @return array
     */
    public function getPromoSettings() {
    	$options['conditions'] = array(
            'Setting.key' => array(
                'left_promo_header',
                'left_promo_body',
                'right_promo_header',
                'right_promo_body',
                'left_promo_enabled',
                'right_promo_enabled',
                'bottom_promo_header',
                'bottom_promo_body',
                'bottom_promo_enabled'
            )
    	);
    	$settings = $this->find('all', $options);
    	$list = array();
    	foreach ($settings as $setting) {
            $list[$setting['Setting']['key']] = $setting['Setting'];
    	}
    	return $list;
    }

    /**
     * Returns admin tabs
     * @param $params
     * @return array
     */
    public function getTabs($params) {
        $tabs = array();
        if ($params['action'] == 'admin_warnings') {
            $tabs[] = $this->__makeTab(__('Warnings', true), 'warnings', 'risks');
            $tabs[] = $this->__makeTab(__('Settings', true), 'warnings', 'settings', NULL, true);
            return $tabs;
        }
        $tabs[] = $this->__makeTab('General Settings', 'index', 'settings', NULL, true);
		$tabs[] = $this->__makeTab('Ticket Settings', 'tickets', 'settings', NULL, true);
		$tabs[] = $this->__makeTab('Currencies', 'index', 'currencies', NULL, true);
		$tabs[] = $this->__makeTab('SEO Settings', 'seo', 'settings', NULL, true);
		$tabs[] = $this->__makeTab('Email templates', 'index', 'templates', NULL, true);
        return $tabs;
    }
}