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
	.hm{		
		background-color: #22ffc7;
	}
</style>

<section class="content-header">
	<h1><?=lang("Material Requirement Planning")?><small><?=lang("form")?></small></h1>
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
					<a id="btnJurnal" class="btn btn-primary hide" href="#" title="Jurnal" style="display:<?= $mode == "ADD" ? "none" : "inline-block" ?>"><i class="fa fa-align-left" aria-hidden="true"></i></a>
					<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
					<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Group")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
				</div>
			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmHeader" class="form-horizontal" action="" method="POST" >
				<div class="box-body">
					<input type="hidden" id="fin_mrp_id" name="fin_mrp_id" value="<?=$fin_mrp_id?>"/>

					<div class="form-group">
						<label for="fst_mrp_no" class="col-md-2 control-label"><?=lang("MRP")?> #</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_mrp_no" placeholder="<?=lang("MRP No")?>" name="fst_mrp_no" value="<?=$fst_mrp_no?>"/>
							<div id="fst_mrp_no_err" class="text-danger"></div>
						</div>								

						<label for="fdt_mrp_datetime" class="col-md-2 control-label"><?=lang("Tanggal")?></label>
						<div class="col-md-4">
							<input type="text" class="form-control datetimepicker text-right" id="fdt_mrp_datetime" placeholder="<?=lang("MRP Datetime")?>" name="fdt_mrp_datetime" value=""/>
							<div id="fdt_mrp_datetime_err" class="text-danger"></div>
						</div>								
                    </div>  

					<div class="form-group">
						<label for="fin_mps_id" class="col-md-2 control-label"><?=lang("MPS #")?></label>
						<div class="col-md-4">
							<select  name='fin_mps_id' class='form-control text-right' id='fin_mps_id' style="width:100%"></select>
							<div id="fst_type_err" class="text-danger"></div>
						</div>					
						<label for="fin_mps_month" class="col-md-2 control-label"><?=lang("Month")?></label>
						<div class="col-md-4">
							<select class='form-control text-right' id='fin_mps_month' name='fin_mps_month'>
								<option value='1'><?=lang("Januari")?></option>
								<option value='2'><?=lang("Febuari")?></option>
								<option value='3'><?=lang("Maret")?></option>
								<option value='4'><?=lang("April")?></option>
								<option value='5'><?=lang("Mei")?></option>
								<option value='6'><?=lang("Juni")?></option>
								<option value='7'><?=lang("Juli")?></option>
								<option value='8'><?=lang("Agustus")?></option>
								<option value='9'><?=lang("September")?></option>
								<option value='10'><?=lang("Oktober")?></option>
								<option value='11'><?=lang("November")?></option>
								<option value='12'><?=lang("Desember")?></option>								
							</select>
							<div id="fin_mps_month_err" class="text-danger"></div>
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
						<div class="col-sm-12">
							<table id="tbldetails" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
						</div>
						<div class="col-sm-12" style="text-align:right">
							<button id="btnProcess1" class="btn btn-primary btn-process" data-level="0">Process</button>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-md-12">
							<label class="control-label text-left">Detail Bill Of Materials</label>
						</div>	
					</div
					>
					<div class="form-group">												
						<div class="col-sm-12">
							<ul class="nav nav-tabs" id="tabLevel">
								
								<!--
								<li class="active"><a data-toggle="tab" href="#detailL1">Level 1</a></li>																
								<li><a data-toggle="tab" href="#detailw2">Level 2</a></li>
								<li><a data-toggle="tab" href="#detailw2">Level 3</a></li>
								<li><a data-toggle="tab" href="#detailw2">Level 4</a></li>
								<li><a data-toggle="tab" href="#detailw2">Level 5</a></li>
								-->
							</ul>

							<div class="tab-content" style="padding:5px" id="tabContentLevel">
								<!--
								<div id="detailL1" class="tab-pane fade in active">
									<button class="btn btn-primary btn-process" data-level="1">Next Level</button>
									<table id="tbldetailsL1" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
								</div>
								
								<div id="detailw2" class="tab-pane fade">
									<table id="tbldetailsw2" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
								</div>
								<div id="detailw3" class="tab-pane fade">
									<table id="tbldetailsw3" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
								</div>
								<div id="detailw4" class="tab-pane fade">
									<table id="tbldetailsw4" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
								</div>
								<div id="detailw5" class="tab-pane fade">
									<table id="tbldetailsw5" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
								</div>
								-->
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
						<label class="col-md-2 control-label"><?=lang("Item")?>:</label>						
						<label class="col-md-10 control-label" id="fst_item">Pencil warna greebel - Box</label>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label "><?=lang("Qty MPS")?>: </label>
						<label class="col-md-2 col-md-offset-8 control-label" id="fdb_qty_mps">50</label>				
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label"><?=lang("Week")?></label>
						<label class="col-md-4 col-md-offset-4 control-label"><?=lang("Qty")?></label>						
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label">1</label>
						<div class="col-md-4 col-md-offset-4">
							<input type='TEXT' id="fdb_qty_w1" class="form-control text-right" value="0"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">2</label>
						<div class="col-md-4 col-md-offset-4">
							<input type='TEXT' id="fdb_qty_w2" class="form-control text-right" value="400"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">3</label>
						<div class="col-md-4 col-md-offset-4">
							<input type='TEXT' id="fdb_qty_w3" class="form-control text-right" value="400"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">4</label>
						<div class="col-md-4 col-md-offset-4">
							<input type='TEXT' id="fdb_qty_w4" class="form-control text-right" value="400"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">5</label>
						<div class="col-md-4 col-md-offset-4">
							<input type='TEXT' id="fdb_qty_w5" class="form-control text-right" value="400"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">Total</label>
						<div class="col-md-4 col-md-offset-4">
							<input type='TEXT' id="fdb_qty_total" readonly class="form-control text-right" value="400"/>
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
		mdlDetail = {			
			show:function(){
				if (selectedDetail != null){
					var data = selectedDetail.data();
					//console.log(data);
					$("#fst_item").text(data.fst_item_code + " - " + data.fst_item_name + "(" + data.fst_unit +")" );
					$("#fdb_qty_mps").text(data.fdb_qty_mps);
					$("#fdb_qty_w1").val(data.fdb_qty_w1);
					$("#fdb_qty_w2").val(data.fdb_qty_w2);
					$("#fdb_qty_w3").val(data.fdb_qty_w3);
					$("#fdb_qty_w4").val(data.fdb_qty_w4);
					$("#fdb_qty_w5").val(data.fdb_qty_w5);
					
				}				
				$("#mdlDetail").modal("show");
			},
			hide:function(){
				$("#mdlDetail").modal("hide");
			},
			clear:function(){
				selectedDetail = null;
			},			
		};

	</script>

	<script type="text/javascript" info="event">		
		$(function(){		
			$("#fdb_qty_w1,#fdb_qty_w2,#fdb_qty_w3,#fdb_qty_w4,#fdb_qty_w5").change(function(e){
				e.preventDefault();
				var total = parseFloat($("#fdb_qty_w1").val()) + parseFloat($("#fdb_qty_w2").val()) + parseFloat($("#fdb_qty_w3").val()) + parseFloat($("#fdb_qty_w4").val()) + parseFloat($("#fdb_qty_w5").val());
				var ttlMPS = parseFloat($("#fdb_qty_mps").text());

				$("#fdb_qty_total").val(total);

				if (total < ttlMPS){
					$("#fdb_qty_total").css("color","red");
				}else{
					$("#fdb_qty_total").css("color","#555");
				}
			});

			$("#btn-save-detail").click(function(e){
				var data = selectedDetail.data();
				data.fdb_qty_w1 = parseFloat($("#fdb_qty_w1").val());
				data.fdb_qty_w2 = parseFloat($("#fdb_qty_w2").val());
				data.fdb_qty_w3 = parseFloat($("#fdb_qty_w3").val());
				data.fdb_qty_w4 = parseFloat($("#fdb_qty_w4").val());
				data.fdb_qty_w5 = parseFloat($("#fdb_qty_w5").val());
				
				tblDetails.row(selectedDetail).data(data).draw(false);

				mdlDetail.clear();	
				mdlDetail.hide();		
			});

		});
	</script>

	<script type="text/javascript" info="init">
		$(function(){									
		});
	</script>

