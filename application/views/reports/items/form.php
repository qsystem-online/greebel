<!-- form start -->
<form id="rptItems" action="<?= site_url() ?>report/items/process" method="POST" enctype="multipart/form-data">
    <div class="box-body">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">                    
            <div class="form-group row">
                <label for="select-GroupItemId" class="col-md-2 control-label"><?= lang("Group") ?></label>
                <div class="col-md-4">
                    <select id="select-GroupItemId" class="form-control" name="fin_item_group_id"></select>
                    <div id="fin_item_group_id_err" class="text-danger"></div>
                </div>
                <label for="fin_item_type_id" class="col-md-2 control-label"><?= lang("Item Type") ?></label>
                <div class="col-md-4">
                    <select class="form-control" id="fin_item_type_id" name="fin_item_type_id">
                        <option value='1'><?= lang("Raw Material") ?></option>
                        <option value='2'><?= lang("Semi Finished Material") ?></option>
                        <option value='3'><?= lang("Supporting Material") ?></option>
                        <option value='4'><?= lang("Ready Product") ?></option>
                        <option value='5'><?= lang("Logistic") ?></option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
            <label for="select-lineBusiness" class="col-md-2 control-label"><?=lang("Line Of Business")?></label>
                <div class="col-md-10">
                    <select class="form-control select2" id="select-lineBusiness" name="fst_linebusiness_id[]"  multiple="multiple">
                    <?php foreach ($linebusinessList as $linebusiness) {    ?>
                                <option value='<?= $linebusiness->fin_linebusiness_id ?>'><?= $linebusiness->fst_linebusiness_name ?> </option>
                            <?php
                        } ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="select-ItemCode" class="col-md-2 control-label"><?= lang("Item Code") ?></label>
                <div class="col-md-4">
                    <select id="select-ItemCode" class="form-control" name="fst_item_code">
                        <option value="0">--  <?= lang("select") ?>  --</option>
                    </select>
                    <div id="fst_item_code_err" class="text-danger"></div>
                </div>
                <label for="select-CodeItem" class="col-md-2 control-label"><?= lang("s/d") ?></label>
                <div class="col-md-4">
                    <select id="select-ItemCode2" class="form-control" name="fst_item_code2">
                        <option value="0">--  <?= lang("select") ?>  --</option>
                    </select>
                    <div id="fst_item_code2_err" class="text-danger"></div>
                </div>
            </div>
            <div class="form-group row">
                <div class="checkbox col-md-12">
                    <label><input id="fbl_is_batch_number" type="checkbox" name="fbl_is_batch_number" value="1"><?= lang("is BatchNumber") ?></label>
                    <label><input id="fbl_is_serial_number" type="checkbox" name="fbl_is_serial_number" value="1"><?= lang("is Serial Number") ?></label>
                    <label><input id="fbl_is_online" type="checkbox" name="fbl_is_online" value="1"><?= lang("is Online") ?></label>
                </div>                        
            </div>
        
            <div class="form-group row">
                <label for="rpt_layout" class="col-sm-2 control-label"><?=lang("Report Layout")?></label>
                <div class="col-sm-4">								
                    <label class="radio"><input type="radio" id="rpt_layout1" class="rpt_layout" name="rpt_layout" value="1" checked onclick="handleRadioClick(this);"><?=lang("Laporan Daftar Barang")?></label>
                    <label class="radio"><input type="radio" id="rpt_layout2" class="rpt_layout" name="rpt_layout" value="2" onclick="handleRadioClick(this);"><?=lang("Laporan Daftar Barang Detail Unit Satuan")?></label>
                    <label class="radio"><input type="radio" id="rpt_layout3" class="rpt_layout" name="rpt_layout" value="3" onclick="handleRadioClick(this);"><?=lang("Laporan Daftar Barang Detail BOM")?></label>
                    <label class="radio"><input type="radio" id="rpt_layout4" class="rpt_layout" name="rpt_layout" value="4" onclick="handleRadioClick(this);"><?=lang("Laporan Daftar Barang Detail Special Pricing")?></label>
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
<?php
    echo $mdlItemGroup;
?>
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
        $("#select-GroupItemId").select2({
            width: '100%',
            ajax: {
                url: '<?= site_url() ?>master/item/get_data_ItemGroupId',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    data2 = [];
                    $.each(data, function(index, value) {
                        data2.push({
                            "id": value.fin_item_group_id,
                            "text": value.fst_item_group_name
                        });
                    });
                    console.log(data2);
                    return {
                        results: data2
                    };
                },
                cache: true,
            }
        }).on("select2:open",function(e){
            e.preventDefault();
            $(this).select2("close");
            showItemGroup(true,function(node){
                //consoleLog(node);                
                $("#select-GroupItemId").empty();
                var newOption = new Option(node.text,node.id, false, false);
                $('#select-GroupItemId').append(newOption).trigger('change');
            });
        });

        $("#select-ItemCode").select2({
            width: '100%',
            ajax: {
                url: '<?= site_url() ?>master/item/get_data_ItemCode',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    data2 = [];
                    $.each(data, function(index, value){
                        data2.push({
                            "id": value.fst_item_code,
                            "text": value.fst_item_code
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

        $("#select-ItemCode2").select2({
            width: '100%',
            ajax: {
                url: '<?= site_url() ?>master/item/get_data_ItemCode',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    data2 = [];
                    $.each(data, function(index, value){
                        data2.push({
                            "id": value.fst_item_code,
                            "text": value.fst_item_code
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
            data = $("#rptItems").serializeArray();
            url = "<?= site_url() ?>report/items/process";
            
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
                        url = "<?= site_url() ?>report/items/generateexcel";
                        //alert(url);
                        //$("iframe").attr("src",url);
                        $("#rptItems").attr('action', url);
                        $("#rptItems").attr('target', 'rpt_iframe');
                        $("#rptItems").submit();
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
            resp = $("#rptUsers").serializeArray();
            url = "<?= site_url() ?>report/items/process";
            

            data = JSON.stringify(resp);
            // $("#fin_branch_id").val(data.insert_id);
            
            //Clear all previous error
            $(".text-danger").html("");
            url = "<?= site_url() ?>report/items/generateexcel/0";
            //alert(url);
            //$("iframe").attr("src",url);
            $("#rptItems").attr('action', url);
            $("#rptItems").attr('target', 'rpt_iframe');
            $("#rptItems").submit();
            $("a#toggle-window").click();

        });        
    });

</script>

