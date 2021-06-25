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
					<input type="hidden" id="fin_promo_id" value="<?=$fin_promo_id?>">
					
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

					<table id="tblTerm" class="table table-bordered table-hover table-striped" style="width:100%"></table>
                    <div id="detail_err" class="text-danger"></div>


					<div class="form-group" style="margin-top:10px;margin-bottom:10px">
						<div class="col-md-2 col-md-offset-10 text-right">
							<a id="btnProcessPromo" class="btn btn-primary">Process Promo</a>
						</div>						
                    </div>

					<table id="tblClient" class="table table-bordered table-hover table-striped" style="width:100%"></table>                    
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
	var finPromoId = "<?= $fin_promo_id?>";
	var tblTerm;
	var tblClient;
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

		$("#btnProcessPromo").click(function(e){
			e.preventDefault();
			App.blockUIOnAjaxRequest();
			$.ajax({
				url:"<?=site_url()?>tr/sales/promo_period/ajx_process_promo/" + $("#fin_promo_id").val(),
				method:"GET",				
			}).done(function(resp){
				if (resp.status == "SUCCESS"){
					getCustomerAchived();
				}
			})
		});

	});
</script>

<script type="text/javascript" info="init">
	$(function(){	
		
		tblTerm = $('#tblTerm').on('preXhr.dt', function ( e, settings, data ) {
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
				{"title": "<?= lang("Item ID ") ?>","width": "10%",data: "fin_item_id",visible: false},
				{"title": "<?= lang("Item terms ") ?>","width": "25%",data: "fst_item_name",visible: true},
				{"title": "<?= lang("Type ") ?>","width": "10%",data: "fst_item_type",visible: true},
				{"title": "<?= lang("Unit ") ?>","width": "5%",data: "fst_unit",visible: true},
				{"title": "<?= lang("Qty ") ?>","width": "5%",data: "fdb_qty",visible: true},              
			],
			processing: true,
			serverSide: false,
			searching: false,
			lengthChange: false,
			paging: false,
			info:false,
			fnRowCallback: function( nRow, aData, iDisplayIndex ) {},
		}).on('draw',function(){
		});

		tblClient = $('#tblClient').on('preXhr.dt', function ( e, settings, data ) {
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
				{"title": "<?= lang("id") ?>","width": "10%",data: "fin_rec_id",visible: false},
				{"title": "<?= lang("Customer Achived") ?>","width": "25%",data: "fin_customer_id",visible: true,
					render:function(data,type,row){
						return row.fst_customer_name;
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
		
		var url = "<?= site_url() ?>master/promotion/fetch_data/" + finPromoId;
		App.blockUIOnAjaxRequest();
        $.ajax({
            type: "GET",
            url: url,
            success: function(resp) {
                console.log(resp);    
				promo = resp.mspromo;
				
				$("#fst_promo_name").text(promo.fst_promo_name);
				$("#fdt_start").text( dateFormat(promo.fdt_start));
				$("#fdt_end").text(dateFormat(promo.fdt_end));

				$.each(resp.promoTerms, function(name, val) {
                    console.log(val);
                    //event.preventDefault();                    
                    tblTerm.row.add({
						fin_id: val.fin_id,
						fin_promo_id: val.fin_promo_id,
						fst_item_type: val.fst_item_type,
						fin_item_id: val.fin_item_id,
						fst_item_name: val.ItemTerms,
						fdb_qty: val.fdb_qty,
						fst_unit: val.fst_unit,
					}).draw(false);
                });

				getCustomerAchived();


            },
            error: function(e) {
                $("#result").text(e.responseText);
                console.log("ERROR : ", e);
            }
        });		
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

	function getCustomerAchived(){

		$.ajax({
			url:"<?=site_url()?>tr/sales/promo_period/ajx_cust_achived/" + $("#fin_promo_id").val(),
			method:"GET",		
		}).done(function(resp){

			if(resp.status == "SUCCESS"){
				data = resp.data;
				tblClient.clear();
				$.each(data,function(i,v){
					tblClient.row.add({
						fin_rec_id :v.fin_rec_id,
						fin_customer_id:v.fin_customer_id,
						fst_customer_name:v.fst_relation_name
					});

				})
				tblClient.draw(false);
			}
			
		});
	}

</script>

<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
