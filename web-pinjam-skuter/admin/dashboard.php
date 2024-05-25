<?php include '../includes/header_admin.php'; 
session_start();
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'Admin') {
    header("Location: " . base_url('/login.php'));
    exit();
};?>
<body>
    <main>
        <h1>Admin Dashboard</h1>
        <h3>Halo, <?php echo $_SESSION['username']; ?></h3>
    </main>
<?php include '../includes/footer.php'; ?>
