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
					<div class="btn-group btn-group-sm  pull-right">					
						<a id="btnNew" class="btn btn-primary" href="#" title="<?=lang("Tambah Baru")?>"><i class="fa fa-plus" aria-hidden="true"></i></a>
						<a id="btnSubmitAjax" class="btn btn-primary" href="#" title="<?=lang("Simpan")?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
						<a id="btnPrint" class="btn btn-primary" href="#" title="<?=lang("Cetak")?>"><i class="fa fa-print" aria-hidden="true"></i></a>
						<a id="btnJurnal" class="btn btn-primary" href="#" title="<?=lang("Jurnal")?>"><i class="fa fa-align-left" aria-hidden="true"></i></a>
						<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
						<a id="btnClose" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
					</div>
				</div>
				<!-- end box header -->
				<!-- form start -->
				<form id="frmSalesOrder" class="form-horizontal" action="<?=site_url()?>tr/sales_order/add" method="POST" enctype="multipart/form-data">			
					<div class="box-body">
						<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">			
						<input type="hidden" id="frm-mode" value="<?=$mode?>">
						<input type="hidden" class="form-control" id="fin_salesorder_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_salesorder_id" value="<?=$fin_salesorder_id?>" readonly>

						
						<div class="form-group">
							<label for="fst_salesorder_no" class="col-md-2 control-label"><?=lang("Sales Order No")?> #</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="fst_salesorder_no" placeholder="<?=lang("Sales Order No")?>" name="fst_salesorder_no" value="<?=$fst_salesorder_no?>" readonly>
								<div id="fst_salesorder_no_err" class="text-danger"></div>
							</div>
							
							<label for="fdt_salesorder_date" class="col-md-2 control-label"><?=lang("Sales Order Date")?> *</label>
							<div class="col-md-4">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control text-right datetimepicker" id="fdt_salesorder_date" name="fdt_salesorder_date"/>
								</div>
								<div id="fdt_salesorder_date_err" class="text-danger"></div>
								<!-- /.input group -->
							</div>						
						</div>

						<div class="form-group">						
							<label for="fst_curr_code" class="col-md-2 control-label"><?=lang("Mata Uang")?> </label>
							<div class="col-md-4">
								<select id="fst_curr_code" class="form-control" name="fst_curr_code">
									<option value="<?=$default_currency['CurrCode']?>"><?=$default_currency['CurrName']?></option>
								</select>
								<div id="fst_curr_code_err" class="text-danger"></div>
							</div>
						
							<label for="fdc_exchange_rate_idr" class="col-md-2 control-label"><?=lang("Nilai Tukar IDR")?> </label>
							<div class="col-md-1">
								<input type="text" class="form-control" id="fdc_exchange_rate_idr" name="fdc_exchange_rate_idr" style="width:50px" value="1" readonly/>
							</div>
							<label class="col-md-2 control-label" style="text-align:left;padding-left:0px"><?=lang("Rupiah")?> </label>
						</div>

						<div class="form-group">						
							<label for="select-relations" class="col-md-2 control-label"><?=lang("Customer")?> </label>
							<div class="col-md-4">
								<select id="select-relations" class="form-control non-editable" name="fin_relation_id">
									<option value="0">-- <?=lang("select")?> --</option>
								</select>
								<div id="fin_relation_id_err" class="text-danger"></div>
							</div>
						
							<label for="select-relations" class="col-md-2 control-label"><?=lang("Term")?> </label>
							<div class="col-md-1">
								<input type="text" class="form-control" id="fin_terms_payment" name="fin_terms_payment" style="width:50px"/>							
								<div id="fin_terms_payment_err" class="text-danger"></div>
							</div>
							<label class="col-md-2 control-label" style="text-align:left;padding-left:0px"><?=lang("Hari")?> </label>
						</div>


						<div class="form-group">
							
							<label for="select-sales" class="col-md-2 control-label"><?=lang("Sales")?> </label>
							<div class="col-md-4">
								<select id="select-sales" class="form-control" name="fin_sales_id">
									<option value="0">-- <?=lang("select")?> --</option>
								</select>
								<div id="fin_sales_id_err" class="text-danger"></div>
							</div>
						</div>

						<div class="form-group">
							<label for="select-warehouse" class="col-md-2 control-label"><?=lang("Warehouse")?> </label>
							<div class="col-md-4">
								<select id="select-warehouse" class="form-control" name="fin_warehouse_id">
									<option value="0">-- <?=lang("select")?> --</option>
								</select>
								<div id="fin_warehouse_id_err" class="text-danger"></div>
							</div>
							<div class="checkbox col-sm-6">
								<label><input id="fbl_is_hold" type="checkbox" name="fbl_is_hold" value="1"><?= lang("Hold Pengiriman") ?></label>
								<label style="margin-left:20px"><input id="fbl_is_vat_include" type="checkbox" name="fbl_is_vat_include" value="1"><?= lang("Include PPN") ?></label>
							</div>
							
						</div>

						<div class="form-group">
							<label for="fst_shipping_address" class="col-md-2 control-label"><?=lang("Alamat Pengiriman")?> </label>
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
						<br>
						<div class="form-group">
							<div class="col-sm-6">	
								<div class="form-group">
									
									<div class="col-sm-12">
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
									<label for="total" class="col-md-8 control-label"><?=lang("Uang Muka")?></label>
									<div class="col-md-4" style='text-align:right'>
										<input type="text" class="money form-control text-right" id="fdc_downpayment" name="fdc_downpayment" value="0">
									</div>
								</div>
							</div>
							
						</div>
						
						

					</div>
					<!-- end box body -->

					<div class="box-footer text-right">
						<!-- <a id="btnSubmitAjaxOld" href="#" class="btn btn-primary"><=lang("Save Ajax")?></a> -->
					</div>
					<!-- end box-footer -->
				</form>
        	</div>
    	</div>
	</div>
