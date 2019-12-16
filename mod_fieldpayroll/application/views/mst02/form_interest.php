

<form class="form-horizontal" style="margin-bottom: 0;" id="validateSubmitForm" method="get" autocomplete="off">
    <!-- Widget -->
    <div class="widget"
         <!-- Widget heading -->
         <div class="widget-head">
            <h4 class="heading">Form Loan Interest</h4>
        </div>
        <!-- // Widget heading END -->
        <div class="widget-body">
            <!-- Row -->
            <div class="row-fluid">
                <!-- Column ke 1 -->
                <div class="span6">                        
                    <input class="span10" id="idrecord" name="idrecord" type="hidden"  value="<?php echo set_value('idrecord', isset($default['idrecord']) ? $default['idrecord'] : ''); ?>" 
                    <?php echo (isset($default['readonly_idrecord'])) ? $default['readonly_idrecord'] : ''; ?>
                           />

                    <!-- Group -->
                    <div class="control-group">
                        <label class="control-label" for="f01">Posting Date</label>
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
                        <label class="control-label" for="f03">IDEmployee</label>
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
                        <label class="control-label" for="f04">Interest</label>
                        <div class="controls">
                            <input class="span10" id="f04" name="f04" type="text"  value="<?php echo set_value('f04', isset($default['f04']) ? $default['f04'] : ''); ?>" 
                            <?php echo (isset($default['readonly_f04'])) ? $default['readonly_f04'] : ''; ?>
                                   />
                            <span id="err_f04"></span>
                        </div>                        
                    </div> 
                    <!-- Group end --> 


                    <!-- // Column 1 END -->
                </div>
                <!-- Column ke 2 -->
                <div class="span6"> 

                    <!-- Group -->
                    <div class="control-group">
                        <label class="control-label" for="f05">Installment</label>
                        <div class="controls">
                            <input class="span10" id="f05" name="f05" type="text"  value="<?php echo set_value('f05', isset($default['f05']) ? $default['f05'] : ''); ?>" 
                            <?php echo (isset($default['readonly_f05'])) ? $default['readonly_f05'] : ''; ?>
                                   />
                            <span id="err_f05"></span>
                        </div>                        
                    </div> 
                    <!-- Group end --> 

                    <!-- Group -->
                    <div class="control-group">
                        <label class="control-label" for="f06">Installment + Interest</label>
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
                        <label class="control-label" for="f07">Note</label>
                        <div class="controls">
                            <textarea id="f07" name="f07" cols="18" rows="3"<?php echo (isset($default['readonly_f07'])) ? $default['readonly_f07'] : ''; ?> ><?php echo (isset($default['f07'])) ? $default['f07'] : ''; ?></textarea>                                          
                            <span id="err_f07"></span>
                        </div>                        
                    </div> 
                    <!-- Group end -->  
                    <!-- // Column 2 END -->
                </div>
            </div>

            <hr class="separator" />
            <div class="form-actions" align="center">
                <button type="button" id="btn_save"   class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Save</button>
                <button type="button" id="btn_cancel" class="btn btn-icon btn-default glyphicons circle_remove"><i></i>Cancel</button>

            </div>


        </div>                   
        <!-- // Row END -->
        <hr class="separator" />
        <!-- Form actions -->
    </div>
</form>
<!-- // Form END -->



<script type="text/javascript">
    $(document).ready(function ()
    {


        var save = $("#btn_save");
        var cancel = $("#btn_cancel");
        var flag = '<?php echo $flag; ?>';
        var url_post = '<?php echo $url_post ?>';

        $("#f01").datepicker({dateFormat: "dd-mm-yy"});


        $("#f02").autocomplete({
            minLength: 2,
            select: function (event, ui) {
                $('#idrecord').val(ui.item.idrecord);
                $('#f03').val(ui.item.idemployee);
                $('#f04').val(ui.item.interest);
                $('#f05').val(ui.item.ortiinstallment);
                $('#f06').val(ui.item.installment);
            },
            source: function (req, add) {
                $.ajax({
                    url: '<?php echo site_url('mst02/home/suggest_loan'); ?>',
                    dataType: "json",
                    type: "POST",
                    data: "term=" + req.term + '&postingdate=' + $("#f01").val(),
                    success: function (data) {
                        if (data.response = "true") {

                            add(data.message);
                        }
                    },
                    error: function (XMLHttpRequest) {
                        alert(XMLHttpRequest.responseText);
                    }
                })
            }
        });


        save.click(function () {
            var idrecord = $("#idrecord").val();
            var f01 = $("#f01").val();
            var f02 = $("#f02").val();
            var f03 = $("#f03").val();
            var f04 = $("#f04").val();
            var f05 = $("#f05").val();
            var f06 = $("#f06").val();
            var f07 = $("#f07").val();

            var iddata = '<?php echo $iddata ?>';
            var url_post = '<?php echo $urlpost ?>';


            var postdata = "iddata=" + iddata + "&idrecord=" + idrecord + "&f01=" + f01 + "&f02=" + f02 + "&f03=" + f03
                    + "&f04=" + f04 + "&f05=" + f05 + "&f06=" + f06
                    + "&f07=" + f07;

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
                                        var content = $("#content");
                                        var site = 'mod_fieldpayroll/index.php/mst02/home/tabmenu/loaninterest';
                                        var url = ROOT.base_url + site;
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
                        error: function (request, status, error) {
                            alert(request.responseText + " " + status + " " + error);
                        }
                    });
            return false;

        });


        cancel.click(
                function ()
                {
                    var content = $("#content");
                    var site = 'mod_fieldpayroll/index.php/mst02/home/tabmenu/loaninterest';
                    var url = ROOT.base_url + site;
                    content.load(url);

                });



    });


</script> 




