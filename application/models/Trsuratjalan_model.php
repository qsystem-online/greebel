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
			'field' => 'fin_trans_id',
			'label' => 'Transaction ID',
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

		return $rules;
	}

	public function getDataById($fin_sj_id){
		$ssql = "SELECT a.*,
			IFNULL(IFNULL(b.fst_salesorder_no,c.fst_purchasereturn_no),g.fst_assembling_no) as fst_trans_no ,
			IFNULL(IFNULL(b.fdt_salesorder_datetime,c.fdt_purchasereturn_datetime),g.fdt_assembling_datetime) as fdt_trans_datetime,            
			d.fin_relation_id,d.fst_relation_name,e.fst_name as fst_shipping_name,e.fst_shipping_address,
			f.fst_warehouse_name 
			FROM trsuratjalan a
			LEFT JOIN trsalesorder b on a.fin_trans_id = b.fin_salesorder_id and a.fst_sj_type = 'SO' 
			LEFT JOIN trpurchasereturn c on a.fin_trans_id = c.fin_purchasereturn_id and a.fst_sj_type = 'PO_RETURN' 
			LEFT JOIN trassembling g on a.fin_trans_id = g.fin_assembling_id and a.fst_sj_type = 'ASSEMBLING_OUT'  
			LEFT JOIN msrelations d on IFNULL(b.fin_relation_id,c.fin_supplier_id)  = d.fin_relation_id 
			LEFT JOIN msshippingaddress e on a.fin_shipping_address_id = e.fin_shipping_address_id 
			INNER JOIN mswarehouse f on a.fin_warehouse_id = f.fin_warehouse_id 
			where a.fin_sj_id = ? and a.fst_active !='D' ";

		$qr = $this->db->query($ssql, [$fin_sj_id]);      
		
		throwIfDBError();  
		$rwSJ = $qr->row();


		if ($rwSJ->fst_sj_type == "SO"){

			$ssql = "SELECT a.*,
				b.fin_promo_id,
				b.fst_custom_item_name,
				c.fbl_is_batch_number,c.fbl_is_serial_number,c.fst_item_code,c.fst_item_name,
				d.fst_unit as fst_basic_unit,d.fdc_conv_to_basic_unit 
				FROM trsuratjalandetails a 
				INNER JOIN trsalesorderdetails b on a.fin_trans_detail_id = b.fin_rec_id 
				INNER JOIN msitems c on b.fin_item_id = c.fin_item_id  
				LEFT JOIN msitemunitdetails d on c.fin_item_id = d.fin_item_id and d.fbl_is_basic_unit = 1
				WHERE a.fin_sj_id = ?";



		}else if ($rwSJ->fst_sj_type == "PO_RETURN"){
			$ssql = "SELECT a.*,
				0 as fin_promo_id,b.fst_custom_item_name,
				c.fbl_is_batch_number,c.fbl_is_serial_number,c.fst_item_code,c.fst_item_name,
				d.fst_unit as fst_basic_unit,d.fdc_conv_to_basic_unit,
				FROM trsuratjalandetails a 
				INNER JOIN trpurchasereturnitems b on a.fin_trans_detail_id = b.fin_rec_id 
				INNER JOIN msitems c on b.fin_item_id = c.fin_item_id  
				LEFT JOIN msitemunitdetails d on c.fin_item_id = d.fin_item_id and d.fbl_is_basic_unit = 1
				WHERE a.fin_sj_id = ?";

		}else if ($rwSJ->fst_sj_type == "ASSEMBLING_OUT"){
			$ssql = "SELECT a.*,
				0 as fin_promo_id,c.fst_item_name as fst_custom_item_name,
				c.fbl_is_batch_number,c.fbl_is_serial_number,c.fst_item_code,c.fst_item_name,
				d.fst_unit as fst_basic_unit,d.fdc_conv_to_basic_unit 
				FROM trsuratjalandetails a 
				INNER JOIN msitems c on a.fin_item_id = c.fin_item_id  
				LEFT JOIN msitemunitdetails d on c.fin_item_id = d.fin_item_id and d.fbl_is_basic_unit = 1
				WHERE a.fin_sj_id = ?";

		}else{
			return [
				"sj" => $rwSJ,
				"sj_details" => []
			];
		}

		$qr = $this->db->query($ssql,[$fin_sj_id]);
		$rsSJDetails = $qr->result();
		
		$data = [
			"sj" => $rwSJ,
			"sj_details" => $rsSJDetails
		];

		return $data;
	}

	public function getDataHeaderById($finSJId){
		$ssql ="SELECT * FROM trsuratjalan where fin_sj_id =? and fst_active != 'D'";
		$qr = $this->db->query($ssql,[$finSJId]);
		return $qr->row();
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

	public function maxQtyItem($sjType,$transDetailId){
		switch($sjType){
			case "SO":
				$ssql = "select * from trsalesorderdetails where fin_rec_id = ?";
				$qr = $this->db->query($ssql,[$transDetailId]);
				$rw = $qr->row();

				if($rw == null){
					return 0;
				}else{
					return (float) $rw->fdb_qty  - ((float) $rw->fdb_qty_out  + (float) $rw->fdb_qty_return);
				}

				break;
			case "PO_RETURN":
				$ssql = "select * from trpurchasereturnitems where fin_rec_id = ?";
				$qr = $this->db->query($ssql,[$transDetailId]);
				$rw = $qr->row();

				if($rw == null){
					return 0;
				}else{
					return (float) $rw->fdb_qty  - (float) $rw->fdb_qty_out;
				}

				break;
			case "ASSEMBLING_OUT":
				//NO Partial no need check
				return 999999999;
			default:
				throw new CustomException("Invalid SJ Type",3003,"FAILED",["fst_sj_type"=>$sjType]);

		}
		
		

	}

	public function unposting($sjId){
		/**
		 * Cancel kartu stock
		 * Cancel serial no
		 * cancel qty out SO detail
		 * update if salesorder status closed
		 */
		
		$this->load->model("trinventory_model");   
		

		$ssql = "select * from trsuratjalan where fin_sj_id = ? and fst_active != 'D'";
		$qr = $this->db->query($ssql,[$sjId]);
		$dataH = $qr->row();        
		if ($dataH == null){
			throw new CustomException(lang("ID Surat Jalan tidak dikenal !"),3003,"FAILED",null);
		}
		
		$ssql = "select * from trsuratjalandetails where fin_sj_id = ?";
		$qr = $this->db->query($ssql,[$sjId]);
		$dataDetails = $qr->result();
		
		if ($dataH->fst_sj_type == "SO"){
			$this->unpostingSOType($dataH,$dataDetails);
		
		}else if ($dataH->fst_sj_type == "PO_RETURN"){

			$this->unpostingPOReturnType($dataH,$dataDetails);

		}else if ($dataH->fst_sj_type == "ASSEMBLING_OUT"){
			$this->unpostingAssemblingType($dataH,$dataDetails);
		}else{
			throw new CustomException("Invalid SJ Type :$dataH->fst_sj_type",3003,"FAILED",NULL);
		}

		
	}

	private function unpostingSOType($dataH,$dataDetails){
		$this->load->model("trsalesorder_model");

		//Cancel kartu stock
		$this->trinventory_model->deleteByCodeId("DO",$dataH->fin_sj_id);

		//Cancel serial no
		$this->trinventory_model->deleteInsertSerial("PPJ",$dataH->fin_sj_id);                

		foreach($dataDetails as $dataD){
			$finSalesorderDetailId = $dataD->fin_trans_detail_id;
			$ssql = "update trsalesorderdetails set fdb_qty_out = fdb_qty_out -  " . $dataD->fdb_qty  ." where fin_rec_id = ?";
			$query = $this->db->query($ssql,[$finSalesorderDetailId]);                  
		}            
		$this->trsalesorder_model->updateClosedStatus($dataH->fin_trans_id);
	}

	private function unpostingPOReturnType($dataH,$dataDetails){
		$this->load->model("trpurchasereturn_model");        
		//Cancel kartu stock
		$this->trinventory_model->deleteByCodeId("PRT",$dataH->fin_sj_id);

		//Cancel serial no
		$this->trinventory_model->deleteInsertSerial("RPB",$dataH->fin_sj_id);                

		foreach($dataDetails as $dataD){
			$finPurchaseReturnDetailId = $dataD->fin_trans_detail_id;
			$ssql = "update trpurchasereturnitems set fdb_qty_out = fdb_qty_out -  " . $dataD->fdb_qty  ." where fin_rec_id = ?";
			$query = $this->db->query($ssql,[$finPurchaseReturnDetailId]);                  
		}           

		$this->trpurchasereturn_model->updateClosedStatus($dataH->fin_trans_id);

	}

	private function unpostingAssemblingType($dataH,$dataDetails){
		//Cancel kartu stock
		$this->trinventory_model->deleteByCodeId("ASO",$dataH->fin_sj_id);

		//Cancel serial no
		$this->trinventory_model->deleteInsertSerial("ASO",$dataH->fin_sj_id);                

		$ssql ="UPDATE trassembling set fin_sj_id = null where fin_assembling_id = ?";
		$query = $this->db->query($ssql,[$dataH->fin_trans_id]);		           
		//$this->trpurchasereturn_model->updateClosedStatus($dataH->fin_trans_id);
	}

	public function posting($sjId){
		$this->load->model("trinventory_model");  
		$this->load->model("msitems_model");
		$this->load->model("trsalesorder_model");   

		$ssql = "select * from trsuratjalan where fin_sj_id = ? and fst_active != 'D'";
		$qr = $this->db->query($ssql,[$sjId]);
		$dataH = $qr->row();

		if ($dataH == null){
			throw new CustomException(lang("ID surat jalan tidak dikenal !"),3003,"FAILED",null);
		}

		$ssql = "SELECT * FROM trsuratjalandetails WHERE fin_sj_id = ?";
		$qr = $this->db->query($ssql,[$sjId]);
		$dataDetails = $qr->result();

		if($dataH->fst_sj_type == "SO"){
			$this->postingSOType($dataH,$dataDetails);
		}else if ($dataH->fst_sj_type == "PO_RETURN"){
			$this->postingPOReturnType($dataH,$dataDetails);
		}else if ($dataH->fst_sj_type == "ASSEMBLING_OUT"){
			$this->postingAssemblingType($dataH,$dataDetails);		
		}else{
			throw new CustomException("Invalid SJ Type",3003,"FAILED",[$dataH]);
		}
		

	}
	
	private function postingSOType($dataH,$dataDetails){

		if (getDbConfig("update_stock_on_delivery") == 0){
			$this->updateInventorySOType($dataH->fin_sj_id);
		}

		foreach($dataDetails as $dataD){			
			//Update data SO detail 
			$ssql = "UPDATE trsalesorderdetails SET fdb_qty_out = fdb_qty_out +  ? WHERE fin_rec_id = ?";
			$query = $this->db->query($ssql,[$dataD->fdb_qty,$dataD->fin_trans_detail_id]);
			throwIfDBError();
		}

		//Cek All Data valid after Process
		$this->trsalesorder_model->updateClosedStatus($dataH->fin_trans_id);

		//Data SO detail  still valid
		$ssql = "SELECT * FROM trsalesorderdetails WHERE fin_salesorder_id = ? AND fdb_qty < (fdb_qty_out + fdb_qty_return)";
		$qr = $this->db->query($ssql,$dataH->fin_trans_id);
		$rw = $qr->row();

		if ($rw != null){
			throw new CustomException(lang("Qty sales order detail not balance !"),3003,"FAILED",null);            
		}
	}

	public function updateInventorySOType($sjId){
		$ssql = "select * from trsuratjalan where fin_sj_id = ? and fst_active != 'D'";
		$qr = $this->db->query($ssql,[$sjId]);
		$dataH = $qr->row();
		if ($dataH == null){
			throw new CustomException(lang("invalid SJ id"),404,"FAILED",[]);	
		}else{
			if ($dataH->fbl_update_stock == 1){
				return;
			}
		}


		$ssql = "SELECT * FROM trsuratjalandetails WHERE fin_sj_id = ?";
		$qr = $this->db->query($ssql,[$sjId]);
		$dataDetails = $qr->result();

		foreach($dataDetails as $dataD){
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
				"fin_trx_id"=>$dataH->fin_sj_id,
				"fst_trx_no"=>$dataH->fst_sj_no,
				"fin_trx_detail_id"=>$dataD->fin_rec_id,
				"fst_referensi"=>$dataH->fst_sj_memo,
				"fin_item_id"=>$dataD->fin_item_id,
				"fst_unit"=>$dataD->fst_unit,
				"fdb_qty_in"=>0,
				"fdb_qty_out"=>$dataD->fdb_qty,
				"fdc_price_in"=>0,
				"fbl_price_in_auto"=>false,
				"fst_active"=>"A"
			];
			$this->trinventory_model->insert($data);			
			throwIfDBError();
		}

		$ssql ="UPDATE trsuratjalan set fbl_update_stock = 1,fdt_delivery_datetime = now() where fin_sj_id = ?";
		$this->db->query($ssql,[$sjId]);

	}
	
	private function postingPOReturnType($dataH,$dataDetails){
		$this->load->model("trpurchasereturn_model");

		if (getDbConfig("update_stock_on_delivery") == 0){
			$this->updateInventoryPOReturnType($dataH->fin_sj_id);
		}

		foreach($dataDetails as $dataD){
			//Update data SO detail 
			$ssql = "UPDATE trpurchasereturnitems SET fdb_qty_out = fdb_qty_out +  ? WHERE fin_rec_id = ?";
			$query = $this->db->query($ssql,[$dataD->fdb_qty,$dataD->fin_trans_detail_id]);
			throwIfDBError();
		}
		
		//Cek All Data valid after Process
		$this->trpurchasereturn_model->updateClosedStatus($dataH->fin_trans_id);

		//Data PURCHASE RETURN detail  still valid
		$ssql = "SELECT * FROM trpurchasereturnitems WHERE fin_purchasereturn_id = ? AND fdb_qty < fdb_qty_out";
		$qr = $this->db->query($ssql,$dataH->fin_trans_id);
		$rw = $qr->row();
		if ($rw != null){
			throw new CustomException(lang("Qty Purchase return detail not balance !"),3003,"FAILED",null);            
		}    
	}

	public function updateInventoryPOReturnType($sjId){
		$ssql = "select * from trsuratjalan where fin_sj_id = ? and fst_active != 'D'";
		$qr = $this->db->query($ssql,[$sjId]);
		$dataH = $qr->row();
		if ($dataH == null){
			throw new CustomException(lang("invalid SJ id"),404,"FAILED",[]);	
		}else{
			if ($dataH->fbl_update_stock == 1){
				return;
			}
		}

		$ssql = "SELECT * FROM trsuratjalandetails WHERE fin_sj_id = ?";
		$qr = $this->db->query($ssql,[$sjId]);
		$dataDetails = $qr->result();
		foreach($dataDetails as $dataD){
			$dataSerial = [
				"fin_warehouse_id"=>$dataH->fin_warehouse_id,
				"fin_item_id"=>$dataD->fin_item_id,
				"fst_unit"=>$dataD->fst_unit,
				"fst_serial_number_list"=>$dataD->fst_serial_number_list,
				"fst_batch_no"=>$dataD->fst_batch_number,
				"fst_trans_type"=>"RPB", 
				"fin_trans_id"=>$dataH->fin_sj_id,
				"fst_trans_no"=>$dataH->fst_sj_no,
				"fin_trans_detail_id"=>$dataD->fin_rec_id,
				"fdb_qty"=>$dataD->fdb_qty,
				"in_out"=>"OUT",
			];            
			$this->trinventory_model->insertSerial($dataSerial);


			$ssql = "SELECT * FROM trpurchasereturn where fin_purchasereturn_id = ? and fst_active ='A'";
			$qr = $this->db->query($ssql,[$dataH->fin_trans_id]);
			$rwReturn = $qr->row();
			if ($rwReturn == null){
				throw new CustomException("Invalid return id!",3003,"FAILED",$dataD);                
			}
			$priceIn =0;
			if ($rwReturn->fbl_non_faktur == 0){			
				//GET PRICE WAKTU BELI ACUANNYA DARI NILAI FAKTUR
				$ssql = "SELECT * from trpurchasereturn a
					INNER JOIN trlpbpurchaseitems b on a.fin_lpbpurchase_id = b.fin_lpbpurchase_id and  b.fin_item_id = ? 
					WHERE a.fin_purchasereturn_id = ?";
				$qr = $this->db->query($ssql,[$dataD->fin_item_id,$dataH->fin_trans_id]);
				throwIfDBError();
				$lpbPurchaseItem = $qr->row();
				if ($lpbPurchaseItem == null){
					throw new CustomException("LPB Purchase Item not found !",3003,"FAILED",$dataD);                
				}
				$priceIn = (float) $lpbPurchaseItem->fdc_price - (float) $lpbPurchaseItem->fdc_disc_amount_per_item;
			}else{
				//GET PRICE WAKTU BELI ACUANNYA DARI NILAI PADA RETURN
				$ssql = "SELECT * from trpurchasereturnitems WHERE fin_rec_id = ?";
				$qr = $this->db->query($ssql,[$dataD->fin_trans_detail_id]);
				throwIfDBError();
				$returnItem = $qr->row();
				if ($returnItem == null){
					throw new CustomException("Non faktur return items not found!",3003,"FAILED",$dataD);
				}

				$priceIn = (float) $returnItem->fdc_price - (float) $returnItem->fdc_disc_amount_per_item;
			}

			$dataStock = [
				"fin_warehouse_id"=>$dataH->fin_warehouse_id,
				"fdt_trx_datetime"=>$dataH->fdt_sj_datetime,
				"fst_trx_code"=>"PRT", 
				"fin_trx_id"=>$dataH->fin_sj_id, 
				"fin_trx_detail_id"=>$dataD->fin_rec_id, 
				"fst_trx_no"=>$dataH->fst_sj_no, 
				"fst_referensi"=>$dataH->fst_sj_memo, 
				"fin_item_id"=>$dataD->fin_item_id, 
				"fst_unit"=>$dataD->fst_unit, 
				"fdb_qty_in"=>0,
				"fdb_qty_out"=>$dataD->fdb_qty, 
				"fdc_price_in"=>$priceIn, 
				"fbl_price_in_auto"=>false,
				"fst_active"=>"A" 
			];
			$this->trinventory_model->insert($dataStock);			
			 throwIfDBError();
		}

		$ssql ="UPDATE trsuratjalan set fbl_update_stock = 1,fdt_delivery_datetime = now() where fin_sj_id = ?";
		$this->db->query($ssql,[$sjId]);
	}

	private function postingAssemblingType($dataH,$dataDetails){
		$this->load->model("trassembling_model");

		if (getDbConfig("update_stock_on_delivery") == 0){
			$this->updateInventoryAssemblingType($dataH->fin_sj_id);
		}		
		$ssql = "UPDATE trassembling SET fin_sj_id = ? WHERE fin_assembling_id = ?";
		$qr = $this->db->query($ssql,[$dataH->fin_sj_id ,$dataH->fin_trans_id]);		    
	}

	public function updateInventoryAssemblingType($sjId){
		$ssql = "select * from trsuratjalan where fin_sj_id = ? and fst_active != 'D'";
		$qr = $this->db->query($ssql,[$sjId]);
		$dataH = $qr->row();
		if ($dataH == null){
			throw new CustomException(lang("invalid SJ id"),404,"FAILED",[]);	
		}else{
			if ($dataH->fbl_update_stock == 1){
				return;
			}
		}

		$ssql = "SELECT * FROM trsuratjalandetails WHERE fin_sj_id = ?";
		$qr = $this->db->query($ssql,[$sjId]);
		$dataDetails = $qr->result();
		foreach($dataDetails as $dataD){
			$dataSerial = [
				"fin_warehouse_id"=>$dataH->fin_warehouse_id,
				"fin_item_id"=>$dataD->fin_item_id,
				"fst_unit"=>$dataD->fst_unit,
				"fst_serial_number_list"=>$dataD->fst_serial_number_list,
				"fst_batch_no"=>$dataD->fst_batch_number,
				"fst_trans_type"=>"ASO", 
				"fin_trans_id"=>$dataH->fin_sj_id,
				"fst_trans_no"=>$dataH->fst_sj_no,
				"fin_trans_detail_id"=>$dataD->fin_rec_id,
				"fdb_qty"=>$dataD->fdb_qty,
				"in_out"=>"OUT",
			];            
			$this->trinventory_model->insertSerial($dataSerial);

			

			$dataStock = [
				"fin_warehouse_id"=>$dataH->fin_warehouse_id,
				"fdt_trx_datetime"=>$dataH->fdt_sj_datetime,
				"fst_trx_code"=>"ASO", 
				"fin_trx_id"=>$dataH->fin_sj_id, 
				"fin_trx_detail_id"=>$dataD->fin_rec_id, 
				"fst_trx_no"=>$dataH->fst_sj_no, 
				"fst_referensi"=>$dataH->fst_sj_memo, 
				"fin_item_id"=>$dataD->fin_item_id, 
				"fst_unit"=>$dataD->fst_unit, 
				"fdb_qty_in"=>0,
				"fdb_qty_out"=>$dataD->fdb_qty, 
				"fdc_price_in"=>0, 
				"fbl_price_in_auto"=>false,
				"fst_active"=>"A" 
			];
			$this->trinventory_model->insert($dataStock);			
			 throwIfDBError();
		}

		$ssql ="UPDATE trsuratjalan set fbl_update_stock = 1,fdt_delivery_datetime = now() where fin_sj_id = ?";
		$this->db->query($ssql,[$sjId]);
	}



	public function delete($key, $softdelete = TRUE,$data=null){
		if ($softdelete){
			$ssql = "UPDATE trsuratjalandetails SET fst_active ='D' WHERE fin_sj_id = ?";
		}else{
			$ssql = "DELETE FROM trsuratjalandetails where fin_sj_id = ?";            
		}
		throwIfDBError();        
		parent::delete($key,$softdelete);
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

	public function isEditable($finSJId){
		/**
		 * + SO tidak bisa di rubah bila sudah terbit invoice / faktur
		 * +          
		 */

		$dataH  = $this->db->get_where("trsuratjalan",["fin_sj_id"=>$finSJId])->row();
		if($dataH == null){
			throw new CustomException("Invalid SJ Id",3003,'FAILED',["fin_sj_id"=>$finSJId]);            
		}

		if ($dataH->fst_sj_type == "SO"){
			$ssql = "select a.*,b.fst_inv_no from trsuratjalan a 
				inner join trinvoice b on a.fin_inv_id = b.fin_inv_id 
				where a.fin_sj_id = ?";
			$qr = $this->db->query($ssql,[$finSJId]);
			$rw =$qr->row();
			if ($rw != null){
				throw new CustomException(sprintf(lang("Transaksi tidak bisa dirubah karena sudah ada invoice %s"),$rw->fst_inv_no),3003,"FAILED",[]);
			}
		}else if ($dataH->fst_sj_type == "PO_RETURN"){
			
			$purchaseReturn = $this->db->get_where("trpurchasereturn",["fin_purchasereturn_id"=>$dataH->fin_trans_id])->row();
			if($purchaseReturn == null){
				throw new CustomException("Invalid Reff Purchase Return",3003,'FAILED',[$dataH]);
			}

			//Cek Non Faktur atau faktur
			if ($purchaseReturn->fbl_non_faktur == 1){
				/**
				 * non faktur: no voucher retur tidak boleh digunakan
				 */
				if ($purchaseReturn->fdc_total_claimed > 0){
					throw new CustomException(lang("Transaksi tidak bisa dibatalkan, karena sudah ada pemakaian voucher return"),3003,'FAILED',[$purchaseReturn]);
				}
			}else{
				/**
				 * faktur: faktur tidak boleh sudah ada pembayaran
				 */
				$lpbPurchase = $this->db->get_where("trlpbpurchase",["fin_lpbpurchase_id"=>$purchaseReturn->fin_lpbpurchase_id])->row();
				if ($lpbPurchase->fdc_total_paid > 0){
					throw new CustomException(lang("Transaksi tidak bisa dibatalkan, karena sudah ada pembayaran untuk invoice yang diretur"),3003,'FAILED',[$lpbPurchase]);
				}

			}


		}
		
		
	}

	public function deleteDetails($finSJId){
		$ssql ="delete from trsuratjalandetails where fin_sj_id = ?";
		$this->db->query($ssql,[$finSJId]);
		throwIfDBError();
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

	public function getTransactionList($sjType,$term){
		switch ($sjType){            
			case "SO":                        
				$ssql = "SELECT a.fin_salesorder_id as fin_trans_id,a.fst_salesorder_no as fst_trans_no,a.fin_relation_id,a.fdt_salesorder_datetime as fdt_trans_datetime,
					a.fin_shipping_address_id,a.fin_warehouse_id,
					c.fst_relation_name,d.fst_name as fst_address_name ,d.fst_shipping_address FROM trsalesorder a
					INNER JOIN trsalesorderdetails b ON a.fin_salesorder_id = b.fin_salesorder_id 
					INNER JOIN msrelations c ON a.fin_relation_id= c.fin_relation_id 
					INNER JOIN msshippingaddress d ON a.fin_shipping_address_id = d.fin_shipping_address_id
					WHERE a.fst_active ='A' 
					AND a.fbl_is_hold = FALSE 
					AND a.fbl_is_closed = FALSE 
					AND a.fdc_downpayment <= a.fdc_downpayment_paid
					AND (a.fst_salesorder_no like ? OR c.fst_relation_name like ? )
					GROUP BY b.fin_salesorder_id HAVING SUM(b.fdb_qty) > SUM(b.fdb_qty_out)";
				$qr = $this->db->query($ssql,["%".$term."%","%".$term."%"]);                
				return $qr->result();
				break;
			case "PO_RETURN":
				$ssql = "SELECT a.fin_purchasereturn_id as fin_trans_id,a.fst_purchasereturn_no as fst_trans_no,c.fin_relation_id,a.fdt_purchasereturn_datetime as fdt_trans_datetime,
					null as fin_shipping_address_id,null as fin_warehouse_id,
					c.fst_relation_name,null as fst_address_name, null as fst_shipping_address 
					FROM trpurchasereturn a
					INNER JOIN trpurchasereturnitems b ON a.fin_purchasereturn_id = b.fin_purchasereturn_id 
					INNER JOIN msrelations c ON a.fin_supplier_id= c.fin_relation_id                     
					WHERE a.fst_active ='A' 
					AND a.fbl_is_closed = FALSE 
					AND (a.fst_purchasereturn_no like ? OR c.fst_relation_name like ? )
					GROUP BY b.fin_purchasereturn_id HAVING SUM(b.fdb_qty) > SUM(b.fdb_qty_out)";
				$qr = $this->db->query($ssql,["%".$term."%","%".$term."%"]);                                
				return $qr->result();
				break;
			case "ASSEMBLING_OUT":
				//$ssql = "SELECT a.fin_assembling_id as fin_trans_id,a.fst_assembling_no as fst_trans_no,0 as fin_relation_id,a.fdt_assembling_datetime as fdt_trans_datetime,";
				$ssql = "SELECT a.* FROM trassembling a 
					WHERE fin_sj_id is NULL 
					AND a.fst_assembling_no like ? and a.fst_active ='A' ";
				$qr = $this->db->query($ssql,["%".$term."%"]);
				$rs =  $qr->result();
				for($i = 0;$i < sizeof($rs);$i++){
					$rw = $rs[$i];					
					$rw->fin_warehouse_id = $rw->fin_source_warehouse_id;
					$rs[$i] = $rw;
					//$rw->fin_warehouse_id = $rw->fin_target_warehouse_id;
				}
				return $rs;
				break;
			default:
				return null;
		}
	}
	public function getPendingDetailTrans($sjType,$transId){
		$this->load->model("msitems_model");

		switch($sjType){
			case "SO":
				$salesOrderId = $transId;
				$ssql = "select a.fin_rec_id as fin_trans_detail_id,a.fin_item_id,a.fst_custom_item_name,
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
				break;

			case "PO_RETURN":
				$ssql = "select a.fin_rec_id as fin_trans_detail_id,a.fin_item_id,a.fst_custom_item_name,
					a.fst_unit, 0 as fin_promo_id,b.fbl_is_batch_number,b.fbl_is_serial_number,
					(a.fdb_qty - a.fdb_qty_out ) as fdb_qty,
					b.fst_item_code,b.fst_item_name
					from trpurchasereturnitems a
					inner join msitems b on a.fin_item_id = b.fin_item_id
					where fin_purchasereturn_id = ? and fdb_qty > fdb_qty_out";
				$qr = $this->db->query($ssql,[$transId]);                
				throwIfDBError();
				$rs = $qr->result();
				for($i = 0;$i < sizeof($rs); $i++){
					$rw = $rs[$i];
					$fstBasicUnit = $this->msitems_model->getBasicUnit($rw->fin_item_id);
					$rw->fst_basic_unit = $fstBasicUnit;
					$rw->fdc_conv_to_basic_unit = $this->msitems_model->getQtyConvertToBasicUnit($rw->fin_item_id,1,$rw->fst_unit);            
					$rs[$i] =  $rw;
				}
				return $rs;                
				
				break;
			case "ASSEMBLING_OUT" :
				$ssql = "SELECT * FROM trassembling where fin_assembling_id = ?";
				$qr = $this->db->query($ssql,[$transId]);
				$rw = $qr->row();
				
				if ($rw != NULL){
					if ($rw->fst_type == "ASSEMBLING"){
						//item out dari detail assembling
						$ssql = "SELECT a.fin_item_id,a.fst_unit,a.fdb_qty,
							b.fbl_is_batch_number,b.fbl_is_serial_number,
							b.fst_item_name as fst_custom_item_name,b.fst_item_name,b.fst_item_code,
							0 as fin_promo_id,a.fin_rec_id as fin_trans_detail_id 
							FROM trassemblingitems a 
							inner join msitems b on a.fin_item_id = b.fin_item_id  
							where a.fin_assembling_id = ?";
					}else{
						$ssql = "SELECT a.fin_item_id,a.fst_unit,a.fdb_qty,
							b.fbl_is_batch_number,b.fbl_is_serial_number,
							b.fst_item_name as fst_custom_item_name,b.fst_item_name,b.fst_item_code,
							0 as fin_promo_id,fin_assembling_id as fin_trans_detail_id 							
							FROM trassembling  a 
							inner join msitems b on a.fin_item_id = b.fin_item_id 
							where a.fin_assembling_id = ?";
					}
					$qr = $this->db->query($ssql,[$transId]);					

					$rs = $qr->result();
					for($i=0;$i<sizeof($rs);$i++){
						$rw = $rs[$i];
						$rw->fst_basic_unit = $this->msitems_model->getBasicUnit($rw->fin_item_id);
						$rw->fdc_conv_to_basic_unit = $this->msitems_model->getConversionUnit($rw->fin_item_id,$rw->fst_unit,$rw->fst_basic_unit);
						$rs[$i] = $rw;						
					}
					return $rs;					
				}else{
					return [];
				}

				break;
			default :
				return [];
		}

		
	}

	public function getDataVoucher($finSJId){

		/*$data = $this->getDataById($finSJId);
		//$header = (array) $data["sj"];	

		
		//$details = (array) $data["sj_details"];	

		
		return [
			"header"=>(array) $data["sj"],
			"details"=>(array) $data["sj_details"]
		];*/

		$ssql = "SELECT a.*,
		IFNULL(IFNULL(b.fst_salesorder_no,c.fst_purchasereturn_no),g.fst_assembling_no) as fst_trans_no ,
		IFNULL(IFNULL(b.fdt_salesorder_datetime,c.fdt_purchasereturn_datetime),g.fdt_assembling_datetime) as fdt_trans_datetime,            
		d.fin_relation_id,d.fst_relation_name,e.fst_name as fst_shipping_name,e.fst_shipping_address,
		f.fst_warehouse_name 
		FROM trsuratjalan a
		LEFT JOIN trsalesorder b on a.fin_trans_id = b.fin_salesorder_id and a.fst_sj_type = 'SO' 
		LEFT JOIN trpurchasereturn c on a.fin_trans_id = c.fin_purchasereturn_id and a.fst_sj_type = 'PO_RETURN' 
		LEFT JOIN trassembling g on a.fin_trans_id = g.fin_assembling_id and a.fst_sj_type = 'ASSEMBLING_OUT'  
		LEFT JOIN msrelations d on IFNULL(b.fin_relation_id,c.fin_supplier_id)  = d.fin_relation_id 
		LEFT JOIN msshippingaddress e on a.fin_shipping_address_id = e.fin_shipping_address_id 
		INNER JOIN mswarehouse f on a.fin_warehouse_id = f.fin_warehouse_id 
		where a.fin_sj_id = ? and a.fst_active !='D' ";

		$qr = $this->db->query($ssql, [$finSJId]);      
		
		throwIfDBError();  
		$rwSJ = $qr->row_array();


		if ($rwSJ["fst_sj_type"] == "SO"){

			$ssql = "SELECT a.*,
				b.fin_promo_id,
				b.fst_custom_item_name,
				c.fbl_is_batch_number,c.fbl_is_serial_number,c.fst_item_code,c.fst_item_name,
				d.fst_unit as fst_basic_unit,d.fdc_conv_to_basic_unit 
				FROM trsuratjalandetails a 
				INNER JOIN trsalesorderdetails b on a.fin_trans_detail_id = b.fin_rec_id 
				INNER JOIN msitems c on b.fin_item_id = c.fin_item_id  
				LEFT JOIN msitemunitdetails d on c.fin_item_id = d.fin_item_id and d.fbl_is_basic_unit = 1
				WHERE a.fin_sj_id = ?";



		}else if ($rwSJ["fst_sj_type"] == "PO_RETURN"){
			$ssql = "SELECT a.*,
				0 as fin_promo_id,b.fst_custom_item_name,
				c.fbl_is_batch_number,c.fbl_is_serial_number,c.fst_item_code,c.fst_item_name,
				d.fst_unit as fst_basic_unit,d.fdc_conv_to_basic_unit,
				FROM trsuratjalandetails a 
				INNER JOIN trpurchasereturnitems b on a.fin_trans_detail_id = b.fin_rec_id 
				INNER JOIN msitems c on b.fin_item_id = c.fin_item_id  
				LEFT JOIN msitemunitdetails d on c.fin_item_id = d.fin_item_id and d.fbl_is_basic_unit = 1
				WHERE a.fin_sj_id = ?";

		}else if ($rwSJ["fst_sj_type"] == "ASSEMBLING_OUT"){
			$ssql = "SELECT a.*,
				0 as fin_promo_id,c.fst_item_name as fst_custom_item_name,
				c.fbl_is_batch_number,c.fbl_is_serial_number,c.fst_item_code,c.fst_item_name,
				d.fst_unit as fst_basic_unit,d.fdc_conv_to_basic_unit 
				FROM trsuratjalandetails a 
				INNER JOIN msitems c on a.fin_item_id = c.fin_item_id  
				LEFT JOIN msitemunitdetails d on c.fin_item_id = d.fin_item_id and d.fbl_is_basic_unit = 1
				WHERE a.fin_sj_id = ?";

		}else{
			return [
				"sj" => $rwSJ,
				"sj_details" => []
			];
		}

		$qr = $this->db->query($ssql,[$finSJId]);
		$rsSJDetails = $qr->result_array();
		
		return [
			"header" => $rwSJ,
			"details" => $rsSJDetails
		];

	}


}
