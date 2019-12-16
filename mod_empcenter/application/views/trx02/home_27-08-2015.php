<html>
    <head>
        <!-- Meta -->
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta content="utf-8" http-equiv="encoding" />

        <?php $base_url = $this->session->userdata('sess_base_url'); ?>
	<?php $checkmother = $this->session->userdata('sess_mother'); ?>
        <!-- JQueryUI -->
       
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" />
        <!-- hide the close link in the toolbar -->

        <style type="text/css">
            .transindo{
                font-size: 10px;
            }
            .upper{text-transform:uppercase;}
            a.ui-dialog-titlebar-close { display:none } .label_error_cuti{color : #be362f;}
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
        <!-- Gritter Notifications Plugin -->
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/js/jquery.gritter.min.js"></script>
	<!-- DataTables Tables Plugin -->
	<script src="<?php echo $base_url; ?>/public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
	<!-- Tables Demo Script -->
	<script src="<?php echo $base_url; ?>public/theme/scripts/demo/tables.js"></script>
        <script>
            $(document).ready(function (){
                nochangestep();
                hideallctntab();
                chgtab(1);
					<?php 
					if ($checkmother == "empty"){
						?>						
						setTimeout(function (){
							chgtab(3);
							$('.tab-pane').removeClass('active');
							$(".tabatas").removeClass('active');
							$('#tab3.tab-pane').addClass('active');
							$(".tab3.tabatas").addClass('active');
						},"300");					
						
						<?php
					}
						?>                  
                setTimeout(function (){
                    $(".hfstep").each(function (){
                        var val = $(this).val();
                        var id  = $(this).attr("id");
                        var step    = id.substring(4,3);
                        if (val == "1"){
                            $(".ifnola[step='"+step+"']").hide({});
                        }else{
                            $(".ifnola[step='"+step+"']").show({});
                        }
                    });                    
                },"500");
                //$("#idemp").keydown(false);

//                $("#empstat").keydown(false);
                $("#hiredate").keydown(false);
                $("#datefirst").keydown(false);
                $("#dateprob").keydown(false);
                $("#contnew").keydown(false);
                $("#contend").keydown(false);
            });
            var inohp = 0;
            var itelpl = 0;
            var itelpk = 0;
            var iexmail = 0;
            var ROOT = {
                'site_url': '<?php echo $base_url . 'index.php'; ?>',
                'base_url': '<?php echo $base_url; ?>'
            };
            var employees = <?php echo $employees;?>;
            $("#bheight").keydown(function(e){
                var s = String.fromCharCode(e.which);
//                alert(s+"|"+e.keyCode+"|"+(e.keyCode-96));
                
                var num = $.isNumeric(s);
                var key = e.keyCode || e.charCode;
                if (num == true || key == 8 || key == 46 || key == 9 || (key >= 96 && key <= 105)){
//                    alert("angka");
                }else{
//                    alert(isinew);
                    return false;
                }
            });
            $("#bweight").keydown(function(e){
                var s = String.fromCharCode(e.which);
                var num = $.isNumeric(s);
                var key = e.keyCode || e.charCode;
                if (num == true || key == 8 || key == 46 || key == 9 || (key >= 96 && key <= 105)){
//                    alert("angka");
                }else{
//                    alert(isinew);
                    return false;
                }
            });
            $("#nmparent").hide();
            $("#idparent").focusin(function (){
                $("#idparent").hide();
                $("#nmparent").show({
                    complete: function(){
                        $("#nmparent").focus();
                        $("#idparent").prop("disabled",true);
                        $("#idparent").show({
                            easing : "slide",
                            duration: 300
                        });
                    }
                });
            });
            $("#nmparent").focusout(function(){
                $("#idparent").prop("disabled",false);
                $("#nmparent").hide({
                    easing : "slide",
                    duration : 300
                });
            });
            $("#nmparent").autocomplete({
                source  : employees,
                select  : function (event,ui){
                    $("#idparent").val(ui.item.IDEmployee);
                    $("#nmparent").hide({
                        easing : "slide",
                        duration : 300,
                        complete : function (){
                            $("#idparent").prop("disabled",false);
                        }
                    });
                }
            });
            $("#dbirth").datepicker({
                changeMonth: true,
                changeYear  : true,
                yearRange   : "-80:+0",
                dateFormat  : "dd-mm-yy"
            });
            function reloadpage(){
                var content = $("#content .innerLR");
                var url = ROOT.base_url + 'mod_empcenter/index.php/trx02/home/';

                content.load(url);
            }
            function loadpersonal(){
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/get_personal",
                    data    : "",
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
//                        alert(data.NoKTP+"|"+data.ExternalEmail);
                        $("#fname").val(data.FullName);
                        $("#nname").val(data.NickName);
                        $("#pbirth").val(data.BirthPlace);
                        var dbi = data.BirthDate.split("-");
                        $("#dbirth").val(dbi[2]+"-"+dbi[1]+"-"+dbi[0]);
                        $("#bheight").val(data.Height);
                        $("#bweight").val(data.Weight);
                        $("#gender").find("option[value='"+data.Gender+"']").prop("selected",true);
                        $("#tblood").val(data.BloodType);
                        $("#czship").val(data.Citizenship);
                        $("#religion").val(data.Religion);
                        $("#noktp").val(data.NoKTP);
                        $("#nonpwp").val(data.NoNPWP);
                        $("#nojamsos").val(data.NoJamsostek);
                        $("#nokpj").val(data.NoKPJ);
                        $("#abank").val(data.BankAccount);
                        $("input[name='marital'][value='"+data.MaritalStatus+"']").prop("checked",true);//radio
                        $("#coupname").val(data.CoupleName);
                        $("#couplektp").val(data.CoupleKTP);
                        $("#nchild").val(data.NumberChildren);
                        // stepper >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                        var vstp    = new Array();
                        vstp.push(data.F1s1);vstp.push(data.F1s2);vstp.push(data.F1s3);vstp.push(data.F1s4);vstp.push(data.F1s5);
                        for(var io = 1;io<=5;io++){
                            $("#f1s"+io).val(vstp[(io-1)]);
                            if (vstp[(io-1)] == "1"){ $(".istep"+io).addClass("icon-white"); }
                                else{ $(".istep"+io).removeClass("icon-white"); }
                        }
                        $(".istep"+io).addClass("icon-white");
                        // no hp============================================
                        var nohps = data.NoHP;
			if (nohps != null){
		                var nohp = nohps.split(",");
		                var jmlnohp = nohp.length;
		                if (jmlnohp > 0){
		                    $("div.controls.nohps").find("input").remove();
		                    $("div.controls.nohps").find("button").remove();
		                    for (var i=0;i<jmlnohp;i++){
		                        inohp++;
	    //                            alert(nohp[i]);                        
		                        $("div.controls.nohps").append("<input class='span2 nohpke"+i+"' type='text' name='nohp[]' value='"+nohp[i]+"'>"+'<button title="Delete ph no.?" class="btn-mini btn btn-danger nohpke'+i+'" onclick="remnohp(\''+i+'\')">x</button>');
		                    }
		                }else{
		                    
		                }
			}
                        // end of no hp============================================
                        // no telp live addr============================================
                        var telpls  = data.LiveAddressNoTelp;
			if (telpls != null){
                                
		                var telpl   = telpls.split(",");
		                var jmltelpl= telpl.length;
		                if (jmltelpl > 0 ){
		                    $("div.controls.telpls").find("input").remove();
		                    $("div.controls.telpls").find("button").remove();
		                    for(var i=0;i<jmltelpl;i++){
		                        itelpl++;
		                        $("div.controls.telpls").append("<input class='span2 telplke"+i+"' type='text' name='telpl[]' value=\""+telpl[i]+"\">"+'<button title="Delete house telp no.?" class="btn-mini btn btn-danger telplke'+i+'" onclick="remtelpl(\''+i+'\')">x</button>');
		                    }
		                }else{}
                        }
                        // end of no telp live addr============================================
                        // no telp ktp addr============================================
                        var telpks  = data.KTPAddressNoTelp;
			if (telpks != null){
		                var telpk   = telpks.split(",");
		                var jmltelpk= telpk.length;
		                if (jmltelpk > 0 ){
		                    $("div.controls.telpks").find("input").remove();
		                    $("div.controls.telpks").find("button").remove();
		                    for(var i=0;i<jmltelpk;i++){
		                        itelpk++;
		                        $("div.controls.telpks").append("<input class='span2 telpkke"+i+"' type='text' name='telpk[]' value=\""+telpk[i]+"\">"+'<button title="Delete house telp no.?" class="btn-mini btn btn-danger telpkke'+i+'" onclick="remtelpk(\''+i+'\')">x</button>');
		                    }
		                }else{}
                        }
                        // end of no telp ktp addr============================================
                        // no telp ktp addr============================================
                        var exmails  = data.ExternalEmail;
			if ( exmails != null){
		                var exmail   = exmails.split(",");
		                var jmlexmail= exmail.length;
		                if (jmlexmail > 0 ){
		                    $("div.controls.exmails").find("input").remove();
		                    $("div.controls.exmails").find("button").remove();
		                    for(var i=0;i<jmlexmail;i++){
		                        iexmail++;
		                        $("div.controls.exmails").append("<input class='span2 exmailke"+i+"' type='text' name='exmail[]' value='"+exmail[i]+"'>"+'<button title="Delete house telp no.?" class="btn-mini btn btn-danger exmailke'+i+'" onclick="remexmail(\''+i+'\')">x</button>');
		                    }
		                }else{}
                        }
                        // end of no telp ktp addr============================================
                        $("#inemail").val(data.InternalEmail);
//                        $("#exmail").val();
                        $("#laddress").val(data.LiveAddress);//textarea
                        
                        $("#ktpaddress").val(data.KTPAddress);//textarea
                        
                        $("#famcert").val(data.FamilyMemberCertificate);
                        $("#marrcert").val(data.MarriageCertificate);
                        
                        $("#nobpjsemp").val(data.NoBPJSEmp);
                        $("#nobpjshlt").val(data.NoBPJSHlt);
                        $("#famcertno").val(data.NoFamCert);
                        $("#lkodepos").val(data.LivePostalCode);
                        $("#ktpkodepos").val(data.KTPPostalCode);
                        
//                        ====== load alamat here ===============
                        loadprovince();
                        setTimeout(function (){
                            $("#laddrprov").find("option[value='"+data.LiveProvince+"']").prop("selected",true);
                            $("#kaddrprov").find("option[value='"+data.KTPProvince+"']").prop("selected",true);
                            loadcities("l",data.LiveProvince);
                            loadcities("k",data.KTPProvince);
                            setTimeout(function (){
                                $("#laddrcity").val(data.LiveCity);
                                $("#kaddrcity").val(data.KTPCity);
                                loadsubs("l",data.LiveCity);
                                loadsubs("k",data.KTPCity);
                                setTimeout(function (){
                                    $("#laddrsub").val(data.LiveSubdistrict);
                                    $("#kaddrsub").val(data.KTPSubdistrict);
                                    loadvlgs("l",data.LiveSubdistrict);
                                    loadvlgs("k",data.KTPSubdistrict);
                                    setTimeout(function (){
                                        $("#laddrvlg").val(data.LiveVillage);
                                        $("#kaddrvlg").val(data.KTPVillage);
                                        $("#liverw").val(data.LiveRW);
                                        $("#livert").val(data.LiveRT);
                                        $("#ktprw").val(data.KTPRW);
                                        $("#ktprt").val(data.KTPRT);
                                    },"100");
                                },"100");
                            },"100");
                        },"100");

                        if (data.F1 == '1'){
                            $(".ribtab1").hide({
                                duration : 500,
                                easing   : "blind"
                            });
                        }
                        if (data.F2 == '1'){
                            $(".ribtab2").hide({
                                duration : 500,
                                easing   : "blind"
                            });
                        }
                        if (data.F3 == '1'){
                            $(".ribtab3").hide({
                                duration : 500,
                                easing   : "blind"
                            });
                        }
                        if (data.F4 == '1'){
                            $(".ribtab4").hide({
                                duration : 500,
                                easing   : "blind"
                            });
                        }
                        if (data.F5 == '1'){
                            $(".ribtab5").hide({
                                duration : 500,
                                easing   : "blind"
                            });
                        }
                        if (data.F6 == '1'){
                            $(".ribtab6").hide({
                                duration : 500,
                                easing   : "blind"
                            });
                        }
                        if (data.F7 == '1'){
                            $(".ribtab7").hide({
                                duration : 500,
                                easing   : "blind"
                            });
                        }
                        var jmlstp  = vstp.reduce(function (a,b){ return (a*1)+(b*1);},0);
                        if (jmlstp < 5){
                            cekupdate();
                            showalert("tab1");
                        }
                        if (data.F2f1 == '0'){
                            cekupdatejob();
                            showalert("tab2");
                            $(".ifnol").show();
                            $("#jchanges").prop("checked",false);
                        }else{
                            $(".ifnol").hide();
                            $("#jchanges").prop("checked",true);
                        }
                    }
                });
            }
            function showalert(tab){
                $(".alert.alert-"+tab).show({
                    effect  : "fade"
                });
            }
            function upd_ftab(tab){
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/upd_ftab",
                    data    : "tab="+tab,
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
                        if (data.status == "oke"){
                            $(".ribtab"+tab).hide({
                                duration : 500,
                                easing   : "blind"
                            });
                        }
                        
                    },
                    error   : function (a){
                        alert(a.responseText+"\n"+a.statusText);
                    }
                });
            }
            function backtohome(){
                window.location.href = "<?php echo $base_url;?>";
            }
            function tostep(step,bef){
//                var stepbef = step-1;
                $(".step"+bef).hide({
                    duration : 300,
                    effect   : "fade",
                    complete : function (){
                        $(".step"+step).show({
                            duration : 300,
                            effect   : "fade",
                            complete : function (){}
                        });
                    }
                });
            }
