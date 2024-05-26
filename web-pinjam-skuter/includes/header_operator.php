<?php
session_start();
include 'function.php';
include '../includes/database.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'Operator') {
    header("Location: " . base_url('/login.php'));
    exit();
}?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url('/css/style.css'); ?>">
    <title>Operator Dashboard</title>
</head>
<header>
    <nav>
        <ul>
            <li><a href="<?php echo base_url('/operator/dashboard.php'); ?>">Dashboard</a></li>
            <li><a href="<?php echo base_url('/operator/rent_scooter.php'); ?>">Peminjaman</a></li>
            <li><a href="<?php echo base_url('/operator/return_scooter.php'); ?>">Pengembalian</a></li>
            <li><a href="<?php echo base_url('/operator/search_renter.php'); ?>">Cari Penyewa</a></li>
            <li><a href="<?php echo base_url('/includes/logout.php'); ?> " onclick="return confirm('Anda yakin ingin keluar?')">Logout</a></li>
        </ul>
    </nav>
</header>
