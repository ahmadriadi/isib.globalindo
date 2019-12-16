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


<div class="btn-group">	 

    <p>
    <table>
        <tr>
            <td>
                <label>Fromdate</label> <input type="text" name="fromdate" id="fromdate" class='span2' value='<?php echo $default['from'] ?>' /> 
            </td>
            <td>                        
                <label>Untildate</label><input type="text" name="untildate" id="untildate" class='span2' value='<?php echo $default['until'] ?>' />
            </td>
        </tr>
    </table>
</p>

<p>
	 <button id='btn_generate' onClick="reply_click(this.id)"  class="btn" id="editable-sample_new">
        GENERATE <i class="icon-plus"></i>
    </button>     
    <button id='btn_add_interest' onClick="reply_click(this.id)"  class="btn" id="editable-sample_new">
        ADD <i class="icon-plus"></i>
    </button> 
    <button id='btn_excel_interest' onClick="reply_click(this.id)"  class="btn" id="editable-sample_new">
        EXCEL <i class="icon-plus"></i>
    </button>				                 
</p>

</div>
<!-- Table -->
<table width="100%" id="tableajaxinterest" width="100%" cellspacing="1" cellpadding="1" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable" >

</table> 


<script type="text/javascript">
    function reloadpage() {
            var content = $("#content .innerLR");
            var url = ROOT.base_url + 'mod_fieldpayroll/index.php/mst02/home/tabmenu' + '/' + '<?php echo $flag;?>';
            content.load(url);
        }
    


    $(document).ready(function () {
 cek();

        var dataajaxinterest = '<?php echo site_url('mst02/home/getdatatable_interest') ?>';


        var oTable = $('#tableajaxinterest').dataTable({
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
            "sAjaxSource": dataajaxinterest,
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
                                                        <button btn='btn_edit_interest' idbtn=" + aoData + "  type='button' class='btn btn-mini btn-warning'><i class='icon-pencil icon-white'></i></button>\n\
                                                        <button btn='btn_delete_interest' idbtn=" + aoData + "  type='button' class='btn btn-mini btn-danger'><i class='icon-trash'></i></button>\n\
                                                        </div>\n\
                                                        ";
                    }
                },               
                {"mData": "IDEmployee", "sTitle": "IDEmployee", "sClass": "left"},
                {"mData": "FullName", "sTitle": "FullName", "sClass": "left"},
                {"mData": "JobGroup", "sTitle": "Group", "sClass": "left"},
                {"mData": "PostingDate", "sTitle": "Posting Date", "sClass": "left", sType: 'date-eu'},
                {"mData": "Amount", "sTitle": " Loan Interest", "sClass": "right"},
                {"mData": "Note", "sTitle": "Note", "sClass": "left"},
            ],
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
			
				
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?php echo site_url('mst02/home/checkstatusprocess') ?>",
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
                $("#tableajaxinterest tbody tr ").on('click', 'button', function () {

                    var button = $(this).attr("btn");
                    var id = $(this).attr("idbtn");
                    reply_click(button + '-' + id);


                })

                $("#tableajaxinterest tbody tr").on('mouseenter', function () {
                    $('#tableajaxinterest tbody tr').addClass("selectable");

                });

                $("#tableajaxinterest tbody tr ").on('mouseenter', 'button', function () {
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



        $("#untildate").datepicker({dateFormat: "dd-mm-yy"});
        $("#fromdate").datepicker({dateFormat: "dd-mm-yy"});

        var url_periode = '<?php echo site_url('mst02/home/set_pattern_date') ?>';
        var content = $("#content");
        var site = "mod_fieldpayroll/index.php/mst02/home";
        var urlsite = ROOT.base_url + site;

        $("#fromdate").change(function () {
            var fromdate = $("#fromdate").val();
            var untildate = $("#untildate").val();
            //alert(url_periode+'/'+fromdate+'/'+untildate);
            $.ajax({
                type: "POST",
                url: url_periode,
                dataType: "json",
                data: "fromdate=" + fromdate + "&untildate=" + untildate,
                cache: false,
                success:
                        function (data) {
                            if (data.valid) {
                                content.fadeOut("slow", "linear");
                                content.load(urlsite);
                                content.fadeIn("slow");

                            }
                        }
            });
        }); //end from

        $("#untildate").change(function () {
            var fromdate = $("#fromdate").val();
            var untildate = $("#untildate").val();
            $.ajax({
                type: "POST",
                url: url_periode,
                dataType: "json",
                data: "fromdate=" + fromdate + "&untildate=" + untildate,
                cache: false,
                success:
                        function (data) {
                            if (data.valid) {
                                content.fadeOut("slow", "linear");
                                content.load(urlsite);
                                content.fadeIn("slow");
                            }
                        }
            });
        }); //end until    



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



</script>  

