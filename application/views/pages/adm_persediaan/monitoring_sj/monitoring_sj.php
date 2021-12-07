<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<section class="content-header">
<h1><?=lang("Delivery Order Monitoring")?><small><?=lang("list")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Monitoring") ?></a></li>
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
                        <li class="nav-item nav-link active">
						<a id="tab1" href="#tab_1" data-toggle="tab" aria-expanded="false"><label>Monitoring Surat Jalan</label></a></li>
                        <li class="nav-item nav-link">
						<a id="tab2" href="#tab_2" data-toggle="tab" aria-expanded="false"><label>Histories</label></a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
						<div style="margin-bottom:5px;margin-top:20px">						
							<div style="float:right">
								<label class="control-label">Search on:</label>
								<select id="selectSearch" class="form-control" style="display:inline;width:148px;background-color:#e6e6ff;padding:8px;margin-left:6px;margin-bottom:6px">
									<option value='a.fst_relation_name'>Customer</option>
									<option value='a.fst_sj_no'>Surat Jalan No.</option>
									<option value='a.fst_sj_type'>Tipe</option>
								</select>
							</div>
							<div style="clear:both"></div>
						</div>
                            <table id="tblMonitoring" class="display table-bordered table-hover table-striped nowrap row-border" style="width:100%"></table>
                        </div> <!-- /.tab-pane -->

                        <div class="tab-pane fade" id="tab_2">
						<div style="margin-bottom:5px;margin-top:20px">
							<div style="float:right">						
								<label class="control-label">Search on:</label>
								<select id="selectSearch2" class="form-control" style="display:inline;width:148px;background-color:#e6e6ff;padding:8px;margin-left:6px;margin-bottom:6px">
									<option value='a.fst_relation_name'>Customer</option>
									<option value='a.fst_sj_no'>Surat Jalan No.</option>
									<option value='a.fst_sj_type'>Tipe</option>
									<option value='a.fst_sj_return_resi_no'>S/J Return Resi No.</option>
								</select>
							</div>
							<div style="clear:both"></div>
						</div>
                            <table id="tblHistMonitoring" class="display table-bordered table-hover table-striped nowrap row-border" style="width:100%"></table>
                        </div><!-- /.tab-pane -->
                                            
                    </div> <!-- /.tab-content -->                    
                </div>
            </div>
            <!-- end box header -->
        </div>
    </div>
</section>


<script type="text/javascript">
    $(function() {
        $("#btn-update").click(function(event) {
			event.preventDefault();
			var resiNo = $("#fst_sj_return_resi_no").val();
			if (resiNo == null || resiNo == "") {
				$("#fst_sj_return_resi_no_err").html("S/J Resi No Required!!!");
				$("#fst_sj_return_resi_no_err").show();
			} else {
				$("#fst_sj_return_resi_no_err").hide();
				//data = $('#resi-modal').serializeArray();
				data = new FormData($("#resi-modal")[0]);
				url= "<?= site_url() ?>adm_persediaan/monitoring_sj/doUpdateResi";
				console.log(data);
				App.blockUIOnAjaxRequest("Please wait while update data.....");
				$.ajax({
					type: "POST",
					enctype: 'multipart/form-data',
					url: url,
					data: data,
					processData: false,
					contentType: false,
					cache: false,
					timeout: 600000,
					success: function(resp) {
						if (resp.message != "") {
							$.alert({
								title: 'Message',
								content: resp.message,
								buttons: {
									OK: function() {
										if (resp.status == "SUCCESS") {
											window.location.href = "<?= site_url() ?>adm_persediaan/monitoring_sj";
											return;
										}
									},
								}
							});
						}
						if (resp.status == "VALIDATION_FORM_FAILED") {
							//Show Error
							errors = resp.data;
							for (key in errors) {
								$("#" + key + "_err").html(errors[key]);
							}
						} else if (resp.status == "SUCCESS") {
							data = resp.data;
							//Clear all previous error
							$(".text-danger").html("");
						}
					},
					error: function(e) {
						$("#result").text(e.responseText);
						console.log("ERROR : ", e);
					}
				});
			}
        });
    });
</script>


