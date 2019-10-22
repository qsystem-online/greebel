
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
	<h1><?=lang("Kas & Bank Pengeluaran")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Kas & Bank") ?></a></li>
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
				<form id="frmCBPengeluaran" class="form-horizontal" action="<?=site_url()?>tr/sales_order/add" method="POST" enctype="multipart/form-data">			
					<div class="box-body">
						<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">	
						<input type="hidden" class="form-control" id="fin_cbpayment_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_cbpayment_id" value="<?=$fin_cbpayment_id?>" readonly>
						

						
						<div class="form-group">							
                            
                            <div class="col-md-5 col-md-offset-2">
                                <div class="radio">
                                    <label><input type="radio" id="typeKas" class="type-pengeluaran" name="type-pengeluaran" value="Cash" checked><?= lang("Kas") ?></label>
                                    <label style="margin-left:20px"><input type="radio" id="typeBank" class="type-pengeluaran" value="Bank" name="type-pengeluaran"><?= lang("Bank") ?></label>
                                </div>
                            </div>                                
						</div>

						<div class="form-group">							
                            <label for="fin_kasbank_id" class="col-md-2 control-label"><?=lang("Jenis Pengeluaran")?> #</label>
                            <div class="col-md-5">
                                <select id="fin_kasbank_id" name="fin_kasbank_id" class="form-control"></select>                                    
                            </div>
							<div id="fin_kasbank_id" class="text-danger"></div>

							<label for="fdt_cbpayment_datetime" class="col-md-2 control-label text-right"><?=lang("Tanggal Pengeluaran")?> *</label>
							<div class="col-md-3">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control text-right datetimepicker" id="fdt_cbpayment_datetime" name="fdt_cbpayment_datetime" value=""/>
								</div>
								<div id="fdt_cbpayment_datetime_err" class="text-danger"></div>
								<!-- /.input group -->
							</div>

						</div>


                        <div class="form-group">															
							<label for="fst_cbpayment_no" class="col-md-2 control-label"><?=lang("No. Pengeluaran")?></label>	
							<div class="col-md-10">				
								<input type="TEXT" id="fst_cbpayment_no" name="fst_cbpayment_no" class="form-control"  value="" placeholder="PREFIX/BRANCH/YEAR/MONTH/99999" /> 
							</div>
						</div>

						<div class="form-group">						
							<label for="fst_curr_code" class="col-md-2 control-label"><?=lang("Mata Uang")?> </label>
							<div class="col-md-2">
								<select id="fst_curr_code" class="form-control" name="fst_curr_code">
									<?php

										$currencies = getDataTable("mscurrencies","*","fst_active ='A'");
										foreach($currencies as $currency){
											$selected = $currency->fst_curr_code == "IDR" ? "SELECTED" : "";
											echo "<option value='$currency->fst_curr_code' $selected>$currency->fst_curr_name</option>";
										}
									?>
								</select>
								<div id="fst_curr_code_err" class="text-danger"></div>
							</div>
						
							<label for="fdc_exchange_rate_idr" class="col-md-2 control-label"><?=lang("Nilai Tukar IDR")?> </label>
							<div class="col-md-2">
								<input type="text" class="money form-control text-right" id="fdc_exchange_rate_idr" name="fdc_exchange_rate_idr" style="width:100%" value="1" />
							</div>
							<label class="col-md-2 control-label" style="text-align:left;padding-left:0px"><?=lang("Rupiah")?> </label>
						</div>



						<div class="form-group">						
							<label for="fin_vendor_id" class="col-md-2 control-label"><?=lang("Supplier")?> </label>
							<div class="col-md-10">
								<select id="fin_supplier_id" class="form-control non-editable" name="fin_supplier_id">
									<?php									
										$suppliers = $this->msrelations_model->getSupplierList();
										foreach($suppliers as $supplier){										
											echo "<option value='$supplier->fin_relation_id' $selected>$supplier->fst_relation_name</option>";
										}									
									?>
								</select>
								<div id="fin_vendor_id_err" class="text-danger"></div>
							</div>						
						</div>
                        
                        <div class="form-group">                            
                            <label for="fin_vendor_id" class="col-md-2 control-label"><?=lang("Info")?> </label>							
                            <div class="col-sm-10">
                                <textarea class="form-control" id="fst_memo" placeholder="<?= lang("Memo") ?>" name="fst_memo" rows="3" style="resize:none"></textarea>
                                <div id="fst_memo_err" class="text-danger"></div>
                            </div>                        
                        </div>


						


						<div class="form-group" style="margin-bottom:5px">
							<div class="col-md-6" style="padding-top:10px"><label><?= lang("Rincian Pengeluaran") ?> :</label></div>
							<div class="col-md-6" style='text-align:right'>
								<button id="btn-add-detail" class="btn btn-primary btn-sm">
									<i class="fa fa-cart-plus" aria-hidden="true"></i>
									<?=lang("Tambah Transaksi")?>
								</button>
							</div>
							<div class="col-md-12">
							
							<table id="tblcbpaymentitems" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
							</div>
							
						</div>
						
						

						<div class="form-group" style="margin-bottom:5px">
							<label for="sub-total-rincian-transaksi" class="col-md-10 control-label"><?=lang("Sub total")?></label>
							<div class="col-md-2" style='text-align:right'>
								<input type="text" class="form-control text-right" id="sub-total-rincian-transaksi" value="0" readonly>
							</div>
						</div>
						
						<div class="form-group">
							<label for="sub-total-rincian-transaksi-idr" class="col-md-10 control-label"><?=lang("Sub total -> IDR")?></label>
							<div class="col-md-2" style='text-align:right'>
								<input type="text" class="form-control text-right" id="sub-total-rincian-transaksi-idr" value="0" readonly>
							</div>
						</div>

						<div class="form-group" style="margin-bottom:5px">
							<div class="col-md-6" style="padding-top:10px"><label><?= lang("Rincian Pengeluaran") ?> :</label></div>
							<div class="col-md-6" style='text-align:right'>
								<button id="btn-detail-pengeluaran" class="btn btn-primary btn-sm">
									<i class="fa fa-cart-plus" aria-hidden="true"></i>
									<?=lang("Tambah Pengeluaran")?>
								</button>
							</div>

							<div class="col-md-12">
							<!-- table table-bordered table-hover table-striped nowarp row-border -->
							<table id="tblcbpaymentpengeluaran" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
							</div>							
						</div>
						<div class="form-group" style="margin-bottom:5px">
							<label for="sub-total-rincian-transaksi" class="col-md-10 control-label"><?=lang("Sub total")?></label>
							<div class="col-md-2" style='text-align:right'>
								<input type="text" class="form-control text-right" id="sub-total-rincian-payment" value="0" readonly>
							</div>
						</div>
						
						<div class="form-group">
							<label for="sub-total-rincian-transaksi-idr" class="col-md-10 control-label"><?=lang("Sub total -> IDR")?></label>
							<div class="col-md-2" style='text-align:right'>
								<input type="text" class="form-control text-right" id="sub-total-rincian-payment-idr" value="0" readonly>
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
<div id="mdlAddItem" class="modal fade" role="dialog" >
	<div class="modal-dialog" style="display:table;width:800px">
		<!-- modal content -->
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
			

			<div class="modal-body">
				<div class="row">
                    <div class="col-md-12" >
                        <div style="border:0 px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">
				
								<form id="form-detail" class="form-horizontal">
									<input type='hidden' id='fin_rec_id_items'/>
									<div class="form-group">
										<label for="fst_trans_type" class="col-md-2 control-label"><?=lang("Trans type")?></label>
										<div class="col-md-10">
											<select id="fst_trans_type" class="form-control" style="width:100%">
												<option value="LPB_PO">LPB Pembelian</option>
												<option value="DP_PO">DP LPB Pembelian</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="fin_trans_id" class="col-md-2 control-label"><?=lang("Trans Number")?></label>
										<div class="col-md-10">
										<select id="fin_trans_id" class="form-control" style="width:100%"></select>
										</div>
									</div>

									<div class="form-group">
										<label for="fdc_trans_amount" class="col-md-2 control-label"><?=lang("Nominal")?></label>
										<div class="col-md-4">
											<input type="TEXT" id="fdc_trans_amount" name="fdc_trans_amount" value= "0" class="form-control text-right" readonly/>
										</div>
										<label for="ttl-paid" class="col-md-2 control-label"><?=lang("Paid")?></label>
										<div class="col-md-4">
											<input type="TEXT" id="ttl-paid"  value= "0" class="form-control text-right" readonly/>
										</div>
									</div>

									<div class="form-group">
										<label for="fdc_return_amount" class="col-md-2 control-label"><?=lang("Total Return")?></label>
										<div class="col-md-10">
											<input type="TEXT" id="fdc_return_amount" name="fdc_return_amount" value= "0" class="money form-control text-right"/>
										</div>
									</div>

									<div class="form-group">
										<label for="fdc_payment" class="col-md-2 control-label"><?=lang("Total Payment")?></label>
										<div class="col-md-10">
											<input type="TEXT" id="fdc_payment" name="fdc_payment" value= "0" class="money form-control text-right"/>
										</div>
									</div>

								</form>
								
								<div class="modal-footer">
									<button id="btn-add-rincian" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Add")?></button>
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

