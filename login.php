<?php
session_start();

$error = "";

if(isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if(empty($email) || empty($password)){
        $error = "Please fill all fields!";
    } else {

        if(file_exists("users.txt")){

            $file = fopen("users.txt", "r");
            $found = false;

            while(!feof($file)){
                $line = fgets($file);
                $data = explode("-", trim($line));

                if(isset($data[1]) && isset($data[3])){
                    if($data[1] == $email && $data[3] == $password){
                        $_SESSION['user'] = $data;
                        $found = true;
                        break;
                    }
                }
            }

            fclose($file);

            if($found){
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid email or password!";
            }

        } else {
            $error = "User file not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Auth System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="auth-wrapper">

    <h2 class="auth-title">Welcome Back!</h2>

    <?php if($error): ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="post">

        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" name="login" class="auth-btn">Login</button>

    </form>

    <div class="auth-footer">
        Don’t have an account?
        <a href="signup.php">Create Account</a>
    </div>

</div>

</body>
</html>