//            function prevstep(step){
//                var stepbef = step+1;
//                $(".step"+stepbef).hide({
//                    duration : 300,
//                    easing   : "slide",
//                    complete : function (){
//                        $(".step"+step).show({
//                            duration : 300,
//                            easing   : "slide",
//                            complete : function (){}
//                        });
//                    }
//                });
//            }
            function savepersonal(){
                var fname   = $("#fname").val();
                var nname   = $("#nname").val();
                var pbirth   = $("#pbirth").val();
                var dbirth   = $("#dbirth").val();
                var bheight   = $("#bheight").val();
                var bweight   = $("#bweight").val();
                var gender   = $("#gender").val();
                var tblood   = $("#tblood").val();
                var czship   = $("#czship").val();
                var religion   = $("#religion").val();
                var noktp   = $("#noktp").val();
                var nonpwp   = $("#nonpwp").val();
                var nojamsos   = $("#nojamsos").val();
                var nokpj   = $("#nokpj").val();
                var abank   = $("#abank").val();
                var marital   = $("input[type='radio'][name='marital']:checked").val();//radio
                var coupname   = $("#coupname").val();
                var couplektp   = $("#couplektp").val();
                var nchild   = $("#nchild").val();
                var nohp   = getnohp();
                var inemail   = $("#inemail").val();
                var exmail   = getexmail();
                var laddress   = $("#laddress").val();
                var laddressph   = gettelpl();
                var ktpaddress   = $("#ktpaddress").val();
                var ktpaddressph   = gettelpk();
                var famcert = $("#famcert").val();
                var marrcert = $("#marrcert").val();
                
                var nobpjsemp   = $("#nobpjsemp").val();
                var nobpjshlt   = $("#nobpjshlt").val();
                var famcertno  = $("#famcertno").val();
                
                var laddrprov   = $("#laddrprov").val();
                var laddrcity   = $("#laddrcity").val();
                var laddrsub    = $("#laddrsub").val();
                var laddrvlg    = $("#laddrvlg").val();
                var kaddrprov   = $("#kaddrprov").val();
                var kaddrcity   = $("#kaddrcity").val();
                var kaddrsub    = $("#kaddrsub").val();
                var kaddrvlg    = $("#kaddrvlg").val();
                
                var liverw      = $("#liverw").val();
                var livert      = $("#livert").val();
                var ktprw       = $("#ktprw").val();
                var ktprt       = $("#ktprt").val();

                var ktpkodepos  = $("#ktpkodepos").val();
                var livekodepos = $("#lkodepos").val();
                
                var f1s1    = $("#f1s1").val();
                var f1s2    = $("#f1s2").val();
                var f1s3    = $("#f1s3").val();
                var f1s4    = $("#f1s4").val();
                var f1s5    = $("#f1s5").val();
                var csteps  = new Array();
                csteps.push(f1s1);csteps.push(f1s2);csteps.push(f1s3);csteps.push(f1s4);csteps.push(f1s5);
                
//////                alert(ktpaddressph);
//                alert(laddressph+"|"+ktpaddressph);
                loading();
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/save_personal",
                    data    : "famcert="+famcert+"&marrcert="+marrcert+"&fname="+fname.toUpperCase()+"&nname="+nname.toUpperCase()+"&pbirth="+pbirth.toUpperCase()+"&dbirth="+dbirth+"&bheight="+bheight+"&bweight="+bweight+"&gender="+gender+"&tblood="+tblood.toUpperCase()+"&czship="+czship.toUpperCase()+"&religion="+religion.toUpperCase()+"&noktp="+noktp+"&nonpwp="+nonpwp+"&nojamsos="+nojamsos+"&nokpj="+nokpj+"&abank="+abank+"&marital="+marital+"&coupname="+coupname.toUpperCase()+"&couplektp="+couplektp+"&nchild="+nchild+"&nohp="+nohp+"&inemail="+inemail+"&exmail="+exmail+"&laddress="+laddress+"&laddressph="+laddressph+"&ktpaddress="+ktpaddress+"&ktpaddressph="+ktpaddressph+"&nobpjsemp="+nobpjsemp+"&nobpjshlt="+nobpjshlt+"&famcertno="+famcertno+"&laddrprov="+laddrprov+"&laddrcity="+laddrcity+"&laddrsub="+laddrsub+"&laddrvlg="+laddrvlg+"&kaddrprov="+kaddrprov+"&kaddrcity="+kaddrcity+"&kaddrsub="+kaddrsub+"&kaddrvlg="+kaddrvlg+"&liverw="+liverw+"&livert="+livert+"&ktprw="+ktprw+"&ktprt="+ktprt+"&ktpkodepos="+ktpkodepos+"&livekodepos="+livekodepos+"&f1s1="+f1s1+"&f1s2="+f1s2+"&f1s3="+f1s3+"&f1s4="+f1s4+"&f1s5="+f1s5,
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
//                        alert(data.toSource())
                        bootbox.alert("Your personal data was saved!", function (){
                            bootbox.hideAll();
                            $.gritter.add({
                                title: 'Data Updated!',
                                text: "Your personal data was updated"
                            });
                            upd_ftab("1");
                            chgtab("2");
                            $(".tab-pane").removeClass("active");
                            $("li.tabatas").removeClass("active");
                            $("li.tab2").addClass("active");
                            $("div#tab2").addClass("active");
                        });
                        csteps  = csteps.reduce(function (a,b){ return (a*1)+(b*1);});
                        if (csteps == 5){
                            $(".alert-tab1").remove();
                        }
                    },
                    error   : function (a){
                        alert(a.responseText+"\n"+a.statusText);
                    }
                });
            }
            function hideallctntab(){
                $(".btnaddfam").hide();
                $(".btnaddedu").hide();
                $(".btnaddtnc").hide();
                $(".btnaddlang").hide();
                $(".btnaddwork").hide();
                $(".taballfamily").hide();
                $(".taballeducation").hide();
                $(".taballtnc").hide();
                $(".taballlang").hide();
                $(".taballwork").hide();
            }
            function showaddmenu(){
                $(".addfamily").show();
                $(".addedu").show();
                $(".addtnc").show();
                $(".addlang").show();
                $(".addwork").show();
            }
            function loading(){
                bootbox.alertloading("<center>Processing. Please wait !!!<br><img src='<?php echo $base_url; ?>public/avatar/76.GIF'></center>");
            }
            function chgtab(tab){
                hideallctntab();
                showaddmenu();
                $("li.tabatas.span3").addClass("span1");
                $("li.tabatas.span3").removeClass("span3");
                $("li.tabatas.tab"+tab).removeClass("span1");
                $("li.tabatas.tab"+tab).addClass("span3");
                if (tab == "1"){
                    upd_ftab("1");
                    loadpersonal();
                    $(".step1").hide({
                        complete : function (){
                            $(".step1").show({
                                duration : 300,
                                easing : "slide",
                                complete: function (){
                                    
                                }
                            });
                        }
                    });
                    $(".step2").hide();
                    $(".step3").hide();
                    $(".step4").hide();
                    $(".step5").hide();
                }
                if (tab == "2"){
                    upd_ftab("2");
                    load_job();
                    $(".jobdtl").hide({
                        duration : 300,
                        easing   : "slide",
                        complete : function (){
                            $(".jobdtl").show({
                                duration : 300,
                                easing   : "slide",
                                complete : function (){
                                    
                                }
                            });
                        }
                    });
                }
                if (tab == "3"){
//jika informasi ibu sudah dimasukkan baru fungsi di bawah ini aktif
<?php if($checkmother!='empty'){?>
                    upd_ftab("3");
<?php } ?>
//                    alert(tab);
                    get_family();
                    $(".addfamily").hide({
                        complete : function (){
                            $(".taballfamily").show({
                                duration : 300,
                                easing   : "slide",
                                complete : function (){
                                    
                                    $(".btnaddfam").show();
                                }
                            });
                        }
                    }); 
                }
                if (tab == "4"){
                    upd_ftab("4");
                    get_education();
                    $(".addedu").hide({
                        complete : function (){
                            $(".taballeducation").show({
                                duration : 300,
                                easing   : "slide",
                                complete : function (){
                                    
                                    $(".btnaddedu").show();
                                }
                            });
                        }
                    }); 
                }
                if (tab == "5"){
                    upd_ftab("5");
                    get_tnc();
                    $(".addtnc").hide({                        
                        complete : function (){
                            $(".taballtnc").show({
                                duration : 300,
                                easing   : "slide",
                                complete : function (){
                                    
                                    $(".btnaddtnc").show();
                                }
                            });
                        }
                    }); 
                }
                if (tab == "6"){
                    upd_ftab("6");
                    get_lang();
                    $(".addlang").hide({
                        complete : function (){
                            $(".taballlang").show({
                                duration : 300,
                                easing   : "slide",
                                complete : function (){
                                    
                                    $(".btnaddlang").show();
                                }
                            });
                        }
                    }); 
                }
                if (tab == "7"){
                    upd_ftab("7");
                    get_work();
                    $(".addwork").hide({
                        complete : function (){
                            $(".taballwork").show({
                                duration : 300,
                                easing   : "slide",
                                complete : function (){                                    
                                    $(".btnaddwork").show();
                                }
                            });
                        }
                    });
                }
                
            }
//            job
            function load_job(){
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/get_job",
                    data    : "",
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
                        $("#idemp").val(data.IDEmployee);
			if (data.IDEmployee == null){
				$("#idemp").keydown(true);
			}else{
				$("#idemp").keydown(false);
			}
                        $("#idparent").val(data.IDEmployeeParent);
                        $("#jobloc").val(data.Location);
                        $("#jobgrp").val(data.JobGroup);
                        $("#depart").val(data.Department);
                        $("#jobpos").val(data.Position);
                        $("#unitjob").val(data.Unit);
                        $("#empstat").val(data.EmployeeStatus);
                        $("#hiredate").val(data.HireDate);
                        $("#datefirst").val(data.DateFirstJoin);
                        $("#dateprob").val(data.DatePassProbation);
                        $("#contnew").val(data.DateNewContract);
                        $("#contend").val(data.DateEndContract);
                        
                    }
                });
            }
            function save_job(){
                var jobloc  = $("#jobloc").val();
                var idparent= $("#idparent").val();
                var empstat = $("#empstat").val();
                var jobgrp  = $("#jobgrp").val();
                var depart  = $("#depart").val();
                var jobpos  = $("#jobpos").val();
                var unitjob = $("#unitjob").val();
                var changes = $("#jchanges:checked").val();
                loading();
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/save_job",
                    data    : "empstat="+empstat.toUpperCase()+"&idparent="+idparent+"&jobloc="+jobloc+"&jobgrp="+jobgrp+"&depart="+depart+"&jobpos="+jobpos.toUpperCase()+"&unitjob="+unitjob.toUpperCase(),
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
			//alert(data);
                        if (data.status == "oke"){
                            bootbox.alert("Data saved!",function (){
                                bootbox.hideAll();
                                chgtab("2");
                                $.gritter.add({
                                    title: 'Data Successfully Saved!',
                                    text: "You can move to the next tab or recheck the data you inserted"
                                }); 
                            });
                            if (changes == "1"){
                                upd_ftab("2f1");
                                $(".alert-tab2").remove();
                                $(".ifnol").hide();
                                unbindformjob();
                            }
                        }
                    },
		    error : function (a,b){
			alert(a.toSource()+"|"+b);
		    }
                });
                
            }
// family script ===========================================================
            function get_family(){
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/get_family",
                    data    : "",
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
//                        alert(data.toSource());
                        $("#famlastid").val(data.lastid.lastid);
                        $("#allfamily").empty();
                        var res = "";
                        for (var i =0; i < data.data.length ; i++){
                            var btn_edit = "<button class='btn btn-mini btn-warning' onclick='edit_fam(\""+data.data[i].IDFamily+"\")'><i class='icon-pencil'></i></button>";
                            var btn_del = "<button class='btn btn-mini btn-danger' onclick='del_fam(\""+data.data[i].IDFamily+"\")'><i class='icon-trash'></i></button>";
                            res = res + "<tr class='selectable'><td class='upper'>"+data.data[i].FamilyMember+"</td><td>"+data.data[i].NoKTP+"</td><td>"+data.data[i].Name+"</td><td>"+data.data[i].Age+"</td><td>"+data.data[i].Address+"</td><td>"+data.data[i].Education+"</td><td>"+data.data[i].Occupation+"</td><td><center>"+btn_edit+btn_del+"</center></td></tr>";
                        }
                        $("#allfamily").append(res);
                        //$("#allfamily").after(data.mother);
                    }
                });
            }
            function edit_fam(famid){
                $("#famproses").val("pedit_family");
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/edit_family",
                    data    : "famid="+famid,
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
                        $("#fammember").find("option[value='"+data.FamilyMember+"']").prop("selected",true);
                        $("#famid").val(famid);
                        $("#famfname").val(data.Name); 
                        $("#famage").val(data.Age);
                        $("#famaddress").val(data.Address);
                        $("#famedu").val(data.Education);
                        $("#famoccu").val(data.Occupation);
                        $("#famnoktp").val(data.NoKTP);
                        $("#famlastid").val("0");
                        $("#fambplace").val(data.BirthPlace);
                        $("#fambdate").val(data.BirthDate);
                        $(".taballfamily").hide({
                            duration: 300,
                            easing : "blind",
                            complete : function (){
                                $(".btnaddfam").hide();
                                $(".addfamily").show({
                                    duration : 300,
                                    easing  : "slide"
                                });
                            }
                        });
                    }
                });
            }
            function del_fam(famid){
//                alert(famid);
                bootbox.confirm("You are going to delete your family member. Continue?", function (res){
                    if (res == true){
                        loading();
                        $.ajax({
                            url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/del_family",
                            data    : "famid="+famid,
                            type    : "post",
                            dataType: "json",
                            cache   : false,
                            success : function (data){
                                bootbox.alert(data.msg, function (){
                                    caddfam();
                                    bootbox.hideAll();
                                    $.gritter.add({
                                        title: 'Data Deleted!',
                                        text: "Data Successfully deleted"
                                    });
                                });
                            }
                        });
                    }
                    else {
                        
                    }
                });
            }
            function addfammember(){
                $("#famproses").val("padd_family");
                $(".taballfamily").hide({
                    duration: 300,
                    easing : "blind",
                    complete : function (){
                        $(".btnaddfam").hide();
                        $("#fammember").find("option").prop("selected",false);
                        $("#famid").val("");
                        $("#famfname").val(""); 
                        $("#famage").val("");
                        $("#famaddress").val("");
                        $("#famedu").val("");
                        $("#famoccu").val("");
                        $("#famnoktp").val("");
                        $("#fambplace").val("");
                        $("#fambdate").val("");
//                            $("div.massuser").effect("slide","slow");
                        $(".addfamily").show({
                            duration : 300,
                            easing  : "slide"
                        });
                    }
                });
                
            }
            function caddfam(){
                $(".addfamily").hide({
                    duration: 300,
                    easing : "blind",
                    complete : function (){
//                            $("div.massuser").effect("slide","slow");
                        $(".taballfamily").show({
                            duration : 300,
                            easing  : "slide",
                            complete : function (){
                                $(".btnaddfam").show();
                                chgtab("3");
                            }
                        });
                    }
                });
            }
            function save_family(){
				
                var proses  = $("#famproses").val();
                var member  = $("#fammember").val();
                var famid   = $("#famid").val();
                var fname   = $("#famfname").val(); 
                var fage    = $("#famage").val();
                var famaddr = $("#famaddress").val();
                var famedu  = $("#famedu").val();
                var famoccu = $("#famoccu").val();
                var famnoktp= $("#famnoktp").val();
                var lastid  = $("#famlastid").val();
                var fambplace   = $("#fambplace").val();
                var fambdate    = $("#fambdate").val();
                var nextid  = (lastid*1)+1;
                if (fname != ""){
                    loading();
                    $.ajax({ 
                        url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/"+proses,
                        data    : "famid="+famid+"&nextid="+nextid+"&member="+member+"&fname="+fname.toUpperCase()+"&fage="+fage+"&faddress="+famaddr+"&fedu="+famedu.toUpperCase()+"&foccu="+famoccu+"&fnoktp="+famnoktp+"&fambplace="+fambplace+"&fambdate="+fambdate,
                        type    : "post",
                        dataType: "json",
                        cache   : false,
                        success : function (data){
                            if (data != null){
                                bootbox.alert(data.msg,function (){
                                    caddfam();
                                    bootbox.hideAll();
                                    $.gritter.add({
                                        title: 'Data Successfully Saved!',
                                        text: "You can move to the next tab or recheck the data you inserted"
                                    }); 
                                });
                                if (data.member == "mother"){
									$("[div-alert='alert_mother']").remove();
									upd_ftab("3");
								}
                            }
                        },
                        error	: function (a){
							alert(a.responseText);
							bootbox.hideAll();
						}
                    });                    
                }
                else {
                    $.gritter.add({
                        title: 'Please Fill The Name Field!',
                        text: "You can't save data without filling the name field"
                    });  
                }
            }
