<?php $base_url = $this->session->userdata('sess_base_url') ?> 
<!-- JQueryUI -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-latest.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/ajaxfileupload.js"></script>
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/development-bundle/demos/demos.css" rel="stylesheet" />
<!-- Gritter Notifications Plugin -->
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>  
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />
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
        <!-- Widget -->
        <div class="widget"
             <!-- Widget heading -->
             <div class="widget-head">
                <h4 class="heading">Upload Excel</h4>

            </div>
            <!-- // Widget heading END -->
            <form name="form" action="" method="POST" enctype="multipart/form-data">
                <div class="widget-body">
                     <label class="control-label" for="file">Perhatian <br/>Pastikan Format File Excel berisikan  data seperti contoh dibawah ini</label> 
                    <table border="1">
                        <tr>
                            <td width="10%" align="center">No</td>
                            <td width="10%" align="center">Payment Term</td>
                        </tr>
                        <tr>
                            <td align="center">1</td>
                            <td align="left" >1 Week after delivery and invoice completed</td>
                        </tr>
                    </table>
                    <br/><br/><br/>
                    <!-- Row -->
                    <div class="row-fluid">
                        <!-- Column ke 1 -->
                        <div class="span6"> 
                            <div class="control-group">
                                <label class="control-label" for="file">Select File</label>                               
                                <div class="controls">
                                    <input id="fileToUpload" type="file" size="45" name="fileToUpload" class="input">
                                </div>
                            </div>
                        </div>                   
                        <!-- // Row END -->
                    </div>

                    <hr class="separator" />
                    <div class="form-actions" align="center">
                        <button type="button" id="btn_upload"  class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Upload</button>
                        <button type="button" id="btn_cancel" class="btn btn-icon btn-default glyphicons circle_remove"><i></i>Cancel</button>
                    </div>
                </div>
                <!-- // Widget END -->

                <!-- // Row END -->

        </div>
        </form>
        <!-- // Form END -->
    </div>
    <!-- // Content END -->

    <script type="text/javascript">

        $(document).ready(function()
        {
            var upload = $('#btn_upload');
            var cancel = $('#btn_cancel');
            var content = $("#content .innerLR");
            var site = 'mod_security/index.php/imp02/home';
            var url = ROOT.base_url + site;
            var url_process = '<?php echo $url_post; ?>';


            upload.click(
                    
                    function()
                    {
                        
                        
                        alert(url_process);
                    
                        $.ajaxFileUpload
                                (
                                        {
                                            url: url_process,
                                            secureuri: false,
                                            fileElementId: 'fileToUpload',
                                            dataType: 'json',
                                            success: function(data, status)
                                            {
                                                if (typeof(data.error) != 'undefined')
                                                {
                                                    if (data.error != '')
                                                    {
                                                        $.gritter.add({
                                                            title: 'WARNING',
                                                            text: data.error,
                                                            image: '<?php echo $base_url . 'public/theme/images/warni.jpeg' ?>',
                                                            class_name: 'gritter-light',
                                                            fade_in_speed: 100,
                                                            fade_out_speed: 100,
                                                            time: 2500
                                                        });
                                                    } else
                                                    {
                                                        bootbox.alert('Upload Success', function(result)
                                                        {

                                                        });
                                                       // content.load(url);
                                                    }
                                                }
                                            },
                                            error: function(data, status, e)
                                            {
                                                $("#info").html(e);
                                            }
                                        }
                                );
                    });


            cancel.click(
                    function()
                    {
                        content.load(url);
                    });


        });

    </script>
