<?php
require_once "function.php";
hanyaOwner();

$pesan = "";
$edit = null;

if (isset($_POST['simpan'])) {
    $kode = mysqli_real_escape_string($kon, $_POST['kode_barang']);
    $nama = mysqli_real_escape_string($kon, $_POST['nama_barang']);
    $harga = (int) $_POST['harga'];
    $stok = (int) $_POST['stok'];
    $supplier = (int) $_POST['supplier_id'];
    $id = (int) $_POST['id'];

    if ($id == 0) {
        $query = "INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id)
        VALUES ('$kode', '$nama', $harga, $stok, $supplier)";
    } else {
        $query = "UPDATE barang SET kode_barang='$kode', nama_barang='$nama', harga=$harga,
        stok=$stok, supplier_id=$supplier WHERE id=$id";
    }

    if (mysqli_query($kon, $query)) {
        header("Location: data_barang.php");
        exit;
    } else {
        $pesan = "Data barang gagal disimpan.";
    }
}

if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $cek = mysqli_query($kon, "SELECT * FROM transaksi_detail WHERE barang_id=$id");

    if (mysqli_num_rows($cek) == 0) {
        mysqli_query($kon, "DELETE FROM barang WHERE id=$id");
        header("Location: data_barang.php");
        exit;
    } else {
        $pesan = "Barang sudah digunakan pada transaksi, jadi tidak bisa dihapus.";
    }
}

if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $queryEdit = mysqli_query($kon, "SELECT * FROM barang WHERE id=$id");
    $edit = mysqli_fetch_assoc($queryEdit);
}

$supplier = mysqli_query($kon, "SELECT * FROM supplier ORDER BY nama");
$hasil = mysqli_query($kon, "SELECT barang.*, supplier.nama AS nama_supplier
FROM barang JOIN supplier ON barang.supplier_id=supplier.id");
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Data Barang</title></head>
<body>
<h2>Data Barang</h2>
<a href="index.php">Kembali ke Home</a>
<p><?php echo $pesan; ?></p>

<h3><?php echo $edit ? 'Edit Barang' : 'Tambah Barang'; ?></h3>
<form method="POST">
    <input type="hidden" name="id" value="<?php echo $edit['id'] ?? 0; ?>">
    <label>Kode Barang</label><br>
    <input type="text" name="kode_barang" value="<?php echo htmlspecialchars($edit['kode_barang'] ?? ''); ?>" required><br><br>
    <label>Nama Barang</label><br>
    <input type="text" name="nama_barang" value="<?php echo htmlspecialchars($edit['nama_barang'] ?? ''); ?>" required><br><br>
    <label>Harga</label><br>
    <input type="number" name="harga" min="0" value="<?php echo $edit['harga'] ?? ''; ?>" required><br><br>
    <label>Stok</label><br>
    <input type="number" name="stok" min="0" value="<?php echo $edit['stok'] ?? ''; ?>" required><br><br>
    <label>Supplier</label><br>
    <select name="supplier_id" required>
        <option value="">Pilih Supplier</option>
        <?php while ($sp = mysqli_fetch_assoc($supplier)) { ?>
            <option value="<?php echo $sp['id']; ?>" <?php echo isset($edit['supplier_id']) && $edit['supplier_id'] == $sp['id'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($sp['nama']); ?>
            </option>
        <?php } ?>
    </select><br><br>
    <button type="submit" name="simpan">Simpan</button>
    <a href="data_barang.php">Batal</a>
</form>

<h3>Daftar Barang</h3>
<table border="1" cellpadding="8" cellspacing="0">
    <tr><th>No</th><th>Kode</th><th>Nama</th><th>Supplier</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
    <?php $no=1; while ($barang = mysqli_fetch_assoc($hasil)) { ?>
    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo htmlspecialchars($barang['kode_barang']); ?></td>
        <td><?php echo htmlspecialchars($barang['nama_barang']); ?></td>
        <td><?php echo htmlspecialchars($barang['nama_supplier']); ?></td>
        <td><?php echo number_format($barang['harga'], 0, ',', '.'); ?></td>
        <td><?php echo $barang['stok']; ?></td>
        <td>
            <a href="data_barang.php?edit=<?php echo $barang['id']; ?>">Edit</a> |
            <a href="data_barang.php?hapus=<?php echo $barang['id']; ?>" onclick="return confirm('Hapus barang ini?')">Hapus</a>
        </td>
    </tr>
    <?php } ?>
</table>
</body>
</html>