<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?=base_url()?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<style type="text/css">
	.border-0{
		border: 0px;
	}
	td{
		padding: 2px; !important 		
	}
    .nav-tabs-custom>.nav-tabs>li.active>a{
        font-weight:bold;
        border-left-color: #3c8dbc;
        border-right-color: #3c8dbc;
        border-style:fixed;
    }
    .nav-tabs-custom>.nav-tabs{
        border-bottom-color: #3c8dbc;        
        border-bottom-style:fixed;
    }
</style>

<section class="content-header">
	<h1><?=lang("Prefix Trans Kas/Bank")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Prefix Trans Kas/Bank") ?></a></li>
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
            <form id="frmKasbank" class="form-horizontal" action="<?=site_url()?>gl/config/prefix_cash_bank/add" method="POST" enctype="multipart/form-data">			
				<div class="box-body">
					<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">			
					<input type="hidden" id="frm-mode" value="<?=$mode?>">

					<div class="form-group">
                        <label for="fin_kasbank_id" class="col-md-2 control-label"><?=lang("Kas/Bank ID")?> #</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="fin_kasbank_id" placeholder="<?=lang("Kas/Bank ID")?>" name="fin_kasbank_id" value="<?=$fin_kasbank_id?>">
                                <div id="fin_kasbank_id_err" class="text-danger"></div>
                            </div>
					</div>

					<div class="form-group">
					<label for="fst_kasbank_name" class="col-md-2 control-label"><?=lang("Kas/Bank Name")?> :</label>
						<div class="col-md-10">
							<input type="text" class="form-control" id="fst_kasbank_name" placeholder="<?=lang("Kas/Bank Name")?>" name="fst_kasbank_name">
                            <div id="fst_kasbank_name_err" class="text-danger"></div>
						</div>
					</div>

                    <div class="form-group">
					<label for="fst_prefix_pengeluaran" class="col-md-2 control-label"><?=lang("Prefix Pengeluaran")?> :</label>
                        <div class="col-md-4">
							<input type="text" class="form-control" id="fst_prefix_pengeluaran" placeholder="<?=lang("Prefix Pengeluaran")?>" name="fst_prefix_pengeluaran">
							<div id="fst_prefix_pengeluaran_err" class="text-danger"></div>
						</div>

					<label for="fst_prefix_pemasukan" class="col-md-2 control-label"><?=lang("Prefix Pemasukan")?> :</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_prefix_pemasukan" placeholder="<?=lang("Prefix Pemasukan")?>" name="fst_prefix_pemasukan">
							<div id="fst_prefix_pemasukan_err" class="text-danger"></div>
						</div>
					</div>

                    <div class="form-group">
                    <label for="fst_type" class="col-md-2 control-label personal-info"><?=lang("Type")?> :</label>
						<div class="col-md-4 personal-info">
							<select class="form-control" id="fst_type" name="fst_type">
								<option value="0">-- <?=lang("select")?> --</option>
								<option value="C"><?=lang("Cash")?></option>
								<option value="B"><?=lang("Bank")?></option>
							</select>
						</div>
                    
                    <label for="select-glaccount_code" class="col-md-2 control-label"><?=lang("Rekening GL Account KasBank")?> :</label>
						<div class="col-md-4">
							<select id="select-glaccount_code" class="form-control" name="fst_gl_account_code">
								<option value="0">-- <?=lang("select")?> --</option>
							</select>
							<div id="fst_gl_account_code_err" class="text-danger"></div>
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

<script type="text/javascript">
	$(function(){
		<?php if($mode == "EDIT"){?>
			init_form($("#fin_kasbank_id").val());
		<?php } ?>

		$("#btnSubmitAjax").click(function(event){
			event.preventDefault();
			data = new FormData($("#frmKasbank")[0]);

			mode = $("#frm-mode").val();
			if (mode == "ADD"){
				url =  "<?= site_url() ?>gl/config/prefix_cash_bank/ajx_add_save";
			}else{
				url =  "<?= site_url() ?>gl/config/prefix_cash_bank/ajx_edit_save";
			}

			//var formData = new FormData($('form')[0])
			$.ajax({
				type: "POST",
                enctype: 'multipart/form-data',
                url: url,
                data: data,
                processData: false,
				contentType: false,
				cache: false,
				timeout: 600000,
				success: function (resp) {	
					if (resp.message != "")	{
						$.alert({
							title: 'Message',
							content: resp.message,
							buttons : {
								OK : function(){
									if(resp.status == "SUCCESS"){
                                        //location.reload();
										window.location.href = "<?= site_url() ?>gl/config/prefix_cash_bank";
										return;
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
						$("#fin_kasbank_id").val(data.insert_id);

						//Clear all previous error
						$(".text-danger").html("");

						// Change to Edit mode
						$("#frm-mode").val("EDIT");  //ADD|EDIT

						$('#fst_kasbank_name').prop('readonly', true);
					}
				},
				error: function (e) {
					$("#result").text(e.responseText);
					console.log("ERROR : ", e);
					$("#btnSubmit").prop("disabled", false);
				}
			});
		});
		
		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>gl/config/prefix_cash_bank/add")
		});

		$("#btnDelete").confirmation({
			title:"<?=lang("Hapus data ini ?")?>",
			rootSelector: '#btnDelete',
			placement: 'left',
		});
		$("#btnDelete").click(function(e){
			e.preventDefault();
			blockUIOnAjaxRequest("<h5>Deleting ....</h5>");
			$.ajax({
				url:"<?= site_url() ?>gl/config/prefix_cash_bank/delete/" + $("#fin_kasbank_id").val(),
			}).done(function(resp){
				//consoleLog(resp);
				$.unblockUI();
				if (resp.message != "")	{
					$.alert({
						title: 'Message',
						content: resp.message,
						buttons : {
							OK : function() {
								if (resp.status == "SUCCESS") {
									window.location.href = "<?= site_url() ?>gl/config/prefix_cash_bank/lizt";
									return;
								}
							},
						}
					});
				}

				if(resp.status == "SUCCESS") {
					data = resp.data;
					$("#fin_kasbank_id").val(data.insert_id);

					//Clear all previous error
					$(".text-danger").html("");
					// Change to Edit mode
					$("#frm-mode").val("EDIT");  //ADD|EDIT
					$('#fst_pcc_name').prop('readonly', true);
				}
			});
		});

		$("#btnList").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>gl/config/prefix_cash_bank/lizt");
		});
	});

	function init_form(fin_kasbank_id){
		//alert("Init Form");
		var url = "<?=site_url()?>gl/config/prefix_cash_bank/fetch_data/" + fin_kasbank_id;
		$.ajax({
			type: "GET",
			url: url,
			success: function (resp) {	
				console.log(resp.ms_Kasbank);

				$.each(resp.ms_Kasbank, function(name, val){
					var $el = $('[name="'+name+'"]'),
					    type = $el.attr('type');
					switch(type){
						case 'checkbox':
							$el.attr('checked', 'checked');
							break;
						case 'radio':
							$el.filter('[value="'+val+'"]').attr('checked', 'checked');
							break;
						default:
							$el.val(val);
					}
				});
			},

			error: function (e) {
				$("#result").text(e.responseText);
				console.log("ERROR : ", e);
			}
		});
	}
</script>

<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>