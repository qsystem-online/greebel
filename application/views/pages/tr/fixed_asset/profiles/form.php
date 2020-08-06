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
	<h1><?=lang("Penerimaan Mutasi Antar Gudang")?><small><?=lang("form")?></small></h1>
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
					<a id="btnJurnal" class="btn btn-primary" href="#" title="Jurnal" style="display:<?= $mode == "ADD" ? "none" : "inline-block" ?>"><i class="fa fa-align-left" aria-hidden="true"></i></a>
					<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
					<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Group")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
				</div>
			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmHeader" class="form-horizontal" action="" method="POST" >
				<div class="box-body">

					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#formMaster">Form</a></li>
						<li style="display:block"><a data-toggle="tab" href="#detailsProfile" >Details</a></li>
					</ul>

					<div class="tab-content">
						<div id="formMaster" class="tab-pane fade in active" style="padding-top:20px">							
							<input type="hidden" id="fin_fa_profile_id" name="fin_fa_profile_id" value="<?=$fin_fa_profile_id?>"/>
							<div class="form-group">
								<label for="fst_fa_profile_no" class="col-md-2 control-label"><?=lang("Master Profile No")?> #</label>
								<div class="col-md-4">
									<input type="TEXT" class="form-control" id="fst_fa_profile_no" name="fst_fa_profile_no" value="<?=$fst_fa_profile_no?>"/>
								</div>						
								<label for="fdt_aquisition_date" class="col-md-2 control-label"><?=lang("Tgl Akuisisi") ?> </label>
								<div class="col-md-4">
									<input type="TEXT" class="form-control text-right datepicker" id="fdt_aquisition_date"  name="fdt_aquisition_date"/>
									<div id="fdt_aquisition_date_err" class="text-danger"></div>
								</div>						
							</div>

							<div class="form-group">
								<label for="fst_type" class="col-md-2 control-label"><?=lang("FA Source")?> #</label>
								<div class="col-md-10">
									<select class="form-control" id="fst_type" name="fst_type">
										<option value="">--</option>
										<option value="PURCHASE">Purchase</option>
										<option value="MUTASI">Mutasi</option>
									</select>
								</div>															
							</div>


							<div class="form-group source-purchase" style="display:none">
								<label for="fst_fa_profile_name" class="col-md-2 control-label"><?=lang("Invoice")?> #</label>
								<div class="col-md-3">
									<select class="form-control" id="fin_lpbpurchase_id" style="width:100%"></select>
								</div>					
								<label for="fst_fa_profile_name" class="col-md-1 control-label"><?=lang("Item")?> </label>			
								<div class="col-md-6">
									<select  class="form-control" id="fin_lpbpurchase_detail_id" name="fin_lpbpurchase_detail_id" style="width:100%" ></select>
								</div>					
							</div>

							<div class="form-group source-mutasi" style="display:none">
								<label for="fst_fa_profile_name" class="col-md-2 control-label"><?=lang("Disposal")?> #</label>
								<div class="col-md-3">
									<select class="form-control" id="fin_fa_disposal_id" style="width:100%"></select>
								</div>					
								<label for="fst_fa_profile_name" class="col-md-1 control-label"><?=lang("Item")?> </label>			
								<div class="col-md-6">
									<select  class="form-control" id="fin_fa_disposal_detail_id" name="fin_fa_disposal_detail_id" style="width:100%"></select>
								</div>					
							</div>							

							<div class="form-group">
								<label for="fst_fa_profile_name" class="col-md-2 control-label"><?=lang("Nama")?> #</label>
								<div class="col-md-10">
									<input type="text" class="form-control" id="fst_fa_profile_name" placeholder="<?=lang("Nama")?>" name="fst_fa_profile_name"/>
									<div id="fst_fa_profile_name_err" class="text-danger"></div>
								</div>								
							</div>
							<div class="form-group">
								<label for="fst_notes" class="col-md-2 control-label"><?=lang("Notes")?> #</label>
								<div class="col-md-10">
									<textarea class="form-control" id="fst_notes" name="fst_notes"></textarea>
									<div id="fst_notes_err" class="text-danger"></div>
								</div>								
							</div>
							<div class="form-group">
								<label for="fst_notes" class="col-md-2 control-label"><?=lang("Qty")?> #</label>
								<div class="col-md-2">
									<input type="TEXT" class="form-control" id="fdb_qty" name="fdb_qty" value="1"></input>
									<div id="fdb_qty_err" class="text-danger"></div>
								</div>								
							</div>
							<div class="form-group">
								<label for="fin_fa_group_id" class="col-md-2 control-label"><?=lang("Fixed Asset Group")?> #</label>
								<div class="col-md-10">
									<select class="form-control" id="fin_fa_group_id" name="fin_fa_group_id">
										<?php
											echo "<option value=''>-</option>";
											$groupList = $this->msfagroups_model->getList();
											foreach($groupList as $group){
												echo "<option value='$group->fin_fa_group_id' 
														data-fst_method='$group->fst_method'
														data-fin_life_time_month='$group->fin_life_time_month'
														data-fst_accum_account_code = '$group->fst_accum_account_code'
														data-fst_deprecost_account_code ='$group->fst_deprecost_account_code'
														data-fst_depre_period ='$group->fst_depre_period'
													>
													$group->fst_fa_group_code - $group->fst_fa_group_name
												</option>";
											}									
										?>
									</select>
									<div id="fin_fa_group_id_err" class="text-danger"></div>
								</div>								
							</div>
							
							<div class="form-group">
								<label for="fst_method" class="col-md-2 control-label"><?=lang("Method")?> #</label>
								<div class="col-md-4">
									<select class="form-control" id="fst_method" placeholder="<?=lang("Method")?>" name="fst_method">
										<option value="Non-Depreciable">Non-Depreciable</option>
										<option value="Straight Line">Straight Line</option>
										<option value="Double Declining Balance">Double Declining Balance</option>
									</select>
									<div id="fst_method_err" class="text-danger"></div>
								</div>								
												
								<label for="fst_depre_period" class="col-md-1 control-label"><?=lang("Periode") ?> #</label>
								<div class="col-md-2">
									<select  class="form-control" id="fst_depre_period"  name="fst_depre_period">
										<option value="monthly"><?=lang("Bulanan")?></option>
										<option value="year"><?=lang("Tahunan")?></option>
									</select>
									<div id="fst_depre_period_err" class="text-danger"></div>
								</div>

								<label for="fin_life_time_period" class="col-md-2 control-label"><?=lang("Umur(periode)") ?></label>
								<div class="col-md-1">
									<input type="text" class="form-control text-right" id="fin_life_time_period" value="1" name="fin_life_time_period"/>
									<div id="fin_life_time_period_err" class="text-danger"></div>
								</div>
							</div>
							<div class="form-group">
								<label for="fst_account_code" class="col-md-2 control-label"><?=lang("Rek. Akun") ?> </label>
								<div class="col-md-10">
									<select  class="form-control" id="fst_account_code" name="fst_account_code">							
									<?php
										$assetListAcc = $this->glaccounts_model->getAccountListByGroup("asset");								
										foreach($assetListAcc as $acc){
											echo "<option value='$acc->fst_glaccount_code' >$acc->fst_glaccount_code - $acc->fst_glaccount_name </option>";
										}
									?>
									</select>
									<div id="fst_account_code_err" class="text-danger"></div>
								</div>								
							</div>

							<div class="form-group">
								<label for="fst_accum_account_code" class="col-md-2 control-label"><?=lang("Rek. Akumulasi Susut") ?> </label>
								<div class="col-md-10">
									<select  class="form-control" id="fst_accum_account_code" name="fst_accum_account_code">							
									<?php
										//$assetListAcc = $this->glaccounts_model->getAccountListByGroup("asset");								
										foreach($assetListAcc as $acc){
											echo "<option value='$acc->fst_glaccount_code' >$acc->fst_glaccount_code - $acc->fst_glaccount_name </option>";
										}
									?>
									</select>
									<div id="fst_accum_account_code_err" class="text-danger"></div>
								</div>								
							</div>
							<div class="form-group">
								<label for="fst_deprecost_account_code" class="col-md-2 control-label"><?=lang("Rek. Biaya Susut") ?> </label>
								<div class="col-md-10">
									<select  class="form-control" id="fst_deprecost_account_code"  name="fst_deprecost_account_code">
									<?php
										$expenseListAcc = $this->glaccounts_model->getAccountListByGroup("biaya");
										foreach($expenseListAcc as $acc){
											echo "<option value='$acc->fst_glaccount_code' >$acc->fst_glaccount_code - $acc->fst_glaccount_name </option>";
										}
									?>
									</select>
									<div id="fst_deprecost_account_code_err" class="text-danger"></div>
								</div>								
							</div>

							<div class="form-group">
								<label for="fdc_aquisition_price" class="col-md-2 control-label"><?=lang("Nilai Perolehan") ?> </label>
								<div class="col-md-4">
									<input type="TEXT" class="form-control money" id="fdc_aquisition_price"  name="fdc_aquisition_price" value="0"/>
									<div id="fdc_aquisition_price_err" class="text-danger"></div>
								</div>				
								<label for="fdc_residu_value" class="col-md-2 control-label"><?=lang("Nilai Residu") ?> </label>
								<div class="col-md-4">
									<input type="TEXT" class="form-control money" id="fdc_residu_value"  name="fdc_residu_value" value="0"/>
									<div id="fdc_residu_value_err" class="text-danger"></div>
								</div>				

							</div>
							
							
							<div class="form-group">
								<label for="fin_pcc_id" class="col-md-2 control-label"><?=lang("Cost Center") ?> </label>
								<div class="col-md-10">
									<select class="form-control " id="fin_pcc_id"  name="fin_pcc_id">
										<?php
											$pccList =  $this->profitcostcenter_model->getAllList();
											foreach($pccList as $pcc){
												echo "<option value='$pcc->fin_pcc_id'>$pcc->fst_pcc_name</option>";
											}
										?>
									</select>
									<div id="fin_pcc_id_err" class="text-danger"></div>
								</div>						
							</div>

							
						</div>
						<div id="detailsProfile" class="tab-pane fade">
							<h3>Details</h3>
							<table id="tblDetailList" class="table table-bordered table-hover table-striped" style="width:100%"></table>
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
						<form class="form-horizontal">
							<input type='hidden' id='fin_rec_id_items'/>
							<div class="form-group">
								<label class="col-md-3 control-label"><?=lang("Profile Code")?></label>
								<div class="col-md-9">											
									<input type="text" id="fst_fa_profile_code_d" class="form-control" />
								</div>										
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label"><?=lang("Profile Name")?></label>	
								<div class="col-md-9">		
									<input type="text" id="fst_fa_profile_name_d" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-12">		
									<button type="button" class="btn btn-default btn-sm text-center pull-right" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
									<button id="btn-save-detail" type="button" class="btn btn-primary btn-sm text-center pull-right" style="width:15%;margin-right:15px"><?=lang("Add")?></button>
									
								</div>
							</div>
						</form>						
						
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" info="define">	
		var mdlDetail = {
			show:function(){
				var data = selectedDetail.data();
				$("#fst_fa_profile_code_d").val(data.fst_fa_profile_code);
				$("#fst_fa_profile_name_d").val(data.fst_fa_profile_name);				
				$("#mdlDetail").modal("show");
			},

			hide:function(){
				$("#mdlDetail").modal("hide");
			},
			clear:function(){
			}
		}	
	</script>

	<script type="text/javascript" info="event">								
		$("#btn-save-detail").click(function(e){
			e.preventDefault();
			var data = selectedDetail.data();




		});
	</script>

	<script type="text/javascript" info="init">
		$(function(){
		});
	</script>

	<script type="text/javascript" info="function">	
	</script>
