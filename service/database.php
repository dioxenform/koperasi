<?php 

$hostname = "LOCALHOST";
$username = "root";
$password = "";
$database_name = "koperasi";


$db = mysqli_connect($hostname,$username,$password, $database_name);

if($db->connect_error){
    echo "koneksi databse rusak";
    die("error!");
}

?>