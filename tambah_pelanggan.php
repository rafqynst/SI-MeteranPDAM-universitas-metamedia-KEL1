<?php
session_start();
include 'config/koneksi.php';

if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| MEMBUAT NOMOR PELANGGAN OTOMATIS
|--------------------------------------------------------------------------
*/

$queryKode = mysqli_query(
    $conn,
    "SELECT nomor_pelanggan
     FROM pelanggan
     ORDER BY id_pelanggan DESC
     LIMIT 1"
);

$dataKode = mysqli_fetch_assoc($queryKode);

if($dataKode){

    $angka = substr(
        $dataKode['nomor_pelanggan'],
        3
    );

    $angka++;

    $nomor_pelanggan =
    "PLG" . str_pad(
        $angka,
        4,
        "0",
        STR_PAD_LEFT
    );

}else{

    $nomor_pelanggan = "PLG0001";
}

/*
|--------------------------------------------------------------------------
| SIMPAN DATA
|--------------------------------------------------------------------------
*/

if(isset($_POST['simpan'])){

    $nomor = mysqli_real_escape_string(
        $conn,
        $_POST['nomor_pelanggan']
    );

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

    $simpan = mysqli_query(
        $conn,
        "INSERT INTO pelanggan
        (
            nomor_pelanggan,
            nama_pelanggan,
            nik,
            alamat,
            no_hp,
            tarif_per_m3,
            status,
            kategori
        )
        VALUES
        (
            '$nomor',
            '$nama',
            '$nik',
            '$alamat',
            '$hp',
            '$tarif',
            '$status',
            '$kategori'
        )"
    );

if($simpan){

    $_SESSION['success'] =
    "Data pelanggan berhasil ditambahkan";

    header("Location: pelanggan.php");
    exit;
}else{

    echo mysqli_error($conn);
}
}
?>
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Tambah Pelanggan</title>

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

        <!-- TOPBAR -->
        <div
        class="bg-white shadow px-6 py-4 flex justify-between items-center">

            <button
            onclick="toggleSidebar()"
            class="mobile-menu text-2xl">
                ☰
            </button>

            <h1 class="text-xl md:text-2xl font-bold">
                Tambah Pelanggan
            </h1>

            <div>
                <?= $_SESSION['nama_petugas']; ?>
            </div>

        </div>

        <!-- FORM -->
        <div class="p-6">

            <div
            class="form-card bg-white rounded-2xl shadow p-8 max-w-4xl">

                <h2 class="text-2xl font-bold mb-6">
                    Form Tambah Pelanggan
                </h2>

                <form method="POST">

                    <div class="grid md:grid-cols-2 gap-5">

                        <div>

                            <label class="font-semibold">
                                Nomor Pelanggan
                            </label>

                            <input
                            type="text"
                            name="nomor_pelanggan"
                            value="<?= $nomor_pelanggan ?>"
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
                            class="form-input">

                        </div>

                        <div>

                            <label class="font-semibold">
                                NIK
                            </label>

                            <input
                            type="text"
                            name="nik"
                            maxlength="16"
                            class="form-input">

                        </div>

                        <div>

                            <label class="font-semibold">
                                Nomor HP
                            </label>

                            <input
                            type="text"
                            name="no_hp"
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
                        class="form-input"></textarea>

                    </div>

                    <div
                    class="grid md:grid-cols-3 gap-4 mt-4">

                        <div>

                            <label class="font-semibold">
                                Tarif per m³
                            </label>

                            <input
                            type="number"
                            name="tarif_per_m3"
                            value="5000"
                            required
                            class="form-input">

                        </div>

                        <div>

                            <label class="font-semibold">
                                Status
                            </label>

                            <select
                            name="status"
                            class="form-input">

                                <option value="Aktif">
                                    Aktif
                                </option>

                                <option value="Nonaktif">
                                    Nonaktif
                                </option>

                            </select>

                        </div>
    <div>
    <label class="font-semibold">
        Kategori
    </label>

    <select name="kategori" required
        class=form-input>
        
        <option value="">-- Pilih Kategori --</option>
        <option value="RT">Rumah Tangga</option>
        <option value="ID">Industri</option>
        <option value="IP">Instansi Pemerintah</option>

    </select>
</div>

                    </div>

                    <div
                    class="flex gap-3 mt-8">

                        <button
                        type="submit"
                        name="simpan"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl">

                            Simpan Data

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