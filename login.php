<?php
session_start();

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $file = fopen("users.txt", "r");
    $found = false;

    while(!feof($file)){
        $line = fgets($file);
        $data = explode("-", trim($line));

        if(isset($data[1]) && $data[1] == trim($email) && $data[3] == trim($password)){
            $_SESSION['user'] = $data;
            $found = true;
            break;
        }
    }

    fclose($file);

    if($found){
        header("Location: dashboard.php");
    } else {
        echo "Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <form method="post">
    Email: <input type="email" name="email"><br>
    Password: <input type="password" name="password"><br>
    <button name="login">Login</button><br><br>
    <p>Don't have an account? <a href="signup.php">Signup here</a></p>
</form>
</body>
</html>