</div>


<div id="mdlDetail2" class="modal fade in" role="dialog" style="display: none">
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
						<label class="col-md-2 control-label"><?=lang("Item")?>:</label>						
						<label class="col-md-10 control-label" id="fst_item_d2">Pencil warna greebel - Box</label>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label"><?=lang("Week")?></label>
						<div class="col-md-2 text-right">
							<label class="control-label"><?=lang("Balance")?></label>
						</div>
						<div class="col-md-2 text-right">
							<label class="control-label"><?=lang("Request")?></label>
						</div>
						<div class="col-md-2 text-right">
							<label class="control-label"><?=lang("Diff")?></label>
						</div>
						<div class="col-md-2 text-right">
							<label class="control-label"><?=lang("PO")?></label>
						</div>
						<div class="col-md-2 text-right">
							<label class="control-label"><?=lang("WO")?></label>						
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label">1</label>
						<div class="col-md-2">
							<input type='TEXT' readonly id="fdb_qty_balance" class="form-control text-right" value="0"/>
						</div>
						<div class="col-md-2">
							<input type='TEXT' readonly id="fdb_qty_rq_w1" class="form-control text-right" value="0"/>
						</div>
						<div class="col-md-2">
							<input type='TEXT' readonly id="fdb_qty_diff_w1" class="form-control text-right" value="0"/>
						</div>
						<div class="col-md-2">
							<input type='TEXT' id="fdb_qty_po_w1" class="powo form-control text-right" value="0"/>
						</div>
						<div class="col-md-2">
							<input type='TEXT' id="fdb_qty_wo_w1" class="powo form-control text-right" value="0"/>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label">2</label>
						<div class="col-md-2">
							<input type='TEXT' readonly id="fdb_bow1" class="form-control text-right" value="0"/>
						</div>
						<div class="col-md-2">
							<input type='TEXT' readonly id="fdb_qty_rq_w2" class="form-control text-right" value="0"/>
						</div>
						<div class="col-md-2">
							<input type='TEXT' readonly id="fdb_qty_diff_w2" class="form-control text-right" value="0"/>
						</div>
						<div class="col-md-2">
							<input type='TEXT' id="fdb_qty_po_w2" class="powo form-control text-right" value="0"/>
						</div>
						<div class="col-md-2">
							<input type='TEXT' id="fdb_qty_wo_w2" class="powo form-control text-right" value="0"/>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label">3</label>
						<div class="col-md-2">
							<input type='TEXT' readonly id="fdb_bow2" class="form-control text-right" value="0"/>
						</div>
						<div class="col-md-2">
							<input type='TEXT' readonly id="fdb_qty_rq_w3" class="form-control text-right" value="0"/>
						</div>
						<div class="col-md-2">
							<input type='TEXT' readonly id="fdb_qty_diff_w3" class="form-control text-right" value="0"/>
						</div>
						<div class="col-md-2">
							<input type='TEXT' id="fdb_qty_po_w3" class="powo form-control text-right" value="0"/>
						</div>
						<div class="col-md-2">
							<input type='TEXT' id="fdb_qty_wo_w3" class="powo form-control text-right" value="0"/>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label">4</label>
						<div class="col-md-2">
							<input type='TEXT' readonly id="fdb_bow3" class="form-control text-right" value="0"/>
						</div>
						<div class="col-md-2">
							<input type='TEXT' readonly id="fdb_qty_rq_w4" class="form-control text-right" value="0"/>
						</div>
						<div class="col-md-2">
							<input type='TEXT' readonly id="fdb_qty_diff_w4" class="powo form-control text-right" value="0"/>
						</div>
						<div class="col-md-2">
							<input type='TEXT' id="fdb_qty_po_w4" class="powo form-control text-right" value="0"/>
						</div>
						<div class="col-md-2">
							<input type='TEXT' id="fdb_qty_wo_w4" class="powo form-control text-right" value="0"/>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label">5</label>
						<div class="col-md-2">
							<input type='TEXT' readonly id="fdb_bow4" class="form-control text-right" value="0"/>
						</div>
						<div class="col-md-2">
							<input type='TEXT' readonly id="fdb_qty_rq_w5" class="form-control text-right" value="0"/>
						</div>
						<div class="col-md-2">
							<input type='TEXT' readonly id="fdb_qty_diff_w5" class="form-control text-right" value="0"/>
						</div>
						<div class="col-md-2">
							<input type='TEXT' id="fdb_qty_po_w5" class="powo form-control text-right" value="0"/>
						</div>
						<div class="col-md-2">
							<input type='TEXT' id="fdb_qty_wo_w5" class="powo form-control text-right" value="0"/>
						</div>
					</div>

				</form>				
			</div>


			<div class="modal-footer">
				<button id="btn-save-detail2" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Add")?></button>
				<button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
			</div>
		</div>
	</div>

	<script type="text/javascript" info="define">
		mdlDetail2 = {			
			tbl:null,
			selectedDetail:null,
			show:function(tbl,detail){
				mdlDetail2.tbl = tbl;
				mdlDetail2.selectedDetail = detail;

				var data = detail.data();
				$("#fst_item_d2").text(data.fst_item_code + " - " + data.fst_item_name + "(" + data.fst_unit +")" );
				$("#fdb_qty_balance").val(data.fdb_qty_balance);
				$("#fdb_qty_rq_w1").val(data.fdb_qty_rq_w1);								
				$("#fdb_qty_po_w1").val(data.fdb_qty_po_w1);
				$("#fdb_qty_wo_w1").val(data.fdb_qty_wo_w1);
				$("#fdb_qty_rq_w2").val(data.fdb_qty_rq_w2);				
				$("#fdb_qty_po_w2").val(data.fdb_qty_po_w2);
				$("#fdb_qty_wo_w2").val(data.fdb_qty_wo_w2);
				$("#fdb_qty_rq_w3").val(data.fdb_qty_rq_w3);
				$("#fdb_qty_po_w3").val(data.fdb_qty_po_w3);
				$("#fdb_qty_wo_w3").val(data.fdb_qty_wo_w3)
				$("#fdb_qty_rq_w4").val(data.fdb_qty_rq_w4);;
				$("#fdb_qty_po_w4").val(data.fdb_qty_po_w4);
				$("#fdb_qty_wo_w4").val(data.fdb_qty_wo_w4);
				$("#fdb_qty_rq_w5").val(data.fdb_qty_rq_w5);
				$("#fdb_qty_po_w5").val(data.fdb_qty_po_w5);
				$("#fdb_qty_wo_w5").val(data.fdb_qty_wo_w5);

				mdlDetail2.refresh();


				//$("#fdb_qty_mps").text(data.fdb_qty_mps);
				//$("#fdb_qty_w1").val(data.fdb_qty_w1);
				//$("#fdb_qty_w2").val(data.fdb_qty_w2);
				//$("#fdb_qty_w3").val(data.fdb_qty_w3);
				//$("#fdb_qty_w4").val(data.fdb_qty_w4);
				//$("#fdb_qty_w5").val(data.fdb_qty_w5);
				
				$("#mdlDetail2").modal("show");
			},
			refresh:function(){

				var diff1 = parseFloat($("#fdb_qty_balance").val()) - parseFloat($("#fdb_qty_rq_w1").val());
				$("#fdb_qty_diff_w1").val(diff1);				
				var powo1 = parseFloat($("#fdb_qty_po_w1").val()) + parseFloat($("#fdb_qty_wo_w1").val());
				var bow1 = diff1 + powo1;
				$("#fdb_bow1").val(bow1);

				var diff2 = bow1 - parseFloat($("#fdb_qty_rq_w2").val());
				$("#fdb_qty_diff_w2").val(diff2);
				var powo2 = parseFloat($("#fdb_qty_po_w2").val()) + parseFloat($("#fdb_qty_wo_w2").val());
				var bow2 = diff2 + powo2;
				$("#fdb_bow2").val(bow2);

				var diff3 = bow2 - parseFloat($("#fdb_qty_rq_w3").val());
				$("#fdb_qty_diff_w3").val(diff3);
				var powo3 = parseFloat($("#fdb_qty_po_w3").val()) + parseFloat($("#fdb_qty_wo_w3").val());
				var bow3 = diff3 + powo3;
				$("#fdb_bow3").val(bow3);

				var diff4 = bow3 - parseFloat($("#fdb_qty_rq_w4").val());
				$("#fdb_qty_diff_w4").val(diff4);
				var powo4 = parseFloat($("#fdb_qty_po_w4").val()) + parseFloat($("#fdb_qty_wo_w4").val());
				var bow4 = diff4 + powo4;
				$("#fdb_bow4").val(bow4);

				var diff5 = bow4 - parseFloat($("#fdb_qty_rq_w5").val());
				$("#fdb_qty_diff_w5").val(diff5);
				var powo5 = parseFloat($("#fdb_qty_po_w5").val()) + parseFloat($("#fdb_qty_wo_w5").val());
				var bow5 = diff5 + powo5;
				$("#fdb_bow5").val(bow5);
			},

			hide:function(){
				$("#mdlDetail2").modal("hide");
			},
			clear:function(){
				mdlDetail2.tbl = null;
				mdlDetail2.selectedDetail = null;
			},			
		};

	</script>

	<script type="text/javascript" info="event">		
		$(function(){		
			
			$(".powo ").change(function(e){
				e.preventDefault();
				mdlDetail2.refresh();
			})

			$("#btn-save-detail2").click(function(e){
				e.preventDefault();
				var tbl = mdlDetail2.tbl;
				var data = mdlDetail2.selectedDetail.data();
				console.log(data);

				data.fdb_qty_po_w1 = parseFloat($("#fdb_qty_po_w1").val());
				data.fdb_qty_wo_w1 = parseFloat($("#fdb_qty_wo_w1").val());
				data.fdb_qty_po_w2 = parseFloat($("#fdb_qty_po_w2").val());
				data.fdb_qty_wo_w2 = parseFloat($("#fdb_qty_wo_w2").val());
				data.fdb_qty_po_w3 = parseFloat($("#fdb_qty_po_w3").val());
				data.fdb_qty_wo_w3 = parseFloat($("#fdb_qty_wo_w3").val());
				data.fdb_qty_po_w4 = parseFloat($("#fdb_qty_po_w4").val());
				data.fdb_qty_wo_w4 = parseFloat($("#fdb_qty_wo_w4").val());
				data.fdb_qty_po_w5 = parseFloat($("#fdb_qty_po_w5").val());
				data.fdb_qty_wo_w5 = parseFloat($("#fdb_qty_wo_w5").val());
				
				tbl.row(mdlDetail2.selectedDetail).data(data).draw(false);		
				
				mdlDetail2.clear();
				mdlDetail2.hide();
			})

		});
	</script>

	<script type="text/javascript" info="init">
		$(function(){									
		});
	</script>

