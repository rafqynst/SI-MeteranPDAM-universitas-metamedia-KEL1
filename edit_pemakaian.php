
<?php
include 'config/koneksi.php';

if (!isset($_GET['id'])) {
    header('Location: pemakaian.php');
    exit;
}

$id = $_GET['id'];

// ambil data tagihan + pelanggan
$query = mysqli_query($conn, "
    SELECT
        tagihan.*,
        pelanggan.nomor_pelanggan,
        pelanggan.nama_pelanggan,
        pelanggan.kategori
    FROM tagihan
    JOIN pelanggan
    ON tagihan.id_pelanggan = pelanggan.id_pelanggan
    WHERE id_tagihan = '$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    header('Location: pemakaian.php');
    exit;
}

// jika lunas tidak bisa edit
if ($data['status'] == 'Lunas') {

    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>

    <script>
    Swal.fire({
        icon: 'warning',
        title: 'Tidak Bisa Edit',
        text: 'Tagihan yang sudah lunas tidak dapat diedit'
    }).then(() => {
        window.location='pemakaian.php';
    });
    </script>
    ";

    exit;
}


// update data
if (isset($_POST['update'])) {

    $meter_lalu = $_POST['meter_lalu'];
    $meter_ini = $_POST['meter_ini'];

    $tarif = $_POST['tarif'];
    $admin = $_POST['admin'];

    // validasi
    if ($meter_ini < $meter_lalu) {

        echo "
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Meter bulan ini tidak boleh lebih kecil!'
        });
        </script>";
    } else {

        $pemakaian = $meter_ini - $meter_lalu;
        $total = ($pemakaian * $tarif) + $admin;

        $update = mysqli_query($conn, "
            UPDATE tagihan
            SET
                meter_bulan_ini = '$meter_ini',
                pemakaian = '$pemakaian',
                total_tagihan = '$total'
            WHERE id_tagihan = '$id'
        ");

        if ($update) {

            echo "
            <script>
            document.addEventListener('DOMContentLoaded', function(){

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data pemakaian berhasil diperbarui',
                    confirmButtonText: 'OK'
                }).then(() => {

                    window.location='pemakaian.php';

                });

            });
            </script>";
        } else {

            echo "
            <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Data gagal diperbarui'
            });
            </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Pemakaian</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="bg-slate-100">

    <div class="max-w-4xl mx-auto p-6">

        <div class="bg-white rounded-2xl shadow-lg p-8">

            <h1 class="text-3xl font-bold mb-6">
                Edit Pemakaian Air
            </h1>

            <form method="POST">

                <!-- pelanggan -->
                <div class="mb-4">

                    <label class="font-medium block mb-2">
                        Pelanggan
                    </label>

                    <input
                        type="text"
                        readonly
                        value="<?= $data['nomor_pelanggan']; ?> - <?= $data['nama_pelanggan']; ?>"
                        class="w-full bg-slate-100 border rounded-lg p-3">

                </div>

                <!-- bulan tahun -->
                <div class="grid md:grid-cols-2 gap-4 mb-4">

                    <div>
                        <label class="font-medium block mb-2">
                            Bulan
                        </label>

                        <input
                            type="text"
                            readonly
                            value="<?= $data['bulan']; ?>"
                            class="w-full bg-slate-100 border rounded-lg p-3">

                    </div>

                    <div>
                        <label class="font-medium block mb-2">
                            Tahun
                        </label>

                        <input
                            type="text"
                            readonly
                            value="<?= $data['tahun']; ?>"
                            class="w-full bg-slate-100 border rounded-lg p-3">

                    </div>

                </div>

                <!-- info pelanggan -->
                <div class="grid md:grid-cols-3 gap-4 mb-4">

                    <div>
                        <label class="block mb-2 font-medium">
                            Kategori
                        </label>

                        <input
                            type="text"
                            readonly
                            value="<?= $data['kategori']; ?>"
                            class="w-full bg-slate-100 border rounded-lg p-3">

                    </div>

                    <div>
                        <label class="block mb-2 font-medium">
                            Tarif / m³
                        </label>

                        <input
                            type="number"
                            name="tarif"
                            id="tarif"
                            readonly
                            value="<?= $data['hpka']; ?>"
                            class="w-full bg-slate-100 border rounded-lg p-3">

                    </div>

                    <div>
                        <label class="block mb-2 font-medium">
                            Biaya Admin
                        </label>

                        <input
                            type="number"
                            name="admin"
                            id="admin"
                            readonly
                            value="<?= $data['biaya_admin']; ?>"
                            class="w-full bg-slate-100 border rounded-lg p-3">

                    </div>

                </div>

                <!-- meter -->
                <div class="grid md:grid-cols-2 gap-4 mb-4">

                    <div>
                        <label class="block mb-2 font-medium">
                            Meter Bulan Lalu
                        </label>

                        <input
                            type="number"
                            name="meter_lalu"
                            id="meter_lalu"
                            readonly
                            value="<?= $data['meter_bulan_lalu']; ?>"
                            class="w-full bg-slate-100 border rounded-lg p-3">

                    </div>

                    <div>
                        <label class="block mb-2 font-medium">
                            Meter Bulan Ini
                        </label>

                        <input
                            type="number"
                            name="meter_ini"
                            id="meter_ini"
                            required
                            value="<?= $data['meter_bulan_ini']; ?>"
                            class="w-full border rounded-lg p-3">

                    </div>

                </div>

                <!-- hasil -->
                <div class="grid md:grid-cols-2 gap-4">

                    <div>
                        <label class="block mb-2 font-medium">
                            Pemakaian Air
                        </label>

                        <input
                            type="number"
                            id="pemakaian"
                            readonly
                            value="<?= $data['pemakaian']; ?>"
                            class="w-full bg-slate-100 border rounded-lg p-3">

                    </div>

                    <div>
                        <label class="block mb-2 font-medium">
                            Total Tagihan
                        </label>

                        <input
                            type="number"
                            id="total"
                            readonly
                            value="<?= $data['total_tagihan']; ?>"
                            class="w-full bg-slate-100 border rounded-lg p-3">

                    </div>

                </div>

                <div class="flex gap-3 mt-8">

                    <a href="pemakaian.php"
                        class="bg-slate-500 text-white px-5 py-3 rounded-lg">
                        Kembali
                    </a>

                    <button
                        type="submit"
                        name="update"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg">
                        Update
                    </button>

                </div>

            </form>

        </div>
    </div>

    <script>
        const meterLalu = document.getElementById('meter_lalu');
        const meterIni = document.getElementById('meter_ini');

        const tarif = document.getElementById('tarif');
        const admin = document.getElementById('admin');

        const pemakaian = document.getElementById('pemakaian');
        const total = document.getElementById('total');

        meterIni.addEventListener('keyup', hitung);

        function hitung() {

            let lalu = parseInt(meterLalu.value) || 0;
            let sekarang = parseInt(meterIni.value) || 0;

            let harga = parseFloat(tarif.value) || 0;
            let adm = parseFloat(admin.value) || 0;

            if (sekarang < lalu) {

                pemakaian.value = 0;
                total.value = 0;
                return;

            }

            let pakai = sekarang - lalu;
            let tagihan = (pakai * harga) + adm;

            pemakaian.value = pakai;
            total.value = tagihan;

        }
    </script>

</body>

</html>
```