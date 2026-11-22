<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_penjualan";

$kon = mysqli_connect($host, $user, $pass, $db);

if (!$kon) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>