<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?=base_url()?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<style type="text/css">
	.border-0{
		border: 0px;
	}
	td{
		padding: 2px; !important 		
	}
    .nav-tabs-custom>.nav-tabs>li.active>a{
        font-weight:bold;
        border-left-color: #3c8dbc;
        border-right-color: #3c8dbc;
        border-style:fixed;
    }
    .nav-tabs-custom>.nav-tabs{
        border-bottom-color: #3c8dbc;        
        border-bottom-style:fixed;
    }
	.is-promo{
		color:#ffffff;
		background-color:#63598c !important;
	}
	.form-group{
		margin-bottom: 5px;
	}
	.checkbox label, .radio label {
		font-weight:700;
	}
</style>
<?php
	echo $mdlPrint;	
?>

<section class="content-header">
	<h1><?=lang("Sales Order")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Sales Order") ?></a></li>
		<li class="active title"><?=$title?></li>
	</ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title title pull-left"><?=$title?></h3>
					<?php if ($mode != "VIEW") { ?>
					<div class="btn-group btn-group-sm  pull-right">					
						<a id="btnNew" class="btn btn-primary" href="#" title="<?=lang("Tambah Baru")?>"><i class="fa fa-plus" aria-hidden="true"></i></a>
						<a id="btnSubmitAjax" class="btn btn-primary" href="#" title="<?=lang("Simpan")?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
						<a id="btnPrint" class="btn btn-primary" href="#" title="<?=lang("Cetak")?>"><i class="fa fa-print" aria-hidden="true"></i></a>
						<a id="btnJurnal" class="btn btn-primary" href="#" title="<?=lang("Jurnal")?>" style="display:<?= $mode == "ADD" ? "none" : "inline-block" ?>"><i class="fa fa-align-left" aria-hidden="true"></i></a>
						<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
						<a id="btnClose" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
					</div>
					<?php } ?>
					
				</div>
				<!-- end box header -->
				<!-- form start -->
				<form id="frmSalesOrder" class="form-horizontal" action="<?=site_url()?>tr/sales_order/add" method="POST" enctype="multipart/form-data">			
					<div class="box-body">
						<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">			
						<input type="hidden" id="frm-mode" value="<?=$mode?>">
						<input type="hidden" class="form-control" id="fin_salesorder_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_salesorder_id" value="<?=$fin_salesorder_id?>" readonly>

						
						<div class="form-group">
							<label for="fst_salesorder_no" class="col-md-2 control-label"><?=lang("Sales Order No.")?> #</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="fst_salesorder_no" placeholder="<?=lang("Sales Order No.")?>" name="fst_salesorder_no" value="<?=$fst_salesorder_no?>" readonly>
								<div id="fst_salesorder_no_err" class="text-danger"></div>
							</div>
							
							<label for="fdt_salesorder_datetime" class="col-md-2 control-label"><?=lang("Sales Order Date")?> *</label>
							<div class="col-md-4">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control text-right datetimepicker" id="fdt_salesorder_datetime" name="fdt_salesorder_datetime"/>
								</div>
								<div id="fdt_salesorder_datetime_err" class="text-danger"></div>
								<!-- /.input group -->
							</div>						
						</div>

						<div class="form-group">						
							<label for="fst_curr_code" class="col-md-2 control-label"><?=lang("Mata Uang")?></label>
							<div class="col-md-4">
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
						
							<label for="fdc_exchange_rate_idr" class="col-md-2 control-label"><?=lang("Nilai Tukar IDR")?></label>
							<div class="col-md-1">
								<input type="text" class="form-control" id="fdc_exchange_rate_idr" name="fdc_exchange_rate_idr" style="width:50px" value="1" readonly/>
							</div>
							<label class="col-md-2 control-label" style="text-align:left;padding-left:0px"><?=lang("Rupiah")?> </label>
						</div>

						<div class="form-group">						
							<label for="select-relations" class="col-md-2 control-label"><?=lang("Customer")?></label>
							<div class="col-md-4">
								<select id="select-relations" class="form-control non-editable" name="fin_relation_id">
									<option value="0">-- <?=lang("select")?> --</option>
								</select>
								<div id="fin_relation_id_err" class="text-danger"></div>
							</div>
						
							<label for="select-relations" class="col-md-2 control-label"><?=lang("Term")?></label>
							<div class="col-md-1">
								<input type="text" class="form-control" id="fin_terms_payment" name="fin_terms_payment" style="width:50px"/>							
								<div id="fin_terms_payment_err" class="text-danger"></div>
							</div>
							<label class="col-md-2 control-label" style="text-align:left;padding-left:0px"><?=lang("Hari")?> </label>
						</div>


						<div class="form-group">
							
							<label for="select-sales" class="col-md-2 control-label"><?=lang("Sales")?></label>
							<div class="col-md-4">
								<select id="select-sales" class="form-control" name="fin_sales_id">
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

						<div class="form-group">
							<label for="select-warehouse" class="col-md-2 control-label"><?=lang("Warehouse")?></label>
							<div class="col-md-4">
								<select id="select-warehouse" class="form-control" name="fin_warehouse_id">
									<?php
										$warehouseList = $this->mswarehouse_model->getNonLogisticWarehouseList();
										foreach($warehouseList as $warehouse){
											echo "<option value='".$warehouse->fin_warehouse_id ."'>$warehouse->fst_warehouse_name</option>";
										}
									?>
								</select>
								<div id="fin_warehouse_id_err" class="text-danger"></div>
							</div>
							<div class="checkbox col-sm-6">
								<label><input id="fbl_is_hold" type="checkbox" name="fbl_is_hold" value="1"><?= lang("Hold Pengiriman") ?></label>
								<label style="margin-left:20px"><input id="fbl_is_vat_include" type="checkbox" name="fbl_is_vat_include" value="1"><?= lang("Include PPN") ?></label>
							</div>
							
						</div>

						<div class="form-group">
							<label for="fst_shipping_address" class="col-md-2 control-label"><?=lang("Alamat Pengiriman")?></label>
							<div class="col-md-10">
								<select class="select2 form-control" name="fin_shipping_address_id" id="fin_shipping_address_id" style="width:100%"></select>
								<div id="fst_shipping_address_err" class="text-danger"></div>
							</div>														
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label"></label>
							<div class="col-md-10">
								<textarea class="form-control" id="fst_shipping_address" style="width:100%" rows="5" readonly></textarea>
								<div id="fst_shipping_address_err" class="text-danger"></div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-12" style='text-align:right'>
								<button id="btn-add-detail" class="btn btn-primary btn-sm">
									<i class="fa fa-cart-plus" aria-hidden="true"></i>
									<?=lang("Tambah Item")?>
								</button>
							</div>
						</div>

						<table id="tblSODetails" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
						<div id="detail_err" class="text-danger"></div>

						<br>
						<div class="form-group">
							<div class="col-sm-6">	
								<div class="form-group">
									
									<div class="col-sm-12">
										<label for="fst_memo" class=""><?=lang("Memo")?></label>
										<textarea class="form-control" id="fst_memo" placeholder="<?= lang("Memo") ?>" name="fst_memo" rows="5" style="resize:none"></textarea>
										<div id="fst_memo_err" class="text-danger"></div>
									</div>
								</div>
		
							</div>
							<div class="col-sm-6">	
								<div class="form-group">
									<label for="sub-total" class="col-md-8 control-label"><?=lang("Sub total")?></label>
									<div class="col-md-4" style='text-align:right'>
										<input type="text" class="form-control text-right" id="sub-total" value="0" readonly>
									</div>
								</div>
								<div class="form-group">
									<label for="sub-total" class="col-md-6 control-label">%<?=lang("PPn")?></label>
									<div class="col-md-2" style='text-align:right'>
										<input type="text" class="form-control text-right" id="fdc_vat_percent" name="fdc_vat_percent" value="<?=$percent_ppn?>" >
									</div>
									<div class="col-md-4" style='text-align:right'>
										<input type="text" class="form-control text-right" id="fdc_vat_amount" name="fdc_vat_amount" value="0" readonly>	
									</div>
								</div>

								<div class="form-group">
									<label for="total" class="col-md-8 control-label"><?=lang("Total")?></label>
									<div class="col-md-4" style='text-align:right'>
										<input type="text" class="form-control text-right" id="total" value="0" readonly>
									</div>
								</div>
								<div class="form-group">
									<label for="total" class="col-md-5 control-label"><?=lang("Uang Muka")?></label>
									<label class="col-md-3 control-label checkbox-inline" style="font-weight:700">
										<input type="checkbox" name="fbl_dp_inc_ppn" id="fbl_dp_inc_ppn" value="1" checked><?=lang("Termasuk Ppn")?>
									</label>
									<div class="col-md-4" style='text-align:right'>
										<input type="text" class="money form-control text-right" id="fdc_downpayment" name="fdc_downpayment" value="0">
									</div>
								</div>
							</div>
							
						</div>
						
						

					</div>
					<!-- end box body -->

					<div class="box-footer text-right">
						
					</div>
					<!-- end box-footer -->
				</form>
        	</div>
    	</div>
	</div>
