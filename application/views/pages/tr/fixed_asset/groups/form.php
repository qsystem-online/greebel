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
	<h1><?=lang("Fixed Asset Group ")?><small><?=lang("form")?></small></h1>
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
					<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Group")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
				</div>
			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmHeader" class="form-horizontal" action="" method="POST" >
				<div class="box-body">
					<input type="hidden" id="fin_fa_group_id" name="fin_fa_group_id" value="<?=$fin_fa_group_id?>"/>

					<div class="form-group">
						<label for="fst_fa_group_code" class="col-md-2 control-label"><?=lang("Kode")?> #</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="fst_fa_group_code" placeholder="<?=lang("Kode")?>" name="fst_fa_group_code"/>
							<div id="fst_fa_group_code_err" class="text-danger"></div>
						</div>								
                    </div>
                    <div class="form-group">
						<label for="fst_fa_group_name" class="col-md-2 control-label"><?=lang("Nama")?> #</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="fst_fa_group_name" placeholder="<?=lang("Nama")?>" name="fst_fa_group_name"/>
							<div id="fst_fa_group_name_err" class="text-danger"></div>
						</div>								
                    </div>
                    <div class="form-group">
						<label for="fst_method" class="col-md-2 control-label"><?=lang("Method")?> #</label>
						<div class="col-md-2">
							<select class="form-control" id="fst_method" placeholder="<?=lang("Method")?>" name="fst_method">
                                <option value="Non-Depreciable">Non-Depreciable</option>
                                <option value="Straight Line">Straight Line</option>
                                <option value="Double Declining Balance">Double Declining Balance</option>
                            </select>
							<div id="fst_method_err" class="text-danger"></div>
						</div>								
                        <label for="fin_life_time_month" class="col-md-2 control-label"><?=lang("Umur (bulan)") ?> #</label>
                        <div class="col-md-2">
                            <input type="text" class="form-control" id="fin_life_time_month" value="12" name="fin_life_time_month"/>
							<div id="fin_life_time_month_err" class="text-danger"></div>
						</div>				
                        <label for="fst_depre_period" class="col-md-2 control-label"><?=lang("Periode") ?> #</label>
                        <div class="col-md-2">
                            <select  class="form-control" id="fst_depre_period"  name="fst_depre_period">
                                <option value="monthly"><?=lang("Bulanan")?></option>
                                <option value="year"><?=lang("Tahunan")?></option>
                            </select>
							<div id="fst_depre_period_err" class="text-danger"></div>
						</div>					
                    </div>
                                       
                    <div class="form-group">
						<label for="fst_accum_account_code" class="col-md-2 control-label"><?=lang("Rek. Akumulasi Susut") ?> </label>
						<div class="col-md-10">
                            <select  class="form-control" id="fst_accum_account_code" name="fst_accum_account_code">
                            <?php
								$assetListAcc = $this->glaccounts_model->getAccountListByGroup("asset");
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

<script type="text/javascript" info="define">
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
			window.location.replace("<?=site_url()?>tr/fixed_asset/groups/add")
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
			window.location.replace("<?=site_url()?>tr/fixed_asset/groups");
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
        $("#fst_accum_account_code").select2();
        $("#fst_deprecost_account_code").select2();        
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
			url = "<?=site_url()?>tr/fixed_asset/groups/ajx_add_save";
		}else{
			if (confirmEdit == 0){
				MdlEditForm.saveCallBack = function(){
					submitAjax(1);
				};		
				MdlEditForm.show();
				return;
			}

			url = "<?=site_url()?>tr/fixed_asset/groups/ajx_edit_save";
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
				url:"<?= site_url() ?>tr/fixed_asset/groups/fetch_data/<?=$fin_fa_group_id?>",
			}).done(function(resp){				
				dataH =  resp.data;
				if (dataH == null){
					alert("<?=lang("ID transaksi tidak dikenal")?>");
					return false;
				}
				
                App.autoFillForm(dataH);
                $("#fst_accum_account_code").val(dataH.fst_accum_account_code).trigger("change");
                $("#fst_deprecost_account_code").val(dataH.fst_deprecost_account_code).trigger("change");
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

		var url =  "<?= site_url() ?>tr/fixed_asset/groups/delete/" + $("#fin_fa_group_id").val();
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
