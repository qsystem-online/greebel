<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?=base_url()?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<style type="text/css">	
	.form-group{
		margin-bottom: 5px;
	}
	.checkbox label, .radio label {
		font-weight:700;
	}
	.select2-results > ul > li > span{
		display:inline-block;
		margin-right:20px;
	}
</style>

<section class="content-header">
	<h1><?=lang("Invoice")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Invoice") ?></a></li>
		<li class="active title"><?=$title?></li>
	</ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
				<div class="box-header with-border">
				<h3 class="box-title title"><?=$title?></h3>
				<?php if ($mode != "VIEW") { ?>
				<div class="btn-group btn-group-sm  pull-right">					
					<a id="btnNew" class="btn btn-primary" href="#" title="<?=lang("Tambah Baru")?>"><i class="fa fa-plus" aria-hidden="true"></i></a>
					<a id="btnSubmitAjax" class="btn btn-primary" href="#" title="<?=lang("Simpan")?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
					<a id="btnPrint" class="btn btn-primary" href="#" title="<?=lang("Cetak")?>"><i class="fa fa-print" aria-hidden="true"></i></a>
					<a id="btnJurnal" class="btn btn-primary" href="#" title="<?=lang("Jurnal")?>" style="display:<?= $mode == "ADD" ? "none" : "inline-block" ?>"><i class="fa fa-align-left" aria-hidden="true"></i></a>
					<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
					<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
				</div>
				<?php } ?>

			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmInvoice" class="form-horizontal" action="<?=site_url()?>tr/delivery_order/add" method="POST" enctype="multipart/form-data">			
				<div class="box-body">
					<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">			
					<input type="hidden" id="frm-mode" value="<?=$mode?>">
					<input type="hidden" class="form-control" id="fin_inv_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_inv_id" value="<?=$fin_inv_id?>" readonly>

					
					<div class="form-group">
						<label for="fst_inv_no" class="col-md-2 control-label"><?=lang("Invoice No.")?> #</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_inv_no" placeholder="<?=lang("Invoice No")?>" name="fst_inv_no" value="<?=$fst_inv_no?>" readonly>
							<div id="fst_inv_no_err" class="text-danger"></div>
						</div>
						
						<label for="fdt_inv_datetime" class="col-md-2 control-label"><?=lang("Invoice Date")?> *</label>
						<div class="col-md-4">
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="term_payment form-control text-right datetimepicker" id="fdt_inv_datetime" name="fdt_inv_datetime"/>
							</div>
							<div id="fdt_inv_datetime_err" class="text-danger"></div>
							<!-- /.input group -->
						</div>						
                    </div>

					<div class="form-group">						
						<label for="fin_salesorder_id" class="col-md-2 control-label"><?=lang("Sales Order")?> </label>
						<div class="col-md-10">
                            <select id="fin_salesorder_id"  name="fin_salesorder_id" class="form-control" style="width:100%"></select>
							<div id="fst_salesorder_no_err" class="text-danger"></div>
						</div>
					</div>


					<div class="form-group">						
						<label for="fst_customer_name" class="col-md-2 control-label"><?=lang("Customer")?> </label>
						<div class="col-md-10">
							<input type="TEXT"  class="form-control unfocus"  id="fst_customer_name"  disabled />
						</div>
					</div>

					<div class="form-group">						
						<label for="fst_curr_code" class="col-md-2 control-label">Mata Uang</label>
						<div class="col-md-4">
							<select id="fst_curr_code" class="form-control" name="fst_curr_code" disabled>
								<?php
									$currList = $this->mscurrencies_model->getCurrencyList();
									foreach($currList as $curr){
										$selected = $curr->fbl_is_default == 1 ? "selected" : "";									
										echo "<option value='$curr->fst_curr_code' data-rate='$curr->fdc_exchange_rate_to_idr' $selected>$curr->fst_curr_code</option>";
									}
								?>
							</select>
							<div id="fst_curr_code_err" class="text-danger"></div>
						</div>
					
						<label for="fdc_exchange_rate_idr" class="col-md-2 control-label">Nilai Tukar IDR</label>
						<div class="col-md-1">
							<input type="text" class="form-control" id="fdc_exchange_rate_idr" name="fdc_exchange_rate_idr" style="width:50px" value="1">
						</div>
						<label class="col-md-2 control-label" style="text-align:left;padding-left:0px">Rupiah </label>
					</div>
					

					

					

                    
					<div class="form-group">						
						<label for="fin_warehouse_id" class="col-md-2 control-label"><?=lang("Gudang")?> </label>
						<div class="col-md-4">
                            <select id="fin_warehouse_id" class="form-control" name="fin_warehouse_id" disabled>
								<?php
									$warehouseList = $this->mswarehouse_model->getNonLogisticWarehouseList();
									foreach($warehouseList as $warehouse){
										echo "<option value='$warehouse->fin_warehouse_id' >$warehouse->fst_warehouse_name</option>";
									}
								?>
                            </select>
							<div id="fin_warehouse_id_err" class="text-danger"></div>
						</div>
						<label for="fin_sales_id" class="col-md-2 control-label"><?=lang("Sales")?> </label>
						<div class="col-md-4">
                            <select id="fin_sales_id" class="form-control" name="fin_sales_id" disabled>
								<?php
									$salesList = $this->users_model->getSalesList();
									foreach($salesList as $sales){
										echo "<option value='$sales->fin_user_id' >$sales->fst_username</option>";
									}
								?>
                            </select>
							<div id="fin_sales_id_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">						
						<label for="fin_terms_payment" class="col-md-2 control-label"><?=lang("Terms")?> </label>
						<div class="col-md-1">	
							<input type="TEXT" id="fin_terms_payment" class="term_payment text-center form-control" name="fin_terms_payment"/>															
						</div>
						<div class="col-md-3">	
							<input type="TEXT" id="fdt_payment_due_date" class="text-right form-control unfocus" name="fdt_payment_due_date" disabled/>
						</div>
						
					</div>
					<div class="form-group">						
						<label for="fin_sj_id" class="col-md-2 control-label"><?=lang("Delivery Order")?> </label>
						<div class="col-md-10">
                            <select id="fst_sj_id_list" class="form-control" name="fst_sj_id_list[]" multiple="multiple">
                            </select>
							<div id="fst_sj_id_list_err" class="text-danger"></div>
						</div>					
					</div>
					<div class="form-group">						
						<label for="fst_reff_no" class="col-md-2 control-label"><?=lang("Reff No")?>. </label>
						<div class="col-md-8">	
							<input type="TEXT" id="fst_reff_no" class="form-control" name="fst_reff_no" />															
						</div>
						<div class="col-md-2 checkbox disabled">
							<label><input id="fbl_is_vat_include" type="checkbox" value="1" name="fbl_is_vat_include" disabled><?=lang("Include PPN")?></label>
						</div>
					</div>
					

					<table id="tblInvItems" class="table table-bordered table-hover table-striped" style="width:100%"></table>
                    <div id="detail_err" class="text-danger"></div>
					<div class="form-group">						
						<label for="fst_reff_no" class="col-md-12">
							<span style="display:inline-block;"><?=lang("Total Uang Muka Tersisa :")?></span> <label id="fdc_downpayment_rest" style="margin-left:30px;margin-right:5px"></label>
							<span style="display:inline-block;">| <?=lang("Total Retur :")?> </span> <label id="fdc_total_return" style="margin-left:30px;margin-right:5px"> 0.00 </label>
						</label>						
					</div>

					<!--
                    <div class="form-group" style="margin-top:10px">
						<div class="col-md-12 pull-right text-right">
							<label for="sub-total" class="">Sub total (DPP) :</label>&nbsp;
							<input type="text" class=" text-right" id="sub-total" value="0" readonly="" size="10">&nbsp;&nbsp;

							<label for="ttl-disc" class="">Disc :</label>&nbsp;
							<input type="text" class=" text-right" id="ttl-disc" value="0" readonly="" size="10">	&nbsp;&nbsp;

							<label for="fdc_vat_percent" class="">PPN(%) :</label>&nbsp;
							<input type="text" class="text-right unfocus" id="fdc_vat_percent" name="fdc_vat_percent" value="10" readonly size="2">&nbsp;
							<input type="text" class="text-right" id="fdc_vat_amount" name="fdc_vat_amount" value="0" readonly="" size="10">&nbsp;&nbsp;

							<label for="total" class="">Total :</label>&nbsp;
							<input type="text" class="text-right" id="total" value="0" readonly="" size="10">&nbsp;&nbsp;
							
							<label for="total" class="">Uang Muka :</label>&nbsp;
							<input type="text" class="money text-right" id="fdc_downpayment_claimed" name="fdc_downpayment_claimed" value="0" style="text-align: right;" size="10">							
							<div id="fdc_downpayment_claimed_err" class="text-danger"></div>
						</div>
					</div>
					-->
					<div class="form-group">								
						<div class="col-sm-6">
							<label for="fst_inv_memo" class=""><?=lang("Memo")?></label>
							<textarea class="form-control" id="fst_inv_memo" placeholder="<?= lang("Memo") ?>" name="fst_inv_memo" rows="5" style="resize:none"></textarea>
							<div id="fst_inv_memo_err" class="text-danger"></div>
						</div>
					
                        <div class="col-sm-6">	
							<div class="form-group">
								<label for="sub-total" class="col-md-8 control-label">Sub total (DPP)</label>
								<div class="col-md-4" style="text-align:right">
									<input type="text" class="form-control text-right" id="sub-total" value="0" readonly="">
								</div>
							</div>
							<div class="form-group">
								<label for="ttl-disc" class="col-md-8 control-label">Disc</label>
								<div class="col-md-4" style="text-align:right">
									<input type="text" class="form-control text-right" id="ttl-disc" value="0" readonly="">
								</div>
							</div>
							
							<div class="form-group">
								<label for="sub-total" class="col-md-6 control-label">%PPn</label>
								<div class="col-md-2" style="text-align:right">
									<input type="text" class="form-control text-right unfocus" id="fdc_vat_percent" name="fdc_vat_percent" value="10" readonly>
								</div>
								<div class="col-md-4" style="text-align:right">
									<input type="text" class="form-control text-right" id="fdc_vat_amount" name="fdc_vat_amount" value="0" readonly="">	
								</div>
							</div>

							<div class="form-group">
								<label for="total" class="col-md-8 control-label">Total</label>
								<div class="col-md-4" style="text-align:right">
									<input type="text" class="form-control text-right" id="total" value="0" readonly="">
								</div>
							</div>
							<div class="form-group">
								<label for="total" class="col-md-8 control-label">Uang Muka</label>
								<div class="col-md-4" style="text-align:right">
									<input type="text" class="money form-control text-right" id="fdc_downpayment_claim" name="fdc_downpayment_claim" value="0" style="text-align: right;">
									<div id="fdc_downpayment_claim_err" class="text-danger"></div>
								</div>
							</div>
						</div>
						
					</div>
					
					
					

                </div>
				<!-- end box body -->

                <div class="box-footer text-right">
                    <!-- <a id="btnSubmitAjax" href="#" class="btn btn-primary"><=lang("Simpan")?></a> -->
                </div>
                <!-- end box-footer -->
            </form>
        </div>
    </div>
