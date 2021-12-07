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
    <h1><?= lang("Team Workstation") ?><small><?= lang("form") ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> <?= lang("Home") ?></a></li>
        <li><a href="#"><?= lang("Team Workstation") ?></a></li>
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
						<a id="btnDelete" class="btn btn-primary" href="#" title="<?=lang("Hapus")?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
						<a id="btnList" class="btn btn-primary" href="#" title="<?=lang("Daftar Transaksi")?>"><i class="fa fa-list" aria-hidden="true"></i></a>												
					</div>
                </div>
                <!-- end box header -->

                <!-- form start fbl_is_multiples_prize -->
                <form id="frmActivityTeams" class="form-horizontal" action="<?= site_url() ?>master/activity_teams/add" method="POST" enctype="multipart/form-data">
                    <div class="box-body">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        <input type="hidden" id="frm-mode" value="<?= $mode ?>">

                        <div class="form-group">
                            <label for="fin_team_id" class="col-md-2 control-label"><?= lang("Team ID") ?>:</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="fin_team_id" placeholder="<?= lang("(Autonumber)") ?>" name="fin_team_id" value="<?= $fin_team_id ?>" readonly>
                                <div id="fin_team_id_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fst_team_name" class="col-md-2 control-label"><?= lang("Team Name") ?>:</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="fst_team_name" placeholder="<?= lang("Team Name") ?>" name="fst_team_name">
                                <div id="fst_team_name_err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fin_headteam_id" class="col-md-2 control-label"><?= lang("Head Team") ?>:</label>
                            <div class="col-md-4">
                                <select id="fin_headteam_id" class="form-control" name="fin_headteam_id"></select>
                                <div id="fin_headteam_id_err" class="text-danger"></div>
                            </div>
                        </div>
                        <!-- end box body -->
                        <form class="form-horizontal edit-mode ">	
                            <div class="form-group">
                                <div class="col-md-12">
                                    <button id="btn-add-team-detail" class="btn btn-primary btn-sm pull-right edit-mode"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;&nbsp;<?= lang("Personel") ?></button>
                                </div>
                                <div class="col-md-12">
                                    <table id="tbl_teams_detail" class="table table-bordered table-hover" style="width:100%;"></table>
                                </div>							
                            </div>
                        </form>

                        <div class="box-footer text-right">
                            
                        </div>
                        <!-- end box-footer -->
                </form>
            </div>
        </div>
</section>

