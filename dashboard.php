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

</head>

<body class="bg-gray-100">

<div class="flex">

    <!-- SIDEBAR -->

    <aside
    id="sidebar"
    class="sidebar bg-blue-700 text-white w-64 min-h-screen">

        <div class="p-6">

            <h2 class="text-2xl font-bold">
                💧 Meteran Air
            </h2>

        </div>

        <nav class="mt-6">

    <a href="dashboard.php"
    class="block px-6 py-3 bg-blue-800">

        Dashboard

    </a>

    <a href="pelanggan.php"
    class="block px-6 py-3 hover:bg-blue-800">

        Data Pelanggan

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
        class="bg-white shadow px-6 py-4 flex justify-between items-center">

            <button
            onclick="toggleSidebar()"
            class="mobile-menu text-2xl">

                ☰

            </button>

            <h1
            class="text-2xl font-bold">

                Dashboard

            </h1>

            <div>

                Halo,
                <b>
                    <?= $_SESSION['nama_petugas']; ?>
                </b>

            </div>

        </div>

        <!-- KONTEN -->

        <div class="p-6">

            <div
            class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- CARD PELANGGAN -->

                <div
                class="card-hover bg-white rounded-2xl shadow p-6">

                    <div class="text-4xl mb-3">
                        👥
                    </div>

                    <h3
                    class="text-gray-500">

                        Total Pelanggan

                    </h3>

                    <p
                    class="text-3xl font-bold text-blue-600">

                        <?= $totalPelanggan ?>

                    </p>

                </div>

                <!-- CARD PETUGAS -->

                <div
                class="card-hover bg-white rounded-2xl shadow p-6">

                    <div class="text-4xl mb-3">
                        👨‍💼
                    </div>

                    <h3
                    class="text-gray-500">

                        Total Petugas

                    </h3>

                    <p
                    class="text-3xl font-bold text-green-600">

                        <?= $totalPetugas ?>

                    </p>

                </div>

                <!-- CARD PEMAKAIAN -->

                <div
                class="card-hover bg-white rounded-2xl shadow p-6">

                    <div class="text-4xl mb-3">
                        💧
                    </div>

                    <h3
                    class="text-gray-500">

                        Pemakaian Air

                    </h3>

                    <p
                    class="text-3xl font-bold text-cyan-600">

                        0 m³

                    </p>

                </div>

                <!-- CARD TAGIHAN -->

                <div
                class="card-hover bg-white rounded-2xl shadow p-6">

                    <div class="text-4xl mb-3">
                        💰
                    </div>

                    <h3
                    class="text-gray-500">

                        Total Penghasilan

                    </h3>

                    <p
                    class="text-3xl font-bold text-yellow-500">

                        Rp0

                    </p>

                </div>

            </div>

            <!-- WELCOME -->

            <div
            class="bg-white rounded-2xl shadow p-6 mt-8">

                <h2
                class="text-2xl font-bold mb-3">

                    Selamat Datang

                </h2>

                <p class="text-gray-600">

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