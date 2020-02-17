<html>
<head>
    <!-- jQuery 3 -->
	<script src="<?=base_url()?>bower_components/jquery/dist/jquery.min.js"></script>	
</head>
<body>
    <input type="TEXT" id="divisi" value=""/>

    <table id="table1" border="1">
        <thead>
            <tr>
                <td style="width:20px">Col1</td>
                <td style="width:150px">Col2</td>
            </tr>
        </thead>
        <tbody id="table1-body">
        </tbody>
    </table>
</body>
<script type="text/javascript">
    $(function(){
        isiTable();
        setInterval(function(){
            isiTable();
        },60000);    
    });
    function isiTable(){
        //Fungsi Ajax      
        $.ajax({
            url: "/greebel/test/loadDataTest",
            method:"GET",
            data:{divisi:$("#divisi").val()},
            dataType:"JSON",
        }).done(function(resp){
            $("#table1-body").empty();        
            $.each(resp,function(i,v){
                $("#table1-body").append("<tr><td>"+i+"</td><td><marquee behavior='slide'>Data ke " + v + "</marquee></td></tr>");
            })
        })     
    }
</script>
</html>