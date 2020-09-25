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
	<h1><?=lang("Master Target Sales")?><small><?=lang("form")?></small></h1>
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
					<a id="btnJurnal" class="btn btn-primary" href="#" title="Jurnal" style="display:<?= $mode == "ADD" ? "none" : "inline-block" ?>"><i class="fa fa-align-left" aria-hidden="true"></i></a>
					<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
					<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Group")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
				</div>
			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmHeader" class="form-horizontal" action="" method="POST" >
				<div class="box-body">
					<input type="hidden" id="fin_mts_id" name="fin_mts_id" value="<?=$fin_mts_id?>"/>

					<div class="form-group">
						<label for="fst_assembling_no" class="col-md-2 control-label"><?=lang("MTS")?> #</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_mts_no" placeholder="<?=lang("MTS No")?>" name="fst_mts_no" value="<?=$fst_mts_no?>"/>
							<div id="fst_mts_no_err" class="text-danger"></div>
						</div>								

						<label for="fdt_mts_datetime" class="col-md-2 control-label"><?=lang("Tanggal")?></label>
						<div class="col-md-4">
							<input type="text" class="form-control datetimepicker text-right" id="fdt_mts_datetime" name="fdt_mts_datetime" value=""/>
							<div id="fdt_mts_datetime_err" class="text-danger"></div>
						</div>								
                    </div>  

					<div class="form-group">
						<label for="fin_year" class="col-md-2 control-label"><?=lang("Tahun")?></label>
						<div class="col-md-4">
							<input type='TEXT' name='fin_year' class='form-control text-right' id='fin_year' value='2020'/>
							<div id="fst_type_err" class="text-danger"></div>
						</div>					
					</div>
					
					<div class="form-group">					
						<label for="fin_item_id" class="col-md-2 control-label"><?=lang("Group Item")?></label>
						<div class="col-md-10">
							<select  class="form-control hpp-header" id="fin_item_group_id" placeholder="<?=lang("Group Item")?>" name="fin_item_group_id" style="width:100%"></select>
							<div id="fin_item_group_id_err" class="text-danger"></div>
						</div>
                    </div>  					

					<div class="form-group">					
						<label for="fst_history_type" class="col-md-2 control-label"><?=lang("History Period")?></label>
						<div class="col-md-10">
							<select  class="form-control hpp-header" id="fst_history_type"  name="fst_history_type" style="width:100%">
								<option value='Last Year'>Last Year</option>
								<option value='Average Last 3 Year'>Average Last 3 Year</option>
							</select>
							<div id="fst_history_type_err" class="text-danger"></div>
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
							<label class="control-label text-left">*HM1 : Qty, History Month 1, *M1  : Qty, Target Month 1</label>
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
						<input type="hidden" id="fin_item_id"/>					
						<label class="col-md-10 control-label" id='dItem'></label>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label"><?=lang("Unit")?>:</label>						
						<div class="col-md-10">
							<select  id="dUnit" class="form-control" style="width:100%"></select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-1 control-label "><?=lang("HM1")?></label>
						<label class="col-md-2 control-label hm" id="hm1">0</label>				
						<label class="col-md-1 control-label "><?=lang("M1")?></label>		
						<div class="col-md-2">
							<input type='TEXT' id="m1" class="form-control " value="600"/>
						</div>

						<label class="col-md-1 control-label "><?=lang("HM2")?></label>
						<label class="col-md-2 control-label hm" id="hm2">0</label>				
						<label class="col-md-1 control-label "><?=lang("M2")?></label>		
						<div class="col-md-2">
							<input type='TEXT' id="m2" class="form-control " value="600"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label "><?=lang("HM3")?></label>
						<label class="col-md-2 control-label hm" id="hm3">0</label>				
						<label class="col-md-1 control-label "><?=lang("M3")?></label>		
						<div class="col-md-2">
							<input type='TEXT' id="m3" class="form-control " value="600"/>
						</div>

						<label class="col-md-1 control-label "><?=lang("HM4")?></label>
						<label class="col-md-2 control-label hm" id="hm4">0</label>				
						<label class="col-md-1 control-label "><?=lang("M4")?></label>		
						<div class="col-md-2">
							<input type='TEXT' id="m4" class="form-control " value="600"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label "><?=lang("HM5")?></label>
						<label class="col-md-2 control-label hm" id="hm5">0</label>				
						<label class="col-md-1 control-label "><?=lang("M5")?></label>		
						<div class="col-md-2">
							<input type='TEXT' id="m5" class="form-control " value="600"/>
						</div>

						<label class="col-md-1 control-label "><?=lang("HM6")?></label>
						<label class="col-md-2 control-label hm" id="hm6">0</label>				
						<label class="col-md-1 control-label "><?=lang("M6")?></label>		
						<div class="col-md-2">
							<input type='TEXT' id="m6" class="form-control " value="600"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label "><?=lang("HM7")?></label>
						<label class="col-md-2 control-label hm" id="hm7">0</label>				
						<label class="col-md-1 control-label "><?=lang("M7")?></label>		
						<div class="col-md-2">
							<input type='TEXT' id="m7" class="form-control " value="600"/>
						</div>

						<label class="col-md-1 control-label "><?=lang("HM8")?></label>
						<label class="col-md-2 control-label hm" id="hm8">0</label>				
						<label class="col-md-1 control-label "><?=lang("M8")?></label>		
						<div class="col-md-2">
							<input type='TEXT' id="m8" class="form-control " value="600"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label "><?=lang("HM9")?></label>
						<label class="col-md-2 control-label hm" id="hm9">0</label>				
						<label class="col-md-1 control-label "><?=lang("M9")?></label>		
						<div class="col-md-2">
							<input type='TEXT' id="m9" class="form-control " value="600"/>
						</div>

						<label class="col-md-1 control-label "><?=lang("HM10")?></label>
						<label class="col-md-2 control-label hm" id="hm10">0</label>				
						<label class="col-md-1 control-label "><?=lang("M10")?></label>		
						<div class="col-md-2">
							<input type='TEXT' id="m10" class="form-control " value="600"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label "><?=lang("HM11")?></label>
						<label class="col-md-2 control-label hm" id="hm11">0</label>				
						<label class="col-md-1 control-label "><?=lang("M11")?></label>		
						<div class="col-md-2">
							<input type='TEXT' id="m11" class="form-control " value="600"/>
						</div>

						<label class="col-md-1 control-label "><?=lang("HM12")?></label>
						<label class="col-md-2 control-label hm" id="hm12">0</label>				
						<label class="col-md-1 control-label "><?=lang("M12")?></label>		
						<div class="col-md-2">
							<input type='TEXT' id="m12" class="form-control " value="600"/>
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
					console.log(data);
					$("#dItem").text(data.fst_item_code + " - " + data.fst_item_name);
					$("#fin_item_id").val(data.fin_item_id);
					App.addOptionIfNotExist("<option value='"+data.fst_unit+"'>"+data.fst_unit+"</option>","dUnit")
					$("#hm1").text(data.fdb_qty_hm01);
					$("#hm2").text(data.fdb_qty_hm02);
					$("#hm3").text(data.fdb_qty_hm03);
					$("#hm4").text(data.fdb_qty_hm04);
					$("#hm5").text(data.fdb_qty_hm05);
					$("#hm6").text(data.fdb_qty_hm06);
					$("#hm7").text(data.fdb_qty_hm07);
					$("#hm8").text(data.fdb_qty_hm08);
					$("#hm9").text(data.fdb_qty_hm09);
					$("#hm10").text(data.fdb_qty_hm10);
					$("#hm11").text(data.fdb_qty_hm11);
					$("#hm12").text(data.fdb_qty_hm12);
					$("#m1").val(data.fdb_qty_m01);
					$("#m2").val(data.fdb_qty_m02);
					$("#m3").val(data.fdb_qty_m03);
					$("#m4").val(data.fdb_qty_m04);
					$("#m5").val(data.fdb_qty_m05);
					$("#m6").val(data.fdb_qty_m06);
					$("#m7").val(data.fdb_qty_m07);
					$("#m8").val(data.fdb_qty_m08);
					$("#m9").val(data.fdb_qty_m09);
					$("#m10").val(data.fdb_qty_m10);
					$("#m11").val(data.fdb_qty_m11);
					$("#m12").val(data.fdb_qty_m12);
				}				
				$("#mdlDetail").modal("show");
			},
			hide:function(){
				$("#mdlDetail").modal("hide");
			},
			clear:function(){
				$("#fin_item_id").val(null);
				$("#unitD").val(null).trigger("change.select2");
				$("#m1").val(0);
				$("#m2").val(0);
				$("#m3").val(0);
				$("#m4").val(0);
				$("#m5").val(0);
				$("#m6").val(0);
				$("#m7").val(0);
				$("#m8").val(0);
				$("#m9").val(0);
				$("#m10").val(0);
				$("#m11").val(0);
				$("#m12").val(0);		
				selectedDetail = null;
			},			
		};

	</script>

	<script type="text/javascript" info="event">		
		$(function(){

			$("#btn-save-detail").click(function(e){

				//tbldetails 
				var data = selectedDetail.data();
				data.fdb_qty_m01  = $("#m1").val();
				data.fdb_qty_m02  = $("#m2").val();
				data.fdb_qty_m03  = $("#m3").val();
				data.fdb_qty_m04  = $("#m4").val();
				data.fdb_qty_m05  = $("#m5").val();
				data.fdb_qty_m06  = $("#m6").val();
				data.fdb_qty_m07  = $("#m7").val();
				data.fdb_qty_m08  = $("#m8").val();
				data.fdb_qty_m09  = $("#m9").val();
				data.fdb_qty_m10  = $("#m10").val();
				data.fdb_qty_m11  = $("#m11").val();
				data.fdb_qty_m12  = $("#m12").val();

				console.log(data);
				tbldetails.row(selectedDetail).data(data).draw(false);

				mdlDetail.clear();	
				mdlDetail.hide();		
			});

		});
	</script>
	<script type="text/javascript" info="init">
		$(function(){			
			$("#dUnit").select2({
				minimumInputLength: 0,
				minimumResultsForSearch: -1,
				ajax:{
					delay:250,
					url:function(params){
						return "<?=site_url()?>tr/production/mts/ajxGetUnits/" + $("#fin_item_id").val();
					},
					processResults: function (resp) {
						if (resp.status == "SUCCESS"){
							data = resp.data;
							var list  = $.map(data,function(v,i){
								v.id = v.fst_unit;
								v.text = v.fst_unit;
								return v;
							});
							return {
								results:list
							}							
						}
					}
				}
			}).on("select2:select",function(e){	
				
				getSalesHistory($("#fin_item_id").val(),$("#dUnit").val(),$("#fst_history_type").val(),$("#fin_year").val(),function(hist){
					var data = selectedDetail.data();
					data.fst_unit = $("#dUnit").val();
					data.fdb_qty_hm01 = hist.fdb_hist_m1_qty;
					data.fdb_qty_hm02 = hist.fdb_hist_m2_qty;
					data.fdb_qty_hm03 = hist.fdb_hist_m3_qty;
					data.fdb_qty_hm04 = hist.fdb_hist_m4_qty;
					data.fdb_qty_hm05 = hist.fdb_hist_m5_qty;
					data.fdb_qty_hm06 = hist.fdb_hist_m6_qty;
					data.fdb_qty_hm07 = hist.fdb_hist_m7_qty;
					data.fdb_qty_hm08 = hist.fdb_hist_m8_qty;
					data.fdb_qty_hm09 = hist.fdb_hist_m9_qty;
					data.fdb_qty_hm10 = hist.fdb_hist_m10_qty;
					data.fdb_qty_hm11 = hist.fdb_hist_m11_qty;
					data.fdb_qty_hm12 = hist.fdb_hist_m12_qty;

					$("#hm1").text(data.fdb_qty_hm01);
					$("#hm2").text(data.fdb_qty_hm02);
					$("#hm3").text(data.fdb_qty_hm03);
					$("#hm4").text(data.fdb_qty_hm04);
					$("#hm5").text(data.fdb_qty_hm05);
					$("#hm6").text(data.fdb_qty_hm06);
					$("#hm7").text(data.fdb_qty_hm07);
					$("#hm8").text(data.fdb_qty_hm08);
					$("#hm9").text(data.fdb_qty_hm09);
					$("#hm10").text(data.fdb_qty_hm10);
					$("#hm11").text(data.fdb_qty_hm11);
					$("#hm12").text(data.fdb_qty_hm12);

					tbldetails.row(selectedDetail).data(data);
				});
			});
			
		});

	</script>

