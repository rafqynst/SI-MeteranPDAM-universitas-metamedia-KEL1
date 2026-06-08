<?php
include 'config/koneksi.php';
session_start();

$id_tagihan = $_GET['id'];

$q = mysqli_query($conn,"
    SELECT *
    FROM tagihan
    WHERE id_tagihan='$id_tagihan'
");

$tagihan = mysqli_fetch_assoc($q);

$id_pelanggan = $tagihan['id_pelanggan'];
$total_bayar  = $tagihan['total_tagihan'];
$id_petugas   = $_SESSION['id_petugas'];

$simpan = mysqli_query($conn,"
    INSERT INTO pembayaran
    (
        id_tagihan,
        id_pelanggan,
        id_petugas,
        metode_pembayaran,
        total_bayar,
        status
    )
    VALUES
    (
        '$id_tagihan',
        '$id_pelanggan',
        '$id_petugas',
        'Cash',
        '$total_bayar',
        'Berhasil'
    )
");

if(!$simpan){
    die(mysqli_error($conn));
}

mysqli_query($conn,"
    UPDATE tagihan
    SET status='Lunas'
    WHERE id_tagihan='$id_tagihan'
");

header("Location: pembayaran_berhasil.php?id=".$id_tagihan);
exit;