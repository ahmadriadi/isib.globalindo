

<style>
    .forhide{
        transition-duration: 0.3s;
        display: none;
    }
    .table-primary{
        background: #8ec657;
    }    
</style>
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
    function pop(idovertime){
        $.ajax({
            url     : ROOT.base_url+"mod_attendance/index.php/trx01/home/captcha",
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
            <table width="100%" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable utama dynamicTable">
                <thead>
                    <tr>
                        <th>Submit Date</th>
                        <th>Name</th>
                        <th class="forhide">ID SPKL</th>
                        <th class="forhide">Presence Date</th>
                        <th class="forhide">In Time</th>
                        <th class="forhide">Out Time</th>
                        <th>Description</th>
                        <th>Confirm</th>
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
<div class="row-fluid" style="display: none;" id="list_of_accepted">
    <script>
        $(document).ready(function(){
            create_accepted();
        });
        function create_accepted(){
            $(".tempat_accepted").empty();
            $(".tempat_accepted").html('<table class="table table-bordered table-condensed table-primary table-vertical-center js-table-sortable dynamicTable" id="acceptedovertime"></table>');            
            var state   = "<?php echo $state;?>";
            var sTable  = "<?php echo site_url();?>/trx01/home/get_accepted/"+state;
            var oTable = $('#acceptedovertime').dataTable({
                "bJQueryUI": false,
                "bSortClasses": false,
                "aaSorting": [[4, "desc"]],
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
                            return c.Name;
                        }
                    },
                    {"mData": "IDSPKL", "sTitle": "IDSPKL", "sClass": "left"},
                    {"mData": "Note", "sTitle": "Description", "sClass": "left"},
                    {"mData": "Note", "sTitle": "Time", "sClass": "left",
                        "mRender" : function (a,b,c){
                            var qq  = c.OvertimeIn.split(" ");
                            var ww  = c.OvertimeOut.split(" ");
                            var ff  = qq[0] == ww[0] ? qq[0]+" = "+qq[1]+" - "+ww[1] : c.OvertimeIn+" - "+c.OvertimeOut;
                            return ff;
                        }
                    },
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
                    List of Accepted Overtime<br>
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