<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<style>
    .waiting{color: #ececec;font-weight: bold;}
    .solved{color: #1ce718;font-weight: bold;}
    .suspended{color: #EC5800;font-weight: bold;}
    .unsolved{color: #fc2c2c;font-weight: bold;}
    .inprog{color: #0066cc;font-weight: bold;}
    a.ui-dialog-titlebar-close { display:block;}
</style>
        <?php  $base_url = $this->session->userdata('sess_base_url'); ?>
<!-- DataTables Plugin --> 
<link href="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css" rel="stylesheet" />
<!-- <link href="<?php //echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/development-bundle/demos/demos.css" rel="stylesheet" />
<!-- JQueryUI -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-latest"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>

 <!--<script src="<?php echo $base_url; ?>public/bootstrap/js/bootstrap.js"></script>-->
<script src="<?php echo $base_url; ?>public/bootstrap/js/popup.js"></script>
<!-- DataTables Tables Plugin -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/api/fnStandingRedraw.js"></script>


<!-- Gritter Notifications Plugin -->
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>  
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />
<div class="alert alert-primary">
    <button class="close" data-dismiss="alert" type="button">×</button>
    <strong>Attention!</strong> Click on the status text to view the detailed information about your submitted report/request.
</div>
 
<div class="widget">

    <div class="widget-head">
        <div class="row-fluid">
            <div class="span6">
                <h4 class="heading">Data Contact IT</h4>                
            </div>
            <div class="span6" style="text-align: right;">
                <button onclick="reloadpage()" class="btn btn-small btn-default"><i class="icon-refresh"></i></button>
                <button onclick="backtohome()" class="btn btn-small btn-success btn-icon glyphicons home"><i></i>Back to Home</button>                
            </div>
        </div>
    </div>
    <div class="widget-body">
           <div class="btn-group">
           <p>
            <table>
                <tr>
                    <td>
                        <label>Fromdate</label> <input type="text" name="fromdate" id="fromdate" class='span2' value='<?php echo $default['from'] ?>' /> 
                    </td>
                    <td>                        
                        <label>Untildate</label><input type="text" name="untildate" id="untildate" class='span2' value='<?php echo $default['until'] ?>' />
                    </td>

                </tr>                
            </table>
            </p>
            <p>    
            <button id='btn_add' onClick="reply_click(this.id)"  class="btn" id="editable-sample_new">
                   ADD <i class="icon-plus"></i>
            </button>
           <button id='btn_export' onClick="reply_click(this.id)"  class="btn" id="editable-sample_new">
                   EXCEL<i class="icon-download"></i>
            </button> 
           <button id='btn_print' onClick="reply_click(this.id)"  class="btn" id="editable-sample_new">
                   PRINT<i class="icon-print"></i>
            </button> 
             </p>
            </div>
   
        <!-- Table -->
            <table width="100%" id="tableajax" cellspacing="1" cellpadding="1" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable dynamicTable" >
           
           </table>
        

    <div id="homedetail" class="row-fluid" style="display:none;"></div> 
        
           
     
    </div>
</div>
<div id="user-form" 
     title="FORM USER" 
     >
</div>
<div id="dialog-confirm" title="DELETE REQUEST">
    <p id="textconfirm" style="display:none;">
        Are you sure want to delete this data?
    </p>   
</div>

    
<script type="text/javascript">
    $('body').on('click', function () {
        hidepopovers();
    });
    function hidepopovers(){
        $('[data-toggle="popover"]').each(function () {
            //the 'is' is for buttons that trigger popups
            //the 'has' is for icons within a button that triggers a popup
//            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                $(this).popover('hide');
//            }
        });
    }
    function reloadpage(){
        var content = $("#content .innerLR");
        var url = ROOT.base_url + 'mod_security/index.php/trx09/home/';
        content.empty();
        content.load(url);
    }
    function backtohome(){
        window.location.href = "<?php echo $base_url;?>";
    }
     $(document).ready(function() {
            
                    var dataajax = '<?php echo site_url('trx09/home/getdatatable') ?>';
                    
                    var oTable = $('#tableajax').dataTable({
                        "bJQueryUI": false,
                        "bSortClasses": false,
                        "aaSorting": [[2, "desc" ]],
                        "bAutoWidth": false,
                        "bInfo": true,
//                        "sScrollY": "100%",
//                        "sScrollX": "100%",
                        "bScrollCollapse": true,
                        "sPaginationType": "bootstrap",
                        "bRetrieve": true,
                        "oLanguage": {
                            "sSearch": "Search:"
                        },
                        "bProcessing": true,
                        "bServerSide": true,
                        "sAjaxSource": dataajax,
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
                              {
                                "mData": "ID", "sTitle": "Action",
                                "bSortable": false,
                                "mRender": function(aoData) {
                                    return "<div class='btn-group'>\n\
                                                    <button btn='btn_edit' idbtn=" + aoData + "  type='button' class='btn btn-mini btn-warning'><i class='icon-pencil icon-white'></i></button>\n\
                                                    <button btn='btn_delete' idbtn=" + aoData + "  type='button' class='btn btn-mini btn-danger'><i class='icon-trash'></i></button>\n\
                                                    </div>\n\
                                                    ";
                                }
                            },
			    {"mData": "AddedBy", "sTitle": "Employee ID", "sClass": "left"},
                            {"mData": "FullName", "sTitle": "Employee Name", "sClass": "left"},	
			    {"mData": "Location", "sTitle": "Location", "sClass": "left"},	     	                            
                            {"mData": "RootName", "sTitle": "Problem", "sClass": "left"},
                            {"mData": "ComplainNote", "sTitle": "Note", "sClass": "left",
                                "mRender": function (a,b,c){
                                    var ret;
                                    if (a.length <= 35){
                                        ret = a;
                                    }
                                    else if (a.length > 35){
                                        ret = "<a data-toggle='popover' data-placement='top' data-content='<div align=\"left\">"+a+"</div>'>";
                                        ret = ret+a.substring(0,35)+" ...";
                                        ret = ret+"</a>";
                                    }
                                    return ret;
                                }
                            },
                            {"mData": "ComplainDate", "sTitle": "Date", "sClass": "center",sType: 'date-eu'},
                            {"mData": "Status", "sTitle": "Status", "sClass": "left",
                             "mRender"  : function (a,b,c){
                                var kls = "";
                                var msg = "";
                                msg = msg+"<h5>Problem : </h5>";
                                msg = msg+c.ProblemNote;
                                msg = msg+"<h5>Solution : </h5>";
                                msg = msg+c.SolutionNote;
                                
                                if (a == "Waiting" && (c.HODC == "2" || c.HODC == "1")){kls="waiting";}
                                else if (c.HODC == "0"){kls = "waiting"; a = "Waiting HoD ";}
                                else if (c.HODC == "3"){kls = "unsolved"; a = "Rejected by HoD"; msg = "<h5>Rejection Note :</h5>"+c.RNote;}
                                else if (a == "Suspended"){kls="suspended";}
                                else if (a == "Solved"){kls="solved";}
                                else if (a == "Unsolved"){kls="unsolved";}
                                else if (a == "In Progress"){kls="inprog";}
				else if (a == "Reject"){kls="unsolved";}
                                return "<span class='"+kls+"' data-toggle='popover' data-placement='left' data-content=\""+msg+"\">"+a+"</span>"; 
                             }
                            },
			    {"mData": "ID", "sTitle": "Ref", "sClass": "left",
                                "mRender": function (a,b,c){
                                    return "#"+a;
                                }
                            },                            
                        ],
                        "fnDrawCallback": function(oSettings) {
                            $("[data-toggle='popover']").popover({
                                html    : true
                            });
                            $("#tableajax tbody tr ").on('mouseleave',function(){
                                hidepopovers();
                            });
                            
                            $("#tableajax tbody tr ").on('click', 'button', function() {

                                // alert(aRow);
                                var button = $(this).attr("btn");
                                var id = $(this).attr("idbtn");

                                $.ajax(
                                    {
                                        type: "POST",
                                        url: '<?php echo site_url('trx09/home/getstatus') ?>',
                                        dataType: "json",
                                        data: "id=" + id,
                                        cache: false,
                                        success:
                                                function(data, text)
                                                {
                                                    if (data.valid == 'true') {                                 
                                                         var status = data.flag; 
                                                         reply_click(button + '-' + id+'-'+status);
                                                    }
                                                    else {                                      
                                                    }

                                                },
                                        error: function(request, status, error) {
                                            alert(request.responseText + " " + status + " " + error);
                                        }
                                    });
                                    return false;   

                            });
                            
                            $('#tableajax tbody tr').on('click', function() {
                                $('#tableajax tbody tr').removeClass("selected");
                                var posisidata = $('td', this);
                                var id = $(posisidata[7]).text();
                                var type = $(posisidata[3]).text();
                                $(this).addClass("selected");
                                homedetail(id,type);
                                return false;
                            });                            
                            
                            $("#tableajax tbody tr").on('mouseenter', function() {
                                $('#tableajax tbody tr').addClass("selectable");
                            });
                            $("#tableajax tbody tr").on('mouseenter', function() {
                                $("[data-toggle='popover']").popover();
                                $("[data-toggle='tooltip']").tooltip();
                            });
                        }
                    });
                    
                    $("#untildate").datepicker( {dateFormat:"dd-mm-yy"});
                    $("#fromdate").datepicker( {dateFormat:"dd-mm-yy"});
                    
                    
                        var url_periode = '<?php echo site_url('trx09/home/set_pattern_date') ?>';
                        var content = $("#content .innerLR");
                        var site = "mod_security/index.php/trx09/home";
                        var urlsite = ROOT.base_url + site;
                        
                        $("#fromdate").change(function(){
                            var fromdate=$("#fromdate").val();
                            var untildate=$("#untildate").val();                            
                            $.ajax({
                                type: "POST",
                                url: url_periode,
                                dataType: "json",
                                data: "fromdate="+fromdate+"&untildate="+untildate,
                                cache:false,
                                success:
                                    function(data){
                                    if(data.valid){                                      
                                         content.fadeOut("slow", "linear");
                                         content.load(urlsite);
                                         content.fadeIn("slow");
                                       
                                    }
                                }
                            });
                        }); //end from

                        $("#untildate").change(function() {
                            var fromdate=$("#fromdate").val();
                            var untildate=$("#untildate").val();
                            $.ajax({
                                type: "POST",
                                url: url_periode,
                                dataType: "json",
                                data: "fromdate="+fromdate+"&untildate="+untildate,
                                cache:false,
                                success:
                                    function(data){
                                    if(data.valid){
                                        content.fadeOut("slow", "linear");
                                        content.load(urlsite);
                                        content.fadeIn("slow");
                                    }
                                }
                            });
                        }); //end until     
                        

                });
                
                
                     
                  function homedetail(id,type){
                      
                      id    = id.replace("#","");
//                      alert(id+"|"+type);
                    
                    if(type=='REQUEST'){
                     $("#homedetail").show();   
                      var url = "<?php echo site_url('trx09/home/home_detail') ?>" + "/" + id;
                       $("#homedetail").load(url);
//                       hidepopovers();
                      return false;
                    }else{
                      $("#homedetail").hide();
//                      hidepopovers();
                    }
                 
                  
                  }                     

                    function reply_click(clicked_id){
                        var str = clicked_id;
                        var explode = str.split('-');
                        var button = explode[0];
                        var id = explode[1];
			var status = explode[2];
			
			 var keterangan='';
                        if(status=='0'){
                             keterangan ='<b class="waiting">Waiting</b>';
                        }else if(status=='1'){
                             keterangan ='<b class="solved">Solved</b>';
                        }else if(status=='2'){
                             keterangan ='<b class="suspended">Suspended</b>';
                        }else if(status=='3'){
                             keterangan ='<b class="unsolved">Unsolved</b>';
                        }else if(status=='4'){
                             keterangan ='<b class="inprog">In Progress</b>';
                        }
		
			

                        if (button == 'btn_add') {
                            var content = $("#content .innerLR");
                            var site = "mod_security/index.php/trx09/home/addnew";
                            var url = ROOT.base_url + site;
                            content.fadeOut("slow", "linear");
                            content.load(url);
                            content.fadeIn("slow");
                        } 
                        else if (button == 'btn_edit'){
			   if(status =='1'){
                             $.gritter.add({                                
                                 title: 'WARNING',
                                 text: "Data has been "+keterangan+". You can't edit the data.",
                                 image: '<?php echo $base_url.'public/theme/images/warni.jpeg' ?>',
                                 class_name: 'gritter-light',                                
                                 fade_in_speed: 100, 
                                 fade_out_speed: 100, 
                                 time: 2500 
                             });
                             return false;
                            }
                            else{					
                            var content = $("#content .innerLR");
                            var site = "mod_security/index.php/trx09/home/edit";
                            var url = ROOT.base_url + site + "/" + id;
                            content.fadeOut("slow", "linear");
                            content.load(url);
                            content.fadeIn("slow");
			   }	

                        }else if (button == 'btn_print') {                               
                                var site = "mod_security/index.php/trx09/home/reportdata";
                                var url = ROOT.base_url + site;

		
                                $("head").append("<style id='styletbh1'>.modal{width: 90%;height: 80%;margin-left: -45%;}</style>");
                                $("head").append("<style id='styletbh2'>.modal-body{position: relative;overflow-y: auto;height: 87%;max-height: 87%;padding: 0px;}</style>");
                                bootbox.dialog("<iframe width='100%' height='100%' src='" + url + "'>" + "</iframe>", {
                                    label: "Close",
                                    class: "btn-danger",
                                    callback: function() {
                                        $("#styletbh1").remove();
                                        $("#styletbh2").remove();
                                    }
                                });
                              
                         } else if (button == 'btn_check') {
                            var content = $("#content .innerLR");
                            var site = "mod_security/index.php/trx09/home/checkdata";
                            var url = ROOT.base_url + site + "/" + id;
                            content.fadeOut("slow", "linear");
                            content.load(url);
                            content.fadeIn("slow");

                        }else if (button == 'btn_delete') {
                            $(document).ready(function()
                            {
                                var contentdel = $("#content .innerLR");
                                var site = 'mod_security/index.php/trx09/home';
                                var urldel = ROOT.base_url + site;
                                
                               if(status =='1'){        
			             $.gritter.add({                                
			                 title: 'WARNING',
	                                 text: "Data has been "+keterangan+". You can't delete the data.",
			                 image: '<?php echo $base_url.'public/theme/images/warni.jpeg' ?>',
			                 class_name: 'gritter-light',                                
			                 fade_in_speed: 100, 
			                 fade_out_speed: 100, 
			                 time: 2500 
			             });
			             return false;
                           
                            }else{ 
                              
                                //alert('<?php echo site_url('trx09/home/delete') ?>'+'/'+id);
                                $("#textconfirm").show();
                                $(function() {
                                    $("#dialog-confirm").dialog({
                                        resizable: false,
                                        height: 140,
                                        modal: true,
                                        buttons: {
                                            "Delete ": function() { 
                                                $.ajax({
                                                    type: "POST",
                                                    url: '<?php echo site_url('trx09/home/delete') ?>'+'/'+id,
                                                    dataType: "json",
                                                    cache: false,
                                                    success:
                                                            function(data) {
                                                               $("#tableajax").dataTable().fnStandingRedraw(); 
                                                            },
                                                    error:
                                                            function(xhr, ajaxOptions, thrownError) {
                                                                alert(xhr.status);
                                                                alert(thrownError);
                                                            }
                                                });

                                                $(this).dialog("close");
                                            },
                                            Cancel: function() {
                                                $(this).dialog("close");

                                            }
                                        }
                                    });
                                });
				}
                            });
                        } else if(button == 'btn_export'){
                            window.location.href = '<?php echo site_url('trx09/home/exportdata') ?>';
                        }
                    }
</script>

<div id="atl-form" 
     title="FORM IT SUPPORT" 
     >
</div>


