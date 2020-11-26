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
	.hm{		
		background-color: #22ffc7;
	}
</style>

<section class="content-header">
	<h1><?=lang("Workorder Batch Number")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Production") ?></a></li>
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
					<a id="btnPrint" class="btn btn-primary hide" href="#" title="<?=lang("Cetak")?>"><i class="fa fa-print" aria-hidden="true"></i></a>
					<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar List")?>"><i class="fa fa-list" aria-hidden="true"></i></a>
				</div>
			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmHeader" class="form-horizontal" action="" method="POST" >
				<div class="box-body">
					
					<div class="form-group">
						<label for="fst_wo_no" class="col-md-2 control-label"><?=lang("WO")?> #</label>
						<div class="col-md-4">
							<select class="form-control" id="fin_wo_id" name="fin_wo_id" style="width:100%"></select>
							<div id="fin_wo_id_err" class="text-danger"></div>
						</div>							
						<label for="fdt_wo_datetime" class="col-md-2 control-label"><?=lang("Tanggal")?></label>
						<div class="col-md-4">
							<input type="text" readonly class="form-control datetimepicker text-right" id="fdt_wo_datetime" placeholder="<?=lang("MPS Datetime")?>" name="fdt_wo_datetime" value=""/>
							<div id="fdt_wo_datetime_err" class="text-danger"></div>
						</div>								
                    </div>  

					<div class="form-group">
						<label for="fst_wo_type" class="col-md-2 control-label"><?=lang("WO Type")?></label>
						<div class="col-md-4">
							<select readonly id="fst_wo_type" name="fst_wo_type" class='form-control'>
								<option value='Internal'>Internal</option>
								<option value='External'>External</option>
							</select>
							<div id="fst_wo_type_err" class="text-danger"></div>
						</div>	
						<label for="fdt_wo_target_date" class="col-md-2 control-label"><?=lang("Target")?></label>
						<div class="col-md-4">
							<input type="text" readonly class="form-control datepicker text-right" id="fdt_wo_target_date" name="fdt_wo_target_date" placeholder="<?=lang("Target Date")?>" value=""/>
							<div id="fdt_wo_target_date_err" class="text-danger"></div>
						</div>							
					</div>									

					<div class="form-group">					
						<label for="fin_item_group_id" class="col-md-2 control-label"><?=lang("Group Item")?></label>
						<div class="col-md-10">
							<select readonly class="form-control" id="fin_item_group_id" placeholder="<?=lang("Group Item")?>" style="width:100%"></select>
							<div id="fin_item_group_id_err" class="text-danger"></div>
						</div>
					</div>
					<div class="form-group">					
						<label for="fin_item_id" class="col-md-2 control-label"><?=lang("Item")?></label>
						<div class="col-md-10">
							<select  readonly class="form-control" id="fin_item_id" name="fin_item_id" placeholder="<?=lang("Item")?>" style="width:100%"></select>
							<div id="fin_item_id_err" class="text-danger"></div>
						</div>
					</div>
					<div class="form-group">					
						<label for="fst_unit" class="col-md-2 control-label"><?=lang("Unit")?></label>
						<div class="col-md-6">
							<select readonly class="form-control" id="fst_unit" name="fst_unit" placeholder="<?=lang("Unit Production")?>" style="width:100%"></select>
							<div id="fst_unit_err" class="text-danger"></div>
						</div>
						<label for="fdb_qty" class="col-md-2 control-label"><?=lang("Qty")?></label>
						<div class="col-md-2">
							<input type="text" readonly class="form-control text-right" id="fdb_qty" name="fdb_qty" placeholder="<?=lang("qty")?>" value="1"></input>
							<div id="fdb_qty_err" class="text-danger"></div>
						</div>
					</div>								

					<div class="form-group" style="margin-top:20px">
						<div class="col-md-12 text-right">
							<button id="btn-add-batchno" class="btn btn-primary btn-sm">
								<i class="fa fa-cart-plus" aria-hidden="true"></i><?=lang("Tambah Batch No")?>
							</button>
						</div>
						<div class="col-md-12">
							<table id="tblBatchno" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
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


