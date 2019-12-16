<!--MEMO-->
<html>
    <head>
        <!-- Meta -->
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta content="utf-8" http-equiv="encoding" />

        <?php $base_url = $this->session->userdata('sess_base_url'); ?>
        <!-- JQueryUI -->
       
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" />
        <!-- hide the close link in the toolbar -->

        <style type="text/css">
            .red-tooltip + .tooltip > .tooltip-inner {background-color: #800;}
            .transindo{
                font-size: 10px;
            }
            .upper{text-transform:uppercase;}
            a.ui-dialog-titlebar-close { display:none; } .label_error_cuti{color : #be362f;}
            .c{
                transition: 0.5s;
            }
            .tabatas{
                font-size: 15px;
            }
            #addform{
                transition: 0.3s;
            }
            #formread{
                transition: 0.3s;
            }
        </style>    
        <!-- Gritter Notifications Plugin -->
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />

        <!-- DataTables Plugin -->
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css" rel="stylesheet" />
        
        <!-- JQuery -->
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-latest.js"></script>

        <!-- JQueryUI -->
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
<!--        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>-->
        <!--timepicker-->
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/timepicker/timepicker.css" rel="stylesheet" />
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/timepicker/timepicker.js"></script>
        <!--wysih-->
	<link href="<?php echo $base_url; ?>public/bootstrap/extend/bootstrap-wysihtml5/css/bootstrap-wysihtml5-0.0.2.css" rel="stylesheet">
	<!-- Bootstrap -->
	<script src="<?php echo $base_url; ?>public/bootstrap/js/bootstrap.js"></script>
        <!-- Gritter Notifications Plugin -->
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>
	<!-- DataTables Tables Plugin -->
	<script src="<?php echo $base_url; ?>/public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
	<!-- Tables Demo Script -->
	<!--<script src="<?php echo $base_url; ?>public/theme/scripts/demo/tables.js"></script>-->  
        <!--wysih-->
	<script src="<?php  echo $base_url; ?>public/bootstrap/extend/bootstrap-wysihtml5/js/wysihtml5-0.3.0_rc2.min.js"></script>
	<script src="<?php echo $base_url; ?>public/bootstrap/extend/bootstrap-wysihtml5/js/bootstrap-wysihtml5-0.0.2.js"></script>        
        <script>
            $(document).ready(function (){
                
		$('.dynamicTable').dataTable({
                    "aaSorting": [[ 0, "desc" ]],
                    "sPaginationType": "bootstrap",
                    "bDestroy": true,
                    "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
                    "oLanguage": {
                        "sLengthMenu": "_MENU_ records per page"
                    },
                    "aoColumnDefs"  :[
                        {'bSortable' : false, 'aTargets' : [0]}
                    ]
		});
                $('textarea.wysihtml5').wysihtml5();
                $("#formadd").css("margin-left","0px");
                $(".inmemo").css("margin-left","0px");
                $(".outmemo").css("margin-left","0px");
                $("#memotext-wysihtml5-toolbar").find("li.dropdown").remove();
                $("#memoid").keypress(false);
                $("#memodate").keypress(false);
                $("#memotoid").keypress(false);
                $("#memotodiv").keypress(false);
                $("#memotopos").keypress(false);
                $("#formadd").hide();
                $("#btnin").hide();
                <?php 
                if ($state != NULL){
                    if ($state == "in"){
                        echo '$(".outmemo").hide();';
                    }
                    if ($state == "out"){
                        echo '$(".inmemoic").removeClass("active");';
                        echo '$(".outmemoic").addClass("active");';
                        echo 'outmemo()';
                    }
                }
                if ($state == NULL){
                    echo '$(".outmemo").hide();';
                }
                ?>

                $("#formread").hide();
//                load_personal();
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
            var suggest = <?php echo $suggest;?>;
            $("#memotoname").autocomplete({
                source  : suggest,
                select  : function (e,ui){
                    $("#memotoid").val(ui.item.iduser);
                    $("#memotodiv").val(ui.item.divisi);
                    $("#memotopos").val(ui.item.posisi);
                }
            });

//            $("#outtime").datetimepicker({dateFormat:"dd-mm-yy", timeFormat :"HH:mm:ss"});
//            $("#intime").datetimepicker({dateFormat:"dd-mm-yy", timeFormat :"HH:mm:ss"});
            var ROOT = {
                'site_url'  : '<?php echo $base_url . '/index.php'; ?>',
                'base_url'  : '<?php echo $base_url; ?>'
            };
            function loading(){
                bootbox.alertloading("<center>Processing. Please wait !!!<br><img src='<?php echo $base_url; ?>public/avatar/76.GIF'></center>");
            }
            function reloadpage(){
                var content = $("#content .innerLR");
                var url = ROOT.base_url + 'mod_empcenter/index.php/trx03/home/';

                content.load(url);
            }
            function backtohome(){
                window.location.href = "<?php echo $base_url;?>";
            }
            function load_personal(){
//                $.ajax({
//                    url     : ROOT.base_url+"mod_attendance/index.php/trx02/home/get_personal",
//                    data    : "",
//                    type    : "post",
//                    dataType: "json",
//                    cache   : false,
//                    success : function (data){
//                        $("#fname").val(data.FullName);
//                        $("#iduser").val(data.IDEmployee);
//                        $("#depart").val(data.IDDepartement);
//                        $("#position").val(data.IDJobPosition);
//                    }
//                });
            }
            function openform(){
                $("#memoid").val("");
//                $("#memodate").val("");
                $("#memotext").val("");
                $("#memosubject").val("");
                $("#memotoid").val("");
                var active = $("#btnnew").attr("wactive");
                $("."+active).removeAttr("style");
                                
                $("div."+active).removeClass("span12");
                $("div."+active).addClass("span6");
                $("#formadd").addClass("span6");
                $("#formadd").show({
                    easing : "slide",
                    duration: 500,
                    complete: function (){
                        $("#memotoname").focus();
                    }
                });
            }
            
            function inmemo(){
                cancelact();
//                $("#btnin").hide({
//                   easing   : "slide",
//                   duration : 300,
//                   complete : function (){
//                       $("#btnout").show({});
//                   }
//                });
                $("li.inmemoic").removeClass("envelope");
                $("li.inmemoic").addClass("message_in");
                $("li.outmemoic").removeClass("message_out");
                $("li.outmemoic").addClass("envelope");
                $("#btnnew").attr("wactive","inmemo");
                $(".outmemo").hide({
                    easing : "slide",
                    duration: 300,
                    complete: function (){
                        $(".inmemo").show({});
                    }
                });                
            }
            function outmemo(){
                cancelact();
//                $(".mdate").attr("width","15%");
//                $(".mpers").attr("width","35%");
//                $(".msub").attr("width","30%");
//                $(".mact").attr("width","20%");
                $(".det").attr("style","white-space: nowrap; overflow-x: hidden;");
//                $("#btnout").hide({
//                   easing   : "slide",
//                   duration : 300,
//                   complete : function (){
//                       $("#btnin").show({});
//                   }
//                });
                $("li.outmemoic").removeClass("envelope");
                $("li.outmemoic").addClass("message_out");
                $("li.inmemoic").removeClass("message_in");
                $("li.inmemoic").addClass("envelope");
                $("#btnnew").attr("wactive","outmemo");
                $(".inmemo").hide({
                    easing : "slide",
                    duration: 300,
                    complete: function (){
                        $(".outmemo").show({});
                    }
                });
            }
            function newmemo(){
                $("#proses").val("send_memo");
                $("#ifadd").show();
                $("#ifedit").hide();
                openform();

            }
            function editmemo(idmemo){
                $("#proses").val("upd_memo");
                $("#ifadd").hide();
                $("#ifedit").show();
                openform();
                
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx03/home/get_memo",
                    data    : "idmemo="+idmemo+"&v=out&s=",
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
//                        alert(data.toSource());
                        var att = data.att;
                        var res = '';
                        for (var x =0;x < att.length;x++){
                            res = res+att[x].NameOnWeb+"<br>";
                        }
                        $("#ifedit").html(res);
                        $("#memoid").val(data.memo.IDMemo);
                        $("#memodate").val(data.memo.MemoDate);
                        editor.setValue(data.memo.MemoText);
                        if (data.memo.CC == "1"){
                            $("#ccmemo").prop("checked", true);
                        }else{
                            $("#ccmemo").prop("checked", false);                            
                        }
                        $("#memosubject").val(data.memo.MemoSubject);
                        $("#memotoid").val(data.memo.ToIDUser);
                        $("#memotoname").val(data.memo.ToName);
                        $("#memotodiv").val(data.memo.ToDiv);
                        $("#memotopos").val(data.memo.ToPos);
                        
                    }
                });                
            }
            function delmemo(idmemo){
                bootbox.confirm("You are going to delete your memo. Continue?", function (r){
                    if (r == true){
                        $.ajax({
                            url     : ROOT.base_url+"mod_empcenter/index.php/trx03/home/delete_memo",
                            data    : "idmemo="+idmemo,
                            type    : "post",
                            dataType: "json",
                            cache   : false,
                            success : function (data){
                                bootbox.alert("Memo Deleted!", function (){
                                    bootbox.hideAll();
                                    reloadpage();
                                });
                            }
                        });    
                    }else{
                        $.gritter.add({
                            title: 'Memo is not deleted!',
                            text: "You canceled the action"
                        });
                    }
                })
            }
            function cancelact(){
//                $(".msub").show({});
                $("button[btn='wider']").show({});
                var active = $("#btnnew").attr("wactive");
                $("."+active).show({});
                $("tr.selected").removeClass("selected");
                $("tr").addClass("selectable");
                $("#formadd").hide({
                    easing : "slide",
                    duration: 500,
                    complete: function (){
                        $(".c").css("margin-left","0px");
                        $("div.c").removeClass("span6");
                        $("div.c").addClass("span12");
                        $("#formadd").removeClass("span6");
                    }
                });                     
                $("#formread").hide({
                    easing : "slide",
                    duration: 500,
                    complete: function (){
                        $(".c").css("margin-left","0px");
                        $("div.c").removeClass("span6");
                        $("div.c").addClass("span12");
                        $("#formread").removeClass("span6");
                    }
                });                     
            }
            function validasi(){
                var text        = $("#memotext").val();
                var subject     = $("#memosubject").val();
                var to          = $("#memotoname").val();
                var count = 0;
                
                if (to != ''){
                    count = count+1;
                }
                else{
                    $.gritter.add({
                        title: 'Memo Recipient Required!',
                        text: "Please specify who will receive your memo"
                    }); 
                }
                if ( subject != ''){
                    count = count+1;
                }
                else{
                    $.gritter.add({
                        title: 'Memo Subject Required!',
                        text: "Please specify the subject of your memo"
                    });
                }
                if (text != ''){
                    count = count+1;
                }
                else{
                    $.gritter.add({
                        title: 'Memo Text Required!',
                        text: "Please fill out your memo content"
                    });
                }
//                alert(count);
                if (count == 3){
                    return true;
                }
                else{
                    return false;
                }
            }
            function send_memo(){
                var memoid      = $("#memoid").val();
                var memodate    = $("#memodate").val();
                var text        = $("#memotext").val();
                var subject     = $("#memosubject").val();
                var to          = $("#memotoid").val();
                var proses      = $("#proses").val();
                var ccmemo      = $("#ccmemo").prop("checked");
                if (ccmemo == true){
                    ccmemo = $("#ccmemo").val();
                }else{
                    ccmemo = "0";
                }
                var valid = validasi();
//                alert(valid)
                if (valid == true){
    //                alert(ccmemo);
                    loading();
                    $.ajax({
                        url     : ROOT.base_url+"mod_empcenter/index.php/trx03/home/"+proses,
                        data    : "memoid="+memoid+"&memodate="+memodate+"&text="+text+"&subject="+subject+"&to="+to+"&ccmemo="+ccmemo,
                        type    : "post",
                        dataType: "json",
                        cache   : false,
                        success : function (data){
//                            alert(data);
//                            alert(data.s);
                            //if (uploadmulti(data.idmemo) == true){//untuk upload
                                bootbox.alert("Memo Sent!", function (){
                                    bootbox.hideAll();
                                    reloadpage();
                                });                            
                            //}
                        }
                    });                    
                }
            }
            $('[data-toggle="popover"]').popover({
                html :true
            });
            $('[data-toggle="tooltip"]').tooltip();
            function viewfolup(idmemo,v){
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx03/home/get_folup",
                    data    : "idmemo="+idmemo+"&v="+v,
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
//                        alert(data.toSource());
                        var res = '';
                        var jml = data.length;
                        var no  = jml+1;
                        for(var i=0;i<jml;i++){
                            no--;
                            res = res +" <b>"+data[i].AddedDate+"</b> : <br>"+data[i].FolUpdate+"<br>";
                        }
                        $("span#folup"+idmemo).attr('data-content',res);
                    }
                });
            }
            function click_btn_view(idmemo){
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx03/home/upd_feed",
                    data    : "idmemo="+idmemo,
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
                        $("span#folup"+idmemo).removeClass("btn-danger");
                        $("span#folup"+idmemo).addClass("btn-inverse");
                        $("span#folup"+idmemo).text("view");
                    }
                });
            }
            function updfol(idmemo){
//                alert(idmemo);
                var form = "Send feedback: <br>";
                var form = form+"<textarea id='textfeed"+idmemo+"' style='resize:none'></textarea>";
                var form = form+"<button onclick='sendfol(\""+idmemo+"\")' class='btn btn-mini btn-success glyphicons share'><i></i>send</button>";
                $("#crtfol"+idmemo).attr('data-content',form);
                
            }
            function sendfol(idmemo){
                var text    = $("#textfeed"+idmemo).val();
//                ajax send
                loading();
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx03/home/send_feed",
                    data    : "text="+text+"&idmemo="+idmemo,
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
                        bootbox.hideAll();
                        $.gritter.add({
                            title: 'Feedback Sent!',
                            text: "Click view feeds to view all your feedback"
                        }); 
                    }
                });
