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
	<h1><?=lang("Request - Pembelian")?><small><?=lang("form")?></small></h1>
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
						<a id="btnPrint" class="btn btn-primary" href="#" title="<?=lang("Cetak")?>"><i class="fa fa-print" aria-hidden="true"></i></a>
						<a id="btnJurnal" class="btn btn-primary" href="#" title="Jurnal" style="display:<?= $mode == "ADD" ? "none" : "none" ?>"><i class="fa fa-align-left" aria-hidden="true"></i></a>
						<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
						<a id="btnClose" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
					</div>
				</div>
				<!-- end box header -->

				<!-- form start -->
				<form id="frmTransaction" class="form-horizontal"  method="POST" enctype="multipart/form-data">			
					<div class="box-body">
						<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">	
						<input type="hidden" class="form-control" id="fin_pr_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_pr_id" value="<?=$fin_pr_id?>" readonly>

						<div class="form-group">
							<label class="col-md-12 control-label" id="fst_publish_status" style="font-style: italic;">#UNPUBLISH</label>
						</div>

                        <div class="form-group">
							<label for="fst_pr_no" class="col-md-2 control-label"><?=lang("No. Request")?> #</label>	
							<div class="col-md-4">				
								<input type="TEXT" id="fst_pr_no" name="fst_pr_no" class="form-control"  value="<?=$fst_pr_no?>" placeholder="PREFIX/BRANCH/YEAR/MONTH/99999" /> 
							</div>

							<label for="fdt_pr_datetime" class="col-md-2 control-label text-right"><?=lang("Tanggal Request")?> *</label>
							<div class="col-md-4">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control text-right datetimepicker" id="fdt_pr_datetime" name="fdt_pr_datetime" value=""/>
								</div>
								<div id="fdt_pr_datetime_err" class="text-danger"></div>
								<!-- /.input group -->
							</div>

						</div>
												
						<div class="form-group">
                            <label for="fin_req_department_id" class="col-md-2 control-label"><?=lang("Request by Department")?> </label>							
                            <div class="col-sm-10">
                                <select id="fin_req_department_id" class="form-control" name="fin_req_department_id">
									<?php
										$departmentList = $this->msdepartments_model->get_departments();
										foreach($departmentList as $department){
											echo "<option value='$department[fin_department_id]'>$department[fst_department_name]</option>";
										}
									?>
								</select>
                            </div>                        
                        </div>	
						<div class="form-group">                            							
							<label for="fin_req_department_id" class="col-md-2 control-label"><?=lang("Memo")?> </label>
							<div class="col-sm-10">
                                <textarea class="form-control" id="fst_memo" placeholder="<?= lang("Memo") ?>" name="fst_memo" rows="3" style="resize:none;width:100%"></textarea>
                                <div id="fst_memo_err" class="text-danger"></div>
							</div> 
						</div>				
						
						<div class="form-group" style="margin-bottom:0px">
							<div class="col-md-12" style="text-align:right">
								<button id="btn-add-detail" class="btn btn-primary btn-sm"><i class="fa fa-cart-plus" aria-hidden="true"></i>Tambah Item</button>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-12">
								<table id="tbldetails" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
							</div>
							<div id="details_err" class="text-danger"></div>
						</div>

                        <div class="form-group">
                            							                            
							<div class="col-sm-6 col-sm-offset-6" style="padding-right:0px">								
								<label class="col-md-8 control-label"  style="padding-top:0px"><?=lang("Total")?> : </label>
								<label id="ttlAmount" class="col-md-4 control-label" style="padding-top:0px" >0.00</label>
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
	<div class="modal-dialog" style="display:table;width:700px">
		<!-- modal content -->
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">			
			<div class="modal-body">
				<div class="row">
                    <div class="col-md-12">
                        <div style="border:0 px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">
				
							<form class="form-horizontal">
									<input type="hidden" id="fin_rec_id" value="0">
									<div class="form-group">
										<label for="fin_item_id" class="col-md-3 control-label">Items</label>
										<div class="col-md-9">
											<select id="fin_item_id" class="form-control " style="width:100%"></select>
										</div>
									</div>
									

									<div class="form-group">
										<label for="fst_unit" class="col-md-3 control-label">Unit</label>
										<div class="col-md-9">
											<select id="fst_unit" class="form-control" style="width:100%"></select>
										</div>
									</div>

									<div class="form-group">
										<label for="fdb_qty" class="col-md-3 control-label">Qty</label>
										<div class="col-md-2">
											<input type="number" class="form-control text-right numeric" id="fdb_qty_req" value="1" min="1">
										</div>

										<label for="fdc_price" class="col-md-4 control-label">Expected Target Data</label>
										<div class="col-md-3">
											<input type="text" class="form-control text-right datepicker" id="fdt_etd" value="" style="text-align: right;">
										</div>
									</div>
									<div class="form-group">
										<label for="fst_memo_d" class="col-md-3 control-label">Memo</label>
										<div class="col-md-9">
											<textarea  class="form-control" id="fst_memo_d" style="resize:none"></textarea>
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
		var selectedItem;
		var mdlDetail = {
			show:function(data){
				mdlDetail.clearForm();

				if (typeof(data) != "undefined"){								
					App.addOptionIfNotExist("<option value='"+data.fin_item_id+"' selected>"+data.fst_item_code + " - " + data.fst_item_name +"</option>","fin_item_id");
					App.addOptionIfNotExist("<option value='"+data.fst_unit+"' selected>"+data.fst_unit +"</option>","fst_unit");
				

					$("#fin_item_id").trigger({
						type: 'select2:select',
						params: {
							data: {
								id:data.fin_item_id,
								text:data.fst_item_code + ' - ' + data.fst_item_name,
								fst_item_code:data.fst_item_code,
								fst_item_name:data.fst_item_name
							}
						}
					});
					
					$("#fdb_qty_req").val(data.fdb_qty_req);
					$("#fst_memo_d").val(data.fst_memo);
					$("#fdt_etd").val(data.fdt_etd);							
				}
					
				$("#mdlDetail").modal("show");				
			},
			hide:function(){
				$("#mdlDetail").modal("hide");
			},
			clearForm:function(){
				$("#fin_item_id").empty();
				$("#fst_unit").empty();
				$("#fdb_qty_req").val(1);
				$("#fdt_etd").val("");
				$("#fst_memo_d").val("");
				selectedItem = null;
			},
			save:function()		{
				t = $("#tbldetails").DataTable();
				
				var data ={
					fin_rec_id:0,
					fin_item_id: $("#fin_item_id").val(),
					fst_item_code:selectedItem.fst_item_code,
					fst_item_name:selectedItem.fst_item_name,
					fst_unit:$("#fst_unit").val(),
					fdb_qty_req:$("#fdb_qty_req").val(),
					fdb_qty_process:0,
					fdb_qty_distribute:0,
					fdt_etd:$("#fdt_etd").val(),
					fst_memo:$("#fst_memo_d").val(),
				}
				
				if(selectedDetail == null){
					//new data					
					t.row.add(data).draw(false);
				}else{
					//edit data
					dataTbl = selectedDetail.data();
					data.fin_rec_id = dataTbl.fin_rec_id;
					data.fdb_qty_process = dataTbl.fdb_qty_process;
					data.fdb_qty_distribute = dataTbl.fdb_qty_distribute;
					selectedDetail.data(data).draw(false);
				}				
				selectedDetail = null;
				mdlDetail.clearForm();
				mdlDetail.hide();				
			}
		};

		$(function(){
			//Form Detail
			$("#fin_item_id").select2({
				minimumInputLength : 2,
				ajax: {
					url: '<?=site_url()?>tr/purchase/purchase_request/get_item_list',
					delay: 250, //milliseconds
					data: function(params){
						return params;
					},
					processResults: function (resp) {
						var itemList = resp.itemList;
						return {
							results: $.map(itemList,function(obj){
								obj.id = obj.fin_item_id;
								obj.text = obj.fst_item_code + " - " + obj.fst_item_name;
								return obj;
							})
						};
					}
				}
			}).on('select2:select', function (e) {
				selectedItem =  e.params.data;
				getItemBuyUnit(null);
			});			
			
			
			$("#btn-add-detail-save").click(function(e){
				e.preventDefault();
				mdlDetail.save();
			})


		});		
	</script>
