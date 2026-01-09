<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include "config/koneksi.php";

/* =========================
   HAPUS DATA
   ========================= */
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    $hapus = mysqli_query($conn, "DELETE FROM items WHERE id='$id'");

    if ($hapus) {
        echo "<script>alert('Data Berhasil Dihapus'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal Menghapus Data'); window.location='index.php';</script>";
    }
    exit;
}

/* =========================
   FILTER STATUS
   ========================= */
$status = $_GET['status'] ?? '';
$where = "";
if ($status != '') {
    $status = mysqli_real_escape_string($conn, $status);
    $where = "WHERE status='$status'";
}

/* =========================
   AMBIL DATA
   ========================= */
$data = mysqli_query($conn, "SELECT * FROM items $where ORDER BY id DESC LIMIT 50");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Admin Dashboard - Lost & Found</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .btn-plus-fixed {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            z-index: 999;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .3);
        }

        .img-table {
            height: 60px;
            width: 60px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .info-detail {
            font-size: 0.85rem;
            line-height: 1.4;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">

        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-search"></i></div>
                <div class="sidebar-brand-text mx-3">ADMIN</div>
            </a>
            <hr class="sidebar-divider">
            <li class="nav-item active">
                <a class="nav-link" href="index.php"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a>
            </li>
            <hr class="sidebar-divider">
            <li class="nav-item">
                <a class="nav-link" href="pages/tambah_barang.php"><i class="fas fa-fw fa-plus-circle"></i><span>Pelaporan Baru</span></a>
            </li>
            <hr class="sidebar-divider">
            <li class="nav-item">
                <a class="nav-link" href="logout.php" onclick="return confirm('Yakin ingin keluar?')">
                    <i class="fas fa-fw fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="container-fluid">
                    <br>
                    <h1 class="h3 mb-4 text-gray-800 font-weight-bold">Manajemen Barang Hilang & Temu</h1>


                    <div class="card shadow mb-4">
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Foto</th>
                                        <th>Barang & Deskripsi</th>
                                        <th>Lokasi</th>
                                        <th>Status</th>
                                        <th>Detail Pelapor</th>
                                        <th class="text-center" style="width: 150px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php if (mysqli_num_rows($data) > 0): ?>
                                        <?php while ($row = mysqli_fetch_assoc($data)): ?>
                                            <tr>
                                                <td class="text-center">
                                                    <?php if ($row['image_path']): ?>
                                                        <img src="<?= $row['image_path']; ?>" class="img-table">
                                                    <?php else: ?>
                                                        <span class="text-muted small">No Image</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <strong><?= $row['item_name']; ?></strong><br>
                                                    <small class="text-muted"><?= $row['description'] ?: 'Tidak ada deskripsi'; ?></small>
                                                </td>
                                                <td><?= $row['location']; ?></td>
                                                <td>
                                                    <?php if ($row['status'] == 'hilang'): ?>
                                                        <span class="badge badge-danger">Hilang</span>
                                                    <?php elseif ($row['status'] == 'ditemukan'): ?>
                                                        <span class="badge badge-warning text-white">Ditemukan</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-success">Selesai</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="info-detail">
                                                    <strong>Nama:</strong> <?= $row['owner_name'] ?: '-'; ?><br>
                                                    <strong>NIM:</strong> <?= $row['nim'] ?: '-'; ?><br>
                                                    <strong>Jurusan:</strong> <?= $row['major'] ?: '-'; ?><br>
                                                    <strong>Kontak:</strong> <?= $row['contact'] ?: '-'; ?>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <?php if ($row['status'] != 'selesai'): ?>
                                                            <form method="post" action="api/api.php" style="display:inline">
                                                                <input type="hidden" name="action" value="update">
                                                                <input type="hidden" name="status" value="selesai">
                                                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                                                <button type="submit" class="btn btn-success btn-sm" title="Tandai Selesai">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            </form>

                                                            <a href="pages/edit_barang.php?id=<?= $row['id']; ?>" class="btn btn-info btn-sm" title="Ubah Data">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        <?php endif; ?>

                                                        <a href="index.php?hapus=<?= $row['id']; ?>"
                                                            onclick="return confirm('Hapus data ini?')"
                                                            class="btn btn-danger btn-sm" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">Belum ada data pelaporan.</td>
                                        </tr>
                                    <?php endif; ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a href="pages/tambah_barang.php" class="btn btn-primary btn-plus-fixed">
            <i class="fas fa-plus"></i>
        </a>

        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="js/sb-admin-2.min.js"></script>
</body>

</html>