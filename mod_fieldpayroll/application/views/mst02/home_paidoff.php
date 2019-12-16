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
    <div class="widget-body">
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
</div>

<script type="text/javascript">
    
        function reloadpage() {
            var content = $("#content .innerLR");
            var url = ROOT.base_url + 'mod_fieldpayroll/index.php/mst02/home/tabmenu' + '/' + '<?php echo $flag;?>';
            content.load(url);
        }
    
    $(document).ready(function () {
         cek();
        
        
        var dataajaxpaidoff = '<?php echo site_url('mst02/home/getdatatable_paidoff') ?>';
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