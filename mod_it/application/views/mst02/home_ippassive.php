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
        width: auto;
        margin: 0 auto;
    }
    
   
</style>


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
<table  id="tableajaxpassive" cellspacing="0" cellpadding="0" class=" table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable" >

</table>

   



<script type="text/javascript">
    function reloadpage() {
            var content = $("#content .innerLR");
            var url = ROOT.base_url + 'mod_it/index.php/mst02/home/tabmenu' + '/' + '<?php echo $flag;?>';
            content.load(url);
        }
    
    $(document).ready(function () {
        cek();


        var dataajaxipactive = '<?php echo site_url('mst02/home/getdatatable') ?>'+'/ippassive';
        var oTable = $('#tableajaxpassive').dataTable({
            "bJQueryUI": false,
            "iDisplayLength": 10,
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "bSortClasses": false,
            "aaSorting": [[0, "asc"]],
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
            "sAjaxSource": dataajaxipactive,
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
                        return "<div class='btn-group'>\n\
                                                        <button btn='btn_edit' idbtn=" + aoData + "  type='button' class='btn btn-mini btn-warning'><i class='icon-pencil icon-white'></i></button>\n\
                                                        <button btn='btn_delete' idbtn=" + aoData + "  type='button' class='btn btn-mini btn-danger'><i class='icon-trash'></i></button>\n\
                                                        </div>\n\
                                                        ";
                    }
                },
                {"mData": "MacAddress", "sTitle": "Mac Address", "sClass": "left"},
                {"mData": "IPAddress", "sTitle": "IP Address", "sClass": "left"},
                {"mData": "GateWay", "sTitle": "Gateway", "sClass": "left"},
                {"mData": "DomainServer", "sTitle": "DNS", "sClass": "left"},
                {"mData": "ComputerName", "sTitle": "Computer Name", "sClass": "left"},
                {"mData": "IDEmployee", "sTitle": "IDEmployee", "sClass": "left"},
                {"mData": "FullName", "sTitle": "FullName", "sClass": "left"},
              //  {"mData": "Status", "sTitle": "Status", "sClass": "left"},
                {"mData": "Departement", "sTitle": "Departement", "sClass": "left"},
               // {"mData": "GroupName", "sTitle": "GroupName", "sClass": "left"},
                {"mData": "Note", "sTitle": "Note", "sClass": "left"},

            ],
            "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
				var id = aData.ID;             


            },
            "fnDrawCallback": function (oSettings) {
                $("#tableajaxpassive tbody tr ").on('click', 'button', function () {

                    var button = $(this).attr("btn");
                    var id = $(this).attr("idbtn");
                    reply_click(button + '-' + id);


                })

                $("#tableajaxpassive tbody tr").on('mouseenter', function () {
                    $('#tableajaxpassive tbody tr').addClass("selectable");

                });
                $('#tableajaxpassive tbody tr').on('click', function () {
                   // var nTds = $('td', this);
                   //  var id = $(nTds[12]).text();
                   //  detail(id)
                   // return false;
                });

                $("#tableajaxpassive tbody tr ").on('mouseenter', 'button', function () {
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



</script>
