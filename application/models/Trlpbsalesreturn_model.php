<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trlpbsalesreturn_model extends MY_Model {
	public $tableName = "trlpbsalesreturn";
	public $pkey = "fin_lpbsalesreturn_id";

	public function __construct(){
		parent:: __construct();
	}

	public function getRules($mode="ADD",$id=0){
		$rules = [];

		$rules[] = [
			'field' => 'fst_lpbsalesreturn_no',
			'label' => 'Penerimaan Sales Return No',
			'rules' => 'required',
			'errors' => array(
				'required' => '%s tidak boleh kosong',
			)
		];
		$rules[] = [
			'field' => 'fin_customer_id',
			'label' => 'Customer',
			'rules' => 'required',
			'errors' => array(
				'required' => '%s tidak boleh kosong',
			)
		];        

		return $rules;
	}



	public function getDataById($finLPBSalesReturnId){


		$ssql = "SELECT a.*,b.fst_relation_name,c.fst_warehouse_name FROM trlpbsalesreturn a
			INNER JOIN msrelations b on a.fin_customer_id = b.fin_relation_id
			INNER JOIN mswarehouse c on a.fin_warehouse_id = c.fin_warehouse_id
			WHERE a.fin_lpbsalesreturn_id = ? and a.fst_active ='A'";

		$qr = $this->db->query($ssql, [$finLPBSalesReturnId]);
		//var_dump($this->db->error());
		//die();
		$dataH = $qr->row();
		if ($dataH == null){
			return null;
		}


		$ssql = "SELECT a.*,b.fst_item_code,b.fst_item_name,b.fbl_is_batch_number,b.fbl_is_serial_number,
			c.fdc_conv_to_basic_unit,d.fst_inv_no 
			FROM trlpbsalesreturnitems a 
			INNER JOIN msitems b on a.fin_item_id = b.fin_item_id
			INNER JOIN msitemunitdetails c on a.fin_item_id = c.fin_item_id and a.fst_unit = c.fst_unit
			LEFT JOIN trinvoice d on a.fin_inv_id = d.fin_inv_id
			WHERE a.fin_lpbsalesreturn_id = ?";
		
		$qr = $this->db->query($ssql,[$finLPBSalesReturnId]);
		$dataDetails = $qr->result();

		for($i=0;$i<sizeof($dataDetails);$i++){
			$data=$dataDetails[$i];
			$data->fst_basic_unit = $this->msitems_model->getBasicUnit($data->fin_item_id);
			$dataDetails[$i] = $data;			
		}

		$data = [
			"header" => $dataH,
			"details" => $dataDetails
		];

		return $data;
	}

	
	public function GenerateNo($trDate = null) {
		$trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
		$tahun = date("Y/m", strtotime ($trDate));
		$activeBranch = $this->aauth->get_active_branch();
		$branchCode = "";
		if($activeBranch){
			$branchCode = $activeBranch->fst_branch_code;
		}

		$prefix = getDbConfig("penerimaan_sales_return_prefix") . "/" . $branchCode ."/";
		$query = $this->db->query("SELECT MAX(fst_lpbsalesreturn_no) as max_id FROM trlpbsalesreturn where fst_lpbsalesreturn_no like '".$prefix.$tahun."%'");
		$row = $query->row_array();

		$max_id = $row['max_id']; 
		
		$max_id1 =(int) substr($max_id,strlen($max_id)-5);
		
		$fst_tr_no = $max_id1 +1;
		
		$max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);
		
		return $max_tr_no;
	}
	
	
	public function posting($finPenerimaanSalesReturnId){
		//$this->load->model("glledger_model");
		$this->load->model("trinventory_model");		
		$dataH = parent::getSimpleDataById($finPenerimaanSalesReturnId);

		$ssql = "SELECT * FROM trlpbsalesreturnitems where fin_lpbsalesreturn_id = ? and fst_active ='A'";
		$qr=$this->db->query($ssql,[$finPenerimaanSalesReturnId]);
		$rs = $qr->result();

		//Update invoice items
		foreach($rs as $rw){
			if ($rw->fin_inv_id != null){
				$ssql = "SELECT * from trinvoiceitems where fin_inv_id = ? and fin_item_id = ? and fst_unit = ?";
				$qr = $this->db->query($ssql,[$rw->fin_inv_id,$rw->fin_item_id,$rw->fst_unit]);
				$rwInv = $qr->row();
				if ($rwInv == null){
					//Invalid
					throw new CustomException(lang("Item yang di tunjuk tidak ada dalam faktur yang ditentukan!"),3003,"FAILED",[]);
				}

				if ($rwInv->fdb_qty < $rwInv->fdb_qty_return +  $rw->fdb_qty){
					throw new CustomException(lang("Qty retur tidak boleh melebih qty faktur yang ditentukan!"),3003,"FAILED",[]);
				}
				$ssql = "UPDATE trinvoiceitems set fdb_qty_return = fdb_qty_return + ? where fin_rec_id = ?";
				$this->db->query($ssql,[$rw->fdb_qty,$rwInv->fin_rec_id]);			
			}					
		}

		//Update Inventory
		foreach($rs as $dataD){			
			$data = [
				"fin_warehouse_id"=>$dataH->fin_warehouse_id,
				"fdt_trx_datetime"=>$dataH->fdt_lpbsalesreturn_datetime,
				"fst_trx_code"=>"SRT", //SALES RETURN
				"fin_trx_id"=>$dataH->fin_lpbsalesreturn_id,
				"fst_trx_no"=>$dataH->fst_lpbsalesreturn_no,
				"fin_trx_detail_id"=>$dataD->fin_rec_id,
				"fst_referensi"=>$dataH->fst_memo,
				"fin_item_id"=>$dataD->fin_item_id,
				"fst_unit"=>$dataD->fst_unit,
				"fdb_qty_in"=>$dataD->fdb_qty,
				"fdb_qty_out"=>0,
				"fdc_price_in"=>1,
				"fbl_price_in_auto"=>true,
				"fst_active"=>"A"
			];
			$this->trinventory_model->insert($data);

			$dataSerial = [
				"fin_warehouse_id"=>$dataH->fin_warehouse_id,
				"fin_item_id"=>$dataD->fin_item_id,
				"fst_unit"=>$dataD->fst_unit,
				"fst_serial_number_list"=>$dataD->fst_serial_number_list,
				"fst_batch_no"=>$dataD->fst_batch_number,
				"fst_trans_type"=>"SRT", //SALES RETURN 
				"fin_trans_id"=>$dataH->fin_lpbsalesreturn_id,
				"fst_trans_no"=>$dataH->fst_lpbsalesreturn_no,
				"fin_trans_detail_id"=>$dataD->fin_rec_id,
				"fdb_qty"=>$dataD->fdb_qty,
				"in_out"=>"IN",
			];			
			$this->trinventory_model->insertSerial($dataSerial);
		}


		
	}

	public function unposting($finLPBSalesReturnId){
		$this->load->model("trinventory_model");

		$ssql = "SELECT * FROM trlpbsalesreturnitems where fin_lpbsalesreturn_id = ? and fst_active ='A'";
		$qr=$this->db->query($ssql,[$finLPBSalesReturnId]);
		$rs = $qr->result();

		//Update invoice items
		foreach($rs as $rw){
			if ($rw->fin_inv_id != null){
				$ssql = "SELECT * from trinvoiceitems where fin_inv_id = ? and fin_item_id = ? and fst_unit = ?";
				$qr = $this->db->query($ssql,[$rw->fin_inv_id,$rw->fin_item_id,$rw->fst_unit]);
				$rwInv = $qr->row();
				if ($rwInv == null){
					//Invalid
					throw new CustomException(lang("Item yang di tunjuk tidak ada dalam faktur yang ditentukan!"),3003,"FAILED",[]);
				}

				$ssql = "UPDATE trinvoiceitems set fdb_qty_return = fdb_qty_return - ? where fin_rec_id = ?";
				$this->db->query($ssql,[$rw->fdb_qty,$rwInv->fin_rec_id]);			
			}					
		}
		


		$this->trinventory_model->deleteByCodeId("SRT",$finLPBSalesReturnId);
        
		//Delete itemdetails
		$this->trinventory_model->deleteInsertSerial("SRT",$finLPBSalesReturnId);


	}

	
	public function isEditable($finLPBSalesReturnId){
	   
		/**
		 * Tidak bisa di rubah kalau sudah di buat memo return
		 */
		//throw new CustomException(lang("Penerimaa telah digunakan pada memo retur !"),3003,"FAILED",[]);
	}

	public function deleteDetail($finLPBSalesReturnId){
		$ssql ="DELETE FROM trlpbsalesreturnitems WHERE fin_lpbsalesreturn_id = ?";
		$this->db->query($ssql,[$finLPBSalesReturnId]);
		throwIfDBError();
	}

	public function delete($finLPBSalesReturnId,$softDelete = true,$data=null){
		if ($softDelete){
			$ssql ="update trlpbsalesreturnitems set fst_active ='D' where fin_lpbsalesreturn_id = ?";
			$this->db->query($ssql,[$finLPBSalesReturnId]);
		}else{
			$ssql ="delete from trlpbsalesreturnitems where fin_lpbsalesreturn_id = ?";
			$this->db->query($ssql,[$finLPBSalesReturnId]);            
		}
		parent::delete($finLPBSalesReturnId,$softDelete,$data);

		return ["status" => "SUCCESS","message"=>""];
	}	
}


