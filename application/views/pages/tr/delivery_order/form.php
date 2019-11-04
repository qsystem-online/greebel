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
						
						<label for="fdt_sj_date" class="col-md-2 control-label"><?=lang("Delivery Order Date")?> *</label>
						<div class="col-md-4">
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control text-right datetimepicker" id="fdt_sj_date" name="fdt_sj_date"/>								
							</div>
							<div id="fdt_sj_date_err" class="text-danger"></div>
							<!-- /.input group -->
						</div>						
                    </div>

					<div class="form-group">						
						<label for="fin_salesorder_id" class="col-md-2 control-label"><?=lang("Sales Order")?> :</label>
						<div class="col-md-4">
							<select id="fin_salesorder_id" class="form-control" name="fin_salesorder_id"></select>
							<div id="fin_salesorder_id_err" class="text-danger"></div>
						</div>
					
						<label for="" class="col-md-2 control-label"><?=lang("Tanggal SO")?> :</label>
						<div class="col-md-4">
                            <div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="unfocus form-control text-right" id="fdt_salesorder_date" />								
							</div>
						</div>
					</div>

					<div class="form-group">						
						<label for="select-relations" class="col-md-2 control-label"><?=lang("Customer")?> :</label>
						<div class="col-md-4">
							<input type="TEXT" class="form-control unfocus" id="fst_relation_name" />
							<div id="fin_relation_id_err" class="text-danger"></div>
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
						
						<label for="fin_driver_id" class="col-md-2 control-label"><?=lang("Sopir")?> :</label>
						<div class="col-md-4">
							<select id="fin_driver_id" class="form-control" name="fin_driver_id">
								<option value="">-- <?=lang("select")?> --</option>
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
						<div class="col-md-4">
							<select id="fin_warehouse_id" class="form-control" name="fin_warehouse_id">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="fin_warehouse_id_err" class="text-danger"></div>
						</div>
						<div class="checkbox col-sm-6">
							<label><input id="fbl_is_hold" type="checkbox" name="fbl_is_hold" value="1"><?= lang("Hold Pengiriman") ?></label>							
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

