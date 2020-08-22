<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Longprocess extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->library('session');
    }

    
    public function index(){
        $this->load->view('test/longprocess');
    }
    
    public function process(){
        
        for($i=0;$i<10;$i++){
            $this->session->set_userdata("progress",$i);
            session_write_close();
            sleep(1);
        }
    }

    public function progress(){
        $progress = $this->session->userdata("progress");
        echo "Still Process $progress  - " . date("Y-m-d H:i:s");
    }

    public function test(){
        $progress = 0;
        if ($this->session->userdata("progress") != null){
            $progress = $this->session->userdata("progress");
            $progress +=1;
            $this->session->set_userdata("progress",$progress);
            session_write_close();
        }

        echo "Session : $progress" ;
    }


}


