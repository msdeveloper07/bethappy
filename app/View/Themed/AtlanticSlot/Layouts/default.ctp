<!DOCTYPE html>
<html lang="en" ng-app="CasinoApp">
    <head>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-M7CGM7LD9H"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'G-M7CGM7LD9H');
        </script>
        <title>Atlantic Slot Casino</title>
        <meta charset="utf-8" />

        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta http-equiv="Content-Type" charset="<?= Configure::read('Settings.charset'); ?>"/><!--text/html;-->
        <meta name="apple-mobile-web-app-capable" content="yes" />

        <meta name="google-site-verification" content="kil9AxhfsNri8oqYNbaBdqK_h8hAGYdZ9g6C8_Vskq4" />
        <base href="/" />
        <!--<?//= Configure::read('Settings.websiteName'); ?> Casino-->
        <?= $this->Html->meta('keywords', (isset($keywords) ? $keywords : Configure::read('Settings.metaKeywords'))); ?>
        <?= $this->Html->meta('description', (isset($description) ? $description : Configure::read('Settings.metaDescription'))); ?>
        <?= $this->Html->meta(array('name' => 'author', 'content' => Configure::read('Settings.metaAuthor'))); ?>
        <?= $this->Html->meta(array('name' => 'reply-to', 'content' => Configure::read('Settings.metaReplayTo'))); ?>
        <?= $this->Html->meta(array('name' => 'copyright', 'content' => Configure::read('Settings.metaCopyright'))); ?>
        <?= $this->Html->meta(array('name' => 'revisit-after', 'content' => Configure::read('Settings.metaRevisitTime'))); ?>
        <?= $this->Html->meta(array('name' => 'identifier-url', 'content' => Configure::read('Settings.metaIdentifierUrl'))); ?>
        <!--<?//= $this->Html->meta('favicon.ico', 'img/casino/favicons/favicon.ico', array('type' => 'icon')); ?>-->

        <link rel="apple-touch-icon" sizes="180x180" href="img/casino/favicons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="img/casino/favicons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="img/casino/favicons/favicon-16x16.png">
        <link rel="manifest" href="img/casino/favicons/site.webmanifest">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
        <?php
        echo $this->Html->css('https://fonts.googleapis.com/css2?family=Berkshire+Swash&display=swap');
        echo $this->Html->css('https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;600;800&display=swap');
        echo $this->Html->css('fontawesome-free-5.11.2-web/css/all.min.css');
        echo $this->Html->css('ion-icons-2.0.0/css/ion-icons.min.css');

        echo $this->Html->script('jquery-3.3.1.min.js');
        echo $this->Html->script('angular-1.7.8/angular.min.js');
        echo $this->Html->script('angular-1.7.8/angular-route.min.js');
        echo $this->Html->script('angular-1.7.8/angular-animate.min.js');
        echo $this->Html->script('angular-1.7.8/angular-touch.min.js');
        echo $this->Html->script('angular-1.7.8/angular-sanitize.min.js');

        echo $this->Html->css('jquery-ui-1.12.1/jquery-ui.min.css');
        echo $this->Html->css('bootstrap-4.3.1/bootstrap.min.css');

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

        echo $this->Html->css('casino/custom.css');
        ?> 

        <?php echo $this->fetch('meta'); ?> 
    </head>

    <body ng-controller="appController">

        <?= $this->element('casino_header'); ?>

        <div class="app-content">
            <div ng-view autoscroll="true"></div>
        </div>

        <?= $this->element('casino_footer'); ?>

        <?php
        echo $this->Html->script('jquery-migrate-1.4.1/jquery-migrate.min.js');
        echo $this->Html->script('jquery-ui-1.12.1/jquery-ui.min.js');
        echo $this->Html->script('bootstrap-4.3.1/bootstrap.min.js');
        echo $this->Html->script('ui-bootstrap-3.0.6/ui-bootstrap-tpls.min.js');
        echo $this->Html->script('zxcvbn/zxcvbn.js');

        echo $this->Html->script('/casino-angularjs/app.js');
        //services
        echo $this->Html->script('/casino-angularjs/services/headerService.js');
        echo $this->Html->script('/casino-angularjs/services/usersService.js');
        echo $this->Html->script('/casino-angularjs/services/countriesService.js');
        echo $this->Html->script('/casino-angularjs/services/currenciesService.js');
        echo $this->Html->script('/casino-angularjs/services/gamesService.js');
        //controllers
        echo $this->Html->script('/casino-angularjs/controllers/appController.js');
        echo $this->Html->script('/casino-angularjs/controllers/headerController.js');
        echo $this->Html->script('/casino-angularjs/controllers/gamesController.js');
        echo $this->Html->script('/casino-angularjs/controllers/pagesController.js');
        echo $this->Html->script('/casino-angularjs/controllers/usersController.js');
        echo $this->Html->script('/casino-angularjs/controllers/limitsController.js');
        echo $this->Html->script('/casino-angularjs/controllers/kycController.js');
        echo $this->Html->script('/casino-angularjs/controllers/gameplayController.js');
        echo $this->Html->script('/casino-angularjs/controllers/depositsController.js');
        echo $this->Html->script('/casino-angularjs/controllers/withdrawsController.js');
