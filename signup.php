<?php
if(isset($_POST['signup'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    $file = fopen("users.txt", "a+");
    rewind($file);
    $exists = false;

    while(!feof($file)){
        $line = fgets($file);
        $data = explode("_", trim($line));

        if(isset($data[1]) && $data[1] == $email){
            $exists = true;
            break;
        }
    }

    if($exists){
        echo "User already registered!";
    } else {
        $userData = "$name-$email-$phone-$password\n";
        fwrite($file, $userData);
        echo "Signup successful!";
    }

    fclose($file);
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
    Name: <input type="text" name="name" required><br>
    Email: <input type="email" name="email" required><br>
    Phone: <input type="text" name="phone" required><br>
    Password: <input type="password" name="password" required><br>
    <button name="signup">Signup</button><br><br>
    <p>Alraedy have an account?<a href="login.php">Login here</a></p>
</form>
</body>
</html>