</div>

<?php echo $mdlEditForm ?>
<?php echo $mdlJurnal ?>

<script type="text/javascript" info="event">
	$(function(){
		
		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url()?>tr/purchase/purchase_request/add";
		});

		$("#btnSubmitAjax").click(function(e){
			e.preventDefault();
			submitAjax(0);
		});

		$("#btnJurnal").click(function(e){
			e.preventDefault();
			MdlJurnal.showJurnalByRef("PRT",$("#fin_purchasereturn_id").val());
		});
		
		$("#btnDelete").click(function(e){
			e.preventDefault();
			deleteAjax(0);
		});
		
		$("#btnClose").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url()?>tr/purchase/purchase_request/";
		});

		
		$("#fin_req_department_id").change(function(e){
			e.preventDefault();
			//console.log($("#fin_req_department_id").val());
			
			getLPBPurchase(function(resp){
				$("#tbldetails").DataTable().clear().draw(false);
				calculateTotal();
			});			
		});

		
		$("#btn-add-detail").click(function(e){
			e.preventDefault();
			mdlDetail.show();			
		});

		
	});
</script>

<script type="text/javascript" info="define">
	var selectedDetail = null;		
	
	$(function(){
		
	});
</script>

<script type="text/javascript" info="init">	

	$(function(){		
		$("#fdt_pr_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");
		
		$('#tbldetails').on('preXhr.dt', function ( e, settings, data ) {
			//add aditional data post on ajax call
			data.sessionId = "TEST SESSION ID";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "fin_rec_id","width": "0px",sortable:false,data:"fin_rec_id",visible:false},
				{"className":'details-control text-center',"defaultContent": '<i class="fa fa-caret-right" aria-hidden="true"></i>',width:"10px",orderable:false},
				{"title" : "Item","width": "250px",sortable:false,
					render:function(data,type,row){
						return row.fst_item_code + " - " +row.fst_item_name;
					}
				},
				{"title" : "Unit","width": "50px",sortable:false,data:"fst_unit"},
				{"title" : "Requset","width": "100px",sortable:false,className:'text-right',data:"fdb_qty_req"},
				{"title" : "Process","width": "100px",sortable:false,className:'text-right',data:"fdb_qty_process"},
				{"title" : "Distribute","width": "100px",sortable:false,className:'text-right',data:"fdb_qty_distribute"},
				{"title" : "ETD",data:"fdt_etd","width": "100px",sortable:false,className:'text-right'},
				{"title" : "Action","width": "75px",sortable:false,className:'text-center',
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
		}).on('draw',function(){
			$(".dataTables_scrollHeadInner").css("min-width","100%");
			$(".dataTables_scrollHeadInner > table").css("min-width","100%");
			calculateTotal();
		}).on('click','.btn-edit',function(e){
			e.preventDefault();
			t = $("#tbldetails").DataTable();
			var trRow = $(this).parents('tr');			
			selectedDetail = t.row(trRow);
			var data = selectedDetail.data();	
			mdlDetail.show(data);

			
		}).on('click','.btn-delete',function(e){
			e.preventDefault();
			t = $('#tbldetails').DataTable();
			var trRow = $(this).parents('tr');
			t.row(trRow).remove().draw();			
		}).on("click",".details-control",function(e){
            e.preventDefault();
            t = $('#tbldetails').DataTable();
            var tr = $(this).closest('tr');
            var row = t.row( tr );
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }else {
                // Open this row
                row.child(sub_trpurchaserequestitems(row.data()) ).show();
                tr.addClass('shown');
            } 
        });
		
		App.fixedSelect2();

		initForm();
	});


