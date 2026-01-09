<?php
// Keluar folder pages, masuk folder config
include "../config/koneksi.php";

if (isset($_POST['simpan'])) {
    $item_name   = mysqli_real_escape_string($conn, $_POST['item_name']);
    $location    = mysqli_real_escape_string($conn, $_POST['location']);
    $status      = mysqli_real_escape_string($conn, $_POST['status']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $owner_name  = mysqli_real_escape_string($conn, $_POST['owner_name']);
    $nim         = mysqli_real_escape_string($conn, $_POST['nim']);
    $major       = mysqli_real_escape_string($conn, $_POST['major']);
    $contact     = mysqli_real_escape_string($conn, $_POST['contact']);

    $image_path = "";

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "../uploads/";
        $file_name = time() . "_" . str_replace(' ', '_', basename($_FILES["image"]["name"]));
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // PAKSA PAKAI IP DI SINI
            $ip_laptop = "192.168.18.39";
            $image_path = "http://" . $ip_laptop . "/lost_found_api/uploads/" . $file_name;
        }
    }

    $sql = "INSERT INTO items 
            (item_name, location, status, description, owner_name, nim, major, contact, image_path)
            VALUES 
            ('$item_name','$location','$status','$description','$owner_name','$nim','$major','$contact','$image_path')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Data Berhasil Disimpan'); window.location='../index.php';</script>";
        exit;
    } else {
        die("Error: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tambah Pelaporan - Lost & Found</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Form Pelaporan Baru</h6>
            </div>
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nama Barang</label>
                            <input type="text" name="item_name" class="form-control" placeholder="Contoh: Kunci Motor" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Lokasi Temuan/Hilang</label>
                            <input type="text" name="location" class="form-control" placeholder="Contoh: Parkiran Gedung A" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="hilang">Hilang</option>
                                <option value="ditemukan">Ditemukan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Foto Barang</label>
                            <input type="file" name="image" class="form-control-file">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Deskripsi Barang</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Jelaskan ciri-ciri barang..."></textarea>
                    </div>

                    <hr>
                    <h6 class="font-weight-bold text-dark">Informasi Pelapor/Pemilik</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nama Lengkap</label>
                            <input type="text" name="owner_name" class="form-control" placeholder="Nama Anda">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>NIM</label>
                            <input type="text" name="nim" class="form-control" placeholder="Nomor Induk Mahasiswa">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Jurusan</label>
                            <input type="text" name="major" class="form-control" placeholder="Contoh: Teknik Informatika">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Nomor Kontak / WA</label>
                            <input type="text" name="contact" class="form-control" placeholder="Contoh: 08123456789">
                        </div>
                    </div>

                    <div class="mt-4 text-right">
                        <a href="../index.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" name="simpan" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>