</div>




<?php echo $mdlEditForm ?>
<?php echo $mdlPrint ?>
<?php echo $mdlJurnal ?>

<script type="text/javascript" info="define">
	var selectedDetail;
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
			window.location.replace("<?=site_url()?>tr/fixed_asset/profiles/add")
		});
		$("#btnPrint").click(function(e){
			e.preventDefault();
			frameVoucher.print("<?=site_url()?>tr/gudang/mutasi/print_voucher/" + $("#fin_mag_id").val());
		});

		$("#btnJurnal").click(function(e){
			e.preventDefault();
			MdlJurnal.showJurnalByRef("PFA",$("#fin_fa_profile_id").val());
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
			window.location.replace("<?=site_url()?>tr/fixed_asset/profiles");
		});	

		$("#fst_type").change(function(){
			$(".source-purchase,.source-mutasi").hide();
			$("#fst_account_code,#fdb_qty,#fdc_aquisition_price").removeAttr('disabled');

			if ( $("#fst_type").val()=="PURCHASE" ){
				$(".source-purchase").show();
				$("#fst_account_code,#fdb_qty,#fdc_aquisition_price").attr('disabled', true);
			}else if($("#fst_type").val()=="MUTASI"){
				$(".source-mutasi").show();
				$("#fdb_qty,#fdc_aquisition_price").attr('disabled', true);
			}
		});

		$("#btn-add-items").click(function(e){
			e.preventDefault();
			selectedDetail = null;		
			mdlDetail.show();
			mdlDetail.clear();
		});

		$("#fin_lpbpurchase_detail_id").on("select2:select",function(e){
			var data = e.params.data;
			$("#fst_fa_profile_name").val(data.fst_custom_item_name);
			$("#fdb_qty").val(data.fdb_qty - data.fdb_qty_return);
			$("#fdc_aquisition_price").val(data.fdc_price);
			$("#fst_account_code").val(data.fst_account_code).trigger("change");

			//$("#fst_account_code").select2("readonly", true);
			

		}).on("select2:unselect",function(e){
			//$("#fst_account_code").select2("readonly", false);						
		});

		$("#fin_fa_disposal_detail_id").on("select2:select",function(e){
			var data = e.params.data;
			console.log(data);
			$("#fst_fa_profile_name").val(data.fst_fa_profile_name);
			$("#fdb_qty").val(1);
			$("#fdc_aquisition_price").val(data.fdc_aquisition_price);
		});

		$("#fin_fa_group_id").change(function(e){
			var group = $("#fin_fa_group_id option:selected").data();
			$("#fst_method").val(group.fst_method);
			$("#fin_life_time_month").val(group.fin_life_time_month);
			$("#fst_accum_account_code").val(group.fst_accum_account_code).trigger("change");
			$("#fst_deprecost_account_code").val(group.fst_deprecost_account_code).trigger("change");
			$("#fst_depre_period").val(group.fst_depre_period);	
					
		});
		
	});
