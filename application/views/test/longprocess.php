<html>
<head>
    <script src="http://localhost/greebel/bower_components/jquery/dist/jquery.min.js"></script>
</head>

<body>
    <button id="btnProcess">Process</button>
    <br>
    <label>Progress :<span id="lblProgress"></span></label>
</body>

<script type="text/javascript">

var myProgress;
var process = 0;

$(function(){       
    $("#btnProcess").click(function(e){
        e.preventDefault();
        $.ajax({
            url:"<?=site_url()?>longprocess/process",
            method:"GET",
        }).done(function(resp){
            console.log(resp);
            process = 0;
        });
        process = 1;
        myProgress = setInterval(showProgress,1000);
    });
});

function showProgress(){
    console.log("Show Progress");
    if(process == 0){
        clearInterval(myProgress);
        return;        
    }

    $.ajax({
        url:"<?=site_url()?>longprocess/progress",
        //url:"http://www.qsystem-online.com",
        method:"GET",
    }).done(function(resp){
        //console.log(resp);
        $("#lblProgress").text(resp);
    });

}

</script>

</html>