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
			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmMSCustpricinggroups" class="form-horizontal" action="<?=site_url()?>pages/pr/mscustpricinggroups/add" method="POST" enctype="multipart/form-data">			
				<div class="box-body">
					<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">			
					<input type="hidden" id="frm-mode" value="<?=$mode?>">

					<div class='form-group'>
                    <label for="CustPricingGroupId" class="col-md-3 control-label"><?=lang("Cust Pricing Group ID")?> #</label>
						<div class="col-md-9">
							<input type="text" class="form-control" id="CustPricingGroupId" placeholder="<?=lang("(Autonumber)")?>" name="CustPricingGroupId" value="<?=$CustPricingGroupId?>" readonly>
							<div id="CustPricingGroupId_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
                    <label for="CustPricingGroupName" class="col-md-3 control-label"><?=lang("Cust Pricing Group Name")?> *</label>
						<div class="col-md-9">
							<input type="text" class="form-control" id="CustPricingGroupName" placeholder="<?=lang("Cust Pricing Group Name")?>" name="CustPricingGroupName">
							<div id="CustPricingGroupName_err" class="text-danger"></div>
						</div>
					</div>

                    <div class="form-group">
					<label for="PercentOfPriceList" class="col-md-3 control-label"><?= lang("Percent Of Price List")?> (%)</label>
						<div class="col-md-1">
							<input type="text" class="form-control text-right" id="PercentOfPriceList" name="PercentOfPriceList">
						</div>
						
					<label for="DifferenceInAmount" class="col-md-2 control-label"><?= lang("Amount")?></label>
						<div class="col-md-2">
							<input type="text" class="form-control text-right money" id="DifferenceInAmount" name="DifferenceInAmount">
							<div id="DifferenceInAmount_err" class="text-danger"></div>
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
			init_form($("#CustPricingGroupId").val());
		<?php } ?>

		$("#btnSubmitAjax").click(function(event){
			event.preventDefault();
			data = new FormData($("#frmMSCustpricinggroups")[0]);

			mode = $("#frm-mode").val();
			if (mode == "ADD"){
				url =  "<?= site_url() ?>pr/mscustpricinggroups/ajx_add_save";
			}else{
				url =  "<?= site_url() ?>pr/mscustpricinggroups/ajx_edit_save";
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
										window.location.href = "<?= site_url() ?>pr/mscustpricinggroups/lizt";
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
						$("#CustPricingGroupId").val(data.insert_id);

						//Clear all previous error
						$(".text-danger").html("");

						// Change to Edit mode
						$("#frm-mode").val("EDIT");  //ADD|EDIT

						$('#CustPricingGroupName').prop('readonly', true);
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
		$("#PercentOfPriceList").change(function(){
			//alert ("PercentOfPriceList");
			$("#DifferenceInAmount").val(0);
			$("#DifferenceInAmount").prop('readonly', true);
		});

		$("#DifferenceInAmount").change(function(){
			//alert ("PercentOfPriceList");
			$("#PercentOfPriceList").val(0);
			$("#PercentOfPriceList").prop('readonly', true);
		});

		$("#PercentOfPriceList").inputmask({
			alias : 'numeric',
			allowMinus : false,
			digits : 2,
			max : 100
		});

		$(".money").inputmask({
			alias : 'numeric',
			autoGroup : true,
			groupSeparator : ",",
			allowMinus : false,
			digits : 2
		})

	});

	function init_form(CustPricingGroupId){
		//alert("Init Form");
		var url = "<?=site_url()?>pr/mscustpricinggroups/fetch_data/" + CustPricingGroupId;
		$.ajax({
			type: "GET",
			url: url,
			success: function (resp) {	
				console.log(resp.mscustpricinggroups);

				$.each(resp.mscustpricinggroups, function(name, val){
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