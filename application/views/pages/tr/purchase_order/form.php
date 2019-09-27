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
	<h1><?=lang("Purchase Order")?><small><?=lang("form")?></small></h1>
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
						<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
						<a id="btnClose" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
					</div>
				</div>
				<!-- end box header -->
				<!-- form start -->
				<form id="frmPurchaseOrder" class="form-horizontal" action="<?=site_url()?>tr/purchase_order/add" method="POST" enctype="multipart/form-data">
					<div class="box-body">
						<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">			
						<input type="hidden" id="frm-mode" value="<?=$mode?>">
						<input type="hidden" class="form-control" id="fin_po_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_po_id" value="<?=$fin_po_id?>" readonly>
						
						<div class="form-group">
							<label for="fst_po_no" class="col-md-2 control-label"><?=lang("Purchase Order No")?> #</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="fst_po_no" placeholder="<?=lang("Purchase Order No")?>" name="fst_po_no" value="<?=$fst_po_no?>" readonly>
								<div id="fst_po_no_err" class="text-danger"></div>
							</div>
							
							<label for="fdt_po_datetime" class="col-md-2 control-label"><?=lang("Purchase Order Date")?> *</label>
							<div class="col-md-4">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control text-right datetimepicker" id="fdt_po_datetime" name="fdt_po_datetime"/>
								</div>
								<div id="fdt_po_datetime_err" class="text-danger"></div>
								<!-- /.input group -->
							</div>						
						</div>

						<div class="form-group">						
							<label for="fst_curr_code" class="col-md-2 control-label"><?=lang("Mata Uang")?> </label>
							<div class="col-md-4">
								<select id="fst_curr_code" class="form-control" name="fst_curr_code">
									<?php
										$currencies = getDataTable("mscurrencies","*","fst_active ='A'");
										foreach($currencies as $currency){
											$selected = $currency->fst_curr_code == "IDR" ? "SELECTED" : "";
											echo "<option value='$currency->fst_curr_code' $selected>$currency->fst_curr_name</option>";
										}
									?>
									<option value="<?=$default_currency['CurrCode']?>"><?=$default_currency['CurrName']?></option>
								</select>
								<div id="fst_curr_code_err" class="text-danger"></div>
							</div>
						
							<label for="fdc_exchange_rate_idr" class="col-md-2 control-label"><?=lang("Nilai Tukar IDR")?> </label>
							<div class="col-md-2">
								<input type="text" class="text-right form-control" id="fdc_exchange_rate_idr" name="fdc_exchange_rate_idr" style="width:100%" value="" readonly/>
							</div>
							<label class="col-md-2 control-label" style="text-align:left;padding-left:0px"><?=lang("Rupiah")?> </label>
						</div>

						<div class="form-group">						
							<label for="select-relations" class="col-md-2 control-label"><?=lang("Suplier")?> </label>
							<div class="col-md-4">
								<select id="fin_supplier_id" class="form-control non-editable" name="fin_supplier_id">
								<?php									
									$suppliers = $this->msrelations_model->getSupplierList();									
									foreach($suppliers as $supplier){										
										echo "<option value='$supplier->fin_relation_id' $selected>$supplier->fst_relation_name</option>";
									}									
								?>
								</select>
								<div id="fin_supplier_id_err" class="text-danger"></div>
							</div>
						
							<label for="fin_term" class="col-md-2 control-label"><?=lang("Term")?> </label>
							<div class="col-md-1">
								<input type="text" class="form-control" id="fin_term" name="fin_term" style="width:50px" value="0"/>
								<div id="fin_term_err" class="text-danger"></div>
							</div>
							<label class="col-md-2 control-label" style="text-align:left;padding-left:0px"><?=lang("Hari")?> </label>
						</div>

						<div class="form-group">
							<label for="fin_warehouse_id" class="col-md-2 control-label"><?=lang("Warehouse")?> </label>
							<div class="col-md-10">
								<div class="pull-left" style="width:20%" >
									<select id="fin_warehouse_id" class="form-control" name="fin_warehouse_id">
									<?php
										$warehouses = $this->mswarehouse_model->getWarehouseList();
										foreach($warehouses as $warehouse){
											echo "<option value='$warehouse->fin_warehouse_id' data-address='$warehouse->fst_delivery_address'>$warehouse->fst_warehouse_name</option>";
										}
									?>
									</select>
									<div id="fin_warehouse_id_err" class="text-danger"></div>
								</div>
								<div class="pull-left" style="width:26%" >
									<div class="form-group">
										<label for="fst_do_no" class="col-md-5 control-label"><?=lang("Nomor DO")?> </label>
										<div class="col-md-7">
											<input type="text" class="form-control" id="fst_do_no" name="fst_do_no"/>
											<div id="fst_do_no_err" class="text-danger"></div>
										</div>
									</div>
								</div>
								<div class="pull-left" style="width:35%;min-width:300px" >
									<div class="form-group">
										<label for="fst_contract_no" class="col-md-5 control-label"><?=lang("Nomor Kontrak")?> </label>
										<div class="col-md-7">
											<input type="text" class="form-control" id="fst_contract_no" name="fst_contract_no"/>
											<div id="fst_contract_no_err" class="text-danger"></div>
										</div>
									</div>
								</div>

								<div style="clear:both"></div>
								
							</div>							
						</div>

						<div class="form-group">
							<label for="fst_delivery_address" class="col-md-2 control-label"><?=lang("Alamat Pengiriman")?></label>
							<div class="col-md-10">
								<textarea class="form-control" id="fst_delivery_address" style="width:100%" name="fst_delivery_address" rows="5"></textarea>
								<div id="fst_delivery_address_err" class="text-danger"></div>
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

						<table id="tblPODetails" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
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
										<input type="number" class="form-control text-right" id="fdc_ppn_percent" name="fdc_ppn_percent" value="10" >
									</div>
									<div class="col-md-4" style='text-align:right'>
										<input type="text" class="form-control text-right" id="fdc_ppn_amount" name="fdc_ppn_amount" value="0" readonly>	
									</div>
								</div>

								<div class="form-group">
									<label for="total" class="col-md-8 control-label"><?=lang("Total")?></label>
									<div class="col-md-4" style='text-align:right'>
										<input type="text" class="form-control text-right" id="total" value="0" readonly>
									</div>
								</div>	
								<div class="form-group">
									<label for="total" class="col-md-8 control-label">Uang Muka</label>
									<div class="col-md-4" style="text-align:right">
										<input type="text" class="money form-control text-right" id="fdc_downpayment" name="fdc_downpayment" value="0" style="text-align: right;">
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
<div id="mdlAddDetail" class="modal fade" role="dialog" >
	<div class="modal-dialog" style="display:table;width:800px">
		<!-- modal content -->
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
			<div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Add PO Detail")?></h4>
			</div>

			<div class="modal-body">
				<div class="row">
                    <div class="col-md-12" >
                        <div style="border:0 px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">
				
								<form id="form-detail" class="form-horizontal">
									<input type='hidden' id='fin_po_detail_id'/>
									<div class="form-group">
										<label for="fin_item_id" class="col-md-2 control-label"><?=lang("Items")?></label>
										<div class="col-md-10">
											<select id="fin_item_id" class="form-control" style="width:100%"></select>
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
										<label for="fst_unit" class="col-md-2 control-label"><?=lang("Unit")?></label>
										<div class="col-md-10">
											<select id="fst_unit" name="fst_unit" class="form-control" style="width:100%"></select>
										</div>
									</div>

									<div class="form-group">
										<label for="fdb_qty" class="col-md-2 control-label"><?=lang("Qty")?></label>
										<div class="col-md-2">
											<input type="number" class="ele-disc form-control text-right numeric" id="fdb_qty" value="1" min="1">
										</div>

										<label for="fdc_price" class="col-md-2 control-label"><?=lang("Price")?></label>
										<div class="col-md-6">
											<input type="text" class="ele-disc form-control text-right money" id="fdc_price" value="0">
										</div>
									</div>

									<div class="form-group">
										<label for="fst_disc_item" class=" col-md-2 control-label"><?=lang("Disc ++")?></label>
										<div class="col-md-10">
											<select id="fst_disc_item" class="ele-disc form-control text-right" style="width:100%">
											<?php
												$discList = $this->msitemdiscounts_model->getItemDiscountList();
												foreach($discList as $disc){
													echo "<option value='$disc->fst_item_discount'>$disc->fst_item_discount</option>";
												}
											?>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label for="fdc_disc_amount" class="col-md-2 control-label"><?=lang("Disc Amt")?></label>
										<div class="col-md-10">
											<input type="text" class="form-control text-right" id="fdc_disc_amount" readonly>
										</div>
									</div>

									

									<div class="form-group">
										<label for="fst_memo_item" class="col-md-2 control-label"><?=lang("Memo")?></label>
										<div class="col-md-10">
											<textarea type="text" class="form-control" id="fst_notes_detail" rows="3"></textarea>
										</div>
									</div>

								</form>
								
								<div class="modal-footer">
									<button id="btn-add-po-detail" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Add")?></button>
									<button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	echo $mdlJurnal;
