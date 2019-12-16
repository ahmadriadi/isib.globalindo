<?php $base_url = $this->session->userdata('sess_base_url'); ?>
<script>
function get_template_notif(){
    $.ajax({
        url     : "<?php echo $base_url?>/mod_security/index.php/trx03/home/notif_check",
        data    : "",
        type    : "post",
        dataType: "json",
        cache   : false,
        success : function (data){
            $(".notification").find(".count").html("<b>"+data.jml+"</b>");
            if (data.jml > 0){
                $(".notification").addClass("newnotif");
                var hasil = data.hasil;
                var res   = "";
                var status= "";
                var bulan = ["","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
                for(var i=0;i<hasil.length;i++){
                    if (hasil[i].bugstatus == '1'){
                        status  = "Solved";
                    }
                    else if(hasil[i].bugstatus == '2'){
                        status  = "Pending";
                    }
                    else if(hasil[i].bugstatus == '3'){
                        status  = "Unsolved";
                    }
                    else if(hasil[i].bugstatus == '4'){
                        status  = "is in progress";
                    }
                    if (hasil[i].needconf == '0'){
                        status  = "needs confirmation";
                    }
                    var tgl = hasil[i].statdate.split(" ");
                    var jam = tgl[1].substring(0,5);
                    var tgla= tgl[0].split("-");
                    
                    res = res+"<li class='listbug' idbug='"+hasil[i].idbug+"'><a>";
                        res = res+"<table width='100%'>";
                            res = res+"<tr>";
                                res = res+"<td align='left'>";
                                    res = res+"Rpt <b>#"+hasil[i].idbug+" "+status+"</b>";
                                res = res+"</td>";
                                res = res+"<td align='right'>";
                                    res = res+bulan[parseInt(tgla[1])]+" "+tgla[2]+" "+jam;
                                res = res+"</td>";
                            res = res+"</tr>";
                        res = res+"</table>";
                    res = res+"</a></li>";
                }
                $("#template_notification").html(res);
                $("#template_notification>li").click(function (){
                    // masuk ke 
                });
                $(".notification").on("blur",function (){
                    discard_notif();
                });
                $(".listbug").click(function (){
                    var url     = "<?php echo $base_url?>/mod_security/index.php/trx09/home/";
                    var content     = $("#content .innerLR");
                    content.empty();
                    content.load(url);
                });
            }else{
                var res = "<li align='center'><a>*No New Notification*</a></li>"
                $(".notification").removeClass("newnotif");
                $("#template_notification").html(res);
            }
        },
        error   : function (a){
            alert(a.responseText+"\n"+a.statusText);
        }
    });
}

function discard_notif(){
    $.ajax({
        url     : "<?php echo $base_url?>/mod_security/index.php/trx03/home/notif_discard",
        data    : "",
        type    : "post",
        dataType: "json",
        cache   : false,
        success : function (data){
//            alert(data.status);
        },
        error   : function (a){
            alert(a.responseText+"\n"+a.statusText);
        }
    });
}
</script>
<!-- Top Menu -->
<style>
    .newnotif{
        /*background: #7d1212;*/
        /*background: #b61a1a;*/
        background: -webkit-linear-gradient(#cc4d4d, #b61a1a); /* For Safari 5.1 to 6.0 */
        background: -o-linear-gradient(#cc4d4d, #b61a1a); /* For Opera 11.1 to 12.0 */
        background: -moz-linear-gradient(#cc4d4d, #b61a1a); /* For Firefox 3.6 to 15 */
        background: linear-gradient(#cc4d4d, #b61a1a); /* Standard syntax */        
    }
    .navbar.main .topnav > li.open .dropdown-menu{
        width: 350px;
        text-align: center;
    }
    .navbar.main .topnav > li.open.dd-1 .dropdown-menu{
        margin: 5px -35px 0px;
    }
    .navbar.main .topnav > li.open.dd-1 > .dropdown-menu:after{
        left: 78px;
    }    
    .navbar.main .topnav > li.open.dd-1 > .dropdown-menu:before{
        left: 77px;
    }    
/*    .newnotif:hover{
        background: #7d1212;
        background: #b61a1a;
        background: -webkit-linear-gradient(#cb1d4d, #b61a1a);  For Safari 5.1 to 6.0 
        background: -o-linear-gradient(#cb1d4d, #b61a1a);  For Opera 11.1 to 12.0 
        background: -moz-linear-gradient(#cb1d4d, #b61a1a);  For Firefox 3.6 to 15 
        background: linear-gradient(#cb1d4d, #b61a1a);  Standard syntax         
    }*/
    
</style>
<ul class="topnav pull-left tn1 hidden-phone">

    <!-- Themer -->
    <!--<li><a href="#themer" data-toggle="collapse" class="glyphicons eyedropper single-icon"><i></i></a></li>-->
    <!-- // Themer END -->

    <li class="dropdown dd-1 ">
        <a href="" data-toggle="dropdown" class="notification">Notifications <span class="count">0</span></a>
        <ul class="dropdown-menu pull-left" id="template_notification">
            
            <!--<li><a href="#" class="glyphicons envelope"><i></i> New Email</a></li>-->
        </ul>
    </li>
</ul>
<!-- // Top Menu END -->

