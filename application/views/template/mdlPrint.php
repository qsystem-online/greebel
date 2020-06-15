<iframe id="frameVoucher" style="display:none"></iframe>
<script type="text/javascript">
	var frameVoucher = {
		print:function(url){
			var left = (screen.width - 800) / 2;
			window.open(url,"_blank","width=800,height=550,menubar=0,toolbar=0,top=50,left="+left);
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