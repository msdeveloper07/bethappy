
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Settings'); ?></li>
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Languages'), ['plugin' => false, 'controller' => 'languages', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Languages')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('View'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('View Language'); ?></h1>
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
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Limit Type', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $limit['Limit']['limit_type']; ?>
                            </div>
                        </div>
                    </li>
                <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Currency', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                               <?= $limit['Limit']['Currency']['name']; ?>
                            </div>
                        </div>
                    </li>
                      <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Country', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">    
                                 <img src="https://www.countryflags.io/<?= $limit['Limit']['Country']['alpha2_code']; ?>/shiny/24.png"> <?= $limit['Limit']['Country']['name']; ?>                                                              
                            </div>
                        </div>
                    </li>
                     <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Provider', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $limit['Limit']['provider_id']; ?>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('User', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $limit['Limit']['User']['username']; ?>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Min', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $limit['Limit']['min']; ?>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Max', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $limit['Limit']['max']; ?>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Daily', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $limit['Limit']['daily']; ?>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Weekly', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $limit['Limit']['weekly']; ?>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Monthly', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $limit['Limit']['monthly']; ?>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Created', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $limit['Limit']['created']; ?>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Modified', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $limit['Limit']['modified']; ?>
                            </div>
                        </div>
                    </li>
            </div>
        </div>
    </div>
</div>
