<?php
session_start();

// Hapus semua variabel sesi
session_unset();

// Hancurkan sesi
session_destroy();

// Arahkan kembali ke halaman login
header("location: index.php");
exit();
?>