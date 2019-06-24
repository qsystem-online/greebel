<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class MSCurrenciesratedetails_model extends MY_Model {
    public $tableName = "mscurrenciesratedetails";
    public $pkey = "recid";

    public function __construct(){
        parent:: __construct();
    }

    public function deleteByDetail($CurrCode){
		$ssql = "delete from " . $this->tableName  . " where CurrCode = ?";
		$this->db->query($ssql,[$CurrCode]);
	}

    public function getRules($mode="ADD",$id=0){
        $rules = [];

       /*$rules[] = [
            'field' => 'CurrCode',
            'label' => 'Currencies Code',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];*/

        $rules[] =[
			'field' => 'ExchangeRate2IDR',
			'label' => 'IDR',
			'rules' => 'required|numeric|greater_than[0]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'numeric' => '%s harus berupa angka',
                'greater_than' => '%s tidak boleh 0',
			)
		];

        return $rules;
    }
}