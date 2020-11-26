<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trlhp_model extends MY_Model {
	public $tableName = "trlhp";
	public $pkey = "fin_lhp_id";

	public function __construct(){
		parent:: __construct();
	}

	public function getRules($mode="ADD",$id=0){
		$rules = [];
		$rules[] = [
			'field' => 'fst_lhp_no',
			'label' => 'LHP No',
			'rules' => 'required',
			'errors' => array(
				'required' => '%s tidak boleh kosong',
			)
		];
		$rules[] = [
			'field' => 'fin_wobatchno_id',
			'label' => 'Batch No',
			'rules' => 'required',
			'errors' => array(
				'required' => '%s tidak boleh kosong',
			)
		];
		$rules[] = [
			'field' => 'fdb_gramasi',
			'label' => 'Gramasi',
			'rules' => 'greater_than[0]',
			'errors' => array(
				'required' => '%s tidak boleh 0',
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
		$prefix = getDbConfig("lhp_prefix") . "/" . $branchCode ."/";
		$query = $this->db->query("SELECT MAX(fst_lhp_no) as max_id FROM trlhp where fst_lhp_no like '".$prefix.$tahun."%'");
		$row = $query->row_array();

		$max_id = $row['max_id']; 
		
		$max_id1 =(int) substr($max_id,strlen($max_id)-5);
		
		$fst_tr_no = $max_id1 +1;
		
		$max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);
		
		return $max_tr_no;
	}
	

	public function posting($finLHPId){
		$this->load->model("trinventory_model");

		//$ssql = "SELECT * FROM trlhp where fin_lhp_id = ? and fst_active ='A'";
		//$qr = $this->db->query($ssql,[$finLHPId]);
		$dataH = $this->getSimpleDataById($finLHPId);		
		if ($dataH == null){
			throw new Customexception("Internal error when save header !",9009,"FAILED",["LHP ID :$finLHPId"]);
		}
	
		//Insert Inventory Update kartu stock nila hpp di kosongkan dulu akan di proses ketika batch wo sudah di close
		$dataStock = [
			//`fin_rec_id`, 
			"fin_warehouse_id"=>$dataH->fin_warehouse_id,
			"fdt_trx_datetime"=>$dataH->fdt_lhp_datetime,
			"fst_trx_code"=>"LHP", 
			"fin_trx_id"=>$dataH->fin_lhp_id,
			"fin_trx_detail_id"=>$dataH->fin_lhp_id,
			"fst_trx_no"=>$dataH->fst_lhp_no, 
			"fst_referensi"=>$dataH->fst_notes,
			"fin_item_id"=>$dataH->fin_item_id, 
			"fst_unit"=>$dataH->fst_wo_unit, 
			"fdb_qty_in"=>$dataH->fdb_qty_baseonwo, 
			"fdb_qty_out"=>0, 
			"fdc_price_in"=>0,
			"fbl_price_in_auto"=>false,
			"fst_active"=>"A" 
		];				
		$this->trinventory_model->insert($dataStock);
		//INSERT SERIAL

		$dataSerial = [
			"fin_warehouse_id"=>$dataH->fin_warehouse_id,
			"fin_item_id"=>$dataH->fin_item_id,
			"fst_unit"=>$dataH->fst_wo_unit,
			"fst_serial_number_list"=>$dataH->fst_serial_number_list,
			"fst_batch_no"=>$dataH->fst_batch_number,
			"fst_trans_type"=>"LHP", 
			"fin_trans_id"=>$dataH->fin_lhp_id,
			"fst_trans_no"=>$dataH->fst_lhp_no,
			"fin_trans_detail_id"=>$dataH->fin_lhp_id,
			"fdb_qty"=>$dataH->fdb_qty_baseonwo,
			"in_out"=>"IN",
		];		
		$this->trinventory_model->insertSerial($dataSerial);
		
		//Update QTY LHP di WO
		$ssql ="UPDATE trwo set fdb_qty_lhp = fdb_qty_lhp + ? where fin_wo_id = ?";
		$this->db->query($ssql,[floatval($dataH->fdb_qty_baseonwo),$dataH->fin_wo_id]);

		$ssql ="SELECT * FROM trwo where fin_wo_id = ? and fdb_qty_lhp > fdb_qty";
		$qr = $this->db->query($ssql,[$dataH->fin_wo_id]);
		$rw = $qr->row();
		if ($rw != null){
			throw new CustomException(lang("total Qty LHP melebihi qty di WO !"),3003,"FAILED",[]);
		}

	}

	public function getDataById($finLHPId){
	
		$ssql = "SELECT a.*,
			b.fdb_qty as fdb_qty_wo,b.fst_wo_no,b.fdb_qty_lhp,c.fst_warehouse_name,
			d.fst_item_name,e.fdc_conv_to_basic_unit,f.fst_wobatchno_no 
			FROM trlhp a 
			LEFT JOIN trwo b on a.fin_wo_id = b.fin_wo_id			
			INNER JOIN mswarehouse c on a.fin_warehouse_id = c.fin_warehouse_id
			INNER JOIN msitems d on a.fin_item_id = d.fin_item_id 
			INNER JOIN msitemunitdetails e on a.fin_item_id = e.fin_item_id and e.fst_unit = b.fst_unit
			LEFT JOIN trwobatchno f on a.fin_wobatchno_id = f.fin_wobatchno_id 
			where a.fin_lhp_id = ? and a.fst_active != 'D'";

		$qr = $this->db->query($ssql,[$finLHPId]);				
		$header = $qr->row();		
		if ($header == null){
			return null;
		}

		$ssql ="SELECT a.*,b.fst_name as fst_activity_name,b.fst_team,
			c.fst_username as fst_user_name,d.fst_team_name 
			FROM trlhpactivities a 
			INNER JOIN msactivity b on a.fin_activity_id = b.fin_activity_id 
			LEFT JOIN users c on a.fin_user_id = c.fin_user_id
			LEFT JOIN msactivityteams d on a.fin_team_id = d.fin_team_id
			where a.fin_lhp_id = ? and a.fst_active ='A'";
		$qr = $this->db->query($ssql,[$finLHPId]);

		$rs = $qr->result();
		return [
			"header"=>$header,
			"details"=>$rs
		];

	}
	
	public function unposting($finLHPId){		
		$this->load->model("trinventory_model");
		
		$ssql ="SELECT * FROM trlhp where fin_lhp_id = ?";
		$qr = $this->db->query($ssql,[$finLHPId]);
		$dataH = $qr->row();
		if ($dataH == null){
			throw new CustomException(lang("invalid LHP id"),404,"FAILED",[]);	
		}

		//delete Inventory
		$this->trinventory_model->deleteByCodeId("LHP",$finLHPId);
		
		//Delete itemdetails
		$this->trinventory_model->deleteInsertSerial("LHP",$finLHPId);
		
		$ssql = "UPDATE trwo set fdb_qty_lhp = fdb_qty_lhp - ? where fin_wo_id = ?";
		$this->db->query($ssql,[$dataH->fdb_qty_baseonwo,$dataH->fin_wo_id]);
		throwIfDBError();

	}

	public function isEditable($finLHPId){
		$this->load->model("trwo_model");

		$dataH = $this->getSimpleDataById($finLHPId);
		if ($dataH == null){
			throw new CustomException(lang("Invalid LHP Id"),3003,"FAILED",[]);			
		}

		// Kalau wo udah di close ngak bisa diedit
		$wo = $this->trwo_model->getSimpleDataById($dataH->fin_wo_id);
		if ($wo == null){
			throw new CustomException(lang("Invalid WO Id"),3003,"FAILED",[]);			
		}
		if ($wo->fbl_closed){
			throw new CustomException(lang("LHP tidak bisa dirubah, status WO closed !"),3003,"FAILED",[]);
		}

		// kalau wobatchno udah di close ngak bisa diedit
		$ssql = "SELECT * FROM trwobatchno where fin_wo_id  = ? and fin_wobatchno_id = ? and fst_active ='A'";
		$qr = $this->db->query($ssql,[$wo->fin_wo_id,$dataH->fin_wobatchno_id]);
		$rw = $qr->row();
		if ($rw == null){
			throw new CustomException(lang("Invalid WO batch number "),3003,"FAILED",[]);
		}
		if ($rw->fbl_closed){
			throw new CustomException(lang("LHP tidak bisa dirubah, status WO batch number closed !"),3003,"FAILED",[]);
		}


	
	}
		
	public function deleteDetail($finLHPId){
		$ssql = "delete from trlhpactivities where fin_lhp_id =?";
		$this->db->query($ssql,[$finLHPId]);
		throwIfDBError();
	}

	



}