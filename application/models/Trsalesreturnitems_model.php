<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trsalesreturnitems_model extends MY_Model {
    public $tableName = "trsalesreturnitems";
    public $pkey = "fin_rec_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];
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
}


