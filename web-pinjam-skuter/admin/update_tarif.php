<?php
include '../includes/database.php';
include '../includes/header_admin.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tarif = $_POST['tarif'];
    $sql = "UPDATE tarif SET value = ? WHERE id = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('d', $tarif);
    if ($stmt->execute()) {
        echo "<p>Tarif berhasil diperbarui</p>";
    } else {
        echo "<p>Terjadi kesalahan saat memperbarui tarif</p>";
    }
    $stmt->close();
}

$result = $conn->query("SELECT value FROM tarif WHERE id = 1");
$tarifSekarang = $result->fetch_assoc();
?>
<main>
    <form style="max-width: fit-content;" method="POST">
        <label for="tarif">Tarif per jam saat ini:</label>
        <input type="number" id="tarif" name="tarif" value="<?php echo $tarifSekarang['value']; ?>" required>
        <button type="submit" onclick="return confirm('Apakah anda yakin?')">Perbarui</button>
    </form>
</main>
<?php include '../includes/footer.php'; ?>
