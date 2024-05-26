<?php 
session_start();

include '../includes/database.php';
include '../includes/function.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'Operator') {
    header("Location: " . base_url('/login.php'));
    exit();
}

$list_kecamatan = $conn->query("SELECT id_kecamatan, nama_kecamatan FROM kecamatan");
$list_scooter = $conn->query("SELECT * FROM scooter WHERE status = 'Tersedia'");

date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $no_ktp = $_POST['no_ktp'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $id_kecamatan = $_POST['id_kecamatan'];
    $id_scooter = $_POST['id_scooter'];
    $tarif_per_jam = get_tarif_per_jam();
    $tanggal_mulai = date('Y-m-d H:i:s');

    $conn->begin_transaction();
    try {
        $sql = "INSERT INTO transaksipenyewaan (no_ktp, nama_penyewa, alamat_penyewa, id_kecamatan, id_scooter, tarif_per_jam, tanggal_mulai) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssisds', $no_ktp, $nama, $alamat, $id_kecamatan, $id_scooter, $tarif_per_jam, $tanggal_mulai);

        if (!$stmt->execute()) {
            throw new Exception("Error: " . $stmt->error);
        }

        $sql_update = "UPDATE scooter SET status = 'Disewa' WHERE id_scooter = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param('i', $id_scooter);

        if (!$stmt_update->execute()) {
            throw new Exception("Error: " . $stmt_update->error);
        }

        $conn->commit();
        $sukses = "Transaksi sewa berhasil direkam";
    } catch (Exception $e) {
        $conn->rollback();
        $error = $e->getMessage();
    }
    $stmt->close();
    $stmt_update->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url('../css/style.css'); ?>">
    <title>Pinjam Skuter</title>
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
            <h1>Peminjaman Skuter</h1>
            <?php
            if (isset($sukses)) {
                echo "<p>$sukses</p>";
            } elseif (isset($error)) {
                echo "<p>$error</p>";
            }
            ?>
            <form method="POST">
                <label for="no_ktp">No KTP:</label>
                <input type="number" id="no_ktp" name="no_ktp" required>
                <label for="nama">Nama:</label>
                <input type="text" id="nama" name="nama" required>
                <label for="alamat">Alamat:</label>
                <textarea id="alamat" name="alamat" required></textarea>
                <label for="id_kecamatan">Kecamatan:</label>
                <select id="id_kecamatan" name="id_kecamatan" required>
                    <?php while ($row = $list_kecamatan->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id_kecamatan']; ?>"><?php echo $row['nama_kecamatan']; ?></option>
                    <?php } ?>
                </select>
                <label for="id_scooter">Id Skuter:</label>
                <select id="id_scooter" name="id_scooter" required>
                    <?php while ($row = $list_scooter->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id_scooter']; ?>"> Id Skuter: <?php echo $row['id_scooter']; ?>, Warna: <?php echo $row['warna']; ?>, Status: <?php echo $row['status']; ?></option>
                    <?php } ?>
                </select>
                <button type="submit" onclick="return confirm('Apakah anda yakin?')">Pinjam</button>
            </form>
        </main>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
