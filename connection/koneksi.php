<?php 
// koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "projek");


function query($query) {
	global $conn;
	$result = mysqli_query($conn, $query);
	$rows = [];
	while( $row = mysqli_fetch_assoc($result) ) {
		$rows[] = $row;
	}
	return $rows;
}

function register($data) {
    global $conn;

    $email = strtolower(stripslashes($data["email"]));
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $nama = htmlspecialchars($data["nama"]); 
    $kelamin = htmlspecialchars($data["kelamin"]);
    $tgl_lahir = htmlspecialchars($data["tgl_lahir"]);
    $bb = htmlspecialchars($data["bb"]);
    $tb = htmlspecialchars($data["tb"]);
    $no_tlp = htmlspecialchars($data["no_tlp"]);

    
    while (true) {
        $unique_id = random_int(100000, 999999);
        $checkUniqueID = query("SELECT unique_id FROM patients WHERE unique_id = '$unique_id'");
        if (count($checkUniqueID) == 0) {
            break;
        }
    }
    

    // cek email sudah ada atau belum
    $result = mysqli_query($conn, "SELECT email FROM patients WHERE email = '$email'");
    if( mysqli_fetch_assoc($result) ) {
        echo "<script>
                alert('email sudah terdaftar');
            </script>";
        return false;
    }

    // enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // tambahkan userbaru ke database
    mysqli_query($conn, "INSERT INTO patients VALUES('', '$email', '$nama', '$kelamin', '$tgl_lahir', '$bb', '$tb', '$no_tlp', '$password', '$unique_id')");

    $_SESSION["login"] = true;
    $_SESSION["userType"] = "patient";

    $_SESSION["unique_id"] = $unique_id;

    $result = mysqli_query($conn, "SELECT * FROM patients WHERE email = '$email'");
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION["id"] = $row["id"];
    }

    if( isset($data["remember_me"]) ) {
                // buat cookie
                setcookie('id', $row['id'], time()+3600);
                setcookie('unique_id', $row['unique_id'], time()+3600);
                setcookie('userType', "patient", time()+3600);
                
     }
            
    return mysqli_affected_rows($conn);
}

function login($data) {
    global $conn;

    $email = $data["email"];
    $password = $data["password"];
    $userType = $data["userType"];

    if ($userType == "patient") {
        $result = mysqli_query($conn, "SELECT * FROM patients WHERE email = '$email'");
        $_SESSION["userType"] = "patient";
    } else if ($userType == "doctor") {
        $result = mysqli_query($conn, "SELECT * FROM doctors WHERE email = '$email'");
        $_SESSION["userType"] = "doctor";
    } else if ($userType == "admin") {
        $result = mysqli_query($conn, "SELECT * FROM admin WHERE email = '$email'");
        $_SESSION["userType"] = "admin";
    } else {
        return false;
    }

    // cek email
    if( mysqli_num_rows($result) === 1 ) {
        // cek password
        $row = mysqli_fetch_assoc($result);
        if( password_verify($password, $row["password"]) ) {
            $_SESSION["login"] = true;

            $_SESSION["id"] = $row["id"];
            $_SESSION["unique_id"] = $row["unique_id"];
            
            // cek remember me
            if( isset($data["remember_me"]) ) {
                // buat cookie
                setcookie('id', $row['id'], time()+60);
                setcookie('unique_id', $row['unique_id'], time()+60);
                setcookie('userType', $userType, time()+60);
                
            }
            // header("Location: index.php");
            // exit;
        } else{
            return false;
        }
    } else{
        return false;
    }
    return mysqli_affected_rows($conn);
}


