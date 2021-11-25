<?php
defined('BASEPATH') OR exit('No direct script access allowed');	
?>

<link rel="stylesheet" href="<?=base_url()?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<style>
	.form-group{
		margin-bottom:10px;
	}
</style>

<section class="content-header">
	<h1><?=lang("Permintaan Pembayaran")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Kas Bank") ?></a></li>
		<li class="active title"><?=$title?></li>
	</ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title title pull-left"><?=$title?></h3>
					<?php if ($mode != "VIEW") { ?>
					<div class="btn-group btn-group-sm  pull-right">					
						<a id="btnNew" class="btn btn-primary" href="#" title="<?=lang("Tambah Baru")?>"><i class="fa fa-plus" aria-hidden="true"></i></a>
						<a id="btnSubmitAjax" class="btn btn-primary" href="#" title="<?=lang("Simpan")?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
						<a id="btnPrint" class="btn btn-primary" href="#" title="<?=lang("Cetak")?>"><i class="fa fa-print" aria-hidden="true"></i></a>
						<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
						<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
					</div>
					<?php } ?>
				</div>
				<!-- end box header -->

				<!-- form start -->
				<form id="frmPaymentrequest" class="form-horizontal" action="<?=site_url()?>kas_bank/payment_request/add" method="POST" enctype="multipart/form-data">
					<div class="box-body">
						<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">
						<input type="hidden" id="frm-mode" value="<?=$mode?>">
						<input type="hidden" class="form-control" id="fin_paymentrequest_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_paymentrequest_id" value="<?=$fin_paymentrequest_id?>" readonly>

						<div class="form-group">
							<label class="col-md-12 control-label" id="fst_status" name="fst_status" style="font-style: italic;">#NEED APPROVAL</label>
						</div>

                        <div class="form-group">
							<label for="fst_paymentrequest_no" class="col-md-2 control-label"><?=lang("No. Request")?> #</label>	
							<div class="col-md-4">				
								<input type="tex" id="fst_paymentrequest_no" name="fst_paymentrequest_no" class="form-control"  value="<?=$fst_paymentrequest_no?>" placeholder="PREFIX/BRANCH/YEAR/MONTH/99999" readonly> 
							</div>

							<label for="fdt_paymentrequest_datetime" class="col-md-2 control-label text-right"><?=lang("Tgl. Pengajuan")?> *</label>
							<div class="col-md-4">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control text-right datetimepicker" id="fdt_paymentrequest_datetime" name="fdt_paymentrequest_datetime" />
								</div>
								<div id="fdt_paymentrequest_datetime_err" class="text-danger"></div>
								<!-- /.input group -->
							</div>
						</div>
						<div class="form-group">
                            <label for="fst_company_code" class="col-md-2 control-label"><?=lang("Kode Company")?> *</label>	
							<div class="col-md-4">				
								<input id="fst_company_code" name="fst_company_code" class="form-control" /> 
								<div id="fst_company_code_err" class="text-danger"></div>
							</div>

							<label for="fdt_payment_due_date" class="col-md-2 control-label text-right"><?=lang("Tgl. Jatuh Tempo")?> *</label>
							<div class="col-md-4">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control text-right datepicker" id="fdt_payment_due_date" name="fdt_payment_due_date" />
								</div>
								<div id="fdt_payment_due_date_err" class="text-danger"></div>
								<!-- /.input group -->
							</div>
						</div>			
						
						<?php if ($mode != "VIEW") { ?>
						<div class="form-group" style="margin-bottom:0px">
							<div class="col-md-12" style="text-align:right">
								<button id="btn-add-detail" class="btn btn-primary btn-sm"><i class="fa fa-cart-plus" aria-hidden="true"></i>Tambah Item</button>
							</div>
						</div>
						<?php } ?>
						
						<table id="tbldetails" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
						<!--<div class="form-group">
							<div class="col-sm-12">
								<table id="tbldetails" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
							</div>
							<div id="details_err" class="text-danger"></div>
						</div>-->
						<br>
                        <div class="form-group">
							<div class="col-sm-6">	
								<div class="form-group">
									<div class="col-sm-12">
									<select id="fin_supplier_id" class="form-control non-editable" name="fin_supplier_id">
									<?php									
										$suppliers = $this->msrelations_model->getSupplierList();									
										foreach($suppliers as $supplier){	
											//$selected = ($fin_supplier_id == $supplier->fin_relation_id) ? "selected" :"";
											echo "<option value='$supplier->fin_relation_id'>$supplier->fst_relation_name</option>";
										}									
									?>
									</select>
										<textarea class="form-control" id="fst_pp_memo" placeholder="<?= lang("Memo") ?>" name="fst_pp_memo" rows="3" style="resize:none"></textarea>
										<div id="fst_pp_memo_err" class="text-danger"></div>
									</div>
								</div>
							</div>
							<div class="col-sm-6">	
								<div class="form-group">
									<label class="col-md-8 control-label"  style="padding-top:0px"><?=lang("Total")?> : </label>
									<label id="ttlAmount" class="col-md-4 control-label" style="padding-top:0px" >0.00</label>
								</div>
							</div>
							
						</div>
					</div><!-- end box body -->
					<div class="box-footer text-right">
						<!-- <a id="btnSubmitAjaxOld" href="#" class="btn btn-primary"><=lang("Save Ajax")?></a> -->
					</div><!-- end box-footer -->
					
				</form>
        	</div>
    	</div>
	</div>