</section>

<!-- modal atau popup "ADD" -->
<div id="myModal" class="modal fade in" role="dialog" style="display: none">
	<div class="modal-dialog" style="display:table;width:650px">
		<!-- modal content -->
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
			<div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Add SO Detail")?></h4>
			</div>

			<div class="modal-body">
				<div class="row">
					<div class="col-md-12" >
						<div style="border:1px inset #f0f0f0;border-radius:10px;padding:5px">
							<fieldset style="padding:10px">
				
								<form id="form-detail" class="form-horizontal">
								<input type='hidden' id='fin_rec_id'/>
									<div class="form-group">
										<label for="select-items" class="col-md-3 control-label"><?=lang("Items")?></label>
										<div class="col-md-9">
											<select id="select-items" class="form-control"></select>
											<div id="ItemId_err" class="text-danger"></div>
										</div>
									</div>
									<div class="form-group">
										<label for="fst_custom_item_name" class="col-md-3 control-label"><?=lang("Custom Name")?></label>
										<div class="col-md-9">
											<input id="fst_custom_item_name" class="form-control"></select>
											<div id="fst_custom_item_name_err" class="text-danger"></div>
										</div>
									</div>

									<div class="form-group">
										<label for="select-unit" class="col-md-3 control-label"><?=lang("Unit")?></label>
										<div class="col-md-9">
											<select id="select-unit" name="fst_unit" class="disc-group form-control" style="width:100%"></select>
											<div id="fst_unit_err" class="text-danger"></div>
										</div>
									</div>

									<div class="form-group">
										<label for="fdb_qty" class="col-md-3 control-label"><?=lang("Qty")?></label>
										<div class="col-md-2">
											<input type="number" class="disc-group form-control text-right numeric" id="so-qty" value="1">
											<div id="fdb_qty_err" class="text-danger"></div>
										</div>

										<label for="fdc_price" class="col-md-2 control-label"><?=lang("Price")?></label>
										<div class="col-md-5">
											<input type="text" class="disc-group form-control text-right money" id="so-price" value="0">
											<div id="fdc_price_err" class="text-danger"></div>
										</div>
									</div>

									

									<div class="form-group">
										<label for="select-disc" class="col-md-3 control-label"><?=lang("Disc ++")?></label>
										<div class="col-md-9">
											<select id="select-disc" class="form-control text-right" name="fst_disc_item">
												<?php
													$discList = $this->msitemdiscounts_model->getItemDiscountList();
													foreach($discList as $disc){
														echo "<option value='$disc->fst_item_discount'>$disc->fst_item_discount</option>";
													}
												?>
											</select>
											<div id="fst_disc_item_err" class="text-danger"></div>
										</div>
									</div>

									<div class="form-group">
										<label for="fdc_disc_amount_per_item" class="col-md-3 control-label"><?=lang("Nilai Disc / Barang ")?></label>
										<div class="col-md-9">
											<input type="text" class="money disc-group form-control text-right" id="fdc_disc_amount_per_item">
											<div id="fdc_disc_amount_per_item_err" class="text-danger"></div>
										</div>
									</div>

									<div class="form-group">
										<label for="fst_memo_item" class="col-md-3 control-label"><?=lang("Memo")?></label>
										<div class="col-md-9">
											<textarea type="text" class="form-control" id="fst_memo_item" rows="3"></textarea>
											<div id="fst_memo_item_err" class="text-danger"></div>
										</div>
									</div>

								</form>

								<div class="modal-footer">
									<button id="btn-add-so-detail" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Add")?></button>
									<button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
								</div>
								<div id="dialog-info" class="alert alert-info">
									<a href="#" class="close" onclick="$('#dialog-info').hide()" aria-label="close">&times;</a>
									<div class="info-message">
										<strong>Info!</strong> 
									</div>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var ajxSel2Item = {
			url: '<?=site_url()?>tr/sales_order/get_data_item',
			dataType: 'json',
			delay: 500,
			data:function(params){
				//params.add_data =1;
				return params;
			},
			processResults: function (data) {
				data2 = [];
				$.each(data,function(index,value){
					data2.push({
						"id" : value.fin_item_id,
						"text" : value.ItemCodeName,
						"fst_item_name" : value.fst_item_name,
						"fst_item_code" : value.fst_item_code,
						"fst_max_item_discount" : value.fst_max_item_discount,
					});	
				});
				return {
					results: data2
				};
			},
			cache: true,
		}

		$(function(){
			$("#select-items").select2({
				width: '100%',				
				ajax: ajxSel2Item,
				minimumInputLength:2,
			}).on('select2:select', function(e){
				itemOnSelect(e);
			});	
			
			$("#select-disc").change(function(e){
				e.preventDefault();
				var amount = App.money_parse($("#so-price").val());
				var strDisc = $("#select-disc").val();
				var discAmount = App.calculateDisc(amount,strDisc);
				$("#fdc_disc_amount_per_item").val(App.money_format(discAmount));
				//$("#fdc_disc_amount").val(App.money_format(discAmount)); 
			});
		
			$(".disc-group").change(function(e){
				var price = App.money_parse($("#so-price").val());
				var discAmountPerItem = App.money_parse($("#fdc_disc_amount_per_item").val());

				var discPersen = 0;
				if (price >0){
					discPersen = (discAmountPerItem / price) * 100;
				} 
				App.addOptionIfNotExist("<option value='"+ discPersen + "' selected>" + App.money_format(discPersen) + "</option>","select-disc");
				$("#select-disc").trigger("change.select2");

				//$("#select-disc").change

				//var amount = $("#so-qty").val() * App.money_parse($("#so-price").val());
				//var strDisc = $("#select-disc").val();
				//var discAmount = App.calculateDisc(amount,strDisc);
				//$("#fdc_disc_amount").val(App.money_format(discAmount)); 


			});

			$("#btn-add-so-detail").click(function(event){
				event.preventDefault();					
				price = money_parse($("#so-price").val());			
				//selected_items = $("#select-items").select2('data')[0];
				selected_disc = $("#select-disc").val();
				selectedUnits = $("#select-unit").select2('data')[0];

				qty = $("#so-qty").val();
				price = money_parse($("#so-price").val());
				discPerItem = money_parse($("#fdc_disc_amount_per_item").val());

				if (discPerItem > price){
					alert("<?=lang("Nilai disc melebih nilai harga !")?>");
					return;
				}

				//amount = price * qty;
				//maxDisc = App.calculateDisc(amount,selectedItem.fst_max_item_discount);
				maxDisc = App.calculateDisc(price,selectedItem.fst_max_item_discount);

				if (maxDisc < discPerItem){
					var cfrm = confirm("<?= lang('Total discount melebih batas yang diperbolehkan !') ?>" + " (maxDisc:" +  maxDisc + "), tetap proses ?");
					if (!cfrm){
						return;
					}
					
				}

				

				data = {
					fin_rec_id:$("#fin_rec_id").val(),
					fin_promo_id:0,
					fin_item_id:selectedItem.id,
					fst_item_name:selectedItem.fst_item_name,
					fst_item_code:selectedItem.fst_item_code,
					fst_custom_item_name:$("#fst_custom_item_name").val(),
					fst_max_item_discount:selectedItem.fst_max_item_discount,
					fdb_qty: $("#so-qty").val(),
					fst_unit: selectedUnits.id,
					fdc_price : price,
					fst_disc_item : selected_disc,
					fdc_disc_amount_per_item: discPerItem,
					fst_memo_item: $("#fst_memo_item").val(),
					real_stock: selectedUnits.real_stock,
					marketing_stock: selectedUnits.marketing_stock,
					fdc_conv_to_basic_unit: selectedUnits.fdc_conv_to_basic_unit,
					fst_basic_unit: selectedUnits.fst_basic_unit,
				}

				t = $('#tblSODetails').DataTable();
				if(mode_so_detail == "EDIT"){
					edited_so_detail.data(data).draw(false);
					edited_so_detail = null;
				}else{
					t.row.add(data).draw(false);	
				}
				calculateTotal();
				clearDetailForm();
			});
		});

		function itemOnSelect(event) {
			var data = event.params.data;
			data = Array.isArray(data) ? data[0] : data;					
			selectedItem = data;

			dataCust = $("#select-relations").select2("data")[0];
			$("#fst_custom_item_name").val(data.fst_item_name);

			$('#select-unit').empty();
			$("#so-qty").val(1);
			$("#so-price").val(App.money_format(0));
			$("#select-disc").val(0);
			$("#fdc_disc_amount").val(App.money_format(0));
			
			App.blockUIOnAjaxRequest();
			$.ajax({
				url: '<?=site_url()?>master/item/get_selling_unit/' + data.id +"/" + dataCust.id +"/" + $("#select-warehouse").val(),
			}).done(function(resp){
				arrData = resp;
				arrSel2 =[];
				$.each(arrData,function(i,v){
					arrSel2.push({
						"id" : v.fst_unit,
						"text" : v.fst_unit,
						"price" :v.sellingPrice,
						"real_stock":v.real_stock,
						"marketing_stock":v.marketing_stock,
						"fdc_conv_to_basic_unit":v.fdc_conv_to_basic_unit,
						"fst_basic_unit":v.fst_basic_unit,
					});
				});

				$("#select-unit").select2({
					minimumResultsForSearch: -1,
					data:arrSel2
				}).on('select2:select', function (e) {
					dataUnit = e.params.data;
					$("#so-price").val(money_format(dataUnit.price));
					var infoStock = "Qty Real Stock : " + dataUnit.real_stock + " " + dataUnit.fst_basic_unit + " => " + dataUnit.real_stock / dataUnit.fdc_conv_to_basic_unit + " " +  dataUnit.text;
					infoStock += " | Qty Marketing Stock : " + dataUnit.marketing_stock + " " + dataUnit.fst_basic_unit + " => " + dataUnit.marketing_stock / dataUnit.fdc_conv_to_basic_unit + " " +  dataUnit.text;
					//$("#dialog-info .info-message").html("Qty Stock : " + dataUnit.real_stock + "  | " + " Qty Marketing Stock : " + dataUnit.marketing_stock);
					$("#dialog-info .info-message").html(infoStock);
					$("#dialog-info").show();							
				});
				
				if (selectedRow != null){
					$("#select-unit").val(selectedRow.fst_unit).trigger("change.select2");
					$("#select-unit").trigger({
						type:"select2:select",
						params:{
							data:{
								id : row.fst_unit,
								text : row.fst_unit,
								price: row.fdc_price,
								real_stock: row.real_stock,
								marketing_stock: row.marketing_stock,
								fdc_conv_to_basic_unit: row.fdc_conv_to_basic_unit,
								fst_basic_unit: row.fst_basic_unit,
							}
						}
					});

				}else{
					$("#select-unit").val(null).trigger("change.select2");				
				}

				

				App.fixedSelect2();
			});				
		}
		function getPromoDisc(){
			var finItemId = "";
			var fstUnit  ="";
			var fdbQty =0;
			App.getValueAjax({
				model:"mspromo_model",
				func:"",
				params:[]
			});
		}

	</script>
