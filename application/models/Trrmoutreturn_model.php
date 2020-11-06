<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trrmoutreturn_model extends MY_Model{
	public $tableName = "trrmoutreturn";
	public $pkey = "fin_rmout_return_id";

	public function __construct()
	{
		parent::__construct();
	}

	public function getRules($mode = "ADD", $id = 0)
	{
		$rules = [];

		$rules[] = [
			'field' => 'fst_rmout_return_no',
			'label' => 'Nomor RM-OUT Retur',
			'rules' => array(
				'required',
			),
			'errors' => array(
				'required' => '%s tidak boleh kosong',
				//'is_unique' => '%s unik'
			),
		];      
		
		return $rules;
	}

	public function getDataById($finRMOutId){                
		$this->load->model("msitems_model");
		$ssql = "SELECT a.*,b.fst_warehouse_name
			FROM trrmout a 
			INNER JOIN mswarehouse b on a.fin_warehouse_id = b.fin_warehouse_id 
			WHERE a.fin_rmout_id = ? and a.fst_active !='D'";
		
		$qr = $this->db->query($ssql,[$finRMOutId]);      
		$dataH = $qr->row();

		if ($dataH == null){
			return null;
		}

		//Detail 
		$ssql = "SELECT a.*,
			b.fst_item_code,b.fst_item_name,b.fbl_is_batch_number,b.fbl_is_serial_number,
			c.fdc_conv_to_basic_unit,
			d.fst_unit as fst_basic_unit
			FROM trrmoutitems a
			INNER JOIN msitems b on a.fin_item_id = b.fin_item_id 
			INNER JOIN msitemunitdetails c on a.fin_item_id = c.fin_item_id  and a.fst_unit = c.fst_unit
			INNER JOIN msitemunitdetails d on a.fin_item_id = d.fin_item_id  and d.fbl_is_basic_unit = 1 
			WHERE a.fin_rmout_id = ? AND a.fst_active ='A' ";
		$qr = $this->db->query($ssql,[$finRMOutId]);		
		$details = $qr->result();
		
		
		return [
			"header"=>$dataH,
			"details"=>$details,
		];
	}

	
	
    public function generateTransactionNo($trDate = null) {
		$trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
		$tahun = date("Y/m", strtotime ($trDate));
		$activeBranch = $this->aauth->get_active_branch();
		$branchCode = "";
		if($activeBranch){
			$branchCode = $activeBranch->fst_branch_code;
		}
		$prefix = getDbConfig("rmout_return_prefix") . "/" . $branchCode ."/";
		$query = $this->db->query("SELECT MAX(fst_rmout_return_no) as max_id FROM trrmoutreturn where fst_rmout_return_no like '".$prefix.$tahun."%'");
		
		$row = $query->row_array();        
		$max_id = $row['max_id']; 		
		$max_id1 =(int) substr($max_id,strlen($max_id)-5);		
		$fst_tr_no = $max_id1 +1;		
		$max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);		
		return $max_tr_no;
	}
    
	
	


























	
	public function posting($finRMOutId){	
		$this->load->model("trinventory_model");
		$this->load->model("mswarehouse_model");

		$ssql ="SELECT * FROM trrmout where fin_rmout_id = ?";
		$qr = $this->db->query($ssql,[$finRMOutId]);
		$dataH = $qr->row();
		if ($dataH == null){
			throw new CustomException(lang("invalid rmout id"),404,"FAILED",[]);	
		}

		$ssql = "SELECT * FROM trrmoutitems where fin_rmout_id = ?";
		$qr = $this->db->query($ssql,[$finRMOutId]);
		$details = $qr->result();
		
		foreach($details as $dataD){
			//update batchno dan serialno			
			$dataSerial = [
				"fin_warehouse_id"=>$dataH->fin_warehouse_id,
				"fin_item_id"=>$dataD->fin_item_id,
				"fst_unit"=>$dataD->fst_unit,
				"fst_serial_number_list"=>$dataD->fst_serial_number_list,
				"fst_batch_no"=>$dataD->fst_batch_number,
				"fst_trans_type"=>"RMOUT", 
				"fin_trans_id"=>$dataH->fin_rmout_id,
				"fst_trans_no"=>$dataH->fst_rmout_no,
				"fin_trans_detail_id"=>$dataD->fin_rec_id,
				"fdb_qty"=>$dataD->fdb_qty,
				"in_out"=>"OUT",
			];
			$this->trinventory_model->insertSerial($dataSerial);

			// OUT barang dari from production warehouse 
			$data = [
				"fin_warehouse_id"=>$dataH->fin_warehouse_id,
				"fdt_trx_datetime"=>$dataH->fdt_rmout_datetime,
				"fst_trx_code"=>"RMOUT", //MAG_PRODUKSI_OUT
				"fin_trx_id"=>$dataH->fin_rmout_id,
				"fst_trx_no"=>$dataH->fst_rmout_no,
				"fin_trx_detail_id"=>$dataD->fin_rec_id,
				"fst_referensi"=>$dataH->fst_memo,
				"fin_item_id"=>$dataD->fin_item_id,
				"fst_unit"=>$dataD->fst_unit,
				"fdb_qty_in"=>0,
				"fdb_qty_out"=>$dataD->fdb_qty,
				"fdc_price_in"=>0,
				"fbl_price_in_auto"=>false,
				"fst_active"=>"A"
			];
			$this->trinventory_model->insert($data);			
		}

		//RMOUT PRODUKSI
		if ($dataH->fin_pagp_id != null){

		}
	}

	public function unposting($finRMOutId){	
		$this->load->model("trinventory_model");
		
		$ssql ="SELECT * FROM trrmout where fin_rmout_id = ?";
		$qr = $this->db->query($ssql,[$finRMOutId]);
		$dataH = $qr->row();
		if ($dataH == null){
			throw new CustomException(lang("invalid RM-OUT id"),404,"FAILED",[]);	
		}

		//delete Inventory
		$this->trinventory_model->deleteByCodeId("RMOUT",$finRMOutId);

		//Delete itemdetails
		$this->trinventory_model->deleteInsertSerial("RMOUT",$finRMOutId);
	}

	public function isEditable(){
		//LHP apa ada ubungan dengan rmout ??

	}

	public function deleteDetail($finRMOutId){
		$ssql ="DELETE FROM trrmoutitems where fin_rmout_id = ?";
		$this->db->query($ssql,[$finRMOutId]);
    }
    
	public function delete($finId,$softDelete=true,$data=null){
		parent::delete($finId,$softDelete);
		if(!$softDelete){
			//$this->db->delete("trmpsitems",array("fin_mts_id"=>$finId));
			//DELETE BOM WO
			$this->db->delete("trwobomdetails",array("fin_wo_id"=>$finId));			
			//DELETE ACTIVITY
			$this->db->delete("trwoactivitydetails",array("fin_wo_id"=>$finId));
		}else{
			$this->db->query("update trwobomdetails set fst_active ='D' where fin_wo_id = ?",[$finId]);
			$this->db->query("update trwoactivitydetails set fst_active ='D' where fin_wo_id = ?",[$finId]);
		}        		
		return [
			"status"=>true,
			"message"=>"",
		];
	}

}