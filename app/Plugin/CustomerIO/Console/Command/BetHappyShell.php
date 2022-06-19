<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BetHappyShell extends Shell {

    public $uses = array('User');

    public function __construct() {
        parent::__construct();
    }

    public function main() {
        $help = "<info>BetHappyShell Shell:</info>\n"
                . "<question>Update:</question>Updating user attributes for Cistomer IO.\n";

        $this->out($help);
    }

    public function updateCustomerIOAttributes() {
        //$this->log('CustomerIO Shell');
        $this->User->updateCustomerIOAttributes();
    }

}
