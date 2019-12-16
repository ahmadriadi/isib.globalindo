<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<?php $base_url = $this->session->userdata('sess_base_url'); ?>
<!-- DataTables Plugin --> 
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css" rel="stylesheet" />
<!-- <link href="<?php //echo $base_url        ?>public/theme/scripts/plugins/system/jquery-ui/development-bundle/demos/demos.css" rel="stylesheet" />
<!-- JQueryUI -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-latest.js"></script>
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
                <button id='btn_export' onClick="reply_click(this.id)"  class="btn" id="editable-sample_new">
                    EXCEL<i class="icon-download"></i>
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
                <?php
                $i = 0;
                foreach ($dataleavepermit as $row) {
                    $i++;
                    $jg = $row['IDJobGroup'];
                    $nc = $row['Necessity'];

                    if ($jg == 'ST') {
                        $group = 'STAFF';
                    } else if ($jg == 'LT') {
                        $group = 'LAPANGAN TETAP';
                    } else if ($jg == 'LK') {
                        $group = 'LAPANGAN KONTRAK';
                    } else if ($jg == 'HL') {
                        $group = 'HARIAN LEPAS';
                    } else if ($jg == 'LL') {
                        $group = 'LAIN-LAIHN';
                    }

                    if ($nc == '1') {
                        $necessity = 'PERSONAL';
                    } else {
                        $necessity = 'OFFICE';
                    }


                    echo "<tr class='selectable' >";
                    echo "<td> 
                          <div class='btn-group'> 
                          <center>                          
                          <button id='btn_edit-" . $row['ID'] . "-" . $row['ConfirmFlag'] . "-" . $row['FlagInput'] . "-" . $row['FullName'] . "' type='button' class='btn btn-mini btn-warning' title='Edit " . $row['FullName'] . "' onclick='reply_click(this.id)' ><i class='icon-pencil'></i></button>
                          <button id='btn_delete-" . $row['ID'] . "-" . $row['ConfirmFlag'] . "-" . $row['FlagInput'] . "-" . $row['FullName'] . "' type='button' class='btn btn-mini btn-danger' title='Delete " . $row['FullName'] . "' onclick='reply_click(this.id)' ><i class='icon-trash'></i></button>                         
                          </center>
                           </div> 
                        </td>";
                    echo "<td>" . $row['IDEmployee'] . "</td>";
                    echo "<td>" . $row['FullName'] . "</td>";
                    echo "<td>" . $group . "</td>";
                    echo "<td>" . $necessity . "</td>";
                    echo "<td>" . $row['LeavePermitDate'] . "</td>";
                    echo "<td>" . $row['OutDate'] . "</td>";
                    echo "<td>" . $row['InDate'] . "</td>";
                    echo "<td>" . $row['IMKHour'] . "</td>";
                    echo "<td>" . $row['VehicleNo'] . "</td>";
                    echo "<td>" . $row['Note'] . "</td>";
                    echo "</tr>";
                }
                ?>
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



                    function reloadpage() {
                        var content = $("#content .innerLR");
                        var url = ROOT.base_url + 'mod_attendance/index.php/trx02a/home/';
                        //alert(url);
                        content.load(url);
                    }
                    function backtohome() {
                        window.location.href = "<?php echo $base_url; ?>";
                    }
                   
                    $(function()
                    {
                        /* DataTables */
                        if ($("#leavepermit").size() > 0)
                        {
                            $("#leavepermit").dataTable({
                                "sPaginationType": "bootstrap",
                                "bDestroy": true,
                                "aaSorting": [[5, "desc"]],
                                "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
                                "oLanguage": {
                                    "sLengthMenu": "_MENU_ records per page"
                                }
                            });
                        }
                    });


                    function reply_click(clicked_id)
                    {
                        var str = clicked_id;
                        var explode = str.split('-');
                        var button = explode[0];
                        var id = explode[1];
                        var status = explode[2];
                        var inputby = explode[3];
                        var name = explode[4];
			var access = '<?php echo $accessbutton; ?>';


                        //alert("ID RECORD :"+id+" STATUS :"+status+" INPUT BY :"+inputby);

                        //                      
                        if (button == 'btn_add') {
                            var content = $("#content");
                            var site = "mod_attendance/index.php/trx02a/home/addnew";
                            var url = ROOT.base_url + site;
                            content.fadeOut("slow", "linear");
                            content.load(url);
                            content.fadeIn("slow");

                        } else if (button == 'btn_edit') {
                            //alert("ID RECORD :"+id+" STATUS :"+status+" INPUT BY :"+inputby);   
                            if (status == '0' || status == '1' && inputby == 'emp' && access=='false') {
				
                                $.gritter.add({
                                    title: 'WARNING',
                                    text: "You can't edit the data " + name,
                                    image: '<?php echo $base_url . 'public/theme/images/warni.jpeg' ?>',
                                    class_name: 'gritter-light',
                                    fade_in_speed: 100,
                                    fade_out_speed: 100,
                                    time: 2500
                                });
                                return false;
                            } else {

                                var content = $("#content");
                                var site = "mod_attendance/index.php/trx02a/home/edit";
                                var url = ROOT.base_url + site + "/" + id;
                                content.fadeOut("slow", "linear");
                                content.load(url);
                                content.fadeIn("slow");

                            }

                        } else if (button == 'btn_delete') {
                            if (status == '0' || status == '1' && inputby == 'emp') {
                                $.gritter.add({
                                    title: 'WARNING',
                                    text: "You can't delete the data " + name,
                                    image: '<?php echo $base_url . 'public/theme/images/warni.jpeg' ?>',
                                    class_name: 'gritter-light',
                                    fade_in_speed: 100,
                                    fade_out_speed: 100,
                                    time: 2500
                                });
                                return false;
                            } else {

                                $(document).ready(function()
                                {

                                    var contentdel = $("#content");
                                    var site = 'mod_attendance/index.php/trx02a/home';
                                    var urldel = ROOT.base_url + site;


                                    //alert('<?php //echo site_url('trx02a/home/delete')   ?>' + '/' + id);

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
                                                        url: '<?php echo site_url('trx02a/home/delete') ?>' + '/' + id,
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
                            }
                        } else if (button == 'btn_export') {
                            window.location.href = '<?php echo site_url('trx02a/home/excel') ?>'+'/'+'AL';

                        }
                    }
    </script>
