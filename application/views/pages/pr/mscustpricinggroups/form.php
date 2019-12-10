<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?=base_url()?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

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
	<h1><?=lang("Master Cust Pricing Groups")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Master Cust Pricing Groups") ?></a></li>
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
					<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
					<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
				</div>
			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmMSCustpricinggroups" class="form-horizontal" action="<?=site_url()?>pr/cust_pricing_group/add" method="POST" enctype="multipart/form-data">			
				<div class="box-body">
					<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">			
					<input type="hidden" id="frm-mode" value="<?=$mode?>">

					<div class='form-group'>
                    <label for="fin_cust_pricing_group_id" class="col-md-3 control-label"><?=lang("Cust Pricing Group ID")?> #</label>
						<div class="col-md-9">
							<input type="text" class="form-control" id="fin_cust_pricing_group_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_cust_pricing_group_id" value="<?=$fin_cust_pricing_group_id?>" readonly>
							<div id="fin_cust_pricing_group_id_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
                    <label for="fst_cust_pricing_group_name" class="col-md-3 control-label"><?=lang("Cust Pricing Group Name")?> *</label>
						<div class="col-md-9">
							<input type="text" class="form-control" id="fst_cust_pricing_group_name" placeholder="<?=lang("Cust Pricing Group Name")?>" name="fst_cust_pricing_group_name">
							<div id="fst_cust_pricing_group_name_err" class="text-danger"></div>
						</div>
					</div>

                    <div class="form-group">
					<label for="fdc_percent_of_price_list" class="col-md-3 control-label"><?= lang("Percent Of Price List")?> (%)</label>
						<div class="col-md-2">
							<input type="text" class="form-control text-right" id="fdc_percent_of_price_list" name="fdc_percent_of_price_list">
						</div>
						
					<label for="fdc_difference_in_amount" class="col-md-2 control-label"><?= lang("Amount")?></label>
						<div class="col-md-2">
							<input type="text" class="form-control text-right money" id="fdc_difference_in_amount" name="fdc_difference_in_amount">
							<div id="fdc_difference_in_amount_err" class="text-danger"></div>
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

<script type="text/javascript">
	$(function(){
		<?php if($mode == "EDIT"){?>
			init_form($("#fin_cust_pricing_group_id").val());
		<?php } ?>

		$("#btnSubmitAjax").click(function(event){
			event.preventDefault();
			data = $("#frmMSCustpricinggroups").serializeArray();

			mode = $("#frm-mode").val();
			if (mode == "ADD"){
				url =  "<?= site_url() ?>pr/cust_pricing_group/ajx_add_save";
			}else{
				url =  "<?= site_url() ?>pr/cust_pricing_group/ajx_edit_save";
			}

			App.blockUIOnAjaxRequest("Please wait while saving data.....");
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
										$("#btnNew").trigger("click");
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
						$("#fin_cust_pricing_group_id").val(data.insert_id);

						//Clear all previous error
						$(".text-danger").html("");

						// Change to Edit mode
						$("#frm-mode").val("EDIT");  //ADD|EDIT

						$('#fst_cust_pricing_group_name').prop('readonly', true);
					}
				},
				error: function (e) {
					$("#result").text(e.responseText);
					console.log("ERROR : ", e);
					$("#btnSubmit").prop("disabled", false);
				}
			});
		});

		// OnChange
		$("#fdc_percent_of_price_list").change(function(){
			//alert ("fdc_percent_of_price_list");
			$("#fdc_difference_in_amount").val(0);
			$("#fdc_difference_in_amount").prop('readonly', true);
		});

		$("#fdc_difference_in_amount").change(function(){
			//alert ("fdc_percent_of_price_list");
			$("#fdc_percent_of_price_list").val(0);
			$("#fdc_percent_of_price_list").prop('readonly', true);
		});
		
		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>pr/cust_pricing_group/add")
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
				url:"<?= site_url() ?>pr/cust_pricing_group/delete/" + $("#fin_cust_pricing_group_id").val(),
			}).done(function(resp){
				//consoleLog(resp);
				$.unblockUI();
				if (resp.message != "")	{
					$.alert({
						title: 'Message',
						content: resp.message,
						buttons : {
							OK : function() {
								if (resp.status == "SUCCESS") {
									window.location.href = "<?= site_url() ?>pr/cust_pricing_group/lizt";
									return;
								}
							},
						}
					});
				}

				if(resp.status == "SUCCESS") {
					data = resp.data;
					$("#fst_curr_code").val(data.insert_id);

					//Clear all previous error
					$(".text-danger").html("");
					// Change to Edit mode
					$("#frm-mode").val("EDIT");  //ADD|EDIT
					$('#fst_curr_name').prop('readonly', true);
				}
			});
		});

		$("#btnList").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>pr/cust_pricing_group");
		});
	});

	function init_form(fin_cust_pricing_group_id){
		//alert("Init Form");
		var url = "<?=site_url()?>pr/cust_pricing_group/fetch_data/" + fin_cust_pricing_group_id;
		$.ajax({
			type: "GET",
			url: url,
			success: function (resp) {	
				console.log(resp.ms_custpricinggroups);

				$.each(resp.ms_custpricinggroups, function(name, val){
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