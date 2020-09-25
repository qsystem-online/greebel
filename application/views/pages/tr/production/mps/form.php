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
	<h1><?=lang("Master Production Schedule")?><small><?=lang("form")?></small></h1>
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
					<a id="btnPrint" class="btn btn-primary hide" href="#" title="<?=lang("Cetak")?>"><i class="fa fa-print" aria-hidden="true"></i></a>
					<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
					<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Group")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
				</div>
			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmHeader" class="form-horizontal" action="" method="POST" >
				<div class="box-body">
					<input type="hidden" id="fin_mps_id" name="fin_mps_id" value="<?=$fin_mps_id?>"/>

					<div class="form-group">
						<label for="fst_mps_no" class="col-md-2 control-label"><?=lang("MPS")?> #</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_mps_no" placeholder="<?=lang("MPS No")?>" name="fst_mps_no" value="<?=$fst_mps_no?>"/>
							<div id="fst_mps_no_err" class="text-danger"></div>
						</div>								

						<label for="fdt_mps_datetime" class="col-md-2 control-label"><?=lang("Tanggal")?></label>
						<div class="col-md-4">
							<input type="text" class="form-control datetimepicker text-right" id="fdt_mps_datetime" placeholder="<?=lang("MPS Datetime")?>" name="fdt_mps_datetime" value=""/>
							<div id="fdt_mps_datetime_err" class="text-danger"></div>
						</div>								
                    </div>  

					<div class="form-group">
						<label for="fin_year" class="col-md-2 control-label"><?=lang("Tahun")?></label>
						<div class="col-md-4">
							<input type='TEXT' name='fin_year' class='form-control text-right' id='fin_year' value='2020'/>
							<div id="fin_year_err" class="text-danger"></div>
						</div>					
					</div>
					
					<div class="form-group">					
						<label for="fin_item_id" class="col-md-2 control-label"><?=lang("Group Item")?></label>
						<div class="col-md-10">
							<select  class="form-control" id="fin_item_group_id" placeholder="<?=lang("Group Item")?>" name="fin_item_group_id" style="width:100%"></select>
							<div id="fin_item_group_id_err" class="text-danger"></div>
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
					</div>

					<div class="form-group">							
						<div class="col-md-12">
							<label class="control-label text-left">*BEO : Balance End Of</label>
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
<?php
    echo $mdlItemGroup;