//                hapus textarea /kosongkan
//                alert(text);
                $("#textfeed"+idmemo).val("");
                $('[data-toggle="popover"]').popover('hide');
            }
            function readmemo(idmemo,from){
                $("button[btn='print']").attr("idmemo",idmemo);
                $("button[btn='smaller']").hide();
                $("tr.selected").removeClass("selected");
                $("tr").addClass("selectable");
                $("tr."+idmemo).removeClass("selectable");
                $("tr."+idmemo).addClass("selected");
                
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx03/home/get_memo",
                    data    : "idmemo="+idmemo+"&from="+from,
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
                        if (data.memo.CC == '1'){
                            $("#ccnya").text(data.memo.ToDiv);
                        }else{
                            $("#ccnya").text('');
                        }
                        $("span.label."+idmemo).remove();
			//$("button#folup"+idmemo).prop("disabled",false);
			$("button#crtfol"+idmemo).prop("disabled",false);

                        var active = $("#btnnew").attr("wactive");
                        $("."+active).removeAttr("style");
                        $("#proses").val("send_memo");
                        $("div."+active).removeClass("span12");
                        $("div."+active).addClass("span6");
                        $("#formread").addClass("span6");
                        $("#formread").show({
                            easing : "slide",
                            duration: 500,
                            complete: function (){
        //                        $("#memotoname").focus();
                            }
                        });                          
                    }
                });              
            }
            function fullwidth(form){
                $("#tfrom").removeClass("span12");
                $("#tfrom").addClass("span6");
                $("#tto").removeClass("span12");
                $("#tto").addClass("span6");
                var active = $("#btnnew").attr("wactive");
                $("."+active).hide({
                    easing  : "slide",
                    duration: 500,
                    complete: function (){
                        $("button[btn='wider']").hide({});
                        $("button[btn='smaller']").show({});
                        $("#"+form).removeClass("span6");
                        $("#"+form).addClass("span12");                        
                    }
                });                
            }
            function halfwidth(form){
                $("#tfrom").removeClass("span6");
                $("#tfrom").addClass("span12");
                $("#tto").removeClass("span6");
                $("#tto").addClass("span12");
                $("#"+form).removeClass("span12");
                $("#"+form).addClass("span6"); 
                var active = $("#btnnew").attr("wactive");
                $("."+active).show({
                    easing  : "slide",
                    duration: 500,
                    complete: function (){
                        $("button[btn='smaller']").hide({});                       
                        $("button[btn='wider']").show({});                       
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
            function uploadmulti(idmemo){
//                alert(f.files[0]);
                var jmlfile = 0;
                var isi = '';
                var form    = $('#attachment').find('form');
                var all     = form.length;
                form.each(function(){
                    jmlfile++;
                    var formdata= new FormData($(this)[0]);
                    $.ajax({
                        url     : ROOT.base_url+"mod_empcenter/index.php/trx03/home/upload/"+idmemo+"/"+jmlfile+"/"+all,
                        type    : "post",
                        xhr     : function (){
                            var myxhr = $.ajaxSettings.xhr();
                            myxhr.upload;
                            return myxhr;
                        },
                        //beforeSend  : beforeSendHandler,
                        dataType    : "json",
                        success     : function (data){
    //                        $('progress#progressbar').attr('value','100');
                            //alert(data);
                            if (data.valid == "false"){
//                                alert(data.fattach+"|file ke "+data.fileke+"|dari "+data.of);
                                alert("an error occured when uploading");
                            }
                            if (data.valid == "true"){
//                                alert(data.fattach+"|file ke "+data.fileke+"|dari "+data.of);
//                                $('#prog'+formnya).text('100%');
                            }
                        },
                        error       : function (a,b){
                            alert(a.toSource()+"\n|"+b);
                        },
                        data        : formdata,
                        cache       : false,
                        contentType : false,
                        processData : false
                    });                    
                });
//                alert(form.length);

                return true;
            }
            function uploadatt(formnya){
       
//                alert(f.files[0]);
                var formdata = new FormData($('form[name="formke'+formnya+'"]')[0]);
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx03/home/upload",
                    type    : "post",
                    xhr     : function (){
                        var myxhr = $.ajaxSettings.xhr();
                        if (myxhr.upload){
                            //myxhr.upload.onprogress = progressbar();
                            myxhr.upload.addEventListener('progress', function (event){
                                var curr;
                                var max = (event.total/event.total)*100;
                                var cur = (event.loaded/event.total)*100;
//                                var text = Math.ceil((event.total/event.loaded)*100);
                                curr = cur.toString();
                                $('#prog'+formnya).text(curr.substring(0,4)+"%");
                                if (event.lengthComputable){
//                                    $('progress#progressbar').attr('max',max);
//                                    $('progress#progressbar').attr('value',cur);
                                }
                                else{
                                    $('#prog'+formnya).text('size unknown');
                                }
                            }, false);
//                            alert("sampe sini");
                        }
                        return myxhr;
                    },
                    //beforeSend  : beforeSendHandler,
                    dataType    : "json",
                    success     : function (data){
//                        $('progress#progressbar').attr('value','100');
                        //alert(data);
                        if (data.valid == "false"){
                            alert(data.ftype);
                            alert("an error occured when uploading");
                        }
                        if (data.valid == "true"){
                            alert(data.ftype);
                            $('#prog'+formnya).text('100%');
                        }
                    },
                    error       : function (a,b){
                        alert(a.toSource()+"\n|"+b);
                    },
                    data        : formdata,
                    cache       : false,
                    contentType : false,
                    processData : false
                });
                
            }
            var ke = 1;
            function morefiles(){
                ke++;
                var ap = '';
                ap  =ap+'<form enctype="multipart/form-data" name="formke'+ke+'">';
                ap  =ap+'<input type="hidden" name="fileke" value="'+ke+'">';
                ap  =ap+'<input class="span6 inputfile" type="file" name="file" onchange="uploadatt('+ke+')"  >';
                //ap  =ap+'<progress id="progressbar"></progress>';
                ap  =ap+'<span id="prog'+ke+'"></span><span onclick="delfile(\''+ke+'\')" class="btn btn-mini btn-danger">x</span>';
                ap  =ap+'</form><br>';
                
                $("#attachment").append(ap);
            }
            function morefile2(){
                var form = '';
                form    = form+"<form enctype='multipart/form-data'>";
                form    = form+"<input class='span6' type='file' name='file'>";
                form    = form+"</form><br>";
                $('#attachment').append(form);
            }
            function delfile(fileke){
                var proses = $("#proses").val();
                var idmemo = '';
                if (proses == "upd_memo"){
                    idmemo = $("#memoid").val();
                }
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx03/home/del_upfile",
                    data    : "fileke="+fileke+"&idmemo="+idmemo,
                    type    : "post",
//                    dataType: "json",
//                    cache   : false,
                    success : function (data){
                        alert(data);
                        var jmlupl = $('.inputfile').length;
                        if (jmlupl == 1){
                            $('form[name="formke'+fileke+'"]').find('input[type="file"]').val('');
                        }
                        if (jmlupl > 1){
                            $("form[name='formke"+fileke+"']").remove();
                        }
                        $('#prog'+fileke).text('');
                    },
                    error   : function (a,b){
                        alert(a.toSource+"\n"+b);
                    }
                });
            }
        </script>
    </head>
    <body>
        <div class="widget">
            <div class="widget-head">
                <div class="row-fluid">
                    <div class="span6">
                        <input type="hidden" id="proses">
                        <h4 class="heading">Memo</h4>
                    </div>
                    <div class="span6" style="text-align: right;">
                        <button onclick="reloadpage()" class="btn btn-small btn-default btn-icon"><i class="icon-refresh"></i></button>
                        <button onclick="backtohome()" class="btn btn-success btn-small btn-icon glyphicons home"><i></i>Back to Home</button>
                    </div>
                </div>                
            </div>
            <div class="widget-body">
                <div class="row-fluid">
                    <div class="span6">
                        <h4>Memo</h4>
                    </div>
                    <div class="span6" style="text-align: right;">
                        <button onclick="newmemo()" id="btnnew" wactive="inmemo" class="btn btn-primary btn-small btn-icon glyphicons circle_plus"><i></i>Create</button>

                    </div>
                </div>
                <hr class="separator">
                <div class="tabsbar tabsbar-2 active-fill">
                    <ul class="row-fluid ">
                        <li class="span6 tab1 tabatas glyphicons message_in active inmemoic">
                            <a onclick="inmemo()" data-toggle="tab"><i></i>
                                <b>Incoming Memo</b>
                                <!--<div class="ribbon-wrapper small ribtab1"><h4 style="color: #be362f; text-shadow: 1px 1px 1px #000;">&ast;</h4></div>-->
                            </a>
                        </li>
                        <li class="span6 tab2 tabatas glyphicons envelope outmemoic" >
                            <a onclick="outmemo()" data-toggle="tab"><i></i> 
                                <b>Outgoing Memo</b>
                                <!--<div class="ribbon-wrapper small ribtab2"><h4 style="color: #be362f; text-shadow: 1px 1px 1px #000;">&ast;</h4></div>-->
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="row-fluid ">
                     <!--+++++++++++++++++++++++++++  form read +++++++++++++++++++++++++++--> 
                    <div class="widget widget-body" style="background-color: #ddd; color: #000;" id="formread">
                        <div class="row-fluid">
                            <div class="span12">
                                <table border="0" width="100%">
                                    <tr>
                                        <td ><b>Memo No</b> / <i class="transindo">Nomor Memo</i>  </td>
                                        <td>:</td>
                                        <td width="20px"> <span id="ridmemo"></span></td>
                                        <td colspan="4" align="right">
                                            <button onclick="printmemo()" btn="print" idmemo="" title="print" data-toggle="tooltip" data-original-title="print" data-placement="top" class="red-tooltip btn btn-mini btn-success"><i class="icon-print"></i></button>
                                            <button onclick="halfwidth('formread')" btn="smaller" title="smaller" data-toggle="tooltip" data-original-title="smaller" data-placement="top" class="red-tooltip btn btn-mini btn-primary"><-</button>
                                            <button onclick="fullwidth('formread')" btn="wider" title="wider" data-toggle="tooltip" data-original-title="wider" data-placement="top" class="red-tooltip btn btn-mini btn-primary">-></button>
                                            <button onclick="cancelact()" title="close" data-toggle="tooltip" data-original-title="close" data-placement="top" class="red-tooltip btn btn-mini btn-danger">x</button>
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
                                                <div class="span12" id="tto" style="margin-left: 0px;">
                                                    <table class="widget-body">
                                                        <tr>
                                                            <td><u><b>CC</b> / <i class="transindo">CC</i></u></td>
                                                            <td>:</td>
                                                            <td><span id="ccnya"></span></td>
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
                    <!--========================form add========================-->
                    <div class=" widget widget-body" id="formadd">
                        <div class="row-fluid">
                            <div class="span4">
                                <h5>Memo Form</h5>
                            </div>
                            <div class="span8" style="text-align: right;">
                                <button  onclick="fullwidth('formadd')" btn="wider" class="btn btn-small btn-icon btn-primary glyphicons right_arrow"><i></i>Full</button>
                                <button style="display: none;" onclick="halfwidth('formadd')" btn="smaller" class="btn btn-small btn-icon btn-primary glyphicons left_arrow"><i></i>Half</button>
                                <button  onclick="cancelact()" class="btn btn-small btn-icon btn-danger glyphicons circle_minus"><i></i>Cancel</button>
                            </div>
                        </div>
                        <hr class="separator">
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="control-group">
                                    <label><b>Memo ID</b> / <i class="transindo">Nomor ID Memo</i></label>
                                    <div class="controls">
                                        <input type="hidden" id="memoid">
                                        <input class="span12 " type="text" value="[auto]" disabled id="">
                                    </div>
                                </div> 
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <label><b>Memo Date</b> / <i class="transindo">Tanggal Memo</i></label>
                                    <div class="controls">
                                        <input class="span12" type="text" id="memodate" disabled value="<?php echo date("Y-m-d H:i:s");?>">
                                    </div>
                                </div>                            
                            </div>
                        </div>
                        <!--<hr>-->
                        <div class="row-fluid">
                            <div class="row-fluid">
                                <label><b>To :</b> / <i class="transindo">Untuk / Kepada :</i></label>                            
                            </div>
                            <div class="row-fluid">
                                <div class="span6">
                                    <div class="control-group">
                                        <label><b>ID Employee </b> / <i class="transindo">NIP </i></label>
                                        <div class="controls">
                                            <input class="span12" type="text" id="memotoid" value="" disabled >
                                        </div>
                                    </div>                            
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label><b>Name </b> / <i class="transindo">Nama</i></label>
                                        <div class="controls">
                                            <input class="span12" type="text" id="memotoname" >
                                        </div>
                                    </div>                            
                                </div>                            
                            </div>
                            <div class="row-fluid">
                                <div class="span6">
                                    <div class="control-group">
                                        <label><b>Department </b> / <i class="transindo">Departemen / Divisi </i></label>
                                        <div class="controls">
                                            <input class="span12" type="text" id="memotodiv" value="" disabled >
                                        </div>
                                    </div>                            
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label><b>Position </b> / <i class="transindo">Posisi</i></label>
                                        <div class="controls">
                                            <input class="span12" type="text" id="memotopos" disabled >
                                        </div>
                                    </div>                            
                                </div>                            
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="control-group">
                                    <label>CC To:</label>
                                    <div class="controls">
                                        <input type="checkbox" id='ccmemo' value='1'> CC to his/her division members
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="control-group">
                                    <label><b>Subject </b> / <i class="transindo">Judul</i></label>
                                    <div class="controls">
                                        <input class="span12" type="text" id="memosubject" >
                                    </div>
                                </div>                              
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                <textarea id="memotext" class="wysihtml5 span12" rows="5"></textarea>
                            </div>
                        </div>
