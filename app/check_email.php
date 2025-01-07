<?php
require __DIR__ . '/../dbconfig.php'; 
if (isset($_GET['email'])) {
    $email = $_GET['email'];

    $email_check_query = "SELECT id FROM students WHERE email = :email";
    $stmt = $pdo->prepare($email_check_query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $email_exists = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($email_exists) {
        echo "exists";  // Email already exists
    } else {
        echo "available";  // Email is available
    }
}
?>
