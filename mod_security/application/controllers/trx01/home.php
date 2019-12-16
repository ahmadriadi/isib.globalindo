<?php

class Home extends CI_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->model('login_model','login');
        $this->load->model(array('Mediatutorialupload', 'Mediatutorialutils', 'Mediatutorialprofile'));
        $this->load->helper(array('html','url', 'form'));
    }

    function anti_xss($source)
    {
            $f=stripslashes(strip_tags(htmlspecialchars ($source,ENT_QUOTES)));
            return $f;
    }
	
    
    function index(){
	
        //kita tampilkan thumbnail
        $subdata = array(
            'cropping_div' => $this->load->view('trx01/_account_cropping', '', true),
            'user_thumb' => $this->Mediatutorialprofile->genProfileThumb()
        );
        $content = $this->load->view('trx01/_account_profilepic_and_status', $subdata, true);
        //
        $data = array(
            'title' => 'Simple profile pic uploader',
            'body' => $content
        );
        $this->load->view('trx01/_output_html', $data);
	// $this->load->view('trx01/homex');
    }
    
    /*
     fungsi untuk upload image
     $mode= 'simple', 'multiple'
    */
    function upload_form($mode='multiple'){
        $data = array(
            'action' => site_url().'/trx01/home/upload_profile_pic/'
        );
        $this->load->view('trx01/_upload_'.$mode.'_form', $data);
    }
    
    function upload_profile_pic()
    {
        //
        $base_session = $this->session->userdata('sess_base_url');
        $file = 'userfile';
        $photo_profile_path = '../public/theme/images/mediaupload/photo_profile/';
        //
        $config['upload_path'] = $photo_profile_path.'temp/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']	= '10024';
        $config['max_width']  = '10024';
        $config['max_height']  = '10024';

        $result = $this->Mediatutorialupload->upload_process($config, $file);
        if($result['status']=='error'){
            $ret = '
                            <script type="text/javascript">
                            alert(\'Upload error: '.$result['msg'].'\');
                            </script>
                        ';
                        echo $ret;
        }
        else{
            $file_uploaded_name = $result['msg']['file_name'];
            //
            $explode = explode('.', $file_uploaded_name); // kita cari extentionnya '.jpg'
            //
            $file_uploaded_newname = $this->Mediatutorialutils->genRndDgt(5, false).'.'.$explode[1]; //buat random
            //
            $config['image_library'] = 'gd2';
            $config['source_image'] = $photo_profile_path.'temp/'.$file_uploaded_name;
            $config['new_image'] = $photo_profile_path.'temp/'.$file_uploaded_newname;
            $config['maintain_ratio'] = true;
            $config['width'] = 300;
            $config['height'] = 300;
            //
            $new_image_url = $base_session.'public/theme/images/mediaupload/photo_profile/temp/'.$file_uploaded_newname;
            //
            $result = $this->Mediatutorialupload->resize_process($config);
            if($result['status']=='error'){
                $ret = '
                            <script type="text/javascript">
                            alert(\'Resize to 300 x 300 error: '.$result['msg'].'\');
                            </script>
                        ';
                        echo $ret;
            }
            else{
                @unlink($config['source_image']);
                $ret = '
                    <script type="text/javascript">
                    parent.$(\'#btn_change\').fadeIn();
                    parent.$(\'#upload_form_pic\').fadeOut();
                    //
                    parent.$(\'#crop_photo\').attr(\'alt\',\''.$file_uploaded_newname.'\');
                    parent.$(\'#crop_photo\').attr(\'src\',\''.$new_image_url.'\');
                    parent.$(\'#crop_photo_preview\').attr(\'src\',\''.$new_image_url.'\');
                    parent.$(\'#box_shadowed_member_area\').fadeIn();
                    parent.window.generate_selection();
                    </script>
                ';
                echo $ret;
            }
        }
    }
        
    /*
     Fungsi untuk resize profile pic
     $filename = namafile
    */
    function crop_resize($filename, $x1, $y1, $w, $h){
        //
        $photo_profile_path = '../public/theme/images/mediaupload/photo_profile/';  
        $photo_temp = '../public/theme/images/mediaupload/photo_profile/temp/';  
        $file_uploaded_name = $filename;
        //
        //Mari kita cropping terlebih dahulu
        $config['image_library'] = 'gd2';
        $config['source_image'] = $photo_profile_path.'temp/'.$file_uploaded_name;
        $config['maintain_ratio'] = FALSE;
        $config['x_axis'] = $x1;
        $config['y_axis'] = $y1;
        $config['width'] = $w;
        $config['height'] = $h;
        //
        $ret = $this->Mediatutorialupload->crop_process($config);
        if($ret['status']=='error'){
            $ret = '
                        <script type="text/javascript">
                        alert(\'Cropping error: '.$ret['msg'].'\');
                        </script>
                    ';
                    echo $ret;
        }
        else{
             //Mari kita resize ke 100 x 100 terlebih dahulu
             unset($config);
            $this->image_lib->clear();
            //
            $config['image_library'] = 'gd2';
            $config['source_image'] = $photo_profile_path.'temp/'.$file_uploaded_name;
            $config['new_image'] = $photo_profile_path.$file_uploaded_name;
            $config['maintain_ratio'] = FALSE;
            $config['width'] = 100;
            $config['height'] = 100;
            //
            $ret = $this->Mediatutorialupload->resize_process($config);
            
            if($ret['status']=='error'){
                $ret = '
                        <script type="text/javascript">
                        alert(\'Resize to 100 x 100 error: '.$ret['msg'].'\');
                        </script>
                    ';
                    echo $ret;
            }
            else{
                @unlink($config['source_image']);
                unset($config);
                $this->image_lib->clear();
                //
                //Nah, baru kita resize ke 50 x 50
                $config['image_library'] = 'gd2';
                $config['source_image'] = $photo_profile_path.$file_uploaded_name;
                $config['new_image'] = $photo_profile_path.'icon_'.$file_uploaded_name;
                $config['width'] = 61;
                $config['height'] = 61;
                //
                $ret = $this->Mediatutorialupload->resize_process($config);
                if($ret['status']=='error'){
                    $ret = '
                            <script type="text/javascript">
                            alert(\'Resize to 50 x 50 error: '.$ret['msg'].'\');
                            </script>
                        ';
                        echo $ret;
                }
                else{
                    $base_session = $this->session->userdata('sess_base_url');
                    //$sourceimage  = $this->session->userdata('sess_sourceimage');
                    //$nameimage    = $this->session->userdata('sess_nameimage');
                    
                    $new_img_url = $base_session.'public/theme/images/mediaupload/photo_profile/'.$file_uploaded_name;
                    $ret = '
                        <script type="text/javascript">                        
                        $(\'.pic_thumb\').html(\''.img($new_img_url).'\');
                        $(\'#btn_change\').fadeIn();
                        $(\'#upload_form_pic\').fadeOut();
                        //
                        </script>
                    ';
                    echo $ret;
                    
                    /*
                    if($sourceimage ==null and $nameimage ==null ){
                            @unlink($sourceimage);                        
                            $this->login->delete_foto($nameimage);                        
                    }
                     * 
                     */
                    
                   
                    
                    $record = array(
                    "IDUser"=>$this->session->userdata('sess_userid'),
                    "SourceImage"=>$photo_profile_path.$file_uploaded_name,
                    "UrlImage"=>$new_img_url,
                    "NameImage"=>$file_uploaded_name

                    );
                    
                    $this->login->insertfoto($record); 
                    $this->session->set_userdata('sess_foto',$new_img_url);
                    
                    
                    
                }
            }
        }
    }
    
    /*
     fungsi untuk action
     bila ingin save maka $x, $y, $w, $h harus diisi
     bila cancel maka tidak perlu diisi kecuali $action dan $object
    */
    function action_pic($action, $object, $x1='', $y1='', $w='', $h=''){
        $photo_temp = '../public/theme/images/mediaupload/photo_profile/temp/';
        //
        if($action == 'cancel')
            @unlink($photo_temp.$object);
        elseif($action == 'save'){
            $this->crop_resize($object, $x1, $y1, $w, $h);
        }
    }
}

