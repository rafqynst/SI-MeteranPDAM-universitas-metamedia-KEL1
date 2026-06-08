<?php
include 'config/koneksi.php';

if (!isset($_GET['id'])) {
    die("ID Tagihan tidak ditemukan.");
}

$id_tagihan = mysqli_real_escape_string($conn, $_GET['id']);

$query = mysqli_query($conn, "
    SELECT
        t.*,
        p.nomor_pelanggan,
        p.nama_pelanggan,
        p.nik,
        p.alamat,
        p.no_hp,
        p.tarif_per_m3,
        p.status AS status_pelanggan,
        p.kategori
    FROM tagihan t
    JOIN pelanggan p
        ON t.id_pelanggan = p.id_pelanggan
    WHERE t.id_tagihan = '$id_tagihan'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data tagihan tidak ditemukan.");
}

$subtotal = $data['pemakaian'] * $data['hpka'];

$warnaKategori = '';

switch ($data['kategori']) {
    case 'RT':
        $warnaKategori = 'bg-green-100 text-green-700';
        break;

    case 'ID':
        $warnaKategori = 'bg-blue-100 text-blue-700';
        break;

    case 'IP':
        $warnaKategori = 'bg-purple-100 text-purple-700';
        break;

    default:
        $warnaKategori = 'bg-gray-100 text-gray-700';
        break;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Tagihan</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @media print {

            .no-print,
            .no-print * {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .card-print {
                box-shadow: none !important;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen py-8 px-4">

    <div class="max-w-6xl mx-auto">

        <div class="bg-white rounded-2xl shadow-lg p-8 card-print">

            <!-- HEADER -->
            <div class="flex justify-between items-center border-b pb-5 mb-6">

                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        Detail Tagihan Air
                    </h1>

                    <p class="text-gray-500 mt-2">
                        ID Tagihan :
                        <?= $data['id_tagihan']; ?>
                    </p>
                </div>

                <div>

                    <?php if ($data['status'] == 'Lunas') : ?>

                        <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full font-semibold">
                            Lunas
                        </span>

                    <?php else : ?>

                        <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full font-semibold">
                            Belum Lunas
                        </span>

                    <?php endif; ?>

                </div>

            </div>

            <!-- INFORMASI PELANGGAN -->
            <div class="mb-8">

                <h2 class="text-xl font-bold text-gray-700 mb-4">
                    Informasi Pelanggan
                </h2>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">

                    <div>
                        <p class="text-gray-500 text-sm">
                            Nomor Pelanggan
                        </p>

                        <p class="font-semibold">
                            <?= $data['nomor_pelanggan']; ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500 text-sm">
                            Nama Pelanggan
                        </p>

                        <p class="font-semibold">
                            <?= $data['nama_pelanggan']; ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500 text-sm">
                            NIK
                        </p>

                        <p class="font-semibold">
                            <?= $data['nik']; ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500 text-sm">
                            No HP
                        </p>

                        <p class="font-semibold">
                            <?= $data['no_hp']; ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500 text-sm">
                            Kategori
                        </p>

                        <span class="<?= $warnaKategori; ?> px-3 py-1 rounded-full text-sm font-semibold">
                            <?= $data['kategori']; ?>
                        </span>
                    </div>

                    <div>
                        <p class="text-gray-500 text-sm">
                            Status Pelanggan
                        </p>

                        <p class="font-semibold">
                            <?= $data['status_pelanggan']; ?>
                        </p>
                    </div>

                    <div class="lg:col-span-2">
                        <p class="text-gray-500 text-sm">
                            Alamat
                        </p>

                        <p class="font-semibold">
                            <?= $data['alamat']; ?>
                        </p>
                    </div>

                </div>

            </div>

            <!-- INFORMASI TAGIHAN -->
            <div class="mb-8">

                <h2 class="text-xl font-bold text-gray-700 mb-4">
                    Informasi Tagihan
                </h2>

                <div class="grid md:grid-cols-3 gap-4">

                    <div>
                        <p class="text-gray-500 text-sm">
                            Tanggal Tagih
                        </p>

                        <p class="font-semibold">
                            <?= date('d-m-Y', strtotime($data['tgl_tagih'])); ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500 text-sm">
                            Bulan
                        </p>

                        <p class="font-semibold">
                            <?= $data['bulan']; ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500 text-sm">
                            Tahun
                        </p>

                        <p class="font-semibold">
                            <?= $data['tahun']; ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500 text-sm">
                            Meter Bulan Lalu
                        </p>

                        <p class="font-semibold">
                            <?= number_format($data['meter_bulan_lalu']); ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500 text-sm">
                            Meter Bulan Ini
                        </p>

                        <p class="font-semibold">
                            <?= number_format($data['meter_bulan_ini']); ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500 text-sm">
                            Pemakaian Air
                        </p>

                        <p class="font-bold text-blue-600">
                            <?= number_format($data['pemakaian']); ?> m³
                        </p>
                    </div>

                </div>

            </div>

            <!-- RINCIAN TAGIHAN -->
            <div class="mb-8">

                <h2 class="text-xl font-bold text-gray-700 mb-4">
                    Rincian Tagihan
                </h2>

                <div class="overflow-x-auto">

                    <table class="w-full border border-gray-200">

                        <thead class="bg-gray-100">

                            <tr>
                                <th class="border p-3 text-left">
                                    Keterangan
                                </th>

                                <th class="border p-3 text-right">
                                    Nilai
                                </th>
                            </tr>

                        </thead>

                        <tbody>

                            <tr>
                                <td class="border p-3">
                                    Pemakaian Air
                                </td>

                                <td class="border p-3 text-right">
                                    <?= number_format($data['pemakaian']); ?> m³
                                </td>
                            </tr>

                            <tr>
                                <td class="border p-3">
                                    Harga Air per m³
                                </td>

                                <td class="border p-3 text-right">
                                    Rp <?= number_format($data['hpka']); ?>
                                </td>
                            </tr>

                            <tr>
                                <td class="border p-3">
                                    Subtotal Pemakaian
                                </td>

                                <td class="border p-3 text-right">
                                    Rp <?= number_format($subtotal); ?>
                                </td>
                            </tr>

                            <tr>
                                <td class="border p-3">
                                    Biaya Admin
                                </td>

                                <td class="border p-3 text-right">
                                    Rp <?= number_format($data['biaya_admin']); ?>
                                </td>
                            </tr>

                            <tr class="bg-green-50">

                                <td class="border p-3 font-bold">
                                    Total Tagihan
                                </td>

                                <td class="border p-3 text-right font-bold text-green-700 text-xl">
                                    Rp <?= number_format($data['total_tagihan']); ?>
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

            <!-- INFORMASI SISTEM -->
            <div class="mb-8">

                <h2 class="text-xl font-bold text-gray-700 mb-4">
                    Informasi Sistem
                </h2>

                <div class="grid md:grid-cols-2 gap-4">

                    <div>
                        <p class="text-gray-500 text-sm">
                            Status Pembayaran
                        </p>

                        <p class="font-semibold">
                            <?= $data['status']; ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500 text-sm">
                            Dibuat Pada
                        </p>

                        <p class="font-semibold">
                            <?= $data['created_at']; ?>
                        </p>
                    </div>

                </div>

            </div>

            <!-- TOMBOL -->
            <div class="flex gap-3 no-print">

                <a href="pemakaian.php"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg transition">
                    Kembali
                </a>
                <?php if (strtolower(trim($data['status'])) != 'lunas'): ?>
                    <a href="pembayaran.php?id=<?= $data['id_tagihan']; ?>"
                        class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg">
                        Bayar Tagihan
                    </a>
                <?php endif; ?>
                <button onclick="window.print()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition">
                    Cetak Tagihan
                </button>

            </div>

        </div>

    </div>

</body>

</html>