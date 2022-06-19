<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <?= $this->Html->link(__('Dashboard'), ['plugin' => false, 'controller' => 'dashboard', 'action' => 'index', 'prefix' => 'admin'], ['escape' => false, 'title' => __('Dashboard')]); ?>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?= __('General Settings'); ?></li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between">
                <h1 class="h3 mb-1 text-gray-800"><?= __('General Settings'); ?></h1>
            </div>
            <br>
        </div>
    </div>
    <!--BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard">
        <?= $this->element('flash_message'); ?>
        <div class="row">
            <div class="col-md-12">
                <p>
                    <?= __('General settings controls some of the most basic configuration settings for your site: your site\'s title and location, who may register an account at your site, and how dates and times are calculated and displayed.'); ?>
                </p>
                <?php echo $this->Form->create('Setting'); ?>
                <ul class="list-group">
                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Website name'); ?><br>
                                    <small class="text-muted font-italic"><?= __('Enter the name of your casino here. Most themes will display this title at the top of every page, and in the reader\'s browser title bar.'); ?></small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input($data['websiteName']['id'], array('value' => $data['websiteName']['value'], 'class' => 'form-control form-control-sm', 'label' => false, 'div' => false)); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Copyright'); ?><br>
                                    <small class="text-muted font-italic"><?= __('Owner rights for product use.'); ?></small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input($data['copyright']['id'], array('value' => $data['copyright']['value'], 'class' => 'form-control form-control-sm', 'label' => false, 'div' => false)); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Website email'); ?><br>
                                    <small class="text-muted font-italic"><?= __('Website contact e-mail address. All website e-mails will be forwarded to this e-mail address.'); ?></small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input($data['websiteEmail']['id'], array('value' => $data['websiteEmail']['value'], 'class' => 'form-control form-control-sm', 'label' => false, 'div' => false)); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Default currency'); ?><br>
                                    <small class="text-muted font-italic"><?= __('Select website main currency. In order to add new please go to Settings -> Currencies.'); ?></small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input($data['defaultCurrency']['id'], array('type' => 'select', 'value' => $data['defaultCurrency']['value'], 'options' => $currencies, 'selected' => $data['defaultCurrency']['value'], 'class' => 'form-control form-control-sm', 'label' => false, 'div' => false)); ?>
                            </div>
                        </div>
                    </li>


                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Default language'); ?><br>
                                    <small class="text-muted font-italic"><?= __('Select website main language. In order to add new language please contact ChalkPro support team.'); ?></small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input($data['defaultLanguage']['id'], array('type' => 'select', 'value' => $data['defaultLanguage']['value'], 'options' => $locales, 'selected' => $data['defaultLanguage']['value'], 'class' => 'form-control form-control-sm', 'label' => false, 'div' => false)); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Default time zone'); ?><br>
                                    <small class="text-muted font-italic"><?= __('Select website main time zone.'); ?></small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input($data['defaultTimezone']['id'], array('type' => 'select', 'value' => $data['defaultTimezone']['value'], 'options' => $time_zones, 'selected' => $data['defaultTimezone']['value'], 'class' => 'form-control form-control-sm', 'label' => false, 'div' => false)); ?>
                            </div>
                        </div>
                    </li>


                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Charset'); ?><br>
                                    <small class="text-muted font-italic"><?= __('Charsets are identifiers used to describe a series of universal characters used in web and internet protocols such as HTML and Microsoft Windows. Default one is "utf-8".'); ?></small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input($data['charset']['id'], array('type' => 'text', 'value' => $data['charset']['value'], 'options' => $charsets, 'selected' => $data['charset']['value'], 'class' => 'form-control form-control-sm', 'label' => false, 'div' => false)); ?>
                            </div>
                        </div>
                    </li>


                    <li class="list-group-item form-group bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Items per page'); ?><br>
                                    <small class="text-muted font-italic"><?= __('Number of maximum rows which will be displayed in one page (only in administration panel).'); ?></small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input($data['itemsPerPage']['id'], array('value' => $data['itemsPerPage']['value'], 'class' => 'form-control form-control-sm', 'label' => false, 'div' => false)); ?>
                            </div>
                        </div>
                    </li>


                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('KYC acceptable file formats'); ?><br>
                                    <small class="text-muted font-italic"><?= __('KYC acceptable file formats.'); ?></small>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->input($data['kyc_file_formats']['id'], array('value' => $data['kyc_file_formats']['value'], 'class' => 'form-control form-control-sm', 'label' => false, 'div' => false)); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Site under maintanance'); ?><br>
                                    <small class="text-muted"><?= __('Enable or disable "Site under maintanance". Main site will not be accessable and all users except administrators will be redirected to maintanance page.'); ?></small>
                                </p>
                            </div>
                            <div class="col-md-4 text-right">
                                <?= $this->Form->input($data['under_maintanance']['id'], array('type' => 'checkbox', 'value' => $data['under_maintanance']['value'], 'checked' => $data['under_maintanance']['value'] ? true : false, 'class' => 'custom-control-input', 'label' => array('class' => 'custom-control-label', 'text' => false), 'div' => 'custom-control custom-switch')); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Referral system'); ?><br>
                                    <small class="text-muted"><?= __('Enable or disable referral system on website.'); ?></small>
                                </p>
                            </div>
                            <div class="col-md-4 text-right">
                                <?= $this->Form->input($data['referals']['id'], array('type' => 'checkbox', 'value' => $data['referals']['value'], 'checked' => $data['referals']['value'] ? true : false, 'class' => 'custom-control-input', 'label' => array('class' => 'custom-control-label', 'text' => false), 'div' => 'custom-control custom-switch')); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Game Slider'); ?><br>
                                    <small class="text-muted"><?= __('Show game slider at front page.'); ?></small>
                                </p>
                            </div>
                            <div class="col-md-4 text-right">
                                <?= $this->Form->input($data['game_slider']['id'], array('type' => 'checkbox', 'value' => $data['game_slider']['value'], 'checked' => $data['game_slider']['value'] ? true : false, 'class' => 'custom-control-input', 'label' => array('class' => 'custom-control-label', 'text' => false), 'div' => 'custom-control custom-switch')); ?>
                            </div>
                        </div>
                    </li>


                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Registration'); ?><br>
                                    <small class="text-muted"><?= __('Enable or disable registration on website.'); ?></small>
                                </p>
                            </div>
                            <div class="col-md-4 text-right">
                                <?= $this->Form->input($data['registration']['id'], array('type' => 'checkbox', 'value' => $data['registration']['value'], 'checked' => $data['registration']['value'] ? true : false, 'class' => 'custom-control-input', 'label' => array('class' => 'custom-control-label', 'text' => false), 'div' => 'custom-control custom-switch')); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item  form-group bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Login'); ?><br>
                                    <small class="text-muted"><?= __('Enable or disable login on website.'); ?></small>
                                </p>
                            </div>
                            <div class="col-md-4 text-right">
                                <?= $this->Form->input($data['login']['id'], array('type' => 'checkbox', 'value' => $data['login']['value'], 'checked' => $data['login']['value'] ? true : false, 'class' => 'custom-control-input', 'label' => array('class' => 'custom-control-label', 'text' => false), 'div' => 'custom-control custom-switch')); ?>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item form-group">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0"><?= __('Password reset'); ?><br>
                                    <small class="text-muted"><?= __('Enable or disable password reset for users on website.'); ?></small>
                                </p>
                            </div>
                            <div class="col-md-4 text-right">
                                <?= $this->Form->input($data['passwordReset']['id'], array('type' => 'checkbox', 'value' => $data['passwordReset']['value'], 'checked' => $data['passwordReset']['value'] ? true : false, 'class' => 'custom-control-input', 'label' => array('class' => 'custom-control-label', 'text' => false), 'div' => 'custom-control custom-switch')); ?>
                            </div>
                        </div>
                    </li>

                </ul>
                <div class="row form-group">
                    <div class="col-sm-12 col-md-3 offset-md-9 col-lg-2 offset-lg-10 my-3">
                        <?php echo $this->Form->submit(__('Save', true), array('class' => 'btn btn-success btn-block')); ?>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
    <!--END PAGE CONTENT-->
</div>