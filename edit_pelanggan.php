<?php
session_start();
include 'config/koneksi.php';

if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id'])){
    header("Location: pelanggan.php");
    exit;
}

$id = (int)$_GET['id'];

$query = mysqli_query(
    $conn,
    "SELECT *
     FROM pelanggan
     WHERE id_pelanggan='$id'"
);

$data = mysqli_fetch_assoc($query);

if(!$data){
    header("Location: pelanggan.php");
    exit;
}

/* UPDATE DATA */

if(isset($_POST['update'])){

    $nama = mysqli_real_escape_string(
        $conn,
        $_POST['nama_pelanggan']
    );

    $nik = mysqli_real_escape_string(
        $conn,
        $_POST['nik']
    );

    $alamat = mysqli_real_escape_string(
        $conn,
        $_POST['alamat']
    );

    $hp = mysqli_real_escape_string(
        $conn,
        $_POST['no_hp']
    );

    $tarif = mysqli_real_escape_string(
        $conn,
        $_POST['tarif_per_m3']
    );

    $status = mysqli_real_escape_string(
        $conn,
        $_POST['status']
    );
     $kategori = mysqli_real_escape_string(
        $conn,
        $_POST['kategori']
    );

    $update = mysqli_query(
        $conn,
        "UPDATE pelanggan SET

        nama_pelanggan='$nama',
        nik='$nik',
        alamat='$alamat',
        no_hp='$hp',
        tarif_per_m3='$tarif',
        status='$status',
        kategori='$kategori'
        WHERE id_pelanggan='$id'"
    );

    if($update){

        $_SESSION['success_edit'] =
        "Data pelanggan berhasil diperbarui";

        header("Location: pelanggan.php");
        exit;

    }else{

        die(mysqli_error($conn));

    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Edit Pelanggan</title>

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

        <div class="p-6 border-b border-blue-600">

            <h2 class="text-2xl font-bold">
                💧 Meteran Air
            </h2>

        </div>

        <nav class="mt-4">

            <a href="dashboard.php"
            class="block px-6 py-3 hover:bg-blue-800">

                Dashboard

            </a>

            <a href="pelanggan.php"
            class="block px-6 py-3 bg-blue-800">

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

        <div
        class="bg-white shadow px-6 py-4 flex justify-between items-center">

            <button
            onclick="toggleSidebar()"
            class="mobile-menu text-2xl">

                ☰

            </button>

            <h1 class="text-xl md:text-2xl font-bold">

                Edit Pelanggan

            </h1>

            <div>
                <?= $_SESSION['nama_petugas']; ?>
            </div>

        </div>

        <div class="p-6">

            <div
            class="form-card bg-white rounded-2xl shadow p-8 max-w-4xl">

                <h2 class="text-2xl font-bold mb-6">

                    Form Edit Pelanggan

                </h2>

                <form method="POST">

                    <div class="grid md:grid-cols-2 gap-5">

                        <div>

                            <label class="font-semibold">
                                Nomor Pelanggan
                            </label>

                            <input
                            type="text"
                            value="<?= $data['nomor_pelanggan']; ?>"
                            readonly
                            class="form-input bg-gray-100">

                        </div>

                        <div>

                            <label class="font-semibold">
                                Nama Pelanggan
                            </label>

                            <input
                            type="text"
                            name="nama_pelanggan"
                            required
                            value="<?= $data['nama_pelanggan']; ?>"
                            class="form-input">

                        </div>

                        <div>

                            <label class="font-semibold">
                                NIK
                            </label>

                            <input
                            type="text"
                            name="nik"
                            value="<?= $data['nik']; ?>"
                            class="form-input">

                        </div>

                        <div>

                            <label class="font-semibold">
                                Nomor HP
                            </label>

                            <input
                            type="text"
                            name="no_hp"
                            value="<?= $data['no_hp']; ?>"
                            class="form-input">

                        </div>

                    </div>

                    <div class="mt-5">

                        <label class="font-semibold">
                            Alamat
                        </label>

                        <textarea
                        name="alamat"
                        rows="4"
                        class="form-input"><?= $data['alamat']; ?></textarea>

                    </div>

                    <div
                    class="grid md:grid-cols-3 gap-5 mt-5">

                        <div>

                            <label class="font-semibold">
                                Tarif per m³
                            </label>

                            <input
                            type="number"
                            name="tarif_per_m3"
                            value="<?= $data['tarif_per_m3']; ?>"
                            class="form-input">

                        </div>

                        <div>

                            <label class="font-semibold">
                                Status
                            </label>

                            <select
                            name="status"
                            class="form-input">

                                <option value="Aktif"
                                <?= $data['status']=='Aktif' ? 'selected' : ''; ?>>

                                    Aktif

                                </option>

                                <option value="Nonaktif"
                                <?= $data['status']=='Nonaktif' ? 'selected' : ''; ?>>

                                    Nonaktif

                                </option>

                            </select>

                        </div>
                        <div class="mb-4">
    <label class="font-semibold">
        Kategori
    </label>

    <select name="kategori" class="form-input">
    <option value="RT"
        <?= ($data['kategori'] == 'RT') ? 'selected' : ''; ?>>
        Rumah Tangga
    </option>

    <option value="ID"
        <?= ($data['kategori'] == 'ID') ? 'selected' : ''; ?>>
        Industri
    </option>

    <option value="IP"
        <?= ($data['kategori'] == 'IP') ? 'selected' : ''; ?>>
        Instansi Pemerintah
    </option>
</select>
</div>
                    </div>

                    <div
                    class="flex gap-3 mt-8">

                        <button
                        type="submit"
                        name="update"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl">

                            Update Data

                        </button>

                        <a
                        href="pelanggan.php"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl">

                            Batal

                        </a>

                    </div>

                </form>

            </div>

        </div>

    </main>

</div>

<script src="assets/js/script.js"></script>

</body>
</html>