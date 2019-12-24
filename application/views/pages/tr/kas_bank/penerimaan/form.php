
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
	<h1><?=lang("Kas & Bank Penerimaan")?><small><?=lang("form")?></small></h1>
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
						<a id="btnPrint" class="btn btn-primary" href="#" title="<?=lang("Cetak")?>" style="display:<?= $mode == "ADD" ? "none" : "inline-block" ?>"><i class="fa fa-print" aria-hidden="true"></i></a>
						<a id="btnJurnal" class="btn btn-primary" href="#" title="<?=lang("Jurnal")?>" style="display:<?= $mode == "ADD" ? "none" : "inline-block" ?>"><i class="fa fa-align-left" aria-hidden="true"></i></a>
						<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>" style="display:<?= $mode == "ADD" ? "none" : "inline-block" ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
						<a id="btnClose" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
					</div>
				</div>
				<!-- end box header -->

				<!-- form start -->
				<form id="frmCBPenerimaan" class="form-horizontal"  method="POST">			
					<div class="box-body">
						<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">	
						<input type="hidden" class="form-control" id="fin_cbreceive_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_cbreceive_id" value="<?=$fin_cbreceive_id?>" readonly>
						

						
						<div class="form-group">
                            <div class="col-md-5 col-md-offset-2">
                                <div class="radio">
                                    <label><input type="radio" id="typeKas" class="type-penerimaan" name="type-penerimaan" value="Cash" checked><?= lang("Kas") ?></label>
                                    <label style="margin-left:20px"><input type="radio" id="typeBank" class="type-penerimaan" value="Bank" name="type-penerimaan"><?= lang("Bank") ?></label>
                                </div>
                            </div>                                
						</div>

						<div class="form-group">
                            <label for="fin_kasbank_id" class="col-md-2 control-label"><?=lang("Jenis Penerimaan")?> #</label>
                            <div class="col-md-5">
                                <select id="fin_kasbank_id" name="fin_kasbank_id" class="form-control"></select>                                    
                            </div>
							<div id="fin_kasbank_id" class="text-danger"></div>

							<label for="fdt_cbreceive_datetime" class="col-md-2 control-label text-right"><?=lang("Tanggal Penerimaan")?> *</label>
							<div class="col-md-3">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control text-right datetimepicker" id="fdt_cbreceive_datetime" name="fdt_cbreceive_datetime" value=""/>
								</div>
								<div id="fdt_cbreceive_datetime_err" class="text-danger"></div>
								<!-- /.input group -->
							</div>

						</div>


                        <div class="form-group">
							<label for="fst_cbreceive_no" class="col-md-2 control-label"><?=lang("No. Penerimaan")?></label>	
							<div class="col-md-10">				
								<input type="TEXT" id="fst_cbreceive_no" name="fst_cbreceive_no" class="form-control"  value="" placeholder="PREFIX/BRANCH/YEAR/MONTH/99999" /> 
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
							<label for="fin_customer_id" class="col-md-2 control-label"><?=lang("Customer")?> </label>
							<div class="col-md-10">
								<select id="fin_customer_id" class="form-control non-editable" name="fin_customer_id">
									<?php									
										$customers = $this->msrelations_model->getCustomerList();
										foreach($customers as $customer){										
											echo "<option value='$customer->fin_relation_id' $selected>$customer->fst_relation_name</option>";
										}									
									?>
								</select>
								<div id="fin_customer_id_err" class="text-danger"></div>
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
							<div class="col-md-6" style="padding-top:10px"><label><?= lang("Rincian Tranasaksi") ?> :</label></div>
							<div class="col-md-6" style='text-align:right'>
								<button id="btn-add-detail" class="btn btn-primary btn-sm">
									<i class="fa fa-cart-plus" aria-hidden="true"></i>
									<?=lang("Tambah Transaksi")?>
								</button>
							</div>
							<div class="col-md-12">
							
							<table id="tblcbreceiveitems" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
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
							<div class="col-md-6" style="padding-top:10px"><label><?= lang("Rincian Penerimaan") ?> :</label></div>
							<div class="col-md-6" style='text-align:right'>
								<button id="btn-detail-penerimaan" class="btn btn-primary btn-sm">
									<i class="fa fa-cart-plus" aria-hidden="true"></i>
									<?=lang("Tambah Penerimaan")?>
								</button>
							</div>

							<div class="col-md-12">
							<!-- table table-bordered table-hover table-striped nowarp row-border -->
							<table id="tblcbreceiveitemstype" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
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

					<div class="box-footer text-right"></div>
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
				
								<form id="form-detail-trans" class="form-horizontal">
									<input type='hidden' id='fin_rec_id_items'/>
									<div class="form-group">
										<label for="fst_trans_type" class="col-md-2 control-label"><?=lang("Trans type")?></label>
										<div class="col-md-10">
											<select id="fst_trans_type" class="form-control" style="width:100%">
												<option value="DP_SO">DP Penjualan</option>
												<option value="INV_SO">Faktur Penjualan</option>
												<option value="RETURN_SO">Return Penjualan Non Faktur</option>
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
											<input type="TEXT" id="fdc_return_amount" value= "0" class="money form-control text-right" readonly/>
										</div>
									</div>

									<div class="form-group">
										<label for="fdc_receive_amount" class="col-md-2 control-label"><?=lang("Total Receive")?></label>
										<div class="col-md-10">
											<input type="TEXT" id="fdc_receive_amount"  value= "0" class="money form-control text-right"/>
										</div>
									</div>
									<div class="form-group">
										<label for="fst_memo_detail_trans" class="col-md-2 control-label"><?=lang("Memo")?></label>
										<div class="col-md-10">
											<textarea class="form-control text-left" id="fst_memo_detail_trans" style="resize:none"></textarea>
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

	<script type="text/javascript">
		$(function(){
			$("#btn-add-rincian").click(function(e){
				data = {
					fin_rec_id:$("#fin_rec_id_items").val(),
					fst_trans_type:$("#fst_trans_type").val(),
					fst_trans_type_name: $("#fst_trans_type option:selected").text(),
					fin_trans_id:$("#fin_trans_id").val(),
					fst_trans_no:$("#fin_trans_id option:selected").text(),
					fdc_trans_amount: App.money_parse($("#fdc_trans_amount").val()),
					fdc_paid_amount: App.money_parse($("#ttl-paid").val()),
					fdc_return_amount: App.money_parse($("#fdc_return_amount").val()),
					fdc_receive_amount:App.money_parse($("#fdc_receive_amount").val()),
					fst_memo:$("#fst_memo_detail_trans").val()
				};			
				t = $("#tblcbreceiveitems").DataTable();
				if (rowReceiveItem != null){
					t.row(rowReceiveItem).data(data).draw(false);
				}else{
					if (isDuplicateItem(data.fst_trans_type,data.fin_trans_id)){
						alert(data.fst_trans_type_name + " : " + data.fst_trans_no + ", sudah ada !");
						return;	
					}
					t.row.add(data).draw(false);
				}

				clearFormDetail();
				calculateRincianTransaksi();
				$("#mdlAddItem").modal("hide");
			});

			$("#fst_trans_type").change(function(e){
				e.preventDefault();
				getTransactionList($(this).val());
			});

			$("#fin_trans_id").change(function(e){
				e.preventDefault();				
				
				ttlAmount = parseFloat($("#fin_trans_id").find(':selected').data('ttl_amount')) ;
				ttlPaid = parseFloat($("#fin_trans_id").find(':selected').data('ttl_paid'));
				ttlReturn = parseFloat($("#fin_trans_id").find(':selected').data('ttl_return'));

				$("#fdc_trans_amount").val(App.money_format(ttlAmount) );
				$("#ttl-paid").val(App.money_format(ttlPaid));
				$("#fdc_return_amount").val(App.money_format(ttlReturn));

				$("#fdc_receive_amount").val(App.money_format(ttlAmount - ttlPaid - ttlReturn));
			});

			$("#fdc_amount").change(function(e){
				e.preventDefault();
				var amount =parseFloat($("#fdc_amount").val());
				var exchangeRate = parseFloat($("#fdc_exchange_rate_idr").val());
				$("#fdc_amount_idr").val( amount * exchangeRate );
			});		

		})

		function getTransactionList(transType,callback){
			switch(transType) {
				case "DP_SO":
					App.getValueAjax({
						site_url:"<?=site_url()?>",
						model:"trsalesorder_model",
						func:"getUnpaidDPList",
						params:[$("#fin_customer_id").val(),$("#fst_curr_code").val()],
						callback:function(soList){
							$("#fin_trans_id").empty();
							$.each(soList,function(i,so){
								var dp = parseFloat(so.fdc_downpayment);					
								$("#fin_trans_id").append("<option value='"+so.fin_salesorder_id+"' data-ttl_amount='"+ dp  +"' data-ttl_paid='"+so.fdc_downpayment_paid+ "' data-ttl_return='0'>"+so.fst_salesorder_no+"</option>")
							});
							$("#fin_trans_id").val(null);							 
							if(typeof callback !== "undefined"){
								callback(soList);
							}
							
						}
					});
					break;

				case "INV_SO":
					App.getValueAjax({
						site_url:"<?=site_url()?>",
						model:"trinvoice_model",
						func:"getUnpaidSalesInvoiceList",
						params:[$("#fin_customer_id").val(),$("#fst_curr_code").val()],
						callback:function(salesInvList){
							$("#fin_trans_id").empty();
							$.each(salesInvList,function(i,salesInv){
								//var dp = parseFloat(lpbPurchase.fdc_downpayment);					
								$("#fin_trans_id").append("<option value='"+salesInv.fin_inv_id+"' data-ttl_amount='"+ (salesInv.fdc_total)   +"' data-ttl_paid='"+salesInv.fdc_total_paid+ "' data-ttl_return='"+salesInv.fdc_total_return+"' >"+salesInv.fst_inv_no+"</option>")
							});
							$("#fin_trans_id").val(null);

							if(typeof callback !== "undefined"){
								callback(salesInvList);
							}
							
						}
					});
					break;
												
				case "RETURN_SO":
					App.getValueAjax({
						site_url:"<?=site_url()?>",
						model:"trsalesreturn_model",
						func:"getSalesReturnNonFakturList",
						params:[$("#fin_customer_id").val(),$("#fst_curr_code").val()],
						callback:function(salesReturnNonFakturList){
							$("#fin_trans_id").empty();
							$.each(salesReturnNonFakturList,function(i,salesReturnNonFaktur){
								//var dp = parseFloat(lpbPurchase.fdc_downpayment);					
								$("#fin_trans_id").append("<option value='"+salesReturnNonFaktur.fin_salesreturn_id+"' data-ttl_amount='"+ parseFloat(salesReturnNonFaktur.fdc_total) * -1  +"' data-ttl_paid='"+salesReturnNonFaktur.fdc_total_claimed * -1 + "' data-ttl_return='0' >"+salesReturnNonFaktur.fst_salesreturn_no+"</option>");
							});
							$("#fin_trans_id").val(null);

							if(typeof callback !== "undefined"){
								callback(salesReturnNonFakturList);
							}
							
						}
					});
					break;
				default:
					// code block
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
			$("#fdc_receive_amount").val(App.money_format(0));
			$("#fst_trans_type").focus();
			rowReceiveItem = null;
		}

		function calculateRincianTransaksi(){
			t = $("#tblcbreceiveitems").DataTable();
			var data = t.rows().data();
			var totalRincian =0;
			$.each(data,function(i,row){
				totalRincian += App.money_parse(row.fdc_receive_amount);
			});
			totalRincianIDR =totalRincian * App.money_parse($("#fdc_exchange_rate_idr").val());
			$("#sub-total-rincian-transaksi").val(App.money_format(totalRincian));
			$("#sub-total-rincian-transaksi-idr").val(App.money_format(totalRincianIDR));
		}

		function isDuplicateItem(type,id){
			t = $("#tblcbreceiveitems").DataTable();
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

	</script>
</div>

<div id="mdlReceiveType" class="modal fade" role="dialog" >
	<div class="modal-dialog" style="display:table;width:900px">
		<!-- modal content -->
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">			
			<div class="modal-body">
				<div class="row">
                    <div class="col-md-12" >
                        <div style="border:0 px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">				
								<form id="form-detail-receive" class="form-horizontal">
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
									
									<div class="form-group divGLAccount"  style="display:none">
										<label for="fst_glaccount_code_pd" class="col-md-2 control-label"><?=lang("GL Account")?></label>
										<div class="col-md-10">
											<select  id="fst_glaccount_code_pd"   class="form-control" style="width:100%">											
											</select>
										</div>										
									</div>

									<div class="form-group divGLAccount"  style="display:none">
										<div>
											<label for="fin_pcc_id" class="col-md-2 control-label"><?=lang("Profit / Cost Center")?></label>
											<div class="col-md-4">
												<select  id="fin_pcc_id" class="form-control pcgroup" disabled>
													<option value='' selected></option>
													<?php
														$pcCenters = getDataTable("msprofitcostcenter","*","fst_active ='A'");
														foreach($pcCenters as $center){
															//$selected = $currency->fst_curr_code == "IDR" ? "SELECTED" : "";
															echo "<option value='$center->fin_pcc_id'>$center->fst_pcc_name</option>";
														}
													?>
												</select>
											</div>
											<label for="fin_pcc_id" class="col-md-2 control-label"><?=lang("Analisa Divisi")?></label>
											<div class="col-md-4">
												<select  id="fin_pc_divisi_id" class="form-control pcgroup">
													<option value='' selected></option>
													<?php
														$deparmentList = getDataTable("departments","*","fst_active ='A'");
														foreach($deparmentList as $department){
															//$selected = $currency->fst_curr_code == "IDR" ? "SELECTED" : "";
															$isDisabled = $department->fst_active == 'D' ? "disabled" :"";
															echo "<option value='$department->fin_department_id' $isDisabled>$department->fst_department_name</option>";
														}
													?>
												</select>
											</div>
										</div>
									</div>									

									<div class="form-group divGLAccount"  style="display:none">
										<label for="fin_pc_customer_id" class="col-md-2 control-label"><?=lang("Analisa Customer")?></label>
										<div class="col-md-4">
											<select  id="fin_pc_customer_id" class="form-control pcgroup" style="width:100%">
												<option value='' selected></option>												
												<?php
													$customerList = getDataTable("msrelations","*","fst_active ='A' and find_in_set('1',fst_relation_type)");
													foreach($customerList as $customer){
														//$selected = $currency->fst_curr_code == "IDR" ? "SELECTED" : "";
														echo "<option value='$customer->fin_relation_id'>$customer->fst_relation_name</option>";
													}
												?>
											</select>
										</div>
										<label for="fin_pc_project_id" class="col-md-2 control-label"><?=lang("Analisa Project")?></label>
										<div class="col-md-4">
											<select  id="fin_pc_project_id" class="form-control pcgroup">
												<option value='' selected></option>
												<?php
													$projectList = getDataTable("msprojects","*","fst_active ='A'");
													foreach($projectList as $project){
														//$selected = $currency->fst_curr_code == "IDR" ? "SELECTED" : "";
														echo "<option value='$project->fin_project_id'>$project->fst_project_name</option>";
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

									<div class="form-group" id="divGiro" style="display:none">
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
									<button id="btn-add-type-penerimaan" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Add")?></button>
									<button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(function(){
			$("#fst_trans_type_pt").val(null);

			$("#fst_trans_type_pt").change(function(e){
				e.preventDefault();
				$("#fst_glaccount_code_pd").prop("disabled",false);
				if ($("#fst_trans_type_pt").val() == "TUNAI" ||$("#fst_trans_type_pt").val() == "TRANSFER"){
					var acc  = $("#fin_kasbank_id option:selected").data("glaccount");
					$("#fst_glaccount_code_pd").empty();
					$("#fst_glaccount_code_pd").append("<option value='"+acc+"' selected>"+ $("#fin_kasbank_id option:selected").data("glaccount") + " - " + $("#fin_kasbank_id option:selected").text() +"</option>")
					//$("#fst_glaccount_code_pd").val(null);
					$(".pcgroup").val(null);
					$(".divGLAccount").hide("fast");
					$("#divGiro").hide("fast");	
					
					$("#fst_bilyet_no").val(null);
					$("fdt_clear_date").val(null);

					$("#fin_pcc_id").val(null);
					$("#fin_pc_divisi_id").val(null);
					$("#fin_pc_customer_id").val(null).trigger("change.select2");
					$("#fin_pc_project_id").val(null);

				}else if($("#fst_trans_type_pt").val() == "GIRO"){
					$("#fst_glaccount_code_pd").empty();
					<?php					
					$accGiroMundur = $this->trcbreceive_model->getInGiroAccount();				
					if($accGiroMundur){ ?>				
						$("#fst_glaccount_code_pd").append("<option value='<?=$accGiroMundur->fst_glaccount_code?>' selected><?= $accGiroMundur->fst_glaccount_code ." - " .$accGiroMundur->fst_glaccount_name ?></option>")
					<?php } ?>
					$(".pcgroup").val(null);
					$(".divGLAccount").hide("fast");
					$("#divGiro").show("fast");
					$("#fin_pcc_id").val(null);
					$("#fin_pc_divisi_id").val(null);
					$("#fin_pc_customer_id").val(null).trigger("change.select2");
					$("#fin_pc_project_id").val(null);
					
									
				}else if($("#fst_trans_type_pt").val() == "GLACCOUNT"){
					$("#fst_glaccount_code_pd").empty();
					<?php
					//$accounts = getDataTable("glaccounts","*","fst_active ='A' and fst_glaccount_level != 'HD' and fbl_is_allow_in_cash_bank_module = 1");
					$accounts = $this->trcbreceive_model->getAccountList();
					foreach($accounts as $account){ 
						$isProfitLost = $account->fst_glaccount_type == "PROFIT_LOST" ? 1 : 0;					
						$isAnalisaDivisi = $account->fbl_pc_divisi;
						$isAnalisaCustomer = $account->fbl_pc_customer;
						$isAnalisaProject = $account->fbl_pc_project; ?>
						$("#fst_glaccount_code_pd").append("<option value='"+"<?=$account->fst_glaccount_code?>"+"' data-is_pc='" + <?= $isProfitLost ?> +"' data-is_pc_divisi='<?=$account->fbl_pc_divisi?>' data-is_pc_customer='<?=$account->fbl_pc_customer?>' data-is_pc_project='<?=$account->fbl_pc_project?>' >"+"<?=$account->fst_glaccount_code . " - " . $account->fst_glaccount_name?>"+"</option>");
						//$selected = $currency->fst_curr_code == "IDR" ? "SELECTED" : "";						
					<?php } ?>
					$(".divGLAccount").show("fast");
					$("#divGiro").hide("fast");
					$("#fst_bilyet_no").val(null);
					$("fdt_clear_date").val(null);				
				}
			});

			$("#btn-add-type-penerimaan").click(function(e){
				e.preventDefault();

				//VALIDASI
				if ($("#fst_trans_type_pt").val() == null){
					alert("<?=lang("Type transaksi kosong !")?>");
					return;
				}
				if ($("#fdc_amount").val() == 0){
					alert("<?=lang("Nominal 0.00 !")?>");
					return;
				}

				if ($("#fst_trans_type_pt").val() == "GLACCOUNT"){
					var isPCC = $("#fst_glaccount_code_pd option:selected").data("is_pc");
					var isPCDivisi = $("#fst_glaccount_code_pd option:selected").data("is_pc_divisi");
					var isPCCustomer = $("#fst_glaccount_code_pd option:selected").data("is_pc_customer");
					var isPCProject = $("#fst_glaccount_code_pd option:selected").data("is_pc_project");
					if (isPCC ==  1){
						if ($("#fin_pcc_id").val() == ""){
							alert("<?= lang("Profit & Cost Center harus diisi !")?>");
							return;
						};
					}
					if (isPCDivisi ==  1){
						if ($("#fin_pc_divisi_id").val() == ""){
							alert("<?= lang("Analisa divisi harus diisi !")?>");
							return;
						};
					}
					if (isPCCustomer ==  1){
						if ($("#fin_pc_customer_id").val() == ""){
							alert("<?= lang("Analisa customer harus diisi !")?>");
							return;
						};
					}
					if (isPCProject ==  1){
						if ($("#fin_pc_project_id").val() == ""){
							alert("<?= lang("Analisa project harus diisi !")?>");
							return;
						};
					}					 
				}

				t = $("#tblcbreceiveitemstype").DataTable();
				data = {
					fin_rec_id:$("#fin_rec_id_item_type").val(),
					fst_cbreceive_type:$("#fst_trans_type_pt").val(),
					fst_cbreceive_type_name:$("#fst_trans_type_pt option:selected").text(),
					fst_glaccount_code:$("#fst_glaccount_code_pd").val(),
					fst_glaccount_code_name:$("#fst_glaccount_code_pd option:selected").text(),
					fin_pcc_id:$("#fin_pcc_id").val(),
					fst_pcc_name:$("#fin_pcc_id option:selected").text(),
					fin_pc_divisi_id:$("#fin_pc_divisi_id").val(),
					fst_pc_divisi_name:$("#fin_pc_divisi_id option:selected").text(),					
					fin_pc_customer_id:$("#fin_pc_customer_id").val(),
					fst_pc_customer_name:$("#fin_pc_customer_id option:selected").text(),					
					fin_pc_project_id:$("#fin_pc_project_id").val(),
					fst_pc_project_name:$("#fin_pc_project_id option:selected").text(),					
					fdc_amount:$("#fdc_amount").val(),
					fst_referensi:$("#fst_referensi").val(),
					fst_bilyet_no:$("#fst_bilyet_no").val(),
					fdt_clear_date:$("#fdt_clear_date").val(),
				};

				if (rowReceiveItemType == null){
					t.row.add(data).draw(false);
				}else{
					t.row(rowReceiveItemType).data(data).draw(false);
				}
				
				calculateRincianPenerimaan();
				$("#mdlReceiveType").modal("hide");


			});

			$("#fst_glaccount_code_pd").select2();
			$("#fst_glaccount_code_pd").change(function(e){
				if ($("#fst_glaccount_code_pd option:selected").data("is_pc") == 0){
					$("#fin_pcc_id").val(null);
					$("#fin_pcc_id").prop("disabled",true);
				}else{
					$("#fin_pcc_id").prop("disabled",false);
				}
			});

			$("#fin_pc_customer_id").select2();

		});
		function clearFormPaymentDetail(){
			$("#fin_rec_id_item_type").val(0);
			$("#fst_trans_type_pt").val(null);
			$("#fst_glaccount_code_pd").val(null).trigger("change.select2");
			$("#fin_pcc_id").val(null);
			$("#fin_pc_divisi_id").val(null);
			$("#fin_pc_customer_id").val(null).trigger("change.select2");
			$("#fin_pc_project_id").val(null);
			$("#fdc_amount").val(App.money_format(0));
			$("#fdc_amount_idr").val(App.money_format(0));
			$("#fst_referensi").val("");
			$("#fst_bilyet_no").val("");
			$("#fdt_clear_date").val("");
			rowPaymentItemType = null;
		}
		function calculateRincianPenerimaan(){
			t = $("#tblcbreceiveitemstype").DataTable();
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
	</script>
</div>

<?php echo $mdlEditForm ?>
<?php echo $mdlJurnal ?>

<script type="text/javascript" info="bind">
	$(function(){
		$(document).bind('keydown', 'alt+d', function(){
			$("#btn-add-detail").trigger("click");
		});

		$(document).bind('keydown', 'alt+j', function(){
			$("#btnJurnal").trigger("click");
		});
	});
	
</script>

<script type="text/javascript" info="define">
	var rowReceiveItem;
	var rowReceiveItemType;
</script>

<script type="text/javascript" info="event">
	$(function(){
		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url()?>tr/kas_bank/penerimaan/add";
		});

		$("#btnSubmitAjax").click(function(e){
			submitAjax(0);
		});
		$("#btnPrint").click(function(e){
			e.preventDefault();
		});
		$("#btnJurnal").click(function(e){
			e.preventDefault();
			MdlJurnal.showJurnalByRef("CBIN",$("#fin_cbreceive_id").val());
		});

		$("#btnDelete").click(function(e){
			e.preventDefault();
			deleteAjax(0);
		});

		$("#btnClose").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url()?>tr/kas_bank/penerimaan/";
		});

		$(".type-penerimaan").change(function(e){
			e.preventDefault();
			getKasBankList($(".type-penerimaan:checked").val());
			t = $("#tblcbreceiveitemstype").DataTable();
			t.clear().draw(false);
		});


		$("#fin_kasbank_id").change(function(e){
			e.preventDefault();
			getTransactionNumber($("#fin_kasbank_id").val(),$("#fdt_cbreceive_datetime").val());
			t = $("#tblcbreceiveitemstype").DataTable();
			t.clear().draw(false);
		});

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
			calculateRincianPenerimaan();
		});

		$("#fin_customer_id").change(function(e){
			e.preventDefault();
			cleanRincianTransaksi();
		});

		$("#btn-add-detail").click(function(e){
			e.preventDefault();	
			if ($("#fin_customer_id").val() == null){
				alert("<?= lang('Pilih customer ...!') ?>");
				return;
			}		
			clearFormDetail();			
			$("#mdlAddItem").modal("show");
		});

		$("#btn-detail-penerimaan").click(function(e){
			e.preventDefault();
			if ($("#fin_kasbank_id").val() == null){
				alert("<?= lang('Pilih jenis penerimaan ...!') ?>");
				return;
			}

			clearFormPaymentDetail();
			$("#mdlReceiveType").modal("show");
		});

	});

