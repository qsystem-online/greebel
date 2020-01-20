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
	<h1><?=lang("Jurnal umum")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("jurnal umum") ?></a></li>
		<li class="active title"><?=$title?></li>
	</ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
				<div class="box-header with-border">
				<h3 class="box-title title"><?=$title?></h3>
				<?php if ($mode != "VIEW") { ?>
				<div class="btn-group btn-group-sm  pull-right">					
					<a id="btnNew" class="btn btn-primary" href="#" title="<?=lang("Tambah Baru")?>"><i class="fa fa-plus" aria-hidden="true"></i></a>
					<a id="btnSubmitAjax" class="btn btn-primary" href="#" title="<?=lang("Simpan")?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
					<a id="btnPrint" class="btn btn-primary" href="#" title="<?=lang("Cetak")?>"><i class="fa fa-print" aria-hidden="true"></i></a>
					<a id="btnJurnal" class="btn btn-primary" href="#" title="<?=lang("Jurnal")?>" style="display:<?= $mode == "ADD" ? "none" : "inline-block" ?>"><i class="fa fa-align-left" aria-hidden="true"></i></a>
					<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
					<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
				</div>
				<?php } ?>

			</div>
            <!-- end box header -->

            <!-- form start -->
            <form id="frmHeader" class="form-horizontal">			
				<div class="box-body">
					<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">					
					<input type="hidden" class="form-control" id="fin_journal_id" placeholder="<?=lang("(Autonumber)")?>" name="fin_journal_id" value="<?=$fin_journal_id?>" readonly>

					<div class="form-group">
						<label for="fst_journal_type" class="col-md-2 control-label"><?=lang("Jenis Jurnal.")?> #</label>
						<div class="col-md-4">
							<select class="form-control" id="fst_journal_type"  name="fst_journal_type">
                                <option value='JA'>Jurnal Penyesuaian</option>
                                <option value='JB'>Jurnal Pembelian</option>
                                <option value='JC'>Jurnal Penutup</option>
                                <option value='JJ'>Jurnal Penjualan</option>
                                <option value='JK'>Jurnal Pengeluaran</option>
                                <option value='JT'>Jurnal Penerimaan</option>
                                <option value='JU'>Jurnal Umum</option>
                                <option value='KK'>Kas Besar Keluar</option>
                            </select>
							<div id="fst_journal_type_err" class="text-danger"></div>
						</div>
                    </div>

					<div class="form-group">
						<label for="fst_journal_no" class="col-md-2 control-label"><?=lang("Jurnal No.")?> #</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_journal_no" placeholder="<?=lang("Jurnal No")?>" name="fst_journal_no" value="<?=$fst_journal_no?>" readonly>
							<div id="fst_journal_no_err" class="text-danger"></div>
						</div>
						
						<label for="fdt_journal_datetime" class="col-md-2 control-label"><?=lang("Jurnal Date")?> *</label>
						<div class="col-md-4">
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control text-right datetimepicker" id="fdt_journal_datetime" name="fdt_journal_datetime"/>
							</div>
							<div id="fdt_journal_datetime_err" class="text-danger"></div>
							<!-- /.input group -->
						</div>						
                    </div>

					<div class="form-group">	
						<label for="fst_curr_code" class="col-md-2 control-label"><?= lang("Mata Uang")?></label>
						<div class="col-md-2">
							<select class="form-control" id="fst_curr_code" placeholder="<?= lang("Mata Uang") ?>" name="fst_curr_code">
								<?php
									$currList = $this->mscurrencies_model->getCurrencyList();
									foreach($currList as $curr){
										$selected = $curr->fbl_is_default == 1 ? "selected" : "";
										echo "<option value='$curr->fst_curr_code' data-rate='$curr->fdc_exchange_rate_to_idr' $selected>$curr->fst_curr_code</option>";
									}
								?>
							</select>
							<div id="fst_curr_code_err" class="text-danger"></div>
						</div>
						<div class="col-md-2">
                            <input type="text" class="money form-control" id="fdc_exchange_rate_idr" name="fdc_exchange_rate_idr" value="1.00"/>
						</div>						
					</div>

                    <div class="form-group">	
						<label for="fst_desc" class="col-md-2 control-label"><?= lang("Keterangan")?></label>
						<div class="col-md-10">
							<textarea class="form-control" id="fst_desc" style="resize:none" placeholder="<?= lang("Keterangan") ?>" name="fst_desc"></textarea>
							<div id="fst_desc_err" class="text-danger"></div>
						</div>						
					</div>
                    
                    <div class="form-group" style="margin-bottom:0px;margin-top:20px">	
						<div class="col-md-12 text-right">
							<button id="btn-open-detail" class="btn btn-primary btn-sm"><?=lang("Tambah")?></button>
							<div id="fst_desc_err" class="text-danger"></div>
						</div>						
					</div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <table id="tblgltrjournalitems" class="table table-bordered table-hover table-striped" style="width:100%"></table>
                            <div id="detail_err" class="text-danger"></div>
                        </div>

                    </div>

                    <div class="form-group">
                        <div class="col-md-12 text-right">
                            <label class="col-md-8 control-label"><?=lang("Debit")?>:</label>
                            <label id="ttlDebit" class="col-md-1 control-label">0.00</label>
                            <label class="col-md-2 control-label"><?=lang("Credit")?>:</label>
                            <label id="ttlCredit" class="col-md-1 control-label">0.00</label>

                            
                        </div>
                    </div>


                    


                </div>
				<!-- end box body -->

                <div class="box-footer text-right">
                    <!-- <a id="btnSubmitAjax" href="#" class="btn btn-primary"><=lang("Simpan")?></a> -->
                </div>
                <!-- end box-footer -->
            </form>
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
                                                    echo "<option value='$acc->fst_glaccount_code' data-pcc='$acc->fbl_pcc' data-pcdiv='$acc->fbl_pc_divisi' data-pccust='$acc->fbl_pc_customer' data-pcproject='$acc->fbl_pc_project' data-cardcontroll='$acc->fbl_controll_card_relation' >$acc->fst_glaccount_name</option>";
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
											<textarea class="form-control text-left" id="fst_memo" style="resize:none"></textarea>
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
                    $("#fst_memo").val(data.fst_memo);
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
                $("#fst_memo").val(null);
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
                    fin_journal_id:0,
                    fst_glaccount_code:$("#fst_glaccount_code").val(),
                    fst_glaccount_name:$("#fst_glaccount_code option:selected").text(),
                    fst_memo:$("#fst_memo").val(),
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

                var t =   $("#tblgltrjournalitems").DataTable();

                if (mdlAddItems.mode == "ADD"){
                    t.row.add(data); 
                }else{
                    
                    data.fin_rec_id = mdlAddItems.data.fin_rec_id;
                    selectedDetail.data(data);
                    //t.row().data(data);
                }

                t.draw(false);

                mdlAddItems.clear();
                mdlAddItems.hide();
            });

		})	
	</script>
