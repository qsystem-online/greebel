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

					<div class="form-group">
                    <label for="RelationId" class="col-md-2 control-label"><?=lang("Relation ID")?> #</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="RelationId" placeholder="<?=lang("(Autonumber)")?>" name="RelationId" value="<?=$RelationId?>" readonly>
							<div id="RelationId_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="select-relationgroupid" class="col-md-2 control-label"><?=lang("Relation Group Name")?></label>
						<div class="col-md-10">
							<select id="select-relationgroupid" class="form-control" name="RelationGroupId">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="RelationGroupId_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="RelationType" class="col-md-2 control-label"><?=lang("Relation Type")?> *</label>
						<div class="col-md-10">
							<select class="form-control select2" id="RelationType" name="RelationType[]"  multiple="multiple">
								<option value="1"><?=lang("Customer")?></option>
								<option value="2"><?=lang("Supplier/Vendor")?></option>
								<option value="3"><?=lang("Expedisi")?></option>
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

					<label for="Gender" class="col-md-2 control-label personal-info"><?=lang("Gender")?></label>
						<div class="col-md-4 personal-info">
							<select class="form-control" id="Gender" name="Gender">
								<option value="0">-- <?=lang("select")?> --</option>
								<option value="M"><?=lang("Male")?></option>
								<option value="F"><?=lang("Female")?></option>
							</select>
						</div>
					</div>

					<div class="form-group personal-info">
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

					<label for="select-countryname" class="col-md-2 control-label"><?=lang("Country Name")?></label>
						<div class="col-md-4">
							<select id="select-countryname" class="form-control" name="CountryId">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="CountryName_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="select-provincename" class="col-md-2 control-label"><?=lang("Province Name")?></label>
						<div class="col-md-4">
							<select id="select-provincename" class="form-control" name="ProvinceId">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="ProvinceName_err" class="text-danger"></div>
						</div>

					<label for="select-districtname" class="col-md-2 control-label"><?=lang("District Name")?></label>
						<div class="col-md-4">
							<select id="select-districtname" class="form-control" name="DistrictId">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="DistrictName_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="select-subdistrictname" class="col-md-2 control-label"><?=lang("Sub District Name")?></label>
						<div class="col-md-4">
							<select id="select-subdistrictname" class="form-control" name="SubDistrictId">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="SubDistrictName_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group relation-info">
					<label for="select-custpricinggroupname" class="col-md-2 control-label"><?=lang("CustPricingGroup Name")?></label>
						<div class="col-md-10">
							<select id="select-custpricinggroupname" class="form-control" name="CustPricingGroupId">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="CustPricingGroupId_err" class="text-danger"></div>
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
							<div class="col-md-7">
								<select id="select-relationnotes" class="form-control" name="RelationNotes">
									<option value="0">-- <?=lang("select")?> --</option>
								</select>
								<textarea class="form-control" id="RelationNotes" name="RelationNotes"></textarea>
								<div id="RelationNotes_err" class="text-danger"></div>
							</div>
						<button id="btn-add-RelationNotes" type="button" class="btn btn-add" ><?=lang("Add")?></button>
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
						//$("#tabs-relation-detail").show();
					}
				},
				error: function (e) {
					$("#result").text(e.responseText);
					console.log("ERROR : ", e);
					$("#btnSubmit").prop("disabled", false);
				}

			});
		});

		$(".select2").select2();

		$("#select-relationgroupid").select2({
			width: '100%',
			tokenSeparators: [",", " "],
			ajax: {
				url: '<?=site_url()?>pr/msrelations/get_msrelationgroups',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.RelationGroupId,
							"text" : value.RelationGroupName
						});
					});
					console.log(items);
					return {
						results: items
					};
				},
				cache: true,
			}
		});

		$("#BusinessType").change(function(event){
			event.preventDefault();
			$(".personal-info").hide();

			$("#BusinessType").each(function(index){				
				if ($(this).val() == "P"){
					$(".personal-info").show();
				} 
			});
		});

		$("#select-countryname").select2({
			width: '100%',
			minimumInputLength: 3,
			tokenSeparators: [",", " "],
			ajax: {
				url: '<?=site_url()?>pr/msrelations/get_mscountries',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.CountryId,
							"text" : value.CountryName
						});
					});
					console.log(items);
					return {
						results: items
					};
				},
				cache: true,
			}
		});

		$("#select-countryname").change(function(event){
			event.preventDefault();
			$('#select-provincename').val(null).trigger('change');
			$("#select-provincename").select2({
				width: '100%',
				tokenSeparators: [",", " "],
				ajax: {
					url: '<?=site_url()?>pr/msrelations/get_msprovinces/'+$("#select-countryname").val(),
					dataType: 'json',
					delay: 250,
					processResults: function (data){
						items = [];
						data = data.data;
						$.each(data,function(index,value){
							items.push({
								"id" : value.ProvinceId,
								"text" : value.ProvinceName
							});
						});
						console.log(items);
						return {
							results: items
						};
					},
					cache: true,
				}
			});
		});

		$("#select-provincename").change(function(event){
			event.preventDefault();
			$('#select-districtname').val(null).trigger('change');
			$("#select-districtname").select2({
				width: '100%',
				tokenSeparators: [",", " "],
				ajax: {
					url: '<?=site_url()?>pr/msrelations/get_msdistricts/'+$("#select-provincename").val(),
					dataType: 'json',
					delay: 250,
					processResults: function (data){
						items = [];
						data = data.data;
						$.each(data,function(index,value){
							items.push({
								"id" : value.DistrictId,
								"text" : value.DistrictName
							});
						});
						console.log(items);
						return {
							results: items
						};
					},
					cache: true,
				}
			});
		});

		$("#select-districtname").change(function(event){
			event.preventDefault();
			$('#select-subdistrictname').val(null).trigger('change');
			$("#select-subdistrictname").select2({
				width: '100%',
				tokenSeparators: [",", " "],
				ajax: {
					url: '<?=site_url()?>pr/msrelations/get_mssubdistricts/'+$("#select-districtname").val(),
					dataType: 'json',
					delay: 250,
					processResults: function (data){
						data2 = [];
						data = data.data;
						$.each(data,function(index,value){
							data2.push({
								"id" : value.SubDistrictId,
								"text" : value.SubDistrictName
							});
						});
						console.log(data2);
						return {
							results: data2
						};
					},
					cache: true,
				}
			});
		});

		$("#select-custpricinggroupname").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>pr/msrelations/get_mscustpricinggroups',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.CustPricingGroupId,
							"text" : value.CustPricingGroupName
						});
					});
					console.log(items);
					return {
						results: items
					};
				},
				cache: true,
			}
		});

		$("#RelationType").change(function(event){
			event.preventDefault();
			$(".relation-info").show();

			$("#RelationType").each(function(index){				
				if ($(this).val() >= "2"){
					$(".relation-info").hide();
				}
			});
		});

		$("#select-relationnotes").select2({
			width: '100%',
			tokenSeparators: [",", " "],
			ajax: {
				url: '<?=site_url()?>pr/msrelations/get_msrelationprintoutnotes',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.NoteId,
							"text" : value.Notes
						});
					});
					console.log(items);
					return {
						results: items
					};
				},
				cache: true,
			}
		});
		
		var newline = "\r\n";
		var data = "";
		
		$('#select-relationnotes').on('select2:select', function (e) {
			data = e.params.data;
			//var selected_relationnotes = data;
		});

		$("#btn-add-RelationNotes").click(function(event){
			event.preventDefault();
			var sstr = $("#RelationNotes").val();alert (sstr);
			$("#RelationNotes").val(sstr + data.text + "\r\n");
			//console.log(selected_relationnotes);
		});
	});

	function init_form(RelationId){
		//alert("Init Form");
		var url = "<?=site_url()?>pr/msrelations/fetch_data/" + RelationId;
		$.ajax({
			type: "GET",
			url: url,
			success: function (resp) {	
				console.log(resp.ms_relations);

				$.each(resp.ms_relations, function(name, val){
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

				var relationType = resp.ms_relations.RelationType.split(",");
				console.log(relationType);
				$("#RelationType").val(relationType).trigger('change');

				$("#BusinessType").each(function(index){				
					if ($(this).val() == "C"){
						$(".personal-info").hide();
					} 
				});

				$("#RelationType").each(function(index){				
					if ($(this).val() == "1"){
						$(".relation-info").show();
					} 
				});

				$("#BirthDate").datepicker('update', dateFormat(resp.ms_relations.BirthDate));

				// menampilkan data di select2, menu edit/update
				var newOption = new Option(resp.ms_relations.RelationGroupName, resp.ms_relations.RelationGroupId, true, true);
				// Append it to the select
    			$('#select-relationgroupid').append(newOption).trigger('change');

				// menampilkan data di select2, menu edit/update
				var newOption = new Option(resp.ms_relations.CountryName, resp.ms_relations.CountryId, true, true);
				// Append it to the select
    			$('#select-countryname').append(newOption).trigger('change');

				var newOption = new Option(resp.ms_relations.ProvinceName, resp.ms_relations.ProvinceId, true, true);
				$('#select-provincename').append(newOption).trigger('change');

				var newOption = new Option(resp.ms_relations.DistrictName, resp.ms_relations.DistrictId, true, true);
				$('#select-districtname').append(newOption).trigger('change');

				var newOption = new Option(resp.ms_relations.SubDistrictName, resp.ms_relations.SubDistrictId, true, true);
				$('#select-subdistrictname').append(newOption).trigger('change');

				var newOption = new Option(resp.ms_relations.CustPricingGroupName, resp.ms_relations.CustPricingGroupId, true, true);
				$('#select-custpricinggroupname').append(newOption).trigger('change');

				var newOption = new Option(resp.ms_relations.Notes, true);
				$('#select-relationnotes').append(newOption).trigger('change');

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

<script type="text/javascript">
    $(function(){
        $(".select2-container").addClass("form-control"); 
        $(".select2-selection--single , .select2-selection--multiple").css({
            "border":"0px solid #000",
            "padding":"0px 0px 0px 0px"
        });         
        $(".select2-selection--multiple").css({
            "margin-top" : "-5px",
            "background-color":"unset"
        });
    });
</script>