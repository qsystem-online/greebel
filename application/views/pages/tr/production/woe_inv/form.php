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
	<h1><?=lang("Workorder Ekternal In")?><small><?=lang("form")?></small></h1>
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
				<a id="btnNew" class="btn btn-primary" href="#" title="<?=lang("Tambah Baru")?>"><i class="fa fa-plus" aria-hidden="true"></i></a>
					<a id="btnSubmitAjax" class="btn btn-primary" href="#" title="<?=lang("Simpan")?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
					<a id="btnPrint" class="btn btn-primary hide" href="#" title="<?=lang("Cetak")?>"><i class="fa fa-print" aria-hidden="true"></i></a>
					<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
					<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("List")?>"><i class="fa fa-list" aria-hidden="true"></i></a>
				</div>
			</div>
			<!-- end box header -->

			<!-- form start -->
			<form id="frmHeader" class="form-horizontal" action="" method="POST" >
				<div class="box-body">
					<input type="hidden" name="fin_woeinv_id" id="fin_woeinv_id" value="<?=$fin_woeinv_id?>"/>
					<div class="form-group">
						<label for="fst_woeinv_no" class="col-md-2 control-label"><?=lang("Invoice No.")?> #</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_woeinv_no" name="fst_woeinv_no" value="<?=$fst_woeinv_no?>" readonly /> 
							<div id="fst_woeinv_no" class="text-danger"></div>
						</div>							
						<label for="fdt_woeinv_datetime" class="col-md-2 control-label"><?=lang("Tanggal")?></label>
						<div class="col-md-4">
							<input type="text" readonly class="form-control datetimepicker text-right" id="fdt_woeinv_datetime" placeholder="<?=lang("Datetime")?>" name="fdt_woeinv_datetime" value=""/>
							<div id="fdt_woeinv_datetime_err" class="text-danger"></div>
						</div>								
					</div>  


					<div class="form-group">
						<label for="fin_wo_id" class="col-md-2 control-label"><?=lang("External WO")?> #</label>
						<div class="col-md-10">
							<select class="form-control" id="fin_wo_id" name="fin_wo_id" style="width:100%"></select>
							<div id="fin_wo_id_err" class="text-danger"></div>
						</div>													
					</div>  
					
					<div class="form-group">
						<label for="fin_supplier_id" class="col-md-2 control-label"><?=lang("Supplier")?> #</label>
						<div class="col-md-10">
							<select class="form-control" id="fin_supplier_id" name="fin_supplier_id" style="width:100%" ></select>
							<div id="fin_supplier_id_err" class="text-danger"></div>
						</div>													
					</div>  
					
					<div class="form-group" style="">					
						<label for="fst_curr_code" class="col-md-2 control-label">Currency Code</label>
						<div class="col-md-4">
							<select class="form-control " id="fst_curr_code" name="fst_curr_code" disabled>
								<?php
									$currList = $this->mscurrencies_model->getCurrencyList();
									foreach($currList as $curr){
										echo "<option value='$curr->fst_curr_code' data-rate='$curr->fdc_exchange_rate_to_idr'>$curr->fst_curr_code - $curr->fst_curr_name </option>";
									}
								?>
							</select>
							<div id="fst_curr_code_err" class="text-danger"></div>
						</div>

						<label for="fdc_exchange_rate_idr" class="col-md-2 control-label">Rate</label>
						<div class="col-md-4">
							<input type="TEXT" class="form-control money" id="fdc_exchange_rate_idr" name="fdc_exchange_rate_idr" value="0" style="text-align: right;">
							<div id="fdc_exchange_rate_idr_err" class="text-danger"></div>
						</div>
					</div>

					
					<div class="form-group">					
						<label for="fin_item_id" class="col-md-2 control-label"><?=lang("Item")?></label>
						<div class="col-md-10">
							<input  type="text" readonly class="form-control" id="fst_item"  placeholder="<?=lang("Item")?>" style="width:100%"></input>
							<div id="fin_item_id_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">					
						<label for="fst_unit" class="col-md-2 control-label"><?=lang("Unit")?></label>
						<div class="col-md-3">
							<select readonly class="form-control" id="fst_unit" placeholder="<?=lang("Unit Production")?>" style="width:100%"></select>
						</div>
						<label for="fdb_qty" class="col-md-5 control-label"><?=lang("Cost Per Unit")?></label>
						<div class="col-md-2">
							<input type="text" class="form-control text-right money" id="fdc_external_cost_per_unit" name="fdc_external_cost_per_unit" placeholder="<?=lang("0")?>" value="0"></input>
						</div>
					</div>


					<div class="form-group">					
						<label for="fin_woein_id_list" class="col-md-2 control-label"><?=lang("Workorder In")?></label>
						<div class="col-md-10">
							<select  class="form-control" id="fin_woein_id_list" name="fin_woein_id_list[]" style="width:100%" multiple="multiple"></select>
						</div>
					</div>

					<div class="form-group">					
						<label for="ttl-qty" class="col-md-2 control-label"><?=lang("Total Qty")?></label>
						<div class="col-md-4">
							<input type="text" class="form-control text-right" disabled id="ttl-qty" value="0"/>
						</div>
						<label for="ttl-cost" class="col-md-2 control-label"><?=lang("Total Cost")?></label>
						<div class="col-md-4">
							<input type="text" class="form-control text-right" disabled id="ttl-cost" value="0"/>
						</div>
					</div>

					<div class="form-group" style="margin-top:20px">
						<div class="col-md-12 text-right">
							<button id="btn-add-cost" class="btn btn-primary btn-sm">
								<i class="fa fa-cart-plus" aria-hidden="true"></i><?=lang("Tambah Cost Account")?>
							</button>
						</div>
						<div class="col-md-12">
							<table id="tblCost" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
						</div>		
					</div>
					<div class="form-group" style="margin-top:0px">
						<label for="ttl-qty" class="col-md-8 control-label"><?=lang("Total Cost")?></label>
						<div class="col-md-4">
							<input type="text" class="form-control text-right" disabled id="ttl-cost-detail" value="0"/>
						</div>
						
					</div>
					
					<div class="form-group">
						<label for="fst_unit" class="col-md-2 control-label"><?=lang("memo")?></label>
						<div class="col-md-10">
							<textarea class="form-control" id="fst_memo" name="fst_memo" rows="5"></textarea>
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

