<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<section class="content-header">
<h1><?=lang("Unhold Sales Order")?><small><?=lang("List")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Tools") ?></a></li>
		<li class="active title"><?=$title?></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title"><?=$title?></h3>
				</div>			
			<!-- /.box-header -->
			<div class="box-body">
				<div class="table">
					<div style="margin-bottom:5px;margin-top:20px">
						<div style="float:right">
							<label class="control-label">Search by :</label>
							<select id="selectSearch" class="form-control"  style="display:inline;width:148px">
								<option value='fin_salesorder_id'>Sales Order ID</option>
								<option value='fst_salesorder_no'>Sales Order No</option>
							</select>
						</div>
						<div style="clear:both"></div>
					</div>
					<table id="tblUnhold" style="width:100%"></table>
				</div>
			</div>
			<!-- /.box-body -->	
		</div>
	</div>
</section>

<script type="text/javascript">
	$(function(){
		$(".filterData").change(function(event){
			event.preventDefault();
			$('#tblUnhold').DataTable().ajax.reload();
		});

		$('#tblUnhold').on('preXhr.dt', function(e, settings, data){
			data.optionSearch = $('#selectSearch').val();
		}).DataTable({
			ajax: {
				url:"<?=site_url()?>tr/sales_order/unhold_fetch_list_data",
			},
			columns:[
				{"title" : "Sales Order ID","width": "13%",sortable:true,data:"fin_salesorder_id",visible:true},
				{"title" : "Sales Order No","width": "12%",sortable:true,data:"fst_salesorder_no",visible:true},
				{"title" : "Sales Order Date","width": "15%",sortable:true,data:"fdt_salesorder_datetime",visible:true},
				{"title" : "Customer","width": "15%",sortable:true,data:"fst_relation_name",visible:true},
				{"title" : "Memo","width": "15%",sortable:true,data:"fst_memo",visible:true},
				{"title" : "Unhold Date","width": "15%",sortable:true,data:"fdt_unhold_datetime",visible:false},
				{"title" : "Unhold","width": "10%",sortable:false,className:'dt-body-center text-center',
					render: function(data,type,row){
						return "<a class='btn-unhold' href='#'><i class='fa fa-pause-circle'></i></a>";
					}
				},
			],
			dataSrc:"data",
			processing: true,
			serverSide: true,
		});

		$("#tblUnhold").on("click",".btn-unhold",function(e){
			e.preventDefault();
			$(this).confirmation({
				title:" Unhold ?",
				rootSelector: '.btn-unhold',
				onConfirm:function() {
					
					doUnhold($(this));
				}
			});
			$(this).confirmation("show");
		});
		
	});

	function doUnhold(element){
		t = $('#tblUnhold').DataTable();
		var trRow = element.parents('tr');
		data = t.row(trRow).data();
		console.log(data);

		$.ajax({
			url:"<?= site_url() ?>tr/sales_order/doUnhold/" + data.fin_salesorder_id,
		}).done(function(resp){
			if (resp.message != "") {
				$.alert({
					title: 'Message',
					content: resp.message,
					buttons: {
						OK : function(){
							if (resp.status == "SUCCESS"){
								//window.location.href = "<?= site_url() ?>tr/sales_order/lizt";
								return;
							}
						},
					}
				});
			}
			if (resp.status == "SUCCESS") {
				//remove row
				trRow.remove();
			}
		});
	}
</script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>