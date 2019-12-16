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
<!-- Bootstrap -->
 <!--<script src="<?php echo $base_url; ?>public/bootstrap/js/bootstrap.js"></script>-->
<script src="<?php echo $base_url; ?>public/bootstrap/js/popup.js"></script>
<!-- DataTables Tables Plugin -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
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
                <h4 class="heading">Data Incomplete</h4>                
            </div>
            <div class="span6" style="text-align: right;">
                <button onclick="reloadpage()" class="btn btn-small btn-default"><i class="icon-refresh"></i></button>
                <button onclick="backtohome()" class="btn btn-small btn-success btn-icon glyphicons home"><i></i>Back to Home</button>                
            </div>
        </div>
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
        Are you sure want to delete this data?
    </p>   
</div>

<script type="text/javascript">
    $('body').on('click', function (e) {
        $('[data-toggle="popover"]').each(function () {
            //the 'is' is for buttons that trigger popups
            //the 'has' is for icons within a button that triggers a popup
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                $(this).popover('hide');
            }
        });
    });
    function reloadpage(){
        var content = $("#content .innerLR");
        var url = ROOT.base_url + 'mod_attendance/index.php/trx03/home/';
        //alert(url);
        content.load(url);
    }
    function backtohome(){
        window.location.href = "<?php echo $base_url;?>";
    }
     $(document).ready(function() {
                
            
                    var dataajax = '<?php echo site_url('trx03/home/dataincomplete') ?>';
                    
                   //alert(dataajax);
                    var oTable = $('#tableajax').dataTable({
                        "bJQueryUI": false,
                        "bSortClasses": false,
                        "aaSorting": [[2, "desc" ]],
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
                                                    <button btn='btn_print' idbtn=" + aoData + "  type='button' class='btn btn-mini btn-primary'><i class='icon-print'></i></button>\n\
                                                    </div>\n\
                                                    ";
                                }
                            },    
                            //{"mData": "IDEmployee", "sTitle": "IDEmployee", "sClass": "left"},
                            //{"mData": "FullName", "sTitle": "Name", "sClass": "left"},
                            {"mData": "Status", "sTitle": "Status", "sClass": "center"},
                            {"mData": "IncompleteDate", "sTitle": "Date", "sClass": "left",sType: 'date-eu'},
                            {"mData": "TimeIn", "sTitle": "Time In", "sClass": "left"},
                            {"mData": "TimeOut", "sTitle": "Time Out", "sClass": "left"},                                                  
                            {"mData": "Note", "sTitle": "Note", "sClass": "left"},
                            
                          
                        ],
                        "fnDrawCallback": function(oSettings) {
                            $("#tableajax tbody tr ").on('click', 'button', function() {

                                // alert(aRow);
                                var button = $(this).attr("btn");
                                var id = $(this).attr("idbtn");

                                $.ajax(
                                    {
                                        type: "POST",
                                        url: '<?php echo site_url('trx03/home/getstatus') ?>',
                                        dataType: "json",
                                        data: "id=" + id,
                                        cache: false,
                                        success:
                                                function(data, text)
                                                {
                                                    if (data.valid == 'true') {                                 
                                                         var status = data.flag; 
                                                         reply_click(button + '-' + id+'-'+status);
                                                    }
                                                    else {                                      
                                                    }

                                                },
                                        error: function(request, status, error) {
                                            alert(request.responseText + " " + status + " " + error);
                                        }
                                    });
                                    return false;   

                            })
                            /*
                              $("#tableajax tbody tr ").on('mouseenter','button', function() {                                   
                                    var button = $(this).attr("btn"); 
                                    
                                    //alert(button);
                                     $.ajax(
                                          {
                                              type: "POST",
                                              url: '<?php //echo site_url('trx03/home/get_access') ?>',
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

                            });
                            $("#tableajax tbody tr").on('mouseenter', function() {

                                $("[data-toggle='popover']").popover();
                                $("[data-toggle='tooltip']").tooltip();

                            });

                        }

                    });
                    
                   
                    
                    
                    
                    $("#untildate").datepicker( {dateFormat:"dd-mm-yy"});
                    $("#fromdate").datepicker( {dateFormat:"dd-mm-yy"});
                    
                    
                        var url_periode = '<?php echo site_url('trx03/home/set_pattern_date') ?>';
                        var content = $("#content ");
                        var site = "mod_attendance/index.php/trx03/home";
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
			var status = explode[2];
			
			 var keterangan='';
                        if(status=='0'){
                             keterangan ='<b class="waiting">Waiting</b>';
                        }else if(status=='1'){
                             keterangan ='<b class="accept">Accepted</b>';
                        }else if(status=='2'){
                             keterangan ='<b class="reject">Rejected</b>';
                        }
		
			

                        if (button == 'btn_add') {                          
                            
                         

                            var content = $("#content ");
                            var site = "mod_attendance/index.php/trx03/home/addnew";
                            var url = ROOT.base_url + site;
                            //alert(url);
                            content.fadeOut("slow", "linear");
                            content.load(url);
                            content.fadeIn("slow");


                        } else if (button == 'btn_edit') {

			   if(status =='1'){
                             $.gritter.add({                                
                                 title: 'WARNING',
                                 text: "Data has been "+keterangan+". You can't edit the data.",
                                 image: '<?php echo $base_url.'public/theme/images/warni.jpeg' ?>',
                                 class_name: 'gritter-light',                                
                                 fade_in_speed: 100, 
                                 fade_out_speed: 100, 
                                 time: 2500 
                             });
                             return false;
                           
                            }else{					
                            var content = $("#content ");
                            var site = "mod_attendance/index.php/trx03/home/edit";
                            var url = ROOT.base_url + site + "/" + id;
                            content.fadeOut("slow", "linear");
                            content.load(url);
                            content.fadeIn("slow");
			   }	

                        } else if (button == 'btn_check') {
                            var content = $("#content ");
                            var site = "mod_attendance/index.php/trx03/home/checkdata";
                            var url = ROOT.base_url + site + "/" + id;
                            content.fadeOut("slow", "linear");
                            content.load(url);
                            content.fadeIn("slow");

                        }else if (button == 'btn_delete') {
                            $(document).ready(function()
                            {
                                var contentdel = $("#content ");
                                var site = 'mod_attendance/index.php/trx03/home';
                                var urldel = ROOT.base_url + site;
                                
                               if(status =='1'){        
			             $.gritter.add({                                
			                 title: 'WARNING',
	                                 text: "Data has been "+keterangan+". You can't delete the data.",
			                 image: '<?php echo $base_url.'public/theme/images/warni.jpeg' ?>',
			                 class_name: 'gritter-light',                                
			                 fade_in_speed: 100, 
			                 fade_out_speed: 100, 
			                 time: 2500 
			             });
			             return false;
                           
                            }else{ 
                              
                                
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
                                                    url: '<?php echo site_url('trx03/home/delete') ?>'+'/'+id,
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
				}
                            });
                        } else if (button == 'btn_export') {
                            window.location.href = '<?php echo site_url('trx03/home/exportdata') ?>';

                        } else if (button == 'btn_print') {
                            
                            if(status =='0' || status =='2' ){        
                             $.gritter.add({                                
                                 title: 'WARNING',
                                 text: "Sorry, Can't Print because data has been "+keterangan,
                                 image: '<?php echo $base_url.'public/theme/images/warni.jpeg' ?>',
                                 class_name: 'gritter-light',                                
                                 fade_in_speed: 100, 
                                 fade_out_speed: 100, 
                                 time: 2500 
                             });
                             return false;
                           
                            }else{	
                        
                            $(document).ready(function()
                            {
                                var url_print = '<?php echo site_url('trx03/home/iframe') ?>';
                               //alert(url_print + "/" + id);
                                $.ajax({
                                    type: "POST",
                                    url: url_print + "/" + id,
                                    success: function(data) {
                                        $('#atl-form').html(data);
                                        $("#atl-form").dialog("open");
                                        return false;
                                    }
                                });
                            });
			 }	
                        }


                    }
</script>

<div id="atl-form" 
     title="FORM ATL" 
     >
</div>