</section>

<div id="mdlDetail" class="modal fade in" role="dialog">
	<div class="modal-dialog" style="display:table;width:700px">
		<!-- modal content -->
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">			
			<div class="modal-body">
				<div class="row">
                    <div class="col-md-12">
                        <div style="border:0 px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">
				
								<form class="form-horizontal">
									<input type="hidden" id="fin_rec_id">
									<div class="form-group">
										<label for="fst_notes" class="col-md-3 control-label">Keterangan</label>
										<div class="col-md-9">
											<textarea  class="form-control" id="fst_notes" style="resize:none"></textarea>
											<div id="fst_notes_error" class="text-danger"></div>	
										</div>
									</div>	

									<div class="form-group">
										<label for="fdb_qty" class="col-md-3 control-label">Qty</label>
										<div class="col-md-2">
											<input type="number" class="form-control text-right numeric" id="fdb_qty" value="1" min="1">
										</div>

										<label for="fdc_amount" class="col-md-4 control-label">Nilai</label>
										<div class="col-md-3">
											<input type="text" class="form-control text-right money" id="fdc_amount" value="0" min="0">
										</div>
									</div>	
								</form>
								<div class="modal-footer">
									<button id="btn-add-detail-save" type="button" class="btn btn-primary btn-sm text-center" style="width:15%">Add</button>
									<button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal">Close</button>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript" info="define">
		mdlDetail = {			
			show:function(){
				if (selectedDetail != null){
					var data = selectedDetail.data();
					$("#fst_notes").val(data.fst_notes);
					$("#fdb_qty").val(data.fdb_qty);
					$("#fdc_amount").val(data.fdc_amount);
				}				
				$("#mdlDetail").modal("show");
			},
			hide:function(){
				$("#mdlDetail").modal("hide");
			},
			clear:function(){
				$("#fst_notes").val(null);
				$("#fdb_qty").val(1);
				$("#fdc_amount").val(0);
				selectedDetail = null;
			},			
		};

	</script>

	<script type="text/javascript" info="event">
		$(function(){
			$("#btn-add-detail-save").click(function(e){

				t = $("#tbldetails").DataTable();
				var data = {
					fin_rec_id:0,					
					fst_notes:$("#fst_notes").val(),
					fdb_qty:$("#fdb_qty").val(),
					fdc_amount:$("#fdc_amount").val(),
				};

				if (selectedDetail == null){
					t.row.add(data).draw(false);
				}else{
					t.row(selectedDetail).data(data).draw(false);
				}
				mdlDetail.clear();	
				mdlDetail.hide();		
			});

		});
	</script>
</div>

<?php echo $mdlEditForm ?>
<?php echo $mdlPrint ?>

