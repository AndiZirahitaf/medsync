<?php
session_start();
if (isset($_COOKIE['unique_id'])) {
    $_SESSION['unique_id'] = $_COOKIE['unique_id'];
    $_SESSION['userType'] = $_COOKIE['userType'];
    $_SESSION['id'] = $_COOKIE['id'];
    $_SESSION['login'] = true;
}
if( !isset($_SESSION["login"]) ) {
    header("Location: login.php");
    exit;
}
if ($_SESSION["userType"] == "doctor" || $_SESSION["userType"] == "admin") {
    header("Location: index.php");
    exit;
}
    require 'connection/koneksi.php';
    $doctor_id = $_POST["doctor_id"];
    $doctor_details = query("SELECT * FROM doctors where id = $doctor_id")[0];
    $listSpesialis = query("SELECT * FROM profesi_spesialis where id = $doctor_details[profesi]")[0];
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-white">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <link rel="stylesheet" href="dist/css/output.css">
    <script src="https://kit.fontawesome.com/2d99d9dc75.js" crossorigin="anonymous"></script>
    <title>MedSync</title>
    <link rel="icon" href="dist/img/favicon.ico">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="h-full">
    <header class="absolute left-0 top-0 z-10 flex w-full items-center bg-transparent">
        <div class="container">
            <div class="relative flex items-center justify-between">

                <div class="">
                    <a href="index.php" class=" py-6 text-lg font-bold text-primary flex justify-center items-center">
                        <span style="margin-top: 5px;">
                            <i class="fa-solid fa-heart-pulse text-2xl text-pink-700" style="margin-right: 15px;"></i>
                        </span>
                        MedSync</a>
                </div>

                <div class="flex items-center">
                    <button id="hamburger" name="hamburger" type="button" class="absolute right-4 block lg:hidden">
                        <span class="hamburger-line origin-top-left transition duration-300 ease-in-out"></span>
                        <span class="hamburger-line transition duration-300 ease-in-out"></span>
                        <span class="hamburger-line origin-bottom-left transition duration-300 ease-in-out"></span>
                    </button>

                    <nav id="nav-menu"
                        class="absolute top-full hidden w-full max-w-[250px] rounded-lg bg-white py-5 shadow-lg  lg:static lg:block lg:max-w-full lg:rounded-none lg:bg-transparent lg:shadow-none lg:dark:bg-transparent">
                        <ul class="block lg:flex">
                            <li class="group">
                                <a href="index.php"
                                    class="mx-8 flex py-2 text-base text-dark group-hover:text-primary">Beranda</a>
                            </li>
                            <li class="group">
                                <a href="browse.php"
                                    class="mx-8 flex py-2 text-base text-dark group-hover:text-primary">Cari Dokter</a>
                            </li>
                            <?php if (isset($_SESSION["login"])) {?>
                            <a href="dashboard.php"
                                class="ml-3 rounded-md bg-white px-4 py-2 text-base font-semibold text-primary transition duration-300 ease-in-out ">Dashboard</a>

                            <?php
                            } else {
                            ?>
                            <a href="login.php"
                                class="ml-5 rounded-full bg-primary px-8 py-3 text-base font-semibold text-white transition duration-300 ease-in-out hover:opacity-80 hover:shadow-lg">Masuk</a>

                            <?php
                            }
                            ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">

        <div class="sm:mx-auto ">
            <div class="">
                <h2 class="text-2xl font-bold text-gray-900 mb-5">Buat Jadwal Konsultasi</h2>
                <div>
                    <div class="flex shadow rounded" style="padding: 20px 30px 20px 30px;">
                        <div>
                            <div class="w-20 h-20 rounded-full"
                                style="background-image: url('dist/img/<?=$doctor_details["gambar"]?>'); background-size: cover; background-position: center;">
                            </div>
                        </div>

                        <div class="ml-3">
                            <h3 class="text-sm text-gray-800 font-semibold my-1"><?=$doctor_details["nama"]?></h3>
                            <p class="text-gray-700 text-sm my-1"><?=$listSpesialis["profesi"]?></p>
                            <p class="text-gray-700 text-sm my-1">No STR: <?=$doctor_details["str"]?></p>
                            <p class="text-gray-700 text-sm my-1">Harga per sesi:
                                Rp<?= number_format($doctor_details["harga"], 0, ',', '.') ?></p>
                        </div>

                    </div>
                    <div class="mt-6">
                        <form action="total.php" method="POST">
                            <input type="number" name="doctor_id" value="<?=$doctor_id?>" hidden>
                            <div class="flex mb-4 gap-3">
                                <div>
                                    <label for="jumlah_sesi" class="block text-sm font-medium text-gray-700">Jumlah Sesi
                                        <span class="text-xs text-slate-500">(30 Menit / sesi)</span></label>
                                    <input type="number" name="jml_sesi" id="jml_sesi" required
                                        class="mt-1 focus:ring-pink-700 focus:border-pink-700 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="tanggal_konsultasi"
                                        class="block text-sm font-medium text-gray-700">Tanggal
                                        Konsultasi</label>
                                    <input type="date" name="tanggal" id="tanggal" required
                                        class="mt-1 focus:ring-pink-700 focus:border-pink-700 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="jam_konsultasi" class="block text-sm font-medium text-gray-700">Jam Mulai
                                    Konsultasi <span class="text-xs text-slate-500">(Batas akhir ditentukan
                                        sesuai jumlah sesi)</span></label>
                                <input type="time" name="time_start" id="time_start" required
                                    class="mt-1 focus:ring-pink-700 focus:border-pink-700 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div class="mb-4">
                                <label for="keluhan" class="block text-sm font-medium text-gray-700">Keluhan</label>
                                <textarea name="keluhan" id="keluhan" required
                                    class="mt-1 focus:ring-pink-700 focus:border-pink-700 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>

                            </div>

                            <div>
                                <button type="submit"
                                    class="flex justify-center items-center w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-pink-700 hover:bg-pink-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-700">Lanjut</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>



</body>

</html>