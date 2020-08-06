<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Gltrjournal_model extends MY_Model {
    public $tableName = "gltrjournal";
    public $pkey = "fin_journal_id";

    public function __construct(){
        parent:: __construct();
    }

    public function getRules($mode="ADD",$id=0){
        $rules = [];

        $rules[] = [
            'field' => 'fst_journal_no',
            'label' => 'No Jurnal',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
            )
        ];


        return $rules;
    }

    public function generateTransNo($fstJournalType, $trDate = null) {

        $trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
        $tahun = date("Y/m", strtotime ($trDate));
        $activeBranch = $this->aauth->get_active_branch();
        $branchCode = "";
        if($activeBranch){
            $branchCode = $activeBranch->fst_branch_code;
        }        
        $prefix =$fstJournalType;

        $query = $this->db->query("SELECT MAX(fst_journal_no) as max_id FROM gltrjournal where fst_journal_no like '".$prefix."/%/".$tahun."%'");

        $row = $query->row_array();

        $max_id = $row['max_id']; 
        
        $max_id1 =(int) substr($max_id,strlen($max_id)-5);
        
        $fst_tr_no = $max_id1 +1;
        
        $max_tr_no = $prefix."/". $branchCode .'/' .$tahun.'/'.sprintf("%05s",$fst_tr_no);
        
        return $max_tr_no;
    }


    public function posting($finJournalId){
        $this->load->model("glledger_model");

        $ssql ="select * from gltrjournal where fin_journal_id = ?";        
        $qr = $this->db->query($ssql,[$finJournalId]);        
        $dataH = $qr->row();

        //get Detail Transaksi
        $ssql ="select * from gltrjournalitems where fin_journal_id = ?";        
        $qr = $this->db->query($ssql,[$finJournalId]);        
        $dataDetails = $qr->result();              
        $dataJurnal = [];
        foreach($dataDetails as $dataD){                
            $dataJurnal[] = [
                "fin_branch_id"=>$dataH->fin_branch_id,
                "fst_account_code"=>$dataD->fst_glaccount_code,
                "fdt_trx_datetime"=>$dataH->fdt_journal_datetime,
                "fst_trx_sourcecode"=>"JM", //Jurnal Manual
                "fin_trx_id"=>$dataH->fin_journal_id,
                "fst_trx_no"=>$dataH->fst_journal_no,
                "fst_reference"=>$dataD->fst_reference,
                "fdc_debit"=>$dataD->fdc_debit * $dataH->fdc_exchange_rate_idr,
                "fdc_origin_debit"=>$dataD->fdc_debit,
                "fdc_credit"=>$dataD->fdc_credit * $dataH->fdc_exchange_rate_idr,
                "fdc_origin_credit"=>$dataD->fdc_credit,
                "fst_orgi_curr_code"=>$dataH->fst_curr_code,
                "fdc_orgi_rate"=>$dataH->fdc_exchange_rate_idr,
                "fst_no_ref_bank"=>null,
                "fin_pcc_id"=>$dataD->fin_pcc_id,
                "fin_pc_divisi_id"=>$dataD->fin_pc_divisi_id,
                "fin_pc_customer_id"=>$dataD->fin_pc_customer_id,
                "fin_pc_project_id"=>$dataD->fin_pc_project_id,
                "fin_relation_id"=>$dataD->fin_relation_id,
                "fst_active"=>"A"
            ];  
        }
        $result = $this->glledger_model->createJurnal($dataJurnal);            
        return $result;
       
    }

    public function unposting($finJournalId,$unpostingDateTime =""){
        $this->load->model("glledger_model");
        $unpostingDateTime = $unpostingDateTime == "" ? date("Y-m-d H:i:s") : $unpostingDateTime;
        $this->glledger_model->cancelJurnal("JM",$finJournalId,$unpostingDateTime);
    }

    public function getDataById($finJournalId){
        $ssql = "select a.*,b.fst_curr_name from gltrjournal a
            INNER JOIN mscurrencies b on a.fst_curr_code = b.fst_curr_code 
            where a.fin_journal_id = ? and a.fst_active != 'D'";

        $qr = $this->db->query($ssql, [$finJournalId]);
        $dataH = $qr->row();

        if (!$dataH){
            return [
                "trJournal"=>null,
                "trJournalItems"=>null
            ];
        }

        $ssql = "SELECT a.*,b.fst_glaccount_name,c.fst_pcc_name,d.fst_department_name,e.fst_relation_name as fst_pcc_customer_name,f.fst_project_name,g.fst_relation_name FROM gltrjournalitems a 
            INNER JOIN glaccounts b on a.fst_glaccount_code = b.fst_glaccount_code 
            LEFT JOIN msprofitcostcenter c on a.fin_pcc_id = c.fin_pcc_id
            LEFT JOIN departments d on a.fin_pc_divisi_id = d.fin_department_id
            LEFT JOIN msrelations e on a.fin_pc_customer_id = e.fin_relation_id
            LEFT JOIN msprojects f on a.fin_pc_project_id = f.fin_project_id       
            LEFT JOIN msrelations g on a.fin_relation_id = g.fin_relation_id
            WHERE a.fin_journal_id = ? and a.fst_active != 'D'";

        $qr = $this->db->query($ssql,[$finJournalId]);        
        $dataDetails = $qr->result();

        $data = [
            "trJournal" => $dataH,
            "trJournalItems" => $dataDetails
		];

		return $data;
    }

    public function getDataHeader($finJournalId){
        $ssql = "SELECT * FROM gltrjournal where fin_journal_id = ? and fst_active != 'D'";
        $qr = $this->db->query($ssql,[$finJournalId]);
        return $qr->row();        
    }

    public function deleteDetail($finJournalId){
        $ssql = "DELETE FROM gltrjournalitems where fin_journal_id =?";
        $this->db->query($ssql,[$finJournalId]);

    }

    public function isEditable($finCBPaymentId){
        /**
         * 
         */
        //$ssql = "select ";

        return ["status"=>"SUCCESS","message"=>""];
    }
    public function delete($finJournalId,$softDelete=TRUE,$data=null){
        
        if ($softDelete){
            $ssql = "UPDATE gltrjournalitems set fst_active ='D' where fin_journal_id = ?";
        }else{
            $ssql = "DELETE from gltrjournalitems  where fin_journal_id = ?";            
        }

        $this->db->query($ssql,[$finJournalId]);        
        throwIfDBError();
        parent::delete($finJournalId,$softDelete,$data);        
        throwIfDBError();


        return ["status"=>"SUCCESS","message"=>""];
    }

    public function getDataVoucher($finJournalId){
        $data = $this->getDataById($finJournalId);
        return [
            "header"=>(array) $data["trJournal"],
            "details"=>(array) $data["trJournalItems"]
        ];
    }
}


