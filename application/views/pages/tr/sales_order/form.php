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
				<h3 class="box-title title"><?=$title?></h3>
			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmSalesOrder" class="form-horizontal" action="<?=site_url()?>tr/sales_order/add" method="POST" enctype="multipart/form-data">			
				<div class="box-body">
					<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">			
					<input type="hidden" id="frm-mode" value="<?=$mode?>">

					<div class="form-group hidden">
                        <label for="fin_salesorder_id" class="col-md-2 control-label"><?=lang("Sales Order ID")?> #</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="fin_salesorder_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_salesorder_id" value="<?=$fin_salesorder_id?>" readonly>
                                <div id="fin_salesorder_id_err" class="text-danger"></div>
                            </div>
					</div>

					<div class="form-group">
                        <label for="fst_salesorder_no" class="col-md-2 control-label"><?=lang("Sales Order No")?> #</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="fst_salesorder_no" placeholder="<?=lang("Sales Order No")?>" name="fst_salesorder_no" value="<?=$fst_salesorder_no?>" readonly>
                                <div id="fst_salesorder_no_err" class="text-danger"></div>
                            </div>
                    </div>

                    <div class="form-group">
                        <label for="fdt_salesorder_date" class="col-md-2 control-label"><?=lang("Sales Order Date")?> *</label>
                            <div class="col-md-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right datepicker" id="fdt_salesorder_date" name="fdt_salesorder_date"/>								
                                </div>
                                <div id="fdt_salesorder_date_err" class="text-danger"></div>
                                <!-- /.input group -->
                            </div>

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

						<label for="select-spv" class="col-md-2 control-label"><?=lang("Sales Spv")?> </label>
                            <div class="col-md-4">
								<select id="select-spv" class="form-control" name="fin_sales_spv_id">
									<option value="0">-- <?=lang("select")?> --</option>
								</select>
                                <div id="fin_sales_spv_id_err" class="text-danger"></div>
                            </div>
					</div>

					<div class="form-group">
						<label for="select-relations" class="col-md-2 control-label"><?=lang("Customer")?> </label>
                            <div class="col-md-4">
								<select id="select-relations" class="form-control" name="fin_relation_id">
									<option value="0">-- <?=lang("select")?> --</option>
								</select>
                                <div id="fin_relation_id_err" class="text-danger"></div>
                            </div>

                        <label for="select-mgr" class="col-md-2 control-label"><?=lang("Sales Mgr")?></label>
                            <div class="col-md-4">
								<select id="select-mgr" class="form-control" name="fin_sales_mgr_id">
									<option value="0">-- <?=lang("select")?> --</option>
								</select>
                                    <div id="fin_sales_mgr_id_err" class="text-danger"></div>
                            </div>
					</div>

                    <div class="form-group">
                            <label for="fbl_is_hold" class="col-sm-2 control-label"> </label>
                            <div class="checkbox col-sm-2">
                                <label><input id="fbl_is_hold" type="checkbox" name="fbl_is_hold" value="1"><?= lang("Hold Pengiriman") ?></label><br>
                            </div>
							<div class="checkbox col-sm-2">
								<?php $checked = ($mode == "ADD") ? "checked" : "" ?>
                                <label><input <?= $checked ?> id="fbl_is_vat_include" type="checkbox" name="fbl_is_vat_include" value="1"><?= lang("Include PPN") ?></label><br>
                            </div>
                    </div>

					<div class="form-group">
						<div class="col-md-12" style='text-align:right'>
							<button id="btn-add-detail" class="btn btn-default btn-sm">
								<i class="fa fa-plus" aria-hidden="true"></i>
								<?=lang("Add Detail")?>
							</button>
						</div>
					</div>

					<table id="tblSODetails" class="table table-bordered table-hover table-striped"></table>
					<div class="form-group">
						<div class="col-sm-6">	
							<div class="form-group">
								<label for="fst_memo" class="col-sm-2 control-label"><?= lang("Memo") ?> </label>
								<div class="col-sm-10">
									<textarea class="form-control" id="fst_memo" placeholder="<?= lang("Memo") ?>" name="fst_memo" rows="5"></textarea>
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
						</div>
						
					</div>
					
					

                </div>
				<!-- end box body -->

                <div class="box-footer text-right">
                    <a id="btnSubmitAjax" href="#" class="btn btn-primary"><?=lang("Save Ajax")?></a>
                </div>
                <!-- end box-footer -->
            </form>
        </div>
    </div>
</section>

