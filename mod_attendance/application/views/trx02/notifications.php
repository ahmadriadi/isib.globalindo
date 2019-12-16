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
    function pop(idlpermit){
        $.ajax({
            url     : ROOT.base_url+"mod_attendance/index.php/trx02/home/captcha",
            data    : "idlpermit="+idlpermit,
            type    : "post",
            dataType: "html",
            success : function (data){
                $("button[idlpermit='"+idlpermit+"']").attr("data-content",data);
            }
        });
    }
</script>
<div class="span12" align="center" id="notlpermit" style="margin-left: 0px; display: none;">
    <h4>Leave Permit Confirmations</h4>
    <hr class="separator">
    <div class="row-fluid">
        <div class="span12">
            <table width="100%" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable utama dynamicTable">
                <thead>
                    <tr>
                        <th>Submit Date</th>
                        <th>Name</th>
                        <th class="forhide">Out Time</th>
                        <th class="forhide">In Time</th>
                        <th class="forhide">Total Time</th>
                        <th>Necessity</th>
                        <th class="forhide">Vehicle Number</th>
                        <th>Confirm</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($lpermits->result() as $lp){
                        $nec    = $lp->Necessity == 1 ? "Personal" : "Official";
                        $prs    = $this->lpt->get_personal($lp->IDEmployee)->row();
                        $nama   = $prs->FullName;
                        echo "<tr>";
                        echo "<td>$lp->LeavePermitDate</td>";
                        echo "<td>$nama</td>";
                        echo "<td class='forhide'>$lp->OutDate</td>";
                        echo "<td class='forhide'>$lp->InDate</td>";
                        echo "<td class='forhide'>$lp->IMKHour</td>";
                        echo "<td>$nec $lp->Note</td>";
                        echo "<td class='forhide'>$nec $lp->VehicleNo</td>";
                        echo "<td>";
                        echo "<a href='#'><button onmouseover='pop(\"$lp->ID\")' data-toggle='popover' data-title='leave permit confirmation' data-content='' data-placement='left' idlpermit='$lp->ID' class='btn btn-inverse btn-small'>confirm</button></a>";
                        echo "</td>";
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
            $(".tempat_accepted").html('<table class="table table-bordered table-condensed table-primary table-vertical-center js-table-sortable dynamicTable" id="acceptedlpermit"></table>');            
            var state   = "<?php echo $state;?>";
            var sTable  = "<?php echo site_url();?>/trx02/home/get_accepted/"+state;
            var oTable = $('#acceptedlpermit').dataTable({
                "bJQueryUI": false,
                "bSortClasses": false,
                "aaSorting": [[3, "desc"]],
                "bAutoWidth": true,
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
                    {"mData": "OutDate", "sTitle": "Leave Date", "sClass": "left",
                        "mRender" : function (a,b,c){
                            var qq  = c.OutDate.split(" ");
                            var ww  = c.InDate.split(" ");
                            var ff  = qq[0] == ww[0] ? qq[0]+" = "+qq[1]+" - "+ww[1] : c.OutDate+" - "+c.InDate;
                            return ff;
                        }
                    },
                    {"mData": "Note", "sTitle": "Reason", "sClass": "left",
                        "mRender" : function (a,b,c){
                            var t = c.Necessity == "1" ? "Personal" : "Official";
                            return "<b>("+t+")</b> "+c.Note;
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
                    List of Accepted Leave Permit<br>
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