</div>
<?php
	echo $mdlJurnal;
	echo $mdlConfirmAuthorize;
	echo $mdlEditForm;
?>


<script type="text/javascript" info="binding_key">
	$(function(){
		$(document).bind('keydown', 'alt+d', function(){
			$("#btn-add-detail").trigger("click");
		});
	});
</script>

<script type="text/javascript" info="event">
	$(function(){

		$("#btnJurnal").click(function(e){
			e.preventDefault();
			MdlJurnal.showJurnalByRef("SO",$("#fin_salesorder_id").val());
		});

		$("#btnPrint").click(function(e){
			$("#mdlPrint").modal("toggle");
		});		

		$("#btnSubmitAjax").click(function(event){
			event.preventDefault();
			saveAjax(0);
		});

		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>tr/sales_order/add")
		});

		$("#btnDelete").confirmation({
			title:"<?=lang("Hapus data ini ?")?>",
			rootSelector: '#btnDelete',
			placement: 'left',
		});

		$("#btnDelete").click(function(e){
			e.preventDefault();
			deleteAjax(0);
		});

		$("#btnClose").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>tr/sales_order/lizt");
		});

		$("#fbl_is_vat_include").change(function(e){
			calculateTotal();
		});
		
		$("#btn-add-detail").click(function(event){
			event.preventDefault();
			if ($("#select-relations").val() == "0"){
				alert("<?=lang("Customer tidak boleh kosong !")?>");
				return;
			}
			mode_so_detail = "ADD";			
			$("#myModal").modal({
				backdrop:"static",
			});
			clearDetailForm();
			$("#dialog-info").hide();					
		});

		

		$("#fdc_vat_percent").change(function(e){
			e.preventDefault();
			calculateTotal();
		});

	});
