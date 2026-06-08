<?php
require 'vendor/autoload.php';
include 'config/koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setTitle('Laporan Pembayaran');

/*
|--------------------------------------------------------------------------
| Judul
|--------------------------------------------------------------------------
*/
$sheet->mergeCells('A1:P1');
$sheet->setCellValue('A1', 'LAPORAN PEMBAYARAN AIR');

$sheet->mergeCells('A2:P2');
$sheet->setCellValue('A2', 'Sistem Meteran Air');

/*
|--------------------------------------------------------------------------
| Header Tabel
|--------------------------------------------------------------------------
*/
$row = 4;

$header = [
    'No',
    'Pelanggan',
    'Kategori',
    'Periode',
    'Meter Awal',
    'Meter Akhir',
    'Pemakaian (m³)',
    'Tarif',
    'HPKA',
    'Admin',
    'Tagihan',
    'Metode',
    'Bayar',
    'Tanggal',
    'Petugas',
    'Status'
];

$col = 'A';

foreach ($header as $judul) {
    $sheet->setCellValue($col . $row, $judul);
    $col++;
}

/*
|--------------------------------------------------------------------------
| Isi Data
|--------------------------------------------------------------------------
*/
$row++;
$no = 1;

$totalPemasukan = 0;
$totalPemakaian = 0;

while ($data = mysqli_fetch_assoc($query)) {

    $sheet->setCellValue('A' . $row, $no++);
    $sheet->setCellValue('B' . $row, $data['nama_pelanggan']);
    $sheet->setCellValue('C' . $row, $data['kategori']);
    $sheet->setCellValue('D' . $row, $data['bulan'] . ' ' . $data['tahun']);
    $sheet->setCellValue('E' . $row, $data['meter_bulan_lalu']);
    $sheet->setCellValue('F' . $row, $data['meter_bulan_ini']);
    $sheet->setCellValue('G' . $row, $data['pemakaian']);
    $sheet->setCellValue('H' . $row, $data['tarif_per_m3']);
    $sheet->setCellValue('I' . $row, $data['hpka']);
    $sheet->setCellValue('J' . $row, $data['biaya_admin']);
    $sheet->setCellValue('K' . $row, $data['total_tagihan']);
    $sheet->setCellValue('L' . $row, $data['metode_pembayaran']);
    $sheet->setCellValue('M' . $row, $data['total_bayar']);
    $sheet->setCellValue('N' . $row, date('d-m-Y', strtotime($data['tanggal_bayar'])));
    $sheet->setCellValue('O' . $row, $data['nama_petugas']);
    $sheet->setCellValue('P' . $row, $data['status_bayar']);

    $totalPemasukan += $data['total_bayar'];
    $totalPemakaian += $data['pemakaian'];

    $row++;
}

/*
|--------------------------------------------------------------------------
| Ringkasan
|--------------------------------------------------------------------------
*/
$row += 2;

$sheet->setCellValue('A' . $row, 'Total Pemasukan');
$sheet->setCellValue('B' . $row, $totalPemasukan);

$row++;

$sheet->setCellValue('A' . $row, 'Total Pemakaian');
$sheet->setCellValue('B' . $row, $totalPemakaian . ' m³');

/*
|--------------------------------------------------------------------------
| Format Rupiah
|--------------------------------------------------------------------------
*/
$sheet->getStyle('H5:M' . ($row - 3))
    ->getNumberFormat()
    ->setFormatCode('#,##0');

/*
|--------------------------------------------------------------------------
| Auto Width
|--------------------------------------------------------------------------
*/
foreach (range('A', 'P') as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

/*
|--------------------------------------------------------------------------
| Style Header
|--------------------------------------------------------------------------
*/
$sheet->getStyle('A4:P4')->applyFromArray([
    'font' => [
        'bold' => true,
        'color' => [
            'rgb' => 'FFFFFF'
        ]
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
            'rgb' => '2563EB'
        ]
    ]
]);

$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);

/*
|--------------------------------------------------------------------------
| Download File
|--------------------------------------------------------------------------
*/
$filename = 'Laporan_Pembayaran_' . date('Ymd_His') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;