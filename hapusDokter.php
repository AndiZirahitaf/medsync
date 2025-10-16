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

require 'connection/koneksi.php';

if (isset($_GET["id"])) {
	$id = $_GET["id"];
} else {
	header("Location: dashboard_admin.php");
	exit;
}

if( hapusDokter($id) > 0 ) {
	echo "
		<script>
			alert('data berhasil dihapus!');
			document.location.href = 'dashboard_admin.php';
		</script>
	";
} else {
	echo "
		<script>
			alert('data gagal ditambahkan!');
			document.location.href = 'dashboard_admin.php';
		</script>
	";
}

?>