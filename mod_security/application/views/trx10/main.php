<div class="widget">
    <style>

    </style>
    <script>
        $(document).ready(function (){
            chgtab(0);
        });
    function chgtab(tab){
        $(".tabs").removeClass("active");
        $(".tabs").removeClass("span3");
        $(".tabs").addClass("span2");
//        setTimeout(function (){
            $(".tabs.tab"+tab).removeClass("span2");
            $(".tabs.tab"+tab).addClass("active");
            $(".tabs.tab"+tab).addClass("span3");
            loadrpt(tab);
//        },"200");   
    }
    function loadrpt(type){
        $.ajax({
            url     : "<?php echo site_url()?>/trx10/home/loadrpt",
            data    : "type="+type,
            type    : "post",
            dataType: "html",
            cache   : false,
            success : function (data){
                $("#probstat").html(data);
            },
            error   : function (a){
                alert(a.responseText+"\n"+a.statusText);
            }
        });
    }
    </script>
    <div class="widget-head">
        <div class="row-fluid">
            <div class="span6">
                <h4 class="heading">User Report / Request</h4>
            </div>
        </div>
    </div>
    <div class="widget-body">
        <div class="row-fluid">
            <div class="tabsbar tabsbar-2 active-fill">
                <ul class="row-fluid">
                    <li class="span3 tabs tab0 glyphicons tags active" onclick="chgtab(0)">
                        <a><i></i><b>
                                New Report / Request <?php echo $waiting?>
                        </b></a>                        
                    </li>
                    <li class="span2 tabs tab2 glyphicons clock" onclick="chgtab(2)">
                        <a><i></i><b>
                                Suspended <?php echo $suspension ?>
                        </b></a>
                    </li>
                    <li class="span2 tabs tab1 glyphicons ok_2" onclick="chgtab(1)">
                        <a><i></i><b>
                                Solved <?php echo $solved ?>
                        </b></a>
                    </li>
                    <li class="span2 tabs tab3 glyphicons remove_2" onclick="chgtab(3)">
                        <a><i></i><b>
                                Unsolved <?php echo $unsolved?>
                        </b></a>
                    <li class="span2 tabs tab4 glyphicons cogwheels" onclick="chgtab(4)">
                        <a><i></i><b>
                                In progress <?php echo $progress?>
                        </b></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row-fluid">
            <div class="widget widget-body span12" id="probstat">
                
            </div>
        </div>
    </div>
</div>
