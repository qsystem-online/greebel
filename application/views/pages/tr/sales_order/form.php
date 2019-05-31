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
	<h1><?=lang("Sales Order")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Sales Order") ?></a></li>
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
            <form id="frmSalesOrder" class="form-horizontal" action="<?=site_url()?>tr/sales_order/add" method="POST" enctype="multipart/form-data">			
				<div class="box-body">
					<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">			
					<input type="hidden" id="frm-mode" value="<?=$mode?>">

					<div class="form-group">
                        <label for="fin_salesorder_id" class="col-md-2 control-label"><?=lang("Sales Order ID")?> #</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="fin_salesorder_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_salesorder_id" value="<?=$fin_salesorder_id?>" readonly>
                                <div id="fin_salesorder_id_err" class="text-danger"></div>
                            </div>
					</div>

					<div class="form-group">
                        <label for="fst_salesorder_no" class="col-md-2 control-label"><?=lang("Sales Order No")?> #</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="fst_salesorder_no" placeholder="<?=lang("Sales Order No")?>" name="fst_salesorder_no">
                                <div id="fst_salesorder_no_err" class="text-danger"></div>
                            </div>
                    </div>

                    <div class="form-group">
                        <label for="fdt_salesorder_date" class="col-md-2 control-label"><?=lang("Sales Order Date")?> *</label>
                            <div class="col-md-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right datepicker" id="fdt_salesorder_date" name="fdt_salesorder_date"/>								
                                </div>
                                <div id="fdt_salesorder_date_err" class="text-danger"></div>
                                <!-- /.input group -->
                            </div>

                        <label for="fin_relation_id" class="col-md-2 control-label"><?=lang("Relation ID")?> </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="fin_relation_id" placeholder="<?=lang("Relation ID")?>" name="fin_relation_id">
                                <div id="fin_relation_id_err" class="text-danger"></div>
                            </div>
					</div>

					<div class="form-group">
                        <label for="fin_warehouse_id" class="col-md-2 control-label"><?=lang("Warehouse ID")?> </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="fin_warehouse_id" placeholder="<?=lang("Warehouse")?>" name="fin_warehouse_id">
                                <div id="fin_warehouse_id_err" class="text-danger"></div>
                            </div>

                        <label for="fin_sales_id" class="col-md-2 control-label"><?=lang("Sales ID")?> </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="fin_sales_id" placeholder="<?=lang("Sales ID")?>" name="fin_sales_id">
                                <div id="fin_sales_id_err" class="text-danger"></div>
                            </div>
					</div>

					<div class="form-group">
                        <label for="fin_sales_spv_id" class="col-md-2 control-label"><?=lang("Sales Spv ID")?> </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="fin_sales_spv_id" placeholder="<?=lang("Sales Spv ID")?>" name="fin_sales_spv_id">
                                <div id="fin_sales_spv_id_err" class="text-danger"></div>
                            </div>

                        <label for="fin_sales_mgr_id" class="col-md-2 control-label"><?=lang("Sales Mgr ID")?></label>
                            <div class="col-md-4">
                                    <input type="text" class="form-control" id="fin_sales_mgr_id" placeholder="<?=lang("Sales Mgr ID")?>" name="fin_sales_mgr_id">
                                    <div id="fin_sales_mgr_id_err" class="text-danger"></div>
                            </div>
					</div>

                    <div class="form-group">
                            <label for="fst_memo" class="col-sm-2 control-label"><?= lang("Memo") ?> </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="fst_memo" placeholder="<?= lang("Memo") ?>" name="fst_memo">
                                <div id="fst_memo_err" class="text-danger"></div>
                            </div>
                    </div>

                    <div class="form-group">
                            <label for="fbl_is_hold" class="col-sm-2 control-label"><?= lang("Hold") ?> </label>
                            <div class="checkbox col-sm-2">
                                <label><input id="fbl_is_hold" type="checkbox" name="fbl_is_hold" value="0"><?= lang("Hold") ?></label><br>
                            </div>
                    </div>

                    <div class="form-group">
                            <label for="fbl_is_vat_include" class="col-sm-2 control-label"><?= lang("Include") ?> </label>
                            <div class="checkbox col-sm-2">
                                <label><input id="fbl_is_vat_include" type="checkbox" name="fbl_is_vat_include" value="1"><?= lang("Include") ?></label><br>
                            </div>
                    </div>

					<div class="form-group">
                        <label for="fdc_vat_percent" class="col-md-2 control-label"><?= lang("Vat Percent")?> (%)</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control text-right" id="fdc_vat_percent" name="fdc_vat_percent">
                                <div id="fdc_vat_percent_err" class="text-danger"></div>
                            </div>
                            
                        <label for="fdc_vat_amount" class="col-md-2 control-label"><?= lang("Vat Amount")?></label>
                            <div class="col-md-4">
                                <input type="text" class="form-control text-right money" id="fdc_vat_amount" name="fdc_vat_amount">
                                <div id="fdc_vat_amount_err" class="text-danger"></div>
                            </div>
					</div>

                    <div class="form-group">
                        <label for="fdc_disc_percent" class="col-md-2 control-label"><?= lang("Disc Percent")?> (%)</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control text-right" id="fdc_disc_percent" name="fdc_disc_percent">
                                <div id="fdc_disc_percent_err" class="text-danger"></div>
                            </div>
                            
                        <label for="fdc_disc_amount" class="col-md-2 control-label"><?= lang("Disc Amount")?> </label>
                            <div class="col-md-4">
                                <input type="text" class="form-control text-right money" id="fdc_disc_amount" name="fdc_disc_amount">
                                <div id="fdc_disc_amount_err" class="text-danger"></div>
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
			init_form($("#fin_salesorder_id").val());
		<?php } ?>

		$("#btnSubmitAjax").click(function(event){
			event.preventDefault();
			data = new FormData($("#frmSalesOrder")[0]);

			mode = $("#frm-mode").val();
			if (mode == "ADD"){
				url =  "<?= site_url() ?>tr/sales_order/ajx_add_save";
			}else{
				url =  "<?= site_url() ?>tr/sales_order/ajx_edit_save";
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
										window.location.href = "<?= site_url() ?>tr/sales_order/lizt";
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
						$("#fin_salesorder_id").val(data.insert_id);

						//Clear all previous error
						$(".text-danger").html("");

						// Change to Edit mode
						$("#frm-mode").val("EDIT");  //ADD|EDIT
						$('#fst_salesorder_no').prop('readonly', true);
					}
				},
				error: function (e) {
					$("#result").text(e.responseText);
					console.log("ERROR : ", e);
					$("#btnSubmit").prop("disabled", false);
				}
			});
		
            $("#fdc_vat_percent").inputmask({
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

        $("#fdc_vat_percent").change(function(event){
			event.preventDefault();
			calculateTotal();
		})
	});

	function init_form(fin_salesorder_id){
		//alert("Init Form");
		var url = "<?=site_url()?>tr/sales_order/fetch_data/" + fin_salesorder_id;
		$.ajax({
			type: "GET",
			url: url,
			success: function (resp) {	
				console.log(resp.sales_order;

				$.each(resp.sales_order, function(name, val){
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

				$("#fdt_salesorder_date").datepicker('update', dateFormat(resp.sales_order.fdt_salesorder_date));
			},

			error: function (e) {
				$("#result").text(e.responseText);
				console.log("ERROR : ", e);
			}
		});
	}
</script>
