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

					<div class="form-group hidden">
                        <label for="fin_salesorder_id" class="col-md-2 control-label"><?=lang("Sales Order ID")?> #</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="fin_salesorder_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_salesorder_id" value="<?=$fin_salesorder_id?>" readonly>
                                <div id="fin_salesorder_id_err" class="text-danger"></div>
                            </div>
					</div>

					<div class="form-group">
                        <label for="fst_salesorder_no" class="col-md-2 control-label"><?=lang("Sales Order No")?> #</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="fst_salesorder_no" placeholder="<?=lang("Sales Order No")?>" name="fst_salesorder_no" value="<?=$fst_salesorder_no?>" readonly>
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

						<label for="select-sales" class="col-md-2 control-label"><?=lang("Sales ID")?> </label>
                            <div class="col-md-4">
								<select id="select-sales" class="form-control" name="fin_sales_id">
									<option value="0">-- <?=lang("select")?> --</option>
								</select>
                                <div id="fin_sales_id_err" class="text-danger"></div>
                            </div>
					</div>

					<div class="form-group">
                        <label for="select-warehouse" class="col-md-2 control-label"><?=lang("Warehouse ID")?> </label>
                            <div class="col-md-4">
								<select id="select-warehouse" class="form-control" name="fin_warehouse_id">
									<option value="0">-- <?=lang("select")?> --</option>
								</select>
                                <div id="fin_warehouse_id_err" class="text-danger"></div>
                            </div>

						<label for="select-spv" class="col-md-2 control-label"><?=lang("Sales Spv ID")?> </label>
                            <div class="col-md-4">
								<select id="select-spv" class="form-control" name="fin_sales_spv_id">
									<option value="0">-- <?=lang("select")?> --</option>
								</select>
                                <div id="fin_sales_spv_id_err" class="text-danger"></div>
                            </div>
					</div>

					<div class="form-group">
						<label for="select-relations" class="col-md-2 control-label"><?=lang("Customer")?> </label>
                            <div class="col-md-4">
								<select id="select-relations" class="form-control" name="fin_relation_id">
									<option value="0">-- <?=lang("select")?> --</option>
								</select>
                                <div id="fin_relation_id_err" class="text-danger"></div>
                            </div>

                        <label for="select-mgr" class="col-md-2 control-label"><?=lang("Sales Mgr ID")?></label>
                            <div class="col-md-4">
								<select id="select-mgr" class="form-control" name="fin_sales_mgr_id">
									<option value="0">-- <?=lang("select")?> --</option>
								</select>
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
                                <label><input id="fbl_is_hold" type="checkbox" name="fbl_is_hold" value="1"><?= lang("Hold") ?></label><br>
                            </div>
                    </div>

					<div class="form-group">
                            <label for="fbl_is_vat_include" class="col-sm-2 control-label"><?= lang("Include PPN") ?> </label>
                            <div class="checkbox col-sm-2">
								<?php $checked = ($mode == "ADD") ? "checked" : "" ?>
                                <label><input <?= $checked ?> id="fbl_is_vat_include" type="checkbox" name="fbl_is_vat_include" value="1"><?= lang("Include PPN") ?></label><br>
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

					<table id="tblSODetail" class="table table-bordered table-hover table-striped"></table>

					<div class="form-group">
                        <label for="fdc_vat_percent" class="col-md-2 control-label"><?= lang("PPN")?> (%)</label>
                            <div class="col-md-4" style='text-align:right'>
                                <input type="text" class="form-control text-right" id="fdc_vat_percent" name="fdc_vat_percent">
                            </div>
                            
                        <label for="fdc_vat_amount" class="col-md-2 control-label"><?= lang("PPN Amount")?></label>
                            <div class="col-md-4">
                                <input type="text" class="form-control text-right money" id="fdc_vat_amount" name="fdc_vat_amount">
                                <div id="fdc_vat_amount_err" class="text-danger"></div>
                            </div>
					</div>

                    <div class="form-group">
                        <label for="fdc_disc_percent" class="col-md-2 control-label"><?= lang("Disc")?> (%)</label>
                            <div class="col-md-4" style='text-align:right'>
                                <input type="text" class="form-control text-right" id="fdc_disc_percent" name="fdc_disc_percent">
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

