
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
	<h1><?=lang("Gudang - Penerimaan Pembelian")?><small><?=lang("form")?></small></h1>
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
				<form id="frmLPBGudang" class="form-horizontal" action="<?=site_url()?>tr/sales_order/add" method="POST" enctype="multipart/form-data">			
					<div class="box-body">
						<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">	
						<input type="hidden" class="form-control" id="fin_lpbgudang_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_lpbgudang_id" value="<?=$fin_lpbgudang_id?>" readonly>



                        <div class="form-group">
							<label for="fst_lpbgudang_no" class="col-md-2 control-label"><?=lang("No. Penerimaan")?></label>	
							<div class="col-md-4">				
								<input type="TEXT" id="fst_lpbgudang_no" name="fst_lpbgudang_no" class="form-control"  value="<?=$fst_lpbgudang_no?>" placeholder="PREFIX/BRANCH/YEAR/MONTH/99999" /> 
							</div>

							<label for="fdt_lpbgudang_datetime" class="col-md-2 control-label text-right"><?=lang("Tanggal Penerimaan")?> *</label>
							<div class="col-md-4">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control text-right datetimepicker" id="fdt_lpbgudang_datetime" name="fdt_lpbgudang_datetime" value=""/>
								</div>
								<div id="fdt_lpbgudang_datetime_err" class="text-danger"></div>
								<!-- /.input group -->
							</div>

						</div>

						

						<div class="form-group">
							<label for="fin_po_id" class="col-md-2 control-label"><?=lang("PO Number")?> </label>
							<div class="col-md-4">
								<select id="fin_po_id" class="form-control non-editable" name="fin_po_id">
									<?php
										$poList = $this->trlpbgudang_model->getPOList();
										foreach($poList as $po){
											echo "<option value='$po->fin_po_id'>$po->fst_po_no</option>";
										}
									?>
								</select>
								<div id="fin_po_id" class="text-danger"></div>
							</div>
							<label for="fin_po_id" class="col-md-2 control-label"><?=lang("PO Date")?> </label>
							<div class="col-md-4">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control text-right datetimepicker" id="fdt_po_datetime"  value="" readonly/>
								</div>
								<div id="fdt_lpbgudang_datetime_err" class="text-danger"></div>
								<!-- /.input group -->
							</div>
						</div>

						<div class="form-group">
                            <label for="fin_vendor_id" class="col-md-2 control-label"><?=lang("Supplier")?> </label>							
                            <div class="col-sm-10">
                                <input type='TEXT' class="form-control" id="fst_supplier_name" readonly />
                            </div>                        
                        </div>
						
						<div class="form-group">
                            <label for="fin_vendor_id" class="col-md-2 control-label"><?=lang("Gudang")?> </label>							
                            <div class="col-sm-10">
                                <select id="fin_warehouse_id" class="form-control" name="fin_warehouse_id" style="width:100%">
									<?php
										//$warehouseList = getDataTable("mswarehouse","*","fin_branch_id = " . $this->aauth->get_active_branch_id() ." and fst_active ='A'");
										$warehouseList = $this->mswarehouse_model->getNonLogisticWarehouseList();
										foreach($warehouseList as $warehouse){
											echo "<option value='$warehouse->fin_warehouse_id'>$warehouse->fst_warehouse_name - $warehouse->fst_delivery_address </option>";
										}
									?>
								</select>                                
							</div>							                    
						</div>						

						<div class="form-group" style="margin-bottom:0px">
							<div class="col-md-12" style="text-align:right">
								<button id="btn-add-items" class="btn btn-primary btn-sm"><i class="fa fa-cart-plus" aria-hidden="true"></i>&nbsp;&nbsp;Tambah Item</button>
							</div>
						</div>

						<div class="form-group">							
							<div class="col-sm-12">
								<table id="tbldetails" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
							</div>
						</div>

                        <div class="form-group">
                            							
                            <div class="col-sm-6">
								<label for="fin_vendor_id" class=""><?=lang("Memo :")?> </label>
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
								<form id="form-detail" class="form-horizontal">
									<input type='hidden' id='fin_rec_id_items'/>
									<div class="form-group">
										<label class="col-md-3 control-label"><?=lang("Item")?> :</label>
										<div class="col-md-9">											
											<select id="fstItem" class="form-control" style="width:100%"> </select>
										</div>										
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label"><?=lang("Unit")?> :</label>	
										<div class="col-md-9">									
											<label id="fstUnit" class="control-label"><?=lang("Unit")?></label>
											<label class="control-label"> => </label>
											<label id="fdcConvBasicUnit" class="control-label"><?=lang("Unit")?></label>
											<label id="fstBasicUnit" class="control-label"><?=lang("Unit")?></label>
										</div>
										
									</div>

									<div class="form-group">
										<label for="fdbQty" class="col-md-3 control-label"><?=lang("Qty")?> :</label>
										<div class="col-md-9">
											<input type='TEXT' id="fdbQty" class="money form-control" value="1"/>
										</div>
									</div>

									<div class="form-group">
										<label for="" class="col-md-3 control-label"><?=lang("M3")?> :</label>
										<div class="col-md-9">
											<input type='TEXT' id="fdcM3" class="money form-control" value="0"/>
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

	<script type="text/javascript" info="event">
		$("#fstItem").change(function(e){
			e.preventDefault();
			//var data = $('#fstItem').find(':selected');
			var data = $('#fstItem').select2('data');
			data = data[0];	
			App.log(data);					
			$("#fstUnit").text(data.fst_unit);
			$("#fdcConvBasicUnit").text(data.fdc_conv_to_basic_unit);
			$("#fstBasicUnit").text(data.fst_basic_unit);

			if (data.fbl_is_batch_number == 1){
				$(".batchNoBlock").show();
			}else{
				$(".batchNoBlock").hide();
			}
			if (data.fbl_is_serial_number == 1){				
				$(".serialNoBlock").show();
			}else{				
				$(".serialNoBlock").hide();
			}
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
			

			item = $("#fstItem").select2("data");
			item = item[0];
			
			if ($("#fdbQty").val() <= 0 ){
				alert("<?=lang("Qty harus diisi !") ?>");
				return;
			}

			if ($("#fdcM3").val() <= 0 ){
				alert("<?=lang("Meter kubik (M3) harus diisi !") ?>");
				return;
			}
			
			if (item.fbl_is_batch_number == 1){
				if ($("#fstBatchNo").val() == "" ){
					alert("Batch Number harus diisi !");
					return;
				}
			}

			if (item.fbl_is_serial_number == 1){
				var convToBasic = parseFloat($("#fdcConvBasicUnit").text());
				var qtyInUnit = parseFloat($("#fdbQty").val());
				var qtyInBasicUnit = qtyInUnit * convToBasic;


				if ($("#fstSerialNoList option").length != qtyInBasicUnit){
					alert("<?= lang("Total Serial Number harus sesuai dengan qty dalam basic unit")?>("+ qtyInBasicUnit +")");
					return;
				}
			}
			
			var arrSerial = [];
			$.each($("#fstSerialNoList option"),function(i,serial){
				App.log(serial);
				arrSerial.push($(serial).val());
			});


			t = $("#tbldetails").DataTable();

			var data;
			if (rowDetail == null){
				item = $("#fstItem").select2("data");
				item = item[0];	

				data = {
					fin_rec_id: 0,					
					fin_po_detail_id: item.fin_po_detail_id,
					fin_item_id: item.id,
					fst_item_code: item.fst_item_code,
					fst_custom_item_name: item.text,
					fst_unit: item.fst_unit,
					fdb_qty_po: item.fdb_qty_po,
					fdb_qty_po_received:item.fdb_qty_po_received,
					fdb_qty: 0,
					fdc_m3: 0,
					fst_batch_no: "",
					arr_serial: [],
				};
			}else{
				data = t.row(rowDetail).data();				
			}

			data.fdb_qty = $("#fdbQty").val();
			data.fdc_m3 = $("#fdcM3").val();
			data.fst_batch_no = $("#fstBatchNo").val();
			data.arr_serial = arrSerial;
			

			if (rowDetail == null){
				App.log(data);
				t.row.add(data).draw(false);
			}else{
				t.row(rowDetail).data(data).draw(false);
			}
			
			calculateTotalQty();
			$("#mdlDetail").modal("hide");

		})

	</script>

