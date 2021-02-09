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
	<h1><?=lang("RM-OUT Return")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Prioduksi") ?></a></li>
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
					<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
					<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
				</div>
			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmHeader" class="form-horizontal" action="" method="POST" >
				<div class="box-body">
					<input type="hidden" id="frm-mode" value="<?=$mode?>">
					<input type="hidden" class="form-control" id="fin_rmout_return_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_rmout_return_id" value="<?=$fin_rmout_return_id?>" readonly>

					
					<div class="form-group">
                        <label for="fst_rmout_return_no" class="col-md-2 control-label"><?=lang("RM-OUT Return No.")?> #</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_rmout_return_no" placeholder="<?=lang("RM-OUT Return No")?>" name="fst_rmout_return_no" value="<?=$fst_rmout_return_no?>" readonly>
							<div id="fst_rmout_return_no_err" class="text-danger"></div>
						</div>
						
						<label for="fdt_rmout_return_datetime" class="col-md-2 control-label"><?=lang("RM-OUT Date")?> *</label>
						<div class="col-md-4">
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control text-right datetimepicker" id="fdt_rmout_return_datetime" name="fdt_rmout_return_datetime"/>
							</div>
							<div id="fdt_rmout_return_datetime_err" class="text-danger"></div>
							<!-- /.input group -->
						</div>						
                    </div>

					<div class="form-group">
						<label for="fst_rmout_type" class="col-md-2 control-label"><?=lang("Workorder")?></label>
						<div class="col-md-10">							
							<select id="fin_wo_id" class="form-control" style="width:100%" ></select>
						</div>					
					</div>

					
					
					<div class="form-group">
						<label for="fin_wobatchno_id" class="col-md-2 control-label"><?=lang("Batch No.")?></label>
						<div class="col-md-10">							
							<select id="fin_wobatchno_id" class="form-control" name="fin_wobatchno_id" style="width:100%" ></select>
							<div id="fin_wobatchno_id_err" class="text-danger"></div>
						</div>					
					</div>

                    <div class="form-group">
						<label for="fin_warehouse_id" class="col-md-2 control-label"><?=lang("Target Warehouse")?></label>
						<div class="col-md-10">							
							<select id="fin_warehouse_id" class="form-control" name="fin_warehouse_id" style="width:100%" >
                            <?php
                                $warehouseList = $this->mswarehouse_model->mswarehouse_model->getNonLogisticWarehouseList();
                                foreach($warehouseList as $warehouse){
                                    echo "<option value='$warehouse->fin_warehouse_id'>$warehouse->fst_warehouse_name</option>";
                                }
                            ?>
                            </select>
							<div id="fin_warehouse_id_err" class="text-danger"></div>
						</div>					
					</div>
											
					<div class="form-group" style="margin-bottom:0px;">
						<div class="col-md-12" style="text-align:right">
							<button id="btn-add-items" class="btn btn-primary btn-sm"><i class="fa fa-cart-plus" aria-hidden="true"></i>&nbsp;&nbsp;Tambah Item</button>
						</div>
					</div>
					
					<table id="tblDetails" class="table table-bordered table-hover table-striped" style="width:100%"></table>
                    <div id="detail_err" class="text-danger"></div>					
                    <div class="form-group">
						<div class="col-sm-6">	
							<div class="form-group">								
								<div class="col-sm-12">
									<label for="fst_memo" class=""><?=lang("Memo")?></label>
									<textarea class="form-control" id="fst_memo" placeholder="<?= lang("Memo") ?>" name="fst_memo" rows="5" style="resize:none"></textarea>
									<div id="fst_memo_err" class="text-danger"></div>
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

