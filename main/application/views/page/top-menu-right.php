<!-- Top Menu Right -->



<ul class="topnav pull-right">
    <!-- Language menu -->
    <!--
    <li class="hidden-phone dropdown dd-1 dd-flags" id="lang_nav">
        <a href="#" data-toggle="dropdown"><img src="<?php echo base_url(); ?>public/theme/images/lang/en.png" alt="en" /></a>
        <ul class="dropdown-menu pull-left">
            <li class="active"><a href="<?php echo site_url('language/en'); ?>" title="English"><img src="<?php echo base_url(); ?>public/theme/images/lang/en.png" alt="English"> English</a></li>
            <li><a href="<?php echo site_url('language/id'); ?>" title="Indonesian"><img src="<?php echo base_url(); ?>public/theme/images/lang/id.png" alt="Indonesian"> Indonesian</a></li>
        </ul>
    </li>
    -->
    <!-- // Language menu END -->

    <!-- Dropdown -->
    <!--
    <li class="dropdown dd-1 visible-desktop">
        <a href="" data-toggle="dropdown" class="glyphicons shield"><i></i>Get Help <span class="caret"></span></a>
        <ul class="dropdown-menu pull-right">

            <li class="dropdown submenu">
                <a href="#" class="dropdown-toggle glyphicons bell" data-toggle="dropdown"><i></i>Level 2</a>
                <ul class="dropdown-menu submenu-show submenu-hide pull-left">
                    <li class="dropdown submenu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Level 2.1</a>
                        <ul class="dropdown-menu submenu-show submenu-hide pull-left">
                            <li><a href="#">Level 2.1.1</a></li>
                            <li><a href="#">Level 2.1.2</a></li>
                            <li><a href="#">Level 2.1.3</a></li>
                            <li><a href="#">Level 2.1.4</a></li>
                        </ul>
                    </li>
                    <li class="dropdown submenu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Level 2.2</a>
                        <ul class="dropdown-menu submenu-show submenu-hide pull-left">
                            <li><a href="#">Level 2.2.1</a></li>
                            <li><a href="#">Level 2.2.2</a></li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li><a href="" class="glyphicons settings"><i></i>Some option</a></li>
            <li><a href="" class="glyphicons bell"><i></i>Some other option</a></li>
            <li><a href="" class="glyphicons bell"><i></i>Other option</a></li>

        </ul>
    </li>
    -->
    <!-- // Dropdown END -->

    <!-- Profile / Logout menu -->
    
    <li class="account dropdown dd-1">
        <span id="LiveDate"></span>
    </li>
    <li class="account dropdown dd-1">
        <span id="LiveTime"></span>
    </li>
    <li class="account dropdown dd-1">
        <a data-toggle="dropdown" href="#" class="glyphicons logout lock"><span class="hidden-phone"><?php echo " IP : " .$this->input->ip_address(); ?></span><i></i></a>
        <ul class="dropdown-menu pull-right">
            <li><a href="#" class="glyphicons cogwheel">Settings<i></i></a></li>
            <li><a href="#" class="glyphicons camera">My Photos<i></i></a></li>
            <li class="profile">
                <span>
                    <span class="heading">Profile <a href="#" class="pull-right">edit</a></span>
                    <span class="img"></span>
                    <span class="details">
                        <a href="#"><?php echo ucfirst(strtolower($this->session->userdata('sess_fullname'))); ?></a>
                        <?php echo $this->session->userdata('sess_email'); ?>
                    </span>
                    <span class="clearfix"></span>
                </span>
            </li>
            <li>
                <span>
                    <a class="btn btn-default btn-mini pull-right" href="<?php echo site_url('logout')?>">Sign Out</a>
                </span>
            </li>
        </ul>
    </li>
    <!-- // Profile / Logout menu END -->

</ul>
<!-- // Top Menu Right END -->

<script>
function LiveDate(){
    var D   = new Date();
    var day = D.getDay();
    var tgl = D.getDate();
    var bln = D.getMonth();
    var thn = D.getFullYear();
    var jam = D.getHours();
    var mnt = D.getMinutes();
    var dtk = D.getSeconds();
    var suf;
    if (jam < 10 ){
        jam = "0"+jam;
    }
    if (mnt < 10 ){
        mnt = "0"+mnt;
    }
    if (dtk < 10 ){
        dtk = "0"+dtk;
    }
    if (tgl == "1" || tgl == "21" || tgl == "31"){
        suf = "st";
    }
    else if (tgl == "2" || tgl == "22"){
        suf = "nd";
    }
    else if (tgl == "3" || tgl == "23"){
        suf = "rd";
    }
    else{
        suf = "th";
    }
    
    var dayarray    = new Array ("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
    var montharray  = new Array ("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    
    var livedate    = "&nbsp;"+dayarray[day]+", "+montharray[bln]+" "+tgl+", "+thn+"&nbsp;";
    var livetime    = "&nbsp;"+jam+":"+mnt+":"+dtk+"&nbsp;";
    $("#LiveDate").html(livedate);
    $("#LiveTime").html(livetime);
    setTimeout("LiveDate()",1000);
}
LiveDate();
</script>
