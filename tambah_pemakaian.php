
<?php
include 'config/koneksi.php';

// Ambil pelanggan aktif
$pelanggan = mysqli_query($conn, "
    SELECT *
    FROM pelanggan
    WHERE status='Aktif'
    ORDER BY nama_pelanggan ASC
");

// Simpan data
if (isset($_POST['simpan'])) {

    $id_pelanggan = $_POST['id_pelanggan'];
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];

    $meter_lalu = $_POST['meter_lalu'];
    $meter_ini = $_POST['meter_ini'];

    $pemakaian = $_POST['pemakaian'];
    $tarif = $_POST['tarif'];
    $admin = $_POST['admin'];

    $total = $_POST['total_tagihan'];

    // Validasi meter
    if ($meter_ini < $meter_lalu) {
        echo "
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Meter bulan ini tidak boleh lebih kecil!'
        });
        </script>";
        exit;
    }

    // cek tagihan ganda
    $cek = mysqli_query($conn, "
        SELECT *
        FROM tagihan
        WHERE id_pelanggan='$id_pelanggan'
        AND bulan='$bulan'
        AND tahun='$tahun'
    ");

    if (mysqli_num_rows($cek) > 0) {
        echo "
        <script>
        Swal.fire({
            icon: 'warning',
            title: 'Tagihan Sudah Ada',
            text: 'Tagihan pelanggan bulan ini sudah dibuat'
        });
        </script>";
        exit;
    }

    // simpan data
    $insert = mysqli_query($conn, "
        INSERT INTO tagihan (
            id_pelanggan,
            tgl_tagih,
            bulan,
            tahun,
            meter_bulan_lalu,
            meter_bulan_ini,
            pemakaian,
            hpka,
            biaya_admin,
            total_tagihan,
            status
        )
        VALUES (
            '$id_pelanggan',
            CURDATE(),
            '$bulan',
            '$tahun',
            '$meter_lalu',
            '$meter_ini',
            '$pemakaian',
            '$tarif',
            '$admin',
            '$total',
            'Belum Bayar'
        )
    ");

    if ($insert) {

        echo "
        <script>
        document.addEventListener('DOMContentLoaded', function(){

            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Pemakaian berhasil ditambahkan',
                confirmButtonText: 'OK'
            }).then((result) => {

                if(result.isConfirmed){
                    window.location='pemakaian.php';
                }

            });

        });
        </script>";

    } else {

        echo "
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Data gagal disimpan'
        });
        </script>";
    }
}
?>



<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Pemakaian</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="bg-slate-100">

    <div class="max-w-4xl mx-auto p-6">

        <div class="bg-white rounded-2xl shadow-lg p-8">

            <h1 class="text-3xl font-bold mb-6">
                Tambah Pemakaian Air
            </h1>

            <form method="POST">

                <!-- pelanggan -->
                <div class="mb-4">
                    <label class="font-medium block mb-2">
                        Pelanggan
                    </label>

                    <select
                        name="id_pelanggan"
                        id="id_pelanggan"
                        required
                        class="w-full border rounded-lg p-3">
                        <option value="">
                            -- Pilih Pelanggan --
                        </option>

                        <?php while ($row = mysqli_fetch_assoc($pelanggan)): ?>
                            <option value="<?= $row['id_pelanggan']; ?>">
                                <?= $row['nomor_pelanggan']; ?> -
                                <?= $row['nama_pelanggan']; ?>
                            </option>
                        <?php endwhile; ?>

                    </select>
                </div>

                <!-- bulan -->
                <div class="grid md:grid-cols-2 gap-4 mb-4">

                    <div>
                        <label class="font-medium block mb-2">
                            Bulan
                        </label>

                        <select
                            name="bulan"
                            required
                            class="w-full border rounded-lg p-3">
                            <option value="">
                                Pilih Bulan
                            </option>

                            <?php
                            $bulan = [
                                'Januari',
                                'Februari',
                                'Maret',
                                'April',
                                'Mei',
                                'Juni',
                                'Juli',
                                'Agustus',
                                'September',
                                'Oktober',
                                'November',
                                'Desember'
                            ];

                            foreach ($bulan as $key => $nama):
                            ?>

                                <option value="<?= $key + 1 ?>">
                                    <?= $nama ?>
                                </option>

                            <?php endforeach; ?>

                        </select>
                    </div>

                    <div>
                        <label class="font-medium block mb-2">
                            Tahun
                        </label>

                        <input
                            type="number"
                            name="tahun"
                            value="<?= date('Y'); ?>"
                            class="w-full border rounded-lg p-3"
                            required>
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
                            id="kategori"
                            readonly
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
                            name="pemakaian"
                            id="pemakaian"
                            readonly
                            class="w-full bg-slate-100 border rounded-lg p-3">
                    </div>

                    <div>
                        <label class="block mb-2 font-medium">
                            Total Tagihan
                        </label>

                        <input
                            type="number"
                            name="total_tagihan"
                            id="total_tagihan"
                            readonly
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
                        name="simpan"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg">
                        Simpan
                    </button>

                </div>

            </form>
        </div>
    </div>

    <script>
        const pelanggan = document.getElementById('id_pelanggan');
        const meterLalu = document.getElementById('meter_lalu');
        const meterIni = document.getElementById('meter_ini');

        const tarif = document.getElementById('tarif');
        const admin = document.getElementById('admin');
        const kategori = document.getElementById('kategori');

        const pemakaian = document.getElementById('pemakaian');
        const total = document.getElementById('total_tagihan');

        // ambil pelanggan
        pelanggan.addEventListener('change', function() {

            fetch('get_pelanggan.php?id=' + this.value)
                .then(res => res.json())
                .then(data => {

                    meterLalu.value = data.meter_lalu;
                    tarif.value = data.tarif;
                    admin.value = data.admin;
                    kategori.value = data.kategori;

                    hitung();

                });

        });

        // hitung otomatis
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