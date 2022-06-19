<?php

/**
 * News Model
 *
 * Handles News Data Actions
 *
 * @package    News.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
class News extends AppModel {

    /**
     * Model name
     *
     * @var string
     */
    public $name = 'News';

    /**
     * Model schema
     *
     * @var $_schema array
     */
    protected $_schema = array(
        'id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'thumb' => array(
            'type' => 'string',
            'length' => 100,
            'null' => false
        ),
        'title' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'summary' => array(
            'type' => 'text',
            'length' => null,
            'null' => false
        ),
        'content' => array(
            'type' => 'text',
            'length' => null,
            'null' => false
        ),
        'created' => array(
            'type' => 'datetime',
            'length' => null,
            'null' => false
        ),
        'modified' => array(
            'type' => 'datetime',
            'length' => null,
            'null' => false
        )
    );
//    public $actsAs = array(
//        'Translate' => array(
//            'title' => 'translations',
//            'content',
//            'summary',
//            'thumb'
//        )
//    );

    /**
     * List of validation rules.
     *
     * @var $validate array
     */
    public $validate = array(
        'title' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank'),
        'summary' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank'),
        'content' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank')
    );

    /**
     * Returns news
     *
     * @param int $limit
     * @return array
     */
    function getNews($limit = 10) {
        $options['limit'] = $limit;
        $options['order'] = 'News.created DESC';
        $data = $this->find('all', $options);
        return $data;
    }

    /**
     * Returns index fields
     *
     * @return array
     */
    public function getIndex() {
        $options['fields'] = array(
            'News.id',
            'News.title',
            'News.summary',
            'News.content',
            'News.thumb',
        );
        return $options;
    }

//    public function getTabs($params) {
//        unset($tabs);
//        return $tabs;
//    }

    /**
     * Returns search
     *
     * @return array
     */
    public function getSearch() {
        return array();
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

    /**
     * Add entry fields
     *
     * @return array
     */
    function getAdd() {
        $fields = array(
            'News.title' => array(
                'type' => 'text'
            ),
            'News.summary' => array(
                'class' => 'ckeditor',
                'type' => 'textarea',
            //'label' => array('style' => 'font-weight:bold'),
            //'required' => false
            ),
            'News.content' => array(
                'class' => 'ckeditor',
                'type' => 'textarea',
            //'label' => array('style' => 'font-weight:bold')
            ),
            'News.thumb' => array(
                'type' => 'file',
            //'div' => array('style' => 'position: relative; left: 15px; top: 18px;'),
            //'after' => '<div style="clear: both;"></div>'
            )
        
        );

        return $fields;
    }

    /**
     * Edit entry fields
     *
     * @return array
     */
    public function getEdit() {
        $fields = array(
            'News.title' => array(
                'type' => 'text'
            ),
            'News.summary' => array(
                'class' => 'ckeditor',
                'type' => 'textarea',
                'required' => false
            ),
            'News.content' => array(
                'class' => 'ckeditor',
                'type' => 'textarea',
            ),
            'News.thumb' => array(
                'type' => 'file'
            )
        );
        return $fields;
    }

    public function getTranslate() {
        $fields = array(
            'News.title' => array(
                'type' => 'text'
            ),
            'News.summary' => array(
                'class' => 'ckeditor',
                'type' => 'textarea',
                'required' => false
            ),
            'News.content' => array(
                'class' => 'ckeditor',
                'type' => 'textarea',
            ),
            'News.thumb' => array(
                'type' => 'file',
            ),
        );
        return $fields;
    }

    public function getTabs($params) {
        $tabs = array();

        $tabs[] = $this->__makeTab(__('List', true), 'admin_index', 'news', NULL, false);
        $tabs[] = $this->__makeTab(__('Create', true), 'admin_add', 'news', NULL, false);
        $tabs[] = $this->__makeTab(__('Edit', true), 'admin_edit', 'news', $params['pass'][0], false);
        $tabs[] = $this->__makeTab(__('View', true), 'admin_view', 'news', $params['pass'][0], false);
        $tabs[] = $this->__makeTab(__('Translate', true), 'admin_translate', 'news', $params['pass'][0], false);
        //var_dump($params['action']);

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
        //var_dump($tabs);
        return $tabs;
    }

}
