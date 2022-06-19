<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta http-equiv="Content-Type" charset="text/html; <?= Configure::read('Settings.charset'); ?>"/>
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <base href="/" />

        <title><?= Configure::read('Settings.websiteTitle'); ?></title>
        <?= $this->Html->meta('keywords', (isset($keywords) ? $keywords : Configure::read('Settings.metaKeywords'))); ?>
        <?= $this->Html->meta('description', (isset($description) ? $description : Configure::read('Settings.metaDescription'))); ?>
        <?= $this->Html->meta(array('name' => 'author', 'content' => Configure::read('Settings.metaAuthor'))); ?>
        <?= $this->Html->meta(array('name' => 'reply-to', 'content' => Configure::read('Settings.metaReplayTo'))); ?>
        <?= $this->Html->meta(array('name' => 'copyright', 'content' => Configure::read('Settings.metaCopyright'))); ?>
        <?= $this->Html->meta(array('name' => 'revisit-after', 'content' => Configure::read('Settings.metaRevisitTime'))); ?>
        <?= $this->Html->meta(array('name' => 'identifier-url', 'content' => Configure::read('Settings.metaIdentifierUrl'))); ?>
	<?php


	echo $this->Html->css('https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700');
	echo $this->Html->css('fontawesome-free-5.11.2-web/css/all.min.css');
	echo $this->Html->css('ion-icons-2.0.0/css/ion-icons.min.css');

	echo $this->Html->css('jquery-ui-1.12.1/jquery-ui.min.css');
	echo $this->Html->css('bootstrap-4.3.1/bootstrap.min.css');
        
      
        echo $this->Html->script('angular-1.7.8/angular.min.js');
        echo $this->Html->script('angular-1.7.8/angular-route.min.js');
//
//	echo $this->Html->css('animate/animate.min.css');
//	echo $this->Html->css('flag-icon/flag-icon.css');
//
//
//	echo $this->Html->css('jquery-isotope-1.5.25/jquery.isotope.css');
//	echo $this->Html->css('lightbox-2.6/lightbox.css');
//	echo $this->Html->css('full-calendar-1.6.4/full-calendar.css');
//	echo $this->Html->css('jquery-gritter-1.7.4/jquery.gritter.css');
//	echo $this->Html->css('morris-0.5.0/morris.css');
//	echo $this->Html->css('jquery-jvectormap-1.2.2/jquery-jvectormap.css');
//
//	echo $this->Html->css('parsley-2.0.0/parsley.css');
//	echo $this->Html->css('jquery-tag-it/jquery.tag-it.css');
//	echo $this->Html->css('bootstrap-wysi-html5/bootstrap-wysi-html5.css');
//	echo $this->Html->css('bootstrap-calendar/bootstrap-calendar.css');
//	echo $this->Html->css('bootstrap-wizard-1.3/bwizard.min.css');
//	echo $this->Html->css('jstree-3.1.0/jstree.css');
//
//	echo $this->Html->css('ion-range-slider-1.9.0/ion.range-slider.css');
//	echo $this->Html->css('ion-range-slider-1.9.0/ion.range-slider.skin-nice.css');
//	echo $this->Html->css('bootstrap-color-picker-2.0/bootstrap-color-picker.min.css');
//	echo $this->Html->css('bootstrap-time-picker-0.2.3/bootstrap-time-picker.min.css');
//	echo $this->Html->css('bootstrap-combobox-1.1.5/bootstrap-combobox.css');
//	echo $this->Html->css('password-indicator/password-indicator.css');
//	echo $this->Html->css('bootstrap-select-1.5.4/bootstrap-select.min.css');
//	echo $this->Html->css('bootstrap-tags-input-0.3.9/bootstrap-tags-input.css');
//	echo $this->Html->css('bootstrap-date-range-picker-1.3.21/date-range-picker-bs3.css');
//
//
//	echo $this->Html->css('data-tables/datatables.min.css');
//	/*echo $this->Html->css('data-tables-1.10.8/extensions/buttons.bootstrap.min.css');
//		echo $this->Html->css('data-tables-1.10.8/extensions/responsive.bootstrap.min.css');
//		echo $this->Html->css('data-tables-1.10.8/extensions/autofill.bootstrap.min.css');
//		echo $this->Html->css('data-tables-1.10.8/extensions/col-reorder.bootstrap.min.css');
//		echo $this->Html->css('data-tables-1.10.8/extensions/key-table.bootstrap.min.css');
//		echo $this->Html->css('data-tables-1.10.8/extensions/row-reorder.bootstrap.min.css');
//		echo $this->Html->css('data-tables-1.10.8/extensions/select.bootstrap.min.css');*/
//
//	echo $this->Html->css('powerange/powerange.min.css');
//	echo $this->Html->css('switchery/switchery.min.css');
//
//	echo $this->Html->css('blue-imp-gallery/blue-imp-gallery.min.css');
//	echo $this->Html->css('jquery-file-upload-5.40.1/jquery.file-upload.css');
//	echo $this->Html->css('jquery-file-upload-5.40.1/jquery.file-upload-ui.css');
//
//
//	echo $this->Html->css('bootstrap-3-editable/bootstrap-editable.css');
//	echo $this->Html->css('bootstrap-3-editable/extensions/address.css');
//	echo $this->Html->css('bootstrap-3-editable/extensions/typeahead.css');
//	echo $this->Html->css('bootstrap-date-picker-2.0/bootstrap-date-picker.css');
//	echo $this->Html->css('bootstrap-date-picker-2.0/bootstrap-date-picker-3.css');
//	echo $this->Html->css('bootstrap-date-time-picker/date-time-picker.css');
//	echo $this->Html->css('select-2-4.0.0-rc.2/select-2.min.css');
//	echo $this->Html->css('bootstrap-eonasdan-date-time-picker-4.14.30/bootstrap-date-time-picker.min.css');
//
//	echo $this->Html->script('jquery-3.3.1.min.js');
//	echo $this->Html->script('pace-0.5.6/pace.min.js');
//	echo $this->Html->script('ckeditor-4.11.2/ckeditor.js');
//
//	echo $this->Html->css('nvd3-1.8.1/nv.d3.css');
//

