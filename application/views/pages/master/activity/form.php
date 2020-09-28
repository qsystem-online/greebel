<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?= base_url() ?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?= base_url() ?>bower_components/datatables.net/datatables.min.css">
<link rel="stylesheet" href="<?= base_url() ?>bower_components/datatables.net/dataTables.checkboxes.css">

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
    <h1><?= lang("Activity") ?><small><?= lang("form") ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
        <li><a href="#"><?= lang("Activity") ?></a></li>
        <li class="active title"><?= $title ?></li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title title"><?= $title ?></h3>
                    <div class="btn-group btn-group-sm  pull-right">					
						<a id="btnNew" class="btn btn-primary" href="#" title="<?=lang("Tambah Baru")?>"><i class="fa fa-plus" aria-hidden="true"></i></a>
						<a id="btnSubmitAjax" class="btn btn-primary" href="#" title="<?=lang("Simpan")?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
						<a id="btnPrint" class="btn btn-primary" href="#" title="<?=lang("Cetak")?>"><i class="fa fa-print" aria-hidden="true"></i></a>
						<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
						<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
					</div>
                </div>
                <!-- end box header -->

                <!-- form start fbl_is_multiples_prize -->
                <form id="frmActivity" class="form-horizontal" action="<?= site_url() ?>master/activity/add" method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" id="frm-mode" value="<?= $mode ?>">

                        <div class='form-group'>
                            <label for="fin_activity_id" class="col-md-3 control-label"><?= lang("Activity ID") ?> :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="fin_activity_id" placeholder="<?= lang("(Autonumber)") ?>" name="fin_activity_id" value="<?= $fin_activity_id ?>" readonly>
                                <div id="fin_activity_id_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class='form-group'>
                            <label for="fst_name" class="col-md-3 control-label"><?= lang("Activity Name") ?> :</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="fst_name" placeholder="<?= lang("Activity Name") ?>" name="fst_name">
                                <div id="fst_name_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fst_team" class="col-md-3 control-label"><?= lang("Team") ?> :</label>
                            <div class="col-md-2">
                                <select id="fst_team" class="form-control" name="fst_team">
                                    <option value='PERSON'><?= lang("PERSON") ?></option>
                                    <option value='TEAM'><?= lang("TEAM") ?></option>
                                </select>
                            </div>
                            <label for="fst_type" class="col-md-2 control-label"><?= lang("Type") ?> :</label>
                            <div class="col-md-3">
                                <select id="fst_type" class="form-control" name="fst_type">
                                    <option value='BORONGAN'><?= lang("BORONGAN") ?></option>
                                    <option value='HARIAN'><?= lang("HARIAN") ?></option>
                                </select>
                                <div id="fst_type_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group cost_per_day" style="display:none">
                            <label for="fdc_cost_per_day" class="col-md-3 control-label"><?= lang("Cost/Day") ?> :</label>
                            <div class="col-md-2">
                                <input type="text" class="form-control money" id="fdc_cost_per_day" placeholder="<?= lang("0") ?>" name="fdc_cost_per_day">
                                <div id="fdc_cost_per_day_err" class="text-danger"></div>
                            </div>
                        </div>
                        <!-- end box body -->
                        <div class="nav-tabs-custom" style="display:unset">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#activity_borongan_detail" data-toggle="tab" aria-expanded="true"><?= lang("Borongan Detail") ?></a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="activity_borongan_detail">
                                    <form class="form-horizontal edit-mode ">	
                                        <div class="form-group">
                                            <div class="col-md-12">
                                            <button id="btn-add-borongan-detail" class="btn btn-primary btn-sm pull-right edit-mode"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;<?= lang("Add Detail") ?></button>
                                            </div>						
                                        </div>
                                    </form>
                                    <table id="tbl_borongan_detail" class="table table-bordered table-hover" style="width:100%;"></table>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->

                        <div class="box-footer text-right">
                            
                        </div>
                        <!-- end box-footer -->
                </form>
            </div>
        </div>
</section>