// end of family script ===========================================================
// education script ===========================================================
            function get_education(){
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/get_education",
                    data    : "",
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
//                        alert(data.toSource());
                        $("#edulastid").val(data.lastid.lastid);
                        $("#alledu").empty();
                        var res = "";
                        for (var i =0; i < data.data.length ; i++){
                            var btn_edit = "<button class='btn btn-mini btn-warning' onclick='edit_edu(\""+data.data[i].IDEducation+"\")'><i class='icon-pencil'></i></button>";
                            var btn_del = "<button class='btn btn-mini btn-danger' onclick='del_edu(\""+data.data[i].IDEducation+"\")'><i class='icon-trash'></i></button>";
                            res = res + "<tr class='selectable'><td class='upper'>"+data.data[i].EducationLevel+"</td><td>"+data.data[i].Course+"</td><td>"+data.data[i].SchoolName+"</td><td>"+data.data[i].City+"</td><td>"+data.data[i].YearFrom+"</td><td>"+data.data[i].YearUntil+"</td><td>"+data.data[i].Certificate+"</td><td><center>"+btn_edit+btn_del+"</center></td></tr>";
                        }
                        $("#alledu").append(res);
                    }
                });
            }
            function edit_edu(eduid){
                $("#eduproses").val("pedit_education");
                
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/edit_education",
                    data    : "eduid="+eduid,
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
//                        alert("oke");
                        $("#edulevel").find("option[value='"+data.EducationLevel+"']").prop("selected",true);
                        $("#eduid").val(eduid);
                        $("#educourse").val(data.Course); 
                        $("#eduname").val(data.SchoolName);
                        $("#educity").val(data.City);
                        $("#edufrom").val(data.YearFrom);
                        $("#edutill").val(data.YearUntil);
                        $("#educert").find("option[value='"+data.Certificate+"']").prop("selected",true);
                        $("#edulastid").val("0");
                        $(".taballeducation").hide({
                            duration: 300,
                            easing : "blind",
                            complete : function (){
                                $(".btnaddedu").hide();
                                $(".addedu").show({
                                    duration : 300,
                                    easing  : "slide"
                                });
                            }
                        });
                    }
                });
            }
            function del_edu(eduid){
//                alert(idfam);
                bootbox.confirm("You are going to delete your education background from the list. Continue?", function (res){
                    if (res == true){
                        loading();
                        $.ajax({
                            url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/del_education",
                            data    : "eduid="+eduid,
                            type    : "post",
                            dataType: "json",
                            cache   : false,
                            success : function (data){
                                bootbox.alert(data.msg, function (){
                                    caddedu();
                                    bootbox.hideAll();
                                    $.gritter.add({
                                        title: 'Data Deleted!',
                                        text: "Data Successfully deleted"
                                    });
                                });
                            }
                        });
                    }
                    else {
                        
                    }
                });
            }
            function addedu(){
                $("#eduproses").val("padd_education");
                $(".taballeducation").hide({
                    duration: 300,
                    easing : "blind",
                    complete : function (){
                        $(".btnaddedu").hide();
                        $("#edulevel").find("option").prop("selected",false);
                        $("#eduid").val("");
                        $("#educourse").val(""); 
                        $("#eduname").val("");
                        $("#educity").val("");
                        $("#edufrom").val("");
                        $("#edutill").val("");
                        $("#educert").find("option").prop("selected",false);
//                            $("div.massuser").effect("slide","slow");
                        $(".addedu").show({
                            duration : 300,
                            easing  : "slide"
                        });
                    }
                });
                
            }
            function caddedu(){
                $(".addedu").hide({
                    duration: 300,
                    easing : "blind",
                    complete : function (){
//                            $("div.massuser").effect("slide","slow");
                        $(".taballeducation").show({
                            duration : 300,
                            easing  : "slide",
                            complete : function (){
                                $(".btnaddedu").show();
                                chgtab("4");
                            }
                        });
                    }
                });
            }
            function save_education(){
                var eduid   = $("#eduid").val();
                var proses  = $("#eduproses").val();
                var level   = $("#edulevel").val();
                var course  = $("#educourse").val();
                var ename   = $("#eduname").val(); 
                var ecity   = $("#educity").val();
                var efrom   = $("#edufrom").val();
                var etill   = $("#edutill").val();
                var ecert   = $("#educert").val();
                var lastid  = $("#edulastid").val();
                var nextid  = (lastid*1)+1;
                if (ename != '' && ecity != '' && efrom != '' && etill != ''){
                    loading();
                    $.ajax({ 
                        url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/"+proses,
                        data    : "eduid="+eduid+"&nextid="+nextid+"&level="+level+"&ename="+ename.toUpperCase()+"&ecity="+ecity.toUpperCase()+"&efrom="+efrom+"&etill="+etill+"&ecert="+ecert+"&course="+course.toUpperCase(),
                        type    : "post",
                        dataType: "json",
                        cache   : false,
                        success : function (data){
                            if (data != null){
                                bootbox.alert(data.msg,function (){
                                    caddedu();
                                    bootbox.hideAll();
                                    $.gritter.add({
                                        title: 'Data Successfully Saved!',
                                        text: "You can move to the next tab or recheck the data you inserted"
                                    }); 
                                });
                            }
                        }
                    });                    
                }
                else{
                    if (ename == ''){
                        $.gritter.add({
                            title: 'Please Fill The Name Field!',
                            text: "You can't save data without filling the name field"
                        });                         
                    }
                    if (ecity == ''){
                        $.gritter.add({
                            title: 'Please Fill The City Field!',
                            text: "You can't save data without filling the city name field"
                        });                         
                    }
                    if (efrom == ''){
                        $.gritter.add({
                            title: 'Please Fill The Year Field!',
                            text: "You can't save data without filling the year field"
                        });                         
                    }
                }
            }
// end of education script ===========================================================
// tnc script ===========================================================
            function get_tnc(){
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/get_tnc",
                    data    : "",
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
//                        alert(data.toSource());
                        $("#tnclastid").val(data.lastid.lastid);
                        $("#alltnc").empty();
                        var res = "";
                        for (var i =0; i < data.data.length ; i++){
                            var btn_edit = "<button class='btn btn-mini btn-warning' onclick='edit_tnc(\""+data.data[i].IDCourse+"\")'><i class='icon-pencil'></i></button>";
                            var btn_del = "<button class='btn btn-mini btn-danger' onclick='del_tnc(\""+data.data[i].IDCourse+"\")'><i class='icon-trash'></i></button>";
                            res = res + "<tr class='selectable'><td class='upper'>"+data.data[i].CourseProgram+"</td><td>"+data.data[i].CourseFacilitator+"</td><td>"+data.data[i].City+"</td><td>"+data.data[i].Duration+"</td><td>"+data.data[i].YearFrom+"</td><td>"+data.data[i].YearUntil+"</td><td><center>"+btn_edit+btn_del+"</center></td></tr>";
                        }
                        $("#alltnc").append(res);
                    }
                });
            }
            function edit_tnc(tncid){
                $("#tncproses").val("pedit_tnc");
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/edit_tnc",
                    data    : "tncid="+tncid,
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
 
                        $("#tncid").val(tncid);
                        $("#tncprogram").val(data.CourseProgram);
                        $("#tncfac").val(data.CourseFacilitator);
                        $("#tnccity").val(data.City);
                        $("#tncdur").val(data.Duration);
                        $("#tncfrom").val(data.YearFrom);
                        $("#tnctill").val(data.YearUntil);
                        $("#edulastid").val("0");
                        $(".taballtnc").hide({
                            duration: 300,
                            easing : "blind",
                            complete : function (){
                                $(".btnaddtnc").hide();
                                $(".addtnc").show({
                                    duration : 300,
                                    easing  : "slide"
                                });
                            }
                        });
                    }
                });
            }
            function del_tnc(tncid){
//                alert(idfam);
                bootbox.confirm("You are going to delete your Training and Course from the list. Continue?", function (res){
                    if (res == true){
                        loading();
                        $.ajax({
                            url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/del_tnc",
                            data    : "tncid="+tncid,
                            type    : "post",
                            dataType: "json",
                            cache   : false,
                            success : function (data){
                                bootbox.alert(data.msg, function (){
                                    caddtnc();
                                    bootbox.hideAll();
                                    $.gritter.add({
                                        title: 'Data Deleted!',
                                        text: "Data Successfully deleted"
                                    });
                                });
                            }
                        });
                    }
                    else {
                        
                    }
                });
            }
            function addtnc(){
                $("#tncproses").val("padd_tnc");
                $(".taballtnc").hide({
                    duration: 300,
                    easing : "blind",
                    complete : function (){
                        $(".btnaddtnc").hide();
                        $("#tncprogram").val("");
                        $("#tncid").val("");
                        $("#tncfac").val(""); 
                        $("#tnccity").val("");
                        $("#tncdur").val("");
                        $("#tncfrom").val("");
                        $("#tnctill").val("");
                        $(".addtnc").show({
                            duration : 300,
                            easing  : "slide"
                        });
                    }
                });
                
            }
            function caddtnc(){
                $(".addtnc").hide({
                    duration: 300,
                    easing : "blind",
                    complete : function (){
//                            $("div.massuser").effect("slide","slow");
                        $(".taballtnc").show({
                            duration : 300,
                            easing  : "slide",
                            complete : function (){
                                $(".btnaddtnc").show();
                                chgtab("5");
                            }
                        });
                    }
                });
            }
            function save_tnc(){
                var tncid   = $("#tncid").val();
                var proses  = $("#tncproses").val();                
                var program = $("#tncprogram").val();
                var fac     = $("#tncfac").val();
                var city    = $("#tnccity").val();
                var duration= $("#tncdur").val();
                var tncfrom = $("#tncfrom").val();
                var tnctill = $("#tnctill").val();
                var lastid  = $("#tnclastid").val();
                var nextid  = (lastid*1)+1;
                if (fac != '' && city != '' && duration != '' && tncfrom != '' && tnctill != ''){
                    loading();
                    $.ajax({ 
                        url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/"+proses,
                        data    : "nextid="+nextid+"&tncid="+tncid+"&program="+program.toUpperCase()+"&facilitator="+fac.toUpperCase()+"&city="+city.toUpperCase()+"&duration="+duration+"&from="+tncfrom+"&until="+tnctill,
                        type    : "post",
                        dataType: "json",
                        cache   : false,
                        success : function (data){
                            if (data != null){
                                bootbox.alert(data.msg,function (){
                                    caddtnc();
                                    bootbox.hideAll();
                                    $.gritter.add({
                                        title: 'Data Successfully Saved!',
                                        text: "You can move to the next tab or recheck the data you inserted"
                                    }); 
                                });
                            }
                        }
                    });                    
                }
                else{
                    $.gritter.add({
                        title: 'Please Fill All The Fields!',
                        text: "You can't save data without filling all the fields"
                    });                     
                }
            }
