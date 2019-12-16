<!--TASKS-->
<html>
    <head>
        <!-- Meta -->
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta content="utf-8" http-equiv="encoding" />

        <?php $base_url = $this->session->userdata('sess_base_url'); ?>
        <!-- JQueryUI -->
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" rel="stylesheet" />
        <!-- hide the close link in the toolbar -->

        <style type="text/css">
            .red-tooltip + .tooltip > .tooltip-inner {background-color: #800;}
            .transindo{
                font-size: 10px;
            }
            .upper{text-transform:uppercase;}
            a.ui-dialog-titlebar-close { display:none; }
            #owl-demo .item img{
                display: block;
                width: 100%;
                height: auto;
            }
            #notifarea{
                transition-duration: 0.3s;
            }
            
             .colornotif{
                background-color: #800;
            }
            
        </style>
        <!-- Gritter Notifications Plugin -->
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />

        <!-- DataTables Plugin -->
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css" rel="stylesheet" />
        
        <!-- JQuery -->
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-latest.js"></script>

        <!-- JQueryUI -->
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<!--        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>-->
        <!--timepicker-->
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/timepicker/timepicker.css" rel="stylesheet" />
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/timepicker/timepicker.js"></script>
        <!--wysih-->
	<link href="<?php echo $base_url; ?>public/bootstrap/extend/bootstrap-wysihtml5/css/bootstrap-wysihtml5-0.0.2.css" rel="stylesheet">
	<!-- Bootstrap -->
	<!--<script src="<?php echo $base_url; ?>public/bootstrap/js/bootstrap.js"></script>-->
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
        check_();
        $("#notinmemo").hide();
        $("#notmemofeed").hide();
        function reloadview(){
            var aktif = $("#whichactive").val();
            notif(aktif);
        }
        function loading(){
            bootbox.alertloading("<center>Processing. Please wait !!!<br><img src='<?php echo $base_url; ?>public/avatar/76.GIF'></center>");
        }        
        function notif(val){
            var aktif = $("#whichactive").val();
            $("#accepted").remove();
            if (val == "notinmemo"){
                $("#"+aktif).hide();
                $("#notinmemo").show({});
                $("#whichactive").val('notinmemo');
            }
            else if (val == "notmemofeed"){
                $("#"+aktif).hide();
                $("#notmemofeed").show({});
                $("#whichactive").val('notmemofeed');
            }
            else if (val == "notmemocon"){
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx03/home/notification",
                    data    : "",
                    type    : "post",
                    dataType: "html",
                    success : function (data){
//                        alert("oke");
                        $("#notmemocon").remove();
                        $("#"+aktif).hide();
                        $("#notifhere").html(data);
                        $("#notmemocon").show({});
                        $("#whichactive").val('notmemocon');                        
                    },
                    error   : function (a){
                        alert(a.responseText);
                    }
                });                
            }
            else if (val == "notpiccon"){
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx01/home/notification/pic",
                    data    : "",
                    type    : "post",
                    dataType: "html",
                    success : function (data){
                        
                        $("#notpiccon").remove();
                        $("#"+aktif).hide();
                        $("#notifhere").html(data);
                        $("#notpiccon").show({});
                        $("#whichactive").val('notpiccon');
                    },
                    error   : function (a){
                        alert(a.responseText);
                    }                    
                });
            }
            
            else if (val == "nothodcon"){
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx01/home/notification/hod",
                    data    : "",
                    type    : "post",
                    dataType: "html",
                    success : function (data){
                        $("#nothodcon").remove();
                        $("#"+aktif).hide();
                        $("#notifhere").html(data);
                        $("#nothodcon").show({});
                        $("#whichactive").val('nothodcon');                        
                    },
                    error   : function (a){
                        alert(a.responseText);
                    }                    
                });
            }
//            khusus HRD            
            else if (val == "nothrdcon"){
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx01/home/notification/hrd",
                    data    : "",
                    type    : "post",
                    dataType: "html",
                    success : function (data){
                        $("#nothrdcon").remove();
                        $("#"+aktif).hide();
                        $("#notifhere").html(data);
                        $("#nothrdcon").show({});
                        $("#whichactive").val('nothrdcon');                        
                    },
                    error   : function (a){
                        alert(a.responseText);
                    }                    
                });
            }
            