//
	echo $this->Html->css('sbadmin2-dash/sb-admin-2.css');
//	echo $this->Html->css('custom.css');
	?>
    </head>

    <body id="page-top">

        <!-- Page Wrapper -->
        <div id="wrapper">

   

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">

                    <!-- Topbar -->
               
                    <!-- End of Topbar -->
                    <!-- Begin Page Content -->
                    <div class="container-fluid">
			<?= $content_for_layout; ?>
                    </div>
                    <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; <?= Configure::read('Settings.websiteTitle') . ' ' . date("Y"); ?></span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>


		<?php
		echo $this->Html->script('jquery-3.3.1.min.js');
		echo $this->Html->script('jquery-migrate-1.4.1/jquery-migrate.min.js');
		echo $this->Html->script('jquery-ui-1.12.1/jquery-ui.min.js');
//		echo $this->Html->script('popper-1.0/popper.min.js');
		echo $this->Html->script('bootstrap-4.3.1/bootstrap.min.js');
//
//
//
//		echo $this->Html->script('slim-scroll-1.3.8/slim-scroll.min.js');
//		echo $this->Html->script('jquery-cookie-1.4.1/jquery.cookie.js');
//
//
//		echo $this->Html->script('jquery-isotope-1.5.25/jquery.isotope.min.js');
//		//echo $this->Html->script('lightbox-2.6/lightbox.min.js');
//
//		echo $this->Html->script('full-calendar-1.6.4/full-calendar.js');
//		echo $this->Html->script('bootstrap-calendar/bootstrap-calendar.min.js');
//		echo $this->Html->script('jquery-gritter-1.7.4/jquery.gritter.js');
//		echo $this->Html->script('jquery-tag-it/jquery.tag-it.min.js');
//		echo $this->Html->script('bootstrap-wysi-html5/wysi-html5-0.3.0.js');
//		echo $this->Html->script('bootstrap-wysi-html5/bootstrap-wysi-html5.js');
//		echo $this->Html->script('superbox-1.0.0/superbox.js');
//
//
//
//		echo $this->Html->script('bootstrap-3-editable/bootstrap-editable.min.js');
//		echo $this->Html->script('bootstrap-3-editable/extensions/address.js');
//		echo $this->Html->script('bootstrap-3-editable/extensions/typeahead.js');
//		echo $this->Html->script('bootstrap-3-editable/extensions/typeaheadjs.js');
//		echo $this->Html->script('bootstrap-date-picker-2.0/bootstrap-date-picker.js');
//		echo $this->Html->script('bootstrap-date-time-picker/bootstrap-date-time-picker.min.js');
//
//		echo $this->Html->script('jquery-mockjax-1.5.0/jquery.mockjax.js');
//		echo $this->Html->script('moment-2.6.0/moment.min.js');
//		echo $this->Html->script('ion-range-slider-1.9.0/ion.range-slider.min.js');
//		echo $this->Html->script('bootstrap-color-picker-2.0/bootstrap-color-picker.min.js');
//		echo $this->Html->script('masked-input-1.3.1/masked-input.min.js');
//		echo $this->Html->script('bootstrap-time-picker-0.2.3/bootstrap-time-picker.min.js');
//		echo $this->Html->script('bootstrap-combobox-1.1.5/bootstrap-combobox.js');
//		echo $this->Html->script('password-indicator/password-indicator.js');
//		echo $this->Html->script('bootstrap-select-1.5.4/bootstrap-select.min.js');
//		echo $this->Html->script('bootstrap-tags-input-0.3.9/bootstrap-tags-input.min.js');
//		echo $this->Html->script('bootstrap-tags-input-0.3.9/bootstrap-tags-input-typeahead.js');
//		echo $this->Html->script('bootstrap-date-range-picker-1.3.21/date-range-picker.js');
//		//echo $this->Html->script('bootstrap-eonasdan-date-time-picker-4.14.30/bootstrap-date-time-picker.min.js');
//		echo $this->Html->script('select-2-4.0.0-rc.2/select-2.min.js');
//
//		echo $this->Html->script('jquery-file-upload-5.40.1/extensions/jquery.ui.widget.js');
//		echo $this->Html->script('jquery-file-upload-5.40.1/extensions/tmpl.min.js');
//		echo $this->Html->script('jquery-file-upload-5.40.1/extensions/load-image.min.js');
//		echo $this->Html->script('jquery-file-upload-5.40.1/extensions/canvas-to-blob.min.js');
//		echo $this->Html->script('blue-imp-gallery/jquery.blue-imp-gallery.min.js');
//		echo $this->Html->script('jquery-file-upload-5.40.1/jquery.iframe-transport.js');
//		echo $this->Html->script('jquery-file-upload-5.40.1/jquery.file-upload.js');
//		echo $this->Html->script('jquery-file-upload-5.40.1/jquery.file-upload-process.js');
//		echo $this->Html->script('jquery-file-upload-5.40.1/jquery.file-upload-image.js');
//		echo $this->Html->script('jquery-file-upload-5.40.1/jquery.file-upload-audio.js');
//		echo $this->Html->script('jquery-file-upload-5.40.1/jquery.file-upload-video.js');
//		echo $this->Html->script('jquery-file-upload-5.40.1/jquery.file-upload-validate.js');
//		echo $this->Html->script('jquery-file-upload-5.40.1/jquery.file-upload-ui.js');
//
//
//		echo $this->Html->script('ckeditor-4.11.2/ckeditor.js');
//
//
//		/*DATA TABLES*/
//		echo $this->Html->script('data-tables/datatables.min.js');
//		echo $this->Html->script('data-tables/Buttons-1.5.4/js/dataTables.buttons.min.js');
//		echo $this->Html->script('data-tables/Buttons-1.5.4/js/buttons.flash.min.js');
//		echo $this->Html->script('data-tables/JSZip-2.5.0/jszip.min.js');
//		echo $this->Html->script('data-tables/pdfmake-0.1.36/pdfmake.min.js');
//		echo $this->Html->script('data-tables/pdfmake-0.1.36/vfs_fonts.js');
//		echo $this->Html->script('data-tables/Buttons-1.5.4/js/buttons.html5.min.js');
//		echo $this->Html->script('data-tables/Buttons-1.5.4/js/buttons.print.min.js');
//
//
//		echo $this->Html->script('switchery/switchery.min.js');
//		echo $this->Html->script('powerange/powerange.min.js');
//		echo $this->Html->script('parsley-2.0.0/parsley.js');
//		echo $this->Html->script('bootstrap-wizard-1.3/bwizard.js');
//		echo $this->Html->script('jstree-3.1.0/jstree.min.js');
//
//
//		/*echo $this->Html->script('jquery-flot/jquery.flot.min.js');
//	echo $this->Html->script('jquery-flot/jquery.flot.time.min.js');
//	echo $this->Html->script('jquery-flot/jquery.flot.resize.min.js');
//	echo $this->Html->script('jquery-flot/jquery.flot.pie.min.js');
//	echo $this->Html->script('jquery-flot/jquery.flot.stack.min.js');
//	echo $this->Html->script('jquery-flot/jquery.flot.crosshair.min.js');
//	echo $this->Html->script('jquery-flot/jquery.flot.categories.min.js');*/
//
//		//echo $this->Html->script('chart-js-1.0.1/chart-js.js');
//		//echo $this->Html->script('nvd3-1.8.1/nv.d3.js');
//
//		echo $this->Html->script('raphael-2.1.2/raphael.min.js');
//		echo $this->Html->script('morris-0.5.0/morris.js');
//		echo $this->Html->script('jquery-jvectormap-1.2.2/jquery-jvectormap.min.js');
//		echo $this->Html->script('jquery-jvectormap-1.2.2/jquery-jvectormap-world-merc-en.js');
//		echo $this->Html->script('jquery-jvectormap-1.2.2/jquery-jvectormap-world-mill-en.js');
//
//
//		echo $this->Html->script('angular-1.7.8/angular.min.js');
//		echo $this->Html->script('angular-1.7.8/angular-route.min.js');
//		echo $this->Html->script('app.js');
//
		echo $this->Html->script('sbadmin2-dash/sb-admin-2.js');

		?>


    </body>

</html>