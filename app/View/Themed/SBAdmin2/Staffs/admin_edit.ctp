<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Staff'); ?></li>
                    <li class="breadcrumb-item "> 
                        <?= $this->Html->link(__('Staff list'), ['plugin' => false, 'controller' => 'staffs', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Audit log')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Edit'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><i class="fas fa fa-clipboard-list"></i> <?= __('Edit'); ?></h1>

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
                <div class="tab-content">
                    <?php echo $this->element('edit'); ?>
                </div>
            </div>
        </div>
    </div>
</div>