<?php
include '../includes/database.php';

$search_results = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['query'])) {
    $query = $_GET['query'];
    $sql = "SELECT tp.*, tb.tanggal_pengembalian, tb.biaya_tambahan
            FROM transaksipenyewaan tp 
            LEFT JOIN transaksipengembalian tb ON tp.id_transaksi = tb.id_transaksi 
            WHERE tp.no_ktp LIKE ? OR tp.nama_penyewa LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_term = "%" . $query . "%";
    $stmt->bind_param('ss', $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $search_results[] = $row;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Penyewa</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include '../includes/header_operator.php'; ?>
    <div class="container">
        <main>
            <h1>Cari Penyewa</h1>
            <form method="GET">
                <label for="query">Cari berdasarkan NO KTP atau Nama Penyewa:</label><br>
                <input type="text" id="query" name="query" required>
                <button type="submit">Search</button>
            </form>

            <?php if (!empty($search_results)) { ?>
                <h2>Hasil Pencarian:</h2>
                <table>
                    <thead>
                        <tr>
                            <th>No KTP</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Id Skuter</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Pengembalian</th>
                            <th>Tarif</th>
                            <th>Biaya Tambahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($search_results as $renter) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($renter['no_ktp']); ?></td>
                                <td><?php echo htmlspecialchars($renter['nama_penyewa']); ?></td>
                                <td><?php echo htmlspecialchars($renter['alamat_penyewa']); ?></td>
                                <td><?php echo htmlspecialchars($renter['id_scooter']); ?></td>
                                <td><?php echo htmlspecialchars($renter['tanggal_mulai']); ?></td>
                                <td><?php echo htmlspecialchars($renter['tanggal_pengembalian'] ? $renter['tanggal_pengembalian'] : 'Belum Dikembalikan'); ?></td>
                                <td><?php echo htmlspecialchars($renter['tarif_per_jam']); ?></td>
                                <td><?php echo htmlspecialchars($renter['biaya_tambahan']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } elseif (isset($_GET['query'])) { ?>
                <p>Data "<?php echo htmlspecialchars($_GET['query']); ?> " tidak ditemukan</p>
            <?php } ?>
        </main>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
