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
							<label for="fst_salesreturn_no" class="col-md-2 control-label"><?=lang("No. Retur")?> #</label>	
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
						
						<div class="form-group" style="display:none">
							<label for="fst_curr_code" class="col-md-2 control-label"><?=lang("Mata Uang")?> </label>
                            <div class="col-sm-2">
								<select id="fst_curr_code" type="TEXT" class="form-control" name="fst_curr_code">
									<?php 
									$curr = getDefaultCurrency();
									echo "<option value='$curr[CurrCode]' data-rate='1'>$curr[CurrCode]</option>";  									
									?>
								</select>
							</div>										                    
							<label for="fdc_exchange_rate_idr" class="col-md-2 control-label"><?=lang("Nilai Tukar IDR")?> </label>
                            <div class="col-sm-2">
								<input type="TEXT" id="fdc_exchange_rate_idr"  name="fdc_exchange_rate_idr" class="form-control money" value="1"/>
								<div id="fdc_exchange_rate_idr_err" class="text-danger"></div>
							</div>										                    							
						</div>

						<div class="form-group">
                            <label for="fst_lpbsalesreturn_id_list" class="col-md-2 control-label"><?=lang("Penerimaan Retur")?> </label>
                            <div class="col-md-8">
                                <select id="fst_lpbsalesreturn_id_list" class="form-control" name="fst_lpbsalesreturn_id_list[]" multiple="multiple" style="width:100%"></select>
                            </div>
							<div class="col-md-2" style="text-align:right">
								<button id="btn-get-detail" class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Get Details</button>
							</div>
                        </div>

						<div class="form-group" style="margin-bottom:0px;display:none">
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
								<label for="fin_vendor_id" class=""><?=lang("Memo")?> </label>
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

<div id="mdlDetail" class="modal fade" role="dialog" style="display:none">
	<div class="modal-dialog" style="display:table;width:700px">
		<!-- modal content -->
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">			
			<div class="modal-body">
				<form id="form-detail-return" class="form-horizontal">
						
					<div class="form-group">
						<label class="col-md-3 control-label"><?=lang("Faktur Penjualan")?> </label>							
						<div class="col-md-9">
							<input type="text" id="dfst_inv_no" class="form-control" name="fst_inv_no" readonly />
						</div>							                    
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Items</label>
						<div class="col-md-9">
							<input type="text" id="dfin_item_id" class="form-control" readonly/>
						</div>
					</div>
					

					<div class="form-group">
						<label class=" col-md-3 control-label">Unit</label>
						<div class="col-md-9">
							<input type="text" id="dfst_unit" class="form-control" readonly/>
						</div>
					</div>
					

					<div class="form-group">
						<label class="col-md-3 control-label">Qty</label>
						<div class="col-md-2">
							<input type="number" class="form-control text-right numeric" id="dfdb_qty" value="1" min="1" readonly>
						</div>

						<label class="col-md-2 control-label">Price</label>
						<div class="col-md-5">
							<input type="text" class="form-control text-right money" id="dfdc_price" value="0" style="text-align: right;" readonly>
						</div>
					</div>									

					<div class="form-group">
						<label class=" col-md-3 control-label">Disc</label>						
						<div class="col-md-9">
							<input type="text" class="form-control text-right" id="dfdc_disc_amount_per_item" value="0.00" readonly>
						</div>
					</div>

					<div class="form-group">
						<label class=" col-md-3 control-label">Potongan</label>
						<div class="col-md-9">
							<input type="text" class="form-control money text-right" id="dfdc_potongan" value="0.00">
						</div>
					</div>
					

					<div class="form-group">
						<label for="fdc_subtotal" class="col-md-3 control-label">Sub total</label>
						<div class="col-md-9">
							<input type="text" class="form-control text-right" id="dfdc_subtotal" value="0.00" readonly />
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button id="btn-add-detail-save" type="button" class="btn btn-primary btn-sm text-center" style="width:15%">Add</button>
				<button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>

	<script type="text/javascript" info="define">
		var mdlDetail = {
			show:function(){
				if (selectedRow !=  null){
					var data = selectedRow.data();
					$("#dfst_inv_no").val(data.fst_inv_no);
					$("#dfin_item_id").val(data.fst_item_code + " - " + data.fst_item_name);
					$("#dfst_unit").val(data.fst_unit);
					$("#dfdb_qty").val(data.fdb_qty);
					$("#dfdc_price").val(App.money_format(data.fdc_price));
					$("#dfdc_disc_amount_per_item").val(App.money_format(data.fdc_disc_amount_per_item));
					$("#dfdc_potongan").val(App.money_format(data.fdc_potongan));					
					mdlDetail.calculateTotal();
					$("#mdlDetail").modal("show");				
				}
				
			},
			hide:function(){
				$("#mdlDetail").modal("hide");
			},
					
			calculateTotal:function(){
				var qty = $("#dfdb_qty").val();
				var price = parseFloat(App.money_parse($("#dfdc_price").val()));
				var discAmount = parseFloat(App.money_parse($("#dfdc_disc_amount_per_item").val()));
				
				var subTotal = qty * price;
				var ttlDisc = qty *discAmount;
				var potongan = parseFloat(App.money_parse($("#dfdc_potongan").val()));
				$("#dfdc_subtotal").val(App.money_format(subTotal - ttlDisc - potongan));
			},			
			clear:function(){
				$("#dfst_inv_no").val("");
				$("#dfin_item_id").val("");
				$("#dfst_unit").val("");
				$("#dfdb_qty").val("1");
				$("#dfdc_price").val("0");
				$("#dfdc_disc_amount_per_item").val("0");
				$("#dfdc_potongan").val(0);
				selectedRow = null;				
			}		
		}
	</script>

	<script type="text/javascript" info="init">	
		$(function(){			
						
		});
	</script>

	<script type="text/javascript" info="event">
		$(function(){
			$("#dfdc_potongan").change(function(e){
				mdlDetail.calculateTotal();
			});

			$("#btn-add-detail-save").click(function(e){
				e.preventDefault();				
				var data = selectedRow.data();
				data.fdc_potongan = App.money_parse($("#dfdc_potongan").val());
				t.row(selectedRow).data(data);
				t.draw(false);				
				mdlDetail.hide();
			});
		});
	</script>

	<script type="text/javascript" info="function">
	</script>