</section>

<!-- modal atau popup "ADD" -->
<div id="myModal" class="modal fade" role="dialog" >
	<div class="modal-dialog" style="display:table;width:800px">
		<!-- modal content -->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Add SO Detail")?></h4>
			</div>

			<div class="modal-body">				
				<form id="form-detail" class="form-horizontal">
				    <input type='hidden' id='fin_rec_id'/>
					<div class="form-group">
						<label for="" class="col-md-2 control-label"><?=lang("Items")?></label>
						<div class="col-md-10">
							<input type="text"  id="frm-item" class="unfocus form-control"></select>
						</div>
					</div>
					<div class="form-group">
						<label for="select-unit" class="col-md-2 control-label"><?=lang("Unit")?></label>
						<div class="col-md-10">
							<input type="TEXT" id="frm-unit"  class="unfocus form-control" style="width:100%"></select>
						</div>
					</div>

					<div class="form-group">
						<label for="fdb_qty" class="col-md-2 control-label"><?=lang("Qty")?></label>
						<div class="col-md-2">
							<input type="number" class="form-control text-right numeric" id="fdb_qty" value="1">
						</div>
					</div>

					<div class="form-group">
						<label for="fst_memo_item" class="col-md-2 control-label"><?=lang("Memo")?></label>
						<div class="col-md-10">
							<textarea type="text" class="form-control" id="fst_memo_item" rows="3"></textarea>
						</div>
					</div>

				</form>
				
			</div>
			<div class="modal-footer">
				<button id="btn-edit-detail" type="button" class="btn btn-primary" ><?=lang("Ubah")?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?=lang("Close")?></button>
			</div>			
		</div>
	</div>

    <script type="text/javascript">
        $(function(){
            $("#btn-edit-detail").click(function(e){
                data = selectedDetail.data();
                data.fdb_qty = money_format($("#fdb_qty").val());
                data.fst_memo_item = $("#fst_memo_item").val();
                
                selectedDetail.data(data).draw(false);
				selectedDetail = null;
                
            });
        });
    </script>

