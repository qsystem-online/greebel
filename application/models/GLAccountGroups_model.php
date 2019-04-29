<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class GLAccountGroups_model extends MY_Model {
    public $tableName = "glaccountgroups";
    public $pkey = "GLAccountGroupId";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($GLAccountGroupId ){
        $ssql = "select * from " . $this->tableName ." where GLAccountGroupId = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$GLAccountGroupId]);
        $rw = $qr->row();
        
		$data = [
            "" => $rw
		];

		return $data;
	}

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'GLAccountGroupName',
            'label' => 'GL Account Group Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        return $rules;
    }
}