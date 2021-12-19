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
</style>

<section class="content-header">
	<h1><?=lang("Mutasi Antar Gudang")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Delivery Order") ?></a></li>
		<li class="active title"><?=$title?></li>
	</ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
				<div class="box-header with-border">
				<h3 class="box-title title"><?=$title?></h3>
				<div class="btn-group btn-group-sm  pull-right">					
					<a id="btnNew" class="btn btn-primary" href="#" title="<?=lang("Tambah Baru")?>"><i class="fa fa-plus" aria-hidden="true"></i></a>
					<a id="btnSubmitAjax" class="btn btn-primary" href="#" title="<?=lang("Simpan")?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
					<a id="btnPrint" class="btn btn-primary" href="#" title="<?=lang("Cetak")?>"><i class="fa fa-print" aria-hidden="true"></i></a>
					<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
					<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
				</div>
			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmHeader" class="form-horizontal" action="" method="POST" >
				<div class="box-body">
					<input type="hidden" id="frm-mode" value="<?=$mode?>">
					<input type="hidden" class="form-control" id="fin_mag_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_mag_id" value="<?=$fin_mag_id?>" readonly>

					
					<div class="form-group">
						<label for="fst_mag_no" class="col-md-2 control-label"><?=lang("MAG No.")?> #</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_mag_no" placeholder="<?=lang("MAG No")?>" name="fst_mag_no" value="<?=$fst_mag_no?>" readonly>
							<div id="fst_mag_no_err" class="text-danger"></div>
						</div>
						
						<label for="fdt_mag_datetime" class="col-md-2 control-label"><?=lang("MAG Date")?> *</label>
						<div class="col-md-4">
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control text-right datetimepicker" id="fdt_mag_datetime" name="fdt_mag_datetime"/>
							</div>
							<div id="fdt_mag_datetime_err" class="text-danger"></div>
							<!-- /.input group -->
						</div>						
                    </div>

					<div class="form-group">
						<label for="fin_from_warehouse_id" class="col-md-2 control-label"><?=lang("Gudang Asal")?></label>
						<div class="col-md-10">
							
							<select id="fin_from_warehouse_id" class="form-control" name="fin_from_warehouse_id" style="width:100%" >
								<?php
									$warehouseList = $this->mswarehouse_model->getWarehouseList();
									foreach($warehouseList as $warehouse){
										echo "<option value='$warehouse->fin_warehouse_id'>$warehouse->fst_warehouse_name</option>";
									}	
								?>
							</select>
							<div id="fin_from_warehouse_id_err" class="text-danger"></div>
						</div>					
					</div>

					<div class="form-group">
						<label for="fin_to_warehouse_id" class="col-md-2 control-label"><?=lang("Gudang Tujuan")?></label>
						<div class="col-md-10">
							<select id="fin_to_warehouse_id" class="form-control" name="fin_to_warehouse_id" style="width:100%" >
								<?php
									$warehouseList = $this->mswarehouse_model->getAllList();
									foreach($warehouseList as $warehouse){
										echo "<option value='$warehouse->fin_warehouse_id'>$warehouse->fst_warehouse_name</option>";
									}	
								?>
							</select>
							<div id="fin_from_warehouse_id_err" class="text-danger"></div>
						</div>					
					</div>

					<div class="form-group">
						<label for="fin_driver_id" class="col-md-2 control-label"><?=lang("Driver")?></label>
						<div class="col-md-4">
							<select id="fin_driver_id" class="form-control" name="fin_driver_id" style="width:100%" >
								<?php
									$driverList = $this->users_model->getDriverList();
									foreach($driverList as $driver){
										echo "<option value='$driver->fin_user_id'>$driver->fst_fullname</option>";
									}
								?>
							</select>
							<div id="fin_driver_id_err" class="text-danger"></div>
						</div>

						<label for="fst_no_polisi" class="col-md-2 control-label"><?=lang("No. Polisi")?></label>
						<div class="col-md-4">
							<input id="fst_no_polisi" class="form-control" name="fst_no_polisi" />
							<div id="fst_no_polisi_err" class="text-danger"></div>
						</div>					
					</div>



						
					<div class="form-group" style="margin-bottom:0px">
						<div class="col-md-12" style="text-align:right">
							<button id="btn-add-items" class="btn btn-primary btn-sm"><i class="fa fa-cart-plus" aria-hidden="true"></i>&nbsp;&nbsp;Tambah Item</button>
						</div>
					</div>
					
					<table id="tblDetails" class="table table-bordered table-hover table-striped" style="width:100%"></table>
                    <div id="detail_err" class="text-danger"></div>					
                    <div class="form-group">
						<div class="col-sm-6">	
							<div class="form-group">								
								<div class="col-sm-12">
									<label for="fst_memo" class=""><?=lang("Memo")?></label>
									<textarea class="form-control" id="fst_memo" placeholder="<?= lang("Memo") ?>" name="fst_memo" rows="5" style="resize:none"></textarea>
									<div id="fst_memo_err" class="text-danger"></div>
								</div>
							</div>
	
						</div>												
					</div>					
                </div>
				<!-- end box body -->

                <div class="box-footer text-right">
                    
                </div>
                <!-- end box-footer -->
            </form>
        </div>
    </div>
