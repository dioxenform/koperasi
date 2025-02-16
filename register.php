<?php
    include "service/database.php";
    session_start();

    $register_message = "";

    if(isset($_SESSION["is_login"])){
        header("location: dashboard.php");
    }

    if(isset($_POST["register"])){
        $username = $_POST["username"];
        $password = $_POST["password"];
        $hash_password = hash("sha256", $password);

        try {
            $SQL = "INSERT INTO users (username, password) VALUES('$username', '$hash_password')";
    
            if($db->query(query: $SQL)){
                $register_message = 'Daftar Akun Berhasil , Silahkan Login';
            }else {
                $register_message = "daftar akun gagal, Silahkan coba lagi";
            }

        }catch (mysqli_sql_exception) {
            $register_message = "Username Sudah Digunakan";
        }
        $db->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="Style/register.css">
</head>
<body>
    <?php include "layout/header.html" ?>
    <div class="container">
        <div class="form-box">
            <h3>Daftar Akun</h3>
            <i><?= $register_message ?></i>
            <form action="register.php" method="POST">
                <div class="field input">
                    <label for="username"><b>Username</b></label>
                    <input type="text" placeholder="USERNAME" name="username" required autocomplete="off"/>
                </div>
                <div class="field input">
                    <label for="password"><b>Password</b></label>
                    <input type="password" placeholder="PASSWORD" name="password" required autocomplete="new-password"/>
                </div>
                <div class="field button">
                    <button type="submit" name="register">Daftar</button>
                </div>
             </form>
        </div>
    </div>
</body>
</html>