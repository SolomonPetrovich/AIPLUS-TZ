<?php
$host = "localhost";
$username = "root"; 
$password = "0000"; 
$database = "aiplus"; 

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>