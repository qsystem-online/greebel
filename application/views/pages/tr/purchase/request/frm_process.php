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
	<h1><?=lang("Proses PR")?><small><?=lang("form")?></small></h1>
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
					<div class="btn-group btn-group-sm  pull-right " >					
						<a id="btnNew" class="btn btn-primary" href="#" title="<?=lang("Tambah Baru")?>"><i class="fa fa-plus" aria-hidden="true"></i></a>
						<a id="btnClose" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
					</div>
				</div>
				<!-- end box header -->
				<div class="box-body">
					<ul class="nav nav-tabs" style="margin:5px">
						<li class="active"><a data-toggle="tab" href="#process">Process PR</a></li>
						<li><a data-toggle="tab" href="#history">Process History</a></li>
					</ul>
					<div class="tab-content">
						<div id="process" class="tab-pane fade in active">
							<!-- form start -->
							<form id="frmTransaction" class="form-horizontal"  method="POST" enctype="multipart/form-data">			
								
								<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">	
								
								
								<div class="form-group">
									<label for="fst_item_type" class="col-md-2 control-label"><?=lang("Tipe Barang")?> #</label>	
									<div class="col-md-3">				
										<select id="fst_item_type" class="cls-filter form-control">
											<option value='MERCHANDISE'>Merchandise</option>
											<option value='LOGISTIC'>Logistik</option>
										</select>
									</div>

									<label for="fin_linebusinness_id" class="col-md-2 control-label text-right"><?=lang("Line Bisnis")?> *</label>
									<div class="col-md-5">
										<select id="fin_linebusinness_id" class="cls-filter form-control">
											<?php
												$lineBusinessList = $this->mslinebusiness_model->get_data_linebusiness();
												foreach($lineBusinessList as $lineBusiness){
													echo "<option value='$lineBusiness->fin_linebusiness_id'>$lineBusiness->fst_linebusiness_name</option>";
												}
											?>
										</select>
									</div>

								</div>
														
								
								<div class="form-group" style="margin-bottom:0px">
									<div class="col-md-10 col-md-offset-2" >
										<label class='radio-inline control-label'><input type="radio" name="cost_type" class='cost_type cls-filter' value='stock' checked/> Stock</label>
										<label class='radio-inline'><input type="radio" name="cost_type" class='cost_type cls-filter' value='nonstock_umum' /> Non Stock Biaya Umum</label>
										<label class='radio-inline'><input type="radio" name="cost_type" class='cost_type cls-filter' value='nonstock_pabrikasi' /> Non Stock Biaya Pabrikasi</label>

										<!-- <button id="btn-add-detail" class="btn btn-primary btn-sm" style="display:inline"><i class="fa fa-cart-plus" aria-hidden="true"></i>Tambah Item</button> -->

									</div>
								</div>

								<div class="form-group">
									<div class="col-sm-12">
										<table id="tbldetails" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
									</div>
									<div id="details_err" class="text-danger"></div>
								</div>

								<div class="form-group">
									<label class="col-md-1 control-label">Suplier</label>
									<div class="col-md-5">
										<select id="fin_supplier_id" class="form-control"></select>
									</div>
									<div class="col-md-2">
										<button id="btn-process" type="button" class="btn btn-default btn-sm text-center form-control"><?= lang("Process to PO")?></button>
									</div>
								</div>																
							</form>
						</div>
						<div id="history" class="tab-pane fade">
							<form id="frmHistory" class="form-horizontal">
								<div class="form-group">
									<label for="fst_item_type" class="col-md-2 control-label"><?=lang("PR Range Date")?> #</label>	
									<div class="col-md-3">														
									</div>
								</div>
							</form>
							<div style="margin:10px">							
								<table id="tblHist" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
							</div>
						</div>
					</div>
				</div><!-- end box body -->		

				<div class="box-footer text-right">
					<!-- <a id="btnSubmitAjaxOld" href="#" class="btn btn-primary"><=lang("Save Ajax")?></a> -->
				</div><!-- end box-footer -->
        	</div>
    	</div>
	</div>
</section>

