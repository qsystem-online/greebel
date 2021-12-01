<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?= base_url() ?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?= base_url() ?>bower_components/datatables.net/datatables.min.css">

<style type="text/css">
	.border-0 {
		border: 0px;
	}
	td {
		padding: 2px; 
			!important
	}
	.nav-tabs-custom>.nav-tabs>li.active>a {
		font-weight: bold;
		border-left-color: #3c8dbc;
		border-right-color: #3c8dbc;
		border-style: fixed;
	}
	.nav-tabs-custom>.nav-tabs {
		border-bottom-color: #3c8dbc;
		border-bottom-style: fixed;
	}
</style>

<section class="content-header">
	<h1><?= lang("Setup") ?><small><?= lang("form") ?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Production") ?></a></li>
		<li class="active title"><?= $title ?></li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header with-border">
				<h3 class="box-title title"><?=$title?></h3>
				<div class="btn-group btn-group-sm  pull-right">					
				</div>
			</div>
			<!-- end box header -->

			<!-- form start -->
			<form id="frmHeader" class="form-horizontal" action="<?= site_url() ?>master/project/add" method="POST" enctype="multipart/form-data">
				<div class="box-body">
					<div class="form-group">
						<div class="col-sm-12">							
							<table id="tblList" class="table table-bordered table-hover table-striped row-border compact nowarp" style="min-width:100%"></table>
						</div>
					</div>
					<!-- end box body -->
					
				</div>
				<div class="box-footer text-right">
				</div>
				<!-- end box-footer -->
			</form>
		</div>
	</div>
</section>

<?php
    //echo $mdlItemGroup;
?>

<div id="mdlForm" class="modal fade in" role="dialog" style="display: none">
    <div class="modal-dialog" style="display:table;width:80%;min-width:800px;max-width:100%">
        <!-- Modal content-->
        <div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
            <div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?= lang("Add Config Account") ?></h4>
            </div>

            <div class="modal-body">
				<form class="form-horizontal">
					<div class="form-group">
						<label for="fin_item_group_id" class="col-md-3 control-label"><?= lang("Title") ?></label>
						<div class="col-md-9">
							<input type="TEXT" class="form-control" readonly id="fst_title" />
						</div>
					</div>
					
					<div class="form-group">
						<label for="" class="col-md-3 control-label"><?= lang("GL Akun") ?></label>
						<div class="col-md-9">
						<select class="select2 form-control" id="fst_glaccount_code" style="width:100%"></select>
							<span id="fst_glaccount_code_error" class="text-danger"></span>
						</div>
					</div>

				</form>
			</div>

			<div class="modal-footer" style="width:100%;padding:10px" class="text-center">
				<button id="btn-save" type="button" class="btn btn-primary btn-sm text-center" style="width:15%"><?=lang("Save")?></button>
				<button type="button" class="btn btn-default btn-sm text-center" style="width:15%" data-dismiss="modal"><?=lang("Close")?></button>
			</div>
        </div>
    </div>

	<script type="text/javascript" info="define">
		var mdlForm = {
			row:null,
			show:function(row){
				
				this.row = row;
				var data = tblList.row(this.row).data();
				$("#fst_title").val(data.fst_title);
				
				$("#fst_glaccount_code").empty();
				App.addOptionIfNotExist("<option value='"+data.fst_glaccount_code+"' data-name='"+data.fst_glaccount_name+"'>"+data.fst_glaccount_code + " - " + data.fst_glaccount_name +"</option>","fst_glaccount_code");
				
				$("#mdlForm").modal("show");
				
			},
			hide:function(){
				$("#mdlForm").modal("hide");
			},
			clear:function(){
				$("#fst_title").val("");
				$("#fst_glaccount_code").empty();
			},			
			save:function(){
				var url;

				url = "<?=$save_url?>";				
				var data = tblList.row(this.row).data();
				data.fst_glaccount_code = $("#fst_glaccount_code").val();				
				data[SECURITY_NAME] = SECURITY_VALUE;


				$.ajax({
					url:url,
					method:"POST",
					data:data,
				}).done(function(resp){
					if(resp.status == "SUCCESS"){
						tblList.row(this.row).data(data).draw(false);
						this.row = null;
					}
				}).always(function(resp){
					if (resp.message != ""){
						alert(resp.message);
					}
				});
								

			}
		}
	</script>

    <script type="text/javascript" info="init">
        $(function() {
			$("#mdlForm").modal("hide");		
			$("#fst_glaccount_code").select2({
				width: '100%',
				ajax: {
					url: '<?= site_url() ?>master/setupaccountlogistic/ajxGetGlAccount',
					dataType: 'json',
					delay: 250,
					processResults: function(resp) {
						if(resp.messages != ""){
							alert(resp.messages);
						}

						if(resp.status != "SUCCESS"){
							return;
						}

						data2 = [];
						$.each(resp.data, function(index, value) {
							data2.push({
								"id": value.fst_glaccount_code,
								"text": value.fst_glaccount_code + " - " + value.fst_glaccount_name
							});
						});
						return {
							results: data2
						};
					},
					cache: true,
				}
			});


		});
	</script>
	
	<script type="text/javascript" info="event">
		$(function(){		
			$("#btn-save").click(function(e){
				e.preventDefault();
				mdlForm.save();
			})
		});
	</script>


</div>




<script type="text/javascript" info="define">	
	var tblList;
</script>

<script type="text/javascript" info="init">
	$(function(){
		tblList = $('#tblList').on('preXhr.dt', function ( e, settings, data ) {
		 	//add aditional data post on ajax call
			 //data.sessionId = "TEST SESSION ID";
			 //data.optionSearch = $('#selectSearch').val();
		}).DataTable({
			scrollX: true,
			scrollCollapse: true,
			order:[[0,"desc"]],
			columns:[
				{title:"id",width:"0",orderable:true,className:"",visible:false,data:"fin_rec_id"},
				{title:"Group Item",width:"",orderable:true,className:"",visible:true,data:"fst_title"},
				{title:"Akun",width:"450px",orderable:true,className:"",visible:true,data:"fst_glaccount_code",
					render:function(data,type,row){
						return row.fst_glaccount_code + " - " + row.fst_glaccount_name;
					}
				},				
				{title:"Action",width:"80px",orderable:true,className:"text-center",visible:true,
					render:function(data,type,row){
						var sstr =  "<a class='btn-edit' href='#' data-id='"  + row.fin_rec_id + "'><i class='fa fa-pencil'></i></a>";
						return sstr;
					}
				}	
			],
			//dataSrc:"data",
			processing: true,
			serverSide: true,
			ajax: "<?=$fetch_list_data?>"
		}).on('draw',function(){
			$(".dataTables_scrollHeadInner").css("min-width","100%");
			$(".dataTables_scrollHeadInner > table").css("min-width","100%");
			$(".dataTables_scrollBody").css("position","static");
		}).on('click',".btn-edit",function(e){
			e.preventDefault();
			var trRow = $(this).parents('tr');
			mdlForm.show(trRow);
		});


	});
</script>

<script type="text/javascript" info="event">
	$(function(){	
		$("#btn-add").click(function(e){
			e.preventDefault();
			mdlForm.show();
		});
	});
</script>

<script type="text/javascript" info="function">
</script>




<!-- Select2 -->
<script src="<?= base_url() ?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?= base_url() ?>bower_components/datatables.net/datatables.min.js"></script>