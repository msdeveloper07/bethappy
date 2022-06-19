<?php

/**
 * App Model
 *
 * Handles App Data Source Actions
 *
 * @package    App.Model
 * @author     
 * @copyright  
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       
 */
App::uses('Model', 'Model');
App::uses('AppController', 'Controller');

class AppModel extends Model {

      public function getClientFolder() {
        return 'casino/bet-happy';
    }
    /**
     * Returns model name
     * @return null|string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Returns model plural name
     * @return string
     */
    public function getPluralName() {
        return Inflector::pluralize($this->name);
    }

    /**
     * MySQL types to HTML4/5
     * @var array
     */
    protected $_types = array(
        'int' => 'number',
        'tinyint' => 'number',
        'text' => 'text',
        'string' => 'text'
    );

    /**
     * Setups sylinks
     * @param string $theme
     */
    public function setupThemeSymlink($theme = '') {
        $themeWebRoot = App::themePath($theme) . WEBROOT_DIR;
        $appWebRoot = WWW_ROOT . 'theme' . DS . $theme;

//        if (!file_exists($appWebRoot))
//            symlink($themeWebRoot, $appWebRoot);
    }

    /**
     * Returns sql date format
     * @param null $date
     * @return bool|string
     */
    public function __getSqlDate($date = null) {
        if (isset($date))
            return date('Y-m-d H:i:s', $date);
        return gmdate('Y-m-d H:i:s');
    }

    public function getView($id) {
        $options['fields'] = array();
        $options['recursive'] = -1;
        $options['conditions'] = array($this->name . '.id' => $id);

        foreach ($this->_schema as $key => $value) {
            if (($key != 'id') && ($key != 'order'))
                $options['fields'][] = $this->name . '.' . $key;
        }
        return $this->find('first', $options);
    }

    public function getIndex() {
        $fields = array();
        foreach ($this->_schema as $key => $value) {
            if (($key != 'id') && ($key != 'order'))
                $fields[] = $this->name . '.' . $key;
        }
        return $this->getBelongings($fields);
    }

    public function getIndexFields() {
        $options['fields'] = array();
        foreach ($this->_schema as $key => $value) {
            if ($key != 'order')
                $options['fields'][] = $this->name . '.' . $key;
        }
        return $options['fields'];
    }

    /**
     * Model search fields wrapper
     * @return array
     */
    public function getSearch() {
        if (!$this->_schema || !is_array($this->_schema))
            return array();

        $type = 'text';
        $options['fields'] = array();

        foreach ($this->_schema as $key => $value) {
            if (($key == 'id') || ($key == 'order'))
                continue;

            if (isset($this->_types[$value['type']]))
                $type = $this->_types[$value['type']];

            $options['fields'][$this->name . '.' . $key] = array('type' => $type);
        }
        return $options['fields'];
    }

    public function getAdd() {
        $fields = array();
        foreach ($this->_schema as $key => $value) {
            if (($key != 'id') && ($key != 'order'))
                $fields[] = $this->name . '.' . $key;
        }
        return $this->getBelongings($fields);
    }

    /**
     * Returns scaffold edit fields
     * @return mixed
     */
    public function getEdit() {
        $fields = array();

        foreach ($this->_schema as $key => $value) {
            if (($key != 'id') && ($key != 'order'))
                $fields[] = $this->name . '.' . $key;
        }
        return $this->getBelongings($fields);
    }

    public function getTranslate() {
        $first = true;
        $args['fields'][] = 'id';
        $fields = $this->actsAs['Translate'];
        foreach ($fields as $key => $value) {
            if ($first) {
                $first = false;
                $args['fields'][] = $key;
            } else {
                $args['fields'][] = $value;
            }
        }
        return $args['fields'];
    }

    /**
     * Assigns additional belongings
     * @param $fields
     * @return mixed
     */
    public function getBelongings($fields) {
        foreach ($fields as $key => $value) {
            // If passed field with additional params
            if (is_array($value))
                $value = $key;

            $model = $this->belongs($value);
            if ($model !== false) {
                unset($fields[$key]);

                if ($model != 'Affiliate') {
                    $list = array(0 => __('Please select')) + $this->{$model}->find('list', array('fields' => array('id', 'name')));
                } else {
                    $list = array(0 => __('Please select')) + $this->{$model}->find('list', array('fields' => array('id', 'affiliate_custom_id')));
                }
                $fields[$value] = array('type' => 'select', 'options' => $list);
            }
        }
        return $fields;
    }

