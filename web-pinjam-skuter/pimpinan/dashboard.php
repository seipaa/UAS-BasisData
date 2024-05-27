<?php
session_start();

include '../includes/database.php';
include '../includes/function.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'Pimpinan') {
    header("Location: " . base_url('/login.php'));
    exit();
}; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url('../css/style.css'); ?>">
    <title>Dashboard Pimpinan Taman</title>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="<?php echo base_url('/pimpinan/dashboard.php'); ?>">Dashboard</a></li>
                <li><a href="<?php echo base_url('/pimpinan/detail_scooter.php'); ?>">Data Scooter</a></li>
                <li><a href="<?php echo base_url('/pimpinan/report_scooter.php'); ?>">Laporan Scooter</a></li>
                <li><a href="<?php echo base_url('/pimpinan/report_renter.php'); ?>">Laporan Penyewa</a></li>
                <li><a href="<?php echo base_url('/pimpinan/statistic.php'); ?>">Statistik</a></li>
                <li><a href="<?php echo base_url('/includes/logout.php'); ?>" onclick="return confirm('Apakah anda yakin ingin keluar?')">Logout</a></li>
            </ul>
        </nav>
    </header>    
    <div class="container">
        <main>
            <h1>Dashboard Pimpinan Taman</h1>
            <h3>Selamat datang, <?php echo $_SESSION ['username']; ?></h3>
        </main>
    </div>
    <?php include '../includes/footer.php'; ?>
