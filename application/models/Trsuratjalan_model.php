<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Suratjalan {
    private $CI;
    private $rw;
    private $db;

    public function __construct($CI,$sjId){
        $this->CI = $CI;
        $this->db = $CI->db;

        $ssql = "select * from trsuratjalan where fin_sj_id = ?";
        $qr = $this->CI->db->query($ssql,[$sjId]);
        $this->rw = $qr->row();
        if ($this->rw == false){
            throw new Exception("Invalid ID");
        }
    }
    public function __debugInfo() {
        //support on php 5.6
        return [
            'rw' => $this->rw
        ];
    }
    public function __get($name){
        if (property_exists($this->rw,$name)){
            return $this->rw->$name;
        }else{
            throw new Exception("Invalid Property Name !");
        }
    }
    
}

class Trsuratjalan_model extends MY_Model {
    public $tableName = "trsuratjalan";
    public $pkey = "fin_sj_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fin_salesorder_id',
            'label' => 'Sales Order No',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];
        $rules[] = [
            'field' => 'fdt_sj_date',
            'label' => 'Tgl Surat Jalan',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];
        $rules[] = [
            'field' => 'fin_warehouse_id',
            'label' => 'Warehouse',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];
        $rules[] = [
            'field' => 'fin_driver_id',
            'label' => 'Driver',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];
        $rules[] = [
            'field' => 'fst_no_polisi',
            'label' => lang('No Polisi'),
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];
        



        return $rules;
    }

    public function getDataById($fin_sj_id){
        $ssql = "select a.*,
            b.fdt_salesorder_date,b.fst_salesorder_no,
            c.fin_relation_id,c.fst_relation_name,a.fin_shipping_address_id
            from trsuratjalan a
            inner join trsalesorder b on a.fin_salesorder_id = b.fin_salesorder_id
            inner join msrelations c on b.fin_relation_id  = c.fin_relation_id 
            where a.fin_sj_id = ?";
        $qr = $this->db->query($ssql, [$fin_sj_id]);
        $rwSJ = $qr->row();

        $ssql = "select a.*,c.fst_item_code,b.fin_promo_id,b.fst_custom_item_name from trsuratjalandetails a 
        inner join trsalesorderdetails b on a.fin_salesorder_detail_id = b.fin_rec_id 
        inner join msitems c on b.fin_item_id = c.fin_item_id  
        where a.fin_sj_id = ?";
		$qr = $this->db->query($ssql,[$fin_sj_id]);
		$rsSJDetails = $qr->result();

		$data = [
            "sj" => $rwSJ,
            "sj_details" => $rsSJDetails
		];

		return $data;
    }

    public function GenerateSJNo($soDate = null) {
        $soDate = ($soDate == null) ? date ("Y-m-d"): $soDate;
        $tahun = date("ym", strtotime ($soDate));
        $prefix = getDbConfig("deliveryorder_prefix");
        $query = $this->db->query("SELECT MAX(fst_sj_no) as max_id FROM trsuratjalan where fst_sj_no like '".$prefix.$tahun."%'");
        $row = $query->row_array();
        $max_id = $row['max_id']; 
        $max_id1 =(int) substr($max_id,8,5);
        $fst_salesorder_no = $max_id1 +1;
        $max_salesorder_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_salesorder_no);
        return $max_salesorder_no;
    }

    public function getPendingDetailSO($salesOrderId){
        $ssql = "select a.fin_rec_id as fin_salesorder_detail_id,a.fin_item_id,a.fst_custom_item_name,
            a.fst_unit,a.fin_promo_id,
            (a.fdb_qty - a.fdb_qty_out) as fdb_qty,
            b.fst_item_code,b.fst_item_name
            from trsalesorderdetails a
            inner join msitems b on a.fin_item_id = b.fin_item_id
            where fin_salesorder_id = ? and fdb_qty > fdb_qty_out";
        $qr = $this->db->query($ssql,[$salesOrderId]);
        return $qr->result();
    }
    

    public function maxQtyItem($salesorderDetailId,$sjId = 0){
        $ssql = "select * from trsalesorderdetails where fin_rec_id = ?";
        $qr = $this->db->query($ssql,[$salesorderDetailId]);
        $rw = $qr->row();

        $currentQty = 0;
        if ($sjId != 0){
            $ssql = "select sum(fdb_qty) as fdb_qty from trsuratjalandetails where fin_sj_id = ? and fin_salesorder_detail_id = ?";
            $qr = $this->db->query($ssql,[$sjId,$salesorderDetailId]);
            $rwSJ = $qr->row();
            if($rwSJ){
                $currentQty = $rwSJ->fdb_qty;
            }
        }

        if(!$rw){
            return 0;
        }else{
            return (float) $rw->fdb_qty  - (float) $rw->fdb_qty_out + $currentQty;
        }

    }

    public function unposting($sjId){
        $this->load->model("trinventory_model");   
        
        //update Sales Order
        $ssql = "select a.*,b.fin_warehouse_id from trsuratjalandetails a 
            inner join trsuratjalan b on a.fin_sj_id = b.fin_sj_id 
            where a.fin_sj_id = ?";

        $qr = $this->db->query($ssql,[$sjId]);
        $rs = $qr->result();
        if(!$rs){
            return false;
        }
        
        $this->trinventory_model->deleteByCodeId("DO",$sjId);

        foreach($rs as $rw){
            $finSalesorderDetailId = $rw->fin_salesorder_detail_id;
            $ssql = "update trsalesorderdetails set fdb_qty_out = fdb_qty_out -  " . $rw->fdb_qty  ." where fin_rec_id = ?";
            $query = $this->db->query($ssql,[$finSalesorderDetailId]);                  
        }

    }

    public function posting($sjId){
        $this->load->model("trinventory_model");        
    
        $ssql = "select a.*,b.fin_warehouse_id,b.fdt_sj_date from trsuratjalandetails a 
            inner join trsuratjalan b on a.fin_sj_id = b.fin_sj_id 
            where a.fin_sj_id = ?";

        $qr = $this->db->query($ssql,[$sjId]);
        $rs = $qr->result();
        if(!$rs){
            return false;
        }
        foreach($rs as $rw){
            //update Sales Order
            $finSalesorderDetailId = $rw->fin_salesorder_detail_id;
            $ssql = "update trsalesorderdetails set fdb_qty_out = fdb_qty_out +  " . $rw->fdb_qty  ." where fin_rec_id = ?";
            $query = $this->db->query($ssql,[$finSalesorderDetailId]);               

            // Update Kartu Stock
            $data = [
                "fin_warehouse_id"=>$rw->fin_warehouse_id,
                "fdt_trx_datetime"=>$rw->fdt_sj_date,
                "fst_trx_code"=>"DO",
                "fin_trx_id"=>$sjId,
                "fst_referensi"=>null,
                "fin_item_id"=>$rw->fin_item_id,
                "fst_unit"=>$rw->fst_unit,
                "fdb_qty_in"=>0,
                "fdb_qty_out"=>$rw->fdb_qty,
                "fdc_price_in"=>0,
                "fst_active"=>"A"
            ];
            $this->trinventory_model->insert($data);
        }
    }

    public function createObject($sjId){
        $ci = & get_instance();
        try{
            $suratJalan = new SuratJalan($ci,$sjId);
            return $suratJalan;
        }catch(Exception $e){
            return null;
        }        
    }

    //===== MONITORING 02/08/2019 enny06 ==========\\
    public function unhold($sjId){
        
        $activeUser = $this->aauth->user();
        //print_r($activeUser);
    
        $data = [
            "fin_sj_id" => $sjId,
            "fbl_is_hold" => "0", //Unhold Success
            "fin_unhold_id" => $activeUser->fin_user_id,
            //"fdt_unhold_datetime" => dBDateFormat("fdt_unhold_datetime")
            "fdt_unhold_datetime" => date("Y-m-d H:i:s")
        ];
        parent::update($data);
       
    }

    /*public function update($fin_sj_id){

        $activeUser = $this->aauth->user();
        $data = [
            "fin_sj_id" => $fin_sj_id,
            "fin_sj_return_by_id" => $activeUser->fin_user_id,
            "fdt_sj_return_datetime" => date("Y-m-d H:i:s"),
            "fst_sj_return_resi_no" => $this->input->post("fst_sj_return_resi_no"),
            "fst_sj_return_memo" => $this->input->post("fst_sj_return_memo"),
        ];
        parent::update($data);
    }*/

}
