<?php
session_start();
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: pemakaian.php");
    exit;
}

// ambil data
$id_tagihan = $_POST['id_tagihan'];
$id_pelanggan = $_POST['id_pelanggan'];
$metode = $_POST['metode_pembayaran'];

$id_petugas = $_SESSION['id_petugas'];

// ambil total tagihan
$queryTagihan = mysqli_query($conn, "
    SELECT *
    FROM tagihan
    WHERE id_tagihan='$id_tagihan'
");

$tagihan = mysqli_fetch_assoc($queryTagihan);

if (!$tagihan) {
    die("Tagihan tidak ditemukan.");
}

$total = $tagihan['total_tagihan'];

// cek sudah lunas
if ($tagihan['status'] == 'Lunas') {

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>

    <script>
        Swal.fire({
            icon:'info',
            title:'Sudah Dibayar',
            text:'Tagihan ini sudah lunas'
        }).then(() => {
            window.location='pemakaian.php';
        });
    </script>
    ";

    exit;
}

// simpan pembayaran
$insert = mysqli_query($conn, "
    INSERT INTO pembayaran (
        id_tagihan,
        id_pelanggan,
        id_petugas,
        metode_pembayaran,
        total_bayar,
        status
    )
    VALUES (
        '$id_tagihan',
        '$id_pelanggan',
        '$id_petugas',
        '$metode',
        '$total',
        'Berhasil'
    )
");

// update status tagihan
if ($insert) {

    mysqli_query($conn, "
        UPDATE tagihan
        SET status='Lunas'
        WHERE id_tagihan='$id_tagihan'
    ");

    $id_pembayaran = mysqli_insert_id($conn);

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>

    <script>
        Swal.fire({
            icon:'success',
            title:'Pembayaran Berhasil!',
            text:'Tagihan berhasil dibayar',
            confirmButtonText:'OK'
        }).then((result) => {

            if(result.isConfirmed){
                window.location=
                'cetak_bukti.php?id=$id_pembayaran';
            }

        });
    </script>
    ";
} else {

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>

    <script>
        Swal.fire({
            icon:'error',
            title:'Gagal',
            text:'Pembayaran gagal'
        }).then(() => {
            history.back();
        });
    </script>
    ";
}