<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'maintenance';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM students";
$result = $conn->query($sql);
?>

<?php
session_start();
if (isset($_GET['logout'])) {
    session_destroy(); 
    header('Location: admin.php'); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color:rgb(247, 227, 227);
        margin: 0px;
        padding: 0px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    header {
            background-color: rgb(58, 197, 15);
            color: white;
            width: 100%;
            padding: 1%;
            text-align: center;
        }
        nav {
            margin: 20px 0;
            display: flex;
            gap: 15px;
        }
        nav a {
            text-decoration: none;
            color: white;
            background-color: #333;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 20px;
        }
        nav a:hover {
            background-color: #555;
        }
    .content {
        width: 80%;
        max-width: 1200px;
        margin-top: 20px;
    }
    table {
        border-collapse: collapse;
        width: 95%;
        background-color: #fff;
        margin-top: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }
    th {
        background-color: #333;
        color: white;
    }
    tr:hover {
        background-color: #f4f4f4;
    }
</style>
</head>
<body><header>
    <h1>View Students</h1>
   </header>
   <h2>
      <nav> 
           <a href="?logout=true" style="color: red ">Back</a> 
      </nav>
    </h2>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Student Name</th>
                <th>Department</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Address</th>
                <th>Phone Number</th>
                <th>Register Number</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['student_name']; ?></td>
                        <td><?php echo $row['department']; ?></td>
                        <td><?php echo $row['age']; ?></td>
                        <td><?php echo $row['gender']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td><?php echo $row['phone_number']; ?></td>
                        <td><?php echo $row['register_number']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No students found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
