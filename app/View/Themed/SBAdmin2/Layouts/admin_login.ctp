<!DOCTYPE html>
<html lang="en">
    <!--ng-app="CasinoAdminApp"-->
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta http-equiv="Content-Type" charset="text/html; <?= Configure::read('Settings.charset'); ?>"/>
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <base href="/" />

        <title><?= Configure::read('Settings.websiteName'); ?> - Admin</title>
        <?= $this->Html->meta('keywords', (isset($keywords) ? $keywords : Configure::read('Settings.metaKeywords'))); ?>
        <?= $this->Html->meta('description', (isset($description) ? $description : Configure::read('Settings.metaDescription'))); ?>
        <?= $this->Html->meta(array('name' => 'author', 'content' => Configure::read('Settings.metaAuthor'))); ?>
        <?= $this->Html->meta(array('name' => 'reply-to', 'content' => Configure::read('Settings.metaReplayTo'))); ?>
        <?= $this->Html->meta(array('name' => 'copyright', 'content' => Configure::read('Settings.metaCopyright'))); ?>
        <?= $this->Html->meta(array('name' => 'revisit-after', 'content' => Configure::read('Settings.metaRevisitTime'))); ?>
        <?= $this->Html->meta(array('name' => 'identifier-url', 'content' => Configure::read('Settings.metaIdentifierUrl'))); ?>
        <!--<?//= $this->Html->meta('favicon.ico', 'img/casino/favicons/favicon.ico', array('type' => 'icon')); ?>-->

        <?php
        echo $this->Html->css('https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700');
        echo $this->Html->css('fontawesome-free-5.11.2-web/css/all.min.css');
        echo $this->Html->css('ion-icons-2.0.0/css/ion-icons.min.css');
        echo $this->Html->css('jquery-ui-1.12.1/jquery-ui.min.css');
        echo $this->Html->css('bootstrap-4.3.1/bootstrap.min.css');
        echo $this->Html->css('sbadmin2-dash/sb-admin-2.css');
        ?>
    </head>

    <body class="bg-gradient-primary">
        <div class="container">
            <!-- Outer Row -->
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-12 col-md-9">
                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-6 d-none d-lg-flex justify-content-lg-center align-items-lg-center bg-login-image">
                                    <img class="text-center mb-3" src="img/casino/bet-happy-logo-lg.png" height="120"/>
                                </div>
                                <div class="col-lg-6 bg-login-form">
                                    <div class="p-5">
                                        <div class="text-center">


                                            <h1 class="h4 mb-4">Welcome to<br/> <?php echo Configure::read('Settings.websiteName'); ?> <br/>Admin Panel!</h1>
                                        </div>
                                        <?php echo $this->element('flash_message'); ?>
                                        <?php echo $this->Form->create(array('class' => 'form-vertical no-padding no-margin', 'id' => 'loginform')); ?>
                                        <div class="form-group">
                                            <?php echo $this->Form->input('username', array('id' => 'UserUsername', 'class' => "form-control form-control-sm", 'placeholder' => 'Username', 'label' => false, 'div' => false)); ?>
                                        </div>
                                        <div class="form-group">
                                            <?php echo $this->Form->input('password', array('id' => 'UserPassword', 'class' => "form-control form-control-sm", 'placeholder' => 'Password', 'label' => false, 'div' => false)); ?>                                            </div>

                                        <div class="form-group">
                                            <?= $this->Form->input('group_id', array('type' => 'select', 'options' => $groups, 'class' => 'form-control form-control-sm', 'div' => false, 'label' => false)); ?>
                                        </div>

                                        <hr class="custom-hr">
                                        <button type="submit" class="btn btn-default btn-block">
                                            <?= __('Login'); ?>
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?php
        echo $this->Html->script('jquery-3.3.1.min.js');
        echo $this->Html->script('jquery-migrate-1.4.1/jquery-migrate.min.js');
        echo $this->Html->script('jquery-ui-1.12.1/jquery-ui.min.js');
        echo $this->Html->script('popper-1.0/popper.min.js');
        echo $this->Html->script('bootstrap-4.3.1/bootstrap.min.js');
        ?>
    </body>
</html>