</div>

<?php echo $mdlEditForm ?>



<script type="text/javascript" info="init">
	var rowDetail;
	var arrItems = new Array();

	$(function(){
		$("#fdt_lpbgudang_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");
		

		$("#fin_po_id").select2({});
		$("#fin_po_id").val(null).trigger("change.select2");
		$("#fin_warehouse_id").val(null);

		//$("#fstItem").select2();
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
				{"title" : "id","width": "50px",sortable:false,data:"fin_rec_id",visible:false},
				{"title" : "po_detail_id","width": "50px",sortable:false,data:"fin_po_detail_id",visible:false},
				{"title" : "Item id","width": "100px",sortable:false,data:"fin_item_id",visible:false},
				{"title" : "Item Code","width": "100px",sortable:false,data:"fst_item_code"},
				{"title" : "Item Name","width": "100px",sortable:false,data:"fst_custom_item_name"},
				{"title" : "Unit","width": "50px",sortable:false,data:"fst_unit"},
				{"title" : "Total Qty PO","width": "50px",sortable:false,data:"fdb_qty_po",className:'text-right'},
				{"title" : "Total Qty Received","width": "70px",sortable:false,data:"fdb_qty_po_received",className:'text-right'},
				{"title" : "Qty","width": "50px",sortable:false,data:"fdb_qty",className:'text-right'},
				{"title" : "M3","width": "50px",sortable:false,data:"fdc_m3",className:'text-right'},
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
			t = $("#tbldetails").DataTable();
			var trRow = $(this).parents('tr');
			rowDetail = trRow;
			var data = t.row(trRow).data();
			App.log(data);

			$("#fstItem").val(data.fin_item_id).trigger("change");	

			$("#fstUnit").text(data.fst_unit);
			$("#fdbQty").val(data.fdb_qty);
			$("#fdcM3").val(data.fdc_m3);
			$("#fstBatchNo").val(data.fst_batch_no);
			$("#fstSerialNoList").empty();
			$.each(data.arr_serial,function(i,serial){
				$("#fstSerialNoList").prepend("<option value='"+serial+"'>"+serial+"</option>");
			});

			$("#mdlDetail").modal("show");
			
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
			window.location.href = "<?=site_url()?>tr/gudang/penerimaan_pembelian/add";
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
			window.location.href = "<?=site_url()?>tr/gudang/penerimaan_pembelian/";
		});

		$("#fin_po_id").change(function(e){
			e.preventDefault();
			getPOInfo($("#fin_po_id").val(),function(resp){
				header = resp.po;
				$("#fdt_po_datetime").val(dateTimeFormat(header.fdt_po_datetime)).datetimepicker("update");
				$("#fst_supplier_name").val(header.fst_supplier_name);
				$("#fin_warehouse_id").val(header.fin_warehouse_id);

				details = resp.po_details;
				
				t= $('#tbldetails').DataTable();
				t.rows().remove();
				$("#fstItem").empty();
				
				arrItems =[];
				$.each(details,function(i,detail){
					var data = {
						fin_rec_id:0,
						fin_po_detail_id:detail.fin_po_detail_id,
						fin_item_id: detail.fin_item_id,
						fst_item_code:detail.fst_item_code,
						fst_custom_item_name:detail.fst_custom_item_name,
						fst_unit:detail.fst_unit,
						fdb_qty_po: detail.fdb_qty,
						fdb_qty_po_received: detail.fdb_qty_lpb,
						fdb_qty:detail.fdb_qty - detail.fdb_qty_lpb,
						fdc_m3: null,
						fst_batch_no: "",
						arr_serial: []
					}
					arrItems.push({
						fin_po_detail_id:detail.fin_po_detail_id,
						id: detail.fin_item_id,						
						fst_item_code: detail.fst_item_code,
						text:detail.fst_custom_item_name,
						fst_unit:detail.fst_unit,
						fdb_qty_po: detail.fdb_qty,
						fdb_qty_po_received: detail.fdb_qty_lpb,						
						fbl_is_batch_number:detail.fbl_is_batch_number,
						fbl_is_serial_number:detail.fbl_is_serial_number,
						fdc_conv_to_basic_unit:detail.fdc_conv_to_basic_unit,
						fst_basic_unit:detail.fst_basic_unit
					});
					t.row.add(data);
				});
				App.log(arrItems);
				$("#fstItem").select2({data:arrItems});

				App.fixedSelect2();
				t.draw(false);
				calculateTotalQty();

			});
		});

		
		$("#btn-add-items").click(function(e){
			e.preventDefault();
			rowDetail = null;
			$("#fstItem").val(null).trigger("change.select2");
			$("#mdlDetail").modal("show");
		});
	});
