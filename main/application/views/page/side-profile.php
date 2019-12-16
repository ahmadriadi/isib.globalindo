<!-- Sidebar Profile -->
<span class="profile">
    <p>Welcome <a href=""><?php echo ucfirst(strtolower($this->session->userdata('sess_fullname'))); ?></a></p>
    <!-- <a class="img" href=""><img src="<?php echo base_url(); ?>public/theme/images/avatar-style-dark.jpg" alt="Avatar" /></a> -->
    <a class="img" >
        
        <img src="<?php echo $this->session->userdata('sess_foto');?>" alt="Avatar" />
    </a>
    <span>
        <ul>
            <li><a class="glyphicons envelope"  url-mod="mod_public" url-det="emailexternal"><i></i>Email</a></li>
            <li><a class="glyphicons lock"  url-mod="mod_security" url-det="trx01"><i></i>Account</a></li>
            <li><a class="glyphicons keys"  url-mod="mod_security" url-det="trx02"><i></i>Password</a></li>
            <li><a class="glyphicons eject" url-mod="mod_main"  url-det="logout"><i></i>Logout</a></li>
        </ul>
    </span>
</span>
<!-- // Sidebar Profile END -->

        <!-- Item Click -->
        <script type="text/javascript">
            var menu_items = $('#menu .profile span ul li a');

            menu_items.click(
                    function()
                    {
                        var content = $("#content .innerLR");
                        var url = ROOT.base_url+$(this).attr("url-mod")+'/index.php/'+$(this).attr("url-det")+ '/home';
                        if ($(this).attr("url-det")=='logout') {
                            window.location.href = ROOT.site_url+'/logout';                            
                        }else if($(this).attr("url-det")=='emailexternal'){
                            var param =  ROOT.base_url+$(this).attr("url-mod")+'/index.php/iframecontent';
                            content.load(param);
                          }else {
                            content.fadeOut("slow", "linear");
                            content.load(url);
                            content.fadeIn("slow");
                        }
                    });
                   
        </script>
        <!-- Item Click END -->

        
    
