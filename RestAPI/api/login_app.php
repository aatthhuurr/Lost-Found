<?php
header("Content-Type: application/json");
include "../config/koneksi.php";

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? ''; // Teks biasa dari Android

$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");

if (mysqli_num_rows($query) > 0) {
    $data = mysqli_fetch_assoc($query);

    // PHP memverifikasi teks biasa vs hash di DB
    if (password_verify($password, $data['password'])) {
        echo json_encode(["status" => "success", "message" => "Berhasil"]);
    } else {
        // Cek cadangan jika di DB masih ada teks biasa (untuk transisi)
        if ($password === $data['password']) {
            echo json_encode(["status" => "success", "message" => "Berhasil"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Sandi Salah"]);
        }
    }
} else {
    echo json_encode(["status" => "error", "message" => "User Tidak Ditemukan"]);
}
