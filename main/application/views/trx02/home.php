<!DOCTYPE html PUBLIC"-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <!-- Meta -->
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta content="utf-8" http-equiv="encoding" />

        <?php $base_url = $this->session->userdata('sess_base_url'); ?>
	
        <!-- JQueryUI -->
       
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" />
        <!--TreeView-->
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/treeview/treeview.css" rel="stylesheet" />
        <!-- hide the close link in the toolbar -->

        <style type="text/css">
            a.ui-dialog-titlebar-close {
                display:none
            }
            .okiscroll{
                overflow-y: scroll;
                border: solid #545454 thin;                
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
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
        <!-- TreeView-->
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/treeview/treeview.js"></script>
        <!--<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/treeview/jquery.cookie.js"></script>-->

        <script type="text/javascript" charset="utf-8">
            $(document).ready(function (){
                $(".pview").attr("onedit","no");
                $(".pview").hide();
                $("#isedit").val("no");
                $(".okiscroll").hide();
                $(".wmore").hide();
                $(".tuser").show();
                $(".tbutton").hide();
                $('#selbtn').find("option").click(false);
                $('option.selecteduser').click(function (){
                    $(this).each(function (){
                        $(this).prop("selected",true);
                    });
                });
                cek();
//                $("#selbtn").keydown(false);
            });

            var ROOT = {
                'site_url': '<?php echo $base_url . 'index.php'; ?>',
                'base_url': '<?php echo $base_url; ?>'
            };
            function cek(){
                
                <?php 
                foreach ($buttons->result() as $btn){
                    if ($btn->access == "0"){
                        echo "$(\"button[idbtn='$btn->kdbutton']\").prop('disabled',true);";
                    }
                    if ($btn->access == "1"){
                        echo "$(\"button[idbtn='$btn->kdbutton']\").prop('disabled',false);";
                    }
                }
                ?>
            }
            function more_opt(){                
                $(".userm").slideUp();
                $(".wmore").effect("slide", "slow").show();
            }
            function reloadpage(){
                var content = $("#content .innerLR");
                var url = ROOT.base_url + 'main/index.php/trx02/home/home';
                //alert(url);
                content.fadeOut("slow", "linear");
                content.load(url);
                content.fadeIn("slow");
            }
            function loading(){
                bootbox.alertloading("<center>Processing. Please wait !!!<br><img src='<?php echo $base_url; ?>public/avatar/76.GIF'></center>");
            }
            function load_detail(iduser,name,rel="",from){

                var onedit = $(".pview").attr("onedit");
//                alert(onedit);
                if ((onedit == "no") || (onedit == "yes" && from != "list") ){                
                    var disab;
                    if (rel == ""){
                        disab = "disabled";
                    }else{
                        disab ="";
                        $(".pview").attr("onedit","yes");
                    }
    //                alert(disab);
                    $(".okiscroll").show();
                    $(".usernya").text(name);
                    $(".usernya2").text(name);
                    $("#iduser").val(iduser);
                    $("tr.ulist").removeClass("selected");
                    $("tr.ulist").addClass("selectable");
                    $("tr.ulist."+iduser).removeClass("selectable");
                    $("tr.ulist."+iduser).addClass("selected");

                    $.ajax({
                        url     : ROOT.base_url +'main/index.php/trx02/home/detail',
                        data    : "iduser="+iduser,
                        type    : "post",
                        dataType: "json",
                        cache   : false,
                        success : function (isi){
//                            alert("harusnya keluar ");
                            var sub;
                            $("#preview").empty();                        
                            var res = '';
                            for (var i = 0; i < isi.menu.length; i++){
                                if (isi.menu[i].level == "1"){ sub = ""} if (isi.menu[i].level == "2"){ sub = "&gt;&gt;"}if (isi.menu[i].level == "3"){ sub = "&gt;&gt;&gt;&gt;"}
                                if (isi.menu[i].access == '0'){
                                var access = "<option value='0' selected>No</option><option value='1'>Yes</option>";                                
                                }else{
                                var access = "<option value='0'>No</option><option value='1' selected>Yes</option>";  
                                }
                                res = res + '<tr class="selectable mnlist '+isi.menu[i].idmenu+'"><td onclick="btns(\''+isi.menu[i].idmenu+'\',\''+iduser+'\',\''+isi.menu[i].menu+'\',\''+name+'\')">' +sub+"&nbsp;"+ isi.menu[i].menu +'</td><td class="akses"><center><select mnlevel="'+isi.menu[i].level+'" id="'+isi.menu[i].idmenu+'" menuteks="'+isi.menu[i].menu+'" idparent="'+isi.menu[i].idparent+'" onchange="bsave(this.id,this.value,\''+isi.menu[i].menu+'\')" class="span8 usermenu" '+disab+' >' + access + '</select></center></td></tr>';
                            }
                            $("#userrole").find("option[value='"+isi.role.Role+"']").prop("selected",true);
                            $("#preview").append(res);
                            var isedit = $("#isedit").val();
                            var userrole    = $("#userrole").val();
                            if (userrole == "0"){
                                $("#urole").text("Common User");
                            }
                            if (userrole == "1"){
                                $("#urole").text("Module Admin");
                            }
                            if (userrole == "2"){
                                $("#urole").text("Super Admin");
                            }
    //                        alert(userrole);
                            if (isedit != "no"){
                                if (userrole == "0"){
                                    $("select.usermenu[mnlevel='1']").prop("disabled",false);
                                    $("select.usermenu[mnlevel='2']").prop("disabled",false);
                                    $("select.usermenu[mnlevel='3']").prop("disabled",false);                     
                                }
                                else if (userrole == "1"){
                                    $("select.usermenu[mnlevel='1']").prop("disabled",false);
                                    $("select.usermenu[mnlevel='2']").prop("disabled",true);
                                    $("select.usermenu[mnlevel='3']").prop("disabled",true); 
                                }
                                else if (userrole == "2"){
                                    $("select.usermenu").prop("disabled",true);
                                    $("select.usermenu").each(function(){
                                        $(this).find('option[value="1"]').prop("selected",true);
                                    });
                                }
                            }
    //                        alert(isedit);
                        }
                    });    
                }
                
            }
            function bsave(idmenu,val,nmmenu){
                var set = $("#userrole").val();
                var sel = $("select.usermenu[id='"+idmenu+"']").val();
                if (set == "1"){
                    save(idmenu,val,nmmenu,set);
                    if (sel == "1"){
                        $("select.usermenu[idparent='"+idmenu+"']").each(function (){
                            $(this).find("option[value='1']").prop("selected",true);
                            $(this).find("option[value='0']").prop("selected",false);
                            var tidmenu = $(this).attr("id");
                            bsave(tidmenu,val,nmmenu);
                        });

                    }else {
                        $("select.usermenu[idparent='"+idmenu+"']").each(function (){
                            $(this).find("option[value='0']").prop("selected",true);
                            $(this).find("option[value='1']").prop("selected",false);
                            var tidmenu = $(this).attr("id");
                            bsave(tidmenu,val,nmmenu);
                        });
                    }
                }
                else if (set == "0"){
                    save(idmenu,val,nmmenu,set);
                    if (sel == "1"){
                        var thisparent = $("select.forall[id='"+idmenu+"']").attr("idparent");
                        $("select.forall[idmenu='"+thisparent+"']").each(function (){
                            $(this).find("option[value='1']").prop("selected",true);
                            $(this).find("option[value='0']").prop("selected",false);
                            var tidmenu = $(this).attr("id");
                            var tval    = $(this).val();
                            var tmnmenu = $(this).attr("menuteks");
                            save(tidmenu,val,nmmenu,set);
                            if (tidmenu != "0"){
                                bsave(tidmenu,val,nmmenu);
                            }
                        });
                    }
                    else{
                        $("select.forall[idparent='"+idmenu+"']").each(function (){
                            $(this).find("option[value='0']").prop("selected",true);
                            $(this).find("option[value='1']").prop("selected",false);
                            var tidmenu = $(this).attr("id");
                            var tidmenu = $(this).attr("id");
                            var tval    = $(this).val();
                            var tmnmenu = $(this).attr("menuteks");
                            save(tidmenu,val,nmmenu,set);
                            bsave(tidmenu,val,nmmenu);
                           
                        });     
                    }
                } 

            }
            function save(idmenu,val,nmmenu,set){
//                var role = $("#userrole").val();
                var rel;
                var iduser  = $("#iduser").val();
                var nmuser  = $(".usernya").text();
                var teks;
                if (idmenu == "all"){
                    rel = "";
                    nmmenu = "all the";
                }
                else{
                    rel = "no";
                }
                val == "0" ? teks = "can not" : teks = "can";
                loading();
//                alert(iduser+"|"+idmenu+"|"+val);
                $.ajax({
                    url     : ROOT.base_url +'main/index.php/trx02/home/chg_acc',
                    data    : "iduser="+iduser+"&idmenu="+idmenu+"&access="+val+"&set="+set,
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
                        load_detail(iduser,nmuser,rel);
//                        userrole(role,"no");
                        $(".pview").show();
                        bootbox.hideAll();
                        $.gritter.add({
                            title: 'Access Changed!',
                            text: nmuser+" now "+teks+" access "+nmmenu+" menu " + data.msg
                        });                        
                    }
                });
            }
            function edit(iduser,name){
                //
                $("input[aria-controls='DataTables_Table_0']").prop("disabled",true);
                $("select[aria-controls='DataTables_Table_0']").prop("disabled",true);
                $(".pagination").hide();//
                $(".tabeluser").hide();//
                //
                $("button.more").prop("disabled",true);
                $("button[idbtn='edit']").prop("disabled",true);
                $("button[editfor='"+iduser+"']").prop("disabled",false);
                $("#isedit").val("yes");
//                alert($(".pview").attr("onedit"));
                load_detail(iduser,name,"no","edit");
                $(".akses").find("select").prop("disabled",false);                
                $(".pview").show();
            }
            function ok(){
                //
                $("input[aria-controls='DataTables_Table_0']").prop("disabled",false);
                $("select[aria-controls='DataTables_Table_0']").prop("disabled",false);
                $(".pagination").show();//
                $(".tabeluser").show();//
                //
                $("button.more").prop("disabled",false);
                $("button[idbtn='edit']").prop("disabled",false);
                $("select#userrole").find("option").prop("selected",false);
                $("select#userrole").find("option[value='0']").prop("selected",true);
                $("#isedit").val("no");
                $(".pview").attr("onedit","no");                
                $(".pview").hide();
                $(".akses").find("select").prop("disabled",true);
            }
            function userrole(val,fchg){
                if (val == "2"){
                    $("#urole").text("Super Admin");
                    $("select.usermenu").prop("disabled",true);
                    $("select.usermenu").each(function (i){
                        $(this).find('option[value="1"]').prop("selected",true);
                        $(this).find('option[value="0"]').prop("selected",false);
                    });

                        save("","1","",val);
//                    alert("changed yes");
                }
                else if (val == "1") {
                    $("select.usermenu[mnlevel='1']").prop("disabled",false);
                    $("select.usermenu[mnlevel='2']").prop("disabled",true);
                    $("select.usermenu[mnlevel='3']").prop("disabled",true);
                    $("#urole").text("Module Admin");
                }
                else if (val == "0"){
                    $("select.usermenu").prop("disabled",false);
                    $("select.usermenu").each(function (i){
                        $(this).find('option[value="1"]').prop("selected",false);
                        $(this).find('option[value="0"]').prop("selected",true);
                    });
                    $("#urole").text("Common User");
                }
                var iduser  = $("#iduser").val();
                if (val == '0'){
                    loading();
                }
                $.ajax({
                    url     : ROOT.base_url +'main/index.php/trx02/home/chg_role',
                    data    : "iduser="+iduser+"&role="+val,
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
                        if (val == '0'){
                            bootbox.hideAll();
                        }
                    }
                });
            }
            function apall(val){
                $("optgroup.p").remove();
                var nmuser  = $(".usernya").text();
                bootbox.confirm("It will change all "+nmuser+"'s access. Continue?",
                    function (res){
                        if (res == true){
                            save("all",val,"all the ");                                                              
                        }
                        else{
//                            alert("canceled");
                        }
                    }
                );
            }
            function apallMass(val){
                $("optgroup.pall").remove();
                if (val == "2"){
                    $("select.forall").prop("disabled",true);
                    $("select.forall").each(function (i){
                        $(this).find('option[value="1"]').prop("selected",true);
                        $(this).find('option[value="0"]').prop("selected",false);
                    });
//                    alert("changed yes");
                }
                else if (val == "1") {
                    $("select.forall[mnlevel='1']").prop("disabled",false);
                    $("select.forall[mnlevel='2']").prop("disabled",true);
                    $("select.forall[mnlevel='3']").prop("disabled",true);                    
                    $("select.forall").each(function (i){
                        $(this).find('option[value="1"]').prop("selected",false);
                        $(this).find('option[value="0"]').prop("selected",true);
                    });
                }
                else if (val == "0"){
                    $("select.forall").prop("disabled",false);
                    $("select.forall").each(function (i){
                        $(this).find('option[value="1"]').prop("selected",false);
                        $(this).find('option[value="0"]').prop("selected",true);
                    });
//                    alert("changed no");
                }
            }
            function chgmassmenu(idmenu){
                var set = $("#apallMass").val();
                var sel = $("select.forall[idmenu='"+idmenu+"']").val();
                if (set == "1"){                    
                    if (sel == "1"){
                        $("select.forall[idparent='"+idmenu+"']").each(function (){
                           $(this).find("option[value='1']").prop("selected",true);
                           $(this).find("option[value='0']").prop("selected",false);
                           var tidmenu = $(this).attr("idmenu");
                           chgmassmenu(tidmenu);
                        });

                    }else {
                        $("select.forall[idparent='"+idmenu+"']").each(function (){
                           $(this).find("option[value='0']").prop("selected",true);
                           $(this).find("option[value='1']").prop("selected",false);
                           var tidmenu = $(this).attr("idmenu");
                           chgmassmenu(tidmenu);
                        });
                    }
                }
                else if (set == "0"){
                    if (sel == "1"){
                        var thisparent = $("select.forall[idmenu='"+idmenu+"']").attr("idparent");
                        $("select.forall[idmenu='"+thisparent+"']").each(function (){
                            $(this).find("option[value='1']").prop("selected",true);
                            $(this).find("option[value='0']").prop("selected",false);
                            var tidmenu = $(this).attr("idmenu");
                            if (tidmenu != "0"){
                                chgmassmenu(tidmenu);
                            }
                        });
                    }
                    else{
                        $("select.forall[idparent='"+idmenu+"']").each(function (){
                           $(this).find("option[value='0']").prop("selected",true);
                           $(this).find("option[value='1']").prop("selected",false);
                           var tidmenu = $(this).attr("idmenu");
                           chgmassmenu(tidmenu);
                           
                        });     
                    }
                }
            }
            function btuser(){
                $(".accmnu").removeClass("span7");
                $(".accmnu").addClass("span5");
                $(".tbutton").slideUp("slow");
                $(".tuser").slideDown("slow");
            }
            function btns(idmenu,iduser,nmmenu,nmuser){
                var isedit = $("#isedit").val();
//                alert(isedit);
                if (isedit == "yes"){
                    $("tr.mnlist").removeClass("selected");
                    $("tr.mnlist").addClass("selectable");
                    $("tr.mnlist."+idmenu).removeClass("selectable");
                    $("tr.mnlist."+idmenu).addClass("selected");
                    $(".accmnu").removeClass("span5");
                    $(".accmnu").addClass("span7");
                    $(".tuser").hide();
                    $(".tbutton").show();
                    $("#mnuteks").text(nmmenu);
                    $("#userteks").text(nmuser);
                    $.ajax({
                        url     : ROOT.base_url +'main/index.php/trx02/home/detail_button',
                        data    : "iduser="+iduser+"&idmenu="+idmenu,
                        type    : "post",
                        dataType: "json",
                        cache   : false,
                        success : function (data){
                            $("#accbtns").empty();
                            var rec="";
                            for(var i =0; i < data.length;i++){
                                if (data[i].access == '0'){
                                    var access = "<option value='0' selected>No</option><option value='1'>Yes</option>";                                
                                }else{
                                    var access = "<option value='0'>No</option><option value='1' selected>Yes</option>";  
                                }
                                rec = rec + '<tr class="selectable"><td>'+data[i].btndesc+'</td><td><select id="'+data[i].idbutton+'" onchange="savebtn(\''+iduser+'\',\''+data[i].idmenu+'\',this.id,this.value)">'+access+'</select></td></tr>';
                            }
//                            alert(data);
                            $("#accbtns").append(rec);
                        }
                    });
                }
                else{}
            }
            function savebtn(iduser,idmenu,idbutton,val){
//                alert(iduser+"|"+idmenu+"|"+idbutton+"|"+val);
                var teks;
                if (val == '0'){ teks = "can not"}else if(val == '1'){ teks = "can"}
                $.ajax({
                    url     : ROOT.base_url +'main/index.php/trx02/home/chg_accbtn',
                    data    : "iduser="+iduser+"&idmenu="+idmenu+"&idbutton="+idbutton+"&access="+val,
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
                        $.gritter.add({
                            title: 'Access Changed!',
                            text: data.nmuser+" now "+teks+" access "+data.nmbutton+" button " + data.msg
                        });                           
                    }
                });
            }
            function more(){
                $("div.userm").slideUp();
                $(".okiscroll").show();
                $("div.wmore").slideDown();
                $("div#alluser").hide();
                chgtab("mac");
            }
            function chgtab(id){
                if (id == "mac"){
                    $.ajax({
                        url     : ROOT.base_url +'main/index.php/trx02/home/all_menu',
                        data    : "",
                        type    : "post",
                        dataType: "json",
                        cache   : false,
                        success : function (isi){
                            var sub;
                            $("#allmenu").empty();

                            var res = '';
                            for (var i = 0; i < isi.length; i++){
                                if (isi[i].level == "1"){ sub = ""} if (isi[i].level == "2"){ sub = "&gt;&gt;"}if (isi[i].level == "3"){ sub = "&gt;&gt;&gt;&gt;"}
                                res = res + '<tr class="selectable massmnlist '+isi[i].idmenu+'"><td onclick="btnmenu('+isi[i].idmenu+')">' +sub+"&nbsp;"+ isi[i].menu +'</td><td ><center><select onchange="chgmassmenu(\''+isi[i].idmenu+'\')" mnlevel="'+isi[i].level+'" idmenu="'+isi[i].idmenu+'" idparent="'+isi[i].idparent+'" class="span8 forall" ><option value="1" >Yes</option><option value="0">No</option></select></center></td></tr>';
                            }
                            $("#allmenu").append(res);
                        }
                    });
                    chgmassbtn();
                }
            }
            var allmenu = new Array();
            function btnmenu(idmenu){
                $("tr.massmnlist").removeClass("selected");
                $("tr.massmnlist").addClass("selectable");
                $("tr.massmnlist."+idmenu).removeClass("selectable");
                $("tr.massmnlist."+idmenu).addClass("selected");
                $.ajax({
                    url     : ROOT.base_url +'main/index.php/trx02/home/button',
                    data    : "idmenu="+idmenu,
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
                        $("#menubtn").empty();

                        var res = '';
                        var btn = new Array();
                        for (var i=0;i<data.length;i++){
                            var cek = $("select#selbtn").find("option[idbtn='"+data[i].IDButton+"'][idmenu='"+data[i].IDMenu+"']").length;
                            if (cek == 0){
                                $("select#selbtn").append("<option idbtn='"+data[i].IDButton+"' idmenu='"+data[i].IDMenu+"' value='0'>"+data[i].ButtonDesc+"</option>");                                
                            }else{}
                            res = res + "<tr><td>"+data[i].ButtonDesc+'</td><td><center><select onchange="massbtnaccess(this.value,\''+data[i].IDMenu+'\',\''+data[i].IDButton+'\')" idmenu="'+data[i].IDMenu+'" idbutton="'+data[i].IDButton+'" class="span8" ><option value="x">NONE</option><option value="1" >Yes</option><option value="0">No</option></select></center></td></tr>';
                        }
                        $("#menubtn").append(res);
                    }
                });
            }
            function massbtnaccess(val,idmenu,idbutton){
                if (val == "x"){
                    $("select#selbtn").find("option[idmenu='"+idmenu+"'][idbtn='"+idbutton+"']").prop("selected",false);
                }
                else if (val == "1"){
                    $("select#selbtn").find("option[idmenu='"+idmenu+"'][idbtn='"+idbutton+"']").prop("selected",true);
                    $("select#selbtn").find("option[idmenu='"+idmenu+"'][idbtn='"+idbutton+"']").val(idmenu+"x"+idbutton+"x"+"1");
                }
                else if (val == "0"){
                    $("select#selbtn").find("option[idmenu='"+idmenu+"'][idbtn='"+idbutton+"']").prop("selected",true);
                    $("select#selbtn").find("option[idmenu='"+idmenu+"'][idbtn='"+idbutton+"']").val(idmenu+"x"+idbutton+"x"+"0");
                }
            }
            function back(){
                $("div.wmore").slideUp();
                $(".okiscroll").show();
                $("div.userm").slideDown();
                reloadpage();
            }
            function chgmassbtn(){
                var check = $("#setmassbtn").prop("checked");
                if (check == true){
                    $("#show_massbtn").hide();
                    $("div.massuser").hide();
                    $("div.massbtn").addClass("span3");                  
                    $("div.massuser").removeClass("span8");                  
                    $("div.massuser").addClass("span5");
                    $("div.massbtn").show({
                        duration: 500,
                        easing : "slide",
                        complete : function (){
                            $("div.massuser").slideDown("slow");
                        }
                        
                    });
                    
                }else{
                    $("#show_massbtn").hide();
                    $("div.massbtn").hide({
                        duration: 500,
                        easing : "blind",
                        complete : function (){
//                            $("div.massuser").effect("slide","slow");
                        }
                        }); 
                    $("div.massbtn").removeClass("span3"); 
                    $("div.massuser").removeClass("span5"); 
                    $("div.massuser").addClass("span8"); 
                }
            }
            function show_massbtn(){
                $("#show_massbtn").hide();
                $("div.massuser").hide();
                $("div.massbtn").addClass("span3");                  
                $("div.massuser").removeClass("span8");                  
                $("div.massuser").addClass("span5");
                $("div.massbtn").show({
                    duration: 500,
                    easing : "slide",
                    complete : function (){
                        $("div.massuser").slideDown("slow");
                    }
                });                
            }
            function hide_massbtn(){
                $("div.massbtn").hide({
                    duration: 500,
                    easing : "blind",
                    complete : function (){
//                            $("div.massuser").effect("slide","slow");
                        $("#show_massbtn").show();
                    }
                    }); 
                $("div.massbtn").removeClass("span3"); 
                $("div.massuser").removeClass("span5"); 
                $("div.massuser").addClass("span8"); 
            }
            function chgmassuser(val){
//                alert(val);
                if (val == "all"){
                    $("div#alluser").hide();
                }
                else if (val == "sel"){
                    $("div#alluser").show();
                    $.ajax({
                        url     : ROOT.base_url +'main/index.php/trx02/home/alluser',
                        data    : "value=alluser",
                        type    : "post",
                        dataType: "json",
                        cache   : false,
                        success : function (data){
                        $("#selalluser").empty();
                        var res = '';
                        for( var i=0;i<data.length;i++){
                            var ada = $("select#selusers").find('option[value="'+data[i].IDEmployee+'"]').length;
                            if (ada == "0"){
                                var kelas = "selectable";
                                var input = "";
                            }
                            else {
                                var kelas = "selected";
                                var input = "checked";
                            }
                            res = res+"<tr class='"+kelas+" "+data[i].IDEmployee+"'><td>"+data[i].IDEmployee+"</td><td>"+data[i].FullName+"</td><td><input "+input+" onclick='seluser(this.value,\""+data[i].FullName+"\")' type='checkbox' username='"+data[i].FullName+"' value='"+data[i].IDEmployee+"'></td></tr>";
                        }
                        $("#selalluser").append(res);
                        }
                    });
                }
            }
            function seluser(val,uname){
                var cek = $("input[value='"+val+"'][username='"+uname+"']").prop("checked");

                if (cek == true){
                    $("#selalluser").find("tr."+val).removeClass("selectable");
                    $("#selalluser").find("tr."+val).addClass("selected");
                    $("select#selusers").append("<option class='selecteduser' selected value='"+val+"'>"+uname+"</option>");
                }
                else{
                    $("#selalluser").find("tr."+val).removeClass("selected");
                    $("#selalluser").find("tr."+val).addClass("selectable");
                    $("select#selusers").find("option[value='"+val+"']").remove();
                }

            }
            function searchuser(val){
                if (val == ""){val = "alluser"}
                $.ajax({
                    url     : ROOT.base_url +'main/index.php/trx02/home/alluser',
                    data    : "value="+val+"&find=any",
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
//                        alert(data.toSource());
                        $("#selalluser").empty();
                        var res = '';
                        for( var i=0;i<data.length;i++){
                            var ada = $("select#selusers").find('option[value="'+data[i].IDEmployee+'"]').length;
                            if (ada == "0"){
                                var kelas = "selectable";
                                var input = "";
                            }
                            else {
                                var kelas = "selected";
                                var input = "checked";
                            }
                            res = res+"<tr class='"+kelas+" "+data[i].IDEmployee+"'><td>"+data[i].IDEmployee+"</td><td>"+data[i].FullName+"</td><td><input "+input+" onclick='seluser(this.value,\""+data[i].FullName+"\")' type='checkbox' username='"+data[i].FullName+"' value='"+data[i].IDEmployee+"'></td></tr>";
                        }
                        $("#selalluser").append(res);
                    }
                });                
            }
            function processmass(){
                var issetbutton = $("#setmassbtn").prop("checked");
                var isalluser     = $("input[type='radio'][name='setmassuser']:checked").val();
                var menuaccess  = new Array();
//                gather values of available menu
                $("select.forall").each(function (i){
                    var idmenu  = $(this).attr("idmenu");
                    var access  = $(this).val();
                    menuaccess.push(idmenu+"x"+access);
                });
                menuaccess.join(",");
//                cek if "set with button" set or not
                if (issetbutton == true){
                    var btnaccess   = $("#selbtn").val();
                    if (btnaccess != null){
                        btnaccess.join(",");                        
                    }else{
                        btnaccess   = "no";
                    }
                }else {
                    var btnaccess   = "no";
                }
//                cek if set apply to all user or not
                if (isalluser == "sel"){
                    var selusers    = $("#selusers").val();
                    if (selusers != null){
                        selusers.join(",");
                    }
                    else{
                        selusers    = "no user";
                    }
                }else{
                    var selusers    = "all";
                }
                if (selusers != "no user"){
                    loading();
                    alert("menus ="+menuaccess+"\nbuttons ="+btnaccess+"\nusers ="+selusers);
                    $.ajax({
                        url     : ROOT.base_url +'main/index.php/trx02/home/mass_process',
                        data    : "menus="+menuaccess+"&buttons="+btnaccess+"&users="+selusers,
                        
                        type    : "post",
                        dataType: "json",
                        cache   : false,
                        success : function (data){
                            bootbox.alert("Finished!", function (res){
                                bootbox.hideAll();
                            });
                        }
                    });
                }else {
                    alert ("no user selected!");
                }
//                alert("menus ="+menuaccess+"\nbuttons ="+btnaccess+"\nusers ="+selusers);
            }
        </script>
    </head>
    <body>
        <!--<button onclick="kosongkan()">kosong</button>-->
        <div class="widget userm">
            <div class="widget-head "><h4 class="heading">User Access</h4></div>
            <div class="widget-body">
                <div class="row-fluid">
                    <!--kiri-->
                    <div class="accmnu span5">
                        <input type="hidden" id="isedit">
                        <div class="row-fluid">
                            <div class="span7">
                                <h4 class="usernya2">User Name </h4>
                            </div>
                            <div class="span5" style="text-align: right;">
                                <h4><span id="urole"></span></h4>
                            </div>
                        </div>
                        
                        <hr class="separator">
                        <div class=" row-fluid pview widget" style="margin-top: 2%;">
                            <div class="span6" style="margin: 5%;">
                                Set <b><span class="usernya">User Name</span></b> as
                                <select id="userrole" class="span12" onchange="userrole(this.value,'yes')">
                                    <!--<option value="yn">Yes/No</option>-->
                                    <option value="0">Common User</option>
                                    <option value="1">Module Admin</option>
                                    <option value="2">Super Admin</option>
                                </select>
                            </div>
                            <div class="span4" >
                                <br><br>
                                <p align="center">
                                    <button type='button' class='btn btn-mini btn-success glyphicons circle_ok' onclick="ok('no')" ><i></i>OK</button>
                                </p>
                            </div>
                        </div> 
                        <input type="hidden" id="iduser">
                        <table width="100%">
                            <thead class="btn-primary">
                                <tr>
                                    <th>Menu </th>
                                    <th>Access</th>
                                </tr>
                            </thead>
                        </table>
                        <div class="okiscroll " style="height: 500px;">
                            <table width="100%" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable">
                                <thead style="display:none;">
                                    <tr>
                                        <th>Menu</th>
                                        <th>Access</th>
                                    </tr>
                                </thead>
                                <tbody id="preview">

                                </tbody>
                            </table>                            
                        </div>
                    </div>
                    <!--end of kiri-->
                    <!--kanan-->
                    <div class="span7 tuser">
                        <div class="row-fluid span12">
                            <div class="span6"><h4>Table of Users</h4></div>
                            <div class="span6">
                                <p align="right">
                                    <button idbtn='more' class='btn btn-mini btn-success glyphicons more' onclick="more()" ><i></i>More</button>
                                </p>
                            </div>                            
                        </div>
                        <br>
                        <hr class="separator">
                        <div class="row-fluid span12">
                            <table class=" table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable dynamicTable">
                                <thead class="btn-primary tabeluser">
                                    <tr>
                                        <th><center>User ID</center></th>
                                        <th><center>User Name</center></th>
                                        <th><center>Action</center></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach ($users->result() as $u){
                                        echo "<tr class='ulist $u->IDEmployee selectable' >";
                                        echo "<td onclick='load_detail(\"$u->IDEmployee\",\"$u->FullName\",\"\",\"list\")'>".$u->IDEmployee."</td>";
                                        echo "<td onclick='load_detail(\"$u->IDEmployee\",\"$u->FullName\",\"\",\"list\")'>".$u->FullName."</td>";
                                        echo "<td> <center>";
                                        echo "<button idbtn='edit' editfor='$u->IDEmployee' type='button' class='btn btn-small btn-warning ' title='Edit Menu Access for ".$u->FullName."' onclick='edit(\"".$u->IDEmployee."\",\"".$u->FullName."\")' ><i class='icon-pencil'></i></button>";
                                        echo "</center></td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>                            
                        </div>
                    </div>
                    <div class="span4 tbutton">
                        <div class="row-fluid">
                            <div class="span12">
                                <h4>Buttons of menu</h4>
                            </div>
                        </div>
                        <hr class="separator">
                        <div class="row-fluid">
                            <div class="span12">
                                Button access for <a id="userteks"></a> in <a id="mnuteks"></a>
                                <table class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable">
                                    <thead class="btn-primary">
                                        <th >Button</th>
                                        <th >Access</th>
                                    </thead>
                                    <tbody id="accbtns">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row-fluid" style="margin-top : 2%">
                            <div class="span12">
                                <p align="center">
                                    <button class="btn btn-primary btn-icon glyphicons circle_ok" onclick="btuser()"><i></i>OK</button>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="widget wmore">
            <div class="widget-head">
                <div class="row-fluid">
                    <div class="span6">
                        <h4 class="heading"> More Options</h4>               
                    </div>
                    <div class="span6" style="text-align: right;">
                        
                            <button class='btn btn-mini btn-success glyphicons chevron-left' onclick="back()"><i></i>back</button>
                       
                    </div>
                </div>
            </div>
            <div class="widget-body">
                <div class="tabsbar tabsbar-2 active-fill">
                    <ul class="row-fluid row-merge">
                        <li class="span3 glyphicons cargo active"><a href="#mac" data-toggle="tab"><i></i> Mass-Access Control</a></li>
                        <!--<li class="span3 glyphicons circle_info"><a href="#kedua" data-toggle="tab"><i></i> Product Details</a></li>-->
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="mac">

                        <h4>Mass-Access Control</h4>
                        <div class="row-fluid ">
                            <div class="12">
                                <div class="row-fluid span4">
                                        <h4>Menu Access</h4>
                                    <div class=" row-fluid setall widget" style="margin-top: 2%;">
                                        <table width="100%">
                                            <tr>
                                                <td class="span5">
                                                    Set user as :
                                                    <select id="apallMass" class="span12" onchange="apallMass(this.value)" >
                                                        <!--<option value="yn">Yes/No</option>-->
                                                        <option value="0">Common user</option>
                                                        <option value="1">Module Admin</option>
                                                        <option value="2">Super Admin</option>
                                                    </select>                                                    
                                                </td>
                                                <td class="span7" align="center" valign="bottom">
                                                    <p align="left">
                                                        <input type="checkbox" id="setmassbtn" value="1" onchange="chgmassbtn()" name="setmassbtn">set button
                                                        <button type='button' class='btn btn-mini btn-success glyphicons chevron-right' id="show_massbtn" onclick="show_massbtn()" ><i></i>Show</button>                                            
                                                    </p>                                                    
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <table width="100%">
                                        <thead class="btn-primary">
                                            <tr>
                                                <th>Menu </th>
                                                <th>Access</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <div class="okiscroll " style="height: 325px;">
                                        <table width="100%" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable">
                                            <thead style="display:none">
                                                <tr>
                                                    <th>Menu</th>
                                                    <th>Access</th>
                                                </tr>
                                            </thead>
                                            <tbody id="allmenu">

                                            </tbody>
                                        </table>                            
                                    </div>
                                </div>
                                <div class=" massbtn">
                                    <div class="row-fluid">
                                        <div class="span6">
                                            <h4>Set Button</h4>
                                        </div>
                                        <div class="span6" style="text-align: right">
                                            <button type='button' class='btn btn-mini btn-success glyphicons chevron-left' onclick="hide_massbtn()" ><i></i>Hide</button>
                                        </div>
                                    </div>
                                    <div class="widget">
                                        <table width="100%">
                                             <thead class="btn-primary">
                                                 <tr>
                                                     <th>Button </th>
                                                     <th>Access</th>
                                                 </tr>
                                             </thead>
                                         </table>
                                         <div class="" style="height: 325px;">
                                             <table width="100%" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable">
                                                 <thead style="display:none">
                                                     <tr>
                                                         <th>Button</th>
                                                         <th>Access</th>
                                                     </tr>
                                                 </thead>
                                                 <tbody id="menubtn">

                                                 </tbody>
                                             </table>
                                         </div>
                                       
                                        <select style="display:none" class="span12" id="selbtn" disabled multiple="multiple">
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="span8 massuser">
                                    <h4>Set User</h4>
                                    <div class="widget">
                                        <div class="widget-body">
                                            <div class="row-fluid">
                                                <div class="span6">
                                                    <label for="setmassuser"><input type="radio" onclick="chgmassuser(this.value)" value='all' name="setmassuser" checked="checked">Set access for all user</label>
                                                    <label for="setmassuser"><input type="radio" onclick="chgmassuser(this.value)" value="sel" name="setmassuser">Set access for selected user</label>
                                                </div>
                                                <div class="span6" style="text-align: right;">
                                                    <button onclick="processmass()" class="btn btn-icon btn-success glyphicons circle_ok"><i></i>Process</button>
                                                </div>
                                            </div>
                                            <div class="row-fluid" id="alluser">
                                                <div class=" span12">
                                                    <div class="span5">
                                                        selected user
                                                        <select id="selusers" disabled class="widget span12" style="height: 390px;" multiple="multiple">
                                                        </select>
                                                    </div>
                                                    <div class="span7">
                                                        Select user below
                                                        <p align="right">
                                                            search : <input id="searchuser" oninput="searchuser(this.value)">
                                                        </p>
                                                        <table width="100%" >
                                                            <thead class="btn-primary">
                                                                <tr>
                                                                    <th>NIP</th>
                                                                    <th>Name</th>
                                                                    <th>Select</th>
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                        <div class="okiscroll " style="height: 325px;">
                                                            <table width="100%" class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable">
                                                                <thead style="display:none">
                                                                    <tr>
                                                                        <th>NIP</th>
                                                                        <th>Name</th>
                                                                        <th>Select</th>
                                                                    </tr>
                                                                </thead>                                                        
                                                                <tbody id="selalluser">

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>                                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                            
                    </div>
<!--                    <div class="tab-pane" id="kedua">
                        <h4>Second tab</h4>
                        <p>Anim pariatur cliche reprehenderit ...</p>
                    </div>-->
                </div>
            </div>
        </div>
        <!-- Gritter Notifications Plugin -->
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>
	<!-- DataTables Tables Plugin -->
	<script src="<?php echo $base_url; ?>/public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
	<!-- Tables Demo Script -->
	<script src="<?php echo $base_url; ?>public/theme/scripts/demo/tables.js"></script>
	<!-- SlimScroll Plugin -->
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/other/jquery-slimScroll/jquery.slimscroll.min.js"></script>
    </body>
</html>