<div id="mdlTeamDetail" class="modal fade in" role="dialog" style="display: none">
    <div class="modal-dialog" style="display:table;width:40%;min-width:400px;max-width:100%">
        <!-- Modal content-->
        <div class="modal-content" style="border-top-left-radius:15px;border-top-right-radius:15px;border-bottom-left-radius:15px;border-bottom-right-radius:15px;">
            <div class="modal-header" style="padding:15px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 15px;border-top-right-radius: 15px;">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?= lang("Add Team Detail") ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" >
                        <div style="border:1px inset #f0f0f0;border-radius:10px;padding:5px">
                            <fieldset style="padding:10px">
                                <form class="form-horizontal ">
                                    <div class="form-group">
                                        <label for="select-fin_user_id" class="col-md-3 control-label"><?=lang("Personel")?></label>
                                        <div class="col-md-9">
                                            <select id="fin_user_id" class="form-control" name="fin_user_id"></select>
                                            <div id="fin_user_id_err" class="text-danger"></div>
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
            $("#btn-add-team-detail").click(function(event) {
                event.preventDefault();
                $("#mdlTeamDetail").modal('show');
                $("#fin_user_id").empty();
            });
            $("#tbl_teams_detail").DataTable({
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
                        "title": "<?= lang("Personel ID") ?>",
                        "width": "5%",
                        data: "fin_user_id",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Personel Name") ?>",
                        "width": "20%",
                        data: "personel_name",
                        visible: true,
                    },
                    {
                        "title": "<?= lang("Action ") ?>",
                        "width": "5%",
                        render: function(data, type, row) {
                            action = "<a class='btn-delete-team-detail edit-mode' href='#'><i class='fa fa-trash'></i></a>&nbsp;";
                            return action;
                        },
                        "sortable": false,
                        "className": "dt-body-center text-center"
                    }
                ],
            });
            $("#tbl_teams_detail").on("click", ".btn-delete-team-detail", function(event) {
                event.preventDefault();
                t = $("#tbl_teams_detail").DataTable();
                var trRow = $(this).parents('tr');
                t.row(trRow).remove().draw();
            });
            $("#fin_user_id").select2({
                width: '100%',
                ajax: {
                    url: '<?= site_url() ?>master/activity_teams/get_user_team',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        data2 = [];
                        $.each(data, function(index, value) {
                            data2.push({
                                "id": value.fin_user_id,
                                "text": value.fst_username
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
            var selected_personel;
            $('#fin_user_id').on('select2:select', function(e) {
                console.log(selected_personel);
                var data = e.params.data;
                selected_personel = data;
            });
            $("#btn-add-detail").click(function(event) {
                event.preventDefault();
                t = $('#tbl_teams_detail').DataTable();
                addRow = true;
                var personel = $("#fin_user_id").val();
                if (personel == null || personel == "") {
                    $("#fin_user_id_err").html("Please select Personel");
                    $("#fin_user_id_err").show();
                    addRow = false;
                    return;
                } else {
                    data = t.rows().data();
                    console.log(data);
                    var valid = true;
                    $.each(data, function(i, v) {
                        if (v.fin_user_id == personel) {
                            $("#fin_user_id_err").html("Selected Personil is already exist!");
                            $("#fin_user_id_err").show();
                            addRow = false;
                            valid = false;
                            return false;
                        } else {
                            $("#fin_user_id_err").hide();
                        }
                    });
                    if (valid == false){
                        return;
                    }
                }
                t.row.add({
                    fin_id: 0,
                    fin_team_id: 0,
                    fin_user_id: selected_personel.id,
                    personel_name: selected_personel.text,
                    action: action
                }).draw(false);
            });
        });
    </script>
</div>

<script type="text/javascript">
    $(function() {
        <?php if ($mode == "EDIT") { ?>
            init_form($("#fin_team_id").val());
        <?php } ?>
        $("#btnSubmitAjax").click(function(event) {
            event.preventDefault();
            data = $("#frmActivityTeams").serializeArray();
            //data = new FormData($("#frmMSItems")[0]);
            detail = new Array();
            t = $('#tbl_teams_detail').DataTable();
            datas = t.data();
            $.each(datas, function(i, v) {
                detail.push(v);
            });
            data.push({
                name: "teamdetails",
                value: JSON.stringify(detail)
            });
            mode = $("#frm-mode").val();
            if (mode == "ADD") {
                url = "<?= site_url() ?>master/activity_teams/ajx_add_save";
            } else {
                url = "<?= site_url() ?>master/activity_teams/ajx_edit_save";
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
                        $("#fin_team_id").val(data.insert_id);
                        //Clear all previous error
                        $(".text-danger").html("");
                        // Change to Edit mode
                        $("#frm-mode").val("EDIT"); //ADD|EDIT
                        $('#fst_team_name').prop('readonly', true);
                    }
                },
                error: function(e) {
                    $("#result").text(e.responseText);
                    console.log("ERROR : ", e);
                    $("#btnSubmit").prop("disabled", false);
                }
            });
        });

        $("#fin_headteam_id").select2({
            width: '100%',
            ajax: {
                url: '<?= site_url() ?>master/activity_teams/get_user_team',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    data2 = [];
                    $.each(data, function(index, value) {
                        data2.push({
                            "id": value.fin_user_id,
                            "text": value.fst_username
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

        $("#btnNew").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>master/activity_teams/add")
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
				url:"<?= site_url() ?>master/activity_teams/delete/" + $("#fin_team_id").val(),
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
									window.location.href = "<?= site_url() ?>master/activity_teams/lizt";
									return;
								}
							},
						}
					});
				}

				if(resp.status == "SUCCESS") {
					data = resp.data;
					$("#fin_team_id").val(data.insert_id);

					//Clear all previous error
					$(".text-danger").html("");
					// Change to Edit mode
					$("#frm-mode").val("EDIT");  //ADD|EDIT
					$('#fst_team_name').prop('readonly', true);
				}
			});
		});

		$("#btnList").click(function(e){
			e.preventDefault();
			window.location.replace("<?=site_url()?>master/activity_teams/lizt");
		});
    });


    function init_form(fin_team_id) {
        //alert("Init Form");
        var url = "<?= site_url() ?>master/activity_teams/fetch_data/" + fin_team_id;
        $.ajax({
            type: "GET",
            url: url,
            success: function(resp) {
                $.each(resp.msactivityteams, function(name, val) {
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
				var newOption = new Option(resp.msactivityteams.headteam, resp.msactivityteams.fin_headteam_id, true, true);
    			// Append it to the select
    			$('#fin_headteam_id').append(newOption).trigger('change');

                $.each(resp.teamdetails, function(name, val) {
                    console.log(val);
                    //event.preventDefault();
                    t = $('#tbl_teams_detail').DataTable();
                        t.row.add({
                        fin_rec_id: val.fin_rec_id,
                        fin_team_id: val.fin_team_id,
                        fin_user_id: val.fin_user_id,
                        personel_name: val.personel,
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