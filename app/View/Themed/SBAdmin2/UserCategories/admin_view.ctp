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
                        <?= $this->Html->link(__('Categories'), ['plugin' => false, 'controller' => 'userCategories', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Categories')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('View'); ?></li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('View Category'); ?></h1>
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
                    <?= $this->element('view'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<style> 
    .color-palette {
        font-size: 13pt;
        display: block;
        line-height: 16px;
        padding: 0px 5px;
        font-family: Arial,sans-serif;
        color: #FFF !important;
        text-shadow: 0px 1px rgba(0, 0, 0, 0.25);
        border-style: solid;
        border-radius: 8px;
        box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.08), 0px 1px rgba(255, 255, 255, 0.3) inset;
    }
</style>