<!--                        <div class="row-fluid" id="ifadd">
                            <div class="span12">
                                <div id="attachment">
                                    <form enctype="multipart/form-data">
                                        <input class="span6" type="file" name="file" >
                                    </form>
                                    <br>
                                </div>
                                <button onclick="morefile2()" class="btn btn-default btn-small glyphicons circle_plus "><i></i>more</button>
                                <button onclick="uploadmulti()" class="btn btn-default btn-small glyphicons share "><i></i>upload</button>
                            </div>
                        </div>
                        <div class="row-fluid" id="ifedit">
                            <div class="span12">
                                ini jika edit
                            </div>
                        </div>-->
                        <hr>
                        <div class="row-fluid">
                            <div class="span12" style="text-align: center;">
                                <button onclick="send_memo()" class="btn btn-icon btn-success glyphicons share"><i></i>Send</button>
                                <button onclick="cancelact()" class="btn btn-icon btn-danger glyphicons circle_minus"><i></i>Cancel</button>
                            </div>
                        </div>
                    </div>
                    <!--============================ end of form add =============================-->
                    
                    <div class="span12 widget widget-body c inmemo">
<!--                        <div class="row-fluid">
                            <div class="span12">
                                <h5>Incoming Memo</h5>
                                <hr class="separator">
                            </div>
                        </div>-->
                        <div class="row-fluid">
                            <div class="span12">
                                <table width="100%" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable dynamicTable">
                                    <thead class="btn-primary">
                                        <tr>
                                            <th >Memo Date</th>
                                            <th>From</th>
                                            <th>Subject</th>
                                            <th><center>Feedback</center></th>
