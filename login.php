<?php
session_start();
include 'config/koneksi.php';

if(isset($_SESSION['login'])){
    header("Location: dashboard.php");
    exit;
}

$error = "";

if(isset($_POST['login'])){

    $username = mysqli_real_escape_string(
        $conn,
        $_POST['username']
    );

    $password = md5($_POST['password']);

    $query = mysqli_query(
        $conn,
        "SELECT * FROM petugas
         WHERE username='$username'
         AND password='$password'"
    );

    if(mysqli_num_rows($query) > 0){

        $data = mysqli_fetch_assoc($query);

        $_SESSION['login'] = true;
        $_SESSION['id_petugas'] = $data['id_petugas'];
        $_SESSION['nama_petugas'] = $data['nama_petugas'];
        $_SESSION['level'] = $data['level'];

        header("Location: dashboard.php");
        exit;

    }else{
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login Meteran Air</title>

<script src="https://cdn.tailwindcss.com"></script>

<link rel="stylesheet" href="assets/css/style.css">

</head>

<body class="min-h-screen flex items-center justify-center p-5">

<div class="login-card bg-white rounded-3xl shadow-2xl overflow-hidden w-full max-w-md">

    <div class="bg-blue-600 text-white text-center p-8">

        <div class="text-6xl mb-3">
            💧
        </div>

        <h1 class="text-3xl font-bold">
            Meteran Air
        </h1>

        <p class="opacity-80 mt-2">
            Sistem Pengelolaan Pelanggan Air
        </p>

    </div>

    <div class="p-8">

        <?php if($error != "") : ?>

        <div class="bg-red-100 border border-red-300 text-red-700 p-3 rounded-lg mb-5">
            <?= $error ?>
        </div>

        <?php endif; ?>

        <form method="POST">

            <div class="mb-4">

                <label class="block mb-2 font-semibold">
                    Username
                </label>

                <input
                    type="text"
                    name="username"
                    required
                    class="input-focus w-full border p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan username">

            </div>

            <div class="mb-5">

                <label class="block mb-2 font-semibold">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    class="input-focus w-full border p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan password">

            </div>

            <div class="mb-5">

                <label class="flex items-center gap-2 cursor-pointer">

                    <input
                        type="checkbox"
                        onclick="togglePassword()">

                    <span class="text-sm">
                        Tampilkan Password
                    </span>

                </label>

            </div>

            <button
                type="submit"
                name="login"
                class="w-full bg-blue-600 hover:bg-blue-700 transition text-white font-bold py-3 rounded-xl">

                Login

            </button>

        </form>

        <div class="text-center mt-6 text-gray-500 text-sm">

            © 2026 Sistem Meteran Air Kelompok 1

        </div>

    </div>

</div>

<script src="assets/js/script.js"></script>

</body>
</html>