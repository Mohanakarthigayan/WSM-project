<?php
session_start();
if (isset($_GET['logout'])) {
    session_destroy(); 
    header('Location: main.php'); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Admin</title>
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
        footer {
            margin-top: auto;
            background: #2d3436;
            color: white;
            width: 100%;
            text-align: center;
            padding: 15px 0;
            font-size: 16px;
            box-shadow: 0px -4px 10px rgba(0, 0, 0, 0.1);
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
        <a href="profile.php">Profile</a>
        <a href="view_report.php">View Reports</a>
        <a href="view_students.php">List Students</a>
        <a href="add_report.php">Add Reports</a>
        <a href="stdls.php">Add Students</a>
        <!-- <a href="mentor.php">Menter</a>
        <a href="time.php">TIme</a> -->
        <a href="?logout=true" class="logout">Past</a>
    </nav>

    <section>
        <h2>Welcome to the Library Management System</h2>
        <p>Select a module from the navigation bar to manage the system efficiently.</p>
    </section>
    
    <footer>
        <p>&copy; 2025 College Library Management</p>
    </footer>
</body>
</html>
