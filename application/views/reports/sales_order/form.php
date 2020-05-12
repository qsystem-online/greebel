<!-- form start -->
<form id="rptSalesOrder" action="<?= site_url() ?>report/sales_order/process" method="POST" enctype="multipart/form-data">
    <div class="box-body">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">                    
            <div class="form-group row">
                <label for="select-Branch" class="col-sm-2 control-label"><?= lang("Branch") ?> :</label>
                <div class="col-sm-4">
                    <select id="select-Branch" class="form-control" name="fin_branch_id"></select>
                    <div id="fin_branch_id_err" class="text-danger"></div>
                </div>
                <label for="select-warehouse" class="col-sm-2 control-label"><?=lang("Warehouse")?> :</label>
                <div class="col-sm-4">
                    <select id="select-warehouse" class="form-control" name="fin_warehouse_id">
                        <option value=''>--select--</option>
                        <?php
                            $warehouseList = $this->mswarehouse_model->getNonLogisticWarehouseList();
                            foreach($warehouseList as $warehouse){
                                echo "<option value='".$warehouse->fin_warehouse_id ."'>$warehouse->fst_warehouse_name</option>";
                            }
                        ?>
                    </select>
                    <div id="fin_warehouse_id_err" class="text-danger"></div>
                </div>
            </div>
            <div class="form-group row">						
                <label for="select-relations" class="col-sm-2 control-label"><?=lang("Customer")?> :</label>
                <div class="col-sm-4">
                    <select id="select-relations" class="form-control non-editable" name="fin_relation_id">
                        <option value="0">-- <?=lang("select")?> --</option>
                    </select>
                    <div id="fin_relation_id_err" class="text-danger"></div>
                </div>            
                <label for="select-sales" class="col-sm-2 control-label"><?=lang("Sales")?> :</label>
                <div class="col-sm-4">
                    <select id="select-sales" class="form-control" name="fin_sales_id">
                        <option value=''>--select--</option>
                        <?php
                            $salesList = $this->users_model->getSalesList();
                            foreach($salesList as $sales){
                                echo "<option value='$sales->fin_user_id'>$sales->fst_username</option>";
                            }                            
                        ?>
                    </select>
                    <div id="fin_sales_id_err" class="text-danger"></div>
                </div>
            </div>
            <div class="form-group row">
                <label for="fdt_salesorder_datetime" class="col-sm-2 control-label"><?=lang("Sales Order Date")?> *</label>
                <div class="col-sm-4">
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control datepicker" id="fdt_salesorder_datetime" name="fdt_salesorder_datetime"/>
                    </div>
                    <div id="fdt_salesorder_datetime_err" class="text-danger"></div>
                    <!-- /.input group -->
                </div>
                <label for="fdt_salesorder_datetime2" class="col-sm-2 control-label"><?=lang("s/d")?> *</label>
                <div class="col-sm-4">
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control datepicker" id="fdt_salesorder_datetime2" name="fdt_salesorder_datetime2"/>
                    </div>
                    <div id="fdt_salesorder_datetime2_err" class="text-danger"></div>
                </div>
                <div class="col-sm-3"></div>
            </div>
            <div class="form-group row">
                <label for="rpt_layout" class="col-sm-2 control-label"><?=lang("Report Layout")?></label>
                <div class="col-sm-4">								
                    <label class="radio"><input type="radio" id="rpt_layout1" class="rpt_layout" name="rpt_layout" value="1" checked onclick="handleRadioClick(this);"><?=lang("Laporan Sales Order Detail")?></label>
                    <label class="radio"><input type="radio" id="rpt_layout2" class="rpt_layout" name="rpt_layout" value="2" onclick="handleRadioClick(this);"><?=lang("Laporan Sales Order Ringkas")?></label>
                    <label class="radio"><input type="radio" id="rpt_layout3" class="rpt_layout" name="rpt_layout" value="3" onclick="handleRadioClick(this);"><?=lang("Laporan Sales Order Outstanding")?></label>
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
            <div class="form-group">
                <div class="checkbox col-md-12">
                    <label><input id="fbl_is_hold" type="checkbox" name="fbl_is_hold" value="1"><?= lang("Hold Pengiriman") ?></label>
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

		// $("#fdt_salesorder_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");		
		// $("#fdt_salesorder_datetime").val(dateTimeFormat("<?= date("Y-m-d")?>")).datetimepicker("update");		
        // $("#fdt_salesorder_datetime").datepicker('update'));

		// $("#btnList").click(function(e){
		// 	e.preventDefault();
		// 	window.location.replace("<?=site_url()?>master/branch/lizt");
        // });
        $("#select-relations").select2({
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
		});

        $("#btnProcess").click(function(event) {
            event.preventDefault();
            App.blockUIOnAjaxRequest("Please wait while processing data.....");
            //data = new FormData($("#frmBranch")[0]);
            data = $("#rptSalesOrder").serializeArray();
            url = "<?= site_url() ?>report/sales_order/process";
            
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
                        url = "<?= site_url() ?>report/sales_order/generateexcel";
                        //alert(url);
                        //$("iframe").attr("src",url);
                        $("#rptSalesOrder").attr('action', url);
                        $("#rptSalesOrder").attr('target', 'rpt_iframe');
                        $("#rptSalesOrder").submit();
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
            resp = $("#rptSalesOrder").serializeArray();
            url = "<?= site_url() ?>report/sales_order/process";
            

            data = JSON.stringify(resp);
            // $("#fin_branch_id").val(data.insert_id);
            
            //Clear all previous error
            $(".text-danger").html("");
            url = "<?= site_url() ?>report/sales_order/generateexcel/0";
            //alert(url);
            //$("iframe").attr("src",url);
            $("#rptSalesOrder").attr('action', url);
            $("#rptSalesOrder").attr('target', 'rpt_iframe');
            $("#rptSalesOrder").submit();
            $("a#toggle-window").click();

        });        
    });

</script>

