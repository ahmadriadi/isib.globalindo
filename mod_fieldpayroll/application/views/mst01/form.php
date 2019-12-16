<?php $base_url = $this->session->userdata('sess_base_url') ?> 
  

<!-- JQueryUI -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-latest.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/development-bundle/demos/demos.css" rel="stylesheet" />

<script src="<?php echo $base_url ?>public/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 

<!-- Time -->
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/timepicker/timepicker.css" rel="stylesheet" />
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/timepicker/timepicker.js"></script>


<!-- Gritter Notifications Plugin -->
 <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>  
 <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />



<!-- Content -->

<div id="content-wrap">
    <h3>Form Field Payroll</h3>
    <div class="innerLR">
        <!-- Form -->
        <form class="form-horizontal" style="margin-bottom: 0;" id="validateSubmitForm" method="get" autocomplete="off">
            <!-- Widget -->
            <div class="widget"
                 <!-- Widget heading -->
                 <div class="widget-head">
                    <h4 class="heading"></h4>
                </div>
                <!-- // Widget heading END -->
                <div class="widget-body">
                    <!-- Row -->
                    <div class="row-fluid">
                        <!-- Column ke 1 -->
                        <div class="span6">
                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f01">IDEmployee</label>
                                <div class="controls">
                                    <input class="span5" id="f01" name="f01" type="text"  value="<?php echo set_value('f01', isset($default['f01']) ? $default['f01'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f01'])) ? $default['readonly_f01'] : ''; ?>
                                           />
                                    <span id="err_f01"></span>
                                </div>                                
                            </div>                         
                            <div class="control-group">
                                <label class="control-label" for="f02">FullName</label>
                                <div class="controls">
                                    <input class="span6" id="f02" name="f02" type="text"  value="<?php echo set_value('f02', isset($default['f02']) ? $default['f02'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f02'])) ? $default['readonly_f02'] : ''; ?>
                                           />
                                    <span id="err_f02"></span>
                                </div>                                
                            </div> 
                            <!-- Group end -->
                                <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f03">Bank Account</label>
                                <div class="controls">
                                    <input class="span6" id="f03" name="f03" type="text"  value="<?php echo set_value('f03', isset($default['f03']) ? $default['f03'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f03'])) ? $default['readonly_f03'] : ''; ?>
                                           />
                                    <span id="err_f03"></span>
                                </div>                                
                            </div> 
                            <!-- Group end -->                            
                           <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f04">Monthly Salary</label>
                                <div class="controls">
                                    <input class="span4" id="f04" name="f04" type="text"  value="<?php echo set_value('f04', isset($default['f04']) ? $default['f04'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f04'])) ? $default['readonly_f04'] : ''; ?>
                                           />
                                    <span id="err_f04"></span>                                    
                                </div>                                
                            </div> 
                            <!-- Group end -->                                                 
                           <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f05">BPJS Tenaga Kerja</label>
                                <div class="controls">
                                    <input class="span4" id="f05" name="f05" type="text"  value="<?php echo set_value('f05', isset($default['f05']) ? $default['f05'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f05'])) ? $default['readonly_f05'] : ''; ?>
                                           />
                                    <span id="err_f05"></span>                                    
                                </div>                                
                            </div> 
                            <!-- Group end --> 
                            
                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f06">BPJS Kesehatan</label>
                                <div class="controls">
                                    <input class="span4" id="f06" name="f06" type="text"  value="<?php echo set_value('f06', isset($default['f06']) ? $default['f06'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f06'])) ? $default['readonly_f06'] : ''; ?>
                                           />
                                    <span id="err_f06"></span>                                    
                                </div>                                
                            </div> 
                            <!-- Group end -->    

                        </div>
                        <!-- // Column 1 END -->
                        
                         <!-- Column ke 2 -->
                         <div class="span6">            
                          <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f07">Daily Salary</label>
                                <div class="controls">
                                    <input class="span4" id="f07" name="f07" type="text"  value="<?php echo set_value('f07', isset($default['f07']) ? $default['f07'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f07'])) ? $default['readonly_f07'] : ''; ?>
                                           />
                                    <span id="err_f07"></span>                                    
                                </div>                                
                            </div> 
                            <!-- Group end -->       
                          <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f08">Overtime Per Hour</label>
                                <div class="controls">
                                    <input class="span4" id="f08" name="f08" type="text"  value="<?php echo set_value('f08', isset($default['f08']) ? $default['f08'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f08'])) ? $default['readonly_f08'] : ''; ?>
                                           />
                                    <span id="err_f08"></span>                                    
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


    $(document).ready(function()
    {
        var save = $('#btn_save');
        var cancel = $('#btn_cancel');
        	

	var pointdaily = '<?php echo $pointdaily ?>';
        var pointovertime = '<?php echo $pointovertime ?>';
        var pointinsurance = '<?php echo $pointinsurance ?>';
        var pointbpjs = '<?php echo $pointbpjs ?>';
	


	 $.ajax({
	    url: '<?php echo site_url('mst01/home/autocomplete_employee'); ?>',
	    dataType: 'json',
	    success: function(d) {
		var data = d;
		$('#f02').typeahead({
		    source: function(query, process) {
			objects = [];
			$.each(data, function(i, object) {                                                   
			    objects.push(object.fullname + '-' 
					 + object.idemployee+'-'+object.bank);
			});
			process(objects);
		    },
		    items: 10,
		    updater: function(item) {
			var s = item.split('-');
			$('#f01').val(s[1]); /* for add item */
			$('#f03').val(s[2]); /* for add item */
			return s[0]; /* data is array*/
			return item;
		    },
		    matcher: function(item) {
			var s = item.split('-');
			return s[0].toLowerCase().indexOf(this.query.toLowerCase()) != -1
		    }
		});
	    }
	});
        
        
        $("#f04").focusout(function() {        
         var nip = $("#f01").val();
                $.ajax(
                        {
                            type: "POST",
                            url: "<?php echo site_url('mst01/home/get_group')?>",
                            dataType: "json",
                            data: "nip=" + nip,
                            cache: false,
                            success:
                                    function(data, text)
                                    {
                                        if (data.valid == 'true') {                                            
                                            var group = data.group;
                                            
					    /*	
                                            if(group=='LT'){
                                              var insurance = (2/100) * $("#f04").val();
                                            }else{
                                              var insurance = (0.25/100) * $("#f04").val();
                                            } 
					    */	
                                            

					    var insurance = (pointinsurance/100) * $("#f04").val();		
					   // var bpjs = (pointbpjs/100) * 2441000; //gapok di generalkan 2441000 sementara untuk penggajian 24-01-2015, request aryana 21-01-2015

					     var bpjs = (pointbpjs/100) * 2700000; //nilai berdasarkan gapok revisi hrd 20-03-2015 
					
			
                                            
            
                                            //var dailysalary = $("#f04").val()/30;
                                            
                                            /* Pertanggal 25-11-2014  intruksi pak leo 
                                               maka perhitungan dailysalary di rubah menjadi 31 hari  
					    */   

					     //var dailysalary = $("#f04").val()/31;


					     /* revisi (12-12-2014) Pertanggal 25-12-2014  intruksi pak leo 
                                               maka perhitungan dailysalary di rubah menjadi 31 hari namun belum fix, maka di kembalikan pada perhitungan 30 hari 
					    */   


				/* intruksi manajemen pertanggal 25-02-2015 untuk daily salary menjadi 31 hari */	 
                                            var dailysalary = $("#f04").val()/pointdaily;      
                                            var dailyovertime = $("#f04").val()/pointovertime;   
                                            
                                                                                     
                                            $("#f05").val(insurance);
                                            $("#f06").val(bpjs);
                                            $("#f07").val(dailysalary);
                                            $("#f08").val(dailyovertime);
                                        }
                                    },
                            error: function(request, status, error) {
                                alert(request.responseText + " " + status + " " + error);
                            }
                        });
                return false
        
        });
        

        save.click(
                function()
                {

                    var f01 = $("#f01").val();
                    var f02 = $("#f02").val();                   
                    var f03 = $("#f03").val();                   
                    var f04 = $("#f04").val();
                    var f05 = $("#f05").val();
                    var f06 = $("#f06").val();
                    var f07 = $("#f07").val();
                    var f08 = $("#f08").val();
                    
                    var content = $("#content .innerLR");
                    var site = 'mod_fieldpayroll/index.php/mst01/home';
                    var url = ROOT.base_url + site;
                    var postdata = "f01=" + f01 + "&f02=" + f02 + "&f03=" + f03 + "&f04=" + f04 + "&f05=" + f05
                                    + "&f06=" + f06 + "&f07=" + f07+ "&f08=" + f08;                 
                    var url_post = '<?php echo $url_post; ?>';
                    
                    //alert(postdata);
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
                                                
                                                
                                               bootbox.alert(data.mesg, function(result) 
                                                {

                                                }); 
                                                
                                                
                                                content.fadeOut("slow", "linear");
                                                content.load(url);
                                                content.fadeIn("slow");

                                            }else {
                                                
                                                 $.gritter.add({                                
                                                    title: 'WARNING',
                                                    text: data.mesg,
                                                    image: '<?php echo $base_url.'public/theme/images/warni.jpeg' ?>',
                                                    class_name: 'gritter-light',                                
                                                    fade_in_speed: 100, 
                                                    fade_out_speed: 100, 
                                                    time: 2500 
                                                });
                                                
                                                $("#err_f01").html(data.err_f01).fadeIn('slow');
                                                $("#err_f02").html(data.err_f02).fadeIn('slow');
                                                $("#err_f03").html(data.err_f03).fadeIn('slow');
                                                $("#err_f04").html(data.err_f04).fadeIn('slow');
                                                $("#err_f05").html(data.err_f05).fadeIn('slow');
                                                $("#err_f06").html(data.err_f06).fadeIn('slow');
                                                $("#err_f07").html(data.err_f07).fadeIn('slow');
                                                $("#err_f08").html(data.err_f08).fadeIn('slow');
                                                         
                                            }
                                        },
                                error: function(request, status, error) {
                                    alert(request.responseText + " " + status + " " + error);
                                }
                            });
                    return false;


                });

        cancel.click(
                function()
                {
                    var content = $("#content .innerLR");
                    var site = 'mod_fieldpayroll/index.php/mst01/home';
                    var url = ROOT.base_url + site;
                    //alert(url);
                    content.fadeOut("slow", "linear");
                    content.load(url);
                    content.fadeIn("slow");

                });
    });



</script> 
