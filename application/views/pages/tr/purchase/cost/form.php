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
	<h1><?=lang("Pembelian - Biaya")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Pembelian") ?></a></li>
		<li class="active title"><?=$title?></li>
	</ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title title pull-left"><?=$title?></h3>
					<div class="btn-group btn-group-sm  pull-right">					
						<a id="btnNew" class="btn btn-primary" href="#" title="<?=lang("Tambah Baru")?>"><i class="fa fa-plus" aria-hidden="true"></i></a>
						<a id="btnSubmitAjax" class="btn btn-primary" href="#" title="<?=lang("Simpan")?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
						<a id="btnPrint" class="btn btn-primary" href="#" title="<?=lang("Cetak")?>" style="display:<?= $mode == "ADD" ? "none" : "inline-block" ?>"><i class="fa fa-print" aria-hidden="true"></i></a>
						<a id="btnJurnal" class="btn btn-primary" href="#" title="<?=lang("Jurnal")?>" style="display:<?= $mode == "ADD" ? "none" : "inline-block" ?>"><i class="fa fa-align-left" aria-hidden="true"></i></a>
						<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>" style="display:<?= $mode == "ADD" ? "none" : "inline-block" ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
						<a id="btnClose" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
					</div>
				</div>
				<!-- end box header -->

				<!-- form start -->
				<form id="frmTransaction" class="form-horizontal"  method="POST" enctype="multipart/form-data">			
					<div class="box-body">
						<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">	
						<input type="hidden" class="form-control" id="fin_purchasecost_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_purchasecost_id" value="<?=$fin_purchasecost_id?>" readonly>

						<div class="form-group">
							<label for="fst_delivery_address" class="col-md-2 control-label"></label>
							<div class="col-md-10">								
								<label class="radio-inline"><input type="radio" id="fblIsImportFalse" class="calc_ttl fbl_is_import" name="fbl_is_import" value="0" checked="">Lokal</label>
								<label class="radio-inline"><input type="radio" id="fblIsImportTrue" class="calc_ttl fbl_is_import" name="fbl_is_import" value="1">Import</label>
							</div>
						</div>

                        <div class="form-group">
							<label for="fst_purchasecost_no" class="col-md-2 control-label"><?=lang("No. Memo Biaya")?></label>	
							<div class="col-md-4">				
								<input type="TEXT" id="fst_purchasecost_no" name="fst_purchasecost_no" class="form-control"  value="<?=$fst_purchasecost_no?>" placeholder="PREFIX/BRANCH/YEAR/MONTH/99999" /> 
							</div>

							<label for="fdt_purchasecost_datetime" class="col-md-2 control-label text-right"><?=lang("Tanggal")?> *</label>
							<div class="col-md-4">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control text-right datetimepicker" id="fdt_purchasecost_datetime" name="fdt_purchasecost_datetime" value=""/>
								</div>
								<div id="fdt_purchasecost_datetime_err" class="text-danger"></div>
								<!-- /.input group -->
							</div>

						</div>
						
						<div id="divPO" class="form-group">
                            <label for="fin_po_id" class="col-md-2 control-label"><?=lang("Purchase Order")?> </label>							
                            <div class="col-sm-10">
								<select id="fin_po_id" class="form-control" name="fin_po_id" style="width:100%"></select>                                
							</div>							                    
						</div>


						<div class="form-group">
                            <label for="fin_vendor_id" class="col-md-2 control-label"><?=lang("Supplier")?> </label>							
                            <div class="col-sm-10">
                                <select id="fin_supplier_id" class="form-control" name="fin_supplier_id">
									<?php
										$supplierList = $this->msrelations_model->getSupplierList();
										foreach($supplierList as $supplier){
											echo "<option value='$supplier->fin_relation_id'>$supplier->fst_relation_name</option>";
										}
									?>
								</select>
                            </div>                        
                        </div>
						
												
						<div class="form-group">
							<label for="fst_curr_code" class="col-md-2 control-label"><?=lang("Mata Uang")?> </label>
                            <div class="col-sm-2">
								<select id="fst_curr_code" type="TEXT" class="calc_ttl form-control" name="fst_curr_code">
									<?php foreach($arrExchangeRate as $key=>$value){
										echo "<option value='$key'>$key</option>";  
									}
									?>
								</select>
							</div>										                    
							<label for="fdc_exchange_rate_idr" class="col-md-2 control-label"><?=lang("Nilai Tukar IDR")?> </label>
                            <div class="col-sm-2">
								<input type="TEXT" id="fdc_exchange_rate_idr"  name="fdc_exchange_rate_idr" class="calc_ttl form-control money"/>
								<div id="fdc_exchange_rate_idr_err" class="text-danger"></div>
							</div>										                    							
						</div>
						
					


						<div class="form-group" style="margin-bottom:0px">
							<div class="col-md-12" style="text-align:right">
								<button id="btn-add-detail" class="btn btn-primary btn-sm"><i class="fa fa-cart-plus" aria-hidden="true"></i>Tambah Biaya</button>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-12">
								<table id="tbldetails" class="table table-bordered table-hover table-striped row-border compact nowarp " style="min-width:100%"></table>
							</div>
							<div id="details_err" class="text-danger"></div>
						</div>

                        <div class="form-group">
                            							
                            <div class="col-sm-6">
								<label for="fst_memo" class=""><?=lang("Memo")?> </label>
                                <textarea class="form-control" id="fst_memo" placeholder="<?= lang("Memo") ?>" name="fst_memo" rows="3" style="resize:none;width:100%"></textarea>
                                <div id="fst_memo_err" class="text-danger"></div>
							</div> 

							<div class="col-sm-6" style="padding-right:0px">
								<label class="col-md-8 control-label" style="padding-top:0px"><?=lang("Total")?> : </label>
								<label id="totalAmount" class="col-md-4 control-label" style="padding-top:0px">0.00</label>
								
								
								<label class="col-md-8 control-label"  style="padding-top:0px"><?=lang("Total IDR")?> : </label>
								<label id="totalAmountIdr" class="col-md-4 control-label" style="padding-top:0px" >0.00</label>
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