<!-- modal atau popup "ADD" -->
<div id="myModal" class="modal fade" role="dialog" >
	<div class="modal-dialog" style="display:table">
		<!-- modal content -->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Add Sales Order Detail")?></h4>
			</div>

			<div class="modal-body">
				<form  class="form-horizontal">
				<input type='hidden' id='fin-detail-id'/>
					<div class="form-group">
						<label for="select-items" class="col-md-2 control-label"><?=lang("Items")?></label>
						<div class="col-md-10">
							<select id="select-items" class="form-control"></select>
							<div id="fin_item_id_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="fdc_qty" class="col-md-2 control-label"><?=lang("Qty")?></label>
						<div class="col-md-10">
							<input type="text" class="form-control numeric" id="so-qty" value="1">
							<div id="fdc_qty_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="fdc_price" class="col-md-2 control-label"><?=lang("Price")?></label>
						<div class="col-md-10">
							<input type="text" class="form-control text-right money" id="so-price" value="0">
							<div id="fdc_price_err" class="text-danger"></div>
						</div>
					</div>

				</form>
			</div>

			<div class="modal-footer">
				<button id="btn-add-so-detail" type="button" class="btn btn-primary" ><?=lang("Add")?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?=lang("Close")?></button>
			</div>
		</div>
	</div>
