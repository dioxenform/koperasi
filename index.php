<?php
    include "service/database.php";
    session_start();

    $login_message = "" ;

    if(isset($_SESSION["is_login"])){
        header("location: dashboard.php");
    }

    if(isset($_POST['login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $hash_password = hash("sha256", $password);

        $SQL = "SELECT * FROM users WHERE username='$username' AND 
        password='$hash_password'
        ";

        $result = $db->query($SQL);

        if($result->num_rows > 0){
           $data = $result->fetch_assoc();
           $_SESSION["username"] = $data["username"];
           $_SESSION["is_login"] = true;

           header("location: dashboard.php");
        }else {
            $login_message = "Akun Tidak Ditemukan";
        }
        $db->close();
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/login.css">
    <title>Login</title>
</head>
<body>
    <?php include "layout/header.html" ?>
    <div class="container">
        <div class="form-box">
            <h3>Login Here</h3>
            <i><?= $login_message ?></i>
            <form action="index.php" method="POST">
            <div class="field input">
                    <label for="username"><b>Username</b></label>
                    <input type="text" placeholder="USERNAME" name="username" required autocomplete="off"/>
                </div>
                <div class="field input">
                    <label for="password"><b>Password</b></label>
                    <input type="password" placeholder="PASSWORD" name="password" required autocomplete="new-password"/>
                </div>
                <div class="field button">
                    <button type="submit" name="login">Masuk</button>
                </div>
        </div>
    </div>
</body>
</html>