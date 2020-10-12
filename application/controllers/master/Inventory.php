<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Inventory extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('trinventory_model');
        
    }

    public function ajxGetBatchNoList(){        
        $finWarehouseId=$this->input->get("fin_warehouse_id");
        $finItemId =$this->input->get("fin_item_id");

        $result = $this->trinventory_model->getReadyBatchNoList($finWarehouseId,$finItemId);
        $this->json_output([
            "status"=>"SUCCESS",
            "messages"=>"",
            "data"=>$result
        ]);

    }

    public function ajxGetSerialNoList(){
        $finWarehouseId=$this->input->get("fin_warehouse_id");
        $finItemId =$this->input->get("fin_item_id");
        $fstBatchNo =$this->input->get("fst_batch_no");
        $result = $this->trinventory_model->getReadySerialNoList($finWarehouseId,$finItemId,$fstBatchNo);
        $this->json_output([
            "status"=>"SUCCESS",
            "messages"=>"",
            "data"=>$result
        ]);

    }
    

}