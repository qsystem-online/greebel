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
	.select2-results > ul > li > span{
		display:inline-block;
		margin-right:20px;
	}
</style>

<section class="content-header">
	<h1><?=lang("Invoice")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Invoice") ?></a></li>
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
					<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
					<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
				</div>				
			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmH" class="form-horizontal" action="<?=site_url()?>tr/delivery_order/add" method="POST" enctype="multipart/form-data">			
				<div class="box-body">
					<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">								
					
					<div class="form-group">
						<label class="col-md-2 control-label"><?=lang("Promo Name")?> :</label>
						<div class="col-md-10">
							<label class="control-label" id="fst_promo_name"></label>
						</div>						
                    </div>



					<div class="form-group">
						<label for="" class="col-md-2 control-label"><?=lang("Periode Promotion")?> :</label>
						<div class="col-md-10">
							<label class="control-label" id="fdt_start"></label>
							<label class="control-label"> - </label>
							<label class="control-label" id="fdt_end"></label>
						</div>						
                    </div>

					<table id="tblParticipant" class="table table-bordered table-hover table-striped" style="width:100%"></table>
                    <div id="detail_err" class="text-danger"></div>
                </div>
				<div class="box-footer text-right"></div>
                <!-- end box-footer -->
            </form>
        </div>
    </div>
</section>



<?php
	echo $mdlEditForm;
	echo $mdlPrint;
?>

<script type="text/javascript" info="bind">
	$(function(){
		$(document).bind('keydown', 'alt+d', function(){
			$("#btn-add-detail").trigger("click");
		});
		$(document).bind('keydown', 'alt+j', function(){
			$("#btnJurnal").trigger("click");
		});
	});
</script>

<script type="text/javascript" info="define">
	
</script>

<script type="text/javascript" info="event">
	$(function(){
		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>tr/sales/invoice/add");
		});

		$("#btnSubmitAjax").click(function(e){
            e.preventDefault();
            submitAjax(0);
		});
		
		$("#btnPrint").click(function(e){
			e.preventDefault();
			frameVoucher.print("<?=site_url()?>tr/sales/invoice/print_voucher/" + $("#fin_inv_id").val());
		});

		$("#btnJurnal").click(function(e){
			e.preventDefault();
			MdlJurnal.showJurnalByRef("SIV",$("#fin_inv_id").val());
		});

		$("#btnDelete").confirmation({
			title:"<?= lang("Hapus data ini ?") ?>",
			rootSelector: '#btnDelete',
			placement:'left',
		});

		$("#btnDelete").click(function(e){
			e.preventDefault();
			deleteAjax(0);
		});
		$("#btnList").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>tr/sales/invoice");
		});

	});
</script>

<script type="text/javascript" info="init">
	$(function(){	
		
		$('#tblInvItems').on('preXhr.dt', function ( e, settings, data ) {
		 	//add aditional data post on ajax call
		 	data.sessionId = "TEST SESSION ID";
		}).on('init.dt',function(){
			$(".dataTables_scrollHeadInner").css("min-width","100%");
			$(".dataTables_scrollHeadInner > table").css("min-width","100%");
			$(".dataTables_scrollBody").css("position","static");
		}).DataTable({
			scrollX: true,
            ordering: true,
			columns:[
                {"title" : "id","width": "5%",data:"fin_rec_id",visible:true},
                {"title" : "Items","width": "20%",orderable:false,data:"fin_item_id",
					render: function(data,type,row){
						return row.fst_custom_item_name;
					}
                },
				{"title" : "Qty SO","width": "5%",data:"fdb_qty_so",className:'text-right',orderable:false,},
				{"title" : "Qty SJ","width": "5%",data:"fdb_qty_sj",className:'text-right',orderable:false,},
				{"title" : "Unit","width": "10%",data:"fst_unit",orderable:false},
                {"title" : "Price","width": "10%",data:"fdc_price",orderable:false,className:'text-right'},
                {"title" : "Disc %","width": "10%",data:"fst_disc_item",orderable:false,className:'text-right'},
                {"title" : "Disc Amount","width": "10%",orderable:false,className:'text-right',
                    render:function(data,type,row){
						disc = row.fdc_disc_amount_per_item * row.fdb_qty_sj;
						return App.money_format(disc);
                    }
                },
                {"title" : "Sub Total","width": "10%",orderable:false,className:'text-right',
                    render:function(data,type,row){
						disc = row.fdc_disc_amount_per_item * row.fdb_qty_sj;
						total = row.fdb_qty_sj * row.fdc_price;
						//disc = calculateDisc(row.fdb_qty * money_parse(row.fdc_price),row.fst_disc_item);
                        return App.money_format(total - disc);;
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

		App.fixedSelect2();
		initForm();
	});
</script>

<script type="text/javascript" info="function">
	
	function submitAjax(confirmEdit){
        
        data = $("#frmInvoice").serializeArray();
		detail = new Array();		

		/*
		t = $('#tblInvDetails').DataTable();
		datas = t.data();
		$.each(datas,function(i,v){
			detail.push(v);
		});
		*/

		
		mode = $("#fin_inv_id").val() != 0 ? "EDIT" : "ADD";

		
		if (mode == "EDIT"){
			url = "<?=site_url()?>tr/sales/invoice/ajx_edit_save";
			if (confirmEdit == 0 && mode != "ADD"){
				MdlEditForm.saveCallBack = function(){
					submitAjax(1);
				};		
				MdlEditForm.show();
				return;
			}

			data.push({
				name : "fin_user_id_request_by",
				value: MdlEditForm.user
			});
			data.push({
				name : "fst_edit_notes",
				value: MdlEditForm.notes
			});									
		}else{
			url = "<?=site_url()?>tr/sales/invoice/ajx_add_save";
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
			}

        }).always(function(resp){
		});

	}


	function initForm(){
		
		
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

		blockUIOnAjaxRequest("<h5>Deleting ....</h5>");
		$.ajax({
			url:"<?= site_url() ?>tr/sales/invoice/delete/" + $("#fin_inv_id").val(),
			method:"POST",
			data:dataSubmit,

		}).done(function(resp){
			consoleLog(resp);
			$.unblockUI();
			if (resp.message != "")	{
				$.alert({
					title: 'Message',
					content: resp.message,
					buttons : {
						OK : function(){
							if(resp.status == "SUCCESS"){
								$("#btnList").trigger("click");
							}
						},
					}
				});
			}

			if(resp.status == "SUCCESS") {
				data = resp.data;
				$("#fin_inv_id").val(data.insert_id);

				//Clear all previous error
				$(".text-danger").html("");
				// Change to Edit mode
				$("#frm-mode").val("EDIT");  //ADD|EDIT
				$('#fst_inv_no').prop('readonly', true);				
			}
		});
	}

</script>

<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
