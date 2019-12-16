<?php $base_url = $this->session->userdata('sess_base_url') ?> 


<!-- JQueryUI -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-latest.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/development-bundle/demos/demos.css" rel="stylesheet" />

<script src="<?php echo $base_url ?>public/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

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
                                    <select id="f01" name="f01" >
                                        <?php foreach ($default['f01'] as $row) { ?>
                                            <option value="<?php echo (isset($row['value'])) ? $row['value'] : ''; ?>" 
                                                    <?php echo (isset($row['selected'])) ? $row['selected'] : ''; ?> >
                                                        <?php echo (isset($row['display'])) ? $row['display'] : ''; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <span id="err_f01"></span>
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
                                <label class="control-label" for="f03">Note</label>                               
                                <div class="controls">
                                    <textarea id="f03" name="f03" cols="18" rows="3" <?php echo (isset($default['readonly_f03'])) ? $default['readonly_f03'] : ''; ?> ><?php echo (isset($default['f03'])) ? $default['f03'] : ''; ?></textarea><span style="color:white;" id="err_f03"></span>                                   
                                </div>                                     
                                <span id="err_f03" class="alertspan"></span>
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

<script type="text/javascript">

    $(document).ready(function()
    {
        var save = $('#btn_save');
        var cancel = $('#btn_cancel');
        var flag = '<?php echo $flagcondition ?>';

        $("#f01").change(function() {

            var codeitem = $("#f01").val();
            if (flag == 'add') {
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: '<?php echo site_url('mst01/home/getcounter_item') ?>',
                    data: "itemcode=" + codeitem,
                    success:
                            function(data, text)
                            {
                                if (data.valid == 'true') {
                                    $("#f02").val(data.counterdata);
                                }
                            }
                });
            }
        });


        save.click(
                function()
                {
                    var f01 = $("#f01").val();
                    var f02 = $("#f02").val();
                    var f03 = $("#f03").val();

                    var content = $("#content .innerLR");
                    var site = 'mod_it/index.php/mst01/home';
                    var url = ROOT.base_url + site;
                    var postdata = "f01=" + f01 + "&f02=" + f02 + "&f03=" + f03;
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
                    var site = 'mod_it/index.php/mst01/home';
                    var url = ROOT.base_url + site;
                    content.load(url);


                });
    });



</script> 
