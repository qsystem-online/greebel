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
        
        $ssql = "select a.*,b.fst_glaccount_name,c.fst_pcc_name,
            d.fst_department_name as fst_pc_divisi_name,
            e.fst_relation_name as fst_pc_customer_name,
            f.fst_project_name as fst_pc_project_name,
            g.fst_relation_name 
            from trcbpaymentitemstype a 
            INNER JOIN glaccounts b on a.fst_glaccount_code = b.fst_glaccount_code 
            LEFT JOIN msprofitcostcenter c on a.fin_pcc_id = c.fin_pcc_id  
            LEFT JOIN departments d on a.fin_pc_divisi_id = d.fin_department_id  
            LEFT JOIN msrelations e on a.fin_pc_customer_id = e.fin_relation_id  
            LEFT JOIN msprojects f on a.fin_pc_project_id = f.fin_project_id
            LEFT JOIN msrelations g on a.fin_relation_id = g.fin_relation_id
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

    public function getDataHeaderById($finCBPaymentId){
        $ssql = "select * from trcbpayment where fin_cbpayment_id = ?";
        $qr = $this->db->query($ssql,[$finCBPaymentId]);
        $rw = $qr->row();
        return $rw;
        
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
        $this->load->model("kasbanknumbering_model");
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
        
        /*
        $query = $this->db->query("SELECT MAX(fst_cbpayment_no) as max_id FROM trcbpayment where fst_cbpayment_no like '".$prefix."/%/".$tahun."%'");
        $row = $query->row_array();
        $max_id = $row['max_id'];     
        $max_id1 =(int) substr($max_id,strlen($max_id)-5);        
        $fst_tr_no = $max_id1 +1;
        */

        $fst_tr_no =  $this->kasbanknumbering_model->getKasBankNo($prefix."/%/" . $tahun ."%");   
        
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
        $this->load->model("glledger_model");
        $this->load->model("kasbanknumbering_model");
        $unpostingDateTime = $unpostingDateTime == "" ? date("Y-m-d H:i:s") : $unpostingDateTime;

        $ssql ="select * from trcbpayment where fin_cbpayment_id = ?";        
        $qr = $this->db->query($ssql,[$finCBPaymentId]);        
        $dataH = $qr->row();

        //unlog kasbank no
        $this->kasbanknumbering_model->unlog($dataH->fst_cbpayment_no);

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
                //cek valid DP_PO
                $result = $this->checkIsValidDPPO($dataItem->fin_trans_id);
                if($result["status"] != "SUCCESS"){
                    return $result;
                }
            }else if ($dataItem->fst_trans_type == "LPB_RETURN"){
                $ssql = "update trpurchasereturn set fdc_total_claimed = fdc_total_claimed - ? where fin_purchasereturn_id = ?";
                $this->db->query($ssql,[abs($dataItem->fdc_payment),$dataItem->fin_trans_id]);
                $result = $this->checkIsValidLPBReturn($dataItem->fin_trans_id);
                if($result["status"] != "SUCCESS"){
                    return $result;
                }
            }
        }
        $this->glledger_model->cancelJurnal("CBOUT",$dataH->fin_cbpayment_id,$unpostingDateTime);

        return ["status"=>"SUCCESS","message"=>""];
    }

    public function posting($finCBPaymentId){
        $this->load->model("kasbank_model");
        $this->load->model("kasbanknumbering_model");
        $this->load->model("glledger_model");

        $ssql ="select * from trcbpayment where fin_cbpayment_id = ?";        
        $qr = $this->db->query($ssql,[$finCBPaymentId]);        
        $dataH = $qr->row();
    
        $acc = $this->kasbank_model->getDataById($dataH->fin_kasbank_id);
        $acc = $acc["ms_kasbank"];

        $defaultGLCode = $acc->fst_glaccount_code;

        $dataJurnal = [];
        
        //log kasbank no
        $this->kasbanknumbering_model->log($dataH->fst_cbpayment_no);

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
                $result = $this->checkIsValidInvocePO($dataItem->fin_trans_id);
                if($result["status"] != "SUCCESS"){
                    return $result;
                }
            }else if ($dataItem->fst_trans_type == "DP_PO"){
                //DP Purcahase
                $tmpArr = $this->getDataJurnalPostingDPPO($dataItem,$dataH);                
                foreach($tmpArr as $tmp){
                    $dataJurnal[] = $tmp;
                }
                $ssql = "update trpo set fdc_downpayment_paid = fdc_downpayment_paid +  ? where fin_po_id = ?";
                $this->db->query($ssql,[$dataItem->fdc_payment,$dataItem->fin_trans_id]);
                //cek valid DP_PO
                $result = $this->checkIsValidDPPO($dataItem->fin_trans_id);
                if($result["status"] != "SUCCESS"){
                    return $result;
                }
            }else if ($dataItem->fst_trans_type == "LPB_RETURN"){
                $tmpArr = $this->getDataJurnalPostingLPBReturn($dataItem,$dataH);                
                foreach($tmpArr as $tmp){
                    $dataJurnal[] = $tmp;
                }
                $ssql = "update trpurchasereturn set fdc_total_claimed = fdc_total_claimed + ? where fin_purchasereturn_id = ?";
                $this->db->query($ssql,[abs($dataItem->fdc_payment),$dataItem->fin_trans_id]);
                $result = $this->checkIsValidLPBReturn($dataItem->fin_trans_id);
                if($result["status"] != "SUCCESS"){
                    return $result;
                }
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
                "fst_trx_no"=>$dataH->fst_cbpayment_no,
                "fst_reference"=>$payment->fst_referensi,
                "fdc_debit"=>0,
                "fdc_origin_debit"=>0,
                "fdc_credit"=>$payment->fdc_amount * $dataH->fdc_exchange_rate_idr,
                "fdc_origin_credit"=>$payment->fdc_amount,
                "fst_orgi_curr_code"=>$dataH->fst_curr_code,
                "fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
                "fst_no_ref_bank"=>null,
                "fin_pcc_id"=>$payment->fin_pcc_id,
                "fin_pc_divisi_id"=>$payment->fin_pc_divisi_id,
                "fin_pc_customer_id"=>$payment->fin_pc_customer_id,
                "fin_pc_project_id"=>$payment->fin_pc_project_id,
                "fin_relation_id"=>$payment->fin_relation_id,
                "fst_active"=>"A"
            ];  
        }         

        $result = $this->glledger_model->createJurnal($dataJurnal);            
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


    public function getDataJurnalPostingLPBReturn($dataItem,$dataH){

        $ssql ="select * from trpurchasereturn where fin_purchasereturn_id = ?";
        $qr = $this->db->query($ssql,[$dataItem->fin_trans_id]);
        $rw = $qr->row();
        if(!$rw){            
            return null;
        }
        
        
        $accHutang = $rw->fbl_is_import == 1 ? getGLConfig("AP_DAGANG_IMPORT") : getGLConfig("AP_DAGANG_LOKAL");
        $accReturn = $rw->fbl_is_import == 1 ? getGLConfig("RETURN_IMPORT") : getGLConfig("RETURN_LOKAL");

        $dataJurnal = [];
        $dataItem->fdc_payment = abs($dataItem->fdc_payment);
        $dataJurnal[] = [
            "fin_branch_id"=>$rw->fin_branch_id,
            "fst_account_code"=>$accHutang,
            "fdt_trx_datetime"=>$dataH->fdt_cbpayment_datetime,
            "fst_trx_sourcecode"=>"CBOUT",
            "fin_trx_id"=>$dataItem->fin_cbpayment_id,
            "fst_trx_no"=>$dataH->fst_cbpayment_no,
            "fst_reference"=>$rw->fst_purchasereturn_no . " | " .$dataH->fst_memo,
            "fdc_debit"=> 0,
            "fdc_origin_debit"=>0,
            "fdc_credit"=>$dataItem->fdc_payment   * $rw->fdc_exchange_rate_idr,
            "fdc_origin_credit"=>$dataItem->fdc_payment,
            "fst_orgi_curr_code"=>$rw->fst_curr_code,
            "fdc_orgi_rate"=>$rw->fdc_exchange_rate_idr,
            "fst_no_ref_bank"=>null,
            "fin_pcc_id"=>null,
            "fin_relation_id"=>$rw->fin_supplier_id,
            "fst_active"=>"A"
        ];
        

        //Cek selisih Kurs        
        if ($dataH->fdc_exchange_rate_idr  != $rw->fdc_exchange_rate_idr){            
            $selisih = ($dataItem->fdc_payment * $rw->fdc_exchange_rate_idr) - ($dataItem->fdc_payment * $dataH->fdc_exchange_rate_idr);

            if ($selisih < 0){
                $accSelisihKurs =  getGLConfig("SELISIH_KURS_UNTUNG");
                $debet = 0;
                $credit = $selisih * -1;
            }else{                
                $accSelisihKurs =  getGLConfig("SELISIH_KURS_RUGI");
                $debet =  $selisih;
                $credit = 0 ;
            }

            $dataJurnal[] = [
                "fin_branch_id"=>$rw->fin_branch_id,
                "fst_account_code"=>$accSelisihKurs,
                "fdt_trx_datetime"=>$dataH->fdt_cbpayment_datetime,
                "fst_trx_sourcecode"=>"CBOUT",
                "fin_trx_id"=>$dataItem->fin_cbpayment_id,
                "fst_trx_no"=>$dataH->fst_cbpayment_no,
                "fst_reference"=>$rw->fst_purchasereturn_no . " | " .$dataH->fst_memo,
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
            "fst_trx_no"=>$dataH->fst_cbpayment_no,
            "fst_reference"=>$rw->fst_po_no . " | " . $dataH->fst_memo,
            "fdc_debit"=> $dataItem->fdc_payment   * $rw->fdc_exchange_rate_idr,
            "fdc_origin_debit"=>$dataItem->fdc_payment,
            "fdc_credit"=>0,
            "fdc_origin_credit"=>0,
            "fst_orgi_curr_code"=>$rw->fst_curr_code,
            "fdc_orgi_rate"=>$rw->fdc_exchange_rate_idr,
            "fst_no_ref_bank"=>null,
            "fin_pcc_id"=>null,
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
                "fst_trx_no"=>$dataH->fst_cbpayment_no,
                "fst_reference"=>$rw->fst_po_no . " | " . $dataH->fst_memo,
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
            "fst_trx_no"=>$dataH->fst_cbpayment_no,
            "fst_reference"=>$dataD->fst_lpbpurchase_no . " | " . $dataH->fst_memo,
            "fdc_debit"=> $dataItem->fdc_payment   * $dataD->fdc_exchange_rate_idr,
            "fdc_origin_debit"=>$dataItem->fdc_payment,
            "fdc_credit"=>0,
            "fdc_origin_credit"=>0,
            "fst_orgi_curr_code"=>$dataD->fst_curr_code,
            "fdc_orgi_rate"=>$dataD->fdc_exchange_rate_idr,
            "fst_no_ref_bank"=>null,
            "fin_pcc_id"=>null,
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
                "fst_trx_no"=>$dataH->fst_cbpayment_no,
                "fst_reference"=>$dataD->fst_lpbpurchase_no . " | " . $dataH->fst_memo,
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


    function getUnpaidPurchaseInvoiceList($finSupplierId,$fstCurrCode){
        //tidak boleh ada transaksi return yg status closednya false(untuk memastikan return sudah diproses di gudang)

        $ssql = "SELECT a.* FROM trlpbpurchase a
            LEFT JOIN trpurchasereturn b on a.fin_lpbpurchase_id = b.fin_lpbpurchase_id 
            WHERE a.fin_supplier_id = ? AND a.fst_curr_code = ? 
            AND (a.fdc_total > (a.fdc_total_paid + a.fdc_total_return) ) 
            AND ifnull(b.fbl_is_closed,1) = 1 
            AND a.fst_active = 'A' and b.fst_active != 'D'";

        $qr = $this->db->query($ssql,[$finSupplierId,$fstCurrCode]);
        //echo $this->db->last_query();
        $rs = $qr->result();
        return $rs;
    }

    function getPurchaseReturnNonFakturList($finSupplierId,$fstCurrCode){
        $ssql ="select * from trpurchasereturn 
        where fin_supplier_id = ? and fst_curr_code = ?
        and fbl_non_faktur = 1 and fdc_total_claimed < fdc_total
        and fst_active ='A'";
        $qr = $this->db->query($ssql,[$finSupplierId,$fstCurrCode]);
        return $qr->result();
    }


    public function getAccountList(){
        $ssql ="SELECT a.*,b.fst_glaccount_type FROM glaccounts a 
            INNER JOIN glaccountmaingroups b on a.fin_glaccount_maingroup_id = b.fin_glaccount_maingroup_id 
            WHERE a.fst_active = 'A' 
            AND a.fst_glaccount_level != 'HD' 
            AND a.fbl_is_allow_in_cash_bank_module = 1";
        $qr = $this->db->query($ssql,[]);
        return $qr->result();
    }

    public function isEditable($finCBPaymentId){
        /**
         * 
         */
        //$ssql = "select ";

        return ["status"=>"SUCCESS","message"=>""];
    }

    public function delete($finCBPaymentId,$softDelete=TRUE,$data=null){
        $this->load->model("trcbpaymentitems_model");
        $this->load->model("trcbpaymentitemstype_model");
        
        $this->trcbpaymentitems_model->deleteByHeaderId($finCBPaymentId,$softDelete);
        $this->trcbpaymentitemstype_model->deleteByHeaderId($finCBPaymentId,$softDelete);
        parent::delete($finCBPaymentId,$softDelete,$data);
        
        $result = parent::getDBErrors();
        if($result["status"] != "SUCCESS"){
            return $result;
        }

        return ["status"=>"SUCCESS","message"=>""];
    }


    public function checkIsValidDPPO($finPOId){
        //Cek DP 
        $ssql = "select * from trpo where fin_po_id = ?";
        $qr = $this->db->query($ssql,[$finPOId]);
        $rw = $qr->row();
        if($rw != null){
            //Cek bila terjadi kelebihan pembayaran downpayment;
            if ($rw->fdc_downpayment_paid > $rw->fdc_downpayment){
                $result["status"] ="FAILED";
                $result["message"] = sprintf(lang("Nilai pembayaran DP untuk PO %s melebihi nilai DP !"),$rw->fst_po_no);
                $result["data"] = $rw;
                return $result;
            }
            //Cek bila terjadi downpayment claim > downpayment paid
            if ($rw->fdc_downpayment_paid < $rw->fdc_downpayment_claimed){
                $result["status"] ="FAILED";
                $result["message"] = sprintf(lang("Nilai DP yang dibayar kurang dari nilai DP yang telah di klaim, PO: %s  !"),$rw->fst_po_no);
                $result["data"] = $rw;
                return $result;
            }
            return ["status"=>"SUCCESS","message"=>""];
        }else{
            return ["status"=>"FAILED","message"=>lang("ID PO tidak dikenal !")];
        }


    }

    public function checkIsValidInvocePO($finLPBPurchaseId){
        $ssql = "select * from trlpbpurchase where fin_lpbpurchase_id = ?";
        $qr = $this->db->query($ssql,[$finLPBPurchaseId]);
        $rw = $qr->row();
        if($rw == null){
            return ["status"=>"FAILED",'message'=>lang("ID Invoice Pembelian tidak dikenal")];
        }

        //Pembayaran + Return melebihi jumlah tagihan
        if(($rw->fdc_total_paid + $rw->fdc_total_return) > $rw->fdc_total){
            return ["status"=>"FAILED","message"=>sprintf(lang("Total pembayaran dan retur Invoice %s melebih jumlah tagihan invoce !"),$rw->fst_lpbpurchase_no)];
        }   

        return ["status"=>"SUCCESS","message"=>""];

    }
    public function checkIsValidLPBReturn($finPurchaseReturnId){
        $ssql = "select * from trpurchasereturn where fin_purchasereturn_id = ?";
        $qr = $this->db->query($ssql,[$finPurchaseReturnId]);
        $rw = $qr->row();
        if($rw == null){
            return ["status"=>"FAILED",'message'=>lang("ID Invoice Pembelian tidak dikenal")];
        }

        //total klaim retur melebihi jumlah retur
        if($rw->fdc_total  < $rw->fdc_total_claimed){
            return ["status"=>"FAILED","message"=>sprintf(lang("Total klaim retur %s melebih jumlah retur yang tersisa !"),$rw->fst_purchasereturn_no)];
        }   

        return ["status"=>"SUCCESS","message"=>""];
    }

}