//            else if (val == "noticphrd"){
//                $.ajax({
//                    url     : ROOT.base_url+"mod_attendance/index.php/trx03/home/notification",
//                    data    : "notfor=hrd",
//                    type    : "post",
//                    dataType: "html",
//                    success : function (data){
//                        $("#notincomplete").remove();
//                        $("#"+aktif).hide();
//                        $("#notifhere").html(data);
//                        $("#notincomplete").show({});
//                        $("#whichactive").val('notincomplete');                        
//                    }
//                }); 
//            }
            
//            end of khusu HRD
            else if (val == 'notlpermit'){
                $.ajax({
                    url     : ROOT.base_url+"mod_attendance/index.php/trx02/home/notification",
                    data    : "",
                    type    : "post",
                    dataType: "html",
                    success : function (data){
                        $("#notlpermit").remove();
                        $("#"+aktif).hide();
                        $("#notifhere").html(data);
                        $("#notlpermit").show({});
                        $("#whichactive").val('notlpermit');                        
                    },
                    error   : function (a){
                        alert(a.responseText);
                    }                    
                });                
            }
            else if (val == 'notofftravel'){
//                alert("oke");
                $.ajax({
                    url     : ROOT.base_url+"mod_attendance/index.php/trx04/home/notifications",
                    data    : "",
                    type    : "post",
                    dataType: "html",
                    success : function (data){
                        $("#notofftravel").remove();
                        $("#"+aktif).hide();
                        $("#notifhere").html(data);
                        $("#notofftravel").show({});
                        $("#whichactive").val('notofftravel');                        
                    },
                    error   : function (a){
                        alert(a.responseText);
                    }                    
                });                
            }
            else if (val == 'notovertime'){
                $.ajax({
                    url     : ROOT.base_url+"mod_attendance/index.php/trx01/home/notification",
                    data    : "",
                    type    : "post",
                    dataType: "html",
                    success : function (data){
                        $("#notovertime").remove();
                        $("#"+aktif).hide();
                        $("#notifhere").html(data);
                        $("#notovertime").show({});
                        $("#whichactive").val('notovertime');                        
                    },
                    error   : function (a){
                        alert(a.responseText);
                    }                    
                });                  
            }
            else if (val == 'notincomplete'){
                $.ajax({
                    url     : ROOT.base_url+"mod_attendance/index.php/trx03/home/notification",
                    data    : "notfor=man",
                    type    : "post",
                    dataType: "html",
                    success : function (data){
                        $("#notincomplete").remove();
                        $("#"+aktif).hide();
                        $("#notifhere").html(data);
                        $("#notincomplete").show({});
                        $("#whichactive").val('notincomplete');                        
                    },
                    error   : function (a){
                        alert(a.responseText);
                    }                    
                });                  
            }
            else if (val == 'notureport'){
                $.ajax({
                    url     : ROOT.base_url+"mod_security/index.php/trx10/home/notification",
                    data    : "",
                    type    : "post",
                    dataType: "html",
                    success : function (data){
                        $("#notureport").remove();
                        $("#"+aktif).hide();
                        $("#notifhere").html(data);
                        $("#notureport").show({});
                        $("#whichactive").val('notureport');
                    },
                    error   : function (a){
                        alert(a.responseText);
                    }                    
                });
            }else if (val == 'notdigitalform'){
                
                //alert(ROOT.base_url+"mod_finance/index.php/notif01/home/notification");
                
                $.ajax({
                    url     : ROOT.base_url+"mod_finance/index.php/notif01/home/notification",
                    data    : "",
                    type    : "post",
                    dataType: "html",
                    success : function (data){
                        $("#notdigitalform").remove();
                        $("#"+aktif).hide();
                        $("#notifhere").html(data);
                        $("#notdigitalform").show({});
                        $("#whichactive").val('notdigitalform');
                    },
                    error   : function (a){
                        alert(a.responseText);
                    }                    
                });
            }
        }
        function gotomemo(val){
            var content = $("#content .innerLR");
            var url = ROOT.base_url+"mod_empcenter/index.php/trx03/home/index/"+val;
//            alert(url);
            content.load(url);
        }
        function check_(){
            $.ajax({
                url     : ROOT.base_url+"/mod_security/index.php/trx03/home/tasks_check",
                data    : "",
                type    : "post",
                dataType: "json",
                cache   : false,
                success : function (data){
                    $(".jmlinmemo").text(data.inmemo);
                    if (data.inmemo < 1){
                        $(".jmlinmemo").removeClass('label-important');
                        $(".jmlinmemo").addClass('label-inverse');
                    }else{
                        $(".jmlinmemo").removeClass('label-inverse');
                        $(".jmlinmemo").addClass('label-important');                        
                    }                    
                    $(".jmlfeedmemo").text(data.memofeed);
                    if (data.memofeed < 1){
                        $(".jmlfeedmemo").removeClass('label-important');
                        $(".jmlfeedmemo").addClass('label-inverse');
                    }else{
                        $(".jmlfeedmemo").removeClass('label-inverse');
                        $(".jmlfeedmemo").addClass('label-important');                        
                    }
                    <?php 
//                    khusus manager
                    ?>
                        $(".jmlmemocon").text(data.memocon);
                        if (data.memocon < 1){
                            $(".jmlmemocon").removeClass('label-important');
                            $(".jmlmemocon").addClass('label-inverse');
                        }else{
                            $(".jmlmemocon").removeClass('label-inverse');
                            $(".jmlmemocon").addClass('label-important');                        
                        }                            
                    <?php 
//                    end of khusus manager
                    ?>
                    $(".jmlleavepic").text(data.leavepic);
                    if (data.leavepic < 1){
                        $(".jmlleavepic").removeClass('label-important');
                        $(".jmlleavepic").addClass('label-inverse');
                    }else{
                        $(".jmlleavepic").removeClass('label-inverse');
                        $(".jmlleavepic").addClass('label-important');                        
                    }                    
                    $(".jmlleaveats").text(data.leaveats);
                    if (data.leaveats < 1){
                        $(".jmlleaveats").removeClass('label-important');
                        $(".jmlleaveats").addClass('label-inverse');
                    }else{
                        $(".jmlleaveats").removeClass('label-inverse');
                        $(".jmlleaveats").addClass('label-important');                        
                    }
                    <?php 
                    $whhrd  = "IDParam = 'IDHRD' OR IDParam = 'IDHRDMGR'";
                    $hrd    = $this->task->get_hrd($whhrd)->result();
                    $hrnya  = array();
                    foreach ($hrd as $h){
                        $hrnya[] = $h->ParamValue;
                    }
                    if (in_array($this->iduser,$hrnya)){
                    ?>
                    //khusus HRD
                    $(".jmlleavehrd").text(data.leavehrd);
                    if (data.leavehrd < 1){
                        $(".jmlleavehrd").removeClass('label-important');
                        $(".jmlleavehrd").addClass('label-inverse');
                    }else{
                        $(".jmlleavehrd").removeClass('label-inverse');
                        $(".jmlleavehrd").addClass('label-important');                        
                    }
//                    $(".jmlicphrd").text(data.icphrd);
//                    if (data.icphrd < 1){
//                        $(".jmlicphrd").removeClass('label-important');
//                        $(".jmlicphrd").addClass('label-inverse');
//                    }else{
//                        $(".jmlicphrd").removeClass('label-inverse');
//                        $(".jmlicphrd").addClass('label-important');                        
//                    }
                    // end of khusu HRD
                    <?php 
                        }
                    ?>
                    $(".jmllpermit").text(data.lpermit);
                    if (data.lpermit < 1){
                        $(".jmllpermit").removeClass('label-important');
                        $(".jmllpermit").addClass('label-inverse');
                    }else{
                        $(".jmllpermit").removeClass('label-inverse');
                        $(".jmllpermit").addClass('label-important');                        
                    }                      
                    $(".jmlofftravel").text(data.offtravel);
                    if (data.offtravel < 1){
                        $(".jmlofftravel").removeClass('label-important');
                        $(".jmlofftravel").addClass('label-inverse');
                    }else{
                        $(".jmlofftravel").removeClass('label-inverse');
                        $(".jmlofftravel").addClass('label-important');                        
                    }
                    $(".jmlovertime").text(data.overtime);
                    if (data.overtime < 1){
                        $(".jmlovertime").removeClass('label-important');
                        $(".jmlovertime").addClass('label-inverse');
                    }else{
                        $(".jmlovertime").removeClass('label-inverse');
                        $(".jmlovertime").addClass('label-important');                        
                    }                    
                    $(".jmlincomplete").text(data.incomplete);
                    if (data.incomplete < 1){
                        $(".jmlincomplete").removeClass('label-important');
                        $(".jmlincomplete").addClass('label-inverse');
                    }else{
                        $(".jmlincomplete").removeClass('label-inverse');
                        $(".jmlincomplete").addClass('label-important');                        
                    }
                    $(".jmlureport").text(data.ureport);
                    if (data.ureport < 1){
                        $(".jmlureport").removeClass('label-important');
                        $(".jmlureport").addClass('label-inverse');
                    }else{
                        $(".jmlureport").removeClass('label-inverse');
                        $(".jmlureport").addClass('label-important');                        
                    }
                    
                    $(".jmldigitalform").text(data.digitalform);
                    
                    if (data.digitalform < 1){
                        $(".jmldigitalform").removeClass('label-important');
                        $(".jmldigitalform").addClass('label-inverse');
                    }else{
                        $(".jmldigitalform").removeClass('label-inverse');
                        $(".jmldigitalform").addClass('label-important');                        
                    }
                    
                }
            });
            setTimeout("check_()",5000);
        }
        function wider(){
            
            $("#notifarea").css("margin-left","0px");
            $("#allnotif").hide({
                easing  : "slide",
                duration: 300,
                complete: function (){
                    $("button[btn='btnwider']").hide({
                        complete : function (){
                            $("button[btn='btnhalf']").show({});
                            
                            $(".forhide").show({});// on leave request, pic
                            $(".showaccepted").show({});
                        }
                    });
                    $("#notifarea").removeClass('span6');
                    $("#notifarea").addClass('span12');
                    
                }
            });
        }
        function half(){
            $(".showaccepted").hide({});
            hideaccepted();
            $("#notifarea").removeClass('span12');
            $("#notifarea").addClass('span6');
            $("#notifarea").removeAttr("style");
            $(".forhide").hide({});
            $("#allnotif").show({
                easing  : "slide",
                duration: 300,
                complete: function (){
                    $("button[btn='btnhalf']").hide({
                        complete : function (){
                            $("button[btn='btnwider']").show({});
                            
                        }
                    });
                    // on leave request, pic
                }
            });
        }
        function showaccepted(){
            $(".showaccepted").html("<i></i>Hide Approved List");
            $(".showaccepted").removeAttr("onclick");
            $(".showaccepted").attr("onclick","hideaccepted()");
            $("#list_of_accepted").show({
                complete: function (){
                    create_accepted();
                    var listpos = $("#judulaccepted").offset();
//                    alert(listpos.top)
                    $('html, body').animate({
                        scrollTop : listpos.top
                    },500);                    
                }
            });

        }
        function hideaccepted(){
            
            $(".showaccepted").html("<i></i>Show Approved List");
            $(".showaccepted").removeAttr("onclick");
            $(".showaccepted").attr("onclick","showaccepted()");            
            $("#list_of_accepted").hide({});

        }
        </script>

    </head>
    <body>
        <div class="widget">
            <div class="widget-head">
                <input type="hidden" id="whichactive" value="awal">
                <h4 class="heading">Tasks</h4>
            </div>
            <div class="widget-body">
                <div class="row-fluid">
                    <div class="span6" id="allnotif">
                        <div class="row-fluid">
                            <div class="span6">
                                <span onclick="notif('notinmemo')" class=" btn btn-icon-stacked btn-block btn-inverse glyphicons message_in">
                                    <i></i>
                                    <span><strong class="count label label-important jmlinmemo"></strong>New</span>                                
                                    <strong>Incoming Memo</strong>
                                </span>
                            </div>                  
                            <div class="span6">
                                <span onclick="notif('notmemofeed')" class=" btn btn-icon-stacked btn-block btn-inverse glyphicons share_alt">
                                    <i></i>
                                    <span><strong class="count label label-inverse jmlfeedmemo"></strong>New</span>                                
                                    <strong>Memo Feeds</strong>
                                </span>
                            </div>                            
                        </div>
                        <?php 
                        //khusus manager
                        $u = $this->task->get_personal($this->iduser)->row();
                        //print_r($u);
                        if (($u->IDJobPosition == "MANAGER") OR ($u->IDJobPosition == "DIRECTOR") OR ($u->IDJobPosition == 'ASSISTANT DIRECTOR') OR ($u->IDJobPosition == 'ASSISTANT MANAGER') ){
                        ?>
                            <div class="separator"></div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <span onclick="notif('notmemocon')" class="center btn btn-icon-stacked btn-block btn-inverse glyphicons message_flag">
                                        <i></i>
                                        <span><strong class="count label label-inverse jmlmemocon"></strong>New</span>                                
                                        <strong>Memo Awaiting Confirmation</strong>
                                    </span>
                                </div>
                            </div>
                        <?php
                        }
                        //end of khusus manager
                        
                        ?>
                        <div class="separator"></div>
                        <div class="row-fluid">
                            <div class="span6">
                                <span onclick="notif('notpiccon')" class=" btn btn-icon-stacked btn-block btn-inverse glyphicons calendar">
                                    <i></i>
                                    <span><strong class="count label label-important jmlleavepic"></strong>Person In Charge</span>                                
                                    <strong>Leave Confirmation</strong>
                                </span>
                            </div>                              
                            <div class="span6">
                                <span onclick="notif('nothodcon')" class=" btn btn-icon-stacked btn-block btn-inverse glyphicons calendar">
                                    <i></i>
                                    <span><strong class="count label label-important jmlleaveats"></strong>Head of Department</span>                                
                                    <strong>Leave Confirmation</strong>
                                </span>
                            </div>                              
                        </div>
                        <?php 
                        //khusu hrd
                                $whhrd  = "IDParam = 'IDHRD' OR IDParam = 'IDHRDMGR'";
                                $hrd    = $this->task->get_hrd($whhrd)->result();
                                $hrnya  = array();
                                foreach ($hrd as $h){
                                    $hrnya[] = $h->ParamValue;
                                }
                            if (in_array($this->iduser,$hrnya)){
                            ?>
                            <div class="separator"></div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <span onclick="notif('nothrdcon')" class=" btn btn-icon-stacked btn-block btn-inverse glyphicons calendar" style="text-align: center;">
                                        <i></i>
                                        <span><strong class="count label label-important jmlleavehrd"></strong>Human Resource Development</span>                                
                                        <strong>Leave Confirmation</strong>
                                    </span>
                                </div>                                                          
                            </div>
                            <?php }
                        //end of khusus hrd
                        ?>
                        <div class="separator"></div>
                        <div class="row-fluid">
                            <div class="span6">
                                <span onclick="notif('notlpermit')" class=" btn btn-icon-stacked btn-block btn-inverse glyphicons cars">
                                    <i></i>
                                    <span><strong class="count label label-important jmllpermit"></strong><strong>Leave Permit</strong></span>                                
                                    Awaiting Confirmation
                                </span>
                            </div>                              
                            <div class="span6">
                                <span onclick="notif('notofftravel')" class=" btn btn-icon-stacked btn-block btn-inverse glyphicons bus">
                                    <i></i>
                                    <span><strong class="count label label-important jmlofftravel"></strong><strong>Official Travel</strong></span>                                
                                    Awaiting Confirmation
                                </span>
                            </div>                              
                        </div>
                        <div class="separator"></div>
                        <div class="row-fluid">
                            <div class="span6">
                                <span onclick="notif('notovertime')" class=" btn btn-icon-stacked btn-block btn-inverse glyphicons clock">
                                    <i></i>
                                    <span><strong class="count label label-important jmlovertime"></strong><strong>Overtime</strong></span>                                
                                    Awaiting Confirmation
                                </span>
                            </div>                              
                            <div class="span6">
                                <span onclick="notif('notincomplete')"class=" btn btn-icon-stacked btn-block btn-inverse glyphicons circle_exclamation_mark">
                                    <i></i>
                                    <span><strong class="count label label-important jmlincomplete"></strong><strong>Incomplete</strong></span>                                
                                    Awaiting Confirmation
                                </span>
                            </div>                              
                        </div>
                        <div class="separator"></div>
                        <div class="row-fluid">
                            <div class="span6 center">
                                <span onclick="notif('notureport')"class="btn btn-icon-stacked btn-block btn-inverse glyphicons circle_info">
                                    <i></i>
                                    <span><strong class="count label label-important jmlureport"></strong><strong>User Request(s)</strong></span>                                
                                    Awaiting Confirmation
                                </span>                                
                            </div>
                             <div class="span6 center">
                                <span onclick="notif('notdigitalform')"class="btn btn-icon-stacked btn-block btn-inverse glyphicons coins">
                                    <i></i>
                                    <span><strong class="count label label-important jmldigitalform"></strong><strong>Digital Form</strong></span>                                
                                    Awaiting Confirmation
                                </span>                                
                            </div>
                        </div>                        
                        <?php 
                        //khusu hrd
