
<?php
include 'config/koneksi.php';

header('Content-Type: application/json');

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    // Data pelanggan
    $queryPelanggan = mysqli_query($conn, "
        SELECT tarif_per_m3, kategori
        FROM pelanggan
        WHERE id_pelanggan = '$id'
    ");

    $pelanggan = mysqli_fetch_assoc($queryPelanggan);

    // Meter bulan lalu
    $queryTagihan = mysqli_query($conn, "
        SELECT meter_bulan_ini
        FROM tagihan
        WHERE id_pelanggan = '$id'
        ORDER BY id_tagihan DESC
        LIMIT 1
    ");

    $tagihan = mysqli_fetch_assoc($queryTagihan);

    $meter_lalu = isset($tagihan['meter_bulan_ini'])
        ? $tagihan['meter_bulan_ini']
        : 0;

    $tarif = $pelanggan['tarif_per_m3'];
    $kategori = $pelanggan['kategori'];

    // biaya admin berdasarkan kategori
    if ($kategori == 'RT') {
        $admin = 10000;
    } elseif ($kategori == 'ID') {
        $admin = 20000;
    } else {
        $admin = 15000;
    }

    echo json_encode([
        'meter_lalu' => $meter_lalu,
        'tarif' => $tarif,
        'kategori' => $kategori,
        'admin' => $admin
    ]);
}

