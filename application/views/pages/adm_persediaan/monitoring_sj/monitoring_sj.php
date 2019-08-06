<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<section class="content-header">
<h1><?=lang("Delivery Order")?><small><?=lang("form")?></small></h1>
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
				    <h3 class="box-title title"><?=$title?></h3>
			    </div>

            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false"><label>Delivery Order Monitoring</label></a></li>
                        <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><label>Histories</label></a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
						<div align="right">					
							<span>Search on:</span>
							<span>
								<select id="selectSearch" class="filterData" name="selectSearch" style="width: 148px;background-color:#e6e6ff;padding:8px;margin-left:6px;margin-bottom:6px">
									<option value="1"><?=lang("Customer")?></option>
									<option value="2"><?=lang("Nomor S/J")?></option>
								</select>
							</span>
						</div>
                            <table id="tblMonitoring" class="display nowrap" style="width:100%"></table>
                        </div> <!-- /.tab-pane -->

                        <div class="tab-pane" id="tab_2">
                            <div class="col-md-12 form-group">
                                <label for="start-from" class="col-md-1 control-label"><?= lang("Date")?>: &nbsp;</label>
                                <div class="col-md-2">
                                    <input type="text" class="form-control datepicker filterData" id="start-date">
                                </div>
                                
                                <label for="end-to" class="col-md-1 control-label" style=""><?= lang("s/d")?>: &nbsp;</label>
                                <div class="col-md-2">
                                    <input type="text" class="form-control datepicker filterData" id="end-date">
                                </div>
                            </div>
                            <table id="tblHistory" class="display nowrap" style="width:100%"></table>
                        </div><!-- /.tab-pane -->
                                            
                    </div> <!-- /.tab-content -->                    
                </div>
            </div>
            <!-- end box header -->
        </div>
    </div>
</section>