</script>


<script type="text/javascript" info="define">	
	var edited_so_detail = null;
	var mode_so_detail = "ADD";
	var arrDetail;	
	var sel2Sales = [];
	var sel2Warehouse =[];
	var sel2Currencies =[];
	var current_pricing_group_id = 0;
	var selectedRow = null;
	var selectedItem = null;
	var cekPromo = 1;
	var confirmAuthorize = 0;

	
</script>

<script type="text/javascript" info="init">

	$(function(){
		$("#fdt_salesorder_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");		
		
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
			selectedCustomer = e.params.data;
			getShippingAddressList(selectedCustomer.id);
			$("#fin_terms_payment").val(selectedCustomer.fin_terms_payment);
			$("#select-sales").val(selectedCustomer.fin_sales_id).trigger("change.select2");
			$("#select-warehouse").val(selectedCustomer.fin_warehouse_id).trigger("change.select2");
			//current_pricing_group_id = selectedCustomer.current_pricing_group_id;			
		});

		$("#select-sales").select2();
		
			

		$('#tblSODetails').on('preXhr.dt', function ( e, settings, data ) {
		 	//add aditional data post on ajax call
		 	data.sessionId = "TEST SESSION ID";		
		}).on('init.dt',function(){
			$(".dataTables_scrollHeadInner").css("min-width","100%");
			$(".dataTables_scrollHeadInner > table").css("min-width","100%");
			$(".dataTables_scrollBody").css("position","static");
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[				
				{"title" : "id",sortable:false,data:"fin_rec_id",visible:true},
				{"title" : "promo",sortable:false,data:"fin_promo_id",visible:true},				
				{"title" : "Items","width": "200px",sortable:false,data:"fin_item_id",
					render: function(data,type,row){
						return row.fst_item_code + " - " + row.fst_custom_item_name;
					}
				},
				{"title" : "Custom Name","width": "0px",sortable:false,data:"fst_custom_item_name",visible:false},
				{"title" : "Qty",data:"fdb_qty",className:'text-right'},
				{"title" : "Unit",width:"50px",data:"fst_unit"},
				{"title" : "Price",width:"80px",
					data:"fdc_price",
					render: $.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					className:'text-right'
				},
				{"title" : "Disc (%)",width:"50px",
					data:"fst_disc_item",
					render: $.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					className:'text-right'
				},
				{"title" : "Disc / Item",width:"80px",data:"fdc_disc_amount_per_item",
					render: $.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					className:'text-right'
				},				
				{"title" : "Total",width:"80px",className:'text-right',
					render: function(data,type,row){
						return App.money_format((row.fdb_qty * row.fdc_price) - (row.fdb_qty * row.fdc_disc_amount_per_item));
						//$.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					}
					
				},
				{"title" : "Memo","width": "120px",data:"fst_memo_item"},
				{"title" : "Action","width": "60px",sortable:false,className:'dt-body-center text-center',
					render:function(data,type,row){
						var action = '<a class="btn-edit" href="#" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp;';
						action += '<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
						if (row.fin_promo_id == 0){
							return action;
						}else{
							return "";
						}

					}
				},
				
			],
			processing: true,
			serverSide: false,
			searching: false,
			lengthChange: false,
			paging: false,
			info:false,
			fnRowCallback: function( nRow, aData, iDisplayIndex ) {
				if (aData.fin_promo_id > 0){
					$(nRow).removeClass("odd");
					$(nRow).removeClass("even");					
					$(nRow).addClass("is-promo");
				}
			},
		}).on('draw',function(){
			$(".dataTables_scrollHeadInner").css("min-width","100%");
			$(".dataTables_scrollHeadInner > table").css("min-width","100%");			
		});

		$("#tblSODetails").on("click",".btn-delete",function(event){
			t = $('#tblSODetails').DataTable();
			var trRow = $(this).parents('tr');

			t.row(trRow).remove().draw();
			calculateTotal();
		});

		$("#tblSODetails").on("click",".btn-edit",function(event){
			event.preventDefault();			
			$("#myModal").modal({
				backdrop:"static",
			});

			t = $('#tblSODetails').DataTable();
			var trRow = $(this).parents('tr');
			mode_so_detail = "EDIT";
			edited_so_detail = t.row(trRow);
			row = edited_so_detail.data();
			selectedRow = row;	

			$("#fin_rec_id").val(row.fin_rec_id);
			
			var newOption = new Option(row.fst_item_code + "-" + row.fst_custom_item_name, row.fin_item_id, true, true);
			$('#select-items').append(newOption).trigger('change');	
			
			$('#select-items').trigger({
				type:"select2:select",
				params:{
					data:{
						"id" : row.fin_item_id,
						"text" : row.fst_item_code + " - " + row.fst_item_name,
						"fst_item_name" : row.fst_item_name,
						"fst_item_code" : row.fst_item_code,
						"fst_max_item_discount" : row.fst_max_item_discount,
					}
				}
			});
			
			
			$("#fst_custom_item_name").val(row.fst_custom_item_name);
			
			//$('#select-disc').val(row.fst_disc_item).trigger('change');	
			App.addOptionIfNotExist("<option value='"+row.fst_disc_item+"' selected>"+ App.money_format(row.fst_disc_item) +"</option>","select-disc");
			//$('#select-disc').val(row.fst_disc_item).trigger('change');
			$("#so-qty").val(row.fdb_qty);
			$("#so-price").val(money_format(row.fdc_price));
			$("#fdc_disc_amount_per_item").val(money_format(row.fdc_disc_amount_per_item));
			$("#fst_memo_item").val(row.fst_memo_item);				
		});

		init_form();
		App.fixedSelect2();		
	});

