<script>
    $(document).ready(function (){
        $("#selectedid").val('');
        get_todayres('<?php echo date('m')?>');
        get_todayclr('<?php echo date('m')?>');
        
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
        $("#resform").hide();
        $("#dateres").datepicker({
            dateFormat  : "dd-mm-yy"
        });
        var emps = <?php echo json_encode($employee);?>;
        $('#nmemp').autocomplete({
            source  : emps,
            select  : function (e, ui){
                $('#idemp').val(ui.item.idemp);
            }
        });
    });
    function get_todayres(month){
        $.ajax({
            url : "<?php echo $base_url?>mod_attendance/index.php/trx11/home/todayres",
            data: "month="+month,
            type: "post",
            dataType: 'json',
            cache   : false,
            success : function (data){
//                        alert(data.nmmonth);
                $("#monnameres").html("<b>"+data.nmmonth+"</b>");
                var res = '';
                if (data.isi.length > 0){
                    for(var i=0;i<data.isi.length;i++){
                        var stda;
                        if (data.isi[i].status == '1'){
                            stda    = "<b style='color:green;'>Reserved &#10004;</b>";
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
                            res = res+"jumlah cuti tahunan yang ditolak";
                            res = res+"</td>";
                            res = res+"<td>";
                            res = res+data.isi[i].jmlres+" hari";
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
                $("#todayres").html(res);
            },
            error : function (a,b){
                alert(a.responseText+"\n"+b);
            }
        });
    }     
    function prevmonthres(){
        var selmonth = $("#selectedmonthres").val();
        var month = (selmonth*1)-1;
        if(month > 0 && month <= 12){
            get_todayres(month);
            $("#selectedmonthres").val(month);
        }
    }
    function nextmonthres(){
        var selmonth = $("#selectedmonthres").val();
        var month = (selmonth*1)+1;
        if(month > 0 && month <= 12){
            get_todayres(month);
            $("#selectedmonthres").val(month);
        }
    }    
    function get_todayclr(month){

        $.ajax({
            url : "<?php echo $base_url?>mod_attendance/index.php/trx11/home/todayclr",
            data: "month="+month,
            type: "post",
            dataType: 'json',
            cache   : false,
            success : function (data){
//                        alert(data.nmmonth);
                $("#monnameclr").html("<b>"+data.nmmonth+"</b>");
                var res = '';
                if (data.isi.length > 0){
                    for(var i=0;i<data.isi.length;i++){
                        var stda;
                        if (data.isi[i].status == '1'){
                            stda    = "<b style='color:green;'>Reserved &#10004;</b>";
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
                            res = res+"jumlah cuti tahunan yang ditolak";
                            res = res+"</td>";
                            res = res+"<td>";
                            res = res+data.isi[i].jmlclr+" hari";
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
                $("#todayclr").html(res);
            },
            error : function (a,b){
                alert(a.responseText+"\n"+b);
            }
        });
    }     
    function prevmonthclr(){
        var selmonth = $("#selectedmonthclr").val();
        var month = (selmonth*1)-1;
        if(month > 0 && month <= 12){
            get_todayclr(month);
            $("#selectedmonthclr").val(month);
        }
    }
    function nextmonthclr(){
        var selmonth = $("#selectedmonthclr").val();
        var month = (selmonth*1)+1;
        if(month > 0 && month <= 12){
            get_todayclr(month);
            $("#selectedmonthclr").val(month);
        }
    }    
    function reset_form(){
        $("#idemp").val('');
        $("#nmemp").val('');
        $("#jmlres").val('');
        $("#note").val('');
        $("#dateres").val('<?php echo date('d-m-Y');?>');
    }
    function open_form(){
        $("#formstatus").val("open");
        $("#mainres").hide({
            effect      : "clip",
            complete    : function (){
                $("#resform").show({
                    effect :"clip"
                });
                $("#nmemp").focus();
            }
        });
    }      
    function select_row(id){
        $("#selectedid").val(id);
        $("tr").removeClass('selected');
        $("tr[idrecord='"+id+"']").addClass('selected');
    }
    function show_newrecord(idrecord){
        $("#formstatus").val("close");
        bootbox.hideAll();
        $('#resform').hide({
            effect  : "clip",
            complete : function (){
                $('#mainres').show({
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
        $("#proses").val("resadd_process");
        reset_form();
        open_form();
    }
    function edit_record(){
        var idprim = $("#selectedid").val();
        if (idprim != ''){
            $.ajax({
                url : "<?php echo $base_url?>mod_attendance/index.php/trx11/home/resedit",
                data: "idres="+idprim,
                type: "post",
                dataType: "json",
                cache   : false,
                success: function (data){
                    reset_form();
                    if (data.JmlReserve == data.JmlDef){
                        var tgl = data.ReserveDate.split('-');
                        $("#dateres").val(tgl['2']+"-"+tgl['1']+"-"+tgl['0']);
                        $("#idemp").val(data.IDEmployee);
                        $("#nmemp").val(data.FullName);
                        $("#jmlres").val(data.JmlReserve);
                        $("#note").val(data.Note);
                        $("#proses").val("resedit_process");
                        open_form();
                    }
                    else{
                        bootbox.alert("It is not editable!<br>The reserve value of this reservation is not same as it's default value.");
                    }
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
        var idres = $("#selectedid").val();
        if (idres != ''){
            bootbox.confirm("Are you sure want to clear this reservation?", function (r){
                if (r == true){
                    loading();
                    $.ajax({
                        url  : "<?php echo $base_url?>mod_attendance/index.php/trx11/home/resdelete",
                        data    : "idres="+idres,
                        type    : "post",
                        dataType: "json",
                        cache   : false,
                        success : function (data){
//                                alert(data);
                            if (data.status == "oke"){
                                bootbox.alert("Reservation cleared!", function (){
                                    bootbox.hideAll();
                                    $("tr[idrecord='"+data.idleave+"']").hide({
                                        duration    : 1000,
                                        complete : function (){
                                            $("tr[idrecord='"+data.idleave+"']").remove();
                                            get_todaydel('<?php echo date('m')?>');
                                        }
                                    });
                                });
                            }else{
                                bootbox.alert("Clearing Failed!", function (){
                                    bootbox.hideAll();
                                });
                            }
                        },
                        error   : function (a,b){
                            alert(a.responseText+"\n"+b);
                            bootbox.hideAll();
                        }
                    });                        
                }else{
                    bootbox.hideAll();
                }
            });
        }
    }
    function cancel_act(){
        $("#formstatus").val("close");
        $('#resform').hide({
            effect  : "clip",
            complete : function (){
                $('#mainres').show({
                    effect  : "clip",
                });
            }
        });
    }    
    function save_this(){
        var proses  = $("#proses").val();
        var idemp   = $("#idemp").val();
        var nmemp   = $("#nmemp").val();
        var jmlres  = $("#jmlres").val();
        var note    = $("#note").val();
        var idres   = $("#selectedid").val();
        var dateres = $("#dateres").val();

        if (idemp != '' && jmlres != '' && note != '' && dateres != ''){
            loading();
            $.ajax({
                url : "<?php echo $base_url?>mod_attendance/index.php/trx11/home/"+proses,
                data: "idemp="+idemp+"&jmlres="+jmlres+"&note="+note+"&idres="+idres+"&dateres="+dateres+"&nmemp="+nmemp,
                type: "post",
                dataType    : "json",
                cache       : false,
                success     : function (data){
//                            alert(data.status+"\n"+data.proses+"\n"+data.newrecord.toSource());
                    if (data.status == "oke"){
                        if (data.proses == 'edit'){
                            $("tr[idrecord='"+data.newrecord.idres+"']").find("td.cidemp").text(data.newrecord.idemp);
                            $("tr[idrecord='"+data.newrecord.idres+"']").find("td.cnmemp").text(data.newrecord.nmemp);
                            $("tr[idrecord='"+data.newrecord.idres+"']").find("td.cdateres").text(data.newrecord.masdate);
                            $("tr[idrecord='"+data.newrecord.idres+"']").find("td.cdateclr").text(data.newrecord.masdate);
                            $("tr[idrecord='"+data.newrecord.idres+"']").find("td.cjmlres").text(data.newrecord.jmlres);
                            $("tr[idrecord='"+data.newrecord.idres+"']").find("td.cjmldef").text(data.newrecord.jmldef);
                            $("tr[idrecord='"+data.newrecord.idres+"']").find("td.cnote").text(data.newrecord.note);
                            show_newrecord(data.newrecord.idres);
                        }
                        if (data.proses == 'add'){
                            bootbox.alert("New reservation successfully added!", function (){
                                bootbox.hideAll();
                                reserve();                                    
                            });
                        }
                        get_todayres('<?php echo date('m')?>');
                        get_todayclr('<?php echo date('m')?>');
                    }else{
                        bootbox.alert("An error occured!", function (){
                            bootbox.hideAll();
                            reloadpage();                                    
                        });                            
                    }
                },
                error : function (a,b){
                    alert(a.responseText+"\n"+b);
                    bootbox.hideAll();
                    reloadpage();                             
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
            if(jmlres == ''){
                $("#jmlres").hide({
                    effect  : "fade",
                    duration: 100,
                    complete : function (){
                        $("#jmlres").show({
                            effect : "highlight",
                            color: "#be362f",
                            duration : 1000
                        });
                    }
                });                
                $.gritter.add({
                    title: 'Value of this reservation is null',
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
                    title: 'Note of this deletion is null',
                    text: "Please describe what is this reservation for!"
                });
            }
            if(dateres == ''){
                $("#dateres").hide({
                    effect  : "fade",
                    duration: 100,
                    complete : function (){
                        $("#dateres").show({
                            effect : "highlight",
                            color: "#be362f",
                            duration : 1000
                        });
                    }
                });
                $.gritter.add({
                    title: 'Reservation date is null',
                    text: "Please specify the date of this reservation!"
                });
            }
        }
    }    
</script>
<div class="row-fluid">
    <div class="widget" data-toggle='collapse-widget' data-collapse-closed='true'>
        <div class="widget-head">
            <h4 class="heading">
                <input type="hidden" id="selectedmonthres" value="<?php echo date('m');?>">
                Reservation of 
                <span class="btn-group">
                    <button onclick="prevmonthres()" class="btn btn-default btn-mini btn-icon">&vartriangleleft;</button>
                    <button class="btn-facebook btn-small" style="width: 100px;" disabled="" id="monnameres"><b><?php echo date('F');?></b></button>
                    <button onclick="nextmonthres()" class="btn btn-default btn-mini btn-icon">&vartriangleright;</button>
                </span> 
                :
            </h4>
        </div>
        <div class="widget-body">
            <table class="table table-condensed" width="100%" id="todayres">

            </table>
        </div>
    </div>
</div>   
<div class="row-fluid">
    <div class="widget" data-toggle='collapse-widget' data-collapse-closed='true'>
        <div class="widget-head">
            <h4 class="heading">
                <input type="hidden" id="selectedmonthclr" value="<?php echo date('m');?>">
                Clearance of 
                <span class="btn-group">
                    <button onclick="prevmonthclr()" class="btn btn-default btn-mini btn-icon">&vartriangleleft;</button>
                    <button class="btn-facebook btn-small" style="width: 100px;" disabled="" id="monnameclr"><b><?php echo date('F');?></b></button>
                    <button onclick="nextmonthclr()" class="btn btn-default btn-mini btn-icon">&vartriangleright;</button>
                </span> 
                :
            </h4>
        </div>
        <div class="widget-body">
            <table class="table table-condensed" width="100%" id="todayclr">

            </table>
        </div>
    </div>
</div>   

<div id="mainres">
    <p align="right" class="btn-group right" style="float:right;">
        <button class="btn btn-icon btn-primary glyphicons circle_plus" onclick="add_record()"><i></i>Add</button>
        <button class="btn btn-icon btn-warning glyphicons edit" onclick="edit_record()"><i></i>Edit</button>
        <button class="btn btn-icon btn-danger glyphicons bin" onclick="delete_record()"><i></i>Clear</button>
    </p>     
    <div class="row-fluid">
        <div class="span12">
            <table width="100%" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable dynamicTable">
                <thead>
                    <tr>
                        <th>
                            Employee ID
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Reserve Date
                        </th>
                        <th>
                            Clearance Date
                        </th>
                        <th>
                            Reserved Value
                        </th>
                        <th>
                            Original Value
                        </th>
                        <th>
                            Note
                        </th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach($reserve->result() as $res){
                    echo "<tr class='selectable' idrecord='".$res->ID."' onclick='select_row(\"".$res->ID."\")' ondblclick='edit_record()' >";
                        echo "<td class='cidemp'>";
                            echo $res->IDEmployee;
                        echo "</td>";
                        echo "<td class='cnmemp'>";
                            echo $res->FullName;
                        echo "</td>";
                        echo "<td class='cdateres'>";
                            echo $res->ReserveDate;
                        echo "</td>";
                        echo "<td class='cdateclr'>";
                            echo $res->ClearanceDate;
                        echo "</td>";
                        echo "<td class='cjmlres'>";
                            echo $res->JmlReserve;
                        echo "</td>";
                        echo "<td class='cjmldef'>";
                            echo $res->JmlDef;
                        echo "</td>";
                        echo "<td class='cnote'>";
                            echo $res->Note;
                        echo "</td>";
                    echo "</tr>";
                }
                ?>                    
                </tbody>
            </table>
        </div>
    </div>
</div>
<div id="resform">
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
                    <input type="text" id="idemp">
                </div>
            </div>
            <div class="control-group">
                <label>Employee Name</label>
                <div class="controls">
                    <input type="text" id="nmemp">
                </div>
            </div>
            <div class="control-group">
                <label>Reservation Date</label>
                <div class="controls">
                    <input type="text" id="dateres">
                </div>
            </div>
<!--            <div class="control-group" >
                <label>Clearance Date</label>
                <div class="controls">
                    <input type="text" id="dateclr">
                </div>
            </div>-->
            <div class="control-group">
                <label>Value</label>
                <div class="controls">
                    <input type="text" id="jmlres">
                </div>
            </div>
            <div class="control-group">
                <label>Note</label>
                <div class="controls">
                    <textarea id="note"></textarea>
                </div>
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
