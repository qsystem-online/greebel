<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?=base_url()?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">



<section class="content-header">
	<h1><?=lang("Sales Order")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Sales Order") ?></a></li>
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
						<a id="btnPromo" class="btn btn-primary" href="#" title="<?=lang("Cek Promo")?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
						<a id="btnSubmitAjax" class="btn btn-primary" href="#" title="<?=lang("Simpan")?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
						<a id="btnPrint" class="btn btn-primary" href="#" title="<?=lang("Cetak")?>"><i class="fa fa-print" aria-hidden="true"></i></a>
						<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
						<a id="btnClose" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
					</div>					
				</div>
				<!-- end box header -->
				<!-- form start -->
				<form id="frmSalesOrder" class="form-horizontal" action="<?=site_url()?>tr/sales_order/add" method="POST" enctype="multipart/form-data">			
					<div class="box-body">
						<table id="tblItemsPromo" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>

					</div>
					<!-- end box body -->
					<div class="box-footer text-right">						
					</div>
					<!-- end box-footer -->
				</form>
        	</div>
    	</div>
	</div>
</section>




<script type="text/javascript" info="binding_key">
	$(function(){
		$(document).bind('keydown', 'alt+d', function(){
			$("#btn-add-detail").trigger("click");
		});
	});
</script>

<script type="text/javascript" info="define">	
	var tblItemsPromo;
</script>

<script type="text/javascript" info="init">
	$(function(){
		
			

		tblItemsPromo = $('#tblItemsPromo').on('preXhr.dt', function ( e, settings, data ) {
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
				{"title" : "promo",sortable:false,data:"fin_promo_id",visible:true},				
				{"title" : "Promo","width": "320px",sortable:false,data:"fst_promo_name"},
				{"title" : "Start","width": "0px",sortable:false,data:"fdt_start"},
				{"title" : "End","width": "0px",sortable:false,data:"fdt_end"},
				{"title" : "Action","width": "60px",sortable:false,className:'dt-body-center text-center',
					render:function(data,type,row){
						var action = '<a class="btn-edit" href="#" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp;';
						//action += '<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
						if (row.fin_promo_id == 0){
							return action;
						}else{
							return "";
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
		}).on('draw',function(){
			$(".dataTables_scrollHeadInner").css("min-width","100%");
			$(".dataTables_scrollHeadInner > table").css("min-width","100%");	
			calculateTotal();		
		}).on("click",".btn-delete",function(event){
			t = $('#tblSODetails').DataTable();
			var trRow = $(this).parents('tr');

			t.row(trRow).remove().draw();
			calculateTotal();
		}).on("click",".btn-edit",function(event){
			event.preventDefault();					
			t = tblDetails;
			var trRow = $(this).parents('tr');			
			selectedRow = t.row(trRow);
			myModal.show();
		});

		//init_form();
		App.fixedSelect2();		
	});
</script>

<script type="text/javascript" info="event">
	$(function(){

		

	});
</script>

<script type="text/javascript" info="function">	

	function saveAjax(confirmEdit){
		
		

	}

	

	function init_form(){
		if ( $("#fin_salesorder_id").val() != 0 ){
			//fetch data
			App.blockUIOnAjaxRequest();
			$.ajax({
				url:"<?=site_url()?>tr/sales_order/fetch_data/" + $("#fin_salesorder_id").val(),				
			}).done(function(resp){
				if(resp.message != ""){
					alert(resp.message);
				}
				if (resp.status == "SUCCESS"){
					//Fill Forms
					dataH = resp.data.sales_order;
					detailData = resp.data.so_details;					
					App.autoFillForm(dataH);
					$("#fdt_salesorder_datetime").val(dateTimeFormat(dataH.fdt_salesorder_datetime)).datetimepicker("update");	
					App.addOptionIfNotExist("<option value='"+ dataH.fin_relation_id +"' selected>" + dataH.fst_relation_name + "</option>","fin_relation_id");

					App.addOptionIfNotExist("<option value='"+ dataH.fin_shipping_address_id +"' selected>" + dataH.fst_address_name + "</option>","fin_shipping_address_id");
					$("#fst_shipping_address").val(dataH.fst_shipping_address);
					$("#fin_sales_id").trigger("change");
					
					details = [];
					$.each(detailData , function(i,detail){
						data = {
							fin_rec_id: detail.fin_rec_id,
							fin_promo_id:detail.fin_promo_id,
							fin_item_id:detail.fin_item_id,
							fst_item_name:detail.fst_item_name,
							fst_item_code:detail.fst_item_code,
							fst_custom_item_name:detail.fst_custom_item_name,
							fst_max_item_discount:detail.fst_max_item_discount,
							fdb_qty: detail.fdb_qty,
							fst_unit: detail.fst_unit,
							fdc_price : detail.fdc_price,
							fst_disc_item : detail.fst_disc_item,
							fdc_disc_amount_per_item: detail.fdc_disc_amount_per_item,
							fst_memo_item: detail.fst_memo_item,
							real_stock: detail.real_stock,
							marketing_stock: detail.marketing_stock,
							fst_basic_unit: detail.fst_basic_unit,
							fdc_conv_to_basic_unit: detail.fdc_conv_to_basic_unit,							
						}
						details.push(data);
					});
					t = tblDetails;
					t.rows.add(details).draw(false);
					calculateTotal();
				}
			})
		}


	}	

	
</script>

<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