<!-- modal atau popup "ADD" -->
<div id="mdlDetail" class="modal fade in" role="dialog" style="display:none">
	<div class="modal-dialog" style="display:table;width:600px">
		<!-- modal content -->
		<div class="modal-content">
			<div class="modal-header" style="padding:7px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 5px;border-top-right-radius: 5px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Add Items")?></h4>
			</div>
			
			<div class="modal-body">     
				<form class="form-horizontal">					
					<div class="form-group">
						<label class="col-md-3 control-label"><?=lang("Item")?></label>
						<div class="col-md-9">											
							<select id="d-fin_item_id" class="form-control" style="width:100%" readonly> </select>
						</div>										
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?=lang("Unit")?></label>	
						<div class="col-md-9">	
							<select id="d-fst_unit" class="form-control" style="width:100%"> </select>								
							<label class="control-label">  =>  </label>
							<label id="fdcConvBasicUnit" class="control-label">1</label>
							<label id="fstBasicUnit" class="control-label"><?=lang("Unit")?></label>
						</div>										
					</div>

					<div class="form-group">
						<label for="d-fdb_qty" class="col-md-3 control-label"><?=lang("Qty")?></label>
						<div class="col-md-9">
							<input type='TEXT' id="d-fdb_qty" class="money form-control" value="1"/>
						</div>
					</div>								

					<div class="form-group batchNoBlock">
						<label for="" class="col-md-3 control-label"><?=lang("Batch Number")?></label>
						<div class="col-md-9">
							<input type="TEXT"  id="fstBatchNo" class="form-control" />
						</div>
					</div>

					<div class="form-group serialNoBlock">
						<label for="" class="col-md-3 control-label"><?=lang("Serial Number")?></label>
						<div class="col-md-9">
							<input type='TEXT' id="fstSerialNo" class="form-control" />
						</div>										
					</div>
					<div class="form-group serialNoBlock" >
						<label for="" class="col-md-3 control-label"></label>
						<div class="col-md-9">
							<select multiple="multiple" id="fstSerialNoList" class="form-control"></select>
						</div>
					</div>
					<div class="form-group serialNoBlock">
						<label for="" class="col-md-3 control-label"></label>
						<div class="col-md-4" >
							<label for="" class=""><?=lang("Total Serial")?></label>
							<label id="ttlSerial" class="">0</label>

						</div>
						<div class="col-md-5 text-right" >
							<button id="btn-delete-serial" class="btn btn-primary btn-xs">Delete Selected Serial</button>
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
		//var availableSerialList = [];		
		var mdlDetail = {
			selectedItem:null,
			selectedUnit:null,
			selectedDetail:null,			
			show:function(){								
				//$("#d-fin_item_id").prop("disabled",true);
				//$("#d-fst_unit").prop("disabled",true);

				//if ($("#fin_wo_id").val() == null){
				//	$("#d-fin_item_id").prop("disabled",false);
				//	$("#d-fst_unit").prop("disabled",false);				
				//}

				if (mdlDetail.selectedDetail != null){
					data =  mdlDetail.selectedDetail.data();					
					mdlDetail.selectedItem = {
						fin_item_id:data.fin_item_id,
						fst_item_code:data.fst_item_code,
						fst_item_name:data.fst_item_name,
						fbl_is_batch_number:data.fbl_is_batch_number,
						fbl_is_serial_number:data.fbl_is_serial_number,
						id:data.fin_item_id,
						text:data.fst_item_code + " - " +data.fst_item_name, 
					};

					App.addOptionIfNotExist("<option value='"+mdlDetail.selectedItem.id+"'>"+mdlDetail.selectedItem.text+"</option>","d-fin_item_id");
					$('#d-fin_item_id').val(data.fin_item_id).trigger({
						type: 'select2:select',
						params: {
							data: mdlDetail.selectedItem
						}
					});

					mdlDetail.selectedUnit = {
						id:data.fst_unit,
						text:data.fst_unit,
						fst_unit:data.fst_unit,
						fbl_is_basic_unit:data.fst_is_basic_unit,
						convBasicUnit:data.fdc_conv_to_basic_unit,
						fst_basic_unit:data.fst_basic_unit					
					}

					App.addOptionIfNotExist("<option value='"+mdlDetail.selectedUnit.id+"'>"+mdlDetail.selectedUnit.text+"</option>","d-fst_unit");
					$("#d-fst_unit").val(data.fst_unit).trigger("change.select2");
										

					convertQty();
					$("#d-fdb_qty").val(data.fdb_qty);

					App.addOptionIfNotExist("<option value='"+data.fst_batch_number+"'>"+data.fst_batch_number+"</option>","fstBatchNo");
                    $("#fstBatchNo").val(data.fst_batch_number).trigger("change.select2");
                    
					getSerialNoList($("#fin_warehouse_id").val(),  $("#d-fin_item_id").val(),$("#fstBatchNo").val(),function(){
						$.each(data.fst_serial_number_list,function(i,serial){
							$("#fstSerialNoList").prepend("<option value='"+serial+"'>"+serial+"</option>");
						});
						calculateTotalSerialNo();
					});


				}

				$("#mdlDetail").modal("show");
			},

			hide:function(){
				$("#mdlDetail").modal("hide");
			},
			clear:function(){
				mdlDetail.selectedDetail =null;
				$("#d-fin_item_id").val(null).trigger("change.select2");
				$("#d-fst_unit").val(null).trigger("change.select2");
				$("#d-fdb_qty").val(1);
				$("#fstBatchNo").val(null).trigger("change.select2");
				$("#fstSerialNoList").empty();
			}
		}
	</script>

	<script type="text/javascript" info="init">
		$(function(){
			$("#d-fin_item_id").select2({
                //minimumResultsForSearch: -1,
				//minimumInputLength: 2,
				ajax:{
					delay: 250,
                    url: "<?=site_url()?>tr/production/rmout_return/ajxGetItemList",
                    data:function(params){
                        params.fin_wo_id = $("#fin_wo_id").val();
                        params.fin_wobatchno_id = $("#fin_wobatchno_id").val();                        
                        return params;
                    },
					dataType: 'json',
					processResults: function (result) {
						if (result.status == "SUCCESS"){
							var data = $.map(result.data, function (obj) {
								obj.id = obj.fin_item_id,  
								obj.text = obj.fst_item_code + " - "  + obj.fst_item_name;								
  								return obj;
							});

							return {
								results: data
							};
						}else{
							return {
								result:[]
							}
						}
					}
				}
			}).on('select2:select',function(e){
				var data = e.params.data;
				mdlDetail.selectedItem = data;
				$("#fstUnit").empty().trigger("change.select2");
				showHideBatchSerial();
			});

			
			$("#d-fst_unit").select2({
				minimumInputLength: 0,
				ajax:{
					url: "<?=site_url()?>/tr/production/rmout_return/ajxGetUnits",
					data:function(params){
						params.fin_item_id = $("#d-fin_item_id").val();
						return params;
					},
					dataType: 'json',
					processResults: function (result) {
						if (result.status == "SUCCESS"){
							var data = $.map(result.data, function (obj) {
								obj.id = obj.fst_unit; 
								obj.text = obj.fst_unit;
								obj.isBasic = obj.fbl_is_basic_unit;
								obj.convBasicUnit = obj.fdc_conv_to_basic_unit;								
  								return obj;
							});
							return {
								results: data
							};
						}else{
							return {
								result:[]
							}
						}
					}
				}
			}).on('select2:select', function (e) {
				var data = e.params.data;
				mdlDetail.selectedUnit = data;
				console.log(mdlDetail.selectedUnit);

				convertQty();
			});

			
		

		});
	</script>


	<script type="text/javascript" info="event">				
		$("#fstBatchNo").change(function(e){
			if( $(".serialNoBlock").is(":visible") ){
				if( $('#fstSerialNoList').has('option').length > 0 ) {				
					if (confirm("<?=lang("Rubah bach no akan menghapus semua serial no, lanjutkan ?")?>")){
						//Lanjut......	
					}else{
						e.preventDefault();
						return false;
					}
                }
                /*
				getSerialNoList($("#fin_warehouse_id").val(),  $("#d-fin_item_id").val(),$("#fstBatchNo").val(),function(){
					App.log(availableSerialList);
                });
                */

			}
		});

		$("#fstSerialNo").keydown(function(e){
			if (e.keyCode == 13){
				e.preventDefault();
				if (checkDuplicateSerialNo($("#fstSerialNo").val())){
					alert("<?=lang("Serial no duplikat !")?>");
					$("#fstSerialNo").select();
					return;
				}

                /*
				var idxArr =  availableSerialList.indexOf($("#fstSerialNo").val());
				if ( idxArr < 0){
					alert("<?=lang("No serial tidak dikenal !")?>");
					$("#fstSerialNo").select();
					return;
				}

				availableSerialList.splice( idxArr,1);
                */


				$("#fstSerialNoList").prepend("<option value='"+$("#fstSerialNo").val()+"'>"+$("#fstSerialNo").val()+"</option>");
				$("#fstSerialNo").val("");
				calculateTotalSerialNo();
			}
		});
		

		$("#btn-delete-serial").click(function(e){
			e.preventDefault();
			$("#fstSerialNoList option:selected").each(function () {
				$(this).remove(); //or whatever else
			});
			calculateTotalSerialNo();
		});
		
		$("#btn-save-detail").click(function(e){
			e.preventDefault();			
			item = mdlDetail.selectedItem;

			if ($("#fdbQty").val() <= 0 ){
				alert("<?=lang("Qty harus diisi !") ?>");
				return;
			}

			if (item.fbl_is_batch_number == 1){
				if ($("#fstBatchNo").val() == "" ){
					alert("Batch Number harus diisi !");
					return;
				}
			}

			if (item.fbl_is_serial_number == 1){
				var convToBasic = mdlDetail.selectedUnit.convBasicUnit; //parseFloat($("#fdcConvBasicUnit").text());
				var qtyInUnit = parseFloat($("#d-fdb_qty").val());
				var qtyInBasicUnit = qtyInUnit * convToBasic;
				if ($("#fstSerialNoList option").length != qtyInBasicUnit){
					alert("<?= lang("Total Serial Number harus sesuai dengan qty dalam basic unit")?>("+ qtyInBasicUnit +")");
					return;
				}
			}
			
			var arrSerial = [];
			$.each($("#fstSerialNoList option"),function(i,serial){
				arrSerial.push($(serial).val());
			});

			t = tblDetails;
			var dataRow;
			if (mdlDetail.selectedDetail != null){		
				dataRow = mdlDetail.selectedDetail.data();
			}else{
				dataRow  ={
					fin_rec_id: 0,
				}
			}
			console.log(dataRow);	
			
			
			dataRow.fin_item_id = mdlDetail.selectedItem.fin_item_id;
			dataRow.fst_item_code = mdlDetail.selectedItem.fst_item_code;
			dataRow.fst_item_name = mdlDetail.selectedItem.fst_item_name;
			dataRow.fbl_is_batch_number= mdlDetail.selectedItem.fbl_is_batch_number;
			dataRow.fbl_is_serial_number= mdlDetail.selectedItem.fbl_is_serial_number;
			
			dataRow.fst_basic_unit =  mdlDetail.selectedUnit.fst_basic_unit;			
			dataRow.fst_unit = mdlDetail.selectedUnit.fst_unit;
			dataRow.fbl_is_basic_unit = mdlDetail.selectedUnit.fbl_is_basic_unit;
			dataRow.fdc_conv_to_basic_unit = mdlDetail.selectedUnit.convBasicUnit;
			dataRow.fdb_qty = $("#d-fdb_qty").val();									
			dataRow.fst_batch_number = $("#fstBatchNo").val();
			dataRow.fst_serial_number_list =arrSerial;
			
			if (mdlDetail.selectedDetail == null){				
				t.row.add(dataRow).draw(false);
			}else{
				t.row(mdlDetail.selectedDetail).data(dataRow);
			}

			t.draw(false);
			mdlDetail.clear();
			mdlDetail.hide();		
		});

	</script>

	

	<script type="text/javascript" info="function">
		function convertQty(){
			$("#fdcConvBasicUnit").text(mdlDetail.selectedUnit.convBasicUnit);
			$("#fstBasicUnit").text(mdlDetail.selectedUnit.fst_basic_unit);
		}

		function showHideBatchSerial(){
			if (mdlDetail.selectedItem.fbl_is_batch_number == 1){
				$(".batchNoBlock").show();
			}else{
				$(".batchNoBlock").hide();				
			}

			if (mdlDetail.selectedItem.fbl_is_serial_number == 1){				
				$(".serialNoBlock").show();
				console.log(mdlDetail.selectedItem);
				getSerialNoList($("#fin_warehouse_id").val(),mdlDetail.selectedItem.id,$("#fstBatchNo").val(), function(){});
			}else{				
				$(".serialNoBlock").hide();
			}
		}

        /*
		function getSerialNoList(finWarehouseId,finItemId,fstBatchNo,callback){
			App.getValueAjax({
				model:"trinventory_model",
				func:"getReadySerialNoList",
				params:[finWarehouseId,finItemId,fstBatchNo],
				wait_message: "<h5>Please wait !,trying to get batch no list....</h5>",
				callback:function(value){
					$("#fstSerialNoList").empty();
					$.each(value,function(i,obj){
						availableSerialList.push(obj.fst_serial_no);
					});
					App.fixedSelect2();
					callback();					
				}
			});			
        }
        */
		function calculateTotalSerialNo(){
			$("#ttlSerial").text($("#fstSerialNoList option").length);
		}		
	</script>
