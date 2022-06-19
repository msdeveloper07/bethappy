
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
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Affiliate Settings'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Affiliate Settings'); ?></h1>
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
                <?= $this->MyForm->create('Affiliate'); ?>   
                <ul class="list-group">
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Affiliate'); ?></p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input('parent_id', array('options' => $affiliate_array, 'label' => false, 'default' => $data['User']['affiliate_id'], 'class' => 'form-control')); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Custom Affiliate ID'); ?></p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input('affiliate_custom_id', array('type' => 'text', 'label' => false, 'value' => $data['Affiliate']['affiliate_custom_id'], 'class' => 'form-control', 'disabled')); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Referal ID'); ?></p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input('referral_id', array('type' => 'text', 'label' => false, 'value' => $data['Affiliate']['referral_id'], 'class' => 'form-control', 'disabled')); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Created'); ?></p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input('created', array('label' => false, 'value' => date('d-m-Y H:s', strtotime($data['Affiliate']['created'])), 'class' => 'form-control', 'disabled')); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Modified'); ?></p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input('modified', array('label' => false, 'value' => date('d-m-Y H:s', strtotime($data['Affiliate']['modified'])), 'class' => 'form-control', 'disabled')); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Percentage'); ?></p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input('percentage', array('label' => false, 'value' => $data['Affiliate']['percentage'], 'class' => 'form-control', 'disabled')); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Live casino percentage'); ?></p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input('lc_percentage', array('type' => 'text', 'label' => false, 'value' => $data['Affiliate']['lc_percentage'], 'class' => 'form-control', 'disabled')); ?>
                            </div>
                        </div>
                    </li>
                </ul>

                <div class="row form-group">
                    <div class="col-sm-12 col-md-3 offset-md-9 col-lg-2 offset-lg-10 my-3">
                        <?php echo $this->Form->submit(__('Save', true), array('class' => 'btn btn-success btn-block')); ?>
                    </div>
                </div>
                <?= $this->Form->end(); ?>

            </div>
        </div>
    </div>
</div>



<!--<?//= $this->Form->input('parent_id', array('options' => $affiliate_array, 'label' => false, 'default' => $data['Affiliate']['parent_id'])); ?>-->




