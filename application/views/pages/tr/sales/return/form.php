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
	<h1><?=lang("Penjualan - Retur")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Penjualan") ?></a></li>
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
						<input type="hidden" class="form-control" id="fin_salesreturn_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_salesreturn_id" value="<?=$fin_salesreturn_id?>" readonly>

                        <div class="form-group">
							<label for="fst_salesreturn_no" class="col-md-2 control-label"><?=lang("No. Retur")?></label>	
							<div class="col-md-4">				
								<input type="TEXT" id="fst_salesreturn_no" name="fst_salesreturn_no" class="form-control"  value="<?=$fst_salesreturn_no?>" placeholder="PREFIX/BRANCH/YEAR/MONTH/99999" /> 
							</div>

							<label for="fdt_salesreturn_datetime" class="col-md-2 control-label text-right"><?=lang("Tanggal Retur")?> *</label>
							<div class="col-md-4">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control text-right datetimepicker" id="fdt_salesreturn_datetime" name="fdt_salesreturn_datetime" value=""/>
								</div>
								<div id="fdt_salesreturn_datetime_err" class="text-danger"></div>
								<!-- /.input group -->
							</div>

						</div>
												
						<div class="form-group">
                            <label for="fin_customer_id" class="col-md-2 control-label"><?=lang("Customer")?> </label>							
                            <div class="col-sm-10">
                                <select id="fin_customer_id" class="form-control" name="fin_customer_id" style="width:100%">
									<?php
										$customerList = $this->msrelations_model->getCustomerList();
										foreach($customerList as $customer){
											echo "<option value='$customer->fin_relation_id'>$customer->fst_relation_name</option>";
										}
									?>
								</select>
                            </div>                        
                        </div>

						<div class="form-group">							
							<div class="col-md-10 col-md-offset-2">
								<label class="checkbox-inline"><input id="fbl_non_faktur" name='fbl_non_faktur' type="checkbox" value="1"><?= lang("Retur Faktur yang sudah dibayar (tidak mengurangi nilai faktur) ") ?></label>
							</div>
						</div>												

						<div class="form-group">
							<label for="fst_curr_code" class="col-md-2 control-label"><?=lang("Mata Uang")?> </label>
                            <div class="col-sm-2">
								<select id="fst_curr_code" type="TEXT" class="form-control" name="fst_curr_code">
									<?php 
									$currencyList  = $this->mscurrencies_model->getCurrencyList();
									foreach($currencyList as $curr){
										$selected = $curr->fbl_is_default == 1 ? "selected" : "";
										echo "<option value='$curr->fst_curr_code' data-rate='$curr->fdc_exchange_rate_to_idr' $selected>$curr->fst_curr_code</option>";  
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

						<div class="form-group" style="margin-bottom:0px">
							<div class="col-md-12" style="text-align:right">
								<button id="btn-add-detail" class="btn btn-primary btn-sm"><i class="fa fa-cart-plus" aria-hidden="true"></i>Tambah Item</button>
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
								<label class="col-md-8 control-label" style="padding-top:0px"><?=lang("Total Disc")?> : </label>
								<label id="ttlDisc" class="col-md-4 control-label" style="padding-top:0px">0.00</label>
								
								<label class="col-md-8 control-label" style="padding-top:0px"><?=lang("DPP")?> : </label>
								<label id="ttlDPP" class="col-md-4 control-label" style="padding-top:0px">0.00</label>
																
								<label class="col-md-8 control-label"  style="padding-top:0px"><?=lang("Ppn")?>% :</label>								
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
				
							<form id="form-detail-return" class="form-horizontal">
									
									<div class="form-group">
										<label for="fin_inv_id" class="col-md-3 control-label"><?=lang("Faktur Penjualan")?> </label>							
										<div class="col-md-9">
											<select id="fin_inv_id" class="form-control" name="fin_inv_id" style="width:100%"></select>                                
										</div>							                    
									</div>

									<div class="form-group">
										<label for="fin_item_id" class="col-md-3 control-label">Items</label>
										<div class="col-md-9">
											<select id="fin_item_id" class="ele-total form-control " style="width:100%"></select>
										</div>
									</div>
									<div class="form-group">
										<label for="fst_custom_item_name" class="col-md-3 control-label">Custom Name</label>
										<div class="col-md-9">
											<input id="fst_custom_item_name" class="form-control">
										</div>
									</div>

									<div class="form-group">
										<label for="fst_unit" class=" col-md-3 control-label">Unit</label>
										<div class="col-md-9">
											<select id="fst_unit" class=" ele-total form-control" style="width:100%"></select>
										</div>
									</div>
									<div class="form-group">
										<label for="fst_unit" class="col-md-12 control-label"><input id="fbl_is_vat_include" type="Checkbox" checked style="margin-right:10px" class="ele-total"/> include Ppn </label>
									</div>

									<div class="form-group">
										<label for="fdb_qty" class="col-md-3 control-label">Qty</label>
										<div class="col-md-2">
											<input type="number" class="ele-total form-control text-right numeric" id="fdb_qty" value="1" min="1">
										</div>

										<label for="fdc_price" class="col-md-2 control-label">Price</label>
										<div class="col-md-5">
											<input type="text" class="ele-total form-control text-right money" id="fdc_price" value="0" style="text-align: right;">
										</div>
									</div>									

									<div class="form-group">
										<label for="fst_disc_item" class=" col-md-3 control-label">Disc ++</label>
										<div class="col-md-3">
											<select id="fst_disc_item" class="ele-total form-control text-right" style="width:100%;text-align-last: right;">
												<?php 
													$discList = $this->msitemdiscounts_model->getItemDiscountList();
													foreach($discList as $disc){
														echo "<option value='$disc->fst_item_discount'>$disc->fst_item_discount</option>";
													}
												?>
											</select>											
										</div>
										<div class="col-md-6">
											<input type="text" class="form-control text-right" id="fdc_disc_amount" value="0.00" disabled>
										</div>
									</div>
									
									<div class="form-group">
										<label for="fdc_disc_amount" class="col-md-3 control-label">DPP Amount</label>
										<div class="col-md-9">
											<input type="text" class="form-control text-right" id="fdc_dpp_amount" value="0.00" readonly />
										</div>
									</div>

									<div class="form-group">
										<label for="fdc_ppn_percent" class="col-md-3 control-label">Ppn</label>	
										<div class="col-md-3">
											<input type="text" id="fdc_ppn_percent" value="10" class="ele-total form-control text-right"/>
										</div>
										<div class="col-md-6">
											<input type="text" class="form-control text-right" id="fdc_ppn_amount" value="0.00" disabled>
										</div>																				
									</div>
									
									
									

									<div class="form-group">
										<label for="fdc_subtotal" class="col-md-3 control-label">Sub total</label>
										<div class="col-md-9">
											<input type="text" class="form-control text-right" id="fdc_subtotal" value="0.00" readonly />
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
	<script type="text/javascript">
		mdlDetail = {
			selectedInv:null,
			selectedItem:null,
			isNonFaktur:false,
			show:function(){
				$("#mdlDetail").modal("show");
				//mdlDetail.clearDetailFormOnInvChange();
			},
			close:function(){
				$("#mdlDetail").modal("hide");
			},
			clearDetailFormOnInvChange:function(){
				$("#fin_item_id").empty();
				$("#fst_custom_item_name").val("");
				$("#fst_unit").empty();
				$("#fdb_qty").val(1);
				$("#fdc_price").val(App.money_format(0));
				$("#fst_disc_item").val($("#fst_disc_item option:first").val());
				$("#fdc_disc_amount").val(App.money_format(0));
				$("#fdc_ppn_amount").val(App.money_format(0));
				$("#fdc_dpp_amount").val(App.money_format(0));
				$("#fdc_subtotal").val(App.money_format(0));
			},			
			calculateTotal:function(){
				var qty = $("#fdb_qty").val();
				var price = $("#fdc_price").val();
				var strDisc = $("#fst_disc_item").val();
				var ttlBfDisc = qty * price;				
				var discAmount = App.calculateDisc(ttlBfDisc, strDisc);
				if($("#fbl_is_vat_include").prop("checked") ){
					var dpp = (price * qty) / (1 + ($("#fdc_ppn_percent").val() / 100));
				}else{
					var dpp = (price * qty);
				}
				
				var ppn = dpp * ($("#fdc_ppn_percent").val() / 100)


				$("#fdc_disc_amount").val(App.money_format(discAmount));
				$("#fdc_dpp_amount").val(App.money_format(dpp));
				$("#fdc_ppn_amount").val(App.money_format(ppn));				
				$("#fdc_subtotal").val(App.money_format(dpp-discAmount+ppn));				
			},
			setUnitToSelect2:function(){
				$("#fst_unit").select2({
					minimumResultsForSearch: Infinity,
					ajax: {
						delay: 500,
						url: SITE_URL + "tr/sales/sales_return/get_sell_unit_list",
						data: function (params) {				
							params.finCustomerId = $("#fin_customer_id").val();
							params.finItemId =$("#fin_item_id").val();
							return params;
						},
						processResults: function (resp) {
							data  = resp.data;
							objList = data.map(function(obj){
								return {
									id:obj.fst_unit,
									text:obj.fst_unit,
									fdc_price:obj.fdc_price,
								}
							});
							return {
								results: objList
							};
						}
					},
				}).on("select2:select",function(e){
					data = e.params.data;
					$("#fdc_price").val(App.money_format(data.fdc_price));
					mdlDetail.calculateTotal();
				});
				App.fixedSelect2();
			},
			clearForm:function(){
				
			}		
		}
		
		$(function(){
			$("#fin_inv_id").select2({
				allowClear:true,
				debug:true,
				placeholder:"<?=lang("Pilih faktur.. ")?>",
				ajax: {
					delay: 500,
					url: SITE_URL + "tr/sales/sales_return/get_list_invoice",
					data: function (params) {
						params.isPaidInv = $("#fbl_non_faktur").prop("checked") == true ? 1 : 0;					
						params.finCustomerId = $("#fin_customer_id").val();
						params.fstCurrCode = $("#fst_curr_code").val();
						return params;
					},
					processResults: function (resp) {
						data  = resp.data;
						objList = data.map(function(obj){
							return {
								id:obj.fin_inv_id,
								text:obj.fst_inv_no,
								fdt_inv_datetime:obj.fdt_inv_datetime,
								fbl_is_vat_include:obj.fbl_is_vat_include,
								fdc_ppn_percent:obj.fdc_ppn_percent,
							}
						});
						return {
							results: objList
						};
					}
				},
				templateResult:function(inv){				
					if (inv.loading == true){
						return;
					}
					sstr = "<label>"+inv.text+"</label><label style='margin-left:20px'>" + App.dateTimeFormat(inv.fdt_inv_datetime) + "</label>";
					return $(sstr);
				}
			}).on('select2:select',function(e){
				mdlDetail.selectedInv = e.params.data;	
				mdlDetail.selectedItem = null;	

				var selectedInv = mdlDetail.selectedInv;

				$("#fbl_is_vat_include").prop("disabled",true);
				$("#fdc_ppn_percent").prop("disabled",true);
				$("#fst_disc_item").prop("disabled",true);

				fblVATInc = selectedInv.fbl_is_vat_include == 1 ? true : false;
				$("#fbl_is_vat_include").prop("checked",fblVATInc);				    
				$("#fdc_ppn_percent").val(selectedInv.fdc_ppn_percent);
				$("#fdc_price").val(0);

			}).on('select2:unselect',function(e){
				$("#fbl_is_vat_include").prop("disabled",false);
				$("#fdc_ppn_percent").prop("disabled",false);
				$("#fst_disc_item").prop("disabled",false);
				mdlDetail.selectedInv = null;
				mdlDetail.clearDetailFormOnInvChange();


			});

			$("#fin_item_id").select2({
				ajax: {
					delay: 500, //milliseconds
					url: function(params){
						if ($("#fin_inv_id").val() == null && $("#fbl_non_faktur").prop("checked") == false){
							alert("<?=lang("Return Faktur Belum bayar harus mengisi no faktur")?>");							
						}else{
							return SITE_URL + 'tr/sales/sales_return/get_item_by_inv';
						}		
						
					},					
					data: function(params){
						params.finInvId = $("#fin_inv_id").val();
						return params;
					},
					processResults: function (resp) {
						var data = resp.data;
						return {
							results: $.map(data,function(obj){
								/*
								fin_inv_detail_id,
								a.fin_item_id,
								b.fst_item_code,
								b.fst_item_name,
								a.fst_custom_item_name,
								a.fst_unit,
								(a.fdb_qty - a.fdb_qty_return) as fdb_qty_max_return,
								a.fdc_price,
								a.fst_disc_item,
								fdc_disc_amount_per_item
								*/


								obj.id = obj.fin_item_id;
								obj.text = obj.fst_custom_item_name;
								return obj;							
							})
						};
					}
				}
			}).on('select2:select', function (e) {
				mdlDetail.selectedItem =  e.params.data;
				
				var selectedItem = mdlDetail.selectedItem;
				App.log(selectedItem);

				$("#fst_custom_item_name").val(selectedItem.fst_item_name);
				//getItemBuyUnit(null);				
				if ($("#fin_inv_id").val() == null ){
					//Set Unit using select2
					mdlDetail.setUnitToSelect2();
				}else{
					//Unit sesuai invoice
					$("#fst_unit").empty();
					App.addOptionIfNotExist("<option value='"+ selectedItem.fst_unit +"' data-price='"+selectedItem.fdc_price+"' selected>"+selectedItem.fst_unit+"</option>","fst_unit");
					$("#fdb_qty").val(selectedItem.fdb_qty_max_return);
					$("#fdc_price").val(selectedItem.fdc_price);
					$("#fst_disc_item").val(selectedItem.fst_disc_item);
					$(".ele-total").trigger("change");
				}
			}).on('select2:opening',function (e){
				
			});
			
			$(".ele-total").change(function(e){				
				mdlDetail.calculateTotal();
			});
						
			$("#btn-add-detail-save").click(function(e){
				e.preventDefault();				
				t = $("#tbldetails").DataTable();

				selectedItem = mdlDetail.selectedItem;

				var dpp = 0;
				var discPerItem = App.calculateDisc($("#fdc_price").val(),$("#fst_disc_item").val());
				var discTotal = $("#fdb_qty").val() * discPerItem;

				if ($("#fbl_is_vat_include").prop("checked") ){					
					var subTotal  = $("#fdc_price").val() * $("#fdb_qty").val();					
					dpp = (subTotal - discTotal) / (1 + ($("#fdc_ppn_percent").val()/100));
				}else{
					dpp = $("#fdc_price").val() * $("#fdb_qty").val() - discTotal;
				}

				data =  {
					fin_rec_id:0,
					fin_item_id: $("#fin_item_id").val(),
					fin_inv_id:0,
					fst_inv_no:"",
					fin_inv_detail_id:0,
					fbl_is_vat_include: $("#fbl_is_vat_include").prop("checked"),
					fst_item_code:selectedItem.fst_item_code,
					fst_custom_item_name:$("#fst_custom_item_name").val(),
					fst_unit:$("#fst_unit").val(),
					fdc_price:$("#fdc_price").val(),
					fdb_qty:$("#fdb_qty").val(),
					fst_disc_item:$("#fst_disc_item").val(),
					fdc_disc_amount_per_item: discPerItem,
					fdc_dpp_amount: dpp,
					fdc_ppn_percent: $("#fdc_ppn_percent").val(),
					fdc_ppn_amount: dpp * ($("#fdc_ppn_percent").val() /100)					
				};
				
				if ( $("#fbl_non_faktur").prop("checked") == false && $("#fin_inv_id").val() == null){
					alert("<?=lang("Return Faktur Belum bayar harus mengisi no faktur")?>");
					return;
				}

				if ($("#fin_inv_id").val() == null ){

				}else{
					data.fin_inv_id = mdlDetail.selectedInv.id;
					data.fst_inv_no = mdlDetail.selectedInv.text;
					data.fin_inv_detail_id = selectedItem.fin_inv_detail_id;
					/*
					if (selectedItem.fdb_qty_max_return != null){
						if (parseFloat(selectedItem.fdb_qty_max_return) < parseFloat(data.fdb_qty)){
							alert("Max return " + selectedItem.fdb_qty_max_return);
							return;
						}
					}
					*/
				}

				if (selectedRow == null){
					t.row.add(data);
				}else{
					dataTmp = t.row(selectedRow).data();
					data.fin_rec_id = dataTmp.fin_rec_id;

					t.row(selectedRow).data(data);
				}		
				t.draw(false);				
				mdlDetail.close();
			});			
		});
	</script>
</div>

<?php echo $mdlEditForm ?>
<?php echo $mdlJurnal ?>

<script type="text/javascript" info="event">
	$(function(){
		
		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url()?>tr/sales/sales_return/add";
		});

		$("#btnSubmitAjax").click(function(e){
			e.preventDefault();
			submitAjax(0);
		});

		$("#btnJurnal").click(function(e){
			e.preventDefault();
			MdlJurnal.showJurnalByRef("SRT",$("#fin_salesreturn_id").val());
		});
		
		$("#btnDelete").click(function(e){
			e.preventDefault();
			deleteAjax(0);
		});
		
		$("#btnClose").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url()?>tr/sales/sales_return/";
		});


		$("#fbl_non_faktur").change(function(e){
			$("#tbldetails").DataTable().clear().draw(false);		
		});
		

		$("#fin_inv_id").change(function(e){
			e.preventDefault();
		});

		$("#fst_curr_code").change(function(e){
			e.preventDefault();			
			$("#fdc_exchange_rate_idr").val(App.money_format($("#fst_curr_code option:selected").data("rate")));
		});

		$("#btn-add-detail").click(function(e){
			e.preventDefault();
			selectedRow = null;
			mdlDetail.show();
		});

		
	
	});
