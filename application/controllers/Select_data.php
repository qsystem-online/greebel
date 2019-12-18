<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Select_data extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->library("select2");

    }

    /** Block Master */
    /**  Get Shipping address by relation id */
    public function get_shipping_address($relationId){
        $ssql = "select * from msshippingaddress where fin_relation_id = ?";
        $qr = $this->db->query($ssql,[$relationId]);
        $rs = $qr->result();		
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = $rs;
        $this->json_output();
    }

    public function get_warehouse($branchId){
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = $this->select2->get_warehouse($branchId);
        $this->json_output();
    }

    public function get_customer($branchId){
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = $this->select2->get_customer($branchId);
        $this->json_output();

    }

    public function get_items_by_supplier(){
        $finSupplierId = $this->input->get("finSupplierId");
        $term = $this->input->get("term");

        /*
        $ssql = "select fin_item_id,fst_item_code,fst_item_name from msitems  where (fin_standard_vendor_id = ? OR fin_optional_vendor_id = ?) 
            and (fst_item_name like ? OR fst_item_code like ?)
            and fst_active ='A'";
        */

        $ssql = "select fin_item_id,fst_item_code,fst_item_name from msitems a 
        inner join msrelations b on replace(a.fst_linebusiness_id,',','|') REGEXP replace(b.fst_linebusiness_id,',','|')
        where b.fin_relation_id = ? and (a.fst_item_name like ? OR a.fst_item_code like ?) and a.fst_active ='A'";
        $qr = $this->db->query($ssql,[$finSupplierId, "%$term%","%$term%"]);
        //echo $this->db->last_query();

        //$qr = $this->db->query($ssql,[$finSupplierId,$finSupplierId,"%$term%","%$term%"]);

        $rs = $qr->result();		
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = $rs;
        $this->json_output();
        
    }

    public function get_items_by_customer(){
        $finCustomerId = $this->input->get("finCustomerId");
        $term = $this->input->get("term");

        $ssql = "select fin_item_id,fst_item_code,fst_item_name from msitems a 
        inner join msrelations b on replace(a.fst_linebusiness_id,',','|') REGEXP replace(b.fst_linebusiness_id,',','|')
        where b.fin_relation_id = ? and (a.fst_item_name like ? OR a.fst_item_code like ?) and a.fst_active ='A'";
        $qr = $this->db->query($ssql,[$finCustomerId, "%$term%","%$term%"]);
        //echo $this->db->last_query();

        //$qr = $this->db->query($ssql,[$finSupplierId,$finSupplierId,"%$term%","%$term%"]);

        $rs = $qr->result();		
        $this->ajxResp["status"] = "SUCCESS";
        $this->ajxResp["data"] = $rs;
        $this->json_output();
        
    }


}