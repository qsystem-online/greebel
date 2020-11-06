
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
	<h1><?=lang("Gudang - Penerimaan Retur")?><small><?=lang("form")?></small></h1>
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
						<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
						<a id="btnClose" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
					</div>
				</div>
				<!-- end box header -->

				<!-- form start -->
				<form id="form-header" class="form-horizontal" action="" method="POST">
					<div class="box-body">
						<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">	
						<input type="hidden" class="form-control" id="fin_lpbsalesreturn_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_lpbsalesreturn_id" value="<?=$fin_lpbsalesreturn_id?>" readonly>

                        <div class="form-group">
							<label for="fst_lpbsalesreturn_no" class="col-md-2 control-label"><?=lang("No. Penerimaan Retur")?></label>	
							<div class="col-md-4">				
								<input type="TEXT" id="fst_lpbsalesreturn_no" name="fst_lpbsalesreturn_no" class="form-control"  value="<?=$fst_lpbsalesreturn_no?>" placeholder="PREFIX/BRANCH/YEAR/MONTH/99999" /> 
							</div>

							<label for="fdt_lpbsalesreturn_datetime" class="col-md-2 control-label text-right"><?=lang("Tanggal Penerimaan")?> *</label>
							<div class="col-md-4">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control text-right datetimepicker" id="fdt_lpbsalesreturn_datetime" name="fdt_lpbsalesreturn_datetime" value=""/>
								</div>
								<div id="fdt_lpbsalesreturn_datetime_err" class="text-danger"></div>
								<!-- /.input group -->
							</div>

						</div>

						<div class="form-group hide">
                            <label for="fst_curr_code" class="col-md-2 control-label"><?=lang("Mata Uang")?> </label>							
                            <div class="col-sm-10">
                                <select id="fst_curr_code" class="form-control" name="fst_curr_code" style="width:100%;">
									<?php
										$defCurr = getDefaultCurrency();										
									?>
									<option value="<?=$defCurr["CurrCode"]?>" selected><?=$defCurr["CurrCode"]?></option>
								</select>
							</div>							                    
						</div>

						<div class="form-group">
							<label for="fin_customer_id" class="col-md-2 control-label"><?=lang("Customer")?> </label>
							<div class="col-md-10">
								<select id="fin_customer_id" class="form-control" name="fin_customer_id" style="width:100%"></select>
								<div id="fin_customer_id_err" class="text-danger"></div>
							</div>							
						</div>

						<div class="form-group">
                            <label for="fin_vendor_id" class="col-md-2 control-label"><?=lang("Gudang")?> </label>							
                            <div class="col-sm-10">
                                <select id="fin_warehouse_id" class="form-control" name="fin_warehouse_id" style="width:100%">
								</select>                                
							</div>							                    
						</div>						

						<div class="form-group" style="margin-bottom:0px">
							<div class="col-md-12" style="text-align:right">
								<button id="btn-add-items" class="btn btn-primary btn-sm non-assembling"><i class="fa fa-cart-plus" aria-hidden="true"></i>&nbsp;&nbsp;Tambah Item</button>
							</div>
						</div>

						<div class="form-group">							
							<div class="col-sm-12">
								<table id="tbldetails" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
							</div>
						</div>

                        <div class="form-group">
                            							
                            <div class="col-sm-6">
								<label for="fin_vendor_id" class=""><?=lang("Memo")?></label>
                                <textarea class="form-control" id="fst_memo" placeholder="<?= lang("Memo") ?>" name="fst_memo" rows="3" style="resize:none;width:100%"></textarea>
                                <div id="fst_memo_err" class="text-danger"></div>
							</div> 
							<label class="col-md-4 control-label"><?=lang("Total Qty")?> : </label>
							<label id="ttlQty" class="col-md-2 control-label">0.00</label>
							
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