<div id="mdlBoronganDetail" class="modal fade in" role="dialog" style="display: none">
    <div class="modal-dialog" style="display:table;width:40%;min-width:400px;max-width:100%">
        <!-- Modal content-->
        <div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
            <div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?= lang("Add Borongan Detail") ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" >
                        <div style="border:1px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">

                                <form class="form-horizontal ">
                                    <div class="form-group">
                                        <label for="fdb_qty_start" class="col-md-3 control-label"><?=lang("Start Qty")?></label>
                                        <div class="col-md-9">
                                            <input type="number" class="form-control text-right numeric" id="fdb_qty_start" value="1">
                                            <div id="fdb_qty_start_err" class="text-danger"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="fdb_qty_end" class="col-md-3 control-label"><?=lang("End Qty")?></label>
                                        <div class="col-md-9">
                                            <input type="number" class="form-control text-right numeric" id="fdb_qty_end" value="1">
                                            <div id="fdb_qty_end_err" class="text-danger"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="fdc_cost" class="col-md-3 control-label"><?=lang("Cost Value")?></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control text-right money" id="fdc_cost" value="0">
                                            <div id="fdc_cost_err" class="text-danger"></div>
                                        </div>
                                    </div>
                                </form>

                                <div class="modal-footer" style="width:100%;padding:10px" class="text-center">
                                    <button id="btn-add-detail" type="button" class="btn btn-primary btn-sm text-center" style="width:15%" ><?=lang("Add")?></button>
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
        var action = '<a class="btn-edit" href="#" data-toggle="" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp; <a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
        $(function() {
            $("#btn-add-borongan-detail").click(function(event) {
                event.preventDefault();
                var type = $("#fst_type").val();
                if (type == "HARIAN") {
                    $("#fst_type_err").html("Detail just for 'BORONGAN'");
                    $("#fst_type_err").show();
                } else {
                    $("#fst_type_err").hide();
                    $("#mdlBoronganDetail").modal('show');
                }
            });
            $("#tbl_borongan_detail").DataTable({
                searching: false,
                paging: false,
                info: false,
                columns: [/*{
                        "title": "<?= lang("ID ") ?>",
                        "width": "5%",
                        data: "fin_id",
                        visible: false
                    },*/
                    {
                        "title": "<?= lang("Start Qty ") ?>",
                        "width": "10%",
                        data: "fdb_qty_start",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("End Qty ") ?>",
                        "width": "10%",
                        data: "fdb_qty_end",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Cost ") ?>",
                        "width": "10%",
                        data: "fdc_cost",
                        render: $.fn.dataTable.render.number(',', '.', 2),
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Action ") ?>",
                        "width": "5%",
                        render: function(data, type, row) {
                            action = "<a class='btn-delete-borongan-detail edit-mode' href='#'><i class='fa fa-trash'></i></a>&nbsp;";
                            return action;
                        },
                        "sortable": false,
                        "className": "dt-body-center text-center"
                    }
                ],
            });
            $("#tbl_borongan_detail").on("click", ".btn-delete-borongan-detail", function(event) {
                event.preventDefault();
                t = $("#tbl_borongan_detail").DataTable();
                var trRow = $(this).parents('tr');
                t.row(trRow).remove().draw();
            });
            $("#btn-add-detail").click(function(event) {
                event.preventDefault();
                t = $('#tbl_borongan_detail').DataTable();
                addRow = true;
                var qtyStart = $("#fdb_qty_start").val();
                if (qtyStart == null || qtyStart == "" || qtyStart == "0") {
                    $("#fdb_qty_start_err").html("Qty Start > 0");
                    $("#fdb_qty_start_err").show();
                    addRow = false;
                    return;
                } else {
                    $("#fdb_qty_start_err").hide();
                }
                var qtyEnd = $("#fdb_qty_end").val();              
                if (qtyEnd == null || qtyEnd == "" || qtyEnd == "0") {
                    $("#fdb_qty_end_err").html("Qty End > 0");
                    $("#fdb_qty_end_err").show();
                    addRow = false;
                    return;
                } else {
                    $("#fdb_qty_end_err").hide();
                }
                var cost = $("#fdc_cost").val();
                if (cost == null || cost == "") {
                    $("#fdc_cost_err").html("Qty End >= 0");
                    $("#fdc_cost_err").show();
                    addRow = false;
                    return;
                } else {
                    $("#fdc_cost_err").hide();
                }
                t.row.add({
                    fin_id: 0,
                    fin_activity_id: 0,
                    fdb_qty_start: $("#fdb_qty_start").val(),
                    fdb_qty_end: $("#fdb_qty_end").val(),
                    fdc_cost: $("#fdc_cost").val(),
                    action: action
                }).draw(false);
            });
        });
    </script>
</div>

