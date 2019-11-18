<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?=base_url()?>bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url()?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<style type="text/css">
	.border-0{
		border: 0px;
	}
	td{
		padding: 2px; !important 		
	}
    .nav-tabs-custom>.nav-tabs>li.active>a{
        font-weight:bold;
        border-left-color: #3c8dbc;
        border-right-color: #3c8dbc;
        border-style:fixed;
    }
    .nav-tabs-custom>.nav-tabs{
        border-bottom-color: #3c8dbc;        
        border-bottom-style:fixed;
    }
	.is-promo{
		color:#ff0000;
		background-color:#ed8fa9 !important;
	}
</style>

<section class="content-header">
	<h1><?=lang("Delivery Order")?><small><?=lang("form")?></small></h1>
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
                    <h3 class="box-title title"><?= $title ?></h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <!--<form id="frmPenjualan" class="form-horizontal"> -->
                <form id="frmDeliveryOrder" class="form-horizontal" action="<?= site_url() ?>tr/delivery_order/add" method="POST" enctype="multipart/form-data">

                    <div class="box-body">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" id="fin_sj_id" name="fin_sj_id" value="<?= $fin_sj_id ?>">
                        <input type="hidden" id="frm-mode" value="<?= $mode ?>">

                        <div class="form-group">
                            <label for="fin_sj_id" class="col-md-2 control-label"><?=lang("Delivery Order ID")?> :</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="fin_sj_id" placeholder="(Autonumber)" name="fin_sj_id" value="<?= set_value("fin_sj_id") ?>" readonly>
                                <div id="fin_sj_id_err" class="text-danger"></div>
                            </div>

                            <div class="form-group">
                                <label for="fst_sj_no" class="col-md-2 control-label"><?=lang("Delivery Order No")?> :</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" id="fst_sj_no" placeholder="<?=lang("Delivery Order No")?>" name="fst_sj_no" value="<?=$fst_sj_no?>" readonly>
                                        <div id="fst_sj_no_err" class="text-danger"></div>
                                    </div>
                            </div>

                            <label for="fdt_sj_date" class="col-md-4 control-label">Transaction Date </label>
                            <div class="col-md-2">
                                <input type="text" class="form-control datepicker" id="fdt_sj_date" placeholder="Date" name="fdt_sj_date" value="<?= set_value("fdt_sj_date") ?>">
                                <div id="fdt_sj_date_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="select-so" class="col-md-2 control-label">No.SO</label>
                            <div class="col-md-4">
                                <select id="select-so" class="form-control" name="fst_so_id" data-placeholder="Select SO No...."></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select-customer" class="col-md-2 control-label">Customer</label>
                            <div class="col-md-4">
                                <select id="select-customer" class="form-control"></select>
                                <div id="fst_customer_name_err" class="text-danger"></div>
                            </div>
                        </div>
                        <table id="tblDetailDO" class="table table-bordered table-hover table-striped"></table>

                        <div class="form-group">
                            <label for="sub-total" class="col-md-10 control-label">Sub total </label>
                            <div class="col-md-2" style='text-align:right'>
                                <input type="text" class="form-control text-right" id="sub-total" value="0" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-md-9 control-label">Disc (%) </label>
                            <div class="col-md-1" style='text-align:right'>
                                <input type="text" class="form-control text-right" id="fdc_disc" name="fdc_disc" style="padding:5px" value=0>
                            </div>
                            <div class="col-md-2" style='text-align:right'>
                                <input type="text" class="form-control text-right" id="disc-val" value="0" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="total" class="col-md-10 control-label">Total</label>
                            <div class="col-md-2" style='text-align:right'>
                                <input type="text" class="form-control text-right" id="total" value="0" readonly>
                            </div>
                        </div>


                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer text-right">
                        <a id="btnSubmitAjax" href="#" class="btn btn-primary"><?=lang("Save Ajax")?></a>
                    </div>
                    <!-- /.box-footer -->
                </form>

            </div>
        </div>
    </div>


    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="display:table">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Product</h4>
                </div>

                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label for="select-product" class="col-md-2 control-label">Product</label>
                            <div class="col-md-10">
                                <select id="select-product" class="form-control"></select>
                                <div id="fst_customer_name_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fst_customer_name" class="col-md-2 control-label">Qty</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control numeric" id="product-qty" value="1">
                                <div id="fst_customer_name_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fst_customer_name" class="col-md-2 control-label">Harga</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control text-right money" id="product-harga" value="0">
                                <div id="fst_customer_name_err" class="text-danger"></div>
                            </div>
                        </div>

                    </form>

                </div>
                <div class="modal-footer">
                    <button id="btn-add-product-detail" type="button" class="btn btn-primary">Add</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var action = '<a class="btn-edit" href="#" data-toggle="" data-original-title="" title=""><i class="fa fa-pencil"></i></a>&nbsp; <a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
        $(function() {
            <?php if ($mode == "EDIT") { ?>
                init_form($("#fin_sj_id").val());
            <?php
        } ?>;
            $("#select-customer").select2({
                width: '100%',
                //minimumInputLength: 2,
                minimumResultsForSearch: Infinity,
                ajax: {
                    url: '<?= site_url() ?>sample/salesorder/get_data_customer',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        data2 = [];
                        $.each(data, function(index, value) {
                            data2.push({
                                "id": value.fin_sj_id,
                                "text": value.fst_customername
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

            $("#select-so").select2({
                width: '100%',
                minimumResultsForSearch: Infinity,
                ajax: {
                    url: '<?= site_url() ?>sample/penjualan/get_datalist_so',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        data2 = [];
                        $.each(data, function(index, value) {
                            data2.push({
                                "id": value.fin_sj_id,
                                "memo": value.fst_memo,
                                "text": value.fin_sj_id
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

            var selected_so;
            //var SODetail;

            $("#select-so").on('select2:select', function(e, id) {
                console.log(selected_so);
                var data = e.params.data;
                selected_so = data;
                console.log(selected_so);
                $("#fst_customer_name").val(data.memo);

                var url = "<?= site_url() ?>/sample/salesorder/fetch_data/" + data.id;
                var action = '<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(resp) {
                        console.log(resp.SODetail);
                        //alert(resp.SODetail);


                        //populate SODetail
                        $.each(resp.SODetail, function(name, val) {
                            console.log(val);
                            //event.preventDefault();
                            t = $('#tblDetailPenjualan').DataTable();

                            t.row.add({
                                fin_sj_id: 0,
                                id_product: val.id_product,
                                product_name: val.title,
                                fin_qty: val.fin_qty,
                                fdc_harga: val.fdc_harga,
                                total: val.fin_qty * val.fdc_harga,
                                action: action
                            }).draw(false);

                            calculateTotal();
                            //add_address(val.fin_sj_id, val.fst_name, val.fst_address, isPrimary);
                        })


                    },

                    error: function(e) {
                        $("#result").text(e.responseText);
                        console.log("ERROR : ", e);
                    }
                });
            });

            $("#btnSubmitAjax").click(function(event) {
                event.preventDefault();
                //data = new FormData($("#frmPenjualan")[0]);
                data = $("#frmPenjualan").serializeArray();
                detail = new Array();

                t = $('#tblDetailPenjualan').DataTable();
                datas = t.data();
                $.each(datas, function(i, v) {
                    detail.push(v);
                });

                data.push({
                    name: "detail",
                    value: JSON.stringify(detail)
                });

                //console.log(data);
                //return;


                mode = $("#frm-mode").val();
                if (mode == "ADD") {
                    url = "<?= site_url() ?>sample/penjualan/ajx_add_save";
                } else {
                    url = "<?= site_url() ?>sample/penjualan/ajx_edit_save";
                }

                App.blockUIOnAjaxRequest("Please wait while saving data.....");
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    timeout: 600000,
                    success: function(resp) {
                        if (resp.message != "") {
                            $.alert({
                                title: 'Message',
                                content: resp.message,
                                onDestroy: function() {
                                    //alert('the user clicked yes');
                                    window.location.href = "<?= site_url() ?>sample/penjualan/add";
                                    return;
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
                            //redirect to list

                            data = resp.data;
                            $("#fin_sj_id").val(data.insert_id);
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

            $(".datepicker").datepicker({
                //var d = new Date();
                //var currMonth = d.getMonth();
                //var currYear = d.getFullYear();
                //var startDate = new Date(currYear, currMonth, 1);

                //$("#datepicker").datepicker();
                //$("#datepicker").datepicker("setDate", startDate);
                format: "yyyy-mm-dd"
            });

            $("#select-product").select2({
                width: '100%',
                minimumInputLength: 2,
                ajax: {
                    url: '<?= site_url() ?>sample/penjualan/get_data_product',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        data2 = [];
                        $.each(data, function(index, value) {
                            data2.push({
                                "id": value.id_product,
                                "price": value.price,
                                "text": value.title
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

            $("#fdc_disc").inputmask({
                alias: 'numeric',
                allowMinus: false,
                digits: 2,
                max: 100
            });

            $(".numeric").inputmask({
                alias: 'numeric',
                allowMinus: false,
                digits: 2
            });

            $(".money").inputmask({
                alias: 'numeric',
                autoGroup: true,
                groupSeparator: ",",
                allowMinus: false,
                digits: 2
            });

            var selected_product;
            var arrDetail;

            $('#select-product').on('select2:select', function(e) {
                console.log(selected_product);
                var data = e.params.data;
                selected_product = data;
                console.log(selected_product);
                $("#product-harga").val(numeral(data.price).format("0,000"));

            });



            $("#btn-add-product").click(function(event) {
                event.preventDefault();

                $("#myModal").modal({
                    backdrop: 'static',
                });
            })

            $("#btn-add-product-detail").click(function(event) {
                event.preventDefault();
                t = $('#tblDetailPenjualan').DataTable();


                var qty = numeral($("#product-qty").val());
                var harga = numeral($("#product-harga").val());
                var total = qty.value() * harga.value();
                var action = '<a class="btn-delete" href="#" data-toggle="confirmation" data-original-title="" title=""><i class="fa fa-trash"></i></a>';

                t.row.add({
                    fin_sj_id: 0,
                    id_product: selected_product.id,
                    product_name: selected_product.text,
                    fin_qty: $("#product-qty").val(),
                    fdc_harga: harga.value(),
                    total: total,
                    action: action
                }).draw(false);

                calculateTotal();
            });

            $('#tblDetailDO').on('preXhr.dt', function(e, settings, data) {
                //add aditional data post on ajax call
                data.sessionId = "TEST SESSION ID";
            }).DataTable({
                columns: [{
                        "title": "ID",
                        "width": "0%",
                        sortable: false,
                        data: "fin_sj_id",
                        visible: false
                    },
                    {
                        "title": "id_product",
                        "width": "0%",
                        sortable: false,
                        data: "id_product",
                        visible: false
                    },
                    {
                        "title": "product",
                        "width": "60%",
                        sortable: false,
                        data: "product_name"
                    },
                    {
                        "title": "Qty",
                        "width": "10%",
                        sortable: false,
                        data: "fin_qty"
                    },
                    {
                        "title": "Harga",
                        "width": "10%",
                        sortable: false,
                        data: "fdc_harga",
                        render: $.fn.dataTable.render.number(',', '.', 2),
                        className: 'dt-right'
                    },
                    {
                        "title": "Total",
                        "width": "10%",
                        sortable: false,
                        data: "total",
                        render: $.fn.dataTable.render.number(',', '.', 2),
                        className: 'dt-right'
                    },
                    {
                        "title": "action",
                        "width": "10%",
                        data: "action",
                        sortable: false,
                        className: 'dt-center'
                    },
                ],
                processing: true,
                serverSide: false,
                searching: false,
                lengthChange: false,
                paging: false,
                info: true,
            }).on('draw', function() {
                $('.btn-delete').confirmation({
                    //rootSelector: '[data-toggle=confirmation]',
                    rootSelector: '.btn-delete',
                    // other options
                });

                $(".btn-delete").click(function(event) {
                    t = $('#tblDetailPenjualan').DataTable();
                    var trRow = $(this).parents('tr');

                    t.row(trRow).remove().draw();
                    calculateTotal();

                    //trRow.remove();		
                    //$('#tblDetailPenjualan').DataTable().row(0).delete();		
                });

                $(".btn-edit").click(function(event) {
                    id = $(this).data("id");
                    window.location.replace("<?= site_url() ?>sample/Penjualan/edit/" + id);
                });

            });

            $("#fdc_disc").keypress(function(event) {
                event.preventDefault();
                calculateTotal();
            })
        });

        function calculateTotal() {
            t = $('#tblDetailPenjualan').DataTable();
            datas = t.data();

            subTotal = 0;
            disc = parseFloat($("#fdc_disc").val());

            $.each(datas, function(i, v) {
                subTotal = subTotal + (v.fin_qty * v.fdc_harga);
            })

            $("#sub-total").val(numeral(subTotal).format("0,000"));
            disc_val = subTotal * (disc / 100);
            $("#disc-val").val(numeral(disc_val).format("0,000"));
            total = subTotal - disc_val;
            $("#total").val(numeral(total).format("0,000"));
        }

        function init_form(fin_sj_id) {
            //alert("Init Form");
            var url = "<?= site_url() ?>/sample/penjualan/fetch_data/" + fin_sj_id;
            $.ajax({
                type: "GET",
                url: url,
                success: function(resp) {
                    console.log(resp.FTHeader);
                    console.log(resp.FTDetail);

                    $.each(resp.FTHeader, function(name, val) {
                        var $el = $('[name="' + name + '"]'),
                            type = $el.attr('type');
                        switch (type) {
                            case 'checkbox':
                                $el.attr('checked', 'checked');
                                break;
                            case 'radio':
                                $el.filter('[value="' + val + '"]').attr('checked', 'checked');
                                break;
                            default:
                                $el.val(val);
                        }
                    });

                    //alert(resp.SOHeader.fst_customer_id);--munculkan respon id punya select2--
                    //var data = {
                    //id: resp.SOHeader.fst_customer_id,
                    //text: resp.SOHeader.fst_customername
                    //};

                    //var newOption = new Option(data.text, data.id, false, false);
                    //$('#select-customer').append(newOption).trigger('change');

                    var data = {
                        id: resp.FTHeader.fst_so_id,
                        text: resp.FTHeader.fst_so_id
                    };

                    var newOption = new Option(data.text, data.id, false, false);
                    $('#select-so').append(newOption).trigger('change');


                    //populate FTDetail
                    $.each(resp.FTDetail, function(name, val) {
                        console.log(val);
                        //event.preventDefault();
                        t = $('#tblDetailDO').DataTable();

                        t.row.add({
                            fin_sj_id: val.fin_sj_id,
                            id_product: val.id_product,
                            product_name: val.title,
                            fin_qty: val.fin_qty,
                            fdc_harga: val.fdc_harga,
                            total: val.fin_qty * val.fdc_harga,
                            action: action
                        }).draw(false);

                        calculateTotal();
                        //add_address(val.fin_sj_id, val.fst_name, val.fst_address, isPrimary);
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
    <script src="<?= base_url() ?>bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url() ?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>