<?php
include 'config/koneksi.php';
session_start();

if (!isset($_GET['id'])) {
    header("Location: pemakaian.php");
    exit;
}


$id_tagihan = intval($_GET['id']);

// Ambil detail tagihan + pelanggan
$query = mysqli_query($conn, "
    SELECT 
        tagihan.*,
        pelanggan.nomor_pelanggan,
        pelanggan.nama_pelanggan,
        pelanggan.alamat,
        pelanggan.no_hp
    FROM tagihan
    JOIN pelanggan 
    ON tagihan.id_pelanggan = pelanggan.id_pelanggan
    WHERE tagihan.id_tagihan = '$id_tagihan'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data tagihan tidak ditemukan.");
}

// Proteksi jika sudah lunas
if ($data['status'] == 'Lunas') {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'info',
            title: 'Sudah Dibayar',
            text: 'Tagihan ini sudah lunas'
        }).then(() => {
            window.location='pemakaian.php';
        });
    </script>";
    exit;
}

$nama_bulan = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember'
];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pembayaran PDAM</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-slate-100">

    <div class="max-w-5xl mx-auto p-6">

        <div class="bg-white rounded-2xl shadow-lg p-8">

            <div class="flex justify-between items-center mb-8">

                <h1 class="text-3xl font-bold">
                    Pembayaran Tagihan PDAM
                </h1>

                <a href="detail_tagih.php?id=<?= $data['id_tagihan']; ?>"
                    class="bg-slate-600 hover:bg-slate-700 text-white px-5 py-3 rounded-lg">
                    ← Kembali
                </a>
            </div>

            <form action="proses_bayar.php" method="POST">

                <input type="hidden"
                    name="id_tagihan"
                    value="<?= $data['id_tagihan']; ?>">

                <input type="hidden"
                    name="id_pelanggan"
                    value="<?= $data['id_pelanggan']; ?>">

                <!-- Detail pelanggan -->
                <div class="grid md:grid-cols-2 gap-6 mb-8">

                    <div class="bg-slate-50 rounded-xl p-5 border">

                        <h2 class="font-bold text-lg mb-4">
                            Data Pelanggan
                        </h2>

                        <div class="space-y-2">

                            <p>
                                <span class="font-semibold">
                                    No Pelanggan:
                                </span>
                                <?= $data['nomor_pelanggan']; ?>
                            </p>

                            <p>
                                <span class="font-semibold">
                                    Nama:
                                </span>
                                <?= $data['nama_pelanggan']; ?>
                            </p>

                            <p>
                                <span class="font-semibold">
                                    No HP:
                                </span>
                                <?= $data['no_hp']; ?>
                            </p>

                            <p>
                                <span class="font-semibold">
                                    Alamat:
                                </span>
                                <?= $data['alamat']; ?>
                            </p>

                        </div>
                    </div>

                    <!-- Detail tagihan -->
                    <div class="bg-slate-50 rounded-xl p-5 border">

                        <h2 class="font-bold text-lg mb-4">
                            Detail Tagihan
                        </h2>

                        <div class="space-y-2">

                            <p>
                                <span class="font-semibold">
                                    Periode:
                                </span>

                                <?= $nama_bulan[$data['bulan']]; ?>
                                <?= $data['tahun']; ?>
                            </p>

                            <p>
                                <span class="font-semibold">
                                    Pemakaian:
                                </span>

                                <?= number_format($data['pemakaian']); ?> m³
                            </p>

                            <p>
                                <span class="font-semibold">
                                    Meter:
                                </span>

                                <?= $data['meter_bulan_lalu']; ?>
                                →
                                <?= $data['meter_bulan_ini']; ?>
                            </p>

                            <p>
                                <span class="font-semibold">
                                    HPKA:
                                </span>

                                Rp
                                <?= number_format($data['hpka']); ?>
                            </p>

                            <p>
                                <span class="font-semibold">
                                    Biaya Admin:
                                </span>

                                Rp
                                <?= number_format($data['biaya_admin']); ?>
                            </p>

                            <p class="text-xl font-bold text-blue-700 mt-3">

                                Total:
                                Rp
                                <?= number_format($data['total_tagihan']); ?>

                            </p>

                        </div>
                    </div>
                </div>

                <!-- metode pembayaran -->
                <div class="mb-8">

                    <h2 class="font-bold text-lg mb-4">
                        Pilih Metode Pembayaran
                    </h2>

                    <div class="space-y-3">

                        <label class="flex items-center gap-3 border p-4 rounded-lg cursor-pointer">

                            <input type="radio"
                                name="metode_pembayaran"
                                value="Cash"
                                required>

                            <span class="font-medium">
                                Cash
                            </span>
                        </label>

                        <label class="flex items-center gap-3 border p-4 rounded-lg cursor-pointer">

                            <input type="radio"
                                name="metode_pembayaran"
                                value="Transfer">

                            <span class="font-medium">
                                Transfer Bank
                            </span>
                        </label>

                        <label class="flex items-center gap-3 border p-4 rounded-lg cursor-pointer">

                            <input type="radio"
                                name="metode_pembayaran"
                                value="QR Code">

                            <span class="font-medium">
                                QR Code
                            </span>
                        </label>

                    </div>
                </div>

                <!-- transfer -->
                <div id="transfer-box"
                    class="hidden bg-blue-50 border border-blue-200 rounded-xl p-5 mb-6">

                    <h3 class="font-bold text-lg mb-3">
                        Transfer Bank
                    </h3>

                    <p>
                        Bank BRI
                    </p>

                    <p class="font-bold text-xl">
                        2213 0101 7938 504
                    </p>

                    <p>
                        Meteran PDAM
                    </p>
                </div>

                <!-- qr -->
                <div id="qr-box"
                    class="hidden bg-green-50 border border-green-200 rounded-xl p-5 mb-6 text-center">

                    <h3 class="font-bold text-lg mb-3">
                        Scan QR Code
                    </h3>

                    <img src="assets/img/pakar balak.png"
     class="w-72 mx-auto rounded-xl shadow-lg"
     alt="QRIS">

                    <p class="mt-4 text-slate-600">
                        Scan QR untuk melakukan pembayaran
                    </p>
                </div>

                <div class="flex gap-4">

                   <a href="bayar_proses.php?id=<?= $id_tagihan ?>"
                    class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold">
                    Bayar 
                    </a>

                </div>

            </form>

        </div>
    </div>

    <script>
        const metode = document.querySelectorAll(
            'input[name="metode_pembayaran"]'
        );

        const transferBox =
            document.getElementById('transfer-box');

        const qrBox =
            document.getElementById('qr-box');

        metode.forEach(item => {

            item.addEventListener('change', function() {

                transferBox.classList.add('hidden');
                qrBox.classList.add('hidden');

                if (this.value === 'Transfer') {
                    transferBox.classList.remove('hidden');
                }

                if (this.value === 'QR Code') {
                    qrBox.classList.remove('hidden');
                }
            });
        });
    </script>

</body>

</html>