</div>

<?php
	echo $mdlJurnal;
	echo $mdlEditForm;
?>

<script type="text/javascript" info="bind">
	$(function(){
		$(document).bind('keydown', 'alt+d', function(){
			$("#btn-add-detail").trigger("click");
		});
		$(document).bind('keydown', 'alt+j', function(){
			$("#btnJurnal").trigger("click");
		});
	});
</script>

<script type="text/javascript" info="define">
	var selectedSalesOrder = {
		id:0
	};
	var selectedDetail;
</script>

<script type="text/javascript" info="event">
	$(function(){
		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>tr/sales/invoice/add");
		});

		$("#btnSubmitAjax").click(function(e){
            e.preventDefault();
            submitAjax(0);
		});
		
		$("#btnJurnal").click(function(e){
			e.preventDefault();
			MdlJurnal.showJurnalByRef("SIV",$("#fin_inv_id").val());
		});

		$("#btnDelete").confirmation({
			title:"<?= lang("Hapus data ini ?") ?>",
			rootSelector: '#btnDelete',
			placement:'left',
		});

		$("#btnDelete").click(function(e){
			e.preventDefault();
			deleteAjax(0);
		});

		$(".term_payment").change(function(e){
			e.preventDefault();
			getPaymentDueDate();

		})
		$("#btnList").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>tr/sales/invoice");
		});

	});
