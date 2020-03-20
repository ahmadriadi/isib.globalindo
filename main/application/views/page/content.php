<?php 
$serverip = $_SERVER['SERVER_ADDR'];
$servername = $_SERVER['SERVER_NAME'];
$enroll =  $this->session->userdata('sess_enroll');
$iduser =  $this->session->userdata('sess_userid');
$base_url = $this->session->userdata('sess_base_url');

$urlexplode = explode("/", $base_url);
$urldata = $urlexplode[0] . '/' . $urlexplode[1] . '/' . $urlexplode[2].'/';




?>
<?php $base_url = $this->session->userdata('sess_base_url'); ?>
<!-- JQueryUI -->
<link href="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/css/smoothness/jquery-ui-1.9.2.custom.css" rel="stylesheet" />
<!--<script src="<?php echo $base_url; ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.js"></script>-->
<link href="<?php echo $base_url;?>public/weathericons/css/weather-icons.css" rel="stylesheet">
<!-- DataTables Tables Plugin -->
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/tables/DataTables/media/js/DT_bootstrap.js"></script>
<style>
    /*neon*/
#glow a {
  color: #fff;
  font-family: Monoton;
  -webkit-animation: neon1 1.5s ease-in-out infinite alternate;
  -moz-animation: neon1 1.5s ease-in-out infinite alternate;
  animation: neon1 1.5s ease-in-out infinite alternate;
}

#glow  a:hover {
  color: #FF1177;
  -webkit-animation: none;
  -moz-animation: none;
  animation: none;
}



/*glow for webkit*/

@-webkit-keyframes neon1 {
  from {
    text-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px #fff, 0 0 40px #FF1177, 0 0 70px #FF1177, 0 0 80px #FF1177, 0 0 100px #FF1177, 0 0 150px #FF1177;
  }
  to {
    text-shadow: 0 0 5px #fff, 0 0 10px #fff, 0 0 15px #fff, 0 0 20px #FF1177, 0 0 35px #FF1177, 0 0 40px #FF1177, 0 0 50px #FF1177, 0 0 75px #FF1177;
  }
}




/*glow for mozilla*/

@-moz-keyframes neon1 {
  from {
    text-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px #fff, 0 0 40px #FF1177, 0 0 70px #FF1177, 0 0 80px #FF1177, 0 0 100px #FF1177, 0 0 150px #FF1177;
  }
  to {
    text-shadow: 0 0 5px #fff, 0 0 10px #fff, 0 0 15px #fff, 0 0 20px #FF1177, 0 0 35px #FF1177, 0 0 40px #FF1177, 0 0 50px #FF1177, 0 0 75px #FF1177;
  }
}


/*glow*/

@keyframes neon1 {
  from {
    text-shadow: 0 0 10px #F9F9F9, 0 0 20px #F9F9F9, 0 0 30px #F9F9F9, 0 0 40px #F9F9F9, 0 0 70px #F9F9F9, 0 0 80px #F9F9F9, 0 0 100px #F9F9F9, 0 0 150px #F9F9F9;
  }
  to {
    text-shadow: 0 0 5px #0DC143, 0 0 10px #0DC143, 0 0 15px #0DC143, 0 0 20px #0DC143, 0 0 35px #0DC143, 0 0 40px #0DC143, 0 0 50px #0DC143, 0 0 75px #0DC143;
  }
}


#back-to-top {
        position: fixed;
        bottom: 40px;
        right: 40px;
        z-index: 9999;
        width: 32px;
        height: 32px;
        text-align: center;
        line-height: 30px;
        background: #f5f5f5;
        color: #444;
        cursor: pointer;
        border: 0;
        border-radius: 2px;
        text-decoration: none;
        transition: opacity 0.2s ease-out;
        opacity: 0;
    }
    #back-to-top:hover {
        background: #e9ebec;
    }
    #back-to-top.show {
        opacity: 1;
    }
    #content {
        height: 100%;
    }
    
</style>



<div id="content"">
    <h2>Dashboard <span>Web Application</span></h2> 
    <div class="innerLR">
