<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$currentEmail = $_SESSION['user'][1];

/* ALWAYS REFRESH THE USER DATA FROM FILE  */
$users = file("users.txt");
foreach($users as $user){
    $data = explode("-", trim($user));
    if($data[1] == $currentEmail){
        $_SESSION['user'] = $data; // refresh session
        break;
    }
}


/*  PROFILE IMAGE UPLOAD  */
if(isset($_POST['upload_pic'])){

    if(isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0){

        $file = $_FILES['profile_pic'];
        $allowed = ['jpg','jpeg','png'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if(in_array($ext, $allowed) && $file['size'] < 2000000){

            $newImageName = time() . "_" . $currentEmail . "." . $ext;
            move_uploaded_file($file['tmp_name'], "uploads/" . $newImageName);

            $users = file("users.txt");
            $fileWrite = fopen("users.txt", "w");

            foreach($users as $user){
                $data = explode("-", trim($user));

                if($data[1] == $currentEmail){
                    $data[4] = $newImageName;
                    $_SESSION['user'][4] = $newImageName;
                }

                fwrite($fileWrite, implode("-", $data) . "\n");
            }

            fclose($fileWrite);
            header("Location: dashboard.php?page=profile");
            exit;
        }
    }
}

/*  DELETE USER  */
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

/* UPDATE USER  */
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
            $image = isset($data[4]) ? $data[4] : "default.png";
            $user = "$newName-$email-$newPhone-$newPassword-$image\n";
        }

        fwrite($file, $user);
    }

    fclose($file);
    header("Location: dashboard.php?page=users");
    exit;
}

$userImage = (!empty($_SESSION['user'][4])) ? $_SESSION['user'][4] : "default.png";
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
            
                <!-- User Dropdown -->
                <div class="user-dropdown" onclick="toggleDropdown(event)">
                    <div class="user-info">
                        <span><?= $_SESSION['user'][0]; ?></span>
                        <img src="uploads/<?= $userImage; ?>" alt="Profile">
                    </div>
                    <div class="dropdown-menu" id="userDropdown">
                        <a href="dashboard.php?page=profile">Profile</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            </div>


        <!-- HOME -->
        <?php if($page == 'home'): ?>

            <div class="dashboard-card">
                <h4>Hello</h4>
                <p>Welcome back, <strong><?= $_SESSION['user'][0]; ?></strong></p>
            </div>

        <!-- PROFILE -->
        <?php elseif($page == 'profile'): ?>

            <div class="profile-section">

                <form method="POST" enctype="multipart/form-data">
                    <label for="profile_pic">
                        <img src="uploads/<?= $userImage; ?>" class="profile-pic" id="previewImg">
                    </label>

                    <input type="file" name="profile_pic" id="profile_pic"
                           class="hidden-input" accept="image/*"
                           onchange="previewImage(event)" required>

                    <br>
                    <button type="submit" name="upload_pic" class="upload-btn">
                        Update Profile Picture
                    </button>
                </form>
            </div>
            
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
            if(isset($_GET['edit'])){
                $editEmail = $_GET['edit'];
                $users = file("users.txt");

                foreach($users as $user){
                    $data = explode("-", trim($user));
                    if($data[1] == $editEmail){
                        $editName = $data[0];
                        $editPhone = $data[2];
                        $editPassword = $data[3];
                    }
                }
            ?>

            <div class="dashboard-card" style="margin-bottom:20px;">
                <h4>Edit User</h4>
                <br>

                <form method="POST">
                    <input type="hidden" name="email" value="<?= $editEmail; ?>">

                    <input type="text" name="name" value="<?= $editName; ?>" required><br><br>
                    <input type="text" name="phone" value="<?= $editPhone; ?>" required><br><br>
                    <input type="text" name="password" value="<?= $editPassword; ?>" required><br><br>

                    <button type="submit" name="update_user" class="upload-btn">
                        Update User
                    </button>
                </form>
            </div>

            <?php } ?>

            <div class="dashboard-card">
                <h4>All Users</h4>
                <br>

                <table border="1" width="100%" cellpadding="8">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>

                    <?php
                    $file = fopen("users.txt", "r");

                    while(!feof($file)){
                        $line = trim(fgets($file));
                        if(empty($line)) continue;

                        $data = explode("-", $line);
                        $img = isset($data[4]) ? $data[4] : "default.png";
                    ?>
                        <tr>
                            <td><?= $data[0]; ?></td>
                            <td><?= $data[1]; ?></td>
                            <td><?= $data[2]; ?></td>
                            <td><img src="uploads/<?= $img; ?>" width="40" style="border-radius:50%;"></td>
                            <td>
                                <a href="dashboard.php?page=users&edit=<?= $data[1]; ?>" style="color:#00d4ff;">Edit</a> |
                                <a href="dashboard.php?page=users&delete=<?= $data[1]; ?>" 
                                   onclick="return confirm('Delete this user?')" 
                                   style="color:#ff4d4d;">Delete</a>
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

<script>
function previewImage(event){
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById('previewImg').src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>
<script>
function previewImage(event){
    const reader = new FileReader();
    reader.onload = function(){
        document.getElementById('previewImg').src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}

// Toggle user dropdown
function toggleDropdown(event) {
    event.stopPropagation();
    document.getElementById('userDropdown').classList.toggle('show');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('userDropdown');
    const userDropdown = document.querySelector('.user-dropdown');
    
    if (!userDropdown.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});
</script>

</body>
</html>