    /**
     * Returns id names
     * @param $data
     * @return array
     */
    public function getIdNames($data) {
        if (empty($data))
            return $data;

        $newData = $data;

        if (isset($data[$this->name]))
            $data = array('0' => $data);

        foreach ($data as &$row) {
            foreach ($row[$this->name] as $key => $value) {
                $model = $this->belongs($key);
                if ($model != false) {
                    $options['recursive'] = 0;
                    $options['conditions'] = array($model . '.id' => $value);

                    $parent = ($this->{$model}->find('first', $options));

                    if (isset($parent[$model]['name'])) {
                        $row[$this->name][$key] = $parent[$model]['name'];
                    } else if (isset($parent[$model]['username'])) {
                        $row[$this->name][$key] = $parent[$model]['username'];
                    }
                }
                if ($key == 'active' || $key == 'mobile') {
                    if ($value == 1) {
                        $row[$this->name][$key] = __('Yes', true);
                    } else {
                        $row[$this->name][$key] = __('No', true);
                    }
                }
            }
        }

        if (isset($newData[$this->name]))
            $data = $data[0];
        return $data;
    }

    /**
     * @param string $value
     * @return bool|string
     */
    public function belongs($value) {
        $value = str_replace($this->name . '.', '', $value);

        $belonging = str_replace('_id', '', $value);
        $belonging = ucfirst($belonging);
        if (isset($this->belongsTo[$belonging]))
            return $belonging;
        return false;
    }

    /**
     * Returns fields schema type by field name
     * @param $fieldName
     * @return null
     */
    public function getFieldType($fieldName) {
        $nameParts = explode('.', $fieldName);

        if (count($nameParts) == 2)
            $fieldName = $nameParts[1];
        if (isset($this->_schema[$fieldName]['type']))
            return $this->_schema[$fieldName]['type'];

        return null;
    }

    /**
     * Returns field escaped condition
     * @param null $fieldType
     * @param $fieldName
     * @return array
     */
    public function prepareField($fieldType = null, $fieldName) {
        $condition = array();

        if ($fieldType == null)
            $fieldType = $this->getFieldType($fieldName);

        switch ($fieldType) {
            case 'int':
            case 'tinyint':
            case 'bigint':
            case 'enum':
                $condition = array($fieldName . ' = ?' => array($fieldName));
                break;
            case 'string':
            case 'datetime':
                $condition = array($fieldName . ' LIKE ?' => array($fieldName));
                break;
        }
        return $condition;
    }

    /**
     * Returns search fields
     * @param $data
     * @return array
     */
    public function getSearchFields($data) {
        $conditions = array();
        $searchFields = $this->getSearch();

        foreach ($data[$this->name] as $key => $value) {
            if (isset($searchFields[$this->name . '.' . $key])) {
                $conditions[$this->name . '.' . $key] = $this->prepareField(null, $this->name . '.' . $key);
            }
        }
        return $conditions;
    }

    /**
     * Returns field part
     *
     * @param $field
     * @param $type
     * @return null
     * @throws Exception
     */
    public function getFieldData($field, $type) {
        $fieldParts = explode('.', $field);

        if (count($fieldParts) == 1)
            $fieldParts = array(0 => null, 1 => $fieldParts);

        switch ($type) {
            case 'model':
                return $fieldParts[0];
                break;
            case 'name':
                return $fieldParts[1];
                break;
        }

        return null;
    }

    /**
     * Assigns escaped value from fieldValues to fieldMap
     *
     * @param $fieldMap
     * @param $fieldsValues
     * @param array $skipValues
     * @return array
     */
    public function assignFieldValues($fieldMap, $fieldsValues, $skipValues = array('')) {
        $result = array();

        foreach ($fieldMap AS $query => $fields) {
            foreach ($fields AS $fieldIndex => $field) {
                if (isset($fieldsValues[$this->getFieldData($field, 'name')])) {
                    if (in_array($fieldsValues[$this->getFieldData($field, 'name')], $skipValues))
                        return array();

                    $fieldValue = $fieldsValues[$this->getFieldData($field, 'name')];

                    /** TODO: on second diff value put in external function */
                    if (strpos(strtolower($query), 'like') !== false)
                        $fieldValue = '%' . $fieldValue . '%';

                    $result[$query][$fieldIndex] = $fieldValue;
                }
            }
        }
        return $result;
    }

