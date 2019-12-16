<?php $base_url = $this->session->userdata('sess_base_url') ?> 
<style>
    a.ui-dialog-titlebar-close { display:block; }
</style>

<!-- JQueryUI -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-latest.js"></script>
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
            <li class="glyphicons tag active"><a href="#presencejurnal" data-toggle="tab"><i></i>RESTORE DATA EMPLOYEE<strong></strong></a></li>
        </ul>
    </div>
    <!-- // Tabs Heading END -->
    <div class="tab-content">
        <!-- Tab content -->
        <div class="tab-pane active" id="presencejurnal">
            <div class="widget">               
                <div class="widget-body">                       
                    <div class="control-group">
                        <label class="control-label" for="f01">Name</label>
                        <div class="controls">
                            <input class="span2" id="f01" name="f01" type="text"  value="<?php echo set_value('f01', isset($default['f01']) ? $default['f01'] : ''); ?>" 
                            <?php echo (isset($default['readonly_f01'])) ? $default['readonly_f01'] : ''; ?>
                                   />
                            <span id="err_f01"></span>
                        </div>                                
                    </div>
                    <button type="button" id="btn_submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="processdata-modal" class="modal hide fade" tabindex="-1"> 
    <div class="modal-header">
        <h3>Restore Data Process</h3>
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

    $(document).ready(function() {

        var btnprocess = $("#btn_submit");
        
        

        $.ajax({
            url: '<?php echo site_url('tools/home/autocomplete_employee'); ?>',
            dataType: 'json',
            success: function(d) {
                var data = d;
                $('#f01').typeahead({
                    source: function(query, process) {
                        objects = [];
                        $.each(data, function(i, object) {
                            objects.push(object.fullname + '-'
                                    + object.idemployee);
                        });
                        process(objects);
                    },
                    items: 10,
                    updater: function(item) {
                        var s = item.split('-');
                        return s[1]; /* data is array*/
                        return item;
                    },
                    matcher: function(item) {
                        var s = item.split('-');
                        return s[0].toLowerCase().indexOf(this.query.toLowerCase()) != -1
                    }
                });
            }
        });


     
        btnprocess.click(function() {
               $("#textfinished").hide();
                $('#processdata-modal').modal({
                    backdrop: 'static', // for disable close
                    keyboard: false //for disable escp
                });
                $("#textloading").show();
                $(":button:contains('Finish')").attr("disabled", true).addClass("ui-state-disabled");


            
        
        
            var url_process= '<?php echo site_url('tools/home/restore') ?>';
            var nip = $("#f01").val();

            $.ajax(
                    {
                        type: "POST",
                        url: url_process,
                        dataType: "json",
                        data: "nip=" + nip,
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
                                            image: '<?php echo $base_url . 'public/theme/images/informa.png' ?>',
                                            class_name: 'gritter-light',
                                            fade_in_speed: 100,
                                            fade_out_speed: 100,
                                            time: 2500
                                        });
                                        return false;

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

