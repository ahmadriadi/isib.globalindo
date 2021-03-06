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
            <li class="glyphicons tag active"><a href="#presencejurnal" data-toggle="tab"><i></i>REPORT SUMMARY EMPLOYEE<strong></strong></a></li>


        </ul>

    </div>
    <!-- // Tabs Heading END -->
    <div class="tab-content">
        <!-- Tab content -->
        <div class="tab-pane active" id="presencejurnal">
            <div class="widget">               
                <div class="widget-body"> 
                    <div class="row-fluid">
                        <!-- Column ke 1 -->
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

                        <!-- Column ke 2 -->
                        <div class="span4">
                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f03">Job Group</label>
                                <div class="controls">
                                    <?php
                                    $no = 'A';
                                    foreach ($default['f03'] as $row) {
                                        ?>  
                                        <input id="f03<?php echo $no ?>" name="f03" type="radio" 
                                               value="<?php echo (isset($row['value'])) ? $row['value'] : ''; ?>"
                                               <?php echo (isset($row['checked'])) ? $row['checked'] : ''; ?> >
                                        <?php echo (isset($row['display'])) ? $row['display'] : ''; ?> <br/>
                                        <?php
                                        $no++;
                                    }
                                    ?>
                                    <span id="err_f03"></span>

                                </div>                                
                            </div> 
                            <!-- Group end -->  
                        </div>

                        <!-- Coloum 3 -->  
                        <div class="span4">
                            <div class="control-group">
                                <div class="controls">
                                    <div class="control-group">
                                        <label class="control-label" for="f03">Location</label>
                                        <div class="controls">                                            
                                            <input type='radio' id='kapuk' name='Site' value='<?php echo $default['site1'] ?>' <?php echo $default['checked_site1'] ?> >&nbsp;&nbsp;<?php echo $default['site1'] ?>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type='radio' id='bitung' name='Site' value='<?php echo $default['site2'] ?>' <?php echo $default['checked_site2'] ?> >&nbsp;&nbsp;<?php echo $default['site2'] ?>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <span id="err_f03"></span>
                                        </div>                                
                                    </div>       
                                </div>                                
                            </div>
                            
                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="dept">Departement</label>
                                <div class="controls">
                                    <select id="dept" name="dept" >
                                        <?php foreach ($default['dept'] as $row) { ?>
                                            <option value="<?php echo (isset($row['value'])) ? $row['value'] : ''; ?>" 
                                                    <?php echo (isset($row['selected'])) ? $row['selected'] : ''; ?> >
                                                <?php echo (isset($row['display'])) ? $row['display'] : ''; ?></option>
                                        <?php } ?>
                                    </select>                                       
                                    <span id="err_05"></span>
                                </div>                                
                            </div> 
                            <!-- Group end -->  

                        </div>
                    </div> 
                    <hr class="separator" />
                    <div class="form-actions" align="center">
                        <button type="button" id="btn_process" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Proceed</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  
<script>
    $(document).ready(function() {
        var process = $("#btn_process");
        $("#f01").datepicker({dateFormat: "dd-mm-yy"});
        $("#f02").datepicker({dateFormat: "dd-mm-yy"});

        process.click(function() {
            var url_presence = '<?php echo site_url('rpt03/home/presencedata') ?>';
            var fromdate = $("#f01").val();
            var untildate = $("#f02").val();
            var dept = $("#dept").val();
            var group = $('input:radio[name=f03]:checked').val();            
            var tempat = $('input:radio[name=Site]:checked').val();
            
            var content = $("#content .innerLR");
            var site = 'mod_public/index.php/rpt03/home/iframedata/' + fromdate + '/' + untildate + '/' + dept+'/'+group+'/'+tempat;
            var url = ROOT.base_url + site;
            var postdata = url_presence + '/' + fromdate + '/' + untildate + '/' + dept+'/'+group+'/'+tempat;

            
            
           
            if (dept == '' || dept == 'NULL') {
                alert('Please Select Departement,,!');
            } else {
                //alert(postdata);
                $.ajax(
                        {
                            type: "POST",
                            url: postdata,
                            dataType: "json",
                            cache: false,
                            success:
                                    function(data, text)
                                    {
                                        if (data.valid == 'true') {
                                            content.fadeOut("slow", "linear");
                                            content.load(url);
                                            content.fadeIn("slow");

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



            }



        });

    });

</script>

