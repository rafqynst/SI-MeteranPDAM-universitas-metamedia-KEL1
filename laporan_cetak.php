<?php
include 'config/koneksi.php';

$bulan = $_GET['bulan'] ?? '';
$tahun = $_GET['tahun'] ?? '';

$where = "";

if (!empty($bulan) && !empty($tahun)) {
    $where = "WHERE t.bulan='$bulan' AND t.tahun='$tahun'";
} elseif (!empty($bulan)) {
    $where = "WHERE t.bulan='$bulan'";
} elseif (!empty($tahun)) {
    $where = "WHERE t.tahun='$tahun'";
}

$query = mysqli_query($conn, "
    SELECT
        p.id_pembayaran,
        p.metode_pembayaran,
        p.total_bayar,
        p.tanggal_bayar,
        p.status AS status_bayar,

        pl.nomor_pelanggan,
        pl.nama_pelanggan,
        pl.alamat,
        pl.kategori,
        pl.tarif_per_m3,

        t.bulan,
        t.tahun,
        t.meter_bulan_lalu,
        t.meter_bulan_ini,
        t.pemakaian,
        t.hpka,
        t.biaya_admin,
        t.total_tagihan,

        pt.nama_petugas

    FROM pembayaran p

    JOIN pelanggan pl
        ON p.id_pelanggan = pl.id_pelanggan

    JOIN tagihan t
        ON p.id_tagihan = t.id_tagihan

    LEFT JOIN petugas pt
        ON p.id_petugas = pt.id_petugas

    $where

    ORDER BY p.tanggal_bayar DESC
");
?>

<!DOCTYPE html>

<html>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta charset="utf-8">
    <title>Cetak Laporan Pembayaran PDAM</title>

    ```
    <style>
        @page {
            size: landscape;
            margin: 10mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        .judul {
            text-align: center;
            margin-bottom: 20px;
        }

        .judul h2 {
            margin: 0;
        }

        .judul p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        th {
            background: #e5e7eb;
        }

        .text-left {
            text-align: left;
        }

        .footer {
            margin-top: 30px;
            width: 100%;
        }

        .ttd {
            float: right;
            text-align: center;
            width: 250px;
        }
    </style>
    ```

</head>

<body>

    <div class="judul">
        <h2>LAPORAN PEMBAYARAN PDAM</h2>
        <p>Sistem Informasi Pembayaran Air PDAM</p>

        ```
        <?php if ($bulan || $tahun): ?>
            <p>
                Periode :
                <?= $bulan ? $bulan : 'Semua Bulan'; ?>
                <?= $tahun ? $tahun : ''; ?>
            </p>
        <?php endif; ?>
        ```

    </div>

    <table>

        ```
        <thead>
            <tr>
                <th>No</th>
                <th>Pelanggan</th>
                <th>Kategori</th>
                <th>Periode</th>
                <th>Awal</th>
                <th>Akhir</th>
                <th>Pakai</th>
                <th>Tarif</th>
                <th>HPKA</th>
                <th>Admin</th>
                <th>Tagihan</th>
                <th>Metode</th>
                <th>Bayar</th>
                <th>Tanggal</th>
                <th>Petugas</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>

            <?php
            $no = 1;

            while ($data = mysqli_fetch_assoc($query)) {
                ?>

                <tr>

                    <td>
                        <?= $no++; ?>
                    </td>

                    <td class="text-left">
                        <strong>
                            <?= $data['nama_pelanggan']; ?>
                        </strong><br>
                        <?= $data['nomor_pelanggan']; ?>
                    </td>

                    <td>
                        <?= $data['kategori']; ?>
                    </td>

                    <td>
                        <?= $data['bulan']; ?>
                        <?= $data['tahun']; ?>
                    </td>

                    <td>
                        <?= $data['meter_bulan_lalu']; ?>
                    </td>

                    <td>
                        <?= $data['meter_bulan_ini']; ?>
                    </td>

                    <td>
                        <?= $data['pemakaian']; ?> m³
                    </td>

                    <td>
                        Rp
                        <?= number_format($data['tarif_per_m3'], 0, ',', '.'); ?>
                    </td>

                    <td>
                        Rp
                        <?= number_format($data['hpka'], 0, ',', '.'); ?>
                    </td>

                    <td>
                        Rp
                        <?= number_format($data['biaya_admin'], 0, ',', '.'); ?>
                    </td>

                    <td>
                        Rp
                        <?= number_format($data['total_tagihan'], 0, ',', '.'); ?>
                    </td>

                    <td>
                        <?= $data['metode_pembayaran']; ?>
                    </td>

                    <td>
                        Rp
                        <?= number_format($data['total_bayar'], 0, ',', '.'); ?>
                    </td>

                    <td>
                        <?= date('d-m-Y', strtotime($data['tanggal_bayar'])); ?>
                    </td>

                    <td>
                        <?= $data['nama_petugas']; ?>
                    </td>

                    <td>
                        <?= $data['status_bayar']; ?>
                    </td>

                </tr>

            <?php } ?>

        </tbody>
        ```

    </table>

    <div class="footer">

        ```
        <div class="ttd">
            <p>
                <?= date('d F Y'); ?>
            </p>
            <br><br><br><br>
            <p><b>Administrator</b></p>
        </div>
        ```

    </div>
   
    <script>
        window.onload = function () {
            window.print();
        }
    </script>

</body>

</html>