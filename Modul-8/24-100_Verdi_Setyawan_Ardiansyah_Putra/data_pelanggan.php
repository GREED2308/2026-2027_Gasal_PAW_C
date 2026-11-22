<?php
require_once "function.php";
hanyaOwner();

$pesan = "";
$edit = null;

if (isset($_POST['simpan'])) {
    $id = mysqli_real_escape_string($kon, $_POST['id']);
    $nama = mysqli_real_escape_string($kon, $_POST['nama']);
    $jk = mysqli_real_escape_string($kon, $_POST['jenis_kelamin']);
    $telp = mysqli_real_escape_string($kon, $_POST['telp']);
    $alamat = mysqli_real_escape_string($kon, $_POST['alamat']);

    if (isset($_POST['edit'])) {
        $query = "UPDATE pelanggan SET nama='$nama', jenis_kelamin='$jk', telp='$telp', alamat='$alamat' WHERE id='$id'";
    } else {
        $query = "INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat)
        VALUES ('$id', '$nama', '$jk', '$telp', '$alamat')";
    }

    if (mysqli_query($kon, $query)) {
        header("Location: data_pelanggan.php");
        exit;
    } else {
        $pesan = "Data gagal disimpan. Pastikan ID pelanggan belum digunakan.";
    }
}

if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($kon, $_GET['hapus']);
    $cek = mysqli_query($kon, "SELECT * FROM transaksi WHERE pelanggan_id='$id'");

    if (mysqli_num_rows($cek) == 0) {
        mysqli_query($kon, "DELETE FROM pelanggan WHERE id='$id'");
        header("Location: data_pelanggan.php");
        exit;
    } else {
        $pesan = "Pelanggan sudah digunakan pada transaksi, jadi tidak bisa dihapus.";
    }
}

if (isset($_GET['edit'])) {
    $id = mysqli_real_escape_string($kon, $_GET['edit']);
    $queryEdit = mysqli_query($kon, "SELECT * FROM pelanggan WHERE id='$id'");
    $edit = mysqli_fetch_assoc($queryEdit);
}

$hasil = mysqli_query($kon, "SELECT * FROM pelanggan ORDER BY id");
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Data Pelanggan</title></head>
<body>
<h2>Data Pelanggan</h2>
<a href="index.php">Kembali ke Home</a>
<p><?php echo $pesan; ?></p>

<h3><?php echo $edit ? 'Edit Pelanggan' : 'Tambah Pelanggan'; ?></h3>
<form method="POST">
    <label>ID Pelanggan</label><br>
    <input type="text" name="id" value="<?php echo htmlspecialchars($edit['id'] ?? ''); ?>" <?php echo $edit ? 'readonly' : ''; ?> required><br><br>
    <label>Nama</label><br>
    <input type="text" name="nama" value="<?php echo htmlspecialchars($edit['nama'] ?? ''); ?>" required><br><br>
    <label>Jenis Kelamin</label><br>
    <select name="jenis_kelamin">
        <option value="L" <?php echo isset($edit['jenis_kelamin']) && $edit['jenis_kelamin'] == 'L' ? 'selected' : ''; ?>>Laki-laki</option>
        <option value="P" <?php echo isset($edit['jenis_kelamin']) && $edit['jenis_kelamin'] == 'P' ? 'selected' : ''; ?>>Perempuan</option>
    </select><br><br>
    <label>Telepon</label><br>
    <input type="text" name="telp" value="<?php echo htmlspecialchars($edit['telp'] ?? ''); ?>"><br><br>
    <label>Alamat</label><br>
    <input type="text" name="alamat" value="<?php echo htmlspecialchars($edit['alamat'] ?? ''); ?>"><br><br>
    <?php if ($edit) { ?><input type="hidden" name="edit" value="1"><?php } ?>
    <button type="submit" name="simpan">Simpan</button>
    <a href="data_pelanggan.php">Batal</a>
</form>

<h3>Daftar Pelanggan</h3>
<table border="1" cellpadding="8" cellspacing="0">
    <tr><th>No</th><th>ID</th><th>Nama</th><th>Jenis Kelamin</th><th>Telepon</th><th>Alamat</th><th>Aksi</th></tr>
    <?php $no=1; while ($pel = mysqli_fetch_assoc($hasil)) { ?>
    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo htmlspecialchars($pel['id']); ?></td>
        <td><?php echo htmlspecialchars($pel['nama']); ?></td>
        <td><?php echo $pel['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
        <td><?php echo htmlspecialchars($pel['telp'] ?? ''); ?></td>
        <td><?php echo htmlspecialchars($pel['alamat'] ?? ''); ?></td>
        <td>
            <a href="data_pelanggan.php?edit=<?php echo urlencode($pel['id']); ?>">Edit</a> |
            <a href="data_pelanggan.php?hapus=<?php echo urlencode($pel['id']); ?>" onclick="return confirm('Hapus pelanggan ini?')">Hapus</a>
        </td>
    </tr>
    <?php } ?>
</table>
</body>
</html>