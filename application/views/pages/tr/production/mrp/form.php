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
	.hm{		
		background-color: #22ffc7;
	}
</style>

<section class="content-header">
	<h1><?=lang("Material Requirement Planning")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Production") ?></a></li>
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
					<a id="btnJurnal" class="btn btn-primary" href="#" title="Jurnal" style="display:<?= $mode == "ADD" ? "none" : "inline-block" ?>"><i class="fa fa-align-left" aria-hidden="true"></i></a>
					<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
					<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Group")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
				</div>
			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmHeader" class="form-horizontal" action="" method="POST" >
				<div class="box-body">
					<input type="hidden" id="fin_assembling_id" name="fin_assembling_id" value="<?=$fin_assembling_id?>"/>

					<div class="form-group">
						<label for="fst_assembling_no" class="col-md-2 control-label"><?=lang("MRP")?> #</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_assembling_no" placeholder="<?=lang("Assembling No")?>" name="fst_assembling_no" value="<?=$fst_assembling_no?>"/>
							<div id="fst_assembling_no_err" class="text-danger"></div>
						</div>								

						<label for="fdt_assembling_datetime" class="col-md-2 control-label"><?=lang("Tanggal")?></label>
						<div class="col-md-4">
							<input type="text" class="form-control datetimepicker text-right" id="fdt_assembling_datetime" placeholder="<?=lang("Mutasi Datetime")?>" name="fdt_assembling_datetime" value=""/>
							<div id="fdt_fa_disposal_datetime_err" class="text-danger"></div>
						</div>								
                    </div>  

					<div class="form-group">
						<label for="fin_year" class="col-md-2 control-label"><?=lang("MPS #")?></label>
						<div class="col-md-4">
							<input type='TEXT' name='fin_year' class='form-control text-right' id='fin_year' value='2020'/>
							<div id="fst_type_err" class="text-danger"></div>
						</div>					
						<label for="fin_year" class="col-md-2 control-label"><?=lang("Month")?></label>
						<div class="col-md-4">
							<select class='form-control text-right' id='fin_year'>
								<option value='M1'><?=lang("Januari")?></option>
								<option value='M2'><?=lang("Febuari")?></option>
								<option value='M3'><?=lang("Maret")?></option>
								<option value='M4'><?=lang("April")?></option>
								<option value='M5'><?=lang("Mei")?></option>
								<option value='M6'><?=lang("Juni")?></option>
								<option value='M7'><?=lang("Juli")?></option>
								<option value='M8'><?=lang("Agustus")?></option>
								<option value='M9'><?=lang("September")?></option>
								<option value='M10'><?=lang("Oktober")?></option>
								<option value='M11'><?=lang("November")?></option>
								<option value='M12'><?=lang("Desember")?></option>								
							</select>
							<div id="fst_type_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="fst_notes" class="col-md-2 control-label"><?=lang("Notes")?></label>
						<div class="col-md-10">
							<textarea type="text" class="form-control" id="fst_notes" name="fst_notes" ></textarea>
							<div id="fst_notes_err" class="text-danger"></div>
						</div>		
					</div>  
					
					<div class="form-group">							
						<div class="col-md-12" style="text-align:right">
							<button id="btn-add-items" class="btn btn-primary btn-sm"><i class="fa fa-cart-plus" aria-hidden="true"></i>&nbsp;&nbsp;Tambah Item</button>
						</div>
						<div class="col-sm-12">
							<table id="tbldetails" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-md-12">
							<label class="control-label text-left">Detail Bill Of Materials</label>
						</div>	
						

						<div class="col-sm-12">
							<ul class="nav nav-tabs">
								<li class="active"><a data-toggle="tab" href="#detailw1">Week 1</a></li>
								<li><a data-toggle="tab" href="#detailw2">Week 2</a></li>
								<li><a data-toggle="tab" href="#detailw2">Week 3</a></li>
								<li><a data-toggle="tab" href="#detailw2">Week 4</a></li>
								<li><a data-toggle="tab" href="#detailw2">Week 5</a></li>
							</ul>

							<div class="tab-content">
								<div id="detailw1" class="tab-pane fade in active">
									<table id="tbldetailsw1" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
								</div>
								<div id="detailw2" class="tab-pane fade">
									<table id="tbldetailsw2" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
								</div>
								<div id="detailw3" class="tab-pane fade">
									<table id="tbldetailsw3" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
								</div>
								<div id="detailw4" class="tab-pane fade">
									<table id="tbldetailsw4" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
								</div>
								<div id="detailw5" class="tab-pane fade">
									<table id="tbldetailsw5" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
								</div>

							</div>
							
						</div>
					</div>
					
                </div>
				<!-- end box body -->

                <div class="box-footer text-right">
                    
                </div>
                <!-- end box-footer -->
            </form>
        </div>
    </div>
