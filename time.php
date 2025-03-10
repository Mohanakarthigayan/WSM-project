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

// Fetch Events
$query = "SELECT * FROM events ORDER BY event_datetime DESC";
$result = $conn->query($query);
$events = $result->fetch_all(MYSQLI_ASSOC);

// Process Selection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_works'])) {
    $selectedWorks = $_POST['selected_works'];
    $selectedEvents = [];
    
    foreach ($selectedWorks as $workId) {
        $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->bind_param("i", $workId);
        $stmt->execute();
        $selectedEvents[] = $stmt->get_result()->fetch_assoc();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Selection</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #007BFF; color: white; }
        button { background-color: #007BFF; color: white; border: none; padding: 10px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <h2>Select Work</h2>
    <form method="POST">
        <table>
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr>
                        <td><input type="checkbox" name="selected_works[]" value="<?php echo $event['id']; ?>"></td>
                        <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                        <td><?php echo htmlspecialchars($event['event_datetime']); ?></td>
                        <td><?php echo htmlspecialchars($event['description']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <button type="submit">Select</button>
    </form>

    <?php if (!empty($selectedEvents)): ?>
        <h2>Selected Work</h2>
        <table>
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Time Duration (Set by Mentor)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($selectedEvents as $event): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                        <td><?php echo htmlspecialchars($event['event_datetime']); ?></td>
                        <td><?php echo htmlspecialchars($event['description']); ?></td>
                        <td><?php echo htmlspecialchars($event['time_duration'] ?? 'Not Set'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
