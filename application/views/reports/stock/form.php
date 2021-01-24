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
			<label for="select-lineBusiness" class="col-md-2 control-label"><?=lang("Warehouse")?></label>
			<div class="col-md-4">
				<select class="form-control select2" id="fin_warehouse_id" name="fin_warehouse_id">
				<?php
					$listWarehouse = $this->mswarehouse_model->getALLWarehouseList();
					foreach($listWarehouse as $wh){
						echo "<option value='$wh->fin_warehouse_id'>$wh->fst_warehouse_name</option>";
					}
				?>
				</select>
			</div>
			<label for="select-items" class="col-sm-2 control-label"><?=lang("Item")?></label>
			<div class="col-sm-4">
				<select id="select-items" class="form-control non-editable" name="fin_item_id"></select>
				<div id="fin_item_id_err" class="text-danger"></div>
			</div>
		</div>  
		<div class="form-group row">
			<label for="select-lineBusiness" class="col-md-2 control-label"><?=lang("From")?></label>
			<div class="col-md-4">
				<input type="text" class="form-control datepicker text-right" id="fdt_from" name="fdt_from" />
			</div>
			<label for="select-lineBusiness" class="col-md-2 control-label"><?=lang("To")?></label>
			<div class="col-md-4">
			<input type="text" class="form-control datepicker text-right" id="fdt_to" name="fdt_to" />
			</div>
		</div>		        
	
		<div class="form-group row">
			<label for="rpt_layout" class="col-sm-2 control-label"><?=lang("Report Layout")?></label>
			<div class="col-sm-4">								
				<label class="radio"><input type="radio" id="rpt_layout1" class="rpt_layout" name="rpt_layout" value="1" checked onclick="handleRadioClick(this);"><?=lang("Laporan Kartu Stock")?></label>
				<label class="radio"><input type="radio" id="rpt_layout2" class="rpt_layout" name="rpt_layout" value="2" onclick="handleRadioClick(this);"><?=lang("Laporan Kartu stock dengan Nilai transaksi")?></label>
				<label class="radio"><input type="radio" id="rpt_layout3" class="rpt_layout" name="rpt_layout" value="3" onclick="handleRadioClick(this);"><?=lang("Laporan Mutasi Persediaan")?></label>
				<label class="radio"><input type="radio" id="rpt_layout4" class="rpt_layout" name="rpt_layout" value="4" onclick="handleRadioClick(this);"><?=lang("Laporan Mutasi Persediaan Ringkas")?></label>
				<label class="radio"><input type="radio" id="rpt_layout5" class="rpt_layout" name="rpt_layout" value="5" onclick="handleRadioClick(this);"><?=lang("Laporan Persediaan Akhir + Nilai")?></label>
				<label class="radio"><input type="radio" id="rpt_layout6" class="rpt_layout" name="rpt_layout" value="6" onclick="handleRadioClick(this);"><?=lang("Laporan Persediaan Semua Gudang")?></label>
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
			placeholder:{
                id: '0', // the value of the option
                text: 'All'
            },
			allowClear: true,
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
			showItemGroup(false,function(node){
				//consoleLog(node);                
				$("#select-GroupItemId").empty();
				var newOption = new Option(node.text,node.id, false, false);
				$('#select-GroupItemId').append(newOption).trigger('change');
			});
		});

        $("#select-items").select2({
            minimumInputLength: 2,
            placeholder:{
                id: '0', // the value of the option
                text: 'All'
            },
            allowClear: true,
            ajax:{
                delay: 250,
                url: "<?=site_url()?>/report/invoice/ajxListItem",
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
			data = $("#rptItems").serializeArray();			
			url = "<?= site_url() ?>report/stock/process";
			
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
						//url = "<= site_url() ?>report/stock/generateexcel";
						url = "<?= site_url() ?>report/stock/generatereport";
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
			a.download = 'Laporan_stock' + '.xls';
			a.click();                        			
			return;
		});        
	});

</script>

