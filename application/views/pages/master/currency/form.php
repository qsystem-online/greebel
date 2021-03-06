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
	<h1><?=lang("Master Currencies")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Master Currencies") ?></a></li>
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
            <form id="frmCurrency" class="form-horizontal" action="<?=site_url()?>master/currency/add" method="POST" enctype="multipart/form-data">			
				<div class="box-body">
					<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">			
					<input type="hidden" id="frm-mode" value="<?=$mode?>">

					<div class="form-group">
                        <label for="fst_curr_code" class="col-md-3 control-label"><?=lang("Currencies Code")?> #</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="fst_curr_code" placeholder="<?=lang("Currencies Code")?>" name="fst_curr_code" >
                                <div id="fst_curr_code_err" class="text-danger"></div>
                            </div>
					</div>

					<div class="form-group">
					<label for="fst_curr_name" class="col-md-3 control-label"><?=lang("Currencies Name")?> *</label>
						<div class="col-md-9">
							<input type="text" class="form-control" id="fst_curr_name" placeholder="<?=lang("Currencies Name")?>" name="fst_curr_name">
                            <div id="fst_curr_name_err" class="text-danger"></div>
						</div>
					</div>

                    <div class="form-group">
						<div class="col-md-12" style='text-align:right'>
							<button id="btn-add-detail" class="btn btn-primary btn-sm">
								<i class="fa fa-cart-plus" aria-hidden="true"></i>
								<?=lang("Add Detail")?>
							</button>
						</div>
					</div>

					<table id="tblCurrDetails" class="table table-bordered table-hover table-striped"></table>

                </div>
				<!-- end box body -->

                <div class="box-footer text-right">
                    
                </div>
                <!-- end box-footer -->
            </form>
        </div>
    </div>
</section>

