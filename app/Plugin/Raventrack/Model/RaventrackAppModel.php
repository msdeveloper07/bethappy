<?php

App::uses('AppModel', 'Model');

class RaventrackAppModel extends AppModel {

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        Configure::load('Raventrack.Raventrack');

        if (Configure::read('Raventrack.Config') == 0)
            throw new Exception('Config not found', 500);

        $this->config = Configure::read('Raventrack.Config');
    }

   }
