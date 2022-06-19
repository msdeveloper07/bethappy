<?php

class IntroController extends AppController
{

	public $name = 'Intro';
	public $uses = array('Intro');
	
	function beforeFilter()
	{
		//parent::beforeFilter();
		$this->Auth->allow(array('index'));
	}
	
	
	public function index()
	{
		$this->layout = 'intro';
	}
}	
?>