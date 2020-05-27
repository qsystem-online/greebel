<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trdistributepr_model extends MY_Model {
	public $tableName = "trdistributepr";
	public $pkey = "fin_distributepr_id";

	public function __construct(){
		parent:: __construct();
	}

	public function getRules($mode="ADD",$id=0){
		$rules = [];

		$rules[] = [
			'field' => 'fst_distributepr_no',
			'label' => 'Distribute No',
			'rules' => 'required',
			'errors' => array(
				'required' => '%s tidak boleh kosong',
			)
		];
		
		return $rules;
	}

	public function getDataById($finDistributePRId){
		$this->load->model("msitems_model");

		$ssql = "SELECT * 
			FROM trdistributepr
			WHERE fin_distributepr_id = ?
		";

		$qr = $this->db->query($ssql, [$finDistributePRId]);
		$dataH = $qr->row();

		
		$ssql = "SELECT a.*,
			b.fin_item_id,b.fst_unit,
			c.fin_req_department_id,c.fst_pr_no,c.fdt_pr_datetime,c.fst_memo,
			d.fst_department_name,
			e.fst_item_code,e.fst_item_name,f.fst_warehouse_name,b.fdt_etd 						
			FROM trdistributepritems a
			INNER JOIN trpurchaserequestitems b on a.fin_pr_detail_id = b.fin_rec_id
			INNER JOIN trpurchaserequest c on b.fin_pr_id = c.fin_pr_id 
			INNER JOIN departments d on c.fin_req_department_id = d.fin_department_id
			INNER JOIN msitems e on b.fin_item_id = e.fin_item_id 
			INNER JOIN mswarehouse f on a.fin_source_warehouse_id = f.fin_warehouse_id 			
			WHERE a.fin_distributepr_id = ?";

		$qr = $this->db->query($ssql,[$finDistributePRId]);   
		$dataDetails = $qr->result();

		for($i =0 ;$i<sizeof($dataDetails) ;$i++){
			$detail = $dataDetails[$i];

			$detail->fst_basic_unit =  $this->msitems_model->getBasicUnit($detail->fin_item_id);
			$detail->fin_conv_basic_unit = $this->msitems_model->getConversionUnit($detail->fin_item_id,$detail->fst_unit,$detail->fst_basic_unit);
			$dataDetails[$i] = $detail;
		}
		
		
		

		$data = [
			"dataH" => $dataH,
			"dataDetails" => $dataDetails
		];

		return $data;
	}




	public function generateTransactionNo($trDate = null) {
		$trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
		$tahun = date("Y/m", strtotime ($trDate));
		$activeBranch = $this->aauth->get_active_branch();
		$branchCode = "";
		if($activeBranch){
			$branchCode = $activeBranch->fst_branch_code;
		}
		$prefix = getDbConfig("distribute_pr_prefix") . "/" . $branchCode ."/";
		$query = $this->db->query("SELECT MAX(fst_distributepr_no) as max_id FROM trdistributepr where fst_distributepr_no like '".$prefix.$tahun."%'");
        $row = $query->row_array();
        

		$max_id = $row['max_id']; 
		
		$max_id1 =(int) substr($max_id,strlen($max_id)-5);
		
		$fst_tr_no = $max_id1 +1;
		
		$max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);
		
		return $max_tr_no;
    }

    public function getNeedToDistribute(){
		$this->load->model("msitems_model");

        $ssql = "SELECT a.*,
			b.fst_pr_no,b.fin_req_department_id,b.fdt_pr_datetime,
			c.fst_department_name,
			d.fst_item_code,d.fst_item_name,d.fin_item_type_id,d.fbl_stock,fbl_is_batch_number,fbl_is_serial_number
			FROM trpurchaserequestitems a
            INNER JOIN trpurchaserequest b on a.fin_pr_id = b.fin_pr_id 
            INNER JOIN departments c on b.fin_req_department_id = c.fin_department_id 
            INNER JOIN msitems d on a.fin_item_id = d.fin_item_id 
            WHERE fdb_qty_distribute < fdb_qty_process and fbl_closed_distribute = false";

		$qr = $this->db->query($ssql);

		$rs = $qr->result();
		for($i=0;$i<sizeof($rs);$i++){
			$rw = $rs[$i];
			$rw->fst_basic_unit = $this->msitems_model->getBasicUnit($rw->fin_item_id);
			$rw->fin_conv_basic_unit = $this->msitems_model->getConversionUnit($rw->fin_item_id,$rw->fst_unit,$rw->fst_basic_unit);
			$rs[$i] = $rw;
		}
		
        return $rs;

	}
	
	public function posting($finDistributePRId){

		$ssql = "SELECT * from trdistributepr where fin_distributepr_id = ?";
		$qr = $this->db->query($ssql,[$finDistributePRId]);
		$dataH = $qr->row();
		if ($dataH == null){
			throw new CustomException("Invalid Distribute PR ID",3003,"FAILED",[]);
		}

		$ssql = "SELECT a.*,b.fin_item_id,b.fst_unit,c.fin_item_type_id,c.fbl_stock,c.fin_item_group_id,d.fin_department_id,e.fst_department_type,
			FROM trdistributepritems a 
			INNER JOIN trpurchaserequestitems b on a.fin_pr_detail_id = b.fin_rec_id
			INNER JOIN msitems c on b.fin_item_id = c.fin_item_id
			INNER JOIN trpurchasereques d on b.fin_pr_id = d.fin_pr_id
			INNER JOIN departments e on d.fin_department_id = e.fin_department_id 
			where a.fin_distributepr_id = ?";

		$qr = $this->db->query($ssql,[$finDistributePRId]);
		$rs = $qr->result();
		$dataJurnal = [];

		foreach($rs as $rw){
			$ssql = "UPDATE trpurchaserequestitems set fdb_qty_distribute = fdb_qty_distribute + ? WHERE fin_rec_id = ?";
			$this->db->query($ssql,[$rw->fdb_qty_distribute ,$rw->fin_pr_detail_id]);	
			if ($this->db->error()["code"]	!= 0){
				throw new CustomException($this->db->error()["message"],"3003","FAILED",[]);
			}

			//Update kartu stock
			$dataStock = [
				//`fin_rec_id`, 
				"fin_warehouse_id"=>$rw->fin_source_warehouse_id,
				"fdt_trx_datetime"=>$dataH->fdt_distributepr_datetime,
				"fst_trx_code"=>"PRD", 
				"fin_trx_id"=>$dataH->fin_distributepr_id,
				"fin_trx_detail_id"=>$rw->fin_rec_id,
				"fst_trx_no"=>$dataH->fst_distributepr_no, 
				"fst_referensi"=>null, 
				"fin_item_id"=>$rw->fin_item_id, 
				"fst_unit"=>$rw->fst_unit, 
				"fdb_qty_in"=>0, 
				"fdb_qty_out"=>$rw->fdb_qty_distribute, 
				"fdc_price_in"=>0,
				"fst_active"=>"A" 
			];
			$this->trinventory_model->insert($dataStock);

			$dataSerial = [
                "fin_warehouse_id"=>$rw->fin_source_warehouse_id,
                "fin_item_id"=>$rw->fin_item_id,
                "fst_unit"=>$rw->fst_unit,
                "fst_serial_number_list"=>$rw->fst_serial_number_list,
                "fst_batch_no"=>$rw->fst_batch_number,
                "fst_trans_type"=>"PRD", 
                "fin_trans_id"=>$dataH->fin_distributepr_id,
                "fst_trans_no"=>$dataH->fst_distributepr_no,
                "fin_trans_detail_id"=>$rw->fin_rec_id,
                "fdb_qty"=>$rw->fdb_qty_distribute,
                "in_out"=>"OUT",
            ];            
			$this->trinventory_model->insertSerial($dataSerial);			

			//jurnal 
			if ($rw->fin_item_type_id == 5 && $rw->fbl_stock == true){
				
				$ssql ="select * from msgroupitems where fin_item_group_id = $rw->fin_item_group_id";
				$qr = $this->db->query($ssql,[]);
				$rwItemGroup = $qr->row();

				$typeBiaya = $rw->fst_department_type == "Umum" ? "BIAYA_UMUM" :"BIAYA_PABRIKASI";
				$accBiaya = getLogisticGLConfig($rw->fin_item_group_id,$typeBiaya);
				$accPersediaan =getLogisticGLConfig($rw->fin_item_group_id,"PERSEDIAAN");
				$cost = $this->trinventory_model->getLastHPP($rw->fin_item_id,$rw->fin_source_warehouse_id);

				//Biaya Pada Persediaan Supplies
				$dataJurnal[] =[ 
					"fin_branch_id"=>$dataH->fin_branch_id,
					"fst_account_code"=>$accBiaya ,
					"fdt_trx_datetime"=>$dataH->fdt_distributepr_datetime,
					"fst_trx_sourcecode"=>"PRD",
					"fin_trx_id"=>$dataH->fin_distributionpr_id,
					"fst_trx_no"=>$dataH->fst_distributionpr_no,
					"fst_reference"=>$rw->fst_notes,
					"fdc_debit"=> $cost,
					"fdc_origin_debit"=>$cost,
					"fdc_credit"=>0,
					"fdc_origin_credit"=>0,
					"fst_orgi_curr_code"=>getDefaultCurrency(),
					"fdc_orgi_rate"=>1,
					"fst_no_ref_bank"=>null,
					"fin_pcc_id"=>$rwItemGroup->fin_pcc_id,
					"fin_pc_divisi_id"=>$rw->fin_department_id,
					"fin_relation_id"=>NULL,
					"fst_active"=>"A",
					"fst_info"=>"Biaya Distribute logistik stock",
				];

				$dataJurnal[] =[ 
					"fin_branch_id"=>$dataH->fin_branch_id,
					"fst_account_code"=>$accPersediaan ,
					"fdt_trx_datetime"=>$dataH->fdt_distributepr_datetime,
					"fst_trx_sourcecode"=>"PRD",
					"fin_trx_id"=>$dataH->fin_distributionpr_id,
					"fst_trx_no"=>$dataH->fst_distributionpr_no,
					"fst_reference"=>$rw->fst_notes,
					"fdc_debit"=> 0,
					"fdc_origin_debit"=>0,
					"fdc_credit"=>$cost,
					"fdc_origin_credit"=>$cost,
					"fst_orgi_curr_code"=>getDefaultCurrency(),
					"fdc_orgi_rate"=>1,
					"fst_no_ref_bank"=>null,
					"fin_pcc_id"=>null,
					"fin_pc_divisi_id"=>null,
					"fin_relation_id"=>NULL,
					"fst_active"=>"A",
					"fst_info"=>"Biaya Distribute logistik stock",
				];
			}
			


		}

		
		
		

	}

	public function unposting($finDistributePRId){
	}


    

























	

	

	
	public function isEditable($finPRId){       
		/**
		 * FALSE CONDITION
		 * 1. kalau sudah publish tidak bisa diedit
		 * 
		 */
		$dataH = $this->getDataHeaderById($finPRId);
		if ($dataH->fdt_publish_datetime != null){
			return ["status"=>"FAILED","message"=>"Purchase request yang sudah di publish tidak bisa di rubah.."];
		}
		$resp =["status"=>"SUCCESS","message"=>""];
		return $resp;
    }
    

	public function deleteDetail($finPRId){
		$ssql ="delete from trpurchaserequestitems where fin_pr_id = ?";
		$this->db->query($ssql,[$finPRId]);
		throwIfDBError();        
	}
	public function update($data){
		//Delete Field yang tidak boleh berubah
		parent::update($data);        
	}

	public function delete($finPRId,$softDelete = true,$data=null){
		if ($softDelete){
			$ssql ="update trpurchaserequestitems set fst_active ='D' where fin_pr_id = ?";
			$this->db->query($ssql,[$finPRId]);
		}else{
			$ssql ="delete from trpurchaserequestitems where fin_pr_id = ?";
			$this->db->query($ssql,[$finPRId]);            
		}
		parent::delete($finPRId,$softDelete,$data);

		return ["status" => "SUCCESS","message"=>""];
	}

}


