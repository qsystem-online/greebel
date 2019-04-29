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
    

});

//Format data dari ajax ke format datepicker, setting di config.js
function dateFormat(strDate){
    var result = moment(strDate,'YYYY-MM-DD').format(DATEPICKER_FORMAT_MOMENT);
    return result;
}