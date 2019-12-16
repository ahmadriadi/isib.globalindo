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
        <h3>Permission to Leave Work</h3>
    <div class="innerLR">
        <!-- Form -->
        <form class="form-horizontal" style="margin-bottom: 0;" id="validateSubmitForm" method="get" autocomplete="off">
            <!-- Widget -->
            <div class="widget"
                 <!-- Widget heading -->
                 <div class="widget-head">
                    <h4 class="heading">Permission to Leave Work</h4>
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
                                    <span id="amountleave"></span>
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
                                <label class="control-label" for="f03">From Date</label>
                                <div class="controls">
                                    <input class="span5" id="f03" name="f03" type="text"  value="<?php echo set_value('f03', isset($default['f03']) ? $default['f03'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f03'])) ? $default['readonly_f03'] : ''; ?>
                                           />
                                    <span id="err_f03"></span>
                                </div>                                
                            </div> 
                            <!-- Group end -->                            
                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f04">Until Date</label>
                                <div class="controls">
                                    <input class="span5" id="f04" name="f04" type="text"  value="<?php echo set_value('f04', isset($default['f04']) ? $default['f04'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f04'])) ? $default['readonly_f04'] : ''; ?>
                                           />
                                    <span id="err_f04"></span>
                                </div>                                
                            </div> 
                            <!-- Group end -->

                        </div>
                        <!-- // Column END -->

                        <!-- // Column 2 -->
                        <div class="span6">                           

                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f05">Days of leave</label>
                                <div class="controls">                                
                                    <input id="f05a" name="f05a" type="checkbox" 
                                           value="<?php echo (isset($default['f05a']['value'])) ? $default['f05a']['value'] : ''; ?>"
                                           <?php echo (isset($default['f05a']['checked'])) ? $default['f05a']['checked'] : ''; ?> 
                                           />
                                           <?php echo (isset($default['f05a']['display'])) ? $default['f05a']['display'] : ''; ?>   
                                    <br/>
                                    <input id="f05b" name="f05b" type="checkbox" 
                                           value="<?php echo (isset($default['f05b']['value'])) ? $default['f05b']['value'] : ''; ?>"
                                           <?php echo (isset($default['f05b']['checked'])) ? $default['f05b']['checked'] : ''; ?> 
                                           />
                                           <?php echo (isset($default['f05b']['display'])) ? $default['f05b']['display'] : ''; ?>   
                                    <br/>
                                    <input id="f05c" name="f05c" type="checkbox" 
                                           value="<?php echo (isset($default['f05c']['value'])) ? $default['f05c']['value'] : ''; ?>"
                                           <?php echo (isset($default['f05c']['checked'])) ? $default['f05c']['checked'] : ''; ?> 
                                           />
                                           <?php echo (isset($default['f05c']['display'])) ? $default['f05c']['display'] : ''; ?>   
                                    <br/>
                                    <input id="f05d" name="f05d" type="checkbox" 
                                           value="<?php echo (isset($default['f05d']['value'])) ? $default['f05d']['value'] : ''; ?>"
                                           <?php echo (isset($default['f05d']['checked'])) ? $default['f05d']['checked'] : ''; ?> 
                                           />
                                           <?php echo (isset($default['f05d']['display'])) ? $default['f05d']['display'] : ''; ?>   
                                    <br/>
                                    <input id="f05e" name="f05e" type="checkbox" 
                                           value="<?php echo (isset($default['f05e']['value'])) ? $default['f05e']['value'] : ''; ?>"
                                           <?php echo (isset($default['f05e']['checked'])) ? $default['f05e']['checked'] : ''; ?> 
                                           />
                                           <?php echo (isset($default['f05e']['display'])) ? $default['f05e']['display'] : ''; ?>   
                                    <br/>
                                    <input id="f05f" name="f05f" type="checkbox" 
                                           value="<?php echo (isset($default['f05f']['value'])) ? $default['f05f']['value'] : ''; ?>"
                                           <?php echo (isset($default['f05f']['checked'])) ? $default['f05f']['checked'] : ''; ?> 
                                           />
                                           <?php echo (isset($default['f05f']['display'])) ? $default['f05f']['display'] : ''; ?>   
                                    <br/>
                                    <input id="f05g" name="f05g" type="checkbox" 
                                           value="<?php echo (isset($default['f05g']['value'])) ? $default['f05g']['value'] : ''; ?>"
                                           <?php echo (isset($default['f05g']['checked'])) ? $default['f05g']['checked'] : ''; ?> 
                                           />
                                           <?php echo (isset($default['f05g']['display'])) ? $default['f05g']['display'] : ''; ?>   
                                    <br/>
                                </div>                                
                            </div> 
                            <!-- Group end -->
                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f06">Work Hour of Days</label>
                                <div class="controls">
                                    <input class="span5" id="f06" name="f06" type="text"  value="<?php echo set_value('f06', isset($default['f06']) ? $default['f06'] : ''); ?>" 
                                    <?php echo (isset($default['readonly_f06'])) ? $default['readonly_f06'] : ''; ?>
                                           />
                                    <span id="err_f06"></span>
                                </div>                                
                            </div> 
                            <!-- Group end -->
                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="f07">Note</label>
                                <div class="controls">
                                    <textarea id="f07" name="f07" cols="18" rows="3" <?php echo (isset($default['readonly_f07'])) ? $default['readonly_f07'] : ''; ?> ><?php echo (isset($default['f07'])) ? $default['f07'] : ''; ?></textarea><span style="color:white;" id="err_f07"></span>                                   
                                    <span id="err_f07"></span>
                                </div>                                
                            </div> 
                            <!-- Group end -->
                        </div>
                        <!-- // Column 2  END -->
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
	

	$.ajax({
	    url: '<?php echo site_url('trx10/home/autocomplete_employee'); ?>',
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

	/*
        var url_suggest = '<?php echo site_url('trx10/home/suggest_employee'); ?>';
        $("#f02").autocomplete({
            minLength: 2,
            select: function(event, ui) {
                $('#f01').val(ui.item.idemployee);

            },
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

        $("#f03").datepicker({dateFormat: "dd-mm-yy"});
        $("#f04").datepicker({dateFormat: "dd-mm-yy"});

        save.click(
                function()
                {

                    var f05a = '';
                    var f05b = '';
                    var f05c = '';
                    var f05d = '';
                    var f05e = '';
                    var f05f = '';
                    var f05g = '';
                    $('#f05a:checked').each(function() {
                        f05a += $(this).val();
                    });
                    $('#f05b:checked').each(function() {
                        f05b += $(this).val();
                    });
                    $('#f05c:checked').each(function() {
                        f05c += $(this).val();
                    });
                    $('#f05d:checked').each(function() {
                        f05d += $(this).val();
                    });
                    $('#f05e:checked').each(function() {
                        f05e += $(this).val();
                    });
                    $('#f05f:checked').each(function() {
                        f05f += $(this).val();
                    });
                    $('#f05g:checked').each(function() {
                        f05g += $(this).val();
                    });



                    var f01 = $("#f01").val();
                    var f02 = $("#f02").val();
                    var f03 = $("#f03").val();
                    var f04 = $("#f04").val();
                    var f06 = $("#f06").val();
                    var f07 = $("#f07").val();

                    var content = $("#content .innerLR");
                    var site = 'mod_attendance/index.php/trx10/home';
                    var url = ROOT.base_url + site;
                    var postdata = "f01=" + f01 + "&f02=" + f02 + "&f03=" + f03 + "&f04=" + f04
                            + "&f05a=" + f05a + "&f05b=" + f05b + "&f05c=" + f05c + "&f05d=" + f05d + "&f05e=" + f05e + "&f05f=" + f05f
                            + "&f05g=" + f05g + "&f06=" + f06 + "&f07=" + f07.toUpperCase();
                    var url_post = '<?php echo $url_post; ?>';

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

                                                $.gritter.add({
                                                    title: 'INFORMATION',
                                                    text: data.mesg,
                                                    image: '<?php echo $base_url . 'public/theme/images/informa.png' ?>',
                                                    class_name: 'gritter-light',
                                                    fade_in_speed: 100,
                                                    fade_out_speed: 100,
                                                    time: 3500
                                                });

                                                content.fadeOut("slow", "linear");
                                                content.load(url);
                                                content.fadeIn("slow");

                                            } else {

                                                $.gritter.add({
                                                    title: 'WARNING',
                                                    text: data.mesg,
                                                    image: '<?php echo $base_url . 'public/theme/images/warni.jpeg' ?>',
                                                    class_name: 'gritter-light',
                                                    fade_in_speed: 100,
                                                    fade_out_speed: 100,
                                                    time: 3500
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
                    var content = $("#content .innerLR");
                    var site = 'mod_attendance/index.php/trx10/home';
                    var url = ROOT.base_url + site;
                    //alert(url);
                    content.fadeOut("slow", "linear");
                    content.load(url);
                    content.fadeIn("slow");

                });
    });



</script> 
