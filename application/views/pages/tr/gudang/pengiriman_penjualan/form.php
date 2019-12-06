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
	<h1><?=lang("Delivery Order")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Delivery Order") ?></a></li>
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
            <form id="frmDeliveryOrder" class="form-horizontal" action="<?=site_url()?>tr/delivery_order/add" method="POST" enctype="multipart/form-data">			
				<div class="box-body">
					<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">			
					<input type="hidden" id="frm-mode" value="<?=$mode?>">
					<input type="hidden" class="form-control" id="fin_sj_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_sj_id" value="<?=$fin_sj_id?>" readonly>

					
					<div class="form-group">
						<label for="fst_sj_no" class="col-md-2 control-label"><?=lang("Delivery Order No")?> #</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_sj_no" placeholder="<?=lang("Delivery Order No")?>" name="fst_sj_no" value="<?=$fst_sj_no?>" readonly>
							<div id="fst_sj_no_err" class="text-danger"></div>
						</div>
						
						<label for="fdt_sj_datetime" class="col-md-2 control-label"><?=lang("Delivery Order Date")?> *</label>
						<div class="col-md-4">
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control text-right datetimepicker" id="fdt_sj_datetime" name="fdt_sj_datetime"/>
							</div>
							<div id="fdt_sj_datetime_err" class="text-danger"></div>
							<!-- /.input group -->
						</div>						
                    </div>

					<div class="form-group">						
						<label for="fin_salesorder_id" class="col-md-2 control-label"><?=lang("Sales Order")?> :</label>
						<div class="col-md-4">
							<select id="fin_salesorder_id" class="form-control" name="fin_salesorder_id" style="width:100%" ></select>
							<div id="fin_salesorder_id_err" class="text-danger"></div>
						</div>
					
						<label for="" class="col-md-2 control-label"><?=lang("Tanggal SO")?> :</label>
						<div class="col-md-4">
                            <div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="unfocus form-control text-right" id="fdt_salesorder_datetime" disabled/>
							</div>
						</div>
					</div>

					<div class="form-group">						
						<label for="select-relations" class="col-md-2 control-label"><?=lang("Customer")?> :</label>
						<div class="col-md-10">
							<input type="TEXT" class="form-control unfocus" id="fst_relation_name" disabled/>
							<div id="fin_relation_id_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						
						<label for="fin_driver_id" class="col-md-2 control-label"><?=lang("Sopir")?> :</label>
						<div class="col-md-4">
							<select id="fin_driver_id" class="form-control" name="fin_driver_id">
								<?php
									$driverList = $this->users_model->getDriverList();
									foreach($driverList as $driver){
										echo "<option value='$driver->fin_user_id'>$driver->fst_fullname</option>";
									}
								?>
							</select>
							<div id="fin_driver_id_err" class="text-danger"></div>
						</div>
                        <label for="fst_no_polisi" class="col-md-2 control-label"><?=lang("No. Polisi")?> :</label>
                        <div class="col-md-4">
							<input type="text" class="form-control" id="fst_no_polisi" name="fst_no_polisi" style="width:100%"/>							
							<div id="fst_no_polisi_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
                        <label for="fin_warehouse_id" class="col-md-2 control-label"><?=lang("Warehouse")?> :</label>
						<div class="col-md-10">
							<select id="fin_warehouse_id" class="form-control" name="fin_warehouse_id">
								<?php
									$warehouseList = $this->mswarehouse_model->getNonLogisticWarehouseList();
									foreach($warehouseList as $warehouse){
										echo "<option value='$warehouse->fin_warehouse_id'>$warehouse->fst_warehouse_name</option>";
									}
								?>
							</select>
							<div id="fin_warehouse_id_err" class="text-danger"></div>
						</div>						
					</div>

                    <div class="form-group">
                        <label for="fst_shipping_address" class="col-md-2 control-label"><?=lang("Alamat Pengiriman")?> :</label>
						<div class="col-md-10">
							<select class="select2 form-control" name="fin_shipping_address_id" id="fin_shipping_address_id" style="width:100%"></select>
							<div id="fst_shipping_address_err" class="text-danger"></div>
						</div>														
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label"></label>
                        <div class="col-md-10">
							<textarea class="form-control" id="fst_shipping_address" style="width:100%" rows="5" readonly></textarea>
							<div id="fst_shipping_address_err" class="text-danger"></div>
						</div>
					</div>
                    
					<div class="form-group">
						<div class="checkbox col-md-10 col-md-offset-2">
							<label><input id="fbl_is_hold" type="checkbox" name="fbl_is_hold" value="1"><?= lang("Hold Pengiriman") ?></label>							
						</div>
					</div>
						
					<div class="form-group" style="margin-bottom:0px">
						<div class="col-md-12" style="text-align:right">
							<button id="btn-add-items" class="btn btn-primary btn-sm"><i class="fa fa-cart-plus" aria-hidden="true"></i>&nbsp;&nbsp;Tambah Item</button>
						</div>
					</div>
					
					<table id="tblSJDetails" class="table table-bordered table-hover table-striped" style="width:100%"></table>
                    <div id="detail_err" class="text-danger"></div>
					<br>
					
                    <div class="form-group">
						<div class="col-sm-6">	
							<div class="form-group">
								
								<div class="col-sm-12">
									<textarea class="form-control" id="fst_sj_memo" placeholder="<?= lang("Memo") ?>" name="fst_sj_memo" rows="5" style="resize:none"></textarea>
									<div id="fst_sj_memo_err" class="text-danger"></div>
								</div>
							</div>
	
						</div>
						<div class="col-sm-6">	
							<div class="form-group">
								<label for="sub-total" class="col-md-8 control-label"><?=lang("Total Qty")?></label>
								<div class="col-md-4" style='text-align:right'>
									<input type="text" class="form-control text-right" id="sub-total" value="0" readonly>
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
<div id="mdlDetail" class="modal fade in" role="dialog">
	<div class="modal-dialog" style="display:table;width:600px">
		<!-- modal content -->
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
			<div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Add ")?></h4>
			</div>

			<div class="modal-body">
				<div class="row">
                    <div class="col-md-12" >
                        <div style="border:1px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">
								<form id="form-detail" class="form-horizontal">
									<input type='hidden' id='fin_rec_id_items'/>
									<div class="form-group">
										<label class="col-md-3 control-label"><?=lang("Item")?> :</label>
										<div class="col-md-9">											
											<select id="fstItem" class="form-control" style="width:100%"> </select>
										</div>										
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label"><?=lang("Unit")?> :</label>	
										<div class="col-md-9">									
											<label id="fstUnit" class="control-label"><?=lang("Unit")?></label>
											<label class="control-label"> => </label>
											<label id="fdcConvBasicUnit" class="control-label"><?=lang("Unit")?></label>
											<label id="fstBasicUnit" class="control-label"><?=lang("Unit")?></label>
										</div>
										
									</div>

									<div class="form-group">
										<label for="fdbQty" class="col-md-3 control-label"><?=lang("Qty")?> :</label>
										<div class="col-md-9">
											<input type='TEXT' id="fdbQty" class="money form-control" value="1"/>
										</div>
									</div>								

									<div class="form-group batchNoBlock">
										<label for="" class="col-md-3 control-label"><?=lang("Batch Number")?> :</label>
										<div class="col-md-9">
											<select  id="fstBatchNo" class="form-control"></select>
										</div>
									</div>

									<div class="form-group serialNoBlock">
										<label for="" class="col-md-3 control-label"><?=lang("Serial Number")?> :</label>
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
											<label for="" class=""><?=lang("Total Serial")?> :</label>
											<label id="ttlSerial" class="">0</label>

										</div>
										<div class="col-md-5 text-right" >
											<button id="btn-delete-serial" class="btn btn-primary btn-xs">Delete Selected Serial</button>
										</div>
									</div>

								</form>
								
								<div class="modal-footer">
									<button id="btn-save-detail" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Add")?></button>
									<button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var availableSerialList =[];

		$("#fstItem").change(function(e){
			e.preventDefault();
			alert("Item Change");
			//var data = $('#fstItem').find(':selected');
			var data = $('#fstItem').select2('data');
			data = data[0];			
			$("#fstUnit").text(data.fst_unit);
			$("#fdcConvBasicUnit").text(data.fdc_conv_to_basic_unit);
			$("#fstBasicUnit").text(data.fst_basic_unit);

			if (data.fbl_is_batch_number == 1){
				$(".batchNoBlock").show();
				getBatchNoList(  $("#fin_warehouse_id").val(),  $("#fstItem").val(), function(){});
			}else{
				$(".batchNoBlock").hide();
			}
			if (data.fbl_is_serial_number == 1){				
				$(".serialNoBlock").show();
			}else{				
				$(".serialNoBlock").hide();
			}
		});

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
				getSerialNoList($("#fin_warehouse_id").val(),  $("#fstItem").val(),$("#fstBatchNo").val(),function(){
					App.log(availableSerialList);
				});

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

				var idxArr =  availableSerialList.indexOf($("#fstSerialNo").val());
				if ( idxArr < 0){
					alert("<?=lang("No serial tidak dikenal !")?>");
					$("#fstSerialNo").select();
					return;
				}

				availableSerialList.splice( idxArr,1);

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
			item = $("#fstItem").select2("data");
			item = item[0];			
			
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
				var convToBasic = parseFloat($("#fdcConvBasicUnit").text());
				var qtyInUnit = parseFloat($("#fdbQty").val());
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


			t = $("#tblSJDetails").DataTable();				
			dataRow = {
				fin_rec_id:0,
				fbl_is_batch_number: item.fbl_is_batch_number,
				fbl_is_serial_number: item.fbl_is_serial_number,
				fdb_qty: $("#fdbQty").val(),
				fin_item_id: item.id,
				fin_promo_id: item.fin_promo_id,
				fin_salesorder_detail_id: item.fin_salesorder_detail_id,
				fst_custom_item_name: item.text,
				fst_item_code: item.fst_item_code,
				fst_item_name: item.fst_item_name,
				fst_unit: item.fst_unit,				
				fst_memo_item:"",
				fst_batch_number:$("#fstBatchNo").val(),
				fst_serial_number_list:arrSerial,
				fst_basic_unit : item.fst_basic_unit,
				fdc_conv_to_basic_unit : item.fdc_conv_to_basic_unit,
			}

			if (selectedDetail == null){				
				t.row.add(dataRow).draw(false);
			}else{
				selectedData = selectedDetail.data();
				dataRow.fin_rec_id = selectedData.fin_rec_id;
				selectedDetail.data(dataRow).draw(false);
			}
			App.log(dataRow);
			calculateTotal();
			$("#mdlDetail").modal("hide");

		})


		function getBatchNoList(finWarehouseId,finItemId,callback){
			availableSerialList = [];

			App.getValueAjax({
				model:"trinventory_model",
				func:"getReadyBatchNoList",
				params:[finWarehouseId,finItemId],
				wait_message: "<h5>Please wait !,trying to get batch no list....</h5>",
				callback:function(value){
					$("#fstBatchNo").empty();
					$.each(value,function(i,obj){
						batchNo = obj.fst_batch_no;
						App.addOptionIfNotExist("<option value='"+batchNo+"'>"+batchNo+"</option>","fstBatchNo");
						
					});
					$("#fstBatchNo").val(null);
					$("#fstBatchNo").select2();
					App.fixedSelect2();
					callback();					
				}
			});
			
		}

		function getSerialNoList(finWarehouseId,finItemId,fstBatchNo,callback){
			availableSerialList = [];

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
	</script>
</div>

<?php echo $mdlEditForm ?>


<script type="text/javascript" info="bind">
	$(document).bind('keydown', 'alt+d', function(){
		$("#btn-add-detail").trigger("click");
	});
</script>

<script type="text/javascript" info="define">
	var selectedDetail;
</script>
<script type="text/javascript" info="event">
	$(function(){
		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>tr/gudang/pengiriman_penjualan/add")
		});

		$("#btnSubmitAjax").click(function(e){
            e.preventDefault();
            submitAjax(0);
		})


		$("#btn-add-items").click(function(e){
			e.preventDefault();
			selectedDetail = null;
			$("#mdlDetail").modal("show");
		});
		
	});
