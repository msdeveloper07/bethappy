<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $pluralName, 2 => __('Warnings %s', $pluralName))))); ?>
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
                                        $timezones = $this->TimeZone->getTimeZones();
                                        ?>

                                        <br>

                                        <?php __('Warning settings would help you to filter warning screen with unnecessary alerts. Please consider what is crucial for you sportsbook and use this function to secure your profit.'); ?>

                                        <table class="table table-bordered table-striped">

                                            <tr>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Value'); ?></th>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Deposit alert'); ?></td>
                                                <td><?php echo $this->Form->input($data['bigDeposit']['id'], array('value' => $data['bigDeposit']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">System will alert once user deposited same or higher amount of money his/her account.</span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Withdraw alert'); ?></td>
                                                <td><?php echo $this->Form->input($data['bigWithdraw']['id'], array('value' => $data['bigWithdraw']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">System will alert once user ask for withdraw same or higher amount of money from his/her account.</span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Stake alert'); ?></td>
                                                <td><?php echo $this->Form->input($data['bigStake']['id'], array('value' => $data['bigStake']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">System will alert once user stake a ticket would be with same or higher amount of money.</span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Odds alert'); ?></td>
                                                <td><?php echo $this->Form->input($data['bigOdd']['id'], array('value' => $data['bigOdd']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">System will alert once odd will reach or would be higher then set in warning settings</span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Potential winning alert'); ?></td>
                                                <td><?php echo $this->Form->input($data['bigWinning']['id'], array('value' => $data['bigWinning']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">System will alert staff once user will place ticket same or higher potential winning amount.</span></td>
                                            </tr>
                                        </table>
                                        <br />
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