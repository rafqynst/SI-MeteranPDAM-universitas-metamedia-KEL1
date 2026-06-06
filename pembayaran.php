<?php
include 'config/koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: pemakaian.php");
    exit;
}

$id = $_GET['id'];

$query = mysqli_query($conn, "
SELECT
t.*,
p.nomor_pelanggan,
p.nama_pelanggan,
p.alamat
FROM tagihan t
JOIN pelanggan p
ON t.id_pelanggan = p.id_pelanggan
WHERE t.id_tagihan='$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: pemakaian.php");
    exit;
}

if ($data['status'] == 'Lunas') {
    header("Location: detail_tagihan.php?id=".$id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pembayaran Tagihan</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-slate-100">

<div class="max-w-4xl mx-auto p-6">

<div class="bg-white rounded-2xl shadow-lg p-8">

<h1 class="text-3xl font-bold mb-6">
Pembayaran Tagihan PDAM
</h1>

<form action="proses_bayar.php" method="POST">

<input
type="hidden"
name="id_tagihan"
value="<?= $data['id_tagihan']; ?>">

<input
type="hidden"
name="total_bayar"
value="<?= $data['total_tagihan']; ?>">

<div class="grid md:grid-cols-2 gap-6">

<div>

<label class="font-semibold">
Nomor Pelanggan
</label>

<input
type="text"
readonly
value="<?= $data['nomor_pelanggan']; ?>"
class="w-full mt-2 border rounded-lg p-3 bg-slate-100">

</div>

<div>

<label class="font-semibold">
Nama Pelanggan
</label>

<input
type="text"
readonly
value="<?= $data['nama_pelanggan']; ?>"
class="w-full mt-2 border rounded-lg p-3 bg-slate-100">

</div>

<div>

<label class="font-semibold">
Bulan
</label>

<input
type="text"
readonly
value="<?= $data['bulan']; ?>"
class="w-full mt-2 border rounded-lg p-3 bg-slate-100">

</div>

<div>

<label class="font-semibold">
Tahun
</label>

<input
type="text"
readonly
value="<?= $data['tahun']; ?>"
class="w-full mt-2 border rounded-lg p-3 bg-slate-100">

</div>

<div>

<label class="font-semibold">
Pemakaian Air
</label>

<input
type="text"
readonly
value="<?= number_format($data['pemakaian']); ?> m³"
class="w-full mt-2 border rounded-lg p-3 bg-slate-100">

</div>

<div>

<label class="font-semibold">
Total Tagihan
</label>

<input
type="text"
readonly
value="Rp <?= number_format($data['total_tagihan'],0,',','.'); ?>"
class="w-full mt-2 border rounded-lg p-3 bg-slate-100 font-bold text-blue-600">

</div>

</div>

<hr class="my-8">

<h2 class="text-xl font-semibold mb-4">
Metode Pembayaran
</h2>

<div class="space-y-4">

<label class="flex items-center gap-3">
<input
type="radio"
name="metode"
value="Cash"
required>

Cash
</label>

<label class="flex items-center gap-3">
<input
type="radio"
name="metode"
value="Transfer">

Transfer Bank
</label>

<label class="flex items-center gap-3">
<input
type="radio"
name="metode"
value="QRIS">

QRIS
</label>

<label class="flex items-center gap-3">
<input
type="radio"
name="metode"
value="E-Wallet">

E-Wallet
</label>

</div>

<div class="flex gap-3 mt-8">

<a
href="detail_tagihan.php?id=<?= $data['id_tagihan']; ?>"
class="bg-slate-500 text-white px-5 py-3 rounded-lg">
Kembali
</a>

<button
type="submit"
class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg">
Bayar Sekarang
</button>

</div>

</form>

</div>

</div>

</body>
</html>