<?php 
$u  = $this->prs->get_data_emp($iduser)->row();
$dept = $u->IDDepartement;
$paramcctv  = $this->prs->getparam_empcenter($iduser,'CCTV_PRODUKSI');
$param  = $this->prs->getparam_empcenter($iduser,'FRONT EMPLOYEE CENTER');
$role = $this->session->userdata('sess_role'); 
//print_r($u);
if ($u->IDJobGroup == "ST" or $param=='exist'){
?> 
        <div class="row-fluid">
           
            <div class="span3">
                <!-- Stats Widget -->
                <a url-mod="mod_empcenter" url-det="trx02" class="widget-stats">
                        <span class="glyphicons user"><i></i></span>
                        <span class="txt">Personal</span>
                        <div class="clearfix"></div>
                        <!--<span class="count label label-important">20</span>-->
                </a>
                <!-- // Stats Widget END -->
            </div>
            <div class="span3">
                <!-- Stats Widget -->
                <a url-mod="mod_empcenter" url-det="trx01" class="widget-stats">
                        <span class="glyphicons calendar"><i></i></span>
                        <span class="txt">Leave</span>
                        <div class="clearfix"></div>
                        <!--<span class="count label label-important">20</span>-->
                </a>
                <!-- // Stats Widget END -->
            </div>
            <div class="span3">
                <!-- Stats Widget -->
                <a url-mod="mod_attendance" url-det="trx01" class="widget-stats">
                        <span class="glyphicons clock"><i></i></span>
                        <span class="txt">Overtime</span>
                        <div class="clearfix"></div>
                        <!--<span class="count label label-important">20</span>-->
                </a>
                <!-- // Stats Widget END -->
            </div>
            <div class="span3">
                <!-- Stats Widget -->
                <a url-mod="mod_attendance" url-det="trx02" class="widget-stats">
                        <span class="glyphicons cars"><i></i></span>
                        <span class="txt">Leave Permit</span>
                        <div class="clearfix"></div>
                        <!--<span class="count label label-important">20</span>-->
                </a>
                <!-- // Stats Widget END -->
            </div>
        </div>
        <hr class="separator">
        <div class="row-fluid">
            <div class="span9">               

        <div class="row-fluid">
            <div class="span4">
                <!-- Stats Widget -->
                <a url-mod="mod_attendance" url-det="trx03" class="widget-stats">
                        <span class="glyphicons circle_exclamation_mark"><i></i></span>
                        <span class="txt">Incomplete Presence</span>
                        <div class="clearfix"></div>
                        <!--<span class="count label label-important">20</span>-->
                </a>
                <!-- // Stats Widget END -->
            </div>
            
            <div class="span4">
                <!-- Stats Widget -->
                <a url-mod="mod_attendance" url-det="trx04" class="widget-stats">
                        <span class="glyphicons bus"><i></i></span>
                        <span class="txt">Official Travel</span>
                        <div class="clearfix"></div>
                        <!--<span class="count label label-important">20</span>-->
                </a>
                <!-- // Stats Widget END -->
            </div>
            
            <div class="span4">
                <!-- Stats Widget -->
                <a url-mod="mod_empcenter" url-det="trx03" class="widget-stats">
                    
                        <span class="glyphicons notes_2"><i></i></span>
                        <span class="txt">Memo</span>
                        <div class="clearfix"></div>
                        <!--<span class="count label label-success ">20</span>-->
                </a>
                <!-- // Stats Widget END -->
            </div>
           
        </div>
        
        <?php 
}
else{
    
}
        ?>


<hr class="separator">
<div class="row-fluid">
 	  <div class="span4">
                <!-- Stats Widget -->
                <a url-mod="mod_security" url-det="trx09" class="widget-stats">
                        <span class="glyphicons warning_sign"><i></i></span>
                        <span class="txt">Contact IT</span>
                        <div class="clearfix"></div>
                        <!--<span class="count label label-success ">20</span>-->
                </a>
                <!-- // Stats Widget END -->
            </div>
      <div class="span4">
                <a url-mod="mod_empcenter" url-det="trx04" class="widget-stats">
                        <span class="glyphicons tags"><i></i></span>
                        <span class="txt">Weekly Activity</span>
                        <div class="clearfix"></div>
                </a>
 </div>

            	

