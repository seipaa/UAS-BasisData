<?php
include_once 'function.php';
include_once '../includes/database.php';?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url('/css/style.css'); ?>">
    <title>Admin Dashboard</title>
</head>
<header>
    <nav>
        <ul>
            
            <li><a href="<?php echo base_url('/admin/dashboard.php'); ?>">Dashboard</a></li>
            <li><a href="<?php echo base_url('/admin/manage_user.php'); ?>">Kelola User</a></li>
            <li><a href="<?php echo base_url('/admin/manage_scooter.php'); ?>">Kelola Skuter</a></li>
            <li><a href="<?php echo base_url('/admin/update_tarif.php'); ?>">Perbarui Tarif</a></li>
            <li><a href="<?php echo base_url('/includes/logout.php'); ?> " onclick="return confirm('Anda yakin ingin keluar?')">Logout</a></li>
        </ul>
    </nav>
</header>


