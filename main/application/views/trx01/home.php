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
            .loading_class {
                filter:alpha(opacity=50); /* for IE4 - IE7 */
                -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=80)"; /* IE8 */
                -moz-opacity:0.5;
                -khtml-opacity: 0.5;
                opacity: 0.5;
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

                $("div.widget.addmenu").hide();
                $("#docs_icons.tambahan_oki").hide();
                $("#iconmod").keydown(false);
                cek();
            });
            var idx  = 0;
            var ROOT = {
                'site_url': '<?php echo $base_url . 'index.php'; ?>',
                'base_url': '<?php echo $base_url; ?>'
            };
            $(function() {
                $("#tree").treeview({
                    collapsed: true,
                    animated: "medium",
                    control:"#sidetreecontrol",
                    persist: "location"
                });
                $("#tree2").treeview({
                    collapsed: true,
                    animated: "medium",
                    control:"#sidetreecontrol2",
                    persist: "location"
                });
                $("#iconmod").focusin(function(){
                    $("#docs_icons.tambahan_oki").effect("slide","slow").show();
                    $("span.iconlabel").slideDown("slow");
                });
                $("select").focusin(function(){
                    $("#docs_icons.tambahan_oki").slideUp("slow");
                    $("span.iconlabel").slideUp("slow");
                });
                $("input.span12").focusin(function(){
                    $("#docs_icons.tambahan_oki").slideUp("slow");
                    $("span.iconlabel").slideUp("slow");
                });
                $("input[type='checkbox']").focusin(function(){
                    $("#docs_icons.tambahan_oki").slideUp("slow");
                    $("span.iconlabel").slideUp("slow");
                });

            });
            function reloadpage(){
                var content = $("#content .innerLR");
                var url = ROOT.base_url + 'main/index.php/trx01/home/home';
                //alert(url);
                content.fadeOut("slow", "linear");
                content.load(url);
                content.fadeIn("slow");
            }
            function hassub(){
                var parent = $("#idparent").val();
                if (parent.substring(1,0) == "2"){
                    $("#hassub").prop('checked',false);
                    $.gritter.add({
                        title: 'Has Sub Menu Unchecked!',
                        text: "This menu can't have any sub menu"
                    });                    
                }
                var has = $("#hassub").prop('checked');
                if (has == true){
                    $("#urlmod").val("");
                    $("#urldet").val("");
                    $("div.hasnosub").hide();
                }
                else{
                    $("div.hasnosub").show();
                }
            }
            function ismod(){
                var par = $("#idparent").val().substring(1,100);
                var is = $("#ismod").prop('checked');
                if ( is == true ){
                    $("div.ismodmenu").show();
                    $("select#idparent").find("option[value='00']").prop("selected",true);
                    $("#hassub").prop("checked",true);
                }
                else{
                    $("#iconmod").val("");
                    $("div.ismodmenu").hide();
                }
                hassub();
            }
            function parent(){
                var vals = $("#idparent").val();
                var val = vals.substring(1,10);
//                alert(val);
                if (mnulvl == "2"){
                    if ($("#hassub").prop("checked") == true){
                        $.gritter.add({
                            title: 'Has Sub Menu Unchecked!',
                            text: "This menu can't have any sub menu"
                        });
                    }
                    $("#hassub").prop('checked',false);                    
                }
                var mnulvl = vals.substring(1,0);
                if (val == "0") {
                    $("#ismod").prop('checked',true);
                    $("#hassub").prop('checked',true);
                }
                else {
                    if ($("#ismod").prop('checked') == true){
                        $.gritter.add({
                            title: 'Module Checked!',
                            text: "Uncheck the Module option first if you want to change the parent"
                        });
                    }
                };
                ismod();
                //hassub();
            }
            function sel_icon(val){
                $("#iconmod").val(val);
                $("#docs_icons.tambahan_oki").slideUp("slow");
                $("span.iconlabel").slideUp("slow");
            }
            function addmenu(){
                $("div.widget.allmenu").slideUp("slow");
                $("div.widget.addmenu").effect("slide","slow").show();
                $("h4.judul").text("Add New Menu");
                $("#proses").val("addmenu");
                $.ajax({
                    url : ROOT.base_url+"main/index.php/trx01/home/add",
                    data: "",
                    type: "post",
                    dataType    : "json",
                    cache   : false,
                    success : function (data){
                        
                        $("#idmenu").val(data.nextidmenu);
                    }
                });
                parent();
//                alert($("#idmenu").val());
//                $("div.ismodmenu").hide();   
                
            }
            function edit(val){
                $.ajax({
                    url     : ROOT.base_url+"main/index.php/trx01/home/edit",
                    data    : "idmenu="+val,
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function(data){
                        var btns = data.btn;
                        var i = 0;
                        for(i = 0;i< btns.length;i++){
                            idx = btns[i].IDButton;
                            $("div.controls.btns").append("<button ondblclick='editbtn(\""+btns[i].IDButton+"\",\""+btns[i].ButtonDesc+"\")' idbutton='"+btns[i].IDButton+"' class=' btn btn-small  btn-default btnnew'>"+btns[i].ButtonDesc+","+btns[i].KdButton+"</button><button delid='"+btns[i].IDButton+"' title='Delete "+btns[i].ButtonDesc+" button?' class='btn-mini btn btn-danger' onclick='delbtn(\""+btns[i].IDMenu+"\",\""+btns[i].IDButton+"\")'>x</button>")
                            var cbtns = $("button.btnnew").length;
                            cbtns == 0 ? $("#ketbtn").show() : $("#ketbtn").hide() ;
                        }
                        $("#idmenu").val(data.menu.idmenu);
                        $("option[value='"+((data.menu.level*1)-1)+""+data.menu.idparent+"']").prop("selected",true);
                        $("#tmenu").val(data.menu.tmenu);
						$("#fapps").val(data.menu.fapps);	
                        $("#urlmod").val(data.menu.urlmod);
                        $("#urldet").val(data.menu.urldet);
                        $("#iconmod").val(data.menu.modicon);
                        if (data.menu.hassub == "1"){
                            $("#hassub").prop('checked',true);
                            $("#urlmod").val("");
                            $("#urldet").val("");
                        }
                        if (data.menu.hassub == "0"){
                            $("#hassub").prop('checked',false);
                        }
                        if (data.menu.idparent == "0"){
                            $("#ismod").prop("checked",true);
                        }
                        else{
                            $("#ismod").prop("checked",false);
                            $("#iconmod").val("");
                        }
//                        alert(idx);
                        parent();
                    },
                    error   : function (){
                        $.gritter.add({
                            title: "Error!",
                            text: "An error occured while getting data"
                        });
                    }
                });
                $("div.widget.allmenu").slideUp("slow");
                $("div.widget.addmenu").effect("slide","slow").show();
                $("h4.judul").text("Edit Menu");
                $("#proses").val("editmenu");
            }
            function loading(){
                bootbox.alertloading("<center>Processing. Please wait !!!<br><img src='<?php echo $base_url; ?>public/avatar/76.GIF'></center>");
            }
            function save(){
                var proses = $("#proses").val();
                var msg;
                proses == "addmenu" ? msg = "Added" : msg = "Updated";
                var idmenu      = $("#idmenu").val();
                var fapps      = $("#fapps").val();
                var idparent    = $("#idparent").val().substring(1,100);
                var level       = $("#idparent").val().substring(1,0);
                var tmenu       = $("#tmenu").val();
                var urlmod      = $("#urlmod").val();
                var urldet      = $("#urldet").val();
                var iconmod     = $("#iconmod").val();
                var shassub     = $("#hassub").prop('checked'), hassub;
                shassub == true ? hassub = $("#hassub").val() : hassub = 0;
                
                if (idparent == '0' && iconmod == ""){
                    var modm = "bad";
                    $.gritter.add({
                        title: 'Icon Needed!',
                        text: "Please select one of available icons"
                    });
                }
                if ((idparent == '0' && iconmod != "")||(idparent != '0' && iconmod == "")){
                    var modm = "ok";
                }
                if (tmenu == ""){
                    var temm = "bad";
                    $.gritter.add({
                        title: "Menu Description Can't Null!",
                        text: "Please fill menu description form"
                    });
                }else{
                    var temm = "ok";
                }
                if (hassub == "0"){
                    if(urlmod == ""){ 
                        var umom = "bad";
                        $.gritter.add({
                            title: "URL Module Can't Null!",
                            text: "Please fill URL module form"
                        });
                    }else{
                        var umom = "ok";
                    }
                    if(urldet == ""){ 
                        var udet = "bad";
                        $.gritter.add({
                            title: "URL Detail Can't Null!",
                            text: "Please fill URL module form"
                        });
                    }else{
                        var udet = "ok";
                    }
                }else{
                    var umom = "ok";
                    var udet = "ok";
                }
                if (modm == "ok" && temm == "ok" && umom == "ok" && udet == "ok"){
//                    loading();
//                alert(proses+"|"+level+"|"+hassub+"|"+idmenu+"|"+idparent+"|"+tmenu+"|"+urlmod+"|"+urldet+"|"+iconmod+"|"+ROOT.site_url);
                    $.ajax({
                        url     : ROOT.base_url+"/main/index.php/trx01/home/"+proses,
                        data    : "idmenu="+idmenu+"&fapps="+fapps+"&idparent="+idparent+"&tmenu="+tmenu+"&urlmod="+urlmod+"&urldet="+urldet+"&iconmod="+iconmod+"&hassub="+hassub+"&level="+((level*1)+1),
                        type    : "post",
                        dataType: "json",
                        cache   : false,
                        success : function(data){
//                            alert(data);
                            if (data.status == "oke" ){
                            reloadpage();
                            bootbox.alert("Success!", function (res){
                                                bootbox.hideAll();
                                                $.gritter.add({
                                                    title   : "Menu "+msg+"!",
                                                    text    : "A menu succesfully "+msg
                                                });
                                            });     
                            }
                        },
                        error   : function (){
                            bootbox.alert("Failed");
                            $.gritter.add({
                                title   : "Menu Isn't Added!",
                                text    : "A new menu failed to be added"
                            });
                        }
                    });                   
                }

            }
            function syncmenu(){
                loading();
                $.ajax({
                    url     : ROOT.base_url+"/main/index.php/trx01/home/syncmenu",
                    data    : "",
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function(data){
                        bootbox.alert("Success!! <br>"+data.menu.synced+" menu synced and "+data.btn.synced+" button synced", function (){bootbox.hideAll();});
                    }
                });
            }
            function del_menu(idmenu,hassub){
                var proses = "delmenu",msg;
                if (hassub == "1"){
                    msg = "You are going to delete this menu. It will also delete all its sub menu. Are sure to continue?";
                }else{
                    msg = "You are going to delete this menu. Continue?";
                }
                bootbox.confirm(msg, function (state){
                    if (state == true){
                        bootbox.promptpassword("Please enter your password", function (pwd){
                            if ( pwd != null){
                                loading();
                                $.ajax({
                                    url     : ROOT.base_url+"/main/index.php/trx01/home/"+proses,
                                    data    : "idmenu="+idmenu+"&pwd="+pwd,
                                    type    : "post",
                                    dataType: "json",
                                    cache   : false,
                                    success : function(data){
//                                        alert(data);
                                        if (data.status == "oke"){
                                            bootbox.hideAll();
                                            $.gritter.add({
                                                title   : "Menu Deleted!",
                                                text    : "The selected menu has been deleted"
                                            });
                                            reloadpage();
                                        }
                                        else{
                                            bootbox.alert("Invalid Password!", function (a){ 
                                                bootbox.hideAll();
                                                $.gritter.add({
                                                    title   : "Menu Isn't Deleted!",
                                                    text    : "The password was invalid"
                                                });                                 
                                            });
                                        }
                                    }
                                });     
                            }
                            else{
                                $.gritter.add({
                                    title   : "Delete Canceled!",
                                    text    : "You canceled the action"
                                });                                 
                            }
                        });
                    }
                    else{
                        $.gritter.add({
                            title   : "Delete Canceled!",
                            text    : "You canceled the action"
                        }); 
                    }                    
                });
            }
            function addbtn(){
                $("#btnprocess").val("addbtn");
                idx++;
//                $("div.controls.btns").append('<button class="btn btn-small  btn-default" >button</button>');
                $("div.controls.btns").append('<input type="text" onblur="savebtn('+idx+',this.value)" class="span3 " idbutton="'+idx+'">');
                $("input[idbutton='"+idx+"']").focus();
                $("#ketbtn").hide();
            }
            function savebtn(idbutton,val){
                var btnprocess  = $("#btnprocess").val();
                var idmenu      = $("#idmenu").val();
//                alert(idbutton+val+idmenu);
                if (val != ""){
                    $("button[delid='"+idbutton+"']").remove();
                    $("input[idbutton='"+idbutton+"']").replaceWith("<button ondblclick='editbtn(\""+idbutton+"\",\""+val+"\")' idbutton='"+idbutton+"' class=' btn btn-small  btn-default btnnew'>"+val+"</button><button delid='"+idbutton+"' title='Delete "+val+" button?' class='btn-mini btn btn-danger' onclick='delbtn(\""+idmenu+"\",\""+idbutton+"\")'>x</button>");
//                    ajax save here
                    $.ajax({
                        url     : ROOT.base_url+"/main/index.php/trx01/home/"+btnprocess,
                        data    : "idmenu="+idmenu+"&idbutton="+idbutton+"&btndesc="+val,
                        type    : "post",
                        dataType: "json",
                        cache   : false,
                        success : function (data){
//                            alert("ijij");
                        }
                    });
                }
                else{
                    $("button[delid='"+idbutton+"']").remove();
                    $("input[idbutton='"+idbutton+"']").remove();
                    
                    
                    delbtn(idmenu,idbutton);
//                    alert(idx)
                }
            }
            function editbtn(idbutton,val){
                var idmenu      = $("#idmenu").val();
                $("#btnprocess").val("editbtn");
                $.ajax({
                    url     : ROOT.base_url+"main/index.php/trx01/home/editb",
                    data    : "idmenu="+idmenu+"&idbutton="+idbutton,
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
                        $("button[idbutton='"+idbutton+"']").replaceWith('<input type="text" onblur="savebtn('+idbutton+',this.value)" class="span3 " idbutton="'+idbutton+'" value="'+data.ButtonDesc+","+data.KdButton+'">');
                        $("button[delid='"+idbutton+"']").replaceWith("<button delid='"+idbutton+"' title='Delete "+val+" button?' class='btn-mini btn btn-danger btnnew' onclick='delbtn(\""+idmenu+"\",\""+idbutton+"\")'>x</button>");
                        $("input[idbutton='"+idbutton+"']").focus();                        
                    }
                });

            }
            function delbtn(idmenu,idbutton){
//                bootbox.confirm("Delete this button?", function (r){
//                    if (r == true){
                        $.ajax({
                            url     : ROOT.base_url+"/main/index.php/trx01/home/delbtn",
                            data    : "idmenu="+idmenu+"&idbutton="+idbutton,
                            type    : "post",
                            dataType: "json",
                            cache   : false,
                            success : function (data){

                            }
                        });
                        
                        
                        $("button[delid='"+idbutton+"']").remove();
                        $("button[idbutton='"+idbutton+"']").remove();
                        var cbtns = $(".btnnew").length;
                        cbtns == 0 ? $("#ketbtn").show() : "";
//                    }
//                    else{
//                        
//                    }
//                })
            }
            function cancelact(){
                var proses = $("#proses").val();
                var idmenu = $("#idmenu").val();
                if (proses == "addmenu"){
                    var idbutton = "canceladd";
                }
                else if (proses == "editmenu"){
                    var idbutton = "canceledit";
                }
//                loading();
                $.ajax({
                    url     : ROOT.base_url+"/main/index.php/trx01/home/delbtn",
                    data    : "idmenu="+idmenu+"&idbutton="+idbutton,
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
//                        alert(data.toSource());
                        bootbox.hideAll();
                        reloadpage();
                        
                    }
                });
                
            }
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
        </script>
    </head>
    <body>

        <!--<button onclick="cek()">cek</button>-->
        <div class="widget allmenu">
            <div class="widget-head"><h4 class="heading">Menu Editor</h4></div>
            <div class="widget-body">
                <div class="row-fluid">
                    <div class="span3">
                        <h4>Menu Preview</h4>
                        <hr class="separator">
                        <div id="sidetreecontrol">
                            <a href="?#" class="btn btn-warning btn-small btn-icon glyphicons collapse_top " ><i></i>Collapse</a>
                            <a href="?#" class="btn btn-danger btn-small btn-icon glyphicons expand" ><i></i>Expand</a>
                        </div>
                        <ul id="tree">
                            <?php echo $menutree;?>
                        </ul>
                        <hr class="separator"> 
                    </div>
                    <div class="span9">
                        <h4>Table of Menu</h4>
                        <hr class="separator">
                        <p align="right">
                            <button onclick="addmenu()" idbtn="add" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> Add</button>
                            <button onclick="syncmenu()" idbtn="syncmenu" class="btn btn-warning btn-icon glyphicons refresh"><i></i> Synchronize Menu</button>
                        </p>
                        <table width='100%' class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable dynamicTable">
                            <thead class="btn-primary">
                                <tr>
                                    <td><center>ID Menu</center></td>
                                    <td><center>For Application</center></td>
                                    <td><center>Menu Desc</center></td>
                                    <td><center>Menu Parent</center></td>
                                    <td><center>Has Sub</center></td>
                                    <td><center>Menu Icon</center></td>
                                    <td><center>URL Mod</center></td>
                                    <td><center>URL Det</center></td>
                                    <td><center>Action</center></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $i = 0;
                                    foreach($menu->result() as $mn){
                                        $i++;
                                        echo "<tr class='selectable' >";
//                                        echo "<td><center class='nomor'>".$i."</center></td>";
                                        echo "<td>".$mn->IDMenu."</td>";
                                        echo "<td>".$mn->ForApplication."</td>";
                                        echo "<td>".$mn->MenuDesc."</td>";
                                        ($a = $this->mnu->get_menu($mn->IDParent)->row()) == NULL? $parent = "0" : $parent = $a->MenuDesc;
                                        echo "<td>".$parent."</td>";
                                        $mn->HasSubMenu == "1" ? $sub = "yes" : $sub = "no";
                                        echo "<td>".$sub."</td>";
                                        echo "<td>".$mn->MenuIcon."</td>";
                                        echo "<td>".$mn->URLMod."</td>";
                                        echo "<td>".$mn->URLDet."</td>";
                                        echo "<td>                                    
                                            <div class='btn-group'>
                                            <button idbtn='edit' type='button' class='btn btn-mini btn-warning' title='Edit ".$mn->MenuDesc."' onclick='edit(\"".$mn->IDMenu."\")' ><i class='icon-pencil'></i></button>
                                            <button idbtn='delete' type='button' class='btn btn-mini btn-danger' title='Delete ".$mn->MenuDesc."' onclick='del_menu(\"".$mn->IDMenu."\",\"".$mn->HasSubMenu."\")'><i class='icon-trash'></i></button>
                                            </div>
                                              </td>";
                                        echo "</tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>                
            </div> 
        </div>
        <div class="widget addmenu">
            
            <div class="widget-head"><h4 class="heading judul"></h4></div>
            <div class="widget-body">
                <div class="row-fluid">
                    <div class="span3">
                        <h4>Menu Preview</h4>
                        <hr class="separator">
                        <div id="sidetreecontrol2">
                            <a href="?#" class="btn btn-warning btn-small btn-icon glyphicons collapse_top " ><i></i>Collapse</a>
                            <a href="?#" class="btn btn-danger btn-small btn-icon glyphicons expand" ><i></i>Expand</a>
                        </div>
                        <ul id="tree2">
                            <?php echo $menutree;?>
                        </ul>
                        <hr class="separator"> 
                    </div>
                    <div class="span9">
                        <h4 class="judul"></h4>
                        <hr class="separator">
                        <input type="hidden" name="proses" id="proses"  value="">
                        <input type="hidden" name="idmenu" id="idmenu" value="">
                        <div class="row-fluid">
							<div class="span3">
                                <div class="control-group">
                                    <div class="controls">
                                        <label class="control-label" for="tmenu">For Application</label>
                                        <input type="text" class="span12" name="fapps" id="fapps" placeholder="Other Application">
                                    </div>
                                </div>
                            </div>
                            <div class="span3">
                                <div class="control-group">
                                    <div class="controls">
                                        <label class="control-label" for="idparent">Menu Parent</label>
                                        <select name="idparent" id="idparent" class="span12" onchange="parent(this.menulevel)">
                                            <option value="00">NONE</option>
                                            <?php 
                                            foreach ($menudrop as $mnu){
                                                
                                                echo "<option value='".$mnu->Level.$mnu->IDMenu."'>";
                                                echo $mnu->MenuDesc;
                                                echo "</option>";
                                                $child = $this->mnu->get_child($mnu->IDMenu,"1");
                                                foreach($child->result() as $c){
                                                    echo "<optgroup>";
                                                    echo "<option value='".$c->Level.$c->IDMenu."'>";
                                                    echo $c->MenuDesc;
                                                    echo "</option>";
                                                    echo "</optgroup>";
                                                }
                                                
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="span3">
                                <div class="control-group">
                                    <div class="controls">
                                        <label class="control-label" for="tmenu">Menu Title</label>
                                        <input type="text" class="span12" name="tmenu" id="tmenu" placeholder="Input menu title">
                                    </div>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="control-group ">
                                    <div class="controls">
                                        <label class="control-label" for="hassub">Has Sub Menu</label>
                                        <input type="checkbox" value="1" class="span3" name="hassub" id="hassub" onclick="hassub()">
                                        <span><i>check if true</i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="span2">
                                <div class="control-group ">
                                    <div class="controls">
                                        <label class="control-label" for="ismod">Module</label>
                                        <input type="checkbox" value="1" class="span3" name="ismod" id="ismod" onclick="ismod()">
                                        <span><i>check if true</i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid hasnosub">
                            <div class="row-fluid span12">
                                <div class="span3 urlmod">
                                    <div class="control-group">
                                        <div class="controls">
                                            <label class="control-label" for="urlmod">Module URL</label>
                                            <input type="text" class="span12"name="urlmod" id="urlmod" placeholder="Input module URL">
                                        </div>
                                    </div>
                                </div>
                                <div class="span3">
                                    <div class="control-group">
                                        <div class="controls">
                                            <label class="control-label" for="urldet">Detail URL</label>
                                            <input type="text" class="span12"name="urldet" id="urldet" placeholder="Input detail URL">
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                            <div class="row-fluid span12">
                                <div class="span12">
                                    <div class="control-group">
                                        <div class="controls btns" style="margin-bottom: 2%;">
                                            <input type="hidden" id="btnprocess" val="">
                                            <label class="control-label" for="button">Buttons</label>
                                            <span id="ketbtn">Click "add" to add button!</span>
                                        </div>
                                        <a class="btn btn-mini btn-default glyphicons circle_plus" onclick="addbtn()"><i></i>add</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid ismodmenu">
                            <div class="span12">
                                <div class="control-group">
                                    <div class="controls">
                                        <label class="control-label" for="iconmod">Module Icon</label>
                                        <input type="text" class="span2" name="iconmod" id="iconmod" placeholder="Select icon">
                                        <span class="iconlabel"><i>select an icon below</i></span>
                                        <div id="docs_icons" class="tambahan_oki widget">
                                            <a  onclick="sel_icon(this.text)" class="glyphicons glass"><i></i>glass</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons leaf"><i></i>leaf</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons dog"><i></i>dog</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons user"><i></i>user</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons girl"><i></i>girl</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons car"><i></i>car</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons user_add"><i></i>user_add</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons user_remove"><i></i>user_remove</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons film"><i></i>film</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons magic"><i></i>magic</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons envelope"><i></i>envelope</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons camera"><i></i>camera</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons heart"><i></i>heart</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons beach_umbrella"><i></i>beach_umbrella</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons train"><i></i>train</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons print"><i></i>print</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons bin"><i></i>bin</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons music"><i></i>music</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons note"><i></i>note</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons heart_empty"><i></i>heart_empty</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons home"><i></i>home</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons snowflake"><i></i>snowflake</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons fire"><i></i>fire</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons magnet"><i></i>magnet</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons parents"><i></i>parents</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons binoculars"><i></i>binoculars</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons road"><i></i>road</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons search"><i></i>search</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons cars"><i></i>cars</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons notes_2"><i></i>notes_2</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons pencil"><i></i>pencil</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons bus"><i></i>bus</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons wifi_alt"><i></i>wifi_alt</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons luggage"><i></i>luggage</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons old_man"><i></i>old_man</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons woman"><i></i>woman</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons file"><i></i>file</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons coins"><i></i>coins</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons airplane"><i></i>airplane</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons notes"><i></i>notes</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons stats"><i></i>stats</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons charts"><i></i>charts</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons pie_chart"><i></i>pie_chart</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons group"><i></i>group</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons keys"><i></i>keys</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons calendar"><i></i>calendar</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons router"><i></i>router</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons camera_small"><i></i>camera_small</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons dislikes"><i></i>dislikes</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons star"><i></i>star</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons link"><i></i>link</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons eye_open"><i></i>eye_open</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons eye_close"><i></i>eye_close</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons alarm"><i></i>alarm</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons clock"><i></i>clock</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons stopwatch"><i></i>stopwatch</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons projector"><i></i>projector</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons history"><i></i>history</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons truck"><i></i>truck</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons cargo"><i></i>cargo</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons compass"><i></i>compass</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons keynote"><i></i>keynote</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons paperclip"><i></i>paperclip</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons power"><i></i>power</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons lightbulb"><i></i>lightbulb</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons tag"><i></i>tag</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons tags"><i></i>tags</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons cleaning"><i></i>cleaning</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons ruller"><i></i>ruller</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons gift"><i></i>gift</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons umbrella"><i></i>umbrella</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons book"><i></i>book</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons bookmark"><i></i>bookmark</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons wifi"><i></i>wifi</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons cup"><i></i>cup</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons stroller"><i></i>stroller</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons headphones"><i></i>headphones</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons headset"><i></i>headset</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons warning_sign"><i></i>warning_sign</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons signal"><i></i>signal</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons retweet"><i></i>retweet</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons refresh"><i></i>refresh</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons roundabout"><i></i>roundabout</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons random"><i></i>random</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons heat"><i></i>heat</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons repeat"><i></i>repeat</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons display"><i></i>display</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons log_book"><i></i>log_book</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons adress_book"><i></i>adress_book</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons building"><i></i>building</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons eyedropper"><i></i>eyedropper</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons adjust"><i></i>adjust</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons tint"><i></i>tint</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons crop"><i></i>crop</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons vector_path_square"><i></i>vector_path_square</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons vector_path_circle"><i></i>vector_path_circle</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons vector_path_polygon"><i></i>vector_path_polygon</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons vector_path_line"><i></i>vector_path_line</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons vector_path_curve"><i></i>vector_path_curve</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons vector_path_all"><i></i>vector_path_all</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons font"><i></i>font</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons italic"><i></i>italic</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons bold"><i></i>bold</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons text_underline"><i></i>text_underline</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons text_strike"><i></i>text_strike</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons text_height"><i></i>text_height</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons text_width"><i></i>text_width</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons text_resize"><i></i>text_resize</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons left_indent"><i></i>left_indent</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons right_indent"><i></i>right_indent</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons align_left"><i></i>align_left</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons align_center"><i></i>align_center</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons align_right"><i></i>align_right</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons justify"><i></i>justify</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons list"><i></i>list</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons text_smaller"><i></i>text_smaller</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons text_bigger"><i></i>text_bigger</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons embed"><i></i>embed</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons embed_close"><i></i>embed_close</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons table"><i></i>table</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons message_full"><i></i>message_full</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons message_empty"><i></i>message_empty</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons message_in"><i></i>message_in</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons message_out"><i></i>message_out</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons message_plus"><i></i>message_plus</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons message_minus"><i></i>message_minus</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons message_ban"><i></i>message_ban</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons message_flag"><i></i>message_flag</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons message_lock"><i></i>message_lock</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons message_new"><i></i>message_new</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons inbox"><i></i>inbox</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons inbox_plus"><i></i>inbox_plus</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons inbox_minus"><i></i>inbox_minus</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons inbox_lock"><i></i>inbox_lock</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons inbox_in"><i></i>inbox_in</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons inbox_out"><i></i>inbox_out</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons cogwheel"><i></i>cogwheel</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons cogwheels"><i></i>cogwheels</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons picture"><i></i>picture</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons adjust_alt"><i></i>adjust_alt</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons database_lock"><i></i>database_lock</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons database_plus"><i></i>database_plus</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons database_minus"><i></i>database_minus</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons database_ban"><i></i>database_ban</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons folder_open"><i></i>folder_open</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons folder_plus"><i></i>folder_plus</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons folder_minus"><i></i>folder_minus</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons folder_lock"><i></i>folder_lock</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons folder_flag"><i></i>folder_flag</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons folder_new"><i></i>folder_new</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons edit"><i></i>edit</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons new_window"><i></i>new_window</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons check"><i></i>check</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons unchecked"><i></i>unchecked</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons more_windows"><i></i>more_windows</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons show_big_thumbnails"><i></i>show_big_thumbnails</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons show_thumbnails"><i></i>show_thumbnails</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons show_thumbnails_with_lines"><i></i>show_thumbnails_with_lines</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons show_lines"><i></i>show_lines</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons playlist"><i></i>playlist</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons imac"><i></i>imac</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons macbook"><i></i>macbook</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons ipad"><i></i>ipad</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons iphone"><i></i>iphone</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons iphone_transfer"><i></i>iphone_transfer</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons iphone_exchange"><i></i>iphone_exchange</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons ipod"><i></i>ipod</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons ipod_shuffle"><i></i>ipod_shuffle</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons ear_plugs"><i></i>ear_plugs</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons phone"><i></i>phone</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons step_backward"><i></i>step_backward</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons fast_backward"><i></i>fast_backward</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons rewind"><i></i>rewind</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons play"><i></i>play</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons pause"><i></i>pause</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons stop"><i></i>stop</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons forward"><i></i>forward</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons fast_forward"><i></i>fast_forward</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons step_forward"><i></i>step_forward</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons eject"><i></i>eject</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons facetime_video"><i></i>facetime_video</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons download_alt"><i></i>download_alt</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons mute"><i></i>mute</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons volume_down"><i></i>volume_down</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons volume_up"><i></i>volume_up</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons screenshot"><i></i>screenshot</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons move"><i></i>move</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons more"><i></i>more</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons brightness_reduce"><i></i>brightness_reduce</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons brightness_increase"><i></i>brightness_increase</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons circle_plus"><i></i>circle_plus</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons circle_minus"><i></i>circle_minus</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons circle_remove"><i></i>circle_remove</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons circle_ok"><i></i>circle_ok</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons circle_question_mark"><i></i>circle_question_mark</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons circle_info"><i></i>circle_info</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons circle_exclamation_mark"><i></i>circle_exclamation_mark</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons remove"><i></i>remove</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons ok"><i></i>ok</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons ban"><i></i>ban</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons download"><i></i>download</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons upload"><i></i>upload</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons shopping_cart"><i></i>shopping_cart</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons lock"><i></i>lock</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons unlock"><i></i>unlock</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons electricity"><i></i>electricity</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons ok_2"><i></i>ok_2</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons remove_2"><i></i>remove_2</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons cart_out"><i></i>cart_out</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons cart_in"><i></i>cart_in</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons left_arrow"><i></i>left_arrow</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons right_arrow"><i></i>right_arrow</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons down_arrow"><i></i>down_arrow</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons up_arrow"><i></i>up_arrow</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons resize_small"><i></i>resize_small</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons resize_full"><i></i>resize_full</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons circle_arrow_left"><i></i>circle_arrow_left</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons circle_arrow_right"><i></i>circle_arrow_right</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons circle_arrow_top"><i></i>circle_arrow_top</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons circle_arrow_down"><i></i>circle_arrow_down</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons play_button"><i></i>play_button</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons unshare"><i></i>unshare</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons share"><i></i>share</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons chevron-right"><i></i>chevron-right</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons chevron-left"><i></i>chevron-left</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons bluetooth"><i></i>bluetooth</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons euro"><i></i>euro</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons usd"><i></i>usd</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons gbp"><i></i>gbp</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons retweet_2"><i></i>retweet_2</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons moon"><i></i>moon</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons sun"><i></i>sun</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons cloud"><i></i>cloud</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons direction"><i></i>direction</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons brush"><i></i>brush</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons pen"><i></i>pen</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons zoom_in"><i></i>zoom_in</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons zoom_out"><i></i>zoom_out</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons pin"><i></i>pin</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons albums"><i></i>albums</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons rotation_lock"><i></i>rotation_lock</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons flash"><i></i>flash</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons google_maps"><i></i>google_maps</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons anchor"><i></i>anchor</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons conversation"><i></i>conversation</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons chat"><i></i>chat</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons male"><i></i>male</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons female"><i></i>female</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons asterisk"><i></i>asterisk</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons divide"><i></i>divide</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons snorkel_diving"><i></i>snorkel_diving</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons scuba_diving"><i></i>scuba_diving</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons oxygen_bottle"><i></i>oxygen_bottle</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons fins"><i></i>fins</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons fishes"><i></i>fishes</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons boat"><i></i>boat</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons delete"><i></i>delete</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons sheriffs_star"><i></i>sheriffs_star</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons qrcode"><i></i>qrcode</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons barcode"><i></i>barcode</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons pool"><i></i>pool</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons buoy"><i></i>buoy</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons spade"><i></i>spade</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons bank"><i></i>bank</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons vcard"><i></i>vcard</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons electrical_plug"><i></i>electrical_plug</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons flag"><i></i>flag</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons credit_card"><i></i>credit_card</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons keyboard-wireless"><i></i>keyboard-wireless</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons keyboard-wired"><i></i>keyboard-wired</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons shield"><i></i>shield</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons ring"><i></i>ring</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons cake"><i></i>cake</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons drink"><i></i>drink</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons beer"><i></i>beer</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons fast_food"><i></i>fast_food</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons cutlery"><i></i>cutlery</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons pizza"><i></i>pizza</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons birthday_cake"><i></i>birthday_cake</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons tablet"><i></i>tablet</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons settings"><i></i>settings</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons bullets"><i></i>bullets</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons cardio"><i></i>cardio</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons t-shirt"><i></i>t-shirt</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons pants"><i></i>pants</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons sweater"><i></i>sweater</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons fabric"><i></i>fabric</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons leather"><i></i>leather</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons scissors"><i></i>scissors</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons bomb"><i></i>bomb</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons skull"><i></i>skull</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons celebration"><i></i>celebration</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons tea_kettle"><i></i>tea_kettle</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons french_press"><i></i>french_press</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons coffe_cup"><i></i>coffe_cup</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons pot"><i></i>pot</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons grater"><i></i>grater</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons kettle"><i></i>kettle</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons hospital"><i></i>hospital</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons hospital_h"><i></i>hospital_h</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons microphone"><i></i>microphone</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons webcam"><i></i>webcam</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons temple_christianity_church"><i></i>temple_christianity_church</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons temple_islam"><i></i>temple_islam</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons temple_hindu"><i></i>temple_hindu</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons temple_buddhist"><i></i>temple_buddhist</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons bicycle"><i></i>bicycle</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons life_preserver"><i></i>life_preserver</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons share_alt"><i></i>share_alt</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons comments"><i></i>comments</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons flower"><i></i>flower</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons baseball"><i></i>baseball</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons rugby"><i></i>rugby</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons ax"><i></i>ax</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons table_tennis"><i></i>table_tennis</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons bowling"><i></i>bowling</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons tree_conifer"><i></i>tree_conifer</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons tree_deciduous"><i></i>tree_deciduous</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons more_items"><i></i>more_items</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons sort"><i></i>sort</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons filter"><i></i>filter</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons gamepad"><i></i>gamepad</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons playing_dices"><i></i>playing_dices</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons calculator"><i></i>calculator</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons tie"><i></i>tie</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons wallet"><i></i>wallet</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons piano"><i></i>piano</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons sampler"><i></i>sampler</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons podium"><i></i>podium</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons soccer_ball"><i></i>soccer_ball</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons blog"><i></i>blog</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons dashboard"><i></i>dashboard</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons certificate"><i></i>certificate</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons bell"><i></i>bell</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons candle"><i></i>candle</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons pushpin"><i></i>pushpin</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons iphone_shake"><i></i>iphone_shake</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons pin_flag"><i></i>pin_flag</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons turtle"><i></i>turtle</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons rabbit"><i></i>rabbit</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons globe"><i></i>globe</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons briefcase"><i></i>briefcase</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons hdd"><i></i>hdd</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons thumbs_up"><i></i>thumbs_up</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons thumbs_down"><i></i>thumbs_down</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons hand_right"><i></i>hand_right</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons hand_left"><i></i>hand_left</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons hand_up"><i></i>hand_up</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons hand_down"><i></i>hand_down</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons fullscreen"><i></i>fullscreen</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons shopping_bag"><i></i>shopping_bag</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons book_open"><i></i>book_open</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons nameplate"><i></i>nameplate</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons nameplate_alt"><i></i>nameplate_alt</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons vases"><i></i>vases</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons bullhorn"><i></i>bullhorn</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons dumbbell"><i></i>dumbbell</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons suitcase"><i></i>suitcase</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons file_import"><i></i>file_import</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons file_export"><i></i>file_export</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons bug"><i></i>bug</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons crown"><i></i>crown</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons smoking"><i></i>smoking</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons cloud-upload"><i></i>cloud-upload</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons cloud-download"><i></i>cloud-download</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons restart"><i></i>restart</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons security_camera"><i></i>security_camera</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons expand"><i></i>expand</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons collapse"><i></i>collapse</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons collapse_top"><i></i>collapse_top</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons globe_af"><i></i>globe_af</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons global"><i></i>global</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons spray"><i></i>spray</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons nails"><i></i>nails</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons claw_hammer"><i></i>claw_hammer</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons classic_hammer"><i></i>classic_hammer</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons hand_saw"><i></i>hand_saw</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons riflescope"><i></i>riflescope</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons electrical_socket_eu"><i></i>electrical_socket_eu</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons electrical_socket_us"><i></i>electrical_socket_us</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons pinterest"><i></i>pinterest</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons dropbox"><i></i>dropbox</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons google_plus"><i></i>google_plus</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons jolicloud"><i></i>jolicloud</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons yahoo"><i></i>yahoo</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons blogger"><i></i>blogger</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons picasa"><i></i>picasa</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons amazon"><i></i>amazon</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons tumblr"><i></i>tumblr</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons wordpress"><i></i>wordpress</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons instapaper"><i></i>instapaper</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons evernote"><i></i>evernote</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons xing"><i></i>xing</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons zootool"><i></i>zootool</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons dribbble"><i></i>dribbble</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons deviantart"><i></i>deviantart</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons read_it_later"><i></i>read_it_later</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons linked_in"><i></i>linked_in</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons forrst"><i></i>forrst</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons pinboard"><i></i>pinboard</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons behance"><i></i>behance</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons github"><i></i>github</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons youtube"><i></i>youtube</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons skitch"><i></i>skitch</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons foursquare"><i></i>foursquare</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons quora"><i></i>quora</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons badoo"><i></i>badoo</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons spotify"><i></i>spotify</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons stumbleupon"><i></i>stumbleupon</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons readability"><i></i>readability</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons facebook"><i></i>facebook</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons twitter"><i></i>twitter</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons instagram"><i></i>instagram</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons posterous_spaces"><i></i>posterous_spaces</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons vimeo"><i></i>vimeo</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons flickr"><i></i>flickr</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons last_fm"><i></i>last_fm</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons rss"><i></i>rss</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons skype"><i></i>skype</a>
                                            <a  onclick="sel_icon(this.text)" class="glyphicons e-mail"><i></i>e-mail</a>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>                   
                        </div>
                        <hr class="separator">
                        <div class="row-fluid">
                            <p align="center">
                                <button type="submit" class="btn btn-icon btn-success glyphicons circle_ok" onclick="save()" ><i></i>Save</button>
                                <button type="button" class="btn btn-icon btn-danger glyphicons circle_remove" onclick="cancelact()"><i></i>Cancel</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class=" tes_aja">
            
        </div>
        <!-- Gritter Notifications Plugin -->
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>
	<!-- DataTables Tables Plugin -->
	<script src="<?php echo $base_url; ?>/public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
	<!-- Tables Demo Script -->
	<script src="<?php echo $base_url; ?>public/theme/scripts/demo/tables.js"></script>
    </body>
</html>
