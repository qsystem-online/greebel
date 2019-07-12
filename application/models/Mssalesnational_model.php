<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mssalesnational_model extends MY_Model {
    public $tableName = "mssalesnational";
    public $pkey = "fin_sales_national_id";

    public function __construct() {
        parent::__construct();
    }

    public function getDataById($fin_sales_national_id){
        $ssql = "SELECT a.*,b.fst_username as SalesName FROM mssalesnational a left join users b on a.fin_sales_id = b.fin_user_id 
        WHERE a.fin_sales_national_id = ? ORDER BY fin_sales_national_id";
        $qr = $this->db->query($ssql, [$fin_sales_national_id]);
        $rwSalesnational = $qr->row();

        $data = [
            "sales_area_national" => $rwSalesnational
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0) {
        $rules = [];

        $rules[] = [
            'field' => 'fst_name',
            'label' => 'Name',
            'rules' => 'required|min_length[3]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 3 character'
            ),
        ];

        return $rules;
    }
}