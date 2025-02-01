<?php
session_start();
require_once "../WebDevelopmentCourse/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../signin.html");
    exit();
}

// Fetch user data for the header
$user_stmt = $pdo->prepare("
    SELECT u.*, up.profile_image, up.college_name 
    FROM users u 
    LEFT JOIN user_profiles up ON u.id = up.user_id 
    WHERE u.id = ?
");
$user_stmt->execute([$_SESSION['user_id']]);
$user = $user_stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_skill'])) {
    $skill_name = $_POST['skill_name'];
    $skill_category = $_POST['skill_category'];
    $user_id = $_SESSION['user_id'];
    
    // Check if skill already exists
    $check_sql = "SELECT id FROM selected_skills WHERE skill_name = :skill_name AND user_id = :user_id";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->bindParam(':skill_name', $skill_name, PDO::PARAM_STR);
    $check_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $check_stmt->execute();
    $result = $check_stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$result) {
        // Insert new skill into the database
        $sql = "INSERT INTO selected_skills (skill_name, skill_category, user_id) VALUES (:skill_name, :skill_category, :user_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':skill_name', $skill_name, PDO::PARAM_STR);
        $stmt->bindParam(':skill_category', $skill_category, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    header("Location: landing_page.php");
    exit();
}

// Get count of selected skills
$count_sql = "SELECT COUNT(*) as count FROM selected_skills WHERE user_id = ?";
$count_stmt = $pdo->prepare($count_sql);
$count_stmt->execute([$_SESSION['user_id']]);
$skill_count = $count_stmt->fetch(PDO::FETCH_ASSOC)['count'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skills Roadmap</title>
    <link href="https://unpkg.com/lucide-icons/dist/umd/lucide.css" rel="stylesheet">
    <link href="landing_styles.css" rel="stylesheet">
    <link href="header_styles.css" rel="stylesheet">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container">
    <style>
            .container {
                margin-top: 80px; /* Adjust based on your header height */
            }
        </style>
        <div class="header">
            <div class="logo">
                <i data-lucide="home"></i>
            </div>
            <h1>Choose Your Skill & Start Your Journey!</h1>
            <p class="subtitle">Select the skills you want to master and embark on a structured learning path.</p>
        </div>

        <div class="content-grid">
            <!-- <div class="skills-grid">
                <div class="skill-card">
                    <div class="skill-header">
                        <div class="icon-container">
                            <i data-lucide="code-2"></i>
                        </div>
                        <div class="skill-info">
                            <h3>Web Development</h3>
                            <p>Master modern web development with HTML, CSS, and JavaScript</p>
                        </div>
                    </div>
                </div>

                <div class="skill-card">
                    <div class="skill-header">
                        <div class="icon-container">
                            <i data-lucide="brain"></i>
                        </div>
                        <div class="skill-info">
                            <h3>Artificial Intelligence</h3>
                            <p>Learn AI concepts, neural networks, and deep learning</p>
                        </div>
                    </div>
                </div>

                <div class="skill-card">
                    <div class="skill-header">
                        <div class="icon-container">
                            <i data-lucide="cpu"></i>
                        </div>
                        <div class="skill-info">
                            <h3>Machine Learning</h3>
                            <p>Explore data science and machine learning algorithms</p>
                        </div>
                    </div>
                </div>

                <div class="skill-card">
                    <div class="skill-header">
                        <div class="icon-container">
                            <i data-lucide="layout-list"></i>
                        </div>
                        <div class="skill-info">
                            <h3>React</h3>
                            <p>Build modern web applications with React</p>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="skills-grid">
                <?php
                
                $skills = [
                    ['Web Development', 'code-2', 'Master modern web development with HTML, CSS, and JavaScript'],
                    ['Artificial Intelligence', 'brain', 'Learn AI concepts, neural networks, and deep learning'],
                    ['Machine Learning', 'cpu', 'Explore data science and machine learning algorithms'],
                    ['React', 'layout-list', 'Build modern web applications with React']
                ];

                foreach ($skills as $skill) {
                    echo '<div class="skill-card" data-skill="' . $skill[0] . '">
                            <div class="skill-header">
                                <div class="icon-container">
                                    <i data-lucide="' . $skill[1] . '"></i>
                                </div>
                                <div class="skill-info">
                                    <h3>' . $skill[0] . '</h3>
                                    <p>' . $skill[2] . '</p>
                                </div>
                            </div>
                        </div>';
                }
                ?>
            </div>
            <!-- Progress card section -->
            <div class="progress-card">
                <div class="progress-header">
                    <i data-lucide="activity"></i>
                    <h3>Your Progress</h3>
                </div>

                <div class="progress-bar-container">
                    <div class="progress-bar-header">
                        <span>Overall Progress</span>
                        <span>0%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill"></div>
                    </div>
                </div>

                <div class="progress-stats">
                    <div class="stat-box">
                        <div class="stat-number"><?php echo $skill_count; ?></div>
                        <div class="stat-label">Skills Selected</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number"><?php echo $skill_count; ?></div>
                        <div class="stat-label">In Progress</div>
                    </div>
                </div>
                <button class="view-progress-btn" onclick="viewProgress()">View My Progress List</button>
            </div>

                <!-- <div class="progress-stats">
                    <div class="stat-box">
                        <div class="stat-number">0</div>
                        <div class="stat-label">Skills Selected</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">0</div>
                        <div class="stat-label">In Progress</div>
                    </div>
                </div> -->

                <!-- <div class="progress-stats">
                    <div class="stat-box">
                        <div class="stat-number"><?php echo $skill_count; ?></div>
                        <div class="stat-label">Skills Selected</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number"><?php echo $skill_count; ?></div>
                        <div class="stat-label">In Progress</div>
                    </div>
                </div>
                <button class="view-progress-btn" onclick="viewProgress()">View My Progress List</button> -->

            </div>
        </div>


        <!-- Roadmap sections -->
    
            <!-- Web Development Roadmap -->
            <?php
            // Define roadmap data for each skill
            $roadmapData = [
                'Web Development' => [
                    'Fundamentals' => ['HTML5', 'CSS3', 'JavaScript ES6+', 'Git Basics', 'Web Protocols'],
                    'Frontend Development' => ['React', 'Vue.js', 'State Management', 'CSS Frameworks', 'Web Performance'],
                    'Backend Development' => ['Node.js', 'Express', 'Databases', 'API Design', 'Authentication']
                ],
                'Artificial Intelligence' => [
                    'AI Basics' => ['Introduction to AI', 'Mathematical Foundations', 'Search Algorithms & Heuristics'],
                    'Machine Learning & Deep Learning' => ['Supervised & Reinforcement Learning', 'Deep Learning Architectures', 'Ethics in AI'],
                    'AI Applications' => ['Natural Language Processing (NLP)', 'Computer Vision', 'AI in Robotics']
                ],
                'Machine Learning' => [
                    'Fundamentals' => ['Introduction to Machine Learning', 'Mathematical Foundations', 'Python Programming Basics', 'Data Preprocessing'],
                    'Intermediate Topics' => ['Feature Engineering', 'Model Training', 'Decision Trees', 'K-Means Clustering'],
                    'Advanced Topics' => ['Deep Learning', 'Convolutional Neural Networks', 'Transfer Learning', 'Deployment of ML Models']
                ],
                'React' => [
                    'Fundamentals' => ['Introduction to React', 'JSX Syntax', 'Components and Props', 'State Management (useState)', 'Handling Events'],
                    'Intermediate Topics' => ['React Router', 'Context API', 'Hooks', 'Styling in React', 'Form Handling'],
                    'Advanced Topics' => ['Redux', 'React Query', 'Server-Side Rendering', 'Testing React Applications']
                ],

                // Add more skills and their roadmaps here as needed
            ];

            // Example skills array (replace this with your actual skills data)
            $skills = [
                ['Web Development', 'code-2'],
                ['Artificial Intelligence', 'brain'],
                ['Machine Learning', 'cpu'],
                ['React', 'layout-list']
            ];
            ?>

            <!-- Roadmap sections -->
            <div class="roadmap-sections">
                <?php
                foreach ($skills as $index => $skill) {
                    $skillName = $skill[0]; // Skill name (e.g., "Web Development")
                    $skillIcon = $skill[1]; // Icon name (e.g., "code-2")

                    // Check if roadmap data exists for this skill
                    if (isset($roadmapData[$skillName])) {
                        $sections = $roadmapData[$skillName]; // Get roadmap sections for this skill
                    } else {
                        $sections = []; // Default empty sections if no data exists
                    }

                    echo '<div class="roadmap-container" id="roadmap-' . $index . '" style="display: none;">
                            <div class="roadmap-header">
                                <div class="icon-container">
                                    <i data-lucide="' . $skillIcon . '"></i>
                                </div>
                                <h3>' . $skillName . ' Roadmap</h3>
                                <form method="POST" class="skill-form">
                                    <input type="hidden" name="skill_name" value="' . $skillName . '">
                                    <input type="hidden" name="skill_category" value="' . $skillIcon . '">
                                    <button type="submit" name="add_skill" class="add-skill-btn">Add to Progress List</button>
                                </form>
                            </div>
                            <div class="roadmap-content">';
                    
                    // Render roadmap sections
                    foreach ($sections as $sectionTitle => $topics) {
                        echo '<div class="roadmap-section">
                                <h4>' . $sectionTitle . '</h4>
                                <ul class="topics-grid">';
                        
                        // Render topics under each section
                        foreach ($topics as $topic) {
                            echo '<li><i data-lucide="chevron-right"></i>' . $topic . '</li>';
                        }
                        
                        echo '</ul>
                            </div>';
                    }

                    echo '</div>
                        </div>';
                }
                ?>
            </div>

        <div class="roadmap-sections">
            <!-- Machine Learning Roadmap -->
            <div class="roadmap-container">
                <div class="roadmap-header">
                    <div class="icon-container">
                        <i data-lucide="brain"></i>
                    </div>
                    <h3>Machine Learning Roadmap</h3>
                    <button class="add-skill-btn">Add to progress list</button>
                </div>
                <div class="roadmap-content">
                    <div class="roadmap-section">
                        <h4>Fundamentals</h4>
                        <ul class="topics-grid">
                            <li><i data-lucide="chevron-right"></i>Mathematics (Linear Algebra, Probability)</li>
                            <li><i data-lucide="chevron-right"></i>Statistics</li>
                            <li><i data-lucide="chevron-right"></i>Python & Libraries (NumPy, Pandas, Matplotlib)</li>
                        </ul>
                    </div>
                    <div class="roadmap-section">
                        <h4>Core Machine Learning</h4>
                        <ul class="topics-grid">
                            <li><i data-lucide="chevron-right"></i>Supervised & Unsupervised Learning</li>
                            <li><i data-lucide="chevron-right"></i>Feature Engineering</li>
                            <li><i data-lucide="chevron-right"></i>Model Evaluation & Hyperparameter Tuning</li>
                        </ul>
                    </div>
                    <div class="roadmap-section">
                        <h4>Deep Learning</h4>
                        <ul class="topics-grid">
                            <li><i data-lucide="chevron-right"></i>Neural Networks</li>
                            <li><i data-lucide="chevron-right"></i>TensorFlow & PyTorch</li>
                            <li><i data-lucide="chevron-right"></i>Computer Vision & NLP</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="roadmap-sections">
            <!-- React Roadmap -->
            <div class="roadmap-container">
                <div class="roadmap-header">
                    <div class="icon-container">
                        <i data-lucide="layout-list"></i>
                    </div>
                    <h3>React Roadmap</h3>
                    <button class="add-skill-btn">Add to progress list</button>
                </div>
                <div class="roadmap-content">
                    <div class="roadmap-section">
                        <h4>Fundamentals</h4>
                        <ul class="topics-grid">
                            <li><i data-lucide="chevron-right"></i>JavaScript (ES6+)</li>
                            <li><i data-lucide="chevron-right"></i>JSX & Components</li>
                            <li><i data-lucide="chevron-right"></i>State & Props</li>
                        </ul>
                    </div>
                    <div class="roadmap-section">
                        <h4>React Core Concepts</h4>
                        <ul class="topics-grid">
                            <li><i data-lucide="chevron-right"></i>Hooks (useState, useEffect, useContext)</li>
                            <li><i data-lucide="chevron-right"></i>Routing with React Router</li>
                            <li><i data-lucide="chevron-right"></i>State Management (Redux, Context API)</li>
                        </ul>
                    </div>
                    <div class="roadmap-section">
                        <h4>Advanced Topics</h4>
                        <ul class="topics-grid">
                            <li><i data-lucide="chevron-right"></i>Server-side Rendering (Next.js)</li>
                            <li><i data-lucide="chevron-right"></i>Performance Optimization</li>
                            <li><i data-lucide="chevron-right"></i>Testing with Jest & React Testing Library</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="header_script.js"></script>
    <script>
        lucide.createIcons();
        
        document.addEventListener("DOMContentLoaded", function() {
            const skillCards = document.querySelectorAll(".skill-card");
            
            skillCards.forEach(card => {
                card.addEventListener("click", function() {
                    const form = this.querySelector(".skill-form");
                    form.style.display = form.style.display === "none" ? "block" : "none";
                });
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Select all roadmap containers
            const roadmapContainers = document.querySelectorAll(".roadmap-container");

            // Add click event listeners to each container
            roadmapContainers.forEach((container) => {
                container.addEventListener("click", function () {
                    // Toggle the display of the roadmap content
                    const roadmapContent = container.querySelector(".roadmap-content");
                    if (roadmapContent.style.display === "none" || roadmapContent.style.display === "") {
                        roadmapContent.style.display = "block";
                    } else {
                        roadmapContent.style.display = "none";
                    }
                });
            });
        });
        lucide.createIcons();
        document.addEventListener("DOMContentLoaded", function() {
            const skillCards = document.querySelectorAll(".skill-card");
            const roadmaps = document.querySelectorAll(".roadmap-container");

            // Hide all roadmaps initially
            roadmaps.forEach(roadmap => {
                roadmap.style.display = "none";
            });

            skillCards.forEach((card, index) => {
                card.addEventListener("click", () => {
                    // Remove highlight from all skill cards
                    skillCards.forEach(c => c.style.border = "none");

                    // Highlight the selected skill card
                    card.style.border = "3px solid #f1981d";

                    // Hide all roadmaps
                    roadmaps.forEach(roadmap => roadmap.style.display = "none");

                    // Show the corresponding roadmap
                    if (roadmaps[index]) {
                        roadmaps[index].style.display = "block";
                    }
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            const skillCards = document.querySelectorAll(".skill-card");
            const roadmapSections = document.querySelectorAll(".roadmap-container");
            const progressCount = document.querySelector(".progress-stats .stat-box .stat-number");

            let selectedSkills = 0;

            // Hide all roadmaps initially
            roadmapSections.forEach(section => {
                section.style.display = "none";
            });

            skillCards.forEach((card, index) => {
                card.addEventListener("click", function () {
                    const roadmap = roadmapSections[index];

                    if (roadmap.style.display === "none") {
                        roadmap.style.display = "block";

                        // Check if Add Skill button exists
                        if (!roadmap.querySelector(".add-skill-btn")) {
                            const addSkillButton = document.createElement("button");
                            addSkillButton.textContent = "Add Skill";
                            addSkillButton.classList.add("add-skill-btn");
                            addSkillButton.style.backgroundColor = "#f1981d";
                            addSkillButton.style.color = "#fff";
                            addSkillButton.style.border = "none";
                            addSkillButton.style.padding = "10px 15px";
                            addSkillButton.style.marginTop = "10px";
                            addSkillButton.style.cursor = "pointer";
                            addSkillButton.style.borderRadius = "5px";
                            addSkillButton.style.float = "right";

                            addSkillButton.addEventListener("click", function () {
                                selectedSkills++;
                                progressCount.textContent = selectedSkills;
                                addSkillButton.remove(); // Remove button after adding skill
                            });

                            roadmap.appendChild(addSkillButton);
                        }
                    }
                });
            });
        });
        document.addEventListener("DOMContentLoaded", function () {
            const skillCards = document.querySelectorAll(".skill-card");
            const roadmapSections = document.querySelectorAll(".roadmap-container");
            const progressCount = document.querySelector(".progress-stats .stat-box .stat-number");
            const progressList = document.querySelector(".progress-list");

            let selectedSkills = 0;
            let addedSkills = new Set(); // Track added skills

            // Hide all roadmaps initially
            roadmapSections.forEach(section => {
                section.style.display = "none";
            });

            skillCards.forEach((card, index) => {
                card.addEventListener("click", function () {
                    const skillName = card.querySelector(".skill-title").textContent.trim();
                    const roadmap = roadmapSections[index];

                    // Display roadmap at the right corner
                    roadmap.style.display = "block";
                    roadmap.style.position = "absolute";
                    roadmap.style.right = "20px";
                    roadmap.style.top = "100px";
                    roadmap.style.width = "300px";
                    roadmap.style.border = "2px solid #ccc";
                    roadmap.style.padding = "10px";
                    roadmap.style.backgroundColor = "#fff";
                    roadmap.style.borderRadius = "8px";

                    // Ensure the "Add Skill to Progress List" button appears only once
                    if (!addedSkills.has(skillName) && !roadmap.querySelector(".add-skill-btn")) {
                        const addSkillButton = document.createElement("button");
                        addSkillButton.textContent = "Add Skill to Progress List";
                        addSkillButton.classList.add("add-skill-btn");
                        addSkillButton.style.backgroundColor = "#f1981d";
                        addSkillButton.style.color = "#fff";
                        addSkillButton.style.border = "none";
                        addSkillButton.style.padding = "10px 15px";
                        addSkillButton.style.marginTop = "10px";
                        addSkillButton.style.cursor = "pointer";
                        addSkillButton.style.borderRadius = "5px";
                        addSkillButton.style.float = "right";

                        addSkillButton.addEventListener("click", function () {
                            selectedSkills++;
                            progressCount.textContent = selectedSkills;
                            addedSkills.add(skillName);

                            // Add skill to progress list
                            const skillItem = document.createElement("li");
                            skillItem.textContent = skillName;
                            progressList.appendChild(skillItem);

                            addSkillButton.remove(); // Remove button after adding skill
                        });

                        roadmap.appendChild(addSkillButton);
                    }
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            localStorage.clear(); 
            let selectedSkillsCount = 0;
            const selectedSkills = new Set(JSON.parse(localStorage.getItem('skillsList')) || []);

            // Function to update the progress count and progress list
            function updateProgress() {
                const progressList = document.querySelector(".progress-card .progress-stats .stat-box .stat-number");
                progressList.innerText = selectedSkills.size;

                // Clear the current progress items and regenerate them
                const progressContainer = document.querySelector(".progress-card");
                const progressItems = progressContainer.querySelectorAll(".progress-item");
                progressItems.forEach(item => item.remove());

                selectedSkills.forEach(skillName => {
                    const progressItem = document.createElement("div");
                    progressItem.classList.add("progress-item");
                    progressItem.innerHTML = `<i data-lucide="check-circle"></i> ${skillName}`;
                    progressContainer.appendChild(progressItem);
                });
            }

            // Update the progress when the page loads
            updateProgress();

            // Add event listener for adding skills
            document.querySelectorAll(".add-skill-btn").forEach((button) => {
                button.addEventListener("click", function () {
                    const skillName = this.parentElement.querySelector("h3").innerText;

                    // Only add the skill if it hasn't been added before
                    if (!selectedSkills.has(skillName)) {
                        selectedSkills.add(skillName);

                        // Store the updated skills list in localStorage
                        localStorage.setItem('skillsList', JSON.stringify(Array.from(selectedSkills)));

                        // Update the progress display
                        updateProgress();
                    }
                });
            });
        });
        // Function to store skills in local storage
        function addSkill() {
            let skillInput = document.getElementById("skillInput").value;
            if (skillInput.trim() === "") return;

            let skills = JSON.parse(localStorage.getItem("skills")) || [];
            skills.push(skillInput);
            localStorage.setItem("skills", JSON.stringify(skills));

            alert("Skill added successfully!");
        }

        // Function to open progress list page
        function viewProgress() {
            window.location.href = "progress.php";
        }

    </script>
</body>

</html>