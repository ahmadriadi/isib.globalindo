<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<style>
    .accept{color: #00CC00;font-weight: bold;}
    .waiting{color: #EC5800;font-weight: bold;}
    .reject{color: #ee1e2d;font-weight: bold;}
    a.ui-dialog-titlebar-close { display:block; }
</style>
<?php $base_url = $this->session->userdata('sess_base_url'); ?>
<!-- DataTables Plugin --> 
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css" rel="stylesheet" />
<!-- <link href="<?php //echo $base_url       ?>public/theme/scripts/plugins/system/jquery-ui/development-bundle/demos/demos.css" rel="stylesheet" />
<!-- JQueryUI -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-latest.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<!-- JQueryUI Time -->
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/time/timepicker.css" rel="stylesheet" />
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/time/timepicker.js"></script>
<!-- Bootstrap -->
<script src="<?php echo $base_url; ?>public/bootstrap/js/bootstrap.js"></script>
<!-- DataTables Tables Plugin -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
<!-- Gritter Notifications Plugin -->
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>  
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />
<style>   
    .sickness{color: #EC5800;font-weight: bold;}
</style>


<div class="widget">

    <div class="widget-head">
        <div class="row-fluid">
            <div class="span6">
                <h4 class="heading">Data Log Machine</h4>                
            </div>
            <div class="span6" style="text-align: right;">
                <button onclick="reloadpage()" class="btn btn-small btn-default"><i class="icon-refresh"></i></button>
                <button onclick="backtohome()" class="btn btn-small btn-success btn-icon glyphicons home"><i></i>Back to Home</button>                
            </div>
        </div>
    </div>
    <div class="widget-body">
        <div class="btn-group"> 
            <button id='btn_add' onClick="reply_click(this.id)"  class="btn" id="editable-sample_new">
                ADD <i class="icon-plus"></i>
            </button>            
        </div>

        <!-- Table -->
        <table id="tableajax" cellspacing="1" cellpadding="1" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable" >

        </table>

    </div>
    <div id="user-form" 
         title="FORM USER" 
         >
    </div>
    <div id="dialog-confirm" title="DELETE REQUEST">
        <p id="textconfirm" style="display:none;">
            Are you sure want to delete this data?
        </p>   
    </div>

    <script type="text/javascript">
                   
                    function reloadpage() {
                        var content = $("#content .innerLR");
                        var url = ROOT.base_url + 'mod_security/index.php/trx11/home/';
                        //alert(url);
                        content.load(url);
                    }
                    function backtohome() {
                        window.location.href = "<?php echo $base_url; ?>";
                    }
                    $(document).ready(function() {


                        var dataajax = '<?php echo site_url('trx11/home/getdatatable') ?>';

                        //alert(dataajax);
                        var oTable = $('#tableajax').dataTable({
                            "bJQueryUI": false,
                            "bSortClasses": false,
                            "aaSorting": [[4, "desc"]],
                            "bAutoWidth": true,
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
                                {
                                    "mData": "ID", "sTitle": "Action",
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
                                {"mData": "FullName", "sTitle": "Name", "sClass": "left"},
                                {"mData": "DateTruncate", "sTitle": "Date", "sClass": "left"},
                                {"mData": "Note", "sTitle": "Note", "sClass": "left"},
                            ],
                            "fnDrawCallback": function(oSettings) {
                                $("#tableajax tbody tr ").on('click', 'button', function() {

                                    // alert(aRow);
                                    var button = $(this).attr("btn");
                                    var id = $(this).attr("idbtn");
                                    reply_click(button + '-' + id);

                                })


                                $("#tableajax tbody tr").on('mouseenter', function() {
                                    $('#tableajax tbody tr').addClass("selectable");

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
                        if (button == 'btn_add') {
                            var content = $("#content");
                            var site = "mod_security/index.php/trx11/home/addnew";
                            var url = ROOT.base_url + site;
                            content.load(url);
                        } else if (button == 'btn_edit') {
                            var content = $("#content");
                            var site = "mod_security/index.php/trx11/home/edit";
                            var url = ROOT.base_url + site + "/" + id;
                            content.load(url);
                            
                        } else if (button == 'btn_delete') {
                            $(document).ready(function()
                            {
                                var contentdel = $("#content");
                                var site = 'mod_security/index.php/trx11/home';
                                var urldel = ROOT.base_url + site;

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
                                                    url: '<?php echo site_url('trx11/home/delete') ?>' + '/' + id,
                                                    dataType: "json",
                                                    cache: false,
                                                    success:
                                                            function(data) {

                                                                contentdel.load(urldel);

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
                        } else if (button == 'btn_export') {
                            window.location.href = '<?php echo site_url('trx11/home/exportdata') ?>';

                        } else if (button == 'btn_print') {



                            $(document).ready(function()
                            {
                                var url_print = '<?php echo site_url('trx11/home/iframe') ?>';
                                //alert(url_print + "/" + id);
                                $.ajax({
                                    type: "POST",
                                    url: url_print + "/" + id,
                                    success: function(data) {
                                        $('#leave-form').html(data);
                                        $("#leave-form").dialog("open");
                                        return false;
                                    }
                                });
                            });
                        }



                    }
    </script>

    <div id="leave-form" 
         title="USER LOGIN" 
         >
    </div>

