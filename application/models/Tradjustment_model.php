<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Tradjustment_model extends MY_Model {
	public $tableName = "tradjustment";
	public $pkey = "fin_adjustment_id";

	public function __construct(){
		parent:: __construct();
	}

	public function getRules($mode="ADD",$id=0){
		$rules = [];
		$rules[] = [
			'field' => 'fst_adjustment_no',
			'label' => 'Adjustment No',
			'rules' => 'required',
			'errors' => array(
				'required' => '%s tidak boleh kosong',
			)
		];

		$rules[] = [
			'field' => 'fin_warehouse_id',
			'label' => 'Gudang',
			'rules' => 'required',
			'errors' => array(
				'required' => '%s tidak boleh kosong',
			)
		];

		return $rules;
	}

	public function GenerateNo($trDate = null) {
		$trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
		$tahun = date("Y/m", strtotime ($trDate));
		$activeBranch = $this->aauth->get_active_branch();
		$branchCode = "";
		if($activeBranch){
			$branchCode = $activeBranch->fst_branch_code;
		}
		$prefix = getDbConfig("adjustment_prefix") . "/" . $branchCode ."/";
		$query = $this->db->query("SELECT MAX(fst_adjustment_no) as max_id FROM tradjustment where fst_adjustment_no like '".$prefix.$tahun."%'");
		$row = $query->row_array();

		$max_id = $row['max_id']; 
		
		$max_id1 =(int) substr($max_id,strlen($max_id)-5);
		
		$fst_tr_no = $max_id1 +1;
		
		$max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);
		
		return $max_tr_no;
	}

	public function getDataById($finAdjustmentId){
		$this->load->model("msitemunitdetails_model");

		$ssql = "SELECT a.*,b.fst_warehouse_name as fst_warehouse_name
			FROM tradjustment a 
			INNER JOIN mswarehouse b on a.fin_warehouse_id = b.fin_warehouse_id
			where a.fin_adjustment_id = ? and a.fst_active != 'D'";

		$qr = $this->db->query($ssql,[$finAdjustmentId]);
		
		$header = $qr->row();

		
		if ($header != null){
			$ssql = "SELECT a.*,
				b.fst_item_code,b.fst_item_name,fbl_is_batch_number,fbl_is_serial_number
				FROM tradjustmentitems a 
				INNER JOIN msitems b on a.fin_item_id = b.fin_item_id 
				WHERE a.fin_adjustment_id = ?";

			$qr = $this->db->query($ssql,[$finAdjustmentId]);
			$details = $qr->result();

			for($i =0;$i < sizeof($details);$i++){
				$dataD = $details[$i];
				$dataD->fst_basic_unit = $this->msitemunitdetails_model->getBasicUnit($dataD->fin_item_id);
				$dataD->fdc_conv_to_basic_unit = $this->msitemunitdetails_model->getConversionUnit($dataD->fin_item_id,1,$dataD->fst_unit,$dataD->fst_basic_unit);
				$details[$i]= $dataD;				
			}
			return [
				"header"=>$header,
				"details"=>$details
			];
		}

		return [
			"header"=>null,
			"details"=>null
		];

	}
	public function getDataHeaderById($finAdjustmentId){
		$ssql = "SELECT * FROM tradjustment a
			where fin_adjustment_id = ? and fst_active != 'D'";

		$qr = $this->db->query($ssql,[$finAdjustmentId]);		
		$header = $qr->row();
		return $header;
	}

	public function posting($finAdjustmentId){
		if (getDbConfig("update_stock_on_delivery") == 0){
			$this->updateInventory($finAdjustmentId);
		}			
	}

	public function updateInventory($finAdjustmentId){
		$this->load->model("trinventory_model");
		$this->load->model("mswarehouse_model");

		$ssql ="SELECT * FROM tradjustment where fin_adjustment_id = ?";
		$qr = $this->db->query($ssql,[$finAdjustmentId]);
		$dataH = $qr->row();
		if ($dataH == null){
			throw new CustomException(lang("invalid adjustment id"),404,"FAILED",[]);	
		}


		$ssql = "SELECT * FROM tradjustmentitems where fin_adjustment_id = ?";
		$qr = $this->db->query($ssql,[$finAdjustmentId]);
		$details = $qr->result();

		//Mutasi IN barang ke buffer warehouse base on branch id
		//$this->load->model("mswarehouse_model");
		//$bufferWarehouse = $this->mswarehouse_model->getBufferWarehouseId();

		foreach($details as $dataD){
			//update batchno dan serialno IN/OUT	
			$dataSerial = [
				"fin_warehouse_id"=>$dataH->fin_warehouse_id,
				"fin_item_id"=>$dataD->fin_item_id,
				"fst_unit"=>$dataD->fst_unit,
				"fst_serial_number_list"=>$dataD->fst_serial_number_list,
				"fst_batch_no"=>$dataD->fst_batch_number,
				"fst_trans_type"=>"ADJ", 
				"fin_trans_id"=>$dataH->fin_adjustment_id,
				"fst_trans_no"=>$dataH->fst_adjustment_no,
				"fin_trans_detail_id"=>$dataD->fin_rec_id,
				"fdb_qty"=>$dataD->fdb_qty,
			];
			$in_out = $dataD->fst_in_out;
			if ($in_out == "IN" ){
				$data["in_out"] = "IN";
			}else{
				$data["in_out"] = "OUT";
			}
			$this->trinventory_model->insertSerial($dataSerial);

			//Adjustment Stock IN / OUT
			$data = [
				"fin_warehouse_id"=>$dataH->fin_warehouse_id,
				"fdt_trx_datetime"=>$dataH->fdt_adjustment_datetime,
				"fst_trx_code"=>"ADJ",
				"fin_trx_id"=>$dataH->fin_adjustment_id,
				"fst_trx_no"=>$dataH->fst_adjustment_no,
				"fin_trx_detail_id"=>$dataD->fin_rec_id,
				"fst_referensi"=>$dataH->fst_notes,
				"fin_item_id"=>$dataD->fin_item_id,
				"fst_unit"=>$dataD->fst_unit,
				"fdc_price_in"=>0,
				"fbl_price_in_auto"=>false,
				"fst_active"=>"A"
			];
			$in_out = $dataD->fst_in_out;
			if ($in_out == "IN" ){
				$data["fdb_qty_in"] = $dataD->fdb_qty;
				$data["fdb_qty_out"]= 0;
			}else{
				$data["fdb_qty_in"] = 0;
				$data["fdb_qty_out"]= $dataD->fdb_qty;
			}
			$this->trinventory_model->insert($data);
		} //end for

	}

	public function unposting($finAdjustmentId){		
		$this->load->model("trinventory_model");
		
		$ssql ="SELECT * FROM tradjustment where fin_adjustment_id = ?";
		$qr = $this->db->query($ssql,[$finAdjustmentId]);
		$dataH = $qr->row();
		if ($dataH == null){
			throw new CustomException(lang("invalid Adjustment id"),404,"FAILED",[]);	
		}
		//delete Inventory
		$this->trinventory_model->deleteByCodeId("ADJ",$finAdjustmentId);

		//Delete itemdetails
		$this->trinventory_model->deleteInsertSerial("ADJ",$finAdjustmentId);
	}

	public function deleteDetail($finAdjustmentId){
		$ssql = "delete from tradjustmentitems where fin_adjustment_id =?";
		$this->db->query($ssql,[$finAdjustmentId]);
		throwIfDBError();
	}

	public function isEditable($finAdjustmentId){

		//Kalau sudah ada di confirm tidak bisa di rubah

		$ssql = "SELECT * FROM tradjustment where fin_adjustment_id = ? and fst_active != 'D'";
		$qr = $this->db->query($ssql,[$finAdjustmentId]);
		$rw = $qr->row();
		if ($rw != null){
			throw new CustomException(lang("MAG tidak dapat di rubah karena sudah dilakukan PAG"),3003,"FAILED",[]);
		}
		return [
			"status"=>"SUCCESS"
		];
	}

	public function getDataVoucher($finAdjustmentId){
		$data = $this->getDataById($finAdjustmentId);

		$header =  $data["header"];

		
		$header->fst_warehouse_name=$this->mswarehouse_model->getValue($header->fin_warehouse_id,"fst_warehouse_name");
		$data["header"] = (array) $header;

		return $data;
	}

	public function checkCloseMag($finAdjustmentId){
		
		$ssql = "SELECT * FROM tradjustmentitems where fin_adjustment_id = ? and fdb_qty < fdb_qty_confirm";
		$qr = $this->db->query($ssql,[$finAdjustmentId]);				
		$rw = $qr->row();
		if ($rw == null){
			$ssql = "UPDATE tradjustment set fbl_closed = 1,fin_closed_by = 0, fdt_closed_datetime = now() where fin_adjustment_id = ?";
			$this->db->query($ssql,[$finAdjustmentId]);
		}
	}



}