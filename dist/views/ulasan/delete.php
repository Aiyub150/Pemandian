<?php
require '../../app/config.php';

$conn = new mysqli('localhost', 'root', '', 'pemandian');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

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
