<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?= __($title_for_layout); ?></title>

        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <style type="text/css">
            body { padding-bottom: 40px; }
            .sidebar-nav { padding: 9px 0; }
        </style>
        
        <?php  
            echo $this->Html->css('/assets/admin/bootstrap/css/bootstrap-classic.css');
            echo $this->Html->css('/assets/admin/bootstrap/css/bootstrap-responsive.css');
            echo $this->Html->css('/assets/admin/bootstrap/css/charisma-app.css');
            echo $this->Html->css('/assets/admin/jquery-ui/jquery-ui-1.10.1.custom.min.css');
            echo $this->Html->css('/assets/admin/bootstrap/css/uniform.default.css');
            echo $this->Html->css('/assets/admin/bootstrap/css/fullcalendar.css');
            echo $this->Html->css('/assets/admin/bootstrap/css/fullcalendar.print.css');
            echo $this->Html->css('/assets/admin/bootstrap/css/chosen.css');

            echo $this->Html->css('/assets/admin/bootstrap/css/colorbox.css');
            echo $this->Html->css('/assets/admin/bootstrap/css/jquery.cleditor.css');        
            echo $this->Html->css('/assets/admin/bootstrap/css/jquery.noty.css');
            echo $this->Html->css('/assets/admin/bootstrap/css/noty_theme_default.css');
            echo $this->Html->css('/assets/admin/bootstrap/css/elfinder.min.css');
            echo $this->Html->css('/assets/admin/bootstrap/css/elfinder.theme.css');  
            echo $this->Html->css('/assets/admin/bootstrap/css/jquery.iphone.toggle.css');   
            echo $this->Html->css('/assets/admin/bootstrap/css/opa-icons.css');
            echo $this->Html->css('/assets/admin/bootstrap/css/uploadify.css');
            echo $this->Html->css('/theme/ISoftGaming/css/polyglot-language-switcher.css');
            echo $this->Html->css('/assets/admin/jstree/themes/default/style.css');
            echo $this->Html->css('/css/admin/style.css');
        ?>
        <?= $this->Html->script(['moment.js', 'angular.min.js', 'angular-route.min.js', 'angular-animate.min.js', 'angular-strap.min.js', 'angular-strap.tpl.min.js']);?>
        
        <script type="text/javascript">            
            var app = angular.module('SportsBookPanel', ['ngRoute', 'ngAnimate', 'mgcrea.ngStrap']).controller('AdminCtrl', ['$scope', function($scope) {}]);
        </script>
        <script type="text/javascript" src="/Libs/jquery/jquery-2.2.0.min.js"></script>
        <script src="http://code.jquery.com/jquery-migrate-1.4.0.js"></script>
        <script type="text/javascript" src="/Libs/jquery-ui-1.11.4/jquery-ui.min.js"></script>
        <script type="text/javascript" src="/Libs/jquery-ui-1.11.4/jquery-ui-custom.min.js"></script>

        <input type="hidden" id="node-url" value="<?= Configure::read('node_url');?>"/>
        <input type="hidden" id="lang-iso" value="<?=Configure::read('Config.language');?>"/>
    </head><!-- END HEAD -->
    
    <!-- BEGIN BODY -->
    <body ng-controller="AdminCtrl">

        <div class="container-fluid">
            <div class="row-fluid">
                <noscript>
                    <div class="alert alert-block span10">
                        <h4 class="alert-heading">Warning!</h4>
                        <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
                    </div>
                </noscript>
                <script type="text/javascript">
                    $( document ).ready(function() {
                        $('.sidebar-menu ul li').find('a[href$="--><!--"]').parent().parent().toggle('open');
                        $('.sidebar-menu ul li').find('a[href="/admin/dashboard"]').parent().parent().toggle('open');

                       $('.sidebar-menu ul').each(function() {
                            if($(this).find('ul li').context.childElementCount == 0) {
                                $(this).parent().css({'display' : 'none'});
                            }
                        });
                    });
                </script>   
                <div id="content" class="span10">
                    <div class="row-fluid">
                        <div class="span12"><?= $content_for_layout ; ?></div>
                    </div>                              						
                </div>
            </div>
        </div>

	<!-- jQuery -->
	<script type="text/javascript" src="/assets/admin/jquery-slimscroll/jquery.slimscroll.min.js"></script>
	<script type="text/javascript" src="/assets/admin/fullcalendar/fullcalendar/fullcalendar.min.js"></script>
	<script type="text/javascript" src="/Libs/bootstrap/js/bootstrap.min.js"></script>
	
        <script type="text/javascript" src="/js/admin/jquery.blockui.js"></script>
        <script type="text/javascript" src="/js/admin/jquery.cookie.js"></script>
        <script type="text/javascript" src="/Libs/admin/howler/howler.js"></script>
        <script type="text/javascript" src="/assets/admin/jquery-knob/js/jquery.knob.js"></script>
        <script type="text/javascript" src="/assets/admin/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
        <script type="text/javascript" src="/assets/admin/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
        <script type="text/javascript" src="/assets/admin/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
        <script type="text/javascript" src="/assets/admin/clockface/js/clockface.js"></script>
        <script type="text/javascript" src="/assets/admin/jquery-tags-input/jquery.tagsinput.min.js"></script>
        <script type="text/javascript" src="/assets/admin/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js"></script>
        <script type="text/javascript" src="/assets/admin/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="/assets/admin/bootstrap-daterangepicker/date.js"></script>
        <script type="text/javascript" src="/assets/admin/bootstrap-daterangepicker/daterangepicker.js"></script>
        <script type="text/javascript" src="/assets/admin/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
        <script type="text/javascript" src="/assets/admin/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
        <script type="text/javascript" src="/assets/admin/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
        
        <script type="text/javascript" src="/Libs/admin/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="/Libs/admin/fancybox/jquery.fancybox.pack.js"></script>
        <script type="text/javascript" src="/assets/admin/flot/jquery.flot.js"></script>
        <script type="text/javascript" src="/assets/admin/flot/jquery.flot.pie.js"></script>
        <script type="text/javascript" src="/assets/admin/flot/jquery.flot.categories.js"></script>
        <script type="text/javascript" src="/js/admin/dropdownSystem.js"></script>
        <script type="text/javascript" src="/js/admin/jquery.peity.min.js" type="text/javascript"></script>        
        <script type="text/javascript" src="/Libs/admin/uniform/jquery.uniform.min.js"></script>
        
        <script type="text/javascript" src="/assets/admin/jstree/jstree.min.js"></script>
        
	<script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-transition.js"></script>
	<script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-alert.js"></script>
	<script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-modal.js"></script>
	<script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-dropdown.js"></script>
        <script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-scrollspy.js"></script>
        <script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-tab.js"></script>
	<script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-tooltip.js"></script>
	<script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-popover.js"></script>
	<script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-button.js"></script>
	<script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-collapse.js"></script>
	<script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-carousel.js"></script>
	<script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-typeahead.js"></script>
	<script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-tour.js"></script>
	<script type="text/javascript" src="/assets/admin/bootstrap/js/jquery.dataTables.min.js"></script> 
	
        <script type="text/javascript" src="/assets/admin/flot/excanvas.min.js"></script>
        <script type="text/javascript" src="/assets/admin/flot/jquery.flot.stack.js"></script>
        <script type="text/javascript" src="/assets/admin/flot/jquery.flot.resize.min.js"></script>
        <script type="text/javascript" src="/assets/admin/flot/jquery.flot.crosshair.js"></script>

	<script type="text/javascript" src="/assets/admin/bootstrap/js/jquery.chosen.min.js"></script>
	<script type="text/javascript" src="/assets/admin/bootstrap/js/jquery.colorbox-min.js"></script>
	<script type="text/javascript" src="/assets/admin/bootstrap/js/jquery.cleditor.min.js"></script>
	<script type="text/javascript" src="/assets/admin/bootstrap/js/jquery.noty.js"></script>
	<script type="text/javascript" src="/assets/admin/bootstrap/js/elfinder.min.js"></script>
	<script type="text/javascript" src="/assets/admin/bootstrap/js/jquery.raty.min.js"></script>
	<script type="text/javascript" src="/assets/admin/bootstrap/js/jquery.iphone.toggle.js"></script>
	<script type="text/javascript" src="/assets/admin/bootstrap/js/jquery.autogrow-textarea.js"></script>
	<script type="text/javascript" src="/assets/admin/bootstrap/js/jquery.uploadify-3.1.min.js"></script>
	<script type="text/javascript" src="/assets/admin/bootstrap/js/jquery.history.js"></script>
	<script type="text/javascript" src="/assets/admin/bootstrap/js/charisma.js"></script>

	<script type="text/javascript" src="/js/admin/googlemap_clusters.js"></script> 
	<script type="text/javascript" src="/js/admin/googlemap.js"></script>
	
	<script type="text/javascript" src="/js/admin/oddskeys.js"></script>
	<script type="text/javascript" src="/js/admin/risks.js"></script>
	<script type="text/javascript" src="/js/admin/scripts.js" ></script>

        <script>
            
            
            jQuery(document).ready(function() {
                // initiate layout and plugins
//                App.setMainPage(true);
//                App.init();
            });
        </script>
    </body>
    <!-- END BODY -->
</html>