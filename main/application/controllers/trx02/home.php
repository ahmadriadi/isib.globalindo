<?php
// UAC
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("uac_model","uac");
        $this->load->model("menu_model","mnu");
    }
    function index(){
        $data['users']= $this->uac->get_user();
        $iduser       = $this->session->userdata("sess_userid");
        $idmenu       = "20";
        $data['teks'] = "User Access Control will be here";
        $data['buttons']    = $this->uac->get_btnaccess($iduser,$idmenu);
        $this->load->view("trx02/home",$data);
        
    }
    function detail($iduser=NULL){
        $iduser     = $this->input->post("iduser");
        $head       = $this->mnu->get_head()->result();
        foreach($head as $h){
            $m1     = $this->uac->get_access($iduser,$h->IDMenu);
            if ($m1->num_rows() != 0){
                $akses['menu'][] = $m1->row();
            }            
            $child  = $this->mnu->get_child($h->IDMenu)->result();
            if ($child != NULL){
                foreach($child as $c){
                    $m2      = $this->uac->get_access($iduser,$c->IDMenu);
                    if ($m2->num_rows() != 0){
                        $akses['menu'][] = $m2->row();
                    }                    
                    $schild  = $this->mnu->get_child($c->IDMenu)->result();
                    if ($schild != NULL){
                        foreach ($schild as $sc){
                            $m3     = $this->uac->get_access($iduser,$sc->IDMenu);
                            if ($m3->num_rows() != 0){
                                $akses['menu'][]= $m3->row();
                            }                            
                        }
                    }
                }
            }
        }
        $akses['role']   = $this->uac->get_role($iduser)->row();
        echo json_encode($akses);
    }
    function detail_button($iduser=NULL,$idmenu=NULL){
        $iduser     = $this->input->post("iduser");
        $idmenu     = $this->input->post("idmenu");
        $btaccess   = $this->uac->get_btnaccess($iduser,$idmenu)->result();
        echo json_encode($btaccess);
    }
    function all_menu(){
        $iduser     = $this->input->post("iduser");
//        $json       = $this->input->post("json");
        $head       = $this->mnu->get_head()->result();
        foreach($head as $h){
            $menu[] = array(
                "idmenu"    => $h->IDMenu,
                "menu"      => $h->MenuDesc,
                "level"     => $h->Level,
                "idparent"  => $h->IDParent
                    ); 
            $child  = $this->mnu->get_child($h->IDMenu)->result();
            if ($child != NULL){
                foreach($child as $c){
                    $menu[] = array(
                        "idmenu"    => $c->IDMenu,
                        "menu"      => $c->MenuDesc,
                        "level"     => $c->Level,
                        "idparent"  => $c->IDParent
                            );
                    $schild  = $this->mnu->get_child($c->IDMenu)->result();
                    if ($schild != NULL){
                        foreach ($schild as $sc){
                            $menu[] = array(
                                "idmenu"    => $sc->IDMenu,
                                "menu"      => $sc->MenuDesc,
                                "level"     => $sc->Level,
                                "idparent"  => $sc->IDParent
                                    );                  
                        }
                    }
                }
            }
        }
        echo json_encode($menu);
    }
    function button($idmenu=NULL){
        $idmenu     = $this->input->post("idmenu");
        $buttons    = $this->uac->get_button($idmenu)->result();
        echo json_encode($buttons);
    }
    function chg_role(){
        $iduser     = $this->input->post("iduser");
        $role       = $this->input->post("role");
        $this->uac->update_role($iduser,$role);
        if ($role == '2'){
            $wh = array("IDUser" => $iduser);
            $rec= array("Access" => "1");
            $this->uac->update_btnaccess($wh,$rec);
        }
        if ($role == '0'){
            $wh = array("IDUser" => $iduser);
            $rec= array("Access" => "0");
            $this->uac->update_btnaccess($wh,$rec);
            $this->uac->update_access($wh,$rec);
        }
        $msg    = array("status" => "oke");
        echo json_encode($msg);
    }
    function chg_acc($iduser=NULL,$idmenu=NULL,$access=NULL){
        $iduser     = $this->input->post("iduser");
        $idmenu     = $this->input->post("idmenu");
        $access     = $this->input->post("access");
        $set        = $this->input->post("set");
        $where1     = array("IDUser" => $iduser, "IDMenu" => $idmenu);
        $rec        = array("Access" => $access);
        $msg        = "";
        if ($set == "0"){
            if ($access == "0"){
                $child      = $this->mnu->get_child($idmenu)->result();
                if ($child != NULL){
                    $msg    = "and all it's sub menu";
                    foreach($child as $c){
                        $where2      = array("IDUser" => $iduser, "IDMenu" => $c->IDMenu);
                        $schild     = $this->mnu->get_child($c->IDMenu)->result();
                        if ($schild != NULL){
                            foreach($schild as $sc){
                                $where3      = array("IDUser" => $iduser, "IDMenu" => $sc->IDMenu);
                                $this->uac->update_access($where3,$rec);
                            }
                        }
                        $this->uac->update_access($where2,$rec);
                    }
                }            
            }
            if ($access == "1"){
                $itsparent1 = $this->mnu->get_menu($idmenu)->row()->IDParent;
                if ($itsparent1 != 0){
                    $msg        = "and all its parent";
                    $itsparent2 = $this->mnu->get_menu($itsparent1)->row()->IDParent;
                    if ($itsparent2 != 0){
                        $wherep2 = array("IDUser" => $iduser, "IDMenu" => $itsparent2);
                        $this->uac->update_access($wherep2,$rec);
                    }
                    $wherep1 = array("IDUser" => $iduser, "IDMenu" => $itsparent1);
                    $this->uac->update_access($wherep1,$rec);
                }            
            }
                $this->uac->update_access($where1,$rec);
        }
        else if ($set == "1"){
            $child      = $this->mnu->get_child($idmenu)->result();
            if ($child != NULL){
                $msg    = "and all it's sub menu";
                foreach($child as $c){
                    $where2      = array("IDUser" => $iduser, "IDMenu" => $c->IDMenu);
                    $schild     = $this->mnu->get_child($c->IDMenu)->result();
                    if ($schild != NULL){
                        foreach($schild as $sc){
                            $where3      = array("IDUser" => $iduser, "IDMenu" => $sc->IDMenu);
                            $this->uac->update_access($where3,$rec);
                            $this->uac->update_btnaccess($where3,$rec);
                        }
                    }
                    $this->uac->update_access($where2,$rec);
                }
            }
            $this->uac->update_access($where1,$rec);
        }
        else if ($set == "2"){
            $msg    = "";
            $whereall   = array("IDUser" => $iduser);
            $this->uac->update_access($whereall,$rec,"all");
            $this->uac->update_btnaccess($whereall,$rec);
        }

        $rep        = array("status" => "oke" , "msg" => $msg);
        echo json_encode($rep);
    }
    function chg_accbtn(){
        $iduser     = $this->input->post("iduser");
        $idmenu     = $this->input->post("idmenu");
        $idbutton   = $this->input->post("idbutton");
        $access     = $this->input->post("access");
        $where      = array(
            "IDUser"    => $iduser,
            "IDMenu"    => $idmenu,
            "IDButton"  => $idbutton
        );
        $rec        = array("Access"    => $access);
        $nmuser     = $this->uac->get_user($iduser)->row()->FullName;
        $nmbutton   = $this->uac->get_button($idmenu,$idbutton)->row()->ButtonDesc;
        $this->uac->update_btnaccess($where,$rec);
        $rep        = array("status" => "oke" , "msg" => "", "nmuser" => $nmuser , "nmbutton" => $nmbutton);
        echo json_encode($rep);
    }
    function alluser(){
        $search     = $this->input->post('value');
        $find       = $this->input->post('find');
        if ($search == "alluser"){
            $users      = $this->uac->get_user()->result();
        }else{
            $users      = $this->uac->get_user($search,$find)->result();
        }
        echo json_encode($users);
    }
    function mass_process(){
        $menus      = $this->input->post("menus");
        $buttons    = $this->input->post("buttons");
        $users      = $this->input->post("users");
        if ($users != "all"){
            $user   = explode(",", $users);
            foreach ($user as $u){
                $menu   = explode(",", $menus);
                foreach ($menu as $mn){
                    $m  = explode("x", $mn);
                    $where  = array (
                        "IDUser"    => $u, // $u == iduser
                        "IDMenu"    => $m[0] // $m[0] == idmenu
                    );
                    $rec    = array (
                        "Access"    => $m[1] // $m[1] == menu access
                    );
                    $this->uac->update_access($where,$rec);
                }
                if ($buttons != "no"){
                    $button     = explode(",", $buttons);
                    foreach ($button as $bt){
                        $b      = explode("x", $bt);
                        $where  = array(
                            "IDUser"    => $u,      // $u == iduser
                            "IDMenu"    => $b[0],   // $b[0] == idmenu
                            "IDButton"  => $b[1]    // $b[1] == idbutton
                        );
                        $rec    = array(
                            "Access"    =>  $b[2]   // $b[2] == button access
                        );
                        $this->uac->update_btnaccess($where,$rec);
                    }
                }
            }
        }
        else if ($users == "all"){
            $users  = $this->uac->get_user()->result();
            foreach($users as $u){
                $menu   = explode(",", $menus);
                foreach ($menu as $mn){
                    $m  = explode("x", $mn);
                    $where  = array (
                        "IDUser"    => $u->IDEmployee, 
                        "IDMenu"    => $m[0] // $m[0] == idmenu
                    );
                    $rec    = array (
                        "Access"    => $m[1] // $m[1] == menu access
                    );
                    $this->uac->update_access($where,$rec);
                }
                if ($buttons != "no"){
                    $button     = explode(",", $buttons);
                    foreach ($button as $bt){
                        $b      = explode("x", $bt);
                        $where  = array(
                            "IDUser"    => $u->IDEmployee,      // $u == iduser
                            "IDMenu"    => $b[0],   // $b[0] == idmenu
                            "IDButton"  => $b[1]    // $b[1] == idbutton
                        );
                        $rec    = array(
                            "Access"    =>  $b[2]   // $b[2] == button access
                        );
                        $this->uac->update_btnaccess($where,$rec);
                    }
                }                
            }
        }
        $msg    = array("status" => "oke");
        echo json_encode($msg);
    }
}