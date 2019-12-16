<script>
    $(document).ready(function (){
        $("#selectedid").val('');
        get_todaydel('<?php echo date('m')?>');
        
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
        
        $("#datedel").datepicker({
            dateFormat  : "dd-mm-yy"
        });
        $("#delform").hide();
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
        var emps = <?php echo json_encode($employee);?>;
        $('#nmemp').autocomplete({
            source  : emps,
            select  : function (e, ui){
                $('#idemp').val(ui.item.idemp);
            }
        });
    });
    function select_row(id){
        $("#selectedid").val(id);
        $("tr").removeClass('selected');
        $("tr[idrecord='"+id+"']").addClass('selected');
    }
    function show_newrecord(idrecord){
        $("#formstatus").val("close");
        bootbox.hideAll();
        $('#delform').hide({
            effect  : "clip",
            complete : function (){
                $('#maindel').show({
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
    function save_this(){
        var proses  = $("#proses").val();
        var idemp   = $("#idemp").val();
        var nmemp   = $("#nmemp").val();
        var jmldel  = $("#jmldel").val();
        var note    = $("#note").val();
        var idleave   = $("#selectedid").val();
        var datedel = $("#datedel").val();

        if (idemp != '' && jmldel != '' && note != '' && datedel != ''){
            loading();
            $.ajax({
                url : "<?php echo $base_url?>mod_attendance/index.php/trx11/home/"+proses,
                data: "idemp="+idemp+"&jmldel="+jmldel+"&note="+note+"&idleave="+idleave+"&datedel="+datedel+"&nmemp="+nmemp,
                type: "post",
                dataType    : "json",
                cache       : false,
                success     : function (data){
//                            alert(data.status+"\n"+data.proses+"\n"+data.newrecord.toSource());
                    if (data.status == "oke"){
                        if (data.proses == 'edit'){
                            $("tr[idrecord='"+data.newrecord.idleave+"']").find("td.cidemp").text(data.newrecord.idemp);
                            $("tr[idrecord='"+data.newrecord.idleave+"']").find("td.cnmemp").text(data.newrecord.nmemp);
                            $("tr[idrecord='"+data.newrecord.idleave+"']").find("td.cdatedel").text(data.newrecord.masdate);
                            $("tr[idrecord='"+data.newrecord.idleave+"']").find("td.cjmldel").text(data.newrecord.jmldel);
                            $("tr[idrecord='"+data.newrecord.idleave+"']").find("td.cnote").text(data.newrecord.note);
                            show_newrecord(data.newrecord.idleave);
                        }
                        if (data.proses == 'add'){
                            bootbox.alert("New deletion successfully added!", function (){
                                bootbox.hideAll();
                                deletion();                                    
                            });
                        }
                        get_todaydel('<?php echo date('m')?>');
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
            if(jmldel == ''){
                $("#jmldel").hide({
                    effect  : "fade",
                    duration: 100,
                    complete : function (){
                        $("#jmldel").show({
                            effect : "highlight",
                            color: "#be362f",
                            duration : 1000
                        });
                    }
                });                
                $.gritter.add({
                    title: 'Value of this deletion is null',
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
                    text: "Please describe what is this deletion for!"
                });
            }
            if(datedel == ''){
                $("#datedel").hide({
                    effect  : "fade",
                    duration: 100,
                    complete : function (){
                        $("#datedel").show({
                            effect : "highlight",
                            color: "#be362f",
                            duration : 1000
                        });
                    }
                });
                $.gritter.add({
                    title: 'Deletion date is null',
                    text: "Please specify the date of this deletion!"
                });
            }
        }
    }
    function add_record(){
        $("#proses").val("deladd_process");
        reset_form();
        open_form();
    }
    function reset_form(){
        $("#idemp").val('');
        $("#nmemp").val('');
        $("#jmldel").val('');
        $("#note").val('');
        $("#datedel").val('<?php echo date('d-m-Y');?>');
    }    
    function edit_record(){
        var idprim = $("#selectedid").val();
        if (idprim != ''){
            $.ajax({
                url : "<?php echo $base_url?>mod_attendance/index.php/trx11/home/deledit",
                data: "idleave="+idprim,
                type: "post",
                dataType: "json",
                cache   : false,
                success: function (data){
                    reset_form();
//                        alert(data.IDEmployee);
                    var tgl = data.TglPengajuan.split('-');
                    $("#datedel").val(tgl['2']+"-"+tgl['1']+"-"+tgl['0']);
                    $("#idemp").val(data.IDEmployee);
                    $("#nmemp").val(data.FullName);
                    $("#jmldel").val(data.TotalCuti);
                    $("#note").val(data.Alasan);
                    $("#proses").val("deledit_process");
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
        var idleave = $("#selectedid").val();
        if (idleave != ''){
            bootbox.confirm("Are you sure want to delete selected data?", function (r){
                if (r == true){
                    loading();
                    $.ajax({
                        url  : "<?php echo $base_url?>mod_attendance/index.php/trx11/home/deldelete",
                        data    : "idleave="+idleave,
                        type    : "post",
                        dataType: "json",
                        cache   : false,
                        success : function (data){
                            if (data.status == "oke"){
                                bootbox.alert("Deletion deleted!", function (){
                                    bootbox.hideAll();
                                    $("tr[idrecord='"+data.idleave+"']").hide({
                                        duration    : 1000,
                                        complete : function (){
                                            $("tr[idrecord='"+data.idleave+"']").remove();
                                            get_todaydel('<?php echo date('m')?>');
                                        }
                                    })
                                });
                            }else{
                                bootbox.alert("Deleting Failed!", function (){
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
        $('#delform').hide({
            effect  : "clip",
            complete : function (){
                $('#maindel').show({
                    effect  : "clip",
                });
            }
        });
    }
    function open_form(){
        $("#formstatus").val("open");
        $("#maindel").hide({
            effect      : "clip",
            complete    : function (){
                $("#delform").show({
                    effect :"clip"
                });
                $("#nmemp").focus();
            }
        });
    }
    function get_todaydel(month){
        $.ajax({
            url : "<?php echo $base_url?>mod_attendance/index.php/trx11/home/todaydel",
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
                            stda    = "<b style='color:green;'>Deleted &#10004;</b>";
                        }else if (data.isi[i].status == '2'){
                            stda    = "<b style='color:#be362f;'>Need More! &#10008;</b>"
                        }else if (data.isi[i].status == '3'){
                            stda    = "<b style='color:#be362f;'>Too Much! &#10008;</b>"
                        }else{
                            stda    = "<b style='color:#bc5800;'> ---</b>"
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
                            res = res+"penghapusan sisa cuti";
                            res = res+"</td>";
                            res = res+"<td>";
                            res = res+data.isi[i].sisa+" hari";
                            res = res+"</td>";                            
                            res = res+"<td>";
                            res = res+stda;
                            res = res+"</td>";
                        res = res+"</tr>";

                    }
                }
                else if (data.isi.length == 0){
                    res = "<tr><td align='center'>No record</td></tr>";
                }
                $("#todaydel").html(res);
            },
            error : function (a,b){
                alert(a.responseText+"\n"+b);
            }
        });
    }     
    function prevmonthdel(){
        var selmonth = $("#selectedmonth").val();
        var month = (selmonth*1)-1;
        if(month > 0 && month <= 12){
            get_todaydel(month);
            $("#selectedmonth").val(month);
        }
    }
    function nextmonthdel(){
        var selmonth = $("#selectedmonth").val();
        var month = (selmonth*1)+1;
        if(month > 0 && month <= 12){
            get_todaydel(month);
            $("#selectedmonth").val(month);
        }
    }    
</script>
<div class="row-fluid">
    <div class="widget" data-toggle='collapse-widget' data-collapse-closed='true'>
        <div class="widget-head">
            <h4 class="heading">
                <input type="hidden" id="selectedmonth" value="<?php echo date('m');?>">
                Deletion of 
                <span class="btn-group">
                    <button onclick="prevmonthdel()" class="btn btn-default btn-mini btn-icon">&vartriangleleft;</button>
                    <button class="btn-facebook btn-small" style="width: 100px;" disabled="" id="monname"><b><?php echo date('F');?></b></button>
                    <button onclick="nextmonthdel()" class="btn btn-default btn-mini btn-icon">&vartriangleright;</button>
                </span> 
                :
            </h4>
        </div>
        <div class="widget-body">
            <table class="table table-condensed" width="100%" id="todaydel">

            </table>
        </div>
    </div>
</div>    
<div id="maindel">
    <p align="right" class="btn-group right" style="float:right;">
        <button class="btn btn-icon btn-primary glyphicons circle_plus" onclick="add_record()"><i></i>Add</button>
        <button class="btn btn-icon btn-warning glyphicons edit" onclick="edit_record()"><i></i>Edit</button>
        <button class="btn btn-icon btn-danger glyphicons bin" onclick="delete_record()"><i></i>Delete</button>
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
                            Deletion Date
                        </th>
                        <th>
                            Value
                        </th>
                        <th>
                            Note
                        </th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach($deletion->result() as $d){
                    echo "<tr class='selectable' idrecord='".$d->IDLeave."' onclick='select_row(\"".$d->IDLeave."\")' ondblclick='edit_record()' >";
                        echo "<td class='cidemp'>";
                            echo $d->IDEmployee;
                        echo "</td>";
                        echo "<td class='cnmemp'>";
                            echo $d->FullName;
                        echo "</td>";
                        echo "<td class='cdatedel'>";
                            echo $d->TglPengajuan;
                        echo "</td>";
                        echo "<td class='cjmldel'>";
                            echo $d->TotalCuti;
                        echo "</td>";
                        echo "<td class='cnote'>";
                            echo $d->Alasan;
                        echo "</td>";
                    echo "</tr>";
                }
                ?>                    
                </tbody>
            </table>
        </div>
    </div>
</div>
<div id="delform">
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
                    <input type="text" id="idemp" readonly>
                </div>
            </div>
            <div class="control-group">
                <label>Employee Name</label>
                <div class="controls">
                    <input type="text" id="nmemp">
                </div>
            </div>
            <div class="control-group">
                <label>Deletion Date</label>
                <div class="controls">
                    <input type="text" id="datedel">
                </div>
            </div>
            <div class="control-group">
                <label>Value</label>
                <div class="controls">
                    <input type="text" id="jmldel">
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
