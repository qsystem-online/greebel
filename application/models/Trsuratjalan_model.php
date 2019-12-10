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
            'label' => 'Sales Order ID',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];
        $rules[] = [
            'field' => 'fdt_sj_datetime',
            'label' => lang('Tgl Surat Jalan'),
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

    public function GenerateSJNo($trDate = null) {
        $trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
        $tahun = date("Y/m", strtotime ($trDate));
        $activeBranch = $this->aauth->get_active_branch();
        $branchCode = "";
        if($activeBranch){
            $branchCode = $activeBranch->fst_branch_code;
        }
        $prefix = getDbConfig("deliveryorder_prefix") . "/" . $branchCode ."/";
        $query = $this->db->query("SELECT MAX(fst_sj_no) as max_id FROM trsuratjalan where fst_sj_no like '".$prefix.$tahun."%'");
        $row = $query->row_array();

        $max_id = $row['max_id']; 
        
        $max_id1 =(int) substr($max_id,strlen($max_id)-5);
        
        $fst_tr_no = $max_id1 +1;
        
        $max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);
        
        return $max_tr_no;
    }

    public function getPendingDetailSO($salesOrderId){
        $this->load->model("msitems_model");

        $ssql = "select a.fin_rec_id as fin_salesorder_detail_id,a.fin_item_id,a.fst_custom_item_name,
            a.fst_unit,a.fin_promo_id,b.fbl_is_batch_number,b.fbl_is_serial_number,
            (a.fdb_qty - (a.fdb_qty_out + a.fdb_qty_return)) as fdb_qty,
            b.fst_item_code,b.fst_item_name
            from trsalesorderdetails a
            inner join msitems b on a.fin_item_id = b.fin_item_id
            where fin_salesorder_id = ? and fdb_qty > (fdb_qty_out + fdb_qty_return)";
        $qr = $this->db->query($ssql,[$salesOrderId]);
        
        $rs = $qr->result();

        for($i = 0;$i < sizeof($rs); $i++){
            $rw = $rs[$i];
            $fstBasicUnit = $this->msitems_model->getBasicUnit($rw->fin_item_id);
            $rw->fst_basic_unit = $fstBasicUnit;
            $rw->fdc_conv_to_basic_unit = $this->msitems_model->getQtyConvertToBasicUnit($rw->fin_item_id,1,$rw->fst_unit);            
            $rs[$i] =  $rw;
        }
        return $rs;
    }
    

    public function maxQtyItem($salesorderDetailId){
        $ssql = "select * from trsalesorderdetails where fin_rec_id = ?";
        $qr = $this->db->query($ssql,[$salesorderDetailId]);
        $rw = $qr->row();

        if($rw == null){
            return 0;
        }else{
            return (float) $rw->fdb_qty  - ((float) $rw->fdb_qty_out  + (float) $rw->fdb_qty_return);
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
        $this->load->model("msitems_model");

        $ssql = "select * from trsuratjalan where fin_sj_id = ? and fst_active != 'D'";
        $qr = $this->db->query($ssql,[$sjId]);
        $dataH = $qr->row();

        if ($dataH == null){
            throw new CustomException(lang("ID surat jalan tidak dikenal !"),3003,"FAILED",null);
        }

        $ssql = "SELECT * FROM trsuratjalandetails WHERE fin_sj_id = ?";
        $qr = $this->db->query($ssql,[$sjId]);
        $detailList = $qr->result();

        foreach($detailList as $dataD){
            //Update msitemdetails dan msitemdetailssummary
            //$strArrSerial  = $dataD["fst_serial_number_list"];                       
            $dataSerial = [
                "fin_warehouse_id"=>$dataH->fin_warehouse_id,
                "fin_item_id"=>$dataD->fin_item_id,
                "fst_unit"=>$dataD->fst_unit,
                "fst_serial_number_list"=>$dataD->fst_serial_number_list,
                "fst_batch_no"=>$dataD->fst_batch_number,
                "fst_trans_type"=>"PPJ", 
                "fin_trans_id"=>$dataH->fin_sj_id,
                "fst_trans_no"=>$dataH->fst_sj_no,
                "fin_trans_detail_id"=>$dataD->fin_rec_id,
                "fdb_qty"=>$dataD->fdb_qty,
                "in_out"=>"OUT",
            ];            
            $this->trinventory_model->insertSerial($dataSerial);
            
            //Update kartu stock
            $data = [
                "fin_warehouse_id"=>$dataH->fin_warehouse_id,
                "fdt_trx_datetime"=>$dataH->fdt_sj_datetime,
                "fst_trx_code"=>"DO",
                "fin_trx_id"=>$sjId,
                "fst_trx_no"=>$dataH->fst_sj_no,
                "fin_trx_detail_id"=>$dataD->fin_rec_id,
                "fst_referensi"=>$dataH->fst_sj_memo,
                "fin_item_id"=>$dataD->fin_item_id,
                "fst_unit"=>$dataD->fst_unit,
                "fdb_qty_in"=>0,
                "fdb_qty_out"=>$dataD->fdb_qty,
                "fdc_price_in"=>0,
                "fst_active"=>"A"
            ];
            $this->trinventory_model->insert($data);

            //Update data SO detail 
            $ssql = "UPDATE trsalesorderdetails SET fdb_qty_out = fdb_qty_out +  ? WHERE fin_rec_id = ?";
            $query = $this->db->query($ssql,[$dataD->fdb_qty,$dataD->fin_salesorder_detail_id]);
            throwIfDBError();
        }

        //Cek All Data valid after Process
        //Data SO detail  still valid
        $ssql = "SELECT * FROM trsalesorderdetails WHERE fin_salesorder_id = ? AND fdb_qty < (fdb_qty_out + fdb_qty_return)";
        $qr = $this->db->query($ssql,$dataH->fin_salesorder_id);
        $rw = $qr->row();
        if ($rw != null){
            throw new CustomException(lang("Qty sales order detail not balance !"),3003,"FAILED",null);            
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
            "fdt_unhold_datetime" => date("Y-m-d H:i:s")
        ];
        parent::update($data);
    }

}
