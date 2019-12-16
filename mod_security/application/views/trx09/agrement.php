<?php $base_url = $this->session->userdata('sess_base_url') ?> 
<!-- JQueryUI -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-latest"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<!--
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/development-bundle/demos/demos.css" rel="stylesheet" />
-->
<script src="<?php echo $base_url ?>public/bootstrap/js/bootstrap.min.js"></script>
<!-- Time -->
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/timepicker/timepicker.css" rel="stylesheet" />
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/timepicker/timepicker.js"></script>
<!-- Gritter Notifications Plugin -->
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>  
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />
<!-- Content -->



<div class="row-fluid">
    <div class="span3">
        <!-- Group -->
        <div class="control-group">
            <label class="control-label" for="f18r">Note</label>
            <div class="controls">
                <textarea id="f18r" name="f18r" cols="18" rows="7" <?php echo (isset($default['readonly_f18r'])) ? $default['readonly_f18r'] : ''; ?> ><?php echo (isset($default['f18r'])) ? $default['f18r'] : ''; ?></textarea>                                 
                <span id="err_f18r"></span>
            </div>  
        </div> 
        <!-- Group end -->  
    </div>

    <div class="span9">
        <!-- Group -->
        <div class="control-group">
            <label class="control-label" for="f19r">Accept</label>
            <div class="controls">
                <?php
                $no = 'A';
                foreach ($default['f19r'] as $row) {
                    ?>  
                    <input id="f19r<?php echo $no ?>" name="f19r" type="radio" 
                           value="<?php echo (isset($row['value'])) ? $row['value'] : ''; ?>"
                           <?php echo (isset($row['checked'])) ? $row['checked'] : ''; ?> >
                    <?php echo (isset($row['display'])) ? $row['display'] : ''; ?> <br/>
                    <?php
                    $no++;
                }
                ?>
                <span id="err_f18r"></span>
            </div>        
        </div> 
        <!-- Group end --> 
    </div>

</div>


<!-- Wizard pagination controls -->
<div align ="center" class="pagination margin-bottom-none">
    <ul>
        <li id="<?php echo $buttoncancel; ?>" class="danger previous first"><a href="javascript:;">Cancel</a></li>
        <li id="<?php echo $buttonsave; ?>" class="primary previous"><a href="javascript:;">Save</a></li>

    </ul>
</div>
<!-- // Wizard pagination controls END -->

<script type="text/javascript">
    var idh = '<?php echo $idh; ?>';
    var nip = '<?php echo $nip; ?>';
    var <?php echo $buttonsave; ?> = <?php echo "$(\"#$buttonsave\");" ?>
    var <?php echo $buttoncancel; ?> = <?php echo "$(\"#$buttoncancel\");" ?>


    function loading() {
        bootbox.alertloading("<center>Processing. Please wait !!!<br><img src='<?php echo $base_url; ?>public/avatar/76.GIF'></center>");
    }

    $(document).ready(function()
    {


<?php echo $buttonsave; ?>.click(
                function()
                {
                    var c1 = '';
                    var c2 = '';
                    var c3 = '';
                    var c4 = '';
                    var c5 = '';
                  
                    $('#checkuser:checked').each(function() {
                        c1 += $(this).val();
                    });
                    $('#checkcuser:checked').each(function() {
                        c2 += $(this).val();
                    });
                    $('#checksoftware:checked').each(function() {
                        c3 += $(this).val();
                    });
                    $('#checkcfolder:checked').each(function() {
                        c4 += $(this).val();
                    });
                    $('#checkafolder:checked').each(function() {
                        c5 += $(this).val();
                    });
                    
                    
                    var f01r = $("#f01r").val();
                    var f02r = $("#f02r").val();
                    var f03r = $("#f03r").val();
                    var f04r = $("#f04r").val();
                    var f05r = $("#f05r").val();
                    var f06r = $("#f06r").val();                    
                    var f07r = $('input:radio[name=f07r]:checked').val();
                    var f08r = $("#f08r").val(); 
                    var f09r = $("#f09r").val(); 
                    var f10r = $("#f10r").val(); 
                    var f11r = $('input:radio[name=f11r]:checked').val();
                    var f12r = $("#f12r").val(); 
                    var f13r = $('input:radio[name=f13r]:checked').val();
                    var f14r = $("#f14r").val(); 
                    var f15r = $('input:radio[name=f15r]:checked').val();
                    var f16r = $("#f16r").val();
                    var f17r = $('input:radio[name=f17r]:checked').val();
                    var f18r = $("#f18r").val();
                    var f19r = $('input:radio[name=f19r]:checked').val();

                    var content = $("#content .innerLR");
                    var site = 'mod_security/index.php/trx09/home';
                    var url = ROOT.base_url + site;
                    var postdata = "idh=" + idh + "&f01=" + f01r+ "&f02=" + f02r+ "&f03=" + f03r+ "&f04=" + f04r+ "&f05=" + f05r+ "&f06=" + f06r
                                    + "&f07=" + f07r+ "&f08=" + f08r+ "&f09=" + f09r+ "&f10=" + f10r+ "&f11=" + f11r+ "&f12=" + f12r+ "&f13=" + f13r
                                    + "&f14=" + f14r+ "&f15=" + f15r+ "&f16=" + f16r+ "&f17=" + f17r+ "&f18=" + f18r+ "&f19=" + f19r+"&nip="+nip+"&c1="+c1+"&c2="+c2+"&c3="+c3+"&c4="+c4+"&c5="+c5;
                    var url_post = '<?php echo $url_post; ?>';
                    
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
                                                    image: '<?php //echo $base_url . 'public/theme/images/warni.jpeg' ?>',
                                                    class_name: 'gritter-light',
                                                    fade_in_speed: 100,
                                                    fade_out_speed: 100,
                                                    time: 2500
                                                });

                                            }
                                        },
                                error: function(request, status, error) {
                                    bootbox.hideAll();
                                    alert(request.responseText + " " + status + " " + error);
                                }
                            });
                    return false;

                  
                });

<?php echo $buttoncancel; ?>.click(
                function()
                {
                    var content = $("#content .innerLR");
                    var site = 'mod_security/index.php/trx09/home';
                    var url = ROOT.base_url + site;
                    content.fadeOut("slow", "linear");
                    content.load(url);
                    content.fadeIn("slow");

                });
    });




</script>
