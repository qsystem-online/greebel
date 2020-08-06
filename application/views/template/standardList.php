<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- <link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css"> -->
<link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net/datatables.min.css">

<section class="content-header">
	<h1><?=$page_name?><small>List</small></h1>
	<ol class="breadcrumb">
		<?php 
			foreach($breadcrumbs as $breadcrumb){
				if ($breadcrumb["link"] == NULL){
					echo "<li class='active'>".$breadcrumb["title"]."</li>";
				}else{
					echo "<li><a href='".$breadcrumb["link"]."'>".$breadcrumb["icon"].$breadcrumb["title"]."</a></li>";
				}
				
			} 
		?>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header with-border">
				<h3 class="box-title"><?=$list_name?></h3>
				<div class="box-tools">
					<a id="btnNew" data-toggle="confirmation" href="<?=$addnew_ajax_url?>" class="btn btn-primary btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> New Record</a>
					<?php if (isset($report_url)){ ?>
						<a id="btnPrint" href="<?=$report_url?>" class="btn btn-primary btn-sm"><i class="fa fa-print" aria-hidden="true"></i> Report </a>
					<?php } ?>

				</div>

			</div>			
			<!-- /.box-header -->
			<div class="box-body">
				<div align="right">
					<span>Search on:</span>
					<span>
                        <select id="selectSearch" name="selectSearch" style="width: 148px;background-color:#e6e6ff;padding:8px;margin-left:6px;margin-bottom:6px">                            
                            <?php
                                foreach($arrSearch as $key => $value){ ?>
                                    <option value=<?=$key?>><?=$value?></option>
                                <?php
                                }
							// <option value="a.fin_id">No.Transaksi</option>
							// <option value="a.fst_customer_name">Customer</option>
                            // <option value="c.fst_salesname">Sales Name</option>
                            ?>
						</select>
					</span>
				</div>
				<table id="tblList" class="table table-bordered table-hover table-striped row-border compact nowarp" style="min-width:100%"></table>
			</div>
			<!-- /.box-body -->
			<div class="box-footer">
			</div>
			<!-- /.box-footer -->		
		</div>
	</div>
</div>
<?php
	if(isset($mdlEditForm)){
		echo $mdlEditForm;
	}
?>
<?php
	if(isset($jsfile)){
		echo $jsfile;
	}
?>
<script type="text/javascript">
	var t;
	var trRow;
	var needConfirmDelete = false;

	$(function(){	
		
		if ($('#mdlEditForm').length > 0){
			needConfirmDelete = true;
		}

		$('#tblList').on('preXhr.dt', function ( e, settings, data ) {
		 	//add aditional data post on ajax call
			 //data.sessionId = "TEST SESSION ID";
			 data.optionSearch = $('#selectSearch').val();
		}).DataTable({
			scrollX: true,
			scrollCollapse: true,
			order:[[0,"desc"]],
			columns:[
                <?php
					foreach($columns as $col){?>
						<?php
							$strData = isset($col['data']) ? ",data:\"" . $col['data'] ."\"" : "";

						?>
						{"title" : "<?=$col['title']?>","width": "<?=$col['width']?>"
							<?= $strData ?>
                            <?php if(isset($col['render'])){?>
                                ,"render":<?php echo $col['render'] ?>
                            <?php } ?>
							<?php if(isset($col['visible'])){?>
                                ,"visible":<?php echo $col['visible'] ?>
                            <?php } ?>
                            <?php if(isset($col['sortable'])){
                                if ($col['sortable']){ ?>
                                    ,"sortable": true
                                <?php }else
                                {?>
                                    ,"sortable": false
                                <?php }
                                
                            } ?>
                            <?php if(isset($col['className'])){?>
                                ,"className":"<?=$col['className']?>"
                            <?php } ?>
                        },
                    <?php }
                ?>
			],
			dataSrc:"data",
			processing: true,
			serverSide: true,
			ajax: "<?=$fetch_list_data_ajax_url?>"
		}).on('draw',function(){
			$(".dataTables_scrollHeadInner").css("min-width","100%");
			$(".dataTables_scrollHeadInner > table").css("min-width","100%");
			$(".dataTables_scrollBody").css("position","static");

			$('.btn-delete').confirmation({
				//rootSelector: '[data-toggle=confirmation]',
				title: "<?=lang('Hapus data ini ?')?>",
				rootSelector: '.btn-delete',
				// other options
			});	
		});
		
		$("#tblList").on("click",".btn-delete",function(event){
			t = $('#tblList').DataTable();
			trRow = $(this).parents('tr');				
			data = t.row(trRow).data();
			id = data.<?=$pKey?>;
			
			//

			deleteAjax(id,false);			
		});

		$("#tblList").on("click",".btn-edit",function(event){
			id = $(this).data("<?=$pKey?>");
			if (typeof id === "undefined") {
				t = $('#tblList').DataTable();
				var trRow = $(this).parents('tr');				
				data = t.row(trRow).data();
				id = data.<?=$pKey?>;
			}
			window.location.replace("<?=$edit_ajax_url?>" + id);
		});

	});

	function deleteAjax(id,confirmDelete){
		var dataSubmit = [];
		dataSubmit.push({
			name : SECURITY_NAME,
			value: SECURITY_VALUE,
		});
		
		if (needConfirmDelete){
			if (confirmDelete == 0){
				MdlEditForm.saveCallBack = function(){
					deleteAjax(id,1);
				};		
				MdlEditForm.show();
				return;
			}			
			dataSubmit.push({
				name : "fin_user_id_request_by",
				value: MdlEditForm.user
			});
			dataSubmit.push({
				name : "fst_edit_notes",
				value: MdlEditForm.notes
			});
		}

		$.ajax({			
			url:"<?=$delete_ajax_url?>" + id,
			method:"POST",
			data:dataSubmit,
			success:function(resp){
				if (resp.message != ""){
					alert(resp.message);
				}

				if (resp.status == "SUCCESS"){
					//t.row(trRow).remove().draw(false); //refresh ajax
					trRow.remove(); //no refresh ajax
				}
			}
		})
	}
</script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<!--
<script src="<?=base_url()?>bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net/js/datetime.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
-->
