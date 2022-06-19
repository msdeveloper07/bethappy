<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <h3 class="page-title">
                <?php echo __('Referral Settings'); ?>
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
                                        $yesNoOptions = array('1' => 'Yes', '0' => 'No'); ?>

                                        All basic configuration referral system. A referrer is a person who makes money by referring players (called referrals) to your sportsbook. <br><br>

                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Value'); ?></th>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('First deposit percentage to referral'); ?></td>
                                                <td><?php echo $this->Form->input($data['referral_deposit_percentage']['id'], array('value' => $data['referral_deposit_percentage']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">Percent which get referrer from first referral deposit. Insert percent from 1 to 100.</td>
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