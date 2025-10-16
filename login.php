<?php 
session_start();
require 'connection/koneksi.php';

// if (isset($_COOKIE['id']) && isset($_COOKIE['key'])) {
// 	$id = $_COOKIE['id'];
// 	$key = $_COOKIE['key'];

// 	$result = mysqli_query($conn, "SELECT email FROM patients WHERE id = $id");
// 	$row = mysqli_fetch_assoc($result);


//     if ($key === hash('sha256', $row['email'])) {
// 		$_SESSION['login'] = true;
// 	}
// }

if( isset($_SESSION["login"]) ) {
	header("Location: index.php");
	exit;
}

if( isset($_POST["login"]) ) {

	 if( login($_POST) > 0 ) {
            echo "
                <script>
                    alert('berhasil masuk!');
                    document.location.href = 'index.php';
                </script>
            ";
        } else {
            echo "
                <script>
                    alert('gagal masuk!');
                    document.location.href = 'index.php';
                </script>
            ";
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

    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <div class="mx-auto h-10 w-auto flex justify-center">
                <i class="fa-solid fa-heart-pulse text-6xl text-pink-700"></i>
            </div>

            <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Masuk ke MedSync
            </h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <?php
            if (isset($error)) {
                echo '<script>alert("Email atau password salah")</script>';
            }
            ?>
            <form class="space-y-6" action="#" method="POST">
                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Alamat Email</label>
                    <div class="mt-2">
                        <input id="email" name="email" type="email" autocomplete="email" required
                            class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
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
                    <label for="userType" class="block text-sm font-medium leading-6 text-gray-900">Jenis User</label>
                    <div class="mt-2">
                        <select id="userType" name="userType" required
                            class="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pink-700 sm:text-sm sm:leading-6">
                            <option value="patient">Pasien</option>
                            <option value="doctor">Dokter</option>
                            <option value="admin">Admin</option>
                        </select>
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
                    <button type="submit" name="login"
                        class="flex w-full justify-center rounded-md bg-pink-700 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-pink-700">Masuk</button>
                </div>

                <div>
                    <p class="mt-2 text-center text-sm text-gray-600">
                        Belum memiliki akun? <a href="register.php"
                            class="font-medium text-pink-700 hover:text-indigo-500">Daftar</a>
                    </p>
                </div>
            </form>

        </div>
    </div>

</body>

</html>