</section>

<!-- modal atau popup "ADD" -->
<div id="myModal" class="modal fade" role="dialog" >
	<div class="modal-dialog" style="display:table;width:800px">
		<!-- modal content -->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Add SO Detail")?></h4>
			</div>

			<div class="modal-body">
				
				<form id="form-detail" class="form-horizontal">
				<input type='hidden' id='fin_rec_id'/>
					<div class="form-group">
						<label for="select-items" class="col-md-2 control-label"><?=lang("Items")?></label>
						<div class="col-md-10">
							<select id="select-items" class="form-control"></select>
							<div id="ItemId_err" class="text-danger"></div>
						</div>
					</div>
					<div class="form-group">
						<label for="fst_custom_item_name" class="col-md-2 control-label"><?=lang("Custom Name")?></label>
						<div class="col-md-10">
							<input id="fst_custom_item_name" class="form-control"></select>
							<div id="fst_custom_item_name_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="select-unit" class="col-md-2 control-label"><?=lang("Unit")?></label>
						<div class="col-md-10">
							<select id="select-unit" name="fst_unit" class="form-control" style="width:100%"></select>
							<div id="fst_unit_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="fdb_qty" class="col-md-2 control-label"><?=lang("Qty")?></label>
						<div class="col-md-2">
							<input type="number" class="form-control text-right numeric" id="so-qty" value="1">
							<div id="fdb_qty_err" class="text-danger"></div>
						</div>

						<label for="fdc_price" class="col-md-2 control-label"><?=lang("Price")?></label>
						<div class="col-md-6">
							<input type="text" class="form-control text-right money" id="so-price" value="0">
							<div id="fdc_price_err" class="text-danger"></div>
						</div>
					</div>

					

					<div class="form-group">
						<label for="select-disc" class="col-md-2 control-label"><?=lang("Disc ++")?></label>
						<div class="col-md-10">
							<select id="select-disc" class="form-control text-right" name="fst_disc_item">
								<option value="0">0</option>							
							</select>
							<div id="fst_disc_item_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="fdc_disc_amount" class="col-md-2 control-label"><?=lang("Disc Amt")?></label>
						<div class="col-md-10">
							<input type="text" class="form-control text-right" id="fdc_disc_amount" readonly>
							<div id="fdc_disc_amount_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="fst_memo_item" class="col-md-2 control-label"><?=lang("Memo")?></label>
						<div class="col-md-10">
							<textarea type="text" class="form-control" id="fst_memo_item" rows="3"></textarea>
							<div id="fst_memo_item_err" class="text-danger"></div>
						</div>
					</div>

				</form>
				
			</div>

			<div class="modal-footer">
				<button id="btn-add-so-detail" type="button" class="btn btn-primary" ><?=lang("Add")?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?=lang("Close")?></button>
			</div>
			<div id="dialog-info" class="alert alert-info">
				<a href="#" class="close" onclick="$('#dialog-info').hide()" aria-label="close">&times;</a>
				<div class="info-message">
					<strong>Info!</strong> Indicates a neutral informative change or action.
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	echo $mdlJurnal;
?>


			
<script type="text/javascript">
	var action = '<a class="btn-edit" href="#" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp;<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
	var edited_so_detail = null;
	var mode_so_detail = "ADD";
	var arrDetail;	
	var sel2Sales = [];
	var sel2Warehouse =[];
	var sel2Currencies =[];
	var current_pricing_group_id = 0;
	selectedRow = null;

	var ajxSel2Item = {
		url: '<?=site_url()?>tr/sales_order/get_data_item',
		dataType: 'json',
		delay: 250,
		processResults: function (data) {
			data2 = [];
			$.each(data,function(index,value){
				data2.push({
					"id" : value.fin_item_id,
					"text" : value.ItemCodeName,
					"fst_item_name" : value.fst_item_name,
					"fst_item_code" : value.fst_item_code,
					"maxItemDiscount" : value.fst_max_item_discount
				});	
			});
			return {
				results: data2
			};
		},
		cache: true,
	}

	function itemOnSelect(event) {		
		var data = event.params.data;
		data = Array.isArray(data) ? data[0] : data;		
		consoleLog(data);
		dataCust = $("#select-relations").select2("data")[0];


		$("#fst_custom_item_name").val(data.fst_item_name);			
		blockUIOnAjaxRequest("<h5><?=lang("Please wait....")?></h5>");
		$('#select-unit').empty();		
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
					"marketing_stock":v.marketing_stock
				});
			});

			$("#select-unit").select2({
				minimumResultsForSearch: -1,
				data:arrSel2
			}).on('select2:select', function (e) {
				dataUnit = e.params.data;
				$("#so-price").val(money_format(dataUnit.price));
				$("#dialog-info .info-message").html("Qty Stock : " + dataUnit.real_stock + " | " + " Qty Marketing Stock : " + dataUnit.marketing_stock);
				$("#dialog-info").show();							
			});
			
			defaultUnit =  arrSel2[0];
			if (selectedRow != null){
				defaultUnit = $.grep(arrSel2,function(v,i){
					return v.id == selectedRow.fst_unit;
				})[0];
				
			}
			$("#select-unit").val(defaultUnit.id).trigger("change");
			$("#select-unit").trigger({
				type: 'select2:select',
				params: {
					data: defaultUnit,
				}
			});

			fixedSelect2();
		});				
	}

	$(function(){
		$("#btnJurnal").click(function(e){
			e.preventDefault();
			$("#mdlJurnal").modal({
				backdrop:"static",
			});
		});

		$("#btnSubmitAjax").click(function(event){
			event.preventDefault();
			var cekPromo = 1;
			var confirmAuthorize = 0;
			saveAjax(cekPromo,confirmAuthorize);
		});

		//$("#fdt_salesorder_date").datepicker('update', dateFormat("<= date("Y-m-d")?>"));
		$("#fdt_salesorder_date").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");

		
		
		$("#select-relations").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>tr/sales_order/get_msrelations',
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
			$("#select-sales").val(selectedCustomer.fin_sales_id).trigger("change");
			$("#select-warehouse").val(selectedCustomer.fin_warehouse_id).trigger("change");
			//current_pricing_group_id = selectedCustomer.current_pricing_group_id;			
		});
		

		$("#fbl_is_vat_include").change(function(e){
			calculateTotal();
		});

		$("#select-items").select2({
			width: '100%',
			ajax: ajxSel2Item
		}).on('select2:select', function(e){
			itemOnSelect(e);
			//console.log(e.params.data);
		});

		
		
		$("#btn-add-detail").click(function(event){
			event.preventDefault();
			if ($("#select-relations").val() == "0"){
				alert("invalid cust");
				return;
			}
			mode_so_detail = "ADD";			
			$("#myModal").modal({
				backdrop:"static",
			});
			clearDetailForm();
			$("#dialog-info").hide();		
			
		})

		$(document).bind('keydown', 'alt+d', function(){
			//alert("TEST COPY");
			$("#btn-add-detail").trigger("click");
		});


		$("#btn-add-so-detail").click(function(event){
			event.preventDefault();		
			price = money_parse($("#so-price").val());			
			selected_items = $("#select-items").select2('data')[0];
			selected_disc = $("#select-disc").select2('data')[0];
			selectedUnits = $("#select-unit").select2('data')[0];

			qty = $("#so-qty").val();
			price = money_parse($("#so-price").val());
			disc = money_parse($("#fdc_disc_amount").val());

			
			amount = price * qty;
			maxDisc = calculateDisc(amount,selected_items.maxItemDiscount);

			if (maxDisc < disc){
				alert("<?= lang('Total discount more than max disc allowed !') ?>" + " (maxDisc:" +  selected_items.maxItemDiscount + ")");
				return;
			}
			data = {
				fin_rec_id:$("#fin_rec_id").val(),
				fin_promo_id:0,
				fin_item_id:selected_items.id,
				ItemName:selected_items.text,
				ItemCode:selected_items.fst_item_code,
				fst_custom_item_name:$("#fst_custom_item_name").val(),
				fdb_qty: $("#so-qty").val(),
				fst_unit: selectedUnits.id,
				fdc_price : price,
				fst_disc_item : selected_disc.id,
				fdc_disc_amount: disc,
				fst_memo_item: $("#fst_memo_item").val(),
				total: (qty * price)  - disc,
				action: action
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

		$("#fdc_vat_percent").change(function(e){
			e.preventDefault();
			calculateTotal();
		});

		$('#tblSODetails').on('preXhr.dt', function ( e, settings, data ) {
		 	//add aditional data post on ajax call
		 	data.sessionId = "TEST SESSION ID";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "Action","width": "40px",data:"action",sortable:false,className:'dt-body-center text-center'},
				{"title" : "id",sortable:false,data:"fin_rec_id",visible:true},
				{"title" : "promo",sortable:false,data:"fin_promo_id",visible:true},				
				{"title" : "Items","width": "250px",sortable:false,data:"fin_item_id",
					render: function(data,type,row){
						return row.ItemCode + "-" + row.fst_custom_item_name;
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
				{"title" : "Disc ++",width:"50px",
					data:"fst_disc_item",
					render: $.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					className:'text-right'
				},
				{"title" : "Disc Amt",width:"80px",
					data:"fdc_disc_amount",
					render: $.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					className:'text-right'
				},				
				{"title" : "Total",width:"80px",
					data:"total",
					render: $.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					className:'text-right'
				},
				{"title" : "Memo","width": "200px",data:"fst_memo_item"},
				
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

			$('.xbtn-delete').confirmation({
				//rootSelector: '[data-toggle=confirmation]',
				rootSelector: '.btn-delete',
				// other options
			});	

			$(".xbtn-delete").click(function(event){
				t = $('#tblSODetails').DataTable();
				var trRow = $(this).parents('tr');

				t.row(trRow).remove().draw();
				calculateTotal();
			});

			$(".xbtn-edit").click();
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
			
			$('#select-items').val(row.fin_item_id).trigger('change');
			//$('#select-items').val(row.fin_item_id);			
			$('#select-items').trigger({
				type:"select2:select",
				params:{
					data:$("#select-items").select2("data")
				}
			});



			/*			
			$('#select-items').trigger({
				type:"select2:select",
				params: {
					data:selectedItem
				}
			});
			*/
			
			$("#fst_custom_item_name").val(row.fst_custom_item_name);
			$('#select-disc').val(row.fst_disc_item).trigger('change');
			
			//$('#select-items').trigger({type:"select2:select"});
			$("#so-qty").val(row.fdb_qty);
			$("#so-price").val(money_format(row.fdc_price));
			$("#fdc_disc_amount").val(money_format(row.fdc_disc_amount));
			$("#fst_memo_item").val(row.fst_memo_item);				
		});


		init_form($("#fin_salesorder_id").val());
		fixedSelect2();

		
	});

	function clearDetailForm(){
		$("#fin_rec_id").val(0);
		$('#select-items').val(null).trigger('change');
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

	function calculateDisc(amount, disc){
		var strArray = disc.split("+");
		totalDisc = 0;
		$.each(strArray,function(i,v){
			disc = amount * (v / 100);
			totalDisc += disc;
			amount = amount - disc;
		});
		return totalDisc;
	}

	function calculateTotal(){
		t = $('#tblSODetails').DataTable();
		datas = t.data();
		ttlBfDisc = 0;
		ttlDisc = 0;

		$.each(datas,function(i,v){
			ttlBfDisc += v.fdb_qty * v.fdc_price;
			ttlDisc +=  v.fdc_disc_amount * 1;
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


	function init_form(fin_salesorder_id){
		$(document).ajaxStart(function() {
			$.blockUI({ message:'<h1><?=lang("Please wait....")?></h1>'});
		});

		$(document).ajaxStop(function() {
			$.unblockUI();
			$(document).unbind('ajaxStart');			
		});

		$.ajax({
			url: '<?=site_url()?>tr/sales_order/getValueFormInit/' + fin_salesorder_id,
		}).done(function(values){
			arrSales = values.data.sales;
			$.each(arrSales,function(i,v){
				sel2Sales.push({
					id:v.fin_user_id,
					text:v.fst_username
				});
			});				
			$("#select-sales").select2({
				minimumResultsForSearch: -1,
				data: sel2Sales,
				width:"100%",
			});

			arrWarehouse = values.data.warehouse;
			$.each(arrWarehouse,function(i,v){
				sel2Warehouse.push({
					id:v.fin_warehouse_id,
					text:v.fst_warehouse_name
				});
			});
			$("#select-warehouse").select2({
				minimumResultsForSearch: -1,
				data: sel2Warehouse,
				width:"100%",
			});

			arrCurrencies = values.data.currencies;
			$.each(arrCurrencies,function(i,v){
				sel2Currencies.push({
					id:v.CurrCode,
					text:v.CurrName
				});
			});
			$("#fst_curr_code").select2({
				minimumResultsForSearch: -1,
				data: sel2Currencies,
				width:"100%",
			});

			arrDisc = values.data.discounts;
			sel2Disc = [];
			$.each(arrDisc,function(i,v){
				sel2Disc.push({
					id:v.fst_item_discount,
					text:v.fst_item_discount
				});
			});
			$("#select-disc").select2({
				dir: 'rtl',
				width: '100%',
				data: sel2Disc,
			}).on('select2:select',function(e){
				var data = e.params.data;
				disc = data.id;
				qty = $("#so-qty").val();
				amount = money_parse($("#so-price").val());
				amount = amount * qty;
				$("#fdc_disc_amount").val( money_format(calculateDisc(amount,disc)) ); 
			});

			<?php if($mode == "EDIT"){?>
				fillForm(values);
			<?php } ?>
		}).always(function(resp){
			fixedSelect2();
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
				action: (detail.fin_promo_id == 0) ? action : ""
			}

			t = $('#tblSODetails').DataTable();			
			t.row.add(data).draw(false);

			//set Data Item select2		
			tmp = {
				"id" : detail.fin_item_id,
				"text" : detail.fst_item_code + "-" + detail.fst_item_name,
				"fst_item_name" : detail.fst_item_name,
				"fst_item_code" : detail.fst_item_code,
				"maxItemDiscount" : detail.fst_max_item_discount
			}
			dataItem.push(tmp);
		});

		$("#select-items").select2({
			width:"100%",
			data:dataItem,
			ajax:ajxSel2Item
		});

		fixedSelect2();
		//$(".non-editable").prop('disabled', true);
		calculateTotal();		
		//$("#fdt_salesorder_date").datepicker('update', dateTimeFormat(resp.sales_order.fdt_salesorder_date));
		$("#fdt_salesorder_date").val(dateTimeFormat(resp.sales_order.fdt_salesorder_date)).datetimepicker('update');
	
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
				$("#fin_shipping_address_id").val(defaultValue.id).trigger("change");
				$("#fin_shipping_address_id").trigger({
					type: 'select2:select',
					params: {
						data: defaultValue
					}
				});
			}
		});
	}


	function saveAjax(cekPromo,confirmAuthorize){
		alert("simpan");
		return;

		data = $("#frmSalesOrder").serializeArray();
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
		data.push({
			name:"cekPromo",
			value: cekPromo
		});
		data.push({
			name:"confirmAuthorize",
			value: confirmAuthorize
		});


		mode = $("#frm-mode").val();
		if (mode == "ADD"){
			url =  "<?= site_url() ?>tr/sales_order/ajx_add_save/";
		}else{
			url =  "<?= site_url() ?>tr/sales_order/ajx_edit_save/";
		}

		//var formData = new FormData($('form')[0])
		
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
									window.location.href = "<?= site_url() ?>tr/sales_order/add";
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
				}else if (resp.status == "INFOPROMO"){					
					if (confirm(resp.confirm_message)){
						//var actionDelete = '<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
						$.each(resp.data,function(i,v){
							if (v.modelPromotion == "ITEM" || v.modelPromotion == "OTHER ITEM"){
								data = {
									fin_rec_id:0,
									fin_promo_id:v.fin_promo_id,
									fin_item_id:v.fin_item_id,
									ItemName:v.fst_item_name,
									ItemCode:v.fst_item_code,								
									fst_custom_item_name: v.fst_custom_item_name,
									fdb_qty: money_parse(v.fdb_qty),
									fst_unit: v.fst_unit,
									fdc_price : 1,
									fst_disc_item : 100,
									fdc_disc_amount: 1,
									fst_memo_item: "",
									total: 0,
									action: ""//actionDelete

								}
								t = $('#tblSODetails').DataTable();							
								t.row.add(data).draw(false);
							}
						});
					}else{
						saveAjax(0,0);
					}		
				}else if( resp.status == "CONFIRM_AUTHORIZE"){
					arrOutofStock = resp.data.arrOutofStock;
					messageOutofStock = "";
					$.each(arrOutofStock,function(i,v){
						if (messageOutofStock == ""){
							messageOutofStock = "";
						}
					});

					arrOutstanding = resp.data.arrOutstanding;
					messageOverlimit ="";
					if (arrOutstanding.totalOutstanding > arrOutstanding.maxCreditLimit){
						messageOverlimit = "<?= lang("Plafon tersedia") ?> :" + money_format(arrOutstanding.maxCreditLimit) + "<br>";
						messageOverlimit += "<?= lang("Piutang Outstanding") ?> :" + money_format(arrOutstanding.piutangOutstanding) + "<br>";
						messageOverlimit += "<?= lang("S/O Outstanding") ?> :" + money_format(arrOutstanding.soOutstanding) + "<br><br>";
						messageOverlimit += "<?= lang("Billyet Outstanding") ?> :" + money_format(arrOutstanding.billyetOutstanding) + "<br><br>";
						messageOverlimit += "<?= lang("Sisa Plafon Tersedia") ?> :" + money_format(arrOutstanding.sisaPlafon) + "<br>";
						messageOverlimit += "<hr>";
						messageOverlimit += "<?= lang("Info Outstanding Faktur") ?>";
						messageOverlimit += "<hr>";
					}
					arrFakturOutstanding = arrOutstanding.dataFakturOutstanding;
					$.each(arrFakturOutstanding,function(i,v){
						if(messageOverlimit == ""){
							
						}
					});
					message = messageOutofStock + messageOverlimit;
					message += "<br><br>";
					message += "<?=lang("Transaksi memerlukan authorization, lanjutkan ?")?>";

					$.confirm({
						title: 'Authorization transaction',
						content: message,
						buttons: {
							confirm: function () {																
								saveAjax(0,1);
							},
							cancel: function () {
							},
						}						
					});					
				}else if(resp.status == "SUCCESS") {
					data = resp.data;
					$("#fin_salesorder_id").val(data.insert_id);

					//Clear all previous error
					$(".text-danger").html("");

					// Change to Edit mode
					$("#frm-mode").val("EDIT");  //ADD|EDIT
					$('#fst_salesorder_no').prop('readonly', true);
					$("#tabs-so-detail").show();
				}
			},
			error: function (e) {
				$("#result").text(e.responseText);
				$("#btnSubmit").prop("disabled", false);
			},
		}).always(function(){
			
		});

	}

</script>

<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
   function fixedSelect2(){
        $(".select2-container").addClass("form-control"); 
        $(".select2-selection--single , .select2-selection--multiple").css({
            "border":"0px solid #000",
            "padding":"0px 0px 0px 0px"
        });         
        $(".select2-selection--multiple").css({
            "margin-top" : "-5px",
            "background-color":"unset"
        });
    };
</script>