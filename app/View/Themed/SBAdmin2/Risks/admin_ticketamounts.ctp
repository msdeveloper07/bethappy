<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __($pluralName), 2 => __('Risk Management Ticket Limits'))))); ?></div>
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
                                        <p>*<?= __('For limit removal, leave fields blank'); ?></p>
                                        <?php $options = array(
                                            'url' => array(
                                                'controller' => 'risks'
                                            ),
                                            'inputDefaults' => array(
                                                'label' => false,
                                                'div' => false)
                                        );
                                        echo $this->Form->create('Setting', $options);?>
                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <th><?= __('Ticket Stake'); ?><br><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('Starting amount of money that user can use place a ticket.');?></span></td>
                                                <th><?= __('Possible Max Win'); ?><br><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('Highest amount of Win that user can get.') ; ?></span></td>
                                            </tr>
                                            <?php foreach($settings as $key=>$datavalues): ?>
                                            <tr>
                                                <td><?= $this->Form->input('amount.'.$key, array('value' => $datavalues['amount'])); ?></td>
                                                <td><?= $this->Form->input('possible_amount_win.'.$key, array('value' => $datavalues['possible_amount_win'])); ?></td>
                                            </tr>
                                            <?php endforeach;?>
                                             <tr>
                                                <td><?= $this->Form->input('amount.new', array('value' => '')); ?></td>
                                                <td><?= $this->Form->input('possible_amount_win.new', array('value' => '')); ?></td>
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