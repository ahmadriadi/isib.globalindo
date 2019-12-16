<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<?php $base_url = $this->session->userdata('sess_base_url'); ?>
<!-- DataTables Plugin --> 
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css" rel="stylesheet" />
<!-- <link href="<?php //echo $base_url     ?>public/theme/scripts/plugins/system/jquery-ui/development-bundle/demos/demos.css" rel="stylesheet" />
<!-- JQueryUI -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-latest"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<!-- JQueryUI Time -->
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/time/timepicker.css" rel="stylesheet" />
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/time/timepicker.js"></script>
<!-- Bootstrap -->
<script src="<?php echo $base_url; ?>public/bootstrap/js/bootstrap.js"></script>
<!-- DataTables Tables Plugin -->
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/css/colvis.css" rel="stylesheet" />
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/css/fixedcolom.css" rel="stylesheet" />
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/api/fnStandingRedraw.js"></script>
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/js/fixedcolom.js"></script>
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/js/colvis.js"></script>
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/js/listbox_paging.js"></script>

<!-- Gritter Notifications Plugin -->
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>  
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />
<style>
    .accept{color: #00CC00;font-weight: bold;}
    .waiting{color: #EC5800;font-weight: bold;}
    .reject{color: #ee1e2d;font-weight: bold;}
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
</style>


<div class="widget">

    <div class="widget-head">
        <div class="row-fluid">
            <div class="span6">
                <h4 class="heading">Data Employee</h4>                
            </div>
            <div class="span6" style="text-align: right">
                <button onclick="reloadpage()" class="btn btn-small btn-default"><i class="icon-refresh"></i></button>
                <button onclick="backtohome()" class="btn btn-small btn-success btn-icon glyphicons home"><i></i>Back to Home</button>
            </div>
        </div>
    </div>
    <div class="widget-body">

        <div class="widget widget-tabs widget-tabs-double-2">
            <div class="widget-head">
                <ul>
                    <li class="active"><a  class="glyphicons user_add" href="#employeeactive" data-toggle="tab"><i></i><span>Employee Active</span></a></li>
                    <li><a  class="glyphicons user_remove" href="#employeepasive" data-toggle="tab"><i></i><span>Employee Pasive</span></a></li>

                </ul>
            </div>
            <div class="widget-body">
                <div class="tab-content">
                    <!-------- ----------------START EMPLOYEE ACTIVE ----------------------------------------------------------->
                    <div id="employeeactive" class="tab-pane active widget-body-regular">
                        <div class="btn-group">
                            <p>    
                                <button id='btn_add' onClick="reply_click(this.id)"  class="btn" id="editable-sample_new">
                                    ADD <i class="icon-plus"></i>
                                </button> 
                                <button id='btn_excel' onClick="reply_click(this.id)"  class="btn" id="editable-sample_new">
                                    EXCEL <i class="icon-plus"></i>
                                </button>                 
                            </p>
                        </div>
                        <!-- Table -->
                        <table id="tableajax" cellspacing="1" cellpadding="1" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable" >

                        </table>
                    </div>

                    <!-------- ----------------END EMPLOYEE ACTIVE ----------------------------------------------------------->

                    <!-------- ----------------START EMPLOYEE PASIVE ----------------------------------------------------------->
                    <div id="employeepasive" class="tab-pane widget-body-regular">
                        <div class="btn-group">
                            <button id='btn_excel' onClick="reply_click(this.id)"  class="btn" id="editable-sample_new">
                                EXCEL <i class="icon-plus"></i>
                            </button>                 

                        </div>
                        <!-- Table -->
                        <table id="tableajax_pasif" cellspacing="1" cellpadding="1" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable" >

                        </table>
                    </div>
                    <!-------- ----------------END EMPLOYEE PASIVE ----------------------------------------------------------->

                </div>
            </div>        
        </div>

        <!-- ===================================================================================================== ----->        


    </div>
    </div>
    <div id="user-form" 
         title="FORM USER" 
         >
    </div>
    <div id="dialog-confirm" title="DELETE REQUEST">
        <p id="textconfirm" style="display:none;">
            Are you sure for delete this data?
        </p>   
    </div>

    <script type="text/javascript">
<?php
foreach ($buttons->result() as $btn) {
    //echo "alert('test');";

    if ($btn->access == "0") {
        echo "$(\"button#$btn->kdbutton\").prop('disabled',true);";
    }
    if ($btn->access == "1") {
        echo "$(\"button#$btn->kdbutton\").prop('disabled',false);";
    }
}
?>

                    function reloadpage() {
                        var content = $("#content");
                        var url = ROOT.base_url + 'mod_attendance/index.php/mst01/home/';
                        //alert(url);
                        content.load(url);
                    }
                    function backtohome() {
                        window.location.href = "<?php echo $base_url; ?>";
                    }
                    $('body').on('click', function(e) {
                        $('[data-toggle="popover"]').each(function() {
                            //the 'is' is for buttons that trigger popups
                            //the 'has' is for icons within a button that triggers a popup
                            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                                $(this).popover('hide');
                            }
                        });
                    });



                    $(document).ready(function() {
                        
                        
                      
                        var dataajax = '<?php echo site_url('mst01/home/dataemployee') ?>';
                        var oTable = $('#tableajax').dataTable({
                            "bJQueryUI": false,
                            "bSortClasses": false,
                            "aaSorting": [[2, "asc"]],
                            "bAutoWidth": true,
                            "bInfo": true,
                            //"pagingType": "full_numbers",
                            "sScrollY": "100%",
                            "sScrollX": "100%",
                            "bScrollCollapse": true,
                            "sPaginationType": "listbox_paging",                           
                            "sPaginationType2": "bootstrap",       
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
                                
                                {
                                    "mData": "ID", "sTitle": "Action", "sClass": "center",
                                    "bSortable": false,
                                    "mRender": function(aoData) {
                                        return "<div class='btn-group'>\n\
                                                        <button btn='btn_edit' idbtn=" + aoData + "  type='button' class='btn btn-mini btn-warning'><i class='icon-pencil icon-white'></i></button>\n\
                                                        <button btn='btn_delete' idbtn=" + aoData + "  type='button' class='btn btn-mini btn-danger'><i class='icon-trash'></i></button>\n\
                                                        </div>\n\
                                                        ";
                                    }
                                },
                                {"mData": "IDEmployee", "sTitle": "IDEmployee", "sClass": "left"},
                                {"mData": "FullName", "sTitle": "FullName", "sClass": "left"},
                                {"mData": "Location", "sTitle": "Location", "sClass": "left"},
                                {"mData": "IDJobGroup", "sTitle": "Group", "sClass": "left"},
                                {"mData": "Dept", "sTitle": "Departement", "sClass": "left"},                              
                                {"mData": "IDJobPosition", "sTitle": "Position", "sClass": "left"},
                                {"mData": "IDUnitGroup", "sTitle": "Unit Group", "sClass": "left","bVisible":false},                              
                                {"mData": "Status", "sTitle": "Status", "sClass": "left","bVisible":false},
                                {"mData": "HireDate", "sTitle": "HireDate", "sClass": "center"},
                                {"mData": "DateEndContract", "sTitle": "End Contract", "sClass": "center"},
                                //{"mData": "ResignDate", "sTitle": "ResignDate", "sClass": "left"},
                            ],
                            
                            "fnDrawCallback": function(oSettings) {
                                $("#tableajax tbody tr ").on('click', 'button', function() {

                                    var button = $(this).attr("btn");
                                    var id = $(this).attr("idbtn");
                                    reply_click(button + '-' + id);


                                })
                                $("#tableajax tbody tr ").on('mouseenter', 'button', function() {
                                    var button = $(this).attr("btn");

                                    $.ajax(
                                            {
                                                type: "POST",
                                                url: '<?php echo site_url('mst01/home/get_access') ?>',
                                                dataType: "json",
                                                data: "btn=" + button,
                                                cache: false,
                                                success:
                                                        function(data, text)
                                                        {
                                                            if (data.valid == 'true') {
                                                                var status = data.btnaccess;
                                                                $("button[btn='" + status + "']").prop('disabled', false);

                                                            } else {
                                                                $("button[btn='" + button + "']").prop('disabled', true);
                                                            }
                                                        },
                                                error:
                                                        function(request, status, error) {
                                                            alert(request.responseText + " " + status + " " + error);
                                                        }
                                            });

                                    return false;


                                });

                                $("#tableajax tbody tr").on('mouseenter', function() {
                                    $('#tableajax tbody tr').addClass("selectable");

                                });
                            }

                        });

                        new $.fn.dataTable.FixedColumns(oTable,
                            {
                            "iLeftColumns" : -1, //Freezed first for columns
                            });


                        var dataajax_pasif = '<?php echo site_url('mst01/home/dataemployee_pasif') ?>';
                        var oTable2 = $('#tableajax_pasif').dataTable({
                            "bJQueryUI": false,
                            "bSortClasses": false,
                            "aaSorting": [[2, "asc"]],
                            "bAutoWidth": true,
                            "bInfo": true,
                            "sScrollY": "100%",
                            "sScrollX": "100%",
                            "bScrollCollapse": true,
                            "sPaginationType": "listbox_paging",                           
                            "sPaginationType2": "bootstrap",  
                            "bRetrieve": true,
                            "oLanguage": {
                                "sSearch": "Search:"
                            },
                            "bProcessing": true,
                            "bServerSide": true,
                            "sAjaxSource": dataajax_pasif,
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
                                {
                                    "mData": "ID", "sTitle": "Action", "sClass": "center",
                                    "bSortable": false,
                                    "mRender": function(aoData) {
                                        return "<div class='btn-group'>\n\
                                                        <button btn='btn_edit' idbtn=" + aoData + "  type='button' class='btn btn-mini btn-warning'><i class='icon-pencil icon-white'></i></button>\n\
                                                        <button btn='btn_delete' idbtn=" + aoData + "  type='button' class='btn btn-mini btn-danger'><i class='icon-trash'></i></button>\n\
                                                        </div>\n\
                                                        ";
                                    }
                                },
                                {"mData": "IDEmployee", "sTitle": "IDEmployee", "sClass": "left"},
                                {"mData": "FullName", "sTitle": "FullName", "sClass": "left"},
                                {"mData": "Location", "sTitle": "Location", "sClass": "left"},
                                {"mData": "IDJobGroup", "sTitle": "Group", "sClass": "left"},
                                {"mData": "Dept", "sTitle": "Departement", "sClass": "left"},
                                {"mData": "IDJobPosition", "sTitle": "Position", "sClass": "left"},
                               // {"mData": "IDUnitGroup", "sTitle": "Unit Group", "sClass": "left"},
                                //{"mData": "Status", "sTitle": "Status", "sClass": "left"},
                                {"mData": "HireDate", "sTitle": "HireDate", "sClass": "center"},
                                {"mData": "ResignDate", "sTitle": "ResignDate", "sClass": "left"},
                            ],
                             
                            "fnDrawCallback": function(oSettings) {
                                $("#tableajax_pasif tbody tr ").on('click', 'button', function() {

                                    var button = $(this).attr("btn");
                                    var id = $(this).attr("idbtn");
                                    reply_click(button + '-' + id);


                                })
                                $("#tableajax_pasif tbody tr ").on('mouseenter', 'button', function() {
                                    var button = $(this).attr("btn");

                                    $.ajax(
                                            {
                                                type: "POST",
                                                url: '<?php echo site_url('mst01/home/get_access') ?>',
                                                dataType: "json",
                                                data: "btn=" + button,
                                                cache: false,
                                                success:
                                                        function(data, text)
                                                        {
                                                            if (data.valid == 'true') {
                                                                var status = data.btnaccess;
                                                                $("button[btn='" + status + "']").prop('disabled', false);

                                                            } else {
                                                                $("button[btn='" + button + "']").prop('disabled', true);
                                                            }
                                                        },
                                                error:
                                                        function(request, status, error) {
                                                            alert(request.responseText + " " + status + " " + error);
                                                        }
                                            });

                                    return false;


                                });

                                $("#tableajax_pasif tbody tr").on('mouseenter', function() {
                                    $('#tableajax_pasif tbody tr').addClass("selectable");

                                });
                            }

                        });
                        

                    });




                    function reply_click(clicked_id)
                    {
                        var str = clicked_id;
                        var explode = str.split('-');
                        var button = explode[0];
                        var id = explode[1];
                        var status = explode[2];


                        //                        alert(keterangan);
                        if (button == 'btn_add') {
                            var content = $("#content");
                            var site = "mod_attendance/index.php/mst01/home/datahome";
                            var flag = 'add';

                            var url = ROOT.base_url + site + "/" + flag + "/" + id;
                            content.fadeOut("slow", "linear");
                            content.load(url);
                            content.fadeIn("slow");


                        } else if (button == 'btn_edit') {
                            var content = $("#content");
                            var site = "mod_attendance/index.php/mst01/home/datahome";
                            var flag = 'edit';

                            var url = ROOT.base_url + site + "/" + flag + "/" + id;
                            content.fadeOut("slow", "linear");
                            content.load(url);
                            content.fadeIn("slow");

                        } else if (button == 'btn_delete') {
                            $(document).ready(function()
                            {

                                $("#textconfirm").show();
                                $(function() {
                                    $("#dialog-confirm").dialog({
                                        resizable: false,
                                        height: 140,
                                        modal: true,
                                        buttons: {
                                            "Delete ": function() {
                                                $.ajax({
                                                    type: "POST",
                                                    url: '<?php echo site_url('mst01/home/delete_employee') ?>' + '/' + id,
                                                    dataType: "json",
                                                    cache: false,
                                                    success:
                                                            function(data) {
                                                                $("#tableajax").dataTable().fnStandingRedraw();
                                                                $("#tableajax_pasif").dataTable().fnStandingRedraw();
                                                            },
                                                    error:
                                                            function(xhr, ajaxOptions, thrownError) {
                                                                alert(xhr.status);
                                                                alert(thrownError);
                                                            }
                                                });

                                                $(this).dialog("close");
                                            },
                                            Cancel: function() {
                                                $(this).dialog("close");

                                            }
                                        }
                                    });
                                });


                            });
                        } else if (button == 'btn_excel') {
                            window.location.href = '<?php echo site_url('mst01/home/exportdata') ?>';

                        }


                    }
    </script>





