<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trmrp_model extends MY_Model{
    public $tableName = "trmrp";
    public $pkey = "fin_mrp_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fst_mrp_no',
            'label' => 'Nomor MRP',
            'rules' => array(
                'required',
			),
			'errors' => array(
                'required' => '%s tidak boleh kosong',
				//'is_unique' => '%s unik'
			),
        ];      
        
        $rules[] = [
            'field' => 'fin_mps_id',
            'label' => 'MPS',
            'rules' => array(
                'required',
			),
			'errors' => array(
                'required' => '%s tidak boleh kosong',
			),
        ]; 

        $rules[] = [
            'field' => 'fin_mps_month',
            'label' => 'MPS Month',
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
		$prefix = getDbConfig("mrp_prefix") . "/" . $branchCode ."/";
        $query = $this->db->query("SELECT MAX(fst_mrp_no) as max_id FROM trmrp where fst_mrp_no like '".$prefix.$tahun."%'");
        
		$row = $query->row_array();        
		$max_id = $row['max_id']; 		
		$max_id1 =(int) substr($max_id,strlen($max_id)-5);		
		$fst_tr_no = $max_id1 +1;		
		$max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);		
		return $max_tr_no;
    }
    
    public function getDataById($finMRPId){
        $this->load->model("trinventory_model");
        

        $ssql = "SELECT a.*,b.fst_mps_no,b.fin_year FROM trmrp a 
            INNER JOIN trmps b on a.fin_mps_id = b.fin_mps_id
            WHERE a.fin_mrp_id = ? and a.fst_active != 'D'";

        $qr = $this->db->query($ssql,[$finMRPId]);
        $dataH = $qr->row();

        if ($dataH == null){
            return null;
        }
        
        $ssql = "SELECT a.*,b.fst_item_name,b.fst_item_code FROM trmrpweekdetails a 
            INNER JOIN msitems b on a.fin_item_id = b.fin_item_id            
            WHERE fin_mrp_id = ?";            
        $qr = $this->db->query($ssql,[$finMRPId]);
        $weekDetails = $qr->result();

        for($i = 0 ;$i < sizeof($weekDetails);$i++){
            $rw = $weekDetails[$i];
            $rw->fdb_qty_mps = $this->getMPSQty($dataH->fin_mps_id,$rw->fin_item_id,$dataH->fin_mps_month);
            $weekDetails[$i]= $rw;
        }

        $stockDate = $dataH->fin_year ."-" . substr("00".$dataH->fin_mps_month,strlen("00".$dataH->fin_mps_month) -2,2) ."-01";

        $ssql = "SELECT a.*,b.fst_item_name,b.fst_item_code FROM trmrpmaterialdetails a 
            INNER JOIN msitems b on a.fin_item_id = b.fin_item_id
			WHERE a.fin_mrp_id = ? AND a.fst_active ='A'";

		$qr = $this->db->query($ssql,[$finMRPId]);
        $materialDetails = $qr->result();                        
        for($i = 0 ;$i < sizeof($materialDetails);$i++){
            $rw = $materialDetails[$i];
            $rw->fdb_qty_balance = $this->trinventory_model->getLastStockAllBranch($rw->fin_item_id,$stockDate);
            $materialDetails[$i]= $rw;
        }



        return [
            "header"=>$dataH,
            "weekDetails"=>$weekDetails,
            "materialDetails"=>$materialDetails,
        ];
    }

    public function getDataHeader($finMRPId){
        $ssql = "SELECT * FROM trmrp WHERE fin_mrp_id = ? ";
        $qr = $this->db->query($ssql,[$finMRPId]);
        $dataH = $qr->row();
        return $dataH;
    }

    public function posting($finMPSId){
        //Update qty buffer
        $ssql = "SELECT * FROM trmpsitems where fin_mps_id = ? and fst_active ='A'";
        $qr = $this->db->query($ssql,[$finMPSId]);
        $rs = $qr->result();
        foreach($rs as $rw){
            $ssql = "UPDATE msitems set fdb_qty_buffer_stock = ? where fin_item_id = ?";
            $this->db->query($ssql,[$rw->fdb_qty_buffer,$rw->fin_item_id]);
        }        
    }
    

    public function deleteDetail($finMRPId){
        $ssql ="DELETE FROM trmrpweekdetails where fin_mrp_id = ?";
        $this->db->query($ssql,[$finMRPId]);

        $ssql ="DELETE FROM trmrpmaterialdetails where fin_mrp_id = ?";
        $this->db->query($ssql,[$finMRPId]);

    }

    public function delete($finId,$softDelete=true,$data=null){
		parent::delete($finId,$softDelete);
		if(!$softDelete){
			$this->db->delete("trmpsitems",array("fin_mts_id"=>$finId));
        }else{
            $this->db->query("update trmpsitems set fst_active ='D' where fin_mps_id = ?",[$finId]);
        }        		
		return [
			"status"=>true,
			"message"=>"",
		];
	}
    

    public function getMPSQty($finMPSId,$finItemId,$finMonth){
        $ssql = "SELECT * FROM trmpsitems where fin_mps_id = ? AND fin_item_id = ? AND fst_active = 'A'";
        $qr = $this->db->query($ssql,[$finMPSId,$finItemId]);
        $rw = $qr->row();
        if ($rw == null){
            return 0;
        }

        switch ($finMonth){
            case 1:
                return $rw->fdb_qty_m01;
                break;
            case 2:
                return $rw->fdb_qty_m02;
                break;
            case 3:
                return $rw->fdb_qty_m03;
                break;
            case 4:
                return $rw->fdb_qty_m04;
                break;
            case 5:
                return $rw->fdb_qty_m05;
                break;
            case 6:
                return $rw->fdb_qty_m06;
                break;
            case 7:
                return $rw->fdb_qty_m07;
                break;
            case 8:
                return $rw->fdb_qty_m08;
                break;
            case 9:
                return $rw->fdb_qty_m09;
                break;                
            case 10:
                return $rw->fdb_qty_m10;
                break;
            case 11:
                return $rw->fdb_qty_m11;
                break;
            case 12:
                return $rw->fdb_qty_m12;
                break;
        }


    }

}