<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trmps_model extends MY_Model{
    public $tableName = "trmps";
    public $pkey = "fin_mps_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fst_mps_no',
            'label' => 'Nomor MPS',
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
		$prefix = getDbConfig("mps_prefix") . "/" . $branchCode ."/";
        $query = $this->db->query("SELECT MAX(fst_mps_no) as max_id FROM trmps where fst_mps_no like '".$prefix.$tahun."%'");
        
		$row = $query->row_array();        
		$max_id = $row['max_id']; 		
		$max_id1 =(int) substr($max_id,strlen($max_id)-5);		
		$fst_tr_no = $max_id1 +1;		
		$max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);		
		return $max_tr_no;
    }
    
    public function getDataById($finMPSId){
        $this->load->model("trinventory_model");

        $ssql = "SELECT a.*,b.fst_item_group_name FROM trmps a 
            INNER JOIN msgroupitems b on a.fin_item_group_id = b.fin_item_group_id
            WHERE fin_mps_id = ? and a.fst_active != 'D'";

        $qr = $this->db->query($ssql,[$finMPSId]);
        $dataH = $qr->row();

        if ($dataH == null){
            return null;
        }
        
        $ssql = "SELECT a.*,b.fst_item_name,b.fst_item_code FROM trmpsitems a 
            INNER JOIN msitems b on a.fin_item_id = b.fin_item_id
            WHERE fin_mps_id = ?";            
        $qr = $this->db->query($ssql,[$finMPSId]);
        $details = $qr->result();

        $ssql = "SELECT a.* FROM trmtsitems a 
			INNER JOIN trmts b on a.fin_mts_id = b.fin_mts_id 
			WHERE b.fin_year = ? and b.fin_item_group_id = ? and a.fst_active ='A'";

		$qr = $this->db->query($ssql,[$dataH->fin_year,$dataH->fin_item_group_id]);
        $detailsMTS = $qr->result();
                
        for($i=0;$i<sizeof($details);$i++){
            $rw = $details[$i];
            $rw->fdb_qty_buffer_stock  =  $rw->fdb_qty_buffer;

            $arrMTS = [
				"fdb_qty_m01"=>0,
				"fdb_qty_m02"=>0,
				"fdb_qty_m03"=>0,
				"fdb_qty_m04"=>0,
				"fdb_qty_m05"=>0,
				"fdb_qty_m06"=>0,
				"fdb_qty_m07"=>0,
				"fdb_qty_m08"=>0,
				"fdb_qty_m09"=>0,
				"fdb_qty_m10"=>0,
				"fdb_qty_m11"=>0,
				"fdb_qty_m12"=>0,				
            ];
            
            foreach($detailsMTS as $dataMTS){
				if ($dataMTS->fin_item_id  == $rw->fin_item_id){
					$arrMTS = [
						"fdb_qty_m01"=>$dataMTS->fdb_qty_m01,
						"fdb_qty_m02"=>$dataMTS->fdb_qty_m02,
						"fdb_qty_m03"=>$dataMTS->fdb_qty_m03,
						"fdb_qty_m04"=>$dataMTS->fdb_qty_m04,
						"fdb_qty_m05"=>$dataMTS->fdb_qty_m05,
						"fdb_qty_m06"=>$dataMTS->fdb_qty_m06,
						"fdb_qty_m07"=>$dataMTS->fdb_qty_m07,
						"fdb_qty_m08"=>$dataMTS->fdb_qty_m08,
						"fdb_qty_m09"=>$dataMTS->fdb_qty_m09,
						"fdb_qty_m10"=>$dataMTS->fdb_qty_m10,
						"fdb_qty_m11"=>$dataMTS->fdb_qty_m11,
						"fdb_qty_m12"=>$dataMTS->fdb_qty_m12
					];					
					break;
				}
            }
            
            $rw->fdb_qty_mts_m01 = $arrMTS["fdb_qty_m01"];
			$rw->fdb_qty_mts_m02 = $arrMTS["fdb_qty_m02"];
			$rw->fdb_qty_mts_m03 = $arrMTS["fdb_qty_m03"];
			$rw->fdb_qty_mts_m04 = $arrMTS["fdb_qty_m04"];
			$rw->fdb_qty_mts_m05 = $arrMTS["fdb_qty_m05"];
			$rw->fdb_qty_mts_m06 = $arrMTS["fdb_qty_m06"];
			$rw->fdb_qty_mts_m07 = $arrMTS["fdb_qty_m07"];
			$rw->fdb_qty_mts_m08 = $arrMTS["fdb_qty_m08"];
			$rw->fdb_qty_mts_m09 = $arrMTS["fdb_qty_m09"];
			$rw->fdb_qty_mts_m10 = $arrMTS["fdb_qty_m10"];
			$rw->fdb_qty_mts_m11 = $arrMTS["fdb_qty_m11"];
            $rw->fdb_qty_mts_m12 = $arrMTS["fdb_qty_m12"];
            
            //Get  last Year Balance
			$lastDate = $dataH->fin_year . "-01-01";
			$qtyStockBasicUnit = $this->trinventory_model->getLastStockAllBranch($rw->fin_item_id,$lastDate);
			$rw->fdb_last_period_qty = $qtyStockBasicUnit;
			$details[$i] = $rw;		
        }


        return [
            "header"=>$dataH,
            "details"=>$details
        ];
    }

    public function getDataHeader($finMPSId){
        $ssql = "SELECT * FROM trmps WHERE fin_mps_id = ? ";
        $qr = $this->db->query($ssql,[$finMPSId]);
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
    

    public function deleteDetail($finMPSId){
        $ssql ="DELETE FROM trmpsitems where fin_mps_id = ?";
        $this->db->query($ssql,[$finMPSId]);
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
    


}