</div>

<?php echo $mdlEditForm ?>
<?php echo $mdlPrint ?>


<script type="text/javascript" info="define">
    var selectedDetail = null;
	var tblDetails;
	var fin_warehouse_id=0;
	var mode ="<?=$mode?>";
</script>

<script type="text/javascript" info="bind">
	$(document).bind('keydown', 'alt+d', function(){
		$("#btn-add-detail").trigger("click");
	});
</script>



<script type="text/javascript" info="init">
	var dataBatchno = [];
	$(function(){
        $("#fdt_rmout_return_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");				
        
		$("#fin_wo_id").select2({
			minimumInputLength:0,
			ajax: {
                url: '<?= site_url() ?>tr/production/rmout_return/ajxGetWOList',
                dataType: 'json',
                delay: 250,
                processResults: function(resp) {
                    data2 = [];
					if (resp.status == "SUCCESS"){
						$.each(resp.data, function(index, value) {
							console.log(value);
							data2.push({
								"id": value.fin_wo_id,
								"text": value.fst_wo_no
							});
						});
						
					}
                    
                    return {
                        results: data2
                    };
                },
                cache: true,
            }
		}).change(function(e){
			$("#fin_wobatchno_id").val(null).trigger("change");	
		});
		
		

		$("#fin_wobatchno_id").select2({
			minimumInputLength:0,
			ajax: {
                url: '<?= site_url() ?>tr/production/rmout_return/ajxGetBatchWOList',
				data:function(params){
					params.fin_wo_id = $("#fin_wo_id").val();
					return params;
				},
                dataType: 'json',
                delay: 250,
                processResults: function(resp) {
                    data2 = [];
					if (resp.status == "SUCCESS"){
						$.each(resp.data, function(index, value) {
							console.log(value);
							data2.push({
								"id": value.fin_wobatchno_id,
								"text": value.fst_wobatchno_no
							});
						});
						
					}
                    
                    return {
                        results: data2
                    };
                },
                cache: true,
            }
		});
		
		tblDetails = $('#tblDetails').on('preXhr.dt', function ( e, settings, data ) {
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
				{"title" : "id","width": "0px",data:"fin_rec_id",visible:false},
				{"title" : "Item Code","width": "50px",data:"fst_item_code",visible:true,orderable:false},
				{"title" : "Item Name","width": "300px",data:"fst_item_name",visible:true,orderable:false},
				{"title" : "Qty","width": "50px",data:"fdb_qty",className:'text-right',orderable:false,},
				{"title" : "Unit","width": "100px",data:"fst_unit",orderable:false,},
				{"title" : "Action","width": "28px",className:'dt-body-center text-center',orderable:false,
                    render: function( data, type, row, meta ) {
                        return "<div style='font-size:16px'><a class='btn-edit' href='#'><i class='fa fa-pencil'></i></a><a class='btn-delete' href='#'><i class='fa fa-trash'></i></a></div>";                        
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
			//calculateTotal();
		}).on("click",".btn-delete",function(event){
			event.preventDefault();
			t = $('#tblSJDetails').DataTable();
			var trRow = $(this).parents('tr');
			t.row(trRow).remove().draw();
			calculateTotal();
		}).on("click",".btn-edit",function(event){	
            tRow = $(this).parents("tr");
			//mdlDetail.clear();
			mdlDetail.selectedDetail  = tblDetails.row(tRow);			
			mdlDetail.show();			
		});
		
		App.fixedSelect2();
		initForm();
	});
</script>

<script type="text/javascript" info="event">
	$(function(){
		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>tr/production/rmout_prod/add")
		});
		$("#btnPrint").click(function(e){
			e.preventDefault();
			frameVoucher.print("<?=site_url()?>tr/gudang/mutasi/print_voucher/" + $("#fin_mag_id").val());
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
			window.location.replace("<?=site_url()?>tr/production/rmout_return");
		});	

		$("#btn-add-items").click(function(e){
			e.preventDefault();
			selectedDetail = null;		
			mdlDetail.show();
			mdlDetail.clear();
		});
		
	});
