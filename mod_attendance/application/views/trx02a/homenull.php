<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<?php $base_url = $this->session->userdata('sess_base_url'); ?>
<!-- DataTables Plugin --> 
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css" rel="stylesheet" />
<!-- <link href="<?php //echo $base_url         ?>public/theme/scripts/plugins/system/jquery-ui/development-bundle/demos/demos.css" rel="stylesheet" />
<!-- JQueryUI -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-latest"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>

<!-- Bootstrap -->
<!--<script src="<?php echo $base_url; ?>public/bootstrap/js/bootstrap.js"></script>-->
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
                <h4 class="heading">Data Leavepermit</h4>                
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
               
            </p>
        </div>

        <!-- Table -->
        <table width='100%' id="leavepermit" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable" >
            <thead class="btn-primary">
                <tr>
                    <td><center>Action</center></td>
            <td><center>IDEmployee</center></td>
            <td><center>FUllName</center></td>
            <td><center>Group</center></td>
            <td><center>Status</center></td>
            <td><center>Leavepermit Date</center></td>
            <td><center>Out Office</center></td>
            <td><center>In Office</center></td>
            <td><center>Hour</center></td>
            <td><center>Vehicle No.</center></td>
            <td><center>Note</center></td>
            </tr>
            </thead>
            <tbody>             
            </tbody>
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
                    $("#untildate").datepicker({dateFormat: "dd-mm-yy"});
                    $("#fromdate").datepicker({dateFormat: "dd-mm-yy"});

                    var url_periode = '<?php echo site_url('trx02a/home/set_pattern_date') ?>';
                    var content = $("#content");
                    var site = "mod_attendance/index.php/trx02a/home";
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

                    function reply_click(clicked_id) {
                        var str = clicked_id;
                        var explode = str.split('-');
                        var button = explode[0];

                        if (button == 'btn_add') {
                            var content = $("#content");
                            var site = "mod_attendance/index.php/trx02a/home/addnew";
                            var url = ROOT.base_url + site;
                            content.fadeOut("slow", "linear");
                            content.load(url);
                            content.fadeIn("slow");

                        }

                    }

    </script>
