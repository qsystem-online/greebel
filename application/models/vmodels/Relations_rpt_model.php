<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Relations_rpt_model extends CI_Model {

    public $layout1Columns = ['No', 'ID Relasi', 'Nama Relasi'];

    public function queryComplete($data, $sorder_by="a.fst_relation_name", $rptLayout="1") {
        
        $country_id = "";
        $area_code = "";
        $branch_id = "";
        $group_id = "";
        $relation_type = "";
        $lob_id = "";
        $business_type = "";
        $parent_id = "";
        $start_relation_id = "";
        $end_relation_id = "";
        $user = $this->aauth->user();
        $deptActive = $user->fin_department_id;
		$dept_purchase = getDbConfig("purchase_department_id");
		$dept_sales = getDbConfig("sales_department_id");

        if (isset($data['fin_country_id'])) { $country_id = $data['fin_country_id'];}
        if (isset($data['fst_area_code'])) { $area_code = $data['fst_area_code'];}
        if (isset($data['fin_branch_id'])) { $branch_id = $data['fin_branch_id'];}
        if (isset($data['fin_relation_group_id'])) { $group_id = $data['fin_relation_group_id'];}
        if (isset($data['fst_relation_type'])) { $relation_type = $data['fst_relation_type'];}
        if (isset($data['fst_linebusiness_id'])) { $lob_id = $data['fst_linebusiness_id'];}
        if (isset($data['fst_business_type'])) { $business_type = $data['fst_business_type'];}
        if (isset($data['fin_parent_id'])) { $parent_id = $data['fin_parent_id'];}
        if (isset($data['fin_relation_id'])) { $start_relation_id = $data['fin_relation_id'];}
        if (isset($data['fin_relation_id2'])) { $end_relation_id = $data['fin_relation_id2'];}

        $swhere = "";
        $sorderby = "";
        if ($country_id > "0") {
            $swhere .= " and a.fin_country_id = " . $country_id;
        }
        if ($area_code > "0") {
            $swhere .= " and a.fst_area_code like '" . $area_code ."%'";
        }
        if ($branch_id > "0") {
            $swhere .= " and a.fin_branch_id = " . $branch_id;
        }
        if ($group_id > "0") {
            $swhere .= " and a.fin_relation_group_id = " . $group_id;
        }
        if ($relation_type > "0" ) {
            $swhere .= " and a.fst_relation_type in (" . $relation_type.")";
        }
        if ($lob_id > "0") {
            $swhere .= " and a.fst_linebusiness_id in (" . $lob_id .")";
        }
        if ($business_type > "0" ) {
            $swhere .= " and a.fst_business_type = '" . $business_type. "'";
        }
        if ($parent_id > "0") {
            $swhere .= " and a.fin_parent_id = " . $parent_id;
        }
        if ($start_relation_id > "0") {
            $swhere .= " and a.fin_relation_id >= '" . $start_relation_id . "'";            
        }
        if ($end_relation_id > "0") {
            $swhere .= " and a.fin_relation_id <= '". $end_relation_id . "'";
        }

        if($deptActive == $dept_sales){
            if ($swhere != "") {
                $swhere = " WHERE find_in_set('1',a.fst_relation_type) AND " . substr($swhere, 5);
            }else{
                $swhere = " WHERE find_in_set('1',a.fst_relation_type)" . substr($swhere, 5);
            }
		}else if($deptActive == $dept_purchase){
            if ($swhere != "") {
                $swhere = " WHERE (find_in_set('2',a.fst_relation_type) or find_in_set('3',a.fst_relation_type)) AND " . substr($swhere, 5);
            }else{
                $swhere = " WHERE (find_in_set('2',a.fst_relation_type) or find_in_set('3',a.fst_relation_type))" . substr($swhere, 5);
            }
		}else{
            if ($swhere != "") {
                $swhere = " where " . substr($swhere, 5);
            }
		}
        if ($sorder_by != "") {
            $sorderby = " ORDER BY " .$sorder_by;
        }
        
        switch($rptLayout) {
            case "1":
                $ssql = "SELECT a.*,MID(a.fst_area_code, 1, 2) AS provinces,MID(a.fst_area_code, 1, 5) AS district,MID(a.fst_area_code, 1, 8) AS subdistrict,MID(a.fst_area_code, 1, 13) AS village,
                b.fst_country_name,c.fst_nama AS fst_province_name,d.fst_nama AS fst_district_name,e.fst_nama AS fst_subdistrict_name,f.fst_nama AS fst_village_name,g.fst_relation_group_name,
                h.fst_cust_pricing_group_name,i.fst_notes,j.fst_username AS SalesName,k.fst_warehouse_name,l.fst_name,m.fst_branch_name,n.fst_relation_name AS ParentName,o.fst_linebusiness_name 
                FROM msrelations a 
                LEFT JOIN mscountries b ON a.fin_country_id = b.fin_country_id 
                LEFT JOIN msarea c ON MID(a.fst_area_code, 1, 2) = c.fst_kode
                LEFT JOIN msarea d ON MID(a.fst_area_code, 1, 5) = d.fst_kode
                LEFT JOIN msarea e ON MID(a.fst_area_code, 1, 8) = e.fst_kode
                LEFT JOIN msarea f ON MID(a.fst_area_code, 1, 13) = f.fst_kode
                LEFT JOIN msrelationgroups g ON a.fin_relation_group_id = g.fin_relation_group_id
                LEFT JOIN mscustpricinggroups h ON a.fin_cust_pricing_group_id = h.fin_cust_pricing_group_id
                LEFT JOIN msrelationprintoutnotes i ON a.fst_relation_notes = i.fin_note_id
                LEFT JOIN users j ON a.fin_sales_id = j.fin_user_id
                LEFT JOIN mswarehouse k ON a.fin_warehouse_id = k.fin_warehouse_id
                LEFT JOIN mssalesarea l ON a.fin_sales_area_id = l.fin_sales_area_id
                LEFT JOIN msbranches m ON a.fin_branch_id = m.fin_branch_id
                LEFT JOIN msrelations n ON a.fin_parent_id = n.fin_relation_id
                LEFT JOIN mslinebusiness o ON a.fst_linebusiness_id = o.fin_linebusiness_id " . $swhere . $sorderby;
                break;
            case "2":
                $ssql = "SELECT a.*,MID(a.fst_area_code, 1, 2) AS provinces,MID(a.fst_area_code, 1, 5) AS district,MID(a.fst_area_code, 1, 8) AS subdistrict,MID(a.fst_area_code, 1, 13) AS village,
                b.fst_country_name,c.fst_nama AS fst_province_name,d.fst_nama AS fst_district_name,e.fst_nama AS fst_subdistrict_name,f.fst_nama AS fst_village_name,g.fst_relation_group_name,
                h.fst_cust_pricing_group_name,i.fst_notes,j.fst_username AS SalesName,k.fst_warehouse_name,l.fst_name,m.fst_branch_name,n.fst_relation_name AS ParentName,o.fst_linebusiness_name,
                p.fst_name,p.fst_area_code,p.fst_shipping_address,MID(p.fst_area_code, 1, 2) AS provinceShipp,MID(p.fst_area_code, 1, 5) AS districtShipp,MID(p.fst_area_code, 1, 8) AS subdistrictShipp,
                MID(p.fst_area_code, 1, 13) AS villageShipp,q.fst_nama AS fst_province_nameShipp,r.fst_nama AS fst_district_nameShipp,s.fst_nama AS fst_subdistrict_nameShipp,t.fst_nama AS fst_village_nameShipp 
                FROM msrelations a 
                LEFT JOIN mscountries b ON a.fin_country_id = b.fin_country_id 
                LEFT JOIN msarea c ON MID(a.fst_area_code, 1, 2) = c.fst_kode
                LEFT JOIN msarea d ON MID(a.fst_area_code, 1, 5) = d.fst_kode
                LEFT JOIN msarea e ON MID(a.fst_area_code, 1, 8) = e.fst_kode
                LEFT JOIN msarea f ON MID(a.fst_area_code, 1, 13) = f.fst_kode
                LEFT JOIN msrelationgroups g ON a.fin_relation_group_id = g.fin_relation_group_id
                LEFT JOIN mscustpricinggroups h ON a.fin_cust_pricing_group_id = h.fin_cust_pricing_group_id
                LEFT JOIN msrelationprintoutnotes i ON a.fst_relation_notes = i.fin_note_id
                LEFT JOIN users j ON a.fin_sales_id = j.fin_user_id
                LEFT JOIN mswarehouse k ON a.fin_warehouse_id = k.fin_warehouse_id
                LEFT JOIN mssalesarea l ON a.fin_sales_area_id = l.fin_sales_area_id
                LEFT JOIN msbranches m ON a.fin_branch_id = m.fin_branch_id
                LEFT JOIN msrelations n ON a.fin_parent_id = n.fin_relation_id
                LEFT JOIN mslinebusiness o ON a.fst_linebusiness_id = o.fin_linebusiness_id
                LEFT JOIN msshippingaddress p ON a.fin_relation_id = p.fin_relation_id
                LEFT JOIN msarea q ON MID(p.fst_area_code, 1, 2) = q.fst_kode
                LEFT JOIN msarea r ON MID(p.fst_area_code, 1, 5) = r.fst_kode
                LEFT JOIN msarea s ON MID(p.fst_area_code, 1, 8) = s.fst_kode
                LEFT JOIN msarea t ON MID(p.fst_area_code, 1, 13) = t.fst_kode " . $swhere . $sorderby;
                break;
            default:
                break;
        }
        
        $query = $this->db->query($ssql);
        //echo $this->db->last_query();
        //die();
        return $query->result();
    }

    public function getRules()
    {
        $rules = [];

        $rules[] = [
            'field' => 'fin_country_id',
            'label' => 'Country',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s tidak boleh kosong'
            )
        ];
        
        return $rules;
    }   

    public function processReport($data) {
        // var_dump($data);die();
        //$data['fin_warehouse_id'], $data["fin_sales_order_datetime"], $data["fin_sales_order_datetime2"], $data["fin_relation_id"], $data['fin_sales_id']
        $dataReport = $this->queryComplete($data,"","1");
        // var_dump($recordset);
        // print_r($dataReturn["fields"]);die();
        
        // if (isset($this->$data['rows'])) {
        //     $reportData = $this->parser->parse('reports/sales_order/rpt',$this->$data["rows"], true);
        // } else {
        //     $reportData = $this->parser->parse('reports/sales_order/rpt',[], true);
        // }
        $reportData = $this->parser->parse('reports/sales_order/rpt',["rows"=>$dataReport['rows']], true);
        // var_dump($reportData);die();
        // return $reportData;
        return $reportData;
        
    }

}