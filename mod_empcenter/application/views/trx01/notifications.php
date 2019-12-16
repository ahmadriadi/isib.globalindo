        <?php $base_url = $this->session->userdata('sess_base_url'); ?> 
<style>
    .forhide{
        display: none;
    }
    .table-primary{
        background: #8ec657;
    }    
    .sickness{color: #EC5800;font-weight: bold;}
</style>
<!-- JQueryUI -->
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" rel="stylesheet" />
<script>
    $('.utama').dataTable({
        "aaSorting": [[ 0, "desc" ]],
        "sPaginationType": "bootstrap",
        "bDestroy": true,
        "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
        "oLanguage": {
                "sLengthMenu": "_MENU_ records per page"
        }
    });
    $('[data-toggle="popover"]').popover({
        html : true
    });
    $('body').on('click', function (e) {
        $('[data-toggle="popover"]').each(function () {
            //the 'is' for buttons that trigger popups
            //the 'has' for icons within a button that triggers a popup
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                $(this).popover('hide');
            }
        });
    });
    function pop(idleave,ckode,step,userid){
        var urlnya = "<?php echo site_url()."/trx01/home/load_confirm/"?>"+ckode+"/"+step+"/"+userid+"/web";
//        alert(urlnya);
        $.ajax({
            url : urlnya,
            data: "",
            type: "post",
            dataType : "html",
            success : function (data){
                $('button[data-idleave="'+idleave+'"]').attr("data-content",data);                    
            }                
        });

    }
    $("[data-toggle='tooltip']").tooltip();
</script> 
<?php

