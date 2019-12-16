<?php
$attributes = array('target' => 'target_iframe');
?>
Select your file (jpg, gif, png)<br/>
<?php echo form_open_multipart($action, $attributes) ?>
<input type="file" name="userfile" size=""/><br/>    
<input type="submit" value="Upload" /> 
<?php echo anchor('#', 'Cancel', 'onclick="$(\'#upload_form_pic\').fadeOut();$(\'#btn_change\').fadeIn();return false;"') ?>
</form>
<iframe name="target_iframe" width="1" height="1" frameborder="0"></iframe>
