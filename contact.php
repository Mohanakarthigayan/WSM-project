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
    <title>Contact Module</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        header {
            background: #0984e3;
            color: white;
            width: 100%;
            padding: 1%;
            text-align: center;
        }
        nav {
            margin: 20px 0;
            display: flex;
            gap: 15px;
            align-items: right;;
        }
        nav a {
            text-decoration: none;
            color: white;
            background-color: #333;
            padding: 12px 25px;
            border-radius: 10px;
            font-size: 20px;
            transition: background-color 0.3s ease;
        }
        nav a:hover {
            background-color: #555;
        }
        section {
            margin: 20px;
            width: 80%;
            padding: 25px;
            background: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        section h2 {
            color: #333;
            font-size: 26px;
            margin-bottom: 10px;
        }
        section p {
            font-size: 18px;
            color: #555;
            margin: 5px 0;
        }
        footer {
            margin-top: auto;
            background-color: #333;
            color: white;
            width: 100%;
            text-align: center;
            padding: 12px 0;
        }
    </style>
    
</head>
<body>
    <header>
        <h1>📞 Contact Module</h1>
    </header>
    
    <h2>
        <nav>
            <a href="?logout=true" style="color: red;">Back</a>
        </nav>
    </h2>  

    <section>
        <h2>👨‍💼 Staff 1</h2>
        <p>📱 123456789</p>
    </section>
    <section>
        <h2>👩‍💼 Staff 2</h2>
        <p>📱 123456789</p>
    </section>

    <section>
        <h2>👨‍💻 Staff 3</h2>
        <p>📱 123456789</p>
    </section>

    <footer>
        <p>&copy; 2025 Works Scholarship Maintenance</p>
    </footer>
</body>
</html>