<div id="mdlDetail" class="modal fade" role="dialog">
	<div class="modal-dialog" style="display:table;width:900px;min-height:1800px">
		<!-- modal content -->
		<div class="modal-content" style="border-radius:5px;">			
			<div class="modal-body">				
				<div class="row">
                    <div class="col-md-12">
						<table id="tblHistory" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		var selectedItem;
		var mdlDetail = {
			show:function(data){
				$('#tblHistory').DataTable().draw(false);
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
			},
			getHistory:function(){

			}
			
		};

		$(function(){
			$('#tblHistory').on('preXhr.dt', function ( e, settings, data ) {
				//add aditional data post on ajax call
				//data.sessionId = "TEST SESSION ID";
				if (selectedDetail != null){
					//var dataTab = selectedDetail.data();
					//data.fin_item_id =  dataTab.fin_item_id;
					//data.fst_unit = dataTab.fst_unit;
				}else{
					//e.preventDefault();
					//settings.jqXHR.abort();
					//return null;
				}
			}).DataTable({
				scrollY: "300px",
				scrollX: true,			
				scrollCollapse: true,	
				order: [],
				preDrawCallback: function( settings ) {					
					if (selectedDetail == null){
						return false;
					}				
				},
				columns:[				
					//{"className":'details-control text-center',"defaultContent": '<i class="fa fa-caret-right" aria-hidden="true"></i>',width:"10px",orderable:false},
					{"title" : "Order No","width": "150px",sortable:false,data:"fst_po_no"},
					{"title" : "Order Datetime","width": "120px",sortable:false,data:"fdt_po_datetime"},
					{"title" : "Qty","width": "50px",sortable:false,data:"fdb_qty"},
					{"title" : "Price","width": "75px",sortable:true,className:'text-right',data:"fdc_price"},
					{"title" : "Supplier",sortable:false,sortable:true,className:'text-right',data:"fin_supplier_id",
						render:function(data,type,row){
							return row.fst_supplier_name;
						}
					},				
				],
				order:[
					[ 2, "desc" ]
				],
				processing: true,
				serverSide: true,
				searching: true,
				lengthChange: false,
				ajax: {
					url:SITE_URL + "tr/purchase/purchase_request/fetch_history_list_data",
					data:function(d){
						if (selectedDetail != null){
							var dataTab = selectedDetail.data();
							d.fin_item_id =  dataTab.fin_item_id;
							d.fst_unit = dataTab.fst_unit;
						}
						//d.fin_item_id = "a";
					},
				},
				paging: true,
				info:true,				
			}).on('draw',function(){
				$(".dataTables_scrollHeadInner").css("min-width","100%");
				$(".dataTables_scrollHeadInner > table").css("min-width","100%");
			});

		});		
	</script>
</div>

<?php
	echo $mdlStock;
?>

<script type="text/javascript" info="event">
	$(function(){		

		$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
			var target = $(e.target).attr("href") // activated tab
			if (target == "#history"){
				getListHistory();
			}
		});

		

		$(".cls-filter").change(function(e){
			var itemType = $("#fst_item_type").val();
			var lineBusiness = $("#fin_linebusinness_id").val();
			var costType =  $(".cost_type:checked").val();

			$.ajax({
				url:SITE_URL + "tr/purchase/purchase_request/get_item_process_list",
				method:"GET",
				data:{
					fst_item_type : $("#fst_item_type").val(),
					fst_linebusiness_id : $("#fin_linebusinness_id").val(),
					fst_stock_cost_type : costType
				}
			}).done(function(resp){				
				dataDetails = resp.data;
				redrawPRList();				
			});
		});


		$("#fin_linebusinness_id").change(function(e){
			App.getValueAjax({
				model:"msrelations_model",
				func:"getSupplierByLineBusinessAndActiveBranch",
				params:[$("#fin_linebusinness_id").val()],
				callback:function(supplierList){
					App.log(supplierList);
					$("#fin_supplier_id").empty();
					$.each(supplierList,function(i,v){
						App.addOptionIfNotExist("<option value='"+v.fin_relation_id+"'>"+v.fst_relation_name+"</option>","fin_supplier_id");
					});
					
				}
			});
		});

		$("#btnNew").click(function(e){
			e.preventDefault();
			cleanForm();
		});

		$("#btn-process").click(function(e){
			e.preventDefault();
			submitAjax();
		});				
		
		$("#btnClose").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url()?>tr/purchase/purchase_request/";
		});
		

		$("#tbldetails").on("change" , ".qty_aloc",function(e){
			t = $("#tbldetails").DataTable();
			var trRow = $(this).parents('tr').prev();
			var data = t.row(trRow).data();				
			details = data.details;
			recId = $(this).data('id');			
			newValue = $(this).val();
			var columnUpdate = $(this).data('column');

			$.each(details,function(i,v){
				if (v.fin_rec_id == recId){
					if (columnUpdate == "fdb_qty_process"){
						details[i].fdb_qty_process = newValue;
					}else{
						details[i].fdb_qty_po = newValue;
					}					
				}
			});
			data.details = details;
			t.row(trRow).data(data).draw(false);						
		})
		
		
		
	});
