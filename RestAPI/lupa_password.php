<?php
include "config/koneksi.php";

if (isset($_POST['reset'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password_baru = $_POST['password_baru'];

    // Cek apakah username ada
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        $hash = password_hash($password_baru, PASSWORD_DEFAULT);
        $update = mysqli_query($conn, "UPDATE users SET password='$hash' WHERE username='$username'");
        if ($update) {
            echo "<script>alert('Password berhasil diubah!'); window.location='login.php';</script>";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Lupa Password - Lost & Found</title>
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fc;
            height: 100vh;
            display: flex;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-4 col-lg-5 col-md-7">
                <div class="card o-hidden border-0 shadow-lg my-5" style="border-radius:12px;">
                    <div class="card-body p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-2">Lupa Password?</h1>
                            <p class="mb-4 small">Masukkan username Anda untuk mengatur ulang password.</p>
                        </div>
                        <?php if (isset($error)) echo "<div class='alert alert-danger small text-center'>$error</div>"; ?>
                        <form class="user" method="POST">
                            <div class="form-group">
                                <input type="text" name="username" class="form-control form-control-user" placeholder="Username" required>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password_baru" class="form-control form-control-user" placeholder="Password Baru" required>
                            </div>
                            <button type="submit" name="reset" class="btn btn-primary btn-user btn-block">RESET PASSWORD</button>
                        </form>
                        <hr>
                        <div class="text-center"><a class="small" href="login.php">Kembali ke Login</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>