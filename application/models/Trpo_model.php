<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class PurchaseOrder{
    private $rw;
    private $CI;
    public function __construct($CI,$fin_salesorder_id){
        $this->CI = $CI;
        $ssql = "Select * from trsalesorder where fin_salesorder_id = ?";
        $qr = $this->CI->db->query($ssql,[$fin_salesorder_id]);
        $this->rw  = $qr->row();

    }
    public function isClosed(){
        return $this->rw->fbl_is_closed;
    }    
    public function getValue($key){
        return $this->rw->$key;
    }

    public function getDetails(){
        $ssql = "Select * from trsalesorderdetails where fin_salesorder_id = ?";
        $qr = $this->CI->db->query($ssql,[$this->rw->fin_salesorder_id]);       
        $rs = $qr->result();
        return $rs;
    }
    public function getData(){
        return $this->rw;
    }

    public function isPromoWithSJ(){
        //transaksi ini memiliki promo item dan sudah ada surat jalan atas transaksi ini
        // Free Item, Free Custom Item, Free Cashback, Disc Per Item

        return false;
    }
    
    public function __get($name){
        if (property_exists($this->rw,$name)){
            return $this->rw->$name;
        }else{
            throw new Exception("Invalid Property Name !");
        }
    }

    public function getDPClaimed($salesOrderId,$excludeInvId = 0){
        $ssql = "select sum(fdc_downpayment_claimed) as fdc_downpayment_claimed from trinvoice where fin_salesorder_id = ? and fst_active ='A' and fin_inv_id != ?";
        $qr = $this->CI->db->query($ssql,[$salesOrderId,$excludeInvId]);
        $rw =$qr->row();
        $totalClaimed = $rw->fdc_downpayment_claimed;
        return $totalClaimed;
    }
    public function getDPAvailableToClaimed($salesOrderId,$excludeInvId = 0){        
        $totalClaimed =  $this->getDPClaimed($salesOrderId,$excludeInvId);
        return (float) $this->fdc_downpayment_paid - (float) $totalClaimed;
    }
}

