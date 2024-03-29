<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mspromo_model extends MY_Model
{
    public $tableName = "mspromo";
    public $pkey = "fin_promo_id";

    public function __construct()
    {
        parent::__construct();
    }

    public function getDataById($fin_promo_id)
    {
        $ssql = "select a.*,b.fst_item_name,c.fst_branch_name from " . $this->tableName . " a 
        left join msitems b on a.fin_promo_item_id = b.fin_item_id
        left join msbranches c on a.fst_list_branch_id = c.fin_branch_id  
        where a.fin_promo_id = ? and a.fst_active = 'A'";
        $qr = $this->db->query($ssql, [$fin_promo_id]);
        $rwPromo = $qr->row_array();

        $ssql = "SELECT a.*,IF (a.fst_item_type ='ITEM',b.fst_item_name,c.fst_item_group_name) AS ItemTerms FROM mspromoitems a 
        LEFT JOIN msitems b ON a.fin_item_id = b.fin_item_id
        LEFT JOIN msgroupitems c ON a.fin_item_id = c.fin_item_group_id 
        WHERE a.fin_promo_id = ?";
        $qr = $this->db->query($ssql, [$fin_promo_id]);
        $rsPromoTerms = $qr->result_array();

        $ssql = "SELECT a.*, IF (a.fst_participant_type ='RELATION',b.fst_relation_name, IF(a.fst_participant_type ='RELATION GROUP',c.fst_relation_group_name,d.fst_member_group_name)) AS ParticipantName FROM mspromoitemscustomer a 
        LEFT JOIN msrelations b ON a.fin_customer_id = b.fin_relation_id
        LEFT JOIN msrelationgroups c ON a.fin_customer_id = c.fin_relation_group_id
        LEFT JOIN msmembergroups d ON a.fin_customer_id = d.fin_member_group_id  
        WHERE a.fin_promo_id = ?";
        $qr = $this->db->query($ssql, [$fin_promo_id]);
        $rsPromoParticipants = $qr->result_array();

        $ssql = "SELECT a.*,MID(a.fst_kode_area, 1, 2) AS provincePromo,MID(a.fst_kode_area, 1, 5) AS districtPromo,MID(a.fst_kode_area, 1, 8) AS subdistrictPromo,MID(a.fst_kode_area, 1, 13) AS villagePromo,
        b.fst_nama AS fst_province_name,c.fst_nama AS fst_district_name,d.fst_nama AS fst_subdistrict_name,e.fst_nama AS fst_village_name FROM mspromoareas a
        LEFT JOIN msarea b ON MID(a.fst_kode_area, 1, 2) = b.fst_kode
        LEFT JOIN msarea c ON MID(a.fst_kode_area, 1, 5) = c.fst_kode
        LEFT JOIN msarea d ON MID(a.fst_kode_area, 1, 8) = d.fst_kode
        LEFT JOIN msarea e ON MID(a.fst_kode_area, 1, 13) = e.fst_kode
        WHERE a.fin_promo_id = ? and a.fst_active = 'A' ";
        $qr = $this->db->query($ssql, [$fin_promo_id]);
        $rsPromoParticipantArea = $qr->result();

        $ssql = "SELECT a.*,b.fst_relation_name AS ParticipantRestric_Name FROM mspromocustomerrestric a LEFT JOIN msrelations b on a.fin_customer_id = b.fin_relation_id WHERE a.fin_promo_id = ?";
        $qr = $this->db->query($ssql, [$fin_promo_id]);
        $rsPromoParticipantsRestric = $qr->result_array();

        $ssql = "SELECT a.*,b.fst_item_name FROM mspromodiscperitems a LEFT JOIN msitems b on a.fin_item_id = b.fin_item_id WHERE a.fin_promo_id = ? AND a.fst_active = 'A'";
        $qr = $this->db->query($ssql, [$fin_promo_id]);
        $rwPromodiscItems = $qr->result_array();

        $ssql = "SELECT a.*,b.fst_item_name AS FreeItem FROM mspromoprizes a LEFT JOIN msitems b ON a.fin_item_id = b.fin_item_id WHERE a.fin_promo_id = ?";
        $qr = $this->db->query($ssql, [$fin_promo_id]);
        $rsFreeItems = $qr->result_array();

        $data = [
            "mspromo" => $rwPromo,
            "promoTerms" => $rsPromoTerms,
            "promoParticipants" => $rsPromoParticipants,
            "promoparticipantsarea" => $rsPromoParticipantArea,
            "promoParticipantsRestric" => $rsPromoParticipantsRestric,
            "promodiscItems" => $rwPromodiscItems,
            "freeItems" => $rsFreeItems
        ];

        return $data;
    }

    public function getRules($mode = "ADD", $id = 0)
    {
        $rules = [];

        $rules[] = [
            'field' => 'fst_promo_name',
            'label' => 'Promo Name',
            'rules' => 'required|min_length[5]',
            'errors' => array(
                'required' => '%s tidak boleh kosong',
                'min_length' => 'Panjang %s paling sedikit 5 character'
            )
        ];

        $rules[] = [
            'field' => 'fst_unit_gabungan',
            'label' => 'Unit',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s Required'
            )
        ];
        $rules[] = [
            'field' => 'fst_list_branch_id',
            'label' => 'Branch',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s Required'
            )
        ];

        $rules[] = [
            'field' => 'fdt_start',
            'label' => 'Periode Awal',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s Required'
            )
        ];

        $rules[] = [
            'field' => 'fdt_end',
            'label' => 'Periode Akhir',
            'rules' => 'required',
            'errors' => array(
                'required' => '%s Required'
            )
        ];
        

        return $rules;
    }

    public function getAllList()
    {
        $ssql = "select fin_promo_id,fst_promo_name from " . $this->tableName . " where fst_active = 'A' order by fst_promo_name";
        $qr = $this->db->query($ssql, []);
        $rs = $qr->result();
        return $rs;
    }

    public function getDiscItem($finCustomerId,$finItemId,$fstUnit,$fdbQty,$trxDate = null){
        if ($trxDate == null){
            $trxDate = date("Y-m-d 23:59:59");
        }else{
            $trxDate = dBDateTimeFormat($trxDate);
        }

        $ssql = "SELECT * FROM mspromodiscperitems a 
            INNER JOIN mspromo b on a.fin_promo_id = b.fin_promo_id 
            WHERE a.fin_item_id = ? and a.fst_unit = ? and a.fin_qty < ?
            and ? between b.fdt_start and b.fdt_end
            AND b.fst_active = 'A'";

        

    }


    public function processPromoPeriod($finPromoId){

        $this->load->model("msitems_model");

        $ssql = "SELECT * FROM mspromo WHERE fin_promo_id = ? and fst_active ='A'";
        $qr = $this->db->query($ssql,[$finPromoId]);
        $rwPromo =  $qr->row();
        if ($rwPromo == null){
            throw new customException("Invalid Promo ID !",3003,"FAILED",[]);
        }

        $start = $rwPromo->fdt_start;
        $end = $rwPromo->fdt_end;

        $arrMember = [];

        //Clear Data member achived
        $ssql ="DELETE FROM mspromoperiodclientachived WHERE fin_promo_id = ?";
        $this->db->query($ssql,[$finPromoId]);


        //Get All Member
        // Get on Area
        $ssql = "SELECT * FROM mspromoareas where fin_promo_id = ? and fst_active = 'A'";
        $qr = $this->db->query($ssql,[$finPromoId]);
        $rs = $qr->result();
        foreach($rs as $area){
            $kodeArea = $area->fst_kode_area;
            $ssql = "SELECT * FROM msrelations a 
                LEFT JOIN (SELECT * FROM mspromocustomerrestric where fin_promo_id = ? AND fst_active ='A') b on a.fin_relation_id = b.fin_customer_id
                WHERE a.fst_area_code like ? and b.fin_rec_id IS NULL 
                AND a.fst_active = 'A'";
            $qr = $this->db->query($ssql,[$finPromoId,$kodeArea ."%"]);
            $rsCust = $qr->result();
            foreach($rsCust as $cust){
                $arrMember[$cust->fin_relation_id] = false; 
            }
        }

        //Get On Customer
        $ssql = "SELECT * FROM mspromoitemscustomer a
            LEFT JOIN (SELECT * FROM mspromocustomerrestric where fin_promo_id = ? AND fst_active ='A') b on a.fin_customer_id = b.fin_customer_id
            WHERE a.fst_participant_type ='RELATION' 
            AND a.fst_active ='A' and b.fin_rec_id is null";

        $qr = $this->db->query($ssql,[$finPromoId]);
        $rsParticipants = $qr->result();
        foreach($rsParticipants as $cust){
            if (! isset($arrMember[$cust->fin_customer_id])){
                $arrMember[$cust->fin_customer_id] = false; 
            }            
        }

        //Cek Term
        $ssql = "SELECT * FROM mspromoitems where fin_promo_id = ? and fst_active ='A'";
        $qr = $this->db->query($ssql,[$finPromoId]);
        $rsTerm = $qr->result();
        foreach($arrMember as $keyCustId => $value){
            foreach($rsTerm as $term){
                if ($term->fst_item_type == "ITEM"){
                    //Get Purchase
                    $ssql = "SELECT a.* FROM trsalesorderdetails a 
                        inner join trsalesorder b on a.fin_salesorder_id = b.fin_salesorder_id 
                        WHERE b.fst_active ='A' and b.fin_relation_id = ? 
                        AND a.fin_item_id = ? and b.fdt_salesorder_datetime between ? and ?";
                    $qr = $this->db->query($ssql,[$keyCustId,$term->fin_item_id,$start,$end]);
                    $rsTmp = $qr->result();
                    $totalBaseUnit = 0;
                    $termUnit = $term->fst_unit;

                    foreach($rsTmp as $tmp){
                        $totalBaseUnit += $this->msitems_model->getQtyConvertUnit($term->fin_item_id,$tmp->fdb_qty,$tmp->fst_unit,$termUnit);                        
                    }

                    if ($totalBaseUnit < $term->fdb_qty){
                        continue 2;
                    }
                    $arrMember[$keyCustId] = true;
                }                
            }
        }

        foreach($arrMember as $keyCustId => $value){
            if ($value == true){
                $ssql = "INSERT INTO mspromoperiodclientachived (fin_promo_id,fin_customer_id,fst_active,fin_insert_id) values(?,?,?,?)";
                $this->db->query($ssql,[$finPromoId,$keyCustId,'A',$this->aauth->get_user_id()]);
            }
        }        


    }

}
