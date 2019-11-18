<script type="text/javascript">
    $(function(){ 
        $("#tblList").on("click",".btn-delete",function(e){
            t= $("#tblList").DataTable();
            var trRow = $(this).parents('tr');            
			var data = t.row(trRow).data();
            //console.log(data);
            deleteAjax(data.fin_lpbgudang_id,0);
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

		var url =  "<?= site_url() ?>tr/purchase/invoice/delete/" + finId;
		$.ajax({
			url:url,
			method:"POST",
			data:dataSubmit,
		}).done(function(resp){
			if (resp.message != ""){
				alert(resp.message);
			}

			if(resp.status == "SUCCESS"){
                window.location.href = "<?=site_url()?>tr/gudang/Penerimaan_pembelian/";
			}

        });
    }        
</script>