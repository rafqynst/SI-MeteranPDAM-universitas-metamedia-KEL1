<?php
include 'config/koneksi.php';

$id = $_GET['id'];

// cek data
$cek = mysqli_query($conn, "SELECT * FROM tagihan WHERE id_tagihan='$id'");
$data = mysqli_fetch_assoc($cek);

if (!$data) {
    header("Location: pemakaian.php?status=notfound");
    exit;
}

// hapus
mysqli_query($conn, "DELETE FROM tagihan WHERE id_tagihan='$id'");

// redirect pakai status sukses
header("Location: pemakaian.php?status=hapus");
exit;
?>