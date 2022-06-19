<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Payments'); ?></li>
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Limits'), ['plugin' => 'payments', 'controller' => 'limits', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Limits')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Create'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Create Limit'); ?></h1>
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

                <?= $this->MyForm->create($model, array('type' => 'file')); ?>
                <ul class="list-group">
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Limit Type', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <select name="data[Limit][limit_type]" class="form-control">  
                                    <option></option>
                                    <?php foreach ($limit_types as $key => $limit_type): ?>
                                        <option value="<?= $key; ?>"><?= $limit_type; ?></option>
                                    <?php endforeach; ?>
                                </select> 
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
                                <select name="data[Limit][country_id]" class="form-control"> 
                                    <option></option>
                                    <?php foreach ($countries as $country): ?>
                                        <option value="<?= $country['Country']['id']; ?>"><?= $country['Country']['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>                                   
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
                                <select name="data[Limit][currency_id]" class="form-control">
                                    <option></option>
                                    <?php foreach ($currencies as $currency): ?>
                                        <option value="<?= $currency['Currency']['id']; ?>" <?= ($user['User']['currency_id'] == $currency['Currency']['id']) ? "selected" : ""; ?>><?= $currency['Currency']['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>  
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Payment Method', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">

                                <select name="data[Limit][payment_method_id]" class="form-control">
                                    <option></option>
                                    <?php foreach ($payment_methods as $payment_method): ?>
                                        <option value="<?= $payment_method['PaymentMethod']['id']; ?>"><?= $payment_method['PaymentMethod']['name']; ?></option>
                                    <?php endforeach; ?>
                                </select> 
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
                                <select name="data[Limit][user_id]" class="form-control">
                                    <option></option>
                                    <?php foreach ($users as $key => $user): ?>                               
                                        <option value="<?= $user['User']['id']; ?>" <?= ($limit['Limit']['user_id'] == $user['User']['id']) ? "selected" : ""; ?>><?= $user['User']['username']; ?></option>
                                    <?php endforeach; ?>
                                </select>                                  
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
                                <?= $this->MyForm->input('Limit.min', array('label' => false, 'type' => 'text', 'name' => 'data[Limit][min]', 'class' => 'form-control')); ?>
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
                                <?= $this->MyForm->input('Limit.max', array('label' => false, 'type' => 'text', 'name' => 'data[Limit][max]', 'class' => 'form-control')); ?>
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
                                <?= $this->MyForm->input('Limit.daily', array('label' => false, 'type' => 'text', 'name' => 'data[Limit][daily]', 'class' => 'form-control')); ?>
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
                                <?= $this->MyForm->input('Limit.weekly', array('label' => false, 'type' => 'text', 'name' => 'data[Limit][weekly]', 'class' => 'form-control')); ?>
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
                                <?= $this->MyForm->input('Limit.monthly', array('label' => false, 'type' => 'text', 'name' => 'data[Limit][monthly]', 'class' => 'form-control')); ?>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="row form-group">
                    <div class="col-sm-12 col-md-3 offset-md-9 col-lg-2 offset-lg-10 my-3">
                        <?php echo $this->Form->submit(__('Save', true), array('class' => 'btn btn-success btn-block')); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
