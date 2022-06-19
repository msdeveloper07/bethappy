<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Payments'); ?></li>
                    <li class="breadcrumb-item"><?= __('Withdraws'); ?></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Risk management'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Risk management'); ?></h1>
            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12">
                <p>

                    <?php
                    $options = array(
                        'inputDefaults' => array(
                            'label' => false,
                            'div' => false)
                    );
                    $yesNoOptions = array('1' => 'Yes', '0' => 'No');
                    ?>

                    <?php echo __('Please set withdraw risk management settings below:'); ?>

                </p>
                <?php echo $this->Form->create('Setting', $options); ?>
                <ul class="list-group">

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0">
                                    <?= __('Allow withdraws'); ?>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?php echo $this->Form->input($data['withdraws']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['withdraws']['value'], 'class' => 'form-control')); ?>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item form-group bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Minimum amount to ask KYC'); ?>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?php echo $this->Form->input($data['kyc_limit_wthdraw']['id'], array('value' => $data['kyc_limit_wthdraw']['value'], 'class' => 'form-control')); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Lowest amount for withdraw'); ?>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?php echo $this->Form->input($data['minWithdraw']['id'], array('value' => $data['minWithdraw']['value'], 'class' => 'form-control')); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Highest amount for withdraw'); ?>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?php echo $this->Form->input($data['maxWithdraw']['id'], array('value' => $data['maxWithdraw']['value'], 'class' => 'form-control')); ?>
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
</div>