</div>



<?php
	echo $mdlJurnal;
	echo $mdlEditForm;
?>

<script type="text/javascript" info="bind">
	$(function(){
	
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
			window.location.replace("<?=site_url()?>tr/jurnal_umum/add");
		});

		$("#btnSubmitAjax").click(function(e){
            e.preventDefault();
            submitAjax(0);
		});

		$("#btnPrint").click(function(e){
			alert("print");
			//data = $("#fin_shipping_address_id").select2("data");
			data = $("#fin_customer_id").select2("data");
			console.log(data);
		});

		$("#btnJurnal").click(function(e){
			e.preventDefault();
			MdlJurnal.showJurnalByRef("JM",$("#fin_journal_id").val());			

		});

		
		$("#btnDelete").click(function(e){
			e.preventDefault();
			deleteAjax(0);
        });
        
        $("#btnList").click(function(e){
            e.preventDefault();
            window.location.replace("<?=site_url()?>tr/jurnal_umum");			
        });


        $("#fst_journal_type").change(function(e){
            e.preventDefault();
            var jurnalType = $("#fst_journal_type").val();
            App.getValueAjax({
                model:"gltrjournal_model",
                func:"generateTransNo",
                params:[jurnalType],                
				callback:function(value){
                    $("#fst_journal_no").val(value);
				}
            });
        })

		$("#btn-open-detail").click(function(e){
            e.preventDefault();
            mdlAddItems.show();			
		});

		$("#fst_curr_code").change(function(e){
			e.preventDefault();
			var rate = $("#fst_curr_code option:selected").data("rate");
			//alert(rate);
			$("#fdc_exchange_rate_idr").val(App.money_format(rate));	

		});		

		$(".ttl").change(function(e){
			e.preventDefault();
			var qty = parseFloat($("#fdb_qty").val());
			var price = parseFloat($("#fdc_price").val());
			var subTotal = qty * price;
			var ppnPercent = parseFloat($("#fdc_ppn_percent").val());
			var ppnAmount = subTotal * (ppnPercent /100);
			var other = parseFloat($("#fdc_other").val());
			var total = subTotal + ppnAmount + other;
			
			$("#sub_total").val(money_format(subTotal));
			$("#fdc_ppn_amount").val(money_format(ppnAmount));
			$("#total").val(money_format(total));


			
		})
	});