<div id="mdlPaymentType" class="modal fade" role="dialog" >
	<div class="modal-dialog" style="display:table;width:800px">
		<!-- modal content -->
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
			

			<div class="modal-body">
				<div class="row">
                    <div class="col-md-12" >
                        <div style="border:0 px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">
				
								<form id="form-detail" class="form-horizontal">
									<input type='hidden' id='fin_rec_id_item_type'/>
									<div class="form-group">
										<label for="fst_trans_type_pt" class="col-md-2 control-label"><?=lang("Trans type")?></label>
										<div class="col-md-10">
											<select id="fst_trans_type_pt" class="form-control" style="width:100%">
												<option value="TUNAI">Tunai</option>
												<option value="TRANSFER">Transfer</option>
												<option value="GIRO">Bilyet / Giro</option>
												<option value="GLACCOUNT">Account GL</option>
											</select>
										</div>
									</div>
									
									<div class="form-group">
										<label for="fst_glaccount_code_pd" class="col-md-2 control-label"><?=lang("GL Account")?></label>
										<div class="col-md-10">
											<select  id="fst_glaccount_code_pd"   class="form-control" style="width:100%">											
											</select>
										</div>										
									</div>

									<div class="form-group">
										<label for="fin_pcc_id" class="col-md-2 control-label"><?=lang("Profit / Cost Center")?></label>
										<div class="col-md-10">
											<select  id="fin_pcc_id" class="form-control">
												<?php
													$pcCenters = getDataTable("msprofitcostcenter","*","fst_active ='A'");
													foreach($pcCenters as $center){
														//$selected = $currency->fst_curr_code == "IDR" ? "SELECTED" : "";
														echo "<option value='$center->fin_pcc_id'>$center->fst_pcc_name</option>";
													}
												?>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label for="fdc_amount" class="col-md-2 control-label"><?=lang("Nominal")?></label>
										<div class="col-md-4">
											<input type="TEXT" id="fdc_amount" name="fdc_payment" value= "0" class="money form-control text-right"/>
										</div>
										<label for="fdc_amount_idr" class="col-md-2 control-label"><?=lang("Nominal -IDR")?></label>
										<div class="col-md-4">
											<input type="TEXT" id="fdc_amount_idr" value= "0" class="money form-control text-right" readonly/>
										</div>
									</div>

									<div class="form-group">
										<label for="fst_referensi" class="col-md-2 control-label"><?=lang("Referensi")?></label>
										<div class="col-md-10">
											<input type="TEXT" id="fst_referensi"  class="form-control"/>
										</div>
									</div>

									<div class="form-group" id="divGiro">
										<label for="fst_bilyet_no" class="col-md-2 control-label"><?=lang("No Bilyet")?></label>
										<div class="col-md-4">
											<input type="TEXT" id="fst_bilyet_no" class="form-control"/>
										</div>
										<label for="fdt_clear_date" class="col-md-2 control-label"><?=lang("Tgl Kliring")?></label>
										<div class="col-md-4">
											<input type="TEXT" id="fdt_clear_date"  class="form-control text-right datepicker" />
										</div>
									</div>



								</form>
								
								<div class="modal-footer">
									<button id="btn-add-type-pengeluaran" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Add")?></button>
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

