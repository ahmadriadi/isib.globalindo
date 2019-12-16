<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<?php $base_url = $this->session->userdata('sess_base_url'); ?>


 <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" rel="stylesheet" />

<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/css/smoothness/demos.css" rel="stylesheet" />
<!-- DataTables Plugin --> 
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css" rel="stylesheet" />

<!-- JQueryUI -->
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-latest"></script>
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<!-- JQueryUI Touch Punch -->
<!-- small hack that enables the use of touch events on sites using the jQuery UI user interface library -->
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

 
<!-- DataTables Tables Plugin -->
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>

<!-- Gritter Notifications Plugin -->
 <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>  
 <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />



<div class="widget">
    <div class="widget-head">
        <h4 class="heading">DAILY ACTIVITY</h4>
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
            <p>
                <button id='btn_add' onClick="reply_click(this.id)"  class="btn" id="editable-sample_new">
                    ADD <i class="icon-plus"></i>
                </button>
                <button id='btn_excel' onClick="reply_click(this.id)"  class="btn" id="editable-sample_new">
                    EXCEL <i class="icon-download-alt"></i>
                </button> 
            </p>
        </div>     

        <table id="tablenote" cellspacing="1" cellpadding="1" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable" >
            <thead>
                <tr>
                    
                     <!--<th class="center">No.</th> -->
                    <th class="center">User</th>
                    <th class="center">Date</th>                    
                    <th class="center">Activity</th>
                    <th class="center">Level</th>
		    <th class="center">On</th>	
                    <th class="center">Problem</th>
                    <th class="center">Solution</th> 
		                     
                </tr>
            </thead>
            <tbody>        
                <?php
               
                foreach ($activity as $row) {
                   echo $row['tr'];
                }
                   ?>
                    
              
            </tbody> 
        </table>
    </div>
</div>



<script type="text/javascript">

                    $(document).ready(function() {

                        $(function()
                        {
                            /* DataTables */
                            if ($('#tablenote').size() > 0)
                            {
                                $('#tablenote').dataTable({
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
                        
                        
                        $("#untildate").datepicker( {dateFormat:"dd-mm-yy"});
                        $("#fromdate").datepicker( {dateFormat:"dd-mm-yy"});
                        
                       var url_periode = '<?php echo site_url('trx07/home/periode_date') ?>';
                       
                        var content = $("#content");
                        var site = "mod_security/index.php/trx07/home";
                        var urlsite = ROOT.base_url + site;
                     
                        
                        $("#fromdate").change(function() {
                            
                            //alert('test');
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
                        
                        var url_add = '<?php echo site_url('trx07/home/add'); ?>';
                        var url_edit = '<?php echo site_url('trx07/home/edit'); ?>';                    
                        var url_delete = '<?php echo site_url('trx07/home/delete'); ?>';
                        var url_excel = '<?php echo site_url('trx07/home/excel'); ?>';
                        var url_print = '<?php echo site_url('trx07/home/iframe'); ?>';
                        var url_cancel = '<?php echo site_url('trx07/home/deliverycancel'); ?>';

                        if (button == 'btn_add') {
                            var content = $("#content");
                            var site = "mod_security/index.php/trx07/home/add";
                            var url = ROOT.base_url + site;
                            content.fadeOut("slow", "linear");
                            content.load(url);
                            content.fadeIn("slow");

                            //alert(url);

                        } else if (button == 'btn_edit') {  
                                                        
                                var content = $("#content");
                                var site = "mod_security/index.php/trx07/home/edit";
                                var url = ROOT.base_url + site+'/'+id;

                                content.fadeOut("slow", "linear");
                                content.load(url);
                                content.fadeIn("slow");                          
                                
                           
                            
                           
                        } else if (button == 'btn_delete') {                             
                            
                            
                                   
                                   $(document).ready(function()
                                {
                                    var contentdel = $("#content");
                                    var site = 'index.php/trx07/home';
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
                                                        url: url_delete + "/" + id,
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
                            
                          
                            

                        } else if (button == 'btn_excel') {
                            window.location.href = url_excel;

                        }
        }
                    
</script>    

