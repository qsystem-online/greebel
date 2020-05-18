<!-- form start -->
<form id="rptBranch" action="<?= site_url() ?>report/branch/process" method="POST" enctype="multipart/form-data">
    <div class="box-body">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">                    
            <div class="form-group row">
                <label for="select-Branch" class="col-sm-2 control-label"><?= lang("Branch") ?> :</label>
                <div class="col-sm-4">
                    <select id="select-Branch" class="form-control" name="fin_branch_id"></select>
                    <div id="fin_branch_id_err" class="text-danger"></div>
                </div>
            </div>
            <div class="form-group row">
            <label for="select-provinces" class="col-md-2 control-label"><?=lang("Province Name")?></label>
                <div class="col-md-4">
                    <select id="select-provinces" class="form-control select2" name="fst_kode">
                        <option value="0">-- <?=lang("select")?> --</option>
                    </select>
                    <div id="fst_nama_err" class="text-danger"></div>
                </div>
            
            <label for="select-district" class="col-md-2 control-label"><?=lang("District Name")?></label>
                <div class="col-md-4">
                    <select id="select-district" class="form-control select2" name="fst_kode">
                        <option value="0">-- <?=lang("select")?> --</option>
                    </select>
                    <div id="fst_nama_err" class="text-danger"></div>
                </div>
            </div>
        
            <div class="form-group row">
                <label for="rpt_layout" class="col-sm-2 control-label"><?=lang("Report Layout")?></label>
                <div class="col-sm-4">								
                    <label class="radio-inline"><input type="radio" id="rpt_layout1" class="rpt_layout" name="rpt_layout" value="1" checked onclick="handleRadioClick(this);"><?=lang("Laporan Daftar Cabang")?></label>
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
        // for(var i=0; i<newArray.length; i++){
        //     alert(newArray[i].label);
        //     console.log(newArray[i].label);
        // }
        
        // currentValue = myRadio.value;
    }         
    $(function() {
        $("#select-Branch").select2({
            width: '100%',
            ajax: {
                url: '<?= site_url() ?>master/warehouse/get_Branch',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    data2 = [];
                    $.each(data, function(index, value) {
                        data2.push({
                            "id": value.fin_branch_id,
                            "text": value.fst_branch_name
                        });
                    });
                    return {
                        results: data2
                    };
                },
                cache: true,
            }
        });
        $("#select-provinces").select2({
            width: '100%',
            ajax: {
                url: '<?=site_url()?>master/branch/get_Province/',
                dataType: 'json',
                delay: 250,
                processResults: function (data){
                    items = [];
                    data = data.data;
                    $.each(data,function(index,value){
                        items.push({
                            "id" : value.fst_kode,
                            "text" : value.fst_nama
                        });
                    });
                    console.log(items);
                    return {
                        results: items
                    };
                },
                cache: true,
            }
        });

        $("#select-provinces").change(function(event){
            event.preventDefault();
            $('#select-district').val(null).trigger('change');
            $("#select-district").select2({
                width: '100%',
                ajax: {
                    url: '<?=site_url()?>master/branch/get_District/'+ $("#select-provinces").val(),
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data){
                        data2 = [];
                        data = data.data;
                        $.each(data,function(index,value){
                            data2.push({
                                "id" : value.fst_kode,
                                "text" : value.fst_nama
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
        });

        /*$("#select-relations").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>tr/sales_order/get_customers',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.fin_relation_id,
							"text" : value.fst_relation_name,
							"fin_sales_id" : value.fin_sales_id,
							"fst_shipping_address":value.fst_shipping_address,
							"fin_warehouse_id":value.fin_warehouse_id,
							"fin_terms_payment":value.fin_terms_payment,
							"fin_cust_pricing_group_id" :value.fin_cust_pricing_group_id					
						});
					});					
					return {
						results: items
					};
				},
				cache: true,
			}
		}).on('select2:select',function(e){
			//selectedCustomer = $("#select-relations").select2("data")[0];
			// selectedCustomer = e.params.data;
			// getShippingAddressList(selectedCustomer.id);
			// $("#fin_terms_payment").val(selectedCustomer.fin_terms_payment);
			// $("#select-sales").val(selectedCustomer.fin_sales_id).trigger("change.select2");
			// $("#select-warehouse").val(selectedCustomer.fin_warehouse_id).trigger("change.select2");
			//current_pricing_group_id = selectedCustomer.current_pricing_group_id;			
		});*/

        $("#btnProcess").click(function(event) {
            event.preventDefault();
            App.blockUIOnAjaxRequest("Please wait while processing data.....");
            //data = new FormData($("#frmBranch")[0]);
            data = $("#rptBranch").serializeArray();
            url = "<?= site_url() ?>report/branch/process";

            if ($("#select-provinces").val() == 0){
                alert("<?=lang('Pilih Province Name ...!')?>");
                return;
            }
            
            // $("iframe").attr("src",url);
            $.ajax({
                type: "POST",
                //enctype: 'multipart/form-data',
                url: url,
                data: data,
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
                        url = "<?= site_url() ?>report/branch/generateexcel";
                        //alert(url);
                        //$("iframe").attr("src",url);
                        $("#rptBranch").attr('action', url);
                        $("#rptBranch").attr('target', 'rpt_iframe');
                        $("#rptBranch").submit();
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
            resp = $("#rptBranch").serializeArray();
            url = "<?= site_url() ?>report/branch/process";
            

            data = JSON.stringify(resp);
            // $("#fin_branch_id").val(data.insert_id);
            
            //Clear all previous error
            $(".text-danger").html("");
            url = "<?= site_url() ?>report/branch/generateexcel/0";
            //alert(url);
            //$("iframe").attr("src",url);
            $("#rptBranch").attr('action', url);
            $("#rptBranch").attr('target', 'rpt_iframe');
            $("#rptBranch").submit();
            $("a#toggle-window").click();

        });        
    });
</script>