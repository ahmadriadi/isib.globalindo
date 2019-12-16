<style>
    .forhide{
        transition-duration: 0.3s;
        display: none;
    }
</style>
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
    function pop(idtravel){
        $.ajax({
            url     : ROOT.base_url+"mod_attendance/index.php/trx04/home/captcha",
            data    : "idtravel="+idtravel,
            type    : "post",
            dataType: "html",
            success : function (data){
                $("button[idtravel='"+idtravel+"']").attr("data-content",data);
            }
        });
    }
</script>
<div class="span12" align="center" id="notofftravel" style="margin-left: 0px; display: none; ">
    <h4>Official Travel Confirmations</h4>
    <hr class="separator">
    <div class="row-fluid">
        <div class="span12">
            <table width="100%" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable dynamicTable">
                <thead class="btn-primary">
                    <tr>
                        <td>Submit Date</td>
                        <td>Name</td>
                        <td class='forhide'>Travel Date</td>
                        <td>Description</td>
                        <td class="forhide">Vehicle Number</td>
                        <td>Confirm</td>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($otravels->result() as $ot){
                        $prs    = $this->otr->get_personal($ot->IDEmployee)->row();
                        $nama   = $prs->FullName;
                        echo "<tr>";
                        echo "<td>".date('d-m-Y',strtotime($ot->AddedDate))."</td>";
                        echo "<td>$nama</td>";
                        echo "<td class='forhide'>$ot->OfficialTravelDate to $ot->UntilDate</td>";
                        echo "<td> $ot->Note</td>";
                        echo "<td class='forhide'>$nec $ot->VehicleNo</td>";
                        echo "<td>";
                        echo "<button onmouseover='pop(\"$ot->ID\")' data-toggle='popover' data-title='official travel confirmation' data-content='' data-placement='left' idtravel='$ot->ID' class='btn btn-inverse btn-small'>confirm</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>


        </div>
    </div>
</div>    