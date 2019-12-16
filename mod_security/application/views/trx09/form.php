<?php $base_url = $this->session->userdata('sess_base_url') ?> 


<!-- JQueryUI -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-latest"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<!--
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/development-bundle/demos/demos.css" rel="stylesheet" />
-->
<!-- Time -->
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/timepicker/timepicker.css" rel="stylesheet" />
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/timepicker/timepicker.js"></script>
<!--wysihtml5-->
<link href="<?php echo $base_url; ?>public/bootstrap/extend/bootstrap-wysihtml5/css/bootstrap-wysihtml5-0.0.2.css" rel="stylesheet"> 
<script src="<?php echo $base_url; ?>public/bootstrap/extend/bootstrap-wysihtml5/js/bootstrap-wysihtml5-0.0.2.js"></script>  
<!-- Gritter Notifications Plugin -->
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>  
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />
<!-- Content -->

        <!-- Form -->
        <form class="form-horizontal" style="margin-bottom: 0;" id="validateSubmitForm" method="get" autocomplete="off">
            <!-- Widget -->
            <div class="widget"
                 <!-- Widget heading -->
                 <div class="widget-head">
                    <h4 class="heading">Form</h4>
                </div>
                <!-- // Widget heading END -->
                <div class="widget-body">
                    <!-- Row -->
                    <div class="row-fluid">
                        <!-- Column ke 1 -->
                        <div class="span6">
                            <div class="control-group">
                              <label class="control-label" for="f01">Main Problem</label>
                                <div class="controls">
                                    <select id="f01" name="f01" >
                                        <?php foreach ($default['f01'] as $row) { ?>
                                            <option value="<?php echo (isset($row['value'])) ? $row['value'] : ''; ?>" 
                                                    <?php echo (isset($row['selected'])) ? $row['selected'] : ''; ?> >
                                                        <?php echo (isset($row['display'])) ? $row['display'] : ''; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <span id="err_f01"></span>
                                </div>                                
                            </div> 

			      <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f03">Location</label>
                                <div class="controls">
                                     <?php
                                    $no = 'A';
                                    foreach ($default['f03'] as $row) {
                                        ?>  
                                        <input id="f03<?php echo $no ?>" name="f03" type="radio" 
                                               value="<?php echo (isset($row['value'])) ? $row['value'] : ''; ?>"
                                               <?php echo (isset($row['checked'])) ? $row['checked'] : ''; ?> >
                                               <?php echo (isset($row['display'])) ? $row['display'] : ''; ?> 
                                               <?php
                                               $no++;
                                           }
                                           ?>
                                    <span id="err_f08"></span>
                                </div>                                
                            </div> 
                            <!-- Group end -->			
			
                        </div>
                        <!-- // Column END -->
                    </div>
                    <br>
                    <div class="row-fluid">
                        <div class="span12">
                             <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f02">Description</label>
                                <div class="controls">
                                    <textarea id="f02" class="wysihtml5 span6" name="f02" cols="18" rows="3" <?php echo (isset($default['readonly_f02'])) ? $default['readonly_f02'] : ''; ?> ><?php echo (isset($default['f02'])) ? $default['f02'] : ''; ?></textarea><span style="color:white;" id="err_f02"></span>                                   
                                    <span id="err_f02"></span>
                                </div>
                            </div> 
                            <!-- Group end -->                            
                        </div>
                    </div>
                    <!-- // Row END -->
                    <hr class="separator" />
                    <!-- Form actions -->
                    <div class="form-actions" align="center">
                        <button type="button" id="btn_save"   class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Submit</button>
                        <button type="button" id="btn_cancel" class="btn btn-icon btn-default glyphicons circle_remove"><i></i>Cancel</button>

                    </div>
                    <!-- // Form actions END -->
                </div>
            </div>
            <!-- // Widget END -->
        </form>
        <!-- // Form END -->
<script type="text/javascript">
    function loading() {
        bootbox.alertloading("<center>Processing. Please wait !!!<br><img src='<?php echo $base_url; ?>public/avatar/76.GIF'></center>");
    }

    $(document).ready(function()
    {
       $('textarea.wysihtml5').wysihtml5();
        
        var save = $('#btn_save');
        var cancel = $('#btn_cancel');

         $("#f01").change(function() {
            var type = $("#f01").val();
            
            //alert(type);
        });
        
       
        save.click(
                function()
                {

                    var f01 = $("#f01").val();
                    var f02 = $("#f02").val();
 		    var f03 = $('input:radio[name=f03]:checked').val();
                     
                    var content = $("#content .innerLR");
                    var site = 'mod_security/index.php/trx09/home';
                    var siterequest = 'mod_security/index.php/trx09/home/tabmenu';
                    var url = ROOT.base_url + site;
                    var urlrequest = ROOT.base_url + siterequest;
                    var postdata = "f01=" + f01 + "&f02=" + f02+ "&f03=" + f03;
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
                                                            
                                                            
                                                            
                                                            if(data.idroot=='9'){
                                                                
                                                                 content.fadeOut("slow", "linear");
                                                                 content.load(urlrequest+'/'+data.counterdata+'/add');
                                                                 content.fadeIn("slow");
                                                            }else{                                                                
                                                                 content.fadeOut("slow", "linear");
                                                                 content.load(url);
                                                                 content.fadeIn("slow");
                                                            } 
                                                            
                                                           

                                                        } else {
                                                            bootbox.hideAll();
                                                            $.gritter.add({
                                                                title: 'WARNING',
                                                                text: data.mesg,
                                                                image: '<?php echo $base_url . 'public/theme/images/warni.jpeg' ?>',
                                                                class_name: 'gritter-light',
                                                                fade_in_speed: 100,
                                                                fade_out_speed: 100,
                                                                time: 2500
                                                            });

                                                            $("#err_f02").html(data.err_f02).fadeIn('slow');
                                                            $("#err_f02").html(data.err_f02).fadeIn('slow');
                                                         

                                                        }
                                                    },
                                            error: function(request, status, error) {
												bootbox.hideAll();
                                                alert(request.responseText + " " + status + " " + error);
                                            }
                                        });
                                return false;


                });

        cancel.click(
                function()
                {
                    var content = $("#content .innerLR");
                    var site = 'mod_security/index.php/trx09/home';
                    var url = ROOT.base_url + site;
                    content.fadeOut("slow", "linear");
                    content.load(url);
                    content.fadeIn("slow");

                });
		
		//var firstchild = $(".wysihtml5-toolbar li:first-child").html();
		//alert(firstchild);
		$(".wysihtml5-toolbar li:first-child").remove();
    });



</script> 


