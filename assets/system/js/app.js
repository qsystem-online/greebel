$(function(){
	//init datetime picker
	if (typeof $(".datepicker").datepicker === "function") { 
		$(".datepicker").datepicker({
			format: DATEPICKER_FORMAT
		});
	}

	if (typeof $(".select2").select2 === "function") { 
		$(".select2").select2();
	};

	if (typeof $('.icheck').iCheck === "function") {
		$('.icheck').iCheck({
			checkboxClass: 'icheckbox_minimal-blue',
			radioClass   : 'iradio_minimal-blue'
		})
	}
	
	if (typeof $('.money').inputmask =="function"){
		$(".money").inputmask({
			alias: 'numeric',
			autoGroup: true,
			groupSeparator: DIGIT_GROUP,
			radixPoint: DECIMAL_SEPARATOR,
			allowMinus: false,
			autoUnmask: true,
			digits: 2
		});
	}


	/*
	if (typeof $('.money').inputmask =="function"){
		alert("money");
		$('.money').inputmask({
			//mask:999999999,
			alias:"numeric",
			//groupSeparator: ".",
		   // radixPoint :",",
			//prefix:"",
			//digits:2,
			//clearIncomplete: true
		});
	}
	*/
	
   /*
   //https://plentz.github.io/jquery-maskmoney/
   if (typeof $('.money').maskMoney =="function"){
		$('.money').maskMoney({
			thousands:",",
			decimal:".",
			precision:2,
			allowZero:true,
			allowNegative:true,
		});
   }
   */
  /*
	$(".money").focusout(function(){
		value = $(this).val();
		//var patern = ',';
		//if (DIGIT_GROUP == "."){
		//	patern ='\\.';  			
		//}
		//var re = new RegExp(patern,"g");
		//value = value.replace(re,'');
		
		if ($.isNumeric(value)){
			$(this).val( money_format(value) );
		}else{
			$(this).focus();
			$(this).select();
		}
	})
	$(".money").focusin(function(){
		
		value = $(this).val();
		
		$(this).val(money_parse(value));
		$(this).select();
	});

	$(".money").focus(function(){
		
	});
	*/

});

//Format data dari ajax ke format datepicker, setting di config.js
function dateFormat(strDate){
	var result = moment(strDate,'YYYY-MM-DD').format(DATEPICKER_FORMAT_MOMENT);
	return result;
}

 function money_format (number) {
	decimals = DECIMAL_DIGIT;
	dec_point = DECIMAL_SEPARATOR;
	thousands_sep = DIGIT_GROUP;
	number = parseFloat(number);
	number = number.toFixed(decimals);
	var nstr = number.toString();
	nstr += '';
	x = nstr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? dec_point + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1))
		x1 = x1.replace(rgx, '$1' + thousands_sep + '$2');
	return x1 + x2;
}

function money_parse(money){
	value = money;
	var digitPatern = ',';
	if (DIGIT_GROUP == "."){
		digitPatern ='\\.';  			
	}

	var re = new RegExp(digitPatern,"g");
	value = value.replace(re,'');

	if (DECIMAL_SEPARATOR == ","){
		value = value.replace(",",".");
	}
	return value;

	
}