<?php
	defined('BASEPATH') OR exit('No direct script access allowed');	
?>

<link rel="stylesheet" href="<?=base_url()?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<style>
	.form-group{
		margin-bottom:10px;
	}
</style>

<section class="content-header">
	<h1><?=lang("Pembelian - Retur")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Pembelian") ?></a></li>
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
						<a id="btnJurnal" class="btn btn-primary" href="#" title="Jurnal" style="display:<?= $mode == "ADD" ? "none" : "inline-block" ?>"><i class="fa fa-align-left" aria-hidden="true"></i></a>
						<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
						<a id="btnClose" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
					</div>
				</div>
				<!-- end box header -->

				<!-- form start -->
				<form id="frmTransaction" class="form-horizontal"  method="POST" enctype="multipart/form-data">			
					<div class="box-body">
						<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">	
						<input type="hidden" class="form-control" id="fin_purchasereturn_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_purchasereturn_id" value="<?=$fin_purchasereturn_id?>" readonly>

						<div class="form-group">
							<label for="fst_delivery_address" class="col-md-2 control-label"></label>
							<div class="col-md-10">								
								<label class="radio-inline"><input type="radio" id="fblIsImportFalse" class="fbl_is_import" name="fbl_is_import" value="0" checked="">Lokal</label>
								<label class="radio-inline"><input type="radio" id="fblIsImportTrue" class="fbl_is_import" name="fbl_is_import" value="1">Import</label>
							</div>
						</div>

                        <div class="form-group">
							<label for="fst_purchasereturn_no" class="col-md-2 control-label"><?=lang("No. Retur")?></label>	
							<div class="col-md-4">				
								<input type="TEXT" id="fst_purchasereturn_no" name="fst_purchasereturn_no" class="form-control"  value="<?=$fst_purchasereturn_no?>" placeholder="PREFIX/BRANCH/YEAR/MONTH/99999" /> 
							</div>

							<label for="fdt_purchasereturn_datetime" class="col-md-2 control-label text-right"><?=lang("Tanggal Retur")?> *</label>
							<div class="col-md-4">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control text-right datetimepicker" id="fdt_purchasereturn_datetime" name="fdt_purchasereturn_datetime" value=""/>
								</div>
								<div id="fdt_purchasereturn_datetime_err" class="text-danger"></div>
								<!-- /.input group -->
							</div>

						</div>
												
						<div class="form-group">
                            <label for="fin_vendor_id" class="col-md-2 control-label"><?=lang("Supplier")?> </label>							
                            <div class="col-sm-10">
                                <select id="fin_supplier_id" class="form-control" name="fin_supplier_id">
									<?php
										$supplierList = $this->msrelations_model->getSupplierList();
										foreach($supplierList as $supplier){
											echo "<option value='$supplier->fin_relation_id'>$supplier->fst_relation_name</option>";
										}
									?>
								</select>
                            </div>                        
                        </div>

						<div class="form-group">							
							<div class="col-md-10 col-md-offset-2">
								<label class="checkbox-inline"><input id="fbl_non_faktur" name='fbl_non_faktur' type="checkbox" value="1">Non Faktur</label>
							</div>
						</div>
						
						<div id="divLPBPurchase" class="form-group">
                            <label for="fin_lpbpurchase_id" class="col-md-2 control-label"><?=lang("Faktur Pembelian")?> </label>							
                            <div class="col-sm-10">
								<select id="fin_lpbpurchase_id" class="form-control" name="fin_lpbpurchase_id" style="width:100%"></select>                                
							</div>							                    
						</div>

						<div class="form-group">
							<label for="fst_curr_code" class="col-md-2 control-label"><?=lang("Mata Uang")?> </label>
                            <div class="col-sm-2">
								<select id="fst_curr_code" type="TEXT" class="form-control" name="fst_curr_code" disabled>
									<?php foreach($arrExchangeRate as $key=>$value){
										echo "<option value='$key'>$key</option>";  
									}
									?>
								</select>
							</div>										                    
							<label for="fdc_exchange_rate_idr" class="col-md-2 control-label"><?=lang("Nilai Tukar IDR")?> </label>
                            <div class="col-sm-2">
								<input type="TEXT" id="fdc_exchange_rate_idr"  name="fdc_exchange_rate_idr" class="form-control money"/>
								<div id="fdc_exchange_rate_idr_err" class="text-danger"></div>
							</div>										                    							
						</div>
						
						<div class="form-group">
                            <label for="fin_warehouse_id" class="col-md-2 control-label"><?=lang("Gudang")?> </label>							
                            <div class="col-sm-10">
								<select id="fin_warehouse_id" class="form-control" name="fin_warehouse_id" style="width:100%">
									<?php
										$warehouseList = $this->mswarehouse_model->getNonLogisticWarehouseList();
										foreach($warehouseList as $warehouse){
											
											echo "<option value='$warehouse->fin_warehouse_id'>$warehouse->fst_warehouse_name</option>";
										}
									?>
								</select>                                
							</div>							                    
						</div>
						


						<div class="form-group" style="margin-bottom:0px">
							<div class="col-md-12" style="text-align:right">
								<button id="btn-add-detail" class="btn btn-primary btn-sm" style="display:none"><i class="fa fa-cart-plus" aria-hidden="true"></i>Tambah Item</button>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-12">
								<table id="tbldetails" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
							</div>
							<div id="details_err" class="text-danger"></div>
						</div>

                        <div class="form-group">
                            							
                            <div class="col-sm-6">
								<label for="fin_vendor_id" class=""><?=lang("Memo :")?> </label>
                                <textarea class="form-control" id="fst_memo" placeholder="<?= lang("Memo") ?>" name="fst_memo" rows="3" style="resize:none;width:100%"></textarea>
                                <div id="fst_memo_err" class="text-danger"></div>
							</div> 
							<div class="col-sm-6" style="padding-right:0px">
								<label class="col-md-8 control-label" style="padding-top:0px"><?=lang("Sub Total")?> : </label>
								<label id="ttlSubTotal" class="col-md-4 control-label" style="padding-top:0px">0.00</label>
								
								<label class="col-md-8 control-label" style="padding-top:0px"><?=lang("Total Disc")?> : </label>
								<label id="ttlDisc" class="col-md-4 control-label" style="padding-top:0px">0.00</label>
								
								<label class="col-md-8 control-label"  style="padding-top:0px"><?=lang("Ppn")?> 
									<input id="fdc_ppn_percent" name="fdc_ppn_percent" style="width:40px" class="text-right"  value="10" /> 
									% :
								</label>								
								<label id="ppnAmount" class="col-md-4 control-label"  style="padding-top:0px">0.00</label>

								<label class="col-md-8 control-label"  style="padding-top:0px"><?=lang("Total")?> : </label>
								<label id="ttlAmount" class="col-md-4 control-label" style="padding-top:0px" >0.00</label>
							</div>
							
						</div>
						


					</div><!-- end box body -->
					

					<div class="box-footer text-right">
						<!-- <a id="btnSubmitAjaxOld" href="#" class="btn btn-primary"><=lang("Save Ajax")?></a> -->
					</div><!-- end box-footer -->
					
				</form>
        	</div>
    	</div>
	</div>
