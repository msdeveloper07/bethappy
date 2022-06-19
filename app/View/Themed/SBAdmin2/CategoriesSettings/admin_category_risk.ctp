<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __($pluralName), 2 => __('List Categories'))))); ?></div>
    </div>
    <?php print_r($data); ?>
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
                                        <?= $this->element('flash_message'); ?>
                                        <h4><?= __('Risk management is crucial for sportsbook. Please be careful in setting all options.'); ?></h4></br>
                                        <?php
                                        $yesNoOptions = array('1' => 'Yes', '0' => 'No');
                                        $options = array(
                                            'url' => array('controller' => 'CategoriesSettings'),
                                            'inputDefaults' => array(
                                                'label' => false,
                                                'div' => false),
                                            'action'=>'category_risk/'.$catid
                                        );
                                        echo $this->Form->create('CategoriesSetting', $options); ?>
                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <td><?= __('Lowest stake'); ?></td>
                                                <td><?= $this->Form->input($settings['minBet']['id'], array('value' => $settings['minBet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('Lowest amount of money that user must have to place a ticket.');?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?= __('Highest stake'); ?></td>
                                                <td><?= $this->Form->input($settings['maxBet']['id'], array('value' => $settings['maxBet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('Highest amount of money that user can use place a ticket.');?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?= __('Highest winning amount'); ?></td>
                                                <td><?= $this->Form->input($settings['maxWin']['id'], array('value' => $settings['maxWin']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('Highest amount of money that can be won in one ticket.');?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?= __('Lowest number of events in one ticket'); ?></td>
                                                <td><?= $this->Form->input($settings['minBetsCount']['id'], array('value' => $settings['minBetsCount']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('Lowest number of events that can be enetered into a ticket.');?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?= __('Highest number of events in one ticket'); ?></td>
                                                <td><?= $this->Form->input($settings['maxBetsCount']['id'], array('value' => $settings['maxBetsCount']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?=__('Highest number of events that can be entered a ticket.');?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?= __('Daily wager limit'); ?></td>
                                                <td><?= $this->Form->input($settings['daily_wager_limit']['id'], array('value' => $settings['daily_wager_limit']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php print __('Highest amount a user can play in 24 hours.')?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?= __('Daily winning amount'); ?></td>
                                                <td><?= $this->Form->input($settings['daily_win_limit']['id'], array('value' => $settings['daily_win_limit']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php print __('Highest amount a user can win in 24 hours.')?></span></td>
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