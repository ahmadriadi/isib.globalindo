
         <?php $base_url = $this->session->userdata('sess_base_url') ?> 
	<link href="<?php echo $base_url ?>public/theme/css/general.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $base_url ?>public/theme/css/imgareaselect-animated.css" rel="stylesheet" type="text/css" />
	<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery-1.6.1.min.js" type="text/javascript" language="javascript"></script>
	<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/functions.js" type="text/javascript" language="javascript"></script>
	<script src="<?php echo $base_url ?>public/theme/scripts/plugins/system/jquery.imgareaselect.pack.js" type="text/javascript" language="javascript"></script>

	<!-- script lanjutan-->
	<script type="text/javascript">
	var base_url = "<?php echo base_url()?>";
	var base_session = "<?php echo $base_url?>";
	var site_url = "<?php echo site_url()?>";
	
	function load_simple_upload(){
		get_html_data(site_url+"/trx01/home/upload_form/simple/",'', 'btn_loading', 'upload_form_pic');
	}
	</script>	
	<script type="text/javascript">
            function preview(img, selection) {
                if (!selection.width || !selection.height)
                    return;
                
                var scaleX = 100 / selection.width;
                var scaleY = 100 / selection.height;
            
                $('#preview img').css({
                    width: Math.round(scaleX * img.width),
                    height: Math.round(scaleY * img.height),
                    marginLeft: -Math.round(scaleX * selection.x1),
                    marginTop: -Math.round(scaleY * selection.y1)
                });
            
                $('#x1').val(selection.x1);
                $('#y1').val(selection.y1);
                $('#x2').val(selection.x2);
                $('#y2').val(selection.y2);
                $('#w').val(selection.width);
                $('#h').val(selection.height);    
            }
            
            function generate_selection(){
                $('#crop_photo').imgAreaSelect({
                    /*x1: 10,
                    x2: 110,
                    y1: 10,
                    y2: 110,*/
                    aspectRatio: '1:1',
                    handles: true,
                    fadeSpeed: 200,
                    onInit: preview,
                    onSelectChange: preview
                });
            }
            
            /*
            fungsi untuk save dan cancel
            image profile pic kita
            */
            function action_pic(action){
                pic = $('.frame').find('img').attr('alt');
                x1 = $('#x1').val();
                y1 = $('#y1').val();
                w = $('#w').val();
                h = $('#h').val();
                //
                extra = (action == 'save')?x1+'/'+y1+'/'+w+'/'+h:'';
                url_page = site_url+'/trx01/home/action_pic/'+action+'/'+pic+'/'+extra;
                //
                loading('action_pic_loading',true);
                setTimeout(function(){
                    $.ajax({
                        type: 'GET',
                        url: url_page,
                        data: '', 
                        cache: false,
                        dataType: 'html',
                        success: function(html){
                            if(action == 'save'){
                                $('#action_pic_result').html(html);
                            }
                            $('#crop_photo').imgAreaSelect({hide:true});
                            $('#box_shadowed_member_area').fadeOut(); 
                            loading('action_pic_loading',false);
                            //$('div.imgareaselect-outer').remove();
                        }
                    });
                }, 500);
            }
        </script>
	



 <!-- Widget -->
	<div class="widget widget-tabs widget-tabs-double-2 border-bottom-none">
	
		<!-- Widget heading -->
		<div class="widget-head border-bottom">
			<ul>
				<li class="active"><a class="glyphicons display" href="#overview" data-toggle="tab"><i></i>Overview</a></li>
				</ul>
		</div>
		<!-- // Widget heading END -->
		
		<div class="widget-body">
		
			<form class="form-horizontal" style="margin: 0;">
				<div class="tab-content" style="padding: 0;">
				
					<div class="tab-pane active widget-body-regular padding-none-TB" id="overview">
					
						<div class="row-fluid row-merge innerTB">
							<div class="span3 center">
							
								
									    
                                                                        <div class="mainDiv">
                                                                        <?php echo  $body ?>
                                                                        </div>									
								
								
							</div>
							
						</div>
				
					</div>
				
					
					
				</div>
			</form>
		</div>
	</div>
	<!-- // Widget END -->   
