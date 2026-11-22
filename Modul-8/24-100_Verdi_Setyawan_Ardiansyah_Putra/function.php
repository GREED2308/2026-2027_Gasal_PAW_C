<?php
session_start();
require_once "db.php";

function harusLogin() {
    if (!isset($_SESSION['id_user'])) {
        header("Location: login.php");
        exit;
    }
}

function hanyaOwner() {
    harusLogin();

    if ($_SESSION['level'] != 1) {
        header("Location: index.php");
        exit;
    }
}
?>