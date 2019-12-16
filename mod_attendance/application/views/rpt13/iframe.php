 <?php $base_url = $this->session->userdata('sess_base_url') ?> 

 <!-- JQueryUI -->
<link rel="stylesheet" type="text/css" media="all" 
              href="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/development-bundle/demos/demos.css" />
<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-ui/js/jquery-ui-1.9.2.custom.min.js" type="text/javascript"></script>

<!----End JQuery --->

<iframe 
  src="<?php echo $url;?>" 
  frameborder="0" 
  width="100%" 
  height="1000" 
  scrolling="yes">
</iframe>
