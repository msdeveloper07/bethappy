<?php

/**
 * Handles Languages
 *
 * Handles Languages Actions
 *
 * @package    Languages
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
class LanguagesController extends AppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'Languages';

    /**
     * An array containing the class names of models this controller uses.
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Language', 'MyI18n');

    /**
     * Called before the controller action.
     * @return void
     */
    public function beforeFilter() {
        parent::beforeFilter();
//        parent::__first_time_redirect();
        $this->Auth->allow(array('getAll', 'getLanguages', 'getLanguagesJson', 'setLanguage', 'getcurrentlanguage', 'getlanguagebyid', 'admin_toggleActive'));
    }

    public function admin_toggleActive($language_id) {
        $this->autoRender = false;
//        $this->layout = 'ajax';

        $language = $this->Language->find('first', array('conditions' => array('id' => $language_id)));
        $language['Language']['active'] = !$language['Language']['active'];
        $this->Language->save($language);
    }

    public function getAll() {
        $this->autoRender = false;

        $this->response->type("json");
        $this->response->body(json_encode($this->Language->get_all()));
    }

    public function getlanguagebyid($id) {
        $this->autoRender = false;

        $this->response->type("json");
        $this->response->body(json_encode($this->Language->getItem($id)));
    }

//was called getLanguages
    public function getAllLanguages() {
        return $this->Language->getall();
    }

    public function getcurrentlanguage() {
        if ($this->Session->read('Auth.User.id')) {                            //if user is logged in the get laguage from session
            return $this->Session->read('Auth.User.language_id');
        } else {        //if no logged in user get laguage from cookie	
            //$cur_lang_name=$this->Cookie->read('language');         //name
            $cur_lang_id = $this->Cookie->read('languageID');         //name

            if (isset($cur_lang_id) && $cur_lang_id != "") {
                return $cur_lang_id;
            } else {
                return 0;
            }
        }
    }

    //used in the list.ctp element to display active languages for translation  
    //same as getLanguagesJson
    public function getLanguages() {
        $this->autoRender = false;

        $data = $this->Language->find('all', array('recursive' => -1, 'conditions' => array('active' => 1), 'order' => array('order DESC')));
        $Languages = array();


        if ($this->Session->read('Auth.User.id')) {
            $currentLanguage = $this->Session->read('Auth.User.language_id');
        } else {
            $currentLanguage = $this->Cookie->read('languageID');
        }


        //var_dump($currentLanguage);
        foreach ($data as $langs) {
            $Languages[] = array(
                'id' => $langs['Language']['id'],
                'name' => $langs['Language']['name'],
                'locale_code' => $langs['Language']['locale_code'],
                'iso6391_code' => $langs['Language']['iso6391_code'],
                'selected' => ($currentLanguage === $langs['Language']['id'])
            );
        }
        $this->response->type('json');
        $this->response->body(json_encode($Languages));
    }

    public function getLanguagesJson() {
        $this->autoRender = false;

        $data = $this->Language->find('all', array('recursive' => -1, 'conditions' => array('active' => 1), 'order' => array('order DESC')));
        $Languages = array();


        if ($this->Session->read('Auth.User.id')) {
            $currentLanguage = $this->Session->read('Auth.User.language_id');
        } else {
            $currentLanguage = $this->Cookie->read('languageID');
        }

        $currentLanguage = (!empty($currentLanguage)) ? $currentLanguage : '1';
        
        foreach ($data as $language) {
            //var_dump($language);
            $Languages[] = array(
                'id' => $language['Language']['id'],
                'name' => $language['Language']['name'],
                'locale_code' => $language['Language']['locale_code'],
                'ISO6391_code' => $language['Language']['ISO6391_code'],
                'selected' => ($currentLanguage === $language['Language']['id'])
            );
        }
        $this->response->type('json');
        $this->response->body(json_encode($Languages));
    }

    public function setLanguage($language_id, $first_time = false) {
        $this->autoRender = false;
        $this->log('SET LANGUAGE');
        $this->log($this->Session->read('Config'));
        try {
            $language = $this->Language->findById($language_id);

            if (isset($language)) {
//                Configure::write('Config.language_iso_code', $language['Language']['ISO6391_code']);
//                Configure::write('Config.language', $language['Language']['locale_code']);
//                $this->Session->write('Config.language', $language['Language']['ISO6391_code']);
//                $this->Session->write('Config.language_locale', $language['Language']['locale_code']);

                Configure::write('Config.Language', $language['Language']);
                $this->Session->write('Config.Language', $language['Language']);


                if ($this->Session->read('Auth.User.id')) {
                    $this->Session->write('Auth.User.language_id', $language['Language']['id']);
                    $this->Session->write('Auth.User.Language', $language['Language']);
                    Configure::Write('Settings.Language', $language['Language']);
                } else {
//                    $this->Cookie->write('language', $language['Language']['name'], $encrypt = false, $expires = null);
                    $this->Cookie->write('language', $language['Language']['locale_code'], $encrypt = false, $expires = null);
                    $this->Cookie->write('languageID', $language['Language']['id'], $encrypt = false, $expires = null);
                }
                $this->log('SET LANGUAGE');
                $this->log($this->Session->read('Config'));
                $this->log(Configure::read('Config'));
                $this->log($this->Cookie->read());

                $response = array('status' => 'success', 'message' => '');
            }
        } catch (Exception $ex) {
            $response = array('status' => 'error', 'message' => $ex->getMessage());
        }
//        $this->response->type('json');
//        $this->response->body(json_encode($response));
//        if ($first_time == true) {
//            $this->redirect(array('controller' => 'pages', 'action' => 'main'));
//        } else {
//            $this->redirect($this->referer());
//        }
    }

    public function admin_index() {
        $this->set('data', $this->paginate());
        $this->set('actions', $this->Language->getActions());
    }

    //function admin_add() {
//        $i18n = I18n::getInstance();
//        $l10n = $i18n->l10n;
//        $Languages = $l10n->catalog();
//
//        foreach ($Languages as $key => $value) {
//            $LanguagesList[$key] = $value['language'];
//        }
//
//        if (!empty($this->request->data)) {
//            if (1 == 2) {
//                //add new Language
//                $LanguageId = $this->request->data['Language']['name'];
//
//                if (!$this->Language->findByName($Languages[$LanguageId]['locale'])) {
//
//                    $this->request->data['Language']['name'] = $Languages[$LanguageId]['locale'];
//                    $this->request->data['Language']['language'] = $Languages[$LanguageId]['language'];
//                    $this->request->data['Language']['language_fallback'] = $Languages[$LanguageId]['locale_fallback'];
//                } else {
//                    $this->request->data = array();
//                    $this->__setError(__('Language already exist', true));
//                }
//            } else {
//                $this->__setError(__('Please contact %s technical support team for additional languages', Configure::read('Settings.WebsitePage')));
//                $this->request->data = array();
//            }
//        }
    //parent::admin_add();
    // }

    function admin_delete($id) {
        $Language = $this->Language->getItem($id);
        $this->MyI18n->deleteAll($Language['Language']['name']);

        $model = $this->__getModel();
        if ($this->$model->delete($id)) {
            $this->__setMessage(__('Item deleted.', true));
            //$this->redirect(array('action' => 'index'));
        } else {
            $this->__setError(__('Can\'t delete item.', true));
        }
        $this->redirect($this->referer(array('action' => 'index')));
    }

}
