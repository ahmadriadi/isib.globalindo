<ul class="menu-1">
    <li class="hasSubmenu active">
        <a class="glyphicons wifi_alt" href="#mnu-recent" data-toggle="collapse"><span><small>Notification</small></span><i></i></a>
        <ul class="collapse in" id="mnu-recent">
            <!-- <li><a class="glyphicons single circle_plus" url-mod="mod_security" url-det="trx07"><i></i><span>Daily Activity</span></a></li> --->
            <!--<li><a class="glyphicons single circle_plus" url-mod="mod_security" url-det="trx"><i></i><span>Things TO DO</span></a></li>-->
            <li><a class="glyphicons circle_exclamation_mark menutasks" url-mod="mod_security" url-det="trx03"><i></i><span id="numtasks">New Task</span></a></li>
            <?php 
            if (in_array("ITMGR", $upar) OR in_array("ITOFCR", $upar))
            {?>
                <li><a class="glyphicons warning_sign menureports" url-mod="mod_security" url-det="trx10"><i></i><span id="numreports">User Report</span></a></li>
            <?php } ?>
                
                
                <?php 
            if (in_array("NOTIF_FINANCE", $upar))
            {?>
                <li><a class="glyphicons circle_exclamation_mark financereport" url-mod="mod_finance" url-det="panelpane01"><i></i><span id="numfinreports">Digital Form Report</span></a></li>
            <?php } ?>
        </ul>
    </li>
</ul>

<div class="clearfix"></div>
<div class="separator bottom"></div>
  <!-- Item Click -->
        <script type="text/javascript">
        isib_checker();
        $(document).ready(function(){
            var menu_items = $('#menu .slim-scroll > ul.menu-1 li ul li a');
            menu_items.click(
                function()
                {
                    var content = $("#content .innerLR");
                    var url = ROOT.base_url + $(this).attr("url-mod") + '/index.php/' + $(this).attr("url-det") + '/home';
                    content.load(url);
                    return false;
                });
            return false;
        });
        function isib_checker(){
            check_tasks();
            get_template_notif();
            check_ureport();
            setTimeout("isib_checker()","15000");
        }
        function check_ureport(){
            $.ajax({
                url     : ROOT.base_url+"/mod_security/index.php/trx03/home/check_ureport",
                data    : "",
                type    : "post",
                dataType: "json",
                cache   : false,
                success : function (data){
                    var total   = data.jmlrpt;
                    total > 0 ? $(".menureports").addClass("btn-danger") : $(".menureports").removeClass("btn-danger");
                    $("#numreports").text(total+" New  User Report(s)");
                },
                error   : function (a){
                    alert(a.responseText+"\n"+a.statusText);
                }
            });
        }
        
        
        
        function check_tasks(){
            $.ajax({
                url     : ROOT.base_url+"/mod_security/index.php/trx03/home/tasks_check",
                data    : "",
                type    : "post",
                dataType: "json",
                cache   : false,
                success : function (data){
//                    alert(data.total);
                    var total = data.total;
//                    alert(total);
                    total > 0 ? $(".menutasks").addClass("btn-danger") : $(".menutasks").removeClass("btn-danger");
                    $("#numtasks").text(total+" New Task(s)");
                },
                error   : function (a){
                    alert(a.responseText+"\n"+a.statusText);
                }
            });
        }
          
        </script>
        <!-- Item Click END -->
