<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Api extends MY_Controller{
    public function get_item_stock($fin_warehouse_id,$fin_item_id,$fst_unit){
        $this->load->model("trinventory_model");
        $qtyStock = $this->trinventory_model->getStock($fin_warehouse_id,$fin_item_id,$fst_unit);
        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $qtyStock;
		$this->json_output();
    }

    public function get_item_marketing_stock($fin_warehouse_id,$fin_item_id,$fst_unit){
        $this->load->model("trinventory_model");
        $qtyMarketingStock = $this->trinventory_model->getMarketingStock($fin_warehouse_id,$fin_item_id,$fst_unit);
        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $qtyMarketingStock;
		$this->json_output();
    }

    public function get_info_stock($fin_warehouse_id,$fin_item_id,$fst_unit){
        $this->load->model("trinventory_model");
        $qtyRealStock = $this->trinventory_model->getStock($fin_warehouse_id,$fin_item_id,$fst_unit);        
        $qtyMarketingStock = $this->trinventory_model->getMarketingStock($fin_warehouse_id,$fin_item_id,$fst_unit);
        
        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = [
            "real_stock"=> $qtyRealStock,
            "marketin_stock" => $qtyMarketingStock
        ];
		$this->json_output();
    }


}