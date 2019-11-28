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
        $ssql = "SELECT a.*,b.fst_po_no,b.fdt_po_datetime,c.fst_relation_name as fstSupplierName FROM " .$this->tableName. " a 
            INNER JOIN trpo b on a.fin_po_id = b.fin_po_id 
            INNER JOIN msrelations c on b.fin_supplier_id = c.fin_relation_id 
            WHERE a.fin_lpbgudang_id = ? and a.fst_active != 'D' ";

        $qr = $this->db->query($ssql, [$finLPBGudangId]);
        $rwLPBGudang = $qr->row();

        if ($rwLPBGudang == null){
            return null;
        }

        $ssql = "SELECT a.*,b.fin_item_id,b.fst_custom_item_name,b.fst_unit,b.fdb_qty as fdb_qty_po,b.fdb_qty_lpb,
            c.fst_item_code,c.fbl_is_batch_number,c.fbl_is_serial_number,d.fdc_conv_to_basic_unit,e.fst_unit as fst_basic_unit 
            FROM trlpbgudangitems a
            INNER JOIN trpodetails b ON a.fin_po_detail_id = b.fin_po_detail_id
            INNER JOIN msitems c ON b.fin_item_id = c.fin_item_id 
            INNER JOIN msitemunitdetails d ON (b.fin_item_id = d.fin_item_id and a.fst_unit = d.fst_unit)  
            INNER JOIN msitemunitdetails e ON (b.fin_item_id = e.fin_item_id and e.fbl_is_basic_unit = 1)   
            WHERE fin_lpbgudang_id = ?";

        $qr = $this->db->query($ssql,[$finLPBGudangId]);


        $rsLPBGudangItems = $qr->result();

        $data = [
            "lpbGudang" => $rwLPBGudang,
            "lpbGudangItems" => $rsLPBGudangItems,
		];
		return $data;
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

        $ssql = "select distinct a.fin_po_id,b.fst_po_no from trpodetails a 
            INNER JOIN trpo b on a.fin_po_id = b.fin_po_id 
            WHERE a.fdb_qty > a.fdb_qty_lpb 
            and b.fst_active ='A' 
            and b.fbl_is_closed = 0 
            and b.fdc_downpayment <= b.fdc_downpayment_paid";

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


        $ssql = "SELECT a.*,
            b.fst_item_code,b.fst_item_name,b.fbl_is_batch_number,b.fbl_is_serial_number, 
            c.fdc_conv_to_basic_unit, d.fst_unit AS fst_basic_unit
            FROM trpodetails a
            INNER JOIN msitems b on a.fin_item_id = b.fin_item_id 
            INNER JOIN msitemunitdetails c on (a.fin_item_id = c.fin_item_id and a.fst_unit = c.fst_unit) 
            INNER JOIN msitemunitdetails d on (a.fin_item_id = d.fin_item_id and d.fbl_is_basic_unit = 1)  
            WHERE a.fdb_qty > a.fdb_qty_lpb AND a.fin_po_id = ?";

        $qr = $this->db->query($ssql,[$finPOId]);
        $poDetails=$qr->result();

        $result =[
            "po"=>$po,
            "po_details"=>$poDetails,
        ];

        return $result;

    }
   

    public function unposting($finLPBGudangId,$unpostingDateTime =""){
        $this->load->model("trinventory_model");
        $this->load->model("trpo_model");
        //$this->load->model("glledger_model");
        $result=[
            "status"=>"SUCCESS",
            "message"=>""
        ];

        try{
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

            //Delete itemdetails
            $ssql ="delete from msitemdetails where fst_trans_type = 'PPB' and fin_trans_id = ?";
            $this->db->query($ssql,[$finLPBGudangId]);            
            $this->my_model->throwIfDBError();
            
            //Update Status Closed PO
            $this->trpo_model->updateClosedStatus($dataH->fin_po_id);
        }catch(CustomException $e){           
            $result["status"]= $e->getStatus();
            $result["message"]= $e->getMessage();
            $result["data"]= $e->getData();
            return $result;
        }
        
        return $result;
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
        
        $finPOId = $dataH->fin_po_id;

        $ssql = "SELECT a.*,b.fin_item_id,b.fst_unit,b.fdc_price,b.fst_disc_item,b.fdb_qty as qty_po,b.fdb_qty_lpb as qty_lpb,b.fst_custom_item_name 
            FROM trlpbgudangitems a 
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

            }else{
                $result["status"] ="FAILED";
                $result["message"] ="Qty penerimaan " . $detail->fst_custom_item_name ." tidak bisa melebihi $qtySisa";
                return $result;
            }
        }

        //Cek total qty Penerimaan 
        $ssql = "SELECT a.* FROM trpodetails a
            INNER JOIN msitems b on a.fin_item_id = b.fin_item_id WHERE fdb_qty_lpb > fdb_qty AND fin_po_detail_id = $detail->fin_po_detail_id";
        $qr = $this->db->query($ssql,[]);
        $rs = $qr->result();
        $errorQty = [];
        foreach($rs as $rw){
            $errorQty[] = "Total $rw->fst_custom_item_name tidak boleh melebihi $rw->fdb_qty";
        }
        if (sizeof($errorQty) > 0){
            $result["status"] ="FAILED";
            $result["message"] ="Total qty penerimaan melebihi qty di PO !";
            $result["data"] = $errorQty;
            return $result;            
        }

        //Insert Detail item
        foreach($dataD as $detail){
            $strBatchNo = $detail->fst_batch_number;
            $strArrSerial = $detail->fst_serial_number_list;
            $arrSerial = json_decode($strArrSerial);
            if ($arrSerial != null){
                foreach($arrSerial as $serial){
                    $data = [
                        "fin_item_id"=>$detail->fin_item_id,
                        "fst_serial_no"=>$serial,
                        "fst_trans_type"=>"PPB",
                        "fin_trans_id"=>$detail->fin_lpbgudang_id,
                        "fdb_qty_in"=>1,
                        "fst_active"=>"A",
                        "fdt_insert_datetime"=>date("Y-m-d H:i:s")
                    ];
                    if ($strBatchNo != "" && $strBatchNo != null){
                        $data["fst_batch_no"] = $strBatchNo;
                    }

                    $this->db->insert("msitemdetails",$data);
                    $result = parent::getDBErrors();
                    if ($result["status"] != "SUCCESS"){
                        return ["status"=>"FAILED",
                            "message"=>sprintf(lang("Nomor serial %s tidak bisa disimpan!"),$serial),
                            "data"=>$result
                        ];
                    }
                }
            }else{
                if ($strBatchNo != "" && $strBatchNo != null){
                    $data = [
                        "fin_item_id"=>$detail->fin_item_id,
                        "fst_batch_no"=> $strBatchNo,
                        "fst_trans_type"=>"PPB",
                        "fin_trans_id"=>$detail->fin_lpbgudang_id,
                        "fdb_qty_in"=>$detail->fdb_qty,
                        "fst_active"=>"A",
                        "fdt_insert_datetime"=>date("Y-m-d H:i:s")
                    ];
                    try {
                        $this->db->insert("msitemdetails",$data);
                    }catch(Exception $e){
                        return ["status"=>"FAILED",
                            "message"=>$e->message,
                            "data"=>$e
                        ];
                    }
                }
            }
        }

        if( $result["status"] != "SUCCESS"){
            var_dump($result); 
            throw new Exception($result["message"], EXCEPTION_JURNAL);
        }


        //Update Status Closed PO
        $this->trpo_model->updateClosedStatus($finPOId);

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
       /**
        * FAILED CONDITION
        * 1. Sudah terbit faktur
        */

        /** 1. Sudahh terbit faktur */
        $ssql = "select a.*,b.fst_lpbpurchase_no from trlpbgudang a
            inner join trlpbpurchase b on a.fin_lpbpurchase_id = b.fin_lpbpurchase_id
            where a.fin_lpbgudang_id = ?";
        $qr = $this->db->query($ssql,[$finLPBGudangId]);
        $rw = $qr->row();
        if($rw != null){
            $resp =["status"=>"FAILED","message"=>sprintf(lang("Transaksi ini telah memiliki faktur %s" ),$rw->fst_lpbpurchase_no)];
            return $resp;
        }
        


        $resp =["status"=>"SUCCESS","message"=>""];
        return $resp;
   }
}


