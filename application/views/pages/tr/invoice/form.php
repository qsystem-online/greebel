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
				<div class="btn-group btn-group-sm  pull-right">					
					<a id="btnNew" class="btn btn-primary" href="#" title="<?=lang("Tambah Baru")?>"><i class="fa fa-plus" aria-hidden="true"></i></a>
					<a id="btnSubmitAjax" class="btn btn-primary" href="#" title="<?=lang("Simpan")?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
					<a id="btnPrint" class="btn btn-primary" href="#" title="<?=lang("Cetak")?>"><i class="fa fa-print" aria-hidden="true"></i></a>
					<a id="btnJurnal" class="btn btn-primary" href="#" title="<?=lang("Jurnal")?>"><i class="fa fa-align-left" aria-hidden="true"></i></a>
					<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
					<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
				</div>
			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmInvoice" class="form-horizontal" action="<?=site_url()?>tr/delivery_order/add" method="POST" enctype="multipart/form-data">			
				<div class="box-body">
					<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">			
					<input type="hidden" id="frm-mode" value="<?=$mode?>">
					<input type="hidden" class="form-control" id="fin_inv_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_inv_id" value="<?=$fin_inv_id?>" readonly>

					
					<div class="form-group">
						<label for="fst_inv_no" class="col-md-2 control-label"><?=lang("Invoice No")?> #</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_inv_no" placeholder="<?=lang("Invoice No")?>" name="fst_inv_no" value="<?=$fst_inv_no?>" readonly>
							<div id="fst_inv_no_err" class="text-danger"></div>
						</div>
						
						<label for="fdt_inv_date" class="col-md-2 control-label"><?=lang("Invoice Date")?> *</label>
						<div class="col-md-4">
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control text-right datetimepicker" id="fdt_inv_date" name="fdt_inv_date"/>
							</div>
							<div id="fdt_inv_date_err" class="text-danger"></div>
							<!-- /.input group -->
						</div>						
                    </div>

					<div class="form-group">						
						<label for="fin_sj_id" class="col-md-2 control-label"><?=lang("Delivery Order")?> </label>
						<div class="col-md-4">
                            <select id="fst_sj_id_list" class="form-control" name="fst_sj_id_list">
                            </select>
							<div id="fst_sj_id_list_err" class="text-danger"></div>
						</div>
						<label for="fin_sj_id" class="col-md-2 control-label"><?=lang("Sales Order")?> </label>
						<div class="col-md-4">
                            <input id="fst_salesorder_no" class="form-control unfocus">
						</div>
					</div>

					<div class="form-group">						
						<label for="fin_relation_id" class="col-md-2 control-label"><?=lang("Customer")?> </label>
						<div class="col-md-10">
							<select  class="form-control" id="fin_relation_id" name="fin_relation_id">
                            </select>
							<div id="fin_relation_id_err" class="text-danger"></div>
						</div>
					</div>

                    
					<div class="form-group">						
						<label for="fin_warehouse_id" class="col-md-2 control-label"><?=lang("Gudang")?> </label>
						<div class="col-md-4">
                            <select id="fin_warehouse_id" class="form-control" name="fin_warehouse_id">
                            </select>
							<div id="fin_warehouse_id_err" class="text-danger"></div>
						</div>
						<label for="fin_sales_id" class="col-md-2 control-label"><?=lang("Sales")?> </label>
						<div class="col-md-4">
                            <select id="fin_sales_id" class="form-control" name="fin_sales_id">
                            </select>
							<div id="fin_sales_id_err" class="text-danger"></div>
						</div>
					</div>
					<div class="form-group">						
						<label for="fin_terms_payment" class="col-md-2 control-label"><?=lang("Terms")?> </label>
						<div class="col-md-1">	
							<input type="TEXT" id="fin_terms_payment" class="text-center form-control" name="fin_terms_payment" />															
						</div>
						<div class="col-md-3">	
							<input type="TEXT" id="fdt_payment_due_date" class="text-right form-control unfocus" name="fdt_payment_due_date" />
						</div>
						<div class="col-md-2 checkbox disabled">
							<label><input id="fbl_is_vat_include" type="checkbox" value="" name="fbl_is_vat_include" disabled><?=lang("Include PPN")?></label>
						</div>
					</div>
					<div class="form-group">						
						<label for="fst_reff_no" class="col-md-2 control-label"><?=lang("Reff No")?>. </label>
						<div class="col-md-4">	
							<input type="TEXT" id="fst_reff_no" class="form-control" name="fst_reff_no" />															
						</div>
					</div>
					

					<table id="tblInvItems" class="table table-bordered table-hover table-striped" style="width:100%"></table>
                    <div id="detail_err" class="text-danger"></div>
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
									<input type="text" class="money form-control text-right" id="fdc_downpayment_claimed" name="fdc_downpayment_claimed" value="0" style="text-align: right;">
									<div id="fdc_downpayment_claimed_err" class="text-danger"></div>
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
?>






