<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<?php $base_url = $this->session->userdata('sess_base_url'); ?>
<!-- DataTables Plugin --> 
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css" rel="stylesheet" />

<!-- JQueryUI -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-latest.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<!-- Bootstrap -->
<script src="<?php echo $base_url; ?>public/bootstrap/js/bootstrap.js"></script>
<!-- DataTables Tables Plugin -->
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/css/colvis.css" rel="stylesheet" />
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/colvis.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/api/fnStandingRedraw.js"></script>
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/js/listbox_paging.js"></script>

<!-- Gritter Notifications Plugin -->
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>  
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />
<style>
    .accept{color: #00CC00;font-weight: bold;}
    .waiting{color: #EC5800;font-weight: bold;}
    .reject{color: #ee1e2d;font-weight: bold;}
    a.ui-dialog-titlebar-close { display:block; }
    td.alert {
        color: #ee1e2d !important; 
        font-weight: bold;

    }
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
</style>


<div class="widget">

    <div class="widget-head">
        <div class="row-fluid">
            <div class="span6">
                <h4 class="heading">Data Personal Loan</h4>                
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
                    <li class="active"><a  class="glyphicons clock" href="#paidon" data-toggle="tab"><i></i><span>Not Yet Paid Off  </span></a></li>
                    <li><a  class="glyphicons ok_2" href="#paidoff" data-toggle="tab"><i></i><span>Already Paid Off </span></a></li>

                </ul>
            </div>
            <div class="widget-body">
                <div class="tab-content">
                    <!-------- ----------------LOAN NOT YET PAID OFF ----------------------------------------------------------->
                    <div id="paidon" class="tab-pane active widget-body-regular">
                        <div class="alert alert-error">
                            <button class="close" data-dismiss="alert" type="button">×</button>
                            <strong>WARNING !
                                IF THE EMPLOYEE LIST IS COLOR RED, THEN THE DATA NOT YET SCHEDULED FOR LOAN
                            </strong>
                        </div>
                        <div class="btn-group">	       
                            <p>    
                                <button id='btn_add' onClick="reply_click(this.id)"  class="btn" id="editable-sample_new">
                                    ADD <i class="icon-plus"></i>
                                </button> 
                                <button id='btn_excel-paidon' onClick="reply_click(this.id)"  class="btn" id="editable-sample_new">
                                    EXCEL <i class="icon-plus"></i>
                                </button>				                 
                            </p>
                        </div>
                        <!-- Table -->
                        <table width="100%" id="tableajaxpaidon" cellspacing="1" cellpadding="1" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable" >

                        </table>
                        <table  id="tableajax2" cellspacing="1" cellpadding="1" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable" >

                        </table>
                    </div>

                    <!-------- ----------------LOAN NOT YET PAID OFF  ----------------------------------------------------------->

                    <!-------- ----------------ALREADY PAID OFF ----------------------------------------------------------->
                    <div id="paidoff" class="tab-pane widget-body-regular">
                        <div class="btn-group">                                          
                            <button id='btn_excel-paidoff' onClick="reply_click(this.id)"  class="btn" id="editable-sample_new">
                                EXCEL <i class="icon-plus"></i>
                            </button>                 
                        </div>
                        <!-- Table -->
                        <table width="100%" id="tableajaxpaidoff" width="100%" cellspacing="1" cellpadding="1" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable" >

                        </table>
                        <table  id="tableajax3" cellspacing="1" cellpadding="1" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable" >

                        </table>
                    </div>
                    <!-------- ----------------END EMPLOYEE PASIVE ----------------------------------------------------------->


                </div>        
            </div>

            <!-- ===================================================================================================== ----->        


        </div>
    </div>












    <div align="center" id="loan-modal" class="modal container  hide fade long" tabindex="-1"></div>

    <div id="dialog-confirm" title="DELETE REQUEST">
        <p id="textconfirm" style="display:none;">
            Are you sure for delete this data?
        </p>   
    </div>

    <div id="processdata-modal" class="modal hide fade" tabindex="-1"> 
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Schedule loan process</h3>
        </div>
        <div class="modal-body">   
            <p id="textloading" style="display:none;">
                <img id="loading" src="<?php echo $base_url; ?>public/avatar/76.GIF" style="/*display:none;*/">
                Processing . . .
            </p>
            <p id="textfinished" style="display:none;">
                Proceed Successed
            </p>
        </div>

    </div> 

    <script type="text/javascript">
        function reloadpage() {
            var content = $("#content .innerLR");
            var url = ROOT.base_url + 'mod_fieldpayroll/index.php/mst02/home/';
            //alert(url);
            content.load(url);
        }
        function backtohome() {
            window.location.href = "<?php echo $base_url; ?>";
        }
        $('body').on('click', function (e) {
            $('[data-toggle="popover"]').each(function () {
                //the 'is' is for buttons that trigger popups
                //the 'has' is for icons within a button that triggers a popup
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    $(this).popover('hide');
                }
            });
        });



        $(document).ready(function () {
            cek();
            var dataajaxpaidon = '<?php echo site_url('mst02/home/getdatatable_paidon') ?>';
            var dataajaxpaidoff = '<?php echo site_url('mst02/home/getdatatable_paidoff') ?>';

            var oTable = $('#tableajaxpaidon').dataTable({
                "bJQueryUI": false,
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
                "bSortClasses": false,
                "aaSorting": [[6, "desc"]],
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
                "sAjaxSource": dataajaxpaidon,
                "fnServerData": function (sSource, aoData, fnCallback) {
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
                        "mData": "ID", "sTitle": "Action", "sClass": "clickdata",
                        "bSortable": false,
                        "mRender": function (aoData) {


                            //alert(aoData);
                            return "<div class='btn-group'>\n\
                                                        <button btn='btn_edit' idbtn=" + aoData + "  type='button' class='btn btn-mini btn-warning'><i class='icon-pencil icon-white'></i></button>\n\
                                                        <button btn='btn_delete' idbtn=" + aoData + "  type='button' class='btn btn-mini btn-danger'><i class='icon-trash'></i></button>\n\
                                                        <button btn='btn_schedule' idbtn=" + aoData + "  type='button' class='btn btn-mini btn-primary'><i class='icon-time'></i></button>\n\
                                                        </div>\n\
                                                        ";
                        }
                    },
                    {"mData": "IDEmployee", "sTitle": "IDEmployee", "sClass": "left"},
                    {"mData": "FullName", "sTitle": "FullName", "sClass": "left"},
                    {"mData": "JobGroup", "sTitle": "Group", "sClass": "left"},
                    {"mData": "LoanDate", "sTitle": "LoanDate", "sClass": "left", sType: 'date-eu'},
                    {"mData": "Amount", "sTitle": "Amount", "sClass": "right"},
                    {"mData": "InterestLaon", "sTitle": "Insterest %", "sClass": "right"},
                    {"mData": "InterestInstalment", "sTitle": "Insterest per month", "sClass": "right"},
                    {"mData": "Instalment", "sTitle": "Instalment", "sClass": "right"},
                    {"mData": "Term", "sTitle": "Term", "sClass": "right"},
                    {"mData": "DateInstalment", "sTitle": "DateInstalment", "sClass": "left", sType: 'date-eu'},
                    {"mData": "Note", "sTitle": "Note", "sClass": "left"},
                    {"mData": "ID", "sTitle": "Param", "sClass": "clickdata", "bVisible": true}

                ],
                "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {

                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "<?php echo site_url('mst02/home/checkschedule') ?>",
                        data: "id=" + aData.ID,
                        success: function (data, text) {

                            if (data.statusdata == 'empty') {
                                $('td', nRow).addClass('alert');

                            }
                            return nRow;

                        }
                    });


                },
                "fnDrawCallback": function (oSettings) {
                    $("#tableajaxpaidon tbody tr ").on('click', 'button', function () {

                        var button = $(this).attr("btn");
                        var id = $(this).attr("idbtn");
                        reply_click(button + '-' + id);


                    })

                    $("#tableajaxpaidon tbody tr").on('mouseenter', function () {
                        $('#tableajaxpaidon tbody tr').addClass("selectable");

                    });
                    $('#tableajaxpaidon tbody tr').on('click', function () {
                        var nTds = $('td', this);
                        var id = $(nTds[12]).text();
                        detail(id)
                        return false;
                    });

                    $("#tableajaxpaidon tbody tr ").on('mouseenter', 'button', function () {
                        var button = $(this).attr("btn");

                        $.ajax(
                                {
                                    type: "POST",
                                    url: '<?php echo site_url('mst02/home/get_access') ?>',
                                    dataType: "json",
                                    data: "btn=" + button,
                                    cache: false,
                                    success:
                                            function (data, text)
                                            {
                                                if (data.valid == 'true') {
                                                    var status = data.btnaccess;
                                                    $("button[btn='" + status + "']").prop('disabled', false);

                                                } else {
                                                    $("button[btn='" + button + "']").prop('disabled', true);
                                                }
                                            },
                                    error:
                                            function (request, status, error) {
                                                alert(request.responseText + " " + status + " " + error);
                                            }
                                });

                        return false;


                    });


                }

            });

            var oTable = $('#tableajaxpaidoff').dataTable({
                "bJQueryUI": false,
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
                "bSortClasses": false,
                "aaSorting": [[6, "desc"]],
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
                "sAjaxSource": dataajaxpaidoff,
                "fnServerData": function (sSource, aoData, fnCallback) {
                    $.ajax({
                        "dataType": 'json',
                        "type": "POST",
                        "url": sSource,
                        "data": aoData,
                        "success": fnCallback
                    });
                },
                "aoColumns": [
                    {"mData": "IDEmployee", "sTitle": "IDEmployee", "sClass": "left"},
                    {"mData": "FullName", "sTitle": "FullName", "sClass": "left"},
                    {"mData": "JobGroup", "sTitle": "Group", "sClass": "left"},
                    {"mData": "LoanDate", "sTitle": "LoanDate", "sClass": "left", sType: 'date-eu'},
                    {"mData": "Amount", "sTitle": "Amount", "sClass": "right"},
                    {"mData": "InterestLaon", "sTitle": "Insterest %", "sClass": "right"},
                    {"mData": "InterestInstalment", "sTitle": "Insterest per month", "sClass": "right"},
                    {"mData": "Instalment", "sTitle": "Instalment", "sClass": "right"},
                    {"mData": "Term", "sTitle": "Term", "sClass": "right"},
                    {"mData": "DateInstalment", "sTitle": "DateInstalment", "sClass": "left", sType: 'date-eu'},
                    {"mData": "Note", "sTitle": "Note", "sClass": "left"},
                    {"mData": "ID", "sTitle": "Param", "sClass": "clickdata", "bVisible": true}

                ],
                "fnDrawCallback": function (oSettings) {


                    $("#tableajaxpaidoff tbody tr").on('mouseenter', function () {
                        $('#tableajaxpaidoff tbody tr').addClass("selectable");

                    });
                    $('#tableajaxpaidoff tbody tr').on('click', function () {
                        var nTds = $('td', this);
                        var id = $(nTds[11]).text();
                        detail2(id)
                        return false;
                    });

                }

            });
            
             function detail2(id) {
                var url = "<?php echo site_url('mst02/home/index_detail') ?>" + "/" + id;
                $("#tableajax3").load(url);
                return false;

            }

            function detail(id) {
                var url = "<?php echo site_url('mst02/home/index_detail') ?>" + "/" + id;
                $("#tableajax2").load(url);
                return false;

            }

        });


        function cek() {

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
        }



        function reply_click(clicked_id)
        {

            var str = clicked_id;
            var explode = str.split('-');
            var button = explode[0];
            var id = explode[1];

            var url_add = "<?php echo site_url('mst02/home/addnew') ?>";
            var url_edit = "<?php echo site_url('mst02/home/edit') ?>";
            var url_schedule = '<?php echo site_url('mst02/home/schedule') ?>' + "/" + id;


            //                        alert(keterangan);
            if (button == 'btn_add') {
                $('#loan-modal').load(url_add, '', function () {
                    $('#loan-modal').modal().draggable({
                        handle: ".modal-body"
                    });
                });


            } else if (button == 'btn_edit') {

                $('#loan-modal').load(url_edit + '/' + id, '', function () {
                    $('#loan-modal').modal().draggable({
                        handle: ".modal-body"
                    });
                });

            } else if (button == 'btn_delete') {
                $(document).ready(function ()
                {

                    var contentdel = $("#content");
                    var site = 'mod_fieldpayroll/index.php/mst02/home';
                    var urldel = ROOT.base_url + site;

                    $("#textconfirm").show();
                    $(function () {
                        $("#dialog-confirm").dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                "Delete ": function () {
                                    $.ajax({
                                        type: "POST",
                                        url: '<?php echo site_url('mst02/home/removedata') ?>' + '/' + id,
                                        dataType: "json",
                                        cache: false,
                                        success:
                                                function (data) {
                                                    if (data.valid == 'true') {
                                                        $("#tableajaxpaidon").dataTable().fnStandingRedraw();
                                                    } else {
                                                        $.gritter.add({
                                                            title: 'WARNING',
                                                            text: data.mesg,
                                                            image: '<?php echo $base_url . 'public/theme/images/warni.jpeg' ?>',
                                                            class_name: 'gritter-light',
                                                            fade_in_speed: 100,
                                                            fade_out_speed: 100,
                                                            time: 2500
                                                        });
                                                    }


                                                },
                                        error:
                                                function (xhr, ajaxOptions, thrownError) {
                                                    alert(xhr.status);
                                                    alert(thrownError);
                                                }
                                    });

                                    $(this).dialog("close");
                                },
                                Cancel: function () {
                                    $(this).dialog("close");

                                }
                            }
                        });
                    });


                });
            } else if (button == 'btn_excel') {
                window.location.href = '<?php echo site_url('mst02/home/exportdata') ?>';

            } else if (button == 'btn_schedule') {

                $("#textfinished").hide();
                $('#processdata-modal').modal();
                $("#textloading").show();
                $(":button:contains('Finish')").attr("disabled", true).addClass("ui-state-disabled");

                $.ajax({
                    type: "POST",
                    url: url_schedule,
                    dataType: "json",
                    data: '',
                    cache: false,
                    success:
                            function (data) {
                                if (data.valid == 'true') {
                                    $("#textloading").hide();
                                    $("#textfinished").show();
                                    $(":button:contains('Finish')").attr("disabled", false).removeClass("ui-state-disabled");

                                    var content = $("#tableajax2");
                                    var url = ROOT.base_url + 'mod_fieldpayroll/index.php/mst02/home/index_detail' + "/" + id;
                                    content.load(url);
                                    $('#processdata-modal').modal('hide');
                                } else {
                                    $("#textloading").hide();
                                    $("#textfinished").show();
                                    $(":button:contains('Finish')").attr("disabled", false).removeClass("ui-state-disabled");
                                    $('#processdata-modal').modal('hide');
                                    $.gritter.add({
                                        title: 'WARNING',
                                        text: data.mesg,
                                        image: '<?php echo $base_url . 'public/theme/images/warni.jpeg' ?>',
                                        class_name: 'gritter-light',
                                        fade_in_speed: 100,
                                        fade_out_speed: 100,
                                        time: 10000
                                    });
                                }

                            },
                    error:
                            function (xhr, ajaxOptions, thrownError) {
                                alert(xhr.status);
                                alert(thrownError);
                            }
                });

            }


        }
    </script>



