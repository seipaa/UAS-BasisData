<?php
session_start();
include '../includes/database.php';
include '../includes/function.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'Operator') {
    header("Location: " . base_url('/login.php'));
    exit();
}

date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_transaksi = $_POST['id_transaksi'];
    $tanggal_pengembalian = date('Y-m-d H:i:s');
    $biaya_tambahan = $_POST['biaya_tambahan'];

    $sql = "SELECT id_scooter FROM transaksipenyewaan WHERE id_transaksi = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_transaksi);
    $stmt->execute();
    $stmt->bind_result($id_scooter);
    $stmt->fetch();
    $stmt->close();

    if ($id_scooter) {
        $sql = "INSERT INTO transaksipengembalian (id_transaksi, id_scooter, tanggal_pengembalian, biaya_tambahan) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iisd', $id_transaksi, $id_scooter, $tanggal_pengembalian, $biaya_tambahan);

        if ($stmt->execute()) {
            $sqlUpdate = "UPDATE Scooter SET status = 'Tersedia' WHERE id_scooter = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param('i', $id_scooter);
            $stmtUpdate->execute();

            $success = "Scooter berhasil dikembalikan.";
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
        $stmtUpdate->close();
    } else {
        $error = "Transaksi tidak valid atau id skuter tidak ditemukan";
    }
}

$result = $conn->query("SELECT * FROM transaksipenyewaan WHERE id_transaksi NOT IN (SELECT id_transaksi FROM transaksipengembalian)");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengembalian Scooter</title>
    <link rel="stylesheet" href="<?php echo base_url('../css/style.css'); ?>">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="<?php echo base_url('/operator/dashboard.php'); ?>">Dashboard</a></li>
                <li><a href="<?php echo base_url('/operator/rent_scooter.php'); ?>">Peminjaman</a></li>
                <li><a href="<?php echo base_url('/operator/return_scooter.php'); ?>">Pengembalian</a></li>
                <li><a href="<?php echo base_url('/operator/search_renter.php'); ?>">Cari Penyewa</a></li>
                <li><a href="<?php echo base_url('/includes/logout.php'); ?>" onclick="return confirm('Apakah anda yakin ingin keluar?')">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <main>
            <h1>Pengembalian Skuter</h1>
            <?php
            if (isset($success)) {
                echo "<p>$success</p>";
            } elseif (isset($error)) {
                echo "<p>$error</p>";
            }
            ?>
            <form method="POST">
                <label for="id_transaksi">Pilih Transaksi:</label>
                <select id="id_transaksi" name="id_transaksi" required>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id_transaksi']; ?>">
                            ID Transaksi: <?php echo $row['id_transaksi']; ?>, Penyewa: <?php echo $row['nama_penyewa']; ?>, ID Skuter: <?php echo $row['id_scooter']; ?>
                        </option>
                    <?php } ?>
                </select>
                <label for="biaya_tambahan">Biaya Tambahan (jika ada):</label>
                <input type="number" id="biaya_tambahan" name="biaya_tambahan" step="0.01"><br>
                <button type="submit">Kembalikan</button>
            </form>
        </main>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
