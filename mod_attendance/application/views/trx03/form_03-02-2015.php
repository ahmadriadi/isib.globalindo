<?php $base_url = $this->session->userdata('sess_base_url') ?> 


<!-- JQueryUI -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-latest"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/development-bundle/demos/demos.css" rel="stylesheet" />


<!-- Time -->
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/timepicker/timepicker.css" rel="stylesheet" />
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/timepicker/timepicker.js"></script>


<!-- Gritter Notifications Plugin -->
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>  
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />



<!-- Content -->

<div id="content-wrap">
    <h3>Form Incomplete</h3>
    <div class="innerLR">
        <!-- Form -->
        <form class="form-horizontal" style="margin-bottom: 0;" id="validateSubmitForm" method="get" autocomplete="off">
            <!-- Widget -->
            <div class="widget"
                 <!-- Widget heading -->
                 <div class="widget-head">
                    <h4 class="heading">Incomplete</h4>
                </div>
                <!-- // Widget heading END -->
                <div class="widget-body">
                    <!-- Row -->
                    <div class="row-fluid">
                        <!-- Column ke 1 -->
                        <div class="span6">
                            <!-- Group -->
                            <!--
                            <div class="control-group">
                                <label class="control-label" for="f01">IDEmployee</label>
                                <div class="controls">
                                    <input class="span10" id="f01" name="f01" type="text"  value="<?php //echo set_value('f01', isset($default['f01']) ? $default['f01'] : '');  ?>" 
                            <?php //echo (isset($default['readonly_f01'])) ? $default['readonly_f01'] : ''; ?>
                                           />
                                    <span id="err_f01"></span>
                                </div>                                
                            </div> 
                            <!-- Group end -->
                            <!-- Group -->
                            <!--
                            <div class="control-group">
                                <label class="control-label" for="f02">FullName</label>
                                <div class="controls">
                                    <input class="span10" id="f02" name="f02" type="text"  value="<?php //echo set_value('f02', isset($default['f02']) ? $default['f02'] : '');  ?>" 
                            <?php //echo (isset($default['readonly_f02'])) ? $default['readonly_f02'] : ''; ?>
                                           />
                                    <span id="err_f02"></span>
                                </div>                                
                            </div> 
                            <!-- Group end -->                            
                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f03">Incompete Date</label>
                                <div class="controls">
                                    <input class="span5" id="f03" name="f03" type="text"  value="<?php echo set_value('f03', isset($default['f03']) ? $default['f03'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f03'])) ? $default['readonly_f03'] : ''; ?>
                                           />
                                    <span id="infoatl"></span>
                                    <span id="err_f03"></span>
                                </div>                                
                            </div> 
                            <!-- Group end -->  
                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f04">Time In</label>
                                <div class="controls">
                                    <input class="span2" id="f04" name="f04" type="text" maxlength="4"  value="<?php echo set_value('f04', isset($default['f04']) ? $default['f04'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f04'])) ? $default['readonly_f04'] : ''; ?>
                                           />
                                    <span id="err_f04"></span>
                                </div>                                
                            </div> 
                            <!-- Group end -->
                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f05">Time Out</label>
                                <div class="controls">
                                    <input class="span2" id="f05" name="f05" type="text" maxlength="4"   value="<?php echo set_value('f05', isset($default['f05']) ? $default['f05'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f05'])) ? $default['readonly_f05'] : ''; ?>
                                           />
                                    <span id="err_f05"></span>
                                </div>                                
                            </div> 
                            <!-- Group end -->                            
                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f06">Note</label>
                                <div class="controls">
                                    <textarea id="f06" name="f06" cols="18" rows="3" <?php echo (isset($default['readonly_f06'])) ? $default['readonly_f06'] : ''; ?> ><?php echo (isset($default['f06'])) ? $default['f06'] : ''; ?></textarea><span style="color:white;" id="err_f06"></span>                                   
                                    <span id="err_f06"></span>
                                </div>                                
                            </div> 
                            <!-- Group end -->   

                        </div>
                        <!-- // Column END -->
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
    function loading() {
        bootbox.alertloading("<center>Processing. Please wait !!!<br><img src='<?php echo $base_url; ?>public/avatar/76.GIF'></center>");
    }

    $(document).ready(function()
    {
        var save = $('#btn_save');
        var cancel = $('#btn_cancel');

        $("#f03").datepicker({dateFormat: "dd-mm-yy"});
        // $("#f04").timepicker( {dateFormat:"dd-mm-yy"});        
        // $("#f05").timepicker( {dateFormat:"dd-mm-yy"});        


        var url_suggest = '<?php echo site_url('trx03/home/suggest_employee'); ?>';
        //alert(url_suggest); 

        $.ajax({
            url: url_suggest,
            dataType: 'json',
            success: function(d) {
                var data = d;
                /*
                 * ini deklarasi typeaheadnya yang sudah dimodifikasi
                 */
                $('#f02').typeahead({
                    source: function(query, process) {
                        objects = [];
                        // iterasi data
                        $.each(data, function(i, object) {
                            objects.push(object.idemployee + '-' + object.fullname);
                            //objects.push(object.nip);                        
                        });
                        process(objects);
                    },
                    items: 10,
                    updater: function(item) {
                        var s = item.split('-');
                        $('#f01').val(s[0]); /* for add item */
                        return s[1]; /* data is array*/

                        return item;
                    }
                    ,
                    matcher: function(item) {
                        var s = item.split('-');
                        return s[1].toLowerCase().indexOf(this.query.toLowerCase()) != -1
                    }
                });
            }
        });


        var atluser = [];
        $('#f03').focusout(function() {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?php echo site_url('trx03/home/atluser') ?>",
                success: function(data, text) {
                    $("#infoatl").html("<br/>Your Incomplete in this periode : " + data.youratl);
                    atluser.push(data.youratl);
                }
            });
        });

        save.click(
                function()
                {

                    var f01 = $("#f01").val();
                    var f02 = $("#f02").val();
                    var f03 = $("#f03").val();
                    var f04 = $("#f04").val();
                    var f05 = $("#f05").val();
                    var f06 = $("#f06").val();

                    var content = $("#content .innerLR");
                    var site = 'mod_attendance/index.php/trx03/home';
                    var url = ROOT.base_url + site;
                    var postdata = "f01=" + f01 + "&f02=" + f02 + "&f03=" + f03 + "&f04=" + f04 + "&f05=" + f05 + "&f06=" + f06;
                    var url_post = '<?php echo $url_post; ?>';

                    var enddatatal = atluser.pop();
                    if (enddatatal !== '0') {
                        bootbox.confirm("Are you sure save data with incomplete has been " + enddatatal + " on this periode ?", function(result) {
                            if (result) {
                                loading();
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
                                                            bootbox.alert(data.mesg, function(result) {
                                                                bootbox.hideAll();
                                                            });

                                                            content.fadeOut("slow", "linear");
                                                            content.load(url);
                                                            content.fadeIn("slow");

                                                        } else {
                                                            bootbox.hideAll();
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
                                                            $("#err_f05").html(data.err_f05).fadeIn('slow');
                                                            $("#err_f06").html(data.err_f06).fadeIn('slow');

                                                        }
                                                    },
                                            error: function(request, status, error) {
                                                alert(request.responseText + " " + status + " " + error);
                                            }
                                        });
                                return false;

                            }

                        });

                    } else {


                        loading();
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

                                                    bootbox.alert(data.mesg, function(result) {
                                                        bootbox.hideAll();
                                                    });

                                                    content.fadeOut("slow", "linear");
                                                    content.load(url);
                                                    content.fadeIn("slow");

                                                } else {
                                                    bootbox.hideAll();
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
                                                    $("#err_f05").html(data.err_f05).fadeIn('slow');
                                                    $("#err_f06").html(data.err_f06).fadeIn('slow');

                                                }
                                            },
                                    error: function(request, status, error) {
                                        alert(request.responseText + " " + status + " " + error);
                                    }
                                });
                        return false;

                    }




                });

        cancel.click(
                function()
                {
                    var content = $("#content .innerLR");
                    var site = 'mod_attendance/index.php/trx03/home';
                    var url = ROOT.base_url + site;
                    //alert(url);
                    content.fadeOut("slow", "linear");
                    content.load(url);
                    content.fadeIn("slow");

                });
    });



</script> 


