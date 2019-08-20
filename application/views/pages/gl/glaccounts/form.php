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
                    <div class="btn-group btn-group-sm pull-right">
                        <a id="btnNew" class="btn btn-primary" href="#" title="<?=lang("Tambah Baru")?>"><i class="fa fa-plus" aria-hidden="true"></i></a>
						<a id="btnSubmitAjax" class="btn btn-primary" href="#" title="<?=lang("Simpan")?>"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
						<a id="btnPrint" class="btn btn-primary" href="#" title="<?=lang("Cetak")?>"><i class="fa fa-print" aria-hidden="true"></i></a>
						<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
						<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
					</div>
                </div>
                <!-- end box header -->

                <!-- form start -->
                <form id="frmGlaccounts" class="form-horizontal" action="<?= site_url() ?>gl/glaccount/add" method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" id="frm-mode" value="<?= $mode ?>">

                        <div class="form-group maingroups">
                            <label for="select-MainGL" class="col-md-2 control-label"><?= lang("Main Group") ?> :</label>
                            <div class="col-md-4">
                                <select id="select-MainGL" class="form-control" name="fin_glaccount_maingroup_id">
                                    <option value="0">-- <?=lang("select")?> --</option>
                                </select>
                                <div id="fin_glaccount_maingroup_id_err" class="text-danger"></div>
                            </div>

                            <label for="select-ParentGL" class="col-md-2 control-label"><?= lang("Parent") ?> :</label>
                            <div class="col-md-4">
                                <select id="select-ParentGL" class="form-control" name="fst_parent_glaccount_code">
                                    <option value="0">-- <?=lang("select")?> --</option>
                                </select>
                                <div id="fst_parent_glaccount_code_err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fst_glaccount_code" class="col-sm-2 control-label"><?= lang("GL Account Code") ?> * </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="fst_glaccount_code" style="width: unset" placeholder="<?= lang("GL Account Code") ?>" name="fst_glaccount_code" value="<?=$fst_glaccount_code?>">
                                <div id="fst_glaccount_code_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fst_glaccount_level" class="col-sm-2 control-label"><?= lang("Level")?> :</label>
                            <div class="col-sm-4">
                                <select class="form-control" id="fst_glaccount_level" name="fst_glaccount_level">
                                    <option value="0">-- <?=lang("select")?> --</option>
                                    <option value='HD'><?= lang("Header") ?></option>
                                    <option value='DT'><?= lang("Detail") ?></option>
                                    <option value='DK'><?= lang("Detail KasBank") ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fst_glaccount_name" class="col-sm-2 control-label"><?= lang("GL Account Name") ?> :</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="fst_glaccount_name" placeholder="<?= lang("GL Account Name") ?>" name="fst_glaccount_name">
                                <div id="fst_glaccount_name_err" class="text-danger"></div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="fst_default_post" class="col-sm-2 control-label"><?= lang("Default Post")?> :</label>
                            <div class="col-sm-4">
                                <select class="form-control" id="fst_default_post" name="fst_default_post">
                                    <option value='D'><?= lang("DEBIT") ?></option>
                                    <option value='C'><?= lang("CREDIT") ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fin_min_user_level_access" class="col-sm-2 control-label"><?= lang("Min. Level Access")?> :</label>
                            <div class="col-sm-4">
                                <select class="form-control" id="fin_min_user_level_access" name="fin_min_user_level_access">
                                    <option value="0">-- <?=lang("select")?> --</option>
                                    <option value='1'><?= lang("Top Management") ?></option>
                                    <option value='2'><?= lang("Upper Management") ?></option>
                                    <option value='3'><?= lang("Middle Management") ?></option>
                                    <option value='4'><?= lang("Supervisors") ?></option>
                                    <option value='5'><?= lang("Line Workers") ?></option>
                                    <option value='6'><?= lang("Public") ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select-Currency" class="col-md-2 control-label"><?= lang("Curr Code") ?> :</label>
                            <div class="col-md-4">
                                <select id="select-Currency" class="form-control" name="fst_curr_code"></select>
                                <!--<div id="fst_curr_code_err" class="text-danger"></div>-->
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
                            <label for="fbl_is_allow_in_cash_bank_module" class="col-sm-2 control-label"><?= lang("Allow") ?> :</label>
                            <div class="checkbox col-sm-2">
                                <label><input id="fbl_is_allow_in_cash_bank_module" type="checkbox" name="fbl_is_allow_in_cash_bank_module" value="1"><?= lang("Allow In CashBank Module") ?></label><br>
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

        var ajaxManiGL =  {
            url: '<?= site_url() ?>gl/glaccount/get_MainGL',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                items = [];
                $.each(data, function(index, value) {
                    items.push({
                        "id": value.fin_glaccount_maingroup_id,
                        "text": value.fst_glaccount_maingroup_name,
                        "prefix" : value.fst_glaccount_main_prefix
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
            init_form($("#fst_glaccount_code").val());
            //$(".maingroups").hide();
        <?php } ?>

        $("#btnSubmitAjax").click(function(event) {
            event.preventDefault();
            //data = new FormData($("#frmGlaccounts")[0]);
            data = $("#frmGlaccounts").serializeArray();

            mode = $("#frm-mode").val();
            if (mode == "ADD") {
                url = "<?= site_url() ?>gl/glaccount/ajx_add_save";
            } else {
                url = "<?= site_url() ?>gl/glaccount/ajx_edit_save";
            }

            //var formData = new FormData($('form')[0])
            $.ajax({
                type: "POST",
                //enctype: 'multipart/form-data',
                url: url,
                data: data,
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
                                        window.location.href = "<?= site_url() ?>gl/glaccount/lizt";
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
                        $("#fst_glaccount_code").val(data.insert_id);

                        //Clear all previous error
                        $(".text-danger").html("");

                        // Change to Edit mode
                        $("#frm-mode").val("EDIT"); //ADD|EDIT
                        $('#fst_glaccount_name').prop('readonly', true);
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
            $("#fst_glaccount_code").inputmask({
                mask: mainGL.prefix,//replace(/9/g,"\\9") + "<?= $mainGLSeparator ?>" + "[9][9][9][9][9][9]",
                greedy:true,
            });
            $("#fst_glaccount_code").attr("placeholder",mainGL.prefix);

            //$('#select-ParentGL').val(null).trigger('change');
            $("#select-ParentGL").select2({
                width: '100%',
                ajax: {
                    url: '<?= site_url() ?>gl/glaccount/get_ParentGL/' + $("#select-MainGL").val(),
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        items = [];
                        $.each(data, function(index, value) {
                            items.push({
                                "id": value.fst_glaccount_code,
                                "text": value.fst_glaccount_name
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
            if(typeof parentGL === 'undefined'){
                return;
            }
            console.log(parentGL);
            //alert(parentGL.id.replace(/9/g,'\\9'));
            $("#fst_glaccount_code").inputmask({
                mask: parentGL.id.replace(/9/g,"\\9") + "<?= $parentGLSeparator ?>" + "[9][9][9][9][9][9]",
                greedy:true,
            });
            $("#fst_glaccount_code").attr("placeholder",parentGL.id);
            
        });

        $("#select-Currency").select2({
            width: '100%',
            ajax: {
                url: '<?= site_url() ?>gl/glaccount/get_Currency',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    items = [];
                    $.each(data, function(index, value) {
                        items.push({
                            "id": value.fst_curr_code,
                            "text": value.fst_curr_name
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

        
		$("#fst_glaccount_level").change(function(event){
            //alert("fst_glaccount_level");
			event.preventDefault();
			$("#select-ParentGL").show();

			$("#fst_glaccount_level").each(function(index){				
				if ($(this).val() == "HD"){
                    $("#select-ParentGL").attr('disabled', 'disabled');
				}else{
                    $("#select-ParentGL").attr('disabled', false);
                }
			});
        });

        $("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>gl/glaccount/add")
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
				url:"<?= site_url() ?>gl/glaccount/delete/" + $("#fst_glaccount_code").val(),
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
									window.location.href = "<?= site_url() ?>gl/glaccount/lizt";
									//return;
								}
							},
						}
					});
				}

				if(resp.status == "SUCCESS") {
					data = resp.data;
					$("#fst_glaccount_code").val(data.insert_id);

					//Clear all previous error
					$(".text-danger").html("");
					// Change to Edit mode
					$("#frm-mode").val("EDIT");  //ADD|EDIT
					$('#fst_glaccount_name').prop('readonly', true);
				}
			});
		});

		$("#btnList").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>gl/glaccount/lizt");
		});
        
    });

    function init_form(fst_glaccount_code) {
        //alert("Init Form");
        //alert(fst_glaccount_code);
        var url = "<?= site_url() ?>gl/glaccount/fetch_data/" + fst_glaccount_code;
        $.ajax({
            type: "GET",
            url: url,
            success: function(resp) {
                console.log(resp.gl_Account);
                
                $.each(resp.gl_Account, function(name, val) {
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

                    $("#fst_glaccount_code").attr('readonly', 'readonly');
                });

                $("#fst_glaccount_level").select2();
                
                // menampilkan data di select2, menu edit/update
                var newOption = new Option(resp.gl_Account.fst_curr_name, resp.gl_Account.fst_curr_code, true, true);
                $('#select-Currency').append(newOption).trigger('change');
               
                var newOption = new Option(resp.gl_Account.fst_glaccount_maingroup_name, resp.gl_Account.fin_glaccount_maingroup_id, true, true);
                
                //$('#select-MainGL').val(resp.gl_Account.fin_glaccount_maingroup_id).trigger('change');
                var data = [{
                    id:0,
                    text:"select",
                    prefix: "0"
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
                        id:0,
                        text:"select",
                        prefix: "0"
                    }],
                    ajax: ajaxManiGL,
                });
                //$('#select-MainGL').val(1).trigger('change');
                $('#select-MainGL').append(newOption).trigger('change');
                
                $("#select-MainGL").select2();
                $("#select-ParentGL").select2();
                $("#select-MainGL,#select-ParentGL,#fst_glaccount_level").select2("enable", false);

                var newOption = new Option(resp.gl_Account.GLParentName, resp.gl_Account.fst_parent_glaccount_code, true, true);
                $('#select-ParentGL').append(newOption);
                $("#select-ParentGL").val(resp.gl_Account.fst_parent_glaccount_code).trigger('change');

                $("#fst_glaccount_code").inputmask("setvalue", resp.gl_Account.fst_glaccount_code);
                $("#fst_glaccount_level").val(resp.gl_Account.fst_glaccount_level);

                console.log(resp.parents);
                
                if (resp.parents == null && resp.isUsed == false){
                    $("#fst_glaccount_level").select2("enable");
                }else{
                    $("#fst_glaccount_level").select2("enable", false);
                }
               
            },

            error: function(e) {
                $("#result").text(e.responseText);
                console.log("ERROR : ", e);
            }
        });
    }
</script>
