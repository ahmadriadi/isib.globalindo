
<style>
a{cursor: pointer;}


</style>
<!-- Regular Size Menu -->
<ul class="menu-0">


    <!-- Menu Regular Item -->
    <li class="glyphicons display active" ><a href="<?php echo base_url(); ?>"><i></i><span >Dashboard</span></a></li>
    <?php echo $menu;?>
</ul>
<div class="clearfix"></div>
<!-- // Regular Size Menu END -->

<!-- Item Click -->
<script type="text/javascript">
$(document).ready(function(){
    var menu_items = $('#menu .slim-scroll > ul.menu-0 ul li ul li a');
	
    menu_items.unbind('click');
    menu_items.click(
            function()
            {
                var content = $("#content .innerLR");
                var url = ROOT.base_url + $(this).attr("url-mod") + '/index.php/' + $(this).attr("url-det") + '/home';
//                alert(url);
//                content.slideUp("slow");
               // content.fadeOut("slow", "linear");
                content.load(url);
//                content.slideDown("slow");
                //content.fadeIn("slow");
                return false;
            });
    <?php 
    if ($update == "notyet"){
        echo "to_personal();";
    }
    ?>
});
function to_personal(){
    var content = $("#content .innerLR");
    var url = ROOT.base_url + 'mod_empcenter/index.php/trx02/home';
    //                alert(url);
    //                content.slideUp("slow");
    //content.fadeOut("slow", "linear");
    content.load(url);
    //                content.slideDown("slow");
    //content.fadeIn("slow");
    return false;    
}
</script>
<!-- Item Click END -->
