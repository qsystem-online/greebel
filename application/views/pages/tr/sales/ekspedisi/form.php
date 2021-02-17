<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?=base_url()?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<style type="text/css">	
	.form-group{
		margin-bottom: 5px;
	}
	.checkbox label, .radio label {
		font-weight:700;
	}
	.select2-results > ul > li > span{
		display:inline-block;
		margin-right:20px;
	}
</style>

<section class="content-header">
	<h1><?=lang("Ekspedisi")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Sales") ?></a></li>
		<li class="active title"><?=$title?></li>
	</ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
				<div class="box-header with-border">
				<h3 class="box-title title"><?=$title?></h3>
				<?php if ($mode != "VIEW") { ?>
				<div class="btn-group btn-group-sm  pull-right">					
					<a id="btnNew" class="btn btn-primary" href="#" title="<?=lang("Tambah Baru")?>"><i class="fa fa-plus" aria-hidden="true"></i></a>
					<a id="btnSubmitAjax" class="btn btn-primary" href="#" title="<?=lang("Simpan")?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
					<a id="btnPrint" class="btn btn-primary" href="#" title="<?=lang("Cetak")?>"><i class="fa fa-print" aria-hidden="true"></i></a>
					<a id="btnJurnal" class="btn btn-primary" href="#" title="<?=lang("Jurnal")?>" style="display:<?= $mode == "ADD" ? "none" : "inline-block" ?>"><i class="fa fa-align-left" aria-hidden="true"></i></a>
					<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
					<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
				</div>
				<?php } ?>

			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmHeader" class="form-horizontal">			
				<div class="box-body">
					<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">					
					<input type="hidden" class="form-control" id="fin_salesekspedisi_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_salesekspedisi_id" value="<?=$fin_salesekspedisi_id?>" readonly>

					
					<div class="form-group">
						<label for="fst_salesekspedisi_no" class="col-md-2 control-label"><?=lang("Ekspedisi No.")?> #</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_salesekspedisi_no" placeholder="<?=lang("Ekspedisi No")?>" name="fst_salesekspedisi_no" value="<?=$fst_salesekspedisi_no?>" readonly>
							<div id="fst_salesekspedisi_no_err" class="text-danger"></div>
						</div>
						
						<label for="fdt_salesekspedisi_datetime" class="col-md-2 control-label"><?=lang("Ekspedisi Date")?> *</label>
						<div class="col-md-4">
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control text-right datetimepicker" id="fdt_salesekspedisi_datetime" name="fdt_salesekspedisi_datetime"/>
							</div>
							<div id="fdt_salesekspedisi_datetime_err" class="text-danger"></div>
							<!-- /.input group -->
						</div>						
                    </div>
					<div class="form-group">	
						<label for="fin_customer_id" class="col-md-2 control-label"><?= lang("Customer")?></label>
						<div class="col-md-10">
							<select class="form-control" id="fin_customer_id" placeholder="<?= lang("Customer") ?>" name="fin_customer_id"  style="width:100%">
								<?php
									$customerList = $this->msrelations_model->getCustomerList();
									foreach($customerList as $customer){
										echo "<option value='$customer->fin_relation_id'> $customer->fst_relation_name </option>";
									}
								?>
							</select>
							<div id="fin_customer_id_err" class="text-danger"></div>
						</div>						
					</div>
					
					<div class="form-group">	
						<label for="fin_supplier_id" class="col-md-2 control-label"><?= lang("Ekspedisi")?></label>
						<div class="col-md-10">
							<select class="form-control" id="fin_supplier_id" placeholder="<?= lang("Exkspedisi") ?>" name="fin_supplier_id"  style="width:100%">
								<?php
									$supplierList = $this->msrelations_model->getSupplierList();
									foreach($supplierList as $supplier){
										echo "<option value='$supplier->fin_relation_id'> $supplier->fst_relation_name </option>";
									}
								?>
							</select>
							<div id="fin_supplier_id_err" class="text-danger"></div>
						</div>						
					</div>

					<div class="form-group">	
						<label for="fst_curr_code" class="col-md-2 control-label"><?= lang("Mata Uang")?></label>
						<div class="col-md-2">
							<select class="form-control" id="fst_curr_code" placeholder="<?= lang("Mata Uang") ?>" name="fst_curr_code">
								<?php
									$currList = $this->mscurrencies_model->getCurrencyList();
									foreach($currList as $curr){
										$selected = $curr->fbl_is_default == 1 ? "selected" : "";
										echo "<option value='$curr->fst_curr_code' data-rate='$curr->fdc_exchange_rate_to_idr' $selected>$curr->fst_curr_code</option>";
									}
								?>
							</select>
							<div id="fst_curr_code_err" class="text-danger"></div>
						</div>
						<div class="col-md-2">
								<input type="text" class="money form-control" id="fdc_exchange_rate_idr" name="fdc_exchange_rate_idr" value="1.00"/>
						</div>
						
					</div>

					<div class="form-group">	
						<label for="fst_no_referensi" class="col-md-2 control-label"><?= lang("No Reff")?></label>
						<div class="col-md-3">
							<input class="form-control" id="fst_no_referensi" placeholder="<?= lang("Reff") ?>" name="fst_no_referensi" />
							<div id="fst_no_referensi_err" class="text-danger"></div>
						</div>
						<label for="fbl_reclaimable" class="col-md-7"><input type="checkbox" id="fbl_reclaimable" name="fbl_reclaimable" style="margin-right:5px;"/> <?= lang("Reclaimable")?></label>
					</div>

					<div class="form-group">	
						<label for="fst_no_faktur_pajak" class="col-md-2 control-label"><?= lang("No Faktur Pajak")?></label>
						<div class="col-md-6">
							<input class="form-control" id="fst_no_faktur_pajak" placeholder="<?= lang("No. Faktur Pajak") ?>" name="fst_no_faktur_pajak" />
							<div id="fst_no_faktur_pajak_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">	
						<label for="fst_sj_list" class="col-md-2 control-label"><?= lang("Surat Jalan")?></label>
						<div class="col-md-10">
							<select class="form-control" id="fst_sj_list" placeholder="<?= lang("Surat Jalan")?> style="width:100%;min-height:100px" name="fst_sj_list[]" multiple></select>
							<div id="fst_sj_list_err" class="text-danger"></div>
						</div>						
					</div>

					<div class="form-group">	
						<label for="fin_shipping_address_id" class="col-md-2 control-label"><?= lang("Alamat Pengiriman")?></label>
						<div class="col-md-10">
							<select class="form-control" id="fin_shipping_address_id" placeholder="<?= lang("Surat Jalan")?> style="width:100%;min-height:100px" name="fin_shipping_address_id"></select>
							<div id="fin_shipping_address_id_err" class="text-danger"></div>
						</div>						
					</div>
					<div class="form-group">	
						<div class="col-md-10 col-md-offset-2">
							<textarea class="form-control" id="shipping-address" disabled ></textarea>
						</div>						
					</div>



					<div class="form-group">								
						<div class="col-sm-6">
							<label for="fst_inv_memo" class=""><?=lang("Memo")?></label>
							<textarea class="form-control" id="fst_inv_memo" placeholder="<?= lang("Memo") ?>" name="fst_inv_memo" rows="5" style="resize:none"></textarea>
							<div id="fst_inv_memo_err" class="text-danger"></div>
						</div>
					
                        <div class="col-sm-6">	
							<div class="form-group">
								<label for="sub-total" class="col-md-8 control-label"><?= lang("Qty (Kodi)")?></label>
								<div class="col-md-4" style="text-align:right">
									<input type="text" class="ttl money form-control text-right" id="fdb_qty" name="fdb_qty" value="1"/>
								</div>
							</div>
							<div class="form-group">
								<label for="ttl-disc" class="col-md-5 control-label"><?= lang("Biaya per kodi")?></label>
								<div class="col-md-3" style="text-align:right">
									<input type="text" class="ttl money form-control text-right" id="fdc_price" name="fdc_price" value="0" >
								</div>
								<div class="col-md-4" style="text-align:right">
									<input type="text" class="money form-control text-right" id="sub_total" n value="0" readonly/>
								</div>
							</div>
							
							<div class="form-group">
								<label for="sub-total" class="col-md-6 control-label">%PPn</label>
								<div class="col-md-2" style="text-align:right">
									<input type="text" class="ttl form-control text-right unfocus" id="fdc_ppn_percent" name="fdc_ppn_percent" value="10">
								</div>
								<div class="col-md-4" style="text-align:right">
									<input type="text" class="form-control text-right" id="fdc_ppn_amount" name="fdc_ppn_amount" value="0" readonly>	
								</div>
							</div>
							<div class="form-group">
								<label for="fdc_other" class="col-md-8 control-label"><?= lang("Lain 2")?></label>
								<div class="col-md-4" style="text-align:right">
									<input type="text" class="ttl money form-control text-right" id="fdc_other" name="fdc_other" value="0" style="text-align: right;">
									<div id="fdc_other_err" class="text-danger"></div>
								</div>
							</div>
							<div class="form-group">
								<label for="total" class="col-md-8 control-label">Total</label>
								<div class="col-md-4" style="text-align:right">
									<input type="text" class="form-control text-right" id="total" value="0" readonly>
								</div>
							</div>
							
						</div>
						
					</div>
					
					
					

                </div>
				<!-- end box body -->

                <div class="box-footer text-right">
                    <!-- <a id="btnSubmitAjax" href="#" class="btn btn-primary"><=lang("Simpan")?></a> -->
                </div>
                <!-- end box-footer -->
            </form>
        </div>
    </div>
