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
	<h1><?=lang("Laporan Hasil Produksi")?><small><?=lang("form")?></small></h1>
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
					<a id="btnPrint" class="btn btn-primary" href="#" title="<?=lang("Cetak")?>"><i class="fa fa-print" aria-hidden="true"></i></a>
					<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
					<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Group")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
				</div>
			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmHeader" class="form-horizontal" action="" method="POST" >
				<div class="box-body">
					<input type="hidden" id="fin_lhp_id" name="fin_lhp_id" value="<?=$fin_lhp_id?>"/>

					<div class="form-group">
						<label for="fst_lhp_no" class="col-md-2 control-label"><?=lang("LHP")?> #</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_lhp_no" placeholder="<?=lang("LHP No")?>" name="fst_lhp_no" value="<?=$fst_lhp_no?>"/>
							<div id="fst_lhp_no_err" class="text-danger"></div>
						</div>								

						<label for="fdt_lhp_datetime" class="col-md-2 control-label"><?=lang("Tanggal")?></label>
						<div class="col-md-4">
							<input type="text" class="form-control datetimepicker text-right" id="fdt_lhp_datetime" placeholder="<?=lang("LHP Datetime")?>" name="fdt_lhp_datetime" value=""/>
							<div id="fdt_lhp_datetime_err" class="text-danger"></div>
						</div>								
                    </div>  

					<div class="form-group">
						<label for="fin_wo_id" class="col-md-2 control-label"><?=lang("WO #")?></label>
						<div class="col-md-5">
							<select id="fin_wo_id" name="fin_wo_id" class='form-control' style="width:100%"></select>
							<div id="fin_wo_id_err" class="text-danger"></div>
						</div>						
						<label for="fin_wobatchno_id" class="col-md-1 control-label"><?=lang("Batch #")?></label>
						<div class="col-md-4">
							<select id="fin_wobatchno_id" name="fin_wobatchno_id" class='form-control' style="width:100%"></select>
							<div id="fin_wobatchno_id_err" class="text-danger"></div>
						</div>		

					</div>

				
					<div class="form-group">
						<label for="fin_warehouse_id" class="col-md-2 control-label"><?=lang("Warehouse")?></label>
						<div class="col-md-10">
							<select id="fin_warehouse_id" name="fin_warehouse_id" class='form-control' style="width:100%">
								<?php
									$warehouseList = $this->mswarehouse_model->getNonLogisticWarehouseList();
									foreach($warehouseList as $warehouse){
										echo "<option value='$warehouse->fin_warehouse_id'>$warehouse->fst_warehouse_name</option>";
									}
								?>
							</select>
							<div id="fin_warehouse_id_err" class="text-danger"></div>
						</div>						
					</div>
					
					

					<div class="form-group">					
						<label for="fdt_start_datetime" class="col-md-2 control-label"><?=lang("Start")?></label>
						<div class="col-md-2">
							<input  class="form-control datetimepicker" id="fdt_start_datetime" name="fdt_start_datetime" style="width:100%"/>
							<div id="fdt_start_datetime_err" class="text-danger"></div>
						</div>						
						<label for="fdt_end_datetime" class="col-md-1 control-label"><?=lang("End")?></label>
						<div class="col-md-2">
							<input  class="form-control datetimepicker" id="fdt_end_datetime" name="fdt_end_datetime" style="width:100%" />
							<div id="fdt_end_datetime_err" class="text-danger"></div>
						</div>		
						
						<label for="fin_downtime_in_minutes" class="col-md-2 control-label"><?=lang("Downtime / minutes")?></label>
						<div class="col-md-1">
							<input  class="form-control text-right" id="fin_downtime_in_minutes" name="fin_downtime_in_minutes" style="width:100%" value="0"/>
							<div id="fin_downtime_in_minutes_err" class="text-danger"></div>
						</div>		
										
					</div>

					<div class="form-group">					
						<label for="fdb_gramasi" class="col-md-2 control-label"><?=lang("Kilo / barang")?></label>
						<div class="col-md-2">
							<input  class="form-control text-right" id="fdb_gramasi" name="fdb_gramasi" value="0.00000"/>
							<div id="fdb_gramasi_err" class="text-danger"></div>
						</div>						
						<label for="fin_mesin_id" class="col-md-1 control-label"><?=lang("Mesin")?></label>
						<div class="col-md-3">
							<select  class="form-control" id="fin_mesin_id" name="fin_mesin_id" style="width:100%">
								<?php
									$listMesin = $this->msmesin_model->getList();
									foreach($listMesin as $mesin){
										echo "<option value='$mesin->fin_mesin_id'>$mesin->fst_name</option>";
									}
								?>
							</select>
							<div id="fin_mesin_id_err" class="text-danger"></div>
						</div>	
						<label for="fin_checksheet_id" class="col-md-1 control-label"><?=lang("CS No.")?></label>
						<div class="col-md-3">
							<select  class="form-control " id="fin_checksheet_id" name="fin_checksheet_id"></select>
							<div id="fin_checksheet_id_err" class="text-danger"></div>
						</div>					
					</div>

					
					<div class="form-group">					
						<label for="fin_item_id" class="col-md-2 control-label"><?=lang("Item")?></label>
						<div class="col-md-7">
							<select  class="form-control" id="fin_item_id" name="fin_item_id" placeholder="<?=lang("Item")?>" style="width:100%"></select>
							<div id="fin_item_id_err" class="text-danger"></div>
						</div>
						<label for="fst_unit" class="col-md-1 control-label"><?=lang("Unit")?></label>
						<div class="col-md-2">
							<select  class="form-control" id="fst_unit" name="fst_unit" placeholder="<?=lang("Unit Production")?>" style="width:100%"></select>
							<div id="fst_unit_err" class="text-danger"></div>
						</div>

					</div>

					
					<div class="form-group">

						<label for="fdb_qty_sisa" class="col-md-2 control-label"><?=lang("Qty Sisa")?></label>
						<div class="col-md-2">
							<input type="text" class="form-control text-right" id="fdb_qty_sisa" name="fdb_qty_sisa" value="0" readonly/>
							<div id="fst_notes_err" class="text-danger"></div>
						</div>	
						<label id="fst_wo_unit" class="col-md-4 control-label" style="text-align:left;padding-left:1px"><?=lang("Satuan WO")?></label>

						<label for="fdb_qty" class="col-md-2 control-label"><?=lang("Qty")?></label>
						<div class="col-md-2">
							<input class="form-control text-right" id="fdb_qty" name="fdb_qty" placeholder="<?=lang("qty")?>" value="1"></input>
							<div id="fdb_qty_err" class="text-danger"></div>
						</div>	
					</div>   	
					
					
					<div class="form-group" style="margin-bottom:0px">
						<div class="col-md-12" style="text-align:right">
							<button id="btnAddActivity" class="btn btn-primary btn-sm"><i class="fa fa-cart-plus" aria-hidden="true"></i>&nbsp;&nbsp;Tambah Workstation</button>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-md-12">
							<table id="tblDetails" class="table table-bordered table-hover table-striped" style="width:100%"></table>
						</div>
					</div>
					
					<div class="form-group">
						<label for="fst_notes" class="col-md-12 control-label" style="text-align:left"><?=lang("Notes")?></label>
					</div>
					<div class="form-group">
						<div class="col-md-6">
							<textarea type="text" class="form-control" id="fst_notes" name="fst_notes" ></textarea>
							<div id="fst_notes_err" class="text-danger"></div>
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
<?php echo $mdlJurnal ?>


