<?php

session_start();
include 'config/koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];

$query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE username='$username'"
);

$user = mysqli_fetch_assoc($query);

if($user){

    if(password_verify($password, $user['password'])){

        $_SESSION['id_user'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];

        header("Location: dashboard.php");
        exit;

    }else{

        echo "Password salah";

    }

}else{

    echo "Username tidak ditemukan";

}
?>