<script type="text/javascript">
var selectedDetail;

$(function(){		
		initVarForm();	


		/*			
        $("#fin_relation_id").select2().on("change",function(e){
            //consoleLog(e);
			//alert("selec2 on change" + $("#fin_relation_id").select2("val"));			
            $.ajax({
                url:"<?= site_url() ?>tr/invoice/get_select2_uninvoice_sj/" + $("#fin_relation_id").select2("val") +"/" + $("#fin_inv_id").val(),
            }).done(function(resp){
                data = resp.data;
                $("#fst_sj_id_list").select2({
                    data: data.arrSJ,
              $.ajax({
                url:"<?= site_url() ?>tr/invoice/get_select2_uninvoice_sj/" + $("#fin_relation_id").select2("val") +"/" + $("#fin_inv_id").val(),
            }).done(function(resp){
                data = resp.data;
                $("#fst_sj_id_list").select2({
                    data: data.arrSJ,
                }).on("change",function(e){                    
					data = $("#fst_sj_id_list").select2("data");
					listSuratJalanChange();					
				});
				$("#fst_sj_id_list").trigger("change");		
				fixedSelect2();            
            });
        });
		*/

		//$("#fdt_sj_date").datetimepicker('update'), dateFormat("<= date("Y-m-d")?>"));
		$("#fdt_inv_date").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>"));
		$("#fdt_inv_date").datetimepicker('update');
		$("#fdt_inv_date").change(function(e){
			e.preventDefault();
			getPaymentDueDate();
		});
		$("#fin_terms_payment").change(function(e){
			e.preventDefault();
			getPaymentDueDate();
		});

        $(".unfocus").focus(function(){
            $(this).blur();
        });

		$(document).bind('keydown', 'alt+d', function(){
			//alert("TEST COPY");
			$("#btn-add-detail").trigger("click");
		});

		$('#tblInvItems').on('preXhr.dt', function ( e, settings, data ) {
		 	//add aditional data post on ajax call
		 	data.sessionId = "TEST SESSION ID";
		}).DataTable({
			scrollX: true,
            ordering: true,
			columns:[
                {"title" : "id","width": "5%",data:"fin_rec_id",visible:true},
                {"title" : "SJ ID","width": "5%",data:"fin_sj_id",visible:false},
				{"title" : "Items","width": "20%",orderable:false,data:"fin_item_id",
					render: function(data,type,row){
						return row.fst_custom_item_name;
					}
                },
				{"title" : "Custom Name","width": "32%",data:"fst_custom_item_name",visible:false,orderable:false},
				{"title" : "Qty","width": "5%",data:"fdb_qty",className:'text-right',orderable:false,},
                {"title" : "Unit","width": "10%",data:"fst_unit",orderable:false},
                {"title" : "Price","width": "10%",data:"fdc_price",orderable:false,className:'text-right'},
                {"title" : "Disc %","width": "10%",data:"fst_disc_item",orderable:false,className:'text-right'},
                {"title" : "Disc Amount","width": "10%",orderable:false,className:'text-right',
                    render:function(data,type,row){
						disc = calculateDisc(row.fdb_qty * money_parse(row.fdc_price),row.fst_disc_item);
						return money_format(disc);
                    }
                },
                {"title" : "Sub Total","width": "10%",orderable:false,className:'text-right',
                    render:function(data,type,row){
						total = row.fdb_qty * money_parse(row.fdc_price);
						disc = calculateDisc(row.fdb_qty * money_parse(row.fdc_price),row.fst_disc_item);
                        return money_format(total - disc);;
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
		});


		fixedSelect2();
        $("#fst_shipping_address").click(function(e){
            calculateTotal();
        })
        $("#btnSubmitAjax").click(function(e){
            e.preventDefault();
            submitAjax();
		});

		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>tr/invoice/add");
		});
		
		$("#btnDelete").confirmation({
			title:"<?= lang("Hapus data ini ?") ?>",
			rootSelector: '#btnDelete',
			placement:'left',
		});
		$("#btnDelete").click(function(e){
			e.preventDefault();
			blockUIOnAjaxRequest("<h5>Deleting ....</h5>");
			$.ajax({
				url:"<?= site_url() ?>tr/invoice/delete/" + $("#fin_inv_id").val(),
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
									window.location.href = "<?= site_url() ?>tr/invoice";
									//return;
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
		});
		$("#btnList").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>tr/invoice");
		});

		$("#btnJurnal").click(function(e){
			e.preventDefault();
			createJurnal();
		});
		

    });
    
	function initForm(){
		$.ajax({
			url:"<?= site_url() ?>tr/invoice/fetch_data/" + $("#fin_inv_id").val(),
		}).done(function(resp){
			$("#fst_inv_no").val(resp.fst_inv_no);
			$("#fdt_inv_date").val(dateTimeFormat(resp.fdt_inv_date));
			$("#fin_relation_id").val(resp.fin_relation_id);	
			$("#fin_terms_payment").val(resp.fin_terms_payment);
			$("#fdt_payment_due_date").val(dateFormat(resp.fdt_payment_due_date));
			$("#fst_reff_no").val(resp.fst_reff_no);
			$("#fst_inv_memo").val(resp.fst_inv_memo);
			$("#fdc_downpayment_claimed").val(resp.fdc_downpayment_claimed);
			$("#fst_sj_id_list").val(resp.fin_sj_id).trigger("change");
			
		});
	}

    

    function calculateTotal(){
        t = $("#tblInvItems").DataTable();
		datas = t.rows().data();    
		ttlBfDisc =0;
		ttlDisc = 0;
		$.each(datas,function(i,v){
			qty = v.fdb_qty;
			price =  money_parse(v.fdc_price) ;

			ttlBfDisc += qty * price;
			ttlDisc +=  calculateDisc(qty*price, v.fst_disc_item);
		});

		if ($("#fbl_is_vat_include").prop('checked')){
			total = ttlBfDisc - ttlDisc;			
			vat = $("#fdc_vat_percent").val() * 1;
			vat = 1 + (vat/100);
			subTotal = total / vat;
			vat = ($("#fdc_vat_percent").val() /100) * subTotal;	
		}else{
			subTotal= ttlBfDisc - ttlDisc;
			vat = ($("#fdc_vat_percent").val() /100) * subTotal;
			total = subTotal + vat;
		}

		$("#sub-total").val(money_format(subTotal));	
		$("#ttl-disc").val(money_format(ttlDisc));	
		$("#fdc_vat_amount").val(money_format(vat));
		$("#total").val(money_format(total));	
	}
	function calculateDisc(amount, disc){
		var strArray = disc.split("+");
		totalDisc = 0;
		$.each(strArray,function(i,v){
			disc = amount * (v / 100);
			totalDisc += disc;
			amount = amount - disc;
		});
		return totalDisc;
	}

</script>
<Script type="text/javascript">
    function listSuratJalanChange(){
		data = $("#fst_sj_id_list").select2("data");		
		t = $("#tblInvItems").DataTable();
		t.clear();


        $.each(data,function(i,v){
			//consoleLog(v);
			//dataH = v.arrSJ;
			$('#fin_relation_id').empty();
			var newOption = new Option(v.fst_relation_name, v.fin_relation_id, false, false);
			$('#fin_relation_id').append(newOption).trigger('change');

			$("#fst_salesorder_no").val(v.fst_salesorder_no);
			
			$('#fin_warehouse_id').empty();
			var newOption = new Option(v.fst_warehouse_name, v.fin_warehouse_id, false, false);
			$('#fin_warehouse_id').append(newOption).trigger('change');

			$('#fin_sales_id').empty();
			var newOption = new Option(v.fst_sales_name, v.fin_sales_id, false, false);
			$("#fin_sales_id").append(newOption).trigger('change');

			$("#fin_terms_payment").val(v.fin_terms_payment);
			getPaymentDueDate();
			consoleLog(v);
			availableToClaimed = money_parse(v.fdc_downpayment_paid) - money_parse(v.ttl_downpayment_claimed)
			$("#fdc_downpayment_claimed").val(money_format(availableToClaimed));		

			if (v.fbl_is_vat_include == "1"){
				$("#fbl_is_vat_include").prop( "checked", true);
			}else{
				$("#fbl_is_vat_include").prop( "checked", false);
			}

            details = v.details;
            $.each(details , function(i2,v2){
				v2.fdc_price = money_format(v2.fdc_price);
                t.row.add(v2);
            })            
		});		
		t.draw();

		calculateTotal();
	}
	
    function initVarForm(){
		blockUIOnAjaxRequest();

        $.ajax({
            url:"<?=site_url()?>tr/invoice/initVarForm/" + $("#fin_inv_id").val(),
        }).done(function(resp){
            data = resp.data;
			/*
            $("#fin_relation_id").select2({
                data:data.arrCustomer
            });
			$("#fin_relation_id").trigger("change");
			*/
			$("#fst_sj_id_list").select2({
				placeholder: "<?= lang('Pilih Surat Jalan')?>",
				data:data.arrSJ,
			}).on("change",function(e){                    
				data = $("#fst_sj_id_list").select2("data");
				consoleLog(data);
				listSuratJalanChange();					
			});
			$("#fst_sj_id_list").val("").change();			
            fixedSelect2();
		});


		
		$(document).ajaxStop(function(){
			<?php if($mode == "EDIT"){?>
				consoleLog("initform");
				$(this).unbind("ajaxStop");
				initForm();
			<?php } ?>
		});

    }
    function submitAjax(){
        
        data = $("#frmInvoice").serializeArray();
		detail = new Array();		

		t = $('#tblInvDetails').DataTable();
		datas = t.data();
		$.each(datas,function(i,v){
			detail.push(v);
		});
		data.push({
			name:"detail",
			value: JSON.stringify(detail)
		});
	   
		url = "<?=site_url()?>tr/invoice/ajx_add_save";
		<?php if ($mode == "EDIT"){ ?>
			url = "<?=site_url()?>tr/invoice/ajx_edit_save";
		<?php } ?>

		blockUIOnAjaxRequest("<h5>Please wait....</h5>");
        $.ajax({
            url : url,
            data: data,
            method: "POST",
        }).done(function(resp){
			$.unblockUI();
            if (resp.message != "")	{
				$.alert({
					title: 'Message',
					content: resp.message,
					buttons : {
						OK : function(){
							if(resp.status == "SUCCESS"){
								//window.location.href = "<= site_url() ?>tr/delivery_order/lizt";
								//return;
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
				$("#fin_inv_id").val(data.insert_id);

				//Clear all previous error
				$(".text-danger").html("");
				// Change to Edit mode
				$("#frm-mode").val("EDIT");  //ADD|EDIT
				$('#fst_inv_no').prop('readonly', true);				
			}
        }).always(function(resp){
			//$.unblockUI();
		});

	}

	function getPaymentDueDate(){
		tglInv = $("#fdt_inv_date").val();
		tglDueDate = moment(tglInv,DATETIMEPICKER_FORMAT_MOMENT).add($("#fin_terms_payment").val(), 'days');
		$("#fdt_payment_due_date").val(tglDueDate.format(DATEPICKER_FORMAT_MOMENT));
	}

	function delete_showJurnal(){
		ttlDebet = 0;
		ttlCredit =0;
		$("#tblJurnal > tbody").empty();
		$("#tblJurnal > tfoot").empty();

		<?php foreach($jurnalAcc as $key=>$jurnal){ ?>
			if ("<?=$key?>" == "piutang_dagang"){
				debet = money_parse($("#sub-total").val()) + money_parse($("#fdc_vat_amount").val()) - money_parse($("#fdc_downpayment_claimed").val()) ;
				ttlDebet += debet;
				credit = 0;
			}
			if ("<?=$key?>" == "disc_dagang"){
				debet = money_parse($("#ttl-disc").val());
				ttlDebet += debet;
				credit = 0;
			}
			if ("<?=$key?>" == "uang_muka"){
				debet = money_parse($("#fdc_downpayment_claimed").val());
				ttlDebet += debet;
				credit = 0;
			}

			if ("<?=$key?>" == "sales"){
				debet = 0;
				credit = money_parse($("#sub-total").val()) + money_parse($("#ttl-disc").val());
				ttlCredit += credit;
			}
			if ("<?=$key?>" == "ppn"){
				debet = 0;
				credit = money_parse($("#fdc_vat_amount").val());
				ttlCredit += credit;
			}
			

			$("#tblJurnal > tbody").append("<tr><td class='<?=$jurnal["pos"]?>'><?=$jurnal["code"] . " - " . $jurnal["name"] ?></td><td class='text-right'>"+money_format(debet)+"</td><td class='text-right'>"+money_format(credit)+"</td></tr>");
		<?php }	?>
		
		$("#tblJurnal > tfoot").append("<tr><td class='text-right'>Total</td><td class='text-right'>"+money_format(ttlDebet)+"</td><td class='text-right'>"+money_format(ttlCredit)+"</td></tr>");


		$("#mdlJurnal").modal({
			backdrop:"static",
		});
	}

	function createJurnal(){				
		var arrJurnal = [];
		<?php foreach($jurnalAcc as $key=>$jurnal){ ?>
			var obj = {
				key:"<?=$key?>",
				code:"<?=$jurnal["code"]?>",	
				name:"<?=$jurnal["name"]?>",	
				debet:0,	
				credit:0,	
				pos:"<?=$jurnal["pos"]?>"					
			}

			if (obj.key == "piutang_dagang"){
				obj.debet = money_parse($("#sub-total").val()) + money_parse($("#fdc_vat_amount").val()) - money_parse($("#fdc_downpayment_claimed").val()) ;
				obj.credit = 0;
			}
			if (obj.key == "disc_dagang"){
				obj.debet = money_parse($("#ttl-disc").val());
				obj.credit = 0;
			}
			if (obj.key == "uang_muka"){
				obj.debet = money_parse($("#fdc_downpayment_claimed").val());
				obj.credit = 0;
			}
			if (obj.key == "sales"){
				obj.debet = 0;
				obj.credit = money_parse($("#sub-total").val()) + money_parse($("#ttl-disc").val());
			}
			if (obj.key == "ppn"){
				obj.debet = 0;
				obj.credit = money_parse($("#fdc_vat_amount").val());
			}
			arrJurnal.push(obj);
		<?php }	?>

		showJurnal(arrJurnal);	
	}
</Script>

<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
