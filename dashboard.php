<?php
session_start();

include 'config/koneksi.php';

if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}

$totalPelanggan =
mysqli_num_rows(
    mysqli_query($conn,"SELECT * FROM pelanggan")
);

$totalPetugas =
mysqli_num_rows(
    mysqli_query($conn,"SELECT * FROM petugas")
);
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
<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Dashboard</title>

<script src="https://cdn.tailwindcss.com"></script>

<link rel="stylesheet"
href="assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body >

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
    <a href="pemakaian.php"
    class="block px-6 py-3 hover:bg-blue-600">

        Pemakaian Air

    </a>

    <a href="logout.php"
    class="block px-6 py-3 hover:bg-red-600">

        Logout

    </a>

</nav>
    </aside>

    <!-- CONTENT -->

    <main class="flex-1">

        <!-- TOPBAR -->

        <div
        class="bg-gradient-to-r from-blue-900/20 via-slate-900/10 to-transparent border-b border-white/10 backdrop-blur-sm shadow-md px-6 py-4 flex justify-between items-center text-white">

            <button
            onclick="toggleSidebar()"
            class="mobile-menu text-2xl">

                ☰

            </button>

            <h1
            class="text-2xl font-bold">

                DASHBOARD

            </h1>

            <div class=" text-gray-800 bg-green-300 input-focus border p-1 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">

                Halo,
                <b>
                    <?= $_SESSION['nama_petugas']; ?>
                </b>

            </div>

        </div>

        <!-- KONTEN -->

        <div class="p-6">

            <div
            class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 p-6 ">

                <!-- CARD PELANGGAN -->

                <div
                class="card-hover bg-white rounded-2xl shadow p-6">
                <div class="text-3xl mb-3 text-blue-400">
                <i class="fas fa-users"></i>
                </div>
                    <h3
                    class="text-gray-500">

                        Total Pelanggan

                    </h3>

                    <p
                    class="text-3xl font-bold text-blue-400">

                        <?= $totalPelanggan ?>

                    </p>

                </div>

                <!-- CARD PETUGAS -->

                <div
                class="card-hover bg-white rounded-2xl shadow p-6">

                    <div class="text-3xl mb-3 text-slate-500">
                <i class="fas fa-user-tie"></i> 
                </div>

                    <h3
                    class="text-gray-500">

                        Total Petugas

                    </h3>

                    <p
                    class="text-3xl font-bold text-slate-500">

                        <?= $totalPetugas ?>

                    </p>

                </div>

                <!-- CARD PEMAKAIAN -->

                <div
                class="card-hover bg-white rounded-2xl shadow p-6">

                    <div class="text-3xl mb-3 text-cyan-400">
                    <i class="fas fa-faucet"></i>
                    </div>
                    <h3
                    class="text-gray-500">

                        Pemakaian Air

                    </h3>

                    <p class="text-3xl font-bold text-cyan-400">
                    <?= number_format($statistik['total_pemakaian'] ?? 0,0,',','.'); ?> m³
                </p>

                </div>

                <!-- CARD TAGIHAN -->

                <div
                class="card-hover bg-white rounded-2xl shadow p-6">

                    <div class="text-3xl mb-3 text-emerald-400">
                    <i class="fas fa-wallet"></i>
                    </div>

                    <h3
                    class="text-gray-500">

                        Total Penghasilan

                    </h3>

                    <p
                    class="text-3xl mb-3 font-bold text-emerald-400">
                    Rp <?= number_format($statistik['total_pemasukan'] ?? 0,0,',','.'); ?>
                </p>

                </div>

            </div>

            <!-- WELCOME -->

            <div class=" p-6 card-hover bg-gradient-to-r from-blue-600  to-cyan-500 rounded-2xl shadow-lg  text-white">

                <h2
                class="text-2xl font-bold mb-3">

                    Selamat Datang

                </h2>

                <p class="text-gray-900">

                    Sistem Meteran Air digunakan untuk
                    mengelola data pelanggan,
                    pencatatan meteran,
                    dan tagihan air.

                </p>

            </div>

        </div>

    </main>

</div>

<script src="assets/js/script.js"></script>

</body>
</html>