    /**
     * Builds and returns search conditions
     * @param $data
     * @return array
     */
    function getSearchConditions($data) {
        if (!isset($data[$this->name]))
            return array();

        $searchFields = $this->getSearch();

        $searchFieldsConditions = $this->getSearchFields($data);

        $missingConditions = array_diff(array_keys($searchFields), array_keys($searchFieldsConditions));

        foreach ($missingConditions AS $missingCondition) {
            $fieldData = $this->prepareField(null, $missingCondition);

            if (!empty($fieldData))
                $searchFieldsConditions[$missingCondition] = $fieldData;
        }

        $conditions = array();

        foreach ($data[$this->name] as $fieldName => $fieldValue) {
            /** If we don't have matched the field */
            if (!isset($searchFields[$this->name . '.' . $fieldName]))
                continue;

            /** If we don't have map for this the field */
            if (empty($searchFieldsConditions) || !isset($searchFieldsConditions[$this->name . '.' . $fieldName])) {
                continue;
            }

            $fieldMap = $searchFieldsConditions[$this->name . '.' . $fieldName];
            $conditions[] = $this->assignFieldValues($fieldMap, $data[$this->name]);
        }
        return array_filter($conditions);
    }

    /**
     * Returns scaffold actions list
     * @return array
     */
    public function getActions() {
        return array(
            0 => array(
                'name' => __('View', true),
                'controller' => NULL,
                'action' => 'view',
                'class' => 'btn btn-success btn-sm'
            ),
            1 => array(
                'name' => __('Edit', true),
                'controller' => NULL,
                'action' => 'edit',
                'class' => 'btn btn-warning btn-sm'
            ),
            2 => array(
                'name' => __('Delete', true),
                'controller' => NULL,
                'action' => 'delete',
                'class' => 'btn btn-danger btn-sm'
            )
        );
    }

    public function getItem($id, $recursive = -1, $contain = null) {
        if ($this->primaryKey == null || empty($this->primaryKey) || !$this->primaryKey)
            $this->primaryKey = 'id';
        $options['conditions'] = array($this->name . '.' . $this->primaryKey => $id);

        if (isset($contain)) {
            $this->contain($contain);
        } else {
            $options['recursive'] = $recursive;
        }
        return $this->find('first', $options);
    }

    public function findItem($conditions, $recursive = -1, $contain = null, $limit = null) {
        foreach ($conditions as $key => $value) {
            if (!empty($value))
                $options['conditions'][$this->name . '.' . $key . ' LIKE'] = '%' . $value . '%';
        }
        if (isset($limit))
            $options['limit'] = $limit;
        if (isset($contain)) {
            $this->contain($contain);
        } else {
            $options['recursive'] = $recursive;
        }
        return $this->find('all', $options);
    }

    /**
     * Returns count
     * @param array $options
     * @return array
     */
    public function getCount($options = array()) {
        return $this->find('count', array('conditions' => $options));
    }

    public function getParentId($id) {
        if (isset($this->belongsTo)) {
            $options['conditions'] = array($this->name . '.id' => $id);

            $options['recursive'] = 0;
            $parent = $this->find('first', $options);
            $belongsTo = array_keys($this->belongsTo);
            $belongsTo = $belongsTo[0];
            return $parent[$belongsTo]['id'];
        }
        return NULL;
    }

    public function getParent() {
        if (!empty($this->belongsTo)) {
            $belongsTo = array_keys($this->belongsTo);
            $belongsTo = $belongsTo[0];
            return $belongsTo;
        }
        return NULL;
    }

    public function getValidation() {
        return $this->validate;
    }

    /**
     * Returns model name
     * @param $name
     * @return string
     */
    public function getModelName($name) {
        if ($name == 'App')
            return $this->name;

        return Inflector::singularize($name);
    }

