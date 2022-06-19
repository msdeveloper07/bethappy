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
                        <?= $this->Html->link(__('Submenus'), ['plugin' => false, 'controller' => 'mt_menus', 'action' => 'submenu', 'prefix' => 'admin', $data['MtSubmenu']['mt_id']], ['escape' => false, 'title' => __('Submenus')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Edit'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Edit Submenu'); ?></h1>
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
                <?= $this->Form->create('MtSubmenu'); ?>

                <ul class="list-group">


                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0">
                                    <?= __('Parent Menu'); ?>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?=
                                $this->Form->input('MtSubmenu.mt_id', array(
                                    'required' => true,
                                    'label' => false,
                                    'type' => 'select',
                                    'default' => $data['MtSubmenu']['mt_id'],
                                    'options' => $mtmenus,
                                    'class' => 'form-control'));
                                ?>
                            </div>
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
                                <?= $this->Form->input('MtSubmenu.title', array('label' => false, 'class' => 'form-control', 'value' => $data['MtSubmenu']['title'])); ?>
                            </div>
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
                                <?= $this->Form->input('MtSubmenu.url', array('label' => false, 'class' => 'form-control', 'value' => $data['MtSubmenu']['url'])); ?>
                            </div>
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
                                <?= $this->Form->input('MtSubmenu.order', array('label' => false, 'type' => 'number', 'class' => 'form-control', 'value' => $data['MtSubmenu']['order'])); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0">
                                    <?= __('Active'); ?>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <div class="custom-control custom-switch">
                                    <?= $this->Form->input('MtSubmenu.active', array('type' => 'checkbox', 'checked' => $data['MtSubmenu']['active'] == 1 ? true : false, 'hiddenField' => false, 'label' => false, 'div' => false, 'id' => 'MtSubmenu.active', 'class' => 'custom-control-input')); ?>
                                    <label class="custom-control-label" for="MtSubmenu.active"></label>
                                </div>
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


