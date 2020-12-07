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
					<div class="form-group">
						<label for="fst_status" class="col-md-2 control-label"><?=lang("Status ")?> </label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_status" placeholder=""  value=""/>
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
				
                </div>
				<!-- end box body -->

                <div class="box-footer text-right">
                    
                </div>
                <!-- end box-footer -->
            </form>
        </div>
    </div>
</section>



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
	});
</script>

<script type="text/javascript" info="event">
	$(function(){	
        $("#btnNew").click(function(e){
            getUpdate();
        })
        $("#btnSubmitAjax").click(function(e){
            submitAjax();
        })
	});
</script>

<script type="text/javascript" info="function">
    var stillProcess = false;
    var cekId = 0;
	function submitAjax(){     		
        stillProcess = true;
        
		$.ajax({
            url:"<?=site_url()?>process/ajxDoClosing",
            method:"POST",
            data:{
                [SECURITY_NAME]:SECURITY_VALUE,
            }            
        }).done(function(resp){
            console.log(resp);
            
        }).always(function(resp){
            console.log(resp);
            stillProcess = false;
        });
        
        getUpdate();
    }
    

    function getUpdate(){
        if (stillProcess  == false){
            cekId =0;
            return;
        }
        cekId++;
        $.ajax({
            url:"<?=site_url()?>process/ajxGetUpdateClosing/" + cekId,
            method:"GET",
        }).done(function(resp){
            
        }).always(function(resp){
            $("#fst_status").val(resp.data);
            getUpdate();
        });
    }
	
	function initForm(){
		
		if (mode == "EDIT"){			
			App.blockUIOnAjaxRequest();
			$.ajax({
				url:"<?= site_url() ?>tr/production/wo/fetch_data/",
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