</div>

<?php echo $mdlEditForm ?>
<?php echo $mdlPrint ?>

<script type="text/javascript" info="define">
	var selectedDetail;	
	var tbldetails;
</script>

<script type="text/javascript" info="bind">
	$(document).bind('keydown', 'alt+d', function(){
		$("#btn-add-detail").trigger("click");
	});
</script>


<script type="text/javascript" info="event">
	$(function(){
		$("#btnNew").click(function(e){
			//e.preventDefault();
			window.location.replace("<?=site_url()?>tr/production/mts/add");
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
			window.location.replace("<?=site_url()?>tr/production/mts");
		});	

	
		$("#fin_year, #fst_history_type").change(function(e){
			e.preventDefault();
			//fillDetail();
			updateHistoryDetail();
		})		
	});
</script>

<script type="text/javascript" info="init">
	$(function(){		
		$("#fdt_mts_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");

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
                    console.log(data2);
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
				fillDetail();
            });
		});
		
		tbldetails = $('#tbldetails').on('preXhr.dt', function ( e, settings, data ) {
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
				{"title" : "HM1","width": "10px",sortable:false,data:"fdb_qty_hm01",className:'text-right'},
				{"title" : "M1","width": "10px",sortable:false,data:"fdb_qty_m01",className:'text-right'},
				{"title" : "HM2","width": "10px",sortable:false,data:"fdb_qty_hm02",className:'text-right'},
				{"title" : "M2","width": "10px",sortable:false,data:"fdb_qty_m02",className:'text-right'},
				{"title" : "HM3","width": "10px",sortable:false,data:"fdb_qty_hm03",className:'text-right'},
				{"title" : "M3","width": "10px",sortable:false,data:"fdb_qty_m03",className:'text-right'},
				{"title" : "HM4","width": "10px",sortable:false,data:"fdb_qty_hm04",className:'text-right'},
				{"title" : "M4","width": "10px",sortable:false,data:"fdb_qty_m04",className:'text-right'},
				{"title" : "HM5","width": "10px",sortable:false,data:"fdb_qty_hm05",className:'text-right'},
				{"title" : "M5","width": "10px",sortable:false,data:"fdb_qty_m05",className:'text-right'},
				{"title" : "HM6","width": "10px",sortable:false,data:"fdb_qty_hm06",className:'text-right'},
				{"title" : "M6","width": "10px",sortable:false,data:"fdb_qty_m06",className:'text-right'},
				{"title" : "HM7","width": "10px",sortable:false,data:"fdb_qty_hm07",className:'text-right'},
				{"title" : "M7","width": "10px",sortable:false,data:"fdb_qty_m07",className:'text-right'},
				{"title" : "HM8","width": "10px",sortable:false,data:"fdb_qty_hm08",className:'text-right'},
				{"title" : "M8","width": "10px",sortable:false,data:"fdb_qty_m08",className:'text-right'},
				{"title" : "HM9","width": "10px",sortable:false,data:"fdb_qty_hm09",className:'text-right'},
				{"title" : "M9","width": "10px",sortable:false,data:"fdb_qty_m09",className:'text-right'},
				{"title" : "HM10","width": "10px",sortable:false,data:"fdb_qty_hm10",className:'text-right'},
				{"title" : "M10","width": "10px",sortable:false,data:"fdb_qty_m10",className:'text-right'},
				{"title" : "HM11","width": "10px",sortable:false,data:"fdb_qty_hm11",className:'text-right'},
				{"title" : "M11","width": "10px",sortable:false,data:"fdb_qty_m11",className:'text-right'},
				{"title" : "HM12","width": "10px",sortable:false,data:"fdb_qty_hm12",className:'text-right'},
				{"title" : "M12","width": "10px",sortable:false,data:"fdb_qty_m12",className:'text-right'},
				{"title" : "H.ttl","width": "10px",sortable:false,className:'text-right',
					render:function(data,type,row){
						return parseFloat(row.fdb_qty_hm01) + parseFloat(row.fdb_qty_hm02) + parseFloat(row.fdb_qty_hm03) + parseFloat(row.fdb_qty_hm04) + parseFloat(row.fdb_qty_hm05) + parseFloat(row.fdb_qty_hm06) + parseFloat(row.fdb_qty_hm07) + parseFloat(row.fdb_qty_hm08) + parseFloat(row.fdb_qty_hm09) + parseFloat(row.fdb_qty_hm10) + parseFloat(row.fdb_qty_hm11) + parseFloat(row.fdb_qty_hm12);						
					}
				},
				{"title" : "M.ttl","width": "10px",sortable:false,className:'text-right',
					render:function(data,type,row){
						return parseFloat(row.fdb_qty_m01) + parseFloat(row.fdb_qty_m02) + parseFloat(row.fdb_qty_m03) + parseFloat(row.fdb_qty_m04) + parseFloat(row.fdb_qty_m05) + parseFloat(row.fdb_qty_m06) + parseFloat(row.fdb_qty_m07) + parseFloat(row.fdb_qty_m08) + parseFloat(row.fdb_qty_m09) + parseFloat(row.fdb_qty_m10) + parseFloat(row.fdb_qty_m11) + parseFloat(row.fdb_qty_m12);				
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
			var trRow = $(this).parents('tr');
			selectedDetail = tbldetails.row(trRow);
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
		
		var datas = tbldetails.data();
		$.each(datas,function(i,v){
			dataDetails.push(v);
		});

		data.push({
			name:"details",
			value: JSON.stringify(dataDetails)
		});

		if (mode == "ADD"){
			url = "<?=site_url()?>tr/production/mts/ajx_add_save";
		}else{			
			url = "<?=site_url()?>tr/production/mts/ajx_edit_save";
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
				url:"<?= site_url() ?>tr/production/mts/fetch_data/<?=$fin_mts_id?>",
			}).done(function(resp){							
				dataH =  resp.data.header;
				if (dataH == null){
					alert("<?=lang("ID transaksi tidak dikenal")?>");
					$("#btnNew").trigger("click");
				}
				
				App.autoFillForm(dataH);
				
				App.addOptionIfNotExist("<option value='"+dataH.fin_item_group_id+"'>"+dataH.fst_item_group_name +"</option>","fin_item_group_id");
				$("fin_item_group_id").val(dataH.fin_item_group_id).trigger("change");

				//$("fst_history_type").val(dataH.fin_item_group_id).trigger("change");

								
				
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

					tbldetails.row.add({
						fin_rec_id:v.fin_rec_id,
						fin_item_id:v.fin_item_id,
						fst_item_code:v.fst_item_code,
						fst_item_name:v.fst_item_name,
						fst_unit:v.fst_unit,
						fdb_qty_hm01:0,
						fdb_qty_m01:v.fdb_qty_m01,
						fdb_qty_hm02:0,
						fdb_qty_m02:v.fdb_qty_m02,
						fdb_qty_hm03:0,
						fdb_qty_m03:v.fdb_qty_m03,
						fdb_qty_hm04:0,
						fdb_qty_m04:v.fdb_qty_m04,
						fdb_qty_hm05:0,
						fdb_qty_m05:v.fdb_qty_m05,
						fdb_qty_hm06:0,
						fdb_qty_m06:v.fdb_qty_m06,
						fdb_qty_hm07:0,
						fdb_qty_m07:v.fdb_qty_m07,
						fdb_qty_hm08:0,
						fdb_qty_m08:v.fdb_qty_m08,
						fdb_qty_hm09:0,
						fdb_qty_m09:v.fdb_qty_m09,
						fdb_qty_hm10:0,
						fdb_qty_m10:v.fdb_qty_m10,
						fdb_qty_hm11:0,
						fdb_qty_m11:v.fdb_qty_m11,
						fdb_qty_hm12:0,
						fdb_qty_m12:v.fdb_qty_m12,
					});					
				});
				tbldetails.draw(false);
				updateHistoryDetail();
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

		var url =  "<?= site_url() ?>tr/production/mts/delete/" + $("#fin_mts_id").val();
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

	function fillDetail(){

		if ($("#fin_item_group_id").val() == null  ){
			return;
		}

		$.ajax({
			url:"<?=site_url()?>/tr/production/mts/ajxGetDetailItems/" + $("#fin_item_group_id").val(),
			data:{
				fst_history_type:$("#fst_history_type").val(),
				fin_year:$("#fin_year").val()
			},
			method:"GET",
		}).done(function(resp){			
			if (resp.status =="SUCCESS"){
				datas = resp.data;
				tbldetails.clear();
				$.each(datas,function(i,v){
					tbldetails.row.add({
						fin_rec_id:0,
						fin_item_id:v.fin_item_id,
						fst_item_code:v.fst_item_code,
						fst_item_name:v.fst_item_name,
						fst_unit:v.fst_unit,
						fdb_qty_hm01:v.fdb_hist_m1_qty,
						fdb_qty_m01:0,
						fdb_qty_hm02:v.fdb_hist_m2_qty,
						fdb_qty_m02:0,
						fdb_qty_hm03:v.fdb_hist_m3_qty,
						fdb_qty_m03:0,
						fdb_qty_hm04:v.fdb_hist_m4_qty,
						fdb_qty_m04:0,
						fdb_qty_hm05:v.fdb_hist_m5_qty,
						fdb_qty_m05:0,
						fdb_qty_hm06:v.fdb_hist_m6_qty,
						fdb_qty_m06:0,
						fdb_qty_hm07:v.fdb_hist_m7_qty,
						fdb_qty_m07:0,
						fdb_qty_hm08:v.fdb_hist_m8_qty,
						fdb_qty_m08:0,
						fdb_qty_hm09:v.fdb_hist_m9_qty,
						fdb_qty_m09:0,
						fdb_qty_hm10:v.fdb_hist_m10_qty,
						fdb_qty_m10:0,
						fdb_qty_hm11:v.fdb_hist_m11_qty,
						fdb_qty_m11:0,
						fdb_qty_hm12:v.fdb_hist_m12_qty,
						fdb_qty_m12:0,							
					});
				});
				tbldetails.draw(false);

			}else{
				alert("Request Error !");
			}

		})
	}

	function updateHistoryDetail(){
		var datas = tbldetails.data();	

		$.each(datas,function(i,v){
			data = tbldetails.row(i).data();
			getSalesHistory(data.fin_item_id,data.fst_unit,$("#fst_history_type").val(),$("#fin_year").val(),function(hist){
				data.fdb_qty_hm01 = hist.fdb_hist_m1_qty;
				data.fdb_qty_hm02 = hist.fdb_hist_m2_qty;
				data.fdb_qty_hm03 = hist.fdb_hist_m3_qty;
				data.fdb_qty_hm04 = hist.fdb_hist_m4_qty;
				data.fdb_qty_hm05 = hist.fdb_hist_m5_qty;
				data.fdb_qty_hm06 = hist.fdb_hist_m6_qty;
				data.fdb_qty_hm07 = hist.fdb_hist_m7_qty;
				data.fdb_qty_hm08 = hist.fdb_hist_m8_qty;
				data.fdb_qty_hm09 = hist.fdb_hist_m9_qty;
				data.fdb_qty_hm10 = hist.fdb_hist_m10_qty;
				data.fdb_qty_hm11 = hist.fdb_hist_m11_qty;
				data.fdb_qty_hm12 = hist.fdb_hist_m12_qty;

				tbldetails.row(i).data(data).draw(false);

			});
			
		});

	}

	function getSalesHistory(itemId,unit,historyType,currentYear,callback){

		$.ajax({
			//$finItemId,$fstUnit,$histType,$currYear
			url:"<?=site_url()?>tr/production/mts/ajxGetHistMTS",
			method:"GET",		
			data:{
				fin_item_id:itemId,
				fst_unit:unit,
				fst_hist_type:historyType,
				fin_year:currentYear
			}			
		}).done(function(resp){
			if (resp.status == "SUCCESS"){				
				var hist = resp.data;
				callback(hist);								
			}
		})
	}	

</script>
<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
