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
}