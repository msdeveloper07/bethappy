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
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Fund'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Fund Player'); ?></h1>
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
                <p>     <?= __('Fund user') . ' <b>' . $user['User']['username'] . ".</b>" ?>
                    <?= __('To fund just enter amount into the input field and press submit. For example if you want to fund, just credit user with 100, just enter that amount into field and press submit.'); ?>
                </p>

                <?= $this->Form->create(); ?>
                <ul class="list-group">
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <?= __('Amount'); ?>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('amount', array('label' => false, 'class' => 'form-control')); ?>
                            </div>
                        </div>
                    </li>


                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <?= __('Method'); ?>
                            </div>
                            <div class="col-md-8">

                                <?=
                                $this->Form->input('method', array('class' => 'form-control',
                                    'options' => array('Cashback'=>__('Cahback'), 'Reward'=>__('Reward')),
                                    'empty' => ' '
                                ));
                                ?>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <?= __('Comments'); ?>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('comments', array('type' => 'textarea', 'label' => false, 'class' => 'form-control')); ?>
                            </div>
                        </div>
                    </li>


                </ul>

                <div class="row form-group">
                    <div class="col-sm-12 col-md-3 offset-md-9 col-lg-2 offset-lg-10 my-3">
                        <?php echo $this->Form->submit(__('Submit', true), array('class' => 'btn btn-success btn-block')); ?>
                    </div>
                </div>
                <?= $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>