<!--                                            <th>Action</th>-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        foreach($in->result() as $i){
                                            if ($i->CC == '1'){
                                                $cc = "<span class='label label-default' >CC</span>";
                                            }else{
                                                $cc = "";
                                            }
                                            $tip = 'class="span12 red-tooltip" data-toggle="tooltip" data-original-title="click to open!" data-placement="top"';
                                            if ($i->MemoStatus == "0"){
						$dis	= "disabled";
                                                $not    = "<span class='label label-important $i->IDMemo' >New!</span>";
                                            }
                                            else{
						$dis	= "";
                                                $not    = "";
                                            }
                                            $dep = $this->mmm->get_department($i->IDDepartement)->row();
                                            echo '<tr class="selectable '.$i->IDMemo.'">';
                                            echo "<td onclick='readmemo(\"$i->IDMemo\",\"in\")' ><center><span $tip>".$i->MemoDate.'</span></center></td>';
                                            echo "<td onclick='readmemo(\"$i->IDMemo\",\"in\")' ><span $tip>$not $cc $dep->DescStructure : $i->FullName</span></td>";
                                            echo "<td onclick='readmemo(\"$i->IDMemo\",\"in\")' ><span $tip>$i->MemoSubject</span></td>";
                                            
                                            echo "<td>
                                                <center>
                                            <span id='folup$i->IDMemo' onmouseover='viewfolup(\"$i->IDMemo\",\"in\")'  class=' btn btn-inverse btn-small' data-toggle='popover' data-title='Update' data-content='' data-placement='left'>
                                                View Feed(s)
                                            </span>
                                            <button $dis id='crtfol$i->IDMemo' onclick='updfol(\"$i->IDMemo\")' style='margin-left: 0px;' class=' btn btn-small btn-warning glyphicons share' data-toggle='popover' data-title='Create Feedback' data-content='' data-placement='left'>
                                            <i></i>
                                                Update
                                            </button>
                                                </center>
                                            </td>";
                                            
                                            echo '</tr>';                                            
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="span12 widget widget-body c outmemo">
<!--                        <div class="row-fluid">
                            <div class="span12">
                                <h5>Outgoing Memo</h5>
                                <hr class="separator">
                            </div>
                        </div>-->
                        <div class="row-fluid">
                            <div class="span12">
                                <table width="100%" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable dynamicTable">
                                    <thead class="btn-primary">
                                        <tr>
                                            <th>Memo Date</th>
                                            <th>To</th>
                                            <th>Subject</th>
                                            <th><center>Feedback</center></th>
                                            <th><center>Action</center></th>
<!--                                            <th>Action</th>-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        foreach($out->result() as $o){
                                            if ($o->CC == "1"){
                                                $cc = "<span class='label label-default'>CC</span>";
                                            }else{
                                                $cc = "";
                                            }
                                            
                                            $tip = 'class="span12 red-tooltip" data-toggle="tooltip" data-original-title="click to open!" data-placement="top"';
                                           // echo $o->MemoStatus;
                                           // echo $o->ConfirmFlag;
                                            if ($o->MemoStatus == "0" and $o->ConfirmFlag == "0"){
                                                $dis    = "";
                                                $not    = "<span class=' label label-warning' >waiting</span>";
                                            }
                                            if ($o->MemoStatus == "0" and $o->ConfirmFlag == "2"){
                                                $dis    = "";
                                                $not    = "<span class=' label label-important' data-toggle='popover' data-title='Reason of Rejection' data-content='$o->RejectReason' data-placement='left'  >rejected</span>";                                                
                                            }
                                            if ($o->MemoStatus == "0" and $o->ConfirmFlag == "1"){
                                                $dis    = "disabled";
                                                $not    = "<span class=' label label-default' >delivered</span>";
                                            }
                                            if ($o->MemoStatus == "1" and $o->ConfirmFlag == "1"){
                                                $dis    = "disabled";
                                                $not    = "<span class=' label label-success' >opened</span>";
                                            }
                                            $wh  = array("IDMemo" => $o->IDMemo, "FolRead" => "0");
                                            $jmlfol = $this->mmm->get_folup($wh)->num_rows();
                                            if ($jmlfol > 0){
                                                $style  = "danger";
                                                $jmlfol = $jmlfol." new update(s)";
                                            }
                                            if ($jmlfol == 0){
                                                $style  = "inverse";
                                                $jmlfol = "";
                                            }
                                            $dep = $this->mmm->get_department($o->IDDepartement)->row();
                                            echo '<tr class="selectable '.$o->IDMemo.'">';
                                            echo "<td onclick='readmemo(\"$o->IDMemo\",\"out\")' ><center><span $tip>$o->MemoDate </span></center></td>";
                                            echo "<td ><span  $tip> $not $cc <a onclick='readmemo(\"$o->IDMemo\",\"out\")' > $dep->DescStructure : $o->FullName</a></span></td>";
                                            echo "<td onclick='readmemo(\"$o->IDMemo\",\"out\")' ><span $tip>$o->MemoSubject</span></td>";
                                            
                                            echo "<td >
                                                <center>
                                            <span id='folup$o->IDMemo' onclick='click_btn_view(\"$o->IDMemo\")' onmouseover='viewfolup(\"$o->IDMemo\",\"out\")' class='btn btn-$style btn-small' data-toggle='popover' data-title='Update' data-content='' data-placement='left'>
                                                View $jmlfol
                                            </span>
                                                </center>
                                            </td>";
                                            echo "<td><center>";
                                            echo "<button onclick='editmemo(\"$o->IDMemo\")' title='edit memo' class='btn btn-mini btn-warning' $dis><i class='icon-pencil'></i></button>";
                                            echo "<button onclick='delmemo(\"$o->IDMemo\")' title='delete memo' class='btn btn-mini btn-danger' $dis><i class='icon-trash'></i></button>";
                                            echo "</center></td>";
                                            echo '</tr>';
                                        }
                                        ?>        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>

