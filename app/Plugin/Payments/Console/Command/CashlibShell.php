<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Cashlibshell extends Shell {

    public $uses = array('Payments.Cashlib', 'Payment');

    public function __construct() {
        parent::__construct();
    }

    public function main() {
        $help = "<info>Cashlib Shell:</info>\n"
                . "<question>checkstatus:</question>Check statuses of pending transactions\n";

        $this->out($help);
    }

    public function checkstatus() {

        $this->Cashlib->resolveStatus();
    }

}