</section>

<div id="mdlDetail" class="modal fade" role="dialog">
	<div class="modal-dialog" style="display:table;width:700px">
		<!-- modal content -->
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">			
			<div class="modal-body">
				<div class="row">
                    <div class="col-md-12">
                        <div style="border:0 px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">
				
							<form id="form-detail" class="form-horizontal">
									<input type="hidden" id="fin_po_detail_id" value="0">
									<div class="form-group">
										<label for="fin_item_id" class="col-md-3 control-label">Items</label>
										<div class="col-md-9">
											<select id="fin_item_id" class="form-control " style="width:100%"></select>
										</div>
									</div>
									<div class="form-group">
										<label for="fst_custom_item_name" class="col-md-3 control-label">Custom Name</label>
										<div class="col-md-9">
											<input id="fst_custom_item_name" class="form-control">
										</div>
									</div>

									<div class="form-group">
										<label for="fst_unit" class="col-md-3 control-label">Unit</label>
										<div class="col-md-9">
											<select id="fst_unit" class="form-control" style="width:100%"></select>
										</div>
									</div>

									<div class="form-group">
										<label for="fdb_qty" class="col-md-3 control-label">Qty</label>
										<div class="col-md-2">
											<input type="number" class="ele-disc form-control text-right numeric" id="fdb_qty" value="1" min="1">
										</div>

										<label for="fdc_price" class="col-md-2 control-label">Price</label>
										<div class="col-md-5">
											<input type="text" class="ele-disc form-control text-right money" id="fdc_price" value="0" style="text-align: right;">
										</div>
									</div>

									<div class="form-group">
										<label for="fst_disc_item" class=" col-md-3 control-label">Disc ++</label>
										<div class="col-md-9">
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
										<label for="fdc_disc_amount" class="col-md-3 control-label">Disc Amount</label>
										<div class="col-md-9">
											<input type="text" class="form-control text-right" id="fdc_disc_amount" readonly />
										</div>
									</div>
									<div class="form-group">
										<label for="fdc_disc_amount" class="col-md-3 control-label">Sub total</label>
										<div class="col-md-9">
											<input type="text" class="form-control text-right" id="fdc_subtotal" readonly />
										</div>
									</div>
								</form>

								<div class="modal-footer">
									<button id="btn-add-detail-save" type="button" class="btn btn-primary btn-sm text-center" style="width:15%">Add</button>
									<button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal">Close</button>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php echo $mdlEditForm ?>
