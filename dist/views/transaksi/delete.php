<?php


session_start(); // Pastikan Anda memulai sesi sebelum mengakses $_SESSION

if(isset($_SESSION['level']) && ($_SESSION['level'] == '1' || $_SESSION['level'] == '2')){

// Pengguna dengan level 1 atau 2 diizinkan mengakses dashboard.php

} else {

header('Location: ../index.php'); exit();

}


require '../../app/config.php';

if (isset($_GET["id"])) {
    $id_transaksi = $_GET["id"];
    
    // Delete data from the database
    $sql = "DELETE FROM transaksi WHERE id_transaksi='$id_transaksi'";
    
    if ($conn->query($sql) === true) {
        header("Location: transaksi.php"); // Redirect to the page after deletion
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

?>
