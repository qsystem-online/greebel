
<?php
	defined('BASEPATH') OR exit('No direct script access allowed');	
?>

<link rel="stylesheet" href="<?=base_url()?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<style>
	.form-group{
		margin-bottom:10px;
	}
</style>

<section class="content-header">
	<h1><?=lang("Kas & Bank Penerimaan Lain-lain")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Kas & Bank") ?></a></li>
		<li class="active title"><?=$title?></li>
	</ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title title pull-left"><?=$title?></h3>
					<div class="btn-group btn-group-sm  pull-right">					
						<a id="btnNew" class="btn btn-primary" href="#" title="<?=lang("Tambah Baru")?>"><i class="fa fa-plus" aria-hidden="true"></i></a>
						<a id="btnSubmitAjax" class="btn btn-primary" href="#" title="<?=lang("Simpan")?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
						<a id="btnPrint" class="btn btn-primary" href="#" title="<?=lang("Cetak")?>" style="display:<?= $mode == "ADD" ? "none" : "inline-block" ?>"><i class="fa fa-print" aria-hidden="true"></i></a>
						<a id="btnJurnal" class="btn btn-primary" href="#" title="<?=lang("Jurnal")?>" style="display:<?= $mode == "ADD" ? "none" : "inline-block" ?>"><i class="fa fa-align-left" aria-hidden="true"></i></a>
						<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>" style="display:<?= $mode == "ADD" ? "none" : "inline-block" ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
						<a id="btnClose" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
					</div>
				</div>
				<!-- end box header -->

				<!-- form start -->
				<form id="frmHeader" class="form-horizontal"  method="POST">			
					<div class="box-body">
						<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">	
						<input type="hidden" class="form-control" id="fin_cbreceiveoth_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_cbreceiveoth_id" value="<?=$fin_cbreceiveoth_id?>" readonly>						
						
						<div class="form-group">
                            <div class="col-md-5 col-md-offset-2">
                                <div class="radio">
                                    <label><input type="radio" id="typeKas" class="type-penerimaan" name="fst_cbreceiveoth_type" value="Cash" checked><?= lang("Kas") ?></label>
                                    <label style="margin-left:20px"><input type="radio" id="typeBank" class="type-penerimaan" value="Bank" name="fst_cbreceiveoth_type"><?= lang("Bank") ?></label>
                                </div>
                            </div>                                
						</div>

						<div class="form-group">
                            <label for="fin_kasbank_id" class="col-md-2 control-label"><?=lang("Jenis Penerimaan")?> #</label>
                            <div class="col-md-5">
                                <select id="fin_kasbank_id" name="fin_kasbank_id" class="form-control"></select>                                    
                            </div>
							<div id="fin_kasbank_id_err" class="text-danger"></div>

							<label for="fdt_cbreceive_datetime" class="col-md-2 control-label text-right"><?=lang("Tanggal Penerimaan")?> *</label>
							<div class="col-md-3">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control text-right datetimepicker" id="fdt_cbreceiveoth_datetime" name="fdt_cbreceiveoth_datetime" value=""/>
								</div>
								<div id="fdt_cbreceive_datetime_err" class="text-danger"></div>
								<!-- /.input group -->
							</div>

						</div>


                        <div class="form-group">
							<label for="fst_cbreceiveoth_no" class="col-md-2 control-label"><?=lang("No. Penerimaan")?></label>	
							<div class="col-md-10">				
								<input type="TEXT" id="fst_cbreceiveoth_no" name="fst_cbreceiveoth_no" class="form-control"  value="" placeholder="PREFIX/BRANCH/YEAR/MONTH/99999" /> 
							</div>
						</div>

						<div class="form-group">
							<label for="fst_curr_code" class="col-md-2 control-label"><?=lang("Mata Uang")?> </label>
							<div class="col-md-2">
								<select id="fst_curr_code" class="form-control" name="fst_curr_code">
									<?php

										$currencies = $this->mscurrencies_model->getCurrencyList();
										foreach($currencies as $currency){
											$selected = $currency->fst_curr_code == "IDR" ? "SELECTED" : "";
											echo "<option value='$currency->fst_curr_code' data-rate='$currency->fdc_exchange_rate_to_idr' $selected>$currency->fst_curr_name</option>";
										}
									?>
								</select>
								<div id="fst_curr_code_err" class="text-danger"></div>
							</div>
						
							<label for="fdc_exchange_rate_idr" class="col-md-2 control-label"><?=lang("Nilai Tukar IDR")?> </label>
							<div class="col-md-2">
								<input type="text" class="money form-control text-right cls-nominal" id="fdc_exchange_rate_idr" name="fdc_exchange_rate_idr" style="width:100%" value="1" />
							</div>
							<label class="col-md-2 control-label" style="text-align:left;padding-left:0px"><?=lang("Rupiah")?> </label>
						</div>
						
						<div class="form-group">
							<label for="fst_receive_from" class="col-md-2 control-label"><?=lang("Diterima dari")?> </label>
							<div class="col-sm-10">
                                <input type="text" class="form-control" id="fst_receive_from"  name="fst_receive_from"/>
                                <div id="fst_receive_from_err" class="text-danger"></div>
                            </div> 
						</div>

						<div class="form-group">
							<label for="fdc_nominal" class="col-md-2 control-label"><?=lang("Nominal")?> </label>
							<div class="col-sm-4">
                                <input type="text" class="money form-control cls-nominal" id="fdc_nominal"  name="fdc_nominal" value="0"/>
                                <div id="fdc_nominal_err" class="text-danger"></div>
                            </div> 
							<label for="fdc_nominal_idr" class="col-md-2 control-label"><?=lang("Nominal IDR")?> </label>
							<div class="col-sm-4">
                                <input type="text" class="money form-control " id="fdc_nominal_idr" value="0" readonly/>
                            </div> 

						</div>


                        <div class="form-group">
                            <label for="fst_memo" class="col-md-2 control-label"><?=lang("Info")?> </label>							
                            <div class="col-sm-10">
                                <textarea class="form-control" id="fst_memo" placeholder="<?= lang("Memo") ?>" name="fst_memo" rows="3" style="resize:none"></textarea>
                                <div id="fst_memo_err" class="text-danger"></div>
                            </div>                        
                        </div>
						

						<div class="form-group" style="margin-bottom:5px">
							<div class="col-md-6" style="padding-top:10px"><label><?= lang("Rincian Penerimaan") ?> :</label></div>
							<div class="col-md-6" style='text-align:right'>
								<button id="btn-detail-penerimaan" class="btn btn-primary btn-sm">
									<i class="fa fa-cart-plus" aria-hidden="true"></i>
									<?=lang("Tambah Penerimaan")?>
								</button>
							</div>

							<div class="col-md-12">
								<!-- table table-bordered table-hover table-striped nowarp row-border -->
								<table id="tblcbreceiveotheritems" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
							</div>		
							<div id="detail_receive_err" class="text-danger"></div>					
						</div>

						<div class="form-group" style="margin-bottom:5px">
							<div class="col-md-7" style='text-align:left'>
								<div class="form-group" style="">									
									<label for="ttl-debit" class="col-md-3"><?=lang("Cash / Transfer")?></label>
									<div class="col-md-4">
										<input type="text" class="money form-control text-right" id="fdc_cash_transfer" name="fdc_cash_transfer" value="0">
									</div>
								</div>
								
								<hr>

								<div class="form-group" style="">									
									<label for="ttl-debit" class="col-md-3"><?=lang("Cheque / Giro")?></label>
									<div class="col-md-4">
										<input type="text" class="money form-control text-right" id="fdc_bilyet" name="fdc_bilyet" value="0">
									</div>	

									<label for="ttl-debit" class="col-md-2"><?=lang("Tgl Cair")?></label>
									<div class="col-md-3">
										<input type="text" class="datepicker form-control text-right" id="fdt_clear_date" name="fdt_clear_date">
									</div>	

								</div>
								<div class="form-group" style="">									
									<label for="ttl-debit" class="col-md-3"><?=lang("Nomor Cheque / Giro")?></label>
									<div class="col-md-4">
										<input type="text" class="form-control" id="fst_bilyet_no" name="fst_bilyet_no" value="">
									</div>									
								</div>
								
							</div>
							<div class="col-md-5" style='text-align:right'>
								<label for="ttl-debit" class="col-md-4 control-label"><?=lang("Debit / Credit")?></label>
								<div class="col-md-4" style='text-align:right;padding-right:3px'>
									<input type="text" class="form-control text-right" id="ttl-debet" value="0" readonly>
								</div>
								<div class="col-md-4" style='text-align:right;padding:0px'>
									<input type="text" class="form-control text-right" id="ttl-credit" value="0" readonly>
								</div>
							</div>


						</div>
						
						


						
					</div>
					<!-- end box body -->

					<div class="box-footer text-right"></div>
					<!-- end box-footer -->
				</form>
        	</div>
    	</div>
	</div>
