<?php
$conn = mysqli_connect("localhost", "root", "", "dbPerkuliahan");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>