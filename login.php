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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body class="min-h-screen flex items-center justify-center p-5">

<div class="login-card bg-indigo-100/30 rounded-3xl shadow-2xl overflow-hidden w-full max-w-md">

    <div class="bg-indigo-100/50 text-white text-center p-8">

        <div class="text-3xl mb-3 text-cyan-400">
    <i class="fas fa-droplet"></i>
</div>

       <h1 class="text-4xl font-extrabold tracking-tight bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">
    Meteran Air
</h1>

<p class="text-slate-300 text-sm font-medium tracking-wide mt-1 opacity-90">
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
                    class="bg-sky-950/30 input-focus w-full border p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                    class="bg-sky-950/30 input-focus w-full border p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                class="w-full bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 transition-all duration-300 text-white font-semibold tracking-wide py-3 rounded-xl shadow-lg shadow-blue-500/20 active:scale-[0.98]">
  
                Login

            </button>

        </form>

        <div class="text-center mt-6 text-gray-950 text-sm">

            © 2026 Sistem Meteran Air Kelompok 1

        </div>

    </div>

</div>

<script src="assets/js/script.js"></script>

</body>
</html>