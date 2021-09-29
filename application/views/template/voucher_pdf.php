<!DOCTYPE html>
<html>
	<head>		
		<title><?= $title?></title>		
		<style>		
			body{
				font-size:10pt;
				font-family:consolas;				
			}
			/*@page { size: 215.9mm 139.7mm potrait} /* output size */
			/*body.nota .sheet { width: 215.9mm; height: 139.7mm } /* sheet size */
			/*@media print { body.nota { width: 139.7mm } } /* fix for Chrome */
			.row{
				clear:both;
				width:100%;
				padding-left:0.1mm;
				padding-right:0.1mm;				
			}
			.row .col{
				float:left;
			}	
			.row .col-right{
				float:right;
			}		

			.italic{
				font-style:italic;
			}
			.bold{
				font-weight:bold;
			}
			.underline{
				text-decoration: underline;
			}
			.table{
				width:100%;
				border-spacing:0px;
    			border-collapse: separate;
			}
			.table thead tr th{
				font-size:10pt;
				/*border-bottom:solid 1px #000;*/
				border:1px dotted #000;
				text-align:left;
			}
			.table tr th.text-right{
				text-align:right;
			}
			.table tr th.text-center{
				text-align:center;
			}

			.table tr td{				
				border-bottom:solid 1px #999;
			}
			.table tr.have-detail td{				
				border-bottom:solid 0px #999;
			}

			.table tr.total td{
				font-size:10pt;
			}	

			.text-right{
				text-align:right;
			}
			div.assignment-total{
				position:fixed;
				bottom:0px;
			}
			table.assignment-total{
				margin-top:20px;
				border:1px dotted #000;
				width:100%;
				
			}
			table.assignment-total tr td{
				border:none;
			}
			table.assignment-total tr td.asign-col{
				height:50px;				
			}

			div.assignment{
				position:fixed;
				bottom:0px;
			}

			table.assignment{
				margin-top:20px;
				border:0px dotted #000;
				width:100%;
				
			}
			table.assignment tr td{
				border:none;
				text-align:center;
			}
			table.assignment tr td.asign-col{
				height:50px;				
			}
			.inline{
				float:left;
			}	

			.notes{
				text-align:justify;
				font-style:italic;
				margin-top:10px;
				font-size:8pt;
			}
			


		</style>
	</head>	
	<body>
		<!--<htmlpagefooter name="myFooter">
			<table width="100%">
				<tr>
					<td width="100%" align="right">Hal: {PAGENO}/{nbpg}</td>
				</tr>
			</table>
		</htmlpagefooter>
		<sethtmlpagefooter name="myFooter" value="ON" page="ALL"/>-->
		{PAGE_CONTENT}			
	</body>
</html>