</section>

<!-- modal atau popup "ADD" -->
<div id="mdlDetail" class="modal fade in" role="dialog">
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
									<input type='hidden' id='fin_rec_id_items'/>
									<div class="form-group">
										<label class="col-md-3 control-label"><?=lang("Item")?></label>
										<div class="col-md-9">											
											<select id="fstItem" class="form-control" style="width:100%"> </select>
										</div>										
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label"><?=lang("Unit")?></label>	
										<div class="col-md-9">	
											<select id="fstUnit" class="form-control" style="width:100%"> </select>								
											<label class="control-label">  =>  </label>
											<label id="fdcConvBasicUnit" class="control-label">1</label>
											<label id="fstBasicUnit" class="control-label"><?=lang("Unit")?></label>
										</div>										
									</div>

									<div class="form-group">
										<label for="fdbQty" class="col-md-3 control-label"><?=lang("Qty")?></label>
										<div class="col-md-9">
											<input type='TEXT' id="fdbQty" class="money form-control" value="1"/>
										</div>
									</div>								

									<div class="form-group batchNoBlock">
										<label for="" class="col-md-3 control-label"><?=lang("Batch Number")?></label>
										<div class="col-md-9">
											<select  id="fstBatchNo" class="form-control" style="width:100%"> </select>
										</div>
									</div>

									<div class="form-group serialNoBlock">
										<label for="" class="col-md-3 control-label"><?=lang("Serial Number")?></label>
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
										<div class="col-md-9" >
											<label for="" class=""><?=lang("Total Serial")?></label>
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
		var selectedUnit;
		var selectedItem;		
		var availableSerialList = [];		
		var mdlDetail = {
			show:function(){
				mdlDetail.clear();
				if (selectedDetail != null){
					data =  selectedDetail.data();

					selectedItem = {
						fin_item_id:data.fin_item_id,
						fst_item_code:data.fst_item_code,
						fst_item_name:data.fst_item_name,
						fbl_is_batch_number:data.fbl_is_batch_number,
						fbl_is_serial_number:data.fbl_is_serial_number,
						id:data.fin_item_id,
						text:data.fst_item_code + " - " +data.fst_item_name, 
					};
					App.addOptionIfNotExist("<option value='"+selectedItem.id+"'>"+selectedItem.text+"</option>","fstItem");
					$('#fstItem').val(data.fin_item_id).trigger("change.select2");

					selectedUnit = {
						id:data.fst_unit,
						text:data.fst_unit,
						fst_unit:data.fst_unit,
						fbl_is_basic_unit:data.fst_is_basic_unit,
						convBasicUnit:data.fdc_conv_to_basic_unit,
						fst_basic_unit:data.fst_basic_unit					
					}
					App.addOptionIfNotExist("<option value='"+selectedUnit.id+"'>"+selectedUnit.text+"</option>","fstUnit");
					$("#fstUnit").val(data.fst_unit).trigger("change.select2");
					

					convertQty();
					$("#fdbQty").val(data.fdb_qty);

					if (selectedItem.fbl_is_batch_number == 1){
						$(".batchNoBlock").show();
						getBatchNoList(  $("#fin_warehouse_id").val(),  $("#fstItem").val(), function(){});
					}else{
						$(".batchNoBlock").hide();
					}

					if (selectedItem.fbl_is_serial_number == 1){
						$(".serialNoBlock").show();
					}else{
						$(".serialNoBlock").hide();
					}

					App.addOptionIfNotExist("<option value='"+data.fst_batch_number+"'>"+data.fst_batch_number+"</option>","fstBatchNo");
					$("#fstBatchNo").val(data.fst_batch_number).trigger("change.select2");
					getSerialNoList($("#fin_from_warehouse_id").val(),  $("#fstItem").val(),$("#fstBatchNo").val(),function(){
						$.each(data.fst_serial_number_list,function(i,serial){
							$("#fstSerialNoList").prepend("<option value='"+serial+"'>"+serial+"</option>");
						});
					});
				}

				$("#mdlDetail").modal("show");
			},

			hide:function(){
				$("#mdlDetail").modal("hide");
			},
			clear:function(){
				$("#fstItem").val(null).trigger("change.select2");
				$("#fstUnit").val(null).trigger("change.select2");
				$("#fdbQty").val(1);
				$("#fstBatchNo").val(null).trigger("change.select2");
				$("#fstSerialNoList").empty();
			}
		}
	</script>

	<script type="text/javascript" info="event">				
		$("#fstBatchNo").change(function(e){
			if( $(".serialNoBlock").is(":visible") ){
				if( $('#fstSerialNoList').has('option').length > 0 ) {				
					if (confirm("<?=lang("Rubah bach no akan menghapus semua serial no, lanjutkan ?")?>")){
						//Lanjut......	
					}else{
						e.preventDefault();
						return false;
					}
				}
				getSerialNoList($("#fin_from_warehouse_id").val(),  $("#fstItem").val(),$("#fstBatchNo").val(),function(){
					App.log(availableSerialList);
				});

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

				var idxArr =  availableSerialList.indexOf($("#fstSerialNo").val());
				if ( idxArr < 0){
					alert("<?=lang("No serial tidak dikenal !")?>");
					$("#fstSerialNo").select();
					return;
				}

				availableSerialList.splice( idxArr,1);

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
			item = selectedItem;			
			if ($("#fdbQty").val() <= 0 ){
				alert("<?=lang("Qty harus diisi !") ?>");
				return;
			}

			if (item.fbl_is_batch_number == 1){
				if ($("#fstBatchNo").val() == "" ){
					alert("Batch Number harus diisi !");
					return;
				}
			}

			if (item.fbl_is_serial_number == 1){
				var convToBasic = selectedUnit.convBasicUnit; //parseFloat($("#fdcConvBasicUnit").text());
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


			t = $("#tblDetails").DataTable();				
			dataRow = {
				fin_rec_id:0,
				fin_item_id: selectedItem.id,
				fst_item_code: selectedItem.fst_item_code,
				fst_item_name: selectedItem.fst_item_name,				
				fst_basic_unit : selectedUnit.fst_basic_unit,
				fbl_is_batch_number:selectedItem.fbl_is_batch_number,
				fbl_is_serial_number:selectedItem.fbl_is_serial_number,
				fst_unit: selectedUnit.id,
				fdc_conv_to_basic_unit : selectedUnit.convBasicUnit,
				fdb_qty: $("#fdbQty").val(),												
				fst_batch_number:$("#fstBatchNo").val(),
				fst_serial_number_list:arrSerial,				
			}			

			if (selectedDetail == null){				
				t.row.add(dataRow).draw(false);
			}else{
				selectedData = selectedDetail.data();
				dataRow.fin_rec_id = selectedData.fin_rec_id;
				selectedDetail.data(dataRow).draw(false);
			}
			selectedDetail = null;
			mdlDetail.clear();
			//mdlDetail.hide();
			

		});



	</script>

	<script type="text/javascript" info="init">
		$(function(){
			$("#fstItem").select2({
				minimumInputLength: 2,
				ajax:{
					delay: 250,
					url: "<?=site_url()?>/tr/gudang/mutasi/ajxListItem",
					dataType: 'json',
					processResults: function (result) {
						if (result.status == "SUCCESS"){
							var data = $.map(result.data, function (obj) {
								obj.id = obj.fin_item_id,  
								obj.text = obj.fst_item_code + " - "  + obj.fst_item_name;
								//obj.fbl_is_batch_number
								//obj.fbl_is_serial_number
  								return obj;
							});

							return {
								results: data
							};
						}else{
							return {
								result:[]
							}
						}
					}
				}
			}).on('select2:select',function(e){
				var data = e.params.data;
				selectedItem = data;
				$("#fstUnit").empty().trigger("change.select2");
				showHideBatchSerial();
			});

			
			$("#fstUnit").select2({
				minimumInputLength: 0,
				ajax:{
					url: "<?=site_url()?>/tr/gudang/mutasi/ajxListUnit",
					data:function(params){
						params.fin_item_id = $("#fstItem").val();
						return params;
					},
					dataType: 'json',
					processResults: function (result) {
						if (result.status == "SUCCESS"){
							var data = $.map(result.data, function (obj) {
								obj.id = obj.fst_unit; 
								obj.text = obj.fst_unit;
								obj.isBasic = obj.fbl_is_basic_unit;
								obj.convBasicUnit = obj.fdc_conv_to_basic_unit;								
  								return obj;
							});
							return {
								results: data
							};
						}else{
							return {
								result:[]
							}
						}
					}
				}
			}).on('select2:select', function (e) {
				var data = e.params.data;
				selectedUnit = data;
				convertQty();
			});

			$("#fstBatchNo").select2({allowClear:true});	

		});
	</script>

	<script type="text/javascript" info="function">
		function convertQty(){
			$("#fdcConvBasicUnit").text(selectedUnit.convBasicUnit);
			$("#fstBasicUnit").text(selectedUnit.fst_basic_unit);
		}

		function showHideBatchSerial(){
			if (selectedItem.fbl_is_batch_number == 1){
				$(".batchNoBlock").show();
				getBatchNoList($("#fin_from_warehouse_id").val(),selectedItem.id, function(){});
			}else{
				$(".batchNoBlock").hide();
				$("#fstBatchNo").empty().trigger("change.select2");
			}

			if (selectedItem.fbl_is_serial_number == 1){				
				$(".serialNoBlock").show();
				getSerialNoList($("#fin_from_warehouse_id").val(),selectedItem.id,$("#fstBatchNo").val(), function(){});
			}else{				
				$(".serialNoBlock").hide();
			}
		}

		function getBatchNoList(finWarehouseId,finItemId,callback){
			availableSerialList = [];
			App.getValueAjax({
				model:"trinventory_model",
				func:"getReadyBatchNoList",
				params:[finWarehouseId,finItemId],
				wait_message: "<h5>Please wait !,trying to get batch no list....</h5>",
				callback:function(value){
					$("#fstBatchNo").empty();
					$.each(value,function(i,obj){
						batchNo = obj.fst_batch_no;
						App.addOptionIfNotExist("<option value='"+batchNo+"'>"+batchNo+"</option>","fstBatchNo");						
					});				
					$("#fstBatchNo").val(null).trigger("change.select2");					
					callback();					
				}
			});
			
		}

		function getSerialNoList(finWarehouseId,finItemId,fstBatchNo,callback){
			App.getValueAjax({
				model:"trinventory_model",
				func:"getReadySerialNoList",
				params:[finWarehouseId,finItemId,fstBatchNo],
				wait_message: "<h5>Please wait !,trying to get batch no list....</h5>",
				callback:function(value){
					$("#fstSerialNoList").empty();
					$.each(value,function(i,obj){
						availableSerialList.push(obj.fst_serial_no);
					});
					App.fixedSelect2();
					callback();					
				}
			});			
		}
		function calculateTotalSerialNo(){
			$("#ttlSerial").text($("#fstSerialNoList option").length);
		}		
	</script>
</div>

<?php echo $mdlEditForm ?>
<?php echo $mdlPrint ?>


<script type="text/javascript" info="define">
	var selectedDetail = null;
</script>

<script type="text/javascript" info="bind">
	$(document).bind('keydown', 'alt+d', function(){
		$("#btn-add-detail").trigger("click");
	});
</script>


<script type="text/javascript" info="event">
	$(function(){
		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>tr/gudang/mutasi/add")
		});
		$("#btnPrint").click(function(e){
			//e.preventDefault();
			window.open("<?= site_url() ?>tr/gudang/mutasi/print_voucher/" +$("#fin_mag_id").val() ,"_blank","menubar=0,resizable=0,scrollbars=0,status=0,width=900,height=500");
		});

		$("#btnSubmitAjax").click(function(e){
            e.preventDefault();
            submitAjax(0);
		});
		
		$("#btnDelete").confirmation({
			title:"<?=lang("Hapus data ini ?")?>",
			rootSelector: '#btnDelete',
			placement: 'left',
		});
		$("#btnDelete").click(function(e){
			e.preventDefault();
			deleteAjax(0);
		});

		$("#btnList").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>tr/gudang/mutasi");
		});	

		$("#btn-add-items").click(function(e){
			e.preventDefault();
			selectedDetail = null;		
			mdlDetail.show();
			mdlDetail.clear();
		});
		
	});
