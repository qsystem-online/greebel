<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?=base_url()?>bower_components/select2/dist/css/select2.min.css">

<style type="text/css">
	.border-0{
		border: 0px;
	}
	td{
		padding: 2px; !important 		
	}

    .nav-tabs-custom>.nav-tabs>li.active>a{
        font-weight:bold;
        border-left-color: #3c8dbc;
        border-right-color: #3c8dbc;
        border-style:fixed;
    }
    .nav-tabs-custom>.nav-tabs{
        border-bottom-color: #3c8dbc;        
        border-bottom-style:fixed;
    }
</style>

<section class="content-header">
	<h1><?=lang("Master Relations")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Master Relations") ?></a></li>
		<li class="active title"><?=$title?></li>
	</ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
				<div class="box-header with-border">
				<h3 class="box-title title"><?=$title?></h3>
			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmMSRelations" class="form-horizontal" action="<?=site_url()?>pages/pr/msrelations/add" method="POST" enctype="multipart/form-data">			
				<div class="box-body">
					<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">			
					<input type="hidden" id="frm-mode" value="<?=$mode?>">

					<div class='form-group'>
                    <label for="RelationId" class="col-md-2 control-label"><?=lang("Relation ID")?> #</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="RelationId" placeholder="<?=lang("(Autonumber)")?>" name="RelationId" value="<?=$RelationId?>" readonly>
							<div id="RelationId_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="RelationType" class="col-md-2 control-label"><?=lang("Relation Type")?> *</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="RelationType" placeholder="<?=lang("Relation Type")?>" name="RelationType">
							<div id="RelationType_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
                    <label for="RelationName" class="col-md-2 control-label"><?=lang("Relation Name")?> *</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="RelationName" placeholder="<?=lang("Relation Name")?>" name="RelationName">
							<div id="RelationName_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="Address" class="col-md-2 control-label"><?=lang("Address")?></label>
						<div class="col-md-10">
							<textarea class="form-control" id="Address" placeholder="<?=lang("Address")?>" name="Address"></textarea>
							<div id="Address_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="PostalCode" class="col-md-2 control-label"><?=lang("Postal Code")?></label>
						<div class="col-md-2">
							<input type="text" class="form-control" id="PostalCode" placeholder="<?=lang("Postal Code")?>" name="PostalCode">
							<div id="PostalCode_err" class="text-danger"></div>
						</div>

					<label for="CountryId" class="col-md-2 control-label"><?=lang("Country ID")?></label>
						<div class="col-md-2">
							<input type="text" class="form-control" id="CountryId" placeholder="<?=lang("Country ID")?>" name="CountryId">
							<div id="CountryId_err" class="text-danger"></div>
						</div>
					
					<label for="ProvinceId" class="col-md-2 control-label"><?=lang("Province ID")?></label>
						<div class="col-md-2">
							<input type="text" class="form-control" id="ProvinceId" placeholder="<?=lang("Province ID")?>" name="ProvinceId">
							<div id="ProvinceId_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="DistrictId" class="col-md-2 control-label"><?=lang("District ID")?></label>
						<div class="col-md-2">
							<input type="text" class="form-control" id="DistrictId" placeholder="<?=lang("District ID")?>" name="DistrictId">
							<div id="DistrictId_err" class="text-danger"></div>
						</div>

					<label for="SubDistrictId" class="col-md-2 control-label"><?=lang("Sub District ID")?></label>
						<div class="col-md-2">
							<input type="text" class="form-control" id="SubDistrictId" placeholder="<?=lang("Sub District ID")?>" name="SubDistrictId">
							<div id="SubDistrictId_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
                    <label for="NPWP" class="col-md-2 control-label"><?=lang("NPWP")?></label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="NPWP" placeholder="<?=lang("NPWP")?>" name="NPWP">
							<div id="NPWP_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
                    <label for="RelationNotes" class="col-md-2 control-label"><?=lang("Relation Notes")?></label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="RelationNotes" placeholder="<?=lang("Relation Notes")?>" name="RelationNotes">
							<div id="RelationNotes_err" class="text-danger"></div>
						</div>
					</div>
                </div>
				<!-- end box body -->

                <div class="box-footer text-right">
                    <a id="btnSubmitAjax" href="#" class="btn btn-primary"><?=lang("Save Ajax")?></a>
                </div>
                <!-- end box-footer -->
            </form>
        </div>
    </div>
</section>

<script type="text/javascript">
	$(function(){
		<?php if($mode == "EDIT"){?>
			init_form($("#RelationId").val());
		<?php } ?>

		$("#btnSubmitAjax").click(function(event){
			event.preventDefault();
			data = new FormData($("#frmMSRelations")[0]);

			mode = $("#frm-mode").val();
			if (mode == "ADD"){
				url =  "<?= site_url() ?>pr/msrelations/ajx_add_save";
			}else{
				url =  "<?= site_url() ?>pr/msrelations/ajx_edit_save";
			}

			//var formData = new FormData($('form')[0])
			$.ajax({
				type: "POST",
				enctype: 'multipart/form-data',
				url: url,
				data: data,
				processData: false,
				contentType: false,
				cache: false,
				timeout: 600000,
				success: function (resp) {	
					if (resp.message != "")	{
						$.alert({
							title: 'Message',
							content: resp.message,
							onDestroy: function(){
								//alert('the user clicked yes');
								window.location.href = "<?= site_url() ?>pr/msrelations/lizt";
								return;
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
						$("#RelationId").val(data.insert_id);

						//Clear all previous error
						$(".text-danger").html("");

						// Change to Edit mode
						$("#frm-mode").val("EDIT");  //ADD|EDIT

						$('#RelationName').prop('readonly', true);
					}
				},
				error: function (e) {
					$("#result").text(e.responseText);
					console.log("ERROR : ", e);
					$("#btnSubmit").prop("disabled", false);
				}
			});

		});
	});

	function init_form(RelationId){
		//alert("Init Form");
		var url = "<?=site_url()?>pr/msrelations/fetch_data/" + RelationId;
		$.ajax({
			type: "GET",
			url: url,
			success: function (resp) {	
				console.log(resp.msrelations);

				$.each(resp.msrelations, function(name, val){
					var $el = $('[name="'+name+'"]'),
					type = $el.attr('type');
					switch(type){
						case 'checkbox':
							$el.attr('checked', 'checked');
							break;
						case 'radio':
							$el.filter('[value="'+val+'"]').attr('checked', 'checked');
							break;
						default:
							$el.val(val);
					}
				});
			},

			error: function (e) {
				$("#result").text(e.responseText);
				console.log("ERROR : ", e);
			}
		});
	}
</script>

<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>