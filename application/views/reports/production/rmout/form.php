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
<form id="rptRMOUT" action="<?= site_url() ?>report/production/rmout/process" method="POST" enctype="multipart/form-data">
    <div class="box-body">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>"> 
            <div class="form-group row">
                <label for="fdt_datetime" class="col-sm-2 control-label" id="tanggal" name="tanggal"><?=lang("Tanggal Data")?> *</label>
                <div class="col-sm-4">
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control datepicker" id="fdt_datetime" name="fdt_datetime"/>
                    </div>
                    <div id="fdt_datetime_err" class="text-danger"></div>
                    <!-- /.input group -->
                </div>
                <label for="fdt_datetime2" class="col-sm-2 control-label"><?=lang("s/d")?> *</label>
                <div class="col-sm-4">
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control datepicker" id="fdt_datetime2" name="fdt_datetime2"/>
                    </div>
                    <div id="fdt_datetime2_err" class="text-danger"></div>
                </div>
                <div class="col-sm-3"></div>
            </div>                   
            <div class="form-group row">
                <label for="rpt_layout" class="col-sm-2 control-label"><?=lang("Report Layout")?></label>
                <div class="col-sm-4">								
                    <label class="radio"><input type="radio" id="rpt_layout1" class="rpt_layout" name="rpt_layout" value="1" checked onclick="handleRadioClick(this);"><?=lang("Laporan RM-OUT (Tgl Data)")?></label>
                    <label class="radio"><input type="radio" id="rpt_layout1" class="rpt_layout" name="rpt_layout" value="2" onclick="handleRadioClick(this);"><?=lang("Laporan RM-OUT (Tgl System)")?></label>
                    <label class="radio"><input type="radio" id="rpt_layout1" class="rpt_layout" name="rpt_layout" value="3" onclick="handleRadioClick(this);"><?=lang("Laporan RM-OUT (Tgl Edit)")?></label>
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
                        <!-- <option value="php">PHP</option>
                        <option value="javascript">JavaScript</option>
                        <option value="java">Java</option>
                        <option value="sql">SQL</option>
                        <option value="jquery">Jquery</option>
                        <option value=".net">.Net</option> -->
                    </select>             
                </div>
            </div>
    </div>
</form>

<script type="text/javascript" info="init">
	$(function(){
		$("#fdt_datetime").val(dateFormat("<?= date("Y-m-d")?>")).datepicker("update");
        $("#fdt_datetime2").val(dateFormat("<?= date("Y-m-d")?>")).datepicker("update");					
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

        if (myRadio.value == "1"){
            $('#tanggal').text("Tanggal Data*");
        }
        if (myRadio.value == "2"){
            $('#tanggal').text("Tanggal System*");
        }

        if (myRadio.value == "3"){
            $('#tanggal').text("Tanggal Edit*");
        }

        // for(var i=0; i<newArray.length; i++){
        //     alert(newArray[i].label);
        //     console.log(newArray[i].label);
        // }
        
        // currentValue = myRadio.value;
    }         
    $(function() {
        
        $("#btnProcess").click(function(event) {
            event.preventDefault();
            App.blockUIOnAjaxRequest("Please wait while processing data.....");
            //data = new FormData($("#frmBranch")[0]);
            data = $("#rptRMOUT").serializeArray();
            url = "<?= site_url() ?>report/production/rmout/process";
            
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
                        url = "<?= site_url() ?>report/production/rmout/generatereport";
                        //alert(url);
                        //$("iframe").attr("src",url);
                        $("#rptRMOUT").attr('action', url);
                        $("#rptRMOUT").attr('target', 'rpt_iframe');
                        $("#rptRMOUT").submit();
                        $("a#toggle-window").click();
                        // Change to Edit mode
                        // $("#frm-mode").val("EDIT"); //ADD|EDIT
                        // $('#fst_branch_name').prop('readonly', true);
                        // updateIFrame(resp.data);

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
			a.download = 'Laporan_RM_OUT' + '.xls';
			a.click();                        			
			return;
		});      
    });

</script>