<?php echo $mdlJurnal ?>

<script type="text/javascript" info="event">
	$(function(){
		
		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url()?>tr/purchase/purchase_return/add";
		});

		$("#btnSubmitAjax").click(function(e){
			e.preventDefault();
			submitAjax(0);
		});

		$("#btnJurnal").click(function(e){
			e.preventDefault();
			MdlJurnal.showJurnalByRef("PRT",$("#fin_purchasereturn_id").val());
		});
		
		$("#btnDelete").click(function(e){
			e.preventDefault();
			deleteAjax(0);
		});
		
		$("#btnClose").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url()?>tr/purchase/purchase_return/";
		});

		$("#fbl_non_faktur").change(function(e){
			nonFaktur = $("#fbl_non_faktur").prop("checked");
			if(nonFaktur){
				$("#divLPBPurchase").fadeOut("slow");
				
				$("#fin_lpbpurchase_id").val(null).trigger("change.select2");
				$("#fst_curr_code").prop("disabled",false);
				$("#btn-add-detail").show();
			}else{
				$("#divLPBPurchase").fadeIn("slow");
				$("#fst_curr_code").prop("disabled",true);
				$("#btn-add-detail").hide();
			}
			$("#tbldetails").DataTable().clear().draw(false);		
		});

		$(".fbl_is_import").change(function(e){
			e.preventDefault();
			$("#fin_supplier_id").trigger("change");

		});

		$("#fin_supplier_id").change(function(e){
			e.preventDefault();
			//console.log($("#fin_supplier_id").val());
			
			getLPBPurchase(function(resp){
				$("#tbldetails").DataTable().clear().draw(false);
				calculateTotal();
			});			
		});

		$("#fin_lpbpurchase_id").change(function(e){
			e.preventDefault();
			getDetailLPBPurchase(function(resp){

			});

		});

		$("#fst_curr_code").change(function(e){
			e.preventDefault();
			var exchangeRate = arrExchangeRate[$("#fst_curr_code").val()];
			$("#fdc_exchange_rate_idr").val(App.money_format(exchangeRate));
		});

		$("#btn-add-detail").click(function(e){
			e.preventDefault();
			clearDetailForm();
			$("#mdlDetail").modal("show");
		});

		$('#fin_item_id').on('select2:select', function (e) {
			selectedItem =  e.params.data;
			$("#fst_custom_item_name").val(selectedItem.fst_item_name);
			getItemBuyUnit(null);
		});
		
		$(".ele-disc").change(function(e){
			var qty = $("#fdb_qty").val();
			var price = $("#fdc_price").val();
			var strDisc = $("#fst_disc_item").val();
			var ttlBfDisc = qty * price;
			var discAmount = App.calculateDisc(ttlBfDisc, strDisc);
			var ttlAfDisc = ttlBfDisc - discAmount;
			$("#fdc_disc_amount").val(App.money_format(discAmount));
			$("#fdc_subtotal").val(App.money_format(ttlAfDisc));			
		});

		$("#btn-add-detail-save").click(function(e){
			e.preventDefault();
			t = $("#tbldetails").DataTable();
			//var selectedItem = $("#fin_item_id").select2("data")[0];
			var data ={
				fin_rec_id:0,
				fin_po_detail_id:0,
				fin_item_id: $("#fin_item_id").val(),
				fst_item_code:selectedItem.fst_item_code,
				fst_custom_item_name:$("#fst_custom_item_name").val(),
				fst_unit:$("#fst_unit").val(),
				fdc_price:$("#fdc_price").val(),
				fst_disc_item:$("#fst_disc_item").val(),
				fdb_qty_total:0,
				fdb_qty_return:$("#fdb_qty").val()
			}

			if(rowDetail == null){
				//new data
				t.row.add(data);
			}else{
				//edit data
				dataTbl = t.row(rowDetail).data();
				if(dataTbl.fin_po_detail_id == 0){
					t.row(rowDetail).data(data);
				}else{	
					//Hanya bisa merubah Qty Return
					dataTbl.fdb_qty_return = $("#fdb_qty").val();
					t.row(rowDetail).data(dataTbl);				
				}
			}
			t.draw(false);
			calculateTotal();
			clearDetailForm();
			$("#mdlDetail").modal("hide");

		})

		$("#fdc_ppn_percent").change(function(e){
			e.preventDefault();
			calculateTotal();
		})
	
	});
