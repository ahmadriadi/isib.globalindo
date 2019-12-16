<style>
    .red-tooltip + .tooltip > .tooltip-inner {background-color: #800;}
    .cur{
        cursor: pointer;
    }
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
    $('[data-toggle="tooltip"]').tooltip();
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
    function pop(idmemo){
        $.ajax({
            url     : ROOT.base_url+"mod_empcenter/index.php/trx03/home/captcha",
            data    : "idmemo="+idmemo,
            type    : "post",
            dataType: "html",
            success : function (data){
                $("button[idmemo='"+idmemo+"']").attr("data-content",data);
            }
        });
    }
    function readmemo(idmemo){
        $("button[btn='print']").attr("idmemo",idmemo);
//        $("button[btn='smaller']").hide();
//        $("tr.selected").removeClass("selected");
//        $("tr").addClass("selectable");
//        $("tr."+idmemo).removeClass("selectable");
//        $("tr."+idmemo).addClass("selected");

        $.ajax({
            url     : ROOT.base_url+"mod_empcenter/index.php/trx03/home/get_memo",
            data    : "idmemo="+idmemo,
            type    : "post",
            dataType: "json",
            cache   : false,
            success : function (data){
                //alert(data.toSource());
                $("#ridmemo").text(data.memo.IDMemo);
                $("#rmemodate").text(data.memo.MemoDate);
                $("#ridf").text(data.memo.FromID);
                $("#rnmf").text(data.memo.FromName);
                $("#rdivf").text(data.memo.FromDiv);
                $("#rposf").text(data.memo.FromPos);
                $("#ridt").text(data.memo.ToID);
                $("#rnmt").text(data.memo.ToName);
                $("#rdivt").text(data.memo.ToDiv);
                $("#rpost").text(data.memo.ToPos);
                $("#rsubj").html(data.memo.MemoSubject);
                $("#rtext").html(data.memo.MemoText);

                $("span.label."+idmemo).remove();
                //$("button#folup"+idmemo).prop("disabled",false);
                $("button#crtfol"+idmemo).prop("disabled",false);

//                var active = $("#btnnew").attr("wactive");
//                $("."+active).removeAttr("style");
//                $("#proses").val("send_memo");
//                $("div."+active).removeClass("span12");
//                $("div."+active).addClass("span6");
//                $("#formread").addClass("span6");
                $("#formread").show({
                    easing : "slide",
                    duration: 500,
                    complete: function (){
                        $("#notmemo").hide({});
//                        $("#memotoname").focus();
                    }
                });                          
            }
        });              
    }
    function printmemo(){
        var idmemo = $("button[btn='print']").attr("idmemo");
        $("head").append("<style id='styletbh1'>.modal{width: 90%;height: 80%;margin-left: -45%;}</style>");
        $("head").append("<style id='styletbh2'>.modal-body{position: relative;overflow-y: auto;height: 87%;max-height: 87%;padding: 0px;}</style>");
        bootbox.dialog("<iframe width='100%' height='100%' src='"+ROOT.base_url+"mod_empcenter/index.php/trx03/home/print_memo/"+idmemo+"'>"+"</iframe>",{
            label   : "Close",
            class   : "btn-danger",
            callback: function (){
                $("#styletbh1").remove();
                $("#styletbh2").remove();
            }
        });
    }
    function closeread(){
        $("#formread").hide({
            easing : "slide",
            duration: 500,
            complete: function (){
                $("#notmemo").show({});
//                        $("#memotoname").focus();
            }
        });          
    }
</script>
<div id="notmemocon" style="display: none;">
<!--+++++++++++++++++++++++++++  form read +++++++++++++++++++++++++++-->
<div class="widget widget-body" style="background-color: #ddd; color: #000; display: none;" id="formread">
    <h4 class="center" style="color: #000;">Memo Preview</h4>
    <hr class="separator">
    <div class="row-fluid">
        <div class="span12">
            <table border="0" width="100%">
                <tr>
                    <td ><b>Memo No</b> / <i class="transindo">Nomor Memo</i>  </td>
                    <td>:</td>
                    <td width="20px"> <span id="ridmemo"></span></td>
                    <td colspan="4" align="right">
                        <button onclick="printmemo()" btn="print" idmemo="" title="print" data-toggle="tooltip" data-original-title="print" data-placement="top" class="red-tooltip btn btn-mini btn-success"><i class="icon-print"></i></button>
 <!--                       <button onclick="halfwidth('formread')" btn="smaller" title="smaller" data-toggle="tooltip" data-original-title="smaller" data-placement="top" class="red-tooltip btn btn-mini btn-primary"><-</button>
                        <button onclick="fullwidth('formread')" btn="wider" title="wider" data-toggle="tooltip" data-original-title="wider" data-placement="top" class="red-tooltip btn btn-mini btn-primary">-></button>-->
                        <button onclick="closeread()" title="close" data-toggle="tooltip" data-original-title="close" data-placement="top" class="red-tooltip btn btn-mini btn-danger">x</button>
                    </td>
                </tr>
                <tr>
                    <td width="200px"><b>Memo Date</b> / <i class="transindo">Tanggal Memo</i></td>
                    <td width="5px">:</td>
                    <td colspan="4" align="left"> <span id="rmemodate"></span></td>
                </tr>
                <tr>
                    <td colspan="6">
                        <div class="row-fluid">
                            <div class="span12" id="tfrom">
                                <table class="widget-body">
                                    <tr>
                                        <td><u><b>From</b> / <i class="transindo">Dari</i></u></td>
                                    </tr>
                                    <tr>
                                        <td><b>ID Employee</b> / <i class="transindo">NIP</i></td>
                                        <td>:</td>
                                        <td><span id="ridf"></span></td>
                                    </tr> 
                                    <tr>
                                        <td><b>Name</b> / <i class="transindo">Nama</i> </td>
                                        <td>:</td>
                                        <td><span id="rnmf"></span></td>
                                    </tr>
                                    <tr>
                                        <td><b>Department</b> / <i class="transindo">Divisi</i> </td>
                                        <td>:</td>
                                        <td ><span id="rdivf"></span></td>
                                    </tr>
                                    <tr>
                                        <td ><b>Position</b> / <i class="transindo">Posisi</i></td>
                                        <td>:</td>
                                        <td><span id="rposf"></span></td>
                                    </tr>
                                </table>                                                    
                            </div>
                            <div class="span12" id="tto" style="margin-left: 0px;">
                                <table class="widget-body">
                                    <tr>
                                        <td><u><b>To</b> / <i class="transindo">Kepada</i></u></td>
                                    </tr>
                                    <tr>
                                        <td><b>ID Employee</b> / <i class="transindo">NIP</i></td>
                                        <td>:</td>
                                        <td><span id="ridt"></span></td>
                                    </tr> 
                                    <tr>
                                        <td><b>Name</b> / <i class="transindo">Nama</i> </td>
                                        <td>:</td>
                                        <td><span id="rnmt"></span></td>
                                    </tr>
                                    <tr>
                                        <td><b>Department</b> / <i class="transindo">Divisi</i> </td>
                                        <td>:</td>
                                        <td ><span id="rdivt"></span></td>
                                    </tr>
                                    <tr>
                                        <td ><b>Position</b> / <i class="transindo">Posisi</i></td>
                                        <td>:</td>
                                        <td><span id="rpost"></span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td colspan="6"><b><h5 style="color: #000;">Subject :  <a id="rsubj" style="color: #000;"></a> </h5></b> </td>
                </tr>
                <tr>
                    <td colspan="6"><b><h5 style="color: #000;">Memo : </h5></b><span id="rtext"></span></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<!--+++++++++++++++++++++++++++ end of form read +++++++++++++++++++++++++++--> 
<div class="span12" align="center" id="notmemo" style="margin-left: 0px;">
    <h4>Incomplete Confirmations</h4>
    <hr class="separator">
    <div class="row-fluid">
        <div class="span12">
            <table width="100%" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable dynamicTable">
                <thead class="btn-primary">
                    <tr>
                        <td>Submit Date</td>
                        <td>From</td>
                        <td class="forhide">To</td>
                        <td class="forhide">Subject</td>
                        <td class="forhide">Memo</td>
                        <td>Confirm</td>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($memos->result() as $mm){
                        $dm = $this->mmm->get_memo($mm->IDMemo)->row();
                        $tip = 'class="span12 red-tooltip cur" data-toggle="tooltip" data-original-title="click to open!" data-placement="top"';
                        echo "<tr class='selectable'>";
                        echo "<td onclick='readmemo(\"$dm->IDMemo\")' ><span $tip >".$dm->AddedDate."</span></td>";
                        echo "<td onclick='readmemo(\"$dm->IDMemo\")' ><span $tip >".substr($dm->FromName,0,15)."</span></td>";
                        echo "<td onclick='readmemo(\"$dm->IDMemo\")' class='forhide'><span $tip >".substr($dm->ToName,0,15)."</span></td>";
                        echo "<td onclick='readmemo(\"$dm->IDMemo\")' class='forhide'><span $tip >".substr($dm->MemoSubject,0,15)."</span></td>";
                        echo "<td onclick='readmemo(\"$dm->IDMemo\")' class='forhide'><span $tip >".  substr($dm->MemoText,0, 15)."</span></td>";
//                        echo "<td>$icp->Note</td>";
                        echo "<td><center>";
                        echo "<button onmouseover=\"pop('$mm->IDMemo')\" data-toggle='popover' data-title='incomplete confirmation' data-content='' data-placement='left' idmemo='$mm->IDMemo' class='btn btn-inverse btn-small'>confirm</button>";
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
</div>