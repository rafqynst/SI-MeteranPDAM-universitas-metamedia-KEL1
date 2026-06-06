<?php
include 'config/koneksi.php';

$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

$where = "";

if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {

    $where = "
        WHERE DATE(pembayaran.tanggal_bayar)
        BETWEEN '$tanggal_awal'
        AND '$tanggal_akhir'
    ";
}

$query = mysqli_query($conn, "
    SELECT
        pembayaran.*,

        pelanggan.nomor_pelanggan,
        pelanggan.nama_pelanggan,

        petugas.nama_petugas,

        tagihan.bulan,
        tagihan.tahun

    FROM pembayaran

    JOIN pelanggan
    ON pembayaran.id_pelanggan =
    pelanggan.id_pelanggan

    JOIN petugas
    ON pembayaran.id_petugas =
    petugas.id_petugas

    JOIN tagihan
    ON pembayaran.id_tagihan =
    tagihan.id_tagihan

    $where

    ORDER BY pembayaran.id_pembayaran DESC
");

$total_pemasukan = 0;

$bulan = [
    1 => 'Jan',
    2 => 'Feb',
    3 => 'Mar',
    4 => 'Apr',
    5 => 'Mei',
    6 => 'Jun',
    7 => 'Jul',
    8 => 'Ags',
    9 => 'Sep',
    10 => 'Okt',
    11 => 'Nov',
    12 => 'Des'
];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pembayaran</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100">

    <div class="max-w-7xl mx-auto p-6">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">

            <div>
                <h1 class="text-3xl font-bold">
                    Laporan Pembayaran
                </h1>

                <p class="text-slate-500">
                    Data pembayaran pelanggan PDAM
                </p>
            </div>

            <a href="riwayat_pembayaran.php"
                class="bg-slate-600 hover:bg-slate-700 text-white px-5 py-3 rounded-lg">

                Kembali
            </a>

        </div>

        <!-- Filter -->
        <div class="bg-white rounded-xl shadow p-6 mb-6">

            <form method="GET">

                <div class="grid md:grid-cols-4 gap-4">

                    <div>
                        <label class="block mb-2 font-medium">
                            Tanggal Awal
                        </label>

                        <input
                            type="date"
                            name="tanggal_awal"
                            value="<?= $tanggal_awal; ?>"
                            class="w-full border rounded-lg p-3">
                    </div>

                    <div>
                        <label class="block mb-2 font-medium">
                            Tanggal Akhir
                        </label>

                        <input
                            type="date"
                            name="tanggal_akhir"
                            value="<?= $tanggal_akhir; ?>"
                            class="w-full border rounded-lg p-3">
                    </div>

                    <div class="flex items-end">

                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg w-full">

                            Tampilkan
                        </button>

                    </div>

                    <div class="flex items-end">

                        <a href="cetak_laporan.php?tanggal_awal=<?= $tanggal_awal; ?>&tanggal_akhir=<?= $tanggal_akhir; ?>"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg w-full text-center">

                            Export Excel
                        </a>

                    </div>

                </div>

            </form>

        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow overflow-x-auto">

            <table class="w-full">

                <thead class="bg-slate-200">

                    <tr>

                        <th class="p-4 text-left">No</th>
                        <th class="p-4 text-left">Tanggal</th>
                        <th class="p-4 text-left">Pelanggan</th>
                        <th class="p-4 text-left">Periode</th>
                        <th class="p-4 text-left">Metode</th>
                        <th class="p-4 text-left">Petugas</th>
                        <th class="p-4 text-left">Total</th>

                    </tr>

                </thead>

                <tbody>

                    <?php
                    $no = 1;

                    while ($row =
                        mysqli_fetch_assoc($query)
                    ):

                        $total_pemasukan +=
                            $row['total_bayar'];
                    ?>

                        <tr class="border-b hover:bg-slate-50">

                            <td class="p-4">
                                <?= $no++; ?>
                            </td>

                            <td class="p-4">

                                <?= date(
                                    'd-m-Y',
                                    strtotime(
                                        $row['tanggal_bayar']
                                    )
                                ); ?>

                            </td>

                            <td class="p-4">

                                <div class="font-semibold">
                                    <?= $row['nama_pelanggan']; ?>
                                </div>

                                <div class="text-sm text-slate-500">
                                    <?= $row['nomor_pelanggan']; ?>
                                </div>

                            </td>

                            <td class="p-4">

                                <?= $bulan[$row['bulan']] ?? '-'; ?>
                                <?= $row['tahun']; ?>

                            </td>

                            <td class="p-4">
                                <?= $row['metode_pembayaran']; ?>
                            </td>

                            <td class="p-4">
                                <?= $row['nama_petugas']; ?>
                            </td>

                            <td class="p-4 font-semibold">

                                Rp
                                <?= number_format(
                                    $row['total_bayar']
                                ); ?>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                </tbody>

                <tfoot class="bg-slate-100 font-bold">

                    <tr>

                        <td colspan="6"
                            class="p-4 text-right">

                            Total Pemasukan

                        </td>

                        <td class="p-4 text-green-700">

                            Rp
                            <?= number_format(
                                $total_pemasukan
                            ); ?>

                        </td>

                    </tr>

                </tfoot>

            </table>

        </div>

    </div>

</body>

</html>