<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trclosingperiod_model extends MY_Model {
	public $tableName = "trclosingperiod";
	public $pkey = "fin_rec_id";

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

	  

	
	

	public function posting($finLPBPurchaseId){
		/**
		 * +Update field fin_lpbpurchase_id di trlpbgudang
		 * +Update claimed downpayment di PO
		 * +Jurnal
		 */
		$this->load->model("glledger_model");
		$this->load->model("trpurchaserequestprocess_model");
		

		$ssql = "SELECT a.*,b.fbl_is_import,b.fdc_downpayment,b.fdc_downpayment_paid,b.fbl_dp_inc_ppn,b.fst_pos_costing 
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
		$accPembelianBahanBaku ="";
		$accPembelianBarangJadi="";

		if ($dataH->fbl_is_import){
			$accPembelianBahanBaku = getGLConfig("PURCHASE_IMPORT"); //BAHAN BAKU
			$accPembelianBarangJadi = getGLConfig("PURCHASE_IMPORT_JADI"); //BARANG JADI
			

			$dpGlAccount = getGLConfig("DP_OUT_IMPORT");
			$apGlAccount = getGLConfig("AP_DAGANG_IMPORT");
		}else{
			$accPembelianBahanBaku  = getGLConfig("PURCHASE_LOKAL"); //BAHAN BAKU
			$accPembelianBarangJadi = getGLConfig("PURCHASE_LOKAL_JADI"); //BARANG JADI

			$dpGlAccount = getGLConfig("DP_OUT_LOKAL");
			$apGlAccount = getGLConfig("AP_DAGANG_LOKAL");
		}

		

		//$purchaseAccount = $accPembelianBahanBaku;
		//$purchaseDiscAccount = getGLConfig("PURCHASE_DISC");

		//fin_item_type_id : 5 Logistic, 1,2,3 Bahan Baku, Other is Merchandise
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
				

		//Dengan PR atau Tidak
		$rwPRProses = $this->trpurchaserequestprocess_model->getHeaderByPO($dataH->fin_po_id);
		$usingPR = false;
		if ($rwPRProses == null){
			//Non PR (Tidak bole ada transaksi item logistik stock)
			$usingPR = false;
		}else{
			//With PR
			$usingPR = true;
		}

		foreach($rs as $rw){		
			//Pembelian/Persediaan/Biaya > Hutang 
			if ($rw->fin_item_type_id == 5 ){ //Logistik				
				$postAcc = "";

				if ($rw->fbl_stock){
					//persediaan supply (Harus Dengan PR)
					if ($usingPR){
						$postAcc = getLogisticGLConfig($rw->fin_item_group_id,"PERSEDIAAN");
					}else{
						throw new CustomException(lang("Untuk Item Logistik stock, harus menggunakan Purchase Request !"),3003,"FAILED",[]);
					}				
					
				}else{					
					//Langsung dijadikan biaya
					if ($usingPR){
						//Jadikan Persedian dan dijadikan biaya pada saat distribusi
						//var_dump($rwPRProses);
						if ($rwPRProses->fst_stock_cost_type == "NONSTOCK_PABRIKASI"){
							$postAcc = getLogisticGLConfig($rw->fin_item_group_id,"BIAYA_PABRIKASI");
						}else if ($rwPRProses->fst_stock_cost_type == "NONSTOCK_UMUM"){
							$postAcc = getLogisticGLConfig($rw->fin_item_group_id,"BIAYA_UMUM");
						}
					}else{
						

						//Langsung dijadikan biaya
						if ($dataH->fst_pos_costing == "NONSTOCK_PABRIKASI"){
							$postAcc = getLogisticGLConfig($rw->fin_item_group_id,"BIAYA_PABRIKASI");
						}else if ($dataH->fst_pos_costing == "NONSTOCK_UMUM"){
							$postAcc = getLogisticGLConfig($rw->fin_item_group_id,"BIAYA_UMUM");
						}
					}
				}

				if (isset($dataTmp[$postAcc][$rw->fin_pcc_id])){
					$dataTmp[$postAcc][$rw->fin_pcc_id] = [
						"debet"=> $dataTmp[$postAcc][$rw->fin_pcc_id]["debet"] + $rw->fdc_total - $rw->fdc_ttl_disc_amount,
						"credit"=> 0
					];
				}else{
					$dataTmp[$postAcc][$rw->fin_pcc_id] = [
						"debet"=> $rw->fdc_total - $rw->fdc_ttl_disc_amount,
						"credit"=> 0
					];
				}

			}else{ //Non Logistic
				if ($rw->fbl_stock){
					if ($rw->fin_item_type_id == 1 || $rw->fin_item_type_id == 2 || $rw->fin_item_type_id == 3){
						//Bahan Baku
						$postAcc = $accPembelianBahanBaku; 
					}else{
						//Barang Jadi
						$postAcc = $accPembelianBarangJadi;
					}
				}else{					
					if ($rw->fin_item_type_id == 6){ //Fixed Asset
						$postAcc = getLogisticGLConfig($rw->fin_item_group_id,"PERSEDIAAN");
						if (isset($dataTmp[$postAcc][$rw->fin_pcc_id])){
							$dataTmp[$postAcc][$rw->fin_pcc_id] = [
								"debet"=> $dataTmp[$postAcc][$rw->fin_pcc_id]["debet"] + $rw->fdc_total - $rw->fdc_ttl_disc_amount,
								"credit"=> 0
							];
						}else{
							$dataTmp[$postAcc][$rw->fin_pcc_id] = [
								"debet"=> $rw->fdc_total - $rw->fdc_ttl_disc_amount,
								"credit"=> 0
							];
						}

					}else{
						//Langsung dijadikan biaya
						if ($usingPR){
							//Jadikan Persedian dan dijadikan biaya pada saat distribusi
							if ($rwPRProses->fst_pos_costing == "NONSTOCK_PABRIKASI"){
								$postAcc = getLogisticGLConfig($rw->fin_item_group_id,"BIAYA_PABRIKASI");
							}else if ($rwPRProses->fst_pos_costing == "NONSTOCK_UMUM"){
								$postAcc = getLogisticGLConfig($rw->fin_item_group_id,"BIAYA_UMUM");
							}
						}else{
							//Langsung dijadikan biaya						
							if ($dataH->fst_pos_costing == "NONSTOCK_PABRIKASI"){
								$postAcc = getLogisticGLConfig($rw->fin_item_group_id,"BIAYA_PABRIKASI");
							}else if ($dataH->fst_pos_costing == "NONSTOCK_UMUM"){
								$postAcc = getLogisticGLConfig($rw->fin_item_group_id,"BIAYA_UMUM");
							}
						}	
						
						if (isset($dataTmp[$postAcc][$rw->fin_pcc_id])){
							$dataTmp[$postAcc][$rw->fin_pcc_id] = [
								"debet"=> $dataTmp[$postAcc][$rw->fin_pcc_id]["debet"] + $rw->fdc_total,
								"credit"=> 0
							];
						}else{
							$dataTmp[$postAcc][$rw->fin_pcc_id] = [
								"debet"=> $rw->fdc_total,
								"credit"=> 0
							];
						}

						//Disc > Hutang (untuk Logistic dan Fixed asset langsung dipotong jadi tidak di jurnal sebagai discount)
						if ($rw->fin_item_type_id == 1 || $rw->fin_item_type_id == 2 ||$rw->fin_item_type_id == 3 ){
							$purchaseDiscAccount = getGLConfig("PURCHASE_DISC");  //DISC PEMBELIAN BAHAN BAKU
						}else{
							$purchaseDiscAccount = getGLConfig("PURCHASE_DISC_JADI"); //DISC PEMBELIAN BARANG JADI
						}

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
				}
							
				


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
					"fst_trx_no"=>$dataH->fst_lpbpurchase_no,
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
			"fst_trx_no"=>$dataH->fst_lpbpurchase_no,
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
			"fst_trx_no"=>$dataH->fst_lpbpurchase_no,
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
			"fst_trx_no"=>$dataH->fst_lpbpurchase_no,
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
		 * 2. Item Fixed Asset sudah di profiling
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

		//Kondisi  2: Item Fixed Asset sudah di profiling
		$ssql = "SELECT * FROM trlpbpurchaseitems where fin_lpbpurchase_id = ? and fbl_fa_profiles = 1";
		$qr = $qr = $this->db->query($ssql,[$finLPBPurchaseId]);
		$rw = $qr->row();
		if ($rw != null){
			return [
				"status"=>"FAILED",
				"message"=>lang("Item Fixed Asset Sudah di profiling !")
			];
		}


		$resp =["status"=>"SUCCESS","message"=>""];
		return $resp;
	} 

}


