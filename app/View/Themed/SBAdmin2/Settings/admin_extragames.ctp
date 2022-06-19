<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $pluralName, 2 => __('Tickets %s', $pluralName))))); ?>
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

                                        <?php echo __('General setting for external games'); ?>
                                        <br><br>
                                        <h3><?php echo __('General'); ?></h3>
                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Value'); ?></th>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Game Not Available'); ?></td>
                                                <td>
                                                    <?php echo $this->Form->input($data['game_not_available']['id'], array('type' => 'textarea', 'value' => $data['game_not_available']['value'])); ?>
                                                    <span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Message displayed when a game is disabled'); ?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Partner Search Points'); ?></td>
                                                <td>
                                                    <?php echo $this->Form->input($data['partner_search']['id'], array('type' => 'text', 'value' => $data['partner_search']['value'])); ?>
                                                    <span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __("Point for external Partners having access to platform's users list"); ?></span>
                                                </td>
                                            </tr>
                                        </table>
                                        <br>
                                        <h3><?php echo __('Live Casino'); ?></h3>
                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Value'); ?></th>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Live Casino Roulette'); ?></td>
                                                <td>
                                                    <?php echo $this->Form->input($data['roulette']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['roulette']['value'])); ?>
                                                    <span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Enable (Yes) or disable (No) Roulette'); ?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Live Casino Blackjack'); ?></td>
                                                <td>
                                                    <?php echo $this->Form->input($data['blackjack']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['blackjack']['value'])); ?>
                                                    <span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Enable (Yes) or disable (No) Blackjack'); ?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Live Casino Baccarat'); ?></td>
                                                <td>
                                                    <?php echo $this->Form->input($data['baccarat']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['baccarat']['value'])); ?>
                                                    <span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Enable (Yes) or disable (No) Baccarat'); ?></span>
                                                </td>
                                            </tr>
                                        </table><br>
                                        <h3><?php echo __('RGS'); ?></h3>
                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Value'); ?></th>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Virtual Soccer'); ?></td>
                                                <td>
                                                    <?php echo $this->Form->input($data['virtual_soccer']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['virtual_soccer']['value'])); ?>
                                                    <span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Enable (Yes) or disable (No) Roulette'); ?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Soccer Roulette'); ?></td>
                                                <td>
                                                    <?php echo $this->Form->input($data['soccer_roulette']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['soccer_roulette']['value'])); ?>
                                                    <span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php echo __('Enable (Yes) or disable (No) Blackjack'); ?></span>
                                                </td>
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
