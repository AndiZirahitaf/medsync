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



if (isset($_POST["ubahProfil"])) {
    if (ubahProfilPasien($_POST) > 0) {
        echo "
            <script>
                alert('Profil berhasil diubah!');
                document.location.href = 'dashboard.php';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('Profil gagal diubah!');
                document.location.href = 'dashboard.php';
            </script>
        ";
    }
}



$gantiPassword = false;

$patient = query("SELECT * FROM patients where id = " . $_SESSION["id"])[0];

$daftar_konsultasi = query("SELECT * FROM consultations where patient_id = " . $_SESSION["id"]." and status = 'active' ORDER BY tanggal ASC");

$daftar_histori = query("SELECT * FROM consultations where patient_id = " . $_SESSION["id"]." and status = 'archive' ORDER BY tanggal ASC");

$listSpesialis = query("SELECT * FROM profesi_spesialis");

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
                                    class="mx-8 flex py-3 text-base text-dark group-hover:text-primary">Beranda</a>
                            </li>
                            <li class="group">
                                <a href="browse.php"
                                    class="mx-8 flex py-3 text-base text-dark group-hover:text-primary">Cari Dokter</a>
                            </li>
                            <a href="logout.php" style="margin-left: 25px;"
                                class=" rounded-full bg-primary px-8 py-3 text-base font-semibold text-white transition duration-300 ease-in-out hover:opacity-80 hover:shadow-lg">Keluar</a>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <div class="container" style="padding-top: 120px;">
        <h1 class="text-4xl mb-10 font-bold tracking-tight text-gray-900 pb-4">Hai, <?=$patient["nama"]?>!</h1>
        <div class="flex justify-between items-end">
            <div>
                <div class="sm:px-0">
                    <h3 class="text-xl font-semibold leading-7 text-gray-900">Profil</h3>
                    <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Dokter dapat melihat informasi ini saat
                        Anda berkonsultasi.</p>
                </div>
            </div>
            <div x-data="{ isOpen: false }">

                <a data-bs-toggle="modal"
                    data-bs-target="#profileModal-<?= $patient["id"] ?>-<?= $patient["nama"] ?>-<?= $patient["kelamin"] ?>-<?= $patient["tgl_lahir"] ?>-<?= $patient["bb"] ?>-<?= $patient["tb"] ?>-<?= $patient["no_tlp"] ?>-<?= $patient["email"] ?>"
                    @click="isOpen = !isOpen"
                    class="rounded-md bg-primary px-4 py-2 cursor-pointer text-sm font-semibold text-white hover:opacity-80">Edit
                    Profil</a>
                <div x-show="isOpen" class="relative z-10 cursor-default" aria-labelledby="docModalLabel" role="dialog"
                    aria-modal="true"
                    id="profileModal-<?= $patient["id"] ?>-<?= $patient["nama"] ?>-<?= $patient["kelamin"] ?>-<?= $patient["tgl_lahir"] ?>-<?= $patient["bb"] ?>-<?= $patient["tb"] ?>-<?= $patient["no_tlp"] ?>-<?= $patient["email"] ?>">
                    <div x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200 transform"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                            <div x-show="isOpen" x-transition:enter="transition ease-out duration-300 transform"
                                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                x-transition:leave="transition ease-in duration-200 transform"
                                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                <form class="space-y-6" action="" method="POST">
                                    <input type="number" name="id" id="id" hidden>
                                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                        <div class="flex justify-between items-start">
                                            <h3 class="text-xl font-semibold text-gray-900 "
                                                style="margin-bottom: 15px;">
                                                Edit Profil</h3>

                                            <button @click="isOpen = !isOpen"
                                                class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500">
                                                <i class="fa-solid fa-times text-lg"></i>
                                            </button>
                                        </div>

                                        <div style="margin-bottom: 15px;">
                                            <label for="email"
                                                class="block text-sm font-medium leading-6 text-gray-900">Alamat
                                                Email</label>
                                            <div class="mt-2">
                                                <input id="email" name="email" type="email" autocomplete="email"
                                                    value="<?=$patient["email"]?>" required
                                                    class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                                            </div>
                                        </div>

                                        <div style="margin-bottom: 15px;">
                                            <label for="email"
                                                class="block text-sm font-medium leading-6 text-gray-900">Nama
                                                Lengkap</label>
                                            <div class="mt-2">
                                                <input id="nama" name="nama" type="text" autocomplete="nama"
                                                    value="<?=$patient["nama"]?>" required
                                                    class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                                            </div>
                                        </div>

                                        <div style="margin-bottom: 15px;">
                                            <label for="gender"
                                                class="block text-sm font-medium leading-6 text-gray-900">Jenis
                                                Kelamin</label>
                                            <div class="flex my-auto mt-2">
                                                <div class="flex items-center mr-5">
                                                    <input id="gender-male" name="kelamin" type="radio" value="l"
                                                        <?= $patient["kelamin"] == "l" ? "checked" : "" ?>
                                                        class="form-radio h-4 w-4 text-pink-700">
                                                    <label for="gender-male"
                                                        class="ml-2 text-sm text-gray-900">Laki-Laki</label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input id="gender-female" name="kelamin" type="radio" value="p"
                                                        <?= $patient["kelamin"] == "p" ? "checked" : "" ?>
                                                        class="form-radio h-4 w-4 text-pink-700">
                                                    <label for="gender-female"
                                                        class="ml-2 text-sm text-gray-900">Perempuan</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div style="margin-bottom: 15px;">
                                            <label for="birthdate"
                                                class="block text-sm font-medium leading-6 text-gray-900">Tanggal
                                                Lahir</label>
                                            <div class="mt-2">
                                                <input id="birthdate" name="tgl_lahir" type="date"
                                                    value="<?=$patient["tgl_lahir"]?>" required
                                                    class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-3" style="margin-bottom: 15px;">
                                            <div>
                                                <label for="weight"
                                                    class="block text-sm font-medium leading-6 text-gray-900">Berat
                                                    Badan
                                                    (kg)</label>
                                                <div class="mt-2">
                                                    <input id="weight" name="bb" type="number"
                                                        value="<?=$patient["bb"]?>" required
                                                        class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                                                </div>
                                            </div>

                                            <div>
                                                <label for="height"
                                                    class="block text-sm font-medium leading-6 text-gray-900">Tinggi
                                                    Badan
                                                    (cm)</label>
                                                <div class="mt-2">
                                                    <input id="height" name="tb" type="number"
                                                        value="<?=$patient["tb"]?>" required
                                                        class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                                                </div>
                                            </div>
                                        </div>


                                        <div style="margin-bottom: 15px;">
                                            <label for="phone"
                                                class="block text-sm font-medium leading-6 text-gray-900">Nomor
                                                Telepon</label>
                                            <div class="mt-2">
                                                <input id="phone" name="no_tlp" type="tel"
                                                    value="<?=$patient["no_tlp"]?>" required
                                                    class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                                            </div>
                                        </div>

                                        <div>
                                            <div style="margin-bottom: 15px;">

                                                <label for="password"
                                                    class="block text-sm font-medium leading-6 text-gray-900">Password</label>

                                                <div class="mt-2">
                                                    <input id="password" name="password" type="password"
                                                        autocomplete="current-password"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                                                </div>
                                            </div>

                                            <div style="margin-bottom: 15px;">
                                                <label for="new_password"
                                                    class="block text-sm font-medium leading-6 text-gray-900">
                                                    Password Baru</label>
                                                <div class="mt-2">
                                                    <input id="new_password" name="new_password" type="password"
                                                        autocomplete="new-password"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                                                </div>
                                            </div>

                                            <div style="margin-bottom: 15px;">
                                                <label for="new_password_confirmation"
                                                    class="block text-sm font-medium leading-6 text-gray-900">Konfirmasi
                                                    Password Baru</label>
                                                <div class="mt-2">
                                                    <input id="new_password_confirmation"
                                                        name="new_password_confirmation" type="password"
                                                        autocomplete="new-password-confirmation"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                                                </div>
                                            </div>
                                        </div>



                                        <div class="mt-4">
                                            <button type="submit" name="ubahProfil"
                                                class="flex w-full justify-center rounded-md bg-pink-700 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-pink-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-pink-700">Simpan
                                                Perubahan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-6 border-t  grid grid-cols-2">
            <div class="divide-y col-span-1 divide-gray-100">
                <div class="py-4 grid grid-cols-3 gap-2 items-center">
                    <dt class="col-span-1 text-sm font-medium leading-6 text-gray-900">Nama Lengkap</dt>
                    <dd class="col-span-1 mt-1 text-sm  text-gray-700 sm:col-span-2 sm:mt-0"><?=$patient["nama"]?></dd>
                </div>
                <div class="py-4 grid grid-cols-3 gap-2 items-center">
                    <dt class="col-span-1 text-sm font-medium leading-6 text-gray-900">Jenis Kelamin</dt>
                    <dd class="col-span-1 mt-1 text-sm leading-6  text-gray-700 sm:col-span-2 sm:mt-0">
                        <?= $patient["kelamin"] == "l" ? "Laki-Laki" : "Perempuan"?></dd>
                </div>
                <div class="py-4 grid grid-cols-3 gap-2 items-center">
                    <dt class="col-span-1 text-sm font-medium leading-6 text-gray-900">Tanggal Lahir</dt>
                    <dd class="col-span-1 mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        <?= date("d/m/Y", strtotime($patient["tgl_lahir"])) ?></dd>
                </div>
                <div class="py-4 grid grid-cols-3 gap-2 items-center">
                    <dt class="col-span-1 text-sm font-medium leading-6 text-gray-900">Berat Badan</dt>
                    <dd class="col-span-1 mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        <?=$patient["bb"]?> Kg</dd>
                </div>
            </div>

            <div class="divide-y col-span-1 divide-gray-100">
                <div class="py-4 grid grid-cols-3 gap-2 items-center">
                    <dt class="col-span-1 text-sm font-medium leading-6 text-gray-900">Tinggi</dt>
                    <dd class="col-span-1 mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        <?=$patient["tb"]?> cm</dd>
                </div>
                <div class="py-4 grid grid-cols-3 gap-2 items-center">
                    <dt class="col-span-1 text-sm font-medium leading-6 text-gray-900">Nomor Telepon</dt>
                    <dd class="col-span-1 mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        <?=$patient["no_tlp"]?></dd>
                </div>
                <div class="py-4 grid grid-cols-3 gap-2 items-center">
                    <dt class="col-span-1 text-sm font-medium leading-6 text-gray-900">Email</dt>
                    <dd class="col-span-1 mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        <?=$patient["email"]?></dd>
                </div>

            </div>
        </div>

        <!-- {{--Jadwal Konsultasi--}} -->

        <div style="margin-top: 70px;">
            <div>
                <h3 class="text-xl font-bold leading-7 text-gray-900">Jadwal Konsultasi</h3>
                <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Berikut adalah jadwal konsultasi Anda.</p>
            </div>
            <div>

                <?php if (count($daftar_konsultasi) == 0) : ?>
                <p class="text-md font-semibold leading-6 text-gray-500 mt-3">Anda belum memiliki jadwal konsultasi.
                </p>
                <?php endif; ?>
                <div class="grid gap-6 grid-cols-3 pt-5">
                    <?php foreach ($daftar_konsultasi as $konsultasi) : ?>
                    <div class="bg-white shadow rounded-lg p-7">
                        <div class="flex items-start justify-between">
                            <div>
                                <?php $doctor = query("SELECT * FROM doctors where id = " . $konsultasi["doctor_id"])[0];
                            $listSpesialis = query("SELECT * FROM profesi_spesialis where id = $doctor[profesi]")[0];
                            ?>

                                <!-- <p class="text-md font-semibold leading-6 text-gray-900"></p> -->
                                <p class="text-sm  leading-6 text-gray-900">Tanggal:
                                    <span
                                        class="font-semibold"><?= date('d/m/Y', strtotime($konsultasi["tanggal"])) ?></span>
                                </p>
                                <p class="text-md leading-6 text-gray-900">Jam:
                                    <span class="font-bold"><?= date('H:i', strtotime($konsultasi["time_start"])) ?>
                                        -
                                        <?= date('H:i', strtotime($konsultasi["time_end"])) ?></span>
                                </p>
                                <p class="mt-3 text-sm leading-2 text-gray-500">Bersama <span
                                        class="font-semibold text-black"><?=$doctor["nama"]?></span></p>
                                <p class="mt-1 text-sm leading-2 text-gray-500"><?=$listSpesialis["profesi"]?></p>
                            </div>
                            <div class="flex items center">
                                <div class="w-20 h-20 rounded-full"
                                    style="background-image: url('dist/img/<?=$doctor["gambar"]?>'); background-size: cover; background-position: center;">
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <?php if ($konsultasi["kode_unik"] == "") : ?>
                            <a class="flex cursor-default justify-center items-center w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-sm text-gray-500"
                                style="background-color: #d1d1d1">Akan dibagikan ketika jam konsultasi
                                dimulai.</a>
                            <!-- <p class="text-xs text-gray-400 mt-2">Room Chat akan dibagikan ketika jam konsultasi
                                dimulai.
                            </p> -->
                            <?php else : ?>
                            <a href="chats/chatRoom.php?code=<?=$konsultasi["kode_unik"]?>"
                                class="flex justify-center items-center w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-pink-700 hover:bg-pink-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-700">Gabung
                                ke Room Chat</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>

        <!-- {{--Histori Konsultasi--}} -->
        <div style="margin-top: 70px;">
            <div>
                <h3 class="text-xl font-bold leading-7 text-gray-900">Histori Konsultasi</h3>
                <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Arsip konsultasi Anda.</p>
            </div>
            <div>
                <?php if (count($daftar_histori) == 0) : ?>
                <p class="text-md font-semibold leading-6 text-gray-500 mt-3">Anda belum memiliki histori konsultasi.
                </p>
                <?php endif; ?>
                <div class="grid grid-cols-2 mt-1" style="column-gap: 20px; row-gap: 10px;">
                    <?php foreach ($daftar_histori as $histori) : ?>
                    <div role="list" class="divide-y divide-gray-100 rounded-md shadow">
                        <?php $doctor = query("SELECT * FROM doctors where id = " . $histori["doctor_id"])[0];?>
                        <div class="flex justify-between gap-x-6 py-5 px-6">
                            <div class="flex min-w-0 gap-x-4">
                                <img class="h-12 w-12 flex-none rounded-full bg-gray-50"
                                    src="dist/img/<?=$doctor["gambar"]?>" alt="" style="object-fit: cover;">
                                <div class="min-w-0 flex-auto">
                                    <p class="text-sm font-semibold leading-6 text-gray-900"><?=$doctor["nama"]?></p>
                                    <a href="chats/historyChatRoom.php?code=<?=$histori["kode_unik"]?>"
                                        class="text-white text-xs bg-primary py-1 px-2 rounded-md">Lihat
                                        Histori Chat</a>
                                </div>
                            </div>
                            <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                                <p class="text-sm leading-6 text-gray-900 ">
                                    <?= date('d-m-Y', strtotime($histori["tanggal"])) ?></p>
                                <p class="mt-1 text-xs leading-5 text-gray-500">
                                    <?= date('H:i', strtotime($histori["time_start"])) ?> -
                                    <?= date('H:i', strtotime($histori["time_end"])) ?></time></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>
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