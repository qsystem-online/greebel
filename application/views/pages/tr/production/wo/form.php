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
	<h1><?=lang("Master Production Schedule")?><small><?=lang("form")?></small></h1>
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
					<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Group")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
				</div>
			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmHeader" class="form-horizontal" action="" method="POST" >
				<div class="box-body">
					<input type="hidden" id="fin_wo_id" name="fin_wo_id" value="<?=$fin_wo_id?>"/>

					<div class="form-group">
						<label for="fst_wo_no" class="col-md-2 control-label"><?=lang("WO")?> #</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_wo_no" placeholder="<?=lang("WO No")?>" name="fst_wo_no" value="<?=$fst_wo_no?>"/>
							<div id="fst_wo_no_err" class="text-danger"></div>
						</div>								

						<label for="fdt_wo_datetime" class="col-md-2 control-label"><?=lang("Tanggal")?></label>
						<div class="col-md-4">
							<input type="text" class="form-control datetimepicker text-right" id="fdt_wo_datetime" placeholder="<?=lang("MPS Datetime")?>" name="fdt_wo_datetime" value=""/>
							<div id="fdt_wo_datetime_err" class="text-danger"></div>
						</div>								
                    </div>  

					<div class="form-group">
						<label for="fst_wo_type" class="col-md-2 control-label"><?=lang("WO Type")?></label>
						<div class="col-md-4">
							<select id="fst_wo_type" name="fst_wo_type" class='form-control'>
								<option value='Internal'>Internal</option>
								<option value='External'>External</option>
							</select>
							<div id="fst_wo_type_err" class="text-danger"></div>
						</div>	
						<label for="fdt_wo_target_date" class="col-md-2 control-label"><?=lang("Target")?></label>
						<div class="col-md-4">
							<input type="text" class="form-control datepicker text-right" id="fdt_wo_target_date" name="fdt_wo_target_date" placeholder="<?=lang("Target Date")?>" value=""/>
							<div id="fdt_wo_target_date_err" class="text-danger"></div>
						</div>							
					</div>
					
					<div class="form-group type-external" style="display:none">					
						<label for="fin_supplier_id" class="col-md-2 control-label"><?=lang("Supplier")?></label>
						<div class="col-md-10">
							<select  class="form-control" id="fin_supplier_id" name="fin_supplier_id" placeholder="<?=lang("Supplier")?>" style="width:100%"></select>
							<div id="fin_supplier_id_err" class="text-danger"></div>
						</div>						
					</div>

					<div class="form-group type-external" style="display:none">					
						<label for="fst_curr_code" class="col-md-2 control-label"><?=lang("Currency Code")?></label>
						<div class="col-md-4">
							<select  class="form-control " id="fst_curr_code" name="fst_curr_code">
								<?php
									$defCurr = $this->mscurrencies_model->getDefaultCurrencyCode();
									$currList = $this->mscurrencies_model->getCurrencyList();
									foreach($currList as $curr){
										$selected ="";
										if($curr->fst_curr_code == $defCurr){
											$selected ="SELECTED";
										}
										echo "<option value='$curr->fst_curr_code' $selected>$curr->fst_curr_code - $curr->fst_curr_name</option>";
									}

								?>
							</select>
							<div id="fst_curr_code_err" class="text-danger"></div>
						</div>

						<label for="fdc_external_cost_per_unit" class="col-md-2 control-label"><?=lang("Cost / unit")?></label>
						<div class="col-md-4">
							<input  type="TEXT" class="form-control money" id="fdc_external_cost_per_unit" name="fdc_external_cost_per_unit" value="0"></input>
							<div id="fdc_external_cost_per_unit_err" class="text-danger"></div>
						</div>
					</div>


					<div class="form-group">					
						<label for="fin_item_group_id" class="col-md-2 control-label"><?=lang("Group Item")?></label>
						<div class="col-md-10">
							<select  class="form-control" id="fin_item_group_id" placeholder="<?=lang("Group Item")?>" style="width:100%"></select>
							<div id="fin_item_group_id_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">					
						<label for="fin_item_id" class="col-md-2 control-label"><?=lang("Item")?></label>
						<div class="col-md-10">
							<select  class="form-control" id="fin_item_id" name="fin_item_id" placeholder="<?=lang("Item")?>" style="width:100%"></select>
							<div id="fin_item_id_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">					
						<label for="fst_unit" class="col-md-2 control-label"><?=lang("Unit")?></label>
						<div class="col-md-6">
							<select  class="form-control" id="fst_unit" name="fst_unit" placeholder="<?=lang("Unit Production")?>" style="width:100%"></select>
							<div id="fst_unit_err" class="text-danger"></div>
						</div>
						<label for="fdb_qty" class="col-md-2 control-label"><?=lang("Qty")?></label>
						<div class="col-md-2">
							<input class="form-control text-right" id="fdb_qty" name="fdb_qty" placeholder="<?=lang("qty")?>" value="1"></input>
							<div id="fdb_qty_err" class="text-danger"></div>
						</div>
					</div>
					

					<div class="form-group">
						<label for="fin_warehouse_target" class="col-md-2 control-label"><?=lang("Gudang Tujuan")?></label>
						<div class="col-md-10">
							<select  class="form-control" id="fin_warehouse_target" name="fin_warehouse_target" >
								<?php
									$listWarehouse = $this->mswarehouse_model->getNonLogisticWarehouseList();
									foreach($listWarehouse  as $warehouse){
										echo "<option value='$warehouse->fin_warehouse_id'>$warehouse->fst_warehouse_name</option>";
									}
								?>
							</select>
							<div id="fst_notes_err" class="text-danger"></div>
						</div>		
					</div>   


					<div class="form-group">
						<label for="fst_notes" class="col-md-2 control-label"><?=lang("Notes")?></label>
						<div class="col-md-10">
							<textarea type="text" class="form-control" id="fst_notes" name="fst_notes" ></textarea>
							<div id="fst_notes_err" class="text-danger"></div>
						</div>		
					</div>   					

					<div class="form-group">												
						<div class="col-sm-12">
							<ul class="nav nav-tabs" id="tabDetail">
								<li class="active"><a data-toggle="tab" href="#bom-master">BOM Master</a></li>																
								<li><a data-toggle="tab" href="#bom-wo">BOM WO</a></li>
								<li><a data-toggle="tab" href="#mr-wo">Material Requirment WO</a></li>
								<li><a data-toggle="tab" href="#activity">Activities</a></li>
								<li><a data-toggle="tab" href="#mag-pag">MAG / PAG</a></li>
								<li><a data-toggle="tab" href="#rmout">Info RM-OUT</a></li>
								<li><a data-toggle="tab" href="#lhp">Info LHP</a></li>
								<li><a data-toggle="tab" href="#batchno">Info Batch-No</a></li>
							</ul>

							<div class="tab-content" style="padding:5px">
								<div id="bom-master" class="tab-pane fade in active">
									<label class="col-sm-12 control-label">*Daftar BOM Berdasarkan scala item / perbasic unit (<label class="infBOMMaster"></label>)</label>									
									<table id="tblBOMMaster" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
								</div>
								<div id="bom-wo" class="tab-pane fade">
									<label class="col-sm-12 control-label">*Daftar BOM Berdasarkan scala item / perbasic unit (<label class="infBOMMaster"></label>)</label>									
									<div style="width:100%;text-align:right">
										<button id="btn-add-bom-wo" class="btn btn-primary btn-sm">
											<i class="fa fa-cart-plus" aria-hidden="true"></i>
											<?=lang("Tambah Item")?>
										</button>
									</div>
									<table id="tblBOMWO" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
								</div>
								<div id="mr-wo" class="tab-pane fade">
									<label class="col-sm-12 control-label">*Daftar Material Requirment total production / per production unit</label>									
									<div class="col-sm-12 text-right" >
										<button id="btn-calculate-material" class="btn btn-primary btn-sm">
											<i class="fa fa-cogs" aria-hidden="true"></i>
											<?=lang("Calculate")?>
										</button>
									</div>
									<table id="tblRMWO" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
									<br>
									<div class="form-group">
										<label for="estimate-hpp-unit" class="col-md-2 control-label"><?=lang("Estimasi HPP Per-Unit")?></label>
										<div class="col-md-4">
											<input type="text" class="form-control money" id="estimate-hpp-unit" readonly value="0"/>
										</div>		
										<label for="estimate-hpp-total" class="col-md-2 control-label"><?=lang("Estimasi HPP Total")?></label>
										<div class="col-md-4">
											<input type="text" class="form-control money" id="estimate-hpp-total" readonly value="0"/>
										</div>		
									</div>   

								</div>

								<div id="activity" class="tab-pane fade">
									<div class="form-group">
										<label for="fin_activity_group_id" class="col-md-2 control-label"><?=lang("Activity Group")?></label>
										<div class="col-md-10">
											<select id="fin_activity_group_id" name="fin_activity_group_id" class="form-control">
												<?php
													$groupList = $this->msactivitygroups_model->getAllList();													
													foreach($groupList as $group){
														echo "<option value='$group->fin_activity_group_id'>$group->fst_activity_group_name</option>";
													}
												?>
											</select>
											<div id="fin_activity_group_id_err" class="text-danger"></div>
										</div>		
									</div>   
									<div class="form-group">
										<div class="col-md-12 text-right">
											<button id="btn-add-activity" class="btn btn-primary btn-sm">
												<i class="fa fa-cart-plus" aria-hidden="true"></i>
												<?=lang("Tambah Activity")?>
											</button>
										</div>
										<div class="col-md-12">
											<table id="tblActivity" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
										</div>
									</div>
								</div>

								<div id="mag-pag" class="tab-pane fade">
									<div class="form-group">
										<div class="col-md-12">
											<table id="tblMAGPAG" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
										</div>
									</div>
								</div>

								<div id="rmout" class="tab-pane fade">
									<div class="form-group">
										<div class="col-md-12">
											<table id="tblRmout" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
										</div>
									</div>
								</div>

								<div id="lhp" class="tab-pane fade">
									<div class="form-group">
										<div class="col-md-12">
											<table id="tblLHP" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
										</div>
									</div>
								</div>
								
								<div id="batchno" class="tab-pane fade">									
									<div class="form-group">
										<div class="col-md-12">
											<table id="tblBatchno" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
										</div>
									</div>
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
<?php
    echo $mdlItemGroup;
