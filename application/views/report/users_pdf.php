<!DOCTYPE html>
<html>
<head>
  <title>Report Master Users</title>
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
 
    .short{
      width: 35px;
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

  <h3 text-align= "center">Table Master Users</h3>
  <link href="style.css" type="text/css" rel="stylesheet" />
	<table cellspacing='0'>

	<div id="outtable">
	  <table>
	  	<thead>
	  		<tr>
	  			<th class="short">#</th>
	  			<th class="normal">User ID</th>
	  			<th class="normal">Full Name</th>
	  			<th class="normal">Gender</th>
          <th class="normal">Birthdate</th>
          <th class="normal">Birthplace</th>
	  		</tr>
	  	</thead>
	  	<tbody>
        <?php $no=0;$no<=100;$no++; ?>
        <?php foreach($datas as $data): ?>
          <tr>
            <td><?php echo $no; ?></td>
            <td><?php echo $data['fin_user_id']; ?></td>
            <td><?php echo $data['fst_fullname']; ?></td>
            <td><?php echo $data['fst_gender']; ?></td>
            <td><?php echo $data['fdt_birthdate']; ?></td>
            <td><?php echo $data['fst_birthplace']; ?></td>
          </tr>
        <?php $no++; ?>
        <?php endforeach; ?>
	  	</tbody>
	  </table>
	 </div>
</body>
</html>