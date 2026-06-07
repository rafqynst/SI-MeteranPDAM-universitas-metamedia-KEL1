<?php
include 'config/koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: tagihan.php");
    exit;
}

$id_tagihan = intval($_POST['id_tagihan']);
$id_pelanggan = intval($_POST['id_pelanggan']);
$metode = mysqli_real_escape_string($conn, $_POST['metode_pembayaran']);

// cek tagihan
$qTagihan = mysqli_query($conn,"
    SELECT *
    FROM tagihan
    WHERE id_tagihan='$id_tagihan'
");

$tagihan = mysqli_fetch_assoc($qTagihan);

if (!$tagihan) {
    die("Tagihan tidak ditemukan");
}

if ($tagihan['status'] == 'Lunas') {

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
    Swal.fire({
        icon:'info',
        title:'Sudah Lunas',
        text:'Tagihan ini sudah dibayar'
    }).then(()=>{
        window.location='tagihan.php';
    });
    </script>";

    exit;
}

mysqli_begin_transaction($conn);

try {

    // simpan pembayaran
    mysqli_query($conn,"
        INSERT INTO pembayaran (
            id_tagihan,
            id_pelanggan,
            tanggal_bayar,
            metode_pembayaran,
            jumlah_bayar,
            status
        )
        VALUES (
            '$id_tagihan',
            '$id_pelanggan',
            NOW(),
            '$metode',
            '{$tagihan['total_tagihan']}',
            'Berhasil'
        )
    ");

    // update status tagihan
    mysqli_query($conn,"
        UPDATE tagihan
        SET status='Lunas'
        WHERE id_tagihan='$id_tagihan'
    ");

    mysqli_commit($conn);

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
    Swal.fire({
        icon:'success',
        title:'Pembayaran Berhasil',
        text:'Tagihan berhasil dilunasi'
    }).then(()=>{
        window.location='detail_tagihan.php?id=$id_tagihan';
    });
    </script>";

} catch (Exception $e) {

    mysqli_rollback($conn);

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
    Swal.fire({
        icon:'error',
        title:'Gagal',
        text:'Pembayaran gagal diproses'
    }).then(()=>{
        history.back();
    });
    </script>";
}