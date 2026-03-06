<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Please enter username and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username=?");
        if (!$stmt) die("Prepare failed: " . $conn->error);

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 1) {
            $user = $res->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Login success
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header("Location: index.php");
                } else {
                    header("Location: scanner.php");
                }
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Username not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - CXIS Attendance</title>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    background-color: #e9f2ff;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 100vh;
}

/* Logo on top */
.login-logo {
    margin-top: 50px;
    margin-bottom: 20px;
}
.login-logo img {
    width: 600px;
    height: auto;
}

/* Login box */
.login-container {
    background-color: #f0f8ff;
    border: 2px solid #0d6efd;
    border-radius: 10px;
    padding: 30px 25px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    width: 100%;
    max-width: 400px;
    text-align: center;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

h2 {
    color: #0d6efd;
    margin-bottom: 15px;
}

input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 12px;
    border-radius: 5px;
    border: 1px solid #0d6efd;
    box-sizing: border-box;
    margin-bottom: 20px}

button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 5px;
    background-color: #0d6efd;
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}
button:hover {
    background-color: #084298;
}

p.error {
    color: red;
    text-align: center;
    font-weight: bold;
}

@media (max-width: 500px) {
    .login-logo img {
        width: 180px;
    }
}
</style>
</head>
<body>

<!-- Logo on top -->
<div class="login-logo">
    <img src="img/cx_logo.png" alt="CXIS Logo">
</div>

<!-- Login Box -->
<div class="login-container">
    <h2>Login</h2>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Log In</button>
    </form>
</div>

</body>
</html>