<?php
$host = '127.0.0.1'; 
$dbname = 'learn_db';
$username = 'case';
$password = 'amcodecase';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
