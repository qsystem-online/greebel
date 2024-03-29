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
<form id="rptInvoiceNetto" action="<?= site_url() ?>report/tr/invoice_netto/process" method="POST" enctype="multipart/form-data">
    <div class="box-body">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">                    
            <div class="form-group row">
                <label for="active_branch_id" class="col-sm-2 control-label"><?= lang("Branch") ?></label>
                <div class="col-sm-4">
                    <?php
                    $active_user = $this->session->userdata("active_user");			
                    $branchs = $this->msbranches_model->getAllList();
                    $disabledSelect = ($active_user->fbl_is_hq == 1) ? "" : "disabled";
                    //$disabledSelect = ($active_user->fin_level <= getDbConfig("change_branch_level")) ? "" : "disabled";
                    ?>
                    <select id="fin_branch_id" class="form-control" name="fin_branch_id" <?= $disabledSelect ?>>
                    <option value='0'>All</option>
                        <?php
                        //print_r($branchs);
                        $activeBranchId = $this->session->userdata("fin_branch_id");
                        foreach ($branchs as $branch) {
                            $isActive = ($branch->fin_branch_id == $activeBranchId) ? "selected" : "";
                            echo "<option value=" . $branch->fin_branch_id . " $isActive >" . $branch->fst_branch_name . "</option>";
                        }
                        ?>
                    </select>
                    <div id="fin_branch_id_err" class="text-danger"></div>
                </div>
                <label for="select-warehouse" class="col-sm-2 control-label"><?=lang("Warehouse")?></label>
                <div class="col-sm-4">
                    <select id="select-warehouse" class="form-control" name="fin_warehouse_id">
                        <option value='0'>All</option>
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
                <label for="select-relations" class="col-sm-2 control-label"><?=lang("Customer")?></label>
                <div class="col-sm-4">
                    <select id="select-relations" class="form-control non-editable" name="fin_relation_id">
                    <option value='0'>All</option>
                    </select>
                    <div id="fin_relation_id_err" class="text-danger"></div>
                </div>            
                <label for="select-sales" class="col-sm-2 control-label"><?=lang("Sales")?></label>
                <div class="col-sm-4">
                    <select id="select-sales" class="form-control" name="fin_sales_id">
                        <option value='0'>All</option>
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
                <label for="fdt_inv_datetime" class="col-sm-2 control-label"><?=lang("Tanggal Faktur")?> *</label>
                <div class="col-sm-4">
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control datepicker" id="fdt_inv_datetime" name="fdt_inv_datetime"/>
                    </div>
                    <div id="fdt_inv_datetime_err" class="text-danger"></div>
                    <!-- /.input group -->
                </div>
                <label for="fdt_inv_datetime2" class="col-sm-2 control-label"><?=lang("s/d")?> *</label>
                <div class="col-sm-4">
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control datepicker" id="fdt_inv_datetime2" name="fdt_inv_datetime2"/>
                    </div>
                    <div id="fdt_inv_datetime2_err" class="text-danger"></div>
                </div>
                <div class="col-sm-3"></div>
            </div>
            <div class="form-group row">						
                <label for="fdt_due_datetime" class="col-sm-2 control-label"><?=lang("Jatuh Tempo")?> *</label>
                <div class="col-sm-4">
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control datepicker" id="fdt_due_datetime" name="fdt_due_datetime"/>
                    </div>
                    <div id="fdt_due_datetime_err" class="text-danger"></div>
                    <!-- /.input group -->
                </div>
                <label for="fst_curr_code" class="col-sm-2 control-label"><?=lang("Mata Uang")?></label>
                <div class="col-sm-4">
                    <select id="fst_curr_code" class="form-control" name="fst_curr_code">
                        <?php
                            $currList = $this->mscurrencies_model->getArrRate();
                            $defaultCurr = $this->mscurrencies_model->getDefaultCurrencyCode();
                            foreach($currList as $curr){
                                $selected =  $defaultCurr == $curr->fst_curr_code ? "selected" : "";
                                echo "<option value='".$curr->fst_curr_code."' $selected>".$curr->fst_curr_name."</option>";
                            }

                        ?>
                        <option value="<?=$default_currency['CurrCode']?>"><?=$default_currency['CurrName']?></option>
                    </select>
                    <div id="fst_curr_code_err" class="text-danger"></div>
                </div>            
            </div>
            <div id="area_1" class="form-group row" style="display:none">
                <label for="select-provinces" class="col-md-2 control-label"><?=lang("Provinsi")?></label>
                <div class="col-md-4">
                    <select id="select-provinces" class="form-control select2" name="fst_kode">
                        <option value="0">-- <?=lang("All")?> --</option>
                    </select>
                    <div id="fst_nama__err" class="text-danger"></div>
                </div>
                <label for="select-district" class="col-md-2 control-label"><?=lang("Kabupaten/Kota")?></label>
                <div class="col-md-4">
                    <select id="select-district" class="form-control select2" name="fst_kode">
                        <option value="0">-- <?=lang("select")?> --</option>
                    </select>
                    <div id="fst_nama__err" class="text-danger"></div>
                </div>

            </div>
            <div id="area_2" class="form-group row" style="display:none">
                <label for="select-subdistrict" class="col-md-2 control-label"><?=lang("Kecamatan")?></label>
                <div class="col-md-4">
                    <select id="select-subdistrict" class="form-control select2" name="fst_kode">
                        <option value="0">-- <?=lang("select")?> --</option>
                    </select>
                    <div id="fst_nama__err" class="text-danger"></div>
                </div>
                <label for="select-village" class="col-md-2 control-label"><?=lang("Kelurahan")?></label>
                <div class="col-md-4">
                    <select id="select-village" class="form-control select2" name="fst_kode">
                        <option value="0">-- <?=lang("select")?> --</option>
                        <div id="fst_nama__err" class="text-danger"></div>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="rpt_layout" class="col-sm-2 control-label"><?=lang("Report Layout")?></label>
                <div class="col-sm-4">								
                    <label class="radio"><input type="radio" id="rpt_layout1" class="rpt_layout" name="rpt_layout" value="1" checked onclick="handleRadioClick(this);"><?=lang("Laporan Penjualan Netto Urut No.Invoice")?></label>
                    <label class="radio"><input type="radio" id="rpt_layout2" class="rpt_layout" name="rpt_layout" value="2" onclick="handleRadioClick(this);"><?=lang("Laporan Penjualan Netto Per-Warehouse")?></label>
                    <label class="radio"><input type="radio" id="rpt_layout3" class="rpt_layout" name="rpt_layout" value="3" onclick="handleRadioClick(this);"><?=lang("Laporan Penjualan Netto Per-Sales")?></label>
                    <label class="radio"><input type="radio" id="rpt_layout4" class="rpt_layout" name="rpt_layout" value="4" onclick="handleRadioClick(this);"><?=lang("Laporan Penjualan Netto Per-Customer")?></label>
                    <label class="radio"><input type="radio" id="rpt_layout5" class="rpt_layout" name="rpt_layout" value="5" onclick="handleRadioClick(this);"><?=lang("Laporan Penjualan Netto Per-Kota")?></label>
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
                    <label><input id="fbl_is_vat_include" type="checkbox" name="fbl_is_vat_include" value="1"><?= lang("PPN Include") ?></label>
                </div>                  
            </div>
    </div>