?>

			
<script type="text/javascript">
	var selectedDetail;


	$(function(){
		initVarForm();
		
		
		$("#btnPrint").click(function(e){
			layoutColumn = [
				{column: "Kode",hidden:false,id:"fst_kode"},
				{column: "Nama",hidden:false,id:"fst_nama"},
				{column: "Harga Beli",hidden:true,id:"fdc_harga_beli"},
				{column: "Satuan",hidden:true,id:"fst_unit"},
				{column: "Harga Jual",hidden:true,id:"fdc_harga_jual"},
			];
			url = "<?= site_url() ?>tr/purchase_order/print_po";
			MdlPrint.showPrint(layoutColumn,url);
			
		});

		$("#btnJurnal").click(function(e){
			e.preventDefault();
			$("#mdlJurnal").modal({
				backdrop:"static",
			});
		});

		$("#btnSubmitAjax").click(function(event){
			event.preventDefault();
			saveAjax();
		});

		//$("#fdt_salesorder_date").datepicker('update', dateFormat("<= date("Y-m-d")?>"));
		$("#fdt_po_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");

		$("#btn-add-detail").click(function(event){
			event.preventDefault();
			selectedDetail = null;			
			$("#mdlAddDetail").modal({
				backdrop:"static",
			});
			clearDetailForm();				
		})

		$(document).bind('keydown', 'alt+d', function(){
			$("#btn-add-detail").trigger("click");
		});


		$("#btn-add-po-detail").click(function(event){
			event.preventDefault();

			
			selected_items = $("#fin_item_id").val();
			unit = $("#fst_unit").val();
			qty = App.money_parse($("#fdb_qty").val());
			price = App.money_parse($("#fdc_price").val());			
			disc_persen = $("#fst_disc_item").val();
			amount = price * qty;
			disc_amount = App.calculateDisc (qty * price,disc_persen);


			data = {
				fin_po_detail_id:$("#fin_po_detail_id").val(),
				fin_item_id:$("#fin_item_id").val(),
				ItemName: $("#fin_item_id").find(':selected').data('fst_item_name'),
				ItemCode: $("#fin_item_id").find(':selected').data('fst_item_code'),
				fst_custom_item_name:$("#fst_custom_item_name").val(),
				fdb_qty: qty,
				fst_unit: unit,
				fdc_price : price,
				fst_disc_item : disc_persen,
				fdc_disc_amount: disc_amount,
				fst_notes: $("#fst_notes_detail").val(),
			}

			t = $('#tblPODetails').DataTable();

			if (selectedDetail != null) {
				row = t.row(selectedDetail).data();
				console.log(row);
				//updatedData = {...row,...data};
				data.fdb_qty_lpb = row.fdb_qty_lpb;
				if (data.fdb_qty < data.fdb_qty_lpb){
					alert("Qty tidak bole lebih kecil dari Qty LPB (" + data.fdb_qty_lpb + ")");
					return;
				}

				t.row(selectedDetail).data(data).draw(false);
				selectedDetail = null;
			}else{
				data.fdb_qty_plb = 0;
				t.row.add(data).draw(false);	
			}
			calculateTotal();
			clearDetailForm();
		});

		$(".ele-disc").change(function(e){
			e.preventDefault();
			qty  = $("#fdb_qty").val();
			price = $("#fdc_price").val();
			strDisc = $("#fst_disc_item").val();

			total = App.money_parse(qty) * App.money_parse(price);
			discAmount = App.calculateDisc(total,strDisc);
			$("#fdc_disc_amount").val(App.money_format(discAmount));

		});
		$("#fdc_ppn_percent").change(function(e){
			calculateTotal();
		});

		$('#tblPODetails').on('preXhr.dt', function ( e, settings, data ) {
		 	//add aditional data post on ajax call
		 	data.sessionId = "TEST SESSION ID";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "Action","width": "40px",sortable:false,className:'dt-body-center text-center',
					render: function(data,type,row){
						var action = '<a class="btn-edit" href="#" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp;';
						console.log(typeof row.fdb_qty_lpb);
						if(row.fdb_qty_lpb == 0 || typeof row.fdb_qty_lpb === 'undefined' ){
							action += '<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
						}
						return action;
					}
				},
				{"title" : "id",sortable:false,data:"fin_po_detail_id",visible:true},
				{"title" : "Items","width": "250px",sortable:false,data:"fin_item_id",
					render: function(data,type,row){
						return row.ItemCode + "-" + row.fst_custom_item_name;
					}
				},
				{"title" : "Qty",data:"fdb_qty",className:'text-right'},
				{"title" : "Qty LPB",width:"50px",className:'text-right',
					render:function(data,type,row){
						if (typeof row.fdb_qty_lpb === "undefined"){
							return 0;
						}
						return row.fdb_qty_lpb;
					}
				},
				{"title" : "Unit",width:"50px",data:"fst_unit"},
				{"title" : "Price",width:"80px",data:"fdc_price",
					render: $.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					className:'text-right'
				},
				{"title" : "Disc ++",width:"50px",data:"fst_disc_item",
					//render: $.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					className:'text-right'
				},
				{"title" : "Disc Amt",width:"80px",data:"fdc_disc_amount",
					render: $.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					className:'text-right'
				},				
				{"title" : "Total",width:"80px",className:'text-right',
					render: function(data,type,row){
						//$.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
						return App.money_format((row.fdb_qty * row.fdc_price) -  row.fdc_disc_amount);
					},
				},
				{"title" : "Memo","width": "200px",data:"fst_notes"},
				
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
				t = $('#tblPODetails').DataTable();
				var trRow = $(this).parents('tr');

				t.row(trRow).remove().draw();
				calculateTotal();
			});

			$(".xbtn-edit").click();
		});


		$("#tblPODetails").on("click",".btn-delete",function(event){
			t = $('#tblPODetails').DataTable();
			var trRow = $(this).parents('tr');
			t.row(trRow).remove().draw();
			calculateTotal();
		});

		$("#tblPODetails").on("click",".btn-edit",function(event){
			event.preventDefault();			
			$("#mdlAddDetail").modal({
				backdrop:"static",
			});

			t = $('#tblPODetails').DataTable();
			var trRow = $(this).parents('tr');
			selectedDetail = trRow;

			edited_po_detail = t.row(trRow);
			row = edited_po_detail.data();
			selectedRow = row;
			$("#fin_po_detail_id").val(row.fin_po_detail_id);			
			$('#fin_item_id').val(row.fin_item_id).trigger("change.select2");
			setUnitItemList(row.fin_item_id,function(resp){
				$('#fst_unit').val(row.fst_unit).trigger("change.select2");
			});						

			$('#fin_item_id').prop('disabled', true);

			$("#fst_custom_item_name").val(row.fst_custom_item_name);
			$('#fst_disc_item').val(row.fst_disc_item).trigger('change');
			
			//$('#select-items').trigger({type:"select2:select"});
			$("#fdb_qty").val(row.fdb_qty);
			$("#fdc_price").val(money_format(row.fdc_price));
			$("#fdc_disc_amount").val(money_format(row.fdc_disc_amount));
			$("#fst_notes_detail").val(row.fst_notes);				
		});


		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>tr/purchase_order/add")
		});
		$("#btnDelete").confirmation({
			title:"<?=lang("Hapus data ini ?")?>",
			rootSelector: '#btnDelete',
			placement: 'left',
		});
		$("#btnDelete").click(function(e){
			e.preventDefault();
			blockUIOnAjaxRequest("<h5>Deleting ....</h5>");
			$.ajax({
				url:"<?= site_url() ?>tr/purchase_order/delete/" + $("#fin_po_id").val(),
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
									window.location.href = "<?= site_url() ?>tr/purchase_order/lizt";
									//return;
								}
							},
						}
					});
				}
			});
		});
		
		$("#btnClose").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>tr/purchase_order/lizt");
		});
		
		if( $("#fin_po_id").val() != 0 ){
			fillForm();
		}
	});

	
	function initVarForm(){		
		$("#fst_curr_code").change(function(e){
			e.preventDefault();
			$.ajax({
				url: "<?=site_url()?>api/get_value",
				method:"POST",
				data:{
					"model": "mscurrencies_model",
					"function": "getRate",
					"params": [$("#fst_curr_code").val(),"2019-08-16"], // $("#fst_curr_code").val(),  //KRW,2019-07-23"
				},
			}).done(function(resp){
				$("#fdc_exchange_rate_idr").val(App.money_format(resp.data));
			});
		});

		$("#fdc_exchange_rate_idr").val(App.money_format(1));


		//Supplier		
		$("#fin_supplier_id").select2({
			placeholder: "<?= lang("Supplier")?>",
			//data:arrSupplier,
		});
		$("#fin_supplier_id").val(null).change();
		
		$("#fin_supplier_id").change(function(e){
			if ($("#fin_supplier_id").val() != null){
				setItemList($("#fin_supplier_id").val(),function(resp){});
			}
		});
		
		

		//warehouse
		$("#fin_warehouse_id").select2({
			placeholder:"<?= lang("Gudang")?>",
		});
		
		$("#fin_warehouse_id").val(null).change();
		$("#fin_warehouse_id").change(function(e){
			$("#fst_delivery_address").val($(this).find(':selected').data('address'));
		});		

		$("#fst_disc_item").select2({
			placeholder:"<?= lang("Discount")?>",
		});
		$("#fst_disc_item").val(null).change();


		$("#fin_item_id").select2();
		$("#fin_item_id").change(function(e){
			e.preventDefault();
			$("#fst_custom_item_name").val($(this).find(':selected').data('fst_item_name'));
			setUnitItemList($("#fin_item_id").val(),function(resp){});
		});

		$("#fst_unit").select2();
		$("#fst_unit").change(function(e){
			e.preventDefault();			
			//get last buy price
			if ($("#fin_item_id").val() == null || $("#fst_unit").val() == null ){
				$("#fdc_price").val(App.money_format(0));
				return;
			}else{
				console.log($("#fin_item_id").val());
				console.log($("#fst_unit").val());
			}

			App.getValueAjax({
				url: "<?=site_url()?>api/get_value",
				model:"trpo_model",
				func:"getLastBuyPrice",
				params:[
					$("#fin_item_id").val(),
					$("#fst_unit").val()
				],
				callback:function(value){					
					$("#fdc_price").val(App.money_format(value));
				}
			});			
		});
		fixedSelect2();
    }

	function clearDetailForm(){
		$("#fin_po_detail_id").val(0);
		$('#fin_item_id').val(null).trigger('change.select2');
		$('#fin_item_id').prop('disabled', false);
		$('#select-items').focus();
		$("#fst_custom_item_name").val("");
		$('#fst_disc_item').val("0").trigger('change.select2');
		$('#fst_unit').val(null).trigger('change');
		
		$("#fdb_qty").val(1);
		$("#fdc_price").val(0);
		$("#fdc_disc_amount").val(0);
		$("#fst_notes").val("");
		selectedDetail = null;
	}

	function calculateTotal(){
		t = $('#tblPODetails').DataTable();
		datas = t.data();
		ttlBfDisc = 0;
		ttlDisc = 0;

		$.each(datas,function(i,v){
			ttlBfDisc += v.fdb_qty * v.fdc_price;
			ttlDisc +=  v.fdc_disc_amount * 1;
		});

		subTotal = ttlBfDisc - ttlDisc;
		vat_persen = parseFloat($("#fdc_ppn_percent").val());
		vat_amount = subTotal * (vat_persen/100)
		total = subTotal + vat_amount;

		$("#sub-total").val(money_format(subTotal));	
		$("#fdc_ppn_amount").val(money_format(vat_amount));
		$("#total").val(money_format(total));		
	}


	function fillForm(){

		App.blockUIOnAjaxRequest("Please wait while loading data.....");
		$.ajax({
			url:"<?=site_url()?>tr/purchase_order/fetch_data/" + $("#fin_po_id").val(),
			method:"GET",
		}).done(function(resp){
			dataH = resp.po;
			dataD = resp.po_details;
			if (dataH == null){
				alert("PO tidak dikenal !");
				$("#btnNew").trigger("click");
				return;
			}


			$.each(dataH, function(name, val){
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

			$("#fin_supplier_id").val(dataH.fin_supplier_id).trigger("change.select2");
			$("#fin_supplier_id").val(dataH.fin_supplier_id).trigger("change");

			$("#fin_warehouse_id").val(dataH.fin_warehouse_id).trigger("change.select2");
			$("#fdt_po_datetime").val(App.dateTimeFormat(dataH.fdt_po_datetime)).datetimepicker('update');			

			t = $('#tblPODetails').DataTable();
			$.each(dataD,function(i,row){				
				t.row.add(row).draw(false);
			});
			calculateTotal();
		});

		/*
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

			t = $('#tblPODetails').DataTable();			
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


		fixedSelect2();
		//$(".non-editable").prop('disabled', true);
		calculateTotal();		
		//$("#fdt_salesorder_date").datepicker('update', dateTimeFormat(resp.sales_order.fdt_salesorder_date));
		$("#fdt_salesorder_date").val(dateTimeFormat(resp.sales_order.fdt_salesorder_date)).datetimepicker('update');
		*/
	
	}

	function saveAjax(){
		data = $("#frmPurchaseOrder").serializeArray();
		detail = new Array();		
		t = $('#tblPODetails').DataTable();
		datas = t.data();

		$.each(datas,function(i,v){
			detail.push(v);
		});

		data.push({
			name:"detail",
			value: JSON.stringify(detail)
		});
		
		
		mode = $("#fin_po_id").val() == "0" ? "ADD" : "EDIT";	


		if (mode == "ADD"){
			url =  "<?= site_url() ?>tr/purchase_order/ajx_add_save/";
		}else{
			url =  "<?= site_url() ?>tr/purchase_order/ajx_edit_save/";
		}
		

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
									//window.location.href = "<?= site_url() ?>tr/sales_order/lizt";
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
					$("#fin_po_id").val(data.insert_id);
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

	function setItemList(suplierCode,callback){
		blockUIOnAjaxRequest("<h1>Please wait..!</h1>");
		$.ajax({
			url:"<?=site_url()?>tr/purchase_order/get_item/" + suplierCode,
		}).done(function(resp){			
			//arrItems = resp.data.arrItem
			var options = "";
			$.each(resp.data.arrItem ,function(i,item){
				options += "<option value='" + item.id + "' data-fst_item_code='"+item.fst_item_code +"' data-fst_item_name='" + item.fst_item_name +"'>" + item.text + "</option>";
			});
			$("#fin_item_id").empty();
			$("#fin_item_id").append(options);	
			$("#fin_item_id").val(null).trigger('change.select2');
			callback(resp);
		});				
	}

	function setUnitItemList(fin_item_id,callback){
		if (fin_item_id == null){
			$("#fst_unit").empty();
			return;
		}
		blockUIOnAjaxRequest("<h1>Please wait..!</h1>");
		$.ajax({
			url:"<?=site_url()?>tr/purchase_order/get_item_unit/" + fin_item_id,
		}).done(function(resp){
			var options = "";
			$.each(resp.data.arrUnit ,function(i,unit){
				options += "<option value='" + unit.id + "'>" + unit.text + "</option>";
			});
			$("#fst_unit").empty();
			$("#fst_unit").append(options);	
			$("#fst_unit").val(null).trigger('change.select2');
			callback(resp);
		});


		

	}

</script>

<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>