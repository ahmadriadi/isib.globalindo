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
                <h4 class="heading">Data Personal Loan</h4>                
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
                    <li id="paidon" ><a  class="glyphicons clock" href="#tab1" onclick="changetab('1')" data-toggle="tab"><i></i><span>Not Yet Paid Off  </span></a></li>
                    <li id="paidoff" ><a  class="glyphicons ok_2" href="#tab2" onclick="changetab('2')" data-toggle="tab"><i></i><span>Already Paid Off</span></a></li>
                    <li id="loaninterest" ><a  class="glyphicons notes_2" href="#tab3" onclick="changetab('3')"  data-toggle="tab"><i></i><span>Loan Interest</span></a></li>
                </ul>
            </div>
            <div class="widget-body">
                <div class="tab-content">
                    <!-------- ----------------START LOAN NOT YET PAID ON ----------------------------------------------------------->
                    <div id="tab1">                       
                    </div>
                    <!-------- ----------------END LOAN NOT YET PAID ON ----------------------------------------------------------->

                    <!-------- ----------------START ALREADY PAID OFF ----------------------------------------------------------->
                    <div id="tab2">                       
                    </div>
                    <!-------- ----------------END ALREADY PAID OFF  ----------------------------------------------------------->

                    <!-------- ---------------- START LOAN INTEREST ----------------------------------------------------------->
                    <div id="tab3">                                            
                    </div>
                    <!-------- ----------------END LOAN INTEREST  ----------------------------------------------------------->

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

    <div id="processdata-modal" class="modal hide fade" tabindex="-1"> 
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Schedule loan process</h3>
        </div>
        <div class="modal-body">   
            <p id="textloading" style="display:none;">
                <img id="loading" src="<?php echo $base_url; ?>public/avatar/76.GIF" style="/*display:none;*/">
                Processing . . .
            </p>
            <p id="textfinished" style="display:none;">
                Proceed Successed
            </p>
        </div>

    </div> 

   <input type='hidden' id='flagdata' name='flagdata' />

    <script type="text/javascript">
        function loading() {
            bootbox.alertloading("<center>Processing. Please wait !!!<br><img src='<?php echo $base_url; ?>public/avatar/76.GIF'></center>");
        }

        var flag = '<?php echo $flag; ?>';
        var flagdata = $("#flagdata");
        var paidon = $("#tab1");
        var paidoff = $("#tab2");
        var interest = $("#tab3");



        if (flag == 'paidon') {
            $("#paidoff").removeClass("active");
            $("#tab2").removeClass("tab-pane active widget-body-regular");

            $("#loaninterest").removeClass("active");
            $("#tab3").removeClass("tab-pane active widget-body-regular");

            $("#paidon").addClass("active");
            $("#tab1").addClass("tab-pane active widget-body-regular");
            $("#tab2").addClass("tab-pane widget-body-regular");
            $("#tab3").addClass("tab-pane widget-body-regular");



            load_paidon();

        } else if (flag == 'paidoff') {
            $("#paidon").removeClass("active");
            $("#tab1").removeClass("tab-pane active widget-body-regular");

            $("#loaninterest").removeClass("active");
            $("#tab3").removeClass("tab-pane active widget-body-regular");

            $("#paidoff").addClass("active");
            $("#tab1").addClass("tab-pane widget-body-regular");
            $("#tab2").addClass("tab-pane active widget-body-regular");
            $("#tab3").addClass("tab-pane widget-body-regular");

            load_paidoff();

        } else if (flag == 'loaninterest') {

            $("#paidon").removeClass("active");
            $("#tab1").removeClass("tab-pane active widget-body-regular");

            $("#paidoff").removeClass("active");
            $("#tab2").removeClass("tab-pane active widget-body-regular");

            $("#loaninterest").addClass("active");
            $("#tab1").addClass("tab-pane widget-body-regular");
            $("#tab2").addClass("tab-pane widget-body-regular");
            $("#tab3").addClass("tab-pane active widget-body-regular");

            load_loaninterest();
        }



        function changetab(tab) {
            if (tab == '1') {
                load_paidon();
            } else if (tab == '2') {
                load_paidoff();
            } else if (tab == '3') {
                load_loaninterest();
            }

        }


        function load_paidon() {
            paidon.load('<?php echo site_url('mst02/home/index_paidon') ?>');

            flagdata.val('paidon');

        }

        function load_paidoff() {
            paidoff.load('<?php echo site_url('mst02/home/index_paidoff') ?>');

	 flagdata.val('paidoff');
        }
        function load_loaninterest() {
            interest.load('<?php echo site_url('mst02/home/index_interest') ?>');
		
 		flagdata.val('loaninterest');
        }



        function backtohome() {
            window.location.href = "<?php echo $base_url; ?>";
        }


 		function dialoghtml(url) {
        $("head").append("<style id='styletbh1'>.modal{width: 60%;height: 80%;margin-left: -30%;}</style>");
        $("head").append("<style id='styletbh2'>.modal-body{position: relative;overflow-y: auto;height: 100%;max-height: 100%;padding: 0px;}</style>");

        $.ajax(
                {
                    type: "post",
                    url: url,
                    dataType: "html",
                    //   data: 'page=' + page,
                    cache: false,
                    success:
                            function (data, text)
                            {
                                bootbox.dialog(data, {
                                    "callback": function () {
                                        $("#styletbh1").remove();
                                        $("#styletbh2").remove();
                                        bootbox.hideAll();

                                    }
                                });
                            },
                    error: function (request, status, error) {
                        alert(request.responseText + " " + status + " " + error);
                    }
                });

    }



        function reply_click(clicked_id)
        {

            var str = clicked_id;
            var explode = str.split('-');
            var button = explode[0];
            var id = explode[1];

            var url_add = "<?php echo site_url('mst02/home/addnew') ?>";
            var url_edit = "<?php echo site_url('mst02/home/edit') ?>";
            var url_schedule = '<?php echo site_url('mst02/home/schedule') ?>' + "/" + id;
            var content = $("#content");



            //                        alert(keterangan);
            if (button == 'btn_add') {
                $('#loan-modal').load(url_add, '', function () {
                    $('#loan-modal').modal().draggable({
                        handle: ".modal-body"
                    });
                });


            } else if (button == 'btn_generate') {
                var site = "mod_fieldpayroll/index.php/mst02/home/gereateform";
                var url = ROOT.base_url + site;
                dialoghtml(url);


            } else if (button == 'btn_add_interest') {
                var site = "mod_fieldpayroll/index.php/mst02/home/add_interest";
                var url = ROOT.base_url + site;

                content.fadeOut("slow", "linear");
                content.load(url);
                content.fadeIn("slow");


            } else if (button == 'btn_edit') {

                $('#loan-modal').load(url_edit + '/' + id, '', function () {
                    $('#loan-modal').modal().draggable({
                        handle: ".modal-body"
                    });
                });

            } else if (button == 'btn_edit_interest') {
                var site = "mod_fieldpayroll/index.php/mst02/home/edit_interest" + '/' + id;
                var url = ROOT.base_url + site;

                content.fadeOut("slow", "linear");
                content.load(url);
                content.fadeIn("slow");

            } else if (button == 'btn_delete_interest') {

                $(document).ready(function ()
                {

                    var contentdel = $("#content");
                    var site = 'mod_fieldpayroll/index.php/mst02/home';
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
                                        url: '<?php echo site_url('mst02/home/delete_interest') ?>' + '/' + id,
                                        dataType: "json",
                                        cache: false,
                                        success:
                                                function (data) {
                                                    if (data.valid == 'true') {
                                                        $("#tableajaxinterest").dataTable().fnStandingRedraw();
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
            } else if (button == 'btn_delete') {
                $(document).ready(function ()
                {

                    var contentdel = $("#content");
                    var site = 'mod_fieldpayroll/index.php/mst02/home';
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
                                        url: '<?php echo site_url('mst02/home/removedata') ?>' + '/' + id,
                                        dataType: "json",
                                        cache: false,
                                        success:
                                                function (data) {
                                                    if (data.valid == 'true') {
                                                        $("#tableajaxpaidon").dataTable().fnStandingRedraw();
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
		//alert(flagdata.val());
                window.location.href = '<?php echo site_url('mst02/home/exportdata') ?>'+'/'+flagdata.val();

            } else if (button == 'btn_excel_interest') {
                window.location.href = '<?php echo site_url('mst02/home/excel_interest') ?>';

            } else if (button == 'btn_schedule') {

                $("#textfinished").hide();
                $('#processdata-modal').modal();
                $("#textloading").show();
                $(":button:contains('Finish')").attr("disabled", true).addClass("ui-state-disabled");

                $.ajax({
                    type: "POST",
                    url: url_schedule,
                    dataType: "json",
                    data: '',
                    cache: false,
                    success:
                            function (data) {
                                if (data.valid == 'true') {
                                    $("#textloading").hide();
                                    $("#textfinished").show();
                                    $(":button:contains('Finish')").attr("disabled", false).removeClass("ui-state-disabled");

                                    var content = $("#tableajax2");
                                    var url = ROOT.base_url + 'mod_fieldpayroll/index.php/mst02/home/index_detail' + "/" + id;
                                    content.load(url);
                                    $('#processdata-modal').modal('hide');
                                } else {
                                    $("#textloading").hide();
                                    $("#textfinished").show();
                                    $(":button:contains('Finish')").attr("disabled", false).removeClass("ui-state-disabled");
                                    $('#processdata-modal').modal('hide');
                                    $.gritter.add({
                                        title: 'WARNING',
                                        text: data.mesg,
                                        image: '<?php echo $base_url . 'public/theme/images/warni.jpeg' ?>',
                                        class_name: 'gritter-light',
                                        fade_in_speed: 100,
                                        fade_out_speed: 100,
                                        time: 10000
                                    });
                                }

                            },
                    error:
                            function (xhr, ajaxOptions, thrownError) {
                                alert(xhr.status);
                                alert(thrownError);
                            }
                });

            }


        }


    </script>