<script type="text/javascript" info="define">
	var selectedDetail;	
</script>

<!--<script type="text/javascript" info="bind">
	$(document).bind('keydown', 'alt+d', function(){
		$("#btn-add-detail").trigger("click");
	});
</script>-->

<script type="text/javascript" info="event">
	$(function(){
		
		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url()?>tr/kas_bank/payment_request/add";
		});

		$("#btnPrint").click(function(e){
			//frameVoucher.print("<?=site_url()?>tr/kas_bank/payment_request/print_voucher/" + $("#fin_paymentrequest_id").val());
			window.open("<?= site_url() ?>tr/kas_bank/payment_request/print_voucher/" +$("#fin_paymentrequest_id").val() ,"_blank","menubar=0,resizable=0,scrollbars=0,status=0,width=900,height=500");
		});

		$("#btnSubmitAjax").click(function(e){
			e.preventDefault();
			submitAjax(0);
		});

		$("#btnDelete").confirmation({
			title:"<?=lang("Hapus data ini ?")?>",
			rootSelector: '#btnDelete',
			placement: 'left',
		});

		$("#btnDelete").click(function(e){
			e.preventDefault();
			deleteAjax(0);
		});
		
		$("#btnList").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url()?>tr/kas_bank/payment_request/";
		});		

		
		$("#btn-add-detail").click(function(e){
			e.preventDefault();
			mdlDetail.show();			
		});

		//Supplier		
		$("#fin_supplier_id").select2({
			placeholder: "<?= lang("Supplier")?>",
			allowClear: true,
			//data:arrSupplier,
		});
		$("#fin_supplier_id").val(null).change();

		$("#fin_supplier_id").change(function(e){
			e.preventDefault();
			supp_id = $("#fin_supplier_id").val();
			if (supp_id != null){
				getAccBank(supp_id,function(resp){
					acc = resp.acc;
					acc_no = acc.fst_bank_acc_no;
					acc_name = acc.fst_bank_acc_name;
					if (acc_no =="" || acc_no == null){
						alert("Bank Account Master relasi kosong!");
					}else{
						$("#fst_pp_memo").val(acc_no + "\r\n" + "A/N :" + acc_name);
					}
				});
			}

		});

		
	});
</script>
<script type="text/javascript" info="init">
	$(function(){
		
		$("#fdt_paymentrequest_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");
		$("#fdt_payment_due_date").val(dateFormat("<?= date("Y-m-d")?>")).datepicker("update");

		$('#tbldetails').on('preXhr.dt', function ( e, settings, data ) {
			data.sessionId = "TEST SESSION ID";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "id","width": "0px",sortable:false,data:"fin_rec_id",visible:false},
				{"title" : "Keterangan","width": "140px",sortable:false,data:"fst_notes"},
				{"title" : "Qty","width": "5px",sortable:false,data:"fdb_qty",className:'text-right'},
				{"title" : "Nilai","width": "10px",sortable:false,data:"fdc_amount",
					render: $.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					className:'text-right'
				},
				<?php if ($mode != "VIEW") { ?>
				{"title" : "Action","width": "5px",sortable:false,className:'dt-body-center text-center',
					render: function(data,type,row){
						var action = '<a class="btn-edit" href="#" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp;';												
						action += '<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';						
						return action;
					}
				},
				<?php } ?>								
			],
			processing: true,
			serverSide: false,
			searching: false,
			lengthChange: false,
			paging: false,
			info:false,				
		}).on('draw',function(){
			$(".dataTables_scrollHeadInner").css("min-width","100%");
			$(".dataTables_scrollHeadInner > table").css("min-width","100%");
			calculateTotal();
		}).on('click','.btn-edit',function(e){
			e.preventDefault();						
			t = $("#tbldetails").DataTable();
			var trRow = $(this).parents('tr');
			selectedDetail = t.row(trRow);

			mdlDetail.show();

		}).on('click','.btn-delete',function(e){
			e.preventDefault();
			t = $('#tbldetails').DataTable();
			var trRow = $(this).parents('tr');
			t.row(trRow).remove().draw();
		});

		initForm();
        App.fixedSelect2();
	});