</script>

<script type="text/javascript" info="init">
	$(function(){
		$("#fdt_cbreceive_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");	

		$('#tblcbreceiveitems').on('preXhr.dt', function ( e, settings, data ) {
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
							case "DP_SO":
								return "DP Penjualan";
								break;
							case "INV_SO":
								return "Faktur Penjualan";
								break;
							
							case "RETURN_SO":
								return "Return Penjualan Non Faktur";
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
				{"title" : "Paid",width:"50px",data:"fdc_paid_amount",
					render:function(data,type,row){
						return App.money_format(data);
					},
					//render: $.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					className:'text-right'
				},
				{"title" : "Payment",width:"50px",data:"fdc_receive_amount",
					render:function(data,type,row){
						return App.money_format(data);
					},
					className:'text-right'
				},
				{"title" : "notes","width": "350px",sortable:false,data:"fst_memo",visible:true},
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
			t = $('#tblcbreceiveitems').DataTable();
			var trRow = $(this).parents('tr');
			rowReceiveItem = trRow;
			var row = t.row(trRow).data();
			
			$("#fin_rec_id_items").val(row.fin_rec_id);
			$("#fst_trans_type").val(row.fst_trans_type);
			
			getTransactionList(row.fst_trans_type,function(resp){
				App.addOptionIfNotExist("<option value='"+ row.fin_trans_id +"' data-ttl_amount='"+row.fdc_trans_amount+"' data-ttl_paid='"+row.fdc_paid_amount+"' data-ttl_return='"+row.fdc_return_amount+"' selected>"+row.fst_trans_no+"</option>","fin_trans_id");
				$("#fdc_trans_amount").val(App.money_format(row.fdc_trans_amount));
				$("#ttl-paid").val(App.money_format(row.fdc_paid_amount));
				$("#fdc_return_amount").val(App.money_format(row.fdc_return_amount));
				$("#fdc_receive_amount").val(App.money_format(row.fdc_receive_amount));
			});	

			//$("#fst_trans_type").prop("disabled",true);
			//$("#fin_trans_id").prop("disabled",true);
			$("#mdlAddItem").modal("show");


		}).on("click",".btn-delete",function(e){
			t = $('#tblcbreceiveitems').DataTable();
			var trRow = $(this).parents('tr');
			t.row(trRow).remove().draw();
			calculateRincianTransaksi();
		});

		$('#tblcbreceiveitemstype').on('preXhr.dt', function ( e, settings, data ) {
			//add aditional data post on ajax call
			data.sessionId = "TEST SESSION ID";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "id","width": "50px",sortable:false,data:"fin_rec_id",visible:true},
				{"title" : "Type","width": "100px",sortable:false,data:"fst_cbreceive_type",
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
				{"title" : "Nominal",width:"100px",data:"fdc_amount",
					render:function(data,type,row){
						return App.money_format(row.fdc_amount);
					},
					//render: $.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					className:'text-right'
				},	
				{"title" : "Profit / Cost Center",width:"150px",data:"fin_pcc_id",
					render: function(data,type,row){
						return row.fst_pcc_name;
					},
				},
				{"title" : "Analisa Divisi",width:"150px",data:"fin_pc_divisi_id",
					render: function(data,type,row){
						return row.fst_pc_divisi_name;
					},
				},
				{"title" : "Analisa Customer",width:"150px",data:"fin_pc_customer_id",
					render: function(data,type,row){
						return row.fst_pc_customer_name;
					},
				},
				{"title" : "Analisa Project",width:"150px",data:"fin_pc_project_id",
					render: function(data,type,row){
						return row.fst_pc_project_name;
					},
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
			t = $("#tblcbreceiveitemstype").DataTable();
			var trRow = $(this).parents('tr');
			rowReceiveItemType = trRow;
			var data = t.row(trRow).data();

			$("#fin_rec_id_item_type").val(data.fin_rec_id);
			$("#fst_trans_type_pt").val(data.fst_cbreceive_type);
			$("#fst_trans_type_pt").trigger("change");

			$("#fst_curr_code_payment_detail").val(data.fst_curr_code);
			$("#fdc_exchange_rate_idr_payment_detail").val(data.fdc_exchange_rate_idr);
			$("#fst_glaccount_code_pd").val(data.fst_glaccount_code);
			$("#fst_glaccount_code_pd").trigger("change.select2");
			
			$("#fin_pcc_id").val(data.fin_pcc_id);
			$("#fdc_amount").val(App.money_format(data.fdc_amount));
			var amountIDR = data.fdc_amount * $("#fdc_exchange_rate_idr").val();
			$("#fdc_amount_idr").val(App.money_format(amountIDR));
			$("#fst_referensi").val(data.fst_referensi);
			$("#fst_bilyet_no").val(data.fst_bilyet_no);
			$("#fdt_clear_date").val(data.fdt_clear_date);

			$("#mdlReceiveType").modal("show");
			
		}).on('click','.btn-delete',function(e){
			e.preventDefault();
			t = $('#tblcbreceiveitemstype').DataTable();
			var trRow = $(this).parents('tr');
			t.row(trRow).remove().draw();
			calculateRincianPenerimaan();


		});

		$("#fin_customer_id").val(null);

		initForm();

	});
</script>

<script type="text/javascript" info="function">
	function getTransactionNumber(kasBankId,cbReceiveDatetime){
		cbReceiveDatetime = App.dateTimeParse(cbReceiveDatetime);
		App.getValueAjax({
			site_url:"<?=site_url()?>",
			model: "trcbreceive_model",
			func:"generateCBReceiveNo",
			params:[kasBankId,cbReceiveDatetime],
			callback:function(value){
				$("#fst_cbreceive_no").val(value);
			}
		});
	}

	function cleanRincianTransaksi(){
		t = $("#tblcbreceiveitems").DataTable();
		t.rows().remove();
		t.draw(false);
	}

	function initForm(){
		if ($("#fin_cbreceive_id").val() != 0){ //EDIT MODE
			//Get Data
			App.blockUIOnAjaxRequest("Get data transaction.....");
			$.ajax({
				url:"<?=site_url()?>tr/kas_bank/penerimaan/fetch_data/" + $("#fin_cbreceive_id").val(),

			}).done(function(resp){
				console.log(resp);
				if (resp.message != ""){
					alert(resp.message);
				}
				if(resp.status =="SUCCESS"){
					var cbReceive = resp.cbreceive;
					var cbReceiveItems = resp.cbreceive_items;
					var cbReceiveItemsType = resp.cbreceive_items_type;					
					if(cbReceive.fst_kasbank_type == "Cash"){
						$("#typeKas").prop("checked",true);
					}else{
						$("#typeBank").prop("checked",true);
					}

					App.autoFillForm(cbReceive);
					getKasBankList($(".type-penerimaan:checked").val(),function(listValue){
						$("#fin_kasbank_id").val(cbReceive.fin_kasbank_id);
					});
					$("#fdt_cbreceive_datetime").val(dateTimeFormat(cbReceive.fdt_cbreceive_datetime)).datetimepicker("update");	

					t = $("#tblcbreceiveitems").DataTable();
					dataItems = [];
					$.each(cbReceiveItems,function(i,v){														
						data = {
							fin_rec_id:v.fin_rec_id,
							fst_trans_type:v.fst_trans_type,
							fin_trans_id:v.fin_trans_id,
							fst_trans_no:v.fst_trans_no,
							fdc_trans_amount: v.fdc_trans_amount,
							fdc_paid_amount: v.fdc_paid_amount,
							fdc_return_amount:v.fdc_return_amount,
							fdc_receive_amount:v.fdc_receive_amount,
							fst_memo:v.fst_memo
						};	
						dataItems.push(data);					
					});
					t.rows.add(dataItems).draw(false);
					calculateRincianTransaksi();

					t = $("#tblcbreceiveitemstype").DataTable();
					dataItemsType = [];
					$.each(cbReceiveItemsType,function(i,v){
						data = {
							fin_rec_id:v.fin_rec_id,
							fst_cbreceive_type:v.fst_cbreceive_type,
							fst_cbreceive_type_name:v.fst_cbreceive_type_name,
							fst_curr_code:v.fst_curr_code,
							fdc_exchange_rate_idr:v.fdc_exchange_rate_idr,
							fst_glaccount_code:v.fst_glaccount_code,
							fst_glaccount_code_name:v.fst_glaccount_code + " - " + v.fst_glaccount_name,
							fin_pcc_id:v.fin_pcc_id,
							fst_pcc_name:v.fst_pcc_name,
							fin_pc_divisi_id:v.fin_pc_divisi_id,
							fst_pc_divisi_name:v.fst_pc_divisi_name,
							fin_pc_customer_id:v.fin_pc_customer_id,
							fst_pc_customer_name:v.fst_pc_customer_name,
							fin_pc_project_id:v.fin_pc_project_id,
							fst_pc_project_name:v.fst_pc_project_name,
							fdc_amount:v.fdc_amount,
							fst_referensi:v.fst_referensi,
							fst_bilyet_no:v.fst_bilyet_no,
							fdt_clear_date:v.fdt_clear_date
						};
						
						dataItemsType.push(data);
					});
					t.rows.add(dataItemsType).draw(false);
					calculateRincianPenerimaan();

				}else{
					$("#btnNew").trigger("click");
				}

			});
		}else{
			$(".type-penerimaan").trigger("change");
		}
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

	function submitAjax(confirmEdit){

		if ($("#sub-total-rincian-transaksi-idr").val() != $("#sub-total-rincian-payment-idr").val()){
			alert("<?= lang('Total transaksi & Total Pembayaran tidak sama ...!') ?>");
			return;
		}
		if ($("#fin_kasbank_id").val() == null){
			alert("<?= lang('Pilih jenis penerimaan ...!') ?>");
			return;
		}

		var dataSubmit = $("#frmCBPenerimaan").serializeArray();
		var detailTrans = new Array();	
		var detailReceive = new Array();	

		var tTrans = $('#tblcbreceiveitems').DataTable();
		var tReceive = $('#tblcbreceiveitemstype').DataTable();

		var dataTrans = tTrans.data();
		var dataReceive = tReceive.data();

		$.each(dataTrans,function(i,v){
			detailTrans.push(v);
		});

		$.each(dataReceive,function(i,v){
			detailReceive.push(v);
		});

		dataSubmit.push({
			name:"detailTrans",
			value: JSON.stringify(detailTrans)
		});
		dataSubmit.push({
			name:"detailReceive",
			value: JSON.stringify(detailReceive)
		});


		mode = $("#fin_cbreceive_id").val() == "0" ? "ADD" : "EDIT";	


		if (mode == "ADD"){
			url =  "<?= site_url() ?>tr/kas_bank/penerimaan/ajx_add_save/";
		}else{
			dataSubmit.push({
				name : "fin_user_id_request_by",
				value: MdlEditForm.user
			});
			dataSubmit.push({
				name : "fst_edit_notes",
				value: MdlEditForm.notes
			});

			url =  "<?= site_url() ?>tr/kas_bank/penerimaan/ajx_edit_save/";
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
					$("#fin_cbreceive_id").val(data.insert_id);
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

		var url =  "<?= site_url() ?>tr/kas_bank/penerimaan/delete/" + $("#fin_cbreceive_id").val();
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