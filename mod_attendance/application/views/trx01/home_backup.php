<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
        <?php  $base_url = $this->session->userdata('sess_base_url'); ?>
<!-- DataTables Plugin --> 
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css" rel="stylesheet" />
<!-- <link href="<?php //echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/development-bundle/demos/demos.css" rel="stylesheet" />
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
        <h4 class="heading">Data Overtime</h4>
    </div>
    <div class="widget-body">
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
            <button id='btn_add' onClick="reply_click(this.id)"  class="btn" id="editable-sample_new">
                   ADD <i class="icon-plus"></i>
            </button>
           <button id='btn_export' onClick="reply_click(this.id)"  class="btn" id="editable-sample_new">
                   EXCEL<i class="icon-download"></i>
            </button> 
             </p>
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
                
            
                    var dataajax = '<?php echo site_url('trx01/home/dataovertime') ?>';
                    
                   //alert(dataajax);
                    var oTable = $('#tableajax').dataTable({
                        "bJQueryUI": true,
                        "bSortClasses": false,
                        "aaSorting": [[ 1, "asc" ]],
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
                                                    <button btn='btn_check' idbtn=" + aoData + "  type='button' class='btn btn-mini btn-info'><i class='icon-check'></i></button>\n\
                                                    </div>\n\
                                                    ";
                                }
                            },
                            {   
                                "mData": "CheckData", "sTitle": "Check","sClass": "center",
                                "bSortable": false,
                                "mRender": function(aoData) {  
                                    if (aoData == 1){
                                         return "<input type='checkbox' checked  value=" + aoData + " > ";                                        
                                    }else if(aoData == 0){
                                         return "<input type='checkbox' value=" + aoData + " > ";
                                    }



                                }
                            },
                            {"mData": "ID", "sTitle": "ID SPKL", "sClass": "left"},
                            {"mData": "IDSPKL", "sTitle": "Sub SPKL", "sClass": "left"},
                            {"mData": "IDEmployee", "sTitle": "IDEmployee", "sClass": "left"},
                            {"mData": "PresenceDate", "sTitle": "PresenceDate", "sClass": "left"},
                            {"mData": "OvertimeIn", "sTitle": "OvertimeIn", "sClass": "left"},
                            {"mData": "OvertimeOut", "sTitle": "OvertimeOut", "sClass": "left"},
                            {"mData": "OvertimeHour", "sTitle": "OvertimeHour","sType":"formatted-num", "sClass": "right"},                            
                            {"mData": "Note", "sTitle": "Note", "sClass": "left"},
                            
                          
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
                                              url: '<?php //echo site_url('trx01/home/get_access') ?>',
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
                    
                   
                    
                    
                    
                    $("#untildate").datepicker( {dateFormat:"dd-mm-yy"});
                    $("#fromdate").datepicker( {dateFormat:"dd-mm-yy"});
                    
                    
                        var url_periode = '<?php echo site_url('trx01/home/set_pattern_date') ?>';
                        var content = $("#content");
                        var site = "mod_attendance/index.php/trx01/home";
                        var urlsite = ROOT.base_url + site;
                        
                         
                        
                        
                        $("#fromdate").change(function() {                           
                            var fromdate=$("#fromdate").val();
                            var untildate=$("#untildate").val();                            
                            //alert(url_periode+'/'+fromdate+'/'+untildate);
                            $.ajax({
                                type: "POST",
                                url: url_periode,
                                dataType: "json",
                                data: "fromdate="+fromdate+"&untildate="+untildate,
                                cache:false,
                                success:
                                    function(data){
                                    if(data.valid){                                      
                                         content.fadeOut("slow", "linear");
                                         content.load(urlsite);
                                         content.fadeIn("slow");
                                       
                                    }
                                }
                            });
                        }); //end from

                        $("#untildate").change(function() {
                            var fromdate=$("#fromdate").val();
                            var untildate=$("#untildate").val();
                            $.ajax({
                                type: "POST",
                                url: url_periode,
                                dataType: "json",
                                data: "fromdate="+fromdate+"&untildate="+untildate,
                                cache:false,
                                success:
                                    function(data){
                                    if(data.valid){
                                        content.fadeOut("slow", "linear");
                                        content.load(urlsite);
                                        content.fadeIn("slow");
                                    }
                                }
                            });
                        }); //end until     
                        

                });
                
                
                     
                    

                    function reply_click(clicked_id)
                    {
                        var str = clicked_id;
                        var explode = str.split('-');
                        var button = explode[0];
                        var id = explode[1];



                        if (button == 'btn_add') {                          
                            
                         

                            var content = $("#content");
                            var site = "mod_attendance/index.php/trx01/home/addnew";
                            var url = ROOT.base_url + site;
                            //alert(url);
                            content.fadeOut("slow", "linear");
                            content.load(url);
                            content.fadeIn("slow");


                        } else if (button == 'btn_edit') {
                            var content = $("#content");
                            var site = "mod_attendance/index.php/trx01/home/edit";
                            var url = ROOT.base_url + site + "/" + id;
                            content.fadeOut("slow", "linear");
                            content.load(url);
                            content.fadeIn("slow");

                        } else if (button == 'btn_check') {
                            var content = $("#content");
                            var site = "mod_attendance/index.php/trx01/home/checkdata";
                            var url = ROOT.base_url + site + "/" + id;
                            content.fadeOut("slow", "linear");
                            content.load(url);
                            content.fadeIn("slow");

                        }else if (button == 'btn_delete') {
                            $(document).ready(function()
                            {
                                var contentdel = $("#content");
                                var site = 'mod_attendance/index.php/trx01/home';
                                var urldel = ROOT.base_url + site;
                                
                                
                               // alert('<?php echo site_url('trx01/home/delete') ?>'+'/'+id);
                                
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
                                                    url: '<?php echo site_url('trx01/home/delete') ?>'+'/'+id,
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
                            window.location.href = '<?php echo site_url('trx01/home/exportdata') ?>';

                        }


                    }
</script>

