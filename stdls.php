<?php
session_start();
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'maintenance';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success_message = '';
$error_message = '';

/*if (isset($_GET['logout'])) {
    session_destroy(); 
    header('Location: admin.php'); 
    exit();
}*/

// Handle form submission and validation
if (isset($_POST['create'])) {
    $student_name = $_POST['student_name'];
    $department = $_POST['department'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $register_number = $_POST['register_number'];

    // Validate input data
    if (empty($student_name) || empty($department) || empty($age) || empty($gender) || empty($address) || empty($phone_number) || empty($register_number)) {
        $error_message = "All fields are required!";
    } elseif (!preg_match('/^\d{10}$/', $phone_number)) {
        $error_message = "Phone number must be exactly 10 digits!";
    } else {
        // Check if the register number or phone number already exists
        $check_query = "SELECT * FROM students WHERE register_number = '$register_number' OR phone_number = '$phone_number'";
        $check_result = $conn->query($check_query);

        if ($check_result->num_rows > 0) {
            $error_message = "A student with the same Register Number or Phone Number already exists!";
        } else {
            // Insert the new student data into the database
            $sql = "INSERT INTO students (student_name, department, age, gender, address, phone_number, register_number) 
                    VALUES ('$student_name', '$department', $age, '$gender', '$address', '$phone_number', '$register_number')";

            if ($conn->query($sql) === TRUE) {
                $success_message = "New student added successfully!";
            } else {
                $error_message = "Error: " . $conn->error;
            }
        }
    }
}

$students = [];
$result = $conn->query("SELECT * FROM students");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        .form-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 400px;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-container input[type="text"], 
        .form-container input[type="number"], 
        .form-container textarea,
        .form-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .back-button {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 10px 15px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

        .message {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
            border-radius: 5px;
        }

        .success {
            background-color: #28a745;
            color: white;
        }

        .error {
            background-color: #dc3545;
            color: white;
        }

        input[type="number"] {
            max-width: 100px;
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
</body><br>
<!-- end of the menu -->
<body>
    <!-- Message Section -->
    <?php if ($success_message): ?>
        <div class="message success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <div class="form-container">
        <h2>Create Student</h2>
        <form method="POST" action="">
            <input type="text" name="student_name" placeholder="Student Name" value="<?php echo isset($student_name) ? $student_name : ''; ?>" required>
            <input type="text" name="register_number" placeholder="Register Number" value="<?php echo isset($register_number) ? $register_number : ''; ?>" required>

            <!-- Department Dropdown -->
            <select name="department" required>
                <option value="">Select Department</option>
                <option value="Tamil" <?php echo (isset($department) && $department == "Computer Science") ? 'selected' : ''; ?>>Computer Science</option>
                <option value="English" <?php echo (isset($department) && $department == "Electrical Engineering") ? 'selected' : ''; ?>>Electrical Engineering</option>
                <option value="Mathematic" <?php echo (isset($department) && $department == "Mechanical Engineering") ? 'selected' : ''; ?>>Mechanical Engineering</option>
                <option value="" <?php echo (isset($department) && $department == "Civil Engineering") ? 'selected' : ''; ?>>Civil Engineering</option>
                <option value="Chemical Engineering" <?php echo (isset($department) && $department == "Chemical Engineering") ? 'selected' : ''; ?>>Chemical Engineering</option>
                <option value="Biotechnology" <?php echo (isset($department) && $department == "Biotechnology") ? 'selected' : ''; ?>>Biotechnology</option>
                <option value="Mathematics" <?php echo (isset($department) && $department == "Mathematics") ? 'selected' : ''; ?>>Mathematics</option>
                <option value="Physics" <?php echo (isset($department) && $department == "Physics") ? 'selected' : ''; ?>>Physics</option>
                <option value="Chemistry" <?php echo (isset($department) && $department == "Chemistry") ? 'selected' : ''; ?>>Chemistry</option>
                <option value="Architecture" <?php echo (isset($department) && $department == "Architecture") ? 'selected' : ''; ?>>Architecture</option>
                <option value="Management" <?php echo (isset($department) && $department == "Management") ? 'selected' : ''; ?>>Management</option>
            </select>

            <input type="number" name="age" placeholder="Age" maxlength="100" minlength="15"<?php echo isset($age) ? $age : ''; ?>" required>
            <select name="gender" required>
                <option value="">Select Gender</option>
                <option value="Male" <?php echo (isset($gender) && $gender == "Male") ? 'selected' : ''; ?>>Male🚹</option>
                <option value="Female" <?php echo (isset($gender) && $gender == "Female") ? 'selected' : ''; ?>>Female🚺</option>
                <option value="Other" <?php echo (isset($gender) && $gender == "Other") ? 'selected' : ''; ?>>Other⚧️</option>
            </select>
            <textarea name="address" placeholder="Address" required><?php echo isset($address) ? $address : ''; ?></textarea>
            <input type="text" name="phone_number" placeholder="Phone Number (10 digits)" pattern="\d{10}" value="<?php echo isset($phone_number) ? $phone_number : ''; ?>" required>
            <button type="submit" name="create">Add Student</button>
        </form>
    </div>
</body>
</html>
