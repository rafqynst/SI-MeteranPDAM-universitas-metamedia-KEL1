<?php
include 'config/koneksi.php';

if (isset($_GET['id'])) {

    $id = intval($_GET['id']);

    // data pelanggan
    $queryPelanggan = mysqli_query($conn, "
        SELECT *
        FROM pelanggan
        WHERE id_pelanggan='$id'
    ");

    $pelanggan = mysqli_fetch_assoc($queryPelanggan);

    // tagihan terakhir
    $queryTagihan = mysqli_query($conn, "
        SELECT *
        FROM tagihan
        WHERE id_pelanggan='$id'
        ORDER BY id_tagihan DESC
        LIMIT 1
    ");

    $tagihan = mysqli_fetch_assoc($queryTagihan);

    // meter lalu
    $meter_lalu = $tagihan['meter_bulan_ini'] ?? 0;

    // tarif
    $tarif = $pelanggan['tarif_per_m3'];

    // kategori
    $kategori = $pelanggan['kategori'];

    // admin
    if ($kategori == 'RT') {
        $admin = 10000;
    } elseif ($kategori == 'ID') {
        $admin = 15000;
    } else {
        $admin = 20000;
    }

    // cek pelanggan lama / baru
    $is_pelanggan_baru = true;

    $bulan = '';
    $tahun = '';

    if ($tagihan) {

        $is_pelanggan_baru = false;

        $bulan = $tagihan['bulan'] + 1;
        $tahun = $tagihan['tahun'];

        // desember → januari
        if ($bulan > 12) {
            $bulan = 1;
            $tahun++;
        }
    }

    echo json_encode([
        'meter_lalu' => $meter_lalu,
        'tarif' => $tarif,
        'admin' => $admin,
        'kategori' => $kategori,
        'bulan' => $bulan,
        'tahun' => $tahun,
        'pelanggan_baru' => $is_pelanggan_baru
    ]);
}