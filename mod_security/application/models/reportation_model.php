<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reportation_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->dbr  = $this->load->database("public",TRUE);
        $this->dbe  = $this->load->database("empcenter",TRUE);
        $this->rpt  = "t01rootcause";
        $this->ref  = "r01rootcause";
        $this->emp  = "m01personal";
        $this->emp_job  = "m01personal_job";
        $this->par  = "m04param";
        $this->req_folacc = "t02request_accessfolder";
        $this->req_usragr = "t02request_agreement";
        $this->req_folcrt = "t02request_createfolder";
        $this->req_usrcrt = "t02request_createuser";
        $this->req_sofins = "t02request_installsoftware";
        $this->req_accusr = "t02request_user";
        
    }

    
     function countreport($param){
        $sql = " SELECT COUNT(StatusProblem) AS Nilai FROM $this->rpt WHERE  StatusProblem='$param' AND DeleteFlag ='A' ";
        return $this->dbr->query($sql)->row();
                
    }

   
    function getby_id_root($id){
        $this->dbr->where('ID',$id);
        $result = $this->dbr->get($this->rpt);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return NULL; 
        }
    }
 

    function get_accepted($iduser){
        $att    = "isib_public";
//        $emp    = "isib_employee";
        $this->datatables->select("A.ID AS Ref, E.FullName AS Name, A.AddedDate AS CompDate, A.ComplainNote AS CompNote, A.HoDConf AS Confirmation, A.HoDConfDate AS ConfDate");
        $this->datatables->from("$att.$this->rpt AS A");
        $this->datatables->join("$att.$this->emp AS E","A.AddedBy = E.IDEmployee");
        $this->datatables->where("A.HoDConfBy = '$iduser' AND A.DeleteFlag = 'A'");
        return $this->datatables->generate();
    }
    function get_upar($where=NULL){
        if ($where != NULL){
            $this->dbe->where($where);
        }
        return $this->dbe->get($this->par);        
    }
    function get_employee($wh){
        $this->dbr->where($wh);
        return $this->dbr->get($this->emp);
    }
    function get_report($wh){
        $this->dbr->where($wh);
        return $this->dbr->get($this->rpt);
    }
    function upd_report($wh,$rec){
        $this->dbr->where($wh);
        return $this->dbr->update($this->rpt,$rec);
    }
    function get_report_dtb($wh){
        $db  = "isib_public";
        $this->datatables->select("T.ID AS ID,T.IDRoot AS RType, R.RootName AS RName,T.HoDConf AS HODC, E.FullName AS EName,EP.FullName AS PICName, T.ComplainNote AS NComplaint, T.ProblemNote AS NProblem, T.AddedDate AS CDate, T.PIC AS PIC,T.IDLocation AS Location");
        $this->datatables->from("$db.".$this->rpt." AS T");
        $this->datatables->join("$db.".$this->ref." AS R","T.IDRoot    = R.IDRoot","LEFT");
        $this->datatables->join("$db.".$this->emp." AS E"," T.AddedBy  = E.IDEmployee","LEFT");
        $this->datatables->join("$db.".$this->emp." AS EP"," T.PIC     = EP.IDEmployee","LEFT");
        $this->datatables->where($wh);
        return $this->datatables->generate();
    }
    function get_hodconf($idhod){
        $query  = "
            SELECT R.*, E.FullName AS EmpName FROM $this->rpt R
                JOIN $this->emp E
                    ON R.AddedBy = E.IDEmployee
                WHERE R.DeleteFlag = 'A' AND R.HoDConf = '0' AND R.AddedBy IN (
                    SELECT IDEmployee FROM $this->emp_job WHERE IDEmployeeParent = '$idhod'
                )";
        return $this->dbr->query($query);
    }
    function get_itofcr($wh){
        $this->dbe->select("E.FullName AS EName, E.IDEmployee AS IDEmp");
        $this->dbe->from("$this->par AS P");
        $this->dbe->join("$this->emp AS E","P.ParamValue = E.IDEmployee");
        $this->dbe->where($wh);
        return $this->dbe->get();
    }
    function get_folacc($wh){
        $this->dbr->where($wh);
        return $this->dbr->get($this->req_folacc);
    }
    function get_folcrt($wh){
        $this->dbr->where($wh);
        return $this->dbr->get($this->req_folcrt);
    }
    function get_usrcrt($wh){
        $this->dbr->where($wh);
        return $this->dbr->get($this->req_usrcrt);
    }
    function get_sofins($wh){
        $this->dbr->where($wh);
        return $this->dbr->get($this->req_sofins);
    }
    function get_accusr($wh){
        $this->dbr->where($wh);
        return $this->dbr->get($this->req_accusr);
    }
}