</script>
<script type="text/javascript" info="init">
	$(function(){
        $("#fst_account_code").select2();
        $("#fst_accum_account_code").select2();
        $("#fst_deprecost_account_code").select2();        

		$("#fin_lpbpurchase_id").select2({
			//allowClear:true,
			placeholder: 'Invoice No #',
			ajax:{
				delay:250,
				url:"<?=site_url()?>tr/fixed_asset/profiles/ajxListLPBPurchase",
				dataType:"json",
				processResults: function (resp) {					
					if (resp.status == "SUCCESS"){
						data = $.map(resp.data,function(obj){
							obj.id = obj.fin_lpbpurchase_id;
							obj.text = obj.fst_lpbpurchase_no;
							return obj;
						});
						//console.log(data);
						return {
							results: data
						};
					}
					
				},
			}
		});

		$("#fin_lpbpurchase_detail_id").select2({
			//allowClear:true,
			placeholder: 'Item',
			ajax:{
				delay:250,
				//url:"<=site_url()?>tr/fixed_asset/profiles/ajxListLPBPurchaseItems/" + $("#fin_lpbpurchase_id").val(),
				url: function (params) {
					return "<?=site_url()?>tr/fixed_asset/profiles/ajxListLPBPurchaseItems/" + $("#fin_lpbpurchase_id").val();
				},
				dataType:"json",
				processResults: function (resp) {								
					if (resp.status == "SUCCESS"){
						data = $.map(resp.data,function(obj){
							obj.id = obj.fin_lpbpurchase_detail_id;
							obj.text = obj.fst_custom_item_name;
							return obj;
						});
						//console.log(data);
						return {
							results: data
						};
					}
					
				},
			}
		});

		$("#fin_fa_disposal_id").select2({
			//allowClear:true,
			placeholder: 'Disposa Mutasi No #',
			ajax:{
				delay:250,
				url:"<?=site_url()?>tr/fixed_asset/profiles/ajxListMutasi",
				dataType:"json",
				processResults: function (resp) {					
					if (resp.status == "SUCCESS"){
						data = $.map(resp.data,function(obj){
							obj.id = obj.fin_fa_disposal_id;
							obj.text = obj.fst_fa_disposal_no;
							return obj;
						});
						//console.log(data);
						return {
							results: data
						};
					}
					
				},
			}
		});

		$("#fin_fa_disposal_detail_id").select2({
			//allowClear:true,
			placeholder: 'Item',
			ajax:{
				delay:250,
				//url:"<=site_url()?>tr/fixed_asset/profiles/ajxListLPBPurchaseItems/" + $("#fin_lpbpurchase_id").val(),
				url: function (params) {
					return "<?=site_url()?>tr/fixed_asset/profiles/ajxListMutasiItems/" + $("#fin_fa_disposal_id").val();
				},
				dataType:"json",
				processResults: function (resp) {								
					if (resp.status == "SUCCESS"){
						data = $.map(resp.data,function(obj){
							obj.id = obj.fin_fa_disposal_detail_id;
							obj.text = obj.fst_fa_profile_code + " - " + obj.fst_fa_profile_name;
							return obj;
						});
						//console.log(data);
						return {
							results: data
						};
					}
					
				},
			}
		});

	
		$("#fdt_aquisition_date").datepicker("update",dateFormat("<?=date('Y-m-d')?>"));

		App.fixedSelect2();
		initForm();
	});
