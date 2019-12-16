<?php $base_url = $this->session->userdata('sess_base_url') ?> 
<style>
    a.ui-dialog-titlebar-close { display:block; }
</style>
<!-- JQueryUI -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-latest"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/development-bundle/demos/demos.css" rel="stylesheet" />

<script src="<?php echo $base_url ?>public/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 

<!-- Time -->
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/timepicker/timepicker.css" rel="stylesheet" />
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/timepicker/timepicker.js"></script>

<!-- Gritter Notifications Plugin -->
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>  
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />


<div class="box-generic">
    <!-- Tabs Heading -->
    <div class="tabsbar">
        <ul>
            <li class="glyphicons restart active"><a href="#presence" data-toggle="tab"><i></i>PROCESS INCOMPLETE BY SYSTEM<strong></strong></a></li>
        </ul>
    </div>
    <!-- // Tabs Heading END -->
    <div class="tab-content">
        <!-- Tab content -->
        <div class="tab-pane active" id="presence">
            <div class="widget">               
                <div class="widget-body">
                    <div class="control-group">
                        <label class="control-label" for="f01">Date</label>
                        <div class="controls">
                            <input class="span2" id="f01" name="f01" type="text"  value="<?php echo set_value('f01', isset($default['f01']) ? $default['f01'] : ''); ?>" 
                            <?php echo (isset($default['readonly_f01'])) ? $default['readonly_f01'] : ''; ?>
                                   />
                            <span id="err_f01"></span>
                        </div>                                
                    </div>   
                    <div class="control-group">
                        <label class="control-label" for="f02">Absence</label>
                        <div class="controls">
                            <input type='radio' id='in' name='Absence' value='<?php echo $default['group1'] ?>' <?php echo $default['checked_group1'] ?> >&nbsp;&nbsp;<?php echo $default['group1'] ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <input type='radio' id='out' name='Absence' value='<?php echo $default['group2'] ?>' <?php echo $default['checked_group2'] ?> >&nbsp;&nbsp;<?php echo $default['group2'] ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <span id="err_f02"></span>
                        </div>                                
                    </div>                  
                    <div class="control-group">
                        <label class="control-label" for="f03">Location</label>
                        <div class="controls">
                            <input type='radio' id='all' name='Site' value='<?php echo $default['group3'] ?>' <?php echo $default['checked_group3'] ?> >&nbsp;&nbsp;<?php echo $default['group3'] ?>
                            &nbsp;&nbsp;&nbsp;&nbsp; 
                            <input type='radio' id='kapuk' name='Site' value='<?php echo $default['group4'] ?>' <?php echo $default['checked_group4'] ?> >&nbsp;&nbsp;<?php echo $default['group4'] ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <input type='radio' id='bitung' name='Site' value='<?php echo $default['group5'] ?>' <?php echo $default['checked_group5'] ?> >&nbsp;&nbsp;<?php echo $default['group5'] ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <span id="err_f03"></span>
                        </div>                                
                    </div>                  
                    <button type="button" id="btn_process" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Proceed</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="processdata-modal" class="modal hide fade" tabindex="-1"> 
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Incomplete By System Process</h3>
    </div>
    <div class="modal-body">   
        <p id="textloading" style="display:none;">
            <img id="loading" src="<?php echo $base_url; ?>public/avatar/76.GIF" style="/*display:none;*/">
            Processing . . .
        </p>
        <p id="textfinished" style="display:none;">
            Proceed Successed
        </p>
    </div>

</div> 


<script>
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

    $(document).ready(function() {
        var process = $("#btn_process");
        $("#f01").datetimepicker({dateFormat: "dd-mm-yy"});

        process.click(function() {
            var url_posting = '<?php echo site_url('proc03/home/processdata') ?>';
            var f01 = $("#f01").val();
            var f02 = $('input:radio[name=Absence]:checked').val();
            var f03 = $('input:radio[name=Site]:checked').val();


            $("#textfinished").hide();
            $('#processdata-modal').modal();
            $("#textloading").show();
            $(":button:contains('Finish')").attr("disabled", true).addClass("ui-state-disabled");


            var content = $("#content");
            $.ajax(
                    {
                        type: "POST",
                        url: url_posting,
                        dataType: "json",
                        data: "f01=" + f01 + "&f02=" + f02 + "&f03=" + f03,
                        cache: false,
                        success:
                                function(data, text)
                                {
                                    if (data.valid == 'true') {
                                        $("#textloading").hide();
                                        $("#textfinished").show();
                                        $(":button:contains('Finish')").attr("disabled", false).removeClass("ui-state-disabled");
                                        $('#processdata-modal').modal('hide');
                                        $.gritter.add({
                                            title: 'INFORMATION',
                                            text: data.mesg,
                                            class_name: 'gritter-light',
                                            fade_in_speed: 100,
                                            fade_out_speed: 100,
                                            time: 2500
                                        });
                                        return false;
                                    }
                                    else {
                                        $('#processdata-modal').modal('hide');

                                        $.gritter.add({
                                            title: 'WARNING',
                                            text: data.mesg,
                                            image: '<?php echo $base_url . 'public/theme/images/warni.jpeg' ?>',
                                            class_name: 'gritter-light',
                                            fade_in_speed: 100,
                                            fade_out_speed: 100,
                                            time: 2500
                                        });
                                        return false;
                                        $("#error_f01").html(data.err_f01).fadeIn('slow');
                                        $("#error_f02").html(data.err_f02).fadeIn('slow');
                                    }
                                },
                        error: function(request, status, error) {
                            alert(request.responseText + " " + status + " " + error);
                        }
                    });
            return false;


        });

    });

</script>
