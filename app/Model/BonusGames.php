<?php
/* 
 * @file BonusGames.php
 */

class BonusGames extends  AppModel {    
    /**
     * Model name
     * @var string
     */
    public $name = 'BonusGames';
    
    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var $useTable string
     */
    public $useTable = 'bonus_games';
        
    /**
     * Model schema
     *
     * @var array
     */
    protected $_schema = array(
        'id' => array(
            'type'          => 'int',
            'length'        => 11,
            'null'          => false
        ),  
        'type_id'           => array(
            'type'          => 'int',
            'length'        => 11,
            'null'          => false
        ),       
        'game'              => array(
            'type'          => 'tinyint',
            'length'        => 1,
            'null'          => false
        ),   
        'percentage' => array(
            'type'          => 'int',
            'length'        => null,
            'null'          => true
        ),
    );
        
    /**
     * List of behaviors to load when the model object is initialized.
     *
     * @var $actsAs array
     */
    public $actsAs = array('Containable');
    
    /**
     * Detailed list of belongsTo associations.
     *
     * @var array
     */
    public $belongsTo = array(
        'BonusType' => array(
            'className'     => 'BonusType',
            'foreignKey'    => 'type_id',
            'counterCache'  => true
        )
    );
    
    /**
     *   BonusGames   Game
     *   ------------------
     *   All            0
     *   Sportsbook     1
     *   Live casino    2
     *   RGS            3
     */
    const ALL_GAMES  = 0,
          SPORTSBOOK = 1,
          LIVECASINO = 2,
          RGS        = 3;
        
    /**
     * Array containing an available games with 
     * their humanized names
     *
     * @var $trigger array 
     */
    public static $games = array(
        self::ALL_GAMES     => 'All', 
        self::SPORTSBOOK    => 'Sportsbook',
        self::LIVECASINO    => 'Live Casino', 
        self::RGS           => 'RGS'
    );
    
    
    /**
     * Returns edit fields
     *
     * @return array|mixed
     */
    public function getEdit() {
        $fields = array(
            'id'                => array('type' => 'hidden'),
            'game'              => $this->getFieldHtmlConfig('select', array('options' => self::$games)),
            'percentage' => $this->getFieldHtmlConfig('number', array('label' => __('Payoff Percentage in %'))),
        );
		
        return $fields;
    }
    
    
    public function calc_payoff($type_id, $ticket, $game_type) {
        $BonusType = ClassRegistry::init('BonusType');    
           
        $bonus_type = $BonusType->find('first', array(
            'conditions'    => array(
                'id'   => $type_id
            )
        ));
                
        if(empty($bonus_type)) return false;  
        
        // each game has its own conditions for bonus payoff
        switch($game_type) {
            case self::SPORTSBOOK: 
                $amount = $this->handle_sportsbook_bets($ticket);
                break;
            
            case self::LIVECASINO: 
                $amount = $this->handle_livecasino_bets($ticket);
                break;
            
            case self::RGS: 
                $amount = $this->handle_rgs_bets($ticket);
                break;
        }     
                
        // ticket isnt valid for bonus pay off
        if($amount === false) return false;
        
        // find percentage for games
        if(!empty($bonus_type['BonusGames'])) {
            foreach($bonus_type['BonusGames'] as $game) {
                if($game['game'] ==  self::ALL_GAMES) {
                    $per = $game['percentage'];
                    break;
                }
                
                if($game['game'] == $game_type) {
                    $per = $game['percentage'];
                }
            }
        }
        
        // if percentage amount in bonus type is set then it is used as a back up 
        // in case a game is not defined in the bonus type or the whole amount is 
        // taken into account
        if(empty($per)) {
            $per = !empty($bonus_type['BonusType']['percentage'])?$bonus_type['BonusType']['percentage']: 100;
        }
        
        // penalty if bonus is canceled
        return $amount * ($per/100);
    }
    
    
    /**
     * Calculates payoff amount for sportsbook bets
     * 
     * @param {int} $ticket_id
     * @return int amount 
     */
    private function handle_sportsbook_bets($ticket) {                
        // if sum is bigger than 3
        if((count($ticket['TicketPart']) + count($ticket['TicketLivepart'])) > 2) {    
            $min_odd_counter = 0;                

            foreach($ticket['TicketPart'] as $ticket_part) {
                // minimum 3 bets over 1.7
                if($ticket_part['odd'] >= 1.7) {
                    $min_odd_counter++;
                }
            }

            foreach($ticket['TicketLivepart'] as $ticket_part) {
                // minimum 3 bets over 1.7
                if($ticket_part['odd'] >= 1.7) {
                    $min_odd_counter++;
                }
            }
            
            if($min_odd_counter > 2) {
                return $ticket['Ticket']['amount'];
            }
        }
        
        return false;
    }
    
    
    /**
     * Calculates payoff amount for livecasino bets
     * 
     * @param {int} $ticket_id
     * @return int amount 
     */
    private function handle_livecasino_bets($ticket) {
        
    }
    
    
    /**
     * Calculates payoff amount for rgs bets
     * 
     * @param {int} $ticket_id
     * @return int amount 
     */
    private function handle_rgs_bets($ticket) {
        
    }
}