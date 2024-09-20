<?php


class Employee {
    private $pdo;
    private $table = 'employees';

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addEmployee() {
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $picture = $_FILES['picture'];

        $picturePath = '';

        if (isset($picture)) {
            $picturePath = 'uploads/' . basename($picture['name']);

            if (!move_uploaded_file($picture['tmp_name'], $picturePath)) {
                throw new Exception("Image upload failed.");
            }
        }

        $sql = "INSERT INTO employees (name, email, phone, picture, manager_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$name, $email, $phone, $picturePath, $_SESSION['manager_id']]);

        $_SESSION['done'] = ['Employee added successfully!'];
        header('Location: employees.php');
        exit;
    }

    public function updateEmployee($employee_id, $name, $phone, $email, $picture = null) {
        $sql = "UPDATE employees SET name = ?, email = ?, phone = ?";

        $picturePath = null;
        if ($picture) {
           
            $oldPicture = $this->pdo->query("SELECT picture FROM employees WHERE id = '$employee_id'")->fetch(PDO::FETCH_ASSOC);
            if ($oldPicture && file_exists($oldPicture['picture'])) {
                unlink($oldPicture['picture']);
            }

            $picturePath = 'uploads/' . basename($picture['name']);
            move_uploaded_file($picture['tmp_name'], $picturePath);
            $sql .= ", picture = ?";
        }

        $sql .= " WHERE id = ? AND manager_id = ?";
        $stmt = $this->pdo->prepare($sql);

        $params = [$name, $email, $phone];
        if ($picturePath) {
            $params[] = $picturePath;
        }
        $params[] = $employee_id;
        $params[] = $_SESSION['manager_id'];

        $stmt->execute($params);

        $_SESSION['done'] = ['Employee updated successfully!'];
        header('Location: employees.php');
        exit;
    }

    public function deleteEmployee($employee_id) {
        $sql = "DELETE FROM employees WHERE id = ? AND manager_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$employee_id, $_SESSION['manager_id']]);

        $_SESSION['done'] = ['Employee deleted successfully!'];
        header('Location: employees.php');
        exit;
    }


}