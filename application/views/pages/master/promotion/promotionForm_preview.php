<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="shortcut icon" href="">
        <meta name="viewport" content="width=device-width, minimum-scale=1, initial-scale=1">
    </head>
    <body onload="window.print()">
        <div class="container isi">
            <div class="row print">
                <div class="col-sm-3" id="foto">
                </div>
                <div class="col-sm-9" id="data">
                    <table class="table siswa">
                        <tbody>
                            <tr>
                                <td width="150px">NISN</td>
                                <td width="5px">:</td>
                                <td><?php echo $mspromo->fst_promo_name; ?></td>
                            </tr>
                            <tr>
                                <td width="150px">Nama</td>
                                <td width="5px">:</td>
                                <td><?php echo $mspromo->fst_promo_name; ?></td>
                            </tr>
                            <tr>
                                <td width="150px">Tanggal Lahir</td>
                                <td width="5px">:</td>
                                <td><?php echo $mspromo->fdt_start; ?></td>
                            </tr>
                            <tr>
                                <td width="150px">Jenis Kelamin</td>
                                <td width="5px">:</td>
                                <td><?php echo $mspromo->fdt_start; ?></td>
                            </tr>
                            <tr>
                                <td width="150px">Kelas</td>
                                <td width="5px">:</td>
                                <td><?php echo $mspromo->fdt_start; ?></td>
                            </tr>
                            <tr>
                                <td width="150px">Program Keahlian</td>
                                <td width="5px">:</td>
                                <td><?php echo $mspromo->fdt_start; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tc">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="14.285%">Fc Kartu Keluarga</th>
                            <th width="14.285%">Fc KTP Ayah</th>
                            <th width="14.285%">Fc KTP Ibu</th>
                            <th width="14.285%">Fc KIP / KPS</th>
                            <th width="14.285%">Fc SKTM</th>
                            <th width="14.285%">Fc Ijazah</th>
                            <th width="14.285%">Fc SKHUN</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
    <script type="text/javascript">
    document.addEventListener("DOMContentLoaded",function(event){
        //alert("TEST PREVIEW");
        //window.print();
    });
    </script>

</html>