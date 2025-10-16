<?php 
session_start();
if (isset($_COOKIE['unique_id'])) {
    $_SESSION['unique_id'] = $_COOKIE['unique_id'];
    $_SESSION['userType'] = $_COOKIE['userType'];
    $_SESSION['id'] = $_COOKIE['id'];
    $_SESSION['login'] = true;
}
if( isset($_SESSION["login"]) ) {
    if ($_SESSION["userType"] == "admin") {
    header("Location: dashboard_admin.php");
    exit;
    } else if ($_SESSION["userType"] == "doctor") {
    header("Location: dashboardDokter.php");
    exit;
    } 
}

// if( !isset($_SESSION["login"]) ) {
//     header("Location: login.php");
//     exit;
// }


require 'connection/koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="dist/img/favicon.ico">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <script src="https://kit.fontawesome.com/2d99d9dc75.js" crossorigin="anonymous"></script>
    <title>MedSync</title>
    <link rel="stylesheet" href="dist/css/output.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>

<body>
    <header class="absolute left-0 top-0 z-10 flex w-full items-center bg-transparent">
        <div class="container">
            <div class="relative flex items-center justify-between">
                <div class="">
                    <a href="index.php" class=" py-6 text-lg font-bold text-primary flex justify-center items-center">
                        <span>
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
                        class="absolute right-4 top-full hidden w-full max-w-[250px] rounded-lg bg-white py-5 shadow-lg  lg:static lg:block lg:max-w-full lg:rounded-none lg:bg-transparent lg:shadow-none lg:dark:bg-transparent">
                        <ul class="block lg:flex">
                            <li class="group">
                                <a href="index.php"
                                    class="mx-8 flex py-2 text-base text-dark group-hover:text-primary">Beranda</a>
                            </li>
                            <li class="group">
                                <a href="browse.php"
                                    class="mx-8 flex py-2 text-base text-dark group-hover:text-primary">Cari Dokter</a>
                            </li>
                            <?php if (isset($_SESSION["login"])) {
                                if ($_SESSION["userType"] == "patient") {
                                    ?>

                            <a href="dashboard.php"
                                class="ml-5 rounded-md bg-white px-4 py-2 text-base font-semibold text-primary transition duration-300 ease-in-out ">Dashboard</a>

                            <?php
                                } else if ($_SESSION["userType"] == "doctor") {
                                    ?>

                            <a href="dashboardDokter.php"
                                class="ml-5 rounded-md bg-white px-4 py-2 text-base font-semibold text-primary transition duration-300 ease-in-out ">Dashboard</a>

                            <?php
                                } else if ($_SESSION["userType"] == "admin") {
                                    ?>

                            <a href="dashboard_admin.php"
                                class="ml-5 rounded-md bg-white px-4 py-2 text-base font-semibold text-primary transition duration-300 ease-in-out ">Dashboard</a>

                            <?php
                                }
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

    <section id="hero" class="pt-16">
        <div class="container">
            <div class="flex flex-wrap">
                <div class="w-full self-center px-4 lg:w-1/2">
                    <h1 class="text-base font-semibold text-primary md:text-xl">
                        <span class="mt-1 block text-4xl font-bold text-dark lg:text-5xl">Konsultasi dengan Dokter
                            Terpercaya dari Rumah</span>
                    </h1>
                    <p class="mb-10 font-medium leading-relaxed text-secondary my-5">
                        Solusi kesehatan cepat, mudah, dan aman

                    </p>

                    <a href="browse.php"
                        class="rounded-full bg-primary px-8 py-3 text-base font-semibold text-white transition duration-300 ease-in-out hover:opacity-80 hover:shadow-lg">Konsultasi
                        Sekarang</a>
                </div>
                <div class="w-full self-end px-4 lg:w-1/2">
                    <div class="relative mt-0 lg:right-0 ">
                        <img src="dist/img/worker.png" alt="Doctor" width="350px"
                            class="relative z-10 mx-auto max-w-full translate-x-10" />
                        <span class="absolute -bottom-0 left-1/2 -translate-x-1/2 md:scale-125">
                            <svg width="400" height="400" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                <path fill="#be185d"
                                    d="M53.1,-13.2C62.6,11.9,59.7,45,42,58C24.2,70.9,-8.4,63.7,-30.6,46.5C-52.9,29.3,-64.8,2.2,-58,-19.3C-51.2,-40.8,-25.6,-56.6,-1.9,-56C21.8,-55.4,43.6,-38.3,53.1,-13.2Z"
                                    transform="translate(120 80) scale(1.3)" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="mt-28 bg-primary">
        <div
            class="container mx-auto text-white border-slate-200 p-8 text-center text-sm dark:border-slate-900 md:flex-row md:px-12">
            ©
            2024
            Umar Andika. All rights reserved.
        </div>
    </footer>
</body>

</html>