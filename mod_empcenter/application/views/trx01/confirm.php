        <!-- JQueryUI -->
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
        <script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

<style>
    #containernya{
        border-radius: 7px;
        background-color: black;
        padding: 10px;
        text-align: center;
        width: 230px;
        height: 160px;
    }
    #ketdrop{
        color: #aaa;
    }
    .objdrag{
        border: solid #8da4cf 2px;
        border-radius: 7px;
        margin-left: 5px;
        width: 45px;
        height: 45px;
/*        cursor: -webkit-grab;
        cursor: -moz-grab;*/
    }

    #Red{
        display: inline-block;
        background-color: #CC0000;
    }
    #Green{
        display: inline-block;
        background-color: #00CC00;
    }
    #Yellow{
        display: inline-block;
        background-color: #CCCC00;
    }
    #Blue{
        display: inline-block;
        background-color: #0000CC;
    }
    #drop2{
        border: #077720 dashed medium;
        margin-top: 5px;
        width: 100%;
        height: 60px;
        background-color: #EEE;
        border-radius: 7px;
    }
    .capangka{
        font-family: Arial;
        font-size: 35px;
        color: black;
    }
</style>
<?php 
if ($via == "web"){
$base_url = $this->session->userdata('sess_base_url'); ?> 
<?php 
$color  = array("","Red","Yellow","Green","Blue");
$index  = rand(1, 4);
?>
<script>
    
$(".objdrag").draggable({
    cursor      : "-moz-grabbing",
    containment : "#dragarea",
    snap        : ".tptdrop",    
    stop        : function (event, ui){
//        alert($(this).attr("id"));
    }
});
var submitted = new Array();
$(".tptdrop").droppable({
    drop        : function (event, ui){        
        var item = ui.draggable.attr("id");
        if (this.id == "drop2"){
            var idx = submitted.indexOf(item);
//            alert(idx);
            if (idx == -1){
                submitted.push(item);                
            }
        }
        if (this.id == "drop1"){
            var index = submitted.indexOf(item);
            submitted.splice(index,1);
        }
//        alert(item+"to "+this.id);
    }
});
function accept(){
    var val = "<?php echo $color[$index]; ?>";
    var jmlbox = submitted.length;
    if (jmlbox == 1){
        if (submitted[0] == val){
            loading();
            $.ajax({
                url     : '<?php echo $base_url;?>/mod_empcenter/index.php/trx01/home/confirm/true/<?php echo "$ckode/$step/$userid";?>',
                data    : "via=web",
                type    : "post",
                success : function (){
                    bootbox.alert("Leave Request accepted! <br> Thanks for your confirmation", function (){
                        bootbox.hideAll();
                    });
                    reloadview();
                },
                error   : function (a,b){
                    alert("Tolong laporkan error ini ke sysdev!\nSalin semua teks dalam kotak ini dan emailkan ke sysdev\n okierie@yahoo.com atau okierie@triasindrasaputra.loc \n terima kasih \n"+a.responseText+"\n"+b);
                    bootbox.hideAll();
                }
            });        
        }
        else{
            bootbox.alert("Wrong color!");
        }
    }
    if (jmlbox == 0){
        bootbox.alert("Please drag and drop the box!");
    }
    if (jmlbox > 1){
        bootbox.alert("Don't drop another box into the white box!");
    }
}
function reject(){
    var val = "<?php echo $color[$index]; ?>";
    var jmlbox = submitted.length;
    if (jmlbox == 1){
        if (submitted[0] == val){
            bootbox.prompttextarea("You are going to reject this Leave Request.</isi>Please write your reason here :", function (r){
                if (r === null){

                }
                else{
                    loading();
                    $.ajax({
                        url     : '<?php echo $base_url;?>/mod_empcenter/index.php/trx01/home/confirm/true/<?php echo "$ckode/reject/$userid";?>',
                        data    : "r_reason="+r+"&via=web&from_step=<?php echo $step;?>&tgl_reject=<?php echo date('Y-m-d');?>",
                        type    : "post",
                        dataType: "html",
                        success : function (data){
    //                        bootbox.alert(data);
                            bootbox.alert("Thanks for your confirmation", function (){
                                bootbox.hideAll();
                            });
                            reloadview();
                        },
		        error   : function (a,b){
		            alert("Tolong laporkan error ini ke sysdev!\nSalin semua teks dalam kotak ini dan emailkan ke sysdev\n okierie@yahoo.com atau okierie@triasindrasaputra.loc \n terima kasih \n"+a.responseText+"\n"+b);
		            bootbox.hideAll();
		        }	
                    });  
    //                bootbox.alert("Thanks for you confirmation");
                }
            });
        }
        else{
            bootbox.alert("Wrong color!");
        }
    }
    if (jmlbox == 0){
        bootbox.alert("Please drag and drop the box!");
    }
    if (jmlbox > 1){
        bootbox.alert("Don't drop another box into the white box!");
    }
}

function loading(){
	bootbox.alertloading("<center>Processing. Please wait !!!<br><img src='<?php echo $base_url; ?>public/avatar/76.GIF'></center>");
}

</script>
Drag the <b><?php echo $color[$index]; ?></b> box into the white box!
<div id="containernya" >
    <div id="dragarea">
        <div class="tptdrop" id="drop1">
            <div  class="objdrag" id="Red"></div>
            <div  class="objdrag" id="Blue"></div>
            <div  class="objdrag" id="Green"></div>
            <div  class="objdrag" id="Yellow"></div>
        </div>
        <div class="tptdrop" id="drop2">
            <br>
            <span id="ketdrop">Drop the <b><?php echo $color[$index]; ?></b> box here!</span>
        </div>        
    </div>
    <div style="margin-top: 10px;">
        <button onclick="accept()" class="btn btn-small btn-success">Accept</button>
        <button onclick="reject()" class="btn btn-small btn-danger">Reject</button>
    </div>
</div>
<?php 
}else{
    
    $a  = rand(1,9);
    $b  = rand(1,9);
    $c  = $a+$b;
    ?>
<script>
    window.history.forward();
    function noBack() {
        window.history.forward();
    }
    function submitanswer(){
        var url = "<?php echo $url;?>";
        var jwb = document.getElementById('jawaban').value;
        var val = <?php echo $c; ?>;
        if ( jwb == val ){
            window.close();
            window.location=url;
        }else{
            alert("Wrong answer!");
        }
        
    }
</script>
You are going to accept the Leave Request.<br> 
Please solve this to make sure your confirmation :
<div onload="noBack()">
    <span class="capangka"><?php echo $a." + ".$b." = " ?></span><input id="jawaban" class="capangka" type="text" size="1" maxlength="2" style="width: 50px;"><br>
    <button onclick="submitanswer()">Submit</button>
</div>
<?php
}
?>
