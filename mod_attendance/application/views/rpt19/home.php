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
            .accept{color: #00CC00;font-weight: bold;}
            .waiting{color: #EC5800;font-weight: bold;}
            .reject{color: #ee1e2d;font-weight: bold;}
            .transindo{
                font-size: 10px;
            }
            .upper{text-transform:uppercase;}
            a.ui-dialog-titlebar-close { display:block } .label_error_cuti{color : #be362f;}
        </style>    
        <!-- Gritter Notifications Plugin -->
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />

        <!-- DataTables Plugin -->
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css" rel="stylesheet" />
        
        <!-- JQuery -->
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-latest.js"></script>

        <!-- JQueryUI -->
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
        <!--timepicker-->
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/timepicker/timepicker.css" rel="stylesheet" />
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/timepicker/timepicker.js"></script>
	<!-- Bootstrap -->
	<script src="<?php echo $base_url; ?>public/bootstrap/js/bootstrap.js"></script>

        <!-- Gritter Notifications Plugin -->
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>
	<!-- DataTables Tables Plugin -->
	<script src="<?php echo $base_url; ?>/public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
	<!-- Tables Demo Script -->
	<script src="<?php echo $base_url; ?>public/theme/scripts/demo/tables.js"></script>
        <script>
            function loading(){
                bootbox.alertloading("<center>Processing. Please wait !!!<br><img src='<?php echo $base_url; ?>public/avatar/76.GIF'></center>");
            }            

            $(function() {
               var emp = <?php echo $emp;?>;
               $( "#nmemployee" ).autocomplete({
                   source: emp,
                   select: function (e,ui){
                       $("#idemployee").val(ui.item.idemp);
                       $("#hdate").val(ui.item.hdate);
                   }
               });
           });
           function view(){
               var idemp    = $("#idemployee").val();
               var hdate    = $("#hdate").val();
               $(".hasilprint").empty();
//               loading();
               $.ajax({
                    url     : "<?php echo $base_url."mod_attendance/index.php/rpt19/home/get_data"?>",
                    data    : "idemp="+idemp+"&hdate="+hdate,
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
                        $(".t1").html("No data");
                        $(".t2").html("No data");
                        var order;
                        $(".t1").hide();
                        $(".tab1").addClass('active');
                        $(".t2").hide();
                        $(".tab2").removeClass('active');
                        $(".t3").hide();
                        $(".tab3").removeClass('active');
//                        bootbox.hideAll();
                        if (data.all == "no"){
                            order = false;
                            $("#selectedid").val(idemp);
                            $("#selecteddate").val(hdate);
                            var res = '';
                            res = res+'<table>';
                                res = res+'<tr>';
                                    res = res+'<td>';
                                    res = res+'Employee ID';
                                    res = res+'</td>';
                                    res = res+'<td>';
                                    res = res+':';
                                    res = res+'</td>';
                                    res = res+'<td> ';
                                    res = res+data.thisemp.IDEmployee;
                                    res = res+'</td>';
                                res = res+'</tr>';
                                res = res+'<tr>';
                                    res = res+'<td>';
                                    res = res+'Employee Name';
                                    res = res+'</td>';
                                    res = res+'<td>';
                                    res = res+':';
                                    res = res+'</td>';
                                    res = res+'<td> ';
                                    res = res+data.thisemp.FullName;
                                    res = res+'</td>';
                                res = res+'</tr>';
                                res = res+'<tr>';
                                    res = res+'<td>';
                                    res = res+'Hire Date';
                                    res = res+'</td>';
                                    res = res+'<td>';
                                    res = res+':';
                                    res = res+'</td>';
                                    res = res+'<td> ';
                                    res = res+data.thisemp.HireDate;
                                    res = res+'</td>';
                                res = res+'</tr>';
                                res = res+'<tr>';
                                    res = res+'<td>';
                                    res = res+'Untaken Leave';
                                    res = res+'</td>';
                                    res = res+'<td>';
                                    res = res+':';
                                    res = res+'</td> ';
                                    res = res+'<td>';
                                    res = res+"<b>"+data.utk+" day(s)**</b>";
                                    res = res+'</td>';
                                res = res+'</tr>';
                                res = res+'<tr>';
                                    res = res+"<td colspan='2'><i style='font-size: 7px;'>** today's calculation; requested leave tomorrow is not calculated</i></td>";
                                res = res+'</tr>';
                            res = res+'</table>';
                            //==============================
                            var mas    = "";
                            mas    = "<br><b>Additional of "+data.thisemp.FullName+"'s Leave Entitlements :</b><br><br>";
                            mas    = mas+'<table class="table table-condensed dynamicTable">';
                                mas    = mas+'<thead>';
                                    mas    = mas+'<tr>';
                                        mas    = mas+'<th>';
                                            mas    = mas+'No';
                                        mas    = mas+'</th>';
                                        mas    = mas+'<th>';
                                            mas    = mas+'Addition Date';
                                        mas    = mas+'</th>';
                                        mas    = mas+'<th>';
                                            mas    = mas+'Amount';
                                        mas    = mas+'</th>';
                                        mas    = mas+'<th>';
                                            mas    = mas+'Note';
                                        mas    = mas+'</th>';
                                    mas    = mas+'</tr>';
                                mas    = mas+'</thead>';
                                mas    = mas+'<tbody>';
                                    for (var i=0; i < data.master.length; i++){
                                        mas    = mas+'<tr class="selectable">';
                                            mas    = mas+'<td>';
                                                mas    = mas+(i*1+1);
                                            mas    = mas+'</td>';
                                            mas    = mas+'<td>';
                                                mas    = mas+data.master[i].Tanggal;
                                            mas    = mas+'</td>';
                                            mas    = mas+'<td>';
                                                mas    = mas+data.master[i].Jml;
                                            mas    = mas+'</td>';
                                            mas    = mas+'<td>';
                                                mas    = mas+data.master[i].Alasan;
                                            mas    = mas+'</td>';
                                        mas    = mas+'</tr>';
                                    }
                                mas    = mas+'</tbody>';
                            mas    = mas+'</table>'; 
    //                        /===================
                            $(".t1").html(res+mas);

                            $(".t1").show({
                                effect :"highlight",
                                duration: 500
                            });
                            var res2    = "";
                            res2    = "<b>"+data.thisemp.FullName+"'s Annual Leave:</b><br><br>";
                            res2    = res2+'<table class="table table-condensed dynamicTable">';
                                res2    = res2+'<thead>';
                                    res2    = res2+'<tr>';
                                        res2    = res2+'<th>';
                                            res2    = res2+'No';
                                        res2    = res2+'</th>';
                                        res2    = res2+'<th>';
                                            res2    = res2+'Date';
                                        res2    = res2+'</th>';
                                        res2    = res2+'<th>';
                                            res2    = res2+'Total';
                                        res2    = res2+'</th>';
                                        res2    = res2+'<th>';
                                            res2    = res2+'Reason';
                                        res2    = res2+'</th>';
                                        res2    = res2+'<th>';
                                            res2    = res2+'PiC';
                                        res2    = res2+'</th>';
                                    res2    = res2+'</tr>';
                                res2    = res2+'</thead>';
                                res2    = res2+'<tbody>';
                                    for (var i=0; i < data.leaves.length; i++){
                                        res2    = res2+'<tr class="selectable">';
                                            res2    = res2+'<td>';
                                                res2    = res2+(i*1+1);
                                            res2    = res2+'</td>';
                                            res2    = res2+'<td>';
                                                res2    = res2+data.leaves[i].Dari+ " to "+data.leaves[i].Sampai;
                                            res2    = res2+'</td>';
                                            res2    = res2+'<td>';
                                                res2    = res2+data.leaves[i].Jml*(-1)+" day(s)";
                                            res2    = res2+'</td>';
                                            res2    = res2+'<td>';
                                                res2    = res2+data.leaves[i].Alasan;
                                            res2    = res2+'</td>';
                                            res2    = res2+'<td>';
                                                res2    = res2+data.leaves[i].Pengganti;
                                            res2    = res2+'</td>';
                                        res2    = res2+'</tr>';
                                    }
                                res2    = res2+'</tbody>';
                            res2    = res2+'</table>';
                            $(".t2").html(res2);

    //                        var tprint = '<div class="control-group">';
    //                            tprint = tprint+'<label>Printing option :</label>';
    //                            tprint = tprint+'<div class="controls">';
    //                            tprint = tprint+'    <input name="pdet" type="radio" value="1">Complete Report';
    //                            tprint = tprint+'    <input name="pdet" type="radio" value="2">Header Only';
    //                            tprint = tprint+'    <input name="pdet" type="radio" value="3">Master Only';
    //                            tprint = tprint+'</div>';
    //                            tprint = tprint+'</div>';
    //                            tprint = tprint+'<button onclick="" class="btn btn-icon btn-success glyphicons print"><i></i>Print</button>';
    //                        $(".t3").html(tprint);
                        }
                        if (data.all == "yes"){
                            $("#selectedid").val("all");
                            $("#selecteddate").val("no");
                            order   = true;
                            var res    = "";
                            res    = "<br><b>Summary of All Employee's Annual Leaves :</b><br><br>";
                            res    = res+'<table class="table table-condensed dynamicTable">';
                                res    = res+'<thead>';
                                    res    = res+'<tr>';
                                        res    = res+'<th>';
                                            res    = res+'Employee ID';
                                        res    = res+'</th>';
                                        res    = res+'<th>';
                                            res    = res+'Name';
                                        res    = res+'</th>';
                                        res    = res+'<th>';
                                            res    = res+'Last Addition';
                                        res    = res+'</th>';
                                        res    = res+'<th>';
                                            res    = res+'Last Leave';
                                        res    = res+'</th>';
                                        res    = res+'<th>';
                                            res    = res+'Untaken';
                                        res    = res+'</th>';
                                    res    = res+'</tr>';
                                res    = res+'</thead>';
                                res    = res+'<tbody>';
                                for (var i=0;i<data.allemp.length;i++){
                                    res    = res+'<tr>';
                                        res    = res+'<td>';
                                            res    = res+data.allemp[i].IDEmployee;
                                        res    = res+'</td>';
                                        res    = res+'<td>';
                                            res    = res+data.allemp[i].FullName;
                                        res    = res+'</td>';
                                        res    = res+'<td>';
                                            res    = res+(data.allemp[i].LastAdd == '0000-00-00' ? "No data" : data.allemp[i].LastAdd);
                                        res    = res+'</td>';
                                        res    = res+'<td>';
                                            res    = res+(data.allemp[i].LastLeave == '0000-00-00' ? "No data" : data.allemp[i].LastLeave);
                                        res    = res+'</td>';
                                        res    = res+'<td>';
                                            res    = res+data.allemp[i].Sisa;
                                        res    = res+'</td>';
                                    res    = res+'</tr>';
                                }
                                res    = res+'</tbody>';
                            res    = res+'</table>';
                            $(".t1").html(res);
                            $(".t1").show({
                                effect  : "highlight",
                                duration: 500
                            });
                        }
                        
                        $('.dynamicTable').dataTable({
                                "sPaginationType": "bootstrap",
                                "bDestroy": true,
                                "bSort" : order,
                                "aaSorting": [[1, "asc"]],
                                "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
                                "oLanguage": {
                                        "sLengthMenu": "_MENU_ records per page"
                                }
                        });                        
            
                    },
                    error   : function (a,b){
                        alert(a.toSource()+"|"+b);
                    }
               });
           }
           function chgtab(t){
                $(".hasilprint").empty();
                if (t == 1){
                    $(".t1").show({});
                    $(".t2").hide({});
                    $(".t3").hide({});
                }
                if (t == 2){
                    $(".t2").show({});
                    $(".t1").hide({});
                    $(".t3").hide({});
                }
                if (t == 3){
                    
                    $(".t3").show({});
                    $(".t2").hide({});
                    $(".t1").hide({});
                }
           }
           function delemp(){
               $("#idemployee").val("");
               $("#nmemployee").val("");
               $("#hdate").val("");
           }
           function print_rpt(){
//               chgtab(3);
                var idemp    = $("#selectedid").val();
                var printrange = $("#printrange").val();
                $(".hasilprint").html("<iframe width='100%' height='1000px' src='<?php echo $base_url."mod_attendance/index.php/rpt19/home/print_data"?>/"+idemp+"/"+printrange+"' ></iframe>");
//               $.ajax({
//                   url      : "<?php echo $base_url."mod_attendance/index.php/rpt19/home/print_data"?>",
//                   data     : "idemp="+idemp+"&printrange="+printrange,
//                   type     : "post",
//                   dataType : "html",
//                   cache    : false,
//                   success  : function (data){
//                       
//                       $(".hasilprint").html(data);
//                   },
//                   error    : function (a,b){
//                       alert(a.responseText+"\n"+b);
//                   }
//               });
           }
           function print_opt(){
                var idemp    = $("#selectedid").val();
                var hdate    = $("#selecteddate").val();
                if (idemp != "all" && idemp != ""){
                    chgtab(3);
                    $.ajax({
                        url      : "<?php echo $base_url."mod_attendance/index.php/rpt19/home/print_opt"?>",
                        data     : "idemp="+idemp+"&hdate="+hdate,
                        type     : "post",
                        dataType : "html",
                        cache    : false,                   
                        success  : function (data){
                            $("#wadahperiod").html(data);
                        },
                        error    : function (a,b){
                            alert(a.responseText+"\n"+b);
                        }
                    });
                }else if(idemp == "all"){
                    chgtab(3)
                    alert("semua");
                }else if (idemp == ""){
                    alert("No data selected!");
                    chgtab(1);
                }
           }
        </script>
    </head>
    <body>
        <div class="widget">
            <div class="widget-head">
                <h4 class="heading">Report of Annual Leave </h4>
            </div>
            <div class="widget-body">
                <div class="row-fluid ">
                    <div class="span12 widget widget-body">
                        <div class="control-group">
                            <label>Employee ID :</label>
                            <div class="controls">
                                <input type="hidden" id='hdate' disabled="">
                                <input type="hidden" id='selectedid' disabled="">
                                <input type="hidden" id='selecteddate' disabled="">
                                <input type="text" id='idemployee' disabled="" readonly="">
                            </div>
                        </div>
                        <div class="control-group">
                            <label>Employee Name* :</label>
                            <div class="controls input-append">
                                <input type="text" id="nmemployee"><a onclick="delemp()" class="btn btn-default glyphicons delete" style='height:18px;border-bottom-right-radius: 3px; border-top-right-radius: 3px;' ><i></i></a>
                            </div>
                            <div>
                                <i style='font-size: 7px;'>* Leave it blank to print all employee's data</i>
                            </div>
                        </div>
                        
                        <div class="control-group">
                            <div class="controls">
                                <button class="btn btn-success btn-icon glyphicons eye_open" onclick="view()"><i></i>View</button>
                            </div>
                        </div>                        
                    </div>
                </div>                
                <div class="tabsbar tabsbar-2 active-fill">
                    <ul class="row-fluid ">
                        <li class="span5 tab1 tabatas glyphicons tags active ">
                            <a onclick="chgtab(1)" data-toggle="tab"><i></i>
                                <b>Employee's Untaken Leave</b>
                                <!--<div class="ribbon-wrapper small ribtab1"><h4 style="color: #be362f; text-shadow: 1px 1px 1px #000;">&ast;</h4></div>-->
                            </a>
                        </li>
                        <li class="span5 tab2 tabatas glyphicons calendar " >
                            <a onclick="chgtab(2)" data-toggle="tab"><i></i> 
                                <b>Employee's Annual Leave</b>
                                <!--<div class="ribbon-wrapper small ribtab2"><h4 style="color: #be362f; text-shadow: 1px 1px 1px #000;">&ast;</h4></div>-->
                            </a>
                        </li>
                        <li class="span2 tab3 tabatas glyphicons print " >
                            <a onclick="print_opt()" data-toggle="tab"><i></i> 
                                <b>Print</b>
                                <!--<div class="ribbon-wrapper small ribtab2"><h4 style="color: #be362f; text-shadow: 1px 1px 1px #000;">&ast;</h4></div>-->
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="row-fluid ">
                    <div class="span12 widget widget-body t1">
                        No data
                    </div>
                    <div class="span12 widget widget-body t2" style="margin-left: 0px; display:none;">
                        No data
                    </div> 
                    <div class="span12 widget widget-body t3" style="margin-left: 0px; display:none;">
                        <div class="row-fluid">
                            <div class="span6">
                                <h5>Printing options : </h5>
                                Select period : 
                                <span id="wadahperiod" class="">
                                    
                                </span>
                                
                            </div>
                        </div>
                        <hr>
                        <div class="row-fluid">
                            <div class="span12 hasilprint">
                                
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </body>
</html>

