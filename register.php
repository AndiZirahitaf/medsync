<?php
session_start();
require 'connection/koneksi.php';

if (isset($_COOKIE['login']) && $_COOKIE['login'] == 'true') {
    $_SESSION['login'] = true;
}

if (isset($_SESSION["login"])) {
    header("Location: index.php");
	exit;
}


if( isset($_POST["register"]) ) {
	if ($_POST["password"] !== $_POST["password_confirmation"]) {
        echo "
            <script>
                alert('konfirmasi password tidak sesuai');
            </script>
        ";
    } else {
        // cek apakah data berhasil di tambahkan atau tidak
        if( register($_POST) > 0 ) {
            echo "
                <script>
                    alert('berhasil daftar!');
                    document.location.href = 'index.php';
                </script>
            ";
        } else {
            echo "
                <script>
                    alert('gagal daftar!');
                    document.location.href = 'index.php';
                </script>
            ";
        }
    }
	
}

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
    <!-- <header class="absolute left-0 top-0 z-10 flex w-full items-center bg-transparent">
        <div class="container">
            <div class="relative flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fa-solid fa-heart-pulse text-2xl text-pink-700"></i>
                    <div class="px-4">
                        <a href="#homeSec" class="block py-6 text-lg font-bold text-primary">MedSync</a>
                    </div>
                </div>
                <div class="flex items-center px-4">
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
                            <a href="#bookingSec"
                                class="ml-5 rounded-full bg-primary px-8 py-3 text-base font-semibold text-white transition duration-300 ease-in-out hover:opacity-80 hover:shadow-lg">Masuk</a>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header> -->
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm mt-5">
            <i class="fa-solid fa-heart-pulse text-6xl text-pink-700"></i>
            <h2 class="mt-3 text-2xl font-bold leading-9 tracking-tight text-gray-900">Daftar MedSync</h2>
            <div class="text-sm" style="color: #757575;">Kami memberi pelayanan konsultasi kesehatan terbaik untuk
                Anda.
            </div>
        </div>
        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-6" action="" method="POST">
                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Alamat Email</label>
                    <div class="mt-2">
                        <input id="email" name="email" type="email" autocomplete="email" required
                            class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Nama Lengkap</label>
                    <div class="mt-2">
                        <input id="nama" name="nama" type="text" autocomplete="nama" required
                            class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="gender" class="block text-sm font-medium leading-6 text-gray-900">Jenis Kelamin</label>
                    <div class="flex my-auto mt-2">
                        <div class="flex items-center mr-5">
                            <input id="gender-male" name="kelamin" type="radio" value="l"
                                class="form-radio h-4 w-4 text-pink-700">
                            <label for="gender-male" class="ml-2 text-sm text-gray-900">Laki-Laki</label>
                        </div>
                        <div class="flex items-center">
                            <input id="gender-female" name="kelamin" type="radio" value="p"
                                class="form-radio h-4 w-4 text-pink-700">
                            <label for="gender-female" class="ml-2 text-sm text-gray-900">Perempuan</label>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="birthdate" class="block text-sm font-medium leading-6 text-gray-900">Tanggal
                        Lahir</label>
                    <div class="mt-2">
                        <input id="birthdate" name="tgl_lahir" type="date" required
                            class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="flex gap-3">
                    <div>
                        <label for="weight" class="block text-sm font-medium leading-6 text-gray-900">Berat
                            (kg)</label>
                        <div class="mt-2">
                            <input id="weight" name="bb" type="number" required
                                class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div>
                        <label for="height" class="block text-sm font-medium leading-6 text-gray-900">Tinggi
                            (cm)</label>
                        <div class="mt-2">
                            <input id="height" name="tb" type="number" required
                                class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Nomor
                            Telepon</label>
                        <div class="mt-2">
                            <input id="phone" name="no_tlp" type="tel" required
                                class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                </div>



                <div>

                    <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>

                    <div class="mt-2">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <label for="password_confirmation"
                        class="block text-sm font-medium leading-6 text-gray-900">Konfirmasi Password</label>
                    <div class="mt-2">
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            autocomplete="current-password" required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="remember_me" name="remember_me" type="checkbox"
                        class="h-4 w-4 text-pink-700 focus:ring-pink-700 border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                        Ingat Saya
                    </label>
                </div>

                <div>
                    <button type="submit" name="register"
                        class="flex w-full justify-center rounded-md bg-pink-700 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-pink-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-pink-700">Daftar</button>
                </div>
                <div>
                    <p class="mt-2 text-center text-sm text-gray-600">
                        Sudah memiliki akun? <a href="login.php"
                            class="font-medium text-pink-700 hover:text-indigo-500">Masuk</a>
                    </p>
                </div>
            </form>

        </div>
    </div>



</body>

</html>