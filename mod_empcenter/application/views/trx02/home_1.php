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
            #jmlanak{
                text-align: right;
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
        <script>
            $(document).ready(function() {
                var idmar = $("#idmarital").val();
                if (idmar == "1"){
                    $(".marital").hide();
                    $(".anak").hide();
                }
                $("#idmarital").change(function(){
                    var val = $("#idmarital").val();          
                    //alert(val);
                    if (val=="1"){
                       $(".marital").hide(); 
                       $(".anak").hide();
                       $("#ncoup").val("-");
                       $("#idcoup").val("-");
                       $("#mcrt").val("-");
                       $("#fcrt").val("-");
                    }
                    else{
                        $(".marital").show();
                    }                    
                });
                $("#anak").change(function(){
                    var val = $("#anak").val();
                    if(val == "Y"){
                        $(".anak").show();
                    }
                    else{
                        $(".anak").hide();
                        $("#jmlanak").val("0");
                        $("#nanak1").val("0");
                        $("#nanak2").val("0");
                    }
                });

                $("#birthdate").datepicker({
                 changeMonth: true,
                 changeYear : true,
                 minDate    : "-100Y",
                 maxDate    : "-14Y",
                 dateFormat :"dd-mm-yy"},"showAnim","clip");                
                
                var data_major = <?php echo $majors?>;
                $(function() {
                    $( "#descmajor" ).autocomplete({
                         source: data_major,
                         select: function (event, ui){
                              $("#idmajor").val(ui.item.MajCode);
                         }
                     });
                });
                var data_unit = <?php echo $unitjob?>;
                $(function() {
                    $( "#nmunit" ).autocomplete({
                         source: data_unit,
                         select: function (event, ui){
                              $("#idunit").val(ui.item.UnitCode);
                         }
                     });
                });
                var data_parents = <?php echo $parents?>;
                $(function() {
                    $( "#nmparent" ).autocomplete({
                         source: data_parents,
                         select: function (event, ui){
                              $("#idparent").val(ui.item.IDEmployee);
                         }
                     });
                });
            });
            function save(){
               var account      = $("#account").val();
               var addktp       = $("#addktp").val();
               var addlive      = $("#addlive").val();
               var birthdate    = $("#birthdate").val();
               var birthplace   = $("#birthplace").val();
               var bloodtype    = $("#bloodtype").val();
               var dprt         = $("#dprt").val();
               var e_eks        = $("#e_eks").val();
               var e_int        = $("#e_int").val();
               var fcert        = $("#fcert").val();
               var fullname     = $("#fullname").val().toUpperCase();
               var gender       = $("#gender").val();
               var group        = $("#group").val();
               var idcoup       = $("#idcoup").val();
               var ideducation  = $("#ideducation").val();
               var idloc        = $("#idloc").val();
               var idmajor      = $("#idmajor").val();
               var idmarital    = $("#idmarital").val();
               var idparent     = $("#idparent").val();
               var idpos        = $("#idpos").val();
               var idreligion   = $("#idreligion").val();
               var idunit       = $("#idunit").val();
               var jmlanak      = $("#jmlanak").val();
               var mcert        = $("#mcert").val();
               var nanak1       = $("#nanak1").val();
               var nanak2       = $("#nanak2").val();
               var ncoup        = $("#ncoup").val();
               var nickname     = $("#nickname").val().toUpperCase(); ;
               var noext        = $("#noext").val();
               var nohp         = $("#nohp").val();
               var nokpj        = $("#nokpj").val();
               var noktp        = $("#noktp").val();
               var nonpwp       = $("#nonpwp").val();
               var notlp        = $("#notlp").val();
               var workexp      = $("#workexp").val();
               $.ajax({
                   url      : "<?php echo site_url(); ?>/trx02/home/update",
                   type     : "POST",
                   dataType : "json",
                   cache    : false,
                   data     : "account="+account+"&addktp="+addktp+"&addlive="+addlive+"&birthdate="+birthdate+"&birthplace="+birthplace+"&bloodtype="+bloodtype+"&dprt="+dprt+"&e_eks="+e_eks+"&e_int="+e_int+"&fcert="+fcert+"&fullname="+fullname+"&gender="+gender+"&group="+group+"&idcoup="+idcoup+"&ideducation="+ideducation+"&idloc="+idloc+"&idmajor="+idmajor+"&idmarital="+idmarital+"&idparent="+idparent+"&idpos="+idpos+"&idreligion="+idreligion+"&idunit="+idunit+"&jmlanak="+jmlanak+"&mcert="+mcert+"&nanak1="+nanak1+"&nanak2="+nanak2+"&ncoup="+ncoup+"&nickname="+nickname+"&noext="+noext+"&nohp="+nohp+"&nokpj="+nokpj+"&noktp="+noktp+"&nonpwp="+nonpwp+"&notlp="+notlp+"&workexp="+workexp,
                   success  : function (data){
//                       alert(data);
                       if (data.status == "oke"){
                           reloadtrx();
                           bootbox.alert("Data berhasil diperbarui");
                        }
                   }
               });
            }
            function reloadtrx(){
                var content = $("#content .innerLR");
                var url = ROOT.base_url + 'mod_empcenter/index.php/trx02/home';
                //alert(url);
                content.fadeOut("slow", "linear");
                content.load(url);
                content.fadeIn("slow");
            }

        </script>
    </head>
    <body>
        <div class="widget">
            <div class="widget-head"><h4 class="heading">DATA DIRI</h4></div>
            <div class="widget-body form-horizontal">
                    <!--identitas-->
                <div class='row-fluid' >
                    <h4 >Identitas</h4>
                    <!--row1-->
                    <div class='span5'>
                        <div class='control-group '>
                            <label class="control-label" for='noktp'>No KTP</label>
                            <div class="controls">
                                <input type="text" id= 'noktp' name="noktp" class='span12' value="<?php echo $det->NoKTP;?>" >
                            </div>
                        </div>
                    </div>
                    <div class='span5'>
                        <div class='control-group'>
                            <label class="control-label" for='nokpj'>No KPJ</label>
                            <div class="controls">
                                <input type="text" id='nokpj' name="nokpj" class='span12' value="<?php echo $det->NoKPJ;?>" >
                            </div>
                        </div>
                    </div>
                    <!--end of row1-->
                    <!--row2-->
                    <div class='span12' style="margin-top: 2%;">
                        <div class='control-group'>
                            <label class="control-label" for='nonpwp'>No NPWP</label>
                            <div class="controls">
                                <input type="text" id='nonpwp' name="nonpwp" class='span3' value="<?php echo $det->NoNPWP;?>" >
                            </div>
                        </div>
                    </div>
                    <!--end of row2-->
                    <!--row3-->
                    <div class='span5'style="margin-top: 2%;">
                        <div class='control-group'>
                            <label class="control-label" for='fullname'>Nama Lengkap</label>
                            <div class="controls">
                                <input type="text" id='fullname' name="fullname" class='span12 upper' value="<?php echo $det->FullName; ?>">
                            </div>
                        </div>
                    </div>        
                    <div class='span5'style="margin-top: 2%;">
                        <div class='control-group'>
                            <label class="control-label" for='nickname'>Nama Panggilan</label>
                            <div class="controls">
                                <input type="text" id="nickname" name="nickname" class='span12 upper' value="<?php echo $det->NickName; ?>" >
                            </div>
                        </div>
                    </div>
                    <!--end of row3-->
                    <!--row4-->
                    <div class='span5'style="margin-top: 2%;">
                        <div class='control-group'>
                            <label class="control-label" for='birthplace'>Tempat Lahir</label>
                            <div class="controls">
                                <input type="text" id="birthplace" name="birthplace" class='span12' value="<?php echo $det->BirthPlace; ?>" >
                            </div>
                        </div>
                    </div>        
                    <div class='span5'style="margin-top: 2%;">
                        <div class='control-group'>
                            <label class="control-label" for='birthdate'>Tanggal Lahir</label>
                            <div class="controls">
                                <input type="text" id="birthdate" name="birthdate" class='span12' value="<?php echo date('d-m-Y',  strtotime($det->BirthDate));?>" >
                            </div>
                        </div>
                    </div>
                    <!--end of row4-->
                    <!--row5-->
                    <div class='span5'style="margin-top: 2%;">
                        <div class='control-group'>
                            <label class="control-label" for='gender'>Gender</label>
                            <div class="controls">
                                <select class='selectpicker span12 upper' name="gender" id="gender">
                                    <option value='0' <?php echo ($det->Gender == NULL)?"selected " : ""?> ></option>
                                    <option value='L' <?php echo ($det->Gender == "L")?"selected " : ""?>>Laki - Laki</option>
                                    <option value='P' <?php echo ($det->Gender == "P")?"selected " : ""?>>Perempuan</option>
                                </select>
                            </div>
                        </div>
                    </div>        
                    <div class='span5'style="margin-top: 2%;">
                        <div class='control-group'>
                            <label class="control-label" for='bloodtype'>Golongan Darah</label>
                            <div class="controls">
                                <input type="text" id="bloodtype" name="bloodtype" class='span2' value="<?php echo $det->BloodType; ?>" >
                            </div>
                        </div>
                    </div>
                    <!--end of row5-->
                    <!--row6-->
                    <div class='span5'style="margin-top: 2%;">
                        <div class='control-group'>
                            <label class="control-label" for='idreligion'>Agama</label>
                            <div class="controls">
                                <select class='selectpicker span12 upper' id="idreligion" nama="idreligion">
                                    <?php
                                    foreach($religion->result() as $reli){
                                        if (($det->IDReligion == $reli->RelCode) or ($det->IDReligion == $reli->RelDesc)){
                                            $selected = "selected";
                                        }
                                        else {
                                            $selected = "";
                                        }
                                        echo "<option value='$reli->RelCode' $selected >";
                                        echo $reli->RelDesc;
                                        echo "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--end of row6-->
                </div>
                <!--end of identitas-->
                <!--education status-->
                <hr class="separator">
                <div class="row-fluid">
                    <h4 style="margin-bottom: 10px;">Status Pendidikan</h4>
                    <!--row1-->
                    <div class='span5'>
                        <div class='control-group'>
                            <label class="control-label" for='ideducation'>Tingkat Pendidikan</label>
                            <div class="controls">
                                <select class='selectpicker span12 upper' name='ideducation' id="ideducation">
                                    <?php 
                                    foreach($edulevel->result() as $edul){
                                        if (($det->IDEducation == $edul->EduCode) or ($det->IDEducation == $edul->EduDesc)){
                                            $selected = "selected";
                                        }
                                        else{
                                            $selected = "";
                                        }
                                        echo "<option value='$edul->EduCode' $selected >";
                                        echo $edul->EduDesc;
                                        echo "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div> 
                    <div class="span5">
                        <div class='control-group'>
                            <label class="control-label" for='descmajor'>Bidang</label>
                            <div class="controls">
                                <input type="hidden" id="idmajor" value="<?php echo $det->IDMajors;?>" >
                                <input type="text"  id="descmajor" name='descmajor' class="span12" value="<?php 
                                $c_maj = $this->pbl->get_majors($det->IDMajors)->row();
                                echo ($c_maj == NULL) ? $det->IDMajors : $c_maj->MajDesc;?>" >
                            </div>
                        </div>                        
                    </div>
                    <!--end of row1-->
                </div>
                <!--end of education status-->
                <!--marital status-->
                <hr class="separator">
                <div class="row-fluid">
                    <h4 style="margin-bottom: 10px;">Status Pernikahan</h4>
                    <!--row1-->
                    <div class="span12" style="margin-bottom : 2%;">
                        <div class='control-group'>
                            <label class="control-label" for='idmarital'>Hubungan</label>
                            <div class="controls">
                                <select class='selectpicker span2 upper' id="idmarital" name='idmarital'>
                                <?php 
                                foreach($relation->result() as $rela){
                                    if (($det->IDMarital == $rela->IDStatus) or ($det->IDMarital == $rela->StatusName)){
                                        $selected = "selected";
                                    }
                                    else{
                                        $selected = "";
                                    }
                                    echo "<option value='$rela->IDStatus' $selected >";
                                    echo $rela->StatusName;
                                    echo "</option>";
                                }
                                ?>
                                </select>
                            </div>
                        </div>                        
                    </div>
                    <!--end of row1-->
                    <!--row2-->
                    <div class="span12 marital" style="margin-bottom: 2%;">
                        <div class="control-group">
                            <label class="control-label" for='ncoup'>Nama Pasangan</label>
                            <div class="controls">
                                <input type="text" class="span3" name='ncoup' id="ncoup" value="<?php echo isset($det->CoupleName)?$det->CoupleName:"";?>">
                            </div>                                
                        </div>
                    </div>
                    <!--end of row2-->
                    <!--row3-->
                    <div class="span3 marital">
                        <div class='control-group'>
                            <label class="control-label" for='mcert'>Surat Nikah</label>
                            <div class="controls">
                                <select class="selectpicker span12 upper" name="mcert" id="mcert">
                                    <option value="Y" <?php echo ($det->MarriageCertificate == "Y")?"selected":"";?> >Ada</option>
                                    <option value="N" <?php echo ($det->MarriageCertificate == "N")?"selected":"";?> >Tidak Ada</option>
                                </select>
                            </div>
                        </div>                        
                    </div>
                    <div class="span3 marital" >
                        <div class='control-group'>
                            <label class="control-label" for='fcert'>Kartu Keluarga</label>
                            <div class="controls">
                                <select class="selectpicker span12 upper" name="fcert" id="fcert">
                                    <option value="Y" <?php echo ($det->FamilyMemberCertificate == "Y")?"selected":"";?> >Ada</option>
                                    <option value="N" <?php echo ($det->FamilyMemberCertificate == "N")?"selected":"";?> >Tidak Ada</option>
                                </select>
                            </div>
                        </div>                        
                    </div>
                    <div class="span3 marital">
                        <div class='control-group'>
                            <label class="control-label" for='idcoup'>KTP Pasangan</label>
                            <div class="controls">
                                <select class="selectpicker span12 upper" name="idcoup" id="idcoup">
                                    <option value="Y" <?php echo ($det->IDMarriedCouple == "Y")?"selected":"";?> >Ada</option>
                                    <option value="N" <?php echo ($det->IDMarriedCouple == "N")?"selected":"";?> >Tidak Ada</option>
                                </select>
                            </div>
                        </div>                        
                    </div>
                    <!--end of row3-->
                    <!--row4-->
                    <div class="span3 marital" style="margin-top: 2%; margin-bottom: 2%;">
                        <div class="control-group">
                            <label class="control-label" for='anak'>Mempunyai Anak</label>
                            <div class="controls">
                                <select class="selectpicker span12 upper" name='anak' id="anak" >
                                    <option value="Y" <?php echo (isset($det->NumberChildren) or $det->NumberChildren != "0")?"selected":"";?> >Iya</option>
                                    <option value="N" <?php echo (!isset($det->NumberChildren) or $det->NumberChildren == "0")?"selected":"";?> >Tidak</option>
                                </select>
                            </div>                                
                        </div>
                    </div>
                    <div class="span7 anak" style="margin-top: 2%; margin-bottom: 2%;">
                        <div class="control-group">
                            <label class="control-label" for='jmlanak'>Jumlah Anak</label>
                            <div class="controls">
                                <input type="text" class="span2" name='jmlanak' id="jmlanak" value="<?php echo (isset($det->NumberChildren))?$det->NumberChildren:"";?>">
                            </div>                                
                        </div>
                    </div>
                    <!--end of row4-->
                    <!--row5-->
                    <div class="span5 anak" >
                        <div class="control-group">
                            <label class="control-label" for='nanak1'>Nama Anak Pertama</label>
                            <div class="controls">
                                <input type="text" class="span12" id="nanak1" name='nanak1' value="<?php echo isset($det->FirstChild)?$det->FirstChild:"";?>">
                            </div>                                
                        </div>
                    </div>                    
                    <div class="span5 anak" >
                        <div class="control-group">
                            <label class="control-label" for='nanak2'>Nama Anak Kedua</label>
                            <div class="controls">
                                <input type="text" class="span12" id="nanak2" name='nanak2' value="<?php echo isset($det->SecondChild)?$det->SecondChild:"";?>">
                            </div>                                
                        </div>
                    </div>
                    <!--end of row5-->
                </div>
                <!--end of marital status-->
                <!--kontak-->
                <hr class="separator">
                <div class="row-fluid">
                    <h4 style="margin-bottom: 10px;">Kontak</h4>
                    
                    <div class="span5">
                        <div class='control-group'>
                            <label class="control-label" for='notlp'>Nomor Telepon</label>
                            <div class="controls">
                                <input type="text" id="notlp" name="notlp" class='span12' value="<?php echo $det->NoTelp; ?>">
                            </div>
                        </div>                        
                    </div>
                    <div class="span5">
                        <div class='control-group'>
                            <label class="control-label" for='nohp'>Nomor Handphone</label>
                            <div class="controls">
                                <input type="text" id="nohp" name="nohp" class='span12' value="<?php echo $det->NoHp; ?>">
                            </div>
                        </div>                        
                    </div>
                    <div class="span12" style="margin-top: 2%;">
                        <div class='control-group'>
                            <label class="control-label" for='noext'>No Extension</label>
                            <div class="controls">
                                <input type="text" id="noext" name="noext" class='span1' value="">
                            </div>
                        </div>                        
                    </div>
                    <div class="span5" style="margin-top: 2%;">
                        <div class='control-group' >
                            <label class="control-label" for='e_int'>Email Internal</label>
                            <div class="controls">
                                <input type="text" id="e_int" name="e_int" class='span12' value="<?php echo $det->EmailInternal;?>">
                            </div>
                        </div>                        
                    </div>
                    <div class="span5" style="margin-top: 2%;">
                        <div class='control-group' >
                            <label class="control-label" for='e_eks'>Email Eksternal</label>
                            <div class="controls">
                                <input type="text" id="e_eks" name="e_eks" class='span12' value="<?php echo $det->EmailExternal;?>">
                            </div>
                        </div>                        
                    </div>
                </div>
                <!--end of kontak-->
                <!--alamat-->
                <hr class="separator">
                <div class="row-fluid">
                    <h4 style="margin-bottom: 10px;">Alamat</h4>
                    <!--row1-->
                    <div class="span12" >
                        <div class="control-group">
                            <label class="control-label" for="addlive">Alamat Tinggal</label>
                            <div class="controls">
                                <textarea name="addlive" id="addlive" class="span6" style="resize:none"><?php echo $det->LiveAddress;?></textarea>
                            </div>
                        </div>
                    </div>
                    <!--end of row1-->
                    <!--row2-->
                    <div class="span12" style="margin-top: 2%">
                        <div class="control-group">
                            <label class="control-label" for="addktp">Alamat KTP</label>
                            <div class="controls">
                                <textarea name="addktp" id="addktp" class="span6" style="resize:none"><?php echo $det->KTPAddress;?></textarea>
                            </div>
                        </div>
                    </div>
                    <!--end of row2-->
                </div>
                <!--end of alamat-->
                <hr class="separator">
                <!--keterangan karyawan-->
                <div class="row-fluid">
                    <h4 style="margin-bottom: 10px;">Keterangan Lain</h4>
                    <div class="span12" style="margin-bottom:2%;">
                        <div class="control-group">
                            <label class="control-label" for="account">No Rekening Bank</label>
                            <div class="controls">
                                <input type="text" class="span2" id="account" name='account' value="<?php echo $det->BankAccount; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="span12" style="margin-bottom:2%;">
                        <div class="control-group">
                            <label class="control-label" for="workexp">Pengalaman Bekerja</label>
                            <div class="controls">
                                <textarea class="span6" style="resize:none;" id="workexp" name='workexp' ><?php echo $det->WorkExperience; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <!--row1-->
                    <div class="span4" style="margin-bottom:2%;">
                        <div class="control-group">
                            <label class="control-label" for="nip">Nomor Induk</label>
                            <div class="controls">
                                <input type="text" class="span12" name="nip" id="nip" value="<?php echo $userid;?>" readonly >
                            </div>
                        </div>
                    </div>
                    <div class="span5" style="margin-bottom:2%;">
                        <div class="control-group">
                            <label class="control-label">Nama Parent</label>
                            <div class="controls">
                                <input type="hidden" class="span12" name="idparent" id="idparent" value="<?php echo (isset($det->IDEmployeeParent))?$det->IDEmployeeParent:"";?>" >
                                <input type="text" class="span12 upper" name="nmparent" id="nmparent" value="<?php echo (isset($det->IDEmployeeParent))?$this->pbl->get_employee($det->IDEmployeeParent)->row()->FullName:"";?>" >
                            </div>
                        </div>
                    </div>
                    
                    <!--end of row1-->
                    <div class="span12" style="margin-bottom:2%;">
                        <div class="control-group">
                            <label class="control-label">Tanggal Masuk</label>
                            <div class="controls">
                                <input type="text" class="span2" name="tglmasuk" id="tglmasuk" value="<?php echo (isset($det->HireDate))?date('d-m-Y',strtotime($det->HireDate)):"";?>" readonly >
                            </div>
                        </div>
                    </div>
                    <!--row2-->
                    <div class="span5">
                        <div class="control-group">
                            <label class="control-label">Tanggal Mulai Bekerja</label>
                            <div class="controls">
                                <input type="text" class="span6" name="tglfirst" id="tglfirst" value="<?php echo (isset($det->DateFirstJoint))?date('d-m-Y',strtotime($det->DateFirstJoint)):"";?>" >
                            </div>
                        </div>
                    </div>
                    <div class="span5">
                        <div class="control-group">
                            <label class="control-label">Tanggal Lulus Masa Percobaan</label>
                            <div class="controls">
                                <input type="text" class="span6" name="tglprob" id="tglprob" value="<?php echo (isset($det->DatePassProbation))?date('d-m-Y',strtotime($det->DatePassProbation)):"";?>" >
                            </div>
                        </div>
                    </div>
                    <!--end of row2-->
                    <!--row3-->
                    <div class="span5" style="margin-bottom:2%;" >
                        <div class="control-group">
                            <label class="control-label" for="idloc">Lokasi Kerja</label>
                            <div class="controls">
                                <select name="idloc" id="idloc" class="selectpicker span7 upper">
                                    <option value="" ></option>
                                <?php 
                                foreach ($location->result() as $loc){
                                    if (($det->IDLocation == $loc->LocCode) or ($det->IDLocation == $loc->LocDes)){
                                        $selected = "selected";
                                    }
                                    else{
                                        $selected = "";
                                    }
                                    echo "<option value='$loc->LocCode' $selected >";
                                    echo $loc->LocDes;
                                    echo "</option>";
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="span5" style="margin-bottom:2%;" >
                        <div class="control-group">
                            <label class="control-label" for='statusk' >Status Karyawan</label>
                            <div class="controls">
                                <input type="text" class="span7" id='statusk' name='statusk' value='<?php echo $det->EmployeeStatus;?>' readonly >
                            </div>
                        </div>
                    </div>
                    <!--end of row3-->
                    <!--row4-->
                    <div class="span5" style="margin-bottom:2%;">
                        <div class="control-group">
                            <label class="control-label" for="group">GRUP</label>
                            <div class="controls">
                                <select class="selectpicker span5 upper" id="group" name="group">
                                    <option>-</option>
                                <?php
                                foreach($jobgroup->result() as $jgrp){
                                    if (($det->IDJobGroup == $jgrp->GroupCode) or ($det->IDJobGroup == $jgrp->GroupDesc)){
                                        $selected = "selected";
                                    }
                                    else{
                                        $selected = "";
                                    }
                                    echo "<option value='$jgrp->GroupCode' $selected >";
                                    echo $jgrp->GroupDesc;
                                    echo "</option>";
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="span5" style="margin-bottom:2%;">
                        <div class="control-group">
                            <label class="control-label" for="dprt">Departemen</label>
                            <div class="controls">
                                <select class="selectpicker span6 upper" id="dprt" name="dprt">
                                    <option>-</option>
                                <?php 
                                foreach ($departement->result() as $dprt){
                                    if ($det->IDDepartement == $dprt->IDStructure){
                                        $selected = "selected";
                                    }
                                    else{
                                        $selected = "";
                                    }
                                    echo "<option value='$dprt->IDStructure' $selected >";
                                    echo $dprt->DescStructure;
                                    echo "</option>";
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--end of row4-->
                    <!--row5-->
                    <div class="span5" style="margin-bottom:2%;">
                        <div class="control-group">
                            <label class="control-label" for="idpos">Posisi</label>
                            <div class="controls">
                                <select class="selectpicker span12 upper" id="idpos" name="idpos">
                                <?php 
                                foreach($position->result() as $pos){
                                    if (($det->IDJobPosition == $pos->PositionCode) or ($det->IDJobPosition == $pos->DescPosition)){
                                        $selected = "selected";
                                    }
                                    else{
                                        $selected = "";
                                    }
                                    echo "<option value='$pos->PositionCode' $selected >";
                                    echo $pos->DescPosition;
                                    echo "</option>";
                                }
                                ?>    
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="span5" style="margin-bottom:2%;">
                        <div class="control-group">
                            <label class="control-label" for="nmunit">Unit Kerja</label>
                            <div class="controls">
                                <input type="hidden" id="idunit" name="idunit"  value="<?php echo $det->IDUnitGroup;?>">
                                <input type="text" id="nmunit" name="nmunit" class="span12 upper" value="<?php 
                                $cunit = $this->pbl->get_unitjob($det->IDUnitGroup)->row();
                                echo ($cunit == NULL)?$det->IDUnitGroup:$cunit->UnitDesc;?>" >
                            </div>
                        </div>
                    </div>
                    <!--end of row5-->
                    <!--row6-->
                    <div class="span5">
                        <div class="control-group">
                            <label class="control-label" for="dnewcont">Tanggal kontrak baru</label>
                            <div class="controls">
                                <input type='text' class="span7" name='dnewcont' id='dnewcont' value="<?php echo (isset($det->DateNewContract))?date('d-m-Y', strtotime($det->DateNewContract)):"";?>" readonly >
                            </div>
                        </div>
                    </div>
                    <div class="span5">
                        <div class="control-group">
                            <label class="control-label" for="dendcont">Tanggal akhir kontrak</label>
                            <div class="controls">
                                <input type='text' class="span7" name='dendcont' id='dendcont' value="<?php echo (isset($det->DateEndContract))?date('d-m-Y', strtotime($det->DateEndContract)):"";?>" readonly >
                            </div>
                        </div>
                    </div>
                    <!--end of row6-->
                </div>
                <!--end of keterangan karyawan-->
                <hr class="separator">
                <div class="form-actions" align='center'>
                    <button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok" onclick="save()" ><i></i>Simpan</button>
                </div>
            </div>
        </div>
    </body>
</html>