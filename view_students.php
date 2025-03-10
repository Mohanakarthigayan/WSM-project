<?php
// list_students.php

// Database Connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'maintenance';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Search Functionality
$search = '';
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_name LIKE ? OR register_number LIKE ?");
    $searchWildcard = "%$search%";
    $stmt->bind_param("ss", $searchWildcard, $searchWildcard);
} else {
    $stmt = $conn->prepare("SELECT * FROM students");
}
$stmt->execute();
$result = $stmt->get_result();
$students = $result->fetch_all(MYSQLI_ASSOC);

// Edit Logic
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $student_name = $conn->real_escape_string($_POST['student_name']);
        $department = $conn->real_escape_string($_POST['department']);
        $age = intval($_POST['age']);
        $gender = $conn->real_escape_string($_POST['gender']);
        $address = $conn->real_escape_string($_POST['address']);
        $phone_number = $conn->real_escape_string($_POST['phone_number']);
        $register_number = $conn->real_escape_string($_POST['register_number']);

        $update_query = $conn->prepare("UPDATE students SET student_name = ?, department = ?, age = ?, gender = ?, address = ?, phone_number = ?, register_number = ? WHERE id = ?");
        $update_query->bind_param("ssissssi", $student_name, $department, $age, $gender, $address, $phone_number, $register_number, $id);

        if ($update_query->execute()) {
            header('Location: view_students.php?success=update'); // Redirect after update
            exit();
        } else {
            $error_message = "Error updating record: " . $conn->error;
        }
    }
}

// Delete Logic
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header('Location: view_students.php?success=delete'); // Redirect after deletion
        exit();
    } else {
        $error_message = "Error deleting record: " . $conn->error;
    }
}

