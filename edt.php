<?php
// Database Connection
$conn = new mysqli("localhost", "root", "", "maintenance"); // Update database name

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize Variables
$students = [];
$student = null;
$error_message = "";
$success_message = "";
$search = $_GET['search'] ?? "";

// Fetch Students for the Table
if ($search) {
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_name LIKE ? OR register_number LIKE ?");
    $likeSearch = "%$search%";
    $stmt->bind_param("ss", $likeSearch, $likeSearch);
} else {
    $stmt = $conn->prepare("SELECT * FROM students");
}
$stmt->execute();
$result = $stmt->get_result();
$students = $result->fetch_all(MYSQLI_ASSOC);

// Handle Edit Request
if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if (!$student) {
        $error_message = "Student not found.";
    }
}

// Handle Update Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = (int) $_POST['id'];
    $student_name = $_POST['student_name'];
    $register_number = $_POST['register_number'];
    $department = $_POST['department'];
    $age = (int) $_POST['age'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];

    $stmt = $conn->prepare("UPDATE students SET student_name = ?, register_number = ?, department = ?, age = ?, gender = ?, address = ?, phone_number = ? WHERE id = ?");
    $stmt->bind_param("sssisssi", $student_name, $register_number, $department, $age, $gender, $address, $phone_number, $id);

    if ($stmt->execute()) {
        $success_message = "Student updated successfully!";
        header("Location: students_management.php?success=update");
        exit();
    } else {
        $error_message = "Error updating student: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
    <style>
        /* CSS styles for table, form, and buttons */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .search-container {
            margin-bottom: 20px;
            text-align: center;
        }
        .search-container input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .search-container button {
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-container button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #007BFF;
            color: #fff;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        form input, form select, form textarea {
            padding: 10px;
            margin-bottom: 15px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        form button {
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Student Management</h1>

    <!-- Success/Error Messages -->
    <?php if ($success_message): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px;"><?php echo $success_message; ?></div>
    <?php elseif ($error_message): ?>
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px;"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Search Form -->
    <div class="search-container">
        <form method="GET">
            <input type="text" name="search" placeholder="Search by Name or Register Number" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Edit Form -->
    <?php if ($student): ?>
        <h2>Edit Student</h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
            <label>Student Name:</label>
            <input type="text" name="student_name" value="<?php echo htmlspecialchars($student['student_name']); ?>" required>
            <label>Register Number:</label>
            <input type="text" name="register_number" value="<?php echo htmlspecialchars($student['register_number']); ?>" required>
            <label>Department:</label>
            <input type="text" name="department" value="<?php echo htmlspecialchars($student['department']); ?>" required>
            <label>Age:</label>
            <input type="number" name="age" value="<?php echo htmlspecialchars($student['age']); ?>" required>
            <label>Gender:</label>
            <select name="gender" required>
                <option value="Male" <?php echo $student['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo $student['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                <option value="Other" <?php echo $student['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
            </select>
            <label>Address:</label>
            <textarea name="address" required><?php echo htmlspecialchars($student['address']); ?></textarea>
            <label>Phone Number:</label>
            <input type="text" name="phone_number" value="<?php echo htmlspecialchars($student['phone_number']); ?>" required>
            <button type="submit" name="update">Update</button>
        </form>
    <?php endif; ?>

</body>
</html>
