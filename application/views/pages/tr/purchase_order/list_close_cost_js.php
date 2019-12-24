<?php echo $mdlPopupNotes ?>
<script type="text/javascript">
	function formatDetailCost(finPOId,row,trRow){		
		
		var respAjx;
		
		App.getValueAjax({
			model:"trpo_model",
			func:"getSummaryCostPO",
			params:[finPOId],
			async:true,
			callback:function(resp){
				respAjx = resp;
				console.log(resp);
				var result ='<table style="width:100%;background-color: #e1fdcc" class="table table-bordered row-border compact nowarp text-primary">';
				var arrCost = resp;
				$.each(arrCost,function(i,v){
					result += "<tr>";
					result += '<td style="width:20%">'+ v.fst_purchasecost_no +'</td>';
					result += '<td style="width:20%">'+ v.fst_curr_code + ':' + App.money_format(v.fdc_total) +'</td>';
					result += '<td>'+ v.fst_memo +'</td>';
					result += '</tr>';
				});								
				result += '</table>';


				row.child(result).show();
				trRow.addClass('shown');		
			}
		});	
		
		//row.child(formatDetailCost(data.fin_po_id)).show();
		//trRow.addClass('shown');

		/*
		console.log(respAjx);

		
		//return respAjx;
		*/
	}

    $(function(){
		$('#option-data').change(function(e){
			t = $('#tblList').DataTable();
			t.ajax.reload();
		});

		$('#tblList').on('preXhr.dt', function ( e, settings, data ) {
		 	//add aditional data post on ajax call
			 //data.sessionId = "TEST SESSION ID";
			 //data.optionSearch = $('#selectSearch').val();
			 data.optionData = $('#option-data').val();
		});

        $("#tblList").on("click",".cost_detail",function(e){
			e.preventDefault();
            t= $("#tblList").DataTable();
			var trRow = $(this).parents('tr');            
			//var tr = $(this).closest('tr');

			var data = t.row(trRow).data();			
			var row = t.row(trRow);
		

            if ( row.child.isShown() ) {
				// This row is already open - close it
				row.child.hide();
				trRow.removeClass('shown');
			}else {
				// Open this row
				formatDetailCost(data.fin_po_id,row,trRow);

				//row.child("asdasdasdasdasdasdasd");
			//	trRow.addClass('shown');

				//row.child(formatDetailCost(data.fin_po_id)).show();
				//trRow.addClass('shown');
			}


		});
		


		$("#tblList").on("change",".isCostCompleted",function(e){
			e.preventDefault;			
			var element = $(this);
			t= $("#tblList").DataTable();            
			var trRow = $(this).parents('tr');            
			var data = t.row(trRow).data();
			var isChecked = $(this).prop("checked");
			$(this).prop("checked",!isChecked);

			var conf;
			if (isChecked){
				conf = confirm("<?= lang("Confirm, tutup biaya ?") ?>");
			}else{
				conf = confirm("<?= lang("Confirm, batalkan tutup biaya ?") ?>");
			}
			if (conf == false){
				return;
			}
			

			var callback = function(respNotes){				
				dataPost = {
					[SECURITY_NAME] : SECURITY_VALUE,
					fin_po_id : data.fin_po_id,
					fst_closed_note:respNotes
				};
				//dataPost[SECURITY_NAME] = SECURITY_VALUE;
				App.blockUIOnAjaxRequest();
				$.ajax({
					url:"<?=site_url()?>tr/purchase_order/process_closing_cost/" + +isChecked,
					data:dataPost,
					method:"POST"
				}).done(function(resp){
					if (resp.message != ""){
						alert(resp.message);
					}

					if (resp.status == "SUCCESS"){
						element.prop("checked",isChecked);
					}					
				});
			}

			callback("");

			//if (isChecked){
			//	MdlPopupNotes.showNotes("",callback);
			//}else{
			//	callback("");
			//}
		});
		
		
	});	

    function onDrawTable(){
        $('.btn-delete').confirmation({
            //rootSelector: '[data-toggle=confirmation]',
            title: "<?=lang('Hapus data ini ?')?>",
            rootSelector: '.btn-delete',
            // other options
        });	
    }


    function deleteAjax(finId,confirmDelete){		
		if (confirmDelete == 0){
			MdlEditForm.saveCallBack = function(){
				deleteAjax(finId,1);
			};		
			MdlEditForm.show();
			return;
		}

		var dataSubmit = [];		
		dataSubmit.push({
			name : "<?=$this->security->get_csrf_token_name()?>",
			value: "<?=$this->security->get_csrf_hash()?>",
		});
		dataSubmit.push({
			name : "fin_user_id_request_by",
			value: MdlEditForm.user
		});
		dataSubmit.push({
			name : "fst_edit_notes",
			value: MdlEditForm.notes
		});

		var url =  "<?= site_url() ?>tr/purchase_order/delete/" + finId;
		$.ajax({
			url:url,
			method:"POST",
			data:dataSubmit,
		}).done(function(resp){
			if (resp.message != ""){
				alert(resp.message);
			}

			if(resp.status == "SUCCESS"){
                window.location.href = "<?=site_url()?>tr/purchase_order/";
			}

        });
    }        
</script>