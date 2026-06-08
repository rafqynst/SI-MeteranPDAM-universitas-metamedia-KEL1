<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: linear-gradient(135deg, #d4fc79, #96e6a1);
        }

        .checkmark-circle {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            border: 6px solid #22c55e;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: pop 0.6s ease-out;
        }

        .checkmark {
            width: 40px;
            height: 80px;
            border-right: 8px solid #22c55e;
            border-bottom: 8px solid #22c55e;
            transform: rotate(45deg) scale(0);
            animation: drawCheck 0.5s ease forwards;
            animation-delay: 0.4s;
        }

        @keyframes pop {
            0% {
                transform: scale(0);
            }
            80% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        @keyframes drawCheck {
            from {
                transform: rotate(45deg) scale(0);
            }
            to {
                transform: rotate(45deg) scale(1);
            }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-2xl rounded-3xl p-10 text-center w-full max-w-md">

        <div class="flex justify-center mb-6">
            <div class="checkmark-circle">
                <div class="checkmark"></div>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-green-600 mb-2">
            Pembayaran Berhasil
        </h1>

        <p class="text-gray-600 mb-8">
            Tagihan telah berhasil dibayarkan.
        </p>

        <div class="flex justify-center gap-4">

            <a href="pemakaian.php"
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold">
                OK
            </a>

            <a href="cetak_struk.php?id=<?= $_GET['id'] ?? '' ?>"
               
               class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold">
                Cetak Struk
            </a>

        </div>

    </div>

</body>
</html>