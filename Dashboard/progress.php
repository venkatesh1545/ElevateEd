<?php
session_start();
require_once "../WebDevelopmentCourse/db.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.html");
    exit();
}

// Handle deleting a skill
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_skill'])) {
    $skill_id = $_POST['delete_skill'];
    $user_id = $_SESSION['user_id'];

    // Delete the skill from the database
    $sql = "DELETE FROM selected_skills WHERE id = :skill_id AND user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':skill_id', $skill_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    // Redirect to refresh the page
    header("Location: progress.php");
    exit();
}

// Fetch selected skills from database
// Fetch skills for the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT id, skill_name FROM selected_skills WHERE user_id = :user_id ORDER BY selected_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$skills = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Tracker</title>
    <link rel="stylesheet" href="progress_styles.css">
    <link href="https://unpkg.com/lucide-icons/dist/umd/lucide.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Left Sidebar -->
        <div class="sidebar">
            <h2>My Progress List</h2>
            <div class="skills-list">
                <?php
                if (!empty($skills)) {
                    foreach ($skills as $skill) {
                        echo '<div class="skill-item">
                                <span>' . htmlspecialchars($skill["skill_name"]) . '</span>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="delete_skill" value="' . $skill["id"] . '">
                                    <button type="submit" class="delete-btn" title="Delete">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                </form>
                            </div>';
                    }
                } else {
                    echo '<p>No skills selected yet.</p>';
                }
                ?>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="resource-section">
                <div class="resource-header">
                    <div class="header-content">
                        <h2>Machine Learning Roadmap</h2>
                        <div class="progress-stats">
                            <div class="progress-bar">
                                <div class="progress" style="width: 40%"></div>
                            </div>
                            <span class="progress-text">40% Complete</span>
                        </div>
                    </div>
                    <p>Your journey to becoming a Machine Learning expert</p>
                </div>

                <!-- Videos Section -->
                <div class="resource-category">
                    <div class="category-header">
                        <div class="category-icon">
                            <i data-lucide="video"></i>
                        </div>
                        <div class="category-info">
                            <h3>Video Courses</h3>
                            <span class="completion-status">2/5 Completed</span>
                        </div>
                    </div>
                    <div class="resource-items">
                        <div class="resource-item completed">
                            <div class="resource-content">
                                <i data-lucide="check-circle"></i>
                                <div class="resource-details">
                                    <span class="resource-title">Python for Machine Learning</span>
                                    <span class="resource-duration">4 hours</span>
                                </div>
                            </div>
                            <span class="status">Completed</span>
                        </div>
                        <div class="resource-item completed">
                            <div class="resource-content">
                                <i data-lucide="check-circle"></i>
                                <div class="resource-details">
                                    <span class="resource-title">Mathematics for ML</span>
                                    <span class="resource-duration">6 hours</span>
                                </div>
                            </div>
                            <span class="status">Completed</span>
                        </div>
                        <div class="resource-item">
                            <div class="resource-content">
                                <i data-lucide="play-circle"></i>
                                <div class="resource-details">
                                    <span class="resource-title">Neural Networks Fundamentals</span>
                                    <span class="resource-duration">8 hours</span>
                                </div>
                            </div>
                        </div>
                        <div class="resource-item">
                            <div class="resource-content">
                                <i data-lucide="play-circle"></i>
                                <div class="resource-details">
                                    <span class="resource-title">Deep Learning with PyTorch</span>
                                    <span class="resource-duration">10 hours</span>
                                </div>
                            </div>
                        </div>
                        <div class="resource-item">
                            <div class="resource-content">
                                <i data-lucide="play-circle"></i>
                                <div class="resource-details">
                                    <span class="resource-title">Computer Vision with CNN</span>
                                    <span class="resource-duration">6 hours</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Articles Section -->
                <div class="resource-category">
                    <div class="category-header">
                        <div class="category-icon">
                            <i data-lucide="book-open"></i>
                        </div>
                        <div class="category-info">
                            <h3>Articles</h3>
                            <span class="completion-status">1/4 Completed</span>
                        </div>
                    </div>
                    <div class="resource-items">
                        <div class="resource-item completed">
                            <div class="resource-content">
                                <i data-lucide="check-circle"></i>
                                <div class="resource-details">
                                    <span class="resource-title">Introduction to Machine Learning</span>
                                    <span class="resource-duration">25 min read</span>
                                </div>
                            </div>
                            <span class="status">Completed</span>
                        </div>
                        <div class="resource-item">
                            <div class="resource-content">
                                <i data-lucide="book"></i>
                                <div class="resource-details">
                                    <span class="resource-title">Understanding Neural Networks</span>
                                    <span class="resource-duration">30 min read</span>
                                </div>
                            </div>
                        </div>
                        <div class="resource-item">
                            <div class="resource-content">
                                <i data-lucide="book"></i>
                                <div class="resource-details">
                                    <span class="resource-title">Deep Learning Best Practices</span>
                                    <span class="resource-duration">20 min read</span>
                                </div>
                            </div>
                        </div>
                        <div class="resource-item">
                            <div class="resource-content">
                                <i data-lucide="book"></i>
                                <div class="resource-details">
                                    <span class="resource-title">ML Model Deployment Guide</span>
                                    <span class="resource-duration">35 min read</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Certifications Section -->
                <div class="resource-category">
                    <div class="category-header">
                        <div class="category-icon">
                            <i data-lucide="award"></i>
                        </div>
                        <div class="category-info">
                            <h3>Certifications</h3>
                            <span class="completion-status">0/3 Completed</span>
                        </div>
                    </div>
                    <div class="resource-items">
                        <div class="resource-item">
                            <div class="resource-content">
                                <i data-lucide="award"></i>
                                <div class="resource-details">
                                    <span class="resource-title">TensorFlow Developer Certificate</span>
                                    <span class="resource-duration">Not started</span>
                                </div>
                            </div>
                        </div>
                        <div class="resource-item">
                            <div class="resource-content">
                                <i data-lucide="award"></i>
                                <div class="resource-details">
                                    <span class="resource-title">Deep Learning Specialization</span>
                                    <span class="resource-duration">Not started</span>
                                </div>
                            </div>
                        </div>
                        <div class="resource-item">
                            <div class="resource-content">
                                <i data-lucide="award"></i>
                                <div class="resource-details">
                                    <span class="resource-title">AWS Machine Learning Certificate</span>
                                    <span class="resource-duration">Not started</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
    lucide.createIcons();
    
    // Add click handlers for skill items
    document.querySelectorAll('.skill-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.skill-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Handle delete button clicks
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this skill?')) {
                e.preventDefault();
            }
        });
    });
</script>
</body>
</html>