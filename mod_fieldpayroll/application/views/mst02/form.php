<?php $base_url = $this->session->userdata('sess_base_url') ?>      
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>FORM PERSONAL LOAN</h3>
</div>
<div class="modal-body">
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
                                <input class="span10" id="f01" name="f01" type="text"  value="<?php echo set_value('f01', isset($default['f01']) ? $default['f01'] : ''); ?>" 
                                <?php echo (isset($default['readonly_f01'])) ? $default['readonly_f01'] : ''; ?>
                                       />
                                <span id="err_f01"></span>
                            </div>                                
                        </div> 
                        <!-- Group end -->
                        <!-- Group -->
                        <div class="control-group">
                            <label class="control-label" for="f02">FullName</label>
                            <div class="controls">
                                <input class="span10" id="f02" name="f02" type="text"  value="<?php echo set_value('f02', isset($default['f02']) ? $default['f02'] : ''); ?>" 
                                <?php echo (isset($default['readonly_f02'])) ? $default['readonly_f02'] : ''; ?>
                                       />
                                <span id="err_f02"></span>
                            </div>                        
                        </div> 
                        <!-- Group end --> 

                        <!-- Group -->
                        <div class="control-group">
                            <label class="control-label" for="f03">Loan Date</label>
                            <div class="controls">
                                <input class="span10" id="f03" name="f03" type="text"  value="<?php echo set_value('f03', isset($default['f03']) ? $default['f03'] : ''); ?>" 
                                <?php echo (isset($default['readonly_f03'])) ? $default['readonly_f03'] : ''; ?>
                                       />
                                <span id="err_f03"></span>
                            </div>                        
                        </div> 
                        <!-- Group end --> 
                        <!-- Group -->
                        <div class="control-group">
                            <label class="control-label" for="f04">Amount</label>
                            <div class="controls">
                                <input class="span10" id="f04" name="f04" type="text"  value="<?php echo set_value('f04', isset($default['f04']) ? $default['f04'] : ''); ?>" 
                                <?php echo (isset($default['readonly_f04'])) ? $default['readonly_f04'] : ''; ?>
                                       />
                                <span id="err_f04"></span>
                            </div>                        
                        </div> 
                        <!-- Group end --> 
                        <!-- Group -->
                    
                        <div class="control-group">
                            <label class="control-label" for="f09">Interest</label>
                            <div class="controls">
                                <input class="span2" id="f09" name="f09" type="text"  value="<?php echo set_value('f09', isset($default['f09']) ? $default['f09'] : ''); ?>" 
                                <?php echo (isset($default['readonly_f09'])) ? $default['readonly_f09'] : ''; ?>
                                       /> % <input class="span5" id="f10" name="f10" type="text"  value="<?php echo set_value('f10', isset($default['f10']) ? $default['f10'] : ''); ?>" 
                                <?php echo (isset($default['readonly_f10'])) ? $default['readonly_f10'] : ''; ?>
                                       /> Per Month
                                <span id="err_f09"></span>
                            </div>                        
                        </div> 
                        <!-- Group end --> 
                        
                     

                        <!-- Group -->
                        <div class="control-group">
                            <label class="control-label" for="f05">Term</label>
                            <div class="controls">
                                <input class="span10" id="f05" name="f05" type="text"  value="<?php echo set_value('f05', isset($default['f05']) ? $default['f05'] : ''); ?>" 
                                <?php echo (isset($default['readonly_f05'])) ? $default['readonly_f05'] : ''; ?>
                                       />
                                <span id="err_f05"></span>
                            </div>                        
                        </div> 
                        <!-- Group end -->
                        <!-- // Column 1 END -->
                    </div>
                    <!-- Column ke 2 -->
                    <div class="span6"> 
                        <!-- Group -->
                        <div class="control-group">
                            <label class="control-label" for="f06">Installment</label>
                            <div class="controls">
                                <input class="span10" id="f06" name="f06" type="text"  value="<?php echo set_value('f06', isset($default['f06']) ? $default['f06'] : ''); ?>" 
                                <?php echo (isset($default['readonly_f06'])) ? $default['readonly_f06'] : ''; ?>
                                       />
                                <span id="err_f06"></span>
                            </div>                        
                        </div> 
                        <!-- Group end -->  

                        <!-- Group -->
                        <div class="control-group">
                            <label class="control-label" for="f07">First Installment Date</label>
                            <div class="controls">
                                <input class="span10" id="f07" name="f07" type="text"  value="<?php echo set_value('f07', isset($default['f07']) ? $default['f07'] : ''); ?>" 
                                <?php echo (isset($default['readonly_f07'])) ? $default['readonly_f07'] : ''; ?>
                                       />
                                <span id="err_f07"></span>
                            </div>                        
                        </div> 
                        <!-- Group end -->                           

                        <!-- Group -->
                        <div class="control-group">
                            <label class="control-label" for="f08">Description</label>
                            <div class="controls">
                                <textarea id="f08" name="f08" cols="18" rows="3"<?php echo (isset($default['readonly_f08'])) ? $default['readonly_f08'] : ''; ?> ><?php echo (isset($default['f08'])) ? $default['f08'] : ''; ?></textarea>                                          
                                <span id="err_f08"></span>
                            </div>                        
                        </div> 
                        <!-- Group end -->  
                        <!-- // Column 2 END -->
                    </div>
                </div>

            </div>                   
            <!-- // Row END -->
            <hr class="separator" />
            <!-- Form actions -->
        </div>
    </form>
    <!-- // Form END -->
