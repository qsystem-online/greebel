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
            <form id="frmMSRelations" class="form-horizontal" action="<?=site_url()?>pr/msrelations/add" method="POST" enctype="multipart/form-data">			
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
							<select class="form-control select2" id="RelationType" name="RelationType[]"  multiple="multiple">
								<?php foreach ($msrelationgroups as $msrelationgroups) {	?>
									<option value='<?= $msrelationgroups->RelationGroupId?>'><?= $msrelationgroups->RelationGroupName ?> </option>	
								<?php } ?>								
							</select>
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
					<label for="BusinessType" class="col-md-2 control-label"><?=lang("Business Type")?> *</label>
						<div class="col-md-4">
							<select class="form-control" id="BusinessType" name="BusinessType">
								<option value='P'><?=lang("Personal")?></option>
								<option value='C'><?=lang("Corporate")?></option>
							</select>
						</div>
					</div>

					<div class="form-group">
					<label for="Gender" class="col-md-2 control-label"><?=lang("Gender")?></label>
						<div class="col-md-4">
							<select class="form-control" id="Gender" name="Gender">
								<option value='M'><?=lang("Male")?></option>
								<option value='F'><?=lang("Female")?></option>
							</select>
						</div>
					</div>

					<div class="form-group">
					<label for="BirthDate" class="col-md-2 control-label"><?=lang("Birth Date")?> *</label>
						<div class="col-md-4">
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control pull-right datepicker" id="BirthDate" name="BirthDate"/>								
							</div>
							<div id="BirthDate_err" class="text-danger"></div>
							<!-- /.input group -->
						</div>

					<label for="BirthPlace" class="col-md-2 control-label"><?=lang("Birth Place")?> </label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="BirthPlace" placeholder="<?=lang("Birth Place")?>" name="BirthPlace">
							<div id="BirthPlace_err" class="text-danger"></div>
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
					<label for="Phone" class="col-md-2 control-label"><?=lang("Phone")?> </label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="Phone" placeholder="<?=lang("Phone")?>" name="Phone">
							<div id="Phone_err" class="text-danger"></div>
						</div>

					<label for="Fax" class="col-md-2 control-label"><?=lang("Fax")?> </label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="Fax" placeholder="<?=lang("Fax")?>" name="Fax">
							<div id="Fax_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="PostalCode" class="col-md-2 control-label"><?=lang("Postal Code")?></label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="PostalCode" placeholder="<?=lang("Postal Code")?>" name="PostalCode">
							<div id="PostalCode_err" class="text-danger"></div>
						</div>

					<label for="select-CountryId" class="col-md-2 control-label"><?=lang("Country ID")?></label>
						<div class="col-md-4">
						<select id="select-CountryId" class="form-control select2" name="CountryId"></select>
							<div id="CountryId_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="select-ProvinceId" class="col-md-2 control-label"><?=lang("Province ID")?></label>
						<div class="col-md-4">
						<select id="select-ProvinceId" class="form-control select2" name="ProvinceId"></select>
							<div id="ProvinceId_err" class="text-danger"></div>
						</div>

					<label for="select-DistrictId" class="col-md-2 control-label"><?=lang("District ID")?></label>
						<div class="col-md-4">
						<select id="select-DistrictId" class="form-control select2" name="DistrictId"></select>
							<div id="DistrictId_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="select-SubDistrictId" class="col-md-2 control-label"><?=lang("Sub District ID")?></label>
						<div class="col-md-4">
						<select id="select-SubDistrictId" class="form-control select2" name="SubDistrictId"></select>
							<div id="SubDistrictId_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="CustPricingGroupid" class="col-md-2 control-label"><?=lang("Cust Pricing Group ID")?></label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="CustPricingGroupid" placeholder="<?=lang("Cust Pricing Group ID")?>" name="CustPricingGroupid">
							<div id="CustPricingGroupid_err" class="text-danger"></div>
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
			console.log(data);

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
							buttons : {
								OK : function(){
									if(resp.status == "SUCCESS"){
										window.location.href = "<?= site_url() ?>pr/msrelations/lizt";
										return;
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

		$("#select-CountryId"),select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>pr/msrelations/get_data_CountryId',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					data2 = [];
					$.each(data,function(index,value){
						data2.push({
							"id" : value.CountryId
						});
					});
					console.log(data2);
					return {
						result: data2
					};
				},
				cache: true,
			}
		});

		$("#select-ProvinceId"),select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>pr/msrelations/get_data_ProvinceId',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					data2 = [];
					$.each(data,function(index,value){
						data2.push({
							"id" : value.ProvinceId
						});
					});
					console.log(data2);
					return {
						result: data2
					};
				},
				cache: true,
			}
		});

		$("#select-DistrictId"),select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>pr/msrelations/get_data_DistrictId',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					data2 = [];
					$.each(data,function(index,value){
						data2.push({
							"id" : value.DistrictId
						});
					});
					console.log(data2);
					return {
						result: data2
					};
				},
				cache: true,
			}
		});

		$("#select-SubDistrictId"),select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>pr/msrelations/get_data_SubDistrictId',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					data2 = [];
					$.each(data,function(index,value){
						data2.push({
							"id" : value.SubDistrictId
						});
					});
					console.log(data2);
					return {
						result: data2
					};
				},
				cache: true,
			}
		});

		$(".datepicker").datepicker({
			format:"yyyy-mm-dd"
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
							console.log(val);
					}
				});

				$("#BirthDate").datepicker('update', dateFormat(resp.msrelations.BirthDate));

				// menampilkan data di select2
				var newOption = new Option(resp.msrelations.CountryId, true);
				var newOption = new Option(resp.msrelations.ProvinceId, true);
				var newOption = new Option(resp.msrelations.DistrictId, true);
				var newOption = new Option(resp.msrelations.SubDistrictId, true);
    			// Append it to the select
    			$('#select-CountryId').append(newOption).trigger('change');
				$('#select-ProvinceId').append(newOption).trigger('change');
				$('#select-DistrictId').append(newOption).trigger('change');
				$('#select-SubDistrictId').append(newOption).trigger('change');
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