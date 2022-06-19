<?php
App::import('Vendor', 'Websocket', array('file' => 'Websocket/loader.php'));
use Ephp\Message;
use Ephp\SocketIOClient;

class LivecasinoShell extends AppShell {
    
    protected $clients;

    
    private static  $UrlList = array(
        'Belgium' => 'wss://engine.magiclivedealers.com:443/GameServer/gameNotifications',
        'Bulgaria' => 'wss://engine.livetablesbg.com:443/GameServer/gameNotifications',
        'Latvia' => 'wss://engine.livetableslv.com:443/GameServer/gameNotifications',
        'LatinAmerica' => 'wss://engine.ezugi.com:443/GameServer/gameNotifications',
    );
    /**
     * Constructor
     * Attaches Event Listener
    */
    public function __construct() {
        $this->requestAction('/?console=1');
        parent::__construct();
        
        ignore_user_abort(true);
        set_time_limit(0);
        ini_set("max_execution_time", "0");
        
        $this->clients = new \SplObjectStorage;
    }
    
    public function main() {
        $this->out("<info>Usage:</info>\n"
                . "Connect to server: <question>connect</question>\n");
    }
    
    public function connect() {

        if (!array_key_exists($this->args[0],self::$UrlList)){
            $this->out("<info>Usage:</info>\n"
                . "Connect to Belgium: <question>connect</question> <info>Belgium</info>\n"
                . "Connect to Bulgaria: <question>connect</question> <info>Bulgaria</info>\n"
                . "Connect to Latvia:  <question>connect</question> <info>Latvia</info>\n"
                . "Connect to LatinAmerica: <question>connect</question> <info>LatinAmerica</info>\n");
            
            exit;
        } 
       
        $client = new SocketIOClient(self::$UrlList[$this->args[0]]);

        $client->connect();

        $conenct_message = json_encode([
            MessageType => "InitializeSession",
            OperatorID => 10078003,
            vipLevel => 0,
            SessionCurrency => "EUR"
        ]);

        $client->send($conenct_message);


        try {
            $client->listen(function($message) use (&$client) {
    
                switch($message->MessageType){
                    case "SessionInitialized":
                        $this->out("Session Initialized at Server");
                        break;
                    case "ActiveTablesList":
                        $this->out("Received Table List");
                        foreach($message->TablesList as $table){
                              $register_message = json_encode([
                                    "TableId"=>$table->TableId,
                                    "MessageType"=>"RegisterSessionByTableId",
                                    "OperatorID"=>10078003
                                ]);
                              $client->send($register_message);
                              $this->out("Register at table id".$table->TableId);
                              sleep(1);
                              
                            }
                        break;
                    case "GameResults":
                        $tableId = $message->TableId;
                        $gameType = $message->gameType;
                        //$gameRound   We have to retrieve from roundStarted message
                        $actionType = $message->MessageType;
                        $data = serialize($message->GameResults);
                        $time = strtotime("now");
                        
                        break;
                    case "NoMoreBets":
                        /*
                         *  [TableId] => 101
                            [gameType] => 2
                            [destination] => gameNotifications
                            [MessageType] => NoMoreBets

                         */
                            //echo "Parse NoMoreBets";
                        break;
                    case "PlayersBets":
                            //echo "Parse PlayersBets";
                        /*
                         *  [TableId] => 5
                            [gameType] => 1
                            [PlayersBets] => Array
                                (
                                    [0] => stdClass Object
                                        (
                                            [BetBehindBetCount] => 0
                                            [BetBehindBetAmount] => 0
                                            [OperatorID] => 10062001
                                            [BetsList] => Array
                                                (
                                                    [0] => stdClass Object
                                                        (
                                                            [BetName] => RegularBet
                                                            [BetAmount] => 100000
                                                        )

                                                )

                                            [SeatId] => s7
                                            [Nickname] => mca195518
                                        )

                                    [1] => stdClass Object
                                        (
                                            [BetBehindBetCount] => 0
                                            [BetBehindBetAmount] => 0
                                            [OperatorID] => 10062001
                                            [BetsList] => Array
                                                (
                                                    [0] => stdClass Object
                                                        (
                                                            [BetName] => RegularBet
                                                            [BetAmount] => 30000
                                                        )

                                                )

                                            [SeatId] => s1
                                            [Nickname] => mca271733
                                        )

                                )

                            [destination] => gameNotifications
                            [MessageType] => PlayersBets
                                            */
                        break;
                    case "SuccessfulRegistration":
                        /*  [TableId] => 1000
                            [MessageType] => SuccessfulRegistration
                         * 
                         */


                        break;
                    case "SeatsUpdate":
                        print_R($message);
                            //echo "Parse SeatsUpdate";
                        break;
                    case "roundStarted":
                        /*
                         *  [TableId] => 142
                            [gameType] => 26
                            [destination] => gameNotifications
                            [TimerTimeLeft] => 20
                            [MessageType] => roundStarted
                         */
                        break;
                        default:
                        echo ($message->MessageType)."\n\r";
                        break;
                }
            });
        } catch(\RuntimeException $e) {
            echo $e->getMessage();
        }
    }
}