</script>

<script type="text/javascript" info="init">
	$(function(){	
		$("#fdt_inv_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker('update');
		$("#fin_warehouse_id").val(null);
		$("#fin_sales_id").val(null);
		


		$("#fin_salesorder_id").select2({
			ajax: {
				delay: 500,
				url: '<?=site_url()?>tr/sales/invoice/get_select2_salesorder_list',
				dataType: 'json',
				processResults: function (data) {      
					arrSalesOrder = data.data.arrSalesOrder;					
					salesOrderList = arrSalesOrder.map(function(salesOrder){
						return {
							id: salesOrder.fin_salesorder_id,
							text: salesOrder.fst_salesorder_no,
							fdt_salesorder_datetime:App.dateFormat(salesOrder.fdt_salesorder_datetime),
							fst_curr_code:salesOrder.fst_curr_code,
							fst_customer_name:salesOrder.fst_customer_name,
							fin_terms_payment:salesOrder.fin_terms_payment,
							fdc_downpayment_rest:salesOrder.fdc_downpayment_rest,
							fbl_is_vat_include:salesOrder.fbl_is_vat_include,
							fin_warehouse_id:salesOrder.fin_warehouse_id,
							fin_sales_id:salesOrder.fin_sales_id,
						}
					});
					

					return {
						results: salesOrderList
					};
    			}
			},
			minimumInputLength: 0,
			templateResult: function(salesOrder){
				if (salesOrder.loading == true){
					return salesOrder.text;
				};

				return $("<span style='width:150px'>" + salesOrder.text + "</span><span style='width:100px'>" + salesOrder.fdt_salesorder_datetime + "</span><span>" + salesOrder.fst_customer_name + "</span>");
			}
		}).on("select2:select",function(e){
			data = e.params.data;
			selectedSalesOrder = data;

			$("#fst_customer_name").val(selectedSalesOrder.fst_customer_name);
			$("#fst_curr_code").val(selectedSalesOrder.fst_curr_code).trigger("change");
			$("#fin_warehouse_id").val(selectedSalesOrder.fin_warehouse_id);
			$("#fin_sales_id").val(selectedSalesOrder.fin_sales_id);
			$("#fin_terms_payment").val(selectedSalesOrder.fin_terms_payment);
			getPaymentDueDate();

			$("#fdc_downpayment_rest").html(App.money_format(selectedSalesOrder.fdc_downpayment_rest));
			if (selectedSalesOrder.fbl_is_vat_include == 1){
				$("#fbl_is_vat_include").prop("checked",true);
			}else{
				$("#fbl_is_vat_include").prop("checked",false);
			}		
			$("#fst_sj_id_list").empty().trigger("change.select2");
		});

		$("#fst_curr_code").change(function(e){
			exchangeRate = $("#fst_curr_code option:selected").data("rate");
			$("#fdc_exchange_rate_idr").val(App.money_format(exchangeRate));
		});

		$("#fst_sj_id_list").select2({
			ajax: {
				delay: 500,
				url: function(params){
					return '<?=site_url()?>tr/sales/invoice/get_select2_uninvoice_sj/' + selectedSalesOrder.id;
				},
				dataType: 'json',
				processResults: function (data) {      
					arrSJ = data.data.arrSJ;					
					suratJalanList = arrSJ.map(function(sj){
						return {
							id: sj.fin_sj_id,
							text: sj.fst_sj_no,
							fdt_sj_datetime:App.dateFormat(sj.fdt_sj_datetime),
						}
					});
					

					return {
						results: suratJalanList
					};
    			}
			},
			minimumInputLength: 0,			
			templateResult: function(sj){
				if (sj.loading == true){
					return sj.text;
				};
				return $("<span style='width:150px'>" + sj.text + "</span><span style='width:100px'>" + sj.fdt_sj_datetime + "</span>");
			}
		}).on('select2:opening select2:closing', function( event ) {
			var $searchfield = $(this).parent().find('.select2-search__field');
    		$searchfield.prop('disabled', true);
		}).on('change',function(e){
			data = $("#fst_sj_id_list").select2("data");

			$sjList = data.map(function(sj){
				return sj.id;
			})
			getDetailSJ($sjList,function(){

			});
		});


		$('#tblInvItems').on('preXhr.dt', function ( e, settings, data ) {
		 	//add aditional data post on ajax call
		 	data.sessionId = "TEST SESSION ID";
		}).on('init.dt',function(){
			$(".dataTables_scrollHeadInner").css("min-width","100%");
			$(".dataTables_scrollHeadInner > table").css("min-width","100%");
			$(".dataTables_scrollBody").css("position","static");
		}).DataTable({
			scrollX: true,
            ordering: true,
			columns:[
                {"title" : "id","width": "5%",data:"fin_rec_id",visible:true},
                {"title" : "Items","width": "20%",orderable:false,data:"fin_item_id",
					render: function(data,type,row){
						return row.fst_custom_item_name;
					}
                },
				{"title" : "Qty SO","width": "5%",data:"fdb_qty_so",className:'text-right',orderable:false,},
				{"title" : "Qty SJ","width": "5%",data:"fdb_qty_sj",className:'text-right',orderable:false,},
				{"title" : "Unit","width": "10%",data:"fst_unit",orderable:false},
                {"title" : "Price","width": "10%",data:"fdc_price",orderable:false,className:'text-right'},
                {"title" : "Disc %","width": "10%",data:"fst_disc_item",orderable:false,className:'text-right'},
                {"title" : "Disc Amount","width": "10%",orderable:false,className:'text-right',
                    render:function(data,type,row){
						disc = row.fdc_disc_amount_per_item * row.fdb_qty_sj;
						return App.money_format(disc);
                    }
                },
                {"title" : "Sub Total","width": "10%",orderable:false,className:'text-right',
                    render:function(data,type,row){
						disc = row.fdc_disc_amount_per_item * row.fdb_qty_sj;
						total = row.fdb_qty_sj * row.fdc_price;
						//disc = calculateDisc(row.fdb_qty * money_parse(row.fdc_price),row.fst_disc_item);
                        return App.money_format(total - disc);;
                    }
                },                
			],
			processing: true,
			serverSide: false,
			searching: false,
			lengthChange: false,
			paging: false,
			info:false,
			fnRowCallback: function( nRow, aData, iDisplayIndex ) {},
		}).on('draw',function(){
			$('.xbtn-delete').confirmation({
				//rootSelector: '[data-toggle=confirmation]',
				rootSelector: '.btn-delete',
				// other options
			});	
			calculateTotal();
		});

		App.fixedSelect2();
		initForm();
	});
</script>

<script type="text/javascript" info="function">
	function getDetailSJ(sjList, callback){
		App.blockUIOnAjaxRequest();
		dataPost = [];
		dataPost.push({
			name:SECURITY_NAME,
			value:SECURITY_VALUE,
		});
		
		dataPost.push({
			name:"fst_sj_id_list",
			value:sjList,
		});

		
		$.ajax({
			url:"<?=site_url()?>tr/sales/invoice/get_detail_sj",
			method:"POST",
			data:dataPost,
		}).done(function(resp){
			arrSJDetail = resp.data.arrSJDetail;
			sjDetailList = arrSJDetail.map(function(sjDetail){
				return {
					fin_rec_id:0,
					fin_item_id:sjDetail.fin_item_id,
					fst_custom_item_name:sjDetail.fst_custom_item_name,
					fdb_qty_so:sjDetail.fdb_qty_so,
					fdb_qty_sj:sjDetail.fdb_qty_sj,
					fst_unit:sjDetail.fst_unit,
					fdc_price:sjDetail.fdc_price,
					fst_disc_item:sjDetail.fst_disc_item,
					fdc_disc_amount_per_item:sjDetail.fdc_disc_amount_per_item					
				}
			});
			App.log(sjDetailList);
			
			t = $("#tblInvItems").DataTable();
			t.clear();
			t.rows.add(sjDetailList).draw();
			
			
		});

	}
	function calculateTotal(){
        t = $("#tblInvItems").DataTable();
		datas = t.rows().data();    
		ttlBfDisc =0;
		ttlDisc = 0;
		$.each(datas,function(i,v){
			qty = v.fdb_qty_sj;
			price =  v.fdc_price;

			ttlBfDisc += qty * price;
			ttlDisc += qty * v.fdc_disc_amount_per_item;
		});
		
		var total = ttlBfDisc - ttlDisc;
		var dppAmount;

		if ($("#fbl_is_vat_include").prop('checked')){
			dppAmount =  total / (1 + $("#fdc_vat_percent").val() /100);						
		}else{
			dppAmount = ttlBfDisc - ttlDisc;			
		}

		subTotal = dppAmount;
		vat = ($("#fdc_vat_percent").val() /100) * subTotal;
		total = subTotal + vat;
		$("#sub-total").val(money_format(subTotal));	
		$("#ttl-disc").val(money_format(ttlDisc));	
		$("#fdc_vat_amount").val(money_format(vat));
		$("#total").val(money_format(total));	
	}

	function getPaymentDueDate(){
		tglInv = $("#fdt_inv_datetime").val();
		tglDueDate = moment(tglInv,DATETIMEPICKER_FORMAT_MOMENT).add($("#fin_terms_payment").val(), 'days');
		$("#fdt_payment_due_date").val(tglDueDate.format(DATEPICKER_FORMAT_MOMENT));
	}


	function submitAjax(confirmEdit){
        
        data = $("#frmInvoice").serializeArray();
		detail = new Array();		

		/*
		t = $('#tblInvDetails').DataTable();
		datas = t.data();
		$.each(datas,function(i,v){
			detail.push(v);
		});
		*/

		
		mode = $("#fin_inv_id").val() != 0 ? "EDIT" : "ADD";

		
		if (mode == "EDIT"){
			url = "<?=site_url()?>tr/sales/invoice/ajx_edit_save";
			if (confirmEdit == 0 && mode != "ADD"){
				MdlEditForm.saveCallBack = function(){
					submitAjax(1);
				};		
				MdlEditForm.show();
				return;
			}

			data.push({
				name : "fin_user_id_request_by",
				value: MdlEditForm.user
			});
			data.push({
				name : "fst_edit_notes",
				value: MdlEditForm.notes
			});									
		}else{
			url = "<?=site_url()?>tr/sales/invoice/ajx_add_save";
		}
			
		App.blockUIOnAjaxRequest("<h5>Please wait....</h5>");
        $.ajax({
            url : url,
            data: data,
            method: "POST",
        }).done(function(resp){
			if (resp.message != "")	{
				$.alert({
					title: 'Message',
					content: resp.message,
					buttons : {
						OK : function(){
							if(resp.status == "SUCCESS"){
								$("#btnNew").trigger("click");
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
			}

        }).always(function(resp){
		});

	}


	function initForm(){
		if($("#fin_inv_id").val() != 0){
			App.blockUIOnAjaxRequest();
			$.ajax({
				url:"<?= site_url() ?>tr/sales/invoice/fetch_data/" + $("#fin_inv_id").val(),
			}).done(function(resp){

				dataH = resp.trinvoice;
				detailData = resp.trinvoicedetails;
				if (dataH == null){
					alert("<?= lang("Data Invoice tidak ditemukan !") ?>");
					$("#btnNew").trigger("click");					
				}
				App.autoFillForm(dataH);
				$("#fdt_inv_datetime").val(dateTimeFormat(dataH.fdt_inv_datetime)).datetimepicker("update");
				App.addOptionIfNotExist("<option value='"+dataH.fin_salesorder_id+"'>"+ dataH.fst_salesorder_no +"</option>","fin_salesorder_id");
				$("#fst_customer_name").val(dataH.fst_customer_name);
				$("#fin_warehouse_id").val(dataH.fin_warehouse_id);
				$("#fin_sales_id").val(dataH.fin_sales_id);
				getPaymentDueDate();
				$("#fdc_downpayment_rest").html(App.money_format(dataH.fdc_downpayment_rest));
				$("#fdc_total_return").html(App.money_format(dataH.fdc_total_return));

				var fstSJIdList = detailData.map(function(dataD){
					App.addOptionIfNotExist("<option value='"+dataD.fin_sj_id+"'>"+ dataD.fst_sj_no +"</option>","fst_sj_id_list");
					return dataD.fin_sj_id;
				});
				//App.log(fstSJIdList);
				$("#fst_sj_id_list").val(fstSJIdList).trigger("change");
				


				/*
				$("#fin_relation_id").val(resp.fin_relation_id);	
				$("#fin_terms_payment").val(resp.fin_terms_payment);
				$("#fdt_payment_due_date").val(dateFormat(resp.fdt_payment_due_date));
				$("#fst_reff_no").val(resp.fst_reff_no);
				$("#fst_inv_memo").val(resp.fst_inv_memo);
				$("#fdc_downpayment_claimed").val(resp.fdc_downpayment_claimed);
				$("#fst_sj_id_list").val(resp.fin_sj_id).trigger("change");
				*/
			});

		}
		
	}
	function deleteAjax(confirmDelete){

		if (confirmDelete == 0){
			MdlEditForm.saveCallBack = function(){
				deleteAjax(1);
			};		
			MdlEditForm.show();
			return;
		}
		var dataSubmit = [];		
		dataSubmit.push({
			name : SECURITY_NAME,
			value: SECURITY_VALUE
		});
		dataSubmit.push({
			name : "fin_user_id_request_by",
			value: MdlEditForm.user
		});
		dataSubmit.push({
			name : "fst_edit_notes",
			value: MdlEditForm.notes
		});

		blockUIOnAjaxRequest("<h5>Deleting ....</h5>");
		$.ajax({
			url:"<?= site_url() ?>tr/sales/invoice/delete/" + $("#fin_inv_id").val(),
			method:"POST",
			data:dataSubmit,

		}).done(function(resp){
			consoleLog(resp);
			$.unblockUI();
			if (resp.message != "")	{
				$.alert({
					title: 'Message',
					content: resp.message,
					buttons : {
						OK : function(){
							if(resp.status == "SUCCESS"){
								$("#btnList").trigger("click");
							}
						},
					}
				});
			}

			if(resp.status == "SUCCESS") {
				data = resp.data;
				$("#fin_inv_id").val(data.insert_id);

				//Clear all previous error
				$(".text-danger").html("");
				// Change to Edit mode
				$("#frm-mode").val("EDIT");  //ADD|EDIT
				$('#fst_inv_no').prop('readonly', true);				
			}
		});
	}

</script>

<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
