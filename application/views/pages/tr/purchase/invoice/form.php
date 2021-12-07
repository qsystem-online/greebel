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
	<h1><?=lang("Pembelian - Faktur")?><small><?=lang("form")?></small></h1>
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
						<a id="btnJurnal" class="btn btn-primary" href="#" title="Jurnal" style="display:<?= $mode == "ADD" ? "none" : "inline-block" ?>"><i class="fa fa-align-left" aria-hidden="true"></i></a>
						<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
						<a id="btnClose" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
					</div>
				</div>
				<!-- end box header -->

				<!-- form start -->
				<form id="frmLPBPurchase" class="form-horizontal"  method="POST" enctype="multipart/form-data">			
					<div class="box-body">
						<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">	
						<input type="hidden" class="form-control" id="fin_lpbpurchase_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_lpbpurchase_id" value="<?=$fin_lpbpurchase_id?>" readonly>



                        <div class="form-group">
							<label for="fst_lpbpurchase_no" class="col-md-2 control-label"><?=lang("No. Faktur")?> #</label>	
							<div class="col-md-4">				
								<input type="TEXT" id="fst_lpbpurchase_no" name="fst_lpbpurchase_no" class="form-control"  value="<?=$fst_lpbpurchase_no?>" placeholder="PREFIX/BRANCH/YEAR/MONTH/99999" /> 
							</div>

							<label for="fdt_lpbpurchase_datetime" class="col-md-2 control-label text-right"><?=lang("Tanggal Faktur")?> *</label>
							<div class="col-md-4">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control text-right datetimepicker" id="fdt_lpbpurchase_datetime" name="fdt_lpbpurchase_datetime" value=""/>
								</div>
								<div id="fdt_lpbpurchase_datetime_err" class="text-danger"></div>
								<!-- /.input group -->
							</div>

						</div>
					
						<div class="form-group">
							<label for="fin_po_id" class="col-md-2 control-label"><?=lang("PO Number")?></label>
							<div class="col-md-5">
								<select id="fin_po_id" class="form-control non-editable" name="fin_po_id">
									<?php
										$poList = $this->trlpbpurchase_model->getPOList();
										foreach($poList as $po){
											echo "<option value='$po->fin_po_id'>$po->fst_po_no - $po->fst_supplier_name </option>";
										}
									?>
								</select>
								<div id="fin_po_id_err" class="text-danger"></div>
							</div>
							<label for="fin_po_id" class="col-md-1 control-label"><?=lang("PO Date")?></label>
							<div class="col-md-4">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control text-right datetimepicker" id="fdt_po_datetime"  value="" readonly/>
								</div>
								<!-- /.input group -->
							</div>
						</div>

						<div class="form-group">
                            <label for="fin_vendor_id" class="col-md-2 control-label"><?=lang("Supplier")?></label>							
                            <div class="col-sm-10">
                                <input type='TEXT' class="form-control" id="fst_supplier_name" readonly />
                            </div>                        
                        </div>
						
						<div class="form-group">
                            <label for="fin_lpbgudang_id" class="col-md-2 control-label"><?=lang("LPB Gudang")?></label>							
                            <div class="col-sm-10">
								<select id="fin_lpbgudang_id" class="form-control" name="fin_lpbgudang_id[]" multiple="multiple" style="width:100%"></select>                                
							</div>							                    
						</div>
						<div class="form-group">
                            <label class="col-md-2 control-label"><?=lang("Term")?></label>
                            <div class="col-md-1">
								<input id="fin_term" type="TEXT" name="fin_term" class="form-control" />
							</div>	                    
							<label class="col-md-1 control-label" style="text-align:left;padding-left:0px"><?=lang("Hari")?> </label>
						</div>
						<div class="form-group">
							<label for="fst_curr_code" class="col-md-2 control-label"><?=lang("Mata Uang")?></label>
                            <div class="col-sm-2">
								<select id="fst_curr_code" class="form-control" style="width:100%" disabled>
									<?php
										$currList = $this->mscurrencies_model->getCurrencyList();

										foreach ($currList as $curr){
											$selected = $curr->fbl_is_default == 1 ? "selected" : "";
											echo "<option value='$curr->fst_curr_code' data-rate='$curr->fdc_exchange_rate_to_idr' $selected >$curr->fst_curr_code</option>";
										}
									?>
								</select>								
							</div>										                    
							<label for="fdc_exchange_rate_idr" class="col-md-2 control-label"><?=lang("Nilai Tukar IDR")?></label>
                            <div class="col-sm-2">
								<input type="TEXT" id="fdc_exchange_rate_idr"  name="fdc_exchange_rate_idr" class="form-control money"/>
								<div id="fdc_exchange_rate_idr_err" class="text-danger"></div>

							</div>										                    
							
						</div>

						


						<div class="form-group">
							<div class="col-sm-12">
								<table id="tbldetails" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
								<div class="text-info">
									<label class="colcontrol-label"  style="padding-top:0px"><?=lang("Sisa DP")?> : 
										<label id="ttlRemainingDP" class="control-label" style="padding-top:0px" >0.00</label>
									</label>
									<span> | </span>

									<label class="colcontrol-label"  style="padding-top:0px"><?=lang("Return")?> : 
										<label id="ttlReturn" class="control-label" style="padding-top:0px" >0.00</label>
									</label>
									
								</div>
							</div>
						</div>

                        <div class="form-group">                            							
                            <div class="col-sm-6">
								<label for="fin_vendor_id" class=""><?=lang("Memo")?> </label>
                                <textarea class="form-control" id="fst_memo" placeholder="<?= lang("Memo") ?>" name="fst_memo" rows="3" style="resize:none;width:100%"></textarea>
                                <div id="fst_memo_err" class="text-danger"></div>
							</div> 
							<div class="col-sm-6" style="padding-right:0px">
								<label class="col-md-8 control-label" style="padding-top:0px"><?=lang("Sub Total")?> : </label>
								<label id="ttlSubTotal" class="col-md-4 control-label" style="padding-top:0px">0.00</label>
								
								<label class="col-md-8 control-label" style="padding-top:0px"><?=lang("Total Disc")?> : </label>
								<label id="ttlDisc" class="col-md-4 control-label" style="padding-top:0px">0.00</label>
								
								<label class="col-md-8 control-label"  style="padding-top:0px"><?=lang("Ppn")?> <label id="ppnPercent" class="control-label"  style="padding-top:0px">0</label>% : </label>
								<label id="ppnAmount" class="col-md-4 control-label"  style="padding-top:0px">0.00</label>

								<label class="col-md-8 control-label"  style="padding-top:0px"><?=lang("Total")?> : </label>
								<label id="ttlAmount" class="col-md-4 control-label" style="padding-top:0px" >0.00</label>

								
								
								<label class="col-md-8 control-label"  style=""><?=lang("Klaim DP")?> : </label>
								<div class="col-md-4">
									<input type="TEXT" class="form-control money" id="fdc_downpayment_claim" name="fdc_downpayment_claim" value="0.00"/>
									<div id="fdc_downpayment_claim_err" class="text-danger"></div>
								</div>
								

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

