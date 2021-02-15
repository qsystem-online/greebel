<style type="text/css">
	.border-0{
		border: 0px;
	}
	.form-group{
		margin-bottom: 5px;
	}
	.checkbox label, .radio label {
		font-weight:700;
	}
</style>
<!-- form start -->
<form id="rptPurchaseRequest" action="<?= site_url() ?>report/tr/purchase_request/process" method="POST" enctype="multipart/form-data">
    <div class="box-body">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">                    
            <div class="form-group row">
                <label for="select-department" class="col-sm-2 control-label"><?=lang("Department")?></label>
                <div class="col-sm-4">
                    <select id="select-department" class="form-control" name="fin_req_department_id">
                        <option value='0'>All</option>
                        <?php
                            $departmentList = $this->msdepartments_model->getAllList();
                            foreach($departmentList as $requestBy){
                                echo "<option value='".$requestBy->fin_department_id ."'>$requestBy->fst_department_name</option>";
                            }
                        ?>
                    </select>
                    <div id="fin_req_department_id_err" class="text-danger"></div>
                </div>
                <label for="select-items" class="col-sm-2 control-label"><?=lang("Item")?></label>
                <div class="col-sm-4">
                    <select id="select-items" class="form-control non-editable" name="fin_item_id"></select>
                    <div id="fin_item_id_err" class="text-danger"></div>
                </div>
            </div>
            <div class="form-group row">
                <label for="fdt_pr_datetime" class="col-sm-2 control-label"><?=lang("Request Date")?> *</label>
                <div class="col-sm-4">
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control datepicker" id="fdt_pr_datetime" name="fdt_pr_datetime"/>
                    </div>
                    <div id="fdt_pr_datetime_err" class="text-danger"></div>
                    <!-- /.input group -->
                </div>
                <label for="fdt_pr_datetime2" class="col-sm-2 control-label"><?=lang("s/d")?> *</label>
                <div class="col-sm-4">
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control datepicker" id="fdt_pr_datetime2" name="fdt_pr_datetime2"/>
                    </div>
                    <div id="fdt_pr_datetime2_err" class="text-danger"></div>
                </div>
                <div class="col-sm-3"></div>
            </div>
            <div class="form-group row">
                <label for="rpt_layout" class="col-sm-2 control-label"><?=lang("Report Layout")?></label>
                <div class="col-sm-4">								
                    <label class="radio"><input type="radio" id="rpt_layout1" class="rpt_layout" name="rpt_layout" value="1" checked onclick="handleRadioClick(this);"><?=lang("Laporan Purchase Request Detail")?></label>
                    <label class="radio"><input type="radio" id="rpt_layout2" class="rpt_layout" name="rpt_layout" value="2" onclick="handleRadioClick(this);"><?=lang("Laporan Purchase Request Ringkas")?></label>
                    <label class="radio"><input type="radio" id="rpt_layout3" class="rpt_layout" name="rpt_layout" value="3" onclick="handleRadioClick(this);"><?=lang("Laporan Purchase Request O/S PO")?></label>
                </div>
                <label for="selected_colums" class="col-sm-2 control-label"><?=lang("Selected Columns")?></label>
                <div class="container col-sm-4">
                    <select id="multiple-columns" multiple="multiple" name="selected_columns[]">
                        <?php
                            foreach($layout_columns as $row) {
                                if ($row['layout']==1){
                                    $caption = $row['label'];
                                    $colNo = $row['value'];
                                    echo "<option value='$colNo'>$caption</option>";
                                }
                            };

                        ?>
                    </select>             
                </div>
            </div>
    </div>
</form>
<script type="text/javascript" info="init">
	$(function(){
		$("#fdt_pr_datetime").val(dateFormat("<?= date("Y-m-d")?>")).datepicker("update");
        $("#fdt_pr_datetime2").val(dateFormat("<?= date("Y-m-d")?>")).datepicker("update");					
	});
