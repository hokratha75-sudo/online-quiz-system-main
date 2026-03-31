<?php
$host = "127.0.0.1";
$port = "5432";
$dbname = "online_quiz_project";
$user = "postgres";
$password = "123";

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    echo "Connection Successful!";
} catch (PDOException $e) {
    echo "Connection Failed: " . $e->getMessage();
}
?>