<?php echo $mdlEditForm ?>


<script type="text/javascript">
	$(function(){
		//$("#fin_kasbank_id").select2();
		$("#btnSubmitAjax").click(function(e){
			submitAjax(0);
		});

		$("#fdt_cbpayment_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");

		$("#fin_kasbank_id").change(function(e){
			e.preventDefault();
			getTransactionNumber($("#fin_kasbank_id").val(),$("#fdt_cbpayment_datetime").val());
		})
		$("#fst_curr_code").change(function(e){
			e.preventDefault();
			cleanRincianTransaksi();
			$.ajax({
				url: "<?=site_url()?>api/get_value",
				method:"POST",
				data:{
					"model": "mscurrencies_model",
					"function": "getRate",
					"params": [$("#fst_curr_code").val()], // $("#fst_curr_code").val(),  //KRW,2019-07-23"
				},
			}).done(function(resp){
				$("#fdc_exchange_rate_idr").val(App.money_format(resp.data));
			});
		});

		$("#fdc_exchange_rate_idr").val(App.money_format(1));
		$("#fdc_exchange_rate_idr").change(function(e){
			e.preventDefault();
			calculateRincianTransaksi();
			calculateRincianPembayaran();
		})

		$("#fin_supplier_id").change(function(e){
			e.preventDefault();
			cleanRincianTransaksi();
		});

		$("#btn-add-detail").click(function(e){
			e.preventDefault();			
			clearFormDetail();			
			$("#mdlAddItem").modal("show");
		})

		$("#btn-detail-pengeluaran").click(function(e){
			e.preventDefault();
			if ($("#fin_kasbank_id").val() == null){
				alert("<?= lang('Pilih jenis pengeluaran ...!') ?>");
				return;
			}

			clearFormPaymentDetail();
			$("#mdlPaymentType").modal("show");
		});

		var rowPaymentItem;
		$('#tblcbpaymentitems').on('preXhr.dt', function ( e, settings, data ) {
			//add aditional data post on ajax call
			data.sessionId = "TEST SESSION ID";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "id","width": "50px",sortable:false,data:"fin_rec_id",visible:true},
				{"title" : "Type","width": "100px",sortable:false,data:"fst_trans_type",
					render: function(data,type,row){

						switch(data){
							case "LPB_PO":
								return "LPB Pembelian";
								break;
							case "DP_PO":
								return "DP LPB Pembelian";
								break;
							default:
								return "";
						}
						//return row.fst_trans_type_name
					}
				},
				{"title" : "Trans No",width:"50px",data:"fin_trans_id",
					render:function(data,type,row){
						return row.fst_trans_no;
					}
				},
				{"title" : "Nominal",width:"50px",className:'text-right',
					render:function(data,type,row){
						return App.money_format(row.fdc_trans_amount);
					}
				},
				{"title" : "Return",width:"50px",data:"fdc_return_amount",className:'text-right',
					render:function(data,type,row){
						return App.money_format(row.fdc_return_amount);
					}
				},			
				{"title" : "Payment",width:"50px",data:"fdc_payment",
					render:function(data,type,row){
						return App.money_format(row.fdc_payment);
					},
					//render: $.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					className:'text-right'
				},
				{"title" : "Action","width": "40px",sortable:false,className:'dt-body-center text-center',
					render: function(data,type,row){
						var action = '<a class="btn-edit" href="#" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp;';						
						if(row.fdb_qty_lpb == 0 || typeof row.fdb_qty_lpb === 'undefined' ){
							action += '<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
						}
						return action;
					}
				},				
				
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

			$('.xbtn-delete').confirmation({
				//rootSelector: '[data-toggle=confirmation]',
				rootSelector: '.btn-delete',
				// other options
			});	

			$(".xbtn-edit").click();
		}).on("click",".btn-edit",function(e){
			e.preventDefault();
			t = $('#tblcbpaymentitems').DataTable();
			var trRow = $(this).parents('tr');
			rowPaymentItem = trRow;
			var row = t.row(trRow).data();
			
			$("#fin_rec_id_items").val(row.fin_rec_id);
			$("#fst_trans_type").val(row.fst_trans_type);
			
			getTransactionList(row.fst_trans_type,function(resp){
				var isDefaultValueExist = false;
				$.each($("#fin_trans_id option"),function(i,e){
					if($(e).val() == row.fin_trans_id ){
						isDefaultValueExist = true;
						return false;
					}
				});

				if(isDefaultValueExist == false){
					$("#fin_trans_id").append("<option value='"+ row.fin_trans_id +"'  data-ttl_amount='0' data-ttl_paid='0'>"+row.fst_trans_no+"</option>")
				}

				$("#fin_trans_id").val(row.fin_trans_id).trigger("change");
				$("#fdc_return_amount").val(row.fdc_return_amount);
				$("#fdc_payment").val(row.fdc_payment);	
			});	

			$("#fst_trans_type").prop("disabled",true);
			$("#fin_trans_id").prop("disabled",true);



			$("#mdlAddItem").modal("show");


		}).on("click",".btn-delete",function(e){
			t = $('#tblcbpaymentitems').DataTable();
			var trRow = $(this).parents('tr');
			t.row(trRow).remove().draw();
			calculateRincianTransaksi();
		});

		$("#btn-add-rincian").click(function(e){
			data = {
				fin_rec_id:$("#fin_rec_id_items").val(),
				fst_trans_type:$("#fst_trans_type").val(),
				fst_trans_type_name: $("#fst_trans_type option:selected").text(),
				fin_trans_id:$("#fin_trans_id").val(),
				fst_trans_no:$("#fin_trans_id option:selected").text(),
				fdc_trans_amount: App.money_parse($("#fdc_trans_amount").val()),
				fdc_return_amount: App.money_parse($("#fdc_return_amount").val()),
				fdc_payment:App.money_parse($("#fdc_payment").val())
			};

			
			t = $("#tblcbpaymentitems").DataTable();
			if (rowPaymentItem != null){
				t.row(rowPaymentItem).data(data).draw(false);
			}else{
				if (isDuplicateItem(data.fst_trans_type,data.fin_trans_id)){
					alert(data.fst_trans_type_name + " : " + data.fst_trans_no + ", sudah ada !");
					return;	
				}
				t.row.add(data).draw(false);
			}
			calculateRincianTransaksi();

		});
		
		$(".type-pengeluaran").change(function(e){
			e.preventDefault();
			getKasBankList($(".type-pengeluaran:checked").val());
		});

		$("#fst_trans_type").change(function(e){
			e.preventDefault();
			getTransactionList($(this).val());
		});

		$("#fin_trans_id").change(function(e){
			e.preventDefault();
			
			ttlAmount = parseFloat($("#fin_trans_id").find(':selected').data('ttl_amount')) ;
			ttlPaid = parseFloat($("#fin_trans_id").find(':selected').data('ttl_paid'));

			$("#fdc_trans_amount").val(App.money_format(ttlAmount) );
			$("#ttl-paid").val(App.money_format(ttlPaid));
			$("#fdc_payment").val(App.money_format(ttlAmount - ttlPaid));
		});

		var rowPaymentItemType;
		$('#tblcbpaymentpengeluaran').on('preXhr.dt', function ( e, settings, data ) {
			//add aditional data post on ajax call
			data.sessionId = "TEST SESSION ID";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "id","width": "50px",sortable:false,data:"fin_rec_id",visible:true},
				{"title" : "Type","width": "100px",sortable:false,data:"fst_cbpayment_type",
					render: function(data,type,row){
						switch (data){
							case "TUNAI":
								return "Tunai";
								break;
							case "TRANSFER":
								return "Transfer";
								break;
							case "GIRO":
								return "Bilyet / Giro";
								break;
							case "GLACCOUNT":
								return "Account GL";
								break;
						}
					}
				},				
				{"title" : "GL Account",width:"250px",data:"fst_glaccount_code",
					render:function(data,type,row){
						return row.fst_glaccount_code_name;
					}
				},
				{"title" : "Profit / Cost Center",width:"150px",data:"fin_pcc_id",
					render: function(data,type,row){
						return row.fst_pcc_name;
					},
				},
				{"title" : "Nominal",width:"100px",data:"fdc_amount",
					render:function(data,type,row){
						return App.money_format(row.fdc_amount);
					},
					//render: $.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					className:'text-right'
				},				
				{"title" : "Referensi",width:"200px",data:"fst_referensi"},
				{"title" : "No. Bilyet",width:"100px",data:"fst_bilyet_no"},
				{"title" : "Tanggal Kliring",width:"100px",data:"fdt_clear_date"},
				{"title" : "Action","width": "40px",sortable:false,className:'dt-body-center text-center',
					render: function(data,type,row){
						var action = '<a class="btn-edit" href="#" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp;';												
						action += '<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';						
						return action;
					}
				},				
				
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
			t = $("#tblcbpaymentpengeluaran").DataTable();
			var trRow = $(this).parents('tr');
			rowPaymentItemType = trRow;
			var data = t.row(trRow).data();

			$("#fin_rec_id_item_type").val(data.fin_rec_id);
			$("#fst_trans_type_pt").val(data.fst_cbpayment_type);
			$("#fst_trans_type_pt").trigger("change");

			$("#fst_curr_code_payment_detail").val(data.fst_curr_code);
			$("#fdc_exchange_rate_idr_payment_detail").val(data.fdc_exchange_rate_idr);
			$("#fst_glaccount_code_pd").val(data.fst_glaccount_code);
			$("#fst_glaccount_code_pd").trigger("change.select2");
			
			$("#fin_pcc_id").val(data.fin_pcc_id);
			$("#fdc_amount").val(data.fdc_amount);
			$("#fst_referensi").val(data.fst_referensi);
			$("#fst_bilyet_no").val(data.fst_bilyet_no);
			$("#fdt_clear_date").val(data.fdt_clear_date);

			$("#mdlPaymentType").modal("show");
			
		}).on('click','.btn-delete',function(e){
			e.preventDefault();
			t = $('#tblcbpaymentpengeluaran').DataTable();
			var trRow = $(this).parents('tr');
			t.row(trRow).remove().draw();
			calculateRincianPembayaran();


		});

		$("#fst_glaccount_code_pd").select2();
		$("#fst_curr_code_payment_detail").change(function(e){
			e.preventDefault();
			$.ajax({
				url: "<?=site_url()?>api/get_value",
				method:"POST",
				data:{
					"model": "mscurrencies_model",
					"function": "getRate",
					"params": [$("#fst_curr_code_payment_detail").val()], 
				},
			}).done(function(resp){
				$("#fdc_exchange_rate_idr_payment_detail").val(App.money_format(resp.data));
			});
		});

		$("#fst_trans_type_pt").val(null);
		$("#fst_trans_type_pt").change(function(e){
			e.preventDefault();
			$("#fst_glaccount_code_pd").prop("disabled",false);


			if ($("#fst_trans_type_pt").val() == "TUNAI" ||$("#fst_trans_type_pt").val() == "TRANSFER"){

				var acc  = $("#fin_kasbank_id option:selected").data("glaccount");

				$("#fst_glaccount_code_pd").empty();
				$("#fst_glaccount_code_pd").append("<option value='"+acc+"'>"+ $("#fin_kasbank_id option:selected").data("glaccount") + " - " + $("#fin_kasbank_id option:selected").text() +"</option>")

				//$("#fst_glaccount_code_pd").val(acc).trigger("change.select2");				
				//$("#fst_glaccount_code_pd").prop("disabled",true);
				//fixedSelect2();
			}else if($("#fst_trans_type_pt").val() == "GIRO"){
				$("#fst_glaccount_code_pd").empty();
				<?php					
				$accGiroMundur = $this->trcbpayment_model->getOutGiroAccount();				
				if($accGiroMundur){ ?>				
					$("#fst_glaccount_code_pd").append("<option value='<?=$accGiroMundur->fst_glaccount_code?>'><?= $accGiroMundur->fst_glaccount_code ." - " .$accGiroMundur->fst_glaccount_name ?></option>")
				<?php } ?>				
			}else if($("#fst_trans_type_pt").val() == "GLACCOUNT"){
				<?php
				$accounts = getDataTable("glaccounts","*","fst_active ='A' and fst_glaccount_level != 'HD' and fbl_is_allow_in_cash_bank_module = 1");
				foreach($accounts as $account){ ?>
					$("#fst_glaccount_code_pd").append("<option value='"+"<?=$account->fst_glaccount_code?>"+"'>"+"<?=$account->fst_glaccount_code . " - " . $account->fst_glaccount_name?>"+"</option>");
					//$selected = $currency->fst_curr_code == "IDR" ? "SELECTED" : "";						
				<?php } ?>
			}


		});

		$("#fdc_exchange_rate_idr_payment_detail").change(function(e){
			e.preventDefault();
			$("#fdc_amount").trigger("change");

		})
		$("#fdc_amount").change(function(e){
			e.preventDefault();
			var amount =parseFloat($("#fdc_amount").val());
			var exchangeRate = parseFloat($("#fdc_exchange_rate_idr").val());
			$("#fdc_amount_idr").val( amount * exchangeRate );
		});

		$("#btn-add-type-pengeluaran").click(function(e){
			e.preventDefault();
			t = $("#tblcbpaymentpengeluaran").DataTable();

			data = {
				fin_rec_id:$("#fin_rec_id_item_type").val(),
				fst_cbpayment_type:$("#fst_trans_type_pt").val(),
				fst_cbpayment_type_name:$("#fst_trans_type_pt option:selected").text(),
				fst_glaccount_code:$("#fst_glaccount_code_pd").val(),
				fst_glaccount_code_name:$("#fst_glaccount_code_pd option:selected").text(),
				fin_pcc_id:$("#fin_pcc_id").val(),
				fst_pcc_name:$("#fin_pcc_id option:selected").text(),
				fdc_amount:$("#fdc_amount").val(),
				fst_referensi:$("#fst_referensi").val(),
				fst_bilyet_no:$("#fst_bilyet_no").val(),
				fdt_clear_date:$("#fdt_clear_date").val(),
			};
			if (rowPaymentItemType == null){
				t.row.add(data).draw(false);
			}else{
				t.row(rowPaymentItemType).data(data).draw(false);
			}
			
			calculateRincianPembayaran();


		});

		fixedSelect2();
		initForm();
	});

	function initForm(){

		if ($("#fin_cbpayment_id").val() != 0){ //EDIT MODE
			//Get Data
			App.blockUIOnAjaxRequest("Get data transaction.....");
			$.ajax({
				url:"<?=site_url()?>tr/kas_bank/pengeluaran/fetch_data/" + $("#fin_cbpayment_id").val(),

			}).done(function(resp){
				console.log(resp);
				if (resp.message != ""){
					alert(resp.message);
				}
				if(resp.status =="SUCCESS"){
					var cbPayment = resp.cbpayment;
					var cbPaymentItems = resp.cbpayment_items;
					var cbPaymentItemsType = resp.cbpayment_items_type;					
					if(cbPayment.fst_kasbank_type == "Cash"){
						$("#typeKas").prop("checked",true);
					}else{
						$("#typeBank").prop("checked",true);
					}

					getKasBankList($(".type-pengeluaran:checked").val(),function(){
						$("#fin_kasbank_id").val(cbPayment.fin_kasbank_id);	
						App.autoFillForm(cbPayment);

						$("#fdt_cbpayment_datetime").val(App.dateTimeFormat(cbPayment.fdt_cbpayment_datetime)).datetimepicker('update');	

						//Set detail items
						t = $("#tblcbpaymentitems").DataTable();
						$.each(cbPaymentItems,function(i,v){
							data = {
								fin_rec_id:v.fin_rec_id,
								fst_trans_type:v.fst_trans_type,
								fin_trans_id:v.fin_trans_id,
								fst_trans_no:v.fst_trans_no,
								fdc_trans_amount: v.fdc_trans_amount,								
								fdc_return_amount: v.fdc_return_amount,
								fdc_payment:v.fdc_payment
							};
							t.row.add(data);				
						});
						t.draw(false);
						calculateRincianTransaksi();

						//Set detail items type
						t = $("#tblcbpaymentpengeluaran").DataTable();
						$.each(cbPaymentItemsType,function(i,v){
							data = {
								fin_rec_id:v.fin_rec_id,
								fst_cbpayment_type:v.fst_cbpayment_type,
								fst_cbpayment_type_name:v.fst_cbpayment_type_name,
								fst_curr_code:v.fst_curr_code,
								fdc_exchange_rate_idr:v.fdc_exchange_rate_idr,
								fst_glaccount_code:v.fst_glaccount_code,
								fst_glaccount_code_name:v.fst_glaccount_code + " - " + v.fst_glaccount_name,
								fin_pcc_id:v.fin_pcc_id,
								fst_pcc_name:v.fst_pcc_name,
								fdc_amount:v.fdc_amount,
								fst_referensi:v.fst_referensi,
								fst_bilyet_no:v.fst_bilyet_no,
								fdt_clear_date:v.fdt_clear_date
							};
							t.row.add(data);							
						});
						t.draw(false);
						calculateRincianPembayaran();

					});
					

				}

			});
		}else{
			$(".type-pengeluaran").trigger("change");
		}
	}


	function clearFormDetail(){
		$("#fst_trans_type").prop("disabled",false);
		$("#fin_trans_id").prop("disabled",false);

		$("#fin_rec_id_items").val(0);
		$("#fst_trans_type").val(null);
		$("#fin_trans_id").val(null);
		$("#fdc_trans_amount").val(App.money_format(0));
		$("#ttl-paid").val(App.money_format(0));
		$("#fdc_return_amount").val(App.money_format(0));
		$("#fdc_payment").val(App.money_format(0));
		$("#fst_trans_type").focus();


		rowPaymentItem = null;

	}

	function clearFormPaymentDetail(){
		$("#fin_rec_id_item_type").val(0);
		rowPaymentItemType = null;
	}

	function calculateRincianTransaksi(){
		t = $("#tblcbpaymentitems").DataTable();
		var data = t.rows().data();
		var totalRincian =0;
		$.each(data,function(i,row){
			totalRincian += App.money_parse(row.fdc_payment);
		});
		totalRincianIDR =totalRincian * App.money_parse($("#fdc_exchange_rate_idr").val());
		$("#sub-total-rincian-transaksi").val(App.money_format(totalRincian));
		$("#sub-total-rincian-transaksi-idr").val(App.money_format(totalRincianIDR));
	}
	function calculateRincianPembayaran(){
		t = $("#tblcbpaymentpengeluaran").DataTable();
		var data = t.rows().data();
		var totalRincian =0;
		var totalRincianIDR =0;
		var exchangeRate = App.money_parse($("#fdc_exchange_rate_idr").val());

		$.each(data,function(i,row){
			totalRincian += App.money_parse(row.fdc_amount);
			totalRincianIDR += App.money_parse(row.fdc_amount * exchangeRate);

		});
		//totalRincianIDR =totalRincian * App.money_parse($("#fdc_exchange_rate_idr").val());
		$("#sub-total-rincian-payment").val(App.money_format(totalRincian));
		$("#sub-total-rincian-payment-idr").val(App.money_format(totalRincianIDR));
	}
	
	function cleanRincianTransaksi(){
		t = $("#tblcbpaymentitems").DataTable();
		t.rows().remove();
		t.draw(false);
	}

	function getTransactionNumber(kasBankId,cbPaymentDatetime){

		cbPaymentDatetime = App.dateTimeParse(cbPaymentDatetime);
		App.getValueAjax({
			site_url:"<?=site_url()?>",
			model: "trcbpayment_model",
			func:"generateCBPaymentNo",
			params:[kasBankId,cbPaymentDatetime],
			callback:function(value){
				$("#fst_cbpayment_no").val(value);
			}
		});
	}

	function getKasBankList(typeKasBank,callbackFunc){
		App.getValueAjax({
			site_url:"<?=site_url()?>",
			model: "kasbank_model",
			func:"getListByType",
			params:[
				typeKasBank
			],
			callback:function(value){
				$("#fin_kasbank_id").empty();
				$.each(value,function(i,item){
					$("#fin_kasbank_id").append("<option value='" + item.fin_kasbank_id + "' data-glaccount='"+item.fst_gl_account_code+"'>"+ item.fst_kasbank_name +"</option>");
				});
				//$("#fin_kasbank_id").val(null).trigger("change");
				$("#fin_kasbank_id").val(null);
				if( typeof callbackFunc === "function" ){
					callbackFunc(value);
				}
				
			}
		});
	}

	function getTransactionList(transType,callback){
		switch(transType) {
			case "LPB_PO":				
				App.getValueAjax({
					site_url:"<?=site_url()?>",
					model:"trcbpayment_model",
					func:"getUnpaidPurchaseInvoiceList",
					params:[$("#fin_supplier_id").val(),$("#fst_curr_code").val()],
					callback:function(lpbPurchaseList){
						$("#fin_trans_id").empty();
						$.each(lpbPurchaseList,function(i,lpbPurchase){
							//var dp = parseFloat(lpbPurchase.fdc_downpayment);					
							$("#fin_trans_id").append("<option value='"+lpbPurchase.fin_lpbpurchase_id+"' data-ttl_amount='"+ lpbPurchase.fdc_total  +"' data-ttl_paid='"+lpbPurchase.fdc_total_paid+ "'>"+lpbPurchase.fst_lpbpurchase_no+"</option>")
						});
						$("#fin_trans_id").val(null);

						if(typeof callback !== "undefined"){
							callback(lpbPurchaseList);
						}
						
					}
				});
				break;
			case "DP_PO":
				App.getValueAjax({
					site_url:"<?=site_url()?>",
					model:"trpo_model",
					func:"getUnpaidDPList",
					params:[$("#fin_supplier_id").val(),$("#fst_curr_code").val()],
					callback:function(poList){
						$("#fin_trans_id").empty();
						$.each(poList,function(i,po){
							var dp = parseFloat(po.fdc_downpayment);					
							$("#fin_trans_id").append("<option value='"+po.fin_po_id+"' data-ttl_amount='"+ dp  +"' data-ttl_paid='"+po.fdc_downpayment_paid+ "'>"+po.fst_po_no+"</option>")
						});
						$("#fin_trans_id").val(null);

						if(typeof callback !== "undefined"){
							callback(poList);
						}
						
					}
				});
				break;
			default:
				// code block
		}
	}

	function isDuplicateItem(type,id){
		t = $("#tblcbpaymentitems").DataTable();
		dataRows = t.rows().data();
		isDuplicate = false;
		$.each(dataRows,function(i,row){
			if (row.fst_trans_type == type && row.fin_trans_id == id){
				isDuplicate =  true;
				return false; //(Quit from iterator)
			}
		});
		return isDuplicate;
	}

	function submitAjax(confirmEdit){

		if ($("#sub-total-rincian-transaksi-idr").val() != $("#sub-total-rincian-payment-idr").val()){
			alert("<?= lang('Total transaksi & Total Pembayaran tidak sama ...!') ?>");
			return;
		}
		if ($("#fin_kasbank_id").val() == null){
			alert("<?= lang('Pilih jenis pengeluaran ...!') ?>");
			return;
		}

		var dataSubmit = $("#frmCBPengeluaran").serializeArray();
		var detailTrans = new Array();	
		var detailPayment = new Array();	

		var tTrans = $('#tblcbpaymentitems').DataTable();
		var tPayment = $('#tblcbpaymentpengeluaran').DataTable();
		
		var dataTrans = tTrans.data();
		var dataPayment = tPayment.data();

		$.each(dataTrans,function(i,v){
			detailTrans.push(v);
		});

		$.each(dataPayment,function(i,v){
			detailPayment.push(v);
		});

		dataSubmit.push({
			name:"detailTrans",
			value: JSON.stringify(detailTrans)
		});
		dataSubmit.push({
			name:"detailPayment",
			value: JSON.stringify(detailPayment)
		});
		
		
		mode = $("#fin_cbpayment_id").val() == "0" ? "ADD" : "EDIT";	


		if (mode == "ADD"){
			url =  "<?= site_url() ?>tr/kas_bank/pengeluaran/ajx_add_save/";
		}else{
			dataSubmit.push({
				name : "fin_user_id_request_by",
				value: MdlEditForm.user
			});
			dataSubmit.push({
				name : "fst_edit_notes",
				value: MdlEditForm.notes
			});

			url =  "<?= site_url() ?>tr/kas_bank/pengeluaran/ajx_edit_save/";
		}
		

		if (confirmEdit == 0 && mode != "ADD"){
			MdlEditForm.saveCallBack = function(){
				submitAjax(1);
			};		
			MdlEditForm.show();
			return;
		}

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

</script>

<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>