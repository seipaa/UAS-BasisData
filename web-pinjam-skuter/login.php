<?php
session_start();
include 'includes/database.php';
include 'includes/function.php';

$result = $conn->query("SELECT COUNT(*) as count FROM pengguna");
$row = $result->fetch_assoc();
$user_count = $row['count'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($user_count == 0) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO pengguna (username, password, role) VALUES (?, ?, 'Admin')");
        $stmt->bind_param('ss', $username, $hashed_password);
        if ($stmt->execute()) {
            $_SESSION['id_user'] = $conn->insert_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = 'Admin';
            header("Location: " . base_url('/admin/dashboard.php'));
            exit();
        } else {
            $error = "Pendaftaran gagal, Silakan coba lagi";
        }
        $stmt->close();
    } else {
        // Proses login
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM pengguna WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'Admin') {
                header("Location: " . base_url('/admin/dashboard.php'));
                exit();
            } elseif ($user['role'] == 'Pimpinan') {
                header("Location: " . base_url('/pimpinan/dashboard.php'));
                exit();
            } elseif ($user['role'] == 'Operator') {
                header("Location: " . base_url('/operator/dashboard.php'));
                exit();
            }
        } else {
            $error = "Username atau password anda salah";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url('/css/style.css'); ?>">
    <title>Login</title>
</head>
<body>
    <main class="login-container">
        <div class="login-form">
            <?php if ($user_count == 0): ?>
                <h1>Daftar Admin Pertama</h1>
                <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
                <form method="POST">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required><br>
                    <button type="submit">Daftar</button>
                </form>
            <?php else: ?>
                <h1>Halaman Login</h1>
                <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
                <form method="POST">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required><br>
                    <button type="submit">Masuk</button>
                </form>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
