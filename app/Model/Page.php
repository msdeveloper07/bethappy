<?php

/**
 * Page Model
 *
 * Handles Page Data Source Actions
 *
 * @package    Pages.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
class Page extends AppModel {

    /**
     * Model name
     *
     * @var string
     */
    public $name = 'Page';
    public $useTable = 'pages';
    public $locale = 'en_us';

    /**
     * Model schema
     *
     * @var array
     */
    protected $_schema = array(
        'id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'title' => array(
            'type' => 'string',
            'length' => 100,
            'null' => false
        ),
        'url' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'content' => array(
            'type' => 'text',
            'length' => false,
            'null' => true
        ),
        'keywords' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'description' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'active' => array(
            'type' => 'tinyint',
            'length' => 1,
            'null' => true
        )
    );

    /**
     * List of behaviors to load when the model object is initialized.
     *
     * @var $actsAs array
     */
    public $actsAs = array(
        'Translate' => array(
            'title' => 'translations',
            'content',
            'keywords',
            'description'
        )
    );

    /**
     * List of validation rules.
     *
     * @var array
     */
    public $validate = array(
        'title' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank'),
        'url' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank'),
        'content' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank')
    );

    /**
     * Create page fields
     * @return array
     */
    public function getAdd() {
        return array(
            'Page.title',
            'Page.url',
            'Page.keywords',
            'Page.description',
            'Page.content' => array('class' => 'ckeditor', 'type' => 'textarea'),
            'Page.active' => $this->getFieldHtmlConfig('switch', array('label' => __('Active'))),
        );
    }

    /**
     * Get page fields
     * @return array
     */
    public function getIndex() {
        return array(
            'Page.title',
            'Page.active' => $this->getFieldHtmlConfig('switch', array('label' => __('Active'))),
            'Page.url',
            'Page.keywords',
            'Page.description',
            'Page.content' => array('class' => 'ckeditor')
        );
    }

    /**
     * Edit page fields
     * @return array
     */
    public function getEdit() {
        return array(
            'Page.title',
            'Page.url',
            'Page.keywords',
            'Page.description',
            'Page.content' => array('class' => 'ckeditor', 'type' => 'textarea'),
            'Page.active' => $this->getFieldHtmlConfig('switch', array('label' => __('Active'))),
        );
    }

    /**
     * Search relations
     * @return array
     */
    public function getSearch() {
        return array(
            'Page.title' => array('type' => 'text'),
            'Page.url' => array('type' => 'text'),
            'Page.content' => array('type' => 'text'),
            'Page.keywords' => array('type' => 'text'),
            'Page.description' => array('type' => 'text')
        );
    }

    /**
     * Returns translate fields
     * @return array
     */
    public function getTranslate() {
        return array(
            'Page.title' => array('type' => 'text'),
            'Page.content' => array('type' => 'textarea', 'class' => 'ckeditor'),
            'Page.description' => array('type' => 'text'),
            'Page.keywords' => array('type' => 'text'),
        );
    }

    /**
     * Returns pages urls
     * @return array
     */
    public function getUrls() {
        $options['fields'] = array('Page.url');

        $data = $this->find('all', $options);

        $urls = array();

        foreach ($data as $page)
            $urls[$page['Page']['url']] = $page['Page']['url'];

        return $urls;
    }

    /**
     * Returns pages contents
     * @return array
     */
    public function getPage($url) {
        $options['conditions'] = array(
            'Page.url' => $url,
            'Page.active' => '1'
        );
        return $this->find('first', $options);
    }

    public function getActions() {
        return array(
            0 => array(
                'name' => __('View', true),
                'action' => 'view',
                'controller' => NULL,
                'class' => 'btn btn-success btn-sm'
            ),
            1 => array(
                'name' => __('Edit', true),
                'action' => 'edit',
                'controller' => NULL,
                'class' => 'btn btn-warning btn-sm'
            ),
            2 => array(
                'name' => __('Delete', true),
                'action' => 'delete',
                'controller' => NULL,
                'class' => 'btn btn-danger btn-sm'
            ),
            3 => array(
                'name' => __('Translate', true),
                'action' => 'translate',
                'controller' => NULL,
                'class' => 'btn btn-dark btn-sm'
            ),
        );
    }

    public function getTabs($params) {
        $tabs = array();

        $tabs[] = $this->__makeTab(__('List', true), 'admin_index', 'pages', NULL, false);
        $tabs[] = $this->__makeTab(__('Create', true), 'admin_add', 'pages', NULL, false);
        $tabs[] = $this->__makeTab(__('Edit', true), 'admin_edit', 'pages', $params['pass'][0], false);
        $tabs[] = $this->__makeTab(__('View', true), 'admin_view', 'pages', $params['pass'][0], false);
        $tabs[] = $this->__makeTab(__('Translate', true), 'admin_translate', 'pages', $params['pass'][0], false);

        if ($params['action'] == 'admin_index') {
            unset($tabs[2]);
            unset($tabs[3]);
            unset($tabs[4]);
            $tabs[0]['active'] = true;
        }

        if ($params['action'] == 'admin_add') {
            $tabs[1]['active'] = true;
            unset($tabs[2]);
            unset($tabs[3]);
            unset($tabs[4]);
        }
        if ($params['action'] == 'admin_edit') {
            $tabs[2]['active'] = true;
        }
        if ($params['action'] == 'admin_view') {
            $tabs[3]['active'] = true;
        }
        if ($params['action'] == 'admin_translate') {
            $tabs[4]['active'] = true;
        }

        return $tabs;
    }

}