    /**
     * Returns admin tabs
     * @param $params
     * @return array
     */
    public function getTabs($params) {
        $tabs = array();

        $tabs[$params['controller'] . 'admin_index'] = array(
            'name' => __('List', true),
            'url' => (array('controller' => $params['controller'], 'action' => 'index'))
        );

        $tabs[$params['controller'] . 'admin_add'] = array(
            'name' => __('Create', true),
            'url' => (array('controller' => $params['controller'], 'action' => 'add'))
        );

        if (isset($params['action'])) {
            if (($params['action'] == 'admin_view') || ($params['action'] == 'admin_edit')) {
                $tabs[$params['controller'] . 'admin_view'] = array(
                    'name' => __('View', true),
                    'url' => (array('controller' => $params['controller'], 'action' => 'view', $params['pass'][0]))
                );
                $tabs[$params['controller'] . 'admin_edit'] = array(
                    'name' => __('Edit', true),
                    'url' => (array('controller' => $params['controller'], 'action' => 'edit', $params['pass'][0]))
                );
            }
        }
        if (!isset($tabs[$params['controller'] . $params['action']]['name'])) {
            $tabs[$params['controller'] . $params['action']]['name'] = Inflector::humanize(str_replace('admin_', '', $params['action']));
        }
        $tabs[$params['controller'] . $params['action']]['active'] = 1;
        $tabs[$params['controller'] . $params['action']]['url'] = '#';

        return $tabs;
    }

    public function __makeTab($name, $action, $controller = NULL, $var = NULL, $active = false) {
        $url = array('name' => $name, 'url' => (array('controller' => $controller, 'action' => $action, $var)));

        if ($active)
            $url['active'] = true;
        return $url;
    }

    public function isOrderable() {
        $schema = $this->schema();
        if (isset($schema['order']))
            return true;
        return false;
    }

    public function moveUp($id) {
        $model = $this->name;
        $options['conditions'] = array($model . '.id' => $id);

        $item = $this->find('first', $options);
        $order = $item[$model]['order'];
        $options['conditions'] = array($model . '.order <' => $order, $model . '.active ' => '1');

        $options['order'] = $model . '.order DESC';
        $item2 = $this->find('first', $options);
        if (empty($item2))
            return;
        $item[$model]['order'] = $item2[$model]['order'];
        $item2[$model]['order'] = $order;

        /*         * **ORDER FIX INCASE ORDER IS THE SAME*** */
        if ($item2[$model]['order'] == $item[$model]['order'])
            $item2[$model]['order'] ++;
        /*         * **ORDER FIX INCASE ORDER IS THE SAME*** */

        $this->save($item);
        $this->save($item2);
    }

    public function moveDown($id) {
        $model = $this->name;
        $options['conditions'] = array($model . '.id' => $id);

        $item = $this->find('first', $options);
        $order = $item[$model]['order'];

        $options['conditions'] = array($model . '.order >' => $order);

        $options['order'] = $model . '.order ASC';
        $item2 = $this->find('first', $options);

        if (empty($item2))
            return;

        $item[$model]['order'] = $item2[$model]['order'];
        $item2[$model]['order'] = $order;
        $this->save($item);
        $this->save($item2);
    }

    public function findLastOrder() {
        $model = $this->name;
        $options['order'] = $model . '.order DESC';
        $item = $this->find('first', $options);
        if (!empty($item))
            return $item[$model]['order'];
        return 0;
    }

    public function getSqlDate() {
        return date('Y-m-d H:i:s');
    }

