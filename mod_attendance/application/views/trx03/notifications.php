<style>
    .forhide{
        transition-duration: 0.3s;
        display: none;
    }
    .warning_sign td{
        font-weight: bold;
        color: #ee1e2f !important;
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
    function pop(idincomplete,dticp){
        var notfor = "<?php echo $notfor?>";
        $.ajax({
            url     : ROOT.base_url+"mod_attendance/index.php/trx03/home/captcha",
            data    : "idincomplete="+idincomplete+"&notfor="+notfor+"&dticp="+dticp,
            type    : "post",
            dataType: "html",
            success : function (data){
                $("button[idincomplete='"+idincomplete+"']").attr("data-content",data);
            }
        });
    }
</script>

<div class="span12" align="center" id="notincomplete" style="margin-left: 0px; display: none;">
    <?php // echo "periode : ".$this->fnicp->periode_posting();?>
    <h4>Incomplete Confirmations</h4>
    <hr class="separator">
    <div class="row-fluid">
        <div class="span12">
            <table width="100%" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable utama dynamicTable">
                <thead>
                    <tr>
                        <th>Submit Date</th>
                        <th>Name</th>
                        <th class="forhide">Incomplete Date</th>
                        <th class="forhide">In Time</th>
                        <th class="forhide">Out Time</th>
                        <th>Description</th>
                        <th>Confirm</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($incompletes as $icp){
//                        $thismonth = date('m');
//                        $from   = date('Y-m-d', strtotime(""))
                        $periode = explode(" ",$this->libfun->periode_posting());
                        $start  = $periode[0];
                        $end    = $periode[1];
                        //$whicp  = array(
                        //    "IDEmployee"    => $icp->IDEmployee,
                        //    "IncompleteDate >=" => date('Y-m-d', strtotime($start)),
                        //    "IncompleteDate <=" => date('Y-m-d', strtotime($end)),
                        //   "ConfirmFlag"   => "1",
                        //    "CHRDFlag"      => "1"
                        //);
                        $whicp  = array(
                            "IDEmployee"    => $icp->IDEmployee,
                            "IncompleteDate >=" => date('Y-m-d', strtotime($start)),
                            "IncompleteDate <=" => date('Y-m-d', strtotime($end)),
                            "ConfirmFlag"   => "1",
                        );
                        $dticp  = $this->incomplete->get_incomplete($whicp)->num_rows();
//                        echo $icp->IDEmployee." => ".$dticp;
                        if ($dticp > 0){
                            $wa = " warning_sign ";
                            $cbtn   = "btn-danger";
                        }
                        if ($dticp == 0){
                            $wa = "";
                            $cbtn   = "btn-inverse";
                        }
                        $prs    = $this->incomplete->get_personal($icp->IDEmployee)->row();
                        $nama   = $prs->FullName;
                        echo "<tr class='$wa selectable'>";
                        echo "<td >".date('d-m-Y',strtotime($icp->AddedDate))."</td>";
                        echo "<td >$nama [$dticp] </td>";
                        echo "<td class='forhide'>".date('d-m-Y',strtotime($icp->IncompleteDate))."</td>";
                        echo "<td class='forhide'>$icp->TimeIn</td>";
                        echo "<td class='forhide'>$icp->TimeOut</td>";
                        echo "<td>$nec $icp->Note</td>";
                        echo "<td><center>";
                        echo "<button onmouseover='pop(\"$icp->ID\",\"$dticp\")' data-toggle='popover' data-title='incomplete confirmation' data-content='' data-placement='left' idincomplete='$icp->ID' class='btn $cbtn btn-small'>confirm</button>";
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
            $(".tempat_accepted").html('<table class="table table-bordered table-condensed table-primary table-vertical-center js-table-sortable dynamicTable" id="acceptedincomplete"></table>');            
            var state   = "<?php echo $state;?>";
            var sTable  = "<?php echo site_url();?>/trx03/home/get_accepted/"+state;
            var oTable = $('#acceptedincomplete').dataTable({
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
                    {"mData": "IncompleteDate", "sTitle": "Date", "sClass": "left"},
                    {"mData": "Note", "sTitle": "Description", "sClass": "left"},
                    {"mData": "Note", "sTitle": "Time", "sClass": "left",
                        "mRender" : function (a,b,c){
                            var ff = c.TimeIn+" - "+c.TimeOut;
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
                    List of Accepted Incomplete<br>
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