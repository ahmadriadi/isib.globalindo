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
<!-- JQueryUI Time -->
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/time/timepicker.css" rel="stylesheet" />
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/time/timepicker.js"></script>
<!-- Bootstrap -->
<script src="<?php echo $base_url ?>public/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

<!-- DataTables Tables Plugin -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/api/fnStandingRedraw.js"></script>
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/js/listbox_paging.js"></script>
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/api/FilterAll.js"></script>

<!-- Gritter Notifications Plugin -->
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>  
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />
<style>
    .accept{color: #00CC00;font-weight: bold;}
    .waiting{color: #EC5800;font-weight: bold;}
    .reject{color: #ee1e2d;font-weight: bold;}
</style>


<div class="widget">

    <div class="widget-head">
        <div class="row-fluid">
            <div class="span6">
                <h4 class="heading">Data Change IDEmployee</h4>                
            </div>
            <div class="span6" style="text-align: right">
                <button onclick="reloadpage()" class="btn btn-small btn-default"><i class="icon-refresh"></i></button>
                <button onclick="backtohome()" class="btn btn-small btn-success btn-icon glyphicons home"><i></i>Back to Home</button>
            </div>
        </div>
    </div>
    <div class="widget-body">
	<div class="alert alert-error">
            <button class="close" data-dismiss="alert" type="button">×</button>
            <strong>Warning !
               The Current Counter for TIS : <?php echo $countertis; ?>  and Other :  <?php echo $counteros; ?>
            </strong>
        </div>
         <div class="btn-group">
            <p>    
                <button id='btn_add' onClick="reply_click(this.id)"  class="btn" id="editable-sample_new">
                    ADD <i class="icon-plus"></i>
                </button>
                 <button id='btn_process' onClick="reply_click(this.id)"  class="btn btn-primary" id="editable-sample_new">
                    PROCESS CHANGE <i class="icon-time"></i>
                </button>
             
            </p>
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
            Are you sure for delete this data?
        </p>   
    </div>
   
    <div id="processdata-modal" class="modal hide fade" tabindex="-1"> 
    <div class="modal-header">
        <h3>Process Change</h3>
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
    
    
    <div id="dialog-process" title="Process Change IDEmployee" height="100">
        <p id="comfirmprocess" style="display:none;">
          Apakah anda yakin untuk melakukan proses ini ?<br/>
          Proses ini akan merubah data-data yang berkaitan dengan <br/>IDEmployee lama seperti :<br/>
          1.Username Login memakai NIP Baru dan Password :123 pada Aplikasi Employee Center.<br/>
          2.Data Personal.<br/>
          3.Cardmap.<br/>
          4.Absensi.<br/>
          5.Lemburan.<br/>
          6.Absensi tidak lengkap.<br/>
          7.Perjalana Dinas.<br/>
          8.Ijin Meninggalkan kantor.<br/>
          9.Skorsing.<br/>
          10.Ijin untuk kuliah.<br/>
          11.Cuti.<br/>
          12.Payroll.<br/>
	  13.Estimator.<br/> 	
         
        </p>   
    </div>

    <script type="text/javascript">
                     <?php 
                            foreach ($buttons->result() as $btn){
                                //echo "alert('test');";

                                if ($btn->access == "0"){
                                    echo "$(\"button#$btn->kdbutton\").prop('disabled',true);";
                                }
                                if ($btn->access == "1"){
                                    echo "$(\"button#$btn->kdbutton\").prop('disabled',false);";
                                }
                            }
                            ?>
            
                    function reloadpage() {
                        var content = $("#content .innerLR");
                        var url = ROOT.base_url + 'mod_attendance/index.php/mst04/home/';
                        //alert(url);
                        content.load(url);
                    }
                    function backtohome() {
                        window.location.href = "<?php echo $base_url; ?>";
                    }
                   
                    $(document).ready(function() {


                        var dataajax = '<?php echo site_url('mst04/home/getdatatables') ?>';

                        //alert(dataajax);
                        var oTable = $('#tableajax').dataTable({
                            "bJQueryUI": false,
                            "bSortClasses": false,
                            "aaSorting": [[4, "asc"]],
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
                                                        <button btn='btn_delete' idbtn=" + aoData + "  type='button' class='btn btn-mini btn-danger'><i class='icon-trash'></i></button>\n\
                                                        </div>\n\
                                                        ";
                                    }
                                },
                                {"mData": "NIPLama", "sTitle": "Old IDEmployee", "sClass": "left"},
                                {"mData": "NIPBaru", "sTitle": "New IDEmployee", "sClass": "left"},
                                {"mData": "FullName", "sTitle": "Name", "sClass": "left"},
                                {"mData": "Note", "sTitle": "Note", "sClass": "left"},
                            ],
                            "fnDrawCallback": function(oSettings) {
                                $("#tableajax tbody tr ").on('click', 'button', function() {

                                    var button = $(this).attr("btn");
                                    var id = $(this).attr("idbtn");
                                    reply_click(button + '-' + id);


                                });
                                   $("#tableajax tbody tr ").on('mouseenter','button', function() {                                   
                                     var button = $(this).attr("btn");                                
                                  
                                     $.ajax(
                                     {
                                        type: "POST",
                                        url: '<?php echo site_url('mst04/home/get_access') ?>',
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


                    });


                    function reply_click(clicked_id)
                    {
                        var str = clicked_id;
                        var explode = str.split('-');
                        var button = explode[0];
                        var id = explode[1];
                        var status = explode[2];
			var group = $("#group").val();
			var statuscard = $("#status").val();


                        if (button == 'btn_add') {
                            var content = $("#content");
                            var site = "mod_attendance/index.php/mst04/home/addnew";
                            var url = ROOT.base_url + site;
                            //alert(url);
                            content.fadeOut("slow", "linear");
                            content.load(url);
                            content.fadeIn("slow");


                        } else if (button == 'btn_process') {

                          $(document).ready(function()
                            {
                              
                                $("#comfirmprocess").show();
                                $(function() {
                                    $("#dialog-process").dialog({
                                        resizable: false,
                                        height:'auto',
                                        weight:'50%',
                                        modal: true,
                                        buttons: {
                                            "Process ": function() {
                                                
                                                    $("#textfinished").hide();
                                                    $('#processdata-modal').modal({
                                                        backdrop: 'static', // for disable close
                                                        keyboard: false //for disable escp
                                                    });
                                                    $("#textloading").show();
                                                    $(":button:contains('Finish')").attr("disabled", true).addClass("ui-state-disabled");  
                                                  
                                            
                                                $.ajax({
                                                    type: "POST",
                                                    url: '<?php echo site_url('mst04/home/change_idemployee') ?>',
                                                    dataType: "json",
                                                    cache: false,
                                                    success:
                                                            function(data) {
                                                            $("#textloading").hide();
                                                            $("#textfinished").show();
                                                            $(":button:contains('Finish')").attr("disabled", false).removeClass("ui-state-disabled");
                                                            $('#processdata-modal').modal('hide');
                                                            $("#tableajax").dataTable().fnStandingRedraw(); 
                                                            reloadpage();
                                                            },
                                                    error:
                                                            function(xhr, ajaxOptions, thrownError) {
                                                                $("#textloading").hide();
                                                                $("#textfinished").show();
                                                                $(":button:contains('Finish')").attr("disabled", false).removeClass("ui-state-disabled");
                                                                $('#processdata-modal').modal('hide');
                                                                alert(xhr.status);
                                                                alert(thrownError);
                                                            }
                                                });

                                                $(this).dialog("close");
                                            },
                                            "Cancel": function() {
                                                $(this).dialog("close");

                                            }
                                        }
                                    });
                                });


                            });

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
                                                    url: '<?php echo site_url('mst04/home/delete') ?>' + '/' + id,
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
                        } else if (button == 'btn_excel') {
                             window.location.href = '<?php echo site_url('mst04/home/exportdata') ?>' + '/' + group+'/'+statuscard;

                        }


                    }
    </script>



