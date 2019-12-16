<?php $base_url = $this->session->userdata('sess_base_url') ?> 


<!-- JQueryUI -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-latest.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/development-bundle/demos/demos.css" rel="stylesheet" />

<script src="<?php echo $base_url ?>public/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

<!-- DataTables Tables Plugin -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>

<!-- Gritter Notifications Plugin -->
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>  
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />

<!-- Content -->

<style>
    .alertspan {       
        font-size: 13px;
        color: yellow;       
    }

</style>

<div id="content-wrap">
    <h3>IT - SYSDEV</h3>
    <div class="innerLR">
        <!-- Form -->
        <form class="form-horizontal" style="margin-bottom: 0;" id="validateSubmitForm" method="get" autocomplete="off">
            <!-- Widget -->
            <div class="widget"
                 <!-- Widget heading -->
                 <div class="widget-head">
                    <h4 class="heading"></h4>
                </div>
                <!-- // Widget heading END -->
                <div class="widget-body">
                    <!-- Row -->
                    <div class="row-fluid">
                        <!-- Column ke 1 -->
                        <div class="span6">                              
                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f01">Item</label>
                                <div class="controls">
                                    <input class="span8" id="f01" name="f01" type="text"  value="<?php echo set_value('f01', isset($default['f01']) ? $default['f01'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f01'])) ? $default['readonly_f01'] : ''; ?>
                                           />
                                     <span id='btn_item' class="btn btn-small btn-warning"><i class="icon-search"></i> </span>
                                </div>
                               
                            </div>
                            <!-- Group end -->   
                           
                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f02">Code Item</label>                               
                                <div class="controls">
                                    <input class="span5" id="f02" name="f02" type="text"  value="<?php echo set_value('f02', isset($default['f02']) ? $default['f02'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f02'])) ? $default['readonly_f02'] : ''; ?>
                                           />
                                </div>                                     
                                <span id="err_f02" class="alertspan"></span>
                            </div> 
                            <!-- Group end -->    


                        </div>
                        <!-- // Column 1 END -->

                        <!-- Column ke 2 -->
                        <div class="span6">   
                            
                             <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f03">Location</label>
                                <div class="controls">
                                    <select id="f03" name="f03" >
                                        <?php foreach ($default['f03'] as $row) { ?>
                                            <option value="<?php echo (isset($row['value'])) ? $row['value'] : ''; ?>" 
                                                    <?php echo (isset($row['selected'])) ? $row['selected'] : ''; ?> >
                                                        <?php echo (isset($row['display'])) ? $row['display'] : ''; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    
                                    <span id="err_f03"></span>
                                </div>                                
                            </div>
                            <!-- Group end -->   
                            
                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f04">Note</label>                               
                                <div class="controls">
                                    <textarea id="f04" name="f04" cols="18" rows="3" <?php echo (isset($default['readonly_f04'])) ? $default['readonly_f04'] : ''; ?> ><?php echo (isset($default['f04'])) ? $default['f04'] : ''; ?></textarea><span style="color:white;" id="err_f04"></span>                                   
                                </div>                                     
                                <span id="err_f04" class="alertspan"></span>
                            </div> 
                            <!-- Group end -->       
                        </div>
                        <!-- // Column 2 END --> 


                    </div>                   
                    <!-- // Row END -->
                    <hr class="separator" />
                    <!-- Form actions -->
                    <div class="form-actions" align="center">
                        <button type="button" id="btn_save"   class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Save</button>
                        <button type="button" id="btn_cancel" class="btn btn-icon btn-default glyphicons circle_remove"><i></i>Cancel</button>

                    </div>
                    <!-- // Form actions END -->
                </div>
            </div>
            <!-- // Widget END -->
        </form>
        <!-- // Form END -->
    </div>	
</div>
<!-- // Content END -->


<div style="display:none;" align="bottom" id="item-modal" class="modal hide fade " tabindex="-1">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Item Inventaris</h3>
    </div>
    <div class="modal-body">
        <select id="itemdata" name="itemdata" >
            <?php foreach ($default['ivndata'] as $row) { ?>
                <option value="<?php echo (isset($row['value'])) ? $row['value'] : ''; ?>" 
                        <?php echo (isset($row['selected'])) ? $row['selected'] : ''; ?> >
                    <?php echo (isset($row['display'])) ? $row['display'] : ''; ?></option>
            <?php } ?>
        </select>
        <table width="100%" id="autotableitem" cellspacing="1" cellpadding="1" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable" >
        </table>
    </div>
</div>


<script type="text/javascript">

    $(document).ready(function()
    {
        var save = $('#btn_save');
        var item = $('#btn_item');
        var cancel = $('#btn_cancel');
        var flag = '<?php echo $flagcondition ?>';

        save.click(
                function()
                {
                    var f01 = $("#f01").val();
                    var f02 = $("#f02").val();
                    var f03 = $("#f03").val();
                    var f04 = $("#f04").val();

                    var content = $("#content .innerLR");
                    var site = 'mod_it/index.php/trx01/home';
                    var url = ROOT.base_url + site;
                    var postdata = "f01=" + f01 + "&f02=" + f02 + "&f03=" + f03+ "&f04=" + f04;
                    var url_post = '<?php echo $url_post; ?>';

                    $.ajax(
                            {
                                type: "POST",
                                url: url_post,
                                dataType: "json",
                                data: postdata,
                                cache: false,
                                success:
                                        function(data, text)
                                        {
                                            if (data.valid == 'true') {
                                                bootbox.alert(data.mesg, function(result)
                                                {

                                                });
                                                content.load(url);


                                            } else {

                                                $.gritter.add({
                                                    title: 'WARNING',
                                                    text: data.mesg,
                                                    image: '<?php echo $base_url . 'public/theme/images/warni.jpeg' ?>',
                                                    class_name: 'gritter-light',
                                                    fade_in_speed: 100,
                                                    fade_out_speed: 100,
                                                    time: 2500
                                                });

                                                $("#err_f01").html(data.err_f01).fadeIn('slow');
                                                $("#err_f02").html(data.err_f02).fadeIn('slow');
                                                $("#err_f03").html(data.err_f03).fadeIn('slow');
                                                $("#err_f04").html(data.err_f04).fadeIn('slow');


                                            }
                                        },
                                error: function(request, status, error) {
                                    alert(request.responseText + " " + status + " " + error);
                                }
                            });
                    return false;


                });

        cancel.click(
                function()
                {
                    var content = $("#content .innerLR");
                    var site = 'mod_it/index.php/trx01/home';
                    var url = ROOT.base_url + site;
                    content.load(url);


                });

        item.click(
                
                
                function()
                {

                    $('#item-modal').modal();

                    var oTableAutoItem = $('#autotableitem').dataTable({
                        "bJQueryUI": false,
                        "bSortClasses": false,
                        "aaSorting": [[6, "desc"]],
                        "bAutoWidth": true,
                        "bInfo": true,
                        "sScrollY": "100%",
                        "sScrollX": "100%",
                        "bScrollCollapse": true,
                        "bRetrieve": true,
                        "bPaginate": true,
                        "oLanguage": {
                            "sSearch": "Search:"
                        },
                        "bProcessing": true,
                        "bServerSide": true,
                        "sAjaxSource": '<?php echo site_url('trx01/home/getdatatable_inventaris') ?>',
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
                                <button btn='btn_itemdata' idbtn=" + aoData + "  type='button' class='btn btn-mini btn-warning'><i class='icon-file'></i></button>\n\
                                </div>\n\
                                ";
                                }
                            },
                            {"mData": "ItemCode", "sWidth": "100%", "sTitle": "Code", "sClass": "left"},
                            {"mData": "CounterCode", "sWidth": "100%", "sTitle": "Code Item", "sClass": "center"},
                            {"mData": "ItemName", "sWidth": "100%", "sTitle": "Name", "sClass": "left"},
                            {"mData": "Note", "sWidth": "100%", "sTitle": "Note", "sClass": "left"},
                        ],
                        "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                            $(nRow).attr("id", aData.ID);
                            return nRow;
                        },
                        "fnDrawCallback": function(oSettings) {
                            $("#autotableitem tbody tr").on('mouseenter', function() {
                                $('#autotableitem tbody tr').addClass("selectable");
                            });
                            $("#autotableitem tbody tr ").on('click', 'button', function() {
                                var button = $(this).attr("btn");
                                var id = $(this).attr("idbtn");
                                reply_click(button + '-' + id);
                                return false;

                            });
                        }

                    });
                    
                    
                   $('select#itemdata').on('change',function(){
                    var selectedValue = $(this).val();
                    oTableAutoItem.fnFilter(selectedValue,1,true);
                });

                });
    });



    function reply_click(clicked_id) {
        var str = clicked_id;
        var explode = str.split('-');
        var button = explode[0];
        var id = explode[1];

        if (button == 'btn_itemdata') {
            $(document).ready(function()
            {
                
                
                $.ajax({
                    type: "POST",
                    url: '<?php echo site_url('trx01/home/getdata_item') ?>' + '/' + id,
                    dataType: "json",
                    cache: false,
                    success:
                            function(data) {
                                $('#f01').val(data.item);
                                $('#f02').val(data.codeitem);
                                $('#item-modal').modal('hide');

                            },
                    error:
                            function(xhr, ajaxOptions, thrownError) {
                                alert(xhr.status);
                                alert(thrownError);
                            }
                });
            });
        }


    }

</script> 