<?php echo $mdlEditForm ?>
<?php echo $mdlJurnal ?>
<?php echo $mdlPrint ?>

<script type="text/javascript" info="event">
	$(function(){
		
		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url()?>tr/purchase/invoice/add";
		});
		$("#btnSubmitAjax").click(function(e){
			e.preventDefault();
			submitAjax(0);
		});
		$("#btnPrint").click(function(e){
			//window.open("<=site_url()?>tr/purchase/invoice/print_voucher/" + $("#fin_lpbpurchase_id").val(),"_blank","width=600,menubar=0,toolbar=0");
			frameVoucher.print("<?=site_url()?>tr/purchase/invoice/print_voucher/" + $("#fin_lpbpurchase_id").val());
		});
		$("#btnJurnal").click(function(e){
			e.preventDefault();
			MdlJurnal.showJurnalByRef("PINV",$("#fin_lpbpurchase_id").val());
		});
		
		$("#btnDelete").click(function(e){
			e.preventDefault();
			deleteAjax(0);
		});
		
		$("#btnClose").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url()?>tr/purchase/invoice/";
		});
		$("#fin_po_id").change(function(e){
			e.preventDefault();
			getPOInfo($("#fin_po_id").val(),function(resp){
				header = resp.po;
				$("#fdt_po_datetime").val(dateTimeFormat(header.fdt_po_datetime)).datetimepicker("update");
				$("#fst_supplier_name").val(header.fst_supplier_name);
				listLPBGudang = resp.lpbgudang_list;
				
				$("#fin_lpbgudang_id").empty();
				$.each(listLPBGudang,function(i,lpbGudang){					
					App.addOptionIfNotExist("<option value='"+ lpbGudang.fin_lpbgudang_id +"'>"+lpbGudang.fst_lpbgudang_no+"</option>","fin_lpbgudang_id");
				});
				$("#ppnPercent").text(header.fdc_ppn_percent);
				$("#fin_term").val(header.fin_term);
				$("#fst_curr_code").val(header.fst_curr_code).trigger("change");
				

				var claimDP = parseFloat(header.fdc_downpayment_paid) - parseFloat(header.fdc_downpayment_claimed);
				$("#ttlRemainingDP").text(App.money_format(claimDP));
				$("#fdc_downpayment_claim").val(App.money_format(claimDP));
				calculateTotal();
			});
		});

		$("#fst_curr_code").change(function(e){
			e.preventDefault();
			var exchangeRate = $("#fst_curr_code option:selected").data("rate");
			$("#fdc_exchange_rate_idr").val(App.money_format(exchangeRate));
		});

		$("#fin_lpbgudang_id").change(function(e){
			e.preventDefault();
			var arrLPBGudangId = $("#fin_lpbgudang_id").val();
			getDetailLPBGudang(arrLPBGudangId,function(resp){
			});
		});
		$("#btn-save-detail").click(function(e){
			e.preventDefault();
			t = $("#tbldetails").DataTable();
			var data = t.row(rowDetail).data();
			console.log(data);
			data.fdb_qty = $("#fdbQty").val();
			data.fdc_m3 = $("#fdcM3").val();
			t.row(rowDetail).data(data).draw(false);
			calculateTotal();
			$("#mdlDetail").modal("hide");
		})
	
	});
