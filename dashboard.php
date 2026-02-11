<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="dashboard-wrapper">

    <!-- Sidebar -->
    <div class="sidebar">
        <div>
            <h3>My Panel</h3>
            <div class="nav-links">
                <a href="dashboard.php?page=home" class="<?= $page == 'home' ? 'active' : '' ?>">Home</a>
                <a href="dashboard.php?page=profile" class="<?= $page == 'profile' ? 'active' : '' ?>">Profile</a>
                <a href="dashboard.php?page=users" class="<?= $page == 'users' ? 'active' : '' ?>">Users</a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="dashboard-content">

        <!-- Header -->
        <div class="dashboard-header">
            <h2>Welcome, <?php echo $_SESSION['user'][0]; ?></h2>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <!-- Cards Section -->
        <div class="card-grid">

            <div class="dashboard-card">
                <h4>Full Name</h4>
                <p><?php echo $_SESSION['user'][0]; ?></p>
            </div>

            <div class="dashboard-card">
                <h4>Email Address</h4>
                <p><?php echo $_SESSION['user'][1]; ?></p>
            </div>

            <div class="dashboard-card">
                <h4>Phone Number</h4>
                <p><?php echo $_SESSION['user'][2]; ?></p>
            </div>

        </div>

    </div>

</div>

</body>
</html>
