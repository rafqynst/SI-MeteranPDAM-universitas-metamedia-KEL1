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
$qTagihan = mysqli_query($conn, "
    SELECT *
    FROM tagihan
    WHERE id_tagihan = $id_tagihan
");

$tagihan = mysqli_fetch_assoc($qTagihan);

if (!$tagihan) {
    die("Tagihan tidak ditemukan");
}

// kalau sudah lunas
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

// mulai transaksi
mysqli_begin_transaction($conn);

try {

    // INSERT pembayaran
    $id_petugas = $_SESSION['id_petugas'];

    $insert = mysqli_query($conn, "
    INSERT INTO pembayaran (
        id_tagihan,
        id_pelanggan,
        id_petugas,
        metode_pembayaran,
        total_bayar,
        tanggal_bayar,
        status
    )
    VALUES (
        $id_tagihan,
        $id_pelanggan,
        $id_petugas,
        '$metode',
        {$tagihan['total_tagihan']},
        NOW(),
        'Berhasil'
    )
");
    if (!$insert) {
        throw new Exception(mysqli_error($conn));
    }

    // UPDATE status tagihan
    $update = mysqli_query($conn, "
        UPDATE tagihan
        SET status = 'Lunas'
        WHERE id_tagihan = $id_tagihan
    ");

    if (!$update) {
        throw new Exception(mysqli_error($conn));
    }

    mysqli_commit($conn);




    echo "
<!DOCTYPE html>
<html>
<head>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>
<script>
Swal.fire({
    icon: 'success',
    title: 'Pembayaran Berhasil',
    text: 'Tagihan berhasil dilunasi'
}).then(() => {
    window.location = 'pemakaian.php?id=" . $id_tagihan . "';
});
</script>
</body>
</html>
";

    exit;
} catch (Exception $e) {

    mysqli_rollback($conn);

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
    Swal.fire({
        icon:'error',
        title:'Gagal',
        text:'" . $e->getMessage() . "'
    }).then(()=>{
        history.back();
    });
    </script>";
}
