<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require_once 'db_connection.php';

// Fetch user-specific data
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, level, exp, title, profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

$_SESSION['username'] = $user_data['username'];
$_SESSION['user_level'] = $user_data['level'];
$_SESSION['user_exp'] = $user_data['exp'];
$_SESSION['user_title'] = $user_data['title'];
$_SESSION['profile_pic'] = $user_data['profile_pic'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuestLife Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #1a1a2e;
            color: #e0e0e0;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            width: 90%;
            margin: 20px auto;
            background-color: #16213e;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            position: relative;
            min-height: 600px;
            overflow-y: auto;
        }
        .profile {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 20px;
        }
        .user-info h2 {
            margin: 0;
            color: #e94560;
        }
        .user-level, .user-title, .user-exp {
            font-size: 0.9em;
            color: #e94560;
            margin: 5px 0;
        }
        button {
            background-color: #e94560;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        button:hover {
            background-color: #ff6b6b;
            transform: scale(1.05);
        }
        .missions {
            margin-top: 20px;
        }
        .mission-input {
            display: flex;
            margin-bottom: 10px;
        }
        .mission-input input {
            flex-grow: 1;
            padding: 10px;
            border: none;
            border-radius: 5px 0 0 5px;
            background-color: #0f3460;
            color: #e0e0e0;
        }
        .mission-input button {
            border-radius: 0 5px 5px 0;
        }
        .mission-list {
            list-style-type: none;
            padding: 0;
        }
        .mission-list li {
            background-color: #0f3460;
            margin-bottom: 5px;
            padding: 10px;
            border-radius: 5px;
        }
        .logout-link {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #e94560;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .logout-link:hover {
            background-color: #ff6b6b;
            transform: scale(1.05);
        }

        /* Custom Pop-up Styles */
        .popup {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background-color: #16213e;
            border: 2px solid #e94560;
            border-radius: 10px;
            padding: 20px;
            z-index: 1000;
            box-shadow: 0 0 20px rgba(233, 69, 96, 0.5);
            max-width: 80%;
            max-height: 80%;
            overflow-y: auto;
        }
        .popup-content {
            color: #e0e0e0;
            text-align: center;
        }
        .popup-buttons {
            margin-top: 15px;
        }
        .popup-buttons button {
            margin: 0 5px;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .mission-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #0f3460;
            border-radius: 5px;
        }
        .mission-content {
            display: flex;
            align-items: flex-start;
            flex-grow: 1;
        }
        .mission-content input[type="checkbox"] {
            margin-right: 10px;
            margin-top: 3px;
        }
        .mission-description {
            margin-top: 5px;
            margin-left: 25px;
            font-size: 0.9em;
            color: #a0a0a0;
        }
        .mission-actions {
            display: flex;
            gap: 5px;
        }
        .mission-actions button {
            padding: 5px 10px;
            font-size: 0.8em;
        }
        .completed-mission {
            text-decoration: line-through;
            opacity: 0.7;
        }
        .exp-bar-container {
            width: 100%;
            background-color: #0f3460;
            border-radius: 5px;
            margin-top: 10px;
            overflow: hidden;
            height: 20px;
            position: relative;
        }
        .exp-bar {
            width: 0%;
            height: 100%;
            background-color: #e94560;
            border-radius: 5px;
            transition: width 0.5s ease-in-out;
            position: absolute;
            left: 0;
            top: 0;
        }
        .recover-button {
            background-color: #4CAF50;
        }
        .recover-button:hover {
            background-color: #45a049;
        }
        .exp-text {
            position: absolute;
            width: 100%;
            text-align: center;
            line-height: 20px;
            color: #e0e0e0;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            z-index: 1;
        }
        .mission-item button {
            background-color: #e94560;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.8em;
        }
        .mission-item button:hover {
            background-color: #ff6b6b;
            transform: scale(1.05);
        }
        .recover-button {
            background-color: #4CAF50 !important;
        }
        .recover-button:hover {
            background-color: #45a049 !important;
        }

        /* Pagination styles */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a, .pagination span {
            color: #e0e0e0;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color .3s;
            border: 1px solid #0f3460;
            margin: 0 4px;
            border-radius: 5px;
        }
        .pagination a:hover {
            background-color: #e94560;
        }
        .pagination .active {
            background-color: #e94560;
            color: white;
            border: 1px solid #e94560;
        }
        .pagination .disabled {
            color: #666;
            pointer-events: none;
        }
        .mission-description {
            font-size: 0.9em;
            color: #a0a0a0;
            margin-top: 5px;
        }
        .edit-profile-form {
            display: none;
        }
        .edit-profile-form input {
            margin-bottom: 10px;
            width: 100%;
            padding: 5px;
            background-color: #0f3460;
            color: #e0e0e0;
            border: 1px solid #e94560;
            border-radius: 5px;
        }
        .profile-pic-upload {
            margin-top: 10px;
        }
        .profile-pic-upload input[type="file"] {
            display: none;
        }
        .profile-pic-upload label {
            background-color: #e94560;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.8em;
        }
        .profile-pic-upload label:hover {
            background-color: #ff6b6b;
        }
        .popup textarea {
            min-height: 100px;
            max-height: 300px;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination button {
            margin: 0 5px;
            padding: 5px 10px;
            background-color: #0f3460;
            color: #e0e0e0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .pagination button:hover {
            background-color: #e94560;
        }

        .pagination button.active {
            background-color: #e94560;
        }

        @media (max-width: 600px) {
            .container {
                width: 95%;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="profile">
            <img src="<?php echo isset($_SESSION['profile_pic']) && $_SESSION['profile_pic'] ? $_SESSION['profile_pic'] : 'https://via.placeholder.com/100'; ?>" alt="Profile Picture" class="profile-pic" id="profile-pic">
            <div class="user-info">
                <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
                <p class="user-level">Level: <span id="user-level"><?php echo $_SESSION['user_level']; ?></span></p>
                <p class="user-title">Title: <span id="user-title"><?php echo $_SESSION['user_title']; ?></span></p>
                <p class="user-exp">Experience:</p>
                <div class="exp-bar-container">
                    <div class="exp-bar" id="exp-bar" style="width: <?php echo ($_SESSION['user_exp'] * 10); ?>%;"></div>
                    <div class="exp-text"><span id="user-exp"><?php echo $_SESSION['user_exp']; ?></span>/10</div>
                </div>
            </div>
        </div>
        <div class="profile-pic-upload">
            <input type="file" id="profile-pic-input" accept="image/*">
            <label for="profile-pic-input">Change Profile</label>
        </div>
        
        <div class="missions">
            <h3>Missions</h3>
            <div class="mission-input">
                <input type="text" id="mission-input" placeholder="Enter a new mission">
                <button onclick="addMission()">Add Mission</button>
            </div>
            <ul class="mission-list" id="mission-list"></ul>
        </div>
        
        <a href="logout.php" class="logout-link">Logout</a>
    </div>

    <div class="overlay" id="overlay"></div>
    <div class="popup" id="popup">
        <div class="popup-content">
            <p id="popup-message"></p>
            <div class="popup-buttons"></div>
        </div>
    </div>

    <div id="pagination" class="pagination"></div>

    <script>
        // Add these variables at the beginning of your script
        let allMissions = [];
        const missionsPerPage = 5;
        let currentPage = 1;

        // Update the updateMissionList function
        function updateMissionList() {
            fetch('get_missions.php')
            .then(response => response.json())
            .then(data => {
                allMissions = data;
                const missionList = document.getElementById("mission-list");
                missionList.innerHTML = "";
                
                const startIndex = (currentPage - 1) * missionsPerPage;
                const endIndex = Math.min(startIndex + missionsPerPage, allMissions.length);
                
                for (let i = startIndex; i < endIndex; i++) {
                    const mission = allMissions[i];
                    const li = document.createElement("li");
                    li.className = "mission-item";
                    li.setAttribute('data-id', mission.id);
                    li.innerHTML = `
                        <div class="mission-content">
                            <input type="checkbox" ${mission.completed ? 'checked disabled' : ''} onchange="completeMission(this, ${mission.id})">
                            <span class="${mission.completed ? 'completed-mission' : ''}">${mission.name}</span>
                            ${mission.description ? `<p class="mission-description">${mission.description}</p>` : ''}
                        </div>
                        <div class="mission-actions">
                            <button class="edit-btn" onclick="editMission(${mission.id})" ${mission.completed ? 'style="display:none;"' : ''}>Edit</button>
                            <button class="recover-btn" onclick="recoverMission(${mission.id})" ${mission.completed ? '' : 'style="display:none;"'}>Recover</button>
                        </div>
                    `;
                    missionList.appendChild(li);
                }
                
                updatePagination();
            })
            .catch(error => {
                console.error('Error:', error);
                showPopup("Failed to load missions. Please try again.");
            });
        }

        // Add the updatePagination function
        function updatePagination() {
            const totalPages = Math.ceil(allMissions.length / missionsPerPage);
            const paginationElement = document.getElementById("pagination");
            paginationElement.innerHTML = "";
            
            if (totalPages > 1) {
                for (let i = 1; i <= totalPages; i++) {
                    const pageButton = document.createElement("button");
                    pageButton.textContent = i;
                    pageButton.onclick = () => changePage(i);
                    if (i === currentPage) {
                        pageButton.classList.add("active");
                    }
                    paginationElement.appendChild(pageButton);
                }
            }
        }

        // Add the changePage function
        function changePage(page) {
            currentPage = page;
            updateMissionList();
        }

        // Call updateMissionList when the page loads
        document.addEventListener('DOMContentLoaded', updateMissionList);

        function addMission() {
            const missionInput = document.getElementById("mission-input");
            const mission = missionInput.value.trim();
            
            if (mission) {
                showPopup("Do you want to add this mission?", function() {
                    fetch('add_mission.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `name=${encodeURIComponent(mission)}&description=`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            missionInput.value = "";
                            showPopup("Mission added successfully!");
                            updateMissionList();
                        } else {
                            showPopup("Failed to add mission. Please try again.");
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showPopup("An error occurred. Please try again.");
                    });
                });
            } else {
                showPopup("Please enter a mission before adding.");
            }
        }

        function completeMission(checkbox, missionId) {
            showPopup("Did you complete this mission?", function() {
                fetch('update_mission.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${missionId}&completed=1`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateMissionList();
                        updateExperience(1);
                    } else {
                        showPopup("Failed to update mission. Please try again.");
                        checkbox.checked = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showPopup("An error occurred. Please try again.");
                    checkbox.checked = false;
                });
            }, function() {
                checkbox.checked = false;
            });
        }

        function editMission(missionId) {
            const missionItem = document.querySelector(`.mission-item[data-id="${missionId}"]`);
            const missionName = missionItem.querySelector('span').textContent;
            const missionDescription = missionItem.querySelector('.mission-description');
            
            showPopup("Edit mission:", function(newValues) {
                if (newValues.name && newValues.name.trim() !== "") {
                    fetch('update_mission.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `id=${missionId}&name=${encodeURIComponent(newValues.name.trim())}&description=${encodeURIComponent(newValues.description.trim())}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateMissionList();
                            showPopup("Mission updated successfully!");
                        } else {
                            showPopup("Failed to update mission. Please try again.");
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showPopup("An error occurred. Please try again.");
                    });
                }
            }, null, {
                name: missionName,
                description: missionDescription ? missionDescription.textContent : ''
            });
        }

        function recoverMission(missionId) {
            showPopup("Are you sure you want to recover this mission?", function() {
                fetch('update_mission.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${missionId}&completed=0`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateMissionList();
                        updateExperience(-1);
                    } else {
                        showPopup("Failed to update mission. Please try again.");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showPopup("An error occurred. Please try again.");
                });
            });
        }

        function updateExperience(change) {
            let userExp = parseInt(document.getElementById('user-exp').textContent);
            let userLevel = parseInt(document.getElementById('user-level').textContent);
            let userTitle = document.getElementById('user-title').textContent;
            
            userExp += change;
            if (userExp >= 10) {
                userLevel++;
                userExp -= 10;
                userTitle = getNewTitle(userLevel);
                showPopup(`Congratulations! You've reached level ${userLevel}! Your new title is: ${userTitle}`);
            } else if (userExp < 0) {
                if (userLevel > 1) {
                    userLevel--;
                    userExp = 9;
                    userTitle = getNewTitle(userLevel);
                    showPopup(`You've returned to level ${userLevel}. Your title is now: ${userTitle}`);
                } else {
                    userExp = 0;
                }
            }
            
            document.getElementById('user-exp').textContent = userExp;
            document.getElementById('user-level').textContent = userLevel;
            document.getElementById('user-title').textContent = userTitle;
            document.getElementById('exp-bar').style.width = `${userExp * 10}%`;
            
            updateUserStats(userLevel, userExp, userTitle);
        }

        function updateUserStats(level, exp, title) {
            fetch('update_stats.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `level=${level}&exp=${exp}&title=${encodeURIComponent(title)}`
            });
        }

        function getNewTitle(level) {
            const titles = [
                "Novice Adventurer",
                "Apprentice Quester",
                "Journeyman Explorer",
                "Expert Pathfinder",
                "Master Trailblazer",
                "Legendary Voyager"
            ];
            return titles[Math.min(level - 1, titles.length - 1)];
        }

        function showPopup(message, onConfirm = null, onCancel = null, inputValues = null) {
            const popup = document.getElementById("popup");
            const popupMessage = document.getElementById("popup-message");
            const popupButtons = document.querySelector(".popup-buttons");
            
            popupMessage.textContent = message;
            
            if (inputValues !== null) {
                const nameInput = document.createElement("input");
                nameInput.type = "text";
                nameInput.value = inputValues.name || "";
                nameInput.placeholder = "Mission name";
                nameInput.style.width = "100%";
                nameInput.style.marginTop = "10px";
                popupMessage.appendChild(nameInput);

                const descInput = document.createElement("textarea");
                descInput.value = inputValues.description || "";
                descInput.placeholder = "Mission description";
                descInput.style.width = "100%";
                descInput.style.marginTop = "10px";
                descInput.style.height = "100px";
                descInput.style.resize = "vertical";
                popupMessage.appendChild(descInput);
                
                popupButtons.innerHTML = `
                    <button onclick="confirmEdit()">Save</button>
                    <button onclick="closePopup()">Cancel</button>
                `;

                window.confirmEdit = function() {
                    if (onConfirm) onConfirm({name: nameInput.value, description: descInput.value});
                    closePopup();
                };
            } else if (onConfirm) {
                popupButtons.innerHTML = `
                    <button onclick="confirmPopup()">Yes</button>
                    <button onclick="cancelPopup()">No</button>
                `;
                window.confirmPopup = function() {
                    onConfirm();
                    closePopup();
                };
                window.cancelPopup = function() {
                    if (onCancel) onCancel();
                    closePopup();
                };
            } else {
                popupButtons.innerHTML = '<button onclick="closePopup()">Okay</button>';
            }
            
            popup.style.display = "block";
            document.getElementById("overlay").style.display = "block";
        }

        function closePopup() {
            document.getElementById("popup").style.display = "none";
            document.getElementById("overlay").style.display = "none";
            const popupMessage = document.getElementById("popup-message");
            while (popupMessage.firstChild) {
                popupMessage.removeChild(popupMessage.firstChild);
            }
        }

        document.getElementById('profile-pic-input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const formData = new FormData();
                formData.append('profile_pic', file);

                fetch('upload_profile_pic.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('profile-pic').src = data.filepath;
                        showPopup("Profile picture updated successfully!");
                    } else {
                        showPopup("Failed to update profile picture. " + (data.message || "Please try again."));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showPopup("An error occurred. Please try again.");
                });
            }
        });
    </script>
</body>
</html>
