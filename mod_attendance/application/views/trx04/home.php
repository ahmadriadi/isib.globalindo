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
            .accept{color: #00CC00;font-weight: bold;}
            .waiting{color: #EC5800;font-weight: bold;}
            .reject{color: #ee1e2d;font-weight: bold;}            
            .transindo{
                font-size: 10px;
            }
            .upper{text-transform:uppercase;}
            a.ui-dialog-titlebar-close { display:none } .label_error_cuti{color : #be362f;}
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
	<!-- Bootstrap -->
	 <!--<script src="<?php echo $base_url; ?>public/bootstrap/js/bootstrap.js"></script> -->
	 <script src="<?php echo $base_url; ?>public/bootstrap/js/popup.js"></script> 
        <!-- Gritter Notifications Plugin -->
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>
	<!-- DataTables Tables Plugin -->
	<script src="<?php echo $base_url; ?>/public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
	<!-- Tables Demo Script -->
	<script src="<?php echo $base_url; ?>public/theme/scripts/demo/tables.js"></script>
        <script>
            $(document).ready(function (){
                load_personal();
            });
            $(".travelform").hide();
            $("#from").datepicker({
                dateFormat : "dd-mm-yy"
            });
            $("#until").datepicker({
                dateFormat : "dd-mm-yy"
            });
            $('body').on('click', function (e) {
                $('[data-toggle="popover"]').each(function () {
                    //the 'is' is for buttons that trigger popups
                    //the 'has' is for icons within a button that triggers a popup
                    if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                        $(this).popover('hide');
                    }
                });
            });
            $("[data-toggle='popover']").popover();
            $("[data-toggle='tooltip']").tooltip();
            var ROOT = {
                'site_url'  : '<?php echo $base_url . '/index.php'; ?>',
                'base_url'  : '<?php echo $base_url; ?>'
            };
            function loading(){
                bootbox.alertloading("<center>Processing. Please wait !!!<br><img src='<?php echo $base_url; ?>public/avatar/76.GIF'></center>");
            }
            function reloadpage(){
                var content = $("#content .innerLR");
                var url = ROOT.base_url + 'mod_attendance/index.php/trx04/home/';
                //alert(url);
                content.load(url);
            }
            function backtohome(){
                window.location.href = "<?php echo $base_url;?>";
            }
            function load_personal(){
                $.ajax({
                    url     : ROOT.base_url+"mod_attendance/index.php/trx04/home/get_personal",
                    data    : "",
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
                        $("#iduser").val(data.IDEmployee);
                        $("#fname").val(data.FullName);
                        $("#depart").val(data.IDDepartement);
                        $("#position").val(data.IDJobPosition);
                    }
                });
            }
            function cancelact(){
                $(".btnedit").prop("disabled",false);
                $(".btndel").prop("disabled",false);
                $(".travelform").hide({
                    easing : "slide",
                    duration :300,
                    complete : function (){
                        $("tr.selected").removeClass("selected");
                        $("tr").addClass("selectable");
                        $(".forhide").show({});
                        $(".travelform").removeClass("span6");
                        $(".alltravels").css("margin-left","0px");
                        $(".alltravels").removeClass("span6");
                        $(".alltravels").addClass("span12");
                        
                    }
                });
            }
            function openform(){
                $(".btnedit").prop("disabled",true);
                $(".btndel").prop("disabled",true);
                $("#from").val("");
                $("#until").val("");
                $("#note").val("");                
                $(".forhide").hide({});
                $(".alltravels").removeAttr("style");
                $(".alltravels").removeClass("span12");
                $(".alltravels").addClass("span6");
                $(".travelform").addClass("span6");
                $(".travelform").show({
                    easing  : "slide",
                    duration: 300
                });
            }
            function addtravel(){
                $("#proses").val("save_travel");
                openform();
            }
            function edittravel(idtravel){
                $("tr").removeClass("selected");
                $("tr.trnya"+idtravel).removeClass("selectable");
                $("tr.trnya"+idtravel).addClass("selected");
                $("#proses").val("update_travel");
                $("#idtravel").val(idtravel);
                $.ajax({
                    url     : ROOT.base_url+"mod_attendance/index.php/trx04/home/edit_travel",
                    data    : "idtravel="+idtravel,
                    type    : "post",
                    dataType: "json",
                    cacehe  : false,
                    success : function (data){
                        openform();
                        var f = data.OfficialTravelDate;
                        var f = f.split(" ");
                        var ftgl = f[0].split("-");
                        $("#from").val(ftgl[2]+"-"+ftgl[1]+"-"+ftgl[0]);
                        var u = data.UntilDate;
                        var u = u.split(" ");
                        var utgl = u[0].split("-");
                        $("#until").val(utgl[2]+"-"+utgl[1]+"-"+utgl[0]);
                        $("#vehicleno").val(data.VehicleNo);
                        $("#note").val(data.Note);
                       
                    },
                    error   : function (){
                        bootbox.alert("An error occured while getting data!");
                    }
                });
                
            }
            function save_travel(){
                var proses  = $("#proses").val();
                var idtravel  = $("#idtravel").val();
                var vehicle = $("#vehicleno").val();
                vehicle     = vehicle.toUpperCase();
                var from    = $("#from").val();
                var until   = $("#until").val();
                var note    = $("#note").val();
                if (proses == "save_travel"){
                    var text = "Submitted";
                }
                else{
                    var text = "Updated";
                }
//                alert("idtravel="+idtravel+"&nextid="+nextid+"&from="+from+"&until="+until+"&note="+note+"&vehicle="+vehicle);
                loading();
                $.ajax({
                    url     : ROOT.base_url+"mod_attendance/index.php/trx04/home/"+proses,
                    data    : "idtravel="+idtravel+"&from="+from+"&until="+until+"&note="+note+"&vehicle="+vehicle,
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
//                        alert(data);
                        reloadpage();
                        bootbox.alert("Data Submitted!",function (){
                            bootbox.hideAll();                            
                            $.gritter.add({
                                title: 'Official Travel Successfully '+text+'!',
                                text: "Please recheck if there's  something missed"
                            });
                        });
                    }
                });
            }
            function deltravel(idtravel){
                bootbox.confirm("You are going to delete this travel data. Continue?", function (r){
                    if (r == true){
                        loading();
                        $.ajax({
                            url     : ROOT.base_url+"mod_attendance/index.php/trx04/home/delete_travel",
                            data    : "idtravel="+idtravel,
                            type    : "post",
                            dataType: "json",
                            cache   : false,
                            success : function (data){
                                bootbox.alert("Data Deleted!",function (){
                                    bootbox.hideAll();
                                    reloadpage();
                                    $.gritter.add({
                                        title: 'Official Travel Successfully Deleted!',
                                        text: "You have deleted your official travel data"
                                    });
                                });
                            }                            
                        });
                    }else{
                        $.gritter.add({
                            title: 'Delete Canceled!',
                            text: "You canceled to delete data"
                        });                        
                    }
                });
            }
            function printtravel(idtravel){
                $("head").append("<style id='styletbh1'>.modal{width: 90%;height: 80%;margin-left: -45%;}</style>");
                $("head").append("<style id='styletbh2'>.modal-body{position: relative;overflow-y: auto;height: 87%;max-height: 87%;padding: 0px;}</style>");
                bootbox.dialog("<iframe width='100%' height='100%' src='"+ROOT.base_url+"mod_attendance/index.php/trx04/home/print_travel/"+idtravel+"'>"+"</iframe>",{
                    label   : "Close",
                    class   : "btn-danger",
                    callback: function (){
                        $("#styletbh1").remove();
                        $("#styletbh2").remove();
                    }
                });
            }
        </script>
    </head>
    <body>
        <div class="widget">
            <div class="widget-head">
                <div class="row-fluid">
                    <div class="span6">
                        <h4 class="heading">Official Travel</h4>                        
                    </div>
                    <div class="span6" style="text-align: right;">
                        <button onclick="reloadpage()" class="btn btn-small btn-default"><i class="icon-refresh"></i></button>
                        <button onclick="backtohome()" class="btn btn-success btn-small btn-icon glyphicons home"><i></i>Back to Home</button>
                    </div>
                </div>
            </div>
            <div class="widget-body">
                <div class="row-fluid">
                    <div class=" widget widget-body travelform">
                        <div class="row-fluid">
                            <div class="span6">
                                <input type="hidden" id="idtravel">
                                <input type="hidden" id="proses">
                                <h4>Official Travel Form</h4>
                            </div>
                            <div class="span6" style="text-align: right">
                                <button onclick="reloadpage()" class="btn btn-icon btn-small btn-danger glyphicons circle_minus"><i></i>Cancel</button>
                            </div>                           
                        </div>
                        <hr class="separator">
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label" for="fname"><b>Full Name</b> / <i class="transindo">Nama Lengkap</i> </label>
                                    <div class="controls">
                                        <input type="text" class="span12" id="fname">
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label" for="iduser"><b>ID Employee</b> / <i class="transindo">NIP</i> </label>
                                    <div class="controls">
                                        <input type="text" class="span12" id="iduser">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--============================================================-->
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label" for="depart"><b>Department</b> / <i class="transindo">Departemen</i> </label>
                                    <div class="controls">
                                        <input type="text" class="span12" id="depart">
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label" for="position"><b>Position</b> / <i class="transindo">Jabatan</i> </label>
                                    <div class="controls">
                                        <input type="text" class="span12" id="position">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--============================================================-->
                        <div class="row-fluid">
                            <div class="span12">
                                <b>Travel</b> / <i class="transindo">Travel</i>
                                <div class="row-fluid">
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label"><b>From Date</b> / <i class="transindo">Dari Tanggal</i></label>
                                            <div class=" controls">
                                                <input type="text" id="from">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <label class="control-label"><b>Until Date</b> / <i class="transindo">Sampai Tanggal</i></label>
                                            <div class=" controls">
                                                <input type="text" id="until">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label"><b>Vehicle No</b> / <i class="transindo">Nomor Kendaraan</i></label>
                                    <div class="controls">
                                        <input type="text" class="span12 upper" id="vehicleno">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="control-group">
                                    <label class="control-label"><b>Description</b> / <i class="transindo">Deskripsi Travel</i></label>
                                    <div class="controls">
                                        <textarea id="note" rows="3" class="span12"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="separator">
                        <div class="row-fluid">
                            <div class="span12" style="text-align : center;">
                                <button onclick="save_travel()" class="btn btn-success btn-icon btn-small glyphicons circle_ok"><i></i>Submit</button>
                                <button onclick="reloadpage()" class="btn btn-danger btn-icon btn-small glyphicons circle_minus"><i></i>Cancel</button>
                            </div>
                        </div>
                    </div>
                    <div class="span12 widget widget-body alltravels" style="margin-left : 0px;">
                        <div class="row-fluid">
                            <div class="span6">
                                <h4>Submitted Official Travel</h4>
                            </div>
                            <div class="span6" style="text-align: right;">
                                <button onclick="addtravel()" class="btn btn-primary btn-small btn-icon glyphicons circle_plus"><i></i>New</button>
                            </div>
                        </div>
                        <hr class="separator">
                        <div class="row-fluid">
                            <div class="span12">
                                <table width="100%" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable ofttable">
                                    <thead class="btn-primary">
                                        <tr>
                                            <th rowspan="2" class="forhide">Submit Date</th>
                                            <th colspan="2"><center>Travel Date</center></th>
                                            <th rowspan="2" class="forhide"><center>Vehicle No</center></th>
                                            <th rowspan="2">Travel Description</th>
                                            <th rowspan="2">Status</th>
                                            <th rowspan="2"><center>Action</center></th>
                                        </tr>
                                        <tr>
                                            <th>From</th>
                                            <th>Until</th>
                                        </tr>
                                    </thead>
                                    <tbody id="alltravel">
                                        <?php 
                                        foreach ($travels->result() as $t){
                                            $statuscon = array(
                                                "0" => "<b class='waiting'>Waiting</b>",
                                                "1" => "<b class='accept'>Accepted</b>",
                                                "2" => "
                                                    <span class='reject' data-toggle='popover' data-title='Reason of rejection' data-content='$t->RejectReason' data-placement='left'>
                                                    <b data-toggle='tooltip' data-original-title='click to view the reason' data-placement='top'>Rejected</b>
                                                    </span>
                                                    ",
                                            );
                                            $t->ConfirmFlag == "1" ? $btnnya = "disabled" : $btnnya = "";
                                            echo "<tr class='selectable trnya$t->ID'>";
                                            echo "<td class='forhide'>$t->AddedDate</td>";                                            
                                            echo "<td>$t->OfficialTravelDate</td>";                                            
                                            echo "<td>$t->UntilDate</td>";                                            
                                            echo "<td class='forhide'><center>$t->VehicleNo</center></td>";                                            
                                            echo "<td>$t->Note</td>"; 
                                            echo "<td>".$statuscon[$t->ConfirmFlag]."</td>";
                                            echo "<td><center>";
                                            echo "<button class='btn btn-success btn-mini btnprint' onclick='printtravel(\"$t->ID\")' ><i class='icon-print'></i></button>";
                                            echo "<button $btnnya class='btn btn-warning btn-mini btnedit' onclick='edittravel(\"$t->ID\")' ><i class='icon-pencil'></i></button>";
                                            echo "<button $btnnya class='btn btn-danger btn-mini btndel' onclick='deltravel(\"$t->ID\")' ><i class='icon-trash'></i></button>";
                                            echo"</center></td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>


<script>
    $(function()
{
        /* DataTables */
        if ($('.ofttable').size() > 0)
        {
                $('.ofttable').dataTable({
                        "sPaginationType": "bootstrap",
                        "bDestroy": true,
                        "aaSorting": [[0, "desc"]],
                        "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
                        "oLanguage": {
                                "sLengthMenu": "_MENU_ records per page"
                        }
                });
        }
});
</script>
