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
                        <div class="tab-pane active" id="tab_1" style="overflow-x:auto;">
						<div align="right">					
							<span>Search on:</span>
							<span>
								<select id="selectSearch" class="filterData" name="selectSearch" style="width: 148px;background-color:#e6e6ff;padding:8px;margin-left:6px;margin-bottom:6px">
									<option value="1"><?=lang("Customer")?></option>
									<option value="2"><?=lang("Nomor S/J")?></option>
								</select>
							</span>
						</div>
                            <table id="tblMonitoring" style="width:100%"></table>
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
                            <table id="tblHistMonitoring" style="width:100%"></table>
                        </div><!-- /.tab-pane -->
                                            
                    </div> <!-- /.tab-content -->                    
                </div>
            </div>
            <!-- end box header -->
        </div>
    </div>
</section>

<script type="text/javascript">
	$(function(){
		$("#tblMonitoring").DataTable({
			ajax: {
				url:"<?=site_url()?>adm_persediaan/monitoring_sj/fetch_monitoring_list",
			},
			columns:[
                {"title" : "S/J ID","width": "10%",sortable:true,data:"fin_sj_id",visible:true},
				{"title" : "S/J No","width": "15%",sortable:true,data:"fst_sj_no",visible:true},
                {"title" : "S/J Date","width": "15%",sortable:true,data:"fdt_sj_date",visible:true},
				{"title" : "S/O No","width": "15%",sortable:true,data:"fst_salesorder_no",visible:true},
				{"title" : "S/O Date","width": "15%",sortable:true,data:"fdt_salesorder_date",visible:true},
                {"title" : "Gudang","width": "15%",sortable:true,data:"fst_warehouse_name",visible:true},
				{"title" : "Cust","width": "20%",sortable:true,data:"fst_relation_name",visible:true},
                {"title" : "Hold","width": "5%",sortable:true,data:"fbl_is_hold",visible:true},
                {"title" : "Return Date","width": "15%",sortable:true,data:"fdt_sj_return_datetime",visible:true},
                {"title" : "S/J Resi No","width": "20%",sortable:true,data:"fst_sj_return_resi_no",visible:true},
				{"title" : "S/J Return Memo","width": "15%",sortable:true,data:"fst_sj_return_memo",visible:true},
                {"title" : "S/J Return By ID","width": "15%",sortable:true,data:"fin_sj_return_by_id",visible:true},
				{"title" : "Unhold Date","width": "0%",sortable:true,data:"fdt_unhold_datetime",visible:false},
				{"title" : "Unhold","width": "15%",sortable:false,className:'dt-body-center text-center',
					render: function(data,type,row){
						return "<a class='btn-unhold' href='#'><i class='fa fa-pause-circle'></i></a>";
					}
				},
			],
			dataSrc:"data",
			processing: true,
			serverSide: true,
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