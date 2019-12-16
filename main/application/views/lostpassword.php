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
        <link href="<?php echo base_url(); ?>public/theme/css/style-dark.css?1369753445" rel="stylesheet" />


        <!-- LESS.js Library -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/system/less.min.js"></script>
    </head>
    <body class="login">

        <!-- Wrapper -->
        <div id="login">

            <div class="wrapper signup">

                <h1 class="glyphicons lock">Lost Password <i></i></h1>

                <!-- Box -->
                <div class="widget">

                    <div class="widget-head">
                        <h3 class="heading">Modify Account</h3>
                        <div class="pull-right">
                            <!--
                                Already a member?
                                <a href="login.html?lang=en&amp;layout_type=fluid&amp;menu_position=menu-left&amp;style=style-dark" class="btn btn-inverse btn-mini">Sign in</a>
                            -->
                        </div>
                    </div>
                    <div class="widget-body">

                        <!-- Form -->
                        <form method="post" action="<?php echo site_url('sendpassword') ?>">

                            <!-- Row -->
                            <div class="row-fluid row-merge">

                                <!-- Column -->
                                <div class="span6">
                                    <div class="innerR">
                                        <label class="strong">Username or ID Employee</label>
                                        <input id="username" name="username"  value="<?php echo set_value('username') ?>" type="text" class="input-block-level" placeholder="Your Username or ID Employee"/>
                                        <?php echo form_error('username', '<div class="error">', '</div>'); ?>
                                        <!--
                                        <label class="strong">Password</label>
                                        <input type="password" class="input-block-level" placeholder="Your Password"/>
                                        <label class="strong">Confirm Password</label>
                                        <input type="password" class="input-block-level" placeholder="Confirm Password"/>
                                        -->
                                        <p>
                                        <?php
                                        if ($this->session->flashdata('lost-msg')) {
                                            echo $this->session->flashdata('lost-msg');
                                        }
                                        ?>
                                        </p>
                                    </div>
                                </div>
                                <!-- // Column END -->

                                <!-- Column -->
                                <div class="span6">
                                    <div class="innerL">
                                        <label class="strong">Email</label>
                                        <input id="email" name="email" value="<?php echo set_value('email') ?>" type="text" class="input-block-level" placeholder="Your Email Address"/>
                                        <?php echo form_error('email', '<div class="error">', '</div>'); ?>
                                        <label class="strong">Confirm Email</label>
                                        <input id="email2" name="email2" value="<?php echo set_value('email2') ?>" type="text" class="input-block-level" placeholder="Your Email Confirmation"/>
                                        <?php echo form_error('email2', '<div class="error">', '</div>'); ?>
                                        <br>
                                            <button id="submit" name="submit" class="btn btn-icon-stacked btn-block btn-success glyphicons user_add" type="submit"><i></i><span>Modify account and</span><span class="strong">Send Password </span></button>
                                            <p>Having troubles? <a href="<?php echo site_url('faq'); ?>">Get Help</a></p>
                                    </div>
                                </div>
                                <!-- // Column END -->

                            </div>
                            <!-- // Row END -->

                        </form>
                        <!-- // Form END -->


                    </div>
                    <!-- // Box END -->

                </div>

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
        <script src="<?php echo base_url(); ?>public/theme/scripts/demo/common.js?1369753445"></script>

        <!-- Holder Plugin -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/other/holder/holder.js"></script>

        <!-- Uniform Forms Plugin -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/forms/pixelmatrix-uniform/jquery.uniform.min.js"></script>

    </body>
</html>
