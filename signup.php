<?php
$message = "";
$messageType = ""; // success or error

if(isset($_POST['signup'])){

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $password = trim($_POST['password']);

    if(empty($name) || empty($email) || empty($phone) || empty($password)){
        $message = "Please fill all fields!";
        $messageType = "error";
    } else {

        if(file_exists("users.txt")){
            $file = fopen("users.txt", "a+");
            rewind($file);
            $exists = false;

            while(!feof($file)){
                $line = fgets($file);
                $data = explode("-", trim($line)); // FIXED separator

                if(isset($data[1]) && $data[1] == $email){
                    $exists = true;
                    break;
                }
            }

            if($exists){
                $message = "User already registered!";
                $messageType = "error";
            } else {

                // Basic password hashing (important upgrade)
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $userData = "$name-$email-$phone-$hashedPassword\n";
                fwrite($file, $userData);

                $message = "Signup successful! You can login now.";
                $messageType = "success";
            }

            fclose($file);

        } else {
            $file = fopen("users.txt", "w");
            fclose($file);
            $message = "System initialized. Please signup again.";
            $messageType = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup | Auth System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="auth-wrapper">

    <h2 class="auth-title">Create Account </h2>

    <?php if($message): ?>
        <div class="msg <?php echo $messageType; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="post">

        <div class="form-group">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" name="signup" class="auth-btn">Signup</button>

    </form>

    <div class="auth-footer">
        Already have an account?
        <a href="login.php">Login</a>
    </div>

</div>

</body>
</html>