<div id="resiModal" class="modal fade" role="dialog">
	<div class="modal-dialog" style="display:table;width:60%;min-width:600px;max-width:100%">	
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
			<div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Update Resi")?></h4>
			</div>

			<div class="modal-body">
				<div class="row">
                    <div class="col-md-12" >
                        <div style="border:1px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">
								<form  class="form-horizontal" id="resi-modal" method="POST" enctype="multipart/form-data">
									<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">			
									<div class="form-group">
										<label for="fin_sj_id" class="col-md-3 control-label"><?=lang("S/J ID")?> :</label>
										<div class="col-md-3">
											<input type="text" class="form-control text-right" id="fin_sj_id" name="fin_sj_id" readonly>
											<div id="fin_sj_id_err" class="text-danger"></div>
										</div>

										<label for="fst_sj_no" class="col-md-3 control-label"><?=lang("S/J No.")?> :</label>
										<div class="col-md-3">
											<input type="text" class="form-control text-right" id="fst_sj_no" name="fst_sj_no" readonly>
											<div id="fst_sj_no_err" class="text-danger"></div>
										</div>
									</div>

									<div class="form-group hide">
										<label for="fin_sj_return_by_id" class="col-md-4 control-label"><?=lang("S/J Return By Id")?> :</label>
										<div class="col-md-8">
											<input type="text" class="form-control text-right" id="fin_sj_return_by_id" name="fin_sj_return_by_id" readonly>
											<div id="fin_sj_return_by_id_err" class="text-danger"></div>
										</div>
									</div>

									<div class="form-group">
										<label for="fst_sj_return_resi_no" class="col-md-3 control-label"><?=lang("S/J Resi No.")?> :</label>
										<div class="col-md-3">
											<input type="text" class="form-control text-right" id="fst_sj_return_resi_no" name="fst_sj_return_resi_no">
											<div id="fst_sj_return_resi_no_err" class="text-danger"></div>
										</div>

									<label for="fdt_sj_return_datetime" class="col-md-3 control-label"><?=lang("S/J Return Date")?> :</label>
										<div class="col-md-3">
											<div class="input-group date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input type="text" class="form-control datetimepicker text-right" id="fdt_sj_return_datetime" name="fdt_sj_return_datetime"/>								
											</div>
											<div id="fdt_sj_return_datetime_err" class="text-danger"></div>
										</div>
									</div>

									<div class="form-group">
										<label for="fst_sj_return_memo" class="col-md-3 control-label"><?=lang("S/J Return Memo")?> :</label>
										<div class="col-md-6">
											<textarea class="form-control" id="fst_sj_return_memo" name="fst_sj_return_memo"></textarea>
											<div id="fst_sj_return_memo_err" class="text-danger"></div>
										</div>
									</div>
								</form>

								<div class="modal-footer" style="width:100%;padding:10px" class="text-center">
									<button id="btn-update" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Update")?></button>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!------- MONITORING ------>
<script type="text/javascript">
	$(function(){
		$(".filterData").change(function(event){
			event.preventDefault();
			$('#tblMonitoring').DataTable().ajax.reload();
		});
		
		$('#tblMonitoring').on('preXhr.dt', function ( e, settings, data ) {
			data.optionSearch = $('#selectSearch').val();
		}).DataTable({
			ajax: {
				url:"<?=site_url()?>adm_persediaan/monitoring_sj/fetch_monitoring_list",
			},
			columns:[
                {"title" : "Surat Jalan ID","width": "10%",sortable:true,data:"fin_sj_id",visible:false},
				{"title" : "Surat Jalan No","width": "20%",sortable:true,data:"fst_sj_no",visible:true},
                {"title" : "Surat Jalan DateTime","width": "20%",sortable:true,data:"fdt_sj_datetime",visible:true},
				{"title" : "Ref. Trans No","width": "20%",sortable:true,data:"fst_trans_no",visible:true},
				{"title" : "Ref. Trans Date","width": "20%",sortable:true,data:"fdt_trans_datetime",visible:true},
                {"title" : "Warehouse Name","width": "20%",sortable:true,data:"fst_warehouse_name",visible:true},
				{"title" : "Customer Name","width": "25%",sortable:true,data:"fst_relation_name",visible:true},
				{"title" : "Unhold","width": "15%",sortable:false,className:'dt-body-center text-center',
					render: function(data,type,row){
						if (row.fbl_is_hold != "0"){
							return "<a class='btn-unhold show' href='#'><i class='fa fa-play-circle'></i></a>";
						}else{
							return "<a class='btn-unhold hide' href='#'><i class='fa fa-play-circle'></i></a>";
						}
					}
				},
				{"title" : "Unhold DateTime","width": "20%",sortable:true,data:"fdt_unhold_datetime",visible:false},
				{"title" : "Surat Jalan Resi No","width": "20%",sortable:true,data:"fst_sj_return_resi_no",visible:true,className:'btn-resi'},
				{"title" : "Surat Jalan Return Memo","width": "20%",sortable:true,data:"fst_sj_return_memo",visible:true},
				{"title" : "Surat Jalan Return Date","width": "20%",sortable:true,data:"fdt_sj_return_datetime",visible:true},
				{"title" : "Return By ID","width": "20%",sortable:true,data:"fin_sj_return_by_id",visible:false},
			],
			dataSrc:"data",
			processing: true,
			serverSide: true,
			scrollX: true,
			scrollCollapse: true,
		}).on('draw',function(){
			$(".dataTables_scrollHeadInner").css("min-width","100%");
			$(".dataTables_scrollHeadInner > table").css("min-width","100%");
			$(".dataTables_scrollBody").css("position","static");
		});
		
		$("#fdt_sj_return_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");
		
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
			$('#fst_sj_return_resi_no').val(row.fst_sj_return_resi_no);
			$('#fst_sj_return_memo').val(row.fst_sj_return_memo);
			$("#fdt_sj_return_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");
		});
		$("#tblMonitoring").on("hover",".btn-resi",function(e){
			alert("HOVER");
			e.preventDefault();
			t = $('#tblMonitoring').DataTable();
			var trRow = $(this).parents('tr');
			update_sj = t.row(trRow);
			row = update_sj.data();
		});
	});
	
	function doUnhold(element){
		t = $('#tblMonitoring').DataTable();
		var trRow = element.parents('tr');
		data = t.row(trRow).data();
		
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
								window.location.href = "<?= site_url() ?>adm_persediaan/monitoring_sj";
								return;
							}
						},
					}
				});
			}
			/*if(resp.status == "SUCCESS") {
                //remove row
               trRow.remove();
            }*/
		});
	}
