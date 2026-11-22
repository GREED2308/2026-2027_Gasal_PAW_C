<?php
require_once "function.php";
hanyaOwner();

$pesan = "";
$edit = null;

if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($kon, $_POST['nama']);
    $username = mysqli_real_escape_string($kon, $_POST['username']);
    $password = $_POST['password'];
    $alamat = mysqli_real_escape_string($kon, $_POST['alamat']);
    $hp = mysqli_real_escape_string($kon, $_POST['hp']);
    $level = (int) $_POST['level'];
    $id = (int) $_POST['id_user'];

    if ($id == 0) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO `user` (nama, username, password, alamat, hp, level)
        VALUES ('$nama', '$username', '$passwordHash', '$alamat', '$hp', $level)";
    } else {
        if ($password != "") {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE `user` SET nama='$nama', username='$username', password='$passwordHash',
            alamat='$alamat', hp='$hp', level=$level WHERE id_user=$id";
        } else {
            $query = "UPDATE `user` SET nama='$nama', username='$username',
            alamat='$alamat', hp='$hp', level=$level WHERE id_user=$id";
        }
    }

    if (mysqli_query($kon, $query)) {
        header("Location: data_user.php");
        exit;
    } else {
        $pesan = "Data gagal disimpan. Periksa kembali username.";
    }
}

if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];

    if ($id != $_SESSION['id_user']) {
        mysqli_query($kon, "DELETE FROM `user` WHERE id_user=$id");
        header("Location: data_user.php");
        exit;
    } else {
        $pesan = "Akun yang sedang digunakan tidak bisa dihapus.";
    }
}

if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $queryEdit = mysqli_query($kon, "SELECT * FROM `user` WHERE id_user=$id");
    $edit = mysqli_fetch_assoc($queryEdit);
}

$hasil = mysqli_query($kon, "SELECT * FROM `user` ORDER BY id_user");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data User</title>
</head>
<body>
<h2>Data User</h2>
<a href="index.php">Kembali ke Home</a>

<p><?php echo $pesan; ?></p>

<h3><?php echo $edit ? "Edit User" : "Tambah User"; ?></h3>
<form method="POST">
    <input type="hidden" name="id_user" value="<?php echo $edit['id_user'] ?? 0; ?>">

    <label>Nama</label><br>
    <input type="text" name="nama" value="<?php echo htmlspecialchars($edit['nama'] ?? ''); ?>" required><br><br>

    <label>Username</label><br>
    <input type="text" name="username" value="<?php echo htmlspecialchars($edit['username'] ?? ''); ?>" required><br><br>

    <label>Password <?php echo $edit ? '(kosongkan jika tidak diubah)' : ''; ?></label><br>
    <input type="password" name="password" <?php echo $edit ? '' : 'required'; ?>><br><br>

    <label>Alamat</label><br>
    <input type="text" name="alamat" value="<?php echo htmlspecialchars($edit['alamat'] ?? ''); ?>"><br><br>

    <label>No. HP</label><br>
    <input type="text" name="hp" value="<?php echo htmlspecialchars($edit['hp'] ?? ''); ?>"><br><br>

    <label>Level</label><br>
    <select name="level">
        <option value="1" <?php echo isset($edit['level']) && $edit['level'] == 1 ? 'selected' : ''; ?>>1 - Owner</option>
        <option value="2" <?php echo isset($edit['level']) && $edit['level'] == 2 ? 'selected' : ''; ?>>2 - Kasir</option>
    </select><br><br>

    <button type="submit" name="simpan">Simpan</button>
    <a href="data_user.php">Batal</a>
</form>

<h3>Daftar User</h3>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>No</th><th>Nama</th><th>Username</th><th>Level</th><th>Aksi</th>
    </tr>
    <?php $no = 1; while ($user = mysqli_fetch_assoc($hasil)) { ?>
    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo htmlspecialchars($user['nama']); ?></td>
        <td><?php echo htmlspecialchars($user['username']); ?></td>
        <td><?php echo $user['level'] == 1 ? 'Owner' : 'Kasir'; ?></td>
        <td>
            <a href="data_user.php?edit=<?php echo $user['id_user']; ?>">Edit</a> |
            <?php if ($user['id_user'] != $_SESSION['id_user']) { ?>
                <a href="data_user.php?hapus=<?php echo $user['id_user']; ?>" onclick="return confirm('Hapus user ini?')">Hapus</a>
            <?php } else { echo 'Akun aktif'; } ?>
        </td>
    </tr>
    <?php } ?>
</table>
</body>
</html>