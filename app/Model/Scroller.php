<?php
    /* 
    * To change this license header, choose License Headers in Project Properties.
    * To change this template file, choose Tools | Templates
    * and open the template in the editor.
    */


class Scroller extends AppModel
{
    /**
     * Model name
     *
     * @var $name string
     */
    public $name = 'Scroller';
    /**
     * Model schema
     *
     * @var $_schema array
     */
    public $schema = array(
        'id'        => array(
            'type'      => 'int',
            'null'      => false,
            'length' => 11            
        ),
        'title' => array(
            'type' => 'varchar',
            'null' => true,
            'length' => 255
        ),
        'text' => array(
            'type' => 'text',
            'null' => true
        ),
        'start_date' => array(
            'type' => 'datetime',
            'null' => true,
        ),
        'end_date' => array(
            'type' => 'datetime',
            'null' => true,
        ),
        'link' => array(
            'type' => 'varchar',
            'null' => true,
            'length' => 50
        ),   
        'active' => array(
            'type' => 'tinyint',
            'null' => true,
            'length' => 1
    ), 
        'order' => array(
            'type' => 'int',
            'null' => true,
            'length' => 11
    ), 
    );
    
        /**
     * List of behaviors to load when the model object is initialized.
     *
     * @var $actsAs array
     */
    public $actsAs = array(
        'Translate' => array(
            'title' => 'translations',
            'text' 
        )
    );
    
    public $locale = 'en_us';
    
        function getScrollerItems() {
        $this->locale = Configure::read('Config.language');
        $options['conditions'] = array(
            'Scroller.active' => 1,
            'Scroller.start_date <='=> $this->__getSqlDate(),
            'Scroller.end_date >'=> $this->__getSqlDate(),        
        ); 
        $options['order'] = 'Scroller.order ASC';
        return $this->find('all', $options);
        
        

    }
}