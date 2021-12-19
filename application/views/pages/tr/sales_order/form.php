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
						<a id="btnPromo" class="btn btn-primary" href="#" title="<?=lang("Cek Promo")?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
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

						<div class="form-group hide" >						
							<label for="fst_curr_code" class="col-md-2 control-label"><?=lang("Mata Uang")?></label>
							<div class="col-md-4">
								<select id="fst_curr_code" class="form-control" name="fst_curr_code">
									<?php										
										$defaultCurr = $this->mscurrencies_model->getDefaultCurrencyCode();
										echo "<option value='$defaultCurr' selected>$defaultCurr</option>";
									?>
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
							<label for="fin_relation_id" class="col-md-2 control-label"><?=lang("Customer")?></label>
							<div class="col-md-10">
								<select id="fin_relation_id" class="form-control non-editable" name="fin_relation_id"></select>
								<div id="fin_relation_id_err" class="text-danger"></div>
							</div>						
						</div>

						<div class="form-group">
							<label for="fst_shipping_address" class="col-md-2 control-label"><?=lang("Alamat Pengiriman")?></label>
							<div class="col-md-10">
								<select class="select2 form-control" name="fin_shipping_address_id" id="fin_shipping_address_id" style="width:100%"></select>
								<div id="fin_shipping_address_id_err" class="text-danger"></div>
							</div>														
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label"></label>
							<div class="col-md-10">
								<textarea class="form-control" id="fst_shipping_address" style="width:100%" rows="3" readonly></textarea>
								<div id="fst_shipping_address_err" class="text-danger"></div>
							</div>
						</div>

						
						<div class="form-group">
							<label for="select-relations" class="col-md-2 control-label"><?=lang("Term of payment")?></label>
							<div class="col-md-1" style="padding-right:5px">
								<input type="text" class="form-control text-right" id="fin_terms_payment" name="fin_terms_payment" value="1"/>							
								<div id="fin_terms_payment_err" class="text-danger"></div>
							</div>
							<label class="col-md-1 control-label" style="text-align:left;padding-left:0px"><?=lang("Hari")?> </label>
							
							<label for="fin_sales_id" class="col-md-2 control-label"><?=lang("Sales")?></label>
							<div class="col-md-6">
								<select id="fin_sales_id" class="form-control" name="fin_sales_id">
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
							<label for="fin_warehouse_id" class="col-md-2 control-label"><?=lang("Warehouse")?></label>
							<div class="col-md-8">
								<select id="fin_warehouse_id" class="form-control" name="fin_warehouse_id">
									<?php
										$warehouseList = $this->mswarehouse_model->getNonLogisticWarehouseList();
										foreach($warehouseList as $warehouse){
											echo "<option value='".$warehouse->fin_warehouse_id ."'>$warehouse->fst_warehouse_name</option>";
										}
									?>
								</select>
								<div id="fin_warehouse_id_err" class="text-danger"></div>
							</div>							
							<label  class="col-md-2 control-label"><input id="fbl_is_hold" type="checkbox"  name="fbl_is_hold"> <?= lang("Hold Pengiriman") ?></label>
						</div>
						

						<div class="form-group" >
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
									<label for="sub-total" class="col-md-8 control-label"><?=lang("Sub total / DPP")?></label>
									<div class="col-md-4" style='text-align:right'>
										<input type="text" class="form-control text-right" id="sub-total" value="0" readonly>
									</div>
								</div>
								<div class="form-group ppn-group" >
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
				<div id="dialog-info" class="alert alert-info" style="display:none">
					<a href="#" class="close" onclick="$('#dialog-info').hide()" aria-label="close">&times;</a>
					<div class="info-message">
						<strong>Info!</strong> 
					</div>
				</div>

				<form class="form-horizontal">
					<div class="form-group">
						<label for="dfin_item" class="col-md-3 control-label"><?=lang("Items")?></label>
						<div class="col-md-9">
							<select id="dfin_item_id" class="form-control price-control"></select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?=lang("Custom Name")?></label>
						<div class="col-md-9">
							<input id="dfst_custom_item_name" class="form-control"></select>
						</div>
					</div>

					<div class="form-group">
						<label for="dfst_unit" class="col-md-3 control-label"><?=lang("Unit")?></label>
						<div class="col-md-9">
							<select id="dfst_unit" name="fst_unit" class="disc-group form-control price-control" style="width:100%"></select>
							<div id="fst_unit_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?=lang("Qty")?></label>
						<div class="col-md-2">
							<input type="number" class="disc-group price-control form-control text-right numeric" id="dfdb_qty" value="1">
						</div>

						<label class="col-md-2 control-label"><?=lang("Price")?></label>
						<div class="col-md-5">
							<input type="text" class="disc-group form-control text-right money" id="dfdc_selling_price" value="0">							
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label"><?=lang("Disc ++")?></label>
						<div class="col-md-9">
							<select id="dfst_disc_item" class="form-control text-right">
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
						<label  class="col-md-3 control-label"><?=lang("Nilai Disc / Barang ")?></label>
						<div class="col-md-9">
							<input type="text" class="money disc-group form-control text-right" id="dfdc_disc_amount_per_item" readonly>
						</div>
					</div>

					<div class="form-group">
						<label for="fst_memo_item" class="col-md-3 control-label"><?=lang("Memo")?></label>
						<div class="col-md-9">
							<textarea type="text" class="form-control" id="dfst_memo_item" rows="3"></textarea>
							<div id="dfst_memo_item_err" class="text-danger"></div>
						</div>
					</div>

				</form>				
													
			</div>
			<div class="modal-footer">
				<button id="btn-add-so-detail" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Add")?></button>
				<button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
					
			</div>
		</div>
	</div>

	<script type="text/javascript" info="define">
		
		var myModal = {
			selectedItem:null,
			selectedUnit:null,
			show:function(){
				if (selectedRow != null){
					var data = selectedRow.data();					
					
					myModal.selectedItem ={
						"id" : data.fin_item_id,
						"text" : data.fst_item_code  + " - " + data.fst_item_name,
						"fst_item_name" : data.fst_item_name,
						"fst_item_code" : data.fst_item_code,
						"fst_max_item_discount" : data.fst_max_item_discount,
					};	

					console.log(myModal.selectedItem);

					App.addOptionIfNotExist("<option value='"+data.fin_item_id+"' selected>"+data.fst_item_code + " - " + data.fst_item_name +"</option>","dfin_item_id");
					$("#dfin_item_id").trigger("change");

					$("#dfst_custom_item_name").val(data.fst_custom_item_name);
					App.addOptionIfNotExist("<option value='"+ data.fst_unit +"' selected>"+ data.fst_unit +"</option>","dfst_unit");
					$("#dfst_unit").trigger("change");
					$("#dfdb_qty").val(money_format(data.fdb_qty));
					$("#dfdc_selling_price").val(money_format(data.fdc_price));
					$("#dfst_disc_item").val(data.fst_disc_item);
					$("#dfdc_disc_amount_per_item").val(money_format(data.fdc_disc_amount_per_item));
					$("#dfst_memo_item").val(data.fst_memo_item);					
				}else{
					myModal.clear();
				}

				$("#myModal").modal("show");
			},
			hide:function(){
				$("#myModal").modal("hide");
			},
			clear:function(){				
				$("#dfin_item_id").val(null).trigger("change");				
				$("#dfst_custom_item_name").val("");
				$("#dfst_unit").val(null).trigger("change");
				$("#dfdb_qty").val("1");
				$("#dfdc_selling_price").val(App.money_format(0));
				$("#dfst_disc_item").val(0);
				$("#dfdc_disc_amount_per_item").val(App.money_format(0));
				$("#dfst_memo_item").val("");	
				$("#dialog-info").hide();			
				selectedRow = null;
			}
		}
	</script>

	<script type="text/javascript" info="init">
		$(function(){
			$("#dfin_item_id").select2({
				width: '100%',	
				cache: true,
				minimumInputLength:2,			
				ajax: {
					url: '<?=site_url()?>tr/sales_order/ajxGetDataItem',
					dataType: 'json',
					delay: 500,
					processResults: function (resp) {
						if (resp.messages != ""){
							alert(resp.messages);
						}
						if (resp.status == "SUCCESS"){
							data = resp.data;
							data2 = [];
							$.each(data,function(index,value){
								
								data2.push({
									"id" : value.fin_item_id,
									"text" : value.fst_item_code  + " - " + value.fst_item_name,
									"fst_item_name" : value.fst_item_name,
									"fst_item_code" : value.fst_item_code,
									"fst_max_item_discount" : value.fst_max_item_discount,
								});	
							});
							return {
								results: data2
							};
						}											
					},					
				}
			}).on('select2:select', function(e){
				myModal.selectedItem = e.params.data;
				$("#dfst_unit").val(null).trigger("change");
				$("#dfst_custom_item_name").val(myModal.selectedItem.fst_item_name);
			});	

			$("#dfst_unit").select2({
				width: '100%',	
				cache: true,
				minimumResultsForSearch: -1,
				ajax: {
					url: '<?=site_url()?>tr/sales_order/ajxGetUnitList',
					data:function(params){
						params.fin_item_id = $("#dfin_item_id").val();
						return params;
					},
					dataType: 'json',
					delay: 250,
					processResults: function (resp) {
						if (resp.messages != ""){
							alert(resp.messages);
						}
						if (resp.status == "SUCCESS"){
							data = resp.data;
							data2 = [];
							$.each(data,function(index,value){
								value.id = value.fst_unit;
								value.text = value.fst_unit;
								data2.push(value);	
							});
							return {
								results: data2
							};
						}											
					},					
				}
			}).on('select2:select', function(e){
				myModal.selectedUnit = e.params.data;
				getSellingInfo();
			});			
		});

	</script>

	<script type="text/javascript" info="event">
		$(function(){		
			$(".price-control").change(function(e){
				e.preventDefault();
			});

			$("#dfst_disc_item").change(function(e){
				e.preventDefault();
				console.log(myModal.selectedItem);
				//Cek Max Disc
				var price = money_parse($("#dfdc_selling_price").val());
				var maxDiscAmount = App.calculateDisc(price,myModal.selectedItem.fst_max_item_discount);
				var discAmount = App.calculateDisc(price,$("#dfst_disc_item").val());
				if (maxDiscAmount < discAmount){
					alert("Disc Melebih Max Disc (" + myModal.selectedItem.fst_max_item_discount + " %)");
				}
				$("#dfdc_disc_amount_per_item").val(App.money_format(discAmount));				
			});

			$("#btn-add-so-detail").click(function(e){
				e.preventDefault();
				var data = {};
				cekPromo = 1;
				confirmAuthorize = 0;
				if (selectedRow != null){
					data = selectedRow.data();
					data.fin_item_id = myModal.selectedItem.id,
					data.fst_item_code = myModal.selectedItem.fst_item_code,
					data.fst_item_name = myModal.selectedItem.fst_item_name,
					data.fst_max_item_discount = myModal.selectedItem.fst_max_item_discount,
					data.fst_custom_item_name = $("#dfst_custom_item_name").val(),
					data.fdb_qty = App.money_parse($("#dfdb_qty").val()),
					data.fst_unit= $("#dfst_unit").val(),
					data.fdc_price = App.money_parse($("#dfdc_selling_price").val()),
					data.fst_disc_item = $("#dfst_disc_item").val(),
					data.fdc_disc_amount_per_item = money_parse($("#dfdc_disc_amount_per_item").val()),
					data.fst_memo_item = $("#dfst_memo_item").val()
					
					selectedRow.data(data).draw(false);
					myModal.clear();
					myModal.hide();
				}else{				
					data = {
						fin_rec_id:0,
						fin_promo_id:0,
						fin_item_id:myModal.selectedItem.id,
						fst_item_code:myModal.selectedItem.fst_item_code,
						fst_item_name:myModal.selectedItem.fst_item_name,
						fst_max_item_discount:myModal.selectedItem.fst_max_item_discount,
						fst_custom_item_name:$("#dfst_custom_item_name").val(),
						fdb_qty:App.money_parse($("#dfdb_qty").val()),
						fst_unit:$("#dfst_unit").val(),
						fdc_price:App.money_parse($("#dfdc_selling_price").val()),
						fst_disc_item:$("#dfst_disc_item").val(),
						fdc_disc_amount_per_item:money_parse($("#dfdc_disc_amount_per_item").val()),
						fst_memo_item:$("#dfst_memo_item").val()
					}
					tblDetails.row.add(data).draw(false);
					myModal.clear();
				}		
			})
		});
	</script>
	
	<script type="text/javascript" info="function">

		function getSellingInfo(){

			App.blockUIOnAjaxRequest();

			$.ajax({
				url:"<?=site_url()?>tr/sales_order/ajxGetSellingInfo",
				method:"GET",
				data:{
					fin_item_id:$("#dfin_item_id").val(),
					fin_warehouse_id:$("#fin_warehouse_id").val(),
					fst_unit:$("#dfst_unit").val(),
					fin_relation_id:$("#fin_relation_id").val()
				}

			}).done(function(resp){

				if (resp.messages != ""){
					alert(resp.messages);
				}
				if (resp.status == "SUCCESS"){
					data = resp.data;
					if (data.sellingPrice == 0){
						$("#dfdc_selling_price").prop("disabled",false);
						$("#dfdc_selling_price").val(money_format(0));
					}else{
						$("#dfdc_selling_price").prop("disabled",true);
						$("#dfdc_selling_price").val(money_format(data.sellingPrice));
					}
					
					
					var infoStock = "Qty Real Stock : " + data.real_stock + " " + data.fst_basic_unit + " => " + data.real_stock / myModal.selectedUnit.fdc_conv_to_basic_unit + " " +  myModal.selectedUnit.text;
					infoStock += " | Qty Marketing Stock : " + data.marketing_stock + " " + data.fst_basic_unit + " => " + data.marketing_stock / myModal.selectedUnit.fdc_conv_to_basic_unit + " " +  myModal.selectedUnit.text;
					
					$("#dialog-info .info-message").html(infoStock);
					$("#dialog-info").show();										
				}	

			});
		}

		
		
	</script>
