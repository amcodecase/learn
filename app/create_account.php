<?php

// Start the session at the top
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php'; // Make sure PHPMailer is installed via Composer
use Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$site_name = getenv('SITE_NAME');
require __DIR__ . '/../dbconfig.php';

// Initialize error variables
$email_error = '';
$student_id_error = '';
$password_match_error = '';
$succes_message = '';

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $student_id = trim($_POST['student_id']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Check if all fields are filled
    if (empty($first_name) || empty($last_name) || empty($email) || empty($student_id) || empty($password) || empty($confirm_password)) {
        echo "All fields are required.";
    } else {
        // Check if passwords match
        if ($password !== $confirm_password) {
            $password_match_error = "Passwords do not match.";
        } else {
            // Check if email already exists
            $email_check_query = "SELECT id FROM students WHERE email = :email";
            $stmt = $pdo->prepare($email_check_query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $email_exists = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($email_exists) {
                $email_error = "Email is already registered.";
            } else {
                // Check if student ID already exists
                $student_id_check_query = "SELECT id FROM students WHERE student_id = :student_id";
                $stmt = $pdo->prepare($student_id_check_query);
                $stmt->bindParam(':student_id', $student_id);
                $stmt->execute();
                $student_id_exists = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($student_id_exists) {
                    $student_id_error = "Student ID is already registered.";
                } else {
                    // Hash the password before storing it
                    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                    // Insert the student data into the database
                    $insert_query = "INSERT INTO students (first_name, last_name, email, student_id, password) 
                                    VALUES (:first_name, :last_name, :email, :student_id, :password)";
                    $stmt = $pdo->prepare($insert_query);
                    $stmt->bindParam(':first_name', $first_name);
                    $stmt->bindParam(':last_name', $last_name);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':student_id', $student_id);
                    $stmt->bindParam(':password', $hashed_password);
                    $stmt->execute();

                    $succes_message = "Account created successfully!";

                    // Set the session variable after successful account creation
                    $_SESSION['account_created'] = "Your account has been created successfully. You can now log in.";

                    // Send confirmation email via PHPMailer
                    $mail = new PHPMailer(true);
                    try {
         

                        //Recipients
                        $mail->setFrom('learn@natec.icu', 'Checkmyca');
                        $mail->addAddress($email, $first_name . ' ' . $last_name); // Add recipient email

// Content
$mail->isHTML(true);
$mail->Subject = 'Account Created Successfully';
$mail->Body    = '
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                color: #333;
                background-color: #f4f4f4;
                padding: 20px;
            }
            .email-container {
                width: 100%;
                max-width: 600px;
                margin: 0 auto;
                background-color: #ffffff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            .header {
                text-align: center;
                margin-bottom: 20px;
            }
            .header h1 {
                color: #4CAF50;
                font-size: 28px;
                margin: 0;
            }
            .message {
                font-size: 16px;
                line-height: 1.5;
                margin-bottom: 20px;
            }
            .button {
                display: inline-block;
                background-color: #4CAF50;
                color: white;
                font-size: 16px;
                padding: 12px 24px;
                text-align: center;
                text-decoration: none;
                border-radius: 5px;
                margin: 20px 0;
                transition: background-color 0.3s ease;
            }
            .button:hover {
                background-color: #45a049;
            }
            .footer {
                font-size: 14px;
                color: #888;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="email-container">
            <div class="header">
                <h1>Account Created Successfully!</h1>
            </div>
            <div class="message">
                <p>Dear ' . $first_name . ',</p>
                <p>Your account has been successfully created. You can now log in to your account and start exploring our platform.</p>
                <p>Click the button below to proceed to the login page:</p>
                <a href="https://learn.natec.icu" class="button">Go to Login</a>
            </div>
            <div class="footer">
                <p>Best Regards,</p>
                <p>' . $site_name . '</p>
            </div>
        </div>
    </body>
    </html>
';

$mail->send();

                        $mail->send();
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }

                    // Redirect to login page after success
                    header('Location: ../login.php');
                    exit();
                }
            }
        }
    }
}
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
            <?php if ($succes_message): ?>
                <div class="success-message" style="color: green;">
                    <p><?= $succes_message; ?></p>
                </div>
            <?php endif; ?>

            <?php if ($email_error || $student_id_error || $password_match_error): ?>
                <div class="error-messages" style="color: red;">
                    <?php
                    echo $email_error ? "<p>$email_error</p>" : '';
                    echo $student_id_error ? "<p>$student_id_error</p>" : '';
                    echo $password_match_error ? "<p>$password_match_error</p>" : '';
                    ?>
                </div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="form-row">
                    <input type="text" name="first_name" id="first_name" placeholder="Enter your first name" required>
                    <input type="text" name="last_name" id="last_name" placeholder="Enter your last name" required>
                </div>

                <div class="form-row">
                    <input type="email" name="email" id="email" placeholder="Enter your email" required>
                    <input type="text" name="student_id" id="student_id" placeholder="Enter your student ID" required>
                </div>

                <div class="form-row">
                    <input type="password" name="password" id="password" placeholder="Create your password" required>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your password" required>
                </div>

                <input type="submit" value="Create Account">
            </form>

            <div class="redirects">
                <p>
                    Already have an account? <a href="../login.php">Login here</a>
                </p>
            </div>
        </div>

        <div class="copyright">
            <p>&copy; <?php echo date('Y'); ?> <?= htmlspecialchars($site_name); ?>. All Rights Reserved</p>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const emailField = document.getElementById('email');
            const studentIdField = document.getElementById('student_id');
            const passwordField = document.getElementById('password');
            const confirmPasswordField = document.getElementById('confirm_password');
            const emailError = document.getElementById('email-error');
            const studentIdError = document.getElementById('student-id-error');
            const passwordMatchError = document.getElementById('password-match-error');

            // Real-time email check
            emailField.addEventListener('blur', function() {
                const email = emailField.value;
                if (email) {
                    fetch('check_email.php?email=' + email)
                        .then(response => response.text())
                        .then(data => {
                            if (data === 'exists') {
                                emailError.textContent = "Email is already registered.";
                                emailError.style.display = 'block';
                            } else {
                                emailError.textContent = "";
                                emailError.style.display = 'none';
                            }
                        });
                }
            });

            // Real-time student ID check
            studentIdField.addEventListener('blur', function() {
                const studentId = studentIdField.value;
                if (studentId) {
                    fetch('check_student_id.php?student_id=' + studentId)
                        .then(response => response.text())
                        .then(data => {
                            if (data === 'exists') {
                                studentIdError.textContent = "Student ID is registered.";
                                studentIdError.style.display = 'block';
                            } else {
                                studentIdError.textContent = "";
                                studentIdError.style.display = 'none';
                            }
                        });
                }
            });

            // Real-time password match check
            confirmPasswordField.addEventListener('input', function() {
                const password = passwordField.value;
                const confirmPassword = confirmPasswordField.value;
                if (password !== confirmPassword) {
                    passwordMatchError.textContent = "Passwords do not match.";
                    passwordMatchError.style.display = 'block';
                } else {
                    passwordMatchError.textContent = "";
                    passwordMatchError.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