</section>

<div id="mdlAddItem" class="modal fade" role="dialog" >
	<div class="modal-dialog" style="display:table;width:800px">
		<!-- modal content -->
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">			
			<div class="modal-body">
				<div class="row">
                    <div class="col-md-12" >
                        <div style="border:0 px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">				
								<form id="form-detail-trans" class="form-horizontal">									

									<div class="form-group">
										<label for="fst_glaccount_code" class="col-md-2 control-label"><?=lang("Rekening GL")?></label>
										<div class="col-md-10">
											<select id="fst_glaccount_code" class="form-control" style="width:100%">
                                            <?php
                                                $accountList = $this->glaccounts_model->getAccountList();
                                                foreach($accountList as $acc){
                                                    echo "<option value='$acc->fst_glaccount_code' data-pcc='$acc->fbl_pcc' data-pcdiv='$acc->fbl_pc_divisi' data-pccust='$acc->fbl_pc_customer' data-pcproject='$acc->fbl_pc_project' data-cardcontroll='$acc->fbl_controll_card_relation' >$acc->fst_glaccount_code - $acc->fst_glaccount_name</option>";
                                                }
                                            ?>
                                            </select>
										</div>
									</div>

									<div class="form-group">
										<label for="fin_pcc_id" class="col-md-2 control-label"><?=lang("Profit/Cost Center")?></label>
										<div class="col-md-4">
										<select id="fin_pcc_id" class="form-control" style="width:100%" disabled >
                                        <?php
                                            $pccList = $this->profitcostcenter_model->get_profitcostcenter();
                                            foreach($pccList as $pcc){
                                                
                                                echo "<option value='$pcc[fin_pcc_id]'> $pcc[fst_pcc_name] </option>";                                                
                                            }
                                        ?>
                                        </select>
										</div>
                                        <label for="fin_pc_divisi_id" class="col-md-2 control-label"><?=lang("Analisa Divisi")?></label>
										<div class="col-md-4">
										<select id="fin_pc_divisi_id" class="form-control" style="width:100%" disabled>
                                        <?php
                                            $deptList = $this->msdepartments_model->get_departments();
                                            foreach($deptList as $dept){
                                                echo "<option value='$dept[fin_department_id]'>$dept[fst_department_name]</option>";
                                            }
                                        ?>
                                        </select>
										</div>
									</div>

                                    <div class="form-group">
										<label for="fin_pc_customer_id" class="col-md-2 control-label"><?=lang("Analisa customer")?></label>
										<div class="col-md-4">
										<select id="fin_pc_customer_id" class="form-control" style="width:100%" disabled>
                                            <?php
                                                $customerList  = $this->msrelations_model->getCustomerListByBranch();
                                                foreach($customerList as $cust){
                                                    echo "<option value='$cust->fin_relation_id'>$cust->fst_relation_name</option>";
                                                }
                                            ?>
                                        </select>
										</div>
                                        <label for="fin_pc_project_id" class="col-md-2 control-label"><?=lang("Analisa Project")?></label>
										<div class="col-md-4">
										<select id="fin_pc_project_id" class="form-control" style="width:100%" disabled>
                                        <?php
                                            $projectList = $this->msprojects_model->getAllList();
                                            foreach($projectList as $project){
                                                echo "<option value='$project->fin_project_id' >$project->fst_project_name</option>";
                                            }

                                        ?>
                                        </select>
										</div>
									</div>

                                    <div class="form-group">
										<label for="fin_relation_id" class="col-md-2 control-label"><?=lang("Kontrol Kartu Per Relasi")?></label>
										<div class="col-md-10">
											<select class="form-control" id="fin_relation_id" disabled>
                                                <?php
                                                    $relationList = $this->msrelations_model->getRelationListByBranch();
                                                    foreach($relationList as $relation){
                                                        echo "<option value='$relation->fin_relation_id'>$relation->fst_relation_name</option>";
                                                    }
                                                ?>

                                            </select>
										</div>
									</div>

                                    <div class="form-group">
										<label for="fst_memo" class="col-md-2 control-label"><?=lang("Memo")?></label>
										<div class="col-md-10">
											<textarea class="form-control text-left" id="fst_notes" style="resize:none"></textarea>
										</div>
									</div>


									<div class="form-group">
										<label for="fdc_debit" class="col-md-2 control-label"><?=lang("Debit")?></label>
										<div class="col-md-4">
											<input type="TEXT" id="fdc_debit"  value= "0" class="money form-control text-right"/>
										</div>
										<label for="fdc_credit" class="col-md-2 control-label"><?=lang("Credit")?></label>
										<div class="col-md-4">
											<input type="TEXT" id="fdc_credit"  value= "0" class="money form-control text-right"/>
										</div>
									</div>
								</form>
								
								<div class="modal-footer">
									<button id="btn-add-rincian" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Add")?></button>
									<button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
        var mdlAddItems = {
            mode:"ADD",
            data:null,
            show:function(data){
                
                if(typeof data === "undefined"){
                    mdlAddItems.mode = "ADD";
                    mdlAddItems.clear();
                    mdlAddItems.data=null;
                }else{
                    mdlAddItems.mode = "EDIT";
                    mdlAddItems.data = data;
                    $("#fst_glaccount_code").val(data.fst_glaccount_code).trigger("change.select2").trigger("change");
                    $("#fst_notes").val(data.fst_notes);
                    $("#fdc_debit").val(data.fdc_debit);
                    $("#fdc_credit").val(data.fdc_credit);
                    $("#fin_pcc_id").val(data.fin_pcc_id);
                    $("#fin_pc_divisi_id").val(data.fin_pc_divisi_id);
                    $("#fin_pc_customer_id").val(data.fin_pc_customer_id).trigger("change.select2");
                    $("#fin_pc_project_id").val(data.fin_pc_project_id);
                    $("#fin_relation_id").val(data.fin_relation_id).trigger("change.select2");
                    
                }
                $("#mdlAddItem").modal("show");
            },
            clear:function(){
                $("#fst_glaccount_code").val(null).trigger("change.select2");
                $("#fst_notes").val(null);
                $("#fdc_debit").val(App.money_format(0));
                $("#fdc_credit").val(App.money_format(0));
                
                if ($('#fin_pc_customer_id').hasClass("select2-hidden-accessible")){
                    $('#fin_pc_customer_id').select2('destroy');
                }
                if ($('#fin_relation_id').hasClass("select2-hidden-accessible")){
                    $('#fin_relation_id').select2('destroy');
                }
                $("#fin_pcc_id").val(null).prop("disabled",true);
                $("#fin_pc_divisi_id").val(null).prop("disabled",true);
                $("#fin_pc_customer_id").val(null).prop("disabled",true);
                $("#fin_pc_project_id").val(null).prop("disabled",true);
                $("#fin_relation_id").val(null).prop("disabled",true);


                mdlAddItems.mode ='ADD';
                mdlAddItems.data =null;

            },
            hide:function(){
                $("#mdlAddItem").modal("hide");             
			},
			save:function(){
				var fblPcc = $("#fst_glaccount_code option:selected").data("pcc");
                var fblPcDiv = $("#fst_glaccount_code option:selected").data("pcdiv");
                var fblPcCust = $("#fst_glaccount_code option:selected").data("pccust");
                var fblPcProject = $("#fst_glaccount_code option:selected").data("pcproject");
                var fblCardControl = $("#fst_glaccount_code option:selected").data("cardcontroll");
                
                if (fblPcc){
                    if ($("#fin_pcc_id").val() == null){
                        alert ("<?= lang("Profit & Cost center harus diisi !")?>");
                        return;
                    }
                }
                if (fblPcDiv){
                    if ($("#fin_pc_divisi_id").val() == null){
                        alert ("<?= lang("Analisa divisi harus diisi !")?>");
                        return;
                    }
                }
                if (fblPcCust){
                    if ($("#fin_pc_customer_id").val() == null){
                        alert ("<?= lang("Analisa customer harus diisi !")?>");
                        return;
                    }
                }
                if (fblPcProject){
                    if ($("#fin_pc_project_id").val() == null){
                        alert ("<?= lang("Analisa project harus diisi !")?>");
                        return;
                    }
                }
                
                if (fblCardControl){
                    if ($("#fin_relation_id").val() == null){
                        alert ("<?= lang("Kontrol kartu relasi harus diisi !")?>");
                        return;
                    }
                }

                

                var data = {
                    fin_rec_id:0,
                    fst_glaccount_code:$("#fst_glaccount_code").val(),
                    fst_glaccount_name:$("#fst_glaccount_code option:selected").text(),
                    fst_notes:$("#fst_notes").val(),
                    fdc_debit:App.money_parse($("#fdc_debit").val()),
                    fdc_credit: App.money_parse($("#fdc_credit").val()),
                    fin_pcc_id:$("#fin_pcc_id").val(),
                    fst_pcc_name:$("#fin_pcc_id option:selected").text(),
                    fin_pc_divisi_id:$("#fin_pc_divisi_id").val(),
                    fst_pc_divisi_name:$("#fin_pc_divisi_id option:selected").text(),                    
                    fin_pc_customer_id:$("#fin_pc_customer_id").val(),
                    fst_pc_customer_name:$("#fin_pc_customer_id option:selected").text(),
                    fin_pc_project_id:$("#fin_pc_project_id").val(),
                    fst_pc_project_name:$("#fin_pc_project_id option:selected").text(),
                    fin_relation_id:$("#fin_relation_id").val(),
                    fst_relation_name:$("#fin_relation_id option:selected").text()
                };

                if (data.fdc_debit - data.fdc_credit == 0){
                    alert("<?=lang('Nilai debit atau credit tidak boleh 0')?>");
                    return;
                }

                var t =   $("#tblcbreceiveotheritems").DataTable();

                if (mdlAddItems.mode == "ADD"){
                    t.row.add(data); 
                }else{                    
                    data.fin_rec_id = mdlAddItems.data.fin_rec_id;
                    selectedDetail.data(data);
                }
                t.draw(false);
                mdlAddItems.clear();
                mdlAddItems.hide();
			}
			
        }

		$(function(){
            $("#fst_glaccount_code").val(null);
            $("#fst_glaccount_code").select2();          
            
            $("#fin_pcc_id").val(null);
            $("#fin_pc_divisi_id").val(null);
            $("#fin_pc_customer_id").val(null);
            $("#fin_pc_project_id").val(null);

            $("#fin_relation_id").val(null);
            //$("#fin_relation_id").select2();

            App.fixedSelect2();

            $("#fst_glaccount_code").change(function(e){
                e.preventDefault();
                var fblPcc = $("#fst_glaccount_code option:selected").data("pcc");
                var fblPcDiv = $("#fst_glaccount_code option:selected").data("pcdiv");
                var fblPcCust = $("#fst_glaccount_code option:selected").data("pccust");
                var fblPcProject = $("#fst_glaccount_code option:selected").data("pcproject");
                var fblCardControll = $("#fst_glaccount_code option:selected").data("cardcontroll");
                

                if (fblPcc){
                    $("#fin_pcc_id").prop("disabled",false);
                 }else{
                    $("#fin_pcc_id").prop("disabled",true);
                    $("#fin_pcc_id").val(null);
                 } 
                if (fblPcDiv) {
                    $("#fin_pc_divisi_id").prop("disabled",false);
                }else{
                    $("#fin_pc_divisi_id").prop("disabled",true);
                    $("#fin_pc_divisi_id").val(null);
                }

                if(fblPcCust) {
                    $('#fin_pc_customer_id').select2();
                    App.fixedSelect2();
                    $("#fin_pc_customer_id").prop("disabled",false);
                }else{
                    if ($('#fin_pc_customer_id').hasClass("select2-hidden-accessible")){
                        $('#fin_pc_customer_id').select2('destroy');
                    }

                    
                    $("#fin_pc_customer_id").prop("disabled",true);
                    $("#fin_pc_customer_id").val(null);
                }

                if (fblPcProject){
                    $("#fin_pc_project_id").prop("disabled",false);
                }else{
                    $("#fin_pc_project_id").prop("disabled",true);
                    $("#fin_pc_project_id").val(null);
                }

                if(fblCardControll) {
                    $("#fin_relation_id").prop("disabled",false);
                    $('#fin_relation_id').select2();
                    App.fixedSelect2();                    
                }else{
                    if ($('#fin_relation_id').hasClass("select2-hidden-accessible")){
                        $('#fin_relation_id').select2('destroy');
                    }                    
                    $("#fin_relation_id").prop("disabled",true);
                    $("#fin_relation_id").val(null);
                }



                $("#fdc_debit").change(function(e){
                    $("#fdc_credit").val(App.money_format(0));
                });

                $("#fdc_credit").change(function(e){
                    $("#fdc_debit").val(App.money_format(0));
                });
            });

			$("#btn-add-rincian").click(function(e){
                e.preventDefault();
                mdlAddItems.save();
            });

		})	
	</script>
