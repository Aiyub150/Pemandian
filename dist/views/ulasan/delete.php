<?php

session_start(); // Pastikan Anda memulai sesi sebelum mengakses $_SESSION

if(isset($_SESSION['level']) && ($_SESSION['level'] == '1' || $_SESSION['level'] == '2')){

// Pengguna dengan level 1 atau 2 diizinkan mengakses dashboard.php

} else {

header('Location: ../index.php'); exit();

}

require '../../app/config.php';

if (isset($_GET["id"])) {
    $id_user = $_GET["id"];
    
    // Delete data from the database
    $sql = "DELETE FROM ulasan WHERE id_user='$id_user'";
    
    if ($conn->query($sql) === true) {
        header("Location: ulasan.php"); // Redirect to the page after deletion
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

?>