<div id="resiModal" class="modal fade" role="dialog">
	<div class="modal-dialog" style="display:table;width:35%;min-width:350px;max-width:100%">	
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Update Resi")?></h4>
			</div>

			<div class="modal-body">
				<form  class="form-horizontal">
					<div class="form-group">
						<label for="fin_sj_id" class="col-md-4 control-label"><?=lang("S/J ID")?></label>
						<div class="col-md-8">
							<input type="text" class="form-control text-right" id="fin_sj_id" name="fin_sj_id">
							<div id="fin_sj_id_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="fst_sj_no" class="col-md-4 control-label"><?=lang("S/J No.")?></label>
						<div class="col-md-8">
							<input type="text" class="form-control text-right" id="fst_sj_no" name="fst_sj_no" readonly>
							<div id="fst_sj_no_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="fst_sj_return_resi_no" class="col-md-4 control-label"><?=lang("S/J Resi No.")?></label>
						<div class="col-md-8">
							<input type="text" class="form-control text-right" id="fst_sj_return_resi_no" name="fst_sj_return_resi_no">
							<div id="fst_sj_return_resi_no_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
					<label for="fdt_sj_return_datetime" class="col-md-4 control-label"><?=lang("S/J Return Date")?></label>
						<div class="col-md-8">
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control pull-right datepicker" id="fdt_sj_return_datetime" name="fdt_sj_return_datetime"/>								
							</div>
							<div id="fdt_sj_return_datetime_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="fst_sj_return_memo" class="col-md-4 control-label"><?=lang("S/J Return Memo")?></label>
						<div class="col-md-8">
							<input type="text" class="form-control text-right" id="fst_sj_return_memo" name="fst_sj_return_memo">
							<div id="fst_sj_return_memo_err" class="text-danger"></div>
						</div>
					</div>

					<div class="form-group">
						<label for="fin_sj_return_by_id" class="col-md-4 control-label"><?=lang("S/J Return By Id")?></label>
						<div class="col-md-8">
							<input type="text" class="form-control text-right" id="fin_sj_return_by_id" name="fin_sj_return_by_id" readonly>
							<div id="fin_sj_return_by_id_err" class="text-danger"></div>
						</div>
					</div>
				</form>
			</div>

			<div class="modal-footer">
				<button id="btn-resi" type="button" class="btn btn-primary" ><?=lang("Update")?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?=lang("Close")?></button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function(){
		$("#tblMonitoring").DataTable({
			//"scrollX": true,
			ajax: {
				url:"<?=site_url()?>adm_persediaan/monitoring_sj/fetch_monitoring_list",
			},
			columns:[
                {"title" : "S/J ID","width": "10%",sortable:true,data:"fin_sj_id",visible:true},
				{"title" : "S/J No","width": "20%",sortable:true,data:"fst_sj_no",visible:true},
                {"title" : "S/J Date","width": "20%",sortable:true,data:"fdt_sj_date",visible:true},
				{"title" : "S/O No","width": "20%",sortable:true,data:"fst_salesorder_no",visible:true},
				{"title" : "S/O Date","width": "20%",sortable:true,data:"fdt_salesorder_date",visible:true},
                {"title" : "Gudang","width": "20%",sortable:true,data:"fst_warehouse_name",visible:true},
				{"title" : "Customer","width": "25%",sortable:true,data:"fst_relation_name",visible:true},
                {"title" : "Hold","width": "10%",sortable:true,data:"fbl_is_hold",visible:true},
                {"title" : "Return Date","width": "20%",sortable:true,data:"fdt_sj_return_datetime",visible:true},
                //{"title" : "S/J Resi No","width": "20%",sortable:true,data:"fst_sj_return_resi_no",visible:true},
				{"title" : "S/J Resi No","width": "20%",sortable:true,data:"fst_sj_return_resi_no",visible:true,placeholder:"Update",className:'btn-resi'},
				{"title" : "S/J Return Memo","width": "20%",sortable:true,data:"fst_sj_return_memo",visible:true},
                {"title" : "S/J Return By ID","width": "20%",sortable:true,data:"fin_sj_return_by_id",visible:true},
				{"title" : "Unhold Date","width": "20%",sortable:true,data:"fdt_unhold_datetime",visible:true},
				{"title" : "Unhold","width": "10%",sortable:false,className:'dt-body-center text-center',
					render: function(data,type,row){
						return "<a class='btn-unhold' href='#'><i class='fa fa-pause-circle'></i></a>";
					}
				},
			],
			dataSrc:"data",
			processing: true,
			serverSide: true,
			scrollX: true,
		});

		$("#tblMonitoring").on("click",".btn-unhold",function(e){
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

		$("#tblMonitoring").on("click",".btn-resi",function(e){
			e.preventDefault();
			t = $('#tblMonitoring').DataTable();
			var trRow = $(this).parents('tr');
			update_sj = t.row(trRow);
			row = update_sj.data();
            $("#resiModal").modal('show');
			
			$('#fin_sj_id').val(row.fin_sj_id);
			$('#fst_sj_no').val(row.fst_sj_no);
		});
		
	});

	function doUnhold(element){
		t = $('#tblMonitoring').DataTable();
		var trRow = element.parents('tr');
		data = t.row(trRow).data();
		console.log(data);

		$.ajax({
			url:"<?= site_url() ?>adm_persediaan/monitoring_sj/doUnhold/" + data.fin_sj_id,
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
			/*if (resp.status == "SUCCESS") {
				//remove row
				trRow.remove();
			}*/
		});
	}

	function doUpdate(element){
		t = $('#tblMonitoring').DataTable();
		var trRow = element.parents('tr');
		data = t.row(trRow).data();
		console.log(data);

		$.ajax({
			url:"<?= site_url() ?>adm_persediaan/monitoring_sj/doUpdate/" + data.fin_sj_id,
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
			/*if (resp.status == "SUCCESS") {
				//remove row
				trRow.remove();
			}*/
		});
	}

</script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>