<!-- modal atau popup "ADD" -->
<div id="CurrDetailModal" class="modal fade" role="dialog" >
	<div class="modal-dialog" style="display:table;width:35%;min-width:350px;max-width:100%">
		<!-- modal content -->
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
			<div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Add Currencies Detail")?></h4>
			</div>

			<div class="modal-body">
				<div class="row">
                    <div class="col-md-12" >
                        <div style="border:1px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">
								<form  class="form-horizontal">
								
									<div class="form-group hide">
										<label for="fst_curr_code" class="col-md-4 control-label"><?=lang("Currencies Code")?></label>
										<div class="col-md-8">
											<input type="text" class="form-control text-right" id="fst_curr_code" name="fst_curr_code">
											<div id="fst_curr_code_err" class="text-danger"></div>
										</div>
									</div>

									<div class="form-group">
										<label for="fdt_date" class="col-md-4 control-label text-right"><?=lang("Date")?></label>
										<div class="col-md-8">
											<div class="input-group date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input type="text" class="form-control text-right datepicker" id="fdt_date" name="fdt_date"/>								
											</div>
											<div id="fdt_date_err" class="text-danger"></div>
											<!-- /.input group -->
										</div>
									</div>

									<div class="form-group">
										<label for="fdc_exchange_rate_to_idr" class="col-md-4 control-label"><?=lang("Exc Rate To IDR")?></label>
										<div class="col-md-8">
											<input type="text" class="form-control text-right money" id="fdc_exchange_rate_to_idr" placeholder="<?= lang("Exchange Rate To IDR") ?>" value="0" name="fdc_exchange_rate_to_idr">
											<div id="fdc_exchange_rate_to_idr_err" class="text-danger"></div>
										</div>
									</div>
								</form>

								<div class="modal-footer" style="width:100%;padding:10px" class="text-center">
									<button id="btn-add-currDetails" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Add")?></button>
									<button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	var action = '<a class="btn-edit" href="#" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp;<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
	$(function(){
		<?php if($mode == "EDIT"){?>
			init_form($("#fst_curr_code").val());
		<?php } ?>

		var edited_curr_detail = null;
		var mode_curr_detail = "ADD";

		$("#btnSubmitAjax").click(function(event){
			event.preventDefault();
			data = $("#frmCurrency").serializeArray();
			//console.log(data);
			detail = new Array();

			t = $('#tblCurrDetails').DataTable();
			datas = t.data();
			$.each(datas,function(i,v){
				detail.push(v);
			});
			data.push({
				name:"detail",
				value: JSON.stringify(detail)
			});

			//console.log(data);
			//return;*/

			mode = $("#frm-mode").val();
			if (mode == "ADD"){
				url =  "<?= site_url() ?>master/currency/ajx_add_save";
			}else{
				url =  "<?= site_url() ?>master/currency/ajx_edit_save";
			}

			App.blockUIOnAjaxRequest("Please wait while saving data.....");
			$.ajax({
				type: 'POST',
				//enctype: 'multipart/form-data',
				url: url,
				data: data,
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
						$("#fst_curr_code").val(data.insert_id);
						//Clear all previous error
						$(".text-danger").html("");
						// Change to Edit mode
						$("#frm-mode").val("EDIT");  //ADD|EDIT
						$('#fst_curr_name').prop('readonly', true);
					}
				},
				error: function (e) {
					$("#result").text(e.responseText);
					console.log("ERROR : ", e);
					$("#btnSubmit").prop("disabled", false);
				}
			});
		});

		var arrDetail;

		$("#fdt_date").datepicker('update', dateFormat("<?= date("Y-m-d")?>"));

		$("#btn-add-detail").click(function(event){
			event.preventDefault();
			mode_curr_detail = "ADD";
			$("#CurrDetailModal").modal({
				backdrop:"static",
			});
		})

		$("#btn-add-currDetails").click(function(event){
			event.preventDefault();
			$("#CurrDetailModal").modal('show');

			ExchangeRateToIDR = money_parse($("#fdc_exchange_rate_to_idr").val());

			data = {
				//recid:$("#recid").val(),
				fst_curr_code:$("#fst_curr_code").val(),
				fdt_date:$("#fdt_date").val(),
				fdc_exchange_rate_to_idr: $("#fdc_exchange_rate_to_idr").val(),
				action: action
			}

			t = $('#tblCurrDetails').DataTable();
			if(mode_curr_detail == "EDIT"){
				edited_curr_detail.data(data).draw(false);
			}else{
				t.row.add(data).draw(false);	
			}
		});

		$('#tblCurrDetails').on('preXhr.dt', function ( e, settings, data ) {
		 	//add aditional data post on ajax call
		 	data.sessionId = "TEST SESSION ID";
		}).DataTable({
			columns:[
				//{"title" : "recid","width": "0%",sortable:false,data:"recid",visible:false},
				{"title" : "<?= lang("Currencies Code")?>","width": "20%",sortable:false,data:"fst_curr_code",visible:true},
				{"title" : "<?= lang("Date")?>","width": "20%",sortable:false,data:"fdt_date",className: 'dt-right'},
				{"title" : "<?= lang("Exchange Rate To IDR") ?>","width": "20%",
					data:"fdc_exchange_rate_to_idr",
					render: $.fn.dataTable.render.number(DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					className:'text-right'
				},
				{"title" : "Action","width": "15%",data:"action",sortable:false,className:'dt-body-center text-center'},
			],
			processing: true,
			serverSide: false,
			searching: false,
			lengthChange: false,
			paging: false,
			info:false,
		}).on('draw',function(){
			$('.btn-delete').confirmation({
				//rootSelector: '[data-toggle=confirmation]',
				rootSelector: '.btn-delete',
				// other options
			});	

			$(".btn-delete").click(function(event){
				t = $('#tblCurrDetails').DataTable();
				var trRow = $(this).parents('tr');
				t.row(trRow).remove().draw();
			});

			$(".btn-edit").click(function(event){
				event.preventDefault();
				$("#CurrDetailModal").modal({
					backdrop:"static",
				});

				t = $('#tblCurrDetails').DataTable();
				var trRow = $(this).parents('tr');

				mode_curr_detail = "EDIT";
				edited_curr_detail = t.row(trRow);
				row = edited_curr_detail.data();	

				//$("#recid").val(row.recid);
				$("#fst_curr_code").val(row.fst_curr_code);
				$("#fdt_date").val(row.fdt_date);
				$("#fdc_exchange_rate_to_idr").val(money_format(row.fdc_exchange_rate_to_idr));
			});
		});
		
		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>master/currency/add")
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
				url:"<?= site_url() ?>master/currency/delete/" + $("#fst_curr_code").val(),
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
									window.location.href = "<?= site_url() ?>master/currency/lizt";
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
			window.location.replace("<?=site_url()?>master/currency");
		});
	});

	function init_form(fst_curr_code){
		//alert("Init Form");
		var url = "<?=site_url()?>master/currency/fetch_data/" + fst_curr_code;
		$.ajax({
			type: "GET",
			url: url,
			success: function (resp) {	
				console.log(resp.ms_Currency);

				$.each(resp.ms_Currency, function(name, val){
					var $el = $('[name="'+name+'"]'),
						type = $el.attr('type');
					switch(type){
						case 'checkbox':
							$el.filter('[value="' + val + '"]').attr('checked', 'checked');
                            break;
						case 'radio':
							$el.filter('[value="' + val + '"]').attr('checked', 'checked');
							break;
						default:
							$el.val(val);
							console.log(val);
					}
				});

				$("#fdt_date").datepicker('update', dateFormat(resp.ms_CurrDetails.fdt_date));
		
				CurrenciesRateDetails = resp.ms_CurrDetails;
				$.each(CurrenciesRateDetails, function(idx, detail){
					data = {
						//recid:detail.recid,
						fst_curr_code:detail.fst_curr_code,
						fdt_date:detail.fdt_date,
						fdc_exchange_rate_to_idr:detail.fdc_exchange_rate_to_idr,
						action: action
					}
					t = $('#tblCurrDetails').DataTable();			
					t.row.add(data).draw(false);
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
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>