<div id="mdlDetailBatch" class="modal fade in" role="dialog" style="display:none">
	<div class="modal-dialog" style="display:table;width:600px">
		<!-- modal content -->
		<div class="modal-content">
			<div class="modal-header" style="padding:7px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 5px;border-top-right-radius: 5px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Batch Number")?></h4>
			</div>

			<div class="modal-body">				        
				<form class="form-horizontal">					
					<div class="form-group text-right">						
						<label class="col-md-12 control-label" id="d-fst_wobatchno_no">(Autonumber)</label>						
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label"><?=lang("Notes")?>:</label>						
						<div class="col-md-10">
							<textarea class="form-control" id="d-notes" style="width:100%"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label"><?=lang("Active")?>:</label>						
						<div class="col-md-10">
							<select class="form-control" id="d-active" style="width:100%">
								<option value='A'>Active</option>
								<option value='S'>Suspend</option>
							</select>
						</div>						
					</div>					
				</form>				
			</div>

			<div class="modal-footer">
				<button id="d-batchno-btn-save" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Add")?></button>
				<button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
			</div>
		</div>
	</div>

	<script type="text/javascript" info="define">

		var mdlDetailBatch = {
			selectedDetail:null,
			fin_wobatchno_id:0,			
			show:function(){
				if(mdlDetailBatch.selectedDetail != null){				
					var data = mdlDetailBatch.selectedDetail.data();					
					mdlDetailBatch.fin_wobatchno_id = data.fin_wobatchno_id;
					$("#d-fst_wobatchno_no").text(data.fst_wobatchno_no);
					$("#d-notes").val(data.fst_notes);
					$("#d-active").val(data.fst_active);
					
				}else{
					mdlDetailBatch.fin_wobatchno_id = 0;
					$("#d-fst_wobatchno_no").text("(Autonumber)");
					$("#d-notes").val("");
					$("#d-active").val("A");
					
				}
				$("#mdlDetailBatch").modal("show");
			},
			hide:function(){
				$("#mdlDetailBatch").modal("hide");
			},
			clear:function(){					
				mdlDetailBatch.selectedDetail = null;
				mdlDetailBatch.fin_wobatchno_id = 0;
			},			
		};

	</script>

	
	<script type="text/javascript" info="init">
		$(function(){	
			$("#d-bom-wo-fin_item_id").select2({
				ajax:{
					url:"<?=site_url()?>tr/production/wo/ajxGetItemForBom",
					data: function (params) {						
						return params;
					},
					dataType: 'json',
					delay: 250,
					processResults: function(resp) {
						if (resp.status == "SUCCESS"){
							var data = resp.data;
							var result = [];
							$.each(data, function(index, value) {
								result.push({
									"id": value.fin_item_id,
									"text": value.fst_item_code + " - " + value.fst_item_name,
									"fin_item_id":value.fin_item_id,
									"fst_item_code":value.fst_item_code,
									"fst_item_name":value.fst_item_name,
								});
							});
							return {
								results: result
							};
						}else{
							alert(resp.messages);
						}
					},
				}
			}).on("select2:select",function(e){		
				mdlDetailBOMWO.selectedItem = e.params.data;
			});		

			$("#d-bom-wo-fst_unit").select2({
				minimumInputLength: 0,
				minimumResultsForSearch: -1,
				ajax:{
					url:"<?=site_url()?>tr/production/wo/ajxGetUnitForBom",
					data: function (params) {
						params.fin_item_id = $("#d-bom-wo-fin_item_id").val();
						return params;
					},
					dataType: 'json',
					delay: 250,
					processResults: function(resp) {
						if (resp.status == "SUCCESS"){
							var data = resp.data;
							var result = [];
							$.each(data, function(index, value) {
								result.push({
									"id": value.fst_unit,
									"text": value.fst_unit,
								});
							});
							return {
								results: result
							};
						}else{
							alert(resp.messages);
						}
					},
				}
			});	

		});
	</script>

	<script type="text/javascript" info="event">		
		$(function(){						
			$("#d-batchno-btn-save").click(function(e){								
				var data= {};				
				$.ajax({
					url:"<?=site_url()?>tr/production/wo_batch/save_batchno",
					data:{
						fin_wo_id:$("#fin_wo_id").val(),
						fin_wobatchno_id:mdlDetailBatch.fin_wobatchno_id,
						fst_notes:$("#d-notes").val(),
						fst_active:$("#d-active").val(),
						[SECURITY_NAME]:SECURITY_VALUE,
					},
					method:"POST",
				}).done(function(resp){
					if(resp.status == "SUCCESS"){
						if(mdlDetailBatch.selectedDetail == null){
							//Add to table;
							var data = resp.data;
							var tmp = {
								fin_wobatchno_id:data.fin_wobatchno_id,
								fst_wobatchno_no:data.fst_wobatchno_no,
								fdt_wobatchno_datetime:data.fdt_wobatchno_datetime,
								fst_notes:data.fst_notes,
								fst_active:data.fst_active
							}
							tblBatchno.row.add(tmp);
						}else{
							//Update Table;
							var data = mdlDetailBatch.selectedDetail.data();
							data.fst_notes =$("#d-notes").val();
							data.fst_active = $("#d-active").val();
							tblBatchno.row(mdlDetailBatch.selectedDetail).data(data);
						}
						tblBatchno.draw(false);
						mdlDetailBatch.clear();		
						mdlDetailBatch.hide();		
					}else{
						alert(resp.messages);
					}

					

				});

				
			});
		});
	</script>

	<script type="text/javascript" info="function">
	</script>

