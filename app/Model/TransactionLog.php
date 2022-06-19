<?php

/**
 * transactionlog Model
 *
 * Handles transactionlog Actions
 *
 * @package    transactionlog.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
class TransactionLog extends AppModel {

    /**
     * Fields
     */
    public $name = 'TransactionLog',
            $useTable = 'transaction_log',
            $schema = array(
                'id' => array(
                    'type' => 'int',
                    'null' => false,
                    'length' => 11
                ),
                'user_id' => array(
                    'type' => 'int',
                    'null' => false,
                    'length' => 11
                ),
                'transaction_type' => array(
                    'type' => 'string',
                    'null' => true,
                    'length' => 32
                ),
                'Model' => array(
                    'type' => 'string',
                    'null' => true,
                    'length' => 50
                ),
                'Parent_id' => array(
                    'type' => 'int',
                    'null' => true,
                    'length' => 11
                ),
                'amount' => array(
                    'type' => 'decimal',
                    'length' => 8,
                    'null' => true
                ),
                'balance' => array(
                    'type' => 'decimal',
                    'length' => 8,
                    'null' => true
                ),
                'date' => array(
                    'type' => 'datetime',
                    'length' => null,
                    'null' => true
                )
    );

    /**
     * Add new entry to the transaction log
     * @param {array} $data
     */
    public function createTransactionLog($data) {
        $this->create();
        $this->save($data);
    }

    /**
     * Get all transaction of a user for a specified amount of time
     * 
     * @param {string}  $from
     * @param {string}  $to
     * @param {int}     $user_id
     * @return type
     */
    public function gettransactionlogs($from = null, $to = null, $user_id = null) {
        //if(empty($from)) $from = date("Y-m-d H:i:s", strtotime("-1 years"));
        //if(empty($to)) $to = date("Y-m-d H:i:s", strtotime("now"));
        $opt['recursive'] = -1;
        $opt['conditions']['user_id'] = $user_id;
        if (!empty($from) && !empty($to))
            $opt['conditions']['date BETWEEN ? AND ?'] = array($from, $to);
        $opt['order'] = array('date ASC');

        return $this->find('all', $opt);
    }

    public function getLogsByType($page = 1, $limit = 5, $types = array('Bet', 'Win', 'Refund'), $from = null, $to = null) {
        $query = 'select * from transactionlog where 1=1 ';
        $querycount = 'select count(*) count from transactionlog where 1=1';

        if (!empty($types)) {
            if (count($types) == 1) {
                $query .= ' and transaction_type = "' . $types[0] . '"';
                $querycount .= ' and transaction_type = "' . $types[0] . '"';
            } else if (count($types) > 0) {
                $query .= ' and transaction_type in ("' . implode('","', $types) . '")';
                $querycount .= ' and transaction_type in ("' . implode('","', $types) . '")';
            }
        }
        if ($from != null) {
            $query .= ' and date >= "' . $from . '"';
            $querycount .= ' and date >= "' . $from . '"';
        }
        if ($to != null) {
            $query .= ' and date <= "' . $to . '"';
            $querycount .= ' and date <= "' . $to . '"';
        }

        $offset = ($page - 1) * $limit;
        $query .= ' limit ' . $limit;
        $query .= ' offset ' . $offset;
        $data = $this->query($query);

        $count = $this->query($querycount);
        return array('all' => $data, 'count' => $count[0][0]['count']);
    }

}
