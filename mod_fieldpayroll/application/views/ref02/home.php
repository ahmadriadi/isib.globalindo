<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<?php $base_url = $this->session->userdata('sess_base_url'); ?>
<!-- DataTables Plugin --> 
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css" rel="stylesheet" />
<!-- <link href="<?php //echo $base_url  ?>public/theme/scripts/plugins/system/jquery-ui/development-bundle/demos/demos.css" rel="stylesheet" />
<!-- JQueryUI -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-latest"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<!-- JQueryUI Time -->
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/time/timepicker.css" rel="stylesheet" />
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/time/timepicker.js"></script>

<!-- DataTables Tables Plugin -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>

<div class="widget">

    <div class="widget-head">
        <h4 class="heading">Data Reference Deduction</h4>
    </div>
    <div class="widget-body">
        <div class="btn-group">         

            <button id='btn_add' onClick="reply_click(this.id)"  class="btn" id="editable-sample_new">
                ADD <i class="icon-plus"></i>
            </button>
            <button id='btn_export' onClick="reply_click(this.id)"  class="btn" id="editable-sample_new">
                EXCEL<i class="icon-download"></i>
            </button> 

        </div>
        <!-- Table -->
        <table id="tableajax" cellspacing="1" cellpadding="1" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable dynamicTable" >

        </table>

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

                    $(document).ready(function() {


                        var dataajax = '<?php echo site_url('ref02/home/datadeduction') ?>';

                        //alert(dataajax);
                        var oTable = $('#tableajax').dataTable({
                            "bJQueryUI": false,
                            "bSortClasses": false,
                            "aaSorting": [[0, "asc"]],
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
                                {"mData": "CodeType", "sTitle": "Code", "sClass": "left"},
                                {"mData": "Description", "sTitle": "Description", "sClass": "left"},
                                {
                                    "mData": "ID", "sTitle": "Action",
                                    "bSortable": false,
                                    "mRender": function(aoData) {
                                        return "<div class='btn-group'>\n\
                                                        <button btn='btn_edit' idbtn=" + aoData + "  type='button' class='btn btn-mini btn-warning'><i class='icon-pencil icon-white'></i></button>\n\
                                                        <button btn='btn_delete' idbtn=" + aoData + "  type='button' class='btn btn-mini btn-danger'><i class='icon-trash'></i></button>\n\\n\
                                                        </div>\n\
                                                        ";
                                    }
                                }

                            ],
                            "fnDrawCallback": function(oSettings) {
                                $("#tableajax tbody tr ").on('click', 'button', function() {

                                    // alert(aRow);
                                    var button = $(this).attr("btn");
                                    var id = $(this).attr("idbtn");

                                    reply_click(button + '-' + id)

                                })
                                /*
                                 $("#tableajax tbody tr ").on('mouseenter','button', function() {                                   
                                 var button = $(this).attr("btn"); 
                             
                                 //alert(button);
                                 $.ajax(
                                 {
                                 type: "POST",
                                 url: '<?php //echo site_url('ref02/home/get_access')  ?>',
                                 dataType: "json",
                                 data: "btn=" + button,
                                 cache: false,
                                 success:
                                 function(data, text)
                                 {
                                 if (data.valid == 'true') {                                
                                 var status = data.btnaccess; 
                                 $("button[btn='"+status+"']").prop('disabled',false);                                                 
                             
                                 }else{                                                   
                                 $("button[btn='"+button+"']").prop('disabled',true);  
                                 }
                                 },
                                 error: function(request, status, error) {
                                 alert(request.responseText + " " + status + " " + error);
                                 }
                                 });
                                 return false;
                                 })
                                 */

                                $("#tableajax tbody tr").on('mouseenter', function() {
                                    $('#tableajax tbody tr').addClass("selectable");

                                })

                            }

                        });

                    });





                    function reply_click(clicked_id)
                    {
                        var str = clicked_id;
                        var explode = str.split('-');
                        var button = explode[0];
                        var id = explode[1];



                        if (button == 'btn_add') {

                            var content = $("#content");
                            var site = "mod_fieldpayroll/index.php/ref02/home/addnew";
                            var url = ROOT.base_url + site;
                            //alert(url);
                            content.fadeOut("slow", "linear");
                            content.load(url);
                            content.fadeIn("slow");


                        } else if (button == 'btn_edit') {
                            var content = $("#content");
                            var site = "mod_fieldpayroll/index.php/ref02/home/edit";
                            var url = ROOT.base_url + site + "/" + id;
                            content.fadeOut("slow", "linear");
                            content.load(url);
                            content.fadeIn("slow");

                        } else if (button == 'btn_check') {
                            var content = $("#content");
                            var site = "mod_fieldpayroll/index.php/ref02/home/checkdata";
                            var url = ROOT.base_url + site + "/" + id;
                            content.fadeOut("slow", "linear");
                            content.load(url);
                            content.fadeIn("slow");

                        } else if (button == 'btn_delete') {
                            $(document).ready(function()
                            {
                                var contentdel = $("#content");
                                var site = 'mod_fieldpayroll/index.php/ref02/home';
                                var urldel = ROOT.base_url + site;


                                // alert('<?php //echo site_url('ref02/home/delete')  ?>'+'/'+id);

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
                                                    url: '<?php echo site_url('ref02/home/delete') ?>' + '/' + id,
                                                    dataType: "json",
                                                    cache: false,
                                                    success:
                                                            function(data) {
                                                                contentdel.fadeOut("slow", "linear");
                                                                contentdel.load(urldel);
                                                                contentdel.fadeIn("slow");
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
                            window.location.href = '<?php echo site_url('ref02/home/exportdata') ?>';

                        }


                    }
    </script>