</div>



<?php echo $mdlEditForm ?>
<?php echo $mdlPrint ?>

<script type="text/javascript" info="define">
	//var selectedItem;
	//var selectedDetail;	
	var tblBatchno;
</script>

<script type="text/javascript" info="bind">
	$(document).bind('keydown', 'alt+d', function(){
		$("#btn-add-detail").trigger("click");
	});
</script>

<script type="text/javascript" info="init">
	$(function(){		
		$("#fdt_wo_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");

		$("#fin_wo_id").select2({
			ajax:{
				url:"<?=site_url()?>tr/production/wo_batch/ajxGetWOList",
				data: function (params) {
					return params;
				},
				dataType: 'json',
                delay: 250,
                processResults: function(resp) {
					if (resp.status == "SUCCESS"){
						var data = resp.data;
						var result = [];
						$.each(data, function(index, value) {
							result.push({
								"id": value.fin_wo_id,
								"text": value.fst_wo_no,
							});
						});
						return {
							results: result
						};
					}else{
						alert(resp.messages);
					}
                },
			}
		}).on("select2:select",function(e){		
			getDetailWO($("#fin_wo_id").val());
		});

		
		tblBatchno = $('#tblBatchno').on('preXhr.dt', function ( e, settings, data ) {
			data.sessionId = "";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "id","width": "0px",sortable:false,data:"fin_wobatchno_id",visible:false},
				{"title" : "Batch No","width": "100px",sortable:false,data:"fst_wobatchno_no"},
				{"title" : "Created Date","width": "100px",sortable:false,data:"fdt_wobatchno_datetime"},
				{"title" : "Notes","width": "300px",sortable:false,data:"fst_notes"},
				{"title" : "Status","width": "100px",sortable:false,data:"fst_active",className:'text-center',
					render:function(data,type,row){
						if(data=="A"){
							return 'Active';
						}else{
							return 'Suspend';
						}
					}
				},
				{"title" : "Action","width": "80px",sortable:false,className:'dt-body-center text-center',
					render: function(data,type,row){

						var action = '<a class="btn-process" href="#"  data-original-title="" title="Close Batch"><i class="fa fa-cog"></i></a>&nbsp;';
						action += '<a class="btn-edit" href="#" data-original-title="" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;';
						action += '<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title="Delete"><i class="fa fa-trash"></i></a>';						
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
		}).on('click','.btn-process',function(e){
			var trRow = $(this).parents('tr');
			var data = tblBatchno.row(trRow).data();	
			console.log(data);	
			if (data.fst_active != 'A'){
				alert("<?=lang('Status Batch Number tidak aktif !')?>");
				return;
			}
			processBatchNo(data.fin_wobatchno_id);
		}).on('click','.btn-edit',function(e){
			e.preventDefault();
			var trRow = $(this).parents('tr');
			mdlDetailBatch.selectedDetail = tblBatchno.row(trRow);						
			mdlDetailBatch.show();
		}).on('click','.btn-delete',function(e){
			e.preventDefault();
			var trRow = $(this).parents('tr');
			
			var data  = tblBatchno.row(trRow).data();
			var cfrm = confirm("<?= lang('Hapus Batch Number ')?>" + data.fst_wobatchno_no);
			if (cfrm == true){
				$.ajax({
					url:"<?=site_url()?>tr/production/wo_batch/delete/data.fin_wobatchno_id",
					method:"GET",
				}).done(function(resp){
					if (resp.messages != ""){
						alert(resp.messages);
					}
					if (resp.status == "SUCCESS"){
						tblBatchno.row(trRow).remove().draw(false);		
					}
				})				
			}			

		});

        App.fixedSelect2();
		initForm();
	});
</script>

<script type="text/javascript" info="event">
	$(function(){
		$("#btnPrint").click(function(e){
			e.preventDefault();
			frameVoucher.print("<?=site_url()?>tr/gudang/mutasi/print_voucher/" + $("#fin_mag_id").val());
		});				
		
		$("#btnList").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>tr/production/wo_batch");
		});

		$("#btn-add-batchno").click(function(e){
			e.preventDefault();
			mdlDetailBatch.selectedDetail = null;
			mdlDetailBatch.show();
		});
	});
