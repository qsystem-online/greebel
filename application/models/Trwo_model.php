<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trwo_model extends MY_Model{
	public $tableName = "trwo";
	public $pkey = "fin_wo_id";

	public function __construct()
	{
		parent::__construct();
	}

	public function getRules($mode = "ADD", $id = 0)
	{
		$rules = [];

		$rules[] = [
			'field' => 'fst_wo_no',
			'label' => 'Nomor WO',
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
		$prefix = getDbConfig("wo_prefix") . "/" . $branchCode ."/";
		$query = $this->db->query("SELECT MAX(fst_wo_no) as max_id FROM trwo where fst_wo_no like '".$prefix.$tahun."%'");
		
		$row = $query->row_array();        
		$max_id = $row['max_id']; 		
		$max_id1 =(int) substr($max_id,strlen($max_id)-5);		
		$fst_tr_no = $max_id1 +1;		
		$max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);		
		return $max_tr_no;
	}
	
	public function getDataById($finWOId){                
		$this->load->model("msitems_model");

		$ssql = "SELECT a.*,
			b.fst_item_name,b.fst_item_code,b.fdc_scale_for_bom,
			c.fin_item_group_id,c.fst_item_group_name,
			d.fst_activity_group_name,
			e.fst_relation_name as fst_supplier_name 
			FROM trwo a 
			INNER JOIN msitems b on a.fin_item_id = b.fin_item_id
			INNER JOIN msgroupitems c on b.fin_item_group_id = c.fin_item_group_id       
			LEFT JOIN msactivitygroups d on a.fin_activity_group_id = d.fin_activity_group_id 
			LEFT JOIN msrelations e on a.fin_supplier_id = e.fin_relation_id 
			WHERE a.fin_wo_id = ? and a.fst_active !='D'";
		
		$qr = $this->db->query($ssql,[$finWOId]);      
		$dataH = $qr->row();

		if ($dataH == null){
			return null;
		}

		$dataH->fst_basic_unit = $this->msitems_model->getBasicUnit($dataH->fin_item_id);
		

		//Detail BOM Master
		$ssql = "SELECT a.fin_item_id,a.fst_unit,a.fdb_qty,b.fst_item_code,b.fst_item_name FROM msitembomdetails a
			INNER JOIN msitems b on a.fin_item_id_bom = b.fin_item_id 
			WHERE a.fin_item_id = ? AND a.fst_active ='A' ";
		$qr = $this->db->query($ssql,[$dataH->fin_item_id]);		
		$detailsBOMMaster = $qr->result();

		//Detail BOM WO
		$ssql = "SELECT a.*,b.fst_item_code,b.fst_item_name FROM 
			trwobomdetails a 
			INNER JOIN msitems b on a.fin_item_id = b.fin_item_id
			WHERE a.fin_wo_id = ? and a.fst_active ='A'";
		$qr = $this->db->query($ssql,[$finWOId]);
		$detailsBOMWO = $qr->result();

		//Detail Activity
		$ssql = "SELECT a.*,b.fst_name,b.fst_team,b.fst_type FROM 
			trwoactivitydetails a 
			INNER JOIN msactivity b on a.fin_activity_id = b.fin_activity_id
			WHERE a.fin_wo_id = ? and a.fst_active ='A'";
		$qr = $this->db->query($ssql,[$finWOId]);
		$detailsActivity = $qr->result();
		
		//Detail MAG PAG
		$ssql = "SELECT a.fin_mag_id,a.fst_mag_no,a.fdt_mag_datetime,
			c.fst_item_name,b.fst_unit,b.fdb_qty,d.fst_mag_confirm_no,d.fdt_mag_confirm_datetime 
			FROM trmag a 
			INNER JOIN trmagitems b on a.fin_mag_id = b.fin_mag_id
			INNER JOIN msitems c on b.fin_item_id = c.fin_item_id
			LEFT JOIN trmagconfirm d on a.fin_mag_id = d.fin_mag_id
			Where a.fin_wo_id = ? and a.fst_active = 'A'";
		$qr = $this->db->query($ssql,[$finWOId]);
		$detailsMAGPAG = $qr->result();

		//Detail RMOUT
		$ssql = "SELECT a.fin_rmout_id,a.fst_rmout_no,a.fdt_rmout_datetime,c.fst_item_name,b.fst_unit,b.fdb_qty 
			FROM trrmout a 
			INNER JOIN trrmoutitems b on a.fin_rmout_id = b.fin_rmout_id
			INNER JOIN msitems c on b.fin_item_id = c.fin_item_id			
			Where a.fin_wo_id = ? and a.fst_active = 'A'";
		$qr = $this->db->query($ssql,[$finWOId]);
		$detailsRmout = $qr->result();


		//Detail LHP
		$ssql = "SELECT a.fin_lhp_id,a.fst_lhp_no,a.fdt_lhp_datetime,a.fin_wobatchno_id,b.fst_wobatchno_no,
			a.fin_warehouse_id,d.fst_warehouse_name,
			a.fdb_gramasi,a.fin_item_id,c.fst_item_name,a.fst_unit,a.fdb_qty
			FROM trlhp a 
			INNER JOIN trwobatchno b on a.fin_wobatchno_id = b.fin_wobatchno_id
			INNER JOIN msitems c on a.fin_item_id = c.fin_item_id
			INNER JOIN mswarehouse d on a.fin_warehouse_id = d.fin_warehouse_id
			Where a.fin_wo_id = ? and a.fst_active = 'A'";
		$qr = $this->db->query($ssql,[$finWOId]);
		$detailsLHP = $qr->result();


		//Detail Batch NO
		$ssql = "SELECT * FROM trwobatchno WHERE fin_wo_id = ? and fst_active != 'D'";
		$qr = $this->db->query($ssql,[$finWOId]);
		$detailsBatchno = $qr->result();

		return [
			"header"=>$dataH,
			"detailsBOMMaster"=>$detailsBOMMaster,
			"detailsBOMWO"=>$detailsBOMWO,
			"detailsActivity"=>$detailsActivity,
			"detailsMAGPAG"=>$detailsMAGPAG,
			"detailsBatchno"=>$detailsBatchno,
			"detailsRmout"=>$detailsRmout,
			"detailsLHP"=>$detailsLHP
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