</div>
<!-- // Widget END  --->
<div class="modal-footer">                    
    <button type="button" id="btn_save"   class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Save</button>
    <button type="button" id="btn_cancel" class="btn btn-icon btn-default glyphicons circle_remove"><i></i>Cancel</button>
</div>


<script type="text/javascript">
    $(document).ready(function()
    {
        var save = $("#btn_save");
        var cancel = $("#btn_cancel");
        var url_post = '<?php echo $url_post ?>';
	
	

	$.ajax({
	    url: '<?php echo site_url('mst02/home/autocomplete_employee'); ?>',
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
			$('#f01').val(s[1]); /* for add item */
                        
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
        
        

        $("#f03").datepicker({dateFormat: "dd-mm-yy"});
        $("#f07").datepicker({dateFormat: "dd-mm-yy"});

        var picker = '<?php echo $datepicker ?>';

        if (picker == 'OFF') {
            $('#f07').attr('readonly', true).datepicker("destroy");
        } else {
            $('#f07').attr('readonly', false).datepicker({dateFormat: "dd-mm-yy"});
        }


       
        $("#f05").focusout(function() {
            var amount = parseInt($('#f04').val());
            var angkabunga  = parseInt($('#f09').val());
            var bunga = angkabunga/100;
            var interest = bunga/12;
            var interestamount =   Math.round(amount*interest);            
            $('#f10').val(interestamount);                        
            var term = parseInt($('#f05').val());
            
            var test = amount / term;            
            var nilai = test + interestamount;
                        
            //var jumlahbunga = interestamount*term;           
            //var total = amount+jumlahbunga;            
            //var installment = total / term;
            $('#f06').val(Math.round(nilai));
        });
        
       
       $("#f05").keyup(function() {
            var amount = parseInt($('#f04').val());
            var term = parseInt($('#f05').val());
            var installment = amount / term;

            $('#f06').val(installment);
        });

        save.click(function() {
            var f01 = $("#f01").val();
            var f02 = $("#f02").val();
            var f03 = $("#f03").val();
            var f04 = $("#f04").val();
            var f05 = $("#f05").val();
            var f06 = $("#f06").val();
            var f07 = $("#f07").val();
            var f08 = $("#f08").val();
            var f09 = $("#f09").val();
            var f10= $("#f10").val();
            var postdata = "f01=" + f01 + "&f02=" + f02 + "&f03=" + f03
                    + "&f04=" + f04 + "&f05=" + f05 + "&f06=" + f06
                    + "&f07=" + f07 + "&f08=" + f08+ "&f09=" + f09+ "&f10=" + f10;

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
                                        var content = $("#content");
                                        var site = 'mod_fieldpayroll/index.php/mst02/home';
                                        var url = ROOT.base_url + site;
                                        $('#loan-modal').modal('hide');
                                        content.load(url);
                                    } else {
                                        $.gritter.add({
                                            title: 'WARNING',
                                            text: data.mesg,
                                            image: '<?php echo $base_url . 'public/theme/images/warni.jpeg' ?>',
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
                    var content = $("#content");
                    var site = 'mod_fieldpayroll/index.php/mst02/home';
                    var url = ROOT.base_url + site;
                    $('#loan-modal').modal('hide');
                    content.load(url);

                });



    });


</script> 




