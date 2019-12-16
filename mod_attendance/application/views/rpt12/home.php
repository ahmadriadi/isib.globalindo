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
            <li class="glyphicons tag active"><a href="#presencejurnal" data-toggle="tab"><i></i>REPORT PRESENCE ONE DAY<strong></strong></a></li>


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
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label" for="f01">Date</label>
                                <div class="controls">
                                    <input class="span5" id="f01" name="f01" type="text"  value="<?php echo set_value('f01', isset($default['f01']) ? $default['f01'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f01'])) ? $default['readonly_f01'] : ''; ?>
                                           />
                                    <span id="err_f01"></span>
                                </div>                                
                            </div>   
                            <div class="control-group">
                                <label class="control-label" for="f02">Name</label>
                                <div class="controls">
                                    <input class="span5" id="f02" name="f02" type="text"  value="<?php echo set_value('f02', isset($default['f02']) ? $default['f02'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f02'])) ? $default['readonly_f02'] : ''; ?>
                                           /><i>*)Blank to print data all employee</i> 
                                    <span id="err_f02"></span>
                                </div>                                
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <div class="control-group">
                                        <label class="control-label" for="f03">Location</label>
                                        <div class="controls">                                            
                                            <input type='radio' id='kapuk' name='Site' value='<?php echo $default['group4'] ?>' <?php echo $default['checked_group4'] ?> >&nbsp;&nbsp;<?php echo $default['group4'] ?>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type='radio' id='bitung' name='Site' value='<?php echo $default['group5'] ?>' <?php echo $default['checked_group5'] ?> >&nbsp;&nbsp;<?php echo $default['group5'] ?>
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <span id="err_f03"></span>
                                        </div>                                
                                    </div>       
                                </div>                                
                            </div>
                        </div>

                        <!-- Column ke 2 -->
                        <div class="span6">
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
                    </div> 
                    <hr class="separator" />
                    <div class="form-actions" align="center">
                        <button type="button" id="btn_submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Submit</button>
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
        $("#f01").datepicker({dateFormat: "dd-mm-yy"});

        /*
         var url_suggest = '<?php echo site_url('rpt12/home/suggest_employee'); ?>';
         $("#f02").autocomplete({
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
         */

        $.ajax({
            url: '<?php echo site_url('rpt12/home/autocomplete_employee'); ?>',
            dataType: 'json',
            success: function(d) {
                var data = d;
                $('#f02').typeahead({
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


        process.click(function() {
            var url_presence = '<?php echo site_url('rpt12/home/presencedata') ?>';
            var nowdate = $("#f01").val();
            var name = $("#f02").val();
            var group = $('input:radio[name=f03]:checked').val();
            var tempat = $('input:radio[name=Site]:checked').val();
            var content = $("#content .innerLR");
            var site = 'mod_attendance/index.php/rpt12/home/iframedata/'+tempat+'/'+ group + '/' + nowdate + '/' + name;
            var url = ROOT.base_url + site;

            var postdata = url_presence +'/'+tempat+ '/' + group + '/' + nowdate + '/' + name;


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

