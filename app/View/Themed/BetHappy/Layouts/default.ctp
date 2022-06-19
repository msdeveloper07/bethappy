<!DOCTYPE html>
<html lang="<?= Configure::Read('Config.Language.ISO6391_code'); ?>" dir="<?= Configure::Read('Config.Language.ISO6391_code') == 'ar' ? 'rtl' : 'ltr'; ?>"  ng-app="CasinoApp">
    <head>
        <title>Bet Happy</title>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta http-equiv="Content-Type" charset="<?= Configure::read('Settings.charset'); ?>"/><!--text/html;-->
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <?php echo $this->fetch('meta'); ?> 
        <base href="/" />
        <?= $this->Html->meta('keywords', (isset($keywords) ? $keywords : Configure::read('Settings.metaKeywords'))); ?>
        <?= $this->Html->meta('description', (isset($description) ? $description : Configure::read('Settings.metaDescription'))); ?>
        <?= $this->Html->meta(array('name' => 'author', 'content' => Configure::read('Settings.metaAuthor'))); ?>
        <?= $this->Html->meta(array('name' => 'reply-to', 'content' => Configure::read('Settings.metaReplayTo'))); ?>
        <?= $this->Html->meta(array('name' => 'copyright', 'content' => Configure::read('Settings.metaCopyright'))); ?>
        <?= $this->Html->meta(array('name' => 'revisit-after', 'content' => Configure::read('Settings.metaRevisitTime'))); ?>
        <?= $this->Html->meta(array('name' => 'identifier-url', 'content' => Configure::read('Settings.metaIdentifierUrl'))); ?>

        <link rel="apple-touch-icon" sizes="180x180" href="img/casino/bet-happy/favicons/apple-touch-icon.png"/>
        <link rel="icon" type="image/png" sizes="32x32" href="img/casino/bet-happy/favicons/favicon-32x32.png"/>
        <link rel="icon" type="image/png" sizes="16x16" href="img/casino/bet-happy/favicons/favicon-16x16.png"/>
        <link rel="manifest" href="img/casino/bet-happy/favicons/site.webmanifest"/>
        <meta name="msapplication-TileColor" content="#da532c"/>
        <meta name="theme-color" content="#ffffff"/>
        <?php
        echo $this->Html->css('https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;700&display=swap');
        echo $this->Html->css('fontawesome-free-5.11.2-web/css/all.min.css');
        echo $this->Html->css('ion-icons-2.0.0/css/ion-icons.min.css');

        //echo $this->Html->script('jquery-3.3.1.min.js');
        echo $this->Html->script('https://code.jquery.com/jquery-3.5.1.js');
        echo $this->Html->script('jquery-migrate-1.4.1/jquery-migrate.min.js');
        echo $this->Html->script('angular-1.7.8/angular.min.js');
        echo $this->Html->script('angular-1.7.8/angular-route.min.js');
        echo $this->Html->script('angular-1.7.8/angular-animate.min.js');
        echo $this->Html->script('angular-1.7.8/angular-touch.min.js');
        echo $this->Html->script('angular-1.7.8/angular-sanitize.min.js');


        //Bootstrap
        echo $this->Html->script('https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js');
        echo $this->Html->css('https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css');
        echo $this->Html->script('https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js');
        echo $this->Html->script('ui-bootstrap-3.0.6/ui-bootstrap-tpls.min.js');

        //translations
        echo $this->Html->script('/casino-angularjs/i18n/en_us.js');
        echo $this->Html->script('/casino-angularjs/i18n/de_de.js');
        echo $this->Html->script('/casino-angularjs/i18n/fr_fr.js');
        echo $this->Html->script('/casino-angularjs/i18n/nl_nl.js');
        echo $this->Html->script('/casino-angularjs/i18n/pt_pt.js');
        echo $this->Html->script('/casino-angularjs/i18n/tr_tr.js');
        echo $this->Html->script('/casino-angularjs/i18n/ar_sa.js');
        echo $this->Html->script('angular-translate-2.18.4/angular-translate.js');

        echo $this->Html->css('jquery-ui-1.12.1/jquery-ui.min.css');
        echo $this->Html->script('jquery-ui-1.12.1/jquery-ui.min.js');


        //Sweet Alert
        echo $this->Html->css('sweet-alert/sweet-alert.css');
        echo $this->Html->script('sweet-alert/sweet-alert.min.js');
        echo $this->Html->script('sweet-alert/angular-sweet-alert.js');

        //AngularJS UI Select with Selectize theme
        echo $this->Html->css('ui-select-0.19.8/ui-select.css');
        echo $this->Html->script('ui-select-0.19.8/ui-select.js');
        echo $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.8.5/css/selectize.default.css');


        //AngularJS Wizard
        echo $this->Html->css('angular-wizard-1.1.1/angular-wizard.css');
        echo $this->Html->script('angular-wizard-1.1.1/angular-wizard.js');

        //AngularJS Intl Tel Input
        echo $this->Html->css('angular-intl-tel-input-1.0.0/intlTelInput.css');
        echo $this->Html->script('angular-intl-tel-input-1.0.0/intlTelInput.js');
        echo $this->Html->script('angular-intl-tel-input-1.0.0/utils.js');
        echo $this->Html->script('angular-intl-tel-input-1.0.0/ng-intl-tel-input.min.js');

        //Angular UI
        echo $this->Html->script('angular-ui/angular-ui.min.js');

        //AngularJS SLIK Carousel
        echo $this->Html->css('angular-slick-3.1.7/slick-1.6.0.min');
        echo $this->Html->css('angular-slick-3.1.7/slick-theme-1.6.0.min');
        echo $this->Html->script('angular-slick-3.1.7/slick-1.6.0.min');
        echo $this->Html->script('angular-slick-3.1.7/angular-slick-3.1.7.js');

        //ng file upload
        echo $this->Html->css('ng-file-upload/ng-file-upload.css');
        echo $this->Html->script('ng-file-upload/ng-file-upload-shim.js');
        echo $this->Html->script('ng-file-upload/ng-file-upload.js');

        echo $this->Html->script('zxcvbn/zxcvbn.js');
        echo $this->Html->script('http://parsleyjs.org/dist/parsley.js');

        echo $this->Html->css('bet-happy/custom.css');
        ?> 
    </head>

    <?= $content_for_layout; ?>

    <?php
