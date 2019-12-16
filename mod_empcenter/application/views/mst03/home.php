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

        <script type="text/javascript" charset="utf-8">
            $(document).ready(function() {
                $("#forms").hide();
            });
            $(window).resize(function(){
                var elewidth = $(".desc1").width();
                var elewidth2 = $(".desc2").width();
//                alert(elewidth+"|"+elewidth2);
                $(".desc1").each(function(i){
                    var limitchar = elewidth / 10;
                    var len = $(this).text().length;
                    if (len > limitchar){
                        $(this).text($(this).text().substr(0,limitchar)+'...');
                    }
                });
                $(".desc2").each(function(i){
                    var limitchar = elewidth2 / 10;
                    var len = $(this).text().length;
                    if (len > limitchar){
                        $(this).text($(this).text().substr(0,limitchar)+'...');
                    }
                });
            });
            var data_unit = <?php echo $units;?>;
            $(function() {
                $( "#nmparent" ).autocomplete({
                     source: data_unit,
                     select: function (event, ui){
                          $("#idparent").val(ui.item.idunit);
                          $("#plevel").find("b").text("Level "+ui.item.level);
                     }
                 });
            });            
            var ROOT = {
                'site_url'  : '<?php echo $base_url . '/index.php'; ?>',
                'base_url'  : '<?php echo $base_url; ?>'
            };
            function add(){
                $("#tproses").val("add_process");
                $("#forms").find(".widget-head").find("h4").text("Add New");
                $("#structuretable").hide();
                $("#forms").show();
            }
            function reloadpage(){
                var content = $("#content .innerLR");
                var url = ROOT.base_url + 'mod_empcenter/index.php/mst03/home';
                //alert(url);
                content.fadeOut("slow", "linear");
                content.load(url);
                content.fadeIn("slow");
            }
            var newwindow;
            function vieworg(){

        	newwindow=window.open("<?php echo site_url();?>/mst03/home/chart/2",'Organization Structure','height=600,width=1000,toolbar=no,addressbar=disabled,status=no,resizable=yes,location=no,menubar=no, scrollbars=yes');
                if (window.focus) {newwindow.focus()}                
//                var content = $("#content .innerLR");
//                var url = ROOT.base_url + 'mod_empcenter/index.php/mst03/home/chart/2';
//                //alert(url);
//                content.fadeOut("slow", "linear");
//                content.load(url);
//                content.fadeIn("slow");
            }
            function save(){
                var proses    = $("#tproses").val();
                var idunit    = $("#idunit").val();
                var idparent  = $("#idparent").val();
                var descunit  = $("#descunit").val().toUpperCase();
                var type      = $("#type").val();
                var level     = $("#level").val();
//                alert(proses+idunit+"|"+idparent+"|"+descunit+"|"+type+"|"+level);
                if ((idparent!='')&&(descunit!='')){
                    $.ajax({
                        url     : "<?php echo site_url(); ?>/mst03/home/"+proses,
                        data    : "idunit="+idunit+"&idparent="+idparent+"&descunit="+encodeURIComponent(descunit)+"&type="+type+"&level="+level,
                        type    : "POST",
                        dataType: "json",
                        cache   : false,
                        success : 
                            function (data){
    //                            alert(data);
                                if (data.status == "oke"){
                                    reloadpage();
                                    bootbox.alert("Data saved");
                                }
                                if (data.status == "bad"){
                                    bootbox.alert(data.msg);
                                }
                            }
                    });
                }
                else if(idparent == ''){
                    $("div.control-group.idparent").addClass('error');
                    $("input#nmparent").after("<p class='error help-block'><span class='label label-important'>Please enter the valid parent</span></p>");
                }
                else if(descunit == ''){
                    $("div.control-group.descunit").addClass('error');
                    $("input#descunit").after("<p class='error help-block'><span class='label label-important'>Please enter the name of unit</span></p>");
                }
            }
            function edit(ids){
                $.ajax({
                    url : "<?php echo site_url(); ?>/mst03/home/edit",
                    data: "ids="+ids,
                    type: "POST",
                    dataType : "json",
                    cache : false,
                    success : 
                        function (data){
//                            alert(data);
                            $("#structuretable").hide();
                            $("#tproses").val("edit_process");
                            $("#idunit").val(data.idunit);
                            $("#idparent").val(data.idparent);
                            $("#nmparent").val(data.nmparent);
                            $("#descunit").val(data.descunit);
                            $("#type").val(data.type);
                            $("#level").val(data.level);
                            if (data.idparent == '0' && data.level == '1'){
                                $("#nmparent").val("NONE");
                                $("#nmparent").prop("readonly",true);
                                $("#idparent").val("0");
                                $("div.row-fluid.typerel").remove();
                                var i;
                                for(i=2;i<=7;i++){
                                    $("select#level").find("option[value='"+i+"']").remove();
                                }
                                $("#forms").append("<input type='hidden' id='type' value='0'>");
                            }
                            $("#forms").show();
                        }
                });
            }
            function del_data(idunit){
                bootbox.confirm("You are going to delete a unit of organization structure. Continue delete?", 
                    function(result){
                        if (result == true){
                            $.ajax({
                                url     :"<?php echo site_url(); ?>/mst03/home/delete",
                                data    :"idunit="+idunit,
                                type    :"POST",
                                dataType:"json",
                                cache   :false,
                                success :
                                    function (data){
                                        reloadpage();
                                        if (data.status == "bad"){ bootbox.alert("Delete Failed!");}
                                        $.gritter.add({
                                            title   : data.title,
                                            text    : data.text
                                        });
                                    }
                            });
                        }
                    });
            }

        </script>
    </head>
    <body>
        <div id="structuretable" class="widget">
            <div class="widget-head">
                <h4 class="heading">Table of Organization Structure</h4>
            </div>
            <div class="widget-body">
                <div class="row-fluid">
                    <p align="right">
                        <button onclick="add()" class="btn btn-primary btn-icon glyphicons circle_plus"><i></i> Add</button>
                        <button onclick="vieworg()" class="btn btn-primary btn-icon glyphicons print"><i></i> Print</button>
                    </p>
                    <table class="table table-bordered table-condensed table-striped table-primary table-vertical-center js-table-sortable dynamicTable">
                        <thead class="btn-primary">
                            <tr>
                                <!--<th>No.</th>-->
                                <th>Structure Desc</th>
                                <!--<th>ID Structure Parent</th>-->
                                <th>Structure Parent Desc</th>
                                <th>Type</th>
                                <th>Level</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 0;
                            foreach ($all as $a){
                                $no++;
                                echo "<tr  class='selectable'>";
                                echo "<td class='desc1' title='".$a->DescStructure."'>".$a->DescStructure."</td>";
                                echo "<td class='desc2' title='".$a->DescParent."'>".$a->DescParent."</td>";
                                echo "<td><center>".$a->RelType."</center></td>";
                                echo "<td><center>".$a->Level."</center></td>";
                                echo "<td>
                                    <center>
                                    <button  type='button' class='btn btn-mini btn-primary' title='Edit ".$a->DescStructure."' onclick='edit(\"".$a->IDStructure."\")' ><i class='icon-pencil'></i></button>
                                    <button  type='button' class='btn btn-mini btn-primary' title='Delete ".$a->DescStructure."' onclick='del_data(\"".$a->IDStructure."\")'><i class='icon-trash'></i></button>
                                    </center>
                                    </td>";
                                echo "</tr>";
                    
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="forms" class="widget form-horizontal">
            <input type="hidden" id="tproses">
            <div class="widget-head">
                <h4 class="heading">Add New</h4>
            </div>
            <div class="widget-body">
                <div class="row-fluid" style="margin-bottom:2%;">
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label" for="idunit">Unit ID</label>
                            <div class="controls">
                                <input type="text" class="span2" name="idunit" id="idunit" value="<?php echo $nextid;?>" readonly >
                            </div>
                        </div>
                    </div>                    
                </div>
                <div class="row-fluid" style="margin-bottom:2%;">
                    <div class="span12">
                        <div class="control-group idparent">
                            <label class="control-label" for="nmparent">Unit Parent</label>
                            <div class="controls">
                                <input type="hidden" class="span7" name="idparent" id="idparent" <?php echo $all == NULL ? "readonly value='0'" : ""; ?> >
                                <input type="text" class="span7 upper" name="nmparent" id="nmparent" <?php echo $all == NULL ? "readonly value='NONE'" : ""; ?> ><span id="plevel"><b></b></span>
                            </div>
                        </div>
                    </div>                    
                </div>
                <div class="row-fluid" style="margin-bottom:2%;">
                    <div class="span12">
                        <div class="control-group descunit">
                            <label class="control-label" for="descunit">Unit Description</label>
                            <div class="controls">
                                <input type="text" class="span7 upper" name="descunit" id="descunit">
                            </div>
                        </div>
                    </div>                    
                </div>
                <div class="row-fluid typerel" style="margin-bottom:2%;" >
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label" for="type">Relation Type</label>
                            <div class="controls">
                                <select class="selectpicker span7 upper" id="type" name="type">
                                    <?php if ($all == NULL){
                                        echo "<option value='0'>None</option>";
                                    } else {  ?>
                                    <option value="Sub">Subordinate</option>
                                    <option value="Col">Collateral</option>
                                    <option value="Stf">Staff</option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>                    
                </div>
                <div class="row-fluid" >
                    <div class="span12">
                        <div class="control-group">
                            <label class="control-label" for="level">Unit Level</label>
                            <div class="controls">
                                <select class="selectpicker span7 upper" id="level" name="level">
                                    <?php 
                                    if ($all == NULL){
                                        echo "<option value='1'>1</option>";
                                    } else {
                                        for ($i=1;$i<=7;$i++){
                                            echo "<option value='$i'>$i</option>";
                                        }
                                    }?>
                                </select>
                            </div>
                        </div>
                    </div>                    
                </div>
                <hr class="separator">
                <div class="row-fluid">
                    <p align="center">
                        <button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok" onclick="save()" ><i></i>Save</button>
                        <button type="button" class="btn btn-icon btn-warning glyphicons circle_remove" onclick="reloadpage()"><i></i>Cancel</button>
                    </p>
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
    </body>
</html>