</div>
            </div>
	            <?php
            		if ($u->IDJobGroup == "ST" or $param=='exist'){
		    ?> 
    
            <div class="span3">
                <script>
				
//                function get_weather(){
//                    var nav = navigator.geolocation.getCurrentPosition(get_weather_by_position);
//                }
//                function get_weather_by_position(pos){
//                    var lat = pos.coords.latitude;
//                    var long    = pos.coords.longitude;
//                    $.ajax({
////                        url     : "http://api.openweathermap.org/data/2.5/weather?q=Penjaringan,id&units=metric",
//                        url     : "http://api.openweathermap.org/data/2.5/weather?lat=-6.1229618&lon=106.7432408&units=metric",
//                        dataType: "json",
//                        success : function (data){
//                            var wid     = data.weather[0].id;
//                            var main    = data.weather[0].main;
//                            var details = data.weather[0].description;
//                            var temp    = data.main.temp;
//                            var tempmin = data.main.temp_min;
//                            var tempmax = data.main.temp_max;
//                            var locname = data.name;
//                            var weather = [
//                                {"804":"wi-day-sunny-overcast",
//                                "800":"wi-day-sunny",
//                                "801":"wi-day-cloudy",
//                                "802":"wi-cloudy",
//                                "803":"wi-cloudy",
//                                "500":"wi-day-rain-mix",
//                                "501":"wi-day-rain-mix",
//                                "502":"wi-rain-shower",
//                                "503":"wi-rain-shower",
//                                "504":"wi-rain",
//                                "520":"wi-day-rain-mix",
//                                "521":"wi-rain-shower",
//                                "522":"wi-rain",
//                                "531":"wi-hail",
//                                "200":"wi-day-lightning",
//                                "201":"wi-day-storm-shower",
//                                "202":"wi-day-thunderstorm",
//                                "211":"wi-lightning",
//                                "212":"wi-lightning",
//                                "221":"wi-lightning",
//                                "230":"wi-day-storm-showers",
//                                "231":"wi-day-storm-showers",
//                                "232":"wi-day-storm-showers",
//                                "741":"wi-fog",
//                                "711":"wi-smoke",
//                                "721":"wi-fog",
//                                "701":"wi-dust"
//                            }
//                            ];
//                            var res = "";
////                            res = res+"<h4>Penjaringan<br><span>"+details.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();})+"</span></h4><br>";
//                            res = res+"<h4>"+locname+"<br><span>"+details.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();})+"</span></h4><br>";
//                            res = res+"<span style='font-size: 4em;'><i class='wi "+weather[0][wid]+"'></i>";
//                            res = res+" "+Math.floor(temp)+""+"<i class='wi wi-celsius' style='font-size: 1em;' ></i></span>";
//                            res = res+"<br>";
//                            res = res+"<br>";
//                            res = res+"<h5>Min: "+tempmin+"&deg;C | Max: "+tempmax+"&deg;C</h5>"
//                            
//                            $("#tptweather").html(res);;
//                            
//                        },
//                        error   : function (a){
//                            alert(a.responseText+"|"+a.statusText);
//                        }
//                    });
////                    273.15 K = 0 C
//                }
//                $(document).ready(function (){
//                    get_weather();
//                    setInterval("get_weather()","300000");
//                });
                </script>
                <div class="widget" style="width: 100%;" >
                    <div class="widget-head">
                        <h4 class="heading" onclick="get_position()">Weather </h4>
                    </div>
                    <div class="widget-body-custome text-center" id="tptweather">

                    </div>
                </div>
            </div>
            
            
            <?php   if($dept =='19' or $iduser =='0249230309' or $iduser =='0664250315'){    ?>     



  <?php }?>
            
         
            
            
        </div>
        
           
        
	<?php } ?>
        
        
            <?php   if($role =='2' or $paramcctv =='exist'){    ?>     
        
        <hr class="separator">
      
        	<?php } ?>
        
        <hr class="separator">
        
        <div class="row-fluid">
          
            <div class="widget span7">
                <div class="widget-head">
                    <h4 class="heading">Attendance Information <span id="tglselected"></span></h4>
                </div>
                <div class="widget-body">
                    <div class="row-fluid">
                        
                        <div class="span6 input-append">
                            <!--<div class="row-fluid">-->
                            <input class="span7" placeholder="Search data by date" type="text" id="tglsearch"><span style="margin-left: 0px; cursor: pointer;" onclick="searchdata()" class="add-on glyphicons search" ><i></i></span>                                
                            <!--</div>-->
                        </div>
                        <div class="span6 btn-group" style="text-align: right;">
                            <button btnnya='btnprev' onclick = 'prevatt()' class="btn btn-small btn-icon btn-default ">&vartriangleleft;</button>
                            <input type="hidden" id="dateattinfo" value="<?php echo date('Y-m-d');?>">
                            <button btnnya='btnnext' onclick = 'nextatt()' class="btn btn-small btn-icon btn-default">&vartriangleright;</button>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <table class="table table-condensed">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>State</th>
                                        <th>Location</th>
                                    </tr>
                                </thead>
                                <tbody id="attinfo">
                                </tbody>
                            </table>                            
                        </div>
                    </div>
                </div>
            </div>            
        </div>
	  <!--- start table hbd ---->
        <?php  $position = $this->session->userdata('sess_position');
               $role = $this->session->userdata('sess_role'); 
	     // echo 'Position : '.$position.' Role : '.$role;
		
            if($position=='DIRECTOR' 
               OR $position=='VICE PRESIDENT'
               OR $position=='KOMISARIS'
               OR $position=='ASSISTANT DIRECTOR'
               OR $position=='MANAGER' 
	       OR $position=='ASSISTANT MANAGER'   		             
               OR  $role=='1' 
	       OR  $role=='2'	   
              ){                          
            ?>
        <div class="row-fluid">
            <div class="widget" data-collapse-closed="true" data-toggle ="collapse-widget">
                <div class="widget-head">
                    <h4 class="heading">Birthday Information <span id="tglselected"></span></h4>
                </div>
                <div class="widget-body">                   
                    <div class="row-fluid">
                        <div class="span12">
                            <table id="tablehbd" class="table table-condensed">                               
                            </table>                            
                        </div>
                    </div>
                </div>
            </div>            
        </div> 
         <?php } ?>
        <!---end table hbd -->
    </div>
        <div class="tickertape" style="margin-top: 2%;">
            <strong class="title">Important</strong>
            <span class="marquee">
                <span><strong>This line</strong> is simply dummy text of the system development.</span>
                <span>web application has been the <strong>industry's standard</strong> software ever since over the year.</span>
            </span>
        </div>

    </div>
	
