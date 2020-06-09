<iframe id="frameVoucher" src="" style="display:unset"></iframe>
<script type="text/javascript">
	var frameVoucher = {
		print:function(url){
			window.open(url,"_blank","width=200,height=100,menubar=0,toolbar=0");
			return;
			$.blockUI({ message:"please wait..."});
			$("#frameVoucher").attr("src",url);
			
		}
	}
	$(function(){
		$("#frameVoucher").on("load",function(e){  
			$.unblockUI();              
			$("#frameVoucher").get(0).contentWindow.print();
		});
	});
</script>