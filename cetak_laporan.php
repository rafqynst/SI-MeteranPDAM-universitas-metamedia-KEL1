<?php
include 'config/koneksi.php';

$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

$where = "";

if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {

    $where = "
        WHERE DATE(pembayaran.tanggal_bayar)
        BETWEEN '$tanggal_awal'
        AND '$tanggal_akhir'
    ";
}

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

    $where

    ORDER BY pembayaran.id_pembayaran DESC
");

header("Content-Type: application/vnd-ms-excel");
header(
    "Content-Disposition: attachment; filename=laporan_pembayaran.xls"
);

$bulan = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember'
];

$total = 0;
?>

<table border="1">

    <tr>
        <th colspan="7"
            style="font-size:18px;height:40px;">
            LAPORAN PEMBAYARAN PDAM
        </th>
    </tr>

    <tr>
        <td colspan="7">

            Periode :

            <?= !empty($tanggal_awal)
                ? date(
                    'd-m-Y',
                    strtotime($tanggal_awal)
                )
                : '-'; ?>

            s/d

            <?= !empty($tanggal_akhir)
                ? date(
                    'd-m-Y',
                    strtotime($tanggal_akhir)
                )
                : '-'; ?>

        </td>
    </tr>

    <tr></tr>

    <tr style="font-weight:bold; background:#ddd;">
        <th>No</th>
        <th>Tanggal</th>
        <th>No Pelanggan</th>
        <th>Nama Pelanggan</th>
        <th>Periode</th>
        <th>Metode</th>
        <th>Total</th>
    </tr>

    <?php
    $no = 1;

    while ($row = mysqli_fetch_assoc($query)):

        $total += $row['total_bayar'];
    ?>

        <tr>

            <td>
                <?= $no++; ?>
            </td>

            <td>
                <?= date(
                    'd-m-Y',
                    strtotime(
                        $row['tanggal_bayar']
                    )
                ); ?>
            </td>

            <td>
                <?= $row['nomor_pelanggan']; ?>
            </td>

            <td>
                <?= $row['nama_pelanggan']; ?>
            </td>

            <td>
                <?= $bulan[$row['bulan']] ?? '-'; ?>
                <?= $row['tahun']; ?>
            </td>

            <td>
                <?= $row['metode_pembayaran']; ?>
            </td>

            <td>
                Rp
                <?= number_format(
                    $row['total_bayar']
                ); ?>
            </td>

        </tr>

    <?php endwhile; ?>

    <tr style="font-weight:bold;">

        <td colspan="6"
            align="right">

            Total Pemasukan

        </td>

        <td>

            Rp
            <?= number_format($total); ?>

        </td>

    </tr>

</table>