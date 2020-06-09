<?php
	defined('BASEPATH') OR exit('No direct script access allowed');	
?>

<link rel="stylesheet" href="<?=base_url()?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<style>
	.form-group{
		margin-bottom:10px;
	}
	.modal-body {
		max-height: calc(100vh - 212px);
		overflow-y: auto;
	}	
</style>

<section class="content-header">
	<h1><?=lang("Distribusi PR")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Pembelian") ?></a></li>
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
						<a id="btnJurnal" class="btn btn-primary" href="#" title="Jurnal" style="display:<?= $mode == "ADD" ? "none" : "unset" ?>"><i class="fa fa-align-left" aria-hidden="true"></i></a>
						<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
						<a id="btnClose" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
					</div>
				</div>
				<!-- end box header -->

				<!-- form start -->
				<form id="frmTransaction" class="form-horizontal"  method="POST" enctype="multipart/form-data">			
					<div class="box-body">
						<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">	
						<input type="hidden" class="form-control" id="fin_distributepr_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_distributepr_id" value="<?=$fin_distributepr_id?>" readonly>


						<div class="form-group">
							<label for="fst_distributepr_no" class="col-md-2 control-label"><?=lang("No. Distribution")?> #</label>	
							<div class="col-md-4">				
								<input type="TEXT" id="fst_distributepr_no" name="fst_distributepr_no" class="form-control"  value="<?=$fst_distributepr_no?>" placeholder="PREFIX/BRANCH/YEAR/MONTH/99999" /> 
							</div>

							<label for="fdt_distributepr_datetime" class="col-md-2 control-label text-right"><?=lang("Tanggal Distribusi")?> *</label>
							<div class="col-md-4">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control text-right datetimepicker" id="fdt_distributepr_datetime" name="fdt_distributepr_datetime" value=""/>
								</div>
								<div id="fdt_distributepr_datetime_err" class="text-danger"></div>
								<!-- /.input group -->
							</div>
						</div>

						<div class="form-group">                            							
							<label for="fst_distributepr_notes" class="col-md-2 control-label"><?=lang("Memo")?> </label>
							<div class="col-sm-10">
								<textarea class="form-control" id="fst_distributepr_notes" placeholder="<?= lang("Memo") ?>" name="fst_distributepr_notes" rows="3" style="resize:none;width:100%"></textarea>
								<div id="fst_distributepr_notes_err" class="text-danger"></div>
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
							<div id="details_err" class="text-danger"></div>
						</div>

						<div class="form-group">                            							                            
							<div class="col-sm-6 col-sm-offset-6" style="padding-right:0px">								
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
	<div class="modal-dialog" style="display:table;width:700px;overflow:auto">
		<!-- modal content -->
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">			
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div style="border:0 px inset #f0f0f0;border-radius:10px;padding:5px">
							<form class="form-horizontal">									

								<div class="form-group">
									<label  class="col-md-3 control-label">Item : </label>
									<label  class="col-md-9 control-label" style="text-align:left" id="itemName"> </label>
								</div>
								<div class="form-group">
									<label for="fst_unit" class="col-md-3 control-label">Unit :</label>
									<label  class="col-md-3 control-label" style="text-align:left" id="itemUnit"> </label>
									<label class="col-md-3 control-label">To basic unit :</label>
									<label class="col-md-3 control-label" id="toBasicUnit" style="text-align:left"> 1</label>
								</div>
								<div class="form-group source-warehouse" >
									<label for="fin_source_warehouse_id" class="col-md-3 control-label">Source Warehouse</label>
									<div class="col-md-9">
										<select class="form-control" id="fin_source_warehouse_id">
										<?php
											$warehouseList = $this->mswarehouse_model->getWarehouseList();
											foreach($warehouseList as $warehouse){
												echo "<option value='$warehouse->fin_warehouse_id'>$warehouse->fst_warehouse_name</option>";
											}
										?>
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<label for="fdb_qty_distribute" class="col-md-3 control-label">Qty Distribute</label>
									<div class="col-md-9">
										<input type="number" class="form-control text-right numeric" id="fdb_qty_distribute" value="1" min="1">
									</div>
								</div>

								<div class="form-group batchNoBlock">
									<label for="" class="col-md-3 control-label"><?=lang("Batch Number")?></label>
									<div class="col-md-9">
										<select  id="fstBatchNo" class="form-control"></select>
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
									<div class="col-md-4" >
										<label for="" class=""><?=lang("Total Serial")?></label>
										<label id="ttlSerial" class="">0</label>

									</div>
									<div class="col-md-5 text-right" >
										<button id="btn-delete-serial" class="btn btn-primary btn-xs">Delete Selected Serial</button>
									</div>
								</div>

								
								<div class="form-group">
									<label for="fst_distributepr_detail_notes" class="col-md-3 control-label">Notes</label>
									<div class="col-md-9">
										<textarea class="form-control" id="fst_distributepr_detail_notes" style="resize:none"></textarea>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<form class="form-horizontal">
					<div class="form-group">
						<div class="col-md-12">
							<button type="button" class="btn btn-primary btn-sm text-center  form-control" id="btn-save-detail">Save</button>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-12">
							<button type="button" class="btn btn-default btn-sm text-center col-md-12 form-control" data-dismiss="modal">Cancel</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		var selectedItem;
		var mdlDetail = {
			show:function(data){
				mdlDetail.clearForm();
				mdlDetail.data = data;
				var qtyToDistribute = data.fdb_qty_distribute;
				$("#itemName").text(data.fst_item_code  + " - " + data.fst_item_name);
				$("#itemUnit").text(data.fst_unit);
				$("#fdb_qty_distribute").val(App.money_format(qtyToDistribute));
				$("#toBasicUnit").text(data.fin_conv_basic_unit + " " + data.fst_basic_unit);

				//if (data.fbl_stock == 1 && data.fin_item_type_id == 5 ){ //5:logistic	
				if (data.fbl_stock == 1){ //5:logistic	
					$(".source-warehouse").show();
					$("#fin_source_warehouse_id").val(data.fin_source_warehouse_id);
				}else{
					$(".source-warehouse").hide();
					$("#fin_source_warehouse_id").val(null);
					
				}

				if (data.fbl_is_batch_number == 1){
					$(".batchNoBlock").show();
					getBatchNoList(  $("#fin_source_warehouse_id").val(),  data.fin_item_id, function(){
						if (selectedDetail != null){
							$("#fstBatchNo").val(data.fst_batch_number);			
							if (data.fbl_is_serial_number == 1){				
								getSerialNoList(data.fin_source_warehouse_id,data.fin_item_id,data.fst_batch_number,function(){
									if (selectedDetail != null){
										$.each(data.fst_serial_number_list,function(i,serial){
											$("#fstSerialNoList").append("<option value='"+serial+"'>"+serial+"</option>");
										});	
									}else{
										$("#fstSerialNoList").empty();
									}
								});
							}
						}else{
							$("#fstBatchNo").val(null);
						}
					});
					//App.addOptionIfNotExist("<option value='"+data.fst_batch_number+"'>"+data.fst_batch_number+"</option>","fstBatchNo"); 

				}else{
					$(".batchNoBlock").hide();
					$("#fstBatchNo").val(null);
				}

				if (data.fbl_is_serial_number == 1){				
					$(".serialNoBlock").show();
				}else{				
					$(".serialNoBlock").hide();
					$("#fstSerialNoList").empty();
				}

				$("#mdlDetail").modal("show");				
			},
			hide:function(){
				$("#mdlDetail").modal("hide");
			},
			clearForm:function(){
				$("#fdb_qty_distribute").val(1);
				$("#fstBatchNo").val(null);
				$("#fstSerialNoList").empty();
				selectedItem = null;
			},
			save:function()		{
				var t = $("#tbldetails").DataTable();				
				var data = mdlDetail.data;

				if (data.fbl_is_batch_number == 1){
					if ($("#fstBatchNo").val() == "" || $("#fstBatchNo").val() == null ){
						alert("Batch Number harus diisi !");
						return;
					}
				}

				if (data.fbl_is_serial_number == 1){
					var convToBasic = parseFloat(data.fin_conv_basic_unit);								
					var qtyInUnit = parseFloat($("#fdb_qty_distribute").val());

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
				
				data.fdb_qty_distribute = App.money_parse($("#fdb_qty_distribute").val());
				data.fin_source_warehouse_id = $("#fin_source_warehouse_id option:selected").val();
				data.fst_source_warehouse_name = $("#fin_source_warehouse_id option:selected").text();
				data.fst_notes = $("#fst_distributepr_detail_notes").val();
				data.fst_batch_number = $("#fstBatchNo").val();
				data.fst_serial_number_list = arrSerial;
				

				//selectedDetail.data(data);	
				//t.draw(false);
				
				if(selectedDetail == null){
					//new data				
					data.fin_rec_id = 0 ;	
					t.row.add(data).draw(false);
				}else{
					//edit data
					dataTbl = selectedDetail.data();
					selectedDetail.data(data).draw(false);
				}	

				t.draw(false);
				selectedDetail = null;
				mdlDetail.clearForm();
				mdlDetail.hide();				
			},
			data:{}
		};


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
					App.fixedSelect2();
					callback();					
				}
			});			
		}

		function getSerialNoList(finWarehouseId,finItemId,fstBatchNo,callback){
			availableSerialList = [];

			App.getValueAjax({
				model:"trinventory_model",
				func:"getReadySerialNoList",
				params:[finWarehouseId,finItemId,fstBatchNo],
				wait_message: "<h5>Please wait !,trying to get batch no list....</h5>",
				callback:function(value){
					//$("#fstSerialNoList").empty();
					$.each(value,function(i,obj){
						availableSerialList.push(obj.fst_serial_no);
					});
					App.fixedSelect2();
					callback();					
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

		function calculateTotalSerialNo(){
			$("#ttlSerial").text($("#fstSerialNoList option").length);
		}

		$(function(){
			//Form Detail
			$("#btn-save-detail").click(function(e){
				e.preventDefault();		
				mdlDetail.save();		
			});	
			
			$("#fin_source_warehouse_id").change(function(e){
				e.preventDefault();
				//var data = selectedDetail.data();	
				getBatchNoList(  $("#fin_source_warehouse_id").val(),  mdlDetail.data.fin_item_id, function(){
					$("#fstBatchNo").val(null);
				});
			});

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
					getSerialNoList($("#fin_source_warehouse_id").val(), mdlDetail.data.fin_item_id,$("#fstBatchNo").val(),function(){
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
						
		});

	</script>
</div>



<div id="listNeedToDistribute" class="modal fade" role="dialog">
	<div class="modal-dialog" style="display:table;width:700px">
	<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">			
			<div class="modal-body">
				<table id="tblListNeedToDistribute" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
			</div>
			<div class="modal-footer">
				
			</div>
		</div>
	</div>

	<script type="text/javascript">
		var listNeedToDistribute = new Array();
		var selectedNeedToDistribute = null;

		$(function(){
			$("#listNeedToDistribute").modal("show");

			<?php
				$listNeedToDistribute = $this->trdistributepr_model->getNeedToDistribute();
				foreach($listNeedToDistribute as $needDistribute){ ?>
					listNeedToDistribute.push({
						fin_pr_detail_id:"<?= $needDistribute->fin_rec_id ?>",						
						fst_pr_no:"<?= $needDistribute->fst_pr_no ?>",
						fin_req_department_id:"<?= $needDistribute->fin_req_department_id ?>",
						fdt_pr_datetime:"<?= $needDistribute->fdt_pr_datetime ?>",
						fin_req_department_id:"<?= $needDistribute->fin_req_department_id ?>",
						fst_department_name:"<?= $needDistribute->fst_department_name ?>",
						fin_item_id:"<?= $needDistribute->fin_item_id ?>",
						fst_item_code:"<?= $needDistribute->fst_item_code ?>",
						fst_item_name:"<?= $needDistribute->fst_item_name ?>",
						fin_item_type_id:"<?= $needDistribute->fin_item_type_id ?>",
						fbl_stock:"<?= $needDistribute->fbl_stock ?>",
						fbl_is_batch_number:"<?= $needDistribute->fbl_is_batch_number ?>",
						fbl_is_serial_number:"<?= $needDistribute->fbl_is_serial_number ?>",
						fst_unit:"<?= $needDistribute->fst_unit?>",
						fst_basic_unit:"<?= $needDistribute->fst_basic_unit?>",
						fin_conv_basic_unit:"<?= $needDistribute->fin_conv_basic_unit?>",
						fdb_qty_req:"<?= $needDistribute->fdb_qty_req ?>",
						fdb_qty_process:"<?= $needDistribute->fdb_qty_process ?>",
						fdb_qty_distribute:"<?= $needDistribute->fdb_qty_distribute ?>",
						fdt_etd:"<?= $needDistribute->fdt_etd ?>",
						fst_memo:"<?= $needDistribute->fst_memo ?>",
					});					
			<?php } ?>
			$("#tblListNeedToDistribute").DataTable({
				scrollY: "300px",
				scrollX: true,			
				scrollCollapse: true,	
				order: [],
				columns:[
					{"title" : "fin_pr_detail_id","width": "0px",sortable:false,data:"fin_pr_detail_id",visible:false},
					{"className":'list-need-to-distribute-details-control text-center',"defaultContent": '<i class="fa fa-caret-right" aria-hidden="true"></i>',width:"10px",orderable:false},				
					{"title": "Department","width": "50px",sortable:false,data:"fin_req_department_id",
						render:function(data,type,row){
							return row.fst_department_name;
						}
					},
					{"title" : "Item","width": "250px",sortable:false,
						render:function(data,type,row){
							return row.fst_item_code + " - " +row.fst_item_name;
						}
					},
					{"title" : "Unit","width": "50px",sortable:false,data:"fst_unit"},
					{"title" : "Request","width": "100px",sortable:false,className:'text-right',data:"fdb_qty_req"},
					{"title" : "Process","width": "100px",sortable:false,className:'text-right',data:"fdb_qty_process"},
					{"title" : "Distribute","width": "100px",sortable:false,className:'text-right',data:"fdb_qty_distribute",
						render:function(data,type,row){
							return App.money_format(row.fdb_qty_distribute);
						}
					},					
					{"title" : "ETD",data:"fdt_etd","width": "100px",sortable:false,className:'text-right'},
					{"title" : "Action","width": "75px",sortable:false,className:'text-center',
						render:function(data,type,row){
							var action = '<a class="btn-add-distribute" href="#" data-original-title="" title="Edit"><i class="fa fa-plus"></i></a> &nbsp;';							
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
				data:listNeedToDistribute,
			}).on("click",".list-need-to-distribute-details-control",function(e){
				e.preventDefault();
				t = $('#tblListNeedToDistribute').DataTable();
				var tr = $(this).closest('tr');
				var row = t.row( tr );
				if ( row.child.isShown() ) {
					// This row is already open - close it
					row.child.hide();
					tr.removeClass('shown');
				}else {
					// Open this row
					row.child(sub_needToDistributeDetail(row.data()) ).show();
					tr.addClass('shown');
				} 
			}).on("click",".btn-add-distribute",function(e){
				e.preventDefault();
				var t = $("#tblListNeedToDistribute").DataTable();
				var trRow = $(this).parents('tr');			
				selectedNeedToDistribute = t.row(trRow);	
				
				var data = selectedNeedToDistribute.data();
				data.fdb_qty_distribute = data.fdb_qty_process - data.fdb_qty_distribute;
				$("#listNeedToDistribute").modal("hide");
				mdlDetail.show(data);
			});

			$("#listNeedToDistribute").modal("hide");

		});

		function sub_needToDistributeDetail(data){
			var sstr = "<div> PR # :"+ data.fst_pr_no + " - " + data.fdt_pr_datetime + " </div>";
				sstr += "<div> PR Notes :"+data.fst_memo+"</div>";
			return sstr;
		}		
	</script>
</div>



<?php echo $mdlEditForm ?>
<?php echo $mdlJurnal ?>

<script type="text/javascript" info="event">
	$(function(){
		
		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url()?>tr/purchase/purchase_request/distribute_add";
		});

		$("#btnSubmitAjax").click(function(e){
			e.preventDefault();
			submitAjax(0);
		});

		$("#btnJurnal").click(function(e){
			e.preventDefault();
			MdlJurnal.showJurnalByRef("PRD",$("#fin_distributepr_id").val());
		});
		
		$("#btnDelete").click(function(e){
			e.preventDefault();
			deleteAjax(0);
		});
		
		$("#btnClose").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url()?>tr/purchase/purchase_request/distribute";
		});		

		
		$("#btn-add-detail").click(function(e){
			e.preventDefault();
			mdlDetail.show();			
		});

		$("#btn-add-items").click(function(e){
			e.preventDefault();
			$("#listNeedToDistribute").modal("show");
		});

		
	});
</script>

<script type="text/javascript" info="define">
	var selectedDetail = null;	
	var t;
</script>

<script type="text/javascript" info="init">	

	$(function(){		
		$("#fdt_distributepr_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");	

		$('#tbldetails').on('preXhr.dt', function ( e, settings, data ) {
			//add aditional data post on ajax call
			data.sessionId = "TEST SESSION ID";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "fin_rec_id","width": "0px",sortable:false,data:"fin_rec_id",visible:false},
				{"className":'details-control text-center',"defaultContent": '<i class="fa fa-caret-right" aria-hidden="true"></i>',width:"10px",orderable:false},				
				{"title": "Department","width": "50px",sortable:false,
					render:function(data,type,row){
						return row.fst_department_name;
					}
				},
				{"title" : "Item","width": "250px",sortable:false,
					render:function(data,type,row){
						return row.fst_item_code + " - " +row.fst_item_name;
					}
				},
				{"title" : "Unit",data:"fst_unit","width": "50px",sortable:false},
				{"title" : "Distribute","width": "100px",sortable:false,className:'text-right',data:"fdb_qty_distribute",
					render:function(data,type,row){
						return App.money_format(row.fdb_qty_distribute);
					}
				},
				{"title" : "Source Warehouse","width": "100px",sortable:false,className:'text-right',data:"fin_source_warehouse_id",
					render:function(data,type,row){
						return row.fst_source_warehouse_name;
					}
				},
				{"title" : "ETD",data:"fdt_etd","width": "100px",sortable:false,className:'text-right'},
				{"title" : "Action","width": "75px",sortable:false,className:'text-center',
					render:function(data,type,row){
						var action = '<a class="btn-edit" href="#" data-original-title="" title="Edit"><i class="fa fa-pencil"></i></a> &nbsp;';
						action += '<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title="Delete"><i class="fa fa-trash"></i></a>';
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
			t = $("#tbldetails").DataTable();
			var trRow = $(this).parents('tr');			
			selectedDetail = t.row(trRow);
			var data = selectedDetail.data();	
			mdlDetail.show(data);

		}).on('click','.btn-delete',function(e){
			e.preventDefault();
			t = $('#tbldetails').DataTable();
			var trRow = $(this).parents('tr');
			t.row(trRow).remove().draw();			
		}).on("click",".details-control",function(e){
			e.preventDefault();
			t = $('#tbldetails').DataTable();
			var tr = $(this).closest('tr');
			var row = t.row( tr );
			if ( row.child.isShown() ) {
				// This row is already open - close it
				row.child.hide();
				tr.removeClass('shown');
			}else {
				// Open this row
				row.child(sub_trpurchaserequestitems(row.data()) ).show();
				tr.addClass('shown');
			} 
		});
		
		App.fixedSelect2();

		initForm();
	});

</script>

<script type="text/javascript" info="function">
	
	function sub_trpurchaserequestitems(data){
		var sstr = "<div> PR # :"+ data.fst_pr_no + " - " + data.fdt_pr_datetime + " </div>";
			sstr += "<div> PR Notes :"+data.fst_memo+"</div>";
			sstr += "<div> Distribution Notes :" +data.fst_notes + "</div>";

		return sstr;
	}

	function calculateTotal(){

		t= $('#tbldetails').DataTable();
		var datas = t.rows().data();
		
		var totalDistribute = 0;
		
		
		$.each(datas,function(i,data){
			totalDistribute += data.fdb_qty_distribute;
		});
		$("#ttlAmount").text(App.money_format(totalDistribute));

	}	

	function deletegetItemBuyUnit(defaultValue){
		App.getValueAjax({
			site_url:"<?= site_url()?>",
			model:"msitemunitdetails_model",
			func:"getBuyingListUnit",
			params:[$("#fin_item_id").val()],
			callback:function(units){
				var fstUnit = $("#fst_unit").val();
				$("#fst_unit").empty();
				$.each(units,function(i,unit){
					$("#fst_unit").append("<option value='" +unit.fst_unit + "'>"+unit.fst_unit+"</option>");
				});
				$("#fst_unit").val(fstUnit).trigger("change");				
			}
		});

	}

	

	function submitAjax(confirmEdit){

		var dataSubmit = $("#frmTransaction").serializeArray();
		
		var mode = $("#fin_distributepr_id").val() == "0" ? "ADD" : "EDIT";	

		if (mode == "ADD"){
			url =  "<?= site_url() ?>tr/purchase/purchase_request/ajx_distribute_add_save/";
		}else{
			dataSubmit.push({
				name : "fin_user_id_request_by",
				value: MdlEditForm.user
			});
			dataSubmit.push({
				name : "fst_edit_notes",
				value: MdlEditForm.notes
			});

			url =  "<?= site_url() ?>tr/purchase/purchase_request/ajx_distribute_edit_save/";
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
			detail = {
				fin_rec_id:v.fin_rec_id,
				fin_pr_detail_id:v.fin_pr_detail_id,
				fdb_qty_distribute:v.fdb_qty_distribute,
				fin_source_warehouse_id:v.fin_source_warehouse_id,
				fst_notes:v.fst_notes,
				fst_batch_number:v.fst_batch_number,
				fst_serial_number_list:v.fst_serial_number_list
			}
			details.push(detail);
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
				data = resp.data;
				$("#fin_distributepr_id").val(data.insert_id);
				//Clear all previous error
				$(".text-danger").html("");					
			}
		});
	}

	function initForm(){
		/*
		var finDistributePRId = $("#fin_distributepr_id").val();
		if (finDistributePRId != 0){
			//get data from server;
			App.blockUIOnAjaxRequest();
			$.ajax({
				url:"<?=site_url()?>tr/purchase/purchase_request/fetch_data/" + finPRId,
				method:"GET",								
			}).done(function(resp){
				if(resp.message != ""){
					alert(resp.message);
				}

				if (resp.status == "SUCCESS"){	
					
					dataH = resp.data.dataH;
					dataDetails = resp.data.dataDetails;					
					App.autoFillForm(dataH);
					
					$("#fdt_pr_datetime").val(App.dateTimeFormat(dataH.fdt_pr_datetime)).datetimepicker("update");
					$("#fdt_publish_datetime").val(App.dateTimeFormat(dataH.fdt_publish_datetime)).datetimepicker("update");
					$("#fin_req_department_id").val(dataH.fin_req_department_id);

					if (dataH.fdt_publish_datetime == null){
						$("#fst_publish_status").text("#UNPUBLISH");
					}else{
						$("#fst_publish_status").text("#PUBLISH");
					}

					
					t = $("#tbldetails").DataTable();					
					$.each(dataDetails , function(i,dataD){						
						var data ={
							fin_rec_id:dataD.fin_rec_id,
							fin_item_id:dataD.fin_item_id,
							fst_item_code:dataD.fst_item_code,
							fst_item_name:dataD.fst_item_name,
							fst_unit:dataD.fst_unit,
							fdb_qty_req:dataD.fdb_qty_req,
							fdb_qty_process:dataD.fdb_qty_process,
							fdb_qty_distribute:dataD.fdb_qty_distribute,
							fdt_etd:App.dateFormat(dataD.fdt_etd),
							fst_memo:dataD.fst_memo
						}
						
						t.row.add(data);						
					});									
					t.draw(false);
					
				}else{
					$("#btnNew").trigger("click");
				}
			});
		}else{
			//Get Need To Distribute Item
			App.blockUIOnAjaxRequest();
			$.ajax({
				url:"<?=site_url()?>tr/purchase/purchase_request/ajx_get_need_to_distribute",
				method:"GET",								
			}).done(function(resp){
				if(resp.message != ""){
					alert(resp.message);
				}

				if (resp.status == "SUCCESS"){	
					
					dataDetails = resp.data;
					

					t = $("#tbldetails").DataTable();					
					$.each(dataDetails , function(i,dataD){						
						var data ={
							fin_rec_id:dataD.fin_rec_id,
							fst_pr_no :dataD.fst_pr_no,
							fdt_pr_datetime :dataD.fdt_pr_datetime,
							fin_req_department_id :dataD.fin_req_department_id,
							fst_department_name:dataD.fst_department_name,
							fin_item_id:dataD.fin_item_id,
							fst_item_code:dataD.fst_item_code,
							fst_item_name:dataD.fst_item_name,
							fin_item_type_id: dataD.fin_item_type_id,
							fbl_stock: dataD.fbl_stock,
							fbl_is_batch_number: dataD.fbl_is_batch_number,
							fbl_is_serial_number: dataD.fbl_is_serial_number,
							fst_unit:dataD.fst_unit,
							fdb_qty_req:dataD.fdb_qty_req,
							fdb_qty_process:dataD.fdb_qty_process,
							fdb_qty_distribute:dataD.fdb_qty_process - dataD.fdb_qty_distribute,
							fin_source_warehouse_id:"",
							fst_source_warehouse_name:"",
							fst_distributepr_detail_notes: "",
							fdt_etd:App.dateFormat(dataD.fdt_etd),
							fst_memo:dataD.fst_memo
						}
						
						t.row.add(data);						
					});									
					t.draw(false);
					
				}else{
					$("#btnNew").trigger("click");
				}
			});
		}
		*/

		var finDistributePRId = $("#fin_distributepr_id").val();
		if (finDistributePRId != 0){
			App.blockUIOnAjaxRequest();
			$.ajax({
				url:"<?=site_url()?>tr/purchase/purchase_request/ajx_fetch_distibution/" + finDistributePRId,
				method:"GET",
			}).done(function(resp){
				
				if (resp.message != ""){
					alert(resp.message);
				}				
				if (resp.status == "SUCCESS"){
					var dataH = resp.data.dataH;
					var dataDetails = resp.data.dataDetails;

					App.autoFillForm(dataH);
					$("#fdt_distributepr_datetime").val(dateTimeFormat(dataH.fdt_distributepr_datetime)).datetimepicker("update");

					//var details =[];
					var t = $('#tbldetails').DataTable();
					$.each(dataDetails,function(i,v){
						/*
						var sstr = "<div> PR # :"+ data.fst_pr_no + " - " + data.fdt_pr_datetime + " </div>";
								sstr += "<div> PR Notes :"+data.fst_memo+"</div>";
								sstr += "<div> Distribution Notes :" +data.fst_notes + "</div>";
						*/


						t.row.add({
							fin_rec_id:v.fin_rec_id,
							fin_pr_detail_id:v.fin_pr_detail_id,
							fst_pr_no:v.fst_pr_no,
							fdt_pr_datetime:v.fdt_pr_datetime,
							fst_memo:v.fst_memo,
							fst_notes:v.fst_notes,
							fin_req_department_id:v.fin_req_department_id,
							fst_department_name:v.fst_department_name,
							fin_item_id:v.fin_item_id,
							fst_item_code:v.fst_item_code,
							fst_item_name:v.fst_item_name,
							fst_unit:v.fst_unit,
							fst_basic_unit:v.fst_basic_unit,
							fin_conv_basic_unit:v.fin_conv_basic_unit,
							fbl_stock:v.fbl_stock,
							fin_item_type_id:v.fin_item_type_id,
							fbl_is_batch_number:v.fbl_is_batch_number,
							fbl_is_serial_number:v.fbl_is_serial_number,
							fdb_qty_distribute:v.fdb_qty_distribute,
							fin_source_warehouse_id:v.fin_source_warehouse_id,
							fst_source_warehouse_name:v.fst_warehouse_name,
							fst_batch_number:v.fst_batch_number,
							fst_serial_number_list:JSON.parse(v.fst_serial_number_list),
							fdt_etd:v.fdt_etd,
						});
					});				
					t.draw(false);


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

		var url =  "<?= site_url() ?>tr/purchase/purchase_request/ajx_delete_distribute/" + $("#fin_distributepr_id").val();
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