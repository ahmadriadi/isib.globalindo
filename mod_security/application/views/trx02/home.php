<!-- Content -->
<?php $base_url = $this->session->userdata('sess_base_url'); ?>
<div id="content-wrap">
    <h3></h3>
    <div class="innerLR">
        <!-- Form -->
        <form class="form-horizontal" style="margin-bottom: 0;" id="validateSubmitForm" method="get" autocomplete="off">
            <!-- Widget -->
            <div class="widget"
                <!-- Widget heading -->
                <div class="widget-head">
                    <h4 class="heading">Change Password</h4>
                </div>
                <!-- // Widget heading END -->
                <div class="widget-body">
                    <!-- Row -->
                    <div class="row-fluid">
                        <!-- Column -->
                        <div class="span6">
                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="userid">User ID</label>
                                <div class="controls">
                                    <input class="span12" id="userid" name="userid" type="text" readonly value="<?php echo set_value('userid', isset($default['userid']) ? $default['userid'] : ''); ?>" />
                                </div>
                            </div>
                            <!-- // Group END -->

                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="fullname">Fullname</label>
                                <div class="controls">
                                    <input class="span12" id="fullname" name="fullname" type="text"  readonly value="<?php echo set_value('fullname', isset($default['fullname']) ? $default['fullname'] : ''); ?>" />
                                </div>
                            </div>
                            <!-- // Group END -->

                        </div>
                        <!-- // Column END -->

                        <!-- Column -->
                        <div class="span6">

                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="oldpassword">Old Password</label>
                                <div class="controls">
                                    <input class="span12" id="oldpassword" name="oldpassword" type="password" />
                                    <span class="field_error" id="error_oldpwd"></span>
                                </div>
                            </div>
                            <!-- // Group END -->

                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="newpassword">New password</label>
                                <div class="controls">
                                    <input class="span12" id="newpassword" name="newpassword" type="password" />
                                    <span class="field_error" id="error_newpwd"></span>
                                </div>
                            </div>
                            <!-- // Group END -->

                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="confirmpassword">Confirm Password</label>
                                <div class="controls">
                                    <input class="span12" id="confirmpassword" name="confirmpassword" type="password" />
                                    <span class="field_error" id="error_confirmpwd"></span>
                                </div>
                            </div>
                            <!-- // Group END -->

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
   var ROOT = {
           'site_url': '<?php echo $base_url . 'index.php'; ?>',
           'base_url': '<?php echo $base_url; ?>'
       };	


    var save = $("#btn_save");
    var cancel = $("#btn_cancel");
    save.click(function()
    {

        var userid = $("#userid").val();
        var fullname = $("#fullname").val();
        var oldpassword = $("#oldpassword").val();
        var newpassword = $("#newpassword").val();
        var confirmpassword = $("#confirmpassword").val();
        var vurl = "<?php echo $url_post; ?>";
        var vdata = "userid=" + userid +"&fullname=" + fullname +"&oldpassword=" + oldpassword + "&newpassword=" + newpassword + "&confirmpassword=" + confirmpassword;
        
        //alert(vurl);
        $.ajax(
                {
                    type: "POST",
                    url: vurl,
                    dataType: "json",
                    data: vdata,
                    cache: false,
                    success:
                            function(data, text)
                            {
                                if (data.valid == 'false')
                                {
                                    $("#error_oldpwd").html(data.error_oldpwd).fadeIn('slow');
                                    $("#error_newpwd").html(data.error_newpwd).fadeIn('slow');
                                    $("#error_confirmpwd").html(data.error_confirmpwd).fadeIn('slow');
                                }
                                else
                                {
                                    var msg = 'Your password has been change';
				    bootbox.alert(msg, function(result) 
		                         {
					      var url = ROOT.site_url;                                         
                                              window.location.href =url+'/login/logout';
		                         }); 

                                }
                            },
                    error: function(request, status, error) {
                        alert(request.responseText + " " + status + " " + error);
                    }
                });
        return false;
    });



    cancel.click(function()
    {
	    window.location.href ="<?php echo $this->session->userdata('sess_base_url'); ?>";	
		

    });

</script>    
