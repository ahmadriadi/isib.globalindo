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
<!--        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>-->
        <!--timepicker-->
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/timepicker/timepicker.css" rel="stylesheet" />
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/timepicker/timepicker.js"></script>
	<!-- Bootstrap -->
	<!--<script src="<?php echo $base_url; ?>public/bootstrap/js/bootstrap.js"></script>--> 
	<script src="<?php echo $base_url; ?>public/bootstrap/js/popup.js"></script> 

        <!-- Gritter Notifications Plugin -->
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>
	<!-- DataTables Tables Plugin -->
	<script src="<?php echo $base_url; ?>/public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
	<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/api/fnStandingRedraw.js"></script>

	<!-- Tables Demo Script -->
	<script src="<?php echo $base_url; ?>public/theme/scripts/demo/tables.js"></script>
        <script>
            //dSiFe
//            dShhl
            $(document).ready(function (){
                $("#fname").keypress(false);
                $("#iduser").keypress(false);
                $("#depart").keypress(false);
                $("#position").keypress(false);
                $("#outtime").keydown(false);
                $("#intime").keydown(false);
                load_personal();
                
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
            
//            $("#outtime").datetimepicker({dateFormat:"dd-mm-yy", timeFormat : "hh:mm:ss"});
//            $("#intime").datetimepicker({dateFormat:"dd-mm-yy", timeFormat : "hh:mm:ss"});
            $("#outtime").datepicker({
                dateFormat  :"dd-mm-yy",
                minDate     :"0D",		
	        onClose:function(dateText, inst){
                   var fromdate = $("#outtime").val();
                   $("#intime").val(fromdate);

               }	
            });

	
            $("#intime").datepicker({
                dateFormat:"dd-mm-yy",
                minDate     :"0D"
            });
            var ROOT = {
                'site_url'  : '<?php echo $base_url . '/index.php'; ?>',
                'base_url'  : '<?php echo $base_url; ?>'
            };
            function loading(){
                bootbox.alertloading("<center>Processing. Please wait !!!<br><img src='<?php echo $base_url; ?>public/avatar/76.GIF'></center>");
            }
            function reloadpage(){
                var content = $("#content .innerLR");
                var url = ROOT.base_url + 'mod_attendance/index.php/trx02/home/';
                //alert(url);
                content.load(url);
            }
            function backtohome(){
                window.location.href = "<?php echo $base_url;?>";
            }
            function load_personal(){
                $.ajax({
                    url     : ROOT.base_url+"mod_attendance/index.php/trx02/home/get_personal",
                    data    : "",
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
                        $("#fname").val(data.FullName);
                        $("#iduser").val(data.IDEmployee);
                        $("#depart").val(data.DescDepart);
                        $("#position").val(data.IDJobPosition);
                    }
                });
            }
            function openform(){
                $(".editbtn").prop("disabled",true);
                $(".deletebtn").prop("disabled",true);
                $(".alllvpmt").removeClass("span11");
                $(".alllvpmt").addClass("span6");
                $(".alllvpmt").removeAttr("style");
                $('.addlvpmt').hide({});
                $('.colout').hide({});
                $('.coltot').hide({});
                $('.colin').hide({});
                $('.colvhcl').hide({});
                $('.colpart').hide({});
            }
            function newlvpmt(){
//                $("#outtimejam").val("  :  :00");
                $(".printbtn").prop("disabled",true);
                $("#proses").val("save_lpermit");
                openform();
                $(".newlvpmt").addClass("span6");
                $(".newlvpmt").show({
                    easing : "slide",
                    duration: 300
                });
                $("div.otherpart").empty();
                $("#outtime").val("");
                $("#intime").val("");
                $("#vehicle").val("");
                $("#note").val("");
                $("input[name='necessity']").removeAttr("checked");
                var jml = $("input[name='partids[]']").length;
//                alert(jml);
                if (jml == 0){
                    $("div.otherpart").append("<span id='ketparts'>Click \"add\" to add other participant!</span>");
                }
            }
            function cancelact(){
                $("tr.selected").addClass("selectable");
                $("tr.selected").removeClass("selected");
                $(".newlvpmt").removeClass("span6");
                $(".newlvpmt").hide({
                    easing : "slide",
                    duration: 300,
                    complete: function (){
                        $(".alllvpmt").removeClass("span6");
                        $(".alllvpmt").addClass("span12");
                        $(".alllvpmt").css("margin-left","0px");
                        $('.addlvpmt').show({});
                        $('.colout').show({});
                        $('.colin').show({});
                        $('.colvhcl').show({});
                        $('.colpart').show({});
                        $('.coltot').show({});
                        $(".editbtn").prop("disabled",false);
                        $(".deletebtn").prop("disabled",false);
                        $(".printbtn").prop("disabled",false);
                    }
                });
                $(".partsm").empty();
                $('div.parts').hide({
                    easing : "blind",
                    duration : 300
                });
            }
            function save_lpermit(){
                var proses      = $("#proses").val();
                var idlpermit   = $("#idlpermit").val();
                var outtime     = $("#outtime").val()+" "+$("#jamout").val();
                var intime      = $("#intime").val()+" "+$("#jamin").val();
                var necessity   = $("input[name='necessity']:checked").val();
                var vehicleno   = $("#vehicle").val();
                var note        = $("#note").val();
                var other       = new Array();
                $("input[name='partids[]']").each(function (){
                    other.push($(this).val());
                });
//                alert(necessity);
                other.join(',');
                if (note != "" && necessity != null && $("#outtime").val() != "" && $("#intime").val() != ""){
                    loading();
                    $.ajax({
                        url     : ROOT.base_url+"mod_attendance/index.php/trx02/home/"+proses,
                        data    : "idlpermit="+idlpermit+"&outtime="+outtime+"&intime="+intime+"&necessity="+necessity+"&vehicleno="+vehicleno+"&note="+note+"&other="+other,
                        type    : "post",
                        dataType: "json",
                        cache   : false,
                        success : function (data){
                            bootbox.alert("Leave Permit Submitted!", function (){
                                $(".printbtn").prop("disabled",false);
                                bootbox.hideAll();
                                reloadpage();
                                if (proses != "save_lpermit"){
                                    var text = "Updated";
                                }
                                if (proses == "save_lpermit"){
                                    var text = "Submitted";
                                }
                                $.gritter.add({
                                    title: 'Leave Permit Successfully '+text+'!',
                                    text: "Please recheck if there's  something missed"
                                }); 
                            });
                        },
                        error: function (a,b){
                            alert(a.toSource()+"\n"+b);
                        }
                    });                    
                }
                else{
                    if (note == ""){
                        $.gritter.add({
                            title: 'Description field must be filled',
                            text: "Please fill out the description!"
                        });                        
                    }
                    if (necessity == null){
                        $.gritter.add({
                            title: "Necessity choice must have selected one",
                            text: 'Please choose your necessity!'
                        });                        
                    }
                    if ($("#outtime").val() == ""){
                        $.gritter.add({
                            title: "Out date must be specified",
                            text: 'Please specify the date you (will) go out!'
                        });
                    }
                    if ($("#intime").val() == ""){
                        $.gritter.add({
                            title: "Return date must be specified",
                            text: 'Please specify the date you (will) return!'
                        });
                    }
                }
            }
//            function addpart(u,val1="",val2=""){
//                var i = u+1;
////                alert(i);
//                $("#ketparts").hide();
//                var a   = '<div class="row-fluid other'+i+'">';
//                var a   = a+'<div class="span4"><input name="partids[]" disabled type="text" class="span12 idke'+i+'" value="'+val1+'"></div>';
//                var a   = a+'<div class="span8"><input name="" onfocus="otherpart('+i+')" type="text" class="span11 nmke'+i+'" value="'+val2+'">';
//                var a   = a+'<button onclick="delpart('+i+')" class="btn btn-icon btn-mini btn-danger">x</button>';
//                var a   = a+'</div>';
//                var a   = a+'</div>';
//                $("div.otherpart").append(a);
//                $("button.addpart").replaceWith('<button onclick="addpart('+i+')" class="addpart btn btn-icon btn-default btn-default glyphicons circle_plus btn-small"><i></i>add</button>');
//            }
            function delpart(val){
                $("div.other"+val).remove();
                var jml = $("input[name='partids[]']").length;
//                alert(jml);
                if (jml == 0){
                    $("div.otherpart").append("<span id='ketparts'>Click \"add\" to add other participant!</span>");
                }     
            }
            function otherpart(val){
                var other = new Array();
                $("input[name='partids[]']").each(function (){
                    other.push($(this).val());
                });
                other.join(',');
//                alert(other);
                $.ajax({
                    url     : ROOT.base_url+"mod_attendance/index.php/trx02/home/get_other",
                    data    : "userids="+other,
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
                        $("input.nmke"+val).autocomplete({
                            source : data,
                            select : function (event,ui){
                                $("input.idke"+val).val(ui.item.iduser);
                            }
                        });
                        $("input.nmke"+val).keypress(function (e){
                            var s = String.fromCharCode(e.which);
                            var key = e.keyCode || e.charCode();
                            if (key == 8 ){
                                $("input.idke"+val).val("");
                            }else if (key == 46){
                                return false;
                            }
                        });
                    }
                });
            }
            function editlvpmt(idlvpmt){
				
                $("tr.trid"+idlvpmt).removeClass("selectable");
                $("tr.trid"+idlvpmt).addClass("selected");
                $("#idlpermit").val(idlvpmt);
                $("#proses").val("update_lpermit");
                $("div.otherpart").empty();
                $("#outtime").val("");
                $("#jamout").val("");
                $("#jamin").val("");
                $("#intime").val("");
                $("#vehicle").val("");
                $("#note").val("");
                $("input[name='necessity']").removeAttr("checked");
                $.ajax({
                    url     : ROOT.base_url+"mod_attendance/index.php/trx02/home/edit_lpermit",
                    data    : "idlpermit="+idlvpmt,
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
//                        alert(data.toSource());
                        if (data.status == "oke"){
                            $(".printbtn").prop("disabled",true);
                            var o = data.data.OutDate;
                            var o = o.split(" ");
                            var otgl = o[0].split("-");
                            $("#outtime").val(otgl[2]+"-"+otgl[1]+"-"+otgl[0]);
                            var ojam = o[1].split(":");
                            $("#jamout").val(ojam[0]+""+ojam[1]);
                            var i = data.data.InDate;
                            var i = i.split(" ");
                            var itgl = i[0].split("-");
                            $("#intime").val(itgl[2]+"-"+itgl[1]+"-"+itgl[0]);
                            var ijam = i[1].split(":");
                            $("#jamin").val(ijam[0]+""+ijam[1]);
                            $("#vehicle").val(data.data.VehicleNo);
                            var nec = data.data.Necessity;
                            $("input[name='necessity'][value='"+nec+"']").prop("checked",true);
                            $("#note").val(data.data.Note);
//                            var parts = data.part;
//                            var jml   = parts.length;
//                            if (jml != 0){
//                                for(var i=0;i<jml;i++){
//                                    var idemp = parts[i].IDEmployee;
//                                    var nmemp = parts[i].FullName;
//                                    addpart(i,idemp,nmemp);
//                                }
//                            }
//                            else if (jml == 0){
//                                $("div.otherpart").append("<span id='ketparts'>Click \"add\" to add other participant!</span>");
//                            }
                            openform();
                            $(".newlvpmt").addClass("span6");
                            $(".newlvpmt").show({
                                easing : "slide",
                                duration: 300
                            });
                        }
                        else{
                            bootbox.alert("An error occured while getting data!");
                        }
                    },
                    error	: function (a){
						alert(a.responseText);
					}
                });
            }
            function deletelvpmt(idlvpmt){
                bootbox.confirm("You are going to delete you Leave Permit Request. Continue?", function (r){
                    if (r == true){
                        loading();
                        $.ajax({
                            url     : ROOT.base_url+"mod_attendance/index.php/trx02/home/delete_lpermit",
                            data    : "idlpermit="+idlvpmt,
                            type    : "post",
                            dataType: "json",
                            cache   : false,
                            success : function(data){
                                if (data.status == "oke"){
                                    bootbox.alert("Data Deleted!", function (){
                                        bootbox.hideAll();
                                        $.gritter.add({
                                            title: 'Leave Permit Deleted!',
                                            text: "Your leave permit successfully deleted"
                                        });
                                        reloadpage();
                                    });
                                }
                            }
                        });
                    }
                    else{
                        $.gritter.add({
                            title: 'Delete Canceled!',
                            text: "You canceled to delete the data"
                        }); 
                    }
                })
            }
            function printlvpmt(idlvpmt){
//                $.ajax({
//                    url     : ROOT.base_url+"mod_attendance/index.php/trx02/home/printlpermit",
//                    data    : "",
//                    type    : "post",
//                    success : function (data){
//
//                    }
//                    
//                });
                $("head").append("<style id='styletbh1'>.modal{width: 90%;height: 80%;margin-left: -45%;}</style>");
                $("head").append("<style id='styletbh2'>.modal-body{position: relative;overflow-y: auto;height: 87%;max-height: 87%;padding: 0px;}</style>");
                bootbox.dialog("<iframe width='100%' height='100%' src='"+ROOT.base_url+"mod_attendance/index.php/trx02/home/printlpermit/"+idlvpmt+"'>"+"</iframe>",{
                    label   : "Close",
                    class   : "btn-danger",
                    callback: function (){
                        $("#styletbh1").remove();
                        $("#styletbh2").remove();
                    }
                });

//                $(".bootbox").resizable();
            }

            function vparts(idlvpmt){
                $("tr.trid"+idlvpmt).removeClass("selectable");
                $("tr.trid"+idlvpmt).addClass("selected");
                openform();
                $('div.alllvpmt').css("margin-left","0px");
                $('div.parts').show({
                    easing : "blind",
                    duration : 300
                });
                $.ajax({
                    url     : ROOT.base_url+"mod_attendance/index.php/trx02/home/get_parts",
                    data    : "idlpermit="+idlvpmt,
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
//                        alert(data.toSource());
                        var res = '';
                        for(var i=0;i<data.length;i++){
                            res = res+"<tr>";
                            res = res+"<td>"+data[i].IDEmployee+"</td>";
                            res = res+"<td>"+data[i].FullName+"</td>";
                            res = res+"<td>"+data[i].IDJobPosition+"</td>";
                            res = res+"<td>"+data[i].IDDepartement+"</td>";
                            res = res+"</tr>";
                        }
                        $(".partsm").append(res);
                    }
                });
            }
