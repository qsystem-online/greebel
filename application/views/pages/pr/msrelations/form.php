<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?= base_url() ?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?= base_url() ?>bower_components/datatables.net/datatables.min.css">
<link rel="stylesheet" href="<?= base_url() ?>bower_components/datatables.net/dataTables.checkboxes.css">

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
				<div class="btn-group btn-group-sm  pull-right">					
					<a id="btnNew" class="btn btn-primary" href="#" title="<?=lang("Tambah Baru")?>"><i class="fa fa-plus" aria-hidden="true"></i></a>
					<a id="btnSubmitAjax" class="btn btn-primary" href="#" title="<?=lang("Simpan")?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
					<a id="btnPrint" class="btn btn-primary" href="#" title="<?=lang("Cetak")?>"><i class="fa fa-print" aria-hidden="true"></i></a>
					<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
					<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
				</div>
			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmRelation" class="form-horizontal" action="<?=site_url()?>pr/relation/add" method="POST" enctype="multipart/form-data">			
				<div class="box-body">
					<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">			
					<input type="hidden" id="frm-mode" value="<?=$mode?>">

					<div class="form-group">
                    <label for="fin_relation_id" class="col-md-2 control-label"><?=lang("Relation ID")?> :</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="fin_relation_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_relation_id" value="<?=$fin_relation_id?>" readonly>
							<div id="fin_relation_id_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="fst_relation_type" class="col-md-2 control-label"><?=lang("Relation Type")?> :</label>
						<div class="col-md-4">
							<select class="form-control select2" id="fst_relation_type" name="fst_relation_type[]"  multiple="multiple">
								<option value="1"><?=lang("Customer")?></option>
								<option value="2"><?=lang("Supplier/Vendor")?></option>
								<option value="3"><?=lang("Expedisi")?></option>
							</select>
						</div>

					<label for="fin_branch_id" class="col-md-2 control-label"><?=lang("Branch Name")?> :</label>
						<div class="col-md-4">
							<select id="select-branch" class="form-control" name="fin_branch_id">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="fin_branch_id_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="select-parentId" class="col-md-2 control-label"><?=lang("Customer Induk")?> :</label>
						<div class="col-md-4">
							<select id="select-parentId" class="form-control relation-info" name="fin_parent_id">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="fin_parent_id_err" class="text-danger"></div>
						</div>
					
					<label for="select-groupId" class="col-md-2 control-label"><?=lang("Relation Group Name")?> :</label>
						<div class="col-md-4">
							<select id="select-groupId" class="form-control" name="fin_relation_group_id">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="fin_relation_group_id_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
                    <label for="fst_relation_name" class="col-md-2 control-label"><?=lang("Relation Name")?> :</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="fst_relation_name" placeholder="<?=lang("Relation Name")?>" name="fst_relation_name">
							<div id="fst_relation_name_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="fst_business_type" class="col-md-2 control-label"><?=lang("Business Type")?> :</label>
						<div class="col-md-4">
							<select class="form-control" id="fst_business_type" name="fst_business_type">
								<option value='P'><?=lang("Personal")?></option>
								<option value='C'><?=lang("Corporate")?></option>
							</select>
						</div>

					<label for="fst_gender" class="col-md-2 control-label personal-info"><?=lang("Gender")?> :</label>
						<div class="col-md-4 personal-info">
							<select class="form-control" id="fst_gender" name="fst_gender">
								<option value="0">-- <?=lang("select")?> --</option>
								<option value="M"><?=lang("Male")?></option>
								<option value="F"><?=lang("Female")?></option>
							</select>
						</div>
					</div>

					<div class="form-group personal-info">
					<label for="fdt_birth_date" class="col-md-2 control-label"><?=lang("Birth Date")?> :</label>
						<div class="col-md-4">
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control pull-right datepicker" id="fdt_birth_date" name="fdt_birth_date"/>								
							</div>
							<div id="fdt_birth_date_err" class="text-danger"></div>
							<!-- /.input group -->
						</div>

						<label for="fst_birth_place" class="col-md-2 control-label"><?=lang("Birth Place")?> :</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_birth_place" placeholder="<?=lang("Birth Place")?>" name="fst_birth_place">
							<div id="fst_birth_place_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group personal-info">
						<label for="fst_nik" class="col-md-2 control-label"><?=lang("NIK")?> :</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="fst_nik" placeholder="<?=lang("NIK")?>" name="fst_nik">
							<div id="fst_nik_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
                    	<label for="fst_npwp" class="col-md-2 control-label"><?=lang("NPWP")?> :</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="fst_npwp" placeholder="<?=lang("NPWP")?>" name="fst_npwp">
							<div id="fst_npwp_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="fst_address" class="col-md-2 control-label"><?=lang("Address")?> :</label>
						<div class="col-md-10">
							<textarea class="form-control" id="fst_address" placeholder="<?=lang("Address")?>" name="fst_address"></textarea>
							<div id="fst_address_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="fst_phone" class="col-md-2 control-label"><?=lang("Phone")?> :</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_phone" placeholder="<?=lang("Phone")?>" name="fst_phone">
							<div id="fst_phone_err" class="text-danger"></div>
						</div>

					<label for="fst_fax" class="col-md-2 control-label"><?=lang("Fax")?> :</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_fax" placeholder="<?=lang("Fax")?>" name="fst_fax">
							<div id="fst_fax_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="fst_postal_code" class="col-md-2 control-label"><?=lang("Postal Code")?> :</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_postal_code" placeholder="<?=lang("Postal Code")?>" name="fst_postal_code">
							<div id="fst_postal_code_err" class="text-danger"></div>
						</div>

					<label for="select-country" class="col-md-2 control-label"><?=lang("Country Name")?> :</label>
						<div class="col-md-4">
							<select id="select-country" class="form-control" name="fin_country_id">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="fst_country_name_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="select-provinces" class="col-md-2 control-label"><?=lang("Province Name")?> :</label>
						<div class="col-md-4">
							<select id="select-provinces" class="form-control" name="fst_kode">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="fst_nama__err" class="text-danger"></div>
						</div>

					<label for="select-district" class="col-md-2 control-label"><?=lang("District Name")?> :</label>
						<div class="col-md-4">
							<select id="select-district" class="form-control" name="fst_kode">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="fst_nama__err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="select-subdistrict" class="col-md-2 control-label"><?=lang("Sub District Name")?> :</label>
						<div class="col-md-4">
							<select id="select-subdistrict" class="form-control" name="fst_kode">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="fst_nama__err" class="text-danger"></div>
						</div>

					<label for="select-village" class="col-md-2 control-label"><?=lang("Village Name")?> :</label>
						<div class="col-md-4">
							<select id="select-village" class="form-control" name="fst_kode">
								<option value="0">-- <?=lang("select")?> --</option>
								<div id="fst_nama__err" class="text-danger"></div>
							</select>
						</div>
					</div>

					<div class="form-group relation-info">
					<label for="select-custpricing" class="col-md-2 control-label"><?=lang("Pricing Group")?> :</label>
						<div class="col-md-10">
							<select id="select-custpricing" class="form-control" name="fin_cust_pricing_group_id">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="fin_cust_pricing_group_id_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="fst_relation_notes" class="col-md-2 control-label"><?=lang("Relation Notes")?> :</label>
						<div class="col-md-7">
							<select id="select-notes" class="form-control" name="fst_relation_notes">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<textarea class="form-control" id="fst_relation_notes" name="fst_relation_notes"></textarea>
							<div id="fst_relation_notes_err" class="text-danger"></div>
						</div>
						<button id="btn-add-fst_relation_notes" type="button" class="btn btn-add" ><?=lang("Add")?></button>
					</div>

					<div class="form-group">
					<label for="fdc_credit_limit" class="col-md-2 control-label"><?=lang("Credit Limit")?> :</label>
						<div class="col-md-4">
							<input type="text" class="form-control text-right money" id="fdc_credit_limit" name="fdc_credit_limit">
							<div id="fdc_credit_limit_err" class="text-danger"></div>
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

					<label for="select-warehouse" class="col-md-2 control-label"><?=lang("Warehouse")?> :</label>
						<div class="col-md-4">
							<select id="select-warehouse" class="form-control" name="fin_warehouse_id">
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

				<?php $displaytabs = ($mode == "ADD") ? "none" : "" ?>
				<div class="nav-tabs-custom" style="display:unset">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#shipping_details" data-toggle="tab" aria-expanded="true"><?= lang("Shipping Address") ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="shipping_details">
							<form class="form-horizontal edit-mode ">	
								<div class="form-group">
									<div class="col-md-12">
									<button id="btn-add-shipping" class="btn btn-primary btn-sm pull-right edit-mode" style="margin-bottom:20px"><i class="fa fa-cart-plus" aria-hidden="true"></i>&nbsp;&nbsp;<?= lang("Add Shipping") ?></button>
									</div>	
								</div>
                            </form>
							<table id="tbl_shipping_details" class="table table-bordered table-hover" style="width:100%;"></table>
						</div>
					</div>
					<!-- /.tab-pane -->
				</div>
				<!-- /.tab-content -->

                <div class="box-footer text-right">
                    
                </div>
                <!-- end box-footer -->
            </form>
        </div>
    </div>
