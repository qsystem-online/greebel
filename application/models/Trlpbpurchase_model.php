<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trlpbpurchase_model extends MY_Model {
	public $tableName = "trlpbpurchase";
	public $pkey = "fin_lpbpurchase_id";

	public function __construct(){
		parent:: __construct();
	}    

	public function getRules($mode="ADD",$id=0){
		$rules = [];

		$rules[] = [
			'field' => 'fst_lpbpurchase_no',
			'label' => 'No Faktur',
			'rules' => 'required',
			'errors' => array(
				'required' => '%s tidak boleh kosong',
			)
		];
		$rules[] = [
			'field' => 'fdc_exchange_rate_idr',
			'label' => 'Exchage rate',
			'rules' => 'greater_than[0]',
			'errors' => array(
				'greater_than' => '%s harus diisi',
			)
		];


		return $rules;
	}

	public function getDataById($finLPBPurchaseId){
		$ssql = "SELECT a.*,b.fst_po_no,b.fdt_po_datetime,fdc_downpayment_paid,fdc_downpayment_claimed,c.fst_relation_name as fst_supplier_name FROM trlpbpurchase a 
			INNER JOIN trpo b ON a.fin_po_id = b.fin_po_id 
			INNER JOIN msrelations  c ON a.fin_supplier_id = c.fin_relation_id 
			WHERE fin_lpbpurchase_id = ? and a.fst_active != 'D'";


		$qr = $this->db->query($ssql, [$finLPBPurchaseId]);
		$rwLPBPurchase = $qr->row();

		if ($rwLPBPurchase == null){
			return null;
		}

		$ssql = "select a.*,b.fst_lpbgudang_no from trlpbpurchasedetails a 
			INNER JOIN trlpbgudang b on a.fin_lpbgudang_id = b.fin_lpbgudang_id 
			where a.fin_lpbpurchase_id = ?";
		$qr = $this->db->query($ssql,[$finLPBPurchaseId]);        
		$rsLPBPurchaseDetails = $qr->result();

			
		$ssql = "select a.* from trlpbpurchaseitems a 
			where a.fin_lpbpurchase_id = ?";

		$qr = $this->db->query($ssql,[$finLPBPurchaseId]);        
		$rsLPBPurchaseItems = $qr->result();

		$data = [
			"lpbPurchase" => $rwLPBPurchase,
			"lpbPurchaseDetails" => $rsLPBPurchaseDetails,
			"lpbPurchaseItems" => $rsLPBPurchaseItems,
		];
		return $data;
	}    

	
	public function generateLPBPurchaseNo($trDate = null) {
		$trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
		$tahun = date("Y/m", strtotime ($trDate));
		$activeBranch = $this->aauth->get_active_branch();
		$branchCode = "";
		if($activeBranch){
			$branchCode = $activeBranch->fst_branch_code;
		}

		$prefix = getDbConfig("lpb_pembelian_prefix");


		//$query = $this->db->query("SELECT MAX(fst_po_no) as max_id FROM $table where $field like '".$prefix.$tahun."%'");
		$query = $this->db->query("SELECT MAX(fst_lpbpurchase_no) as max_id FROM trlpbpurchase where fst_lpbpurchase_no like '".$prefix."/%/".$tahun."%'");

		$row = $query->row_array();

		$max_id = $row['max_id']; 
		
		$max_id1 =(int) substr($max_id,strlen($max_id)-5);
		
		$fst_tr_no = $max_id1 +1;
		
		$max_tr_no = $prefix."/". $branchCode .'/' .$tahun.'/'.sprintf("%05s",$fst_tr_no);
		
		return $max_tr_no;
	}

	public function getPOList(){
		$ssql = "select distinct a.fin_trans_id as fin_po_id,b.fst_po_no,b.fin_supplier_id,c.fst_relation_name as fst_supplier_name 
			FROM trlpbgudang a 
			INNER JOIN trpo b on a.fin_trans_id = b.fin_po_id 
			INNER JOIN msrelations c on b.fin_supplier_id = c.fin_relation_id 
			WHERE a.fst_lpb_type = 'PO' and a.fin_lpbpurchase_id IS NULL and a.fst_active != 'D' ";
		$qr = $this->db->query($ssql,[]);
		$rs = $qr->result();
		return $rs;
		
	}

	public function getPODetail($finPOId){
		$ssql = "select a.*,b.fst_relation_name as fst_supplier_name from trpo a
			INNER JOIN msrelations b on a.fin_supplier_id = b.fin_relation_id
			WHERE fin_po_id = ?";
		$qr = $this->db->query($ssql,[$finPOId]);
		$po=$qr->row();


		$ssql = "SELECT fin_lpbgudang_id,fst_lpbgudang_no,fdt_lpbgudang_datetime  FROM trlpbgudang 
			WHERE fst_lpb_type= 'PO' and fin_trans_id = ? AND fin_lpbpurchase_id IS NULL AND fst_active != 'D'";
		$qr = $this->db->query($ssql,[$finPOId]);
		$poDetails=$qr->result();

		$result =[
			"po"=>$po,
			"lpbgudang_list"=>$poDetails,
		];

		return $result;

	}

	public function getListItemByLPBGudangIds($finLPBGudangIds){
		
		$ssql ="SELECT b.fin_item_id,c.fst_item_code,b.fst_custom_item_name,b.fst_unit,b.fdc_price,b.fst_disc_item,b.fdc_disc_amount_per_item,SUM(a.fdb_qty) as fdb_qty_total 
			FROM trlpbgudangitems a 
			INNER JOIN trpodetails b ON a.fin_trans_detail_id = b.fin_po_detail_id 
			INNER JOIN msitems c ON b.fin_item_id = c.fin_item_id 
			WHERE fin_lpbgudang_id IN ? 
			GROUP BY b.fin_item_id,c.fst_item_code,b.fst_custom_item_name,b.fst_unit,b.fdc_price,b.fst_disc_item,b.fdc_disc_amount_per_item";
		
		$qr = $this->db->query($ssql,[$finLPBGudangIds]);
		$rs = $qr->result();
		return $rs;
	}

	public function posting($finLPBPurchaseId){
		/**
		 * +Update field fin_lpbpurchase_id di trlpbgudang
		 * +Update claimed downpayment di PO
		 * +Jurnal
		 */
		$this->load->model("glledger_model");
		$this->load->model("trpurchaserequestprocess_model");
		

		$ssql = "SELECT a.*,b.fbl_is_import,b.fdc_downpayment,b.fdc_downpayment_paid,b.fbl_dp_inc_ppn 
			FROM trlpbpurchase a 
			INNER JOIN trpo b ON a.fin_po_id = b.fin_po_id 
			WHERE fin_lpbpurchase_id = ?";

		$qr = $this->db->query($ssql,[$finLPBPurchaseId]);
		$dataH = $qr->row();        
		if($dataH == null){
			throw new CustomException(lang("Invalid LPB Purchase ID"),3003,"FAILED",null);
		}
		
		$ssql = "SELECT * from trlpbpurchasedetails where fin_lpbpurchase_id = ?";
		$qr= $this->db->query($ssql,[$finLPBPurchaseId]);
		$rs = $qr->result();
		foreach($rs as $rw){
			//Update field fin_lpbpurchase_id di trlpbgudang
			$ssql ="update trlpbgudang set fin_lpbpurchase_id = ? where fin_lpbgudang_id = ?";
			$this->db->query($ssql,[$finLPBPurchaseId,$rw->fin_lpbgudang_id]);
			throwIfDBError();
		}

		//Update claimed downpayment di PO
		$ssql = "update trpo set fdc_downpayment_claimed = fdc_downpayment_claimed + " . $dataH->fdc_downpayment_claim . " where fin_po_id = ?";
		$this->db->query($ssql,[$dataH->fin_po_id]);
		throwIfDBError();
				

		
		//JURNAL 
		$dataJurnal= [];
		if ($dataH->fbl_is_import){
			$glAccount = getGLConfig("PURCHASE_IMPORT");
			$dpGlAccount = getGLConfig("DP_OUT_IMPORT");
			$apGlAccount = getGLConfig("AP_DAGANG_IMPORT");
		}else{
			$glAccount = getGLConfig("PURCHASE_LOKAL");
			$dpGlAccount = getGLConfig("DP_OUT_LOKAL");
			$apGlAccount = getGLConfig("AP_DAGANG_LOKAL");
		}
		$purchaseAccount = $glAccount;
		$purchaseDiscAccount = getGLConfig("PURCHASE_DISC");

		//fin_item_type_id : 5 Logistic, Other is Merchandise
		$ssql = "SELECT 
				b.fin_item_type_id,
				b.fbl_stock,
				d.fin_pcc_id,
				c.fin_item_group_id,
				sum((a.fdb_qty * a.fdc_price)) as fdc_total,
				sum((a.fdb_qty * a.fdc_disc_amount_per_item)) as fdc_ttl_disc_amount 
			FROM trlpbpurchaseitems a
			INNER JOIN msitems b on a.fin_item_id = b.fin_item_id
			INNER JOIN msgroupitems c on b.fin_item_group_id = c.fin_item_group_id 
			INNER JOIN msgroupitems d on SUBSTRING_INDEX(c.fst_tree_id, '.', 1) = d.fin_item_group_id
			WHERE a.fin_lpbpurchase_id = ? 
			GROUP BY d.fin_pcc_id";
		$qr = $this->db->query($ssql,[$finLPBPurchaseId]);
		$rs =$qr->result(); 		
		$dataTmp = [];
				

		foreach($rs as $rw){
			//Pembelian/Persediaan/Biaya > Hutang 
			if ($rw->fin_item_type_id == 5 ){
				//Logistik (stock or non stock ?)
				$postAcc = "";
				if ($rw->fbl_stock){
					//persediaan supply
					$postAcc = getLogisticGLConfig($rw->fin_item_group_id,"PERSEDIAAN");
				}else{
					//Biaya
					//Pending Tunggu benerin PR dan PO
					$rwPRProses = $this->trpurchaserequestprocess_model->getHeaderByPO($dataH->fin_po_id);
					if ($rwPRProses->fst_stock_cost_type == "NONSTOCK_PABRIKASI"){
						$postAcc = getLogisticGLConfig($rw->fin_item_group_id,"BIAYA_PABRIKASI");;
					}else if ($rwPRProses->fst_stock_cost_type == "NONSTOCK_UMUM"){
						$postAcc = getLogisticGLConfig($rw->fin_item_group_id,"BIAYA_UMUM");;
					}
				}

				if (isset($dataTmp[$postAcc][$rw->fin_pcc_id])){
					$dataTmp[$postAcc][$rw->fin_pcc_id] = [
						"debet"=> $dataTmp[$purchaseAccount][$rw->fin_pcc_id]["debet"] + $rw->fdc_total,
						"credit"=> 0
					];
				}else{
					$dataTmp[$postAcc][$rw->fin_pcc_id] = [
						"debet"=> $rw->fdc_total,
						"credit"=> 0
					];
				}

			}else{
				//Non Logistic
				if (isset($dataTmp[$purchaseAccount][$rw->fin_pcc_id])){
					//$dataTmp[$purchaseAccount][$rw->fin_pcc_id] += $rw->fdc_total;
					$dataTmp[$purchaseAccount][$rw->fin_pcc_id] = [
						"debet"=> $dataTmp[$purchaseAccount][$rw->fin_pcc_id]["debet"] + $rw->fdc_total,
						"credit"=> 0
					];
				}else{
					$dataTmp[$purchaseAccount][$rw->fin_pcc_id] = [
						"debet"=> $rw->fdc_total,
						"credit"=> 0
					];
				}
			}

			//Disc > Hutang
			if (isset($dataTmp[$purchaseDiscAccount][$rw->fin_pcc_id])){
				$dataTmp[$purchaseDiscAccount][$rw->fin_pcc_id] = [
					"debet"=>0,
					"credit"=> $dataTmp[$purchaseDiscAccount][$rw->fin_pcc_id]["credit"] + $rw->fdc_ttl_disc_amount
				];

			}else{
				$dataTmp[$purchaseDiscAccount][$rw->fin_pcc_id] = [
					"debet"=>0,
					"credit"=>$rw->fdc_ttl_disc_amount
				];
			}			 
		}

		

		foreach($dataTmp as $accCode=>$arrAcc){
			foreach($arrAcc as $pccId=>$arrPcc){
				$dataJurnal[] =[
					"fin_branch_id"=>$dataH->fin_branch_id,
					"fst_account_code"=>$accCode,
					"fdt_trx_datetime"=>date("Y-m-d H:i:s"),
					"fst_trx_sourcecode"=>"PINV", //PURCHASE INVOICE
					"fin_trx_id"=>$finLPBPurchaseId,
					"fst_reference"=>null,
					"fdc_debit"=> $arrPcc["debet"]* $dataH->fdc_exchange_rate_idr,
					"fdc_origin_debit"=>$arrPcc["debet"],
					"fdc_credit"=> $arrPcc["credit"] * $dataH->fdc_exchange_rate_idr,
					"fdc_origin_credit"=> $arrPcc["credit"], 
					"fst_orgi_curr_code"=>$dataH->fst_curr_code,
					"fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
					"fst_no_ref_bank"=>null,
					"fin_pcc_id"=> ($pccId == "") ? NULL:$pccId, 
					"fin_relation_id"=>null,
					"fst_active"=>"A",
					"fst_info"=>""
				];
			}
		}

		

		   

		/*
		$ssql = "SELECT d.fin_pcc_id,
				sum(a.fdb_qty * a.fdc_price) as fdc_total,
				sum(a.fdb_qty * a.fdc_disc_amount_per_item) as fdc_ttl_disc_amount 
			FROM trlpbpurchaseitems a
			INNER JOIN msitems b on a.fin_item_id = b.fin_item_id
			INNER JOIN msgroupitems c on b.fin_item_group_id = c.fin_item_group_id 
			INNER JOIN msgroupitems d on SUBSTRING_INDEX(c.fst_tree_id, '.', 1) = d.fin_item_group_id
			WHERE a.fin_lpbpurchase_id = ? 
			GROUP BY d.fin_pcc_id";
		$qr = $this->db->query($ssql,[$finLPBPurchaseId]);
		$rs =$qr->result();      

		foreach($rs as $rw){                    
			//PEMBELIAN
			$dataJurnal[] =[
				"fin_branch_id"=>$dataH->fin_branch_id,
				"fst_account_code"=>$glAccount,
				"fdt_trx_datetime"=>date("Y-m-d H:i:s"),
				"fst_trx_sourcecode"=>"PINV", //PURCHASE INVOICE
				"fin_trx_id"=>$finLPBPurchaseId,
				"fst_reference"=>$dataH->fst_memo,
				"fdc_debit"=> $rw->fdc_total * $dataH->fdc_exchange_rate_idr, //$dataH->fdc_subttl * $dataH->fdc_exchange_rate_idr,
				"fdc_origin_debit"=> $rw->fdc_total, //$dataH->fdc_subttl,
				"fdc_credit"=>0,
				"fdc_origin_credit"=>0,
				"fst_orgi_curr_code"=>$dataH->fst_curr_code,
				"fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
				"fst_no_ref_bank"=>null,
				"fin_pcc_id"=>$rw->fin_pcc_id,
				"fin_relation_id"=>null,
				"fst_active"=>"A",
				"fst_info"=>"PEMBELIAN"
			];

			//DISC
			$dataJurnal[] =[
				"fin_branch_id"=>$dataH->fin_branch_id,
				"fst_account_code"=>getGLConfig("PURCHASE_DISC"),
				"fdt_trx_datetime"=>date("Y-m-d H:i:s"),
				"fst_trx_sourcecode"=>"PINV", //PURCHASE INVOICE
				"fin_trx_id"=>$finLPBPurchaseId,
				"fst_reference"=>null,
				"fdc_debit"=> 0,
				"fdc_origin_debit"=>0,
				"fdc_credit"=> $rw->fdc_ttl_disc_amount * $dataH->fdc_exchange_rate_idr, //$dataH->fdc_disc_amount * $dataH->fdc_exchange_rate_idr ,
				"fdc_origin_credit"=> $rw->fdc_ttl_disc_amount, //$dataH->fdc_disc_amount,
				"fst_orgi_curr_code"=>$dataH->fst_curr_code,
				"fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
				"fst_no_ref_bank"=>null,
				"fin_pcc_id"=>$rw->fin_pcc_id,
				"fin_relation_id"=>null,
				"fst_active"=>"A",
				"fst_info"=>"DISC"
			];                     
		}
		*/




		//PPN
		//APAKAH DP SUDAH ADA UNSUR PPN ATAU TIDAK        
		if ($dataH->fbl_dp_inc_ppn){
			$ttlPpn = $dataH->fdc_ppn_amount;            
			$dpClaim = $dataH->fdc_downpayment_claim;
			$dpClaim = $dpClaim / (1 + $dataH->fdc_ppn_percent / 100);
			$ttlPpn -= floatval($dataH->fdc_downpayment_claim) - $dpClaim;

		}else{
			$ttlPpn = $dataH->fdc_ppn_amount;
			$dpClaim = $dataH->fdc_downpayment_claim;
		}
		$dataJurnal[] =[
			"fin_branch_id"=>$dataH->fin_branch_id,
			"fst_account_code"=>getGLConfig("PPN_MASUKAN"),
			"fdt_trx_datetime"=>date("Y-m-d H:i:s"),
			"fst_trx_sourcecode"=>"PINV", //PURCHASE INVOICE
			"fin_trx_id"=>$finLPBPurchaseId,
			"fst_reference"=>$dataH->fst_memo,
			"fdc_debit"=> $ttlPpn * $dataH->fdc_exchange_rate_idr,
			"fdc_origin_debit"=>$ttlPpn,
			"fdc_credit"=>0,
			"fdc_origin_credit"=>0,
			"fst_orgi_curr_code"=>$dataH->fst_curr_code,
			"fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
			"fst_no_ref_bank"=>null,
			"fin_pcc_id"=>null,
			"fin_relation_id"=>null,
			"fst_active"=>"A",
			"fst_info"=>"PPN"
		]; 

		//UANG MUKA DI KLAIM
		$dataJurnal[] =[
			"fin_branch_id"=>$dataH->fin_branch_id,
			"fst_account_code"=>$dpGlAccount,
			"fdt_trx_datetime"=>date("Y-m-d H:i:s"),
			"fst_trx_sourcecode"=>"PINV", //PURCHASE INVOICE
			"fin_trx_id"=>$finLPBPurchaseId,
			"fst_reference"=>null,
			"fdc_debit"=> 0,
			"fdc_origin_debit"=>0,
			"fdc_credit"=>$dpClaim * $dataH->fdc_exchange_rate_idr ,
			"fdc_origin_credit"=>$dpClaim,
			"fst_orgi_curr_code"=>$dataH->fst_curr_code,
			"fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
			"fst_no_ref_bank"=>null,
			"fin_pcc_id"=>null,
			"fin_relation_id"=>null,
			"fst_active"=>"A",
			"fst_info"=>"UANG MUKA DI KLAIM"
		]; 

		
		//HUTANG (AP)
		$ttlHutang = ($dataH->fdc_subttl + $ttlPpn) - ($dpClaim + $dataH->fdc_disc_amount );
		$dataJurnal[] =[
			"fin_branch_id"=>$dataH->fin_branch_id,
			"fst_account_code"=>$apGlAccount,
			"fdt_trx_datetime"=>date("Y-m-d H:i:s"),
			"fst_trx_sourcecode"=>"PINV", //PURCHASE INVOICE
			"fin_trx_id"=>$finLPBPurchaseId,
			"fst_reference"=>null,
			"fdc_debit"=> 0,
			"fdc_origin_debit"=>0,
			"fdc_credit"=>$ttlHutang * $dataH->fdc_exchange_rate_idr ,
			"fdc_origin_credit"=>$ttlHutang,
			"fst_orgi_curr_code"=>$dataH->fst_curr_code,
			"fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
			"fst_no_ref_bank"=>null,
			"fin_pcc_id"=>null,
			"fin_relation_id"=>$dataH->fin_supplier_id,
			"fst_active"=>"A",
			"fst_info"=>"HUTANG DAGANG"
		];        
			
		$this->glledger_model->createJurnal($dataJurnal);       
	}

	public function unposting($finLPBPurchaseId,$unpostingDateTime =""){
		$this->load->model("glledger_model");
		//trlpbgudang : unpost fin_lpbpurchase_id
		//trpo : unpost fdc_downpayment_claimed        
		//glledger: unpost jurnal

		$unpostingDateTime = $unpostingDateTime == "" ? date("Y-m-d H:i:s") : $unpostingDateTime;

		$ssql ="select * from trlpbpurchase where fin_lpbpurchase_id = ?";
		$qr = $this->db->query($ssql,[$finLPBPurchaseId]);
		$dataH = $qr->row();
		if($dataH == null){
			throw new CustomException(lang("Invalid Purchase Invoice"),3003,"FAILED",null);            
		}

		$ssql = "update trlpbgudang set fin_lpbpurchase_id = NULL where fin_lpbpurchase_id = ?";
		$this->db->query($ssql,[$finLPBPurchaseId]);
		throwIfDBError();

		$ssql = "update trpo set fdc_downpayment_claimed = fdc_downpayment_claimed - " . $dataH->fdc_downpayment_claim . " where fin_po_id = ?";
		$this->db->query($ssql,[$dataH->fin_po_id]);
		throwIfDBError();
		

		$this->glledger_model->cancelJurnal("PINV",$finLPBPurchaseId,$unpostingDateTime);        
	}

	public function isEditable($finLPBPurchaseId){
	   
		/**
		 * FALSE CONDITION
		 * 1. Purchase Invoice sudah di lakukan pembayaran
		 */

		$ssql ="select * from trlpbpurchase where fin_lpbpurchase_id =?";
		$qr = $this->db->query($ssql,[$finLPBPurchaseId]);
		$rw = $qr->row();

		//Kondisi  1: Purchase Invoice sudah di lakukan pembayaran
		if ($rw->fdc_total_paid > 0){
			return [
				"status"=>"FAILED",
				"message"=>lang("Transaksi tidak dapat di rubah karena sudah dilakukan pembayaran !")
			];
		}


		$resp =["status"=>"SUCCESS","message"=>""];
		return $resp;
	} 


	public function delete($finLPBPurchaseId,$softDelete = true,$data=null){
		
		
		//Delete detail transaksi
		if ($softDelete){
			$ssql ="update trlpbpurchaseitems set fst_active ='D' where fin_lpbpurchase_id = ?";
			$this->db->query($ssql,[$finLPBPurchaseId]);
		}else{
			$ssql ="delete from trlpbpurchaseitems where fin_lpbpurchase_id = ?";
			$this->db->query($ssql,[$finLPBGudangId]);            
		}
		parent::delete($finLPBPurchaseId,$softDelete,$data);

		return ["status" => "SUCCESS","message"=>""];
	}

	public function deleteDetail($finLPBPurchaseId){
		$ssql ="DELETE from trlpbpurchasedetails where fin_lpbpurchase_id = ?";
		$this->db->query($ssql,[$finLPBPurchaseId]);
		throwIfDBError();
		$ssql ="DELETE from trlpbpurchaseitems where fin_lpbpurchase_id = ?";
		$this->db->query($ssql,[$finLPBPurchaseId]);
		throwIfDBError();
	}
	
	
	
	public function getDataVoucher($finLPBPurchaseId){
		$ssql = "SELECT a.*,b.fst_relation_name as fst_supplier_name,
			c.fst_po_no,c.fdt_po_datetime
			FROM trlpbpurchase a 
			INNER JOIN msrelations b on a.fin_supplier_id = b.fin_relation_id
			INNER JOIN trpo c on a.fin_po_id = c.fin_po_id
			WHERE fin_lpbpurchase_id = ?";
		$qr = $this->db->query($ssql,[$finLPBPurchaseId]);			
		$header = $qr->row_array();

		$ssql = "SELECT a.*,
			b.fst_item_code,b.fst_item_name  
			FROM trlpbpurchaseitems a 
			INNER JOIN msitems b on a.fin_item_id = b.fin_item_id 
			WHERE fin_lpbpurchase_id = ?";
		$qr = $this->db->query($ssql,[$finLPBPurchaseId]);
		$details = $qr->result_array();

		
		return [
			"header"=>$header,
			"details"=>$details
		];
	}


}


