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
<!-- Bootstrap -->
<script src="<?php echo $base_url; ?>public/bootstrap/js/bootstrap.js"></script>
<!-- DataTables Tables Plugin -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/js/listbox_paging.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/api/FilterAll.js"></script>

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
                <h4 class="heading">Data Payroll Slip</h4>                
            </div>
            <div class="span6" style="text-align: right">
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
                       <div class="row-fluid">
                        <label>Job Group</label>
                        <select class=" span16" data-style="btn-inverse" id="group" name="group" >
                             <?php foreach ($default['group'] as $row) { ?>
                                 <option value="<?php echo (isset($row['value'])) ? $row['value'] : ''; ?>" 
                                         <?php echo (isset($row['selected'])) ? $row['selected'] : ''; ?> >
                                     <?php echo (isset($row['display'])) ? $row['display'] : ''; ?></option>
                             <?php } ?>
                         </select>  
                         </div>
                    </td>	
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
                <button id='btn_excel' onClick="reply_click(this.id)"  class="btn" id="editable-sample_new">
                    EXCEL<i class="icon-download"></i>
                </button> 
            </p>
        </div>

        <!-- Table -->
        <table id="tableajax" cellspacing="1" cellpadding="1" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable dynamicTable" >

        </table>

    </div>
   
    <div id="dialog-confirm" title="DELETE REQUEST">
        <p id="textconfirm" style="display:none;">
            Are you sure for delete this data?
        </p>   
    </div>

    <script type="text/javascript">
                        function reloadpage() {
                            var content = $("#content .innerLR");
                            var url = ROOT.base_url + 'mod_fieldpayroll/index.php/trx07/home/';
                            //alert(url);
                            content.load(url);
                        }
                        function backtohome() {
                            window.location.href = "<?php echo $base_url; ?>";
                        }
                        $('body').on('click', function(e) {
                            $('[data-toggle="popover"]').each(function() {
                                //the 'is' is for buttons that trigger popups
                                //the 'has' is for icons within a button that triggers a popup
                                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                                    $(this).popover('hide');
                                }
                            });
                        });
                        $(document).ready(function() {

                            cek();
                            var dataajax = '<?php echo site_url('trx07/home/resultpayrollslip') ?>';

                            var oTable = $('#tableajax').dataTable({
                                "bJQueryUI": false,
                                "bSortClasses": false,
                                "aaSorting": [[2, "asc"],[4,"desc"]],
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
                                                        <button btn='btn_print' idbtn=" + aoData + "  type='button' class='btn btn-mini btn-primary'><i class='icon-print'></i></button>\n\
                                                        </div>\n\
                                                        ";
                                        }
                                    },
                                    {"mData": "IDEmployee", "sTitle": "IDEmployee", "sClass": "left"},
                                    {"mData": "FullName", "sTitle": "FullName", "sClass": "left"},
				    {"mData": "IDJobGroup", "sTitle": "ID Group", "sClass": "left"},
                                    {"mData": "PostingDate", "sTitle": "PostingDate", "sClass": "center", sType: 'date-eu'},                                  
                                    {"mData": "SumDailySalaryPayment", "sTitle": "SumDailySalaryPayment", "sClass": "right"},
                                    {"mData": "SumDailyIncentiveShift", "sTitle": "SumDailyIncentiveShift", "sClass": "right"},
                                    {"mData": "SumDailyOvertimePayment", "sTitle": "SumDailyOvertimePayment", "sClass": "right"},
                                    {"mData": "OtherIncome", "sTitle": "OtherIncome", "sClass": "right"},
                                    {"mData": "InsurancePayment", "sTitle": "InsurancePayment", "sClass": "right"},
                                    {"mData": "AbsencePayment", "sTitle": "AbsencePayment", "sClass": "right"},
                                    {"mData": "LoanPayment", "sTitle": "LoanPayment", "sClass": "right"},
                                    {"mData": "OutstandingPayment", "sTitle": "OutstandingPayment", "sClass": "right"},
                                    {"mData": "OtherPayment", "sTitle": "OtherPayment", "sClass": "right"},
                                    {"mData": "TotalIncome", "sTitle": "TotalIncome", "sClass": "right"},
                                    {"mData": "TotalDeduction", "sTitle": "TotalDeduction", "sClass": "right"},
                                    {"mData": "TakeHomePay", "sTitle": "TakeHomePay", "sClass": "right"},
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
                                        url: '<?php echo site_url('trx07/home/get_access') ?>',
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
                                }    
                               
                            });


                           function cek(){
                
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
                        }

                            
                            
                            $("#untildate").datepicker({dateFormat: "dd-mm-yy"});
                            $("#fromdate").datepicker({dateFormat: "dd-mm-yy"});


                            var url_periode = '<?php echo site_url('trx07/home/set_pattern_date') ?>';
                            var content = $("#content");
                            var site = "mod_fieldpayroll/index.php/trx07/home";
                            var urlsite = ROOT.base_url + site;




                            $("#fromdate").change(function() {
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
                                            function(data) {
                                                if (data.valid) {
                                                    content.fadeOut("slow", "linear");
                                                    content.load(urlsite);
                                                    content.fadeIn("slow");

                                                }
                                            }
                                });
                            }); //end from

                            $("#untildate").change(function() {
                                var fromdate = $("#fromdate").val();
                                var untildate = $("#untildate").val();
                                $.ajax({
                                    type: "POST",
                                    url: url_periode,
                                    dataType: "json",
                                    data: "fromdate=" + fromdate + "&untildate=" + untildate,
                                    cache: false,
                                    success:
                                            function(data) {
                                                if (data.valid) {
                                                    content.fadeOut("slow", "linear");
                                                    content.load(urlsite);
                                                    content.fadeIn("slow");
                                                }
                                            }
                                });
                            }); //end until  

			    $('select#group').on('change',function(){
                            var selectedValue = $(this).val();
                            
                            oTable.fnFilter(selectedValue,3,true);
                            if(selectedValue=='AL'){
                                oTable.fnFilterClear();
                            }                            
                            
                        });
	   
                        });


                        function reply_click(clicked_id)
                        {
                            var str = clicked_id;
                            var explode = str.split('-');
                            var button = explode[0];
                            var id = explode[1];
			    var group = $("#group").val(); 	
                          

                             if (button == 'btn_excel') {
                                window.location.href = '<?php echo site_url('trx07/home/exportdata') ?>'+'/'+group;

                            } else if (button == 'btn_print') {
                                $(document).ready(function()
                                {
                                    var url_print = '<?php echo site_url('trx07/home/iframe') ?>';
                                  
                                    $.ajax({
                                        type: "POST",
                                        url: url_print + "/" + id,
                                        success: function(data) {
                                            $('#slip-form').html(data);
                                            $("#slip-form").dialog("open");
                                            return false;
                                        }
                                    });
                                });
                            }


                        }
    </script>

  <div id="slip-form" 
         title="FORM SLIP GAJI" 
         >
    </div>

