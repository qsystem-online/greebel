<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trwobatchno_model extends MY_Model{
    public $tableName = "trwobatchno";
    public $pkey = "fin_wobatchno_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fin_wo_id',
            'label' => 'Workorder ID',
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

    public function generateBatchNo($finWOId){
        $ssql = "SELECT * FROM trwobatchno where fin_wo_id = ? order by fst_wobatchno_no desc limit 1";
        $qr = $this->db->query($ssql,[$finWOId]);
        $rw = $qr->row();
        if($rw == null){
            $ssql = "SELECT * FROM trwo where fin_wo_id = ? and fst_active = 'A'";
            $qr= $this->db->query($ssql,[$finWOId]);
            $rwWO = $qr->row();
            if($rwWO == null){
                return null;
            }
            $lastNo = substr($rwWO->fst_wo_no,strlen($rwWO->fst_wo_no)-4);
            $lastNo .= "-000";
        }else{
            $lastNo = $rw->fst_wobatchno_no;
        }

        $woPrefix = substr($lastNo,0,strlen($lastNo)-3);
        $no  = (int) substr($lastNo,strlen($lastNo)-3);
        $no += 1;
        $strNextNo = "000" .$no;
        $strNextNo = substr($strNextNo,strlen($strNextNo)-3);
        return $woPrefix . $strNextNo;
    }

    public function getDataById($finWOBatchnoId){
        $ssql = "SELECT fin_wobatchno_id,fst_wobatchno_no,fin_wo_id,fdt_wobatchno_datetime,fst_notes,fst_active 
        FROM trwobatchno where fin_wobatchno_id = ? and fst_active != 'D'";
        $qr = $this->db->query($ssql,[$finWOBatchnoId]);
        $rw = $qr->row();
        return $rw;
    }
}