</script>

<script type="text/javascript" info="function">

	

	function saveAjax(confirmEdit){
		
		if (cekPromo == 1){
			getPromoItem();
			return;
		}

		if (confirmAuthorize == 0){
			checkAuthorize();
			return;
		}

		data = $("#frmSalesOrder").serializeArray();

		mode = $("#fin_salesorder_id").val() == 0 ? "ADD" : "EDIT"; //$("#frm-mode").val();
		if (mode == "ADD"){
			url =  "<?= site_url() ?>tr/sales_order/ajx_add_save/";
		}else{
			url =  "<?= site_url() ?>tr/sales_order/ajx_edit_save/";			
			if (confirmEdit == 0 && mode != "ADD"){
				MdlEditForm.saveCallBack = function(){
					saveAjax(1);
				};		
				MdlEditForm.show();
				return;
			}

			data.push({
				name : "fin_user_id_request_by",
				value: MdlEditForm.user
			});
			data.push({
				name : "fst_edit_notes",
				value: MdlEditForm.notes
			});

		}

		
		detail = new Array();		

		t = $('#tblSODetails').DataTable();

		t.rows(function(idx,data,node){
			return data.fin_promo_id != 0 ;
		}).remove().draw();

		datas = t.data();
		$.each(datas,function(i,v){
			detail.push(v);
		});

		data.push({
			name:"detail",
			value: JSON.stringify(detail)
		});		


		

		//var formData = new FormData($('form')[0])
		App.blockUIOnAjaxRequest("Please wait while saving data.....");
		$.ajax({
			type: "POST",
			//enctype: 'multipart/form-data',
			url: url,
			data: data,
			timeout: 600000,
			success: function (resp) {				
				if (resp.message != "")	{
					$.alert({
						title: 'Message',
						content: resp.message,
						buttons : {
							OK : function(){
								if(resp.status == "SUCCESS"){
									$("#btnNew").trigger("click");
									return;
								}
							},
						}
					});
				}

				if(resp.status == "VALIDATION_FORM_FAILED" ){
					//Show Error
					errors = resp.data;
					for (key in errors) {
						$("#"+key+"_err").html(errors[key]);
					}
				}else if(resp.status == "SUCCESS") {
					data = resp.data;
					$("#fin_salesorder_id").val(data.insert_id);
					//Clear all previous error
					$(".text-danger").html("");
				}
			},
			error: function (e) {
				$("#result").text(e.responseText);
				$("#btnSubmit").prop("disabled", false);
			},
		}).always(function(){
			
		});

	}


	function getPromoItem(){
		var t = $('#tblSODetails').DataTable();
		t.rows(function(idx,data,node){
			return data.fin_promo_id != 0 ;
		}).remove().draw();
		datas = t.data();
		detail = new Array();
		$.each(datas,function(i,v){
			detail.push(v);
		});

		dataPost = $("#frmSalesOrder").serializeArray();
		dataPost.push({
			name:"detail",
			value: JSON.stringify(detail)
		});
		
		
		App.blockUIOnAjaxRequest();
		$.ajax({
			url:"<?=site_url()?>tr/sales_order/get_promo_item",
			data:dataPost,
			method:"POST",

		}).done(function(resp){
			if (resp.message != ""){
				alert(resp.message);				
			}
			if (resp.status == "SUCCESS"){
				cekPromo = 0;
				var promotionList = resp.data;
				if(promotionList.length == 0){
					saveAjax(0);
				}else{
					periksa = confirm("<?=lang("Transaksi ini mendapatkan item promo, periksa promo ?")?>");
					if (periksa){
						$.each(promotionList,function (i,promotion){
							if (promotion.fin_promo_item_id != null && promotion.fin_promo_item_id != ""){
								var dataTbl = {
									fin_rec_id:0,
									fin_promo_id:promotion.fin_promo_id,
									fin_item_id:promotion.fin_promo_item_id,
									fst_item_name:promotion.fst_item_name,
									fst_item_code:promotion.fst_item_code,
									fst_custom_item_name: promotion.fst_item_name,
									fst_max_item_discount:0,
									fdb_qty: promotion.fdb_promo_qty,
									fst_unit: promotion.fst_promo_unit,
									fdc_price : 1,
									fst_disc_item : "100",
									fdc_disc_amount_per_item: 1,
									fst_memo_item: promotion.fst_promo_name,
									total: 0,
									real_stock: 1,
									marketing_stock: 1,
									fdc_conv_to_basic_unit: 1,
									fst_basic_unit: "",
								}
								t.row.add(dataTbl).draw(false);
							}

							if (promotion.fst_other_prize != null && promotion.fst_other_prize != ""){
								var dataTbl = {
									fin_rec_id:0,
									fin_promo_id:promotion.fin_promo_id,
									fin_item_id:0,
									fst_item_name:promotion.fst_other_prize,
									fst_item_code:"PRZ",
									fst_custom_item_name: promotion.fst_other_prize,
									fst_max_item_discount:100,
									fdb_qty: 1,
									fst_unit: "PCS",
									fdc_price : 1,
									fst_disc_item : "100",
									fdc_disc_amount_per_item: 1,
									fst_memo_item: promotion.fst_promo_name,
									total: 0,
									real_stock: 0,
									marketing_stock: 0,
									fdc_conv_to_basic_unit: 1,
									fst_basic_unit: "",
								}
								t.row.add(dataTbl).draw(false);
							}

							if (promotion.fdc_cashback > 0 ){
								var dataTbl = {
									fin_rec_id:0,
									fin_promo_id:promotion.fin_promo_id,
									fin_item_id:1,
									fst_item_name:"<?=lang("Voucher cashback promotion")?>" + "(" +  App.money_format(promotion.fdc_cashback) + ")",
									fst_item_code:"VCR",
									fst_custom_item_name: "<?=lang("Voucher cashback promotion")?>" + "(" +  App.money_format(promotion.fdc_cashback) + ")",
									fst_max_item_discount:0,
									fdb_qty: 1,
									fst_unit: "LBR",
									fdc_price : 1,
									fst_disc_item : "100",
									fdc_disc_amount_per_item: 1,
									fst_memo_item: promotion.fst_promo_name,
									total: 0,
									real_stock: 0,
									marketing_stock: 0,
									fdc_conv_to_basic_unit: 1,
									fst_basic_unit: "LBR",
								}
								t.row.add(dataTbl).draw(false);
							}						
						});

					}else{
						saveAjax(0);
					}
				}
			}
		});
	}

	function checkAuthorize(){
		var t = $('#tblSODetails').DataTable();
		t.rows(function(idx,data,node){
			return data.fin_promo_id != 0 ;
		}).remove().draw();
		datas = t.data();
		detail = new Array();
		$.each(datas,function(i,v){
			detail.push(v);
		});

		dataPost = $("#frmSalesOrder").serializeArray();
		dataPost.push({
			name:"detail",
			value: JSON.stringify(detail)
		});
		
		App.blockUIOnAjaxRequest();
		$.ajax({
			url:"<?=site_url()?>tr/sales_order/check_authorization",
			data:dataPost,
			method:"POST",
		}).done(function(resp){
			
			if (resp.message != ""){
				alert(resp.message);
			}
			
			if (resp.status ="SUCCESS"){
				data = resp.data;
				if (data.need_authorize == true){
					var authorizeList = data.authorize_list;
					messageList = [];

					//Default
					$.each(authorizeList.default,function(i,item){
						messageList.push("");
					});

					//OutOfStock
					$.each(authorizeList.out_of_stock,function(i,item){
						var strMessage = "Out Of Stock : " + item.fst_item_name + " ready :" + App.money_format(item.stock) + " request :" + item.fdb_qty + " " + item.fst_unit;
						messageList.push(strMessage);						
					});

					//Over max Disc
					$.each(authorizeList.over_max_disc,function(i,item){			
						var strMessage = "Over Max Disc : " + item.fst_item_name + " " + item.fdb_qty + " " + item.fst_unit  + " | Max Disc :" + App.money_format(item.fdc_max_disc_amount) + " Current Disc :" + App.money_format(item.fdc_disc_amount_per_item);
						messageList.push(strMessage);
					});
					

					//over_credit_limit
					if (authorizeList.over_credit_limit != null){
						var limit = authorizeList.over_credit_limit;
						var strMessage = "Total Credit Limit : " + App.money_format(limit.maxCreditLimit);
						strMessage += " | Invoice Outstanding :" + App.money_format(limit.piutangOutstanding); 
						strMessage += " | Check / Giro Pending :" + App.money_format(limit.totalChequePending);
						strMessage += " | Sales Order Pending :" + App.money_format(limit.totalSOPending);						
						strMessage += " | Current Plafon : " + App.money_format(limit.sisaPlafon);
						strMessage += " | Current Order :" + App.money_format(limit.currentOrder);
						messageList.push(strMessage);
					}

					//over_tolerance_invoice
					$.each(authorizeList.over_tolerance_invoice,function(i,item){			
						//messageList[] = "Over Max Disc : " + item.fst_item_name + " " + item.fdb_qty + " " + item.fst_unit  + " | Max Disc :" + App.money_format(item.fdc_max_disc_amount) + " Current Disc :" + App.money_format(item.fdc_disc_amount);
					});

					MdlConfirmAuthorize.show(messageList);
					MdlConfirmAuthorize.authorizeCallback = function(){
						confirmAuthorize = 1;
						saveAjax(0);
					};
				}else{
					confirmAuthorize = 1;
					saveAjax(0);
				}
			}
		});
	}

	function getShippingAddressList(customerId,selectedShippingAddressId){
		blockUIOnAjaxRequest("<div><?=lang("Please wait....")?></div>");		
		$("#fin_shipping_address_id").empty();
		$("#fst_shipping_address").val("");
		$.ajax({
			//url: '<=site_url()?>pr/relation/select_shipping_address/' + customerId,
			url:"<?=site_url()?>select_data/get_shipping_address/" + customerId,
		}).done(function(resp){
			arrShippingAddress = resp.data;
			arrSelect = [];
			defaultValue = null;

			$.each(arrShippingAddress,function(i,v){
				obj = {
					id: v.fin_shipping_address_id,
					text: v.fst_name,
					fst_shipping_address:v.fst_shipping_address
				};
				arrSelect.push(obj);
				if (obj.id == selectedShippingAddressId){
					defaultValue = obj;
				}
			});
			if(arrSelect.length > 0){



				defaultValue = (defaultValue == null) ? arrSelect[0] : defaultValue;
				$("#fin_shipping_address_id").select2({
					minimumResultsForSearch: -1,
					data: arrSelect
				}).on("select2:select",function(e){
					data = e.params.data;
					$("#fst_shipping_address").val(data.fst_shipping_address);
				});

				
				
				/*
				$("#fin_shipping_address_id").val(defaultValue.id).trigger("change");
				*/
				$("#fin_shipping_address_id").trigger({
					type: 'select2:select',
					params: {
						data: defaultValue
					}
				});
				App.fixedSelect2();


			}
		});
	}

	function fillForm(resp){
		resp = resp.data;

		$.each(resp.sales_order, function(name, val){
			var $el = $('[name="'+name+'"]'),
				type = $el.attr('type');
			switch(type){
				case 'checkbox':
					$el.filter('[value="' + val + '"]').attr('checked', 'checked');
					break;
				case 'radio':
					$el.filter('[value="' + val + '"]').attr('checked', 'checked');
					break;
				default:
					$el.val(val);
			}
		});


		//Customer
		var arrCust = [];
		var currCust = {
			"id" : resp.sales_order.fin_relation_id,
			"text" : resp.sales_order.fst_relation_name,
			"fin_sales_id" : resp.sales_order.fin_sales_id,
			"fin_shipping_address_id":resp.sales_order.fin_shipping_address_id,
			"fin_warehouse_id":resp.sales_order.fin_warehouse_id,
			"fin_terms_payment":resp.sales_order.fin_terms_payment							
		}
		arrCust.push(currCust);
		//var newOption = new Option(resp.sales_order.fst_relation_name, resp.sales_order.fin_relation_id, true, true);
		//$('#select-relations').append(newOption).trigger('change');
		//current_pricing_group_id = resp.sales_order.current_pricing_group_id;
		$('#select-relations').empty();
		$('#select-relations').select2({
			minimumResultsForSearch: -1,
			width: "100%",
			data:arrCust
		});

		getShippingAddressList(currCust.id,currCust.fin_shipping_address_id);

		$('#select-sales').val(resp.sales_order.fin_relation_id).trigger('change');
		$('#select-sales').val(resp.sales_order.fin_sales_id).trigger('change');
		$('#select-warehouse').val(resp.sales_order.fin_warehouse_id).trigger('change');

		SODetails = resp.so_details;

		dataItem = [];
		$.each(SODetails, function(idx, detail){
			data = {
				fin_rec_id:detail.fin_rec_id,
				fin_promo_id:detail.fin_promo_id,
				fin_item_id:detail.fin_item_id,
				ItemCode:detail.fst_item_code,
				ItemName:detail.fst_item_name,
				fst_custom_item_name:detail.fst_custom_item_name,
				fdb_qty:detail.fdb_qty,
				fst_unit:detail.fst_unit,
				fdc_price:detail.fdc_price,
				fst_disc_item:detail.fst_disc_item,
				fdc_disc_amount:detail.fdc_disc_amount,
				fst_memo_item:detail.fst_memo_item,
				total:detail.fdb_qty * detail.fdc_price - detail.fdc_disc_amount,
			}

			t = $('#tblSODetails').DataTable();			
			t.row.add(data).draw(false);

			//set Data Item select2		
			tmp = {
				"id" : detail.fin_item_id,
				"text" : detail.fst_item_code + "-" + detail.fst_item_name,
				"fst_item_name" : detail.fst_item_name,
				"fst_item_code" : detail.fst_item_code,
				"fst_max_item_discount" : detail.fst_max_item_discount
			}
			dataItem.push(tmp);
		});

		$("#select-items").select2({
			width:"100%",
			data:dataItem,
			ajax:ajxSel2Item
		});

		App.fixedSelect2();
		//$(".non-editable").prop('disabled', true);
		calculateTotal();		
		$("#fdt_salesorder_datetime").val(dateTimeFormat(resp.sales_order.fdt_salesorder_datetime)).datetimepicker('update');
	
	}

	function clearDetailForm(){
		$("#fin_rec_id").val(0);
		$('#select-items').val(null).trigger('change.select2');
		$('#select-items').focus();
		$("#fst_custom_item_name").val("");
		$('#select-disc').val("0").trigger('change');
		$('#select-unit').val(null).trigger('change');
		$("#fin-detail-id").val(0);
		$("#so-qty").val(1);
		$("#so-price").val(0);
		$("#fdc_disc_amount").val(0);
		$("#fst_memo_item").val("");
		selectedRow = null;
	}
	
	function calculateTotal(){
		//Setiap terjadi perubahan total  harus cek promo lagi dan konfirmasi authorize lagi
		cekPromo = 1; 
		confirmAuthorize = 0;	

		t = $('#tblSODetails').DataTable();
		datas = t.data();
		ttlBfDisc = 0;
		ttlDisc = 0;

		$.each(datas,function(i,v){
			ttlBfDisc += v.fdb_qty * v.fdc_price;
			ttlDisc +=  v.fdc_disc_amount_per_item * v.fdb_qty;
		});

		if ($("#fbl_is_vat_include").prop('checked')){
			total = ttlBfDisc - ttlDisc;			
			vat = $("#fdc_vat_percent").val() * 1;
			vat = 1 + (vat/100);
			subTotal = total / vat;
			vat = ($("#fdc_vat_percent").val() /100) * subTotal;	
		}else{
			subTotal= ttlBfDisc - ttlDisc;
			vat = ($("#fdc_vat_percent").val() /100) * subTotal;
			total = subTotal + vat;
		}

		$("#sub-total").val(money_format(subTotal));	
		$("#fdc_vat_amount").val(money_format(vat));
		$("#total").val(money_format(total));		
	}

	function init_form(){
		if ( $("#fin_salesorder_id").val() != 0 ){
			//fetch data
			App.blockUIOnAjaxRequest();
			$.ajax({
				url:"<?=site_url()?>tr/sales_order/fetch_data/" + $("#fin_salesorder_id").val(),				
			}).done(function(resp){
				if(resp.message != ""){
					alert(resp.message);
				}
				if (resp.status == "SUCCESS"){
					//Fill Forms
					dataH = resp.data.sales_order;
					detailData = resp.data.so_details;					
					App.autoFillForm(dataH);
					$("#fdt_salesorder_datetime").val(dateTimeFormat(dataH.fdt_salesorder_datetime)).datetimepicker("update");	
					App.addOptionIfNotExist("<option value='"+ dataH.fin_relation_id +"' selected>" + dataH.fst_relation_name + "</option>","select-relations");
					$("#select-sales").trigger("change.select2");
					getShippingAddressList(dataH.fin_relation_id,dataH.fin_shipping_address_id);

					details = [];

					$.each(detailData , function(i,detail){
						data = {
							fin_rec_id: detail.fin_rec_id,
							fin_promo_id:detail.fin_promo_id,
							fin_item_id:detail.fin_item_id,
							fst_item_name:detail.fst_item_name,
							fst_item_code:detail.fst_item_code,
							fst_custom_item_name:detail.fst_custom_item_name,
							fst_max_item_discount:detail.fst_max_item_discount,
							fdb_qty: detail.fdb_qty,
							fst_unit: detail.fst_unit,
							fdc_price : detail.fdc_price,
							fst_disc_item : detail.fst_disc_item,
							fdc_disc_amount_per_item: detail.fdc_disc_amount_per_item,
							fst_memo_item: detail.fst_memo_item,
							real_stock: detail.real_stock,
							marketing_stock: detail.marketing_stock,
							fst_basic_unit: detail.fst_basic_unit,
							fdc_conv_to_basic_unit: detail.fdc_conv_to_basic_unit,							
						}
						details.push(data);
					});

					t = $('#tblSODetails').DataTable();			
					//t.data(details).draw(false);
					t.rows.add(details).draw(false);
					calculateTotal();
					//App.addOptionIfNotExist("<option value='"+ dataH.fin_shipping_address_id +"' selected>" + dataH.fst_address_name + "</option>","fin_shipping_address_id");
					//$("#fst_shipping_address").val(dataH.fst_shipping_address);

					

				}
			})
		}


	}


	function deleteAjax(confirmDelete){

		if (confirmDelete == 0){
			MdlEditForm.saveCallBack = function(){
				deleteAjax(1);
			};		
			MdlEditForm.show();
			return;
		}
		var dataSubmit = [];		
		dataSubmit.push({
			name : "<?=$this->security->get_csrf_token_name()?>",
			value: "<?=$this->security->get_csrf_hash()?>",
		});
		dataSubmit.push({
			name : "fin_user_id_request_by",
			value: MdlEditForm.user
		});
		dataSubmit.push({
			name : "fst_edit_notes",
			value: MdlEditForm.notes
		});



		App.blockUIOnAjaxRequest("<h5>Deleting ....</h5>");
		$.ajax({
			url:"<?= site_url() ?>tr/sales_order/delete/" + $("#fin_salesorder_id").val(),
			data:dataSubmit,
			method:"POST",
		}).done(function(resp){
			//consoleLog(resp);
			$.unblockUI();
			if (resp.message != "")	{
				$.alert({
					title: 'Message',
					content: resp.message,
					buttons : {
						OK : function() {
							if (resp.status == "SUCCESS") {
								window.location.href = "<?= site_url() ?>tr/sales_order/lizt";
								//return;
							}
						},
					}
				});
			}

			if(resp.status == "SUCCESS") {
				data = resp.data;
				$("#fin_salesorder_id").val(data.insert_id);

				//Clear all previous error
				$(".text-danger").html("");
				// Change to Edit mode
				$("#frm-mode").val("EDIT");  //ADD|EDIT
				$('#fst_salesorder_name').prop('readonly', true);
			}
		});
	}

</script>

<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

