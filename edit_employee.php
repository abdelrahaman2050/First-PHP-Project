<?php
session_start();

require 'Database.php';
require 'Employee.php';

if (!isset($_SESSION['manager_id'])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->connect();

$employee = new Employee($db);

$employee_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $picture = isset($_FILES['picture']) ? $_FILES['picture'] : null;

    if ($employee_id) {
        $employee->updateEmployee($employee_id, $name, $phone, $email, $picture);

        $_SESSION['success'] = "Employee updated successfully!";
        header("Location: employees.php");
        exit;
    }
}

$sql = "SELECT * FROM employees WHERE id = :id AND manager_id = :manager_id";
$stmt = $db->prepare($sql);
$stmt->execute([
    ':id' => $employee_id,
    ':manager_id' => $_SESSION['manager_id']
]);
$employeeData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$employeeData) {
    header("Location: employees.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }
        h2 {
            margin-top: 0;
            color: #007bff;
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
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group input:focus {
            border-color: #007bff;
            outline: none;
        }
        .form-group img {
            margin-top: 10px;
            border-radius: 4px;
        }
        .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            width: 100%;
            margin: 10px 0;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .message {
            color: #d9534f;
            margin-bottom: 15px;
        }
        .success {
            color: #5bc0de;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Employee</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <p class="success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></p>
        <?php endif; ?>

        <form action="edit_employee.php?id=<?php echo htmlspecialchars($employee_id); ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($employeeData['name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($employeeData['email'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($employeeData['phone'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="picture">Picture:</label>
                <input type="file" id="picture" name="picture">
                <?php if (!empty($employeeData['picture'])): ?>
                    <img src="<?php echo htmlspecialchars($employeeData['picture']); ?>" alt="Picture" width="100">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn">Update</button>
        </form>
    </div>
</body>
</html>