/*// Logout Logic
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit();
}*/

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .search-container input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 30px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        .search-container input[type="text"]:focus {
            border-color: #007BFF;
            outline: none;  
        }
        .search-container button {
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-left: 10px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .search-container button:hover {
            background-color: white;
            color: #007BFF;
            border: 1px solid #007BFF;
        }
        .search-container button:active {
            background-color: #003f7f;
            transform: scale(1);
        }
/* Reset Button Styling */
        .search-container .reset-button {
            display: inline-block;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease, color 0.3s ease;
            background-color: #f39c12; /* Pastel yellow color */
            color: white;
            text-decoration: none; /* Remove underline */
            margin-left: 10px; /* Space between search and reset button */
        }
        .search-container .reset-button:hover {
            background-color: white;
            color: #f39c12; /* Pastel yellow text */
            border: 1px solid #f39c12;
        }     
/* Table Styling */
        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
/* Keep all rows white */
        table tr {
            background-color: white;
        }
/* Table Headers */
        table th {
            background-color: #007BFF;
            color: #fff;
        }
/* Edit Button - Blue */
        .edit-button {
            background-color: #007BFF;
            color: white;
            padding: 5px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        transition: background-color 0.3s ease, color 0.3s ease;
        }
/* Delete Button - Red */
        .delete-button {
            background-color: #dc3545;
            color: white;
            padding: 5px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
/* Hover Effect - White Background */
        .edit-button:hover {
            background-color: white;
            color: #007BFF;
            border: 1px solid #007BFF;
        }
        .delete-button:hover {
            background-color: white;
            color: #dc3545;
            border: 1px solid #dc3545;
        }
    /* Edit Form Styling */
    .edit-form {
        width: 50%;
        margin: 30px auto;
        padding: 20px;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }
    
    .edit-form h2 {
        text-align: center;
        color: #007BFF;
    }

    .edit-form input,
    .edit-form select,
    .edit-form textarea {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
    }

    .edit-form textarea {
        resize: vertical;
        height: 100px;
    }

    .edit-form button {
        background-color: #007BFF;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease, transform 0.3s ease;
        width: 100%;
    }

    .edit-form button:hover {
        background-color:rgb(6, 179, 0);
        transform: scale(1.05);
    }

    .edit-form button:active {
        background-color: #003f7f;
        transform: scale(1);
    }
    .message {
    padding: 10px;
    margin-top: 20px;
    border-radius: 5px;
    text-align: center;
    font-size: 16px;
    transition: opacity 1s ease, display 1s ease;
}

.message.success {
    background-color: #28a745; /* Green background for success */
    color: white;
}

.message.error {
    background-color: #dc3545; /* Red background for error */
    color: white;
}

</style>
<!--menus style and option-->
        <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #dff9fb, #ffffff);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }
        header {
            background: #0984e3;
            color: white;
            width: 100%;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        nav {
            margin: 20px 0;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        nav a {
            text-decoration: none;
            color: white;
            background: #2d3436;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 18px;
            transition: all 0.3s ease;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        nav a:hover {
            background: #636e72;
            transform: scale(1.05);
        }
        section {
            margin: 20px;
            width: 80%;
            padding: 30px;
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }
        .logout {
            background: red !important;
        }
        </style>
    </head>
<body>
      <header>
        Hello Admininstrator..
    </header>

    <nav>
        <!-- <a href="profile.php">Profile</a> -->
        <a href="view_report.php">View Reports</a>
        <a href="view_students.php">List Students</a>
        <a href="add_report.php">Add Reports</a>
        <a href="stdls.php">Add Students</a>
        <a href="admin.php" class="logout">Back</a>
    </nav>
</body>
<!-- end of menu option -->

<body>
<?php if (isset($_GET['success']) && $_GET['success'] === 'update'): ?>
    <div class="message success" id="message" style="background-color: #28a745; color: white;">Student updated successfully!</div>
<?php elseif (isset($_GET['success']) && $_GET['success'] === 'delete'): ?>
    <div class="message success" id="message" style="background-color: #28a745; color: white;">Student deleted successfully!</div>
<?php elseif (isset($error_message)): ?>
    <div class="message error" id="message"><?php echo $error_message; ?></div>
<?php endif; ?>
<script>
    // Check if there is a message displayed
    window.onload = function() {
        const message = document.getElementById('message');
        if (message) {
            // Set a timeout to fade out the message after 5 seconds
            setTimeout(() => {
                message.style.opacity = '0';
                setTimeout(() => {
                    message.style.display = 'none';
                }, 1000); // Delay for fade-out effect
            }, 2000); // 5 seconds for success message disappearance
        }
    };
</script>

<br>
<?php if (isset($student)): ?>
    <div class="edit-form">
        <h2>Edit Student</h2>
        <form method="POST">
            <input type="text" name="student_name" value="<?php echo htmlspecialchars($student['student_name']); ?>" required>
            <input type="text" name="register_number" value="<?php echo htmlspecialchars($student['register_number']); ?>" required>
            <input type="text" name="department" value="<?php echo htmlspecialchars($student['department']); ?>" required>
            <input type="number" name="age" value="<?php echo htmlspecialchars($student['age']); ?>" required>
            <select name="gender" required>
                <option value="Male" <?php echo $student['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo $student['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                <option value="Other" <?php echo $student['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
            </select>
            <textarea name="address" required><?php echo htmlspecialchars($student['address']); ?></textarea>
            <input type="text" name="phone_number" value="<?php echo htmlspecialchars($student['phone_number']); ?>" required>
            <button type="submit" name="update">Save Changes</button>
        </form>
    <?php else: ?>
        <div class="search-container">
            <form method="get">
                <input type="text"  name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" >Search</button>
                <a href="view_students.php" class="reset-button">Reset</a>
            </form>
        </div><br>

        <?php if (empty($students)): ?>
            <p>No students found!</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Register Number</th>
                        <th>Department</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['student_name']); ?></td>
                            <td><?php echo htmlspecialchars($student['register_number']); ?></td>
                            <td><?php echo htmlspecialchars($student['department']); ?></td>
                            <td><?php echo htmlspecialchars($student['age']); ?></td>
                            <td><?php echo htmlspecialchars($student['gender']); ?></td>
                            <td><?php echo htmlspecialchars($student['address']); ?></td>
                            <td><?php echo htmlspecialchars($student['phone_number']); ?></td>
                            
                         <!-- Edit Button -->
                           <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                 <!-- Edit Button -->
                            <form method="GET">
                                 <button type="submit" name="edit" value="<?php echo $student['id']; ?>" class="edit-button">
                                     Edit
                                 </button>
                             </form>

                 <!-- Separator -->
                    <span>|</span>

                <!-- Delete Button -->
                            <form method="GET" onsubmit="return confirm('Delete this student?');">
                                  <button type="submit" name="delete" value="<?php echo $student['id']; ?>" class="delete-button">
                                    Delete
                                    </button>
                            </form>
                        </div>
                        </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
