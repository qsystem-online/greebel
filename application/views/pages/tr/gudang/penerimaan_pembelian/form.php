
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
							<label for="fst_lpbgudang_no" class="col-md-2 control-label"><?=lang("No. Penerimaan")?> #</label>	
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
							<label for="fst_lpb_type" class="col-md-2 control-label"><?=lang("Jenis Penerimaan")?> </label>
							<div class="col-md-10">
								<select id="fst_lpb_type" class="form-control non-editable" name="fst_lpb_type">
									<option value='PO'>Purchase Order</option>
									<!-- <option value='SO_RETURN'>Sales Return</option> -->
									<option value='ASSEMBLING_IN'>Assembling /Disassembling In</option>
								</select>
								<div id="fst_lpb_type_err" class="text-danger"></div>
							</div>							
						</div>


						<div class="form-group">
							<label for="fin_trans_id" class="col-md-2 control-label"><?=lang("No. Transaksi")?> </label>
							<div class="col-md-10">
								<select id="fin_trans_id" class="form-control non-editable" name="fin_trans_id">									
								</select>
								<div id="fin_trans_id_err" class="text-danger"></div>
							</div>
						</div>			
						<div class="form-group">
							<label class="col-md-10 col-md-offset-2">
								<label id="fst_relation_name">Relation Name</label>
							</label>
							
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
<div id="mdlDetail" class="modal fade in" role="dialog" style="display: none" data-backdrop="static">
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
								<form id="form-detail-penerimaan-gudang" class="form-horizontal">
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
											<input type='TEXT' id="fdcM3" class="money form-control" value="1"/>
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
		mdlDetail = {
			setDataItems:function(arrDataItems){
				arrItems = arrDataItems.map(function(dataD){
					dataD.id = dataD.fin_item_id;
					dataD.text = dataD.fst_custom_item_name;
					return dataD;
				});				
				$("#fstItem").select2({
					data: arrItems
				});
				App.fixedSelect2();
			},

			show:function(){
				if ($("#fin_warehouse_id").val() == null){
					alert("<?=lang("Gudang harus diisi !")?>");
					return;
				}
				$("#mdlDetail").modal("show");
			},
			hide:function(){
				$("#mdlDetail").modal("hide");
			},
			clear:function(){
				$("#fstItem").val(null).trigger("change.select2");
				$(".batchNoBlock").hide();
				$(".serialNoBlock").hide();
			},			
		};

		$(function(){
			
			$("#fstItem").change(function(e){
				e.preventDefault();
				//var data = $('#fstItem').find(':selected');
				var data = $('#fstItem').select2('data');
				data = data[0];		
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
					arrSerial.push($(serial).val());
				});


				t = $("#tbldetails").DataTable();

				var data;
				if (rowDetail == null){
					item = $("#fstItem").select2("data");
					item = item[0];	

					data = {
						fin_rec_id: 0,					
						fin_trans_detail_id: item.fin_trans_detail_id,
						fin_item_id: item.id,
						fst_item_code: item.fst_item_code,
						fst_custom_item_name: item.text,
						fst_unit: item.fst_unit,
						fdb_qty_trans: item.fdb_qty_trans,
						fdb_qty_lpb:item.fdb_qty_lpb,
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
					t.row.add(data).draw(false);
				}else{
					t.row(rowDetail).data(data).draw(false);
				}
				
				calculateTotalQty();
				mdlDetail.hide();

			});

		});
	</script>

</div>

<?php echo $mdlEditForm ?>
<?php echo $mdlPrint ?>