// end of tnc script ===========================================================
// languages script ===========================================================
            function get_lang(){
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/get_language",
                    data    : "",
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
//                        alert(data.toSource());
                        $("#langlastid").val(data.lastid.lastid);
                        $("#alllang").empty();
                        var res = "";
                        for (var i =0; i < data.data.length ; i++){
                            var btn_edit = "<button class='btn btn-mini btn-warning' onclick='edit_lang(\""+data.data[i].IDLanguage+"\")'><i class='icon-pencil'></i></button>";
                            var btn_del = "<button class='btn btn-mini btn-danger' onclick='del_lang(\""+data.data[i].IDLanguage+"\")'><i class='icon-trash'></i></button>";
                            res = res + "<tr class='selectable'><td class='upper'>"+data.data[i].Language+"</td><td>"+data.data[i].Listening+"</td><td>"+data.data[i].Reading+"</td><td>"+data.data[i].Conversation+"</td><td>"+data.data[i].Writing+"</td><td><center>"+btn_edit+btn_del+"</center></td></tr>";
                        }
                        $("#alllang").append(res);
                    }
                });
            }
            function edit_lang(langid){
                $("#langproses").val("pedit_language");
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/edit_language",
                    data    : "langid="+langid,
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
 
                        $("#langid").val(langid);
                        $("#langlanguage").val(data.Language);
                        $("#langlisten").val(data.Listening);
                        $("#langread").val(data.Reading);
                        $("#langconv").val(data.Conversation);
                        $("#langwrite").val(data.Writing);
                        $("#langlastid").val("0");
                        $(".taballlang").hide({
                            duration: 300,
                            easing : "blind",
                            complete : function (){
                                $(".btnaddlang").hide();
                                $(".addlang").show({
                                    duration : 300,
                                    easing  : "slide"
                                });
                            }
                        });
                    }
                });
            }
            function del_lang(langid){
//                alert(idfam);
                bootbox.confirm("You are going to delete your Language from the list. Continue?", function (res){
                    if (res == true){
                        loading();
                        $.ajax({
                            url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/del_language",
                            data    : "langid="+langid,
                            type    : "post",
                            dataType: "json",
                            cache   : false,
                            success : function (data){
                                bootbox.alert(data.msg, function (){
                                    caddlang();
                                    bootbox.hideAll();
                                    $.gritter.add({
                                        title: 'Data Deleted!',
                                        text: "Data Successfully deleted"
                                    });
                                });
                            }
                        });
                    }
                    else {
                        
                    }
                });
            }
            function addlang(){
                $("#langproses").val("padd_language");
                $(".taballlang").hide({
                    duration: 300,
                    easing : "blind",
                    complete : function (){
                        $(".btnaddlang").hide();
                        $("#langlanguage").val("");
                        $("#langlisten").val("");
                        $("#langread").val("");
                        $("#langconv").val("");
                        $("#langwrite").val("");
                        $(".addlang").show({
                            duration : 300,
                            easing  : "slide"
                        });
                    }
                });
                
            }
            function caddlang(){
                $(".addlang").hide({
                    duration: 300,
                    easing : "blind",
                    complete : function (){
                        $(".taballlang").show({
                            duration : 300,
                            easing  : "slide",
                            complete : function (){
                                $(".btnaddlang").show();
                                chgtab("6");
                            }
                        });
                    }
                });
            }
            function save_lang(){
                var langid  = $("#langid").val();
                var proses  = $("#langproses").val();                
                var language= $("#langlanguage").val();
                var listen  = $("#langlisten").val();
                var read    = $("#langread").val();
                var convers = $("#langconv").val();
                var write   = $("#langwrite").val();
                var lastid  = $("#langlastid").val();
                var nextid  = (lastid*1)+1;
                if (language != '' && listen != '' && read != '' && convers != '' && write != ''){
                    loading();                    
                    $.ajax({ 
                        url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/"+proses,
                        data    : "nextid="+nextid+"&langid="+langid+"&language="+language.toUpperCase()+"&listen="+listen+"&read="+read+"&convers="+convers+"&write="+write,
                        type    : "post",
                        dataType: "json",
                        cache   : false,
                        success : function (data){
//                            alert(data);
                            if (data != null){
                                bootbox.alert(data.msg,function (){
                                    caddlang();
                                    bootbox.hideAll();
                                    $.gritter.add({
                                        title: 'Data Successfully Saved!',
                                        text: "You can move to the next tab or recheck the data you inserted"
                                    }); 
                                });
                            }
                        },
                        error   : function (data){
                            alert(data.toSource());
                        }
                    });
                }else{
                    $.gritter.add({
                        title: 'Please Fill All The Fields!',
                        text: "You can't save data without filling all the fields"
                    });                     
                }
            }
// end of lang script ===========================================================
// working experience script ===========================================================
            function get_work(){
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/get_work",
                    data    : "",
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
//                        alert(data.toSource());
                        $("#worklastid").val(data.lastid.lastid);
                        $("#allwork").empty();
                        var res = "";
                        for (var i =0; i < data.data.length ; i++){
                            var btn_edit = "<button class='btn btn-mini btn-warning' onclick='edit_work(\""+data.data[i].IDWorkExp+"\")'><i class='icon-pencil'></i></button>";
                            var btn_del = "<button class='btn btn-mini btn-danger' onclick='del_work(\""+data.data[i].IDWorkExp+"\")'><i class='icon-trash'></i></button>";
                            res = res + "<tr class='selectable'><td class='upper'>"+data.data[i].CompanyName+"</td><td>"+data.data[i].CompanyAddress+"</td><td>"+data.data[i].CompanyPhone+"</td><td>"+data.data[i].Position+"</td><td>"+data.data[i].WorkDuration+"</td><td><center>"+btn_edit+btn_del+"</center></td></tr>";
                        }
                        $("#allwork").append(res);
                    }
                });
            }
            function edit_work(workid){
                $("#workproses").val("pedit_work");
                $.ajax({
                    url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/edit_work",
                    data    : "workid="+workid,
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
 
                        $("#workid").val(workid);
                        $("#workcomp").val(data.CompanyName);
                        $("#workaddress").append(data.CompanyAddress);
                        $("#workphone").val(data.CompanyPhone);
                        $("#workpos").val(data.Position);
                        $("#workdur").val(data.WorkDuration);
                        $("#worklastid").val("0");
                        $(".taballwork").hide({
                            duration: 300,
                            easing : "blind",
                            complete : function (){
                                $(".btnaddwork").hide();
                                $(".addwork").show({
                                    duration : 300,
                                    easing  : "slide"
                                });
                            }
                        });
                    }
                });
            }
            function del_work(workid){
//                alert(idfam);
                bootbox.confirm("You are going to delete your Working Experience from the list. Continue?", function (res){
                    if (res == true){
                        loading();
                        $.ajax({
                            url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/del_work",
                            data    : "workid="+workid,
                            type    : "post",
                            dataType: "json",
                            cache   : false,
                            success : function (data){
                                bootbox.alert(data.msg, function (){
                                    caddwork();
                                    bootbox.hideAll();
                                    $.gritter.add({
                                        title: 'Data Deleted!',
                                        text: "Data Successfully deleted"
                                    });
                                });
                            }
                        });
                    }
                    else {
                        
                    }
                });
            }
            function addwork(){
                $("#workproses").val("padd_work");
                $(".taballwork").hide({
                    duration: 300,
                    easing : "blind",
                    complete : function (){
                        $(".btnaddwork").hide();
                        $("#workcomp").val("");
                        $("#workaddress").val("");
                        $("#workphone").val("");
                        $("#workpos").val("");
                        $("#workdur").val("");
                        $(".addwork").show({
                            duration : 300,
                            easing  : "slide"
                        });
                    }
                });                
            }
            function caddwork(){
                $(".addwork").hide({
                    duration: 300,
                    easing : "blind",
                    complete : function (){
                        $(".taballwork").show({
                            duration : 300,
                            easing  : "slide",
                            complete : function (){
                                $(".btnaddwork").show();
                                chgtab("7");
                            }
                        });
                    }
                });
            }
            function save_work(){
                var workid  = $("#workid").val();
                var proses  = $("#workproses").val();                
                var comp    = $("#workcomp").val();
                var address = $("#workaddress").val();
                var phone   = $("#workphone").val();
                var pos     = $("#workpos").val();
                var dur     = $("#workdur").val();
                var lastid  = $("#worklastid").val();
                var nextid  = (lastid*1)+1;
//                .toUpperCase()
//                alert(workid+"|"+proses+"|"+comp+"|"+address+"|"+phone+"|"+pos+"|"+dur+"|"+nextid);
                if (comp != '' && address != '' && phone != '' && pos != '' && dur != ''){
                    loading();
                    $.ajax({ 
                        url     : ROOT.base_url+"mod_empcenter/index.php/trx02/home/"+proses,
                        data    : "nextid="+nextid+"&workid="+workid+"&comp="+comp+"&address="+address+"&phone="+phone+"&pos="+pos+"&dur="+dur,
                        type    : "post",
                        dataType: "json",
                        cache   : false,
                        success : function (data){
    //                        alert(data);
                            if (data != null){
                                bootbox.alert(data.msg,function (){
                                    caddwork();
                                    bootbox.hideAll();
                                    $.gritter.add({
                                        title: 'Data Successfully Saved!',
                                        text: "You can move to the next tab or recheck the data you inserted"
                                    }); 
                                });
                            }
                        }
                    });                    
                }
                else{
                    $.gritter.add({
                        title: 'Please Fill All The Fields!',
                        text: "You can't save data without filling all the fields"
                    });                    
                }
            }
// end of working experiences script ===========================================================
            function addnohp(){
                inohp++;
                $("div.controls.nohps").append('<input class="span2 nohpke'+inohp+' " type="text" name="nohp[]"><button title="Delete ph no.?" class="btn-mini btn btn-danger nohpke'+inohp+'" onclick="remnohp(\''+inohp+'\')">x</button>');
                ceknohp();
            }
            function remnohp(hpke){
                $("input.nohpke"+hpke).remove();
                $("button.nohpke"+hpke).remove();
                checkfield("4");
            }
            function getnohp(){
                var nohps = new Array();
                $("input[name='nohp[]']").each(function(){
                    var isi = $(this).val();
                    if (isi != ''){
                        nohps.push(isi);
                    }
//                    alert($(this).val().toSource());
                });
                nohps.join(",");
                return nohps;
            }
            function addtelpl(){
                itelpl++;
                $("div.controls.telpls").append('<input class="span2 telplke'+itelpl+'" type="text" name="telpl[]"><button title="Delete ph no.?" class="btn-mini btn btn-danger telplke'+itelpl+'" onclick="remtelpl(\''+itelpl+'\')">x</button>');
                cektelpl();
            }
            function remtelpl(hpke){
                $("input.telplke"+hpke).remove();
                $("button.telplke"+hpke).remove();
                checkfield("5");
            }
            function gettelpl(){
                
                var telpls = new Array();
                $("input[name='telpl[]']").each(function(){
                    var isi = $(this).val();
                    if (isi != ''){
                        telpls.push(isi);
                    }
//                    alert($(this).val().toSource());
                });
                telpls.join(",");
                return telpls;
            }
            function addtelpk(){
                itelpk++;
                $("div.controls.telpks").append('<input class="span2 telpkke'+itelpk+'" type="text" name="telpk[]"><button title="Delete ph no.?" class="btn-mini btn btn-danger telpkke'+itelpk+'" onclick="remtelpk(\''+itelpk+'\')">x</button>');
                cektelpk();
            }
            function remtelpk(hpke){
                $("input.telpkke"+hpke).remove();
                $("button.telpkke"+hpke).remove();
                checkfield("5");
            }
            function gettelpk(){
                
                var telpks = new Array();
                $("input[name='telpk[]']").each(function(){
                    var isi = $(this).val();
                    if (isi != ''){
                        telpks.push(isi);
                    }
//                    alert($(this).val().toSource());
                });
                telpks.join(",");
                return telpks;
            }
            function addexmail(){
                iexmail++;
                $("div.controls.exmails").append('<input class="span2 exmailke'+iexmail+'"  type="text" name="exmail[]"><button title="Delete email?" class="btn-mini btn btn-danger exmailke'+iexmail+'" onclick="remexmail(\''+iexmail+'\')">x</button>');
                cekexmail();
            }
            function remexmail(hpke){
                $("input.exmailke"+hpke).remove();
                $("button.exmailke"+hpke).remove();
                checkfield("4");
            }
            function getexmail(){
                
                var exmails = new Array();
                $("input[name='exmail[]']").each(function(){
                    var isi = $(this).val();
                    if (isi != ''){
                        exmails.push(isi);
                    }
//                    alert($(this).val().toSource());
                });
                exmails.join(",");
                return exmails;
            }
            function nochangestep(){
                $(".nochgpdet").each(function (){
                    $(this).click(function (){
                        var check   = $(this).prop("checked");
                        var step = $(this).attr("step");
                        if (check){
                            $(".istep"+step).addClass("icon-white");
                            $("#f1s"+step).val("1");
                        }else{
                            checkfield(step);
                        }
//                        $("#f1s"+step).val();
                    });
                });
            }
            function checkfield(step){
                var fields  = new Array();
                var values  = new Array();
                $("[tstp='"+step+"'][type!='radio']").each(function (){
                    var itsid   = $(this).attr("id");
                    var itsval  = $(this).val();                                
                    fields.push(itsid);
                    values.push(itsval);
                });
                if (step == '3'){
                    var valmrtl = $("[name='marital']:checked").val();
                    fields.push("marital");
                    values.push(valmrtl);
                }
                else if (step == '4'){
                    var nohps   = getnohp();
                    var exmails = getexmail();
                    fields.push("nohps");
                    fields.push("exmails");
                    values.push(nohps);
                    values.push(exmails);
                }
                else if (step == '5'){
                    var telpls  = gettelpl();
                    var telpks  = gettelpk();
                    fields.push("telpls");
                    fields.push("telpks");
                    values.push(telpls);
                    values.push(telpks);                                
                }
                fields  = fields.join("-(x)-");
                values  = values.join("-(x)-");
//                alert(fields.toSource()+"\n"+values.toSource());
                var nochg   = $("#f1s"+step+"changes").prop("checked");
                if (!nochg){
                    $.ajax({
                        url     : "<?php echo site_url()?>/trx02/home/cek_form",
                        data    : "field="+fields+"&value="+values,
                        type    : "post",
                        dataType: "json",
                        cache   : false,
                        success : function (data){
    //                        alert(data.status);
                            if (data.status == "oke"){
                                $(".istep"+step).addClass("icon-white");
                                $("#f1s"+step).val("1");
                            }
                            else{
                                $(".istep"+step).removeClass("icon-white");
                                $("#f1s"+step).val("0");
                            }
                        },
                        error   : function (a){
                            alert(a.responseText+"\n"+a.statusText);
                        }                    
                    });
                }
                else{
                
                }
            }
            function cekupdate(){                
                for (var i=1;i<=5;i++){// i = step ke 1-5
                    var cstp    = $("#f1s"+i).val();
                    if (cstp == 0){
                        $(".fistp"+i).each(function(){
                            $(this).on('blur',function (){
                                var step    = $(this).attr("tstp");
                                checkfield(step);

                            });
                        });
                        $(".fsstp"+i).each(function(){
                            $(this).change(function (){
                                var step    = $(this).attr("tstp");
    //                            alert(itsid+"|"+itsval);                        
                                checkfield(step);
                            });
                        });
                        $(".frstp"+i).each(function(){
                            $(this).click(function (){
    //                            var itsid   = $(this).attr("name");
    //                            var itsval  = $(this).val();
                                var step    = $(this).attr("tstp");
    //                            alert(itsid+"|"+itsval);
                                checkfield(step);
                            });
                        });
                        if (i == '4'){
                            ceknohp();
                            cekexmail();
                        }
                        else if (i == '5'){
                            cektelpl();
                            cektelpk();
                        }
                    }
                }
            }
