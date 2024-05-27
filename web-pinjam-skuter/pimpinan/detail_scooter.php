<?php
session_start();
include '../includes/database.php';
include '../includes/function.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'Pimpinan') {
    header("Location: " . base_url('/login.php'));
    exit();
}

$scooter_list = [];
$search_query = $_GET['query'] ?? '';

if ($search_query) {
    $stmt = $conn->prepare("SELECT * FROM scooter WHERE id_scooter LIKE ? OR warna LIKE ?");
    $search_term = "%" . $search_query . "%";
    $stmt->bind_param('ss', $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $scooter_list[] = $row;
    }
    $stmt->close();
} else {
    $result = $conn->query("SELECT * FROM scooter");
    while ($row = $result->fetch_assoc()) {
        $scooter_list[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url('../css/style.css'); ?>">
    <title>List Skuter</title>
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
            <h1>Data Skuter</h1>
            <form method="GET" action="detail_scooter.php">
                <label for="query">Cari berdasarkan ID atau Warna:</label><br>
                <input type="text" id="query" name="query" value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Search</button>
            </form>
            <br>
            <?php if (!empty($scooter_list)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID Skuter</th>
                            <th>Warna</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($scooter_list as $scooter): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($scooter['id_scooter']); ?></td>
                                <td><?php echo htmlspecialchars($scooter['warna']); ?></td>
                                <td><?php echo htmlspecialchars($scooter['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Data "<?php echo htmlspecialchars($_GET['query']); ?>" tidak ditemukan</p>
            <?php endif; ?>
        </main>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
