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
			if ($strDate == null || $strDate == ""){
				return null;
			}

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
			if ($strDate == null || $strDate == ""){
				return null;
			}

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
	if(!function_exists('getLogisticGLConfig')){
		function getLogisticGLConfig($finItemGroupId,$mode){
			$CI = & get_instance();
			
			
			$ssql = "SELECT a.* FROM msconfiglogisticjurnal a 
			INNER JOIN msgroupitems b ON b.fin_item_group_id = a.fin_item_group_id
			INNER JOIN msgroupitems c ON c.fst_tree_id LIKE CONCAT(b.fst_tree_id,'%') 
			WHERE c.fin_item_group_id = ?";
			

			$qr = $CI->db->query($ssql,[$finItemGroupId]);
			$rw = $qr->row();
			if ($rw){
				if ($mode == "PERSEDIAAN"){
					return $rw->fst_persediaan_account_code;
				}else if ($mode == "BIAYA_UMUM"){
					return $rw->fst_biaya_umum_account_code;
				}else if ($mode == "BIAYA_PABRIKASI"){
					return $rw->fst_biaya_pabrikasi_account_code;
				}                
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

			if ($strDisc == null || $strDisc ==""){
				$strDisc = "100";
			}
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
		if ($where != ""){
			$ci->db->where($where);
		}        
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
				"message"=>sprintf(lang("Tanggal transaksi telah dikunci %s"),gmdate(DATEPICKER_FORMAT_ALIAS,$lockDate)),
				"data"=>[
					"lock_date"=>date("Y-m-d",$lockDate),
					"compare_date"=>date("Y-m-d",$tgl),
				]
			];
		}else{
			return [
				"status"=>"SUCCESS",
				"message"=>"",
				"data"=>[
					"lock_date"=>date("Y-m-d",$lockDate),
					"compare_date"=>date("Y-m-d",$tgl),
				]
			];
		}
	}

	function dateBeforeSystem($tgl){
		$startDate = getDbConfig("start_program");
		$startDate = strtotime($startDate);	
		$tgl = strtotime($tgl);
		if ($tgl < $startDate){
			return true;
		}else{
			return false;
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
				"to"=>date("Y-m-d") ." 23:59:59"
			];
		}
		$arr = explode(" - ",$dateRange);
		return [
			"from"=>dBDateFormat($arr[0]),
			"to"=>dBDateFormat($arr[1]) . " 23:59:59",
		];
	}

	function startWith($sstr,$prefix){
		$patern = $prefix;
		$patern = str_replace(".","\.",$patern);
		//$sstr = "23.1.1123123.1";
		$cek = preg_match('/^'.$patern.'/',$sstr);
		if ($cek > 0 ){
			return true;
		}else{
			return false;
		}
	}
	function throwIfDBError($db = null){
		$CI = & get_instance();
		$CI->load->model("mY_model","my_model");
		$CI->my_model->throwIfDBError($db);

	}
	
	function dd($object){
		var_dump($object);
		die();
	}


	function getMpdfSetting(){
		$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
		$fontDirs = $defaultConfig['fontDir'];
		
		$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
		$fontData = $defaultFontConfig['fontdata'];	

		return [
			"format"=>[215.9,139.7],
			"margin_left"=>5,
			"margin_top"=>5,
			"margin_right"=>10,
			"margin_bottom"=>8,
			"margin_footer"=>3,	
			'fontDir' => array_merge($fontDirs, [
				FCPATH . 'assets/fonts/PT_Mono/',
			]),
			'fontdata' => $fontData + [
				'pt-mono' => [
					'R' => 'PTMono-Regular.ttf',
				]
			],					
		];
	}


	function getPeriod($date=null,$mode="month"){
		if ($date==null){
			if ($mode == "year"){
				return date("Y-12");
			}
			return date_format($date,"Y-m");
		}else{
			$date = date_create($date);
			if ($mode == "year"){
				return date_format($date,"Y-12");
			}			
			return date_format($date,"Y-m");
		}
	}


	function nextPeriod($strPeriod,$mode="month"){
		$arrPeriod = explode("-",$strPeriod);

		$th = $arrPeriod[0];
		$bln = $arrPeriod[1];

		if ($mode == "year"){
			return $th + 1 ."-12";
		}

		$nextBln = $bln+ 1;
		if ($nextBln == 13){
			$nextBln = 1;
			$th = $th + 1;
		}

		$nextBln = "00" . $nextBln;
		$nextBln = substr($nextBln,strlen($nextBln)-2);
		$th = "0000" . $th;
		$th = substr($th,strlen($th)-4);
		return $th."-".$nextBln;

	}
	
	function addPeriod($strPeriod,$addPeriod,$mode="month"){
		for($i=0;$i<$addPeriod -1;$i++){
			$strPeriod = nextPeriod($strPeriod,$mode);
		}
		return $strPeriod;
	}

	function periodIsEndOfYear($strPeriod){
		$arrPeriod = explode("-",$strPeriod);
		$th = $arrPeriod[0];
		$bln = $arrPeriod[1];

		if ($bln==12){
			return true;
		}
		return false;

	}

	function diffPeriod($startPeriod,$endPeriod,$mode="month"){
		//$dateStart=date_create($startPeriod . "-01");
		//$dateEnd=date_create($endPeriod."-01");


		if ($mode=="year"){
			return date_format($dateEnd,"Y") - date_format($dateStart,"Y");
		}

		$arrStart =explode("-",$startPeriod);
		$arrEnd =explode("-",$endPeriod);
		
		$diffTh = $arrEnd[0] -$arrStart[0];
		$diffMonth = $arrEnd[1] -$arrStart[1];
		return ($diffTh*12 ) + $diffMonth;
		
		/*
		$diff=date_diff($dateStart,$dateEnd);
		if ($diff == false){
			return 0;
		}
		return ($diff->y *12) + $diff->m;		
		*/
	}

	function getPeriodDate($strPeriod){
		//last day of month
		$strPeriod = nextPeriod($strPeriod);
		$date = date_create($strPeriod . "-01");
		date_add($date,date_interval_create_from_date_string("-1 days"));
		return date_format($date,"Y-m-d");

	}

	function echoIfColSelected($colNo,$selectedCols,$sstrExist,$sstrNotExist=""){
		if (in_array($colNo,$selectedCols)){
			echo $sstrExist;
		}else{
			echo $sstrNotExist;
		}
	}
	function totalSelectedCol($untilCol,$selectedCols){
		$ttl = 0;
		for($i=0;$i<$untilCol;$i++){
			if (in_array($i,$selectedCols)){
				$ttl += 1;
			}
		}
		return $ttl;
	}

	function foldersize($path) {
		//$path = FCPATH . ".." ."/eticketing/assets/app/tickets/image" ;
		//$path = str_replace("/",DIRECTORY_SEPARATOR,$path);
		$total_size = 0;
		$files = scandir($path);
		$cleanPath = rtrim($path, '/'). '/';
	
		foreach($files as $t) {
			if ($t<>"." && $t<>"..") {
				$currentFile = $cleanPath . $t;
				if (is_dir($currentFile)) {
					$size = foldersize($currentFile);
					$total_size += $size;
				}
				else {
					$size = filesize($currentFile);
					$total_size += $size;
				}
			}   
		}	
		return $total_size;
	}

	function format_size($size,$sizeType="") {
		global $units;
		$units = [
			"B","KB","MB","GB","TB"
		];

		$mod = 1024;
		if ($sizeType == ""){			
			for ($i = 0; $size > $mod; $i++) {
				$size /= $mod;
			}	
			$endIndex = strpos($size, ".") + 3;	
			//return substr( $size, 0, $endIndex).' '.$units[$i];
			return (double) substr($size, 0, $endIndex);
		}else{
			if ($sizeType == "B"){
				return (double) $size;// .' bytes';
			}
			$idx = array_search($sizeType,$units);
			for ($i = 0; $i < $idx; $i++) {
				$size /= $mod;
			}
			
			$endIndex = strpos($size, ".") + 3;	
			//return substr( $size, 0, $endIndex).' '.$sizeType;
			return (double) substr( $size, 0, $endIndex);

		}

		
	}