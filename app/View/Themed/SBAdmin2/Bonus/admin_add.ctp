

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Bonuses'); ?></li>
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Bonuses'), ['plugin' => false, 'controller' => 'Bonus', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Bonus')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Create'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Create Bonus'); ?></h1>
            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12">
                <?= $this->element('tabs'); ?>

            </div>
            <div class="col-md-12 pt-2">
                <?= $this->Form->create($model, array('url' => $url, 'type' => 'file')); ?>
                <ul class="list-group">

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0">
                                    <?= __('Bonus'); ?>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('Bonus.type_id', Bonus::getFieldHtmlConfig('select', array('label' => false, 'options' => $Bonus_Types))); ?>
                            </div>
                        </div>
                    </li>


                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0">
                                    <?= __('Initial amount'); ?>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('Bonus.initial_amount', array('label' => false, 'div' => false, 'class' => 'form-control', 'type' => 'text', 'name' => 'data[Bonus][initial_amount]')); ?>                            </div>
                        </div>
                    </li>


                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0">
                                    <?= __('Payoff amount'); ?>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('Bonus.payoff_amount', array('label' => false, 'div' => false, 'type' => 'text', 'class' => 'form-control', 'name' => 'data[Bonus][payoff_amount]')); ?>                            </div>
                        </div>
                    </li>
                </ul>
                <div class="row form-group">
                    <div class="col-sm-12 col-md-3 offset-md-9 col-lg-2 offset-lg-10 my-3">
                        <?php echo $this->Form->submit(__('Create', true), array('class' => 'btn btn-success btn-block')); ?>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>
