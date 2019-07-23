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
        function dBDateTimeFormat($strDate,$inputFormat){
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

