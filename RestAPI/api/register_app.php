<?php
header("Content-Type: application/json");
include "../config/koneksi.php";

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? ''; 
$email    = $_POST['email'] ?? '';

if (!empty($username) && !empty($password)) {
    // Password di-hash agar web admin bisa membaca
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Simpan hasil query ke variabel $insert agar sesuai dengan pengecekan IF
    $insert = mysqli_query($conn, "INSERT INTO users (username, password, email) VALUES ('$username', '$password_hash', '$email')");

    if ($insert) {
        echo json_encode(["status" => "success", "message" => "Data berhasil disimpan"]);
    } else {
        // Tambahkan mysqli_error untuk melihat alasan jika gagal (misal: kolom email belum ada)
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Input tidak boleh kosong"]);
}
?>