<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);

// require 'err_report.php';
require __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');  // Move one level up to the root directory
$dotenv->load();

$site_name = getenv('SITE_NAME');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($site_name); ?> - Create Account</title>
    <link rel="stylesheet" href="src/css/create_account.css">
</head>
<body>
    <div class="main-content">
        <div class="logo">
            <img src="src/img/logo.png" alt="Check List Logo">
        </div>
        <div class="header">
            <!-- <h1><?= htmlspecialchars($site_name); ?> - Create Account</h1> -->
        </div>
        <div class="create-account-form">
            <h2>Create a New Account</h2>
            <form action="#" method="post">
                <!-- Names First -->
                <input type="text" name="first_name" id="first_name" placeholder="Enter your first name" required>
                <input type="text" name="last_name" id="last_name" placeholder="Enter your last name" required>

                <!-- Then the other fields -->
                <input type="email" name="email" id="email" placeholder="Enter your email" required>
                <input type="text" name="student_id" id="student_id" placeholder="Enter your student ID" required>

                <!-- Password and Confirm Password -->
                <input type="password" name="password" id="password" placeholder="Create your password" required>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your password" required>

                <input type="submit" value="Create Account">
            </form>
            <div class="redirects">
                <p>
                    Already have an account? <a href="../">Login here</a>
                </p>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; <?php echo date('Y'); ?> <?= htmlspecialchars($site_name); ?>. All Rights Reserved</p>
        </div>
    </div>
</body>
</html>
