<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Branch_rpt_model extends CI_Model {

    public $layout1Columns = ['No', 'ID', 'Nama Cabang'];

    public function queryComplete($data, $sorder_by="a.fin_branch_id", $rptLayout="1") {
        
        $branch_id = "";
        $relation_id = "";
        if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
        if (isset($data['fin_relation_id'])) { $relation_id = $data['fin_relation_id'];}
        
        $swhere = "";
        $sorderby = "";
        if ($branch_id > "0") {
            $swhere .= " and a.fin_branch_id = " . $branch_id;
        }
        if ($relation_id > "0") {
            $swhere .= " and a.fin_relation_id = " . $relation_id;
        }
        if ($swhere != "") {
            $swhere = " where " . substr($swhere, 5);
        }
        if ($sorder_by != "") {
            $sorderby = " order by " .$sorder_by;
        }
        
        switch($rptLayout) {
            case "1":
                $ssql = "SELECT a.*,MID(a.fst_area_code, 1, 2) AS province,MID(a.fst_area_code, 1, 5) AS district,MID(a.fst_area_code, 1, 8) AS subdistrict,
                b.fst_nama AS fst_province_name,c.fst_nama AS fst_district_name,d.fst_nama AS fst_subdistrict_name from msbranches a 
                left join msarea b on MID(a.fst_area_code, 1, 2) = b.fst_kode
                left join msarea c on MID(a.fst_area_code, 1, 5) = c.fst_kode
                left join msarea d on MID(a.fst_area_code, 1, 8) = d.fst_kode " . $swhere . $sorderby;
                break;
            default:
                break;
        }

        $query = $this->db->query($ssql);
        // $dataReturn["rows"]=$query->result();
        // $fields = $query->list_fields();
        // $dataReturn["fields"]=$fields;
        return $query->result();
    }

    public function getRules()
    {
        $rules = [];

        $rules[] = [
            'field' => 'fst_kode',
            'label' => 'District Name',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];
        
        return $rules;
    } 

    public function processReport($data) {
        // var_dump($data);die();
        $dataReport = $this->queryComplete($data,"","1");
        // var_dump($recordset);
        // print_r($dataReturn["fields"]);die();
        
        $reportData = $this->parser->parse('reports/branch/rpt',["rows"=>$dataReport['rows']], true);
        // var_dump($reportData);die();
        // return $reportData;
        return $reportData;
        
    }

}