</div>

<?php echo $mdlEditForm ?>
<?php echo $mdlJurnal ?>
<?php echo $mdlPrint ?>

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
	var selectedDetail;	
</script>

<script type="text/javascript" info="event">
	$(function(){
		$("#btnNew").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url()?>tr/kas_bank/penerimaan_lain/add";
		});

		$("#btnSubmitAjax").click(function(e){
			submitAjax(0);
		});
		$("#btnPrint").click(function(e){
			e.preventDefault();
			frameVoucher.print("<?=site_url()?>tr/kas_bank/penerimaan_lain/print_voucher/" + $("#fin_cbreceiveoth_id").val());
		});
		$("#btnJurnal").click(function(e){
			e.preventDefault();
			MdlJurnal.showJurnalByRef("CBRO",$("#fin_cbreceiveoth_id").val());
		});

		$("#btnDelete").click(function(e){
			e.preventDefault();
			deleteAjax(0);
		});

		$("#btnClose").click(function(e){
			e.preventDefault();
			window.location.href = "<?=site_url()?>tr/kas_bank/penerimaan_lain/";
		});

		$(".type-penerimaan").change(function(e){
			e.preventDefault();
			t = $("#tblcbreceiveothersitems").DataTable();
			t.clear().draw(false);
		});

		$("#fin_kasbank_id").change(function(e){
			e.preventDefault();
			getTransactionNumber($("#fin_kasbank_id").val(),$("#fdt_cbreceiveoth_datetime").val());
			t = $("#tblcbreceiveothersitems").DataTable();
			t.clear().draw(false);
		});
		

		$("#fst_curr_code").change(function(e){
			e.preventDefault();			
			rate =  $("#fst_curr_code option:selected").data("rate");
			$("#fdc_exchange_rate_idr").val(App.money_format(rate));
		});

		$("#fdc_exchange_rate_idr").val(App.money_format(1));

		$(".cls-nominal").change(function(e){
			fdcNominal = parseFloat($("#fdc_nominal").val());
			fdcRate = parseFloat($("#fdc_exchange_rate_idr").val());
			$("#fdc_nominal_idr").val(App.money_format(fdcNominal * fdcRate));
		});		

		$("#btn-detail-penerimaan").click(function(e){
			e.preventDefault();
			mdlAddItems.show();
		});

	});