</div>

<?php echo $mdlEditForm ?>
<?php echo $mdlJurnal ?>
<?php echo $mdlPrint ?>

<script type="text/javascript" info="define">
	var tblDetails;
	var nonFaktur;
	var selectedRow = null;
	var lpbSalesRetunListChanged = false;
	var fblIncPPN = <?=$fblPPNInc?>;
	var fdcPPNPercent = <?=$fdcPPNPercent?>;

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

		$("#fst_lpbsalesreturn_id_list").select2({
			ajax:{
				url:"<?=site_url()?>tr/sales/sales_return/ajxGetLPBSalesReturnList",
				delay: 250,
				data: function (params) {				
					params.fin_customer_id = $("#fin_customer_id").val();
					params.fst_curr_code =$("#fst_curr_code").val();
					return params;
				},
				processResults: function (resp) {
					if (resp.messages != ""){
						alert(resp.messages);
					}

					if (resp.status == "SUCCESS"){
						data  = resp.data;
						var arrData=[];
						$.each(data,function(i,v){
							arrData.push({
								id:v.fin_lpbsalesreturn_id,
								text:v.fst_lpbsalesreturn_no,
							})
						});
						return {
							results: arrData
						};
					}
				}

			}
			
						
		}).on("change",function(e){
			lpbSalesRetunListChanged = true;
			$("#btnSubmitAjax").hide();
			

			//console.log(e.params.data);
			//console.log($("#fst_lpbsalesreturn_id_list").val());
			//console.log($('#fst_lpbsalesreturn_id_list').select2('data'));

		});
		
		tblDetails = $('#tbldetails').on('preXhr.dt', function ( e, settings, data ) {
			//add aditional data post on ajax call
			data.sessionId = "TEST SESSION ID";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "fin_rec_id","width": "0px",sortable:false,data:"fin_rec_id",visible:false},			
				{"title" : "Inv No","width": "100px",sortable:false,data:"fst_inv_no"},
				{"title" : "Item Name",sortable:false,data:"fst_item_id",
					render:function(data,type,row){
						var sstr = row.fst_item_code + " - " + row.fst_item_name;
						sstr += "<br>" + row.fst_unit + " :" + row.fdb_qty + " * @" + money_format(row.fdc_price);
						sstr += " = " +  money_format(row.fdb_qty * row.fdc_price);
						return sstr;
					}
				},
				{"title" : "Disc","width": "80px",sortable:false,className:'text-right',
					render:function(data,type,row){						
						discAmount = parseFloat(row.fdb_qty) * parseFloat(row.fdc_disc_amount_per_item);
						return App.money_format(discAmount);
					}
				},
				{"title" : "Potongan","width": "80px",sortable:false,className:'text-right',data:"fdc_potongan",
					render:function(data,type,row){
						return App.money_format(data);
					}
				},				
				{"title" : "Sub Total","width": "80px",sortable:false,className:'text-right',
					render:function(data,type,row){
						var subttl = row.fdb_qty * row.fdc_price;
						var disc = row.fdb_qty * row.fdc_disc_amount_per_item;						
						return App.money_format(parseFloat(subttl -disc - row.fdc_potongan));
					}
				},
				{"title" : "PPN","width": "80px",sortable:false,className:'text-right',
					render:function(data,type,row){
						var subttl = row.fdb_qty * row.fdc_price;
						var disc = row.fdb_qty * row.fdc_disc_amount_per_item;												
						var dpp = subttl -disc - row.fdc_potongan;						
						if (fblIncPPN == 1){
							dpp = dpp / (1 + (row.fdc_ppn_percent/100));
						}
						return App.money_format(dpp * (row.fdc_ppn_percent/100));
					}
				},
				{"title" : "Total","width": "80px",sortable:false,className:'text-right',
					render:function(data,type,row){
						var subttl = row.fdb_qty * row.fdc_price;
						var disc = row.fdb_qty * row.fdc_disc_amount_per_item;												
						var dpp = subttl -disc - row.fdc_potongan;						
						if (fblIncPPN == 1){
							dpp = dpp / (1 + (row.fdc_ppn_percent/100));
						}
						
						var total = dpp + (dpp * (row.fdc_ppn_percent/100));
						return App.money_format(total);
						

						

					}
				},
				{"title" : "Action","width": "35px",sortable:false,className:'text-center',
					render:function(data,type,row){
						var action = '<a class="btn-edit" href="#" data-original-title="" title=""><i class="fa fa-pencil"></i></a>';
						//action += '<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
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
			

			t = tblDetails;
			var trRow = $(this).parents('tr');
			selectedRow = t.row(trRow);
			mdlDetail.show();

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

		$("#btnPrint").click(function(e){
			e.preventDefault();
			frameVoucher.print("<?=site_url()?>tr/sales/sales_return/print_voucher/" + $("#fin_salesreturn_id").val());			
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

		$("#btn-get-detail").click(function(e){
			e.preventDefault();
			getDetailLPBSalesReturn();
		});

		$("#btn-add-detail").click(function(e){
			e.preventDefault();
			selectedRow = null;
			mdlDetail.show();
		});

		
	
	});
</script>

<script type="text/javascript" info="function">
	function getDetailLPBSalesReturn(){
		$("#btnSubmitAjax").hide();
		
		App.blockUIOnAjaxRequest();
		$.ajax({
			url:"<?=site_url()?>tr/sales/sales_return/ajxGetLPBSalesReturnItems",
			method:"GET",
			data:{
				fst_lpbsalesreturn_id_list:$("#fst_lpbsalesreturn_id_list").val()
			}
		}).done(function(resp){
			$("#btnSubmitAjax").show();
			if (resp.messages != ""){
				alert(resp.messages);
			}
			
			if(resp.status == "SUCCESS"){
				var details = [];
				$.each(resp.data,function(i,v){
					details.push({
						fin_rec_id:0,
						fin_inv_id:v.fin_inv_id,
						fst_inv_no:v.fst_inv_no,
						fin_item_id:v.fin_item_id,
						fst_item_code:v.fst_item_code,
						fst_item_name:v.fst_item_name,
						fst_unit:v.fst_unit,
						fdb_qty:v.fdb_qty,
						fdc_price:v.fdc_price,
						fdc_potongan:0,
						fdc_disc_amount_per_item:v.fdc_disc_amount_per_item,
						fdc_ppn_percent:v.fdc_ppn_percent,
					});
				});
				tblDetails.clear();
				tblDetails.rows.add(details).draw(false);
			}
			


		});
		
	}
	
	
	function calculateTotal(){

		t= tblDetails;
		var datas = t.rows().data();
		var total = 0;
		var totalDisc = 0;
		var totalDPP = 0;
		var totalPPN = 0;

		$.each(datas,function(i,data){

			var discAmount =  parseFloat(data.fdc_disc_amount_per_item) *  parseFloat(data.fdb_qty);
			var subttl =  parseFloat(data.fdc_price)  *  parseFloat(data.fdb_qty);											
			subttl = subttl - discAmount - parseFloat(data.fdc_potongan);

			var dpp = subttl;
			if (fblIncPPN == 1){
				dpp = dpp /(1+(data.fdc_ppn_percent/100));
			}

			totalDPP += dpp;
			var ppn = dpp * (data.fdc_ppn_percent/100);
			totalPPN += ppn;
			

			totalDisc += discAmount;								
		});

		//$("#ttlSubTotal").text(App.money_format(total));
		$("#ttlDisc").text(App.money_format(totalDisc));
		$("#ttlDPP").text(App.money_format(totalDPP));
		$("#ppnAmount").text(App.money_format(totalPPN));
		$("#ttlAmount").text(App.money_format(totalDPP + totalPPN));

	}

	function DELETE_getDetailSalesInv(callback){
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

	function DELETE_getItemBuyUnit(defaultValue){
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
					$.each(dataH.fst_lpbsalesreturn_id_list,function(i,v){
						App.addOptionIfNotExist("<option value='"+v.fin_lpbsalesreturn_id+"' selected>"+v.fst_lpbsalesreturn_no+"</option>","fst_lpbsalesreturn_id_list");
					});

					

					t = tblDetails;
					dataDetails =[];

					$.each(detailData,function(i,dataD){
						data =  {
							fin_rec_id:dataD.fin_rec_id,
							fin_item_id: dataD.fin_item_id,
							fst_item_code:dataD.fst_item_code,
							fst_item_name:dataD.fst_item_name,							
							fin_inv_id:dataD.fin_inv_id,
							fst_inv_no:dataD.fst_inv_no,
							fst_unit:dataD.fst_unit,
							fdc_price:dataD.fdc_price,
							fdb_qty:dataD.fdb_qty,
							fdc_disc_amount_per_item: dataD.fdc_disc_amount_per_item,
							fdc_ppn_percent: dataD.fdc_ppn_percent,
							fdc_potongan:dataD.fdc_potongan
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