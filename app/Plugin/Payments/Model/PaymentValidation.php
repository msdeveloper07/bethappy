<?php

App::uses('PaymentAppModel', 'Payments.Model');
//App::import('Controller', 'App');

class PaymentValidation extends PaymentAppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'PaymentValidation';
    public $useTable = false;

    /*
     * All conditions regarding deposits go here.
     * Player cannot deposit more then the max and less then the min per transaction.
     * Player may have limits that apply only to him, if so add them here: Daily, Monthly Limits etc.
     */

    public function validate_deposit($amount, $model) {
        $this->autoRender = false;

        $user_id = CakeSession::read('Auth.User.id');
        $user = $this->getUser($user_id);
        $minDeposit = Configure::read('Settings.minWithdraw');
        $maxDeposit = Configure::read('Settings.maxWithdraw');
        
         if (!$user['User']['id'])
            throw new Exception(__('Session expired. Please sign in.', true));
        
        //Min amount
        if ($amount < $minDeposit * $user['Currency']['rate']) {
            throw new Exception(__("Min deposit is %d", $minDeposit));
        }
        //Max amount
        elseif ($amount > $maxDeposit * $user['Currency']['rate']) {
            throw new Exception(__("Max deposit is %d", $maxDeposit));
        }
        //Empty amount
        elseif ($amount <= 0 || !$amount || empty($amount)) {
            throw new Exception(__("Deposit amount is empty or 0."));
        }

        /* player cannot make more than 4 deposits without verifying KYC
         * OR
         * player cannot make a total of maxDeposit without verifying KYC, set to 1000
         * whichever comes first
         */

//        $kyc_cage = $this->KYC->kyc_cage($user);
//        $payments = $this->Payment->sumUserPayments($user_id, $amount, 'Deposit');
//        if ($user['User']['kyc_status'] <= 0 || $kyc_cage <= 0) {
//            if ($payments['count'] >= 4 || $payments['sum'] >= (Configure::read('Settings.maxDeposit') * $user['Currency']['rate'])) {//was 10
//                $this->Alert->createAlert($user['User']['id'], 'Deposit', $model, 'Total deposit amount is greater then or equal to ' . (Configure::read('Settings.maxDeposit') * $user['Currency']['rate']) . $user['Currency']['code'] . ', or player attempted ' . $payments['count'] . ' deposits without verifying KYC.', $this->__getSqlDate());
//                $kycmsg = __('In order to make additional deposits, you have to verify your account. Please go to the KYC section and follow the instructions.');
//                throw new Exception($kycmsg);
//            }
//        }
    }

    public function validate_withdraw($amount, $model) {

        $user_id = CakeSession::read('Auth.User.id');
        $user = $this->User->getUser($user_id);
        $hasPending = 0;

        if (!$user['User']['id'])
            throw new Exception(__('Session expired. Please sign in.', true));

        $minWithdraw = Configure::read('Settings.minDeposit');
        $maxWithdraw = Configure::read('Settings.maxDeposit');
        //Min amount
        if ($amount < $minWithdraw * $user['Currency']['rate']) {
            throw new Exception(__('Min withdraw is %d', $minWithdraw . '.'));
        }
        //Max amount
        elseif ($amount > $maxWithdraw * $user['Currency']['rate']) {
            throw new Exception(__('Max withdraw is %d', $maxWithdraw . '.'));
        }
        //Empty amount
        elseif ($amount <= 0 || !$amount || empty($amount)) {
            throw new Exception(__('Withdraw amount is empty or 0.'));
        }

        if (!Configure::read('Settings.withdraws'))
            throw new Exception(__('Withdrawal request is not enabled at the moment.', true));

        if ($user['User']['balance'] <= 0)
            throw new Exception(__('You have no money in your balance.', true));

        if (!empty($user['ActiveBonus']['id']))
            throw new Exception(__('Withdrawal request is not available during active bonus.', true));

        $deposits = $this->Payment->getUserPaymentsByType($user['User']['id'], 'Deposit');
        if (count($deposits) == 0) {
            $this->Alert->createAlert($user['User']['id'], 'Withdraw', $model, 'Account with no previous deposits asked for withdrawal.', $this->__getSqlDate());
            throw new Exception(__('You have no previous deposits.', true));
        }

        $withdraws = $this->Payment->getUserPaymentsByType($user['User']['id'], 'Withdraw');
        foreach ($withdraws as $withdraw) {
            if ($withdraw['Payment']['status'] == 'Pending') {
                $hasPending++;
            }
        }

        if ($hasPending > 0) {
            $this->Alert->createAlert($user['User']['id'], 'Withdraw', $model, 'Previous withdrawal transaction is not completed.', $this->__getSqlDate());
            throw new Exception(__('Previous withdrawal transaction is not completed.'));
        }

        //Check previous transactions
        $transactions = $this->query("select sum(amount) as Bets from transaction_log where transaction_type='Bet' and user_id=" . $user['User']['id']);

        $deposit = $this->query("SELECT amount FROM `payments` where user_id=" . $user['User']['id'] . " AND provider != 'Manual' ORDER BY created ASC LIMIT 1");

        if (abs($transactions[0][0]['Bets']) < (2 * $deposit[0]['payments']['amount'])) {
            $bounswon = $this->query("select sum(amount) as Bonuswin from transaction_log where transaction_type='Win' and Model='Bonus' and user_id=" . $user['User']['id']);
            if (!$bounswon[0][0]['Bonuswin']) {
                $this->Alert->createAlert($user['User']['id'], 'Withdraw', $model, 'Account with not enough bets asked for withdrawal.', $this->__getSqlDate());
                throw new Exception(__('Not enough bets.', true));
            }
        }

        if ($user['User']['kyc_status'] <= 0) {
            $this->Alert->createAlert($user['User']['id'], 'Withdraw', $model, 'Account with missing or expired KYC asked for withdrawal.', $this->__getSqlDate());
            throw new Exception(__('In order to make a withdrawal you have to verify your account. Please go to the KYC section of your account section and follow the instructions.'));
        }

        $kyc_cage = $this->KYC->kyc_cage($user);
        if ($kyc_cage <= 0) {
            $this->Alert->createAlert($user['User']['id'], 'Withdraw', $model, 'Account with expired KYC asked for withdrawal.', $this->__getSqlDate());
        }
        
        //check if all the given money are spent/bet
        
        $manual_deposit = $this->query("SELECT SUM(amount) FROM `payments` WHERE user_id=" . $user['User']['id'] . " AND provider='Manual'");
        
         if (abs($transactions[0][0]['Bets']) < $manual_deposit) {
                $this->Alert->createAlert($user['User']['id'], 'Withdraw', $model, 'Account with not enough bets asked for withdrawal.', $this->__getSqlDate());
                throw new Exception(__('Not enough bets.', true));
        }
        
        
    }

    
    
    
}