</div>
			
			
<script type="text/javascript">
	var action = '<a class="btn-edit" href="#" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp;<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
	$(function(){
		<?php if($mode == "EDIT"){?>
			init_form($("#fin_salesorder_id").val());
		<?php } ?>

		$("#fdt_salesorder_date").datepicker('update', dateFormat("<?= date("Y-m-d")?>"));

		var edited_so_detail = null;
		var mode_so_detail = "ADD";

		$("#btnSubmitAjax").click(function(event){
			event.preventDefault();
			data = $("#frmSalesOrder").serializeArray();
			//console.log(data);
			detail = new Array();
			t = $('#tblSODetail').DataTable();
			datas = t.data();
			$.each(datas,function(i,v){
				detail.push(v);
			});
			data.push({
				name:"detail",
				value: JSON.stringify(detail)
			});

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
						$("#tabs-so-detail").show();
						console.log(data.data_image);
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

		$("#select-relations").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>tr/sales_order/get_msrelations',
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

		$("#select-warehouse").select2({
			width: '100%',
			tokenSeparators: [",", " "],
			ajax: {
				url: '<?=site_url()?>tr/sales_order/get_mswarehouse',
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

		$("#select-sales").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>tr/sales_order/get_users',
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

		$("#select-spv").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>tr/sales_order/get_users',
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

		$("#select-mgr").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>tr/sales_order/get_users',
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

		$("#select-items").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>tr/sales_order/get_data_item',
				dataType: 'json',
				delay: 250,
				processResults: function (data) {
					data2 = [];
					$.each(data,function(index,value){
						data2.push({
							"id" : value.ItemId,
							"text" : value.ItemName
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

		$("#btn-add-detail").click(function(event){
			event.preventDefault();
			mode_so_detail = "ADD"; // 28/04/2019
			$("#myModal").modal({
				backdrop:"static",
			});

			$('#select-items').val(null).trigger('change');
			$("#fin-detail-id").val(0);
			$("#so-qty").val(1);
			$("#so-price").val(0);
		})

		$("#btn-add-so-detail").click(function(event){
			event.preventDefault();
			selected_items = $("#select-items").select2('data')[0];

			var qty = numeral($("#so-qty").val());
			var price = numeral($("#so-price").val());
			var total = qty.value() * price.value();

			t.row.add({
				fin_salesorder_id:$("#fin-detail-id").val(),
				ItemName:selected_item.text,
				fdc_qty: $("#so-qty").val(),
				fdc_price: price.value(),
				total: total,
				action: action
			}).draw(false);

			t = $('#tblSODetail').DataTable();
			if(mode_so_detail =="EDIT"){
				edited_so_detail.data(data).draw(false);
			}else{
				t.row.add(data).draw(false);	
			}

			calculateTotal();
		});

		// OnChange
		$("#fdc_vat_percent").change(function(){
			//alert ("fdc_vat_percent");
			$("#fdc_vat_amount").val(0);
			$("#fdc_vat_amount").prop('readonly', true);
		});

		$("#fdc_vat_amount").change(function(){
			//alert ("fdc_vat_percent");
			$("#fdc_vat_percent").val(0);
			$("#fdc_vat_percent").prop('readonly', true);
		});

		$("#fdc_vat_percent").inputmask({
			alias : 'numeric',
			allowMinus : false,
			digits : 2,
			max : 100
		});

		// OnChange
		$("#fdc_disc_percent").change(function(){
			//alert ("fdc_disc_percent");
			$("#fdc_disc_amount").val(0);
			$("#fdc_disc_amount").prop('readonly', true);
		});

		$("#fdc_disc_amount").change(function(){
			//alert ("fdc_disc_percent");
			$("#fdc_disc_percent").val(0);
			$("#fdc_disc_percent").prop('readonly', true);
		});

		$("#fdc_disc_percent").inputmask({
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

		$("#fdc_disc_amount").inputmask({
			alias: 'numeric', 
			allowMinus: false,  
			digits: 2, 
			max: 100
		});

		$(".numeric").inputmask({
			alias: 'numeric', 
			allowMinus: false,  
			digits: 2
		});

		$('#tblSODetail').on('preXhr.dt', function ( e, settings, data ) {
		 	//add aditional data post on ajax call
		 	data.sessionId = "TEST SESSION ID";
		}).DataTable({
			columns:[
				//{"title" : "ID","width": "0%",sortable:false,data:"fin_id",visible:false},
				{"title" : "rec_id","width": "0%",sortable:false,data:"rec_id",visible:false},
				{"title" : "fin_salesorder_id","width": "0%",sortable:false,data:"fin_salesorder_id",visible:false},
				{"title" : "Items","width": "20%",sortable:false,data:"ItemName"},
				{"title" : "Qty","width": "10%",data:"fdc_qty"},
				{"title" : "price","width": "10%",
					data:"fdc_price",
					render: $.fn.dataTable.render.number( ',', '.', 2 ),
					className:'dt-right'
				},
				{"title" : "Total","width": "10%",
					data:"total",
					render: $.fn.dataTable.render.number( ',', '.', 2 ),
					className:'dt-right'
				},
				{"title" : "action","width": "10%",data:"action",sortable:false,className:'dt-body-center text-center'},
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
				t = $('#tblSODetail').DataTable();
				var trRow = $(this).parents('tr');

				t.row(trRow).remove().draw();
				calculateTotal();
			});

			$(".btn-edit").click(function(event){
				event.preventDefault();
				$("#myModal").modal({
					backdrop:"static",
				});
				t = $('#tblSODetail').DataTable();
				var trRow = $(this).parents('tr');

				mode_so_detail = "EDIT";
				edited_so_detail = t.row(trRow);
				row = edited_so_detail.data();	

				//$("#fin_id").val(row.fin_id); // 28/04/2019
				$("#select-items").val(row.ItemId).change();
				$("#fin-detail-id").val(row.rec_id);
				$("#so-qty").val(row.fdc_qty);
				$("#so-price").val(row.fdc_price);
			});
		});

		$("#fdc_disc_percent").change(function(event){
			event.preventDefault();
			calculateTotal();
		})
	});


	function init_form(fin_salesorder_id){
		alert("Init Form");
		var url = "<?=site_url()?>tr/sales_order/fetch_data/" + fin_salesorder_id;
		$.ajax({
			type: "GET",
			url: url,
			success: function (resp) {	
				console.log(resp.sales_order);

				$.each(resp.sales_order, function(name, val){
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

				// OnChange
				var fdt_salesorder_date = $salesDate;
				$("#fst_salesorder_no").change(function(){
					//alert ("fst_salesorder_no");
					$("#salesDate").val(0);
					$("#salesDate").prop('readonly', true);
				});

				$("#fdt_salesorder_date").datepicker('update', dateFormat(resp.sales_order.fdt_salesorder_date));

				// menampilkan data di select2, menu edit/update
				var newOption = new Option(resp.sales_order.RelationName, resp.sales_order.RelationId, true, true);
				// Append it to the select
    			$('#select-relations').append(newOption).trigger('change');

				var newOption = new Option(resp.sales_order.fst_warehouse_name, resp.sales_order.fin_warehouse_id, true, true);
    			$('#select-warehouse').append(newOption).trigger('change');

				var newOption = new Option(resp.sales_order.fst_username, resp.sales_order.fin_user_id, true, true);
				$('#select-sales').append(newOption).trigger('change');

				var newOption = new Option(resp.sales_order.fst_username, resp.sales_order.fin_user_id, true, true);
				$('#select-spv').append(newOption).trigger('change');

				var newOption = new Option(resp.sales_order.fst_username, resp.sales_order.fin_user_id, true, true);
				$('#select-mgr').append(newOption).trigger('change');
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