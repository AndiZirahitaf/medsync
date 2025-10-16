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
if ($_SESSION["userType"] == "doctor" || $_SESSION["userType"] == "patient") {
    header("Location: index.php");
    exit;
}
require 'connection/koneksi.php';
$doctors = query("SELECT * FROM doctors");

$admin = query("SELECT * FROM admin where id = " . $_SESSION["id"])[0];

$listSpesialis = query("SELECT * FROM profesi_spesialis");
  


if( isset($_POST["tambahDokter"]) ) {
    if( tambahDokter($_POST) > 0 ) {
        echo "
            <script>
                alert('berhasil menambahkan dokter!');
                document.location.href = 'dashboard_admin.php';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('gagal menambahkan dokter!');
                document.location.href = 'dashboard_admin.php';
            </script>
        ";
    }
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
    <style>
    .table-wrapper {
        border-radius: 15px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        overflow-y: scroll;
        overflow-x: scroll;
        height: fit-content;
        max-height: 66.4vh;
    }
    </style>
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
                <div class="flex items-center px-4">
                    <button id="hamburger" name="hamburger" type="button" class="absolute right-4 block lg:hidden">
                        <span class="hamburger-line origin-top-left transition duration-300 ease-in-out"></span>
                        <span class="hamburger-line transition duration-300 ease-in-out"></span>
                        <span class="hamburger-line origin-bottom-left transition duration-300 ease-in-out"></span>
                    </button>

                    <nav id="nav-menu"
                        class="absolute right-4 top-full hidden w-full max-w-[250px] rounded-lg bg-white py-5 shadow-lg  lg:static lg:block lg:max-w-full lg:rounded-none lg:bg-transparent lg:shadow-none lg:dark:bg-transparent">
                        <ul class="block lg:flex items-center">

                            <li class="mt-5">
                                <a href="logout.php"
                                    class="ml-5 rounded-md bg-white px-4 py-2 text-base font-semibold text-primary transition duration-300 ease-in-out ">
                                    Keluar</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <div class="container" style="padding-top: 100px; padding-bottom: 100px;">
        <h1 class="text-4xl mb-10 font-bold tracking-tight text-gray-900 pb-4">Selamat Datang, <span
                class="italic"><?=$admin["name"]?></span></h1>
        <div class="flex justify-between items-center">
            <div>
                <div class="sm:px-0">
                    <h3 class="text-xl font-semibold leading-7 text-gray-900">Daftar Dokter</h3>
                    <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Berikut list data dokter. Anda bisa
                        menambahkan akun dokter.</p>
                </div>
            </div>
            <div x-data="{ isOpen: false }">

                <a data-bs-toggle="modal" data-bs-target="#profileModal" @click="isOpen = !isOpen"
                    class="rounded-full bg-primary px-8 py-3 cursor-pointer text-base font-semibold text-white transition duration-300 ease-in-out hover:opacity-80 hover:shadow-lg">Tambah
                    Akun Dokter</a>
                <div x-show="isOpen" class="relative z-10 cursor-default" aria-labelledby="docModalLabel" role="dialog"
                    aria-modal="true" id="profileModal">
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
                                <form class="space-y-6" action="" method="post" enctype="multipart/form-data">
                                    <input type="number" name="id" id="id" hidden>
                                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                        <div class="flex justify-between items-start">
                                            <h3 class="text-xl font-semibold text-gray-900 "
                                                style="margin-bottom: 15px;">
                                                Tambah Dokter</h3>

                                            <button @click="isOpen = !isOpen"
                                                class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500">
                                                <i class="fa-solid fa-times text-lg"></i>
                                            </button>
                                        </div>
                                        <div style="margin-bottom: 15px;">
                                            <label for="str"
                                                class="block text-sm font-medium leading-6 text-gray-900">No.
                                                STR</label>
                                            <div class="mt-2">
                                                <input id="str" name="str" type="text" autocomplete="str" required
                                                    class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                                            </div>
                                        </div>
                                        <div style="margin-bottom: 15px;">
                                            <label for="nama"
                                                class="block text-sm font-medium leading-6 text-gray-900">Nama
                                                Lengkap</label>
                                            <div class="mt-2">
                                                <input id="nama" name="nama" type="text" autocomplete="nama" required
                                                    class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3" style="margin-bottom: 15px;">
                                            <div>
                                                <label for="profesi"
                                                    class="block text-sm font-medium leading-6 text-gray-900">Profesi</label>
                                                <div class="mt-2">
                                                    <select id="profesi" name="profesi" autocomplete="profesi"
                                                        class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                                                        <?php foreach ($listSpesialis as $spesialis) : ?>
                                                        <option value="<?=$spesialis["id"]?>">
                                                            <?=$spesialis["profesi"]?>
                                                        </option>
                                                        <?php endforeach; ?>

                                                    </select>
                                                </div>
                                            </div>
                                            <div>
                                                <label for="pengalaman"
                                                    class="block text-sm font-medium leading-6 text-gray-900">Pengalaman
                                                    (tahun)</label>
                                                <div class="mt-2">
                                                    <input id="pengalaman" name="pengalaman" type="number" required
                                                        autocomplete="pengalaman"
                                                        class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3" style="margin-bottom: 15px;">
                                            <div>
                                                <label for="rating"
                                                    class="block text-sm font-medium leading-6 text-gray-900">Rating</label>
                                                <div class="mt-2">
                                                    <input id="rating" name="rating" type="text" autocomplete="rating"
                                                        class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                                                </div>
                                            </div>
                                            <div>
                                                <label for="mahir"
                                                    class="block text-sm font-medium leading-6 text-gray-900">Mahir
                                                    mengenai</label>
                                                <div class="mt-2">
                                                    <input id="mahir" name="mahir" type="text" autocomplete="mahir"
                                                        class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3" style="margin-bottom: 15px;">
                                            <div>
                                                <label for="harga"
                                                    class="block text-sm font-medium leading-6 text-gray-900">Harga<span
                                                        class="text-xs">/sesi</span></label>
                                                <div class="mt-2">
                                                    <input id="harga" name="harga" type="number" autocomplete="harga"
                                                        required
                                                        class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                                                </div>
                                            </div>
                                            <div>
                                                <label for="alumni"
                                                    class="block text-sm font-medium leading-6 text-gray-900">Alumni</label>
                                                <div class="mt-2">
                                                    <input id="alumni" name="alumni" type="text" autocomplete="alumni"
                                                        required
                                                        class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3" style="margin-bottom: 15px;">
                                            <div>
                                                <label for="praktik"
                                                    class="block text-sm font-medium leading-6 text-gray-900">Praktik</label>
                                                <div class="mt-2">
                                                    <input id="praktik" name="praktik" type="text" required
                                                        autocomplete="praktik"
                                                        class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                                                </div>
                                            </div>
                                            <div>
                                                <label for="gambar"
                                                    class="block text-sm font-medium leading-6 text-gray-900">Gambar</label>
                                                <div class="mt-2">
                                                    <input id="gambar" name="gambar" type="file" required
                                                        class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                                                </div>
                                            </div>
                                        </div>
                                        <div style="margin-bottom: 15px;">
                                            <label for="email"
                                                class="block text-sm font-medium leading-6 text-gray-900">Alamat
                                                Email</label>
                                            <div class="mt-2">
                                                <input id="email" name="email" type="email" autocomplete="email"
                                                    required
                                                    class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                                            </div>
                                        </div>

                                        <div>
                                            <div style="margin-bottom: 15px;">

                                                <label for="password"
                                                    class="block text-sm font-medium leading-6 text-gray-900">Password</label>

                                                <div class="mt-2">
                                                    <input id="password" name="password" type="password" required
                                                        autocomplete="current-password"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                                                </div>
                                            </div>


                                            <div style="margin-bottom: 15px;">
                                                <label for="password_confirmation"
                                                    class="block text-sm font-medium leading-6 text-gray-900">Konfirmasi
                                                    Password</label>
                                                <div class="mt-2">
                                                    <input id="password_confirmation" name="password_confirmation"
                                                        required type="password"
                                                        autocomplete="new-password-confirmation"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                                                </div>
                                            </div>
                                        </div>



                                        <div class="mt-4">
                                            <button type="submit" name="tambahDokter"
                                                class="flex w-full justify-center rounded-md bg-pink-700 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-pink-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-pink-700">Tambah</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-6">
            <div class="outer-wrapper shadow-md sm:rounded-lg">
                <div class="table-wrapper">
                    <table class="text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    #
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    No. STR
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Nama
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Profesi
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Pengalaman
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Rating
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Mahir
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Harga
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Alumni
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Praktik
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Gambar
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Email
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Password
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Hapus
                                    <!-- <span class="sr-only">Hapus</span> -->
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($doctors as $doctor) : ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th class="px-6 py-4 ">
                                    <?= $i; ?>
                                </th>
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <?= $doctor["str"]; ?>
                                </th>
                                <td class="px-6 py-4">
                                    <?= $doctor["nama"]; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?= $doctor["profesi"]; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?= $doctor["pengalaman"]; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?= $doctor["rating"]; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?= $doctor["mahir"]; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?= $doctor["harga"]; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?= $doctor["alumni"]; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?= $doctor["praktik"]; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <img src="dist/img/<?= $doctor["gambar"]; ?>">
                                </td>
                                <td class="px-6 py-4">
                                    <?= $doctor["email"]; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?= $doctor["password"]; ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="hapusDokter.php?id=<?= $doctor["id"]; ?>"
                                        onclick="return confirm('Apakah Anda Yakin?');"
                                        class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500">
                                        <i class="fa-solid fa-times text-lg"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>


    </div>
    <footer class="mt-18 bg-primary">
        <div
            class="container mx-auto text-white border-slate-200 p-8 text-center text-sm dark:border-slate-900 md:flex-row md:px-12">
            ©
            2024
            Umar Andika. All rights reserved.
        </div>
    </footer>
</body>

</html>