//            $("#outtimejam").val("__:__");
            function formatjam(val,idinputan){
//                val = val.split(":");
                //alert(val[0].split("").length);
                if (val.split("").length == 2){
                    $("#"+idinputan).val(val+":");
                }
            }

	   $(function()
                {
                        /* DataTables */
                        if ($('.LeavePermitTable').size() > 0)
                        {
                                $('.LeavePermitTable').dataTable({
                                        "sPaginationType": "bootstrap",
                                        "bDestroy": true,
                                        "aaSorting": [[1, "desc"]],
                                        "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
                                        "oLanguage": {
                                                "sLengthMenu": "_MENU_ records per page"
                                        }
                                });
                        }
                });		
        </script>
    </head>
    <body>
        <div class="widget">
            <div class="widget-head">
                <div class="row-fluid">
                    <div class="span6">
                        <input type="hidden" value="" id="proses">
                        <input type="hidden" value="" id="idlpermit">
                        <h4 class="heading">Leave Permit </h4>                        
                    </div>
                    <div class="span6" style="text-align: right">
                        <button onclick="reloadpage()" class="btn btn-small btn-default"><i class="icon-refresh"></i></button>
                        <button onclick="backtohome()" class="btn btn-small btn-success btn-icon glyphicons home"><i></i>Back to Home</button>
                    </div>
                </div>
            </div>
            <div class="widget-body contnr">
                <div class="row-fluid">
                    <div class="widget widget-body newlvpmt" style="display:none">
                        <div class="row-fluid">
                            <div class="span6">
                                <h4>Leave Permit Form</h4>
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
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label" for="outtime"><b>Out Date/Time</b> / <i class="transindo">Tgl/Jam Keluar</i></label>
                                    <div class="controls">
                                        <input type="text" class="span5" id="outtime">
                                        <input type="text" class="span3" id="jamout" maxlength="4">
                                        <!--<input type="text" oninput="formatjam(this.value,this.id)" class="span4" id="outtimejam">-->
                                    </div>
                                </div>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label class="control-label" for="outtime"><b>Return Date/Time</b> / <i class="transindo">Tgl/Jam Masuk</i></label>
                                    <div class="controls">
                                        <input type="text" class="span5" id="intime">
                                        <input type="text" class="span3" id="jamin" maxlength="4">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--=========================================================-->
                        <div class="row-fluid">                        
                            <div class="span12">
                                <div class="control-group">
                                    <label class="control-label" for="vehicle"><b>Vehicle Number</b> / <i class="transindo">Nomor Kendaraan</i></label>
                                    <div class="controls">
                                        <input type="text" class="span3" id="vehicle">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--=========================================================-->
                        <!--=========================================================-->
                        <div class="row-fluid">                        
                            <div class="span12">
                                <div class="control-group">
                                    <label class="control-label" for="necessity"><b>Necessity</b> / <i class="transindo">Keperluan</i></label>
                                    <div class="controls">
                                        <label style="display: inline-block;">
											<input type="radio" class="span6" value="1" name="necessity"><b>Personal</b> / <i class="transindo">Pribadi</i>
										</label>
                                        <label style="display: inline-block;">
											<input type="radio" class="span6" value="2" name="necessity"><b>Official</b> / <i class="transindo">Kantor / Pekerjaan</i>
										</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--=========================================================-->
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="control-group">
                                    <label class="control-label" for="note"><b>Objective Description</b> / <i class="transindo">Deskripsi Tujuan</i></label>
                                    <div class="controls">
                                        <textarea class="span12" rows="3" id="note"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--=========================================================-->
                        <!--dinonaktifkan sementara-->
