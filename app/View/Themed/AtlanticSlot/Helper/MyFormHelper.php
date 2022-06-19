<?php

App::uses('FormHelper', 'View/Helper');

class MyFormHelper extends FormHelper {
    
    /**
     * Helper name
     * @var string
     */
    public $name = 'MyForm';

    /**
     * Overrides a form input element complete with label and wrapper div
     * @param string $fieldName
     * @param array $options
     * @return string
     */
    public function input($fieldName, $options = array()) {
        if(!isset($options['required'])) $options['required'] = false; // Finger bitch!

        return parent::input($fieldName, $options);
    }

    /** Date time
     *
     * @param string $fieldName
     * @param string $dateFormat
     * @param string $timeFormat
     * @param null $selected
     * @param array $attributes
     * @return string
     */
    public function dateTime($fieldName, $dateFormat = 'DMY', $timeFormat = '12', $selected = null, $attributes = array()) {
        if ($timeFormat != NULL) return $this->input($fieldName, array('type' => 'text', 'label' => false, 'class' => 'input-small flexy_datetimepicker_input'));

        return $this->input($fieldName, array('type' => 'text', 'label' => false, 'class' => 'input-small flexy_datepicker_input'));
    }
}