<!----End JQuery --->
<iframe 
  src="<?php echo $url_form;?>" 
  frameborder="0" 
  width="100%" 
  height="1000" 
  scrolling="yes">
</iframe>

 <script>
$(function() {
                $("#atl-form").dialog({
                    autoOpen: false, //width: 940,                                                                                            
                    closeOnEscape: true,
                    modal: true,
                    resizable: true,
                    draggable: true,
                    width: 800,
                    height: 600,
                    position: "center",                    
                    close: function() {                     
                        $("#atl-form").html(""); 
                        $("#atl-form").dialog('destroy');
                      
                    }

                });
            });
</script>

