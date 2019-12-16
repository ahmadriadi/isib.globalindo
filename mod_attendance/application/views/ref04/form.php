<?php $base_url = $this->session->userdata('sess_base_url') ?> 


<!-- JQueryUI -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-latest"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/development-bundle/demos/demos.css" rel="stylesheet" />

<!--<script src="<?php echo $base_url ?>public/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>--> 

<!-- Time -->
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/timepicker/timepicker.css" rel="stylesheet" />
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/timepicker/timepicker.js"></script>


<!-- Gritter Notifications Plugin -->
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>  
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />


<!-- Content -->

<div id="content-wrap">
    <h3>Form Parameter Lock</h3>
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
                            <div class="control-group">
                                <label class="control-label" for="f01">Type</label>
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
                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f02">Value 1</label>
                                <div class="controls">
                                    <input class="span5" id="f02" name="f02" type="text"  value="<?php echo set_value('f02', isset($default['f02']) ? $default['f02'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f02'])) ? $default['readonly_f02'] : ''; ?>
                                           />
                                    <span id="err_f02"></span>
                                </div>                                
                            </div>   
                            <!-- Group end -->
                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f03">Value 1</label>
                                <div class="controls">
                                    <input class="span5" id="f03" name="f03" type="text"  value="<?php echo set_value('f03', isset($default['f03']) ? $default['f03'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f03'])) ? $default['readonly_f03'] : ''; ?>
                                           />
                                    <span id="err_f03"></span>
                                </div>                                
                            </div>   
                            <!-- Group end -->
                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f04">User Exclude</label>
                                <div class="controls">
                                    <input class="span5" id="f04" name="f04" type="text"  value="<?php echo set_value('f04', isset($default['f04']) ? $default['f04'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f04'])) ? $default['readonly_f04'] : ''; ?>
                                           />
                                    <span id="err_f04"></span>
                                </div>                                
                            </div>   
                            <!-- Group end -->
                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f05">Time Limit for User</label>
                                <div class="controls">
                                    <input class="span5" id="f05" name="f05" type="text"  value="<?php echo set_value('f05', isset($default['f05']) ? $default['f05'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f05'])) ? $default['readonly_f05'] : ''; ?>                                           
                                            onblur="if(this.value=='hhmm') this.value='hhmm';" 
                                             onfocus="if(this.value=='hhmm') this.value='';"
                                           />
                                    <span id="err_f05"></span>
                                </div>                                
                            </div>   
                            <!-- Group end -->
                            
                        </div>
                        <!-- // Column 1 END -->  

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

         var url_suggest = '<?php echo site_url('ref04/home/suggest_employee'); ?>';
        $("#f04").autocomplete({
            minLength: 2,
            source: function(req, add) {
                $.ajax({
                    url: url_suggest,
                    dataType: "json",
                    type: "POST",
                    data: req,
                    success: function(data) {
                        if (data.response = "true") {

                            add(data.message);
                        }
                    },
                    error: function(XMLHttpRequest) {
                        alert(XMLHttpRequest.responseText);
                    }
                })
            }
        });
        
        
        save.click(
                function()
                {
                    var f01 = $("#f01").val();
                    var f02 = $("#f02").val();
                    var f03 = $("#f03").val();
                    var f04 = $("#f04").val();
                    var f05 = $("#f05").val();
                    var content = $("#content .innerLR");
                    var site = 'mod_attendance/index.php/ref04/home';
                    var url = ROOT.base_url + site;
                    var postdata = "f01=" + f01 + "&f02=" + f02 + "&f03=" + f03+ "&f04=" + f04+ "&f05=" + f05;
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
                    var site = 'mod_attendance/index.php/ref04/home';
                    var url = ROOT.base_url + site;
                    content.load(url);


                });
    });



</script> 
