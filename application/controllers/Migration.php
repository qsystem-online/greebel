<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration extends CI_Controller {
	public function master_item(){
        //phpinfo();
        //die();
        $dbmssql = $this->load->database("dbsqlsrv",true);

        $ssql = "select * from tbItems";

        $qr = $dbmssql->query($ssql,[]);

        $rs = $qr->result();
        print_r($rs);

	}
	
}