<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?= base_url() ?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?= base_url() ?>bower_components/datatables.net/datatables.min.css">
<link rel="stylesheet" href="<?= base_url() ?>bower_components/datatables.net/dataTables.checkboxes.css">

<section class="content-header">
    <h1><?= lang("Menus") ?><small><?= lang("form") ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
        <li><a href="#"><?= lang("Menus") ?></a></li>
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

                <!-- form start -->
                <form id="frmWarehouse" class="form-horizontal" action="<?= site_url() ?>master/warehouse/add" method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" id="frm-mode" value="<?= $mode ?>">

                        <div class='form-group'>
                            <label for="fin_warehouse_id" class="col-sm-2 control-label"><?= lang("Warehouse ID") ?> :</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="fin_warehouse_id" placeholder="<?= lang("(Autonumber)") ?>" name="fin_warehouse_id" value="<?= $fin_warehouse_id ?>" readonly>
                                <div id="fin_warehouse_id_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fst_warehouse_name" class="col-sm-2 control-label"><?= lang("Warehouse Name") ?> :</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="fst_warehouse_name" placeholder="<?= lang("Warehouse Name") ?>" name="fst_warehouse_name">
                                <div id="fst_warehouse_name_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select-Branch" class="col-md-2 control-label"><?= lang("Branch") ?> :</label>
                            <div class="col-md-4">
                                <select id="select-Branch" class="form-control" name="fin_branch_id"></select>
                                <div id="fin_branch_id_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fbl_is_external" class="col-sm-2 control-label"><?= lang("External") ?> :</label>
                            <div class="checkbox col-sm-2">
                                <label><input id="fbl_is_external" type="checkbox" name="fbl_is_external" value="1"><?= lang("External") ?></label><br>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fbl_is_main" class="col-sm-2 control-label"><?= lang("Main") ?> :</label>
                            <div class="checkbox col-sm-2">
                                <label><input id="fbl_is_main" type="checkbox" name="fbl_is_main" value="1"><?= lang("Main Warehouse") ?></label><br>
                                <div id="fbl_is_main_err" class="text-danger" style="padding-left:200px"></div>
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

<script type="text/javascript">
    $(function() {

        <?php if ($mode == "EDIT") { ?>
            init_form($("#fin_warehouse_id").val());
        <?php } ?>

        $("#btnSubmitAjax").click(function(event) {
            event.preventDefault();
            //data = new FormData($("#frmWarehouse")[0]);
            data = $("#frmWarehouse").serializeArray();

            mode = $("#frm-mode").val();
            if (mode == "ADD") {
                url = "<?= site_url() ?>master/warehouse/ajx_add_save";
            } else {
                url = "<?= site_url() ?>master/warehouse/ajx_edit_save";
            }
            console.log(data);

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
                                        //location.reload();
                                        window.location.href = "<?= site_url() ?>master/warehouse/lizt";
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
                        $("#fin_warehouse_id").val(data.insert_id);

                        //Clear all previous error
                        $(".text-danger").html("");

                        // Change to Edit mode
                        $("#frm-mode").val("EDIT"); //ADD|EDIT

                    }
                },
                error: function(e) {
                    $("#result").text(e.responseText);
                    console.log("ERROR : ", e);
                    $("#btnSubmit").prop("disabled", false);
                }
            });
        });


        $("#select-Branch").select2({
            width: '100%',
            ajax: {
                url: '<?= site_url() ?>master/warehouse/get_Branch',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    data2 = [];
                    $.each(data, function(index, value) {
                        data2.push({
                            "id": value.fin_branch_id,
                            "text": value.fst_branch_name
                        });
                    });
                    console.log(data2);
                    return {
                        results: data2
                    };
                },
                cache: true,
            }
        });

    });

    function init_form(fin_warehouse_id) {
        //alert("Init Form");
        var url = "<?= site_url() ?>master/warehouse/fetch_data/" + fin_warehouse_id;
        $.ajax({
            type: "GET",
            url: url,
            success: function(resp) {
                console.log(resp.warehouse);

                $.each(resp.warehouse, function(name, val) {
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

                // menampilkan data di select2
                var newOption = new Option(resp.warehouse.fst_branch_name, resp.warehouse.fin_branch_id, true, true);
                // Append it to the select
                $('#select-Branch').append(newOption).trigger('change');
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