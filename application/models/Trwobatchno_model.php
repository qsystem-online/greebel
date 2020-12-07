<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trwobatchno_model extends MY_Model{
	public $tableName = "trwobatchno";
	public $pkey = "fin_wobatchno_id";

	public function __construct()
	{
		parent::__construct();
	}

	public function getRules($mode = "ADD", $id = 0)
	{
		$rules = [];

		$rules[] = [
			'field' => 'fin_wo_id',
			'label' => 'Workorder ID',
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

	public function generateBatchNo($finWOId){
		$ssql = "SELECT * FROM trwobatchno where fin_wo_id = ? order by fst_wobatchno_no desc limit 1";
		$qr = $this->db->query($ssql,[$finWOId]);
		$rw = $qr->row();
		if($rw == null){
			$ssql = "SELECT * FROM trwo where fin_wo_id = ? and fst_active = 'A'";
			$qr= $this->db->query($ssql,[$finWOId]);
			$rwWO = $qr->row();
			if($rwWO == null){
				return null;
			}
			$lastNo = substr($rwWO->fst_wo_no,strlen($rwWO->fst_wo_no)-4);
			$lastNo .= "-000";
		}else{
			$lastNo = $rw->fst_wobatchno_no;
		}

		$woPrefix = substr($lastNo,0,strlen($lastNo)-3);
		$no  = (int) substr($lastNo,strlen($lastNo)-3);
		$no += 1;
		$strNextNo = "000" .$no;
		$strNextNo = substr($strNextNo,strlen($strNextNo)-3);
		return $woPrefix . $strNextNo;
	}

	public function getDataById($finWOBatchnoId){
		$ssql = "SELECT fin_wobatchno_id,fst_wobatchno_no,fin_wo_id,fdt_wobatchno_datetime,fst_notes,fst_active 
		FROM trwobatchno where fin_wobatchno_id = ? and fst_active != 'D'";
		$qr = $this->db->query($ssql,[$finWOBatchnoId]);
		$rw = $qr->row();
		return $rw;
	}


	public function closingBatch($finWOBatchnoId){
		$this->load->model("msitems_model");
		$this->load->model("trlhp_model");		
		//Get ALL RMOUT , RMOUT RETURN AND LHP Using this batch No
		//**HPP in basic unit need to convert to transaction unit */
		$totalRmoutHpp = 0;
		$ssql = "SELECT b.* FROM trrmout a 
			INNER JOIN trrmoutitems b on a.fin_rmout_id = b.fin_rmout_id  
			where a.fin_wobatchno_id = ? and a.fst_active ='A'";
		$qr = $this->db->query($ssql,[$finWOBatchnoId]);
		$rs = $qr->result();
		foreach($rs as $rw){
			$unit = $rw->fst_unit;
			$fdbQty =$rw->fdb_qty;
			$qtyInBasic = $this->msitems_model->getQtyConvertToBasicUnit($rw->fin_item_id,$fdbQty,$unit);
			$totalRmoutHpp += $qtyInBasic * $rw->fdc_hpp;
		}
		
		$totalRmoutReturnHpp = 0;
		$ssql = "SELECT b.* FROM trrmoutreturn a 
			INNER JOIN trrmoutreturnitems b on a.fin_rmout_return_id = b.fin_rmout_return_id  
			where a.fin_wobatchno_id = ? and a.fst_active ='A'";
		$qr = $this->db->query($ssql,[$finWOBatchnoId]);
		$rs = $qr->result();
		foreach($rs as $rw){
			$unit = $rw->fst_unit;
			$fdbQty =$rw->fdb_qty;
			$qtyInBasic = $this->msitems_model->getQtyConvertToBasicUnit($rw->fin_item_id,$fdbQty,$unit);
			$totalRmoutReturnHpp += $qtyInBasic * $rw->fdc_hpp;
		}

		$totalCost = $totalRmoutHpp - $totalRmoutReturnHpp;

		$getTotalLHPInBasicUnit = 0;

		$ssql = "SELECT * FROM trlhp where fin_wobatchno_id = ? and fst_active ='A'";
		$qr = $this->db->query($ssql,[$finWOBatchnoId]);
		$rs = $qr->result();
		foreach($rs as $rw){
			$qtyInBasic = 0;
			//Kilo conver ke basic unit
			if ($rw->fst_unit == "KILO"){
				$qtyInBasic = $rw->fdb_qty / $rw->fdb_gramasi;
			}else{
				$qtyInBasic = $this->msitems_model->getQtyConvertToBasicUnit($rw->fin_item_id,$rw->fdb_qty,$rw->fst_unit);
			}
			$getTotalLHPInBasicUnit += $qtyInBasic;
		}

		$hppLHP =  $totalCost / $getTotalLHPInBasicUnit;
		$ssql = "UPDATE trlhp set fdc_hpp = ? where fin_wobatchno_id = ?";
		$this->db->query($ssql,[$hppLHP,$finWOBatchnoId]);
		throwIfDBError();

		foreach($rs as $rw){            
			$this->trlhp_model->unposting($rw->fin_lhp_id);
			$this->trlhp_model->posting($rw->fin_lhp_id);    
		}

		$ssql = "Update trwobatchno set fbl_closed = 1 where fin_wobatchno_id = ?";
		$this->db->query($ssql,[$finWOBatchnoId]);
		throwIfDBError();

	}
}