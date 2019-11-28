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
        $ssql = "SELECT a.*,b.fst_po_no,b.fdt_po_datetime,b.fin_term,fdc_downpayment_paid,fdc_downpayment_claimed,c.fst_relation_name as fst_supplier_name FROM trlpbpurchase a 
            INNER JOIN trpo b ON a.fin_po_id = b.fin_po_id 
            INNER JOIN msrelations  c ON a.fin_supplier_id = c.fin_relation_id 
            WHERE fin_lpbpurchase_id = ? and a.fst_active != 'D'";


        $qr = $this->db->query($ssql, [$finLPBPurchaseId]);
        $rwLPBPurchase = $qr->row();

        if ($rwLPBPurchase == null){
            return null;
        }

        $ssql = "select a.*,b.fst_lpbgudang_no from trlpbpurchaseitems a 
            inner join trlpbgudang b on a.fin_lpbgudang_id = b.fin_lpbgudang_id 
            where a.fin_lpbpurchase_id = ?";

        $qr = $this->db->query($ssql,[$finLPBPurchaseId]);        
        $rsLPBPurchaseItems = $qr->result();

        $data = [
            "lpbPurchase" => $rwLPBPurchase,
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
        $ssql = "select distinct a.fin_po_id,b.fst_po_no,b.fin_supplier_id,c.fst_relation_name as fst_supplier_name 
            FROM trlpbgudang a 
            INNER JOIN trpo b on a.fin_po_id = b.fin_po_id 
            INNER JOIN msrelations c on b.fin_supplier_id = c.fin_relation_id 
            WHERE a.fin_lpbpurchase_id IS NULL and a.fst_active != 'D' ";
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
            WHERE fin_po_id = ? AND fin_lpbpurchase_id IS NULL AND fst_active != 'D'";
        $qr = $this->db->query($ssql,[$finPOId]);
        $poDetails=$qr->result();

        $result =[
            "po"=>$po,
            "lpbgudang_list"=>$poDetails,
        ];

        return $result;

    }

    public function getListItemByLPBGudangIds($finLPBGudangIds){
        $ssql ="SELECT b.fin_item_id,c.fst_item_code,b.fst_custom_item_name,b.fst_unit,b.fdc_price,b.fst_disc_item,SUM(a.fdb_qty) as fdb_qty_total 
            FROM trlpbgudangitems a 
            INNER JOIN trpodetails b ON a.fin_po_detail_id = b.fin_po_detail_id 
            INNER JOIN msitems c ON b.fin_item_id = c.fin_item_id 
            WHERE fin_lpbgudang_id IN ? 
            GROUP BY b.fin_item_id,c.fst_item_code,b.fst_custom_item_name,b.fst_unit,b.fdc_price,b.fst_disc_item";


        $qr = $this->db->query($ssql,[$finLPBGudangIds]);
        $rs = $qr->result();
        return $rs;
    }

    public function posting($finLPBPurchaseId){
        $this->load->model("glledger_model");

        $ssql = "SELECT a.*,b.fbl_is_import,b.fdc_downpayment,b.fdc_downpayment_paid,b.fbl_dp_inc_ppn FROM trlpbpurchase a 
            INNER JOIN trpo b ON a.fin_po_id = b.fin_po_id 
            WHERE fin_lpbpurchase_id = ?";

        $qr = $this->db->query($ssql,[$finLPBPurchaseId]);
        $dataH = $qr->row();
        
        if($dataH == null){
            $result = [
                "status"=>"failed",
                "message"=>"Invalid LPB Purchase ID",
            ];
            return $result;
        }
        
        $ssql = "select * from trlpbpurchaseitems where fin_lpbpurchase_id = ?";
        $qr= $this->db->query($ssql,[$finLPBPurchaseId]);
        $rs = $qr->result();
        foreach($rs as $rw){
            $ssql ="update trlpbgudang set fin_lpbpurchase_id = ? where fin_lpbgudang_id = ?";
            $this->db->query($ssql,[$finLPBPurchaseId,$rw->fin_lpbgudang_id]);
        }

        //Update claimed downpayment di PO
        $ssql = "update trpo set fdc_downpayment_claimed = fdc_downpayment_claimed + " . $dataH->fdc_downpayment_claim . " where fin_po_id = ?";
        $this->db->query($ssql,[$dataH->fin_po_id]);
                

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

        
        $ssql = "CREATE TEMPORARY TABLE tmp_result  
            SELECT c.fin_po_detail_id,f.fin_pcc_id, c.fdc_price,c.fst_disc_item,0 as fdc_disc_amount,sum(b.fdb_qty) as fdb_qty FROM trlpbpurchaseitems a 
            INNER JOIN trlpbgudangitems b ON a.fin_lpbgudang_id = b.fin_lpbgudang_id
            INNER JOIN trpodetails c ON b.fin_po_detail_id = c.fin_po_detail_id
            INNER JOIN msitems d ON c.fin_item_id = d.fin_item_id
            INNER JOIN msgroupitems e ON d.fin_item_group_id = e.fin_item_group_id
            INNER JOIN msgroupitems f ON f.fin_item_group_id = SUBSTRING(e.fst_tree_id,1,INSTR(e.fst_tree_id,'.')-1)
            WHERE a.fin_lpbpurchase_id = ? 
            GROUP  BY c.fin_po_detail_id,f.fin_pcc_id, c.fdc_price,c.fst_disc_item";

        $qr = $this->db->query($ssql,[$finLPBPurchaseId]);

        $ssql = "update tmp_result   set fdc_disc_amount = 0" ;
        $qr = $this->db->query($ssql);

        $ssql = "select * from tmp_result";
        $qr = $this->db->query($ssql,[]);
        $rs = $qr->result();
    

        foreach($rs as $rw ){
            $ttlDisc = calculateDisc($rw->fst_disc_item,($rw->fdb_qty * $rw->fdc_price));
            $ssql = "update tmp_result set fdc_disc_amount = ? where fin_po_detail_id = ? and fin_pcc_id = ?";    
            $this->db->query($ssql,[$ttlDisc,$rw->fin_po_detail_id,$rw->fin_pcc_id]);
    
        }
        $ssql = "select * from tmp_result";
        $qr = $this->db->query($ssql,[]);

        $ssql = "select fin_pcc_id,sum(fdb_qty * fdc_price) as fdc_total,sum(fdc_disc_amount) as fdc_ttl_disc_amount  from tmp_result group by fin_pcc_id";
        $qr = $this->db->query($ssql,[]);
        $rs = $qr->result();

        foreach($rs as $rw){                    
            //PEMBELIAN
            $dataJurnal[] =[
                "fin_branch_id"=>$dataH->fin_branch_id,
                "fst_account_code"=>$glAccount,
                "fdt_trx_datetime"=>date("Y-m-d H:i:s"),
                "fst_trx_sourcecode"=>"PINV", //PURCHASE INVOICE
                "fin_trx_id"=>$finLPBPurchaseId,
                "fst_reference"=>null,
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

        $ssql = "DROP TEMPORARY TABLE IF EXISTS tmp_result";
        $qr = $this->db->query($ssql,[]);
        


        
        //PPN
        //APAKAH DP SUDAH ADA UNSUR PPN ATAU TIDAK
        if ($dataH->fbl_dp_inc_ppn){
            $ttlPpn = $dataH->fdc_ppn_amount;            
            $dpClaim = $dataH->fdc_downpayment_claim;
            $dpClaim = $dpClaim / (1 + $dataH->fdc_ppn_percent / 100);
            $ttlPpn += floatval($dataH->fdc_downpayment_claim) - $dpClaim;

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
            "fst_reference"=>null,
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
            "fin_relation_id"=>null,
            "fst_active"=>"A",
            "fst_info"=>"HUTANG DAGANG"
        ];      
        
        $result = $this->glledger_model->createJurnal($dataJurnal);
        if ($result["status"] != "SUCCESS"){
            return $result;
        }

        $result=[
            "status"=>"SUCCESS",
            "message"=>""
        ];

        return $result;
       
    }

    public function unposting($finLPBPurchaseId,$unpostingDateTime =""){
        $this->load->model("glledger_model");

        //trpo : unpost fdc_downpayment_claimed
        //trlpbgudang : unpost fin_lpbpurchase_id
        //glledger: unpost jurnal
        $unpostingDateTime = $unpostingDateTime == "" ? date("Y-m-d H:i:s") : $unpostingDateTime;

        $ssql ="select * from trlpbpurchase where fin_lpbpurchase_id = ?";
        $qr = $this->db->query($ssql,[$finLPBPurchaseId]);
        $dataH = $qr->row();
        if($dataH == null){
            return [
                "status"=>"FAILED",
                "message"=>lang("Invalid Purchase Invoice")
            ];
        }
        $ssql = "update trpo set fdc_downpayment_claimed = fdc_downpayment_claimed - " . $dataH->fdc_downpayment_claim . " where fin_po_id = ?";
        $this->db->query($ssql,[$dataH->fin_po_id]);

        $ssql = "update trlpbgudang set fin_lpbpurchase_id = NULL where fin_lpbpurchase_id = ?";
        $this->db->query($ssql,[$finLPBPurchaseId]);


        $this->glledger_model->cancelJurnal("PINV",$finLPBPurchaseId,$unpostingDateTime);

        $result=[
            "status"=>"SUCCESS",
            "message"=>""
        ];

        $dbError  = $this->db->error();
		if ($dbError["code"] != 0){	
            $result["status"]= "FAILED";
            $result["message"]= $dbError["message"];            
			return $result;
        }                
        return $result;
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

    public function update($data){
        //Cancel Transaksi
        $ssql ="delete from trlpbpurchaseitems where fin_lpbpurchase_id = ?";
        $this->db->query($ssql,$data["fin_lpbpurchase_id"]);        
        parent::update($data);
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

}


