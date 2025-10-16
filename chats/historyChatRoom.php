<?php 
session_start();
if (isset($_COOKIE['unique_id'])) {
    $_SESSION['unique_id'] = $_COOKIE['unique_id'];
    $_SESSION['userType'] = $_COOKIE['userType'];
    $_SESSION['id'] = $_COOKIE['id'];
    $_SESSION['login'] = true;
}
if( !isset($_SESSION["login"]) ) {
    header("Location: ../login.php");
    exit;
}


require '../connection/koneksi.php';

if (isset($_GET['code'])) {
    $code = $_GET['code'];
    $konsultasi = query("SELECT * FROM consultations WHERE kode_unik = '$code'")[0];
    if (count($konsultasi) == 0) {
        echo "<script>alert('Room chat tidak ditemukan!');</script>";
        header("Location: index.php");
        exit;
    } 
    

    
    $patient_id = $konsultasi['patient_id'];
    $doctor_id = $konsultasi['doctor_id'];
 
} else {
    header("Location: index.php");
    exit;
}

$code = $_GET['code'];  

$_SESSION["kode_unik"] = $code;

$doctor = query("SELECT * FROM doctors WHERE id = '$doctor_id'")[0];
$patient = query("SELECT * FROM patients WHERE id = '$patient_id'")[0];

if ($_SESSION["userType"] == "patient") {
    $incoming = $doctor;
    $v = true;
    $str = "";
    
} else if ($_SESSION["userType"] == "doctor") {
    $incoming = $patient;
    $v = false;
    $str = "Dokter";
    
}

$listSpesialis = query("SELECT * FROM profesi_spesialis");

