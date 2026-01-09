<?php
// Keluar folder pages, masuk folder config
include "../config/koneksi.php";

// 1. AMBIL DATA LAMA BERDASARKAN ID
$id = $_GET['id'] ?? '';
if ($id == '') {
    header("Location: ../index.php");
    exit;
}

$query = mysqli_query($conn, "SELECT * FROM items WHERE id = '$id'");
$old_data = mysqli_fetch_assoc($query);

if (!$old_data) {
    echo "<script>alert('Data tidak ditemukan'); window.location='../index.php';</script>";
    exit;
}

// 2. PROSES UPDATE DATA
if (isset($_POST['update'])) {
    $item_name   = mysqli_real_escape_string($conn, $_POST['item_name']);
    $location    = mysqli_real_escape_string($conn, $_POST['location']);
    $status      = mysqli_real_escape_string($conn, $_POST['status']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $owner_name  = mysqli_real_escape_string($conn, $_POST['owner_name']);
    $nim         = mysqli_real_escape_string($conn, $_POST['nim']);
    $major       = mysqli_real_escape_string($conn, $_POST['major']);
    $contact     = mysqli_real_escape_string($conn, $_POST['contact']);
    $image_path  = $old_data['image_path']; // Default pakai foto lama

    // Logika jika ada upload foto baru
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_dir = "../uploads/";
        $file_name = time() . "_" . str_replace(' ', '_', basename($_FILES["image"]["name"]));
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // PAKSA PAKAI IP DI SINI JUGA
            $ip_laptop = "192.168.18.39";
            $image_path = "http://" . $ip_laptop . "/lost_found_api/uploads/" . $file_name;
        }
    }

    $sql = "UPDATE items SET 
            item_name='$item_name', location='$location', status='$status', 
            description='$description', owner_name='$owner_name', 
            nim='$nim', major='$major', contact='$contact', 
            image_path='$image_path' 
            WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Data Berhasil Diperbarui'); window.location='../index.php';</script>";
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
    <title>Edit Pelaporan - Lost & Found</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">Edit Data Pelaporan #<?= $id; ?></h6>
            </div>
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nama Barang</label>
                            <input type="text" name="item_name" class="form-control" value="<?= $old_data['item_name']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Lokasi</label>
                            <input type="text" name="location" class="form-control" value="<?= $old_data['location']; ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="hilang" <?= $old_data['status'] == 'hilang' ? 'selected' : ''; ?>>Hilang</option>
                                <option value="ditemukan" <?= $old_data['status'] == 'ditemukan' ? 'selected' : ''; ?>>Ditemukan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Foto Baru (Kosongkan jika tidak diganti)</label>
                            <input type="file" name="image" class="form-control-file">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3"><?= $old_data['description']; ?></textarea>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nama Pelapor</label>
                            <input type="text" name="owner_name" class="form-control" value="<?= $old_data['owner_name']; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>NIM</label>
                            <input type="text" name="nim" class="form-control" value="<?= $old_data['nim']; ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Jurusan</label>
                            <input type="text" name="major" class="form-control" value="<?= $old_data['major']; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Kontak/WA</label>
                            <input type="text" name="contact" class="form-control" value="<?= $old_data['contact']; ?>">
                        </div>
                    </div>

                    <div class="mt-4 text-right">
                        <a href="../index.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" name="update" class="btn btn-info">
                            <i class="fas fa-edit"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>