<?php
include '../config.php';


session_start();

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    if ($id) {
        header('Location: /');
    }
}

$db = db();
// $users = getAllData($db)['users'];

if (isset($_POST['login'])) {
    $username = $_POST['username'];

    if (str_contains($username, '@')) {
        $type = "email";
    } else {
        $type = "username";
    }
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $username = mysqli_real_escape_string($db, $username);

    $qur = "SELECT * FROM users WHERE $type = '$username'";
    $result = $db->query($qur);
    $user = mysqli_fetch_assoc($result);
    if ($result->num_rows > 0 && $user['password'] == $password) {
        $qr = "UPDATE roles SET status = '1' WHERE user_id = '" . $user['id'] . "'";
        $db->query($qr);

        $_SESSION['remember'] = isset($_POST['remember']) ? true : false;
        $_SESSION['last_activity'] = time();

        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: /');
        exit;
    } else {
        $_SESSION['error'] = "Invalid username or password";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
    <link rel="stylesheet" href="../style.css" />
</head>

<body>
    <div class="global-container">
        <div class="card login-form">
            <div class="card-body">
                <h1 class="card-title text-center">M A S U K</h1>
            </div>
            <div class="card-text">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Email Address or Username</label>
                        <input type="text" class="form-control" id="username" name="username" />
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" />
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" />
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>
                    <div class="justify-content-between">
                        <button type="submit" class="btn btn-primary" name="login">Login</button>
                        <a href="/register" class="btn btn-primary">Register</a>
                        <a href="#">forgot password ?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        <?php if (isset($_SESSION['error'])): ?>
            alert("<?= $_SESSION['error'] ?>");
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </script>
</body>

</html>