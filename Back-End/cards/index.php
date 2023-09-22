<?php

include '../config.php';

$data = null;
$conn = db();
if (isset($_GET['id'])) {
    $patient_id = getDataByRow('cards', 'patient_id', $conn);
    $allData = getAllData($conn);
    $data = getDataById('cards', $_GET['id'], $conn) ? getDataById('cards', $_GET['id'], $conn) : $allData['cards'][array_search($_GET['id'], $patient_id)];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cards Patient - <?= $data['name'] ?></title>
    <style>
        .container span {
            display: block;
        }
    </style>
</head>

<body>
    <?php if ($data) : ?>
        <div class="container">
            <span><b>ID Pasien </b>: <i><?= $data['patient_id'] ?></i></span>
            <span><b>Nama </b>: <i><?= $data['nama'] ?></i></span>
            <span><b>Umur </b>: <i><?= hitungUmurDenganBulan($data['tgl_lahir'])['tahun'] ?> tahun, <?= hitungUmurDenganBulan($data['tgl_lahir'])['bulan'] ?> bulan</i></span>
            <span><b>Tanggal Lahir </b>: <i><?= $data['tgl_lahir'] ?></i></span>
            <span><b>Alamat </b>: <i><?= $data['alamat'] ?></i></span>
            <span><b>RT / RW </b>: <i><?= $data['rt_rw'] ?></i></span>
            <span><b>Provinsi </b>: <i><?= json_decode($data['kelurahan'])[0] ?></i></span>
            <span><b>Kabupaten </b>: <i><?= json_decode($data['kelurahan'])[1] ?></i></span>
            <span><b>Kelurahan / Desa </b>: <i><?= json_decode($data['kelurahan'])[2] ?></i></span>
            <span><b>No Telepon </b>: <i><?= $data['no_telp'] ?></i></span>
            <span><b>Gender </b>: <i><?= $data['gender'] == 'Male' || $data['gender'] == 'Female' ? ($data['gender'] == 'Male' ? 'Laki-laki' : 'Perempuan') : "Waria" ?></i></span>
        </div>
    <?php else : ?>
        <h1>Data Not Found</h1>
    <?php endif; ?>
</body>

</html>