</script>

<script type="text/javascript" info="define">
	var nonFaktur;
	var rowDetail = null;
	var selectedItem;
	var arrExchangeRate =  new Array();

	<?php foreach($arrExchangeRate as $key=>$value){ ?>
		arrExchangeRate["<?=$key?>"] = "<?= $value->fdc_rate ?>";
	<?php }	?>
	
	$(function(){
		$('#tbldetails').on('preXhr.dt', function ( e, settings, data ) {
			//add aditional data post on ajax call
			data.sessionId = "TEST SESSION ID";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "fin_rec_id","width": "0px",sortable:false,data:"fin_rec_id",visible:false},
				{"title" : "fin_po_detail_id","width": "0px",sortable:false,data:"fin_po_detail_id",visible:false},				
				{"title" : "Item id","width": "0px",sortable:false,data:"fin_item_id",visible:false},
				{"title" : "Item Code","width": "80px",sortable:false,data:"fst_item_code"},
				{"title" : "Item Name","width": "100px",sortable:false,data:"fst_custom_item_name"},
				{"title" : "Unit","width": "100px",sortable:false,data:"fst_unit"},
				{"title" : "Price","width": "100px",sortable:false,data:"fdc_price",className:'text-right',
					render:function(data,type,row){
						return App.money_format(data);
					}
				},
				{"title" : "Discount","width": "100px",sortable:false,data:"fst_disc_item",className:'text-center'},
				{"title" : "Disc Amount","width": "100px",sortable:false,className:'text-right',
					render:function(data,type,row){
						discAmount = App.calculateDisc((row.fdc_price * row.fdb_qty_return), row.fst_disc_item);
						return App.money_format(discAmount);
					}
				},
				{"title" : "Max Return","width": "100px",sortable:false,data:"fdb_qty_total",className:'text-right'},
				{"title" : "Return","width": "100px",sortable:false,data:"fdb_qty_return",className:'text-right'},
				{"title" : "Total","width": "100px",sortable:false,className:'text-right',
					render:function(data,type,row){
						var total = row.fdb_qty_return * row.fdc_price;
						var discAmount = App.calculateDisc((row.fdc_price * row.fdb_qty_return), row.fst_disc_item);
						return App.money_format(total - discAmount);
					}
				},
				{"title" : "Action","width": "75px",sortable:false,className:'text-center',
					render:function(data,type,row){
						var action = '<a class="btn-edit" href="#" data-original-title="" title=""><i class="fa fa-pencil"></i></a>';
						action += '<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
						return action;
					}
				}
			],
			processing: true,
			serverSide: false,
			searching: false,
			lengthChange: false,
			paging: false,
			info:false,				
		}).on('draw',function(){
			$(".dataTables_scrollHeadInner").css("min-width","100%");
			$(".dataTables_scrollHeadInner > table").css("min-width","100%");
		}).on('click','.btn-edit',function(e){
			e.preventDefault();
			t = $("#tbldetails").DataTable();
			var trRow = $(this).parents('tr');
			rowDetail = trRow;
			var data = t.row(trRow).data();
			selectedItem = data;

			$("#fin_item_id").empty();
			$("#fin_item_id").append("<option value='"+data.fin_item_id+"'>"+data.fst_item_code + " - " + data.fst_custom_item_name +"</option>");
			$("#fst_custom_item_name").val(data.fst_custom_item_name);
			$("#fst_unit").empty();
			$("#fst_unit").append("<option value='"+data.fst_unit+"'>"+data.fst_unit+"</option>" );
			$("#fdb_qty").val(data.fdb_qty_return);
			$("#fdc_price").val(data.fdc_price);
			$("#fst_disc_item").val(data.fst_disc_item);
			//$("#fdc_disc_amount").val(App.calculateDisc(data.fdb_qty_return * data.fdc_price,data.fst_disc_item) );
			$(".ele-disc").trigger("change");

			$("#mdlDetail").modal("show");
			
		}).on('click','.btn-delete',function(e){
			e.preventDefault();
			t = $('#tbldetails').DataTable();
			var trRow = $(this).parents('tr');
			t.row(trRow).remove().draw();
			calculateTotal();
		});

	});
