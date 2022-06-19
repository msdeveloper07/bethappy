


<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => __('List %s', __($pluralName)), 1 => __($singularName))))); ?></div>
    </div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <?= $this->element('search');?>
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                    <div class="tab-content">
                                        <?= $this->element('flash_message'); ?>
                                        <h4><?= __('Risk management is crucial for sportsbook. Please be careful in setting all options.'); ?></h4></br>
                                        <?php
                                        $yesNoOptions = array('1' => 'Yes', '0' => 'No');
                                        $options = array(
                                            'url' => array('controller' => 'Userssettings'),
                                            'inputDefaults' => array(
                                                'label' => false,
                                                'div' => false),
                                            'action'=>'depositrisk/'.$userid
                                        );
                                        echo $this->Form->create('Userssetting', $options);
                                        ?>
                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <td><?= __('Minimum'); ?> <?= __('Deposit'); ?></td>
                                                <td><?= $this->Form->input($settings['minDeposit']['id'], array('value' => $settings['minDeposit']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('Lowest amount of money that user must have to deposit.');?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?= __('Maximum'); ?> <?= __('Deposit'); ?></td>
                                                <td><?= $this->Form->input($settings['maxDeposit']['id'], array('value' => $settings['maxDeposit']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('Highest amount of money that user can deposit.');?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?= __('Deposit'); ?></td>
                                                <td><?= $this->Form->input($settings['deposits']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $settings['deposits']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('Enable Deposits');?></span></td>
                                            </tr>
                                             <tr>
                                                <td><?= __('Manual'); ?> <?= __('Deposit'); ?></td>
                                                <td><?= $this->Form->input($settings['D_Manual']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $settings['D_Manual']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('Enable manual deposit');?></span></td>
                                            </tr>
                                        </table>
                                        <br>
                                        <?= $this->Form->submit(__('Save', true), array('class' => 'btn')); ?>
                                        <?= $this->Form->end(); ?>
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