</script>

<script type="text/javascript" info="define">
	var dataDetails;
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
				{"className":'details-control text-center',"defaultContent": '<i class="fa fa-caret-right" aria-hidden="true"></i>',width:"10px",orderable:false},
				{"title" : "Item","width": "250px",sortable:false,
					render:function(data,type,row){
						return row.fst_item_code + " - " +row.fst_item_name;
					}
				},
				{"title" : "Unit","width": "50px",sortable:false,data:"fst_unit"},
				{"title" : "Requset","width": "100px",sortable:false,className:'text-right',data:"fdb_qty_req"},
				{"title" : "Process","width": "100px",sortable:false,className:'text-right',
					render:function(data,type,row){
						//return row.fdb_qty_process;

						details = row.details;
						total = 0;
						$.each(details, function(i,dataD){
							qtyProcess = dataD.fdb_qty_process == null ? 0 : dataD.fdb_qty_process;
							qtyProcess = parseFloat(qtyProcess);
							total += qtyProcess;
						});
						return total;
					}
				},				
				{"title" : "Action","width": "75px",sortable:false,className:'text-center',
					render:function(data,type,row){
						var action = '<a class="btn-hist" href="#" data-original-title="" title=""><i class="fa fa-history"></i></a>';
						action += '&nbsp;<a class="btn-stock" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-shopping-cart"></i></a>';
						action += '&nbsp;<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
						
						
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
		}).on('click','.btn-hist',function(e){
			e.preventDefault();
			t = $("#tbldetails").DataTable();
			var trRow = $(this).parents('tr');			
			selectedDetail = t.row(trRow);
			var data = selectedDetail.data();	
			mdlDetail.show(data);

			
		
		}).on('click','.btn-stock',function(e){
			e.preventDefault();
			t = $("#tbldetails").DataTable();
			var trRow = $(this).parents('tr');			
			selectedDetail = t.row(trRow);
			var data = selectedDetail.data();	
			MdlStock.itemName = data.fst_item_name;
			MdlStock.show(data.fin_item_id,data.fst_unit);
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
		
		$("#tbldetails").on("click",".btn-delete-detail",function(e){
			e.preventDefault();
			/*
			var detailId = $(this).data('id');	
			
			$.each(dataDetails,function(i,v){
				$.each(v.details,function(y,detail){
					if (detail.fin_rec_id == detailId){
						dataDetails[i].details.splice(y,1);
					}
				})
			});
			$("#tbldetails").DataTable().draw();
			*/
			
			t = $("#tbldetails").DataTable();
			var trRow = $(this).parents('tr').prev();
			var data = t.row(trRow).data();	
			details = data.details;			
			recId = $(this).data('id');	
			$(".detail_id_"+ recId).remove();			
			$.each(details,function(i,v){
				if (v.fin_rec_id == recId){					
					details.splice(i,1);
				}
			});
			data.details = details;
			t.row(trRow).data(data).draw(false);			

			
			//App.log(dataDetails);

		});

		App.fixedSelect2();

		//initForm();
	});


</script>

