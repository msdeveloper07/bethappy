<?php
/**
 * Front Mails Controller
 *
 * Handles Mails Actions
 *
 * @package    Mails
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */

class MailsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Mails';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Mail', 'User', 'UserLimits');

    /**
     * Array containing the names of components this controller uses.
     *
     * @var array
     */
    public $components = array();

    /**
     * An array containing the names of helpers this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $helpers = array();

    /**
     * Called before the controller action.
     *
     * @return void
     */
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('contact'));
    }

    /**
     * User contacts form
     *
     * @return void
     */
    public function contact() {
        if(!empty($this->request->data)) {
            $this->Mail->set($this->request->data);
            if ($this->Mail->validates()) {               
                // pick department
                switch($this->request->data['Mail']['subject']) {
                    case '0': 
                        $mail = "support@elysium.com";
                        break;
                    
                    case '1': 
                        $mail = "affiliates@elysium.com";
                        break;
                    
                    case '2': 
                        $mail = "complaints@elysium.com";
                        break;
                    
                    default: 
                        $mail = Configure::read('Settings.websiteSupportEmail');
                        break;
                }
                
                // load template
                App::import('Model', 'Template');
                $Template = new Template();
                
                // format mail
                $isSend = $this->__send($mail, 'Customer contact', $Template->createAffiliateRegistrationMail(array(
                        'name'      =>  $this->request->data['Mail']['name'],
                        'email'     =>  $this->request->data['Mail']['email'],
                        'content'   =>  $this->request->data['Mail']['message']
                    ))
                );

                if ($isSend) {
                    $this->__setMessage(__('Email successfully sent', true));
                } else {
                    $this->__setError(__('Cannot send email. Please try again.', true));
                }
            }
            $this->request->data = array();
        }
    }

    function admin_index() {
        if (!empty($this->request->data)) {
            //send mail
            $to = $this->request->data['Mail']['to'];
            $bcc = preg_split('/[;,]/', $to);
            $subject = $this->request->data['Mail']['subject'];
            $content = $this->request->data['Mail']['content'];
            $this->__send('example@example.com', $subject, $content, $bcc);
            $this->__setMessage(__('Emails successfully sent', true));
            $this->request->data = array();
        }
        $this->set('tabs', $this->Mail->getTabs($this->params));
    }

    function admin_all() {
	
	  if (!empty($this->request->data)) {
	
			if (isset($this->request->data['Download']['download'])) {
			
				$users = $this->User->getAllEmails('all');

				
			
				$report[0]['header'] = array('User ID', 'Username', 'Email', 'First Name', 'Last Name');
				$i=0;
				
				foreach ($users as $user){
					$isselfimpossed=$this->UsersLimits->isselfimpossed($user['User']['id']);
						if ($isselfimpossed==false){
							$data['id'] = $user['User']['id'];
							$data['username'] = $user['User']['username'];
							$data['email'] = $user['User']['email'];
							$data['first_name'] = $user['User']['first_name'];
							$data['last_name'] = $user['User']['last_name'];
							$report[$i]['data'][] = $data;
							$i++;
						}
				}
				
				$this->_exportAsCSV($report, 'usermaillist');
				exit();
			}
	
	
      
            //get all mails
            $bcc = $this->User->getAllEmails();
            App::uses('Validation', 'Utility');

			 foreach ($bcc as $key => $to) {
				$isselfimpossed=$this->UsersLimits->isselfimpossed($key);
					if (!Validation::email($to) || $isselfimpossed==true ) {
						unset($bcc[$key]);
					}
				}
	
            $subject = $this->request->data['Mail']['subject'];
            $content = $this->request->data['Mail']['content'];
            $this->__send('example@example.com', $subject, $content, $bcc);
            $this->__setMessage(__('Emails successfully sent'));
            $this->request->data = array();
		
        }
        $this->set('tabs', $this->Mail->getTabs($this->params));
		
		
		
		
    }
	
	
	
	    private function _exportAsCSV($data, $title) {
        $filename = $title . '.csv';
        $csvFile = fopen('php://output', 'w');

        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        foreach ($data as $report) {
            fputcsv($csvFile, $report['header'], ';', '"');
            foreach ($report['data'] as $dataRow) {
                fputcsv($csvFile, $dataRow, ';', '"');
            }
        }
        fclose($csvFile);
        die;
    }

}

?>
