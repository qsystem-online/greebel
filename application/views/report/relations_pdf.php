<!DOCTYPE html>
<html>
<head>
  <title>Report Master Relations</title>
  <style type="text/css">

@page {
        margin: 0px 0px;
    }
    header {
        position: fixed;
        top: 0px;
        left: 0px;
        right: 0px;
        height: 50px;

        /** Extra personal styles **/
        background-color: #03a9f4;
        color: white;
        text-align: center;
        line-height: 35px;
    }

    footer {
        position: fixed; 
        bottom: 0px; 
        left: 0px; 
        right: 0px;
        height: 50px; 
        /** Extra personal styles **/
        background-color: #03a9f4;
        color: white;
        text-align: center;
        line-height: 35px;
    }
    
    body{
        margin-top: 2cm;
        margin-left: 2cm;
        margin-right: 2cm;
        margin-bottom: 2cm;
    }

    h3{
        font-family: arial;
        text-align: center;
    }

    #outtable{
      padding: 20px;
      border:2px solid #e3e3e3;
      width: 100%;
      table-layout: auto;
      border-radius: 10px;
    }

    .id{
      width: 80px;
    }
 
    .short{
      width: 40px;
    }
 
    .normal{
      width: auto;
    }

    table{
      border-collapse: collapse;
      font-family: arial;
      color:#5E5B5C;
      table-layout: auto;
      width: 100%;
      font-size:10pt;
    }
 
    thead th{
      text-align: left;
      padding: 10px;
    }
 
    tbody td{
      border-top: 3px solid #5E5B5C;
      padding: 10px;
    }
 
    tbody tr:nth-child(even){
      background: #F6F5FA;
    }
 
    tbody tr:hover{
      background: #5E5B5C;
    }
  </style>
</head>
<body>

    <header>            
      <img src= "<?= base_url() ?>/assets/app/users/avatar/avatar_1.jpg" height="60"/>
        Our Code World
    </header>

    <footer>
        Copyright &copy; <?php echo date("Y"); ?>  Page 
        <script type="text/php">
            $this->get_canvas()->page_script('
              $font = $fontMetrics->getFont("Arial", "bold");
              $this->get_canvas()->text(770, 580, "Page $PAGE_NUM of $PAGE_COUNT", $font, 10, array(0, 0, 0));
            ');                
        </script>
    </footer>

  <h3 text-align= "center">Table Master Relations</h3>
  <link href="style.css" type="text/css" rel="stylesheet" />
	<table cellspacing='0'>

	<div id="outtable">
	  <table>
	  	<thead>
	  		<tr>
	  			<th class="short">#</th>
	  			<th class="id">Relation ID</th>
	  			<th class="normal">Relation Name</th>
	  			<th class="normal">Relation Type</th>
	  		</tr>
	  	</thead>
	  	<tbody>
        <?php $no=0;$no<=100;$no++; ?>
        <?php foreach($datas as $data): ?>
        <?php
            switch ($data["RelationType"]) {
              case '1';
                $relationType = "Customer";
                break;
              case '2';
                $relationType = "Supplier/Vendor";
                break;
              case '3';
                $relationType = "Expedisi";
                break;
              case '1,2';
                $relationType = "Customer, Supplier/Vendor";
                break;
              case '1,3';
                $relationType = "Customer, Expedisi";
                break;
              case '2,1';
                $relationType = "Supplier/Vendor, Customer";
                break;
              case '2,3';
                $relationType = "Supplier/Vendor, Expedisi";
                break;
              case '3,1';
                $relationType = "Expedisi, Customer";
                break;
              case '3,2';
                $relationType = "Expedisi, Supplier/Vendor";
                break;
              case '1,2,3';
                $relationType = "Customer, Supplier/Vendor, Expedisi";
                break;
              case '1,3,2';
                $relationType = "Customer, Expedisi, Supplier/Vendor";
                break;
              case '2,1,3';
                $relationType = "Supplier/Vendor, Customer, Expedisi";
                break;
              case '2,3,1';
                $relationType = "Supplier/Vendor, Expedisi, Customer";
                break;
              case '3,1,2';
                $relationType = "Expedisi, Customer, Supplier/Vendor";
                break;
              case '3,2,1';
                $relationType = "Expedisi, Supplier/Vendor, Customer";
                break;
            }
            $data['RelationType'] = $relationType;
        ?>
          <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $data['RelationId']; ?></td>
            <td><?php echo $data['RelationName']; ?></td>
            <td><?php echo $data['RelationType']; ?></td>
          </tr>
        <?php $no++; ?>
        <?php endforeach; ?>
	  	</tbody>
	  </table>
	 </div>
</body>
</html>