<!-- modal atau popup "ADD" -->
<div id="myModal" class="modal fade" role="dialog" >
	<div class="modal-dialog" style="display:table">
		<!-- modal content -->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Add SO Detail")?></h4>
			</div>

			<div class="modal-body">
				<form  class="form-horizontal">
				<input type='hidden' id='fin-detail-id'/>
					<div class="form-group">
						<label for="select-items" class="col-md-2 control-label"><?=lang("Items")?></label>
						<div class="col-md-10">
							<select id="select-items" class="form-control"></select>
							<div id="ItemId_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="select-unit" class="col-md-2 control-label"><?=lang("Unit")?></label>
						<div class="col-md-10">
							<select id="select-unit" name="fst_unit" class="form-control"></select>
							<div id="fst_unit_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="fdc_qty" class="col-md-2 control-label"><?=lang("Qty")?></label>
						<div class="col-md-10">
							<input type="number" class="form-control text-right numeric" id="so-qty" value="1">
							<div id="fdc_qty_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="fdc_price" class="col-md-2 control-label"><?=lang("Price")?></label>
						<div class="col-md-10">
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
							<input type="text" class="form-control" id="fst_memo_item">
							<div id="fst_memo_item_err" class="text-danger"></div>
						</div>
					</div>

				</form>
			</div>

			<div class="modal-footer">
				<button id="btn-add-so-detail" type="button" class="btn btn-primary" ><?=lang("Add")?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?=lang("Close")?></button>
			</div>
		</div>
	</div>