</script>
<script type="text/javascript" info="init">
	$(function(){
		$("#fdt_mag_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");				
		$('#tblDetails').on('preXhr.dt', function ( e, settings, data ) {
		 	//add aditional data post on ajax call
		 	data.sessionId = "TEST SESSION ID";
		}).on('init.dt',function(){
			$(".dataTables_scrollHeadInner").css("min-width","100%");
			$(".dataTables_scrollHeadInner > table").css("min-width","100%");
			$(".dataTables_scrollBody").css("position","static");
		}).DataTable({
			scrollX: true,
            ordering: true,
			columns:[
				{"title" : "id","width": "0px",data:"fin_rec_id",visible:false},
				{"title" : "Item Code","width": "50px",data:"fst_item_code",visible:true,orderable:false},
				{"title" : "Custom Name","width": "300px",data:"fst_item_name",visible:true,orderable:false},
				{"title" : "Qty","width": "50px",data:"fdb_qty",className:'text-right',orderable:false,},
				{"title" : "Unit","width": "100px",data:"fst_unit",orderable:false,},
				{"title" : "Action","width": "28px",className:'dt-body-center text-center',orderable:false,
                    render: function( data, type, row, meta ) {
                        return "<div style='font-size:16px'><a class='btn-edit' href='#'><i class='fa fa-pencil'></i></a><a class='btn-delete' href='#'><i class='fa fa-trash'></i></a></div>";                        
                    }
                },
			],
			processing: true,
			serverSide: false,
			searching: false,
			lengthChange: false,
			paging: false,
			info:false,
			fnRowCallback: function( nRow, aData, iDisplayIndex ) {},
		}).on('draw',function(){						
			calculateTotal();
		}).on("click",".btn-delete",function(event){
			event.preventDefault();
			t = $('#tblDetails').DataTable();
			var trRow = $(this).parents('tr');
			t.row(trRow).remove().draw();
			calculateTotal();
		}).on("click",".btn-edit",function(event){	
            t = $("#tblDetails").DataTable();
            tRow = $(this).parents("tr");
            selectedDetail  = t.row(tRow);
			//data = t.row(tRow).data();			
			mdlDetail.show();			
		});
		
		App.fixedSelect2();
		initForm();
	});