<div id="mdlDetail" class="modal fade in" role="dialog" style="display: gone">
	<div class="modal-dialog" style="display:table;width:700px">
		<!-- modal content -->
		<div class="modal-content">
			<div class="modal-header" style="padding:7px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 5px;border-top-right-radius: 5px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Tambah Workstation")?></h4>
			</div>

			<div class="modal-body">				        
				<form class="form-horizontal">
				
					<div class="form-group">
						<label class="col-md-2 control-label"><?=lang("Workstation")?>:</label>						
						<div class="col-md-10">
							<select  id="dfin_activity_id" class="form-control" style="width:100%"></select>
						</div>
					</div>

					<div class="form-group activity-person">
						<label class="col-md-2 control-label"><?=lang("User")?>:</label>						
						<div class="col-md-10">
							<select  id="dfin_user_id" class="form-control" style="width:100%">
								<?php
									$userList = $this->users_model->getProductionList();
									foreach($userList as $user){
										echo "<option value='$user->fin_user_id'>$user->fst_username</option>";
									}
								?>
							</select>
						</div>
					</div>

					<div class="form-group activity-team">
						<label class="col-md-2 control-label"><?=lang("Team")?>:</label>						
						<div class="col-md-10">
							<select  id="dfin_team_id" class="form-control" style="width:100%">
								<?php
									$teamList= $this->msactivityteams_model->getList();
									foreach($teamList as $team){
										echo "<option value='$team->fin_team_id'>$team->fst_team_name</option>";
									}
								?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label"><?=lang("Start")?>:</label>						
						<div class="col-md-4">
							<input type="text" id="dfdt_start_datetime" class="datetimepicker form-control text-right"/>
						</div>
						<label class="col-md-2 control-label"><?=lang("End")?>:</label>						
						<div class="col-md-4">
							<input type="text" id="dfdt_end_datetime" class="datetimepicker form-control text-right"/>
						</div>					
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label"><?=lang("Qty")?>:</label>						
						<div class="col-md-4">
							<input type="text" id="dfdb_qty" class="money form-control text-right"/>
						</div>
						<label class="col-md-2 control-label"><?=lang("Unit")?>:</label>						
						<div class="col-md-4">
							<select type="text" id="dfst_unit" class="form-control" style="width:100%">
								<?php
									$unitList = $this->msunits_model->getList();
									foreach($unitList as $unit){
										echo "<option value='$unit->fst_unit'>$unit->fst_unit</option>";
									}

								?>
							</select>
						</div>					
					</div>


				</form>				
			</div>

			<div class="modal-footer">
				<button id="btn-save-detail" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Add")?></button>
				<button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
			</div>
		</div>
	</div>

	<script type="text/javascript" info="define">
		mDt = {	
			selectedActivity:null,	
			selectedDetail:null,
			show:function(){
				 
				if (mDt.selectedDetail != null){
					var data = mDt.selectedDetail.data();
					
					App.addOptionIfNotExist("<option value='"+data.fin_activity_id+"'>"+data.fst_activity_name+"</option>","dfin_activity_id");
					
					$("#dfin_activity_id").trigger({
						type:"select2:select",
						params:{
							data:{
								id:data.fin_activity_id,
								text:data.fst_activity_name,
								fst_team:data.fst_team
							}							
						}
					});

					$("#dfin_user_id").val(data.fin_user_id).trigger("change");
					$("#dfin_team_id").val(data.fin_team_id).trigger("change");
					$("#dfdt_start_datetime").val(data.fdt_start_datetime);
					$("#dfdt_end_datetime").val(data.fdt_end_datetime);
					$("#dfdb_qty").val(data.fdb_qty);
					$("#dfst_unit").val(data.fst_unit);		
					
					



				}				
				$("#mdlDetail").modal("show");
			},
			hide:function(){
				$("#mdlDetail").modal("hide");
			},
			clear:function(){
				mDt.selectedDetail = null;
				$("#dfin_activity_id").val(null).trigger("change");
				$("#dfin_user_id").val(null).trigger("change");
				$("#dfin_team_id").val(null).trigger("change");
				$("#dfdt_start_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");		
				$("#dfdt_end_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");
				$("#dfdb_qty").val(1);
				$("#dfst_unit").val(null).trigger("change");		
				selectedDetail = null;
			},			
		};

	</script>

	<script type="text/javascript" info="event">		
		$(function(){		
		

			$("#btn-save-detail").click(function(e){				
				var data = {
					fin_rec_id:0,
					fin_activity_id:mDt.selectedActivity.id,
					fst_activity_name:mDt.selectedActivity.text,
					fst_team:mDt.selectedActivity.fst_team,
					fin_user_id:$("#dfin_user_id").val(),
					fst_user_name:$("#dfin_user_id option:selected").text(),
					fin_team_id:$("#dfin_team_id").val(),
					fst_team_name:$("#dfin_team_id option:selected").text(),
					fdt_start_datetime:$("#dfdt_start_datetime").val(),
					fdt_end_datetime:$("#dfdt_end_datetime").val(),
					fdb_qty:$("#dfdb_qty").val(),
					fst_unit:$("#dfst_unit").val(),
				}

				if (mDt.selectedDetail == null){
					tblDetails.row.add(data).draw(false);				
				}else{
					var tmp = mDt.selectedDetail.data();
					data.fin_rec_id = tmp.fin_rec_id;
					mDt.selectedDetail.data(data).draw(false);				
				}
				

				//tblDetails.row(selectedDetail).data(data).draw(false);

				mDt.clear();	
				//mDt.hide();		
			});

		});
	</script>

	<script type="text/javascript" info="init">
		$(function(){

			$("#dfin_activity_id").select2({
				ajax:{
					url:"<?=site_url()?>tr/production/lhp/ajxGetActivity",
					data: function (params) {
						params.fin_wo_id=$("#fin_wo_id").val();
						return params;
					},
					dataType: 'json',
					delay: 250,
					processResults: function(resp) {
						if (resp.messages != ""){
							alert(resp.messages);
						}
						if (resp.status == "SUCCESS"){
							var data = resp.data;
							var result = [];
							$.each(data, function(index, v) {
								result.push({								
									"id": v.fin_activity_id,
									"text": v.fst_name,
									"fst_team":v.fst_team,
								});
							});
							return {
								results: result
							};
						}

					},
				}
			}).on("select2:select",function(e){
				mDt.selectedActivity  = e.params.data;
				console.log(mDt.selectedActivity);
				if (mDt.selectedActivity.fst_team == "PERSON"){
					$(".activity-person").show();
					$(".activity-team").hide();
				}else{
					$(".activity-person").hide();
					$(".activity-team").show();
				}


			})
			
			$("#dfin_user_id").select2();

			$("#dfin_team_id").select2({
				ajax:{
					url:"<?=site_url()?>tr/production/lhp/ajxGetUser",
					data: function (params) {
						params.fin_wo_id=$("#fin_wo_id").val();
						return params;
					},
					dataType: 'json',
					delay: 250,
					processResults: function(resp) {
						if (resp.messages != ""){
							alert(resp.messages);
						}
						if (resp.status == "SUCCESS"){
							var data = resp.data;
							var result = [];
							$.each(data, function(index, v) {
								result.push({								
									"id": v.fin_user_id,
									"text": v.fst_user_name,
								});
							});
							return {
								results: result
							};
						}

					},
				}
			});

			mDt.clear();
			

			
		});
	</script>
