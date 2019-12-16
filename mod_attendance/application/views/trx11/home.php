<!--AL Entitlement-->
<html>
    <head>
        <!-- Meta -->
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta content="utf-8" http-equiv="encoding" />

        <?php $base_url = $this->session->userdata('sess_base_url'); ?>
        <!-- JQueryUI -->
       
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" />
        <!-- hide the close link in the toolbar -->

        <style type="text/css">
            .red-tooltip + .tooltip > .tooltip-inner {background-color: #800;}
            .transindo{
                font-size: 10px;
            }
            .upper{text-transform:uppercase;}
            a.ui-dialog-titlebar-close { display:none; } .label_error_cuti{color : #be362f;}
            .c{
                transition: 0.5s;
            }
            .warning_field{
                box-shadow: 10px 10px 10px #be362f;
            }

        </style>    
        <!-- Gritter Notifications Plugin -->
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />

        <!-- DataTables Plugin -->
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css" rel="stylesheet" />
        
        <!-- JQuery -->
        <!-- <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-latest.js"></script> -->

        <!-- JQueryUI -->
        <!-- <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>-->
<!--        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>-->
        <!--timepicker-->
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/timepicker/timepicker.css" rel="stylesheet" />
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/timepicker/timepicker.js"></script>
        <!--wysih-->
	<link href="<?php echo $base_url; ?>public/bootstrap/extend/bootstrap-wysihtml5/css/bootstrap-wysihtml5-0.0.2.css" rel="stylesheet">
	<!-- Bootstrap -->
	<script src="<?php echo $base_url; ?>public/bootstrap/js/popup.js"></script>
        <!-- Gritter Notifications Plugin -->
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>
	<!-- DataTables Tables Plugin -->
	<script src="<?php echo $base_url; ?>/public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
	<!-- Tables Demo Script -->
	<!--<script src="<?php echo $base_url; ?>public/theme/scripts/demo/tables.js"></script>-->  
        <!--wysih-->
	<script src="<?php  echo $base_url; ?>public/bootstrap/extend/bootstrap-wysihtml5/js/wysihtml5-0.3.0_rc2.min.js"></script>
	<script src="<?php echo $base_url; ?>public/bootstrap/extend/bootstrap-wysihtml5/js/bootstrap-wysihtml5-0.0.2.js"></script>        
        <script>
//$(window).keypress(function(event) {
//    var formstatus = $("#formstatus").val();
//    if (!(event.which == 115 && event.ctrlKey) && !(event.which == 19)){ 
//        return true;
//        if (formstatus == "open"){
//            save_this();
//        }else{
//            alert("cannot save");
//        }
//        event.preventDefault();
//    }else{
//        
//        return false;
//    }
//});

            $(document).ready(function (){
                addition();
            });
            function clipkonten(){
                        $("#konten").show({
                            effect : "clip"
                        });                                
            }
            function addition(){
                $("#konten").hide({
                    effect :"clip",
                    complete : function (){
                        $("#konten").load(ROOT.base_url + 'mod_attendance/index.php/trx11/home/addition','', 
                            function (){
                                clipkonten();
                            }
                        );
                    }
                });
            }
            function deletion(){
                $("#konten").hide({
                    effect :"clip",
                    complete : function (){                
                        $("#konten").load(ROOT.base_url + 'mod_attendance/index.php/trx11/home/deletion','', 
                            function (){
                                clipkonten();
                            }
                        );
                    }
                });                
            }
            function reserve(){
                $("#konten").hide({
                    effect :"clip",
                    complete : function (){                 
                        $("#konten").load(ROOT.base_url + 'mod_attendance/index.php/trx11/home/reserve','', 
                            function (){
                                clipkonten();
                            }
                        );
                    }
                });                  
            }
            function loading(){
                bootbox.alertloading("<center>Processing. Please wait !!!<br><img src='<?php echo $base_url; ?>public/avatar/76.GIF'></center>");
            }
            function reloadpage(){
                var content = $("#content .innerLR");
                var url = ROOT.base_url + 'mod_attendance/index.php/trx11/home/';
                content.load(url);
            }
            function backtohome(){
                window.location.href = "<?php echo $base_url;?>";
            }
        </script>
    </head>
    <body>
        <div class="widget">
            <div class="widget-head">
                <div class="row-fluid">
                    <div class="span6">
                        <input type="hidden" id="openstate" value="">
                        <input type="hidden" id="selectedid" value="">
                        <input type="hidden" id="proses">
                        <input type="hidden" id="formstatus">
                        <h4 class="heading">AL Entitlements</h4>
                    </div>
                    <div class="span6" style="text-align: right;">
                        <button onclick="reloadpage()" class="btn btn-small btn-default btn-icon"><i class="icon-refresh"></i></button>
                        <button onclick="backtohome()" class="btn btn-success btn-small btn-icon glyphicons home"><i></i>Back to Home</button>
                    </div>
                </div>
            </div>
            <div class="widget-body">
                <div class="tabsbar tabsbar-2 active-fill">
                    <ul class="row-fluid ">
                        <li class="span4 tab1 tabatas glyphicons database_plus active inmemoic">
                            <a onclick="addition()" data-toggle="tab"><i></i>
                                <b>Entitlement Addition</b>
                                <!--<div class="ribbon-wrapper small ribtab1"><h4 style="color: #be362f; text-shadow: 1px 1px 1px #000;">&ast;</h4></div>-->
                            </a>
                        </li>
                        <li class="span4 tab2 tabatas glyphicons database_minus outmemoic" >
                            <a onclick="deletion()" data-toggle="tab"><i></i> 
                                <b>Entitlement Deletion</b>
                                <!--<div class="ribbon-wrapper small ribtab2"><h4 style="color: #be362f; text-shadow: 1px 1px 1px #000;">&ast;</h4></div>-->
                            </a>
                        </li>
                        <li class="span4 tab2 tabatas glyphicons database_lock outmemoic" >
                            <a onclick="reserve()" data-toggle="tab"><i></i> 
                                <b>Entitlement Reserves</b>
                                <!--<div class="ribbon-wrapper small ribtab2"><h4 style="color: #be362f; text-shadow: 1px 1px 1px #000;">&ast;</h4></div>-->
                            </a>
                        </li>
                    </ul>
                </div>
                <div id="konten">
                    
                </div>

            </div>
        </div>
    </body>
</html>

