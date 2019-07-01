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
			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmCurrency" class="form-horizontal" action="<?=site_url()?>master/currency/add" method="POST" enctype="multipart/form-data">			
				<div class="box-body">
					<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">			
					<input type="hidden" id="frm-mode" value="<?=$mode?>">

					<div class="form-group">
                        <label for="CurrCode" class="col-md-2 control-label"><?=lang("Currencies Code")?> #</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="CurrCode" placeholder="<?=lang("Currencies Code")?>" name="CurrCode" value="<?=$CurrCode?>">
                                <div id="CurrCode_err" class="text-danger"></div>
                            </div>
					</div>

					<div class="form-group">
					<label for="CurrName" class="col-md-2 control-label"><?=lang("Currencies Name")?> *</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="CurrName" placeholder="<?=lang("Currencies Name")?>" name="CurrName">
                            <div id="CurrName_err" class="text-danger"></div>
						</div>
					</div>

                    <div class="form-group">
						<div class="col-md-12" style='text-align:right'>
							<button id="btn-add-detail" class="btn btn-default btn-sm">
								<i class="fa fa-plus" aria-hidden="true"></i>
								<?=lang("Add Detail")?>
							</button>
						</div>
					</div>

					<table id="tblCurrDetails" class="table table-bordered table-hover table-striped"></table>

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

<!-- modal atau popup "ADD" -->
<div id="CurrDetailModal" class="modal fade" role="dialog" >
	<div class="modal-dialog" style="display:table;width:35%;min-width:350px;max-width:100%">
		<!-- modal content -->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Add Currencies Detail")?></h4>
			</div>

			<div class="modal-body">
				<form  class="form-horizontal">
				
					<div class="form-group hide">
						<label for="CurrCode" class="col-md-4 control-label"><?=lang("Currencies Code")?></label>
						<div class="col-md-8">
							<input type="text" class="form-control text-right" id="CurrCode" name="CurrCode">
							<div id="CurrCode_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					    <label for="Date" class="col-md-4 control-label"><?=lang("Date")?></label>
						<div class="col-md-8">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right datepicker" id="Date" name="Date"/>								
                            </div>
                            <div id="Date_err" class="text-danger"></div>
                            <!-- /.input group -->
                        </div>
					</div>

					<div class="form-group">
						<label for="ExchangeRate2IDR" class="col-md-4 control-label"><?=lang("Exc Rate To IDR")?></label>
						<div class="col-md-8">
							<input type="text" class="form-control text-right money" id="ExchangeRate2IDR" placeholder="<?= lang("Exchange Rate To IDR") ?>" value="0" name="ExchangeRate2IDR">
							<div id="ExchangeRate2IDR_err" class="text-danger"></div>
						</div>
					</div>
				</form>
			</div>

			<div class="modal-footer">
				<button id="btn-add-currDetails" type="button" class="btn btn-primary" ><?=lang("Add")?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?=lang("Close")?></button>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	var action = '<a class="btn-edit" href="#" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp;<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
	$(function(){
		<?php if($mode == "EDIT"){?>
			init_form($("#CurrCode").val());
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

			//var formData = new FormData($('form')[0])
			$.ajax({
				type: 'POST',
				enctype: 'multipart/form-data',
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
										window.location.href = "<?= site_url() ?>master/currency";
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
						$("#CurrCode").val(data.insert_id);
						//Clear all previous error
						$(".text-danger").html("");
						// Change to Edit mode
						$("#frm-mode").val("EDIT");  //ADD|EDIT
						$('#CurrName').prop('readonly', true);
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

		$("#Date").datepicker('update', dateFormat("<?= date("Y-m-d")?>"));

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

			ExchangeRateToIDR = money_parse($("#ExchangeRate2IDR").val()); // INI

			data = {
				//recid:$("#recid").val(),
				CurrCode:$("#CurrCode").val(),
				Date:$("#Date").val(),
				ExchangeRate2IDR: $("#ExchangeRate2IDR").val(), // INI
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
				{"title" : "<?= lang("CurrCode")?>","width": "20%",sortable:false,data:"CurrCode",visible:true},
				{"title" : "<?= lang("Date")?>","width": "20%",sortable:false,data:"Date",className: 'dt-right'},
				{"title" : "<?= lang("Exchange Rate To IDR") ?>","width": "20%",
					data:"ExchangeRate2IDR",
					render: $.fn.dataTable.render.number(DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT), // MASALAH DISINI, TABEL TIDAK MUNCUL JIKA INI DIAKTIFKAN
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
				$("#CurrCode").val(row.CurrCode);
				$("#Date").val(row.Date);
				$("#ExchangeRate2IDR").val(money_format(row.ExchangeRate2IDR)); // INI
			});
		});
	});

	function init_form(CurrCode){
		//alert("Init Form");
		var url = "<?=site_url()?>master/currency/fetch_data/" + CurrCode;
		$.ajax({
			type: "GET",
			url: url,
			success: function (resp) {	
				console.log(resp.msCurrency);

				$.each(resp.msCurrency, function(name, val){
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

				$("#Date").datepicker('update', dateFormat(resp.msCurrDetails.Date));
		
				CurrenciesRateDetails = resp.msCurrDetails;
				$.each(CurrenciesRateDetails, function(idx, detail){
					data = {
						//recid:detail.recid,
						CurrCode:detail.CurrCode,
						Date:detail.Date,
						ExchangeRate2IDR:detail.ExchangeRate2IDR,
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
<script src="<?=base_url()?>bower_components/datatables.net/dataTables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>