?>

<div id="mdlDetailBOMWO" class="modal fade in" role="dialog" style="display:none">
	<div class="modal-dialog" style="display:table;width:600px">
		<!-- modal content -->
		<div class="modal-content">
			<div class="modal-header" style="padding:7px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 5px;border-top-right-radius: 5px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Tambah BOM WO")?></h4>
			</div>

			<div class="modal-body">				        
				<form class="form-horizontal">
				
					<div class="form-group">
						<label class="col-md-2 control-label"><?=lang("Item")?>:</label>						
						<div class="col-md-10">
							<select class="form-control" id="d-bom-wo-fin_item_id" style="width:100%"></select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label"><?=lang("Unit")?>:</label>						
						<div class="col-md-4">
							<select class="form-control" id="d-bom-wo-fst_unit" style="width:100%"></select>
						</div>
						<label class="col-md-2 control-label"><?=lang("Qty")?>:</label>						
						<div class="col-md-4">
							<input type="TEXT" class="form-control text-right" id="d-bom-wo-fdb_qty" value="1"/>
						</div>
					</div>					
				</form>				
			</div>

			<div class="modal-footer">
				<button id="d-bom-wo-btn-save" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Add")?></button>
				<button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
			</div>
		</div>
	</div>

	<script type="text/javascript" info="define">

		var mdlDetailBOMWO = {
			selectedDetail:null,
			selectedItem:null,			
			show:function(){
				if (mdlDetailBOMWO.selectedDetail != null){
					var data = mdlDetailBOMWO.selectedDetail.data();
					console.log(data);

					mdlDetailBOMWO.selectedItem = {
						fin_item_id:data.fin_item_id,
						fst_item_code:data.fst_item_code,
						fst_item_name:data.fst_item_name
					}

					$("#d-bom-wo-fin_item_id").empty();
					App.addOptionIfNotExist("<option value='" + mdlDetailBOMWO.selectedItem.fin_item_id + "'>"+mdlDetailBOMWO.selectedItem.fst_item_code + " - " + mdlDetailBOMWO.selectedItem.fst_item_name +"</option>","d-bom-wo-fin_item_id");
					$("#d-bom-wo-fin_item_id").trigger("change");

					$("#d-bom-wo-fst_unit").empty();
					App.addOptionIfNotExist("<option value='" + data.fst_unit + "'>"+ data.fst_unit +"</option>","d-bom-wo-fst_unit");
					$("#d-bom-wo-fst_unit").trigger("change");

					$("#d-bom-wo-fdb_qty").val(data.fdb_qty);					
				}else{
					mdlDetailBOMWO.clear();
				}				
				$("#mdlDetailBOMWO").modal("show");
			},
			hide:function(){
				$("#mdlDetailBOMWO").modal("hide");
			},
			clear:function(){	
				$("#d-bom-wo-fin_item_id").empty();
				$("#d-bom-wo-fin_item_id").trigger("change");
				$("#d-bom-wo-fst_unit").empty();
				$("#d-bom-wo-fst_unit").trigger("change");
				$("#d-bom-wo-fdb_qty").val(1);

				mdlDetailBOMWO.selectedDetail = null;
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
			
			$("#btn-add-bom-wo").click(function(e){
				e.preventDefault();
				mdlDetailBOMWO.selectedDetail = null;
				mdlDetailBOMWO.show();
			});	

			$("#d-bom-wo-btn-save").click(function(e){								
				var data= {};
				var mode="new";
				if (mdlDetailBOMWO.selectedDetail != null){
					data = mdlDetailBOMWO.selectedDetail.data();
					mode= "update";
				}else{
					data.fin_rec_id = 0;
				}				
				data.fin_item_id = mdlDetailBOMWO.selectedItem.fin_item_id;
				data.fst_item_code = mdlDetailBOMWO.selectedItem.fst_item_code;
				data.fst_item_name = mdlDetailBOMWO.selectedItem.fst_item_name;
				data.fst_unit = $("#d-bom-wo-fst_unit").val();
				data.fdb_qty = $("#d-bom-wo-fdb_qty").val();

				if(mode == "update"){
					tblBOMWO.row(mdlDetailBOMWO.selectedDetail).data(data);
				}else{
					tblBOMWO.row.add(data);
				}
				tblBOMWO.draw(false);
				mdlDetailBOMWO.clear();		
				mdlDetailBOMWO.hide();		
			});
		});

	</script>

	<script type="text/javascript" info="function">
		function DELETE_JUGA_regenerateDetailForm(){
			var beoM01 = parseFloat($("#fdb_last_period_qty").text()) - parseFloat($("#fdb_qty_mts_m01").text()) + parseFloat($("#fdb_qty_m01").val());
			var beoM02 = beoM01 - parseFloat($("#fdb_qty_mts_m02").text()) + parseFloat($("#fdb_qty_m02").val());
			var beoM03 = beoM02 - parseFloat($("#fdb_qty_mts_m03").text()) + parseFloat($("#fdb_qty_m03").val());
			var beoM04 = beoM03 - parseFloat($("#fdb_qty_mts_m04").text()) + parseFloat($("#fdb_qty_m04").val());
			var beoM05 = beoM04 - parseFloat($("#fdb_qty_mts_m05").text()) + parseFloat($("#fdb_qty_m05").val());
			var beoM06 = beoM05 - parseFloat($("#fdb_qty_mts_m06").text()) + parseFloat($("#fdb_qty_m06").val());
			var beoM07 = beoM06 - parseFloat($("#fdb_qty_mts_m07").text()) + parseFloat($("#fdb_qty_m07").val());
			var beoM08 = beoM07 - parseFloat($("#fdb_qty_mts_m08").text()) + parseFloat($("#fdb_qty_m08").val());
			var beoM09 = beoM08 - parseFloat($("#fdb_qty_mts_m09").text()) + parseFloat($("#fdb_qty_m09").val());
			var beoM10 = beoM09 - parseFloat($("#fdb_qty_mts_m10").text()) + parseFloat($("#fdb_qty_m10").val());
			var beoM11 = beoM10 - parseFloat($("#fdb_qty_mts_m11").text()) + parseFloat($("#fdb_qty_m11").val());
			var beoM12 = beoM11 - parseFloat($("#fdb_qty_mts_m12").text()) + parseFloat($("#fdb_qty_m12").val());

			$("#fdb_beo_m01").text(beoM01);
			$("#fdb_beo_m02").text(beoM02);
			$("#fdb_beo_m03").text(beoM03);
			$("#fdb_beo_m04").text(beoM04);
			$("#fdb_beo_m05").text(beoM05);
			$("#fdb_beo_m06").text(beoM06);
			$("#fdb_beo_m07").text(beoM07);
			$("#fdb_beo_m08").text(beoM08);
			$("#fdb_beo_m09").text(beoM09);
			$("#fdb_beo_m10").text(beoM10);
			$("#fdb_beo_m11").text(beoM11);
			$("#fdb_beo_m12").text(beoM12);			

		}
	</script>

