var select2customAdapter;
$(function(){
	//init datetime picker
	if (typeof $(".datepicker").datepicker === "function") { 

		$(".datepicker").datepicker({
			format: DATEPICKER_FORMAT,
			autoclose:true,
		}).on("show",function(){
			$('.datepicker-dropdown').css('z-index', 99999999999999);
		});
	}

	if (typeof $(".select2").select2 === "function") { 
		$(".select2").select2();

		//reff: https://stackoverflow.com/questions/16480910/update-select2-data-without-rebuilding-the-control
		//Sample usage: D:\xampp\htdocs\greebel\application\views\pages\tr\production\rmout_p\form.php | id:fin_wobatchno_id |line: 670
		$.fn.select2.amd.define('select2/data/customAdapter',['select2/data/array', 'select2/utils'],
			function (ArrayAdapter, Utils) {
				function CustomDataAdapter ($element, options) {
					CustomDataAdapter.__super__.constructor.call(this, $element, options);
				}
				Utils.Extend(CustomDataAdapter, ArrayAdapter);
				CustomDataAdapter.prototype.updateOptions = function (data) {
					this.$element.find('option').remove(); // remove all options
					this.addOptions(this.convertToOptions(data));
				}        
				return CustomDataAdapter;
			}
		);
		select2customAdapter = $.fn.select2.amd.require('select2/data/customAdapter');
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
			allowMinus: true,
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

	if (typeof $(".daterangepicker").daterangepicker === "function"){
		$('.daterangepicker').daterangepicker({
			startDate: "01-01-2019",
			locale: {
				format: DATERANGEPICKER_FORMAT,
				
			  }
		},function(start, end, label){
			this.element.trigger('daterangepicker.change', this);
			//$("#daterange_needapproval").trigger("afterchange");
		});
	}
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
		var async = true;
		if (typeof obj.async != "undefined"){
			async = obj.async;
		}		
		if (typeof obj.wait_message != "undefined"){
			App.blockUIOnAjaxRequest(obj.wait_message);
		}else{
			App.blockUIOnAjaxRequest();
		}		
		$.ajax({
			//url:obj.site_url + 'api/get_value',
			url: SITE_URL + 'api/get_value',
			method:"POST",
			async:async,
			data:{
				model:obj.model,
				function:obj.func,
				params:obj.params
			},
		}).done(function(resp){
			obj.callback(resp.data);
		});
			
	
	},
	blockUIOnAjaxRequest: function(message){
		
		if (typeof message == "undefined"){
			message = "<h5><img src='" + SITE_URL + "assets/system/images/loading.gif'> Please wait ... !</h5>";
		}

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
	dateParse: function(strDate){
		var result = moment(strDate,DATEPICKER_FORMAT_MOMENT).format('YYYY-MM-DD');
		return result;
	},

	dateTimeFormat:function(strDateTime){
		var result = moment(strDateTime,'YYYY-MM-DD HH:mm:ss').format(DATETIMEPICKER_FORMAT_MOMENT);
		return result;
	},
	dateTimeParse:function(strDateTime){
		var result = moment(strDateTime,DATETIMEPICKER_FORMAT_MOMENT).format('YYYY-MM-DD HH:mm:ss');
		return result;
	},

	consoleLog:function(obj){
		console.log(obj);	
	},

	autoFillForm:function(data){
		$.each(data, function(name, val){
			var $el = $('[name="'+name+'"]'),
			type = $el.attr('type');
			switch(type){
				case 'checkbox':
					if (val == 0){
						//$el.filter('[value="' + val + '"]').attr('checked', 'checked');
						$el.prop("checked",false);
					}else{
						$el.prop("checked",true);
						//$el.filter('[value="' + val + '"]').attr('checked', 'checked');
					}
					
					break;
				case 'radio':
					$el.filter('[value="' + val + '"]').attr('checked', 'checked');
					break;
				default:
					$el.val(val);
			}
		});
	},

	addOptionIfNotExist:function(option,selectId){
		value = $(option).val();		
		if (! $("#" + selectId + " option[value='"+ value +"']").length){
			$("#" + selectId).append(option);
		}else{
			$("#" + selectId + " option[value='"+ value +"']").prop("selected",true);
		}
	},

	fixedSelect2: function(){
		/*
		$(".select2-container").addClass("form-control"); 		
		$(".select2-selection--single , .select2-selection--multiple").css({
			"border":"0px solid #000",
			"padding":"0px 0px 0px 0px",
			"cursor":"unset",
			"user-select":"unset",
			"-webkit-user-select":"unset"
		});         
		$(".select2-selection--multiple").css({
			"margin-top" : "-5px",
			"background-color":"unset"
		});
		*/
		
	},

	log:function(obj){		
		console.log(obj);
	},
	consoleLog:function(obj){
		App.log(obj);
	},
	showFormErrors:function(resp){
		if(resp.status == "VALIDATION_FORM_FAILED" ){
			//Show Error
			errors = resp.data;
			for (key in errors) {
				$("#"+key+"_err").html(errors[key]);
			}
		}
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