</section>

<?php
	echo $mdlJurnal;
	echo $mdlEditForm;
	echo $mdlPrint;
?>

<script type="text/javascript" info="bind">
	$(function(){
	
		$(document).bind('keydown', 'alt+j', function(){
			$("#btnJurnal").trigger("click");
		});
	});
</script>

<script type="text/javascript" info="define">	
</script>

<script type="text/javascript" info="event">
	$(function(){
		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>tr/sales/ekspedisi/add");
		});

		$("#btnSubmitAjax").click(function(e){
            e.preventDefault();
            submitAjax(0);
		});

		$("#btnPrint").click(function(e){
			e.preventDefault();
			frameVoucher.print("<?=site_url()?>tr/sales/ekspedisi/print_voucher/" + $("#fin_salesekspedisi_id").val());
		});

		$("#btnJurnal").click(function(e){
			e.preventDefault();
			MdlJurnal.showJurnalByRef("EXP",$("#fin_salesekspedisi_id").val());			

		});

		
		$("#btnDelete").click(function(e){
			e.preventDefault();
			deleteAjax(0);
		});
		$("#btnList").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>tr/sales/ekspedisi");
		});

		$("#fin_customer_id").change(function(e){
			$("#fin_shipping_address_id").empty().trigger("change");
			//$("#fin_shipping_address_id").val(null).trigger("change");
			$("#shipping-address").val(null);
			$("#fst_sj_list").empty().trigger("change");
			$(".ttl").trigger("change");
		});

		$("#fst_curr_code").change(function(e){
			e.preventDefault();
			var rate = $("#fst_curr_code option:selected").data("rate");
			//alert(rate);
			$("#fdc_exchange_rate_idr").val(App.money_format(rate));	

		});		

		$(".ttl").change(function(e){
			e.preventDefault();
			var qty = parseFloat($("#fdb_qty").val());
			var price = parseFloat($("#fdc_price").val());
			var subTotal = qty * price;
			var ppnPercent = parseFloat($("#fdc_ppn_percent").val());
			var ppnAmount = subTotal * (ppnPercent /100);
			var other = parseFloat($("#fdc_other").val());
			var total = subTotal + ppnAmount + other;
			
			$("#sub_total").val(money_format(subTotal));
			$("#fdc_ppn_amount").val(money_format(ppnAmount));
			$("#total").val(money_format(total));


			
		})
	});
