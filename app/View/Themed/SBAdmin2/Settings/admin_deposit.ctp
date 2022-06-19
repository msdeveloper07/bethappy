<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <h3 class="page-title">
                <?php echo $pluralName;?>
            </h3>
        </div>
    </div>
    <div id="page" class="dashboard">
        <?php echo $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <?php echo $this->element('search');?>
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                    <?php echo $this->element('tabs');?>
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

                                        <table class="items">
                                            <tr>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Value'); ?></th>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Deposit fee percentage'); ?></td>
                                                <td><?php echo $this->Form->input($data['deposit_funding_percentage']['id'], array('value' => $data['deposit_funding_percentage']['value'])); ?>%</td>
                                            </tr>
                                        </table>

                                        <?php echo $this->Form->submit(__('Save', true), array('class' => 'btn')); ?>
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