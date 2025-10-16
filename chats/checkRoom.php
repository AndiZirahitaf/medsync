<?php
session_start();
include '../connection/koneksi.php';

$kode_unik = $_SESSION['kode_unik'];
$sql = "SELECT status FROM consultations WHERE kode_unik = '$kode_unik'";
$query = mysqli_query($conn, $sql);

if ($query) {
    $row = mysqli_fetch_assoc($query);
    $response = array('status' => $row['status']);
    echo json_encode($response);
} else {
    $response = array('status' => 'error');
    echo json_encode($response);
}
?>