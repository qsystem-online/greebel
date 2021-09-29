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
        $user = $this->aauth->user();
        if($user->fin_group_id =='1'){
            $ssql = "select count(*) as ttl_need_approval from trverification where fst_verification_status = 'RV'";
            $qr = $this->db->query($ssql,[]);
        }else{
            $ssql = "select count(*) as ttl_need_approval from trverification where fst_verification_status = 'RV' and fin_department_id =? and fin_user_group_id = ?";
            $qr = $this->db->query($ssql,[$user->fin_department_id,$user->fin_group_id]);
        }
        $rw = $qr->row();
        return $rw->ttl_need_approval;

    }

    public function getTtlChangeAfterApproved(){

    }

    public function getTtlVoidAuthorize(){

    }
}