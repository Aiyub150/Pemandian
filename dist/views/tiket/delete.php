<?php
require '../../app/config.php';

session_start();

if (!isset($_SESSION['id_user']) || isset($_SESSION['level']) != '1') {
    header("location: ../login.php"); // Arahkan ke halaman login jika tidak ada sesi id_user
    exit();
}

if (isset($_GET["id"])) {
    $id_tiket = $_GET["id"];
    
    // Delete data from the database
    $sql = "DELETE FROM tiket WHERE id_tiket='$id_tiket'";
    
    if ($conn->query($sql) === true) {
        header("Location: tiket.php"); // Redirect to the page after deletion
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

?>
