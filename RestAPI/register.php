<?php
include "config/koneksi.php";
if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Username sudah ada!";
    } else {
        mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('$username', '$password')");
        echo "<script>alert('Berhasil Daftar!'); window.location='login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Daftar - Lost & Found</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-custom {
            border: none;
            border-radius: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            width: 400px;
            max-width: 90%;
            background: white;
            padding: 40px;
        }

        .card-custom h2 {
            text-align: center;
            font-weight: 700;
            margin-bottom: 10px;
            color: #333;
        }

        .card-custom p {
            text-align: center;
            color: #888;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .form-control {
            border-radius: 30px !important;
            padding: 25px 20px !important;
            border: 1px solid #d1d3e2;
            margin-bottom: 15px;
        }

        .btn-action {
            background-color: #5577e5;
            border: none;
            border-radius: 30px;
            padding: 12px;
            color: white;
            font-weight: 600;
            width: 100%;
            box-shadow: 0 4px 10px rgba(85, 119, 229, 0.3);
            text-transform: uppercase;
        }

        .footer-links {
            text-align: center;
            margin-top: 25px;
            font-size: 0.85rem;
        }

        .footer-links a {
            color: #5577e5;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="card-custom">
        <h2>DAFTAR</h2>
        <p>Buat Akun Disini ya</p>

        <form method="POST">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <button type="submit" name="register" class="btn-action">Daftar Akun</button>
        </form>

        <div class="footer-links">
            <a href="login.php">Sudah punya akun? Login</a>
        </div>
    </div>
</body>

</html>