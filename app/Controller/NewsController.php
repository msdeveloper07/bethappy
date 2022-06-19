<?php

/**
 * Handles News
 *
 * Handles News Actions
 *
 * @package    News
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
class NewsController extends AppController {

    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'News';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('News');

    /**
     * Called before the controller action.
     *
     * @return void
     */
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('view'));
    }

    public function view($id) {
        $new = $this->News->getItem($id);
        $this->set('new', $new);
    }

    function admin_add() {
        //handle upload and set data
        if (!empty($this->request->data)) {

            $client_folder = $this->News->getClientFolder();

            $image = array($this->request->data['News']['thumb']);
            $imagesUrls = $this->__uploadFiles('img/' . $client_folder . '/news', $image);

            if (array_key_exists('urls', $imagesUrls)) {
                $this->request->data['News']['thumb'] = $imagesUrls['urls'][0];
            } else {
                $this->__setError($imagesUrls['errors'][0]);
                $this->request->data['News']['thumb'] = '';
            }
        }
        parent::admin_add();
    }

    function admin_edit($id) {
        //handle upload and set data
        if (!empty($this->request->data)) {
            $image = array($this->request->data['News']['thumb']);
            $client_folder = $this->News->getClientFolder();
            if ($image[0]['error'] == 0) {
                $imagesUrls = $this->__uploadFiles('img/' . $client_folder . '/news', $image);

                if (array_key_exists('urls', $imagesUrls)) {
                    $this->request->data['News']['thumb'] = $imagesUrls['urls'][0];
                } else {
                    $this->__setError($imagesUrls['errors'][0]);
                    $this->request->data['News']['thumb'] = '';
                }
            } else {
                $slide = $this->News->getItem($id);
                $this->request->data['News']['thumb'] = $slide['News']['thumb'];
            }
        }

        parent::admin_edit($id);
    }

    public function admin_translate($id, $locale = null) {
//        var_dump($locale);
        if (!empty($this->request->data)) {
            $this->loadModel('MyI18n');
            $sqltranslate = "INSERT INTO i18n (model, foreign_key, locale, field, content) VALUES 
                ('News', {$id}, '$locale', 'title', '" . str_replace("'", "\'", $this->request->data['News']['title']) . "'),
                ('News', {$id}, '$locale', 'summary', '" . str_replace("'", "\'", $this->request->data['News']['summary']) . "')
                ('News', {$id}, '$locale', 'content', '" . str_replace("'", "\'", $this->request->data['News']['content']) . "')
                ('News', {$id}, '$locale', 'thumb', '" . str_replace("'", "\'", $this->request->data['News']['thumb']) . "')
                ON DUPLICATE KEY UPDATE field=VALUES(field), content=VALUES(content);";

            $query = $this->MyI18n->query($sqltranslate);

            $this->__setMessage(__('Item added', true));
            $this->redirect(array('action' => 'index'));
        }

        $this->loadModel('Language');
        //$locales = $this->Language->getLanguagesList();
        $locales = $this->Language->getActive();

        //unset($locales[Configure::read('Admin.defaultLanguage')]);
        if ($locale != null)
            $this->News->locale = $locale;
        $this->request->data = $this->News->getItem($id);

        $fields = $this->News->getTranslate();
        //var_dump($this->Template);
        $this->set('tabs', $this->News->getTabs($this->request->params));
        $this->set('currentid', $id);
        $this->set('currentlocale', $locale);
        $this->set('model', $model);
        $this->set('locales', $locales);
        $this->set('fields', $fields);
    }

}
