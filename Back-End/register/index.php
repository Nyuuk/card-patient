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

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
    <link rel="stylesheet" href="../style.css" />
</head>

<body>
    <div class="global-container">
        <div class="card login-form">
            <div class="card-body">
                <h1 class="text-center">R E G I S T E R</h1>
            </div>
            <div class="card-text">
                <form action="" method="POST" onsubmit="return validate()">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" />
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="text" class="form-control" id="email" name="email" />
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" />
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" />
                    </div>
                    <div class="mb-3">
                        <label for="cpassword" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="cpassword" name="cpassword" />
                    </div>
                    <div class="mb-3">
                        <p for="tanggal_lahir" class="form-label">Tanggal Lahir</p>
                        <p class="title-little">dd/mm/yyyy</p>
                        <div id="tanggal_lahir" class="input-group mb-3">
                            <input type="number" class="form-control" id="day" name="day" placeholder="Tanggal" />
                            <span class="input-group-text">/</span>
                            <input type="number" class="form-control" name="month" id="month" placeholder="Bulan" />
                            <span class="input-group-text">/</span>
                            <input type="number" class="form-control" name="year" id="year" placeholder="Tahun" />
                        </div>
                    </div>
                    <div class="justify-content-between">
                        <button type="submit" class="btn btn-primary" name="register" id="register">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        <?php if (isset($_SESSION['error'])) : ?>
            alert("<?= $_SESSION['error'] ?>");
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['message'])) : ?>
            alert("<?= $_SESSION['message'] ?>");
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        const btnSubmit = document.getElementById("register");

        const tglDoc = document.getElementById('tgl');
        tglDoc.addEventListener('input', (e) => {
            const val = e.target.value;
            if (val && val > 31) {
                tglDoc.value = 0
            }
        })

        const blnDoc = document.getElementById('bln');
        blnDoc.addEventListener('input', (e) => {
            const val = e.target.value;
            if (val && val > 12) {
                blnDoc.value = 0
            }
        })

        const validate = () => {
            const name = document.getElementById("name").value;
            const email = document.getElementById("email").value;
            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;
            const c_password = document.getElementById("cpassword").value;
            const tgl = document.getElementById("day").value;
            const bln = document.getElementById("month").value;
            const thn = document.getElementById("year").value;

            if (password != c_password) {
                alert("Password does not match");
                return false;
            }
            if (
                name == "" ||
                email == "" ||
                username == "" ||
                password == "" ||
                c_password == ""
            ) {
                alert("All fields are required");
                return false;
            }

            // buat logika untuk format bulan mm tanggal dd tahun yyyy
            if (
                !(
                    (bln.length > 0 && bln.length < 2) ||
                    (tgl.length > 0 && tgl.length < 2) ||
                    (thn.length > 0 && thn.length < 2) ||
                )
            ) {
                alert("Format tanggal lahir salah");
                return false;
            }

            return true;
        };
    </script>
</body>

</html>