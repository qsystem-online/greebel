<!DOCTYPE html>
<html>
    <head>
        <!-- jQuery 3 -->
		<script src="<?=base_url()?>bower_components/jquery/dist/jquery.min.js"></script>
    </head>
    <body>
        <table id="tblReport" border="1" style="width:100%">
            <thead>
                <tr style="background-color:navy;color:white">
                    <th style="width:10%">Col 1</th>
                    <th style="width:20%;display:none">Col 2</th>
                    <th style="width:20%">Col 3</th>
                    <th style="width:30%">Col 4</th>
                    <th style="width:20%">Col 5</th>                    
                </tr>
            </thead>
            <tbody>
                <?php
                  for($i=0;$i<5000;$i++){
                      echo "<tr>
                        <td>Baris $i Col 1</td>
                        <td style='display:none'>Baris $i Col 2</td>
                        <td>Baris $i Col 3</td>
                        <td>Baris $i Col 4</td>
                        <td>Baris $i Col 5</td>                        
                      </tr>";
                  }  
                ?>
            </tbody>
            <button id="btnDownload">Download</button>
            <a id="btnDownload2">Download2</a>
            
        </table>
    </body>
    <script type="text/javascript">
    $(function(){
        $("#btnDownload").click(function(e){
            e.preventDefault();
            //alert("click");
            var data_type = 'data:application/vnd.ms-excel';
            //var data_type = '';
            var table_div = document.getElementById('tblReport');
            var table_html = table_div.outerHTML.replace(/ /g, '%20');

            var a = document.createElement('a');
            a.href = data_type + ', ' + table_html;
            a.download = 'exported_table_' + Math.floor((Math.random() * 9999999) + 1000000) + '.xls';
            a.click();                        
        });

        
    })
    </script>
</html>