<!-- modal atau popup "ADD" -->
<div id="mdlDetail" class="modal fade in" role="dialog" style="display: none">
	<div class="modal-dialog" style="display:table;width:600px">
		<!-- modal content -->
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
			<div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Add ")?></h4>
			</div>

			<div class="modal-body">
				<div class="row">
                    <div class="col-md-12" >
                        <div style="border:1px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">
								<form class="form-horizontal">
									<div class="form-group">
										<label class="col-md-12 control-label"><?=lang("Filter Paid Invoice")?> 
											<input type="checkbox" id="dPaidInv" unchecked />
										</label>
										
									</div>

									<div class="form-group">
										<label class="col-md-3 control-label"><?=lang("Invoice No")?> :</label>
										<div class="col-md-9">											
											<select id="dfin_inv_id" class="form-control" style="width:100%"> </select>
										</div>										
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label"><?=lang("Item")?> :</label>
										<div class="col-md-9">											
											<select id="dfin_item_id" class="form-control" style="width:100%"> </select>
										</div>										
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label"><?=lang("Unit")?> :</label>	
										<div class="col-md-9">									
											<select id="dfst_unit" class="form-control" style="width:100%"> </select>
										</div>
										
									</div>

									<div class="form-group">
										<label for="fdbQty" class="col-md-3 control-label"><?=lang("Qty")?> :</label>
										<div class="col-md-9">
											<input type='TEXT' id="dfdb_qty" class="money form-control" value="1"/>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label"><?=lang("Basic Unit")?> :</label>
										<div class="col-md-3">
											<input type='TEXT' id="dfst_basic_unit" class="form-control" readonly/>
										</div>
										<label class="col-md-3 control-label"><?=lang("Basic Unit Qty")?> :</label>
										<div class="col-md-3">
											<input type='TEXT' id="dfdb_qty_basic" class="money form-control" value="1" readonly/>
										</div>
									</div>

									<div class="form-group batchNoBlock">
										<label for="" class="col-md-3 control-label"><?=lang("Batch Number")?> :</label>
										<div class="col-md-9">
											<input type='TEXT' id="fstBatchNo" class="form-control" />
										</div>
									</div>

									<div class="form-group serialNoBlock">
										<label for="" class="col-md-3 control-label"><?=lang("Serial Number")?> :</label>
										<div class="col-md-9">
											<input type='TEXT' id="fstSerialNo" class="form-control" />
										</div>										
									</div>
									<div class="form-group serialNoBlock" >
										<label for="" class="col-md-3 control-label"></label>
										<div class="col-md-9">
											<select multiple="multiple" id="fstSerialNoList" class="form-control"></select>
										</div>
									</div>
									<div class="form-group serialNoBlock">
										<label for="" class="col-md-3 control-label"></label>
										<div class="col-md-4" >
											<label for="" class=""><?=lang("Total Serial")?> :</label>
											<label id="ttlSerial" class="">0</label>

										</div>
										<div class="col-md-5 text-right" >
											<button id="btn-delete-serial" class="btn btn-primary btn-xs">Delete Selected Serial</button>
										</div>
									</div>

								</form>
								
								<div class="modal-footer">
									<button id="btn-save-detail" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Add")?></button>
									<button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" info="define">
		mdlDetail = {
			selectedInv:null,
			selectedItem:null,
			selectedUnit:null,
		
			show:function(){
				if ($("#fin_customer_id").val() == null){
					alert("<?=lang("Customer harus diisi !")?>");
					return;
				}

				if (selectedRow != null){
					var data = selectedRow.data();
					
					if (data.fin_inv_id == null){
						$("#dfin_inv_id").empty().trigger("change");
					}else{
						App.addOptionIfNotExist("<option value='"+data.fin_inv_id+"'>"+data.fst_inv_no+"</option>","dfin_inv_id");
					}

					App.addOptionIfNotExist("<option value='"+data.fin_item_id+"'>"+data.fst_item_code + " - "+ data.fst_item_name+"</option>","dfin_item_id");
					mdlDetail.selectedItem = {
						id:data.fin_item_id,
						text:data.fst_item_code + " - " + data.fst_item_name,
						fst_item_code:data.fst_item_code,
						fst_item_name:data.fst_item_name,
						fbl_is_batch_number:data.fbl_is_batch_number,
						fbl_is_serial_number:data.fbl_is_serial_number,
						fst_basic_unit:data.fst_basic_unit
					};
					$("#dfin_item_id").trigger({
						type:"select2:select",
						params:{
							data:mdlDetail.selectedItem
						}
					});

					App.addOptionIfNotExist("<option value='"+data.fst_unit+"'>"+data.fst_unit +"</option>","dfst_unit");
					mdlDetail.selectedUnit = {
						id:data.fst_unit,
						text:data.fst_unit,
						fdc_conv_to_basic_unit:data.fdc_conv_to_basic_unit
					};
					$("#dfst_unit").trigger({
						type:"select2:select",
						params:{
							data:mdlDetail.selectedUnit
						}
					});


					$("#dfdb_qty").val(data.fdb_qty).trigger("change");

					
					$("#fstBatchNo").val(data.fst_batch_number);
					$("#fstSerialNoList").empty();
					$.each(data.fst_serial_number_list,function(i,serial){
						$("#fstSerialNoList").prepend("<option value='"+serial+"'>"+serial+"</option>");
					});
				}else{

				}				
				$("#mdlDetail").modal("show");


			},

			hide:function(){
				$("#mdlDetail").modal("hide");
			},
			clear:function(){
				mdlDetail.selectedItem = null;
				mdlDetail.selectedUnit = null;
				selectedRow = null;
				$(".batchNoBlock").hide();
				$(".serialNoBlock").hide();
			},			
		};
	</script>

	<script type="text/javascript" info="init">
		$(function(){
			$("#dfin_inv_id").select2({
				placeholder:"Select Invoice...",
				allowClear: true,
				ajax:{
					url:"<?=site_url()?>tr/gudang/penerimaan_return/ajxGetInvoiceList",
					dataType: 'json',
					delay: 250,
					data:function(params){
						params.fin_customer_id = $("#fin_customer_id").val();
						params.fbl_lunas = $("#dPaidInv").prop("checked");
						params.fst_curr_code = $("#fst_curr_code").val();
						return params;
					},
					processResults: function(resp) {
						if (resp.status == "SUCCESS"){
							var data = resp.data;
							var result = [];
							$.each(data, function(index, value) {
								result.push({
									"id": value.fin_inv_id,
									"text": value.fst_inv_no,
								});
							});
							return {
								results: result
							};
						}else{
							alert(resp.messages);
						}
					},
				},
			}).on("change",function(e){
				$("#dfin_item_id").val(null).trigger("change");
				mdlDetail.selectedItem = null;
				mdlDetail.selectedUnit = null;				
			});

			$("#dfin_item_id").select2({
				ajax:{
					url:"<?=site_url()?>tr/gudang/penerimaan_return/ajxGetItemList",
					dataType: 'json',
					delay: 250,
					data:function(params){
						params.fin_inv_id = $("#dfin_inv_id").val();
						return params;
					},
					processResults: function(resp) {
						if (resp.status == "SUCCESS"){
							var data = resp.data;
							var result = [];
							$.each(data, function(index, value) {
								result.push({
									id: value.fin_item_id,
									text: value.fst_item_code + " - " + value.fst_item_name,
									fst_item_code :value.fst_item_code,
									fst_item_name:value.fst_item_name,
									fst_basic_unit:value.fst_basic_unit,
									fbl_is_batch_number:value.fbl_is_batch_number,
									fbl_is_serial_number:value.fbl_is_serial_number
								});
							});
							return {
								results: result
							};
						}else{
							alert(resp.messages);
						}
					},
				},
			}).on("select2:select",function(e){
				mdlDetail.selectedItem = e.params.data;
				$("#dfst_basic_unit").val(mdlDetail.selectedItem.fst_basic_unit);
				
				if (mdlDetail.selectedItem.fbl_is_batch_number == 1){
					$(".batchNoBlock").show();
				}else{
					$(".batchNoBlock").hide();
				}

				if (mdlDetail.selectedItem.fbl_is_serial_number == 1){				
					$(".serialNoBlock").show();
				}else{				
					$(".serialNoBlock").hide();
				}


			});

			$("#dfst_unit").select2({
				minimumResultsForSearch: -1,
				ajax:{
					url:"<?=site_url()?>tr/gudang/penerimaan_return/ajxGetItemUnits",
					dataType: 'json',
					delay: 250,
					data:function(params){
						params.fin_inv_id = $("#dfin_inv_id").val();
						params.fin_item_id = $("#dfin_item_id").val();
						return params;
					},
					processResults: function(resp) {
						if (resp.status == "SUCCESS"){
							var data = resp.data;
							var result = [];
							$.each(data, function(index, value) {
								result.push({
									"id": value.fst_unit,
									"text": value.fst_unit,
									fdc_conv_to_basic_unit:value.fdc_conv_to_basic_unit
								});
							});
							return {
								results: result
							};
						}else{
							alert(resp.messages);
						}
					},
				},
			}).on("select2:select",function(e){
				mdlDetail.selectedUnit = e.params.data;
			});		
		});

	</script>

	<script type="text/javascript" info="event">
		

		$(function(){
			$("#dfdb_qty").change(function(e){
				var basicQty = parseFloat($("#dfdb_qty").val()) * parseFloat(mdlDetail.selectedUnit.fdc_conv_to_basic_unit);
				$("#dfdb_qty_basic").val(basicQty);
			});

			$("#fstSerialNo").keydown(function(e){
				if (e.keyCode == 13){
					e.preventDefault();
					if (checkDuplicateSerialNo($("#fstSerialNo").val())){
						alert("<?=lang("Serial no duplikat !")?>");
						$("#fstSerialNo").select();
						return;
					}
					$("#fstSerialNoList").prepend("<option value='"+$("#fstSerialNo").val()+"'>"+$("#fstSerialNo").val()+"</option>");
					$("#fstSerialNo").val("");
					calculateTotalSerialNo();
				}
			});

			$("#btn-delete-serial").click(function(e){
				e.preventDefault();
				$("#fstSerialNoList option:selected").each(function () {
					$(this).remove(); //or whatever else
				});
				calculateTotalSerialNo();
			});
			
			$("#btn-save-detail").click(function(e){
				e.preventDefault();		
				
				if (mdlDetail.selectedItem.fbl_is_batch_number == 1){
					if ($("#fstBatchNo").val() == "" ){
						alert("Batch Number harus diisi !");
						return;
					}
				}

				if (mdlDetail.selectedItem.fbl_is_serial_number == 1){
					var qtyInBasicUnit = parseFloat($("#dfdb_qty_basic").val());
					if ($("#fstSerialNoList option").length != qtyInBasicUnit){
						alert("<?= lang("Total Serial Number harus sesuai dengan qty dalam basic unit")?>("+ qtyInBasicUnit +")");
						return;
					}
				}

				if ($("#fdbQty").val() <= 0 ){
					alert("<?=lang("Qty harus diisi !") ?>");
					return;
				}


				var data ={fin_rec_id:0};

				if (selectedRow !=  null){
					data = selectedRow.data();
				}

				data.fin_inv_id = $("#dfin_inv_id").val();
				data.fst_inv_no = $("#dfin_inv_id option:selected").text();
				data.fin_item_id = mdlDetail.selectedItem.id;
				data.fst_item_code =mdlDetail.selectedItem.fst_item_code;
				data.fst_item_name = mdlDetail.selectedItem.fst_item_name;
				data.fbl_is_batch_number = mdlDetail.selectedItem.fbl_is_batch_number;
				data.fbl_is_serial_number = mdlDetail.selectedItem.fbl_is_serial_number;
				data.fst_basic_unit = mdlDetail.selectedItem.fst_basic_unit;
				data.fst_unit = mdlDetail.selectedUnit.id;
				data.fdc_conv_to_basic_unit = mdlDetail.selectedUnit.fdc_conv_to_basic_unit;
				data.fdb_qty = parseFloat($("#dfdb_qty").val());
				data.fst_batch_number = $("#fstBatchNo").val();
				var arrSerial = [];
				$.each($("#fstSerialNoList option"),function(i,serial){
					arrSerial.push($(serial).val());
				});
				data.fst_serial_number_list = arrSerial;
				

				if (selectedRow !=  null){
					data = selectedRow.data();
				}

				var t = tbldetails;
				if (selectedRow == null){
					t.row.add(data).draw(false);
				}else{
					selectedRow.data(data).draw(false);
				}
				mdlDetail.clear();
				mdlDetail.hide();
			});

		});
	</script>

	<script type="text/javascript" info="function">
		function checkDuplicateSerialNo(serialNo){
			isDuplicate = false;
			$.each ($("#fstSerialNoList option"),function(i,v){
				if ($(v).val() == serialNo){
					isDuplicate = true;
					return false;
				};			
			});
			return isDuplicate;
		}
		function calculateTotalSerialNo(){
			$("#ttlSerial").text($("#fstSerialNoList option").length);

		}



	</script>

