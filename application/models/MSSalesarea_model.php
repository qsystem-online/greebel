<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class MSSalesarea_model extends MY_Model {
    public $tableName = "mssalesarea";
    public $pkey = "fin_sales_area_id";

    public function __construct() {
        parent::__construct();
    }

    public function getDataById($fin_sales_area_id){
        $ssql = "SELECT a.*,b.fst_name as RegionalName,c.fst_username as SalesName FROM mssalesarea a 
        left join mssalesregional b on a.fin_sales_regional_id = b.fin_sales_regional_id
        left join users c on a.fin_sales_id = c.fin_user_id 
        WHERE a.fin_sales_area_id = ? ORDER BY fin_sales_area_id";
        $qr = $this->db->query($ssql, [$fin_sales_area_id]);
        $rwSalesarea = $qr->row();

        $data = [
            "sales_area_area" => $rwSalesarea
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0) {
        $rules = [];

        $rules[] = [
            'field' => 'fst_name',
            'label' => 'Name',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            ),
        ];

        return $rules;
    }
}