<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trcbpaymentother_model extends MY_Model {
    public $tableName = "trcbpaymentother";
    public $pkey = "fin_cbpaymentoth_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_cbpaymentoth_no',
            'label' => 'No Penerimaan',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];


        return $rules;
    }

    public function getDataById($finCBPaymentOthId){
        $ssql = "SELECT a.*,b.fst_kasbank_name from  trcbpaymentother a  
            INNER JOIN mskasbank b on a.fin_kasbank_id = b.fin_kasbank_id 
            WHERE a.fin_cbpaymentoth_id = ? and a.fst_active != 'D'";

        $qr = $this->db->query($ssql, [$finCBPaymentOthId]);
        $dataH = $qr->row();
        if ($dataH == null){
            return [
                "dataH"=>null,
                "dataDetails"=>null
            ];
        }

        $ssql = "SELECT a.*,b.fst_glaccount_name,c.fst_pcc_name,
            d.fst_department_name as fst_pc_divisi_name,
            e.fst_relation_name as fst_pc_customer_name,
            f.fst_project_name as fst_pc_project_name,
            g.fst_relation_name FROM trcbpaymentotheritems a
            INNER JOIN glaccounts b on a.fst_glaccount_code = b.fst_glaccount_code 
            LEFT JOIN msprofitcostcenter c on a.fin_pcc_id = c.fin_pcc_id
            LEFT JOIN departments d on a.fin_pc_divisi_id = d.fin_department_id
            LEFT JOIN msrelations e on a.fin_pc_customer_id = e.fin_relation_id
            LEFT JOIN msprojects f on a.fin_pc_project_id = f.fin_project_id       
            LEFT JOIN msrelations g on a.fin_relation_id = g.fin_relation_id
            WHERE a.fin_cbpaymentoth_id = ?";
        $qr = $this->db->query($ssql,[$finCBPaymentOthId]);        
        $dataDetails = $qr->result();
        
		$data = [
            "dataH"=>$dataH,
            "dataDetails"=>$dataDetails
		];
		return $data;
    }

    public function getDataHeaderById($finCBPaymentOthId){
        $ssql = "select * from trcbpaymentother where fin_cbpaymentoth_id = ?";
        $qr = $this->db->query($ssql,[$finCBPaymentOthId]);        
        $rw = $qr->row();
        return $rw;
        
    }

    public function generateCBNo($finKasBankId, $trDate = null) {
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

    public function posting($finCBPaymentOthId){
        $this->load->model("kasbank_model");
        $this->load->model("kasbanknumbering_model");
        $this->load->model("glledger_model");

        $ssql ="select * from trcbpaymentother where fin_cbpaymentoth_id = ?";        
        $qr = $this->db->query($ssql,[$finCBPaymentOthId]);        
        $dataH = $qr->row();

        //get Detail Transaksi
        $ssql ="select * from trcbpaymentotheritems where fin_cbpaymentoth_id = ?";        
        $qr = $this->db->query($ssql,[$finCBPaymentOthId]);        
        $dataDetails = $qr->result();

        //log kasbank no
        $this->kasbanknumbering_model->log($dataH->fst_cbpaymentoth_no);

        $dataJurnal = [];
        if ($dataH->fdc_cash_transfer > 0){
            $kasBankAcc = $this->kasbank_model->getDataHeaderById($dataH->fin_kasbank_id);
            if ($kasBankAcc == null){
                throw new CustomException(lang("Invalid cash/bank ID"),3003,"FAILED",null);            
            }
            $fdcCashTransfer = floatval($dataH->fdc_cash_transfer);
            $dataJurnal[] = [
                "fin_branch_id"=>$dataH->fin_branch_id,
                "fst_account_code"=>$kasBankAcc->fst_gl_account_code,
                "fdt_trx_datetime"=>$dataH->fdt_cbpaymentoth_datetime,
                "fst_trx_sourcecode"=>"CBPO", //Jurnal Manual
                "fin_trx_id"=>$finCBPaymentOthId,
                "fst_trx_no"=>$dataH->fst_cbpaymentoth_no,
                "fst_reference"=>$dataH->fst_memo,
                "fdc_debit"=>0,
                "fdc_origin_debit"=>0,
                "fdc_credit"=>$fdcCashTransfer * $dataH->fdc_exchange_rate_idr,
                "fdc_origin_credit"=>$fdcCashTransfer,
                "fst_orgi_curr_code"=>$dataH->fst_curr_code,
                "fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
                "fst_no_ref_bank"=>null,
                "fin_pcc_id"=>null,
                "fin_pc_divisi_id"=>null,
                "fin_pc_customer_id"=>null,
                "fin_pc_project_id"=>null,
                "fin_relation_id"=>null,
                "fst_active"=>"A"
            ];

        }

        if ($dataH->fdc_bilyet > 0){
            $bilyetAcc = getGLConfig("CB_OUT_GIRO_MUNDUR");
            if ($bilyetAcc == ""){
                throw new CustomException(lang("Invalid cash/bank ID"),3003,"FAILED",null);            
            }
            $fdcBilyet = floatval($dataH->fdc_bilyet);
            $dataJurnal[] = [
                "fin_branch_id"=>$dataH->fin_branch_id,
                "fst_account_code"=>$bilyetAcc,
                "fdt_trx_datetime"=>$dataH->fdt_cbpaymentoth_datetime,
                "fst_trx_sourcecode"=>"CBPO", //Jurnal Manual
                "fin_trx_id"=>$finCBPaymentOthId,
                "fst_trx_no"=>$dataH->fst_cbpaymentoth_no,
                "fst_reference"=>$dataH->fst_memo,
                "fdc_debit"=>0,
                "fdc_origin_debit"=>0,
                "fdc_credit"=>$fdcBilyet * $dataH->fdc_exchange_rate_idr,
                "fdc_origin_credit"=>$fdcBilyet,
                "fst_orgi_curr_code"=>$dataH->fst_curr_code,
                "fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
                "fst_no_ref_bank"=>null,
                "fin_pcc_id"=>null,
                "fin_pc_divisi_id"=>null,
                "fin_pc_customer_id"=>null,
                "fin_pc_project_id"=>null,
                "fin_relation_id"=>null,
                "fst_active"=>"A"
            ];

        }

        

        foreach($dataDetails as $dataD){                
            $dataJurnal[] = [
                "fin_branch_id"=>$dataH->fin_branch_id,
                "fst_account_code"=>$dataD->fst_glaccount_code,
                "fdt_trx_datetime"=>$dataH->fdt_cbpaymentoth_datetime,
                "fst_trx_sourcecode"=>"CBPO", //Jurnal Manual
                "fin_trx_id"=>$finCBPaymentOthId,
                "fst_trx_no"=>$dataH->fst_cbpaymentoth_no,
                "fst_reference"=>$dataD->fst_notes,
                "fdc_debit"=>$dataD->fdc_debit * $dataH->fdc_exchange_rate_idr,
                "fdc_origin_debit"=>$dataD->fdc_debit,
                "fdc_credit"=>$dataD->fdc_credit * $dataH->fdc_exchange_rate_idr,
                "fdc_origin_credit"=>$dataD->fdc_credit,
                "fst_orgi_curr_code"=>$dataH->fst_curr_code,
                "fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
                "fst_no_ref_bank"=>null,
                "fin_pcc_id"=>$dataD->fin_pcc_id,
                "fin_pc_divisi_id"=>$dataD->fin_pc_divisi_id,
                "fin_pc_customer_id"=>$dataD->fin_pc_customer_id,
                "fin_pc_project_id"=>$dataD->fin_pc_project_id,
                "fin_relation_id"=>$dataD->fin_relation_id,
                "fst_active"=>"A"
            ];  
        }


        $result = $this->glledger_model->createJurnal($dataJurnal);            
        return $result;
       
    }

    public function unposting($finCBPaymentOthId,$unpostingDateTime =""){
        $this->load->model("glledger_model");
        $this->load->model("kasbanknumbering_model");

        $unpostingDateTime = $unpostingDateTime == "" ? date("Y-m-d H:i:s") : $unpostingDateTime;

        $ssql ="select * from trcbpaymentother where fin_cbpaymentoth_id = ?";        
        $qr = $this->db->query($ssql,[$finCBPaymentOthId]);        
        $dataH = $qr->row();

        //unlog kasbank no
        $this->kasbanknumbering_model->unlog($dataH->fst_cbpaymentoth_no);

        $this->glledger_model->cancelJurnal("CBPO",$dataH->fin_cbpaymentoth_id,$unpostingDateTime);
    }

    public function isEditable($finCBPaymentOthId){
        return ["status"=>"SUCCESS","message"=>""];
    }

    public function deleteDetail($finCBPaymentOthId){
        $ssql ="DELETE FROM trcbpaymentotheritems where fin_cbpaymentoth_id = ?";
        $this->db->query($ssql,[$finCBPaymentOthId]);
        throwIfDBError();    
    }

    public function delete($finCBPaymentOthId,$softDelete=TRUE,$data=null){
        if($softDelete){
            $ssql = "UPDATE trcbpaymentotheritems set fst_active ='D' where fin_cbpaymentoth_id = ?";
            $this->db->query($ssql,[$finCBReceiveId]);
            throwIfDBError();
        }else{
            $ssql = "DELETE FROM trcbpaymentotheritems where fin_cbpaymentoth_id = ?";
            $this->db->query($ssql,[$finCBReceiveId]);
            throwIfDBError();            
        }        
        parent::delete($finCBPaymentOthId,$softDelete,$data);            
    }

    public function getDataVoucher($finCBPaymentOthId){
		$ssql ="SELECT a.*,
			b.fst_kasbank_name,
			d.fst_curr_name 
			FROM trcbpaymentother a
			INNER JOIN mskasbank b on a.fin_kasbank_id = b.fin_kasbank_id 
			INNER JOIN mscurrencies d on a.fst_curr_code = d.fst_curr_code 
			WHERE a.fin_cbpaymentoth_id = ?";
			
		$qr = $this->db->query($ssql,[$finCBPaymentOthId]);
		$header = $qr->row_array();
		$details =[];
		if ($header != null){      
			$ssql = "SELECT a.*,b.fst_glaccount_name
				FROM trcbpaymentotheritems a
				INNER JOIN glaccounts b ON a.fst_glaccount_code =b.fst_glaccount_code
				WHERE a.fin_cbpaymentoth_id = ?";

			$qr = $this->db->query($ssql,[$finCBPaymentOthId]);

			$details = $qr->result_array();
		}
		return [
			"header"=>$header,
			"details"=>$details,
		];
	}

}


