<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

/* HAPUS DATA */
if (isset($_GET['hapus'])) {

    $id = (int) $_GET['hapus'];

    mysqli_query(
        $conn,
        "DELETE FROM pelanggan
        WHERE id_pelanggan='$id'"
    );

    $_SESSION['success_delete'] =
        "Data pelanggan berhasil dihapus";

    header("Location: pelanggan.php");
    exit;
}

/* PENCARIAN */
$cari = '';

if (isset($_GET['cari'])) {
    $cari = mysqli_real_escape_string(
        $conn,
        $_GET['cari']
    );

    $query = mysqli_query(
        $conn,
        "SELECT *
         FROM pelanggan
         WHERE nama_pelanggan LIKE '%$cari%'
         OR nomor_pelanggan LIKE '%$cari%'
         OR no_hp LIKE '%$cari%'
         ORDER BY id_pelanggan DESC"
    );
} else {

    $query = mysqli_query(
        $conn,
        "SELECT *
         FROM pelanggan
         ORDER BY id_pelanggan DESC"
    );
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Pelanggan</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<!-- MODAL HAPUS -->

<div
id="modalHapus"
class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="modal-content bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">

        <div class="p-6">

            <div class="text-center">

                <div class="text-6xl mb-4">
                    🗑️
                </div>

                <h3 class="text-2xl font-bold text-gray-800">
                    Hapus Pelanggan
                </h3>

                <p
                id="namaPelanggan"
                class="text-gray-500 mt-3">
                </p>

            </div>

            <div
            class="flex justify-center gap-3 mt-8">

                <button
                onclick="tutupModal()"
                class="px-5 py-3 bg-gray-400 hover:bg-gray-500 text-white rounded-xl">

                    Batal

                </button>

                <a
                id="btnHapus"
                href="#"
                class="px-5 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl">

                    Ya, Hapus

                </a>

            </div>

        </div>

    </div>

</div>
<body class="bg-gray-100">

    <div class="flex">

        <!-- SIDEBAR -->
      <aside
    id="sidebar"
    class="sidebar bg-gradient-to-b from-cyan-500/70 via-blue-600/70 to-blue-900/70 text-white w-64 min-h-screen">
        <div class="p-6">

            <h2 class="text-2xl font-bold text-blue-300">
                <i class="fas fa-droplet"></i> Meteran Air
            </h2>
           

        </div>

        <nav class="mt-6">

    <a href="dashboard.php" class="block px-6 py-3 hover:bg-blue-600">

                    Dashboard

                </a>

    <a href="pelanggan.php"
    class="block px-6 py-3 hover:bg-blue-600">

        Data Pelanggan

    </a>
    </a>
    <a href="pemakaian.php"
    class="block px-6 py-3 hover:bg-blue-600">

        Pemakaian Air

    </a>
    <a href="lapor.php"
    class="block px-6 py-3 hover:bg-blue-600">

        Laporan

    </a>

    <a href="logout.php"
    class="block text-red-600 px-6 py-3 hover:bg-red-600  hover:text-white font-semibold ">

        Logout

    </a>

</nav>
    </aside>

        <!-- CONTENT -->
        <main class="flex-1">

            <!-- TOPBAR -->
            <div class="bg-gradient-to-r from-blue-900/20 via-slate-900/10 to-transparent border-b border-white/10 backdrop-blur-sm shadow-md px-6 py-4 flex justify-between items-center text-white">


                <button onclick="toggleSidebar()" class="mobile-menu text-2xl">

                    ☰

                </button>

                <h1 class="text-xl md:text-2xl font-bold">
                    Data Pelanggan
                </h1>

                <div class=" text-gray-800 bg-green-300 input-focus border p-1 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">

                Halo,
                <b>
                    <?= $_SESSION['nama_petugas']; ?>
                </b>

                </div>

            </div>

            <!-- CONTENT -->
            <div class="p-6">

                <!-- HEADER -->
                <div class="bg-white rounded-2xl shadow p-6 mb-6">

                    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

                        <div>

                            <h2 class="text-2xl font-bold">

                                Daftar Pelanggan

                            </h2>

                            <p class="text-gray-500">

                                Kelola seluruh data pelanggan air

                            </p>

                        </div>

                        <a href="tambah_pelanggan.php"
                            class="bg-cyan-500  hover:bg-cyan-700  text-white px-5 py-3 rounded-xl">

                            + Tambah Pelanggan

                        </a>

                    </div>

                </div>

                <!-- SEARCH -->
                <div class="bg-white rounded-2xl shadow p-4 mb-6">

                    <form method="GET">

                        <div class="flex flex-col md:flex-row gap-3">

                            <input type="text" name="cari" value="<?= $cari ?>"
                                placeholder="Cari nama pelanggan, nomor pelanggan atau no hp..."
                                class="border rounded-xl p-3 flex-1">

                            <button type="submit" class="bg-cyan-500  text-white px-6 rounded-xl">

                                Cari

                            </button>

                        </div>

                    </form>

                </div>
                <?php
                if (isset($_SESSION['success'])):
                    ?>

                    <div id="notif-sukses"
                        class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl mb-4">

                        <?= $_SESSION['success']; ?>

                    </div>

                    <script>
                        setTimeout(function () {
                            document.getElementById('notif-sukses').style.display = 'none';
                        }, 3000);
                    </script>

                    <?php
                    unset($_SESSION['success']);
                endif;
                ?>
                <?php if (isset($_SESSION['success_delete'])): ?>

                    <div id="notif-delete" class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-xl mb-4">

                        <?= $_SESSION['success_delete']; ?>

                    </div>

                    <script>
                        setTimeout(() => {
                            document.getElementById('notif-delete').style.display = 'none';
                        }, 3000);
                    </script>

                    <?php
                    unset($_SESSION['success_delete']);
                endif;
                ?>
                <?php
                    if(isset($_SESSION['success_edit'])):
                    ?>

                    <div id="notif-edit"
                    class="bg-blue-100 border border-blue-300 text-blue-700 px-4 py-3 rounded-xl mb-4">

                        <?= $_SESSION['success_edit']; ?>

                    </div>

                    <script>
                    setTimeout(() => {
                        document.getElementById('notif-edit').style.display='none';
                    },3000);
                    </script>

                    <?php
                    unset($_SESSION['success_edit']);
                    endif;
                    ?>
                <!-- TABEL -->
                <div class="bg-white rounded-2xl shadow overflow-hidden">

                    <div class="table-container">

                        <table class="w-full">

                            <thead class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white">
                                <tr>

                                    <th class="p-4 text-left">
                                        No
                                    </th>

                                    <th class="p-4 text-left">
                                        No Pelanggan
                                    </th>

                                    <th class="p-4 text-left">
                                        Nama
                                    </th>

                                    <th class="p-4 text-left">
                                        No HP
                                    </th>

                                    <th class="p-4 text-left">
                                        Tarif/m³
                                    </th>

                                    <th class="p-4 text-left">
                                        Status
                                    </th>

                                    <th class="p-4 text-center">
                                        Kategori
                                    </th>

                                    <th class="p-4 text-center">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php

                                $no = 1;

                                while ($data = mysqli_fetch_assoc($query)):

                                    ?>

                                    <tr class="border-b hover:bg-gray-50">

                                        <td class="p-4">
                                            <?= $no++; ?>
                                        </td>

                                        <td class="p-4 font-semibold">
                                            <?= $data['nomor_pelanggan']; ?>
                                        </td>

                                        <td class="p-4">
                                            <?= $data['nama_pelanggan']; ?>
                                        </td>

                                        <td class="p-4">
                                            <?= $data['no_hp']; ?>
                                        </td>

                                        <td class="p-4">
                                            Rp <?= number_format($data['tarif_per_m3'], 0, ',', '.'); ?>
                                        </td>

                                        <td class="p-4">

                                            <?php if ($data['status'] == 'Aktif'): ?>

                                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">

                                                    Aktif

                                                </span>

                                            <?php else: ?>

                                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">

                                                    Nonaktif

                                                </span>

                                            <?php endif; ?>

                                        </td>

                                        <td class="p-4">
                                            <?= $data['kategori']; ?>
                                        </td>


                                        <td class="p-2">

                                            <div class="flex justify-center gap-2">

                                                <a href="edit_pelanggan.php?id=<?= $data['id_pelanggan']; ?>"
                                                    class="bg hover:bg text-green px-2 py-2 rounded-lg">

                                                   <i class="fas fa-pen"></i>

                                                </a>

                                               <button
                                                onclick="bukaModal(
                                                '<?= $data['id_pelanggan']; ?>',
                                                '<?= htmlspecialchars($data['nama_pelanggan']); ?>'
                                                )"
                                                class="bg hover:bg text-red px-2 py-2 rounded-lg">

                                                    <i class="fas fa-trash"></i>

                                                </button>

                                               

                                            </div>

                                        </td>

                                    </tr>

                                <?php endwhile; ?>

                                <?php
                                if (mysqli_num_rows($query) == 0) {
                                    ?>
                                    <tr>

                                        <td colspan="7" class="text-center p-8 text-gray-500">

                                            Belum ada data pelanggan

                                        </td>

                                    </tr>
                                    <?php
                                }
                                ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </main>

    </div>

    <script src="assets/js/script.js"></script>

</body>

</html>