</div>




<?php echo $mdlEditForm ?>
<?php echo $mdlPrint ?>
<?php echo $mdlJurnal ?>

<script type="text/javascript" info="define">
	var selectedDetail;	
	var tblDetails;
</script>

<script type="text/javascript" info="bind">
	$(document).bind('keydown', 'alt+d', function(){
		$("#btn-add-detail").trigger("click");
	});
</script>


<script type="text/javascript" info="init">
	$(function(){		
		$("#fdt_mrp_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");
		

		$("#fin_mps_id").select2({
			width: '100%',
			ajax:{
				url:"<?=site_url()?>tr/production/mrp/ajxGetMPS",
				dataType: 'json',
                delay: 250,
                processResults: function(resp) {
					if (resp.status == "SUCCESS"){
						var data = resp.data;
						data2 = [];
						$.each(data, function(index, value) {
							data2.push({
								"id": value.fin_mps_id,
								"text": value.fst_mps_no
							});
						});
						return {
							results: data2
						};
					}else{
						alert(resp.messages);
					}                    
                },
                cache: true,
			}
		})


		tblDetails = $('#tbldetails').on('preXhr.dt', function ( e, settings, data ) {
			data.sessionId = "TEST SESSION ID";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "id","width": "0px",sortable:false,data:"fin_rec_id",visible:false},
				{"title" : "Item","width": "300px",sortable:false,data:"fin_item_id",
					"render":function(data,type,row){
						return row.fst_item_code + " - " + row.fst_item_name; 
					}
				},
				{"title" : "Unit","width": "50px",sortable:false,data:"fst_unit"},
				{"title" : "Q.MPS","width": "50px",sortable:false,className:'text-right',data:"fdb_qty_mps"},
				{"title" : "Q.W1","width": "50px",sortable:false,className:'text-right',data:"fdb_qty_w1"},
				{"title" : "Q.W2","width": "50px",sortable:false,className:'text-right',data:"fdb_qty_w2"},
				{"title" : "Q.W3","width": "50px",sortable:false,className:'text-right',data:"fdb_qty_w3"},
				{"title" : "Q.W4","width": "50px",sortable:false,className:'text-right',data:"fdb_qty_w4"},
				{"title" : "Q.W5","width": "50px",sortable:false,className:'text-right',data:"fdb_qty_w5"},
				{"title" : "Q.Ttl","width": "50px",sortable:false,className:'text-right',
					render:function(data,type,row){
						console.log(data);
						console.log(row);
						

						var total = row.fdb_qty_w1 + row.fdb_qty_w2 +row.fdb_qty_w3 +row.fdb_qty_w4 +row.fdb_qty_w5;
						var color = "unset";

						if (total < row.fdb_qty_mps){
							color = "red";
						}
						return "<span style='color:"+ color +"'>" + total + "</span>";
					}
				},			
				{"title" : "Action","width": "80px",sortable:false,className:'dt-body-center text-center',
					render: function(data,type,row){
						var action = '<a class="btn-edit" href="#" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp;';												
						//action += '<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';						
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
		}).on('click','.btn-edit',function(e){
			e.preventDefault();						
			//t = $("#tbldetails").DataTable();
			t = tblDetails;
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

<script type="text/javascript" info="event">
	$(function(){
		$("#btnNew").click(function(e){
			//e.preventDefault();
			window.location.replace("<?=site_url()?>tr/production/mrp/add");
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
			window.location.replace("<?=site_url()?>tr/production/mrp");
		});	

		$("#btn-add-items").click(function(e){
			e.preventDefault();
			mdlDetail.show();
		});

		$("#fin_mps_id,#fin_mps_month").change(function(e){

			$.ajax({
				url:"<?=site_url()?>tr/production/mrp/ajxGetMPSDetails",
				data:{
					fin_mps_id:$("#fin_mps_id").val(),
					fin_mps_month:$("#fin_mps_month").val()
				},
				method:"GET"
			}).done(function(resp){
				console.log(resp);
				if (resp.status== "SUCCESS"){
					tblDetails.clear();
					var details = resp.data;
					$.each(details,function(i,v){
						var data = {
							fin_rec_id:0,
							fin_item_id:v.fin_item_id,
							fst_item_code:v.fst_item_code,
							fst_item_name:v.fst_item_name,
							fst_unit:v.fst_unit,
							fdb_qty_mps:v.fdb_qty_mps,
							fdb_qty_w1:0,
							fdb_qty_w2:0,
							fdb_qty_w3:0,
							fdb_qty_w4:0,
							fdb_qty_w5:0,
						};

						tblDetails.row.add(data);
					});
					tblDetails.draw(false);
				}
			});

		});
		
		//$(".btn-process").click(function(e){
		$(document).on("click",".btn-process",function(e){
			e.preventDefault();
			var lvlProcess = $(this).data("level");
			var details = [];

			if (lvlProcess == 0){
				datas = tblDetails.data();
				$.each(datas,function(i,v){
					var tmp = {
						fin_item_id:v.fin_item_id,
						fst_unit:v.fst_unit,
						fdb_qty_w1:v.fdb_qty_w1,
						fdb_qty_w2:v.fdb_qty_w2,						
						fdb_qty_w3:v.fdb_qty_w3,
						fdb_qty_w4:v.fdb_qty_w4,
						fdb_qty_w5:v.fdb_qty_w5
					}
					details.push(tmp);
				})
			}else{
				tbl = $("#tbldetailsL" + lvlProcess).DataTable();
				datas = tbl.data()
				$.each(datas,function(i,v){
					var ttlWO = v.fdb_qty_wo_w1 + v.fdb_qty_wo_w2 + v.fdb_qty_wo_w3 + v.fdb_qty_wo_w4 + v.fdb_qty_wo_w5;
					if (ttlWO > 0){
						var tmp = {
							fin_item_id:v.fin_item_id,
							fst_unit:v.fst_unit,
							fdb_qty_w1:v.fdb_qty_wo_w1,
							fdb_qty_w2:v.fdb_qty_wo_w2,						
							fdb_qty_w3:v.fdb_qty_wo_w3,
							fdb_qty_w4:v.fdb_qty_wo_w4,
							fdb_qty_w5:v.fdb_qty_wo_w5
						}
						details.push(tmp);
					}
				});				
			}

			if(details.length == 0){
				alert("process done !");
				return;
			}


			$.ajax({
				url:"<?=site_url()?>tr/production/mrp/ajxGetMaterialDetails",
				method:"GET",
				data:{
					details:details,
					fin_mps_id:$("#fin_mps_id").val(),
					fin_mps_month:$("#fin_mps_month").val()
				}
			}).done(function(resp){
				if (resp.status == "SUCCESS"){					
					generateMaterialDetails(lvlProcess +1,resp.data);
				}								
			});
		});
		

		$(document).on("shown.bs.tab","#tabLevel a",function(e){			
			var level = $(this).data("level");
			$($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
		});

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
		
		var t =  tblDetails; //$('#tbldetails').DataTable();		
		var datas = t.data();

		$.each(datas,function(i,v){
			dataDetails.push(v);
		});

		data.push({
			name:"details",
			value: JSON.stringify(dataDetails)
		});

		var dataDetails2 = new Array();	
		var ttlLevel = $("#tabLevel > li").length;
		var i;
		for (i = 1; i <= ttlLevel ; i++) {
			t2 = $('#tbldetailsL' + i).DataTable();
			var datas = t2.data();
			$.each(datas,function(idx,v){
				var data = {
					fin_rec_id:v.fin_rec_id,
					fin_level:i,
					fin_item_id:v.fin_item_id,
					fst_unit:v.fst_unit,
					fdb_qty_rq_w1:v.fdb_qty_rq_w1,
					fdb_qty_po_w1:v.fdb_qty_po_w1,
					fdb_qty_wo_w1:v.fdb_qty_wo_w1,
					fdb_qty_rq_w2:v.fdb_qty_rq_w2,
					fdb_qty_po_w2:v.fdb_qty_po_w2,
					fdb_qty_wo_w2:v.fdb_qty_wo_w2,
					fdb_qty_rq_w3:v.fdb_qty_rq_w3,
					fdb_qty_po_w3:v.fdb_qty_po_w3,
					fdb_qty_wo_w3:v.fdb_qty_wo_w3,
					fdb_qty_rq_w4:v.fdb_qty_rq_w4,
					fdb_qty_po_w4:v.fdb_qty_po_w4,
					fdb_qty_wo_w4:v.fdb_qty_wo_w4,
					fdb_qty_rq_w5:v.fdb_qty_rq_w5,
					fdb_qty_po_w5:v.fdb_qty_po_w5,
					fdb_qty_wo_w5:v.fdb_qty_wo_w5										
				}

				dataDetails2.push(data);
			});
		}
		data.push({
			name:"details2",
			value: JSON.stringify(dataDetails2)
		});

		if (mode == "ADD"){
			url = "<?=site_url()?>tr/production/mrp/ajx_add_save";
		}else{			
			url = "<?=site_url()?>tr/production/mrp/ajx_edit_save";
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
				url:"<?= site_url() ?>tr/production/mrp/fetch_data/<?=$fin_mrp_id?>",
			}).done(function(resp){				
				if (resp.status == "SUCCESS"){
					data = resp.data;
					dataH =  data.header;
					if (dataH == null){
						alert("<?=lang("ID transaksi tidak dikenal")?>");
						return false;
					}				
					App.autoFillForm(dataH);
					App.addOptionIfNotExist("<option value='"+dataH.fin_mps_id+"'>"+dataH.fst_mps_no+"</option>","fin_mps_id");

					weekDetails =  data.weekDetails;
					$.each(weekDetails,function(i,v){
						var data = {
							fin_rec_id:v.fin_rec_id,
							fin_item_id:v.fin_item_id,
							fst_item_code:v.fst_item_code,
							fst_item_name:v.fst_item_name,
							fst_unit:v.fst_unit,
							fdb_qty_mps: parseFloat(v.fdb_qty_mps),
							fdb_qty_w1:parseFloat(v.fdb_qty_w1),
							fdb_qty_w2:parseFloat(v.fdb_qty_w2),
							fdb_qty_w3:parseFloat(v.fdb_qty_w3),
							fdb_qty_w4:parseFloat(v.fdb_qty_w4),
							fdb_qty_w5:parseFloat(v.fdb_qty_w5),
						};
						tblDetails.row.add(data);
					});
					tblDetails.draw(false);

					materialDetails =  data.materialDetails;
					var tabActive =0;
					var details = [];
					$.each(materialDetails,function(i,v){
						if (tabActive == 0 ){
							tabActive = v.fin_level;
						}

						if (v.fin_level != tabActive){
							generateMaterialDetails(tabActive,details);
							details = [];
							tabActive = v.fin_level; 
						}		
						var data = {
							fin_rec_id:v.fin_rec_id,
							fin_item_id:v.fin_item_id,
							fst_item_code:v.fst_item_code,
							fst_item_name:v.fst_item_name,
							fst_unit:v.fst_unit,
							fdb_qty_balance:v.fdb_qty_balance,
							fdb_qty_rq_w1:v.fdb_qty_rq_w1,
							fdb_qty_po_w1:v.fdb_qty_po_w1,
							fdb_qty_wo_w1:v.fdb_qty_wo_w1,
							fdb_qty_rq_w2:v.fdb_qty_rq_w2,
							fdb_qty_po_w2:v.fdb_qty_po_w2,
							fdb_qty_wo_w2:v.fdb_qty_wo_w2,
							fdb_qty_rq_w3:v.fdb_qty_rq_w3,
							fdb_qty_po_w3:v.fdb_qty_po_w3,
							fdb_qty_wo_w3:v.fdb_qty_wo_w3,
							fdb_qty_rq_w4:v.fdb_qty_rq_w4,
							fdb_qty_po_w4:v.fdb_qty_po_w4,
							fdb_qty_wo_w4:v.fdb_qty_wo_w4,
							fdb_qty_rq_w5:v.fdb_qty_rq_w5,
							fdb_qty_po_w5:v.fdb_qty_po_w5,
							fdb_qty_wo_w5:v.fdb_qty_wo_w5,
						};
						details.push(data);														
					});
					generateMaterialDetails(tabActive,details);
				}
				
				


				
				/*
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
				*/


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


	function generateMaterialDetails(level,details){


		if ($.fn.DataTable.isDataTable('#tbldetailsL' + level) ) {
			$('#tbldetailsL' + level).DataTable().destroy();
		}
		$("#li-level-" +level).remove();
		$("#detailL" +level).remove();

	
		//Add Tab List
		$("#tabLevel").append("<li id='li-level-" +level +"'><a data-level='"+level+"' data-toggle='tab' href='#detailL" + level +"'>Level " + level +"</a></li>");


		//add tab content
		var sstr ="";
		sstr += '<div id="detailL'+level+'" class="tab-pane fade in">';
		sstr += '<button class="btn btn-primary btn-process" data-level="'+level+'">Next Level</button>';
		sstr += '<table id="tbldetailsL'+level+'" class="table table-bordered table-hover table-striped nowarp row-border" style="width:2000px"></table>';
		sstr += '</div>';
		$("#tabContentLevel").append(sstr);
		$('#tabLevel a:last').tab('show');

		
		//Init Tabel
		$('#tbldetailsL' +level ).on('preXhr.dt', function ( e, settings, data ) {
			data.sessionId = "TEST SESSION ID";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "id","width": "0px",sortable:false,data:"fin_rec_id",visible:false},
				{"title" : "BOM Item","width": "300px",sortable:false,data:"fin_item_id",
					"render":function(data,type,row){
						return row.fst_item_code + " - " + row.fst_item_name; 
					}
				},
				{"title" : "Unit","width": "50px",sortable:false,data:"fst_unit"},
				{"title" : "Balance","width": "50px",sortable:false,className:"text-right",data:"fdb_qty_balance"},
				{"title" : "Q.Req W1","width": "80px",sortable:false,className:"text-right",data:"fdb_qty_rq_w1"},
				{"title" : "Q.PO W1","width": "80px",sortable:false,className:"text-right",data:"fdb_qty_po_w1"},
				{"title" : "Q.WO W1","width": "80px",sortable:false,className:"text-right",data:"fdb_qty_wo_w1"},
				{"title" : "Balance W1","width": "80px",sortable:false,className:"text-right",
					render:function(data,type,row){
						var balance =  parseFloat(row.fdb_qty_balance);
						var reqW1= parseFloat(row.fdb_qty_rq_w1);
						var diff1 = balance -reqW1;
						var powo1 = parseFloat(row.fdb_qty_po_w1) +  parseFloat(row.fdb_qty_wo_w1);
						var bow1 = diff1 + powo1;
						return bow1;
					}
				},
				{"title" : "Q.Req W2","width": "80px",sortable:false,className:"text-right",data:"fdb_qty_rq_w2"},
				{"title" : "Q.PO W2","width": "80px",sortable:false,className:"text-right",data:"fdb_qty_po_w2"},
				{"title" : "Q.WO W2","width": "80px",sortable:false,className:"text-right",data:"fdb_qty_wo_w2"},
				{"title" : "Balance W2","width": "80px",sortable:false,className:"text-right",
					render:function(data,type,row){
						var balance =  parseFloat(row.fdb_qty_balance);
						var reqW1= parseFloat(row.fdb_qty_rq_w1);
						var diff1 = balance -reqW1;
						var powo1 = parseFloat(row.fdb_qty_po_w1) +  parseFloat(row.fdb_qty_wo_w1);
						var bow1 = diff1 + powo1;

						var reqW2= parseFloat(row.fdb_qty_rq_w2);
						var powo2 = parseFloat(row.fdb_qty_po_w2) +  parseFloat(row.fdb_qty_wo_w2);
						var bow2 = bow1 - reqW2 + powo2
						return bow2;
					}
				},
				{"title" : "Q.Req W3","width": "80px",sortable:false,className:"text-right",data:"fdb_qty_rq_w3"},
				{"title" : "Q.PO W3","width": "80px",sortable:false,className:"text-right",data:"fdb_qty_po_w3"},
				{"title" : "Q.WO W3","width": "80px",sortable:false,className:"text-right",data:"fdb_qty_wo_w3"},
				{"title" : "Balance W3","width": "80px",sortable:false,className:"text-right",
					render:function(data,type,row){
						var balance =  parseFloat(row.fdb_qty_balance);
						var reqW1= parseFloat(row.fdb_qty_rq_w1);
						var diff1 = balance -reqW1;
						var powo1 = parseFloat(row.fdb_qty_po_w1) +  parseFloat(row.fdb_qty_wo_w1);
						var bow1 = diff1 + powo1;

						var reqW2= parseFloat(row.fdb_qty_rq_w2);
						var powo2 = parseFloat(row.fdb_qty_po_w2) +  parseFloat(row.fdb_qty_wo_w2);
						var bow2 = bow1 - reqW2 + powo2

						var reqW3= parseFloat(row.fdb_qty_rq_w3);
						var powo3 = parseFloat(row.fdb_qty_po_w3) +  parseFloat(row.fdb_qty_wo_w3);
						var bow3 = bow2 - reqW3 + powo3

						return bow3;

					}
				},
				{"title" : "Q.Req W4","width": "80px",sortable:false,className:"text-right",data:"fdb_qty_rq_w4"},
				{"title" : "Q.PO W4","width": "80px",sortable:false,className:"text-right",data:"fdb_qty_po_w4"},
				{"title" : "Q.WO W4","width": "80px",sortable:false,className:"text-right",data:"fdb_qty_wo_w4"},
				{"title" : "Balance W4","width": "80px",sortable:false,className:"text-right",
					render:function(data,type,row){
						var balance =  parseFloat(row.fdb_qty_balance);
						var reqW1= parseFloat(row.fdb_qty_rq_w1);
						var diff1 = balance -reqW1;
						var powo1 = parseFloat(row.fdb_qty_po_w1) +  parseFloat(row.fdb_qty_wo_w1);
						var bow1 = diff1 + powo1;

						var reqW2= parseFloat(row.fdb_qty_rq_w2);
						var powo2 = parseFloat(row.fdb_qty_po_w2) +  parseFloat(row.fdb_qty_wo_w2);
						var bow2 = bow1 - reqW2 + powo2

						var reqW3= parseFloat(row.fdb_qty_rq_w3);
						var powo3 = parseFloat(row.fdb_qty_po_w3) +  parseFloat(row.fdb_qty_wo_w3);
						var bow3 = bow2 - reqW3 + powo3

						var reqW4= parseFloat(row.fdb_qty_rq_w4);
						var powo4 = parseFloat(row.fdb_qty_po_w4) +  parseFloat(row.fdb_qty_wo_w4);
						var bow4 = bow3 - reqW4 + powo4

						return bow4;
					}
				},
				{"title" : "Q.Req W5","width": "80px",sortable:false,className:"text-right",data:"fdb_qty_rq_w5"},
				{"title" : "Q.PO W5","width": "80px",sortable:false,className:"text-right",data:"fdb_qty_po_w5"},
				{"title" : "Q.WO W5","width": "80px",sortable:false,className:"text-right",data:"fdb_qty_wo_w5"},												
				{"title" : "Action","width": "80px",sortable:false,className:'dt-body-center text-center',
					render: function(data,type,row){
						var action = '<a class="btn-edit2" href="#" data-level="'+level+'"><i class="fa fa-pencil"></i></a>&nbsp;';												
						//action += '<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';						
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
		}).on('click','.btn-edit2',function(e){
			e.preventDefault();				
			var level = $(this).data("level");		
			t = $("#tbldetailsL" + level).DataTable();
			//t = $("#tbldetails2").DataTable();
			var trRow = $(this).parents('tr');
			//selectedDetail2 = t.row(trRow);
			var detail = t.row(trRow);
			mdlDetail2.show(t,detail);
		}).on('click','.btn-delete',function(e){
			e.preventDefault();
			t = $('#tbldetails').DataTable();
			var trRow = $(this).parents('tr');
			t.row(trRow).remove().draw(false);
		});

		var tbl  =$('#tbldetailsL' +level ).DataTable();

		$.each(details,function(i,v){
			var data = {
				fin_rec_id:0,
				fin_item_id:v.fin_item_id,
				fst_item_code:v.fst_item_code,
				fst_item_name:v.fst_item_name,
				fst_unit:v.fst_unit,
				fdb_qty_balance:v.fdb_qty_balance,
				fdb_qty_rq_w1:v.fdb_qty_rq_w1,
				fdb_qty_po_w1: (typeof v.fdb_qty_po_w1 === "undefined") ? 0 : v.fdb_qty_po_w1,
				fdb_qty_wo_w1: (typeof v.fdb_qty_wo_w1 === "undefined") ? 0 : v.fdb_qty_wo_w1,
				fdb_qty_rq_w2:v.fdb_qty_rq_w2,
				fdb_qty_po_w2:(typeof v.fdb_qty_po_w2 === "undefined") ? 0 : v.fdb_qty_po_w2,
				fdb_qty_wo_w2:(typeof v.fdb_qty_wo_w2 === "undefined") ? 0 : v.fdb_qty_wo_w2,
				fdb_qty_rq_w3:v.fdb_qty_rq_w3,
				fdb_qty_po_w3:(typeof v.fdb_qty_po_w3 === "undefined") ? 0 : v.fdb_qty_po_w3,
				fdb_qty_wo_w3:(typeof v.fdb_qty_wo_w3 === "undefined") ? 0 : v.fdb_qty_wo_w3,
				fdb_qty_rq_w4:v.fdb_qty_rq_w4,
				fdb_qty_po_w4:(typeof v.fdb_qty_po_w4 === "undefined") ? 0 : v.fdb_qty_po_w4,
				fdb_qty_wo_w4:(typeof v.fdb_qty_wo_w4 === "undefined") ? 0 : v.fdb_qty_wo_w4,
				fdb_qty_rq_w5:v.fdb_qty_rq_w5,
				fdb_qty_po_w5:(typeof v.fdb_qty_po_w5 === "undefined") ? 0 : v.fdb_qty_po_w5,
				fdb_qty_wo_w5:(typeof v.fdb_qty_wo_w5 === "undefined") ? 0 : v.fdb_qty_wo_w5,			
			};
			tbl.row.add(data);
			
		});
		tbl.draw(false);
	}
	

</script>


<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
