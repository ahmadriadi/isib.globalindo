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
        <noscript>
            <style>
                #login{
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
        <!-- Global Path --> 
        <script type="text/javascript" charset="utf-8">
            var ROOT = {
                'site_url': '<?php echo site_url(); ?>',
                'base_url': '<?php echo base_url(); ?>'
            };
        </script>   
        <!-- no back button -->
        <script type="text/javascript">
            window.history.forward();
            function noBack() {
                window.history.forward();
            }
        </script>        
    </head>
    <body class="login" onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">

        <!-- Wrapper -->
        <div id="login">

            <div class="container">

                <div class="wrapper">

                    <h1 class="glyphicons lock">Globalindo WebApp <i></i></h1>

                    <!-- Box -->
                    <div class="widget">

                        <div class="widget-head">
                            <h3 class="heading">Login area</h3>
                            <div class="pull-right">
                                <!--
                                Don't have an account? 
                                <a href="signup.html?lang=en&amp;layout_type=fluid&amp;menu_position=menu-left&amp;style=style-dark" class="btn btn-inverse btn-mini">Sign up</a>
                                -->
                            </div>
                        </div>
                        <div class="widget-body">

                            <!-- Form -->
                            <!-- <form id="loginForm" method="post" action=""> -->
                            <?php
                            $attributes = array('id' => 'loginForm');
                            echo form_open('login/verification', $attributes);
                            ?>    
                            <label>Username or Employee ID</label>
                            <input id="username" name="username" type="text" class="input-block-level" placeholder="Your Employee ID"/><span id="span-username" class="glyphicons"></span> 
                            <label>Password </label>
                            <input id="password" name="password" type="password" class="input-block-level margin-none" placeholder="Your Password" /><span id="span-password" class="glyphicons"></span>
                            
                            <div class="row-fluid">
                                <div class="span6">
                                    <a  href="<?php echo site_url('lostpassword');?>">forgot your password?</a>
                                </div>
                                <div class="span6 center">
                                    <button id="submit" name="submit" class="btn btn-block btn-primary" type="submit">Sign in</button>
                                </div>
                            </div>
                            <?php echo form_close(); ?>    
                            <!-- </form> -->
                            <!-- // Form END -->

                        </div>
                        <div id="login-msg" class="widget-footer">
                            <!-- <p class="glyphicons restart"><i></i>Please enter your username and password</p> -->
                        </div>
                    </div>
                    <!-- // Box END -->
                    <div class="innerAll center">
                        <!--
                        <p>Alternatively</p>
                        <a href="index.html?lang=en&amp;layout_type=fluid&amp;menu_position=menu-left&amp;style=style-dark" class="btn btn-icon-stacked btn-block btn-facebook glyphicons facebook"><i></i><span>Join using your</span><span class="strong">Facebook Account</span></a>
                        <p>or</p>
                        <a href="index.html?lang=en&amp;layout_type=fluid&amp;menu_position=menu-left&amp;style=style-dark" class="btn btn-icon-stacked btn-block btn-google glyphicons google_plus"><i></i><span>Join using your</span><span class="strong">Google Account</span></a>
                        <p>Having troubles? <a href="faq.html?lang=en&amp;layout_type=fluid&amp;menu_position=menu-left&amp;style=style-dark">Get Help</a></p>
                        -->
                    </div>
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

        <!-- Login -->
        <script src="<?php echo base_url(); ?>public/theme/scripts/plugins/system/login.js"></script>

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
