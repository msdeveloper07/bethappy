<?php

/**
 * Template Model
 *
 * Handles Template Data Source Actions
 *
 * @package    Templates.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
class Template extends AppModel {

    /**
     * Model name
     * @var $name string
     */
    public $name = 'Template';

    /**
     * define the property directly
     */
    public $locale = 'en_us';

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
        'name' => array(
            'type' => 'string',
            'length' => 100,
            'null' => false
        ),
        'subject' => array(
            'type' => 'string',
            'length' => 100,
            'null' => false
        ),
        'content' => array(
            'type' => 'text',
            'length' => null,
            'null' => false
        )
    );

    /**
     * List of behaviors to load when the model object is initialized.
     *
     * @var $actsAs array
     */
    public $actsAs = array(
        'Translate' => array(
            'content',
            'subject' => 'translations'
        )
    );

    /**
     * List of validation rules.
     * @var array
     */
//    public $validate = array(
//        'subject' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank'),
//        'content' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank')
//    );

    /**
     * Returns actions
     *
     * @return array
     */
    public function getActions() {
        $actions = parent::getActions();
        //var_dump($actions);
//        unset($actions[2]);
        return $actions;
    }

    /**
     * Returns index fields
     *
     * @return array
     */
    public function getIndex() {
        $options['fields'] = array(
            'Template.id',
            'Template.subject',
            'Template.content'
        );
        return $options;
    }

    /**
     * Returns edit fields
     * @return array|mixed
     */
    public function getEdit() {


        return array(
            'Template.name' => array(
                'type' => 'text'
            ),
            'Template.subject' => array(
                'type' => 'text'
            ),
            'Template.content' => array(
                'type' => 'textarea',
                'class' => 'ckeditor'
            )
        );


//        $fields = array(
//            'Template.title' => array('type' => 'text'),
//            'Template.subject' => array('type' => 'text'),
//            'Template.content' => array('type' => 'textarea', 'class' => 'ckeditor')
//        );
//        return $fields;
    }

    /**
     * Returns translate fields
     * @return array
     */
    public function getTranslate() {
        return array(
            'Template.subject',
            'Template.content' => array('type' => 'textarea', 'class' => 'ckeditor')
        );
    }

    /**
     * Returns add fields
     * @return array|mixed
     */
    public function getAdd() {

        return array(
            'Template.name' => array(
                'type' => 'text'
            ),
            'Template.subject' => array(
                'type' => 'text'
            ),
            'Template.content' => array(
                'type' => 'textarea',
                'class' => 'ckeditor'
            )
        );
//        $fields = array(
//            'Template.title' => array('type' => 'text'),
//            'Template.subject' => array('type' => 'text'),
//            'Template.content' => array('type' => 'textarea', 'class' => 'ckeditor')
//        );
//        return $fields;
    }

    /**
     * Returns view fields
     *
     * @param $id
     * @return array
     */
    public function getView($id) {
        $options['fields'] = array(
            'Template.subject',
            'Template.content'
        );
        $options['recursive'] = -1;
        $options['conditions'] = array(
            'Template.id' => $id
        );

        $data = $this->find('first', $options);
        return $data;
    }

    public function createAffiliateRegistrationMail($arr) {
        if (empty($arr))
            return "Sorry!";

        $output = "";

        foreach ($arr as $key => $value) {
            $output .= '<b>' . $key . ' :</b><br/>' . $value . '<br/>';
        }
        return $output;
    }

   public function getTabs($params) {
        $tabs = array();

        $tabs[] = $this->__makeTab(__('List', true), 'admin_index', 'templates', NULL, false);
        $tabs[] = $this->__makeTab(__('Create', true), 'admin_add', 'templates', NULL, false);
        $tabs[] = $this->__makeTab(__('Edit', true), 'admin_edit', 'templates', $params['pass'][0], false);
        $tabs[] = $this->__makeTab(__('View', true), 'admin_view', 'templates', $params['pass'][0], false);
        $tabs[] = $this->__makeTab(__('Translate', true), 'admin_translate', 'templates', $params['pass'][0], false);

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
