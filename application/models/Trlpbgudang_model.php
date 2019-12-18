<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trlpbgudang_model extends MY_Model {
    public $tableName = "trlpbgudang";
    public $pkey = "fin_lpbgudang_id";

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


    public function getDataById($finLPBGudangId){
        $ssql = "SELECT a.*,c.fst_relation_name  FROM " .$this->tableName. " a             
            INNER JOIN msrelations c on a.fin_relation_id = c.fin_relation_id 
            WHERE a.fin_lpbgudang_id = ? and a.fst_active != 'D' ";

        $qr = $this->db->query($ssql, [$finLPBGudangId]);
        $rwLPBGudang = $qr->row();

        if ($rwLPBGudang == null){
            return [
                "lpbGudang" => null,
                "lpbGudangItems" => [],
            ];
        }


        switch ($rwLPBGudang->fst_lpb_type ){
            case "PO":
                $ssql = "SELECT a.fin_rec_id,a.fin_trans_detail_id,a.fin_item_id,a.fst_custom_item_name,a.fst_unit,a.fdb_qty,a.fst_batch_number,a.fst_serial_number_list,a.fdc_m3,
                    b.fdb_qty as fdb_qty_trans,b.fdb_qty_lpb,
                    c.fst_item_code,c.fbl_is_batch_number,c.fbl_is_serial_number,d.fdc_conv_to_basic_unit,e.fst_unit as fst_basic_unit 
                    FROM trlpbgudangitems a
                    INNER JOIN trpodetails b ON a.fin_trans_detail_id = b.fin_po_detail_id                    
                    INNER JOIN msitems c ON b.fin_item_id = c.fin_item_id 
                    INNER JOIN msitemunitdetails d ON (b.fin_item_id = d.fin_item_id and a.fst_unit = d.fst_unit)  
                    INNER JOIN msitemunitdetails e ON (b.fin_item_id = e.fin_item_id and e.fbl_is_basic_unit = 1)                       
                    WHERE fin_lpbgudang_id = ?";
                break;

            case "SO_RETURN":

                break;
            default:
                return [
                    "lpbGudang" => null,
                    "lpbGudangItems" => [],
                ];
        }
        


        
        $qr = $this->db->query($ssql,[$finLPBGudangId]);
        throwIfDBError();

        $rsLPBGudangItems = $qr->result();

        $data = [
            "lpbGudang" => $rwLPBGudang,
            "lpbGudangItems" => $rsLPBGudangItems,
		];
		return $data;
    }

    public function getDataHeaderById($finLPBGudangId){
        $ssql = "select * from trlpbgudang where fin_lpbgudang_id = ? and fst_active != 'D'";
        $qr = $this->db->query($ssql,[$finLPBGudangId]);
        return $qr->row();
        
    }

    
    public function generateLPBGudangNo($trDate = null) {
        $trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
        $tahun = date("Y/m", strtotime ($trDate));
        $activeBranch = $this->aauth->get_active_branch();
        $branchCode = "";
        if($activeBranch){
            $branchCode = $activeBranch->fst_branch_code;
        }

        $prefix = getDbConfig("lpb_gudang_prefix");


        //$query = $this->db->query("SELECT MAX(fst_po_no) as max_id FROM $table where $field like '".$prefix.$tahun."%'");
        $query = $this->db->query("SELECT MAX(fst_lpbgudang_no) as max_id FROM trlpbgudang where fst_lpbgudang_no like '".$prefix."/%/".$tahun."%'");

        $row = $query->row_array();

        $max_id = $row['max_id']; 
        
        $max_id1 =(int) substr($max_id,strlen($max_id)-5);
        
        $fst_tr_no = $max_id1 +1;
        
        $max_tr_no = $prefix."/". $branchCode .'/' .$tahun.'/'.sprintf("%05s",$fst_tr_no);
        
        return $max_tr_no;
    }

    public function getPOList(){

        /*
        $ssql = "select distinct a.fin_po_id,b.fst_po_no from trpodetails a 
            INNER JOIN trpo b on a.fin_po_id = b.fin_po_id 
            WHERE a.fdb_qty > a.fdb_qty_lpb 
            and b.fst_active ='A' 
            and b.fbl_is_closed = 0 
            and b.fdc_downpayment <= b.fdc_downpayment_paid";
        */
        $ssql = "SELECT a.fin_po_id as fin_trans_id,a.fst_po_no as fst_trans_no,a.fdt_po_datetime as fdt_trans_datetime,b.fst_relation_name  from trpo a 
            INNER JOIN msrelations b on a.fin_supplier_id = b.fin_relation_id 
            and a.fst_active ='A' 
            and a.fbl_is_closed = 0 
            and a.fdc_downpayment <= a.fdc_downpayment_paid";
        $qr = $this->db->query($ssql,[]);
        $rs = $qr->result();
        return $rs;        
    }    

    public function getPODetail($finPOId){
        $ssql = "SELECT a.fin_po_detail_id as fin_trans_detail_id,a.fdb_qty as fdb_qty_trans,a.fst_unit,a.fdb_qty_lpb,a.fin_item_id,a.fst_custom_item_name,
            b.fst_item_code,b.fst_item_name,b.fbl_is_batch_number,b.fbl_is_serial_number, 
            c.fdc_conv_to_basic_unit, d.fst_unit AS fst_basic_unit
            FROM trpodetails a
            INNER JOIN msitems b on a.fin_item_id = b.fin_item_id 
            INNER JOIN msitemunitdetails c on (a.fin_item_id = c.fin_item_id and a.fst_unit = c.fst_unit) 
            INNER JOIN msitemunitdetails d on (a.fin_item_id = d.fin_item_id and d.fbl_is_basic_unit = 1)  
            WHERE a.fdb_qty > a.fdb_qty_lpb AND a.fin_po_id = ?";

        $qr = $this->db->query($ssql,[$finPOId]);
        $poDetails=$qr->result(); 

        return $poDetails;

    }
   

    public function getSOReturnList(){
        $ssql = "SELECT a.fin_salesreturn_id as fin_trans_id,a.fst_salesreturn_no as fst_trans_no,a.fdt_salesreturn_datetime as fdt_trans_datetime,b.fst_relation_name FROM trsalesreturn a 
            INNER JOIN msrelations b on a.fin_customer_id = b.fin_relation_id 
            WHERE  a.fbl_is_closed = 0 and a.fst_active = 'A'";
        $qr = $this->db->query($ssql,[]);
        return $qr->result();
    }

    public function getSOReturnDetail($finSalesRetunId){
        $ssql = "SELECT a.fin_rec_id as fin_trans_detail_id,a.fdb_qty as fdb_qty_trans,a.fst_unit,a.fdb_qty_lpb,a.fin_item_id,a.fst_custom_item_name,
            b.fst_item_code,b.fst_item_name,b.fbl_is_batch_number,b.fbl_is_serial_number, 
            c.fdc_conv_to_basic_unit, d.fst_unit AS fst_basic_unit
            FROM trsalesreturnitems a
            INNER JOIN msitems b on a.fin_item_id = b.fin_item_id 
            INNER JOIN msitemunitdetails c on (a.fin_item_id = c.fin_item_id and a.fst_unit = c.fst_unit) 
            INNER JOIN msitemunitdetails d on (a.fin_item_id = d.fin_item_id and d.fbl_is_basic_unit = 1)  
            WHERE a.fdb_qty > a.fdb_qty_lpb AND a.fin_salesreturn_id = ?";

        $qr = $this->db->query($ssql,[$finSalesRetunId]);
        $soReturnDetails = $qr->result();        
        return $soReturnDetails;

    }
   


    public function unposting($finLPBGudangId,$unpostingDateTime =""){
        $this->load->model("trinventory_model");               
        $unpostingDateTime = $unpostingDateTime == "" ? date("Y-m-d H:i:s") : $unpostingDateTime;


        $ssql ="select * from trlpbgudang where fin_lpbgudang_id = ?";        
        $qr = $this->db->query($ssql,[$finLPBGudangId]);        
        $dataH = $qr->row();
        if($dataH == null){
            throw new CustomException("Invalid LPB Gudang Id",3003,"FAILED",["fin_lpbgudang_id"=>$finLPBGudangId]);
        }

        //get Detail Transaksi
        $ssql ="select * from trlpbgudangitems where fin_lpbgudang_id = ?";        
        $qr = $this->db->query($ssql,[$finLPBGudangId]);        
        $listItems = $qr->result();
        
        switch ($dataH->fst_lpb_type){
            case "PO":
                $this->unpostingLPBPO($listItems,$dataH);
                break;
            case "SO_RETURN":
                throw new CustomException("BELUM DIBUAT !!!!",3003,"FAILED",null);
                break;
            default:
                throw new CustomException("Invalid LPB Type",3003,"FAILED",["fst_lpb_type"=>$dataH->fst_lpb_type]);
        }

        //delete Inventory
        $this->trinventory_model->deleteByCodeId("LPB",$finLPBGudangId);
        //Delete itemdetails
        $this->trinventory_model->deleteInsertSerial("PPB",$finLPBGudangId);
        
    }

    private function unpostingLPBPO($listItems,$dataH){

        foreach($listItems as $item){
            $ssql = "update trpodetails set fdb_qty_lpb = fdb_qty_lpb - ? where fin_po_detail_id = ?";
            $this->db->query($ssql,[$item->fdb_qty,$item->fin_trans_detail_id]);
            throwIfDBError();
        }

        //Update Status Closed PO
        $this->trpo_model->updateClosedStatus($dataH->fin_trans_id);
    }


    public function posting($finLPBGudangId){
        $this->load->model("trinventory_model");
        $this->load->model("trpo_model");

        $ssql = "select * from trlpbgudang where fin_lpbgudang_id = ?";
        $qr= $this->db->query($ssql,[$finLPBGudangId]);
        $dataH = $qr->row();
        if ($dataH == null){
            throw new CustomException(lang("ID Penerimaan Gudang tidak dikenal"),3003,"FAILED");
        }
        
        if ($dataH->fst_lpb_type == "PO"){
            $this->postingLPBPO($dataH);
        }else if($dataH->fst_lpb_type == "SO_RETURN"){
            $this->postingLPBSOReturn($dataH);
        }else{
            throw new CustomException("Invalid LPB type",3003,"FAILED",$dataH);
        }

    }

    private function postingLPBPO($dataH){
        $finPOId = $dataH->fin_trans_id;

        $ssql = "SELECT a.*,b.fin_item_id,b.fst_unit,b.fdc_price,b.fst_disc_item,b.fdb_qty as qty_po,b.fdb_qty_lpb as qty_lpb,b.fst_custom_item_name 
            FROM trlpbgudangitems a 
            LEFT JOIN trpodetails b on a.fin_trans_detail_id = b.fin_po_detail_id
            WHERE fin_lpbgudang_id = ?";

        $qr = $this->db->query($ssql,[$dataH->fin_lpbgudang_id]);
        $dataDetails = $qr->result();

        
        foreach($dataDetails as $detail){
            //Cek qty_lpb < qty po
            $qtyPO = (float) $detail->qty_po;
            $qtyLPB= (float) $detail->qty_lpb;
            $qtyTransaksi = (float) $detail->fdb_qty;
            $qtySisa =$qtyPO -$qtyLPB;


            if ( $qtySisa >=  $qtyTransaksi ){
                //Update detail PO                
                $ssql = "update trpodetails set fdb_qty_lpb = fdb_qty_lpb + $detail->fdb_qty where fin_po_detail_id = $detail->fin_trans_detail_id";
                $this->db->query($ssql,[]);

                //Update kartu stock
                $dataStock = [
                    //`fin_rec_id`, 
                    "fin_warehouse_id"=>$dataH->fin_warehouse_id,
                    "fdt_trx_datetime"=>$dataH->fdt_lpbgudang_datetime,
                    "fst_trx_code"=>"LPB", 
                    "fin_trx_id"=>$dataH->fin_lpbgudang_id,
                    "fin_trx_detail_id"=>$detail->fin_rec_id,
                    "fst_trx_no"=>$dataH->fst_lpbgudang_no, 
                    "fst_referensi"=>null, 
                    "fin_item_id"=>$detail->fin_item_id, 
                    "fst_unit"=>$detail->fst_unit, 
                    "fdb_qty_in"=>$detail->fdb_qty, 
                    "fdb_qty_out"=>0, 
                    "fdc_price_in"=>(float) $detail->fdc_price - (float) calculateDisc($detail->fst_disc_item,$detail->fdc_price),
                    "fst_active"=>"A" 
                ];
                $this->trinventory_model->insert($dataStock);

                //Update msitemdetails & summary
                $dataSerial = [
                    "fin_warehouse_id"=>$dataH->fin_warehouse_id,
                    "fin_item_id"=>$detail->fin_item_id,
                    "fst_unit"=>$detail->fst_unit,
                    "fst_serial_number_list"=>$detail->fst_serial_number_list,
                    "fst_batch_no"=>$detail->fst_batch_number,
                    "fst_trans_type"=>"PPB", 
                    "fin_trans_id"=>$dataH->fin_lpbgudang_id,
                    "fst_trans_no"=>$dataH->fst_lpbgudang_no,
                    "fin_trans_detail_id"=>$detail->fin_rec_id,
                    "fdb_qty"=>$detail->fdb_qty,
                    "in_out"=>"IN",
                ];
                
                $this->trinventory_model->insertSerial($dataSerial);
                
            }else{
                throw new CustomException(sprintf(lang("Qty penerimaan %s tidak bisa melebih %s"),$detail->fst_custom_item_name,$qtySisa),3003,"FAILED",null);
            }
            
        }

        //Cek total qty Penerimaan 
        $ssql = "SELECT a.* FROM trpodetails a            
            WHERE a.fdb_qty_lpb > a.fdb_qty AND fin_po_id = ?";
        $qr = $this->db->query($ssql,[$finPOId]);
        $rs = $qr->result();
        $errorQty = [];
        foreach($rs as $rw){
            $errorQty[] = "Total $rw->fst_custom_item_name tidak boleh melebihi $rw->fdb_qty";
        }
        if (sizeof($errorQty) > 0){
            throw new CustomException(lang("Total qty penerimaan melebihi qty di PO !"),3003,"FAILED",$errorQty);
        }

        //Update Status Closed PO
        $this->trpo_model->updateClosedStatus($finPOId);   
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

   public function deleteDetail($finLPBGudangId){
       $ssql = "DELETE from trlpbgudangitems where fin_lpbgudang_id = ?";
       $this->db->query($ssql,[$finLPBGudangId]);
       throwIfDBError();
   }

   public function isEditable($finLPBGudangId,$dataH){
       /**
        * FAILED CONDITION
        * + PO Sudah terbit faktur
        * + PO bila  serial_number atau batch_no sudah terpakai
        * + SO RETURN
        */

        switch ($dataH->fst_lpb_type){
        
            case "PO" :
                /** 1. Sudah terbit faktur */
                $ssql = "select a.*,b.fst_lpbpurchase_no from trlpbgudang a
                    inner join trlpbpurchase b on a.fin_lpbpurchase_id = b.fin_lpbpurchase_id
                    where a.fin_lpbgudang_id = ?";

                $qr = $this->db->query($ssql,[$finLPBGudangId]);
                $rw = $qr->row();
                if($rw != null){
                    $resp =["status"=>"FAILED","message"=>sprintf(lang("Transaksi ini telah memiliki faktur %s" ),$rw->fst_lpbpurchase_no)];
                    return $resp;
                }

                /** cek bila batch no atau serial sudah terpakai */
                $ssql = "SELECT a.*,b.fst_item_code,b.fst_item_name FROM msitemdetails a 
                    INNER JOIN msitems b on a.fin_item_id = b.fin_item_id 
                    WHERE a.fst_trans_type ='PPB' AND a.fin_trans_id = ?";
                $qr = $this->db->query($ssql,[$finLPBGudangId]);
                $rs = $qr->result();
                foreach($rs as $rw){
                    $opBatch = $rw->fst_batch_no == null ? "is" : "=";
                    $opSerial = $rw->fst_serial_no == null ? "is" : "=";

                    $ssql = "SELECT * FROM msitemdetailssummary WHERE fin_warehouse_id = ? and fin_item_id = ? and fst_batch_no $opBatch ? and fst_serial_no $opSerial ?";
                    $qr = $this->db->query($ssql,[$rw->fin_warehouse_id,$rw->fin_item_id,$rw->fst_batch_no,$rw->fst_serial_no]);
                    $rwSumm = $qr->row();
                    if (($rwSumm->fdb_qty_in - $rwSumm->fdb_qty_out) < ($rw->fdb_qty_in - $rw->fdb_qty_out)){
                        $resp =["status"=>"FAILED",
                            "message"=>sprintf(
                                lang("Transaksi tidak dapat dihapus, item %s batch|serial: %s telah digunakan !" ),
                                $rw->fst_item_code . " - " . $rw->fst_item_name , 
                                $rw->fst_batch_no ."|". $rw->fst_serial_no
                            )
                        ];
                        return $resp;
                    }
                }
                break;

            case "SO_RETURN":
                break;
            default:

        }

        
        $resp =["status"=>"SUCCESS","message"=>""];
        return $resp;
   }

   public function getTransactionList($lpbType){
        if ($lpbType == "PO"){
            return $this->getPOList();
        }else if($lpbType == "SO_RETURN"){
            return $this->getSOReturnList();
        }
   }

   public function getTransDetail($lpbType,$finTransId){
        if ($lpbType == "PO"){
            return $this->getPODetail($finTransId);
        }else if($lpbType == "SO_RETURN"){
            return $this->getSOReturnDetail($finTransId);
        }
   }
}


