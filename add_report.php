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

// Handle event submission
if (isset($_POST['create'])) {
    $event_name = $_POST['event_name'];
    $event_datetime = $_POST['event_datetime'];
    $description = $_POST['description'];

    if (empty($event_name) || empty($event_datetime) || empty($description)) {
        $error_message = "All fields are required!";
    } else {
        $sql = "INSERT INTO events (event_name, event_datetime, description) VALUES ('$event_name', '$event_datetime', '$description')";
        if ($conn->query($sql) === TRUE) {
            $success_message = "New event added successfully!";
        } else {
            $error_message = "Error: " . $conn->error;
        }
    }
}

$events = [];
$result = $conn->query("SELECT * FROM events");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
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
        .form-container input,
        .form-container textarea {
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
<!-- end of menus -->
<br>
<body>
<link rel="stylesheet" href="menu_style.css" type="text/css">
<link rel="htmlsheet" herf="menu_option.php" type="text/html">
   
    
    <?php if ($success_message): ?>
        <div class="message success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <div class="form-container">
        <h2>Submit Event</h2>
        <form method="POST" action="">
            <input type="text" name="event_name" placeholder="Event Name" required>
            <input type="datetime-local" name="event_datetime" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <button type="submit" name="create">Submit Event</button>
        </form>
    </div>
</body>
</html>
