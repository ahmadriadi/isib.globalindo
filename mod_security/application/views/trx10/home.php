<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta content="utf-8" http-equiv="encoding" />

        <?php $base_url = $this->session->userdata('sess_base_url'); ?>
         <!--JQueryUI--> 
       
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" />
<!--         hide the close link in the toolbar 
         Gritter Notifications Plugin -->
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />
         <!--DataTables Plugin--> 
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css" rel="stylesheet" />
        
         <!--JQuery--> 
        <!--<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-latest.js"></script>-->

         <!--JQueryUI--> 
        <!--<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>-->
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
        <!--timepicker-->
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/timepicker/timepicker.css" rel="stylesheet" />
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/timepicker/timepicker.js"></script>
        <!--wysih-->
	<link href="<?php echo $base_url; ?>public/bootstrap/extend/bootstrap-wysihtml5/css/bootstrap-wysihtml5-0.0.2.css" rel="stylesheet">
	 <!--Bootstrap--> 
	<script src="<?php echo $base_url; ?>public/bootstrap/js/bootstrap.js"></script> 
	<!--<script src="<?php echo $base_url; ?>public/bootstrap/js/popup.js"></script>-->
         <!--Gritter Notifications Plugin--> 
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>
	 <!--DataTables Tables Plugin--> 
	<script src="<?php echo $base_url; ?>/public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
	 <!--Tables Demo Script--> 
	<script src="<?php echo $base_url; ?>public/theme/scripts/demo/tables.js"></script>
        <!--wysih-->
	<script src="<?php  echo $base_url; ?>public/bootstrap/extend/bootstrap-wysihtml5/js/wysihtml5-0.3.0_rc2.min.js"></script>
	<script src="<?php echo $base_url; ?>public/bootstrap/extend/bootstrap-wysihtml5/js/bootstrap-wysihtml5-0.0.2.js"></script>
        <script>
        $(document).ready(function (){
            loadmain();
            $(document).click(function (e) {
//                var tes = $(this).attr("class");
//                alert(tes);
                $('[data-toggle="popover"]').each(function () {
                    //the 'is' for buttons that trigger popups
                    //the 'has' for icons within a button that triggers a popup
                    
                    if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0 ) {
                        
//                        setTimeout(function (ini){
                            if ($("[role='presentation']").length == 0 ){
                                $(this).popover('hide');
                            }
//                        },"500",$(this));

                    }
                });
            });            
        });
        function loadmain(){
            $.ajax({
                url     : "<?php echo site_url('trx10/home/main') ?>",
                data    : "",
                type    : "post",
                dataType: "html",
                cache   : false,
                success : function(data){
                    $("#wadah").empty();
                    $("#wadah").html(data);
//                    $("#mainstrk").show({
//                        effect : "clip",
//                        complete: function (){
//                            $("#coaform").remove();
//                        }
//                    });
                }
            });
        }
        function loading(){
            bootbox.alertloading("<center>Processing. Please wait !!!<br><img src='<?php echo $base_url; ?>public/avatar/76.GIF'></center>");
        }
        </script>
        <style>
            .selectable{
                background: #656565;
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <div id="wadah">

            <!--===========================================================================================-->
            
        </div>        
    </body>
</html>
