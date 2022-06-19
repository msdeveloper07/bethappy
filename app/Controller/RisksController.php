<?php
/**
 * Front Risks Controller
 *
 * Handles Risks Actions
 *
 * @package    Risks
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */

class RisksController extends AppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Risks';

    /**
     * An array containing the class names of models this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Risk', 'Setting', 'Sport', 'League', 'Ticket', 'Deposit', 'Withdraw', 'Country.Country', 'UserCategory');

    /**
     * Admin index
     * @return mixed|void
     */
    function admin_index() {
        if (!empty($this->request->data)) {
            if ($this->Setting->saveSettings($this->request->data, 'riskSettings')) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }
        $settings = $this->Setting->getRiskSettings();
        $this->set('settings', $settings);
        $this->set('tabs', $this->Risk->getTabs($this->params));

        $this->set('singularName', 'Risk');
        $this->set('pluralName', 'Risks');
    }

    function admin_sports() {
        if (!empty($this->request->data) && !empty($this->request->data['limit'])) {
            $this->autoRender = false;
            $this->Risk->updateRisk('Sport', serialize($this->request->data['limit']));
        } else {
            $this->set('sports', $this->Sport->find('all', array(
                'recursive' => -1, 
                'conditions' => array('Sport.active' => 1), 'order' => array('Sport.name ASC'))
            ));
        }
    }

    function admin_leagues($sportId = null) {
        if (!empty($this->request->data) && !empty($this->request->data['limit'])) {
            $this->autoRender = false;
            $this->Risk->updateRisk('League', serialize($this->request->data['limit']));
        } else {
            $sport_id = $this->request->data['Search']['sport_id'];
            $all = $this->Risk->getLeaguesOrdered($sport_id);

            $sports = $this->Sport->find('list', array(
                'fields' => array('id', 'name'),
                'recursive' => -1,
                'conditions' => array('Sport.active' => 1),
                'order' => array('Sport.name ASC')
            ));
            $this->set('data', $all['data']);
            $this->set('countries', $all['countries']);
            $this->set('sports', $sports);
            $this->set('sportname', $sports[$sport_id]);
        }
    }
    
    function admin_warnings() {
//        $bigOddTickets = $this->Ticket->getBigOddTickets(Configure::read('Settings.bigOdd'));
//        $bigStakeTickets = $this->Ticket->getBigStakeTickets(Configure::read('Settings.bigStake'));
//        $bigWinningTickets = $this->Ticket->getBigWinningTickets(Configure::read('Settings.bigWinning'));
//        $bigDeposits = $this->Deposit->getBigDeposits(Configure::read('Settings.bigDeposit'));
//        $bigWithdraws = $this->Withdraw->getBigWithdraws(Configure::read('Settings.bigWithdraw'));
//        
//        $this->set(compact('bigOddTickets', 'bigStakeTickets', 'bigWinningTickets', 'bigDeposits', 'bigWithdraws'));
//        $this->set('tabs', $this->Risk->getTabs($this->params));
//
//        $this->set('singularName', 'Warning');
//        $this->set('pluralName', 'Warnings');
    }

    function admin_ticketamounts() {
        if (!empty($this->request->data)) {
            $array_value=$this->request->data;
           
            foreach($array_value['amount'] as $key=>$amounts){
                if ($amounts!="") {
                    $all_values[]=array(
                        'amount'=>$amounts,
                        'possible_amount_win'=>$array_value['possible_amount_win'][$key]
                    );
                }
            }
            $value=serialize($all_values);
            if ($this->Setting->updateField('ticket_limit_by_amount', $value)) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }      
            
        $all_settings = $this->Setting->getRiskSettings();
      
        $ticket_limit=unserialize($all_settings['ticket_limit_by_amount']['value']);

        $this->set('settings', $ticket_limit);
        $this->set('tabs', $this->Risk->getTabs($this->params));
        $this->set('singularName', 'Risk');
        $this->set('pluralName', 'Risks');
    }
    
    function admin_ticketcountry() {
        if (!empty($this->request->data)) {
            if ($this->Setting->updateField('ticket_limit_by_country', serialize($this->request->data['Setting']))) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }

        //load countries from user model
        $available_countries=$this->User->getCountriesList();

        //load settings
        $all_settings = $this->Setting->getRiskSettings();
        $ticket_limit_by_country=$all_settings['ticket_limit_by_country']['value'];

        //set the limit array into database
        if ($ticket_limit_by_country==""){
            $countries=$available_countries;
            foreach($countries as &$country){
                $country="";
            }

            $value=serialize($countries);
            $this->Setting->updateField('ticket_limit_by_country', $value);
        }

        $this->set('all_countries', $available_countries);
        $this->set('countries', unserialize($ticket_limit_by_country));
        $this->set('singularName', 'Risk');
        $this->set('pluralName', 'Risks');
    }

    public function admin_riskcategories() {
        if (!empty($this->request->data)) {
            if ($this->User->updateRisk($this->request->data)) {
                $this->__setMessage(__('Settings saved.', true));
            } else {
                $this->__setError(__('can\'t save settings.', true));
            }
        }
        $this->paginate = array('limit' => Configure::read('Settings.itemsPerPage'));
        $data = $this->paginate('UserCategory');
        $this->set('data', $data);
        $this->set('tabs', $this->Risk->getTabs($this->params));
    }


}

