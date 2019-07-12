<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Msrelationprintoutnotes_model extends MY_Model {
    public $tableName = "msrelationprintoutnotes";
    public $pkey = "fin_note_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getDataById($fin_note_id ){
        $ssql = "select * from " . $this->tableName ." where fin_note_id = ? and fst_active = 'A'";
		$qr = $this->db->query($ssql,[$fin_note_id]);
        $rw = $qr->row();
        
		$data = [
            "" => $rw
		];

		return $data;
	}

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_print_out',
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