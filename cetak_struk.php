<?php
include 'config/koneksi.php';

if (!isset($_GET['id'])) {
    die("ID Tagihan tidak ditemukan");
}

$id_tagihan = intval($_GET['id']);

$query = mysqli_query($conn, "
    SELECT
        p.*,
        t.bulan,
        t.tahun,
        pl.nama_pelanggan,
        pl.alamat
    FROM pembayaran p
    JOIN tagihan t ON p.id_tagihan = t.id_tagihan
    JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan
    WHERE p.id_tagihan = '$id_tagihan'
    ORDER BY p.id_pembayaran DESC
    LIMIT 1
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data pembayaran tidak ditemukan");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Struk Pembayaran</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
body{
    font-family: Arial, sans-serif;
    background:#f5f5f5;
}

.struk{
    width:350px;
    margin:20px auto;
    background:#fff;
    padding:20px;
    border:1px solid #ccc;
}

.judul{
    text-align:center;
    margin-bottom:15px;
}

.judul h2{
    margin:0;
}

.garis{
    border-top:1px dashed #000;
    margin:10px 0;
}

table{
    width:100%;
    font-size:14px;
}

table td{
    padding:3px 0;
    vertical-align:top;
}

.total{
    font-size:18px;
    font-weight:bold;
}

.footer{
    text-align:center;
    margin-top:15px;
    font-size:12px;
}

.btn-print{
    text-align:center;
    margin-top:20px;
}

@media print{
    .btn-print{
        display:none;
    }

    body{
        background:white;
    }

    .struk{
        border:none;
        margin:0 auto;
    }
}
</style>
</head>

<body>
<div>
    
     <a href="pemakaian.php"
                class="inline-block mt-6 bg-slate-600 hover:bg-slate-700 text-white px-5 py-3 rounded-lg">
                ← Kembali
            </a>

</div>
<div class="struk">

    <div class="judul">
        <h2>PDAM</h2>
        <p>BUKTI PEMBAYARAN TAGIHAN AIR</p>
    </div>

    <div class="garis"></div>

    <table>
        <tr>
            <td>No. Bayar</td>
            <td>: <?= $data['id_pembayaran']; ?></td>
        </tr>

        <tr>
            <td>Tanggal</td>
            <td>: <?= date('d-m-Y H:i', strtotime($data['tanggal_bayar'])); ?></td>
        </tr>

        <tr>
            <td>ID Pelanggan</td>
            <td>: <?= $data['id_pelanggan']; ?></td>
        </tr>

        <tr>
            <td>Nama</td>
            <td>: <?= htmlspecialchars($data['nama_pelanggan']); ?></td>
        </tr>

        <tr>
            <td>Alamat</td>
            <td>: <?= htmlspecialchars($data['alamat']); ?></td>
        </tr>

        <tr>
            <td>Periode</td>
            <td>: <?= $data['bulan']; ?> / <?= $data['tahun']; ?></td>
        </tr>

        <tr>
            <td>Metode</td>
            <td>: <?= $data['metode_pembayaran']; ?></td>
        </tr>
    </table>

    <div class="garis"></div>

    <table>
        <tr>
            <td>Total Bayar</td>
            <td align="right" class="total">
                Rp <?= number_format($data['total_bayar'], 0, ',', '.'); ?>
            </td>
        </tr>
    </table>

    <div class="garis"></div>

    <div class="footer">
        <p>Status : <?= $data['status']; ?></p>
        <p>Terima kasih telah melakukan pembayaran.</p>
        <p>Simpan struk ini sebagai bukti pembayaran.</p>
    </div>

</div>

<div class="btn-print">
   <button
    onclick="window.print()"
    class="inline-flex items-center gap-2
           bg-gradient-to-r from-blue-600 to-cyan-600
           hover:from-blue-700 hover:to-cyan-700
           text-white font-bold
           px-6 py-3
           rounded-xl
           shadow-lg
           hover:scale-105
           transition duration-300">
    
    <span>🖨️</span>
    <span>Cetak Struk</span>

</button>

</div>
<br>


</body>
</html>