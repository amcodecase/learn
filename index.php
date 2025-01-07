<?php 

// require 'err_report.php';
require 'vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$site_name = getenv('SITE_NAME');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($site_name); ?></title>
    <link rel="stylesheet" href="app/src/css/index.css">
</head>
<body>
    <div class="main-content">
        <div class="logo">
            <img src="app/src/img/logo.png" alt="Check List Logo">
        </div>
        <div class="header">
            <!-- <h1><?= htmlspecialchars($site_name); ?></h1> -->
        </div>
        <div class="login-form">
            <h2>Login to Proceed</h2>
            <form action="#" method="post">
                <!-- <label for="login-id">Email or Student ID</label> -->
                <input type="text" name="login-id" id="login-id" placeholder="Enter your email or student ID" required>
                <!-- <label for="password">Password</label> -->
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
                <input type="submit" value="Login">
            </form>
            <div class="redirects">
                <p>
                    <a href="app/reset_password.php">Reset Password</a> | <a href="app/create_account.php">Create Account</a>
                </p>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; <?php echo date('Y'); ?> <?= htmlspecialchars($site_name); ?>. All Rights Reserved</p>
        </div>
    </div>
</body>
</html>
