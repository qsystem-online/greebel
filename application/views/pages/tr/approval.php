<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<section class="content-header">
	<h1><?=lang("Sales Order")?><small><?=lang("form")?></small></h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
		<li><a href="#"><?= lang("Tools") ?></a></li>
		<li class="active title"><?=$title?></li>
	</ol>
</section>


<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
				<div class="box-header with-border">
				    <h3 class="box-title title"><?=$title?></h3>
			    </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false"><label>Need Approval</label></a></li>
                        <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><label>Histories</label></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <table id="tblNeedApproval" style="width:100%"></table>
                            <a class="btn-test" href="#">TEST</a>
                        </div> <!-- /.tab-pane -->            
                        <div class="tab-pane" id="tab_2">
                            <table id="tblHistApproval"></table>
                        </div><!-- /.tab-pane -->
                                            
                    </div> <!-- /.tab-content -->                    
                </div>
            </div>
            <!-- end box header -->
        </div>
    </div>
</section>
<script type="text/javascript">
    $(function(){
        $("#tblNeedApproval").DataTable({
            ajax: {
                url:"<?=site_url()?>tr/approval/fetch_need_approval_list",
            },
			columns:[
				{"title" : "id","width": "10%",sortable:true,data:"fin_rec_id",visible:true},
				{"title" : "Module","width": "10%",sortable:false,data:"fst_controller",visible:true},
				{"title" : "Transaction #","width": "10%",sortable:false,data:"fin_transaction_id",
					render: function(data,type,row){
                        //return row.ItemCode + "-" + row.fst_custom_item_name;
                        return data;
					}
				},
                {"title" : "Message","width": "40%",sortable:false,data:"fst_message",visible:true},
                {"title" : "Insert time","width": "20%",sortable:false,data:"fdt_insert_datetime",visible:true},
                {"title" : "Approve","width": "10%",sortable:false,className:'dt-body-center text-center',
                    render: function(data,type,row){
                        return "<a class='btn-approve' href='#'><i class='fa fa-check-circle-o'></i></a>";
                        
                    }
                },
            ],
            dataSrc:"data",
			processing: true,
			serverSide: true,
        });
        
        $("#tblNeedApproval").on("click",".btn-approve",function(e){
            e.preventDefault();
            $(this).confirmation({
                title:"Approve ?",
                rootSelector: '.btn-approve',
                onConfirm:function(){
                    //console.log($(this));
                    
                                             
                    doApproval($(this));
                }
			});
            $(this).confirmation("show");            
        });
        
    });

    function doApproval(element){
        
        t = $('#tblNeedApproval').DataTable();
        var trRow = element.parents('tr');
        data = t.row(trRow).data(); 

        $.ajax({
            url:"<?= site_url() ?>tr/approval/doApproval/" + data.fin_rec_id,
        }).done(function(resp){
            if (resp.message != "")	{
                $.alert({
                    title: 'Message',
                    content: resp.message,
                    buttons : {
                        OK : function(){
                            if(resp.status == "SUCCESS"){
                                //window.location.href = "<?= site_url() ?>tr/sales_order/lizt";
                                return;
                            }
                        },
                    }
                });
            }
            if(resp.status == "SUCCESS") {
                //remove row
                trRow.remove();
            }
        });
    }
</script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>