</script>

<script type="text/javascript" info="function">
	
	
	
	function initForm(){
		var mode = "<?=$mode?>";		
		if (mode == "EDIT"){
			getDetailWO("<?=$fin_wo_id?>");
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

		var url =  "<?= site_url() ?>tr/production/mps/delete/" + $("#fin_wo_id").val();
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
	function getDetailWO(finWOId){
		$.ajax({
			url:"<?=site_url()?>tr/production/wo_batch/ajxGetDetailWO",
			data:{
				"fin_wo_id":finWOId,
			},
			method:"GET",
		}).done(function(resp){
			if(resp.status=="SUCCESS"){
				var data = resp.data.header;
				$("#fdt_wo_datetime").val(dateTimeFormat(data.fdt_wo_datetime)).datetimepicker("update");	
				$("#fdt_wo_target_date").val(dateFormat(data.fdt_wo_target_date)).datepicker("update");	

				App.addOptionIfNotExist("<option value='"+data.fin_wo_id+"'>"+data.fst_wo_no +"</option>","fin_wo_id");				
				App.addOptionIfNotExist("<option value='"+data.fin_item_group_id+"'>"+data.fst_item_group_name +"</option>","fin_item_group_id");
				App.addOptionIfNotExist("<option value='"+data.fin_item_id+"'>"+data.fst_item_name +"</option>","fin_item_id");
				App.addOptionIfNotExist("<option value='"+data.fst_unit+"'>"+data.fst_unit +"</option>","fst_unit");
				$("#fdb_qty").val(data.fdb_qty);	
				
				var details = resp.data.details;
				$.each(details,function(i,v){
					var data = {
						fin_wobatchno_id: v.fin_wobatchno_id,
						fst_wobatchno_no:v.fst_wobatchno_no,
						fdt_wobatchno_datetime:v.fdt_wobatchno_datetime,
						fst_notes:v.fst_notes,
						fst_active:v.fst_active
					}
					tblBatchno.row.add(data);
				});
				tblBatchno.draw(false);				
			}else{
				alert(resp.messages);
			}
		})
	}
	function processBatchNo(finWOBatchnoId){
		//alert(finWOBatchnoId);
		$.ajax({
			url:"<?=site_url()?>tr/production/wo_batch/ajxClosing/" + finWOBatchnoId,
			method:"GET",			
		}).done(function(resp){

		});
	}
</script>
<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
