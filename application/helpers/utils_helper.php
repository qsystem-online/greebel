<?php
    if (!function_exists('dateFormat')){
        function dateFormat($strDate,$inputFormat,$outputFormat = 'Y-m-d H:i:s'){
            $date = DateTime::createFromFormat($inputFormat, $strDate);
            if($date){
                return $date->format($outputFormat);
            }else{
                return date($outputFormat,0);
            }
        }
    }
    if (!function_exists('dBDateFormat')){
        function dBDateFormat($strDate,$inputFormat=''){
            $inputFormat = ($inputFormat == '' ) ? DATEPICKER_FORMAT_ALIAS : $inputFormat;

            $date = DateTime::createFromFormat($inputFormat, $strDate);
            if($date){
                return $date->format('Y-m-d');
            }else{
                return date('Y-m-d',0);
            }
        }
    }
    if (!function_exists('dBDateTimeFormat')){
        function dBDateTimeFormat($strDate,$inputFormat =''){
            $inputFormat = ($inputFormat == '' ) ? DATEPICKER_FORMAT_ALIAS . " H:i:s" : $inputFormat;
            
            $date = DateTime::createFromFormat($inputFormat, $strDate);
            if($date){
                return $date->format('Y-m-d H:i:s');
            }else{
                return date('Y-m-d H:i:s',0);
            }
        }
    }

    if (!function_exists('parseNumber')){
        function parseNumber($strNumber,$commaSeparators=''){
            
            $commaSeparators = ($commaSeparators == '') ? DECIMAL_SIGN : $commaSeparators;
            $thousandsSeparators =  ($commaSeparators == ".") ? "," : ".";            
            $strNumber = str_replace($thousandsSeparators,"",$strNumber);
            $strNumber =  ($commaSeparators == ",") ? str_replace(",",".",$strNumber) : $strNumber ;
            return (float) $strNumber;
        }
    }

    if (!function_exists('formatNumber')){
        function formatNumber($number,$digitComma = 0,$commaSeparators=''){
            $commaSeparators = ($commaSeparators == '') ? DECIMAL_SIGN : $commaSeparators;
            $thousandsSeparators =  ($commaSeparators == ".") ? "," : ".";            
            return number_format($number,$digitComma,$commaSeparators,$thousandsSeparators);
        }
    }
    if (!function_exists('getDbConfig')){
        function getDbConfig($key){
            $CI = & get_instance();
            $ssql ="select fst_value from config where fst_key = ? and fbl_active = true";
            $qr = $CI->db->query($ssql,[$key]);
            $rw = $qr->row();
            if ($rw){
                return $rw->fst_value;
            }
            return null;
        }
    }

    if(!function_exists('getGLConfig')){
        function getGLConfig($key){
            $CI = & get_instance();
            $ssql ="select fst_glaccount_code from msconfigjurnal where fst_key = ? and fbl_active = true";
            $qr = $CI->db->query($ssql,[$key]);
            $rw = $qr->row();
            if ($rw){
                return $rw->fst_glaccount_code;
            }
            return null;
        }
    }

    if (!function_exists('getDefaultCurrency')){
        function getDefaultCurrency(){
            return [
                "CurrCode"=>"IDR",
                "CurrName"=>"Rupiah"
            ];
        }
    }

    if (!function_exists('calculateDisc')){
        function calculateDisc($strDisc,$amount){
            $arrDisc = explode("+",$strDisc);            
            $totalDisc = 0;
            foreach($arrDisc as $disc){
                $discAmount = $amount * ($disc/100);
                $totalDisc +=  $discAmount;
                $amount = $amount - $discAmount;
            }
            return $totalDisc;
        }
    }

    if (!function_exists('lastQuery')){
        function lastQuery(){
            $CI = &get_instance();
            echo $CI->db->last_query();
            die();
        }
    }

    if (!function_exists('exist_array_replace')){
        //merubah isi nilai array 1 sesuai dengan array2
        function exist_array_replace($arr1,$arr2){
            foreach($arr1 as $k => $v){
                if ( isset($arr2[$k])){
                    $arr1[$k] = $arr2[$k];
                }
            }
            return $arr1;
        }
    }

    if (!function_exists('add_key_array_object')){
        function add_key_array_object($arr,$key){
            $tmpArray = [];
            foreach($arr as $obj){
                $tmpArray[$obj->$key] = $obj;
            }
            return $tmpArray;
        }
    }

    function add_date($tgl,$days){
        $tsTgl = strtotime($tgl. ' + ' .$days.' days');
        return date('Y-m-d', $tsTgl); 
    }

    function getDataTable($table,$fields="*",$where=""){
        $ci =& get_instance();
        $ci->db->select($fields);
        $ci->db->where($where);
        $query = $ci->db->get($table);
        return $query->result();
    }

    function getAutoNumber($prefixKey,$table,$field,$trDate=null){
        $trDate = ($trDate == null) ? date ("Y-m-d"): $trDate;
        $tahun = date("Y/m", strtotime ($trDate));

        $activeBranch = $this->aauth->get_active_branch();

        $branchCode = "";

        if($activeBranch){
            $branchCode = $activeBranch->fst_branch_code;
        }


        //$prefix = getDbConfig($prefixKey) . "/" . $branchCode ."/";
        $prefix = getDbConfig($prefixKey);


        //$query = $this->db->query("SELECT MAX(fst_po_no) as max_id FROM $table where $field like '".$prefix.$tahun."%'");
        $query = $this->db->query("SELECT MAX($field) as max_id FROM $table where $field like '".$prefix."/%/".$tahun."%'");

        $row = $query->row_array();

        $max_id = $row['max_id']; 
        
        $max_id1 =(int) substr($max_id,strlen($max_id)-5);
        
        $fst_tr_no = $max_id1 +1;
        
        $max_tr_no = $prefix.''.$tahun.'/'.sprintf("%05s",$fst_tr_no);
        
        return $max_tr_no;

    }

    function dateIsLock($tgl){
        //'11', 'lock_transaction_date', '2019-05-01', 'Setiap transaksi dibawah tgl lock tidak dapat ditambah, rubah ataupun di hapus', '1'
        $lockDate = getDbConfig("lock_transaction_date");
        $lockDate = strtotime($lockDate);
        $tgl = strtotime($tgl);
        if ($tgl < $lockDate){
            return [
                "status"=>"VALIDATION_FORM_FAILED",
                "message"=>"Transaction date is locked ! "
            ];
        }else{
            return [
                "status"=>"SUCCESS",
                "message"=>""
            ];
        }
    }


    function error_handle($errno, $errstr, $errfile, $errline){
        echo "Error No :$errno <br>";
        echo "Error message :$errstr <br>";
        echo "File Name :$errfile <br>";
        echo "Line :$errline <br>";
        die();
    }
    
    function parseDateRange($dateRange){
        if ($dateRange == null){
            return [
                "from"=>"2019-01-01",
                "to"=>date("Y-m-d")
            ];
        }
        $arr = explode(" - ",$dateRange);
        return [
            "from"=>dBDateFormat($arr[0]),
            "to"=>dBDateFormat($arr[1]),
        ];
    }
