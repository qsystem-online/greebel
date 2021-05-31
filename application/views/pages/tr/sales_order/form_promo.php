<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?=base_url()?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">



<section class="content-header">
	<h1><?=lang("Sales Order")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Sales Order") ?></a></li>
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
						<a id="btnSubmitAjax" class="btn btn-primary" href="#" title="<?=lang("Simpan")?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>						<a id="btnClose" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
					</div>					
				</div>
				<!-- end box header -->
				<!-- form start -->
				<form id="frmSalesOrder" class="form-horizontal" action="<?=site_url()?>tr/sales_order/add" method="POST" enctype="multipart/form-data">			
					<div class="box-body">
						<table id="tblItemsPromo" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
						
						<br>
						<label> Claimed Prize :</label>
						<br>
						<table id="tblClaimedPrize" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>


					</div>
					<!-- end box body -->
					<div class="box-footer text-right">						
					</div>
					<!-- end box-footer -->
				</form>
        	</div>
    	</div>
	</div>
</section>

<div id="myModal" class="modal fade in" role="dialog" style="display: hidden">
	<div class="modal-dialog" style="display:table;width:650px">
		<!-- modal content -->
		<div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
			<div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Add SO Detail")?></h4>
			</div>

			<div class="modal-body">	
				<div id="dialog-info" class="alert alert-info" style="display:none">
					<a href="#" class="close" onclick="$('#dialog-info').hide()" aria-label="close">&times;</a>
					<div class="info-message">
						<strong>Info!</strong> 
					</div>
				</div>

				<form class="form-horizontal">
					<div class="form-group promo-cashback" >
						<label for="dfin_item" class="col-md-3 control-label"><?=lang("Cashback")?> : </label>
						<label class="col-md-6 control-label" id="promo-cashback">0.00</label>
						<div class="col-md-3">
							<input id="btn-add-cashback" type="button" class="form-control btn btn-primary" value="Add Casback">
						</div>

					</div>
					<div class="form-group promo-other" >
						<label for="dfin_item" class="col-md-3 control-label"><?=lang("Other Item")?> : </label>
						<label class="col-md-6 control-label" id="promo-other">-</label>
						<div class="col-md-3">
							<input id="btn-add-other" type="button" class="form-control btn btn-primary" value="Add Other">
						</div>

					</div>
				

					<div class="form-group promo-freeitem">
						<label for="dfst_unit" class="col-md-12 control-label"><?=lang("Free Items")?></label>
						<div class="col-md-12">
							<table id="tblFreeItems" class="table table-bordered table-hover table-striped nowarp row-border" style="min-width:100%"></table>
						</div>
					</div>					
				</form>													
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
			</div>
		</div>
	</div>

	<script type="text/javascript" info="defined">
		var tblFreeItems;

		frmAdd = {
			selectedPromo:null,
			show:function(row){
				this.selectedPromo=row;
				var data = tblItemsPromo.row(this.selectedPromo).data();

				var claimData = tblClaimedPrize.data();


				if (data.fbl_promo_gabungan == 0  && claimData.length > 0){
					if (confirm("Promo tidak dapat digabung, hapus daftar hadiah ?") ){
						tblClaimedPrize.rows().remove().draw(false);						
					}else{
						return;
					}					
				}

				$.each


				$.ajax({
					url:"<?=site_url()?>tr/sales_order/ajxGetDetailPromo/" + data.fin_promo_id,
					method:"GET",
				}).done(function(resp){
					if (resp.status == "SUCCESS"){
						var data = resp.data;
						var promo = data.promo;
						var freeItems = data.free_items;

						$(".promo-cashback").show();					
						var cashback = parseFloat(promo.fdc_cashback);
						if ( cashback <= 0 ){
							$(".promo-cashback").hide();							
						}
						$("#promo-cashback").text(promo.fdc_cashback);


						$(".promo-other").show();
						if (promo.fst_other_prize == "" ){
							$(".promo-other").hide();							
						}
						$("#promo-other").text(promo.fst_other_prize);


						$(".promo-freeitem").show();
						tblFreeItems.clear().draw();
						if (freeItems.length <= 0){
							$(".promo-freeitem").hide();
						}
						$.each(freeItems,function(i,v){
							var data=v;
							tblFreeItems.row.add(data).draw(false);							
						});


						$("#myModal").modal("show");
					}
					
				})
			},
			hide:function(){
				this.selectedPromo=null;
				$("#myModal").modal("hide");
			}
		}
	</script>


	<script type="text/javascript" info="event">
		$(function(){
			$("#btn-add-cashback").click(function(e){
				e.preventDefault();
				var data = tblItemsPromo.row(this.selectedPromo).data();
				var cashback = $("#promo-cashback").text();				
				cashback = App.money_parse(cashback);				
				addPrize("CSHBCK",0,'CSHBCK',"CASH BACK",cashback);				
			});

			$("#btn-add-other").click(function(e){
				e.preventDefault();
				var data = tblItemsPromo.row(this.selectedPromo).data();
				var othPrize = $("#promo-other").text();				
				addPrize("OTH_ITEM",0,"OTHP",othPrize,0);				
			});

			
		})
	</script>

	<script type="text/javascript" info="init">
		$(function(){

			tblFreeItems = $('#tblFreeItems').on('preXhr.dt', function ( e, settings, data ) {
				//add aditional data post on ajax call
				data.sessionId = "TEST SESSION ID";		
			}).on('init.dt',function(){
				$(".dataTables_scrollHeadInner").css("min-width","100%");
				$(".dataTables_scrollHeadInner > table").css("min-width","100%");
				$(".dataTables_scrollBody").css("position","static");
			}).DataTable({
				scrollY: "300px",
				scrollX: true,			
				scrollCollapse: true,	
				order: [],
				columns:[				
					{"title" : "id",sortable:false,data:"fin_rec_id",visible:false},				
					{"title" : "Item","width": "",sortable:false,data:"fst_item_name",className:'text-left'},
					{"title" : "Unit","width": "25px",sortable:false,data:"fst_unit",className:'text-left'},
					{"title" : "Qty","width": "25px",sortable:false,data:"fdb_qty",className:'text-right'},
					{"title" : "Action","width": "60px",sortable:false,className:'dt-body-center text-center',
						render:function(data,type,row){
							var action = '<a class="btn-add-free-item" href="#" data-original-title="" title=""><i class="fa fa-plus-square"></i></a>&nbsp;';
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
			}).on("click",".btn-add-free-item",function(event){
				event.preventDefault();					
				var promo = tblItemsPromo.row(this.selectedPromo).data();

				var trRow = $(this).parents('tr');			
				var data = tblFreeItems.row(trRow).data();

				addPrize("FREE_ITEM",data.fin_item_id,data.fst_item_code,data.fst_item_name,0);				
				//myModal.show();
			});

		});
	</script>

	<script type="text/javascript" info="function">
		function addPrize(prizeType,itemId,itemCode,itemName,cashbackValue){
			var currentPromo = tblItemsPromo.row(frmAdd.selectedPromo).data();

			
			data = {
				fin_promo_id:currentPromo.fin_promo_id,
				fst_promo_name:currentPromo.fst_promo_name,
				fbl_promo_gabungan:currentPromo.fbl_promo_gabungan,
				fst_prize_type:prizeType,
				fin_item_id:itemId,
				fst_item_code:itemCode,
				fst_item_name:itemName,
				fdc_cashback: cashbackValue
			}

			//Cek If Duplikat

			var dataClaims = tblClaimedPrize.data();
			var duplicate = false;

			$.each(dataClaims,function(i,v){
				if (data.fin_promo_id == v.fin_promo_id){
					if (data.fst_prize_type == v.fst_prize_type &&  data.fst_prize_type != "FREE_ITEM"){
						duplicate = true;
						return false;
					}

					if (data.fst_prize_type == "FREE_ITEM"){
						//if (data.fin_item_id == v.fin_item_id){
							alert("tidak bisa ditambah, Free item sudah digunakan !");
							duplicate = true;
							return false;
						//}

					}
				}				
			});

			if (duplicate == false){
				tblClaimedPrize.row.add(data).draw(false);
			}

			frmAdd.hide();
			

		}
	</script>
</div>



<script type="text/javascript" info="binding_key">
	$(function(){
		$(document).bind('keydown', 'alt+d', function(){
			$("#btn-add-detail").trigger("click");
		});
	});
</script>

<script type="text/javascript" info="define">	
	var tblItemsPromo;
	var tblClaimedPrize;
	var selectedPromo;
</script>

<script type="text/javascript" info="init">
	$(function(){
		
			
		
		tblItemsPromo = $('#tblItemsPromo').on('preXhr.dt', function ( e, settings, data ) {
		 	//add aditional data post on ajax call
		 	data.sessionId = "TEST SESSION ID";		
		}).on('init.dt',function(){
			$(".dataTables_scrollHeadInner").css("min-width","100%");
			$(".dataTables_scrollHeadInner > table").css("min-width","100%");
			$(".dataTables_scrollBody").css("position","static");
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[				
				{"title" : "promo id",sortable:false,data:"fin_promo_id",visible:false},				
				{"title" : "Promo","width": "",sortable:false,data:"fst_promo_name"},
				{"title" : "Start","width": "100px",sortable:false,data:"fdt_start"},
				{"title" : "End","width": "100px",sortable:false,data:"fdt_end"},
				{"title" : "Gabungan","width": "25px",sortable:false,data:"fbl_promo_gabungan",className:'text-center',
					render:function(data,type,row){
						var checked = "";
						if (data == 1){
							checked ="checked";
						}
						return "<input type='checkbox' disabled " + checked + "/>";
					}				
				},
				{"title" : "Action","width": "60px",sortable:false,className:'dt-body-center text-center',
					render:function(data,type,row){
						var action = '<a class="btn-add-item" href="#" data-original-title="" title=""><i class="fa fa-plus-square"></i></a>&nbsp;';
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
		}).on("click",".btn-add-item",function(event){
			event.preventDefault();					
			
			var trRow = $(this).parents('tr');			
			frmAdd.show(trRow);
		});


		tblClaimedPrize = $('#tblClaimedPrize').on('preXhr.dt', function ( e, settings, data ) {
		 	//add aditional data post on ajax call
		 	data.sessionId = "TEST SESSION ID";		
		}).on('init.dt',function(){
			$(".dataTables_scrollHeadInner").css("min-width","100%");
			$(".dataTables_scrollHeadInner > table").css("min-width","100%");
			$(".dataTables_scrollBody").css("position","static");
		}).DataTable({
			scrollY: "300px",
			scrollX: true,			
			scrollCollapse: true,	
			order: [],
			columns:[				
				{"title" : "promo id",sortable:false,data:"fin_promo_id",visible:false},				
				{"title" : "Promo","width": "300px",sortable:false,data:"fst_promo_name"},
				{"title" : "Items","width": "",sortable:false,data:"fst_item_name",
					render:function(data,type,row){
						var item = row.fst_item_code + " - " + row.fst_item_name;
						if (row.fdc_cashback >0){
							item += " " + App.money_format(row.fdc_cashback);
						}
						return item;
					}
				},		
				{"title" : "Action","width": "60px",sortable:false,className:'dt-body-center text-center',
					render:function(data,type,row){
						var action = '<a class="btn-delete-item" href="#" data-original-title="" title=""><i class="fa fa-trash"></i></a>&nbsp;';
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
		}).on("click",".btn-delete-item",function(event){
			event.preventDefault();	
			//alert("masuk");							
			var trRow = $(this).parents('tr');			
			tblClaimedPrize.row(trRow).remove().draw(false);
		});

		init_form();
		App.fixedSelect2();		
	});
</script>

<script type="text/javascript" info="event">
	$(function(){

		$("#btnSubmitAjax").click(function(e){
			e.preventDefault();
			saveAjax();
		});

	});
</script>

<script type="text/javascript" info="function">	

	function saveAjax(){


		var details = [];
		
		$.each(tblClaimedPrize.data(),function(i,v){
			details.push(v);
		});


		var data =[];

		data.push({
			name : SECURITY_NAME,
			value: SECURITY_VALUE
		});

		data.push({
			name : "fin_salesorder_id",
			value: <?= $fin_salesorder_id?>
		});

		data.push({
			name : "details",
			value: JSON.stringify(details)
		});

		App.blockUIOnAjaxRequest("Please wait while saving data.....");
		$.ajax({
			url:"<?=site_url()?>tr/sales_order/ajx_save_promo",
			type: "POST",
			data: data,
			timeout: 600000
		}).done(function(resp){
			console.log(resp);

			if (resp.messages != ""){
				alert(resp.messages);
			}

			if (resp.status == "SUCCESS"){			
				window.location.replace("<?=site_url()?>tr/sales_order")
			}

		});
		

	}	

	function init_form(){
		<?php
			//var_dump($promos);				
			foreach($promos as $promo){
				?>					
					var data = {
						fin_promo_id:<?=$promo->fin_promo_id?>,
						fst_promo_name:"<?=$promo->fst_promo_name?>",
						fbl_promo_gabungan:<?=$promo->fbl_promo_gabungan?>,
						fdt_start:"<?=$promo->fdt_start?>",
						fdt_end:"<?=$promo->fdt_end?>"
					};
					tblItemsPromo.row.add(data).draw(false);

				<?php
			}
		?>

		

	}	

	
</script>

<!-- Select2 -->
<script src="<?=base_url()?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