<script type="text/javascript" info="init">
	var rowDetail;
	var arrItems = new Array();
	var arrWarehouseNonLogistik =[];
	var arrWarehouseLogistik =[];

	<?php
		$warehouseList = $this->mswarehouse_model->getNonLogisticWarehouseList();
		foreach($warehouseList as $warehouse){ ?>
			arrWarehouseNonLogistik.push({
				id:"<?=$warehouse->fin_warehouse_id?>",
				text:"<?= $warehouse->fst_warehouse_name ." - " . $warehouse->fst_delivery_address?>"
			});
			//echo "<option value='$warehouse->fin_warehouse_id'>$warehouse->fst_warehouse_name - $warehouse->fst_delivery_address </option>";
	<?php }	?>
	
	<?php
		$warehouseList = $this->mswarehouse_model->getLogisticWarehouseList();
		foreach($warehouseList as $warehouse){ ?>
			arrWarehouseLogistik.push({
				id:"<?=$warehouse->fin_warehouse_id?>",
				text:"<?=$warehouse->fst_warehouse_name . " - " . $warehouse->fst_delivery_address?>"
			});
			//echo "<option value='$warehouse->fin_warehouse_id'>$warehouse->fst_warehouse_name - $warehouse->fst_delivery_address </option>";
	<?php }	?>


	
	$(function(){
		$("#fdt_lpbgudang_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");
		
		
		$("#fin_trans_id").select2({
			ajax:{
				delay:500,
				url:function(){
					return SITE_URL + "tr/gudang/penerimaan_pembelian/get_transaction_list/" + $("#fst_lpb_type").val();
				},
				processResults:function(resp){
					transList = resp.data;
					arrTrans = transList.map(function(trans){
						return {
							id:trans.fin_trans_id,
							text: trans.fst_trans_no,// + " - " + trans.fdt_trans_datetime + " - "  + trans.fst_relation_name,
							fst_trans_no:trans.fst_trans_no,
							fdt_trans_datetime:trans.fdt_trans_datetime,
							fin_pr_process_id:trans.fin_pr_process_id,
							fst_item_type:trans.fst_item_type,
							fst_relation_name:trans.fst_relation_name,
							fin_warehouse_id:trans.fin_warehouse_id,
						}
					});
					return {
						results:arrTrans
					}
				}
			},
			templateResult:function(trans){
				if (typeof trans.fst_trans_no == "undefined"){
					return trans.text;
				}
				return $("<span style='display:inline-block;width:150px'>" + trans.fst_trans_no +"</span><span style='display:inline-block;width:150px'>"+trans.fdt_trans_datetime+"</span><span>"+trans.fst_relation_name+"</span>");
			}
		}).on("select2:select",function(e){
			//GET DETAIL Transaction
			data = e.params.data;
			$("#fst_relation_name").html(data.fst_relation_name);
			//Bila fin_pr_process_id == 0 set warehouse logistik 
			//Bila melalui PR dan type logistik makan warehouse logistik

			$("#fin_warehouse_id").empty();
			if (data.fin_pr_process_id == 0){				
				$.each(arrWarehouseNonLogistik,function(i,v){
					App.addOptionIfNotExist("<option value='"+v.id+"'>"+v.text+"</option>","fin_warehouse_id");
				});
			}else{
				if (data.fst_item_type == "LOGISTIC"){
					$.each(arrWarehouseLogistik,function(i,v){
						App.addOptionIfNotExist("<option value='"+v.id+"'>"+v.text+"</option>","fin_warehouse_id");
					});
				}else{
					$.each(arrWarehouseNonLogistik,function(i,v){
						App.addOptionIfNotExist("<option value='"+v.id+"'>"+v.text+"</option>","fin_warehouse_id");
					});					
				}
			}

			if(typeof data.fin_warehouse_id !== "undefined") {
				$("#fin_warehouse_id").val(data.fin_warehouse_id);
			}

			//$("#fin_warehouse_id").empty();

			getDetailTransaction();
		});

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
				{"title" : "trans_detail_id","width": "50px",sortable:false,data:"fin_trans_detail_id",visible:false},
				{"title" : "Item Code","width": "100px",sortable:false,data:"fst_item_code"},
				{"title" : "Item Name","width": "100px",sortable:false,data:"fst_custom_item_name"},
				{"title" : "Unit","width": "100px",sortable:false,data:"fst_unit"},
				{"title" : "Total Qty","width": "100px",sortable:false,data:"fdb_qty_trans",className:'text-right'},
				{"title" : "Total Qty Received","width": "100px",sortable:false,data:"fdb_qty_lpb",className:'text-right'},
				{"title" : "Qty","width": "100px",sortable:false,data:"fdb_qty",className:'text-right'},
				{"title" : "M3","width": "100px",sortable:false,data:"fdc_m3",className:'text-right'},
				{"title" : "Action","width": "40px",sortable:false,className:'dt-body-center text-center',
					render: function(data,type,row){
						var action = '<a class="btn-edit" href="#" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp;';												

						if($("#fst_lpb_type").val() != "ASSEMBLING_IN"){
							action += '<a class="btn-delete non-assembling" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';						
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
			calculateTotalQty();

		}).on('click','.btn-edit',function(e){
			e.preventDefault();
			
			mdlDetail.show();

			t = $("#tbldetails").DataTable();
			var trRow = $(this).parents('tr');
			rowDetail = trRow;
			var data = t.row(trRow).data();			
			$("#fstItem").val(data.fin_item_id).trigger("change");
			$("#fstUnit").text(data.fst_unit);
			$("#fdbQty").val(data.fdb_qty);
			$("#fdcM3").val(data.fdc_m3);
			$("#fstBatchNo").val(data.fst_batch_no);
			$("#fstSerialNoList").empty();
			$.each(data.arr_serial,function(i,serial){
				$("#fstSerialNoList").prepend("<option value='"+serial+"'>"+serial+"</option>");
			});					
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
			window.location.href = "<?=site_url()?>tr/gudang/penerimaan_pembelian/";
		});

		$("#fst_lpb_type").change(function(e){
			$("#fin_trans_id").val(null).trigger("change.select2");

			if($("#fst_lpb_type").val() == "ASSEMBLING_IN"){
				$(".non-assembling").hide();
				$("#fdbQty").prop("readonly",true);
				$("#fdcM3").prop("readonly",true);
			}else{
				$(".non-assembling").show();
				$("#fdbQty").prop("readonly",false);
				$("#fdcM3").prop("readonly",false);
			}
		});


		$("#btn-add-items").click(function(e){
			e.preventDefault();
			rowDetail = null;
			mdlDetail.show();
			mdlDetail.clear();			
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
		var isValidData =true;

		
		$.each(datas,function(i,v){
			console.log(v);

			if ((v.fbl_is_batch_number == "1") && (v.fst_batch_no == null || v.fst_batch_no =="")){
				isValidData = false;
				alert ("Batch number " + v.fst_custom_item_name  + " tidak boleh kosong !");
				return false;
			}
			if ((v.fbl_is_serial_number == 1) && (v.fst_serial_number_list == null || v.fst_serial_number_list =="")){
				isValidData = false;
				alert ("Serial number " + v.fst_custom_item_name  + " tidak boleh kosong !");
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
					dataDetails = resp.data.lpbGudangItems;

					App.autoFillForm(dataH);

					$("#fdt_lpbgudang_datetime").val(App.dateTimeFormat(dataH.fdt_lpbgudang_datetime)).datetimepicker("update");

					App.addOptionIfNotExist("<option value='"+ dataH.fin_trans_id +"' selected>" + dataH.fst_trans_no +"</option>","fin_trans_id");	
					$('#fin_trans_id').trigger("change.select2");
					$("#fdt_trans_datetime").html(dataH.fdt_trans_datetime);

					App.addOptionIfNotExist("<option value='"+dataH.fin_warehouse_id+"'>"+dataH.fst_warehouse_name  + " - " + dataH.fst_delivery_address +"</option>","fin_warehouse_id");
					$("#fst_relation_name").html(dataH.fst_relation_name);
										
					
					t= $("#tbldetails").DataTable();
					t.rows().clear();
					arrDataDetails = [];
					$.each(dataDetails,function(i,dataD){
						data = {
							fbl_is_batch_number:dataD.fbl_is_batch_number,
							fbl_is_serial_number: dataD.fbl_is_serial_number,
							fin_rec_id:dataD.fin_rec_id,
							fin_trans_detail_id:dataD.fin_trans_detail_id,
							fin_item_id:dataD.fin_item_id,
							fst_item_code:dataD.fst_item_code,
							fst_custom_item_name:dataD.fst_custom_item_name,
							fst_unit:dataD.fst_unit,
							fdb_qty_trans: parseFloat(dataD.fdb_qty_trans),
							fdb_qty_lpb:  parseFloat(dataD.fdb_qty_lpb),
							fdb_qty:dataD.fdb_qty,
							fdc_m3:dataD.fdc_m3,
							fst_batch_no: dataD.fst_batch_number,
							arr_serial: JSON.parse(dataD.fst_serial_number_list),
							fdc_conv_to_basic_unit:dataD.fdc_conv_to_basic_unit,
							fst_basic_unit:dataD.fst_basic_unit
						}		
						arrDataDetails.push(data);						
					});	
					
					t.rows.add(arrDataDetails).draw(false);
					mdlDetail.setDataItems(arrDataDetails);
					

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