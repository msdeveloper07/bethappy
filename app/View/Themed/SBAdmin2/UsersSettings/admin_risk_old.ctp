<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
             <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Players'); ?></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= $this->Html->link(__('Players List'), ['plugin' => false, 'controller' => 'users', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Players List')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= $this->Html->link(__('Player View'), ['plugin' => false, 'controller' => 'users', 'action' => 'view', 'prefix' => 'admin', $user_id], ['escape' => false, 'title' => __('Player View')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Player Risk Settings'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Player Risk Settings'); ?></h1>
            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12">
                <p><?= __('Risk management is crucial. Please be careful in setting all options.'); ?></p></br> 
                <?php
                $yesNoOptions = array('1' => 'Yes', '0' => 'No');
                $options = array(
                    'url' => array(
                        'controller' => 'UsersSettings'
                    ),
                    'inputDefaults' => array(
                        'label' => false,
                        'div' => false),
                    'action' => 'risk/' . $userid
                );
                ?>
                <?php echo $this->Form->create('UserSettings', $options); ?>
                <ul class="list-group">
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Lowest stake'); ?><br>
                                    <small class="text-muted font-italic"><?= __('Lowest amount of money that user must have to place a ticket.'); ?></small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input($settings['minBet']['id'], array('value' => $settings['minBet']['value'], 'class' => 'form-control')); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Highest stake'); ?><br>
                                    <small class="text-muted font-italic"><?= __('Highest amount of money that user can use place a ticket.'); ?></small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input($settings['maxBet']['id'], array('value' => $settings['maxBet']['value'], 'class' => 'form-control')); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Highest winning amount'); ?><br>
                                    <small class="text-muted font-italic"><?= __('Highest amount of money that can be won in one ticket.'); ?></small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input($settings['maxWin']['id'], array('value' => $settings['maxWin']['value'], 'class' => 'form-control')); ?>
                            </div>
                        </div>
                    </li>

<!--                    <li class="list-group-item form-group bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?//= __('Lowest number of events in one ticket'); ?><br>
                                    <small class="text-muted font-italic"><?//= __('Lowest number of events that can be enetered into a ticket.'); ?></small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?//= $this->Form->input($settings['minBetsCount']['id'], array('value' => $settings['minBetsCount']['value'], 'class' => 'form-control')); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?//= __('Highest number of events in one ticket'); ?><br>
                                    <small class="text-muted font-italic"><?//= __('Highest number of events that can be entered into a ticket.'); ?></small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?//= $this->Form->input($settings['maxBetsCount']['id'], array('value' => $settings['maxBetsCount']['value'], 'class' => 'form-control')); ?>
                            </div>
                        </div>
                    </li>-->
                    
                    <li class="list-group-item form-group bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Daily wager limit'); ?><br>
                                    <small class="text-muted font-italic"><?= __('Highest amount a user can play in 24 hours.') ?></small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input($settings['daily_wager_limit']['id'], array('value' => $settings['daily_wager_limit']['value'], 'class' => 'form-control')); ?>
                            </div>
                        </div>
                    </li>
                    
                      <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Daily winning amount'); ?><br>
                                    <small class="text-muted font-italic"><?= __('Highest amount a user can win in 24 hours.') ?></small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input($settings['daily_win_limit']['id'], array('value' => $settings['daily_win_limit']['value'], 'class' => 'form-control')); ?>
                            </div>
                        </div>
                    </li>


                </ul>
                <div class="row form-group">
                    <div class="col-sm-12 col-md-3 offset-md-9 col-lg-2 offset-lg-10 my-3">
                        <?php echo $this->Form->submit(__('Save', true), array('class' => 'btn btn-success btn-block')); ?>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
    <!--END PAGE CONTENT-->
</div>


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
                                            'action'=>'risk/'.$userid
                                        );
                                        echo $this->Form->create('Userssetting', $options);
                                        ?>
                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <td><?= __('Lowest stake'); ?></td>
                                                <td><?= $this->Form->input($settings['minBet']['id'], array('value' => $settings['minBet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('Lowest amount of money that user must have to place a ticket.');?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?= __('Highest stake'); ?></td>
                                                <td><?= $this->Form->input($settings['maxBet']['id'], array('value' => $settings['maxBet']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('Highest amount of money that user can use place a ticket.');?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?= __('Highest winning amount'); ?></td>
                                                <td><?= $this->Form->input($settings['maxWin']['id'], array('value' => $settings['maxWin']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('Highest amount of money that can be won in one ticket.');?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?= __('Lowest number of events in one ticket'); ?></td>
                                                <td><?= $this->Form->input($settings['minBetsCount']['id'], array('value' => $settings['minBetsCount']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('Lowest number of events that can be enetered into a ticket.');?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?= __('Highest number of events in one ticket'); ?></td>
                                                <td><?= $this->Form->input($settings['maxBetsCount']['id'], array('value' => $settings['maxBetsCount']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?= __('Highest number of events that can be entered a ticket.');?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?= __('Daily wager limit'); ?></td>
                                                <td><?= $this->Form->input($settings['daily_wager_limit']['id'], array('value' => $settings['daily_wager_limit']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php print __('Highest amount a user can play in 24 hours.')?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?= __('Daily winning amount'); ?></td>
                                                <td><?= $this->Form->input($settings['daily_win_limit']['id'], array('value' => $settings['daily_win_limit']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php print __('Highest amount a user can win in 24 hours.')?></span></td>
                                            </tr>
                                             <tr>
                                                <td><?= __('Allow Duplicate Bets'); ?></td>
                                                <td><?= $this->Form->input($settings['AllowDuplicateBets']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $settings['AllowDuplicateBets']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php print __('Allow players to place bets more than once.')?></span></td>
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