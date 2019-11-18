<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trlpbpurchaseitems_model extends MY_Model {
    public $tableName = "trlpbpurchaseitems";
    public $pkey = "fin_rec_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_lpbgudang_no',
            'label' => 'No Penerimaan',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];


        return $rules;
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
        $ssql = "select distinct a.fin_po_id,b.fst_po_no from trlpbgudang a 
            INNER JOIN trpo b on a.fin_po_id = b.fin_po_id 
            WHERE a.fin_lpbpurchase_id IS NULL";
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
            WHERE fin_po_id = ? AND fin_lpbpurchase_id IS NULL ";
        $qr = $this->db->query($ssql,[$finPOId]);
        $poDetails=$qr->result();

        $result =[
            "po"=>$po,
            "lpbgudang_list"=>$poDetails,
        ];

        return $result;

    }

    public function getListItemByLPBGudangIds($finLPBGudangIds){
        $ssql ="SELECT b.fin_item_id,c.fst_item_code,b.fst_custom_item_name,b.fst_unit,b.fdc_price,b.fst_disc_item,SUM(a.fdb_qty) as fdb_qty_total FROM trlpbgudangitems a 
            INNER JOIN trpodetails b ON a.fin_po_detail_id = b.fin_po_detail_id 
            INNER JOIN msitems c ON b.fin_item_id = c.fin_item_id 
            WHERE fin_lpbgudang_id IN ? 
            GROUP BY b.fin_item_id,c.fst_item_code,b.fst_custom_item_name,b.fst_unit,b.fdc_price,b.fst_disc_item";


        $qr = $this->db->query($ssql,[$finLPBGudangIds]);
        $rs = $qr->result();
        return $rs;
    }




    /*
    public function getDataById($finLPBGudangId){
        $ssql = "SELECT a.*,b.fst_po_no,b.fdt_po_datetime,c.fst_relation_name as fstSupplierName FROM " .$this->tableName. " a 
            INNER JOIN trpo b on a.fin_po_id = b.fin_po_id 
            INNER JOIN msrelations c on b.fin_supplier_id = c.fin_relation_id 
            WHERE a.fin_lpbgudang_id = ? and a.fst_active != 'D' ";

        $qr = $this->db->query($ssql, [$finLPBGudangId]);
        $rwLPBGudang = $qr->row();

        if ($rwLPBGudang == null){
            return null;
        }

        $ssql = "SELECT a.*,b.fin_item_id,b.fst_custom_item_name,b.fst_unit,c.fst_item_code,b.fdb_qty as fdb_qty_po,b.fdb_qty_lpb FROM trlpbgudangitems a
            INNER JOIN trpodetails b ON a.fin_po_detail_id = b.fin_po_detail_id
            INNER JOIN msitems c ON b.fin_item_id = c.fin_item_id 
            WHERE fin_lpbgudang_id = ?";

        $qr = $this->db->query($ssql,[$finLPBGudangId]);        
        $rsLPBGudangItems = $qr->result();

        $data = [
            "lpbGudang" => $rwLPBGudang,
            "lpbGudangItems" => $rsLPBGudangItems,
		];
		return $data;
    }
    
    

    
    
   

    public function unposting($finLPBGudangId,$unpostingDateTime =""){
        $this->load->model("trinventory_model");
        //$this->load->model("glledger_model");
        $result=[
            "status"=>"SUCCESS",
            "message"=>""
        ];

        $unpostingDateTime = $unpostingDateTime == "" ? date("Y-m-d H:i:s") : $unpostingDateTime;

        $ssql ="select * from trlpbgudang where fin_lpbgudang_id = ?";        
        $qr = $this->db->query($ssql,[$finLPBGudangId]);        
        $dataH = $qr->row();

        //get Detail Transaksi
        $ssql ="select * from trlpbgudangitems where fin_lpbgudang_id = ?";        
        $qr = $this->db->query($ssql,[$finLPBGudangId]);        
        $listItems = $qr->result();        
        foreach($listItems as $item){            
            $ssql = "update trpodetails set fdb_qty_lpb = fdb_qty_lpb - ? where fin_po_detail_id = ?";
            $this->db->query($ssql,[$item->fdb_qty,$item->fin_po_detail_id]);
            $this->trinventory_model->deleteByCodeId("LPB",$finLPBGudangId);
        }

        $dbError  = $this->db->error();
		if ($dbError["code"] != 0){	
            $result["status"]= "FAILED";
            $result["message"]= $dbError["message"];            
			return $result;
        }
        
        
        return $result;
    }

    public function posting($finLPBGudangId){
        $this->load->model("trinventory_model");

        $ssql = "select * from trlpbgudang where fin_lpbgudang_id = ?";
        $qr= $this->db->query($ssql,[$finLPBGudangId]);
        $dataH = $qr->row();

        $ssql = "SELECT a.*,b.fin_item_id,b.fst_unit,b.fdc_price,b.fdb_qty as qty_po,b.fdb_qty_lpb as qty_lpb,b.fst_custom_item_name FROM trlpbgudangitems a 
            LEFT JOIN trpodetails b on a.fin_po_detail_id = b.fin_po_detail_id
            WHERE fin_lpbgudang_id = ?";

        $qr= $this->db->query($ssql,[$finLPBGudangId]);
        $dataD = $qr->result();

        $result=[
            "status"=>"SUCCESS",
            "message"=>""
        ];

        foreach($dataD as $detail){
            
            //Cek qty_lpb < qty po
            $qtyPO = (double) $detail->qty_po;
            $qtyLPB= (double) $detail->qty_lpb;
            $qtyTransaksi = (double) $detail->fdb_qty;
            $qtySisa =$qtyPO -$qtyLPB;


            if ( $qtySisa >=  $qtyTransaksi ){
                //Update detail PO                
                $ssql = "update trpodetails set fdb_qty_lpb = fdb_qty_lpb + $detail->fdb_qty where fin_po_detail_id = $detail->fin_po_detail_id";
                $this->db->query($ssql,[]);

                //Update kartu stock
                $dataStock = [
                    //`fin_rec_id`, 
                    "fin_warehouse_id"=>$dataH->fin_warehouse_id,
                    "fdt_trx_datetime"=>$dataH->fdt_lpbgudang_datetime,
                    "fst_trx_code"=>"LPB", 
                    "fin_trx_id"=>$finLPBGudangId, 
                    "fst_trx_no"=>$dataH->fst_lpbgudang_no, 
                    "fst_referensi"=>null, 
                    "fin_item_id"=>$detail->fin_item_id, 
                    "fst_unit"=>$detail->fst_unit, 
                    "fdb_qty_in"=>$detail->fdb_qty, 
                    "fdb_qty_out"=>0, 
                    "fdc_price_in"=>$detail->fdc_price, 
                    "fst_active"=>"A" 
                ];
                $this->trinventory_model->insert($dataStock);

            }else{
                $result["status"] ="FAILED";
                $result["message"] ="Qty penerimaan " . $detail->fst_custom_item_name ." tidak bisa melebihi $qtySisa";
                return $result;
            }

        }

        if( $result["status"] != "SUCCESS"){
            var_dump($result); 
            throw new Exception($result["message"], EXCEPTION_JURNAL);
        }

        return $result;
       
    }

    public function update($data){
        //Cancel Transaksi
        $ssql ="delete from trlpbgudangitems where fin_lpbgudang_id = ?";
        $this->db->query($ssql,$data["fin_lpbgudang_id"]);        

        parent::update($data);

    }
   
   public function delete($finLPBGudangId,$softDelete = true,$data=null){
        //bila sudah dibuat invoice pembeliaan transaksi tidak dapat di edit ataupun dihapus
        $isEditable = $this->isEditable($finLPBGudangId);
        if($isEditable["status"] != "SUCCESS"){
            return $isEditable;
        }

        $resp = $this->unposting($finLPBGudangId);       
        if($resp["status"] != "SUCCESS"){
            return $resp;
        }

        //Delete detail transaksi
        if ($softDelete){
            $ssql ="update trlpbgudangitems set fst_active ='D' where fin_lpbgudang_id = ?";
            $this->db->query($ssql,[$finLPBGudangId]);
            
            $ssql ="update trlpbgudang set fst_active ='D' where fin_lpbgudang_id = ?";
            $this->db->query($ssql,[$finLPBGudangId]);
            
            
        }else{
            $ssql ="delete from trlpbgudangitems where fin_lpbgudang_id = ?";
            $this->db->query($ssql,[$finLPBGudangId]);
            
            parent::delete($finLPBGudangId,$softDelete,$data);
        }

        $dbError  = $this->db->error();
		if ($dbError["code"] != 0){			
			$resp["status"] = "DB_FAILED";
			$resp["message"] = $dbError["message"];
			return $resp;
        }

        return ["status" => "SUCCESS","message"=>""];
   }

   public function isEditable($finLPBGudangId){
       $resp =["status"=>"SUCCESS","message"=>""];
       return $resp;
   }
   */
}


