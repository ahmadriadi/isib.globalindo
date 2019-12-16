<!DOCTYPE html PUBLIC"-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" />
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9"> <![endif]-->
<!--[if gt IE 8]> <html class="ie gt-ie8"> <![endif]-->
<!--[if !IE]><!--><html><!-- <![endif]-->
    <head>
        <title>ISIB - Web Application</title>

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

        <!-- Main Theme Stylesheet :: CSS -->
        <link href="<?php echo base_url(); ?>public/theme/css/style-dark.css?1369753444" rel="stylesheet" />

        <!-- LESS.js Library -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/system/less.min.js"></script>
    </head>
    <body class="login">

        <!-- Wrapper -->
        <div id="login">

            <div class="container">

                <!-- Box -->
                <div class="hero-unit well">
                    <h1><?php echo $default['msg-header']; ?> <span> ! </span></h1>
                    <hr class="separator" />
                    <!-- Row -->
                    <div class="row-fluid row-merge">

                        <!-- Column -->
                        <div class="span6">
                            <div class="innerAll center">
                                <p><?php echo $default['msg-content']; ?></p>
                            </div>
                        </div>
                        <!-- // Column END -->

                        <!-- Column -->
                        <div class="span6">
                            <div class="innerAll center">
                                <p><?php echo $default['msg-continue']; ?> <a href="<?php echo site_url('feedback') ?>">Let us know</a></p>
                                <div class="row-fluid">
                                    <div class="span6">
                                        <a href="<?php echo base_url(); ?>" class="btn btn-icon-stacked btn-block btn-success glyphicons user_add"><i></i><span>Go back to</span><span class="strong">Main Page</span></a>
                                    </div>
                                    <div class="span6">
                                        <a href="<?php echo site_url('faq'); ?>" class="btn btn-icon-stacked btn-block btn-danger glyphicons circle_question_mark"><i></i><span>Browse through our</span><span class="strong">Support Centre</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- // Column END -->

                    </div>
                    <!-- // Row END -->

                </div>
                <!-- // Box END -->

            </div>

        </div>
        <!-- // Wrapper END -->	

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

        <!-- JQuery -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/system/jquery.min.js"></script>

        <!-- Modernizr -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/system/modernizr.js"></script>

        <!-- Bootstrap -->
        <script src="<?php echo base_url(); ?>public/bootstrap/js/bootstrap.min.js"></script>

        <!-- SlimScroll Plugin -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/other/jquery-slimScroll/jquery.slimscroll.min.js"></script>

        <!-- Common Demo Script -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/demo/common.js?1369753444"></script>

        <!-- Holder Plugin -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/other/holder/holder.js"></script>

        <!-- Uniform Forms Plugin -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/forms/pixelmatrix-uniform/jquery.uniform.min.js"></script>

    </body>
</html>
