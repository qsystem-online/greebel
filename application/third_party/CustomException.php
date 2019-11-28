<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class CustomException extends Exception{

    public $status = "";
    public $data = [];

	public function __construct($message,$code,$status="",$data=[]){        
        parent::__construct($message,$code);
        $this->status = $status;
        $this->data = $data;
	}
	
	public function getData(){
		return $this->data;
    }

    public function getStatus(){
        return $this->status;
    }
}
