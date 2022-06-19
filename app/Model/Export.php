<?php
/**
 * API Model
 *
 * Handles API Data Source Actions
 *
 * @package    API.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */

class Export extends AppModel
{

    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'Export';

    /**
     * Custom database table name, or null/false if no table association is desired.
     *
     * @var bool
     */
    public $useTable = false;
    
    public function exportUsers(){
        $userModel = ClassRegistry::init('User');
        $data = $userModel->query('SELECT User.id,User.username,User.first_name,User.last_name,
        (select language from languages where User.language_id=languages.id) as language,
        (select name from currencies where User.currency_id=currencies.id) as currency,
        (select sum(payments.amount) from payments where User.id=payments.user_id and payments.type="Deposit") as deposit_amount,
        (select count(id) from payments where User.id=payments.user_id and payments.type="Deposit") as deposit_num,
        User.date_of_birth,
        (select date from userlogs where User.id=userlogs.user_id and action="login" order by id desc Limit 1,1) as last_login,        
        User.email,User.balance,User.registration_date,User.country,User.mobile_number
        FROM `users` as User where group_id=1');
        
        
        foreach($data as &$user){
            $user['User']['language'] = $user[0]['language'];
            $user['User']['currency'] = $user[0]['currency'];
            $user['User']['deposit_amount'] = $user[0]['deposit_amount'];
            $user['User']['deposit_num'] = $user[0]['deposit_num'];
            $user['User']['last_login'] = $user[0]['last_login'];
        }
        
        return $data;
    }
    
    public function exportDepositors(){
        $userModel = ClassRegistry::init('User');
        $data = $userModel->query('SELECT User.id,User.username,User.first_name,User.last_name,
        (select sum(payments.amount) from payments where User.id=payments.user_id and payments.type="Deposit") as deposit_amount,
        (select count(id) from payments where User.id=payments.user_id and payments.type="Deposit") as deposit_num,
        User.date_of_birth,
        (select date from userlogs where User.id=userlogs.user_id and action="login" order by id desc Limit 1,1) as last_login,        
        User.email,User.balance,User.registration_date,User.country,User.mobile_number,languages.language as language,
        currencies.name as currency
        FROM `users` as User 
        INNER JOIN languages on User.language_id=languages.id 
        INNER JOIN currencies on User.currency_id=currencies.id
        WHERE User.group_id=1 
        HAVING deposit_amount>0');
        
        foreach($data as &$user){
            $user['User']['language'] = $user['languages']['language'];
            $user['User']['currency'] = $user['currencies']['currency'];
            $user['User']['deposit_amount'] = $user[0]['deposit_amount'];
            $user['User']['deposit_num'] = $user[0]['deposit_num'];
            $user['User']['last_login'] = $user[0]['last_login'];
        }
        
        return $data;
    }
}