<?php echo $mdlEditForm ?>
<?php echo $mdlPrint ?>



<div id="MdlCost" class="modal fade in" role="dialog" style="display:none">
	<div class="modal-dialog" style="display:table;width:900px">
		<!-- modal content -->
		<div class="modal-content">
			<div class="modal-header" style="padding:7px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 5px;border-top-right-radius: 5px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Add Cost Account")?></h4>
			</div>

			<div class="modal-body">				        
				<form class="form-horizontal">					
					<div class="form-group">
						<label class="col-md-2 control-label"><?=lang("Account Code")?>:</label>						
						<div class="col-md-10">
							<select class="form-control" id="d-fst_glaccount_code" style="width:100%"></select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label"><?=lang("Total")?>:</label>						
						<div class="col-md-10">
							<input type="text" class="form-control money" id="d-fdc_total" value="0"></input>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label"><?=lang("Cost Center")?>:</label>						
						<div class="col-md-4">
							<select class="form-control" id="d-fin_pcc_id" style="width:100%">
								<?php
									$pccList = $this->profitcostcenter_model->getAllList();
									foreach($pccList as $pcc){
										echo "<option value='$pcc->fin_pcc_id'>$pcc->fst_pcc_name</option>";
									}
								?>
							</select>
						</div>
						<label class="col-md-2 control-label"><?=lang("Cost Divisi")?>:</label>						
						<div class="col-md-4">
							<select class="form-control" id="d-fin_pc_divisi_id" style="width:100%">
								<?php
									$deptList = $this->msdepartments_model->getAllList();
									foreach($deptList as $dept){
										echo "<option value='$dept->fin_department_id'>$dept->fst_department_name</option>";
									}
								?>

							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label"><?=lang("Cost Customer")?>:</label>						
						<div class="col-md-4">
							<select class="form-control" id="d-fin_pc_customer_id" style="width:100%"></select>
						</div>
						<label class="col-md-2 control-label"><?=lang("Cost Project")?>:</label>						
						<div class="col-md-4">
							<select class="form-control" id="d-fin_pc_project_id" style="width:100%">
								<?php
									$projectList = $this->msprojects_model->getAllList();
									foreach($projectList as $project){
										echo "<option value='$project->fin_project_id'>$project->fst_project_name</option>";
									}
								?>
							</select>
						</div>
					</div>


							
				</form>				
			</div>

			<div class="modal-footer">
				<button id="d-btn-save" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Add")?></button>
				<button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
			</div>
		</div>
	</div>

	<script type="text/javascript" info="define">

		var mdlDetailCost = {
			selectedDetail:null,			
			show:function(){

				if(mdlDetailCost.selectedDetail != null){				
					var data = mdlDetailCost.selectedDetail.data();
					console.log(data);

					App.addOptionIfNotExist("<option value='"+data.fst_glaccount_code+"'>"+ data.fst_glaccount_code +" - " + data.fst_glaccount_name+"</option>","d-fst_glaccount_code");
					$("#d-fdc_total").val(money_format(data.fdc_total));
					$("#d-fin_pcc_id").val(data.fin_pcc_id).trigger("change");
					$("#d-fin_pc_divisi_id").val(data.fin_pc_divisi_id).trigger("change");

					App.addOptionIfNotExist("<option value='"+data.fin_pc_customer_id +"'>"+ data.fst_pc_customer_name +"</option>","d-fin_pc_customer_id");					
					$("#d-fin_pc_customer_id").val(data.fin_pc_customer_id).trigger("change");

					$("#d-fin_pc_project_id").val(data.fin_pc_project_id).trigger("change");
					
				}else{
				
					
				}
				$("#MdlCost").modal("show");
			},
			hide:function(){
				$("#MdlCost").modal("hide");
			},
			clear:function(){					
				mdlDetailCost.selectedDetail = null;
				$("d-fst_glaccount_code").val(null);
				$("#d-fdc_total").val(money_format(0));
				$("#d-fin_pcc_id").val(null).trigger("change");
				$("#d-fin_pc_divisi_id").val(null).trigger("change");
				$("#d-fin_pc_customer_id").val(null).trigger("change");
				$("#d-fin_pc_project_id").val(null).trigger("change");
			},
		};

	</script>

	
	<script type="text/javascript" info="init">
		$(function(){	

			$("#d-fst_glaccount_code").select2({
				ajax:{
					url:"<?=site_url()?>tr/production/woe_inv/ajxGetCostAccount",
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
									"id": value.fst_glaccount_code,
									"text": value.fst_glaccount_code + " - " + value.fst_glaccount_name,
									"fst_glaccount_name":value.fst_glaccount_name,
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
				//mdlDetailBOMWO.selectedItem = e.params.data;
			});	

			$("#d-fin_pcc_id").select2({
				placeholder: 'Profit Cost Center',
				allowClear:true,
			});
			$("#d-fin_pc_divisi_id").select2({
				placeholder: 'Divisi Cost Center',
				allowClear:true,
			});
			$("#d-fin_pc_customer_id").select2({
				placeholder: 'Customer Cost Center',
				allowClear:true,
			});
			$("#d-fin_pc_project_id").select2({
				placeholder: 'Project Cost Center',
				allowClear:true,
			});
			
			$("#d-fin_pc_customer_id").select2({
				allowClear:true,
				ajax:{
					url:"<?=site_url()?>tr/production/woe_inv/ajxGetCustomer",
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
				}
			}).on("select2:select",function(e){		
				//mdlDetailBOMWO.selectedItem = e.params.data;
			});				

		});
	</script>

	<script type="text/javascript" info="event">		
		$(function(){						
			$("#d-btn-save").click(function(e){								
				//var data= {};

				var data = {
					fin_rec_id:0,
					fst_glaccount_code: $("#d-fst_glaccount_code").val(),
					fst_glaccount_name: $("#d-fst_glaccount_code option:selected").text(),
					fin_pcc_id:$("#d-fin_pcc_id").val(),
					fst_pcc_name: $("#d-fin_pcc_id option:selected").text(),
					fin_pc_divisi_id:$("#d-fin_pc_divisi_id").val(),
					fst_pc_divisi_name:$("#d-fin_pc_divisi_id option:selected").text(),
					fin_pc_customer_id:$("#d-fin_pc_customer_id").val(),
					fst_pc_customer_name:$("#d-fin_pc_customer_id option:selected").text(),					
					fin_pc_project_id:$("#d-fin_pc_project_id").val(),
					fst_pc_project_name:$("#d-fin_pc_project_id option:selected").text(),
					fdc_total:$("#d-fdc_total").val(),
				};
				//console.log(data);

				if (mdlDetailCost.selectedDetail == null){
					tblCost.row.add(data).draw(false);
				}else{
					dataOld =mdlDetailCost.selectedDetail.data();
					data.fin_rec_id = dataOld.fin_rec_id;
					tblCost.row(mdlDetailCost.selectedDetail).data(data);

				}
				tblCost.draw(false);
			});
		});
	</script>

	<script type="text/javascript" info="function">
	</script>

</div>

<script type="text/javascript" info="define">
	var selectedWO;
	var selectedWOInList = [];
	var tblCost;
</script>

<script type="text/javascript" info="bind">
	$(document).bind('keydown', 'alt+d', function(){
		$("#btn-add-detail").trigger("click");
	});
</script>

<script type="text/javascript" info="init">
	$(function(){		
		$("#fdt_woeinv_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");

		$("#fin_wo_id").select2({
			ajax:{
				url:"<?=site_url()?>tr/production/woe_inv/ajxGetWOList",
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
								"text": value.fst_wo_no + ' - ' + value.fst_item_name,
								"fin_supplier_id":value.fin_supplier_id,
								"fst_supplier_name":value.fst_supplier_name,
								"fin_item_id":value.fin_item_id,
								"fst_item_code":value.fst_item_code,
								"fst_item_name":value.fst_item_name,
								"fst_unit":value.fst_unit,
								"fdc_external_cost_per_unit":value.fdc_external_cost_per_unit,
								"fst_curr_code":value.fst_curr_code
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
			selectedWO = e.params.data;
			App.addOptionIfNotExist("<option value='"+selectedWO.fin_supplier_id+"'>"+ selectedWO.fst_supplier_name +"</option>","fin_supplier_id");
			$("#fst_item").val(selectedWO.fst_item_code + " - " + selectedWO.fst_item_name);
			App.addOptionIfNotExist("<option value='"+selectedWO.fst_unit+"'>"+ selectedWO.fst_unit +"</option>","fst_unit");			
			$("#fdc_external_cost_per_unit").val(App.money_format(selectedWO.fdc_external_cost_per_unit));
			$("#fst_curr_code").val(selectedWO.fst_curr_code).trigger("change");
		});

		$("#fin_supplier_id").select2({
			ajax:{
				url:"<?=site_url()?>tr/production/woe_inv/ajxGetSupplierList",
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
			}
		});

		$("#fin_woein_id_list").select2({
			ajax:{
				url:"<?=site_url()?>tr/production/woe_inv/ajxGetWOInList",				
				data: function (params) {
					params.fin_wo_id = $("#fin_wo_id").val();
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
								"id": value.fin_woein_id,
								"text": value.fst_woein_no,
								"fdb_qty":value.fdb_qty,
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
			//console.log(e.params.data);
			selectedWOInList.push(e.params.data);
			calculateWOEIn();
			//$("#fin_woein_id_list").trigger("change");

		}).on("select2:unselect",function(e){			
			var data = e.params.data;
			selectedWOInList = $.grep(selectedWOInList,function(v,i){
				return v.id != data.id;
			});		
			calculateWOEIn();	
			//$("#fin_woein_id_list").trigger("change");

		});


		tblCost = $('#tblCost').on('preXhr.dt', function ( e, settings, data ) {
			data.sessionId = "";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"className":'details-control',"orderable":false,"data":null,"defaultContent": '',width:"30px"},
				{"title" : "id","width": "0px",sortable:false,data:"fin_rec_id",visible:false},
				{"title" : "Account",sortable:false,data:"fst_glaccount_code",
					render:function(data,type,row){
						return row.fst_glaccount_code + " - " +row.fst_glaccount_name;
					}
				},
				{"title" : "Total","width": "200px",sortable:false,data:"fdc_total",className:'text-right',
					render:function(data,type,row){
						return App.money_format(data);
					}
				},				
				{"title" : "Action","width": "80px",sortable:false,className:'dt-body-center text-center',
					render: function(data,type,row){

						var action = '<a class="btn-edit" href="#" data-original-title="" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;';
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
			calculateTotal();
		}).on('click','.btn-edit',function(e){
			e.preventDefault();
			var trRow = $(this).parents('tr');
			mdlDetailCost.selectedDetail = tblCost.row(trRow);						
			mdlDetailCost.show();
		}).on('click','.btn-delete',function(e){
			e.preventDefault();
			var trRow = $(this).parents('tr');
			tblCost.row(trRow).remove().draw(false);
		}).on('click', 'td.details-control', function () {
			var tr = $(this).closest('tr');
			var row = tblCost.row( tr );
			if ( row.child.isShown() ) {
				// This row is already open - close it
				row.child.hide();
				tr.removeClass('shown');
			}else {
				// Open this row
				//row.child( format(row.data()) ).show();
				var data = row.data();			
				row.child(function(){
					var sstr="<span style='font-size:8pt'>";
					sstr += "<b>Profit Cost Center :</b>" + data.fst_pcc_name;
					sstr += " | <b>Cost Divisi :</b>" + data.fst_pc_divisi_name;
					sstr += " | <b>Cost Customer :</b>" + data.fst_pc_customer_name;
					sstr += " | <b>Cost Project :</b>" + data.fst_pc_project_name;
					sstr += "</span>"
					return sstr;
				},data).show();
				tr.addClass('shown');
			}
		});

		
		App.fixedSelect2();
		initForm();
	});
</script>

<script type="text/javascript" info="event">
	$(function(){
		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>tr/production/woe_inv/add");
		});


		$("#btnSubmitAjax").click(function(e){
			e.preventDefault();
			submitAjax(0);
		});

		$("#btnPrint").click(function(e){
			e.preventDefault();
			frameVoucher.print("<?=site_url()?>tr/gudang/mutasi/print_voucher/" + $("#fin_mag_id").val());
		});

		$("#btnDelete").click(function(e){
			e.preventDefault();
			deleteAjax(false);			
		});
			
		
		$("#btnList").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>tr/production/woe_inv");
		});

		$("#fst_curr_code").change(function(e){
			var rate = $("#fst_curr_code option:selected").data("rate");
			$("#fdc_exchange_rate_idr").val(App.money_format(rate));
			calculateWOEIn();
		});

		
		$("#fdc_exchange_rate_idr").change(function(e){
			calculateWOEIn();
		});

		$("#fdc_external_cost_per_unit").change(function(e){
			calculateWOEIn();
		});

		
		$("#btn-add-cost").click(function(e){
			e.preventDefault();
			mdlDetailCost.selectedDetail = null;
			mdlDetailCost.clear();
			mdlDetailCost.show();
		});
	});
</script>

<script type="text/javascript" info="function">
	
	
	function submitAjax(confirmEdit){     

		if($("#ttl-cost").val() != $("#ttl-cost-detail").val()){
			alert("<?=lang("Total tidak sama !")?>");
			return;
		}

		var mode = "<?=$mode?>";   
		if (mode == "EDIT" && confirmEdit == 0){
			MdlEditForm.saveCallBack = function(){
				submitAjax(1);
			};		
			MdlEditForm.show();
			return;
		}


		data = $("#frmHeader").serializeArray();

		data.push({			
			name:"fst_curr_code",
			value: $("#fst_curr_code").val()
		});

		data.push({
			name:SECURITY_NAME,
			value: SECURITY_VALUE
		});	

		var detailsCost = new Array();		
		var datas = tblCost.data();
		$.each(datas,function(i,v){
			detailsCost.push(v);
		});

		data.push({
			name:"detailsCost",
			value: JSON.stringify(detailsCost)
		});

		
		
		if (mode == "ADD"){
			url = "<?=site_url()?>tr/production/woe_inv/ajx_add_save";
		}else{			
			url = "<?=site_url()?>tr/production/woe_inv/ajx_edit_save";
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
				}
		});

	}


	function initForm(){
		var mode = "<?=$mode?>";		
		if (mode == "EDIT"){			
			$.ajax({
				url:"<?=site_url()?>tr/production/woe_inv/fetch_data/<?=$fin_woeinv_id?>",
				method:"GET",
			}).done(function(resp){
				if(resp.status=="SUCCESS"){
					var dataH = resp.data.dataH;
					var dataDIn = resp.data.dataItemIn;
					var dataDCost = resp.data.dataItemCost;
					App.autoFillForm(dataH);
					$("#fdt_woeinv_datetime").val(dateTimeFormat(dataH.fdt_woeinv_datetime)).datetimepicker("update");	
					App.addOptionIfNotExist("<option value='"+dataH.fin_wo_id+"'>"+dataH.fst_wo_no+"</option>","fin_wo_id");
					App.addOptionIfNotExist("<option value='"+dataH.fin_supplier_id+"'>"+dataH.fst_supplier_name+"</option>","fin_supplier_id");
					$("#fst_item").val(dataH.fst_item_code + " - " + dataH.fst_item_name);
					App.addOptionIfNotExist("<option value='"+dataH.fst_unit +"'>"+dataH.fst_unit+"</option>","fst_unit");
					$("#fdc_external_cost_per_unit").val(money_format(dataH.fdc_external_cost_per_unit));

					selectedWO = {
						"id": dataH.fin_wo_id,
						"text": dataH.fst_wo_no + ' - ' + dataH.fst_item_name,
						"fin_supplier_id":dataH.fin_supplier_id,
						"fst_supplier_name":dataH.fst_supplier_name,
						"fin_item_id":dataH.fin_item_id,
						"fst_item_code":dataH.fst_item_code,
						"fst_item_name":dataH.fst_item_name,
						"fst_unit":dataH.fst_unit,
						"fdc_external_cost_per_unit":dataH.fdc_external_cost_per_unit,
						"fst_curr_code":dataH.fst_curr_code
					}
					
					var ttlQty=0;
					$.each(dataDIn,function(i,v){
						selectedWOInList.push({
							"id": v.fin_woein_id,
							"text": v.fst_woein_no,
							"fdb_qty":v.fdb_qty,
						});

						App.addOptionIfNotExist("<option value='"+v.fin_woein_id +"' selected>"+v.fst_woein_no+"</option>","fin_woein_id_list");

						ttlQty += parseFloat(v.fdb_qty);
						/*
						selectedWOInList.push({
							"id": v.fin_woein_id,
							"text": v.fst_woein_no,
							"fdb_qty":v.fdb_qty,
						});						
						
						
						//$("")
						*/
					});
					$("#ttl-qty").val(ttlQty);
					$("#ttl-cost").val(money_format(ttlQty * selectedWO.fdc_external_cost_per_unit));


					//Fill Table
					$.each(dataDCost,function(i,v){
						var data = {
							fin_rec_id:v.fin_rec_id,
							fst_glaccount_code: v.fst_glaccount_code,
							fst_glaccount_name: v.fst_glaccount_name,
							fin_pcc_id:v.fin_pcc_id,
							fst_pcc_name: v.fst_pcc_name,
							fin_pc_divisi_id:v.fin_pc_divisi_id,
							fst_pc_divisi_name:v.fst_department_name,
							fin_pc_customer_id:v.fin_pc_customer_id,
							fst_pc_customer_name:v.fst_customer_name,
							fin_pc_project_id:v.fin_pc_project_id,
							fst_pc_project_name:v.fst_project_name,
							fdc_total:v.fdc_total
						};
						tblCost.row.add(data).draw(false);
					});

				}else{
					alert(resp.messages);
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

		var url =  "<?= site_url() ?>tr/production/woe_in/delete/" + $("#fin_woein_id").val();
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
	
	function calculateTotal(){
		var dataList = tblCost.data();
		var total = 0;		
		$.each(dataList,function(i,v){
			total += parseFloat(v.fdc_total);
		});
		$("#ttl-cost-detail").val(money_format(total));
	}

	function calculateWOEIn(){
		console.log(selectedWOInList);
		totalQty = 0;
		totalCost = 0;
		exchangeRate = parseFloat($("#fdc_exchange_rate_idr").val());
		
		var costPerUnit = parseFloat(App.money_parse($("#fdc_external_cost_per_unit").val()));

		$.each(selectedWOInList,function(i,v){
			totalQty += parseFloat(v.fdb_qty);
			totalCost += parseFloat(v.fdb_qty) * costPerUnit * exchangeRate;
		});
		$("#ttl-qty").val(totalQty);
		$("#ttl-cost").val(money_format(totalCost));
	}
	
</script>
<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
