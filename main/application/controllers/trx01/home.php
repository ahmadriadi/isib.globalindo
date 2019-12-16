<?php
// MENU
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("menu_model","mnu");
        $this->load->model("uac_model","uac");
    }
    function index(){
        $iduser             = $this->session->userdata("sess_userid");
//        $data['menuauto']   = json_encode($this->mnu->get_menu(NULL,"auto")->result_array());
        $data['menudrop']   = $this->mnu->get_head()->result();
        $data['menutree']   = $this->menutree();
        $data['menu']       = $this->mnu->get_menu();
        $idmenu             = "19";
        $data['buttons']    = $this->uac->get_btnaccess($iduser,$idmenu);
        $this->load->view("trx01/home",$data);
    }
    function add(){
        $data['nextidmenu'] = ($this->mnu->getlastid()->row()->maxid)+1;
        echo json_encode($data);
    }
    function addmenu(){
		$fapps     = $this->input->post("fapps");	
        $idmenu     = $this->input->post("idmenu");
        $idparent   = $this->input->post("idparent");
        $tmenu      = $this->input->post("tmenu");
        $hassub     = $this->input->post("hassub");
        $urlmod     = $this->input->post("urlmod");
        $urldet     = $this->input->post("urldet");
        $icon       = $this->input->post("iconmod");
        $level      = $this->input->post("level");
        $record     = array(
		    "ForApplication"    => $fapps,
            "IDMenu"    => $idmenu,
            "IDParent"  => $idparent,
            "MenuDesc"  => $tmenu,
            "HasSubMenu"=> $hassub,
            "URLMod"    => $urlmod,
            "URLDet"    => $urldet,
            "MenuIcon"  => $icon,
            "Level"     => $level
        );
        $wherebtn = array("IDMenu" => $idmenu , "Status" => "T");
        $rec    = array("Status"    => "A");
        $this->mnu->update_button($wherebtn,$rec);
        $this->mnu->add_menu($record);
        $msg    = array("status" => "oke");
        echo json_encode($msg);
    }
    function edit(){
        $idmenu         = $this->input->post("idmenu");
        $menu           = $this->mnu->get_menu($idmenu)->row();
        $data['btn']    = $this->mnu->get_button($idmenu)->result_array();
        $this->session->set_userdata("btn_bef_edit",$data['btn']);
        $data['menu']   = array(
            "idmenu"    => $idmenu,
			"fapps"  => $menu->ForApplication,	
            "idparent"  => $menu->IDParent,
            "tmenu"     => $menu->MenuDesc,
            "urlmod"    => $menu->URLMod,
            "urldet"    => $menu->URLDet,
            "hassub"    => $menu->HasSubMenu,
            "level"     => $menu->Level,
            "modicon"   => $menu->MenuIcon
        );
        echo json_encode($data);
    }
    function editmenu(){
	    $fapps     = $this->input->post("fapps");		
        $idmenu     = $this->input->post("idmenu");
        $idparent   = $this->input->post("idparent");
        $tmenu      = $this->input->post("tmenu");
        $hassub     = $this->input->post("hassub");
        $urlmod     = $this->input->post("urlmod");
        $urldet     = $this->input->post("urldet");
        $icon       = $this->input->post("iconmod");
        $level      = $this->input->post("level");
        $record     = array(
		    "ForApplication"  => $fapps,
            "IDParent"  => $idparent,
            "MenuDesc"  => $tmenu,
            "HasSubMenu"=> $hassub,
            "URLMod"    => $urlmod,
            "URLDet"    => $urldet,
            "MenuIcon"  => $icon,
            "Level"     => $level
        );
        $where  = array ("IDMenu"   => $idmenu);
        $wherebtn = array("IDMenu" => $idmenu , "Status" => "T");
        $rec    = array("Status"    => "A");
        $this->mnu->update_button($wherebtn,$rec);
        $this->mnu->update_menu($where,$record);
        $msg    = array("status" => "oke");
        echo json_encode($msg);        
    }
    function delmenu(){
        $idemployee = $this->session->userdata("sess_userid");
        $idmenu     = $this->input->post("idmenu");
        $pwd        = $this->input->post("pwd");
        $cek_pwd    = $this->mnu->cek_pwd($idemployee,$pwd)->num_rows();
        if ($cek_pwd > 0){
            $where      = array( "IDMenu"   => $idmenu);
            $this->mnu->delete_menu($where);
            $msg        = array( "status"   => "oke");
        }elseif ($cek_pwd == 0){
            $msg        = array( "status"   => "bad");
        }
        echo json_encode($msg);
    }
    function menutree(){
        $ghead = $this->mnu->get_head()->result();
        $tree = "";
        foreach ($ghead as $thead){
            $tree .= "<li>";
            $tree .= "<a href='javascript:void(0)'>".$thead->MenuDesc."</a>";
            $tree .=  $this->treechild($thead->IDMenu);
            $tree .= "</li>";
        }
        return $tree;
    }    
    function treechild($idparent){
        $tchild = $this->mnu->get_child($idparent)->result();
        if ($tchild != NULL){
            $tree = "";
            foreach($tchild as $tc){
                $tree .="<ul>";
                $tree .="<li>";
                $tree .="<a href='javascript:void(0)'>".$tc->MenuDesc."</a>";
                $tree .= $this->treechild($tc->IDMenu);
                $tree .="</li>";
                $tree .="</ul>";            
           
            }
            return $tree;
        }
    }
    function build_user_access($idmenu){
        $alluser    = $this->mnu->get_user();
        foreach ($alluser->result() as $au){
            $rec    = array( "IDUser" => $au->IDEmployee, "IDMenu" => $idmenu);
            $this->mnu->add_access($rec);            
        }
    }
    function build_access(){
        $alluser    = $this->mnu->get_user();
        $allmenu    = $this->mnu->get_menu();
        $allaccess  = $this->mnu->get_access();
        echo $alluser->num_rows()."<br>";
        echo $allaccess->num_rows();
        echo $allmenu->num_rows();
//        foreach ($alluser->result() as $au){
//            echo $au->FullName."=>";
//            foreach ($allmenu->result() as $am){
//                $rec    = array( "IDUser" => $au->IDEmployee, "IDMenu" => $am->IDMenu);
//                $this->mnu->add_access($rec);
//            }
//            echo "OK <br>";
//        }
//        echo "blocked by administrator";
    }
    function syncmenu(){
        $allmenu    = $this->mnu->get_menu();
        $allbtn     = $this->mnu->get_button();
        $i = 0;
        foreach ($allmenu->result() as $a){
            $cek = $this->mnu->get_access($a->IDMenu)->num_rows();
            if ($cek == "0"){
                $i++;
                $this->build_user_access($a->IDMenu);
            }
//            echo $a->IDMenu." => ".$cek."<br>";
        }
        $ii = 0;
        foreach ($allbtn->result() as $b){
            $cek = $this->mnu->get_btnaccess($b->IDMenu,$b->IDButton)->num_rows();
            if ($cek == "0"){
                $ii++;
                $alluser    = $this->mnu->get_user();
                foreach ($alluser->result() as $u){
                    $rec    = array("IDUser" => $u->IDEmployee , "IDMenu" => $b->IDMenu , "IDButton" => $b->IDButton);
                    $this->mnu->add_btnaccess($rec);
                }
            }
        }
        $msg['btn'] = array( "synced" => $ii);
        $msg['menu']= array( "synced" => $i);
        echo json_encode($msg);
    }
    function addbtn(){
        $idmenu     = $this->input->post('idmenu');
        $idbutton   = $this->input->post('idbutton');
        $btn        = explode(',', $this->input->post('btndesc')); 
        $rec        = array(
            "IDButton"  => $idbutton,
            "IDMenu"    => $idmenu,
            "ButtonDesc"=> $btn[0],
            "KdButton"  => $btn[1],
            "Status"    => "T"
        );
        $this->mnu->add_btn($rec);
        echo "{'status' : 'oke'}";
    }
    function editb(){
        $idmenu     = $this->input->post("idmenu");
        $idbutton   = $this->input->post("idbutton");
        $btn        = $this->mnu->get_button($idmenu,$idbutton)->row();
        echo json_encode($btn);
    }
    function editbtn(){
        $idmenu     = $this->input->post('idmenu');
        $idbutton   = $this->input->post('idbutton');
        $btndesc    = explode(',', $this->input->post('btndesc')); 
        $rec        = array(
            "ButtonDesc"    => $btndesc[0],
            "KdButton"      => $btndesc[1]
        );
        $where      = array(
            "IDButton"  => $idbutton,
            "IDMenu"    => $idmenu
        );
        $this->mnu->update_button($where,$rec);
        echo "{'status' : 'oke'}";
    }
    function delbtn(){
        $idmenu     = $this->input->post('idmenu');
        $idbutton   = $this->input->post('idbutton');
        if ($idbutton == "canceladd"){
            $where      = array(
                "IDMenu"    => $idmenu,
                "Status"    => "T"
            );

        }
        else if ($idbutton == "canceledit"){
            $where      = array(
                "IDMenu"    => $idmenu
            );

        }
        else{
            $where      = array(
                "IDButton"  => $idbutton,
                "IDMenu"    => $idmenu
            );

        }
        $cek = $this->mnu->get_button(NULL,NULL,$where)->result();
        if ($cek == NULL){}
        else{
            $this->mnu->del_button($where);
//            if ($idbutton == "canceledit"){
//                $btn_bef = $this->session->userdata("btn_bef_edit");
//                foreach ($btn_bef as $b){
//    //                print_r($b);
//                    $this->mnu->add_btn($b);
//                }
//                $this->syncmenu();
//    //            print_r($btn_bef[0]);
//            }
        }
        $msg = array( "status" => "oke");
        echo json_encode($msg);
    }
    function add_a_user($iduser){
        $menu = $this->mnu->get_menu()->result();
        $button = $this->mnu->get_button()->result();
        $i  = 0;
//        echo $button;
        foreach($menu as $m){
            $i++;            
            $rec    = array("IDUser" => $iduser, "IDMenu" => $m->IDMenu, "Access" => "0");
            $this->mnu->add_access($rec);
            echo $i.$m->MenuDesc." OKE <br>";
        }
        $o  = 0;
        foreach ($button as $b){
            $o++;
            $recbtn = array("IDUser" => $iduser, "IDMenu" => $b->IDMenu, "IDButton" => $b->IDButton, "Access"   => "0");
            $this->mnu->add_btnaccess($recbtn);
            echo $o.$b->IDMenu.$b->ButtonDesc." OKE <br>";
        }
    }
}

