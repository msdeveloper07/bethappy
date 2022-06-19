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
                        <?= $this->Html->link(__('KYC'), ['plugin' => false, 'controller' => 'KYC', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('KYC')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Edit'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Edit KYC'); ?></h1>
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
                <?= $this->Form->create($model, array('type' => 'file')); ?>

                <ul class="list-group mb-4">

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Document', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <a href="img/kyc/<?= $data['KYC']['kyc_data_url']; ?>">
                                    <img src="img/<?= $client_folder ."/kyc/". $data['KYC']['kyc_data_url']; ?>" width="100%"/> 
                                </a>
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
                                <?= $this->Html->link($data['User']['username'], ['plugin' => false, 'controller' => 'users', 'action' => 'view', 'prefix' => 'admin', $data['User']['id']], ['escape' => false]); ?>
                                <?= $this->Form->input('KYC.user_id', array('label' => false, 'type' => 'hidden', 'name' => 'data[KYC][user_id]', 'class' => 'form-control', 'div' => false, disabled)); ?>
                            </div>
                        </div>
                    </li>



                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('KYC Data URL', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('KYC.kyc_data_url', array('label' => false, 'type' => 'text', 'value' => $data['KYC']['kyc_data_url'], 'name' => 'data[KYC][kyc_data_url]', 'class' => 'form-control', 'div' => false, disabled)); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('File Type', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('KYC.file_type', array('label' => false, 'type' => 'text', 'value' => $data['KYC']['file_type'], 'name' => 'data[KYC][file_type]', 'class' => 'form-control', 'div' => false, disabled)); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('KYC Type', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('KYC.kyc_type', array('type' => 'text', 'value' => KYC::$humanizeTypes[$data['KYC']['kyc_type']], 'label' => false, 'class' => 'form-control', disabled)); ?>                            
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Reason', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('KYC.reason', array('label' => false, 'type' => 'text', 'value' => $data['KYC']['reason'], 'name' => 'data[KYC][reason]', 'class' => 'form-control', 'div' => false)); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Status', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('KYC.status', KYC::getFieldHtmlConfig('select', array('label' => false, 'class' => 'form-control', 'options' => KYC::$humanizeStatuses, 'defaultValue' => $data['KYC']['status']))); ?>                            
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Expires', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('KYC.expires', array('label' => false, 'type' => 'text', 'name' => 'data[KYC][expires]', 'class' => 'form-control datetimepicker-filter', 'div' => false)); ?>
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
                                <?= $this->Form->input('KYC.created', array('label' => false, 'type' => 'hidden', 'value' => $data['KYC']['created'], 'name' => 'data[KYC][created]', 'class' => 'form-control', 'div' => false)); ?>
                                <?= date('d-m-Y H:i:s', strtotime($data['KYC']['created'])); ?>
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
