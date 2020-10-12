<!-- form start -->
<form id="rptSalesarea" action="<?= site_url() ?>report/sales_area/process" method="POST" enctype="multipart/form-data">
    <div class="box-body">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">                    
            <div class="form-group row">
                <label for="select-RegionalStart" class="col-md-2 control-label"><?=lang("Sales Regional Name")?></label>
                <div class="col-md-4">
                    <select id="select-RegionalStart" class="form-control" name="fin_sales_regional_id">
                        <option value="0">-- <?=lang("select")?> --</option>
                    </select>
                    <div id="fin_sales_regional_id_err" class="text-danger"></div>
                </div>

                <label for="select-RegionalEnd" class="col-md-2 control-label"><?=lang("Sales Regional Name")?></label>
                <div class="col-md-4">
                    <select id="select-RegionalEnd" class="form-control" name="fin_sales_regional_id2">
                        <option value="0">-- <?=lang("select")?> --</option>
                    </select>
                    <div id="fin_sales_regional_id2_err" class="text-danger"></div>
                </div>
            </div>
        
            <div class="form-group row">
                <label for="rpt_layout" class="col-sm-2 control-label"><?=lang("Report Layout")?></label>
                <div class="col-sm-4">								
                    <label class="radio"><input type="radio" id="rpt_layout1" class="rpt_layout" name="rpt_layout" value="1" checked onclick="handleRadioClick(this);"><?=lang("Laporan Daftar Sales Area")?></label>
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
        // for(var i=0; i<newArray.length; i++){
        //     alert(newArray[i].label);
        //     console.log(newArray[i].label);
        // }
        
        // currentValue = myRadio.value;
    }         

    $(function() {
        $("#select-RegionalStart").select2({
            width: '100%',
            ajax: {
                url: '<?=site_url()?>master/sales_area/get_Regional',
                dataType: 'json',
                delay: 250,
                processResults: function (data){
                    data2 = [];
                    data = data.data;
                    $.each(data,function(index,value){
                        data2.push({
                            "id" : value.fin_sales_regional_id,
                            "text" : value.fst_name
                        });
                    });
                    console.log(data2);
                    return {
                        results: data2
                    };
                },
                cache: true,
            }
        });

        $("#select-RegionalEnd").select2({
            width: '100%',
            ajax: {
                url: '<?=site_url()?>master/sales_area/get_Regional',
                dataType: 'json',
                delay: 250,
                processResults: function (data){
                    data2 = [];
                    data = data.data;
                    $.each(data,function(index,value){
                        data2.push({
                            "id" : value.fin_sales_regional_id,
                            "text" : value.fst_name
                        });
                    });
                    console.log(data2);
                    return {
                        results: data2
                    };
                },
                cache: true,
            }
        });

        $("#btnProcess").click(function(event) {
            event.preventDefault();
            App.blockUIOnAjaxRequest("Please wait while processing data.....");
            //data = new FormData($("#frmBranch")[0]);
            data = $("#rptSalesarea").serializeArray();
            url = "<?= site_url() ?>report/sales_area/process";
            
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
                                        $("#btnNew").trigger("click");
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
                        url = "<?= site_url() ?>report/sales_area/generateexcel";
                        //alert(url);
                        //$("iframe").attr("src",url);
                        $("#rptSalesarea").attr('action', url);
                        $("#rptSalesarea").attr('target', 'rpt_iframe');
                        $("#rptSalesarea").submit();
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
            App.blockUIOnAjaxRequest("Please wait while downloading excel file.....");
            //data = new FormData($("#frmBranch")[0]);
            resp = $("#rptSalesarea").serializeArray();
            url = "<?= site_url() ?>report/sales_area/process";
            

            data = JSON.stringify(resp);
            // $("#fin_branch_id").val(data.insert_id);
            
            //Clear all previous error
            $(".text-danger").html("");
            url = "<?= site_url() ?>report/sales_area/generateexcel/0";
            //alert(url);
            //$("iframe").attr("src",url);
            $("#rptSalesarea").attr('action', url);
            $("#rptSalesarea").attr('target', 'rpt_iframe');
            $("#rptSalesarea").submit();
            $("a#toggle-window").click();

        });
    });

</script>