</script>

<script type="text/javascript" info="function">
	function getPOInfo(finPOId,callback){
		App.getValueAjax({
			site_url:"<?=site_url()?>",
			model:"trlpbgudang_model",
			func:"getPODetail",
			params:[finPOId],
			callback:callback
		});
	}
	function calculateTotalSerialNo(){
		$("#ttlSerial").text($("#fstSerialNoList option").length);

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
		var dataSubmit = $("#frmLPBGudang").serializeArray();
		var dataDetails = new Array();	
		var tDetails = $('#tbldetails').DataTable();
		var datas = tDetails.data();

		$.each(datas,function(i,v){
			dataDetails.push(v);
		});

		dataSubmit.push({
			name:"details",
			value: JSON.stringify(dataDetails)
		});

		var mode = $("#fin_lpbgudang_id").val() == "0" ? "ADD" : "EDIT";	

		if (mode == "ADD"){
			url =  "<?= site_url() ?>tr/gudang/penerimaan_pembelian/ajx_add_save/";
		}else{
			dataSubmit.push({
				name : "fin_user_id_request_by",
				value: MdlEditForm.user
			});
			dataSubmit.push({
				name : "fst_edit_notes",
				value: MdlEditForm.notes
			});

			url =  "<?= site_url() ?>tr/gudang/penerimaan_pembelian/ajx_edit_save/";
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
		
		var finLPBGudangId = $("#fin_lpbgudang_id").val();
		if (finLPBGudangId != 0){
			//get data from server;
			App.blockUIOnAjaxRequest("Loading data, please wait..!");
			$.ajax({
				url:"<?=site_url()?>tr/gudang/penerimaan_pembelian/fetch_data/" + finLPBGudangId,
				method:"GET",								
			}).done(function(resp){

				if(resp.message != ""){
					alert(resp.message);
				}

				if (resp.status == "SUCCESS"){				
					dataH = resp.data.lpbGudang;
					dataD = resp.data.lpbGudangItems;
					App.autoFillForm(dataH);

					$("#fdt_lpbgudang_datetime").val(App.dateTimeFormat(dataH.fdt_lpbgudang_datetime)).datetimepicker("update");
					
					//App.addOptionIfNotExist("<option value='"+ dataH.fin_po_id +"' selected>" + dataH.fst_po_no +"</option>","fin_po_id");					
					if ($('#fin_po_id').find("option[value='" + dataH.fin_po_id + "']").length) {
					} else { 
						var newOption = new Option(dataH.fst_po_no,dataH.fin_po_id, true, true);
						$('#fin_po_id').append(newOption);
					}
					$('#fin_po_id').trigger("change.select2");

					$("#fdt_po_datetime").val(App.dateTimeFormat(dataH.fdt_po_datetime)).datetimepicker("update");
					$("#fst_supplier_name").val(dataH.fstSupplierName);
					
					t= $("#tbldetails").DataTable();
					t.rows().remove();

					$.each(dataD,function(i,item){
						data = {
							fbl_is_batch_number:item.fbl_is_batch_number,
							fbl_is_serial_number: item.fbl_is_serial_number,
							fin_rec_id:item.fin_rec_id,
							fin_po_detail_id:item.fin_po_detail_id,
							fin_item_id:item.fin_item_id,
							fst_item_code:item.fst_item_code,
							fst_custom_item_name:item.fst_custom_item_name,
							fst_unit:item.fst_unit,
							fdb_qty_po: parseFloat(item.fdb_qty_po),
							fdb_qty_po_received:  parseFloat(item.fdb_qty_lpb),
							fdb_qty:item.fdb_qty,
							fdc_m3:item.fdc_m3,
							fst_batch_no: item.fst_batch_number,
							arr_serial: JSON.parse(item.fst_serial_number_list),
							fdc_conv_to_basic_unit:item.fdc_conv_to_basic_unit,
							fst_basic_unit:item.fst_basic_unit
						}					
						t.row.add(data);
					});					
					t.draw(false);
					calculateTotalQty();

					getPOInfo($("#fin_po_id").val(),function(resp){					
						details = resp.po_details;
						$.each(details,function(i,detail){
							arrItems.push({
								fin_po_detail_id:detail.fin_po_detail_id,
								id: detail.fin_item_id,						
								fst_item_code: detail.fst_item_code,
								text:detail.fst_custom_item_name,
								fst_unit:detail.fst_unit,
								fdb_qty_po: detail.fdb_qty,
								fdb_qty_po_received: detail.fdb_qty_lpb,						
								fbl_is_batch_number:detail.fbl_is_batch_number,
								fbl_is_serial_number:detail.fbl_is_serial_number,
								fdc_conv_to_basic_unit:detail.fdc_conv_to_basic_unit,
								fst_basic_unit:detail.fst_basic_unit																
							});
						});

						//Add item from tabel if not exist												
						var datas = t.data();
						$.each(datas,function(i,v){
							var isExist =false;
							$.each(arrItems,function(i2,v2){
								if(v.fin_item_id == v2.fin_item_id){
									isExist = true;
									return false;
								}								
							});
							if(isExist == false){							
								arrItems.push({
									fin_po_detail_id:v.fin_po_detail_id,
									id: v.fin_item_id,						
									fst_item_code: v.fst_item_code,
									text:v.fst_custom_item_name,
									fst_unit:v.fst_unit,
									fdb_qty_po: v.fdb_qty_po,
									fdb_qty_po_received: v.fdb_qty_po_received,						
									fdb_qty: v.fdb_qty,
									fbl_is_batch_number:v.fbl_is_batch_number,
									fbl_is_serial_number:v.fbl_is_serial_number,
									fdc_conv_to_basic_unit:v.fdc_conv_to_basic_unit,
									fst_basic_unit:v.fst_basic_unit																
								});
							}
						});						
						$("#fstItem").select2({data:arrItems});
						App.fixedSelect2();
					});
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

		var url =  "<?= site_url() ?>tr/gudang/penerimaan_pembelian/delete/" + $("#fin_lpbgudang_id").val();
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
</script>

<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>