class Trpo_model extends MY_Model {
    public $tableName = "trpo";
    public $pkey = "fin_po_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_po_no',
            'label' => 'Purchase Order No',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];


        return $rules;
    }

    public function createObject($fin_po_id){
        $CI = &get_instance();
        $purchaseOrder = new PurchaseOrder($this,$fin_po_id);
        return $purchaseOrder;
    }

    public function getDataById($fin_salesorder_id){
        $ssql = "select a.*,
            b.fst_relation_name,b.fin_sales_id,a.fin_shipping_address_id,b.fin_cust_pricing_group_id,
            c.fst_fullname as fst_sales_name 
            from trsalesorder a
            inner join msrelations b on a.fin_relation_id  = b.fin_relation_id 
            inner join users c on a.fin_sales_id  = c.fin_user_id             
            where a.fin_salesorder_id = ?";
        $qr = $this->db->query($ssql, [$fin_salesorder_id]);
        $rwSalesOrder = $qr->row();

        $ssql = "select a.*,b.fst_item_name,b.fst_item_code,b.fst_max_item_discount from trsalesorderdetails a 
        left join msitems b on a.fin_item_id = b.fin_item_id
        where a.fin_salesorder_id = ?";
		$qr = $this->db->query($ssql,[$fin_salesorder_id]);
		$rsSODetails = $qr->result();

		$data = [
            "sales_order" => $rwSalesOrder,
            "so_details" => $rsSODetails
		];

		return $data;
    }

    public function GeneratePONo($trDate = null) {
        $trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
        $tahun = date("ym", strtotime ($trDate));
        $prefix = getDbConfig("purchaseorder_prefix");
        $query = $this->db->query("SELECT MAX(fst_po_no) as max_id FROM trpo where fst_po_no like '".$prefix.$tahun."%'");
        $row = $query->row_array();
        $max_id = $row['max_id']; 
        $max_id1 =(int) substr($max_id,8,5);
        $fst_tr_no = $max_id1 +1;
        $max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);
        return $max_tr_no;
    }

    public function getDataOutstanding($fin_customer_id,$fdc_credit_limit,$current_so_id = 0){
        
        $fdc_credit_limit = (float) $fdc_credit_limit;
        $piutangOutstanding =0;
        $soOutstanding = 10000000;
        $billyetOutstanding = 0;
        $totalOutstanding = ($piutangOutstanding + $soOutstanding + $billyetOutstanding);        
        return [
            "maxCreditLimit"=>$fdc_credit_limit,
            "piutangOutstanding"=> $piutangOutstanding,
            "soOutstanding"=> $soOutstanding,
            "billyetOutstanding"=>$billyetOutstanding,
            "totalOutstanding"=> $totalOutstanding,
            "sisaPlafon"=> $fdc_credit_limit - $totalOutstanding,
            "dataFakturOutstanding"=>[],
        ];
    }
    
    private function isOnPromoItemRules($rules,$fin_item_id,$fin_item_subgroup_id){
        foreach($rules as $rule){
            if($rule->fst_item_type == 'SUB GROUP'){
                if ($fin_item_subgroup_id == $fin_item_id){
                    return true;
                }
            }else if ($rule->fst_item_type == 'ITEM') {
                if ($rule->fin_item_id == $fin_item_id){
                    return true;
                }
            }
        }
        return false;
    }

    
    public function posting($fin_salesorder_id){
        //Bila terdapat DP jurnal DP tersebut
        

        $ssql ="select * from trsalesorder where fin_salesorder_id = ?";
        $qr = $this->db->query($ssql,[$fin_salesorder_id]);
        $dataH = $qr->row_array();

        if ($dataH["fdc_downpayment"] > 0 && $dataH["fst_active"] == "A"){
            $this->load->model("glledger_model");
            $dataJurnal = [
                [
                    "fin_branch_id"=>$dataH["fin_branch_id"],
                    "fst_account_code"=>getGLConfig("SO_PIUTANG"),
                    "fdt_trx_datetime"=>$dataH["fdt_salesorder_date"],
                    "fst_trx_sourcecode"=>JURNAL_TRX_SC_SO,
                    "fin_trx_id"=>$dataH["fin_salesorder_id"],
                    "fst_reference"=>null,
                    "fdc_debit"=>$dataH["fdc_downpayment"],
                    "fdc_credit"=>0,
                    "fst_orgi_curr_code"=>$dataH["fst_curr_code"],
                    "fdc_orgi_rate"=>$dataH["fdc_exchange_rate_idr"],
                    "fst_no_ref_bank"=>null,
                    "fst_profit_cost_center_code"=>null,
                    "fin_relation_id"=>$dataH["fin_relation_id"],
                    "fst_active"=>"A"
                ],
                [
                    "fin_branch_id"=>$dataH["fin_branch_id"],
                    "fst_account_code"=>getGLConfig("SO_DP"),
                    "fdt_trx_datetime"=>$dataH["fdt_salesorder_date"],
                    "fst_trx_sourcecode"=>JURNAL_TRX_SC_SO,
                    "fin_trx_id"=>$dataH["fin_salesorder_id"],
                    "fst_reference"=>null,
                    "fdc_debit"=>0,
                    "fdc_credit"=>$dataH["fdc_downpayment"],
                    "fst_orgi_curr_code"=>$dataH["fst_curr_code"],
                    "fdc_orgi_rate"=>$dataH["fdc_exchange_rate_idr"],
                    "fst_no_ref_bank"=>null,
                    "fst_profit_cost_center_code"=>null,
                    "fin_relation_id"=>$dataH["fin_relation_id"],
                    "fst_active"=>"A"
                ],
            ];
            
            if($this->glledger_model->createJurnal($dataJurnal) === false){
                throw new Exception("Error Create Jurnal !", EXCEPTION_JURNAL);
            }
        }


    }

    public function update($data){
        //Delete Field yang tidak boleh berubah
        unset($data["fin_relation_id"]);
        unset($data["fst_salesorder_no"]);
        parent::update($data);        
    }

    public function approved($finSalesOrderId){

        $data = [
            "fin_salesorder_id"=>$finSalesOrderId,
            "fst_active"=>"A"
        ];
        parent::update($data);
        

        //Cek kalau semua proses verification sudah selesai
        $ssql = "select * from trverification 
        where fst_controller ='SO' 
        and fin_transaction_id = ? 
        and fst_verification_status != 'VF' 
        and fst_active='A'" ;

        $qr = $this->db->query($ssql,[$finSalesOrderId]);

        $rw = $qr->row();
        if ($rw == false){
            $this->posting($finSalesOrderId);
        }
    }


    //==== UNHOLD ===============================\\
    public function unhold($finSalesOrderId){
        
        $activeUser = $this->aauth->user();
        //print_r($activeUser);
    
        $data = [
            "fin_salesorder_id" => $finSalesOrderId,
            "fbl_is_hold" => "0", //Unhold Success
            "fin_unhold_id" => $activeUser->fin_user_id,
            //"fdt_unhold_datetime" => dBDateFormat("fdt_unhold_datetime")
            "fdt_unhold_datetime" => date("Y-m-d H:i:s")
        ];

        parent::update($data);
       
    }
}


