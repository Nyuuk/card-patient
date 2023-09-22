<?php

// define('PROJECT_ROOT', __DIR__);


// connection to mysql
function db()
{
    $mysqli = new mysqli('some-mysql', 'root', '1234', 'card_patient');

    if ($mysqli->connect_error) {
        header("HTTP/1.1 500 Internal Server Error");
        header("Status: 500");
        header('Location: /500');
        die("Connection failed: " . $mysqli->connect_error);
    }
    return $mysqli;
}

function checkTimeOut($last_activity, $timeout_mode)
{
    // timeout mode is boolean
    // last_activity is time()

    // set jika timeout mode adalah true maka timeout mode 1 hari, dan jika false maka 10 menit
    // $timeout = $timeout_mode ? 86400 : 600;
    $timeout = $timeout_mode ? 60 : 30;
    if ((time() - $last_activity) > $timeout)
    {
        // jika waktu habis
        return false;
    }
    return true;
}

function getAllData($conn)
{
    $arrTables = ["cards", "citys", "roles", "users"];
    $dt = [];
    foreach ($arrTables as $table) {
        $data = $conn->query("SELECT * FROM " . $table)->fetch_all(MYSQLI_ASSOC);
        $dt[$table] = $data;
    }
    return $dt;
}

function getDataById($table, $id, $conn)
{
    $result = $conn->query("SELECT * FROM " . $table . " WHERE id = \"" . $id . "\"");
    $data = [];
    if ($result) {
        $data = $result->fetch_assoc();
    }
    return $data;
}

// getalldatabyrow
function getDataByRow($table, $row, $conn)
{
    $result = $conn->query("SELECT * FROM " . $table);
    if ($result) {
        return array_column($result->fetch_all(MYSQLI_ASSOC), $row);
    }

    return [];
    // $existingData = array_column($data[$table], $row);
    // return $existingData;
}

function hitungUmurDenganBulan($tanggal_lahir)
{
    // Pecah tanggal lahir menjadi komponen-komponennya
    list($hari, $bulan, $tahun) = explode('/', $tanggal_lahir);

    // Dapatkan tanggal saat ini
    $tanggal_sekarang = date('d/m/Y');

    // Pecah tanggal saat ini menjadi komponen-komponennya
    list($hari_sekarang, $bulan_sekarang, $tahun_sekarang) = explode('/', $tanggal_sekarang);

    // Hitung umur dalam tahun
    $umurTahun = $tahun_sekarang - $tahun;

    // Hitung total bulan
    $umurBulan = $bulan_sekarang - $bulan;

    // Periksa apakah sudah ulang tahun atau belum
    if ($hari_sekarang < $hari) {
        $umurBulan--;
    }

    // Jika hasil total bulan negatif, tambahkan 12 bulan
    if ($umurBulan < 0) {
        $umurBulan += 12;
        $umurTahun--;
    }

    return ['tahun' => $umurTahun, 'bulan' => $umurBulan];
}

// function hitungUmurDenganBulan($tanggal_lahir)
// {
//     // Konversi tanggal lahir ke format timestamp
//     // echo $tanggal_lahir;
//     $tanggal_lahir_timestamp = strtotime($tanggal_lahir);

//     echo $tanggal_lahir_timestamp;
//     if ($tanggal_lahir_timestamp === false) {
//         // Tanggal lahir tidak valid
//         return false;
//     }

//     // Dapatkan tanggal saat ini dalam format timestamp
//     $tanggal_sekarang_timestamp = time();

//     // Hitung selisih dalam detik antara tanggal lahir dan tanggal saat ini
//     $selisih_detik = $tanggal_sekarang_timestamp - $tanggal_lahir_timestamp;

//     // Hitung umur dalam tahun dan bulan
//     $umurTahun = floor($selisih_detik / (365 * 24 * 60 * 60));
//     $umurBulan = floor(($selisih_detik % (365 * 24 * 60 * 60)) / (30 * 24 * 60 * 60));

//     return ['tahun' => $umurTahun, 'bulan' => $umurBulan];
// }


function generateUniqueId($length = 10)
{
    $characters = '0123456789';
    $result = '';

    for ($i = 0; $i < $length; $i++) {
        $result .= $characters[mt_rand(0, strlen($characters) - 1)];
    }

    return $result;
}

// $uniqueId = generateUniqueId();
