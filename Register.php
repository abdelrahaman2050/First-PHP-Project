<?php
session_start();

if (isset($_SESSION['manager_id'])) {
    header("Location: home.php");
    exit;
}

require 'Database.php';
require 'Manager.php';

$database = new Database();
$db = $database->connect();
$manager = new Manager($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];


    if ($manager->register($name, $email, $password)) {
        $_SESSION['success'] = "Registration successful! Please log in.";
        header("Location: login.php");
        exit;
    } else {
        $_SESSION['error'] = "Registration failed! Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 50%;
            margin: auto;
            overflow: hidden;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        h2 {
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .btn {
            display: inline-block;
            font-size: 16px;
            color: #fff;
            background: #4a90e2;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            width: 100%;
            cursor: pointer;
            box-sizing: border-box;
        }

        .btn:hover {
            background: #357abd;
        }

        .message {
            text-align: center;
            margin-top: 10px;
            color: red;
        }

        .btn-container {
            text-align: center;
        }

        .btn-container a {
            display: inline-block;
            width: 100%;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Register</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="message"><?php echo $_SESSION['error'];
            unset($_SESSION['error']); ?></p>
        <?php endif; ?>

        <form action="register.php" method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>

        <div class="btn-container">
            <a href="login.php" class="btn">Back to Login</a>
        </div>
    </div>
</body>

</html>