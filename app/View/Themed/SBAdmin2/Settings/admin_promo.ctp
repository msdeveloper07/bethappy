<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $pluralName, 2 => __('Edit %s', $singularName))))); ?>
        </div>
    </div>
    <div id="page" class="dashboard">
        <?php echo $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">

                                    <div class="tab-content">

                                        <?php echo $this->element('flash_message'); ?>

                                        <?php
                                        $options = array(
                                            'inputDefaults' => array(
                                                'label' => false,
                                                'div' => false)
                                        );
                                        echo $this->Form->create('Setting', $options);
                                        $yesNoOptions = array('1' => 'Yes', '0' => 'No');
                                        ?>

                                        <h3>Left promotion sidebar</h3>
                                        <table class="table table-hover" style=" width: 65%">
                                            <tr>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Value'); ?></th>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Left active'); ?></td>
                                                <td>
                                                    <div class="control-group">
                                                        <div class="controls">
                                                            <div class="transition-value-toggle-button">
                                                                <?php echo $this->Form->input($data['left_promo_enabled']['id'], array('type' => 'checkbox', 'class' => 'toggle', 'checked' => $data['left_promo_enabled']['value'])); ?>
                                                            </div>
                                                            <span style="font-size: x-small; font-style: italic; padding-left: 10px; position: relative; bottom: 5px;">Show (Yes) or hide (No) left promotion sidebar in front end.</span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><?php echo __('Left sidebar title'); ?></td>
                                                <td><?php echo $this->Form->input($data['left_promo_header']['id'], array('value' => $data['left_promo_header']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">Enter left sidebar title.</span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Left sidebar content'); ?></td>
                                                <td><?php echo $this->Form->textarea($data['left_promo_body']['id'], array('value' => $data['left_promo_body']['value'], 'id' => 'epic', 'class' => 'span12 ckeditor')); ?></td>
                                            </tr>

                                        </table>
                                        <br /><br />

                                        <h3>Right promotion sidebar</h3>
                                        <table class="items" style="width: 65%">
                                            <tr>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Value'); ?></th>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Right active'); ?></td>
                                                <td>
                                                    <div class="control-group">
                                                        <div class="controls">
                                                            <div class="transition-value-toggle-button">
                                                                <?php echo $this->Form->input($data['right_promo_enabled']['id'], array('type' => 'checkbox', 'class' => 'toggle', 'checked' => $data['right_promo_enabled']['value'])); ?>
                                                            </div>
                                                            <span style="font-size: x-small; font-style: italic; padding-left: 10px; position: relative; bottom: 5px;">Show (Yes) or hide (No) right promotion sidebar in front end.</span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Right sidebar title'); ?></td>
                                                <td><?php echo $this->Form->input($data['right_promo_header']['id'], array('value' => $data['right_promo_header']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">Enter right sidebar title.</span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Right sidebar content'); ?></td>
                                                <td><?php echo $this->Form->textarea($data['right_promo_body']['id'], array('value' => $data['right_promo_body']['value'], 'class' => 'span12 ckeditor')); ?></td>
                                            </tr>
                                        </table>
                                        <br /><br />

                                        <h3>Bottom promotion sidebar</h3>
                                        <table class="items" style="width: 65%">
                                            <tr>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Value'); ?></th>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Bottom active'); ?></td>
                                                <td>
                                                    <div class="control-group">
                                                        <div class="controls">
                                                            <div class="transition-value-toggle-button">
                                                                <?php echo $this->Form->input($data['bottom_promo_enabled']['id'], array('type' => 'checkbox', 'class' => 'toggle', 'checked' => $data['bottom_promo_enabled']['value'])); ?>
                                                            </div>
                                                            <span style="font-size: x-small; font-style: italic; padding-left: 10px; position: relative; bottom: 5px;">Show (Yes) or hide (No) bottom promotion sidebar in front end.</span>
                                                        </div>
                                                    </div>
                                                </td>
                                            <tr>
                                                <td><?php echo __('Bottom sidebar title'); ?></td>
                                                <td><?php echo $this->Form->input($data['bottom_promo_header']['id'], array('value' => $data['bottom_promo_header']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">Enter bottom sidebar title.</span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Bottom sidebar content'); ?></td>
                                                <td><?php echo $this->Form->textarea($data['bottom_promo_body']['id'], array('value' => $data['bottom_promo_body']['value'], 'class' => 'span12 ckeditor')); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <?php echo $this->Form->submit(__('Save', true), array('class' => 'btn')); ?>
                                                </td>
                                            </tr>
                                        </table>
                                        <div style="clear:both;"></div>
                                        <?php echo $this->Form->end(); ?>
                                    </div>
                                </div>
                                <!--END TABS-->
                            </div>
                            <div class="space10 visible-phone"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>