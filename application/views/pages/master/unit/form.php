<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?= base_url() ?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?= base_url() ?>bower_components/datatables.net/datatables.min.css">

<style type="text/css">
	.border-0 {
		border: 0px;
	}
	td {
		padding: 2px; 
			!important
	}
	.nav-tabs-custom>.nav-tabs>li.active>a {
		font-weight: bold;
		border-left-color: #3c8dbc;
		border-right-color: #3c8dbc;
		border-style: fixed;
	}
	.nav-tabs-custom>.nav-tabs {
		border-bottom-color: #3c8dbc;
		border-bottom-style: fixed;
	}
</style>

<section class="content-header">
	<h1><?= lang("Projects") ?><small><?= lang("form") ?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Production") ?></a></li>
		<li class="active title"><?= $title ?></li>
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
					<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
					<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
				</div>
			</div>
			<!-- end box header -->

			<!-- form start -->
			<form id="frmHeader" class="form-horizontal" action="" method="POST" enctype="multipart/form-data">
				<div class="box-body">
					<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
					

					<div class="form-group">
						<label for="fst_unit" class="col-sm-2 control-label"><?=lang("ID")?></label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="fin_rec_id" name="fin_rec_id" value="<?= $finRecId?>" readonly>							
						</div>
					</div>

					<div class="form-group">
						<label for="fst_unit" class="col-sm-2 control-label"><?=lang("Unit")?> *</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="fst_unit" name="fst_unit">
							<div id="fst_unit_err" class="text-danger"></div>
						</div>
					</div>
					<div class="form-group">
						<label for="fst_desc" class="col-sm-2 control-label"><?=lang("Keterangan")?> *</label>
						<div class="col-sm-10">
							<textarea class="form-control" id="fst_desc" name="fst_desc"></textarea>
							<div id="fst_desc_err" class="text-danger"></div>
						</div>
					</div>
					<!-- end box body -->
					
				</div>
				<div class="box-footer text-right">
				</div>
				<!-- end box-footer -->
			</form>
		</div>
	</div>
</section>
<?php
	echo $mdlEditForm;
?>
<script type="text/javascript" info="define">
	var mode = "<?=$mode?>";
</script>

<script type="text/javascript" info="init">
	$(function(){

		init_form();

	});
</script>

<script type="text/javascript" info="event">
	$(function(){
		$("#btnSubmitAjax").click(function(event){
			event.preventDefault();
			submitAjax(0);			
		});

		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?= site_url() ?>master/unit/add")
		});

		$("#btnDelete").confirmation({
			title:"<?=lang("Hapus data ini ?")?>",
			rootSelector: '#btnDelete',
			placement: 'left',
		});
		$("#btnDelete").click(function(e){
			e.preventDefault();
			blockUIOnAjaxRequest("<h5>Deleting ....</h5>");
			$.ajax({
				url:"<?= site_url() ?>master/mesin/delete/" + $("#fin_mesin_id").val(),
			}).done(function(resp){
				//consoleLog(resp);
				$.unblockUI();
				if (resp.message != ""){
					$.alert({
						title: 'Message',
						content: resp.message,
						buttons : {
							OK : function(){
								if (resp.status == "SUCCESS"){
									window.location.href = "<?= site_url() ?>master/mesin/lizt";
									return;
								}
							},
						}
					});
				}

				if(resp.status == "SUCCESS"){
					data = resp.data;
					$("#fin_project_id").val(data.insert_id);
					
					//Clear all previous error
					$(".text-danger").html("");
					//Change to Edit mode
					$("#frm-mode").val("EDIT"); //ADD|EDIT
					$('#fst_project_name').prop('readonly', true);
				}
			});
		});

		$("#btnList").click(function(e){
			e.preventDefault();
			window.location.replace(" <?= site_url() ?>master/unit");
		});
	});
</script>

<script type="text/javascript" info="function">
	function submitAjax(confirmEdit){     

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
			url = "<?=site_url()?>master/unit/ajx_add_save";
		}else{			
			url = "<?=site_url()?>master/unit/ajx_edit_save";
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

	function init_form(){
		//alert("Init Form");
		if (mode != "EDIT"){
			return;
		}
		var url = "<?= site_url() ?>master/unit/fetch_data/" + $("#fin_rec_id").val();
		$.ajax({
			typr: "GET",
			url: url,
			success: function (resp){
				if (resp.messages != ""){
					alert(resp.messages);
				}
				if (resp.status == "SUCCESS"){
					var data = resp.data;
					App.autoFillForm(data);
				}
			},
			error: function(e){
				$("#result").text(e.responseText);
				console.log("ERROR : ", e);
			}
		});
	}
</script>




<!-- Select2 -->
<script src="<?= base_url() ?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?= base_url() ?>bower_components/datatables.net/datatables.min.js"></script>