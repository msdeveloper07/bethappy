<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $pluralName, 2 => __('Payment gateways'))))); ?></div>
    </div>
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                    <div class="tab-content">
                                        <?php
                                            $options = array('inputDefaults' => array('label' => false, 'div' => false));
                                            echo $this->Form->create('Setting', $options);
                                            $yesNoOptions = array('1' => 'Yes', '0' => 'No');
                                        ?>
                                        <?= __('Please set payment gateways below:'); ?>
                                        <br><br>
                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <th width="300px"><?= __('Payments'); ?></th>
                                                <th><?= __('Description'); ?></th>
                                                <th><?= __('Value'); ?></th>
                                            </tr>
                                            <tr>
                                                <th style="text-align: center;"><?= $this->Html->image('banks/etranzact.png', array('width' => '75px')); ?></th>
                                                <td><?= 'eTranzact'; ?></td>
                                                <td><?= $this->Form->input($data['eTranzactStatus']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['eTranzactStatus']['value'])); ?></td>
                                            </tr>
                                            <tr>
                                                <th style="text-align: center;"><?= $this->Html->image('banks/payment.jpg', array('width' => '75px')); ?></th>
                                                <td><?= 'Apco'; ?></td>
                                                <td><?= $this->Form->input($data['ApcoStatus']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['ApcoStatus']['value'])); ?></td>
                                            </tr>
                                            <tr>
                                                <th style="text-align: center;"><?= $this->Html->image('banks/epg.png', array('width' => '75px')); ?></th>
                                                <td><?= 'Epg'; ?></td>
                                                <td><?= $this->Form->input($data['epgStatus']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['epgStatus']['value'])); ?></td>
                                            </tr>
                                            <tr>
                                                <th style="text-align: center;"><?= $this->Html->image('banks/tfm.png', array('width' => '75px')); ?></th>
                                                <td><?= 'TFM'; ?></td>
                                                <td><?= $this->Form->input($data['TFMStatus']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['TFMStatus']['value'])); ?></td>
                                            </tr>
                                        </table>
                                        <br />
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