<script type="text/javascript">
    $(function() {
        <?php if ($mode == "EDIT") { ?>
            init_form($("#fin_activity_id").val());
        <?php } ?>
        $("#btnSubmitAjax").click(function(event) {
            event.preventDefault();
            data = $("#frmActivity").serializeArray();
            //data = new FormData($("#frmMSItems")[0]);
            detail = new Array();
            t = $('#tbl_borongan_detail').DataTable();
            datas = t.data();
            $.each(datas, function(i, v) {
                detail.push(v);
            });
            data.push({
                name: "borongandetail",
                value: JSON.stringify(detail)
            });
            mode = $("#frm-mode").val();
            if (mode == "ADD") {
                url = "<?= site_url() ?>master/activity/ajx_add_save";
            } else {
                url = "<?= site_url() ?>master/activity/ajx_edit_save";
            }
            console.log(data);

            App.blockUIOnAjaxRequest("Please wait while saving data.....");
            $.ajax({
                type: "POST",
                //enctype: 'multipart/form-data',
                url: url,
                data: data,
                //processData: false,
                //contentType: false,
                //cache: false,
                timeout: 600000,
                success: function(resp) {
                    if (resp.message != "") {
                        $.alert({
                            title: 'Message',
                            content: resp.message,
                            buttons: {
                                OK: function() {
                                    if (resp.status == "SUCCESS") {
                                        $("#btnNew").trigger("click");
                                        return;
                                    }
                                },
                            }
                        });
                    }
                    if (resp.status == "VALIDATION_FORM_FAILED") {
                        //Show Error
                        errors = resp.data;
                        for (key in errors) {
                            $("#" + key + "_err").html(errors[key]);
                        }
                    } else if (resp.status == "SUCCESS") {
                        data = resp.data;
                        $("#fin_activity_id").val(data.insert_id);
                        //Clear all previous error
                        $(".text-danger").html("");
                        // Change to Edit mode
                        $("#frm-mode").val("EDIT"); //ADD|EDIT
                        $('#fst_name').prop('readonly', true);
                    }
                },
                error: function(e) {
                    $("#result").text(e.responseText);
                    console.log("ERROR : ", e);
                    $("#btnSubmit").prop("disabled", false);
                }
            });
        });

        $("#fst_type").change(function(){
            type = $(this).val();
            if(type == "BORONGAN"){
                $(".cost_per_day").hide();
            }else if(type == "HARIAN"){
                $(".cost_per_day").show();
            }
        });

        $("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>master/activity/add")
		});

		$("#btnDelete").confirmation({
			title:"<?=lang("Hapus data ini ?")?>",
			rootSelector: '#btnDelete',
			placement: 'left',
		});
		$("#btnDelete").click(function(e){
			e.preventDefault();
			blockUIOnAjaxRequest("<h5>Deleting ....</h5>");
			$.ajax({
				url:"<?= site_url() ?>master/activity/delete/" + $("#fin_activity_id").val(),
			}).done(function(resp){
				//consoleLog(resp);
				$.unblockUI();
				if (resp.message != "")	{
					$.alert({
						title: 'Message',
						content: resp.message,
						buttons : {
							OK : function() {
								if (resp.status == "SUCCESS") {
									window.location.href = "<?= site_url() ?>master/activity/lizt";
									return;
								}
							},
						}
					});
				}

				if(resp.status == "SUCCESS") {
					data = resp.data;
					$("#fin_activity_id").val(data.insert_id);

					//Clear all previous error
					$(".text-danger").html("");
					// Change to Edit mode
					$("#frm-mode").val("EDIT");  //ADD|EDIT
					$('#fst_name').prop('readonly', true);
				}
			});
		});

        $("#btnPrint").click(function(e){
            //e.preventDefault();
			//window.open("<?= site_url() ?>master/promotion/form_promotion_pdf/" + $("#fin_activity_id").val());
            frameVoucher.print("<?=site_url()?>master/activity/print_voucher/" + $("#fin_activity_id").val());
        });

		$("#btnList").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>master/activity/lizt");
		});
    });


    function init_form(fin_activity_id) {
        //alert("Init Form");
        var url = "<?= site_url() ?>master/activity/fetch_data/" + fin_activity_id;
        $.ajax({
            type: "GET",
            url: url,
            success: function(resp) {
                $.each(resp.msactivity, function(name, val) {
                    var $el = $('[name="' + name + '"]'),
                        type = $el.attr('type');
                    switch (type) {
                        case 'checkbox':
                            $el.filter('[value="' + val + '"]').attr('checked', 'checked');
                            break;
                        case 'radio':
                            $el.filter('[value="' + val + '"]').attr('checked', 'checked');
                            break;
                        default:
                            $el.val(val);
                            console.log(val);
                    }
                });

                if (resp.msactivity.fst_type == "BORONGAN"){
					$(".cost_per_day").hide();
				}else{
					$(".cost_per_day").show();
				}

                $.each(resp.borongandetail, function(name, val) {
                    console.log(val);
                    //event.preventDefault();
                    t = $('#tbl_borongan_detail').DataTable();
                        t.row.add({
                        fin_rec_id: val.fin_rec_id,
                        fin_activity_id: val.fin_activity_id,
                        fdb_qty_start: val.fdb_qty_start,
                        fdb_qty_end: val.fdb_qty_end,
                        fdc_cost: val.fdc_cost,
                        action: action
                    }).draw(false);
                })
            },
            error: function(e) {
                $("#result").text(e.responseText);
                console.log("ERROR : ", e);
            }
        });
    }
</script>


<!-- Select2 -->
<script src="<?= base_url() ?>bower_components/select2/dist/js/select2.full.js"></script>
<!-- DataTables -->
<script src="<?= base_url() ?>bower_components/datatables.net/datatables.min.js"></script>
<script src="<?= base_url() ?>bower_components/datatables.net/dataTables.checkboxes.min.js"></script>