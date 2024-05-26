<?php
include 'database.php';

function get_tarif_per_jam() {
    global $conn;
    $result = $conn->query("SELECT value FROM tarif WHERE id = 1");
    $tarif = $result->fetch_assoc();
    return $tarif['value'];
}

function base_url($path = '') {
    $base_url = 'http://localhost/web-pinjam-skuter';
    return $base_url . $path;
}
?>
