<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trpurchasereturn_model extends MY_Model {
    public $tableName = "trpurchasereturn";
    public $pkey = "fin_purchasereturn_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_purchasereturn_no',
            'label' => 'Purchase Return No',
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

    public function GeneratePurchaseReturnNo($trDate = null) {
        $trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
        $tahun = date("Y/m", strtotime ($trDate));
        $activeBranch = $this->aauth->get_active_branch();
        $branchCode = "";
        if($activeBranch){
            $branchCode = $activeBranch->fst_branch_code;
        }
        $prefix = getDbConfig("purchase_return_prefix") . "/" . $branchCode ."/";
        $query = $this->db->query("SELECT MAX(fst_purchasereturn_no) as max_id FROM trpurchasereturn where fst_purchasereturn_no like '".$prefix.$tahun."%'");
        $row = $query->row_array();

        $max_id = $row['max_id']; 
        
        $max_id1 =(int) substr($max_id,strlen($max_id)-5);
        
        $fst_tr_no = $max_id1 +1;
        
        $max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);
        
        return $max_tr_no;
    }

    public function getListPurchaseFaktur($fin_supplier_id,$isImport){
        //Faktur2 yang pembayarannya belum lunas
        $ssql ="select a.fin_lpbpurchase_id,a.fst_lpbpurchase_no from trlpbpurchase a
            inner join trpo b on a.fin_po_id = b.fin_po_id 
            where a.fdc_total > a.fdc_total_paid + a.fdc_total_return
            AND b.fbl_is_import = ? 
            AND a.fin_supplier_id = ? and a.fst_active != 'D' ";
            
        $qr =$this->db->query($ssql,[(boolean) $isImport,(int) $fin_supplier_id]);
        //echo $this->db->last_query();
        $rs= $qr->result();
        return $rs;
    }

    public function getLPBPurchase($finLPBPurchaseId){
        $ssql = "select a.*,b.fin_warehouse_id from trlpbpurchase a
            inner join trpo b on a.fin_po_id = b.fin_po_id
            where fin_lpbpurchase_id = ?";

        $qr = $this->db->query($ssql,[$finLPBPurchaseId]);
        $rw = $qr->row();
        if($rw == null){
            return [
                "lPBPurchase"=>null,
                "lPBPurchaseDetails"=>[]            
            ];
        }

        

        $ssql ="SELECT b.fin_po_detail_id,c.fin_item_id,d.fst_item_code,c.fst_custom_item_name,c.fst_unit,c.fdc_price,c.fst_disc_item, 
            SUM(b.fdb_qty) AS fdb_qty_lpb, 
            IFNULL(SUM(e.fdb_qty_return),0) AS fdb_qty_return, 
            SUM(b.fdb_qty) - IFNULL(SUM(e.fdb_qty_return),0) AS fdb_qty_max_return
            FROM trlpbpurchaseitems a 
            INNER JOIN trlpbgudangitems b ON a.fin_lpbgudang_id = b.fin_lpbgudang_id 
            INNER JOIN trpodetails c ON b.fin_po_detail_id = c.fin_po_detail_id 
            INNER JOIN msitems d ON c.fin_item_id = d.fin_item_id 
            LEFT JOIN (
                SELECT a.fin_po_detail_id,fdb_qty AS fdb_qty_return FROM trpurchasereturnitems a 
                INNER JOIN trpurchasereturn b ON a.fin_purchasereturn_id = b.fin_purchasereturn_id
                WHERE b.fin_lpbpurchase_id = ? and a.fst_active != 'D' 
            ) e ON b.fin_po_detail_id = e.fin_po_detail_id 
            WHERE a.fin_lpbpurchase_id = ? 
            GROUP BY b.fin_po_detail_id HAVING SUM(b.fdb_qty) > IFNULL(SUM(e.fdb_qty_return),0)";       

        $qr = $this->db->query($ssql,[$finLPBPurchaseId,$finLPBPurchaseId]);
        $rs = $qr->result();

        return [
            "lPBPurchase"=>$rw,
            "lPBPurchaseDetails"=>$rs
        ];

    }

    public function getSummaryReturnByLPBPurchase($finLPBPurchaseId){
        $ssql ="select a.fin_po_detail_id,sum(a.fdb_qty) as fdb_qty_return from trpurchasereturnitems a 
            inner join trpurchasereturn b on a.fin_purchasereturn_id = b.fin_purchasereturn_id 
            where b.fin_lpbpurchase_id = ? and b.fst_active != 'D' 
            group by a.fin_po_detail_id";

        $qr = $this->db->query($ssql,[(int)$finLPBPurchaseId]);
        $rs = $qr->result();
        $result =[];
        foreach($rs as $rw){
            $result[$rw->fin_po_detail_id] = $rw;
        }
        return $result;
    }

    public function getSummaryQtyLPBByLPBPurchase($finLPBPurchaseId){
        $ssql ="select c.fin_po_detail_id,sum(c.fdb_qty) as fdb_qty_lpb from trlpbpurchaseitems a 
            inner join trlpbpurchase b on a.fin_lpbpurchase_id = b.fin_lpbpurchase_id 
            inner join trlpbgudangitems c on a.fin_lpbgudang_id = c.fin_lpbgudang_id
            where b.fin_lpbpurchase_id = ? group by c.fin_po_detail_id";

        $qr = $this->db->query($ssql,[(int)$finLPBPurchaseId]);
        $rs = $qr->result();
        $result =[];
        foreach($rs as $rw){
            $result[$rw->fin_po_detail_id] = $rw;
        }
        return $result;
    }

    public function posting($finPurchaseReturnId){
        $this->load->model("glledger_model");
        $ssql ="select * from trpurchasereturn where fin_purchasereturn_id = ?";
        $qr = $this->db->query($ssql,[$finPurchaseReturnId]);
        $dataH = $qr->row_array();


        if($dataH["fin_lpbpurchase_id"] != 0 ){ //Return dengan Faktur
            
            //Update total Return di LPB Purchase        
            $ssql = "update trlpbpurchase set fdc_total_return = fdc_total_return + $dataH[fdc_total] where fin_lpbpurchase_id = $dataH[fin_lpbpurchase_id]";
            $this->db->query($ssql,[]);
                    
            //Update qty return di podetails
            $ssql ="select * from trpurchasereturnitems where fin_purchasereturn_id = ?";
            $qr = $this->db->query($ssql,[$finPurchaseReturnId]);
            $dataDetails = $qr->result_array();
            foreach($dataDetails as $dataD){
                $ssql ="update trpodetails set fdb_qty_return = fdb_qty_return + $dataD[fdb_qty] where fin_po_detail_id = $dataD[fin_po_detail_id]";
                $this->db->query($ssql,[]);
            }
        }else{ //Return non Faktur

        }

        //posting jurnal
        /**
         * Hutang
         * Disc
         *      Return
         *      PPN
         */
        $accHutang = $dataH["fbl_is_import"] == 1 ? getGLConfig("AP_DAGANG_IMPORT") : getGLConfig("AP_DAGANG_LOKAL");
        $valHutang = 0;
        $accDisc = getGLConfig("PURCHASE_DISC");
        $valDisc = 0;
        $accReturn = $dataH["fbl_is_import"] == 1 ? getGLConfig("RETURN_IMPORT") : getGLConfig("RETURN_LOKAL");
        $valReturn = 0;
        $accPpn = getGLConfig("PPN_MASUKAN");
        $valPpn = 0;
        $dataJurnal = [];

        $valDisc = $dataH["fdc_disc_amount"];
        $valPpn = $dataH["fdc_ppn_amount"];
        $valReturn = $dataH["fdc_subttl"];
        $valHutang = $dataH["fdc_total"];
        

        $dataJurnal[] =[ //Hutang
            "fin_branch_id"=>$dataH["fin_branch_id"],
            "fst_account_code"=>$accHutang,
            "fdt_trx_datetime"=>$dataH["fdt_purchasereturn_datetime"],
            "fst_trx_sourcecode"=>"PRT",
            "fin_trx_id"=>$dataH["fin_purchasereturn_id"],
            "fst_trx_no"=>$dataH["fst_purchasereturn_no"],
            "fst_reference"=>null,
            "fdc_debit"=> $valHutang * $dataH["fdc_exchange_rate_idr"],
            "fdc_origin_debit"=>$valHutang,
            "fdc_credit"=>0,
            "fdc_origin_credit"=>0,
            "fst_orgi_curr_code"=>$dataH["fst_curr_code"],
            "fdc_orgi_rate"=>$dataH["fdc_exchange_rate_idr"],
            "fst_no_ref_bank"=>null,
            "fst_profit_cost_center_code"=>null,
            "fin_relation_id"=>$dataH["fin_supplier_id"],
            "fst_active"=>"A"
        ];
        $dataJurnal[] =[ //Disc
            "fin_branch_id"=>$dataH["fin_branch_id"],
            "fst_account_code"=>$accDisc,
            "fdt_trx_datetime"=>$dataH["fdt_purchasereturn_datetime"],
            "fst_trx_sourcecode"=>"PRT",
            "fin_trx_id"=>$dataH["fin_purchasereturn_id"],
            "fst_trx_no"=>$dataH["fst_purchasereturn_no"],
            "fst_reference"=>null,
            "fdc_debit"=> $valDisc * $dataH["fdc_exchange_rate_idr"],
            "fdc_origin_debit"=>$valDisc,
            "fdc_credit"=>0,
            "fdc_origin_credit"=>0,
            "fst_orgi_curr_code"=>$dataH["fst_curr_code"],
            "fdc_orgi_rate"=>$dataH["fdc_exchange_rate_idr"],
            "fst_no_ref_bank"=>null,
            "fst_profit_cost_center_code"=>null,
            "fin_relation_id"=>null,
            "fst_active"=>"A"
        ];
        $dataJurnal[] =[ //Return
            "fin_branch_id"=>$dataH["fin_branch_id"],
            "fst_account_code"=>$accReturn,
            "fdt_trx_datetime"=>$dataH["fdt_purchasereturn_datetime"],
            "fst_trx_sourcecode"=>"PRT",
            "fin_trx_id"=>$dataH["fin_purchasereturn_id"],
            "fst_trx_no"=>$dataH["fst_purchasereturn_no"],
            "fst_reference"=>null,
            "fdc_debit"=> 0,
            "fdc_origin_debit"=>0,
            "fdc_credit"=>$valReturn * $dataH["fdc_exchange_rate_idr"],
            "fdc_origin_credit"=>$valReturn,
            "fst_orgi_curr_code"=>$dataH["fst_curr_code"],
            "fdc_orgi_rate"=>$dataH["fdc_exchange_rate_idr"],
            "fst_no_ref_bank"=>null,
            "fst_profit_cost_center_code"=>null,
            "fin_relation_id"=>null,
            "fst_active"=>"A"
        ];
        $dataJurnal[] =[ //PPN
            "fin_branch_id"=>$dataH["fin_branch_id"],
            "fst_account_code"=>$accPpn,
            "fdt_trx_datetime"=>$dataH["fdt_purchasereturn_datetime"],
            "fst_trx_sourcecode"=>"PRT",
            "fin_trx_id"=>$dataH["fin_purchasereturn_id"],
            "fst_trx_no"=>$dataH["fst_purchasereturn_no"],
            "fst_reference"=>null,
            "fdc_debit"=> 0,
            "fdc_origin_debit"=>0,
            "fdc_credit"=>$valPpn * $dataH["fdc_exchange_rate_idr"],
            "fdc_origin_credit"=>$valPpn,
            "fst_orgi_curr_code"=>$dataH["fst_curr_code"],
            "fdc_orgi_rate"=>$dataH["fdc_exchange_rate_idr"],
            "fst_no_ref_bank"=>null,
            "fst_profit_cost_center_code"=>null,
            "fin_relation_id"=>null,
            "fst_active"=>"A"
        ];

        $result = $this->glledger_model->createJurnal($dataJurnal);
        if( $result["status"] != "SUCCESS"){
            throw new Exception($result["message"], EXCEPTION_JURNAL);
        }
        return $result;
    }

    public function unposting($finPurchaseReturnId){
        $this->load->model("glledger_model");
        $ssql ="select * from trpurchasereturn where fin_purchasereturn_id = ?";
        $qr = $this->db->query($ssql,[$finPurchaseReturnId]);
        $dataH = $qr->row_array();


        if($dataH["fin_lpbpurchase_id"] != 0 ){ //Return dengan Faktur
            
            //Update total Return di LPB Purchase        
            $ssql = "update trlpbpurchase set fdc_total_return = fdc_total_return - $dataH[fdc_total] where fin_lpbpurchase_id = $dataH[fin_lpbpurchase_id]";
            $this->db->query($ssql,[]);
                    
            //Update qty return di podetails
            $ssql ="select * from trpurchasereturnitems where fin_purchasereturn_id = ?";
            $qr = $this->db->query($ssql,[$finPurchaseReturnId]);
            $dataDetails = $qr->result_array();
            foreach($dataDetails as $dataD){
                $ssql ="update trpodetails set fdb_qty_return = fdb_qty_return - $dataD[fdb_qty] where fin_po_detail_id = $dataD[fin_po_detail_id]";
                $this->db->query($ssql,[]);
            }
        }

        $this->glledger_model->cancelJurnal("PRT",$finPurchaseReturnId);        
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

    public function getDataById($finPurchaseReturnId){
        $ssql = "SELECT a.*,b.fst_lpbpurchase_no FROM " .$this->tableName. " a  
            LEFT JOIN trlpbpurchase b ON a.fin_lpbpurchase_id = b.fin_lpbpurchase_id 
            WHERE a.fin_purchasereturn_id = ? AND a.fst_active != 'D'";

        $qr = $this->db->query($ssql, [$finPurchaseReturnId]);
        $dataH = $qr->row();

        $ssql = "select a.*,b.fdb_total_lpb,c.fdb_qty_return,d.fst_item_code from trpurchasereturnitems a 
            left join (
                select c.fin_po_detail_id,sum(fdb_qty) as fdb_total_lpb from trpurchasereturn a 
                left join trlpbpurchaseitems b on a.fin_lpbpurchase_id = b.fin_lpbpurchase_id
                inner join trlpbgudangitems c on b.fin_lpbgudang_id = c.fin_lpbgudang_id            
                where a.fin_purchasereturn_id = ? 
                group by c.fin_po_detail_id
            ) b on a.fin_po_detail_id = b.fin_po_detail_id
            left join trpodetails c on a.fin_po_detail_id = c.fin_po_detail_id 
            inner join msitems  d on a.fin_item_id = d.fin_item_id             
            where a.fin_purchasereturn_id = ?";

		$qr = $this->db->query($ssql,[$finPurchaseReturnId,$finPurchaseReturnId]);
		$dataDetails = $qr->result();

		$data = [
            "purchasereturn" => $dataH,
            "purchasereturn_details" => $dataDetails
		];

		return $data;
    }

    public function getDataHeaderById($finPurchaseReturnId){
        $ssql = "SELECT * FROM " .$this->tableName. "
            WHERE fin_purchasereturn_id = ?";
        $qr = $this->db->query($ssql, [$finPurchaseReturnId]);
        $dataH = $qr->row();
		return $dataH;
    }
    
    public function isEditable($finPurchaseReturnId){
       
        /**
         * FALSE CONDITION
         * 1. 
         */
        $resp =["status"=>"SUCCESS","message"=>""];
        return $resp;
    }
    public function update($data){
        //Delete Field yang tidak boleh berubah
        parent::update($data);        
    }

    public function delete($finPurchaseReturnId,$softDelete = true,$data=null){
        if ($softDelete){
            $ssql ="update trpurchasereturnitems set fst_active ='D' where fin_purchasereturn_id = ?";
            $this->db->query($ssql,[$finPurchaseReturnId]);
        }else{
            $ssql ="delete from trpurchasereturnitems where fin_purchasereturn_id = ?";
            $this->db->query($ssql,[$finPurchaseReturnId]);            
        }
        parent::delete($finPurchaseReturnId,$softDelete,$data);

        return ["status" => "SUCCESS","message"=>""];
   }
}


