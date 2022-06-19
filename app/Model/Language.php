<?php

/**
 * Language Model
 *
 * Handles Language Data Source Actions
 *
 * @package    Languages.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
class Language extends AppModel {

    /**
     * Model name
     * @var $name string
     */
    public $name = 'Language';
    public $useTable = 'languages';

    /**
     * Model schema
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
            'length' => 10,
            'null' => false
        ),
        'iso6391_code' => array(
            'type' => 'string',
            'length' => 30,
            'null' => false
        ),
        'locale_code' => array(
            'type' => 'string',
            'length' => 30,
            'null' => false
        ),
        'locale_fallback' => array(
            'type' => 'string',
            'length' => 10,
            'null' => false
        ),
        'active' => array(
            'type' => 'int',
            'length' => 1,
            'null' => false
        ),
        'order' => array(
            'type' => 'int',
            'length' => 11,
            'null' => false
        )
    );

    const ACTIVE = 1;

    public function get() {
        $data = $this->find('all', array('conditions' => array('active' => self::ACTIVE)));
        foreach ($data as $Language) {
            if (!isset($Languages[$Language['Language']['name']])) {             //Prevent Common Languages to Show
                $Languages[$Language['Language']['name']] = $Language['Language'];
            }
        }
        return $Languages;
    }

    public function get_all() {
        $data = $this->find('all');
        foreach ($data as $language) {
            $languages[] = $language['Language'];
        }
        return $languages;
    }

    public function getList() {
        $data = $this->get();
        foreach ($data as $key => $value)
            $Languages[$key] = $key;
        return $Languages;
    }

    //return array(Language => Language);
    public function getLanguagesList() {
        $data = $this->get();
        foreach ($data as $key => $value)
            $Languages[$key] = $value['name'];
        return $Languages;
    }

    public function getIdLangueageList() {
        $options['fields'] = array('Language.id', 'Language.name');
        $options['conditions'] = array('Language.active' => self::ACTIVE);
        $options['order'] = array('field(id, 24, 1, 14, 23, 25)');

        return $this->find('list', $options);
    }
    
     public function getActive() {
        return $this->find('all', array('conditions' => array('active' => self::ACTIVE)));
    }

    public function getIndex() {
        $options['fields'] = array('Language.id', 'Language.name');
        $options['conditions'] = array('Language.id <>' => 1, 'Language.active' => self::ACTIVE);
        return $options;
    }

    public function getAdd() {
//        $i18n = I18n::getInstance();
//        $l10n = $i18n->l10n;
//        $Languages = $l10n->catalog();
//        foreach ($Languages as $key => $value) {
//            $LanguagesList[$key] = $value['name'];
//        }
//        $fields = array('Language.name' => array('label' => __('Language', true), 'type' => 'select', 'options' => $LanguagesList));
//        return $fields;


        return array(
            'Language.name' => array(
                'type' => 'text'
            ),
            'Language.ISO6391_code' => array(
                'type' => 'text',
            ),
            'Language.locale_code' => array(
                'type' => 'text',
            ),
            'Language.locale_falback' => array(
                'type' => 'text',
            ),
            'Language.order' => array(
                'type' => 'number',
            ),
            'Language.active' => array(
                'type' => 'switch',
            )
        );
    }

    public function getEdit() {
        return array(
            'Language.name' => array(
                'type' => 'text'
            ),
            'Language.ISO6391_code' => array(
                'type' => 'text',
            ),
            'Language.locale_code' => array(
                'type' => 'text',
            ),
            'Language.locale_falback' => array(
                'type' => 'text',
            ),
            'Language.order' => array(
                'type' => 'number',
            ),
            'Language.active' => array(
                'type' => 'switch',
            )
        );
    }

    
      public function getView($id) {
//        $options['fields'] = array(
//            'Language.subject',
//            'Template.content'
//        );
        $options['recursive'] = -1;
        $options['conditions'] = array(
            'Language.id' => $id
        );

        $data = $this->find('first', $options);
        return $data;
    }
    public function getActions() {
        $actions = parent::getActions();
//        unset($actions[0]);
//        unset($actions[1]);
        return $actions;
    }

    public function getTabs($params) {
        $tabs = parent::getTabs($params);
        unset($tabs['Languagesadmin_search']);
        return $tabs;
    }

    public function getById($id) {
        $lang = $this->getItem($id);
        return $lang['Language']['name'];
    }

    public function getLang($id) {
        $lang = explode("_", $this->getName($id));
        return $lang[0];
    }

    public function getLanguageName($id) {
        $lang = $this->getItem($id);
        return $lang['Language']['name'];
    }

    public function getByFlag($flag) {//is actually renamed to iso6391_code
        return $this->find('first', array('recursive' => -1, 'conditions' => array('iso6391_code' => $flag)));
    }

    public function getByName($name) {

        $options['conditions'] = array('Language.active' => self::ACTIVE, 'Language.name' => $name);
        return $this->find('first', $options);
    }

}