<div id="mdlDetail" class="modal fade" role="dialog">
	<div class="modal-dialog" style="display:table;width:850px">
		<!-- modal content -->
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">			
			<div class="modal-body">
				<div class="row">
                    <div class="col-md-12">
                        <div style="border:0 px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">
				
							<form id="form-detail" class="form-horizontal">
									<input type="hidden" id="fin_po_detail_id" value="0">
									<div class="form-group">
										<label for="fst_glaccount_code" class="col-md-3 control-label">GL Account</label>
										<div class="col-md-9">
											<select id="fst_glaccount_code" class="form-control " style="width:100%">
											<?php
												//$costList = $this->glaccounts_model->getAccountRabaRugi();
												$costList = $this->glaccounts_model->getAccountAll();
												foreach($costList as $cost){
													echo "<option value='".$cost->fst_glaccount_code ."' data-pcdiv='$cost->fbl_pc_divisi' data-pccust='$cost->fbl_pc_customer' data-pcproj='$cost->fbl_pc_project' >". $cost->fst_glaccount_code . " - " . $cost->fst_glaccount_name . "</option>";
												}
											?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="fst_notes" class="col-md-3 control-label">Notes</label>
										<div class="col-md-9">
											<textarea id="fst_notes" class="form-control" style="resize:none"></textarea>
										</div>
									</div>

									<div class="form-group">
										<label for="fin_pcc_id" class="col-md-3 control-label"><?=lang("Profit & Cost Center")?></label>
										<div class="col-md-3">
											<select id="fin_pcc_id" class="form-control" style="width:100%">
												<?php
													$pccList = $this->profitcostcenter_model->getAllList();
													foreach($pccList as $pcc){
														echo "<option value='".$pcc->fin_pcc_id ."'>". $pcc->fst_pcc_name . "</option>";
													}
												?>
											</select>
										</div>
										<label for="fin_pc_divisi_id" class="col-md-3 control-label"><?=lang("Analisa Department")?></label>
										<div class="col-md-3">
											<select id="fin_pc_divisi_id" class="form-control" style="width:100%">
												<?php
													$departmentList = $this->msdepartments_model->getAllList();
													foreach($departmentList as $department){
														echo "<option value='".$department->fin_department_id ."'>". $department->fst_department_name . "</option>";
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label for="fin_pc_customer_id" class="col-md-3 control-label"><?=lang("Analisa Customer")?></label>
										<div class="col-md-3">
											<select id="fin_pc_customer_id" class="form-control" style="width:100%">
												<?php
													$customerList = $this->msrelations_model->getCustomerListByBranch();
													foreach($customerList as $customer){
														echo "<option value='".$customer->fin_relation_id ."'>". $customer->fst_relation_name . "</option>";
													}
												?>
											</select>
										</div>
										<label for="fin_pc_project_id" class="col-md-3 control-label"><?=lang("Analisa Project")?></label>
										<div class="col-md-3">
											<select id="fin_pc_project_id" class="form-control" style="width:100%">
												<?php
													$projectList = $this->msprojects_model->getAllList();
													foreach($projectList as $project){
														echo "<option value='".$project->fin_project_id ."'>". $project->fst_project_name . "</option>";
													}
												?>
											</select>
										</div>
									</div>
									

									<div class="form-group">
										<label for="fdc_debet" class="col-md-3 control-label">Debet</label>
										<div class="col-md-3">
											<input type="text" class="form-control text-right money" id="fdc_debet" value="0">
										</div>

										<label for="fdc_credit" class="col-md-3 control-label">Credit</label>
										<div class="col-md-3">
											<input type="text" class="form-control text-right money" id="fdc_credit" value="0">
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
	<script type="text/javascript">
		$(function(){
			$("#fst_glaccount_code").select2();
			$("#fdc_debet").change(function(e){
				e.preventDefault();
				$("#fdc_credit").val(App.money_format(0));
			});
			$("#fdc_credit").change(function(e){
				e.preventDefault();
				$("#fdc_debet").val(App.money_format(0));
			});

			$("#btn-add-detail-save").click(function(e){
				e.preventDefault();
				var error = false;

				//Validasi data
				var selectedGL = $("#fst_glaccount_code option:selected");
				
				if ($("#fst_glaccount_code").val() == null){
					alert("<?=lang("GL Account tidak boleh kosong !")?>");
					error = true;
				};

				if ($("#fin_pcc_id").val() == null){
					alert("<?=lang("Profit Cost Center harus diisi untuk GL Account code ini ")?>");
					error = true;
				}
				
				var isPCDiv = selectedGL.data("pcdiv");
				var isPCCust = selectedGL.data("pccust");
				var isPCProj = selectedGL.data("pcproj");
				
				if(isPCDiv == 1){
					if ($("#fin_pc_divisi_id").val() == null){
						alert("<?=lang("Analisa Divisi harus diisi untuk GL Account code ini ")?>");
						error = true;
					}
				}
				if(isPCCust == 1){
					if ($("#fin_pc_customer_id").val() == null){
						alert("<?=lang("Analisa Customer harus diisi untuk GL Account code ini ")?>")
						error = true;
					}

				}
				if(isPCProj == 1){ 
					if ($("#fin_pc_project_id").val() == null){
						alert("<?=lang("Analisa Project harus diisi untuk GL Account code ini ")?>")
						error = true;
					}
				}

				if ($("#fdc_debet").val() == 0 && $("#fdc_credit").val() == 0){
					alert("<?=lang("Nilai Debet dan Kredit tidak boleh kosong !")?>")
					error = true;
				}

				if (error){
					return;
				}

				t = $("#tbldetails").DataTable();
				var data ={
					fin_rec_id:0,
					fst_glaccount_code:$("#fst_glaccount_code").val(),
					fst_glaccount_title:$("#fst_glaccount_code option:selected").text(),
					fst_notes:$("#fst_notes").val(),
					fin_pcc_id:$("#fin_pcc_id").val(),
					fst_pcc_title:$("#fin_pcc_id option:selected").text(),
					fin_pc_divisi_id:$("#fin_pc_divisi_id").val(),
					fst_pc_divisi_title:$("#fin_pc_divisi_id option:selected").text(),
					fin_pc_customer_id:$("#fin_pc_customer_id").val(),
					fst_pc_customer_name:$("#fin_pc_customer_id option:selected").text(),
					fin_pc_project_id:$("#fin_pc_project_id").val(),
					fst_pc_project_title:$("#fin_pc_project_id option:selected").text(),
					fdc_debet:$("#fdc_debet").val(),
					fdc_credit:$("#fdc_credit").val(),
				};
				if (rowDetail == null){
					t.row.add(data).draw(false);
				}else{
					var tmpData = t.row(rowDetail).data();
					data.fin_rec_id = tmpData.fin_rec_id;					
					t.row(rowDetail).data(data).draw(false);
				}				
				calculateTotal();
				$("#mdlDetail").modal("hide");				
				rowDetail = null;
			})
		})
	
	</script>
</div>

<?php echo $mdlEditForm ?>
<?php echo $mdlJurnal ?>
<?php echo $mdlPrint ?>

<script type="text/javascript" info="event">
	$(function(){
		
		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url()?>tr/purchase/cost/add";
		});

		$("#btnSubmitAjax").click(function(e){
			e.preventDefault();
			submitAjax(0);
		});
		$("#btnPrint").click(function(e){
			e.preventDefault();
			frameVoucher.print("<?=site_url()?>tr/purchase/cost/print_voucher/" + $("#fin_purchasecost_id").val());
		})

		$("#btnJurnal").click(function(e){
			e.preventDefault();
			MdlJurnal.showJurnalByRef("PCS",$("#fin_purchasecost_id").val());
		});
		
		$("#btnDelete").click(function(e){
			e.preventDefault();
			deleteAjax(0);
		});
		
		$("#btnClose").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url()?>tr/purchase/cost/";
		});
	
		$(".fbl_is_import").change(function(e){
			e.preventDefault();
			getPOList(function(resp){
			});

			var isImport = $(".fbl_is_import:checked").val();
			if (isImport == 1){
				$("#fst_curr_code").prop("disabled",false);
			}else{
				$("#fst_curr_code").prop("disabled",true);
				$("#fst_curr_code").val("IDR");
				$("#fdc_exchange_rate_idr").val(App.money_parse(1));
			}

		});

		$("#fst_curr_code").change(function(e){
			e.preventDefault();
			var exchangeRate = arrExchangeRate[$("#fst_curr_code").val()];
			$("#fdc_exchange_rate_idr").val(App.money_format(exchangeRate));
		});

		$(".calc_ttl").change(function(e){
			calculateTotal();
		});


		$("#btn-add-detail").click(function(e){
			e.preventDefault();
			clearDetailForm();
			$("#mdlDetail").modal("show");
		});
	});
