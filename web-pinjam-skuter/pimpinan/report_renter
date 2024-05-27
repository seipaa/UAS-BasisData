<?php session_start();

include '../includes/database.php';
include '../includes/function.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'Pimpinan') {
    header("Location: " . base_url('/login.php'));
    exit();
}

$start_date = $_POST['start_date'] ?? null;
$end_date = $_POST['end_date'] ?? null;
$no_ktp = $_POST['no_ktp'] ?? null;

$transactions = [];
if ($start_date && $end_date && $no_ktp) {
    $stmt = $conn->prepare("SELECT * FROM transaksipenyewaan WHERE no_ktp = ? AND tanggal_mulai BETWEEN ? AND ?");
    $stmt->bind_param('sss', $no_ktp, $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url('../css/style.css'); ?>">
    <title>Laporan Penyewaan Berdasarkan Penyewa</title>
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
            <h1>Laporan Penyewaan Berdasarkan Penyewa</h1>
            <form method="POST">
                <label for="no_ktp">No KTP:</label>
                <input type="text" id="no_ktp" name="no_ktp" required>
                <label for="start_date">Tanggal Mulai:</label>
                <input type="date" id="start_date" name="start_date" required>
                <label for="end_date">Tanggal Akhir:</label>
                <input type="date" id="end_date" name="end_date" required>
                <button type="submit">Lihat Laporan</button>
            </form>
            <?php if ($transactions): ?>
                <table>
                    <tr>
                        <th>No KTP</th>
                        <th>Nama Penyewa</th>
                        <th>Alamat Penyewa</th>
                        <th>Tanggal Mulai</th>
                        <th>Tarif Per Jam</th>
                    </tr>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?php echo $transaction['no_ktp']; ?></td>
                            <td><?php echo $transaction['nama_penyewa']; ?></td>
                            <td><?php echo $transaction['alamat_penyewa']; ?></td>
                            <td><?php echo $transaction['tanggal_mulai']; ?></td>
                            <td><?php echo $transaction['tarif_per_jam']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>Tidak ada transaksi ditemukan untuk periode tersebut</p>
            <?php endif; ?>
        </main>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
