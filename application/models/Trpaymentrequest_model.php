<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Trpaymentrequest_model extends MY_Model {
	public $tableName = "trpaymentrequest";
	public $pkey = "fin_paymentrequest_id";

	public function __construct(){
		parent:: __construct();
	}

	public function getRules($mode="ADD",$id=0){
		$rules = [];

		$rules[] = [
			'field' => 'fst_paymentrequest_no',
			'label' => lang('No Payment Request'),
			'rules' => 'required',
			'errors' => array(
				'required' => lang('%s tidak boleh kosong'),
			)
		];
		$rules[] = [
			'field' => 'fst_company_code',
			'label' => lang('Company'),
			'rules' => 'required',
			'errors' => array(
				'required' => lang('%s tidak boleh kosong'),
			)
		];

		return $rules;
	}

	public function createObject($fin_paymentrequest_id){
        $CI = &get_instance();
        $paymentRequest = new PaymentRequest($this,$fin_paymentrequest_id);
        return $paymentRequest;
    }

	public function GeneratePaymentRequestNo($trDate = null) {
		$trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
		$tahun = date("Y/m", strtotime ($trDate));
		$activeBranch = $this->aauth->get_active_branch();
		$branchCode = "";
		if($activeBranch){
			$branchCode = $activeBranch->fst_branch_code;
		}
		$prefix = getDbConfig("payment_request_prefix") . "/" . $branchCode ."/";
		$query = $this->db->query("SELECT MAX(fst_paymentrequest_no) as max_id FROM trpaymentrequest where fst_paymentrequest_no like '".$prefix.$tahun."%'");
		$row = $query->row_array();

		$max_id = $row['max_id']; 
		
		$max_id1 =(int) substr($max_id,strlen($max_id)-5);
		
		$fst_tr_no = $max_id1 +1;
		
		$max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);
		
		return $max_tr_no;
	}

	public function getDataById($finPaymentRequestId){

        $ssql = "SELECT * FROM " .$this->tableName. " WHERE fin_paymentrequest_id = ? AND fst_active != 'D'";
		$qr = $this->db->query($ssql,[$finPaymentRequestId]);
		$dataH = $qr->row();

		if ($dataH == null){
			return [
				"header"=>null,
				"detail"=>null
			];
		}

		$ssql ="SELECT * FROM trpaymentrequestitems WHERE fin_paymentrequest_id = ?";
		$qr = $this->db->query($ssql,[$finPaymentRequestId]);
		$detailList = $qr->result();

		return [
			"header"=>$dataH,
			"detail"=>$detailList
		];
	}
	
	public function getDataHeaderById($finPaymentRequestId){
		$ssql ="SELECT * FROM trpaymentrequest WHERE fin_paymentrequest_id = ?";
		$qr = $this->db->query($ssql, [$finPaymentRequestId]);
		return $qr->row();

	}

	public function unposting($finPaymentRequestId){

		$ssql ="select * from trpaymentrequest where fin_paymentrequest_id = ?";
		$qr = $this->db->query($ssql,[$finPaymentRequestId]);
		$dataH = $qr->row_array();

		if($dataH == null){
			throw new CustomException("Invalid Payment Request Id",3003,"FAILED",null);
		}

		//Delete Data Approval
		$this->trverification_model->cancelAuthorize("PP",$finPaymentRequestId);		
	}

	public function posting($finPaymentRequestId){

		$ssql ="select * from trpaymentrequest where fin_paymentrequest_id = ?";
		$qr = $this->db->query($ssql,[$finPaymentRequestId]);
		$dataH = $qr->row_array();

		if($dataH == null){
			throw new CustomException("Invalid Payment Request Id",3003,"FAILED",null);
		}

		if ($dataH["fst_active"] == 'S') {
			//Create Approval record
			$this->load->model("trverification_model");
			$message = "Payment Request " .$dataH["fst_paymentrequest_no"] ." Need Approval";
			$this->trverification_model->createAuthorize("PP","default",$finPaymentRequestId,$message,null,$dataH["fst_paymentrequest_no"]);
		}

		return [
			"status"=>"SUCCESS",
			"message"=>""
		];

	}

	public function update($dataH){
		//Delete Field yang tidak boleh berubah
		//unset($data["fin_relation_id"]);
		unset($dataH["fst_paymentrequest_no"]);
		parent::update($dataH);        
	}

	public function approved($finPaymentRequestId,$approved = true){
		
		if($approved){
			$data = [
				"fin_paymentrequest_id"=>$finPaymentRequestId,
				"fst_active"=>"A"
			];        
			parent::update($data);            
			$result = $this->posting($finPaymentRequestId);            
		}else{
			$data = [
				"fin_paymentrequest_id"=>$finPaymentRequestId,
				"fst_active"=>"R"
			];        
			parent::update($data);            
		}
		

		return [
			"status"=>"SUCCESS",
			"message"=>""
		] ;      
	}

	public function cancelApproval($finPaymentRequestId){
		$ssql = "select * from trpaymentrequest where fin_paymentrequest_id = ? and fst_active = 'A' ";
		$qr = $this->db->query($ssql,[$finPaymentRequestId]);
		$rw = $qr->row();
		
		if ($rw == null){
			$resp =["status"=>"FAILED","message"=>lang("ID PAYMENT REQUEST tidak dikenal !")];
			return $resp;
		}
		
		$ssql = "UPDATE trpaymentrequest SET fst_active ='S' where fin_paymentrequest_id = ?";
		$this->db->query($ssql,[$finPaymentRequestId]);
		
		return ["status"=>"SUCCESS",""];
	}


	public function delete($finPaymentRequestId,$softDelete = true,$data=null){
		//cek jika sudah di Approve
		//Cek trverification
		//NV = Need Verification, RV = Ready to verification, VF=Verified, RJ= Rejected, VD= Void
		$ssql = "SELECT * FROM trverification WHERE 
			fin_branch_id = ? and fst_controller ='PP' and fin_transaction_id = ? and fst_verification_status in ?";

		$qr = $this->db->query($ssql,[
			$this->aauth->get_active_branch_id(), 
			$finPaymentRequestId,
			['VF','RJ','VD'] 
		]);
		/*if($qr->row() != null){
			$resp =["status"=>"FAILED","message"=>lang("Payment Request tidak dapat dihapus karena sudah terjadi proses approval !") ];
			return $resp;
		}*/
		parent::delete($finPaymentRequestId,$softDelete,$data);
		if(!$softDelete){
			$this->db->delete("trpaymentrequestitems",array("fin_paymentrequest_id"=>$finPaymentRequestId));
		}
		

		return [
			"status"=>true,
			"message"=>"",
		];
	}

	public function show_transaction($finPaymentRequestId){
		redirect(site_url()."tr/kas_bank/payment_request/view/$finPaymentRequestId", 'refresh');
	}

	public function isDeletable($finPaymentRequestId){
		/**
		 * FALSE CONDITION
		 * + PO yang sudah ada status approve tidak bisa di edit lagi
		 * + sudah terima dp tidak boleh dirubah lagi
		 * + sudah ada penerimaan barang tidak boleh dirubah
		 *        
		*/

		//Cek trverification
		//NV = Need Verification, RV = Ready to verification, VF=Verified, RJ= Rejected, VD= Void
		$ssql = "SELECT * FROM trverification WHERE 
			fin_branch_id = ? and fst_controller ='PP' and fin_transaction_id = ? and fst_verification_status in ?";

		$qr = $this->db->query($ssql,[
			$this->aauth->get_active_branch_id(), 
			$finPaymentRequestId,
			['VF','RJ','VD'] 
		]);
		if($qr->row() != null){
			$resp =["status"=>"FAILED","message"=>lang("PP tidak dapat dihapus karena sudah terjadi proses approval !") ];
			return $resp;
		}

		$resp =["status"=>"SUCCESS","message"=>""];
		return $resp;
	}

	public function isEditable($finPaymentRequestId){
		/**
		 * FALSE CONDITION
		 * + PO yang sudah ada status approve tidak bisa di edit lagi
		 * + sudah terima dp tidak boleh dirubah lagi
		 * + sudah ada penerimaan barang tidak boleh dirubah
		 *        
		*/

		//Cek trverification
		//NV = Need Verification, RV = Ready to verification, VF=Verified, RJ= Rejected, VD= Void
		$ssql = "SELECT * FROM trverification WHERE 
			fin_branch_id = ? and fst_controller ='PP' and fin_transaction_id = ? and fst_verification_status in ?";

		$qr = $this->db->query($ssql,[
			$this->aauth->get_active_branch_id(), 
			$finPaymentRequestId,
			['VF','RJ','VD'] 
		]);
		if($qr->row() != null){
			$resp =["status"=>"FAILED","message"=>lang("PP tidak dapat dirubah karena sudah terjadi proses approval !") ];
			return $resp;
		}

		$resp =["status"=>"SUCCESS","message"=>""];
		return $resp;
	}

	public function getDataVoucher($finPaymentRequestId){
		$ssql ="SELECT a.*,b.fst_username FROM trpaymentrequest a INNER JOIN users b on a.fin_insert_id = b.fin_user_id 
		 WHERE a.fin_paymentrequest_id = ? AND a.fst_active != 'D'";
			
		$qr = $this->db->query($ssql,[$finPaymentRequestId]);
		$header = $qr->row_array();
		
		$ssql = "SELECT * FROM trpaymentrequestitems WHERE fin_paymentrequest_id = ?";

		$qr = $this->db->query($ssql,[$finPaymentRequestId]);
		//var_dump($this->db->error());

		$details = $qr->result_array();

		return [
			"header"=>$header,
			"details"=>$details
		];
	}

	
}