//        echo $this->Html->script('slim-scroll-1.3.8/slim-scroll.min.js');
//        echo $this->Html->script('jquery-cookie-1.4.1/jquery.cookie.js');
//
//
//        echo $this->Html->script('jquery-isotope-1.5.25/jquery.isotope.min.js');
//        echo $this->Html->script('lightbox-2.6/lightbox.min.js');
//
//        echo $this->Html->script('full-calendar-1.6.4/full-calendar.js');
//        echo $this->Html->script('bootstrap-calendar/bootstrap-calendar.min.js');
//        echo $this->Html->script('jquery-gritter-1.7.4/jquery.gritter.js');
//        echo $this->Html->script('jquery-tag-it/jquery.tag-it.min.js');
//        echo $this->Html->script('bootstrap-wysi-html5/wysi-html5-0.3.0.js');
//        echo $this->Html->script('bootstrap-wysi-html5/bootstrap-wysi-html5.js');
//        echo $this->Html->script('superbox-1.0.0/superbox.js');
//
//
//
//        echo $this->Html->script('bootstrap-3-editable/bootstrap-editable.min.js');
//        echo $this->Html->script('bootstrap-3-editable/extensions/address.js');
//        echo $this->Html->script('bootstrap-3-editable/extensions/typeahead.js');
//        echo $this->Html->script('bootstrap-3-editable/extensions/typeaheadjs.js');
//        echo $this->Html->script('bootstrap-date-picker-2.0/bootstrap-date-picker.js');
//        echo $this->Html->script('bootstrap-date-time-picker/bootstrap-date-time-picker.min.js');
//
//        echo $this->Html->script('jquery-mockjax-1.5.0/jquery.mockjax.js');
//        echo $this->Html->script('moment-2.6.0/moment.min.js');
//        echo $this->Html->script('ion-range-slider-1.9.0/ion.range-slider.min.js');
//        echo $this->Html->script('bootstrap-color-picker-2.0/bootstrap-color-picker.min.js');
//        echo $this->Html->script('masked-input-1.3.1/masked-input.min.js');
//        echo $this->Html->script('bootstrap-time-picker-0.2.3/bootstrap-time-picker.min.js');
//        echo $this->Html->script('bootstrap-combobox-1.1.5/bootstrap-combobox.js');
//        echo $this->Html->script('password-indicator/password-indicator.js');
//        echo $this->Html->script('bootstrap-select-1.5.4/bootstrap-select.min.js');
//        echo $this->Html->script('bootstrap-tags-input-0.3.9/bootstrap-tags-input.min.js');
//        echo $this->Html->script('bootstrap-tags-input-0.3.9/bootstrap-tags-input-typeahead.js');
//        echo $this->Html->script('bootstrap-date-range-picker-1.3.21/date-range-picker.js');
//        echo $this->Html->script('bootstrap-eonasdan-date-time-picker-4.14.30/bootstrap-date-time-picker.min.js');
//
//
//        echo $this->Html->script('jquery-file-upload-5.40.1/extensions/jquery.ui.widget.js');
//        echo $this->Html->script('jquery-file-upload-5.40.1/extensions/tmpl.min.js');
//        echo $this->Html->script('jquery-file-upload-5.40.1/extensions/load-image.min.js');
//        echo $this->Html->script('jquery-file-upload-5.40.1/extensions/canvas-to-blob.min.js');
//        echo $this->Html->script('blue-imp-gallery/jquery.blue-imp-gallery.min.js');
//        echo $this->Html->script('jquery-file-upload-5.40.1/jquery.iframe-transport.js');
//        echo $this->Html->script('jquery-file-upload-5.40.1/jquery.file-upload.js');
//        echo $this->Html->script('jquery-file-upload-5.40.1/jquery.file-upload-process.js');
//        echo $this->Html->script('jquery-file-upload-5.40.1/jquery.file-upload-image.js');
//        echo $this->Html->script('jquery-file-upload-5.40.1/jquery.file-upload-audio.js');
//        echo $this->Html->script('jquery-file-upload-5.40.1/jquery.file-upload-video.js');
//        echo $this->Html->script('jquery-file-upload-5.40.1/jquery.file-upload-validate.js');
//        echo $this->Html->script('jquery-file-upload-5.40.1/jquery.file-upload-ui.js');
//
//
//        echo $this->Html->script('ckeditor-4.11.2/ckeditor.js');
//
//
//        echo $this->Html->script('switchery/switchery.min.js');
//        echo $this->Html->script('powerange/powerange.min.js');
//        echo $this->Html->script('parsley-2.0.0/parsley.js');
//        echo $this->Html->script('bootstrap-wizard-1.3/bwizard.js');
//        echo $this->Html->script('jstree-3.1.0/jstree.min.js');
//
//
//        echo $this->Html->script('jquery-flot/jquery.flot.min.js');
//        echo $this->Html->script('jquery-flot/jquery.flot.time.min.js');
//	echo $this->Html->script('jquery-flot/jquery.flot.resize.min.js');
//	echo $this->Html->script('jquery-flot/jquery.flot.pie.min.js');
//	echo $this->Html->script('jquery-flot/jquery.flot.stack.min.js');
//	echo $this->Html->script('jquery-flot/jquery.flot.crosshair.min.js');
//        echo $this->Html->script('jquery-flot/jquery.flot.categories.min.js');
//
//	echo $this->Html->script('chart-js-1.0.1/chart-js.js');
//	echo $this->Html->script('nvd3-1.8.1/nv.d3.js');
//
//        echo $this->Html->script('raphael-2.1.2/raphael.min.js');
//        echo $this->Html->script('morris-0.5.0/morris.js');
//        echo $this->Html->script('jquery-jvectormap-1.2.2/jquery-jvectormap.min.js');
//        echo $this->Html->script('jquery-jvectormap-1.2.2/jquery-jvectormap-world-merc-en.js');
//        echo $this->Html->script('jquery-jvectormap-1.2.2/jquery-jvectormap-world-mill-en.js');
        ?>

    </body>
</html>