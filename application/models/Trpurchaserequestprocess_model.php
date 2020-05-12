<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Trpurchaserequestprocess_model extends MY_Model {
	public $tableName = "trpurchaserequestprocess";
	public $pkey = "fin_process_id";

	public function __construct(){
		parent:: __construct();
	}

	public function getRules($mode="ADD",$id=0){
		$rules = [];
        /*
		$rules[] = [
			'field' => 'fst_pr_no',
			'label' => 'Purchase Request No',
			'rules' => 'required',
			'errors' => array(
				'required' => '%s tidak boleh kosong',
			)
        ];
        */		
		return $rules;
	}


	public function getDetailByProcessId($finProcessId){
        $ssql ="SELECT c.fin_pr_id,c.fst_pr_no,a.fin_item_id,b.fst_item_name,a.fst_unit,fdb_qty_req,fdb_qty_process,fdb_qty_to_po 
			FROM trpurchaserequestitems a
			INNER JOIN msitems b on a.fin_item_id = b.fin_item_id
			INNER JOIN trpurchaserequest c on a.fin_pr_id = c.fin_pr_id
			WHERE a.fin_process_id = ?";             

		$qr = $this->db->query($ssql,[$finProcessId]);
		$rs = $qr->result();
        return $rs;        

    }
    

	public function cancelProcess($finProcessId){
        $ssql = "SELECT a.* FROM trpurchaserequestprocess a 
            inner join trpo b on a.fin_po_id = b.fin_po_id 
            where a.fin_process_id = ? and b.fst_active != 'D'";

        $qr = $this->db->query($ssql,[$finProcessId]);
        $rw = $qr->row();
        //CEK IF PO EXIST
        if ($rw != null){
            throw new CustomException(lang("Proceess telah dilakukan PO"),3003,"FAILED",[]);
        }
        		

		//CEK IF PROCESS already distribute
		$ssql ="SELECT * FROM trpurchaserequestitems where fin_process_id = ? and fdb_qty_distribute > 0";
		$qr = $this->db->query($ssql,[$finProcessId]);
		$rw = $qr->row();
		if ($rw != NULL){
			throw new CustomException(lang("Proceess telah didistribusikan"),3003,"FAILED",[]);
		}  


        $ssql = "DELETE FROM trpurchaserequestprocess where fin_process_id =?";
        $qr = $this->db->query($ssql,[$finProcessId]);
        throwIfDBError();
        
        $ssql = "UPDATE trpurchaserequestitems set fin_process_id = NULL, fdb_qty_process=0 where fin_process_id = ? ";        
        $qr = $this->db->query($ssql,[$finProcessId]);
        throwIfDBError();

		
	}

	public function getHeaderByPO($finPOId){
		$ssql ="SELECT * FROM trpurchaserequestprocess where fin_po_id = ?";
		$qr = $this->db->query($ssql,[$finPOId]);
		$rw = $qr->row();		
		return $rw;
	}
	










	

	

	
	
	
	

	

	public function updateClosedStatus($finPurchaseReturnId){

		$ssql = "select * from trpurchasereturnitems where fin_purchasereturn_id = ? and fdb_qty > fdb_qty_out";
		$qr = $this->db->query($ssql,$finPurchaseReturnId);
		if ($qr->row() == null){
			//Transaksi Return Completed
			$ssql = "update trpurchasereturn set fdt_closed_datetime = now() , fbl_is_closed = 1, fst_closed_notes = 'AUTO - ".date("Y-m-d H:i:s") ."' where fin_purchasereturn_id = ?";
			$this->db->query($ssql,[$finPurchaseReturnId]);
		}else{
			$ssql = "update trpurchasereturn set fdt_closed_datetime = null , fbl_is_closed = 0, fst_closed_notes = null where fin_purchasereturn_id = ?";
			$this->db->query($ssql,[$finPurchaseReturnId]);
		}
	}
}


