<?php $base_url = $this->session->userdata('sess_base_url') ?> 
<!-- JQueryUI -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-latest.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/development-bundle/demos/demos.css" rel="stylesheet" />
<!--bootstrap-->
<script src="<?php echo $base_url ?>public/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 

<!--wysihtml5-->
<link href="<?php echo $base_url; ?>public/bootstrap/extend/bootstrap-wysihtml5/css/bootstrap-wysihtml5-0.0.2.css" rel="stylesheet"> 
<script src="<?php echo $base_url; ?>public/bootstrap/extend/bootstrap-wysihtml5/js/bootstrap-wysihtml5-0.0.2.js"></script> 
<!-- Content -->
<style>
    .alertspan {       
        font-size: 13px;
        color: yellow;       
    }


</style>


<div id="content-wrap">
    <h3></h3>
    <div class="innerLR">
            <!-- Form -->
            <form id="formbm" class="form-horizontal" style="margin-bottom: 0;" id="validateSubmitForm" method="get" autocomplete="off">
                <!-- Widget -->
                <div class="widget"
                     <!-- Widget heading -->
                     <div class="widget-head">
                        <h4 class="heading">Form Generate for Loan Interest</h4>
                    </div>
                    <!-- // Widget heading END -->
                    <div class="widget-body">
                        <!-- Row -->
                        <div class="row-fluid">

                            <div class="span6">
                            
                             <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label" for="f01">Start from Posting Date</label>
                                    <div class="controls">
                                        <input class="span5" id="f01" name="f01" type="text"  value="<?php echo set_value('f01', isset($default['f01']) ? $default['f01'] : ''); ?>" 
                                        <?php echo (isset($default['readonly_f01'])) ? $default['readonly_f01'] : ''; ?>
                                               />
                                        <span id="err_f01"></span>
                                    </div>                                
                                </div> 

                                <!-- Group -->
                                <div class="control-group">
                                    <label class="control-label" for="f02">Reason for Generate</label>
                                    <div class="controls">
                                        <textarea id="f02tiny" class="wysihtml5 span100" name="f02" cols="18" rows="3" <?php echo (isset($default['readonly_f02'])) ? $default['readonly_f02'] : ''; ?> ><?php echo (isset($default['f02'])) ? $default['f02'] : ''; ?></textarea><span style="color:white;" id="err_f02"></span>                                   
                                        <span id="err_f02"></span>
                                    </div>
                                </div> 
                                <!-- Group end -->  

                            </div>
                            <!-- // Column 2 END -->  
                        </div> 

                        <!-- // Row END -->
                        <hr class="separator" />
                        <!-- Form actions -->
                        <div class="form-actions" align="center">
                            <button type="button" id="btn_save_generate"   class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Save</button>
                            <button type="button" id="btn_cancel_generate" class="btn btn-icon btn-default glyphicons circle_remove"><i></i>Cancel</button>

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

    $(document).ready(function ()
    {
        var save_generate = $('#btn_save_generate');
        var cancel_generate = $('#btn_cancel_generate');
        
        $("#f01").datepicker({dateFormat: "dd-mm-yy"});        
        
        
        tinyMCE.init({
            theme: "advanced",
            theme_advanced_toolbar_location: "top",
            theme_advanced_toolbar_align: "left",
            mode: "exact",
            elements: "f02tiny"
        });


        save_generate.click(
                function ()
                {
                    
						  var postingdate = $("#f01").val();
                    var note = encodeURIComponent(tinyMCE.get('f02tiny').getContent());
                    
                    var postdata = 'postingdate='+postingdate+'&note='+note;
                    var url_post = '<?php echo $urlpost; ?>';

                    $.ajax(
                            {
                                type: "POST",
                                url: url_post,
                                dataType: "json",
                                data: postdata,
                                cache: false,
                                success:
                                        function (data, text)
                                        {
                                            if (data.valid == 'true') {
                                                $("#styletbh1").remove();
                                                $("#styletbh2").remove();
                                                bootbox.hideAll();

                                            } else {
                                                alert(data.mesg);

                                              


                                            }
                                        },
                                error: function (request, status, error) {
                                    alert(request.responseText + " " + status + " " + error);
                                }
                            });
                    return false;

                });

        cancel_generate.click(
                function ()
                {

                    $("#styletbh1").remove();
                    $("#styletbh2").remove();
                    bootbox.hideAll();


                });


    });




</script> 