?>

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
						<label class="col-md-10 control-label" id="fst_item_d">Pencil warna greebel - Box</label>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label text-left"><?=lang("Buffer")?>:</label>
						<div class="col-md-4 col-md-offset-6">
							<input type='TEXT' id="fdb_qty_buffer_stock" class="form-control text-right" value="50" style="background-color:#2cddea"/>
						</div>
							
					</div>

					<div class="form-group">
						<label class="col-md-4 control-label"><?=lang("Balance")?></label>
						<label class="col-md-4 control-label"><?=lang("MTS")?></label>
						<label class="col-md-4 control-label"><?=lang("Qty")?></label>						
					</div>

					<div class="form-group">
						<label class="col-md-1 control-label" >1</label>
						<label class="col-md-3 control-label" id="fdb_last_period_qty">0</label>
						<label class="col-md-4 control-label" id="fdb_qty_mts_m01" >0</label>
						<div class="col-md-4">
							<input type='TEXT' id="fdb_qty_m01" class="form-control text-right qty-mps" value="0"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">2</label>
						<label class="col-md-3 control-label" id="fdb_beo_m01">50</label>
						<label class="col-md-4 control-label" id="fdb_qty_mts_m02">500</label>
						<div class="col-md-4">
							<input type='TEXT' id="fdb_qty_m02" class="form-control text-right qty-mps" value="0"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">3</label>
						<label class="col-md-3 control-label" id="fdb_beo_m02">50</label>
						<label class="col-md-4 control-label" id="fdb_qty_mts_m03">500</label>						
						<div class="col-md-4">
							<input type='TEXT' id="fdb_qty_m03" class="form-control text-right qty-mps" value="0"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">4</label>
						<label class="col-md-3 control-label" id="fdb_beo_m03">50</label>
						<label class="col-md-4 control-label" id="fdb_qty_mts_m04">500</label>					
						<div class="col-md-4">
							<input type='TEXT' id="fdb_qty_m04" class="form-control text-right qty-mps" value="0"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">5</label>
						<label class="col-md-3 control-label" id="fdb_beo_m04">50</label>
						<label class="col-md-4 control-label" id="fdb_qty_mts_m05">500</label>						
						<div class="col-md-4">
							<input type='TEXT' id="fdb_qty_m05" class="form-control text-right qty-mps" value="0"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">6</label>
						<label class="col-md-3 control-label" id="fdb_beo_m05">50</label>
						<label class="col-md-4 control-label" id="fdb_qty_mts_m06">500</label>						
						<div class="col-md-4">
							<input type='TEXT' id="fdb_qty_m06" class="form-control text-right qty-mps" value="0"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">7</label>
						<label class="col-md-3 control-label" id="fdb_beo_m06">50</label>
						<label class="col-md-4 control-label" id="fdb_qty_mts_m07">500</label>						
						<div class="col-md-4">
							<input type='TEXT' id="fdb_qty_m07" class="form-control text-right qty-mps" value="0"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">8</label>
						<label class="col-md-3 control-label" id="fdb_beo_m07">50</label>
						<label class="col-md-4 control-label" id="fdb_qty_mts_m08">500</label>						
						<div class="col-md-4">
							<input type='TEXT' id="fdb_qty_m08" class="form-control text-right qty-mps" value="0"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">9</label>
						<label class="col-md-3 control-label" id="fdb_beo_m08">50</label>
						<label class="col-md-4 control-label" id="fdb_qty_mts_m09">500</label>						
						<div class="col-md-4">
							<input type='TEXT' id="fdb_qty_m09" class="form-control text-right qty-mps" value="0"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">10</label>
						<label class="col-md-3 control-label" id="fdb_beo_m09">50</label>
						<label class="col-md-4 control-label" id="fdb_qty_mts_m10">500</label>						
						<div class="col-md-4">
							<input type='TEXT' id="fdb_qty_m10" class="form-control text-right qty-mps" value="0"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">11</label>
						<label class="col-md-3 control-label" id="fdb_beo_m10">50</label>
						<label class="col-md-4 control-label" id="fdb_qty_mts_m11">500</label>						
						<div class="col-md-4">
							<input type='TEXT' id="fdb_qty_m11" class="form-control text-right qty-mps" value="0"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">12</label>
						<label class="col-md-3 control-label" id="fdb_beo_m11">50</label>
						<label class="col-md-4 control-label" id="fdb_qty_mts_m12">500</label>						
						<div class="col-md-4">
							<input type='TEXT' id="fdb_qty_m12" class="form-control text-right qty-mps" value="0"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">Last</label>
						<label class="col-md-3 control-label" id="fdb_beo_m12" style="background-color:#2dee74">50</label>
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
					console.log(data);
					$("#fst_item").text(data.fst_item_code + " - " + data.fst_item_name + " (" + data.fst_unit + ")");
					$("#fdb_qty_buffer_stock").text(data.fdb_qty_buffer_stock);
					$("#fdb_last_period_qty").text(data.fdb_last_period_qty);
					$("#fdb_qty_mts_m01").text(data.fdb_qty_mts_m01);
					$("#fdb_qty_mts_m02").text(data.fdb_qty_mts_m02);
					$("#fdb_qty_mts_m03").text(data.fdb_qty_mts_m03);
					$("#fdb_qty_mts_m04").text(data.fdb_qty_mts_m04);
					$("#fdb_qty_mts_m05").text(data.fdb_qty_mts_m05);
					$("#fdb_qty_mts_m06").text(data.fdb_qty_mts_m06);
					$("#fdb_qty_mts_m07").text(data.fdb_qty_mts_m07);
					$("#fdb_qty_mts_m08").text(data.fdb_qty_mts_m08);
					$("#fdb_qty_mts_m09").text(data.fdb_qty_mts_m09);
					$("#fdb_qty_mts_m10").text(data.fdb_qty_mts_m10);
					$("#fdb_qty_mts_m11").text(data.fdb_qty_mts_m11);
					$("#fdb_qty_mts_m12").text(data.fdb_qty_mts_m12);
					
					
					$("#fdb_qty_m01").val(data.fdb_qty_m01);
					$("#fdb_qty_m02").val(data.fdb_qty_m02);
					$("#fdb_qty_m03").val(data.fdb_qty_m03);
					$("#fdb_qty_m04").val(data.fdb_qty_m04);
					$("#fdb_qty_m05").val(data.fdb_qty_m05);
					$("#fdb_qty_m06").val(data.fdb_qty_m06);
					$("#fdb_qty_m07").val(data.fdb_qty_m07);
					$("#fdb_qty_m08").val(data.fdb_qty_m08);
					$("#fdb_qty_m09").val(data.fdb_qty_m09);
					$("#fdb_qty_m10").val(data.fdb_qty_m10);
					$("#fdb_qty_m11").val(data.fdb_qty_m11);
					$("#fdb_qty_m12").val(data.fdb_qty_m12);
					

					regenerateDetailForm();					


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

	
	<script type="text/javascript" info="init">
		$(function(){			
		});
	</script>

	<script type="text/javascript" info="event">		
		$(function(){

			$(".qty-mps").change(function(e){
				e.preventDefault();
				regenerateDetailForm();
			});

			$("#btn-save-detail").click(function(e){
				t = tbldetails;
				var dataOLD = selectedDetail.data();
				
				var data = {
					fin_rec_id:dataOLD.fin_rec_id,
					fin_item_id:dataOLD.fin_item_id,
					fst_item_code:dataOLD.fst_item_code,
					fst_item_name:dataOLD.fst_item_name,
					fst_unit:dataOLD.fst_unit,
					fdb_qty_buffer_stock:parseFloat($("#fdb_qty_buffer_stock").val()),
					fdb_last_period_qty:parseFloat(dataOLD.fdb_last_period_qty),
					fdb_qty_mts_m01:parseFloat(dataOLD.fdb_qty_mts_m01),
					fdb_qty_m01:parseFloat($("#fdb_qty_m01").val()),
					fdb_qty_mts_m02:dataOLD.fdb_qty_mts_m02,
					fdb_qty_m02:parseFloat($("#fdb_qty_m02").val()),
					fdb_qty_mts_m03:dataOLD.fdb_qty_mts_m03,
					fdb_qty_m03:parseFloat($("#fdb_qty_m03").val()),
					fdb_qty_mts_m04:dataOLD.fdb_qty_mts_m04,
					fdb_qty_m04:parseFloat($("#fdb_qty_m04").val()),
					fdb_qty_mts_m05:dataOLD.fdb_qty_mts_m05,
					fdb_qty_m05:parseFloat($("#fdb_qty_m05").val()),
					fdb_qty_mts_m06:dataOLD.fdb_qty_mts_m06,
					fdb_qty_m06:parseFloat($("#fdb_qty_m06").val()),
					fdb_qty_mts_m07:dataOLD.fdb_qty_mts_m07,
					fdb_qty_m07:parseFloat($("#fdb_qty_m07").val()),
					fdb_qty_mts_m08:dataOLD.fdb_qty_mts_m08,
					fdb_qty_m08:parseFloat($("#fdb_qty_m08").val()),
					fdb_qty_mts_m09:dataOLD.fdb_qty_mts_m09,
					fdb_qty_m09:parseFloat($("#fdb_qty_m09").val()),
					fdb_qty_mts_m10:dataOLD.fdb_qty_mts_m10,
					fdb_qty_m10:parseFloat($("#fdb_qty_m10").val()),
					fdb_qty_mts_m11:dataOLD.fdb_qty_mts_m11,
					fdb_qty_m11:parseFloat($("#fdb_qty_m11").val()),
					fdb_qty_mts_m12:dataOLD.fdb_qty_mts_m12,
					fdb_qty_m12:parseFloat($("#fdb_qty_m12").val())
				}						
				t.row(selectedDetail).data(data).draw(false);
				mdlDetail.clear();		
				mdlDetail.hide();		
			});

		});
	</script>

	<script type="text/javascript" info="function">
		function regenerateDetailForm(){
			var beoM01 = parseFloat($("#fdb_last_period_qty").text()) - parseFloat($("#fdb_qty_mts_m01").text()) + parseFloat($("#fdb_qty_m01").val());
			var beoM02 = beoM01 - parseFloat($("#fdb_qty_mts_m02").text()) + parseFloat($("#fdb_qty_m02").val());
			var beoM03 = beoM02 - parseFloat($("#fdb_qty_mts_m03").text()) + parseFloat($("#fdb_qty_m03").val());
			var beoM04 = beoM03 - parseFloat($("#fdb_qty_mts_m04").text()) + parseFloat($("#fdb_qty_m04").val());
			var beoM05 = beoM04 - parseFloat($("#fdb_qty_mts_m05").text()) + parseFloat($("#fdb_qty_m05").val());
			var beoM06 = beoM05 - parseFloat($("#fdb_qty_mts_m06").text()) + parseFloat($("#fdb_qty_m06").val());
			var beoM07 = beoM06 - parseFloat($("#fdb_qty_mts_m07").text()) + parseFloat($("#fdb_qty_m07").val());
			var beoM08 = beoM07 - parseFloat($("#fdb_qty_mts_m08").text()) + parseFloat($("#fdb_qty_m08").val());
			var beoM09 = beoM08 - parseFloat($("#fdb_qty_mts_m09").text()) + parseFloat($("#fdb_qty_m09").val());
			var beoM10 = beoM09 - parseFloat($("#fdb_qty_mts_m10").text()) + parseFloat($("#fdb_qty_m10").val());
			var beoM11 = beoM10 - parseFloat($("#fdb_qty_mts_m11").text()) + parseFloat($("#fdb_qty_m11").val());
			var beoM12 = beoM11 - parseFloat($("#fdb_qty_mts_m12").text()) + parseFloat($("#fdb_qty_m12").val());

			$("#fdb_beo_m01").text(beoM01);
			$("#fdb_beo_m02").text(beoM02);
			$("#fdb_beo_m03").text(beoM03);
			$("#fdb_beo_m04").text(beoM04);
			$("#fdb_beo_m05").text(beoM05);
			$("#fdb_beo_m06").text(beoM06);
			$("#fdb_beo_m07").text(beoM07);
			$("#fdb_beo_m08").text(beoM08);
			$("#fdb_beo_m09").text(beoM09);
			$("#fdb_beo_m10").text(beoM10);
			$("#fdb_beo_m11").text(beoM11);
			$("#fdb_beo_m12").text(beoM12);			

		}
	</script>