if ($state == "pic"){?>

    <div class="span12" align="center" id="notpiccon" style="margin-left: 0px; display: none; ">
        <h4>
            Leave Confirmations<br>
            <span>Person in Charge</span>
        </h4>
        <hr class="separator">
        <div class="row-fluid">
            <?php // print_r($detail);?>
            <div class="span12">
                <table class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable utama dynamicTable">
                    <thead >
                        <tr>
                            <th>Name</th>
                            <th>Reason</th>
                            <th>Leave Date</th>
                            <th class="forhide">Total</th>
                            <th >Confirm</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($detail as $d){
                            $jenis  = array(
                                "AL"    => "Annual Leave",
                                "CL"    => "Condolence Leave",
                                "CIR"   => "Circumsion Leave",
                                "MTL"   => "Maternity Leave",
                                "SL"    => "Sick Leave",
                                "MRL"   => "Marriage Leave",
                                "OL"    => "Unpaid Leave",
                            );                            
                            $e = $this->lvm->get_employee($d->IDEmployee)->row();                            
                            echo "<tr>";
                            echo "<td>".$e->FullName."</td>";
                            if($d->Jenis =='SL'){
                                if($d->SickLetter=='1'){
                                    $ket = "With Letter";
                                }else{
                                    $ket = "Without Letter";
                                }
                                echo '<td><span class="sickness" data-toggle="popover" data-title="Sickness Letter" data-content="'.$ket.'" data-placement="right"><b data-toggle="tooltip" data-original-title="click to view the letter" data-placement="top" >"'.$jenis[$d->Jenis]."</u> : ".$d->Alasan.'"</b></span></td>';
                            }else{
                                echo "<td>".$jenis[$d->Jenis]."</u> : ".$d->Alasan."</td>";
                            }
                            echo "<td>".$d->TglCutiDari." to ".$d->TglCutiSampai."</td>";
                            echo "<td class='forhide'>".$d->TotalCuti." days </td>";
                            echo "<td><button onmouseover='pop(\"$d->IDLeave\",\"$d->Ckode\",\"1\",\"$d->IDEmployee\")' data-toggle='popover' data-title='leave confirmation' data-idleave='".$d->IDLeave."' data-content='' data-placement='left' class='btn btn-small btn-inverse'>Confirm</button></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>    
<?php } 
if ($state == "hod"){
?>
    <div class="span12" align="center" id="nothodcon" style="margin-left: 0px; display: none;">
        <h4>
            Leave Confirmations<br>
            <span>Head of Department</span>
        </h4>
        <hr class="separator">
        <div class="row-fluid">
            <div class="span12">
                <table class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable  utama dynamicTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Reason</th>
                            <th class="forhide">Person in Charge</th>
                            <th>Leave Date</th>
                            <th class="forhide">Total</th>
                            <th>Confirm</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($detail as $d){
                            $jenis  = array(
                                "AL"    => "Annual Leave",
                                "CL"    => "Condolence Leave",
                                "CIR"   => "Circumsion Leave",
                                "MTL"   => "Maternity Leave",
                                "SL"    => "Sick Leave",
                                "MRL"   => "Marriage Leave",
                                "OL"    => "Unpaid Leave",
                            );                            
                            $e = $this->lvm->get_employee($d->IDEmployee)->row();
                            $pgt = $this->lvm->get_employee($d->IDPengganti)->row();
                            echo "<tr>";
                            echo "<td>".$e->FullName."</td>";
                            if($d->Jenis =='SL'){
                                if($d->SickLetter=='1'){
                                    $ket = "With Letter";
                                }else{
                                    $ket = "Without Letter";
                                }
                                echo '<td><span class="sickness" data-toggle="popover" data-title="Sickness Letter" data-content="'.$ket.'" data-placement="right"><b data-toggle="tooltip" data-original-title="click to view the letter" data-placement="top" >"'.$jenis[$d->Jenis]."</u> : ".$d->Alasan.'"</b></span></td>';
                            }else{
                                echo "<td>".$jenis[$d->Jenis]."</u> : ".$d->Alasan."</td>";
                            }
                            echo "<td class='forhide'>".$pgt->FullName." </td>";
                            echo "<td>".date('d-m-Y',strtotime($d->TglCutiDari))." to ".date('d-m-Y',strtotime($d->TglCutiSampai))."</td>";
                            echo "<td class='forhide'>".$d->TotalCuti." days </td>";
                            echo "<td><button onmouseover='pop(\"$d->IDLeave\",\"$d->Ckode\",\"2\",\"$d->IDEmployee\")' data-toggle='popover' data-title='leave confirmation' data-idleave='".$d->IDLeave."' data-content='' data-placement='left' class='btn btn-small btn-inverse'>Confirm</button></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>    
<?php } 
if ($state == "hrd"){
?>
    <div class="span12" align="center" id="nothrdcon" style="margin-left: 0px; display: none;">
        <h4>
            Leave Confirmations<br>
            <span>Human Resource Development</span>
        </h4>
        <hr class="separator">
        <div class="row-fluid">
            <div class="span12">
                <table class="table table-bordered table-condensed table-striped table-primary table-vertical-center  utama js-table-sortable dynamicTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Reason</th>
                            <th class="forhide">Person in Charge</th>
                            <th>Leave Date</th>
                            <th class="forhide">Total</th>
                            <th>Confirm</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($detail as $d){
                            $jenis  = array(
                                "AL"    => "Annual Leave",
                                "CL"    => "Condolence Leave",
                                "CIR"   => "Circumsion Leave",
                                "MTL"   => "Maternity Leave",
                                "SL"    => "Sick Leave",
                                "MRL"   => "Marriage Leave",
                                "OL"    => "Unpaid Leave",
                            );
                            $e = $this->lvm->get_employee($d->IDEmployee)->row();
                            $pgt = $this->lvm->get_employee($d->IDPengganti)->row();
                            echo "<tr>";
                            echo "<td>".$e->FullName."</td>";
                             if($d->Jenis =='SL'){
                                if($d->SickLetter=='1'){
                                    $ket = "With Letter";
                                }else{
                                    $ket = "Without Letter";
                                }
                                echo '<td><span class="sickness" data-toggle="popover" data-title="Sickness Letter" data-content="'.$ket.'" data-placement="right"><b data-toggle="tooltip" data-original-title="click to view the letter" data-placement="top" >"'.$jenis[$d->Jenis]."</u> : ".$d->Alasan.'"</b></span></td>';
                            }else{
                                echo "<td>".$jenis[$d->Jenis]."</u> : ".$d->Alasan."</td>";
                            }
                            echo "<td class='forhide'>".$pgt->FullName." </td>";
                            echo "<td>".date('d-m-Y',strtotime($d->TglCutiDari))." to ".date('d-m-Y',strtotime($d->TglCutiSampai))."</td>";
                            echo "<td class='forhide'>".$d->TotalCuti." days </td>";
                            echo "<td><button onmouseover='pop(\"$d->IDLeave\",\"$d->Ckode\",\"3\",\"$d->IDEmployee\")' data-toggle='popover' data-title='leave confirmation' data-idleave='".$d->IDLeave."' data-content='' data-placement='left' class='btn btn-small btn-inverse'>Confirm</button></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>    
<?php } ?>
<div class="row-fluid" style="display: none;" id="list_of_accepted">
    <script>
        $(document).ready(function(){
            create_accepted();
        });
        function create_accepted(){
            $(".tempat_accepted").empty();
            $(".tempat_accepted").html('<table class="table table-bordered table-condensed table-primary table-vertical-center js-table-sortable dynamicTable" id="acceptedleaves"></table>');            
            var state   = "<?php echo $state;?>";
            var sTable  = "<?php echo site_url();?>/trx01/home/get_accepted/"+state;
            var oTable = $('#acceptedleaves').dataTable({
                "bJQueryUI": false,
                "bSortClasses": false,
                "aaSorting": [[3, "desc"]],
                "bAutoWidth": false,
                "bInfo": true,
                "sScrollY": "100%",
                "sScrollX": "100%",
                "bScrollCollapse": true,
//                    "sPaginationType": "listbox_paging",                           
                "sPaginationType2": "bootstrap",  
                "bRetrieve": true,
                "oLanguage": {
                    "sSearch": "Search:"
                },
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource": sTable,
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
                    {"mData": "Name", "sTitle": "Name", "sClass": "left",
                        "mRender" : function (a,b,c){
                            return "<b>("+c.Jenis+")</b> "+c.Name;
                        }
                    },
                    {"mData": "Dari", "sTitle": "LeaveDate", "sClass": "left",
                        "mRender" : function (a,b,c){
                            return c.Dari+" = "+c.Total+" days";
                        }
                    },
                    {"mData": "Reason", "sTitle": "Reason", "sClass": "left"},
                    {"mData": "ApprovalDate", "sTitle": "App Date", "sClass": "left"},
                ],
                "fnDrawCallback": function(oSettings) {
                    $('tr').addClass("selectable");
                }
            });            
        }
    </script>
    
    <div class="span12" style="margin-left: 0px;" id="judulaccepted">
        <div class="row-fluid"  >
            <div class="span12 center"><br><br>
                <h4 >
                    List of Accepted Leave<br>
                    <?php
                    $sub    = $state == "pic" ? "Person in Charge" : ($state == "hod" ? "Head of Department" : "Resource Development");
                    echo "<span>$sub</span>";
                    ?>
                </h4>
            </div>
        </div>
        <hr>
        <div class="row-fluid">
            <div class="span12 tempat_accepted">
                
            </div>
        </div>
    </div>
</div>
