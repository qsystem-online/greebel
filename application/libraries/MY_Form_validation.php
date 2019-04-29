<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {
	protected $CI;

	public function __construct() {
		parent::__construct();
		// reference to the CodeIgniter super object
		$this->CI =& get_instance();
	}


	public function is_unique($str, $field){

		$arr = explode(".", $field);
		if (sizeof($arr) > 2){
			$table = $arr[0];
			$column = $arr[1];
			$key = $arr[2];
			$id = $arr[3];

			$ssql = "select * from $table where $column = ? and $key <> ? limit 1";
			$qr = $this->CI->db->query($ssql,[$str,$id]);
			if($qr->row()){
				return FALSE;
			}
			return TRUE;

		}else{
			sscanf($field, '%[^.].%[^.]', $table, $field);
			return isset($this->CI->db)	? ($this->CI->db->limit(1)->get_where($table, array($field => $str))->num_rows() === 0)	: FALSE;	
		}
		
		
		

		
	}
}