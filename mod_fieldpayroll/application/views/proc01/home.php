<?php $base_url = $this->session->userdata('sess_base_url') ?> 
<style>
    a.ui-dialog-titlebar-close { display:block; }
</style>

<!-- JQueryUI -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-latest"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/development-bundle/demos/demos.css" rel="stylesheet" />

<script src="<?php echo $base_url ?>public/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 

<!-- Gritter Notifications Plugin -->
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>  
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />


<div class="box-generic">
    <!-- Tabs Heading -->
    <div class="tabsbar">
        <ul>
            <li class="glyphicons restart active"><a href="#payslip" data-toggle="tab"><i></i>MONTHLY PROCESS<strong></strong></a></li>
        </ul>
    </div>
    <!-- // Tabs Heading END -->
    <div class="tab-content">
        <!-- Tab content -->
        <div class="tab-pane active" id="payslip">
            <div class="widget">               
                <div class="widget-body">
                    <div class="control-group">
                        <label class="control-label" for="f01">From Date</label>
                        <div class="controls">
                            <input class="span2" id="f01" name="f01" type="text"  value="<?php echo set_value('f01', isset($default['f01']) ? $default['f01'] : ''); ?>" 
                            <?php echo (isset($default['readonly_f01'])) ? $default['readonly_f01'] : ''; ?>
                                   />
                            <span id="err_f01"></span>
                        </div>                                
                    </div>   
                    <div class="control-group">
                        <label class="control-label" for="f02">Until Date</label>
                        <div class="controls">
                            <input class="span2" id="f02" name="f02" type="text"  value="<?php echo set_value('f02', isset($default['f02']) ? $default['f02'] : ''); ?>" 
                            <?php echo (isset($default['readonly_f02'])) ? $default['readonly_f02'] : ''; ?>
                                   />
                            <span id="err_f02"></span>
                        </div>                                
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="f03">FullName</label>
                        <div class="controls">
                            <input class="span2" id="f03" name="f03" type="text"  value="<?php echo set_value('f03', isset($default['f03']) ? $default['f03'] : ''); ?>" 
                            <?php echo (isset($default['readonly_f03'])) ? $default['readonly_f03'] : ''; ?>
                                   />
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
        <h3>Monthly Process</h3>
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
        $("#f01").datepicker({dateFormat: "dd-mm-yy"});
        $("#f02").datepicker({dateFormat: "dd-mm-yy"});


        var url_suggest = '<?php echo site_url('proc01/home/suggest_employee'); ?>';
        $("#f03").autocomplete({
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

	
	process.unbind("click");
        process.click(function() {
            var url_posting = '<?php echo site_url('proc01/home/postingmonthly') ?>';
            var fromdate = $("#f01").val();
            var untildate = $("#f02").val();
            var nip = $("#f03").val();


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
                        data: "f01=" + fromdate + "&f02=" + untildate + "&f03=" + nip,
                        cache: false,
                        success:
                                function(data, text)
                                {
                                    if (data.valid == 'true') {
                                        $("#textloading").hide();
                                        $("#textfinished").show();
                                        $(":button:contains('Finish')").attr("disabled", false).removeClass("ui-state-disabled");
                                        $('#processdata-modal').modal('hide');
                                    }
                                    else {
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
