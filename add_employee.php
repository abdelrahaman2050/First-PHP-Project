<?php
session_start();

if (!isset($_SESSION['manager_id'])) {
    header("Location: login.php");  
    exit;
}

require 'Database.php';
require 'Employee.php';

$database = new Database();
$db = $database->connect();

$employee = new Employee($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $picture = $_FILES['picture'];

    if ($picture) {
        
        $uploadDir = 'uploads/';
        $uploadedFilePath = $uploadDir . basename($picture['name']);

        if (move_uploaded_file($picture['tmp_name'], $uploadedFilePath)) {
           
            $employee->picture = $uploadedFilePath;
        } else {
            throw new Exception("Error in file paths while uploads - Failed to upload picture");
        }
    }

    $sql = "INSERT INTO `employees` (`name`, `email`, `phone`, `picture`, `manager_id`) VALUES (?, ?, ?, ?, ?)";

    $stmt = $db->prepare($sql);
    if ($stmt->execute([$name, $email, $phone, $uploadedFilePath, $_SESSION['manager_id']])) {
        $_SESSION['done'] = ['Employee added successfully!'];
        header('Location: employees.php');
        exit;
    } else {
        throw new Exception("Failed to add employee!");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Employee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 60%;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        h2 {
            margin-top: 0;
            color: #333;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-group input[type="file"] {
            padding: 5px;
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="email"]:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            display: block;
            width: 100%;
            margin-top: 20px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Employee</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="message"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Phone:</label>
                <input type="text" name="phone" required>
            </div>
            <div class="form-group">
                <label>Picture:</label>
                <input type="file" name="picture" required>
            </div>
            <button type="submit">Add Employee</button>
        </form>
    </div>
</body>
</html>
