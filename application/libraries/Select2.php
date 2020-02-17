<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Select2 {
	public $CI;
	public function __construct() {
		$this->CI = & get_instance();		
    }
    
    public function get_warehouse($branchId){
        $ssql = "select fin_warehouse_id as id,fst_warehouse_name as text,fst_delivery_address from mswarehouse where fin_branch_id = ? and fst_active ='A'";
        $qr = $this->CI->db->query($ssql,[$branchId]);
        $rs = $qr->result();
        return $rs;
    }

    public function get_discountList(){
        $ssql = "select fst_item_discount as id,fst_item_discount as text from msitemdiscounts where fst_active ='A'";
        $qr = $this->CI->db->query($ssql,[]);
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

    public function get_itemBySupplier($supplierId,$term = ""){
        $term = "%$term%";

        //$ssql = "select fin_item_id as id,concat(fst_item_code,' - ' ,fst_item_name) as text, fst_item_code,fst_item_name from msitems where (fin_standard_vendor_id = ?  or fin_optional_vendor_id = ?) and fst_active = 'A'";
        $ssql = "SELECT a.fin_item_id as id,concat(a.fst_item_code,' - ' ,a.fst_item_name) as text, a.fst_item_code,a.fst_item_name FROM msitems a 
        INNER JOIN msrelations b ON REPLACE(a.fst_linebusiness_id,',','|') REGEXP  REPLACE(b.fst_linebusiness_id,',','|')
        WHERE b.fin_relation_id = ? and a.fst_active ='A' and concat(a.fst_item_code,' - ' ,a.fst_item_name) like ?";
        
        $qr = $this->CI->db->query($ssql,[$supplierId,$term]);
        $rs = $qr->result();
        return $rs;
    }

    public function get_buyItemUnit($itemId){
        $ssql = "select fst_unit as id,fst_unit as text from msitemunitdetails where fin_item_id = ? and fbl_is_buying = true";
        $qr = $this->CI->db->query($ssql,[$itemId]);
        $rs = $qr->result();
        return $rs;
    }
}