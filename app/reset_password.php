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
    <title><?= htmlspecialchars($site_name); ?> - Reset Password</title>
    <link rel="stylesheet" href="src/css/reset_password.css">
</head>
<body>
    <div class="main-content">
        <div class="logo">
            <img src="src/img/logo.png" alt="Check List Logo">
        </div>
        <div class="header">
            <!-- <h1><?= htmlspecialchars($site_name); ?> - Reset Password</h1> -->
        </div>
        <div class="reset-password-form">
            <h2>Reset Your Password</h2>
            <form action="#" method="post">
                <!-- Choose to reset using Email or Student ID -->
                <!-- <label for="reset-id">Enter your Email or Student ID</label> -->
                <input type="text" name="reset-id" id="reset-id" placeholder="Enter your email or student ID" required>

                <!-- Submit -->
                <input type="submit" value="Reset Password">
            </form>
            <div class="redirects">
                <p>
                    Remembered your password? <a href="../">Login here</a>
                </p>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; <?php echo date('Y'); ?> <?= htmlspecialchars($site_name); ?>. All Rights Reserved</p>
        </div>
    </div>
</body>
</html>
