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
        <!-- hide the close link in the toolbar -->

        <style type="text/css">
	     a.ui-dialog-titlebar-close { display:block; }
            i{font-size: 10px;}
            .cnfmd{color: #00CC00;font-weight: bold;}
            .waitingg{color: #EC5800;font-weight: bold;}
            .rejectedd{color: #ee1e2d;font-weight: bold;}
            
            .label_error_cuti{color : #be362f;}
        </style>
        <!-- Bootstrap -->
<!--        <link href="<?php echo $base_url; ?>public/bootstrap/css/bootstrap.css" rel="stylesheet" />
        <link href="<?php echo $base_url; ?>public/bootstrap/css/responsive.css" rel="stylesheet" />-->
      
        <!-- Gritter Notifications Plugin -->
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/notifications/Gritter/css/jquery.gritter.css" rel="stylesheet" />

        <!-- DataTables Plugin -->
        <link href="<?php echo $base_url; ?>public/theme/scripts/plugins/tables/DataTables/media/css/DT_bootstrap.css" rel="stylesheet" />
	
        <!-- JQuery -->
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-latest.js"></script>
        <!-- Bootstrap -->
        <!--<script src="<?php echo $base_url; ?>public/bootstrap/js/bootstrap.js"></script>--> 
	<script src="<?php echo $base_url; ?>public/bootstrap/js/popup.js"></script>   
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
        <script type="text/javascript" charset="utf-8">
            var ROOT = {
                'site_url'  : '<?php echo $base_url . '/index.php'; ?>',
                'base_url'  : '<?php echo $base_url; ?>'
            };
                
                /*
                var utk = <?php //echo $utk;?>;
                var checkald = '<?php //echo $checkald; ?>';
                var amountpicket = '<?php //echo $amountpicket;?>';                
                
                if(checkald=='exist'){
                     var sisa_al = (utk)+(amountpicket);
                }else{
                     var sisa_al = (utk)-(6) +Number((amountpicket));
                }
                
            
            alert('Range :'+utk+' Check ALD :'+checkald+ ' Amount Picket :'+amountpicket+' Rest of Annual Leave :'+sisa_al);
            */
               
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                var d,cth,cmo,cda,th,mo,da,minth,minmo,minda,p;
                cth = <?php echo date('Y'); ?>;
                cmo = <?php echo date('m'); ?>;
                cda = <?php echo date('d'); ?>;
                $("#dari").prop("disabled",true);
                //datepicker
                $("#dari").datepicker( {changeMonth: true,
                 changeYear : true,
                 beforeShow : minldate,
                 maxDate    : "+1Y",
                 dateFormat :"dd-mm-yy"},"showAnim","clip");
                    $("#formtrx").hide();
                    $("#sampai").prop('disabled', true);
                    $('#deletedari').click(function(){
                        $('#dari').val('').focus();
                        $('#sampai').val('');
                        $("#sampai").prop('disabled', true);
                        $("#total").val('');
                        $("#sisa").val('');
                    });
                    $('#deletesampai').click(function(){
                        $('#sampai').val('').focus();
                    });
                    $('#dari').keydown(false);
                    $('#sampai').keydown(false);
                    $('#dari').change(function(){
                        if ($('#dari').val() != ''){
                                $("#sampai").prop('disabled', false);
                                $("#sampai").val($("#dari").val());
                                total_cuti();
                        }
                    });
            });
            //-=-=-=-=-=-=-=-=-=
            $("input[type='radio'][name='jenis']").click(
                function (){
                    if ($(this).val() == "CL"){
                        $(".blokpgt").remove();
                        $("#ketcl").show({});
                    }else{
                        $("#ketcl").hide({});
                    }
                    if ($(this).val() == "SL"){
                        $(".blokpgt").remove();
                        $("#ketsl").show({});
                    }else{
                        $("#ketsl").hide({});
                    }
                    if ($(this).val() == 'MRL'){
                        $("#ketmrl").show({});
                    }else{
                        $("#ketmrl").hide({});
                    }
                    if ($(this).val() == 'MTL'){
                        $("#ketmtl").show({});
                    }else{
                        $("#ketmtl").hide({});
                    }
                    
                    $("#dari").prop("disabled",false);
                    $("#dari").val('');
                    $("#sampai").val('');
                }
            );
    var stat = "",bjenis,balasan,bidpgt,bnmpgt,bdari,bsampai,btotal,bsisa,bsickletter,bconcl,bconmrl,bconmtl;
            function minldate(){
                var checked = $("input[type='radio'][name='jenis']:checked").val();
                //alert(checked);
                if (checked == "AL"){
                    minda  = "+7D";
                    var minlDate = minda;
                }
                if (checked == "CL"){
                    //minda  = "-2D";
		    minda  = "";	
                    var minlDate = minda;
                }
                if (checked == "CIR"){
                    //minda  = "+1D";
		    minda  = "";		
                    var minlDate = minda;
                }
                if (checked == "SL"){
                    minda  = "";
                    var minlDate = minda;
                }
                if (checked == "MTL"){
                    minda  = "";
                    var minlDate = minda;
                }
                if (checked == "MRL"){
                    minda  = "+1D";
                    var minlDate = minda;
                }
                if (checked == "OL"){
                    //minda  = "+1D";
		    minda  = "";		
                    var minlDate = minda;
                }
                //alert(minlDate);
                return{
                    minDate : minlDate
                }
            }
            var data_pengganti = <?php echo $pengganti?>;
            $(function() {
                $( "#nmpengganti" ).autocomplete({
                     source: data_pengganti,
                     select: function (event, ui){
                          $("#idpengganti").val(ui.item.IDEmployee);
                     }
                 });
            });

            //=-=-=-=-=-=-=-=--
            function loading(){
                bootbox.alertloading("<center>Processing. Please wait !!!<br><img src='<?php echo $base_url; ?>public/avatar/76.GIF'></center>");
            }
            function reloadtrx(){
                var content = $("#content .innerLR");
                var url = ROOT.base_url + 'mod_empcenter/index.php/trx01/home';
                //alert(url);
                content.fadeOut("slow", "linear");
                content.load(url);
                content.fadeIn("slow");
            }
            function del_pgt(){
                $("#nmpengganti").val("");
                $("#idpengganti").val("");
            }
            function add(){
                // end of pendefinisian mindate
                $("#stype").val("add_process");
                $("#listtrx").effect("blind","slow").hide();
                $("#formtrx").effect("slide","slow").show();
            }

	    function printdata(idtrx) {
                $(document).ready(function()
                {
                    var url_iframe = "<?php echo site_url(); ?>/trx01/home/iframe";
                    //alert(url_print + "/" + id);
                    $.ajax({
                        type: "POST",
                        url: url_iframe + "/" + idtrx,
                        success: function(data) {
                            $('#leave-form').html(data);
                            $("#leave-form").dialog("open");
                            return false;
                        }
                    });
                });

            }	

            function edit(idtrx){
                //bootbox.alert(idtrx);
                $("#stype").val("edit_process");
                $("#listtrx").effect("blind","slow").hide();
                $.ajax({
                    url 	: "<?php echo site_url();?>/trx01/home/edit",
                    data	: "idleave="+idtrx,
                    type	: "POST",
                    dataType    : "json",
                    cache	: false,
                    success	: 
                        function (data){
                            stat = data.idleave;
                            $("#dari").prop("disabled",false);
                            $("#alasan").val(data.alasan);
                            balasan = data.alasan;
                            $("#dari").val(data.dari);
                            bdari   = data.dari;
                            $("#sampai").val(data.sampai);
                            bsampai = data.sampai;
                            $("#total").val(data.total);
                            btotal  = data.total;
                            $("#sisa").val(data.sisa);
                            bsisa   = data.sisa;
                            $("#idpengganti").val(data.idpengganti);
                            bidpgt  = data.idpengganti;
                            $("#nmpengganti").val(data.nmpengganti);
                            bnmpgt  = data.nmpengganti;
                            $("input[type='radio'][name='jenis'][value='"+data.jenis+"']").prop('checked',true);
                            bjenis  = data.jenis;
                            if (data.jenis == "SL"){
                                $("#ketsl").show({});
                                $("#sickletter").find("option[value='"+data.sickletter+"']").prop('selected',true);
                                bsickletter = data.sickletter;                                
                            }
                            if (data.jenis == "CL"){
                                $("#ketcl").show({});
                                $("#concl").find("option[value='"+data.concl+"']").prop('selected',true);
                                bconcl      = data.concl;                                
                            }
                            if (data.jenis == "MRL"){
                                $("#ketmrl").show({});
                                $("#conmrl").find("option[value='"+data.conmrl+"']").prop('selected',true);
                                bconmrl      = data.conmrl;                                
                            }
                            if (data.jenis == "MTL"){
                                $("#ketmtl").show({});
                                $("#conmtl").find("option[value='"+data.conmtl+"']").prop('selected',true);
                                bconmtl     = data.conmtl;
                            }

                            // bootbox.alert(data.jenis);
                            $("#formtrx").append("<input type='hidden' id='idl' value='"+data.idleave+"'>");
                            if (data.fpgt == 'true' && data.idpengganti != ''){
                                $("#nmpengganti").prop("disabled",true);
                                $("#nmpengganti").keydown(false);
                                $("#btndelpgt").hide();
                            }
                            //======-------------------------
                            d		= new Date("<?php echo date('Y-m-d');?>");
                            d.setDate(d.getDate());
                            cth    	= d.getFullYear();//"<?php echo date('Y');?>";// current tahun
                            cmo    	= d.getMonth();//"<?php echo date('m');?>";// current month
                            cda    	= d.getDate();//"<?php echo date('d');?>";  
                            p		= new Date(data.tglpengajuan);
                            p.setDate(p.getDate());
                            th     	= p.getFullYear();
                            mo     	= p.getMonth();
                            da		= p.getDate();
                            // pendefinisian mindate; tahun bulan dan tanggalnya
                            if (th == cth){
                                    minth = "-0Y";
                            }
                            if (th != cth){
                                    minth = th-cth+"Y";
                            }
                            if (mo == cmo){
                                    minmo = "-0M";
                            }
                            if (mo != cmo){
                                    minmo = mo-cmo+"M";
                            }
                            if (da != cda){
                                    minda = (da-cda+7)+"D";
                            }
                            if (da == cda){
                                    minda = "+7D";
                            }
                            //minda  = "+7D";
                            //alert(d+"|||"+p);
                            //alert(th+"-"+mo+"|"+cth+"-"+cmo+"|"+minth+minmo+minda);
                            $("#dari").datepicker( {changeMonth: true,
                             changeYear : true,
                             minDate    : minth+minmo+minda,
                             maxDate    : "+1Y",
                             dateFormat :"dd-mm-yy"},"showAnim","clip");
                            // end of pendefinisian mindate
                        }
                });
                $("#formtrx").effect("slide","slow").show();
            }
            function del_trx(idtrx){
                //bootbox.alert(idtrx);
                bootbox.confirm("Anda yakin akan menghapus data cuti Anda?", function(result){
                    if (result == true){
                        $.ajax({
                            url		:"<?php echo site_url();?>/trx01/home/delete",
                            data	:"idleave="+idtrx,
                            type	:"POST",
                            dataType    :"json",
                            cache	:false,
                            success	:
                                function (data){
                                    if (data.status == "oke"){
                                        reloadtrx();
                                        $.gritter.add({
                                                title: 'Terhapus!',
                                                text: "data permohonan cuti anda telah terhapus"
                                        });
                                    }
                                }
                        });

                    }
                });
            }
            function cancelact(){               
                $("#formtrx").effect("blind","slow").hide();
                $("#listtrx").effect("slide","slow").show();
                reloadtrx();
                reset_formulir();
            }
          	
		
	       function simpan(){
                var sisa_al,status_al,calculate_al,param_al;
                var utk = <?php echo $utk;?>;
                
                
                var proses	= $("#stype").val();
                var idl		= $("#idl").val();
                var sickletter  = $("#sickletter").val();
                var jenis	= $("input[type='radio'][name='jenis']:checked").val();
                var concl       = $("#concl").val();
                var conmrl      = $("#conmrl").val();
                var alasan	= $("#alasan").val();
                var dari	= $("#dari").val();
                var sampai	= $("#sampai").val();
                var total	= $("#total").val();
                var sisa        = $("#sisa").val();
                var idpengganti	= $("#idpengganti").val();
                
                var checkald = '<?php echo $checkald; ?>';
                var amountpicket = '<?php echo $amountpicket;?>';
                
                if(checkald=='exist'){
                     var sisa_al = (utk)+Number(amountpicket);
                }else{
                     var sisa_al = (utk)-(6) +Number(amountpicket);
                }
                
                
                if(sisa_al >=1){
                     calculate_al = sisa_al - total;
                     if(calculate_al >= 0){
                         param_al ='entrydata'; 
                     }else{
                         param_al ='blockdata'; 
                        }
                     status_al = param_al;   
                }else {
                     status_al ='blockdata';
                    
                }
                
                 //alert('total :'+total+' Range :'+utk+' Check ALD :'+checkald+ ' Amount Picket :'+amountpicket+' Rest of Annual Leave :'+sisa_al);
                //alert('Jenis Cuti :'+jenis+' check ald :'+checkald+' sisa :'+sisa_al+' kalkulasi : '+calculate_al+' status entry :'+status_al);
                
                if (jenis != "SL"){
                    sickletter = "0";
                }
                else{
                    sickletter = sickletter;
                }
                //alert(userid+jenis+alasan+dari+sampai+total+idpengganti);
		
                 
                if (jenis != null && alasan != "" && dari != "" && sampai != "" && ((idpengganti == "" && jenis == "CL") || (idpengganti == "" && jenis == "SL") || (idpengganti != "" && (jenis != "CL" || jenis != "SL")))){
                  
                   if(jenis=='AL' && status_al=='blockdata'){
                    alert('Your Annual Leave is not permitted because sum of rest annual leave  6 days for Annual Leave Deduction (Cuti Lebaran)');
                    
                    }else{                   
                    loading();
                    $.ajax({
                        url	:"<?php echo site_url();?>/trx01/home/"+proses,
                        data	:"concl="+concl+"&conmrl="+conmrl+"&sisa="+sisa+"&idleave="+idl+"&jenis="+jenis+"&alasan="+alasan+"&dari="+dari+"&sampai="+sampai+"&total="+total+"&idpengganti="+idpengganti+"&sickletter="+sickletter,
                        type	:"POST",
                        dataType:"json",
                        cache	:false,
                        success	: function (data){
//                            alert(data);
//                            bootbox.hideAll();
                            if (data.status == "oke"){
                                reloadtrx();                                    
                                bootbox.alert("Permohonan telah terkirim.", function (){
                                    bootbox.hideAll();
                                });
                                //bootbox.alert(data.isi);
                            }
                            else{
                                reloadtrx();
                                bootbox.alert("Permohonan cuti Anda hari ini telah terdaftar. Gunakan fasilitas 'edit' jika ada perubahan.",
                                function (){
                                    bootbox.hideAll();
                                });
                            }
                        },
                        error : function (a,b){
                            alert("Tolong laporkan error ini ke sysdev!\nSalin semua teks dalam kotak ini dan emailkan ke sysdev\n okierie@yahoo.com atau okierie@triasindrasaputra.loc \n terima kasih \n"+a.responseText+"\n"+b);
				bootbox.hideAll();
                        }
                    });
                    
                    }
                }
                
                  
                if (jenis == null){
                    $(".radio").addClass("label_error_cuti");
                    var jml = $(".blokjenis").length;
                    if (jml < 1){
                        $("#contjenis").before("<p class='error help-block blokjenis'><span class='label label-important'>Jenis cuti harus dipilih!</span></p>");                        
                    }
                }
                if (dari == ""){
                    var jml = $(".blokdari").length;
                    if (jml < 1){
                        $("#dari").before("<p class='error help-block blokdari'><span class='label label-important'>Tanggal cuti harus diisi!</span></p>");
                    }
                }
                if (sampai == ""){
                    if (dari != ""){
                        var jml = $(".blokdari").length;
//                        alert(jml);
                        if (jml < 1){
                            $("#dari").before("<p class='error help-block blokdari'><span class='label label-important'>Tanggal cuti harus diisi!</span></p>");                            
                        }
                    }
                    var jml = $(".bloksmp").length;
                    if (jml < 1){
                        $("#sampai").before("<p class='error help-block bloksmp'><span class='label label-important'>Tanggal cuti harus diisi!</span></p>");
                    }
                }
                if (alasan == ""){
                    var jml = $(".blokals").length;
                    if (jml < 1){
                        $("#alasan").before("<p class='error help-block blokals'><span class='label label-important'>Alasan cuti harus diisi!</span></p>");
                    }
                }
                if (idpengganti == "" && jenis != "CL"){
                    if (jenis != "SL"){
                        var jml = $(".blokpgt").length;
                        if (jml == 0){
                            $("#idpengganti").before("<p class='error help-block blokpgt'><span>&nbsp;</span></p>");
                            $("#nmpengganti").before("<p class='error help-block blokpgt'><span class='label label-important'>Pengganti harus diisi!</span></p>");                        
                        }                        
                    }
                }
            }
		

            function subjenis(){
                $("#sampai").val('');
            }
            function reset_formulir(){
//            alert(balasan+"|"+bconcl+"|"+bconmrl+"|"+bconmtl+"|"+bdari+"|"+bidpgt+"|"+bjenis+"|"+bnmpgt+"|"+bsampai+"|"+bsickletter+"|"+bsisa+"|"+btotal);
                if (stat == ""){
                    $(".radio").removeClass("label_error_cuti");
                    $(".help-block").remove();
                    $("#jenis").val('');
                    $("#sickletter").find("option[value='0']").prop('selected',true);
                    $("input[name=jenis]").prop('checked', false);
                    $("#alasan").val('');
                    $("#dari").val('');
                    $("#sampai").val('');
                    $("#sampai").prop('disabled', true);
                    $("#total").val('');
                    $("#sisa").val('');
                    $("#idpengganti").val('');			
                    $("#nmpengganti").val('');
                }
                if (stat != ""){
                    //alert(stat);
                    $("input[type='radio'][name='jenis'][value='"+bjenis+"']").prop('checked',true);
                    $("#sickletter").find("option[value='"+bsickletter+"']").prop("selected",true);
                    $("#concl").find("option[value='"+bconcl+"']").prop("selected",true);
                    $("#conmrl").find("option[value='"+bconmrl+"']").prop("selected",true);
                    $("#conmtl").find("option[value='"+bconmtl+"']").prop("selected",true);
                    $("#alasan").val(balasan);
                    $("#dari").val(bdari);
                    $("#sampai").val(bsampai);
                    $("#sampai").prop('disabled', true);
                    $("#total").val(btotal);
                    $("#sisa").val(bsisa);
                    $("#idpengganti").val(bidpgt);			
                    $("#nmpengganti").val(bnmpgt);
                    if (bjenis == "SL"){
                        $("#ketsl").show({});
                    }
                    if (bjenis == "CL"){
                        $("#ketcl").show({});
                    }
                    if (bjenis == "MRL"){
                        $("#ketmrl").show({});
                    }
                    if (bjenis == "MTL"){
                        $("#ketmtl").show({});
                    }
                }
                
            }
            function total_cuti(){
                var i, libur=0, tgl, nol=null;
                var utk = <?php echo $utk;?>;
                var dari = $("#dari").val().split("-");
                var sampai = $("#sampai").val().split("-");	
                var d = new Date(dari[2]+"-"+dari[1]+"-"+dari[0]);
                var s = new Date(sampai[2]+"-"+sampai[1]+"-"+sampai[0]);
                var range = (s-d)/(24*3600000);
                var hari=new Array(7);
                hari[0]="Minggu";
                hari[1]="Senin";
                hari[2]="Selasa";
                hari[3]="Rabu";
                hari[4]="Kamis";
                hari[5]="Jumat";
                hari[6]="Sabtu";
                var hlibur = <?php echo json_encode($libur);?>;
                for (i=0;i<=range;i++){
                    var da = new Date(dari[2]+"-"+dari[1]+"-"+dari[0]);
                    da.setDate(d.getDate()+i);
                    var nol = '';
                    if (da.getDate() <= 9){ var nol = "0";}
                    var nol2 = '';
                    if (da.getMonth()+1 <= 9){ var nol2 = "0";}
                    var datgl = da.getFullYear()+"-"+nol2+(da.getMonth()+1)+"-"+nol+da.getDate();
                    //alert(datgl);
                    if((hari[da.getDay()] == "Minggu") || (hlibur.indexOf(datgl) != -1)){
                        libur = libur+1;
                    //	alert(libur+"ada libur tanggal = "+da);
                    }
                    //  alert(da+i);
                }
                //alert("libur "+libur+" hari");
                // total ini harus diproses lebih lanjut
                var tot = (s-d)/(24*3600000)+1-libur;
                $("#total").val(tot);
                if ($("input[type='radio'][name='jenis']:checked").val() == "AL"){
                    $("#sisa").val(utk-tot);
                }
                else {
                    $("#sisa").val(utk);
                }			
            }	
            // ----------- datepicker -----------

            $("#sampai").datepicker( {changeMonth: true,
                     changeYear : true,
                     beforeShow : aturRange,
//                     maxDate    : "+10D",
                     dateFormat :"dd-mm-yy"},"showAnim","clip");
            function aturRange(){
                cth = <?php echo date('Y'); ?>;
                cmo = <?php echo date('m'); ?>;
                cda = <?php echo date('d'); ?>;
                var a   = $("#dari").val();
                var tg  = a.split("-");
                var th  = tg[2];
                var mo  = tg[1];
                var da  = tg[0];
                if (th == cth){
                        var minth = "-0Y";
                }
                if (th != cth){
                        var minth = th-cth+"Y";
                }
                if (mo == cmo){
                        var minmo = "-0M";
                }
                if (mo != cmo){
                        var minmo = (mo-cmo)+"M";
                }
                if (da == cda){
                        var minda = "+7D";
                }
                if (da != cda){
                        var minda = da-cda+"D";
                }
                var minimal = minth+minmo+minda;
                
                
                var checked = $("input[type='radio'][name='jenis']:checked").val();
                var ma=365;
                if (checked == "CL"){
                    if ($("#concl").val() == 'F'){
                        ma = 1;
                    }
                    else if ($("#concl").val() == 'O'){
                        ma = 0;
                    }
                }
                else if (checked == "MRL"){
                    if ($("#conmrl").val() == 'SM'){
                        ma = 2;
                    }
                    else if ($("#conmrl").val() == 'CM'){
                        ma = 1;
                    }
                }
                else if (checked == "CIR"){
                    ma  = 1;
                }
                else if (checked  == "MTL"){
                    if ($("#conmtl").val() == "SMtl"){
                        ma  = 90;
                    }
                    else if ($("#conmtl").val() == 'OMtl'){
                        ma  = 1;
                    }
                }
                var maxdate = (th-cth)+"Y"+(mo-cmo)+"M"+(da-cda+ma)+"D";
//                alert("min="+minimal+"|max="+maxdate+"|"+"ma="+ma);
                return{
                        maxDate : maxdate,
                        minDate : minimal
                };

            }

		// ----------- end of datepicker -----------
            function showDialog(){
                $("#dialog-modal").dialog({
                    width: 600,
                    height: 400,
                    open: function(event, ui){
                        var textarea = $('<textarea style="height: 276px;">');
                        $(textarea).redactor({
                            focus: true,
                            autoresize: false,
                            initCallback: function(){
                                this.set('<p>Lorem...</p>');
                            }
                        });
                    }
                });
            }
            function backtohome(){
                window.location.href = "<?php echo $base_url;?>";
            }
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

	  $(function()
                {
                        /* DataTables */
                        if ($('.LeaveTable').size() > 0)
                        {
                                $('.LeaveTable').dataTable({
                                        "sPaginationType": "bootstrap",
                                        "bDestroy": true,
                                        "aaSorting": [[1, "desc"]],
                                        "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
                                        "oLanguage": {
                                                "sLengthMenu": "_MENU_ records per page"
                                        }
                                });
                        }
                });	
        </script>
    </head>
    <body>
<div id="listtrx" class="widget">
	<div class="widget-head">
            <div class="row-fluid">
                <div class="span6">
                    <h3 class="heading"> YOUR LEAVE LIST / DAFTAR CUTI ANDA</h3>                    
                </div>
                <div class="span6" style="text-align: right;">
                    <button onclick="reloadtrx()" class="btn btn-small btn-default btn-icon"><i class="icon-refresh"></i></button>
                    <button idbtn='btmain' onclick="backtohome()" class="btn btn-small btn-success btn-icon glyphicons home"><i></i>Back to Home</button>                    
                </div>
            </div>
	</div>

	<div class="widget-body">
		<p align="right">
			<button onclick="add()" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> Request Leave</button>
		</p>

		<table class="LeaveTable  table table-striped table-bordered table-condensed">
			
                    <!-- Table heading -->
                    <thead class="btn-primary">
                        <tr>
                            <th rowspan="2" ><center >No</center></th>
                            <th rowspan="2" ><center >Request Date</center></th>
                            <th rowspan="2" ><center >Type</center></th>
                            <th colspan="3" ><center >Status</center></th>
                            <th rowspan="2" ><center >Action</center></th>
                        </tr>
                        <tr>
                            <th><center>PiC</center></th>
                            <th><center>HoD</center></th>
                            <th><center>HRD</center></th>
                        </tr>
                    </thead>
                    <!-- // Table heading END -->
				
                    <!-- Table body -->
                    <tbody>
                    <?php 
                    $no =0;
                    foreach($trx->result() as $tr){
                        $popover = " data-toggle='popover' data-title='reason of rejection' data-content='$tr->RejectReason' data-placement='left' idpop='$tr->IDLeave' ";
                        $tooltip = " data-toggle='tooltip' data-original-title='click to view the reason' data-placement='top' idtip='$tr->IDLeave' ";
                        $no++;
                        if($tr->FPgt =='true')      { $stat1 = "<a class='cnfmd'>Accepted</a>";}
                        if($tr->FPgt =='false')     { $stat1 = "<a class='waitingg'>Waiting</a>";}
                        if($tr->FPgt =='rejected')  { $stat1 = "<span $popover ><a $tooltip class='rejectedd'>Rejected</a></span>";}
                        if($tr->FAts =='true')      { $stat2 = "<a class='cnfmd'>Accepted</a>";}
                        if($tr->FAts =='false')     { $stat2 = "<a class='waitingg'>Waiting</a>";}
                        if($tr->FAts =='rejected')  { $stat2 = "<span $popover ><a $tooltip class='rejectedd'>Rejected</a></span>";}
                        if($tr->FHrd =='true')      { $stat3 = "<a class='cnfmd'>Accepted</a>";}
                        if($tr->FHrd =='false')     { $stat3 = "<a class='waitingg'>Waiting</a>";}
                        if($tr->FHrd =='rejected')  { $stat3 = "<span $popover ><a $tooltip class='rejectedd'>Rejected</a></span>";}
                        echo "<tr  class='".$tr->IDLeave." selectable'>";
                        echo "<td><center>$no</center></td>";
                        echo "<td><center>".date('Y-m-d',strtotime($tr->TglPengajuan))."</center></td>";
                        echo "<td><center>".$tr->Jenis."</center></td>";
                        echo "<td><center>".$stat1.($tr->FPgt_tgl != NULL ? "<br><i>".date('Y-m-d',strtotime($tr->FPgt_tgl))."</i></center></td>": "</center></td>");
                        echo "<td><center>".$stat2.($tr->FAts_tgl != NULL ? "<br><i>".date('Y-m-d',strtotime($tr->FAts_tgl))."</i></center></td>": "</center></td>");
                        echo "<td><center>".$stat3.($tr->FHrd_tgl != NULL ? "<br><i>".date('Y-m-d',strtotime($tr->FHrd_tgl))."</i></center></td>": "</center></td>");
                        if ($tr->FPgt == 'true' and $tr->FAts == 'true' and $tr->FHrd == 'true'){
                            $disabled = "disabled";
                        }
                        else {
                            $disabled = "";
                        }
                        echo "<td>".
                        "<center>
			 <button  type='button' id='btn_print' class='btn btn-mini btn-primary' onclick='printdata(\"" . $tr->IDLeave . "\")' ><i class='icon-print'></i></button>
                        <button $disabled type='button' id='btn_edit' class='btn btn-mini btn-warning' onclick='edit(\"".$tr->IDLeave."\")' ><i class='icon-pencil'></i></button>
                        <button $disabled type='button' id='btn_delete' class='btn btn-mini btn-danger' onclick='del_trx(\"".$tr->IDLeave."\")'><i class='icon-trash'></i></button>
                        </center>
                        "."</td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                    <!-- // Table body END -->
				
		</table>
	</div>
</div>
<div class="form-horizontal" style="margin-bottom: 0;" id="validateSubmitForm" >

<div id="formtrx">
	<input type="hidden" id="stype">
<div class="widget">	
		<!-- Widget heading -->
		<div class="widget-head">
                    <div class="row-fluid">
                        <div class="span6">
                            <h3 class="heading">APPLICATION FOR LEAVE / FORMULIR PERMOHONAN CUTI</h3>
                        </div>
                        <div class="span6" style="text-align: right;">
                            <button type="button" class="btn btn-icon btn-small btn-danger glyphicons circle_remove" onclick="cancelact()"><i></i>Cancel</button>
                        </div>                        
                    </div>	
			
		</div>
		<!-- // Widget heading END -->
<!--
body
-->

		<div class="widget-body">
<!--
			row1
-->
			<div class='row-fluid'>
<!--
				kol1
-->
				<div class='span6'>
					<div class="control-group">
						<label class="control-label" for="nama">Name <br> <i>Nama</i></label>
						<div class="controls"><input class="span10" id="nama" name="nama" type="text" value="<?php echo $nama;?>" readonly /></div>
					</div>
					<div class="control-group">
						<label class="control-label" for="jabatan">Position <br> <i>Jabatan</i></label>
						<div class="controls"><input class="span10" id="jabatan" name="jabatan" type="text" value="<?php echo $jabatan;?>" readonly /></div>
					</div>
					<div class="control-group">
						<label class="control-label" for="bagian">Department <br> <i>Bagian</i></label>
						<div class="controls"><input class="span10" id="bagian" name="bagian" type="text" value="<?php echo $bagian;?>" readonly /></div>
					</div>
				</div>
<!--
				end of kol1
-->				
<!--
				kol2
-->
				<div class='span6'>
					<div class="control-group">
						<label class="control-label" for="userid">Staff ID No.<br><i>No ID Karyawan</i></label>
						<div class="controls"><input class="span10" id="userid" name="userid" type="text" value="<?php echo $userid;?>" readonly /></div>
					</div>
					<div class="control-group">
						<label class="control-label" for="tglmasuk">Commenced Date <br> <i>Tgl Masuk</i></label>
						<div class="controls"><input class="span10" id="tglmasuk" name="tglmasuk" type="text" value="<?php echo $tglmasuk;?>" readonly /></div>
					</div>
					<div class="control-group">
						<label class="control-label" for="utk">Un-taken Leave <br> <i>Cuti yg Belum Diambil</i></label>
						<div class="controls"><input class="span10" id="utk" name="utk" type="text" value="<?php echo $utk;?>" readonly /></div>
					</div>
				</div>
			</div>
<!--
			end of kol2
-->
<!--
			end of row1
-->
			<hr class='separator'>
<!--
			row2
-->
			<div id="contjenis" class='row-fluid' style='margin-left: 5%;'><!-- tambahan margin-left 5% -->
				<h4 style="margin-bottom: 10px; margin-left: -5%;">Type of Leave Request / <i>Jenis Permohonan Cuti</i></h4>
<!--
				kol1
-->
				<div class='span4'>
			
                                        <div class='control-group'>
                                            <label class="radio">
                                                <input type="radio" class="radio"  name="jenis" value='AL' <?php if ($utk == 0){ echo "disabled";} ?> />
                                                Annual Leave <br> <i>Cuti Tahunan</i>
                                            </label>
                                        </div>
                                        <div class='control-group'>
                                            <label class="radio">
                                                <input type="radio" class="radio"  name="jenis" value='CL'/>
                                                Condolence Leave <br> <i>Cuti Duka Cita</i>
                                            </label>
                                            <div id="ketcl" style="display: none;">
                                                Concern of Subject: 
                                                <select class="span6" onchange="subjenis()" id="concl">
                                                    <option value="F">Spouse/Child/Parent</option>
                                                    <option value="O">Other family member</option>
                                                </select>                                                        
                                            </div>
                                        </div>
                                        <div class='control-group'>
                                            <label class="radio">
                                                <input type="radio" class="radio"  name="jenis" value='CIR'/>
                                                Circumcision Leave <br> <i>Cuti Khitanan</i>
                                            </label>
                                        </div>
									
				</div>
<!--
				end of kol1
-->
<!--
				kol2
-->
				<div class='span4'>
				
                                        <div class='control-group'>
                                            <label class="radio">
                                                <input type="radio" class="radio"  name="jenis" value='MTL'/>
                                                Maternity Leave <br> <i>Cuti Melahirkan</i>
                                            </label>
                                            <div id="ketmtl" style="display: none;">
                                                Concern of Subject : 
                                                <select class="span3" onchange="subjenis()" id="conmtl">
                                                    <option value="SMtl">SELF</option>
                                                    <option value="OMtl">WIFE</option>
                                                </select>                                                        
                                            </div>                                            
                                        </div>
                                        <div class='control-group'>
                                            <label class="radio">
                                                <input type="radio" class="radio"  name="jenis" value='SL'/>
                                                Sick Leave <br> <i>Cuti Sakit</i>
                                            </label>
                                            <div id="ketsl" style="display: none;">
                                                Sickness Letter : 
                                                <select class="span2" onchange="subjenis()" id="sickletter">
                                                    <option value="0">NO</option>
                                                    <option value="1">YES</option>
                                                </select>                                                        
                                            </div>

                                        </div>
                                        <div class='control-group'>
                                            <label class="radio">
                                                <input type="radio" class="radio"  name="jenis" value='MRL'/>
                                                Marriage Leave <br> <i>Cuti Pernikahan</i>
                                            </label>
                                            <div id="ketmrl" style="display: none;">
                                                Concern of Subject : 
                                                <select class="span6" onchange="subjenis()" id="conmrl">
                                                    <option value="SM">Self Marriage</option>
                                                    <option value="CM">Child's Marriage</option>
                                                </select>                                                        
                                            </div>
                                        </div>
									
				</div>
<!--
				end of kol2
-->
<!--
				kol3
-->
				<div class='span3'>
						<div class='control-group'>
							<label class="radio">
								<input type="radio" class="radio"  name="jenis" value='OL'/>
								Unpaid Leave <br> <i>Cuti Tidak Dibayarkan</i>
							</label>
						</div>									
				</div>
<!--
				end of kol3
-->
			</div>
<!--
			end of row2
-->
<!--
			row3
-->
			<hr class='separator'>
			<div class='row-fluid' >
				<h4 style='margin-bottom: 10px;'>Leave Request / <i>Permohonan Cuti</i></h4>
				<div class='control-group span4 input-append'>
					<label class='control-label' for='dari'> From <br><i>Dari</i> </label>
					<div class='controls '>
                                            <input type='text' id='dari' name='dari' class='span10' placeholder="Klik untuk input">
                                            <span class="add-on glyphicons delete" id='deletedari' style='cursor: pointer;' ><i></i></span>
					</div>
				</div>
				<div class='control-group span4 input-append'>
					<label class='control-label' for='sampai'> Until <br><i>Sampai</i> </label>
					<div class='controls '>
						<input type='text' id='sampai' name='sampai' class='span10' placeholder="Klik untuk input" onchange="total_cuti()" >
						<span class="add-on glyphicons delete" id='deletesampai' style='cursor: pointer;' ><i></i></span>
					</div>
				</div>
				<div class='control-group span4'>
					<label class='control-label' for='total'> Total <br><i>Total</i> </label>
					<div class='controls'> <input type='text' id='total' name='total' class='span4' readonly> Days / <i>Hari</i></div>
					
				</div>
			</div>
<!--
			end of row3
-->
<!--
			row4
-->
			<div class='row-fluid' style='margin-bottom: 10px;'>
				<div class='span5' align='right'>
					Outstanding Leave After Deducated With Leave Request <br> <i>Sisa Cuti Setelah Dikurangi Permohonan Cuti</i>
				</div>
				<div class='span3'>
					<input id='sisa' class='span3' name='sisa' type='text' readonly> Days / <i>Hari</i>
				</div>
			</div>
<!--
			end of row4
-->
<!--
			row5
-->
			<div class='row-fluid' style='margin-bottom: 10px;'>
				<div class='span5' align='right'>
					Reason of Leave <br> <i>Alasan Cuti</i>
				</div>
				<div class='span7'>
					<textarea id='alasan' name='alasan' style='resize:none; width: 80%;' rows="1" placeholder="Ketik alasan cuti"></textarea>
				</div>
			</div>
<!--
			end of row5
-->
<!--
			row6
-->
			<div class='row-fluid' style='margin-bottom: 10px;'>
				<div class='span5' align='right'>
					Person in Charge During Leave <br> <i>Pengganti Selama Cuti</i>
				</div>
				<div class='span2'>
                                    <input type='text' id='idpengganti' name='idpengganti' class='span12' readonly>
				</div>
                                <div class="span4 input-append">
                                    <input type='text' id='nmpengganti' name='nmpengganti' class='span10' placeholder="Ketik nama pengganti selama cuti">
                                    <span onclick="del_pgt()" id="btndelpgt" class="add-on glyphicons delete" style='cursor: pointer;' ><i></i></span>
                                </div>
			</div>
<!--
			end of row6
-->
			<hr class='separator'>
			<div class="form-actions" align='center'>
					<button type="submit" class="btn btn-icon btn-success glyphicons circle_ok" onclick="simpan()" ><i></i>Save</button>
					<button type="button" class="btn btn-icon btn-default glyphicons refresh" onclick="reset_formulir()"><i></i>Reset</button>
					<button type="button" class="btn btn-icon btn-danger glyphicons circle_remove" onclick="cancelact()"><i></i>Cancel</button>
			</div>
<!--
		end of body
-->
	</div>
</div>

</div>

    </body>
</html>
<div id="leave-form" 
     title="PRINT LEAVE" 
     >
</div>


