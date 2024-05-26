<?php
include '../includes/database.php';
include_once '../includes/header_admin.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $warna = $_POST['warna'];
        $sql = "INSERT INTO scooter (Warna, Status) VALUES ('$warna', 'Tersedia')";
        $conn->query($sql);
    } elseif (isset($_POST['delete'])) {
        $id_scooter = $_POST['id_scooter'];
        $sql = "DELETE FROM scooter WHERE id_scooter = $id_scooter";
        $conn->query($sql);
    } elseif (isset($_POST['update'])) {
        $id_scooter = $_POST['id_scooter'];
        $warna = $_POST['warna'];
        $status = $_POST['status'];
        $sql = "UPDATE scooter SET Warna = '$warna', Status = '$status' WHERE id_scooter = $id_scooter";
        $conn->query($sql);
    }
}

$result = $conn->query("SELECT * FROM scooter");
$perbarui_skuter = null;
if (isset($_GET['edit'])) {
    $id_scooter = $_GET['edit'];
    $edit_result = $conn->query("SELECT * FROM scooter WHERE id_scooter = $id_scooter");
    $perbarui_skuter = $edit_result->fetch_assoc();
}
?>

<main>
    <h1>Kelola Skuter</h1>
    <button onclick="toggleAddForm()">Tambah Skuter</button>

    <table>
        <tr>
            <th>Id Scooter</th>
            <th>Warna</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id_scooter']; ?></td>
                <td><?php echo $row['warna']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <form method="POST" style="display:inline-block;">
                        <input type="hidden" name="id_scooter" value="<?php echo $row['id_scooter']; ?>">
                        <button class="btn-hapus" type="submit" name="delete" onclick="return confirm('Anda yakin ingin menghapus skuter ini?')">Hapus</button>
                    </form>
                    <form method="GET" action="" style="display:inline-block;">
                        <input type="hidden" name="edit" value="<?php echo $row['id_scooter']; ?>">
                        <button type="submit">Edit</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</main>

<?php if ($perbarui_skuter) { ?>
    <main>
        <h1>Perbarui Skuter</h1>
        <form method="POST">
            <input type="hidden" name="id_scooter" value="<?php echo $perbarui_skuter['id_scooter']; ?>">
            <label for="warna">Warna:</label>
            <input type="text" id="warna" name="warna" value="<?php echo $perbarui_skuter['warna']; ?>" required>
            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="Tersedia" <?php if ($perbarui_skuter['status'] == 'Tersedia') echo 'selected'; ?>>Tersedia</option>
                <option value="Dipinjam" <?php if ($perbarui_skuter['status'] == 'Dipinjam') echo 'selected'; ?>>Dipinjam</option>
                <option value="Rusak" <?php if ($perbarui_skuter['status'] == 'Rusak') echo 'selected'; ?>>Rusak</option>
            </select>
            <button type="submit" name="update" onclick="return confirm('Anda yakin ingin memperbarui skuter ini?')">Perbarui Skuter</button>
        </form>
    </main>    
<?php } ?>

<main id="addForm" style="display:none;">
    <h1>Tambah Skuter</h1>
    <form method="POST">
        <label for="warna">Warna:</label>
        <input type="text" id="warna" name="warna" required>
        <button type="submit" name="add" onclick="return confirm('Apakah anda yakin?')">Tambah Skuter</button>
    </form>
</main>

<?php include '../includes/footer.php'; ?>

<script>
    function toggleAddForm() {
        var addForm = document.getElementById('addForm');
        if (addForm.style.display === 'none') {
            addForm.style.display = 'block';
        } else {
            addForm.style.display = 'none';
        }
    }
</script>
