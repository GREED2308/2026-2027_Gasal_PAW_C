<?php
require_once "function.php";
harusLogin();

$nama = $_SESSION['nama'];
$level = $_SESSION['level'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Home Sistem Penjualan</title>
    <style>
        body { font-family: Arial; margin: 0; }
        nav { background: #1769aa; padding: 15px; }
        nav a { color: white; margin-right: 15px; text-decoration: none; }
        .isi { padding: 25px; }
    </style>
</head>
<body>
<nav>
    <a href="index.php">Home</a>

    <?php if ($level == 1) { ?>
        <a href="data_barang.php">Data Barang</a>
        <a href="../../Modul-5/24-100_Verdi_Setyawan_Ardiansyah_Putra/soal_3.php">Data Supplier</a>
        <a href="data_pelanggan.php">Data Pelanggan</a>
        <a href="data_user.php">Data User</a>
    <?php } ?>

    <a href="../../Modul-6/24-100_Verdi_Setyawan_Ardiansyah_Putra/transaksi.php">Transaksi</a>
    <a href="../../Modul-7/24-100_Verdi_Setyawan_Ardiansyah_Putra/transaksiLaporan.php">Laporan</a>
    <a href="logout.php" onclick="return confirm('Yakin ingin logout?')">Logout</a>
</nav>

<div class="isi">
    <h2>Selamat Datang, <?php echo htmlspecialchars($nama); ?>!</h2>

    <?php if ($level == 1) { ?>
        <p>Anda login sebagai Owner.</p>
        <p>Owner dapat membuka Home, Data Master, Transaksi, dan Laporan.</p>
    <?php } else { ?>
        <p>Anda login sebagai Kasir.</p>
        <p>Kasir dapat membuka Home, Transaksi, dan Laporan.</p>
    <?php } ?>
</div>
</body>
</html>