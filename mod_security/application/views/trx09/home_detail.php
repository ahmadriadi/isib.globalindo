<hr>
<!--<div class="widget">
    <div class="widget-head">-->
        <div class="row-fluid">
            <div style="margin-left: 10px;" class="span10">
                <h4 class="heading">Details of Request <span>Ref #<?php echo $idh; ?></span></h4>
            </div>
        </div>        
<!--    </div>
    <div class="widget-body">-->

<?php $base_url = $this->session->userdata('sess_base_url') ?> 
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
                            <table width="100%" id="tableuser" cellspacing="1" cellpadding="1" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable dynamicTable" >
                            </table> 

                        </div>
                        <!-- // Tab User END -->

                        <!-- Tab Create User  -->
                        <div class="tab-pane" id="tab2">
                            <table width="100%" id="tablecreateuser" cellspacing="1" cellpadding="1" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable dynamicTable" >
                            </table> 

                        </div>
                        <!-- // Tab Create User  END -->

                        <!-- Install Software -->
                        <div class="tab-pane" id="tab3">
                            <table width="100%" id="tablesoftware" cellspacing="1" cellpadding="1" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable dynamicTable" >
                            </table> 
                        </div>
                        <!-- // End Install Software -->

                        <!-- Create Folder -->
                        <div class="tab-pane" id="tab4">
                            <table width="100%" id="tablecreatefolder" cellspacing="1" cellpadding="1" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable dynamicTable" >
                            </table>
                        </div>
                        <!-- // Create Folder END -->

                        <!-- Access Folder-->
                        <div class="tab-pane" id="tab5">
                            <table width="100%" id="tableaccessfolder" cellspacing="1" cellpadding="1" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable dynamicTable" >
                            </table>
                        </div>
                        <!-- // Access Folder END -->

                        <!-- User Agreement-->
                        <div class="tab-pane" id="tab6">
                            <table width="100%" id="tableagreement" cellspacing="1" cellpadding="1" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable dynamicTable" >
                            </table>
                        </div>
                        <!-- // User Agreement END -->

                    </div>

                </div>
            </div>
        </div>
        <!-- // Form Wizard / Widget Tabs / Double Style END -->
