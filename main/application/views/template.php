<!DOCTYPE html PUBLIC"-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" />
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9"> <![endif]-->
<!--[if gt IE 8]> <html class="ie gt-ie8"> <![endif]-->
<!--[if !IE]><!--><html><!-- <![endif]-->
    <head>
        <noscript>
            <style>
                .container-fluid.fluid.menu-left{
                    display: none;
                }
                .container-idle-dialog{
                    display: none;
                }
                .jsdisabledmsg{
                    /*background-color: #ddd;*/
                    /*color: #000;*/
                    width: 40%;
                    margin: auto;
                    margin-top: 7%;
                    padding: 30px;
                    border: #ff0 dashed medium;
                    border-radius: 20px;
                    display: block;
                    box-shadow: 0px 0px 10px #fff;
                }
            </style>
            <div align='center' class="jsdisabledmsg">
                <h3>
                    Javascript disabled or your browser doesn't support javascript.<br>
                    Please enable javascript!
                </h3>
            </div>
        </noscript>
        <title>ISIB Web Application</title>

        <!-- Meta -->
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta content="utf-8" http-equiv="encoding" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />

        <!-- Favicon -->
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
        <link rel="icon" href="favicon.ico" type="image/x-icon" />

        <!-- Bootstrap -->
        <link href="<?php echo base_url(); ?>public/bootstrap/css/bootstrap.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>public/bootstrap/css/responsive.css" rel="stylesheet" />

        <!-- Glyphicons Font Icons -->
        <link href="<?php echo base_url(); ?>public/theme/css/glyphicons.css" rel="stylesheet" />

        <!-- Uniform Pretty Checkboxes -->
        <link href="<?php echo base_url(); ?>public/theme/scripts/plugins/forms/pixelmatrix-uniform/css/uniform.default.css" rel="stylesheet" />

        <!--[if IE]><!--><script src="<?php echo base_url(); ?>public/theme/scripts/plugins/other/excanvas/excanvas.js"></script><!--<![endif]-->
        <!--[if lt IE 8]><script src="<?php echo base_url(); ?>public/theme/scripts/plugins/other/json2.js"></script><![endif]-->

        <!-- Bootstrap Extended -->
        <link href="<?php echo base_url(); ?>public/bootstrap/extend/jasny-bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet" />

        <link href="<?php echo base_url(); ?>public/bootstrap/extend/jasny-bootstrap/css/jasny-bootstrap-responsive.min.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>public/bootstrap/extend/bootstrap-wysihtml5/css/bootstrap-wysihtml5-0.0.2.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>public/bootstrap/extend/bootstrap-select/bootstrap-select.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>public/bootstrap/extend/bootstrap-toggle-buttons/static/stylesheets/bootstrap-toggle-buttons.css" rel="stylesheet" />

        <!-- Select2 Plugin -->
        <link href="<?php echo base_url(); ?>public/theme/scripts/plugins/forms/select2/select2.css" rel="stylesheet" />

        <!-- DateTimePicker Plugin -->
        <link href="<?php echo base_url(); ?>public/theme/scripts/plugins/forms/bootstrap-datetimepicker/css/datetimepicker.css" rel="stylesheet" />

        <!-- JQueryUI -->
        <link href="<?php echo base_url(); ?>public/theme/scripts/plugins/system/jquery-ui/css/dark-hive/jquery.ui.all.css" rel="stylesheet" />
       <!-- <link href="<?php echo base_url(); ?>public/theme/scripts/plugins/system/jquery-ui/development-bundle/demos/demos.css" rel="stylesheet" /> -->
 
        <!-- MiniColors ColorPicker Plugin -->
        <link href="<?php echo base_url(); ?>public/theme/scripts/plugins/color/jquery-miniColors/jquery.miniColors.css" rel="stylesheet" />

        <!-- Notyfy Notifications Plugin -->
        <link href="<?php echo base_url(); ?>public/theme/scripts/plugins/notifications/notyfy/jquery.notyfy.css" rel="stylesheet" />
        <link href="<?php echo base_url(); ?>public/theme/scripts/plugins/notifications/notyfy/themes/default.css" rel="stylesheet" />

        <!-- Gritter Notifications Plugin -->
        <link href="<?php echo base_url(); ?>public/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />

        <!-- Easy-pie Plugin -->
        <link href="<?php echo base_url(); ?>public/theme/scripts/plugins/charts/easy-pie/jquery.easy-pie-chart.css" rel="stylesheet" />

        <!-- Google Code Prettify Plugin -->
        <link href="<?php echo base_url(); ?>public/theme/scripts/plugins/other/google-code-prettify/prettify.css" rel="stylesheet" />

        <!-- Bootstrap Image Gallery -->
        <link href="<?php echo base_url(); ?>public/bootstrap/extend/bootstrap-image-gallery/css/bootstrap-image-gallery.min.css" rel="stylesheet" />

        <!-- Main Theme Stylesheet :: CSS -->
        <link href="<?php echo base_url(); ?>public/theme/css/style-dark.css?1369753442" rel="stylesheet" />
        
       
        <!-- hide the close link in the toolbar -->
        <style type="text/css">a.ui-dialog-titlebar-close { display:none }</style>

        <!-- JQuery  jquery-1.8.3-->
          <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/system/jquery.js"></script>
	<!-- <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-1.8.3.js"></script>-->
        <!-- LESS.js Library -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/system/less.min.js"></script>
        
        <!-- Editor TinyMCE -->
        <script type="text/javascript" src="<?php echo base_url(); ?>public/theme/scripts/plugins/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>  
       
        <!-- Global Path --> 
        <script type="text/javascript" charset="utf-8">
            var ROOT = {
                'site_url': '<?php echo site_url(); ?>',
                'base_url': '<?php echo base_url(); ?>'
            };
        </script>   

        <!-- No back button -->
        <script type="text/javascript">
            window.history.forward();
            function noBack() {
                window.history.forward();
            }
        </script>  

    </head>

    <body class="" onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">

        <!-- dialog window markup -->
        <!--<div class="container-idle-dialog" title="Your session is about to expire!">
            <p>
                <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
                You will be logged off in <span class="container-idle-dialog-countdown" style="font-weight:bold"></span> seconds.
            </p>

            <p>Do you want to continue your session?</p>
        </div>-->

        <!-- Main Container Fluid -->
        <div class="container-fluid fluid menu-left">

            <!-- Top navbar (note: add class "navbar-hidden" to close the navbar by default) -->
            <div class="navbar main hidden-print">

                <!-- Wrapper -->
                <div class="wrapper">

                    <!-- Menu Toggle Button -->
                    <!--<li><a href="" data-toggle="collapse" class="btn-navbar glyphicons eyedropper single-icon"><i></i></a></li>-->
                    <button type="button" class="btn btn-navbar">
                        <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span>
                    </button>
                    <!-- // Menu Toggle Button END -->

                    <!-- Top Menu -->
                    <?php $this->load->view('page/top-menu'); ?>
                    <!-- // Top Menu END -->

                    <!-- Top Menu Right -->
                    <?php $this->load->view('page/top-menu-right'); ?>
                    <!-- // Top Menu Right END -->


                    <div class="clearfix"></div>
                </div>
                <!-- // Wrapper END -->

                <span class="toggle-navbar"></span>
            </div>
            <!-- Top navbar END -->

            <!-- Sidebar menu & content wrapper -->
            <div id="wrapper">

                <!-- Sidebar Menu -->
                <div id="menu" class="hidden-phone hidden-print">

                    <!-- Brand -->
                    <a href="<?php echo site_url(); ?>" class="appbrand">ISIB <span>System Development <div id="ObserverElement"></div></span></a>

                    <!-- Scrollable menu wrapper with Maximum height -->
                    <div class="slim-scroll" data-scroll-height="100">
                         
                        <!-- Sidebar Profile -->
                        <?php $this->load->view('page/side-profile'); ?>
		        <?php echo $side_recent; ?>
                        <!-- // Sidebar Profile END -->

                        <!-- Regular Size Menu -->
                        <?php echo $side_navigation; ?>
                        <!-- // Regular Size Menu END -->

                      

                        <!-- Sidebar Stats Widgets -->
                        <?php $this->load->view('page/side-widgets'); ?>
                        <!-- // Sidebar Stats Widgets END -->

                    </div>
                    <!-- // Scrollable Menu wrapper with Maximum Height END -->

                </div>
                <!-- // Sidebar Menu END -->

                <!-- Content -->
                <?php $this->load->view('page/content'); ?>
                <!-- // Content END -->

            </div>
            <div class="clearfix"></div>
            <!-- // Sidebar menu & content wrapper END -->

            <div id="footer" style="margin-top: 20px;" class="hidden-print">

                <!--  Copyright Line -->
                <div class="copy">&copy; 2019 - <a href="<?php echo site_url('sysdev'); ?>">System Development</a> - All Rights Reserved. <a href="https://isibdev.com" target="_blank">ISIB</a> - Current version: v1.0  <a target="_blank" href="<?php echo site_url('changelog') ?>">changelog</a></div>
                <!--  End Copyright Line -->

            </div>
            <!-- // Footer END -->

        </div>
        <!-- // Main Container Fluid END -->


        <!-- Themer -->
        <div id="themer" class="collapse">
            <div class="wrapper">
                <span class="close2">&times; close</span>
                <h4>Themer <span>color options</span></h4>
                <ul>
                    <li>Theme: <select id="themer-theme" class="pull-right"></select><div class="clearfix"></div></li>
                    <li>Primary Color: <input type="text" data-type="minicolors" data-default="#ffffff" data-slider="hue" data-textfield="false" data-position="left" id="themer-primary-cp" /><div class="clearfix"></div></li>
                    <li>
                        <span class="link" id="themer-custom-reset">reset theme</span>
                        <span class="pull-right"><label>advanced <input type="checkbox" value="1" id="themer-advanced-toggle" /></label></span>
                    </li>
                </ul>
                <div id="themer-getcode" class="hide">
                    <hr class="separator" />
                    <button class="btn btn-primary btn-small pull-right btn-icon glyphicons download" id="themer-getcode-less"><i></i>Get LESS</button>
                    <button class="btn btn-inverse btn-small pull-right btn-icon glyphicons download" id="themer-getcode-css"><i></i>Get CSS</button>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <!-- // Themer END -->

        <!-- Modal Gallery -->
        <div id="modal-gallery" class="modal modal-gallery hide fade hidden-print" tabindex="-1">
            <div class="modal-header">
                <a class="close" data-dismiss="modal">&times;</a>
                <h3 class="modal-title"></h3>
            </div>
            <div class="modal-body"><div class="modal-image"></div></div>
            <div class="modal-footer">
                <a class="btn btn-primary modal-next">Next <i class="icon-arrow-right icon-white"></i></a>
                <a class="btn btn-info modal-prev"><i class="icon-arrow-left icon-white"></i> Previous</a>
                <a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000"><i class="icon-play icon-white"></i> Slideshow</a>
                <a class="btn modal-download" target="_blank"><i class="icon-download"></i> Download</a>
            </div>
        </div>
        <!-- // Modal Gallery END -->

        <!-- JQueryUI -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>
        <!-- JQueryUI Touch Punch -->
        <!-- small hack that enables the use of touch events on sites using the jQuery UI user interface library -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/system/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

        <!-- IDLE -->
        <!--
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/system/jquery.idletimer.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/system/jquery.idletimeout.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/system/idlecontrol.js" type="text/javascript"></script>
		-->
        <!-- Modernizr -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/system/modernizr.js"></script>

        <!-- Bootstrap -->
        <script src="<?php echo base_url(); ?>public/bootstrap/js/bootstrap.min.js"></script>

        <!-- SlimScroll Plugin -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/other/jquery-slimScroll/jquery.slimscroll.min.js"></script>

        <!-- Common Demo Script -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/demo/common.js?1369753442"></script>

        <!-- Holder Plugin -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/other/holder/holder.js"></script>

        <!-- Uniform Forms Plugin -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/forms/pixelmatrix-uniform/jquery.uniform.min.js"></script>

        <!-- Global -->
        <script>
        var basePath = '<?php echo base_url(); ?>public/';
        </script>

        <!-- Bootstrap Extended -->
        <script src="<?php echo base_url(); ?>public/bootstrap/extend/bootstrap-select/bootstrap-select.js"></script>
        <script src="<?php echo base_url(); ?>public/bootstrap/extend/bootstrap-toggle-buttons/static/js/jquery.toggle.buttons.js"></script>
        <script src="<?php echo base_url(); ?>public/bootstrap/extend/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bootstrap/extend/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bootstrap/extend/jasny-bootstrap/js/bootstrap-fileupload.js"></script>
        <script src="<?php echo base_url(); ?>public/bootstrap/extend/bootbox.js"></script>
        <script src="<?php echo base_url(); ?>public/bootstrap/extend/bootstrap-wysihtml5/js/wysihtml5-0.3.0_rc2.min.js"></script>
        <script src="<?php echo base_url(); ?>public/bootstrap/extend/bootstrap-wysihtml5/js/bootstrap-wysihtml5-0.0.2.js"></script>

        <!-- Google Code Prettify -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/other/google-code-prettify/prettify.js"></script>

        <!-- Gritter Notifications Plugin -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>

        <!-- Notyfy Notifications Plugin -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/notifications/notyfy/jquery.notyfy.js"></script>

        <!-- MiniColors Plugin -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/color/jquery-miniColors/jquery.miniColors.js"></script>

        <!-- DateTimePicker Plugin -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/forms/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

        <!-- Cookie Plugin -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/system/jquery.cookie.js"></script>

        <!-- Colors -->
        <script>
        var primaryColor = '#8ec657',
                dangerColor = '#b55151',
                successColor = '#609450',
                warningColor = '#ab7a4b',
                inverseColor = '#45484d';
        </script>

        <!-- Themer -->
        <script>
            var themerPrimaryColor = primaryColor;
        </script>
        <script src="<?php echo base_url(); ?>public/theme/scripts/demo/themer.js"></script>

        <!-- Twitter Feed -->
       <!--- <script src="<?php echo base_url(); ?>public/theme/scripts/demo/twitter.js"></script>

        <!-- Easy-pie Plugin -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/charts/easy-pie/jquery.easy-pie-chart.js"></script>

        <!-- Sparkline Charts Plugin -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/charts/sparkline/jquery.sparkline.min.js"></script>

        <!-- Ba-Resize Plugin -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/other/jquery.ba-resize.js"></script>

        <!-- Dashboard Demo Script -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/demo/index.js?1369753442"></script>


        <!-- Google JSAPI -->
        <script type="text/javascript" src="<?php echo base_url(); ?>public/theme/scripts/plugins/system/jsapi.js"></script>

        <!--  Flot Charts Plugin -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/charts/flot/jquery.flot.js"></script>
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/charts/flot/jquery.flot.pie.js"></script>
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/charts/flot/jquery.flot.tooltip.js"></script>
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/charts/flot/jquery.flot.selection.js"></script>
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/charts/flot/jquery.flot.resize.js"></script>
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/charts/flot/jquery.flot.orderBars.js"></script>

        <!-- Charts Helper Demo Script -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/demo/charts.helper.js?1369753442"></script>
       
        <!-- Notifications Helper Demo Script -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/demo/notifications.js"></script>

        <!-- Bootstrap Image Gallery -->
        <script src="<?php echo base_url(); ?>public/bootstrap/extend/bootstrap-image-gallery/js/bootstrap-image-gallery.min.js" type="text/javascript"></script>
        
      
    </body>

</html>

