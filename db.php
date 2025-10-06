<?php
// ✅ Show PHP Errors (500 issue trace ke liye)
error_reporting(E_ALL);
ini_set("display_errors", 1);

$host = "localhost"; // ✅ Agar host change ho, bata dena
$user = "uppbmi0whibtc";
$pass = "bjgew6ykgu1v";
$dbname = "db382tjgrk7koi";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
?>
