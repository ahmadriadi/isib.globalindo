<style>
    .tbldalam{
        width: 50%;
        height: 100%;
        padding-left: 3px;
    }
    .tbldalamakhir{
        width: 73%;
        height: 100%;
    }
    hr.hrku{
        margin: 0px 0px 10px 0px;
    }
    .selectable:hover{
        background: #555;
    }
</style>
<script>
$(document).ready(function (){
    buildtable();
});
function builddialog(action){
//    alert(action);
    var dialog_header = '\n\
    <div class="widget-body">\n\
        <div class="row-fluid">\n\
            <div class="span12">\n\
                <h4>Action : <span id="takenaction"></span></h4>\n\
            </div>\n\
        </div>\n\
        <hr class="hrku">\n\
        <div class="row-fluid">\n\
            <h5>Please fill out this form for taking the action :</h5>\n\
        </div>\n\
        <div class="row-fluid">\n\ ';
    var dialog_pblnote = '\n\
            <div class="span6">\n\
                <div class="control-group">\n\
                    <div class="control-label">Problem Note</div>\n\
                    <div class="controls">\n\
                        <textarea id="slvpbl"></textarea>\n\
                    </div>\n\
                </div>\n\
            </div>\n\ ';
    var dialog_date = '\n\
            <div class="span6">\n\
                <div class="control-group">\n\
                    <div class="control-label">Date</div>\n\
                    <div class="controls">\n\
                        <input type="text" id="slvdate" value="<?php echo date("Y-m-d");?>">\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>\n\ ';
    var dialog_slvnote  = '\n\
        <div class="row-fluid">\n\
            <div class="span6">\n\
                <div class="control-group">\n\
                    <div class="control-label">Solving Note</div>\n\
                    <div class="controls">\n\
                        <textarea id="slvnote"></textarea>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>\n\ ';
    var dialog_footer   = '\n\
        <hr class="hrku">\n\
        <div class="row-fluid">\n\
            <div class="span12 center">\n\
                <button class="btn btn-primary btn-icon glyphicons ok_2" onclick="prob_action_process()"><i></i>Update</button>\n\
                <button class="btn btn-danger btn-icon glyphicons remove_2" onclick="bootbox.hideAll()"><i></i>Cancel</button>\n\
            </div>\n\
        </div>\n\
    </div>\n\ ';
    var isi="";
    if (action == "needconf"){
        isi = dialog_header+dialog_pblnote+dialog_date+dialog_footer;
    }else{
        isi = dialog_header+dialog_pblnote+dialog_date+dialog_slvnote+dialog_footer;
    }
//    alert(isi);
    $("#dialogtext").val(isi);
    return true;
}
function buildtable(){
    var dataajax    = "<?php echo site_url()?>/trx10/home/get_data/0";   
    var table = $('#table_newrpt').dataTable({
        "bJQueryUI": false,
        "bSortClasses": false,
        "aaSorting": [[2, "desc" ]],
        "bAutoWidth": false,
        "bInfo": true,
        "sScrollY": "100%",
        "sScrollX": "100%",
        "bScrollCollapse": true,
        "sPaginationType": "bootstrap",
        "bRetrieve": true,
        "oLanguage": {
            "sSearch": "Search:"
        },
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": dataajax,
        "fnServerData": function(sSource, aoData, fnCallback) {
            $.ajax({
                "dataType": 'json',
                "type": "POST",
                "url": sSource,
                "data": aoData,
                "success": fnCallback
            });
        },
        "aoColumns": [
            {"mData": "ID", "sTitle": "Ref", "sClass": "left", "mRender" : function (a){ return "#"+a;}},
            {"mData": "EName", "sTitle": "Emp Name", "sClass": "left"},
	    {"mData": "Location", "sTitle": "IDLocation", "sClass": "left"},  	
            {"mData": "NComplaint", "sTitle": "ComplaintNote", "sClass": "left",
                "mRender": function (a,b,c){
                    var potong  = a.length <= 35 ? a : a.substring(0,32)+" ...";
                    var com = '<a data-toggle="popover" data-content="<div align=\'left\'>'+a+'</div>" data-placement="right">';
                    if (a.length > 35){
                        com = com+potong+"</a>";
                    }else{
                        com = potong;
                    }
                    return com;
                }
            },
//            {"mData": "ComplainDate", "sTitle": "ComplaintDate", "sClass": "center",sType: 'date-eu'},
          //  {"mData": "Cause", "sTitle": "Cause", "sClass": "center"},
          //  {"mData": "ProblemNote", "sTitle": "ProblemNote", "sClass": "left"},                                                  
          //  {"mData": "SolutionNote", "sTitle": "SolutionNote", "sClass": "left"},
          //  {"mData": "SolutionDate", "sTitle": "SolutionDate", "sClass": "left"},
            {
                "mData": "RType", "sTitle": "Type", "sClass": "left",
                "mRender": function(a,b,c){
                    var icon,ba="",bb="";
//                    if (c.HODC == '0'){ icon = "<a class='glyphicons standard btn-small primary remove_2'><i></i>"; ba = "<b>"; bb = "</b></a>";}
//                    else if (c.HODC == '1'){ icon = "<a class='glyphicons standard btn-small primary ok_2'><i></i>"; ba = "<b>"; bb = "</b></a>";}
                    if (c.HODC == '0'){ icon = "<i class='icon-white icon-question-sign'></i>"; ba = "<b>"; bb = "</b>";}
                    else if (c.HODC == '1'){ icon = "<i class='icon-white icon-ok'></i>"; ba = "<b>"; bb = "</b>";}
                    else if (c.HODC == '3'){ icon = "<i class='icon-white icon-remove'></i>"; ba = "<b>"; bb = "</b>";}
                    else { icon = "";}
                    return ba+c.RName+icon+bb;
                }
            
            },
            {"mData": "CDate", "sTitle": "Submit Date", "sClass": "center"},
            {
                "mData": "ID", "sTitle": "Action","sClass" : "center",
                "bSortable": false,
                "mRender": function(a,b,c) {
                    var bin = "";
                    bin = bin+'<table class="table-borderless " width="200px">';
                        bin = bin+'<tr >';
                            bin = bin+'<td  rowspan="3" width="50%" style="padding: 0">';
                                bin = bin+'<a idrpt="'+c.ID+'" action="solved" onclick="prob_action(this)" class="widget-stats btn-success" style="margin: 0; height: 100%; border-radius: 5px;" > ';
                                bin = bin+'<span class="glyphicons ok_2"><i></i></span>';
                                bin = bin+'<span class="txt"><b>Solved</></span>';
                                bin = bin+'</a>';                                
                            bin = bin+'</td>';
                            bin = bin+'<td  width="50%" style="padding: 0; padding-left: 5px;">';
                                bin = bin+'<span idrpt="'+c.ID+'" action="inprogress" onclick="prob_action(this)" class="tbldalam btn btn-small btn-primary glyphicons cogwheels"><i></i>InProgress</span>';
                            bin = bin+'</td>';
                        bin = bin+'</tr>';
                        bin = bin+'<tr >';
                            bin = bin+'<td  style="padding: 0; padding-left: 5px;">';
                                bin = bin+'<span idrpt="'+c.ID+'" action="suspended" onclick="prob_action(this)" class="tbldalam btn btn-small btn-warning glyphicons clock"><i></i>Suspended</span>';
                            bin = bin+'</td>';
                        bin = bin+'</tr>';
                        bin = bin+'<tr >';
                            bin = bin+'<td  style="padding: 0; padding-left: 5px;">';
                                bin = bin+'<span idrpt="'+c.ID+'" action="unsolved" onclick="prob_action(this)" class="tbldalam btn btn-small btn-danger glyphicons remove"><i></i>Unsolved</span>';
                            bin = bin+'</td>';
                        bin = bin+'</tr>';
                        bin = bin+'<tr >';
                            bin = bin+'<td  style="padding: 0; padding-top: 5px;" colspan="2">';
                                bin = bin+'<span idrpt="'+c.ID+'" action="needconf" onclick="prob_action(this)" class="tbldalamakhir btn btn-small btn-facebook glyphicons share"><i></i>Needs HoD Confirmation</span>';
                            bin = bin+'</td>';
                        bin = bin+'</tr>';
                    bin = bin+'</table>';
                    var btn = "";
                    btn = btn+"<span data-placement='left' title='Set This Problem to :' data-content='"+bin+"' ";
                    btn = btn+"class='btn btn-small  btn-inverse' data-toggle='popover' data-title=''>Action</span>";
                    <?php if (in_array("ITMGR", $upar)){?>
                        var pic="";
                        pic = pic+'<table class="table-borderless " >'
                            pic = pic+'<tr >';
                                pic = pic+'<td  style="padding: 0; " align="center">';
                        pic = pic+'<input type="hidden" class="idrpt" value="'+c.ID+'" >';
                        pic = pic+'<input type="text" class="idpic" placeholder="[Employee ID]" value="'+(c.PIC == null ? "" : c.PIC )+'" disabled="disabled">';
                                pic = pic+'</td>';
                            pic = pic+'</tr>';
                            pic = pic+'<tr >';
                                pic = pic+'<td  style="padding: 0; " align="center">';
                        pic = pic+'<input type="text" class="nmpic" placeholder="[Employee Name]" value="'+(c.PICName == null ? "" : c.PICName)+'" >';
                                pic = pic+'</td>';
                            pic = pic+'</tr>';
                            pic = pic+'<tr >';
                                pic = pic+'<td  style="padding: 0; padding-top: 3px;" align="center">';
                        pic = pic+'<span class="btn btn-small btn-facebook glyphicons user_add " action="set" onclick="set_cpic(this)"><i></i>Set As PIC</span>';
                        pic = pic+'<span class="btn btn-small btn-danger glyphicons user_remove " action="remove" onclick="set_cpic(this)"><i></i>Remove PIC</span>';
                                pic = pic+'</td>';
                            pic = pic+'</tr>';                        
                        pic = pic+'</table>';
                        
                        btn = btn+"<span onclick='activate_autocomplete()' data-placement='left' title='Set PIC for this problem:' data-content='"+pic+"' ";
                        btn = btn+"class='btn btn-small btn-inverse' data-toggle='popover' data-title=''>Set PIC</span>";                        
                    <?php } ?>
//                    return c.PIC;
                    return btn;
                }
            },
        ],
        "fnRowCallback" : function (a,b,c,d){
//            alert($(this).attr("class"));
//            alert(a.toSource());
//            alert(b.toSource());
//            alert(c.toSource());
//            alert(d.toSource());
            $('td:eq(0)', a).attr('idrpt', b.ID);
            $('td:eq(0)', a).attr('type', b.RName);
            return a;
        },
        "fnDrawCallback": function(oSettings) {
            $("[data-toggle='popover']").popover({
                html    : true
            });
            $("#table_newrpt>tbody>tr").attr("sub","close");
            $("#table_newrpt>tbody>tr").addClass("selectable");
            $("#table_newrpt>tbody>tr.selectable").click(function (){
                var sub = $(this).attr("sub");
                var idrpt   = $(this).find("td:first").attr("idrpt");
                var type    = $(this).find("td:first").attr("type");
                if (type == "REQUEST"){
                    if (sub == "close"){
                        var ini =$(this);
                        $.ajax({
                            url     : "<?php echo site_url()?>/trx10/home/get_request_detail",
                            data    : "idrpt="+idrpt,
                            type    : "post",
                            dataType: "html",
                            cache   : false,
                            success : function (data){
    //                            alert(data);
                                ini.after("<tr><td colspan='6'>"+data+"</td></tr>");
                                ini.attr("sub","open");
                            },
                            error   : function(a){
                                alert(a.responseText);                            
                            }
                        });
                    }
                    else{
                        $(this).next().remove();
                        $(this).attr("sub","close");
                    }
                }
                else{
                    return false;
                }
            });
        }
    });
}
function activate_autocomplete(){
    setTimeout(function (){
        $(".nmpic").autocomplete({
            source  : function (request,response){
                $.ajax({
                    url     : "<?php echo site_url()?>/trx10/home/get_uparam",
                    data    : "key="+request.term,
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
                        response(data);
                    },
                    error   : function (a){
                        alert(a.responseText()+"\n"+a.statusText);
                    }
                });
            },
            select  : function (a,e){
                $(".idpic").val(e.item.idpic);
                setTimeout(function (){
                    $("[role='presentation']").remove();
                },"500");
            }
        });
        $(".nmpic").focus();
    },"200");
}
function prob_action(ini){
    var stat    = $(ini).attr("disabled");
    if (stat != "disabled"){
        var action  = $(ini).attr("action");
        var idrpt   = $(ini).attr("idrpt");
        var build   = builddialog(action);

        if (build){
            var dialog  = $("#dialogtext").val();
            dialog  = dialog+'<input type="hidden" value="'+idrpt+'" id="cidrpt">';
            dialog  = dialog+'<input type="hidden" value="'+action+'" id="caction">';
            bootbox.dialog(dialog);
            if (action != "needconf"){
                $("#takenaction").text("Set Ref #"+idrpt+" as "+action.toUpperCase());
            }
            else{
                $("#takenaction").text("Ref #"+idrpt+" => Send confirmation to reporter's HOD");
            }        
        }
    }
}
function prob_action_process(){
    var idrpt   = $("#cidrpt").val();
    var action  = $("#caction").val();
    var snote   = $("#slvnote").val();
    var sdate   = $("#slvdate").val();
    var spbl    = $("#slvpbl").val();
//    alert("idrpt="+idrpt+"&action="+action+"&snote="+snote+"&sdate="+sdate+"&spbl="+spbl);
    $.ajax({
        url     : "<?php echo site_url()?>/trx10/home/set_report",
        data    : "idrpt="+idrpt+"&action="+action+"&snote="+snote+"&sdate="+sdate+"&spbl="+spbl,
        type    : "post",
        dataType: "json",
        cache   : false,
        success : function (data){
            chgtab(0);
            $.gritter.add({
                title: "Problem handling updated!",
                text: "",
            });
            bootbox.hideAll();
        },
        error   : function (a){
            alert(a.responseText+"\n"+a.statusText);
            bootbox.hideAll();
        }
    });
}
function set_cpic(ini){
//    alert($(ini).attr("action"));
    var idrpt   = $(".idrpt").val();
    var idpic   = $(".idpic").val();
    var nmpic   = $(".nmpic").val();
    var action  = $(ini).attr("action");
    if (action == 'set'){
        var text    = "Set "+nmpic+" as the PIC of this problem?";
        var grittl  = "Setting PIC Success!";
        var gritxt  = 'The PIC of the selected problem has been set to '+nmpic;
    }else{
        var text    = "Remove the PIC of this problem?";
        var grittl  = "Removing PIC Success!";
        var gritxt  = 'The PIC of the selected problem has been removed';        
    }
    loading();
    bootbox.confirm(text, function (r){
        if (r){
            $.ajax({
                url     : "<?php echo site_url()?>/trx10/home/set_cpic",
                data    : "idpic="+idpic+"&action="+action+"&idrpt="+idrpt,
                type    : "post",
                dataType: "json",
                cache   : false,
                success : function (data){
                    chgtab(0);
                    if (data.status == "oke"){
                        $.gritter.add({
                            title: grittl,
                            text: gritxt
                        });
                    }
                    bootbox.hideAll();
                },
                error   : function (a){
                    alert(a.responseText+"\n"+a.statusText);
                    bootbox.hideAll();
                }
            });
        }
        else{
            bootbox.hideAll();
        }
    });
}
</script>
<div class="row-fluid">
    <div class="span12">
        <table width="100%" id="table_newrpt" class="table-bordered table-condensed table-primary"></table>
    </div>
</div>
<textarea style="display: none" id="dialogtext"></textarea>
<textarea style="display: none" id="dialogtext_header"></textarea>
<textarea style="display: none" id="dialogtext_pblnote"></textarea>
<textarea style="display: none" id="dialogtext_date"></textarea>
<textarea style="display: none" id="dialogtext_slvnote"></textarea>
<textarea style="display: none" id="dialogtext_footer"></textarea>
 