</script>

<script type="text/javascript" info="init">
	$(function(){
		$("#fdt_cbreceiveoth_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");	
		
		$("#fin_kasbank_id").select2({
			ajax: {
				url: SITE_URL + "gl/config/prefix_cash_bank/getListByType",
				dataType:"json",
				data:function(params){
					params.kasbank_type = $(".type-penerimaan:checked").val();
					return params;
				},
				processResults: function (data) {
					// Transforms the top-level key of the response object from 'items' to 'results'
					var dataList = data.map(function(v){
						return {
							id: v.fin_kasbank_id,
							text: v.fst_kasbank_name
						}
					})
					App.log(dataList);

					return {
						results: dataList
					};    			
				}
				// Additional AJAX parameters go here; see the end of this chapter for the full code of this example
			}
		});


		$('#tblcbreceiveotheritems').on('preXhr.dt', function ( e, settings, data ) {
			//add aditional data post on ajax call
			data.sessionId = "TEST SESSION ID";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "id","width": "50px",sortable:false,data:"fin_rec_id",visible:false},	
				{"className":'details-control text-center',"defaultContent": '<i class="fa fa-caret-right" aria-hidden="true"></i>',width:"10px",orderable:false},			
				{"title" : "GL Account",data:"fst_glaccount_code",
					render:function(data,type,row){
						return row.fst_glaccount_code + " - " + row.fst_glaccount_name;
					}
				},
				{"title" : "Debit",width:"100px",data:"fdc_debit",
					render:function(data,type,row){
						return App.money_format(row.fdc_debit);
					},
					//render: $.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					className:'text-right'
				},	
				{"title" : "Credit",width:"100px",data:"fdc_credit",
					render:function(data,type,row){
						return App.money_format(row.fdc_credit);
					},
					//render: $.fn.dataTable.render.number( DIGIT_GROUP, DECIMAL_SEPARATOR, DECIMAL_DIGIT),
					className:'text-right'
				},							
				{"title" : "Action","width": "40px",sortable:false,className:'dt-body-center text-center',
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
			calculateTotal();
		}).on('click','.btn-edit',function(e){
			e.preventDefault();
			t = $("#tblcbreceiveotheritems").DataTable();
			var trRow = $(this).parents('tr');
			selectedDetail  = t.row(trRow);
			var data = t.row(trRow).data();
			mdlAddItems.show(data);
		}).on('click','.btn-delete',function(e){
			e.preventDefault();
			t = $('#tblcbreceiveotheritems').DataTable();
			var trRow = $(this).parents('tr');
			t.row(trRow).remove().draw();
		}).on("click",".details-control",function(e){
            e.preventDefault();
            t = $('#tblcbreceiveotheritems').DataTable();
            var tr = $(this).closest('tr');
            var row = t.row( tr );
            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
            }else {
                row.child( showJurnalDetail(row.data()) ).show();
                tr.addClass('shown');
            }            
		});
		

		$("#fin_customer_id").val(null);

		initForm();
		App.fixedSelect2();

	});
	function showJurnalDetail(data){
        var result ="";    
        result = "<table style='min-width:400px;font-size:9pt'>";
        result += "<tr style='border-bottom:1px solid #f9f9f9'><td>Memo</td><td>:</td><td>"+data.fst_notes+"</td></tr>";
        result += "<tr style='border-bottom:1px solid #f9f9f9'><td>Profit/Cost Center</td><td>:</td><td>"+data.fst_pcc_name+"</td></tr>";
        result += "<tr style='border-bottom:1px solid #f9f9f9'><td>Analisa Department</td><td>:</td><td>"+data.fst_pc_divisi_name+"</td></tr>";
        result += "<tr style='border-bottom:1px solid #f9f9f9'><td>Analisa Customer</td><td>:</td><td>"+data.fst_pc_customer_name+"</td></tr>";
        result += "<tr style='border-bottom:1px solid #f9f9f9'><td>Analisa Project</td><td>:</td><td>"+data.fst_pc_project_name+"</td></tr>";
        result += "<tr style='border-bottom:1px solid #f9f9f9'><td>Kontrol Kartu Relasi</td><td>:</td><td>"+data.fst_relation_name+"</td></tr>";
        result += "</table>";
        return result;
    }