</div>


<span id="glow"> <a href="#" id="back-to-top" title="Back to top"><img src="<?php echo $base_url ?>public/avatar/top.png"></a> </span>
<!-- Item Click -->
<script type="text/javascript">
    
$(document).ready(function(){
    $("#tglsearch").keypress(false);
    $("#tglsearch").datepicker({
        dateFormat : "dd-mm-yy",
        maxDate    : "+0D"
    });
    var menu_items = $('.widget-stats');

    menu_items.click(
            function()
            {
                var content = $("#content .innerLR");
                var url = ROOT.base_url + $(this).attr("url-mod") + '/index.php/' + $(this).attr("url-det") + '/home';
                
		if($(this).attr("url-det")=='intranet'){
	          window.open("<?php echo $urldata.'intranet';?>",'_blank');	
		}else if($(this).attr("url-det")=='produksi'){
                  window.open("<?php echo $urldata.'leavepermit/index.php/cctv';?>",'_blank');	
                }else{
		 
                content.fadeOut("slow", "linear");
                content.load(url);
                content.fadeIn("slow");
                return false;	
	
		}
                
            });
   get_attinfo();
});
function searchdata(){
    var val = $("#tglsearch").val();
    var tgl = val.split("-");
    $("#dateattinfo").val(tgl[2]+"-"+tgl[1]+"-"+tgl[0]);
    get_attinfo();
    
}
function prevatt(){
    var curdate = $("#dateattinfo").val();
    var a = curdate.split("-");
    var th = a[0];
    var bl = a[1];
    var tg = a[2];
    var prev = new Date(th,bl-1,tg);
    prev.setDate(prev.getDate()-1);
//    alert(curdate+"|"+th+"|"+bl+"|"+tg+"|"+prev);
    var blnnya ;
    (prev.getMonth()+1) < 10 ? blnnya = "0"+(prev.getMonth()+1) : blnnya = (prev.getMonth()+1);
    var tglnya ;
    (prev.getDate()) < 10 ? tglnya = "0"+(prev.getDate()) : tglnya = (prev.getDate());
    $("#dateattinfo").val(prev.getFullYear()+"-"+blnnya+"-"+tglnya);
//    alert($("#dateattinfo").val());
    get_attinfo();

}
function nextatt(){
    var curdate = $("#dateattinfo").val();
    var a = curdate.split("-");
    var th = a[0];
    var bl = a[1];
    var tg = a[2];
    var prev = new Date(th,bl-1,tg);
    prev.setDate(prev.getDate()+1);
//    alert(curdate+"|"+th+"|"+bl+"|"+tg+"|"+prev);
    var blnnya ;
    (prev.getMonth()+1) < 10 ? blnnya = "0"+(prev.getMonth()+1) : blnnya = (prev.getMonth()+1);
    var tglnya ;
    (prev.getDate()) < 10 ? tglnya = "0"+(prev.getDate()) : tglnya = (prev.getDate());
    $("#dateattinfo").val(prev.getFullYear()+"-"+blnnya+"-"+tglnya);
    get_attinfo();
}
function get_attinfo(){
    $("#attinfo").empty();
    var enroll = "<?php echo $enroll;?>";
    var tgl = $("#dateattinfo").val();
    $.ajax({
        url     : ROOT.base_url+"mod_attendance/index.php/ref33/home/get_data",
        type    : "post",
        data    : "tgl="+tgl,
        dataType: "json",
        cache   : false,
        success : function (data){
//            alert(data.toSource());
            var state, loc;
            var res = '';
            for(var i = 0; i < data.length; i++){
                data[i].Direction == "0" ? state = "OUT" : state = "IN";
                data[i].Location == "0" ? loc = "Unknown" : (data[i].Location == "1" ? loc = "Kapuk" : loc = "Bitung");
                res = res+'<tr>';
                res = res+'<td>'+(i+1)+'</td>';
                res = res+'<td>'+data[i].EnrollDate+'</td>';
                res = res+'<td>'+data[i].EnrollTime+'</td>';
                res = res+'<td>'+state+'</td>';
                res = res+'<td>'+loc+'</td>';
                res = res+'</tr>';
            }
            $("#attinfo").append(res);
            
//            alert(tgl);
            tgl = tgl.split("-");
            $("#tglselected").text(tgl[2]+"-"+tgl[1]+"-"+tgl[0]);
            var insertdate = new Date(tgl[0],(tgl[1]-1),tgl[2]);
//            var insertdate = ins.getFullYear()+"-"+ins.getMonth()+"-"+ins.getDate();
            var dd  = new Date();
            var now = new Date(dd.getFullYear(),dd.getMonth(),dd.getDate());
//            var now = no.getFullYear()+"-"+no.getMonth()+"-"+no.getDate();
            var banding = insertdate-now;
//            alert(insertdate+"|"+now+"|"+banding);
            var picthn, picbln, pictgl,urlpictin, urlpictout, urlnopict,hpictin, hpictout;
            picthn = tgl[0];
            picbln = tgl[1];//< 10 ? "0"+tgl[1] : tgl[1]
            pictgl = tgl[2];// < 10 ? "0"+tgl[2] : tgl[2];
            urlpictin = "http://<?php echo $servername; ?>/live/photo/"+picthn+"/"+picbln+"/"+pictgl+"/"+enroll+"_1.jpg";
            urlpictout= "http://<?php echo $servername; ?>/live/photo/"+picthn+"/"+picbln+"/"+pictgl+"/"+enroll+"_0.jpg";
            //cek_gambar(urlpictin,"in");
            //cek_gambar(urlpictout,"out");
	    //alert(urlpictin+"\n"+urlpictout);
            if (banding == 0){
//                alert("sama");
                $("button[btnnya='btnnext']").prop("disabled",true);
            }
            if (banding < 0){
                $("button[btnnya='btnnext']").prop("disabled",false);                
            }
//            alert(hpictin+"|"+hpictout);
////            alert(cek_gambar(urlpictin)+"|"+cek_gambar(urlpictout));
////            alert(picthn+"/"+picbln+"/"+pictgl);
//            $("#pictin").attr("src",urlpictin);
//            $("#pictout").attr("src",urlpictout);
            
        }
    });
}
function cek_gambar(url,w){
    var urlnopict = "<?php echo $base_url."public/avatar/noimage.jpg"?>";
    $.get(url)
            .done(function (){
                //alert(url+"ada");
                if (w == "in"){
                    $("#pictin").attr("src",url);
                }
                if (w == "out"){
                    $("#pictout").attr("src",url);
                }
            })
            .fail(function (){
                //alert(url+"tidak ada");
                if (w == "in"){
                    $("#pictin").attr("src",urlnopict);
                }
                if (w == "out"){
                    $("#pictout").attr("src",urlnopict);
                }
            });
    
    // masalah di sini tidak bisa return value
//    var http = url;
//    http.open('HEAD',url,false);
//    http.send();
//    return http.status != 404;
}


