<?php

/**
 * Handles Notes
 *
 * Handles Notes Actions
 *
 * @package    Notes
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
class NotesController extends AppController {

    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Notes';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Note', 'User');

    /**
     * Called before the controller action.
     *
     * @return void
     */
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('admin_user_notes', 'admin_add'));
    }

    //Used in BO to display all notes
    public function admin_user_notes($user_id) {

        $options = array();
        $options['conditions']['Note.user_id'] = $user_id;
        $this->paginate = $this->Note->getPagination($options);
        $this->set('data', $this->paginate());
        $this->set('user_id', $user_id);



//        $data = $this->Note->find('all', array('conditions' => array('user_id' => $user_id), 'recursive'=>0));
//        $this->set('user_id', $user_id);
//        $this->set('data', $data);
    }

    function admin_add() {
        //$this->log('Admin note add');
        //$this->log($this->request);
        if (!empty($this->request->data)) {
            $request = $this->request->data;
            if ($this->Note->add_note($request['user_id'], $request['note'])) {
                $this->__setMessage(__('Note saved.', true));
            } else {

                $this->__setError(__('Note cannot be saved.', true));
            }
        }
        parent::admin_add();
    }

//    function admin_edit($id) {
//        //handle upload and set data
//        if (!empty($this->request->data)) {
//            $image = array($this->request->data['Notes']['thumb']);
//
//            if ($image[0]['error'] == 0) {
//                $imagesUrls = $this->__uploadFiles('img/news', $image);
//
//                if (array_key_exists('urls', $imagesUrls)) {
//                    $this->request->data['Notes']['thumb'] = $imagesUrls['urls'][0];
//                } else {
//                    $this->__setError($imagesUrls['errors'][0]);
//                    $this->request->data['Notes']['thumb'] = '';
//                }
//            } else {
//                $slide = $this->Notes->getItem($id);
//                $this->request->data['Notes']['thumb'] = $slide['Notes']['thumb'];
//            }
//        }
//
//        parent::admin_edit($id);
//    }
}
