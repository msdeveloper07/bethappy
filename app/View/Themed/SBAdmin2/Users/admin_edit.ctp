<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item"><?= __('Players'); ?></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= $this->Html->link(__('Players List'), ['plugin' => false, 'controller' => 'users', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Players List')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= $this->Html->link(__('Player View'), ['plugin' => false, 'controller' => 'users', 'action' => 'view', 'prefix' => 'admin', $user['User']['id']], ['escape' => false, 'title' => __('Player View')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('Player Edit'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('Player Edit'); ?></h1>
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
                <h4><?= __('Account Information'); ?></h4>
                <ul class="list-group mb-4">
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Username', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('User.username', array('label' => false, 'type' => 'text', 'name' => 'data[User][username]', 'class' => 'form-control', 'div' => false)); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('E-mail', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('User.email', array('label' => false, 'type' => 'text', 'name' => 'data[User][email]', 'class' => 'form-control', 'div' => false)); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Phone number', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('User.mobile_number', array('label' => false, 'type' => 'text', 'name' => 'data[User][mobile_number]', 'class' => 'form-control', 'div' => false)); ?>
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
                                <?= $this->Form->input('User.currency_id', User::getFieldHtmlConfig('select', array('label' => false, 'class' => 'form-control', 'div' => false, 'options' => $currencies, 'defaultValue' => $user['User']['currency_id']))); ?>                            
                            </div>
                        </div>
                    </li>

                    <!--                    <li class="list-group-item form-group">
                                            <div class="row align-items-center">
                                                <div class="col-md-4">
                                                    <p class="mb-0"><?//= __('Password', true); ?><br>
                                                    </p>
                                                </div>
                                                <div class="col-md-8">
                    <?//= $this->Form->input('User.password', array('label' => false, 'type' => 'text', 'name' => 'data[User][password]', 'class' => 'form-control', 'div' => false)); ?>                            
                                                </div>
                                            </div>
                                        </li>-->


                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Status', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('User.status', User::getFieldHtmlConfig('select', array('label' => false, 'class' => 'form-control', 'options' => User::$User_Statuses_Humanized, 'defaultValue' => $user['User']['status']))); ?>                            
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Category', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('User.category', User::getFieldHtmlConfig('select', array('label' => false, 'class' => 'form-control', 'options' => $user_categories, 'defaultValue' => $user['User']['category_id']))); ?>      
                            </div>
                        </div>
                    </li>


                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Newsletter', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('User.newsletter', User::getFieldHtmlConfig('switch', array('label' => false, 'name' => 'data[User][newsletter]', 'id' => 'UserNewsletter'))); ?>                            
                            </div>
                        </div>
                    </li>


                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Bonus', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('User.bonus_allow', User::getFieldHtmlConfig('switch', array('label' => false, 'name' => 'data[User][bonus_allow]', 'id' => 'UserBonus'))); ?>                            
                            </div>
                        </div>
                    </li>


                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Raventrack', true); ?> <?= __('Affiliate', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <select name="data[User][affiliate_id]" class="form-control">
                                    <option></option>
                                    <?php foreach ($affiliates as $affiliate): ?>
                                        <option value="<?= $affiliate['Raventrack']['affiliate_id']; ?>" <?= ($user['User']['affiliate_id'] == $affiliate['Raventrack']['affiliate_id']) ? "selected" : ""; ?>><?= $affiliate['Raventrack']['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>                            
                            </div>
                        </div>
                    </li>




                </ul>


                <h4><?= __('Personal Information'); ?></h4>
                <ul class="list-group mb-4">
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('First name', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('User.first_name', array('label' => false, 'type' => 'text', 'name' => 'data[User][first_name]', 'class' => 'form-control', 'div' => false)); ?>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Last name', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('User.last_name', array('label' => false, 'type' => 'text', 'name' => 'data[User][last_name]', 'class' => 'form-control', 'div' => false)); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Date of birth', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('User.date_of_birth', User::getFieldHtmlConfig('date', array('label' => false, 'name' => 'data[User][date_of_birth]', 'id' => 'UserDateOfBirth', 'defaultDate' => $user['User']['date_of_birth']))); ?>                            
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Gender', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="UserGenderMale" name="data[User][gender]" class="custom-control-input" value="male" <?= $user['User']['gender'] === 'male' ? 'checked' : ''; ?>/>
                                    <label class="custom-control-label" for="UserGenderMale"><?= __('Male'); ?></label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="UserGenderFemale" name="data[User][gender]" class="custom-control-input" value="female" <?= $user['User']['gender'] === 'female' ? 'checked' : ''; ?>/>
                                    <label class="custom-control-label" for="UserGenderFemale"><?= __('Female'); ?></label>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>

                <h4><?= __('Address Information'); ?></h4>
                <ul class="list-group">
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Address', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('User.address1', array('label' => false, 'type' => 'text', 'name' => 'data[User][address1]', 'class' => 'form-control', 'div' => false)); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('City', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('User.city', array('label' => false, 'type' => 'text', 'name' => 'data[User][city]', 'class' => 'form-control', 'div' => false)); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <p class="mb-0"><?= __('Zip Code', true); ?><br>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <?= $this->Form->input('User.zip_code', array('label' => false, 'type' => 'text', 'name' => 'data[User][zip_code]', 'class' => 'form-control', 'div' => false)); ?>
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
                                <?= $this->Form->input('User.country_id', User::getFieldHtmlConfig('select', array('label' => false, 'options' => $countries, 'defaultValue' => $user['User']['country_id']))); ?> 
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

