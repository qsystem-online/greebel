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
									<?php
										foreach($arrSearch as $key => $value){ ?>
											<option value=<?=$key?>><?=$value?></option>
										<?php
										}
									?>
									<option value="a.fst_relation_name">Customer</option>
									<option value="a.fst_sj_no">Surat Jalan No.</option>
								</select>
							</span>
						</div>
                            <table id="tblMonitoring" class="display nowrap" style="width:100%"></table>
                        </div> <!-- /.tab-pane -->

                        <div class="tab-pane" id="tab_2">
							<div align="right">						
								<span>Search on:</span>
								<span>
									<select id="pilihSearch" class="filterData" name="selectSearch" style="width: 148px;background-color:#e6e6ff;padding:8px;margin-left:6px;margin-bottom:6px">
										<?php
											foreach($arrSearch as $key => $value){ ?>
												<option value=<?=$key?>><?=$value?></option>
											<?php
											}
										?>
										<option value="a.fst_relation_name">Customer</option>
										<option value="a.fst_sj_no">Surat Jalan No.</option>
										<option value="a.fdt_sj_date">S/J DateTime</option>
										<option value="a.fst_sj_return_resi_no">S/J Return Resi No.</option>
									</select>
								</span>
							</div>
                            <table id="tblHistMonitoring" class="display nowrap" style="width:100%"></table>
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
			$(".text-danger").html("");
			//data = $('#resi-modal').serializeArray();
			data = new FormData($("#resi-modal")[0]);
			url= "<?= site_url() ?>adm_persediaan/monitoring_sj/doUpdateResi";
			console.log(data);

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

        });
    });
</script>


<div id="resiModal" class="modal fade" role="dialog">
	<div class="modal-dialog" style="display:table;width:65%;min-width:650px;max-width:100%">	
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Update Resi")?></h4>
			</div>

			<div class="modal-body">
				<form  class="form-horizontal" id="resi-modal" method="POST" enctype="multipart/form-data">
				<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">			
					<div class="form-group">
						<label for="fin_sj_id" class="col-md-2 control-label"><?=lang("S/J ID")?> :</label>
						<div class="col-md-4">
							<input type="text" class="form-control text-right" id="fin_sj_id" name="fin_sj_id" readonly>
							<div id="fin_sj_id_err" class="text-danger"></div>
						</div>

						<label for="fst_sj_no" class="col-md-2 control-label"><?=lang("S/J No.")?> :</label>
						<div class="col-md-4">
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
						<label for="fst_sj_return_resi_no" class="col-md-2 control-label"><?=lang("S/J Resi No.")?> :</label>
						<div class="col-md-4">
							<input type="text" class="form-control text-right" id="fst_sj_return_resi_no" name="fst_sj_return_resi_no">
							<div id="fst_sj_return_resi_no_err" class="text-danger"></div>
						</div>

					<label for="fdt_sj_return_datetime" class="col-md-2 control-label"><?=lang("S/J Return Date")?> :</label>
						<div class="col-md-4">
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
						<label for="fst_sj_return_memo" class="col-md-2 control-label"><?=lang("S/J Return Memo")?> :</label>
						<div class="col-md-6">
							<textarea class="form-control" id="fst_sj_return_memo" name="fst_sj_return_memo"></textarea>
							<div id="fst_sj_return_memo_err" class="text-danger"></div>
						</div>
					</div>
				</form>
			</div>

			<div class="modal-footer">
				<button id="btn-update" type="button" class="btn btn-primary" ><?=lang("Update")?></button>
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
                {"title" : "Surat Jalan ID","width": "10%",sortable:true,data:"fin_sj_id",visible:true},
				{"title" : "Surat Jalan No","width": "20%",sortable:true,data:"fst_sj_no",visible:true},
                {"title" : "Surat Jalan DateTime","width": "20%",sortable:true,data:"fdt_sj_date",visible:true},
				{"title" : "Sales Order No","width": "20%",sortable:true,data:"fst_salesorder_no",visible:true},
				{"title" : "Sales Order Date","width": "20%",sortable:true,data:"fdt_salesorder_date",visible:true},
                {"title" : "Warehouse Name","width": "20%",sortable:true,data:"fst_warehouse_name",visible:true},
				{"title" : "Customer Name","width": "25%",sortable:true,data:"fst_relation_name",visible:true},
                {"title" : "Hold","width": "10%",sortable:true,data:"fbl_is_hold",visible:true},
                {"title" : "Surat Jalan Return Date","width": "20%",sortable:true,data:"fdt_sj_return_datetime",visible:true},
				{"title" : "Surat Jalan Resi No","width": "20%",sortable:true,data:"fst_sj_return_resi_no",visible:true,className:'btn-resi'},
				{"title" : "Surat Jalan Return Memo","width": "20%",sortable:true,data:"fst_sj_return_memo",visible:true},
                {"title" : "Return By ID","width": "20%",sortable:true,data:"fin_sj_return_by_id",visible:true},
				{"title" : "Unhold DateTime","width": "20%",sortable:true,data:"fdt_unhold_datetime",visible:false},
				{"title" : "Unhold","width": "15%",sortable:false,className:'dt-body-center text-center',
					render: function(data,type,row){
						return "<a class='btn-unhold' href='#'><i class='fa fa-pause-circle'></i></a>";
					}
				},
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
								//window.location.href = "<?= site_url() ?>adm_persediaan/monitoring_sj";
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

<!----------- HISTORY MONITORING ---------->
<script type="text/javascript">
	$(function(){

		$(".filterData").change(function(event){
			event.preventDefault();
			$('#tblHistMonitoring').DataTable().ajax.reload();
		});

		$('#tblHistMonitoring').on('preXhr.dt', function ( e, settings, data ) {
			data.optionSearch = $('#pilihSearch').val();
			console.log(data);
		}).DataTable({
			ajax: {
				url:"<?=site_url()?>adm_persediaan/monitoring_sj/fetch_histmonitoring_list",
			},
			columns:[
                {"title" : "Surat Jalan ID","width": "10%",sortable:true,data:"fin_sj_id",visible:true},
				{"title" : "Surat Jalan No","width": "20%",sortable:true,data:"fst_sj_no",visible:true},
                {"title" : "Surat Jalan DateTime","width": "20%",sortable:true,data:"fdt_sj_date",visible:true},
				{"title" : "Sales Order No","width": "20%",sortable:true,data:"fst_salesorder_no",visible:true},
				{"title" : "Sales Order Date","width": "20%",sortable:true,data:"fdt_salesorder_date",visible:true},
                {"title" : "Warehouse Name","width": "20%",sortable:true,data:"fst_warehouse_name",visible:true},
				{"title" : "Customer Name","width": "25%",sortable:true,data:"fst_relation_name",visible:true},
                {"title" : "Surat Jalan Return DateTime","width": "20%",sortable:true,data:"fdt_sj_return_datetime",visible:true},
				{"title" : "Surat Jalan Resi No","width": "20%",sortable:true,data:"fst_sj_return_resi_no",visible:true},
				{"title" : "Surat Jalan Return Memo","width": "20%",sortable:true,data:"fst_sj_return_memo",visible:true},
                {"title" : "Return By ID","width": "20%",sortable:true,data:"fin_sj_return_by_id",visible:true},
				{"title" : "Unhold DateTime","width": "20%",sortable:true,data:"fdt_unhold_datetime",visible:true},
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
	});
</script>

<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>