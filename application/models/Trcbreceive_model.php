<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trcbreceive_model extends MY_Model {
	public $tableName = "trcbreceive";
	public $pkey = "fin_cbreceive_id";

	public function __construct(){
		parent:: __construct();
	}

	public function getRules($mode="ADD",$id=0){
		$rules = [];

		$rules[] = [
			'field' => 'fst_cbreceive_no',
			'label' => 'No Penerimaan',
			'rules' => 'required',
			'errors' => array(
				'required' => '%s tidak boleh kosong',
			)
		];


		return $rules;
	}

	public function generateCBReceiveNo($finKasBankId, $trDate = null) {
		$this->load->model("kasbanknumbering_model");

		$trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
		$tahun = date("Y/m", strtotime ($trDate));
		$activeBranch = $this->aauth->get_active_branch();
		$branchCode = "";
		if($activeBranch){
			$branchCode = $activeBranch->fst_branch_code;
		}

		$ssql ="select * from mskasbank where fin_kasbank_id = ?";
		$qr = $this->db->query($ssql,[$finKasBankId]);
		$rw = $qr->row();
		if($rw == null){
			return "";
		}

		$prefix =$rw->fst_prefix_pemasukan;

		/*
		$query = $this->db->query("SELECT MAX(fst_cbreceive_no) as max_id FROM trcbreceive where fst_cbreceive_no like '".$prefix."/%/".$tahun."%'");

		$row = $query->row_array();

		$max_id = $row['max_id']; 
		
		$max_id1 =(int) substr($max_id,strlen($max_id)-5);
		
		$fst_tr_no = $max_id1 +1;
		*/
		$fst_tr_no =  $this->kasbanknumbering_model->getKasBankNo($prefix."/%/" . $tahun ."%");        
		$max_tr_no = $prefix."/". $branchCode .'/' .$tahun.'/'.sprintf("%05s",$fst_tr_no);        
		return $max_tr_no;
	}
	public function getInGiroAccount(){
		$glCode = getGLConfig("CB_IN_GIRO_MUNDUR");
		$ssql = "select * from glaccounts where fst_glaccount_code = ?";
		$qr = $this->db->query($ssql,[$glCode]);
		$rw = $qr->row();
		return $rw;
	}
	public function getAccountList(){
		//$accounts = getDataTable("glaccounts","*","fst_active ='A' and fst_glaccount_level != 'HD' and fbl_is_allow_in_cash_bank_module = 1");
		$ssql ="SELECT a.*,b.fst_glaccount_type FROM glaccounts a 
			INNER JOIN glaccountmaingroups b on a.fin_glaccount_maingroup_id = b.fin_glaccount_maingroup_id 
			WHERE a.fst_active = 'A' 
			AND a.fst_glaccount_level != 'HD' 
			AND a.fbl_is_allow_in_cash_bank_module = 1";
		$qr = $this->db->query($ssql,[]);
		return $qr->result();
	}

	public function unposting($finCBReceiveId,$unpostingDateTime =""){
		$this->load->model("glledger_model");
		$this->load->model("kasbanknumbering_model");

		$unpostingDateTime = $unpostingDateTime == "" ? date("Y-m-d H:i:s") : $unpostingDateTime;

		$ssql ="select * from trcbreceive where fin_cbreceive_id = ?";        
		$qr = $this->db->query($ssql,[$finCBReceiveId]);        
		$dataH = $qr->row();

		//unlog kasbank no
		$this->kasbanknumbering_model->unlog($dataH->fst_cbreceive_no);

		//get Detail Transaksi
		$ssql ="select * from trcbreceiveitems where fin_cbreceive_id = ?";        
		$qr = $this->db->query($ssql,[$finCBReceiveId]);        
		$dataItems = $qr->result();        
		foreach($dataItems as $dataItem){
			if ($dataItem->fst_trans_type == "DP_SO"){
				$ssql = "update trsalesorder set fdc_downpayment_paid = fdc_downpayment_paid -  ? where fin_salesorder_id = ?";
				$this->db->query($ssql,[$dataItem->fdc_receive_amount,$dataItem->fin_trans_id]);
				throwIfDBError();
				//cek valid DP_SO
				$this->checkIsValidDPSO($dataItem->fin_trans_id);

			}else if ($dataItem->fst_trans_type == "INV_SO"){
				$ssql = "update trinvoice set fdc_total_paid = fdc_total_paid -  ? where fin_inv_id = ?";
				$this->db->query($ssql,[$dataItem->fdc_receive_amount,$dataItem->fin_trans_id]);
				throwIfDBError();
				$this->checkIsValidInvoceSO($dataItem->fin_trans_id);

			}else if ($dataItem->fst_trans_type == "RETURN_SO"){
				$ssql = "update trsalesreturn set fdc_total_claimed = fdc_total_claimed - ? where fin_salesreturn_id = ?";
				$this->db->query($ssql,[abs($dataItem->fdc_receive_amount),$dataItem->fin_trans_id]);
				throwifDBError();
				$this->checkIsValidSalesReturn($dataItem->fin_trans_id);
			
			}else if ($dataItem->fst_trans_type == "PAYMENT_OVER"){
				
			}else if ($dataItem->fst_trans_type == "CLAIM_PAYMENT_OVER"){

				$ssql = "update trcbreceiveitems set fdc_receive_amount_claimed = fdc_receive_amount_claimed -  ? where fin_rec_id = ?";
				$this->db->query($ssql,[abs($dataItem->fdc_receive_amount),$dataItem->fin_trans_id]);
				throwIfDBError();
				$this->checkIsValidClaimPaymentOver($dataItem->fin_trans_id);  

			}else if ($dataItem->fst_trans_type == "CLAIM_EXPEDITION"){

				$ssql = "update trsalesekspedisi set fdc_total_claimed = fdc_total_claimed - ? where fin_salesekspedisi_id = ?";
                $this->db->query($ssql,[abs($dataItem->fdc_receive_amount),$dataItem->fin_trans_id]);				
				throwIfDBError();
				$this->checkIsValidClaimExpedition($dataItem->fin_trans_id);

			}else if ($dataItem->fst_trans_type == "CLAIM_PAYMENT_UNKNOWN"){
				$ssql ="UPDATE glledger set fbl_claimed_cashbank_receive_unknown = false where fin_rec_id =?";
				$this->db->query($ssql,[$dataItem->fin_trans_id]);
				throwIfDBError();
			
			}else if ($dataItem->fst_trans_type == "SELL_FIXED_ASSET"){
				$ssql = "update trfadisposal set fdc_sell_total_paid = fdc_sell_total_paid -  ? where fin_fa_disposal_id = ?";
				$this->db->query($ssql,[abs($dataItem->fdc_receive_amount),$dataItem->fin_trans_id]);
				throwIfDBError();

			}else{
				throw new CustomException("Invalid detail cash bank transaction type $dataItem->fst_trans_type",9009,"FAILED",null);
			}
		}
		$this->glledger_model->cancelJurnal("CBIN",$dataH->fin_cbreceive_id,$unpostingDateTime);
	}

	public function posting($finCBReceiveId){
		$this->load->model("kasbank_model");
		$this->load->model("kasbanknumbering_model");
		$this->load->model("glledger_model");

		$ssql ="select * from trcbreceive where fin_cbreceive_id = ?";        
		$qr = $this->db->query($ssql,[$finCBReceiveId]);        
		$dataH = $qr->row();
	
		$acc = $this->kasbank_model->getDataById($dataH->fin_kasbank_id);
		$acc = $acc["ms_kasbank"];
		$defaultGLCode = $acc->fst_glaccount_code;

		$dataJurnal = [];
		
		//log kasbank no
		$this->kasbanknumbering_model->log($dataH->fst_cbreceive_no);

		

		//get Detail Transaksi
		$ssql ="select * from trcbreceiveitems where fin_cbreceive_id = ?";        
		$qr = $this->db->query($ssql,[$finCBReceiveId]);        
		$dataItems = $qr->result();               
		foreach($dataItems as $dataItem){
			if ($dataItem->fst_trans_type == "DP_SO"){
				//DP Purcahase
				$tmpArr = $this->getDataJurnalPostingDPSO($dataItem,$dataH);                 
				$ssql = "update trsalesorder set fdc_downpayment_paid = fdc_downpayment_paid +  ? where fin_salesorder_id = ?";
				$this->db->query($ssql,[$dataItem->fdc_receive_amount,$dataItem->fin_trans_id]);
				throwIfDBError();
				//cek valid DP_PO
				$this->checkIsValidDPSO($dataItem->fin_trans_id);

			}else if ($dataItem->fst_trans_type == "INV_SO") {
				//Purchase Invoice
				$tmpArr =$this->getDataJurnalPostingSalesInv($dataItem,$dataH);
				$ssql = "update trinvoice set fdc_total_paid = fdc_total_paid +  ? where fin_inv_id = ?";
				$this->db->query($ssql,[$dataItem->fdc_receive_amount,$dataItem->fin_trans_id]);
				throwIfDBError();
				$this->checkIsValidInvoceSO($dataItem->fin_trans_id);
			}else if ($dataItem->fst_trans_type == "RETURN_SO"){
				$tmpArr = $this->getDataJurnalPostingSOReturn($dataItem,$dataH);
				$ssql = "update trsalesreturn set fdc_total_claimed = fdc_total_claimed + ? where fin_salesreturn_id = ?";
				$this->db->query($ssql,[abs($dataItem->fdc_receive_amount),$dataItem->fin_trans_id]);
				throwIfDBError();

				$this->checkIsValidSalesReturn($dataItem->fin_trans_id);
				
			
			}else if ($dataItem->fst_trans_type == "PAYMENT_OVER"){
				$tmpArr = $this->getDataJurnalPostingPaymentOver($dataItem,$dataH);
				
			}else if ($dataItem->fst_trans_type == "CLAIM_PAYMENT_OVER"){
				//Claim Kelebihan Pembayaran
				$tmpArr = $this->getDataJurnalPostingClaimPurchaseOver($dataItem,$dataH);        

				$ssql = "update trcbreceiveitems set fdc_receive_amount_claimed = fdc_receive_amount_claimed +  ? where fin_rec_id = ?";
				$this->db->query($ssql,[abs($dataItem->fdc_receive_amount),$dataItem->fin_trans_id]);
				throwIfDBError();
				
				//cek valid claim kelebihan pembayaran
				$this->checkIsValidClaimPaymentOver($dataItem->fin_trans_id);

			}else if ($dataItem->fst_trans_type == "CLAIM_EXPEDITION"){
				//Claim Kelebihan Pembayaran
				$tmpArr = $this->getDataJurnalPostingClaimExpedition($dataItem,$dataH);        

				$ssql = "update trsalesekspedisi set fdc_total_claimed = fdc_total_claimed + ? where fin_salesekspedisi_id = ?";
                $this->db->query($ssql,[abs($dataItem->fdc_receive_amount),$dataItem->fin_trans_id]);				
				throwIfDBError();

				//cek valid claim kelebihan ekspedisi
				$this->checkIsValidClaimExpedition($dataItem->fin_trans_id);

			}else if ($dataItem->fst_trans_type == "CLAIM_PAYMENT_UNKNOWN"){
				$tmpArr = $this->getDataJurnalPostingClaimPaymentUnknown($dataItem,$dataH);
				$ssql ="UPDATE glledger set fbl_claimed_cashbank_receive_unknown = true where fin_rec_id =?";
				$this->db->query($ssql,[$dataItem->fin_trans_id]);
				throwIfDBError();
			}else if ($dataItem->fst_trans_type == "SELL_FIXED_ASSET"){
				$tmpArr = $this->getDataJurnalPostingSellFixedAsset($dataItem,$dataH);
				$ssql ="UPDATE trfadisposal set fdc_sell_total_paid = fdc_sell_total_paid + ? where fin_fa_disposal_id = ?";

				$this->db->query($ssql,[abs($dataItem->fdc_receive_amount),$dataItem->fin_trans_id]);
				throwIfDBError();
			}else{
				throw new CustomException("Unknow Detail Transaction Type $dataItem->fst_trans_type",9009,"FAILED",null);
			}
			
			foreach($tmpArr as $tmp){
				$dataJurnal[] = $tmp;
			}
		}

		//Get Detail Payment
		$ssql ="select * from trcbreceiveitemstype where fin_cbreceive_id = ?";        
		$qr = $this->db->query($ssql,[$finCBReceiveId]);        
		$dataReceives = $qr->result();      
		foreach($dataReceives as $receive){
			$fstAccountCode = $defaultGLCode;
			if ($receive->fst_cbreceive_type == "TUNAI" || $receive->fst_cbreceive_type == "TRANSFER" ){
				$fstAccountCode = $defaultGLCode;
			}else if ($receive->fst_cbreceive_type == "GIRO"){
				$fstAccountCode = getGLConfig("CB_IN_GIRO_MUNDUR");
			}else if ($receive->fst_cbreceive_type == "GLACCOUNT"){
				$fstAccountCode = $receive->fst_glaccount_code;
			}
			$dataJurnal[] = [
				"fin_branch_id"=>$dataH->fin_branch_id,
				"fst_account_code"=>$fstAccountCode,
				"fdt_trx_datetime"=>$dataH->fdt_cbreceive_datetime,
				"fst_trx_sourcecode"=>"CBIN",
				"fin_trx_id"=>$finCBReceiveId,
				"fst_trx_no"=>$dataH->fst_cbreceive_no,
				"fst_reference"=>$receive->fst_referensi,
				"fdc_debit"=>$receive->fdc_amount * $dataH->fdc_exchange_rate_idr,
				"fdc_origin_debit"=>$receive->fdc_amount,
				"fdc_credit"=>0,
				"fdc_origin_credit"=>0,
				"fst_orgi_curr_code"=>$dataH->fst_curr_code,
				"fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
				"fst_no_ref_bank"=>null,
				"fin_pcc_id"=>$receive->fin_pcc_id,
				"fin_pc_divisi_id"=>$receive->fin_pc_divisi_id,
				"fin_pc_customer_id"=>$receive->fin_pc_customer_id,
				"fin_pc_project_id"=>$receive->fin_pc_project_id,
				"fin_relation_id"=>$receive->fin_relation_id,
				"fst_active"=>"A",
				"fst_info"=>"PENERIMAAN",
			];  
		}   


		$this->glledger_model->createJurnal($dataJurnal);       
	}

	public function getDataJurnalPostingDPSO($dataItem,$dataH){

		$ssql ="select * from trsalesorder where fin_salesorder_id = ?";
		$qr = $this->db->query($ssql,[$dataItem->fin_trans_id]);
		$rw = $qr->row();
		if($rw == null){            
			throw new CustomException(lang("Id sales order tidak dikenal !"),3003,"FAILED",null);
		}

		if ($rw->fdc_downpayment == 0){
			throw new CustomException(sprintf(lang("Sales Order %s tidak memiliki DP"), $rw->fst_salesorder_no),3003,"FAILED",null);
		}   

		
		$fstAccountCode = getGLConfig("AR_DAGANG_LOKAL");
		$dataJurnal = [];
		$dataJurnal[] = [
			"fin_branch_id"=>$rw->fin_branch_id,
			"fst_account_code"=>$fstAccountCode,
			"fdt_trx_datetime"=>$dataH->fdt_cbreceive_datetime,
			"fst_trx_sourcecode"=>"CBIN",
			"fin_trx_id"=>$dataItem->fin_cbreceive_id,
			"fst_trx_no"=>$dataH->fst_cbreceive_no,
			"fst_reference"=>$rw->fst_salesorder_no . " | " . $dataH->fst_memo,
			"fdc_debit"=> 0,
			"fdc_origin_debit"=>0,
			"fdc_credit"=>$dataItem->fdc_receive_amount   * $rw->fdc_exchange_rate_idr,
			"fdc_origin_credit"=>$dataItem->fdc_receive_amount,
			"fst_orgi_curr_code"=>$rw->fst_curr_code,
			"fdc_orgi_rate"=>$rw->fdc_exchange_rate_idr,
			"fst_no_ref_bank"=>null,
			"fin_pcc_id"=>null,
			"fin_relation_id"=>$rw->fin_relation_id,
			"fst_active"=>"A"
		];

		//Cek selisih Kurs        
		if ($dataH->fdc_exchange_rate_idr  != $rw->fdc_exchange_rate_idr){
			
			$selisih = ($dataItem->fdc_receive_amount * $rw->fdc_exchange_rate_idr) - ($dataItem->fdc_receive_amount * $dataH->fdc_exchange_rate_idr);
			if ($selisih < 0){
				$fstAccountCode =  getGLConfig("SELISIH_KURS_UNTUNG");
			}else{
				$fstAccountCode =  getGLConfig("SELISIH_KURS_RUGI");
			}
			$debet = $selisih;
			$credit = 0;

			$dataJurnal[] = [
				"fin_branch_id"=>$rw->fin_branch_id,
				"fst_account_code"=>$fstAccountCode,
				"fdt_trx_datetime"=>$dataH->fdt_cbpayment_datetime,
				"fst_trx_sourcecode"=>"CBIN",
				"fin_trx_id"=>$dataItem->fin_cbreceive_id,
				"fst_trx_no"=>$dataH->fst_cbreceive_no,
				"fst_reference"=>$rw->fst_salesorder_no . " | " . $dataH->fst_memo,
				"fdc_debit"=> $debet,
				"fdc_origin_debit"=>$debet,
				"fdc_credit"=>$credit,
				"fdc_origin_credit"=>$credit,
				"fst_orgi_curr_code"=>"IDR",
				"fdc_orgi_rate"=>1,
				"fst_no_ref_bank"=>null,
				"fin_pcc_id"=>null,
				"fin_relation_id"=>null,
				"fst_active"=>"A"
			];
		}
		return $dataJurnal;
	}

	public function checkIsValidDPSO($finSalesOrderId){
		//Cek DP 
		$ssql = "select * from trsalesorder where fin_salesorder_id = ?";
		$qr = $this->db->query($ssql,[$finSalesOrderId]);
		$rw = $qr->row();
		if($rw != null){
			//Cek bila terjadi kelebihan pembayaran downpayment;
			if ($rw->fdc_downpayment_paid > $rw->fdc_downpayment){
				throw new CustomException(sprintf(lang("Nilai pembayaran DP untuk Sales Order %s melebihi nilai DP !"),$rw->fst_salesorder_no),3003,"FAILED",$rw);
			}
			//Cek bila terjadi downpayment claim > downpayment paid
			if ($rw->fdc_downpayment_paid < $rw->fdc_downpayment_claimed){
				throw new CustomException(sprintf(lang("Nilai DP yang dibayar kurang dari nilai DP yang telah di klaim, Sales Order: %s  !"),$rw->fst_salesorder_no),3003,"FAILED",$rw);
			}
		}else{            
			throw new CustomException(lang("ID PO tidak dikenal !"),3003,"FAILED",null);
		}
	}

	public function getDataJurnalPostingSalesInv($dataItem,$dataH){
		$dataJurnal = [];

		$ssql = "SELECT a.* FROM trinvoice a
			WHERE a.fin_inv_id = ? and a.fst_active != 'D'";
		$qr =$this->db->query($ssql,[$dataItem->fin_trans_id]);

		$dataD = $qr->row();
		if($dataD == null){
			return $dataJurnal;
		}

		//Jurnal Piutang dagang Lokal atau Import
		$glAccountPiutang = getGLConfig("AR_DAGANG_LOKAL");
		$glAccountInfo = "PIUTANG DAGANG";
				
		$dataJurnal[] = [
			"fin_branch_id"=>$dataD->fin_branch_id,
			"fst_account_code"=>$glAccountPiutang,
			"fdt_trx_datetime"=>$dataH->fdt_cbreceive_datetime,
			"fst_trx_sourcecode"=>"CBIN",
			"fin_trx_id"=>$dataItem->fin_cbreceive_id,
			"fst_trx_no"=>$dataH->fst_cbreceive_no,
			"fst_reference"=>$dataD->fst_inv_no . " | " . $dataH->fst_memo,
			"fdc_debit"=> 0,
			"fdc_origin_debit"=>0,
			"fdc_credit"=>$dataItem->fdc_receive_amount   * $dataD->fdc_exchange_rate_idr,
			"fdc_origin_credit"=>$dataItem->fdc_receive_amount,
			"fst_orgi_curr_code"=>$dataD->fst_curr_code,
			"fdc_orgi_rate"=>$dataD->fdc_exchange_rate_idr,
			"fst_no_ref_bank"=>null,
			"fin_pcc_id"=>null,
			"fin_relation_id"=>$dataH->fin_customer_id,
			"fst_active"=>"A",
			"fst_info"=>$glAccountInfo,
		];


		//Jurnal Selisih Kurs     
		if ($dataH->fdc_exchange_rate_idr  != $dataD->fdc_exchange_rate_idr){

			$selisih = ($dataItem->fdc_receive_amount * $dataD->fdc_exchange_rate_idr) - ($dataItem->fdc_receive_amount * $dataH->fdc_exchange_rate_idr);

			if ($selisih < 0){
				$fstAccountCode =  getGLConfig("SELISIH_KURS_UNTUNG");
				$glAccountInfo = "SELISIH KURS UNTUNG";                
			}else{
				$fstAccountCode =  getGLConfig("SELISIH_KURS_RUGI");
				$glAccountInfo = "SELISIH KURS RUGI";
			}
				
			$debet = $selisih;
			$credit = 0;

			$dataJurnal[] = [
				"fin_branch_id"=>$dataD->fin_branch_id,
				"fst_account_code"=>$fstAccountCode,
				"fdt_trx_datetime"=>$dataH->fdt_cbreceive_datetime,
				"fst_trx_sourcecode"=>"CBIN",
				"fin_trx_id"=>$dataItem->fin_cbreceive_id,
				"fst_trx_no"=>$dataH->fst_cbreceive_no,
				"fst_reference"=>$dataD->fst_inv_no . " | " . $dataH->fst_memo,
				"fdc_debit"=> $debet,
				"fdc_origin_debit"=>$debet,
				"fdc_credit"=>$credit,
				"fdc_origin_credit"=>$credit,
				"fst_orgi_curr_code"=>"IDR",
				"fdc_orgi_rate"=>1,
				"fst_no_ref_bank"=>null,
				"fin_pcc_id"=>null,
				"fin_relation_id"=>null,
				"fst_active"=>"A",
				"fst_info"=>$glAccountInfo
			];
		}

		return $dataJurnal;
	}

	public function checkIsValidInvoceSO($finInvId){
		$ssql = "select * from trinvoice where fin_inv_id = ?";
		$qr = $this->db->query($ssql,[$finInvId]);
		$rw = $qr->row();
		if($rw == null){
			throw new CustomException(lang("ID Invoice tidak dikenal !"),3003,"FAILED",null);
		}

		//Pembayaran + Return melebihi jumlah tagihan
		if(($rw->fdc_total_paid + $rw->fdc_total_return) > ($rw->fdc_total)){
			throw new CustomException(sprintf(lang("Total pembayaran dan retur Invoice %s melebih jumlah tagihan invoce !"),$rw->fst_inv_no),3003,"FAILED",null);
		}
	}


	public function getDataJurnalPostingSOReturn($dataItem,$dataH){

		/* SAAT REALISASI PEMBAYARAN FAKTUR, POTONG RETUR (AYAT SILANG)
		------------------------------------------------------------
		Kas/Bank		Rp.   891.000,-
		Retur Belum Realisasi	Rp.    99.000,-
			Piutang Dagang			Rp.  990.000,-
		*/

		$dataJurnal = [];

		
		$ssql = "SELECT a.* FROM trsalesreturn a
			WHERE a.fin_salesreturn_id = ? and a.fst_active != 'D'";
		$qr =$this->db->query($ssql,[$dataItem->fin_trans_id]);
		$dataD = $qr->row();
		if($dataD == null){
			throw new CustomException("ID sales return tidak dikenal !",9009,"FAILED",null);
		}

		//Jurnal Piutang dagang Lokal atau Import
		$accRetunBelumRealisasi = getGLConfig("RETUR_BELUM_REALISASI");        
		//$glAccountPiutang = getGLConfig("AR_DAGANG_LOKAL");
		$glAccountInfo = "RETUR BELUM REALISASI";
				
		$dataJurnal[] = [
			"fin_branch_id"=>$dataD->fin_branch_id,
			"fst_account_code"=>$accRetunBelumRealisasi,
			"fdt_trx_datetime"=>$dataH->fdt_cbreceive_datetime,
			"fst_trx_sourcecode"=>"CBIN",
			"fin_trx_id"=>$dataItem->fin_cbreceive_id,
			"fst_trx_no"=>$dataH->fst_cbreceive_no,
			"fst_reference"=>$dataD->fst_salesreturn_no . " | " . $dataH->fst_memo,
			"fdc_debit"=> 0,
			"fdc_origin_debit"=>0,
			"fdc_credit"=> $dataItem->fdc_receive_amount * $dataD->fdc_exchange_rate_idr,
			"fdc_origin_credit"=> $dataItem->fdc_receive_amount * 1,
			"fst_orgi_curr_code"=>$dataD->fst_curr_code,
			"fdc_orgi_rate"=>$dataD->fdc_exchange_rate_idr,
			"fst_no_ref_bank"=>null,
			"fin_pcc_id"=>null,
			"fin_relation_id"=>$dataH->fin_customer_id,
			"fst_active"=>"A",
			"fst_info"=>$glAccountInfo,
		];


		//Jurnal Selisih Kurs     
		if ($dataH->fdc_exchange_rate_idr  != $dataD->fdc_exchange_rate_idr){

			$selisih = ($dataItem->fdc_receive_amount * $dataD->fdc_exchange_rate_idr) - ($dataItem->fdc_receive_amount * $dataH->fdc_exchange_rate_idr);

			if ($selisih < 0){
				$fstAccountCode =  getGLConfig("SELISIH_KURS_RUGI");
				$glAccountInfo = "SELISIH KURS RUGI";                
				
			}else{
				$fstAccountCode =  getGLConfig("SELISIH_KURS_UNTUNG");
				$glAccountInfo = "SELISIH KURS UNTUNG";
			}
				
			$debet = $selisih;
			$credit = 0;
						
			$dataJurnal[] = [
				"fin_branch_id"=>$dataD->fin_branch_id,
				"fst_account_code"=>$fstAccountCode,
				"fdt_trx_datetime"=>$dataH->fdt_cbreceive_datetime,
				"fst_trx_sourcecode"=>"CBIN",
				"fin_trx_id"=>$dataItem->fin_cbreceive_id,
				"fst_trx_no"=>$dataH->fst_cbreceive_no,
				"fst_reference"=>$dataD->fst_inv_no . " | " . $dataH->fst_memo,
				"fdc_debit"=> $debet,
				"fdc_origin_debit"=>$debet,
				"fdc_credit"=>$credit,
				"fdc_origin_credit"=>$credit,
				"fst_orgi_curr_code"=>"IDR",
				"fdc_orgi_rate"=>1,
				"fst_no_ref_bank"=>null,
				"fin_pcc_id"=>null,
				"fin_relation_id"=>null,
				"fst_active"=>"A",
				"fst_info"=>$glAccountInfo
			];
		}

		return $dataJurnal;

	}

	public function checkIsValidSalesReturn($finSalesReturnId){
		$ssql = "select * from trsalesreturn where fin_salesreturn_id = ?";
		$qr = $this->db->query($ssql,[$finSalesReturnId]);
		$rw = $qr->row();
		if($rw == null){
			throw new CustomException(lang("ID sales return tidak dikenal !"),9009,"FAILED",null);
		}

		if($rw->fdc_total < $rw->fdc_total_claimed){
			throw new CustomException(sprintf(lang("Total klaim retur %s melebih nilai retur"),$rw->fst_salesreturn_no),3003,"FAILED",null);
		}
	}

	public function getDataJurnalPostingPaymentOver($dataItem,$dataH){

		/* Kelebihan pembayaran
		------------------------------------------------------------
		Kas/Bank		Rp.   50.000,-
			Kelebihan Bayar			Rp.  50.000,-
		*/

		$dataJurnal = [];
		//Jurnal Piutang dagang Lokal atau Import
		$accKelebihanBayar = getGLConfig("CUSTOMER_KELEBIHAN_BAYAR");        
		$glAccountInfo = "KELEBIHAN PEMBAYARAN";
				
		$dataJurnal[] = [
			"fin_branch_id"=>$dataH->fin_branch_id,
			"fst_account_code"=>$accKelebihanBayar,
			"fdt_trx_datetime"=>$dataH->fdt_cbreceive_datetime,
			"fst_trx_sourcecode"=>"CBIN",
			"fin_trx_id"=>$dataH->fin_cbreceive_id,
			"fst_trx_no"=>$dataH->fst_cbreceive_no,
			"fst_reference"=>$dataH->fst_memo,
			"fdc_debit"=> 0,
			"fdc_origin_debit"=>0,
			"fdc_credit"=> $dataItem->fdc_receive_amount * $dataH->fdc_exchange_rate_idr,
			"fdc_origin_credit"=> $dataItem->fdc_receive_amount * 1,
			"fst_orgi_curr_code"=>$dataH->fst_curr_code,
			"fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
			"fst_no_ref_bank"=>null,
			"fin_pcc_id"=>null,
			"fin_relation_id"=>$dataH->fin_customer_id,
			"fst_active"=>"A",
			"fst_info"=>$glAccountInfo,
		];        
		return $dataJurnal;

	}
	
	public function getDataJurnalPostingClaimPurchaseOver($dataItem,$dataH){
		$ssql = "SELECT a.*,b.fst_cbreceive_no,b.fst_curr_code,b.fdc_exchange_rate_idr FROM trcbreceiveitems a
			INNER JOIN trcbreceive b on a.fin_cbreceive_id = b.fin_cbreceive_id 
			WHERE a.fin_rec_id = ?   
			AND b.fst_active != 'D'";

		$qr =$this->db->query($ssql,[$dataItem->fin_trans_id]);
		$dataD = $qr->row();
		if($dataD == null){
			throw new CustomException("ID Kelebihan Pembayaran tidak dikenal !",9009,"FAILED",null);
		}

		$maxClaim = (float) $dataD->fdc_receive_amount - (float) $dataD->fdc_receive_amount_claimed;
		if ($maxClaim < (float) $dataItem->fdc_receive_amount){
			throw new CustomException(sprintf(lang("Maksimum klaim kelebihan pembayaran %s"),formatNumber($maxClaim)),3003,"FAILED",null);
		}

		$dataJurnal = [];
		//Jurnal Piutang dagang Lokal atau Import
		$accKelebihanBayar = getGLConfig("CUSTOMER_KELEBIHAN_BAYAR");        
		$glAccountInfo = "CLAIM KELEBIHAN PEMBAYARAN";
				
		$dataJurnal[] = [
			"fin_branch_id"=>$dataH->fin_branch_id,
			"fst_account_code"=>$accKelebihanBayar,
			"fdt_trx_datetime"=>$dataH->fdt_cbreceive_datetime,
			"fst_trx_sourcecode"=>"CBIN",
			"fin_trx_id"=>$dataH->fin_cbreceive_id, 
			"fst_trx_no"=>$dataH->fst_cbreceive_no,
			"fst_reference"=>$dataH->fst_memo,
			"fdc_debit"=> 0,
			"fdc_origin_debit"=>0,
			"fdc_credit"=> $dataItem->fdc_receive_amount * $dataH->fdc_exchange_rate_idr,
			"fdc_origin_credit"=> $dataItem->fdc_receive_amount * 1,
			"fst_orgi_curr_code"=>$dataH->fst_curr_code,
			"fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
			"fst_no_ref_bank"=>null,
			"fin_pcc_id"=>null,
			"fin_relation_id"=>$dataH->fin_customer_id,
			"fst_active"=>"A",
			"fst_info"=>$glAccountInfo,
		];        

		//Jurnal Selisih Kurs     
		if ($dataH->fdc_exchange_rate_idr  != $dataD->fdc_exchange_rate_idr){

			$selisih = ($dataItem->fdc_receive_amount * $dataD->fdc_exchange_rate_idr) - ($dataItem->fdc_receive_amount * $dataH->fdc_exchange_rate_idr);

			if ($selisih < 0){
				$fstAccountCode =  getGLConfig("SELISIH_KURS_RUGI");
				$glAccountInfo = "SELISIH KURS RUGI";                
				
			}else{
				$fstAccountCode =  getGLConfig("SELISIH_KURS_UNTUNG");
				$glAccountInfo = "SELISIH KURS UNTUNG";
			}
				
			$debet = $selisih;
			$credit = 0;
						
			$dataJurnal[] = [
				"fin_branch_id"=>$dataD->fin_branch_id,
				"fst_account_code"=>$fstAccountCode,
				"fdt_trx_datetime"=>$dataH->fdt_cbreceive_datetime,
				"fst_trx_sourcecode"=>"CBIN",
				"fin_trx_id"=>$dataItem->fin_cbreceive_id,
				"fst_trx_no"=>$dataH->fst_cbreceive_no,
				"fst_reference"=>$dataD->fst_inv_no . " | " . $dataH->fst_memo,
				"fdc_debit"=> $debet,
				"fdc_origin_debit"=>$debet,
				"fdc_credit"=>$credit,
				"fdc_origin_credit"=>$credit,
				"fst_orgi_curr_code"=>"IDR",
				"fdc_orgi_rate"=>1,
				"fst_no_ref_bank"=>null,
				"fin_pcc_id"=>null,
				"fin_relation_id"=>null,
				"fst_active"=>"A",
				"fst_info"=>$glAccountInfo
			];
		}        
		return $dataJurnal;
	}	

	public function checkIsValidClaimPaymentOver($finCbReceiveDetailId){
		$ssql = "select * from trcbreceiveitems where fin_rec_id = ?";
		$qr = $this->db->query($ssql,[$finCbReceiveDetailId]);
		$rw = $qr->row();
		if($rw == null){
			throw new CustomException(lang("ID Kelebihan Pembayaran tidak dikenal !"),9009,"FAILED",null);
		}

		if($rw->fdc_receive_amount < $rw->fdc_receive_amount_claimed){
			throw new CustomException(lang("Total klaim kelebihan pembayaran melebih nilainya"),3003,"FAILED",null);
		}
	}

	public function getDataJurnalPostingClaimExpedition($dataItem,$dataH){
		//var_dump($dataItem);
		/*
		$ssql = "SELECT a.*,b.fst_cbreceive_no,b.fst_curr_code,b.fdc_exchange_rate_idr FROM trcbreceiveitems a
			INNER JOIN trcbreceive b on a.fin_cbreceive_id = b.fin_cbreceive_id 
			WHERE a.fin_rec_id = ?   
			AND b.fst_active != 'D'";
		*/
		$ssql = "SELECT a.* FROM trsalesekspedisi a
			WHERE a.fin_salesekspedisi_id = ? AND a.fst_active != 'D'";


		$qr =$this->db->query($ssql,[$dataItem->fin_trans_id]);
		$dataD = $qr->row();
		if($dataD == null){
			throw new CustomException("ID klaim Ekspedis tidak dikenal !",9009,"FAILED",null);
		}

		$maxClaim = (float) $dataD->fdc_total - (float) $dataD->fdc_total_claimed;
		if ($maxClaim < (float) $dataItem->fdc_receive_amount){
			throw new CustomException(sprintf(lang("Maksimum klaim ekspedisi %s"),formatNumber($maxClaim)),3003,"FAILED",null);
		}

		

		$dataJurnal = [];
		$accPiutangEkspedisi = getGLConfig("PIUTANG_EKSPEDISI_PENJUALAN");    
		$glAccountInfo = "CLAIM BIAYA EKSPEDISI";
				
		$dataJurnal[] = [
			"fin_branch_id"=>$dataH->fin_branch_id,
			"fst_account_code"=>$accPiutangEkspedisi,
			"fdt_trx_datetime"=>$dataH->fdt_cbreceive_datetime,
			"fst_trx_sourcecode"=>"CBIN",
			"fin_trx_id"=>$dataH->fin_cbreceive_id, 
			"fst_trx_no"=>$dataH->fst_cbreceive_no,
			"fst_reference"=>$dataH->fst_memo,
			"fdc_debit"=> 0,
			"fdc_origin_debit"=>0,
			"fdc_credit"=> $dataItem->fdc_receive_amount * $dataH->fdc_exchange_rate_idr,
			"fdc_origin_credit"=> $dataItem->fdc_receive_amount * 1,
			"fst_orgi_curr_code"=>$dataH->fst_curr_code,
			"fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
			"fst_no_ref_bank"=>null,
			"fin_pcc_id"=>null,
			"fin_relation_id"=>$dataH->fin_customer_id,
			"fst_active"=>"A",
			"fst_info"=>$glAccountInfo,
		];        

		//Jurnal Selisih Kurs     
		if ($dataH->fdc_exchange_rate_idr  != $dataD->fdc_exchange_rate_idr){

			$selisih = ($dataItem->fdc_receive_amount * $dataD->fdc_exchange_rate_idr) - ($dataItem->fdc_receive_amount * $dataH->fdc_exchange_rate_idr);

			if ($selisih < 0){
				$fstAccountCode =  getGLConfig("SELISIH_KURS_RUGI");
				$glAccountInfo = "SELISIH KURS RUGI";                
				
			}else{
				$fstAccountCode =  getGLConfig("SELISIH_KURS_UNTUNG");
				$glAccountInfo = "SELISIH KURS UNTUNG";
			}
				
			$debet = $selisih;
			$credit = 0;
						
			$dataJurnal[] = [
				"fin_branch_id"=>$dataD->fin_branch_id,
				"fst_account_code"=>$fstAccountCode,
				"fdt_trx_datetime"=>$dataH->fdt_cbreceive_datetime,
				"fst_trx_sourcecode"=>"CBIN",
				"fin_trx_id"=>$dataItem->fin_cbreceive_id,
				"fst_trx_no"=>$dataH->fst_cbreceive_no,
				"fst_reference"=>$dataD->fst_inv_no . " | " . $dataH->fst_memo,
				"fdc_debit"=> $debet,
				"fdc_origin_debit"=>$debet,
				"fdc_credit"=>$credit,
				"fdc_origin_credit"=>$credit,
				"fst_orgi_curr_code"=>"IDR",
				"fdc_orgi_rate"=>1,
				"fst_no_ref_bank"=>null,
				"fin_pcc_id"=>null,
				"fin_relation_id"=>null,
				"fst_active"=>"A",
				"fst_info"=>$glAccountInfo
			];
		}        
		return $dataJurnal;
	}
	
	public function checkIsValidClaimExpedition($finSalesEkspedisiId){
		$ssql = "select * from trsalesekspedisi where fin_salesekspedisi_id = ?";
		$qr = $this->db->query($ssql,[$finSalesEkspedisiId]);
		$rw = $qr->row();
		if($rw == null){
			throw new CustomException(lang("ID Claim Ekspedisi tidak dikenal !"),9009,"FAILED",null);
		}

		if($rw->fdc_total < $rw->fdc_total_claimed){
			throw new CustomException(lang("Total klaim kelebihan biaya ekspedisi melebih nilainya"),3003,"FAILED",null);
		}
	}

	


	public function getDataJurnalPostingClaimPaymentUnknown($dataItem,$dataH){
		$ssql = "SELECT * FROM glledger a
			WHERE a.fin_rec_id = ?   
			AND a.fst_active != 'D'";

		$qr =$this->db->query($ssql,[$dataItem->fin_trans_id]);
		$dataD = $qr->row();
		if($dataD == null){
			throw new CustomException("ID Pembayaran unknown tidak dikenal !",9009,"FAILED",null);
		}

		$maxClaim = abs((float) $dataD->fdc_origin_credit);
		if ($maxClaim < (float) $dataItem->fdc_receive_amount){
			throw new CustomException(sprintf(lang("Maksimum klaim kelebihan pembayaran %s"),formatNumber($maxClaim)),3003,"FAILED",null);
		}

		$dataJurnal = [];
		//Jurnal Piutang dagang Lokal atau Import
		$accUnknownPayment = getGLConfig("UNKNOWN_CASH_BANK_RECEIVE");   
		$glAccountInfo = "CLAIM PEMBAYARAN TIDAK DIKENAL";
				
		$dataJurnal[] = [
			"fin_branch_id"=>$dataH->fin_branch_id,
			"fst_account_code"=>$accUnknownPayment,
			"fdt_trx_datetime"=>$dataH->fdt_cbreceive_datetime,
			"fst_trx_sourcecode"=>"CBIN",
			"fin_trx_id"=>$dataH->fin_cbreceive_id, 
			"fst_trx_no"=>$dataH->fst_cbreceive_no,
			"fst_reference"=>$dataH->fst_memo,
			"fdc_debit"=> 0,
			"fdc_origin_debit"=>0,
			"fdc_credit"=> $dataItem->fdc_receive_amount * $dataH->fdc_exchange_rate_idr,
			"fdc_origin_credit"=> $dataItem->fdc_receive_amount * 1,
			"fst_orgi_curr_code"=>$dataH->fst_curr_code,
			"fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
			"fst_no_ref_bank"=>null,
			"fin_pcc_id"=>null,
			"fin_relation_id"=>$dataH->fin_customer_id,
			"fst_active"=>"A",
			"fst_info"=>$glAccountInfo,
		];        

		//Jurnal Selisih Kurs     
		if ($dataH->fdc_exchange_rate_idr  != $dataD->fdc_orgi_rate){

			$receiveAmount = abs($dataItem->fdc_receive_amount*1);
			$selisih = ( $receiveAmount * $dataD->fdc_exchange_rate_idr) - ($receiveAmount * $dataH->fdc_exchange_rate_idr);

			if ($selisih < 0){
				$fstAccountCode =  getGLConfig("SELISIH_KURS_UNTUNG");
				$glAccountInfo = "SELISIH KURS UNTUNG";                               
			}else{
				$fstAccountCode =  getGLConfig("SELISIH_KURS_RUGI");
				$glAccountInfo = "SELISIH KURS RUGI";                 
			}
				
			$debet = 0;
			$credit = $selisih;
						
			$dataJurnal[] = [
				"fin_branch_id"=>$dataD->fin_branch_id,
				"fst_account_code"=>$fstAccountCode,
				"fdt_trx_datetime"=>$dataH->fdt_cbreceive_datetime,
				"fst_trx_sourcecode"=>"CBIN",
				"fin_trx_id"=>$dataItem->fin_cbreceive_id,
				"fst_trx_no"=>$dataH->fst_cbreceive_no,
				"fst_reference"=>$dataD->fst_inv_no . " | " . $dataH->fst_memo,
				"fdc_debit"=> $debet,
				"fdc_origin_debit"=>$debet,
				"fdc_credit"=>$credit,
				"fdc_origin_credit"=>$credit,
				"fst_orgi_curr_code"=>"IDR",
				"fdc_orgi_rate"=>1,
				"fst_no_ref_bank"=>null,
				"fin_pcc_id"=>null,
				"fin_relation_id"=>null,
				"fst_active"=>"A",
				"fst_info"=>$glAccountInfo
			];
		}



		return $dataJurnal;
	}

	public function getDataJurnalPostingSellFixedAsset($dataItem,$dataH){
		$ssql = "select * from trfadisposal where fin_fa_disposal_id = ? ";
		$qr =$this->db->query($ssql,[$dataItem->fin_trans_id]);
		$dataD = $qr->row();
		if($dataD == null){
			throw new CustomException("ID Penjualan Disposal tidak dikenal !",9009,"FAILED",null);
		}

		$maxClaim = abs((float) $dataD->fdc_sell_total);
		if ($maxClaim < (float) $dataItem->fdc_receive_amount){
			throw new CustomException(sprintf(lang("Maksimum klaim kelebihan pembayaran %s"),formatNumber($maxClaim)),3003,"FAILED",null);
		}

		$dataJurnal = [];
		//Jurnal Piutang dagang Lokal atau Import
		$accPiutangSellFA = getGLConfig("PIUTANG_PENJUALAN_FIXED_ASSET");   
		$glAccountInfo = "PEMBAYARAN PIUTANG FA";
				
		$dataJurnal[] = [
			"fin_branch_id"=>$dataH->fin_branch_id,
			"fst_account_code"=>$accPiutangSellFA,
			"fdt_trx_datetime"=>$dataH->fdt_cbreceive_datetime,
			"fst_trx_sourcecode"=>"CBIN",
			"fin_trx_id"=>$dataH->fin_cbreceive_id, 
			"fst_trx_no"=>$dataH->fst_cbreceive_no,
			"fst_reference"=>$dataH->fst_memo,
			"fdc_debit"=> 0,
			"fdc_origin_debit"=>0,
			"fdc_credit"=> $dataItem->fdc_receive_amount * $dataD->fdc_sell_exchange_rate_idr,
			"fdc_origin_credit"=> $dataItem->fdc_receive_amount * 1,
			"fst_orgi_curr_code"=>$dataH->fst_curr_code,
			"fdc_orgi_rate"=>$dataD->fdc_sell_exchange_rate_idr,
			"fst_no_ref_bank"=>null,
			"fin_pcc_id"=>null,
			"fin_relation_id"=>$dataH->fin_customer_id,
			"fst_active"=>"A",
			"fst_info"=>$glAccountInfo,
		];        

		//Jurnal Selisih Kurs     
		if ($dataH->fdc_exchange_rate_idr  != $dataD->fdc_sell_exchange_rate_idr){

			$receiveAmount = abs($dataItem->fdc_receive_amount*1);
			$selisih = ( $receiveAmount * $dataD->fdc_sell_exchange_rate_idr) - ($receiveAmount * $dataH->fdc_exchange_rate_idr);

			if ($selisih < 0){
				$fstAccountCode =  getGLConfig("SELISIH_KURS_UNTUNG");
				$glAccountInfo = "SELISIH KURS UNTUNG";                               
			}else{
				$fstAccountCode =  getGLConfig("SELISIH_KURS_RUGI");
				$glAccountInfo = "SELISIH KURS RUGI";                 
			}
				
			$debet = 0;
			$credit = abs($selisih);
						
			$dataJurnal[] = [
				"fin_branch_id"=>$dataD->fin_branch_id,
				"fst_account_code"=>$fstAccountCode,
				"fdt_trx_datetime"=>$dataH->fdt_cbreceive_datetime,
				"fst_trx_sourcecode"=>"CBIN",
				"fin_trx_id"=>$dataItem->fin_cbreceive_id,
				"fst_trx_no"=>$dataH->fst_cbreceive_no,
				"fst_reference"=>$dataD->fst_fa_disposal_no . " | " . $dataH->fst_memo,
				"fdc_debit"=> $debet,
				"fdc_origin_debit"=>$debet,
				"fdc_credit"=>$credit,
				"fdc_origin_credit"=>$credit,
				"fst_orgi_curr_code"=>"IDR",
				"fdc_orgi_rate"=>1,
				"fst_no_ref_bank"=>null,
				"fin_pcc_id"=>null,
				"fin_relation_id"=>null,
				"fst_active"=>"A",
				"fst_info"=>$glAccountInfo
			];
		}



		return $dataJurnal;
	}

	

	public function getDataById($finCBReceiveId){
		$ssql = "select a.*,b.fst_type as fst_kasbank_type from " .$this->tableName. " a 
			INNER JOIN mskasbank b on a.fin_kasbank_id = b.fin_kasbank_id 
			WHERE a.fin_cbreceive_id = ? and a.fst_active != 'D'";

		$qr = $this->db->query($ssql, [$finCBReceiveId]);
		$rwCBReceive = $qr->row();
		if ($rwCBReceive == null){
			return null;
		}

		$ssql = "select * from trcbreceiveitems where fin_cbreceive_id = ?";
		$qr = $this->db->query($ssql,[$finCBReceiveId]);        
		$rsCBReceiveItems = $qr->result();
		for($i=0;$i < count($rsCBReceiveItems) ; $i++){
			
			$trans = $this->getTransactionInfo( $rsCBReceiveItems[$i]->fst_trans_type,$rsCBReceiveItems[$i]->fin_trans_id);
			
			//$trans = (array) $rsCBReceiveItems[$i];
			$rsCBReceiveItems[$i]->fst_trans_no = $trans["fst_trans_no"];
			$rsCBReceiveItems[$i]->fdc_trans_amount = $trans["fdc_trans_amount"];
			$rsCBReceiveItems[$i]->fdc_paid_amount = $trans["fdc_paid_amount"];
			$rsCBReceiveItems[$i]->fdc_return_amount = $trans["fdc_return_amount"];            
		}
		
		$ssql = "select a.*,b.fst_glaccount_name,c.fst_pcc_name,
			d.fst_department_name as fst_pc_divisi_name,
			e.fst_relation_name as fst_pc_customer_name,
			f.fst_project_name as fst_pc_project_name,
			g.fst_relation_name 
			from trcbreceiveitemstype a 
			INNER JOIN glaccounts b on a.fst_glaccount_code = b.fst_glaccount_code 
			LEFT JOIN msprofitcostcenter c on a.fin_pcc_id = c.fin_pcc_id  
			LEFT JOIN departments d on a.fin_pc_divisi_id = d.fin_department_id  
			LEFT JOIN msrelations e on a.fin_pc_customer_id = e.fin_relation_id  
			LEFT JOIN msprojects f on a.fin_pc_project_id = f.fin_project_id  
			LEFT JOIN msrelations g on a.fin_relation_id = g.fin_relation_id  
			WHERE a.fin_cbreceive_id = ?";

		$qr = $this->db->query($ssql,[$finCBReceiveId]);
		$rsCBReceiveItemsType = $qr->result();
		$data = [
			"cbreceive" => $rwCBReceive,
			"cbreceive_items" => $rsCBReceiveItems,
			"cbreceive_items_type" => $rsCBReceiveItemsType
		];
		return $data;
	}


	public function getTransactionInfo($transType,$transId){        
		if($transType == "DP_SO"){
			$ssql ="select * from trsalesorder where fin_salesorder_id = ?";
			$qr =$this->db->query($ssql,[$transId]);
			$rw = $qr->row();
			if(!$rw){
				return [
					"fst_trans_no"=>null,
					"fdc_trans_amount"=>0,
					"fdc_paid_amount"=>0,
					"fdc_return_amount"=>0
				];
			}else{
				return [
					"fst_trans_no"=>$rw->fst_salesorder_no,
					"fdc_trans_amount"=>$rw->fdc_downpayment,
					"fdc_paid_amount"=>$rw->fdc_downpayment_paid,
					"fdc_return_amount"=>0
				];
			}            
		}else if($transType == "INV_SO"){
			$ssql ="select * from trinvoice where fin_inv_id = ?";
			$qr =$this->db->query($ssql,[$transId]);
			$rw = $qr->row();
			if($rw == null){
				return [
					"fst_trans_no"=>null,
					"fdc_trans_amount"=>0,
					"fdc_paid_amount"=>0,
					"fdc_return_amount"=>0
				];
			}else{
				return [
					"fst_trans_no"=>$rw->fst_inv_no,
					"fdc_trans_amount"=>$rw->fdc_total,
					"fdc_paid_amount"=>$rw->fdc_total_paid,
					"fdc_return_amount"=>$rw->fdc_total_return
				];
			}
		}else if($transType == "RETURN_SO"){
			$ssql ="select * from trsalesreturn where fin_salesreturn_id = ?";
			$qr =$this->db->query($ssql,[$transId]);
			$rw = $qr->row();
			if($rw == null){
				return [
					"fst_trans_no"=>null,
					"fdc_trans_amount"=>0,
					"fdc_paid_amount"=>0,
					"fdc_return_amount"=>0
				];
			}else{
				return [
					"fst_trans_no"=>$rw->fst_salesreturn_no,
					"fdc_trans_amount"=>$rw->fdc_total * -1,
					"fdc_paid_amount"=>$rw->fdc_total_claimed * -1,
					"fdc_return_amount"=>0
				];
			}
		}else if($transType == "PAYMENT_OVER"){                       
			return [
				"fst_trans_no"=>null,
				"fdc_trans_amount"=>0,
				"fdc_paid_amount"=>0,
				"fdc_return_amount"=>0,
			];        
			
		}else if($transType == "CLAIM_PAYMENT_OVER"){
			$ssql = "SELECT a.*,b.fst_cbreceive_no from trcbreceiveitems a 
				INNER JOIN trcbreceive b on a.fin_cbreceive_id = b.fin_cbreceive_id
				WHERE a.fin_rec_id = ? and b.fst_active != 'D'";

			$qr = $this->db->query($ssql,[$transId]);
			$rw = $qr->row();
			if($rw == null){
				return [
					"fst_trans_no"=>null,
					"fdc_trans_amount"=>0,
					"fdc_paid_amount"=>0,
					"fdc_return_amount"=>0
				];
			}else{
				return [
					"fst_trans_no"=>$rw->fst_cbreceive_no,
					"fdc_trans_amount"=>$rw->fdc_receive_amount,
					"fdc_paid_amount"=>$rw->fdc_receive_amount_claimed,
					"fdc_return_amount"=>0,
				];
			}
		}else if($transType == "CLAIM_EXPEDITION"){

			$ssql = "SELECT a.* from trsalesekspedisi a 
				WHERE a.fin_salesekspedisi_id = ? and a.fst_active != 'D'";

			$qr = $this->db->query($ssql,[$transId]);
			$rw = $qr->row();
			if($rw == null){
				return [
					"fst_trans_no"=>null,
					"fdc_trans_amount"=>0,
					"fdc_paid_amount"=>0,
					"fdc_return_amount"=>0
				];
			}else{
				return [
					"fst_trans_no"=>$rw->fst_salesekspedisi_no,
					"fdc_trans_amount"=>$rw->fdc_total,
					"fdc_paid_amount"=>$rw->fdc_total_claimed,
					"fdc_return_amount"=>0,
				];
			}

		}else if($transType == "CLAIM_PAYMENT_UNKNOWN"){
			$ssql = "Select * from glledger where fin_rec_id = ?";
			$qr = $this->db->query($ssql,[$transId]);
			$rw = $qr->row();
			if($rw == null){
				return [
					"fst_trans_no"=>null,
					"fdc_trans_amount"=>0,
					"fdc_paid_amount"=>0,
					"fdc_return_amount"=>0
				];
			}else{
				return [
					"fst_trans_no"=>$rw->fst_trx_no,
					"fdc_trans_amount"=>$rw->fdc_origin_credit * -1,
					"fdc_paid_amount"=>0,
					"fdc_return_amount"=>0,
				];
			}
		}else if($transType == "SELL_FIXED_ASSET"){
			$ssql = "Select * from trfadisposal where fin_fa_disposal_id = ?";
			$qr = $this->db->query($ssql,[$transId]);
			$rw = $qr->row();
			if ($rw == null){
				return [
					"fst_trans_no"=>null,
					"fdc_trans_amount"=>0,
					"fdc_paid_amount"=>0,
					"fdc_return_amount"=>0
				];
			}else{
				return [
					"fst_trans_no"=>$rw->fst_fa_disposal_no,
					"fdc_trans_amount"=>$rw->fdc_sell_total,
					"fdc_paid_amount"=>$rw->fdc_sell_total_paid,
					"fdc_return_amount"=>0
				];
			}
		}

	}


	public function getDataHeaderById($finCBReceiveId){
		$ssql = "select * from trcbreceive where fin_cbreceive_id = ?";
		$qr = $this->db->query($ssql,[$finCBReceiveId]);
		$rw = $qr->row();
		return $rw;
		
	}

	public function isEditable($finCBReceiveId){
		//Untuk Kelebihan bayar tidak boleh sudah di gunakan
		$ssql = "select a.* from trcbreceiveitems a where a.fin_cbreceive_id = ?";
		$qr = $this->db->query($ssql,[$finCBReceiveId]);
		$rs = $qr->result();
		foreach($rs as $rw){
			if($rw->fst_trans_type == "PAYMENT_OVER"){
				//SUDAH DI CLAIMED
				if($rw->fdc_receive_amount_claimed > 0){
					throw new CustomException(lang("Transaksi Kelebihan Pembayaran telah digunakan !"),3003,'FAILED',null);
				}
			}            
		}

		return ["status"=>"SUCCESS","message"=>""];
	}

	public function getPaymentOverList($finCustId,$fstCurrCode){
		$ssql = "SELECT a.fin_cbreceive_id,a.fin_rec_id as fin_cbreceive_detail_id ,b.fst_cbreceive_no,a.fdc_receive_amount as fdc_total,a.fdc_receive_amount_claimed as fdc_total_claimed  
			FROM trcbreceiveitems a 
			INNER JOIN trcbreceive b on a.fin_cbreceive_id = b.fin_cbreceive_id             
			WHERE a.fst_trans_type ='PAYMENT_OVER' AND a.fdc_receive_amount > a.fdc_receive_amount_claimed 
			AND b.fin_customer_id = ?
			AND b.fst_curr_code = ? 
			AND b.fst_active = 'A'";
		$qr = $this->db->query($ssql,[$finCustId,$fstCurrCode]);
		return $qr->result();

	}

	public function getClaimableExpeditionList($finCustId,$fstCurrCode){
		$ssql = "SELECT a.fin_salesekspedisi_id,a.fst_salesekspedisi_no,a.fdc_total,a.fdc_total_claimed 
			FROM trsalesekspedisi a 
			WHERE a.fbl_reclaimable = 1 
			AND a.fdc_total > a.fdc_total_claimed 
			AND a.fin_customer_id = ?
			AND a.fst_curr_code = ? 
			AND a.fst_active = 'A'";
		$qr = $this->db->query($ssql,[$finCustId,$fstCurrCode]);
		return $qr->result();

	}



	public function getUnknownReceiveList($fstCurrCode){
		$accUnknownReceive = getGLConfig("UNKNOWN_CASH_BANK_RECEIVE");
		$ssql = "SELECT * FROM glledger where fst_account_code = ? and fbl_claimed_cashbank_receive_unknown = false and fst_active ='A' and fdc_origin_credit > 0  and fst_orgi_curr_code = ?";
		$qr = $this->db->query($ssql,[$accUnknownReceive,$fstCurrCode]);
		return $qr->result();

	}

	

	public function delete($finCBReceiveId,$softDelete=TRUE,$data=null){
		if($softDelete){
			$ssql = "UPDATE trcbreceiveitems set fst_active ='D' where fin_cbreceive_id = ?";
			$this->db->query($ssql,[$finCBReceiveId]);
			throwIfDBError();

			$ssql = "UPDATE trcbreceiveitemstype set fst_active ='D' where fin_cbreceive_id = ?";
			$this->db->query($ssql,[$finCBReceiveId]);
			throwIfDBError();
		}else{
			$ssql = "DELETE FROM trcbreceiveitems where fin_cbreceive_id = ?";
			$this->db->query($ssql,[$finCBReceiveId]);
			throwIfDBError();
			
			$ssql = "DELETE FROM trcbreceiveitemstype where fin_cbreceive_id = ?";
			$this->db->query($ssql,[$finCBReceiveId]);
			throwIfDBError();
		}        
		parent::delete($finCBReceiveId,$softDelete,$data);            
	}

	public function deleteDetail($finCBReceiveId){
		$ssql ="DELETE FROM trcbreceiveitems where fin_cbreceive_id = ?";
		$this->db->query($ssql,[$finCBReceiveId]);
		throwIfDBError();
		
		$ssql ="DELETE FROM trcbreceiveitemstype where fin_cbreceive_id = ?";
		$this->db->query($ssql,[$finCBReceiveId]);
		throwIfDBError();        
	}

	public function getDataVoucher($finCBReceiveId){
		$ssql ="SELECT a.*,
			b.fst_kasbank_name,
			c.fst_relation_name,
			d.fst_curr_name 
			FROM trcbreceive a
			INNER JOIN mskasbank b on a.fin_kasbank_id = b.fin_kasbank_id 
			INNER JOIN msrelations c on a.fin_customer_id = c.fin_relation_id 
			INNER JOIN mscurrencies d on a.fst_curr_code = d.fst_curr_code 
			WHERE a.fin_cbreceive_id = ?";
			
		$qr = $this->db->query($ssql,[$finCBReceiveId]);
		$header = $qr->row_array();
		$details =[];
		if ($header != null){      
			$ssql = "SELECT a.* 
				FROM trcbreceiveitems a
				WHERE a.fin_cbreceive_id = ?";
			$qr = $this->db->query($ssql,[$finCBReceiveId]);

			$details = $qr->result_array();

			$ssql ="select a.* from trcbreceiveitemstype a WHERE a.fin_cbreceive_id = ?";
			$qr = $this->db->query($ssql,[$finCBReceiveId]);
			$details2 = $qr->result_array();
		}
		return [
			"header"=>$header,
			"details"=>$details,
			"details2"=>$details2
		];
	}
}


