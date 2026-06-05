<?php
include 'config/koneksi.php';

if (!isset($_GET['id'])) {
    header('Location: pemakaian.php');
    exit;
}

$id = $_GET['id'];



// ambil data tagihan
$query = mysqli_query($conn, "
    SELECT
        tagihan.*,
        pelanggan.nomor_pelanggan,
        pelanggan.nama_pelanggan,
        pelanggan.alamat,
        pelanggan.no_hp,
        pelanggan.kategori
    FROM tagihan
    JOIN pelanggan
    ON tagihan.id_pelanggan = pelanggan.id_pelanggan
    WHERE id_tagihan='$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    header('Location: pemakaian.php');
    exit;
}

// nama bulan
$namaBulan = [
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
    <title>Detail Tagihan</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="bg-slate-100">

    <div class="max-w-5xl mx-auto p-6">

        <div class="bg-white rounded-2xl shadow-lg p-8">

            <div class="flex justify-between items-center mb-8">

                <h1 class="text-3xl font-bold">
                    Detail Tagihan
                </h1>

                <?php if ($data['status'] == 'Lunas'): ?>

                    <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full font-semibold">
                        Lunas
                    </span>

                <?php else: ?>

                    <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full font-semibold">
                        Belum Bayar
                    </span>

                <?php endif; ?>

            </div>

            <!-- pelanggan -->
            <div class="grid md:grid-cols-2 gap-6 mb-8">

                <div class="bg-slate-50 p-5 rounded-xl">

                    <h2 class="font-bold text-lg mb-4">
                        Data Pelanggan
                    </h2>

                    <div class="space-y-3">

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
                                Kategori:
                            </span>
                            <?= $data['kategori']; ?>
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

                <!-- tagihan -->
                <div class="bg-slate-50 p-5 rounded-xl">

                    <h2 class="font-bold text-lg mb-4">
                        Data Tagihan
                    </h2>

                    <div class="space-y-3">

                        <p>
                            <span class="font-semibold">
                                Periode:
                            </span>

                            <?= $namaBulan[$data['bulan']]; ?>
                            <?= $data['tahun']; ?>
                        </p>

                        <p>
                            <span class="font-semibold">
                                Meter Awal:
                            </span>
                            <?= number_format($data['meter_bulan_lalu']); ?>
                            m³
                        </p>

                        <p>
                            <span class="font-semibold">
                                Meter Akhir:
                            </span>
                            <?= number_format($data['meter_bulan_ini']); ?>
                            m³
                        </p>

                        <p>
                            <span class="font-semibold">
                                Pemakaian:
                            </span>
                            <?= number_format($data['pemakaian']); ?>
                            m³
                        </p>

                        <p>
                            <span class="font-semibold">
                                Tarif/m³:
                            </span>
                            Rp <?= number_format($data['hpka'], 0, ',', '.'); ?>
                        </p>

                        <p>
                            <span class="font-semibold">
                                Biaya Admin:
                            </span>
                            Rp <?= number_format($data['biaya_admin'], 0, ',', '.'); ?>
                        </p>

                        <hr>

                        <p class="text-xl font-bold text-blue-700">

                            Total Tagihan:
                            Rp <?= number_format($data['total_tagihan'], 0, ',', '.'); ?>

                        </p>

                    </div>

                </div>

            </div>

            <!-- tombol -->
            <div class="flex gap-3">

                <a href="pemakaian.php"
                    class="bg-slate-500 text-white px-5 py-3 rounded-lg">
                    Kembali
                </a>

                <?php if ($data['status'] == 'Belum Bayar'): ?>


                    <a
                        href="pembayaran.php?id=<?= $data['id_tagihan']; ?>"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg">
                        Bayar Sekarang
                    </a>



                <?php endif; ?>

            </div>

        </div>
    </div>

    

</body>

</html>