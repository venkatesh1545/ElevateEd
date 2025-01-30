<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.html");
    exit();
}

// Get user data from session
$fullName = $_SESSION['full_name'];
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Developer Hub</title>
    <link rel="stylesheet" href="UD_styles.css">
</head>
<body>
    <header class="top-nav">
        <div class="nav-left">
            <div class="logo">DevHub</div>
            <nav class="main-nav">
                <a href="#" class="nav-link active">Learn</a>
                <a href="#" class="nav-link">Compete</a>
                <a href="#" class="nav-link">Support</a>
            </nav>
        </div>
        <div class="nav-right">
            <a href="#" class="sign-in">Sign In</a>
        </div>
    </header>

    <div class="container">
        <aside class="sidebar">
            <nav class="side-nav">
                <a href="#" class="nav-item active">
                    <span class="nav-icon">🏠</span>
                    <span>Dashboard</span>
                </a>
                <div class="nav-item-wrapper">
                    <a href="#" class="nav-item">
                        <span class="nav-icon">📚</span>
                        <span>Learning Path</span>
                        <span class="submenu-arrow">▾</span>
                    </a>
                    <div class="submenu">
                        <a href="#" class="submenu-item">
                            <span class="nav-icon">📝</span>
                            <span>Summarizer</span>
                        </a>
                        <a href="#" class="submenu-item">
                            <span class="nav-icon">📊</span>
                            <span>Progress Work</span>
                        </a>
                    </div>
                </div>
                <a href="#" class="nav-item">
                    <span class="nav-icon">📝</span>
                    <span>Resume Builder</span>
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="header-section">
                <h1 class="page-title">Developer Dashboard</h1>
                <div class="header-actions">
                    <button class="action-button">
                        <span class="button-icon">+</span>
                        New Project
                    </button>
                </div>
            </div>

            <div class="content-grid">
                <div class="profile-card glass-card">
                    <div class="profile-header">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80" alt="Profile Picture" class="profile-image">
                        <div class="profile-info">
                            <h2 id="full-name"><?php echo htmlspecialchars($fullName); ?></h2>
                            <p class="username" id="user-name">@<?php echo htmlspecialchars($username); ?></p>
                            <p class="institution">Stanford University</p>
                        </div>
                    </div>
                    
                    <div class="skills">
                        <span class="skill react">React</span>
                        <span class="skill nodejs">Node.js</span>
                        <span class="skill python">Python</span>
                    </div>

                    <button class="view-profile">View Full Profile</button>

                    <div class="social-links">
                        <a href="#" class="social-link">
                            <span class="social-icon">in</span>
                            LinkedIn Profile
                            <span class="arrow">→</span>
                        </a>
                        <a href="#" class="social-link">
                            <span class="social-icon">gh</span>
                            GitHub Profile
                            <span class="arrow">→</span>
                        </a>
                    </div>

                    <div class="contribution-dots">
                        <div class="dot" data-level="4"></div>
                        <div class="dot" data-level="2"></div>
                        <div class="dot" data-level="5"></div>
                        <div class="dot" data-level="3"></div>
                        <div class="dot" data-level="1"></div>
                        <div class="dot" data-level="4"></div>
                        <div class="dot" data-level="5"></div>
                    </div>
                </div>

                <div class="goals-card glass-card">
                    <div class="card-header">
                        <span class="card-icon">🎯</span>
                        <h3>Current Goals</h3>
                    </div>
                    <div class="progress-container">
                        <div class="progress-ring">
                            <svg class="progress-ring__circle" width="120" height="120">
                                <circle class="progress-ring__circle-bg" cx="60" cy="60" r="54"/>
                                <circle class="progress-ring__circle-progress" cx="60" cy="60" r="54"/>
                            </svg>
                            <span class="progress-text">70%</span>
                        </div>
                    </div>
                    <p class="goal-text">Complete React Advanced Course</p>
                </div>

                <div class="activity-card glass-card">
                    <h3>Recent Activity</h3>
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-status completed">
                                <span class="status-icon">✓</span>
                            </div>
                            <div class="activity-content">
                                <p>Completed JavaScript Basics</p>
                                <span class="activity-time">2 hours ago</span>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-status started">
                                <span class="status-icon">→</span>
                            </div>
                            <div class="activity-content">
                                <p>Started React Course</p>
                                <span class="activity-time">1 day ago</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <section class="contributions-section glass-card">
                <h3>Yearly Contributions</h3>
                <div class="contribution-graph">
                    <div class="graph-bar" style="--height: 30%"></div>
                    <div class="graph-bar" style="--height: 45%"></div>
                    <div class="graph-bar" style="--height: 25%"></div>
                    <div class="graph-bar" style="--height: 60%"></div>
                    <div class="graph-bar" style="--height: 35%"></div>
                    <div class="graph-bar" style="--height: 70%"></div>
                    <div class="graph-bar" style="--height: 40%"></div>
                    <div class="graph-bar" style="--height: 55%"></div>
                    <div class="graph-bar" style="--height: 30%"></div>
                    <div class="graph-bar" style="--height: 65%"></div>
                    <div class="graph-bar" style="--height: 45%"></div>
                    <div class="graph-bar" style="--height: 80%"></div>
                </div>
            </section>
        </main>
    </div>
    <script>
    // Fallback JavaScript to ensure user data is displayed
    window.onload = function() {
        // Check if we have data in sessionStorage (from signin.php)
        const storedUsername = sessionStorage.getItem('username');
        const storedFullName = sessionStorage.getItem('full_name');
        
        if (storedUsername && storedFullName) {
            document.getElementById('user-name').textContent = '@' + storedUsername;
            document.getElementById('full-name').textContent = storedFullName;
        }
    }
    </script>
</body>
</html>