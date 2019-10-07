<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_model extends CI_Model {
    //Get Approval List
    public function getTtlApproved(){
        $tbl = "";
        $ssql = "select count(*) as ttl_approve from trverification a
            inner join users b on a.fin_department_id = b.fin_department_id
            inner join users c on a.fin_user_group_id = c.fin_group_id
            where a.fin_rec_id = ? and a.fst_verification_status = 'RV'
            and b.fst_active = 'A'";
        $query = $this->db->query($ssql,$this->aauth->get_user_id());
        $row = $query->row();
        return $row->ttl_approve;
    }

    public function getTtlNeedApproval(){

    }

    public function getTtlChangeAfterApproved(){

    }

    public function getTtlVoidAuthorize(){

    }
}