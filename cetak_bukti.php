<?php
include 'config/koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: riwayat_pembayaran.php");
    exit;
}

$id = intval($_GET['id']);

$query = mysqli_query($conn, "
    SELECT 
        pembayaran.*,
        pelanggan.nomor_pelanggan,
        pelanggan.nama_pelanggan,
        pelanggan.alamat,

        tagihan.bulan,
        tagihan.tahun,
        tagihan.pemakaian,
        tagihan.total_tagihan,

        petugas.nama_petugas

    FROM pembayaran

    JOIN pelanggan
    ON pembayaran.id_pelanggan =
    pelanggan.id_pelanggan

    JOIN tagihan
    ON pembayaran.id_tagihan =
    tagihan.id_tagihan

    JOIN petugas
    ON pembayaran.id_petugas =
    petugas.id_petugas

    WHERE pembayaran.id_pembayaran='$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data pembayaran tidak ditemukan");
}

$bulan = [
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
    <title>Cetak Bukti</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body class="bg-slate-100 p-8">

    <div class="max-w-md mx-auto">

        <div class="bg-white shadow-xl rounded-xl p-8 border">

            <div class="text-center border-b pb-4">

                <h1 class="text-2xl font-bold">
                    PDAM KOTA PADANG
                </h1>

                <p class="text-slate-500">
                    Bukti Pembayaran Air
                </p>
            </div>

            <div class="space-y-3 mt-6 text-sm">

                <div class="flex justify-between">
                    <span>Tanggal</span>
                    <span>
                        <?= date(
                            'd-m-Y H:i',
                            strtotime($data['tanggal_bayar'])
                        ); ?>
                    </span>
                </div>

                <div class="flex justify-between">
                    <span>No Pelanggan</span>
                    <span>
                        <?= $data['nomor_pelanggan']; ?>
                    </span>
                </div>

                <div class="flex justify-between">
                    <span>Nama</span>
                    <span>
                        <?= $data['nama_pelanggan']; ?>
                    </span>
                </div>

                <div class="flex justify-between">
                    <span>Periode</span>
                    <span>
                        <?= $bulan[$data['bulan']]; ?>
                        <?= $data['tahun']; ?>
                    </span>
                </div>

                <div class="flex justify-between">
                    <span>Pemakaian</span>
                    <span>
                        <?= number_format(
                            $data['pemakaian']
                        ); ?> m³
                    </span>
                </div>

                <div class="flex justify-between">
                    <span>Metode</span>
                    <span>
                        <?= $data['metode_pembayaran']; ?>
                    </span>
                </div>

                <div class="flex justify-between font-bold text-lg border-t pt-3">
                    <span>Total</span>
                    <span>
                        Rp
                        <?= number_format(
                            $data['total_bayar']
                        ); ?>
                    </span>
                </div>

                <div class="flex justify-between">
                    <span>Status</span>

                    <span class="text-green-600 font-bold">
                        LUNAS
                    </span>
                </div>

                <div class="flex justify-between">
                    <span>Petugas</span>
                    <span>
                        <?= $data['nama_petugas']; ?>
                    </span>
                </div>

            </div>

            <div class="text-center mt-8 border-t pt-4 text-sm text-slate-500">

                Terima kasih telah melakukan pembayaran

            </div>
        </div>

        <div class="flex gap-3 mt-5 no-print">

            <button
                onclick="window.print()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg w-full">

                Print
            </button>

            <a href="riwayat_pembayaran.php"
                class="bg-slate-600 hover:bg-slate-700 text-white px-5 py-3 rounded-lg text-center w-full">

                Kembali
            </a>

        </div>

    </div>

</body>

</html>