</div>



<script type="text/javascript" info="define">
	var selectedWO;
	var tblDetails;
</script>

<script type="text/javascript" info="bind">
	$(document).bind('keydown', 'alt+d', function(){
		$("#btn-add-detail").trigger("click");
	});
</script>

<script type="text/javascript" info="init">
	$(function(){		
		$("#fdt_lhp_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");
		$("#fdt_start_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");		
		$("#fdt_end_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");
		

		$("#fin_wo_id").select2({
			ajax:{
				url:"<?=site_url()?>tr/production/lhp/ajxGetWOList",
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
								"fin_item_id":value.fin_item_id,
								"fst_item_name":value.fst_item_name,
								"fst_unit":value.fst_unit,
								"fin_warehouse_target":value.fin_warehouse_target,
								"fdb_qty":value.fdb_qty,
								"fdb_qty_lhp":value.fdb_qty_lhp,
								"fdc_conv_to_basic_unit":value.fdc_conv_to_basic_unit,
								"gramasi_master":value.gramasi_master,
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
			$("#fin_warehouse_id").val(selectedWO.fin_warehouse_target);
			$("#fin_warehouse_id").trigger("change");

			$("#fin_item_id").empty();
			App.addOptionIfNotExist("<option value='"+ selectedWO.fin_item_id +"'>"+selectedWO.fst_item_name +"</option>","fin_item_id");
			$("#fin_item_id").trigger("change");


			$("#fst_unit").empty();
			App.addOptionIfNotExist("<option value='"+selectedWO.fst_unit+"'>"+selectedWO.fst_unit+"</option>","fst_unit");
			App.addOptionIfNotExist("<option value='KILO'>Kilo</option>","fst_unit");

			$("#fdb_qty").val(selectedWO.fdb_qty - selectedWO.fdb_qty_lhp);
			$("#fst_wo_unit").text(selectedWO.fst_unit);
			$("#fdb_gramasi").val(selectedWO.gramasi_master);

		});

		$("#fin_wobatchno_id").select2({
			ajax:{
				url:"<?=site_url()?>tr/production/lhp/ajxGetWOBatchNo",
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
								"id": value.fin_wobatchno_id,
								"text": value.fst_wobatchno_no,
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
		})
		
		
		tblDetails = $("#tblDetails").on('preXhr.dt', function ( e, settings, data ) {
			data.sessionId = "";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "id","width": "0px",sortable:false,data:"fin_rec_id",visible:false},
				{"title" : "Workstation","width": "200px",sortable:false,data:"fin_activity_id",
					"render":function(data,type,row){
						return row.fst_activity_name; 
					}
				},
				{"title" : "Team","width": "50px",sortable:false,data:"fst_team"},

				{"title" : "User / Team","width": "150px",sortable:false,
					render:function(data,type,row){
						if (row.fst_team == "PERSON"){
							return row.fst_user_name;
						}else{
							return row.fst_team_name;
						}
					}
				},
				{"title" : "Start","width": "50px",sortable:false,data:"fdt_start_datetime"},
				{"title" : "End","width": "50px",sortable:false,data:"fdt_end_datetime"},
				{"title" : "Qty","width": "50px",sortable:false,data:"fdb_qty"},
				{"title" : "Unit","width": "50px",sortable:false,data:"fst_unit"},
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
		}).on('click','.btn-edit',function(e){
			e.preventDefault();
			var trRow = $(this).parents('tr');
			mDt.selectedDetail = tblDetails.row(trRow);
			mDt.show();

			//tblActivity.row(trRow).remove().draw(false);
		}).on('click','.btn-delete',function(e){
			e.preventDefault();
			var trRow = $(this).parents('tr');
			tblDetails.row(trRow).remove().draw(false);
		});

		

        App.fixedSelect2();
		initForm();
	});
</script>

<script type="text/javascript" info="event">
	$(function(){
		$("#btnNew").click(function(e){
			//e.preventDefault();
			window.location.replace("<?=site_url()?>tr/production/lhp/add");
		});
		$("#btnPrint").click(function(e){
			e.preventDefault();
			frameVoucher.print("<?=site_url()?>tr/production/lhp/print_voucher/" + $("#fin_lhp_id").val());
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
			window.location.replace("<?=site_url()?>tr/production/lhp");
		});
		
		$("#btnAddActivity").click(function(e){
			e.preventDefault();
			selectedDetail = null;		
			mDt.show();
			mDt.clear();
		});
		
		$("#fst_unit").change(function(e){
			e.preventDefault();
			alert("Change");
			//Caculate qty to kilo
			var fdbQty = $("#fdb_qty").val();				
			var gramasi = $("#fdb_gramasi").val();

			if ($("#fst_unit").val() =="KILO"){				
				$("#fdb_qty").val(selectedWO.fdc_conv_to_basic_unit * fdbQty * gramasi);
			}else{
				$("#fdb_qty").val(fdbQty / selectedWO.fdc_conv_to_basic_unit / gramasi);
			}
			//generateMaterialRequirment();
		});

		$("#fdb_gramasi").change(function(e){
			$("#fdb_qty").trigger("change");
		});

		$("#fdb_qty").change(function(e){
			var qty = selectedWO.fdb_qty;
			var qtyLHP = selectedWO.fdb_qty_lhp;
			var qtyTrans = 0;

			var gramasi = $("#fdb_gramasi").val();
			if ($("#fst_unit").val() =="KILO"){				
				fdbQty = $("#fdb_qty").val();
				qtyTrans = fdbQty / selectedWO.fdc_conv_to_basic_unit / gramasi;
				
			}else{
				qtyTrans = $("#fdb_qty").val();
				//$("#fdb_qty").val(fdbQty / selectedWO.fdc_conv_to_basic_unit / gramasi);
			}

			fdb_qty_sisa = qty -qtyLHP - qtyTrans;
			$("#fdb_qty_sisa").val(fdb_qty_sisa);
		})		
		
		$(document).on("shown.bs.tab","#tabDetail a",function(e){
			var tabActive = $(e.target).text();
			//if (tabActive == "Material Requirment WO"){
			//	generateMaterialRequirment();
			//}

			$($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
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
		var details = new Array();			
		var datas = tblDetails.data();
		$.each(datas,function(i,v){
			details.push(v);
		});
		data.push({
			name:"details",
			value: JSON.stringify(details)
		});
		
		if (mode == "ADD"){
			url = "<?=site_url()?>tr/production/lhp/ajx_add_save";
		}else{			
			url = "<?=site_url()?>tr/production/lhp/ajx_edit_save";
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
				url:"<?= site_url() ?>tr/production/lhp/fetch_data/<?=$fin_lhp_id?>",
			}).done(function(resp){	
				if (resp.messages != ""){
					alert(resp.messages);				
				}

				if (resp.status == "SUCCESS"){
					var data = resp.data;
					var header = data.header;
					if (header != null){
						App.autoFillForm(header);
						$("#fdt_lhp_datetime").val(dateTimeFormat(header.fdt_lhp_datetime)).datetimepicker("update");	
						$("#fdt_start_datetime").val(dateTimeFormat(header.fdt_start_datetime)).datepicker("update");	
						$("#fdt_end_datetime").val(dateTimeFormat(header.fdt_start_datetime)).datepicker("update");	

						selectedWO={
							"id": header.fin_wo_id,
							"text": header.fst_wo_no,
							"fin_item_id":header.fin_item_id,
							"fst_item_name":header.fst_item_name,
							"fst_unit":header.fst_unit,
							"fin_warehouse_target":header.fin_warehouse_id,
							"fdb_qty":header.fdb_qty_wo,
							"fdb_qty_lhp":header.fdb_qty_lhp - header.fdb_qty_baseonwo,
							"fdc_conv_to_basic_unit":header.fdc_conv_to_basic_unit,
						}

						App.addOptionIfNotExist("<option value='"+header.fin_wo_id+"'>"+header.fst_wo_no+"</option>","fin_wo_id");
						App.addOptionIfNotExist("<option value='"+header.fin_wobatchno_id+"'>"+header.fst_wobatchno_no +"</option>","fin_wobatchno_id");
						App.addOptionIfNotExist("<option value='"+header.fin_item_id+"'>"+header.fst_item_name +"</option>","fin_item_id");

						App.addOptionIfNotExist("<option value='"+header.fst_wo_unit+"'>"+header.fst_wo_unit +"</option>","fst_unit");
						App.addOptionIfNotExist("<option value='KILO'>KILO</option>","fst_unit");
						$("#fst_unit").val(header.fst_unit);

						//Detail Activity

						var details = resp.data.details

						var dataDetails = [];
						$.each(details, function(i,v){
							var data = {
								fin_rec_id:v.fin_rec_id,
								fin_activity_id:v.fin_activity_id,
								fst_activity_name:v.fst_activity_name,
								fst_team:v.fst_team,
								fin_user_id:v.fin_user_id,
								fst_user_name:v.fst_user_name,
								fin_team_id:v.fin_team_id,
								fst_team_name:v.fst_team_name,
								fdt_start_datetime:dateTimeFormat(v.fdt_start_datetime),
								fdt_end_datetime:dateTimeFormat(v.fdt_end_datetime),
								fdb_qty:v.fdb_qty,
								fst_unit:v.fst_unit
							};
							dataDetails.push(data);
						});
						console.log(dataDetails);
						tblDetails.rows.add(dataDetails).draw(false);
						//$("#fin_wo_id")
					}
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

		var url =  "<?= site_url() ?>tr/production/lhp/delete/" + $("#fin_lhp_id").val();
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