<script type="text/javascript">
                            var idh = '<?php echo $idh; ?>';
                            var condition = '<?php echo $condition; ?>';

                            if (condition == 'display') {
                                home_user();
                            }
                            
                            

                            function changetab(tab) {
                                if (tab == '1') {
                                    home_user();
                                } else if (tab == '2') {
                                    home_createuser();
                                } else if (tab == '3') {
                                    home_software();
                                } else if (tab == '4') {
                                    home_cratefolder();
                                } else if (tab == '5') {
                                    home_accessfolder();
                                } else if (tab == '6') {
                                    home_agreement();
                                }
                            }


                            function home_user() {                                                            
                                $(document).ready(function() {
                                    var OUser = $('#tableuser').dataTable({
                                        "bJQueryUI": false,
                                        "bSortClasses": false,
                                        "aaSorting": [[2, "desc"]],
                                        "bAutoWidth": true,
                                        "bInfo": true,
                                        "sScrollY": "100%",
                                        "sScrollX": "100%",
                                        "bScrollCollapse": true,
                                        "sPaginationType": "bootstrap",
                                        "bRetrieve": true,
                                        "oLanguage": {
                                            "sSearch": "Search:"
                                        },
                                        "bProcessing": true,
                                        "bServerSide": true,
                                        "sAjaxSource": '<?php echo site_url('trx09/home/getdatatable_user/' . $idh); ?>',
                                        "fnServerData": function(sSource, aoData, fnCallback) {
                                            $.ajax({
                                                "dataType": 'json',
                                                "type": "POST",
                                                "url": sSource,
                                                "data": aoData,
                                                "success": fnCallback
                                            });
                                        },
                                        "aoColumns": [
                                            {"mData": "ComputerName", "sTitle": "Computer Name", "sClass": "left"}
                                        ],
                                        "fnDrawCallback": function(oSettings) {
                                            $("#tableuser tbody tr").on('mouseenter', function() {
                                                $('#tableuser tbody tr').addClass("selectable");

                                            });
                                        }
                                    });
                                });

                            }
                            
                            
                              function home_createuser() {
                                $(document).ready(function() {
                                    var OcreateUser = $('#tablecreateuser').dataTable({
                                        "bJQueryUI": false,
                                        "bSortClasses": false,
                                        "aaSorting": [[2, "desc"]],
                                        "bAutoWidth": true,
                                        "bInfo": true,
                                        "sScrollY": "100%",
                                        "sScrollX": "100%",
                                        "bScrollCollapse": true,
                                        "sPaginationType": "bootstrap",
                                        "bRetrieve": true,
                                        "oLanguage": {
                                            "sSearch": "Search:"
                                        },
                                        "bProcessing": true,
                                        "bServerSide": true,
                                        "sAjaxSource": '<?php echo site_url('trx09/home/getdatatable_createuser/' . $idh); ?>',
                                        "fnServerData": function(sSource, aoData, fnCallback) {
                                            $.ajax({
                                                "dataType": 'json',
                                                "type": "POST",
                                                "url": sSource,
                                                "data": aoData,
                                                "success": fnCallback
                                            });
                                        },
                                        "aoColumns": [
                                            {"mData": "UserID", "sTitle": "UserID", "sClass": "left"},
                                            {"mData": "StatusUser", "sTitle": "Status", "sClass": "center"},
                                            {"mData": "InternalEmail", "sTitle": "Internal Email", "sClass": "left"},
                                            {"mData": "ExternalEmail", "sTitle": "External Email", "sClass": "left"},
                                            {"mData": "InternetStatus", "sTitle": "Internet", "sClass": "center"},
                                        ],
                                        "fnDrawCallback": function(oSettings) {
                                            $("#tablecreateuser tbody tr").on('mouseenter', function() {
                                                $('#tablecreateuser tbody tr').addClass("selectable");

                                            });
                                        }

                                    });

                                });

                            }
                            
                            
                            function home_software() {
                                $(document).ready(function() {
                                    var OSoftware = $('#tablesoftware').dataTable({
                                        "bJQueryUI": false,
                                        "bSortClasses": false,
                                        "aaSorting": [[2, "desc"]],
                                        "bAutoWidth": true,
                                        "bInfo": true,
                                        "sScrollY": "100%",
                                        "sScrollX": "100%",
                                        "bScrollCollapse": true,
                                        "sPaginationType": "bootstrap",
                                        "bRetrieve": true,
                                        "oLanguage": {
                                            "sSearch": "Search:"
                                        },
                                        "bProcessing": true,
                                        "bServerSide": true,
                                        "sAjaxSource": '<?php echo site_url('trx09/home/getdatatable_software/' . $idh); ?>',
                                        "fnServerData": function(sSource, aoData, fnCallback) {
                                            $.ajax({
                                                "dataType": 'json',
                                                "type": "POST",
                                                "url": sSource,
                                                "data": aoData,
                                                "success": fnCallback
                                            });
                                        },
                                        "aoColumns": [
                                            {"mData": "SoftwareName", "sTitle": "Software", "sClass": "left"},
                                            {"mData": "SoftwareStatus", "sTitle": "Status", "sClass": "center"}

                                        ],
                                        "fnDrawCallback": function(oSettings) {
                                            $("#tablesoftware tbody tr").on('mouseenter', function() {
                                                $('#tablesoftware tbody tr').addClass("selectable");

                                            });
                                        }

                                    });
                                });
                            }
                            
                            
                              function home_cratefolder() {
                                $(document).ready(function() {

                                    var OCreateFolder = $('#tablecreatefolder').dataTable({
                                        "bJQueryUI": false,
                                        "bSortClasses": false,
                                        "aaSorting": [[2, "desc"]],
                                        "bAutoWidth": true,
                                        "bInfo": true,
                                        "sScrollY": "100%",
                                        "sScrollX": "100%",
                                        "bScrollCollapse": true,
                                        "sPaginationType": "bootstrap",
                                        "bRetrieve": true,
                                        "oLanguage": {
                                            "sSearch": "Search:"
                                        },
                                        "bProcessing": true,
                                        "bServerSide": true,
                                        "sAjaxSource": '<?php echo site_url('trx09/home/getdatatable_createfolder/' . $idh); ?>',
                                        "fnServerData": function(sSource, aoData, fnCallback) {
                                            $.ajax({
                                                "dataType": 'json',
                                                "type": "POST",
                                                "url": sSource,
                                                "data": aoData,
                                                "success": fnCallback
                                            });
                                        },
                                        "aoColumns": [
                                            {"mData": "FolderName", "sTitle": "Folder Name", "sClass": "left"},
                                            {"mData": "FolderStatus", "sTitle": "Status", "sClass": "center"}

                                        ],
                                        "fnDrawCallback": function(oSettings) {
                                            $("#tablecreatefolder tbody tr").on('mouseenter', function() {
                                                $('#tablecreatefolder tbody tr').addClass("selectable");

                                            });
                                        }

                                    });

                                });

                            }
                            
                            
                              function home_accessfolder() {
                                $(document).ready(function() {
                                    var OAccessFolder = $('#tableaccessfolder').dataTable({
                                        "bJQueryUI": false,
                                        "bSortClasses": false,
                                        "aaSorting": [[2, "desc"]],
                                        "bAutoWidth": true,
                                        "bInfo": true,
                                        "sScrollY": "100%",
                                        "sScrollX": "100%",
                                        "bScrollCollapse": true,
                                        "sPaginationType": "bootstrap",
                                        "bRetrieve": true,
                                        "oLanguage": {
                                            "sSearch": "Search:"
                                        },
                                        "bProcessing": true,
                                        "bServerSide": true,
                                        "sAjaxSource": '<?php echo site_url('trx09/home/getdatatable_accessfolder/' . $idh); ?>',
                                        "fnServerData": function(sSource, aoData, fnCallback) {
                                            $.ajax({
                                                "dataType": 'json',
                                                "type": "POST",
                                                "url": sSource,
                                                "data": aoData,
                                                "success": fnCallback
                                            });
                                        },
                                        "aoColumns": [
                                            {"mData": "FolderAccess", "sTitle": "Folder Access", "sClass": "left"},
                                            {"mData": "AccessStatus", "sTitle": "Status", "sClass": "center"}

                                        ],
                                        "fnDrawCallback": function(oSettings) {
                                            $("#tableaccessfolder tbody tr").on('mouseenter', function() {
                                                $('#tableaccessfolder tbody tr').addClass("selectable");

                                            });
                                        }

                                    });
                                });


                            }
                            
                            
                              function home_agreement() {
                                $(document).ready(function() {
                                    var OAgreement = $('#tableagreement').dataTable({
                                        "bJQueryUI": false,
                                        "bSortClasses": false,
                                        "aaSorting": [[2, "desc"]],
                                        "bAutoWidth": true,
                                        "bInfo": true,
                                        "sScrollY": "100%",
                                        "sScrollX": "100%",
                                        "bScrollCollapse": true,
                                        "sPaginationType": "bootstrap",
                                        "bRetrieve": true,
                                        "oLanguage": {
                                            "sSearch": "Search:"
                                        },
                                        "bProcessing": true,
                                        "bServerSide": true,
                                        "sAjaxSource": '<?php echo site_url('trx09/home/getdatatable_agreement/' . $idh); ?>',
                                        "fnServerData": function(sSource, aoData, fnCallback) {
                                            $.ajax({
                                                "dataType": 'json',
                                                "type": "POST",
                                                "url": sSource,
                                                "data": aoData,
                                                "success": fnCallback
                                            });
                                        },
                                        "aoColumns": [
                                            {"mData": "StatusAgreement", "sTitle": "Status", "sClass": "center"}

                                        ],
                                        "fnDrawCallback": function(oSettings) {
                                            $("#tableagreement tbody tr").on('mouseenter', function() {
                                                $('#tableagreement tbody tr').addClass("selectable");

                                            });
                                        }

                                    });
                                });

                            }






</script>

<!--    </div>
</div>-->