//                                $whhrd  = "IDParam = 'IDHRD' OR IDParam = 'IDHRDMGR'";
//                                $hrd    = $this->task->get_hrd($whhrd)->result();
//                                $hrnya  = array();
//                                foreach ($hrd as $h){
//                                    $hrnya[] = $h->ParamValue;
//                                }
//                            if (in_array($this->iduser,$hrnya)){
//                            ?>
<!--                            <div class="separator"></div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <span onclick="notif('noticphrd')" class=" btn btn-icon-stacked btn-block btn-inverse glyphicons calendar" style="text-align: center;">
                                        <i></i>
                                        <span><strong class="count label label-important jmlicphrd"></strong>Human Resource Development</span>                                
                                        <strong>Incomplete Confirmation</strong>
                                    </span>
                                </div>                                                          
                            </div>-->
                            <?php 
                            
//                            }
                        //end of khusus hrd
                        ?>                        
                    </div>
                    <div class="span6 widget widget-body" id="notifarea">
                        <div class="row-fluid">
                            <div class="span6 left">
                                <button btn="btnwider" onclick="wider()" class="btn btn-small btn-icon btn-primary glyphicons left_arrow"><i></i>Wider</button>
                                <button btn="btnhalf" onclick="half()" style="display:none;" class="btn btn-small btn-icon btn-primary glyphicons right_arrow"><i></i>Half Width</button>
                                <!--<button onclick="reloadview()" class="btn btn-small btn-icon btn-primary glyphicons right_arrow"><i></i>tes reload</button>--> 
                            </div>
                            <div class="span6 right">
                                <button class="showaccepted btn btn-default btn-icon btn-small glyphicons log_book" onclick="showaccepted()" style="display: none;"><i></i>Show Approved List</button>
                            </div>
                        </div>
                        <div class="row-fluid" id="notifhere">
                            <div class="span12" align="center" id="awal">
                                <h4>Tasks Notifications</h4>
                                <hr class="separator">
                            </div>
                            <div class="span12" align="center" id="notinmemo" style="margin-left: 0px;">
                                <h4>Incoming Memo</h4>
                                <hr class="separator">
                                <div class="row-fluid">
                                    <div class="span12">
                                        <button onclick='gotomemo("in")' class="btn btn-block btn-facebook">Go To Memo</button>
                                    </div>
                                </div>
                            </div>
                            <div class="span12" align="center" id="notmemofeed" style="margin-left: 0px;">
                                <h4>Memo Feedback</h4>
                                <hr class="separator">
                                <div class="row-fluid">
                                    <div class="span12">
                                        <button onclick='gotomemo("out")' class="btn btn-block btn-facebook">Go To Memo</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
