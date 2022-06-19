<!DOCTYPE html>
<html lang="en">
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

        //Select2
        echo $this->Html->css('select2-4.0.12/select2.min.css');

        //Color picker
        echo $this->Html->css('color-picker/color-picker.css');

        echo $this->Html->css('admin/int_games.css');
        echo $this->Html->css('sbadmin2-dash/sb-admin-2.css');
        echo $this->Html->css('admin/custom.css');


        echo $this->Html->script('jquery-3.3.1.min.js');
        ?>

    </head>

    <body id="page-top">
        <!-- Page Wrapper -->
        <div id="wrapper">
            <?= $this->element('admin_sidebar'); ?>
            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">
                <!-- Main Content -->
                <div id="content">
                    <!-- Topbar -->
                    <?= $this->element('admin_header'); ?>
                    <!-- End of Topbar -->


                    <!-- Begin Page Content -->
                    <div class="container-fluid">
                        <?= $content_for_layout; ?>
                    </div>
                    <!-- /.container-fluid -->
                </div>
                <!-- End of Main Content -->
                <!-- Footer -->
                <footer class="sticky-footer bg-white mt-4">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; <?= Configure::read('Settings.websiteName') . ' ' . date("Y"); ?></span>
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
        echo $this->Html->script('jquery-migrate-1.4.1/jquery-migrate.min.js');
        echo $this->Html->script('jquery-ui-1.12.1/jquery-ui.min.js');
        echo $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js');

        echo $this->Html->script('bootstrap-4.3.1/bootstrap.min.js');


//        echo $this->Html->script('https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js');
//        echo $this->Html->script('https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.js');
        echo $this->Html->script('admin/int_games.js');
        echo $this->Html->script('select2-4.0.12/select2.min.js');
        echo $this->Html->script('color-picker/color-picker.js');
        echo $this->Html->script('sbadmin2-dash/sb-admin-2.js');
        ?>
        <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
        <script>

        

            (function load_alerts() {
                $.get("/admin/alerts/getAlertsJson", function (response) {
                    //console.log(response);
                    if (response.status === 'success') {
                        $("#alerts_count").html(response.count);
                        //show last 5 alerts
                        if (response.count > 0) {
                            for (var i = 0; i < 5; i++) {
                                var alert_model = response.data[i]['Alert']['alert_model'];
                                var alert_source = response.data[i]['Alert']['alert_source'];
                                var alert_text = response.data[i]['Alert']['alert_text'];
                                var user = response.data[i]['User']['username'];
                                var date = response.data[i]['Alert']['date'];
                                $("#alerts_list").append('<div class="dropdown-item d-flex align-items-center">' +
                                        '<div class="mr-3">' +
                                        '<div class="icon-circle bg-default">' +
                                        '<i class="fas fa-exclamation-triangle text-white"></i>' +
                                        '</div>' +
                                        '</div>' +
                                        '<div>' +
                                        '<div class="small text-gray-500">' + alert_source + '-' + alert_model + '</div>' +
                                        '<div class="font-weight-bold">' + user + '</div>' + alert_text +
                                        '<div class="text-gray-500 small">' + date + '</div>' +
                                        '</div>' +
                                        '</div>');
                            }
                        } else {
                            //show no alerts
                            $("#alerts_list").append('<div class="dropdown-item d-flex align-items-center justify-content-center">No alerts to display.</div>');
                        }
                    }
                }
                , "json");
            })();
        </script>
        <script>
            $(function () {
                $(".datetimepicker-full-filter").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "yy-mm-dd",
                    firstDay: 1,
                    timeicker: true
                });
            });

            $(function () {
                $(".datetimepicker-filter").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "yy-mm-dd",
                    firstDay: 1
                });
            });
            //init CKEditor
            CKEDITOR.replace('ckeditor');

        </script>
    </body>
</html>