</form>
<script type="text/javascript" info="init">
	$(function(){
		$("#fdt_inv_datetime").val(dateFormat("<?= date("Y-m-d")?>")).datepicker("update");
        $("#fdt_inv_datetime2").val(dateFormat("<?= date("Y-m-d")?>")).datepicker("update");
        $("#fdt_due_datetime").val(dateFormat("<?= date("Y-m-d")?>")).datepicker("update");					
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

				
        if (myRadio.value != "5"){
            $('#area_1').hide();
            $('#area_2').hide();

        }else{
            $('#area_1').show();
            $('#area_2').show();
        }

        ///if (myRadio.value == "3"){
            ///$('#select-sales').val("0");
            //$('#select-sales').hide();
            //$('#select-sales').prop('disabled',true);
        //}else{
            //$('#select-sales').show();
        ///}
        ///if (myRadio.value == "4"){
            ///$('#select-relations').empty();
            ///$('#select-relations').append('<option value="0">All</option>');
        ///}
        // for(var i=0; i<newArray.length; i++){
        //     alert(newArray[i].label);
        //     console.log(newArray[i].label);
        // }
        
        // currentValue = myRadio.value;
    }         
    $(function() {
        $("#select-relations").select2({
			width: '100%',
			ajax:{
				url: '<?=site_url()?>report/tr/invoice_netto/get_customers',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
                    items.push({
							"id" : "0",
							"text" : "All",					
						});
					$.each(data,function(index,value){
						items.push({
							"id" : value.fin_relation_id,
							"text" : value.fst_relation_name,					
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

        $("#select-provinces").select2({
            width: '100%',
            ajax: {
                url: '<?=site_url()?>report/tr/invoice_netto/get_provinces',
                dataType: 'json',
                delay: 250,
                processResults: function (data){
                    items = [];
                    data = data.data;
                    items.push({
							"id" : "0",
							"text" : "All",					
						});
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
					url: '<?=site_url()?>pr/relation/get_district/'+$("#select-provinces").val(),
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
		});

        $("#select-district").change(function(event){
			event.preventDefault();
			$('#select-subdistrict').val(null).trigger('change');
			$("#select-subdistrict").select2({
				width: '100%',
				ajax: {
					url: '<?=site_url()?>pr/relation/get_subdistrict/'+$("#select-district").val(),
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
		});

		$("#select-subdistrict").change(function(event){
			event.preventDefault();
			$('#select-village').val(null).trigger('change');
			$("#select-village").select2({
				width: '100%',
				ajax: {
					url: '<?=site_url()?>pr/relation/get_village/'+$("#select-subdistrict").val(),
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
		});

        $("#btnProcess").click(function(event) {
            event.preventDefault();
            App.blockUIOnAjaxRequest("Please wait while processing data.....");
            //data = new FormData($("#frmBranch")[0]);
            data = $("#rptInvoiceNetto").serializeArray();
            url = "<?= site_url() ?>report/tr/invoice_netto/process";
            
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
                        url = "<?= site_url() ?>report/tr/invoice_netto/generatereport";
                        //alert(url);
                        //$("iframe").attr("src",url);
                        $("#rptInvoiceNetto").attr('action', url);
                        $("#rptInvoiceNetto").attr('target', 'rpt_iframe');
                        $("#rptInvoiceNetto").submit();
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
			a.download = 'Laporan_Nilai Netto Faktur_Penjualan' + '.xls';
			a.click();                        			
			return;
		});      
    });

</script>