</script>

<script type="text/javascript" info="define">
	var rowDetail;	
	$(function(){
		$("#fin_po_id").select2({});
		$("#fin_lpbgudang_id").select2({});
		App.fixedSelect2();
		$('#tbldetails').on('preXhr.dt', function ( e, settings, data ) {
			//add aditional data post on ajax call
			data.sessionId = "TEST SESSION ID";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "Item id","width": "80px",sortable:false,data:"fin_item_id",visible:false},
				{"title" : "Item Code","width": "60px",sortable:false,data:"fst_item_code"},
				{"title" : "Item Name","width": "150px",sortable:false,data:"fst_custom_item_name"},
				{"title" : "Unit","width": "40px",sortable:false,data:"fst_unit"},
				{"title" : "Price","width": "60px",sortable:false,data:"fdc_price",className:'text-right',
					render:function(data,type,row){
						return App.money_format(data);
					}
				},
				{"title" : "Discount","width": "50px",sortable:false,data:"fst_disc_item",className:'text-center'},
				{"title" : "Discount Amount","width": "50px",sortable:false,className:'text-right',
					render:function(data,type,row){
						discAmount = App.calculateDisc((row.fdc_price * row.fdb_qty_total), row.fst_disc_item);
						return App.money_format(discAmount);
					}
				},
				{"title" : "Total Qty","width": "50px",sortable:false,data:"fdb_qty_total",className:'text-right'},
				{"title" : "Total","width": "80px",sortable:false,className:'text-right',
					render:function(data,type,row){
						var total = row.fdb_qty_total * row.fdc_price;
						var discAmount = App.calculateDisc((row.fdc_price * row.fdb_qty_total), row.fst_disc_item);
						return App.money_format(total - discAmount);
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
			t = $("#tbldetails").DataTable();
			var trRow = $(this).parents('tr');
			rowDetail = trRow;
			var data = t.row(trRow).data();
			$("#fstItem").text(data.fst_custom_item_name);
			$("#fstUnit").text(data.fst_unit);
			$("#fdbQty").val(data.fdb_qty);
			$("#fdcM3").val(data.fdc_m3);
			
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
	$(function(){
		$("#fdt_lpbpurchase_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");		
		$("#fin_po_id").val(null).trigger("change.select2");
		$("#fin_warehouse_id").val(null);
		initForm();
	});
</script>

<script type="text/javascript" info="function">
	function getPOInfo(finPOId,callback){
		App.getValueAjax({
			site_url:"<?=site_url()?>",
			model:"trlpbpurchase_model",
			func:"getPODetail",
			params:[finPOId],
			callback:callback
		});
	}
	function calculateTotal(){
		t= $('#tbldetails').DataTable();
		var datas = t.rows().data();
		var total = 0;
		var totalDisc = 0;
		
		$.each(datas,function(i,data){
			var subttl =  parseFloat(data.fdb_qty_total * data.fdc_price);
			var discAmount =  App.calculateDisc(subttl,data.fst_disc_item);
			total += subttl;
			totalDisc += discAmount;			
		});
		$("#ttlSubTotal").text(App.money_format(total));
		$("#ttlDisc").text(App.money_format(totalDisc));
		var ppnPercent = $("#ppnPercent").text();
		var ttlBeforePPn = total - totalDisc
		var ppnAmount = ttlBeforePPn * ppnPercent / 100;
		var totalAfterPPn = ttlBeforePPn + ppnAmount;
		$("#ppnAmount").text(App.money_format(ppnAmount));
		$("#ttlAmount").text(App.money_format(totalAfterPPn));
	}
	function getDetailLPBGudang(arrLPBGudangId,callback){
		//params = JSON.stringify(arrLPBGudangId);
		if (arrLPBGudangId.length <= 0){
			t = $("#tbldetails").DataTable();
			t.rows().remove();
			t.draw(false);
			return;
		}
		params = [arrLPBGudangId];
		App.getValueAjax({			
			site_url:"<?= site_url()?>",
			model:"trlpbpurchase_model",
			func:"getListItemByLPBGudangIds",
			params:params,
			callback:function(resp){
				details = resp;				
				t = $("#tbldetails").DataTable();
				t.rows().remove();
				$.each(details,function(i,detail){
					t.row.add(detail);
				});
				t.draw(false);	
				calculateTotal();	
			}
		});
	}
	function submitAjax(confirmEdit){
		var dataSubmit = $("#frmLPBPurchase").serializeArray();		
		var mode = $("#fin_lpbpurchase_id").val() == "0" ? "ADD" : "EDIT";	

		if (mode == "ADD"){
			url =  "<?= site_url() ?>tr/purchase/invoice/ajx_add_save/";

		}else{
			dataSubmit.push({
				name : "fin_user_id_request_by",
				value: MdlEditForm.user
			});
			dataSubmit.push({
				name : "fst_edit_notes",
				value: MdlEditForm.notes
			});
			url =  "<?= site_url() ?>tr/purchase/invoice/ajx_edit_save/";
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
					$("#fin_lpbpurchase_id").val(data.insert_id);
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
		
		var finLPBPurchaseId = $("#fin_lpbpurchase_id").val();
		if (finLPBPurchaseId != 0){
			//get data from server;
			$.ajax({
				url:"<?=site_url()?>tr/purchase/invoice/fetch_data/" + finLPBPurchaseId,
				method:"GET",								
			}).done(function(resp){
				if(resp.message != ""){
					alert(resp.message);
					//return;
				}
				if (resp.status == "SUCCESS"){				
					dataH = resp.data.lpbPurchase;
					dataDetails = resp.data.lpbPurchaseDetails;
					dataItems = resp.data.lpbPurchaseItems;

					App.autoFillForm(dataH);
					
					$("#fdt_lpbpurchase_datetime").val(App.dateTimeFormat(dataH.fdt_lpbpurchase_datetime)).datetimepicker("update");
					
					App.addOptionIfNotExist("<option value='"+ dataH.fin_po_id +"' selected>" + dataH.fst_po_no +" - "+ dataH.fst_supplier_name +"</option>","fin_po_id");
					$("#fin_po_id").trigger("change.select2");

					$("#fdt_po_datetime").val(App.dateTimeFormat(dataH.fdt_po_datetime)).datetimepicker("update");
					$("#fst_supplier_name").val(dataH.fst_supplier_name);
					
					var finLPBGudangIds = new Array();
					$.each(dataDetails,function(i,value){
						finLPBGudangIds.push(value.fin_lpbgudang_id);
						App.addOptionIfNotExist("<option value='"+ value.fin_lpbgudang_id +"' selected>" + value.fst_lpbgudang_no +"</option>","fin_lpbgudang_id");
					});

					$("#fin_lpbgudang_id").val(finLPBGudangIds).trigger("change");
					$("#fin_term").val(dataH.fin_term);
					$("#ttlRemainingDP").text( App.money_format(parseFloat(dataH.fdc_downpayment_paid) - parseFloat(dataH.fdc_downpayment_claimed)));
					$("#ttlReturn").text( App.money_format(parseFloat(dataH.fdc_total_return)));

					$("#fst_curr_code").val(dataH.fst_curr_code);

					$("#ppnPercent").text(dataH.fdc_ppn_percent);
					//$("#ppnAmount").text();
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
		var url =  "<?= site_url() ?>tr/purchase/invoice/delete/" + $("#fin_lpbpurchase_id").val();
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