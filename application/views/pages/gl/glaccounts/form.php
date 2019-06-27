<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<link rel="stylesheet" href="<?= base_url() ?>bower_components/select2/dist/css/select2.min.css">

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
                </div>
                <!-- end box header -->

                <!-- form start -->
                <form id="frmGLAccounts" class="form-horizontal" action="<?= site_url() ?>GL/GLAccountCode" method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" id="frm-mode" value="<?= $mode ?>">

                        <div class="form-group maingroups">
                            <label for="select-MainGL" class="col-md-2 control-label"><?= lang("Main Group") ?> :</label>
                            <div class="col-md-4">
                                <select id="select-MainGL" class="form-control" name="GLAccountMainGroupId"></select>
                                <div id="GLAccountMainGroupId_err" class="text-danger"></div>
                            </div>

                            <label for="select-ParentGL" class="col-md-2 control-label"><?= lang("Parent") ?> :</label>
                            <div class="col-md-4">
                                <select id="select-ParentGL" class="form-control" name="ParentGLAccountCode"></select>
                                <div id="ParentGLAccountCode_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="GLAccountCode" class="col-sm-2 control-label"><?= lang("GL Account Code") ?> * </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="GLAccountCode" style="width: unset" placeholder="<?= lang("GL Account Code") ?>" name="GLAccountCode" value="<?=$GLAccountCode?>">
                                <div id="GLAccountCode_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="GLAccountLevel" class="col-sm-2 control-label"><?= lang("Level") ?></label>
                            <div class="col-sm-4">
                                <select class="form-control" id="GLAccountLevel" name="GLAccountLevel">
                                    <option value='HD'><?= lang("Header") ?></option>
                                    <option value='DT'><?= lang("Detail") ?></option>
                                    <option value='DK'><?= lang("Detail KasBank") ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="GLAccountName" class="col-sm-2 control-label"><?= lang("GL Account Name") ?> * </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="GLAccountName" placeholder="<?= lang("GL Account Name") ?>" name="GLAccountName">
                                <div id="GLAccountName_err" class="text-danger"></div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="DefaultPost" class="col-sm-2 control-label"><?= lang("Default Post") ?></label>
                            <div class="col-sm-4">
                                <select class="form-control" id="DefaultPost" name="DefaultPost">
                                    <option value='D'><?= lang("DEBIT") ?></option>
                                    <option value='C'><?= lang("CREDIT") ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="MinUserLevelAccess" class="col-sm-2 control-label"><?= lang("Min. Level Access") ?></label>
                            <div class="col-sm-4">
                                <select class="form-control" id="MinUserLevelAccess" name="MinUserLevelAccess">
                                    <option value='0'><?= lang("Top Management") ?></option>
                                    <option value='1'><?= lang("Upper Management") ?></option>
                                    <option value='2'><?= lang("Middle Management") ?></option>
                                    <option value='3'><?= lang("Supervisors") ?></option>
                                    <option value='4'><?= lang("Line Workers") ?></option>
                                    <option value='5'><?= lang("Public") ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select-CurrCode" class="col-md-2 control-label"><?= lang("Curr Code") ?> :</label>
                            <div class="col-md-4">
                                <select id="select-CurrCode" class="form-control" name="CurrCode"></select>
                                <!--<div id="CurrCode_err" class="text-danger"></div>-->
                            </div>
                        </div>
                        <div class="form-group">

                            <label for="fin_seq_no" class="col-sm-2 control-label"><?= lang("Seq Number") ?> * </label>
                            <div class="col-sm-1">
                                <input type="text" class="form-control" id="fin_seq_no" placeholder="<?= lang("0") ?>" name="fin_seq_no">
                                <div id="fin_seq_no_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="isAllowInCashBankModule" class="col-sm-2 control-label"><?= lang("Allow") ?> :</label>
                            <div class="checkbox col-sm-2">
                                <label><input id="isAllowInCashBankModule" type="checkbox" name="isAllowInCashBankModule" value="1"><?= lang("Allow In CashBank Module") ?></label><br>
                            </div>
                        </div>
                        <!-- end box body -->

                        <div class="box-footer text-right">
                            <a id="btnSubmitAjax" href="#" class="btn btn-primary">Save Record</a>
                        </div>
                        <!-- end box-footer -->
                </form>
            </div>
        </div>
</section>

