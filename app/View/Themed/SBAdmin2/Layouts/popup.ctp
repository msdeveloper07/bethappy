<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
        <title><?php echo $title_for_layout; ?></title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
<!--	<link href="/assets/admin/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="/assets/admin/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
	<link href="/assets/admin/font-awesome/css/font-awesome.css" rel="stylesheet" />
	<link href="/css/admin/style.css" rel="stylesheet" />
	<link href="/css/admin/style_responsive.css" rel="stylesheet" />
	<link href="/css/admin/style_gray.css" rel="stylesheet" id="style_color" />

	<link href="/assets/admin/fancybox/source/jquery.fancybox.css" rel="stylesheet" />
	<link href="/assets/admin/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
	<link href="/assets/admin/fullcalendar/fullcalendar/bootstrap-fullcalendar.css" rel="stylesheet" />
	<link href="/assets/admin/jqvmap/jqvmap/jqvmap.css" media="screen" rel="stylesheet" type="text/css" />
        <link href="/assets/admin/bootstrap-wysihtml5/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css" />-->


<?php echo $this->Html->css('/assets/admin/bootstrap/css/bootstrap-classic.css');?>
	<style type="text/css">
	  body {
		padding-bottom: 40px;
	  }
	  .sidebar-nav {
		padding: 9px 0;
	  }         
	</style>
    <?php  
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
        //echo $this->Html->css('/assets/admin/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css');
        echo $this->Html->css('/theme/ISoftGaming/css/polyglot-language-switcher.css');
    ?>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDzIOWEC_c__aKrqHDOkoZh2AYvqQ9HZRU"></script>

        <script type="text/javascript" src="/assets/admin/bootstrap/js/jquery-1.8.3.min.js"></script>
        <script type="text/javascript" src="/assets/admin/jquery-ui/jquery-ui-1.9.2.min.js"></script>
        <script type="text/javascript" src="/assets/admin/jquery-ui/jquery-ui-1.10.1.custom.min.js"></script>
