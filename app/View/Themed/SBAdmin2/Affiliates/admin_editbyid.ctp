
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Affiliates'); ?></li>
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Affiliates List'), ['plugin' => false, 'controller' => 'affiliates', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Affiliates List')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Edit'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Affiliates List'); ?></h1>
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

                <?php echo $this->MyForm->create('Affiliate'); ?>
                <ul class="list-group">
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0">
                                    <?= __('Custom Affiliate ID'); ?>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('affiliate_custom_id', array('type' => 'text', 'label' => false, 'value' => $data['Affiliate']['affiliate_custom_id'], 'class' => 'form-control')); ?>
                            </div>
                        </div>

                    </li>
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0">
                                    <?= __('Parent'); ?>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('parent_id', array('options' => $affiliate_array, 'label' => false, 'default' => $data['Affiliate']['parent_id'], 'class' => 'form-control')); ?>
                            </div>
                        </div>

                    </li>
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0">
                                    <?= __('Percentage'); ?>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <td><?= $this->Form->input('percentage', array('label' => false, 'value' => $data['Affiliate']['percentage'], 'class' => 'form-control')); ?>

                            </div>
                        </div>

                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0">
                                    <?= __('Live Casino Percentage'); ?>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('lc_percentage', array('label' => false, 'value' => $data['Affiliate']['lc_percentage'], 'class' => 'form-control')); ?>
                            </div>
                        </div>

                    </li>

                </ul>
                <?= $this->Form->hidden('id', array('value' => $data['Affiliate']['id'])); ?>
                <?= $this->Form->hidden('user_id', array('value' => $data['Affiliate']['user_id'])); ?>
                <div class="row form-group">
                    <div class="col-sm-12 col-md-3 offset-md-9 col-lg-2 offset-lg-10 my-3">
                        <?php echo $this->Form->submit(__('Save', true), array('class' => 'btn btn-success btn-block')); ?>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
        <!--END PAGE CONTENT-->
    </div>
</div>

<!--<?//= $this->Form->input('created', array('label' => false, 'value' => $data['Affiliate']['created'])); ?>-->
<!--<?//= $this->Form->input('modified', array('label' => false, 'value' => $data['Affiliate']['modified'])); ?>-->
