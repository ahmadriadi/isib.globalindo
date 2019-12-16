<div class="crop_container">
	<div style="width:100%;">
		Klik dan Drag photo di bawah untuk meng-crop
	</div>
	<div style="float: left;">
	  <div style="margin: 0 0 5px 0;" class="frame">
	    <img src="" alt="" id="crop_photo">
	  </div>
        <input type="button" value="Save Pic" onclick="action_pic('save');">
        <input type="button" value="Cancel" onclick="action_pic('cancel');">
	<span id="action_pic_loading"></span>
	<span id="action_pic_result"></span>
	</div>
 
	<div style="float: left;">
	<div class="thumb" style="margin-left:20px;"></div>
	  <div style="margin: 8px 0 0 27px; width: 100px; height: 100px;position:absolute;float:left;z-index:5;" class="frame" >
		<div style="width:100px;height: 100px; overflow: hidden;" id="preview">
		    <img style="margin-left: -11px; margin-top: -22px;" src="" id="crop_photo_preview">
	      </div>
	  </div>

	<input type="hidden" id="w" value="">
	<input type="hidden" id="h" value="">
	<input type="hidden" id="x1" value="">
	<input type="hidden" id="x2" value="">
	<input type="hidden" id="y1" value="">
	<input type="hidden" id="y2" value="">
        
	</div>
</div>