</script>

<script type="text/javascript" info="init">
	function testSelec(val){		
		return $('<label style="width:100%">'+ val.text +'</label>');
		//return '<span>'+val+'</span>';
	}

	$(function(){
		$("#fdt_purchasereturn_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");
		$("#fin_supplier_id").select2({templateResult:testSelec});
		$("#fin_supplier_id").val(null).trigger("change.select2");		
		$("#fin_lpbpurchase_id").select2();	
		$("#fst_curr_code").val(null);
		$("#fin_warehouse_id").val(null);

		//Form Detail
		$("#fin_item_id").select2({
			ajax: {
				url: '<?=site_url()?>/select_data/get_items_by_supplier',
				delay: 250, //milliseconds
				data: function(params){
					params.finSupplierId = $("#fin_supplier_id").val();
					return params;
				},
				processResults: function (resp) {
					// Transforms the top-level key of the response object from 'items' to 'results'
					var data = resp.data;
					//$("#fin_item_id").empty();
					return {
						results: $.map(data,function(obj){
							obj.id = obj.fin_item_id;
							obj.text = obj.fst_item_code + " - " + obj.fst_item_name;
							//$("#fin_item_id").append("<option value='"+obj.id+"' data-code='"+obj.fst_item_code+"' data-name='"+obj.fst_item_name +"'>"+ obj.text +"</option>");
							return obj;
						})
					};
				}
			}
		});
		
		App.fixedSelect2();
		initForm();
	});


</script>