</script>

<script type="text/javascript" info="function">
	function submitAjax(confirmEdit){        
		data = $("#frmHeader").serializeArray();

		data.push({
			name:SECURITY_NAME,
			value: SECURITY_VALUE
		});

		detail = new Array();		
		t = $('#tblDetails').DataTable();
		datas = t.data();
		var isValidData = true;
		$.each(datas,function(i,v){
			App.log(v);
			if ((v.fbl_is_batch_number == "1") && (v.fst_batch_number == null || v.fst_batch_number =="")){
				isValidData = false;
				alert ("Batch number " + v.fst_item_name  + " tidak boleh kosong !");
				return false;
			}
			if ((v.fbl_is_serial_number == 1) && (v.fst_serial_number_list == null || v.fst_serial_number_list =="")){
				isValidData = false;
				alert ("Serial number " + v.fst_item_name  + " tidak boleh kosong !");
				return false;
			}			
			detail.push(v);
		});
		if (isValidData == false){				
			return;
		}
		
		data.push({
			name:"detail",
			value: JSON.stringify(detail)
		});
	   
		if (mode == "ADD"){
			url = "<?=site_url()?>tr/production/rmout_prod/ajx_add_save";
		}else{
			if (confirmEdit == 0){
				MdlEditForm.saveCallBack = function(){
					submitAjax(1);
				};		
				MdlEditForm.show();
				return;
			}

			url = "<?=site_url()?>tr/production/rmout_prod/ajx_edit_save";
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
					$("#fin_sj_id").val(data.insert_id);

					//Clear all previous error
					$(".text-danger").html("");
					// Change to Edit mode
					$("#frm-mode").val("EDIT");  //ADD|EDIT
					$('#fst_sj_no').prop('readonly', true);
					$("#tabs-so-detail").show();
				}
        });

	}
	
	function initForm(){
		if (mode != "ADD"){
			App.blockUIOnAjaxRequest();
			$.ajax({
				url:"<?= site_url() ?>tr/production/rmout_prod/fetch_data/" + $("#fin_rmout_id").val(),
			}).done(function(resp){
				data =  resp.data;
				dataH = data.header;
				if (dataH == null){
					alert("<?=lang("ID transaksi tidak dikenal")?>");
					//$("#btnNew").trigger("click");
					return false;
				}
				
				App.autoFillForm(dataH);
                $("#fdt_rmout_return_datetime").val(dateTimeFormat(dataH.fdt_rmout_return_datetime)).datetimepicker("update");                
                App.addOptionIfNotExist("<option value='"+dataH.fin_wo_id+"'>"+dataH.fst_wo_no+"</option>","fin_wo_id");
				App.addOptionIfNotExist("<option value='"+dataH.fin_wobatchno_id+"'>"+dataH.fst_wobatchno_no+"</option>","fin_wobatchno_id");
				
				details = data.details;
				t = tblDetails;
				t.clear();				
				$.each(details,function(i,v){				
					
					var dataRow = {
						fin_rec_id:v.fin_rec_id,
						fin_item_id: v.fin_item_id,
						fst_item_code: v.fst_item_code,
						fst_item_name: v.fst_item_name,				
						fst_basic_unit : v.fst_basic_unit,
						fbl_is_batch_number:v.fbl_is_batch_number,
						fbl_is_serial_number:v.fbl_is_serial_number,
						fst_unit: v.fst_unit,
						fdc_conv_to_basic_unit : v.fdc_conv_to_basic_unit,
						fdb_qty:v.fdb_qty,												
						fst_batch_number:v.fst_batch_number,
						fst_serial_number_list: JSON.parse(v.fst_serial_number_list),
					}
					t.row.add(dataRow);
				});
				t.draw();								
				App.fixedSelect2();
			});
		}
	}

    

    function calculateTotal(){
        t = $("#tblDetails").DataTable();
        datas = t.rows().data();
        ttl = 0;
        $.each(datas,function(i,v){
            ttl += money_parse(v.fdb_qty);
        })
		$("#sub-total").val(money_format(ttl));		
	}
	
	function checkDuplicateSerialNo(serialNo){
		isDuplicate = false;
		$.each ($("#fstSerialNoList option"),function(i,v){
			if ($(v).val() == serialNo){
				isDuplicate = true;
				return false;
			};			
		});
		return isDuplicate;
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

		var url =  "<?= site_url() ?>tr/gudang/mutasi/delete/" + $("#fin_mag_id").val();
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

</script>
<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
