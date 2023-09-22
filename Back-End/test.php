<?php

// include 'config.php';

// $db = db();


// echo json_encode($db->query('SELECT * FROM cards')->fetch_all());
// echo json_encode(getAllData($db)['cards']);
// echo json_encode(hitungUmurDenganBulan('31/12/2003'))

// $db = db();

// echo json_encode(getDataByRow('citys', 'kabupaten', $db));

// $res = $db->query('SELECT * FROM citys')->fetch_all(MYSQLI_ASSOC);
// echo json_encode($res);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="form-control">
        <label for="tanggal">Tanggal</label>
        <input type="number" placeholder="Tanggal" id="tanggal" name="tanggal">
        <label for="bulan">Bulan</label>
        <input type="number" placeholder="Bulan" id="bulan" name="bulan">
        <label for="tahun">Tahun</label>
        <input type="number" placeholder="Tahun" id="tahun" name="tahun">
    </div>
</body>
</html>