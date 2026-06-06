<?php
include 'config/koneksi.php';

$id_tagihan = $_POST['id_tagihan'];
$metode = $_POST['metode'];
$total = $_POST['total_bayar'];

// sementara gunakan id petugas 1
$id_petugas = 1;

$insert = mysqli_query($conn,"
INSERT INTO pembayaran(
id_tagihan,
id_petugas,
metode_pembayaran,
total_bayar,
status_pembayaran
)
VALUES(
'$id_tagihan',
'$id_petugas',
'$metode',
'$total',
'Berhasil'
)
");

if($insert){

mysqli_query($conn,"
UPDATE tagihan
SET status='Lunas'
WHERE id_tagihan='$id_tagihan'
");

?>
<!DOCTYPE html>
<html>
<head>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>

<script>

Swal.fire({
icon:'success',
title:'Pembayaran Berhasil',
text:'Tagihan berhasil dibayar',
confirmButtonText:'OK'
}).then(()=>{

window.location='riwayat_pembayaran.php';

});

</script>

</body>
</html>

<?php

}else{

?>

<!DOCTYPE html>
<html>
<head>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>

<script>

Swal.fire({
icon:'error',
title:'Gagal',
text:'Pembayaran gagal disimpan',
confirmButtonText:'OK'
}).then(()=>{

history.back();

});

</script>

</body>
</html>

<?php
}
?>