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
    $jmlSesi = $_POST["jml_sesi"];
    $tglKonsultasi = $_POST["tanggal"];
    $jamMulaiKonsultasi = $_POST["time_start"];
    $keluhan = $_POST["keluhan"];
    $doctor_id = $_POST["doctor_id"];

    $doctor_details = query("SELECT * FROM doctors where id = $doctor_id")[0];
    $listSpesialis = query("SELECT * FROM profesi_spesialis where id = $doctor_details[profesi]")[0];

    $jamAkhirKonsultasi = date('H:i', strtotime($jamMulaiKonsultasi) + ($jmlSesi * 30 * 60) );

    if (isset($_POST["tambahKonsultasi"])) {
        $total = $_POST["total"];
        $doctor_id = $_POST["doctor_id"];
        $patient_id = $_POST["patient_id"];
        $tanggal = $_POST["tanggal"];
        $jml_sesi = $_POST["jml_sesi"];
        $time_start = $_POST["time_start"];
        $time_end = $_POST["time_end"];
        $keluhan = $_POST["keluhan"];

        $resultNth = mysqli_query($conn, "SELECT MAX(nth_id) AS max_nth_id FROM consultations WHERE doctor_id = $doctor_id AND patient_id = $patient_id");
        $row = mysqli_fetch_assoc($resultNth);
        if ($row["max_nth_id"] == NULL) {
            $nth_id = 1;
        } else {
            $nth_id = $row["max_nth_id"] + 1;
        }

        $query = "INSERT INTO consultations (doctor_id, patient_id, nth_id, tanggal, jml_sesi, time_start, time_end, keluhan, status) VALUES ('$doctor_id', '$patient_id', '$nth_id', '$tanggal', '$jml_sesi', '$time_start', '$time_end', '$keluhan', 'active')";
        $result = mysqli_query($conn, $query);
        if ($result) {
            echo "<script>alert('Berhasil menambahkan konsultasi')</script>";
            echo "<script>window.location.href = 'dashboard.php'</script>";
        } else {
            echo "<script>alert('Gagal menambahkan konsultasi')</script>";
            echo "<script>window.location.href = 'browse.php'</script>";
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en" class="h-full bg-white">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="dist/css/output.css">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
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

        <div class="sm:mx-auto">
            <div class=" p-5">
                <h2 class="text-2xl font-bold text-gray-900 mb-5">Buat Jadwal Konsultasi</h2>
                <div>
                    <div class="flex p-5 shadow rounded" style="padding: 20px 30px 20px 30px;">
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
                    <div class="mt-6 divide-y">
                        <div class="flex justify-between items-center py-2">
                            <p class="text-sm text-gray-500">Tanggal Konsultasi</p>
                            <p class="text-sm text-gray-700"><?= date('d-m-Y', strtotime($tglKonsultasi)) ?></p>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <p class="text-sm text-gray-500">Jumlah Sesi</p>
                            <p class="text-sm text-gray-700"><?= $jmlSesi ?></p>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <p class="text-sm text-gray-500">Jam Konsultasi</p>
                            <p class="text-sm text-gray-700"><?= $jamMulaiKonsultasi . "-" .  $jamAkhirKonsultasi?></p>
                        </div>
                        <div class="flex flex-col py-2">
                            <div class="flex justify-between items-center ">
                                <p class="text-sm text-gray-500">Subtotal</p>
                                <p class="text-sm text-gray-700">
                                    <?= number_format($doctor_details["harga"], 0, ',', '.') ?> x <?=$jmlSesi?> =</p>
                            </div>
                            <?php $subtotal = $doctor_details["harga"] * $jmlSesi;?>
                            <p class="text-sm text-gray-700 self-end">Rp<?= number_format($subtotal, 0, ',', '.') ?>
                            </p>
                        </div>

                        <div class="flex justify-between items-center py-1">
                            <p class="text-sm text-gray-500">Diskon</p>
                            <p class="text-sm text-gray-700">
                                <?php if ($jmlSesi > 2) {
                                    $diskon = $subtotal * 0.1;} else 
                                    {
                                        $diskon = 0;
                                    }
                                echo 'Rp'.number_format($diskon, 0, ',', '.');
                                ?></p>
                        </div>
                        <div class="flex justify-between items-center pt-5">

                            <div>
                                <p class="text-sm text-gray-700">Total Pembayaran</p>
                                <p class="text-lg font-bold text-gray-900">
                                    Rp<?=number_format($total = $subtotal - $diskon, 0, ',', '.')?></p>
                            </div>
                            <form action="" method="post">
                                <input type="number" name="total" value="<?=$total?>" hidden>
                                <input type="number" name="doctor_id" value="<?=$doctor_id?>" hidden>
                                <input type="number" name="patient_id" value="<?=$_SESSION["id"]?>" hidden>
                                <input type="date" name="tanggal" value="<?=$tglKonsultasi?>" hidden>
                                <input type="number" name="jml_sesi" value="<?=$jmlSesi?>" hidden>
                                <input type="time" name="time_start" value="<?=$jamMulaiKonsultasi?>" hidden>
                                <input type="time" name="time_end" value="<?=$jamAkhirKonsultasi?>" hidden>
                                <input type="text" name="keluhan" value="<?=$keluhan?>" hidden>

                                <button type="submit" name="tambahKonsultasi"
                                    class="rounded-full bg-primary px-8 py-3 text-base font-semibold text-white transition duration-300 ease-in-out hover:opacity-80 hover:shadow-lg">Bayar</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>




</body>

</html>