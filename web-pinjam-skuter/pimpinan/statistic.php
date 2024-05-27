<?php session_start();

include '../includes/database.php';
include '../includes/function.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'Pimpinan') {
    header("Location: " . base_url('/login.php'));
    exit();
}

// Statistik skuter terlaris
$scooter_stats = $conn->query("SELECT id_scooter, COUNT(*) as jumlah_transaksi FROM transaksipenyewaan GROUP BY id_scooter ORDER BY jumlah_transaksi DESC LIMIT 10");

// Statistik penyewa terbanyak
$renter_stats = $conn->query("SELECT no_ktp, nama_penyewa, COUNT(*) as jumlah_transaksi FROM transaksipenyewaan GROUP BY no_ktp, nama_penyewa ORDER BY jumlah_transaksi DESC LIMIT 10");

// Statistik kecamatan
$kecamatan_stats = $conn->query("SELECT kecamatan.nama_kecamatan, COUNT(*) as jumlah_transaksi FROM transaksipenyewaan JOIN kecamatan ON transaksipenyewaan.id_kecamatan = kecamatan.id_kecamatan GROUP BY kecamatan.nama_kecamatan ORDER BY jumlah_transaksi DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url('../css/style.css'); ?>">
    <title>Statistik Penyewaan</title>
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
            <h1>Statistik Penyewaan</h1>
            <h2>Skuter Terlaris</h2>
            <table>
                <tr>
                    <th>Id Skuter</th>
                    <th>Jumlah Transaksi</th>
                </tr>
                <?php while ($row = $scooter_stats->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_scooter']; ?></td>
                        <td><?php echo $row['jumlah_transaksi']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>

            <h2>Orang yang paling sering menyewa skuter</h2>
            <table>
                <tr>
                    <th>No KTP</th>
                    <th>Nama Penyewa</th>
                    <th>Jumlah Transaksi</th>
                </tr>
                <?php while ($row = $renter_stats->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['no_ktp']; ?></td>
                        <td><?php echo $row['nama_penyewa']; ?></td>
                        <td><?php echo $row['jumlah_transaksi']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>

            <h2>Kecamatan yang paling sering menyewa skuter</h2>
            <table>
                <tr>
                    <th>Nama Kecamatan</th>
                    <th>Jumlah Transaksi</th>
                </tr>
                <?php while ($row = $kecamatan_stats->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['nama_kecamatan']; ?></td>
                        <td><?php echo $row['jumlah_transaksi']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </main>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
