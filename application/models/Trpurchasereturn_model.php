<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trpurchasereturn_model extends MY_Model {
	public $tableName = "trpurchasereturn";
	public $pkey = "fin_purchasereturn_id";

	public function __construct(){
		parent:: __construct();
	}

	public function getRules($mode="ADD",$id=0){
		$rules = [];

		$rules[] = [
			'field' => 'fst_purchasereturn_no',
			'label' => 'Purchase Return No',
			'rules' => 'required',
			'errors' => array(
				'required' => '%s tidak boleh kosong',
			)
		];
		$rules[] = [
			'field' => 'fin_supplier_id',
			'label' => 'Supplier',
			'rules' => 'required',
			'errors' => array(
				'required' => '%s tidak boleh kosong',
			)
		];        

		return $rules;
	}

	public function GeneratePurchaseReturnNo($trDate = null) {
		$trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
		$tahun = date("Y/m", strtotime ($trDate));
		$activeBranch = $this->aauth->get_active_branch();
		$branchCode = "";
		if($activeBranch){
			$branchCode = $activeBranch->fst_branch_code;
		}
		$prefix = getDbConfig("purchase_return_prefix") . "/" . $branchCode ."/";
		$query = $this->db->query("SELECT MAX(fst_purchasereturn_no) as max_id FROM trpurchasereturn where fst_purchasereturn_no like '".$prefix.$tahun."%'");
		$row = $query->row_array();

		$max_id = $row['max_id']; 
		
		$max_id1 =(int) substr($max_id,strlen($max_id)-5);
		
		$fst_tr_no = $max_id1 +1;
		
		$max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);
		
		return $max_tr_no;
	}

	public function getListPurchaseFaktur($fin_supplier_id,$isImport){
		//Faktur2 yang belum ada pembayarannya
		$ssql ="select a.fin_lpbpurchase_id,a.fst_lpbpurchase_no,a.fst_curr_code from trlpbpurchase a
			inner join trpo b on a.fin_po_id = b.fin_po_id 
			where a.fdc_total_paid = 0 
			AND b.fbl_is_import = ? AND b.fbl_cost_completed = 1 
			AND a.fin_supplier_id = ? and a.fst_active != 'D' ";
			
		$qr =$this->db->query($ssql,[(boolean) $isImport,(int) $fin_supplier_id]);
		//echo $this->db->last_query();
		$rs= $qr->result();
		return $rs;
	}

	public function getLPBPurchase($finLPBPurchaseId){
				
		$ssql ="SELECT a.fin_item_id,b.fst_item_code,a.fst_custom_item_name,a.fdb_qty,a.fdb_qty_return,a.fst_unit,a.fdc_price,a.fst_disc_item, a.fdc_disc_amount_per_item 
			FROM trlpbpurchaseitems a
			INNER JOIN msitems b on a.fin_item_id = b.fin_item_id  
			WHERE a.fin_lpbpurchase_id = ?";
		$qr = $this->db->query($ssql,[$finLPBPurchaseId]);
		$rs = $qr->result();

		return $rs;
	}

	public function getSummaryReturnByLPBPurchase($finLPBPurchaseId){
		$ssql ="select a.fin_po_detail_id,sum(a.fdb_qty) as fdb_qty_return from trpurchasereturnitems a 
			inner join trpurchasereturn b on a.fin_purchasereturn_id = b.fin_purchasereturn_id 
			where b.fin_lpbpurchase_id = ? and b.fst_active != 'D' 
			group by a.fin_po_detail_id";

		$qr = $this->db->query($ssql,[(int)$finLPBPurchaseId]);
		$rs = $qr->result();
		$result =[];
		foreach($rs as $rw){
			$result[$rw->fin_po_detail_id] = $rw;
		}
		return $result;
	}

	public function getSummaryQtyLPBByLPBPurchase($finLPBPurchaseId){
		$ssql ="select c.fin_po_detail_id,sum(c.fdb_qty) as fdb_qty_lpb from trlpbpurchaseitems a 
			inner join trlpbpurchase b on a.fin_lpbpurchase_id = b.fin_lpbpurchase_id 
			inner join trlpbgudangitems c on a.fin_lpbgudang_id = c.fin_lpbgudang_id
			where b.fin_lpbpurchase_id = ? group by c.fin_po_detail_id";

		$qr = $this->db->query($ssql,[(int)$finLPBPurchaseId]);
		$rs = $qr->result();
		$result =[];
		foreach($rs as $rw){
			$result[$rw->fin_po_detail_id] = $rw;
		}
		return $result;
	}

	public function posting($finPurchaseReturnId){

		$this->load->model("glledger_model");
		//$this->load->model("trinventory_model");

		$ssql ="select * from trpurchasereturn where fin_purchasereturn_id = ?";
		$qr = $this->db->query($ssql,[$finPurchaseReturnId]);
		$dataH = $qr->row_array();

		$ssql ="select * from trpurchasereturnitems where fin_purchasereturn_id = ?";
		$qr = $this->db->query($ssql,[$finPurchaseReturnId]);
		$detailList = $qr->result();



		if($dataH["fbl_non_faktur"] == 0 ){ //Return dengan Faktur
			
			//Update total Return di LPB Purchase        
			$ssql = "update trlpbpurchase set fdc_total_return = fdc_total_return + $dataH[fdc_total] where fin_lpbpurchase_id = $dataH[fin_lpbpurchase_id]";
			$this->db->query($ssql,[]);
			throwIfDBError();
					
			//Update qty return di lpbpurchaseitems
			$ssql ="select * from trpurchasereturnitems where fin_purchasereturn_id = ?";
			$qr = $this->db->query($ssql,[$finPurchaseReturnId]);
			$dataDetails = $qr->result_array();
			foreach($dataDetails as $dataD){
				$ssql ="update trlpbpurchaseitems set fdb_qty_return = fdb_qty_return + ? where fin_item_id = ? and fin_lpbpurchase_id = ?";
				$this->db->query($ssql,[$dataD["fdb_qty"],$dataD["fin_item_id"],$dataH["fin_lpbpurchase_id"]]);
				throwIfDBError();
			}

		}else{ //Return non Faktur

		}

		//posting jurnal
		/**
		 * Hutang / ayat silang
		 * Disc
		 *      Return
		 *      PPN
		 */
		if($dataH["fbl_non_faktur"] == 0 ){
			$accHutang = $dataH["fbl_is_import"] == 1 ? getGLConfig("AP_DAGANG_IMPORT") : getGLConfig("AP_DAGANG_LOKAL");
			$valHutang = 0;        
		}else{
			//jagan di lawan ke hutang, pakai jurnal ayat silang
			$accHutang = getGLConfig("RETUR_PEMBELIAN_BELUM_REALISASI");
			$valHutang = 0;        
		}

		$accDisc = getGLConfig("PURCHASE_DISC");
		$valDisc = 0;
		$accReturn = $dataH["fbl_is_import"] == 1 ? getGLConfig("RETURN_IMPORT") : getGLConfig("RETURN_LOKAL");
		$valReturn = 0;
		$accPpn = getGLConfig("PPN_MASUKAN");
		$valPpn = 0;
		$dataJurnal = [];

		$valDisc = $dataH["fdc_disc_amount"];
		$valPpn = $dataH["fdc_ppn_amount"];
		$valReturn = $dataH["fdc_subttl"];
		$valHutang = $dataH["fdc_total"];
		

		$dataJurnal[] =[ //Hutang
			"fin_branch_id"=>$dataH["fin_branch_id"],
			"fst_account_code"=>$accHutang,
			"fdt_trx_datetime"=>$dataH["fdt_purchasereturn_datetime"],
			"fst_trx_sourcecode"=>"PRT",
			"fin_trx_id"=>$dataH["fin_purchasereturn_id"],
			"fst_trx_no"=>$dataH["fst_purchasereturn_no"],
			"fst_reference"=>null,
			"fdc_debit"=> $valHutang * $dataH["fdc_exchange_rate_idr"],
			"fdc_origin_debit"=>$valHutang,
			"fdc_credit"=>0,
			"fdc_origin_credit"=>0,
			"fst_orgi_curr_code"=>$dataH["fst_curr_code"],
			"fdc_orgi_rate"=>$dataH["fdc_exchange_rate_idr"],
			"fst_no_ref_bank"=>null,
			"fst_profit_cost_center_code"=>null,
			"fin_relation_id"=> $dataH["fbl_non_faktur"] == 0  ? $dataH["fin_supplier_id"] : null,
			"fst_active"=>"A"
		];
		$dataJurnal[] =[ //Disc
			"fin_branch_id"=>$dataH["fin_branch_id"],
			"fst_account_code"=>$accDisc,
			"fdt_trx_datetime"=>$dataH["fdt_purchasereturn_datetime"],
			"fst_trx_sourcecode"=>"PRT",
			"fin_trx_id"=>$dataH["fin_purchasereturn_id"],
			"fst_trx_no"=>$dataH["fst_purchasereturn_no"],
			"fst_reference"=>null,
			"fdc_debit"=> $valDisc * $dataH["fdc_exchange_rate_idr"],
			"fdc_origin_debit"=>$valDisc,
			"fdc_credit"=>0,
			"fdc_origin_credit"=>0,
			"fst_orgi_curr_code"=>$dataH["fst_curr_code"],
			"fdc_orgi_rate"=>$dataH["fdc_exchange_rate_idr"],
			"fst_no_ref_bank"=>null,
			"fst_profit_cost_center_code"=>null,
			"fin_relation_id"=>null,
			"fst_active"=>"A"
		];
		$dataJurnal[] =[ //Return
			"fin_branch_id"=>$dataH["fin_branch_id"],
			"fst_account_code"=>$accReturn,
			"fdt_trx_datetime"=>$dataH["fdt_purchasereturn_datetime"],
			"fst_trx_sourcecode"=>"PRT",
			"fin_trx_id"=>$dataH["fin_purchasereturn_id"],
			"fst_trx_no"=>$dataH["fst_purchasereturn_no"],
			"fst_reference"=>null,
			"fdc_debit"=> 0,
			"fdc_origin_debit"=>0,
			"fdc_credit"=>$valReturn * $dataH["fdc_exchange_rate_idr"],
			"fdc_origin_credit"=>$valReturn,
			"fst_orgi_curr_code"=>$dataH["fst_curr_code"],
			"fdc_orgi_rate"=>$dataH["fdc_exchange_rate_idr"],
			"fst_no_ref_bank"=>null,
			"fst_profit_cost_center_code"=>null,
			"fin_relation_id"=>null,
			"fst_active"=>"A"
		];
		$dataJurnal[] =[ //PPN
			"fin_branch_id"=>$dataH["fin_branch_id"],
			"fst_account_code"=>$accPpn,
			"fdt_trx_datetime"=>$dataH["fdt_purchasereturn_datetime"],
			"fst_trx_sourcecode"=>"PRT",
			"fin_trx_id"=>$dataH["fin_purchasereturn_id"],
			"fst_trx_no"=>$dataH["fst_purchasereturn_no"],
			"fst_reference"=>null,
			"fdc_debit"=> 0,
			"fdc_origin_debit"=>0,
			"fdc_credit"=>$valPpn * $dataH["fdc_exchange_rate_idr"],
			"fdc_origin_credit"=>$valPpn,
			"fst_orgi_curr_code"=>$dataH["fst_curr_code"],
			"fdc_orgi_rate"=>$dataH["fdc_exchange_rate_idr"],
			"fst_no_ref_bank"=>null,
			"fst_profit_cost_center_code"=>null,
			"fin_relation_id"=>null,
			"fst_active"=>"A"
		];

		$result = $this->glledger_model->createJurnal($dataJurnal);       
		
		//Update kartu stock - Inventory - buat terpisah
		/*
		foreach($detailList as $dataD){
			$dataStock = [
				//`fin_rec_id`, 
				"fin_warehouse_id"=>$dataH["fin_warehouse_id"],
				"fdt_trx_datetime"=>$dataH["fdt_purchasereturn_datetime"],
				"fst_trx_code"=>"PRT", 
				"fin_trx_id"=>$finPurchaseReturnId, 
				"fin_trx_detail_id"=>$dataD->fin_rec_id, 
				"fst_trx_no"=>$dataH["fst_purchasereturn_no"], 
				"fst_referensi"=>null, 
				"fin_item_id"=>$dataD->fin_item_id, 
				"fst_unit"=>$dataD->fst_unit, 
				"fdb_qty_in"=>0,
				"fdb_qty_out"=>$dataD->fdb_qty, 
				"fdc_price_in"=>(float) $dataD->fdc_price - (float) calculateDisc($dataD->fst_disc_item,$dataD->fdc_price) , 
				"fst_active"=>"A" 
			];

			$this->trinventory_model->insert($dataStock);
		}         
		*/

		return $result;
	}

	public function unposting($finPurchaseReturnId){
		$this->load->model("glledger_model");
		$this->load->model("trinventory_model");

		$ssql ="select * from trpurchasereturn where fin_purchasereturn_id = ?";
		$qr = $this->db->query($ssql,[$finPurchaseReturnId]);
		$dataH = $qr->row();
		if ($dataH == null){
			throw new CustomException(lang("ID purchase return tidak ditemukan!"),3003,"FAILED",["fin_purchasereturn_id"=>$finPurchaseReturnId]);
		}


		$ssql ="select * from trpurchasereturnitems where fin_purchasereturn_id = ?";
		$qr = $this->db->query($ssql,[$finPurchaseReturnId]);
		$dataDetails = $qr->result();
		

		if ($dataH->fbl_non_faktur == 0){
			//Update total Return di LPB Purchase        
			$ssql = "update trlpbpurchase set fdc_total_return = fdc_total_return - ? where fin_lpbpurchase_id = ?";
			$this->db->query($ssql,[$dataH->fdc_total,$dataH->fin_lpbpurchase_id]);
			throwIfDBError();

			//return qty_return
			foreach($dataDetails as $dataD){
				$ssql ="update trlpbpurchaseitems set fdb_qty_return = fdb_qty_return - ? where fin_item_id = ? and fin_lpbpurchase_id = ?";
				$this->db->query($ssql,[$dataD->fdb_qty,$dataD->fin_item_id,$dataH->fin_lpbpurchase_id]);
				throwIfDBError();
			}
		}

		$this->glledger_model->cancelJurnal("PRT",$finPurchaseReturnId);   
		//$result = $this->trinventory_model->deleteByCodeId("PRT",$finPurchaseReturnId);        
	}


	public function getDataById($finPurchaseReturnId){
		$ssql = "SELECT a.*,b.fst_lpbpurchase_no FROM " .$this->tableName. " a  
			LEFT JOIN trlpbpurchase b ON a.fin_lpbpurchase_id = b.fin_lpbpurchase_id 
			WHERE a.fin_purchasereturn_id = ? AND a.fst_active != 'D'";

		$qr = $this->db->query($ssql, [$finPurchaseReturnId]);
		$dataH = $qr->row();

		$ssql = "SELECT a.*,ifnull(b.fdb_qty,0) as fdb_qty_lpb, ifnull(b.fdb_qty_return,0) as fdb_qty_return ,c.fst_item_code from trpurchasereturnitems a 
			LEFT JOIN trlpbpurchaseitems b on a.fin_item_id = b.fin_item_id AND fin_lpbpurchase_id = ?
			INNER JOIN msitems c on a.fin_item_id = c.fin_item_id                         
			where a.fin_purchasereturn_id = ?";

		$qr = $this->db->query($ssql,[$dataH->fin_lpbpurchase_id,$finPurchaseReturnId]);        
		$dataDetails = $qr->result();

		$data = [
			"purchasereturn" => $dataH,
			"purchasereturn_details" => $dataDetails
		];

		return $data;
	}

	public function getDataHeaderById($finPurchaseReturnId){
		$ssql = "SELECT * FROM " .$this->tableName. "
			WHERE fin_purchasereturn_id = ?";
		$qr = $this->db->query($ssql, [$finPurchaseReturnId]);
		$dataH = $qr->row();
		return $dataH;
	}
	
	public function isEditable($finPurchaseReturnId){
	   
		/**
		 * FALSE CONDITION
		 * 1. Kalau sudah dibayar kan returnnya ngak bisa di edit
		 * 
		 */

		$dataH = $this->getSimpleDataById($finPurchaseReturnId);
		if ($dataH->fdc_total_claimed >0){
			throw new CustomException("Return Already Paid !","3003","FAILED",[]);
		}
		
		$resp =["status"=>"SUCCESS","message"=>""];
		return $resp;
	}
	public function update($data){
		//Delete Field yang tidak boleh berubah
		parent::update($data);        
	}

	public function delete($finPurchaseReturnId,$softDelete = true,$data=null){
		if ($softDelete){
			$ssql ="update trpurchasereturnitems set fst_active ='D' where fin_purchasereturn_id = ?";
			$this->db->query($ssql,[$finPurchaseReturnId]);
		}else{
			$ssql ="delete from trpurchasereturnitems where fin_purchasereturn_id = ?";
			$this->db->query($ssql,[$finPurchaseReturnId]);            
		}
		parent::delete($finPurchaseReturnId,$softDelete,$data);

		return ["status" => "SUCCESS","message"=>""];
	}

	public function deleteDetail($finPurchaseReturnId){
		$ssql ="delete from trpurchasereturnitems where fin_purchasereturn_id = ?";
		$this->db->query($ssql,[$finPurchaseReturnId]);
		throwIfDBError();        
	}

	public function updateClosedStatus($finPurchaseReturnId){

		$ssql = "select * from trpurchasereturnitems where fin_purchasereturn_id = ? and fdb_qty > fdb_qty_out";
		$qr = $this->db->query($ssql,$finPurchaseReturnId);
		if ($qr->row() == null){
			//Transaksi Return Completed
			$ssql = "update trpurchasereturn set fdt_closed_datetime = now() , fbl_is_closed = 1, fst_closed_notes = 'AUTO - ".date("Y-m-d H:i:s") ."' where fin_purchasereturn_id = ?";
			$this->db->query($ssql,[$finPurchaseReturnId]);
		}else{
			$ssql = "update trpurchasereturn set fdt_closed_datetime = null , fbl_is_closed = 0, fst_closed_notes = null where fin_purchasereturn_id = ?";
			$this->db->query($ssql,[$finPurchaseReturnId]);
		}
	}

	public function getDataVoucher($finReturnId){
		$ssql ="SELECT a.*,b.fst_relation_name AS fst_supplier_name,c.fst_curr_name,
			d.fst_lpbpurchase_no,d.fdt_lpbpurchase_datetime 
			FROM trpurchasereturn a
			INNER JOIN msrelations b ON a.fin_supplier_id = b.fin_relation_id
			INNER JOIN mscurrencies c ON a.fst_curr_code = c.fst_curr_code 
			LEFT JOIN trlpbpurchase d ON a.fin_lpbpurchase_id = d.fin_lpbpurchase_id
			WHERE fin_purchasereturn_id = ?";
			
		$qr = $this->db->query($ssql,[$finReturnId]);
		$header = $qr->row_array();
		
		$ssql = "SELECT a.*,b.fst_item_code FROM trpurchasereturnitems a
			INNER JOIN msitems b on a.fin_item_id = b.fin_item_id
			WHERE fin_purchasereturn_id = ?";

		$qr = $this->db->query($ssql,[$finReturnId]);


		$details = $qr->result_array();

		return [
			"header"=>$header,
			"details"=>$details
		];
	}
	

}


