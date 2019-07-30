<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Select2 {
	public $CI;
	public function __construct() {
		$this->CI = & get_instance();		
    }
    
    public function get_warehouse($branchId){
        $ssql = "select fin_warehouse_id,fst_warehouse_name from mswarehouse where fin_branch_id = ? and fst_active ='A'";
        $qr = $this->db->query($ssql,[$branchId]);
        $rs = $qr->result();
        return $rs;
    }

	
}