</script>

<script type="text/javascript" info="function">
	
	function sub_trpurchaserequestitems(data){
		return "<div>"+data.fst_memo+"</div>";
	}

	function calculateTotal(){

		t= $('#tbldetails').DataTable();
		var datas = t.rows().data();
		
		var total = 0;
		var totalDisc = 0;
		
		$.each(datas,function(i,data){
			var subttl =  parseFloat(data.fdb_qty * data.fdc_price);
			var discAmount = parseFloat(data.fdb_qty * data.fdc_disc_amount_per_item);
			total += subttl;
			totalDisc += discAmount;			
		});

		$("#ttlSubTotal").text(App.money_format(total));
		$("#ttlDisc").text(App.money_format(totalDisc));

		var ppnPercent = $("#fdc_ppn_percent").val();
		var ttlBeforePPn = total - totalDisc
		var ppnAmount = ttlBeforePPn * ppnPercent / 100;
		var totalAfterPPn = ttlBeforePPn + ppnAmount;

		$("#ppnAmount").text(App.money_format(ppnAmount));
		$("#ttlAmount").text(App.money_format(totalAfterPPn));

	}	

	function getItemBuyUnit(defaultValue){
		App.getValueAjax({
			site_url:"<?= site_url()?>",
			model:"msitemunitdetails_model",
			func:"getBuyingListUnit",
			params:[$("#fin_item_id").val()],
			callback:function(units){

				var fstUnit = $("#fst_unit").val();

				$("#fst_unit").empty();
				$.each(units,function(i,unit){
					$("#fst_unit").append("<option value='" +unit.fst_unit + "'>"+unit.fst_unit+"</option>");
				});
				$("#fst_unit").val(fstUnit).trigger("change");				
			}
		});

	}

	

	function submitAjax(confirmEdit){

		var dataSubmit = $("#frmTransaction").serializeArray();
		
		var mode = $("#fin_pr_id").val() == "0" ? "ADD" : "EDIT";	

		if (mode == "ADD"){
			url =  "<?= site_url() ?>tr/purchase/purchase_request/ajx_add_save/";
		}else{
			dataSubmit.push({
				name : "fin_user_id_request_by",
				value: MdlEditForm.user
			});
			dataSubmit.push({
				name : "fst_edit_notes",
				value: MdlEditForm.notes
			});

			url =  "<?= site_url() ?>tr/purchase/purchase_request/ajx_edit_save/";
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
			//enctype: 'multipart/form-data',
			url: url,
			data: dataSubmit,
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
					$("#fin_po_id").val(data.insert_id);
					//Clear all previous error
					$(".text-danger").html("");					
				}
			},
			error: function (e) {
				$("#result").text(e.responseText);
				$("#btnSubmit").prop("disabled", false);
			},
		}).always(function(){
			
		});
	}

	function initForm(){
			
		var finPRId = $("#fin_pr_id").val();
		if (finPRId != 0){
			//get data from server;
			App.blockUIOnAjaxRequest();
			$.ajax({
				url:"<?=site_url()?>tr/purchase/purchase_request/fetch_data/" + finPRId,
				method:"GET",								
			}).done(function(resp){
				if(resp.message != ""){
					alert(resp.message);
				}

				if (resp.status == "SUCCESS"){	
					
					dataH = resp.data.dataH;
					dataDetails = resp.data.dataDetails;					
					App.autoFillForm(dataH);
					
					$("#fdt_pr_datetime").val(App.dateTimeFormat(dataH.fdt_pr_datetime)).datetimepicker("update");
					$("#fin_req_department_id").val(dataH.fin_req_department_id);

					if (dataH.fdt_publish_datetime == null){
						$("#fst_publish_status").text("#UNPUBLISH");
					}else{
						$("#fst_publish_status").text("#PUBLISH");
					}

					
					t = $("#tbldetails").DataTable();					
					$.each(dataDetails , function(i,dataD){						
						var data ={
							fin_rec_id:dataD.fin_rec_id,
							fin_item_id:dataD.fin_item_id,
							fst_item_code:dataD.fst_item_code,
							fst_item_name:dataD.fst_item_name,
							fst_unit:dataD.fst_unit,
							fdb_qty_req:dataD.fdb_qty_req,
							fdb_qty_process:dataD.fdb_qty_process,
							fdb_qty_distribute:dataD.fdb_qty_distribute,
							fdt_etd:App.dateFormat(dataD.fdt_etd),
							fst_memo:dataD.fst_memo
						}
						
						t.row.add(data);						
					});									
					t.draw(false);
					
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

		var url =  "<?= site_url() ?>tr/purchase/purchase_request/delete/" + $("#fin_pr_id").val();
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