</script>

<script type="text/javascript" info="function">
	function calculateTotal(){
		t = $("#tblcbreceiveotheritems").DataTable();
		dataList = t.data();
		var ttlDebit = 0;
		var ttlCredit = 0;		
		$.each(dataList,function(i,v){
			ttlDebit += parseFloat(v.fdc_debit);
			ttlCredit += parseFloat(v.fdc_credit);
		});
		$("#ttl-debet").val(App.money_format(ttlDebit));
		$("#ttl-credit").val(App.money_format(ttlCredit));
	}

	function getTransactionNumber(kasBankId,cbReceiveDatetime){
		cbReceiveDatetime = App.dateTimeParse(cbReceiveDatetime);
		App.getValueAjax({
			site_url:"<?=site_url()?>",
			model: "trcbreceiveother_model",
			func:"generateCBNo",
			params:[kasBankId,cbReceiveDatetime],
			callback:function(value){
				$("#fst_cbreceiveoth_no").val(value);
			}
		});
	}	

	function initForm(){
		if ($("#fin_cbreceiveoth_id").val() != 0){ //EDIT MODE
			//Get Data
			App.blockUIOnAjaxRequest("Get data transaction.....");
			$.ajax({
				url:"<?=site_url()?>tr/kas_bank/penerimaan_lain/fetch_data/" + $("#fin_cbreceiveoth_id").val(),

			}).done(function(resp){
				if (resp.message != ""){
					alert(resp.message);
				}
				if(resp.status =="SUCCESS"){
					var dataH = resp.dataH;
					var dataDetails = resp.dataDetails;
					
					App.autoFillForm(dataH);
					$("#fdt_cbreceiveoth_datetime").val(dateTimeFormat(dataH.fdt_cbreceiveoth_datetime)).datetimepicker("update");	
					App.addOptionIfNotExist("<option value='"+dataH.fin_kasbank_id+"'>"+dataH.fst_kasbank_name+"</option>","fin_kasbank_id");
					
					$(".cls-nominal").trigger("change");
					if(dataH.fdt_clear_date != null){
						$("#fdt_clear_date").val(dateFormat(dataH.fdt_clear_date)).datepicker("update");
					}else{
						$("#fdt_clear_date").val(null);
					}
					

					t = $("#tblcbreceiveotheritems").DataTable();
					dataItems = [];
					$.each(dataDetails,function(i,v){
						var data = {
							fin_rec_id:v.fin_rec_id,
							fst_glaccount_code:v.fst_glaccount_code,
							fst_glaccount_name:v.fst_glaccount_name,
							fst_notes:v.fst_notes,
							fdc_debit:App.money_parse(v.fdc_debit),
							fdc_credit: App.money_parse(v.fdc_credit),
							fin_pcc_id:v.fin_pcc_id,
							fst_pcc_name:v.fst_pcc_name,
							fin_pc_divisi_id:v.fin_pc_divisi_id == null ? "" : v.fin_pc_divisi_id,
							fst_pc_divisi_name:v.fst_pc_divisi_name,
							fin_pc_customer_id:v.fin_pc_customer_id,
							fst_pc_customer_name:v.fst_pc_customer_name,
							fin_pc_project_id:v.fin_pc_project_id,
							fst_pc_project_name:v.fst_pc_project_name,
							fin_relation_id:v.fin_relation_id,
							fst_relation_name:v.fst_relation_name
						};	
						dataItems.push(data);					
					});
					t.rows.add(dataItems).draw(false);
				}else{
					$("#btnNew").trigger("click");
				}

			});
		}else{
			$(".type-penerimaan").trigger("change");
		}
	}

	function submitAjax(confirmEdit){
		if ($("#fin_kasbank_id").val() == null){
			alert("<?= lang('Pilih jenis penerimaan ...!') ?>");
			return;
		}

		var dataSubmit = $("#frmHeader").serializeArray();
		var detailTrans = new Array();	
		var detailReceive = new Array();	

		var details = $('#tblcbreceiveotheritems').DataTable();
		
		var details = details.data();
		

		detailData = [];

		$.each(details,function(i,v){
			detailData.push(v);
		});

		dataSubmit.push({
			name:"details",
			value: JSON.stringify(detailData)
		});
		

		mode = $("#fin_cbreceiveoth_id").val() == "0" ? "ADD" : "EDIT";	


		if (mode == "ADD"){
			url =  "<?= site_url() ?>tr/kas_bank/penerimaan_lain/ajx_add_save/";
		}else{
			dataSubmit.push({
				name : "fin_user_id_request_by",
				value: MdlEditForm.user
			});
			dataSubmit.push({
				name : "fst_edit_notes",
				value: MdlEditForm.notes
			});

			url =  "<?= site_url() ?>tr/kas_bank/penerimaan_lain/ajx_edit_save/";
		}


		if (confirmEdit == 0 && mode != "ADD"){
			MdlEditForm.saveCallBack = function(){
				submitAjax(1);
			};		
			MdlEditForm.show();
			return;
		}

		App.blockUIOnAjaxRequest("Please wait while saving data.....");
		$.ajax({
			type: "POST",
			//enctype: 'multipart/form-data',
			url: url,
			data: dataSubmit,
			timeout: 600000,
			success: function (resp) {				
				if (resp.message != "")	{
					$.alert({
						title: 'Message',
						content: resp.message,
						buttons : {
							OK : function(){
								if(resp.status == "SUCCESS"){
									$("#btnNew").trigger("click");
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
					$("#fin_cbreceive_id").val(data.insert_id);
					//Clear all previous error
					$(".text-danger").html("");					
				}
			},
			error: function (e) {
				$("#result").text(e.responseText);
				$("#btnSubmit").prop("disabled", false);
			},
		}).always(function(){
			
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
			name : "<?=$this->security->get_csrf_token_name()?>",
			value: "<?=$this->security->get_csrf_hash()?>",
		});
		dataSubmit.push({
			name : "fin_user_id_request_by",
			value: MdlEditForm.user
		});
		dataSubmit.push({
			name : "fst_edit_notes",
			value: MdlEditForm.notes
		});

		var url =  "<?= site_url() ?>tr/kas_bank/penerimaan_lain/delete/" + $("#fin_cbreceiveoth_id").val();
		$.ajax({
			url:url,
			method:"POST",
			data:dataSubmit,
		}).done(function(resp){
			if (resp.message != ""){
				alert(resp.message);
			}
			if(resp.status == "SUCCESS"){
				$("#btnClose").trigger("click");			
			}
		});	

	}

	
	
</script>

<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>