//            function cek
            function ceknohp(){
                $("input[name='nohp[]']").on("blur",function (){//step 4
                    var nohps   = getnohp();
//                    alert(nohps);
                    checkfield("4");
                });                
            }
            function cekexmail(){
                $("input[name='exmail[]']").on("blur",function (){//step 4
                    var exmails   = getexmail();
//                    alert(exmails);
                    checkfield("4");
                });
            }
            function cektelpl(){
                $("input[name='telpl[]']").on("blur",function (){//step 5
                    var telpls   = gettelpl();
//                    alert(telpls);
                    checkfield("5");
                });  
            }
            function cektelpk(){
                $("input[name='telpk[]']").on("blur",function (){//step 5
                    var telpks   = gettelpk();
//                    alert(telpks);
                    checkfield("5");
                });                
            }
            
            function cekupdatejob(){
                $(".fijob").each(function (){
                    $(this).on("blur",function (){
//                        alert($(this).attr("id"));
                        cekformjob();
                    });
                });
                $(".fsjob").each(function (){
                    $(this).change(function (){
//                        alert($(this).attr("id"));
                        cekformjob();
                    });                    
                });
            }
            function unbindformjob(){
                $(".fijob").each(function (){
                    $(this).unbind("blur");
                });
                $(".fsjob").each(function (){
                    $(this).unbind("change");                  
                });                
            }
            function cekformjob(){
                var jobloc  = $("#jobloc").val();
                var idparent= $("#idparent").val();
                var empstat = $("#empstat").val();
                var jobgrp  = $("#jobgrp").val();
                var depart  = $("#depart").val();
                var jobpos  = $("#jobpos").val();
                var unitjob = $("#unitjob").val();
                
                $.ajax({
                    url     : "<?php echo site_url()?>/trx02/home/cek_job",
                    data    : "empstat="+empstat.toUpperCase()+"&idparent="+idparent+"&jobloc="+jobloc+"&jobgrp="+jobgrp+"&depart="+depart+"&jobpos="+jobpos.toUpperCase()+"&unitjob="+unitjob.toUpperCase(),
                    type    : "post",
                    dataType: "json",
                    cache   : false,
                    success : function (data){
                        if (data.status == "oke"){
                            $(".ifnol").hide({effect : "fade",
                            complete    : function (){
                                $("#jchanges").prop("checked",true);
                            }
                            });
                        }
                        else{
                            $(".ifnol").show({effect : "fade"});
                            $("#jchanges").prop("checked",false);
                        }
                    },
                    error   : function (a){
                        alert(a.responseText+"\n"+a.statusText);
                    }
                });
            }
        </script>
    </head>
    <body>
        <div class="widget">
            <div class="widget-head">
                <div class="row-fluid">
                    <div class="span4">
                        <h4 class="heading">Individual Information</h4>
                    </div>
                    <div class="span8" style="text-align:right">
                        <button onclick="reloadpage()" class="btn btn-small btn-default btn-icon"><i class="icon-refresh"></i></button>
                        <button idbtn='btmain' onclick="backtohome()" class="btn btn-small btn-success btn-icon glyphicons home"><i></i>Back to Home</button>
                    </div>
                </div>
            </div>
            <div class="widget-body">
                <div class="tabsbar tabsbar-2 active-fill">
                    <ul class="row-fluid ">
                        <li class="span3 tab1 tabatas glyphicons user active">
                            <a href="#tab1" onclick="chgtab('1')" data-toggle="tab"><i></i>
                                Personal Details 
                                <div class="ribbon-wrapper small ribtab1"><h4 style="color: #be362f; text-shadow: 1px 1px 1px #000;">&ast;</h4></div>
                            </a>
                        </li>
                        <li class="span1 tab2 tabatas glyphicons building">
                            <a href="#tab2" onclick="chgtab('2')" data-toggle="tab"><i></i> 
                                Job Description 
                                <div class="ribbon-wrapper small ribtab2"><h4 style="color: #be362f; text-shadow: 1px 1px 1px #000;">&ast;</h4></div>
                            </a>
                        </li>
                        <li class="span1 tab3 tabatas glyphicons group">
                            <a href="#tab3" onclick="chgtab('3')" data-toggle="tab"><i></i>
                                Family Informations 
                                <div class="ribbon-wrapper small ribtab3"><h4 style="color: #be362f; text-shadow: 1px 1px 1px #000;">&ast;</h4></div>
                            </a>
                        </li>
                        <li class="span1 tab4 tabatas glyphicons book_open">
                            <a href="#tab4" onclick="chgtab('4')" data-toggle="tab"><i></i> 
                                Education Background 
                                <div class="ribbon-wrapper small ribtab4"><h4 style="color: #be362f; text-shadow: 1px 1px 1px #000;">&ast;</h4></div>
                            </a>
                        </li>
                        <li class="span1 tab5 tabatas glyphicons buoy">
                            <a href="#tab5" onclick="chgtab('5')" data-toggle="tab"><i></i> 
                                Training & Course Attended 
                                <div class="ribbon-wrapper small ribtab5"><h4 style="color: #be362f; text-shadow: 1px 1px 1px #000;">&ast;</h4></div>
                            </a>
                        </li>
                        <li class="span1 tab6 tabatas glyphicons globe">
                            <a href="#tab6" onclick="chgtab('6')" data-toggle="tab"><i></i> 
                                Language 
                                <div class="ribbon-wrapper small ribtab6"><h4 style="color: #be362f; text-shadow: 1px 1px 1px #000;">&ast;</h4></div>
                            </a>
                        </li>
                        <li class="span1 tab7 tabatas glyphicons certificate">
                            <a href="#tab7" onclick="chgtab('7')" data-toggle="tab"><i></i> 
                                Working Experience 
                                <div class="ribbon-wrapper small ribtab7"><h4 style="color: #be362f; text-shadow: 1px 1px 1px #000;">&ast;</h4></div>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab1">
                        <div class="alert alert-tab1" style="display: none">
                            <button class="close" data-dismiss='alert' type="button" ><i class="icon-remove"></i></button>
                            <strong>Attention!</strong><br>
                            Please update the information of each steps of this Personal Details!
                        </div>
                        <h4>Personal Details</h4>
                        <div class="widget widget-body step1">
                            <h3>Step 1 <i class="icon-ok-sign istep1"></i></h3>
                            <hr class="separator">
                            <input class="hfstep" type="hidden" id="f1s1">
                            <input class="hfstep" type="hidden" id="f1s2">
                            <input class="hfstep" type="hidden" id="f1s3">
                            <input class="hfstep" type="hidden" id="f1s4">
                            <input class="hfstep" type="hidden" id="f1s5">
                            <div class="row-fluid">
                                <div class="span3">
                                    <div class="control-group">
                                        <label for="fname"><b>Full Name</b> / <i class="transindo">Nama Lengkap</i></label>
                                        <div class="controls">
                                            <input type="text" class="span12 fistp1" tstp="1" id="fname">
                                        </div>
                                    </div>
                                </div>
                                <div class="span3">
                                    <div class="control-group">
                                        <label for="nname"><b>Nick Name</b> / <i class="transindo">Nama Panggilan</i></label>
                                        <div class="controls">
                                            <input type="text" class="span10 fistp1" tstp="1" id="nname">
                                        </div>
                                    </div>        
                                </div>
                            </div>
                            
                            <!--===================-->
                            <div class="row-fluid">
                                <div class="span2">
                                    <div class="control-group">
                                        <label style="overflow: visible;" for="pbirth"><b>Place of Birth</b> / <i class="transindo">Tempat Lahir</i></label>
                                        <div class="controls">
                                            <input type="text" class="span12 fistp1" tstp="1" id="pbirth">
                                        </div>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="control-group">
                                        <label style="overflow: visible; " for="dbirth"><b>Date of Birth</b> / <i class="transindo">Tanggal Lahir</i></label>
                                        <div class="controls">
                                            <input type="text" class="span12 fsstp1" tstp="1" id="dbirth">
                                        </div>
                                    </div>
                                </div>                    
                            </div>
                            <hr class="separator">
                            <div class="row-fluid">
                                <div class="span2">
                                    <div class="control-group">
                                        <label for="bheight"><b>Height</b> / <i class="transindo">Tinggi Badan</i></label>
                                        <div class="controls">
                                            <input type="text" maxlength="3" class="span6 fistp1" tstp="1" id="bheight">cm
                                        </div>
                                    </div>
                                </div>
                                <div class="span2">
                                    <div class="control-group">
                                        <label for="bweight"><b>Weight</b> / <i class="transindo">Berat Badan</i></label>
                                        <div class="controls">
                                            <input type="text" maxlength="3" class="span6 fistp1" tstp="1" id="bweight">kg
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!--===================--> 
                            <div class="row-fluid">
                                <div class="span2">
                                    <div class="control-group">
                                        <label for="gender"><b>Gender</b> / <i class="transindo">Jenis Kelamin</i></label>
                                        <div class="controls">
                                            <select class="span12 fsstp1" tstp="1" id="gender">
                                                <option value="M">Male / Laki - Laki</option>
                                                <option value="F">Female / Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="span3">
                                    <div class="control-group">
                                        <label for="tblood"><b>Type of Blood</b> / <i class="transindo">Golongan Darah</i></label>
                                        <div class="controls">
                                            <select class="span4 fsstp1" tstp="1" id="tblood">
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="A/B">A/B</option>
                                                <option value="O">O</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label class="ifnola" step="1"><input id="f1s1changes" class="nochgpdet" step="1" type="checkbox" value="1">There are no changes of this step.</label>
                            <hr class="separator">
                            <div class="row-fluid">
                                <div class="span8" style="text-align: center">
                                    <div class="pagination margin-bottom-none">
                                            <ul>
                                                <li onclick="tostep(1,1)" class="primary"><a >Step 1</a></li>
                                                <li onclick="tostep(2,1)"><a >Step 2</a></li>
                                                <li onclick="tostep(3,1)"><a >Step 3</a></li>
                                                <li onclick="tostep(4,1)"><a >Step 4</a></li>
                                                <li onclick="tostep(5,1)"><a >Step 5</a></li>
                                            </ul>
                                    </div>
                                </div>
                                <div class="span4" style="text-align: center">
                                    <div class="pagination margin-bottom-none">
                                            <ul>
                                                    <li onclick="tostep(1,1)"><a class="btn btn-icon btn-primary glyphicons left_arrow" disabled><i></i>Prev</a></li>
                                                    <li onclick="tostep(2,1)"><a class="btn btn-icon btn-primary glyphicons right_arrow"><i></i>Next</a></li>
                                            </ul>
                                    </div>
                                    <!--<button onclick="resetstep(1)" class="btn btn-icon btn-default glyphicons refresh"><i></i>Reset</button>-->
                                    <!--<button onclick="nextstep(2)" class="btn btn-icon btn-primary glyphicons right_arrow"><i></i>Next</button>-->
                                </div>
                            </div>
                        </div>
                        <!--==================================================================================-->
                        <div class="widget widget-body step2">
                            <h3>Step 2 <i class="icon-ok-sign istep2"></i></h3>
                            <hr class="separator">
                            <!--===================-->                         
                            <div class="row-fluid">
                                <div class="span3">
                                    <div class="control-group">
                                        <label for="czship"><b>Citizenship</b> / <i class="transindo">Kewarganegaraan</i></label>
                                        <div class="controls">
                                            <input type="text" class="span12 fistp2" tstp="2" id="czship">
                                        </div>
                                    </div>
                                </div>
                                <div class="span3">
                                    <div class="control-group">
                                        <label for="religion"><b>Religion</b> / <i class="transindo">Agama</i></label>
                                        <div class="controls">
                                            <select class="span12 fsstp2" tstp="2" id="religion">
                                                <option value="ISLAM">ISLAM</option>
                                                <option value="KATOLIK">KATOLIK</option>
                                                <option value="PROTESTAN">PROTESTAN</option>
                                                <option value="HINDU">HINDU</option>
                                                <option value="BUDDHA">BUDDHA</option>
                                            </select>
                                        </div>
                                    </div>        
                                </div>
                            </div>
                            <hr class="separator">
                            <!--===================-->
                            <div class="row-fluid">
                                <div class="span3">
                                    <div class="control-group">
                                        <label for="noktp"><b>KTP Number</b> / <i class="transindo">Nomor KTP</i></label>
                                        <div class="controls">
                                            <input type="text" class="span12 fistp2" tstp="2" id="noktp">
                                        </div>
                                    </div>
                                </div>
                                <div class="span3">
                                    <div class="control-group">
                                        <label for="nonpwp"><b>NPWP Number</b> / <i class="transindo">Nomor NPWP</i></label>
                                        <div class="controls">
                                            <input type="text" class="span12 fistp2" tstp="2" id="nonpwp">
                                        </div>
                                    </div>        
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label for="nonpwp"><b>BPJS Ketenagakerjaan Number</b> / <i class="transindo">Nomor BPJS Ketenagakerjaan</i></label>
                                        <div class="controls">
                                            <input type="text" class="span12 fistp2" tstp="2" value="-" id="nobpjsemp">
                                        </div>
                                    </div>        
                                </div>
                            </div>
                            <!--===================-->
                            <div class="row-fluid">
                                <div class="span3">
                                    <div class="control-group">
                                        <label for="nojamsos"><b>Jamsostek Number</b> / <i class="transindo">Nomor Jamsostek</i></label>
                                        <div class="controls">
                                            <input type="text" class="span12 fistp2" tstp="2" id="nojamsos">
                                        </div>
                                    </div>        
                                </div>
                                <div class="span3">
                                    <div class="control-group">
                                        <label for="nokpj"><b>KPJ Number</b> / <i class="transindo">Nomor KPJ</i></label>
                                        <div class="controls">
                                            <input type="text" class="span12 fistp2" tstp="2" id="nokpj">
                                        </div>
                                    </div>        
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label for="nokpj"><b>BPJS Kesehatan Number</b> / <i class="transindo">Nomor BPJS Kesehatan</i></label>
                                        <div class="controls">
                                            <input type="text" class="span12 fistp2" tstp="2" value="-" id="nobpjshlt">
                                        </div>
                                    </div>        
                                </div>
                            </div>
                            <hr class="separator">
                            <!--===================-->
                            <div class="row-fluid">
                                <div class="span3">
                                    <div class="control-group">
                                        <label for="abank"><b>Bank Account Number</b> / <i class="transindo">Nomor Rekening Bank</i></label>
                                        <div class="controls">
                                            <input type="text" id="abank" class="span12 fistp2" tstp="2">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label class="ifnola" step="2"><input id="f1s2changes" class="nochgpdet" step="2" type="checkbox" value="1">There are no changes of this step.</label>
                            <hr class="separator">
                            <div class="row-fluid">
                                <div class="span8" style="text-align: center">
                                    <div class="pagination margin-bottom-none">
                                            <ul>
                                                <li onclick="tostep(1,2)"><a >Step 1</a></li>
                                                <li onclick="tostep(2,2)" class="primary"><a >Step 2</a></li>
                                                <li onclick="tostep(3,2)"><a >Step 3</a></li>
                                                <li onclick="tostep(4,2)"><a >Step 4</a></li>
                                                <li onclick="tostep(5,2)"><a >Step 5</a></li>
                                            </ul>
                                    </div>
                                </div>
                                <div class="span4" style="text-align: center">
                                    <div class="pagination margin-bottom-none">
                                            <ul>
                                                    <li onclick="tostep(1,2)"><a class="btn btn-icon btn-primary glyphicons left_arrow"><i></i>Prev</a></li>
                                                    <li onclick="tostep(3,2)"><a class="btn btn-icon btn-primary glyphicons right_arrow"><i></i>Next</a></li>
                                            </ul>
                                    </div>
                                    <!--<button onclick="resetstep(1)" class="btn btn-icon btn-default glyphicons refresh"><i></i>Reset</button>-->
                                    <!--<button onclick="nextstep(2)" class="btn btn-icon btn-primary glyphicons right_arrow"><i></i>Next</button>-->
                                </div>
                            </div>
                        </div>
                        <!--==================================================================================-->
                        <div class="widget widget-body step3">
                            <h3>Step 3 <i class="icon-ok-sign istep3"></i></h3>
                            <hr class="separator">
                            <!--===================-->
                            <div class="row-fluid">
                                <div class="span2">
                                    <label for="marital"><b>Marital Status</b> / <i class="transindo">Status</i></label>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="control-group">
                                    <div class="controls">
                                        <div class="span2">
                                            <label><input type="radio" class="frstp3" tstp="3" name="marital" value="SINGLE">Single / <i class="transindo">Belum Menikah</i></label>
                                        </div>
                                        <div class="span2">
                                            <label><input type="radio" class="frstp3" tstp="3" name="marital" value="MARRIED">Married / <i class="transindo">Menikah</i></label>
                                        </div>
                                        <div class="span2">
                                            <label><input type="radio" class="frstp3" tstp="3" name="marital" value="DIVORCED">Divorced / <i class="transindo">Janda / Duda</i></label>                                   
                                        </div>                                        
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span4">
                                    <div class="control-group">
                                        <label for="marrcert"><b>Marriage Certificate</b> / <i class="transindo">Surat Kawin</i></label>
                                        <div class="controls">
                                            <select id="marrcert" class="span12 fsstp3" tstp="3">
                                                <option value="0">---</option>
                                                <option value="yes">YES</option>
                                                <option value="no">NO</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="span4">
                                    <div class="control-group">
                                        <label for="famcert"><b>Family Member Certificate</b> / <i class="transindo">Kartu Keluarga</i></label>
                                        <div class="controls">
                                            <select id="famcert" class="span12 fsstp3" tstp="3">
                                                <option value="0">---</option>
                                                <option value="yes">YES</option>
                                                <option value="no">NO</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="span4">
                                    <div class="control-group">
                                        <label for="famcert"><b>Family Certificate Number</b> / <i class="transindo">Nomor Kartu Keluarga</i></label>
                                        <div class="controls">
                                            <input class="span12 fistp3" tstp="3" value="-" type="text" id="famcertno">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span4">
                                    <div class="control-group">
                                        <label for="coupname"><b>Couple Name</b> / <i class="transindo"> Nama Pasangan</i></label>
                                        <div class="controls">
                                            <input class="span12 fistp3" tstp="3" value="0" type="text" id="coupname">
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="span4">
                                    <div class="control-group">
                                        <label for="couplektp"><b>Couple KTP Number</b> / <i class="transindo"> Nomor KTP Pasangan</i></label>
                                        <div class="controls">
                                            <input class="span12 fistp3" tstp="3" value="0" type="text" id="couplektp">
                                        </div>
                                    </div>                                    
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span4">
                                    <div class="control-group">
                                        <label for="nchild"><b>Number of Children</b> / <i class="transindo"> Jumlah Anak</i> : </label>
                                        <div class="controls">
                                            <input class="span2 fistp3" tstp="3" value="0" type="text" id="nchild">
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <label class="ifnola" step="3"><input id="f1s3changes" class="nochgpdet" step="3" type="checkbox" value="1">There are no changes of this step.</label>
                            <hr class="separator">
                            <div class="row-fluid">
                                <div class="span8" style="text-align: center">
                                    <div class="pagination margin-bottom-none">
                                            <ul>
                                                <li onclick="tostep(1,3)"><a >Step 1</a></li>
                                                <li onclick="tostep(2,3)"><a >Step 2</a></li>
                                                <li onclick="tostep(3,3)" class="primary"><a >Step 3</a></li>
                                                <li onclick="tostep(4,3)"><a >Step 4</a></li>
                                                <li onclick="tostep(5,3)"><a >Step 5</a></li>
                                            </ul>
                                    </div>
                                </div>
                                <div class="span4" style="text-align: center">
                                    <div class="pagination margin-bottom-none">
                                            <ul>
                                                    <li onclick="tostep(2,3)"><a class="btn btn-icon btn-primary glyphicons left_arrow"><i></i>Prev</a></li>
                                                    <li onclick="tostep(4,3)"><a class="btn btn-icon btn-primary glyphicons right_arrow"><i></i>Next</a></li>
                                            </ul>
                                    </div>
                                    <!--<button onclick="resetstep(1)" class="btn btn-icon btn-default glyphicons refresh"><i></i>Reset</button>-->
                                    <!--<button onclick="nextstep(2)" class="btn btn-icon btn-primary glyphicons right_arrow"><i></i>Next</button>-->
                                </div>
                            </div>
                        </div>
                        <!--==================================================================================-->
                        <div class="widget widget-body step4">
                            <script>
                                function hitung(){
                                    var jml = new Array();
                                    $("[tstp='4']").each(function (){
                                        jml.push($(this).attr("id"));
                                    });
                                    alert(jml.length);
                                }
                            </script>
                            <h3>Step 4 <i class="icon-ok-sign istep4"></i></h3>
                            <hr class="separator">
                            <!--===================-->
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">                                        
                                        <div class="controls nohps">
                                            <label for="nohp"><b>Mobile Phone Number</b> / <i class="transindo">Nomor HP</i></label>
                                            <input class="span2 "  type="text" name="nohp[]">
                                        </div>
                                        <a class="btn btn-mini btn-default glyphicons circle_plus" onclick="addnohp()"><i></i>add ph no</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span3">
                                    <div class="control-group">
                                        <label for="inemail"><b>Internal Email Address</b> / <i class="transindo">Alamat Email Internal</i></label>
                                        <div class="controls">
                                            <input type="text"  id="inemail" class="span12 fistp4" tstp="4">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="control-group">
                                        <label for="exmail"><b>External Email Address</b> / <i class="transindo">Alamat Email Eksternal</i></label>
                                        <div class="controls exmails">
                                            <input type="text" name="exmail[]" class="span2">
                                        </div>
                                        <a class="btn btn-mini btn-default glyphicons circle_plus" onclick="addexmail()"><i></i>add email</a>
                                    </div>
                                </div>
                            </div>
                            <label class="ifnola" step="4"><input id="f1s4changes" class="nochgpdet" step="4" type="checkbox" value="1">There are no changes of this step.</label>
                            <hr class="separator">
                            <div class="row-fluid">
                                <div class="span8" style="text-align: center">
                                    <div class="pagination margin-bottom-none">
                                            <ul>
                                                <li onclick="tostep(1,4)"><a >Step 1</a></li>
                                                <li onclick="tostep(2,4)"><a >Step 2</a></li>
                                                <li onclick="tostep(3,4)"><a >Step 3</a></li>
                                                <li onclick="tostep(4,4)"  class="primary"><a >Step 4</a></li>
                                                <li onclick="tostep(5,4)"><a >Step 5</a></li>
                                            </ul>
                                    </div>
                                </div>
                                <div class="span4" style="text-align: center">
                                    <div class="pagination margin-bottom-none">
                                            <ul>
                                                    <li onclick="tostep(3,4)"><a class="btn btn-icon btn-primary glyphicons left_arrow"><i></i>Prev</a></li>
                                                    <li onclick="tostep(5,4)"><a class="btn btn-icon btn-primary glyphicons right_arrow"><i></i>Next</a></li>
                                            </ul>
                                    </div>
                                    <!--<button onclick="resetstep(1)" class="btn btn-icon btn-default glyphicons refresh"><i></i>Reset</button>-->
                                    <!--<button onclick="nextstep(2)" class="btn btn-icon btn-primary glyphicons right_arrow"><i></i>Next</button>-->
                                </div>
                            </div>
                        </div>
                        <div class="widget widget-body step5">
                            
                            <script>
                                
                            function loadprovince(){
                                $.ajax({
                                    url     : "<?php echo $base_url."mod_empcenter/index.php/trx02/home/getallprovince"?>",
                                    data    : "",
                                    type    : "post",
                                    dataType: "json",
                                    cache   : false,
                                    success : function (data){
                                        var res = "<option kode='-' value='-'>-</option>";
                                        for (var i=0;i<data.length;i++){
                                            var nama = data[i].lokasi_nama;
                                            var kode = data[i].lokasi_provinsi;
                                            res = res+"<option kode='"+kode+"' value='"+nama+"'  >"+nama+"</option>";
                                        }
                                        $("select#laddrprov").empty();
                                        $("select#kaddrprov").empty();
                                        $("select#laddrprov").append(res);
                                        $("select#kaddrprov").append(res);
                                    },
                                    error   : function (a){
                                        alert(a.responseText);
                                    }
                                });
                            }
                            function loadcities(foraddr,namaprov){
                                var kodeprov = $("#"+foraddr+"addrprov").find("option[value='"+namaprov+"']").attr('kode');
                                if (namaprov != '-'){
                                    $.ajax({
                                        url     : "<?php echo $base_url."mod_empcenter/index.php/trx02/home/get_cities"?>",
                                        data    : "kodeprov="+kodeprov,
                                        type    : "post",
                                        dataType: "json",
                                        cache   : false,
                                        success : function (data){
                                            var res = "<option kode='-' value='-'>-</option>";
                                            for (var i=0;i<data.length;i++){
                                                var nama = data[i].lokasi_nama;
                                                var kode = data[i].lokasi_kabupaten;
                                                res = res+"<option kode='"+kode+"' value='"+nama+"' >"+nama+"</option>";
                                            }

                                            $("#"+foraddr+"addrcity").empty();
                                            $("#"+foraddr+"addrsub").empty();
                                            $("#"+foraddr+"addrvlg").empty();
                                            $("#"+foraddr+"addrcity").append(res);

                                        },
                                        error   : function (a){
                                            alert(a.responseText);
                                        }
                                    });
                                }else{
                                    $("#"+foraddr+"addrcity").empty();
                                    $("#"+foraddr+"addrsub").empty();
                                    $("#"+foraddr+"addrvlg").empty();                                    
                                }
                            }
                            function loadsubs(foraddr,namakota){
                                var namaprov    = $("#"+foraddr+"addrprov").val();
                                var kodeprov    = $("#"+foraddr+"addrprov").find("option[value='"+namaprov+"']").attr('kode');
                                var kodekota    = $("#"+foraddr+"addrcity").find("option[value='"+namakota+"']").attr('kode');
                                if (namakota != '-'){
                                    $.ajax({
                                        url     : "<?php echo $base_url."mod_empcenter/index.php/trx02/home/get_subs"?>",
                                        data    : "kodekota="+kodekota+"&kodeprov="+kodeprov,
                                        type    : "post",
                                        dataType: "json",
                                        cache   : false,
                                        success : function (data){
    //                                        alert(kodekota+","+kodeprov);
                                            var res = "<option kode='-' value='-'>-</option>";
                                            for (var i=0;i<data.length;i++){
                                                var nama = data[i].lokasi_nama;
                                                var kode = data[i].lokasi_kecamatan;
                                                res = res+"<option kode='"+kode+"' value='"+nama+"' >"+nama+"</option>";
                                            }
                                            $("#"+foraddr+"addrsub").empty();
                                            $("#"+foraddr+"addrvlg").empty();
                                            $("#"+foraddr+"addrsub").append(res);
                                        },
                                        error   : function (a){
                                            alert(a.responseText);
                                        }
                                    });
                                }else{
                                    $("#"+foraddr+"addrsub").empty();
                                    $("#"+foraddr+"addrvlg").empty();
                                }
                            }
                            function loadvlgs(foraddr,namakec){
                                var namaprov    = $("#"+foraddr+"addrprov").val();
                                var namakota    = $("#"+foraddr+"addrcity").val();
                                var kodeprov    = $("#"+foraddr+"addrprov").find("option[value='"+namaprov+"']").attr('kode');
                                var kodekota    = $("#"+foraddr+"addrcity").find("option[value='"+namakota+"']").attr('kode');
                                var kodekec    = $("#"+foraddr+"addrsub").find("option[value='"+namakec+"']").attr('kode');
                                if (namakec != '-'){
                                    $.ajax({
                                        url     : "<?php echo $base_url."mod_empcenter/index.php/trx02/home/get_vlgs"?>",
                                        data    : "kodekota="+kodekota+"&kodeprov="+kodeprov+"&kodekec="+kodekec,
                                        type    : "post",
                                        dataType: "json",
                                        cache   : false,
                                        success : function (data){
    //                                        alert(kodekota+","+kodeprov);
                                            var res = "<option kode='-' value='-'>-</option>";
                                            for (var i=0;i<data.length;i++){
                                                var nama = data[i].lokasi_nama;
                                                var kode = data[i].lokasi_kelurahan;
                                                res = res+"<option kode='"+kode+"' value='"+nama+"' >"+nama+"</option>";
                                            }
                                            $("#"+foraddr+"addrvlg").empty();
                                            $("#"+foraddr+"addrvlg").append(res);
                                        },
                                        error   : function (a){
                                            alert(a.responseText);
                                        }
                                    });
                                }else{
                                    $("#"+foraddr+"addrvlg").empty();
                                }
                            }
                            </script>
                            <h3>Step 5 <i class="icon-ok-sign istep5"></i></h3>
                            <hr class="separator">
                            <!--===================-->
                            <div class="row-fluid">
                                <div class="span6">
                                    <div class="control-group">
                                        <label for="laddress"><b>Live Address</b> / <i class="transindo">Alamat Sekarang</i></label>
                                        <div class="controls">
                                            <table>
                                                <tr>
                                                    <td>State/Province /<i class="transindo">Provinsi</i></td>
                                                    <td>:</td>
                                                    <td>
                                                        <select id="laddrprov" class="fsstp5" tstp="5" onchange="loadcities('l',this.value)">
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>District/City / <i class="transindo">Kabupaten/Kota</i></td>
                                                    <td>:</td>
                                                    <td>
                                                        <select id="laddrcity" class="fsstp5" tstp="5" onchange="loadsubs('l',this.value)">
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Sub-district / <i class="transindo">Kecamatan</i></td>
                                                    <td>:</td>
                                                    <td>
                                                        <select id="laddrsub" class="fsstp5" tstp="5" onchange="loadvlgs('l',this.value)">
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Village / <i class="transindo">Desa/Kelurahan</i></td>
                                                    <td>:</td>
                                                    <td>
                                                        <select id="laddrvlg" class="fsstp5" tstp="5">
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                         RT :<input type="text" class="span3 fistp5" tstp="5" id="livert"> RW : <input type="text" class="span3 fistp5" tstp="5" id="liverw">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
                                                        <textarea id="laddress" class="span12 fistp5" tstp="5"></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Postal Code / <i class="transindo">Kode Pos</i></td>
                                                    <td>:</td>
                                                    <td><input type="text" id="lkodepos" maxlength="5" class="span3 fistp5" tstp="5"></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <div class="controls telpls">
                                            <label for="laddressph"><b>House Phone Number</b> / <i class="transindo">Nomor Telepon Rumah</i></label>
                                            <input class="span4"  type="text" name="telpl[]" >
                                        </div>
                                        <a class="btn btn-mini btn-default glyphicons circle_plus" onclick="addtelpl()"><i></i>add</a>
                                    </div>
                                </div>
                            </div>
                            <!--===================-->
                            <hr>
                            <div class="row-fluid">
                                <div class="span6">
                                    <div class="control-group">
                                        <label for="ktpaddress"><b>KTP Address</b> / <i class="transindo">Alamat di KTP</i></label>
                                        <div class="controls">