</div>



<?php
	echo $mdlJurnal;
	echo $mdlConfirmAuthorize;
	echo $mdlEditForm;
	echo $mdlPrint;	
?>


<script type="text/javascript" info="binding_key">
	$(function(){
		$(document).bind('keydown', 'alt+d', function(){
			$("#btn-add-detail").trigger("click");
		});
	});
</script>

<script type="text/javascript" info="define">	
	var selectedCustomer = null;
	var selectedRow = null;
	var mode = "<?=$mode?>";
	var tblDetails;
	var priceIncPPN = <?=getDbConfig("sales_price_inc_ppn");?>;
	var defaultPPNPercent = <?=getDbConfig("sales_ppn_percent");?>;
	var cekPromo = 1;
	var confirmAuthorize = 0;



	var edited_so_detail = null;
	var mode_so_detail = "ADD";
	var arrDetail;				
</script>

<script type="text/javascript" info="init">
	$(function(){
		$("#fdt_salesorder_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");				
		$("#fdc_vat_percent").val(defaultPPNPercent);
		if (priceIncPPN == 1){
			//Hide PPN
			$("#fdc_vat_percent").prop("readonly",true);				
		}else{
			$("#fdc_vat_percent").prop("readonly",false);			
		}

		$("#fin_relation_id").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>tr/sales_order/ajxGetCustomerList',
				dataType: 'json',
				delay: 250,
				processResults: function (resp){
					if (resp.messages !=""){
						alert(resp.messages);
					}
					if (resp.status =="SUCCESS"){
						var data = resp.data;
						var arrData =[];
						$.each(data,function(index,value){
							value.id = value.fin_relation_id;
							value.text = value.fst_relation_name,
							arrData.push(value);
						});
					}
					return {
						results: arrData
					};
				},
				cache: true,
			}
		}).on('select2:selecting',function(e){
			
			//console.log(tmp);
			//e.preventDefault();
			if (selectedCustomer != null){		
				tmp = e.params.args.data;
				if (tmp.fin_cust_pricing_group_id != selectedCustomer.fin_cust_pricing_group_id){
					if ( tblDetails.data().count() > 0) {									
						if (confirm("Pelangan memiliki group price yg berbeda dan perubahan akan menghapus detail, Lanjutkan ?")){
							tblDetails.clear();
							tblDetails.draw();
						}else{
							e.preventDefault()
							return;
						}
					}
				}
			}

		}).on('select2:select',function(e){									
			selectedCustomer = e.params.data;		
			$("#fin_terms_payment").val(selectedCustomer.fin_terms_payment);
			$("#fin_sales_id").val(selectedCustomer.fin_sales_id).trigger("change");
		});

		$("#fin_shipping_address_id").select2({
			width: '100%',
			ajax: {
				url: function(params){
					return '<?=site_url()?>pr/relation/get_shipping_address/' + $("#fin_relation_id").val();
				},
				dataType: 'json',
				delay: 250,
				processResults: function (resp){
					if (resp.messages !=""){
						alert(resp.messages);
					}
					if (resp.status =="SUCCESS"){
						var data = resp.data;
						var arrData =[];
						$.each(data,function(index,value){
							arrData.push({
								"id" : value.fin_shipping_address_id,
								"text" : value.fst_name,
								"fst_shipping_address":value.fst_shipping_address
							});
						});
					}
					return {
						results: arrData
					};
				},
				cache: true,
			}
		}).on("select2:select",function(e){
			var data = e.params.data;
			$("#fst_shipping_address").val(data.fst_shipping_address);
		});

		$("#fin_sales_id").select2();
		
			

		tblDetails = $('#tblSODetails').on('preXhr.dt', function ( e, settings, data ) {
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
				{"title" : "Items","width": "320px",sortable:false,data:"fin_item_id",
					render: function(data,type,row){
						
						return row.fst_item_code + " - " + row.fst_custom_item_name + "<br>" + row.fst_memo_item;
						
					}
				},
				{"title" : "Custom Name","width": "0px",sortable:false,data:"fst_custom_item_name",visible:false},
				{"title" : "Qty",width:"30px",data:"fdb_qty",className:'text-right'},
				{"title" : "Unit",width:"50px",data:"fst_unit"},
				{"title" : "Price",width:"80px",
					data:"fdc_price",
					render: $.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					className:'text-right'
				},
				{"title" : "Disc",width:"120px",data:"fst_disc_item",className:'text-right',
					//render: $.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					render:function(data,type,row){
						return data + " - " + money_format(row.fdc_disc_amount_per_item);
					}
				},
							
				{"title" : "Total",width:"80px",className:'text-right',
					render: function(data,type,row){
						return App.money_format((row.fdb_qty * row.fdc_price) - (row.fdb_qty * row.fdc_disc_amount_per_item));
						//$.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					}
					
				},
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
			calculateTotal();		
		}).on("click",".btn-delete",function(event){
			t = $('#tblSODetails').DataTable();
			var trRow = $(this).parents('tr');

			t.row(trRow).remove().draw();
			calculateTotal();
		}).on("click",".btn-edit",function(event){
			event.preventDefault();					
			t = tblDetails;
			var trRow = $(this).parents('tr');			
			selectedRow = t.row(trRow);
			myModal.show();
		});

		init_form();
		App.fixedSelect2();		
	});
