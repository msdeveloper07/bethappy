<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Content'); ?></li>
                         <li class="breadcrumb-item" aria-current="page">
                        <?= $this->Html->link(__('Email Templates'), ['plugin' => false, 'controller' => 'templates', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Email Templates')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Create'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Create Email Template'); ?></h1>
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
                    <?php echo $this->element('add'); ?>
            </div>
        </div>
    </div>
</div>
