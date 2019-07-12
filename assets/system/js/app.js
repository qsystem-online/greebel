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
			groupSeparator: ",",
			radixPoint: ".",
			allowMinus: false,
			autoUnmask: true,
			digits: 2
		});
	}
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

function consoleLog(obj){
	console.log(obj);	
}