</script>
<script type="text/javascript" info="init">
	$(function(){
		$("#fdt_sj_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");

		//Get unprocess SO
        $("#fin_salesorder_id").select2({
			dropdownAutoWidth : true,
            ajax:{
				delay:500,
                url:"<?=site_url()?>tr/gudang/pengiriman_penjualan/sel2_get_so",
                processResults: function (resp) {
                    arrData = resp.data;
                    sel2Data =[];
                    $.each(arrData,function(i,v){
                        sel2Data.push({
                            id: v.fin_salesorder_id,
                            text: v.fst_salesorder_no,
                            fdt_salesorder_datetime : v.fdt_salesorder_datetime,
                            fin_relation_id : v.fin_relation_id,
                            fst_relation_name : v.fst_relation_name,
                            fin_shipping_address_id : v.fin_shipping_address_id,
                            fst_shipping_address : v.fst_shipping_address,
                            fin_warehouse_id: v.fin_warehouse_id
                        });
                    });                    
                    return {
                        results: sel2Data
                    };
                }
            },
            templateResult:function(item){
                if (item.loading != true){
                    return $("<div style='display:inline-block;width:150px'>" + item.text + "</div>"  + "<div style='min-width:100px;display:inline-block'>" + item.fst_relation_name + "</div>");
                }else{
                    return "Loading...";
                }
            }
        }).on("select2:select",function(e){
            data = e.params.data;
            $("#fst_relation_name").val(data.fst_relation_name);            
            $("#fdt_salesorder_datetime").val(dateTimeFormat(data.fdt_salesorder_datetime)).datetimepicker("update");
            $("#fin_warehouse_id").val(data.fin_warehouse_id);

			initShippingAddress(data.fin_relation_id,function(){
				$("#fin_shipping_address_id").val(data.fin_shipping_address_id).trigger("change");
			});

            getOutstandingDetailSO(data.id,function(){

			});
		});

		$('#tblSJDetails').on('preXhr.dt', function ( e, settings, data ) {
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
				{"title" : "Custom Name","width": "300px",data:"fst_custom_item_name",visible:true,orderable:false},
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
			
			$('.xbtn-delete').confirmation({
				//rootSelector: '[data-toggle=confirmation]',
				rootSelector: '.btn-delete',
				// other options
			});	

			calculateTotal();
		}).on("click",".btn-delete",function(event){
			t = $('#tblSODetails').DataTable();
			var trRow = $(this).parents('tr');

			t.row(trRow).remove().draw();
			calculateTotal();
		}).on("click",".btn-edit",function(event){	
            t = $("#tblSJDetails").DataTable();
            tRow = $(this).parents("tr");
            selectedDetail  = t.row(tRow);
			data = t.row(tRow).data();
			
			/*
			fbl_is_batch_number: "1"
			fbl_is_serial_number: "1"
			fdb_qty: "1.00"
			fin_item_id: "63"
			fin_promo_id: "0"
			fin_salesorder_detail_id: "326"
			fst_custom_item_name: "GREEBEL 3736 PENCIL SUPER LEAD"
			fst_item_code: "040101000009"
			fst_item_name: "GREEBEL 3736 PENCIL SUPER LEAD"
			fst_unit: "CTN"
			*/
			
			//App.addOptionIfNotExist("<option value='"+data.fin_item_id+"' selected>"+ data.fst_custom_item_name +"</option>","fstItem");			
			$('#fstItem').val(data.fin_item_id).trigger("change");

			$("#fstUnit").html(data.fst_unit);			
			$("#fstBasicUnit").html(data.fst_basic_unit);
			$("#fdcConvBasicUnit").html(data.fdc_conv_to_basic_unit);
			$("#fdbQty").val(data.fdb_qty);
			$("#fstBatchNo").val(data.fst_batch_no);
			$.each(data.arr_serial,function(i,serial){
				$("#fstSerialNoList").prepend("<option value='"+serial+"'>"+serial+"</option>");
			});            
            $("#mdlDetail").modal("show");
								
        });


		App.fixedSelect2();

		//initVarForm();



	});
