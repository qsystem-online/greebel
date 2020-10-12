<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trmagconfirm_model extends MY_Model {
	public $tableName = "trmagconfirm";
	public $pkey = "fin_mag_confirm_id";

	public function __construct(){
		parent:: __construct();
	}

	public function getRules($mode="ADD",$id=0){
		$rules = [];
		$rules[] = [
			'field' => 'fst_mag_no',
			'label' => 'MAG No',
			'rules' => 'required',
			'errors' => array(
				'required' => '%s tidak boleh kosong',
			)
		];

		return $rules;
	}

	
	public function GenerateNo($trDate = null) {
		$trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
		$tahun = date("Y/m", strtotime ($trDate));
		$activeBranch = $this->aauth->get_active_branch();
		$branchCode = "";
		if($activeBranch){
			$branchCode = $activeBranch->fst_branch_code;
		}
        $prefix = getDbConfig("mag_confirm_prefix") . "/" . $branchCode ."/";
        
		$query = $this->db->query("SELECT MAX(fst_mag_confirm_no) as max_id 
            FROM trmagconfirm where fst_mag_confirm_no like '".$prefix.$tahun."%'");

		$row = $query->row_array();

		$max_id = $row['max_id']; 
		
		$max_id1 =(int) substr($max_id,strlen($max_id)-5);
		
		$fst_tr_no = $max_id1 +1;
		
		$max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);
		
		return $max_tr_no;
	}

	public function GenerateNoByMAG($finMAGId) {
		$ssql = "SELECT * FROM trmag where fin_mag_id = ? and fst_active = 'A'";
		$qr= $this->db->query($ssql,[$finMAGId]);
		$rw = $qr->row();
		if($rw == null){
			return null;
		}

		$fstMAGNo =$rw->fst_mag_no;
		$ssql ="select * from trmagconfirm where fst_mag_confirm_no like ? order by fst_mag_confirm_no desc limit 1";
		$qr = $this->db->query($ssql,[$fstMAGNo ."%"]);
		$rw = $qr->row();
		if ($rw==null){
			return $fstMAGNo . "-01";
		}
		$lastConfirmNo = $rw->fst_mag_confirm_no;
		$lastConfirmNo =  (double) substr($lastConfirmNo,strlen($lastConfirmNo) -2);
		$nextConfirmNo = $lastConfirmNo + 1;
		$nextConfirmNo = "00" . $nextConfirmNo;
		$nextConfirmNo = substr($nextConfirmNo,strlen($nextConfirmNo)-2);

		
		return $fstMAGNo . "-".$nextConfirmNo;
	}

	
	public function getDataById($finPagId){
		$this->load->model("msitemunitdetails_model");

		$ssql = "SELECT a.*,
            b.fin_from_warehouse_id,b.fin_to_warehouse_id,b.fst_mag_no,b.fdt_mag_datetime,
            c.fst_warehouse_name as fst_from_warehouse_name,d.fst_warehouse_name as fst_to_warehouse_name,
			e.fst_wo_no 
            FROM trmagconfirm a 
            INNER JOIN trmag b on a.fin_mag_id = b.fin_mag_id
			INNER JOIN mswarehouse c on b.fin_from_warehouse_id = c.fin_warehouse_id
			INNER JOIN mswarehouse d on b.fin_to_warehouse_id = d.fin_warehouse_id
			LEFT JOIN trwo e on b.fin_wo_id = e.fin_wo_id 
			where a.fin_mag_confirm_id = ? and a.fst_active != 'D'";

        $qr = $this->db->query($ssql,[$finPagId]);
        
		
		$header = $qr->row();

		
		if ($header != null){
			$ssql = "SELECT a.*,
				b.fst_item_code,b.fst_item_name,fbl_is_batch_number,fbl_is_serial_number
				FROM trmagitems a 
				INNER JOIN msitems b on a.fin_item_id = b.fin_item_id 
				WHERE fin_mag_id = ?";

			$qr = $this->db->query($ssql,[$header->fin_mag_id]);
			$details = $qr->result();

			for($i =0;$i < sizeof($details);$i++){
				$dataD = $details[$i];
				$dataD->fst_basic_unit = $this->msitemunitdetails_model->getBasicUnit($dataD->fin_item_id);
				$dataD->fdc_conv_to_basic_unit = $this->msitemunitdetails_model->getConversionUnit($dataD->fin_item_id,1,$dataD->fst_unit,$dataD->fst_basic_unit);
				$details[$i]= $dataD;				
			}
			return [
				"header"=>$header,
				"details"=>$details
			];
		}

		return [
			"header"=>null,
			"details"=>null
		];

	}
	public function getDataHeaderById($finPagId){
		$ssql = "SELECT * FROM trmagconfirm a
			where fin_mag_confirm_id = ? and fst_active != 'D'";

		$qr = $this->db->query($ssql,[$finPagId]);		
		$header = $qr->row();
		return $header;
	}
	public function unposting($finPagId){		
		$this->load->model("trinventory_model");		
		$ssql ="SELECT * FROM trmagconfirm where fin_mag_confirm_id = ?";
		$qr = $this->db->query($ssql,[$finPagId]);
		$dataH = $qr->row();
		if ($dataH == null){
			throw new CustomException(lang("invalid mag confirm id"),404,"FAILED",[]);	
		}

		//delete Inventory
		$this->trinventory_model->deleteByCodeId("PAGBO",$finPagId);
        $this->trinventory_model->deleteByCodeId("PAGIN",$finPagId);
		
		//Delete itemdetails
		$this->trinventory_model->deleteInsertSerial("PAG",$finPagId);

        $this->checkCloseMag((int) $dataH->fin_mag_id);
	}

	public function deleteDetail($finMagId){
		$ssql = "update trmagitems set fdb_qty_confirm = 0 , fst_serial_number_list_confirm = null where fin_mag_id =?";
		$this->db->query($ssql,[$finMagId]);
		throwIfDBError();
	}

	public function isEditable($finMagId){
		return [
			"status"=>"SUCCESS"
		];
	}

	public function isEditableProduction($finMagId){
		//Tidak boleh sudah di lakukan RMOUT
		
		return [
			"status"=>"SUCCESS"
		];
	}

	

	public function getDataVoucher($finMagId){
		$data = $this->getDataById($finMagId);

		$header =  $data["header"];

		
		$header->fst_from_warehouse_name=$this->mswarehouse_model->getValue($header->fin_from_warehouse_id,"fst_warehouse_name");
		$header->fst_to_warehouse_name=$this->mswarehouse_model->getValue($header->fin_to_warehouse_id,"fst_warehouse_name");
		$data["header"] = (array) $header;

		return $data;
	}


	public function posting($finMagConfirmId){
		$this->load->model("trinventory_model");
		$this->load->model("mswarehouse_model");

		$ssql ="SELECT a.*,b.fbl_update_stock,b.fin_from_warehouse_id,b.fin_to_warehouse_id,b.fin_branch_id as fin_from_branch_id
        FROM trmagconfirm a 
        inner join trmag b on a.fin_mag_id = b.fin_mag_id 
        where a.fin_mag_confirm_id = ?";

		$qr = $this->db->query($ssql,[$finMagConfirmId]);
		$dataH = $qr->row();
		if ($dataH == null){
			throw new CustomException(lang("invalid mag confirm id"),404,"FAILED",[]);	
		}else{


			if ($dataH->fbl_update_stock != 1){
				throw new CustomException(lang("Stock belum di update"),404,"FAILED",[]);	
			}
		}


		$ssql = "SELECT * FROM trmagitems where fin_mag_id = ?";
		$qr = $this->db->query($ssql,[$dataH->fin_mag_id]);
		$details = $qr->result();

		foreach($details as $dataD){			
			//Mutasi OUT barang dari buffer warehouse 
			$this->load->model("mswarehouse_model");
			$bufferWarehouse = $this->mswarehouse_model->getBufferWarehouseId($dataH->fin_from_branch_id);

			$data = [
				"fin_warehouse_id"=>$bufferWarehouse,//$dataH->fin_from_warehouse_id,
				"fdt_trx_datetime"=>$dataH->fdt_mag_confirm_datetime,
				"fst_trx_code"=>"PAGBO", //PAG_BUFFER_OUT
				"fin_trx_id"=>$dataH->fin_mag_confirm_id,
				"fst_trx_no"=>$dataH->fst_mag_confirm_no,
				"fin_trx_detail_id"=>$dataD->fin_rec_id,
				"fst_referensi"=>$dataH->fst_memo,
				"fin_item_id"=>$dataD->fin_item_id,
				"fst_unit"=>$dataD->fst_unit,
				"fdb_qty_in"=>0,
				"fdb_qty_out"=>$dataD->fdb_qty_confirm,
				"fdc_price_in"=>0,
				"fst_active"=>"A"
			];
			$this->trinventory_model->insert($data);

			//Mutasi IN barang dari buffer warehouse base on branch id
			//$ssql = "SELECT * FROM trinventory where fin_trx_detail_id = ? and fst_trx_code ='MAGBI'";
			$ssql = "SELECT * FROM trinventory where fin_trx_detail_id = ?";
			$qr =$this->db->query($ssql,[$dataD->fin_rec_id]);
			$rw = $qr->row();
			if ($rw == null){

			}

			$hpp = $rw->fdc_price_in;						
			$data = [
				"fin_warehouse_id"=>$dataH->fin_to_warehouse_id,
				"fdt_trx_datetime"=>$dataH->fdt_mag_confirm_datetime,
				"fst_trx_code"=>"PAGIN", //PAG_IN
				"fin_trx_id"=>$dataH->fin_mag_confirm_id,
				"fst_trx_no"=>$dataH->fst_mag_confirm_no,
				"fin_trx_detail_id"=>$dataD->fin_rec_id,
				"fst_referensi"=>$dataH->fst_memo,
				"fin_item_id"=>$dataD->fin_item_id,
				"fst_unit"=>$dataD->fst_unit,
				"fdb_qty_in"=>$dataD->fdb_qty_confirm,
				"fdb_qty_out"=>0,
				"fdc_price_in"=>$hpp,
				"fst_active"=>"A"
			];
			$this->trinventory_model->insert($data);

			//transfer masuk serial dan batch no
			$dataSerial = [
				"fin_warehouse_id"=>$dataH->fin_to_warehouse_id,
				"fin_item_id"=>$dataD->fin_item_id,
				"fst_unit"=>$dataD->fst_unit,
				"fst_serial_number_list"=>$dataD->fst_serial_number_list,
				"fst_batch_no"=>$dataD->fst_batch_number,
				"fst_trans_type"=>"PAG", 
				"fin_trans_id"=>$dataH->fin_mag_confirm_id,
				"fst_trans_no"=>$dataH->fst_mag_confirm_no,
				"fin_trans_detail_id"=>$dataD->fin_rec_id,
				"fdb_qty"=>$dataD->fdb_qty,
				"in_out"=>"IN",
			];
			$this->trinventory_model->insertSerial($dataSerial);			
		} //end for

		$this->checkCloseMag((int) $dataH->fin_mag_id);		
	}

	public function checkCloseMag($finMagId){
		
		$ssql = "SELECT * FROM trmagitems where fin_mag_id = ? and fdb_qty < fdb_qty_confirm";
		$qr = $this->db->query($ssql,[$finMagId]);				
		$rw = $qr->row();
		if ($rw == null){
			$ssql = "UPDATE trmag set fbl_closed = 1,fin_closed_by = 0, fdt_closed_datetime = now() where fin_mag_id = ?";
			$this->db->query($ssql,[$finMagId]);
		}else{
            $ssql = "UPDATE trmag set fbl_closed = 0,fin_closed_by = null, fdt_closed_datetime = null where fin_mag_id = ?";
			$this->db->query($ssql,[$finMagId]);
        }
	}

    public function delete($key, $softdelete = TRUE,$data=null){
        

        $ssql ="select * from trmagconfirm where fin_mag_confirm_id = ? and fst_active != 'D'";
        $qr= $this->db->query($ssql,[$key]);
        $dataH = $qr->row();
        if ($dataH == null){
            return;
        }

        $this->deleteDetail($dataH->fin_mag_id);        
        parent::delete($key,$softdelete,$data);
    }


}