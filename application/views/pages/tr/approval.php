<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<section class="content-header">
	<h1><?=lang("Approval")?><small><?=lang("form")?></small></h1>
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
                            <div style="margin-bottom:5px;margin-top:20px">
                                <div style="float:left">
                                    <label class="control-label">Date Range :</label>
                                    <input id="daterange_needapproval" type="TEXT" class="daterangepicker form-control" style="position:static;display:inline" value='' />
                                </div>
                                <div style="float:right">
                                    <label class="control-label">Search by :</label>
                                    <select id="selectSearch" class="form-control"  style="display:inline;width:148px">
                                        <option value='fst_transaction_no'>Transaction No</option>
                                        <option value='fst_message'>Message</option>
                                        <option value='fst_user_name'>Request by</option>
                                    </select>
                                </div>
                                <div style="clear:both"></div>
                            </div>
                        
                            
                            <table id="tblNeedApproval" style="width:100%"></table>                            
                            
                        </div> <!-- /.tab-pane -->            
                        <div class="tab-pane" id="tab_2">
                            <div style="margin-bottom:5px;margin-top:20px">
                                <div style="float:left">
                                    <label class="control-label">Date Range :</label>
                                    <input id="daterange_historyapproval" type="TEXT" class="daterangepicker form-control" style="position:static;display:inline" value='' />
                                </div>
                                <div style="float:right">
                                    <label class="control-label">Search by :</label>
                                    <select id="selectSearch" class="form-control"  style="display:inline;width:148px">
                                        <option value='fst_transaction_no'>Transaction No</option>
                                        <option value='fst_message'>Message</option>
                                        <option value='fst_user_name'>Request by</option>
                                    </select>
                                </div>
                                <div style="clear:both"></div>
                            </div>
                            <table id="tblHistApproval" style="width:100%"></table>
                        </div><!-- /.tab-pane -->
                                            
                    </div> <!-- /.tab-content -->                    
                </div>
            </div>
            <!-- end box header -->
        </div>
    </div>
</section>



<!-- modal atau popup "ADD" -->
<div id="mdlapproval" class="modal fade" role="dialog" >
	<div class="modal-dialog" style="display:table;width:800px">
		<!-- modal content -->
		<div class="modal-content" style="border-top-left-radius:5px;border-top-right-radius:5px;border-bottom-left-radius:5px;border-bottom-right-radius:5px;">
			<div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 5px;border-top-right-radius: 5px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Approval Notes")?></h4>
			</div>

			<div class="modal-body">
				<div class="row">
                    <div class="col-md-12" >				
                        <form id="form-approval" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input type="hidden" id="fin_rec_id"/>
                                    <textarea id="fst_notes" name="fst_notes" style="width:100%;height:150px"></textarea>
                                </div>
                            </div>
                        </form>
					</div>
				</div>
            </div> <!-- END MODAL BODY -->
            <div class="modal-footer">
                <button id="btn-do-approve" type="button" class="btn btn-primary btn-sm text-center" ><?=lang("Approve")?></button>
                <button id="btn-do-reject" type="button" class="btn btn-primary btn-sm text-center" ><?=lang("Rejected")?></button>
                <button type="button" class="btn btn-primary btn-sm text-center" data-dismiss="modal"><?=lang("Close")?></button>
            </div>
		</div>
	</div>
</div>


