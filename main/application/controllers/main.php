<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Main extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('hits_model', 'hits');
        $this->load->model("menu_model","mnu");
        $this->load->model("login_model","log");
        $this->load->model("personal_model","prs");
        //$this->maintenance();
        $this->is_logged_in();
        $this->hits_info();
    }

    public function index() {
        $page['title'] = "Main Page";
        $page['fullname'] = $this->session->userdata('sess_fullname');
//        if ($this->session->userdata('sess_menu_in') != TRUE) {
////            $menua = $this->buildmenu($this->session->userdata('sess_userid')); 
//            $menua = $this->kk(); 
//            $session = array(
//                'sess_menu_in'=> TRUE,
//                'sess_menu'   => $menua
//            );
//            $this->session->set_userdata($session);
//        }
//        $menu['menu'] = $this->session->userdata('sess_menu');
//        echo $this->input->ip_address();//IP
        $userid     = $this->session->userdata('sess_userid');
        $u  = $this->prs->get_data_emp($userid)->row();
        $jg = $u->IDJobGroup;
        $userdata   = $this->prs->get_data_public($userid);
        $tabs       = $userdata->F1+$userdata->F2+$userdata->F3+$userdata->F4+$userdata->F5+$userdata->F6+$userdata->F7;
        $psteps     = $userdata->F1s1+$userdata->F1s2+$userdata->F1s3+$userdata->F1s4+$userdata->F1s5;
        $fjob       = $userdata->F2f1;
        $syarat     = $tabs+$psteps+$fjob;//harus berjumlah 13
//        $numlog     = $this->log->get_log($userid)->row()->Log;
        //echo $syarat;
        if ( $syarat < 13 and $jg == "ST"){
            $page['side_recent']     = "";
            $menu['menu']   = "";
            $menu['update']   = "notyet";
        }
        else if ( ($syarat == 13 and $jg == "ST") or ($jg != "ST") ){
           // if ($jg == "ST"){
                $whpar['ParamValue']    = $userid;
                $upar   = $this->prs->get_userparam($whpar)->result();
                $iu     = 0;
                $uprm   = Array();
                foreach ($upar as $up){
                    $uprm[$iu] = $up->IDParam;
                    $iu++;
                }
                $drcnt['upar']  = $uprm;
                $page['side_recent']     = $this->load->view('page/side-recent',$drcnt,TRUE);
          //  }else{
         //       $page['side_recent']     = "";
         //   }
            $menu['menu']   = $this->buildmenu($userid);
//            if ($numlog == "1"){
//                $menu['update']   = "notyet";
//            }
//            else {
                $menu['update']   = "";
//            }
        }
//        meloloskan
//        $menu['menu']   = $this->buildmenu($userid);
//        print_r($this->session->userdata('sess_user_menu'));
//        echo "Oki Erie Rinaldi";
        //$page['content'] = "blog";
        //$page['widget'] = "widget";
        //$page['posts'] = $this->posts->get_allposts(10);
        //$page['recent'] = $this->recently->get_post();
        //$page['comment'] = $this->commented->get_post();
//        $page['side_navigation'] =   $this->load->view('page/side-navigation','',TRUE);//ganti side navigation di sini
        
        $page['side_navigation'] = $this->load->view('page/side_nav',$menu,TRUE);//ganti side navigation di sini
        $this->load->view('template', $page);
    }

    public function missing() {
        $page['title'] = "404 Page Not Found";
        $page['content'] = "missing";
        $this->load->view('template', $page);
    }

    public function language($lang) {
        $page['title'] = "Language";
        $this->load->view('template', $page);
    }

    function is_logged_in() {
//        die();
        if ($this->session->userdata('sess_logged_in') != TRUE) {
            redirect('login', 'refresh');
        }
    }

    public function hits_info() {
        $info = array(
            'remote_addr' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'uri_string' => $this->uri->uri_string(),
            'referrer' => $this->agent->referrer()
        );
        $this->hits->insert($info);
    }
//    build menu
    function buildmenu($iduser){
//        $iduser = $this->session->userdata('sess_userid');
        $ghead  = $this->mnu->get_head_main()->result();
        $i = rand(0,100);
        $menu   = "";
        foreach ($ghead as $h){
            $i++;
            $cek = $this->mnu->get_access($h->IDMenu,$iduser)->row();
            if ($cek != NULL){
                $access = $cek->Access;
            }
            if ( $cek == NULl){
                $access = "0"; 
            }
//            $access = "";
            if ($access != "0"){
                $menu   .= "<li class='hasSubmenu glyphicons $h->MenuIcon'>";
                $menu   .= "<a data-toggle='collapse' href='#mnu_systemtea$i'><i></i><span> $h->MenuDesc </span></a>";
                $menu   .= $this->buildchild($i,$h->IDMenu,$iduser);
                $jmlchild= $this->mnu->get_jml_child($h->IDMenu);
                $menu   .= "<span class='count'>$jmlchild</span>";
                $menu   .= "</li>";              
            }
        }
        return $menu;
//        echo "<br><xmp width='100'> $menu </xmp>";
    }
    function buildchild($i=NULL,$idparent,$iduser){
        $tchild = $this->mnu->get_child_main($idparent)->result();
        $int    = rand(5000,6000);
//        $iduser = $this->session->userdata('sess_userid');
//        print_r($tchild);
        
        if ($tchild != NULL){
                
                $child  = "<ul class='collapse' id='mnu_systemtea$i' >";
                foreach ($tchild as $c){
                    $cek = $this->mnu->get_access($c->IDMenu,$iduser)->row();
                    if ($cek != NULL){
                        $access = $cek->Access;
                    }
                    if ($cek == NULL){
                        $access = "0";
                    }
                    $int++;
                    if ($access != '0'){
                        if ($c->HasSubMenu == 1){
                            $child  .= "<li class='hasSubmenu'>";
                            $child  .= "<a data-toggle='collapse' href='#mnu_systemtea$int'><span>$c->MenuDesc </span></a>";
                            $child  .= $this->buildchild($int,$c->IDMenu,$iduser);
                            $jmlchild = $this->mnu->get_jml_child($c->IDMenu);
                            $child  .= "<span class='count'>$jmlchild</span>";                        
                            $child  .= "</li>";
                        }
                        if ($c->HasSubMenu == 0){
                            $cek_this_parent = $this->mnu->get_menu_main($idparent)->row()->IDParent;
                            if ($cek_this_parent == 0){
                                $child  .= "<li>";
                                $child  .= "<ul>";
                                $child  .= "<li>";
                                $child  .= "<a url-mod='$c->URLMod' url-det='$c->URLDet' ><span> $c->MenuDesc </span></a>";
                                $child  .= "</li>";
                                $child  .= "</ul>";
                                $child  .= "</li>";
                            }
                            else{
                                $child  .= "<li>";
                                $child  .= "<a url-mod='$c->URLMod' url-det='$c->URLDet' ><span> $c->MenuDesc </span></a>";
                                $child  .= "</li>";
                            }
                        }                        
                    }
                }
                $child  .= "</ul>";
                return $child;
            }   
        }
        function maintenance(){
            $this->load->view("maintenance");
        }
    
}



/* End of file main.php */
/* Location: ./application/controllers/welcome.php */