<script type="text/javascript" info="function">
	
	function redrawPRList(){
		t = $("#tbldetails").DataTable();
		t.clear().draw();
		$.each(dataDetails, function(i,dataD){
			details = dataD.details;
			//dataD.fdb_qty_process = dataD.fdb_qty_req;
			dataD.details  = details.map(function(obj){
				obj.fdb_qty_process = obj.fdb_qty_req;
				obj.fdb_qty_po = 0;
				return obj;
			});				
			t.row.add(dataD);
		});
		t.draw(false);
	}

	function sub_trpurchaserequestitems(data){
		details = data.details;
		var result =  "<table class='table bordered'>";		
		$.each(details,function(i,v){
			result +=  "<tr class='detail_id_" +v.fin_rec_id + "'>";		
			result +="<td style='width:100px'>"+ v.fst_req_department_name +"</td>";
			result +="<td style='width:160px'>"+ v.fst_pr_no +"</td>";
			result +="<td style='width:140px'>"+ v.fdt_pr_datetime +"</td>";
			result +="<td style='width:75px'>"+ v.fdt_etd +"</td>";
			result +="<td style='width:85px' class='text-right'>"+ v.fdb_qty_req +"</td>";			
			result +="<td style='width:85px;text-align:right'><label>Qty Process :</label> <input type='number'  data-id='"+v.fin_rec_id+"'  data-column='fdb_qty_process' style='width:100%' class='qty_aloc text-right' value='"+v.fdb_qty_process+"'/></td>";
			result +="<td style='width:85px;text-align:right'><label>Qty PO :</label> <input type='number'  data-id='" +v.fin_rec_id+"' data-column='fdb_qty_po' style='width:100%' class='qty_aloc text-right' value='0'/></td>";
			result +="</tr>";
			result += "<tr class='detail_id_" +v.fin_rec_id + "'>";
			result += "<td colspan=6><i>Memo: "+ v.fst_memo +"</i></td>";
			result += "<td><a class='btn btn-delete-detail' data-id='"+ v.fin_rec_id +"'><i class='fa fa-trash'></i></a></td>";
			result += "</tr>";			
		})
		result += "</table>"		
		return result;
	}	

	function cleanForm(){
		$("#fst_item_type").val(null);//.trigger("change");
		$("#fin_linebusinness_id").val(null);//.trigger("change");
		$("#fin_supplier_id").empty();
		t = $("#tbldetails").DataTable();
		t.clear().draw();
	}

	function submitAjax(){
		
		url =  "<?= site_url() ?>tr/purchase/purchase_request/ajx_process_pr/";
		

		//var dataSubmit = $("#frmTransaction").serializeArray();
		var dataSubmit = [];		

		dataSubmit.push({
			name : SECURITY_NAME,
			value: SECURITY_VALUE
		});					

		dataSubmit.push({
			name:"fst_item_type",
			value: $("#fst_item_type").val()
		});

		dataSubmit.push({
			name:"fin_linebusinness_id",
			value: $("#fin_linebusinness_id").val()
		});

		dataSubmit.push({
			name:"fst_stock_cost_type",
			value: $(".cost_type:checked").val()
		});

		dataSubmit.push({
			name:"fin_supplier_id",
			value: $("#fin_supplier_id").val()
		});
		
		var details = [];		
		var datas =$("#tbldetails").DataTable().data();		
		$.each(datas,function(i,v){
			details.push(v);
		});

		dataSubmit.push({
			name:"details",
			value: JSON.stringify(details)
		});

		App.blockUIOnAjaxRequest("Please wait while generate data PO.....");
		$.ajax({
			type: "POST",
			//enctype: 'multipart/form-data',
			url: url,
			data: dataSubmit,
			timeout: 600000,
			success: function (resp) {				
				if (resp.message != "")	{
					alert(resp.message);
				}
				
				if(resp.status == "SUCCESS") {
					data = resp.data;
					
					if (data.with_po){
						window.location.href = SITE_URL + "tr/purchase_order/generate/" + data.fin_process_id;
					}else{
						location.reload();
					}
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
	}

	function getListHistory(){		
		if ($.fn.DataTable.isDataTable( '#tblHist' ) ) {
			$('#tblHist').DataTable().clear().destroy();
		}
		
		$('#tblHist').on('preXhr.dt', function ( e, settings, data ) {
			//add aditional data post on ajax call
			data.sessionId = "TEST SESSION ID";
		}).DataTable({
			scrollX: true,
			scrollCollapse: true,
			order:[[1,"desc"]],
			dataSrc:"data",
			processing: true,
			serverSide: true,
			//searching: false,
			ajax: "<?=site_url()?>tr/purchase/purchase_request/fetch_list_processed_data",
			columns:[				
				{"className":'details-history-control text-center',"defaultContent": '<i class="fa fa-caret-right" aria-hidden="true"></i>',width:"10px",orderable:false},
				{"title" : "Process Datetime","width": "250px" , data:"fdt_process_datetime"},
				{"title" : "Process Id","width": "50px",sortable:true,data:"fin_process_id"},
				{"title" : "Qty Processed","width": "80px",sortable:false,className:'text-right',data:"fdb_qty_process"},
				{"title" : "PO #","width": "100px",sortable:false,className:'',data:"fst_po_no"},
				{"title" : "Qty PO","width": "80px",sortable:false,className:'text-right',data:"fdb_qty_to_po"},				
				{"title" : "Action","width": "75px",sortable:false,className:'text-center',
					render:function(data,type,row){
						var action = '';
						if (row.fdb_qty_to_po > 0 && row.fin_po_id == null){
							action += '<a class="btn-generate-po" href="#" title="Generate PO" data-prosesid="'+ row.fin_process_id +'"><i class="fa fa-cogs"></i></a> &nbsp;';
						}
						action += '<a class="btn-delete-processed" href="#" title="Cancel Process"><i class="fa fa-trash"></i></a>';
						return action;
					}
				}
			],			
		}).on('draw',function(){
			$(".dataTables_scrollHeadInner").css("min-width","100%");
			$(".dataTables_scrollHeadInner > table").css("min-width","100%");
		}).on('click','.btn-delete-processed',function(e){
			e.preventDefault();
			t = $('#tblHist').DataTable();
			var trRow = $(this).parents('tr');
			var data = t.row(trRow).data();
			$.ajax({
				url:"<?=site_url()?>tr/purchase/purchase_request/ajx_cancel_process/" +data.fin_process_id,
				method:"GET",
			}).done(function(resp){
				if (resp.message != ""){
					alert(resp.message);
				}

				if (resp.status == "SUCCESS"){
					t.row(trRow).remove().draw();
				}
			});		
		}).on('click','.btn-generate-po',function(e){
			e.preventDefault();
			t = $('#tblHist').DataTable();
			var trRow = $(this).parents('tr');
			var data = t.row(trRow).data();
			window.location.href = SITE_URL + "tr/purchase_order/generate/" + data.fin_process_id;
			
        }).on('click','.details-history-control',function(e){
			e.preventDefault();
			t = $('#tblHist').DataTable();
			var tr = $(this).closest('tr');

			//data = t.row(trRow).data();			
            var row = t.row( tr );
            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
            }else {
				// Open this row
				trpurchaserequest_history_details(row.data(),function(result){
					row.child(result).show();
                	tr.addClass('shown');
				});
				//console.log(a);
				//row.child(sub_trpurchaserequestitems(row.data()) ).show();                
				//row.child(  ).show();
                //tr.addClass('shown');
			} 
			



			//trpurchaserequest_history_details(data);
		});

	}

	function trpurchaserequest_history_details(data,callback){
		finProcessId = data.fin_process_id;		
		App.blockUIOnAjaxRequest();
		req = $.ajax({
			url:"<?=site_url()?>/tr/purchase/purchase_request/ajx_get_process_details/" + finProcessId,
			method:"GET"
		}).done(function(resp){
			if(resp.status == "SUCCESS"){
				listDetail = resp.data;
				App.log(listDetail);
				var result =  "<table class='table bordered'>";		
				$.each(listDetail,function(i,v){
					result +=  "<tr>";		
					result +="<td style='width:160px'>"+ v.fst_pr_no +"</td>";
					result +="<td style='width:140px'>"+ v.fst_item_name +"</td>";
					result +="<td style='width:140px'>"+ v.fst_unit +"</td>";					
					result +="<td style='width:85px' class='text-right'> Req: "+ v.fdb_qty_req +"</td>";			
					result +="<td style='width:85px' class='text-right'> Processed: "+ v.fdb_qty_process +"</td>";			
					result +="<td style='width:85px' class='text-right'> PO: "+ v.fdb_qty_to_po +"</td>";			
					result +="</tr>";
				})
				result += "</table>"

				callback(result);
			}
		});
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