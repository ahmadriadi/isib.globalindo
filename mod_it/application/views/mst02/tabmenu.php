<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<?php $base_url = $this->session->userdata('sess_base_url'); ?>
<!-- DataTables Plugin --> 
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css" rel="stylesheet" />

<!-- JQueryUI -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-latest.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<!-- Bootstrap -->
<script src="<?php echo $base_url; ?>public/bootstrap/js/bootstrap.js"></script>
<!-- DataTables Tables Plugin -->
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/css/colvis.css" rel="stylesheet" />
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/colvis.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/api/fnStandingRedraw.js"></script>
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/js/listbox_paging.js"></script>

<!-- Gritter Notifications Plugin -->
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>  
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />
<style>
    .accept{color: #00CC00;font-weight: bold;}
    .waiting{color: #EC5800;font-weight: bold;}
    .reject{color: #ee1e2d;font-weight: bold;}
    a.ui-dialog-titlebar-close { display:block; }
    td.alert {
        color: #ee1e2d !important; 
        font-weight: bold;

    }
    div.dataTables_wrapper {
        width: 100%;
        margin: 0 auto;
    }
</style>


<div class="widget">

    <div class="widget-head">
        <div class="row-fluid">
            <div class="span6">
                <h4 class="heading">Data IP Address</h4>                
            </div>
            <div class="span6" style="text-align: right">
                <button onclick="reloadpage()" class="btn btn-small btn-default"><i class="icon-refresh"></i></button>
                <button onclick="backtohome()" class="btn btn-small btn-success btn-icon glyphicons home"><i></i>Back to Home</button>
            </div>
        </div>
    </div>
    <div class="widget-body">

        <div class="widget widget-tabs widget-tabs-double-2">
            <div class="widget-head">
                <ul>
                    <li id="ipactive" ><a  class="glyphicons ok_2" href="#tab1" onclick="changetab('1')" data-toggle="tab"><i></i><span>IP Address Used </span></a></li>
                    <li id="ippassive" ><a  class="glyphicons clock" href="#tab2" onclick="changetab('2')" data-toggle="tab"><i></i><span>IP Address Not Used </span></a></li>
                </ul>
            </div>
            <div class="widget-body">
                <div class="tab-content">
                    <!-------- ----------------START IP USED ----------------------------------------------------------->
                    <div id="tab1">                       
                    </div>
                    <!-------- ----------------END IP USED----------------------------------------------------------->

                    <!-------- ----------------START IP NOT USED ----------------------------------------------------------->
                    <div id="tab2">                       
                    </div>
                    <!-------- ----------------END IP NOT USED  ----------------------------------------------------------->

                 
                </div>        
            </div>

            <!-- ===================================================================================================== ----->        


        </div>
    </div>
    </div>

    <div align="center" id="loan-modal" class="modal container  hide fade long" tabindex="-1"></div>
    <div id="dialog-confirm" title="DELETE REQUEST">
        <p id="textconfirm" style="display:none;">
            Are you sure for delete this data?
        </p>   
    </div>
    

   <input type='hidden' id='flagdata' name='flagdata' />

    <script type="text/javascript">
        function loading() {
            bootbox.alertloading("<center>Processing. Please wait !!!<br><img src='<?php echo $base_url; ?>public/avatar/76.GIF'></center>");
        }

        var flag = '<?php echo $flag; ?>';
        var flagdata = $("#flagdata");
        var ipactive = $("#tab1");
        var ippassive = $("#tab2");



        if (flag == 'ipactive') {
            $("#ippassive").removeClass("active");
            $("#tab2").removeClass("tab-pane active widget-body-regular");


            $("#ipactive").addClass("active");
            $("#tab1").addClass("tab-pane active widget-body-regular");
            $("#tab2").addClass("tab-pane widget-body-regular");           

            load_ipactive();

        } else if (flag == 'ippassive') {
            $("#ipactive").removeClass("active");
            $("#tab1").removeClass("tab-pane active widget-body-regular");   
            
            $("#ippassive").addClass("active");
            $("#tab1").addClass("tab-pane widget-body-regular");
            $("#tab2").addClass("tab-pane active widget-body-regular");            

            load_ippassive();

        }



        function changetab(tab) {
            if (tab == '1') {
                load_ipactive();
            } else if (tab == '2') {
                load_ippassive();
            } 

        }


        function load_ipactive() {
            ipactive.load('<?php echo site_url('mst02/home/index_ipactive') ?>');
            flagdata.val('ipactive');

        }

        function load_ippassive() {
            ippassive.load('<?php echo site_url('mst02/home/index_ippassive') ?>');
			flagdata.val('ippassive');
        }
       


        function backtohome() {
            window.location.href = "<?php echo $base_url; ?>";
        }


        function reply_click(clicked_id)
        {

            var str = clicked_id;
            var explode = str.split('-');
            var button = explode[0];
            var id = explode[1];

            var url_add = "<?php echo site_url('mst02/home/addnew') ?>";
            var url_edit = "<?php echo site_url('mst02/home/edit') ?>";
            var content = $("#content");



         
            if (button == 'btn_add') {
                var site = "mod_it/index.php/mst02/home/addnew";
                var url = ROOT.base_url + site;
                
                content.fadeOut("slow", "linear");
                content.load(url);
                content.fadeIn("slow");


            }  else if (button == 'btn_edit') {
				var site = "mod_it/index.php/mst02/home/edit"+ '/' + id+'/'+flagdata.val();
                var url = ROOT.base_url + site;
                
                content.fadeOut("slow", "linear");
                content.load(url);
                content.fadeIn("slow");

            }  else if (button == 'btn_delete') {
                $(document).ready(function ()
                {

                    var contentdel = $("#content");
                    var site = 'mod_it/index.php/mst02/home';
                    var urldel = ROOT.base_url + site;

                    $("#textconfirm").show();
                    $(function () {
                        $("#dialog-confirm").dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                "Delete ": function () {
                                    $.ajax({
                                        type: "POST",
                                        url: '<?php echo site_url('mst02/home/delete') ?>' + '/' + id,
                                        dataType: "json",
                                        cache: false,
                                        success:
                                                function (data) {
                                                    if (data.valid == 'true') {
                                                        $("#tableajaxipactive").dataTable().fnStandingRedraw();
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
                                                    }


                                                },
                                        error:
                                                function (xhr, ajaxOptions, thrownError) {
                                                    alert(xhr.status);
                                                    alert(thrownError);
                                                }
                                    });

                                    $(this).dialog("close");
                                },
                                Cancel: function () {
                                    $(this).dialog("close");

                                }
                            }
                        });
                    });


                });
            } else if (button == 'btn_excel') {
                window.location.href = '<?php echo site_url('mst02/home/excel') ?>'+'/'+flagdata.val();

            }


        }


    </script>




