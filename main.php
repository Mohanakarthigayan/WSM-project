<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Scholarship Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            color: #333;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        /* Header Styling */
        header {
            background: #0984e3;;
            color: white;
            text-align: center;
            padding: 20px;
            box-shadow: 0 4px 10px rgb(179, 166, 255);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        header h1 {
            font-size: 32px;
            font-weight: 600;
            flex: 1;
            margin-left: 20px;
        }

        header img {
            width: 120px;
            height: 120px;
            border-radius: 200%; /* Make the image circular */
            object-fit: cover;
        }

        /* Navigation Styling */
        nav {
            background: rgb(179, 166, 255);
            display: flex;
            justify-content: center;
            padding: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        nav a {
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            font-size: 18px;
            margin: 0 15px;
            transition: background-color 0.3s ease;
            font-weight: 500;
        }

        nav a:hover {
            background-color: #555;
            border-radius: 5px;
        }

        /* Container Styling */
        .container {
            flex: 1; /* Makes the container take the remaining space */
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }

        /* Dashboard Card Styling */
        .dashboard-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-10px);
        }

        .dashboard-card h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        /* Button Styling */
        .btn {
            background: #4CAF50;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
            font-size: 16px;
        }

        .btn:hover {
            background: #45a049;
        }

        /* Footer Styling */
        footer {
            background-color: #3b3b3b;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 30px;
            margin-bottom: 0;
        }

        /* Add some spacing at the bottom */
        footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <header>
        <!-- Add your logo path here -->
        <img src="Library.png" alt="547 Logo"> <!-- Replace with your actual logo -->
        <h1>Work Scholarship maintenance</h1>
    </header>

    <nav>
        <a href="adlg.php">Admin</a>
        <a href="contact.php">Contact</a>
        <a href="#" style="color: red;">Back</a>
    </nav>

    <div class="container">
        <div class="dashboard-card">
            <h2>Overview</h2>
            <div class="content">
                <div>
                    <p>Welcome to the Work Scholarship Management System. Here you can track scholarship statuses and maintain student data.</p>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Work Scholarship Management. All Rights Reserved.</p>
    </footer>
</body>
</html>
