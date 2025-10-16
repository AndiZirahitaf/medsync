<?php 
session_start();
if (isset($_COOKIE['unique_id'])) {
    $_SESSION['unique_id'] = $_COOKIE['unique_id'];
    $_SESSION['userType'] = $_COOKIE['userType'];
    $_SESSION['id'] = $_COOKIE['id'];
    $_SESSION['login'] = true;
}
// if( !isset($_SESSION["login"]) ) {
//     header("Location: login.php");
//     exit;
// }
if( isset($_SESSION["login"]) ) {
    if ($_SESSION["userType"] == "doctor" || $_SESSION["userType"] == "admin") {
    header("Location: index.php");
    exit;
}
}

require 'connection/koneksi.php';
    $doctors = query("SELECT * FROM doctors");
    $listSpesialis = query("SELECT * FROM profesi_spesialis");


    if (isset($_POST["filter"])) {
        $count = 0;
        $sql = "SELECT * FROM doctors ";

        
        $stringAlert = "";
        for ($i = 0; $i < count($listSpesialis); $i++) {
            if (isset($_POST["cat-" . ($i + 1)])) {
                $stringAlert .= $listSpesialis[$i]["profesi"];
                $count++;
                if ($count == 1) {
                    $sql .= "WHERE profesi = " . $listSpesialis[$i]["id"];
                } else {
                    $sql .= " OR profesi = " . $listSpesialis[$i]["id"];
                }
            } 
        }

        if (isset ($_POST["sort"])) {
            $sort = $_POST["sort"];
            if ($sort == 2) {
            $sql .= " ORDER BY harga ASC";
            } else if ($sort == 3) {
                $sql .= " ORDER BY rating DESC";
            } else if ($sort == 4) {
                $sql .= " ORDER BY pengalaman DESC";
            }
        }
        
        // echo "<script>alert('".$stringAlert."')</script>";
        // echo "<script>alert('".$sql."')</script>";
    
        $doctors = query($sql);
        
    }
?>
<!DOCTYPE html>
<html lang="en">

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
    <script></script>
</head>

