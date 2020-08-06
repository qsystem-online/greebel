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
</style>

<section class="content-header">
	<h1><?=lang("Disposal Asset ")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Delivery Order") ?></a></li>
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
					<input type="hidden" id="fin_fa_disposal_id" name="fin_fa_disposal_id" value="<?=$fin_fa_disposal_id?>"/>

					<div class="form-group">
						<label for="fst_fa_disposal_no" class="col-md-2 control-label"><?=lang("Disposal")?> #</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="fst_fa_disposal_no" placeholder="<?=lang("Disposal No")?>" name="fst_fa_disposal_no" value="<?=$fst_fa_disposal_no?>"/>
							<div id="fst_fa_disposal_no_err" class="text-danger"></div>
						</div>								

						<label for="fdt_fa_disposal_datetime" class="col-md-2 control-label"><?=lang("Tgl Disposal")?></label>
						<div class="col-md-4">
							<input type="text" class="form-control datetimepicker text-right" id="fdt_fa_disposal_datetime" placeholder="<?=lang("Mutasi Datetime")?>" name="fdt_fa_disposal_datetime" value=""/>
							<div id="fdt_fa_disposal_datetime_err" class="text-danger"></div>
						</div>								
                    </div>  

					<div class="form-group">
						<label for="fst_disposal_type" class="col-md-2 control-label"><?=lang("Tipe")?></label>
						<div class="col-md-4">
							<select  class="form-control" id="fst_disposal_type" placeholder="<?=lang("Tipe Disposal")?>" name="fst_disposal_type">
								<option value='DESTROY'>Destroy</option>
								<option value='JUAL'>Jual</option>
								<option value='MUTASI'>Mutasi</option>
							</select>
							<div id="fst_disposal_type_err" class="text-danger"></div>
						</div>					
					</div>

					<div class="form-group type-mutasi">					
						<label for="fin_to_branch_id" class="col-md-2 control-label"><?=lang("Cabang Tujuan")?></label>
						<div class="col-md-10">
							<select  class="form-control" id="fin_to_branch_id" placeholder="<?=lang("Cabang Tujuan")?>" name="fin_to_branch_id" style="width:100%">
								<?php
									$listBranch =  $this->msbranches_model->getAllList();
									foreach($listBranch as $branch){
										echo "<option value='$branch->fin_branch_id' >$branch->fst_branch_name</option>";
									}

								?>
							</select>
							<div id="fin_to_branch_id_err" class="text-danger"></div>
						</div>
                    </div>  

					<div class="form-group type-destroy">
						<label for="fst_destroy_ba" class="col-md-2 control-label"><?=lang("Berita Acara")?> #</label>
						<div class="col-md-10">
							<input type="TEXT" class="form-control" id="fst_destroy_ba" name="fst_destroy_ba"/>
							<div id="fst_destroy_ba_err" class="text-danger"></div>
						</div>																					
                    </div>  

					<div class="form-group type-jual">
						<label for="fst_sell_curr_code" class="col-md-2 control-label"><?=lang("Currency")?> #</label>
						<div class="col-md-4">
							<select class="form-control" id="fst_sell_curr_code" name="fst_sell_curr_code" style="width:100%">
							</select>
							<div id="fst_sell_curr_code_err" class="text-danger"></div>							
						</div>						
						<label for="fdc_sell_exchange_rate_idr" class="col-md-2 control-label"><?=lang("Exchange Rate")?> </label>
						<div class="col-md-4">
							<input type='TEXT' class="form-control money" id="fdc_sell_exchange_rate_idr" name="fdc_sell_exchange_rate_idr" value="0" />
							<div id="fdc_sell_exchange_rate_idr_err" class="text-danger"></div>							
						</div>		
					</div>


					<div class="form-group type-jual">
						<label for="fin_customer_id" class="col-md-2 control-label"><?=lang("Customer")?> #</label>
						<div class="col-md-10">
							<select class="form-control" id="fin_customer_id" name="fin_customer_id" style="width:100%"></select>
							<div id="fin_customer_id_err" class="text-danger"></div>
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
						<div class="col-md-12" style="text-align:right">
							<button id="btn-add-items" class="btn btn-primary btn-sm"><i class="fa fa-cart-plus" aria-hidden="true"></i>&nbsp;&nbsp;Tambah Item</button>
						</div>
						<div class="col-sm-12">
							<table id="tbldetails" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
						</div>
					</div>	

					<div class="form-group type-jual">
						<label class="col-md-10 control-label"><?=lang("sub total")?> #</label>
						<label  class="col-md-2 control-label" id="subtotal-sell">0.00</label>																
                    </div>  

					<div class="form-group type-jual">
						<label class="col-md-9 control-label"><?=lang("Ppn %")?></label>
						<div class="col-md-1">
							<input type="text" class="form-control money" id="fdc_ppn_percent" name="fdc_ppn_percent" value="0.00"/>
							<div id="fdc_ppn_percent_err" class="text-danger"></div>
						</div>						
						<label class="col-md-2 control-label" id="fdc_ppn_amount">0.00</label>
                    </div>  
					<div class="form-group type-jual">
						<label class="col-md-10 control-label"><?=lang("sub total")?> #</label>
						<label  class="col-md-2 control-label" id="total-sell">0.00</label>																
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
				<h4 class="modal-title"><?=lang("Tambah Fixed Asset")?></h4>
			</div>

			<div class="modal-body">				        
				<form id="form-detail-penerimaan-gudang" class="form-horizontal">
				
					<div class="form-group">										
						<label class="col-md-2 control-label "><?=lang("FA Code")?></label>						
						<div class="col-md-10">																		
							<select id="fst_fa_profile_code" class="form-control" style="width:100%"> </select>
						</div>
					</div>
					<div class="form-group">										
						<label class="col-md-2 control-label "><?=lang("Perolehan")?></label>						
						<div class="col-md-4">																		
							<input type="text" id="fdc_aquisition_price" class="form-control money" value="0.00" readonly/>
						</div>
						<label class="col-md-2 control-label "><?=lang("Susut")?></label>	
						<div class="col-md-4">																		
							<input type="text" id="fdc_depre_amount" class="form-control money" value="0.00" readonly/>
						</div>
					</div>
					
					<div class="form-group">										
						<label class="col-md-2 control-label "><?=lang("Nilai Buku")?></label>						
						<div class="col-md-10">																		
							<input type="text" id="fdc_book_amount" class="form-control money" value="0.00" readonly/>
						</div>
					</div>
					<div class="form-group type-jual">										
						<label class="col-md-2 control-label "><?=lang("Harga Jual")?></label>						
						<div class="col-md-10">																		
							<input type="text" id="fdc_sell_price" class="form-control money" value="0.00"/> 
						</div>
					</div>
					<div class="form-group">										
						<label class="col-md-2 control-label "><?=lang("Notes")?></label>						
						<div class="col-md-10">																		
						<textarea id="fst_detail_notes" class="form-control"> </textarea>
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
		var selectedAsset;

		mdlDetail = {			
			show:function(){
				if (selectedDetail != null){
					var data = selectedDetail.data();
					selectedAsset = data.fa_profile;

					//console.log(data);
					//$("#fst_fa_profile_code").val(data.fst_fa_profile_code + " - " +data.fst_fa_profile_name).trigger("change.select2");

					App.addOptionIfNotExist("<option value='"+selectedAsset.fin_fa_profile_rec_id+"'>"+selectedAsset.fst_fa_profile_code + " - " +selectedAsset.fst_fa_profile_name+"</option>","fst_fa_profile_code");
					//$("#fst_fa_profile_code").trigger("change");									
					$("#fst_fa_profile_code").trigger({
						type: 'select2:select',
    					params: {
        					data: selectedAsset
    					}
					});
					
					//fst_notes:$("#fst_detail_notes").val(),										
					//};
					//$("#fst_fa_profile_code").val(selectedAsset.fin_rec_id).trigger("change");
					$("#fdc_sell_price").val(data.fdc_sell_price);
					$("#fst_detail_notes").val(data.fst_notes);

				}				
				$("#mdlDetail").modal("show");
			},
			hide:function(){
				$("#mdlDetail").modal("hide");
			},
			clear:function(){
				$("#fst_fa_profile_code").val(null).trigger("change.select2");
				$("#fdc_aquisition_price").val(0);
				$("#fdc_depre_amount").val(0);
				$("#fdc_book_amount").val(0);
				selectedAsset = null;
				$("#fst_detail_notes").val(null);
				selectedDetail = null;
			},			
		};

	</script>

	<script type="text/javascript" info="event">
		
		$(function(){
			$("#fst_fa_profile_code").on("select2:select",function(e){
				selectedAsset = e.params.data;
				$("#fdc_aquisition_price").val(selectedAsset.fdc_aquisition_price);
				$("#fdc_depre_amount").val(selectedAsset.fdc_depre_amount);
				$("#fdc_book_amount").val(selectedAsset.fdc_aquisition_price - selectedAsset.fdc_depre_amount);
				//$("fdc_aquisition_price").val(selectedAsset.fdc_aquisition_price);
				

			});

			$("#btn-save-detail").click(function(e){

				t = $("#tbldetails").DataTable();
				var data = {
					fin_rec_id:0,					
					fa_profile:selectedAsset,
					fdc_sell_price:$("#fdc_sell_price").val(),
					fst_notes:$("#fst_detail_notes").val(),
				};

				if (selectedDetail == null){
					t.row.add(data).draw(false);
				}else{
					t.row(selectedDetail).data(data).draw(false);
				}
				mdlDetail.clear();	
				mdlDetail.hide();		
			});

		});
	</script>
	<script type="text/javascript" info="init">
		$(function(){
			$("#fst_fa_profile_code").select2({
				placeholder: 'Profile Code',
				allowClear:true,				
				ajax: {
					delay: 250,
					minimumInputLength: 0,
					url: '<?=site_url()?>tr/fixed_asset/disposal/ajxListFixedAsset',
					dataType: 'json',
					data: function (params) {
						//params.fin_wa_id = $("#fin_from_warehouse_id").val();
						return params
					},
					processResults: function (resp) {
						if (resp.status == "SUCCESS"){
							var data = resp.data;
							var arrData = $.map(data,function(obj){
								obj.id = obj.fin_fa_profile_rec_id;
								obj.text = obj.fst_fa_profile_code + " - " +obj.fst_fa_profile_name;
								return obj;
							});

							return {
								results: arrData
							};								

						}
						return;
					},
					

				}
			});

		});

	</script>