</script>
<script type="text/javascript" info="function">
	function submitAjax(confirmEdit){        
        data = $("#frmDeliveryOrder").serializeArray();
		detail = new Array();		

		t = $('#tblSJDetails').DataTable();
		datas = t.data();
		var isValidData = true;
		$.each(datas,function(i,v){
			App.log(v);
			if ((v.fbl_is_batch_number == "1") && (v.fst_batch_number == null || v.fst_batch_number =="")){
				isValidData = false;
				alert ("Batch number " + v.fst_custom_item_name  + " tidak boleh kosong !");
				return false;
			}
			if ((v.fbl_is_serial_number == 1) && (v.fst_serial_number_list == null || v.fst_serial_number_list =="")){
				isValidData = false;
				alert ("Serial number " + v.fst_custom_item_name  + " tidak boleh kosong !");
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
	   
		if ($("#fin_sj_id").val() == 0){
			url = "<?=site_url()?>tr/gudang/pengiriman_penjualan/ajx_add_save";
		}else{
			url = "<?=site_url()?>tr/gudang/pengiriman_penjualan/ajx_edit_save";
		}
		

		App.blockUIOnAjaxRequest("<h5>Please wait....</h5>");
		//$.blockUI({ message:"<h5>Please wait....</h5>"});
        $.ajax({
            url : url,
            data: data,
            method: "POST",
        }).done(function(resp){
			//$.unblockUI();
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
					$("#fin_sj_id").val(data.insert_id);

					//Clear all previous error
					$(".text-danger").html("");
					// Change to Edit mode
					$("#frm-mode").val("EDIT");  //ADD|EDIT
					$('#fst_sj_no').prop('readonly', true);
					$("#tabs-so-detail").show();
				}
        }).always(function(resp){
			//$.unblockUI();
		});

	}
	
	function initShippingAddress(relationId,callback){
        $.ajax({
            url:"<?=site_url()?>select_data/get_shipping_address/" + relationId,
        }).done(function(resp){
            arrData = resp.data;

            sel2DataShippingAddress = [];
            $.each(arrData,function(i,v){
                sel2DataShippingAddress.push({
                    id: v.fin_shipping_address_id,
                    text: v.fst_name,
                    fst_shipping_address: v.fst_shipping_address
                })
            });
            $("#fin_shipping_address_id").select2({
                data:sel2DataShippingAddress
            }).on("change",function(e){
				//data = e.params.data;
				data = $('#fin_shipping_address_id').select2('data')[0];				
				App.log(data);
                $("#fst_shipping_address").val(data.fst_shipping_address);
			});
			$("#fin_shipping_address_id").val(null).trigger("change.select2");

			App.fixedSelect2();
			
			if(typeof callback == "function"){
				callback();
			}            
        });

    }

	function initVarForm(){
		blockUIOnAjaxRequest();
        $.ajax({
            url:"<?=site_url()?>tr/delivery_order/initVarForm"
        }).done(function(resp){
            data = resp.data;
            $("#fin_warehouse_id").select2({
                data:data.arrWarehouse
            });
            $("#fin_driver_id").select2({
                data:data.arrDriver
			});
			
			<?php if($mode == "EDIT"){?>
			initForm();
			<?php } ?>
            
        });
    }

	function initForm(){
		
		$.ajax({
			url:"<?= site_url() ?>tr/delivery_order/fetch_data/" + $("#fin_sj_id").val(),
		}).done(function(resp){
			dataH = resp.sj;

			$("#fst_sj_no").val(dataH.fst_sj_no);
			$("#fst_sj_no").val(dataH.fst_sj_no);
			$("#fdt_sj_datetime").val(dateTimeFormat(dataH.fdt_sj_datetime));

			var newOption = new Option( dataH.fst_salesorder_no, dataH.fin_salesorder_id, true, true);
			$('#fin_salesorder_id').append(newOption).trigger('change');
			$("#fdt_salesorder_date").val(dateTimeFormat(dataH.fdt_salesorder_date));
			$("#fst_relation_name").val(dataH.fst_relation_name);

			initShippingAddress(dataH.fin_relation_id,dataH.fin_shipping_address_id);
						
			$("#fin_driver_id").val(dataH.fin_driver_id).trigger("change");
			$("#fst_no_polisi").val(dataH.fst_no_polisi);
			$("#fin_warehouse_id").val(dataH.fin_warehouse_id).trigger("change");
			$("#fst_sj_memo").val(dataH.fst_sj_memo);
			

			isHold = (dataH.fbl_is_hold == 0) ? false : true;			
			$("#fbl_is_hold").prop('checked', isHold);
			
			details = resp.sj_details;
			t = $("#tblSJDetails").DataTable(); 
			$.each(details,function(i,v){				
				t.row.add(v);				
			});
			t.draw();
			//$("#fin_shipping_address_id").val(dataH.fin_shipping_address_id).trigger("change");

		});
	}

    function getOutstandingDetailSO(finSalesOrderId,callback){
        $.ajax({
            url:"<?=site_url()?>tr/gudang/pengiriman_penjualan/get_detail_so/" + finSalesOrderId,
        }).done(function(resp){
            arrData = resp.data;
			t = $("#tblSJDetails").DataTable();            
			var dataTable =[];
			var itemList = [];
            $.each(arrData,function(i,v){				
				dataRow = {
					fbl_is_batch_number: v.fbl_is_batch_number,
					fbl_is_serial_number: v.fbl_is_serial_number,
					fdb_qty: v.fdb_qty,
					fin_item_id: v.fin_item_id,
					fin_promo_id: v.fin_promo_id,
					fin_salesorder_detail_id: v.fin_salesorder_detail_id,
					fst_custom_item_name: v.fst_custom_item_name,
					fst_item_code: v.fst_item_code,
					fst_item_name: v.fst_item_name,
					fst_unit: v.fst_unit,
					fin_rec_id:0,
					fst_memo_item:"",
					fst_batch_number:null,
					fst_serial_number_list:null,
					fst_basic_unit : v.fst_basic_unit,
            		fdc_conv_to_basic_unit : v.fdc_conv_to_basic_unit,
				}
				dataTable.push(dataRow); 
				
				itemList.push({
					fin_salesorder_detail_id: v.fin_salesorder_detail_id,
					id: v.fin_item_id,
					text:v.fst_custom_item_name,
					fin_promo_id: v.fin_promo_id,
					fst_item_code: v.fst_item_code,
					fst_item_name: v.fst_item_name,
					fst_unit: v.fst_unit,
					fdb_qty: v.fdb_qty,
					fbl_is_batch_number: v.fbl_is_batch_number,
					fbl_is_serial_number: v.fbl_is_serial_number,
					fst_basic_unit : v.fst_basic_unit,
					fdc_conv_to_basic_unit : v.fdc_conv_to_basic_unit,									
				});
			
			});

			t.rows.add(dataTable).draw(false);
			$("#fstItem").select2({
				data:itemList
			});

			$("#fstItem").val(null).trigger("change.select2");
			App.fixedSelect2();			
			callback();
        });
    }

    function calculateTotal(){
        t = $("#tblSJDetails").DataTable();
        datas = t.rows().data();
        consoleLog(datas);
        ttl = 0;
        $.each(datas,function(i,v){
            ttl += money_parse(v.fdb_qty);
        })
		$("#sub-total").val(money_format(ttl));
		
		selectedDetail = null;
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
	function calculateTotalSerialNo(){
		$("#ttlSerial").text($("#fstSerialNoList option").length);
	}

</script>



<script type="application/json">

$(function(){		
		

        $(".unfocus").focus(function(){
            $(this).blur();
        });

        

	
		

		


		

        fixedSelect2();
        $("#fst_shipping_address").click(function(e){
            calculateTotal();
        })
        

		

		$("#btnDelete").confirmation({
			title:"<?=lang("Hapus data ini ?")?>",
			rootSelector: '#btnDelete',
			placement: 'left',
		});
		$("#btnDelete").click(function(e){
			e.preventDefault();
			blockUIOnAjaxRequest("<h5>Deleting ....</h5>");
			$.ajax({
				url:"<?= site_url() ?>tr/delivery_order/delete/" + $("#fin_sj_id").val(),
			}).done(function(resp){
				//consoleLog(resp);
				$.unblockUI();
				if (resp.message != "")	{
					$.alert({
						title: 'Message',
						content: resp.message,
						buttons : {
							OK : function() {
								if (resp.status == "SUCCESS") {
									window.location.href = "<?= site_url() ?>tr/delivery_order/lizt";
									//return;
								}
							},
						}
					});
				}

				if(resp.status == "SUCCESS") {
					data = resp.data;
					$("#fin_sj_id").val(data.insert_id);

					//Clear all previous error
					$(".text-danger").html("");
					// Change to Edit mode
					$("#frm-mode").val("EDIT");  //ADD|EDIT
					$('#fst_sj_no').prop('readonly', true);
				}
			});
		});

		$("#btnList").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>tr/delivery_order/lizt");
		});
    });
    
    
</script>


<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