</div>
			
			
<script type="text/javascript">
	var action = '<a class="btn-edit" href="#" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp;<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
	var edited_so_detail = null;
	var mode_so_detail = "ADD";
	var arrDetail;

	$(function(){
		<?php if($mode == "EDIT"){?>
			init_form($("#fin_salesorder_id").val());
		<?php } ?>

		
		$("#btnSubmitAjax").click(function(event){
			event.preventDefault();
			data = $("#frmSalesOrder").serializeArray();
			//console.log(data);
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

			mode = $("#frm-mode").val();
			if (mode == "ADD"){
				url =  "<?= site_url() ?>tr/sales_order/ajx_add_save";
			}else{
				url =  "<?= site_url() ?>tr/sales_order/ajx_edit_save";
			}

			//var formData = new FormData($('form')[0])
			$.ajax({
				type: "POST",
				enctype: 'multipart/form-data',
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
										window.location.href = "<?= site_url() ?>tr/sales_order/lizt";
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

						// Change to Edit mode
						$("#frm-mode").val("EDIT");  //ADD|EDIT
						$('#fst_salesorder_no').prop('readonly', true);
						$("#tabs-so-detail").show();
						console.log(data.data_image);
					}
				},
				error: function (e) {
					$("#result").text(e.responseText);
					console.log("ERROR : ", e);
					$("#btnSubmit").prop("disabled", false);
				}
			});
		});

		$("#fdt_salesorder_date").datepicker('update', dateFormat("<?= date("Y-m-d")?>"));

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
							"id" : value.RelationId,
							"text" : value.RelationName
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

		$("#select-warehouse").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>tr/sales_order/get_mswarehouse',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.fin_warehouse_id,
							"text" : value.fst_warehouse_name
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

		$("#select-sales").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>tr/sales_order/get_users',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.fin_user_id,
							"text" : value.fst_username
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

		$("#select-spv").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>tr/sales_order/get_users',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.fin_user_id,
							"text" : value.fst_username
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

		$("#select-mgr").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>tr/sales_order/get_users',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.fin_user_id,
							"text" : value.fst_username
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

		$("#select-items").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>tr/sales_order/get_data_item',
				dataType: 'json',
				delay: 250,
				processResults: function (data) {
					data2 = [];
					$.each(data,function(index,value){
						data2.push({
							"id" : value.ItemId,
							"text" : value.ItemName,
							"maxItemDiscount" : value.MaxItemDiscount
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

		$("#select-disc").select2({
			dir: 'rtl',
			width: '100%',
			ajax: {
				url: '<?=site_url()?>tr/sales_order/get_data_disc',
				dataType: 'json',
				delay: 250,
				processResults: function (data) {
					data2 = [];
					$.each(data,function(index,value){
						data2.push({
							"id" : value.ItemDiscount,
							"text" : value.ItemDiscount
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
		
		$("#select-disc").on('select2:select',function(e){
			var data = $('#select-disc').select2("data")[0];
			disc = data.id;
			qty = $("#so-qty").val();

			amount = money_parse($("#so-price").val());
			amount = amount * qty;

			$("#fdc_disc_amount").val( money_format(calculateDisc(amount,disc)) ); 
		});

		$('#select-items').on('select2:select', function (e) {
			//var data = e.params.data;
			//selected_items = data;
			var data = $('#select-items').select2("data")[0];
			//$("#so-price").val(numeral(data.fdc_price).format("0,000"));
			console.log(data);
			//Fill Unit
			$('#select-unit').val(null).trigger('change');
			$("#select-unit").select2({
				width: '100%',
				ajax:{
					url: '<?=site_url()?>master/msitems/getSellingUnit/' + data.id,
					dataType: 'json',
					delay: 250,
					processResults: function (data) {
						data2 = [];
						$.each(data,function(index,value){
							data2.push({
								"id" : value.Unit,
								"text" : value.Unit,
								"price" :value.PriceList
							});
						});
						console.log(data2);
						return {
							results: data2
						};
					},
				},
				cache: true,
			});
			//$('#select-unit').trigger('change');
		});

		$('#select-unit').on('select2:select', function (e) {
			
			
			dataCust = $("#select-relations").select2("data")[0];
			dataUnit = $('#select-unit').select2("data")[0];
			dataItem = $('#select-items').select2("data")[0];
			
			$.ajax({
				url:"<?=site_url()?>master/msitems/getSellingPrice/" + dataItem.id + "/" + dataUnit.id + "/" + dataCust.id,
				method:"GET",
				success:function(resp){
					console.log(resp);
					//$("#so-price").val(numeral(resp.sellingPrice).format("0,000"));
					//$("#so-price").inputmask("setvalue",resp.sellingPrice);
					$("#so-price").val(money_format(resp.sellingPrice));
				}
			});
			
			
		});
		
		
		$("#btn-add-detail").click(function(event){
			event.preventDefault();
			//console.log($("#select-relations").val());
			if ($("#select-relations").val() == "0"){
				alert("invalid cust");
				return;
			}
			mode_so_detail = "ADD";
			$("#myModal").modal({
				backdrop:"static",
			});
			clearDetailForm();			
			
		})

		$(document).bind('keydown', 'alt+d', function(){
			//alert("TEST COPY");
			$("#btn-add-detail").trigger("click");
		});


		$("#btn-add-so-detail").click(function(event){
			event.preventDefault();
			selected_items = $("#select-items").select2('data')[0];
			selected_disc = $("#select-disc").select2('data')[0];
			selectedUnits = $("#select-unit").select2('data')[0];

			qty = $("#so-qty").val();
			price = money_parse($("#so-price").val());
			disc = money_parse($("#fdc_disc_amount").val());

			data = {
				rec_id:$("#fin-detail-id").val(),
				fin_item_id:selected_items.id,
				ItemName:selected_items.text,
				fdc_qty: $("#so-qty").val(),
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
			columns:[
				{"title" : "id","width": "5%",sortable:false,data:"rec_id",visible:true},
				{"title" : "Items","width": "15%",sortable:false,data:"fin_item_id",
					render: function(data,type,row){
						console.log(row);
						return row.ItemName;
					}
				},
				{"title" : "Qty","width": "10%",data:"fdc_qty",className:'text-right'},
				{"title" : "Unit","width": "10%",data:"fst_unit"},
				{"title" : "Price","width": "10%",
					data:"fdc_price",
					render: $.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					className:'text-right'
				},
				{"title" : "Disc ++","width": "10%",
					data:"fst_disc_item",
					render: $.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					className:'text-right'
				},
				{"title" : "Disc Amt","width": "10%",
					data:"fdc_disc_amount",
					render: $.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					className:'text-right'
				},
				{"title" : "Memo","width": "15%",data:"fst_memo_item"},
				{"title" : "Total","width": "10%",
					data:"total",
					render: $.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					className:'text-right'
				},
				{"title" : "Action","width": "8%",data:"action",sortable:false,className:'dt-body-center text-center'},
			],
			processing: true,
			serverSide: false,
			searching: false,
			lengthChange: false,
			paging: false,
			info:false,
		}).on('draw',function(){
			$('.btn-delete').confirmation({
				//rootSelector: '[data-toggle=confirmation]',
				rootSelector: '.btn-delete',
				// other options
			});	

			$(".btn-delete").click(function(event){
				t = $('#tblSODetails').DataTable();
				var trRow = $(this).parents('tr');

				t.row(trRow).remove().draw();
				calculateTotal();
			});

			$(".btn-edit").click(function(event){
				event.preventDefault();
				$("#myModal").modal({
					backdrop:"static",
				});

				t = $('#tblSODetails').DataTable();
				var trRow = $(this).parents('tr');

				mode_so_detail = "EDIT";
				edited_so_detail = t.row(trRow);
				row = edited_so_detail.data();	

				$('#select-items').val(row.fin_item_id).trigger('change');
				$('#select-disc').val(row.fst_disc_item).trigger('change');
				$('#select-unit').val(row.fst_unit).trigger('change');
				$("#fin-detail-id").val(row.rec_id).trigger('change');
				$("#so-qty").val(row.fdc_qty);
				$("#so-price").val(money_format(row.fdc_price));
				$("#fdc_disc_amount").val(money_format(row.fdc_disc_amount));
				$("#fst_memo_item").val(row.fst_memo_item);

			});
		});

	});

	function clearDetailForm(){
		$('#select-items').val(null).trigger('change');
		$('#select-disc').val("0").trigger('change');
		$('#select-unit').val(null).trigger('change');
		$("#fin-detail-id").val(0);
		$("#so-qty").val(1);
		$("#so-price").val(0);
		$("#fdc_disc_amount").val(0);
		$("#fst_memo_item").val("");
	}


	function calculateDisc(amount, disc){
		var strArray = disc.split("+");
		console.log(amount);
		console.log(disc);
		

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
		totalDetail =0;
		subTotal = 0;

		disc = parseFloat ($("#ItemDiscount").val());
		$.each(datas,function(i,v){
			totalDetail += v.total;
		})

		if ($("#fbl_is_vat_include").prop('checked')){
			total = totalDetail;
			vat = $("#fdc_vat_percent").val() * 1;
			subTotal = total / (( vat  + 100) /100);

			vat =  ($("#fdc_vat_percent").val() /100) * subTotal;			
			$("#sub-total").val(money_format(subTotal));	
			$("#fdc_vat_amount").val(money_format(vat));
			$("#total").val(money_format(total));

		}else{
			subTotal=totalDetail;
			vat = ($("#fdc_vat_percent").val() /100) * subTotal;
			$("#sub-total").val(money_format(subTotal));	
			$("#fdc_vat_amount").val(money_format(vat));
			total =subTotal + vat;
			$("#total").val(money_format(total));
		}
		
		/*
		$("#sub-total").val(numeral(subTotal).format("0,000"));
		disc_val = subTotal * (disc/100);

		$("#disc-val").val(numeral(disc_val).format("0,000"));
		total = subTotal - disc_val;
		$("#total").val(numeral(total).format("0,000"));
		*/
	}


	function init_form(fin_salesorder_id){
		//alert("Init Form");
		var url = "<?=site_url()?>tr/sales_order/fetch_data/" + fin_salesorder_id;
		$.ajax({
			type: "GET",
			url: url,
			success: function (resp) {	
				console.log(resp.sales_order);

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
							console.log(val);
					}
				});

				SODetails = resp.so_details;
				$.each(SODetails, function(idx, detail){
					data = {
						fin_salesorder_id:detail.fin_salesorder_id,
						ItemId:detail.ItemId,
						ItemName:detail.ItemName,
						ItemDiscount:detail.ItemDiscount,
						fdc_qty:detail.fdc_qty,
						fdc_price:detail.fdc_price,
						ItemDiscount:detail.ItemDiscount,
						total:detail.fdc_qty * detail.fdc_price / detail.ItemDiscount,
						action: action
					}
					t = $('#tblSODetails').DataTable();			
					t.row.add(data).draw(false);

					//set Data select2		
					var newOption = new Option(detail.ItemName, detail.ItemId, false, false);
					$('#select-items').append(newOption).trigger('change');

					var newOption = new Option(detail.ItemDiscount, false);
					$('#select-disc').append(newOption).trigger('change');
				});

				$('#select-items').trigger('change');
					calculateTotal();

				// OnChange
				//var fdt_salesorder_date = $soDate;
				$("#fst_salesorder_no").change(function(){
					//alert ("fst_salesorder_no");
					$("#soDate").val(0);
					$("#soDate").prop('readonly', true);
				});

				$("#fdt_salesorder_date").datepicker('update', dateFormat(resp.sales_order.fdt_salesorder_date));

				// menampilkan data di select2, menu edit/update
				var newOption = new Option(resp.sales_order.RelationName, resp.sales_order.RelationId, true, true);
				// Append it to the select
    			$('#select-relations').append(newOption).trigger('change');

				var newOption = new Option(resp.sales_order.fst_warehouse_name, resp.sales_order.fin_warehouse_id, true, true);
    			$('#select-warehouse').append(newOption).trigger('change');

				var newOption = new Option(resp.sales_order.fst_username, resp.sales_order.fin_user_id, true, true);
				$('#select-sales').append(newOption).trigger('change');

				var newOption = new Option(resp.sales_order.fst_username, resp.sales_order.fin_user_id, true, true);
				$('#select-spv').append(newOption).trigger('change');

				var newOption = new Option(resp.sales_order.fst_username, resp.sales_order.fin_user_id, true, true);
				$('#select-mgr').append(newOption).trigger('change');
			},

			error: function (e) {
				$("#result").text(e.responseText);
				console.log("ERROR : ", e);
			}
		});
	}


</script>

<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/dataTables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript">
    $(function(){
        $(".select2-container").addClass("form-control"); 
        $(".select2-selection--single , .select2-selection--multiple").css({
            "border":"0px solid #000",
            "padding":"0px 0px 0px 0px"
        });         
        $(".select2-selection--multiple").css({
            "margin-top" : "-5px",
            "background-color":"unset"
        });
    });
</script>