</section>

<div id="mdlDetail" class="modal fade in" role="dialog" style="display: none">
	<div class="modal-dialog" style="display:table;width:600px">
		<!-- modal content -->
		<div class="modal-content">
			<div class="modal-header" style="padding:7px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 5px;border-top-right-radius: 5px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Tambah Detail")?></h4>
			</div>

			<div class="modal-body">				        
				<form class="form-horizontal">
				
					<div class="form-group">
						<label class="col-md-2 control-label"><?=lang("Item")?>:</label>						
						<label class="col-md-10 control-label">Pencil warna greebel - Box</label>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label "><?=lang("Buffer")?></label>
						<label class="col-md-2 control-label hm">50</label>				
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label"><?=lang("Balance")?></label>
						<label class="col-md-4 control-label"><?=lang("MTS")?></label>
						<label class="col-md-4 control-label"><?=lang("Qty")?></label>						
					</div>

					<div class="form-group">
						<label class="col-md-1 control-label">1</label>
						<label class="col-md-3 control-label">50</label>
						<label class="col-md-4 control-label">500</label>
						<div class="col-md-4">
							<input type='TEXT' id="" class="form-control text-right" value="400"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">2</label>
						<label class="col-md-3 control-label">50</label>
						<label class="col-md-4 control-label">500</label>
						<div class="col-md-4">
							<input type='TEXT' id="" class="form-control text-right" value="400"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">3</label>
						<label class="col-md-3 control-label">50</label>
						<label class="col-md-4 control-label">500</label>
						<div class="col-md-4">
							<input type='TEXT' id="" class="form-control text-right" value="400"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">4</label>
						<label class="col-md-3 control-label">50</label>
						<label class="col-md-4 control-label">500</label>
						<div class="col-md-4">
							<input type='TEXT' id="" class="form-control text-right" value="400"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">5</label>
						<label class="col-md-3 control-label">50</label>
						<label class="col-md-4 control-label">500</label>
						<div class="col-md-4">
							<input type='TEXT' id="" class="form-control text-right" value="400"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">6</label>
						<label class="col-md-3 control-label">50</label>
						<label class="col-md-4 control-label">500</label>
						<div class="col-md-4">
							<input type='TEXT' id="" class="form-control text-right" value="400"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">7</label>
						<label class="col-md-3 control-label">50</label>
						<label class="col-md-4 control-label">500</label>
						<div class="col-md-4">
							<input type='TEXT' id="" class="form-control text-right" value="400"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">8</label>
						<label class="col-md-3 control-label">50</label>
						<label class="col-md-4 control-label">500</label>
						<div class="col-md-4">
							<input type='TEXT' id="" class="form-control text-right" value="400"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">9</label>
						<label class="col-md-3 control-label">50</label>
						<label class="col-md-4 control-label">500</label>
						<div class="col-md-4">
							<input type='TEXT' id="" class="form-control text-right" value="400"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">10</label>
						<label class="col-md-3 control-label">50</label>
						<label class="col-md-4 control-label">500</label>
						<div class="col-md-4">
							<input type='TEXT' id="" class="form-control text-right" value="400"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">11</label>
						<label class="col-md-3 control-label">50</label>
						<label class="col-md-4 control-label">500</label>
						<div class="col-md-4">
							<input type='TEXT' id="" class="form-control text-right " value="400"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">12</label>
						<label class="col-md-3 control-label">50</label>
						<label class="col-md-4 control-label">500</label>
						<div class="col-md-4">
							<input type='TEXT' id="" class="form-control text-right" value="400"/>
						</div>
					</div>



					
					
				
				</form>				
			</div>

			<div class="modal-footer">
				<button id="btn-save-detail" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Add")?></button>
				<button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
			</div>
		</div>
	</div>

	<script type="text/javascript" info="define">
		var selectedItemD;

		mdlDetail = {			
			show:function(){
				if (selectedDetail != null){
					var data = selectedDetail.data();

					console.log(data);
					selectedItemD = data.item;					
					App.addOptionIfNotExist("<option value='"+selectedItemD.id+"'>"+selectedItemD.text+"</option>","fin_item_id_d");
					$("#fin_item_id_d").trigger({
						type: 'select2:select',
						params: {
							data: selectedItemD
						}
					});
					$("#fin_item_id_d").trigger("change");

					App.addOptionIfNotExist("<option value='"+data.fst_unit+"'>"+data.fst_unit+"</option>","fst_unit_d");
					$("#fst_unit_d").trigger("change");
					$("#fdb_qty_d").val(data.fdb_qty);
					$("#fdc_hpp_d").val(data.fdc_hpp);
					$("#fst_notes_d").val(data.fst_notes);				
				}				
				$("#mdlDetail").modal("show");
			},
			hide:function(){
				$("#mdlDetail").modal("hide");
			},
			clear:function(){
				$("#fin_item_id_d").val(null).trigger("change.select2");
				$("#fst_unit_d").val(null).trigger("change.select2");
				$("#fdb_qty_d").val(1);
				$("#fdc_hpp_d").val(0);
				$("#fst_notes_d").val(null);
				selectedItemD = null;			
				selectedDetail = null;
			},			
		};

	</script>

	<script type="text/javascript" info="event">		
		$(function(){

			//selectedItemD
			$("#fin_item_id_d").on("select2:select",function(e){
				selectedItemD = e.params.data;

			});


			$(".hpp-detail").change(function(e){
				e.preventDefault();				

				if ($("#fin_item_id_d").val() == null || $("#fst_unit_d").val() == null){
					return;
				}

				var finWarehouseId;
				if ($("#fst_type").val() == "ASSEMBLING"){
					finWarehouseId = $("#fin_source_warehouse_id").val();
				}else{
					finWarehouseId = $("#fin_target_warehouse_id").val();
				}

				data = {
					"fin_item_id":$("#fin_item_id_d").val(),
					"fst_unit":$("#fst_unit_d").val(),
					"fdb_qty":$("#fdb_qty_d").val(),
					"fin_warehouse_id":finWarehouseId
				};

				$.ajax({
					url:"<?=site_url()?>tr/production/assembling/ajxGetTotalHPP",
					method:"GET",
					data:data,

				}).done(function(resp){
					
					if (resp.status == "SUCCESS"){
						data = resp.data;				
						$("#fdc_hpp_d").val(data.HPP);
					}
				});

			});

			$("#btn-save-detail").click(function(e){
				t = $("#tbldetails").DataTable();
				var data = {
					fin_rec_id:0,
					item: selectedItemD,
					fst_unit:$("#fst_unit_d").val(),
					fdb_qty:$("#fdb_qty_d").val(),
					fdc_hpp:$("#fdc_hpp_d").val(),
					fst_notes:$("#fst_notes_d").val(),			
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
	<script type="text/javascript" info="init">
		$(function(){			
			$("#fin_item_id_d").select2({
				minimumInputLength: 2,
				ajax:{
					delay:250,
					url:"<?=site_url()?>tr/production/assembling/ajxGetItemList",
					processResults: function (resp) {
						console.log(resp);
						if (resp.status == "SUCCESS"){
							data = resp.data;
							var list  = $.map(data,function(v,i){
								v.id = v.fin_item_id;
								v.text = v.fst_item_code + " - " +v.fst_item_name;
								return v;
							});
							return {
								results:list
							}							
						}
					}
				}
			});
			
			$("#fst_unit_d").select2({
				minimumInputLength: 0,
				minimumResultsForSearch: -1,
				ajax:{
					delay:250,
					url:function(params){
						return "<?=site_url()?>tr/production/assembling/ajxGetUnits/" + $("#fin_item_id_d").val();
					},
					processResults: function (resp) {
						console.log(resp);
						if (resp.status == "SUCCESS"){
							data = resp.data;
							var list  = $.map(data,function(v,i){
								v.id = v.fst_unit;
								v.text = v.fst_unit;
								return v;
							});
							return {
								results:list
							}							
						}
					}
				}
			});
			
		});

	</script>

</div>

<?php echo $mdlEditForm ?>
<?php echo $mdlPrint ?>
<?php echo $mdlJurnal ?>

<script type="text/javascript" info="define">
	var selectedDetail;	
</script>

<script type="text/javascript" info="bind">
	$(document).bind('keydown', 'alt+d', function(){
		$("#btn-add-detail").trigger("click");
	});
</script>


<script type="text/javascript" info="event">
	$(function(){
		$("#btnNew").click(function(e){
			//e.preventDefault();
			window.location.replace("<?=site_url()?>tr/fixed_asset/disposal/add");
		});
		$("#btnPrint").click(function(e){
			e.preventDefault();
			frameVoucher.print("<?=site_url()?>tr/gudang/mutasi/print_voucher/" + $("#fin_mag_id").val());
		});
		$("#btnJurnal").click(function(e){
			e.preventDefault();
			MdlJurnal.showJurnalByRef("DFA",$("#fin_fa_disposal_id").val());
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
			window.location.replace("<?=site_url()?>tr/production/assembling");
		});	

		$("#btn-add-items").click(function(e){
			e.preventDefault();
			mdlDetail.show();
		});

		$(".hpp-header").change(function(e){
			e.preventDefault();
			calculateHPPHeader();

			if ($("#fdc_hpp_header").val() != 0){
				return;
			}

			if ($("#fin_item_id").val() == null || $("#fst_unit").val() == null){
				return;
			}
			

			var finWarehouseId;
			if ($("#fst_type").val() == "ASSEMBLING"){
				finWarehouseId = $("#fin_target_warehouse_id").val();				
			}else{
				finWarehouseId = $("#fin_source_warehouse_id").val();
			}

			data = {
				"fin_item_id":$("#fin_item_id").val(),
				"fst_unit":$("#fst_unit").val(),
				"fdb_qty":$("#fdb_qty").val(),
				"fin_warehouse_id":finWarehouseId
			};

			$.ajax({
				url:"<?=site_url()?>tr/production/assembling/ajxGetTotalHPP",
				method:"GET",
				data:data,

			}).done(function(resp){				
				if (resp.status == "SUCCESS"){
					data = resp.data;
					$("#fdc_hpp_header").val(data.HPP);
				}
			});
		})
		
	});
</script>
<script type="text/javascript" info="init">
	$(function(){		
		$("#fdt_assembling_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");
		$("#fin_item_id").select2({
			minimumInputLength: 2,
			ajax:{
				delay:250,
				url:"<?=site_url()?>tr/production/assembling/ajxGetItemList",
				processResults: function (resp) {
					console.log(resp);
					if (resp.status == "SUCCESS"){
						data = resp.data;
						var list  = $.map(data,function(v,i){
							v.id = v.fin_item_id;
							v.text = v.fst_item_code + " - " +v.fst_item_name;
							return v;
						});
						return {
							results:list
						}							
					}
				}
			}
		});
		
		$("#fst_unit").select2({
			minimumInputLength: 0,
			minimumResultsForSearch: -1,
			ajax:{
				delay:250,
				url:function(params){
					return "<?=site_url()?>tr/production/assembling/ajxGetUnits/" + $("#fin_item_id").val();
				},
				processResults: function (resp) {
					console.log(resp);
					if (resp.status == "SUCCESS"){
						data = resp.data;
						var list  = $.map(data,function(v,i){
							v.id = v.fst_unit;
							v.text = v.fst_unit;
							return v;
						});
						return {
							results:list
						}							
					}
				}
			}
		});
		

		$("#fin_source_warehouse_id,#fin_target_warehouse_id").select2();

		
		$('#tbldetails').on('preXhr.dt', function ( e, settings, data ) {
			data.sessionId = "TEST SESSION ID";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "id","width": "0px",sortable:false,data:"fin_rec_id",visible:false},
				{"title" : "Item","width": "300px",sortable:false,data:"fin_item_id",
					"render":function(data,type,row){
						return row.item.text + "<br><i>" + row.fst_notes + "</i>"; 
					}
				},
				{"title" : "Unit","width": "50px",sortable:false,data:"fst_unit"},
				{"title" : "Q.MPS","width": "50px",sortable:false,data:"fst_unit"},
				{"title" : "Q.W1","width": "50px",sortable:false,data:"fst_unit"},
				{"title" : "Q.W2","width": "50px",sortable:false,data:"fst_unit"},
				{"title" : "Q.W3","width": "50px",sortable:false,data:"fst_unit"},
				{"title" : "Q.W4","width": "50px",sortable:false,data:"fst_unit"},
				{"title" : "Q.W5","width": "50px",sortable:false,data:"fst_unit"},
				{"title" : "Q.Ttl","width": "50px",sortable:false,data:"fst_unit"},			
				{"title" : "Action","width": "80px",sortable:false,className:'dt-body-center text-center',
					render: function(data,type,row){
						var action = '<a class="btn-edit" href="#" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp;';												
						action += '<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';						
						return action;
					}
				},								
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
			calculateHPPHeader();
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
			t.row(trRow).remove().draw(false);
		});

		$('#tbldetailsw1').on('preXhr.dt', function ( e, settings, data ) {
			data.sessionId = "TEST SESSION ID";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "id","width": "0px",sortable:false,data:"fin_rec_id",visible:false},
				{"title" : "BOM Item","width": "300px",sortable:false,data:"fin_item_id",
					"render":function(data,type,row){
						return row.item.text + "<br><i>" + row.fst_notes + "</i>"; 
					}
				},
				{"title" : "Unit","width": "50px",sortable:false,data:"fst_unit"},
				{"title" : "Balance","width": "50px",sortable:false,data:"fst_unit"},
				{"title" : "Q.Req","width": "50px",sortable:false,data:"fst_unit"},
				{"title" : "Diff","width": "50px",sortable:false,data:"fst_unit"},
				{"title" : "Q.PO","width": "50px",sortable:false,data:"fst_unit"},
				{"title" : "Q.WO","width": "50px",sortable:false,data:"fst_unit"},
				
				
				
				{"title" : "Action","width": "80px",sortable:false,className:'dt-body-center text-center',
					render: function(data,type,row){
						var action = '<a class="btn-edit" href="#" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp;';												
						action += '<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';						
						return action;
					}
				},								
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
			calculateHPPHeader();
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
			t.row(trRow).remove().draw(false);
		});

        App.fixedSelect2();
		initForm();
	});
</script>
<script type="text/javascript" info="function">
	
	function submitAjax(confirmEdit){     
		var mode = "<?=$mode?>";   
		var dataDetails = new Array();	
		if (mode == "EDIT" && confirmEdit == 0){
			MdlEditForm.saveCallBack = function(){
				submitAjax(1);
			};		
			MdlEditForm.show();
			return;
		}


		data = $("#frmHeader").serializeArray();

		data.push({
			name:SECURITY_NAME,
			value: SECURITY_VALUE
		});	
		
		var t = $('#tbldetails').DataTable();
		var datas = t.data();

		$.each(datas,function(i,v){
			v.fin_item_id = v.item.id;
			delete v.item;
			dataDetails.push(v);
		});

		data.push({
			name:"details",
			value: JSON.stringify(dataDetails)
		});

		if (mode == "ADD"){
			url = "<?=site_url()?>tr/production/assembling/ajx_add_save";
		}else{			
			url = "<?=site_url()?>tr/production/assembling/ajx_edit_save";
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
					$("#fin_assembling_id").val(data.insert_id);					
				}
        });

	}
	
	function initForm(){
		var mode = "<?=$mode?>";		
		if (mode == "EDIT"){			
			App.blockUIOnAjaxRequest();
			$.ajax({
				url:"<?= site_url() ?>tr/production/assembling/fetch_data/<?=$fin_assembling_id?>",
			}).done(function(resp){				
				dataH =  resp.data.header;
				if (dataH == null){
					alert("<?=lang("ID transaksi tidak dikenal")?>");
					return false;
				}
				
                App.autoFillForm(dataH);
				//$("#fdt_fa_disposal_datetime").val(dataH.fst_accum_account_code).trigger("change");
				$("#fdt_assembling_datetime").val(dateTimeFormat(dataH.fdt_assembling_datetime)).datetimepicker("update");				
				App.addOptionIfNotExist("<option value='"+dataH.fin_item_id +"'>"+dataH.fst_item_code +" - " + dataH.fst_item_name +"</option>","fin_item_id");
				App.addOptionIfNotExist("<option value='"+dataH.fst_unit +"'>"+dataH.fst_unit +"</option>","fst_unit");

				t = $("#tbldetails").DataTable();
				$.each(resp.data.details,function(i,v){
					var item = {
						"id":v.fin_item_id,
						"text":v.fst_item_code + " - " + v.fst_item_name,
					};
					var data = {
						fin_rec_id:v.fin_rec_id,					
						item:item,
						fst_unit:v.fst_unit,
						fdb_qty:v.fdb_qty,
						fdc_hpp:v.fdc_hpp,
						fst_notes:v.fst_notes,
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

		var url =  "<?= site_url() ?>tr/production/assembling/delete/" + $("#fin_assembling_id").val();
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

	function calculateHPPHeader(){
		var t = $('#tbldetails').DataTable();
		var datas = t.data();

		var ttlHPP =0;
		$.each(datas,function(i,v){
			ttlHPP += parseFloat(v.fdc_hpp);
		});

		if (ttlHPP  == 0){
			$("#fdc_hpp_header").prop("readonly",0);
		}else{	
			$("#fdc_hpp_header").prop("readonly",1);
		}
		$("#fdc_hpp_header").val(App.money_format(ttlHPP));
	}

</script>
<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
