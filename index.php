<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuestLife</title>
    <style>
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
    </style>
</head>
<body>
    <div class="container">
        <h1>QuestLife</h1>
        <div id="login-form">
            <form action="login.php" method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            <p class="toggle-form" onclick="toggleForm('register')">Don't have an account? Register</p>
        </div>
        <div id="register-form" style="display: none;">
            <form action="register.php" method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit">Register</button>
            </form>
            <p class="toggle-form" onclick="toggleForm('login')">Already have an account? Login</p>
        </div>
    </div>

    <script>
        function toggleForm(formType) {
            if (formType === 'login') {
                document.getElementById('login-form').style.display = 'block';
                document.getElementById('register-form').style.display = 'none';
            } else {
                document.getElementById('login-form').style.display = 'none';
                document.getElementById('register-form').style.display = 'block';
            }
        }
    </script>
</body>
</html>
