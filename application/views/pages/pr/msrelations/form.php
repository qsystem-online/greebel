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
                    <label for="RelationId" class="col-md-2 control-label"><?=lang("Relation ID")?> :</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="RelationId" placeholder="<?=lang("(Autonumber)")?>" name="RelationId" value="<?=$RelationId?>" readonly>
							<div id="RelationId_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="RelationType" class="col-md-2 control-label"><?=lang("Relation Type")?> :</label>
						<div class="col-md-10">
							<select class="form-control select2" id="RelationType" name="RelationType[]"  multiple="multiple">
								<option value="1"><?=lang("Customer")?></option>
								<option value="2"><?=lang("Supplier/Vendor")?></option>
								<option value="3"><?=lang("Expedisi")?></option>
							</select>
						</div>
					</div>

					<div class="form-group relation-info">
					<label for="select-parentId" class="col-md-2 control-label"><?=lang("Customer Induk")?> :</label>
						<div class="col-md-10">
							<select id="select-parentId" class="form-control" name="fin_parent_id">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="fin_parent_id_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="select-relationgroupid" class="col-md-2 control-label"><?=lang("Relation Group Name")?> :</label>
						<div class="col-md-10">
							<select id="select-relationgroupid" class="form-control" name="RelationGroupId">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="RelationGroupId_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
                    <label for="RelationName" class="col-md-2 control-label"><?=lang("Relation Name")?> :</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="RelationName" placeholder="<?=lang("Relation Name")?>" name="RelationName">
							<div id="RelationName_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="BusinessType" class="col-md-2 control-label"><?=lang("Business Type")?> :</label>
						<div class="col-md-4">
							<select class="form-control" id="BusinessType" name="BusinessType">
								<option value='P'><?=lang("Personal")?></option>
								<option value='C'><?=lang("Corporate")?></option>
							</select>
						</div>

					<label for="Gender" class="col-md-2 control-label personal-info"><?=lang("Gender")?> :</label>
						<div class="col-md-4 personal-info">
							<select class="form-control" id="Gender" name="Gender">
								<option value="0">-- <?=lang("select")?> --</option>
								<option value="M"><?=lang("Male")?></option>
								<option value="F"><?=lang("Female")?></option>
							</select>
						</div>
					</div>

					<div class="form-group personal-info">
					<label for="BirthDate" class="col-md-2 control-label"><?=lang("Birth Date")?> :</label>
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

					<label for="BirthPlace" class="col-md-2 control-label"><?=lang("Birth Place")?> :</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="BirthPlace" placeholder="<?=lang("Birth Place")?>" name="BirthPlace">
							<div id="BirthPlace_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group personal-info">
						<label for="NIK" class="col-md-2 control-label"><?=lang("NIK")?> :</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="NIK" placeholder="<?=lang("NIK")?>" name="NIK">
							<div id="NIK_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="Address" class="col-md-2 control-label"><?=lang("Address")?> :</label>
						<div class="col-md-10">
							<textarea class="form-control" id="Address" placeholder="<?=lang("Address")?>" name="Address"></textarea>
							<div id="Address_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="fst_shipping_address" class="col-md-2 control-label"><?=lang("Shipping Address")?> :</label>
						<div class="col-md-10">
							<textarea class="form-control" id="fst_shipping_address" placeholder="<?=lang("Shipping Address")?>" name="fst_shipping_address"></textarea>
							<div id="fst_shipping_address_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="Phone" class="col-md-2 control-label"><?=lang("Phone")?> :</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="Phone" placeholder="<?=lang("Phone")?>" name="Phone">
							<div id="Phone_err" class="text-danger"></div>
						</div>

					<label for="Fax" class="col-md-2 control-label"><?=lang("Fax")?> :</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="Fax" placeholder="<?=lang("Fax")?>" name="Fax">
							<div id="Fax_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="PostalCode" class="col-md-2 control-label"><?=lang("Postal Code")?> :</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="PostalCode" placeholder="<?=lang("Postal Code")?>" name="PostalCode">
							<div id="PostalCode_err" class="text-danger"></div>
						</div>

					<label for="select-countryname" class="col-md-2 control-label"><?=lang("Country Name")?> :</label>
						<div class="col-md-4">
							<select id="select-countryname" class="form-control" name="CountryId">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="CountryName_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="select-provincename" class="col-md-2 control-label"><?=lang("Province Name")?> :</label>
						<div class="col-md-4">
							<select id="select-provincename" class="form-control" name="kode">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="nama_err" class="text-danger"></div>
						</div>

					<label for="select-districtname" class="col-md-2 control-label"><?=lang("District Name")?> :</label>
						<div class="col-md-4">
							<select id="select-districtname" class="form-control" name="kode">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="nama_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="select-subdistrictname" class="col-md-2 control-label"><?=lang("Sub District Name")?> :</label>
						<div class="col-md-4">
							<select id="select-subdistrictname" class="form-control" name="kode">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="nama_err" class="text-danger"></div>
						</div>

					<label for="select-village" class="col-md-2 control-label"><?=lang("Village Name")?> :</label>
						<div class="col-md-4">
							<select id="select-villagename" class="form-control" name="kode">
								<option value="0">-- <?=lang("select")?> --</option>
								<div id="nama_err" class="text-danger"></div>
							</select>
						</div>
					</div>

					<div class="form-group relation-info">
					<label for="select-pricinggroupname" class="col-md-2 control-label"><?=lang("Pricing Group")?> :</label>
						<div class="col-md-10">
							<select id="select-pricinggroupname" class="form-control" name="CustPricingGroupid">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="CustPricingGroupid_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
                    <label for="NPWP" class="col-md-2 control-label"><?=lang("NPWP")?> :</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="NPWP" placeholder="<?=lang("NPWP")?>" name="NPWP">
							<div id="NPWP_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="RelationNotes" class="col-md-2 control-label"><?=lang("Relation Notes")?> :</label>
						<div class="col-md-7">
							<select id="select-relationnotes" class="form-control" name="RelationNotes">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<textarea class="form-control" id="RelationNotes" name="RelationNotes"></textarea>
							<div id="RelationNotes_err" class="text-danger"></div>
						</div>
						<button id="btn-add-RelationNotes" type="button" class="btn btn-add" ><?=lang("Add")?></button>
					</div>

					<div class="form-group">
					<label for="fin_credit_limit" class="col-md-2 control-label"><?=lang("Credit Limit")?> :</label>
						<div class="col-md-4">
							<input type="text" class="form-control text-right money" id="fin_credit_limit" name="fin_credit_limit">
							<div id="fin_credit_limit_err" class="text-danger"></div>
						</div>

					<label for="fin_sales_area_id" class="col-md-2 control-label"><?=lang("Sales Area Name")?> :</label>
						<div class="col-md-4">
							<select id="select-salesArea" class="form-control" name="fin_sales_area_id">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="fin_sales_area_id_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="select-salesId" class="col-md-2 control-label"><?=lang("Sales Name")?> :</label>
						<div class="col-md-4">
							<select id="select-salesId" class="form-control" name="fin_sales_id">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="fin_sales_id_err" class="text-danger"></div>
						</div>

					<label for="select-warehouseId" class="col-md-2 control-label"><?=lang("Warehouse")?> :</label>
						<div class="col-md-4">
							<select id="select-warehouseId" class="form-control" name="fin_warehouse_id">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="fin_warehouse_id_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="fin_terms_payment" class="col-md-2 control-label"><?=lang("Terms Payment")?> :</label>
						<div class="col-md-4">
							<input type="text" class="form-control text-right" id="fin_terms_payment" placeholder="<?=lang("Terms Payment")?>" name="fin_terms_payment">
							<div id="fin_terms_payment_err" class="text-danger"></div>
						</div>
					<label for="fin_terms_payment" class="col-sm-0 control-label"><?=lang("Hari")?></label>
					</div>

					<div class="form-group">
						<label for="fin_top_komisi" class="col-md-2 control-label"><h6><i>*<?=lang("Terms Of Payment")?>*</i></h6></label>
					</div>

					<div class="form-group">
						<label for="fin_top_komisi" class="col-md-2 control-label"><?=lang("TOP Commission")?> :</label>
						<div class="col-md-4">
							<input type="text" class="form-control text-right" id="fin_top_komisi" name="fin_top_komisi" value="0">
							<div id="fin_top_komisi_err" class="text-danger"></div>
						</div>
					<label for="fin_top_komisi" class="col-sm-0 control-label"><?=lang("Hari")?></label>
					</div>
						
					<div class="form-group">
						<label for="fin_top_plus_komisi" class="col-md-2 control-label"><?=lang("TOP Plus Commission")?> :</label>
						<div class="col-md-4">
							<input type="text" class="form-control text-right" id="fin_top_plus_komisi" name="fin_top_plus_komisi" value="0">
							<div id="fin_top_plus_komisi_err" class="text-danger"></div>
						</div>
					<label for="fin_top_plus_komisi" class="col-sm-0 control-label"><?=lang("Hari")?></label>
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
			data = $("#frmMSRelations").serializeArray();

			mode = $("#frm-mode").val();
			if (mode == "ADD"){
				url =  "<?= site_url() ?>pr/msrelations/ajx_add_save";
			}else{
				url =  "<?= site_url() ?>pr/msrelations/ajx_edit_save";
			}

			//var formData = new FormData($('form')[0])
			$.ajax({
				type: "POST",
				//enctype: 'multipart/form-data',
				url: url,
				data: data,
				//processData: false,
				//contentType: false,
				//cache: false,
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

		$(".select2").select2();

		$("#select-parentId").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>pr/msrelations/get_parentId',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.RelationId,
							"text" : value.RelationName
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
				ajax: {
					url: '<?=site_url()?>pr/msrelations/get_provinces/'+$("#select-countryname").val(),
					dataType: 'json',
					delay: 250,
					processResults: function (data){
						items = [];
						data = data.data;
						$.each(data,function(index,value){
							items.push({
								"id" : value.kode,
								"text" : value.nama
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
				ajax: {
					url: '<?=site_url()?>pr/msrelations/get_districts/'+$("#select-provincename").val(),
					dataType: 'json',
					delay: 250,
					processResults: function (data){
						items = [];
						data = data.data;
						$.each(data,function(index,value){
							items.push({
								"id" : value.kode,
								"text" : value.nama
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
				ajax: {
					url: '<?=site_url()?>pr/msrelations/get_subdistricts/'+$("#select-districtname").val(),
					dataType: 'json',
					delay: 250,
					processResults: function (data){
						items = [];
						data = data.data;
						$.each(data,function(index,value){
							items.push({
								"id" : value.kode,
								"text" : value.nama
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

		$("#select-subdistrictname").change(function(event){
			event.preventDefault();
			$('#select-villagename').val(null).trigger('change');
			$("#select-villagename").select2({
				width: '100%',
				ajax: {
					url: '<?=site_url()?>pr/msrelations/get_village/'+$("#select-subdistrictname").val(),
					dataType: 'json',
					delay: 250,
					processResults: function (data){
						items = [];
						data = data.data;
						$.each(data,function(index,value){
							items.push({
								"id" : value.kode,
								"text" : value.nama
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

		$("#select-pricinggroupname").select2({
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
							"id" : value.CustPricingGroupid,
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

		$("#select-salesArea").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>pr/msrelations/get_salesArea',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.fin_sales_area_id,
							"text" : value.fst_name
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

		$("#select-salesId").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>pr/msrelations/get_salesId',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.fin_user_id,
							"text" : value.fst_username
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

		$("#select-salesId").change(function(event){
			event.preventDefault();
			$(".relation-info").show();

			$("#RelationType").each(function(index){				
				if ($(this).val() >= "2"){
					$(".relation-info").hide();
				}
			});
		});

		$("#select-warehouseId").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>pr/msrelations/get_warehouseId',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.fin_warehouse_id,
							"text" : value.fst_warehouse_name
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
			//alert("RelationNotes");
			event.preventDefault();
			var sstr = $("#RelationNotes").val();
			//alert (sstr);
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

				var newOption = new Option(resp.ms_relations.ParentName, resp.ms_relations.fin_parent_id, true, true);
				$('#select-parentId').append(newOption);
				$("#select-parentId").val(resp.ms_relations.fin_parent_id).trigger('change');

				// menampilkan data di select2, menu edit/update
				var newOption = new Option(resp.ms_relations.RelationGroupName, resp.ms_relations.RelationGroupId, true, true);
				// Append it to the select
    			$('#select-relationgroupid').append(newOption).trigger('change');

				// menampilkan data di select2, menu edit/update
				var newOption = new Option(resp.ms_relations.CountryName, resp.ms_relations.CountryId, true, true);
    			$('#select-countryname').append(newOption).trigger('change');

				var newOption = new Option(resp.ms_relations.namaprovince, resp.ms_relations.province, true, true);
				$('#select-provincename').append(newOption).trigger('change');

				var newOption = new Option(resp.ms_relations.namadistrict, resp.ms_relations.district, true, true);
				$('#select-districtname').append(newOption).trigger('change');

				var newOption = new Option(resp.ms_relations.namasubdistrict, resp.ms_relations.subdistrict, true, true);
				$('#select-subdistrictname').append(newOption).trigger('change');

				var newOption = new Option(resp.ms_relations.namavillage, resp.ms_relations.village, true, true);
				$('#select-villagename').append(newOption).trigger('change');

				var newOption = new Option(resp.ms_relations.CustPricingGroupName, resp.ms_relations.CustPricingGroupid, true, true);
				$('#select-pricinggroupname').append(newOption).trigger('change');

				var newOption = new Option(resp.ms_relations.Notes, true);
				$('#select-relationnotes').append(newOption).trigger('change');

				var newOption = new Option(resp.ms_relations.fst_name, resp.ms_relations.fin_sales_area_id, true, true);
				$('#select-salesArea').append(newOption).trigger('change');

				var newOption = new Option(resp.ms_relations.SalesName, resp.ms_relations.fin_sales_id, true, true);
				$('#select-salesId').append(newOption).trigger('change');

				var newOption = new Option(resp.ms_relations.fst_warehouse_name, resp.ms_relations.fin_warehouse_id, true, true);
				$('#select-warehouseId').append(newOption).trigger('change');

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