</script>

<script type="text/javascript" info="define">
	var nonFaktur;
	var selectedRow = null;
</script>

<script type="text/javascript" info="init">
	$(function(){		
		$("#fdt_salesreturn_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");
		$("#fst_curr_code").trigger("change");

		$("#fin_customer_id").select2({
			templateResult:function(val){
				return $('<label style="width:100%">'+ val.text +'</label>');
			}
		}).on("change",function(e){
			$("#tbldetails").DataTable().clear().draw(false);
		});
		$("#fin_customer_id").val(null).trigger("change.select2");					
		
		$('#tbldetails').on('preXhr.dt', function ( e, settings, data ) {
			//add aditional data post on ajax call
			data.sessionId = "TEST SESSION ID";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "fin_rec_id","width": "0px",sortable:false,data:"fin_rec_id",visible:true},			
				{"title" : "Inv No","width": "100px",sortable:false,data:"fst_inv_no"},
				{"title" : "Item Code","width": "80px",sortable:false,data:"fst_item_code"},
				{"title" : "Item Name","width": "100px",sortable:false,data:"fst_custom_item_name"},
				{"title" : "Ppn Inc","width": "100px",sortable:false,data:"fbl_is_vat_include",className:"text-center",
					render:function(data,type,row){
						checked = data == true ? "checked" : "";
						return "<input type='checkbox' " + checked + "/>";
					}
				},
				{"title" : "Satuan","width": "100px",sortable:false,data:"fst_unit"},
				{"title" : "Qty","width": "100px",sortable:false,data:"fdb_qty",className:'text-right'},
				{"title" : "Price","width": "100px",sortable:false,data:"fdc_price",className:'text-right',
					render:function(data,type,row){
						return App.money_format(data);
					}
				},
				{"title" : "Disc","width": "100px",sortable:false,className:'text-right',
					render:function(data,type,row){						
						discAmount = parseFloat(row.fdb_qty) * parseFloat(row.fdc_disc_amount_per_item);
						return App.money_format(discAmount);
					}
				},
				{"title" : "DPP","width": "100px",sortable:false,className:'text-right',
					render:function(data,type,row){						
						return App.money_format(row.fdc_dpp_amount);
					}
				},				
				{"title" : "Ppn","width": "100px",sortable:false,className:'text-right',
					render:function(data,type,row){												
						return App.money_format(row.fdc_ppn_amount);
					}
				},

				{"title" : "Total","width": "100px",sortable:false,className:'text-right',
					render:function(data,type,row){						
						return App.money_format(parseFloat(row.fdc_dpp_amount) + parseFloat(row.fdc_ppn_amount));
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
			calculateTotal();
		}).on('click','.btn-edit',function(e){
			e.preventDefault();
			mdlDetail.show();

			t = $("#tbldetails").DataTable();
			var trRow = $(this).parents('tr');
			selectedRow = t.row(trRow);
			var data = t.row(selectedRow).data();
			
			$("#fin_inv_id").empty();
			if (data.fin_inv_id != 0){
				$("#fin_inv_id").trigger({
					type:"select2:select",
					params:{	
						data:{				
							id:data.fin_inv_id,
							text:data.fst_inv_no,								
							fdt_inv_datetime:data.fdt_inv_datetime,
							fbl_is_vat_include:data.fbl_is_vat_include,
							fdc_ppn_percent:data.fdc_ppn_percent,
						}					
					}
				});
				App.addOptionIfNotExist("<option value='"+data.fin_inv_id+"' selected>"+data.fst_inv_no+"</option>","fin_inv_id");
			}

			$("#fin_item_id").empty();
			$("#fin_item_id").trigger({
				type:"select2:select",
				params:{	
					data:{				
						id:data.fin_item_id,
						text:data.fst_custom_item_name,
						fin_inv_detail_id:data.fin_inv_detail_id,
						fin_item_id:data.fin_item_id,
						fst_item_code:data.fst_item_code,
						fst_item_name:data.fst_item_name,
						fst_custom_item_name:data.fst_custom_item_name,
						fst_unit:data.fst_unit,
						fdb_qty_max_return:null,
						fdc_price:data.fdc_price,
						fst_disc_item:data.fst_disc_item,
						fdc_disc_amount_per_item:data.fdc_disc_amount_per_item
					}					
				}
			});
			$("#fin_item_id").append("<option value='"+data.fin_item_id+"'>"+data.fst_item_code + " - " + data.fst_custom_item_name +"</option>");
			
			$("#fst_custom_item_name").val(data.fst_custom_item_name);
			
			$("#fst_unit").empty();
			$("#fst_unit").append("<option value='"+data.fst_unit+"'>"+data.fst_unit+"</option>" );
			
			$("#fdb_qty").val(data.fdb_qty);
			$("#fdc_price").val(data.fdc_price);
			$("#fst_disc_item").val(data.fst_disc_item);
			mdlDetail.calculateTotal();
			

		}).on('click','.btn-delete',function(e){
			e.preventDefault();
			t = $('#tbldetails').DataTable();
			var trRow = $(this).parents('tr');
			t.row(trRow).remove().draw();
		});
				
		App.fixedSelect2();
		initForm();
	});
</script>

<script type="text/javascript" info="function">
	function calculateTotal(){

		t= $('#tbldetails').DataTable();
		var datas = t.rows().data();
		var total = 0;
		var totalDisc = 0;
		var totalDPP = 0;
		var totalPPN = 0;
		$.each(datas,function(i,data){

			var discAmount =  parseFloat(data.fdc_disc_amount_per_item) *  parseFloat(data.fdb_qty);
			var subttl =  parseFloat(data.fdc_dpp_amount)  +  parseFloat(data.fdc_ppn_amount);			
			total += subttl;
			totalDisc += discAmount;
			totalDPP += parseFloat(data.fdc_dpp_amount);
			totalPPN += parseFloat(data.fdc_ppn_amount);

		});

		//$("#ttlSubTotal").text(App.money_format(total));
		$("#ttlDisc").text(App.money_format(totalDisc));
		$("#ttlDPP").text(App.money_format(totalDPP));
		$("#ppnAmount").text(App.money_format(totalPPN));
		$("#ttlAmount").text(App.money_format(totalDPP + totalPPN));

	}

	function getDetailSalesInv(callback){
		//params = JSON.stringify(arrLPBGudangId);
		
		
		App.getValueAjax({			
			site_url:"<?= site_url()?>",
			model:"trsalesreturn_model",
			func:"getSalesInvoice",
			params:[$("#fin_inv_id").val()],
			callback:function(resp){
				invoice = resp.invoice;
				invoiceDetails = resp.invoiceDetails;
				if(invoice == null){
					alert('<?=lang("Faktur Penjualan tidak ditemukan")?>');
				}

				$("#fst_curr_code").val(invoice.fst_curr_code).trigger("change");
				$("#fin_warehouse_id").val(invoice.fin_warehouse_id);

				var t =  $("#tbldetails").DataTable();
				t.clear();
				$.each(invoiceDetails,function(i,detail){
					var data ={
						fin_rec_id:0,
						fin_salesorder_detail_id:detail.fin_po_detail_id,
						fin_item_id:detail.fin_item_id,
						fst_item_code:detail.fst_item_code,
						fst_custom_item_name:detail.fst_custom_item_name,
						fst_unit:detail.fst_unit,
						fdc_price:detail.fdc_price,
						fst_disc_item:detail.fst_disc_item,
						fdc_disc_amount_per_item:detail.fdc_disc_amount_per_item,
						fdb_qty_max_return :detail.fdb_ttl_qty_out - detail.fdb_ttl_qty_return,
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

	function submitAjax(confirmEdit){

		var dataSubmit = $("#frmTransaction").serializeArray();
		
		var mode = $("#fin_salesreturn_id").val() == "0" ? "ADD" : "EDIT";	

		if (mode == "ADD"){
			url =  "<?= site_url() ?>tr/sales/sales_return/ajx_add_save/";
		}else{
			dataSubmit.push({
				name : "fin_user_id_request_by",
				value: MdlEditForm.user
			});
			dataSubmit.push({
				name : "fst_edit_notes",
				value: MdlEditForm.notes
			});

			url =  "<?= site_url() ?>tr/sales/sales_return/ajx_edit_save/";
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
		var finSalesReturnId = $("#fin_salesreturn_id").val();
		if (finSalesReturnId != 0){
			//get data from server;
			App.blockUIOnAjaxRequest();
			$.ajax({
				url:"<?=site_url()?>tr/sales/sales_return/fetch_data/" + finSalesReturnId,
				method:"GET",								
			}).done(function(resp){
				if(resp.message != ""){
					alert(resp.message);
				}

				if (resp.status == "SUCCESS"){	
					
					dataH = resp.data.salesreturn;
					detailData = resp.data.salesreturn_details;
					

					App.autoFillForm(dataH);
					$("#fbl_non_faktur").trigger("change");				
					$("#fdt_salesreturn_datetime").val(App.dateTimeFormat(dataH.fdt_salesreturn_datetime)).datetimepicker("update");
					$("#fin_customer_id").val(dataH.fin_customer_id).trigger("change.select2");

					t = $("#tbldetails").DataTable();
					dataDetails =[];

					$.each(detailData,function(i,dataD){
						data =  {
							fin_rec_id:dataD.fin_rec_id,
							fin_item_id: dataD.fin_item_id,
							fin_inv_id:dataD.fin_inv_id,
							fst_inv_no:dataD.fst_inv_no,
							fdt_inv_datetime:dataD.fdt_inv_datetime,							
							fin_inv_detail_id:dataD.fin_inv_detail_id,
							fbl_is_vat_include: dataD.fbl_is_vat_include,
							fst_item_code:dataD.fst_item_code,
							fst_custom_item_name:dataD.fst_custom_item_name,
							fst_unit:dataD.fst_unit,
							fdc_price:dataD.fdc_price,
							fdb_qty:dataD.fdb_qty,
							fst_disc_item:dataD.fst_disc_item,
							fdc_disc_amount_per_item: dataD.fdc_disc_amount_per_item,
							fdc_dpp_amount: dataD.fdc_dpp_amount,
							fdc_ppn_percent: dataD.fdc_ppn_percent,
							fdc_ppn_amount: dataD.fdc_ppn_amount							
						};
						dataDetails.push(data);
					});
					t.clear();
					t.rows.add(dataDetails).draw(false);

					/*
					getSalesInvoice(function(resp){
						//console.log($("#fin_inv_id option[value='"+ dataH.fin_inv_id +"']").length);
						App.addOptionIfNotExist("<option value='"+ dataH.fin_inv_id +"'>" + dataH.fst_inv_no + "</option>","fin_inv_id");
						$("#fin_inv_id").val(dataH.fin_inv_id).trigger("change.select2");
					});

										
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
					calculateTotal();*/

				}else{
					//$("#btnNew").trigger("click");
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

		var url =  "<?= site_url() ?>tr/sales/sales_return/delete/" + $("#fin_salesreturn_id").val();
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