</div>

<div id="mdlDetailActivity" class="modal fade in" role="dialog" style="display:none">
	<div class="modal-dialog" style="display:table;width:600px">
		<!-- modal content -->
		<div class="modal-content">
			<div class="modal-header" style="padding:7px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 5px;border-top-right-radius: 5px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Tambah Activity")?></h4>
			</div>

			<div class="modal-body">				        
				<form class="form-horizontal">
				
					<div class="form-group">
						<label class="col-md-2 control-label"><?=lang("Activity")?>:</label>						
						<div class="col-md-10">
							<select class="form-control" id="d-activity-fin_activity_id" style="width:100%"></select>
						</div>
					</div>					
				</form>				
			</div>

			<div class="modal-footer">
				<button id="d-activity-btn-save" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Add")?></button>
				<button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
			</div>
		</div>
	</div>

	<script type="text/javascript" info="define">

		var mdlDetailActivity = {
			selectedDetail:null,
			selectedActivity:null,			
			show:function(){
				if (mdlDetailBOMWO.selectedDetail != null){
					var data = mdlDetailBOMWO.selectedDetail.data();
					console.log(data);

					mdlDetailBOMWO.selectedItem = {
						fin_item_id:data.fin_item_id,
						fst_item_code:data.fst_item_code,
						fst_item_name:data.fst_item_name
					}

					$("#d-bom-wo-fin_item_id").empty();
					App.addOptionIfNotExist("<option value='" + mdlDetailBOMWO.selectedItem.fin_item_id + "'>"+mdlDetailBOMWO.selectedItem.fst_item_code + " - " + mdlDetailBOMWO.selectedItem.fst_item_name +"</option>","d-bom-wo-fin_item_id");
					$("#d-bom-wo-fin_item_id").trigger("change");

					$("#d-bom-wo-fst_unit").empty();
					App.addOptionIfNotExist("<option value='" + data.fst_unit + "'>"+ data.fst_unit +"</option>","d-bom-wo-fst_unit");
					$("#d-bom-wo-fst_unit").trigger("change");

					$("#d-bom-wo-fdb_qty").val(data.fdb_qty);					
				}else{
					mdlDetailBOMWO.clear();
				}				
				$("#mdlDetailActivity").modal("show");
			},
			hide:function(){
				$("#mdlDetailActivity").modal("hide");
			},
			clear:function(){	
				$("#d-bom-wo-fin_item_id").empty();
				$("#d-bom-wo-fin_item_id").trigger("change");
				$("#d-bom-wo-fst_unit").empty();
				$("#d-bom-wo-fst_unit").trigger("change");
				$("#d-bom-wo-fdb_qty").val(1);

				mdlDetailBOMWO.selectedDetail = null;
			},			
		};

	</script>

	
	<script type="text/javascript" info="init">
		$(function(){	
			$("#d-activity-fin_activity_id").select2({
				ajax:{
					url:"<?=site_url()?>tr/production/wo/ajxGetActivityList",
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
									"id": value.fin_activity_id,
									"text": value.fst_name,									
									"fst_team":value.fst_team,
									"fst_type":value.fst_type,
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
				mdlDetailActivity.selectedActivity = e.params.data;
			});		
		});
	</script>

	<script type="text/javascript" info="event">		
		$(function(){
			
			$("#btn-add-activity").click(function(e){
				e.preventDefault();
				mdlDetailActivity.selectedDetail = null;
				mdlDetailActivity.show();
			});	

			
			$("#d-activity-btn-save").click(function(e){								
				var activity = mdlDetailActivity.selectedActivity;

				var data = {
					fin_rec_id:0,
					fin_activity_id:activity.id,
					fst_name:activity.text,
					fst_team:activity.fst_team,
					fst_type:activity.fst_type
				}
				tblActivity.row.add(data);

				tblActivity.draw(false);
				mdlDetailActivity.clear();		
				mdlDetailActivity.hide();		
			});
		});

	</script>

	<script type="text/javascript" info="function">
		
	</script>

</div>

<?php echo $mdlEditForm ?>
<?php echo $mdlPrint ?>
<?php echo $mdlJurnal ?>

<script type="text/javascript" info="define">
	var selectedItem;
	var selectedDetail;	
	var tblBOMMaster;
	var tblBOMWO;
	var tblRMWO;
	var tblActivity;
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
		$("#fdt_wo_target_date").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");
		

		$("#fin_supplier_id").select2({
			ajax:{
				url:"<?=site_url()?>tr/production/wo/ajxGetSupplierList",
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


		$("#fin_item_group_id").select2({
            width: '100%',
            ajax: {
                url: '<?= site_url() ?>master/item/get_data_ItemGroupId',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    data2 = [];
                    $.each(data, function(index, value) {
                        data2.push({
                            "id": value.fin_item_group_id,
                            "text": value.fst_item_group_name
                        });
                    });
                    return {
                        results: data2
                    };
                },
                cache: true,
            }
        }).on("select2:open",function(e){
            e.preventDefault();
			$(this).select2("close");
			var leafOnly =true;
            showItemGroup(leafOnly,function(node){
                //consoleLog(node);                
                $("#fin_item_group_id").empty();
                var newOption = new Option(node.text,node.id, false, false);
				$('#fin_item_group_id').append(newOption).trigger('change');
            });
		});

		$("#fin_item_id").select2({
			ajax:{
				url:"<?=site_url()?>tr/production/wo/ajxGetItemList",
				data: function (params) {
					params.fin_item_group_id = $("#fin_item_group_id").val();
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
								"fdc_scale_for_bom":value.fdc_scale_for_bom,
								"fst_basic_unit":value.fst_basic_unit
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
			selectedItem = e.params.data;
			$(".infBOMMaster").text("1:" + selectedItem.fdc_scale_for_bom + "/" + selectedItem.fst_basic_unit);			
			getItemBOM();
		});

		$("#fst_unit").select2({
			minimumInputLength: 0,
			minimumResultsForSearch: -1,
			ajax:{
				url:"<?=site_url()?>tr/production/wo/ajxGetUnits",
				data: function (params) {
					params.fin_item_id = $("#fin_item_id").val();
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

		$("#fin_activity_group_id").select2({
			width:"100%",
			placeholder: "Select activity group",
    		allowClear: true,
		}).val(null).trigger("change");				
		

		tblBOMMaster = $('#tblBOMMaster').on('preXhr.dt', function ( e, settings, data ) {
			data.sessionId = "";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "id","width": "0px",sortable:false,data:"fin_rec_id",visible:false},
				{"title" : "Item","width": "300px",sortable:false,data:"fin_item_id",
					"render":function(data,type,row){
						return row.fst_item_code + " - " + row.fst_item_name; 
					}
				},
				{"title" : "Unit","width": "50px",sortable:false,data:"fst_unit"},
				{"title" : "Qty","width": "10px",sortable:false,data:"fdb_qty",className:"text-right"},
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
		});

		tblBOMWO = $('#tblBOMWO').on('preXhr.dt', function ( e, settings, data ) {
			data.sessionId = "";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "id","width": "0px",sortable:false,data:"fin_rec_id",visible:false},
				{"title" : "Item","width": "300px",sortable:false,data:"fin_item_id",
					"render":function(data,type,row){
						return row.fst_item_code + " - " + row.fst_item_name; 
					}
				},
				{"title" : "Unit","width": "50px",sortable:false,data:"fst_unit"},
				{"title" : "Qty","width": "10px",sortable:false,data:"fdb_qty",className:"text-right"},
				{"title" : "Action","width": "80px",sortable:false,className:'dt-body-center text-center',
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
			//generateMaterialRequirment();
		}).on('click','.btn-edit',function(e){
			e.preventDefault();
			var trRow = $(this).parents('tr');
			mdlDetailBOMWO.selectedDetail = tblBOMWO.row(trRow);						
			mdlDetailBOMWO.show();
		}).on('click','.btn-delete',function(e){
			e.preventDefault();
			var trRow = $(this).parents('tr');
			tblBOMWO.row(trRow).remove().draw(false);
		});

		tblRMWO = $('#tblRMWO').on('preXhr.dt', function ( e, settings, data ) {
			data.sessionId = "";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "id","width": "0px",sortable:false,data:"fin_rec_id",visible:false},
				{"title" : "Item","width": "300px",sortable:false,data:"fin_item_id",
					"render":function(data,type,row){
						return row.fst_item_code + " - " + row.fst_item_name; 
					}
				},
				{"title" : "Unit","width": "50px",sortable:false,data:"fst_unit"},
				{"title" : "Qty","width": "10px",sortable:false,data:"fdb_qty",className:"text-right"},
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
		});

		tblActivity = $("#tblActivity").on('preXhr.dt', function ( e, settings, data ) {
			data.sessionId = "";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "id","width": "0px",sortable:false,data:"fin_rec_id",visible:false},
				{"title" : "Activity","width": "300px",sortable:false,data:"fin_activity_id",
					"render":function(data,type,row){
						return row.fst_name; 
					}
				},
				{"title" : "Team","width": "50px",sortable:false,data:"fst_team"},
				{"title" : "Type","width": "50px",sortable:false,data:"fst_type"},
				{"title" : "Action","width": "80px",sortable:false,className:'dt-body-center text-center',
					render: function(data,type,row){
						//var action = '<a class="btn-edit" href="#" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp;';												
						var action = '<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';						
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
		}).on('click','.btn-delete',function(e){
			e.preventDefault();
			var trRow = $(this).parents('tr');
			tblActivity.row(trRow).remove().draw(false);
		});

		tblMAGPAG = $("#tblMAGPAG").on('preXhr.dt', function ( e, settings, data ) {
			data.sessionId = "";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "id","width": "0px",sortable:false,data:"fin_mag_id",visible:false},
				{"title" : "MAG","width": "70px",sortable:false,data:"fst_mag_no"},
				{"title" : "Tgl MAG","width": "80px",sortable:false,data:"fdt_mag_datetime"},
				{"title" : "Item","width": "100px",sortable:false,data:"fst_item_name"},
				{"title" : "Unit","width": "50px",sortable:false,data:"fst_unit"},
				{"title" : "Qty","width": "50px",sortable:false,data:"fdb_qty"},
				{"title" : "PAG","width": "70px",sortable:false,data:"fst_mag_confirm_no"},
				{"title" : "Tgl PAG","width": "80px",sortable:false,data:"fdt_mag_confirm_datetime"},
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
		});


		tblRmout = $("#tblRmout").on('preXhr.dt', function ( e, settings, data ) {
			data.sessionId = "";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "id","width": "0px",sortable:false,data:"fin_rmout_id",visible:false},
				{"title" : "RM-Out","width": "70px",sortable:false,data:"fst_rmout_no"},
				{"title" : "Tgl RM_Out","width": "80px",sortable:false,data:"fdt_rmout_datetime"},
				{"title" : "Item","width": "100px",sortable:false,data:"fst_item_name"},
				{"title" : "Unit","width": "50px",sortable:false,data:"fst_unit"},
				{"title" : "Qty","width": "50px",sortable:false,data:"fdb_qty"},
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
		});


		tblLHP = $("#tblLHP").on('preXhr.dt', function ( e, settings, data ) {
			data.sessionId = "";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "id","width": "0px",sortable:false,data:"fin_lhp_id",visible:false},
				{"title" : "LHP","width": "70px",sortable:false,data:"fst_lhp_no"},
				{"title" : "Tgl LHP","width": "80px",sortable:false,data:"fdt_lhp_datetime"},
				{"title" : "Batch No","width": "80px",sortable:false,data:"fst_wobatchno_no"},
				{"title" : "Warehouse","width": "80px",sortable:false,data:"fst_warehouse_name"},
				{"title" : "Gramasi","width": "80px",sortable:false,data:"fdb_gramasi"},
				{"title" : "Item","width": "100px",sortable:false,data:"fst_item_name"},
				{"title" : "Unit","width": "50px",sortable:false,data:"fst_unit"},
				{"title" : "Qty","width": "50px",sortable:false,data:"fdb_qty"},
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
		});

        App.fixedSelect2();
		initForm();
	});
</script>

<script type="text/javascript" info="event">
	$(function(){
		$("#btnNew").click(function(e){
			//e.preventDefault();
			window.location.replace("<?=site_url()?>tr/production/wo/add");
		});
		$("#btnPrint").click(function(e){
			e.preventDefault();
			frameVoucher.print("<?=site_url()?>tr/gudang/mutasi/print_voucher/" + $("#fin_mag_id").val());
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
			window.location.replace("<?=site_url()?>tr/production/wo");
		});

		$("#fst_wo_type").change(function(e){
			if ($("#fst_wo_type").val() == "External"){
				$(".type-external").show();
			}else{
				$(".type-external").hide();
			}
		});

		$("#fst_unit,#fdb_qty").change(function(e){
			e.preventDefault();
			//generateMaterialRequirment();
		});

		$("#btn-calculate-material").click(function(e){
			e.preventDefault();
			generateMaterialRequirment();
		});

		$(document).on("shown.bs.tab","#tabDetail a",function(e){
			//var tabActive = $(e.target).text();
			//if (tabActive == "Material Requirment WO"){
			//	generateMaterialRequirment();
			//}

			$($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
		});


		$("#fin_activity_group_id").change(function(e){
			e.preventDefault();
			$.ajax({
				url:"<?=site_url()?>tr/production/wo/ajxGetActivityList",
				method:"GET",
				data:{
					term:"",
					fin_activity_group_id:$("#fin_activity_group_id").val(),
				}
			}).done(function(resp){
				if(resp.status="SUCCESS"){
					var details = resp.data;
					tblActivity.clear();

					$.each(details,function(i,v){						
						var data = {
							fin_rec_id:0,
							fin_activity_id:v.fin_activity_id,
							fst_name:v.fst_name,
							fst_team:v.fst_team,
							fst_type:v.fst_type
						};
						tblActivity.row.add(data);
					});
					tblActivity.draw(false);
				}else{
					alert(resp.messages);
				}
			});
		});	
	});
</script>

<script type="text/javascript" info="function">
	
	function submitAjax(confirmEdit){     
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
			name:SECURITY_NAME,
			value: SECURITY_VALUE
		});	
		
		//get tabel BOM WO
		var detailsBOMWO = new Array();			
		var datas = tblBOMWO.data();
		$.each(datas,function(i,v){
			detailsBOMWO.push(v);
		});
		data.push({
			name:"detailsBOMWO",
			value: JSON.stringify(detailsBOMWO)
		});

		//get tabel Activity
		var detailsActivity = new Array();			
		datas = tblActivity.data();
		$.each(datas,function(i,v){
			detailsActivity.push(v);
		});
		data.push({
			name:"detailsActivity",
			value: JSON.stringify(detailsActivity)
		});

		
		

		if (mode == "ADD"){
			url = "<?=site_url()?>tr/production/wo/ajx_add_save";
		}else{			
			url = "<?=site_url()?>tr/production/wo/ajx_edit_save";
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
					$("#fin_assembling_id").val(data.insert_id);					
				}
        });

	}
	
	function initForm(){
		var mode = "<?=$mode?>";		
		if (mode == "EDIT"){			
			App.blockUIOnAjaxRequest();
			$.ajax({
				url:"<?= site_url() ?>tr/production/wo/fetch_data/<?=$fin_wo_id?>",
			}).done(function(resp){	
				if (resp.status == "SUCCESS"){
					var data = resp.data;
					var header = data.header;
					if (header != null){
						App.autoFillForm(header);
						$("#fdt_wo_datetime").val(dateTimeFormat(header.fdt_wo_datetime)).datetimepicker("update");	
						$("#fdt_wo_target_date").val(dateFormat(header.fdt_wo_target_date)).datepicker("update");	

						App.addOptionIfNotExist("<option value='"+header.fin_item_group_id+"'>"+header.fst_item_group_name +"</option>","fin_item_group_id");
						App.addOptionIfNotExist("<option value='"+header.fin_item_id+"'>"+header.fst_item_name +"</option>","fin_item_id");
						App.addOptionIfNotExist("<option value='"+header.fst_unit+"'>"+header.fst_unit +"</option>","fst_unit");
						

						$(".infBOMMaster").text("1:" + header.fdc_scale_for_bom + "/" + header.fst_basic_unit);			

						var detailsBOMMaster = data.detailsBOMMaster;
						fillBOMMaster(detailsBOMMaster);

						var detailsBOMWO = data.detailsBOMWO;
						$.each(detailsBOMWO,function(i,v){
							var data = {
								fin_rec_id:v.fin_rec_id,
								fin_item_id:v.fin_item_id,
								fst_item_code:v.fst_item_code,
								fst_item_name:v.fst_item_name,
								fst_unit:v.fst_unit,
								fdb_qty:v.fdb_qty
							};

							tblBOMWO.row.add(data);
						});
						tblBOMWO.draw(false);

						var detailsActivity = data.detailsActivity;
						$.each(detailsActivity,function(i,v){
							var data = {
								fin_rec_id:v.fin_rec_id,
								fin_activity_id:v.fin_activity_id,
								fst_name:v.fst_name,
								fst_team:v.fst_team,
								fst_type:v.fst_type,
							};

							tblActivity.row.add(data);
						});
						tblActivity.draw(false);
						
						var detailsMAGPAG = data.detailsMAGPAG;
						$.each(detailsMAGPAG,function(i,v){
							var data = {
								fin_mag_id:v.fin_mag_id,
								fst_mag_no:v.fst_mag_no,
								fdt_mag_datetime:v.fdt_mag_datetime,
								fst_item_name:v.fst_item_name,
								fst_unit:v.fst_unit,
								fdb_qty:v.fdb_qty,
								fst_mag_confirm_no:v.fst_mag_confirm_no,
								fdt_mag_confirm_datetime:v.fdt_mag_confirm_datetime
							};
							tblMAGPAG.row.add(data);
						});
						tblMAGPAG.draw(false);


						var detailsRmout = data.detailsRmout;
						$.each(detailsRmout,function(i,v){
							var data = {
								fin_rmout_id:v.fin_rmout_id,
								fst_rmout_no:v.fst_rmout_no,
								fdt_rmout_datetime:v.fdt_rmout_datetime,
								fst_item_name:v.fst_item_name,
								fst_unit:v.fst_unit,
								fdb_qty:v.fdb_qty,
							};
							tblRmout.row.add(data);
						});
						tblRmout.draw(false);

					
						var detailsLHP = data.detailsLHP;
						$.each(detailsLHP,function(i,v){
							var data = {
								fin_lhp_id:v.fin_lhp_id,
								fst_lhp_no:v.fst_lhp_no,
								fdt_lhp_datetime:v.fdt_lhp_datetime,
								fst_wobatchno_no:v.fst_wobatchno_no,
								fst_warehouse_name:v.fst_warehouse_name,
								fdb_gramasi:v.fdb_gramasi,
								fst_item_name:v.fst_item_name,
								fst_unit:v.fst_unit,
								fdb_qty:v.fdb_qty,
							};
							tblLHP.row.add(data);
						});
						tblLHP.draw(false);



						var detailsBatchno = data.detailsBatchno;
						$.each(detailsBatchno,function(i,v){
							var data = {
								fin_wobatchno_id:v.fin_wobatchno_id,
								fst_wobatchno_no:v.fst_wobatchno_no,
								fdt_wobatchno_datetime:v.fdt_wobatchno_datetime,
								fst_notes:v.fst_notes,
								fst_active:v.fst_active
							};
							tblBatchno.row.add(data);
						});
						tblBatchno.draw(false);
						


						generateMaterialRequirment();
						
					}else{
						alert("<?=lang("ID transaksi tidak dikenal")?>");
						return false;
					}					
				}else{
					alert(resp.message);				
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

		var url =  "<?= site_url() ?>tr/production/wo/delete/" + $("#fin_wo_id").val();
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

	function getItemBOM(finItemId){

		$.ajax({
			url:"<?=site_url()?>tr/production/wo/ajxGetItemBOM",
			method:"GET",
			data:{
				fin_item_id:$("#fin_item_id").val()
			}
		}).done(function(resp){
			if (resp.status == "SUCCESS"){
				fillBOMMaster(resp.data);
				fillBOMWO(resp.data);
			}
		});
	}

	function fillBOMMaster(details){		
		tblBOMMaster.clear();
		$.each(details,function(i,v){
			var data = {
				fin_rec_id:0,
				fin_item_id:v.fin_item_id,
				fst_item_code:v.fst_item_code,
				fst_item_name:v.fst_item_name,
				fst_unit:v.fst_unit,
				fdb_qty:v.fdb_qty
			}
			tblBOMMaster.row.add(data);
		});
		tblBOMMaster.draw(false);
	}

	function fillBOMWO(details){
		tblBOMWO.clear();
		$.each(details,function(i,v){
			var data = {
				fin_rec_id:0,
				fin_item_id:v.fin_item_id,
				fst_item_code:v.fst_item_code,
				fst_item_name:v.fst_item_name,
				fst_unit:v.fst_unit,
				fdb_qty:v.fdb_qty
			}
			tblBOMWO.row.add(data);
		});
		tblBOMWO.draw(false);
	}

	function generateMaterialRequirment(){
		var data = [];		
		data.push({
			name:SECURITY_NAME,
			value: SECURITY_VALUE
		});	

		data.push({
			name:"fin_item_id",
			value: $("#fin_item_id").val()		
		});
		data.push({
			name:"fst_unit",
			value: $("#fst_unit").val()			
		});
		data.push({
			name:"fdb_qty",
			value: $("#fdb_qty").val()			
		});
		


		var t = tblBOMWO;
		var datas = t.data();
		dataDetails =[];
		$.each(datas,function(i,v){
			dataDetails.push(v);
		});
		data.push({
			name:"details",
			value: JSON.stringify(dataDetails)
		});

		if (dataDetails.length <= 0){
			return;
		}

		App.blockUIOnAjaxRequest("<h5>Please wait....</h5>");
        $.ajax({
            url : "<?=site_url()?>tr/production/wo/ajxCalculateMaterialRequirment",
            data: data,
            method: "POST",
        }).done(function(resp){
            if(resp.status=="SUCCESS"){
				tblRMWO.clear();
				var details =resp.data;				
				var totalHPP = 0;
				$.each(details,function(i,v){
					var data = {
						fin_rec_id:0,
						fin_item_id:v.fin_item_id,
						fst_item_name:v.fst_item_name,
						fst_item_code:v.fst_item_code,
						fst_unit:v.fst_unit,
						fdb_qty:v.fdb_qty_real,
						fdc_ttl_hpp:v.fdc_ttl_hpp
					}
					totalHPP += parseFloat(v.fdc_ttl_hpp);
					tblRMWO.row.add(data);
				});
				tblRMWO.draw(false);
				$("#estimate-hpp-total").val(App.money_format(totalHPP));
				var hppPerUnit = totalHPP / parseFloat($("#fdb_qty").val());
				$("#estimate-hpp-unit").val(App.money_format(hppPerUnit));

			}else{
				alert(resp.messages);
			}
        });	
	}	

</script>
<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