</script>
<script type="text/javascript" info="function">
	function submitAjax(confirmEdit){        
		
		if($("#fin_from_warehouse_id").val() == $("#fin_to_warehouse_id").val()){
			alert("<?=lang("Gudang Asal & Gudang Tujuan Tidak boleh sama !")?>");
			return;
		}


		data = $("#frmHeader").serializeArray();

		data.push({
			name:SECURITY_NAME,
			value: SECURITY_VALUE
		});

		detail = new Array();		
		t = $('#tblDetails').DataTable();
		datas = t.data();
		var isValidData = true;
		$.each(datas,function(i,v){
			App.log(v);
			if ((v.fbl_is_batch_number == "1") && (v.fst_batch_number == null || v.fst_batch_number =="")){
				isValidData = false;
				alert ("Batch number " + v.fst_custom_item_name  + " tidak boleh kosong !");
				return false;
			}
			if ((v.fbl_is_serial_number == 1) && (v.fst_serial_number_list == null || v.fst_serial_number_list =="")){
				isValidData = false;
				alert ("Serial number " + v.fst_custom_item_name  + " tidak boleh kosong !");
				return false;
			}			
			detail.push(v);
		});
		if (isValidData == false){				
			return;
		}
		
		data.push({
			name:"detail",
			value: JSON.stringify(detail)
		});
	   
		if ($("#fin_mag_id").val() == 0){
			url = "<?=site_url()?>tr/gudang/mutasi/ajx_add_save";
		}else{
			if (confirmEdit == 0){
				MdlEditForm.saveCallBack = function(){
					submitAjax(1);
				};		
				MdlEditForm.show();
				return;
			}

			url = "<?=site_url()?>tr/gudang/mutasi/ajx_edit_save";
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
									window.open("<?= site_url() ?>tr/gudang/mutasi/print_voucher/" + data.insert_id ,"_blank","menubar=0,resizable=0,scrollbars=0,status=0,width=900,height=500");
									return false;
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
					$("#fin_sj_id").val(data.insert_id);

					//Clear all previous error
					$(".text-danger").html("");
					// Change to Edit mode
					$("#frm-mode").val("EDIT");  //ADD|EDIT
					$('#fst_sj_no').prop('readonly', true);
					$("#tabs-so-detail").show();
				}
        });

	}
	
	function initForm(){
		if ($("#fin_mag_id").val() != 0){
			App.blockUIOnAjaxRequest();
			$.ajax({
				url:"<?= site_url() ?>tr/gudang/mutasi/fetch_data/" + $("#fin_mag_id").val(),
			}).done(function(resp){
				data =  resp.data;
				dataH = data.header;
				if (dataH == null){
					alert("<?=lang("ID transaksi tidak dikenal")?>");
					//$("#btnNew").trigger("click");
					return false;
				}
				
				App.autoFillForm(dataH);
				$("#fdt_mag_datetime").val(dateTimeFormat(dataH.fdt_mag_datetime)).datetimepicker("update");
				
				details = data.details;
				t = $("#tblDetails").DataTable(); 
				t.clear();				
				$.each(details,function(i,v){				
					
					var dataRow = {
						fin_rec_id:v.fin_rec_id,
						fin_item_id: v.fin_item_id,
						fst_item_code: v.fst_item_code,
						fst_item_name: v.fst_item_name,				
						fst_basic_unit : v.fst_basic_unit,
						fbl_is_batch_number:v.fbl_is_batch_number,
						fbl_is_serial_number:v.fbl_is_serial_number,
						fst_unit: v.fst_unit,
						fdc_conv_to_basic_unit : v.fdc_conv_to_basic_unit,
						fdb_qty:v.fdb_qty,												
						fst_batch_number:v.fst_batch_number,
						fst_serial_number_list: JSON.parse(v.fst_serial_number_list),
					}
					console.log(dataRow);
					t.row.add(dataRow);
				});
				t.draw();								
				App.fixedSelect2();
			});
		}
	}

    function getDetailTransaction(callback){
		t = $("#tblSJDetails").DataTable();
		t.clear().draw();

        $.ajax({
            url:"<?=site_url()?>tr/gudang/pengiriman_penjualan/get_detail_trans/" +$("#fst_sj_type").val() + "/" + $("#fin_trans_id").val(),
        }).done(function(resp){
            arrData = resp.data;
			
			var dataTable =[];			
            $.each(arrData,function(i,v){				
				dataRow = {
					fbl_is_batch_number: v.fbl_is_batch_number,
					fbl_is_serial_number: v.fbl_is_serial_number,
					fdb_qty: v.fdb_qty,
					fin_item_id: v.fin_item_id,
					fin_promo_id: v.fin_promo_id,
					fin_trans_detail_id: v.fin_trans_detail_id,
					fst_custom_item_name: v.fst_custom_item_name,
					fst_item_code: v.fst_item_code,
					fst_item_name: v.fst_item_name,
					fst_unit: v.fst_unit,
					fin_rec_id:0,
					fst_memo_item:"",
					fst_batch_number:null,
					fst_serial_number_list:null,
					fst_basic_unit : v.fst_basic_unit,
            		fdc_conv_to_basic_unit : v.fdc_conv_to_basic_unit,
				}
				dataTable.push(dataRow);

				itemList.push({
					fin_trans_detail_id: v.fin_trans_detail_id,
					id: v.fin_item_id,
					text:v.fst_custom_item_name,
					fin_promo_id: v.fin_promo_id,
					fst_item_code: v.fst_item_code,
					fst_item_name: v.fst_item_name,
					fst_unit: v.fst_unit,
					fdb_qty: v.fdb_qty,
					fbl_is_batch_number: v.fbl_is_batch_number,
					fbl_is_serial_number: v.fbl_is_serial_number,
					fst_basic_unit : v.fst_basic_unit,
					fdc_conv_to_basic_unit : v.fdc_conv_to_basic_unit,									
				});			
			});

			t.rows.add(dataTable).draw(false);
			$("#fstItem").select2({
				data:itemList
			});

			//$("#fstItem").val(null).trigger("change.select2");
			App.fixedSelect2();			
			callback();
        });
    }

    function calculateTotal(){
        t = $("#tblDetails").DataTable();
        datas = t.rows().data();
        ttl = 0;
        $.each(datas,function(i,v){
            ttl += money_parse(v.fdb_qty);
        })
		$("#sub-total").val(money_format(ttl));		
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

		var url =  "<?= site_url() ?>tr/gudang/mutasi/delete/" + $("#fin_mag_id").val();
		$.ajax({
			url:url,
			method:"POST",
			data:dataSubmit,
		}).done(function(resp){
			if (resp.message != ""){
				alert(resp.message);
			}
			if(resp.status == "SUCCESS"){
				$("#btnList").trigger("click");			
			}
		});	

	}

</script>
<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
