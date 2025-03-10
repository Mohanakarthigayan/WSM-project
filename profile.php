<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_name'])) {
    header("Location: admin.php");
    exit;
}

$admin_name = $_SESSION['admin_name'];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'maintenance');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch admin details
$sql = "SELECT * FROM admins WHERE admin_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $admin_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $admin_password = $row['password'];
} else {
    die("Admin not found in the database.");
}

$stmt->close();

// Handle Username Change
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_username'])) {
    $new_username = $_POST['new_username'];
    $update_sql = "UPDATE admins SET admin_name = ? WHERE admin_name = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('ss', $new_username, $admin_name);

    if ($update_stmt->execute()) {
        $_SESSION['admin_name'] = $new_username;
        echo "<script>alert('Username updated successfully!'); window.location.profile.php();</script>";
    } else {
        echo "<script>alert('Error updating username! Try again.');</script>";
    }
    $update_stmt->close();
}

// Handle Password Change
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($current_password == $admin_password) {
        if ($new_password == $confirm_password) {
            $update_sql = "UPDATE admins SET password = ? WHERE admin_name = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param('ss', $new_password, $admin_name);

            if ($update_stmt->execute()) {
                echo "<script>alert('Password updated successfully!');</script>";
            } else {
                echo "<script>alert('Error updating password!');</script>";
            }
            $update_stmt->close();
        } else {
            echo "<script>alert('New password and confirm password do not match!');</script>";
        }
    } else {
        echo "<script>alert('Current password is incorrect!');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="css/profile_style.css">
    <script>
        function toggleForm(id) {
            var form = document.getElementById(id);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .container {
    width: 80%; /* Increase width for better visibility */
    max-width: 900px;
    display: flex; /* Align items side by side */
    align-items: center;
    justify-content: start;
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.profile-image {
    width: 180px; /* Increase size for visibility */
    height: 180px;
    border-radius: 50%;
    border: 4px solid #4CAF50;
    margin-right: 20px; /* Space between image and content */
}

.profile-content {
    flex-grow: 1; /* Allow text content to take remaining space */
}


    h2 {
        text-align: center;
        color: #333;
    }

    table {
        width: 80%;
        margin: 20px 0;
        border-collapse: collapse;
    }

    th, td {
        padding: 10px;
        text-align: justify;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f4f4f4;
    }

    button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #45a049;
    }

    .logout-btn {
        display: inline-block;
        background-color: #f44336;
        color: white;
        padding: 10px 15px;
        text-align: center;
        text-decoration: none;
        border-radius: 5px;
        margin-top: 20px;
        font-size: 16px;
    }

    .logout-btn:hover {
        background-color: #d32f2f;
    }

    input[type="text"], input[type="password"] {
        width: 80%;
        padding: 0px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    .form-container {
        margin-top: 20px;
    }

    #username-form, #password-form {
        margin-top: 20px;
    }
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    width: 120%;
    max-width: 900px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column; /* Stack elements */
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.profile-image {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    border: 4px solid #4CAF50;
    margin-bottom: 20px; /* Space below the image */
}

.profile-content {
    text-align: center;
    width: 100%;
}

table {
    width: 60%;
    margin: 20px 0;
    border-collapse: collapse;
}

th, td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 10px;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #45a049;
}

.logout-btn {
    background-color: #f44336;
    color: white;
    padding: 10px 15px;
    text-align: center;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 20px;
    font-size: 16px;
}

.logout-btn:hover {
    background-color: #d32f2f;
}

</style>
<center>
    <div class="container">
    <img src="Library.png" alt="Admin Profile" class="profile-image">
    <div class="profile-content">
        <h2 style="color: rgb(40, 43, 243);">Welcome, <?php echo htmlspecialchars($admin_name); ?>👋</h2>
        <table>
            <tr>
                <th>Admin Username</th>
                <td><?php echo htmlspecialchars($admin_name); ?></td>
            </tr>
            <tr>
                <th>Admin Password</th>
                <td><?php echo htmlspecialchars($admin_password); ?></td>
            </tr>
        </table>
        <p><b>⚠️ Refresh the page after updating Name or Password ⚠️</b></p>
        <button onclick="toggleForm('username-form')">Edit Username</button>
        <div id="username-form" style="display: none;">
            <form method="POST">
                <input type="text" name="new_username" placeholder="Enter New Username" required>
                <button type="submit" name="change_username">Update Username</button>
            </form>
        </div>

        <button onclick="toggleForm('password-form')">Edit Password</button>
        <div id="password-form" style="display: none;">
            <form method="POST">
                <input type="password" name="current_password" placeholder="Current Password" required>
                <input type="password" name="new_password" placeholder="New Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
                <button type="submit" name="change_password">Update Password</button>
            </form>
        </div>
        <a href="admin.php" class="logout-btn">Back</a>
    </div>
</div>
</center>
</body>
</html>