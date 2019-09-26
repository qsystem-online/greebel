<div id="mdlPrint" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width:250px">
		<!-- Modal content-->
		<div class="modal-content" style="border-top-left-radius:10px;border-top-right-radius:10px;border-bottom-left-radius:5px;border-bottom-right-radius:5px;">
			<div class="modal-header" style="padding:5px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 5px;border-top-right-radius: 10px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Print")?></h4>
			</div>
			<div class="modal-body">
				<div class="row">
                    <div class="col-md-12" >
                        <div style="border:1px inset #f0f0f0;border-radius:5px;padding:10px">
                            <fieldset style="padding:10px">
                                <legend>Mode:</legend>
                                <input type="radio" name="mode" value="download" checked><label style="margin-left:10px;font-size:12pt"> Download </label><br>
                                <input type="radio" name="mode" value="preview"><label style="margin-left:10px;font-size:12pt"> Preview </label>  <br>                            
                            </fieldset>
                            <div style="width:100%;padding:5px" class="text-center">
                                <button id="btnLayout" class="btn btn-primary btn-sm text-center" style="width:100%;margin-bottom:5px;"><i class="fa fa-columns"></i> Layout</button>
                                <button id="btnSubmitPrinter" class="btn btn-primary btn-sm text-center" style="width:100%"><i class="fa fa-print"></i> Print</button>
                            </div>
                        </div>
                    </div>
				</div>				
			</div>
		</div>
	</div>
</div>

<div id="mdlPrintLayout" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width:400px">
		<!-- Modal content-->
		<div class="modal-content" style="border-top-left-radius:10px;border-top-right-radius:10px;border-bottom-left-radius:5px;border-bottom-right-radius:5px;">
			<div class="modal-header" style="padding:5px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 5px;border-top-right-radius: 10px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Layout")?></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
                        <table id="tblLayout" class="display table table-bordered table-hover table-striped nowarp row-border" style="width:100%"></table>
                    </div>                                     
				</div>				
			</div>
		</div>
	</div>
</div>

<form id="frmSubmitPrint" style="display:none" target="_blank" method="POST">
    <input type="text" name = "<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">			
    <input type="text" id="layoutColumn" name="layoutColumn" />
    <input type="text" id="printMode" name="printMode" value="download" />
    <input type="submit" value="submit"/>
</form>

<script type="text/javascript">
    
	layoutColumn = [
        {column: "Coloumn 1",hidden:false,id:"fst_column1"},
        {column: "Coloumn 2",hidden:true,id:"fst_column2"},
    ];
    
    MdlPrint = {
        url: "",
        showPrint : function(colLayout,url){
            layoutColumn = colLayout;
            
            if ( $.fn.dataTable.isDataTable( '#tblLayout' ) ) {
                $("#tblLayout").DataTable().destroy();
            }

            $("#tblLayout").DataTable({
                columns: [
                    { title: "Kolom",width:"80%",data:"column" },
                    { title: "Hiden",className: 'dt-center',width:"20%",data:"hidden",
                        render: function(data, type, row) {
                            if (data == false) {
                                return '<input type="checkbox" class="chk-hidden editor-active">';
                            } else {
                                return '<input type="checkbox" class="chk-hidden editor-active" checked>';
                            }
                            return data;
                        }
                    },
                ],
                data:layoutColumn,
                paging:false,
                ordering:false,
                info:false,
                filter:false,
            });
            
            this.url = url;

            $("#mdlPrint").modal("toggle");
            
        }
    }

    $(function () {
        //$("#tblLayout").DataTable();

        $("#btnLayout").click(function(e){
            e.preventDefault();
            if ( $.fn.dataTable.isDataTable( '#tblLayout' ) ) {
                $("#tblLayout").DataTable().destroy();
            }

            

            $("#tblLayout").DataTable({
                columns: [
                    { title: "Kolom",width:"80%",data:"column" },
                    { title: "Hiden",className: 'dt-center',width:"20%",data:"hidden",
                        render: function(data, type, row) {
                            if (data == false) {
                                return '<input type="checkbox" class="chk-hidden editor-active">';
                            } else {
                                return '<input type="checkbox" class="chk-hidden editor-active" checked>';
                            }
                            return data;
                        }
                    },
                ],
                data:layoutColumn,
                paging:false,
                ordering:false,
                info:false,
                filter:false,
            });

            $('#mdlPrintLayout').modal('toggle');
        });

        $("#tblLayout").on("change",".chk-hidden",function(e){            
            t = $("#tblLayout").DataTable();
            var trRow = $(this).parents('tr');
            data = t.row(trRow).data();
            data.hidden = $(this).prop("checked");
            t.row(trRow).data(data);
        });


        $("#btnTest").click(function(e){
            e.preventDefault();
            t = $("#tblLayout").DataTable();
            console.log(t.rows().data());
        });

        $("#btnSubmitPrinter").click(function(e){
            e.preventDefault();
            t = $("#tblLayout").DataTable();
            data = t.rows().data();
            newData = [];
            $.each(data,function(i,v){
                newData.push(v);
            });            
            $("#layoutColumn").val(JSON.stringify(newData));
            $("#frmSubmitPrint").attr("action",MdlPrint.url);
            $("#frmSubmitPrint").submit(); 


        });
    });

	function showItemGroup(leafOnly,callback){
		$("#mdlItemGroup").modal({
			backdrop:"static",
		});
		g_LeafOnly = leafOnly;
		selected_callback = callback;
    }
    
   
</script>