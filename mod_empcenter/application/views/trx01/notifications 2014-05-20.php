        <?php $base_url = $this->session->userdata('sess_base_url'); ?> 
<style>
    .forhide{
        display: none;
    }
</style>
<!-- JQueryUI -->
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" rel="stylesheet" />
<script>
    $('.dynamicTable').dataTable({
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
                <table class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable dynamicTable">
                    <thead class="btn-primary">
                        <tr>
                            <td>Name</td>
                            <td>Reason</td>
                            <td>Leave Date</td>
                            <td class="forhide">Total</td>
                            <td >Confirm</td>
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
                            echo "<td>".$jenis[$d->Jenis]."</u> : ".$d->Alasan."</td>";
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
                <table class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable dynamicTable">
                    <thead class="btn-primary">
                        <tr>
                            <td>Name</td>
                            <td>Reason</td>
                            <td class="forhide">Person in Charge</td>
                            <td>Leave Date</td>
                            <td class="forhide">Total</td>
                            <td>Confirm</td>
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
                            echo "<td>".$jenis[$d->Jenis]."</u> : ".$d->Alasan."</td>";
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
            <span>Resource Development</span>
        </h4>
        <hr class="separator">
        <div class="row-fluid">
            <div class="span12">
                <table class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable dynamicTable">
                    <thead class="btn-primary">
                        <tr>
                            <td>Name</td>
                            <td>Reason</td>
                            <td class="forhide">Person in Charge</td>
                            <td>Leave Date</td>
                            <td class="forhide">Total</td>
                            <td>Confirm</td>
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
                            echo "<td><u>".$jenis[$d->Jenis]."</u> : ".$d->Alasan."</td>";
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