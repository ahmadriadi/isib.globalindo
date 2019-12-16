<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<?php $base_url = $this->session->userdata('sess_base_url'); ?>
<!-- DataTables Plugin --> 
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css" rel="stylesheet" />

<!-- JQueryUI -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-latest"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<!-- JQueryUI Touch Punch -->
<!-- small hack that enables the use of touch events on sites using the jQuery UI user interface library -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

<!-- DataTables Tables Plugin -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
<div class="widget">
    <div class="widget-head">
        <h4 class="heading">DAILY ACTIVITY <?php echo "Fromdate : ".$fdate." Untildate : ".$udate." no result data"?> </h4>
    </div>
    <div class="widget-body">
        <div class="btn-group">            
            <p>
           <table>
                <tr>
                    <td>
                        <label>Fromdate</label> <input type="text" name="fromdate" id="fromdate" class='span2' value='<?php echo $fdate ?>' /> 
                    </td>
                    <td>                        
                        <label>Untildate</label><input type="text" name="untildate" id="untildate" class='span2' value='<?php echo $udate ?>' />
                    </td>

                </tr>                
            </table>               
            <p>
                <button id='btn_add' onClick="reply_click(this.id)"  class="btn" id="editable-sample_new">
                    ADD <i class="icon-plus"></i>
                </button>
                <button id='btn_excel' onClick="reply_click(this.id)"  class="btn" id="editable-sample_new">
                    EXCEL <i class="icon-download-alt"></i>
                </button> 
            </p>
        </div>     

        <table cellspacing="1" cellpadding="1" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable tabledeliveryorder" >
            <thead>
                <tr>
                    
                    <!--<th class="center">No.</th> -->
                    <th class="center">User</th>
                    <th class="center">Date</th>                    
                    <th class="center">Activity</th>
                    <th class="center">Problem</th>
                    <th class="center">Soluion</th>
                </tr>
            </thead>
            <tbody>        

                <tr class="selectable">
                    <td class="left">
                        -
                    </td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>                       
                    <td>-</td>
                    <td>-</td>  
                                              
                </tr>

            </tbody> 
        </table>

        <p>Note : </p><p>- Please Input data with button ADD </p>       
               <p>- Please Input Fromdate and Untildate for result data</p>        
      
    </div>
</div>

<script type="text/javascript">

               $(document).ready(function(){
                        $(function()
                        {
                            /* DataTables */
                            if ($('.tabledeliveryorder').size() > 0)
                            {
                                $('.tabledeliveryorder').dataTable({
                                    "sPaginationType": "bootstrap",
                                    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
                                    "oLanguage": {
                                        "sLengthMenu": "_MENU_"
                                    },
				    "aaSorting": [[1, "desc"]],	
                                    "sScrollX": "100%",
                                    "sScrollXInner": "110%",				   
                                    "bScrollCollapse": true,
				    "bDestroy": true		
                                });
                            }
                        });

                        $("#fromdate").datepicker({dateFormat: "dd-mm-yy"});    
                        $("#untildate").datepicker({dateFormat: "dd-mm-yy"});
                       



                        var fromdate = $("#fromdate").val();
                        var untildate = $("#untildate").val();

                        var url_periode = '<?php echo site_url('trx07/home/periode_date') ?>';

                        var content = $("#content");
                        var site = "mod_security/index.php/trx07/home";
                        var urlsite = ROOT.base_url + site;
                         
                      
                       $("#fromdate").change(function() {                            
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
                            });
                      
                      
                      
                      
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
                         var site = "mod_security/index.php/trx07/home/add";
                         var url = ROOT.base_url + site;
                         content.fadeOut("slow", "linear");
                         content.load(url);
                         content.fadeIn("slow");
                     

                }
           }


</script>    

