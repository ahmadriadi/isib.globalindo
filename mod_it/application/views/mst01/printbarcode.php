<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<?php $base_url = $this->session->userdata('sess_base_url'); ?>

<!-- JQueryUI -->
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-latest.js"></script>
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>

 <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" rel="stylesheet" />

<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/css/smoothness/demos.css" rel="stylesheet" />
<!-- DataTables Plugin --> 
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css" rel="stylesheet" />

<script src="<?php echo $base_url ?>public/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 




<!-- DataTables Tables Plugin -->
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>


<div class="widget">
    <div class="widget-head">
        <div class="row-fluid">
            <div class="span6">
                <h4 class="heading">Print Barcode</h4>                
            </div>
            <div class="span6" style="text-align: right">
                <button onclick="refresh()" class="btn btn-small btn-success btn-icon glyphicons refresh"><i>Refresh</i></button>
                <button onclick="back()" class="btn btn-small btn-success btn-icon glyphicons home"><i></i>Back to Home</button>
            </div>
        </div>
    </div>
    <div class="widget-body">
      
            <table>
                <tr>
                    <td>Code Item</td>
                    <td>:</td>
                    <td><?php echo $code; ?></td> 
                </tr>                
                <tr>
                    <td>Item Name</td>
                    <td>:</td>
                    <td><?php echo $name; ?></td> 
                </tr>                
                <tr>
                    <td>Note</td>
                    <td>:</td>
                    <td><?php echo $note; ?></td> 
                </tr>   
                
               </table>
             

        <table id="tableprint" cellspacing="1" cellpadding="1" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable" >
            <thead>
                <tr>
                    
                     <!--<th class="center">No.</th> -->
                    <th class="center">Action</th>
                    <th class="center">Computer Name</th>                    
                    <th class="center">IP Address</th>
                    <th class="center">Port</th>
		    <th class="center">Printer</th>	
                    <th class="center">Status Socket</th>
                    <th class="center">Status Connection</th> 
		                     
                </tr>
            </thead>
            <tbody>        
                <?php
               
                foreach ($statusprinter as $row) {
                   echo $row['tr'];
                     }
                   ?>
                    
              
            </tbody> 
        </table>
    </div>
</div>



<script type="text/javascript">
                     var id= '<?php echo $id ?>';
                    $(document).ready(function() {

                        $(function()
                        {
                            /* DataTables */
                            if ($('#tableprint').size() > 0)
                            {
                                $('#tableprint').dataTable({
                                    "sPaginationType": "bootstrap",
                                    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
                                    "oLanguage": {
                                        "sLengthMenu": "_MENU_"
                                    },
				    "aaSorting": [[1, "desc"]],			
                                    "sScrollX": "100%",
                                    "sScrollXInner": "110%",
                                    "bScrollCollapse": true,
				    "bDestroy": true	
                                });
                            }
                        });
                        });
                        
                  function printbarcode(idprinter){
                      
                       $.ajax({
                                    type: "POST",
                                    url: '<?php echo site_url('mst01/home/viewprintbarcode') ?>' + '/' + id,
                                    dataType: "json",
                                    cache: false,
                                    success:
                                            function(data) {
                                        
                                           
                                               if (data.valid == 'true') {
                                                   
                                                   $("#code").html(data.code)
                                                   $('#name').html(data.name);
                                                   $('#note').html(data.note);
                                               }
                                            },
                                    error:
                                            function(xhr, ajaxOptions, thrownError) {
                                                alert(xhr.status);
                                                alert(thrownError);
                                            }
                                });
                                

                            var Printdata = $('#printbar');
                            var CancelPrint = $('#cancelprint');
                              
                              $('#PrintBarcode').modal('show')
                               Printdata.click(function (){
                               var qtyrow = $('#qtyrow').val();  
                                       $.ajax({
                                        type: "POST",
                                        url: '<?php echo site_url('mst01/home/printbarcode') ?>' + '/' + idprinter+'/'+id+'/'+qtyrow,
                                        dataType: "json",
                                        cache: false,
                                        success:
                                              function(data) {
                                                    $('#qtyrow').val('');
                                                    $('#PrintBarcode').modal('hide');
                                                     reloadpage();
                                                },
                                        error:
                                                function(xhr, ajaxOptions, thrownError) {
                                                    alert(xhr.status);
                                                    alert(thrownError);
                                                }
                                    });
                                   }); 
                                   CancelPrint.click(function (){
                                      var qtyrow = $('#qtyrow').val('');
                                      $('#PrintBarcode').modal('hide');
                                       refresh();
                                   }); 

                  } 
                  
                  
                    function refresh() {
                        var content = $("#content");
                        var url = ROOT.base_url + 'mod_it/index.php/mst01/home/getprinter';
                        content.load(url+'/'+id);
                    }
                    
                    function back() {
                        var content = $("#content");
                        var url = ROOT.base_url + 'mod_it/index.php/mst01/home/';
                        content.load(url);
                    }
             
</script>    

<!-- Modal -->
<div class="modal fade" id="PrintBarcode" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">     
      <div class="modal-body">
       <table>
            <tbody>
                <tr>
                    <td><label style="color:white;">Code : &nbsp;&nbsp;&nbsp;&nbsp;<span id='code'></span></label> </td>
                </tr>
                <tr>
                    <td><label style="color:white;">Name : &nbsp;&nbsp;&nbsp;&nbsp;<span id='name'></span></label> </td>
                </tr>
                <tr>
                    <td><label style="color:white;">Note : &nbsp;&nbsp;&nbsp;&nbsp;<span id='note'></span></label> </td>
                </tr>                
                <tr>
                    <td><label style="color:white;">Qty Row </label></td>
                    <td>:</td>
                    <td> <input class="span1" id="qtyrow" name="qtyrow" type="text"></td>
                </tr>            
            </tbody>
        </table>  
      </div>
      <div class="modal-footer">
        <button id="cancelprint" type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button id="printbar" type="button" class="btn btn-primary">Print</button>
      </div>
    </div>
  </div>
</div>