<!--                        <div class="row-fluid">
                            <label class="control-label" ><b>Other Participant</b> / <i class="transindo">Karyawan Lain</i></label>
                            <div class="span12 otherpart">                                
                                <span id="ketparts">Click add to add other participant!</span>
                            </div>
                            <button onclick="addpart(0)" class="addpart btn btn-icon btn-default btn-default glyphicons circle_plus btn-small"><i></i>add</button>
                        </div>-->
                        <!--------------------------->
                        <hr class="separator">
                        <div class="row-fluid">
                            <div class="span12" style="text-align: center;">
                                <button onclick="save_lpermit()" class="btn btn-icon btn-small btn-success glyphicons circle_ok"><i></i>Save</button>
                                <button onclick="reloadpage()" class="btn btn-icon btn-small btn-danger glyphicons circle_minus"><i></i>Cancel</button>
                            </div>
                        </div>
                    </div>
                    <!----------------------------------------------------------------------------------------------------------------------------------------------------->
                    <div class="span12 widget widget-body alllvpmt" style="margin-left: 0px;">
                        <div class="row-fluid">
                            <div class="span6">
                                <h4>Submitted Leave Permit</h4>
                            </div>
                            <div class="span6" style="text-align: right;">
                                <button onclick="newlvpmt()" class="btn btn-small btn-icon btn-primary glyphicons circle_plus addlvpmt"><i></i>New</button>
                            </div>
                        </div>
                        <hr class="separator">
                        <div class="row-fluid">

                            <table width="100%" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable LeavePermitTable">
                                <thead class="btn-primary">
                                    <tr>
                                        <th>Submit Date</th>
                                        <th class='colout'>Out Time</th>
                                        <th class='colin'>In Time</th>
                                        <!--<th class='coltot'>Total Time</th>-->
                                        <!--<th>Necessity</th>-->
                                        <th>Description</th>
                                        <!--<th class='colvhcl'>Vehicle No</th>-->
                                        <!--<th class='colpart'>Participant</th>-->
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="alllvpmt">
                                    <?php 
                                    foreach($all->result() as $l){
                                        $where      = array("ID" => $l->ID);
//                                        $jmlparts   = $this->lpt->get_participant($where)->num_rows();
//                                        $jmlparts == "0" ? $dis = "disabled" : $dis = "";
                                        $l->Necessity == "1" ? $n = "Personal" : $n =  "Official";
                                        $l->ConfirmFlag == "1" ? $btnnya = "disabled" : $btnnya = "";
                                        $statuscon = array(
                                            "0" => "<b class='waiting'>Waiting</b>",
                                            "1" => "<b class='accept'>Accepted</b>",
                                            "2" => "
                                                <span class='reject' data-toggle='popover' data-title='Reason of rejection' data-content='$l->RejectReason' data-placement='left'>
                                                <b data-toggle='tooltip' data-original-title='click to view the reason' data-placement='top' >Rejected</b>
                                                </span>
                                                ",
                                        );
                                        echo "<tr class='selectable trid$l->ID'>";
                                        echo "<td class='colsbt'>".date("Y-m-d", strtotime($l->LeavePermitDate))."</td>";
                                        echo "<td class='colout'>$l->OutDate</td>";
                                        echo "<td class='colin'>$l->InDate</td>";
//                                        echo "<td class='coltot'>$l->IMKHour hour(s)</td>";
//                                        echo "<td>$n</td>";
                                        if (substr($l->Note,16,18) == ""){
                                            $des    = $l->Note;
                                            $titik  = "";                                            
                                        }else{
                                            $des    = substr($l->Note,0,12);
                                            $titik  = "...";                                            
                                        }
                                        echo "<td >
                                            <span data-toggle='tooltip' data-original-title='click to view full description' data-placement='top'>
                                            <u>$n</u>:<span data-toggle='popover' data-title='Description' data-content='$l->Note' data-placement='left'>".$des.$titik."</span>
                                            </span>    
                                            </td>";
//                                        echo "<td class='colvhcl' >$l->VehicleNo</td>";
//                                        echo "<td class='colpart' ><button>$jmlparts</button><button onclick='vparts(\"$l->ID\")' $dis>view</button></td>";
                                        echo "<td>".$statuscon[$l->ConfirmFlag]."</td>";
                                        echo "<td><center>";
                                        echo "<button onclick='printlvpmt(\"$l->ID\")' class='btn btn-mini btn-success printbtn'><i class='icon-print'></i></button>";
                                        echo "<button $btnnya onclick='editlvpmt(\"$l->ID\")' class='btn btn-mini btn-warning editbtn'><i class='icon-pencil'></i></button>";
                                        echo "<button $btnnya onclick='deletelvpmt(\"$l->ID\")' class='btn btn-mini btn-danger deletebtn'><i class='icon-trash'></i></button>";
                                        echo "</center></td>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="span6 widget widget-body parts" style="display:none;">
                        <div class="row-fluid">
                            <div class="span6"><h4>Participant</h4></div>
                            <div class="span6" style="text-align:right;"><button onclick="cancelact()" class="btn btn-icon btn-small btn-danger glyphicons circle_minus"><i></i>Cancel</button></div>
                        </div>
                        <hr class="separator">
                        <div class="row-fluid">
                            <table width="100%" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable ">
                                <thead class="btn-primary">
                                <th>IDEmployee</th>
                                <th>Full Name</th>
                                <th>Position</th>
                                <th>Department</th>
                                </thead>
                                <tbody class="partsm">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

