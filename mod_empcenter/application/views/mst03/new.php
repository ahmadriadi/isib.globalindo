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
            var ROOT = {
                'site_url'  : '<?php echo $base_url . '/index.php'; ?>',
                'base_url'  : '<?php echo $base_url; ?>'
            };
        </script>
        <script type="text/javascript" charset="utf-8">
        $(document).ready(function(){

            $("#desc").focus();
            $("#btnsimpan").click(function(){
                var id = $("#id").val();
                var desc = $("#desc").val();
//                alert(id+desc);
                $.ajax({
                    url : "<?php echo site_url();?>/mst03/home/ins",
                    type: "POST",
                    data : "id="+id+"&desc="+desc,
                    success : function(data){
                        //alert(data);
                        $("#id").val("");
                        $("#desc").val("");
                        window.location.href = data;
                    }
                });
            });
        });
        </script>
    </head>
    <body>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>DESC</th>
            </tr>
            <?php 
            if ($units == NULL){
                echo "<tr><td colspan='2'>tidak ada data</td></tr>";
            }
            else{
                $next = "";
                foreach ($units as $u){
                    echo "<tr>";
                    echo "<td>";
                    echo $u->IDStructure;
                    echo "</td>";
                    echo "<td>";
                    echo $u->DescStructure;
                    echo "</td>";
                    echo "</tr>";
                    $next = $u->IDStructure;
                }
            }
            ?>
        </table>
        <hr id="sep">
        ID struktur<input type="text" id="id" value="<?php echo $next+1;?>" ><br>
        desc struktur<input type="text" size="100"id="desc"><br>
        <button id="btnsimpan">masukan</button>
    </body>
</html>



                                1<select class="selectpicker span7 upper" id="type" name="type">
                                    <?php if ($all == NULL){
                                        echo "<option value=''>None</option>";
                                    } else {  ?>
                                    <option value="N">Normal</option>
                                    <option value="S">Subordinat</option>
                                    <?php }?>
                                </select>

                                2<select class="selectpicker span7 upper" id="level" name="level">
                                    <?php 
                                    if ($all == NULL){
                                        echo "<option value='1'>1</option>";
                                    } else {
                                        for ($i=1;$i=5;$i++){
                                            echo "<option value='$i'>$i</option>";
                                        }
                                    }?>
                                </select>


