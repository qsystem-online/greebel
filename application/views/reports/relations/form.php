<!-- form start -->
<form id="rptRelations" action="<?= site_url() ?>report/relations/process" method="POST" enctype="multipart/form-data">
    <div class="box-body">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
            <div class="form-group row">                    
            <label for="select-country" class="col-md-2 control-label"><?=lang("Country Name")?></label>
                <div class="col-md-4">
                    <select id="select-country" class="form-control select2" name="fin_country_id">
                        <option value="0">-- <?=lang("select")?> --</option>
                    </select>
                    <div id="fst_country_name_err" class="text-danger"></div>
                </div>
            <label for="fin_branch_id" class="col-md-2 control-label"><?=lang("Branch Name")?></label>
                <div class="col-md-4">
                    <select id="select-branch" class="form-control" name="fin_branch_id">
                        <option value="0">-- <?=lang("select")?> --</option>
                    </select>
                    <div id="fin_branch_id_err" class="text-danger"></div>
                </div>
            </div>
            
            <div class="form-group row">
            <label for="select-provinces" class="col-md-2 control-label"><?=lang("Province Name")?></label>
                <div class="col-md-4">
                    <select id="select-provinces" class="form-control select2" name="fst_kode">
                        <option value="0">-- <?=lang("select")?> --</option>
                    </select>
                    <div id="fst_nama__err" class="text-danger"></div>
                </div>
            <label for="select-groupId" class="col-md-2 control-label"><?=lang("Relation Group Name")?></label>
                <div class="col-md-4">
                    <select id="select-groupId" class="form-control" name="fin_relation_group_id">
                        <option value="0">-- <?=lang("select")?> --</option>
                    </select>
                    <div id="fin_relation_group_id_err" class="text-danger"></div>
                </div>
            </div>
            <div class="form-group row">
            <label for="select-district" class="col-md-2 control-label"><?=lang("District Name")?></label>
                <div class="col-md-4">
                    <select id="select-district" class="form-control select2" name="fst_kode">
                        <option value="0">-- <?=lang("select")?> --</option>
                    </select>
                    <div id="fst_nama__err" class="text-danger"></div>
                </div>
            <label for="fst_relation_type" class="col-md-2 control-label"><?=lang("Relation Type")?></label>
                <div class="col-md-4">
                    <select class="form-control select2" id="fst_relation_type" name="fst_relation_type[]"  multiple="multiple">
                        <option value="1"><?=lang("Customer")?></option>
                        <option value="2"><?=lang("Supplier/Vendor")?></option>
                        <option value="3"><?=lang("Expedisi")?></option>
                    </select>
                </div>
            </div>
            
            <div class="form-group row">
            <label for="select-subdistrict" class="col-md-2 control-label"><?=lang("Sub District Name")?></label>
                <div class="col-md-4">
                    <select id="select-subdistrict" class="form-control select2" name="fst_kode">
                        <option value="0">-- <?=lang("select")?> --</option>
                    </select>
                    <div id="fst_nama__err" class="text-danger"></div>
                </div>
            <label for="fst_business_type" class="col-md-2 control-label"><?=lang("Business Type")?></label>
                <div class="col-md-4">
                    <select class="form-control select2" id="fst_business_type" name="fst_business_type">
                        <option value='0'>--<?=lang("select")?>--</option>
                        <option value='P'><?=lang("Personal")?></option>
                        <option value='C'><?=lang("Corporate")?></option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
            <label for="select-village" class="col-md-2 control-label"><?=lang("Village Name")?></label>
                <div class="col-md-4">
                    <select id="select-village" class="form-control select2" name="fst_kode">
                        <option value="0">-- <?=lang("select")?> --</option>
                        <div id="fst_nama__err" class="text-danger"></div>
                    </select>
                </div>
            <label for="select-parentId" class="col-md-2 control-label"><?=lang("Customer Induk")?></label>
                <div class="col-md-4">
                    <select id="select-parentId" class="form-control relation-info" name="fin_parent_id">
                        <option value="0">-- <?=lang("select")?> --</option>
                    </select>
                    <div id="fin_parent_id_err" class="text-danger"></div>
                </div>
            </div>
            <div class="form-group row">
            <label for="select-lineBusiness" class="col-md-2 control-label"><?=lang("Line Of Business")?></label>
                <div class="col-md-10">
                    <select class="form-control select2" id="select-lineBusiness" name="fst_linebusiness_id[]"  multiple="multiple">
                    <?php foreach ($linebusinessList as $linebusiness) {    ?>
                                <option value='<?= $linebusiness->fin_linebusiness_id ?>'><?= $linebusiness->fst_linebusiness_name ?> </option>
                            <?php
                        } ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="select-relationId" class="col-md-2 control-label"><?= lang("Relation Name") ?></label>
                <div class="col-md-4">
                    <select id="select-relationId" class="form-control" name="fin_relation_id">
                        <option value="0">--  <?= lang("select") ?>  --</option>
                    </select>
                    <div id="fin_relation_id_err" class="text-danger"></div>
                </div>
                <label for="select-relationId2" class="col-md-2 control-label"><?= lang("s/d") ?></label>
                <div class="col-md-4">
                    <select id="select-relationId2" class="form-control" name="fin_relation_id2">
                        <option value="0">--  <?= lang("select") ?>  --</option>
                    </select>
                    <div id="fin_relation_id2_err" class="text-danger"></div>
                </div>
            </div>
        
            <div class="form-group row">
                <label for="rpt_layout" class="col-sm-2 control-label"><?=lang("Report Layout")?></label>
                <div class="col-sm-4">								
                    <label class="radio"><input type="radio" id="rpt_layout1" class="rpt_layout" name="rpt_layout" value="1" checked onclick="handleRadioClick(this);"><?=lang("Laporan Daftar Relasi")?></label>
                    <label class="radio"><input type="radio" id="rpt_layout2" class="rpt_layout" name="rpt_layout" value="2" onclick="handleRadioClick(this);"><?=lang("Laporan Daftar Relasi Detail Alamat Pengiriman")?></label>
                </div>
                <label for="selected_colums" class="col-sm-2 control-label"><?=lang("Selected Columns")?></label>
                <div class="container col-sm-4">
                    <select id="multiple-columns" multiple="multiple" name="selected_columns[]">
                        <?php
                            foreach($layout_columns as $row) {
                                if ($row['layout']==1){
                                    $caption = $row['label'];
                                    $colNo = $row['value'];
                                    echo "<option value='$colNo'>$caption</option>";
                                }
                            };

                        ?>
                        <!-- <option value="php">PHP</option>
                        <option value="javascript">JavaScript</option>
                        <option value="java">Java</option>
                        <option value="sql">SQL</option>
                        <option value="jquery">Jquery</option>
                        <option value=".net">.Net</option> -->
                    </select>             
                </div>
            </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function() {
       // $('#multiple-columns').multiselect();
        $('#multiple-columns').multiselect({
            enableFiltering: true,        
            // includeResetOption: true,
            includeSelectAllOption: true,
            selectAllText: 'Pilih semua'
        });
        $('#multiple-columns').multiselect('selectAll',false);
        $('#multiple-columns').multiselect('updateButtonText');
        
    });

    function handleRadioClick(myRadio) {
        // alert('Old value: ' + currentValue);        
        var js_data = '<?php echo json_encode($layout_columns); ?>';
        var js_obj_data = JSON.parse(js_data );
        
        var newArray = js_obj_data.filter(function (el) {                        
            // alert(el.layout==(myRadio.value).toString());
            return el.layout==(myRadio.value).toString();
        });        

        console.log(newArray);
        $('#multiple-columns').multiselect('dataprovider', newArray);
		$('#multiple-columns').multiselect('selectAll',false);
		$('#multiple-columns').multiselect('updateButtonText');
        // for(var i=0; i<newArray.length; i++){
        //     alert(newArray[i].label);
        //     console.log(newArray[i].label);
        // }
        
        // currentValue = myRadio.value;
    }         
    $(function() {

        $("#select-parentId").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>pr/relation/get_parent_id',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.fin_relation_id,
							"text" : value.fst_relation_name
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

		$("#select-branch").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>pr/relation/get_branch',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.fin_branch_id,
							"text" : value.fst_branch_name
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

		$("#select-groupId").select2({
			width: '100%',
			tokenSeparators: [",", " "],
			ajax: {
				url: '<?=site_url()?>pr/relation/get_relation_group',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.fin_relation_group_id,
							"text" : value.fst_relation_group_name
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

		$("#fst_business_type").change(function(event){
			event.preventDefault();
			$(".personal-info").hide();

			$("#fst_business_type").each(function(index){				
				if ($(this).val() == "P"){
					$(".personal-info").show();
				} 
			});
		});

		$("#select-country").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>pr/relation/get_countries',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.fin_country_id,
							"text" : value.fst_country_name
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

		$("#select-country").change(function(event){
			event.preventDefault();
			$('#select-provinces').val(null).trigger('change');
			$("#select-provinces").select2({
				width: '100%',
				ajax: {
					url: '<?=site_url()?>pr/relation/get_provinces/'+$("#select-country").val(),
					dataType: 'json',
					delay: 250,
					processResults: function (data){
						items = [];
						data = data.data;
						$.each(data,function(index,value){
							items.push({
								"id" : value.fst_kode,
								"text" : value.fst_nama
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

		$("#select-provinces").change(function(event){
			event.preventDefault();
			$('#select-district').val(null).trigger('change');
			$("#select-district").select2({
				width: '100%',
				ajax: {
					url: '<?=site_url()?>pr/relation/get_district/'+$("#select-provinces").val(),
					dataType: 'json',
					delay: 250,
					processResults: function (data){
						items = [];
						data = data.data;
						$.each(data,function(index,value){
							items.push({
								"id" : value.fst_kode,
								"text" : value.fst_nama
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

		$("#select-district").change(function(event){
			event.preventDefault();
			$('#select-subdistrict').val(null).trigger('change');
			$("#select-subdistrict").select2({
				width: '100%',
				ajax: {
					url: '<?=site_url()?>pr/relation/get_subdistrict/'+$("#select-district").val(),
					dataType: 'json',
					delay: 250,
					processResults: function (data){
						items = [];
						data = data.data;
						$.each(data,function(index,value){
							items.push({
								"id" : value.fst_kode,
								"text" : value.fst_nama
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

		$("#select-subdistrict").change(function(event){
			event.preventDefault();
			$('#select-village').val(null).trigger('change');
			$("#select-village").select2({
				width: '100%',
				ajax: {
					url: '<?=site_url()?>pr/relation/get_village/'+$("#select-subdistrict").val(),
					dataType: 'json',
					delay: 250,
					processResults: function (data){
						items = [];
						data = data.data;
						$.each(data,function(index,value){
							items.push({
								"id" : value.fst_kode,
								"text" : value.fst_nama
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

		$("#select-custpricing").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>pr/relation/get_cust_pricing_group',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.fin_cust_pricing_group_id,
							"text" : value.fst_cust_pricing_group_name
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

        $("#select-relationId").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>pr/relation/get_relationsPrinted',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.fin_relation_id,
							"text" : value.fst_relation_name
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

		$("#select-relationId2").select2({
			width: '100%',
			ajax: {
				url: '<?=site_url()?>pr/relation/get_relationsPrinted',
				dataType: 'json',
				delay: 250,
				processResults: function (data){
					items = [];
					data = data.data;
					$.each(data,function(index,value){
						items.push({
							"id" : value.fin_relation_id,
							"text" : value.fst_relation_name
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


        $("#btnProcess").click(function(event) {
            event.preventDefault();
            App.blockUIOnAjaxRequest("Please wait while processing data.....");
            //data = new FormData($("#frmBranch")[0]);
            data = $("#rptRelations").serializeArray();
            url = "<?= site_url() ?>report/relations/process";
            
            // $("iframe").attr("src",url);
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
                                        alert('OK');
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
                        data = JSON.stringify(resp.data);
                        // $("#fin_branch_id").val(data.insert_id);
                        // 
                        //Clear all previous error
                        $(".text-danger").html("");
                        url = "<?= site_url() ?>report/relations/generateexcel";
                        //alert(url);
                        //$("iframe").attr("src",url);
                        $("#rptRelations").attr('action', url);
                        $("#rptRelations").attr('target', 'rpt_iframe');
                        $("#rptRelations").submit();
                        $("a#toggle-window").click();
                        // Change to Edit mode
                        // $("#frm-mode").val("EDIT"); //ADD|EDIT
                        // $('#fst_branch_name').prop('readonly', true);
                        // updateIFrame(resp.data);

                    }
                },
                error: function(e) {
                    alert('error : ' + e);
                    $("#result").text(e.responseText);
                    console.log("ERROR : ", e);
                    $("#btnProcess").prop("disabled", false);
                }
            });
        });

        $("#btnExcel").click(function(event) {
            event.preventDefault();
            App.blockUIOnAjaxRequest("Please wait while downloading excel file.....");
            //data = new FormData($("#frmBranch")[0]);
            resp = $("#rptRelations").serializeArray();
            url = "<?= site_url() ?>report/relations/process";
            

            data = JSON.stringify(resp);
            // $("#fin_branch_id").val(data.insert_id);
            
            //Clear all previous error
            $(".text-danger").html("");
            url = "<?= site_url() ?>report/relations/generateexcel/0";
            //alert(url);
            //$("iframe").attr("src",url);
            $("#rptRelations").attr('action', url);
            $("#rptRelations").attr('target', 'rpt_iframe');
            $("#rptRelations").submit();
            $("a#toggle-window").click();

        });        
    });

</script>

