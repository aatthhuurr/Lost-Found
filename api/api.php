<?php
ob_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");

// 1. KONEKSI KE DATABASE
include "../config/koneksi.php";

if (!$conn) {
    header("Content-Type: application/json");
    echo json_encode(["message" => "Koneksi gagal ke database"]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

/* ================= GET DATA (Untuk Mobile & Web) ================= */
if ($method === 'GET') {
    header("Content-Type: application/json");
    $query = mysqli_query($conn, "SELECT * FROM items ORDER BY id DESC");
    $data = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
    echo json_encode($data);
    exit;
}

/* ================= POST ACTION ================= */
$action = $_POST['action'] ?? '';
$id     = $_POST['id'] ?? '';

// --- ACTION 1: UPDATE STATUS SAJA (Hanya dari Tombol Centang Dashboard Web) ---
// Kita cek spesifik: jika ada status tapi TIDAK ADA item_name, berarti ini update cepat status ke 'selesai'
// --- ACTION 1: UPDATE STATUS (Android & Tombol Centang Web) ---
// --- ACTION 1: UPDATE STATUS (Android & Tombol Centang Web) ---
if ($action === 'update' && isset($_POST['status']) && !isset($_POST['item_name'])) {
    $id = $_POST['id'];
    $status_raw = strtolower($_POST['status']);

    // Logika: Jika status yang dikirim kosong atau null, default ke 'selesai' 
    // (untuk jaga-jaga tombol centang web lama), tapi kalau ada isinya 
    // (dari Android), pakai isi tersebut.
    $status = ($status_raw == '') ? 'selesai' : $status_raw;

    $update = mysqli_query($conn, "UPDATE items SET status='$status' WHERE id='$id'");

    if ($update) {
        if (isset($_SERVER['HTTP_REFERER'])) {
            header("Location: ../index.php");
            exit;
        }
        echo json_encode(["message" => "Status updated to $status"]);
        exit;
    }
}
// --- ACTION 2: DELETE ---
if ($action === 'delete') {
    mysqli_query($conn, "DELETE FROM items WHERE id='$id'");
    header("Location: ../index.php");
    exit;
}

// --- ACTION 3: INSERT / UPDATE FULL DATA (Dari Form Edit/Tambah) ---
if ($action === 'insert' || ($action === 'update' && isset($_POST['item_name']))) {

    $name    = mysqli_real_escape_string($conn, $_POST['item_name'] ?? '');
    $loc     = mysqli_real_escape_string($conn, $_POST['location'] ?? '');
    $status  = strtolower($_POST['status'] ?? 'kehilangan'); // Mengambil dari dropdown form
    $desc    = mysqli_real_escape_string($conn, $_POST['description'] ?? '');
    $owner   = mysqli_real_escape_string($conn, $_POST['owner_name'] ?? '');
    $nim     = mysqli_real_escape_string($conn, $_POST['nim'] ?? '');
    $major   = mysqli_real_escape_string($conn, $_POST['major'] ?? '');
    $contact = mysqli_real_escape_string($conn, $_POST['contact'] ?? '');
    $image_path = $_POST['old_image'] ?? '';

    // LOGIKA UPLOAD FOTO
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $dir = "../uploads/";
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $file_name = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $dir . $file_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Gunakan IP Laptop agar Android bisa akses foto
            $ip_laptop = "192.168.18.39";
            $image_path = "http://" . $ip_laptop . "/lost_found_api/uploads/" . $file_name;
        }
    }

    if ($action === 'insert') {
        mysqli_query($conn, "INSERT INTO items 
        (item_name, location, status, description, owner_name, nim, major, contact, image_path) 
        VALUES ('$name','$loc','$status','$desc','$owner','$nim','$major','$contact','$image_path')");
    } else {
        // UPDATE FULL (Tidak akan memaksa jadi 'selesai' jika tidak dipilih)
        mysqli_query($conn, "UPDATE items SET 
        item_name='$name', location='$loc', status='$status', 
        description='$desc', owner_name='$owner', 
        nim='$nim', major='$major', contact='$contact', 
        image_path='$image_path' 
        WHERE id='$id'");
    }

    header("Location: ../index.php");
    exit;
}
