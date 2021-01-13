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
					<input type="hidden" name="fin_woein_id" id="fin_woein_id" value="<?=$fin_woein_id?>"/>
					<div class="form-group">
						<label for="fst_woein_no" class="col-md-2 control-label"><?=lang("Memo In No.")?> #</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_woein_no" name="fst_woein_no" value="<?=$fst_woein_no?>" readonly /> 
							<div id="fst_woein_no" class="text-danger"></div>
						</div>							
						<label for="fdt_woein_datetime" class="col-md-2 control-label"><?=lang("Tanggal")?></label>
						<div class="col-md-4">
							<input type="text" readonly class="form-control datetimepicker text-right" id="fdt_woein_datetime" placeholder="<?=lang("Datetime")?>" name="fdt_woein_datetime" value=""/>
							<div id="fdt_woein_datetime_err" class="text-danger"></div>
						</div>								
					</div>  


					<div class="form-group">
						<label for="fin_woeout_id" class="col-md-2 control-label"><?=lang("External WO Out")?> #</label>
						<div class="col-md-10">
							<select class="form-control" id="fin_woeout_id" name="fin_woeout_id" style="width:100%"></select>
							<div id="fin_woeout_id_err" class="text-danger"></div>
						</div>													
					</div>  
					
					<div class="form-group">
						<label for="fin_supplier_id" class="col-md-2 control-label"><?=lang("Supplier")?> #</label>
						<div class="col-md-10">
							<select class="form-control" id="fin_supplier_id" style="width:100%" disabled></select>
							<div id="fin_supplier_id_err" class="text-danger"></div>
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
						<div class="col-md-2">
							<select readonly class="form-control" id="fst_unit" placeholder="<?=lang("Unit Production")?>" style="width:100%"></select>
						</div>
						<label for="fdb_qty" class="col-md-2 control-label"><?=lang("Qty Balance WOE Out")?></label>
						<div class="col-md-2">
							<input type="text" readonly class="form-control text-right" id="fdb_qty_balance"  placeholder="<?=lang("qty")?>" value="1"></input>
						</div>
						<label for="fdb_qty" class="col-md-2 control-label"><?=lang("Qty In")?></label>
						<div class="col-md-2">
							<input type="text"  class="form-control text-right" id="fdb_qty" name="fdb_qty" placeholder="<?=lang("qty")?>" value="1"></input>
							<div id="fdb_qty_err" class="text-danger"></div>
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

<script type="text/javascript" info="define">

</script>

<script type="text/javascript" info="bind">
	$(document).bind('keydown', 'alt+d', function(){
		$("#btn-add-detail").trigger("click");
	});
</script>

<script type="text/javascript" info="init">
	$(function(){		
		$("#fdt_woein_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");

		$("#fin_woeout_id").select2({
			ajax:{
				url:"<?=site_url()?>tr/production/woe_in/ajxGetWOEList",
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
								"id": value.fin_woeout_id,
								"text": value.fst_woeout_no,
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
			$.ajax({
				url:"<?=site_url()?>tr/production/woe_in/ajxGetDetailWOE",
				method:"GET",
				data:{
					fin_woeout_id:$("#fin_woeout_id").val()
				}
			}).done(function(resp){
				if(resp.status=="SUCCESS"){
					data = resp.data;
					App.addOptionIfNotExist("<option value='"+data.fin_supplier_id+"'>"+ data.fst_relation_name +"</option>","fin_supplier_id");
					$("#fst_item").val(data.fst_item_code + " - " + data.fst_item_name);
					App.addOptionIfNotExist("<option value='"+data.fst_unit+"'>"+ data.fst_unit +"</option>","fst_unit");
					$("#fdb_qty_balance").val(parseInt(data.fdb_qty) - parseInt(data.fdb_qty_in));
				}else{
					alert(resp.messages);
				}
			});
		});

		App.fixedSelect2();
		initForm();
	});
</script>

<script type="text/javascript" info="event">
	$(function(){
		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>tr/production/woe_in/add");
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
			window.location.replace("<?=site_url()?>tr/production/woe_in");
		});

		$("#btn-add-batchno").click(function(e){
			e.preventDefault();
			mdlDetailBatch.selectedDetail = null;
			mdlDetailBatch.show();
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
		
		
		if (mode == "ADD"){
			url = "<?=site_url()?>tr/production/woe_in/ajx_add_save";
		}else{			
			url = "<?=site_url()?>tr/production/woe_in/ajx_edit_save";
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
				url:"<?=site_url()?>tr/production/woe_in/fetch_data/<?=$fin_woein_id?>",
				method:"GET",
			}).done(function(resp){
				if(resp.status=="SUCCESS"){
					var data = resp.data.data;
					App.autoFillForm(data);
					$("#fdt_woein_datetime").val(dateTimeFormat(data.fdt_woein_datetime)).datetimepicker("update");	
					App.addOptionIfNotExist("<option value='"+data.fin_woeout_id+"'>"+ data.fst_woeout_no +"</option>","fin_woeout_id");
					App.addOptionIfNotExist("<option value='"+data.fin_supplier_id+"'>"+ data.fst_supplier_name +"</option>","fin_supplier_id");
					$("#fst_item").val(data.fst_item_code + " - " + data.fst_item_name);
					App.addOptionIfNotExist("<option value='"+data.fst_unit+"'>"+ data.fst_unit +"</option>","fst_unit");
					$("#fdb_qty_balance").val(data.fdb_qty_balance);
					
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
	
	
</script>
<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