</section>
</div>


<!--- // START TAB SHIPPING ADDRESS \\ ---------------------------------------------------------------------------------------------------------------->

<div id="mdlShippingDetails" class="modal fade in" role="dialog" style="display: none">
    <div class="modal-dialog" style="display:table;width:75%;min-width:750px;max-width:100%">
        <!-- modal content -->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">x</button>
				<h4 class="modal-title"><?=lang("Add Shipping Address")?></h4>
			</div>

			<div class="modal-body">
				<form  class="form-horizontal">
					<div class="form-group">
						<label for="fst_name" class="col-md-2 control-label"><?=lang("Name")?> :</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="fst_name" name="fst_name">
							<div id="fst_name_err" class="text-danger"></div>
						</div>
					</div>
				
					<div class="form-group">
						<label for="provinceShipp" class="col-md-2 control-label"><?=lang("Province Name")?> :</label>
						<div class="col-md-4">
							<select id="provinceShipp" class="form-control" name="fst_kode">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="fst_nama_err" class="text-danger"></div>
						</div>

						<label for="districtShipp" class="col-md-2 control-label"><?=lang("District Name")?> :</label>
						<div class="col-md-4">
							<select id="districtShipp" class="form-control" name="fst_kode">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="fst_nama_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="subdistrictShipp" class="col-md-2 control-label"><?=lang("Sub District Name")?> :</label>
						<div class="col-md-4">
							<select id="subdistrictShipp" class="form-control" name="fst_kode">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="fst_nama_err" class="text-danger"></div>
						</div>

						<label for="villageShipp" class="col-md-2 control-label"><?=lang("Village Name")?> :</label>
						<div class="col-md-4">
							<select id="villageShipp" class="form-control" name="fst_kode">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="fst_nama_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="fst_shipping_address" class="col-md-2 control-label"><?=lang("Shipping Address")?> :</label>
						<div class="col-md-10">
							<textarea class="form-control" id="fst_shipping_address" name="fst_shipping_address"></textarea>
							<div id="fst_shipping_address_err" class="text-danger"></div>
						</div>
					</div>
				</form>
			</div>

			<div class="modal-footer">
				<button id="btn-add-shippDetails" type="button" class="btn btn-primary" ><?=lang("Add")?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?=lang("Close")?></button>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		var action = '<a class="btn-edit" href="#" data-toggle="" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp; <a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
		$(function() {

			$("#btn-add-shipping").click(function(event) {
				event.preventDefault();
				$("#mdlShippingDetails").modal('show');
			});

			$("#tbl_shipping_details").DataTable({
				searching: false,
				paging: false,
				info:false,
				columns:[
					{"title": "<?=lang("Shipping Address ID")?>","width": "15%",data: "fin_shipping_address_id",visible: true},
					{"title": "<?=lang("Relation ID")?>","width": "10%",data: "fin_relation_id",visible: false},
					{"title": "<?=lang("Name")?>","width": "20%",data: "fst_name",visible: true},
					{"title": "<?=lang("Area Code")?>","width": "15%",data: "fst_kode",visible: true},
					{"title": "<?=lang("Shipping Address")?>","width": "20%",data: "fst_shipping_address", visible: true},
					{"title": "<?= lang("Action")?>","width": "10%",render: function(data, type, row) {
                            action = "<a class='btn-delete-shipping-details edit-mode' href='#'><i class='fa fa-trash'></i></a>&nbsp;";
                            return action;
                        },
						"sortable":false,"className":"dt-body-center text-center"}
				],
			});

			$("#tbl_shipping_details").on("click", ".btn-delete-shipping-details", function(event) {
				event.preventDefault();
				t = $('#tbl_shipping_details').DataTable();
				var trRow = $(this).parents('tr');
				t.row(trRow).remove().draw();
			});

			$("#select-country").change(function(event){
				event.preventDefault();
				$('#provinceShipp').val(null).trigger('change');
				$("#provinceShipp").select2({
					width: '100%',
					ajax: {
						url: '<?=site_url()?>pr/relation/get_dataProvince/'+ $("#select-country").val(),
						dataType: 'json',
						delay: 250,
						processResults: function (data){
							data2 = [];
							data = data.data;
							$.each(data,function(index,value){
								data2.push({
									"id" : value.fst_kode,
									"text" : value.fst_nama
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

			$("#provinceShipp").change(function(event){
				event.preventDefault();
				$('#districtShipp').val(null).trigger('change');
				$("#districtShipp").select2({
					width: '100%',
					ajax: {
						url: '<?=site_url()?>pr/relation/get_district/'+ $("#provinceShipp").val(),
						dataType: 'json',
						delay: 250,
						processResults: function (data){
							data2 = [];
							data = data.data;
							$.each(data,function(index,value){
								data2.push({
									"id" : value.fst_kode,
									"text" : value.fst_nama
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

			$("#districtShipp").change(function(event){
				event.preventDefault();
				$('#subdistrictShipp').val(null).trigger('change');
				$("#subdistrictShipp").select2({
					width: '100%',
					ajax: {
						url: '<?=site_url()?>pr/relation/get_subdistrict/'+ $("#districtShipp").val(),
						dataType: 'json',
						delay: 250,
						processResults: function (data){
							data2 = [];
							data = data.data;
							$.each(data,function(index,value){
								data2.push({
									"id" : value.fst_kode,
									"text" : value.fst_nama
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

			$("#subdistrictShipp").change(function(event){
				event.preventDefault();
				$('#villageShipp').val(null).trigger('change');
				$("#villageShipp").select2({
					width: '100%',
					ajax: {
						url: '<?=site_url()?>pr/relation/get_village/'+ $("#subdistrictShipp").val(),
						dataType: 'json',
						delay: 250,
						processResults: function (data){
							data2 = [];
							data = data.data;
							$.each(data,function(index,value){
								data2.push({
									"id" : value.fst_kode,
									"text" : value.fst_nama
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

			var selected_areaCode;
			$('#provinceShipp').on('select2:select', function(e) {
				console.log(selected_areaCode);
				var data = e.params.data;
				selected_areaCode = data;
			})

			$('#districtShipp').on('select2:select', function(e) {
				console.log(selected_areaCode);
				var data = e.params.data;
				selected_areaCode = data;
			})

			$('#subdistrictShipp').on('select2:select', function(e) {
				console.log(selected_areaCode);
				var data = e.params.data;
				selected_areaCode = data;
			})

			$('#villageShipp').on('select2:select', function(e) {
				console.log(selected_areaCode);
				var data = e.params.data;
				selected_areaCode = data;
			})

			$("#btn-add-shippDetails").click(function(event) {
				event.preventDefault();
				t = $('#tbl_shipping_details').DataTable();
				t.row.add({
					fin_shipping_address_id: 0,
					fin_relation_id: 0,
					fst_kode: selected_areaCode.id,
					fst_nama: selected_areaCode.text,
					fst_name: $("#fst_name").val(),
					fst_shipping_address: $("#fst_shipping_address").val(),
					action:action
				}).draw(false);
			});
		});	
	</script>
</div>

<!--- // END TAB SHIPPING ADDRESS \\ ------------------------------------------------------------------------------------------------------------------>


<script type="text/javascript">
	$(function(){

		<?php if($mode == "EDIT"){?>
			init_form($("#fin_relation_id").val());
		<?php } ?>

		$("#btnSubmitAjax").click(function(event){
			event.preventDefault();
			data = $("#frmRelation").serializeArray();
			//data = new FormData($("#frmRelation")[0]);
			detail = new Array();
            t = $('#tbl_shipping_details').DataTable();
			datas = t.data();
            $.each(datas, function(i, v) {
                detail.push(v);
            });
			data.push({
				name:"shippingDetail",
				value: JSON.stringify(detail)
			});
			//data.append("detail",JSON.stringify(detail));

			mode = $("#frm-mode").val();
			if (mode == "ADD"){
				url =  "<?= site_url() ?>pr/relation/ajx_add_save";
			}else{
				url =  "<?= site_url() ?>pr/relation/ajx_edit_save";
			}
			console.log(data);

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
										window.location.href = "<?= site_url() ?>pr/relation/lizt";
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
						$("#fin_relation_id").val(data.insert_id);

						// Clear all previous error \\
						$(".text-danger").html("");

						// Change to Edit mode \\
						$("#frm-mode").val("EDIT");  //ADD|EDIT
						$('#fst_relation_name').prop('readonly', true);
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
				url: '<?=site_url()?>pr/relation/get_parent_id',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.fin_relation_id,
							"text" : value.fst_relation_name
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

		$("#select-branch").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>pr/relation/get_branch',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.fin_branch_id,
							"text" : value.fst_branch_name
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

		$("#select-groupId").select2({
			width: '100%',
			tokenSeparators: [",", " "],
			ajax: {
				url: '<?=site_url()?>pr/relation/get_relation_group',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.fin_relation_group_id,
							"text" : value.fst_relation_group_name
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

		$("#fst_business_type").change(function(event){
			event.preventDefault();
			$(".personal-info").hide();

			$("#fst_business_type").each(function(index){				
				if ($(this).val() == "P"){
					$(".personal-info").show();
				} 
			});
		});

		$("#select-country").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>pr/relation/get_countries',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.fin_country_id,
							"text" : value.fst_country_name
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

		$("#select-country").change(function(event){
			event.preventDefault();
			$('#select-provinces').val(null).trigger('change');
			$("#select-provinces").select2({
				width: '100%',
				ajax: {
					url: '<?=site_url()?>pr/relation/get_provinces/'+$("#select-country").val(),
					dataType: 'json',
					delay: 250,
					processResults: function (data){
						items = [];
						data = data.data;
						$.each(data,function(index,value){
							items.push({
								"id" : value.fst_kode,
								"text" : value.fst_nama
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

		$("#select-provinces").change(function(event){
			event.preventDefault();
			$('#select-district').val(null).trigger('change');
			$("#select-district").select2({
				width: '100%',
				ajax: {
					url: '<?=site_url()?>pr/relation/get_district/'+$("#select-provinces").val(),
					dataType: 'json',
					delay: 250,
					processResults: function (data){
						items = [];
						data = data.data;
						$.each(data,function(index,value){
							items.push({
								"id" : value.fst_kode,
								"text" : value.fst_nama
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

		$("#select-district").change(function(event){
			event.preventDefault();
			$('#select-subdistrict').val(null).trigger('change');
			$("#select-subdistrict").select2({
				width: '100%',
				ajax: {
					url: '<?=site_url()?>pr/relation/get_subdistrict/'+$("#select-district").val(),
					dataType: 'json',
					delay: 250,
					processResults: function (data){
						items = [];
						data = data.data;
						$.each(data,function(index,value){
							items.push({
								"id" : value.fst_kode,
								"text" : value.fst_nama
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

		$("#select-subdistrict").change(function(event){
			event.preventDefault();
			$('#select-village').val(null).trigger('change');
			$("#select-village").select2({
				width: '100%',
				ajax: {
					url: '<?=site_url()?>pr/relation/get_village/'+$("#select-subdistrict").val(),
					dataType: 'json',
					delay: 250,
					processResults: function (data){
						items = [];
						data = data.data;
						$.each(data,function(index,value){
							items.push({
								"id" : value.fst_kode,
								"text" : value.fst_nama
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

		$("#select-custpricing").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>pr/relation/get_cust_pricing_group',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.fin_cust_pricing_group_id,
							"text" : value.fst_cust_pricing_group_name
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

		$("#fst_relation_type").change(function(event){
			event.preventDefault();
			$(".relation-info").show();

			$("#fst_relation_type").each(function(index){				
				if ($(this).val() >= "2"){
					$(".relation-info").hide();
				}
			});
		});

		$("#select-notes").select2({
			width: '100%',
			tokenSeparators: [",", " "],
			ajax: {
				url: '<?=site_url()?>pr/relation/get_relation_print_out_note',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.fin_note_id,
							"text" : value.fst_notes
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
				url: '<?=site_url()?>pr/relation/get_sales_area',
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
				url: '<?=site_url()?>pr/relation/get_sales_id',
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

			$("#fst_relation_type").each(function(index){				
				if ($(this).val() >= "2"){
					$(".relation-info").hide();
				}
			});
		});

		$("#select-branch").change(function(event) {
            event.preventDefault();
			$('#select-warehouse').val(null).trigger('change');
			$("#select-warehouse").select2({
				width: '100%',
				ajax: {
					url: '<?=site_url()?>pr/relation/get_warehouse/' + $("#select-branch").val(),
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
		});

		var newline = "\r\n";
		var data = "";
		
		$('#select-notes').on('select2:select', function (e) {
			data = e.params.data;
			//var selected_fst_relation_notes = data;
		});

		$("#btn-add-fst_relation_notes").click(function(event){
			//alert("fst_relation_notes");
			event.preventDefault();
			var sstr = $("#fst_relation_notes").val();
			//alert (sstr);
			$("#fst_relation_notes").val(sstr + data.text + "\r\n");
			//console.log(selected_fst_relation_notes);
		});
	});


	function init_form(fin_relation_id){
		//alert("Init Form");
		var url = "<?=site_url()?>pr/relation/fetch_data/" + fin_relation_id;
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

				var fst_relation_type = resp.ms_relations.fst_relation_type.split(",");
				console.log(fst_relation_type);
				$("#fst_relation_type").val(fst_relation_type).trigger('change');

				$("#fst_business_type").each(function(index){				
					if ($(this).val() == "C"){
						$(".personal-info").hide();
					} 
				});

				$("#fst_relation_type").each(function(index){			
					if ($(this).val() == "1"){
						$(".relation-info").show();
					} 
				});

				$("#fdt_birth_date").datepicker('update', dateFormat(resp.ms_relations.fdt_birth_date));

				var newOption = new Option(resp.ms_relations.ParentName, resp.ms_relations.fin_parent_id, true, true);
				$('#select-parentId').append(newOption);
				$("#select-parentId").val(resp.ms_relations.fin_parent_id).trigger('change');

				var newOption = new Option(resp.ms_relations.fst_branch_name, resp.ms_relations.fin_branch_id, true, true);
				$('#select-branch').append(newOption).trigger('change');

				// menampilkan data di select2, menu edit/update \\
				var newOption = new Option(resp.ms_relations.fst_relation_group_name, resp.ms_relations.fin_relation_group_id, true, true);
				// Append it to the select
    			$('#select-groupId').append(newOption).trigger('change');

				// menampilkan data di select2, menu edit/update \\
				var newOption = new Option(resp.ms_relations.fst_country_name, resp.ms_relations.fin_country_id, true, true);
    			$('#select-country').append(newOption).trigger('change');

				var newOption = new Option(resp.ms_relations.fst_province_name, resp.ms_relations.provinces, true, true);
				$('#select-provinces').append(newOption).trigger('change');

				var newOption = new Option(resp.ms_relations.fst_district_name, resp.ms_relations.district, true, true);
				$('#select-district').append(newOption).trigger('change');

				var newOption = new Option(resp.ms_relations.fst_subdistrict_name, resp.ms_relations.subdistrict, true, true);
				$('#select-subdistrict').append(newOption).trigger('change');

				var newOption = new Option(resp.ms_relations.fst_village_name, resp.ms_relations.village, true, true);
				$('#select-village').append(newOption).trigger('change');

				var newOption = new Option(resp.ms_relations.fst_cust_pricing_group_name, resp.ms_relations.fin_cust_pricing_group_id, true, true);
				$('#select-custpricing').append(newOption).trigger('change');

				var newOption = new Option(resp.ms_relations.fst_notes, true);
				$('#select-notes').append(newOption).trigger('change');

				var newOption = new Option(resp.ms_relations.fst_name, resp.ms_relations.fin_sales_area_id, true, true);
				$('#select-salesArea').append(newOption).trigger('change');

				var newOption = new Option(resp.ms_relations.SalesName, resp.ms_relations.fin_sales_id, true, true);
				$('#select-salesId').append(newOption).trigger('change');

				var newOption = new Option(resp.ms_relations.fst_warehouse_name, resp.ms_relations.fin_warehouse_id, true, true);
				$('#select-warehouse').append(newOption).trigger('change');

				// POPULATE SHIPPING DETAILS \\
				$.each(resp.ms_shipping, function(name, val) {
                    console.log(val);
                    //event.preventDefault();
                    t = $('#tbl_shipping_details').DataTable();
                    t.row.add({
                        fin_shipping_address_id: val.fin_shipping_address_id,
                        fin_relation_id: val.fin_relation_id,
						fst_name: val.fst_name,
						fst_kode: val.fst_province_name,
						fst_kode: val.fst_district_name,
						fst_kode: val.fst_subdistrict_name,
						fst_kode: val.fst_village_name,
                        fst_shipping_address: val.fst_shipping_address,
                        action: action
                    }).draw(false);
				})
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
<!-- DataTables -->
<script src="<?= base_url() ?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?= base_url() ?>bower_components/datatables.net/dataTables.checkboxes.min.js"></script>
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