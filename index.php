<?php
session_start();

if (!isset($_SESSION['manager_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            min-height: 100vh;
            position: relative;
        }
        header {
            background: #007bff; 
            color: #fff;
            padding: 20px 0;
            text-align: center;
            border-bottom: 5px solid #0056b3; 
        }
        header h1 {
            margin: 0;
            font-size: 24px;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
            padding: 20px;
        }
        .content {
            text-align: center;
            padding: 20px;
        }
        .btn {
            display: inline-block;
            font-size: 16px;
            color: #fff;
            background: #007bff; 
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background: #0056b3; 
        }
        footer {
            background: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
        footer p {
            margin: 10px 0;
        }
        footer a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 5px;
            background: #007bff; 
            margin: 0 10px;
        }
        footer a:hover {
            background: #0056b3; 
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Welcome to the Employee Management System</h1>
        </div>
    </header>

    <div class="container content">
        <p><a href="employees.php" class="btn">View Employees</a></p>
        <p><a href="add_employee.php" class="btn">Add New Employee</a></p>
        <p><a href="logout.php" class="btn">Logout</a></p> 
    </div>

   
</body>
</html>
