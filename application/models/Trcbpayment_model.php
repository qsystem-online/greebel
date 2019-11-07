<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trcbpayment_model extends MY_Model {
    public $tableName = "trcbpayment";
    public $pkey = "fin_cbpayment_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_cbpayment_no',
            'label' => 'No Pengeluaran',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];


        return $rules;
    }


    public function getDataById($finCBPaymentId){
        $ssql = "select a.*,b.fst_type as fst_kasbank_type from " .$this->tableName. " a 
            INNER JOIN mskasbank b on a.fin_kasbank_id = b.fin_kasbank_id 
            WHERE a.fin_cbpayment_id = ? and a.fst_active != 'D'";

        $qr = $this->db->query($ssql, [$finCBPaymentId]);
        $rwCBPayment = $qr->row();

        if (!$rwCBPayment){
            return null;
        }

        $ssql = "select * from trcbpaymentitems where fin_cbpayment_id = ?";
        $qr = $this->db->query($ssql,[$finCBPaymentId]);        
        $rsCBPaymentItems = $qr->result();

        for($i=0;$i < count($rsCBPaymentItems) ; $i++){
            $rsCBPaymentItems[$i]->fst_trans_no = $this->getTransactionNo( $rsCBPaymentItems[$i]->fst_trans_type,$rsCBPaymentItems[$i]->fin_trans_id);
        }
        
        $ssql = "select a.*,b.fst_glaccount_name,c.fst_pcc_name from trcbpaymentitemstype a 
            INNER JOIN glaccounts b on a.fst_glaccount_code = b.fst_glaccount_code 
            INNER JOIN msprofitcostcenter c on a.fin_pcc_id = c.fin_pcc_id  
            WHERE a.fin_cbpayment_id = ?";

		$qr = $this->db->query($ssql,[$finCBPaymentId]);
		$rsCBPaymentItemsType = $qr->result();
        

		$data = [
            "cbpayment" => $rwCBPayment,
            "cbpayment_items" => $rsCBPaymentItems,
            "cbpayment_items_type" => $rsCBPaymentItemsType
		];

		return $data;
    }



    public function getTransactionNo($transType,$transId){
        if($transType == "LPB_PO"){
            $ssql ="select * from trlpbpurchase where fin_lpbpurchase_id = ?";
            $qr =$this->db->query($ssql,[$transId]);
            $rw = $qr->row();
            if(!$rw){
                return null;
            }
            return $rw->fst_lpbpurchase_no;

        }else if($transType == "DP_PO"){
            $ssql ="select * from trpo where fin_po_id = ?";
            $qr =$this->db->query($ssql,[$transId]);
            $rw = $qr->row();
            if(!$rw){
                return null;
            }
            return $rw->fst_po_no;
        }

    }


    public function generateCBPaymentNo($finKasBankId, $trDate = null) {
        $trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
        $tahun = date("Y/m", strtotime ($trDate));
        $activeBranch = $this->aauth->get_active_branch();
        $branchCode = "";
        if($activeBranch){
            $branchCode = $activeBranch->fst_branch_code;
        }


        //$prefix = getDbConfig($prefixKey) . "/" . $branchCode ."/";
        $ssql ="select * from mskasbank where fin_kasbank_id = ?";
        $qr = $this->db->query($ssql,[$finKasBankId]);
        $rw = $qr->row();
        if(!$rw){
            return "";
        }

        $prefix =$rw->fst_prefix_pengeluaran;



        //$query = $this->db->query("SELECT MAX(fst_po_no) as max_id FROM $table where $field like '".$prefix.$tahun."%'");
        $query = $this->db->query("SELECT MAX(fst_cbpayment_no) as max_id FROM trcbpayment where fst_cbpayment_no like '".$prefix."/%/".$tahun."%'");

        $row = $query->row_array();

        $max_id = $row['max_id']; 
        
        $max_id1 =(int) substr($max_id,strlen($max_id)-5);
        
        $fst_tr_no = $max_id1 +1;
        
        $max_tr_no = $prefix."/". $branchCode .'/' .$tahun.'/'.sprintf("%05s",$fst_tr_no);
        
        return $max_tr_no;
    }

    public function getOutGiroAccount(){
        $glCode = getGLConfig("CB_OUT_GIRO_MUNDUR");
        $ssql = "select * from glaccounts where fst_glaccount_code = ?";
        $qr = $this->db->query($ssql,[$glCode]);
        $rw = $qr->row();
        return $rw;        
    }

    public function unposting($finCBPaymentId,$unpostingDateTime =""){
        //$this->load->model("kasbank_model");
        //$this->load->model("glledger_model");
        $unpostingDateTime = $unpostingDateTime == "" ? date("Y-m-d H:i:s") : $unpostingDateTime;

        $ssql ="select * from trcbpayment where fin_cbpayment_id = ?";        
        $qr = $this->db->query($ssql,[$finCBPaymentId]);        
        $dataH = $qr->row();

        //get Detail Transaksi
        $ssql ="select * from trcbpaymentitems where fin_cbpayment_id = ?";        
        $qr = $this->db->query($ssql,[$finCBPaymentId]);        
        $dataItems = $qr->result();        
        foreach($dataItems as $dataItem){            
            if ($dataItem->fst_trans_type == "LPB_PO"){
                $ssql = "update trlpbpurchase set fdc_total_paid = fdc_total_paid -  ? where fin_lpbpurchase_id = ?";
                $this->db->query($ssql,[$dataItem->fdc_payment,$dataItem->fin_trans_id]);
            }else if ($dataItem->fst_trans_type == "DP_PO"){
                $ssql = "update trpo set fdc_downpayment_paid = fdc_downpayment_paid -  ? where fin_po_id = ?";
                $this->db->query($ssql,[$dataItem->fdc_payment,$dataItem->fin_trans_id]);
            }             
        }

        $this->glledger_model->cancelJurnal("CBOUT",$dataH->fin_cbpayment_id,$unpostingDateTime);
    }

    public function posting($finCBPaymentId){
        $this->load->model("kasbank_model");
        $this->load->model("glledger_model");

        $ssql ="select * from trcbpayment where fin_cbpayment_id = ?";        
        $qr = $this->db->query($ssql,[$finCBPaymentId]);        
        $dataH = $qr->row();
    
        $acc = $this->kasbank_model->getDataById($dataH->fin_kasbank_id);
        $acc = $acc["ms_kasbank"];

        $defaultGLCode = $acc->fst_glaccount_code;

        $dataJurnal = [];
        

        //get Detail Transaksi
        $ssql ="select * from trcbpaymentitems where fin_cbpayment_id = ?";        
        $qr = $this->db->query($ssql,[$finCBPaymentId]);        
        $dataItems = $qr->result();        
        foreach($dataItems as $dataItem){
            if ($dataItem->fst_trans_type == "LPB_PO"){
                //Purchase Invoice
                $tmpArr =$this->getDataJurnalPostingLPBPurchase($dataItem,$dataH);
                foreach($tmpArr as $tmp){
                    $dataJurnal[] = $tmp;
                }
                $ssql = "update trlpbpurchase set fdc_total_paid = fdc_total_paid +  ? where fin_lpbpurchase_id = ?";
                $this->db->query($ssql,[$dataItem->fdc_payment,$dataItem->fin_trans_id]);

            }else if ($dataItem->fst_trans_type == "DP_PO"){
                //DP Purcahase
                $tmpArr = $this->getDataJurnalPostingDPPO($dataItem,$dataH);                
                foreach($tmpArr as $tmp){
                    $dataJurnal[] = $tmp;
                }
                $ssql = "update trpo set fdc_downpayment_paid = fdc_downpayment_paid +  ? where fin_po_id = ?";
                $this->db->query($ssql,[$dataItem->fdc_payment,$dataItem->fin_trans_id]);
            } 
        }

        //Get Detail Payment
        $ssql ="select * from trcbpaymentitemstype where fin_cbpayment_id = ?";        
        $qr = $this->db->query($ssql,[$finCBPaymentId]);        
        $dataPayments = $qr->result();
        foreach($dataPayments as $payment){
            $fstAccountCode = $defaultGLCode;
            if ($payment->fst_cbpayment_type == "TUNAI" || $payment->fst_cbpayment_type == "TRANSFER" ){
                $fstAccountCode = $defaultGLCode;
            }else if ($payment->fst_cbpayment_type == "GIRO"){
                $fstAccountCode = getGLConfig("CB_OUT_GIRO_MUNDUR");
            }else if ($payment->fst_cbpayment_type == "GLACCOUNT"){
                $fstAccountCode = $payment->fst_glaccount_code;
            }
            $dataJurnal[] = [
                "fin_branch_id"=>$dataH->fin_branch_id,
                "fst_account_code"=>$fstAccountCode,
                "fdt_trx_datetime"=>date("Y-m-d H:i:s"),
                "fst_trx_sourcecode"=>"CBOUT",
                "fin_trx_id"=>$finCBPaymentId,
                "fst_reference"=>null,
                "fdc_debit"=>0,
                "fdc_origin_debit"=>0,
                "fdc_credit"=>$payment->fdc_amount * $dataH->fdc_exchange_rate_idr,
                "fdc_origin_credit"=>$payment->fdc_amount,
                "fst_orgi_curr_code"=>$dataH->fst_curr_code,
                "fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
                "fst_no_ref_bank"=>null,
                "fst_profit_cost_center_code"=>null,
                "fin_relation_id"=>null,
                "fst_active"=>"A"
            ];  
        }  
        //Cek Balance Debet Vs Credit
        $totalDebet = 0;
        $totalCredit = 0;        
        foreach($dataJurnal as $jurnal){
            $totalDebet += $jurnal["fdc_debit"] * 1;
            $totalCredit += $jurnal["fdc_credit"] * 1;
        }

        if ($totalDebet !=  $totalCredit){
            //var_dump();            
            $result["status"] ="FAILED";
            $result["message"] ="Debet Vs Credit not balance !($totalDebet vs $totalCredit)";
            $result["data"] =$dataJurnal;
            return $result;
        }else{
            //var_dump($dataJurnal);
            $result = $this->glledger_model->createJurnal($dataJurnal);
        }               
        return $result;
       
    }

    public function update($data){
        //Cancel Transaksi
        $ssql ="delete from trcbpaymentitems where fin_cbpayment_id = ?";
        $this->db->query($ssql,$data["fin_cbpayment_id"]);        

        $ssql ="delete from trcbpaymentitemstype where fin_cbpayment_id = ?";
        $this->db->query($ssql,$data["fin_cbpayment_id"]);        

        parent::update($data);

    }


    public function getDataJurnalPostingDPPO($dataItem,$dataH){

        $ssql ="select * from trpo where fin_po_id = ?";
        $qr = $this->db->query($ssql,[$dataItem->fin_trans_id]);
        $rw = $qr->row();

        if(!$rw){            
            return null;
        }


        if ($rw->fdc_downpayment == 0){
            return null;
        }   


       
        $fstAccountCode = getGLConfig("AP_DAGANG_LOKAL");
        if ($rw->fbl_is_import == 1){
            $fstAccountCode = getGLConfig("AP_DAGANG_IMPORT");
        }

        $dataJurnal = [];
        $dataJurnal[] = [
            "fin_branch_id"=>$rw->fin_branch_id,
            "fst_account_code"=>$fstAccountCode,
            "fdt_trx_datetime"=>$dataH->fdt_cbpayment_datetime,
            "fst_trx_sourcecode"=>"CBOUT",
            "fin_trx_id"=>$dataItem->fin_cbpayment_id,
            "fst_reference"=>null,
            "fdc_debit"=> $dataItem->fdc_payment   * $rw->fdc_exchange_rate_idr,
            "fdc_origin_debit"=>$dataItem->fdc_payment,
            "fdc_credit"=>0,
            "fdc_origin_credit"=>0,
            "fst_orgi_curr_code"=>$rw->fst_curr_code,
            "fdc_orgi_rate"=>$rw->fdc_exchange_rate_idr,
            "fst_no_ref_bank"=>null,
            "fst_profit_cost_center_code"=>null,
            "fin_relation_id"=>$rw->fin_supplier_id,
            "fst_active"=>"A"
        ];

        //Cek selisih Kurs        
        if ($dataH->fdc_exchange_rate_idr  != $rw->fdc_exchange_rate_idr){
            
            $selisih = ($dataItem->fdc_payment * $rw->fdc_exchange_rate_idr) - ($dataItem->fdc_payment * $dataH->fdc_exchange_rate_idr);

            if ($selisih < 0){
                $fstAccountCode =  getGLConfig("SELISIH_KURS_RUGI");
                $debet =  $selisih * - 1;
                $credit = 0 ;
                                
            }else{
                $fstAccountCode =  getGLConfig("SELISIH_KURS_UNTUNG");
                $debet = 0;
                $credit = $selisih;
            }

            $dataJurnal[] = [
                "fin_branch_id"=>$rw->fin_branch_id,
                "fst_account_code"=>$fstAccountCode,
                "fdt_trx_datetime"=>$dataH->fdt_cbpayment_datetime,
                "fst_trx_sourcecode"=>"CBOUT",
                "fin_trx_id"=>$dataItem->fin_cbpayment_id,
                "fst_reference"=>null,
                "fdc_debit"=> $debet,
                "fdc_origin_debit"=>$debet,
                "fdc_credit"=>$credit,
                "fdc_origin_credit"=>$credit,
                "fst_orgi_curr_code"=>"IDR",
                "fdc_orgi_rate"=>1,
                "fst_no_ref_bank"=>null,
                "fst_profit_cost_center_code"=>null,
                "fin_relation_id"=>null,
                "fst_active"=>"A"
            ];
        }
        return $dataJurnal;
    }


    public function getDataJurnalPostingLPBPurchase($dataItem,$dataH){
        $dataJurnal = [];

        $ssql = "SELECT a.*,b.fbl_is_import  FROM trlpbpurchase a
         INNER JOIN trpo b on a.fin_po_id = b.fin_po_id 
         WHERE a.fin_lpbpurchase_id = ? and a.fst_active != 'D'";
        $qr =$this->db->query($ssql,[$dataItem->fin_trans_id]);

        $dataD = $qr->row();
        if($dataD == null){
            return $dataJurnal;
        }

        //Jurnal Hutang dagang Lokal atau Import
        if ($dataD->fbl_is_import == 1 ){
            $glAccountHutang = getGLConfig("AP_DAGANG_IMPORT");
            $glAccountInfo = "HUTANG DAGANG IMPORT";
        }else{
            $glAccountHutang = getGLConfig("AP_DAGANG_LOKAL");
            $glAccountInfo = "HUTANG DAGANG LOKA";
        }
    
        
        $dataJurnal[] = [
            "fin_branch_id"=>$dataD->fin_branch_id,
            "fst_account_code"=>$glAccountHutang,
            "fdt_trx_datetime"=>$dataH->fdt_cbpayment_datetime,
            "fst_trx_sourcecode"=>"CBOUT",
            "fin_trx_id"=>$dataItem->fin_cbpayment_id,
            "fst_reference"=>null,
            "fdc_debit"=> $dataItem->fdc_payment   * $dataD->fdc_exchange_rate_idr,
            "fdc_origin_debit"=>$dataItem->fdc_payment,
            "fdc_credit"=>0,
            "fdc_origin_credit"=>0,
            "fst_orgi_curr_code"=>$dataD->fst_curr_code,
            "fdc_orgi_rate"=>$dataD->fdc_exchange_rate_idr,
            "fst_no_ref_bank"=>null,
            "fst_profit_cost_center_code"=>null,
            "fin_relation_id"=>$dataH->fin_supplier_id,
            "fst_active"=>"A",
            "fst_info"=>$glAccountInfo,
        ];

        //Jurnal Selisih Kurs     
        if ($dataH->fdc_exchange_rate_idr  != $dataD->fdc_exchange_rate_idr){

            $selisih = ($dataItem->fdc_payment * $dataD->fdc_exchange_rate_idr) - ($dataItem->fdc_payment * $dataH->fdc_exchange_rate_idr);

            if ($selisih < 0){
                $fstAccountCode =  getGLConfig("SELISIH_KURS_RUGI");
                $glAccountInfo = "SELISIH KURS RUGI";
                $debet = $selisih * - 1;
                $credit = 0;
                                
            }else{
                $fstAccountCode =  getGLConfig("SELISIH_KURS_UNTUNG");
                $glAccountInfo = "SELISIH KURS UNTUNG";
                $debet = 0 ;
                $credit = $selisih;
            }

            $dataJurnal[] = [
                "fin_branch_id"=>$dataD->fin_branch_id,
                "fst_account_code"=>$fstAccountCode,
                "fdt_trx_datetime"=>$dataH->fdt_cbpayment_datetime,
                "fst_trx_sourcecode"=>"CBOUT",
                "fin_trx_id"=>$dataItem->fin_cbpayment_id,
                "fst_reference"=>null,
                "fdc_debit"=> $debet,
                "fdc_origin_debit"=>$debet,
                "fdc_credit"=>$credit,
                "fdc_origin_credit"=>$credit,
                "fst_orgi_curr_code"=>"IDR",
                "fdc_orgi_rate"=>1,
                "fst_no_ref_bank"=>null,
                "fst_profit_cost_center_code"=>null,
                "fin_relation_id"=>null,
                "fst_active"=>"A",
                "fst_info"=>$glAccountInfo
            ];
        }

        return $dataJurnal;
    }

    function getUnpaidPurchaseInvoiceList($finSupplierId,$fstCurrCode){
        $ssql = "SELECT * FROM trlpbpurchase 
            WHERE fin_supplier_id = ? AND fst_curr_code = ? 
            AND (fdc_total > fdc_total_paid) 
            AND fst_active != 'D' ";
        $qr = $this->db->query($ssql,[$finSupplierId,$fstCurrCode]);
        //echo $this->db->last_query();
        $rs = $qr->result();
        return $rs;
    }


   



}