</div>

<?php echo $mdlEditForm ?>
<?php echo $mdlPrint ?>



<script type="text/javascript" info="define">	
	var tbldetails;
	var selectedRow = null;
	var mode = "<?=$mode?>";
</script>

<script type="text/javascript" info="init">	
	$(function(){
		$("#fdt_lpbsalesreturn_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");
		
		
		$("#fin_customer_id").select2({
			ajax:{
				url:"<?=site_url()?>pr/relation/ajxGetCustomerList",
				dataType: 'json',
                delay: 250,
                processResults: function(resp) {
					if (resp.status == "SUCCESS"){
						var data = resp.data;
						var result = [];
						$.each(data, function(index, value) {
							result.push({
								"id": value.fin_relation_id,
								"text": value.fst_relation_name,
							});
						});
						return {
							results: result
						};
					}else{
						alert(resp.messages);
					}
                },
			},
		});

		$("#fin_warehouse_id").select2({
			minimumResultsForSearch: -1,
			ajax:{
				url:"<?=site_url()?>master/warehouse/ajxGetWarehouseList",
				dataType: 'json',
                delay: 250,
                processResults: function(resp) {
					if (resp.status == "SUCCESS"){
						var data = resp.data;
						var result = [];
						$.each(data, function(index, value) {
							result.push({
								"id": value.fin_warehouse_id,
								"text": value.fst_warehouse_name,
							});
						});
						return {
							results: result
						};
					}else{
						alert(resp.messages);
					}
                },
			},
		});

		$("#fin_warehouse_id").val(null);

		App.fixedSelect2();

		tbldetails = $('#tbldetails').on('preXhr.dt', function ( e, settings, data ) {
			//add aditional data post on ajax call
			data.sessionId = "TEST SESSION ID";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "id","width": "50px",sortable:false,data:"fin_rec_id",visible:false},
				{"title" : "Invoice No.","width": "100px",sortable:false,data:"fin_inv_id",
					render:function(data,type,row){
						console.log(row);
						return row.fst_inv_no;
					},
					visible:true
				},
				{"title" : "Item","width": "250px",sortable:false,data:"fin_item_id",
					render:function(data,type,row){
						return row.fst_item_code + " - " + row.fst_item_name; 
					}
				},
				{"title" : "Unit","width": "100px",sortable:false,data:"fst_unit"},
				{"title" : "Qty","width": "50px",sortable:false,data:"fdb_qty",className:'text-right'},
				{"title" : "Action","width": "40px",sortable:false,className:'dt-body-center text-center',
					render: function(data,type,row){
						var action = '<a class="btn-edit" href="#" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp;';												
						action += '<a class="btn-delete non-assembling" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
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
			calculateTotalQty();

		}).on('click','.btn-edit',function(e){
			e.preventDefault();
			
			t = $("#tbldetails").DataTable();
			var trRow = $(this).parents('tr');
			selectedRow = t.row(trRow);
			mdlDetail.show();					
		}).on('click','.btn-delete',function(e){
			e.preventDefault();
			t = $('#tbldetails').DataTable();
			var trRow = $(this).parents('tr');
			t.row(trRow).remove().draw();
			calculateTotalQty();


		});
		initForm();
	});
</script>

<script type="text/javascript" info="event">
	$(function(){
		
		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url()?>tr/gudang/penerimaan_return/add";
		});

		$("#btnPrint").click(function(e){
			e.preventDefault();
			frameVoucher.print("<?=site_url()?>tr/gudang/penerimaan_pembelian/print_voucher/" + $("#fin_lpbgudang_id").val());
		});


		$("#btnSubmitAjax").click(function(e){
			e.preventDefault();
			submitAjax(0);
		});
		
		$("#btnDelete").click(function(e){
			e.preventDefault();
			deleteAjax(0);
		});
		
		$("#btnClose").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url()?>tr/gudang/penerimaan_return/";
		});


		$("#btn-add-items").click(function(e){
			e.preventDefault();
			mdlDetail.clear();
			mdlDetail.show();			
		});


		

	});
