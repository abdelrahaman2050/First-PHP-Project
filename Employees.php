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
$manager_id = $_SESSION['manager_id'];

$query = "SELECT * FROM employees WHERE manager_id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$manager_id]);
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);


if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] === 'delete') {
    $employee->deleteEmployee($_GET['id']);
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Employees List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background: #007bff;
            color: #fff;
            padding: 15px 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        header a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-size: 18px;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            transition: background-color 0.3s, transform 0.3s;
        }

        header a:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .container {
            width: 90%;
            margin: auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        h2 {
            margin-top: 0;
            color: #333;
        }

        .actions {
            margin-bottom: 20px;
            text-align: right;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
        }

        .message p {
            margin: 0;
            padding: 10px;
            border-radius: 5px;
            color: #fff;
            font-weight: bold;
        }

        .message .success {
            background-color: #28a745;
        }

        .message .error {
            background-color: #dc3545;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #007bff;
            color: #fff;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table img {
            max-width: 100px;
            border-radius: 5px;
        }

        .action-buttons a {
            display: inline-block;
            padding: 5px 10px;
            margin: 0 5px;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }

        .edit-button {
            background-color: #28a745;
        }

        .edit-button:hover {
            background-color: #218838;
        }

        .delete-button {
            background-color: #dc3545;
        }

        .delete-button:hover {
            background-color: #c82333;
        }

        .add-employee-button {
            background-color: #007bff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            color: white;
            text-transform: uppercase;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
        }

        .add-employee-button:hover {
            background-color: #0056b3;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <header>
        <a href="index.php">Home</a>
        <a href="logout.php">Logout</a>
    </header>

    <div class="container">
        <h2>Employees List</h2>

        <div class="actions">
            <a href="add_employee.php" class="add-employee-button">Add New Employee</a>
        </div>

        <div class="message">
            <?php if (isset($_SESSION['success'])): ?>
                <p class="success"><?php echo $_SESSION['success'];
                unset($_SESSION['success']); ?></p>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <p class="error"><?php echo $_SESSION['error'];
                unset($_SESSION['error']); ?></p>
            <?php endif; ?>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Picture</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($employee['id']); ?></td>
                        <td><?php echo htmlspecialchars($employee['name']); ?></td>
                        <td><?php echo htmlspecialchars($employee['email']); ?></td>
                        <td><?php echo htmlspecialchars($employee['phone']); ?></td>
                        <td><img src="<?php echo htmlspecialchars($employee['picture']); ?>" alt="Picture"></td>
                        <td class="action-buttons">
                            <a href="edit_employee.php?id=<?php echo htmlspecialchars($employee['id']); ?>"
                                class="edit-button">Edit</a>
                            <a href="employees.php?id=<?php echo htmlspecialchars($employee['id']); ?>&action=delete"
                                class="delete-button"
                                onclick="return confirm('Are you sure you want to delete this employee?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>