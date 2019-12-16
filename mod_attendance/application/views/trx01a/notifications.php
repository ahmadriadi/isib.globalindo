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
    function pop(idovertime){
        $.ajax({
            url     : ROOT.base_url+"mod_attendance/index.php/trx01a/home/captcha",
            data    : "idovertime="+idovertime,
            type    : "post",
            dataType: "html",
            success : function (data){
                $("button[idovertime='"+idovertime+"']").attr("data-content",data);
            }
        });
    }
</script>
<div class="span12" align="center" id="notovertime" style="margin-left: 0px; display: none;">
    <h4>Overtime Confirmations</h4>
    <hr class="separator">
    <div class="row-fluid">
        <div class="span12">
            <table width="100%" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable dynamicTable">
                <thead class="btn-primary">
                    <tr>
                        <td>Submit Date</td>
                        <td>Name</td>
                        <td class="forhide">ID SPKL</td>
                        <td class="forhide">Presence Date</td>
                        <td class="forhide">In Time</td>
                        <td class="forhide">Out Time</td>
                        <td>Description</td>
                        <td>Confirm</td>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($overtimes->result() as $ot){
                        $prs    = $this->overtime->get_personal($ot->IDEmployee)->row();
                        $nama   = $prs->FullName;
                        echo "<tr>";
                        echo "<td>".date('d-m-Y',strtotime($ot->AddedDate))."</td>";
                        echo "<td>$nama</td>";
                        echo "<td class='forhide'>$ot->IDSPKL</td>";
                        echo "<td class='forhide'>$ot->PresenceDate</td>";
                        echo "<td class='forhide'>$ot->OvertimeIn</td>";
                        echo "<td class='forhide'>$ot->OvertimeOut</td>";
                        echo "<td>$nec $ot->Note</td>";
                        echo "<td><center>";
                        echo "<button onmouseover='pop(\"$ot->ID\")' data-toggle='popover' data-title='overtime confirmation' data-content='' data-placement='left' idovertime='$ot->ID' class='btn btn-inverse btn-small'>confirm</button>";
                        echo "</center></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
                    <?php 
//                        print_r($lpermits->result());
                    ?>

        </div>
    </div>
</div>    