<script type="text/javascript" info="function">
	

	function calculateTotal(){

		t= $('#tbldetails').DataTable();
		var datas = t.rows().data();
		console.log(datas);
		var total = 0;
		var totalDisc = 0;
		
		$.each(datas,function(i,data){
			var subttl =  parseFloat(data.fdb_qty_return * data.fdc_price);
			var discAmount =  App.calculateDisc(subttl,data.fst_disc_item);
			total += subttl;
			totalDisc += discAmount;			
		});

		$("#ttlSubTotal").text(App.money_format(total));
		$("#ttlDisc").text(App.money_format(totalDisc));

		var ppnPercent = $("#fdc_ppn_percent").val();
		var ttlBeforePPn = total - totalDisc
		var ppnAmount = ttlBeforePPn * ppnPercent / 100;
		var totalAfterPPn = ttlBeforePPn + ppnAmount;

		$("#ppnAmount").text(App.money_format(ppnAmount));
		$("#ttlAmount").text(App.money_format(totalAfterPPn));

	}

	function getLPBPurchase(callback){
		if ($("#fin_supplier_id").val() == null){
			return;
		}

		var isImport = $(".fbl_is_import:checked").val();
		App.getValueAjax({
			site_url:"<?=site_url()?>",
			model:"trpurchasereturn_model",
			func:"getListPurchaseFaktur",
			params:[$("#fin_supplier_id").val(),isImport],
			callback:function(resp){
				var lPBPurchaseList = resp;
				$("#fin_lpbpurchase_id").empty();
				$.each(lPBPurchaseList ,function(i,v){
					$("#fin_lpbpurchase_id").append("<option value='"+v.fin_lpbpurchase_id+"'>"+ v.fst_lpbpurchase_no +"</option>")
				});
				$("#fin_lpbpurchase_id").val(null);
				//$("#tbldetails").DataTable().clear().draw(false);				
				callback(resp);
			}
		});
	}
	function getDetailLPBPurchase(callback){
		//params = JSON.stringify(arrLPBGudangId);
		
		
		App.getValueAjax({			
			site_url:"<?= site_url()?>",
			model:"trpurchasereturn_model",
			func:"getLPBPurchase",
			params:[$("#fin_lpbpurchase_id").val()],
			callback:function(resp){
				lPBPurchase = resp.lPBPurchase;
				lPBPurchaseDetails = resp.lPBPurchaseDetails;
				if(lPBPurchase == null){
					alert('<?=lang("Faktur Pembelian tidak ditemukan")?>');
				}

				$("#fst_curr_code").val(lPBPurchase.fst_curr_code).trigger("change");
				$("#fin_warehouse_id").val(lPBPurchase.fin_warehouse_id);

				var t =  $("#tbldetails").DataTable();
				t.clear();
				$.each(lPBPurchaseDetails,function(i,detail){
					var data ={
						fin_rec_id:0,
						fin_po_detail_id:detail.fin_po_detail_id,
						fin_item_id:detail.fin_item_id,
						fst_item_code:detail.fst_item_code,
						fst_custom_item_name:detail.fst_custom_item_name,
						fst_unit:detail.fst_unit,
						fdc_price:detail.fdc_price,
						fst_disc_item:detail.fst_disc_item,
						fdb_qty_total:detail.fdb_qty_lpb - detail.fdb_qty_return,
						fdb_qty_return:0
					}
					t.row.add(data);
				});
				t.draw(false);
				calculateTotal();
			}
		});
	}

	function getItemBuyUnit(defaultValue){
		App.getValueAjax({
			site_url:"<?= site_url()?>",
			model:"msitemunitdetails_model",
			func:"getBuyingListUnit",
			params:[$("#fin_item_id").val()],
			callback:function(units){
				$("#fst_unit").empty();
				$.each(units,function(i,unit){
					$("#fst_unit").append("<option value='" +unit.fst_unit + "' data-price='"+ unit.fdc_last_buy_price+"'>"+unit.fst_unit+"</option>");
				});
				$("#fst_unit").val(defaultValue).trigger("change");				
			}
		});

	}

	function clearDetailForm(){
		$("#fin_item_id").empty();
		//$("#fin_item_id").append("<option value='"+data.fin_item_id+"'>"+data.fst_item_code + " - " + data.fst_custom_item_name +"</option>");
		$("#fst_custom_item_name").val("");
		$("#fst_unit").empty();
		//$("#fst_unit").append("<option value='"+data.fst_unit+"'>"+data.fst_unit+"</option>" );
		$("#fdb_qty").val(1);
		$("#fdc_price").val(App.money_format(0));
		//$("#fst_disc_item").val(null);
		$("#fst_disc_item").val($("#fst_disc_item option:first").val());
		$("#fdc_disc_amount").val(App.money_format(0));
		rowDetail = null;
	}

	function submitAjax(confirmEdit){

		var dataSubmit = $("#frmTransaction").serializeArray();
		
		var mode = $("#fin_purchasereturn_id").val() == "0" ? "ADD" : "EDIT";	

		if (mode == "ADD"){
			url =  "<?= site_url() ?>tr/purchase/purchase_return/ajx_add_save/";
		}else{
			dataSubmit.push({
				name : "fin_user_id_request_by",
				value: MdlEditForm.user
			});
			dataSubmit.push({
				name : "fst_edit_notes",
				value: MdlEditForm.notes
			});

			url =  "<?= site_url() ?>tr/purchase/purchase_return/ajx_edit_save/";
		}

		if (confirmEdit == 0 && mode != "ADD"){
			MdlEditForm.saveCallBack = function(){
				submitAjax(1);
			};		
			MdlEditForm.show();
			return;
		}

		var details = [];		
		var datas =$("#tbldetails").DataTable().data();		
		$.each(datas,function(i,v){
			details.push(v);
		});

		dataSubmit.push({
			name:"details",
			value: JSON.stringify(details)
		});

		App.blockUIOnAjaxRequest("Please wait while saving data.....");
		$.ajax({
			type: "POST",
			//enctype: 'multipart/form-data',
			url: url,
			data: dataSubmit,
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

	function initForm(){
			
		var finPurchaseReturnId = $("#fin_purchasereturn_id").val();
		if (finPurchaseReturnId != 0){
			//get data from server;
			App.blockUIOnAjaxRequest();
			$.ajax({
				url:"<?=site_url()?>tr/purchase/purchase_return/fetch_data/" + finPurchaseReturnId,
				method:"GET",								
			}).done(function(resp){
				if(resp.message != ""){
					alert(resp.message);
				}

				if (resp.status == "SUCCESS"){	
					
					dataH = resp.data.purchasereturn;
					detailData = resp.data.purchasereturn_details;
					
					App.autoFillForm(dataH);
					$("#fbl_non_faktur").trigger("change");				
					$(".fbl_is_import [value='" + dataH.fbl_is_import +"']").prop("checked",true);
					$("#fdt_purchasereturn_datetime").val(App.dateTimeFormat(dataH.fdt_purchasereturn_datetime)).datetimepicker("update");
					$("#fin_supplier_id").val(dataH.fin_supplier_id).trigger("change.select2");
					
					getLPBPurchase(function(resp){
						//console.log($("#fin_lpbpurchase_id option[value='"+ dataH.fin_lpbpurchase_id +"']").length);
						App.addOptionIfNotExist("<option value='"+ dataH.fin_lpbpurchase_id +"'>" + dataH.fst_lpbpurchase_no + "</option>","fin_lpbpurchase_id");
						$("#fin_lpbpurchase_id").val(dataH.fin_lpbpurchase_id).trigger("change.select2");
					});
					t = $("#tbldetails").DataTable();					
					$.each(detailData , function(i,dataD){
						data = {
							fin_rec_id:dataD.fin_rec_id,
							fin_po_detail_id:dataD.fin_po_detail_id,
							fin_item_id:dataD.fin_item_id,
							fst_item_code:dataD.fst_item_code,
							fst_custom_item_name:dataD.fst_custom_item_name,
							fst_unit:dataD.fst_unit,
							fdc_price:dataD.fdc_price,
							fst_disc_item:dataD.fst_disc_item,
							fdb_qty_total:dataD.fdb_total_lpb - dataD.fdb_qty_return,
							fdb_qty_return:dataD.fdb_qty
						}
						t.row.add(data);						
					});									
					t.draw(false);
					calculateTotal();
				}else{
					$("#btnNew").trigger("click");
				}
			});
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

		var url =  "<?= site_url() ?>tr/purchase/purchase_return/delete/" + $("#fin_purchasereturn_id").val();
		$.ajax({
			url:url,
			method:"POST",
			data:dataSubmit,
		}).done(function(resp){
			if (resp.message != ""){
				alert(resp.message);
			}

			if(resp.status == "SUCCESS"){
				$("#btnClose").trigger("click");			
			}

		});
		


	}
</script>

<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>