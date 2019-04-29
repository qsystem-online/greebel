<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class MSRelationprintoutnotes_model extends MY_Model {
    public $tableName = "msrelationprintoutnotes";
    public $pkey = "RelationNoteId";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($RelationNoteId ){
        $ssql = "select * from " . $this->tableName ." where RelationNoteId = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$RelationNoteId]);
        $rw = $qr->row();
        
		$data = [
            "" => $rw
		];

		return $data;
	}

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'PrintOut',
            'label' => 'Print Out',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        return $rules;
    }
}