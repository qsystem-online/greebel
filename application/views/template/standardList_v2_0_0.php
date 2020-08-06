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
                    <?php
                        foreach($boxTools as $boxTool){
                            echo $boxTool;
                        }                        
                    ?>					
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

<?php echo isset($mdlEditForm) ? $mdlEditForm : "" ?>

<script type="text/javascript">
	$(function(){	
     
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
                            <?php if(isset($col['sortable'])){
                                if ($col['sortable']){ ?>
                                    ,"orderable": true
                                <?php }else
                                {?>
                                    ,"orderable": false
                                <?php }
                                
                            } ?>
                            <?php if(isset($col['className'])){?>
                                ,"className":"<?=$col['className']?>"
                            <?php } ?>
							<?php if(isset($col['visible'])){?>
                                ,"visible":<?=$col['visible']?>
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
			onDrawTable();

		});
    });
    
</script>

<?php 
	echo $jsfile;	
?>

<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<!--
<script src="<?=base_url()?>bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net/js/datetime.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
-->
