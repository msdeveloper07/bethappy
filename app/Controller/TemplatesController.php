<?php

/**
 * Front Templates Controller
 *
 * Handles Templates Actions
 *
 * @package    Templates
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link      
 */
class TemplatesController extends AppController {

    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Templates';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Template');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('admin_index'));
    }

    public function admin_index() {
        parent::admin_index();
        $this->set('data', $this->paginate());
        $this->set('actions', $this->Template->getActions());
        $this->set('tabs', $this->Template->getTabs($this->request->params));
    }

    public function admin_translate($id, $locale = null) {
//        var_dump($locale);
        if (!empty($this->request->data)) {
            $this->loadModel('MyI18n');
            $sqltranslate = "INSERT INTO i18n (model, foreign_key, locale, field, content) VALUES 
                ('Template', {$id}, '$locale', 'subject', '" . str_replace("'", "\'", $this->request->data['Template']['subject']) . "'),
                ('Template', {$id}, '$locale', 'content', '" . str_replace("'", "\'", $this->request->data['Template']['content']) . "')
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
            $this->Template->locale = $locale;
        $this->request->data = $this->Template->getItem($id);

        $fields = $this->Template->getTranslate();

        $this->set('tabs', $this->Template->getTabs($this->request->params));
        $this->set('currentid', $id);
        $this->set('currentlocale', $locale);
        $this->set('model', $model);
        $this->set('locales', $locales);
        $this->set('fields', $fields);
    }

}