$(document).ready(function() {                                    
                                    
                                    var dataajax = '<?php echo base_url('main/index.php/trx03/home/datahbday') ?>';
                                    
                                    var oTable = $('#tablehbd').dataTable({
                                        "bJQueryUI": false,
                                        "bSortClasses": false,
                                        "aaSorting": [[2, "asc"]],
                                        "bAutoWidth": true,
                                        "bInfo": true,
                                        "sScrollY": "100%",
                                        "sScrollX": "100%",                                        
                                        "bScrollCollapse": true,
                                        "sPaginationType": "bootstrap",
                                        "iDisplayLength": 5, //for default display
                                        "aLengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                                        //"bLengthChange": false, //for hide display perpage
                                        "bFilter": false, //for hide text search
                                        //"bPaginate": false, //for hide pagination
                                        "bRetrieve": true,
                                        "oLanguage": {
                                            "sSearch": "Search:"
                                        },
                                        "bProcessing": true,
                                        "bServerSide": true,
                                        "sAjaxSource": dataajax,
                                        "fnServerData": function(sSource, aoData, fnCallback) {
                                            $.ajax({
                                                "dataType": 'json',
                                                "type": "POST",
                                                "url": sSource,
                                                "data": aoData,
                                                "success": fnCallback
                                            });
                                        },
                                        "aoColumns": [
                                            
                                            {"mData": "IDEmployee", "sTitle": "IDEmployee", "sClass": "left"},
                                            {"mData": "FullName", "sTitle": "FullName", "sClass": "left"},
                                            {"mData": "BirthDate", "sTitle": "Birth Date", "sClass": "left"},
                                            //{"mData": "HireDate", "sTitle": "Hire Date", "sClass": "center"},                                         
                                            {"mData": "JGroup", "sTitle": "Status", "sClass": "left"},
                                            {"mData": "DescStructure", "sTitle": "Departement", "sClass": "left"},
                                            //{"mData": "Position", "sTitle": "Position", "sClass": "left"},
                                        ],
                                        "fnDrawCallback": function(oSettings) {                                           
                                            $("#tablehbd tbody tr").on('mouseenter', function() {
                                                $('#tablehbd tbody tr').addClass("selectable");

                                            });
                                        }

                                    });



                                });
                                
                                if ($('#back-to-top').length) {
    var scrollTrigger = 100, // px
        backToTop = function () {
            var scrollTop = $(window).scrollTop();
            if (scrollTop > scrollTrigger) {
                $('#back-to-top').addClass('show');
            } else {
                $('#back-to-top').removeClass('show');
            }
        };
    backToTop();
    $(window).on('scroll', function () {
        backToTop();
    });
    $('#back-to-top').on('click', function (e) {
        e.preventDefault();
        $('html,body').animate({
            scrollTop: 0
        }, 700);
    });
}

</script>
<!-- Item Click END -->