    /**
     * Returns theme field types
     * @param $fieldType
     * @param $data
     * @return array
     */
    public function getFieldHtmlConfig($fieldType, $data = array()) {
        $id = '';
        $label = null;
        $div = null;
        $style = '';
        $defaultDate = null;
        $defaultValue = null;

        $options = array();

        if (isset($data['defaultValue']))
            $defaultValue = $data['defaultValue'];

        if (isset($data['label']))
            $label = $data['label'];

        if (isset($data['div']))
            $div = $data['div'];

        if (isset($data['id']))
            $id = $data['id'];

        if (isset($data['style']))
            $style = $data['style'];

        if (isset($data['defaultDate']))
            $defaultDate = $data['defaultDate'];

        if (isset($data['options']))
            $options = $data['options'];

        $fields = array(
            /** Date type */
            'date' => array(
                'div' => $div != null ? $div : false,
                'class' => 'form-control datetimepicker-filter',
//                'before' => '<div class="input-group">',
                'type' => 'text',
//                'after' => '<div class="input-group-append">
//    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
//  </div></div>',
                'data-date-format' => 'yyyy-mm-dd',
                'label' => $label != null ? $label : '',
                'defaultDate' => $defaultDate
            ),
            'datetime' => array(
//                'div' => 'control-group',
                'div' => false,
                'class' => 'form-control datetimepicker-filter',
//                'before' => '<div class="controls">',
                'type' => 'text',
//                'after' => '</div>',
                'data-date-format' => 'yyyy-mm-dd',
                'label' => $label != null ? $label : ''
            ),
            /** Number type */
            'number' => array(
                'type' => 'number',
                'min' => 0,
                'step' => 'any',
                'label' => $label != null ? __($label) : '',
                'class' => 'form-control',
            ),
            /** Currency type */
            'currency' => array(
//                'style' => $style != '' ? $style : 'width: 145px;',
//                'div' => 'control-group',
//                'before' => '<div class="controls" style=""><label for="' . $id . '" style="position: relative; top: 0px;">' . __($label) . '</label><div class="input-prepend input-append"><span class="add-on"></span>',
                'type' => 'number',
//                'after' => '<span class="add-on"></span></div></div>',
                'label' => false,
                'class' => 'form-control',
            ),
            /** Switch type */
            'switch' => array(
//                'style' => '',
//                'div' => 'control-group',
//                'before' => '<div style="' . $style . '" class="controls"><label for="' . $id . '">' . __($label) . '</label><div class="transition-value-toggle-button">',
                'before' => '<div class="custom-control custom-switch">',
                'type' => 'checkbox',
                'class' => 'custom-control-input custom-switch-input',
                'after' => '<label class="custom-control-label" for="' . $id . '"></label></div>',
                'label' => false,
                'id' => $id != null ? $id : '',
            ),
            /** Select type */
            'select' => array(
                'type' => 'select',
                'options' => $options,
                'label' => $label != null ? __($label) : '',
                'class' => 'form-control',
                'div' => $div != null ? $div : false,
                'value' => $defaultValue != null ? $defaultValue : false,
            ),
//            'checkbox' => array(
//                'type' => 'checkbox',
//                'multiple' => 'checkbox',
//                'options' => $options,
//                'label' => $label != null ? __($label) : '',
//                'class' => 'form-control',
//            ),
            'auto-complete' => array(
                'type' => 'select',
                'data-provide' => 'typeahead',
                'data-items' => '[' . implode(',', $options) . ']'
            ),
            'text' => array(
//                'style' => $style != '' ? $style : 'width: 145px;',
//                'div' => 'control-group',
//                'before' => '<div class="controls" style=""><label for="' . $id . '" style="position: relative; top: 0px;">' . __($label) . '</label><div class="input-prepend input-append"><span class="add-on"></span>',
                'type' => 'text',
//                'after' => '<span class="add-on"></span></div></div>',
                'label' => $label != null ? $label : '',
                'class' => 'form-control',
            ),
        );

        if ($data['empty'])
            $fields['select']['empty'] = $data['empty'];

        if (!isset($fields[$fieldType]))
            return array();

        return $fields[$fieldType];
    }

