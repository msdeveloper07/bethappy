<div class="container-fluid">
    <div class="row">
           <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Players'); ?></li>
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Categories'), ['plugin' => false, 'controller' => 'UserCategories', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Categories')]); ?>
                    </li>
                       <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Custom Settings'), ['plugin' => false, 'controller' => 'CategoriesSettings', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Custom Settings')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Edit'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Edit'); ?></h1>
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
                <?= $this->element('edit'); ?>
            </div>
        </div>
    </div>
</div>
