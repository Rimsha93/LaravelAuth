<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

/*  DELETE LOGIC */
if(isset($_GET['delete'])){
    $deleteEmail = $_GET['delete'];
    $users = file("users.txt");
    $file = fopen("users.txt", "w");

    foreach($users as $user){
        $data = explode("-", trim($user));
        if($data[1] != $deleteEmail){
            fwrite($file, $user);
        }
    }

    fclose($file);
    header("Location: dashboard.php?page=users");
    exit;
}

/* UPDATE LOGIC  */
if(isset($_POST['update_user'])){
    $email = $_POST['email'];
    $newName = $_POST['name'];
    $newPhone = $_POST['phone'];
    $newPassword = $_POST['password'];

    $users = file("users.txt");
    $file = fopen("users.txt", "w");

    foreach($users as $user){
        $data = explode("-", trim($user));
        if($data[1] == $email){
            $user = "$newName-$email-$newPhone-$newPassword\n";
        }
        fwrite($file, $user);
    }

    fclose($file);
    header("Location: dashboard.php?page=users");
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

        <div class="dashboard-header">
            <h2>Welcome, <?= $_SESSION['user'][0]; ?></h2>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <!-- HOME -->
        <?php if($page == 'home'): ?>

            <div class="dashboard-card">
                <h4>Hello 👋</h4>
                <p>Welcome back, <strong><?= $_SESSION['user'][0]; ?></strong></p>
            </div>

        <!-- PROFILE -->
        <?php elseif($page == 'profile'): ?>

            <div class="card-grid">

                <div class="dashboard-card">
                    <h4>Full Name</h4>
                    <p><?= $_SESSION['user'][0]; ?></p>
                </div>

                <div class="dashboard-card">
                    <h4>Email</h4>
                    <p><?= $_SESSION['user'][1]; ?></p>
                </div>

                <div class="dashboard-card">
                    <h4>Phone</h4>
                    <p><?= $_SESSION['user'][2]; ?></p>
                </div>

            </div>

        <!-- USERS -->
        <?php elseif($page == 'users'): ?>

            <?php
            /* EDIT MODE CHECK */
            if(isset($_GET['edit'])){
                $editEmail = $_GET['edit'];
                $users = file("users.txt");

                foreach($users as $user){
                    $data = explode("_", trim($user));
                    if($data[1] == $editEmail){
                        $editName = $data[0];
                        $editPhone = $data[2];
                        $editPassword = $data[3];
                    }
                }
            ?>

            <!-- EDIT FORM -->
            <div class="dashboard-card">
                <h4>Edit User</h4>
                <br>
                <form method="post">
                    <input type="hidden" name="email" value="<?= $editEmail; ?>">

                    <input type="text" name="name" value="<?= $editName; ?>" required><br><br>
                    <input type="text" name="phone" value="<?= $editPhone; ?>" required><br><br>
                    <input type="text" name="password" value="<?= $editPassword; ?>" required><br><br>

                    <button type="submit" name="update_user">Update</button>
                </form>
            </div>

            <?php } ?>

            <!-- USERS TABLE -->
            <div class="dashboard-card">
                <h4>All Users</h4>
                <br>

                <table border="1" width="100%" cellpadding="8">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>

                    <?php
                    $file = fopen("users.txt", "r");

                    while(!feof($file)){
                        $line = trim(fgets($file));
                        if(empty($line)) continue;

                        $data = explode("-", $line);
                    ?>
                        <tr>
                            <td><?= $data[0]; ?></td>
                            <td><?= $data[1]; ?></td>
                            <td><?= $data[2]; ?></td>
                            <td>
                                <a href="dashboard.php?page=users&edit=<?= $data[1]; ?>">Edit</a> |
                                <a href="dashboard.php?page=users&delete=<?= $data[1]; ?>" onclick="return confirm('Delete this user?')">Delete</a>
                            </td>
                        </tr>
                    <?php
                    }

                    fclose($file);
                    ?>
                </table>
            </div>

        <?php endif; ?>

    </div>
</div>

</body>
</html>
