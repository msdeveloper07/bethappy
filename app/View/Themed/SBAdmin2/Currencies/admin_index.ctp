<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Settings'); ?></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Currencies'); ?></li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Currencies'); ?></h1>
                <a href="/admin/currencies/add" class="btn btn-success px-4"><?= __('Create Currency'); ?></a>
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
            <div class="col-md-12">
                <div class="table-responsive-sm pt-2">
                    <?= $this->element('list'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