</script>

<script type="text/javascript" info="init">
	$(function(){	
		$("#btnDelete").confirmation({
			title:"<?= lang("Hapus data ini ?") ?>",
			rootSelector: '#btnDelete',
			placement:'left',
        });
        
        $("#fst_journal_type").val(null);

		$("#fdt_journal_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker('update');
		
		$('#tblgltrjournalitems').on('preXhr.dt', function ( e, settings, data ) {
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
                {"title" : "id","width": "0px",data:"fin_rec_id",visible:false},
                {"className":'details-control text-center',"defaultContent": '<i class="fa fa-caret-right" aria-hidden="true"></i>',width:"10px",orderable:false},				
                {"title" : "Account GL",data:"fst_glaccount_code",visible:true,orderable:false,
                    render:function(data,type,row){
                        return row.fst_glaccount_name;
                    }
                },                
                {"title" : "Debit","width": "100px",data:"fdc_debit",className:'text-right',orderable:false,
                    render:function(data,type,row){
                        return App.money_format(data);
                    }
                },
                {"title" : "Credit","width": "100px",data:"fdc_credit",className:'text-right',orderable:false,
                    render:function(data,type,row){
                        return App.money_format(data);
                    }
                },
                {"title" : "Action","width": "40px",className:'dt-body-center text-center',orderable:false,
                    render: function( data, type, row, meta ) {
                        return "<div style='font-size:16px'><a class='btn-edit' href='#'><i class='fa fa-pencil'></i></a><a class='btn-delete' href='#'><i class='fa fa-trash'></i></a></div>";                        
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
			calculateTotal();
		}).on("click",".btn-delete",function(event){
			event.preventDefault();
			t = $('#tblgltrjournalitems').DataTable();
			var trRow = $(this).parents('tr');
			t.row(trRow).remove().draw();
		}).on("click",".btn-edit",function(e){	
            e.preventDefault();
            t = $("#tblgltrjournalitems").DataTable();
            tRow = $(this).parents("tr");
            selectedDetail  = t.row(tRow);
			data = t.row(tRow).data();
			mdlAddItems.show(data);
        }).on("click",".details-control",function(e){
            e.preventDefault();
            t = $('#tblgltrjournalitems').DataTable();

            var tr = $(this).closest('tr');
            var row = t.row( tr );
    
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                row.child( showJurnalDetail(row.data()) ).show();
                tr.addClass('shown');
            }


            
        });

		//$("#fdc_exchange_rate_idr").val(App.money_format($("#fdc_exchange_rate_idr").val()));	


		$("#fst_sj_list").select2({
			ajax: {
				delay: 500,
				url: '<?=site_url()?>tr/sales/ekspedisi/get_sj_list',
				data:function(params){
					params.fin_customer_id = $("#fin_customer_id").val();					
					return params;
				},
				dataType: 'json',
				processResults: function (data) {      
					sjList = data.data.sjList;					
					arrSJ = sjList.map(function(sj){
						return {
							id: sj.fin_sj_id,
							text: sj.fst_sj_no,
							fdt_sj_datetime:App.dateFormat(sj.fdt_sj_datetime),
							fst_salesorder_no:sj.fst_salesorder_no,
							fdt_salesorder_datetime:sj.fdt_salesorder_datetime,
						}
					});				
					return {
						results: arrSJ
					};
    			}
			},
			minimumInputLength: 0,
			templateResult: function(sj){
				if (sj.loading == true){
					return sj.text;
				};

				return $("<span style='width:150px'>" + sj.text + "</span><span style='width:120px'>" + sj.fdt_sj_datetime + "</span><span>" + sj.fst_salesorder_no + "</span><span style='width:120px'>" + sj.fdt_salesorder_datetime + "</span>");
			}
		});

		$("#fin_shipping_address_id").select2({
			ajax: {
				delay: 500,
				url: function(){
					
					return '<?=site_url()?>pr/relation/get_shipping_address/' + $("#fin_customer_id").val();
				},			
				dataType: 'json',
				beforeSend:function(){
					
				},
				processResults: function (data) {      
					addressList = data.data;	
					arrAddress = addressList.map(function(address){
						return {
							id: address.fin_shipping_address_id,
							text: address.fst_name,
							fst_address:address.fst_shipping_address
						}
					});			
					return {
						results: arrAddress
					};
    			}
			},
			minimumInputLength: 0,
			//minimumResultsForSearch: Infinity,
			templateResult: function(sj){
				if (sj.loading == true){
					return sj.text;
				};
				return sj.text;				
			}
		}).on("select2:select",function(e){
			data = e.params.data;
			$("#shipping-address").val(data.fst_address);
		});
		
		App.fixedSelect2();
		initForm();
    });
    
    function showJurnalDetail(data){
        var result ="";    
        result = "<table style='min-width:400px;font-size:9pt'>";
        result += "<tr style='border-bottom:1px solid #f9f9f9'><td>Memo</td><td>:</td><td>"+data.fst_memo+"</td></tr>";
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
	function getDetailSJ(sjList, callback){
		App.blockUIOnAjaxRequest();
		dataPost = [];
		dataPost.push({
			name:SECURITY_NAME,
			value:SECURITY_VALUE,
		});
		
		dataPost.push({
			name:"fst_sj_id_list",
			value:sjList,
		});

		
		$.ajax({
			url:"<?=site_url()?>tr/sales/invoice/get_detail_sj",
			method:"POST",
			data:dataPost,
		}).done(function(resp){
			arrSJDetail = resp.data.arrSJDetail;
			sjDetailList = arrSJDetail.map(function(sjDetail){
				return {
					fin_rec_id:0,
					fin_item_id:sjDetail.fin_item_id,
					fst_custom_item_name:sjDetail.fst_custom_item_name,
					fdb_qty_so:sjDetail.fdb_qty_so,
					fdb_qty_sj:sjDetail.fdb_qty_sj,
					fst_unit:sjDetail.fst_unit,
					fdc_price:sjDetail.fdc_price,
					fst_disc_item:sjDetail.fst_disc_item,
					fdc_disc_amount_per_item:sjDetail.fdc_disc_amount_per_item					
				}
			});
			App.log(sjDetailList);
			
			t = $("#tblInvItems").DataTable();
			t.clear();
			t.rows.add(sjDetailList).draw();
			
			
		});

	}
	function calculateTotal(){
        t = $('#tblgltrjournalitems').DataTable();
        datas = t.rows().data();    
        App.log(datas);

		ttlDebit =0;
		ttlCredit= 0;
		$.each(datas,function(i,v){
            ttlDebit += parseFloat(v.fdc_debit);
            ttlCredit += parseFloat(v.fdc_credit);
		});
        $("#ttlDebit").text(App.money_format(ttlDebit));
        $("#ttlCredit").text(App.money_format(ttlCredit));    		
	}

	function getPaymentDueDate(){
		tglInv = $("#fdt_inv_datetime").val();
		tglDueDate = moment(tglInv,DATETIMEPICKER_FORMAT_MOMENT).add($("#fin_terms_payment").val(), 'days');
		$("#fdt_payment_due_date").val(tglDueDate.format(DATEPICKER_FORMAT_MOMENT));
	}


	function submitAjax(confirmEdit){
        
        data = $("#frmHeader").serializeArray();
		detail = new Array();		

		
		t = $('#tblgltrjournalitems').DataTable();
		datas = t.data();
		$.each(datas,function(i,v){
			detail.push(v);
        });
        
        data.push({
            name:"details",
			value: JSON.stringify(detail)
        })
		

		
		mode = $("#fin_journal_id").val() != 0 ? "EDIT" : "ADD";

		
		if (mode == "EDIT"){
			url = "<?=site_url()?>tr/jurnal_umum/ajx_edit_save";
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
			url = "<?=site_url()?>tr/jurnal_umum/ajx_add_save";
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
		if($("#fin_journal_id").val() != 0){
			App.blockUIOnAjaxRequest();
			$.ajax({
				url:"<?= site_url() ?>tr/jurnal_umum/fetch_data/" + $("#fin_journal_id").val(),
			}).done(function(resp){
				var dataH = resp.trJournal;
				var dataDetails = resp.trJournalItems;

				if (dataH == null){
					alert("<?= lang("Data tidak ditemukan !") ?>");
					//$("#btnNew").trigger("click");					
				}
				App.autoFillForm(dataH);

                $("#fdt_journal_datetime").val(dateTimeFormat(dataH.fdt_journal_datetime)).datetimepicker("update");	
                var dataList = [];			
				$.each(dataDetails,function(i,v){
					var data = {
                        fin_rec_id:v.fin_rec_id,
                        fin_journal_id:v.fin_journal_id,
                        fst_glaccount_code:v.fst_glaccount_code,
                        fst_glaccount_name:v.fst_glaccount_name,
                        fst_memo:v.fst_memo,
                        fdc_debit:v.fdc_debit,
                        fdc_credit: v.fdc_credit,
                        fin_pcc_id:v.fin_pcc_id,
                        fst_pcc_name:v.fst_pcc_name,
                        fin_pc_divisi_id:v.fin_pc_divisi_id,
                        fst_pc_divisi_name:v.fst_department_name, 
                        fin_pc_customer_id:v.fin_pc_customer_id,
                        fst_pc_customer_name:v.fst_pcc_customer_name,
                        fin_pc_project_id:v.fin_pc_project_id,
                        fst_pc_project_name:v.fst_project_name,
                        fin_relation_id:v.fin_relation_id,
                        fst_relation_name:v.fst_relation_name
                    };
                    dataList.push(data);
				});
                t = $('#tblgltrjournalitems').DataTable();
                t.rows.add(dataList).draw(false);								
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

		blockUIOnAjaxRequest("<h5>Deleting ....</h5>");
		$.ajax({
			url:"<?= site_url() ?>tr/jurnal_umum/delete/" + $("#fin_journal_id").val(),
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
