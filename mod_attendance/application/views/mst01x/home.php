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
            $(document).ready(function() {

                hideallctntab();

                //$("#idemp").keydown(false);

//                $("#empstat").keydown(false);
                var flag = "<?php echo $flag; ?>";

                if (flag == 'add') {
                    chgtab(2);

                    $("#hiredate").datepicker({dateFormat: "dd-mm-yy"});
                    $("#datefirst").datepicker({dateFormat: "dd-mm-yy"});
                    $("#dateprob").datepicker({dateFormat: "dd-mm-yy"});
                    $("#contnew").datepicker({dateFormat: "dd-mm-yy"});
                    $("#contend").datepicker({dateFormat: "dd-mm-yy"});
                    $("#tglmasuk").datepicker({dateFormat: "dd-mm-yy"});
                    $("#tglkeluar").datepicker({dateFormat: "dd-mm-yy"});

                    $("#hiredate").keydown(true);
                    $("#datefirst").keydown(true);
                    $("#dateprob").keydown(true);
                    $("#contnew").keydown(true);
                    $("#contend").keydown(true);
                    $("#tglmasuk").keydown(true);
                    $("#tglkeluar").keydown(true);

                } else {
                    chgtab(1);
                    $("#hiredate").datepicker({dateFormat: "dd-mm-yy"}).datepicker('enable');
                    $("#datefirst").datepicker({dateFormat: "dd-mm-yy"}).datepicker('disable');
                    $("#dateprob").datepicker({dateFormat: "dd-mm-yy"}).datepicker('disable');
                    $("#contnew").datepicker({dateFormat: "dd-mm-yy"}).datepicker('enable');
                    $("#contend").datepicker({dateFormat: "dd-mm-yy"}).datepicker('enable');
                    $("#tglmasuk").datepicker({dateFormat: "dd-mm-yy"}).datepicker('disable');
                    //$("#tglkeluar").datepicker({dateFormat: "dd-mm-yy"});
		    $("#tglkeluar").datepicker({dateFormat: "dd-mm-yy",                    
                      onClose:function(dateText, inst){   
                       var tglkeluar = $("#tglkeluar").val();
                       if(tglkeluar !==''){
                            $("input[name='statusemployee'][value='P']").prop("checked", true);// for radio 
                       }else{
                           $("input[name='statusemployee'][value='A']").prop("checked", true);// for radio 
                       }
                       
                    }          
                    });	

                    $("#hiredate").keydown(true);
                    $("#datefirst").keydown(false);
                    $("#dateprob").keydown(false);
                    $("#contnew").keydown(true);
                    $("#contend").keydown(true);
                    $("#tglmasuk").keydown(false);
                    $("#tglkeluar").keydown(true);
                }


            });
            var inohp = 0;
            var itelpl = 0;
            var itelpk = 0;
            var iexmail = 0;
            var nip = "<?php echo $userid; ?>";
            var flag = "<?php echo $flag; ?>";
	    var statuskaryawan = "<?php echo $statusemployee; ?>";	

            var ROOT = {
                'site_url': '<?php echo $base_url . 'index.php'; ?>',
                'base_url': '<?php echo $base_url; ?>'
            };
            var employees = <?php echo $employees; ?>;
            $("#bheight").keydown(function(e) {
                var s = String.fromCharCode(e.which);
//                alert(s+"|"+e.keyCode+"|"+(e.keyCode-96));

                var num = $.isNumeric(s);
                var key = e.keyCode || e.charCode;
                if (num == true || key == 8 || key == 46 || key == 9 || (key >= 96 && key <= 105)) {
//                    alert("angka");
                } else {
//                    alert(isinew);
                    return false;
                }
            });
            $("#bweight").keydown(function(e) {
                var s = String.fromCharCode(e.which);
                var num = $.isNumeric(s);
                var key = e.keyCode || e.charCode;
                if (num == true || key == 8 || key == 46 || key == 9 || (key >= 96 && key <= 105)) {
//                    alert("angka");
                } else {
//                    alert(isinew);
                    return false;
                }
            });
            $("#nmparent").hide();
            $("#idparent").focusin(function() {
                $("#idparent").hide();
                $("#nmparent").show({
                    complete: function() {
                        $("#nmparent").focus();
                        $("#idparent").prop("disabled", true);
                        $("#idparent").show({
                            easing: "slide",
                            duration: 300
                        });
                    }
                });
            });
            $("#nmparent").focusout(function() {
                $("#idparent").prop("disabled", false);
                $("#nmparent").hide({
                    easing: "slide",
                    duration: 300
                });
            });
            $("#nmparent").autocomplete({
                source: employees,
                select: function(event, ui) {
                    $("#idparent").val(ui.item.IDEmployee);
                    $("#nmparent").hide({
                        easing: "slide",
                        duration: 300,
                        complete: function() {
                            $("#idparent").prop("disabled", false);
                        }
                    });
                }
            });
            $("#dbirth").datepicker({
//                changeMonth: true,
                changeYear: true,
                yearRange: "-80:+0",
                dateFormat: "dd-mm-yy"
            });
            function reloadpage() {
                var content = $("#content");
                var url = ROOT.base_url + 'mod_attendance/index.php/mst01/home/';
                content.load(url);
            }
           function loadpersonal() {
                $(document).ready(function() {
            
                if (flag == 'add') {
                    var idemployee = $("#idemp").val();
                } else {
                    var idemployee = nip;
                }

                //alert(ROOT.base_url + "mod_attendance/index.php/mst01/home/get_personal/" + idemployee);
                $.ajax({
                    url: ROOT.base_url + "mod_attendance/index.php/mst01/home/get_personal/" + idemployee,
                    data: "",
                    type: "post",
                    dataType: "json",
                    cache: false,
                    success: function(data) {
//                        alert(data.NoKTP+"|"+data.EmailExternal);
                        $("#fname").val(data.FullName);
                        $("#nname").val(data.NickName);
                        $("#pbirth").val(data.BirthPlace);
                                               
                        if(data.BirthDate =='' || data.BirthDate ==null){
                            $("#dbirth").val('');
                        }else{
                             var dbi = data.BirthDate.split("-");
                             $("#dbirth").val(dbi[2] + "-" + dbi[1] + "-" + dbi[0]);
                        }
                      
                       
                        $("#bheight").val(data.Height);
                        $("#bweight").val(data.Weight);
                        $("#gender").find("option[value='" + data.Gender + "']").prop("selected", true);
                        $("#tblood").val(data.BloodType);
                        $("#czship").val(data.Citizenship);
                        $("#religion").val(data.Religion);
                        $("#noktp").val(data.NoKTP);
                        $("#nonpwp").val(data.NoNPWP);
                        $("#nojamsos").val(data.Jamsostek);
                        $("#nokpj").val(data.NoKPJ);
                        $("#abank").val(data.BankAccount);
                        
                        
                        
                        //alert(data.BankAccount);
                        $("input[name='marital'][value='" + data.MaritalStatus + "']").prop("checked", true);//radio
                        $("#coupname").val(data.CoupleName);
                        $("#couplektp").val(data.CoupleKTP);
                        $("#nchild").val(data.NumberChildren);
                        // no hp============================================
                        var nohps = data.NoHp;
                        if (nohps != null) {
                            var nohp = nohps.split(",");
                            var jmlnohp = nohp.length;
                            if (jmlnohp > 0) {
                                $("div.controls.nohps").find("input").remove();
                                $("div.controls.nohps").find("button").remove();
                                for (var i = 0; i < jmlnohp; i++) {
                                    inohp++;
                                    $("div.controls.nohps").append("<input class='span2 nohpke" + i + "' type='text' name='nohp[]' value='" + nohp[i] + "'>" + '<button title="Delete ph no.?" class="btn-mini btn btn-danger nohpke' + i + '" onclick="remnohp(\'' + i + '\')">x</button>');
                                }
                            } else {

                            }
                        }
                        // end of no hp============================================
                        // no telp live addr============================================
                        var telpls = data.LiveAddressNoTelp;
                        if (telpls != null) {
                            var telpl = telpls.split(",");
                            var jmltelpl = telpl.length;
                            if (jmltelpl > 0) {
                                $("div.controls.telpls").find("input").remove();
                                $("div.controls.telpls").find("button").remove();
                                for (var i = 0; i < jmltelpl; i++) {
                                    itelpl++;
                                    $("div.controls.telpls").append("<input class='span2 telplke" + i + "' type='text' name='telpl[]' value='" + telpl[i] + "'>" + '<button title="Delete house telp no.?" class="btn-mini btn btn-danger telplke' + i + '" onclick="remtelpl(\'' + i + '\')">x</button>');
                                }
                            } else {
                            }
                        }
                        // end of no telp live addr============================================
                        // no telp ktp addr============================================
                        var telpks = data.KTPAddressNoTelp;
                        if (telpks != null) {
                            var telpk = telpks.split(",");
                            var jmltelpk = telpk.length;
                            if (jmltelpk > 0) {
                                $("div.controls.telpks").find("input").remove();
                                $("div.controls.telpks").find("button").remove();
                                for (var i = 0; i < jmltelpk; i++) {
                                    itelpk++;
                                    $("div.controls.telpks").append("<input class='span2 telpkke" + i + "' type='text' name='telpk[]' value='" + telpk[i] + "'>" + '<button title="Delete house telp no.?" class="btn-mini btn btn-danger telpkke' + i + '" onclick="remtelpk(\'' + i + '\')">x</button>');
                                }
                            } else {
                            }
                        }
                        // end of no telp ktp addr============================================
                        // no telp ktp addr============================================
                        var exmails = data.EmailExternal;
                        if (exmails != null) {
                            var exmail = exmails.split(",");
                            var jmlexmail = exmail.length;
                            if (jmlexmail > 0) {
                                $("div.controls.exmails").find("input").remove();
                                $("div.controls.exmails").find("button").remove();
                                for (var i = 0; i < jmlexmail; i++) {
                                    iexmail++;
                                    $("div.controls.exmails").append("<input class='span2 exmailke" + i + "' type='text' name='exmail[]' value='" + exmail[i] + "'>" + '<button title="Delete house telp no.?" class="btn-mini btn btn-danger exmailke' + i + '" onclick="remexmail(\'' + i + '\')">x</button>');
                                }
                            } else {
                            }
                        }
                        // end of no telp ktp addr============================================
                        $("#inemail").val(data.EmailInternal);
//                        $("#exmail").val();
                        $("#laddress").append(data.LiveAddress);//textarea

                        $("#ktpaddress").append(data.KTPAddress);//textarea

                        $("#famcert").val(data.FamilyMemberCertificate);
                        $("#marrcert").val(data.MarriageCertificate);
                        if (data.F1 == '1') {
                            $(".ribtab1").hide({
                                duration: 500,
                                easing: "blind"
                            });
                        }
                        if (data.F2 == '1') {
                            $(".ribtab2").hide({
                                duration: 500,
                                easing: "blind"
                            });
                        }
                        if (data.F3 == '1') {
                            $(".ribtab3").hide({
                                duration: 500,
                                easing: "blind"
                            });
                        }
                        if (data.F4 == '1') {
                            $(".ribtab4").hide({
                                duration: 500,
                                easing: "blind"
                            });
                        }
                        if (data.F5 == '1') {
                            $(".ribtab5").hide({
                                duration: 500,
                                easing: "blind"
                            });
                        }
                        if (data.F6 == '1') {
                            $(".ribtab6").hide({
                                duration: 500,
                                easing: "blind"
                            });
                        }
                        if (data.F7 == '1') {
                            $(".ribtab7").hide({
                                duration: 500,
                                easing: "blind"
                            });
                        }
                    }
                });
                });
                
                
            }


            function upd_ftab(tab) {
                $.ajax({
                    url: ROOT.base_url + "mod_attendance/index.php/mst01/home/upd_ftab",
                    data: "tab=" + tab,
                    type: "post",
                    dataType: "json",
                    cache: false,
                    success: function(data) {
                        $(".ribtab" + tab).hide({
                            duration: 500,
                            easing: "blind"
                        });

                    }
                });
            }
            function backtohome() {
                window.location.href = "<?php echo $base_url; ?>";
            }
            function tostep(step, bef) {
//                var stepbef = step-1;
                $(".step" + bef).hide({
                    duration: 300,
                    easing: "slide",
                    complete: function() {
                        $(".step" + step).show({
                            duration: 300,
                            easing: "slide",
                            complete: function() {
                            }
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
            function savepersonal() {
                var fname = $("#fname").val();
                var nname = $("#nname").val();
                var pbirth = $("#pbirth").val();
                var dbirth = $("#dbirth").val();
                var bheight = $("#bheight").val();
                var bweight = $("#bweight").val();
                var gender = $("#gender").val();
                var tblood = $("#tblood").val();
                var czship = $("#czship").val();
                var religion = $("#religion").val();
                var noktp = $("#noktp").val();
                var nonpwp = $("#nonpwp").val();
                var nojamsos = $("#nojamsos").val();
                var nokpj = $("#nokpj").val();
                var abank = $("#abank").val();
                var marital = $("input[type='radio'][name='marital']:checked").val();//radio
                var coupname = $("#coupname").val();
                var couplektp = $("#couplektp").val();
                var nchild = $("#nchild").val();
                var nohp = getnohp();
                var inemail = $("#inemail").val();
                var exmail = getexmail();
                var laddress = $("#laddress").val();
                var laddressph = gettelpl();
                var ktpaddress = $("#ktpaddress").val();
                var ktpaddressph = gettelpk();
                var famcert = $("#famcert").val();
                var marrcert = $("#marrcert").val();
//                alert(ktpaddressph);
//                alert(laddressph+"|"+ktpaddressph);
                loading();

                if (flag == 'add') {
                    var idemployee = $("#idemp").val();
                } else {
                    var idemployee = nip;
                }
                
                
                //alert("fname=" + fname.toUpperCase() + "&nname=" + nname.toUpperCase() + "&pbirth=" + pbirth.toUpperCase() + "&dbirth=" + dbirth + "&bheight=" + bheight + "&bweight=" + bweight + "&gender=" + gender + "&tblood=" + tblood.toUpperCase());
                //alert(ROOT.base_url + "mod_attendance/index.php/mst01/home/save_personal/" + idemployee);

                
                $.ajax({
                    url: ROOT.base_url + "mod_attendance/index.php/mst01/home/save_personal/" + idemployee,
                    data: "famcert=" + famcert + "&marrcert=" + marrcert + "&fname=" + fname.toUpperCase() + "&nname=" + nname.toUpperCase() + "&pbirth=" + pbirth.toUpperCase() + "&dbirth=" + dbirth + "&bheight=" + bheight + "&bweight=" + bweight + "&gender=" + gender + "&tblood=" + tblood.toUpperCase() + "&czship=" + czship.toUpperCase() + "&religion=" + religion.toUpperCase() + "&noktp=" + noktp + "&nonpwp=" + nonpwp + "&nojamsos=" + nojamsos + "&nokpj=" + nokpj + "&abank=" + abank + "&marital=" + marital + "&coupname=" + coupname.toUpperCase() + "&couplektp=" + couplektp + "&nchild=" + nchild + "&nohp=" + nohp + "&inemail=" + inemail + "&exmail=" + exmail + "&laddress=" + laddress + "&laddressph=" + laddressph + "&ktpaddress=" + ktpaddress + "&ktpaddressph=" + ktpaddressph,
                    type: "post",
                    dataType: "json",
                    cache: false,
                    success: function(data) {
//                        alert(data.toSource())
                        bootbox.alert("Your personal data was saved!", function() {
                            bootbox.hideAll();
                            $.gritter.add({
                                title: 'Data Updated!',
                                text: "Your personal data was updated"
                            });
                            chgtab("2");
                            $(".tab-pane").removeClass("active");
                            $("li.tabatas").removeClass("active");
                            $("li.tab2").addClass("active");
                            $("div#tab2").addClass("active");
                        });
                    }
                });
            }
            function hideallctntab() {
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
            function showaddmenu() {
                $(".addfamily").show();
                $(".addedu").show();
                $(".addtnc").show();
                $(".addlang").show();
                $(".addwork").show();
            }
            function loading() {
                bootbox.alertloading("<center>Processing. Please wait !!!<br><img src='<?php echo $base_url; ?>public/avatar/76.GIF'></center>");
            }
            function chgtab(tab) {
                hideallctntab();
                showaddmenu();
                $("li.tabatas.span3").addClass("span1");
                $("li.tabatas.span3").removeClass("span3");
                $("li.tabatas.tab" + tab).removeClass("span1");
                $("li.tabatas.tab" + tab).addClass("span3");
                if (tab == "1") {
                    upd_ftab("1");
                    loadpersonal();
                    $(".step1").hide({
                        complete: function() {
                            $(".step1").show({
                                duration: 300,
                                easing: "slide",
                                complete: function() {

                                }
                            });
                        }
                    });
                    $(".step2").hide();
                    $(".step3").hide();
                    $(".step4").hide();
                    $(".step5").hide();
                }
                if (tab == "2") {
                    upd_ftab("2");
                    load_job();
                    $(".jobdtl").hide({
                        duration: 300,
                        easing: "slide",
                        complete: function() {
                            $(".jobdtl").show({
                                duration: 300,
                                easing: "slide",
                                complete: function() {

                                }
                            });
                        }
                    });
                }
                if (tab == "3") {
                    upd_ftab("3");
//                    alert(tab);
                    get_family();
                    $(".addfamily").hide({
                        complete: function() {
                            $(".taballfamily").show({
                                duration: 300,
                                easing: "slide",
                                complete: function() {

                                    $(".btnaddfam").show();
                                }
                            });
                        }
                    });
                }
                if (tab == "4") {
                    upd_ftab("4");
                    get_education();
                    $(".addedu").hide({
                        complete: function() {
                            $(".taballeducation").show({
                                duration: 300,
                                easing: "slide",
                                complete: function() {

                                    $(".btnaddedu").show();
                                }
                            });
                        }
                    });
                }
                if (tab == "5") {
                    upd_ftab("5");
                    get_tnc();
                    $(".addtnc").hide({
                        complete: function() {
                            $(".taballtnc").show({
                                duration: 300,
                                easing: "slide",
                                complete: function() {

                                    $(".btnaddtnc").show();
                                }
                            });
                        }
                    });
                }
                if (tab == "6") {
                    upd_ftab("6");
                    get_lang();
                    $(".addlang").hide({
                        complete: function() {
                            $(".taballlang").show({
                                duration: 300,
                                easing: "slide",
                                complete: function() {

                                    $(".btnaddlang").show();
                                }
                            });
                        }
                    });
                }
                if (tab == "7") {
                    upd_ftab("7");
                    get_work();
                    $(".addwork").hide({
                        complete: function() {
                            $(".taballwork").show({
                                duration: 300,
                                easing: "slide",
                                complete: function() {
                                    $(".btnaddwork").show();
                                }
                            });
                        }
                    });
                }

                if (tab == "8") {
                    load_exitinterview();
                    $(".exitinterview").hide({
                        duration: 300,
                        easing: "slide",
                        complete: function() {
                            $(".exitinterview").show({
                                duration: 300,
                                easing: "slide",
                                complete: function() {

                                }
                            });
                        }
                    });
                }


            }
            //===============PASSIVE OR ACTIVE EMPLOYEE =======//


            function load_exitinterview() {
                if (flag == 'add') {
                    var idemployee = $("#idemp").val();
                } else {
                    var idemployee = nip;
                }


                $.ajax({
                    url: ROOT.base_url + "mod_attendance/index.php/mst01/home/get_passive_or_active/" + idemployee,
                    data: "",
                    type: "post",
                    dataType: "json",
                    cache: false,
                    success: function(data) {
                        $("#nip").val(data.IDEmployee);
                        if (data.IDEmployee == null) {
                            $("#nip").keydown(true);
                        } else {
                            $("#nip").keydown(false);
                        }
                        $("#nip").val(data.IDEmployee);
                        $("#fullname").val(data.FullName);
                        $("input[name='statusemployee'][value='" + data.Status + "']").prop("checked", true);// for radio 
		        $("input[name='sendmail'][value='N']").prop("checked", true);// for radio       
                        $("#tglmasuk").val(data.HireDate);
                        $("#tglkeluar").val(data.ResignDate);
                        $("#reasonresgin").append(data.ReasonResign);// for textarea
                    }
                });

            }



            function save_exitinterview() {
                var statusemployee = $("input[type='radio'][name='statusemployee']:checked").val();//radio
		var sendmail = $("input[type='radio'][name='sendmail']:checked").val();//radio
                var tglmasuk = $("#tglmasuk").val();
                var tglkeluar = $("#tglkeluar").val();
                var reasonresgin = $("#reasonresgin").val();
                if (flag == 'add') {
                    var idemployee = $("#idemp").val();
                } else {
                    var idemployee = nip;
                }
                
                
                //alert(ROOT.base_url + "mod_attendance/index.php/mst01/home/save_passive_or_active/" + idemployee);
                if (statusemployee == 'P') {
                    if (tglkeluar) {
                        loading();
                        $.ajax({
                            url: ROOT.base_url + "mod_attendance/index.php/mst01/home/save_passive_or_active/" + idemployee,
                            data: "statusemployee=" + statusemployee + "&tglmasuk=" + tglmasuk + "&tglkeluar=" + tglkeluar + "&reasonresgin=" + reasonresgin.toUpperCase()+ "&sendmail=" + sendmail,
                            type: "post",
                            dataType: "json",
                            cache: false,
                            success: function(data) {
                                if (data.status == "oke") {

                                   // alert("Status :"+data.status+" Message :"+data.pesan);

                                    bootbox.alert("Data saved!", function() {
                                        bootbox.hideAll();
                                        $.gritter.add({
                                            title: 'Data Successfully Saved!',
                                            text: ""
                                        });
                                    });
                                }
                            },
                            error: function(a, b) {
                                alert(a.toSource() + "|" + b);
                            }
                        });
                    } else {
                        $.gritter.add({
                            title: 'WARNING',
                            text: "Save Data Error, Resign Date is required not null..! ",
                            image: '<?php echo $base_url . 'public/theme/images/warni.jpeg' ?>',
                            class_name: 'gritter-light',
                            fade_in_speed: 100,
                            fade_out_speed: 100,
                            time: 2500
                        });
                        return false;
                    }
                } else {

                    loading();
                    $.ajax({
                        url: ROOT.base_url + "mod_attendance/index.php/mst01/home/save_passive_or_active/" + idemployee,
                        data: "statusemployee=" + statusemployee + "&tglmasuk=" + tglmasuk + "&tglkeluar=" + tglkeluar + "&reasonresgin=" + reasonresgin.toUpperCase(),
                        type: "post",
                        dataType: "json",
                        cache: false,
                        success: function(data) {
                            if (data.status == "oke") {
                                bootbox.alert("Data saved!", function() {
                                    bootbox.hideAll();
                                    $.gritter.add({
                                        title: 'Data Successfully Saved!',
                                        text: ""
                                    });
                                });
                            }
                        },
                        error: function(a, b) {
                            alert(a.toSource() + "|" + b);
                        }
                    });
                }


            }




            //=======END PASSIVE OR ACTIVE EMPLOYEE ===========//



//            job
            function load_job() {

                if (flag == 'add') {
                    var idemployee = $("#idemp").val();
                } else {
                    var idemployee = nip;
                }

                $.ajax({
                    url: ROOT.base_url + "mod_attendance/index.php/mst01/home/get_job/" + idemployee,
                    data: "",
                    type: "post",
                    dataType: "json",
                    cache: false,
                    success: function(data) {
                        $("#idemp").val(data.IDEmployee);
                        if (data.IDEmployee == null) {
                            $("#idemp").keydown(true);
                        } else {
                            $("#idemp").keydown(false);
                        }
                        $("#idparent").val(data.IDEmployeeParent);
                        $("#jobloc").val(data.Location);
                        $("#jobgrp").val(data.IDJobGroup);
                        $("#depart").val(data.Department);
                        $("#jobpos").val(data.Position);
                       // $("#unitjob").val(data.Unit);
                        //$("#empstat").val(data.EmployeeStatus);
                        $("#hiredate").val(data.HireDate);
                        $("#datefirst").val(data.DateFirstJoin);
                        $("#dateprob").val(data.DatePassProbation);
                        $("#contnew").val(data.DateNewContract);
                        $("#contend").val(data.DateEndContract);
			$("#fullname").val(data.FullName);
                        $("#nickname").val(data.NickName);
                        $("#nobank").val(data.BankAccount);
                        $("#note").val(data.Note);
                    }
                });

            }
           function save_job() {
                var jobloc = $("#jobloc").val();
                var idparent = $("#idparent").val();
               // var empstat = $("#empstat").val();
                var jobgrp = $("#jobgrp").val();
                var depart = $("#depart").val();
                var jobpos = $("#jobpos").val();
               // var unitjob = $("#unitjob").val();
                var hiredate = $("#hiredate").val();
                var datefirst = $("#datefirst").val();
                var dateprob = $("#dateprob").val();
                var contnew = $("#contnew").val();
                var contend = $("#contend").val();
                var fullname = $("#fullname").val();
                var nickname = $("#nickname").val();
                var nobank = $("#nobank").val();
                var note = $("#note").val();


                loading();

                if (flag == 'add') {
                    var idemployee = $("#idemp").val();
                    var status ='A';
                } else {
                    var idemployee = nip;
                    var status = statuskaryawan;
                }


                //alert("NIP :" + idemployee + "&hiredate :" + hiredate);
                $.ajax({
                    url: ROOT.base_url + "mod_attendance/index.php/mst01/home/save_job/" + idemployee,
                    //data: "empstat=" + empstat.toUpperCase() + "&idparent=" + idparent + "&jobloc=" + jobloc + "&jobgrp=" + jobgrp + "&depart=" + depart + "&jobpos=" + jobpos.toUpperCase() + "&unitjob=" + unitjob.toUpperCase()
                           // + "&hiredate=" + hiredate + "&datefirst=" + datefirst + "&dateprob=" + dateprob + "&contnew=" + contnew + "&contend=" + contend + "&fullname=" + fullname.toUpperCase() +"&nickname=" + nickname.toUpperCase() + "&nobank=" + nobank + "&note=" + note.toUpperCase()+"&status="+status,

		     data: "idparent=" + idparent + "&jobloc=" + jobloc + "&jobgrp=" + jobgrp + "&depart=" + depart + "&jobpos=" + jobpos.toUpperCase()+ "&hiredate=" + hiredate + "&datefirst=" + datefirst + "&dateprob=" + dateprob + "&contnew=" + contnew + "&contend=" + contend + "&fullname=" + fullname.toUpperCase() +"&nickname=" + nickname.toUpperCase() + "&nobank=" + nobank + "&note=" + note.toUpperCase()+"&status="+status,	
                    type: "post",
                    dataType: "json",
                    cache: false,
                    success: function(data) {
                        //alert(data);
                        if (data.status == "oke") {
                            bootbox.alert("Data saved!", function() {
                                bootbox.hideAll();
                                chgtab("2");
                                $.gritter.add({
                                    title: 'Data Successfully Saved!',
                                    text: "You can move to the next tab or recheck the data you inserted"
                                });
                            });
                        }
                    },
                    error: function(a, b) {
                        alert(a.toSource() + "|" + b);
                    }
                });

            }
// family script ===========================================================
            function get_family() {
                if (flag == 'add') {
                    var idemployee = $("#idemp").val();
                } else {
                    var idemployee = nip;
                }

                $.ajax({
                    url: ROOT.base_url + "mod_attendance/index.php/mst01/home/get_family/" + idemployee,
                    data: "",
                    type: "post",
                    dataType: "json",
                    cache: false,
                    success: function(data) {
//                        alert(data.toSource());
                        $("#famlastid").val(data.lastid.lastid);
                        $("#allfamily").empty();
                        var res = "";
                        for (var i = 0; i < data.data.length; i++) {
                            var btn_edit = "<button class='btn btn-mini btn-warning' onclick='edit_fam(\"" + data.data[i].IDFamily + "\")'><i class='icon-pencil'></i></button>";
                            var btn_del = "<button class='btn btn-mini btn-danger' onclick='del_fam(\"" + data.data[i].IDFamily + "\")'><i class='icon-trash'></i></button>";
                            res = res + "<tr class='selectable'><td class='upper'>" + data.data[i].FamilyMember + "</td><td>" + data.data[i].NoKTP + "</td><td>" + data.data[i].Name + "</td><td>" + data.data[i].Age + "</td><td>" + data.data[i].Address + "</td><td>" + data.data[i].Education + "</td><td>" + data.data[i].Occupation + "</td><td><center>" + btn_edit + btn_del + "</center></td></tr>";
                        }
                        $("#allfamily").append(res);
                    }
                });
            }
            function edit_fam(famid) {
                $("#famproses").val("pedit_family");

                if (flag == 'add') {
                    var idemployee = $("#idemp").val();
                } else {
                    var idemployee = nip;
                }

                $.ajax({
                    url: ROOT.base_url + "mod_attendance/index.php/mst01/home/edit_family/" + idemployee,
                    data: "famid=" + famid,
                    type: "post",
                    dataType: "json",
                    cache: false,
                    success: function(data) {
                        $("#fammember").find("option[value='" + data.FamilyMember + "']").prop("selected", true);
                        $("#famid").val(famid);
                        $("#famfname").val(data.Name);
                        $("#famage").val(data.Age);
                        $("#famaddress").val(data.Address);
                        $("#famedu").val(data.Education);
                        $("#famoccu").val(data.Occupation);
                        $("#famnoktp").val(data.NoKTP);
                        $("#famlastid").val("0");
                        $(".taballfamily").hide({
                            duration: 300,
                            easing: "blind",
                            complete: function() {
                                $(".btnaddfam").hide();
                                $(".addfamily").show({
                                    duration: 300,
                                    easing: "slide"
                                });
                            }
                        });
                    }
                });
            }
            function del_fam(famid) {
                if (flag == 'add') {
                    var idemployee = $("#idemp").val();
                } else {
                    var idemployee = nip;
                }

//                alert(famid);
                bootbox.confirm("You are going to delete your family member. Continue?", function(res) {
                    if (res == true) {
                        loading();
                        $.ajax({
                            url: ROOT.base_url + "mod_attendance/index.php/mst01/home/del_family/" + idemployee,
                            data: "famid=" + famid,
                            type: "post",
                            dataType: "json",
                            cache: false,
                            success: function(data) {
                                bootbox.alert(data.msg, function() {
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
            function addfammember() {
                $("#famproses").val("padd_family");
                $(".taballfamily").hide({
                    duration: 300,
                    easing: "blind",
                    complete: function() {
                        $(".btnaddfam").hide();
                        $("#fammember").find("option").prop("selected", false);
                        $("#famid").val("");
                        $("#famfname").val("");
                        $("#famage").val("");
                        $("#famaddress").val("");
                        $("#famedu").val("");
                        $("#famoccu").val("");
                        $("#famnoktp").val("");
//                            $("div.massuser").effect("slide","slow");
                        $(".addfamily").show({
                            duration: 300,
                            easing: "slide"
                        });
                    }
                });

            }
            function caddfam() {
                $(".addfamily").hide({
                    duration: 300,
                    easing: "blind",
                    complete: function() {
//                            $("div.massuser").effect("slide","slow");
                        $(".taballfamily").show({
                            duration: 300,
                            easing: "slide",
                            complete: function() {
                                $(".btnaddfam").show();
                                chgtab("3");
                            }
                        });
                    }
                });
            }
            function save_family() {
                var proses = $("#famproses").val();
                var member = $("#fammember").val();
                var famid = $("#famid").val();
                var fname = $("#famfname").val();
                var fage = $("#famage").val();
                var famaddr = $("#famaddress").val();
                var famedu = $("#famedu").val();
                var famoccu = $("#famoccu").val();
                var famnoktp = $("#famnoktp").val();
                var lastid = $("#famlastid").val();
                var nextid = (lastid * 1) + 1;
                if (fname != "") {
                    loading();

                    if (flag == 'add') {
                        var idemployee = $("#idemp").val();
                    } else {
                        var idemployee = nip;
                    }

                    $.ajax({
                        url: ROOT.base_url + "mod_attendance/index.php/mst01/home/" + proses + "/" + idemployee,
                        data: "famid=" + famid + "&nextid=" + nextid + "&member=" + member + "&fname=" + fname.toUpperCase() + "&fage=" + fage + "&faddress=" + famaddr + "&fedu=" + famedu.toUpperCase() + "&foccu=" + famoccu + "&fnoktp=" + famnoktp,
                        type: "post",
                        dataType: "json",
                        cache: false,
                        success: function(data) {
                            if (data != null) {
                                bootbox.alert(data.msg, function() {
                                    caddfam();
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
                else {
                    $.gritter.add({
                        title: 'Please Fill The Name Field!',
                        text: "You can't save data without filling the name field"
                    });
                }
            }
// end of family script ===========================================================
// education script ===========================================================
            function get_education() {
                if (flag == 'add') {
                    var idemployee = $("#idemp").val();
                } else {
                    var idemployee = nip;
                }
                $.ajax({
                    url: ROOT.base_url + "mod_attendance/index.php/mst01/home/get_education/" + idemployee,
                    data: "",
                    type: "post",
                    dataType: "json",
                    cache: false,
                    success: function(data) {
//                        alert(data.toSource());
                        $("#edulastid").val(data.lastid.lastid);
                        $("#alledu").empty();
                        var res = "";
                        for (var i = 0; i < data.data.length; i++) {
                            var btn_edit = "<button class='btn btn-mini btn-warning' onclick='edit_edu(\"" + data.data[i].IDEducation + "\")'><i class='icon-pencil'></i></button>";
                            var btn_del = "<button class='btn btn-mini btn-danger' onclick='del_edu(\"" + data.data[i].IDEducation + "\")'><i class='icon-trash'></i></button>";
                            res = res + "<tr class='selectable'><td class='upper'>" + data.data[i].EducationLevel + "</td><td>" + data.data[i].Course + "</td><td>" + data.data[i].SchoolName + "</td><td>" + data.data[i].City + "</td><td>" + data.data[i].YearFrom + "</td><td>" + data.data[i].YearUntil + "</td><td>" + data.data[i].Certificate + "</td><td><center>" + btn_edit + btn_del + "</center></td></tr>";
                        }
                        $("#alledu").append(res);
                    }
                });
            }
            function edit_edu(eduid) {
                if (flag == 'add') {
                    var idemployee = $("#idemp").val();
                } else {
                    var idemployee = nip;
                }

                $("#eduproses").val("pedit_education");
                $.ajax({
                    url: ROOT.base_url + "mod_attendance/index.php/mst01/home/edit_education/" + idemployee,
                    data: "eduid=" + eduid,
                    type: "post",
                    dataType: "json",
                    cache: false,
                    success: function(data) {
//                        alert("oke");
                        $("#edulevel").find("option[value='" + data.EducationLevel + "']").prop("selected", true);
                        $("#eduid").val(eduid);
                        $("#educourse").val(data.Course);
                        $("#eduname").val(data.SchoolName);
                        $("#educity").val(data.City);
                        $("#edufrom").val(data.YearFrom);
                        $("#edutill").val(data.YearUntil);
                        $("#educert").find("option[value='" + data.Certificate + "']").prop("selected", true);
                        $("#edulastid").val("0");
                        $(".taballeducation").hide({
                            duration: 300,
                            easing: "blind",
                            complete: function() {
                                $(".btnaddedu").hide();
                                $(".addedu").show({
                                    duration: 300,
                                    easing: "slide"
                                });
                            }
                        });
                    }
                });
            }
            function del_edu(eduid) {
//                alert(idfam);
                bootbox.confirm("You are going to delete your education background from the list. Continue?", function(res) {
                    if (res == true) {
                        loading();
                        if (flag == 'add') {
                            var idemployee = $("#idemp").val();
                        } else {
                            var idemployee = nip;
                        }
                        $.ajax({
                            url: ROOT.base_url + "mod_attendance/index.php/mst01/home/del_education/" + idemployee,
                            data: "eduid=" + eduid,
                            type: "post",
                            dataType: "json",
                            cache: false,
                            success: function(data) {
                                bootbox.alert(data.msg, function() {
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
            function addedu() {
                $("#eduproses").val("padd_education");
                $(".taballeducation").hide({
                    duration: 300,
                    easing: "blind",
                    complete: function() {
                        $(".btnaddedu").hide();
                        $("#edulevel").find("option").prop("selected", false);
                        $("#eduid").val("");
                        $("#educourse").val("");
                        $("#eduname").val("");
                        $("#educity").val("");
                        $("#edufrom").val("");
                        $("#edutill").val("");
                        $("#educert").find("option").prop("selected", false);
//                            $("div.massuser").effect("slide","slow");
                        $(".addedu").show({
                            duration: 300,
                            easing: "slide"
                        });
                    }
                });

            }
            function caddedu() {
                $(".addedu").hide({
                    duration: 300,
                    easing: "blind",
                    complete: function() {
//                            $("div.massuser").effect("slide","slow");
                        $(".taballeducation").show({
                            duration: 300,
                            easing: "slide",
                            complete: function() {
                                $(".btnaddedu").show();
                                chgtab("4");
                            }
                        });
                    }
                });
            }
            function save_education() {
                var eduid = $("#eduid").val();
                var proses = $("#eduproses").val();
                var level = $("#edulevel").val();
                var course = $("#educourse").val();
                var ename = $("#eduname").val();
                var ecity = $("#educity").val();
                var efrom = $("#edufrom").val();
                var etill = $("#edutill").val();
                var ecert = $("#educert").val();
                var lastid = $("#edulastid").val();
                var nextid = (lastid * 1) + 1;
                if (flag == 'add') {
                    var idemployee = $("#idemp").val();
                } else {
                    var idemployee = nip;
                }


                if (ename != '' && ecity != '' && efrom != '' && etill != '') {
                    loading();
                    $.ajax({
                        url: ROOT.base_url + "mod_attendance/index.php/mst01/home/" + proses + "/" + idemployee,
                        data: "eduid=" + eduid + "&nextid=" + nextid + "&level=" + level + "&ename=" + ename.toUpperCase() + "&ecity=" + ecity.toUpperCase() + "&efrom=" + efrom + "&etill=" + etill + "&ecert=" + ecert + "&course=" + course.toUpperCase(),
                        type: "post",
                        dataType: "json",
                        cache: false,
                        success: function(data) {
                            if (data != null) {
                                bootbox.alert(data.msg, function() {
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
                else {
                    if (ename == '') {
                        $.gritter.add({
                            title: 'Please Fill The Name Field!',
                            text: "You can't save data without filling the name field"
                        });
                    }
                    if (ecity == '') {
                        $.gritter.add({
                            title: 'Please Fill The City Field!',
                            text: "You can't save data without filling the city name field"
                        });
                    }
                    if (efrom == '') {
                        $.gritter.add({
                            title: 'Please Fill The Year Field!',
                            text: "You can't save data without filling the year field"
                        });
                    }
                }
            }
// end of education script ===========================================================
// tnc script ===========================================================
            function get_tnc() {
                if (flag == 'add') {
                    var idemployee = $("#idemp").val();
                } else {
                    var idemployee = nip;
                }
                $.ajax({
                    url: ROOT.base_url + "mod_attendance/index.php/mst01/home/get_tnc/" + idemployee,
                    data: "",
                    type: "post",
                    dataType: "json",
                    cache: false,
                    success: function(data) {
//                        alert(data.toSource());
                        $("#tnclastid").val(data.lastid.lastid);
                        $("#alltnc").empty();
                        var res = "";
                        for (var i = 0; i < data.data.length; i++) {
                            var btn_edit = "<button class='btn btn-mini btn-warning' onclick='edit_tnc(\"" + data.data[i].IDCourse + "\")'><i class='icon-pencil'></i></button>";
                            var btn_del = "<button class='btn btn-mini btn-danger' onclick='del_tnc(\"" + data.data[i].IDCourse + "\")'><i class='icon-trash'></i></button>";
                            res = res + "<tr class='selectable'><td class='upper'>" + data.data[i].CourseProgram + "</td><td>" + data.data[i].CourseFacilitator + "</td><td>" + data.data[i].City + "</td><td>" + data.data[i].Duration + "</td><td>" + data.data[i].YearFrom + "</td><td>" + data.data[i].YearUntil + "</td><td><center>" + btn_edit + btn_del + "</center></td></tr>";
                        }
                        $("#alltnc").append(res);
                    }
                });
            }
            function edit_tnc(tncid) {
                $("#tncproses").val("pedit_tnc");
                if (flag == 'add') {
                    var idemployee = $("#idemp").val();
                } else {
                    var idemployee = nip;
                }

                $.ajax({
                    url: ROOT.base_url + "mod_attendance/index.php/mst01/home/edit_tnc/" + idemployee,
                    data: "tncid=" + tncid,
                    type: "post",
                    dataType: "json",
                    cache: false,
                    success: function(data) {

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
                            easing: "blind",
                            complete: function() {
                                $(".btnaddtnc").hide();
                                $(".addtnc").show({
                                    duration: 300,
                                    easing: "slide"
                                });
                            }
                        });
                    }
                });
            }
            function del_tnc(tncid) {
//                alert(idfam);
                if (flag == 'add') {
                    var idemployee = $("#idemp").val();
                } else {
                    var idemployee = nip;
                }
                bootbox.confirm("You are going to delete your Training and Course from the list. Continue?", function(res) {
                    if (res == true) {
                        loading();
                        $.ajax({
                            url: ROOT.base_url + "mod_attendance/index.php/mst01/home/del_tnc/" + idemployee,
                            data: "tncid=" + tncid,
                            type: "post",
                            dataType: "json",
                            cache: false,
                            success: function(data) {
                                bootbox.alert(data.msg, function() {
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
            function addtnc() {
                $("#tncproses").val("padd_tnc");
                $(".taballtnc").hide({
                    duration: 300,
                    easing: "blind",
                    complete: function() {
                        $(".btnaddtnc").hide();
                        $("#tncprogram").val("");
                        $("#tncid").val("");
                        $("#tncfac").val("");
                        $("#tnccity").val("");
                        $("#tncdur").val("");
                        $("#tncfrom").val("");
                        $("#tnctill").val("");
                        $(".addtnc").show({
                            duration: 300,
                            easing: "slide"
                        });
                    }
                });

            }
            function caddtnc() {
                $(".addtnc").hide({
                    duration: 300,
                    easing: "blind",
                    complete: function() {
//                            $("div.massuser").effect("slide","slow");
                        $(".taballtnc").show({
                            duration: 300,
                            easing: "slide",
                            complete: function() {
                                $(".btnaddtnc").show();
                                chgtab("5");
                            }
                        });
                    }
                });
            }
            function save_tnc() {
                var tncid = $("#tncid").val();
                var proses = $("#tncproses").val();
                var program = $("#tncprogram").val();
                var fac = $("#tncfac").val();
                var city = $("#tnccity").val();
                var duration = $("#tncdur").val();
                var tncfrom = $("#tncfrom").val();
                var tnctill = $("#tnctill").val();
                var lastid = $("#tnclastid").val();
                var nextid = (lastid * 1) + 1;
                if (fac != '' && city != '' && duration != '' && tncfrom != '' && tnctill != '') {
                    loading();

                    if (flag == 'add') {
                        var idemployee = $("#idemp").val();
                    } else {
                        var idemployee = nip;
                    }

                    $.ajax({
                        url: ROOT.base_url + "mod_attendance/index.php/mst01/home/" + proses + "/" + idemployee,
                        data: "nextid=" + nextid + "&tncid=" + tncid + "&program=" + program.toUpperCase() + "&facilitator=" + fac.toUpperCase() + "&city=" + city.toUpperCase() + "&duration=" + duration + "&from=" + tncfrom + "&until=" + tnctill,
                        type: "post",
                        dataType: "json",
                        cache: false,
                        success: function(data) {
                            if (data != null) {
                                bootbox.alert(data.msg, function() {
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
                else {
                    $.gritter.add({
                        title: 'Please Fill All The Fields!',
                        text: "You can't save data without filling all the fields"
                    });
                }
            }
// end of tnc script ===========================================================
// languages script ===========================================================
            function get_lang() {
                if (flag == 'add') {
                    var idemployee = $("#idemp").val();
                } else {
                    var idemployee = nip;
                }


                $.ajax({
                    url: ROOT.base_url + "mod_attendance/index.php/mst01/home/get_language/" + idemployee,
                    data: "",
                    type: "post",
                    dataType: "json",
                    cache: false,
                    success: function(data) {
//                        alert(data.toSource());
                        $("#langlastid").val(data.lastid.lastid);
                        $("#alllang").empty();
                        var res = "";
                        for (var i = 0; i < data.data.length; i++) {
                            var btn_edit = "<button class='btn btn-mini btn-warning' onclick='edit_lang(\"" + data.data[i].IDLanguage + "\")'><i class='icon-pencil'></i></button>";
                            var btn_del = "<button class='btn btn-mini btn-danger' onclick='del_lang(\"" + data.data[i].IDLanguage + "\")'><i class='icon-trash'></i></button>";
                            res = res + "<tr class='selectable'><td class='upper'>" + data.data[i].Language + "</td><td>" + data.data[i].Listening + "</td><td>" + data.data[i].Reading + "</td><td>" + data.data[i].Conversation + "</td><td>" + data.data[i].Writing + "</td><td><center>" + btn_edit + btn_del + "</center></td></tr>";
                        }
                        $("#alllang").append(res);
                    }
                });
            }
            function edit_lang(langid) {
                $("#langproses").val("pedit_language");

                if (flag == 'add') {
                    var idemployee = $("#idemp").val();
                } else {
                    var idemployee = nip;
                }
                $.ajax({
                    url: ROOT.base_url + "mod_attendance/index.php/mst01/home/edit_language/" + idemployee,
                    data: "langid=" + langid,
                    type: "post",
                    dataType: "json",
                    cache: false,
                    success: function(data) {

                        $("#langid").val(langid);
                        $("#langlanguage").val(data.Language);
                        $("#langlisten").val(data.Listening);
                        $("#langread").val(data.Reading);
                        $("#langconv").val(data.Conversation);
                        $("#langwrite").val(data.Writing);
                        $("#langlastid").val("0");
                        $(".taballlang").hide({
                            duration: 300,
                            easing: "blind",
                            complete: function() {
                                $(".btnaddlang").hide();
                                $(".addlang").show({
                                    duration: 300,
                                    easing: "slide"
                                });
                            }
                        });
                    }
                });
            }
            function del_lang(langid) {
//                alert(idfam);
                bootbox.confirm("You are going to delete your Language from the list. Continue?", function(res) {
                    if (res == true) {
                        loading();

                        if (flag == 'add') {
                            var idemployee = $("#idemp").val();
                        } else {
                            var idemployee = nip;
                        }
                        $.ajax({
                            url: ROOT.base_url + "mod_attendance/index.php/mst01/home/del_language/" + idemployee,
                            data: "langid=" + langid,
                            type: "post",
                            dataType: "json",
                            cache: false,
                            success: function(data) {
                                bootbox.alert(data.msg, function() {
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
            function addlang() {
                $("#langproses").val("padd_language");
                $(".taballlang").hide({
                    duration: 300,
                    easing: "blind",
                    complete: function() {
                        $(".btnaddlang").hide();
                        $("#langlanguage").val("");
                        $("#langlisten").val("");
                        $("#langread").val("");
                        $("#langconv").val("");
                        $("#langwrite").val("");
                        $(".addlang").show({
                            duration: 300,
                            easing: "slide"
                        });
                    }
                });

            }
            function caddlang() {
                $(".addlang").hide({
                    duration: 300,
                    easing: "blind",
                    complete: function() {
                        $(".taballlang").show({
                            duration: 300,
                            easing: "slide",
                            complete: function() {
                                $(".btnaddlang").show();
                                chgtab("6");
                            }
                        });
                    }
                });
            }
            function save_lang() {
                var langid = $("#langid").val();
                var proses = $("#langproses").val();
                var language = $("#langlanguage").val();
                var listen = $("#langlisten").val();
                var read = $("#langread").val();
                var convers = $("#langconv").val();
                var write = $("#langwrite").val();
                var lastid = $("#langlastid").val();
                var nextid = (lastid * 1) + 1;
                if (language != '' && listen != '' && read != '' && convers != '' && write != '') {
                    loading();
                    if (flag == 'add') {
                        var idemployee = $("#idemp").val();
                    } else {
                        var idemployee = nip;
                    }
                    $.ajax({
                        url: ROOT.base_url + "mod_attendance/index.php/mst01/home/" + proses + "/" + idemployee,
                        data: "nextid=" + nextid + "&langid=" + langid + "&language=" + language.toUpperCase() + "&listen=" + listen + "&read=" + read + "&convers=" + convers + "&write=" + write,
                        type: "post",
                        dataType: "json",
                        cache: false,
                        success: function(data) {
//                            alert(data);
                            if (data != null) {
                                bootbox.alert(data.msg, function() {
                                    caddlang();
                                    bootbox.hideAll();
                                    $.gritter.add({
                                        title: 'Data Successfully Saved!',
                                        text: "You can move to the next tab or recheck the data you inserted"
                                    });
                                });
                            }
                        },
                        error: function(data) {
                            alert(data.toSource());
                        }
                    });
                } else {
                    $.gritter.add({
                        title: 'Please Fill All The Fields!',
                        text: "You can't save data without filling all the fields"
                    });
                }
            }
// end of lang script ===========================================================
// working experience script ===========================================================
            function get_work() {
                if (flag == 'add') {
                    var idemployee = $("#idemp").val();
                } else {
                    var idemployee = nip;
                }

                $.ajax({
                    url: ROOT.base_url + "mod_attendance/index.php/mst01/home/get_work/" + idemployee,
                    data: "",
                    type: "post",
                    dataType: "json",
                    cache: false,
                    success: function(data) {
//                        alert(data.toSource());
                        $("#worklastid").val(data.lastid.lastid);
                        $("#allwork").empty();
                        var res = "";
                        for (var i = 0; i < data.data.length; i++) {
                            var btn_edit = "<button class='btn btn-mini btn-warning' onclick='edit_work(\"" + data.data[i].IDWorkExp + "\")'><i class='icon-pencil'></i></button>";
                            var btn_del = "<button class='btn btn-mini btn-danger' onclick='del_work(\"" + data.data[i].IDWorkExp + "\")'><i class='icon-trash'></i></button>";
                            res = res + "<tr class='selectable'><td class='upper'>" + data.data[i].CompanyName + "</td><td>" + data.data[i].CompanyAddress + "</td><td>" + data.data[i].CompanyPhone + "</td><td>" + data.data[i].Position + "</td><td>" + data.data[i].WorkDuration + "</td><td><center>" + btn_edit + btn_del + "</center></td></tr>";
                        }
                        $("#allwork").append(res);
                    }
                });
            }
            function edit_work(workid) {
                $("#workproses").val("pedit_work");
                if (flag == 'add') {
                    var idemployee = $("#idemp").val();
                } else {
                    var idemployee = nip;
                }
                $.ajax({
                    url: ROOT.base_url + "mod_attendance/index.php/mst01/home/edit_work/" + idemployee,
                    data: "workid=" + workid,
                    type: "post",
                    dataType: "json",
                    cache: false,
                    success: function(data) {

                        $("#workid").val(workid);
                        $("#workcomp").val(data.CompanyName);
                        $("#workaddress").append(data.CompanyAddress);
                        $("#workphone").val(data.CompanyPhone);
                        $("#workpos").val(data.Position);
                        $("#workdur").val(data.WorkDuration);
                        $("#worklastid").val("0");
                        $(".taballwork").hide({
                            duration: 300,
                            easing: "blind",
                            complete: function() {
                                $(".btnaddwork").hide();
                                $(".addwork").show({
                                    duration: 300,
                                    easing: "slide"
                                });
                            }
                        });
                    }
                });
            }
            function del_work(workid) {
//                alert(idfam);
                bootbox.confirm("You are going to delete your Working Experience from the list. Continue?", function(res) {
                    if (res == true) {
                        loading();
                        if (flag == 'add') {
                            var idemployee = $("#idemp").val();
                        } else {
                            var idemployee = nip;
                        }
                        $.ajax({
                            url: ROOT.base_url + "mod_attendance/index.php/mst01/home/del_work/" + idemployee,
                            data: "workid=" + workid,
                            type: "post",
                            dataType: "json",
                            cache: false,
                            success: function(data) {
                                bootbox.alert(data.msg, function() {
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
            function addwork() {
                $("#workproses").val("padd_work");
                $(".taballwork").hide({
                    duration: 300,
                    easing: "blind",
                    complete: function() {
                        $(".btnaddwork").hide();
                        $("#workcomp").val("");
                        $("#workaddress").val("");
                        $("#workphone").val("");
                        $("#workpos").val("");
                        $("#workdur").val("");
                        $(".addwork").show({
                            duration: 300,
                            easing: "slide"
                        });
                    }
                });
            }
            function caddwork() {
                $(".addwork").hide({
                    duration: 300,
                    easing: "blind",
                    complete: function() {
                        $(".taballwork").show({
                            duration: 300,
                            easing: "slide",
                            complete: function() {
                                $(".btnaddwork").show();
                                chgtab("7");
                            }
                        });
                    }
                });
            }
            function save_work() {
                var workid = $("#workid").val();
                var proses = $("#workproses").val();
                var comp = $("#workcomp").val();
                var address = $("#workaddress").val();
                var phone = $("#workphone").val();
                var pos = $("#workpos").val();
                var dur = $("#workdur").val();
                var lastid = $("#worklastid").val();
                var nextid = (lastid * 1) + 1;

                if (flag == 'add') {
                    var idemployee = $("#idemp").val();
                } else {
                    var idemployee = nip;
                }
//                .toUpperCase()
//                alert(workid+"|"+proses+"|"+comp+"|"+address+"|"+phone+"|"+pos+"|"+dur+"|"+nextid);
                if (comp != '' && address != '' && phone != '' && pos != '' && dur != '') {
                    loading();
                    $.ajax({
                        url: ROOT.base_url + "mod_attendance/index.php/mst01/home/" + proses + "/" + idemployee,
                        data: "nextid=" + nextid + "&workid=" + workid + "&comp=" + comp + "&address=" + address + "&phone=" + phone + "&pos=" + pos + "&dur=" + dur,
                        type: "post",
                        dataType: "json",
                        cache: false,
                        success: function(data) {
                            //                        alert(data);
                            if (data != null) {
                                bootbox.alert(data.msg, function() {
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
                else {
                    $.gritter.add({
                        title: 'Please Fill All The Fields!',
                        text: "You can't save data without filling all the fields"
                    });
                }
            }
// end of working experiences script ===========================================================
            function addnohp() {
                inohp++;
                $("div.controls.nohps").append('<input class="span2 nohpke' + inohp + '" type="text" name="nohp[]"><button title="Delete ph no.?" class="btn-mini btn btn-danger nohpke' + inohp + '" onclick="remnohp(\'' + inohp + '\')">x</button>');
            }
            function remnohp(hpke) {
                $("input.nohpke" + hpke).remove();
                $("button.nohpke" + hpke).remove();
            }
            function getnohp() {

                var nohps = new Array();
                $("input[name='nohp[]']").each(function() {
                    var isi = $(this).val();
                    if (isi != '') {
                        nohps.push(isi);
                    }
//                    alert($(this).val().toSource());
                });
                nohps.join(",");
                return nohps;
            }
            function addtelpl() {
                itelpl++;
                $("div.controls.telpls").append('<input class="span2 telplke' + itelpl + '" type="text" name="telpl[]"><button title="Delete ph no.?" class="btn-mini btn btn-danger telplke' + itelpl + '" onclick="remtelpl(\'' + itelpl + '\')">x</button>');
            }
            function remtelpl(hpke) {
                $("input.telplke" + hpke).remove();
                $("button.telplke" + hpke).remove();
            }
            function gettelpl() {

                var telpls = new Array();
                $("input[name='telpl[]']").each(function() {
                    var isi = $(this).val();
                    if (isi != '') {
                        telpls.push(isi);
                    }
//                    alert($(this).val().toSource());
                });
                telpls.join(",");
                return telpls;
            }
            function addtelpk() {
                itelpk++;
                $("div.controls.telpks").append('<input class="span2 telpkke' + itelpk + '" type="text" name="telpk[]"><button title="Delete ph no.?" class="btn-mini btn btn-danger telpkke' + itelpk + '" onclick="remtelpk(\'' + itelpk + '\')">x</button>');
            }
            function remtelpk(hpke) {
                $("input.telpkke" + hpke).remove();
                $("button.telpkke" + hpke).remove();
            }
            function gettelpk() {

                var telpks = new Array();
                $("input[name='telpk[]']").each(function() {
                    var isi = $(this).val();
                    if (isi != '') {
                        telpks.push(isi);
                    }
//                    alert($(this).val().toSource());
                });
                telpks.join(",");
                return telpks;
            }
            function addexmail() {
                iexmail++;
                $("div.controls.exmails").append('<input class="span2 exmailke' + iexmail + '" type="text" name="exmail[]"><button title="Delete email?" class="btn-mini btn btn-danger exmailke' + iexmail + '" onclick="remexmail(\'' + iexmail + '\')">x</button>');
            }
            function remexmail(hpke) {
                $("input.exmailke" + hpke).remove();
                $("button.exmailke" + hpke).remove();
            }
            function getexmail() {

                var exmails = new Array();
                $("input[name='exmail[]']").each(function() {
                    var isi = $(this).val();
                    if (isi != '') {
                        exmails.push(isi);
                    }
//                    alert($(this).val().toSource());
                });
                exmails.join(",");
                return exmails;
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
                        <?php
                        if ($flag == 'add') {
                            ?> 
                            <li class="span1 tab2 tabatas glyphicons building active">
                                <a href="#tab2" onclick="chgtab('2')" data-toggle="tab"><i></i> 
                                    Job Description                               
                                </a>
                            </li> 

                            <li class="span3 tab1 tabatas glyphicons user">
                                <a href="#tab1" onclick="chgtab('1')" data-toggle="tab"><i></i>
                                    Personal Details                               
                                </a>
                            </li>


                        <?php } else { ?>                        

                            <li class="span3 tab1 tabatas glyphicons user active">
                                <a href="#tab1" onclick="chgtab('1')" data-toggle="tab"><i></i>
                                    Personal Details                               
                                </a>
                            </li>
                            <li class="span1 tab2 tabatas glyphicons building">
                                <a href="#tab2" onclick="chgtab('2')" data-toggle="tab"><i></i> 
                                    Job Description                               
                                </a>
                            </li>                        
                        <?php } ?>
                        <li class="span1 tab3 tabatas glyphicons group">
                            <a href="#tab3" onclick="chgtab('3')" data-toggle="tab"><i></i> 
                                Family Informations                                
                            </a>
                        </li>
                        <li class="span1 tab4 tabatas glyphicons book_open">
                            <a href="#tab4" onclick="chgtab('4')" data-toggle="tab"><i></i> 
                                Education Background                                 
                            </a>
                        </li>
                        <li class="span1 tab5 tabatas glyphicons buoy">
                            <a href="#tab5" onclick="chgtab('5')" data-toggle="tab"><i></i> 
                                Training & Course Attended                                
                            </a>
                        </li>
                        <li class="span1 tab6 tabatas glyphicons globe">
                            <a href="#tab6" onclick="chgtab('6')" data-toggle="tab"><i></i> 
                                Language                                
                            </a>
                        </li>
                        <li class="span1 tab7 tabatas glyphicons certificate">
                            <a href="#tab7" onclick="chgtab('7')" data-toggle="tab"><i></i> 
                                Working Experience                                
                            </a>
                        </li>
                        <?php if ($flag == 'add') { ?>
                            <li class="span1 tab8 tabatas glyphicons user_add">
                                <a href="#tab8" onclick="chgtab('8')" data-toggle="tab"><i></i> 
                                    Active  Employee                              
                                </a>
                            </li>
                        <?php } else { ?>
                            <li class="span1 tab8 tabatas glyphicons user_remove">
                                <a href="#tab8" onclick="chgtab('8')" data-toggle="tab"><i></i> 
                                    Passive  Employee                              
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="tab-content">          

                    <?php if ($flag == 'add') { ?>
                        <div class="tab-pane" id="tab1">   
                        <?php } else { ?>    
                            <div class="tab-pane active" id="tab1">   
                            <?php } ?>  


                            <h4>Personal Details</h4>
                            <div class="widget widget-body step1">
                                <h3>Step 1</h3>
                                <hr class="separator">
                                <div class="row-fluid">
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="fname"><b>Full Name</b> / <i class="transindo">Nama Lengkap</i></label>
                                            <div class="controls">
                                                <input type="text" class="span12" id="fname">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="nname"><b>Nick Name</b> / <i class="transindo">Nama Panggilan</i></label>
                                            <div class="controls">
                                                <input type="text" class="span10" id="nname">
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
                                                <input type="text" class="span12" id="pbirth">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span2">
                                        <div class="control-group">
                                            <label style="overflow: visible; " for="dbirth"><b>Date of Birth</b> / <i class="transindo">Tanggal Lahir</i></label>
                                            <div class="controls">
                                                <input type="text" class="span12" id="dbirth">
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
                                                <input type="text" maxlength="3" class="span6" id="bheight" pattern="\d*">cm
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span2">
                                        <div class="control-group">
                                            <label for="bweight"><b>Weight</b> / <i class="transindo">Berat Badan</i></label>
                                            <div class="controls">
                                                <input type="text" maxlength="3" class="span6" id="bweight" pattern="\d*">kg
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
                                                <select class="span12" id="gender">
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
                                                <select class="span4" id="tblood">
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="A/B">A/B</option>
                                                    <option value="O">O</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="separator">
                                <div class="row-fluid">
                                    <div class="span8" style="text-align: center">
                                        <div class="pagination margin-bottom-none">
                                            <ul>
                                                <li onclick="tostep(1, 1)" class="primary"><a >Step 1</a></li>
                                                <li onclick="tostep(2, 1)"><a >Step 2</a></li>
                                                <li onclick="tostep(3, 1)"><a >Step 3</a></li>
                                                <li onclick="tostep(4, 1)"><a >Step 4</a></li>
                                                <li onclick="tostep(5, 1)"><a >Step 5</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="span4" style="text-align: center">
                                        <div class="pagination margin-bottom-none">
                                            <ul>
                                                <li onclick="tostep(1, 1)"><a class="btn btn-icon btn-primary glyphicons left_arrow" disabled><i></i>Prev</a></li>
                                                <li onclick="tostep(2, 1)"><a class="btn btn-icon btn-primary glyphicons right_arrow"><i></i>Next</a></li>
                                            </ul>
                                        </div>
                                        <!--<button onclick="resetstep(1)" class="btn btn-icon btn-default glyphicons refresh"><i></i>Reset</button>-->
                                        <!--<button onclick="nextstep(2)" class="btn btn-icon btn-primary glyphicons right_arrow"><i></i>Next</button>-->
                                    </div>
                                </div>
                            </div>
                            <!--==================================================================================-->
                            <div class="widget widget-body step2">
                                <h3>Step 2</h3>
                                <hr class="separator">
                                <!--===================-->                         
                                <div class="row-fluid">
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="czship"><b>Citizenship</b> / <i class="transindo">Kewarganegaraan</i></label>
                                            <div class="controls">
                                                <input type="text" class="span12" id="czship">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="religion"><b>Religion</b> / <i class="transindo">Agama</i></label>
                                            <div class="controls">
                                                <select class="span12" id="religion">
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
                                                <input type="text" class="span12" id="noktp">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="nonpwp"><b>NPWP Number</b> / <i class="transindo">Nomor NPWP</i></label>
                                            <div class="controls">
                                                <input type="text" class="span12" id="nonpwp">
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
                                                <input type="text" class="span12" id="nojamsos">
                                            </div>
                                        </div>        
                                    </div>
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="nokpj"><b>KPJ Number</b> / <i class="transindo">Nomor KPJ</i></label>
                                            <div class="controls">
                                                <input type="text" class="span12" id="nokpj">
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
                                                <input type="text" id="abank" class="span12">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="separator">
                                <div class="row-fluid">
                                    <div class="span8" style="text-align: center">
                                        <div class="pagination margin-bottom-none">
                                            <ul>
                                                <li onclick="tostep(1, 2)"><a >Step 1</a></li>
                                                <li onclick="tostep(2, 2)" class="primary"><a >Step 2</a></li>
                                                <li onclick="tostep(3, 2)"><a >Step 3</a></li>
                                                <li onclick="tostep(4, 2)"><a >Step 4</a></li>
                                                <li onclick="tostep(5, 2)"><a >Step 5</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="span4" style="text-align: center">
                                        <div class="pagination margin-bottom-none">
                                            <ul>
                                                <li onclick="tostep(1, 2)"><a class="btn btn-icon btn-primary glyphicons left_arrow"><i></i>Prev</a></li>
                                                <li onclick="tostep(3, 2)"><a class="btn btn-icon btn-primary glyphicons right_arrow"><i></i>Next</a></li>
                                            </ul>
                                        </div>
                                        <!--<button onclick="resetstep(1)" class="btn btn-icon btn-default glyphicons refresh"><i></i>Reset</button>-->
                                        <!--<button onclick="nextstep(2)" class="btn btn-icon btn-primary glyphicons right_arrow"><i></i>Next</button>-->
                                    </div>
                                </div>
                            </div>
                            <!--==================================================================================-->
                            <div class="widget widget-body step3">
                                <h3>Step 3</h3>
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
                                                <label><input type="radio" name="marital" value="SINGLE">Single / <i class="transindo">Belum Menikah</i></label>
                                            </div>
                                            <div class="span2">
                                                <label><input type="radio" name="marital" value="MARRIED">Married / <i class="transindo">Menikah</i></label>
                                            </div>
                                            <div class="span2">
                                                <label><input type="radio" name="marital" value="DIVORCED">Divorced / <i class="transindo">Janda / Duda</i></label>                                   
                                            </div>                                        
                                        </div>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="marrcert"><b>Marriage Certificate</b> / <i class="transindo">Surat Kawin</i></label>
                                            <div class="controls">
                                                <select id="marrcert" class="span5">
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
                                                <select id="famcert" class="span4">
                                                    <option value="0">---</option>
                                                    <option value="yes">YES</option>
                                                    <option value="no">NO</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="coupname"><b>Couple Name</b> / <i class="transindo"> Nama Pasangan</i></label>
                                            <div class="controls">
                                                <input class="span12" value="0" type="text" id="coupname">
                                            </div>
                                        </div>                                    
                                    </div>
                                    <div class="span4">
                                        <div class="control-group">
                                            <label for="couplektp"><b>Couple KTP Number</b> / <i class="transindo"> Nomor KTP Pasangan</i></label>
                                            <div class="controls">
                                                <input class="span10" value="0" type="text" id="couplektp">
                                            </div>
                                        </div>                                    
                                    </div>
                                </div>
                                <div class="row-fluid">
                                    <div class="span4">
                                        <div class="control-group">
                                            <label for="nchild"><b>Number of Children</b> / <i class="transindo"> Jumlah Anak</i> : </label>
                                            <div class="controls">
                                                <input class="span2" value="0" type="text" id="nchild">
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <hr class="separator">
                                <div class="row-fluid">
                                    <div class="span8" style="text-align: center">
                                        <div class="pagination margin-bottom-none">
                                            <ul>
                                                <li onclick="tostep(1, 3)"><a >Step 1</a></li>
                                                <li onclick="tostep(2, 3)"><a >Step 2</a></li>
                                                <li onclick="tostep(3, 3)" class="primary"><a >Step 3</a></li>
                                                <li onclick="tostep(4, 3)"><a >Step 4</a></li>
                                                <li onclick="tostep(5, 3)"><a >Step 5</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="span4" style="text-align: center">
                                        <div class="pagination margin-bottom-none">
                                            <ul>
                                                <li onclick="tostep(2, 3)"><a class="btn btn-icon btn-primary glyphicons left_arrow"><i></i>Prev</a></li>
                                                <li onclick="tostep(4, 3)"><a class="btn btn-icon btn-primary glyphicons right_arrow"><i></i>Next</a></li>
                                            </ul>
                                        </div>
                                        <!--<button onclick="resetstep(1)" class="btn btn-icon btn-default glyphicons refresh"><i></i>Reset</button>-->
                                        <!--<button onclick="nextstep(2)" class="btn btn-icon btn-primary glyphicons right_arrow"><i></i>Next</button>-->
                                    </div>
                                </div>
                            </div>
                            <!--==================================================================================-->
                            <div class="widget widget-body step4">
                                <h3>Step 4</h3>
                                <hr class="separator">
                                <!--===================-->
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="control-group">                                        
                                            <div class="controls nohps">
                                                <label for="nohp"><b>Mobile Phone Number</b> / <i class="transindo">Nomor HP</i></label>
                                                <input class="span2" type="text" name="nohp[]">
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
                                                <input type="text" id="inemail" class="span12">
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
                                <hr class="separator">
                                <div class="row-fluid">
                                    <div class="span8" style="text-align: center">
                                        <div class="pagination margin-bottom-none">
                                            <ul>
                                                <li onclick="tostep(1, 4)"><a >Step 1</a></li>
                                                <li onclick="tostep(2, 4)"><a >Step 2</a></li>
                                                <li onclick="tostep(3, 4)"><a >Step 3</a></li>
                                                <li onclick="tostep(4, 4)"  class="primary"><a >Step 4</a></li>
                                                <li onclick="tostep(5, 4)"><a >Step 5</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="span4" style="text-align: center">
                                        <div class="pagination margin-bottom-none">
                                            <ul>
                                                <li onclick="tostep(3, 4)"><a class="btn btn-icon btn-primary glyphicons left_arrow"><i></i>Prev</a></li>
                                                <li onclick="tostep(5, 4)"><a class="btn btn-icon btn-primary glyphicons right_arrow"><i></i>Next</a></li>
                                            </ul>
                                        </div>
                                        <!--<button onclick="resetstep(1)" class="btn btn-icon btn-default glyphicons refresh"><i></i>Reset</button>-->
                                        <!--<button onclick="nextstep(2)" class="btn btn-icon btn-primary glyphicons right_arrow"><i></i>Next</button>-->
                                    </div>
                                </div>
                            </div>
                            <div class="widget widget-body step5">
                                <h3>Step 5</h3>
                                <hr class="separator">
                                <!--===================-->
                                <div class="row-fluid">
                                    <div class="span4">
                                        <div class="control-group">
                                            <label for="laddress"><b>Live Address</b> / <i class="transindo">Alamat Sekarang</i></label>
                                            <div class="controls">
                                                <textarea id="laddress" class="span12"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span8">
                                        <div class="control-group">
                                            <div class="controls telpls">
                                                <label for="laddressph"><b>House Phone Number</b> / <i class="transindo">Nomor Telepon Rumah</i></label>
                                                <input class="span2" type="text" name="telpl[]">
                                            </div>
                                            <a class="btn btn-mini btn-default glyphicons circle_plus" onclick="addtelpl()"><i></i>add</a>
                                        </div>
                                    </div>
                                </div>
                                <!--===================-->
                                <div class="row-fluid">
                                    <div class="span4">
                                        <div class="control-group">
                                            <label for="ktpaddress"><b>KTP Address</b> / <i class="transindo">Alamat di KTP</i></label>
                                            <div class="controls">
                                                <textarea id="ktpaddress" class="span12"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span8">
                                        <div class="control-group">
                                            <div class="controls telpks">
                                                <label for="ktpaddressph"><b>House Phone Number</b> / <i class="transindo">Nomor Telepon Rumah</i></label>
                                                <input class="span2" type="text" name="telpk[]">
                                            </div>
                                            <a class="btn btn-mini btn-default glyphicons circle_plus" onclick="addtelpk()"><i></i>add</a>
                                        </div>
                                    </div>
                                </div>
                                <hr class="separator">
                                <div class="row-fluid">
                                    <div class="span8" style="text-align: center">
                                        <div class="pagination margin-bottom-none">
                                            <ul>
                                                <li onclick="tostep(1, 5)"><a >Step 1</a></li>
                                                <li onclick="tostep(2, 5)"><a >Step 2</a></li>
                                                <li onclick="tostep(3, 5)"><a >Step 3</a></li>
                                                <li onclick="tostep(4, 5)"><a >Step 4</a></li>
                                                <li onclick="tostep(5, 5)" class="primary"><a >Step 5</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="span4" style="text-align: center">
                                        <div class="pagination margin-bottom-none">
                                            <ul>
                                                <li onclick="tostep(4, 5)"><a class="btn btn-icon btn-primary glyphicons left_arrow"><i></i>Prev</a></li>
                                                <li onclick="savepersonal()"><a class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Save</a></li>
                                            </ul>
                                        </div>
                                        <!--<button onclick="resetstep(1)" class="btn btn-icon btn-default glyphicons refresh"><i></i>Reset</button>-->
                                        <!--<button onclick="nextstep(2)" class="btn btn-icon btn-primary glyphicons right_arrow"><i></i>Next</button>-->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($flag == 'add') { ?>
                            <div class="tab-pane active" id="tab2">   
                            <?php } else { ?>    
                                <div class="tab-pane" id="tab2">   
                                <?php } ?>  

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
                                                            <input type="text" class="span12" id="idemp">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span8">
                                                    <div class="control-group">
                                                        <label for="idparent"><b>Manager/Director ID</b> / <i class="transindo">NIP Atasan</i></label>
                                                        <div class="controls">
                                                            <input type="text" class="span6" id="idparent">
                                                            <input type="text" class="span6 upper" id="nmparent">
                                                        </div>
                                                    </div>                                    
                                                </div>
                                            </div>
                                            <!--===================-->
                                            <div class="row-fluid">
                                                <div class="span4">
                                                    <div class="control-group">
                                                        <label for="fullname"><b>Full Name</b> / <i class="transindo">Nama Lengkap</i></label>
                                                        <div class="controls">
                                                            <input type="text" class="span12" id="fullname">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span4">
                                                    <div class="control-group">
                                                        <label for="nickname"><b>Nick Name</b> / <i class="transindo">Nama Panggilan</i></label>
                                                        <div class="controls">
                                                            <input type="text" class="span10" id="nickname">
                                                        </div>
                                                    </div>        
                                                </div>
                                            </div>

                                            <div class="row-fluid">
                                                <div class="span4">
                                                    <div class="control-group">
                                                        <label for="jobloc"><b>Working Location</b> / <i class='transindo'>Lokasi Kerja</i></label>
                                                        <div class="controls">
                                                            <select class="span12" id="jobloc">
                                                                <option value="KAPUK">KAPUK</option>
                                                                <option value="BITUNG">BITUNG</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span4">
                                                    <div class="control-group">
                                                        <label for="jobgrp"><b>Group</b> / <i class='transindo'>Status Karyawan</i></label>
                                                        <div class="controls">
                                                            <select class="span12" id="jobgrp">
                                                                <option value="ST">STAFF</option>
                                                                <option value="LT">LAP TETAP</option>
                                                                <option value="LK">LAP KONTRAK</option>
								<option value="MAG">MAGANG</option>
								<option value="OS">MITRA KERJA</option>
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
                                                            <select class="span12" id="depart">
                                                                <?php
                                                                foreach ($departement->result() as $dprt) {
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
                                                            <select class="upper span12" id="jobpos">
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
								<option value="MITRA KERJA">MITRA KERJA</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-----
                                            <div class="row-fluid">
                                                <div class="span4">
                                                    <div class="control-group">
                                                        <label for="unitjob"><b>Unit</b> / <i class='transindo'>Unit</i></label>
                                                        <div class="controls">
                                                            <input class="upper span12" id="unitjob" type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span4">
                                                    <div class="control-group">
                                                        <label for="empstat"><b>Status</b> / <i class='transindo'>Status</i></label>
                                                        <div class="controls">
                                                            <input class="upper span12" id="empstat" type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            ---->
                                            <div class="row-fluid">                                               
                                                <div class="span4">
                                                    <div class="control-group">
                                                        <label for="nobank"><b>Bank Account</b> / <i class='transindo'>Akun Bank</i></label>
                                                        <div class="controls">
                                                            <input class="upper span12" id="nobank" type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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

                                            <div class="row-fluid">
                                                <div class="span6">
                                                    <div class="control-group">
                                                        <label for="note"><b>Description </b> / <i class='transindo'>Keterangan</i></label>
                                                        <div class="controls">
                                                            <textarea id="note" class="span12"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane" id="tab3">
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
                                            <div class="span3">
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

                            <div class="tab-pane" id="tab8"> 

                                <h4>Passive / Active Employee</h4>
                                <div class="widget widget-body exitinterview">
                                    <div class="row-fluid">
                                        <div class="span8">
                                            <!--===================-->
                                            <div class="row-fluid">
                                                <div class="span4">
                                                    <div class="control-group">
                                                        <label for="nip"><b>Employee ID</b> / <i class="transindo">NIP</i></label>
                                                        <div class="controls">
                                                            <input type="text" class="span12" id="nip">                                                            
                                                        </div>
                                                    </div>
                                                </div> 

                                                <div class="span4">
                                                    <div class="control-group">
                                                        <label for="fullname"><b>Fullname</b> / <i class='transindo'>Nama Lengkap</i></label>
                                                        <div class="controls">
                                                            <input type="text" class="span12" id="fullname">   
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--===================-->


                                            <div class="row-fluid">
                                                <div class="span2">
                                                    <label for="status"><b>Status</b></label>
                                                </div>
                                            </div>
                                            <div class="row-fluid">
                                                <div class="control-group">
                                                    <div class="controls">
                                                        <div class="span2">
                                                            <label><input type="radio" name="statusemployee" value="A">Active / <i class="transindo">Aktif</i></label>
                                                        </div>
                                                        <div class="span2">
                                                            <label><input type="radio" name="statusemployee" value="P">Passive / <i class="transindo">Pasif</i></label>
                                                        </div>                                                                                              
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row-fluid">
                                                <div class="span4">
                                                    <div class="control-group">
                                                        <label for="tglmasuk"><b>Hire Date</b> / <i class='transindo'>Kontrak</i></label>
                                                        <div class="controls">
                                                            <input type="text" class="span12" id="tglmasuk">  
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="span4">
                                                    <div class="control-group">
                                                        <label for="tglkeluar"><b>Resign Date</b> / <i class='transindo'>Mengundurkan diri</i></label>
                                                        <div class="controls">
                                                            <input type="text" class="span12" id="tglkeluar">  

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
					    <div class="row-fluid">
                                                <div class="span2">
                                                    <label for="status"><b>Send Email</b></label>
                                                </div>
                                            </div>
                                            <div class="row-fluid">
                                                <div class="control-group">
                                                    <div class="controls">
                                                        <div class="span2">
                                                            <label><input type="radio" name="sendmail" value="Y">Yes / <i class="transindo">Ya</i></label>
                                                        </div>
                                                        <div class="span2">
                                                            <label><input type="radio" name="sendmail" value="N">No / <i class="transindo">Tidak</i></label>
                                                        </div>                                                                                              
                                                    </div>
                                                </div>
                                            </div>
						
                                            <!--===================-->
                                            <div class="row-fluid">
                                                <div class="span4">
                                                    <div class="control-group">
                                                        <label for="reasonresgin"><b>Reason to Resign</b> / <i class='transindo'>Alasan mengundurkan diri</i></label>
                                                        <div class="controls">
                                                            <textarea rows="4" cols="100" id="reasonresgin" class="span12"></textarea>
                                                        </div>
                                                    </div>
                                                </div>                                              
                                            </div>
                                            <hr class="separator">
                                            <div class="row-fluid">
                                                <div class="span12" style="text-align:center;">
                                                    <button onclick="save_exitinterview()" class="btn btn-success btn-icon glyphicons circle_ok"><i></i>Save</button>
                                                </div>
                                            </div>
                                            <!--===================-->


                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>

                        </body>
                        </html>