</script>
<script type="text/javascript">
    $(document).ready(function() {
       // $('#multiple-columns').multiselect();
        $('#multiple-columns').multiselect({
            enableFiltering: true,        
            // includeResetOption: true,
            includeSelectAllOption: true,
            selectAllText: 'Pilih semua'
        });
        $('#multiple-columns').multiselect('selectAll',false);
        $('#multiple-columns').multiselect('updateButtonText');
        
    });

    function handleRadioClick(myRadio) {
        // alert('Old value: ' + currentValue);        
        var js_data = '<?php echo json_encode($layout_columns); ?>';
        var js_obj_data = JSON.parse(js_data );
        
        var newArray = js_obj_data.filter(function (el) {                        
            // alert(el.layout==(myRadio.value).toString());
            return el.layout==(myRadio.value).toString();
        });        

        console.log(newArray);
        $('#multiple-columns').multiselect('dataprovider', newArray);
        $('#multiple-columns').multiselect('selectAll',false);
		$('#multiple-columns').multiselect('updateButtonText');

        if (myRadio.value == "2"){
            $('#select-items').val("0");

        }
        //if (myRadio.value == "4"){
        //    $('#select-relations').empty();
        //    $('#select-relations').append('<option value="0">All</option>');
        //}
        // for(var i=0; i<newArray.length; i++){
        //     alert(newArray[i].label);
        //     console.log(newArray[i].label);
        // }
        
        // currentValue = myRadio.value;
    }         
    $(function() {
        $("#select-items").select2({
            minimumInputLength: 2,
            placeholder:{
                id: '0', // the value of the option
                text: 'All'
            },
            allowClear: true,
            ajax:{
                delay: 250,
                url: "<?=site_url()?>/report/tr/purchase_request/ajxListItem",
                dataType: 'json',
                processResults: function (result) {
                    if (result.status == "SUCCESS"){
                        var data = $.map(result.data, function (obj) {
                            obj.id = obj.fin_item_id,  
                            obj.text = obj.fst_item_code + " - "  + obj.fst_item_name;
                            //obj.fbl_is_batch_number
                            //obj.fbl_is_serial_number
                            return obj;
                        });

                        return {
                            results: data
                        };
                    }else{
                        return {
                            result:[]
                        }
                    }
                }
            }
        }).on('select2:select',function(e){
            var data = e.params.data;
            selectedItem = data;
            //$("#fstUnit").empty().trigger("change.select2");
            //showHideBatchSerial();
        });

        $("#btnProcess").click(function(event) {
            event.preventDefault();
            App.blockUIOnAjaxRequest("Please wait while processing data.....");
            //data = new FormData($("#frmBranch")[0]);
            data = $("#rptPurchaseRequest").serializeArray();
            url = "<?= site_url() ?>report/tr/purchase_request/process";
            
            // $("iframe").attr("src",url);
            $.ajax({
                type: "POST",
                //enctype: 'multipart/form-data',
                url: url,
                data: data,
                //processData: false,
                //contentType: false,
                //cache: false,
                timeout: 600000,
                success: function(resp) {
                    if (resp.message != "") {
                        $.alert({
                            title: 'Message',
                            content: resp.message,
                            buttons: {
                                OK: function() {
                                    if (resp.status == "SUCCESS") {
                                        $("#btnProcess").trigger("click");
                                        alert('OK');
                                        return;
                                    }
                                },
                            }
                        });
                    }


                    if (resp.status == "VALIDATION_FORM_FAILED") {
                        //Show Error
                        errors = resp.data;
                        for (key in errors) {
                            $("#" + key + "_err").html(errors[key]);
                        }
                    } else if (resp.status == "SUCCESS") {
                        data = JSON.stringify(resp.data);
                        // $("#fin_branch_id").val(data.insert_id);
                        // 
                        //Clear all previous error
                        $(".text-danger").html("");
                        //url = "<?= site_url() ?>report/sales_order/generateexcel";
                        url = "<?= site_url() ?>report/tr/purchase_request/generatereport";

                        $("#rptPurchaseRequest").attr('action', url);
                        $("#rptPurchaseRequest").attr('target', 'rpt_iframe');
                        $("#rptPurchaseRequest").submit();
                        $("a#toggle-window").click();

                    }
                },
                error: function(e) {
                    alert('error : ' + e);
                    $("#result").text(e.responseText);
                    console.log("ERROR : ", e);
                    $("#btnProcess").prop("disabled", false);
                }
            });
        });

        $("#btnExcel").click(function(event) {
			event.preventDefault();

			var iframe = $('#rpt_iframe'); // or some other selector to get the iframe
			//var data_type = 'data:application/vnd.ms-excel';
			var data_type = 'data:application/pdf';

			//var table_div = document.getElementById('tblReport');
			//var table_div = document.getElementById('bodyReport');
			var table_div = document.getElementById('rpt_iframe').contentWindow.document.getElementById('bodyReport');

			var table_html = table_div.outerHTML.replace(/ /g, '%20');
			table_html = table_html.replace(/#/g,'%23');

            var a = document.createElement('a');
            a.href = data_type + ', ' + table_html;
			//a.download = 'exported_table_' + Math.floor((Math.random() * 9999999) + 1000000) + '.xls';
			a.download = 'Laporan_Purchase_Request' + '.xls';
			a.click();                        			
			return;
		});      
    });

</script>

