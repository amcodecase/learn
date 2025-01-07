<?php
require __DIR__ . '/../dbconfig.php';  // Include your DB connection

if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];

    $student_id_check_query = "SELECT id FROM students WHERE student_id = :student_id";
    $stmt = $pdo->prepare($student_id_check_query);
    $stmt->bindParam(':student_id', $student_id);
    $stmt->execute();

    $student_id_exists = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student_id_exists) {
        echo "exists";  // Student ID already exists
    } else {
        echo "available";  // Student ID is available
    }
}
?>
