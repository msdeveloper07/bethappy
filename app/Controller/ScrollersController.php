<?php
    /* 
    * To change this license header, choose License Headers in Project Properties.
    * To change this template file, choose Tools | Templates
    * and open the template in the editor.
    */
class ScrollersController extends AppController
{
    
    public $name = 'Scrollers';
    
    public $uses = array('Scroller');
    
    
    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow(array('Scroller'));
    }

    /**
     * Returns Scroller
     *
     * @return mixed
     * @throws ForbiddenException
     */
    public function Scroller()
    {
        if (empty($this->request->params['requested'])) {
            throw new ForbiddenException();
       }

      return $this->Scroller->getScrollerItems();

      
    }
}