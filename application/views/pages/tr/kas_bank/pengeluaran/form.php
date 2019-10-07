
<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>

<section class="content-header">
	<h1><?=lang("Kas & Bank Pengeluaran")?><small><?=lang("form")?></small></h1>
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
						<a id="btnPrint" class="btn btn-primary" href="#" title="<?=lang("Cetak")?>"><i class="fa fa-print" aria-hidden="true"></i></a>
						<a id="btnJurnal" class="btn btn-primary" href="#" title="<?=lang("Jurnal")?>"><i class="fa fa-align-left" aria-hidden="true"></i></a>
						<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
						<a id="btnClose" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
					</div>
				</div>
				<!-- end box header -->

				<!-- form start -->
				<form id="frmSalesOrder" class="form-horizontal" action="<?=site_url()?>tr/sales_order/add" method="POST" enctype="multipart/form-data">			
					<div class="box-body">
						<input type="hidden" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">			
						<input type="hidden" id="frm-mode" value="<?=$mode?>">
						
						
						<div class="form-group">							
                            <label for="fin_kasbank_id" class="col-md-2 control-label"><?=lang("Jenis Pengeluaran")?> #</label>
                            <div class="col-md-5">
                                <select id="fin_kasbank_id" name="fin_kasbank_id" class="form-control">
                                    <option>TEST1</option>
                                </select>                                    
                            </div>
                            <div class="col-md-5">
                                <div class="radio">
                                    <label><input type="radio" id="radioKas"  name="optradio" checked><?= lang("Kas") ?></label>
                                    <label style="margin-left:20px"><input type="radio" id="radioBank" name="optradio"><?= lang("Bank") ?></label>
                                </div>
                            </div>                                
                            <div id="fin_kasbank_id" class="text-danger"></div>
						</div>


                        <div class="form-group">
							<label for="fdt_transaction_datetime" class="col-md-2 control-label"><?=lang("Tanggal Pengeluaran")?> *</label>
							<div class="col-md-2">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control text-right datetimepicker" id="fdt_transaction_datetime" name="fdt_transaction_datetime" value=""/>
								</div>
								<div id="fdt_transaction_datetime" class="text-danger"></div>
								<!-- /.input group -->
							</div>	
                            <label class="col-md-3 control-label"><?=lang("No. Pengeluaran Kas/Bank :")?></label>					
                            <label id="fst_transaction_no" class="col-md-2 control-label">xxxxx/BRANCH/YEAR/MONTH/99999</label>
						</div>

						<div class="form-group">						
							<label for="fst_curr_code" class="col-md-2 control-label"><?=lang("Mata Uang")?> </label>
							<div class="col-md-2">
								<select id="fst_curr_code" class="form-control" name="fst_curr_code">
									<option value=""></option>
								</select>
								<div id="fst_curr_code_err" class="text-danger"></div>
							</div>
						
							<label for="fdc_exchange_rate_idr" class="col-md-2 control-label"><?=lang("Nilai Tukar IDR")?> </label>
							<div class="col-md-1">
								<input type="text" class="form-control" id="fdc_exchange_rate_idr" name="fdc_exchange_rate_idr" style="width:50px" value="1" readonly/>
							</div>
							<label class="col-md-2 control-label" style="text-align:left;padding-left:0px"><?=lang("Rupiah")?> </label>
						</div>



						<div class="form-group">						
							<label for="fin_vendor_id" class="col-md-2 control-label"><?=lang("Vendor")?> </label>
							<div class="col-md-10">
								<select id="fin_vendor_id" class="form-control non-editable" name="fin_vendor_id">
									<option value="0">-- <?=lang("select")?> --</option>
								</select>
								<div id="fin_vendor_id_err" class="text-danger"></div>
							</div>						
						</div>
                        
                        <div class="form-group">                            
                            <label for="fin_vendor_id" class="col-md-2 control-label"><?=lang("Info")?> </label>							
                            <div class="col-sm-10">
                                <textarea class="form-control" id="fst_memo" placeholder="<?= lang("Memo") ?>" name="fst_memo" rows="3" style="resize:none"></textarea>
                                <div id="fst_memo_err" class="text-danger"></div>
                            </div>                        
                        </div>


						

						<div class="form-group">
							<div class="col-md-12" style='text-align:right'>
								<button id="btn-add-detail" class="btn btn-primary btn-sm">
									<i class="fa fa-cart-plus" aria-hidden="true"></i>
									<?=lang("Tambah Item")?>
								</button>
							</div>
						</div>
						<table id="tblcbpaymentitems" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
						<br>
						<div class="form-group">
							
							<div class="col-sm-6">	
								<div class="form-group">
									<label for="sub-total" class="col-md-8 control-label"><?=lang("Sub total")?></label>
									<div class="col-md-4" style='text-align:right'>
										<input type="text" class="form-control text-right" id="sub-total" value="0" readonly>
									</div>
								</div>
								
								<div class="form-group">
									<label for="total" class="col-md-8 control-label"><?=lang("Total")?></label>
									<div class="col-md-4" style='text-align:right'>
										<input type="text" class="form-control text-right" id="total" value="0" readonly>
									</div>
								</div>
								<div class="form-group">
									<label for="total" class="col-md-8 control-label"><?=lang("Uang Muka")?></label>
									<div class="col-md-4" style='text-align:right'>
										<input type="text" class="money form-control text-right" id="fdc_downpayment" name="fdc_downpayment" value="0">
									</div>
								</div>
							</div>
							
						</div>
						
						

					</div>
					<!-- end box body -->

					<div class="box-footer text-right">
						<!-- <a id="btnSubmitAjaxOld" href="#" class="btn btn-primary"><=lang("Save Ajax")?></a> -->
					</div>
					<!-- end box-footer -->
				</form>
        	</div>
    	</div>
	</div>