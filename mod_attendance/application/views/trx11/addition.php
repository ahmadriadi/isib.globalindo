
<script>
            $(document).ready(function (){
                $("#selectedid").val('');
	// collapsible widgets
	$('.widget[data-toggle="collapse-widget"] .widget-body')
		.on('show', function(){
			$(this).parents('.widget:first').attr('data-collapse-closed', "false");
		})
		.on('shown', function(){
			setTimeout(function(){ $(window).resize(); }, 500);
		})
		.on('hidden', function(){
			$(this).parents('.widget:first').attr('data-collapse-closed', "true");
		});
	
	$('.widget[data-toggle="collapse-widget"]').each(function()
	{
		// append toggle button
		$(this).find('.widget-head').append('<span class="collapse-toggle"></span>');
		
		// make the widget body collapsible
		$(this).find('.widget-body').addClass('collapse');
		
		// verify if the widget should be opened
		if ($(this).attr('data-collapse-closed') !== "true")
			$(this).find('.widget-body').addClass('in');
		
		// bind the toggle button
		$(this).find('.collapse-toggle').on('click', function(){
			$(this).parents('.widget:first').find('.widget-body').collapse('toggle');
		});
	});                
                get_todayadd('<?php echo date('m')?>');
                quick_note();
                $("#formstatus").val("close");
                $("#masdate").datepicker({
                    dateFormat  : "dd-mm-yy"
                });
                var emps = <?php echo json_encode($employee);?>;
                $('#nmemp').autocomplete({
                    source  : emps,
                    select  : function (e, ui){
                        $('#idemp').val(ui.item.idemp);
                    }
                });
                $('#form').hide();
		$('.dynamicTable').dataTable({
                    "aaSorting": [[ 0, "desc" ]],
                    "sPaginationType": "bootstrap",
                    "bDestroy": true,
                    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
                    "oLanguage": {
                        "sLengthMenu": "_MENU_ records per page"
                    },
//                    "aoColumnDefs"  :[
//                        {'bSortable' : false, 'aTargets' : [0]}
//                    ]
		});
            });
            function select_row(id){
                $("#selectedid").val(id);
                $("tr").removeClass('selected');
                $("tr[idrecord='"+id+"']").addClass('selected');
            }
            function open_form(){
                $("#formstatus").val("open");
                $("#maintabel").hide({
                    effect  : "clip",
                    complete : function (){
                        $('#form').show({
                            effect  : "clip",
                        });
                        $("#nmemp").focus();
                    }
                });
            }
            function reset_form(){
                $("#idemp").val('');
                $("#nmemp").val('');
                $("#jml").val('');
                $("#note").find("option[idopt='0']").prop("selected",true);
                $("#masdate").val('<?php echo date('d-m-Y');?>');
            }
            function cancel_act(){
                $("#formstatus").val("close");
                $('#form').hide({
                    effect  : "clip",
                    complete : function (){
                        $('#maintabel').show({
                            effect  : "clip",
                        });
                    }
                });
            }
            function save_this(){
                var proses  = $("#proses").val();
                var idemp   = $("#idemp").val();
                var nmemp   = $("#nmemp").val();
                var jml     = $("#jml").val();
                var note    = $("#note").val();
                var idmas   = $("#selectedid").val();
                var masdate = $("#masdate").val();
                
                if (idemp != '' && jml != '' && note != '' && masdate != ''){
                    loading();
                    $.ajax({
                        url : "<?php echo $base_url?>mod_attendance/index.php/trx11/home/"+proses,
                        data: "idemp="+idemp+"&jml="+jml+"&note="+note+"&idmas="+idmas+"&masdate="+masdate+"&nmemp="+nmemp,
                        type: "post",
                        dataType    : "json",
                        cache       : false,
                        success     : function (data){
                            if (data.status == "oke"){                            
                                if (data.proses == 'edit'){
                                    $("tr[idrecord='"+data.newrecord.idmas+"']").find("td.cidemp").text(data.newrecord.idemp);
                                    $("tr[idrecord='"+data.newrecord.idmas+"']").find("td.cnmemp").text(data.newrecord.nmemp);
                                    $("tr[idrecord='"+data.newrecord.idmas+"']").find("td.cmasdate").text(data.newrecord.masdate);
                                    $("tr[idrecord='"+data.newrecord.idmas+"']").find("td.cjml").text(data.newrecord.jml);
                                    var note = data.newrecord.note.split("OT---");
                                    if (note.length == "1"){
                                        $("tr[idrecord='"+data.newrecord.idmas+"']").find("td.cnote").text(note[0]);                                        
                                    }else{
                                        $("tr[idrecord='"+data.newrecord.idmas+"']").find("td.cnote").text(note[1]);
                                    }
                                    show_newrecord(data.newrecord.idmas);
                                }
                                if (data.proses == 'add'){
                                    bootbox.alert("New master successfully added!", function (){
                                        bootbox.hideAll();
                                        reloadpage();                                    
                                    });
                                }
                                get_todayadd('<?php echo date('m')?>');
                            }else{
                                bootbox.alert("An error occured!", function (){
                                    bootbox.hideAll();
                                    addition();                                    
                                });                            
                            }

                        }
                    });
                }
                else{

                    if(idemp == ''){
                        $("#idemp").hide({
                            effect  : "fade",
                            duration: 100,
                            complete : function (){
                                $("#idemp").show({
                                    effect : "highlight", 
                                    color: "#be362f",
                                    duration : 1000
                                });
                            }
                        });
                        $.gritter.add({
                            title: 'Employee Identity is null!',
                            text: "Please select one employee!"
                        });                
                    }
                    if(nmemp == ''){
                        $("#nmemp").hide({
                            effect  : "fade",
                            duration: 100,
                            complete : function (){
                                $("#nmemp").show({
                                    effect : "highlight",
                                    color: "#be362f",
                                    duration : 1000
                                });
                            }
                        });
                        $.gritter.add({
                            title: 'Employee Identity is null!',
                            text: "Please select one employee!"
                        });                
                    }
                    if(jml == ''){
                        $("#jml").hide({
                            effect  : "fade",
                            duration: 100,
                            complete : function (){
                                $("#jml").show({
                                    effect : "highlight",
                                    color: "#be362f",
                                    duration : 1000
                                });
                            }
                        });                
                        $.gritter.add({
                            title: 'Value of Leave Entitlement is null',
                            text: "Please fill with numeric value (more than 0)!"
                        });                  
                    }
                    if(note == ''){
                        $("#note").hide({
                            effect  : "fade",
                            duration: 100,
                            complete : function (){
                                $("#note").show({
                                    effect : "highlight",
                                    color: "#be362f",
                                    duration : 1000
                                });
                            }
                        });                
                        $.gritter.add({
                            title: 'Note of this addition is null',
                            text: "Please describe what is this addition for!"
                        });
                    }
                    if(masdate == ''){
                        $("#masdate").hide({
                            effect  : "fade",
                            duration: 100,
                            complete : function (){
                                $("#masdate").show({
                                    effect : "highlight",
                                    color: "#be362f",
                                    duration : 1000
                                });
                            }
                        });
                        $.gritter.add({
                            title: 'Additionl date is null',
                            text: "Please specify the date of this addition!"
                        });
                    }                
                }
            }
            function show_newrecord(idrecord){
                $("#formstatus").val("close");
                bootbox.hideAll();
                $('#form').hide({
                    effect  : "clip",
                    complete : function (){
                        $('#maintabel').show({
                            effect  : "clip",
                            complete    : function (){
                                $("tr[idrecord='"+idrecord+"']").hide({
                                    complete    : function (){
                                        $("tr[idrecord='"+idrecord+"']").show({
                                            effect :"highlight",
                                            duration: 1000
                                        });                                        
                                    }
                                });
                            }
                        });
                    }
                });
            }
            function add_record(){
                $("#proses").val("addadd_process");
                reset_form();
                open_form();
            }
            function edit_record(){
                var idprim = $("#selectedid").val();
                if (idprim != ''){
                    $.ajax({
                        url : "<?php echo $base_url?>mod_attendance/index.php/trx11/home/addedit",
                        data: "idmas="+idprim,
                        type: "post",
                        dataType: "json",
                        cache   : false,
                        success: function (data){
                            reset_form();
    //                        alert(data.IDEmployee);
                            var tgl = data.TglMaster.split('-');
                            $("#masdate").val(tgl['2']+"-"+tgl['1']+"-"+tgl['0']);
                            $("#idemp").val(data.IDEmployee);
                            $("#nmemp").val(data.FullName);
                            $("#jml").val(data.Jml);
                            var ket = data.Keterangan;
                            var not = ket.split("---");
//                            alert(not.length);
                            if (not.length == 1){
                                $("#quick_note").find("option").prop("selected",false);
                                $("#quick_note").find("option[idopt='1']").prop("selected",true);
                                $("#text_note").hide();
                            }else{
                                $("#quick_note").find("option").prop("selected",false);
                                $("#quick_note").find("option[idopt='2']").prop("selected",true);
                                $("#text_note").val(not[1]);
                                
                            }
                            $("#note").val(ket);
                            $("#proses").val("addedit_process");
                            quick_note();
                            open_form();
                        },
                        error   :  function (a,b){
                            alert(a.responseText+"|"+b);
                        }
                    });
                }else{
                    return false;
                }
            }
            function delete_record(){
                var idmas = $("#selectedid").val();
                if (idmas != ''){
                    bootbox.confirm("Are you sure want to delete selected data?", function (r){
                        if (r == true){
                            loading();
                            $.ajax({
                                url  : "<?php echo $base_url?>mod_attendance/index.php/trx11/home/adddelete",
                                data    : "idmas="+idmas,
                                type    : "post",
                                dataType: "json",
                                cache   : false,
                                success : function (data){
                                    if (data.status == "oke"){
                                        bootbox.alert("Additional deleted!", function (){
                                            bootbox.hideAll();
                                            $("tr[idrecord='"+data.idmas+"']").hide({
                                                duration    : 1000,
                                                complete : function (){
                                                    $("tr[idrecord='"+data.idmas+"']").remove();
                                                    get_todayadd('<?php echo date('m')?>');
                                                }
                                            })
                                        });
                                    }else{
                                        bootbox.alert("Deleting Failed!", function (){
                                            bootbox.hideAll();
                                        });
                                    }
                                }
                            });                        
                        }else{
                            bootbox.hideAll();
                        }
                    });
                }
            }
            function quick_note(){
                var qn = $("#quick_note").val();
                if (qn == "OT"){
                    $("#text_note").prop("disabled",false);
                    $("#text_note").show({effect: "fade"});
                }else{
                    $("#text_note").val('');
                    $("#text_note").hide({effect: "fade"});
                    $("#text_note").prop("disabled",true);                    
                    $("#note").val(qn);
                }
            }
            function get_todayadd(month){
                
                $.ajax({
                    url : "<?php echo $base_url?>mod_attendance/index.php/trx11/home/todayadd",
                    data: "month="+month,
                    type: "post",
                    dataType: 'json',
                    cache   : false,
                    success : function (data){
//                        alert(data.nmmonth);
                        $("#monname").html("<b>"+data.nmmonth+"</b>");
                        var res = '';
                        if (data.isi.length > 0){
                            for(var i=0;i<data.isi.length;i++){
                                var stda;
                                if (data.isi[i].status == '1'){
                                    stda    = "<b style='color:green;'>Added &#10004;</b>";
                                }else{
                                    stda    = "<b style='color:#bc5800;'>---</b>"
                                }
                                res = res+"<tr>";
                                    res = res+"<td>";
                                    res = res+data.isi[i].idemp;
                                    res = res+"</td>";
                                    res = res+"<td>";
                                    res = res+data.isi[i].nmemp;
                                    res = res+"</td>";
                                    res = res+"<td>";
                                    res = res+data.isi[i].mondate;
                                    res = res+"</td>";
                                    res = res+"<td>";
                                    res = res+"cuti tahunan";
                                    res = res+"</td>";
                                    res = res+"<td>";
                                    res = res+data.isi[i].addhari+" hari";
                                    res = res+"</td>";
                                    res = res+"<td>";
                                    res = res+stda;
                                    res = res+"</td>";
                                res = res+"</tr>";

                            }
                        }
                        if (data.isi.length == 0){
                            res = "<tr><td align='center'>No record</td></tr>";
                        }
                        $("#todayadd").html(res);
                    },
                    error : function (a,b){
                        alert(a.responseText+"\n"+b);
                    }
                });
            }            
            function typing(){
                var text = $("#text_note").val();
                $("#note").val("OT---"+text);
            }
            function tes(){
                alert($("#note").val());
            }
            function prevmonthadd(){
                var selmonth = $("#selectedmonth").val();
                var month = (selmonth*1)-1;
                if(month > 0 && month <= 12){
                    get_todayadd(month);
                    $("#selectedmonth").val(month);
                }
            }
            function nextmonthadd(){
                var selmonth = $("#selectedmonth").val();
                var month = (selmonth*1)+1;
                if(month > 0 && month <= 12){
                    get_todayadd(month);
                    $("#selectedmonth").val(month);
                }
            }
            </script>
                <div class="row-fluid">
                    <div class="widget" data-toggle='collapse-widget' data-collapse-closed='true'>
                        <div class="widget-head">
                            <h4 class="heading">
                                <input type="hidden" id="selectedmonth" value="<?php echo date('m');?>">
                                Addition of 
                                <span class="btn-group">
                                    <button onclick="prevmonthadd()" class="btn btn-default btn-mini btn-icon">&vartriangleleft;</button>
                                    <button class="btn-facebook btn-small" style="width: 100px;" disabled="" id="monname"><b><?php echo date('F');?></b></button>
                                    <button onclick="nextmonthadd()" class="btn btn-default btn-mini btn-icon">&vartriangleright;</button>
                                </span> 
                                :
                            </h4>
                        </div>
                        <div class="widget-body">
                            <table class="table table-condensed" width="100%" id="todayadd">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
                            </table>
                        </div>
                    </div>
                </div>
                <div id="maintabel">                    
                    <p align="right" class="btn-group right" style="float:right;">
                        <button class="btn btn-icon btn-primary glyphicons circle_plus" onclick="add_record()"><i></i>Add</button>
                        <button class="btn btn-icon btn-warning glyphicons edit" onclick="edit_record()"><i></i>Edit</button>
                        <button class="btn btn-icon btn-danger glyphicons bin" onclick="delete_record()"><i></i>Delete</button>
                    </p>
                    <div class="row-fluid " >
                        <div class="span12">
                            <table width="100%" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable dynamicTable">
                                <thead>
                                    <tr>
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Addition Date</th>
                                        <th>Value</th>
                                        <th>Note</th>
                                        <th>Added By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach ($addition->result() as $a){
                                        echo "<tr class='selectable' idrecord='".$a->ID."' onclick='select_row(\"".$a->ID."\")' ondblclick='edit_record()' >";
                                            echo "<td class='cidemp'>";
                                            echo $a->IDEmployee;
                                            echo "</td>";
                                            echo "<td class='cnmemp'>";
                                            echo $a->FullName;
                                            echo "</td>";
                                            echo "<td class='cmasdate'>";
                                            echo date('d-m-Y',  strtotime($a->TglMaster));
                                            echo "</td>";
                                            echo "<td class='cjml'>";
                                            echo $a->Jml;
                                            echo "</td>";
                                            echo "<td class='cnote'>";
                                            echo str_replace("OT---", "", $a->Keterangan);
                                            echo "</td>";
                                            echo "<td class='caddby'>";
                                            echo $a->AddedBy;
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
<!--                            <button onclick="tes()">tes</button>-->
                        </div>
                    </div>
                </div>
                <div id="form">
                    <div class="row-fluid">
                        <div class="span6">
                            <h4>Form</h4>
                        </div>
                        <div class="span6 right">
                            <button class="btn btn-danger btn-icon glyphicons circle_minus" onclick="cancel_act()"><i></i>Cancel</button>
                        </div>
                    </div>
                    <hr>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="control-group">
                                <label>Employee ID</label>
                                <div class="controls">
                                    <input type="text" id="idemp" readonly="">*                                   
                                </div>
                            </div>
                            <div class="control-group">
                                <label>Employee Name</label>
                                <div class="controls">
                                    <input type="text" id="nmemp">                                    
                                </div>
                            </div>
                            <div class="control-group">
                                <label>Mastering Date</label>
                                <div class="controls">
                                    <input type="text" id="masdate" value="<?php echo date('d-m-Y');?>">**                                 
                                </div>
                            </div>
                            <div class="control-group">
                                <label>Number of Leave Entitlement</label>
                                <div class="controls">
                                    <input type="text" id="jml">                                    
                                </div>
                            </div>
                            <div class="control-group">
                                <label>Note</label>
                                <div class="controls">
                                    <input type="hidden" id="note">
                                    <select id="quick_note" onchange="quick_note()">
                                        <option idopt='0'>--Please Select--</option>
                                        <option idopt="1" value="Cuti Tahunan">Annual Leave / Cuti Tahunan</option>
                                        <option idopt="2" value="OT">Other / Lain-Lain</option>
                                    </select><br>
                                    <textarea onkeyup="typing()" id="text_note" class="span4" style="resize: none;"></textarea>
                                </div>
                            </div>
                            <div>
                                <i class="transindo">* filled automatically</i><br>
                                <i class="transindo">** today is default value</i>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row-fluid">
                        <div class="span12 center">
                            <button class="btn btn-icon btn-success glyphicons ok" onclick="save_this()"><i></i>Save</button>
                            <button class="btn btn-danger btn-icon glyphicons circle_minus" onclick="cancel_act()"><i></i>Cancel</button>
                        </div>
                    </div>
                </div>