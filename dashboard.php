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

<div class="dashboard">
    <h2>Welcome to Dashboard</h2>

    <div class="user-info">
        <p><strong>Name:</strong> <?php echo $_SESSION['user'][0]; ?></p>
        <p><strong>Email:</strong> <?php echo $_SESSION['user'][1]; ?></p>
        <p><strong>Phone:</strong> <?php echo $_SESSION['user'][2]; ?></p>
    </div>

    <a href="logout.php" class="logout-btn">Logout</a>
</div>

</body>
</html>