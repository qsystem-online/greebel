<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trmemowoeout_model extends MY_Model{
	public $tableName = "trmemowoeout";
	public $pkey = "fin_woeout_id";

	public function __construct()
	{
		parent::__construct();
	}

	public function getRules($mode = "ADD", $id = 0)
	{
		$rules = [];

		$rules[] = [
			'field' => 'fst_woeout_no',
			'label' => 'Nomor Memo Out',
			'rules' => array(
				'required',
			),
			'errors' => array(
				'required' => '%s tidak boleh kosong',
				//'is_unique' => '%s unik'
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
		$prefix = getDbConfig("woe_memo_out_prefix") . "/" . $branchCode ."/";
		$query = $this->db->query("SELECT MAX(fst_woeout_no) as max_id FROM trmemowoeout where fst_woeout_no like '".$prefix.$tahun."%'");
		
		$row = $query->row_array();        
		$max_id = $row['max_id']; 		
		$max_id1 =(int) substr($max_id,strlen($max_id)-5);		
		$fst_tr_no = $max_id1 +1;		
		$max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);		
		return $max_tr_no;
    }
    
    public function getDataById($finWOEOutId){                
        
        $ssql = "SELECT a.*,b.fst_wo_no,b.fdb_qty as fdb_qty_wo,b.fst_unit,c.fst_relation_name as fst_supplier_name,d.fst_item_name,d.fst_item_code  
            FROM trmemowoeout a 
            INNER JOIN trwo b on a.fin_wo_id = b.fin_wo_id
            INNER JOIN msrelations c on a.fin_supplier_id = c.fin_relation_id
            INNER JOIN msitems d  on b.fin_item_id = d.fin_item_id            
            where a.fin_woeout_id = ?";
        $qr = $this->db->query($ssql,[$finWOEOutId]);
        $data = $qr->row();
        if ($data == null){
            return null;
        }

        $ssql = "SELECT sum(fdb_qty)  as ttl_qty_out FROM trmemowoeout where fin_wo_id = ? and fst_active = 'A'";
        $qr =$this->db->query($ssql,[$data->fin_wo_id]);
        $rw = $qr->row();

        $data->ttl_qty_out = $rw->ttl_qty_out;
        $data->ttl_qty_balance = $data->fdb_qty_wo - $rw->ttl_qty_out;

		return [
			"data"=>$data,
		];
	}
    
    
































	

	public function getDataHeader($finWOId){
		$ssql = "SELECT * FROM trwo WHERE fin_wo_id = ? ";
		$qr = $this->db->query($ssql,[$finWOId]);
		$dataH = $qr->row();
		return $dataH;
	}

	public function posting($finWOId){	
	}
	
	public function isEditable(){
		//Belum ada MAG dari wo ini

	}


	public function deleteDetail($finWOId){
		$ssql ="DELETE FROM trwobomdetails where fin_wo_id = ?";
		$this->db->query($ssql,[$finWOId]);

		$ssql ="DELETE FROM trwoactivitydetails where fin_wo_id = ?";
		$this->db->query($ssql,[$finWOId]);

	}

	public function delete($finId,$softDelete=true,$data=null){
		parent::delete($finId,$softDelete);
		if(!$softDelete){
			//$this->db->delete("trmpsitems",array("fin_mts_id"=>$finId));
			//DELETE BOM WO
			$this->db->delete("trwobomdetails",array("fin_wo_id"=>$finId));			
			//DELETE ACTIVITY
			$this->db->delete("trwoactivitydetails",array("fin_wo_id"=>$finId));
		}else{
			$this->db->query("update trwobomdetails set fst_active ='D' where fin_wo_id = ?",[$finId]);
			$this->db->query("update trwoactivitydetails set fst_active ='D' where fin_wo_id = ?",[$finId]);
		}        		
		return [
			"status"=>true,
			"message"=>"",
		];
	}

	public function getDetailMaterialRequired($finWOId){
		$ssql = "SELECT * FROM trwo where fin_wo_id = ? and fst_active = 'A'";
		$qr= $this->db->query($ssql,[$finWOId]);
		$wo = $qr->row();
		if($wo == null){
			return [];
		}

		$ssql ="SELECT a.*,
			b.fst_item_code,b.fst_item_name,b.fbl_is_batch_number,b.fbl_is_serial_number FROM trwobomdetails a INNER JOIN 
			msitems b on a.fin_item_id = b.fin_item_id
			WHERE a.fin_wo_id = ? and a.fst_active ='A'";
		$qr= $this->db->query($ssql,[$finWOId]);
		$detailBOMWO = $qr->result();

		$detailBOMWO = $this->ajxCalculateMaterialRequirment($wo->fin_item_id,$wo->fst_unit,$wo->fdb_qty,$detailBOMWO);
		return $detailBOMWO;
	}

	public function ajxCalculateMaterialRequirment($finItemId,$fstUnit,$fdbQty,$detailBOMWO){
		$this->load->model("msitems_model");
		$this->load->model("trinventory_model");
		$this->load->model("mswarehouse_model");
		
		$ssql = "SELECT * FROM msitems where fin_item_id = ? and fst_active ='A'";
		$qr=$this->db->query($ssql,[$finItemId]);
		$item = $qr->row();
		if ($item == null){
			throw new CustomException("Item tidak dikenal !",3003,"FAILED",[]);
		}

		$scale = (double) $item->fdc_scale_for_bom;
		$basicUnit = $this->msitems_model->getBasicUnit($finItemId);
		
		$fdbQtyInBasic = (double) $this->msitems_model->getQtyConvertToBasicUnit($finItemId,$fdbQty,$fstUnit);
		$resultDetails = [];		

		for($i=0;$i<sizeof($detailBOMWO);$i++){
			$bom = $detailBOMWO[$i];
			$bom->fdb_qty_real = ($fdbQtyInBasic/$scale) * $bom->fdb_qty;

			$mainWarehouseId = $this->mswarehouse_model->getMainWarehouseId($this->aauth->get_active_branch_id());
			$hpp = $this->trinventory_model->getTotalHPP($bom->fin_item_id,$bom->fst_unit,$bom->fdb_qty_real,$mainWarehouseId);
			$bom->fdc_ttl_hpp = (double) $hpp;
			$detailBOMWO[$i] = $bom;
		}
		return $detailBOMWO;
	}

	public function getQtyMAG($finWODetailId){
		/*
		$ssql ="SELECT * FROM trwobomdetails where fin_rec_id = ? and fst_active ='A'";
		$qr = $this->db->query($ssql,[$finWODetailId]);
		$rw = $qr->row();
		if ($rw==null){
			return 0;
		}
		*/


		//$finWOId = $rw->fin_wo_id;

		/*
		$ssql = "SELECT sum(b.fdb_qty) as ttl_qty FROM trmag a 
			INNER JOIN trmagitems b on a.fin_mag_id = b.fin_mag_id 
			WHERE a.fin_wo_id = ? and b.fin_wo_detail_id = ? and a.fst_active != 'D' ";
		*/

		$ssql = "SELECT sum(fdb_qty) as ttl_qty FROM trmagitems  WHERE fin_wo_detail_id = ? and fst_active != 'D' ";


		//$qr =$this->db->query($ssql,[$finWOId,$finWODetailId]);
		$qr =$this->db->query($ssql,[$finWODetailId]);
		$rw = $qr->row();

		if ($rw == null){
			return 0;
		}

		if ($rw->ttl_qty == null){
			return 0;			
		}

		return (double) $rw->ttl_qty;

	}

	public function getReqQty($finWODetailId){
		$this->load->model("msitems_model");

		$ssql = "SELECT a.*,
			b.fin_item_id as fin_item_id_master,
			b.fdb_qty as fdb_qty_master,
			b.fst_unit as fst_unit_master,
			c.fdc_scale_for_bom 			
		FROM trwobomdetails a 		
		INNER JOIN trwo b  on a.fin_wo_id = b.fin_wo_id 
		INNER JOIN msitems c on b.fin_item_id = c.fin_item_id 
		where a.fin_rec_id = ? and a.fst_active = 'A'";

		$qr = $this->db->query($ssql,[$finWODetailId]);
		
		$rw = $qr->row();

		if ($rw == null){
			return 0;
		}
		$scale = (double) $rw->fdc_scale_for_bom;
		$basicUnit = $this->msitems_model->getBasicUnit($rw->fin_item_id_master);

		$fdbQtyInBasic = (double) $this->msitems_model->getQtyConvertToBasicUnit($rw->fin_item_id_master,$rw->fdb_qty_master,$rw->fst_unit_master);		

		return ($fdbQtyInBasic/$scale) * $rw->fdb_qty;	
	}

}