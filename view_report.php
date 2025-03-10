<?php
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
    $stmt = $conn->prepare("SELECT * FROM events WHERE event_name LIKE ? OR description LIKE ?");
    $searchWildcard = "%$search%";
    $stmt->bind_param("ss", $searchWildcard, $searchWildcard);
} else {
    $stmt = $conn->prepare("SELECT * FROM events ORDER BY event_datetime DESC");
}
$stmt->execute();
$result = $stmt->get_result();
$events = $result->fetch_all(MYSQLI_ASSOC);

// Edit Logic
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $event = $stmt->get_result()->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $event_name = $conn->real_escape_string($_POST['event_name']);
        $event_datetime = $conn->real_escape_string($_POST['event_datetime']);
        $description = $conn->real_escape_string($_POST['description']);

        $update_query = $conn->prepare("UPDATE events SET event_name = ?, event_datetime = ?, description = ? WHERE id = ?");
        $update_query->bind_param("sssi", $event_name, $event_datetime, $description, $id);

        if ($update_query->execute()) {
            header('Location: view_report.php?success=update');
            exit();
        } else {
            $error_message = "Error updating record: " . $conn->error;
        }
    }
}

// Delete Logic
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header('Location: view_report.php?success=delete');
        exit();
    } else {
        $error_message = "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Reports</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .search-container {
            margin-bottom: 20px;
            text-align: center;
        }
        .search-container input {
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .search-container button {
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-container button:hover {
            background-color: #0056b3;
        }
        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #007BFF;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        .actions a {
            text-decoration: none;
            padding: 5px 10px;
            margin-right: 10px;
            color: white;
            border-radius: 5px;
        }
        .actions a.delete {
            background-color: #dc3545;
        }
        .actions a:hover {
            opacity: 0.8;
        }
        .message {
            margin: 20px 0;
            padding: 10px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .edit-button, .delete-button {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .edit-button {
            background-color: #007BFF;
            color: white;
        }
        .delete-button {
            background-color: #dc3545;
            color: white;
        }
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

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .form-container {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-container input, .form-container textarea, .form-container button {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-container button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
        .message {
            margin: 20px 0;
            padding: 10px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
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
<br>
<body>

<?php if (isset($_GET['success']) && $_GET['success'] === 'update'): ?>
    <div class="message success" id="message">Report updated successfully!</div>
<?php elseif (isset($_GET['success']) && $_GET['success'] === 'delete'): ?>
    <div class="message success" id="message">Report deleted successfully!</div>
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
            }, 3000); // 5 seconds
        }
    };
</script>


<?php if (isset($event)): ?>
    <div class="form-container">
        <h2>Edit Event</h2>
        <form method="POST">
            <input type="text" name="event_name" value="<?php echo htmlspecialchars($event['event_name']); ?>" required>
            <input type="datetime-local" name="event_datetime" value="<?php echo htmlspecialchars($event['event_datetime']); ?>" required>
            <textarea name="description" required><?php echo htmlspecialchars($event['description']); ?></textarea>
            <button type="submit" name="update">Save Changes</button>
        </form>
    </div>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Event Name</th>
                <th>Date</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $index => $event): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                    <td><?php echo htmlspecialchars($event['event_datetime']); ?></td>
                    <td><?php echo htmlspecialchars($event['description']); ?></td>
                    <td><div style="display: flex; align-items: center; gap: 10px;">
        <!-- Edit Button -->
                    <form method="GET" action="view_report.php">
                        <button type="submit" name="edit" value="<?= htmlspecialchars($event['id']); ?>" class="edit-button">
                         Edit
                        </button>
                    </form>
        <!-- Separator -->
        <span>|</span>
        <!-- Delete Button -->
        <form method="GET" action="view_report.php" onsubmit="return confirm('Are you sure you want to delete this event?');">
            <button type="submit" name="delete" value="<?= htmlspecialchars($event['id']); ?>" class="delete-button">
                Delete
            </button>
        </form>
    </div></tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

</body>
</html>
