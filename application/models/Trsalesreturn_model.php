<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trsalesreturn_model extends MY_Model {
    public $tableName = "trsalesreturn";
    public $pkey = "fin_salesreturn_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_salesreturn_no',
            'label' => 'Sales Return No',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];
        $rules[] = [
            'field' => 'fin_customer_id',
            'label' => 'Customer',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];        

        return $rules;
    }

    public function getDataById($finSalesReturnId){
        $ssql = "SELECT a.* FROM " .$this->tableName. " a  
            WHERE a.fin_salesreturn_id = ? AND a.fst_active != 'D'";

        $qr = $this->db->query($ssql, [$finSalesReturnId]);
        $dataH = $qr->row();
        if ($dataH == null){
            return [
                "salesreturn"=>null,
                "salesreturn_details"=>[]
            ];            
        }

        $ssql = "SELECT a.*,b.fst_inv_no,b.fdt_inv_datetime,c.fst_item_code FROM trsalesreturnitems a 
            LEFT JOIN trinvoice b on a.fin_inv_id = b.fin_inv_id
            INNER JOIN msitems c on a.fin_item_id = c.fin_item_id 
            WHERE a.fin_salesreturn_id = ?";

		$qr = $this->db->query($ssql,[$finSalesReturnId]);
		$dataDetails = $qr->result();
		$data = [
            "salesreturn" => $dataH,
            "salesreturn_details" => $dataDetails
		];

		return $data;
    }

    public function getDataHeaderById($finSalesReturnId){
        $ssql = "SELECT * FROM " .$this->tableName. "
            WHERE fin_salesreturn_id = ?";
        $qr = $this->db->query($ssql, [$finSalesReturnId]);
        $dataH = $qr->row();
		return $dataH;
    }
    

    public function GenerateSalesReturnNo($trDate = null) {
        $trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
        $tahun = date("Y/m", strtotime ($trDate));
        $activeBranch = $this->aauth->get_active_branch();
        $branchCode = "";
        if($activeBranch){
            $branchCode = $activeBranch->fst_branch_code;
        }
        $prefix = getDbConfig("sales_return_prefix") . "/" . $branchCode ."/";
        $query = $this->db->query("SELECT MAX(fst_salesreturn_no) as max_id FROM trsalesreturn where fst_salesreturn_no like '".$prefix.$tahun."%'");
        $row = $query->row_array();

        $max_id = $row['max_id']; 
        
        $max_id1 =(int) substr($max_id,strlen($max_id)-5);
        
        $fst_tr_no = $max_id1 +1;
        
        $max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);
        
        return $max_tr_no;
    }
    
    public function getListSalesFaktur($isPaidFaktur,$fin_customer_id,$fstCurrCode,$search){        
        if ($isPaidFaktur) { //Faktur2 yang sudah pernah dilakukan pembayaran atau sudah pernah ada transaksi retur
            $ssql = "SELECT DISTINCT b.fin_inv_id,b.fst_inv_no,b.fdt_inv_datetime,b.fbl_is_vat_include,b.fdc_ppn_percent from trinvoiceitems a 
                INNER JOIN trinvoice b on a.fin_inv_id = b.fin_inv_id 
                WHERE a.fdb_qty > fdb_qty_return
                AND b.fdc_total_paid > 0 
                AND b.fin_relation_id = ? AND b.fst_curr_code = ? AND b.fst_inv_no like ? AND b.fst_active != 'D' ";

        }else{ //Faktur2 yang belumh pernah dilakukan pembayaran dan belum pernah ada transaksi retur
            $ssql = "SELECT DISTINCT b.fin_inv_id,b.fst_inv_no,fdt_inv_datetime,b.fbl_is_vat_include,b.fdc_ppn_percent FROM trinvoiceitems a
                INNER JOIN trinvoice b on a.fin_inv_id = b.fin_inv_id
                WHERE a.fdb_qty > fdb_qty_return
                AND b.fdc_total_paid = 0 
                AND b.fin_relation_id = ? AND b.fst_curr_code = ? AND b.fst_inv_no like ? AND b.fst_active != 'D' ";
        }

        $qr =$this->db->query($ssql,[(int) $fin_customer_id,$fstCurrCode,$search]);        
        $rs= $qr->result();
        return $rs;

        /*
        //Faktur2 yang pembayarannya belum lunas
        $ssql = "SELECT a.fin_inv_id,a.fst_inv_no FROM trinvoice a
            WHERE a.fdc_total > a.fdc_total_paid + a.fdc_total_return
            AND a.fin_relation_id = ? AND a.fst_active != 'D' ";
            
        $qr =$this->db->query($ssql,[(int) $fin_customer_id]);
        //echo $this->db->last_query();
        $rs= $qr->result();
        return $rs;
        */
    }

    public function getSalesInvoice($finInvId){
        $ssql = "select a.* from trinvoice a where a.fin_inv_id = ?";
        $qr = $this->db->query($ssql,[$finInvId]);
        $rw = $qr->row();
        if($rw == null){
            return [
                "invoice"=>null,
                "invoiceDetails"=>[]            
            ];
        }

        $ssql = "SELECT c.fin_item_id,c.fst_custom_item_name,e.fst_item_code,e.fst_item_name,c.fst_unit,c.fdc_price,c.fst_disc_item,c.fdc_disc_amount_per_item,b.fdb_ttl_qty_out,ifnull(d.fdb_ttl_qty_return,0) as fdb_ttl_qty_return  
            FROM trinvoice a 
            INNER JOIN (
                SELECT a.fin_inv_id,b.fin_salesorder_detail_id,sum(b.fdb_qty) as fdb_ttl_qty_out 
                FROM trsuratjalan a
                INNER JOIN trsuratjalandetails b on a.fin_sj_id = b.fin_sj_id                
                group by a.fin_inv_id,b.fin_salesorder_detail_id)  b on a.fin_inv_id = b.fin_inv_id            
            INNER JOIN trsalesorderdetails c on b.fin_salesorder_detail_id = c.fin_rec_id
            LEFT JOIN (
                SELECT b.fin_inv_id, a.fin_salesorder_detail_id,sum(a.fdb_qty) as fdb_ttl_qty_return  FROM trsalesreturnitems a 
                INNER JOIN trsalesreturn b on a.fin_salesreturn_id = b.fin_salesreturn_id
                GROUP BY b.fin_inv_id,a.fin_salesorder_detail_id
            ) d on a.fin_inv_id = d.fin_inv_id and c.fin_rec_id = d.fin_salesorder_detail_id            
            INNER JOIN msitems e on c.fin_item_id = e.fin_item_id
            WHERE a.fin_inv_id = ? 
            AND b.fdb_ttl_qty_out > ifnull(d.fdb_ttl_qty_return,0)";

        $qr = $this->db->query($ssql,[$finInvId]); 
               
        $rs = $qr->result();
        return [
            "invoice"=>$rw,
            "invoiceDetails"=>$rs            
        ];

    }


    public function getSummaryReturnByInvoice($finInvId){

        $ssql = "SELECT b.fin_salesorder_detail_id,b.fdb_ttl_qty_out,ifnull(c.fdb_ttl_qty_return,0) as fdb_ttl_qty_return  FROM trinvoice a
            INNER JOIN ( 
                select a.fin_inv_id,b.fin_salesorder_detail_id,sum(b.fdb_qty) as fdb_ttl_qty_out from trsuratjalan a 
                inner join trsuratjalandetails b on a.fin_sj_id = b.fin_sj_id
                group by a.fin_inv_id,b.fin_salesorder_detail_id
            ) b on a.fin_inv_id = b.fin_inv_id
            LEFT JOIN (
                select a.fin_inv_id,b.fin_salesorder_detail_id,sum(b.fdb_qty) as fdb_ttl_qty_return from trsalesreturn a 
                inner join trsalesreturnitems b on a.fin_salesreturn_id = b.fin_salesreturn_id
                group by a.fin_inv_id,b.fin_salesorder_detail_id
            ) c on a.fin_inv_id = c.fin_inv_id
            WHERE a.fin_inv_id = ?";        

        $qr = $this->db->query($ssql,[(int)$finInvId]);
        $rs = $qr->result();
        $result =[];
        foreach($rs as $rw){
            $result[$rw->fin_salesorder_detail_id] = $rw;
        }
        return $result;
    }

    public function posting($finSalesReturnId){
        $this->load->model("glledger_model");
        //$this->load->model("trinventory_model");

        $ssql ="select * from trsalesreturn where fin_salesreturn_id = ?";
        $qr = $this->db->query($ssql,[$finSalesReturnId]);
        $dataH = $qr->row();

        $ssql ="select * from trsalesreturnitems where fin_salesreturn_id = ?";
        $qr = $this->db->query($ssql,[$finSalesReturnId]);
        $dataDetails = $qr->result();


        if ($dataH->fbl_non_faktur == 0){
            //Return langsung mengurangi invoice            
            $ssql = "SELECT fin_inv_id,sum(fdc_total) as ttl_return  FROM trsalesreturnitems where fin_salesreturn_id = ? group by fin_inv_id";
            $qr = $this->db->query($ssql,[$finSalesReturnId]);
            $dataSumDetails = $qr->result();

            foreach($dataSumDetails as $dataSum){
                $ssql ="SELECT * from trinvoice where fin_inv_id = ? and fst_active != 'D'";
                $qr = $this->db->query($ssql,[$dataSum->fin_inv_id]);
                $dataInv = $qr->row();
                if($dataInv == null){
                    throw new CustomException(lang("invalid invoice ID"),3003,"FAILED",$dataSum);
                }
                $maxReturn = $dataInv->fdc_total - ($dataInv->fdc_total_paid + $dataInv->fdc_total_return);
                if (  $maxReturn < $dataSum->ttl_return ){
                    throw new CustomException(sprintf(lang("Max return invoice %s adalah %s"),$dataInv->fst_inv_no,formatNumber($maxReturn)),3003,"FAILED",null);
                }
                $ssql ="UPDATE trinvoice set fdc_total_return = fdc_total_return + ? where fin_inv_id = ?";
                $this->db->query($ssql,[$dataSum->ttl_return,$dataSum->fin_inv_id]);
                
                throwIfDBError();

            }
        }

        /**
         * SAAT RETUR, KALAU LANGSUNG KE PIUTANG DAGANG         
            Retur Penjualan		Rp.   100.000,-
            PPN			Rp.     9.000,-
                Discount			Rp.   10.000,-
                Piutang Dagang			Rp.   99.000,-


            SAAT RETUR, KALAU PAKAI AYAT SILANG
            --------------------------------------------
            Misalkan retur 10% dari invoice

            Retur Penjualan		Rp.   100.000,-
            PPN			Rp.     9.000,-
                Discount			Rp.   10.000,-
                Retur Belum Realisasi		Rp.   99.000,-
         */

        //JURNAL RETURN
        $accReturnPenjualan = getGLConfig("RETUR_PENJUALAN");
        $accPPN = getGLConfig("PPN_KELUARAN");
        $accDisc = getGLConfig("INV_DISC");
        $accPiutang = getGLConfig("AR_DAGANG_LOKAL");
        $accRetunBelumRealisasi = getGLConfig("RETUR_BELUM_REALISASI");

        $ttlReturnPenjualan = 0;
        $ttlPpn = 0;
        $ttlDisc = 0;
        $ttlPiutang = 0;        
        
        foreach($dataDetails as $dataD){
            // update qty return invoice items
            if ($dataD->fin_inv_detail_id != 0 ){
                $ssql = "UPDATE trinvoiceitems set fdb_qty_return = fdb_qty_return + ? where fin_rec_id = ?";
                $this->db->query($ssql,[$dataD->fdb_qty,$dataD->fin_inv_detail_id]);
                throwIfDBError();
            }
            
            $ttlReturnPenjualan += $dataD->fdc_total;
            $ttlPpn += $dataD->fdc_ppn_amount;
            $discAmount = $dataD->fdb_qty * $dataD->fdc_disc_amount_per_item;
            $ttlDisc += $discAmount;
            $ttlPiutang += $dataD->fdc_total + $dataD->fdc_ppn_amount - $discAmount;
        }

        

        $dataJurnal = [];
        $dataJurnal[] =[ //Retur Penjualan
            "fin_branch_id"=>$dataH->fin_branch_id,
            "fst_account_code"=>$accReturnPenjualan,
            "fdt_trx_datetime"=>$dataH->fdt_salesreturn_datetime,
            "fst_trx_sourcecode"=>"SRT",
            "fin_trx_id"=>$dataH->fin_salesreturn_id,
            "fst_trx_no"=>$dataH->fst_salesreturn_no,
            "fst_reference"=>$dataH->fst_memo,
            "fdc_debit"=> $ttlReturnPenjualan * $dataH->fdc_exchange_rate_idr,
            "fdc_origin_debit"=>$ttlReturnPenjualan,
            "fdc_credit"=>0,
            "fdc_origin_credit"=>0,
            "fst_orgi_curr_code"=>$dataH->fst_curr_code,
            "fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
            "fst_no_ref_bank"=>null,
            "fst_profit_cost_center_code"=>null,
            "fin_relation_id"=>null,
            "fst_active"=>"A"
        ];

        $dataJurnal[] =[ //PPN
            "fin_branch_id"=>$dataH->fin_branch_id,
            "fst_account_code"=>$accPPN,
            "fdt_trx_datetime"=>$dataH->fdt_salesreturn_datetime,
            "fst_trx_sourcecode"=>"SRT",
            "fin_trx_id"=>$dataH->fin_salesreturn_id,
            "fst_trx_no"=>$dataH->fst_salesreturn_no,
            "fst_reference"=>$dataH->fst_memo,
            "fdc_debit"=> $ttlPpn * $dataH->fdc_exchange_rate_idr,
            "fdc_origin_debit"=>$ttlPpn,
            "fdc_credit"=>0,
            "fdc_origin_credit"=>0,
            "fst_orgi_curr_code"=>$dataH->fst_curr_code,
            "fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
            "fst_no_ref_bank"=>null,
            "fst_profit_cost_center_code"=>null,
            "fin_relation_id"=>null,
            "fst_active"=>"A"
        ];

        $dataJurnal[] =[ //Disc
            "fin_branch_id"=>$dataH->fin_branch_id,
            "fst_account_code"=>$accDisc,
            "fdt_trx_datetime"=>$dataH->fdt_salesreturn_datetime,
            "fst_trx_sourcecode"=>"SRT",
            "fin_trx_id"=>$dataH->fin_salesreturn_id,
            "fst_trx_no"=>$dataH->fst_salesreturn_no,
            "fst_reference"=>$dataH->fst_memo,
            "fdc_debit"=> 0,
            "fdc_origin_debit"=>0,
            "fdc_credit"=>$ttlDisc * $dataH->fdc_exchange_rate_idr,
            "fdc_origin_credit"=>$ttlDisc,
            "fst_orgi_curr_code"=>$dataH->fst_curr_code,
            "fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
            "fst_no_ref_bank"=>null,
            "fst_profit_cost_center_code"=>null,
            "fin_relation_id"=>null,
            "fst_active"=>"A"
        ];

        $dataTmp =[ //Piutang / Retur belum realisasi
            "fin_branch_id"=>$dataH->fin_branch_id,
            "fst_account_code"=>$accPiutang,
            "fdt_trx_datetime"=>$dataH->fdt_salesreturn_datetime,
            "fst_trx_sourcecode"=>"SRT",
            "fin_trx_id"=>$dataH->fin_salesreturn_id,
            "fst_trx_no"=>$dataH->fst_salesreturn_no,
            "fst_reference"=>$dataH->fst_memo,
            "fdc_debit"=> 0,
            "fdc_origin_debit"=>0,
            "fdc_credit"=>$ttlPiutang * $dataH->fdc_exchange_rate_idr,
            "fdc_origin_credit"=>$ttlPiutang,
            "fst_orgi_curr_code"=>$dataH->fst_curr_code,
            "fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
            "fst_no_ref_bank"=>null,
            "fst_profit_cost_center_code"=>null,
            "fin_relation_id"=>$dataH->fin_customer_id,
            "fst_active"=>"A"
        ];


        if ($dataH->fbl_non_faktur != 0){
            //Akui piutang sebagai return yg belum direalisasi
            $dataTmp["fst_account_code"] = $accRetunBelumRealisasi;
            $dataTmp["fin_relation_id"] = null;
        }
        
        $dataJurnal[] = $dataTmp;

        $result = $this->glledger_model->createJurnal($dataJurnal);        
    }

    public function unposting($finSalesReturnId){
        $this->load->model("glledger_model");
        
        
        $ssql ="select * from trsalesreturn where fin_salesreturn_id = ?";
        $qr = $this->db->query($ssql,[$finSalesReturnId]);
        $dataH = $qr->row();

        if($dataH == null){
            throw new CustomException(lang("invalid sales return ID"),3003,"FAILED",$finSalesReturnId);
        }

        $this->glledger_model->cancelJurnal("SRT",$finSalesReturnId);

        if ($dataH->fbl_non_faktur == 0){
            //Kembalikan nilai retun di invoice
            $ssql = "SELECT fin_inv_id,sum(fdc_total) as ttl_return  FROM trsalesreturnitems where fin_salesreturn_id = ? group by fin_inv_id";
            $qr = $this->db->query($ssql,[$finSalesReturnId]);            
            $dataSumDetails = $qr->result();
            
            foreach($dataSumDetails as $dataSum){
                $ssql ="SELECT * from trinvoice where fin_inv_id = ? and fst_active != 'D'";
                $qr = $this->db->query($ssql,[$dataSum->fin_inv_id]);
                $dataInv = $qr->row();
                if($dataInv == null){
                    throw new CustomException(lang("invalid invoice ID"),3003,"FAILED",$dataSum);
                }
                
                $ssql = "UPDATE trinvoice set fdc_total_return = fdc_total_return - ? where fin_inv_id = ?";
                $this->db->query($ssql,[$dataSum->ttl_return, $dataSum->fin_inv_id]);
                throwIfDBError();
            }
        }

        //Kembalikan nilai qty return;
        $ssql =  "SELECT * FROM trsalesreturnitems WHERE fin_salesreturn_id = ?";
        $qr = $this->db->query($ssql,[$finSalesReturnId]);
        $dataDetails  = $qr->result();
        foreach($dataDetails as $dataD){
            if ($dataD->fin_inv_detail_id != 0){
                $ssql = "UPDATE trinvoiceitems set fdb_qty_return = fdb_qty_return - ? WHERE fin_rec_id = ?";
                $this->db->query($ssql,[$dataD->fdb_qty,$dataD->fin_inv_detail_id]);
                throwIfDBError();
            }
        }

    }

    public function getItemListByInv($finInvId,$search="%"){
        if ($finInvId != ""){
            $ssql ="SELECT a.fin_rec_id as fin_inv_detail_id,a.fin_item_id,b.fst_item_code,b.fst_item_name,a.fst_custom_item_name,a.fst_unit,(a.fdb_qty - a.fdb_qty_return) as fdb_qty_max_return,a.fdc_price,a.fst_disc_item,fdc_disc_amount_per_item 
                FROM trinvoiceitems  a    
                INNER JOIN msitems b on a.fin_item_id =b.fin_item_id 
                WHERE a.fin_inv_id = ? AND a.fdb_qty > a.fdb_qty_return
                AND a.fst_custom_item_name like ?";
            
            $qr = $this->db->query($ssql,[$finInvId,$search]);
            $rs = $qr->result();            
            return $rs;
        }else{
            $ssql = "SELECT a.fin_item_id,a.fst_item_code,a.fst_item_name,a.fst_item_name as fst_custom_item_name from msitems a 
            where (a.fst_item_code like ? or a.fst_item_name like ?) 
            and a.fst_active ='A'";

            $qr = $this->db->query($ssql,[$search,$search]);
            $rs = $qr->result();            
            return $rs;
        }
    }

    public function isEditable($finPurchaseReturnId){
       
        /**
         * FALSE CONDITION
         * 1. 
         */
        $resp =["status"=>"SUCCESS","message"=>""];
        return $resp;
    }

    public function deleteDetail($finSalesReturnId){
        $ssql ="DELETE FROM trsalesreturnitems WHERE fin_salesreturn_id = ?";
        $this->db->query($ssql,[$finSalesReturnId]);
        throwIfDBError();
    }

    public function delete($finSalesReturnId,$softDelete = true,$data=null){
        if ($softDelete){
            $ssql ="update trsalesreturnitems set fst_active ='D' where fin_salesreturn_id = ?";
            $this->db->query($ssql,[$finSalesReturnId]);
        }else{
            $ssql ="delete from trsalesreturnitems where fin_salesreturn_id = ?";
            $this->db->query($ssql,[$finSalesReturnId]);            
        }
        parent::delete($finSalesReturnId,$softDelete,$data);

        return ["status" => "SUCCESS","message"=>""];
    }
}