//    echo $this->Html->script('jquery-migrate-1.4.1/jquery-migrate.min.js');
//    echo $this->Html->script('jquery-ui-1.12.1/jquery-ui.min.js');
//    echo $this->Html->script('https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js');
//    echo $this->Html->script('https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js');
//    echo $this->Html->script('ui-bootstrap-3.0.6/ui-bootstrap-tpls.min.js');
//    echo $this->Html->script('zxcvbn/zxcvbn.js');
//    echo $this->Html->script('http://parsleyjs.org/dist/parsley.js');
//
//
//    //services
//    echo $this->Html->script('/casino-angularjs/services/headerService.js');
//    echo $this->Html->script('/casino-angularjs/services/footerService.js');
//    echo $this->Html->script('/casino-angularjs/services/usersService.js');
//    echo $this->Html->script('/casino-angularjs/services/countriesService.js');
//    echo $this->Html->script('/casino-angularjs/services/currenciesService.js');
//    echo $this->Html->script('/casino-angularjs/services/languagesService.js');
//    echo $this->Html->script('/casino-angularjs/services/gamesService.js');
//    echo $this->Html->script('/casino-angularjs/services/sliderService.js');
//    echo $this->Html->script('/casino-angularjs/services/pagesService.js');
//    //controllers
//    echo $this->Html->script('/casino-angularjs/controllers/appController.js');
//    echo $this->Html->script('/casino-angularjs/controllers/headerController.js');
//    echo $this->Html->script('/casino-angularjs/controllers/footerController.js');
//    echo $this->Html->script('/casino-angularjs/controllers/usersController.js');
//    echo $this->Html->script('/casino-angularjs/controllers/pagesController.js');
//    echo $this->Html->script('/casino-angularjs/controllers/gamesController.js');
//    echo $this->Html->script('/casino-angularjs/controllers/limitsController.js');
//    echo $this->Html->script('/casino-angularjs/controllers/kycController.js');
//    echo $this->Html->script('/casino-angularjs/controllers/gameplayController.js');
//    echo $this->Html->script('/casino-angularjs/controllers/depositsController.js');
//    echo $this->Html->script('/casino-angularjs/controllers/withdrawsController.js');
    ?>
</html>