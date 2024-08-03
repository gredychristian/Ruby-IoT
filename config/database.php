<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "iotlearning";

$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if(!$conn){
    die("Failed to connect. " . mysqli_connect_error());
}

// echo "Connection success. ";
?>