</script>

<script type="text/javascript" info="event">
	$(function(){

		$("#btnJurnal").click(function(e){
			e.preventDefault();
			MdlJurnal.showJurnalByRef("SO",$("#fin_salesorder_id").val());
		});

		$("#btnPrint").click(function(e){
			//frameVoucher.print("<?=site_url()?>tr/sales_order/print_voucher/" + $("#fin_salesorder_id").val());
			window.open("<?= site_url() ?>tr/sales_order/print_voucher/" +$("#fin_salesorder_id").val() ,"_blank","menubar=0,resizable=0,scrollbars=0,status=0,width=900,height=500");
		});		

		$("#btnSubmitAjax").click(function(event){
			event.preventDefault();
			saveAjax(0);
		});

		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>tr/sales_order/add")
		});

		$("#btnPromo").click(function(e){
			e.preventDefault();
			getPromoItem(function(){

			});
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
			myModal.show();
			myModal.clear();

			/*
			
			if ($("#select-relations").val() == "0"){
				alert("<=lang("Customer tidak boleh kosong !")?>");
				return;
			}
			mode_so_detail = "ADD";		
			$("#dialog-info").hide();					
			*/


		});		

		$("#fdc_vat_percent").change(function(e){
			e.preventDefault();
			calculateTotal();
		});

	});
