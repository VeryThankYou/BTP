<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "logplan";
//The information needed to log into the database

$conn = new mysqli($servername, $username, $password, $dbname);
//Create a connection to the database
mysqli_set_charset($conn,"utf8");
//Set the characterset to utf-8
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//In case of failed connection
?>