<?php
require_once "function.php";

if (isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($kon, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($kon, "SELECT * FROM `user` WHERE username='$username'");

    if (mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query);

        $passwordBenar = password_verify($password, $user['password']);

        if (!$passwordBenar && $password == $user['password']) {
            $passwordBenar = true;

            $passwordBaru = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($kon, "UPDATE `user` SET password='$passwordBaru' WHERE id_user=" . $user['id_user']);
        }

        if ($passwordBenar) {
            session_regenerate_id(true);
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['level'] = $user['level'];

            header("Location: index.php");
            exit;
        } else {
            $error = "Password yang dimasukkan salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Sistem Penjualan</title>
    <style>
        body { font-family: Arial; background: #f2f2f2; }
        .kotak { width: 350px; margin: 100px auto; padding: 25px; background: white; border: 1px solid #ddd; }
        input { width: 100%; padding: 9px; margin: 7px 0 15px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #1769aa; color: white; border: 0; cursor: pointer; }
        .error { color: red; }
    </style>
</head>
<body>
<div class="kotak">
    <h2>Login Sistem Penjualan</h2>

    <?php if ($error != "") { ?>
        <p class="error"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" name="login">Login</button>
    </form>
</div>
</body>
</html>