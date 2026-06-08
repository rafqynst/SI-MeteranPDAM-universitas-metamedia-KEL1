<?php
include 'config/koneksi.php';

$bulan = $_GET['bulan'] ?? '';
$tahun = $_GET['tahun'] ?? '';

$where = "";

if (!empty($bulan) && !empty($tahun)) {
    $where = "WHERE t.bulan='$bulan' AND t.tahun='$tahun'";
} elseif (!empty($bulan)) {
    $where = "WHERE t.bulan='$bulan'";
} elseif (!empty($tahun)) {
    $where = "WHERE t.tahun='$tahun'";
}

$query = mysqli_query($conn, "
    SELECT
        p.id_pembayaran,
        p.metode_pembayaran,
        p.total_bayar,
        p.tanggal_bayar,
        p.status AS status_bayar,

        pl.nomor_pelanggan,
        pl.nama_pelanggan,
        pl.alamat,
        pl.kategori,
        pl.tarif_per_m3,

        t.bulan,
        t.tahun,
        t.meter_bulan_lalu,
        t.meter_bulan_ini,
        t.pemakaian,
        t.hpka,
        t.biaya_admin,
        t.total_tagihan,

        pt.nama_petugas

    FROM pembayaran p

    JOIN pelanggan pl
        ON p.id_pelanggan = pl.id_pelanggan

    JOIN tagihan t
        ON p.id_tagihan = t.id_tagihan

    LEFT JOIN petugas pt
        ON p.id_petugas = pt.id_petugas

    $where

    ORDER BY p.tanggal_bayar DESC
");

$statistik = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT
        COUNT(p.id_pembayaran) AS total_transaksi,
        SUM(p.total_bayar) AS total_pemasukan,
        SUM(t.pemakaian) AS total_pemakaian

    FROM pembayaran p

    JOIN tagihan t
        ON p.id_tagihan = t.id_tagihan

    $where
"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Laporan Pembayaran</title>

<script src="https://cdn.tailwindcss.com"></script>

<style>
@media print {

    .no-print {
        display: none;
    }

    body {
        background: white;
    }

    .print-area {
        box-shadow: none !important;
    }
}
</style>

</head>
<body class="bg-gray-100">

<div class="container mx-auto p-6">

    <div class="bg-white rounded-xl shadow-lg p-6 print-area">

        <div class="text-center mb-6">

            <h1 class="text-3xl font-bold">
                LAPORAN PEMBAYARAN AIR
            </h1>

            <p class="text-gray-500">
                Sistem Meteran Air
            </p>

        </div>

        <!-- FILTER -->

        <form method="GET" class="no-print mb-6">

            <div class="flex flex-wrap gap-3 items-end">

                <select name="bulan" class="border rounded-lg px-4 py-2">
    <option value="">Semua</option>

    <?php
    $bulanList = [
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

    foreach($bulanList as $key => $nama){
        $selected = ($bulan == $key) ? 'selected' : '';
        echo "<option value='$key' $selected>$nama</option>";
    }
    ?>
</select>

                <div>
                    <label class="block mb-1 font-medium">
                        Tahun
                    </label>

                    <input
                        type="number"
                        name="tahun"
                        value="<?= $tahun ?>"
                        class="border rounded-lg px-4 py-2">
                </div>

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">

                    Tampilkan

                </button>

                <button
                    type="button"
                    onclick="window.print()"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">

                    Cetak

                </button>

            </div>

        </form>

        <!-- STATISTIK -->

        <div class="grid md:grid-cols-3 gap-4 mb-6">

            <div class="bg-green-500 text-white rounded-xl p-4">

                <h3 class="font-semibold">
                    Total Pemasukan
                </h3>

                <p class="text-2xl font-bold">
                    Rp <?= number_format($statistik['total_pemasukan'] ?? 0,0,',','.'); ?>
                </p>

            </div>

            <div class="bg-blue-500 text-white rounded-xl p-4">

                <h3 class="font-semibold">
                    Total Pemakaian
                </h3>

                <p class="text-2xl font-bold">
                    <?= number_format($statistik['total_pemakaian'] ?? 0,0,',','.'); ?> m³
                </p>

            </div>

            <div class="bg-purple-500 text-white rounded-xl p-4">

                <h3 class="font-semibold">
                    Total Transaksi
                </h3>

                <p class="text-2xl font-bold">
                    <?= number_format($statistik['total_transaksi'] ?? 0); ?>
                </p>

            </div>

        </div>

        <!-- TABEL -->

        <div class="overflow-x-auto">

            <table class="w-full text-sm border">

                <thead class="bg-blue-600 text-white">

                    <tr>
                        <th class="p-2">No</th>
                        <th class="p-2">Pelanggan</th>
                        <th class="p-2">Kategori</th>
                        <th class="p-2">Periode</th>
                        <th class="p-2">Meter Awal</th>
                        <th class="p-2">Meter Akhir</th>
                        <th class="p-2">Pemakaian</th>
                        <th class="p-2">Tarif</th>
                        <th class="p-2">HPKA</th>
                        <th class="p-2">Admin</th>
                        <th class="p-2">Tagihan</th>
                        <th class="p-2">Metode</th>
                        <th class="p-2">Bayar</th>
                        <th class="p-2">Tanggal</th>
                        <th class="p-2">Petugas</th>
                        <th class="p-2">Status</th>
                    </tr>

                </thead>

                <tbody>

                <?php
                $no = 1;

                while($data = mysqli_fetch_assoc($query)){
                ?>

                    <tr class="border-b hover:bg-gray-50">

                        <td class="p-2"><?= $no++; ?></td>

                        <td class="p-2">
                            <b><?= $data['nama_pelanggan']; ?></b><br>
                            <?= $data['nomor_pelanggan']; ?>
                        </td>

                        <td class="p-2">
                            <?= $data['kategori']; ?>
                        </td>

                        <td class="p-2">
                            <?= $data['bulan']; ?>
                            <?= $data['tahun']; ?>
                        </td>

                        <td class="p-2">
                            <?= $data['meter_bulan_lalu']; ?>
                        </td>

                        <td class="p-2">
                            <?= $data['meter_bulan_ini']; ?>
                        </td>

                        <td class="p-2">
                            <?= $data['pemakaian']; ?> m³
                        </td>

                        <td class="p-2">
                            Rp <?= number_format($data['tarif_per_m3'],0,',','.'); ?>
                        </td>

                        <td class="p-2">
                            Rp <?= number_format($data['hpka'],0,',','.'); ?>
                        </td>

                        <td class="p-2">
                            Rp <?= number_format($data['biaya_admin'],0,',','.'); ?>
                        </td>

                        <td class="p-2 font-bold">
                            Rp <?= number_format($data['total_tagihan'],0,',','.'); ?>
                        </td>

                        <td class="p-2">
                            <?= $data['metode_pembayaran']; ?>
                        </td>

                        <td class="p-2 text-green-600 font-bold">
                            Rp <?= number_format($data['total_bayar'],0,',','.'); ?>
                        </td>

                        <td class="p-2">
                            <?= date('d-m-Y', strtotime($data['tanggal_bayar'])); ?>
                        </td>

                        <td class="p-2">
                            <?= $data['nama_petugas']; ?>
                        </td>

                        <td class="p-2">
                            <?= $data['status_bayar']; ?>
                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>
</html>