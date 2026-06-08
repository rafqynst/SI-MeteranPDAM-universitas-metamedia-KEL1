<?php
include 'config/koneksi.php';

$search = $_GET['search'] ?? '';

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

    WHERE pelanggan.nama_pelanggan
    LIKE '%$search%'

    ORDER BY pembayaran.id_pembayaran DESC
");

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
    <title>Riwayat Pembayaran</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-slate-100">

    <div class="max-w-7xl mx-auto p-6">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">

            <div>
                <h1 class="text-3xl font-bold">
                    Riwayat Pembayaran
                </h1>

                <p class="text-slate-500">
                    Data pembayaran pelanggan PDAM
                </p>
            </div>

            <div class="flex gap-3">



                <a href="pemakaian.php"
                    class="bg-slate-600 hover:bg-slate-700 text-white px-5 py-3 rounded-lg">

                    Kembali
                </a>

            </div>

        </div>

        <!-- Search -->
        <div class="bg-white rounded-xl shadow p-5 mb-6">

            <form method="GET">

                <div class="flex gap-3">

                    <input
                        type="text"
                        name="search"
                        id="searchInput"
                        value="<?= $search; ?>"
                        placeholder="Cari nama pelanggan..."
                        class="w-full border rounded-lg px-4 py-3">

                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 rounded-lg">

                        Cari
                    </button>

                </div>

            </form>

        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow overflow-x-auto">

            <table class="w-full">

                <thead class="bg-slate-200">

                    <tr class="text-left">

                        <th class="p-4">No</th>
                        <th class="p-4">Tanggal</th>
                        <th class="p-4">Pelanggan</th>
                        <th class="p-4">Periode</th>
                        <th class="p-4">Metode</th>
                        <th class="p-4">Total</th>
                        <th class="p-4">Petugas</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Aksi</th>

                    </tr>

                </thead>

                <tbody id="tableHistory">

                    <?php
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($query)):
                    ?>

                        <tr class="border-b hover:bg-slate-50">

                            <td class="p-4">
                                <?= $no++; ?>
                            </td>

                            <td class="p-4">
                                <?= date(
                                    'd-m-Y H:i',
                                    strtotime(
                                        $row['tanggal_bayar']
                                    )
                                ); ?>
                            </td>

                            <td class="p-4">

                                <div class="font-semibold nama">
                                    <?= $row['nama_pelanggan']; ?>
                                </div>

                                <div class="text-sm text-slate-500 nomor">
                                    <?= $row['nomor_pelanggan']; ?>
                                </div>

                            </td>

                            <td class="p-4">

                                <?= $bulan[$row['bulan']] ?? '-'; ?>
                                <?= $row['tahun']; ?>

                            </td>

                            <td class="p-4">

                                <?php
                                if ($row['metode_pembayaran'] == 'Cash') {
                                    $warna =
                                        'bg-green-100 text-green-700';
                                } elseif (
                                    $row['metode_pembayaran']
                                    == 'Transfer'
                                ) {
                                    $warna =
                                        'bg-blue-100 text-blue-700';
                                } else {
                                    $warna =
                                        'bg-purple-100 text-purple-700';
                                }
                                ?>

                                <span class="<?= $warna; ?> px-3 py-1 rounded-full text-sm">

                                    <?= $row['metode_pembayaran']; ?>

                                </span>

                            </td>

                            <td class="p-4 font-semibold">

                                Rp
                                <?= number_format(
                                    $row['total_bayar']
                                ); ?>

                            </td>

                            <td class="p-4">

                                <?= $row['nama_petugas']; ?>

                            </td>

                            <td class="p-4">

                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">

                                    <?= $row['status']; ?>

                                </span>

                            </td>

                            <td class="p-4">

                                <div class="flex gap-2 justify-center">

                                    <a href="cetak_bukti.php?id=<?= $row['id_pembayaran']; ?>"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">

                                        Cetak
                                    </a>

                                    <a href="detail_tagihan.php?id=<?= $row['id_tagihan']; ?>"
                                        class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg text-sm">

                                        Detail
                                    </a>

                                </div>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>


    <script>
        const searchInput = document.getElementById("searchInput");
        const tableRows = document.querySelectorAll("#tableHistory tr");

        searchInput.addEventListener("keyup", function() {

            const keyword = this.value.toLowerCase();

            tableRows.forEach(row => {

                const nama = row.querySelector(".nama");
                const nomor = row.querySelector(".nomor");

                if (!nama || !nomor) return;

                const namaText = nama.textContent.toLowerCase();
                const nomorText = nomor.textContent.toLowerCase();

                if (
                    namaText.includes(keyword) ||
                    nomorText.includes(keyword)
                ) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    </script>
</body>

</html>