<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<?php $base_url = $this->session->userdata('sess_base_url'); ?>
<!-- DataTables Plugin --> 
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css" rel="stylesheet" />
<!-- <link href="<?php //echo $base_url   ?>public/theme/scripts/plugins/system/jquery-ui/development-bundle/demos/demos.css" rel="stylesheet" />
<!-- JQueryUI -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-latest.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>

<!-- DataTables Tables Plugin -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/api/fnStandingRedraw.js"></script>
<div class="widget">

    <div class="widget-head">
        <div class="row-fluid">
            <div class="span6">
                <h4 class="heading">Data Parameter Personal Payroll</h4>                
            </div>
            <div class="span6" style="text-align: right">
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


                function reloadpage() {
                    var content = $("#content .innerLR");
                    var url = ROOT.base_url + 'mod_fieldpayroll/index.php/ref04/home/';
                    //alert(url);
                    content.load(url);
                        }
                function backtohome() {
                    window.location.href = "<?php echo $base_url; ?>";
                }
            
                $(document).ready(function() {


                    var dataajax = '<?php echo site_url('ref04/home/getdatatables') ?>';

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
                            },
                            {"mData": "SumDaySalary", "sTitle": "Sum Day of Monthly Salary", "sClass": "left"},
                            {"mData": "OvertimeWorkHour", "sTitle": "Sum Hour of Overtime ", "sClass": "left"},
                            {"mData": "InsurancePercent", "sTitle": "BPJS Tenaga Kerja Percent", "sClass": "left"},
                            {"mData": "BPJSPercent", "sTitle": "BPJS Kesehatan Percent", "sClass": "left"},
                            {"mData": "Note", "sTitle": "Note", "sClass": "left"}
                        ],
                        "fnDrawCallback": function(oSettings) {
                            $("#tableajax tbody tr ").on('click', 'button', function() {

                                var button = $(this).attr("btn");
                                var id = $(this).attr("idbtn");

                                reply_click(button + '-' + id)

                            })
                           
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
                        var site = "mod_fieldpayroll/index.php/ref04/home/addnew";
                        var url = ROOT.base_url + site;
                        //alert(url);
                        content.fadeOut("slow", "linear");
                        content.load(url);
                        content.fadeIn("slow");


                    } else if (button == 'btn_edit') {
                        var content = $("#content");
                        var site = "mod_fieldpayroll/index.php/ref04/home/edit";
                        var url = ROOT.base_url + site + "/" + id;
                        content.fadeOut("slow", "linear");
                        content.load(url);
                        content.fadeIn("slow");

                    } else if (button == 'btn_delete') {
                        $(document).ready(function()
                        {
                            var contentdel = $("#content");
                            var site = 'mod_fieldpayroll/index.php/ref04/home';
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
                                                url: '<?php echo site_url('ref04/home/delete') ?>' + '/' + id,
                                                dataType: "json",
                                                cache: false,
                                                success:
                                                        function(data) {
                                                            $("#tableajax").dataTable().fnStandingRedraw(); 
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
                        window.location.href = '<?php echo site_url('ref04/home/exportdata') ?>';

                    }


                }
    </script>

