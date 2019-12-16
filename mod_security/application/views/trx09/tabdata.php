<?php $base_url = $this->session->userdata('sess_base_url') ?> 
<!-- JQueryUI -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-latest"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<!--
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/development-bundle/demos/demos.css" rel="stylesheet" />
-->
<script src="<?php echo $base_url ?>public/bootstrap/js/bootstrap.min.js"></script>
<!-- Time -->
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/timepicker/timepicker.css" rel="stylesheet" />
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/timepicker/timepicker.js"></script>
<!-- Gritter Notifications Plugin -->
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>  
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />
<!-- Content -->
<div id="content-wrap">
    <h3></h3>
    <div class="innerLR">

        <!-- Form Wizard / Widget Tabs / Double Style -->
        <div class="wizard">
            <div class="widget widget-tabs widget-tabs-double">
                <!-- Widget heading -->
                <div class="widget-head">
                    <ul>
                        <li class="active primary"><a href="#tab1" onclick="changetab('1')"  class="glyphicons user" data-toggle="tab"><i></i><span class="strong">User</span></a></li>
                        <li><a href="#tab2" onclick="changetab('2')"  class="glyphicons user_add" data-toggle="tab"><i></i><span class="strong">Create User</span></a></li>
                        <li><a href="#tab3" onclick="changetab('3')"  class="glyphicons cogwheel" data-toggle="tab"><i></i><span class="strong">Install Software</span></a></li>
                        <li><a href="#tab4" onclick="changetab('4')" class="glyphicons folder_plus" data-toggle="tab"><i></i><span class="strong">Create / Delete Folder</span></a></li>
                        <li><a href="#tab5" onclick="changetab('5')" class="glyphicons folder_new" data-toggle="tab"><i></i><span class="strong">Access Folder on Server</span></a></li>
                        <li><a href="#tab6" onclick="changetab('6')" class="glyphicons ok" data-toggle="tab"><i></i><span class="strong">User Agreement</span></a></li>
                    </ul>
                </div>
                <!-- // Widget heading END -->

                <div class="widget-body">
                    <div class="tab-content">

                        <!-- Tab User Start  -->
                        <div class="tab-pane active" id="tab1">
                        </div>
                        <!-- // Tab User END -->

                        <!-- Tab Create User  -->
                        <div class="tab-pane" id="tab2">
                        </div>
                        <!-- // Tab Create User  END -->

                        <!-- Install Software -->
                        <div class="tab-pane" id="tab3">
                        </div>
                        <!-- // End Install Software -->

                        <!-- Create Folder -->
                        <div class="tab-pane" id="tab4">
                        </div>
                        <!-- // Create Folder END -->

                        <!-- Access Folder-->
                        <div class="tab-pane" id="tab5">
                        </div>
                        <!-- // Access Folder END -->

                        <!-- User Agreement-->
                        <div class="tab-pane" id="tab6">
                        </div>
                        <!-- // User Agreement END -->

                    </div>

                </div>
            </div>
        </div>
        <!-- // Form Wizard / Widget Tabs / Double Style END -->


    </div>
</div>

<!-- // Content END -->

<script type="text/javascript">
    function loading() {
        bootbox.alertloading("<center>Processing. Please wait !!!<br><img src='<?php echo $base_url; ?>public/avatar/76.GIF'></center>");
    }

    var idh = '<?php echo $idh; ?>';
    var condition = '<?php echo $condition; ?>';
    
    var user= $("#tab1");
    var createuser = $("#tab2");
    var installsoftware = $("#tab3");
    var createfolder   = $("#tab4");
    var accessfolder = $("#tab5");
    var agreement = $("#tab6");
                            
                            
    $(document).ready(function()
    {


    });
    
    
    if(condition=='add'){
       load_user(); 
    }
    
    
    function changetab(tab) {
        if (tab == '1') {
            load_user();
        } else if (tab == '2') {
            load_createuser();
        } else if (tab == '3') {
            load_software();
        } else if (tab == '4') {
            load_cratefolder();
        } else if (tab == '5') {
            load_accessfolder();
        } else if (tab == '6') {
            load_agreement();
        }
    }



    function load_user() {
         user.load('<?php echo site_url('trx09/home/adduser/'.$idh)?>');

    }
    function load_createuser() {
         createuser.load('<?php echo site_url('trx09/home/addcrateuser/'.$idh)?>');

    }


    function load_software() {
         installsoftware.load('<?php echo site_url('trx09/home/addinstallsoftware/'.$idh)?>');
    }

    function load_cratefolder() {
        createfolder.load('<?php echo site_url('trx09/home/addcreatefolder/'.$idh)?>');


    }

    function load_accessfolder() {
        accessfolder.load('<?php echo site_url('trx09/home/addaccessfolder/'.$idh)?>');


    }
    
    function load_agreement() {
        agreement.load('<?php echo site_url('trx09/home/addagreement/'.$idh)?>');


    }


</script> 


