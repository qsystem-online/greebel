<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trmts_model extends MY_Model{
    public $tableName = "trmts";
    public $pkey = "fin_mts_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fst_mts_no',
            'label' => 'Nomor MTS',
            'rules' => array(
                'required',
			),
			'errors' => array(
                'required' => '%s tidak boleh kosong',
				//'is_unique' => '%s unik'
			),
        ];      
        $rules[] = [
            'field' => 'fin_year',
            'label' => 'Tahun MTS',
            'rules' => array(
                'required',
			),
			'errors' => array(
                'required' => '%s tidak boleh kosong',				
			),
        ];   

        $rules[] = [
            'field' => 'fin_item_group_id',
            'label' => 'Group Item',
            'rules' => array(
                'required',
			),
			'errors' => array(
                'required' => '%s tidak boleh kosong',
			),
        ]; 


        return $rules;
    }

    public function generateTransactionNo($trDate = null) {
		$trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
		$tahun = date("Y/m", strtotime ($trDate));
		$activeBranch = $this->aauth->get_active_branch();
		$branchCode = "";
		if($activeBranch){
			$branchCode = $activeBranch->fst_branch_code;
		}
		$prefix = getDbConfig("mts_prefix") . "/" . $branchCode ."/";
        $query = $this->db->query("SELECT MAX(fst_mts_no) as max_id FROM trmts where fst_mts_no like '".$prefix.$tahun."%'");
        
		$row = $query->row_array();        
		$max_id = $row['max_id']; 		
		$max_id1 =(int) substr($max_id,strlen($max_id)-5);		
		$fst_tr_no = $max_id1 +1;		
		$max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);		
		return $max_tr_no;
    }
    
    public function getDataById($finMTSId){
        $ssql = "SELECT a.*,b.fst_item_group_name FROM trmts a 
            INNER JOIN msgroupitems b on a.fin_item_group_id = b.fin_item_group_id
            WHERE fin_mts_id = ? ";

        $qr = $this->db->query($ssql,[$finMTSId]);
        $dataH = $qr->row();

        if ($dataH == null){
            return null;
        }
        
        $ssql = "SELECT a.*,b.fst_item_name,b.fst_item_code FROM trmtsitems a 
            INNER JOIN msitems b on a.fin_item_id = b.fin_item_id
            WHERE fin_mts_id = ?";
        $qr = $this->db->query($ssql,[$finMTSId]);
        $details = $qr->result();

        return [
            "header"=>$dataH,
            "details"=>$details
        ];
    }

    public function getSalesHistory($finItemId,$fstUnit,$histType,$mNumber,$currYear){
        $this->load->model("msitemunitdetails_model");        
        if ($histType == "LAST YEAR"){
            //GET TOTAL PENJUALAN TAHUN LALU TIAP BULANNYA
            $histYear = $currYear - 1;

            $ssql = "SELECT fst_unit,sum(fdb_qty) as fdb_qty FROM trsalesorderdetails a 
                INNER JOIN trsalesorder b on a.fin_salesorder_id = b.fin_salesorder_id
                WHERE fin_item_id = ?  and MONTH(fdt_salesorder_datetime) = ? and YEAR(fdt_salesorder_datetime) = ?
                GROUP BY fst_unit";


            $qr = $this->db->query($ssql,[$finItemId,$mNumber,$histYear]);
            $rs = $qr->result();
                    
		}else{
            //GET RATA2 TOTAL PENJUALAN 3 TAHUN TERAKHIR TIAP BULANNYA
            $histYear = [
                $currYear - 1,
                $currYear - 2,
                $currYear - 3
            ];
            $ssql = "SELECT fst_unit,round(sum(fdb_qty)/3,4) as fdb_qty FROM trsalesorderdetails a 
                INNER JOIN trsalesorder b on a.fin_salesorder_id = b.fin_salesorder_id
                WHERE fin_item_id = ?  and MONTH(fdt_salesorder_datetime) = ? and YEAR(fdt_salesorder_datetime) in ?
                GROUP BY fst_unit";
            $qr = $this->db->query($ssql,[$finItemId,$mNumber,$histYear]);
            $rs = $qr->result();
        }

        $ttlQty = 0;
        for($i=0;$i<sizeof($rs);$i++){
            $rw =  $rs[$i];                
            $qty = $rw->fdb_qty;
            //Convert unit
            $ttlQty += $this->msitemunitdetails_model->getConversionUnit($finItemId,$qty,$rw->fst_unit,$fstUnit);
        }
        return round($ttlQty,4);
    }
    


}