</script>

<script type="text/javascript" info="function">
	function getAccBank(finRelationId,callback){
		App.getValueAjax({
			site_url:"<?=site_url()?>",
			model:"trpaymentrequest_model",
			func:"getAccBankSupplier",
			params:[finRelationId],
			callback:callback
		});
	}
	
	function submitAjax(confirmEdit){     
		var mode = "<?=$mode?>";  
		var dataDetail = new Array();	
		data = $("#frmPaymentrequest").serializeArray();

		data.push({
			name:SECURITY_NAME,
			value: SECURITY_VALUE
		});	
		
		var t = $('#tbldetails').DataTable();
		var datas = t.data();

		$.each(datas,function(i,v){		
			dataDetail.push(v);
		});

		data.push({
			name:"detail",
			value: JSON.stringify(dataDetail)
		});
		if (mode == "ADD"){
			url = "<?=site_url()?>tr/kas_bank/payment_request/ajx_add_save";
		}else{
			url = "<?=site_url()?>tr/kas_bank/payment_request/ajx_edit_save";
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
									return false;
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
					$("#fin_paymentrequest_id").val(data.insert_id);

					//Clear all previous error
					$(".text-danger").html("");
					// Change to Edit mode
					//$("#frm-mode").val("EDIT");  //ADD|EDIT
					//$('#fst_paymentrequest_no').prop('readonly', true);
				}
        });

	}
	
	function initForm(){
		var mode = "<?=$mode?>";		
		if (mode != "ADD"){			
			App.blockUIOnAjaxRequest();
			$.ajax({
				url:"<?= site_url() ?>tr/kas_bank/payment_request/fetch_data/<?=$fin_paymentrequest_id?>",
			}).done(function(resp){			
				dataH =  resp.data.header;
				if (dataH == null){
					alert("<?=lang("ID transaksi tidak dikenal")?>");
					return false;
				}
                App.autoFillForm(dataH);
				$("#fdt_paymentrequest_datetime").val(dateTimeFormat(dataH.fdt_paymentrequest_datetime)).datetimepicker("update");
				$("#fdt_payment_due_date").val(dateFormat(dataH.fdt_payment_due_date)).datepicker("update");
				if(dataH.fst_active =='S'){
					$("#fst_status").text("#NEED APPROVAL")
				}else if(dataH.fst_active =='A'){
					$("#fst_status").text("#APPROVED")
				}else if(dataH.fst_active =='R'){
					$("#fst_status").text("#REJECTED")
				}

				t = $("#tbldetails").DataTable();
				$.each(resp.data.detail,function(i,v){
					var data = {
						fin_rec_id:v.fin_rec_id,					
						fst_notes:v.fst_notes,
						fdb_qty:v.fdb_qty,
						fdc_amount:v.fdc_amount,
					};
					t.row.add(data);
				});

				t.draw(false);

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
			name : "<?=$this->security->get_csrf_token_name()?>",
			value: "<?=$this->security->get_csrf_hash()?>",
		});
		dataSubmit.push({
			name : "fin_user_id_request_by",
			value: MdlEditForm.user
		});
		dataSubmit.push({
			name : "fst_edit_notes",
			value: MdlEditForm.notes
		});

		var url =  "<?= site_url() ?>tr/kas_bank/payment_request/delete/" + $("#fin_paymentrequest_id").val();
		$.ajax({
			url:url,
			method:"POST",
			data:dataSubmit,
		}).done(function(resp){
			if (resp.message != ""){
				alert(resp.message);
			}
			if(resp.status == "SUCCESS"){
				$("#btnList").trigger("click");			
			}
		});	

	}

	function calculateTotal(){

	t= $('#tbldetails').DataTable();
	var datas = t.rows().data();

	var total = 0;

	$.each(datas,function(i,data){
		var subttl =  parseFloat(data.fdb_qty * data.fdc_amount);
		total += subttl;			
	});

	$("#ttlAmount").text(App.money_format(total));

	}

</script>

<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>