<table>
                                                <tr>
                                                    <td>State/Province /<i class="transindo">Provinsi</i></td>
                                                    <td>:</td>
                                                    <td>
                                                        <select id="kaddrprov" class="fsstp5" tstp="5" onchange="loadcities('k',this.value)">
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>District/City / <i class="transindo">Kabupaten/Kota</i></td>
                                                    <td>:</td>
                                                    <td>
                                                        <select id="kaddrcity" class="fsstp5" tstp="5" onchange="loadsubs('k',this.value)">
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Sub-district / <i class="transindo">Kecamatan</i></td>
                                                    <td>:</td>
                                                    <td>
                                                        <select id="kaddrsub" class="fsstp5" tstp="5" onchange="loadvlgs('k',this.value)">
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Village / <i class="transindo">Desa/Kelurahan</i></td>
                                                    <td>:</td>
                                                    <td>
                                                        <select id="kaddrvlg" class="fsstp5" tstp="5">
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td>
                                                        RT :<input type="text" class="span3 fistp5" tstp="5" id="ktprt"> RW : <input type="text" class="span3 fistp5" tstp="5" id="ktprw"> 
                                                    </td>
                                                </tr>                                                
                                                <tr>
                                                    <td colspan="3">
                                                        <textarea id="ktpaddress" class="span12 fistp5" tstp="5"></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Postal Code / <i class="transindo">Kode Pos</i></td>
                                                    <td>:</td>
                                                    <td><input type="text" id="ktpkodepos" maxlength="5" class="span3 fistp5" tstp="5"></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="span6">
                                    <div class="control-group">
                                        <div class="controls telpks">
                                            <label for="ktpaddressph"><b>House Phone Number</b> / <i class="transindo">Nomor Telepon Rumah</i></label>
                                            <input class="span4" type="text" name="telpk[]" >
                                        </div>
                                        <a class="btn btn-mini btn-default glyphicons circle_plus" onclick="addtelpk()"><i></i>add</a>
                                    </div>
                                </div>
                            </div>
                            <label class="ifnola" step="5"><input id="f1s5changes" class="nochgpdet" step="5" type="checkbox" value="1">There are no changes of this step.</label>
                            <hr class="separator">
                            <div class="row-fluid">
                                <div class="span8" style="text-align: center">
                                    <div class="pagination margin-bottom-none">
                                            <ul>
                                                <li onclick="tostep(1,5)"><a >Step 1</a></li>
                                                <li onclick="tostep(2,5)"><a >Step 2</a></li>
                                                <li onclick="tostep(3,5)"><a >Step 3</a></li>
                                                <li onclick="tostep(4,5)"><a >Step 4</a></li>
                                                <li onclick="tostep(5,5)" class="primary"><a >Step 5</a></li>
                                            </ul>
                                    </div>
                                </div>
                                <div class="span4" style="text-align: center">
                                    <div class="pagination margin-bottom-none">
                                            <ul>
                                                    <li onclick="tostep(4,5)"><a class="btn btn-icon btn-primary glyphicons left_arrow"><i></i>Prev</a></li>
                                                    <li onclick="savepersonal()"><a class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Save</a></li>
                                            </ul>
                                    </div>
                                    <!--<button onclick="resetstep(1)" class="btn btn-icon btn-default glyphicons refresh"><i></i>Reset</button>-->
                                    <!--<button onclick="nextstep(2)" class="btn btn-icon btn-primary glyphicons right_arrow"><i></i>Next</button>-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab2" >
                        <div class="alert alert-tab2" style="display: none">
                            <button class="close" data-dismiss='alert' type="button" ><i class="icon-remove"></i></button>
                            <strong>Attention!</strong><br>
                            Please update the details of your job descriptions.<br>
                            If there are no changes, just check the box at the end of the page instead of changing the descriptions.
                        </div>
                        <h4>Job Details</h4>
                        <div class="widget widget-body jobdtl">
                            <div class="row-fluid">
                                <div class="span8">
                                    <!--===================-->
                                    <div class="row-fluid">
                                        <div class="span4">
                                            <div class="control-group">
                                                <label for="idemp"><b>Employee ID</b> / <i class="transindo">NIP</i></label>
                                                <div class="controls">
                                                    <input type="text" class="span12 fijob" id="idemp">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span8">
                                            <div class="control-group">
                                                <label for="idparent"><b>Manager/Director ID</b> / <i class="transindo">NIP Atasan</i></label>
                                                <div class="controls">
                                                    <input type="text" class="span6" id="idparent">
                                                    <input type="text" class="span6 upper fijob" id="nmparent" placeholder="[Name]">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--===================-->
                                    <div class="row-fluid">
                                        <div class="span4">
                                            <div class="control-group">
                                                <label for="jobloc"><b>Working Location</b> / <i class='transindo'>Lokasi Kerja</i></label>
                                                <div class="controls">
                                                    <select class="span12 fsjob" id="jobloc">
                                                        <option value="KAPUK">KAPUK</option>
                                                        <option value="BITUNG">BITUNG</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span4">
                                            <div class="control-group">
                                                <label for="jobgrp"><b>Job Status</b> / <i class='transindo'>Status Karyawan</i></label>
                                                <div class="controls">
                                                    <select class="span12 fsjob" id="jobgrp">
                                                        <option value="ST">STAFF</option>
                                                        <option value="LT">LAP TETAP</option>
                                                        <option value="LK">LAP KONTRAK</option>
                                                        <option value="MAG">MAGANG</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--===================-->
                                    <div class="row-fluid">
                                        <div class="span4">
                                            <div class="control-group">
                                                <label for="depart"><b>Department</b> / <i class='transindo'>Departemen</i></label>
                                                <div class="controls">
                                                    <select class="span12 fsjob" id="depart">
                                                        <?php 
                                                        foreach ($departement->result() as $dprt){
                                                            echo "<option value='$dprt->IDStructure'>";
                                                            echo $dprt->DescStructure;
                                                            echo "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span4">
                                            <div class="control-group">
                                                <label for="jobpos"><b>Position</b> / <i class='transindo'>Posisi</i></label>
                                                <div class="controls">
                                                    <select class="upper span12 fsjob" id="jobpos">
                                                        <option value="DIRECTOR">DIRECTOR</option>
                                                        <option value="ASSISTANT DIRECTOR">ASSISTANT DIRECTOR</option>
                                                        <option value="MANAGER">MANAGER</option>
                                                        <option value="ASSISTANT MANAGER">ASSISTANT MANAGER</option>
                                                        <option value="SUPERVISOR">SUPERVISOR</option>
                                                        <option value="ASSISTANT SUPERVISOR">ASSISTANT SUPERVISOR</option>
                                                        <option value="STAFF">STAFF</option>
                                                        <option value="OPERATOR">OPERATOR</option>
                                                        <option value="HELPER">HELPER</option>
                                                        <option value="MAGANG">MAGANG</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span4">
                                            <div class="control-group">
                                                <label for="unitjob"><b>Unit</b> / <i class='transindo'>Unit</i></label>
                                                <div class="controls">
                                                    <input class="upper span12 fijob" id="unitjob" placeholder="[Unit]" type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span4">
                                            <div class="control-group">
                                                <label for="empstat"><b>Status</b> / <i class='transindo'>Status</i></label>
                                                <div class="controls">
                                                    <input class="upper span12 fijob" id="empstat" placeholder="[Status]" type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <label class="ifnol"><input id="jchanges" type="checkbox" value="1">There are no changes of job description.</label>
                                    <hr class="separator">
                                    <div class="span12" style="text-align:center;">
                                        <button onclick="save_job()" class="btn btn-success btn-icon glyphicons circle_ok"><i></i>Save</button>
                                    </div>
                                    <!--===================-->
                                </div>
                                <div class="span4 widget widget-body">
                                    <h4>Calendar</h4>
                                    <hr class="separator">
                                    <div class="row-fluid ">
                                        <div class="span6">
                                            <div class="control-group">
                                                <label for="hiredate"><b>Hire Date</b> / <i class='transindo'>Tanggal Kontrak</i></label>
                                                <div class="controls">
                                                    <input class="span12" id="hiredate" type="text" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label for="datefirst"><b>First Join </b> / <i class='transindo'>Tanggal Mulai Bergabung</i></label>
                                                <div class="controls">
                                                    <input class="span12" id="datefirst" type="text" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span6">
                                            <div class="control-group">
                                                <label for="dateprob"><b>Pass Probation </b> / <i class='transindo'>Tanggal Lulus Masa Percobaan</i></label>
                                                <div class="controls">
                                                    <input class="span12" id="dateprob" type="text" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span6">
                                            <div class="control-group">
                                                <label for="contnew"><b>New Contract </b> / <i class='transindo'>Tanggal Kontrak Baru </i></label>
                                                <div class="controls">
                                                    <input class="span12" id="contnew" type="text" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="span6">
                                            <div class="control-group">
                                                <label for="contend"><b>End Contract </b> / <i class='transindo'>Tanggal Berakhir Kontrak</i></label>
                                                <div class="controls">
                                                    <input class="span12" id="contend" type="text" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="tab-pane" id="tab3">
						<?php if($checkmother=='empty'){?>
							<div class="row-fluid" div-alert='alert_mother'>
								<div class="span12">
									<div class="alert alert-error">
										<button class="close" data-dismiss="alert" type="button">×</button>
										<strong>WARNING ! The information of your family member is missing. Please add the profile of your mother!
										</strong>
									</div>
								</div>
							</div>
						<?php } ?>
                        <div class="row-fluid">
                            <div class="span6">
                                <h4>Family Information</h4>
                            </div>
			    
                            <div class="span6" style="text-align: right;">
                                <button onclick="addfammember()" class="btn btn-primary btn-icon glyphicons circle_plus btnaddfam"><i></i>Add Family Member</button>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="widget widget-body taballfamily">
                                <div class="row-fluid">
                                    <div class="12">
                                        <table class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable ">
                                            <thead class="btn-primary">
                                                <tr>
                                                    <th>Family Member</th>
                                                    <th>KTP Number</th>
                                                    <th>Full Name</th>
                                                    <th>Age</th>
                                                    <th>Address</th>
                                                    <th>Education</th>
                                                    <th>Occupation</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="allfamily">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--add family form-->
                            <div class="widget widget-body addfamily">                            
                                <div class="row-fluid">
                                    <div class="span3">
                                        <div class="control-group">
                                            <input id="famlastid" type="hidden">
                                            <input id="famproses" type="hidden">
                                            <input id="famid" type="hidden">
                                            <label for="fammember"><b>Family Member</b> / <i class="transindo">Anggota Keluarga</i></label>
                                            <div class="controls">
                                                <select id="fammember" class="span12">
                                                    <option value="father">FATHER</option>
                                                    <option value="mother">MOTHER</option>
                                                    <option value="sibling">SIBLING</option>
                                                    <option value="spouse">SPOUSE</option>
                                                    <option value="child">CHILD</option>
                                                </select>
                                            </div>
                                        </div>                                    
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="famnoktp"><b>KTP Number</b> / <i class="transindo">Nomor KTP</i></label>
                                            <div class="controls">
                                                <input id="famnoktp" type="text" class="span12">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="famfname"><b>Full Name</b> / <i class="transindo">Nama Lengkap</i></label>
                                            <div class="controls">
                                                <input id="famfname" type="text" class="span12 upper">
                                            </div>
                                        </div>                                    
                                    </div>
                                </div>
                                <script>
                                    $(document).ready(function (){
                                        $("#fambdate").datepicker({
                                            changeYear  : true,
                                            changeMonth : true,
                                            dateFormat  : "dd-mm-yy",
                                            yearRange   : '-100'
                                        });
                                        $("#fambdate").change(function (){
                                            var sekarang= "<?php echo date('Y-m-d')?>";
                                            var lahir   = $(this).val();                                        
                                            $.ajax({
                                                url     : "<?php echo $base_url."/mod_empcenter/index.php/trx02/home/get_usia"?>",
                                                data    : "date1="+sekarang+"&date2="+lahir,
                                                type    : "post",
                                                success : function (data){
                                                    $("#famage").val(data);
                                                }
                                            });

                                        })
                                    });
                                </script>
                                <div class="row-fluid">
                                    <div class="span2">
                                        <div class="control-group">
                                            <label for="fambplace"><b>Birth Place</b> / <i class="transindo">Tempat Lahir</i></label>
                                            <div class="controls">
                                                <input id="fambplace" type="text" class="span12">
                                            </div>
                                        </div>                                        
                                    </div>
                                    <div class="span2">
                                        <div class="control-group">
                                            <label for="fambdate"><b>Birth Date</b> / <i class="transindo">Tanggal Lahir</i></label>
                                            <div class="controls">
                                                <input id="fambdate" type="text" class="span12">
                                            </div>
                                        </div>                                        
                                    </div>
                                    <div class="span2">
                                        <div class="control-group">
                                            <label for="famage"><b>Age</b> / <i class="transindo">Usia</i></label>
                                            <div class="controls">
                                                <input id="famage" type="text" class="span5">
                                            </div>
                                        </div>                                    
                                    </div>                                    
                                </div>
                                <div class="row-fluid">
                                    <div class="span4">
                                        <div class="control-group">
                                            <label for="famaddress"><b>Address</b> / <i class="transindo">Alamat</i></label>
                                            <div class="controls">
                                                <textarea class="span12" id="famaddress"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span2">
                                        <div class="control-group">
                                            <label for="famedu"><b>Education</b> / <i class="transindo">Pendidikan</i></label>
                                            <div class="controls">
                                                <input id="famedu" class="span6" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span2">
                                        <div class="control-group">
                                            <label for="famoccu"><b>Occupation</b> / <i class="transindo">Pekerjaan</i></label>
                                            <div class="controls">
                                                <input id="famoccu" class="span6" type="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="separator">
                                <div class="row-fluid">
                                    <div class="span12" style="text-align : center;">
                                        <p>
                                            <button onclick="save_family()" class="btn btn-success btn-icon glyphicons circle_ok"><i></i>Save</button>
                                            <button onclick="caddfam()" class="btn btn-danger btn-icon glyphicons circle_remove"><i></i>Cancel</button>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane" id="tab4">
                        <div class="row-fluid">
                            <div class="span6">
                                <h4>Education Background</h4>
                            </div>
                            <div class="span6" style="text-align: right;">
                                <button onclick="addedu()" class="btn btn-primary btn-icon glyphicons circle_plus btnaddedu"><i></i>Add Education Background</button>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="widget widget-body taballeducation">
                                <div class="row-fluid">
                                    <div class="12">
                                        <table class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable ">
                                            <thead class="btn-primary">
                                                <tr>
                                                    <th>Level</th>
                                                    <th>Course</th>
                                                    <th>School Name</th>
                                                    <th>City</th>
                                                    <th>From</th>
                                                    <th>Until</th>
                                                    <th>Certificate</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="alledu">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--add family form-->
                            <div class="widget widget-body addedu">                            
                                <div class="row-fluid">
                                    <div class="span3">
                                        <div class="control-group">
                                            <input id="edulastid" type="hidden">
                                            <input id="eduproses" type="hidden">
                                            <input id="eduid" type="hidden">
                                            <label for="edulevel"><b>Education Level</b> / <i class="transindo">Tingkat Pendidikan</i></label>
                                            <div class="controls">
                                                <select id="edulevel" class="span10">
                                                    <option value="SD">SD</option>
                                                    <option value="SMP">SMP</option>
                                                    <option value="SMA/SMK">SMA / SMK</option>
                                                    <option value="DI">DI</option>
                                                    <option value="DII">DII</option>
                                                    <option value="DIII">DIII</option>
                                                    <option value="DIV">DIV</option>
                                                    <option value="S1">S1</option>
                                                    <option value="S2">S2</option>
                                                    <option value="S3">S3</option>
                                                </select>
                                            </div>
                                        </div>                                    
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="educourse"><b>Course</b> / <i class="transindo">Jurusan</i></label>
                                            <div class="controls">
                                                <input id="educourse" type="text" class="span10 upper">
                                            </div>
                                        </div>                                    
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span4">
                                        <div class="control-group">
                                            <label for="eduname"><b>School Name</b> / <i class="transindo">Nama Sekolah / Universitas</i></label>
                                            <div class="controls">
                                                <input id="eduname" type="text" class="span10 upper">
                                            </div>
                                        </div>                                    
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="educity"><b>City</b> / <i class="transindo">Kota</i></label>
                                            <div class="controls">
                                                <input id="educity" type="text" class="span10 upper">
                                            </div>
                                        </div>                                    
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span2">
                                        <div class="control-group">
                                            <label for="edufrom"><b>From</b> / <i class="transindo">Dari Tahun</i></label>
                                            <div class="controls">
                                                <input id="edufrom" class="span6" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span2">
                                        <div class="control-group">
                                            <label for="edutill"><b>Until</b> / <i class="transindo">Sampai Tahun</i></label>
                                            <div class="controls">
                                                <input id="edutill" class="span6" type="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="educert"><b>Certificate</b> / <i class="transindo">Lulus</i></label>
                                            <div class="controls">
                                                <select id="educert" class="span10">
                                                    <option value="yes">YES</option>
                                                    <option value="no">NO</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="separator">
                                <div class="row-fluid">
                                    <div class="span12" style="text-align : center;">
                                        <p>
                                            <button onclick="save_education()" class="btn btn-success btn-icon glyphicons circle_ok"><i></i>Save</button>
                                            <button onclick="caddedu()" class="btn btn-danger btn-icon glyphicons circle_remove"><i></i>Cancel</button>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>                            
                    </div>
                    <div class="tab-pane" id="tab5">
                        <div class="row-fluid">
                            <div class="span6">
                                <h4>Training and Course Attended</h4>
                            </div>
                            <div class="span6" style="text-align: right;">
                                <button onclick="addtnc()" class="btn btn-primary btn-icon glyphicons circle_plus btnaddtnc"><i></i>Add T&amp;C Attended </button>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="widget widget-body taballtnc">
                                <div class="row-fluid">
                                    <div class="12">
                                        <table class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable ">
                                            <thead class="btn-primary">
                                                <tr>
                                                    <th>Program</th>
                                                    <th>Facilitator</th>
                                                    <th>City</th>
                                                    <th>Duration</th>
                                                    <th>From</th>
                                                    <th>Until</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="alltnc">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--add training form-->
                            <div class="widget widget-body addtnc">                            
                                <div class="row-fluid">
                                    <div class="span3">
                                        <div class="control-group">
                                            <input id="tnclastid" type="hidden">
                                            <input id="tncproses" type="hidden">
                                            <input id="tncid" type="hidden">
                                            <label for="tncprogram"><b>Program</b> / <i class="transindo">Program</i></label>
                                            <div class="controls">
                                                <input type="text" class="span10 upper" id="tncprogram">
                                            </div>
                                        </div>                                    
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="tncfac"><b>Facilitator</b> / <i class="transindo">Fasilitator</i></label>
                                            <div class="controls">
                                                <input id="tncfac" type="text" class="span10 upper">
                                            </div>
                                        </div>                                    
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span4">
                                        <div class="control-group">
                                            <label for="tnccity"><b>City</b> / <i class="transindo">Kota</i></label>
                                            <div class="controls">
                                                <input id="tnccity" type="text" class="span10 upper">
                                            </div>
                                        </div>                                    
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="tncdur"><b>Duration</b> / <i class="transindo">Durasi</i></label>
                                            <div class="controls">
                                                <input id="tncdur" type="text" class="span10 upper">
                                            </div>
                                        </div>                                    
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span2">
                                        <div class="control-group">
                                            <label for="tncfrom"><b>From</b> / <i class="transindo">Dari Tahun</i></label>
                                            <div class="controls">
                                                <input id="tncfrom" class="span6" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span2">
                                        <div class="control-group">
                                            <label for="tnctill"><b>Until</b> / <i class="transindo">Sampai Tahun</i></label>
                                            <div class="controls">
                                                <input id="tnctill" class="span6" type="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="separator">
                                <div class="row-fluid">
                                    <div class="span12" style="text-align : center;">
                                        <p>
                                            <button onclick="save_tnc()" class="btn btn-success btn-icon glyphicons circle_ok"><i></i>Save</button>
                                            <button onclick="caddtnc()" class="btn btn-danger btn-icon glyphicons circle_remove"><i></i>Cancel</button>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab6">
                        <div class="row-fluid">
                            <div class="span6">
                                <h4>Languages</h4>
                            </div>
                            <div class="span6" style="text-align: right;">
                                <button onclick="addlang()" class="btn btn-primary btn-icon glyphicons circle_plus btnaddlang"><i></i>Add Language </button>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="widget widget-body taballlang">
                                <div class="row-fluid">
                                    <div class="12">
                                        <table class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable ">
                                            <thead class="btn-primary">
                                                <tr>
                                                    <th>Language</th>
                                                    <th>Reading</th>
                                                    <th>Listening</th>
                                                    <th>Conversation</th>
                                                    <th>Writing</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="alllang">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--add Language form-->
                            <div class="widget widget-body addlang">                            
                                <div class="row-fluid">
                                    <div class="span3">
                                        <div class="control-group">
                                            <input id="langlastid" type="hidden">
                                            <input id="langproses" type="hidden">
                                            <input id="langid" type="hidden">
                                            <label for="langlanguage"><b>Language</b> / <i class="transindo">Bahasa</i></label>
                                            <div class="controls">
                                                <input type="text" class="span10 upper" id="langlanguage">
                                            </div>
                                        </div>                                    
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="langlisten"><b>Listening</b> / <i class="transindo">Mendengar</i> (0 - 100%)</label>
                                            <div class="controls">
                                                <input id="langlisten" type="text" class="span10 upper">
                                            </div>
                                        </div>                                    
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="langread"><b>Reading</b> / <i class="transindo">Membaca</i> (0 - 100%) </label>
                                            <div class="controls">
                                                <input id="langread" type="text" class="span10 upper">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="langconv"><b>Conversation</b> / <i class="transindo">Berbicara</i> (0 - 100%) </label>
                                            <div class="controls">
                                                <input id="langconv" type="text" class="span10 upper">
                                            </div>
                                        </div>                                    
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="langwrite"><b>Writing</b> / <i class="transindo">Menulis</i> (0 - 100%) </label>
                                            <div class="controls">
                                                <input id="langwrite" class="span10 upper" type="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="separator">
                                <div class="row-fluid">
                                    <div class="span12" style="text-align : center;">
                                        <p>
                                            <button onclick="save_lang()" class="btn btn-success btn-icon glyphicons circle_ok"><i></i>Save</button>
                                            <button onclick="caddlang()" class="btn btn-danger btn-icon glyphicons circle_remove"><i></i>Cancel</button>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab7">
                        <div class="row-fluid">
                            <div class="span6">
                                <h4>Working Experience</h4>
                            </div>
                            <div class="span6" style="text-align: right;">
                                <button onclick="addwork()" class="btn btn-primary btn-icon glyphicons circle_plus btnaddwork"><i></i>Add Working Experience</button>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="widget widget-body taballwork">
                                <div class="row-fluid">
                                    <div class="span12">
                                        <table class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable ">
                                            <thead class="btn-primary">
                                                <tr>
                                                    <th>Company Name</th>
                                                    <th>Address</th>
                                                    <th>Phone Number</th>
                                                    <th>Position</th>
                                                    <th>Duration</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="allwork">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
<!--                            <div class="widget-body span12" style="text-align: right;">
                                <button idbtn='btmain' onclick="backtohome('7')" class="btn btn-small btn-success btn-icon glyphicons home"><i></i>Back to Home</button>
                            </div>-->
                            <!--add family form-->
                            <div class="widget widget-body addwork">                            
                                <div class="row-fluid">
                                    <div class="span3">
                                        <div class="control-group">
                                            <input id="worklastid" type="hidden">
                                            <input id="workproses" type="hidden">
                                            <input id="workid" type="hidden">
                                            <label for="workcomp"><b>Company Name</b> / <i class="transindo">Nama Perusahaan</i></label>
                                            <div class="controls">
                                                <input type="text" class="span10 upper" id="workcomp">
                                            </div>
                                        </div>                                    
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="workaddress"><b>Company Address</b> / <i class="transindo">Alamat Perusahaan</i></label>
                                            <div class="controls">
                                                <textarea class="span12 upper" rows="3" id="workaddress"></textarea>
                                            </div>
                                        </div>                                    
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span4">
                                        <div class="control-group">
                                            <label for="workphone"><b>Company Phone Number</b> / <i class="transindo">Nomor Telepon Perusahaan</i></label>
                                            <div class="controls">
                                                <input id="workphone" type="text" class="span10 upper">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="workpos"><b>Position</b> / <i class="transindo">Jabatan</i></label>
                                            <div class="controls">
                                                <input id="workpos" type="text" class="span10 upper">
                                            </div>
                                        </div>                                    
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span2">
                                        <div class="control-group">
                                            <label for="workdur"><b>Work Duration</b> / <i class="transindo">Masa Kerja</i></label>
                                            <div class="controls">
                                                <input id="workdur" class="span6" type="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="separator">
                                <div class="row-fluid">
                                    <div class="span12" style="text-align : center;">
                                        <p>
                                            <button onclick="save_work()" class="btn btn-success btn-icon glyphicons circle_ok"><i></i>Save</button>
                                            <button onclick="caddwork()" class="btn btn-danger btn-icon glyphicons circle_remove"><i></i>Cancel</button>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
