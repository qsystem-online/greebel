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
            'field' => 'fin_sj_id',
            'label' => 'Nomor surat jalan',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];
        $rules[] = [
            'field' => 'fdt_inv_date',
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
        $ssql ="select * from trinvoice where fin_inv_id = ?";
        
        $qr = $this->db->query($ssql,[$invId]);
        $rw = $qr->row();
        if($rw === false){
            return [];
        }
        $ssql = "select * from trinvoicedetails where fin_inv_id = ?";
        $qr = $this->db->query($ssql,[$invId]);
        $rs = $qr->result();
        $rw->details = $rs;
        return $rw;
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
    public function generateInvoiceNo($invDate = null) {
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
    }

    public function get_select2_uninvoice_sj($excInvId = 0){
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

        $qr = $this->db->query($ssql,[$excInvId]);
        //echo $this->db->last_query();
        //die();
        $rs = $qr->result();

        for($i = 0; $i < sizeof($rs);$i++){
            $rw = $rs[$i];
            $rs[$i]->details = $this->detailBySJ($rw->id);
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

    public function posting($fin_inv_id){

        $this->load->model("glledger_model");
        $ssql = "select * from trinvoice where fin_inv_id = ?";
        $qr = $this->db->query($ssql,[$fin_inv_id]);
        $rw = $qr->row();
        if($rw === false){
            return;
        }

        //Update SJ
        $ssql = "UPDATE trsuratjalan SET fin_inv_id = ? where fin_sj_id = ? ";
        $this->db->query($ssql,[$fin_inv_id,$rw->fin_sj_id]);
        
        //get Jurnal pos account
        $accDPiutang = getGLConfig("SO_PIUTANG");
        $accDDisc = getGLConfig("INV_DISC");
        $accDUM = getGLConfig("SO_DP");
        $accCSales =getGLConfig("INV_SALES");
        $accCPPN =getGLConfig("INV_PPN");        
        $piutang = (float) $rw->fdc_dpp_amount - (float) $rw->fdc_downpayment_claimed + (float) $rw->fdc_vat_amount;
        $disc = (float) $rw->fdc_disc_amount;
        $dp =(float) $rw->fdc_downpayment_claimed;
        $sales = (float) $rw->fdc_dpp_amount + (float) $rw->fdc_disc_amount;
        $vat = (float) $rw->fdc_vat_amount;	

        $datas = [];
        //Piutang
        $data["fst_trx_sourcecode"] = "IV";
        $data["fin_trx_id"]= $fin_inv_id;
        $data["fin_branch_id"]= $this->aauth->get_active_branch_id();
        $data["fdt_trx_datetime"]=$rw->fdt_inv_date;
        $data["fst_account_code"]=$accDPiutang;
        $data["fst_reference"]= null;
        $data["fdc_debit"]= $piutang;
        $data["fdc_credit"]= 0;
        $data["fst_orgi_curr_code"] = $rw->fst_curr_code;
        $data["fdc_orgi_rate"]=$rw->fdc_exchange_rate_idr;
        $data["fin_relation_id"]=$rw->fin_relation_id;
        $data["fst_active"]="A";        
        $datas[] = $data;

        //Disc
        $data["fst_account_code"]= $accDDisc;
        $data["fdc_debit"]= $disc;        
        $datas[] = $data;
        
        //DP
        $data["fst_account_code"]= $accDUM;
        $data["fdc_debit"]= $dp;        
        $datas[] = $data;
        
        //sales
        $data["fst_account_code"]= $accCSales;
        $data["fdc_debit"]= 0;        
        $data["fdc_credit"]= $sales;        
        $datas[] = $data;
        
        //ppn
        $data["fst_account_code"]= $accCPPN;
        $data["fdc_credit"]= $vat;        
        $datas[] = $data;

        $this->glledger_model->createJurnal($datas);
        
    }

    public function unposting($fin_inv_id,$newTrxDate = ""){
        $this->load->model("glledger_model");
        $ssql = "UPDATE trsuratjalan SET fin_inv_id = null where fin_inv_id = ?";
        $this->db->query($ssql,[$fin_inv_id]);

        $this->glledger_model->cancelJurnal("IV",$fin_inv_id,$newTrxDate);
    }

    public function deleteDetail($invId){
        $ssql ="delete from trinvoicedetails where fin_inv_id = ?";
        $this->db->query($ssql,[$invId]);
    }
    public function delete($invId,$softdelete = true,$data=null){
        $this->load->model("trinvoicedetails_model");
        $this->unposting($invId);
        
        parent::delete($invId,$softdelete);
        
        $ssql ="select * from trinvoicedetails where fin_inv_id =?";
        $qr = $this->db->query($ssql,[$invId]);
        $rs = $qr->result();
        foreach($rs as $rw){
            $this->trinvoicedetails_model->delete($rw->fin_rec_id,$softdelete);
        }
        $ssql = "update trsuratjalan set fin_inv_id = null where fin_inv_id =?";
        $this->db->query($ssql,[$invId]);
    }

    public function test_exception(){
        $this->load->model("trinventory_model");

        echo "Ini di trinvoice before";
        
        $this->trinventory_model->test_exception();
        
        echo "Ini di trinvoice after";

    }
}
