<?php
require '../../app/config.php';

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