</script>
<script type="text/javascript" info="function">
	
	function submitAjax(confirmEdit){     
		var mode = "<?=$mode?>";   
		data = $("#frmHeader").serializeArray();

		data.push({
			name:SECURITY_NAME,
			value: SECURITY_VALUE
		});			
	   
		if (mode == "ADD"){
			url = "<?=site_url()?>tr/fixed_asset/profiles/ajx_add_save";
		}else{
			if (confirmEdit == 0){
				MdlEditForm.saveCallBack = function(){
					submitAjax(1);
				};		
				MdlEditForm.show();
				return;
			}

			url = "<?=site_url()?>tr/fixed_asset/profiles/ajx_edit_save";
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
		var mode = "<?=$mode?>";		
		if (mode == "EDIT"){			
			App.blockUIOnAjaxRequest();
			$.ajax({
				url:"<?= site_url() ?>tr/fixed_asset/profiles/fetch_data/<?=$fin_fa_profile_id?>",
			}).done(function(resp){				
				
				var dataH =  resp.data.header;
				var details =  resp.data.details;
				console.log(dataH);

				if (dataH == null){
					alert("<?=lang("ID transaksi tidak dikenal")?>");
					return false;
				}
				
                App.autoFillForm(dataH);
				$("#fdt_aquisition_date").val(dateFormat(dataH.fdt_aquisition_date));
				$("#fst_type").trigger("change");

				
				App.addOptionIfNotExist("<option value='" + dataH.fin_fa_disposal_id + "'>" +dataH.fst_fa_disposal_no+"</option>","fin_fa_disposal_id");
				App.addOptionIfNotExist("<option value='" + dataH.fin_fa_disposal_detail_id +"'>" +dataH.fst_fa_disposal_code + " - " + dataH.fst_fa_disposal_name +"</option>","fin_fa_disposal_detail_id");
				
				App.addOptionIfNotExist("<option value='" + dataH.fin_lpbpurchase_id + "'>" + dataH.fst_lpbpurchase_no+"</option>","fin_lpbpurchase_id");
				App.addOptionIfNotExist("<option value='" + dataH.fin_lpbpurchase_detail_id +"'>" + dataH.fst_lpbpurchase_item_name +"</option>","fin_lpbpurchase_detail_id");
				
				$("#fst_account_code").val(dataH.fst_account_code).trigger("change");
                $("#fst_accum_account_code").val(dataH.fst_accum_account_code).trigger("change");
                $("#fst_deprecost_account_code").val(dataH.fst_deprecost_account_code).trigger("change");
				

				loadDetails(details);
				App.fixedSelect2();
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

		var url =  "<?= site_url() ?>tr/gudang/penerimaan_mutasi/delete/" + $("#fin_mag_confirm_id").val();
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

	function loadDetails(details){

		if ( $.fn.DataTable.isDataTable('#tblDetailList') ) {
			$('#tblDetailList').DataTable().destroy();
		}
		$('#tblDetailList tbody').empty();


		$('#tblDetailList').on('preXhr.dt', function ( e, settings, data ) {
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
				{"title" : "recid","width": "0px",data:"fin_rec_id",visible:false},
				{"title" : "Fixed Asset Profile Code","width": "100px",data:"fst_fa_profile_code",visible:true},
				{"title" : "Item Name","width": "300px",data:"fst_fa_profile_name",visible:true,orderable:false},
				{"title" : "Action","width": "28px",className:'dt-body-center text-center',orderable:false,
                    render: function( data, type, row, meta ) {
						return "<div style='font-size:16px'><a class='btn-edit' href='#'><i class='fa fa-pencil'></i></a></div>";                        
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
			//calculateTotal();
		}).on("click",".btn-delete",function(event){
			event.preventDefault();
			t = $('#tblSJDetails').DataTable();
			var trRow = $(this).parents('tr');
			t.row(trRow).remove().draw();
			calculateTotal();
		}).on("click",".btn-edit",function(event){	
            t = $("#tblDetailList").DataTable();
            tRow = $(this).parents("tr");
            selectedDetail  = t.row(tRow);
			//data = t.row(tRow).data();			
			mdlDetail.show();			
		});

		var t = $('#tblDetailList').DataTable();

		$.each(details,function(i,v){
			var row ={
				fin_rec_id:v.fin_rec_id,
				fst_fa_profile_code:v.fst_fa_profile_code,
				fst_fa_profile_name:v.fst_fa_profile_name
			};
			t.row.add(row);
		});

		t.draw(false);
	}

</script>
<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