<script type="text/javascript">
    var selectedRecord;

    $(function(){
        reloadNeedApproval();
        $('.nav-tabs a').on('shown.bs.tab', function(event){            
            var x = $(event.target).text();         // active tab
            var y = $(event.relatedTarget).text();  // previous tab
            if (x  == "Need Approval"){
                reloadNeedApproval();
            }

            if (x  == "Histories"){
                reloadHistories();
            }
            
        });
        $("#btn-do-approve").click(function(e){
            e.preventDefault();
            doApproval(1);
        })
        $("#btn-do-reject").click(function(e){
            e.preventDefault();
            doApproval(0);
        })

        $("#daterange_needapproval").on('daterangepicker.change',function(e){
            e.preventDefault();
            console.log("daterangepicker change");
        })
        
    });

    function reloadNeedApproval(){
        if ( $.fn.DataTable.isDataTable( '#tblNeedApproval' ) ) {
            $('#tblNeedApproval').DataTable().clear().destroy();
        }

        $("#tblNeedApproval").DataTable({
            ajax: {
                url:"<?=site_url()?>tr/approval/fetch_need_approval_list",
            },
			columns:[
				{"title" : "id","width": "10%",sortable:true,data:"fin_rec_id",visible:true},
				{"title" : "Module","width": "10%",sortable:false,data:"fst_controller",visible:true},				
                {"title" : "Transaction no","width": "10%",sortable:false,data:"fst_transaction_no"},
                {"title" : "Message","width": "40%",sortable:false,data:"fst_message",visible:true},
                {"title" : "Insert time","width": "10%",sortable:false,data:"fdt_insert_datetime",visible:true},
                {"title" : "Request By","width": "10%",sortable:false,data:"fst_user_name",visible:true},
                {"title" : "Action","width": "10%",sortable:false,className:'dt-body-center text-center',
                    render: function(data,type,row){
                        action = "<a class='btn-approve' href='#'><i style='font-size:14pt;margin-right:10px' class='fa fa-check-circle-o'></i></a>";
                        action += "<a class='btn-view' href='#'><i style='font-size:14pt;color:lime' class='fa fa-bars'></i></a>";                        
                        return action;                        
                    }
                },
            ],
            dataSrc:"data",
			processing: true,
			serverSide: true,
        }).on('preXhr.dt', function ( e, settings, data ) {
            data.dateRange = $('#daterange_needapproval').val();
            data.optionSearch = $('#selectSearch').val();
		});
        
        $("#tblNeedApproval").on("click",".btn-approve",function(e){
            e.preventDefault();
            t = $('#tblNeedApproval').DataTable();
            var trRow = $(this).parents('tr');
            selectedRecord = trRow;            
            data = t.row(trRow).data();
            $("#fin_rec_id").val(data.fin_rec_id);

            $("#mdlapproval").modal("show");

            /*
            $(this).confirmation({
                title:"Approve ?",
                rootSelector: '.btn-approve',
                onConfirm:function(){
                    //console.log($(this));                      
                    doApproval($(this));
                }
			});
            $(this).confirmation("show");
            */
        });
      
        $("#tblNeedApproval").on("click",".btn-view",function(e){    
            showTransaction($(this));
        });


    }
    
    function reloadHistories(){
        
        if ( $.fn.DataTable.isDataTable( '#tblHistApproval' ) ) {
            $('#tblHistApproval').DataTable().clear().destroy();
        }

        $("#tblHistApproval").DataTable({
            ajax: {
                url:"<?=site_url()?>tr/approval/fetch_hist_approval_list",
            },
			columns:[
				{"title" : "id","width": "10%",sortable:true,data:"fin_rec_id",visible:true},
				{"title" : "Module","width": "10%",sortable:false,data:"fst_controller",visible:true},				
                {"title" : "Transaction no","width": "10%",sortable:false,data:"fst_transaction_no"},
                {"title" : "Message","width": "40%",sortable:false,data:"fst_message",visible:true},
                {"title" : "Insert time","width": "10%",sortable:false,data:"fdt_insert_datetime",visible:true},
                {"title" : "Request By","width": "10%",sortable:false,data:"fst_user_name",visible:true},
                {"title" : "Action","width": "10%",sortable:false,className:'dt-body-center text-center',
                    render: function(data,type,row){
                        action = "<a class='btn-view' href='#'><i style='font-size:14pt;color:lime' class='fa fa-bars'></i></a>";                        
                        return action;                        
                    }
                },
            ],
            dataSrc:"data",
			processing: true,
			serverSide: true,
        }).on('preXhr.dt', function ( e, settings, data ) {
            data.dateRange = $('#daterange_historyapproval').val();
            data.optionSearch = $('#selectSearch').val();
		});
      
        $("#tblHistApproval").on("click",".btn-view",function(e){    
            showTransaction($(this));
        });

    }

    function doApproval(isApproved){
        
        data = {
            <?=$this->security->get_csrf_token_name()?> : "<?=$this->security->get_csrf_hash()?>",
            fin_rec_id: $("#fin_rec_id").val(),
            fst_notes :$("#fst_notes").val(),
            isApproved :isApproved,
        };

        $.ajax({
            url:"<?= site_url() ?>tr/approval/doApproval/" + $("#fin_rec_id").val(),
            data:data,
            method:"POST"

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
                selectedRecord.remove();
                //trRow.remove();
            }
        });
    }

    function showTransaction(element){
        //alert("Show");
        t = $('#tblNeedApproval').DataTable();
        var trRow = element.parents('tr');
        data = t.row(trRow).data(); 

        url = "<?= site_url() ?>tr/approval/viewDetail/" + data.fin_rec_id;
        window.open(url);
    }



</script>
<!-- DataTables -->
<script src="<?=base_url()?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?=base_url()?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>