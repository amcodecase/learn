<?php 
// Start the session at the top
session_start();

// Include database connection
require 'dbconfig.php';

// Include your .env file
require 'vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$site_name = getenv('SITE_NAME');

// Check if session has the account creation message
$account_created_message = isset($_SESSION['account_created']) ? $_SESSION['account_created'] : null;

// Unset the session variable to prevent the message from showing again on refresh
unset($_SESSION['account_created']);

// Handle the login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_id = $_POST['login-id'];
    $password = $_POST['password'];

    try {
        // Prepare a statement to fetch the user with the matching login_id
        $stmt = $pdo->prepare("SELECT * FROM students WHERE email = :login_id OR student_id = :login_id");
        $stmt->execute(['login_id' => $login_id]);

        // Fetch the user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists and password matches
        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['id']; // Store user ID for reference
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];

            // Redirect to /app/index.php
            header('Location: app/index.php');
            exit();
        } else {
            // Handle invalid login attempt
            $login_error_message = "Invalid login credentials. Please try again.";
        }
    } catch (PDOException $e) {
        // Handle database errors
        $login_error_message = "Database error: " . $e->getMessage();
    }
}
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
            
            <!-- Display account creation success message if set -->
            <?php if ($account_created_message): ?>
                <div class="success-message" style="color: green;">
                    <p><?= $account_created_message; ?></p>
                </div>
            <?php endif; ?>

            <!-- Display login error message if set -->
            <?php if (isset($login_error_message)): ?>
                <div class="error-message" style="color: red;">
                    <p><?= $login_error_message; ?></p>
                </div>
            <?php endif; ?>

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