</head><!-- END HEAD -->
<!-- BEGIN BODY -->
<body>  
    <div class="container-fluid">
        <div class="row-fluid">
            <noscript>
                <div class="alert alert-block span10">
                    <h4 class="alert-heading">Warning!</h4>
                    <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
                </div>
            </noscript>            
            <div id="content" class="span12">
                <?php echo $content_for_layout ; ?>                          						
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="/assets/admin/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="/assets/admin/fullcalendar/fullcalendar/fullcalendar.min.js"></script> <!-- data table plugin -->
    <script src="/assets/admin/bootstrap/js/bootstrap.min.js"></script>
    <script src="/js/admin/jquery.blockui.js"></script>
    <script type="text/javascript" src="/assets/admin/bootstrap/js/jquery.cookie.js"></script>
    <script src="/assets/admin/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>
    <script src="/assets/admin/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
    <script src="/assets/admin/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
    <script src="/assets/admin/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
    <script src="/assets/admin/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
    <script src="/assets/admin/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>
    <script src="/assets/admin/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>
    <script src="/assets/admin/jquery-knob/js/jquery.knob.js"></script>
    <script type="text/javascript" src="/assets/admin/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
    <script type="text/javascript" src="/assets/admin/uniform/jquery.uniform.min.js"></script>
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
    <script type="text/javascript" src="/assets/admin/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="/assets/admin/fancybox/source/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" src="/assets/admin/flot/jquery.flot.min.js"></script>
    <script type="text/javascript" src="/assets/admin/flot/jquery.flot.pie.min.js"></script>
    <script type="text/javascript" src="/assets/admin/flot/jquery.flot.categories.js"></script>
    <script type="text/javascript" src="/js/admin/jquery.peity.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/js/admin/scripts.js" type="text/javascript"></script>

    <!--livecasino charisma start -->
    <script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-transition.js"></script>	<!-- alert enhancer library -->
    <script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-alert.js"></script>	<!-- modal / dialog library -->
    <script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-modal.js"></script>	<!-- custom dropdown library (settings on tabs)-->
    <script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-dropdown.js"></script>	<!-- scrolspy library -->
    <script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-scrollspy.js"></script>	<!-- library for creating tabs -->
    <script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-tab.js"></script>	<!-- library for advanced tooltip -->
    <script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-tooltip.js"></script>	<!-- popover effect library -->
    <script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-popover.js"></script>	<!-- button enhancer library -->
    <script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-button.js"></script>	<!-- accordion library (optional, not used in demo) -->
    <script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-collapse.js"></script>	<!-- carousel slideshow library (optional, not used in demo) -->
    <script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-carousel.js"></script>	<!-- autocomplete library -->
    <script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-typeahead.js"></script>	<!-- tour library -->
    <script type="text/javascript" src="/assets/admin/bootstrap/js/bootstrap-tour.js"></script>
    <script type="text/javascript" src="/assets/admin/bootstrap/js/jquery.dataTables.min.js"></script> 

    <script type="text/javascript" src="/assets/admin/flot/excanvas.min.js"></script>
    <script type="text/javascript" src="/assets/admin/flot/jquery.flot.stack.js"></script>
    <script type="text/javascript" src="/assets/admin/flot/jquery.flot.resize.min.js"></script>
    <script type="text/javascript" src="/assets/admin/flot/jquery.flot.crosshair.js"></script>


    <script type="text/javascript" src="/assets/admin/bootstrap/js/jquery.chosen.min.js"></script>
    <script type="text/javascript" src="/assets/admin/bootstrap/js/jquery.uniform.min.js"></script>	<!-- plugin for gallery image view -->
    <script type="text/javascript" src="/assets/admin/bootstrap/js/jquery.colorbox.min.js"></script>	<!-- rich text editor library -->
    <script type="text/javascript" src="/assets/admin/bootstrap/js/jquery.cleditor.min.js"></script>	<!-- notification plugin -->
    <script type="text/javascript" src="/assets/admin/bootstrap/js/jquery.noty.js"></script>	<!-- file manager library -->
    <script type="text/javascript" src="/assets/admin/bootstrap/js/jquery.elfinder.min.js"></script>	<!-- star rating plugin -->
    <script type="text/javascript" src="/assets/admin/bootstrap/js/jquery.raty.min.js"></script>	<!-- for iOS style toggle switch -->
    <script type="text/javascript" src="/assets/admin/bootstrap/js/jquery.iphone.toggle.js"></script>	
    <script type="text/javascript" src="/assets/admin/bootstrap/js/jquery.autogrow-textarea.js"></script>	<!-- multiple file upload plugin -->
    <script type="text/javascript" src="/assets/admin/bootstrap/js/jquery.uploadify-3.1.min.js"></script>	<!-- history.js for cross-browser state change on ajax -->
    <script type="text/javascript" src="/assets/admin/bootstrap/js/jquery.history.js"></script>	<!-- application script for Charisma demo -->
    <script type="text/javascript" src="/assets/admin/bootstrap/js/charisma.js"></script>

    <script type="text/javascript" src="/js/admin/googlemap_clusters.js"></script> 
    <script type="text/javascript" src="/js/admin/googlemap.js"></script>

    <script>
        function loadalerts(){
            $.get( "/admin/alert/alert_informer", function(response, status) {
                $( "#alertcounter" ).html( response.data );
                //status
            },"json");
            
            $.get( "/admin/alert/newdepalert_informer", function(response, status) {
                $( "#alertdepcounter" ).html( response.data );
            },"json");
        }
        jQuery(document).ready(function() {
                // initiate layout and plugins
                App.setMainPage(true);
                App.init();
                setInterval( loadalerts, 10000 );
        });
    </script>
</body>
<!-- END BODY -->
</html>
