<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Msactivityborongandetails_model extends MY_Model
{
    public $tableName = "msactivityborongandetails";
    public $pkey = "fin_rec_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fin_rec_id)
    {
        $ssql = "SELECT * FROM " . $this->tableName . " where fin_rec_id = ? ";
        $qr = $this->db->query($ssql, [$fin_rec_id]);
        $rw = $qr->row();

        $data = [
            "" => $rw
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fin_item_id',
            'label' => 'Item ID',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        return $rules;
    }
    public function deleteByHeaderId($fin_activity_id)
    {
        $ssql = "DELETE FROM " . $this->tableName . " where fin_activity_id = $fin_activity_id";
        $this->db->query($ssql);
    }
}
