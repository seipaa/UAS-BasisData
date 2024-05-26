<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'Admin') {
    header("Location: /login.php");
    exit();
}

include_once '../includes/database.php';
include_once '../includes/function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role = $_POST['role'];
        $nama = $_POST['nama'];
        $alamat = $_POST['alamat'];
        $no_telepon = $_POST['no_telepon'];
        $sql = "INSERT INTO pengguna (username, password, role, nama, alamat, no_telepon) 
                VALUES ('$username', '$password', '$role', '$nama', '$alamat', '$no_telepon')";
        $conn->query($sql);
    } elseif (isset($_POST['delete'])) {
        $id_user = $_POST['id_user'];
        $sql = "DELETE FROM pengguna WHERE id_user = $id_user";
        $conn->query($sql);
    } elseif (isset($_POST['update'])) {
        $id_user = $_POST['id_user'];
        $username = $_POST['username'];
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $password_sql = ", password = '$password'";
        } else {
            $password_sql = "";
        }
        $role = $_POST['role'];
        $nama = $_POST['nama'];
        $alamat = $_POST['alamat'];
        $no_telepon = $_POST['no_telepon'];
        $sql = "UPDATE pengguna SET username = '$username', role = '$role', nama = '$nama', alamat = '$alamat', no_telepon = '$no_telepon' $password_sql WHERE id_user = $id_user";
        $conn->query($sql);
    }
}

$result = $conn->query("SELECT * FROM pengguna");

$edit_user = null;
if (isset($_GET['edit'])) {
    $id_user = $_GET['edit'];
    $edit_result = $conn->query("SELECT * FROM pengguna WHERE id_user = $id_user");
    $edit_user = $edit_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include '../includes/header_admin.php'; ?>
    
    <main>
    <h1>Kelola Pengguna</h1>
        
        <button onclick="document.getElementById('addUserForm').style.display='block'">Add User</button>    
        <table>
            <tr>
                <th>Id Pengguna</th>
                <th>Username</th>
                <th>Role</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>No Telepon</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id_user']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td><?php echo $row['nama']; ?></td>
                    <td><?php echo $row['alamat']; ?></td>
                    <td><?php echo $row['no_telepon']; ?></td>
                    <td>
                        <form method="POST" style="display:inline-block;">
                            <input type="hidden" name="id_user" value="<?php echo $row['id_user']; ?>">
                            <button type="submit" name="delete" onclick="return confirm('Apakah anda yakin ingin menghapus?')">Delete</button>
                        </form>
                        <form method="GET" action="" style="display:inline-block;">
                            <input type="hidden" name="edit" value="<?php echo $row['id_user']; ?>">
                            <button type="submit" >Edit</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <div>
            <?php if ($edit_user) { ?>
                <h2>Perbarui Pengguna</h2>
                <form method="POST">
                    <input type="hidden" name="id_user" value="<?php echo $edit_user['id_user']; ?>">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo $edit_user['username']; ?>" required>
                    <label for="password">Password (Kosongkan jika tidak ada yang diubah):</label>
                    <input type="password" id="password" name="password">
                    <label for="role">Role:</label>
                    <select id="role" name="role">
                        <option value="Admin" <?php if ($edit_user['role'] == 'Admin') echo 'selected'; ?>>Admin</option>
                        <option value="Pimpinan" <?php if ($edit_user['role'] == 'Pimpinan') echo 'selected'; ?>>Pimpinan</option>
                        <option value="Operator" <?php if ($edit_user['role'] == 'Operator') echo 'selected'; ?>>Operator</option>
                    </select>
                    <label for="nama">Nama:</label>
                    <input type="text" id="nama" name="nama" value="<?php echo $edit_user['nama']; ?>" required>
                    <label for="alamat">Alamat:</label>
                    <textarea id="alamat" name="alamat" required><?php echo $edit_user['alamat']; ?></textarea>
                    <label for="no_telepon">No Telepon:</label>
                    <input type="number" id="no_telepon" name="no_telepon" value="<?php echo $edit_user['no_telepon']; ?>" required>
                    <button type="submit" name="update" onclick="return confirm('Apakah anda yakin?')">Update User</button>
                </form>
            <?php } ?>
            
            <div id="addUserForm" style="display:none;">
                <h2>Tambah Pengguna Baru</h2>
                <form method="POST">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <label for="role">Role:</label>
                    <select id="role" name="role">
                        <option value="Admin">Admin</option>
                        <option value="Pimpinan">Pimpinan</option>
                        <option value="Operator">Operator</option>
                    </select>
                    <label for="nama">Nama:</label>
                    <input type="text" id="nama" name="nama" required>
                    <label for="alamat">Alamat:</label>
                    <textarea id="alamat" name="alamat" required></textarea>
                    <label for="no_telepon">No Telepon:</label>
                    <input type="number" id="no_telepon" name="no_telepon" required>
                    <button type="submit" name="add" onclick="return confirm('Apakah anda yakin?')">Tambah User</button>
                </form>
            </div>
        </main>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script>
        document.getElementById('addUserForm').style.display = 'none';

        function toggleAddUserForm() {
            var addUserForm = document.getElementById('addUserForm');
            if (addUserForm.style.display === 'none') {
                addUserForm.style.display = 'block';
            } else {
                addUserForm.style.display = 'none';
            }
        }

        document.querySelector('button[onclick="toggleAddUserForm()"]').addEventListener('click', toggleAddUserForm);
    </script>
</body>
</html>
