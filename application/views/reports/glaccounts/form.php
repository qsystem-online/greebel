<!-- form start -->
<form id="rptGlaccounts" action="<?= site_url() ?>report/glaccounts/process" method="POST" enctype="multipart/form-data">
    <div class="box-body">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">                    
            <div class="form-group row">
                <label for="select-mainGLStart" class="col-md-2 control-label"><?= lang("Main Group GL") ?></label>
                <div class="col-md-4">
                    <select id="select-mainGLStart" class="form-control" name="fin_glaccount_maingroup_id">
                        <?php foreach ($mainGL2Report as $mainGLReport) {    ?>
                        <option value='<?= $mainGLReport->fin_glaccount_maingroup_id ?>'><?= $mainGLReport->fst_glaccount_maingroup_name ?> </option>
                    <?php
                } ?>
                    </select>
                </div>
                <label for="select-mainGLEnd" class="col-md-2 control-label"><?= lang("s/d") ?></label>
                <div class="col-md-4">
                    <select id="select-mainGLEnd" class="form-control" name="fin_glaccount_maingroup_id2">
                        <?php foreach ($mainGL2Report as $mainGLReport) {    ?>
                        <option value='<?= $mainGLReport->fin_glaccount_maingroup_id ?>'><?= $mainGLReport->fst_glaccount_maingroup_name ?> </option>
                    <?php
                } ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="checkbox col-md-12">
                    <label><input id="fbl_is_allow_in_cash_bank_module" type="checkbox" name="fbl_is_allow_in_cash_bank_module" value="1"><?= lang("Allow In Cashbank Module") ?></label>
                    <label><input id="fbl_pc_divisi" type="checkbox" name="fbl_pc_divisi" value="1"><?= lang("Analisa Divisi") ?></label>
                    <label><input id="fbl_pc_customer" type="checkbox" name="fbl_pc_customer" value="1"><?= lang("Analisa Customer") ?></label>
                    <label><input id="fbl_pc_project" type="checkbox" name="fbl_pc_project" value="1"><?= lang("Analisa Project") ?></label>
                </div>                        
            </div>
        
            <div class="form-group row">
                <label for="rpt_layout" class="col-sm-2 control-label"><?=lang("Report Layout")?></label>
                <div class="col-sm-4">								
                    <label class="radio"><input type="radio" id="rpt_layout1" class="rpt_layout" name="rpt_layout" value="1" checked onclick="handleRadioClick(this);"><?=lang("Laporan Daftar GL Account")?></label>
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


    $("#btnProcess").click(function(event) {
        event.preventDefault();
        App.blockUIOnAjaxRequest("Please wait while processing data.....");
        //data = new FormData($("#frmBranch")[0]);
        data = $("#rptGlaccounts").serializeArray();
        url = "<?= site_url() ?>report/glaccounts/process";
        
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
                    url = "<?= site_url() ?>report/glaccounts/generateexcel";
                    //alert(url);
                    //$("iframe").attr("src",url);
                    $("#rptGlaccounts").attr('action', url);
                    $("#rptGlaccounts").attr('target', 'rpt_iframe');
                    $("#rptGlaccounts").submit();
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

    $("#select-MainGLStart").select2();

    $("#select-MainGLEnd").select2();

    $("#btnExcel").click(function(event) {
        event.preventDefault();
        App.blockUIOnAjaxRequest("Please wait while downloading excel file.....");
        //data = new FormData($("#frmBranch")[0]);
        resp = $("#rptGlaccounts").serializeArray();
        url = "<?= site_url() ?>report/glaccounts/process";
        

        data = JSON.stringify(resp);
        // $("#fin_branch_id").val(data.insert_id);
        
        //Clear all previous error
        $(".text-danger").html("");
        url = "<?= site_url() ?>report/glaccounts/generateexcel/0";
        //alert(url);
        //$("iframe").attr("src",url);
        $("#rptGlaccounts").attr('action', url);
        $("#rptGlaccounts").attr('target', 'rpt_iframe');
        $("#rptGlaccounts").submit();
        $("a#toggle-window").click();

    });

</script>

