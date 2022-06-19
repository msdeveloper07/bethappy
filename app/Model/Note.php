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
class Note extends AppModel {

    /**
     * Model name
     *
     * @var string
     */
    public $name = 'Note';
    public $useTable = 'user_notes';

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
        'user_id' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'submitted_by' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        ),
        'content' => array(
            'type' => 'text',
            'length' => false,
            'null' => true
        ),
        'created' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        ),
        'modified' => array(
            'type' => 'string',
            'length' => 255,
            'null' => true
        )
    );
    public $belongsTo = array('User' => array('className' => 'Users', 'foreignKey' => 'user_id'), 'Author' => array('className' => 'Users', 'foreignKey' => 'submitted_by'));

    public function add_note($user_id, $note) {
        //$this->log('ADD NOTE');
        $data = array(
            'Note' => array(
                'user_id' => (int) $user_id,
                'content' => $note,
                'submitted_by' => CakeSession::read("Auth.User.id"),
                'created' => $this->__getSqlDate(),
                'modified' => $this->__getSqlDate(),
            )
        );
        //$this->log($data);
        $this->create();
        //$this->save($data);

        if ($this->save($data))
            return true;

        return false;
    }

    public function getPagination($options = array()) {

        $options['recursive'] = 1;
        $pagination = array(
            'limit' => Configure::read('Settings.itemsPerPage'),
            'order' => array('Note.created' => 'DESC'),
            'recursive' => 0
        );

        if (!empty($options)) {
            $pagination['conditions'] = $options['conditions'];
        }

        return $pagination;
    }

//
//
//    /**
//     * Create page fields
//     * @return array
//     */
//    public function getAdd() {
//        return array(
//            'Page.title',
//            'Page.active'       => $this->getFieldHtmlConfig('switch', array('label' => __('Active'))),
//            'Page.url',
//            'Page.keywords',
//            'Page.description'  =>  array('class' => 'span12 wysihtml5'),
//            'Page.content'      =>  array('class' => 'span12 ckeditor')
//        );
//    }
//    
//    /**
//     * Get page fields
//     * @return array
//     */
//    public function getIndex() {
//        return array(
//            'Page.title',
//            'Page.active'       => $this->getFieldHtmlConfig('switch', array('label' => __('Active'))),
//            'Page.url',
//            'Page.keywords',
//            'Page.description'  =>  array('class' => 'span12 wysihtml5')
//        );
//    }
//
//    /**
//     * Edit page fields
//     * @return array
//     */
//    public function getEdit() {
//        return array(
//            'Page.title',
//            'Page.active'       => $this->getFieldHtmlConfig('switch', array('label' => __('Active'))),
//            'Page.url',
//            'Page.keywords',
//            'Page.description'  =>  array('class' => 'span12 wysihtml5'),
//            'Page.content'      =>  array('class' => 'span12 ckeditor')
//        );
//    }
//
//    /**
//     * Search relations
//     * @return array
//     */
//    public function getSearch() {
//        return array(
//            'Page.title'        =>  array('type' => 'text'),
//            'Page.url'          =>  array('type' => 'text'),
//            'Page.content'      =>  array('type' => 'text'),
//            'Page.keywords'     =>  array('type' => 'text'),
//            'Page.description'  =>  array('type' => 'text')
//        );
//    }
//
//    /**
//     * Returns translate fields
//     * @return array
//     */
//    public function getTranslate() {
//        return array (
//            'title'             =>  array('type' => 'text'),
//            'content'           =>  array('class' => 'span12 ckeditor'),
//            'keywords'          =>  array('type' => 'text'),
//            'description'       =>  array('type' => 'text')
//        );
//    }
//
}
