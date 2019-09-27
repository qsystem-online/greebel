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

	if (typeof $(".datetimepicker").datetimepicker === "function") { 
		$('.datetimepicker').datetimepicker({
			//language:  'fr',
			weekStart: 0,
			todayBtn:  1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			forceParse: 0,
			format: DATETIMEPICKER_FORMAT,
			showMeridian: 0
		});	
	};
});

var App = {
	calculateDisc : function(amount, disc){
		if (disc == "" || disc == null){
			return 0;
		}
		var strArray = disc.split("+");
		totalDisc = 0;
		$.each(strArray,function(i,v){
			disc = amount * (v / 100);
			totalDisc += disc;
			amount = amount - disc;
		});
		return totalDisc;
	},
	money_format : function(number) {
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
	},
	money_parse : function(money){
		value = money.toString();
		var digitPatern = ',';
		if (DIGIT_GROUP == "."){
			digitPatern ='\\.';  			
		}
	
		var re = new RegExp(digitPatern,"g");
		value = value.replace(re,'');
	
		if (DECIMAL_SEPARATOR == ","){
			value = value.replace(",",".");
		}
		return parseFloat(value);
	
		
	},
	getValueAjax : function(obj){		
		//url,model,func,params,callback

		blockUIOnAjaxRequest();
		$.ajax({
			url:obj.url,
			method:"POST",
			data:{
				model:obj.model,
				function:obj.func,
				params:obj.params
			}
		}).done(function(resp){
			obj.callback(resp.data);
		});
			
	
	},
	blockUIOnAjaxRequest: function(message){
		$(document).ajaxStart(function() {
			$.blockUI({ message:message});
		});
	
		$(document).ajaxStop(function() {
			$.unblockUI();
			$(document).unbind('ajaxStart');
		});
	},
	dateFormat: function(strDate){
		var result = moment(strDate,'YYYY-MM-DD').format(DATEPICKER_FORMAT_MOMENT);
		return result;
	},	
	dateTimeFormat:function(strDateTime){
		var result = moment(strDateTime,'YYYY-MM-DD HH:mm:ss').format(DATETIMEPICKER_FORMAT_MOMENT);
		return result;
	},
	consoleLog:function(obj){
		console.log(obj);	
	}

}


//Format data dari ajax ke format datepicker, setting di config.js
function dateFormat(strDate){
	var result = moment(strDate,'YYYY-MM-DD').format(DATEPICKER_FORMAT_MOMENT);
	return result;
}

function dateTimeFormat(strDateTime){
	var result = moment(strDateTime,'YYYY-MM-DD HH:mm:ss').format(DATETIMEPICKER_FORMAT_MOMENT);
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
	value = money.toString();
	var digitPatern = ',';
	if (DIGIT_GROUP == "."){
		digitPatern ='\\.';  			
	}

	var re = new RegExp(digitPatern,"g");
	value = value.replace(re,'');

	if (DECIMAL_SEPARATOR == ","){
		value = value.replace(",",".");
	}
	return parseFloat(value);

	
}




function consoleLog(obj){
	console.log(obj);	
}

function blockUIOnAjaxRequest(message){
	$(document).ajaxStart(function() {
		$.blockUI({ message:message});
	});

	$(document).ajaxStop(function() {
		$.unblockUI();
		$(document).unbind('ajaxStart');
	});
}

function fixedSelect2(){
	$(".select2-container").addClass("form-control"); 
	$(".select2-selection--single , .select2-selection--multiple").css({
		"border":"0px solid #000",
		"padding":"0px 0px 0px 0px"
	});         
	$(".select2-selection--multiple").css({
		"margin-top" : "-5px",
		"background-color":"unset"
	});
};

