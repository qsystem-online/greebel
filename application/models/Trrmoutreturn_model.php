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

	public function getDataById($finRMOutReturnId){                
		$this->load->model("msitems_model");
		$ssql = "SELECT a.*,b.fst_wo_no,c.fst_warehouse_name,d.fst_wobatchno_no 
			FROM trrmoutreturn a 
			INNER JOIN trwo b on a.fin_wo_id = b.fin_wo_id 
			INNER JOIN mswarehouse c on a.fin_warehouse_id = c.fin_warehouse_id 
			INNER JOIN trwobatchno d on a.fin_wobatchno_id = d.fin_wobatchno_id 
			WHERE a.fin_rmout_return_id = ? and a.fst_active !='D'";
		
		$qr = $this->db->query($ssql,[$finRMOutReturnId]);      
		$dataH = $qr->row();

		if ($dataH == null){
			return null;
		}

		//Detail 
		$ssql = "SELECT a.*,b.fst_item_code,b.fst_item_name,b.fbl_is_batch_number,b.fbl_is_serial_number,
			c.fst_unit as fst_basic_unit,d.fdc_conv_to_basic_unit 
			FROM trrmoutreturnitems a
			INNER JOIN msitems b on a.fin_item_id = b.fin_item_id 
			INNER JOIN msitemunitdetails c on a.fin_item_id = c.fin_item_id and c.fbl_is_basic_unit = 1
			INNER JOIN msitemunitdetails d on a.fin_item_id = d.fin_item_id  and a.fst_unit = d.fst_unit
			WHERE a.fin_rmout_return_id = ? AND a.fst_active ='A' ";
		$qr = $this->db->query($ssql,[$finRMOutReturnId]);		
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

	public function isEditable($finRMOutReturnId){
		//Kalau wobatchno sudah close tidak bisa di edit
		$rmoutReturn =$this->getSimpleDataById($finRMOutReturnId);
		if ($rmoutReturn == null){
			throw new CustomException(lang("Invalid rmout return id"), 9009,"FAILED",[]);			
		}

		$this->load->model("trwobatchno_model");
		$batchNo = $this->trwobatchno_model->getSimpleDataById($rmoutReturn->fin_wobatchno_id);
		if ($batchNo == null){
			throw new CustomException("[is editable]" +lang("Invalid wobatchno id"), 9009,"FAILED",[]);			
		}

		if ($batchNo->fbl_closed ){
			throw new CustomException("[is editable]" +lang("Proses gagal, WO Batch Number sudah di close !"), 3003,"FAILED",[]);
		}

	}
    
	
	public function getHPPReturnItem($finItemId,$finReturnItemId,$finWarehouseId){
		$this->load->model("trinventory_model");

		//Cek HPP Return item id;
		$hpp = $this->trinventory_model->getLastHPP($finReturnItemId,$finWarehouseId);
		if ($hpp > 0){
			return $hpp;
		}

		//Cek kalau item return merupakan non component
		$ssql = "select * from msitemnoncomponentdetails where fin_item_id = ? and fin_nc_item_id = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$finItemId,$finreturnItemId]);
		$rw = $qr->row();
		if ($rw){
			
			if ($rw->fst_hpp_type =='PRODUCT'){
				$hpp = $this->trinventory_model->getLastHPP($finItemId,$finWarehouseId);
			}else{
				$arrItemList = explode(",",$rw->fst_item_list_id);
				foreach($arrItemList as $itemId){
					$hpp = $this->trinventory_model->getLastHPP($itemId,$finWarehouseId);
					if ($hpp > 0){
						return $hpp;
					}
				}
				return 0;
			}
		}
		throw new CustomException(lang("Item return not permited !"),3003,"FAILED",[]);
	}	

	public function posting($finRMOutReturnId){	
		$this->load->model("trinventory_model");
		$this->load->model("mswarehouse_model");

		$dataH = $this->getSimpleDataById($finRMOutReturnId);

		//Update Inventory
		$ssql = "SELECT * FROM trrmoutreturnitems where fin_rmout_return_id  = ?  and fst_active ='A'";
		$qr = $this->db->query($ssql,[$finRMOutReturnId]);
		$rs = $qr->result();
		foreach($rs as $dataD){
			$dataSerial = [
				"fin_warehouse_id"=>$dataH->fin_warehouse_id,
				"fin_item_id"=>$dataD->fin_item_id,
				"fst_unit"=>$dataD->fst_unit,
				"fst_serial_number_list"=>$dataD->fst_serial_number_list,
				"fst_batch_no"=>$dataD->fst_batch_number,
				"fst_trans_type"=>"ROUTR", //RMOUT RETURN 
				"fin_trans_id"=>$finRMOutReturnId,
				"fst_trans_no"=>$dataH->fst_rmout_return_no,
				"fin_trans_detail_id"=>$dataD->fin_rec_id,
				"fdb_qty"=>$dataD->fdb_qty,
				"in_out"=>"IN",
			];
			$this->trinventory_model->insertSerial($dataSerial);

			$data = [
				"fin_warehouse_id"=>$dataH->fin_warehouse_id,
				"fdt_trx_datetime"=>$dataH->fdt_rmout_return_datetime,
				"fst_trx_code"=>"ROUTR", //RMOUT RETURN 
				"fin_trx_id"=>$dataH->fin_rmout_return_id,
				"fst_trx_no"=>$dataH->fst_rmout_return_no,
				"fin_trx_detail_id"=>$dataD->fin_rec_id,
				"fst_referensi"=>$dataH->fst_memo,
				"fin_item_id"=>$dataD->fin_item_id,
				"fst_unit"=>$dataD->fst_unit,
				"fdb_qty_in"=>$dataD->fdb_qty,
				"fdb_qty_out"=>0,
				"fdc_price_in"=>$dataD->fdc_avg_cost,
				"fbl_price_in_auto"=>false,
				"fst_active"=>"A"
			];
			$this->trinventory_model->insert($data);				
		}
	}

	public function unposting($finRMOutReturnId){	
		$this->load->model("trinventory_model");
		
		$dataH = $this->getSimpleDataById($finRMOutReturnId);
		if ($dataH == null){
			throw new CustomException(lang("invalid RM-OUT Return id"),404,"FAILED",[]);	
		}

		//delete Inventory
		$this->trinventory_model->deleteByCodeId("ROUTR",$finRMOutReturnId);

		//Delete itemdetails
		$this->trinventory_model->deleteInsertSerial("ROUTR",$finRMOutReturnId);
	}
	public function deleteDetail($finRMOutReturnId){
		$ssql ="DELETE FROM trrmoutreturnitems where fin_rmout_return_id = ?";
		$this->db->query($ssql,[$finRMOutReturnId]);
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