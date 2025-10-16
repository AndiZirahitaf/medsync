<?php 
    session_start();
    require '../connection/koneksi.php';
    
   
     if(isset($_SESSION['unique_id'])){
        include_once '../connection/koneksi.php';
        $outgoing_id = $_SESSION['unique_id'];
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
        if (isset($_POST['cons_code'])){
            $cons_code = mysqli_real_escape_string($conn, $_POST['cons_code']);
        } else {
            $cons_code = $_SESSION['kode_unik'];
        }
        
        $output = "";
        
        if ($_SESSION["userType"] == "patient") {
             $sql = "SELECT * FROM messages LEFT JOIN patients ON patients.unique_id = messages.outgoing_msg_id
                WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id} AND cons_code = '{$cons_code}')
                OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id} AND cons_code = '{$cons_code}') ORDER BY msg_id";

                $doctor = query("SELECT * FROM doctors WHERE unique_id = '$incoming_id'")[0];
                $strGambar = '<img src="../dist/img/'.$doctor["gambar"].'" alt="">';

        } else if ($_SESSION["userType"] == "doctor") {
            $sql = "SELECT * FROM messages LEFT JOIN doctors ON doctors.unique_id = messages.outgoing_msg_id
                WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id} AND cons_code = '{$cons_code}')
                OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id} AND cons_code = '{$cons_code}') ORDER BY msg_id";

                 $strGambar = '<img src="../dist/img/patient.png" alt="">';
        }

        $query = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query) > 0){
            while($row = mysqli_fetch_assoc($query)){
                if($row['outgoing_msg_id'] === $outgoing_id){
                    $output .= '<div class="chat outgoing">
                                <div class="details">
                                    <p>'. $row['msg'] .'</p>
                                </div>
                                </div>';
                }else{
                    $output .= '<div class="chat incoming">'
                                .$strGambar.
                                '<div class="details">
                                    <p>'. $row['msg'] .'</p>
                                </div>
                                </div>';
                }
            }
        }else{
            $output .= '<div class="text">Belum ada pesan.</div>';
        }
        echo $output;
    }else{
        header("location: ../login.php");
    }

?>