</script>

<script type="text/javascript" info="define">
	var nonFaktur;
	var rowDetail = null;
	var arrExchangeRate =  new Array();
	<?php foreach($arrExchangeRate as $key=>$value){ ?>		
		arrExchangeRate["<?=$key?>"] = "<?= $value->fdc_rate ?>";
	<?php }	?>
	
	$(function(){
		$('#tbldetails').on('preXhr.dt', function ( e, settings, data ) {
			//add aditional data post on ajax call
			data.sessionId = "TEST SESSION ID";
		}).on('init.dt',function(){
			$(".dataTables_scrollHeadInner").css("min-width","100%");
			$(".dataTables_scrollHeadInner > table").css("min-width","100%");
			$(".dataTables_scrollBody").css("position","static");
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[				
				{"title" : "fin_rec_id","width": "0px",sortable:false,data:"fin_rec_id",visible:false},
				{"title" : "GL Account","width": "300px",sortable:false,data:"fst_glaccount_code",
					render:function(data,type,row){
						return row.fst_glaccount_title
					}
				},
				{"title" : "Notes","width": "300px",sortable:false,data:"fst_notes"},
				{"title" : "Profit & Cost Center","width": "250px",sortable:false,data:"fin_pcc_id",visible:false,
					render:function(data,type,row){
						return row.fst_pcc_title
					}
				},
				{"title" : "Analisa Divisi","width": "250px",sortable:false,data:"fin_pc_divisi_id",visible:false,
					render:function(data,type,row){
						return row.fst_pc_divisi_title
					}
				},
				{"title" : "Analisa Customer","width": "250px",sortable:false,data:"fin_pc_customer_id",visible:false,
					render:function(data,type,row){
						return row.fst_pc_customer_name
					}
				},
				{"title" : "Analisa Project","width": "250px",sortable:false,data:"fin_pc_project_id",visible:false,
					render:function(data,type,row){
						return row.fst_pc_project_title
					}
				},
				{"title" : "Debet","width": "125px",sortable:false,data:"fdc_debet",className:"text-right",
					render:function(data,type,row){
						return App.money_format(data);
					}
				},
				{"title" : "Credit","width": "125px",sortable:false,data:"fdc_credit",className:"text-right",
					render:function(data,type,row){
						return App.money_format(data);
					}
				},
				{"title" : "Action","width": "100px",sortable:false,className:'text-center',
					render:function(data,type,row){
						var action = '<a class="btn-edit" href="#" data-original-title="" title=""><i class="fa fa-pencil"></i></a>';
						action += '<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
						return action;
					}
				}
			],
			processing: true,
			serverSide: false,
			searching: false,
			lengthChange: false,
			paging: false,
			info:false,
		}).on('click','.btn-edit',function(e){
			e.preventDefault();
			clearDetailForm();

			t = $("#tbldetails").DataTable();
			rowDetail = $(this).parents('tr');			 
			var data = t.row(rowDetail).data();


			$("#fst_glaccount_code").val(data.fst_glaccount_code).trigger("change.select2");
			$("#fst_notes").val(data.fst_notes);
			$("#fin_pcc_id").val(data.fin_pcc_id);
			$("#fin_pc_divisi_id").val(data.fin_pc_divisi_id);
			$("#fin_pc_customer_id").val(data.fin_pc_customer_id);
			$("#fin_pc_project_id").val(data.fin_pc_project_id);

			$("#fdc_debet").val(data.fdc_debet);
			$("#fdc_credit").val(data.fdc_credit);
			$("#mdlDetail").modal("show");
			
		}).on('click','.btn-delete',function(e){
			e.preventDefault();
			t = $('#tbldetails').DataTable();
			var trRow = $(this).parents('tr');
			t.row(trRow).remove().draw();
			calculateTotal();
		});

	});
