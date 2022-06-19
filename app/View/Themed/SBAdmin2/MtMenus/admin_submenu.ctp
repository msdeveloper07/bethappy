<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Content'); ?></li>
                    <li class="breadcrumb-item"><?= __('Menus'); ?></li>
                    <li class="breadcrumb-item" aria-current="page">
                        <?= $this->Html->link(__('Main Menu'), ['plugin' => false, 'controller' => 'mt_menus', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Menus')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Submenus'); ?></li>
                </ol>
            </nav>

                  <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Submenus'); ?></h1>
                <a href="/admin/mt_menus/submenuadd" class="btn btn-success px-4"><?= __('Create Submenu'); ?></a>
            </div>
            
            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __(''); ?></h1>
            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message');?>
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


