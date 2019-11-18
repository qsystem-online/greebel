$(function(){
    //init datetime picker
    $(".datepicker").datepicker({
        format: DATEPICKER_FORMAT
    });

});

//Format data dari ajax ke format datepicker, setting di config.js
function dateFormat(strDate){
    var result = moment(strDate,'YYYY-MM-DD').format(DATEPICKER_FORMAT_MOMENT);
    return result;
}