<script type="text/javascript">

        var ajaxManiGL =  {
                url: '<?= site_url() ?>GL/GLAccounts/get_MainGL',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    items = [];
                    $.each(data, function(index, value) {
                        items.push({
                            "id": value.GLAccountMainGroupId,
                            "text": value.GLAccountMainGroupName,
                            "prefix" : value.GLAccountMainPrefix
                        });
                    });
                    console.log(items);
                    return {
                        results: items
                    };
                },
                cache: true,
            }


    $(function() {

        <?php if ($mode == "EDIT") { ?>
            init_form($("#GLAccountCode").val());
            //$(".maingroups").hide();
        <?php } ?>

        $("#btnSubmitAjax").click(function(event) {
            event.preventDefault();
            data = new FormData($("#frmGLAccounts")[0]);

            mode = $("#frm-mode").val();
            if (mode == "ADD") {
                url = "<?= site_url() ?>GL/GLAccounts/ajx_add_save";
            } else {
                url = "<?= site_url() ?>GL/GLAccounts/ajx_edit_save";
            }

            //var formData = new FormData($('form')[0])
            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: url,
                data: data,
                processData: false,
                contentType: false,
                cache: false,
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
                                        window.location.href = "<?= site_url() ?>GL/GLAccounts";
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
                        $("#GLAccountCode").val(data.insert_id);

                        //Clear all previous error
                        $(".text-danger").html("");

                        // Change to Edit mode
                        $("#frm-mode").val("EDIT"); //ADD|EDIT
                        $('#GLAccountName').prop('readonly', true);

                    }
                },
                error: function(e) {
                    $("#result").text(e.responseText);
                    console.log("ERROR : ", e);
                    $("#btnSubmit").prop("disabled", false);
                }
            });
        });

        $("#select-MainGL").select2({
            width: '100%',
            ajax: ajaxManiGL,
        });

        $("#select-MainGL").change(function(event) {
            event.preventDefault();
            mainGL = $("#select-MainGL").select2("data")[0];
            console.log(mainGL);
            $("#GLAccountCode").inputmask({
                //mask: mainGL.prefix.replace(/9/g,"\\9") + "<?= $mainGLSeparator ?>" + "[9][9][9][9][9][9]",
                greedy:true,
            });
            $("#GLAccountCode").attr("placeholder",mainGL.prefix);

            //$('#select-ParentGL').val(null).trigger('change');
            $("#select-ParentGL").select2({
                width: '100%',
                ajax: {
                    url: '<?= site_url() ?>GL/GLAccounts/get_ParentGL/' + $("#select-MainGL").val(),
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        items = [];
                        $.each(data, function(index, value) {
                            items.push({
                                "id": value.GLAccountCode,
                                "text": value.GLAccountName
                            });
                        });
                        console.log(items);
                        return {
                            results: items
                        };
                    },
                    cache: true,
                }
            });
        })


        $("#select-ParentGL").change(function(event) {
            event.preventDefault();
            parentGL = $("#select-ParentGL").select2("data")[0];
            console.log(parentGL);
            //alert(parentGL.id.replace(/9/g,'\\9'));
            $("#GLAccountCode").inputmask({
                mask: parentGL.id.replace(/9/g,"\\9") + "<?= $parentGLSeparator ?>" + "[9][9][9][9][9][9]",
                greedy:true,
            });
            $("#GLAccountCode").attr("placeholder",parentGL.id);
            
        });

        $("#select-CurrCode").select2({
            width: '100%',
            ajax: {
                url: '<?= site_url() ?>GL/GLAccounts/get_CurrCode',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    items = [];
                    $.each(data, function(index, value) {
                        items.push({
                            "id": value.CurrCode,
                            "text": value.CurrName
                        });
                    });
                    console.log(items);
                    return {
                        results: items
                    };
                },
                cache: true,
            }
        });


    });

    function init_form(GLAccountCode) {
        //alert("Init Form");
        //alert(GLAccountCode);
        var url = "<?= site_url() ?>GL/GLAccounts/fetch_data/" + GLAccountCode;
        $.ajax({
            type: "GET",
            url: url,
            success: function(resp) {
                console.log(resp.glAccounts);

                $.each(resp.glAccounts, function(name, val) {
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

                // menampilkan data di select2, menu edit/update
                var newOption = new Option(resp.glAccounts.CurrName, resp.glAccounts.CurrCode, true, true);
                $('#select-CurrCode').append(newOption).trigger('change');


                var newOption = new Option(resp.glAccounts.GLAccountMainGroupName, resp.glAccounts.GLAccountMainGroupId, true, true);
                
                //$('#select-MainGL').val(resp.glAccounts.GLAccountMainGroupId).trigger('change');
                var data = [{
                    id:1,
                    text:" ",
                    prefix: "1"
                }];
                /*
                var option = new Option("Asset", 1, true, true);
                $('#select-MainGL').append(option);
                $('#select-MainGL').trigger({
                    type: 'select2:select',
                    params: {
                        data: data
                    }
                });
                */
                $('#select-MainGL').select2({
                    data:[{
                        id:1,
                        text:"Assets",
                        prefix: "1"
                    }],
                    ajax: ajaxManiGL,
                });
                //$('#select-MainGL').val(1).trigger('change');
                $('#select-MainGL').append(newOption).trigger('change');

                var newOption = new Option(resp.glAccounts.GLParentName, resp.glAccounts.ParentGLAccountCode, true, true);
                $('#select-ParentGL').append(newOption);
                $('#select-ParentGL').prop('readonly', true);
                $("#select-ParentGL").val(resp.glAccounts.ParentGLAccountCode).trigger('change');

                $("#GLAccountCode").inputmask("setvalue", resp.glAccounts.GLAccountCode);
                $('#GLAccountCode').prop('readonly', true);
                /*
                $('#select-MainGL').select2({
                    data:data,
                }).trigger('change');
                */


                
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
<script type="text/javascript">
    $(function(){
        $(".select2-container").addClass("form-control"); 
        $(".select2-selection--single , .select2-selection--multiple").css({
            "border":"0px solid #000",
            "padding":"0px 0px 0px 0px"
        });         
        $(".select2-selection--multiple").css({
            "margin-top" : "-5px",
            "background-color":"unset"
        });
    });
</script>