<?php
$url = array();
//var_dump($this->params);
//var_dump($data);
if (isset($this->params['pass'][0]))
    $url = array($this->params['pass'][0]);
?>
<?php echo $this->MyForm->create($model, array('url' => $url, 'type' => 'file'));
?>
<ul class="list-group mb-4">
    <li class="list-group-item form-group">
        <div class="row align-items-center">
            <div class="col-md-4">
                <p class="mb-0">
                    <label for="<?= $model; ?>Locale"><?= __('Locale', true) ?></label>
                </p>
            </div>
            <div class="col-md-8">
                <select name="data[<?= $model; ?>][locale]" id="<?= $model; ?>Locale" class="form-control">
                    <?php foreach ($locales as $key => $local): ?>
                        <option value="<?= $local['Language']['locale_code']; ?>" rel="<?php echo $this->Html->URL(array("action" => "translate", $currentid, $local['Language']['locale_code'])); ?>" 
                            <?php if ($currentlocale == $local['Language']['locale_code']) echo "selected='selected'"; ?>><?= $local['Language']['name']; ?></option>
                    <?php endforeach; ?>

                </select>
            </div>
        </div>
    </li>
</ul>


<ul class="list-group">
    <?php
    foreach ($fields AS $i => &$field):
        //var_dump($field);
//        var_dump($i);
        ?>
        <li class="list-group-item form-group">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <p class="mb-0">
                        <?php
                        if (is_array($field)) {
                            $label = explode(".", $i);
                        } else {
                            $label = explode(".", $field);
                        }
                        //var_dump($label);
                        $pos = strpos($field, '.active');
                        ?>
                        <?= ucfirst(str_replace("_", " ", $label[1])); ?>
                    </p>
                </div>
                <div class="col-md-8">

                    <?php
                    switch ($field) {

                        case $field['type'] == 'text' && $field['class'] == 'datetimepicker-filter':
                            echo $this->Form->input($i, array('type' => 'text', 'label' => false, 'id' => 'datetimepicker-filter', 'class' => 'form-control datetimepicker-filter'));

                            break;
                        case $field['type'] == 'textarea' && $field['class'] == 'ckeditor':
                            echo $this->Form->input($i, array('type' => 'textarea', 'label' => false, 'id' => 'ckeditor', 'class' => 'form-control ckeditor'));
                            break;

                        case $field['type'] == 'checkbox':

                            echo '<div class="custom-control custom-switch">'
                            . $this->Form->input($i, array('type' => 'checkbox', 'hiddenField' => false, 'label' => false, 'div' => false, 'id' => $i, 'class' => 'custom-control-input')) .
                            '<label class="custom-control-label" for="' . $i . '"></label>
                                                </div>';
                            break;

                        case $field['type'] == 'switch':
                            echo '<div class="custom-control custom-switch">'
                            . $this->Form->input($i, array('type' => 'checkbox', 'hiddenField' => false, 'label' => false, 'div' => false, 'id' => $field, 'class' => 'custom-control-input')) .
                            '<label class="custom-control-label" for="' . $field . '"></label>
                                                </div>';
                            break;

                        case $field['type'] == 'select':
                            echo $this->Form->input($i, array('type' => 'select', 'options' => $field['options'], 'label' => false, 'class' => 'form-control'));
                            break;

                        case $field['type'] == 'file':
                            echo '<div class="custom-file">' .
                            $this->Form->input($i, array('type' => 'file', 'label' => false, 'class' => 'custom-file-input')) .
                            '<label class="custom-file-label" for="' . $field . '"></label>                  
                              </div>';
                            break;

                        case $field['type'] == 'text':
                            echo $this->Form->input($i, array('type' => 'text', 'label' => false, 'class' => 'form-control', 'disabled' => ($label[1] == 'id' ? true : false)));
                            break;

                        case $field['type'] == 'number':
                            echo $this->Form->input($i, array('type' => 'number', 'label' => false, 'class' => 'form-control', 'disabled' => ($label[1] == 'id' ? true : false)));
                            break;

                        case $field['type'] == 'hidden':
                            echo $this->Form->input($i, array('type' => 'hidden', 'label' => false, 'class' => 'form-control', 'disabled' => ($label[1] == 'id' ? true : false)));
                            break;

                        default:
                            echo $this->Form->input($field, array('type' => 'text', 'label' => false, 'class' => 'form-control', 'disabled' => ($label[1] == 'id' ? true : false)));
                            break;
                    }
                    ?>


                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
<div class="row form-group">
    <div class="col-sm-12 col-md-3 offset-md-9 col-lg-2 offset-lg-10 my-3">
        <?php echo $this->Form->submit(__('Save', true), array('class' => 'btn btn-success btn-block')); ?>
    </div>
</div>
<?php echo $this->Form->end(); ?>







<!--<?//= $this->Form->create($model, array('type' => 'file', 'novalidate' => true)); ?>
<!--<label for="<?= $model; ?>Locale"><?//= __('locale', true) ?></label>
<select name="data[<?= $model; ?>][locale]" id="<?//= $model; ?>Locale">
<?php //foreach ($locales as $key => $local):  ?>
        <option value="<?//= $key; ?>" rel="<?php //echo $this->Html->URL(array("action" => "translate", $currentid, $key));    ?>" <?php //if ($currentlocale == $key) echo "selected='selected'";    ?>><?= $local; ?></option>
<?php //endforeach;  ?>

</select>-->
<?php
//echo $this->Form->inputs(array_merge($fields, array('legend' => __('Create %s', __($singularName)))));
//echo $this->Form->submit(__('Submit', true), array('class' => 'btn btn-primary', 'style' => 'margin-top: 15px;'));
//echo $this->Form->end();
?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#<?= $model; ?>Locale").change(function () {
            var selected = $(this).find("option:selected");
            location.href = selected.attr("rel");
        });
    });
</script>