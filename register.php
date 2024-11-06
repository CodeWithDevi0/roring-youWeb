<?php
require_once 'db_connection.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $message = 'Passwords do not match';
    } else {
        // Check if username already exists
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            $message = 'Username already exists. Please choose a different username.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, password, level, exp, title) VALUES (?, ?, 1, 0, 'Novice Adventurer')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                $message = 'Registration successful. You can now login.';
            } else {
                $message = 'Error: ' . $stmt->error;
            }

            $stmt->close();
        }
        $check_stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - QuestLife</title>
    <style>
        /* Copy the styles from index.php */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #1a1a2e;
            color: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #16213e;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        h1 {
            color: #e94560;
            margin-bottom: 1.5rem;
        }
        input, button {
            margin: 10px 0;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
            border: none;
            border-radius: 5px;
        }
        input {
            background-color: #0f3460;
            color: #e0e0e0;
            transition: all 0.3s ease;
        }
        input:focus {
            outline: none;
            box-shadow: 0 0 5px #e94560;
        }
        button {
            background-color: #e94560;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        button:hover {
            background-color: #ff6b6b;
            transform: scale(1.05);
        }
        .toggle-form {
            margin-top: 1rem;
            color: #e94560;
            cursor: pointer;
        }
        .toggle-form:hover {
            text-decoration: underline;
        }
        /* Popup styles */
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
        }
        .popup-content {
            color: #e0e0e0;
            text-align: center;
        }
        .popup-button {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #e94560;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .popup-button:hover {
            background-color: #ff6b6b;
            transform: scale(1.05);
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
        .message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
            color: #fff;
            text-align: center;
        }
        .success {
            background-color: #4CAF50;
        }
        .error {
            background-color: #f44336;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Register for QuestLife</h1>
        <?php
        if ($message) {
            $class = (strpos($message, 'successful') !== false) ? 'success' : 'error';
            echo "<div class='message $class'>$message</div>";
        }
        ?>
        <form id="register-form" method="post" action="register.php">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>
        <p class="toggle-form" onclick="window.location.href='index.php'">Already have an account? Login</p>
    </div>

    <script>
        // If registration was successful, redirect to login page after a short delay
        <?php if (strpos($message, 'successful') !== false): ?>
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 3000);
        <?php endif; ?>
    </script>
</body>
</html>