    public function getCssData() {
        return '<style type="text/css">'
                . 'body {margin:0;}'
                . 'div, h1, h2, h3, h4, h5 {display: block;}'
                . '*, *:before, *:after {box-sizing: border-box;}'
                . '#page-wrapper {width: 100%;}'
                . '.row {margin-right: -15px; margin-left: -15px;}'
                . '.row:before {display:table; content:" ";}'
                . '.clearfix:after, .container:after, .container-fluid:after, .row:after, .btn-toolbar:after, .nav:after, .navbar:after, .navbar-header:after, .navbar-collapse:after, .pager:after, .panel-body:after, .modal-footer:after { clear: both; }'
                . '.todisable a {color:#333333;pointer-events:none;cursor:pointer;text-decoration:none;}'
                . '.backtoagent {display: none;}'
                . 'h2 {font-size: 30px;}'
                . 'h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6 {margin-top: 20px; margin-bottom: 10px; font-family: inherit; font-weight: 500; line-height: 1.1; color: inherit;}'
                . '.pull-right {float:right}'
                . '.pull-left {float:left}'
                . '.text-right {text-align:right}'
                . '.text-left {text-align:left}'
                . '.text-center {text-align:center}'
                . '.panel {margin-bottom: 20px; background-color: #fff; border: 1px solid transparent; border-radius: 4px; -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05); box-shadow: 0 1px 1px rgba(0, 0, 0, .05);}'
                . '.panel-heading {padding: 10px 15px; border-bottom: 1px solid transparent; border-top-left-radius: 3px; border-top-right-radius: 3px;}'
                . '.panel-primary {border-color:#428bca;}'
                . '.panel-primary > .panel-heading {color: #428bca; background-color: #428bca; border-color: #428bca;}'
                . '.panel-info {border-color: #bce8f1;}'
                . '.panel-info > .panel-heading {color: #31708f; background-color: #d9edf7; border-color: #bce8f1;}'
                . '.panel-warning {border-color: #faebcc;}'
                . '.panel-warning > .panel-heading {color: #8a6d3b; background-color: #fcf8e3; border-color: #faebcc; }'
                . '.panel-footer {padding: 10px 15px; background-color: #f5f5f5; border-top: 1px solid #ddd; border-bottom-right-radius: 3px; border-bottom-left-radius: 3px;}'
                . '.announcement-text {margin: 0; white-space: pre-line;}'
                . '.col-lg-2, .col-md-2, .col-sm-2, .col-xs-2, .col-lg-3, .col-md-3, .col-sm-3, .col-xs-3, .col-lg-4, .col-md-4, .col-sm-4, .col-xs-4 {float:left; position: relative; min-height: 1px; padding-right: 3px; }'
                . '.col-lg-2, .col-md-2, .col-sm-2, .col-xs-2 { width: 16.666666666666664%; }'
                . '.col-lg-3, .col-md-3, .col-sm-3, .col-xs-3 { width: 25%; }'
                . '.col-lg-4, .col-md-4, .col-sm-4, .col-xs-4 { width: 33.33333333333333%; }'
                . '.col-lg-5, .col-md-5, .col-sm-5, .col-xs-5 { width: 41.66666666666667%; }'
                . '.col-lg-6, .col-md-6, .col-sm-6, .col-xs-6 { width: 50%; }'
                . '.col-lg-7, .col-md-7, .col-sm-7, .col-xs-7 { width: 58.333333333333336%; }'
                . '.col-lg-8, .col-md-8, .col-sm-8, .col-xs-8 { width: 66.66666666666666%; }'
                . '.col-lg-9, .col-md-9, .col-sm-9, .col-xs-9 { width: 75%; }'
                . '.col-lg-10, .col-md-10, .col-sm-10, .col-xs-10 { width: 83.33333333333334%; }'
                . '.col-lg-11, .col-md-11, .col-sm-11, .col-xs-11 { width: 91.66666666666666%; }'
                . '.col-lg-12, .col-md-12, .col-sm-12, .col-xs-12 { width: 100%; }'
                . '.table {width: 100%;margin-bottom: 20px; border-collapse:collapse;}'
                . '.table-bordered {border: 1px solid #ddd;} td {border: 1px solid #ddd;}'
                . 'th, td {padding:8px;border:1px solid #ddd;}'
                . '.red {color: #ea0000} .green {color: #458746}'
                . 'th.info, td.info {background-color:#d9edf7}'
                . 'th.warning, td.warning {background-color: #fcf8e3}'
                . 'th.active, td.active {background-color: #f5f5f5}'
                . 'th.success, td.success {background-color: #dff0d8}'
                . 'th.danger, td.danger {background-color: #f2dede}'
                . 'th.bg-primary, td.bg-primary {background-color:#428bca;}'
                . '</style>';
    }

    public function customSQL($sqlScript) {
        if (!empty($sqlScript)) {
            App::uses('ConnectionManager', 'Model');
            $dataSource = ConnectionManager::getDataSource('default');
            $dbh = new PDO('mysql:host=' . $dataSource->config['host'] . ';dbname=' . $dataSource->config['database'] . ';charset=utf8', $dataSource->config['login'], $dataSource->config['password']);

            try {
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $dbh->exec($sqlScript);
            } catch (Exception $e) {
                $dbh->rollBack();
                echo "Failed: " . $e->getMessage();
                return $e;
            }
        }
    }

}
