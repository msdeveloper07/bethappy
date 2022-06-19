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
                    <li class="breadcrumb-item active" aria-current="page"><?= __('View'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('View KYC'); ?></h1>
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
                                <?= $data['KYC']['kyc_data_url']; ?>                            
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
                                <?= $data['KYC']['file_type']; ?> 
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
                                <?= KYC::$humanizeTypes[$data['KYC']['kyc_type']]; ?> 
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
                                <?= $data['KYC']['reason']; ?>                         
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
                                <?= $this->element('status_button', array('status' => $data['KYC']['status'])); ?>                               
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
                                <?php if (!empty($data['KYC']['expires'])): ?>
                                    <?= date('d-m-Y H:i:s', strtotime($data['KYC']['expires'])); ?>   
                                <?php endif; ?>
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
                                <?= date('d-m-Y H:i:s', strtotime($data['KYC']['created'])); ?>
                            </div>
                        </div>
                    </li>
                </ul>


            </div>
        </div>
    </div>
</div>
