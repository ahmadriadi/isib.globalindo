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
            <li class="glyphicons tag active"><a href="#payslip" data-toggle="tab"><i></i>REPORT PAYSLIP EMPLOYEE<strong></strong></a></li>
        </ul>
    </div>
    <!-- // Tabs Heading END -->
    <div class="tab-content">
        <!-- Tab content -->
        <div class="tab-pane active" id="payslip">
            <div class="widget">               
                <div class="widget-body">
                    <div class="row-fluid">
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label" for="f01">From Date</label>
                                <div class="controls">
                                    <input class="span5" id="f01" name="f01" type="text"  value="<?php echo set_value('f01', isset($default['f01']) ? $default['f01'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f01'])) ? $default['readonly_f01'] : ''; ?>
                                           />
                                    <span id="err_f01"></span>
                                </div>                                
                            </div>   
                        </div>
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label" for="f02">Until Date</label>
                                <div class="controls">
                                    <input class="span5" id="f02" name="f02" type="text"  value="<?php echo set_value('f02', isset($default['f02']) ? $default['f02'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f02'])) ? $default['readonly_f02'] : ''; ?>
                                           />
                                    <span id="err_f02"></span>
                                </div>                                
                            </div>
                        </div>
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label" for="f03">FullName</label>
                                <div class="controls">
                                    <input class="span7" id="f03" name="f03" type="text"  value="<?php echo set_value('f03', isset($default['f03']) ? $default['f03'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f03'])) ? $default['readonly_f03'] : ''; ?>
                                           />
                                    <span id="err_f03"></span>
                                </div>                                
                            </div>
                        </div>
                    </div>

                    <div align="center">
                        <button type="button" id="btn_submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Print to Slip</button>
                        <button type="button" id="btn_excel" class="btn btn-icon btn-primary glyphicons download"><i></i>Export to Excel</button>
                    </div>
                </div>
            </div>
        </div>
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
        var process = $("#btn_submit");
        var excel = $("#btn_excel");
        $("#f01").datepicker({dateFormat: "dd-mm-yy"});
        $("#f02").datepicker({dateFormat: "dd-mm-yy"});


        var url_suggest = '<?php echo site_url('rpt02/home/suggest_employee'); ?>';


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


        process.click(function() {
            var url_posting = '<?php echo site_url('rpt02/home/specialslip') ?>';
            var fromdate = $("#f01").val();
            var untildate = $("#f02").val();
            var nip = $("#f03").val();
            var url = "<?php echo site_url('rpt02/home/iframedata') ?>" + "/" + untildate + "/" + fromdate + "/" + untildate + "/" + nip;



            var content = $("#content");
            $.ajax(
                    {
                        type: "POST",
                        url: url_posting,
                        dataType: "json",
                        data: "f01=" + fromdate + "&f02=" + untildate,
                        cache: false,
                        success:
                                function(data, text)
                                {
                                    if (data.valid == 'true') {

                                        content.load(url);

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
        
        
         excel.click(function() {
            var url_posting = '<?php echo site_url('rpt02/home/specialslip') ?>';
            var fromdate = $("#f01").val();
            var untildate = $("#f02").val();
            var nip = $("#f03").val();
            var url = "<?php echo site_url('rpt02/home/exportdata') ?>" + "/" + untildate + "/" + fromdate + "/" + untildate + "/" + nip;


            $.ajax(
                    {
                        type: "POST",
                        url: url_posting,
                        dataType: "json",
                        data: "f01=" + fromdate + "&f02=" + untildate,
                        cache: false,
                        success:
                                function(data, text)
                                {
                                    if (data.valid == 'true') {

                                     window.location.href = url;


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
                           // alert(request.responseText + " " + status + " " + error);
                        }
                    });
            return false;


        });

    });

</script>

