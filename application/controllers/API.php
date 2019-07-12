<?php
defined('BASEPATH') or exit('No direct script access allowed');
class API extends MY_Controller{
    public function getItemStock($fin_warehouse_id,$fin_item_id,$fst_unit){
        $this->load->model("Trinventory_model");
        $qtyStock = $this->Trinventory_model->getStock($fin_warehouse_id,$fin_item_id,$fst_unit);
        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $qtyStock;
		$this->json_output();
    }

    public function getItemMarketingStock($fin_warehouse_id,$fin_item_id,$fst_unit){
        $this->load->model("Trinventory_model");
        $qtyMarketingStock = $this->Trinventory_model->getMarketingStock($fin_warehouse_id,$fin_item_id,$fst_unit);
        $this->ajxResp["status"] = "SUCCESS";
		$this->ajxResp["data"] = $qtyMarketingStock;
		$this->json_output();
    }

}