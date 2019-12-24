<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class SalesInvoice {
    private $CI;
    private $rw;
    private $db;

    public function __construct($CI,$invId){
        $this->CI = $CI;
        $this->db = $CI->db;

        $ssql = "select * from trinvoice where fin_inv_id = ?";
        $qr = $this->CI->db->query($ssql,[$invId]);
        $this->rw = $qr->row();
        if ($this->rw == false){
            throw new Exception("Invalid ID");
        }
    }
    public function __debugInfo() {
        //support on php 5.6
        return [
            'rw' => $this->rw
        ];
    }
    public function __get($name){
        if (property_exists($this->rw,$name)){
            return $this->rw->$name;
        }else{
            throw new Exception("Invalid Property Name !");
        }
    }
    public function isPaid(){
        return false;
    }
}


class Trinvoice_model extends MY_Model {
    public $tableName = "trinvoice";
    public $pkey = "fin_inv_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fin_salesorder_id',
            'label' => lang('Sales Order'),
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];
        $rules[] = [
            'field' => 'fdt_inv_datetime',
            'label' => lang('Tgl Invoice'),
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];
        $rules[] = [
            'field' => 'fin_terms_payment',
            'label' => 'term',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];
        return $rules;
    }

    public function getDataById($invId){
        $ssql ="SELECT a.*,b.fst_salesorder_no,c.fst_relation_name as fst_customer_name,(b.fdc_downpayment_paid - b.fdc_downpayment_claimed) as fdc_downpayment_rest from trinvoice a 
            inner join trsalesorder b on a.fin_salesorder_id = b.fin_salesorder_id 
            inner join msrelations c on a.fin_relation_id = c.fin_relation_id 
            where a.fin_inv_id = ? and a.fst_active != 'D'";
        
        $qr = $this->db->query($ssql,[$invId]);
        $rw = $qr->row();
        

        $ssql = "select a.*,b.fst_sj_no from trinvoicedetails a 
            INNER JOIN trsuratjalan b on a.fin_sj_id = b.fin_sj_id 
            where a.fin_inv_id = ?";
        $qr = $this->db->query($ssql,[$invId]);
        $rs = $qr->result();
        
        return [
            "trinvoice"=>$rw,
            "trinvoicedetails"=>$rs,
        ];
    }

    public function getDataHeaderById ($finInvId){
        $ssql = "SELECT * FROM trinvoice where fin_inv_id = ? and fst_active != 'D'";
        $qr = $this->db->query($ssql,[$finInvId]);
        return $qr->row();
    }

    public function createObject($invId){
        $ci = & get_instance();
        try{
            $invoice = new SalesInvoice($ci,$invId);
            return $invoice;
        }catch(Exception $e){
            return null;
        }   
    }

    public function generateInvoiceNo($trDate = null) {
        /*
        $invDate = ($invDate == null) ? date ("Y-m-d"): $invDate;
        $tahun = date("ym", strtotime ($invDate));
        $prefix = getDbConfig("invoice_prefix");
        $query = $this->db->query("SELECT MAX(fst_inv_no) as max_id FROM trinvoice where fst_inv_no like '".$prefix.$tahun."%'");
        $row = $query->row_array();
        $max_id = $row['max_id']; 
        $max_id1 =(int) substr($max_id,8,5);
        $fst_inv_no = $max_id1 +1;
        $max_inv_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_inv_no);
        return $max_inv_no;
        */
        $trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
        $tahun = date("Y/m", strtotime ($trDate));
        $activeBranch = $this->aauth->get_active_branch();
        $branchCode = "";
        if($activeBranch){
            $branchCode = $activeBranch->fst_branch_code;
        }
        $prefix = getDbConfig("invoice_prefix") . "/" . $branchCode ."/";
        $query = $this->db->query("SELECT MAX(fst_inv_no) as max_id FROM trinvoice where fst_inv_no like '".$prefix.$tahun."%'");
        $row = $query->row_array();

        $max_id = $row['max_id']; 
        
        $max_id1 =(int) substr($max_id,strlen($max_id)-5);
        
        $fst_tr_no = $max_id1 +1;
        
        $max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);
        
        return $max_tr_no;
    }

    public function get_select2_uninvoice_sj($finSalesOrderId,$finWarehouseId = null){
        /*
        $ssql = "select a.fin_sj_id as id,a.fst_sj_no as text,a.fin_warehouse_id,c.fst_warehouse_name,
            b.fin_relation_id,f.fst_relation_name,b.fst_salesorder_no,b.fbl_is_vat_include,b.fin_branch_id,b.fin_terms_payment,b.fin_sales_id,b.fdc_downpayment_paid,
            d.fst_fullname as fst_sales_name,IFNULL(e.ttl_downpayment_claimed,0) as ttl_downpayment_claimed 
            from trsuratjalan a 
            inner join trsalesorder b on a.fin_salesorder_id = b.fin_salesorder_id
            inner join mswarehouse c on a.fin_warehouse_id = c.fin_warehouse_id
            inner join users d on b.fin_sales_id = d.fin_user_id            
            left join (select fin_salesorder_id,sum(fdc_downpayment_claimed) as ttl_downpayment_claimed from trinvoice group by fin_salesorder_id) e on a.fin_salesorder_id = e.fin_salesorder_id 
            inner join msrelations f on b.fin_relation_id = f.fin_relation_id 
            where (a.fin_inv_id is null || a.fin_inv_id = ?) 
            and a.fst_active = 'A'";
        */
        
        $ssql = "SELECT a.fin_sj_id,a.fst_sj_no,a.fdt_sj_datetime FROM trsuratjalan a
            WHERE fin_trans_id = ? AND fin_warehouse_id like ? 
            AND fin_inv_id IS NULL AND a.fst_active = 'A'";
        
        $finWarehouseId =  $finWarehouseId == null ? '%' : $finWarehouseId;
        $qr = $this->db->query($ssql,[$finSalesOrderId,$finWarehouseId]);
        $rs = $qr->result();
        for($i = 0; $i < sizeof($rs);$i++){
            $rw = $rs[$i];
            $rs[$i]->details = $this->detailBySJ($rw->fin_sj_id);
        }
        
        return $rs;
    }

    public function detailBySJ($fin_sj_id){
        $ssql = "select a.*,
        b.fst_custom_item_name,b.fdc_price,b.fst_disc_item,
        b.fbl_is_promo_disc,b.fin_promo_id            
        from trsuratjalandetails a
            inner join trsalesorderdetails b on a.fin_salesorder_detail_id = b.fin_rec_id
            where a.fin_sj_id = ?";
        $qr = $this->db->query($ssql,[$fin_sj_id]);
        $rsDetail = $qr->result();
        $rsDetail = ($rsDetail == false) ? [] : $rsDetail;
        return $rsDetail;
    }

    public function getDetailSJ($arrSJId){
        $ssql ="SELECT b.fin_promo_id,b.fin_item_id,b.fst_custom_item_name,b.fst_unit,b.fdc_price,b.fst_disc_item,b.fdc_disc_amount_per_item,b.fdb_qty as fdb_qty_so,sum(a.fdb_qty) as fdb_qty_sj 
            FROM trsuratjalandetails a 
            INNER JOIN trsalesorderdetails b on a.fin_trans_detail_id = b.fin_rec_id            
            WHERE fin_sj_id IN ?
            GROUP BY b.fin_promo_id,b.fin_item_id,b.fst_custom_item_name,b.fst_unit,b.fdc_price,b.fst_disc_item,b.fdc_disc_amount_per_item,b.fdb_qty";

        $qr = $this->db->query($ssql,[$arrSJId]);                

        $rs = $qr->result();
        return $rs;

    }

    public function posting($finInvId){
        $this->load->model("glledger_model");        
        $ssql = "SELECT * FROM trinvoice where fin_inv_id = ? and fst_active != 'D'";        
        $qr= $this->db->query($ssql,[$finInvId]);
        $dataH = $qr->row();
        if($dataH == null){
            throw new CustomException(lang("ID Invoice tidak dikenal !"),3003,"FAILED",["fin_inv_id" =>$finInvId]);
        }

        
        //UPDATE SALES ORDER bila ada DP yang di CLAIM
        $ssql = "UPDATE trsalesorder set fdc_downpayment_claimed = fdc_downpayment_claimed + ? where fin_salesorder_id = ?"; 
        $this->db->query($ssql,[$dataH->fdc_downpayment_claim,$dataH->fin_salesorder_id]);
        throwIfDBError();

        $ssql ="SELECT * FROM trsalesorder where fin_salesorder_id = ? and fdc_downpayment_paid < fdc_downpayment_claimed";
        $rw = $this->db->query($ssql,[$dataH->fin_salesorder_id])->row();
        if($rw != null){
            throw new CustomException(lang("DP yang di klaim melebih batas !"),3003,"FAILED",null);            
        }
        

        //UPDATE fin_inv_id di SURAT JALAN
        $ssql = "SELECT * FROM trinvoicedetails WHERE fin_inv_id = ? and fst_active != 'D'";
        $qr =$this->db->query($ssql,[$finInvId]);
        $dataDetail = $qr->result();
        foreach($dataDetail as $dataD){
            $ssql = "UPDATE trsuratjalan SET fin_inv_id = ? where fin_sj_id = ? ";
            $this->db->query($ssql,[$finInvId,$dataD->fin_sj_id]);
            throwIfDBError();
        }

        //POSTING GL (PROFIT CENTER BY ITEM)
        /**
         * PIUTANG
         * Disc
         * DP Claim
         *      SALES
         *      PPN
         * 
         */
        $accDPiutang = getGLConfig("AR_DAGANG_LOKAL");
        $accDDisc = getGLConfig("INV_DISC");
        $accDUM = getGLConfig("DP_IN_LOKAL");
        $accCSales =getGLConfig("INV_SALES");
        $accCPPN =getGLConfig("PPN_KELUARAN");        

        $piutang = (float) $dataH->fdc_dpp_amount - (float) $dataH->fdc_downpayment_claim + (float) $dataH->fdc_ppn_amount;
        $disc = (float) $dataH->fdc_disc_amount;
        $dpClaim = (float) $dataH->fdc_downpayment_claim;
        $sales = (float) $dataH->fdc_subttl;
        $ppn = (float) $dataH->fdc_ppn_amount;

        $dataJurnal = [];

        $dataJurnal[] =[ //PIUTANG
            "fin_branch_id"=>$dataH->fin_branch_id,
            "fst_account_code"=>$accDPiutang,
            "fdt_trx_datetime"=>$dataH->fdt_inv_datetime,
            "fst_trx_sourcecode"=>"SIV", //SALES INVOICE
            "fin_trx_id"=>$finInvId,
            "fst_trx_no"=>$dataH->fst_inv_no,
            "fst_reference"=>$dataH->fst_inv_memo,
            "fdc_debit"=> $piutang * $dataH->fdc_exchange_rate_idr,
            "fdc_origin_debit"=>$piutang,
            "fdc_credit"=>0,
            "fdc_origin_credit"=>0,
            "fst_orgi_curr_code"=>$dataH->fst_curr_code,
            "fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
            "fst_no_ref_bank"=>null,
            "fin_pcc_id"=>null,
            "fin_relation_id"=>$dataH->fin_relation_id,
            "fst_active"=>"A",
            "fst_info"=>"PIUTANG",
        ];

        

        $dataJurnal[] =[ //CLAIM DP
            "fin_branch_id"=>$dataH->fin_branch_id,
            "fst_account_code"=>$accDUM,
            "fdt_trx_datetime"=>$dataH->fdt_inv_datetime,
            "fst_trx_sourcecode"=>"SIV", //SALES INVOICE
            "fin_trx_id"=>$finInvId,
            "fst_trx_no"=>$dataH->fst_inv_no,
            "fst_reference"=>$dataH->fst_inv_memo,
            "fdc_debit"=> $dataH->fdc_downpayment_claim * $dataH->fdc_exchange_rate_idr,
            "fdc_origin_debit"=>$dataH->fdc_downpayment_claim,
            "fdc_credit"=>0,
            "fdc_origin_credit"=>0,
            "fst_orgi_curr_code"=>$dataH->fst_curr_code,
            "fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
            "fst_no_ref_bank"=>null,
            "fin_pcc_id"=>null,
            "fin_relation_id"=>null,
            "fst_active"=>"A",
            "fst_info"=>"DP CLAIM",
        ];


        //SALES PECAH PER PROFIT CENTER
        $ssql = "SELECT f.fin_pcc_id,sum(b.fdb_qty * c.fdc_price) as fdc_total,sum(b.fdb_qty * c.fdc_disc_amount_per_item) as fdc_total_disc_amount FROM trinvoicedetails a 
            inner join trsuratjalandetails b on a.fin_sj_id = b.fin_sj_id 
            inner join trsalesorderdetails c on b.fin_trans_detail_id = c.fin_rec_id
            inner join msitems d on c.fin_item_id = d.fin_item_id
            inner join msgroupitems e on d.fin_item_group_id = e.fin_item_group_id
            inner join msgroupitems f on SUBSTRING_INDEX(e.fst_tree_id,'.',1) = f.fin_item_group_id
            where a.fin_inv_id = ? and b.fst_active ='A' and c.fst_active ='A'
            Group by f.fin_pcc_id";
        
        $qr = $this->db->query($ssql,[$finInvId]);

        $rsSalesPerPCC = $qr->result();
        foreach($rsSalesPerPCC  as $salesPerPCC){
            
            //DISCOUNT PECAH PER PROFIT CENTER
            $dataJurnal[] =[ 
                "fin_branch_id"=>$dataH->fin_branch_id,
                "fst_account_code"=>$accDDisc,
                "fdt_trx_datetime"=>$dataH->fdt_inv_datetime,
                "fst_trx_sourcecode"=>"SIV", //SALES INVOICE
                "fin_trx_id"=>$finInvId,
                "fst_trx_no"=>$dataH->fst_inv_no,
                "fst_reference"=>$dataH->fst_inv_memo,
                "fdc_debit"=> $salesPerPCC->fdc_total_disc_amount * $dataH->fdc_exchange_rate_idr,
                "fdc_origin_debit"=>$salesPerPCC->fdc_total_disc_amount,
                "fdc_credit"=>0,
                "fdc_origin_credit"=>0,
                "fst_orgi_curr_code"=>$dataH->fst_curr_code,
                "fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
                "fst_no_ref_bank"=>null,
                "fin_pcc_id"=>$salesPerPCC->fin_pcc_id,
                "fin_relation_id"=>null,
                "fst_active"=>"A",
                "fst_info"=>"DISC",
            ];

            //CEK PPN INC ATAU EXCLUDE fbl_is_vat_include
            $salesAmount = 0;
            if ($dataH->fbl_is_vat_include == 1){
                $dpp = $salesPerPCC->fdc_total;
                $ppn = $dpp * ($dataH->fdc_ppn_percent /100);
                $salesAmount = $salesPerPCC->fdc_total - $ppn;
            }else{
                $salesAmount = $salesPerPCC->fdc_total;
            }

            $dataJurnal[] =[ //SALES PER PCC
                "fin_branch_id"=>$dataH->fin_branch_id,
                "fst_account_code"=>$accCSales,
                "fdt_trx_datetime"=>$dataH->fdt_inv_datetime,
                "fst_trx_sourcecode"=>"SIV", //SALES INVOICE
                "fin_trx_id"=>$finInvId,
                "fst_trx_no"=>$dataH->fst_inv_no,
                "fst_reference"=>$dataH->fst_inv_memo,
                "fdc_debit"=> 0,
                "fdc_origin_debit"=>0,
                "fdc_credit"=> $salesAmount * $dataH->fdc_exchange_rate_idr,
                "fdc_origin_credit"=>$salesAmount,
                "fst_orgi_curr_code"=>$dataH->fst_curr_code,
                "fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
                "fst_no_ref_bank"=>null,
                "fin_pcc_id"=>$salesPerPCC->fin_pcc_id,
                "fin_relation_id"=>null,
                "fst_active"=>"A",
                "fst_info"=>"SALES",
            ];
        }

        $dataJurnal[] =[ //PPN
            "fin_branch_id"=>$dataH->fin_branch_id,
            "fst_account_code"=>$accCPPN,
            "fdt_trx_datetime"=>$dataH->fdt_inv_datetime,
            "fst_trx_sourcecode"=>"SIV", //SALES INVOICE
            "fin_trx_id"=>$finInvId,
            "fst_trx_no"=>$dataH->fst_inv_no,
            "fst_reference"=>$dataH->fst_inv_memo,
            "fdc_debit"=> 0,
            "fdc_origin_debit"=>0,
            "fdc_credit"=> $ppn * $dataH->fdc_exchange_rate_idr,
            "fdc_origin_credit"=>$ppn,
            "fst_orgi_curr_code"=>$dataH->fst_curr_code,
            "fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
            "fst_no_ref_bank"=>null,
            "fst_profit_cost_center_code"=>null,
            "fin_relation_id"=>null,
            "fst_active"=>"A",
            "fst_info"=>"PPN",
        ];


        //var_dump($dataJurnal);
       // die();
   
        $this->glledger_model->createJurnal($dataJurnal);        
    }


    public function unposting($finInvId){
        $this->load->model("glledger_model");

        //DELETE Info inv id di surat jalan
        $ssql = "UPDATE trsuratjalan SET fin_inv_id = null where fin_inv_id = ?";
        $this->db->query($ssql,[$finInvId]);       
        throwIfDBError();
        
        //Update data DP yang di claim
        $ssql = "SELECT * FROM trinvoice where fin_inv_id = ? and fst_active != 'D'";
        $dataH = $this->db->query($ssql,[$finInvId])->row();
        if($dataH == null){
            throw new CustomException(lang("ID Invoice tidak dikenal !"),3003,"FAILED",null);            
        }
        $ssql ="UPDATE trsalesorder set fdc_downpayment_claimed = fdc_downpayment_claimed - ? where fin_salesorder_id = ?";
        $this->db->query($ssql,[$dataH->fdc_downpayment_claim,$dataH->fin_salesorder_id]);

        $this->glledger_model->cancelJurnal("SIV",$finInvId);
    }

    public function deleteDetail($invId){
        $ssql ="delete from trinvoicedetails where fin_inv_id = ?";
        $this->db->query($ssql,[$invId]);
        throwIfDBError();
        $ssql ="delete from trinvoiceitems where fin_inv_id = ?";
        $this->db->query($ssql,[$invId]);
        throwIfDBError();
        
    }

    public function delete($invId,$softdelete = true,$data=null){        
        if($softdelete){
            $ssql = "UPDATE trinvoicedetails set fst_active ='D' where fin_inv_id = ?";            
        }else{
            $ssql = "DELETE FROM trinvoicedetails where fin_inv_id = ?";            
        }
        $this->db->query($ssql,[$invId]);
        parent::delete($invId,$softdelete);            
    }

    public function test_exception(){
        $this->load->model("trinventory_model");

        echo "Ini di trinvoice before";
        
        $this->trinventory_model->test_exception();
        
        echo "Ini di trinvoice after";

    }

    public function getPastDueInvoiceOverToleranceList($toleranceDays,$finCustId){
        $ssql = "Select * from trinvoice 
            where fin_relation_id = ? 
            AND fdc_total > (fdc_total_paid + fdc_total_return)
            AND DATE_ADD(fdt_payment_due_date,INTERVAL ? DAY)  > CURDATE()
            AND fst_active ='A'";
        $qr = $this->db->query($ssql,[$finCustId,$toleranceDays]);
        return $qr->result();
    }

    public function getSalesOrderList(){
        $term = $this->input->get("term");
        $term = "%".$term."%";

        $ssql = "SELECT DISTINCT b.fin_salesorder_id,a.fin_warehouse_id, b.fst_salesorder_no,b.fst_curr_code,(b.fdc_downpayment_paid - b.fdc_downpayment_claimed) as fdc_downpayment_rest,b.fbl_is_vat_include, b.fdt_salesorder_datetime,b.fin_terms_payment,b.fin_sales_id,c.fst_relation_name as fst_customer_name 
            FROM trsuratjalan a 
            INNER JOIN trsalesorder b on a.fin_trans_id = b.fin_salesorder_id 
            INNER JOIN msrelations c on b.fin_relation_id = c.fin_relation_id 
            WHERE fin_inv_id IS NULL AND a.fst_active ='A' 
            AND (
                fst_relation_name like ? OR fst_salesorder_no like ?
            )";

        $qr = $this->db->query($ssql,[$term,$term]);       
        $rs = $qr->result();
        return $rs;
    }

    public function getUnpaidSalesInvoiceList($finCustId,$fstCurrCode){
        //Bisa di bayar kalau tidak ada pendingan return

        $ssql = "SELECT a.* 
            FROM trinvoice a
            LEFT JOIN trsalesreturnitems b on a.fin_inv_id = b.fin_inv_id
            INNER JOIN trsalesreturn c on b.fin_salesreturn_id = c.fin_salesreturn_id
            where a.fin_relation_id = ? and a.fst_curr_code = ? and a.fdc_total - a.fdc_total_return > a.fdc_total_paid and a.fst_active =! 'D'
            AND ifnull(c.fbl_is_closed,1) =  1";

        $qr =$this->db->query($ssql,[$finCustId,$fstCurrCode]);
        $rs = $qr->result();
        return $rs;
    }

    public function isEditable($dataH){
        /**
         * + Invoice belum dilakukan pembayaran
         * + Tidak ada transaksi return
         * 
         */
        if ($dataH->fdc_total_paid > 0 ){
            throw new CustomException(lang("Invoice tidak dapat di rubah karena sudah dilakukan pembayaran !"),3003,"FAILED",null);            
        }
        if ($dataH->fdc_total_return > 0 ){
            throw new CustomException(lang("Invoice tidak dapat di rubah karena sudah ada transakis return !"),3003,"FAILED",null);            
        }

    }
}