<script type="text/javascript">
var selectedDetail;
$(function(){		
		initVarForm();
	
		//$("#fdt_sj_date").datetimepicker('update'), dateFormat("<= date("Y-m-d")?>"));
		$("#fdt_sj_date").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>"));
		$("#fdt_sj_date").datetimepicker('update');

        $(".unfocus").focus(function(){
            $(this).blur();
        });

        //Get unprocess SO
        $("#fin_salesorder_id").select2({
            ajax:{
                url:"<?=site_url()?>tr/delivery_order/sel2_get_so",
                processResults: function (resp) {
                    arrData = resp.data;
                    sel2Data =[];
                    $.each(arrData,function(i,v){
                        sel2Data.push({
                            id: v.fin_salesorder_id,
                            text: v.fst_salesorder_no,
                            fdt_salesorder_date : v.fdt_salesorder_date,
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
                    return $("<div class='row'><div class='col-md-5'>" + item.text + "</div>"  + "<div class='col-md-7'>" + item.fst_relation_name + "</div></div>");
                }else{
                    return "Loading...";
                }
            }
        }).on("select2:select",function(e){
            data = e.params.data;
            data = Array.isArray(data) ? data[0] : data;      
            $("#fst_relation_name").val(data.fst_relation_name);
            
            //$("#fdt_salesorder_date").datepicker('update', dateFormat(data.fdt_salesorder_date));
            $("#fdt_salesorder_date").val(dateTimeFormat(data.fdt_salesorder_date));   
            $("#fin_warehouse_id").val(data.fin_warehouse_id).trigger("change");
            
            initShippingAddress(data.fin_relation_id,data.fin_shipping_address_id);
            getDetail();
        });

	
		$(document).bind('keydown', 'alt+d', function(){
			//alert("TEST COPY");
			$("#btn-add-detail").trigger("click");
		});

		$('#tblSJDetails').on('preXhr.dt', function ( e, settings, data ) {
		 	//add aditional data post on ajax call
		 	data.sessionId = "TEST SESSION ID";
		}).DataTable({
			scrollX: true,
            ordering: true,
			columns:[
				{"title" : "id","width": "5%",data:"fin_rec_id",visible:true},
				{"title" : "Items","width": "20%",orderable:false,data:"fin_item_id",
					render: function(data,type,row){
						return row.fst_item_code + "-" + row.fst_custom_item_name;
					}
				},
				{"title" : "Custom Name","width": "32%",data:"fst_custom_item_name",visible:true,orderable:false},
				{"title" : "Qty","width": "5%",data:"fdb_qty",className:'text-right',orderable:false,},
				{"title" : "Unit","width": "10%",data:"fst_unit",orderable:false,},
				{"title" : "Memo","width": "20%",data:"fst_memo_item",orderable:false,},
                {"title" : "Action","width": "8%",className:'dt-body-center text-center',orderable:false,
                    render: function( data, type, row, meta ) {
                        consoleLog();
                        if (row.fin_promo_id == "0"){
                            return "<div style='font-size:16px'><a class='btn-edit' href='#'><i class='fa fa-pencil'></i></a><a class='btn-delete' href='#'><i class='fa fa-trash'></i></a></div>";
                        }else{                            
                            return "<div style='font-size:16px'><a class='btn-delete' href='#'><i class='fa fa-trash'></i></a></div>";
                        }
                        
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


		$("#tblSJDetails").on("click",".btn-delete",function(event){
			t = $('#tblSODetails').DataTable();
			var trRow = $(this).parents('tr');

			t.row(trRow).remove().draw();
			calculateTotal();
		});

		$("#tblSJDetails").on("click",".btn-edit",function(event){	
            t = $("#tblSJDetails").DataTable();

            tRow = $(this).parents("tr");
            selectedDetail  = t.row(tRow);
            data = t.row(tRow).data();
            $("#frm-item").val(data.fst_item_code + " - " + data.fst_custom_item_name);
            $("#frm-unit").val(data.fst_unit);
            $("#fdb_qty").val(data.fdb_qty);
            $("#fst_memo_item").val(data.fst_memo_item);
            
            $("#myModal").modal({
				backdrop:"static",
			});
								
        });

        fixedSelect2();
        $("#fst_shipping_address").click(function(e){
            calculateTotal();
        })
        $("#btnSubmitAjax").click(function(e){
            e.preventDefault();
            submitAjax();
        })

		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>tr/delivery_order/add")
		});

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
    
    function initShippingAddress(relationId,defaultValue){
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
            }).on("select2:select",function(e){
                data = e.params.data;
                $("#fst_shipping_address").val(data.fst_shipping_address);
            }).on("change",function(e){
                data = $("#fin_shipping_address_id").select2("data")[0];
				//consoleLog(data);
                //$("#fst_shipping_address").val(data.fst_shipping_address);
            });
            if (defaultValue != 0){
                $("#fin_shipping_address_id").val(defaultValue).trigger("change");
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
			$("#fdt_sj_date").val(dateTimeFormat(dataH.fdt_sj_date));

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

    function getDetail(){
        $.ajax({
            url:"<?=site_url()?>tr/delivery_order/get_detail_so/" + $("#fin_salesorder_id").val(),
        }).done(function(resp){
            arrData = resp.data;
            t = $("#tblSJDetails").DataTable();            
            $.each(arrData,function(i,v){
                v.fin_rec_id = 0;
                v.fst_memo_item ="";
                t.row.add(v);
            });
            t.draw();

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
    }
</script>

<Script type="text/javascript">
    function submitAjax(){
        
        data = $("#frmDeliveryOrder").serializeArray();
		detail = new Array();		

		t = $('#tblSJDetails').DataTable();
		datas = t.data();
		$.each(datas,function(i,v){
			detail.push(v);
		});
		data.push({
			name:"detail",
			value: JSON.stringify(detail)
		});
	   
		url = "<?=site_url()?>tr/delivery_order/ajx_add_save";
		<?php if ($mode == "EDIT"){ ?>
			url = "<?=site_url()?>tr/delivery_order/ajx_edit_save";
		<?php } ?>

		blockUIOnAjaxRequest("<h5>Please wait....</h5>");

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
</Script>

<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
