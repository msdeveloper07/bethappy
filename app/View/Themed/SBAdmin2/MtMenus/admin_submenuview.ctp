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
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Main Menu'), ['plugin' => false, 'controller' => 'mt_menus', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Main Menu')]); ?>
                    </li>
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Submenus'), ['plugin' => false, 'controller' => 'mt_menus', 'action' => 'submenu', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Submenus')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('View'); ?></li>

                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('View Submenu'); ?></h1>
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
                <ul class="list-group">
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0">
                                    <?= __('Parent Menu'); ?>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $data['MtSubmenu']['title']; ?>

                            </div>
                    </li>
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0">
                                    <?= __('Title'); ?>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $data['MtMenu']['title']; ?>

                            </div>
                    </li>
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0">
                                    <?= __('URL'); ?>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $data['MtMenu']['url']; ?>

                            </div>
                    </li>
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0">
                                    <?= __('Order'); ?>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $data['MtMenu']['order']; ?>

                            </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>


