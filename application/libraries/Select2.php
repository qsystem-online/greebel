<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Select2 {
	public $CI;
	public function __construct() {
		$this->CI = & get_instance();		
    }
    
    public function get_warehouse($branchId){
        $ssql = "select fin_warehouse_id as id,fst_warehouse_name as text from mswarehouse where fin_branch_id = ? and fst_active ='A'";
        $qr = $this->CI->db->query($ssql,[$branchId]);
        $rs = $qr->result();
        return $rs;
    }

	public function get_customer($branchId){
        $ssql = "select fin_relation_id as id,fst_relation_name as text from msrelations where fin_branch_id = ? and fst_active ='A'";
        $qr = $this->CI->db->query($ssql,[$branchId]);
        $rs = $qr->result();
        return $rs;
    }

    public function get_supplier($branchId){
        $ssql = "select fin_relation_id as id,fst_relation_name as text from msrelations where fin_branch_id = ? and FIND_IN_SET(1,fst_relation_type) and fst_active ='A'";
        $qr = $this->CI->db->query($ssql,[$branchId]);
        $rs = $qr->result();
        return $rs;
    }
}