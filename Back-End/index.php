<?php

include './config.php';

$conn = db();

session_start();

if (!isset($_SESSION['id'])) {
    header('Location: /login');
}

if (isset($_POST['logout'])) {
    $qr = "UPDATE roles SET status = '0' WHERE user_id = '" . $_SESSION['id'] . "'";
    $conn->query($qr);
    session_unset();
    session_destroy();
    header('Location: /login');
}

if (isset($_POST['newDaerah'])) {

    $provinsi = $_POST['provinsi'];
    $kabupaten = $_POST['kabupaten'];
    $kecamatan = $_POST['kecamatan'];
    $data = getAllData($conn);

    $provinsi = mysqli_real_escape_string($conn, $provinsi);
    $kabupaten = mysqli_real_escape_string($conn, $kabupaten);
    $kecamatan = mysqli_real_escape_string($conn, $kecamatan);
    $qr = "INSERT INTO citys (provinsi, kabupaten, desa) VALUES ('" . $provinsi . "', '" . $kabupaten . "', '" . $kecamatan . "')";
    if ($conn->query($qr) == TRUE) {
        $_SESSION['message'] = 'Success added new city';
        header('Location: /');
    }
    $_SESSION['message'] = 'Failed to add new city';
    header('Location: /');
}

if (isset($_POST['newPatient'])) {

    // unique patient_id with start TSX-
    $patient_id = 'TSX-' . generateUniqueId();
    // check patient_id in database
    $result = $conn->query('SELECT * FROM cards WHERE patient_id = "' . $patient_id . '"');
    while (true) {
        if ($result->num_rows > 0) {
            $patient_id = 'TSX-' . generateUniqueId();
            $result = $conn->query('SELECT * FROM cards WHERE patient_id = "' . $patient_id . '"');
        } else {
            break;
        }
    }

    $name = mysqli_real_escape_string($conn, ($_POST['nama']));
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $cityProv = mysqli_real_escape_string($conn, $_POST['provinsi']);
    $cityKab = mysqli_real_escape_string($conn, $_POST['kabupaten']);
    $cityKel = mysqli_real_escape_string($conn, $_POST['kecamatan']);
    $city = [$cityProv, $cityKab, $cityKel];
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $rtrw = mysqli_real_escape_string($conn, $_POST['rtrw']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    // putData($data);

    $qur = "INSERT INTO cards (patient_id, nama, alamat, no_telp, rt_rw, kelurahan, tgl_lahir, gender) VALUES ('" . $patient_id . "', '" . $name . "', '" . $alamat . "', '" . $number . "', '" . $rtrw . "', '" . json_encode($city) . "', '" . $tanggal_lahir . "', '" . $gender . "')";
    $result = $conn->query($qur);

    if ($result) {
        $_SESSION['message'] = 'Success added new patient';
        header('Location: /cards?id=' . $patient_id);
    }
    // header('Location: /cards?id=' . $patient_id);
    $_SESSION['message'] = 'Failed to add new patient';
}
if (!checkTimeOut($_SESSION['last_activity'], $_SESSION['remember'])) {
    $qr = "UPDATE roles SET status = '0' WHERE user_id = '" . $_SESSION['id'] . "'";
    session_unset();
    session_destroy();
    $conn->query($qr);
    header('Location: /login');
}
$_SESSION['last_activity'] = time();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>
</head>

<body>
    <?php
    $user = getDataById('users', $_SESSION['id'], $conn);
    $qur = "SELECT * FROM roles WHERE user_id = " . $user['id'];
    $result = $conn->query($qur);
    $resData = $result->fetch_assoc();
    $_SESSION['message'] = json_encode($resData);
    if ($result->num_rows > 0 && $resData['role'] == 'admin') {
    ?>
        <h1>ADMIN</h1>
        <p>Form Daerah</p>
        <hr>
        <form action="" method="POST">
            <div class="form-control">
                <label for="provinsi">Provinsi</label>
                <input type="text" placeholder="provinsi" id="provinsi" name="provinsi">
            </div>
            <div class="form-control">
                <label for="kabupaten">Kabupaten</label>
                <input type="text" placeholder="kabupaten" id="kabupaten" name="kabupaten">
            </div>
            <div class="form-control">
                <label for="kecamatan">Kecamatan</label>
                <input type="text" placeholder="Kecamatan" id="kecamatan" name="kecamatan">
            </div>
            <div class="form-control">
                <button type="submit" name="newDaerah">Submit</button>
            </div>
        </form>
    <?php } elseif ($result->num_rows > 0 && $resData['role'] == 'operator') { ?>
        <h1>OPERATOR</h1>
        <p>Form Card Patient</p>
        <hr>
        <form action="" method="POST">
            <div class="form-control">
                <label for="nama">Nama</label>
                <input type="text" placeholder="Nama" id="nama" name="nama">
            </div>
            <div class="form-control">
                <label for="alamat">Alamat</label>
                <input type="text" placeholder="Alamat" id="alamat" name="alamat">
            </div>
            <div class="form-control">
                <label for="provinsi">Provinsi</label>
                <select name="provinsi" id="provinsi">
                    <?php
                    $provinsi = getDataByRow('citys', 'provinsi', $conn);
                    for ($i = 0; $i < count($provinsi); $i++) {
                        if ($provinsi[$i] != $provinsi[$i - 1]) {
                            echo "<option value='" . $provinsi[$i] . "'>" . $provinsi[$i] . "</option>";
                        }
                    }
                    ?>
                </select>
                <label for="kabupaten">Kabupaten</label>
                <select name="kabupaten" id="kabupaten">
                    <?php
                    $kabupaten = getDataByRow('citys', 'kabupaten', $conn);
                    for ($i = 0; $i < count($kabupaten); $i++) {
                        if ($kabupaten[$i] != $kabupaten[$i - 1]) {
                            echo "<option value='" . $kabupaten[$i] . "'>" . $kabupaten[$i] . "</option>";
                        }
                    }
                    ?>
                </select>
                <label for="kecamatan">Kecamatan</label>
                <select name="kecamatan" id="kecamatan">
                    <?php
                    $kecamatan = getDataByRow('citys', 'desa', $conn);
                    for ($i = 0; $i < count($kabupaten); $i++) {
                        if ($kabupaten[$i] != $kabupaten[$i - 1]) {
                            echo "<option value='" . $kabupaten[$i] . "'>" . $kecamatan[$i] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-control">
                <label for="number">No Telp</label>
                <input type="text" placeholder="No Telp" id="number" name="number">
            </div>
            <div class="form-control">
                <label for="rtrw">RT / RW</label>
                <input type="text" placeholder="RT / RW" id="rtrw" name="rtrw">
            </div>
            <div class="form-control">
                <label for="tanggal_lahir">Tanggal Lahir</label>
                <input type="text" placeholder="Tanggal Lahir" id="tanggal_lahir" name="tanggal_lahir">
            </div>
            <div class="form-control">
                <label for="gender">Jenis Kelamin</label>
                <select name="gender" id="gender">
                    <option value="Male">Laki Laki</option>
                    <option value="Female">Perempuan</option>
                </select>
            </div>
            <div class="form-control">
                <button type="submit" name="newPatient">Submit</button>
            </div>
        </form>
    <?php } else { ?>
        <h1>ERROR</h1>
        <p><?php echo $_SESSION['message']; ?></p>
        <?php unset($_SESSION['message']); ?>
    <?php } ?>
    <hr>
    <form action="" method="POST">
        <button type="submit" name="logout">Logout</button>
    </form>
    <script>
        <?php if (isset($_SESSION['message'])) : ?>
            alert("<?= $_SESSION['message'] ?>");
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
    </script>
</body>

</html>