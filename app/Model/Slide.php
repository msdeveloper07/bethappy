<?php

/**
 * Slide Model
 * Handles Slide Data Source Actions
 * @package    Slides.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
class Slide extends AppModel {

    /**
     * Model name
     * @var string
     */
    public $name = 'Slide';

    /**
     * Model schema
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
        'description' => array(
            'type' => 'text',
            'length' => false,
            'null' => false
        ),
        'start_date' => array(
            'type' => 'datetime',
            'null' => true,
        ),
        'end_date' => array(
            'type' => 'datetime',
            'null' => true,
        ),
        'url' => array(
            'type' => 'string',
            'length' => 255,
            'null' => false
        ),
        'image' => array(
            'type' => 'string',
            'length' => 100,
            'null' => false
        ),
        'image_mobile' => array(
            'type' => 'string',
            'length' => 100,
            'null' => false
        ),
        'active' => array(
            'type' => 'tinyint',
            'length' => 1,
            'null' => false
        ),
        'order' => array(
            'type' => 'int',
            'length' => 1,
            'null' => false
        ),
    );

    /**
     * List of behaviors to load when the model object is initialized.
     * @var $actsAs array
     */
    public $actsAs = array();

    /**
     * List of validation rules.
     * @var array
     */
//    public $validate = array(
//        'title' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank'),
//        'description' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank'),
//        'image' => array('rule' => 'notEmpty', 'message' => 'This field cannot be left blank')
//    );

    /**
     * Returns slides
     * @return array
     */
    public function getSlides() {

        $this->locale = 'en_us';
        //$this->log('getSlider');
        $slides = $this->find('all', array('conditions' => array('Slide.active' => 1), 'order' => 'Slide.order ASC'));
        $data = array();
        foreach ($slides as $key => $slide) {

            $title = $this->query("SELECT content FROM i18n WHERE foreign_key = " . $slide['Slide']['id'] . " and field = 'title' AND model = 'Slide' and locale = '" . Configure::read('Config.language') . "' limit 1; ");
            $description = $this->query("SELECT content FROM i18n WHERE foreign_key = " . $slide['Slide']['id'] . " and field = 'description' AND model = 'Slide' and locale = '" . Configure::read('Config.language') . "' limit 1; ");
            $image = $this->query("SELECT content FROM i18n WHERE foreign_key = " . $slide['Slide']['id'] . " and field = 'image' AND model = 'Slide' and locale = '" . Configure::read('Config.language') . "' limit 1; ");
            $image_mobile = $this->query("SELECT content FROM i18n WHERE foreign_key = " . $slide['Slide']['id'] . " and field = 'image_mobile' AND model = 'Slide' and locale = '" . Configure::read('Config.language') . "' limit 1; ");
            $url = $this->query("SELECT content FROM i18n WHERE foreign_key = " . $slide['Slide']['id'] . " and field = 'url' AND model = 'Slide' and locale = '" . Configure::read('Config.language') . "' limit 1; ");

            $data[$key]['Slide']['id'] = $slide['Slide']['id'];
            $data[$key]['Slide']['title'] = (!empty($title[0]['i18n']['content']) ? $title[0]['i18n']['content'] : $slide['Slide']['title']);
            $data[$key]['Slide']['description'] = (!empty($description[0]['i18n']['content']) ? $description[0]['i18n']['content'] : $slide['Slide']['description']);
            $data[$key]['Slide']['image'] = (!empty($image[0]['i18n']['content']) ? $image[0]['i18n']['content'] : $slide['Slide']['image']);
            $data[$key]['Slide']['image_mobile'] = (!empty($image_mobile[0]['i18n']['content']) ? $image_mobile[0]['i18n']['content'] : $slide['Slide']['image_mobile']);
            $data[$key]['Slide']['url'] = (!empty($url[0]['i18n']['content']) ? $url[0]['i18n']['content'] : $slide['Slide']['url']);


            $data[$key]['Slide']['start_date'] = $slide['Slide']['start_date'];
            $data[$key]['Slide']['end_date'] = $slide['Slide']['start_date'];
            $data[$key]['Slide']['order'] = $slide['Slide']['order'];
            $data[$key]['Slide']['active'] = $slide['Slide']['active'];
        }

        //$this->log($data);
        return $data;
    }

    public function getActions() {
        $actions = parent::getActions();
        $actions[3] = array(
            'name' => __('Translate', true),
            'action' => 'translate',
            'controller' => 'slides',
            'class' => 'btn btn-sm btn-dark');

//        unset($actions[2]);
        return $actions;
    }

    /**
     * Search fields
     * @return array
     * 
     */
    public function getSearch() {
        return array(
            'Slide.id' => array(
                'type' => 'number',
                'label' => __('Identity number'),
                'min' => 1
            ),
            'Slide.title' => array(
                'type' => 'text',
                'label' => __('Words in title')
            ),
            'Slide.description' => array(
                'type' => 'text',
                'label' => __('Words in description')
            ),
            'Slide.url' => array(
                'type' => 'text',
                'label' => __('Url link')
            ),
            'Slide.active' => array(
                'style' => '',
                'div' => 'control-group',
                'before' => '<div class="controls" style="margin-top: 15px"><label style="position: absolute; top: 0px;">Active</label><div style="margin-top: 12px;" class="transition-value-toggle-button">',
                'type' => 'checkbox',
                'class' => 'toggle',
                'after' => '</div></div>',
                'label' => false
            )
        );
    }

    public function getIndex() {
        $options['fields'] = array(
            'Slide.id',
            'Slide.title',
            'Slide.description',
            'Slide.image',
            'Slide.image_mobile',
            'Slide.url',
            'Slide.start_date',
            'Slide.end_date',
            'Slide.active',
        );
        return $options;
    }

    /**
     * Add entry fields
     * @return array
     */
    function getAdd() {
        return array(
            'Slide.title' => array(
                'type' => 'text'
            ),
            'Slide.description' => array(
                'class' => 'ckeditor',
                'type' => 'textarea'
            ),
            'Slide.image' => array(
                'type' => 'file',
//                'div'       =>  array('style' => 'position: relative; left: 15px; top: 18px;'),
            //'after' => '<div style="clear: both;"></div>'
            ),
            'Slide.image_mobile' => array(
                'type' => 'file',
//                'div'       =>  array('style' => 'position: relative; left: 15px; top: 18px;'),
            //'after' => '<div style="clear: both;"></div>'
            ),
//            'Slide.active' => array(
//                'type' => 'switch',
//            ),
            'Slide.url' => array(
                'type' => 'text'
            ),
            'Slide.start_date' => array(
                'class' => 'datetimepicker-filter',
                'type' => 'text',
            ),
            'Slide.end_date' => array(
                'class' => 'datetimepicker-filter',
                'type' => 'text',
            ),
            'Slide.active' => $this->getFieldHtmlConfig('switch', array('label' => __('Active'))),
//            'Slide.active' => array(
////                'div' => 'control-group',
////                'before' => '<div class="controls" style="margin-top: 15px"><div class="transition-value-toggle-button">',
//                'type' => 'checkbox',
////                'class' => 'toggle',
////                'after' => '</div></div>',
//                'label' => false
//            )
        );
    }

    /**
     * Edit entry fields
     * @return array
     */
    public function getEdit() {
        return array(
            'Slide.title' => array(
                'type' => 'text'
            ),
            'Slide.description' => array(
                'class' => 'ckeditor',
                'type' => 'textarea',
            ),
//            'Slide.start_date' => $this->getFieldHtmlConfig('date', array('label' => 'End Date')),
//            'Slide.end_date' => $this->getFieldHtmlConfig('date', array('label' => 'End Date')),
//            'Slide.url' => array(
////                'style'     =>  '',
//                'type' => 'text',
////                'div' =>  array('style' => 'margin-top: 15px; float: left;')
//            ),
            'Slide.image' => array(
                'type' => 'file',
//                'div'       =>  array('style' => 'position: relative; left: 15px; top: 18px;'),
//                'after'     =>  '<div style="clear: both;"></div>'
            ),
            'Slide.image_mobile' => array(
                'type' => 'file',
//                'div'       =>  array('style' => 'position: relative; left: 15px; top: 18px;'),
//                'after'     =>  '<div style="clear: both;"></div>'
            ),
            'Slide.url' => array(
                'type' => 'text'
            ),
            'Slide.start_date' => array(
                'class' => 'datetimepicker-filter',
                'type' => 'text',
            ),
            'Slide.end_date' => array(
                'class' => 'datetimepicker-filter',
                'type' => 'text',
            ),
            'Slide.active' => array(
//                'style'     =>  '',
//                'div'       =>  'control-group',
//                'before'    =>  '<div class="controls" style="margin-top: 15px"><div class="transition-value-toggle-button">',
                'type' => 'switch',
//                'class'     => 'toggle',
//                'after'     =>  '</div></div>',
//                'label'     =>  false
            )
        );
    }

    public function getTranslate() {
        return array(
            'Slide.title' => array(
                'type' => 'text'
            ),
            'Slide.description' => array(
                'class' => 'ckeditor',
                'type' => 'textarea',
            ),
            'Slide.image' => array(
                'type' => 'file',
            ),
            'Slide.image_mobile' => array(
                'type' => 'file',
            )
        );
    }

    public function getTabs($params) {
        $tabs = array();

        $tabs[] = $this->__makeTab(__('List', true), 'admin_index', 'slides', NULL, false);
        $tabs[] = $this->__makeTab(__('Create', true), 'admin_add', 'slides', NULL, false);
        $tabs[] = $this->__makeTab(__('Edit', true), 'admin_edit', 'slides', $params['pass'][0], false);
        $tabs[] = $this->__makeTab(__('View', true), 'admin_view', 'slides', $params['pass'][0], false);
        $tabs[] = $this->__makeTab(__('Translate', true), 'admin_translate', 'slides', $params['pass'][0], false);

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
