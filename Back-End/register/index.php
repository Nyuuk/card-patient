<?php

include '../config.php';

session_start();
$_SESSION['last_activity'] = time();
if (isset($_SESSION['id'])) {
    header('Location: /');
}

$conn = db();

if (isset($_POST['register'])) {
    $tgl_lahir = $_POST['day'] . '/' . $_POST['month'] . '/' . $_POST['year'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    if ($password == $cpassword) {
        $users = getAllData($conn)['users'];
        $table_users = getDataByRow('users', 'username', $conn);

        $status = true;
        if (in_array($username, $table_users)) {
            $_SESSION['error'] = 'Username already exists';
            $status = false;
        }
        $table_email = getDataByRow('users', 'email', $conn);
        if (in_array($email, $table_email)) {
            $_SESSION['error'] = 'Email already exists';
            $status = false;
        }

        if ($status) {
            // $nama = mysqli_real_escape_string($conn, $name);
            $email = mysqli_real_escape_string($conn, $email);
            $username = mysqli_real_escape_string($conn, $username);
            $password = mysqli_real_escape_string($conn, $password);
            $tgl_lahit = mysqli_real_escape_string($conn, $tgl_lahir);
            
            $qr = "INSERT INTO users (email, username, password, tanggal_lahir) VALUES ('" . $email . "', '" . $username . "', '" . $password . "', '" . $tgl_lahit . "')";
            $result = $conn->query($qr);
            $id = $conn->insert_id;
            $qrRole = "INSERT INTO roles (user_id, role, status) VALUES ('" . $id . "', 'operator', '1')";
            $resultRole = $conn->query($qrRole);
            if ($result && $resultRole) {
                $_SESSION['last_activity'] = time();
                $_SESSION['remember'] = false;

                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;
                header('Location: /');
                exit;
            }
            $_SESSION['message'] = 'Success added new user';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>

<body>
    <div>
        <form action="" method="POST" onsubmit="return validate()">
            <div class="form-control">
                <label for="name">Name</label>
                <input type="text" placeholder="Name" id="name" name="name">
            </div>
            <div class="form-control">
                <label for="email">Email Address</label>
                <input type="email" placeholder="Email" id="email" name="email">
            </div>
            <div class="form-control">
                <label for="username">Username</label>
                <input type="text" placeholder="Username" id="username" name="username">
            </div>
            <div class="form-control">
                <label for="password">Password</label>
                <input type="text" placeholder="Password" id="password" name="password">
            </div>
            <div class="form-control">
                <label for="cpassword">Confirm Password</label>
                <input type="text" placeholder="Confirm Password" id="cpassword" name="cpassword">
            </div>
            <div class="form-control">
                <!-- <label for="tgl_lahir">Tanggal Lahir</label> -->
                <p>dd/mm/yyyy</p>
                <input type="text" placeholder="Day" id="day" name="day">
                <span>/</span>
                <input type="text" placeholder="Month" id="month" name="month">
                <span>/</span>
                <input type="text" placeholder="Year" id="year" name="year">
                <!-- <input type="date" placeholder="Tanggal Lahir" id="tgl_lahir" name="tgl_lahir"> -->
            </div>
            <div class="form-control">
                <button type="submit" name="register">Register</button>
            </div>
        </form>
    </div>
    <script>
        <?php if (isset($_SESSION['error'])): ?>
            alert("<?= $_SESSION['error'] ?>");
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['message'])): ?>
            alert("<?= $_SESSION['message'] ?>");
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        function validate() {
            const password = document.getElementById('password').value;
            const cpassword = document.getElementById('cpassword').value;

            const day = document.getElementById('day').value;
            const month = document.getElementById('month').value;
            const year = document.getElementById('year').value;

            if (isNaN(day) || isNaN(month) || isNaN(year)) {
                alert('Tanggal Lahir harus diisi dengan angka');
                return false;
            }

            if (day > 31 || day < 1) {
                alert("Tanggal lebih dari 31 atau 1")
                return false
            }
            if (month > 12 || month < 1) {
                alert("Bulan lebih dari 12 atau 1")
                return false;
            }
            if (year.length != 4) {
                alert("Tahun harus 4 digit")
                return false;
            }
            if (password != cpassword) {
                alert('Password does not match');
                return false;
            }
            return true;
        }
    </script>
</body>

</html>