function ubahProfilPasien($data) {
    global $conn;

    $id = $_SESSION["id"];
    $email = strtolower(stripslashes($data["email"]));
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $new_password = mysqli_real_escape_string($conn, $data["new_password"]);
    $new_password_confirmation = mysqli_real_escape_string($conn, $data["new_password_confirmation"]);
    $nama = htmlspecialchars($data["nama"]); 
    $kelamin = htmlspecialchars($data["kelamin"]);
    $tgl_lahir = htmlspecialchars($data["tgl_lahir"]);
    $bb = htmlspecialchars($data["bb"]);
    $tb = htmlspecialchars($data["tb"]);
    $no_tlp = htmlspecialchars($data["no_tlp"]);

    $result = mysqli_query($conn, "SELECT * FROM patients WHERE id = '$id'");
    $row = mysqli_fetch_assoc($result);

    if ($password != "" && $new_password != "" && $new_password_confirmation != "") {
        if (password_verify($password, $row["password"] )) {
            if ($new_password != $new_password_confirmation) {
                echo '<script>alert("Password baru tidak sama");</script>';
                return false;
            } else {
                $new_password = password_hash($new_password, PASSWORD_DEFAULT);
                mysqli_query($conn, "UPDATE patients SET email = '$email', nama = '$nama', kelamin = '$kelamin', tgl_lahir =
                '$tgl_lahir', bb = '$bb', tb = '$tb', no_tlp = '$no_tlp', password = '$new_password' WHERE id = '$id'");
            }
        } else {
            echo '<script>alert("Password lama salah");</script>';
            return false;
        }
    } else if (!($password == "" && $new_password == "" && $new_password_confirmation == "")) {
        echo '<script>alert("Password tidak boleh kosong");</script>';
        return false;
    } else {
        mysqli_query($conn, "UPDATE patients SET email = '$email', nama = '$nama', kelamin = '$kelamin', tgl_lahir =
        '$tgl_lahir', bb = '$bb', tb = '$tb', no_tlp = '$no_tlp' WHERE id = '$id'");
    }

    return mysqli_affected_rows($conn);
}

function ubahFotoProfilDokter($data) {
    global $conn;

    $id = $_SESSION["id"];
    $gambar = uploadGambar();
    if (!$gambar) {
        return false;
    }

    $result = mysqli_query($conn, "SELECT * FROM doctors WHERE id = '$id'");
    $row = mysqli_fetch_assoc($result);

    // Delete the old image
    $old_image = $row['gambar'];
    unlink('dist/img/' . $old_image);

    mysqli_query($conn, "UPDATE doctors SET gambar = '$gambar' WHERE id = '$id'");

    return mysqli_affected_rows($conn);
}

function ubahProfilDokter($data) {
    global $conn;

    $id = $_SESSION["id"];
    $str = htmlspecialchars($data["str"]); 
    $nama = htmlspecialchars($data["nama"]);
    $profesi = htmlspecialchars($data["profesi"]);
    $pengalaman = htmlspecialchars($data["pengalaman"]);
    $rating = htmlspecialchars($data["rating"]);
    $mahir = htmlspecialchars($data["mahir"]);
    $harga = htmlspecialchars($data["harga"]);
    $alumni = htmlspecialchars($data["alumni"]);
    $praktik = htmlspecialchars($data["praktik"]);
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $new_password = mysqli_real_escape_string($conn, $data["new_password"]);
    $new_password_confirmation = mysqli_real_escape_string($conn, $data["new_password_confirmation"]);
    $email = strtolower(stripslashes($data["email"]));
    // $gambar_temp = mysqli_real_escape_string($conn, $data["gambar"]);

    $result = mysqli_query($conn, "SELECT * FROM doctors WHERE id = '$id'");
    $row = mysqli_fetch_assoc($result);
    if ($password != "" && $new_password != "" && $new_password_confirmation != "") {
        if (password_verify($password, $row["password"] )) {
            if ($new_password != $new_password_confirmation) {
                echo '<script>alert("Password baru tidak sama");</script>';
                return false;
            } else {
                $new_password = password_hash($new_password, PASSWORD_DEFAULT);
             
                mysqli_query($conn, "UPDATE doctors SET str = '$str', nama = '$nama', profesi = '$profesi', pengalaman =
                '$pengalaman', rating = '$rating', mahir = '$mahir', harga = '$harga', alumni = '$alumni', praktik = '$praktik', email = '$email', password = '$new_password' WHERE id = '$id'");
            
                
            }
        } else {
            echo '<script>alert("Password lama salah");</script>';
            return false;
        }
    } else if (!($password == "" && $new_password == "" && $new_password_confirmation == "")) {
        echo '<script>alert("Password tidak boleh kosong");</script>';
        return false;
    } else {
   
            mysqli_query($conn, "UPDATE doctors SET str = '$str', nama = '$nama', profesi = '$profesi', pengalaman =
            '$pengalaman', rating = '$rating', mahir = '$mahir', harga = '$harga', alumni = '$alumni', praktik = '$praktik', email = '$email' WHERE id = '$id'");
       
    }

    return mysqli_affected_rows($conn);
}
function hapusDokter($id) {
	global $conn;
	mysqli_query($conn, "DELETE FROM doctors WHERE id = $id");
	return mysqli_affected_rows($conn);
}

function tambahDokter($data) {
    global $conn;

    $str = htmlspecialchars($data["str"]); 
    $nama = htmlspecialchars($data["nama"]);
    $profesi = htmlspecialchars($data["profesi"]);
    $pengalaman = htmlspecialchars($data["pengalaman"]);
    $rating = htmlspecialchars($data["rating"]);
    $mahir = htmlspecialchars($data["mahir"]);
    $harga = htmlspecialchars($data["harga"]);
    $alumni = htmlspecialchars($data["alumni"]);
    $praktik = htmlspecialchars($data["praktik"]);
    // $gambar = htmlspecialchars($data["gambar"]);
    $email = strtolower(stripslashes($data["email"]));
    
    while (true) {
        $unique_id = random_int(100000, 999999);
        $checkUniqueID = query("SELECT unique_id FROM doctors WHERE unique_id = '$unique_id'");
        if (count($checkUniqueID) == 0) {
            break;
        }
    }
    
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password_confirmation = mysqli_real_escape_string($conn, $data["password_confirmation"]);


    $gambar = uploadGambar();
    if (!$gambar) {
        return false;
    }

    // cek email sudah ada atau belum
    $result = mysqli_query($conn, "SELECT email FROM doctors WHERE email = '$email'");
    if( mysqli_fetch_assoc($result) ) {
        echo "<script>
                alert('email sudah terdaftar');
            </script>";
        return false;
    }

    if ($password != $password_confirmation) {
        echo '<script>alert("Konfirmasi password tidak sesuai");</script>';
        return false;
    }

    // enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // tambahkan userbaru ke database
    mysqli_query($conn, "INSERT INTO doctors VALUES('', '$str', '$nama', '$profesi', '$pengalaman', '$rating', '$mahir', '$harga', '$alumni', '$praktik', '$gambar', '$email','$password', '$unique_id')");

    return mysqli_affected_rows($conn);
}

function uploadGambar() {

	$namaFile = $_FILES['gambar']['name'];
	$ukuranFile = $_FILES['gambar']['size'];
	$error = $_FILES['gambar']['error'];
	$tmpName = $_FILES['gambar']['tmp_name'];

	echo "<script>
				alert('$error $tmpName $ukuranFile');
			  </script>";
    
    

	// cek apakah tidak ada gambar yang diupload
	if( $error === 4 ) {
		echo "<script>
				alert('pilih gambar terlebih dahulu!');
			  </script>";
		return false;
	}

	// cek apakah yang diupload adalah gambar
	$ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
	$ekstensiGambar = explode('.', $namaFile);
	$ekstensiGambar = strtolower(end($ekstensiGambar));
	if( !in_array($ekstensiGambar, $ekstensiGambarValid) ) {
		echo "<script>
				alert('yang anda upload bukan gambar!');
			  </script>";
		return false;
	}

	// cek jika ukurannya terlalu besar
	if( $ukuranFile > 1000000 ) {
		echo "<script>
				alert('ukuran gambar terlalu besar!');
			  </script>";
		return false;
	}

	// lolos pengecekan, gambar siap diupload
	// generate nama gambar baru
	$namaFileBaru = uniqid();
	$namaFileBaru .= '.';
	$namaFileBaru .= $ekstensiGambar;

	move_uploaded_file($tmpName, 'dist/img/' . $namaFileBaru);

	return $namaFileBaru;
}

function tambahRoomChat($data) {
    global $conn;

    $patient_id = $data["patient_id"];
    $doctor_id = $_SESSION["id"];
    $nth_id = $data["nth_id"];

    $kode_unik = random_bytes(6);
    $kode_unik = bin2hex($kode_unik);
    $kode_unik = strtoupper($kode_unik);

    mysqli_query($conn, "UPDATE consultations SET kode_unik = '$kode_unik' WHERE doctor_id = '$doctor_id' AND patient_id = '$patient_id' AND nth_id = '$nth_id'");

    return mysqli_affected_rows($conn);
}


?>