<?php
include 'config/koneksi.php';

// Query ambil data tagihan + pelanggan
$query = mysqli_query($conn, "
    SELECT 
        tagihan.*,
        pelanggan.nomor_pelanggan,
        pelanggan.nama_pelanggan
    FROM tagihan
    JOIN pelanggan 
        ON tagihan.id_pelanggan = pelanggan.id_pelanggan
    ORDER BY tagihan.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pemakaian Air</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100">

    <div class="max-w-7xl mx-auto p-6">

        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    Data Pemakaian Air
                </h1>
                <p class="text-slate-500">
                    Kelola data pemakaian pelanggan PDAM
                </p>
            </div>

            <a href="tambah_pemakaian.php"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow transition duration-200">
                + Tambah Pemakaian
            </a>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-md p-5">

            <!-- Search -->
            <div class="mb-5">
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Cari nama pelanggan / nomor pelanggan..."
                    class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">

                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-slate-200 text-slate-700">
                            <th class="p-3 text-left">No</th>
                            <th class="p-3 text-left">No Pelanggan</th>
                            <th class="p-3 text-left">Nama</th>
                            <th class="p-3 text-left">Bulan/Tahun</th>
                            <th class="p-3 text-center">Meter Awal</th>
                            <th class="p-3 text-center">Meter Akhir</th>
                            <th class="p-3 text-center">Pemakaian</th>
                            <th class="p-3 text-right">Tagihan</th>
                            <th class="p-3 text-center">Status</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="tableBody">

                        <?php
                        $no = 1;

                        if (mysqli_num_rows($query) > 0):
                            while ($data = mysqli_fetch_assoc($query)):
                        ?>

                                <tr class="border-b hover:bg-slate-50 transition">
                                    <td class="p-3">
                                        <?= $no++; ?>
                                    </td>

                                    <td class="p-3 nomor">
                                        <?= $data['nomor_pelanggan']; ?>
                                    </td>

                                    <td class="p-3 nama font-medium text-slate-700">
                                        <?= $data['nama_pelanggan']; ?>
                                    </td>

                                    <td class="p-3">
                                        <?= $data['bulan']; ?>/<?= $data['tahun']; ?>
                                    </td>

                                    <td class="p-3 text-center">
                                        <?= number_format($data['meter_bulan_lalu']); ?>
                                    </td>

                                    <td class="p-3 text-center">
                                        <?= number_format($data['meter_bulan_ini']); ?>
                                    </td>

                                    <td class="p-3 text-center font-semibold">
                                        <?= number_format($data['pemakaian']); ?> m³
                                    </td>

                                    <td class="p-3 text-right font-semibold text-green-700 whitespace-nowrap">
                                        Rp <?= number_format($data['total_tagihan'], 0, ',', '.'); ?>
                                    </td>

                                    <td class="p-3 text-center">
                                        <?php if ($data['status'] == 'Lunas'): ?>

                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                                                Lunas
                                            </span>

                                        <?php else: ?>

                                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-medium whitespace-nowrap">
                                                Belum Bayar
                                            </span>

                                        <?php endif; ?>
                                    </td>

                                    <td class="p-3">
                                        <div class="flex gap-2 justify-center">

                                            <a href="edit_pemakaian.php?id=<?= $data['id_tagihan']; ?>"
                                                class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm">
                                                Edit
                                            </a>

                                            <a href="detail_tagihan.php?id=<?= $data['id_tagihan']; ?>"
                                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                                                Detail
                                            </a>

                                            <a href="#"
                                                onclick="konfirmasiHapus(<?= $data['id_tagihan']; ?>)"
                                                class="bg-red-500 text-white px-3 py-1 rounded">
                                                Hapus
                                            </a>

                                            <script>
                                                function hapusData() {
                                                    return confirm("Yakin ingin menghapus data pemakaian ini?");
                                                }
                                            </script>

                                        </div>
                                    </td>
                                </tr>

                            <?php
                            endwhile;
                        else:
                            ?>

                            <tr>
                                <td colspan="10" class="text-center py-10 text-slate-500">
                                    Belum ada data pemakaian
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <!-- Search JS -->
    <script>
        const searchInput = document.getElementById("searchInput");
        const tableRows = document.querySelectorAll("#tableBody tr");

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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function konfirmasiHapus(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak bisa mengembalikan data ini setelah dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'hapus_pemakaian.php?id=' + id;
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

        if (status === 'hapus') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data pemakaian berhasil dihapus',
                timer: 2000,
                showConfirmButton: false
            });

            // hapus parameter dari URL supaya tidak muncul lagi saat refresh
            window.history.replaceState({}, document.title, "pemakaian.php");
        }
    </script>

</body>

</html>