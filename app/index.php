<?php
// After
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_logged_in']) || !$_SESSION['user_logged_in']) {
    // If not logged in, redirect to the login page
    header('Location: ../index.php');
    exit();
}

require_once '../dbconfig.php';

// Fetch courses from the database
$query_courses = "SELECT * FROM courses";
$stmt_courses = $pdo->query($query_courses);
$courses = $stmt_courses->fetchAll(PDO::FETCH_ASSOC);

// Fetch years from the database
$query_years = "SELECT DISTINCT year FROM courses ORDER BY year DESC";
$stmt_years = $pdo->query($query_years);
$years = $stmt_years->fetchAll(PDO::FETCH_ASSOC);

// Fetch CA data
$query_ca = "SELECT * FROM ca_scores WHERE user_id = :user_id"; // Assuming user_id is stored in the session
$stmt_ca = $pdo->prepare($query_ca);
$stmt_ca->execute(['user_id' => $_SESSION['user_id']]);
$ca_scores = $stmt_ca->fetchAll(PDO::FETCH_ASSOC);

// Fetch profile details
$query_profile = "SELECT * FROM students WHERE id = :user_id"; // Assuming students table
$stmt_profile = $pdo->prepare($query_profile);
$stmt_profile->execute(['user_id' => $_SESSION['user_id']]);
$profile = $stmt_profile->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($site_name); ?> - Dashboard</title>
    <link rel="stylesheet" href="src/css/home.css">
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f9f9f9;
        }
        .main-content {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 1.8rem;
            margin: 0;
        }
        .header .user-info {
            display: flex;
            align-items: center;
            font-size: 1rem;
        }
        .header .user-info img {
            border-radius: 50%;
            margin-right: 10px;
            width: 40px;
            height: 40px;
        }
        .btn-submit {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-submit:hover {
            background-color: #45a049;
        }
        .course-selection, .ca-section, .profile-section {
            margin-bottom: 30px;
        }
        .profile-section {
            display: none;
        }
        table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .user-info a {
            color: #007bff;
            text-decoration: none;
        }
        .user-info a:hover {
            text-decoration: underline;
        }
        .ca-section h2, .profile-section h2 {
            font-size: 1.4rem;
            margin-bottom: 10px;
        }
        .ca-section table, .ca-section p {
            margin-top: 10px;
        }
        .user-info p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="header">
            <h1>Welcome to Your Dashboard</h1>
            <div class="user-info">
                <img src="path/to/user-avatar.jpg" alt="User Avatar">
                <p>Hello, <?= htmlspecialchars($_SESSION['user_name']); ?>!</p>
                <a href="../logout.php">Logout</a>
            </div>
        </div>

        <div class="ca-section">
            <h2>Your Continuous Assessment (CA) Scores</h2>
            <?php if (!empty($ca_scores)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Course Name</th>
                            <th>Year</th>
                            <th>CA Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ca_scores as $score): ?>
                            <tr>
                                <td><?= htmlspecialchars($score['course_name']); ?></td>
                                <td><?= htmlspecialchars($score['year']); ?></td>
                                <td><?= htmlspecialchars($score['ca_score']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>You have no CA scores yet.</p>
            <?php endif; ?>
        </div>

        <div class="course-selection">
            <form action="submit_course.php" method="post">
                <label for="course">Select Course:</label>
                <select name="course" id="course" required>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= htmlspecialchars($course['id']); ?>"><?= htmlspecialchars($course['course_name']); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="year">Select Year:</label>
                <select name="year" id="year" required>
                    <?php foreach ($years as $year): ?>
                        <option value="<?= htmlspecialchars($year['year']); ?>"><?= htmlspecialchars($year['year']); ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="btn-submit">Register for Course</button>
            </form>
        </div>

        <div class="profile-section" id="profile-section">
            <h2>Your Profile</h2>
            <p><strong>First Name:</strong> <?= htmlspecialchars($profile['first_name']); ?></p>
            <p><strong>Last Name:</strong> <?= htmlspecialchars($profile['last_name']); ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($profile['email']); ?></p>
            <p><strong>Student ID:</strong> <?= htmlspecialchars($profile['student_id']); ?></p>
            <a href="edit_profile.php">Edit Profile</a>
        </div>

        <div class="user-info">
            <a href="javascript:void(0)" onclick="toggleProfile()">Edit Profile</a>
        </div>
    </div>

    <script>
        function toggleProfile() {
            const profileSection = document.getElementById('profile-section');
            profileSection.style.display = profileSection.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>
