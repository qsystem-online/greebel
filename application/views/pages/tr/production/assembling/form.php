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
	<h1><?=lang("Assembling / Disassembling ")?><small><?=lang("form")?></small></h1>
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
						<label for="fst_assembling_no" class="col-md-2 control-label"><?=lang("Dis/Assembling")?> #</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_assembling_no" placeholder="<?=lang("Assembling No")?>" name="fst_assembling_no" value="<?=$fst_assembling_no?>"/>
							<div id="fst_assembling_no_err" class="text-danger"></div>
						</div>								

						<label for="fdt_assembling_datetime" class="col-md-2 control-label"><?=lang("Tgl Dis/Assembling")?></label>
						<div class="col-md-4">
							<input type="text" class="form-control datetimepicker text-right" id="fdt_assembling_datetime" placeholder="<?=lang("Mutasi Datetime")?>" name="fdt_assembling_datetime" value=""/>
							<div id="fdt_fa_disposal_datetime_err" class="text-danger"></div>
						</div>								
                    </div>  

					<div class="form-group">
						<label for="fst_type" class="col-md-2 control-label"><?=lang("Tipe")?></label>
						<div class="col-md-4">
							<select  class="form-control hpp-header" id="fst_type" placeholder="<?=lang("Tipe Process")?>" name="fst_type">
								<option value='ASSEMBLING'>Assembling</option>
								<option value='DISASSEMBLING'>Dissassembling</option>
							</select>
							<div id="fst_type_err" class="text-danger"></div>
						</div>					
					</div>
					
					<div class="form-group">					
						<label for="fin_item_id" class="col-md-2 control-label"><?=lang("Item")?></label>
						<div class="col-md-10">
							<select  class="form-control hpp-header" id="fin_item_id" placeholder="<?=lang("Item")?>" name="fin_item_id" style="width:100%"></select>
							<div id="fin_item_id_err" class="text-danger"></div>
						</div>
                    </div>  

					<div class="form-group">					
						<label for="fst_unit" class="col-md-2 control-label"><?=lang("Unit")?></label>
						<div class="col-md-4">
							<select  class="form-control hpp-header" id="fst_unit" placeholder="<?=lang("Unit")?>" name="fst_unit" style="width:100%"></select>
							<div id="fin_item_id_err" class="text-danger"></div>
						</div>
						<label for="fdb_qty" class="col-md-2 control-label"><?=lang("Qty")?></label>
						<div class="col-md-4">
							<input type='TEXT' class="form-control hpp-header money" id="fdb_qty" name="fdb_qty" value="1"/>
							<div id="fdb_qty_err" class="text-danger"></div>
						</div>
                    </div>  

					<div class="form-group">					
						<?php
							$warehouseList = $this->mswarehouse_model->getWarehouseList();
						?>
						<label for="fin_source_warehouse_id" class="col-md-2 control-label"><?=lang("Gudang Asal")?></label>
						<div class="col-md-4">
							<select  class="form-control hpp-header" id="fin_source_warehouse_id" placeholder="<?=lang("Gudang Asal")?>" name="fin_source_warehouse_id" style="width:100%">
								<?php
									foreach($warehouseList as $warehouse){
										echo "<option value='$warehouse->fin_warehouse_id'>$warehouse->fst_warehouse_name</option>";
									}
								?>
							</select>
							<div id="fin_source_warehouse_id_err" class="text-danger"></div>
						</div>
						<label for="fin_target_warehouse_id" class="col-md-2 control-label"><?=lang("Gudang Tujuan")?></label>
						<div class="col-md-4">
							<select  class="form-control hpp-header" id="fin_target_warehouse_id" placeholder="<?=lang("Gudang Tujuan")?>" name="fin_target_warehouse_id" style="width:100%">
								<?php
									foreach($warehouseList as $warehouse){
										echo "<option value='$warehouse->fin_warehouse_id'>$warehouse->fst_warehouse_name</option>";
									}
								?>
							</select>
							<div id="fin_target_warehouse_id_err" class="text-danger"></div>
						</div>
                    </div>  
					<div class="form-group">
						<label for="fst_notes" class="col-md-2 control-label"><?=lang("Harga Pokok Penjualan")?></label>
						<div class="col-md-10">
							<input type="text" class="form-control money" id="fdc_hpp_header" name="fdc_hpp_header" value="0"/>
							<div id="fdc_hpp_header_err" class="text-danger"></div>
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
						<label class="col-md-2 control-label"><?=lang("Item")?></label>						
						<div class="col-md-10">																		
							<select id="fin_item_id_d" class="form-control hpp-detail" style="width:100%"> </select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label "><?=lang("Unit")?></label>						
						<div class="col-md-10">
							<select id="fst_unit_d" class="form-control hpp-detail" style="width:100%"> </select>
						</div>						
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label "><?=lang("Qty")?></label>						
						<div class="col-md-10">																		
							<input id="fdb_qty_d" class="form-control money hpp-detail" value="1"/>
						</div>						
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label "><?=lang("HPP")?></label>						
						<div class="col-md-10">																		
							<input id="fdc_hpp_d" class="form-control money" value="0"/>
						</div>						
					</div>
										
					<div class="form-group">
						<label class="col-md-2 control-label "><?=lang("Notes")?></label>						
						<div class="col-md-10">																		
							<textarea id="fst_notes_d" class="form-control"> </textarea>
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
				{"title" : "Item","width": "70%",sortable:false,data:"fin_item_id",
					"render":function(data,type,row){
						return row.item.text + "<br><i>" + row.fst_notes + "</i>"; 
					}
				},
				{"title" : "Unit","width": "10%",sortable:false,data:"fst_unit"},
				{"title" : "Qty","width": "10%",sortable:false,data:"fdb_qty"},
				{"title" : "HPP","width": "10%",sortable:false,data:"fdc_hpp",className:'text-right',
					render:function(data,type,row){
						return money_format(data);
					}
				},
				{"title" : "Action","width": "10%",sortable:false,className:'dt-body-center text-center',
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
