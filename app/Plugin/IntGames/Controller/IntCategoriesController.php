<?php

/**
 * Front IntCategories Controller
 * Handles IntCategories Actions
 * 
 */
App::uses('AppController', 'Controller');

class IntCategoriesController extends IntGamesAppController {

    /**
     * Controller name
     * @var string
     */
    public $name = 'IntCategories';
    public $components = array(0 => 'RequestHandler');

    /**
     * Additional models
     * @var array
     */
    public $uses = array('IntGames.IntCategory');

    /**
     * Called before the controller action.
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('getCategoryBySlug');
    }

    public function getCategoryBySlug($category_slug) {
        $this->autoRender = false;

        $category_id = $this->IntCategory->getBySlug($category_slug);
        
        $response = array('status' => 'success', 'category_id' => $category_id);
        
        $this->response->type('json');
        $this->response->body(json_encode($response));
    }
    
        public function admin_translate($id, $locale = null) {
        if (!empty($this->request->data)) {
            $this->loadModel('MyI18n');
            $sqltranslate = "INSERT INTO i18n (model, foreign_key, locale, field, content) VALUES 
                ('IntCategory', {$id}, '$locale', 'name', '" . str_replace("'", "\'", $this->request->data['IntCategory']['name']) . "'),
                ON DUPLICATE KEY UPDATE field=VALUES(field), content=VALUES(content);";
            $query = $this->MyI18n->query($sqltranslate);
            $this->__setMessage(__('Item added', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->loadModel('Language');
        $locales = $this->Language->getActive();

        if ($locale != null)
            $this->IntCategory->locale = $locale;
        $this->request->data = $this->IntCategory->getItem($id);

        $fields = $this->IntCategory->getTranslate();
        $this->set('tabs', $this->IntCategory->getTabs($this->request->params));
        $this->set('currentid', $id);
        $this->set('currentlocale', $locale);
        $this->set('model', $model);
        $this->set('locales', $locales);
        $this->set('fields', $fields);
    }

}