</div>

<?php echo $mdlEditForm ?>
<?php echo $mdlPrint ?>
<?php echo $mdlJurnal ?>

<script type="text/javascript" info="define">
	var selectedDetail;	
	var tbldetails;

</script>

<script type="text/javascript" info="bind">
	$(document).bind('keydown', 'alt+d', function(){
		$("#btn-add-detail").trigger("click");
	});
</script>

<script type="text/javascript" info="init">
	$(function(){		
		$("#fdt_mps_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");

		$("#fin_item_group_id").select2({
            width: '100%',
            ajax: {
                url: '<?= site_url() ?>master/item/get_data_ItemGroupId',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    data2 = [];
                    $.each(data, function(index, value) {
                        data2.push({
                            "id": value.fin_item_group_id,
                            "text": value.fst_item_group_name
                        });
                    });
                    return {
                        results: data2
                    };
                },
                cache: true,
            }
        }).on("select2:open",function(e){
            e.preventDefault();
			$(this).select2("close");
			var leafOnly =true;
            showItemGroup(leafOnly,function(node){
                //consoleLog(node);                
                $("#fin_item_group_id").empty();
                var newOption = new Option(node.text,node.id, false, false);
				$('#fin_item_group_id').append(newOption).trigger('change');
				getDetail(function(details){
					fillDetails(details)
				});
            });
		});

		

		
		tbldetails =  $('#tbldetails').on('preXhr.dt', function ( e, settings, data ) {
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
						return row.fst_item_code + "<br><i>" + row.fst_item_name + "</i>"; 
					}
				},
				{"title" : "Unit","width": "50px",sortable:false,data:"fst_unit"},
				{"title" : "Buff","width": "10px",sortable:false,data:"fdb_qty_buffer_stock"},
				{"title" : "Balance","width": "10px",sortable:false,data:"fdb_last_period_qty"},
				
				{"title" : "MTS M1","width": "40px",sortable:false,data:"fdb_qty_mts_m01"},
				{"title" : "M1","width": "10px",sortable:false,data:"fdb_qty_m01"},
				{"title" : "*BEO M1","width": "20px",sortable:false,
					render:function(data,type,row){
						var beoM01 = row.fdb_last_period_qty + row.fdb_qty_m01 - row.fdb_qty_mts_m01;
						return beoM01;
					}
				},

				{"title" : "MTS M2","width": "10px",sortable:false,data:"fdb_qty_mts_m02"},
				{"title" : "M2","width": "10px",sortable:false,data:"fdb_qty_m02"},
				{"title" : "*BEO M2","width": "10px",sortable:false,
					render:function(data,type,row){
						var beoM01 = row.fdb_last_period_qty + row.fdb_qty_m01 - row.fdb_qty_mts_m01;
						var beoM02 = beoM01 + row.fdb_qty_m02 - row.fdb_qty_mts_m02;
						return beoM02;
					}
				},

				{"title" : "MTS M3","width": "10px",sortable:false,data:"fdb_qty_mts_m03"},
				{"title" : "M3","width": "10px",sortable:false,data:"fdb_qty_m03"},
				{"title" : "*BEO M3","width": "10px",sortable:false,
					render:function(data,type,row){
						var beoM01 = row.fdb_last_period_qty + row.fdb_qty_m01 - row.fdb_qty_mts_m01;
						var beoM02 = beoM01 + row.fdb_qty_m02 - row.fdb_qty_mts_m02;
						var beoM03 = beoM02 + row.fdb_qty_m03 - row.fdb_qty_mts_m03;
						return beoM03;
					}
				},

				{"title" : "MTS M4","width": "10px",sortable:false,data:"fdb_qty_mts_m04"},
				{"title" : "M4","width": "10px",sortable:false,data:"fdb_qty_m04"},
				{"title" : "*BEO M4","width": "10px",sortable:false,
					render:function(data,type,row){
						var beoM01 = row.fdb_last_period_qty + row.fdb_qty_m01 - row.fdb_qty_mts_m01;
						var beoM02 = beoM01 + row.fdb_qty_m02 - row.fdb_qty_mts_m02;
						var beoM03 = beoM02 + row.fdb_qty_m03 - row.fdb_qty_mts_m03;
						var beoM04 = beoM03 + row.fdb_qty_m04 - row.fdb_qty_mts_m04;
						return beoM04;
					}
				},

				{"title" : "MTS M5","width": "10px",sortable:false,data:"fdb_qty_mts_m05"},
				{"title" : "M5","width": "10px",sortable:false,data:"fdb_qty_m05"},
				{"title" : "*BEO M5","width": "10px",sortable:false,
					render:function(data,type,row){
						var beoM01 = row.fdb_last_period_qty + row.fdb_qty_m01 - row.fdb_qty_mts_m01;
						var beoM02 = beoM01 + row.fdb_qty_m02 - row.fdb_qty_mts_m02;
						var beoM03 = beoM02 + row.fdb_qty_m03 - row.fdb_qty_mts_m03;
						var beoM04 = beoM03 + row.fdb_qty_m04 - row.fdb_qty_mts_m04;
						var beoM05 = beoM04 + row.fdb_qty_m05 - row.fdb_qty_mts_m05;
						return beoM05;
					}
				},

				{"title" : "MTS M6","width": "10px",sortable:false,data:"fdb_qty_mts_m06"},
				{"title" : "M6","width": "10px",sortable:false,data:"fdb_qty_m06"},
				{"title" : "*BEO M","width": "10px",sortable:false,
					render:function(data,type,row){
						var beoM01 = row.fdb_last_period_qty + row.fdb_qty_m01 - row.fdb_qty_mts_m01;
						var beoM02 = beoM01 + row.fdb_qty_m02 - row.fdb_qty_mts_m02;
						var beoM03 = beoM02 + row.fdb_qty_m03 - row.fdb_qty_mts_m03;
						var beoM04 = beoM03 + row.fdb_qty_m04 - row.fdb_qty_mts_m04;
						var beoM05 = beoM04 + row.fdb_qty_m05 - row.fdb_qty_mts_m05;
						var beoM06 = beoM05 + row.fdb_qty_m06 - row.fdb_qty_mts_m06;
						return beoM06;
					}
				},

				{"title" : "MTS M7","width": "10px",sortable:false,data:"fdb_qty_mts_m07"},
				{"title" : "M7","width": "10px",sortable:false,data:"fdb_qty_m07"},
				{"title" : "*BEO M7","width": "10px",sortable:false,
					render:function(data,type,row){
						var beoM01 = row.fdb_last_period_qty + row.fdb_qty_m01 - row.fdb_qty_mts_m01;
						var beoM02 = beoM01 + row.fdb_qty_m02 - row.fdb_qty_mts_m02;
						var beoM03 = beoM02 + row.fdb_qty_m03 - row.fdb_qty_mts_m03;
						var beoM04 = beoM03 + row.fdb_qty_m04 - row.fdb_qty_mts_m04;
						var beoM05 = beoM04 + row.fdb_qty_m05 - row.fdb_qty_mts_m05;
						var beoM06 = beoM05 + row.fdb_qty_m06 - row.fdb_qty_mts_m06;
						var beoM07 = beoM06 + row.fdb_qty_m07 - row.fdb_qty_mts_m07;
						return beoM07;
					}
				},

				{"title" : "MTS M8","width": "10px",sortable:false,data:"fdb_qty_mts_m08"},
				{"title" : "M8","width": "10px",sortable:false,data:"fdb_qty_m08"},
				{"title" : "*BEO M8","width": "10px",sortable:false,
					render:function(data,type,row){
						var beoM01 = row.fdb_last_period_qty + row.fdb_qty_m01 - row.fdb_qty_mts_m01;
						var beoM02 = beoM01 + row.fdb_qty_m02 - row.fdb_qty_mts_m02;
						var beoM03 = beoM02 + row.fdb_qty_m03 - row.fdb_qty_mts_m03;
						var beoM04 = beoM03 + row.fdb_qty_m04 - row.fdb_qty_mts_m04;
						var beoM05 = beoM04 + row.fdb_qty_m05 - row.fdb_qty_mts_m05;
						var beoM06 = beoM05 + row.fdb_qty_m06 - row.fdb_qty_mts_m06;
						var beoM07 = beoM06 + row.fdb_qty_m07 - row.fdb_qty_mts_m07;
						var beoM08 = beoM07 + row.fdb_qty_m08 - row.fdb_qty_mts_m08;
						return beoM08;
					}
				},

				{"title" : "MTS M9","width": "10px",sortable:false,data:"fdb_qty_mts_m09"},
				{"title" : "M9","width": "10px",sortable:false,data:"fdb_qty_m09"},
				{"title" : "*BEO M9","width": "10px",sortable:false,
					render:function(data,type,row){
						var beoM01 = row.fdb_last_period_qty + row.fdb_qty_m01 - row.fdb_qty_mts_m01;
						var beoM02 = beoM01 + row.fdb_qty_m02 - row.fdb_qty_mts_m02;
						var beoM03 = beoM02 + row.fdb_qty_m03 - row.fdb_qty_mts_m03;
						var beoM04 = beoM03 + row.fdb_qty_m04 - row.fdb_qty_mts_m04;
						var beoM05 = beoM04 + row.fdb_qty_m05 - row.fdb_qty_mts_m05;
						var beoM06 = beoM05 + row.fdb_qty_m06 - row.fdb_qty_mts_m06;
						var beoM07 = beoM06 + row.fdb_qty_m07 - row.fdb_qty_mts_m07;
						var beoM08 = beoM07 + row.fdb_qty_m08 - row.fdb_qty_mts_m08;
						var beoM09 = beoM08 + row.fdb_qty_m09 - row.fdb_qty_mts_m09;
						return beoM09;
					}
				},

				{"title" : "MTS M10","width": "10px",sortable:false,data:"fdb_qty_mts_m10"},
				{"title" : "M10","width": "10px",sortable:false,data:"fdb_qty_m10"},
				{"title" : "*BEO M10","width": "10px",sortable:false,
					render:function(data,type,row){
						var beoM01 = row.fdb_last_period_qty + row.fdb_qty_m01 - row.fdb_qty_mts_m01;
						var beoM02 = beoM01 + row.fdb_qty_m02 - row.fdb_qty_mts_m02;
						var beoM03 = beoM02 + row.fdb_qty_m03 - row.fdb_qty_mts_m03;
						var beoM04 = beoM03 + row.fdb_qty_m04 - row.fdb_qty_mts_m04;
						var beoM05 = beoM04 + row.fdb_qty_m05 - row.fdb_qty_mts_m05;
						var beoM06 = beoM05 + row.fdb_qty_m06 - row.fdb_qty_mts_m06;
						var beoM07 = beoM06 + row.fdb_qty_m07 - row.fdb_qty_mts_m07;
						var beoM08 = beoM07 + row.fdb_qty_m08 - row.fdb_qty_mts_m08;
						var beoM09 = beoM08 + row.fdb_qty_m09 - row.fdb_qty_mts_m09;
						var beoM10 = beoM09 + row.fdb_qty_m10 - row.fdb_qty_mts_m10;
						return beoM10;
					}
				},

				{"title" : "MTS M11","width": "10px",sortable:false,data:"fdb_qty_mts_m11"},
				{"title" : "M11","width": "10px",sortable:false,data:"fdb_qty_m11"},
				{"title" : "*BEO M11","width": "10px",sortable:false,
					render:function(data,type,row){
						var beoM01 = row.fdb_last_period_qty + row.fdb_qty_m01 - row.fdb_qty_mts_m01;
						var beoM02 = beoM01 + row.fdb_qty_m02 - row.fdb_qty_mts_m02;
						var beoM03 = beoM02 + row.fdb_qty_m03 - row.fdb_qty_mts_m03;
						var beoM04 = beoM03 + row.fdb_qty_m04 - row.fdb_qty_mts_m04;
						var beoM05 = beoM04 + row.fdb_qty_m05 - row.fdb_qty_mts_m05;
						var beoM06 = beoM05 + row.fdb_qty_m06 - row.fdb_qty_mts_m06;
						var beoM07 = beoM06 + row.fdb_qty_m07 - row.fdb_qty_mts_m07;
						var beoM08 = beoM07 + row.fdb_qty_m08 - row.fdb_qty_mts_m08;
						var beoM09 = beoM08 + row.fdb_qty_m09 - row.fdb_qty_mts_m09;
						var beoM10 = beoM09 + row.fdb_qty_m10 - row.fdb_qty_mts_m10;
						var beoM11 = beoM10 + row.fdb_qty_m11 - row.fdb_qty_mts_m11;
						return beoM11;
					}
				},

				{"title" : "MTS M12","width": "10px",sortable:false,data:"fdb_qty_mts_m12"},
				{"title" : "M12","width": "10px",sortable:false,data:"fdb_qty_m12"},
				{"title" : "*BEO M12","width": "10px",sortable:false,
					render:function(data,type,row){
						var beoM01 = row.fdb_last_period_qty + row.fdb_qty_m01 - row.fdb_qty_mts_m01;
						var beoM02 = beoM01 + row.fdb_qty_m02 - row.fdb_qty_mts_m02;
						var beoM03 = beoM02 + row.fdb_qty_m03 - row.fdb_qty_mts_m03;
						var beoM04 = beoM03 + row.fdb_qty_m04 - row.fdb_qty_mts_m04;
						var beoM05 = beoM04 + row.fdb_qty_m05 - row.fdb_qty_mts_m05;
						var beoM06 = beoM05 + row.fdb_qty_m06 - row.fdb_qty_mts_m06;
						var beoM07 = beoM06 + row.fdb_qty_m07 - row.fdb_qty_mts_m07;
						var beoM08 = beoM07 + row.fdb_qty_m08 - row.fdb_qty_mts_m08;
						var beoM09 = beoM08 + row.fdb_qty_m09 - row.fdb_qty_mts_m09;
						var beoM10 = beoM09 + row.fdb_qty_m10 - row.fdb_qty_mts_m10;
						var beoM11 = beoM10 + row.fdb_qty_m11 - row.fdb_qty_mts_m11;
						var beoM12 = beoM11 + row.fdb_qty_m12 - row.fdb_qty_mts_m12;
						return beoM12;
					}
				},


				{"title" : "Action","width": "80px",sortable:false,className:'dt-body-center text-center',
					render: function(data,type,row){
						var action = '<a class="btn-edit" href="#" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp;';												
						action += '<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';						
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
			t = tbldetails;
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
			window.location.replace("<?=site_url()?>tr/production/mps/add");
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
			window.location.replace("<?=site_url()?>tr/production/mps");
		});	

		$("#fin_year").change(function(e){
			e.preventDefault();
			getDetail(function(details){
				fillDetails(details)
			});
		});

		$("#btn-add-items").click(function(e){
			e.preventDefault();
			mdlDetail.show();
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
		
		var t = $('#tbldetails').DataTable();
		var datas = t.data();

		$.each(datas,function(i,v){
			dataDetails.push(v);
		});

		data.push({
			name:"details",
			value: JSON.stringify(dataDetails)
		});

		if (mode == "ADD"){
			url = "<?=site_url()?>tr/production/mps/ajx_add_save";
		}else{			
			url = "<?=site_url()?>tr/production/mps/ajx_edit_save";
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
				url:"<?= site_url() ?>tr/production/mps/fetch_data/<?=$fin_mps_id?>",
			}).done(function(resp){	
				if (resp.status == "SUCCESS"){
					var data = resp.data;
					var header = data.header;
					if (header != null){

						App.autoFillForm(header);
						$("#fdt_mps_datetime").val(dateTimeFormat(header.fdt_mps_datetime)).datetimepicker("update");	
						App.addOptionIfNotExist("<option value='"+header.fin_item_group_id+"'>"+header.fst_item_group_name +"</option>","fin_item_group_id");
						var details = data.details;
						fillDetails(details);
					}else{
						alert("<?=lang("ID transaksi tidak dikenal")?>");
						return false;
					}					
				}else{
					alert(resp.message);				
				}
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

		var url =  "<?= site_url() ?>tr/production/mps/delete/" + $("#fin_mps_id").val();
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

	function getDetail(callback){
		$.ajax({
			url:"<?=site_url()?>tr/production/mps/ajxGetDetailItems",
			data:{
				fin_year:$("#fin_year").val(),
				fin_item_group_id:$("#fin_item_group_id").val()
			}
		}).done(function(resp){
			if (resp.status =="SUCCESS"){
				data = resp.data;
				data.fdb_qty_m01 = 0;
				data.fdb_qty_m02 = 0;
				data.fdb_qty_m03 = 0;
				data.fdb_qty_m04 = 0;
				data.fdb_qty_m05 = 0;
				data.fdb_qty_m06 = 0;
				data.fdb_qty_m07 = 0;
				data.fdb_qty_m08 = 0;
				data.fdb_qty_m09 = 0;
				data.fdb_qty_m10 = 0;
				data.fdb_qty_m11 = 0;
				data.fdb_qty_m12 = 0;
				
				callback(data);
			}else{
				alert(resp.messages);
			}
		});
	}

	function fillDetails(details){
		
		tbldetails.clear();
		$.each(details, function(i,v){
			//console.log(v);
			var data = {
				fin_rec_id:0,
				fin_item_id:v.fin_item_id,
				fst_item_code:v.fst_item_code,
				fst_item_name:v.fst_item_name,
				fst_unit:v.fst_unit,
				fdb_qty_buffer_stock:parseFloat(v.fdb_qty_buffer_stock),
				fdb_last_period_qty:parseFloat(v.fdb_last_period_qty),
				fdb_qty_mts_m01:parseFloat(v.fdb_qty_mts_m01),
				fdb_qty_m01:parseFloat(v.fdb_qty_m01),
				fdb_qty_mts_m02:parseFloat(v.fdb_qty_mts_m02),
				fdb_qty_m02:parseFloat(v.fdb_qty_m02),
				fdb_qty_mts_m03:parseFloat(v.fdb_qty_mts_m03),
				fdb_qty_m03:parseFloat(v.fdb_qty_m03),
				fdb_qty_mts_m04:parseFloat(v.fdb_qty_mts_m04),
				fdb_qty_m04:parseFloat(v.fdb_qty_m04),
				fdb_qty_mts_m05:parseFloat(v.fdb_qty_mts_m05),
				fdb_qty_m05:parseFloat(v.fdb_qty_m05),
				fdb_qty_mts_m06:parseFloat(v.fdb_qty_mts_m06),
				fdb_qty_m06:parseFloat(v.fdb_qty_m06),
				fdb_qty_mts_m07:parseFloat(v.fdb_qty_mts_m07),
				fdb_qty_m07:parseFloat(v.fdb_qty_m07),
				fdb_qty_mts_m08:parseFloat(v.fdb_qty_mts_m08),
				fdb_qty_m08:parseFloat(v.fdb_qty_m08),
				fdb_qty_mts_m09:parseFloat(v.fdb_qty_mts_m09),
				fdb_qty_m09:parseFloat(v.fdb_qty_m09),
				fdb_qty_mts_m10:parseFloat(v.fdb_qty_mts_m10),
				fdb_qty_m10:parseFloat(v.fdb_qty_m10),
				fdb_qty_mts_m11:parseFloat(v.fdb_qty_mts_m11),
				fdb_qty_m11:parseFloat(v.fdb_qty_m11),
				fdb_qty_mts_m12:parseFloat(v.fdb_qty_mts_m12),
				fdb_qty_m12:parseFloat(v.fdb_qty_m12)
			}
			tbldetails.row.add(data);
		});
		tbldetails.draw(false);

	}


</script>
<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
