<?php $base_url = $this->session->userdata('sess_base_url') ?> 

<script src="<?php echo $base_url ?>public/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>   

<form  id="form-do" class ="form-horizontal" style="margin-bottom: 0;" id="validateSubmitForm" method="get" autocomplete="off">
    <!-- Widget -->
    <div class="widget">
        <!-- Widget heading -->
        <div class="widget-head">
            <h4 class="heading">USER ACTIVITY</h4>
        </div>
        <!-- // Widget heading END -->
        <div class="widget-body">
            <!-- Row -->
            <div class="row-fluid">
                <!-- Column ke 1 -->
                <div class="span6">            
                   
                    <div>
                        <!-- Group -->
                        <div class="control-group">
                            <label class="control-label" for="f01"><p style="color:white;">Activity</p></label>
                            <div class="controls">                                   
                                <textarea id="f01" name="f01" cols="18" rows="3" <?php echo (isset($default['readonly_f01'])) ? $default['readonly_f01'] : ''; ?> ><?php echo (isset($default['f01'])) ? $default['f01'] : ''; ?></textarea><span style="color:white;" id="err_f01"></span>                                   
                            </div>                                
                        </div> 
                        <!-- Group end -->
                        
                         <!-- Group -->
                        <div class="control-group">
                            <label class="control-label" for="f01"><p style="color:white;">Level</p></label>
                            <div class="controls"> 
                                 <?php
                            foreach ($default['f05'] as $row) {
                                ?>  
                                <input id="f05" class="f05" name="f05" type="radio" 
                                       value="<?php echo (isset($row['value'])) ? $row['value'] : ''; ?>"
                                       <?php echo (isset($row['checked'])) ? $row['checked'] : ''; ?> >
                                       <?php echo (isset($row['display'])) ? $row['display'] : ''; ?> 
                                       <?php
                                   }
                                   ?>
                            </div>                                
                        </div> 
                        <!-- Group end -->
                        
                        
			 <!-- Group -->
                        <div class="control-group">
                            <label class="control-label" for="f04"><p style="color:white;">On</p></label>
                            <div class="controls">
				<select id="f04" name="f04" >
					<?php foreach ($default['f04'] as $row) { ?>
					    <option value="<?php echo (isset($row['value'])) ? $row['value'] : ''; ?>" 
						<?php echo (isset($row['selected'])) ? $row['selected'] : ''; ?> >
						<?php echo (isset($row['display'])) ? $row['display'] : ''; ?>
					    </option>
					<?php }?>
				 </select> 
				<span style="color:white;" id="err_f04"></span>                                   
                            </div>                                
                        </div> 
                        <!-- Group end -->
                        
                         <!-- Group -->
                        <div class="control-group">
                            <label class="control-label" for="f02"><p style="color:white;">Problem</p></label>
                            <div class="controls">                                   
                                <textarea id="f02" name="f02" cols="18" rows="3" <?php echo (isset($default['readonly_f02'])) ? $default['readonly_f02'] : ''; ?> ><?php echo (isset($default['f02'])) ? $default['f02'] : ''; ?></textarea><span style="color:white;" id="err_f02"></span>                                   
                            </div>                                
                        </div> 
                        <!-- Group end -->
                        
                         <!-- Group -->
                        <div class="control-group">
                            <label class="control-label" for="f03"><p style="color:white;">Solution</p></label>
                            <div class="controls">                                   
                                <textarea id="f03" name="f03" cols="18" rows="3" <?php echo (isset($default['readonly_f03'])) ? $default['readonly_f03'] : ''; ?> ><?php echo (isset($default['f03'])) ? $default['f03'] : ''; ?></textarea><span style="color:white;" id="err_f03"></span>                                   
                            </div>                                
                        </div> 
                        <!-- Group end -->

			 
                        
                        
                         <!-- Group -->
                        <div class="control-group" align="center">
                            <button type="button" id="btn_save"   class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Save</button>
                            <button type="button" id="btn_cancel" class="btn btn-icon btn-default glyphicons circle_remove"><i></i>Close</button>                             
                        </div> 
                        <!-- Group end -->

                   </div>
                <!-- // Column END -->
            </div>                   
            <!-- // Row END -->
          
        </div>
    </div>
    <!-- // Widget END -->
</form>




<script>


                                    $(document).ready(function() {

                                       

                                        var save            = $("#btn_save");
                                        var cancel          = $("#btn_cancel");
                                    
                                        save.click(function()
                                        {

                                           
                                            var f01 = $("#f01").val();
                                            var f02 = $("#f02").val();
                                            var f03 = $("#f03").val();
					    var f04 = $("#f04").val();	
                                            var f05 = $('input:radio[name=f05]:checked').val();
                                            var url_post = '<?php echo $url_post; ?>';
                                            var postdata = "f01=" + f01 + "&f02=" + f02 + "&f03=" + f03+ "&f04=" + f04 + "&f05=" + f05;


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
                                                                      
                                                                        var site = 'mod_security/index.php/trx07/home';
                                                                        var url = ROOT.base_url + site;                                                                           
                                                                      
                                                                        $("#content").fadeOut("slow", "linear");
                                                                        $("#content").load(url);
                                                                        $("#content").fadeIn("slow");
                                                                    }
                                                                    else {

                                                                        $("#err_f01").html(data.err_f01).fadeIn('slow');
                                                                        $("#err_f02").html(data.err_f02).fadeIn('slow');
                                                                        $("#err_f03").html(data.err_f03).fadeIn('slow');
                                                                       

                                                                    }
                                                                },
                                                        error: function(request, status, error) {
                                                            alert(request.responseText + " " + status + " " + error);
                                                        }
                                                    });


                                            return false;
                                        });


                                        cancel.click(function() {
                                            var site = 'mod_security/index.php/trx07/home';
                                            var url = ROOT.base_url + site;
                                            $("#content").fadeOut("slow", "linear");
                                            $("#content").load(url);
                                            $("#content").fadeIn("slow");
                                        });



                                    
                                 
     });                               
                                    
</script>
