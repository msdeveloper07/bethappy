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
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Player Deposit Risk Settings'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Player Deposit Risk Settings'); ?></h1>
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
                    'action' => 'depositrisk/' . $user_id
                );
                ?>
                <?php echo $this->Form->create('UserSettings', $options); ?>
                <ul class="list-group">
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Minimum Deposit'); ?><br>
                                    <small class="text-muted font-italic"><?= __('Lowest amount of money that user must have to deposit.'); ?></small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input($settings['minDeposit']['id'], array('value' => $settings['minDeposit']['value'], 'class' => 'form-control')); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Maximum Deposit'); ?><br>
                                    <small class="text-muted font-italic"><?= __('Highest amount of money that user can to deposit.'); ?></small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input($settings['maxDeposit']['id'], array('value' => $settings['maxDeposit']['value'], 'class' => 'form-control')); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Deposits'); ?><br>
                                    <small class="text-muted font-italic"><?= __('Enable deposits.'); ?></small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input($settings['deposits']['id'], array('type' => 'select', 'class' => 'form-control', 'options' => $yesNoOptions, 'value' => $settings['deposits']['value'])); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Manual Deposits'); ?><br>
                                    <small class="text-muted font-italic"><?= __('Enable manual deposits.'); ?></small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input($settings['D_Manual']['id'], array('type' => 'select', 'class' => 'form-control', 'options' => $yesNoOptions, 'value' => $settings['D_Manual']['value'])); ?>
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