<body>
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
    <section id="browse" style="padding-top: 80px;">
        <div class="container py-4 flex items-center gap-3">
            <a href="index.html" class="text-primary text-base"> <i class="fas fa-home"></i>
            </a>
            <span class="text-sm text-gray-400">
                <i class="fa
                    
                    fa-chevron-right"></i>
            </span>
            <p class="text-gray-600 font-medium">Cari Dokter</p>
        </div>

        <div class="container grid grid-cols-5 gap-6 pt-4 pb-16 items-start">
            <div class="col-span-1 bg-white px-4 pb-6 shadow rounded overflow-hidden">
                <div class="divide-y divide-gray-200 space-y-5">
                    <form action="" method="post">
                        <div class="pt-5">
                            <h3 class="text-md text-gray-800 mb-3 font-bold">Spesialis</h3>
                            <div class="space-y-2">
                                <?php
                                        for ($i = 0; $i < count($listSpesialis); $i++) {

                                            ?>
                                <div class="flex items-center">
                                    <input type="checkbox" name="cat-<?= $i + 1 ?>" <?php if (isset($_POST["cat-" . ($i + 1)])) {
                                                echo "checked";
                                            } ?> class="text-primary focus:ring-0 rounded-sm cursor-pointer">
                                    <label for="cat-1" class="text-gray-600 ml-3 cursor-pointer">
                                        <?php echo $listSpesialis[$i]["profesi"]; ?>
                                    </label>
                                    <div class="ml-auto text-gray-600 text-sm">
                                        <?php
                                                        $sql = "SELECT COUNT(*) as jumlah FROM doctors WHERE profesi = ".$listSpesialis[$i]["id"];
                                                        $result = mysqli_query($conn, $sql);
                                                        $row = mysqli_fetch_assoc($result);
                                                        echo "(".$row["jumlah"].")";
                                                        ?>
                                    </div>
                                </div>
                                <?php

                                        }
                                        ?>
                            </div>

                        </div>
                        <div class="pt-5">
                            <h3 class="text-md text-gray-800 mb-3 font-bold">Urut Berdasarkan</h3>
                            <div class="flex items-center mb-4">
                                <select name="sort"
                                    class="w-full text-sm text-gray-600 px-4 py-3 border-gray-300 shadow-sm rounded focus:ring-primary focus:border-primary">
                                    <option value="1" <?php 
                                    if (isset($_POST["sort"]) && $_POST["sort"] == "1") {
                                         echo "selected";
                                    } 
                                    ?>>Default
                                    </option>
                                    <option value="2" <?php if (isset($_POST["sort"]) && $_POST["sort"] == "2") {
                                         echo "selected";
                                    }  ?>>Harga
                                    </option>
                                    <option value="3" <?php if (isset($_POST["sort"]) && $_POST["sort"] == "3") {
                                         echo "selected";
                                    }  ?>>Rating
                                    </option>
                                    <option value="4" <?php if (isset($_POST["sort"]) && $_POST["sort"] == "4") {
                                         echo "selected";
                                    }  ?>>
                                        Pengalaman</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" name="filter"
                            class="flex justify-center items-center w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-pink-700 hover:bg-pink-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-700">
                            Filter</button>
                    </form>

                </div>
            </div>

            <div class="col-span-4">

                <div class="grid grid-cols-3 gap-6">
                    <?php $i = 1; ?>
                    <?php foreach( $doctors as $doctor ) : ?>
                    <div data-bs-toggle="modal"
                        data-bs-target="#docModal-<?= $doctor["id"] ?>-<?= $doctor["nama"] ?>-<?= $doctor["profesi"] ?>-<?= $doctor["pengalaman"] ?>-<?= $doctor["rating"] ?>-<?= $doctor["mahir"] ?>-<?= $doctor["harga"] ?>-<?= $doctor["gambar"] ?>-<?= $doctor["str"] ?>-<?= $doctor["alumni"] ?>-<?= $doctor["praktik"] ?>"
                        x-data="{ isOpen: false }" @click="isOpen = !isOpen"
                        class="detail_doc bg-white shadow h-60 rounded overflow-hidden my-auto card border- hover:border-primary border-2 cursor-pointer">
                        <div class="p-7">
                            <div class="flex">
                                <div>
                                    <div class="w-20 h-20 rounded-full"
                                        style="background-image: url('dist/img/<?= $doctor["gambar"] ?>'); background-size: cover; background-position: center;">
                                    </div>

                                </div>

                                <div class="ml-3">
                                    <h3 class="text-sm text-gray-800 font-semibold"><?= $doctor["nama"] ?></h3>
                                    <p class="text-gray-700 text-sm"><?php
                                        for ($i = 0; $i < count($listSpesialis); $i++) {

                                            if ($listSpesialis[$i]["id"] == $doctor["profesi"]) {
                                                echo $listSpesialis[$i]["profesi"];;
                                            }

                                        }
                                        ?></p>
                                    <div class="flex mt-2">
                                        <div class="py-1 px-2 bg-slate-200 rounded-md">
                                            <p class="text-gray-600 text-xs"><i
                                                    class="fa-solid fa-suitcase pr-2"></i><?= $doctor["pengalaman"] ?>
                                                Tahun
                                            </p>
                                        </div>
                                        <div class="py-1 px-2 bg-slate-200 rounded-md ml-2">
                                            <p class="text-gray-600 text-xs"><i
                                                    class="fa-solid fa-star pr-2"></i><?= $doctor["rating"] ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-500 text-sm mt-4">
                                Mahir di bidang:
                            </p>
                            <p class="text-gray-500 text-sm">
                                <?= $doctor["mahir"] == null ? '-' : $doctor["mahir"]?>
                            </p>
                            <p class="text-primary font-semibold text-xl mt-3">Rp.
                                <?= number_format($doctor["harga"], 0, ',', '.') ?><span class="text-xs text-gray-700">
                                    / Sesi</span></p>
                        </div>
                        <div x-show="isOpen" class="relative z-10 cursor-default" aria-labelledby="docModalLabel"
                            role="dialog" aria-modal="true"
                            id="docModal-<?= $doctor["id"] ?>-<?= $doctor["nama"] ?>-<?= $doctor["profesi"] ?>-<?= $doctor["pengalaman"] ?>-<?= $doctor["rating"] ?>-<?= $doctor["mahir"] ?>-<?= $doctor["harga"] ?>-<?= $doctor["gambar"] ?>-<?= $doctor["str"] ?>-<?= $doctor["alumni"] ?>-<?= $doctor["praktik"] ?>">
                            <div x-transition:enter="transition ease-out duration-300 transform"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-200 transform"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                <div
                                    class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                    <div x-show="isOpen" x-transition:enter="transition ease-out duration-300 transform"
                                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                        x-transition:leave="transition ease-in duration-200 transform"
                                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                        class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                        <form action="checkout.php" method="post">
                                            <input type="number" value="<?=$doctor["id"]?>" name="doctor_id" hidden>
                                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                                <div class="sm:flex sm:items-start justify-between">
                                                    <div class="flex">
                                                        <div class="mx-auto flex h-20 w-20 flex-shrink-0 items-center justify-center rounded-full"
                                                            style="background-image: url('dist/img/<?= $doctor["gambar"] ?>'); background-size: cover; background-position: center;">
                                                        </div>
                                                        <div class="ml-5">
                                                            <h3 class="text-xl font-semibold leading-6 text-gray-900"
                                                                id="modal-title"><?=$doctor["nama"]?></h3>
                                                            <p class="text-gray-700 text-md"><?php
                                                                for ($i = 0; $i < count($listSpesialis); $i++) {

                                                                    if ($listSpesialis[$i]["id"] == $doctor["profesi"]) {
                                                                        echo $listSpesialis[$i]["profesi"];;
                                                                    }

                                                                }
                                                                ?>
                                                            </p>
                                                            <div class="flex mt-2">
                                                                <div class="py-1 px-2 bg-slate-200 rounded-md">
                                                                    <p class="text-gray-600 text-xs"><i
                                                                            class="fa-solid fa-suitcase pr-2"></i><?= $doctor["pengalaman"] ?>
                                                                        Tahun
                                                                    </p>
                                                                </div>
                                                                <div class="py-1 px-2 bg-slate-200 rounded-md ml-2">
                                                                    <p class="text-gray-600 text-xs"><i
                                                                            class="fa-solid fa-star pr-2"></i><?= $doctor["rating"] ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <button type="button"
                                                        class="text-gray-500 hover:text-gray-700 focus:outline-none">
                                                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>


                                                <div class="mt-2">

                                                    <div class="flex mt-4 items-center">
                                                        <div class="w-9">
                                                            <i
                                                                class="fa-solid fa-id-card text-xl text-primary pr-3"></i>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm text-gray-800 font-bold">No STR</p>
                                                            <p class="text-sm font-normal text-gray-500 italic">
                                                                <?=$doctor["str"]?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="flex mt-2 items-center">
                                                        <div class="w-9">
                                                            <i
                                                                class="fa-solid fa-user-graduate text-xl text-primary pr-3"></i>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm text-gray-800 font-bold">Alumni</p>
                                                            <p class="text-sm font-normal text-gray-500 italic">
                                                                <?=$doctor["alumni"]?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="flex mt-2 items-center">
                                                        <div class="w-9">
                                                            <i
                                                                class="fa-solid fa-map-location-dot text-xl text-primary pr-3"></i>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm text-gray-800 font-bold">Praktik di</p>
                                                            <p class="text-sm font-normal text-gray-500 italic">
                                                                <?=$doctor["praktik"]?>
                                                            </p>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                            <div
                                                class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 items-center">
                                                <button type="submit" name="jadwal"
                                                    class="inline-flex w-full justify-center rounded-md bg-primary px-3 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto">Jadwalkan
                                                    Konsultasi</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <?php $i++; ?>
                    <?php endforeach; ?>

                </div>

            </div>




        </div>

    </section>
    <footer class="mt-10 bg-primary">
        <div
            class="container mx-auto text-white border-slate-200 p-8 text-center text-sm dark:border-slate-900 md:flex-row md:px-12">
            ©
            2024
            Umar Andika. All rights reserved.
        </div>
    </footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
</body>

</html>