if ($konsultasi['status'] == "active") {
        echo "<script>alert('Room chat masih aktif! Silahkan akses dari Daftar Konsultasi!');</script>";
        header("Location: ../dashboard".$str.".php");
        exit;
    }
    
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="stylesheet" href="../dist/css/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
    <script src="https://kit.fontawesome.com/2d99d9dc75.js" crossorigin="anonymous"></script>
    <title>MedSync</title>
    <link rel="icon" href="../dist/img/favicon.ico">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        text-decoration: none;
    }

    body {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        /* background: #f7f7f7; */
        /* padding: 0 100px; */
    }

    .wrapper {
        background: #fff;
        max-width: 700px;
        margin-top: 50px;
        width: 100%;
        border-radius: 16px;
        box-shadow: 0 0 128px 0 rgba(0, 0, 0, 0.1),
            0 32px 64px -48px rgba(0, 0, 0, 0.5);
    }

    /* Login & Signup Form CSS Start */
    .form {
        padding: 25px 30px;
    }

    .form header {
        font-size: 25px;
        font-weight: 600;
        padding-bottom: 10px;
        border-bottom: 1px solid #e6e6e6;
    }

    .form form {
        margin: 20px 0;
    }

    .form form .error-text {
        color: #721c24;
        padding: 8px 10px;
        text-align: center;
        border-radius: 5px;
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        margin-bottom: 10px;
        display: none;
    }

    .form form .name-details {
        display: flex;
    }

    .form .name-details .field:first-child {
        margin-right: 10px;
    }

    .form .name-details .field:last-child {
        margin-left: 10px;
    }

    .form form .field {
        display: flex;
        margin-bottom: 10px;
        flex-direction: column;
        position: relative;
    }

    .form form .field label {
        margin-bottom: 2px;
    }

    .form form .input input {
        height: 40px;
        width: 100%;
        font-size: 16px;
        padding: 0 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .form form .field input {
        outline: none;
    }

    .form form .image input {
        font-size: 17px;
    }

    .form form .button input {
        height: 45px;
        border: none;
        color: #fff;
        font-size: 17px;
        background: #333;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 13px;
    }

    .form form .field i {
        position: absolute;
        right: 15px;
        top: 70%;
        color: #ccc;
        cursor: pointer;
        transform: translateY(-50%);
    }

    .form form .field i.active::before {
        color: #333;
        content: "\f070";
    }

    .form .link {
        text-align: center;
        margin: 10px 0;
        font-size: 17px;
    }

    .form .link a {
        color: #333;
    }

    .form .link a:hover {
        text-decoration: underline;
    }

    /* Users List CSS Start */
    .users {
        padding: 25px 30px;
    }

    .users header,
    .users-list a {
        display: flex;
        align-items: center;
        padding-bottom: 20px;
        border-bottom: 1px solid #e6e6e6;
        justify-content: space-between;
    }

    .wrapper img {
        object-fit: cover;
        border-radius: 50%;
    }

    .users header img {
        height: 50px;
        width: 50px;
    }

    :is(.users, .users-list) .content {
        display: flex;
        align-items: center;
    }

    :is(.users, .users-list) .content .details {
        color: #000;
        margin-left: 20px;
    }

    :is(.users, .users-list) .details span {
        font-size: 18px;
        font-weight: 500;
    }

    .users header .logout {
        display: block;
        background: #333;
        color: #fff;
        outline: none;
        border: none;
        padding: 7px 15px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 17px;
    }

    .users .search {
        margin: 20px 0;
        display: flex;
        position: relative;
        align-items: center;
        justify-content: space-between;
    }

    .users .search .text {
        font-size: 18px;
    }

    .users .search input {
        position: absolute;
        height: 42px;
        width: calc(100% - 50px);
        font-size: 16px;
        padding: 0 13px;
        border: 1px solid #e6e6e6;
        outline: none;
        border-radius: 5px 0 0 5px;
        opacity: 0;
        pointer-events: none;
        transition: all 0.2s ease;
    }

    .users .search input.show {
        opacity: 1;
        pointer-events: auto;
    }

    .users .search button {
        position: relative;
        z-index: 1;
        width: 47px;
        height: 42px;
        font-size: 17px;
        cursor: pointer;
        border: none;
        background: #fff;
        color: #333;
        outline: none;
        border-radius: 0 5px 5px 0;
        transition: all 0.2s ease;
    }

    .users .search button.active {
        background: #333;
        color: #fff;
    }

    .search button.active i::before {
        content: "\f00d";
    }

    .users-list {
        max-height: 350px;
        overflow-y: auto;
    }

    :is(.users-list, .chat-box)::-webkit-scrollbar {
        width: 0px;
    }

    .users-list a {
        padding-bottom: 10px;
        margin-bottom: 15px;
        padding-right: 15px;
        border-bottom-color: #f1f1f1;
    }

    .users-list a:last-child {
        margin-bottom: 0px;
        border-bottom: none;
    }

    .users-list a img {
        height: 40px;
        width: 40px;
    }

    .users-list a .details p {
        color: #67676a;
    }

    .users-list a .status-dot {
        font-size: 12px;
        color: #468669;
        padding-left: 10px;
    }

    .users-list a .status-dot.offline {
        color: #ccc;
    }

    /* Chat Area CSS Start */
    .chat-area header {
        display: flex;
        align-items: center;
        padding: 18px 30px;
    }

    .chat-area header .back-icon {
        color: #333;
        font-size: 18px;
    }

    .chat-area header img {
        height: 45px;
        width: 45px;
        margin-right: 20px;
    }

    .chat-area header .details span {
        font-size: 17px;
        font-weight: 500;
    }

    .chat-box {
        position: relative;
        min-height: 450px;
        max-height: 450px;
        overflow-y: auto;
        padding: 10px 30px 20px 30px;
        background: #f7f7f7;
        box-shadow: inset 0 32px 32px -32px rgb(0 0 0 / 5%),
            inset 0 -32px 32px -32px rgb(0 0 0 / 5%);
    }

    .chat-box .text {
        position: absolute;
        top: 45%;
        left: 50%;
        width: calc(100% - 50px);
        text-align: center;
        transform: translate(-50%, -50%);
    }

    .chat-box .chat {
        margin: 15px 0;
    }

    .chat-box .chat p {
        word-wrap: break-word;
        padding: 8px 16px;
        box-shadow: 0 0 32px rgb(0 0 0 / 8%), 0rem 16px 16px -16px rgb(0 0 0 / 10%);
    }

    .chat-box .outgoing {
        display: flex;
    }

    .chat-box .outgoing .details {
        margin-left: auto;
        max-width: calc(100% - 130px);
    }

    .outgoing .details p {
        background: #333;
        color: #fff;
        border-radius: 18px 18px 0 18px;
    }

    .chat-box .incoming {
        display: flex;
        align-items: flex-end;
    }

    .chat-box .incoming img {
        height: 35px;
        width: 35px;
    }

    .chat-box .incoming .details {
        margin-right: auto;
        margin-left: 10px;
        max-width: calc(100% - 130px);
    }

    .incoming .details p {
        background: #fff;
        color: #333;
        border-radius: 18px 18px 18px 0;
    }

    .typing-area {
        padding: 18px 30px;
        display: flex;
        justify-content: space-between;
    }

    .typing-area input {
        height: 45px;
        width: calc(100% - 58px);
        font-size: 16px;
        padding: 0 13px;
        border: 1px solid #e6e6e6;
        outline: none;
        border-radius: 5px 0 0 5px;
    }

    .typing-area button {
        color: #fff;
        width: 55px;
        border: none;
        outline: none;
        background: #333;
        font-size: 19px;
        cursor: pointer;
        opacity: 0.7;
        pointer-events: none;
        border-radius: 0 5px 5px 0;
        transition: all 0.3s ease;
    }

    .typing-area button.active {
        opacity: 1;
        pointer-events: auto;
    }

    /* Responive media query */
    @media screen and (max-width: 450px) {

        .form,
        .users {
            padding: 20px;
        }

        .form header {
            text-align: center;
        }

        .form form .name-details {
            flex-direction: column;
        }

        .form .name-details .field:first-child {
            margin-right: 0px;
        }

        .form .name-details .field:last-child {
            margin-left: 0px;
        }

        .users header img {
            height: 45px;
            width: 45px;
        }

        .users header .logout {
            padding: 6px 10px;
            font-size: 16px;
        }

        :is(.users, .users-list) .content .details {
            margin-left: 15px;
        }

        .users-list a {
            padding-right: 10px;
        }

        .chat-area header {
            padding: 15px 20px;
        }

        .chat-box {
            min-height: 400px;
            padding: 10px 15px 15px 20px;
        }

        .chat-box .chat p {
            font-size: 15px;
        }

        .chat-box .outogoing .details {
            max-width: 230px;
        }

        .chat-box .incoming .details {
            max-width: 265px;
        }

        .incoming .details img {
            height: 30px;
            width: 30px;
        }

        .chat-area form {
            padding: 20px;
        }

        .chat-area form input {
            height: 40px;
            width: calc(100% - 48px);
        }

        .chat-area form button {
            width: 45px;
        }
    }
    </style>

</head>

<body class="body">

    <header class="absolute left-0 top-0 z-10 flex w-full items-center bg-transparent">
        <div class="container">
            <div class="relative flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fa-solid fa-heart-pulse text-2xl text-pink-700"></i>
                    <div class="px-4">
                        <a href="../index.php" class="block py-6 text-lg font-bold text-primary">MedSync</a>
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
                            <a href="../logout.php"
                                class="ml-5 rounded-md bg-white px-4 py-2 text-base font-semibold text-primary transition duration-300 ease-in-out ">Keluar</a>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="py-4 flex items-center gap-3 self-start">
                <a href="../dashboard<?=$str?>.php" class="text-primary text-base"> <i class="fas fa-arrow-left"></i>
                </a>
                <p class="text-gray-600 font-medium">Kembali</p>
            </div>
        </div>

    </header>
    <div class="wrapper ">
        <section class="chat-area">
            <header x-data="{ isOpen: false }" class="flex justify-between">
                <?php if ($v == false) {?>
                <div class="details ">
                    <p class="font-semibold text-xl" style="margin-bottom: 5px;"><?php echo $patient['nama']?></p>
                    <a data-bs-toggle="modal"
                        data-bs-target="#detailModal-<?=$patient["id"] ?>-<?=$patient["nama"] ?>-<?=$patient["kelamin"] ?>-<?=$patient["tb"] ?>-<?=$patient["bb"] ?>-<?=$patient["tgl_lahir"] ?>"
                        @click="isOpen = !isOpen"
                        class="cursor-pointer text-white text-sm bg-primary py-1 px-2 rounded-md">Lihat
                        Detail Chat</a>

                    <div x-show="isOpen" class="relative z-10 cursor-default" aria-labelledby="docModalLabel"
                        role="dialog" aria-modal="true"
                        id="detailModal-<?=$patient["id"] ?>-<?=$patient["nama"] ?>-<?=$patient["kelamin"] ?>-<?=$patient["tb"] ?>-<?=$patient["bb"] ?>-<?=$patient["tgl_lahir"] ?>">
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

                                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                        <div class="flex justify-between items-start">
                                            <h3 class="text-xl font-semibold text-gray-900 "
                                                style="margin-bottom: 15px;">
                                                Detail Pasien</h3>

                                            <button @click="isOpen = !isOpen"
                                                class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500">
                                                <i class="fa-solid fa-times text-lg"></i>
                                            </button>
                                        </div>

                                        <div class="flex text-sm">
                                            <div>
                                                <p class="text-gray-500">Nama</p>
                                                <p class="text-gray-500">Jenis Kelamin</p>
                                                <p class="text-gray-500">Usia</p>
                                                <p class="text-gray-500">Tinggi Badan</p>
                                                <p class="text-gray-500">Berat Badan</p>
                                            </div>
                                            <div class="flex">
                                                <div style="margin-left: 10px;" class="text-gray-500">
                                                    <p>:</p>
                                                    <p>:</p>
                                                    <p>:</p>
                                                    <p>:</p>
                                                    <p>:</p>
                                                </div>
                                                <div class="font-semibold" style="margin-left: 15px;">
                                                    <p><?=$patient['nama']?></p>
                                                    <p><?= $patient["kelamin"] == "l" ? "Laki-Laki" : "Perempuan"?></p>
                                                    <p><?php
                                                    $birthDate = $patient['tgl_lahir'];
                                                    $age = date_diff(date_create($birthDate), date_create('today'))->y;
                                                    echo $age." tahun";
                                                    ?></p>
                                                    <p><?=$patient['tb']?> cm</p>
                                                    <p><?=$patient['bb']?> kg</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <form action="" method="post">
                                                <input type="text" value="<?=$code?>" name="consCode" hidden>
                                                <button type="submit" name="endChat"
                                                    class="flex w-full justify-center rounded-md bg-pink-700 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-pink-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-pink-700">Selesaikan
                                                    Chat</button>
                                            </form>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col justify-end items-end font-semibold">
                    <p class="text-gray-500 font-normal"><?= $konsultasi['tanggal']; ?>
                    </p>
                    <p>
                        <?= date('H:i', strtotime($konsultasi["time_start"])) ?>-<?= date('H:i', strtotime($konsultasi["time_end"])) ?>
                    </p>
                </div>
                <?php } else { ?>
                <div class="details flex justify-between w-full">

                    <div class="flex items-center">
                        <!-- <img src="../dist/img/<?= $doctor['gambar']?>" alt="doctor" class="rounded-full h-10 w-10"> -->
                        <!-- <div class="w-10 h-10 rounded-full"
                            style="background-image: url('dist/img/<?=$doctor["gambar"]?>'); background-size: cover; background-position: center;">
                        </div> -->
                        <img src="../dist/img/<?= $doctor['gambar']?>" alt="" srcset="">
                        <div>
                            <p class="font-semibold text-xl">
                                <?= $doctor['nama']?></p>
                            <p style="margin-bottom: 5px;">
                                <?php
                        for ($i = 0; $i < count($listSpesialis); $i++) {

                            if ($listSpesialis[$i]["id"] == $doctor["profesi"]) {
                                echo $listSpesialis[$i]["profesi"];
                            }

                        }
                         ?></p>
                        </div>

                    </div>
                    <div class="flex flex-col font-semibold items-end">
                        <p class="text-gray-500 font-normal"><?= $konsultasi['tanggal']; ?>
                        </p>
                        <p>
                            <?= date('H:i', strtotime($konsultasi["time_start"])) ?>-<?= date('H:i', strtotime($konsultasi["time_end"])) ?>
                        </p>
                    </div>
                </div>
                <?php } ?>

            </header>
            <div class="chat-box">
                <input class="kode_unik" type="text" name="kodeUnik" hidden value="<?=$konsultasi["kode_unik"]?>">

            </div>

        </section>

        <script type="text/javascript">
        const incoming_id = <?= $incoming["unique_id"]; ?>;
        </script>
        <script src="../dist/js/chatHistory.js">

        </script>


    </div>

</body>

</html>