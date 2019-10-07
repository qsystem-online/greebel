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


    public function get_value1($model,$function,$params){

        $model = strtolower($model);
        $this->load->model(ucfirst($model),'model');

        //$result = $this->model->getRate("USD");
        $params = explode(",",$params);

        $result = call_user_func_array(array($this->model, $function), $params);
        $resp =[
            "status"=>"success",
            "message"=>"",
            "data"=>$result
        ];
        $this->json_output($resp);


    }
    public function get_value(){

        $model = strtolower($this->input->post("model"));
        $function = $this->input->post("function");
        $params = $this->input->post("params");
        

        $this->load->model(ucfirst($model),'model');

        //$result = $this->model->getRate("USD");
        //$params = explode(",",$params);

        $result = call_user_func_array(array($this->model, $function), $params);
        $resp =[
            "status"=>"success",
            "message"=>"",
            "data"=>$result
        ];
        $this->json_output($resp);


    }
}