
<?php echo $this->MyForm->create($model, array('type' => 'file')); ?>
<ul class="list-group">
    <?php
    foreach ($fields AS $i => &$field):
        var_dump($i, $field);exit;
        ?>
        <li class="list-group-item form-group">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <p class="mb-0" >
                        <?php
                        if (is_array($field)) {
                            $label = explode(".", $i);
                        } else {
                            $label = explode(".", $field);
                        }

                        $switch = strpos($field, '.active');

                        if ($label[1] == 'brand_id')
                            $label[1] = 'Brand';
                        if ($label[1] == 'category_id')
                            $label[1] = 'Category';
                        if ($label[1] == 'payoff_mul')
                            $label[1] = 'Initial amount multiplier to lock';

                        if ($label[1] == 'duration' && $i == 'BonusType.duration')
                            $label[1] = 'Duration in hours';

                        //var_dump($label);
                        ?>
                        <?= ucfirst(str_replace("_", " ", $label[1])); ?>
                    </p>
                </div>
                <div class="col-md-8">
                    <?php
                    switch ($field) {

                        case $field['type'] == 'text' && $field['class'] == 'datetimepicker-filter':
                            echo $this->Form->input($i, array('type' => 'text', 'label' => false, 'id' => 'datetimepicker-filter-' . str_replace("_", "-", $label[1]), 'class' => 'form-control datetimepicker-filter'));

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
                            . $this->Form->input($i, array('type' => 'checkbox', 'hiddenField' => false, 'label' => false, 'div' => false, 'id' => $i, 'class' => 'custom-control-input')) .
                            '<label class="custom-control-label" for="' . $i . '"></label>
                                                </div>';
                            break;

                        case $field['type'] == 'select':
                            if ($model == 'IntGame' && $i == 'IntGame.brand_id') {
                                ?>
                                <select type="select" name="data[<?= $model; ?>][brand_id]" class="form-control">
                                    <option selected disabled><?php echo __('Select brand'); ?></option>
                                    <?php foreach ($field['options'] as $key => $brand) { ?>
                                        <option value="<?php echo $brand['IntBrand']['id']; ?>"><?php echo $brand['IntBrand']['name']; ?></option>
                                    <?php } ?>
                                </select>

                                <?php
                            } else if ($model == 'IntGame' && $i == 'IntGame.category_id') {
                                ?>
                                <select type="select" name="data[<?= $model; ?>][category_id]" class="form-control">
                                    <option selected disabled><?php echo __('Select category'); ?></option>
                                    <?php foreach ($field['options'] as $key => $category) { ?>
                                        <option value="<?php echo $category['IntCategory']['id']; ?>"><?php echo $category['IntCategory']['name']; ?></option>
                                    <?php } ?>
                                </select>

                                <?php
                            } else {
                                echo $this->Form->input($i, array('type' => 'select', 'options' => $field['options'], 'label' => false, 'class' => 'form-control'));
                            }


                            break;

                        case $field['type'] == 'file':

                            echo '<div class="custom-file" id="' . $model . ucfirst(str_replace("_", "", $label[1])) . 'Wrapper">' .
                            $this->Form->input($i, array('type' => 'file', 'label' => false, 'div' => false, 'class' => 'custom-file-input', 'id' => $model . ucfirst(str_replace("_", "", $label[1])))) .
                            '<label class="custom-file-label" for="' . $model . ucfirst(str_replace("_", "", $label[1])) . '">' . __('Choose file') . '</label>                  
                              </div>';

//                            $tmp = explode(".", $i);
//                            $for = $tmp[0] . '' . ucfirst($tmp[1]);
//  
//                            echo '<div class="custom-file">' .
//                            $this->Form->input($i, array('type' => 'file', 'label' => false, 'class' => 'custom-file-input')) .
//                            '<label class="custom-file-label" for="' . $for . '"></label>                  
//                              </div>';
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




    <?php if (isset($acl_fields) && !empty($acl_fields)): ?>
        <li class="list-group-item form-group">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <?= __('Group', true); ?>
                </div>
                <div class="col-md-8">
                    <div class="row-fluid" id="acls">
                        <?php foreach ($this->request->data['BonusAcl'] as $i => $acl) { ?>  
                            <div class="row-fluid">
                                <?php
                                foreach ($acl_fields as $key => $field) {
                                    echo $this->Form->input('BonusAcl.' . $i . '.' . $key, array_merge($field, array('legend' => false)));
                                }
                                ?>
                            </div>
                            <button class="btn btn-danger mt-4" id="icon-remove"><i class="fas fa-times"></i></button>
                        <?php } ?>                                          
                    </div>
                    <button id="add-acl" class="btn btn-success float-right mt-2"><?= __('Add Group'); ?></button>

                </div>
            </div>
        </li>
    <?php endif; ?>


    <?php if (isset($condition_fields) && !empty($condition_fields)): ?>
        <li class="list-group-item form-group">
            <div class="row align-items-center">
                <div class="col-md-4">

                    <?= __('Conditions', true); ?>

                </div>
                <div class="col-md-8">
                    <div class="row-fluid" id="conditions">
                        <?php foreach ($this->request->data['BonusCondition'] as $i => $acl) { ?>  
                            <div class="row-fluid">
                                <?php
                                foreach ($condition_fields as $key => $field) {
                                    echo $this->Form->input('BonusCondition.' . $i . '.' . $key, array_merge($field, array('legend' => false)));
                                }
                                ?>
                                <hr/>
                            </div>
                        <?php } ?>          
                    </div>
                    <button id="add-condition" class="btn btn-success mt-2 float-right"><?= __('Add Condition'); ?></button>

                </div>
            </div>
        </li>
    <?php endif; ?>


    <?php if (isset($game_fields) && !empty($game_fields)): ?>
        <li class="list-group-item form-group">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <?= __('Games', true); ?>
                </div>
                <div class="col-md-8">
                    <div class="row-fluid" id="games">
                        <?php foreach ($this->request->data['BonusGames'] as $i => $game) { ?>  
                            <div class="row-fluid">
                                <?php
                                foreach ($game_fields as $key => $field) {
                                    echo $this->MyForm->input('BonusGames.' . $i . '.' . $key, array_merge($field, array('legend' => false)));
                                }
                                ?>
                                <hr/>
                            </div>
                            <button class="btn btn-danger mt-4" id="icon-remove"><i class="fas fa-times"></i></button>
                        <?php } ?>          
                    </div>
                    <button id="add-game" class="btn btn-success float-right mt-2"><?= __('Add Game'); ?></button>


                </div>
            </div>
        </li>
    <?php endif; ?>
</ul>
<div class="row form-group">
    <div class="col-sm-12 col-md-3 offset-md-9 col-lg-2 offset-lg-10 my-3">
        <?php echo $this->Form->submit(__('Save', true), array('class' => 'btn btn-success btn-block')); ?>
    </div>
</div>
<?php echo $this->Form->end(); ?>

<script>
    console.log($(this).next(".custom-file-label"));
    $("input[type=file]").change(function () {
        var fieldVal = $(this).val();
        console.log(fieldVal);
        // Change the node's value by removing the fake path (Chrome)
        fieldVal = fieldVal.replace("C:\\fakepath\\", "");
        console.log($(this).next(".custom-file-label").html());
        if (fieldVal !== undefined || fieldVal !== "") {
            $(this).next(".custom-file-label").html(fieldVal);
        }

    });
</script>
<?php
//echo $this->MyForm->create($model, array('type' => 'file'));
//echo $this->MyForm->inputs(array_merge($fields, array('legend' => false, 'div' => false, 'class' => 'form-control')));
//echo $this->MyForm->submit(__('Save', true), array('class' => 'btn btn-success', 'style' => 'margin-top: 15px;
//));
//echo $this->MyForm->end();