</script>

<!----------- HISTORY MONITORING ---------->
<script type="text/javascript">
	$(function(){

		/*$(document).on("shown.bs.tab","#tab2 a",function(e){
			$($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
		});*/

		$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
			$($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
		})
		
		$(".filterData").change(function(event){
			event.preventDefault();
			$('#tblHistMonitoring').DataTable().ajax.reload();
		});
		
		$('#tblHistMonitoring').on('preXhr.dt', function ( e, settings, data ) {
			data.optionSearch2 = $('#selectSearch2').val();
			console.log(data);
		}).DataTable({
			ajax: {
				url:"<?=site_url()?>adm_persediaan/monitoring_sj/fetch_histmonitoring_list",
			},
			columns:[
                {"title" : "Surat Jalan ID","width": "10%",sortable:true,data:"fin_sj_id",visible:false},
				{"title" : "Surat Jalan No","width": "20%",sortable:true,data:"fst_sj_no",visible:true},
                {"title" : "Surat Jalan DateTime","width": "20%",sortable:true,data:"fdt_sj_datetime",visible:true},
				{"title" : "Ref. Trans No","width": "20%",sortable:true,data:"fst_trans_no",visible:true},
				{"title" : "Ref. Trans Date","width": "20%",sortable:true,data:"fdt_trans_datetime",visible:true},
                {"title" : "Warehouse Name","width": "20%",sortable:true,data:"fst_warehouse_name",visible:true},
				{"title" : "Customer Name","width": "25%",sortable:true,data:"fst_relation_name",visible:true},
				{"title" : "Unhold","width": "15%",sortable:false,className:'dt-body-center text-center',
					render: function(data,type,row){
						if (row.fbl_is_hold != "0"){
							return "<a class='btn-unhold show' href='#'><i class='fa fa-play-circle'></i></a>";
						}else{
							return "<a class='btn-unhold hide' href='#'><i class='fa fa-play-circle'></i></a>";
						}
					}
				},
				{"title" : "Unhold DateTime","width": "20%",sortable:true,data:"fdt_unhold_datetime",visible:false},
				{"title" : "Surat Jalan Resi No","width": "20%",sortable:true,data:"fst_sj_return_resi_no",visible:true,className:'btn-edit'},
				{"title" : "Surat Jalan Return Memo","width": "20%",sortable:true,data:"fst_sj_return_memo",visible:true},
				{"title" : "Surat Jalan Return Date","width": "20%",sortable:true,data:"fdt_sj_return_datetime",visible:true},
				{"title" : "Return By ID","width": "20%",sortable:true,data:"fin_sj_return_by_id",visible:false},
			],
			dataSrc:"data",
			processing: true,
			serverSide: true,
			scrollX: true,
			scrollCollapse: true,
		}).on('draw',function(){
			$(".dataTables_scrollHeadInner").css("min-width","100%");
			$(".dataTables_scrollHeadInner > table").css("min-width","100%");
			$(".dataTables_scrollBody").css("position","static");
		});

		$("#tblHistMonitoring").on("click",".btn-edit",function(e){
			e.preventDefault();
			t = $('#tblHistMonitoring').DataTable();
			var trRow = $(this).parents('tr');
			edit_sj = t.row(trRow);
			row = edit_sj.data();
            $("#resiModal").modal('show');
			
			$('#fin_sj_id').val(row.fin_sj_id);
			$('#fst_sj_no').val(row.fst_sj_no);
			$('#fst_sj_return_resi_no').val(row.fst_sj_return_resi_no);
			$('#fst_sj_return_memo').val(row.fst_sj_return_memo);
			$("#fdt_sj_return_datetime").val(dateTimeFormat(row.fdt_sj_return_datetime)).datetimepicker('update');
		});
	});
</script>

<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>