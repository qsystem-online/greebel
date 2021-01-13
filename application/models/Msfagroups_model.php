<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Msfagroups_model extends MY_Model{
    public $tableName = "msfagroups";
    public $pkey = "fin_fa_group_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fst_fa_group_code',
            'label' => 'Group Code',
            'rules' => array(
                'required',
				//'is_unique[glaccounts.fst_glaccount_code.fst_glaccount_code.' . $id . ']'
			),
			'errors' => array(
                'required' => '%s tidak boleh kosong',
				//'is_unique' => '%s unik'
			),
        ];        
        return $rules;
    }

    public function getDataById($finFAGroupId)
    {
        $ssql = "SELECT * FROM msfagroups where fin_fa_group_id = ? and fst_active != 'D'";
        $qr = $this->db->query($ssql, [$finFAGroupId]);
        $rw = $qr->row();

        
        return $rw;
    }

    public function isEditable($finFAGroupId){
        return true;
        //Throw customexception if not editable
    }

    public function getList($active ='A'){
        $ssql = "SELECT * FROM msfagroups where fst_active != 'D'";
        $qr =$this->db->query($ssql,[]);
        return $qr->result();
    }
}