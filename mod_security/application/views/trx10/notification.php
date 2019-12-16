        <?php $base_url = $this->session->userdata('sess_base_url'); ?> 
<style>
    .forhide{
        display: none;
    }
    .sickness{color: #EC5800;font-weight: bold;}
    .deskripsi{
        cursor : pointer;
    }
    a{
        color: rgb(142, 198, 87);
    }    
</style>
<!-- JQueryUI -->
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" rel="stylesheet" />
<script>
    $(document).ready(function (){
        $('.dynamicTable').dataTable({
            "aaSorting": [[ 0, "desc" ]],
            "sPaginationType": "bootstrap",
            "bDestroy": true,
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "oLanguage": {
                    "sLengthMenu": "_MENU_ records per page"
            }
        });

        $('body').on('click', function (e) {
            $('[data-toggle="popover"]').each(function (){
                //the 'is' for buttons that trigger popups
                //the 'has' for icons within a button that triggers a popup
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0){
                    $(this).popover('hide');
                }
            });
        });
        $("#nottable tbody tr").click(function (){
            var idrpt   = $(this).attr("idrpt");
            var type    = $(this).attr("type");
            var sub     = $(this).attr("sub");
            if (type == '9'){//jika request
                if (sub == "close"){
                    var ini = $(this);
                    $.ajax({
                        url     : "<?php echo site_url()?>/trx10/home/get_request_detail",
                        data    : "idrpt="+idrpt,
                        type    : "post",
                        dataType: "html",
                        cache   : false,
                        success : function (data){
//                            alert(data);
                            ini.after("<tr><td colspan='6'>"+data+"</td></tr>");
                            ini.attr("sub","open");
                        },
                        error   : function(a){
                            alert(a.responseText);
                        }
                    });
                }
                else{
                    $(this).next().remove();
                    $(this).attr("sub","close");
                }
            }
            else{
                return false;
            }
            
        });
    });

    function pop(idrpt){
        var urlnya = "<?php echo site_url();?>/trx10/home/load_confirm/";
        $.ajax({
            url : urlnya,
            data: "idrpt="+idrpt,
            type: "post",
            dataType : "html",
            success : function (data){
                $('button[data-idrpt="'+idrpt+'"]').attr("data-content",data);
            },
            error   : function (a){
                alert(a.responseText);
            }
        });
    }
    $("[data-toggle='tooltip']").tooltip();
</script> 

    <div class="span12" align="center" id="notureport" style="margin-left: 0px; display: none; ">
        <h4>
            Report/Request Confirmations<br>
            <span>Head of Department</span>
        </h4>
        <hr class="separator">
        <div class="row-fluid">
            <?php // print_r($detail);?>
            <div class="span12">
                <table id='nottable' width="100%" class="table table-bordered table-condensed table-primary table-vertical-center js-table-sortable dynamicTable">
                    <thead >
                        <tr>
                            <th>Ref</th>
                            <th>Name</th>
                            <th>Problem</th>
                            <th class="forhide">Date</th>
                            <th class="forhide">Time</th>
                            <th >Confirm</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
//                        print_r($reports->result());
                        foreach ($reports->result() as $r){
                            echo "<tr class='selectable' sub='close' idrpt='$r->ID' type='$r->IDRoot'>";
                            echo "<td>#$r->ID</td>";
                            echo "<td>$r->EmpName</td>";
                            $pjg    = strlen($r->ComplainNote);
                            echo "<td><span class='deskripsi'data-toggle='popover' data-content=\"$r->ComplainNote\" data-placement='left'>".  ($pjg > 35 ? substr($r->ComplainNote,0,31)." ...": $r->ComplainNote) ."</span></td>";
                            echo "<td class='forhide'>$r->AddedDate</td>";
                            echo "<td class='forhide'>$r->AddedDate</td>";
                            echo "<td><button onmouseover='pop(\"$r->ID\")' data-toggle='popover' data-title='user request/report confirmation' data-idrpt='".$r->ID."' data-content='' data-placement='left' class='btn btn-small btn-inverse'>Confirm</button></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
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
            $(".tempat_accepted").html('<table class="table table-bordered table-condensed table-primary table-vertical-center js-table-sortable dynamicTable" id="acceptedrepreq"></table>');            
            
            var sTable  = "<?php echo site_url();?>/trx10/home/get_accepted/";
            var oTable = $('#acceptedrepreq').dataTable({
                "bJQueryUI": false,
                "bSortClasses": false,
                "aaSorting": [[3, "desc"]],
                "bAutoWidth": false,
                "bInfo": true,
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
                    {"mData": "Ref", "sTitle": "Ref", "sClass": "left",
                        "mRender" : function (a){
                            return "#"+a;
                        }
                    },
                    {"mData": "Name", "sTitle": "Name", "sClass": "left",
                        "mRender" : function (a,b,c){
                            return c.Name;
                        }
                    },
                    {"mData": "CompNote", "sTitle": "Problem", "sClass": "left",
                        "mRender"   : function (a){
                            var pjg = a.length;
                            var ret = "";
                            if (pjg > 35){
                                ret = "<a data-toggle='popover' data-placement='right' data-content=\""+a+"\" >"+a.substring(0,31)+" ...</a>";
                            }
                            else{
                                ret = a;
                            }
                            return ret;
                        }
                    },
                    {"mData": "CompDate", "sTitle": "Date", "sClass": "left"},
                    {"mData": "Confirmation", "sTitle": "App Status", "sClass": "left",
                        "mRender" : function (a,b,c){
                            var status = a == 1 ? "Accepted" : "Rejected";
                            return status;
                        }
                    },
                    {"mData": "ConfDate", "sTitle": "App Date", "sClass": "left"},
                ],
                "fnDrawCallback": function(oSettings) {
                    $('tr').addClass("selectable");
                    $('[data-toggle="popover"]').popover({
                        html : true
                    });
                }
            });
        }
    </script>
    
    <div class="span12" style="margin-left: 0px;" id="judulaccepted">
        <div class="row-fluid"  >
            <div class="span12 center"><br><br>
                <h4 >
                    List of Confirmed Report/Request<br>
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

