<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<?php $base_url = $this->session->userdata('sess_base_url'); ?>
<!-- DataTables Plugin --> 
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css" rel="stylesheet" />

<!-- JQueryUI -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-latest"></script>
<!-- <script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script> -->
<!-- JQueryUI Time -->
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/time/timepicker.css" rel="stylesheet" />
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/time/timepicker.js"></script>
<!-- Bootstrap -->
<!--<script src="<?php echo $base_url; ?>public/bootstrap/js/bootstrap.js"></script>-->
<!-- DataTables Tables Plugin -->

<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/css/colvis.css" rel="stylesheet" />
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/colvis.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/api/fnStandingRedraw.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/api/FilterAll.js"></script>
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/js/listbox_paging.js"></script>

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
                <h4 class="heading">Data Overtime</h4>                
            </div>
            <div class="span6" style="text-align: right">
                <button onclick="reloadpage()" class="btn btn-small btn-default"><i class="icon-refresh"></i></button>
                <button onclick="backtohome()" class="btn btn-small btn-success btn-icon glyphicons home"><i></i>Back to Home</button>
            </div>
        </div>
    </div>
    <div class="widget-body">
        <div class="btn-group">
          
            <table>
                <tr>
		    <td>
                       <div class="row-fluid">
                        <div class="span10" style="text-align: left">
                        <label>Job Group</label>
                        <select class=" span16" data-style="btn-inverse" id="group" name="group" >
                             <?php foreach ($default['group'] as $row) { ?>
                                 <option value="<?php echo (isset($row['value'])) ? $row['value'] : ''; ?>" 
                                         <?php echo (isset($row['selected'])) ? $row['selected'] : ''; ?> >
                                     <?php echo (isset($row['display'])) ? $row['display'] : ''; ?></option>
                             <?php } ?>
                         </select>  
                        </div>
                        <div>
                        <label>Location</label>
                        <select class=" span16" data-style="btn-inverse" id="location" name="location" >
                             <?php foreach ($default['location'] as $row) { ?>
                                 <option value="<?php echo (isset($row['value'])) ? $row['value'] : ''; ?>" 
                                         <?php echo (isset($row['selected'])) ? $row['selected'] : ''; ?> >
                                     <?php echo (isset($row['display'])) ? $row['display'] : ''; ?></option>
                             <?php } ?>
                         </select> 
                          </div>
                          </div>
                    </td>	
                </tr> 
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
                    EXCEL<i class="icon-download"></i>
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




                    function reloadpage() {
                        var content = $("#content .innerLR");
                        var url = ROOT.base_url + 'mod_attendance/index.php/trx01a/home/';
                        //alert(url);
                        content.load(url);
                    }
                    function backtohome() {
                        window.location.href = "<?php echo $base_url; ?>";
                    }
                   
                    $(document).ready(function() {


                        var dataajax = '<?php echo site_url('trx01a/home/dataovertime') ?>';

                        var oTable = $('#tableajax').dataTable({
                            "bJQueryUI": false,
                            "bSortClasses": false,
                            "aaSorting": [[7, "desc"]],
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
                            "bDeferRender": true,
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
                                    "mRender": function(data, type, row) {


                                        return "<div class='btn-group'>\n\
                                                  <button btn='btn_edit' idbtn=" + row.ID + "  type='button' class='btn btn-mini btn-warning'><i class='icon-pencil icon-white'></i></button>\n\
                                                  <button btn='btn_delete' idbtn=" + row.ID + "  type='button' class='btn btn-mini btn-danger'><i class='icon-trash '></i></button>\n\
                                                  </div>\n\
                                                  ";

                                    }
                                },
                                {
                                    "mData": "CheckData", "sTitle": "Check", "sClass": "center",
                                    "bSortable": false,
                                    "mRender": function(data, type, row) {
                                        if (row.CheckData == 1) {
                                            return "<input type='checkbox' idcheckbox=" + row.ID + "   name='cekdata' checked  valceckbox=" + row.CheckData + " > ";
                                        } else if (row.CheckData == 0) {
                                            return "<input type='checkbox' idcheckbox=" + row.ID + "  name='cekdata' valceckbox=" + row.CheckData + " > ";
                                        }

                                    }
                                },
				{"mData": "IDLocation", "sTitle": "ID Location", "sClass": "left"},  
				{"mData": "Location", "sTitle": "Location", "sClass": "left"},
                                {"mData": "ID", "sTitle": "ID SPKL", "sClass": "left"},
                                {"mData": "IDSPKL", "sTitle": "Sub SPKL", "sClass": "left"},
                                {"mData": "IDEmployee", "sTitle": "IDEmployee", "sClass": "left"},
                                {"mData": "FullName", "sTitle": "Fullname", "sClass": "left"},
                                {"mData": "IDJobGroup", "sTitle": "ID Group", "sClass": "left"},
                                {"mData": "PresenceDate", "sTitle": "PresenceDate", "sClass": "left", sType: 'date-eu'},
                                {"mData": "OvertimeIn", "sTitle": "OvertimeIn", "sClass": "left", sType: 'date-eu'},
                                {"mData": "OvertimeOut", "sTitle": "OvertimeOut", "sClass": "left", sType: 'date-eu'},
                                {"mData": "OvertimeHour", "sTitle": "WorkHour", "sType": "formatted-num", "sClass": "right"},
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
                                                url: '<?php echo site_url('trx01a/home/getstatus') ?>',
                                                dataType: "json",
                                                data: "id=" + id,
                                                cache: false,
                                                success:
                                                        function(data, text)
                                                        {
                                                            if (data.valid == 'true') {
                                                                var status = data.flag;
                                                                var inputby = data.inputby;
                                                                reply_click(button + '-' + id + '-' + status + '-' + inputby);
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


                                $("#tableajax tbody tr ").on('click', 'input:checkbox', function() {
                                    var id = $(this).attr("idcheckbox");
                                    $.ajax(
                                            {
                                                type: "POST",
                                                url: "<?php echo site_url('trx01a/home/checkdata') ?>",
                                                dataType: "json",
                                                data: "id=" + id,
                                                cache: false,
                                                success:
                                                        function(data, text)
                                                        {
                                                            if (data.valid == 'true') {
                                                                $("#tableajax").dataTable().fnStandingRedraw();
                                                            } else {
                                                                data.mesg;
                                                                $("#tableajax").dataTable().fnStandingRedraw();
                                                            }
                                                        },
                                                error: function(request, status, error) {
                                                    alert(request.responseText + " " + status + " " + error);
                                                }
                                            });
                                    return false;

                                })


                                $("#tableajax tbody tr ").on('mouseenter', 'button', function() {
                                    var button = $(this).attr("btn");

                                    $.ajax(
                                            {
                                                type: "POST",
                                                url: '<?php echo site_url('trx01a/home/get_access') ?>',
                                                dataType: "json",
                                                data: "btn=" + button,
                                                cache: false,
                                                success:
                                                        function(data, text)
                                                        {
                                                            if (data.valid == 'true') {
                                                                var status = data.btnaccess;
                                                                $("button[btn='" + status + "']").prop('disabled', false);

                                                            } else {
                                                                $("button[btn='" + button + "']").prop('disabled', true);
                                                            }
                                                        },
                                                error: function(request, status, error) {
                                                    alert(request.responseText + " " + status + " " + error);
                                                }
                                            });
                                    return false;
                                })


                                $("#tableajax tbody tr").on('mouseenter', function() {
                                    $('#tableajax tbody tr').addClass("selectable");

                                });
                               

                            }

                        });





                        $("#untildate").datepicker({dateFormat: "dd-mm-yy"});
                        $("#fromdate").datepicker({dateFormat: "dd-mm-yy"});


                        var url_periode = '<?php echo site_url('trx01a/home/set_pattern_date') ?>';
                        var content = $("#content");
                        var site = "mod_attendance/index.php/trx01a/home";
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
                            
                            oTable.fnFilter(selectedValue,8,true);
                            if(selectedValue=='AL'){
                                oTable.fnFilterClear();
                            }                            
                            
                        });


		  $('select#location').on('change',function(){
                            var selectedValue = $(this).val();
                            oTable.fnFilter(selectedValue,2,true);
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
                        var status = explode[2];
                        var inputby = explode[3];
                        var access = '<?php echo $accessbutton; ?>';
			var group = $("#group").val();


                        if (button == 'btn_add') {
                            var content = $("#content");
                            var site = "mod_attendance/index.php/trx01a/home/addnew";
                            var url = ROOT.base_url + site;
                            //alert(url);
                            content.fadeOut("slow", "linear");
                            content.load(url);
                            content.fadeIn("slow");


                        } else if (button == 'btn_edit') {
                            if (status == '1' && inputby == 'emp' && access == 'false') {
                                $.gritter.add({
                                    title: 'WARNING',
                                    text: "Sorry, Can't Edit the data ",
                                    image: '<?php echo $base_url . 'public/theme/images/warni.jpeg' ?>',
                                    class_name: 'gritter-light',
                                    fade_in_speed: 100,
                                    fade_out_speed: 100,
                                    time: 2500
                                });
                                return false;
                            } else {
                                var content = $("#content");
                                var site = "mod_attendance/index.php/trx01a/home/edit";
                                var url = ROOT.base_url + site + "/" + id;
                                content.fadeOut("slow", "linear");
                                content.load(url);
                                content.fadeIn("slow");
                            }

                        } else if (button == 'btn_delete') {
                            $(document).ready(function()
                            {
                                if (status == 'emp' && access == 'false') {
                                    $.gritter.add({
                                        title: 'WARNING',
                                        text: "Sorry, Can't Delete",
                                        image: '<?php echo $base_url . 'public/theme/images/warni.jpeg' ?>',
                                        class_name: 'gritter-light',
                                        fade_in_speed: 100,
                                        fade_out_speed: 100,
                                        time: 2500
                                    });
                                    return false;
                                } else {
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
                                                        url: '<?php echo site_url('trx01a/home/delete') ?>' + '/' + id,
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

                                }
                            });
                        } else if (button == 'btn_excel') {
                            window.location.href = '<?php echo site_url('trx01a/home/exportdata') ?>'+'/'+group;

                        }


                    }
    </script>
