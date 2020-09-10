<link rel="stylesheet" href="<?= base_url() ?>bower_components/jstree/dist/themes/default/style.min.css" />
<style>
.vakata-context, .vakata-context ul{
	z-index:1060;
}
</style>
<div id="mdlItemGroup" class="modal fade" role="dialog">
	<div class="modal-dialog" style="width:800px">
		<!-- Modal content-->
		<div class="modal-content" style="border-top-left-radius:10px;border-top-right-radius:10px;border-bottom-left-radius:5px;border-bottom-right-radius:5px;">
			<div class="modal-header" style="padding:5px;background-color:#3c8dbc;color:#ffffff;border-top-left-radius: 5px;border-top-right-radius: 10px;">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?=lang("Group Item")?></h4>
			</div>
			<div class="modal-body">
				<div class="pull-right">
					<label>Search : </label>
					<input type="text" id="jstree_group_q" class=""/> 
				</div>
				<div class="pull-left">
					<button id="btnCreateRoot" class="btn btn-primary"><?=lang("Group Baru")?></button>
				</div>
				<div style="clear:both"></div>
				<div id="jstree_group"></div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var selected_callback;
	var g_LeafOnly;    
    var g_ReadOnly = <?= $readOnly ?>;
    var g_ContextMenu = "";

    $(function () {
        if (g_ReadOnly == 0){
            g_ContextMenu ="contextmenu";
        }
        $.ajax({
            url:"<?=site_url() ?>master/group_item/data_tree",

        }).done(function(resp){
            consoleLog(resp);
            /*
            $('#jstree_group').jstree({
                core:{
                    data:resp
                },
            });
            */
             /*
                data:[
                    { id: "1",parent:"#",text:"Root 1",
                        state: {opened:false,disabled:false,selected  : false},
                        li_attr: {},
                        a_attr: {},
                    },
                    { id: "1.1",parent:"1",text:"Root 1.1",
                        state: {opened:false,disabled:false,selected  : false},
                        li_attr: {},
                        a_attr: {},
                    },
                    { id: "2",parent:"#",text:"Root 2",
                        state: {opened:false,disabled:false,selected  : false},
                        li_attr: {},
                        a_attr: {},
                    },
                    { id: "3",parent:"#",text:"Root 3",
                        state: {opened:false,disabled:false,selected  : false},
                        li_attr: {},
                        a_attr: {},
                    },
                ],
            */
            $('#jstree_group').jstree(true).settings.core.data = resp;
            $('#jstree_group').jstree(true).refresh();
        });


        $("#btnCreateRoot").click(function(e){
            e.preventDefault();

            if (g_ReadOnly == 1){
                return alert("Tidak dapat add");
            }           
           
            /*
            $('#jstree_group').jstree(true).create_node ({
                par: "#", 
                node:"test"                
            });
            */
            id = $('#jstree_group').jstree(true).create_node ("#","new node");
            //alert(id);

            node = $('#jstree_group').jstree(true).get_node(id,false);
            //consoleLog(node.node);

            $('#jstree_group').jstree(true).edit(node);
            

        });

        //,icon: "glyphicon glyphicon-file"
        $('#jstree_group').jstree({
             core:{
                
                 "check_callback" : true,
             },


             plugins:['search',g_ContextMenu,'wholerow'],
             contextmenu:{
                 items:function($node){
                    var tree = $("#jstree_group").jstree(true);
                    return {
                        "Create": {
                            "separator_before": false,
                            "separator_after": false,
                            "label": "Create",
                            "action": function (obj) {
                                // cek if node already used
                                $.ajax({
                                    url:"<?=site_url() ?>master/group_item/is_node_used/" + $node.id,
                                }).done(function(resp){
                                    if(resp.status == "SUCCESS"){
                                        result = resp.data.result;
                                        consoleLog(result);

                                        if (result ==  true){
                                            //in used need confirmation
                                            $.confirm({
                                                title: '<?= lang("Peringatan! Group terpakai")?>',
                                                content: '<?= lang("Item dengan group ini akan di pindah ke group yang baru ?")?>',
                                                buttons: {
                                                    confirm: function () {																
                                                        $node = tree.create_node($node);
                                                        tree.edit($node);
                                                    },
                                                    cancel: function () {
                                                    },
                                                }						
                                            });	
                                            
                                        }else{
                                            //not used
                                            $node = tree.create_node($node);
                                            tree.edit($node);
                                        }
                                    }
                                });

                                
                            }
                        },
                        "Rename": {
                            "separator_before": false,
                            "separator_after": false,
                            "label": "Rename",
                            "action": function (obj) { 
                                tree.edit($node);
                            }
                        },                         
                        "Remove": {
                            "separator_before": false,
                            "separator_after": false,
                            "label": "Remove",
                            "action": function (obj) { 
                                consoleLog($node);
                                $node["<?=$this->security->get_csrf_token_name()?>"] = "<?=$this->security->get_csrf_hash()?>";
                                
                                $.ajax({
                                    url:"<?=site_url() ?>master/Group_item/delete_data_tree",
                                    //method:"POST",
                                    data:$node,
                                }).done(function(resp){
                                    if (resp.message != ""){
                                        alert(resp.message);
                                    }

                                    if (resp.status == "SUCCESS"){
                                        tree.delete_node($node);
                                    }
                                });
                                
                                //alert("delete")
                            }
                        }
                    };
                 }
             }
        }).bind("dblclick.jstree", function (event) {
            //consoleLog(event);
            //consoleLog(node);
            //consoleLog($(this));
            var arrTmp = $(event.target).closest("li");
            //var data = node.data("jstree");
            id = arrTmp[0].id;            
            node = $('#jstree_group').jstree(true).get_node(id,false);

			if (g_LeafOnly){
				if ($('#jstree_group').jstree(true).is_leaf(node)){ 
					$('#mdlItemGroup').modal('toggle');               
					selected_callback(node);
            	}
			}else{
				$('#mdlItemGroup').modal('toggle');               
				selected_callback(node);
			}
            
            // Do some action
        });

        
        $("#jstree_group").on('changed.jstree', function (e, data) {            
            //consoleLog(data);
            //consoleLog($('#jstree_group').jstree(true).is_leaf(data.node));
        });

        $("#jstree_group").on('rename_node.jstree', function (e, data) {            
            data = data.node;
            data["<?=$this->security->get_csrf_token_name()?>"] = "<?=$this->security->get_csrf_hash()?>";

            consoleLog(data);
            //consoleLog($('#jstree_group').jstree(true).is_leaf(data.node));
            $.ajax({
                url:"<?=site_url() ?>master/group_item/update_data_tree",
                method:"POST",
                data:data,
            }).done(function(resp){
                consoleLog(resp);
                $('#jstree_group').jstree(true).set_id(data,resp.data.id);

            });
        });
        /*
        $("#jstree_group").on('delete_node.jstree', function (e, data) {  
            e.preventDefault();          
            consoleLog(data);
        });
        */
        /*
        $("#jstree_group").on('create_node.jstree', function (e, data) {            
            //consoleLog(data);
            //consoleLog($('#jstree_group').jstree(true).is_leaf(data.node));
        });
        */

        var to = false;
        $('#jstree_group_q').keyup(function () {
            if(to) { 
                clearTimeout(to); 
            }
            to = setTimeout(function () {
                var v = $('#jstree_group_q').val();
                $('#jstree_group').jstree(true).search(v);
            }, 250);

        });
    });

	function showItemGroup(leafOnly,callback){
        if (g_ReadOnly == true){
            $("#btnCreateRoot").hide();
        }else{
            $("#btnCreateRoot").show();
        }
		$("#mdlItemGroup").modal({
			backdrop:"static",
		});    
		g_LeafOnly = leafOnly;
    	selected_callback = callback;
	}
		
</script>

<!-- jstree -->
<script src="<?= base_url() ?>bower_components/jstree/dist/jstree.min.js"></script>