</div>



<?php echo $mdlEditForm ?>
<?php echo $mdlPrint ?>
<?php echo $mdlJurnal ?>

<script type="text/javascript" info="define">
	var selectedDetail;	
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
			window.location.replace("<?=site_url()?>tr/fixed_asset/disposal/add");
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
			window.location.replace("<?=site_url()?>tr/fixed_asset/disposal");
		});	

		$("#btn-add-items").click(function(e){
			e.preventDefault();
			mdlDetail.show();
		});

		$("#fst_disposal_type").change(function(e){
			var tipe = $(this).val();
			var t = $('#tbldetails').DataTable();


			$(".type-destroy,.type-jual,.type-mutasi").hide();
			if (tipe=="MUTASI"){
				$(".type-mutasi").show();
				t.column(3).visible(false);
			}else if (tipe=="DESTROY"){
				$(".type-destroy").show();
				t.column(3).visible(false);
			}else if (tipe=="JUAL"){
				$(".type-jual").show();				
				t.column(3).visible(true);
			}

		})
		$("#fdc_ppn_percent").change(function(e){
			calculateTotal();
		});
		
	});
</script>
<script type="text/javascript" info="init">
	$(function(){
		$(".type-jual,.type-mutasi").hide();
		$(".type-destroy").show();
		
		$("#fdt_fa_disposal_datetime").val(dateTimeFormat("<?= date("Y-m-d H:i:s")?>")).datetimepicker("update");

		$("#fst_sell_curr_code").select2({
			minimumResultsForSearch: -1,
			ajax:{
				delay:250,
				minimumInputLength: 0,
				url:"<?=site_url()?>master/currency/ajxGetList",
				data:function(params){
					params.proses_datetime = $("#fdt_fa_disposal_datetime").val();
					return params;
				},
				processResults: function (resp) {
					console.log(resp);
					if (resp.status == "SUCCESS"){
						data = resp.data;
						var list  = $.map(data,function(v,i){
							v.id = v.fst_curr_code;
							v.text = v.fst_curr_name;
							return v;
						});
						return {
							results:list
						}							
					}


				}

			}
		}).on("select2:select",function(e){
			curr = e.params.data;
			$("#fdc_sell_exchange_rate_idr").val(curr.fdc_exchange_rate_to_idr);


		});

		$("#fin_to_branch_id").select2();

		$("#fin_customer_id").select2({

			minimumInputLength: 1,
			ajax:{
				url:"<?=site_url()?>tr/fixed_asset/disposal/ajxListRelation",
				dataType: 'json',
				delay: 250,
				processResults: function (resp) {
					if (resp.status == "SUCCESS"){
						data = resp.data;
						var customerList = $.map(data,function(v,i){
							v.id = v.fin_relation_id;
							v.text =v.fst_relation_name;
							return v;
						});
						return {
							results: customerList,
						};	
					}      				
					return;
    			},
    			cache: true,
			}
		});

		$('#tbldetails').on('preXhr.dt', function ( e, settings, data ) {
			data.sessionId = "TEST SESSION ID";
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[
				{"title" : "id","width": "0px",sortable:false,data:"fin_rec_id",visible:false},
				{"title" : "Fixed Asset","width": "300px",sortable:false,data:"fa_profile",
					"render":function(data,type,row){
						var faProfile = row.fa_profile;
						return faProfile.fst_fa_profile_code + " - " + faProfile.fst_fa_profile_name;
					}
				},
				{"title" : "Notes","width": "0px",sortable:false,data:"fst_notes"},
				{"title" : "Sell Price","width": "150px",sortable:false,data:"fdc_sell_price",className:'text-right'},

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
			t = $("#tbldetails").DataTable();
			var trRow = $(this).parents('tr');
			selectedDetail = t.row(trRow);

			mdlDetail.show();

		}).on('click','.btn-delete',function(e){
			e.preventDefault();
			t = $('#tbldetails').DataTable();
			var trRow = $(this).parents('tr');
			t.row(trRow).remove().draw();
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
		
		var t = $('#tbldetails').DataTable();
		var datas = t.data();

		$.each(datas,function(i,v){

			//var faProfile = v.fa_profile; 
			v.fin_fa_profile_detail_id = v.fa_profile.fin_fa_profile_rec_id,
			delete v.fa_profile;			
			dataDetails.push(v);
		});

		data.push({
			name:"details",
			value: JSON.stringify(dataDetails)
		});

		if (mode == "ADD"){
			url = "<?=site_url()?>tr/fixed_asset/disposal/ajx_add_save";
		}else{
			

			url = "<?=site_url()?>tr/fixed_asset/disposal/ajx_edit_save";
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
					$("#fin_sj_id").val(data.insert_id);

					//Clear all previous error
					$(".text-danger").html("");
					// Change to Edit mode
					$("#frm-mode").val("EDIT");  //ADD|EDIT
					$('#fst_sj_no').prop('readonly', true);
					$("#tabs-so-detail").show();
				}
        });

	}
	
	function initForm(){
		var mode = "<?=$mode?>";		
		if (mode == "EDIT"){			
			App.blockUIOnAjaxRequest();
			$.ajax({
				url:"<?= site_url() ?>tr/fixed_asset/disposal/fetch_data/<?=$fin_fa_disposal_id?>",
			}).done(function(resp){				
				dataH =  resp.data.header;
				if (dataH == null){
					alert("<?=lang("ID transaksi tidak dikenal")?>");
					return false;
				}
				
                App.autoFillForm(dataH);
				//$("#fdt_fa_disposal_datetime").val(dataH.fst_accum_account_code).trigger("change");
				$("#fdt_fa_disposal_datetime").val(dateTimeFormat(dataH.fdt_fa_disposal_datetime)).datetimepicker("update");
				$("#fst_disposal_type").trigger("change");

				//$("#fin_customer_id")
				App.addOptionIfNotExist("<option value='"+dataH.fin_customer_id+"' selected>"+dataH.fst_customer_name+"</option>","fin_customer_id");
				
				App.addOptionIfNotExist("<option value='"+dataH.fst_sell_curr_code+"' selected>"+dataH.fst_sell_curr_name+"</option>","fst_sell_curr_code");

				t = $("#tbldetails").DataTable();
				$.each(resp.data.details,function(i,v){
					var fixedAsset = {
						"id":v.fin_fa_profile_detail_id,
						"text":v.fst_fa_profile_code + " - " + v.fst_fa_profile_name,
						"fin_fa_profile_rec_id":v.fin_fa_profile_detail_id,						
						"fst_fa_profile_code":v.fst_fa_profile_code,
						"fst_fa_profile_name":v.fst_fa_profile_name,		
						"fdc_aquisition_price":v.fdc_aquisition_price,
						"fdc_depre_amount":v.fdc_deprecated_amount
					};
					var data = {
						fin_rec_id:v.fin_rec_id,					
						fa_profile:fixedAsset,
						fdc_sell_price:v.fdc_sell_price,
						fst_notes:v.fst_notes,
					};
					t.row.add(data);
				});

				t.draw(false);

				

				
					





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

		var url =  "<?= site_url() ?>tr/fixed_asset/disposal/delete/" + $("#fin_fa_disposal_id").val();
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

	function calculateTotal(){
		var t = $('#tbldetails').DataTable();
		var datas = t.data();

		var ttlSell =0;
		$.each(datas,function(i,v){
			ttlSell += parseFloat(v.fdc_sell_price);
		});

		var ttlPpn = (parseFloat($("#fdc_ppn_percent").val()) /100) * ttlSell;
		$("#subtotal-sell").text(App.money_format(ttlSell));
		$("#fdc_ppn_amount").text(App.money_format(ttlPpn));
		$("#total-sell").text(App.money_format(ttlPpn + ttlSell));
	}

</script>
<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