</script>

<script type="text/javascript" info="function">
	
	function getDetailTransaction(callback){
		var lpbType =$("#fst_lpb_type").val();
		var transId =$("#fin_trans_id").val();
		
		App.getValueAjax({
			site_url:"<?=site_url()?>",
			model:"trlpbgudang_model",
			func:"getTransDetail",
			params:[lpbType,transId],
			callback:function(resp){
				
				var dataDetails = resp;
				arrDetails = [];
				arrDetails = dataDetails.map(function(dataD){
					dataD.fin_rec_id = 0;
					dataD.fdb_qty = dataD.fdb_qty_trans - dataD.fdb_qty_lpb;
					dataD.fdc_m3 = 1;
					dataD.fst_batch_no="";
					dataD.arr_serial=[];

					/*
					return {
						fin_trans_detail_id: "87"
						fdb_qty_trans: "50.00"
						fst_unit: "SET"
						fdb_qty_lpb: "5.00"
						fst_item_code: "040101000009"
						fst_item_name: "GREEBEL 3736 PENCIL SUPER LEAD"
						fbl_is_batch_number: "1"
						fbl_is_serial_number: "0"
						fdc_conv_to_basic_unit: "1.00"
						fst_basic_unit: "SET"
					};
					*/
					return dataD;
				});
				
				var t = $("#tbldetails").DataTable();
				t.clear();
				t.rows.add(arrDetails).draw(false);
				mdlDetail.setDataItems(arrDetails);
			}
		});
	}

	
	function calculateTotalQty(){
		t= $('#tbldetails').DataTable();
		var datas = t.rows().data();
		var totalQty =0;
		$.each(datas,function(i,data){
			totalQty += parseFloat(data.fdb_qty);
		});
		$("#ttlQty").text(App.money_format(totalQty));
	}

	function submitAjax(confirmEdit){
		var dataSubmit = $("#form-header").serializeArray();
		var dataDetails = new Array();	
		var datas = tbldetails.data();
		var isValidData =true;

		if (confirmEdit == 0 && mode != "ADD"){
			MdlEditForm.saveCallBack = function(){
				submitAjax(1);
			};		
			MdlEditForm.show();
			return;
		}

		$.each(datas,function(i,v){
			console.log(v);
			if ((v.fbl_is_batch_number == "1") && (v.fst_batch_number == null || v.fst_batch_number =="")){
				isValidData = false;
				alert ("Batch number " + v.fst_item_name  + " tidak boleh kosong !");
				return false;
			}
			if ((v.fbl_is_serial_number == 1) && (v.fst_serial_number_list == null || v.fst_serial_number_list =="")){
				isValidData = false;
				alert ("Serial number " + v.fst_item_name  + " tidak boleh kosong !");
				return false;
			}			
			dataDetails.push(v);
		});

		if (isValidData == false){				
			return;
		}

		dataSubmit.push({
			name:"details",
			value: JSON.stringify(dataDetails)
		});

		if (mode == "ADD"){
			url =  "<?= site_url() ?>tr/gudang/penerimaan_return/ajx_add_save/";
		}else{
			dataSubmit.push({
				name : "fin_user_id_request_by",
				value: MdlEditForm.user
			});
			dataSubmit.push({
				name : "fst_edit_notes",
				value: MdlEditForm.notes
			});

			url =  "<?= site_url() ?>tr/gudang/penerimaan_return/ajx_edit_save/";
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
					$("#fin_lpbgudang_id").val(data.insert_id);
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
		if (mode != "ADD"){
			//get data from server;
			App.blockUIOnAjaxRequest("Loading data, please wait..!");
			$.ajax({
				url:"<?=site_url()?>tr/gudang/penerimaan_return/fetch_data/" + $("#fin_lpbsalesreturn_id").val(),
				method:"GET",								
			}).done(function(resp){

				if(resp.message != ""){
					alert(resp.message);
				}

				if (resp.status == "SUCCESS"){				

					dataH = resp.data.header;
					details = resp.data.details;
					App.autoFillForm(dataH);

					$("#fdt_lpbsalesreturn_datetime").val(App.dateTimeFormat(dataH.fdt_lpbsalesreturn_datetime)).datetimepicker("update");
					
					App.addOptionIfNotExist("<option value='"+dataH.fin_customer_id+"'>"+dataH.fst_relation_name+"</option>","fin_customer_id");
					App.addOptionIfNotExist("<option value='"+dataH.fin_warehouse_id+"'>"+dataH.fst_warehouse_name+"</option>","fin_warehouse_id");
										
					tbldetails.rows().clear();
					$.each(details,function(i,v){
						var data = {
							fin_rec_id:v.fin_rec_id,
							fin_inv_id:v.fin_inv_id,
							fst_inv_no:v.fst_inv_no,
							fin_item_id:v.fin_item_id,
							fst_item_code:v.fst_item_code,
							fst_item_name:v.fst_item_name,
							fbl_is_batch_number:v.fbl_is_batch_number,
							fbl_is_serial_number:v.fbl_is_serial_number,
							fst_basic_unit:v.fst_basic_unit,
							fst_unit:v.fst_unit,
							fdb_qty:v.fdb_qty,
							fdc_conv_to_basic_unit:v.fdc_conv_to_basic_unit,
							fst_batch_number:v.fst_batch_number,
							fst_serial_number_list:JSON.parse(v.fst_serial_number_list)
						}

						tbldetails.row.add(data);
					});
					tbldetails.draw(false);
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

		var url =  "<?= site_url() ?>tr/gudang/penerimaan_return/delete/" + $("#fin_lpbsalesreturn_id").val();
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