</script>

<script type="text/javascript" info="init">
	$(function(){	
		$("#btnDelete").confirmation({
			title:"<?= lang("Hapus data ini ?") ?>",
			rootSelector: '#btnDelete',
			placement:'left',
		});
		$("#fdt_salesekspedisi_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker('update');
		
		$("#fin_customer_id").select2();
		$("#fin_supplier_id").select2();
		//$("#fdc_exchange_rate_idr").val(App.money_format($("#fdc_exchange_rate_idr").val()));	


		$("#fst_sj_list").select2({
			ajax: {
				delay: 500,
				url: '<?=site_url()?>tr/sales/ekspedisi/get_sj_list',
				data:function(params){
					params.fin_customer_id = $("#fin_customer_id").val();					
					return params;
				},
				dataType: 'json',
				processResults: function (data) {      
					sjList = data.data.sjList;					
					arrSJ = sjList.map(function(sj){
						return {
							id: sj.fin_sj_id,
							text: sj.fst_sj_no,
							fdt_sj_datetime:App.dateFormat(sj.fdt_sj_datetime),
							fst_salesorder_no:sj.fst_salesorder_no,
							fdt_salesorder_datetime:sj.fdt_salesorder_datetime,
						}
					});				
					return {
						results: arrSJ
					};
    			}
			},
			minimumInputLength: 0,
			templateResult: function(sj){
				if (sj.loading == true){
					return sj.text;
				};

				return $("<span style='width:150px'>" + sj.text + "</span><span style='width:120px'>" + sj.fdt_sj_datetime + "</span><span>" + sj.fst_salesorder_no + "</span><span style='width:120px'>" + sj.fdt_salesorder_datetime + "</span>");
			}
		});

		$("#fin_shipping_address_id").select2({
			ajax: {
				delay: 500,
				url: function(){
					
					return '<?=site_url()?>pr/relation/get_shipping_address/' + $("#fin_customer_id").val();
				},			
				dataType: 'json',
				beforeSend:function(){
					
				},
				processResults: function (data) {      
					addressList = data.data;	
					arrAddress = addressList.map(function(address){
						return {
							id: address.fin_shipping_address_id,
							text: address.fst_name,
							fst_address:address.fst_shipping_address
						}
					});			
					return {
						results: arrAddress
					};
    			}
			},
			minimumInputLength: 0,
			//minimumResultsForSearch: Infinity,
			templateResult: function(sj){
				if (sj.loading == true){
					return sj.text;
				};
				return sj.text;				
			}
		}).on("select2:select",function(e){
			data = e.params.data;
			$("#shipping-address").val(data.fst_address);
		});
		
		App.fixedSelect2();
		initForm();
	});
</script>

<script type="text/javascript" info="function">
	function getDetailSJ(sjList, callback){
		App.blockUIOnAjaxRequest();
		dataPost = [];
		dataPost.push({
			name:SECURITY_NAME,
			value:SECURITY_VALUE,
		});
		
		dataPost.push({
			name:"fst_sj_id_list",
			value:sjList,
		});

		
		$.ajax({
			url:"<?=site_url()?>tr/sales/invoice/get_detail_sj",
			method:"POST",
			data:dataPost,
		}).done(function(resp){
			arrSJDetail = resp.data.arrSJDetail;
			sjDetailList = arrSJDetail.map(function(sjDetail){
				return {
					fin_rec_id:0,
					fin_item_id:sjDetail.fin_item_id,
					fst_custom_item_name:sjDetail.fst_custom_item_name,
					fdb_qty_so:sjDetail.fdb_qty_so,
					fdb_qty_sj:sjDetail.fdb_qty_sj,
					fst_unit:sjDetail.fst_unit,
					fdc_price:sjDetail.fdc_price,
					fst_disc_item:sjDetail.fst_disc_item,
					fdc_disc_amount_per_item:sjDetail.fdc_disc_amount_per_item					
				}
			});
			App.log(sjDetailList);
			
			t = $("#tblInvItems").DataTable();
			t.clear();
			t.rows.add(sjDetailList).draw();
			
			
		});

	}
	function calculateTotal(){
        t = $("#tblInvItems").DataTable();
		datas = t.rows().data();    
		ttlBfDisc =0;
		ttlDisc = 0;
		$.each(datas,function(i,v){
			qty = v.fdb_qty_sj;
			price =  v.fdc_price;

			ttlBfDisc += qty * price;
			ttlDisc += qty * v.fdc_disc_amount_per_item;
		});
		
		var total = ttlBfDisc - ttlDisc;
		var dppAmount;

		if ($("#fbl_is_vat_include").prop('checked')){
			dppAmount =  total / (1 + $("#fdc_vat_percent").val() /100);						
		}else{
			dppAmount = ttlBfDisc - ttlDisc;			
		}

		subTotal = dppAmount;
		vat = ($("#fdc_vat_percent").val() /100) * subTotal;
		total = subTotal + vat;
		$("#sub-total").val(money_format(subTotal));	
		$("#ttl-disc").val(money_format(ttlDisc));	
		$("#fdc_vat_amount").val(money_format(vat));
		$("#total").val(money_format(total));	
	}

	function getPaymentDueDate(){
		tglInv = $("#fdt_inv_datetime").val();
		tglDueDate = moment(tglInv,DATETIMEPICKER_FORMAT_MOMENT).add($("#fin_terms_payment").val(), 'days');
		$("#fdt_payment_due_date").val(tglDueDate.format(DATEPICKER_FORMAT_MOMENT));
	}


	function submitAjax(confirmEdit){
        
        data = $("#frmHeader").serializeArray();
		detail = new Array();		

		/*
		t = $('#tblInvDetails').DataTable();
		datas = t.data();
		$.each(datas,function(i,v){
			detail.push(v);
		});
		*/

		
		mode = $("#fin_salesekspedisi_id").val() != 0 ? "EDIT" : "ADD";

		
		if (mode == "EDIT"){
			url = "<?=site_url()?>tr/sales/ekspedisi/ajx_edit_save";
			if (confirmEdit == 0 && mode != "ADD"){
				MdlEditForm.saveCallBack = function(){
					submitAjax(1);
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
		}else{
			url = "<?=site_url()?>tr/sales/ekspedisi/ajx_add_save";
		}
			
		App.blockUIOnAjaxRequest("<h5>Please wait....</h5>");
        $.ajax({
            url : url,
            data: data,
            method: "POST",
        }).done(function(resp){
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
			}

        }).always(function(resp){
		});

	}


	function initForm(){
		if($("#fin_salesekspedisi_id").val() != 0){
			App.blockUIOnAjaxRequest();
			$.ajax({
				url:"<?= site_url() ?>tr/sales/ekspedisi/fetch_data/" + $("#fin_salesekspedisi_id").val(),
			}).done(function(resp){
				App.log(resp);
				var dataH = resp.salesEkspedisi;
				var dataDetails = resp.salesEkspedisiItems;

				if (dataH == null){
					alert("<?= lang("Data tidak ditemukan !") ?>");
					//$("#btnNew").trigger("click");					
				}
				App.autoFillForm(dataH);

				/*
				if (dataH.fbl_reclaimable == 1){
					$("#fbl_reclaimable").prop("checked",true);
				}else{
					$("#fbl_reclaimable").prop("checked",false);
				}
				*/

				$("#fdt_salesekspedisi_datetime").val(dateTimeFormat(dataH.fdt_salesekspedisi_datetime)).datetimepicker("update");

				$("#fin_customer_id").val(dataH.fin_customer_id).trigger("change.select2");
				$("#fin_supplier_id").val(dataH.fin_supplier_id).trigger("change.select2");
				
				arrId = [];

				$.each(dataDetails,function(i,v){
					App.addOptionIfNotExist("<option value='"+v.fin_sj_id+"'>"+ v.fst_sj_no +"</option>","fst_sj_list");
					arrId.push(v.fin_sj_id);
				});

				$("#fst_sj_list").val(arrId).trigger("change.select2");


				App.addOptionIfNotExist("<option value='"+dataH.fin_shipping_address_id+"'>"+ dataH.fst_shipping_address_name +"</option>","fin_shipping_address_id");
				$("#shipping-address").val(dataH.fst_shipping_address);

				//App.addOptionIfNotExist("<option value='"+dataH.fin_supplier_id+"'>"+ dataH.fst_supplier_name +"</option>","fin_supplier_id");
				
				
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
			name : SECURITY_NAME,
			value: SECURITY_VALUE
		});
		dataSubmit.push({
			name : "fin_user_id_request_by",
			value: MdlEditForm.user
		});
		dataSubmit.push({
			name : "fst_edit_notes",
			value: MdlEditForm.notes
		});

		blockUIOnAjaxRequest("<h5>Deleting ....</h5>");
		$.ajax({
			url:"<?= site_url() ?>tr/sales/ekspedisi/delete/" + $("#fin_salesekspedisi_id").val(),
			method:"POST",
			data:dataSubmit,

		}).done(function(resp){
			consoleLog(resp);
			$.unblockUI();
			if (resp.message != "")	{
				$.alert({
					title: 'Message',
					content: resp.message,
					buttons : {
						OK : function(){
							if(resp.status == "SUCCESS"){
								$("#btnList").trigger("click");
							}
						},
					}
				});
			}

			if(resp.status == "SUCCESS") {
				data = resp.data;
				$("#fin_inv_id").val(data.insert_id);

				//Clear all previous error
				$(".text-danger").html("");
				// Change to Edit mode
				$("#frm-mode").val("EDIT");  //ADD|EDIT
				$('#fst_inv_no').prop('readonly', true);				
			}
		});
	}

</script>

<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
