<div class="box_shadowed" id="box_shadowed_member_area">
        <?php echo $cropping_div ?>
</div>

<div class="change_button">
        <input id="btn_change" type="button" value="Change" onclick="$(this).fadeOut('fast');load_simple_upload();"/>
</div>

<div class="form_profile_pic">
    <div id="btn_loading"></div>
    <div id="upload_form_pic" style="margin-left:15px;margin-top:5px;float:left;position:relative;">
        
    </div>
</div>
<?php echo $user_thumb ?>
