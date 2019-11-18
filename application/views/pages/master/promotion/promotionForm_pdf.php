<!DOCTYPE html>
<html>
<head>
  <title>Sales Promotion</title>
  <style type="text/css">

@page {
        margin: 0px 0px;
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
        margin-top: 1cm;
        margin-left: 0.5cm;
        margin-right: 1cm;
        margin-bottom: 1cm;
    }

    h3{
        font-family: Tahoma;
        text-align: center;
    }

    #outtable{
      padding: 1px;
      border:0px solid #000000;
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

    .large{
      width: 250px;
    }

    table{
      border-collapse: collapse;
      font-family: Tahoma;
      table-layout: auto;
      width: 100%;
      font-size:10pt;
    }
 
    thead th{
      text-align: left;
      padding: 0px;
    }
 
    tbody td{
      border-top: 1px solid #000000;
      padding: 0px;
    }
  </style>
</head>
<body onload="window.print()">

    <header>            

    </header>

    <footer>
        <script type="text/php">
            $this->get_canvas()->page_script('
              $font = $fontMetrics->getFont("Tahoma", "bold");
              $this->get_canvas()->text(770, 580, "Page $PAGE_NUM of $PAGE_COUNT", $font, 10, array(0, 0, 0));
            ');                
        </script>
    </footer>

  <h3>Mutasi Antar Gudang</h3>
  <link href="style.css" type="text/css" rel="stylesheet" />
	<table cellspacing='0'>
	  	<thead>
        <tr>
          <th class="normal">Promo Name</th>
	  			<th class="normal">: <?php echo $mspromo->fst_promo_name; ?></th>
	  		</tr>
        <tr>
          <th class="normal">Free Item</th>
	  			<th class="normal">: <?php echo $mspromo->fst_item_name; ?></th>
	  		</tr>
        <tr>
	  			<th class="normal">Qty</th>
	  			<th class="normal">: <?php echo $mspromo->fdb_promo_qty; ?></th>
	  			<th class="normal">Unit</th>
	  			<th class="normal">: <?php echo $mspromo->fst_promo_unit; ?> </th>
	  		</tr>
        <tr>
          <th class="normal">CashBack</th>
	  			<th class="normal">: <?php echo number_format($mspromo->fdc_cashback); ?></th>
	  		</tr>
        <tr>
          <th class="normal">Other Item</th>
	  			<th class="normal">: <?php echo $mspromo->fst_other_prize; ?></th>
	  		</tr>
        <tr>
	  			<th class="normal">Start Period</th>
	  			<th class="normal">: <?php echo $mspromo->fdt_start; ?></th>
	  			<th class="normal">End Period</th>
	  			<th class="normal">: <?php echo $mspromo->fdt_end; ?> </th>
	  		</tr>
	  	</thead>
    </div>
    <div id="outtable">
	  <table>
    <h3>Syarat items</h3>
	  	</thead>
        <tr>
          <th class="normal">No</th>
          <th class="normal">Type</th>
          <th class="large">Name</th>
          <th class="normal">Qty</th>
          <th class="normal">Unit</th>
        </tr>
	  	<tbody>
        <?php $no=0;$no<=100;$no++; ?>
        <?php foreach($promoTerms as $data): ?>
        <tr>
          <td><?php echo $no; ?></td>
          <td><?php echo $data->fst_item_type; ?></td>
          <td><?php echo $data->ItemTerms; ?></td>
          <td><?php echo $data->fdb_qty; ?></td>
          <td><?php echo $data->fst_unit; ?></td>
        </tr>
        <?php $no++; ?>
        <?php endforeach; ?>  
	  	</tbody>
	  </table>
    </div>
    <h3>Participants</h3>
    <div id="outtable">
	  <table>
	  	</thead>
        <tr>
          <th class="normal">No</th>
          <th class="normal">Type</th>
          <th class="normal">Name</th>
        </tr>
	  	<tbody>
        <?php $no=0;$no<=100;$no++; ?>
        <?php foreach($promoParticipants as $participant): ?>
        <tr>
          <td><?php echo $no; ?></td>
          <td><?php echo $participant->fst_participant_type; ?></td>
          <td><?php echo $participant->ParticipantName; ?></td>
        </tr>
        <?php $no++; ?>
        <?php endforeach; ?>
	  	</tbody>
	  </table>
    </div>
</body>
</html>