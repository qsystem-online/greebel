<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class PurchaseOrder{
    private $rw;
    private $CI;
    public function __construct($CI,$fin_salesorder_id){
        $this->CI = $CI;
        $ssql = "Select * from trpo where fin_po_id = ?";
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
        $rules[] = [
            'field' => 'fin_supplier_id',
            'label' => 'Supplier',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];
        $rules[] = [
            'field' => 'fin_warehouse_id',
            'label' => 'Gudang',
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

    public function getDataById($fin_po_id){
        $ssql = "select * from " .$this->tableName. " WHERE fin_po_id = ? and fst_active != 'D'";

        $qr = $this->db->query($ssql, [$fin_po_id]);
        $rwPO = $qr->row();

        $ssql = "select a.*,b.fst_item_code as ItemCode from trpodetails a 
            inner join msitems b on a.fin_item_id = b.fin_item_id 
            where a.fin_po_id = ?";
		$qr = $this->db->query($ssql,[$fin_po_id]);
		$rsPODetails = $qr->result();

		$data = [
            "po" => $rwPO,
            "po_details" => $rsPODetails
		];

		return $data;
    }

    public function getDataHeaderById($finPOId){
        $ssql = "select * from " .$this->tableName. " WHERE fin_po_id = ? and fst_active != 'D'";
        $qr = $this->db->query($ssql, [$finPOId]);
        return $qr->row();

    }


    public function GeneratePONo($trDate = null) {
        $trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
        $tahun = date("Y/m", strtotime ($trDate));
        $activeBranch = $this->aauth->get_active_branch();
        $branchCode = "";
        if($activeBranch){
            $branchCode = $activeBranch->fst_branch_code;
        }
        $prefix = getDbConfig("purchaseorder_prefix") . "/" . $branchCode ."/";
        $query = $this->db->query("SELECT MAX(fst_po_no) as max_id FROM trpo where fst_po_no like '".$prefix.$tahun."%'");
        $row = $query->row_array();

        $max_id = $row['max_id']; 
        
        $max_id1 =(int) substr($max_id,strlen($max_id)-5);
        
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
    
    
    public function getLastBuyPrice($fin_item_id,$fst_unit){
        $ssql = "select * from trpodetails where fin_item_id = ? and fst_unit = ? order by fin_po_detail_id desc limit 1";
        $qr = $this->db->query($ssql,[$fin_item_id,$fst_unit]);
        $rw = $qr->row();
        if(!$rw){
            return 0;
        }else{
            return $rw->fdc_price;
        }
    }


    public function posting($finPOId){
        //Bila terdapat DP jurnal DP tersebut
        $ssql ="select * from trpo where fin_po_id = ?";
        $qr = $this->db->query($ssql,[$finPOId]);
        $dataH = $qr->row_array();

        if ($dataH["fdc_downpayment"] > 0 && $dataH["fst_active"] == "A"){
            $this->load->model("glledger_model");

            $dataJurnal = [];
            $dp =0;
            $dpPpn =0;

            if ($dataH["fbl_dp_inc_ppn"] == 1){
                $dp = $dataH["fdc_downpayment"] / ( (100 + $dataH["fdc_ppn_percent"]) / 100) ;
                $dpPpn =$dp * ($dataH["fdc_ppn_percent"] / 100);
            }else{
                $dp = $dataH["fdc_downpayment"] ;
                $dpPpn =0;
            }

            $fstAccountCode = getGLConfig("DP_OUT_LOKAL");
            if ($dataH["fbl_is_import"] == 1){
                $fstAccountCode = getGLConfig("DP_OUT_IMPORT");
            }
            

            $dataJurnal[] =[
                "fin_branch_id"=>$dataH["fin_branch_id"],
                "fst_account_code"=>$fstAccountCode,
                "fdt_trx_datetime"=>date("Y-m-d H:i:s"),
                "fst_trx_sourcecode"=>"PO",
                "fin_trx_id"=>$dataH["fin_po_id"],
                "fst_reference"=>null,
                "fdc_debit"=> $dp * $dataH["fdc_exchange_rate_idr"],
                "fdc_origin_debit"=>$dp,
                "fdc_credit"=>0,
                "fdc_origin_credit"=>0,
                "fst_orgi_curr_code"=>$dataH["fst_curr_code"],
                "fdc_orgi_rate"=>$dataH["fdc_exchange_rate_idr"],
                "fst_no_ref_bank"=>null,
                "fin_pcc_id"=>null,
                "fin_pc_divisi_id"=>null,
                "fin_pc_customer_id"=>null,
                "fin_pc_project_id"=>null,
                "fin_relation_id"=>null,
                "fst_active"=>"A"
            ];

            if ($dpPpn > 0){
                $dataJurnal[] =[
                    "fin_branch_id"=>$dataH["fin_branch_id"],
                    "fst_account_code"=>getGLConfig("PPN_MASUKAN"),
                    "fdt_trx_datetime"=>date("Y-m-d H:i:s"),
                    "fst_trx_sourcecode"=>"PO",
                    "fin_trx_id"=>$dataH["fin_po_id"],
                    "fst_reference"=>null,
                    "fdc_debit"=> $dpPpn * $dataH["fdc_exchange_rate_idr"],
                    "fdc_origin_debit"=>$dpPpn,
                    "fdc_credit"=>0,
                    "fdc_origin_credit"=>0,
                    "fst_orgi_curr_code"=>$dataH["fst_curr_code"],
                    "fdc_orgi_rate"=>$dataH["fdc_exchange_rate_idr"],
                    "fst_no_ref_bank"=>null,
                    "fin_pcc_id"=>null,
                    "fin_pc_divisi_id"=>null,
                    "fin_pc_customer_id"=>null,
                    "fin_pc_project_id"=>null,
                    "fin_relation_id"=>null,
                    "fst_active"=>"A"
                ];
            }

            $fstAccountCode = getGLConfig("AP_DAGANG_LOKAL");
            if ($dataH["fbl_is_import"] == 1){
                $fstAccountCode = getGLConfig("AP_DAGANG_IMPORT");
            }

            $dataJurnal[] = [
                "fin_branch_id"=>$dataH["fin_branch_id"],
                "fst_account_code"=>$fstAccountCode,
                "fdt_trx_datetime"=>date("Y-m-d H:i:s"),
                "fst_trx_sourcecode"=>"PO",
                "fin_trx_id"=>$dataH["fin_po_id"],
                "fst_reference"=>null,
                "fdc_debit"=>0,
                "fdc_origin_debit"=>0,
                "fdc_credit"=>$dataH["fdc_downpayment"] * $dataH["fdc_exchange_rate_idr"],
                "fdc_origin_credit"=>$dataH["fdc_downpayment"],
                "fst_orgi_curr_code"=>$dataH["fst_curr_code"],
                "fdc_orgi_rate"=>$dataH["fdc_exchange_rate_idr"],
                "fst_no_ref_bank"=>null,
                "fin_pcc_id"=>null,
                "fin_pc_divisi_id"=>null,
                "fin_pc_customer_id"=>null,
                "fin_pc_project_id"=>null,
                "fin_relation_id"=>$dataH["fin_supplier_id"],
                "fst_active"=>"A"
            ];
            

            $result = $this->glledger_model->createJurnal($dataJurnal);

            if( $result["status"] != "SUCCESS"){
                throw new Exception($result["message"], EXCEPTION_JURNAL);
            }
            return $result;
        }
        return [
            "status"=>"SUCCESS",
            "message"=>""
        ];

    }

    public function update($data){
        //Delete Field yang tidak boleh berubah
        unset($data["fin_relation_id"]);
        unset($data["fst_salesorder_no"]);
        parent::update($data);        
    }

    public function approved($finPOId,$approved = true){
        
        if($approved){
            $data = [
                "fin_po_id"=>$finPOId,
                "fst_active"=>"A"
            ];        
            parent::update($data);            
            $result = $this->posting($finPOId);            
        }else{
            $data = [
                "fin_po_id"=>$finPOId,
                "fst_active"=>"R"
            ];        
            parent::update($data);            
        }
        

        return [
            "status"=>"SUCCESS",
            "message"=>""
        ] ;      
    }

    public function cancelApproval($finPOId){
        $ssql = "select * from trpo where fin_po_id = ? and fst_active = 'A' ";
        $qr = $this->db->query($ssql,[$finPOId]);
        $rw = $qr->row();
        
        if ($rw != null){

            //Cek DP
            if ($rw->fdc_downpayment_paid > 0 ) {                
                $resp =["status"=>"FAILED","message"=>lang("Status approval PO tidak dapat dirubah karena sudah ada pembayaran DP !")];
                return $resp;    
            }
            
            //Cek bila sudah ada penerimaan barang
            $ssql = "select * from trpodetails where fin_po_id = ? and fdb_qty_lpb > 0";
            $qr = $this->db->query($ssql,[$finPOId]);
            $rw = $qr->row();
            if ($rw != null){
                $resp =["status"=>"FAILED","message"=>lang("Status approval PO tidak dapat dirubah karena sudah terjadi penerimaan barang !")];
                return $resp;    
            }

            $this->load->model("glledger_model");
            $result = $this->glledger_model->cancelJurnal("PO",$finPOId);
            if ($result["status"] != "SUCCESS"){
                return $result;    
            }
           
            $ssql = "UPDATE trpo SET fst_active ='S' where fin_po_id = ?";
            $this->db->query($ssql,[$finPOId]);
            return $result;
        }
        return ["status"=>"SUCCESS",""];
    }

    public function delete($finPOId,$softDelete=true,$data=null){
        //cek jika sudah ada penerimaan barang
        $ssql  = "select * from trpodetails where fin_po_id = ? and fdb_qty_lpb > 0";
        $qr = $this->db->query($ssql,[$finPOId]);
        if ($qr->row()){
            return [
                "status"=>false,
                "message"=>lang("PO tidak dapat dihapus, sudah ada penerimaan barang !"),
            ];
        }
        parent::delete($finPOId,$softDelete);
        if(!$softDelete){
            $this->db->delete("trpodetails",array("fin_po_id"=>$finPOId));
        }
        

        return [
            "status"=>true,
            "message"=>"",
        ];
    }


    public function show_transaction($finPOId){
        redirect(site_url()."tr/purchase_order/view/$finPOId", 'refresh');
    }

    public function getUnpaidDPList($finSupplierId = "",$fstCurrCode=""){
        if ($finSupplierId == "" ){
            $ssql ="select fin_po_id,fst_po_no,fdc_downpayment,fdc_downpayment_paid,fdc_ppn_percent from trpo where fdc_downpayment > fdc_downpayment_paid and fst_active ='A'";
            $qr = $this->db->query($ssql,[]);
        }else{
            $ssql ="select fin_po_id,fst_po_no,fdc_downpayment,fdc_downpayment_paid,fdc_ppn_percent from trpo where   fdc_downpayment  > fdc_downpayment_paid and fst_active ='A' and fin_supplier_id = ? and fst_curr_code = ?";
            $qr = $this->db->query($ssql,[$finSupplierId,$fstCurrCode]);
        }
        
        return $qr->result();
    }

    public function isEditable($finPOId){
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
            fin_branch_id = ? and fst_controller ='PO' and fin_transaction_id = ? and fst_verification_status in ?";

        $qr = $this->db->query($ssql,[
            $this->aauth->get_active_branch_id(), 
            $finPOId,
            ['VF','RJ','VD'] 
        ]);
        if($qr->row() != null){
            $resp =["status"=>"FAILED","message"=>lang("PO tidak dapat dirubah karena sudah terjadi proses approval !") ];
            return $resp;
        }
        

        //Cek DP
        $ssql = "select * from trpo where fin_po_id = ? and fdc_downpayment_paid > 0";
        $qr = $this->db->query($ssql,[$finPOId]);
        $rw = $qr->row();
        if ($rw != null){
            $resp =["status"=>"FAILED","message"=>lang("PO tidak dapat dirubah karena sudah ada pembayaran DP !")];
            return $resp;    
        }

        //Cek bila sudah ada penerimaan barang
        $ssql = "select * from trpodetails where fin_po_id = ? and fdb_qty_lpb > 0";
        $qr = $this->db->query($ssql,[$finPOId]);
        $rw = $qr->row();
        if ($rw != null){
            $resp =["status"=>"FAILED","message"=>lang("PO tidak dapat dirubah karena sudah ada penerimaan barang di gudang !")];
            return $resp;    
        }


        $resp =["status"=>"SUCCESS","message"=>""];
        return $resp;
    }
}