</script>

<script type="text/javascript" info="function">	

	function saveAjax(confirmEdit){
		
		
		if (confirmAuthorize == 0){
			checkAuthorize(function(resp){
				saveAjax(confirmEdit);
			});
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
		datas = t.data();
		$.each(datas,function(i,v){
			detail.push(v);
		});

		data.push({
			name:"detail",
			value: JSON.stringify(detail)
		});		

	
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
									window.open("<?= site_url() ?>tr/sales_order/print_voucher/" + resp.data.insert_id ,"_blank","menubar=0,resizable=0,scrollbars=0,status=0,width=900,height=500");
									return;
								}

								if(resp.status == "CEK_PROMO"){
									window.location.replace("<?=site_url()?>tr/sales_order/cek_promo/" + resp.data.insert_id);
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

	function getPromoItem(callback){
		var t = tblDetails;
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
			url:"<?=site_url()?>tr/sales_order/ajxGetPromoItem",
			data:dataPost,
			method:"POST",
		}).done(function(resp){

			if (resp.message != ""){
				alert(resp.message);				
			}

			if (resp.status == "SUCCESS"){
				cekPromo = 0;
				var promotionList = resp.data;
				if (promotionList.length > 0){
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
						return;
					}
				}
				callback(resp);
			}

		});
	}

	function checkAuthorize(callback){
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
						callback();
					};
				}else{
					confirmAuthorize = 1;
					callback();
				}
			}
		});
	}

	function calculateTotal(){
		//Setiap terjadi perubahan total  harus cek promo lagi dan konfirmasi authorize lagi		
		t = tblDetails;
		datas = t.data();
		ttlBfDisc = 0;
		ttlDisc = 0;

		$.each(datas,function(i,v){
			ttlBfDisc += v.fdb_qty * v.fdc_price;
			ttlDisc +=  v.fdc_disc_amount_per_item * v.fdb_qty;
		});

		//if ($("#fbl_is_vat_include").prop('checked')){
		if(priceIncPPN == 1){
			total = ttlBfDisc - ttlDisc;			
			vat = parseFloat($("#fdc_vat_percent").val());
			vat = 1 + (vat/100);
			subTotal = total / vat;
			vat = (parseFloat($("#fdc_vat_percent").val()) /100) * subTotal;	
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
					App.addOptionIfNotExist("<option value='"+ dataH.fin_relation_id +"' selected>" + dataH.fst_relation_name + "</option>","fin_relation_id");

					App.addOptionIfNotExist("<option value='"+ dataH.fin_shipping_address_id +"' selected>" + dataH.fst_address_name + "</option>","fin_shipping_address_id");
					$("#fst_shipping_address").val(dataH.fst_shipping_address);
					$("#fin_sales_id").trigger("change");
					
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
					t = tblDetails;
					t.rows.add(details).draw(false);
					calculateTotal();
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
