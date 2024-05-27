<?php
session_start();
include '../includes/database.php';
include '../includes/function.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'Pimpinan') {
    header("Location: " . base_url('/login.php'));
    exit();
}

$scooter_list = [];
$result = $conn->query("SELECT * FROM scooter");
while ($row = $result->fetch_assoc()) {
    $scooter_list[] = $row;
}

$transactions = [];
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';
$id_scooter = $_POST['id_scooter'] ?? '';

if ($start_date && $end_date && $id_scooter) {
    $stmt = $conn->prepare("SELECT * FROM transaksipenyewaan WHERE id_scooter = ? AND tanggal_mulai BETWEEN ? AND ?");
    $stmt->bind_param('iss', $id_scooter, $start_date, $end_date);
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
    <title>Laporan Transaksi Penyewaan</title>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="<?php echo base_url('/pimpinan/dashboard.php'); ?>">Dashboard</a></li>
                <li><a href="<?php echo base_url('/pimpinan/detail_scooter.php'); ?>">Data Skuter</a></li>
                <li><a href="<?php echo base_url('/pimpinan/report_scooter.php'); ?>">Laporan Skuter</a></li>
                <li><a href="<?php echo base_url('/pimpinan/report_renter.php'); ?>">Laporan Penyewa</a></li>
                <li><a href="<?php echo base_url('/pimpinan/statistic.php'); ?>">Statistik</a></li>
                <li><a href="<?php echo base_url('/includes/logout.php'); ?>" onclick="return confirm('Apakah anda yakin ingin keluar?')">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <main>
            <h1>Laporan Transaksi Penyewaan Berdasarkan Scooter</h1>
            <form method="post" action="">
                <label for="id_scooter">ID Skuter:</label>
                <select id="id_scooter" name="id_scooter" required>
                    <?php foreach ($scooter_list as $scooter): ?>
                        <option value="<?php echo $scooter['id_scooter']; ?>"><?php echo $scooter['id_scooter'] . ' - ' . $scooter['warna'] . ' - ' . $scooter['status']; ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="start_date">Tanggal Mulai:</label>
                <input type="date" id="start_date" name="start_date" required>
                <label for="end_date">Tanggal Akhir:</label>
                <input type="date" id="end_date" name="end_date" required>
                <button type="submit">Tampilkan Laporan</button>
            </form>
            <?php if (!empty($transactions)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>No KTP</th>
                            <th>Nama Penyewa</th>
                            <th>Alamat Penyewa</th>
                            <th>Tanggal Mulai</th>
                            <th>Tarif Per Jam</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td><?php echo $transaction['no_ktp']; ?></td>
                                <td><?php echo $transaction['nama_penyewa']; ?></td>
                                <td><?php echo $transaction['alamat_penyewa']; ?></td>
                                <td><?php echo $transaction['tanggal_mulai']; ?></td>
                                <td><?php echo $transaction['tarif_per_jam']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Tidak ada transaksi ditemukan untuk periode tersebut</p>
            <?php endif; ?>
        </main>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