</script>

<script type="text/javascript" info="init">
	function testSelec(val){		
		return $('<label style="width:100%">'+ val.text +'</label>');
	}

	function formatPO(val){
		var sstrElement = "<span style='display:inline-block;width:150px'>" + val.text + "</span>";
		sstrElement += "<span style='display:inline-block;width:150px'>" + $("#fin_po_id option[value='" + val.id +"']").data("total") +"</span>";
		sstrElement += "<span>"+ $("#fin_po_id option[value='" + val.id +"']").data("supplier_name") +"</span>";
		return $(sstrElement);
	}

	$(function(){
		$("#fdt_purchasecost_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");

		$("#fin_po_id").select2({templateResult:formatPO});

		$("#fin_supplier_id").select2({templateResult:testSelec});
		$("#fin_supplier_id").val(null).trigger("change.select2");		
		$(".fbl_is_import").trigger("change");
		App.fixedSelect2();
		initForm();
	});


</script>

<script type="text/javascript" info="function">
	function getPOList(callback){
		var isImport = $(".fbl_is_import:checked").val();
		App.getValueAjax({
			site_url:"<?=site_url()?>",
			model:"trpurchasecost_model",
			func:"getListPO",
			params:[isImport],
			callback:function(resp){
				var poList = resp;
				$("#fin_po_id").empty();
				$.each(poList ,function(i,v){
					var total = parseFloat(v.fdc_subttl) - parseFloat(v.fdc_disc_amount) + parseFloat(v.fdc_ppn_amount);
					total =App.money_format(total);
					$("#fin_po_id").append("<option value='"+v.fin_po_id+"' data-total='" + v.fst_curr_code +':' + total +"' data-supplier_name='"+v.fst_supplier_name+"'>"+ v.fst_po_no +"</option>")
				});
				$("#fin_po_id").val(null);
				callback(resp);
			}
		});
	}
	function calculateTotal(){

		t= $('#tbldetails').DataTable();
		var datas = t.rows().data();
		var totalDebet = 0;
		var totalCredit = 0;
		
		var totalIDR = 0;

		$.each(datas,function(i,data){
			totalDebet +=  parseFloat(data.fdc_debet);
			totalCredit +=  parseFloat(data.fdc_credit);
		});
		var total = totalDebet - totalCredit;
		$("#totalAmount").text(App.money_format(total));
		$("#totalAmountIdr").text(App.money_format(total * $("#fdc_exchange_rate_idr").val() ));

	}
	function clearDetailForm(){
		$("#fst_glaccount_code").val(null).trigger("change.select2");
		$("#fst_notes").val(null);
		$("#fin_pcc_id").val(null);
		$("#fin_pc_divisi_id").val(null);
		$("#fin_pc_customer_id").val(null);
		$("#fin_pc_project_id").val(null);

		$("#fdc_debet").val(App.money_format(0));
		$("#fdc_credit").val(App.money_format(0));
	}

	function submitAjax(confirmEdit){

		var dataSubmit = $("#frmTransaction").serializeArray();

		var mode = $("#fin_purchasecost_id").val() == "0" ? "ADD" : "EDIT";	

		if (mode == "ADD"){
			url =  "<?= site_url() ?>tr/purchase/cost/ajx_add_save/";
		}else{
			dataSubmit.push({
				name : "fin_user_id_request_by",
				value: MdlEditForm.user
			});
			dataSubmit.push({
				name : "fst_edit_notes",
				value: MdlEditForm.notes
			});

			url =  "<?= site_url() ?>tr/purchase/cost/ajx_edit_save/";
		}

		if (confirmEdit == 0 && mode != "ADD"){
			MdlEditForm.saveCallBack = function(){
				submitAjax(1);
			};		
			MdlEditForm.show();
			return;
		}

		var details = [];		
		var datas =$("#tbldetails").DataTable().data();		
		$.each(datas,function(i,v){
			details.push(v);
		});

		dataSubmit.push({
			name:"details",
			value: JSON.stringify(details)
		});

		App.blockUIOnAjaxRequest("Please wait while saving data.....");
		$.ajax({
			type: "POST",
			url: url,
			data: dataSubmit,
			timeout: 600000,			
			error: function (e) {
				$("#result").text(e.responseText);
				$("#btnSubmit").prop("disabled", false);
			},
		}).done(function (resp) {				
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
				$("#fin_purchasecost_id").val(data.insert_id);
				//Clear all previous error
				$(".text-danger").html("");					
			}
		}).always(function(){
			
		});
	}

	function initForm(){
		var finPurchaseCostId = $("#fin_purchasecost_id").val();
		if (finPurchaseCostId != 0){
			//get data from server;
			App.blockUIOnAjaxRequest();
			$.ajax({
				url:"<?=site_url()?>tr/purchase/cost/fetch_data/" + finPurchaseCostId,
				method:"GET",								
			}).done(function(resp){
				if(resp.message != ""){
					alert(resp.message);
				}

				if (resp.status == "SUCCESS"){						
					dataH = resp.data.dataH;					
					detailList = resp.data.detailList;
					
					App.autoFillForm(dataH);

					
					$(".fbl_is_import [value='" + dataH.fbl_is_import +"']").prop("checked",true);
					var isImport = $(".fbl_is_import:checked").val();
					if (isImport == 1){
						$("#fst_curr_code").prop("disabled",false);
					}else{
						$("#fst_curr_code").prop("disabled",true);
						//$("#fst_curr_code").val("IDR");
						//$("#fdc_exchange_rate_idr").val(App.money_parse(1));
					}
					$("#fst_curr_code").val(dataH.fst_curr_code);

					//$(".fbl_is_import").trigger("change");

					$("#fdt_purchasecost_datetime").val(App.dateTimeFormat(dataH.fdt_purchasecost_datetime)).datetimepicker("update");
					$("#fin_supplier_id").val(dataH.fin_supplier_id).trigger("change.select2");


					getPOList(function(resp){
						App.addOptionIfNotExist("<option value='"+dataH.fin_po_id+"'>"+dataH.fst_po_no+"</option>","fin_po_id");
						$("#fin_po_id").val(dataH.fin_po_id).trigger("change.select2");
					});
					


					t = $("#tbldetails").DataTable();					
					$.each(detailList , function(i,dataD){
						var data ={
							fin_rec_id:dataD.fin_rec_id,
							fst_glaccount_code: dataD.fst_glaccount_code,
							fst_glaccount_title: dataD.fst_glaccount_code + " - " + dataD.fst_glaccount_name,
							fst_notes:dataD.fst_notes,
							fin_pcc_id:dataD.fin_pcc_id,
							fst_pcc_title:dataD.fst_pcc_name,
							fin_pc_divisi_id:dataD.fin_pc_divisi_id,
							fst_pc_divisi_title:"",
							fin_pc_customer_id:dataD.fin_pc_customer_id,
							fst_pc_customer_name:"",
							fin_pc_project_id:dataD.fin_pc_project_id,
							fst_pc_project_title:"",
							fdc_debet:dataD.fdc_debet,
							fdc_credit:dataD.fdc_credit,
						};
						t.row.add(data);						
					});									
					t.draw(false);
					calculateTotal();
				}else{
					$("#btnNew").trigger("click");
				}
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

		var url =  "<?= site_url() ?>tr/purchase/cost/delete/" + $("#fin_purchasecost_id").val();
		$.ajax({
			url:url,
			method:"POST",
			data:dataSubmit,
		}).done(function(resp){
			if (resp.message != ""){
				alert(resp.message);
			}
			if(resp.status == "SUCCESS"){
				$("#btnClose").trigger("click");			
			}

		});	

	}

</script>

<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>